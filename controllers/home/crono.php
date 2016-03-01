<?php
/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: TecnoSite/controllers/index/
 * @version $Id: index.php 1.0 2013-04-03 10:32 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Controller\Base_Controller as Controller;

class Crono_Controller extends Controller
{
	private $json = array();

	public function __construct()
	{
		$this->model = $this->model();

		$this->librarys(
			array(
				'Functions' => 'func'
			)
		);
	}

	public function inicio($request)
	{
		$this->viewTwig();

		$this->display(
			array(
				'views' => $this->pathViews,
				'layout' => $this->layoutView,
				'seccion' => $this->getSection()
			)
		);
	}

	public function encuesta()
	{
		$this->viewTwig();

		$this->display(
			array(
				'views' => $this->pathViews,
				'layout' => $this->layoutView,
				'seccion' => $this->getSection()
			)
		);
	}

	public function mostrarHoras($request)
	{
		$this->json = array(
			'status' => false,
			'mensaje' => 'Hubo un error al procesar la encuesta, por favor intentelo de nuevo.'
		);

		$rfids = array();
		$tablas = array(
			'smc_reg_CMCG13_RFID',
			'smc_reg_CMCG13_RFID_2',
			'smc_reg_CMCG13_RFID_3',
			//'smc_reg_CMCG13_RFID_ESCALERA',
			//'smc_reg_CMCG13_RFID_4',
			//'smc_reg_CMCG13_RFID_5',
			//'smc_reg_CMCG13_RFID_6',
			//'smc_reg_CMCG13_RFID_7'
		);

		$registro = array(
			'tag' => $this->func->generarTagRFID($request->query['id_registro']),
			'id' => $request->query['id_registro']
		);

		foreach ($tablas as $key => $tabla)
		{
			$rfids[] = $this->model->getRFID($registro, $tabla);
		}

		$diffs = array();
		$horas = array('h' => 0, 'i' => 0, 's' => 0);
		$total_horas = $total_mins = '00';

		foreach ($rfids as $key => $rfid) 
		{
			if (!empty($rfid))
			{
				foreach ($rfid as $key => $row)
				{
					if ($row->fechaHora_acceso AND $row->fechaHora_salida)
					{
						$datetime1 = new \DateTime($row->fechaHora_acceso);
						$datetime2 = new \DateTime($row->fechaHora_salida);
						$diffs[] = $datetime1->diff($datetime2);
					}
				}
			}
		}

		//print_r($diffs);

		if (!empty($diffs))
		{
			foreach($diffs as $diff)
			{
				$horas['h'] += $diff->h;
				$horas['i'] += $diff->i;
				$horas['s'] += $diff->s;
			}

			$total_horas = ($horas['h'] + (int)($horas['i'] / 60));
			$total_mins = ((int)($horas['i'] % 60) + (int)($horas['s'] / 60));
			$total_segs = (int)($horas['i'] % 60);
		}
		//echo $total_horas . ':' . $total_mins . ':' . $total_segs, '<br />';

		if ($total_horas >= 0 AND $total_horas <= 9)
		{
			$total_horas = '0' . $total_horas;
		}

		if ($total_mins >= 0 AND $total_mins <= 9)
		{
			$total_mins = '0' . $total_mins;
		}

		$this->json = array(
			'status' => true,
			'nombre' => $this->model->getNombre($request->query['id_registro']),
			'horas' => $total_horas . ':' . $total_mins
		);

		echo json_encode($this->json);
	}

	public function listadoHoras($request)
	{
		$this->viewTwig();

		$this->json = array(
			'status' => false,
			'mensaje' => 'Hubo un error al procesar la encuesta, por favor intentelo de nuevo.'
		);

		$tablas = array(
			'smc_reg_CMCG13_RFID',
			'smc_reg_CMCG13_RFID_2',
			'smc_reg_CMCG13_RFID_3',
			'smc_reg_CMCG13_RFID_ESCALERA',
			//'smc_reg_CMCG13_RFID_4',
			//'smc_reg_CMCG13_RFID_5',
			//'smc_reg_CMCG13_RFID_6',
			//'smc_reg_CMCG13_RFID_7'
		);

		$registros = $this->model->getRegistros();
		foreach ($registros as $key => $registro)
		{
			$rfids = array();
			$registro->id_tag = $this->func->generarTagRFID($registro->id_registro);

			foreach ($tablas as $i => $tabla)
			{
				$rfids[] = $this->model->getRFIDS($registro, $tabla);
			}

			$diffs = array();
			$horas = array('h' => 0, 'i' => 0, 's' => 0);
			$total_horas = $total_mins = '0';

			foreach ($rfids as $j => $rfid)
			{
				if (!empty($rfid))
				{
					foreach ($rfid as $k => $row)
					{
						if ($row->fechaHora_acceso AND $row->fechaHora_salida)
						{
							$datetime1 = new \DateTime($row->fechaHora_acceso);
							$datetime2 = new \DateTime($row->fechaHora_salida);
							$diffs[] = $datetime1->diff($datetime2);
						}
					}
				}
			}

			if (!empty($diffs))
			{
				foreach($diffs as $diff)
				{
					$horas['h'] += $diff->h;
					$horas['i'] += $diff->i;
					$horas['s'] += $diff->s;
				}

				$total_horas = ($horas['h'] + (int)($horas['i'] / 60));
				$total_mins = ((int)($horas['i'] % 60) + (int)($horas['s'] / 60));
				$total_segs = (int)($horas['i'] % 60);
			}

			if ($total_horas <= 9)
			{
				$total_horas = '0' . $total_horas;
			}

			if ($total_mins <= 9)
			{
				$total_mins = '0' . $total_mins;
			}

			$registros[$key]->rfids = $rfids;
			$registros[$key]->horas = $total_horas . ':' . $total_mins;
		}

		$this->display(
			array(
				'views' => $this->pathViews,
				'layout' => $this->layoutView,
				'seccion' => $this->getSection(),
				'registros' => $registros
			)
		);
	}
}
?>