<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/libs/
 * @version $Id: class.Sessions.php 1.0 2011-09-13 21:30 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com, son_gohan_khan@hotmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

session_name("smcTecnoRegistro");
session_start();

use SimpleMVC\Model\Model_Controller AS Model;

class Sessions extends Model
{

	public $sessID = "";

	/**
	 * 
	 * Enter description here ...
	 */
	public function __construct()
	{
		parent::__construct();

		$this->sessID = session_id();
	}

	/**
	 * 
	 * Si ya existe una session direccionamos al inicio
	 * Para prevenir el reenvio del formulario de acceso
	 */
	public function isSession()
	{
		$user = $this->get("user");

		if (isset($user['logged_in']) && $user['logged_in'] == true)
		{
			header ("Location: " . URL_PATH . "/home/");

			exit ();
		}
	}

	/**
	 * 
	 * Si no existe una session direccionamos al formulario
	 */
	public function isNotSession()
	{
		header ("Location: " . URL_PATH . "/?login=required");

		exit ();
	}

	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $onF5
	 */
	public function validarSesionUsuario($onF5 = true)
	{
		$validate = $this->validarTiempoSesion();

		if (!$validate['status'])
		{
			if ($validate['login'] == "time" && !$onF5)
			{
				header ("Location: " . URL_PATH . "/login/?action=logout&sess=inactivity");
			}
			else if ($validate['login'] == false)
			{
				$this->isNotSession();
			}
			else
			{
				header ("HTTP/1.0 512 Not Session");
			}

			exit ();
		}
	}

	public function validarPermisoSeccion($idSeccion)
	{
		$user = $this->get("user");

		$qry = sprintf(
			"SELECT sau_idUsuario " .
			"FROM smc_seccionesAccesoUsuarios " .
			"WHERE sau_idUsuario = %d " .
			"AND sau_idSeccion = %d",
			$user['id'],
			$idSeccion
		);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() <= 0)
			{
				header ("HTTP/1.1 513 Not Permission");

				echo "You're not permission";

				exit ();
			}
		}
	}

	public function validarNivelUsuario($idSeccion, $niveles = "1")
	{
		$user = $this->get("user");

		$qry = "SELECT sau_idUsuario 
			FROM smc_seccionesAccesoUsuarios 
			WHERE (sau_idUsuario = %d 
			AND sau_idSeccion = %d 
			AND sau_idNivel IN (%s))";

		$qry = sprintf($qry, $user['id'], $idSeccion, $niveles);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() <= 0)
			{
				header ("HTTP/1.1 513 Not Permission");

				echo "You're not permission";

				exit ();
			}
		}
	}

	/**
	 * 
	 * Metodo que verifica y actualiza el tiempo se sesión
	 * 
	 */
	public function validarTiempoSesionOldNew()
	{
		if ($user = $this->get("user"))
		{
			$now = date("Y-n-j H:i:s");

			$currentTime = ( strtotime($now) - strtotime($user['tiempoSesion']) );

			// termina en n mins. de inactividad // 30 min
			// 30 * 6 * 10
			if ($currentTime >= (180 * 10))
			{
				$user['logged_in'] = false;

				return array("status" => false, "login" => "time");
			}
			else
			{
				$user['tiempoSesion'] = $now;

				$this->set("user", $user);

				return array("status" => true);
			}
		}
		else
		{
			return array("status" => false, "login" => false, "sesion" => false);
		}
	}

	/**
	 * 
	 * Metodo que verifica y actualiza el tiempo se sesión
	 * 
	 */
	public function validarTiempoSesion()
	{
		$user = $this->get("user");

		if (isset($user['logged_in']) && $user['logged_in'] == true)
		{
			$now = date("Y-n-j H:i:s");

			$currentTime = ( strtotime($now) - strtotime($user['tiempoSesion']) );

			// termina en n mins. de inactividad // 30 min
			// 30 * 6 * 10
			if ($currentTime >= (180 * 10))
			{
				$user['logged_in'] = false;

				//$this->cerrarSesion($user);

				//$this->set("user");

				return array("status" => false, "login" => "time");
			}
			else
			{
				$user['tiempoSesion'] = $now;

				$this->set("user", $user);

				return array("status" => true);
			}
		}
		else
		{
			return array("status" => false, "login" => false);
		}
	}

	private function cerrarSesion($user)
	{
		$qry = "UPDATE smc_usuarios SET 
			usr_tiempoSesion = '0000-00-00 00:00:00', 
			usr_enSesion = 0 
			WHERE usr_idUsuario = %d 
			LIMIT 1";

		$qry = sprintf($qry, $user['id']);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}
		}

		return false;
	}

	public function getNombreUsuario($id)
	{
		$user = array();
		$id = $this->db->hEscapeString($id);

		$qry = "SELECT usr_nombre, usr_app, usr_apm 
			FROM smc_usuarios 
			WHERE usr_idUsuario = %d
		";

		$qry = sprintf($qry, $id);

		if ($thid->db->hQuery($qry))
		{
			if ($this->db->hNumRows() == 1)
			{
				$user = $this->db->hFetchObject();
				$this->db->hFree();
			}
		}

		return $user;
	}

	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $name
	 * @param unknown_type $value
	 */
	public function set($name, $value = '')
	{
		if (is_array($name))
		{
			foreach ($name as $key => $value)
			{
				if (is_array($value))
				{
					$this->set($key, $value);
				}
				else
				{
					$_SESSION[$key] = $value;
				}
			}
		}
		else
		{
			if ($value != "")
			{
				$_SESSION[$name] = $value;
			}
			else
			{
				if (isset($_SESSION[$name]))
				{
					unset($_SESSION[$name]);
				}
			}
		}
	}

	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $name
	 */
	public function get($name = '')
	{
		if ($name != "")
		{
			if (isset($_SESSION[$name]))
			{
				return $_SESSION[$name];
			}
		}
		else
		{
			if (isset($_SESSION) && !empty($_SESSION))
			{
				return $_SESSION;
			}
		}

		return false;
	}

}
?>