<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/controllers/eventos/evento/
 * @version $Id: titulos.php 1.0 2012-04-05 20:33 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Controller\Base_Controller as Controller;

class Titulos_Controller extends Controller
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
		$idiomas = $this->evt->getIdiomas($this->idEvento);
		$titulos = $this->evt->getTitulos($this->idEvento);

		$total = count($titulos);

		if ($total > 0)
		{
			$total -= 1;
			$idNuevoTitulo = $titulos[$total]->ect_idTitulo + 1;
		}
		else
		{
			$idNuevoTitulo = 1;
		}

		$this->display(
			array(
				"seccion" => $this->seccion,
				"idiomas" => $idiomas,
				"titulos" => $titulos,
				"idNuevo" => $idNuevoTitulo
			)
		);
	}

	public function agregarTitulo()
	{
		if (!isset($_POST['ect_clave']))
		{
			$this->json =  array(
				"status" => false,
				"mensaje" => "El formulario no se envío adecuadamente."
			);
		}
		else
		{

			$this->fova->campos = $_POST;

			$this->fova->match = "/^([a-zA-Z\. ]+)\$/";
			$this->fova->validarCampo("ect_clave", "req", "", 0, 20, "MATCH");

			$this->fova->validarCampo("ect_nombre", "req", "", 0, 35);
			$this->fova->validarCampo("ect_idioma", "req", "", 2, 3);

			if ($this->fova->validacion['status'])
			{

				$this->fova->campos['ect_nombre'] = $this->func->capitalizarStr($this->fova->campos['ect_nombre']);

				if ($this->validarClave($this->fova->campos['ect_clave']))
				{
					$this->json = array(
						"status" => false,
						"mensaje" => "Ya existe un titulo con la clave indicada."
					);
				}
				else if ($this->validarNombre($this->fova->campos['ect_nombre']))
				{
					$this->json = array(
						"status" => false,
						"mensaje" => "Ya existe un titulo con el nombre indicada."
					);
				}
				else
				{

					$this->fova->campos['ect_clave'] = trim($this->fova->campos['ect_clave']);

					if ($this->model->insertar($this->fova->campos, $this->idEvento, $this->user_login['id']))
					{
						$this->json = array(
							"status" => "funcion",
							"nomFuncion" => "altaTitulo",
							"seccion" => $this->seccion,
							"titulo" => $this->fova->campos
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

			$idTitulo = $_GET['id'];

			if ($this->model->eliminar($idTitulo, $this->idEvento))
			{
				$this->json = array(
					"status" => "funcion",
					"nomFuncion" => "eliminarTitulo",
					"id" => $idTitulo
				);
			}
			else
			{
				header ("HTTP/1.1 514 An Error");
				exit ();
			}

		}

		echo json_encode($this->json);
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