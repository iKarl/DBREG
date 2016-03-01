<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/models/login/
 * @version $Id: index.php 1.0 2012-01-31 23:11 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Model\Model_Controller as Model;

class Index_Model extends Model
{

	private $user = false;
	private $pass = false;

	/*
	 * Valida el usuario
	 */
	public function validarUsuario($username)
	{

		$username = $this->db->hEscapeString($username);

		$qry = "SELECT usr_usuario 
			FROM smc_usuarios 
			WHERE usr_usuario = '%s' 
			LIMIT 1";

		$qry = sprintf($qry, $username);

		if ($this->db->hQuery($qry))
		{

			if ($this->db->hNumRows() == 1)
			{

				$this->user = $this->db->hFetchObject();
				$this->db->hFree();

			}

		}

		return $this->user;

	}

	/*
	 * Valida la contraseña
	 */
	public function validarPassword($password)
	{

		$password = $this->db->hEscapeString($password);

		$qry = "SELECT usr_idUsuario, usr_status 
			FROM smc_usuarios 
			WHERE usr_usuario = '%s' 
			AND usr_password = '%s' 
			LIMIT 1";

		$qry = sprintf($qry, $this->user->usr_usuario, md5($password));

		if ($result = $this->db->hQuery($qry))
		{

			if ($this->db->hNumRows() == 1)
			{

				$this->user = $this->db->hFetchObject();
				$this->db->hFree();

				$this->pass = true;

			}

		}

		return $this->pass;

	}

	/*
	 * Valida si ya a iniciado sesion
	 */
	public function validarSesion()
	{

		$qry = "
			SELECT usr_enSesion, usr_ultimaSesion 
			FROM smc_usuarios 
			WHERE usr_idUsuario = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $this->user->usr_idUsuario);

		if ($this->db->hQuery($qry))
		{

			if ($sesion = $this->db->hFetchObject())
			{

				return $sesion;

			}

		}

		return false;

	}

	/*
	 * Actualiza la fecha de ultimo acceso
	 */
	public function actualizarAcceso($tiempoSesion)
	{

		$qry = "UPDATE smc_usuarios SET 
			usr_tiempoSesion = '%s', 
			usr_enSesion = 1, 
			usr_ultimaSesion = NOW() 
			WHERE usr_idUsuario = %d 
			LIMIT 1";

		$qry = sprintf($qry, $tiempoSesion, $this->user->usr_idUsuario);

		if ($this->db->hQuery($qry))
		{

			if ($this->db->hAffectedRows() <= 0)
			{

				$this->user = false;

			}

		}

		return $this->user;

	}

	public function cerrarSesion($user)
	{

		$qry = "UPDATE smc_usuarios SET 
			usr_tiempoSesion = '0000-00-00 00:00:00', 
			usr_enSesion = 0 
			WHERE usr_idUsuario = %d 
			LIMIT 1";

		$qry = sprintf($qry, $user['id']);

		if ($this->db->hQuery($qry))
		{

			//if ($this->db->hAffectedRows() == 1)
			//{

				return true;

			//}

		}

		return false;

	}


	public function inserton($id, $horas)
	{

		$qry = "UPDATE smc_reg_CMCG13 SET 
			horas = '%s' 
			WHERE id_registro = %d
		";

		$qry = sprintf($qry, $horas, $id);

		$this->db->hQuery($qry);

		return true;
	}
}
?>