<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/controllers/footer/
 * @version $Id: index.php 1.0 2012-02-03 00:32 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Controller\Base_Controller as Controller;

class Footer_Controller extends Controller
{

	public function __construct()
	{

		$this->viewTwig();
		$this->model = $this->model("reportes", "eventos/evento/reportes/index");
		$this->model->evt = $this->model("index", "eventos/evento/index");
	}

	public function inicio()
	{
		$evento = $this->model->evt->getEvento(2);

		$series = $this->model->concentrado($evento->evt_clave, $evento->evt_idioma);
		$acompanantes = $this->model->getAcompanantes($evento->evt_clave, $evento->evt_idioma);
		$status = $this->model->getStatusPago($evento->evt_clave, $evento->evt_idioma);

		$series = array_merge($series, $acompanantes);

		$total = 0;
		foreach ($series as $serie => $value)
		{
			$total += $value['value'];
		}

		$asistencia = $this->model->getAsistencias($evento->evt_clave, $evento->evt_idioma);
		$acompanantes_asis = $this->model->getAcompanantesAsitencia($evento->evt_clave, $evento->evt_idioma);
		$asistencia = array_merge($asistencia, $acompanantes_asis);

		$total_asis = 0;
		foreach ($asistencia as $serie => $value)
		{
			$total_asis += $value['value'];
		}

		// Mostramos la vista
		$this->display(
			array(
				'layout' => $this->layoutView,
				'series' => json_encode($series),
				'total' => $total,
				'asistencias' => json_encode($asistencia),
				'total_asis' => $total_asis,
				'status' => json_encode($status)
			)
		);
	}

}
?>
