<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/controllers/eventos/hospedaje/
 * @version $Id: index.php 1.0 2014-03-18 21:31 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Controller\Base_Controller as Controller;

class Index_Controller extends Controller
{
	public $seccion = "";
	public $idEvento = 0;
	public $func = null;
	public $fova = null;
	private $user_login = array();
	private $json = array();

	/**
	 * Metodo constructor
	 */
	public function __construct()
	{
		$this->librarys(
			array(
				"Sessions" => "sess",
				"Functions" => "fc",
				"Eventos" => "evt"
			)
		);

		$this->sess->validarSesionUsuario();
		$this->sess->validarPermisoSeccion(1);

		$this->helpers("FormValidator", "fv");

		$this->func = $this->fc;
		$this->fova = $this->fv;

		// Sección
		//$this->seccion = parent::$section;

		// Datos de usuario en sesion
		$this->user_login = $this->sess->get('user');

		if ($this->sess->get("idEvento"))
		{
			$this->idEvento = $this->sess->get("idEvento");
		}

		$this->model = $this->model("Rfid", "eventos/evento/rfid");

		$this->viewTwig();
	}

	public function inicio()
	{
		// Obtenemos datos del evento
		$evento = $this->model->getEvento($this->idEvento);

		$this->display(
			array(
				'tablas' => $this->model->showTables($evento->evt_clave)
			)
		);
	}

	public function cerrarSesion($request)
	{
		$this->json = array(
			'status' => false,
			'mensaje' => 'No fue posible cerrar las sesiones.'
		);

		if (!empty($request->query['tablas']))
		{
			foreach ($request->query['tablas'] as $tabla)
			{
				if ($this->model->cerrarSesion($request->query, $tabla))
				{
					$this->json = array(
						'status' => true,
						'mensaje' => 'Cierre de sesiones con exito!'
					);
				}
			}
		}

		echo json_encode($this->json);
	}
}