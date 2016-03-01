<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/controllers/header/
 * @version $Id: index.php 1.0 2012-02-03 00:27 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Directs"); }

use SimpleMVC\Controller\Base_Controller as Controller;

class Header_Controller extends Controller
{

	public function __construct()
	{
		$this->librarys(
			array(
				"Sessions" => "sess",
				"Functions" => "func"
			)
		);

		// verificamos la sesion del usuario
		$this->sess->validarSesionUsuario(false);

		$this->model = $this->model("Header");

		$this->viewTwig();
	}

	public function inicio()
	{
		$user = $this->model->datosUsuario();
		$user->saludo = ($user->usr_genero == "F")  ? "Bienvenida" : "Bienvenido";

		$this->display(
			array(
				"url" => $this->url,
				"layout" => $this->layoutView,
				"images" => $this->pathImages,
				"user" => $user,
				"ultimoAcceso" => $this->func->date2screen($user->usr_ultimaSesion)
			)
		);
	}

}
?>