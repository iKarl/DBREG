<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/models/eventos/
 * @version $Id: index.php 1.0 2012-03-23 00:08 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Model\Model_Controller as Model;

class Index_Model extends Model
{
	public function getEventos()
	{
		$eventos = array();

		$qry = "SELECT e.evt_idEvento, e.evt_nombre, e.evt_clave, e.evt_idioma, e.evt_inicio, e.evt_termina, 
			e.evt_iva, e.evt_status, t.cte_nombre, s.ecs_nombre 
			FROM smc_eventos AS e 
			JOIN smc_eventoCatalogoStatus AS s ON (s.ecs_idStatus = e.evt_status) 
			JOIN smc_eventoCatalogoTipos AS t ON (t.cte_idTipoEvento = e.evt_idTipoEvento) 
			WHERE 1 
			ORDER BY evt_nombre ASC
			LIMIT 0 , 10
		";

		if ($this->db->hQuery($qry))
		{

			if ($this->db->hNumRows() > 0)
			{

				while ($evento = $this->db->hFetchObject())
				{
					$eventos[] = $evento;
				}

			}

		}

		return $eventos;
	}

	public function getEvento($idEvento)
	{
		$evento = array();

		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "SELECT evt_idEvento, evt_nombre, evt_clave, evt_inicio, evt_termina, evt_iva,
			evt_divisa, evt_tipoCambio, evt_nombreTablaAsistentes 
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

	public function getRegistro($idRegistro, $clave_evento)
	{
		$registro = array();
		$idRegistro = $this->db->hEscapeString($idRegistro);

		$qry = "SELECT r.*, f.*, p.*, c.id_cobro, c.id_registro AS caja_id_registro,
			c.costo_original, c.descuento, c.folio_pago, c.forma_pago AS caja_forma_pago,
			c.comentarios AS caja_comentarios, c.status AS caja_status 
			FROM smc_reg_%s AS r 
			LEFT JOIN smc_reg_%s_fotos AS f ON (f.foto_idRegistro = r.id_registro) 
			LEFT JOIN smc_reg_%s_caja AS c ON (c.id_registro = r.id_registro) 
			LEFT JOIN smc_catPaises AS p ON (p.pais_idPais = r.pais) 
			WHERE r.id_registro = %d 
			LIMIT 1";

		$qry = sprintf($qry, $clave_evento, $clave_evento, $clave_evento, $idRegistro);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() == 1)
			{
				$registro = $this->db->hFetchObject();
				$this->db->hFree();
			}
		}

