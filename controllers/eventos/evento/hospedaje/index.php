<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/controllers/eventos/hospedaje/
 * @version $Id: index.php 1.0 2014-03-18 21:31 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Controller\Base_Controller as Controller;

class Index_Controller extends Controller
{
	public $seccion = "";
	public $idEvento = 0;
	public $func = null;
	public $fova = null;
	private $user_login = array();
	private $json = array();

	/**
	 * Metodo constructor
	 */
	public function __construct()
	{
		$this->librarys(
			array(
				"Sessions" => "sess",
				"Functions" => "fc",
				"Eventos" => "evt"
			)
		);

		$this->sess->validarSesionUsuario();
		$this->sess->validarPermisoSeccion(1);

		$this->helpers("FormValidator", "fv");

		$this->func = $this->fc;
		$this->fova = $this->fv;

		// Sección
		//$this->seccion = parent::$section;

		// Datos de usuario en sesion
		$this->user_login = $this->sess->get('user');

		if ($this->sess->get("idEvento"))
		{
			$this->idEvento = $this->sess->get("idEvento");
		}

		$this->model = $this->model("Index", "eventos/evento/hospedaje");

		$this->viewTwig();
	}

	public function inicio()
	{
		$this->display(
			array(
				'hoteles' => $this->model->getHoteles($this->idEvento)
			)
		);
	}

	public function setHotel($request)
	{
		$this->json = array(
			'status' => false,
			'mensaje' => 'No fue posible agregar el hotel, intentalo de nuevo.'
		);

		if (!empty($request->query['htl_nombre']))
		{
			if (($id_hotel = $this->model->setHotel($request->query, $this->idEvento, $this->user_login['id'])) !== false)
			{
				$this->json = array(
					'status' => true,
					'id_hotel' => $id_hotel,
					'hotel' => $request->query
				);
			}
		}

		echo json_encode($this->json);
	}

	public function updateHotel($request)
	{
		$this->json = array(
			'status' => false,
			'mensaje' => 'No fue posible actualizar el hotel, intentalo de nuevo.'
		);

		if (!empty($request->query['htl_nombre']))
		{
			if ($this->model->updateHotel($request->query, $this->idEvento, $this->user_login['id']))
			{
				$this->json = array(
					'status' => true,
					'id_hotel' => $request->query['htl_id'],
					'hotel' => $request->query
				);
			}
		}

		echo json_encode($this->json);
	}

	public function getHotel($request)
	{
		$hotel = array();

		if (!empty($request->get['htl_id']))
		{
			$hotel = $this->model->getHotel($request->get['htl_id'], $this->idEvento);
		}

		echo json_encode(
			array(
				'hotel' => $hotel
			)
		);
	}

	public function verHotel($request)
	{
		if (!empty($request->get['id']))
		{
			$this->sess->set('id_hotel', $request->get['id']);
		}

		$this->display(
			array(
				'hotel' => $this->model->getHotel($request->get['id'], $this->idEvento),
				'habitaciones' => $this->model->getHabitaciones($request->get['id'])
			)
		);
	}

	public function setHabitacion($request)
	{
		$this->json = array(
			'status' => false,
			'mensaje' => 'No fue posible agregar la habitación, intentalo de nuevo.'
		);

		if (!empty($request->query['hhb_clave']) AND !empty($request->query['hhb_pax'])
			AND !empty($request->query['hhb_paxMaxReservacion']))
		{
			if (($id_habitacion = $this->model->setHabitacion($request->query, $this->sess->get('id_hotel'), $this->idEvento, $this->user_login['id'])) !== false)
			{
				$this->json = array(
					'status' => true,
					'id_habitacion' => $id_habitacion,
					'habitacion' => $request->query
				);
			}
		}

		echo json_encode($this->json);
	}

	public function verHabitacion($request)
	{
		if (!empty($request->get['id']))
		{
			$this->sess->set('id_habitacion', $request->get['id']);
		}

		$idiomas = (array) $this->evt->getIdiomas($this->idEvento);
		$nombres = $this->model->getNombresHabitacion($request->get['id'], $this->sess->get('id_hotel'), $this->idEvento);

		foreach ($nombres as $nombre)
		{
			foreach ($idiomas as $key => $idioma)
			{
				if ($idioma->eis_idioma == $nombre->hhn_idioma)
				{
					unset($idiomas[$key]);
				}
			}
		}

		$this->display(
			array(
				'hotel' => $this->model->getHotel($this->sess->get('id_hotel'), $this->idEvento),
				'habitacion' => $this->model->getHabitacion($request->get['id'], $this->sess->get('id_hotel'), $this->idEvento),
				'idiomas' => $idiomas,
				'nombres' => $nombres
			)
		);
	}

	public function updateHabitacion($request)
	{
		$this->json = array(
			'status' => false,
			'mensaje' => 'No fue posible actualizar la habitación'
		);

		if ($this->model->updateHabitacion($request->query, $this->sess->get('id_habitacion'), $this->sess->get('id_hotel'), $this->idEvento, $this->user_login['id']))
		{
			$this->json = array(
				'status' => true,
				'mensaje' => 'Cambios guardados con exito!'
			);
		}

		echo json_encode($this->json);
	}

