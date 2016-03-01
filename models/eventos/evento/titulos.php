<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/models/eventos/evento/
 * @version $Id: titulos.php 1.0 2012-05-24 00:35 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Model\Model_Controller as Model;

class Titulos_Model extends Model
{

	public function validarClave($clave, $idEvento)
	{
		$clave = $this->db->hEscapeString($clave);

		$qry = "SELECT ect_idEvento 
			FROM smc_eventoTitulos 
			WHERE ect_clave = '%s' 
			AND ect_idEvento = %d
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

	public function validarNombre($nombre, $idEvento)
	{
		$nombre = $this->db->hEscapeString($nombre);

		$qry = "SELECT ect_idEvento 
			FROM smc_eventoTitulos 
			WHERE ect_nombre = '%s' 
			AND ect_idEvento = %d
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

	public function insertar($post, $idEvento, $idUsuario)
	{
		$idTitulo = $this->db->hEscapeString($post['ect_idTitulo']);
		$clave = $this->db->hEscapeString($post['ect_clave']);
		$idioma = $this->db->hEscapeString($post['ect_idioma']);
		$nombre = $this->db->hEscapeString($post['ect_nombre']);
		$idEvento = $this->db->hEscapeString($idEvento);
		$idUsuario = $this->db->hEscapeString($idUsuario);

		$qry = "INSERT INTO smc_eventoTitulos SET 
			ect_idEvento = %d,
			ect_idTitulo = %d,
			ect_clave = '%s',
			ect_idioma = '%s',
			ect_nombre = '%s',
			ect_fechaAlta = NOW(),
			ect_usuarioAlta = %d
		";

		$qry = sprintf($qry, $idEvento, $idTitulo, $clave, $idioma, $nombre, $idUsuario);

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

		$qry = "DELETE FROM smc_eventoTitulos 
			WHERE ect_idTitulo = '%s' 
			AND ect_idEvento = %d 
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