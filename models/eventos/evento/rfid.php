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

class Rfid_Model extends Model
{
	public function getEvento($idEvento)
	{
		$evento = array();

		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "SELECT evt_idEvento, evt_nombre, evt_clave, evt_inicio, evt_termina, evt_divisa,
			evt_tipoCambio, evt_nombreTablaAsistentes 
			FROM smc_eventos 
			WHERE evt_idEvento = %d
		";

		$qry = sprintf($qry, $idEvento);

		if ($this->db->hQuery($qry))
		{
			$evento = $this->db->hFetchObject();
		}

		return $evento;
	}

	/**
	 * Metodo que agrega una categoria al evento
	 */
	public function showTables($clave_evento)
	{
		$tablas = array();
		$qry = "SHOW TABLES";
		$clave = '/' . $clave_evento . '_RFID/i';

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() >= 1)
			{
				while ($tabla = $this->db->hFetchArray())
				{
					if (preg_match($clave, $tabla[0]))
					{
						$tablas[] = $tabla[0];
					}
				}
			}
		}

		return $tablas;
	}

	/**
	 * Metodo que agrega una categoria al evento
	 */
	public function cerrarSesion($post, $tabla)
	{
		$fecha_salida = $this->db->hEscapeString($post['fecha_salida']);
		$hora_salida = $this->db->hEscapeString($post['hora_salida']);
		$numero_antena_salida = $this->db->hEscapeString($post['numero_antena_salida']);
		$numero_sesion = $this->db->hEscapeString($post['numero_sesion']);

		$qry = "UPDATE %s SET 
			fechaHora_salida = '%s',
			numero_antena_salida = '%s',
			numero_sesion = '%s',
			status = 2 
			WHERE status = 1
		";

		$qry = sprintf($qry, $tabla, $fecha_salida . ' ' . $hora_salida, $numero_antena_salida, $numero_sesion);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() >= 1)
			{
				return true;
			}
		}

		return false;
	}
}