<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/controllers/eventos/
 * @version $Id: index.php 1.0 2012-03-23 00:06 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
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
		$this->sess->validarPermisoSeccion(1);

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
		$eventos = $this->model->getEventos();
		$total = count($eventos);

		foreach ($eventos as $evento)
		{
			$evento->evt_inicio = $this->func->date2screen($evento->evt_inicio);
			$evento->evt_termina = $this->func->date2screen($evento->evt_termina);
		}

		// Mostramos la vista
		$this->display(
			array(
				"seccion" => $this->seccion,
				"eventos" => $eventos,
				"totalEventos" => $total,
				"tituloSeccion" => "Eventos"
			)
		);
	}

	public function formAlta()
	{
		$this->display(
			array(
				"seccion" => $this->seccion,
				"tiposEvento" => $this->evt->getTiposEvento(),
				"divisas" => $this->func->getDivisas(),
				"statusEvento" => $this->evt->getStatusEvento()
			)
		);
	}

	public function agregarEvento()
	{
		if (!isset($_POST))
		{
			$this->json = array(
				"status" => false,
				"mesnaje" => "El formulario no se envío correctamente."
			);
		}
		else
		{

			$this->helpers("FormValidator", "fv");
			$this->fv->campos = $_POST;

			$this->fv->validarCampo("evt_idTipoEvento", "req", "", 1, 2);

			$this->fv->match = "/^([a-zA-Z0-9]+)\$/";
			$this->fv->validarCampo("evt_clave", "req", "Indique solo letras y números", 5, 15, "MATCH");

			$this->fv->validarCampo("evt_nombre", "req", "", 3, 175);
			$this->fv->validarCampo("evt_inicio", "req", "", 10, 10);
			$this->fv->validarCampo("evt_termina", "req", "", 10, 10);
			$this->fv->validarCampo("evt_divisa", "req", "", 3, 3);
			$this->fv->validarCampo("evt_iva", "", "", 4, 9, "MONE");
			$this->fv->validarCampo("evt_status", "req", "", 1, 1);

			if ($this->fv->validacion['status'])
			{

				// Pasamos a mayusculas la clave
				$this->fv->campos['evt_clave'] = strtoupper($this->fv->campos['evt_clave']);

				if ($this->validarClaveEvento($this->fv->campos['evt_clave']))
				{
					$this->json = array(
						"status" => false,
						"mensaje" => "Ya existe un evento con la clave indicada."
					);
				}
				else if ($this->validarNombreEvento($this->fv->campos['evt_nombre']))
				{
					$this->json = array(
						"status" => false,
						"mensaje" => "Ya existe un evento con el mismo nombre."
					);
				}
				else
				{

					$post = $this->fv->campos;

					$post['evt_inicio'] = $this->func->date2db($post['evt_inicio']);
					$post['evt_termina'] = $this->func->date2db($post['evt_termina']);
					$post['evt_iva'] = $this->func->moneda2db($post['evt_iva']);

					if ($idEvento = $this->model->insertarEvento($post, $this->user_login['id']))
					{

						$this->fv->campos['tipoEvento'] = $this->evt->getTipoEvento($post['evt_idTipoEvento']);
						$this->fv->campos['statusEvento'] = $this->evt->getNombreStatusEvento($post['evt_status']);

						$this->json = array(
							"status" => "funcion",
							"nomFuncion" => "altaEvento",
							"seccion" => $this->seccion,
							"idEvento" => $idEvento,
							"evento" => $this->fv->campos
						);
					}
					else
					{
						$this->json = array(
							"status" => false,
							"mensaje" => "No fue posible agregar el evento, intentalo de nuevo."
						);
					}

				}

			}
			else
			{

				$this->json = $this->fv->validacion;

			}

		}

		echo json_encode($this->json);
	}

	private function validarClaveEvento($clave)
	{
		return $this->model->validarClave($clave);
	}

	private function validarNombreEvento($nombre)
	{
		return $this->model->validarNombre($nombre);
	}

}
?>