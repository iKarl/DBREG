<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/controllers/home/registros/
 * @version $Id: index.php 1.0 2012-04-30 23:49 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Controller\Base_Controller as Controller;

class Index_Controller extends Controller
{
	public $seccion = "";
	public $idEvento = 0;
	public $idRegistro = 0;
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
		$this->sess->validarPermisoSeccion(4);

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

		// Si existe un registro nuevo
		if ($this->sess->get("idRegistro"))
		{

			$this->idRegistro = $this->sess->get("idRegistro");
		}

		$this->model = $this->model();

		$this->viewTwig();
	}

	/**
	 * Metodo por donde inicia la sección
	 * 
	 */
	public function inicio()
	{
		if (!isset($_POST['id']) || empty($_POST['id']))
		{
			if (!$this->sess->get("idEvento"))
			{
				header ("HTTP/1.1 514 An Error");
				return;
			}
		}
		else
		{
			$this->idEvento = $_POST['id'];
			$this->sess->set("idEvento", $this->idEvento);
		}

		$evento = $this->model->getEvento($this->idEvento);

		$this->display(
			array(
				"evento" => $evento,
				"seccion" => $this->seccion,
				"registros" => $this->evt->getRegistrosInicio('smc_reg_' . $evento->evt_clave),
				"totalRegistros" => $this->evt->getTotalRegistros($evento->evt_clave),
				"totalRegistrosPag" => $this->evt->getTotalRegistros($evento->evt_clave, "PAG", ' OR status = "CCC" OR status = "COR"'),
				"totalRegistrosImp" => $this->evt->getTotalRegistrosImp($evento->evt_clave)
			)
		);
	}

	/**
	 * Metodo que muestra el formulario de alta para nuevo registro
	 */
	public function nuevoRegistro()
	{
		$this->json = array(
			"status" => "funcion",
			"nomFuncion" => "formAltaRegistro",
			"seccion" => $this->seccion
		);

		echo json_encode($this->json);
	}

	/**
	 * Metodo que muestra el formulario de registro
	 * 
	 */
	public function formAltaRegistro()
	{
		$evento = $this->model->getEvento($this->idEvento);
		$itemsEvento = $this->evt->getNombresItems($this->idEvento);

		// Obtenemos el total de items
		foreach ($itemsEvento as $key => $item)
		{
			$itemsEvento[$key]->total_conf = $this->evt->getTotalItems($item->eni_clave, $evento->evt_clave);
		}

		$this->display(
			array(
				"seccion" => $this->seccion,
				"evento" => $evento,
				"titulos" => $this->evt->getTitulos($this->idEvento),
				"idiomas" => $this->evt->getIdiomas($this->idEvento),
				"formasPago" => $this->evt->getNombresFormasPagosReg($this->idEvento),
				"statusRegs" => $this->evt->getStatusRegistro($this->idEvento),
				"categorias" => $this->evt->getNombresCategorias($this->idEvento),
				"categoriasAcom" => $this->evt->getNombresCategoriasAcom($this->idEvento),
				"items" => $itemsEvento,
				"hoteles" => $this->evt->getHoteles($this->idEvento),
				"generos" => $this->func->getGeneros(),
				"paises" => $this->func->getPaises()
			)
		);
	}

	public function fotoWeb()
	{
		$this->display(
			array(
				'seccion' => $this->seccion
			)
		);
	}

	/**
	 * Muestra el modal para subir las fotografias
	 */
	public function fotoUpload()
	{
		$this->display(
			array(
				'seccion' => $this->seccion
			)
		);
	}

	/**
	 * Genera as fotografias
	 */
	public function subirFoto()
	{
		if (is_uploaded_file($_FILES['photo']['tmp_name']))
		{
			$photo = $_FILES['photo'];

			$typeMime = array(
				'image/png',
				'image/gif',
				'image/jpeg',
				'image/jpg'
			);

			// Tipo de la imagen
			if (in_array($photo['type'], $typeMime))
			{
				// Tamaño de la imagen no mas de 4 MB
				if ($photo['size'] <= 4494304)
				{
					$size = getimagesize($photo['tmp_name']);
					// Tamaño de la imagen
					if ($size[0] <= 2472 && $size[1] <= 1854)
					{
						$file = file_get_contents($photo['tmp_name']);
						$base64 = base64_encode($file);
						$w = $size[0];
						$h = $size[1];

						if (($size[0] >= 640 && $size[1] >= 427)
							OR ($size[0] < 133 && $size[1] < 177))
						{
							$w = 320;
							$h = 213;

							$new_img = imagecreatetruecolor($w, $h);
							$img_str = base64_decode($base64);
							$img = imagecreatefromstring($img_str);
							imagecopyresampled($new_img, $img, 0, 0, 0, 0, $w, $h, $size[0], $size[1]);
							
							ob_start();
							switch ($photo['type'])
							{
								case 'image/png':
									imagepng($new_img, NULL, 9);
								break;

								case 'image/gif':
									imagegif($new_img);
								break;

								case 'image/jpg':
								case 'image/jpeg':
									imagejpeg($new_img, NULL, 100);
								break;
							}
							$salida = ob_get_contents();
							ob_end_clean();

							$base64 = base64_encode($salida);
						}

						$img = 'data:' . $photo['type'] . ';base64,' . $base64;

						$this->json = array(
							'status' => true,
							'type' => $photo['type'],
							'img' => $base64,
							'photo' => $img,
							'pw' => $w,
							'ph' => $h
						);
					}
					else
					{
						$this->json = array(
							'status' => false,
							'mensaje' => 'Dimenciónes no maximas a 2472 de ancho x 1854 de alto'
						);
					}
				}
				else
				{
					$this->json = array(
						'status' => false,
						'mensaje' => 'No se permiten imagenes de mas de 4 MB de tamaño'
					);
				}

			}
			else
			{
				$this->json = array(
					'status' => false,
					'mensaje' => 'Solo se permiten archivos de tipo imagen: jpg, jpeg, gif o png'
				);
			}
		}
		else
		{
			$this->json = array(
				'status' => false,
				'photo' => $img
			);
		}

		echo json_encode($this->json);
	}

	protected function guardarFotografia($photo, $idRegistro)
	{
		$evento = $this->model->getEvento($this->idEvento);

		$w = $photo['w'];
		$h = $photo['h'];

		$new_img = imagecreatetruecolor($w, $h);
		$img_str = base64_decode($photo['photo']);
		$img = imagecreatefromstring($img_str);
		imagecopyresampled($new_img, $img, 0, 0, $photo['x'], $photo['y'], $w, $h, $w, $h);
		
		ob_start();
		switch ($photo['type'])
		{
			case 'image/png':
				imagepng($new_img, NULL, 9);
			break;

			case 'image/gif':
				imagegif($new_img);
			break;

			case 'image/jpg':
			case 'image/jpeg':
				imagejpeg($new_img, NULL, 100);
			break;
		}
		$salida = ob_get_contents();
		ob_end_clean();

		$base64 = base64_encode($salida);

		return $this->model->setFotografia($photo['type'], $base64, $idRegistro, $evento->evt_clave);
	}

	/**
	 * Metodo que lista los costos de la categoria de registro seleccionado
	 * 
	 */
	public function listaCostosCategoria()
	{
		if (!isset($_POST['cve']))
		{
			header ("HTTP/1.1 514 An Error");
			return;
		}

		$clave = $_POST['cve'];

		$opciones = "<option value=''>Seleccione:</option>";
		$precios = $this->evt->getCostosCategoria($clave, $this->idEvento);

		foreach ($precios as $key => $value)
		{
			$opciones .= '<option value="' . $key . '"';
			$opciones .= ($key == 5) ? ' selected' : '';
			$opciones .= '>' . $value['nombre'] . ' - ' . $this->func->moneda2screen($value['costo']) . '</option>';
		}

		echo $opciones;
	}

	/**
	 * Metodo que agrega el registro
	 * 
	 */
	public function validarRegistro($request)
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
			// Obtenemos datos del evento
			$evento = $this->model->getEvento($this->idEvento);
			$this->fova->campos = $request->query;
			$fotoMensaje = '';

			$this->fova->validarCampo("id_tag", "", "", 0, 20);
			$this->fova->validarCampo("titulo", "", "", 0, 20);
			$this->fova->validarCampo("nombre", "req", "", 2, 100);
			$this->fova->validarCampo("app", "req", "", 2, 50);
			$this->fova->validarCampo("apm", "", "", 2, 50);
			
			$this->fova->validarCampo("rfc", "", "", 0, 13);
			$this->fova->validarCampo("curp", "", "", 0, 18);
			$this->fova->validarCampo("genero", "", "", 1, 1);
			//$this->fova->validarCampo("idioma", "req", "", 2, 2);
			$this->fova->validarCampo("emp_o_ins", "", "", 2, 125);
			$this->fova->validarCampo("cargo", "", "", 3, 75);
			$this->fova->validarCampo("folio_pago", "", "", 1, 15, "INT");
			/*$this->fova->validarCampo("direccion", "", "", 3, 125);
			$this->fova->validarCampo("colonia", "", "", 1, 40);
			$this->fova->validarCampo("ciudad", "", "", 1, 40);
			$this->fova->validarCampo("estado", "", "", 1, 40);
			$this->fova->validarCampo("pais", "", "", 1, 40);

			
			$this->fova->validarCampo("lada_telefono", "", "", 1, 4);
			$this->fova->validarCampo("telefono_particular", "", "", 1, 25);
			$this->fova->validarCampo("telefono_movil", "", "", 1, 25);
			$this->fova->validarCampo("fax_RS", "", "", 1, 25);*/
			$this->fova->validarCampo("comentarios", "", "", 1, 0);

			$this->fova->validarCampo("email", "", "", 5, 125, "MAIL");

			$this->fova->validarCampo("cat_registro", "req", "", 2, 10);
			$this->fova->validarCampo("id_costo", "req", "", 1, 2, "INT");
			$this->fova->validarCampo("forma_pago", "req", "", 2, 2);
			$this->fova->validarCampo("status", "req", "", 3, 3);

			$this->fova->validarCampo("clave_asociada", "", "", 0, 15);

			if ($this->fova->validacion['status'])
			{

				// Genera el folio de registro
				$folio_registro = time();
				$this->fova->agregarCampo("folio_registro", $folio_registro);

				// Genera la clave del registro
				$clave_registro = md5($this->func->generarID(24));
				$this->fova->agregarCampo("clave_registro", $clave_registro);

				// Obtenemos el costo total del registro
				$costo_total = $this->evt->getCostoCategoria(
					$this->fova->campos['cat_registro'],
					$this->fova->campos['id_costo'],
					$this->idEvento
				);

				if ($evento->evt_iva > 0)
				{
					$costo_total->costo = $costo_total->costo * $evento->evt_iva;
				}

				$this->fova->agregarCampo("costo_registro", $costo_total->costo);
				$this->fova->agregarCampo("costo_total", $costo_total->costo);

				if ($idNuevoRegistro = $this->guardarRegistro($this->fova->campos))
				{
					$this->sess->set('idRegistro', $idNuevoRegistro);

					$totalRegistros = $this->evt->getTotalRegistros($this->fova->campos['tabla_registros']);

					$totalRegPagados = 0;
					$regStatus = "PAG";
					if ($this->fova->campos['status'] == $regStatus)
					{
						$totalRegPagados = $this->evt->getTotalRegistros($this->fova->campos['tabla_registros'], $regStatus);
					}

					if ($this->fova->campos['save_photo'] == 1)
					{
						if (!$this->guardarFotografia($this->fova->campos['photo'], $idNuevoRegistro))
						{
							$fotoMensaje = 'El fotografia no fue guardad, por favor intente agregar la fotografia editando el registro.';
						}
					}

					$this->json = array(
						"status" => "funcion",
						"nomFuncion" => "finNuevoRegistro",
						"idRegistro" => $idNuevoRegistro,
						"totalRegistros" => $totalRegistros,
						"totalRegPagados" => $totalRegPagados,
						"mensaje" => "El nuevo registro fue agregado con exito.",
						'fotoMensaje' => $fotoMensaje
					);
				}
				else
				{
					$this->json = array(
						"status" => false,
						"mensaje" => "No fue posible guardar el registro, intentalo de nuevo."
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
	 * Metodo que guarda el registro
	 * 
	 * @param array $post tiene los datos del registro
	 */
	private function guardarRegistro(array $post)
	{
		// Creamos la fecha del registro en formato MySQL
		$post['fecha_registro'] = date("Y-m-d H:i:s", $post['folio_registro']);

		$post['costo_registro'] = $this->func->moneda2db($post['costo_registro']);
		$post['costo_total'] = $this->func->moneda2db($post['costo_total']);

		if (!empty($post['id_tag']))
		{
			$post['id_tag'] = $this->func->generarTagRFID($post['id_tag']);
			$idRegistro = $this->model->setRegistro($post, $this->user_login['id']);
		}
		else
		{
			$idRegistro = $this->model->setRegistro($post, $this->user_login['id']);
			$tagRFID = $this->func->generarTagRFID($idRegistro);
			$this->model->setTagRFID($tagRFID, $idRegistro, $post['tabla_registros']);
		}

		// RFID
		//$tagRFID = $this->func->generarTagRFID($idRegistro);
		//$this->model->setTagRFID($tagRFID, $idRegistro, $post['tabla_registros']);

		return $idRegistro;
	}

	/**
	 * Metodo que agrega los datos de contacto
	 * 
	 */
	public function agregarDatosContacto()
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
			if (!$this->idRegistro)
			{
				header ("HTTP/1.1 514 An Error");
				exit();
			}

			$this->fova->campos = $_POST;

			$this->fova->validarCampo("direccion", "", "", 0, 75);
			$this->fova->validarCampo("cp", "", "", 0, 5, "INT");
			$this->fova->validarCampo("colonia", "", "", 0, 40);

			if ($this->fova->campos['pais'] == 146) // si es México
			{
				$this->fova->validarCampo("del_o_mun", "", "", 0, 40);
			}
			else
			{
				$this->fova->agregarCampo('del_o_mun', "");
			}

			$this->fova->validarCampo("estado", "", "", 0, 40);
			$this->fova->validarCampo("ciudad", "", "", 0, 40);
			$this->fova->validarCampo("pais", "", "", 1, 3, "INT");
			$this->fova->validarCampo("lada_telefono", "", "", 0, 3, "INT");
			$this->fova->validarCampo("telefono_particular", "", "", 0, 25, "INT");
			$this->fova->validarCampo("telefono_movil", "", "", 0, 25, "INT");

			if ($this->fova->validacion['status'])
			{
				if ($this->model->guardarDatosContacto($this->fova->campos, $this->idRegistro))
				{
					$this->json = array(
						"status" => "funcion",
						"nomFuncion" => "finDatosContacto",
						"mensaje" => "El información se guardo con exito."
					);
				}
				else
				{
					$this->json = array(
						"status" => false,
						"mensaje" => "No fue posible guardar la información, intentalo de nuevo."
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
	 * Metodo que agrega un acompañante
	 */
	public function setAcompanante()
	{
		$this->json = array(
			"status" => false,
			"mensaje" => "No fue posible agregar el acompañante."
		);
		$evento = $this->model->getEvento($this->idEvento);

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
		$_POST['acm_idRegistro'] = $this->idRegistro;

		if (($_POST['id_acompanante'] = $this->model->setAcompanante($_POST, $this->user_login['id'])) == true)
		{
			$this->json = array(
				'status' => true,
				'acomp' => $_POST
			);
		}

		echo json_encode($this->json);
	}

	/**
	 * Metodo que actualiza un acompañante
	 */
	public function updateAcompanante($request)
	{
		$this->json = array(
			"status" => false,
			"mensaje" => "No fue posible actualizar el acompañante."
		);
		$evento = $this->model->getEvento($this->idEvento);

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
		$_POST['acm_idRegistro'] = $this->idRegistro;
		$_POST['acm_fechaModificacion'] = $this->func->date2db();

		if ($this->model->updateAcompanante($_POST, $this->user_login['id']))
		{
			$this->json = array(
				'status' => true,
				"mensaje" => "Acompañante actualizado con exito!",
				"acom" => $_POST
			);
		}

		echo json_encode($this->json);
	}

	/**
	 * Metodo que pide confirmación para eliminar el acompañante
	 */
	public function confEliminarAcom()
	{
		if (!isset($_GET['id']))
		{
			header ("HTTP/1.1 514 An Error");
			exit ();
		}
		else
		{
			$id = $_GET['id'];

			$this->json = array(
				"status" => "funcion",
				"nomFuncion" => "confEliminarAcom",
				"seccion" => $this->seccion,
				"titulo" => "Confirmación para eliminar acompañante",
				"mensaje" => "¿Estas realmente seguro de eliminar el acompañante?",
				"idAcom" => $id
			);

			echo json_encode($this->json);
		}
	}

	/**
	 * Metodo que elimina un acompañante
	 */
	public function eliminarAcompanante()
	{
		if (!isset($_POST['num_acm']))
		{
			header ("HTTP/1.1 514 An Error");
			exit ();
		}
		else
		{
			$id = $_POST['num_acm'];

			$evento = $this->model->getEvento($this->idEvento);
			$idRegistro = $this->sess->get("idRegistro");

			if ($this->model->eliminarAcompanante($id, $idRegistro, $evento->evt_clave))
			{
				$this->json = array(
					"status" => "funcion",
					"nomFuncion" => "eliminarAcompanante",
					"idAcom" => $id
				);
			}
			else
			{
				$this->json = array(
					"status" => false,
					"mensaje" => "No fue posible eliminar el acompañente, intentalo de nuevo."
				);				
			}

			echo json_encode($this->json);
		}
	}

	/**
	 * Metodo que lista los costos de la categoria del tipo de acompañante seleccionado
	 * 
	 */
	public function listaCostosCategoriaAcom()
	{
		if (!isset($_POST['cve']))
		{
			header ("HTTP/1.1 514 An Error");
			return;
		}

		$clave = $_POST['cve'];

		$opciones = "<option value=''>Seleccione:</option>";
		$precios = $this->evt->getCostosCategoriaAcom($clave, $this->idEvento);

		foreach ($precios as $key => $value)
		{
			$opciones .= '<option value="' . $key . '"';
			$opciones .= ($key == 5) ? ' selected' : '';
			$opciones .= '>' . $value['nombre'] . ' - ' . $this->func->moneda2screen($value['costo']) . '</option>';
		}

		echo $opciones;
	}

	/**
	 * Metodo que muestra formulario para nuevo item
	 */
	public function formItem()
	{
		// Obtenemos datos del evento
		$evento = $this->model->getEvento($this->idEvento);

		$itemsEvento = $this->evt->getNombresItems($this->idEvento);

		// Obtenemos el total de items
		foreach ($itemsEvento as $key => $item)
		{
			$itemsEvento[$key]->total_conf = $this->evt->getTotalItems($item->eni_clave, $evento->evt_clave);
		}

		$this->json = array(
			"status" => "funcion",
			"nomFuncion" => "formItem",
			"seccion" => $this->seccion,
			"evento" => $evento,
			"statusReg" => $this->evt->getStatusRegistro($this->idEvento),
			"items" => $itemsEvento
		);

		echo json_encode($this->json);
	}

	/**
	 * Metodo que agrega un item
	 */
	public function agregarItem()
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
			if (!$this->idRegistro)
			{
				header ("HTTP/1.1 514 An Error");
				exit();
			}
			$evento = $this->model->getEvento($this->idEvento);

			$this->fova->campos = $_POST;

			if (!isset($this->fova->campos['clave_evento'], $this->fova->campos['num_item']))
			{
				header ("HTTP/1.1 514 An Error");
				exit();
			}

			$i = $this->fova->campos['num_item'];

			// Clave
			if (isset($this->fova->campos["item_clave_" . $i]))
			{
				$this->fova->validarCampo("item_clave_" . $i, "req", "", 0, 10);
			}

			// Cantidad
			if (isset($this->fova->campos["item_cantidad_" . $i]))
			{
				$this->fova->validarCampo("item_cantidad_" . $i, "req", "", 0, 0, "INT");
			}

			// Costo
			if (isset($this->fova->campos["item_id_costo_" . $i]))
			{
				$this->fova->validarCampo("item_id_costo_" . $i, "req", "", 1, 2);

				$costo = $this->evt->getCostoItem(
					$this->fova->campos["item_clave_" . $i],
					$this->fova->campos["item_id_costo_" . $i],
					$this->idEvento
				);
				if ($evento->evt_iva > 0)
				{
					$costo->costo = $costo->costo * $evento->evt_iva;
				}

				$this->fova->agregarCampo("item_costo_unitario_" . $i, $costo->costo);
			}

			// Costo total
			if (isset($this->fova->campos["item_costo_total_" . $i]))
			{
				$this->fova->validarCampo("item_costo_total_" . $i, "req", "", 0, 0, "MONE");
				$this->fova->campos["item_costo_total_" . $i] = $this->func->moneda2db($this->fova->campos["item_costo_total_" . $i]);	
			}

			// Status
			if (isset($this->fova->campos["item_status_" . $i]))
			{
				$this->fova->validarCampo("item_status_" . $i, "req", "", 0, 3);
			}

			if ($this->fova->validacion['status'])
			{
				if ($this->model->agregarItem($this->fova->campos, $this->idRegistro, $this->user_login['id']))
				{
					$this->json = array(
						"status" => "funcion",
						"nomFuncion" => "finAgregarItem",
						"seccion" => $this->seccion,
						"idItem" => $i,
					);
				}
				else
				{
					$this->json = array(
						"status" => false,
						"mensaje" => "No fue posible agregar el item."
					);
				}
			}
			else
			{
				$this->json = $this->fova->validacion;
			}

			echo json_encode($this->json);
		}
	}

	/**
	 * Metodo que pide confirmación para eliminar el item
	 */
	public function confEliminarItem()
	{
		if (!isset($_GET['id']))
		{
			header ("HTTP/1.1 514 An Error");
			exit ();
		}
		else
		{
			$id = $_GET['id'];

			$this->json = array(
				"status" => "funcion",
				"nomFuncion" => "confEliminarItem",
				"seccion" => $this->seccion,
				"titulo" => "Confirmación para eliminar item",
				"mensaje" => "¿Estas realmente seguro de eliminar el item?",
				"idItem" => $id
			);

			echo json_encode($this->json);
		}
	}

	/**
	 * Metodo que elimina un item
	 */
	public function eliminarItem()
	{
		if (!isset($_POST['num_item']))
		{
			header ("HTTP/1.1 514 An Error");
			exit ();
		}
		else
		{
			$id = $_POST['num_item'];

			$evento = $this->model->getEvento($this->idEvento);
			$idRegistro = $this->sess->get("idRegistro");

			if ($this->model->eliminarItem($id, $idRegistro, $evento->evt_clave))
			{
				$this->json = array(
					"status" => "funcion",
					"nomFuncion" => "eliminarItem",
					"idItem" => $id
				);
			}
			else
			{
				$this->json = array(
					"status" => false,
					"mensaje" => "No fue posible eliminar el acompañente, intentalo de nuevo."
				);				
			}

			echo json_encode($this->json);
		}
	}

	/**
	 * Por convertirla a old version
	 *
	 * Metodo que agrega los items
	 */
	public function agregarAdicionales()
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
			if (!$this->idRegistro)
			{
				header ("HTTP/1.1 514 An Error");
				exit();
			}

			$evento = $this->model->getEvento($this->idEvento);
			$this->fova->campos = $_POST;

			if (isset($this->fova->campos['num_item_act']) && !empty($this->fova->campos['num_item_act']))
			{
				$acom = $this->fova->campos['num_item_act'];
			}

			for ($i = 1 ; $i < $acom ; $i++ )
			{
				if (isset($this->fova->campos["item_clave_" . $i]))
				{
					$this->fova->validarCampo("item_clave_" . $i, "req", "", 0, 10);
				}

				if (isset($this->fova->campos["item_cantidad_" . $i]))
				{
					$this->fova->validarCampo("item_cantidad_" . $i, "req", "", 0, 0, "INT");
				}

				if (isset($this->fova->campos["item_costo_total_" . $i]))
				{
					$this->fova->validarCampo("item_costo_total_" . $i, "req", "", 0, 0, "MONE");
					$this->fova->campos["item_costo_total_" . $i] = $this->func->moneda2db($this->fova->campos["item_costo_total_" . $i]);	
				}

				// Costo
				if (isset($this->fova->campos["item_id_costo_" . $i]))
				{
					$this->fova->validarCampo("item_id_costo_" . $i, "req", "", 1, 2);

					$costo = $this->evt->getCostoItem(
						$this->fova->campos["item_clave_" . $i],
						$this->fova->campos["item_id_costo_" . $i],
						$this->idEvento
					);
					if ($evento->evt_iva > 0)
					{
						$costo->costo = $costo->costo * $evento->evt_iva;
					}

					$this->fova->agregarCampo("item_costo_unitario_" . $i, $costo->costo);
				}

			}

			if ($this->fova->validacion['status'])
			{
				if ($this->model->guardarItems($this->fova->campos, $this->idRegistro, $this->user_login['id']))
				{
					$this->json = array(
						"status" => "funcion",
						"nomFuncion" => "finAltaItems",
						"idRegistro" => $this->idRegistro,
						"mensaje" => "El información se guardo con exito."
					);
				}
				else
				{
					$this->json = array(
						"status" => false,
						"mensaje" => "El información se guardo con exito."
					);
				}
			}
			else
			{
				$this->json = $this->fova->validacion;
			}

			echo json_encode($this->json);
		}
	}

	/**
	 * Metodo que lista los costos del item seleccionado
	 * 
	 */
	public function listaCostosCategoriaItem()
	{
		if (!isset($_POST['cve']))
		{
			header ("HTTP/1.1 514 An Error");
			return;
		}

		$clave = $_POST['cve'];

		$opciones = "<option value=''>Seleccione:</option>";
		$precios = $this->evt->getCostosCategoriaItem($clave, $this->idEvento);

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
			$this->json = "Error";
		}
		else
		{
			$clave = $_POST['clave'];
			$idCosto = $_POST['idCosto'];
			$cantidad = $_POST['cantidad'];

			$costo = $this->evt->getCostoItem($clave, $idCosto, $this->idEvento);

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
				$this->json = "Error";
			}
		}

		echo $this->json;
	}

	/**
	 * Metodo que agrega los datos de facturación
	 * 
	 */
	public function agregarDatosFacturacion()
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
			if (!$this->idRegistro)
			{
				header ("HTTP/1.1 514 An Error");
				exit();
			}

			$this->fova->campos = $_POST;

			$this->fova->validarCampo("razon_social_RS", "req", "", 0, 175);

			if ($this->fova->campos['pais_RS'] == 146) // si es México
			{
				$this->fova->validarCampo("rfc_RS", "req", "", 0, 20);
				$this->fova->validarCampo("del_o_mun_RS", "req", "", 0, 40);
			}
			else
			{
				$this->fova->agregarCampo('rfc_RS', "");
				$this->fova->agregarCampo('del_o_mun_RS', "");
			}

			$this->fova->validarCampo("direccion_RS", "req", "", 0, 75);
			$this->fova->validarCampo("cp_RS", "req", "", 5, 5, "INT");
			$this->fova->validarCampo("colonia_RS", "req", "", 0, 40);
			$this->fova->validarCampo("estado_RS", "req", "", 0, 40);
			$this->fova->validarCampo("ciudad_RS", "req", "", 0, 40);
			$this->fova->validarCampo("pais_RS", "req", "", 1, 3);
			$this->fova->validarCampo("lada_telefono_RS", "", "", 0, 3, "INT");
			$this->fova->validarCampo("telefono_RS", "req", "", 0, 25, "INT");
			$this->fova->validarCampo("fax_RS", "", "", 0, 25, "INT");
			$this->fova->validarCampo("email_RS", "", "", 0, 125, "MAIL");

			if ($this->fova->validacion['status'])
			{
				if ($this->model->guardarDatosFacturacion($this->fova->campos, $this->idRegistro))
				{
					$this->json = array(
						"status" => "funcion",
						"nomFuncion" => "finDatosFacturacion",
						"mensaje" => "El nuevo registro fue agregado con exito.",
					);
				}
				else
				{
					$this->json = array(
						"status" => false,
						"mensaje" => "No fue posible guardar el registro, intentalo de nuevo."
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
	 * Metodo que obtiene los detalles de un codigo postal
	 * 
	 */
	public function detallesCP()
	{
		if (isset($_POST['cp']) && !empty($_POST['cp']))
		{
			$cp = $_POST['cp'];
			$this->json = array(
				"cps" => $this->model->getDatosCP($cp)
			);
		}

		echo json_encode($this->json);
	}

	/**
	 * Metodo que busca registros
	 */
	public function buscarRegistros()
	{
		$registros = array();

		// Busqueda por ID de Registro
		if (!empty($_POST['id_registro_b']))
		{
			$id_registro = trim($_POST['id_registro_b']);
			$tabla = $_POST['nombre_tabla'];

			$registros = $this->evt->getRegistroID($id_registro, $tabla);
		}
		// Busqueda por email
		else if (!empty($_POST['email_b']))
		{
			$email = trim(stripslashes($_POST['email_b']));
			$tabla = $_POST['nombre_tabla'];

			$registros = $this->evt->getRegistroEmail($email, $tabla);
		}
		// Busqueda por empresa o instituación
		else if (!empty($_POST['emp_o_ins_b']))
		{
			$emp_o_ins = trim(stripslashes($_POST['emp_o_ins_b']));
			$tabla = $_POST['nombre_tabla'];

			$registros = $this->evt->getRegistroEmpOInt($emp_o_ins, $tabla);			
		}
		// Busqueda por nombre, app o/y app
		else
		{
			$app = $apm = $nombre = "";
			$tabla = $_POST['nombre_tabla'];

			if (!empty($_POST['app_b']))
			{
				$app = trim(stripslashes($_POST['app_b']));
			}

			if (!empty($_POST['apm_b']))
			{
				$apm = trim(stripslashes($_POST['apm_b']));
			}

			if (!empty($_POST['nombre_b']))
			{
				$nombre = trim(stripslashes($_POST['nombre_b']));
			}

			if ($app || $apm || $nombre)
			{
				$registros = $this->evt->getRegistros($app, $apm, $nombre, $tabla);
			}
			else
			{
				$registros = $this->evt->getRegistrosInicio($tabla);
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

	/**
	 * Metodo que busca un registro por acompañante
	 */
	public function buscarAcompanante()
	{
		$registros = array();

		if (!empty($_POST['acom_b']))
		{
			$nombre = trim($_POST['acom_b']);

			// Obtenemos datos del evento
			$evento = $this->model->getEvento($this->idEvento);

			$registros = $this->evt->getRegistroAcompanante($nombre, $evento->evt_clave);
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

	public function editarRegistro()
	{
		if (!isset($_GET['id']) || empty($_GET['id']))
		{
			header ("HTTP/1.1 514 An Error");
			return;
		}
		else
		{
			$idReg = $_GET['id'];

			$this->json = array(
				"status" => "funcion",
				"nomFuncion" => "formEditarRegistro",
				"idReg" => $idReg,
				"seccion" => $this->seccion
			);
		}

		echo json_encode($this->json);
	}

	/**
	 * Metodo que muestra el formulario para la edicion de registro
	 */
	public function formEditarRegistro()
	{
		if (!isset($_GET['idReg']) || empty($_GET['idReg']))
		{
			header ("HTTP/1.1 514 An Error");
			return;
		}
		else
		{
			$idRegistro = $_GET['idReg'];
			$this->sess->set("idRegistro", $idRegistro);

			// Obtenemos datos del evento
			$evento = $this->model->getEvento($this->idEvento);

			// Obtenemos los datos del registros
			$registro = $this->model->getRegistro($idRegistro, $evento->evt_clave);

			$itemsEvento = $this->evt->getNombresItems($this->idEvento);
			$acompanantes = $this->model->getAcompanantes($idRegistro, $evento->evt_clave);
			$itemsAsistentes = $this->model->getItems($idRegistro, $evento->evt_clave);

			if (!empty($acompanantes))
			{
				foreach ($acompanantes as $key => $acompanante)
				{
					$acompanantes[$key]->costos = $this->evt->getCostosCategoriaAcom($acompanante->acm_clave, $this->idEvento);
				}
			}

			if (!empty($itemsAsistentes))
			{
				foreach ($itemsAsistentes as $key => $item)
				{
					$itemsAsistentes[$key]->costos = $this->evt->getCostosCategoriaItem($item->item_clave, $this->idEvento);
					$itemsAsistentes[$key]->item_costo_total = $this->func->moneda2screen($item->item_costo_total);
				}
			}

			// Obtenemos el total de items
			foreach ($itemsEvento as $key => $item)
			{
				$itemsEvento[$key]->total_conf = $this->evt->getTotalItems($item->eni_clave, $evento->evt_clave);
			}

			$costoTotalRegistro = $this->func->moneda2screen($this->evt->costoTotalRegistro($registro->id_registro, $evento->evt_clave));

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
					"seccion" => $this->seccion,
					"evento" => $this->model->getEvento($this->idEvento),
					"titulos" => $this->evt->getTitulos($this->idEvento),
					"idiomas" => $this->evt->getIdiomas($this->idEvento),
					"formasPago" => $this->evt->getNombresFormasPagosReg($this->idEvento),
					"statusRegs" => $this->evt->getStatusRegistro($this->idEvento),
					"categorias" => $this->evt->getNombresCategorias($this->idEvento),
					"costosCategoria" => $this->evt->getCostosCategoria($registro->cat_registro, $this->idEvento),
					"categoriasAcom" => $this->evt->getNombresCategoriasAcom($this->idEvento),
					"hoteles" => $this->evt->getHoteles($this->idEvento),
					"reservacion" => $reservacion,
					"res_numAdultos" => !empty($reservacion->res_numAdultos) ? json_decode($reservacion->res_numAdultos) : '',
					"res_numMenores" => !empty($reservacion->res_numMenores) ? json_decode($reservacion->res_numMenores) : '',
					"habitaciones" => $habitaciones,
					"specHabitacion" => $specHabitacion,
					"items" => $itemsEvento,
					"generos" => $this->func->getGeneros(),
					"paises" => $this->func->getPaises(),
					"maxIdAcom" => $this->model->getMaxIdAcom($registro->id_registro, $evento->evt_clave),
					"acompanantes" => $acompanantes,
					"maxIdItem" => $this->model->getMaxIdItem($registro->id_registro, $evento->evt_clave),
					"adicionales" => $itemsAsistentes,
					"registro" => $registro,
					"costoRegistro" => $costoTotalRegistro,
				)
			);
		}
	}

	public function buscarTag($request)
	{
		$this->json = array(
			'status' => false
		);

		if (!empty($request->get['id_tag']))
		{
			$tag = $this->func->generarTagRFID($request->get['id_tag']);

			// Obtenemos datos del evento
			$evento = $this->model->getEvento($this->idEvento);

			if ($this->model->buscarTag($evento->evt_clave, $tag, $this->sess->get('idRegistro')))
			{
				$this->json = array(
					'status' => true
				);
			}
		}

		echo json_encode($this->json);
	}

	public function buscarTagOtro($request)
	{
		$this->json = array(
			'status' => false
		);

		if (!empty($request->get['id_tag']))
		{
			$tag = $this->func->generarTagRFID($request->get['id_tag']);

			// Obtenemos datos del evento
			$evento = $this->model->getEvento($this->idEvento);

			if ($this->model->buscarTagOtro($evento->evt_clave, $tag, $this->sess->get('idRegistro')))
			{
				$this->json = array(
					'status' => true
				);
			}
		}

		echo json_encode($this->json);
	}

	/**
	 * Metodo que actualiza el registro
	 * 
	 */
	public function actualizarRegistro($request)
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
			if (!isset($_POST['id_registro']))
			{
				header ("HTTP/1.1 514 An Error");
				exit();
			}

			$evento = $this->model->getEvento($this->idEvento);
			$this->fova->campos = $request->query;

			$this->fova->validarCampo("nombre", "req", "", 2, 100);
			$this->fova->validarCampo("app", "req", "", 2, 50);
			$this->fova->validarCampo("apm", "", "", 2, 50);
			$this->fova->validarCampo("titulo", "", "", 0, 20);
			$this->fova->validarCampo("rfc", "", "", 0, 13);
			$this->fova->validarCampo("curp", "", "", 0, 18);
			$this->fova->validarCampo("genero", "", "", 1, 1);
			//$this->fova->validarCampo("idioma", "req", "", 2, 2);
			$this->fova->validarCampo("emp_o_ins", "", "", 2, 125);
			$this->fova->validarCampo("cargo", "", "", 3, 50);
			$this->fova->validarCampo("email", "", "", 8, 125);
			$this->fova->validarCampo("id_tag", "", "", 1, 0, 'INT');

			$this->fova->validarCampo("cat_registro", "req", "", 2, 10);
			$this->fova->validarCampo("id_costo", "req", "", 1, 2, "INT");
			$this->fova->validarCampo("forma_pago", "req", "", 2, 2);
			$this->fova->validarCampo("status", "req", "", 3, 3);

			$this->fova->validarCampo("clave_asociada", "", "", 0, 15);

			if ($this->fova->validacion['status'])
			{
				// Obtenemos el costo de la categoria para el registro
				$costo_total = $this->evt->getCostoCategoria(
					$this->fova->campos['cat_registro'],
					$this->fova->campos['id_costo'],
					$this->idEvento
				);

				if ($evento->evt_iva > 0)
				{
					$costo_total->costo = $costo_total->costo * $evento->evt_iva;
				}

				$this->fova->agregarCampo("costo_registro", $costo_total->costo);

				$this->fova->campos['id_tag'] = $this->func->generarTagRFID($this->fova->campos['id_tag']);

				$this->fova->campos['costo_total'] = $this->func->moneda2db($request->query['costo_total']);

				if ($this->model->actualizarRegistro($this->fova->campos, $this->user_login['id']))
				{
					$totalRegPagados = 0;
					$regStatus = "PAG";
					if ($this->fova->campos['status'] == $regStatus)
					{
						$totalRegPagados = $this->evt->getTotalRegistros($this->fova->campos['tabla_registros'], $regStatus);
					}

					if ($this->fova->campos['save_photo'] == 1)
					{
						if (!$this->guardarFotografia($this->fova->campos['photo'], $this->fova->campos['id_registro']))
						{
							$fotoMensaje = 'El fotografia no fue guardad, por favor intentalo nuevamente.';
						}
					}

					$this->json = array(
						"status" => "funcion",
						"nomFuncion" => "finActualizarRegistro",
						"seccion" => $this->seccion,
						"idReg" => $this->fova->campos['id_registro'],
						"statusReg" => $this->fova->campos['status'],
						"formaPago" => $this->fova->campos['forma_pago'],
						"totalRegPagados" => $totalRegPagados,
						"mensaje" => "El registro se actualizo con exito.",
					);
				}
				else
				{
					$this->json = array(
						"status" => false,
						"mensaje" => "No fue posible actualizar el registro, intentalo de nuevo."
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
	 * Metodo que elimina la fotografia
	 */
	public function eliminarFotografia()
	{
		if (isset($_GET['id']) && !empty($_GET['id']))
		{
			$idRegistro = $_GET['id'];

			if ($this->sess->get("idRegistro") == $idRegistro)
			{
				$evento = $this->model->getEvento($this->idEvento);

				if ($this->model->eliminarFotografia($idRegistro, $evento->evt_clave))
				{
					$this->json = array(
						'status' => 'funcion',
						'nomFuncion' => 'eliminarFotografia',
						'seccion' => $this->seccion
					);
				}
				else
				{
					$this->json = array(
						'status' => 'funcion',
						'nomFuncion' => 'errorEliminarFotografia',
						'mensaje' => 'No fue posible eliminar la fotografia de este registro, intentalo de nuevo por favor.'
					);
				}
			}
			else
			{
				header ("HTTP/1.1 514 An Error");
				exit();
			}
		}
		else
		{
			header ("HTTP/1.1 514 An Error");
			exit();
		}

		echo json_encode($this->json);
	}

	/**
	 * Metodo que actualiza los datos de contacto
	 * 
	 */
	public function actualizarDatosContacto()
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
			if (!isset($_POST['id_registro']))
			{
				header ("HTTP/1.1 514 An Error");
				exit();
			}

			$this->fova->campos = $_POST;

			$this->fova->validarCampo("direccion", "", "", 0, 75);
			$this->fova->validarCampo("cp", "", "", 0, 5, "INT");
			$this->fova->validarCampo("colonia", "", "", 0, 40);

			if ($this->fova->campos['pais'] == 146) // si es México
			{
				$this->fova->validarCampo("del_o_mun", "", "", 0, 40);
			}
			else
			{
				$this->fova->agregarCampo('del_o_mun', "");
			}

			$this->fova->validarCampo("estado", "", "", 0, 40);
			$this->fova->validarCampo("ciudad", "", "", 0, 40);
			$this->fova->validarCampo("pais", "req", "", 1, 3, "INT");
			$this->fova->validarCampo("lada_telefono", "", "", 0, 2, "INT");
			$this->fova->validarCampo("telefono_particular", "", "", 0, 25, "INT");
			$this->fova->validarCampo("telefono_movil", "", "", 0, 25, "INT");

			if ($this->fova->validacion['status'])
			{
				if ($this->model->actualizarDatosContacto($this->fova->campos, $this->user_login['id']))
				{
					$this->json = array(
						"status" => "funcion",
						"nomFuncion" => "finActualizarDatosContacto",
						"mensaje" => "El registro se actualizo con exito."
					);
				}
				else
				{
					$this->json = array(
						"status" => false,
						"mensaje" => "No fue posible actualizar la información, intentalo de nuevo."
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
	 * Metodo que actualiza un item
	 */
	public function actualizarItem()
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
			if (!$this->idRegistro)
			{
				header ("HTTP/1.1 514 An Error");
				exit();
			}

			$evento = $this->model->getEvento($this->idEvento);
			$this->fova->campos = $_POST;

			if (!isset($this->fova->campos['clave_evento'], $this->fova->campos['num_item']))
			{
				header ("HTTP/1.1 514 An Error");
				exit();
			}

			$i = $this->fova->campos['num_item'];

			// Clave
			$this->fova->validarCampo("item_clave_" . $i, "req", "", 0, 10);

			// Cantidad
			if (isset($this->fova->campos["item_cantidad_" . $i]))
			{
				$this->fova->validarCampo("item_cantidad_" . $i, "req", "", 0, 0, "INT");
			}

			// Costo
			if (isset($this->fova->campos["item_id_costo_" . $i]) && !empty($this->fova->campos["item_clave_" . $i]))
			{
				$this->fova->validarCampo("item_id_costo_" . $i, "req", "", 1, 2);

				$costo = $this->evt->getCostoItem(
					$this->fova->campos["item_clave_" . $i],
					$this->fova->campos["item_id_costo_" . $i],
					$this->idEvento
				);

				if ($evento->evt_iva > 0)
				{
					$costo->costo = $costo->costo * $evento->evt_iva;
				}

				$this->fova->agregarCampo("item_costo_unitario_" . $i, $costo->costo);
			}

			// Costo total
			if (isset($this->fova->campos["item_costo_total_" . $i]))
			{
				$this->fova->validarCampo("item_costo_total_" . $i, "req", "", 0, 0, "MONE");
				$this->fova->campos["item_costo_total_" . $i] = $this->func->moneda2db($this->fova->campos["item_costo_total_" . $i]);	
			}

			// Status
			if (isset($this->fova->campos["item_status_" . $i]))
			{
				$this->fova->validarCampo("item_status_" . $i, "req", "", 0, 3);
			}

			if ($this->fova->validacion['status'])
			{
				if ($this->model->actualizarItem($this->fova->campos, $this->idRegistro, $this->user_login['id']))
				{
					$this->json = array(
						"status" => "funcion",
						"nomFuncion" => "finActualizarItem",
						"idItem" => $i,
						"mensaje" => "El información se guardo con exito."
					);
				}
				else
				{
					$this->json = array(
						"status" => false,
						"mensaje" => "No fue posible actualizar el item."
					);
				}
			}
			else
			{
				$this->json = $this->fova->validacion;
			}

			echo json_encode($this->json);
		}
	}

	/**
	 * Metodo que actualiza los datos de facturación
	 * 
	 */
	public function actualizarDatosFacturacion()
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
			if (!isset($_POST['id_registro']))
			{
				header ("HTTP/1.1 514 An Error");
				exit();
			}

			$this->fova->campos = $_POST;

			$this->fova->validarCampo("razon_social_RS", "req", "", 0, 175);

			if ($this->fova->campos['pais_RS'] == 146) // si es México
			{
				$this->fova->validarCampo("rfc_RS", "req", "", 0, 20);
				$this->fova->validarCampo("del_o_mun_RS", "req", "", 0, 40);
			}
			else
			{
				$this->fova->agregarCampo('rfc_RS', "");
				$this->fova->agregarCampo('del_o_mun_RS', "");
			}

			$this->fova->validarCampo("direccion_RS", "req", "", 0, 75);
			$this->fova->validarCampo("cp_RS", "req", "", 5, 5, "INT");
			$this->fova->validarCampo("colonia_RS", "req", "", 0, 40);
			$this->fova->validarCampo("estado_RS", "req", "", 0, 40);
			$this->fova->validarCampo("ciudad_RS", "req", "", 0, 40);
			$this->fova->validarCampo("pais_RS", "req", "", 1, 3);
			$this->fova->validarCampo("lada_telefono_RS", "", "", 0, 3, "INT");
			$this->fova->validarCampo("telefono_RS", "req", "", 0, 25, "INT");
			$this->fova->validarCampo("fax_RS", "", "", 0, 25, "INT");
			$this->fova->validarCampo("email_RS", "", "", 0, 125, "MAIL");

			if ($this->fova->validacion['status'])
			{
				if ($this->model->actualizarDatosFacturacion($this->fova->campos, $this->user_login['id']))
				{
					$this->json = array(
						"status" => "funcion",
						"nomFuncion" => "finActualizarDatosFacturacion",
						"mensaje" => "El registro se actualizo con exito.",
					);
				}
				else
				{
					$this->json = array(
						"status" => false,
						"mensaje" => "No fue posible actualizar el registro, intentalo de nuevo."
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

	/*public function formHospedaje()
	{
		$evento = $this->model->getEvento($this->idEvento);

		$evento->evt_inicio = $this->func->date2screen($evento->evt_inicio);
		$evento->evt_termina = $this->func->date2screen($evento->evt_termina);

		$this->json = array(
			'status' => 'funcion',
			'nomFuncion' => 'formAgregarHospedaje',
			'seccion' => $this->seccion,
			'evento' => $evento,
			'hoteles' => $this->evt->getHoteles($this->idEvento),
			"formasPago" => $this->evt->getNombresFormasPagosReg($this->idEvento),
			'statusRes' => $this->evt->getStatusRegistro($this->idEvento)
		);

		echo json_encode($this->json);
	}*/

	/**
	 * Metodo que devuelve las habitaciones del hotel indicado
	 */
	public function getHabitaciones($request)
	{
		if (isset($request->get['id_hotel']) && !empty($request->get['id_hotel']))
		{
			$id_hotel = $request->get['id_hotel'];

			$habitaciones = $this->evt->getHabitaciones($this->idEvento, $id_hotel);

			echo json_encode(array('habitaciones' => $habitaciones));
		}
		else
		{
			header ('HTTP/1.1 513 An Error');
			exit();
		}
	}

	/**
	 * Metodo que devuelve las especificaciones de la habitacion indicada
	 */
	public function getEspecHabitacion($request)
	{
		if (isset($request->get['id_hotel']) && !empty($request->get['id_hotel']) && 
			isset($request->get['id_habitacion']) && !empty($request->get['id_habitacion']))
		{
			$id_hotel = $request->get['id_hotel'];
			$id_habitacion = $request->get['id_habitacion'];

			$spec = $this->evt->getEspecHabitacion($this->idEvento, $id_hotel, $id_habitacion);

			$spec->hhc_costoAdulto = $this->func->moneda2screen($spec->hhc_costoAdulto);
			$spec->hhc_costoMenor = $this->func->moneda2screen($spec->hhc_costoMenor);
			$spec->hhc_costoCamaristaNoche = $this->func->moneda2screen($spec->hhc_costoCamaristaNoche);
			$spec->hhc_costoBellBoys = $this->func->moneda2screen($spec->hhc_costoBellBoys);

			echo json_encode(
				array(
					'costo_adulto' => $spec->hhc_costoAdulto,
					'costo_menor' => $spec->hhc_costoMenor,
					'costo_camarista' => $spec->hhc_costoCamaristaNoche,
					'costo_bellBoys' => $spec->hhc_costoBellBoys,
					'pax_cuartos' => $spec->hhb_paxMaxReservacion,
					'pax_adultos' => $spec->hhb_paxAdultos,
					'pax_menores' => $spec->hhb_paxMenores,
					'pax' => $spec->hhb_pax,
				)
			);
		}
		else
		{
			header ('HTTP/1.1 513 An Error');
			exit();
		}		
	}	

	/**
	 * Metodo que valida la reservacion
	 */
	public function setHospedaje($request)
	{
		if (isset($_POST) && !empty($_POST))
		{
			$costo_adultos = 0;
			$costo_menores = 0;

			// Obtenemos datos del evento
			$evento = $this->model->getEvento($this->idEvento);

			$request->query['res_llegada'] = $this->func->date2db($request->query['res_llegada']);
			$request->query['res_salida'] = $this->func->date2db($request->query['res_salida']);
			if (!empty($request->query['res_anticipo']))
			{
				$request->query['res_anticipo'] = $this->func->moneda2db($request->query['res_anticipo']);
			}
			else
			{
				$request->query['res_anticipo'] = '0.00';
			}

			$datetime1 = new DateTime($request->query['res_llegada']);
			$datetime2 = new DateTime($request->query['res_salida']);
			$interval = $datetime1->diff($datetime2);
			$noches = $interval->format('%R%a');

			$spec = $this->evt->getEspecHabitacion($this->idEvento, $request->query['res_idHotel'], $request->query['res_idHabitacion']);

			$request->query['res_folio'] = time();
			$request->query['res_clave'] = $this->func->mayusStr($this->func->generarID(8));
			$json_habs_adultos = "{";
			$json_habs_menores = "{";

			foreach ($request->query['habitacion'] as $desc => $rooms)
			{
				if ($desc == 'adultos')
				{
					$json_habs_adultos .= '"' . $desc . '":[';
					foreach ($rooms as $key => $ocupacion)
					{
						$json_habs_adultos .= '{"hab":' . $ocupacion . '},';
						$costo_adultos += (($spec->hhc_costoAdulto * $noches) * $ocupacion);
					}
					$json_habs_adultos = trim($json_habs_adultos, ',');
					$json_habs_adultos .= ']}';
				}

				if ($desc == 'menores')
				{
					$json_habs_menores .= '"' . $desc . '":[';
					foreach ($rooms as $key => $ocupacion)
					{
						$json_habs_menores .= '{"hab":' . $ocupacion . '},';
						$costo_menores += (($spec->hhc_costoMenor * $noches) * $ocupacion);
					}
					$json_habs_menores = trim($json_habs_menores, ',');
					$json_habs_menores .= ']}';
				}
			}

			//$costo_adultos = $spec->hhc_costoAdulto * $noches;
			//$costo_menores = $spec->hhc_costoMenor * $noches;
			$request->query['json_habs_adultos'] = $json_habs_adultos;
			$request->query['json_habs_menores'] = $json_habs_menores;
			$request->query['res_costoTotal'] = $costo_adultos + $costo_menores + $spec->hhc_costoCamaristaNoche + $spec->hhc_costoBellBoys;
			$request->query['res_saldo'] = $request->query['res_costoTotal'] - $request->query['res_anticipo'];

			if (($request->query['id_reservacion'] = $this->model->agregarReservacion($request->query, $evento->evt_clave, $this->idRegistro, $this->user_login['id'])) != false)
			{
				$this->json = array(
					"status" => true,
					"mensaje" => 'Reservación agregada con exito!',
					'reservacion' => $request->query['id_reservacion']
				);
			}
			else
			{
				$this->json = array(
					"status" => false,
					"mensaje" => "No fue posible agregar la reservación, intentalo de nuevo."
				);
			}
		}

		echo json_encode($this->json);
	}

	/**
	 * Metodo que valida la reservacion
	 */
	public function setUpdateHospedaje($request)
	{
		if (isset($_POST) && !empty($_POST))
		{
			$costo_adultos = 0;
			$costo_menores = 0;

			// Obtenemos datos del evento
			$evento = $this->model->getEvento($this->idEvento);

			$request->query['res_llegada'] = $this->func->date2db($request->query['res_llegada']);
			$request->query['res_salida'] = $this->func->date2db($request->query['res_salida']);
			if (!empty($request->query['res_anticipo']))
			{
				$request->query['res_anticipo'] = $this->func->moneda2db($request->query['res_anticipo']);
			}
			else
			{
				$request->query['res_anticipo'] = '0.00';
			}

			$datetime1 = new DateTime($request->query['res_llegada']);
			$datetime2 = new DateTime($request->query['res_salida']);
			$interval = $datetime1->diff($datetime2);
			$noches = $interval->format('%R%a');

			$spec = $this->evt->getEspecHabitacion($this->idEvento, $request->query['res_idHotel'], $request->query['res_idHabitacion']);

			//$request->query['res_folio'] = time();
			//$request->query['res_clave'] = $this->func->mayusStr($this->func->generarID(8));
			$json_habs_adultos = "{";
			$json_habs_menores = "{";

			foreach ($request->query['habitacion'] as $desc => $rooms)
			{
				if ($desc == 'adultos')
				{
					$json_habs_adultos .= '"' . $desc . '":[';
					foreach ($rooms as $key => $ocupacion)
					{
						$json_habs_adultos .= '{"hab":' . $ocupacion . '},';
						$costo_adultos += (($spec->hhc_costoAdulto * $noches) * $ocupacion);
					}
					$json_habs_adultos = trim($json_habs_adultos, ',');
					$json_habs_adultos .= ']}';
				}

				if ($desc == 'menores')
				{
					$json_habs_menores .= '"' . $desc . '":[';
					foreach ($rooms as $key => $ocupacion)
					{
						$json_habs_menores .= '{"hab":' . $ocupacion . '},';
						$costo_menores += (($spec->hhc_costoMenor * $noches) * $ocupacion);
					}
					$json_habs_menores = trim($json_habs_menores, ',');
					$json_habs_menores .= ']}';
				}
			}

			//$costo_adultos = $spec->hhc_costoAdulto * $noches;
			//$costo_menores = $spec->hhc_costoMenor * $noches;
			$request->query['json_habs_adultos'] = $json_habs_adultos;
			$request->query['json_habs_menores'] = $json_habs_menores;
			$request->query['res_costoTotal'] = $costo_adultos + $costo_menores + $spec->hhc_costoCamaristaNoche + $spec->hhc_costoBellBoys;
			$request->query['res_saldo'] = $request->query['res_costoTotal'] - $request->query['res_anticipo'];

			if ($this->model->actualizarReservacion($request->query, $evento->evt_clave, $this->idRegistro, $this->user_login['id']))
			{
				$this->json = array(
					"status" => true,
					"mensaje" => 'Reservación actualizada con exito!'
				);
			}
			else
			{
				$this->json = array(
					"status" => false,
					"mensaje" => "No fue posible actualizar la reservación, intentalo de nuevo."
				);
			}
		}

		echo json_encode($this->json);
	}

	/**
	 * Metodo que gestiona las acciones de impresion de gafete 
	 */
	public function impresionGafete()
	{
		if (!isset($_GET['id']) || empty($_GET['id']))
		{
			header ("HTTP/1.1 514 An Error");

			exit ();
		}
		else
		{
			$idRegistro = $_GET['id'];

			// Obtenemos datos del evento
			$evento = $this->model->getEvento($this->idEvento);

			// Obtenemos los datos del registro
			$registro = $this->model->getRegistro($idRegistro, $evento->evt_clave);

			// Comprovamos que el estatus sea pagado en cortesia o CCC
			if ($registro->status == "PAG" || $registro->status == "COR" || $registro->status == "CCC" || $registro->status == 'EXE')
			{
				// Gafete aun no impreso
				if ($registro->impresion_gafete == 0)
				{
					$this->evt->setPrimerGafate($registro->id_registro, $evento->evt_clave, $this->user_login['id']);

					$totalRegistrosImp = $this->evt->getTotalRegistrosImp($evento->evt_clave);

					$this->json = array(
						"status" => "funcion",
						"nomFuncion" => "impresionGafete", // lecturaImpresion
						"seccion" => $this->seccion . "/?action=imprimirGafete&id=" . $registro->id_registro,
						'idReg' => $registro->id_registro,
						"totalRegistrosImp" => $totalRegistrosImp
					);
				}
				else
				{
					$registro->fecha_impresion_gafete = $this->func->date2screen($registro->fecha_impresion_gafete, true, " a las ");

					$this->json = array(
						"status" => "funcion",
						"nomFuncion" => "reImpresionGafate",
						"seccion" => $this->seccion . "/?action=validarPasswordAdminReimpresion&id=" . $registro->id_registro,
						"registro" => array(
							"id" => $registro->id_registro,
							"ultimaImpresion" => $registro->fecha_impresion_gafete,
							"totalImpresiones" => $registro->impresion_total_gafete
						)
					);
				}
			}
			else
			{
				$this->json = array(
					"status" => "funcion",
					"nomFuncion" => "impresionNoPermitida",
					"mensaje" => "Este registro no esta pagado, por esta razón no puede imprimir el gafete."
				);
			}

		}

		echo json_encode($this->json);
	}

	/**
	 * Metodo que imprime el gafete
	 */
	public function lecturaImpresion($request)
	{
		if (!isset($request->query['id_registro_re']) || empty($request->query['id_registro_re']))
		{
			die ("Error de registro");
		}
		else
		{
			$idRegistro = $request->query['id_registro_re'];
		}

		// Obtenemos datos del evento
		$evento = $this->model->getEvento($this->idEvento);

		// Obtenemos los datos del registro
		$registro = $this->model->getRegistro($idRegistro, $evento->evt_clave);

		if (!empty($request->query['lectura_pistola']))
		{
			$request->query['lectura_pistola'] = $this->func->generarTagRFID($request->query['lectura_pistola']);
			// Guardara lectura
			$this->model->setLectura($idRegistro, $evento->evt_clave, $request->query);
		}
		/*else
		{
			$idRegistro = $this->model->setRegistro($post, $this->user_login['id']);
			$tagRFID = $this->func->generarTagRFID($idRegistro);
			$this->model->setTagRFID($tagRFID, $idRegistro, $post['tabla_registros']);
		}*/

		$this->evt->cargarGafetePDF($this->idEvento, $evento, $registro, $this->func);
	}

	/**
	 * Metodo que imprime el gafete
	 */
	public function imprimirGafete()
	{
		if (!isset($_GET['id']) || empty($_GET['id']))
		{
			die ("Error de registro");
		}
		else
		{
			$idRegistro = $_GET['id'];
		}

		// Obtenemos datos del evento
		$evento = $this->model->getEvento($this->idEvento);

		// Obtenemos los datos del registro
		$registro = $this->model->getRegistro($idRegistro, $evento->evt_clave);
		$registro->talleres = $this->model->getRegistroTalleres($idRegistro, $evento->evt_clave);

		$this->evt->cargarGafetePDF($this->idEvento, $evento, $registro, $this->func);
	}

	/**
	 * Metodo que reimprime el gafete
	 */
	public function setReImpresionGafete()
	{
		if (!isset($_GET['id']) || empty($_GET['id']))
		{
			die ("Error de registro");
		}
		else
		{
			$idRegistro = $_GET['id'];
		}

		// Obtenemos datos del evento
		$evento = $this->model->getEvento($this->idEvento);

		// Obtenemos los datos del registro
		$registro = $this->model->getRegistro($idRegistro, $evento->evt_clave);
		$registro->talleres = $this->model->getRegistroTalleres($idRegistro, $evento->evt_clave);

		if ($this->evt->setReImpresionGafate($registro->id_registro, $evento->evt_clave, $this->user_login['id']))
		{
			$this->evt->cargarGafetePDF($this->idEvento, $evento, $registro, $this->func);
		}
		else
		{
			die ("Error al reimprimir el gafete, por favor cierre esta pagina.");
		}
	}

	/**
	 * Metodo que gestiona las acciones de impresion de constancia 
	 */
	public function impresionConstancia()
	{
		if (!isset($_GET['id']) || empty($_GET['id']))
		{
			header ("HTTP/1.1 514 An Error");

			exit ();
		}
		else
		{
			$idRegistro = $_GET['id'];

			// Obtenemos datos del evento
			$evento = $this->model->getEvento($this->idEvento);

			// Obtenemos los datos del registro
			$registro = $this->model->getRegistro($idRegistro, $evento->evt_clave);

			// Comprovamos que el estatus sea pagado en cortesia o CCC
			if ($registro->status == "PAG" || $registro->status == "COR" || $registro->status == "CCC" || $registro->status == 'EXE')
			{
				// Constancia aun no impreso
				if ($registro->impresion_constancia == 0)
				{
					$this->json = array(
						"status" => "funcion",
						"nomFuncion" => "impresionConstancia",
						"seccion" => $this->seccion . "/?action=imprimirConstancia&id=" . $registro->id_registro,
						"idRegistro" => $registro->id_registro
					);
				}
				else
				{
					$registro->fecha_impresion_constancia = $this->func->date2screen($registro->fecha_impresion_constancia, true, " a las ");

					$this->json = array(
						"status" => "funcion",
						"nomFuncion" => "reImpresionConstancia",
						"seccion" => $this->seccion . "/?action=validarPasswordAdminReimpresion&id=" . $registro->id_registro,
						"registro" => array(
							"id" => $registro->id_registro,
							"ultimaImpresion" => $registro->fecha_impresion_constancia,
							"totalImpresiones" => $registro->impresion_total_constancia
						)
					);
				}
			}
			else
			{
				$this->json = array(
					"status" => "funcion",
					"nomFuncion" => "impresionNoPermitida",
					"mensaje" => "Este registro no esta pagado, por esta razón no puede imprimir la constancia."
				);
			}

		}

		echo json_encode($this->json);
	}

	/**
	 * Metodo que imprime la constancia
	 */
	public function imprimirConstancia()
	{
		if (!isset($_GET['id'], $_POST['folioConstancia']) || empty($_GET['id']))
		{
			die ("Error de registro");
		}
		else
		{
			$idRegistro = $_POST['id_registro_re'];
			$folio = $_POST['folioConstancia'];
		}

		// Obtenemos datos del evento
		$evento = $this->model->getEvento($this->idEvento);

		$this->evt->setPrimerConstancia($idRegistro, $evento->evt_clave, $this->user_login['id']);
		$this->model->setFolioConstancia($idRegistro, $folio, $evento->evt_clave);

		// Obtenemos los datos del registro
		$registro = $this->model->getRegistro($idRegistro, $evento->evt_clave);

		$this->evt->cargarConstanciaPDF($this->idEvento, $evento, $registro, $this->func);
	}

	/**
	 * Metodo que reimprime la constancia
	 */
	public function setReImpresionConstancia()
	{
		if (!isset($_GET['id'], $_GET['folio']) || empty($_GET['id']))
		{
			die ("Error de registro");
		}
		else
		{
			$idRegistro = $_GET['id'];
			$folio = $_GET['folio'];
		}

		// Obtenemos datos del evento
		$evento = $this->model->getEvento($this->idEvento);

		// Obtenemos los datos del registro
		$registro = $this->model->getRegistro($idRegistro, $evento->evt_clave);

		if ($this->evt->setReImpresionConstancia($registro->id_registro, $evento->evt_clave, $this->user_login['id']))
		{
			$this->model->setFolioConstancia($idRegistro, $folio, $evento->evt_clave);
			$this->evt->cargarConstanciaPDF($this->idEvento, $evento, $registro, $this->func);
		}
		else
		{
			die ("Error al reimprimir la constancia, por favor cierre esta pagina.");
		}
	}

	/**
	 * Metodo que imprime la constancia del taller
	 */
	public function imprimirConstanciaTaller()
	{
		if (!isset($_GET['id']) || empty($_GET['id']))
		{
			die ("Error de registro");
		}
		else
		{
			$idRegistro = $_GET['idReg'];
			$clave_taller = $_GET['tllr'];
		}

		// Obtenemos datos del evento
		$evento = $this->model->getEvento($this->idEvento);

		// Obtenemos los datos del registro
		$registro = $this->model->getRegistro($idRegistro, $evento->evt_clave);
		// Obtenemos los datos del taller
		$taller = $this->model->getRegistroTaller($idRegistro, $evento->evt_clave, $clave_taller);

		if (is_object($taller))
		{
			$this->evt->cargarConstanciaTallerPDF($this->idEvento, $evento, $registro, $taller, $this->func);
		}
		else
		{
			die('No fue posible generar la constancia: gafate no impreso, posiblemente no asistio al evento.');
		}
	}

	/**
	 * Metodo que gestiona las acciones de impresion de gafete de
	 * acompañante
	 */
	public function impresionGafeteAcom()
	{
		if (!isset($_GET['id']) || empty($_GET['id']))
		{
			header ("HTTP/1.1 514 An Error");

			exit ();
		}
		else
		{
			$idAcom = $_GET['id'];

			// Obtenemos datos del evento
			$evento = $this->model->getEvento($this->idEvento);

			// Obtenemos los datos del acompañante
			$acompanante = $this->model->getAcompanante($this->idRegistro, $idAcom, $evento->evt_clave);

			// Comprovamos que el estatus sea pagado, cortesia o CCC
			if ($acompanante->acm_status == "PAG" || $acompanante->acm_status == "COR" || $acompanante->acm_status == "CCC" || $acompanante->acm_status == 'EXE')
			{
				// Gafete aun no impreso
				if ($acompanante->acm_impresion_gafete == 0)
				{
					$this->evt->setPrimerGafateAcom($this->idRegistro, $acompanante->id_acompanante, $evento->evt_clave, $this->user_login['id']);

					//$totalRegistrosImp = $this->evt->getTotalRegistrosImp($evento->evt_nombreTablaAsistentes);

					$this->json = array(
						"status" => "funcion",
						"nomFuncion" => "impresionGafeteAcom",
						"seccion" => $this->seccion . "/?action=imprimirGafeteAcom&id=" . $acompanante->id_acompanante
						//"totalRegistrosImp" => $totalRegistrosImp
					);
				}
				else
				{
					$acompanante->acm_fecha_impresion_gafete = $this->func->date2screen($acompanante->acm_fecha_impresion_gafete, true, " a las ");

					$this->json = array(
						"status" => "funcion",
						"nomFuncion" => "reImpresionGafateAcom",
						"seccion" => $this->seccion . "/?action=validarPasswordAdminReimpresion&id=" . $acompanante->id_acompanante,
						"acompanante" => array(
							"id" => $acompanante->id_acompanante,
							"ultimaImpresion" => $acompanante->acm_fecha_impresion_gafete,
							"totalImpresiones" => $acompanante->acm_impresion_total_gafete
						)
					);
				}
			}
			else
			{
				$this->json = array(
					"status" => "funcion",
					"nomFuncion" => "impresionNoPermitida",
					"mensaje" => "Este acompañante no esta pagado, por esta razón no puede imprimir el gafete."
				);
			}

		}

		echo json_encode($this->json);
	}

	/**
	 * Metodo que imprime el gafete del acompañante
	 */
	public function imprimirGafeteAcom()
	{
		if (!isset($_GET['id']) || empty($_GET['id']))
		{
			die ("Error de registro");
		}
		else
		{
			$idAcom = $_GET['id'];
		}

		// Obtenemos datos del evento
		$evento = $this->model->getEvento($this->idEvento);

		// Obtenemos los datos del acompañante
		$acompanante = $this->model->getAcompanante($this->idRegistro, $idAcom, $evento->evt_clave);

		$this->evt->cargarGafetePDFAcom($this->idEvento, $evento, $acompanante, $this->func);
	}

	/**
	 * Metodo que reimprime el gafete del acompañante
	 */
	public function setReImpresionGafeteAcom()
	{
		if (!isset($_GET['id']) || empty($_GET['id']))
		{
			die ("Error de registro");
		}
		else
		{
			$idAcom = $_GET['id'];
		}

		// Obtenemos datos del evento
		$evento = $this->model->getEvento($this->idEvento);

		// Obtenemos los datos del acompañante
		$acompanante = $this->model->getAcompanante($this->idRegistro, $idAcom, $evento->evt_clave);

		if ($this->evt->setReImpresionGafateAcom($this->idRegistro, $acompanante->id_acompanante, $evento->evt_clave, $this->user_login['id']))
		{
			$this->evt->cargarGafetePDFAcom($this->idEvento, $evento, $acompanante, $this->func);
		}
		else
		{
			die ("Error al reimprimir el gafete, por favor cierre esta pagina.");
		}
	}

	/**
	 * Metodo que valida el usuario y password de administrador para reimpresion
	 */
	public function validarPasswordAdminReimpresion()
	{
		if (!isset($_POST['id_registro_re'], $_POST['username'], $_POST['password'], $_POST['reimpresion']) ||
			empty($_POST['id_registro_re']) || empty($_POST['username']) || empty($_POST['password']) || empty($_POST['reimpresion']))
		{
			$this->json = array(
				"status" => false,
				"mensaje" => "Por favor, indique usuario y contraseña del administrador."
			);
		}
		else
		{
			$folioConstancia = "";

			$idRegistro = $_POST['id_registro_re'];

			if (isset($_POST['folioConstancia']))
			{
				$folioConstancia = $_POST['folioConstancia'];	
			}

			$username = $_POST['username'];
			$password = $_POST['password'];
			$tipo = $_POST['reimpresion'];

			// Validar password de administrador
			if ($this->model->validarPasswordAdmin($username, $password))
			{
				if ($tipo == "GFT")
				{
					$this->json = array(
						"status" => "funcion",
						"nomFuncion" => "reimpresion",
						"seccion" => $this->seccion . "/?action=setReImpresionGafete&id=" . $idRegistro,
					);
				}
				else if ($tipo == "CTC")
				{
					$this->json = array(
						"status" => "funcion",
						"nomFuncion" => "reimpresion",
						"seccion" => $this->seccion . "/?action=setReImpresionConstancia&id=" . $idRegistro . "&folio=". $folioConstancia,
					);
				}
				else if ($tipo == "GFT_AC")
				{
					$this->json = array(
						"status" => "funcion",
						"nomFuncion" => "reimpresion",
						"seccion" => $this->seccion . "/?action=setReImpresionGafeteAcom&id=" . $idRegistro,
					);
				}
				else
				{
					$this->json = array(
						"status" => false,
						"mensaje" => "Tipo de reimpresion no indicado"
					);
				}
			}
			else
			{
				$this->json = array(
					"status" => false,
					"mensaje" => "Los datos ingresados no son de administrador."
				);
			}
		}

		echo json_encode($this->json);
	}

	public function pasarAsistencia($request)
	{
		$this->json = array(
			'status' => false,
			'mensaje' => 'No fue posible pasar asistencia'
		);

		if (!empty($request->query['id_registro']))
		{
			// Obtenemos datos del evento
			$evento = $this->model->getEvento($this->idEvento);

			if ($this->model->pasarAsistencia($request->query['id_registro'], $evento->evt_clave, $this->user_login['id']))
			{
				// Obtenemos los datos del registros
				$registro = $this->model->getRegistro($request->query['id_registro'], $evento->evt_clave);

				$this->json = array(
					'status' => true,
					'registro' => $registro
				);
			}
		}

		echo json_encode($this->json);
	}

	public function quitarAsistencia($request)
	{
		$this->json = array(
			'status' => false,
			'mensaje' => 'No fue posible eliminar la asistencia'
		);

		if (!empty($request->query['id_registro']))
		{
			// Obtenemos datos del evento
			$evento = $this->model->getEvento($this->idEvento);

			if ($this->model->quitarAsistencia($request->query['id_registro'], $evento->evt_clave, $this->user_login['id']))
			{
				// Obtenemos los datos del registros
				$registro = $this->model->getRegistro($request->query['id_registro'], $evento->evt_clave);

				$this->json = array(
					'status' => true
				);
			}
		}

		echo json_encode($this->json);
	}

	public function clonarRegistro($request)
	{
		$this->json = array(
			'status' => false,
			'registro' => array()
		);

		if ($registro = $this->model->getRegistro($request->get['id_registro'], $request->get['clave_evento']))
		{
			$this->json = array(
				'status' => true,
				'registro' => $registro
			);
		}

		echo json_encode($this->json);
	}

	public function verificarFolioPago($request)
	{
		$this->json = array(
			'status' => false
		);

		if (!empty($request->query['folio_pago']))
		{
			$request->query['folio_pago'] = ltrim($request->query['folio_pago'], '0');
			$id_registro = 0;

			if (!empty($request->query['id_registro']))
			{
				$id_registro = $request->query['id_registro'];
			}

			// Obtenemos datos del evento
			$evento = $this->model->getEvento($this->idEvento);

			if (($status = $this->model->verificarFolioPago($request->query['folio_pago'], $evento->evt_clave, $id_registro)) == true)
			{
				$this->json = array(
					'status' => true,
					'mensaje' => 'Error: otro registro ya tiene asignado este folio de pago, por favor asigna otro.'
				);
			}
		}

		echo json_encode($this->json);
	}

	/**
	 * Metodo que imprime la constancia
	 */
	public function impresionCarta()
	{
		if (!isset($_GET['id']) || empty($_GET['id']))
		{
			die ("Error de registro");
		}
		$idRegistro = $_GET['id'];

		// Obtenemos datos del evento
		$evento = $this->model->getEvento($this->idEvento);

		// Obtenemos los datos del registro
		$registro = $this->model->getRegistro($idRegistro, $evento->evt_clave);

		$this->evt->cargarCartaCompromiso($this->idEvento, $evento, $registro, $this->func);
	}
}
?>