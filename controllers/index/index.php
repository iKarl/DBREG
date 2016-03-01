<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/controllers/index/
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * @version $Id: index.php 1.0 2012-01-30 21:53 _Karl_ $;
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Controller\Base_Controller as Controller;

class Index_Controller extends Controller
{

	private $seccion = "";

	public function __construct()
	{
		$this->librarys("Sessions", "sess");
		$this->sess->isSession();
		$this->model = $this->model("Index");

		$this->seccion = parent::$section;

		$this->viewTwig();
	}

	/*
	 * Muestra el formulario de acceso al sistema
	 */
	public function inicio()
	{
		// Mostramos la vista
		$this->display(
			array(
				"url" => $this->url,
				"seccion" => $this->seccion,
				"layout" => $this->layoutView,
				"images" => $this->pathImages
			)
		);
	}

	public function rfid($request)
	{
		$this->model->prueba($request->query);
		echo 'Insert!';
	}
}
?>