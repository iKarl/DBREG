<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/controllers/eventos/evento/
 * @version $Id: statusRegistros.php 1.0 2012-06-06 23:24 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Controller\Base_Controller as Controller;

class StatusRegistros_Controller extends Controller
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
		$statusReg = $this->evt->getStatusRegistro($this->idEvento);

		$total = count($statusReg);

		if ($total > 0)
		{
			$total -= 1;
			$idNuevoStatus = $statusReg[$total]->esr_idStatus + 1;
		}
		else
		{
			$idNuevoStatus = 1;
		}

		$this->display(
			array(
				"seccion" => $this->seccion,
				"statusRegs" => $statusReg,
				"idNuevo" => $idNuevoStatus
			)
		);
	}

	public function agregarStatusReg()
	{
		if (!isset($_POST['esr_clave']))
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
			$this->fova->validarCampo("esr_clave", "req", "Indique solo caracteres", 3, 10, "MATCH");

			$this->fova->validarCampo("esr_nombre", "req", "", 0, 35);

			if ($this->fova->validacion['status'])
			{

				$this->fova->campos['esr_clave'] = $this->func->mayusStr($this->fova->campos['esr_clave']);
				$this->fova->campos['esr_nombre'] = $this->func->capitalizarStr($this->fova->campos['esr_nombre']);

				if ($this->validarClave($this->fova->campos['esr_clave']))
				{
					$this->json = array(
						"status" => false,
						"mensaje" => "Ya existe un status de registro con la clave indicada."
					);
				}
				else if ($this->validarNombre($this->fova->campos['esr_nombre'], 0))
				{
					$this->json = array(
						"status" => false,
						"mensaje" => "Ya existe un status de registro con el nombre indicado."
					);
				}
				else
				{

					if ($this->model->insertar($this->fova->campos, $this->idEvento, $this->user_login['id']))
					{
						$this->json = array(
							"status" => "funcion",
							"nomFuncion" => "altaStatusReg",
							"seccion" => $this->seccion,
							"statusReg" => $this->fova->campos
						);
					}
					else
					{
						$this->json = array(
							"status" => false,
							"mensaje" => "No fue posible agregar el titulo, itentalo de nuevo."
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
		if (!isset($_GET['id']) || empty($_GET['id']))
		{
			header ("HTTP/1.1 514 An Error");
			exit ();
		}
		else
		{

			$idStatusReg = $_GET['id'];

			if ($idStatusReg > 4)
			{
				if ($this->model->eliminar($idStatusReg, $this->idEvento))
				{
					$this->json = array(
						"status" => "funcion",
						"nomFuncion" => "eliminarStatusReg",
						"id" => $idStatusReg
					);
				}
				else
				{
					$this->json = array(
						"status" => "funcion",
						"nomFuncion" => "errorEliminarStatusReg",
						"mensaje" => "Error en el sistema al tratar de liminar el status, intentalo de nuevo."
					);
				}
			}
			else
			{
				$this->json = array(
					"status" => "funcion",
					"nomFuncion" => "errorEliminarStatusReg",
					"mensaje" => "No es posible eliminar el status indicado."
				);
			}

		}

		echo json_encode($this->json);
	}

	public function editar()
	{
		if (!isset($_GET['id']) || empty($_GET['id']))
		{
			header ("HTTP/1.1 An Error An Error");
			exit();
		}
		else
		{

			$idStatusReg = $_GET['id'];

			$this->fova->campos = $_POST;

			$this->fova->validarCampo("esr_nombre_nuevo", "req", "", 0, 35);

			if ($this->fova->validacion['status'])
			{

				$this->fova->campos['esr_nombre_nuevo'] = $this->func->capitalizarStr($this->fova->campos['esr_nombre_nuevo']);

				if ($this->validarNombre($this->fova->campos['esr_nombre_nuevo'], $idStatusReg))
				{
					$this->json = array(
						"status" => "false",
						"mensaje" => "Ya existe una clave con el nombre indicado."
					);
				}
				else
				{

					if ($this->model->actualizar($this->fova->campos['esr_nombre_nuevo'], $idStatusReg, $this->idEvento, $this->user_login['id']))
					{
						$this->json = array(
							"status" => "funcion",
							"nomFuncion" => "actualizarStatusReg",
							"statusReg" => array("nombre" => $this->fova->campos['esr_nombre_nuevo'], "id" => $idStatusReg)
						);						
					}
					else
					{
						$this->json = array(
							"status" => "false",
							"mensaje" => "No fue posible relizar el cambio, intentelo de nuevo."
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

	private function validarClave($clave)
	{

		return $this->model->validarClave($clave, $this->idEvento);
	}

	private function validarNombre($nombre, $idStatusReg)
	{

		return $this->model->validarNombre($nombre, $this->idEvento, $idStatusReg);
	}

}