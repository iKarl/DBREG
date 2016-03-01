<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/controllers/eventos/evento/
 * @version $Id: formasPago.php 1.0 2012-06-06 23:19 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Controller\Base_Controller as Controller;

class FormasPago_Controller extends Controller
{

	public $seccion = "";
	public $idEvento = 0;
	public $func = null;
	public $fova = null;
	private $user_login = array();
	private $json = array();

	/**
	 * 
	 * 
	 * Metodo constructor
	 *
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
		$this->seccion = parent::$section;

		// Datos de usuario en sesion
		$this->user_login = $this->sess->get('user');

		if ($this->sess->get("idEvento"))
		{
			$this->idEvento = $this->sess->get("idEvento");
		}

		$this->model = $this->model();

		$this->viewTwig();
	}

	public function inicio()
	{
		$formasPago = $this->evt->getFormasPago($this->idEvento);

		foreach ($formasPago as $key => $formaPago)
		{
			$formasPago[$key]->nombres = $this->evt->getNombresFormasPago($this->idEvento, $formaPago->efp_clave);

			$total = count($formasPago[$key]->nombres); //fpn_idNombre

			if ($total > 0)
			{
				$formasPago[$key]->idNuevoNombre = $formasPago[$key]->nombres[--$total]->fpn_idNombre + 1;
			}
			else
			{
				$formasPago[$key]->idNuevoNombre = 1;
			}

		}

		$this->display(
			array(
				"seccion" => $this->seccion,
				"formasPago" => $formasPago
			)
		);
	}

	public function agregar()
	{
		if (!isset($_POST['efp_clave']))
		{
			$this->json =  array(
				"status" => false,
				"mensaje" => "El formulario no se envío adecuadamente."
			);
		}
		else
		{

			$this->fova->campos = $_POST;

			$this->fova->match = "/^([a-zA-Z]+)\$/";
			$this->fova->validarCampo("efp_clave", "req", "Solo se permiten caracteres", 2, 2, "MATCH");

			if ($this->fova->validacion['status'])
			{

				$this->fova->campos['efp_clave'] = $this->func->mayusStr($this->fova->campos['efp_clave']);

				if ($this->validarClave($this->fova->campos['efp_clave']))
				{
					$this->json = array(
						"status" => false,
						"mensaje" => "Ya existe una forma de pago con la clave indicada."
					);
				}
				else
				{

					if ($this->model->insertar($this->fova->campos, $this->idEvento, $this->user_login['id']))
					{
						$this->json = array(
							"status" => "funcion",
							"nomFuncion" => "altaFormaPago",
							"seccion" => $this->seccion,
							"formaPago" => $this->fova->campos
						);
					}
					else
					{
						$this->json = array(
							"status" => false,
							"mensaje" => "No fue posible agregar la forma de pago, itentalo de nuevo."
						);
					}
				}

			}
			else
			{
				$this->json = $this->fova->validacion;
			}

		}

		echo json_encode($this->json);
	}

	public function eliminar()
	{
		if (!isset($_GET['cve']) || empty($_GET['cve']))
		{
			header ("HTTP/1.1 514 An Error");
			exit ();
		}
		else
		{
			$clave = $_GET['cve'];

			if ($this->model->eliminarNombresFormaPago($clave, $this->idEvento))
			{
				if ($this->model->eliminar($clave, $this->idEvento))
				{
					$this->json = array(
						"status" => "funcion",
						"nomFuncion" => "eliminarFormaPago",
						"formaPago" => $clave
					);
				}
				else
				{
					$this->json = array(
						"status" => false,
						"mensaje" => "No fue posible eliminar la forma de pago, intentalo de nuevo."
					);
				}
			}
			else
			{
				$this->json = array(
					"status" => false,
					"mensaje" => "No fue posible eliminar los nombres asociados a la forma de pago, intentalo de nuevo."
				);
			}
		}

		echo json_encode($this->json);
	}

	public function formAgregarNombre()
	{
		if (!isset($_GET['cve']) || empty($_GET['cve']) &&
			!isset($_GET['id']) || empty($_GET['id']))
		{
			header ("HTTP/1.0 514 An Error");
		}
		else
		{
			$clave = $_GET['cve'];
			$idNombre = $_GET['id'];

			$idiomasExistentes = $this->model->getIdiomasNombres($clave, $this->idEvento);

			$this->json = array(
				"status" => "funcion",
				"nomFuncion" => "formAgregarNombreFormaPago",
				"seccion" => $this->seccion,
				"listaIdiomas" => $idiomasExistentes,
				"clave" => $clave,
				"idNombre" => $idNombre,
				"idiomas" => $this->evt->listaIdiomasSeleccion($this->idEvento, '', $idiomasExistentes) // $this->model->getIdiomasNombres($clave, $this->idEvento);
			);

			echo json_encode($this->json);
		}
	}

	public function agregarNombre()
	{
		if (!isset($_POST))
		{
			header ("HTTP/1.1 514 An Error");
		}
		else
		{

			$this->fova->campos = $_POST;

			$this->fova->validarCampo("fpn_clave", "req", "", 2, 3);
			$this->fova->validarCampo("fpn_idNombre", "req", "", 0, 0, "INT");
			$this->fova->validarCampo("fpn_nombre", "req", "", 0, 45);
			$this->fova->validarCampo("fpn_idioma", "req", "", 2, 2);

			if ($this->fova->validacion['status'])
			{

				if ($this->validarNombre($this->fova->campos['fpn_nombre']))
				{
					$this->json = array(
						"status" => false,
						"mensaje" => "Otra forma de pago tiene el nombre indicado, por favor indique otro nombre."
					);
				}
				else
				{

					$this->fova->campos['fpn_nombre'] = $this->func->capitalizarStr($this->fova->campos['fpn_nombre']);

					if ($this->model->insertarNombre($this->fova->campos, $this->idEvento))
					{
						$this->fova->campos['fpn_nuevoID'] = $this->fova->campos['fpn_idNombre'] + 1;

						$this->json = array(
							"status" => "funcion",
							"nomFuncion" => "finaAltaNombreFormaPago",
							"seccion" => $this->seccion,
							"formaPago" => $this->fova->campos
						);
					}
					else
					{
						$this->json = array(
							"status" => false,
							"mensaje" => "No fue posible agregar el nombre intentalo de nuevo."
						);
					}

				}

			}
			else
			{
				$this->json = $this->fova->validacion;
			}

			echo json_encode($this->json);
		}
	}

	public function eliminarNombre()
	{
		if (!isset($_GET['cve']) || empty($_GET['cve']) &&
			!isset($_GET['id']) || empty($_GET['id']))
		{
			header ("HTTP/1.0 514 An Error");
		}
		else
		{
			$clave = $_GET['cve'];
			$id = $_GET['id'];

			if ($this->model->eliminarNombre($clave, $id, $this->idEvento))
			{
				$this->json = array(
					"status" => "funcion",
					"nomFuncion" => "eliminarNombreFormaPago",
					"nombreFormaPago" => $_GET
				);
			}
			else
			{
				$this->json = array(
					"status" => false,
					"mensaje" => "No fue posible eliminar el nombre de la categoria, intentelo de nuevo."
				);
			}

			echo json_encode($this->json);
		}
	}

	private function validarClave($clave)
	{

		return $this->model->validarClave($clave, $this->idEvento);
	}

	private function validarNombre($nombre)
	{

		return $this->model->validarNombre($nombre, $this->idEvento);
	}

}