	public function setCostoHabitacion($request)
	{
		$this->json = array(
			'status' => false,
			'mensaje' => 'No fue posible agregar los costos de la habitación, intentalo de nuevo.'
		);

		if (!empty($request->query['hhc_costoAdulto']))
		{
			$request->query['hhc_costoAdulto'] = $this->fc->moneda2db($request->query['hhc_costoAdulto']);
			$request->query['hhc_costoMenor'] = $this->fc->moneda2db($request->query['hhc_costoMenor']);
			$request->query['hhc_costoCamaristaNoche'] = $this->fc->moneda2db($request->query['hhc_costoCamaristaNoche']);
			$request->query['hhc_costoBellBoys'] = $this->fc->moneda2db($request->query['hhc_costoBellBoys']);

			if (($id_costo_habitacion = $this->model->setCostoHabitacion($request->query, $this->idEvento, $this->sess->get('id_hotel'), $this->sess->get('id_habitacion'), $this->user_login['id'])) !== false)
			{
				$request->query['hhc_costoAdulto'] = $this->fc->moneda2Screen($request->query['hhc_costoAdulto']);
				$request->query['hhc_costoMenor'] = $this->fc->moneda2Screen($request->query['hhc_costoMenor']);
				$request->query['hhc_costoCamaristaNoche'] = $this->fc->moneda2Screen($request->query['hhc_costoCamaristaNoche']);
				$request->query['hhc_costoBellBoys'] = $this->fc->moneda2Screen($request->query['hhc_costoBellBoys']);

				$this->json = array(
					'status' => true,
					'id_costo_habitacion' => $id_costo_habitacion,
					'costo_habitacion' => $request->query
				);
			}
		}

		echo json_encode($this->json);
	}

	public function updateCostoHabitacion($request)
	{
		$this->json = array(
			'status' => false,
			'mensaje' => 'No fue posible guardar los costos de la habitación, intentalo de nuevo.'
		);

		if (!empty($request->query['hhc_costoAdulto']))
		{
			$request->query['hhc_costoAdulto'] = $this->fc->moneda2db($request->query['hhc_costoAdulto']);
			$request->query['hhc_costoMenor'] = $this->fc->moneda2db($request->query['hhc_costoMenor']);
			$request->query['hhc_costoCamaristaNoche'] = $this->fc->moneda2db($request->query['hhc_costoCamaristaNoche']);
			$request->query['hhc_costoBellBoys'] = $this->fc->moneda2db($request->query['hhc_costoBellBoys']);

			if (($id_costo_habitacion = $this->model->updateCostoHabitacion($request->query, $this->idEvento, $this->sess->get('id_hotel'), $this->sess->get('id_habitacion'), $this->user_login['id'])) !== false)
			{
				$request->query['hhc_costoAdulto'] = $this->fc->moneda2Screen($request->query['hhc_costoAdulto']);
				$request->query['hhc_costoMenor'] = $this->fc->moneda2Screen($request->query['hhc_costoMenor']);
				$request->query['hhc_costoCamaristaNoche'] = $this->fc->moneda2Screen($request->query['hhc_costoCamaristaNoche']);
				$request->query['hhc_costoBellBoys'] = $this->fc->moneda2Screen($request->query['hhc_costoBellBoys']);

				$this->json = array(
					'status' => true,
					'id_costo_habitacion' => $id_costo_habitacion,
					'costo_habitacion' => $request->query
				);
			}
		}

		echo json_encode($this->json);
	}

	public function setNombreHabitacion($request)
	{
		$this->json = array(
			'status' => false,
			'mensaje' => 'No fue posible agregar el nombre.'
		);

		if (($id_nombre = $this->model->setNombreHabitacion($request->query, $this->idEvento, $this->sess->get('id_hotel'), $this->sess->get('id_habitacion'))) !== false)
		{
			$this->json = array(
				'status' => true,
				'id_nombre' => $id_nombre,
				'nombre' => $request->query
			);
		}

		echo json_encode($this->json);
	}

	public function deleteNombreHabitacion($request)
	{
		$this->json = array(
			'status' => false,
			'mensaje' => 'No fue posible eliminar el nombre, intentalo de nuevo.'
		);

		if (!empty($request->get['hhn_id']))
		{
			if ($this->model->deleteNombreHabitacion($request->get['hhn_id'], $this->idEvento, $this->sess->get('id_hotel'), $this->sess->get('id_habitacion')))
			{
				$request->get['hhn_nombre_del'] = '';
				$idiomas = $this->evt->getIdiomas($this->idEvento);
				//$nombres = $this->model->getNombresHabitacion($request->get['id'], $this->sess->get('id_hotel'), $this->idEvento);

				//foreach ($nombres as $nombre)
				//{
					foreach ($idiomas as $key => $idioma)
					{
						if ($request->get['hhn_idioma_del'] == $idioma->eis_idioma)
						{
							$request->get['hhn_nombre_del'] = $idioma->eis_nombre;
						}
					}
				//}

				$this->json = array(
					'status' => true,
					'idioma' => $request->get
				);
			}
		}

		echo json_encode($this->json);
	}
}