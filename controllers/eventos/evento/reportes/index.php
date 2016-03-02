<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/controllers/eventos/evento/reportes/
 * @version $Id: index.php 1.0 2012-08-30 21:28 _Karl_ $;
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
	 * Metodo constructor
	 */
	public function __construct()
	{
		$this->librarys(
			array(
				"Sessions" => "sess",
				"Functions" => "fc",
				"Eventos" => "evt",
				'SMC\Lib\Reportes' => 'rpt'
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

		$this->model = $this->model("reportes");
		$this->model->evt = $this->model("index", "eventos/evento/index");

		$this->viewTwig();
	}

	public function inicio()
	{
		$evento = $this->model->evt->getEvento($this->idEvento);

		$series = $this->model->concentrado($evento->evt_clave, $evento->evt_idioma);
		$acompanantes = $this->model->getAcompanantes($evento->evt_clave, $evento->evt_idioma);

		$series = array_merge($series, $acompanantes);

		$total = 0;
		foreach ($series as $serie => $value)
		{
			$total += $value['value'];
		}

		$this->display(
			array(
				'seccion' => $this->seccion,
				'series' => json_encode($series),
				'layout' => $this->layoutView,
				'total' => $total
			)
		);
	}

	public function asistencia()
	{
		$evento = $this->model->evt->getEvento($this->idEvento);

		$asistencia = $this->model->getAsistencias($evento->evt_clave, $evento->evt_idioma);
		$acompanantes = $this->model->getAcompanantesAsitencia($evento->evt_clave, $evento->evt_idioma);

		$asistencia = array_merge($asistencia, $acompanantes);

		$total = 0;
		foreach ($asistencia as $serie => $value)
		{
			$total += $value['value'];
		}

		$this->display(
			array(
				'seccion' => $this->seccion,
				'asistencias' => json_encode($asistencia),
				'layout' => $this->layoutView,
				'total' => $total
			)
		);
	}

	public function asistenciaPais()
	{
		$evento = $this->model->evt->getEvento($this->idEvento);

		$asistencia = $this->model->getAsistenciasPais($evento->evt_clave);
		//$acompanantes = $this->model->getAcompanantesAsitencia($evento->evt_clave, $evento->evt_idioma);

		//$asistencia = array_merge($asistencia, $acompanantes);

		$total = 0;
		foreach ($asistencia as $serie => $value)
		{
			$total += $value['value'];
		}

		$this->display(
			array(
				'seccion' => $this->seccion,
				'asistencias' => json_encode($asistencia),
				'layout' => $this->layoutView,
				'total' => $total
			)
		);
	}

	public function asistenciaPaisLlegados()
	{
		$evento = $this->model->evt->getEvento($this->idEvento);

		$asistencia = $this->model->getAsistenciasPaisLlegados($evento->evt_clave);
		//$acompanantes = $this->model->getAcompanantesAsitencia($evento->evt_clave, $evento->evt_idioma);

		//$asistencia = array_merge($asistencia, $acompanantes);

		$total = 0;
		foreach ($asistencia as $serie => $value)
		{
			$total += $value['value'];
		}

		$this->display(
			array(
				'seccion' => $this->seccion,
				'asistencias' => json_encode($asistencia),
				'layout' => $this->layoutView,
				'total' => $total
			)
		);
	}

	public function adicionales()
	{
		$evento = $this->model->evt->getEvento($this->idEvento);

		$adicionales = $this->model->getAdicionales($evento->evt_clave, $evento->evt_idioma);

		$this->display(
			array(
				'seccion' => $this->seccion,
				'adicionales' => json_encode($adicionales),
				'layout' => $this->layoutView
			)
		);
	}

	public function statusPago()
	{
		$evento = $this->model->evt->getEvento($this->idEvento);

		$status = $this->model->getStatusPago($evento->evt_clave, $evento->evt_idioma);

		$this->display(
			array(
				'seccion' => $this->seccion,
				'status' => json_encode($status),
				'layout' => $this->layoutView
			)
		);
	}

	public function genReporte()
	{
		$evento = $this->model->evt->getEvento($this->idEvento);

		// Campos para el reporte
		$campos = $this->rpt->getFieldsTableRegistros($evento->evt_clave);
		// Status de registro
		$status = $this->evt->getStatusRegistro($this->idEvento);
		// Categorias
		$categorias = $this->evt->getNombresCategorias($this->idEvento, $evento->evt_idioma);
		// Formas de pago
		$formasPago = $this->evt->getNombresFormasPagosReg($this->idEvento, $evento->evt_idioma);

		$this->display(
			array(
				'seccion' => $this->seccion,
				'campos' => $campos,
				'categorias' => $categorias,
				'formasPago' => $formasPago,
				'statusReg' => $status
			)
		);
	}	

	public function repGeneral()
	{
		if (isset($_POST))
		{
			$evento = $this->model->evt->getEvento($this->idEvento);
			$this->rpt->repGeneral($_POST, $evento);
		}
		else
		{
			header ("HTTP/1.1 514 An Error");
			exit ();
		}
	}

	public function genAdicionales()
	{
		$evento = $this->model->evt->getEvento($this->idEvento);

		// Campos para el reporte
		$campos = $this->rpt->getFieldsTableItems($evento->evt_clave);
		// Status de registro
		$status = $this->evt->getStatusRegistro($this->idEvento);
		// Categorias
		$categorias = $this->evt->getNombresItems($this->idEvento, $evento->evt_idioma);

		$this->display(
			array(
				'seccion' => $this->seccion,
				'campos' => $campos,
				'categorias' => $categorias,
				'statusReg' => $status
			)
		);
	}

	public function repAdicionales()
	{
		if (isset($_POST))
		{
			$evento = $this->model->evt->getEvento($this->idEvento);
			$this->rpt->repAdicionales($_POST, $evento);
		}
		else
		{
			header ("HTTP/1.1 514 An Error");
			exit ();
		}
	}

	public function genReporteFotos()
	{
		$evento = $this->model->evt->getEvento($this->idEvento);

		// Campos para el reporte
		//$campos = $this->rpt->getFieldsTableRegistros($evento->evt_clave);
		// Status de registro
		$status = $this->evt->getStatusRegistro($this->idEvento);
		// Categorias
		$categorias = $this->evt->getNombresCategorias($this->idEvento, $evento->evt_idioma);
		// Formas de pago
		//$formasPago = $this->evt->getNombresFormasPagosReg($this->idEvento, $evento->evt_idioma);

		$this->display(
			array(
				'seccion' => $this->seccion,
				//'campos' => $campos,
				'categorias' => $categorias,
				//'formasPago' => $formasPago,
				'statusReg' => $status
			)
		);
	}

	public function validarFormRepFotosPDF($request)
	{
		if (!empty($request->query))
		{
			$this->fova->campos = $request->query;

			//$this->fova->validarCampo('cat_registro', '', 0, 0);
			$this->fova->validarCampo('inicio', '', '', 0, 0, 'INT');
			$this->fova->validarCampo('fin', '', '', 0, 0, 'INT');
			//$this->fova->validarCampo('status', '', 0, 0);

			if ($this->fova->validacion['status'])
			{
				if (is_numeric($this->fova->campos['inicio']) && $this->fova->campos['inicio'] < 1)
				{
					$this->json = array(
						'status' => false,
						'mensaje' => 'El número minimo de incio es 1'
					);
				}
				else
				{
					$this->json = array(
						'status' => 'funcion',
						'nomFuncion' => 'reporteFotosPDF',
						'seccion' => $this->seccion
					);
				}
			}
			else
			{
				$this->json = $this->fova->validacion;
			}
		}
		else
		{
			$this->json = array(
				'status' => false,
				'mensaje' => 'Error en los datos del formulario.'
			);
		}

		echo json_encode($this->json);
	}

	public function reporteFotosPDF($request)
	{
		if (!empty($request->get))
		{
			if (!empty($request->get['inicio']))
			{
				$inicio = $request->get['inicio'];
				$request->get['inicio'] = $inicio - 1;
			}

			$evento = $this->model->evt->getEvento($this->idEvento);
			$registros = $this->model->getRegistrosFotos($evento->evt_clave, $request->get);
			$this->rpt->reporteFotosPDF($registros, $evento, $this->func);
		}
		else
		{
			header ("HTTP/1.1 514 An Error");
			exit ();
		}
	}

	public function cajeros()
	{
		$cajeros = $this->model->getCajeros();

		$this->display(
			array(
				'seccion' => $this->seccion,
				'cajeros' => $cajeros
			)
		);
	}

	public function reporteCajero($request)
	{
		if (isset($request->query))
		{
			$evento = $this->model->evt->getEvento($this->idEvento);
			$this->rpt->reporteCajero($request->query, $evento, $this->func);
		}
		else
		{
			header ("HTTP/1.1 514 An Error");
			exit ();
		}
	}

	public function expReporteGeneral($request)
	{
		if (isset($request->query))
		{
			$evento = $this->model->evt->getEvento($this->idEvento);
			$this->rpt->expReporteGeneral($request->query, $evento, $this->func);
		}
		else
		{
			header ("HTTP/1.1 514 An Error");
			exit ();
		}
	}

	public function expReporteGeneralItems($request)
	{
		if (isset($request->query))
		{
			$evento = $this->model->evt->getEvento($this->idEvento);
			$this->rpt->expReporteGeneralItems($request->query, $evento, $this->func);
		}
		else
		{
			header ("HTTP/1.1 514 An Error");
			exit ();
		}
	}
	
	public function expReporteGeneralAcomp($request)
	{
		if (isset($request->query))
		{
			$evento = $this->model->evt->getEvento($this->idEvento);
			$this->rpt->expReporteGeneralAcomp($request->query, $evento, $this->func);
		}
		else
		{
			header ("HTTP/1.1 514 An Error");
			exit ();
		}
	}

	public function rfid($request)
	{
		if (isset($request->query))
		{
			$evento = $this->model->evt->getEvento($this->idEvento);
			$this->rpt->expReporteRFID($request->query, $evento, $this->func);
		}
		else
		{
			header ("HTTP/1.1 514 An Error");
			exit ();
		}
	}
}