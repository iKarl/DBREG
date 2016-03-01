<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/controllers/catalogos/codPostales/
 * @version $Id: index.php 1.0 2012-03-19 15:57 _Karl_ $;
 * @author: Carlos A. Garcia Hernandez <carlos.agh@gmail.com, son_gohan_khan@hotmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Controller\Base_Controller as Controller;

class Index_Controller extends Controller
{
	public $seccion = "";
	private $user_login = array();
	private $json = array();

	public function __construct()
	{
		$this->librarys("Sessions", "sess");

		$this->sess->validarSesionUsuario();
		$this->sess->validarPermisoSeccion(3);

		$this->seccion = parent::$section;

		$this->user_login = $this->sess->get("user");

		$this->model = $this->model();

		$this->viewTwig();
	}

	public function inicio()
	{
		$this->display(
			array(
				'seccion' => $this->seccion
			)
		);
	}

	public function buscar()
	{
		if (isset($_POST['codigoPostal']) && !empty($_POST['codigoPostal']))
		{
			$cp = $_POST['codigoPostal'];

			$cps = $this->model->buscar($cp);

			if (!empty($cps))
			{
				$this->json = array(
					"status" => "funcion",
					"nomFuncion" => "listaCodigosPostales",
					"seccion" => $this->seccion,
					"codigosPostales" => $cps
				);
			}
			else
			{
				$this->json = array(
					"status" => "funcion",
					"nomFuncion" => "noCodigosPostales",
					"mensaje" => "No se encontraron registros con el Codigo Postal: " . $cp
				);
			}
		}
		else
		{
			header ("HTTP/1.1 513 An Error");
			exit ();
		}

		echo json_encode($this->json);
	}

	public function formEditar()
	{
		if (!isset($_GET['id']) || empty($_GET['id']))
		{
			header ("HTTP/1.1 513 An Error");
			exit ();
		}

		$id = $_GET['id'];
		$cp = $this->model->obtenerCP($id);

		$this->display(
			array(
				'seccion' => $this->seccion,
				"cp" => $cp
			)
		);
	}

	public function actualizar()
	{
		if (!isset($_POST))
		{
			$this->json = array(
				"status" => false,
				"mensaje" => "El formulario no se envío adecuadamente."
			);
		}
		else
		{
			$this->helpers("FormValidator", "fv");
			$this->fv->campos = $_POST;

			$this->fv->validarCampo("cp_codigoPostal", "req", "", 0, 10, "INT");
			$this->fv->validarCampo("cp_asenta", "req", "", 0, 150);
			$this->fv->validarCampo("cp_tipoAsenta", "req", "", 0, 100);
			$this->fv->validarCampo("cp_nombreMnpio", "req", "", 0, 100);
			$this->fv->validarCampo("cp_nombreEstado", "req", "", 0, 100);
			$this->fv->validarCampo("cp_nombreCiudad", "", "", 0, 100);

			if ($this->fv->validacion['status'])
			{
				if ($this->model->actualizar($_POST))
				{
					$this->json = array(
						"status" => "funcion",
						"nomFuncion" => "actualizarCodigoPostal",
						"cp" => $_POST
					);
				}
				else
				{
					$this->json = array(
						"status" => false,
						"mensaje" => "No fue posible actualizar el codigo postal."
					);
				}
			}
			else
			{
				$this->json = $this->fv->validacion;
			}

		}

		echo json_encode($this->json);
	}
}
?>