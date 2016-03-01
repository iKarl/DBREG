<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/models/eventos/evento/
 * @version $Id: formasPago.php 1.0 2012-06-08 21:56 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Model\Model_Controller as Model;

class FormasPago_Model extends Model
{

	public function validarClave($clave, $idEvento)
	{
		$clave = $this->db->hEscapeString($clave);

		$qry = "SELECT efp_idEvento 
			FROM smc_eventoFormasPago 
			WHERE efp_clave = '%s' 
			AND efp_idEvento = %d
		";

		$qry = sprintf($qry, $clave, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				$this->db->hFree();
				return true;
			}
		}

		return false;
	}

	public function insertar($post, $idEvento, $idUsuario)
	{
		$clave = $this->db->hEscapeString($post['efp_clave']);
		$idEvento = $this->db->hEscapeString($idEvento);
		$idUsuario = $this->db->hEscapeString($idUsuario);

		$qry = "INSERT INTO smc_eventoFormasPago SET 
			efp_idEvento = %d,
			efp_clave = '%s',
			efp_fechaAlta = NOW(),
			efp_usuarioAlta = %d
		";

		$qry = sprintf($qry, $idEvento, $clave, $idUsuario);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}
		}

		return false;
	}

	public function eliminarNombresFormaPago($clave, $idEvento)
	{
		$clave = $this->db->hEscapeString($clave);
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "DELETE FROM smc_eventoFormasPagoNombres 
			WHERE fpn_clave = '%s' 
			AND fpn_idEvento = %d
		";

		$qry = sprintf($qry, $clave, $idEvento);

		if ($this->db->hQuery($qry))
		{
			return true;
		}

		return false;
	}

	public function eliminar($clave, $idEvento)
	{
		$clave = $this->db->hEscapeString($clave);
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "DELETE FROM smc_eventoFormasPago 
			WHERE efp_clave = '%s' 
			AND efp_idEvento = %d 
			LIMIT 1";

		$qry = sprintf($qry, $clave, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}
		}

		return false;
	}

	public function getIdiomasNombres($clave, $idEvento)
	{
		$idiomas = array();

		$clave = $this->db->hEscapeString($clave);
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "SELECT fpn_idioma 
			FROM smc_eventoFormasPagoNombres 
			WHERE fpn_idEvento = %d 
			AND fpn_clave = '%s'
		";

		$qry = sprintf($qry, $idEvento, $clave);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() > 0)
			{
				while ($idioma = $this->db->hFetchObject())
				{
					$idiomas[$idioma->fpn_idioma] = $idioma;
				}
				$this->db->hFree();
			}
		}

		return $idiomas;
	}

	public function eliminarNombre($clave, $id, $idEvento)
	{
		$clave = $this->db->hEscapeString($clave);
		$id = $this->db->hEscapeString($id);
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "DELETE FROM smc_eventoFormasPagoNombres 
			WHERE fpn_clave = '%s' 
			AND fpn_idNombre = %d 
			AND fpn_idEvento = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $clave, $id, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}
		}

		return false;
	}

	public function validarNombre($nombre, $idEvento)
	{
		$nombre = $this->db->hEscapeString($nombre);
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "SELECT fpn_idEvento 
			FROM smc_eventoFormasPagoNombres 
			WHERE fpn_nombre = '%s' 
			AND fpn_idEvento = %d
		";

		$qry = sprintf($qry, $nombre, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				$this->db->hFree();
				return true;
			}
		}

		return false;
	}

	public function insertarNombre($nombre, $idEvento)
	{
		$idNombre = $this->db->hEscapeString($nombre['fpn_idNombre']);
		$clave = $this->db->hEscapeString($nombre['fpn_clave']);
		$idioma = $this->db->hEscapeString($nombre['fpn_idioma']);
		$nombre = $this->db->hEscapeString($nombre['fpn_nombre']);
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "INSERT INTO smc_eventoFormasPagoNombres SET 
			fpn_idEvento = %d,
			fpn_idNombre = %d,
			fpn_clave = '%s',
			fpn_idioma = '%s',
			fpn_nombre = '%s'
		";

		$qry = sprintf($qry, $idEvento, $idNombre, $clave, $idioma, $nombre);

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