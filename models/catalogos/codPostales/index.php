<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/models/catalogos/codPostales/
 * @version $Id: index.php 1.0 2012-03-19 19:09 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com, son_gohan_khan@hotmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Model\Model_Controller as Model;

class Index_Model extends Model
{

	public function buscar($cp = 0)
	{

		$cps = array();
		$cp = $this->db->hEscapeString($cp);

		$qry = "SELECT cp_idCodigoPostal, cp_codigoPostal, cp_asenta, cp_tipoAsenta, cp_nombreMnpio, cp_nombreEstado, cp_nombreCiudad 
			FROM smc_catCodigosPostales 
			WHERE cp_codigoPostal = '%s' 
			ORDER BY cp_asenta ASC
		";

		$qry = sprintf($qry, $cp);

		if ($this->db->hQuery($qry))
		{

			if ($this->db->hNumRows() > 0)
			{

				while ($cp = $this->db->hFetchAssoc())
				{

					$cps[] = $cp;

				}

			}

		}

		return $cps;

	}

	public function obtenerCP($id = 0)
	{

		$cp = array();
		$id = $this->db->hEscapeString($id);

		$qry = "SELECT cp_idCodigoPostal, cp_codigoPostal, cp_asenta, cp_tipoAsenta, cp_nombreMnpio, cp_nombreEstado, cp_nombreCiudad 
			FROM smc_catCodigosPostales 
			WHERE cp_idCodigoPostal = %d";

		$qry = sprintf($qry, $id);

		if ($this->db->hQuery($qry))
		{

			if ($this->db->hNumRows() == 1)
			{

				$cp = $this->db->hFetchObject();

			}

		}

		return $cp;

	}

	public function existe($cp, $nombreAsenta, $idCp = 0)
	{

		$existe = 0;		
		$cp = $this->db->dbEscapeString($cp);
		$nombreAsenta = $this->db->dbEscapeString($nombreAsenta);
		$andDist = "";

		if ($idCp)
		{

			$idCp = $this->db->dbEscapeString($idCp);

			$andDist = " AND cp_idCodigoPostal <> " . $idCp;

		}

		$qry = sprintf(
			"SELECT cp_idCodigoPostal " .
			"FROM smc_catCodigosPostales " .
			"WHERE (cp_codigoPostal = '%s' " .
			"AND cp_asenta = '%s')" .
			$andDist,
			$cp,
			$nombreAsenta
		);

		if ($this->db->dbQuery($qry))
		{

			if ($this->db->dbNumRows() >= 1)
			{

				$existe = 1;

			}

		}
		else
		{

			$existe = false;

		}

		return $existe;

	}

	public function actualizar($cp)
	{

		$id = $this->db->hEscapeString($cp['cp_idCodigoPostal']);
		$codigoPostal = $this->db->hEscapeString($cp['cp_codigoPostal']);
		$asenta = $this->db->hEscapeString($cp['cp_asenta']);
		$tipoAsenta = $this->db->hEscapeString($cp['cp_tipoAsenta']);
		$mnpio = $this->db->hEscapeString($cp['cp_nombreMnpio']);
		$estado = $this->db->hEscapeString($cp['cp_nombreEstado']);
		$ciudad = $this->db->hEscapeString($cp['cp_nombreCiudad']);

		$qry = "UPDATE smc_catCodigosPostales SET 
			cp_codigoPostal = '%s', 
			cp_asenta = '%s', 
			cp_tipoAsenta = '%s', 
			cp_nombreMnpio = '%s', 
			cp_nombreEstado = '%s', 
			cp_nombreCiudad = '%s' 
			WHERE cp_idCodigoPostal = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $codigoPostal, $asenta, $tipoAsenta, $mnpio, $estado, $ciudad, $id);

		if ($this->db->hQuery($qry))
		{
			return true;
		}

		return false;

	}

}
?>