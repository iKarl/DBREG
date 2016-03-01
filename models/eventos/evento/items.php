<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/models/eventos/evento/
 * @version $Id: items.php 1.0 2012-06-27 22:33 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Model\Model_Controller as Model;

class Items_Model extends Model
{

	/**
	 * 
	 * Metodo que obtiene las categorias del evento
	 * 
	 * 
	 */
	public function getItems($idEvento = 0)
	{
		$items = array();

		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "SELECT eci_clave, eci_costo_fecha_1, eci_fecha_1, eci_costo_fecha_2, 
			eci_fecha_2, eci_costo_fecha_3, eci_fecha_3, eci_costo_otro, eci_costo_sitio, 
			eci_aWeb, eci_paxMaximos 
			FROM smc_eventoItems 
			WHERE eci_idEvento = %d";

		$qry = sprintf($qry, $idEvento);

		if ($this->db->hQuery($qry))
		{
			while ($item = $this->db->hFetchObject())
			{
				$items[] = $item;
			}
		}

		return $items;
	}

	/**
	 * 
	 * Metodo que obtiene una categoria
	 * 
	 * 
	 */
	public function getItem($clave, $idEvento)
	{
		$item = array();

		$clave = $this->db->hEscapeString($clave);
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "SELECT eci_clave, eci_costo_fecha_1, eci_fecha_1, 
			eci_costo_fecha_2, eci_fecha_2, eci_costo_fecha_3, eci_fecha_3, 
			eci_costo_otro, eci_costo_sitio, eci_aWeb, eci_paxMaximos 
			FROM smc_eventoItems 
			WHERE eci_clave = '%s' 
			AND eci_idEvento = %d";

		$qry = sprintf($qry, $clave, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() == 1)
			{
				$item = $this->db->hFetchObject();
			}
		}

