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

	public function __construct() {
		$this->librarys("Sessions", "sess");
		$this->sess->isSession();
		$this->model = $this->model("Index");

		$this->seccion = parent::$section;

		$this->viewTwig();
	}

	/*
	 * Muestra el formulario de acceso al sistema
	 */
	public function inicio() {
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

	public function rfid($request) {
		$this->model->prueba($request->query);
		echo 'Insert!';
	}

	public function search($request) {
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Credentials: true ");
		header("Access-Control-Allow-Methods: OPTIONS, GET, POST");
		header("Access-Control-Allow-Headers: Content-Type, Depth, User-Agent, X-File-Size, X-Requested-With, If-Modified-Since, X-File-Name, Cache-Control");

		if (!empty($request->get["paterno"]) && !empty($request->get["nombre"])) {
			$registros = $this->model->searchAmbos($request->get["paterno"], $request->get["nombre"]);
		} else if (!empty($request->get["paterno"]) && empty($request->get["nombre"])) {
			$registros = $this->model->searchPaterno($request->get["paterno"]);
		} else if (!empty($request->get["nombre"]) && empty($request->get["paterno"])) {
			$registros = $this->model->searchNombre($request->get["nombre"]);
		} else if (!empty($request->get["empresa"]) && empty($request->get["paterno"]) && empty($request->get["nombre"])) {
			$registros = $this->model->searchEmpresa($request->get["empresa"]);
		}

		echo json_encode($registros);
	}

	public function get($request) {
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Credentials: true ");
		header("Access-Control-Allow-Methods: OPTIONS, GET, POST");
		header("Access-Control-Allow-Headers: Content-Type, Depth, User-Agent, X-File-Size, X-Requested-With, If-Modified-Since, X-File-Name, Cache-Control");

		$registro = $this->model->get($request->get["id"]);
		echo json_encode($registro);
	}

	public function create($request) {
		$this->json =  array(
			"status" => false
		);
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Credentials: true ");
		header("Access-Control-Allow-Methods: OPTIONS, GET, POST");
		header("Access-Control-Allow-Headers: Content-Type, Depth, User-Agent, X-File-Size, X-Requested-With, If-Modified-Since, X-File-Name, Cache-Control");
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body);
		$data->costo = "0";

		if ($data->categoria == "FEMECOT") {
			$data->costo = "6000";
		} else if ($data->categoria == "RESIDENTE") {
			$data->costo = "3000";
		} else if ($data->categoria == "ENFERMERIA") {
			$data->costo = "3000";
		} else if ($data->categoria == "CMO") {
			$data->costo = "6000";
		} else if ($data->categoria == "GENERAL") {
			$data->costo = "6000";
		} else if ($data->categoria == "ACOMP") {
			$data->costo = "2000";
		} else if ($data->categoria == "ACOMMENOR") {
			$data->costo = "1000";
		}

		if (($id = $this->model->set($data)) !== false) {
			//$this->base64_to_jpg($data->photo, "./views/images/fotos/foto_" . $id . ".jpg");
			//$this->base64_to_jpg($data->uid, "./views/images/fotos/id_front_" . $id . ".jpg");

			//$this->model->asistencia($id);

			$this->json["status"] = true;
			$this->json["id"] = $id;
			echo json_encode($this->json);
		}
	}

	public function update($request) {
		$this->json =  array(
			"status" => false
		);
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Credentials: true ");
		header("Access-Control-Allow-Methods: OPTIONS, GET, POST");
		header("Access-Control-Allow-Headers: Content-Type, Depth, User-Agent, X-File-Size, X-Requested-With, If-Modified-Since, X-File-Name, Cache-Control");
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body);

		if ($this->model->update($data)) {
			/*if (!empty($data->photo)) {
				$this->base64_to_jpg($data->photo, "./views/images/fotos/foto_" . $data->id_registro . ".jpg");
			}
			
			if (!empty($data->uid)) {
				$this->base64_to_jpg($data->uid, "./views/images/fotos/id_front_" . $data->id_registro . ".jpg");
			}*/

			$this->json["status"] = true;
			echo json_encode($this->json);
		}
	}

	public function cena($request) {
		$this->json =  array(
			"status" => false
		);
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Credentials: true ");
		header("Access-Control-Allow-Methods: OPTIONS, GET, POST");
		header("Access-Control-Allow-Headers: Content-Type, Depth, User-Agent, X-File-Size, X-Requested-With, If-Modified-Since, X-File-Name, Cache-Control");
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body);

		if ($this->model->cena($data->id)) {
			$this->json["status"] = true;
			echo json_encode($this->json);
		}
	}

	public function printing($request) {
		$this->json =  array(
			"status" => false
		);
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Credentials: true ");
		header("Access-Control-Allow-Methods: OPTIONS, GET, POST");
		header("Access-Control-Allow-Headers: Content-Type, Depth, User-Agent, X-File-Size, X-Requested-With, If-Modified-Since, X-File-Name, Cache-Control");

		if ($this->model->asistencia($request->get["id"])) {
			$this->json["status"] = true;
			echo json_encode($this->json);
		}
	}

	public function validarFolio($request) {
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Credentials: true ");
		header("Access-Control-Allow-Methods: OPTIONS, GET, POST");
		header("Access-Control-Allow-Headers: Content-Type, Depth, User-Agent, X-File-Size, X-Requested-With, If-Modified-Since, X-File-Name, Cache-Control");

		$this->json = array(
			"exists" => false
		);

		if (!empty($request->get["id"])) {
			if (($registro = $this->model->validarFolioId($request->get["folio"], $request->get["id"])) !== false) {
				$this->json["exists"] = true;
			}
		} else {
			if (($registro = $this->model->validarFolio($request->get["folio"])) !== false) {
				$this->json["exists"] = true;
			}
		}

		echo json_encode($this->json);
	}

	private function base64_to_jpg($base64_string, $output_file) {
		// open the output file for writing
		$ifp = fopen( $output_file, 'wb' ); 

		// split the string on commas
		// $data[ 0 ] == "data:image/png;base64"
		// $data[ 1 ] == <actual base64 string>
		$data = explode( ',', $base64_string );

		// we could add validation here with ensuring count( $data ) > 1
		fwrite( $ifp, base64_decode( $data[ 1 ] ) );

		// clean up the file resource
		fclose( $ifp ); 

		return $output_file;
	}
}
?>