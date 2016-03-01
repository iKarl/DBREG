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
		$this->sess->validarPermisoSeccion(5);

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
		$eventos = $this->model->getEventos();

		// Mostramos la vista
		$this->display(
			array(
				"seccion" => $this->seccion,
				"tituloSeccion" => "Lista de Eventos",
				"eventos" => $eventos
			)
		);
	}

	/*
	 * Metodo inicio
	 * Inicio de la sección
	 */
	public function caja()
	{
		$this->sess->set('id_evento', $_GET['id']);
		$this->sess->set('idEvento', $_GET['id']);
		$evento = $this->model->getEvento($_GET['id']);

		// Mostramos la vista
		$this->display(
			array(
				"seccion" => $this->seccion,
				"tituloSeccion" => "Buscar registro",
				"categorias" => $this->evt->getNombresCategorias($evento->evt_idEvento),
				"titulos" => $this->evt->getTitulos($evento->evt_idEvento),
				"formasPago" => $this->evt->getNombresFormasPagosReg($evento->evt_idEvento)
			)
		);
	}

	/**
	 * Metodo que guarda el registro
	 * 
	 * @param array $post tiene los datos del registro
	 */
	public function guardarRegistro($request)
	{
		$post = $request->query;
		$evento = $this->model->getEvento($this->sess->get('id_evento'));
		$post['tabla_registros'] = $evento->evt_nombreTablaAsistentes;

		// Obtenemos el costo total del registro
		$costo = $this->evt->getCostoCategoria(
			$post['cat_registro'],
			$post['id_costo'],
			$evento->evt_idEvento
		);
		$post['costo_registro'] = $costo->costo;
		$post['id_costo'] = $costo->idCosto;

		$post['fecha_registro'] = date('Y-m-d H:i:s');

		$idRegistro = $this->model->setRegistro($post, $this->user_login['id']);

		echo $idRegistro;
	}

	/**
	 * Metodo que busca registros
	 */
	public function buscarRegistros()
	{
		$registros = array();
		$evento = $this->model->getEvento($this->sess->get('id_evento'));

		// Busqueda por ID de Registro
		if (!empty($_POST['id_registro']))
		{
			$id_registro = trim($_POST['id_registro']);

			$registros = $this->evt->getRegistroID($id_registro, $evento->evt_nombreTablaAsistentes);
		}
		/* Busqueda por email
		else if (!empty($_POST['email_b']))
		{
			$email = trim(stripslashes($_POST['email_b']));
			$tabla = $_POST['nombre_tabla'];

			$registros = $this->evt->getRegistroEmail($email, $tabla);
		}
		// Busqueda por empresa o instituación*/
		else if (!empty($_POST['emp_o_ins']))
		{
			$emp_o_ins = trim(stripslashes($_POST['emp_o_ins']));

			$registros = $this->evt->getRegistroEmpOInt($emp_o_ins, $evento->evt_nombreTablaAsistentes);			
		}
		// Busqueda por nombre, app o/y app
		else
		{
			$app = $apm = $nombre = "";

			if (!empty($_POST['app']))
			{
				$app = trim(stripslashes($_POST['app']));
			}

			if (!empty($_POST['apm']))
			{
				$apm = trim(stripslashes($_POST['apm']));
			}

			if (!empty($_POST['nombre']))
			{
				$nombre = trim(stripslashes($_POST['nombre']));
			}

			if ($app || $nombre)
			{
				$registros = $this->evt->getRegistros($app, $apm, $nombre, $evento->evt_nombreTablaAsistentes);
			}
			else
			{
				$registros = $this->evt->getRegistrosInicio($evento->evt_nombreTablaAsistentes);
			}
		}

		if (!empty($registros))
		{
			$this->json = array(
				"status" => "funcion",
				"nomFuncion" => "mostrarRegistros",
				"seccion" => $this->seccion,
				"registros" => $registros
			);
		}
		else
		{
			$this->json = array(
				"status" => "funcion",
				"nomFuncion" => "mostrarRegistros",
				"mensaje" => "No se encontro ningun registro."
			);
		}

		echo json_encode($this->json);
	}

	public function cobrar()
	{
		$evento = $this->model->getEvento($this->sess->get('id_evento'));
		$registro = $this->model->getRegistro($_GET['id'],  $evento->evt_clave);

		$itemsEvento = $this->evt->getNombresItems($evento->evt_idEvento);
		$acompanantes = $this->model->getAcompanantes($registro->id_registro, $evento->evt_clave);
		$itemsAsistentes = $this->model->getItems($registro->id_registro, $evento->evt_clave);

		/*if (!empty($acompanantes))
		{
			foreach ($acompanantes as $key => $acompanante)
			{
				$acompanantes[$key]->costos = $this->evt->getCostosCategoriaAcom($acompanante->acm_clave, $evento->evt_idEvento);
			}
		}*/

		if (!empty($itemsAsistentes))
		{
			foreach ($itemsAsistentes as $key => $item)
			{
				$itemsAsistentes[$key]->costos = $this->evt->getCostosCategoriaItem($item->item_clave, $evento->evt_idEvento);
				$itemsAsistentes[$key]->item_costo_total = $this->func->moneda2screen($item->item_costo_total);
			}
		}

		// Obtenemos el total de items
		foreach ($itemsEvento as $key => $item)
		{
			$itemsEvento[$key]->total_conf = $this->evt->getTotalItems($item->eni_clave, $evento->evt_clave);
		}

		$sumaCostoTotalRegistro = $this->func->moneda2screen($this->evt->costoTotalRegistro($registro->id_registro, $evento->evt_clave));
		$costoTotalRegistro = $this->func->moneda2screen($registro->costo_total);

		$registro->id_tag = preg_split("/(([1-9])+([0-9]+)?)/", $registro->id_tag, NULL, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);

		$evento->evt_inicio = $this->func->date2screen($evento->evt_inicio);
		$evento->evt_termina = $this->func->date2screen($evento->evt_termina);

		$habitaciones = array();
		$specHabitacion = array();
		if ($reservacion = $this->model->getReservacion($registro->id_registro, $evento->evt_clave))
		{
			$habitaciones = $this->evt->getHabitaciones($this->idEvento, $reservacion->res_idHotel);
			$specHabitacion = $this->evt->getEspecHabitacion($this->idEvento, $reservacion->res_idHotel, $reservacion->res_idHabitacion);
		}

		$this->display(
			array(
				'registro' => $registro,
				'acompanantes' => $acompanantes,
				'adicionales' => $itemsAsistentes,
				'items' => $itemsEvento,
				'costoRegistro' => $costoTotalRegistro,
				'sumaCostoTotalRegistro' => $sumaCostoTotalRegistro,
				'formas_pago' => $this->evt->getNombresFormasPagosReg($evento->evt_idEvento),
				'status_registro' => $this->evt->getStatusRegistro($evento->evt_idEvento),
				'categoriasAcom' => $this->evt->getNombresCategoriasAcom($evento->evt_idEvento),
				'titulos' => $this->evt->getTitulos($evento->evt_idEvento),
				'generos' => $this->func->getGeneros(),
				'paises' => $this->func->getPaises()
			)
		);
	}

	public function pagar()
	{
		$this->json = array(
			'status' => false,
			'mensaje' => 'Error no fue posible procesar el pago.'
		);

		if (!empty($_POST))
		{
			$evento = $this->model->getEvento($this->sess->get('id_evento'));
			$registro = $this->model->getRegistro($_POST['id_registro'],  $evento->evt_clave);

			if (isset($_POST['revision_factura']) && !empty($_POST['revision_factura']))
			{
				$_POST['revision_factura'] = 1;
			}
			else
			{
				$_POST['revision_factura'] = 0;
			}

			$_POST['caja_costo_total'] = $this->func->moneda2db($_POST['caja_costo_total']);
			$_POST['caja_costo_descuento'] = $this->func->moneda2db($_POST['caja_costo_descuento']);
			$_POST['costo_total'] = $_POST['caja_costo_total'] - $_POST['caja_costo_descuento'];
			$_POST['fecha_cobro'] = $this->func->date2db();

			if (isset($_POST['requiere_factura']) && $_POST['requiere_factura'] == 1)
			{
				$pais_nombre = $this->func->getNombrePais($_POST['pais_RS']);
				$_POST['pais_RS_nombre'] = $pais_nombre->pais_nombreEs;

				if ($_POST['pais_RS'] != 146)
				{
					$_POST['del_o_mun_RS'] = '';
					$_POST['rfc_RS'] = '';
				}
			}
			else
			{
				$_POST['requiere_factura'] = 2;
				$_POST['razon_social_RS'] = '';
				$_POST['rfc_RS'] = '';
				$_POST['pais_RS'] = '';
				$_POST['pais_RS_nombre'] = '';
				$_POST['cp_RS'] = '';
				$_POST['estado_RS'] = '';
				$_POST['del_o_mun_RS'] = '';
				$_POST['colonia_RS'] = '';
				$_POST['ciudad_RS'] = '';
				$_POST['direccion_RS'] = '';
				$_POST['lada_telefono_RS'] = '';
				$_POST['telefono_RS'] = '';
				$_POST['email_RS'] = '';
			}

			if (empty($_POST['id_cobro']))
			{
				if ($this->model->pagar($_POST, $evento->evt_clave, $this->user_login['id']))
				{
					$this->json = array(
						'status' => true,
						'mensaje' => 'Cobro realizado con exito!'
					);
				}
			}
			else
			{
				if ($this->model->actualizarPago($_POST, $evento->evt_clave, $this->user_login['id']))
				{
					$this->json = array(
						'status' => true,
						'mensaje' => 'Cobro realizado con exito!'
					);
				}
			}
		}

		echo json_encode($this->json);
	}

	/**
	 * Metodo que lista los costos de la categoria del tipo de acompañante seleccionado
	 * 
	 */
	public function listaCostosCategoriaAcom()
	{
		if (!isset($_GET['clave']))
		{
			header ("HTTP/1.1 514 An Error");
			return;
		}
		$evento = $this->model->getEvento($this->sess->get('id_evento'));

		$opciones = "<option value=''>Seleccione:</option>";
		$precios = $this->evt->getCostosCategoriaAcom($_GET['clave'], $evento->evt_idEvento);

		foreach ($precios as $key => $value)
		{
			$opciones .= '<option value="' . $key . '"';
			$opciones .= ($key == 5) ? ' selected' : '';
			$opciones .= '>' . $value['nombre'] . ' - ' . $this->func->moneda2screen($value['costo']) . '</option>';
		}

		echo $opciones;
	}

	/**
	 * Metodo que agrega un acompañante
	 */
	public function setAcompanante()
	{
		$this->json = array(
			"status" => false,
			"mensaje" => "No fue posible agregar el acompañante."
		);
		$evento = $this->model->getEvento($this->sess->get('id_evento'));

		$_POST['acm_costo'] = $this->evt->getCostoCategoriaAcom(
			$_POST["acm_clave"],
			$_POST["acm_id_costo"],
			$evento->evt_idEvento
		);

		if ($evento->evt_iva > 0)
		{
			$_POST['acm_costo']->costo = $_POST['acm_costo']->costo * $evento->evt_iva;
		}

		$_POST['tabla'] = $evento->evt_clave;

		if ($this->model->agregarAcompanante($_POST, $this->user_login['id']))
		{
			$this->json = array(
				'status' => true,
				'acomp' => $_POST
			);
		}

		echo json_encode($this->json);
	}

	public function delAcompanante()
	{
		$this->json = array(
			"status" => false,
			"mensaje" => "No fue posible eliminar el acompañante."
		);

		if (!empty($_GET['id']) AND !empty($_POST['acm_idRegistro']))
		{
			$evento = $this->model->getEvento($this->sess->get('id_evento'));

			if ($this->model->delAcompanante($_GET['id'], $_POST['acm_idRegistro'], $evento->evt_clave))
			{
				$this->json = array(
					'status' => true,
					'acomp' => $_GET['id'],
					'registro' => $_POST['acm_idRegistro']
				);
			}
		}

		echo json_encode($this->json);
	}

	/**
	 * Metodo que lista los costos del item seleccionado
	 * 
	 */
	public function listaCostosCategoriaItem()
	{
		if (!isset($_GET['clave']))
		{
			header ("HTTP/1.1 514 An Error");
			return;
		}
		$evento = $this->model->getEvento($this->sess->get('id_evento'));

		$opciones = "<option value=''>Seleccione:</option>";
		$precios = $this->evt->getCostosCategoriaItem($_GET['clave'], $evento->evt_idEvento);

		foreach ($precios as $key => $value)
		{
			$opciones .= '<option value="' . $key . '"';
			$opciones .= ($key == 5) ? ' selected' : '';
			$opciones .= '>' . $value['nombre'] . ' - ' . $this->func->moneda2screen($value['costo']) . '</option>';
		}

		echo $opciones;
	}

	/**
	 * Metodo que lista los costos del item seleccionado
	 * 
	 */
	public function getCostoTotalItem()
	{
		if (!isset($_POST['cantidad'], $_POST['clave'], $_POST['idCosto']))
		{
			$this->json = "";
		}
		else
		{
			$evento = $this->model->getEvento($this->sess->get('id_evento'));
			$clave = $_POST['clave'];
			$idCosto = $_POST['idCosto'];
			$cantidad = $_POST['cantidad'];

			$costo = $this->evt->getCostoItem($clave, $idCosto, $evento->evt_idEvento);

			if (empty($cantidad) || empty($clave) || empty($idCosto))
			{
				$this->json = "";
			}
			else if (is_numeric($cantidad))
			{
				$total = $costo->costo * $cantidad;
				$this->json = $this->func->moneda2screen($total);
			}
			else
			{
				$this->json = "";
			}
		}

		echo $this->json;
	}

	/**
	 * Metodo que agrega un acompañante
	 */
	public function setAdicional()
	{
		$this->json = array(
			"status" => false,
			"mensaje" => "No fue posible agregar el item."
		);
		$evento = $this->model->getEvento($this->sess->get('id_evento'));

		$_POST['item_costo'] = $this->evt->getCostoItem(
			$_POST["item_clave"],
			$_POST["item_id_costo"],
			$evento->evt_idEvento
		);
		$_POST['tabla'] = $evento->evt_clave;

		if ($evento->evt_iva > 0)
		{
			$_POST['item_costo']->costo = $_POST['item_costo']->costo * $evento->evt_iva;
		}

		$_POST['item_costo_total'] = $this->func->moneda2db($_POST['item_costo_total']);

		if ($this->model->agregarAdicional($_POST, $this->user_login['id']))
		{
			$this->json = array(
				'status' => true,
				'item' => $_POST
			);
		}

		echo json_encode($this->json);
	}

	public function delAdicional()
	{
		$this->json = array(
			"status" => false,
			"mensaje" => "No fue posible eliminar el item."
		);

		if (!empty($_GET['id']) AND !empty($_POST['item_idRegistro']))
		{
			$evento = $this->model->getEvento($this->sess->get('id_evento'));

			if ($this->model->delAdicional($_GET['id'], $_POST['item_idRegistro'], $evento->evt_clave))
			{
				$this->json = array(
					'status' => true,
					'item' => $_GET['id'],
					'registro' => $_POST['item_idRegistro']
				);
			}
		}

		echo json_encode($this->json);
	}
}
?>