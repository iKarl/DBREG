<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/models/header/
 * @version $Id: index.php 1.0 2012-02-03 00:36 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Model\Model_Controller as Model;

class Header_Model extends Model
{

	public function datosUsuario()
	{

		$this->librarys("Sessions", "sess");

		$user = array(); 
		$user_loggin = $this->sess->get("user");

		$qry = "SELECT usr_idUsuario, usr_nombre, usr_genero, usr_app, usr_apm, usr_usuario, usr_ultimaSesion 
			FROM smc_usuarios 
			WHERE usr_idUsuario = %d 
			AND usr_status = 1 
			LIMIT 1";

		$qry = sprintf($qry, $user_loggin['id']);

		if ($this->db->hQuery($qry))
		{

			if ($this->db->hNumRows() == 1)
			{

				$user = $this->db->hFetchObject();

			}

		}

		return $user;

	}

}
?>