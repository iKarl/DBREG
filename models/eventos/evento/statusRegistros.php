<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/models/eventos/evento/
 * @version $Id: statusRegistros.php 1.0 2012-06-05 23:59 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Model\Model_Controller as Model;

class StatusRegistros_Model extends Model
{

	public function validarClave($clave, $idEvento)
	{
		$clave = $this->db->hEscapeString($clave);

		$qry = "SELECT esr_idEvento 
			FROM smc_eventoStatusRegistros 
			WHERE esr_clave = '%s' 
			AND esr_idEvento = %d
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

	public function validarNombre($nombre, $idEvento, $idStatusReg = 0)
	{
		$nombre = $this->db->hEscapeString($nombre);

		$and = "";

		if ($idStatusReg != 0)
		{
			$idStatusReg = $this->db->hEscapeString($idStatusReg);
			$and = "AND esr_idStatus <> " . $idStatusReg;
		}

		$qry = "SELECT esr_idEvento 
			FROM smc_eventoStatusRegistros 
			WHERE esr_nombre = '%s' 
			AND esr_idEvento = %d 
			%s
		";

		$qry = sprintf($qry, $nombre, $idEvento, $and);

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
		$idStatusReg = $this->db->hEscapeString($post['esr_idStatus']);
		$clave = $this->db->hEscapeString($post['esr_clave']);
		$nombre = $this->db->hEscapeString($post['esr_nombre']);
		$idEvento = $this->db->hEscapeString($idEvento);
		$idUsuario = $this->db->hEscapeString($idUsuario);

		$qry = "INSERT INTO smc_eventoStatusRegistros SET 
			esr_idEvento = %d,
			esr_idStatus = %d,
			esr_clave = '%s',
			esr_nombre = '%s',
			esr_fechaAlta = NOW(),
			esr_usuarioAlta = %d
		";

		$qry = sprintf($qry, $idEvento, $idStatusReg, $clave, $nombre, $idUsuario);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}
		}

		return false;
	}

	public function actualizar($nombre, $idStatusReg, $idEvento, $idUsuario)
	{
		$idStatusReg = $this->db->hEscapeString($idStatusReg);
		$nombre = $this->db->hEscapeString($nombre);
		$idEvento = $this->db->hEscapeString($idEvento);
		$idUsuario = $this->db->hEscapeString($idUsuario);

		$qry = "UPDATE smc_eventoStatusRegistros SET 
			esr_nombre = '%s',
			esr_fechaModificacion = NOW(),
			esr_usuarioModifico = %d 
			WHERE esr_idStatus = %d 
			AND esr_idEvento = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $nombre, $idUsuario, $idStatusReg, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}
		}

		return false;
	}

	public function eliminar($id, $idEvento)
	{
		$id = $this->db->hEscapeString($id);
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "DELETE FROM smc_eventoStatusRegistros 
			WHERE esr_idStatus = '%s' 
			AND esr_idEvento = %d 
			LIMIT 1";

		$qry = sprintf($qry, $id, $idEvento);

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