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
	 * 
	 * Metodo que nos devuelve datos de un evento
	 *
	 *
	 */
	public function getEvento($id = 0)
	{
		$evento = array();

		$id = $this->db->hEscapeString($id);

		$qry = "SELECT e.evt_idEvento, e.evt_idTipoEvento, e.evt_nombre, e.evt_clave, e.evt_idioma, e.evt_inicio, e.evt_termina, 
			e.evt_divisa, e.evt_iva, e.evt_tipoCambio, e.evt_observaciones, e.evt_fechaAlta, e.evt_usuarioAlta, 
			e.evt_fechaModificacion, e.evt_usuarioModifico, e.evt_status, t.cte_nombre, s.ecs_nombre 
			FROM smc_eventos AS e 
			JOIN smc_eventoCatalogoStatus AS s ON (s.ecs_idStatus = e.evt_status) 
			JOIN smc_eventoCatalogoTipos AS t ON (t.cte_idTipoEvento = e.evt_idTipoEvento) 
			WHERE evt_idEvento = %d
		";

		$qry = sprintf($qry, $id);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() == 1)
			{
				$evento = $this->db->hFetchObject();
				$this->db->hFree();
			}
		}

		return $evento;
	}

	/**
	 * 
	 * Metodo que verifica si ya existe un evento con la clave indicada
	 *
	 *
	 */
	public function validarClave($clave, $idEvento)
	{
		$clave = $this->db->hEscapeString($clave);
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "SELECT evt_idEvento 
			FROM smc_eventos 
			WHERE evt_clave = '%s' 
			AND evt_idEvento <> %d";

		$qry = sprintf($qry, $clave, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				return true;
			}
		}

		return false;
	}

	public function cambiarNombreTablaRegs($nombreActual, $nuevoNombre)
	{
		$nombreActual = $this->db->hEscapeString($nombreActual);
		$nuevoNombre = $this->db->hEscapeString($nuevoNombre);

		$qry = "RENAME TABLE `smcTecnoRegistro`.`smc_reg_%s` TO `smcTecnoRegistro`.`smc_reg_%s`";

		$qry = sprintf($qry, $nombreActual, $nuevoNombre);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return false;
			}
		}

		return false;
	}

	public function cambiarNombreTablaItems($nombreActual, $nuevoNombre)
	{
		$nombreActual = $this->db->hEscapeString($nombreActual);
		$nuevoNombre = $this->db->hEscapeString($nuevoNombre);

		$qry = "RENAME TABLE `smcTecnoRegistro`.`smc_reg_%s_items` TO `smcTecnoRegistro`.`smc_reg_%s_items`";

		$qry = sprintf($qry, $nombreActual, $nuevoNombre);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return false;
			}
		}

		return false;
	}

	public function cambiarNombreTablaAcoms($nombreActual, $nuevoNombre)
	{
		$nombreActual = $this->db->hEscapeString($nombreActual);
		$nuevoNombre = $this->db->hEscapeString($nuevoNombre);

		$qry = "RENAME TABLE `smcTecnoRegistro`.`smc_reg_%s_acom` TO `smcTecnoRegistro`.`smc_reg_%s_acom`";

		$qry = sprintf($qry, $nombreActual, $nuevoNombre);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return false;
			}
		}

		return false;
	}

	public function cambiarNombreTablaFotos($nombreActual, $nuevoNombre)
	{
		$nombreActual = $this->db->hEscapeString($nombreActual);
		$nuevoNombre = $this->db->hEscapeString($nuevoNombre);

		$qry = "RENAME TABLE `smcTecnoRegistro`.`smc_reg_%s_fotos` TO `smcTecnoRegistro`.`smc_reg_%s_fotos`";

		$qry = sprintf($qry, $nombreActual, $nuevoNombre);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return false;
			}
		}

		return false;
	}

	public function cambiarNombreTablaReservaciones($nombreActual, $nuevoNombre)
	{
		$nombreActual = $this->db->hEscapeString($nombreActual);
		$nuevoNombre = $this->db->hEscapeString($nuevoNombre);

		$qry = "RENAME TABLE `smcTecnoRegistro`.`smc_reg_%s_reservaciones` TO `smcTecnoRegistro`.`smc_reg_%s_reservaciones`";

		$qry = sprintf($qry, $nombreActual, $nuevoNombre);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return false;
			}
		}

		return false;
	}

	public function cambiarNombreTablaRFID($nombreActual, $nuevoNombre)
	{
		$nombreActual = $this->db->hEscapeString($nombreActual);
		$nuevoNombre = $this->db->hEscapeString($nuevoNombre);

		$qry = "RENAME TABLE `smcTecnoRegistro`.`smc_reg_%s_RFID` TO `smcTecnoRegistro`.`smc_reg_%s_RFID`";

		$qry = sprintf($qry, $nombreActual, $nuevoNombre);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return false;
			}
		}

		return false;
	}

	/**
	 * 
	 * Metodo que valida si ya existe un evento con el nombre indicado
	 *
	 *
	 */
	public function validarNombre($nombre, $idEvento)
	{
		$nombre = $this->db->hEscapeString($nombre);
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "SELECT evt_idEvento 
			FROM smc_eventos 
			WHERE evt_nombre = '%s' 
			AND evt_idEvento <> %d";

		$qry = sprintf($qry, $nombre, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * 
	 * Metodo que actualiza datos generales del evento
	 *
	 *
	 */
	public function actualizarDatosGenerales($post, $idEvento, $idUsuario)
	{
		$idEvento = $this->db->hEscapeString($idEvento);
		$idUsuario = $this->db->hEscapeString($idUsuario);

		$tipoEvento = $this->db->hEscapeString($post['evt_idTipoEvento']);
		$clave = $this->db->hEscapeString($post['evt_clave']);
		$nombre = $this->db->hEscapeString($post['evt_nombre']);
		$inicia = $this->db->hEscapeString($post['evt_inicio']);
		$termina = $this->db->hEscapeString($post['evt_termina']);
		$divisa = $this->db->hEscapeString($post['evt_divisa']);
		$iva = $this->db->hEscapeString($post['evt_iva']);
		$status = $this->db->hEscapeString($post['evt_status']);

		$qry = "UPDATE smc_eventos SET 
			evt_idTipoEvento = %d,
			evt_clave = '%s',
			evt_nombre = '%s',
			evt_inicio = '%s',
			evt_termina = '%s',
			evt_divisa = '%s',
			evt_iva = '%s',
			evt_nombreTablaAsistentes = 'smc_reg_%s',
			evt_fechaModificacion = NOW(),
			evt_usuarioModifico = %d,
			evt_status = %d 
			WHERE evt_idEvento = %d 
			LIMIT 1";

		$qry = sprintf($qry, $tipoEvento, $clave, $nombre, $inicia, $termina, $divisa, $iva, $clave, $idUsuario, $status, $idEvento);

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
	 * Metodo que actualiza las observaciones del evento
	 *
	 *
	 */
	public function actualizarObservaciones($observaciones, $idEvento, $idUsuario)
	{
		$idEvento = $this->db->hEscapeString($idEvento);
		$idUsuario = $this->db->hEscapeString($idUsuario);

		$observaciones = $this->db->hEscapeString($observaciones);

		$qry = "UPDATE smc_eventos SET 
			evt_observaciones = '%s',
			evt_fechaModificacion = NOW(),
			evt_usuarioModifico = %d 
			WHERE evt_idEvento = %d 
			LIMIT 1";

		$qry = sprintf($qry, $observaciones, $idUsuario, $idEvento);

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
	 * Metodo que actualiza el tipo de cambio de la divisa
	 *
	 *
	 */
	public function setDivisa($idEvento, $campo, $valor, $idUsuario)
	{
		$qry = "UPDATE smc_eventos SET 
			%s = '%s',
			evt_fechaModificacion = NOW(),
			evt_usuarioModifico = %d 
			WHERE evt_idEvento = %d 
			LIMIT 1";

		$qry = sprintf($qry, $campo, $valor, $idUsuario, $idEvento);

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
	 * Metodo que obtiene las categorias del evento
	 * 
	 * 
	 */
	public function getCategorias($id = 0)
	{
		$categorias = array();

		$id = $this->db->hEscapeString($id);

		$qry = "SELECT ecc_clave, ecc_costo_fecha_1, ecc_costo_fecha_2, 
			ecc_costo_fecha_3, ecc_costo_sitio, ecc_costo_otro 
			FROM smc_eventoCategorias 
			WHERE ecc_idEvento = %d";

		$qry = sprintf($qry, $id);

		if ($this->db->hQuery($qry))
		{
			while ($categoria = $this->db->hFetchObject())
			{
				$categorias[] = $categoria;
			}
		}

		return $categorias;
	}

	/**
	 * 
	 * Metodo que obtiene una categoria
	 * 
	 * 
	 */
	public function getCategoria($clave, $id)
	{
		$categoria = array();

		$clave = $this->db->hEscapeString($clave);
		$id = $this->db->hEscapeString($id);

		$qry = "SELECT ecc_clave, ecc_costo_fecha_1, ecc_costo_fecha_2, 
			ecc_costo_fecha_3, ecc_costo_sitio, ecc_costo_otro 
			FROM smc_eventoCategorias 
			WHERE ecc_clave = '%s' 
			AND ecc_idEvento = %d";

		$qry = sprintf($qry, $clave, $id);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() == 1)
			{
				$categoria = $this->db->hFetchObject();
			}
		}

		return $categoria;
	}

	/**
	 *  
	 * Metodo que agrega una categoria al evento
	 * 
	 * 
	 */
	public function agregarCategoria($post, $idEvento, $idUsuario)
	{
		$id = $this->db->hEscapeString($idEvento);
		$clave = $this->db->hEscapeString($post['ecc_clave']);
		$costoFecha1 = $this->db->hEscapeString($post['ecc_costo_fecha_1']);
		$costoFecha2 = $this->db->hEscapeString($post['ecc_costo_fecha_2']);
		$costoFecha3 = $this->db->hEscapeString($post['ecc_costo_fecha_3']);
		$costoSitio = $this->db->hEscapeString($post['ecc_costo_sitio']);
		$costoOtro = $this->db->hEscapeString($post['ecc_costo_otro']);
		$idUsuario = $this->db->hEscapeString($idUsuario);

		$qry = "INSERT INTO smc_eventoCategorias SET 
			ecc_idEvento = %d,
			ecc_clave = '%s',
			ecc_costo_fecha_1 = '%s',
			ecc_costo_fecha_2 = '%s',
			ecc_costo_fecha_3 = '%s',
			ecc_costo_sitio = '%s',
			ecc_costo_otro = '%s',
			ecc_fechaAlta = NOW(),
			ecc_usuarioAlta = %d
		";

		$qry = sprintf($qry, $id, $clave, $costoFecha1, $costoFecha2, $costoFecha3, $costoSitio, $costoOtro, $idUsuario);

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
	public function actualizarCategoria($post, $idEvento, $idUsuario)
	{
		$id = $this->db->hEscapeString($idEvento);
		$claveAnt = $this->db->hEscapeString($post['ecc_clave_ant']);
		$clave = $this->db->hEscapeString($post['ecc_clave']);
		$costoFecha1 = $this->db->hEscapeString($post['ecc_costo_fecha_1']);
		$costoFecha2 = $this->db->hEscapeString($post['ecc_costo_fecha_2']);
		$costoFecha3 = $this->db->hEscapeString($post['ecc_costo_fecha_3']);
		$costoSitio = $this->db->hEscapeString($post['ecc_costo_sitio']);
		$costoOtro = $this->db->hEscapeString($post['ecc_costo_otro']);
		$idUsuario = $this->db->hEscapeString($idUsuario);

		$qry = "UPDATE smc_eventoCategorias SET 
			ecc_clave = '%s',
			ecc_costo_fecha_1 = '%s',
			ecc_costo_fecha_2 = '%s',
			ecc_costo_fecha_3 = '%s',
			ecc_costo_sitio = '%s',
			ecc_costo_otro = '%s',
			ecc_fechaModifacion = NOW(),
			ecc_usuarioModifico = %d
			WHERE ecc_clave = '%s' 
			AND ecc_idEvento = %d 
			LIMIT 1";

		$qry = sprintf($qry, $clave, $costoFecha1, $costoFecha2, $costoFecha3, $costoSitio, $costoOtro, $idUsuario, $claveAnt, $id);

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
	 * Metodo que elimina una categoria
	 * 
	 * 
	 */
	public function eliminarCategoria($clave, $idEvento)
	{
		$clave = $this->db->hEscapeString($clave);
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "DELETE FROM smc_eventoCategorias 
			WHERE ecc_clave = '%s' 
			AND ecc_idEvento = %d 
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
	 * Metodo que actualiza las claves en los nombres de las categoria
	 * 
	 * 
	 */
	public function actualizarClavesCategoria($nuevaClave, $clave, $idEvento)
	{
		$nuevaClave = $this->db->hEscapeString($nuevaClave);
		$clave = $this->db->hEscapeString($clave);
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "UPDATE smc_eventoCategoriasNombres SET 
			enc_clave = '%s'
			WHERE enc_idEvento = %d 
			AND enc_clave = '%s'
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
	 * Metodo que elimina los nombres de la categoria
	 * 
	 * 
	 */
	public function eliminarNombresCategoria($clave, $idEvento, $idNombreCat = 0)
	{
		$clave = $this->db->hEscapeString($clave);
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "DELETE FROM smc_eventoCategoriasNombres 
			WHERE enc_clave = '%s' 
			AND enc_idEvento = %d
		";

		if ($idNombreCat)
		{
			$idNombreCat = $this->db->hEscapeString($idNombreCat);
			$qry .= " AND enc_idNombreCategoria = " . $idNombreCat . " LIMIT 1";
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
	public function validarClaveCategoria($clave, $idEvento, $claveAnt = "")
	{
		$clave = $this->db->hEscapeString($clave);
		$id = $this->db->hEscapeString($idEvento);

		$qry = "SELECT ecc_idEvento 
			FROM smc_eventoCategorias 
			WHERE (ecc_clave = '%s' 
			AND ecc_idEvento = %d)";

		if ($claveAnt != "")
		{
			$claveAnt = $this->db->hEscapeString($claveAnt);
			$qry .= " AND (ecc_clave <> '" . $claveAnt . "')";
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
	public function getNombresCategorias($clave, $idEvento)
	{
		$nombres = array();

		$clave = $this->db->hEscapeString($clave);
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "SELECT enc_idNombreCategoria, enc_clave, enc_idioma, enc_nombre 
			FROM smc_eventoCategoriasNombres 
			WHERE enc_idEvento = %d 
			AND enc_clave = '%s'
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
	public function getIdiomasNombresCategoria($clave, $idEvento)
	{
		$idiomas = array();

		$clave = $this->db->hEscapeString($clave);
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "SELECT enc_idioma 
			FROM smc_eventoCategoriasNombres 
			WHERE enc_clave = '%s' 
			AND enc_idEvento = %d 
		";

		$qry = sprintf($qry, $clave, $idEvento);

		if ($this->db->hQuery($qry))
		{
			while ($fila = $this->db->hFetchObject()) {
				$idiomas[$fila->enc_idioma] = true;
			}
		}

		return $idiomas;
	}

	/**
	 *  
	 * Metodo que verifica si ya existe una categoria con el nombre en español o ingles indicado
	 * 
	 * 
	 */
	public function validarNombreCategoria($nombre, $idEvento, $nombreAnt = "")
	{
		$nombre = $this->db->hEscapeString($nombre);
		$id = $this->db->hEscapeString($idEvento);

			$qry = "SELECT enc_idEvento 
				FROM smc_eventoCategoriasNombres 
				WHERE enc_nombre = '%s' 
				AND enc_idEvento = %d
			";

			if ($nombreAnt != "")
			{
				$nombreAnt = $this->db->hEscapeString($nombreAnt);
				$qry .= " AND enc_nombre <> '" . $nombreAnt . "'";
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

	public function insertarNombreCategoria($post, $idEvento)
	{
		$idEvento = $this->db->hEscapeString($idEvento);
		$idNombre = $this->db->hEscapeString($post['enc_idNombreCategoria']);
		$clave = $this->db->hEscapeString($post['enc_clave']);
		$idioma = $this->db->hEscapeString($post['enc_idioma']);
		$nombre = $this->db->hEscapeString($post['enc_nombre']);

		$qry = "INSERT INTO smc_eventoCategoriasNombres SET 
			enc_idNombreCategoria = %d,
			enc_idEvento = %d,
			enc_clave = '%s',
			enc_idioma = '%s',
			enc_nombre = '%s'
		";

		$qry = sprintf($qry, $idNombre, $idEvento, $clave, $idioma, $nombre);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}
		}

		return false;
	}

	public function validarClaveIdioma($claveIdioma, $idEvento)
	{

		$claveIdioma = $this->db->hEscapeString($claveIdioma);
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "SELECT eis_idEvento 
			FROM smc_eventoIdiomas 
			WHERE eis_idioma = '%s' 
			AND eis_idEvento = %d
		";

		$qry = sprintf($qry, $claveIdioma, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				return true;
			}
		}

		return false;
	}

	public function agregarIdioma($post, $idEvento)
	{

		$claveIdioma = $this->db->hEscapeString($post['eis_idioma']);
		$nombre = $this->db->hEscapeString($post['eis_nombre']);
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "INSERT INTO smc_eventoIdiomas SET 
			eis_idEvento = %d,
			eis_idioma = '%s',
			eis_nombre = '%s'
		";

		$qry = sprintf($qry, $idEvento, $claveIdioma, $nombre);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}
		}

		return false;
	}

	public function getIdiomas($idEvento)
	{
		$idiomas = array();

		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "SELECT eis_idEvento, eis_idioma, eis_nombre 
			FROM smc_eventoIdiomas 
			WHERE eis_idEvento = %d
		";

		$qry = sprintf($qry, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($idioma = $this->db->hFetchObject())
				{
					$idiomas[] = $idioma;
				}
				
			}
		}

		return $idiomas;
	}

	public function eliminarIdioma($clave, $idEvento)
	{
		$clave = $this->db->hEscapeString($clave);
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "DELETE FROM smc_eventoIdiomas 
			WHERE eis_idioma = '%s' 
			AND eis_idEvento = '%s' 
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
}