		return $registro;
	}

	/**
	 * Metodo que obtiene los acompañantes de un registro
	 */
	public function getAcompanantes($idRegistro, $tabla)
	{
		$acoms = array();
		$idRegistro = $this->db->hEscapeString($idRegistro);
		$tabla = $this->db->hEscapeString($tabla);

		$qry = "SELECT id_acompanante, acm_idRegistro, acm_idInterno, acm_clave, acm_genero, acm_titulo, 
			acm_nombre, acm_app, acm_apm, acm_id_costo, acm_costo, acm_comentarios, acm_status 
			FROM smc_reg_%s_acom 
			WHERE acm_idRegistro = %d
		";

		$qry = sprintf($qry, $tabla, $idRegistro);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($acom = $this->db->hFetchObject())
				{
					$acoms[] = $acom;
				}

				$this->db->hFree();
			}
		}

		return $acoms;
	}

	/**
	 * Metodo que obtiene los adicionales de un registro
	 */
	public function getItems($idRegistro, $tabla)
	{
		$items = array();
		$idRegistro = $this->db->hEscapeString($idRegistro);
		$tabla = $this->db->hEscapeString($tabla);

		$qry = "SELECT id_item, item_idRegistro, item_idInterno, item_clave, item_cantidad, 
			item_id_costo, item_costo_unitario, item_costo_total, item_comentarios, item_status 
			FROM smc_reg_%s_items 
			WHERE item_idRegistro = %d
		";

		$qry = sprintf($qry, $tabla, $idRegistro);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($item = $this->db->hFetchObject())
				{
					$items[] = $item;
				}

				$this->db->hFree();
			}
		}

		return $items;
	}

	public function getReservacion($id_registro, $tabla)
	{
		$reservacion = array();

		$qry = "SELECT * 
			FROM smc_reg_%s_reservaciones 
			WHERE res_idRegistro = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $tabla, $id_registro);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() == 1)
			{
				$reservacion = $this->db->hFetchObject();
				$this->db->hFree();
			}
		}

		return $reservacion;
	}

	/**
	 * Metodo que agregar un acompañante
	 */
	public function agregarAcompanante(array $acom, $idUsuario)
	{
		$qry = "INSERT INTO smc_reg_%s_acom SET 
			acm_idRegistro = %d,
			id_registro = %d,
			acm_clave = '%s',
			acm_genero = '%s',
			acm_titulo = '%s',
			acm_nombre = UPPER('%s'),
			acm_app = UPPER('%s'),
			acm_apm = UPPER('%s'),
			acm_id_costo = %d,
			acm_costo = '%s',
			acm_fechaModificacion = NOW(),
			acm_usuarioModifico = %d,
			acm_status = 'PAG'
		";

		$qry = sprintf($qry,
			$acom['tabla'],
			$acom['acm_idRegistro'],
			$acom['acm_idRegistro'],
			$acom['acm_clave'],
			$acom['acm_genero'],
			$acom['acm_titulo'],
			$acom['acm_nombre'],
			$acom['acm_app'],
			$acom['acm_apm'],
			$acom['acm_costo']->idCosto,
			$acom['acm_costo']->costo,
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

	public function delAcompanante($id_acompanante, $id_registro, $tabla)
	{
		$qry = "DELETE FROM smc_reg_%s_acom 
			WHERE id_acompanante = %d 
			AND acm_idRegistro = %d 
			LIMIT 1";

		$qry = sprintf($qry, $tabla, $id_acompanante, $id_registro);

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
	 * Metodo que agregar un item
	 */
	public function agregarAdicional(array $item, $idUsuario)
	{
		$qry = "INSERT INTO smc_reg_%s_items SET 
			item_idRegistro = %d,
			item_clave = '%s',
			item_cantidad = '%s',
			item_id_costo = '%s',
			item_costo_unitario = '%s',
			item_costo_total = '%s',
			item_fechaModificacion = NOW(),
			item_usuarioModifico = %d,
			item_status = 'PAG'
		";

		$qry = sprintf($qry,
			$item['tabla'],
			$item['item_idRegistro'],
			$item['item_clave'],
			$item['item_cantidad'],
			$item['item_id_costo'],
			$item['item_costo']->costo,
			$item['item_costo_total'],
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

	public function delAdicional($id_item, $id_registro, $tabla)
	{
		$qry = "DELETE FROM smc_reg_%s_items 
			WHERE id_item = %d 
			AND item_idRegistro = %d 
			LIMIT 1";

		$qry = sprintf($qry, $tabla, $id_item, $id_registro);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}
		}

		return false;
	}

	public function pagar($registro, $tabla, $id_usuario)
	{
		$qry = "INSERT INTO smc_reg_%s_caja SET 
			id_usuario = %d,
			id_registro = %d,
			forma_pago = '%s',
			costo_original = '%s',
			descuento = '%s',
			costo_total = '%s',
			folio_pago = '%s',
			comentarios = '%s',
			fecha_cobro = '%s',
			status = '%s'
		";

		$qry = sprintf($qry,
			$tabla,
			$id_usuario,
			$registro['id_registro'],
			$registro['caja_forma_pago'],
			$registro['caja_costo_total'],
			$registro['caja_costo_descuento'],
			$registro['costo_total'],
			$registro['caja_folio_pago'],
			$registro['comentarios'],
			$registro['fecha_cobro'],
			$registro['caja_status_pago']
		);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return $this->actualizarRegistro($registro, $tabla, $id_usuario);
			}
		}

		return false;
	}

	public function actualizarPago($registro, $tabla, $id_usuario)
	{
		$qry = "UPDATE smc_reg_%s_caja SET 
			forma_pago = '%s',
			costo_original = '%s',
			descuento = '%s',
			costo_total = '%s',
			folio_pago = '%s',
			comentarios = '%s',
			fecha_cobro = '%s',
			status = '%s' 
			WHERE id_cobro = %d
			LIMIT 1
		";

		$qry = sprintf($qry,
			$tabla,
			$registro['caja_forma_pago'],
			$registro['caja_costo_total'],
			$registro['caja_costo_descuento'],
			$registro['costo_total'],
			$registro['caja_folio_pago'],
			$registro['comentarios'],
			$registro['fecha_cobro'],
			$registro['caja_status_pago'],
			$registro['id_cobro']
		);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return $this->actualizarRegistro($registro, $tabla, $id_usuario);
			}
		}

		return false;
	}

	private function actualizarRegistro($registro, $tabla, $id_usuario)
	{
		$qry = "UPDATE smc_reg_%s SET 
			costo_total = '%s',
			comentarios = '%s',
			forma_pago = '%s',
			revision_factura = %d,
			status = '%s',

			`requiere_factura` = %d ,
			`razon_social_RS` = UPPER('%s') ,
			`rfc_RS` = UPPER('%s') ,
			`pais_RS` = '%s' ,
			`pais_RS_nombre` = '%s' ,
			`cp_RS` = UPPER('%s') ,
			`estado_RS` = UPPER('%s') ,
			`del_o_mun_RS` = UPPER('%s') ,
			`colonia_RS` = UPPER('%s') ,
			`ciudad_RS` = UPPER('%s') ,
			`direccion_RS` = UPPER('%s') ,
			`lada_telefono_RS` = '%s' ,
			`telefono_RS` = '%s' ,
			`fax_RS` = '%s' ,
			`email_RS` = '%s' ,

			fecha_modificacion = '%s',
			idUsuario_modifico = %d 
			WHERE id_registro = %d 
			LIMIT 1
		";

		$qry = sprintf($qry,
			$tabla,
			$registro['costo_total'],
			$registro['comentarios'],
			$registro['caja_forma_pago'],
			$registro['revision_factura'],
			$registro['caja_status_pago'],

			$registro['requiere_factura'],
			$registro['razon_social_RS'],
			$registro['rfc_RS'],
			$registro['pais_RS'],
			$registro['pais_RS_nombre'],
			$registro['cp_RS'],
			$registro['estado_RS'],
			$registro['del_o_mun_RS'],
			$registro['colonia_RS'],
			$registro['ciudad_RS'],
			$registro['direccion_RS'],
			$registro['lada_telefono_RS'],
			$registro['telefono_RS'],
			$registro['fax_RS'],
			$registro['email_RS'],

			$registro['fecha_cobro'],
			$id_usuario,
			$registro['id_registro']
		);

		if ($this->db->hQuery($qry))
		{
			return true;
		}

		return false;
	}

	public function setRegistro(array $post, $idUsuario)
	{
		$qry = "INSERT INTO %s SET 
			`cat_registro` = '%s' ,
			`id_costo` = %d ,
			`costo_registro` = '%s' ,
			`costo_adicional` = '0.00' ,
			`costo_total` = '%s' ,
			`divisa` = 'MXN' ,

			`titulo` = '%s' ,
			`nombre` = UPPER('%s') ,
			`app` = UPPER('%s') ,
			`apm` = UPPER('%s') ,

			`politicas_terminos_condiciones` = 1 ,
			`forma_pago` = '%s' ,
			`fecha_registro` = '%s' ,
			`status` = 'PAG' ,
			`realizado_en` = 'CAJ' , 
			`idUsuario_alta` = %d";

		$qry = sprintf($qry,
			$post['tabla_registros'],
			$post['cat_registro'],
			$post['id_costo'],
			$post['costo_registro'],
			$post['costo_registro'],

			$post['titulo'],
			$post['nombre'],
			$post['app'],
			$post['apm'],

			$post['forma_pago'],
			$post['fecha_registro'],
			$idUsuario
		);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return $this->db->hInsertID();
			}
		}

		return false;
	}
}
?>