<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/controllers/catalogos/paises/
 * @version $Id: index.php 1.0 2012-02-27 22:41 _Karl_ $;
 * @author: Carlos A. Garcia Hernandez <carlos.agh@gmail.com, son_gohan_khan@hotmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Controller\Base_Controller as Controller;

class Index_Controller extends Controller
{
	public $seccion = "";
	public $limite = 15;
	private $user_login = array();
	private $json = array();

	/**
	 * 
	 * Carga el model para interactuar con la
	 * base de datos
	 */
	public function __construct()
	{
		$this->librarys("Sessions", "sess");

		$this->sess->validarSesionUsuario();
		$this->sess->validarPermisoSeccion(2);

		// Sección actual
		$this->seccion = parent::$section;

		// Datos de usuario en sesion
		$this->user_login = $this->sess->get('user');

		$this->model = $this->model();

		$this->viewTwig();
	}

	public function inicio()
	{
		$paises = $this->model->listaPaises(0, $this->limite);

		$this->display(
			array(
				"tituloSeccion" => "Catalogo de países",
				'seccion' => $this->seccion,
				"paises" => $paises,
				"paginas" => $this->paginador(),
				'images' => $this->pathImages
			)
		);
	}

	public function paises()
	{
		if (isset($_GET['pPagina']))
		{
			$start = $_GET['pPagina'];
		}
		else
		{
			$start = 0;
		}

		$paises = $this->model->listaPaises($start, $this->limite);

		$this->json = array(
			"status" => "funcion",
			"nomFuncion" => "cargarPaises",
			"seccion" => $this->seccion,
			"num" => $start,
			"images" => $this->pathImages . "/flags",
			"paises" => $paises
		);

		echo json_encode($this->json);
	}

	public function paginador()
	{
		$porPagina = $this->limite;
		$total = $this->model->totalPaises();

		if ($total != 0)
		{
			$paginas = ceil($total / $porPagina);
		}
		else
		{
			$paginas = 1;
		}

		$p = "";

		for($i = 0, $j = 1; $i < $paginas; $i++, $j++)
		{

			$start = $i * $porPagina;

			$p .= '<option value="' . $start . '">' . $j . '</option>';

		}

		return $p;
	}

	public function formAgregar()
	{
		$this->display(
			array(
				'seccion' => $this->seccion
			)
		);
	}

	public function agregar()
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

			$this->fv->validarCampo("pais_nombreEs", "req", "", 0, 90);
			$this->fv->validarCampo("pais_nombreEn", "", "", 0, 90);
			$this->fv->validarCampo("pais_iso2", "req", "", 2, 2);
			$this->fv->validarCampo("pais_iso3", "req", "", 3, 3);

			if ($this->fv->validacion['status'])
			{
				if (!$this->model->existePais($_POST['pais_nombreEs'], $_POST['pais_iso2'], $_POST['pais_iso3'], $_POST['pais_nombreEn']))
				{
					if ($this->model->agregar($_POST))
					{
						$this->json = array(
							"status" => "cerrarModal"
						);
					}
					else
					{
						$this->json = array(
							"status" => false,
							"mensaje" => "No fue posible agregar el país"
						);
					}
				}
				else
				{
					$this->json = array(
						"status" => false,
						"mensaje" => 'Ya existe un país con la clave ISO 2: "' . $_POST['pais_iso2'] . '", o<br />con la clave ISO 3: "' . $_POST['pais_iso3'] . '",<br />o con el nombre en español: "' . $_POST['pais_nombreEs'] . '",<br />o con el nombre en ingles: "' . $_POST['pais_nombreEn'] . '"'
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

	public function formEditar()
	{
		if (!isset($_POST['id']) || empty($_POST['id']))
		{
			header ("HTTP/1.1 514 An Error");
			exit ();
		}

		$idPais = $_POST['id'];

		if ($pais = $this->model->traerPais($idPais))
		{
			$this->display(
				array(
					"seccion" => $this->seccion,
					"pais" => $pais
				)
			);
		}
		else
		{
			header ("HTTP/1.1 514 An Error");
			exit ();
		}
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

			$this->fv->validarCampo("pais_nombreEs", "req", "", 2, 90);
			$this->fv->validarCampo("pais_nombreEn", "", "", 2, 90);
			$this->fv->validarCampo("pais_iso2", "req", "", 2, 2);
			$this->fv->validarCampo("pais_iso3", "req", "", 3, 3);

			if ($this->fv->validacion['status'])
			{
				$idPais = $_POST['pais_idPais'];

				if (!$this->model->existePais($_POST['pais_nombreEs'], $_POST['pais_iso2'], $_POST['pais_iso3'], $_POST['pais_nombreEn'], $idPais))
				{
					if ($this->model->actualizar($_POST, $this->user_login['id']))
					{
						$this->json = array(
							"status" => "funcion",
							"nomFuncion" => "actualizarPais",
							"pais" => array(
								"idPais" => $idPais,
								"nombreEs" => $_POST['pais_nombreEs'],
								"nombreEn" => $_POST['pais_nombreEn'],
								"iso2" => $_POST['pais_iso2'],
								"iso3" => $_POST['pais_iso3'],
								"imagen" => $this->pathImages . "/flags/" . strtolower($_POST['pais_iso2']) . ".png"
							)
						);
					}
					else
					{
						$this->json = array(
							"status" => false,
							"mensaje" => "No fue posible actualizar el país"
						);
					}
				}
				else
				{
					$this->json = array(
						"status" => false,
						"mensaje" => 'Ya existe un país con la clave ISO 2: "' . $_POST['pais_iso2'] . '", o<br />con la clave ISO 3: "' . $_POST['pais_iso3'] . '",<br />o con el nombre en español: "' . $_POST['pais_nombreEs'] . '",<br />o con el nombre en ingles: "' . $_POST['pais_nombreEn'] . '"'
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