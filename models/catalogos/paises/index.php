<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/models/catalogos/paises/
 * @version $Id: index.php 1.0 2012-02-27 23:33 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com, son_gohan_khan@hotmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Model\Model_Controller as Model;

class Index_Model extends Model
{

	public function listaPaises($inicio = 0, $limite = 15)
	{

		$paises = array();

		$qry = "SELECT pais_idPais, pais_iso2, pais_iso3, pais_nombreEs, pais_nombreEn, pais_imagen 
			FROM smc_catPaises 
			WHERE 1 
			ORDER BY pais_nombreEs ASC 
			LIMIT %d , %d
		";

		$qry = sprintf($qry, $inicio, $limite);

		if ($this->db->hQuery($qry))
		{

			if ($this->db->hNumRows() >= 1)
			{

				while ($pais = $this->db->hFetchObject())
				{

					$paises[] = $pais;

				}

				$this->db->hFree();

			}

		}

		return $paises;

	}

	public function totalPaises()
	{

		$total = 0;

		$qry = "SELECT COUNT(pais_idPais) AS total 
			FROM smc_catPaises
		";

		if ($this->db->hQuery($qry))
		{

			$total = $this->db->hFetchObject();
			$total = $total->total;

		}

		return $total;

	}

	public function traerPais($idPais)
	{

		$pais = array();

		$idPais = $this->db->hEscapeString($idPais);

		$qry = "SELECT pais_idPais, pais_iso2, pais_iso3, pais_nombreEs, pais_nombreEn 
			FROM smc_catPaises 
			WHERE pais_idPais = %d
		";

		$qry = sprintf($qry, $idPais);

		if ($this->db->hQuery($qry))
		{

			if ($this->db->hNumRows() == 1)
			{

				$pais = $this->db->hFetchObject();

				$this->db->hFree();

			}

		}

		return $pais;

	} 

	public function existePais($nombreEs, $iso2, $iso3, $nombreEn = "", $idPais = 0)
	{

		$and = ")";
		$nombreEs = $this->db->hEscapeString($nombreEs);
		$iso2 = $this->db->hEscapeString($iso2);
		$iso3 = $this->db->hEscapeString($iso3);

		if ($nombreEn != "")
		{
			$nombreEn = $this->db->hEscapeString($nombreEn);
			$and = " OR pais_nombreEn = '" . $nombreEn . "')";
		}

		if ($idPais)
		{
			$and .= " AND pais_idPais <> " . $idPais;
		}

		$qry = "SELECT pais_idPais 
			FROM smc_catPaises 
			WHERE (pais_nombreEs = '%s' 
			OR pais_iso2 = '%s' 
			OR pais_iso3 = '%s'
			%s 
		";

		$qry = sprintf($qry, $nombreEs, $iso2, $iso3, $and);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() == 1)
			{
				return true;
			}
		}

		return false;

	}

	public function agregar($pais)
	{

		$nombreEs = $this->db->hEscapeString($pais['pais_nombreEs']);
		$nombreEn = $this->db->hEscapeString($pais['pais_nombreEn']);
		$iso2 = $this->db->hEscapeString($pais['pais_iso2']);
		$iso3 = $this->db->hEscapeString($pais['pais_iso3']);

		$qry = "INSERT INTO smc_catPaises SET 
			pais_iso2 = '%s', 
			pais_iso3 = '%s', 
			pais_nombreEs = '%s', 
			pais_nombreEn = '%s'
		";

		$qry = sprintf($qry, $iso2, $iso3, $nombreEs, $nombreEn);

		if ($this->db->hQuery($qry))
		{

			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}

		}

		return false;

	}

	public function actualizar($pais, $idUsuario)
	{

		$imagen = "";
		$idPais = $this->db->hEscapeString($pais['pais_idPais']);
		$iso2 = $this->db->hEscapeString($pais['pais_iso2']);
		$iso3 = $this->db->hEscapeString($pais['pais_iso3']);
		$nombreEs = $this->db->hEscapeString($pais['pais_nombreEs']);
		$nombreEn = $this->db->hEscapeString($pais['pais_nombreEn']);

		$qry = "UPDATE smc_catPaises SET 
			pais_iso2 = '%s', 
			pais_iso3 = '%s', 
			pais_nombreEs = '%s', 
			pais_nombreEn = '%s', 
			pais_imagen = LOWER('%s'), 
			pais_fechaModificacion = NOW(), 
			pais_usuarioModifico = %d 
			WHERE pais_idPais = %d 
			LIMIT 1
		";

		$imagen = $iso2 . ".png";

		$qry = sprintf($qry, $iso2, $iso3, $nombreEs, $nombreEn, $imagen, $idUsuario, $idPais);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}
		}

		return false;

	}

}
?>