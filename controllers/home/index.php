<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/controllers/home/
 * @version $Id: index.php 1.0 2012-02-03 00:22 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Controller\Base_Controller as Controller;

class Index_Controller extends Controller
{

	public $seccion = "";
	private $json = array();

	public function __construct()
	{

		$this->librarys(
			array(
				"Sessions" => "sess",
				"Functions" => "func"
			)
		);

		$this->seccion = parent::$section;

		$this->model = $this->model("Home");
		$this->model->rpt = $this->model("reportes", "eventos/evento/reportes/index");
		$this->model->evt = $this->model("index", "eventos/evento/index");

		// Preparamos la vista
		$this->viewTwig();

	}

	public function inicio()
	{

		// Incluimos el header
		$this->includeHeader();

		// obtenemos la lista de eventos
		$eventos = $this->model->getEventos();

		$evento = $this->model->evt->getEvento(1);

		$series = $this->model->rpt->concentrado($evento->evt_clave, $evento->evt_idioma);
		$acompanantes = $this->model->rpt->getAcompanantes($evento->evt_clave, $evento->evt_idioma);

		$series = array_merge($series, $acompanantes);

		$total = 0;
		foreach ($series as $serie => $value)
		{
			$total += $value['value'];
		}

		$asistencia = $this->model->rpt->getAsistencias($evento->evt_clave, $evento->evt_idioma);
		$acompanantes_asis = $this->model->rpt->getAcompanantesAsitencia($evento->evt_clave, $evento->evt_idioma);
		$asistencia = array_merge($asistencia, $acompanantes_asis);

		$total_asis = 0;
		foreach ($asistencia as $serie => $value)
		{
			$total_asis += $value['value'];
		}

		// Mostramos la vista
		$this->display(
			array(
				"seccion" => $this->seccion,
				"eventos" => $eventos,
				'total' => $total,
				'total_asis' => $total_asis
			)
		);

		// Incluimos la footer
		$this->includeFooter();

	}

	public function import($request)
	{
		$this->model->newConnect('DBREG', '54.67.5.42', 'ROD', 'DBREG15');

		$registros = $this->model->import();

		if (!empty($registros))
		{
			$this->model->newConnect('CARDIO', 'localhost', 'prueba', 'testings');

			foreach ($registros as $registro)
			{
				/*if ($registro->tipo_registro == 1) {
					$registro->cat_registro = 'CONGRES';
				} else if ($registro->tipo_registro == 2) {
					$registro->cat_registro = 'ACOM';
				} else if ($registro->tipo_registro == 3) {
					$registro->cat_registro = 'ACMEN';
				} else if ($registro->tipo_registro == 4) {
					$registro->cat_registro = 'INVESP';
				} else {
					$registro->cat_registro = 'CONGRES';
				}*/

				$this->model->setRegistro($registro);
			}

			$this->model->newConnect('DBREG', '54.67.5.42', 'ROD', 'DBREG15');
			foreach ($registros as $registro)
			{
				$this->model->leido($registro->id_registro);
			}

			echo 'Un total de: ' . count($registros) . ' importados.';
			return;
			exit();
		}
		else
		{
			echo 'Sin registros nuevos!';
		}
	}
}
?>