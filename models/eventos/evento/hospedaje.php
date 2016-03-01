<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/models/eventos/evento/
 * @version $Id: index.php 1.0 2012-03-23 00:08 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Model\Model_Controller as Model;

class Index_Model extends Model
{

	/**
	 * Metodo que agrega una categoria al evento
	 */
	public function setHotel($post, $idEvento, $idUsuario)
	{
		$htl_nombre = $this->db->hEscapeString($post['htl_nombre']);
		$htl_incluye = $this->db->hEscapeString($post['htl_incluye']);
		$htl_direccion = $this->db->hEscapeString($post['htl_direccion']);
		$htl_status = $this->db->hEscapeString($post['htl_status']);

		$qry = "INSERT INTO smc_hoteles SET 
			htl_idEvento = %d,
			htl_nombre = '%s',
			htl_incluye = '%s',
			htl_direccion = '%s',
			htl_fechaAlta = NOW(),
			htl_usuarioAlta = %d,
			htl_status = %d
		";

		$qry = sprintf($qry, $idEvento, $htl_nombre, $htl_incluye, $htl_direccion, $idUsuario, $htl_status);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return $this->db->hInsertID();
			}
		}

		return false;
	}

	/**
	 * Metodo que agrega una categoria al evento
	 */
	public function updateHotel($post, $idEvento, $idUsuario)
	{
		$htl_nombre = $this->db->hEscapeString($post['htl_nombre']);
		$htl_incluye = $this->db->hEscapeString($post['htl_incluye']);
		$htl_direccion = $this->db->hEscapeString($post['htl_direccion']);
		$htl_status = $this->db->hEscapeString($post['htl_status']);
		$htl_id = $this->db->hEscapeString($post['htl_id']);

		$qry = "UPDATE smc_hoteles SET 
			htl_nombre = '%s',
			htl_incluye = '%s',
			htl_direccion = '%s',
			htl_fechaModificacion = NOW(),
			htl_usuarioModifico = %d,
			htl_status = %d 
			WHERE htl_id = %d 
			AND htl_idEvento = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $htl_nombre, $htl_incluye, $htl_direccion, $idUsuario, $htl_status, $htl_id, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}
		}

		return false;
	}

	public function getHoteles($idEvento)
	{
		$hoteles = array();

		$qry = "SELECT * 
			FROM smc_hoteles 
			WHERE htl_idEvento = %d
		";

		$qry = sprintf($qry, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($hotel = $this->db->hFetchObject())
				{
					$hoteles[] = $hotel;
				}

				$this->db->hFree();
			}
		}

		return $hoteles;
	}

	public function getHotel($id_hotel, $idEvento)
	{
		$hotel = array();

		$qry = "SELECT * 
			FROM smc_hoteles 
			WHERE htl_id = %d 
			AND htl_idEvento = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $id_hotel, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() == 1)
			{
				$hotel = $this->db->hFetchObject();
				$this->db->hFree();
			}
		}

		return $hotel;
	}

	/**
	 * Metodo que agrega una categoria al evento
	 */
	public function setHabitacion($post, $id_hotel, $idEvento, $idUsuario)
	{
		$hhb_clave = $this->db->hEscapeString($post['hhb_clave']);
		$hhb_pax = $this->db->hEscapeString($post['hhb_pax']);
		$hhb_paxMaxReservacion = $this->db->hEscapeString($post['hhb_paxMaxReservacion']);
		$hhb_paxAdultos = $this->db->hEscapeString($post['hhb_paxAdultos']);
		$hhb_paxMenores = $this->db->hEscapeString($post['hhb_paxMenores']);

		$qry = "INSERT INTO smc_hotelesHabitaciones SET 
			hhb_id_hotel = %d,
			hhb_idEvento = %d,
			hhb_clave = '%s',
			hhb_pax = %d,
			hhb_paxMaxReservacion = %d,
			hhb_paxAdultos = %d,
			hhb_paxMenores = %d,
			hhb_fechaAlta = NOW(),
			hhb_usuarioAlta = %d,
			hhb_status = 1
		";

		$qry = sprintf($qry, $id_hotel, $idEvento, $hhb_clave, $hhb_pax, $hhb_paxMaxReservacion, $hhb_paxAdultos, $hhb_paxMenores, $idUsuario);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return $this->db->hInsertID();
			}
		}

		return false;
	}

	public function getHabitaciones($id_hotel)
	{
		$habitaciones = array();

		$qry = "SELECT * 
			FROM smc_hotelesHabitaciones 
			WHERE hhb_id_hotel = %d
		";

		$qry = sprintf($qry, $id_hotel);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($habitacion = $this->db->hFetchObject())
				{
					$habitaciones[] = $habitacion;
				}

				$this->db->hFree();
			}
		}

		return $habitaciones;
	}

	public function getHabitacion($id_habitacion, $id_hotel, $idEvento)
	{
		$habitacion = array();

		$qry = "SELECT h.*, c.* 
			FROM smc_hotelesHabitaciones AS h 
			LEFT JOIN smc_hotelesHabitacionesCostos AS c ON (c.hhc_id_habitacion = h.hhb_id) 
			WHERE h.hhb_id = %d 
			AND h.hhb_id_hotel = %d 
			AND h.hhb_idEvento = %d 
		";

		$qry = sprintf($qry, $id_habitacion, $id_hotel, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() == 1)
			{
				$habitacion = $this->db->hFetchObject();
				$this->db->hFree();
			}
		}

		return $habitacion;
	}

	/**
	 * Metodo que agrega una categoria al evento
	 */
	public function updateHabitacion($post, $id_habitacion, $id_hotel, $idEvento, $idUsuario)
	{
		$hhb_clave = $this->db->hEscapeString($post['hhb_clave']);
		$hhb_pax = $this->db->hEscapeString($post['hhb_pax']);
		$hhb_paxMaxReservacion = $this->db->hEscapeString($post['hhb_paxMaxReservacion']);
		$hhb_paxAdultos = $this->db->hEscapeString($post['hhb_paxAdultos']);
		$hhb_paxMenores = $this->db->hEscapeString($post['hhb_paxMenores']);
		$hhb_status = $this->db->hEscapeString($post['hhb_status']);

		$qry = "UPDATE smc_hotelesHabitaciones SET 
			hhb_clave = '%s',
			hhb_pax = %d,
			hhb_paxMaxReservacion = %d,
			hhb_paxAdultos = %d,
			hhb_paxMenores = %d,
			hhb_fechaModificacion = NOW(),
			hhb_usuarioModifico = %d,
			hhb_status = %d 
			WHERE hhb_id = %d 
			AND hhb_id_hotel = %d 
			AND hhb_idEvento = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $hhb_clave, $hhb_pax, $hhb_paxMaxReservacion, $hhb_paxAdultos, $hhb_paxMenores, $idUsuario, $hhb_status, $id_habitacion, $id_hotel, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Metodo que agrega una categoria al evento
	 */
	public function setCostoHabitacion($post, $idEvento, $id_hotel, $id_habitacion, $idUsuario)
	{
		$hhc_costoAdulto = $this->db->hEscapeString($post['hhc_costoAdulto']);
		$hhc_costoMenor = $this->db->hEscapeString($post['hhc_costoMenor']);
		$hhc_costoCamaristaNoche = $this->db->hEscapeString($post['hhc_costoCamaristaNoche']);
		$hhc_costoBellBoys = $this->db->hEscapeString($post['hhc_costoBellBoys']);

		$qry = "INSERT INTO smc_hotelesHabitacionesCostos SET 
			hhc_idEvento = %d,
			hhc_id_hotel = %d,
			hhc_id_habitacion = %d,
			hhc_costoAdulto = '%s',
			hhc_costoMenor = '%s',
			hhc_costoCamaristaNoche = '%s',
			hhc_costoBellBoys = '%s',
			hhc_fechaAlta = NOW(),
			hhc_usuarioAlta = %d
		";

		$qry = sprintf($qry, $idEvento, $id_hotel, $id_habitacion, $hhc_costoAdulto, $hhc_costoMenor, $hhc_costoCamaristaNoche, $hhc_costoBellBoys, $idUsuario);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return $this->db->hInsertID();
			}
		}

		return false;
	}

	/**
	 * Metodo que agrega una categoria al evento
	 */
	public function updateCostoHabitacion($post, $idEvento, $id_hotel, $id_habitacion, $idUsuario)
	{
		$hhc_costoAdulto = $this->db->hEscapeString($post['hhc_costoAdulto']);
		$hhc_costoMenor = $this->db->hEscapeString($post['hhc_costoMenor']);
		$hhc_costoCamaristaNoche = $this->db->hEscapeString($post['hhc_costoCamaristaNoche']);
		$hhc_costoBellBoys = $this->db->hEscapeString($post['hhc_costoBellBoys']);

		$qry = "UPDATE smc_hotelesHabitacionesCostos SET 
			hhc_costoAdulto = '%s',
			hhc_costoMenor = '%s',
			hhc_costoCamaristaNoche = '%s',
			hhc_costoBellBoys = '%s',
			hhc_fechaModificacion = NOW(),
			hhc_usuarioModifico = %d 
			WHERE hhc_id = %d 
			AND hhc_idEvento = %d 
			AND hhc_id_hotel = %d 
			AND hhc_id_habitacion = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $hhc_costoAdulto, $hhc_costoMenor, $hhc_costoCamaristaNoche, $hhc_costoBellBoys, $idUsuario, $post['hhc_id'], $idEvento, $id_hotel, $id_habitacion);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Metodo que agrega una categoria al evento
	 */
	public function setNombreHabitacion($post, $idEvento, $id_hotel, $id_habitacion)
	{
		$hhn_idioma = $this->db->hEscapeString($post['hhn_idioma']);
		$hhn_nombre = $this->db->hEscapeString($post['hhn_nombre']);

		$qry = "INSERT INTO smc_hotelesHabitacionesNombres SET 
			hhn_idEvento = %d,
			hhn_idHotel = %d,
			hhn_idHabitacion = %d,
			hhn_idioma = '%s',
			hhn_nombre = '%s'
		";

		$qry = sprintf($qry, $idEvento, $id_hotel, $id_habitacion, $hhn_idioma, $hhn_nombre);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return $this->db->hInsertID();
			}
		}

		return false;
	}

	public function getNombresHabitacion($id_habitacion, $id_hotel, $id_evento)
	{
		$nombres = array(); 

		$qry = "SELECT * 
			FROM smc_hotelesHabitacionesNombres 
			WHERE hhn_idHabitacion = %d 
			AND hhn_idHotel = %d 
			AND hhn_idEvento = %d
		";

		$qry = sprintf($qry,$id_habitacion, $id_hotel, $id_evento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($nombre = $this->db->hFetchObject())
				{
					$nombres[] = $nombre;
				}
				$this->db->hFree();
			}
		}

		return $nombres;
	}

	public function deleteNombreHabitacion($id_nombre, $idEvento, $id_hotel, $id_habitacion)
	{
		$status = false;

		$qry = "DELETE FROM smc_hotelesHabitacionesNombres 
			WHERE hhn_id = %d 
			AND hhn_idHotel = %d 
			AND hhn_idEvento = %d 
			AND hhn_idHabitacion = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $id_nombre, $id_hotel, $idEvento, $id_habitacion);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				$status = true;
			}
		}

		return $status;
	}
}