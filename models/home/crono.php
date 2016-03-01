<?php
/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: evts/models/CARDIO/
 * @author: Carlos A. García Hernández <cgarcia@turycon.com.mx, carlos.agh@gmail.com>
 * @version $Id: reportes.php 1.0 2012-06-06 13:58 _Karl_ $;
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Model\Model_Controller as Model;

class Crono_Model extends Model
{
	public function getRegistros()
	{
		$registros = array();

		$qry = "SELECT id_registro, id_tag, nombre, app, apm, status 
			FROM smc_reg_CMCG13 
			WHERE status = 'COR' 
			OR status = 'PAG' 
			OR status = 'CCC'
		";

		if ($this->db->hQuery($qry))
		{
			while ($registro = $this->db->hFetchObject())
			{
				$registros[] = $registro;
			}
		}

		return $registros;
	}

	public function getNombre($id_registro)
	{
		$registro = array();

		$qry = "SELECT nombre, app, apm 
			FROM smc_reg_CMCG13 
			WHERE `id_registro` = %d 
			LIMIT 1
		";

		$qry = sprintf($qry,
			$id_registro
		);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() == 1)
			{
				$registro = $this->db->hFetchObject();
			}
		}

		return $registro;
	}

	public function getRFID($registro, $tabla)
	{
		$rfids = array();

		$qry = "SELECT * 
			FROM %s 
			WHERE id_tag = '%s'
			OR id_registro = %d 
		";

		$qry = sprintf($qry, $tabla, $registro['tag'], $registro['id']);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($rfid = $this->db->hFetchObject())
				{
					$rfids[] = $rfid;
				}
				$this->db->hFree();
			}
		}

		return $rfids;
	}

	public function getRFIDS($registro, $tabla)
	{
		$rfids = array();

		$qry = "SELECT * 
			FROM %s 
			WHERE id_tag = '%s' 
			OR id_registro = %d
		";

		$qry = sprintf($qry, $tabla, $registro->id_tag, $registro->id_registro);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($rfid = $this->db->hFetchObject())
				{
					$rfids[] = $rfid;
				}
				$this->db->hFree();
			}
		}

		return $rfids;
	}

	public function setUpdateRegistro($costo_total, $id_registro)
	{
		$qry = "UPDATE smc_reg_CMCG13 SET 
			costo_total = '%s' 
			WHERE id_registro = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $costo_total, $id_registro);

		if ($this->db->hQuery($qry))
		{
			return true;
		}

		return false;
	}
}
?>