		return $item;
	}

	/**
	 *  
	 * Metodo que agrega una categoria al evento
	 * 
	 * 
	 */
	public function agregarItem($post, $idEvento, $idUsuario)
	{
		$id = $this->db->hEscapeString($idEvento);
		$clave = $this->db->hEscapeString($post['eci_clave']);

		$costoFecha1 = $this->db->hEscapeString($post['eci_costo_fecha_1']);
		$fecha1 = $this->db->hEscapeString($post['eci_fecha_1']);

		$costoFecha2 = $this->db->hEscapeString($post['eci_costo_fecha_2']);
		$fecha2 = $this->db->hEscapeString($post['eci_fecha_2']);

		$costoFecha3 = $this->db->hEscapeString($post['eci_costo_fecha_3']);
		$fecha3 = $this->db->hEscapeString($post['eci_fecha_3']);

		$costoSitio = $this->db->hEscapeString($post['eci_costo_otro']);
		$costoOtro = $this->db->hEscapeString($post['eci_costo_sitio']);

		$pax = $this->db->hEscapeString($post['eci_paxMaximos']);

		$idUsuario = $this->db->hEscapeString($idUsuario);

		$qry = "INSERT INTO smc_eventoItems SET 
			eci_idEvento = %d,
			eci_clave = '%s',
			eci_costo_fecha_1 = '%s',
			eci_fecha_1 = '%s',
			eci_costo_fecha_2 = '%s',
			eci_fecha_2 = '%s',
			eci_costo_fecha_3 = '%s',
			eci_fecha_3 = '%s',
			eci_costo_sitio = '%s',
			eci_costo_otro = '%s',
			eci_paxMaximos = %d,
			eci_fechaAlta = NOW(),
			eci_usuarioAlta = %d
		";

		$qry = sprintf(
			$qry,
			$id,
			$clave,
			$costoFecha1,
			$fecha1,
			$costoFecha2,
			$fecha2,
			$costoFecha3,
			$fecha3,
			$costoSitio,
			$costoOtro,
			$pax,
			$idUsuario
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

	/**
	 *  
	 * Metodo que actualiza una categoria
	 * 
	 * 
	 */
	public function actualizarItem($post, $idEvento, $idUsuario)
	{
		$id = $this->db->hEscapeString($idEvento);
		$claveAnt = $this->db->hEscapeString($post['eci_clave_anterior']);
		$clave = $this->db->hEscapeString($post['eci_clave_a']);
		$costoFecha1 = $this->db->hEscapeString($post['eci_costo_fecha_1_a']);
		$fecha1 = $this->db->hEscapeString($post['eci_fecha_1_a']);
		$costoFecha2 = $this->db->hEscapeString($post['eci_costo_fecha_2_a']);
		$fecha2 = $this->db->hEscapeString($post['eci_fecha_2_a']);
		$costoFecha3 = $this->db->hEscapeString($post['eci_costo_fecha_3_a']);
		$fecha3 = $this->db->hEscapeString($post['eci_fecha_3_a']);
		$costoSitio = $this->db->hEscapeString($post['eci_costo_sitio_a']);
		$costoOtro = $this->db->hEscapeString($post['eci_costo_otro_a']);
		$pax = $this->db->hEscapeString($post['eci_paxMaximos_a']);
		$idUsuario = $this->db->hEscapeString($idUsuario);

		$qry = "UPDATE smc_eventoItems SET 
			eci_clave = '%s',
			eci_costo_fecha_1 = '%s',
			eci_fecha_1 = '%s',
			eci_costo_fecha_2 = '%s',
			eci_fecha_2 = '%s',
			eci_costo_fecha_3 = '%s',
			eci_fecha_3 = '%s',
			eci_costo_otro = '%s',
			eci_costo_sitio = '%s',
			eci_aWeb = %d,
			eci_paxMaximos = %d,
			eci_fechaModifacion = NOW(),
			eci_usuarioModifico = %d
			WHERE eci_clave = '%s' 
			AND eci_idEvento = %d 
			LIMIT 1";

		$qry = sprintf($qry, $clave, $costoFecha1, $fecha1, $costoFecha2, $fecha2, $costoFecha3, $fecha3, $costoOtro, $costoSitio, 0, $pax, $idUsuario, $claveAnt, $id);

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
	 * 
	 * Metodo que actualiza las claves en los nombres de las categoria
	 * 
	 * 
	 */
	public function actualizarClavesItem($nuevaClave, $clave, $idEvento)
	{
		$nuevaClave = $this->db->hEscapeString($nuevaClave);
		$clave = $this->db->hEscapeString($clave);
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "UPDATE smc_eventoItemsNombres SET 
			eni_clave = '%s'
			WHERE eni_idEvento = %d 
			AND eni_clave = '%s'
		";

		$qry = sprintf($qry, $nuevaClave, $idEvento, $clave);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() >= 1)
			{
				return true;
			}
		}

		return false;
	}

	/**
	 *  
	 * Metodo que elimina una categoria
	 * 
	 * 
	 */
	public function eliminarItem($clave, $idEvento)
	{
		$clave = $this->db->hEscapeString($clave);
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "DELETE FROM smc_eventoItems 
			WHERE eci_clave = '%s' 
			AND eci_idEvento = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $clave, $idEvento);

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
	 * 
	 * Metodo que elimina los nombres del item
	 * 
	 * 
	 */
	public function eliminarNombresItem($clave, $idEvento)
	{
		$clave = $this->db->hEscapeString($clave);
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "DELETE FROM smc_eventoItemsNombres 
			WHERE eni_clave = '%s' 
			AND eni_idEvento = %d
		";

		$qry = sprintf($qry, $clave, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() >= 0)
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * 
	 * Metodo que elimina los nombres de la categoria
	 * 
	 * 
	 */
	public function eliminarNombre($clave, $idEvento, $idNombre = 0)
	{
		$clave = $this->db->hEscapeString($clave);
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "DELETE FROM smc_eventoItemsNombres 
			WHERE eni_clave = '%s' 
			AND eni_idEvento = %d
		";

		if ($idNombre)
		{
			$idNombre = $this->db->hEscapeString($idNombre);
			$qry .= sprintf(" AND eni_idNombreItem = %d LIMIT 1", $idNombre);
		}

		$qry = sprintf($qry, $clave, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() >= 0)
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * 
	 * Metodo que verifica si ya existe una categoria con la clave indicada
	 * 
	 * 
	 */
	public function validarClaveItem($clave, $idEvento, $claveAnt = "")
	{
		$clave = $this->db->hEscapeString($clave);
		$id = $this->db->hEscapeString($idEvento);

		$qry = "SELECT eci_idEvento 
			FROM smc_eventoItems 
			WHERE (eci_clave = '%s' 
			AND eci_idEvento = %d)";

		if ($claveAnt != "")
		{
			$claveAnt = $this->db->hEscapeString($claveAnt);
			$qry .= " AND (eci_clave <> '" . $claveAnt . "')";
		}

		$qry = sprintf($qry, $clave, $id);

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

	/**
	 *
	 * Metodo que obtiene los nombres de la categorias
	 *
	 */
	public function getNombresItems($clave, $idEvento)
	{
		$nombres = array();

		$clave = $this->db->hEscapeString($clave);
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "SELECT eni_idNombreItem, eni_clave, eni_idioma, eni_nombre 
			FROM smc_eventoItemsNombres 
			WHERE eni_idEvento = %d 
			AND eni_clave = '%s'
		";

		$qry = sprintf($qry, $idEvento, $clave);

		if ($this->db->hQuery($qry))
		{
			while ($fila = $this->db->hFetchObject()) {
				$nombres[] =  $fila;
			}
		}

		return $nombres;
	}

	/**
	 *
	 * Metodo que obtiene los idiomas de los nombres de las categorias existentes
	 *
	 */
	public function getIdiomasItems($clave, $idEvento)
	{
		$idiomas = array();

		$clave = $this->db->hEscapeString($clave);
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "SELECT eni_idioma 
			FROM smc_eventoItemsNombres 
			WHERE eni_clave = '%s' 
			AND eni_idEvento = %d
		";

		$qry = sprintf($qry, $clave, $idEvento);

		if ($this->db->hQuery($qry))
		{
			while ($fila = $this->db->hFetchObject()) {
				$idiomas[] = $fila;
			}
		}

		return $idiomas;
	}

	/**
	 *  
	 * Metodo que verifica el nombre del item
	 * 
	 * 
	 */
	public function validarNombre($nombre, $idEvento, $nombreAnt = "")
	{
		$nombre = $this->db->hEscapeString($nombre);
		$id = $this->db->hEscapeString($idEvento);

			$qry = "SELECT eni_idEvento 
				FROM smc_eventoItemsNombres 
				WHERE eni_nombre = '%s' 
				AND eni_idEvento = %d
			";

			if ($nombreAnt != "")
			{
				$nombreAnt = $this->db->hEscapeString($nombreAnt);
				$qry .= " AND eni_nombre <> '" . $nombreAnt . "'";
			}

			$qry = sprintf($qry, $nombre, $id);

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

	public function insertarNombre($post, $idEvento)
	{
		$idEvento = $this->db->hEscapeString($idEvento);
		$clave = $this->db->hEscapeString($post['eni_clave']);
		$idioma = $this->db->hEscapeString($post['eni_idioma']);
		$nombre = $this->db->hEscapeString($post['eni_nombre']);

		$qry = "INSERT INTO smc_eventoItemsNombres SET 
			eni_idEvento = %d,
			eni_clave = '%s',
			eni_idNombreItem = %d,
			eni_idioma = '%s',
			eni_nombre = '%s'
		";

		$idItem = $this->getMaxId($clave, $idEvento);

		$qry = sprintf($qry, $idEvento, $clave, $idItem, $idioma, $nombre);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return $idItem;
			}
		}

		return false;
	}

	private function getMaxId($clave, $idEvento)
	{
		$qry = "SELECT MAX(eni_idNombreItem) AS idItem 
			FROM smc_eventoItemsNombres 
			WHERE eni_clave = '%s' 
			AND eni_idEvento = %d
		";

		$qry = sprintf($qry, $clave, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				$max = $this->db->hFetchObject();
				if ($max->idItem)
				{
					return $max->idItem + 1;
				}
			}
		}

		return 1;
	}

}
?>