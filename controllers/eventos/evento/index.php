<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/controllers/eventos/evento/
 * @version $Id: index.php 1.0 2012-04-05 20:33 _Karl_ $;
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

	/**
	 * 
	 * 
	 * Metodo inicio
	 *
	 */
	function inicio()
	{
		if (!isset($_POST['id']) || empty($_POST['id']))
		{
			if (!$this->sess->get("idEvento"))
			{
				header ("HTTP/1.1 514 An Error");
			}
		}
		else
		{
			$this->idEvento = $_POST['id'];
			$this->sess->set("idEvento", $this->idEvento);
		}

		// obtener datos del evento
		$evento = $this->model->getEvento($this->idEvento);

		$evento->evt_inicio = $this->func->date2screen($evento->evt_inicio);
		$evento->evt_termina = $this->func->date2screen($evento->evt_termina);

		// Usuario que dio de alta
		$evento->evt_fechaAlta = $this->func->date2screen($evento->evt_fechaAlta, true, " a las ");
		$evento->evt_usuarioAlta = $this->func->getNombreUsuario($evento->evt_usuarioAlta);

		// Ultimo usuario que modifico
		$evento->evt_fechaModificacion = $this->func->date2screen($evento->evt_fechaModificacion, true, " a las ");
		$evento->evt_usuarioModifico = $this->func->getNombreUsuario($evento->evt_usuarioModifico);

		// obtener categorias
		$categorias = $this->model->getCategorias($this->idEvento);

		// Seteamos los nombres de las categorias
		foreach ($categorias as $key => $categoria)
		{
			$categorias[$key]->ecc_costo_fecha_1 = $this->func->moneda2screen($categorias[$key]->ecc_costo_fecha_1);
			$categorias[$key]->ecc_costo_fecha_2 = $this->func->moneda2screen($categorias[$key]->ecc_costo_fecha_2);
			$categorias[$key]->ecc_costo_fecha_3 = $this->func->moneda2screen($categorias[$key]->ecc_costo_fecha_3);
			$categorias[$key]->ecc_costo_sitio = $this->func->moneda2screen($categorias[$key]->ecc_costo_sitio);
			$categorias[$key]->ecc_costo_otro = $this->func->moneda2screen($categorias[$key]->ecc_costo_otro);

			$categorias[$key]->nombres = $this->model->getNombresCategorias($categoria->ecc_clave, $this->idEvento);

			if (!empty($categorias[$key]->nombres))
			{
				$ultimoNombre = end($categorias[$key]->nombres);
				$categorias[$key]->idNombre = $ultimoNombre->enc_idNombreCategoria+1;
			}
			else
			{
				$categorias[$key]->idNombre = 1;
			}

		}

		// Mostramos la vista
		$this->display(
			array(
				"seccion" => $this->seccion,
				"evento" => $evento,
				"categorias" => $categorias,
				"idiomas" => $this->model->getIdiomas($this->idEvento),
				"tituloSeccion" => "Evento"
			)
		);
	}

	/**
	 * 
	 * 
	 * Metodo que muestra el formulario para los datos generales
	 *
	 */
	public function formDatosGenerales()
	{
		// get datos del evento
		$evento = $this->model->getEvento($this->idEvento);

		$evento->evt_inicio = $this->func->date2screen($evento->evt_inicio);
		$evento->evt_termina = $this->func->date2screen($evento->evt_termina);

		$this->display(
			array(
				"seccion" => $this->seccion,
				"evento" => $evento,
				"tiposEvento" => $this->evt->getTiposEvento(),
				"statusEvento" => $this->evt->getStatusEvento(),
				"divisas" => $this->func->getDivisas()
			)
		);
	}

	/**
	 * 
	 * 
	 * Metodo que actualiza los datos generales
	 *
	 */
	public function actualizarDatosGenerales()
	{
		if (!isset($_POST))
		{
			$this->json = array(
				"status" => false,
				"mensaje" => "El formulario no se envío correctamente."
			);
		}
		else
		{

			$this->fova->campos = $_POST;

			$this->fova->validarCampo("evt_idTipoEvento", "req", "", 1, 2);

			$this->fova->match = "/^([a-zA-Z0-9]+)\$/";
			$this->fova->validarCampo("evt_clave", "req", "Indique solo letras y números", 5, 15, "MATCH");

			$this->fova->validarCampo("evt_nombre", "req", "", 0, 175);
			$this->fova->validarCampo("evt_inicio", "req", "", 10, 10, "DATE");
			$this->fova->validarCampo("evt_termina", "req", "", 10, 10, "DATE");
			$this->fova->validarCampo("evt_divisa", "req", "", 3, 3);
			$this->fova->validarCampo("evt_iva", "", "", 4, 9, "MONE");
			$this->fova->validarCampo("evt_status", "req", "", 1, 2);

			if ($this->fova->validacion['status'])
			{

				// Pasamos a mayusculas la clave
				$this->fv->campos['evt_clave'] = strtoupper($this->fv->campos['evt_clave']);

				if ($this->validarClaveEvento($this->fova->campos['evt_clave'], $this->idEvento))
				{
					$this->json = array(
						"status" => false,
						"mensaje" => "Ya existe otro evento con la clave indicada."
					);
				}
				else if ($this->validarNombreEvento($this->fova->campos['evt_nombre'], $this->idEvento))
				{
					$this->json = array(
						"status" => false,
						"mensaje" => "Ya existe otro evento con el mismo nombre."
					);
				}
				else
				{

					$post = $this->fova->campos;

					$post['evt_inicio'] = $this->func->date2db($post['evt_inicio']);
					$post['evt_termina'] = $this->func->date2db($post['evt_termina']);
					$post['evt_iva'] = $this->func->moneda2db($post['evt_iva']);

					if ($this->model->actualizarDatosGenerales($post, $this->idEvento, $this->user_login['id']))
					{

						// Si se indica otra clave de evento, cambiamos el nombre de la tabla de registros
						if ($this->fova->campos['evt_clave'] != $this->fova->campos['evt_clave_org'])
						{
							$this->model->cambiarNombreTablaRegs($this->fova->campos['evt_clave_org'], $this->fova->campos['evt_clave']);
							$this->model->cambiarNombreTablaItems($this->fova->campos['evt_clave_org'], $this->fova->campos['evt_clave']);
							$this->model->cambiarNombreTablaAcoms($this->fova->campos['evt_clave_org'], $this->fova->campos['evt_clave']);
							$this->model->cambiarNombreTablaFotos($this->fova->campos['evt_clave_org'], $this->fova->campos['evt_clave']);
							$this->model->cambiarNombreTablaReservaciones($this->fova->campos['evt_clave_org'], $this->fova->campos['evt_clave']);
							$this->model->cambiarNombreTablaRFID($this->fova->campos['evt_clave_org'], $this->fova->campos['evt_clave']);
						}

						$this->fova->campos['tipoEvento'] = $this->evt->getTipoEvento($post['evt_idTipoEvento']);
						$this->fova->campos['statusEvento'] = $this->evt->getNombreStatusEvento($post['evt_status']);

						$this->json = array(
							"status" => "funcion",
							"nomFuncion" => "actualizarDatosGeneralesEvento",
							"evento" => $this->fova->campos
						);
					}
					else
					{
						$this->json = array(
							"status" => false,
							"mensaje" => "No fue posible actualizar el evento, intentalo de nuevo."
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

	/**
	 * 
	 * 
	 * Metodo que muestra el formulario para las observaciones
	 *
	 */
	public function formObservaciones()
	{
		// get datos del evento
		$evento = $this->model->getEvento($this->idEvento);

		$this->display(
			array(
				"seccion" => $this->seccion,
				"observaciones" => $evento->evt_observaciones
			)
		);
	}

	/**
	 * 
	 * 
	 * Metodo que actualiza las observaciones
	 *
	 */
	public function actualizarObservaciones()
	{
		if (!isset($_POST))
		{
			$this->json = array(
				"status" => false,
				"mensaje" => "El formulario no se envío correctamente."
			);
		}
		else
		{

			$post = $_POST;
			$this->fova->campos = $post;

			$this->fova->validarCampo("evt_observaciones", "", "", 0, 1000);

			if (!$this->fova->validacion['status'])
			{
				$this->json = $this->fova->validacion;
			}
			else
			{

				if ($this->model->actualizarObservaciones($post['evt_observaciones'], $this->idEvento, $this->user_login['id']))
				{
					$this->json = array(
						"status" => "funcion",
						"nomFuncion" => "actualizarObservacionesEvento",
						"observaciones" => $post['evt_observaciones']
					);
				}
				else
				{
					$this->json = array(
						"status" => false,
						"mensaje" => "No fue posible actualizar las observaciones, intentalo de nuevo."
					);
				}

			}

		}

		echo json_encode($this->json);
	}

	/**
	 * 
	 * 
	 * Metodo que solo carga las gategorias
	 *
	 */
	public function cargarCategorias()
	{
		$categorias = $this->model->getCategorias($this->idEvento);

		foreach ($categorias as $key => $categoria)
		{
			$categorias[$key]->ecc_costo_fecha_1 = $this->func->moneda2screen($categorias[$key]->ecc_costo_fecha_1);
			$categorias[$key]->ecc_costo_fecha_2 = $this->func->moneda2screen($categorias[$key]->ecc_costo_fecha_2);
			$categorias[$key]->ecc_costo_fecha_3 = $this->func->moneda2screen($categorias[$key]->ecc_costo_fecha_3);
			$categorias[$key]->ecc_costo_sitio = $this->func->moneda2screen($categorias[$key]->ecc_costo_sitio);
			$categorias[$key]->ecc_costo_otro = $this->func->moneda2screen($categorias[$key]->ecc_costo_otro);

			$categorias[$key]->nombres = $this->model->getNombresCategorias($categoria->ecc_clave, $this->idEvento);

			if (!empty($categorias[$key]->nombres))
			{
				$ultimoNombre = end($categorias[$key]->nombres);
				$categorias[$key]->idNombre = $ultimoNombre->enc_idNombreCategoria+1;
			}
			else
			{
				$categorias[$key]->idNombre = 1;
			}
		}

		$this->json = array(
			"seccion" => $this->seccion,
			"categorias" => $categorias
		);

		echo json_encode($this->json);
	}

	/**
	 * 
	 * 
	 * Metodo que actualiza el tipo de cambio para la divisa
	 *
	 */
	public function editarDivisa()
	{
		if (!isset($_POST))
		{
			$this->json = array(
				"status" => false,
				"mensaje" => "El formulario no se envío correctamente."
			);
		}
		else
		{

			$this->fova->campos = $_POST;

			$campo = key($_POST); // clave

			$this->fova->validarCampo($campo, "", "", 0, 12, "MONE");

			if ($this->fova->validacion['status'])
			{

				$valor = $this->fova->campos[$campo];

				if ($this->model->setDivisa($this->idEvento, $campo, $valor, $this->user_login['id']))
				{
					$this->json = array(
						"status" => "funcion",
						"nomFuncion" => "editarDivisa",
						"campo" => $campo,
						"valor" => $valor
					);			
				}
				else
				{
					$this->json = array(
						"status" => "funcion",
						"nomFuncion" => "errorActualizarDivisa"
					);
				}

			}
			else
			{
				$this->json = $this->fova->validacion;
			}

		}

		echo json_encode($this->json);
	}

	/**
	 * 
	 * 
	 * Metodo que muestra el formulario para las categorias
	 *
	 */
	public function formAltaCategoria()
	{
		$this->display(
			array(
				"seccion" => $this->seccion
			)
		);
	}

	/**
	 * 
	 * 
	 * Metodo que agrega las categorias
	 * 
	 */
	public function agregarCategoria()
	{
		if (!isset($_POST))
		{
			$this->json = array(
				"status" => false,
				"mensaje" => "El formulario no se envío correctamente"
			);
		}
		else
		{

			$this->fova->campos = $_POST;

			$this->fova->match = "/^([a-zA-Z]+)\$/";
			$this->fova->validarCampo("ecc_clave", "req", "Indique solo caracteres", 2, 10, "MATCH");

			$this->fova->validarCampo("ecc_costo_fecha_1", "", "", 4, 12, "MONE");
			$this->fova->validarCampo("ecc_costo_fecha_2", "", "", 4, 12, "MONE");
			$this->fova->validarCampo("ecc_costo_fecha_3", "", "", 4, 12, "MONE");
			$this->fova->validarCampo("ecc_costo_sitio", "", "", 4, 12, "MONE");
			$this->fova->validarCampo("ecc_costo_otro", "", "", 4, 12, "MONE");

			if ($this->fova->validacion['status'])
			{

				$post = $this->fova->campos;

				// Validar clave de la categoria
				if ($this->validarClaveCategoria())
				{
					$this->json = array(
						"status" => false,
						"mensaje" => "Ya existe una categoria con la clave indicada."
					);
				}
				else
				{

					$post['ecc_clave'] = strtoupper($post['ecc_clave']);
					$post['ecc_costo_fecha_1'] = $this->func->moneda2db($post['ecc_costo_fecha_1']);
					$post['ecc_costo_fecha_2'] = $this->func->moneda2db($post['ecc_costo_fecha_2']);
					$post['ecc_costo_fecha_3'] = $this->func->moneda2db($post['ecc_costo_fecha_3']);
					$post['ecc_costo_sitio'] = $this->func->moneda2db($post['ecc_costo_sitio']);
					$post['ecc_costo_otro'] = $this->func->moneda2db($post['ecc_costo_otro']);

					if (!$this->model->agregarCategoria($post, $this->idEvento, $this->user_login['id']))
					{

						$this->json = array(
							"status" => false,
							"mensaje" => "No fue posible agregar la categoria, intentalo de nuevo."
						);

					}
					else
					{

						$this->json = array(
							"status" => "funcion",
							"nomFuncion" => "nuevaCategoria",
							"seccion" => $this->seccion . "/?action=cargarCategorias"
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

	/**
	 * 
	 * 
	 * Metodo que muestra el formulario para las categorias
	 *
	 */
	public function formEditarCategoria()
	{
		if (!isset($_GET['cve']) || empty($_GET['cve']))
		{
			header ("HTTP/1.1 514 An Error");
		}
		else
		{
			$clave = $_GET['cve'];
		}

		$categoria = $this->model->getCategoria($clave, $this->idEvento);

		$categoria->ecc_costo_fecha_1 = $this->func->moneda2screen($categoria->ecc_costo_fecha_1);
		$categoria->ecc_costo_fecha_2 = $this->func->moneda2screen($categoria->ecc_costo_fecha_2);
		$categoria->ecc_costo_fecha_3 = $this->func->moneda2screen($categoria->ecc_costo_fecha_3);
		$categoria->ecc_costo_sitio = $this->func->moneda2screen($categoria->ecc_costo_sitio);
		$categoria->ecc_costo_otro = $this->func->moneda2screen($categoria->ecc_costo_otro);

		$this->display(
			array(
				"seccion" => $this->seccion,
				"categoria" => $categoria
			)
		);
	}

	/**
	 * 
	 * 
	 * Metodo que actualiza la categoria
	 * 
	 */
	public function actualizarCategoria()
	{
		if (!isset($_POST))
		{
			$this->json = array(
				"status" => false,
				"mensaje" => "El formulario no se envío correctamente"
			);
		}
		else
		{

			$this->fova->campos = $_POST;

			$this->fova->validarCampo("ecc_clave_ant", "req", "", 2, 10);

			$this->fova->match = "/^([a-zA-Z]+)\$/";
			$this->fova->validarCampo("ecc_clave", "req", "Solo caracteres", 2, 10, "MATCH");
			
			$this->fova->validarCampo("ecc_costo_fecha_1", "", "", 4, 12, "MONE");
			$this->fova->validarCampo("ecc_costo_fecha_2", "", "", 4, 12, "MONE");
			$this->fova->validarCampo("ecc_costo_fecha_3", "", "", 4, 12, "MONE");
			$this->fova->validarCampo("ecc_costo_sitio", "", "", 4, 12, "MONE");
			$this->fova->validarCampo("ecc_costo_otro", "", "", 4, 12, "MONE");

			if ($this->fova->validacion['status'])
			{

				$post = $this->fova->campos;

				if ($this->validarClaveCategoria())
				{
					$this->json = array(
						"status" => false,
						"mensaje" => "Ya existe otra categoria con la clave indicada."
					);
				}
				else
				{

					$post['ecc_clave'] = strtoupper($post['ecc_clave']);
					$post['ecc_costo_fecha_1'] = $this->func->moneda2db($post['ecc_costo_fecha_1']);
					$post['ecc_costo_fecha_2'] = $this->func->moneda2db($post['ecc_costo_fecha_2']);
					$post['ecc_costo_fecha_3'] = $this->func->moneda2db($post['ecc_costo_fecha_3']);
					$post['ecc_costo_sitio'] = $this->func->moneda2db($post['ecc_costo_sitio']);
					$post['ecc_costo_otro'] = $this->func->moneda2db($post['ecc_costo_otro']);

					if (!$this->model->actualizarCategoria($post, $this->idEvento, $this->user_login['id']))
					{

						$this->json = array(
							"status" => false,
							"mensaje" => "No fue posible actualizar la categoria, intentalo de nuevo."
						);

					}
					else
					{

						// Actualiza la clave de los nombres de la categoria
						$this->model->actualizarClavesCategoria($post['ecc_clave'], $post['ecc_clave_ant'], $this->idEvento);

						$this->json = array(
							"status" => "funcion",
							"nomFuncion" => "nuevaCategoria",
							"seccion" => $this->seccion . "/?action=cargarCategorias"
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

	public function confEliminarCategoria()
	{
		if (!isset($_GET['cve']))
		{
			header ("HTTP/1.1 514 An Error");
		}

		$this->json = array(
			"status" => "funcion",
			"nomFuncion" => "confEliminarCategoria",
			"seccion" => $this->seccion . "/?action=eliminarCategoria",
			"mensaje" => "¿Realmente desea eliminar la categoria?",
			"categoria" => $_GET['cve']
		);

		echo json_encode($this->json);
	}

	/**
	 * 
	 * 
	 * Metodo que elimina una categoria
	 * 
	 */
	public function eliminarCategoria()
	{
		if (!isset($_POST['ecc_clave']) || empty($_POST['ecc_clave']))
		{
			header ("HTTP/1.1 514 An Error");
		}
		else
		{
			$clave = $_POST['ecc_clave'];
		}

		if ($this->model->eliminarNombresCategoria($clave, $this->idEvento))
		{
			if ($this->model->eliminarCategoria($clave, $this->idEvento))
			{
				$this->json = array(
					"status" => "funcion",
					"nomFuncion" => "eliminarCategoria",
					"categoria" => $clave
				);
			}
			else
			{
				$this->json = array(
					"status" => "funcion",
					"nomFuncion" => "errorEliminarCategoria",
					"mensaje" => "No fue posible eliminar la categoria, por favor intentelo de nuevo."
				);
			}
		}
		else
		{
			$this->json = array(
				"status" => "funcion",
				"nomFuncion" => "errorEliminarCategoria",
				"mensaje" => "No fue posible eliminar los nombres asociados a la categoria, por favor intentelo de nuevo."
			);
		}

		echo json_encode($this->json);
	}

	/**
	 * 
	 * 
	 * Metodo que muestra el formulario de alta para nuevo nombre de la categoria
	 * 
	 */
	public function formAltaNombreCategoria()
	{
		if (!isset($_GET['cve']) || empty($_GET['cve']))
		{
			header ("HTTP/1.1 514 An Error");
		}
		else
		{
			$id = $_GET['id'];
			$clave = $_GET['cve'];
		}

		$disabled = $this->model->getIdiomasNombresCategoria($clave, $this->idEvento);

		$this->assignVars(
			array(
				"listaLenguajes" => $this->evt->listaIdiomasSeleccion($this->idEvento, '', $disabled),
				"enc_idNombreCategoria" => $id,
				"enc_clave" => $clave
			)
		);

		$this->view();
	}

	/**
	 * 
	 * 
	 * Metodo que agrega el nombre de la categoria
	 *
	 */
	public function agregarNombreCategoria()
	{
		if (!isset($_POST) || empty($_POST))
		{
			$this->json = array(
				"status" => false,
				"mensaje" => "El formulario no se envío de forma"
			);
		}
		else
		{

			$this->fova->campos = $_POST;

			$this->fova->validarCampo("enc_idioma", "req", 0, 0);
			$this->fova->validarCampo("enc_nombre", "req", 0, 0);

			if ($this->fova->validacion['status'])
			{

				$post = $this->fova->campos;

				if ($this->validarNombreCategoria($post['enc_nombre'], $this->idEvento))
				{
					$this->json = array(
						"false" => false,
						"mensaje" => "Ya existe este nombre en la categoria"
					);
				}
				else
				{

					$post['enc_nombre'] = $this->func->capitalizarStr($post['enc_nombre']);

					if ($this->model->insertarNombreCategoria($post, $this->idEvento))
					{
						$this->json = array(
							"status" => "funcion",
							"nomFuncion" => "nuevaCategoria",
							"seccion" => $this->seccion . "/?action=cargarCategorias"
						);
					}
					else
					{
						$this->json = array(
							"status" => false,
							"mensaje" => "No fue posible agregar el nombre, intentalo de nuevo"
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

	public function confEliminarNombreCategoria()
	{
		if (!isset($_GET['cve'], $_GET['id']))
		{
			header ("HTTP/1.1 514 An Error");
		}

		$this->json = array(
			"status" => "funcion",
			"nomFuncion" => "confEliminarNombreCategoria",
			"seccion" => $this->seccion . "/?action=eliminarNombreCategoria",
			"mensaje" => "¿Realmente desea eliminar el nombre de la categoria?",
			"nombreCategoria" => $_GET
		);

		echo json_encode($this->json);
	}

	public function eliminarNombreCategoria()
	{
		if (!isset($_POST['enc_clave'], $_POST['enc_idNombreCategoria']))
		{
			$this->json = array(
				"status" => false,
				"mensaje" => "No fue posible eliminar el nombre de la categoria, intentalo de nuevo."
			);
		}
		else
		{

			$clave = $_POST['enc_clave'];
			$idNombreCat = $_POST['enc_idNombreCategoria'];

			if ($this->model->eliminarNombresCategoria($clave, $this->idEvento, $idNombreCat))
			{
				$this->json = array(
					"status" => "funcion",
					"nomFuncion" => "eliminarNombreCategoria",
					"seccion" => $this->seccion . "/?action=cargarCategorias"
				);
			}
			else
			{				
				$this->json = array(
					"status" => false,
					"mensaje" => "No fue posible eliminar el nombre de la categoria, intentalo de nuevo."
				);
			}

		}

		echo json_encode($this->json);
	}

	/**
	 * 
	 * 
	 * Metodo que muestra el formulario de alta de idioma
	 *
	 */
	public function formAltaIdioma()
	{
		$this->display(
			array(
				"seccion" => $this->seccion
			)
		);
	}

	/**
	 * 
	 * 
	 * Metodo que agrega el idioma
	 *
	 */
	public function agregarIdioma()
	{
		if (!isset($_POST))
		{
			$this->json = array(
				"status" => false,
				"mensaje" => "El formulario no fue envíado correctamente."
			);
		}
		else
		{

			$this->fova->campos = $_POST;

			$this->fova->match = "/(^[a-zA-Z]+)\$/";
			$this->fova->validarCampo("eis_idioma", "req", "", 2, 2, "MATCH");
			$this->fova->validarCampo("eis_nombre", "req", "", 2, 45);

			if ($this->fova->validacion['status'])
			{

				$this->fova->campos['eis_idioma'] = strtolower($this->fova->campos['eis_idioma']);
				$this->fova->campos['eis_nombre'] = $this->func->capitalizarStr($this->fova->campos['eis_nombre']);

				if (!$this->model->validarClaveIdioma($this->fova->campos['eis_idioma'], $this->idEvento))
				{

					if ($this->model->agregarIdioma($this->fova->campos, $this->idEvento))
					{
						$this->json = array(
							"status" => "funcion",
							"nomFuncion" => "altaIdioma",
							"seccion" => $this->seccion,
							"idioma" => $this->fova->campos
						);
					}
					else
					{
						$this->json = array(
							"status" => false,
							"mensaje" => "No fue posible agregar el idioma, intentalo de nuevo"
						);
					}
				}
				else
				{
					$this->json = array(
						"status" => false,
						"mensaje" => "Ya existe un idioma con la clave indicada"
					);
				}

			}
			else
			{
				$this->json = $this->fova->validacion;
			}

		}

		echo json_encode($this->json);
	}

	public function confEliminarIdioma()
	{
		if (!isset($_GET['cve']))
		{
			header ("HTTP/1.1 514 An Error");
		}

		$this->json = array(
			"status" => "funcion",
			"nomFuncion" => "confEliminarNombreIdioma",
			"seccion" => $this->seccion . "/?action=eliminarIdioma",
			"mensaje" => "¿Realmente desea eliminar el idioma?",
			"idioma" => $_GET['cve']
		);

		echo json_encode($this->json);
	}

	public function eliminarIdioma()
	{

		if (!isset($_POST['eis_idioma']))
		{
			$this->json = array(
				"status" => false,
				"mensaje" => "Error al eliminar el idioma, intentelo de nuevo."
			);
		}
		else
		{
			$clave = $_POST['eis_idioma'];

			if ($this->model->eliminarIdioma($clave, $this->idEvento))
			{
				$this->json = array(
					"status" => "funcion",
					"nomFuncion" => "eliminarIdioma",
					"idioma" => $clave
				);
			}
			else
			{
				$this->json = array(
					"status" => false,
					"mensaje" => "No fue posible eliminar el idioma, intentelo de nuevo."
				);
			}
		}

		echo json_encode($this->json);
	}

	/**
	 * 
	 * 
	 * Metodo que valida si la clave para el evento ya existe
	 *
	 */
	private function validarClaveEvento($clave)
	{
		return $this->model->validarClave($clave, $this->idEvento);
	}

	/**
	 * 
	 * 
	 * Metodo que valida si el nombre para el evento ya existe
	 *
	 */
	private function validarNombreEvento($nombre)
	{
		return $this->model->validarNombre($nombre, $this->idEvento);
	}

	/**
	 * 
	 * 
	 * Metodo que valida si la clave de la categoria ya existe
	 *
	 */
	private function validarClaveCategoria()
	{
		if (isset($this->fova->campos['ecc_clave_ant']))
		{
			return $this->model->validarClaveCategoria($this->fova->campos['ecc_clave'], $this->idEvento, $this->fova->campos['ecc_clave_ant']);
		}
		else
		{
			return $this->model->validarClaveCategoria($this->fova->campos['ecc_clave'], $this->idEvento);	
		}
	}

	/**
	 * 
	 * 
	 * Metodo que valida si los nombres indicados ya existen en las categorias
	 */
	private function validarNombreCategoria()
	{
		if (isset($this->fova->campos['enc_nombre_ant']))
		{
			return $this->model->validarNombreCategoria($this->fova->campos['enc_nombre'], $this->idEvento, $this->fova->campos['enc_nombre_ant']);
		}
		else
		{
			return $this->model->validarNombreCategoria($this->fova->campos['enc_nombre'], $this->idEvento);
		}
	}

}
?>