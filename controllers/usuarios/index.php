<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/controllers/eventos/
 * @version $Id: index.php 1.0 2012-03-23 00:06 _Karl_ $;
 * @author: Daniel Hernández <boox@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Controller\Base_Controller as Controller;

class Index_Controller extends Controller
{
	public $seccion = "";
	private $user_login = array();
	private $json = array();
	public $func = null;

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
		$this->sess->validarPermisoSeccion(6);

		$this->func = $this->fc;

		// Sección
		$this->seccion = parent::$section;

		// Datos de usuario en sesion
		$this->user_login = $this->sess->get('user');

		$this->model = $this->model();

		$this->viewTwig();
	}

	/*
	 * Metodo inicio
	 * Inicio de la sección
	 */
	public function inicio()
	{
		// obtenemos la lista de eventos
		$usuarios = $this->model->getUsuarios();

		// Mostramos la vista
		$this->display(
			array(
				"seccion" => $this->seccion,
				"tituloSeccion" => "Administración usuarios",
				'usuarios' => $usuarios
			)
		);
	}

	public function setUsuario()
	{
		$this->json = array(
			'status' => false,
			'mensaje' => 'Error al agregar el usuario.'
		);

		if (!empty($_POST))
		{
			$_POST['usr_fechaAlta'] = $this->func->date2db();

			if (($id_usuario = $this->model->setUsuario($_POST, $this->user_login['id'])) !== false)
			{
				$this->json = array(
					'status' => true,
					'id_usuario' => $id_usuario,
					'usuario' => $_POST
				);
			}
		}

		echo json_encode($this->json);
	}

	public function getUsuario()
	{
		$usuario = $this->model->getUsuario($_GET['id']);
		$usuario_secciones = implode(',', $this->model->getSeccionesUsuario($usuario->usr_idUsuario));

		$this->display(
			array(
				'seccion' => $this->seccion,
				'usuario' => $usuario,
				'secciones' => $this->model->getSecciones(),
				'usuario_secciones' => $usuario_secciones
			)
		);
	}

	public function updateUsuario()
	{
		$this->json = array(
			'status' => false,
			'mensaje' => 'Error al guardar el usuario.'
		);

		if (!empty($_POST))
		{
			$_POST['usr_fechaModificacion'] = $this->func->date2db();

			if ($this->model->updateUsuario($_POST, $this->user_login['id']))
			{
				$this->json = array(
					'status' => true,
					'mensaje' => 'El usuario se guardo con exito!'
				);
			}
		}

		echo json_encode($this->json);
	}

	public function setPermisos()
	{
		$this->json = array(
			'status' => false,
			'mensaje' => 'Error al agregar secciones al usuario.'
		);

		if (!empty($_POST))
		{
			$this->model->ereaseSeccionesUsuario($_POST['usr_idUsuario']);

			foreach ($_POST['lista_permisos'] as $seccion)
			{
				$this->model->setPermiso($_POST['usr_idUsuario'], $seccion);
			}

			$this->json = array(
				'status' => true,
				'mensaje' => 'Secciones para el usuario agregadas con exito!'
			);
		}

		echo json_encode($this->json);
	}

}
?>