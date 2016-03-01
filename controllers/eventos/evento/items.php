<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/controllers/eventos/evento/
 * @version $Id: items.php 1.0 2012-04-05 20:33 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Controller\Base_Controller as Controller;

class Items_Controller extends Controller
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
		if (!$this->idEvento)
		{
			header ("HTTP/1.1 514 An Error");
		}

		// Obtener items
		$items = $this->model->getItems($this->idEvento);

		// Seteamos los nombres de los items
		foreach ($items as $key => $item)
		{
			$items[$key]->eci_costo_fecha_1 = $this->func->moneda2screen($item->eci_costo_fecha_1);
			$items[$key]->eci_fecha_1 = $this->func->date2screen($item->eci_fecha_1);

			$items[$key]->eci_costo_fecha_2 = $this->func->moneda2screen($item->eci_costo_fecha_2);
			$items[$key]->eci_fecha_2 = $this->func->date2screen($item->eci_fecha_2);

			$items[$key]->eci_costo_fecha_3 = $this->func->moneda2screen($item->eci_costo_fecha_3);
			$items[$key]->eci_fecha_3 = $this->func->date2screen($item->eci_fecha_3);

			$items[$key]->eci_costo_sitio = $this->func->moneda2screen($item->eci_costo_sitio);
			$items[$key]->eci_costo_otro = $this->func->moneda2screen($item->eci_costo_otro);

			$items[$key]->nombres = $this->model->getNombresItems($item->eci_clave, $this->idEvento);

			if (!empty($items[$key]->nombres))
			{
				$ultimoNombre = end($items[$key]->nombres);
				$items[$key]->idNombre = $ultimoNombre->eni_idNombreItem + 1;
			}
			else
			{
				$items[$key]->idNombre = 1;
			}

		}

		// Mostramos la vista
		$this->display(
			array(
				"seccion" => $this->seccion,
				"items" => $items
			)
		);
	}

	/**
	 * 
	 * 
	 * Metodo que agrega las categorias
	 * 
	 */
	public function agregarItem()
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

			$this->fova->match = "/^([a-zA-Z0-9]+)\$/";
			$this->fova->validarCampo("eci_clave", "req", "Indique solo caracteres", 2, 10);

			$this->fova->validarCampo("eci_costo_fecha_1", "", "", 4, 12, "MONE");
			$this->fova->validarCampo("eci_fecha_1", "", "", 4, 12, "DATE");

			$this->fova->validarCampo("eci_costo_fecha_2", "", "", 4, 12, "MONE");
			$this->fova->validarCampo("eci_fecha_2", "", "", 4, 12, "DATE");

			$this->fova->validarCampo("eci_costo_fecha_3", "", "", 4, 12, "MONE");
			$this->fova->validarCampo("eci_fecha_3", "", "", 4, 12, "DATE");

			$this->fova->validarCampo("eci_costo_otro", "", "", 4, 12, "MONE");
			$this->fova->validarCampo("eci_costo_sitio", "", "", 4, 12, "MONE");

			$this->fova->validarCampo("eci_paxMaximos", "", "", 0, 5, "INT");

			if ($this->fova->validacion['status'])
			{
				$post = $this->fova->campos;

				// Validar clave de la categoria
				if ($this->validarClaveItem())
				{
					$this->json = array(
						"status" => false,
						"mensaje" => "Ya existe un item con la clave indicada."
					);
				}
				else
				{
					$this->fova->campos['eci_clave'] = $this->func->mayusStr($post['eci_clave']);

					$this->fova->campos['eci_costo_fecha_1'] = $this->func->moneda2db($post['eci_costo_fecha_1']);
					if (!empty($this->fova->campos['eci_fecha_1']))
					{
						$this->fova->campos['eci_fecha_1'] = $this->func->date2db($post['eci_fecha_1']);
					}

					$this->fova->campos['eci_costo_fecha_2'] = $this->func->moneda2db($post['eci_costo_fecha_2']);
					if (!empty($this->fova->campos['eci_fecha_1']))
					{
						$this->fova->campos['eci_fecha_2'] = $this->func->date2db($post['eci_fecha_2']);
					}

					$this->fova->campos['eci_costo_fecha_3'] = $this->func->moneda2db($post['eci_costo_fecha_3']);
					if (!empty($this->fova->campos['eci_fecha_1']))
					{
						$this->fova->campos['eci_fecha_3'] = $this->func->date2db($post['eci_fecha_3']);
					}

					$this->fova->campos['eci_costo_otro'] = $this->func->moneda2db($post['eci_costo_otro']);
					$this->fova->campos['eci_costo_sitio'] = $this->func->moneda2db($post['eci_costo_sitio']);

					if (!$this->model->agregarItem($this->fova->campos, $this->idEvento, $this->user_login['id']))
					{
						$this->json = array(
							"status" => false,
							"mensaje" => "No fue posible agregar el Item, intentalo de nuevo."
						);
					}
					else
					{
						$this->json = array(
							"status" => "funcion",
							"nomFuncion" => "nuevaItem",
							"seccion" => $this->seccion,
							"item" => $this->fova->campos
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
	public function formEditarItem()
	{
		if (!isset($_GET['cve']) || empty($_GET['cve']))
		{
			header ("HTTP/1.1 514 An Error");
		}
		else
		{
			$clave = $_GET['cve'];
		}

		$item = $this->model->getItem($clave, $this->idEvento);

		$item->eci_costo_fecha_1 = $this->func->moneda2screen($item->eci_costo_fecha_1);
		$item->eci_fecha_1 = $this->func->date2screen($item->eci_fecha_1);

		$item->eci_costo_fecha_2 = $this->func->moneda2screen($item->eci_costo_fecha_2);
		$item->eci_fecha_2 = $this->func->date2screen($item->eci_fecha_2);

		$item->eci_costo_fecha_3 = $this->func->moneda2screen($item->eci_costo_fecha_3);
		$item->eci_fecha_3 = $this->func->date2screen($item->eci_fecha_3);

		$item->eci_costo_otro = $this->func->moneda2screen($item->eci_costo_otro);
		$item->eci_costo_sitio = $this->func->moneda2screen($item->eci_costo_sitio);

		$this->json = array(
				"status" => "funcion",
				"nomFuncion" => "formEditarItem",
				"seccion" => $this->seccion,
				"item" => $item
		);

		echo json_encode($this->json);
	}

	/**
	 * 
	 * 
	 * Metodo que actualiza la categoria
	 * 
	 */
	public function actualizarItem()
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

			$this->fova->validarCampo("eci_clave_anterior", "req", "", 2, 10);

			$this->fova->match = "/^([a-zA-Z0-9]+)\$/";
			$this->fova->validarCampo("eci_clave_a", "req", "Solo caracteres", 2, 10, "MATCH");
			
			$this->fova->validarCampo("eci_costo_fecha_1_a", "", "", 4, 12, "MONE");
			$this->fova->validarCampo("eci_fecha_1_a", "", "", 10, 10, "DATE");
			$this->fova->validarCampo("ecc_costo_fecha_2_a", "", "", 4, 12, "MONE");
			$this->fova->validarCampo("eci_fecha_2_a", "", "", 10, 10, "DATE");
			$this->fova->validarCampo("ecc_costo_fecha_3_a", "", "", 4, 12, "MONE");
			$this->fova->validarCampo("eci_fecha_3_a", "", "", 10, 10, "DATE");
			$this->fova->validarCampo("ecc_costo_sitio_a", "", "", 4, 12, "MONE");
			$this->fova->validarCampo("ecc_costo_otro_a", "", "", 4, 12, "MONE");
			$this->fova->validarCampo("eci_paxMaximos_a", "", "", 0, 5, "INT");

			if ($this->fova->validacion['status'])
			{
				if ($this->validarClaveItem())
				{
					$this->json = array(
						"status" => false,
						"mensaje" => "Ya existe otro item con la clave indicada."
					);
				}
				else
				{
					$this->fova->campos['eci_clave_a'] = $this->func->mayusStr($this->fova->campos['eci_clave_a']);
					$this->fova->campos['eci_costo_fecha_1_a'] = $this->func->moneda2db($this->fova->campos['eci_costo_fecha_1_a']);
					$this->fova->campos['eci_fecha_1_a'] = $this->func->date2db($this->fova->campos['eci_fecha_1_a']);
					$this->fova->campos['eci_costo_fecha_2_a'] = $this->func->moneda2db($this->fova->campos['eci_costo_fecha_2_a']);
					$this->fova->campos['eci_fecha_2_a'] = $this->func->date2db($this->fova->campos['eci_fecha_2_a']);
					$this->fova->campos['eci_costo_fecha_3_a'] = $this->func->moneda2db($this->fova->campos['eci_costo_fecha_3_a']);
					$this->fova->campos['eci_fecha_3_a'] = $this->func->date2db($this->fova->campos['eci_fecha_3_a']);
					$this->fova->campos['eci_costo_sitio_a'] = $this->func->moneda2db($this->fova->campos['eci_costo_sitio_a']);
					$this->fova->campos['eci_costo_otro_a'] = $this->func->moneda2db($this->fova->campos['eci_costo_otro_a']);

					if (!$this->model->actualizarItem($this->fova->campos, $this->idEvento, $this->user_login['id']))
					{
						$this->json = array(
							"status" => false,
							"mensaje" => "No fue posible actualizar el item, intentalo de nuevo."
						);
					}
					else
					{
						// Actualiza la clave de los nombres de los items
						$this->model->actualizarClavesItem($this->fova->campos['eci_clave_a'], $this->fova->campos['eci_clave_anterior'], $this->idEvento);

						$this->json = array(
							"status" => "funcion",
							"nomFuncion" => "actualizarItem",
							"seccion" => $this->seccion,
							"item" => $this->fova->campos
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
	 * Metodo que elimina una categoria
	 * 
	 */
	public function eliminarItem()
	{
		if (!isset($_POST['eci_clave_e']) || empty($_POST['eci_clave_e']))
		{
			header ("HTTP/1.1 514 An Error");
		}
		else
		{
			$clave = $_POST['eci_clave_e'];
		}

		if ($this->model->eliminarNombresItem($clave, $this->idEvento))
		{
			if ($this->model->eliminarItem($clave, $this->idEvento))
			{
				$this->json = array(
					"status" => "funcion",
					"nomFuncion" => "eliminarItem",
					"item" => $clave
				);
			}
			else
			{
				$this->json = array(
					"status" => false,
					"mensaje" => "No fue posible eliminar el item, por favor intentelo de nuevo."
				);
			}
		}
		else
		{
			$this->json = array(
				"status" => false,
				"mensaje" => "No fue posible eliminar los nombres asociados al item, por favor intentelo de nuevo."
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
	public function formAltaNombreItem()
	{
		if (!isset($_GET['cve']) || empty($_GET['cve']))
		{
			header ("HTTP/1.1 514 An Error");
			exit ();
		}
		else
		{
			$clave = $_GET['cve'];
		}

		$idiomas = $this->evt->getIdiomas($this->idEvento);
		$idiomasExs = $this->model->getIdiomasItems($clave, $this->idEvento);

		$this->json = array(
			"status" => "funcion",
			"nomFuncion" => "formAltaNombreItem",
			"seccion" => $this->seccion,
			"clave" => $clave,
			"idiomas" => $idiomas,
			"idiomasExs" => $idiomasExs
		);

		echo json_encode($this->json);
	}

	/**
	 * 
	 * 
	 * Metodo que agrega el nombre de la categoria
	 *
	 */
	public function agregarNombre()
	{
		if (!isset($_POST) || empty($_POST))
		{
			$this->json = array(
				"status" => false,
				"mensaje" => "El formulario no se envío de forma correcta."
			);
		}
		else
		{

			$this->fova->campos = $_POST;

			$this->fova->validarCampo("eni_idioma", "req", 0, 0);
			$this->fova->validarCampo("eni_nombre", "req", 0, 0);

			if ($this->fova->validacion['status'])
			{
				//$post = $this->fova->campos;

				if ($this->validarNombre())
				{
					$this->json = array(
						"false" => false,
						"mensaje" => "Ya existe este nombre en otro item"
					);
				}
				else
				{

					$this->fova->campos['eni_nombre'] = $this->func->capitalizarStr($this->fova->campos['eni_nombre']);

					if ($idItem = $this->model->insertarNombre($this->fova->campos, $this->idEvento))
					{
						$this->json = array(
							"status" => "funcion",
							"nomFuncion" => "nuevoNombreItem",
							"seccion" => $this->seccion,
							"idItem" => $idItem,
							"item" => $this->fova->campos
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

	/**
	 * 
	 * 
	 * Metodo que valida si el nombre indicado ya existe en los items
	 */
	private function validarNombre()
	{
		if (isset($this->fova->campos['eni_nombre_ant']))
		{
			return $this->model->validarNombre($this->fova->campos['enc_nombre'], $this->idEvento, $this->fova->campos['eni_nombre_ant']);
		}
		else
		{
			return $this->model->validarNombre($this->fova->campos['eni_nombre'], $this->idEvento);
		}
	}

	public function eliminarNombre()
	{
		if (!isset($_GET['cve'], $_GET['id']))
		{
			$this->json = array(
				"status" => false,
				"mensaje" => "No fue posible eliminar el nombre del item, intentalo de nuevo."
			);
		}
		else
		{
			$clave = $_GET['cve'];
			$id = $_GET['id'];

			if (!$this->model->eliminarNombre($clave, $this->idEvento, $id))
			{
				$this->json = array(
					"status" => false,
					"mensaje" => "No fue posible eliminar el nombre del item, intentalo de nuevo."
				);
			}
			else
			{
				$this->json = array(
					"status" => true
				);
			}

		}

		echo json_encode($this->json);
	}

	/**
	 * Metodo que valida si la clave del item ya existe
	 * 
	 */
	private function validarClaveItem()
	{
		if (isset($this->fova->campos['eci_clave_anterior']))
		{
			return $this->model->validarClaveItem($this->fova->campos['eci_clave_a'], $this->idEvento, $this->fova->campos['eci_clave_anterior']);
		}
		else
		{
			return $this->model->validarClaveItem($this->fova->campos['eci_clave'], $this->idEvento);	
		}
	}

}
?>