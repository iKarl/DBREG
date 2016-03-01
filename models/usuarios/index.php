<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/models/eventos/
 * @version $Id: index.php 1.0 2012-03-23 00:08 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Model\Model_Controller as Model;

class Index_Model extends Model
{
	public function getUsuarios()
	{
		$usuarios = array();

		$qry = "SELECT * 
			FROM smc_usuarios 
			WHERE 1 
		";

		if ($this->db->hQuery($qry))
		{

			if ($this->db->hNumRows() > 0)
			{

				while ($usuario = $this->db->hFetchObject())
				{
					$usuarios[] = $usuario;
				}

			}

		}

		return $usuarios;
	}

	/**
	 * Metodo que agregar un acompañante
	 */
	public function setUsuario(array $usuario, $id_usuario)
	{
		$qry = "INSERT INTO smc_usuarios SET 
			usr_nombre = '%s',
			usr_app = '%s',
			usr_apm = '%s',
			usr_genero = '%s',
			usr_usuario = '%s',
			usr_password = '%s',
			usr_fechaAlta = '%s',
			usr_usuarioAlta = %d,
			usr_status = %d
		";

		$qry = sprintf($qry,
			$usuario['usr_nombre'],
			$usuario['usr_app'],
			$usuario['usr_apm'],
			$usuario['usr_genero'],
			$usuario['usr_usuario'],
			md5($usuario['usr_password']),
			$usuario['usr_fechaAlta'],
			$id_usuario,
			$usuario['usr_status']
		);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return $this->db->hInsertID();
			}
		}

		return false;
	}

	public function getUsuario($id_usuario)
	{
		$usuario = array();

		$qry = "SELECT * 
			FROM smc_usuarios 
			WHERE usr_idUsuario = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $id_usuario);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() == 1)
			{
				$usuario = $this->db->hFetchObject();
				$this->db->hFree();
			}
		}

		return $usuario;
	}

	/**
	 * Metodo que agregar un acompañante
	 */
	public function updateUsuario(array $usuario, $id_usuario)
	{
		$campo_password = '';
		if ($usuario['usr_password'] !== '')
		{
			$campo_password = "usr_password = '" . md5($usuario['usr_password']) . "',";
		}

		$qry = "UPDATE smc_usuarios SET 
			usr_nombre = '%s',
			usr_app = '%s',
			usr_apm = '%s',
			usr_genero = '%s',
			usr_usuario = '%s',
			%s
			usr_fechaModificacion = '%s',
			usr_usuarioModifico = %d,
			usr_status = %d 
			WHERE usr_idUsuario = %d 
			LIMIT 1
		";

		$qry = sprintf($qry,
			$usuario['usr_nombre'],
			$usuario['usr_app'],
			$usuario['usr_apm'],
			$usuario['usr_genero'],
			$usuario['usr_usuario'],
			$campo_password,
			$usuario['usr_fechaModificacion'],
			$id_usuario,
			$usuario['usr_status'],
			$usuario['usr_idUsuario']
		);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}
		}

		return false;
	}

	public function getSecciones()
	{
		$secciones = array();

		$qry = "SELECT sec_idSeccion, sec_nombre, sec_descripcion 
			FROM smc_secciones 
			WHERE 1
		";

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($seccion = $this->db->hFetchObject())
				{
					$secciones[] = $seccion;
				}
				$this->db->hFree();
			}
		}

		return $secciones;
	}

	public function getSeccionesUsuario($id_usuario)
	{
		$secciones = array();

		$qry = "SELECT sau_idSeccion 
			FROM smc_seccionesAccesoUsuarios 
			WHERE sau_idUsuario = %d
		";

		$qry = sprintf($qry, $id_usuario);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($seccion = $this->db->hFetchObject())
				{
					$secciones[] = $seccion->sau_idSeccion;
				}
				$this->db->hFree();
			}
		}

		return $secciones;
	}

	/**
	 * Metodo que agregar un acompañante
	 */
	public function ereaseSeccionesUsuario($id_usuario)
	{
		$qry = "DELETE FROM smc_seccionesAccesoUsuarios 
			WHERE sau_idUsuario = %d 
		";

		$qry = sprintf($qry, $id_usuario);

		if ($this->db->hQuery($qry))
		{
			return true;
		}

		return false;
	}
	/**
	 * Metodo que agregar un acompañante
	 */
	public function setPermiso($id_usuario, $seccion)
	{
		$qry = "INSERT INTO smc_seccionesAccesoUsuarios SET 
			sau_idUsuario = %d,
			sau_idSeccion = %d,
			sau_idNivel = 1
		";

		$qry = sprintf($qry,
			$id_usuario,
			$seccion
		);

		if ($this->db->hQuery($qry))
		{
			return true;
		}

		return false;
	}
}
?>