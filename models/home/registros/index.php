<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/models/home/registros/
 * @version $Id: index.php 1.0 2012-05-10 00:05 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Model\Model_Controller as Model;

class Index_Model extends Model
{

	public function getEvento($idEvento)
	{
		$evento = array();

		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "SELECT evt_idEvento, evt_nombre, evt_clave, evt_inicio, evt_termina, evt_iva, evt_divisa,
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

	public function getDatosCP($cp)
	{
		$cps = array();

		$cp = $this->db->hEscapeString($cp);

		$qry = "SELECT cp_asenta, cp_nombreMnpio, cp_nombreEstado, cp_nombreCiudad 
			FROM smc_catCodigosPostales 
			WHERE cp_codigoPostal = '%s' 
			ORDER BY cp_asenta ASC
		";

		$qry = sprintf($qry, $cp);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($cp = $this->db->hFetchObject())
				{
					$cps[] = $cp;
				}
				$this->db->hFree();
			}
		}

		return $cps;
	}

	public function buscarTag($tabla, $tag, $idRegistro)
	{
		$tag = $this->db->hEscapeString($tag);

		$qry = "SELECT id_registro 
			FROM smc_reg_%s 
			WHERE id_tag = '%s' 
			AND id_registro <> %d
		";

		$qry = sprintf($qry, $tabla, $tag, $idRegistro);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				return true;
			}
		}

		return false;
	}

	public function buscarTagOtro($tabla, $tag, $idRegistro)
	{
		$tag = $this->db->hEscapeString($tag);

		$qry = "SELECT id_registro 
			FROM smc_reg_%s 
			WHERE id_tag = '%s' 
		";

		$qry = sprintf($qry, $tabla, $tag);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				return true;
			}
		}

		return false;
	}

	public function setRegistro(array $post, $idUsuario)
	{
		$qry = "INSERT INTO %s SET 
			`folio_registro` = %d ,
			`folio_pago` = '%s' ,
			`clave_evento` = '%s' ,
			`clave_registro` = '%s' ,
			`cat_registro` = '%s' ,
			`idioma` = '%s' ,
			`clave_asociada` = '%s' ,
			`id_costo` = %d ,
			`costo_registro` = '%s' ,
			`costo_adicional` = '0.00' ,
			`costo_total` = '%s' ,
			`divisa` = '%s' ,
			`tipo_cambio_divisa` = '%s' ,

			`id_tag` = '%s',
			`genero` = '%s' ,
			`titulo` = '%s' ,
			`nombre` = UPPER('%s') ,
			`app` = UPPER('%s') ,
			`apm` = UPPER('%s') ,
			`curp` = UPPER('%s') ,
			`rfc` = UPPER('%s') ,
			`pais` = %d ,

			`emp_o_ins` = UPPER('%s') ,
			`cargo` = '%s' ,

			`comentarios` = '%s' ,
			`email` = '%s' ,

			`politicas_terminos_condiciones` = 1 ,
			`forma_pago` = '%s' ,
			`fecha_registro` = '%s' ,
			`status` = '%s' ,
			`realizado_en` = 'STO' , 
			`idUsuario_alta` = %d";

		$qry = sprintf($qry,
			$post['tabla_registros'],
			$post['folio_registro'],
			$post['folio_pago'],
			$post['clave_evento'],
			$post['clave_registro'],
			$post['cat_registro'],
			'es',
			$post['clave_asociada'],
			$post['id_costo'],
			$post['costo_registro'],
			$post['costo_registro'],
			$post['divisa'],
			$post['tipo_cambio_divisa'],

			$post['id_tag'],
			$post['genero'],
			$post['titulo'],
			$post['nombre'],
			$post['app'],
			$post['apm'],
			$post['curp'],
			$post['rfc'],
			$post['pais'],
			$post['emp_o_ins'],
			$post['cargo'],

			$post['comentarios'],
			$post['email'],

			$post['forma_pago'],
			$post['fecha_registro'],
			$post['status'],
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

	public function setTagRFID($tagRFID, $idUsuario, $tabla_registros)
	{
		$qry = "UPDATE %s SET 
			`id_tag` = '%s' 
			WHERE id_registro = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $tabla_registros, $tagRFID, $idUsuario);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return $this->db->hInsertID();
			}
		}

		return false;
	}

	public function guardarDatosContacto(array $post, $idRegistro)
	{

		$qry = "UPDATE %s SET 
			`pais` = %d ,
			`cp` = UPPER('%s') ,
			`estado` = UPPER('%s') ,
			`del_o_mun` = UPPER('%s') ,
			`colonia` = UPPER('%s') ,
			`ciudad` = UPPER('%s') ,
			`direccion` = UPPER('%s') ,
			`lada_telefono` = '%s' ,
			`telefono_particular` = '%s' ,
			`telefono_movil` = '%s' 
			WHERE id_registro = %d 
			LIMIT 1
		";

		$qry = sprintf($qry,
			$post['tabla_registros'],
			$post['pais'],
			$post['cp'],
			$post['estado'],
			$post['del_o_mun'],
			$post['colonia'],
			$post['ciudad'],
			$post['direccion'],
			$post['lada_telefono'],
			$post['telefono_particular'],
			$post['telefono_movil'],
			$idRegistro
		);

		if ($this->db->hQuery($qry))
		{
			//if ($this->db->hAffectedRows() == 1)
			//{
				return true;
			//}
		}

		return false;
	}

	public function guardarAcompanantes(array $post, $idRegistro, $idUsuario)
	{
		$status = false;

		$evento = $this->db->hEscapeString($post['clave_evento']);
		$idRegistro = $this->db->hEscapeString($idRegistro);
		$idUsuario = $this->db->hEscapeString($idUsuario);

		$totalAcom = $post['num_acm_act'];

		unset($post['clave_evento']);
		unset($post['num_acm_act']);

		$acoms = array();
		for($i = 1, $j = 1 ; $i < $totalAcom ; $i++, $j++)
		{
			// Clave
			if (isset($post["acm_clave_" . $i]))
			{
				$acoms[$j]['acm_clave'] = $post["acm_clave_" . $i];
			}
			else
			{
				continue;
			}
			// ID costo
			if (isset($post["acm_id_costo_" . $i]))
			{
				$acoms[$j]['acm_id_costo'] = $post["acm_id_costo_" . $i];
			}
			// Costo
			if (isset($post["acm_costo_" . $i]))
			{
				$acoms[$j]['acm_costo'] = $post["acm_costo_" . $i];
			}
			// Genero
			if (isset($post["acm_genero_" . $i]))
			{
				$acoms[$j]['acm_genero'] = $post["acm_genero_" . $i];
			}
			// Nombre
			if (isset($post["acm_nombre_" . $i]))
			{
				$acoms[$j]['acm_nombre'] = $post["acm_nombre_" . $i];
			}
			// App
			if (isset($post["acm_app_" . $i]))
			{
				$acoms[$j]['acm_app'] = $post["acm_app_" . $i];
			}
			// Apm
			if (isset($post["acm_apm_" . $i]))
			{
				$acoms[$j]['acm_apm'] = $post["acm_apm_" . $i];
			}
			// Titulo
			if (isset($post["acm_titulo_" . $i]))
			{
				$acoms[$j]['acm_titulo'] = $post["acm_titulo_" . $i];
			}
		}

		$i = 1;
		foreach ($acoms as $key => $value)
		{
			$qry = "INSERT INTO smc_reg_%s_acom SET 
				acm_idRegistro = %d,
				acm_idInterno = %d,
				acm_clave = '%s',
				acm_genero = '%s',
				acm_titulo = '%s',
				acm_nombre = UPPER('%s'),
				acm_app = UPPER('%s'),
				acm_apm = UPPER('%s'),
				acm_id_costo = %d,
				acm_costo = '%s',
				acm_fechaModificacion = NOW(),
				acm_usuarioModifico = %d
			";

			$qry = sprintf($qry,
				$evento,
				$idRegistro,
				$i,
				$value['acm_clave'],
				$value['acm_genero'],
				$value['acm_titulo'],
				$value['acm_nombre'],
				$value['acm_app'],
				$value['acm_apm'],
				$value['acm_id_costo'],
				$value['acm_costo'],
				$idUsuario
			);

			if ($this->db->hQuery($qry))
			{
				$status = true;
			}
			else
			{
				$status = false;
			}

			$i++;
		}

		return $status;
	}

	public function guardarItems(array $post, $idRegistro, $idUsuario)
	{
		$status = false;

		$evento = $this->db->hEscapeString($post['clave_evento']);
		$idRegistro = $this->db->hEscapeString($idRegistro);
		$idUsuario = $this->db->hEscapeString($idUsuario);

		$totalItems = $post['num_item_act'];

		unset($post['clave_evento']);
		unset($post['num_item_act']);

		$items = array();
		for($i = 1, $j = 1 ; $i < $totalItems ; $i++, $j++)
		{
			// Clave
			if (isset($post["item_clave_" . $i]))
			{
				$items[$j]['item_clave'] = $post["item_clave_" . $i];
			}
			else
			{
				continue;
			}
			// ID costo
			if (isset($post["item_id_costo_" . $i]))
			{
				$items[$j]['item_id_costo'] = $post["item_id_costo_" . $i];
			}
			// Cantidad de items
			if (isset($post["item_cantidad_" . $i]))
			{
				$items[$j]['item_cantidad'] = $post["item_cantidad_" . $i];
			}
			// Costo unitiario del item
			if (isset($post["item_costo_unitario_" . $i]))
			{
				$items[$j]['item_costo_unitario'] = $post["item_costo_unitario_" . $i];
			}
			// Costo tatal
			if (isset($post["item_costo_total_" . $i]))
			{
				$items[$j]['item_costo_total'] = $post["item_costo_total_" . $i];
			}
		}

		$i = 1;
		foreach ($items as $key => $value)
		{
			$qry = "INSERT INTO smc_reg_%s_items SET 
				item_idRegistro = %d,
				item_idInterno = %d,
				item_clave = '%s',
				item_cantidad = '%s',
				item_id_costo = '%s',
				item_costo_unitario = '%s',
				item_costo_total = '%s',
				item_fechaModificacion = NOW(),
				item_usuarioModifico = %d
			";

			$qry = sprintf($qry,
				$evento,
				$idRegistro,
				$i,
				$value['item_clave'],
				$value['item_cantidad'],
				$value['item_id_costo'],
				$value['item_costo_unitario'],
				$value['item_costo_total'],
				$idUsuario
			);

			if ($this->db->hQuery($qry))
			{
				$status = true;
			}
			else
			{
				$status = false;
			}

			$i++;
		}

		return $status;
	}

	public function guardarDatosFacturacion(array $post, $idRegistro)
	{


		$qry = "UPDATE %s SET 
			`requiere_factura` = 1 ,
			`razon_social_RS` = UPPER('%s') ,
			`rfc_RS` = UPPER('%s') ,
			`pais_RS` = '%s' ,
			`cp_RS` = UPPER('%s') ,
			`estado_RS` = UPPER('%s') ,
			`del_o_mun_RS` = UPPER('%s') ,
			`colonia_RS` = UPPER('%s') ,
			`ciudad_RS` = UPPER('%s') ,
			`direccion_RS` = UPPER('%s') ,
			`lada_telefono_RS` = '%s' ,
			`telefono_RS` = '%s' ,
			`fax_RS` = '%s' ,
			`email_RS` = '%s' 
			WHERE id_registro = %d 
			LIMIT 1
		";

		$qry = sprintf($qry,
			$post['tabla_registros'],
			$post['razon_social_RS'],
			$post['rfc_RS'],
			$post['pais_RS'],
			$post['cp_RS'],
			$post['estado_RS'],
			$post['del_o_mun_RS'],
			$post['colonia_RS'],
			$post['ciudad_RS'],
			$post['direccion_RS'],
			$post['lada_telefono_RS'],
			$post['telefono_RS'],
			$post['fax_RS'],
			$post['email_RS'],
			$idRegistro
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

	public function getRegistro($idRegistro, $clave_evento)
	{
		$registro = array();
		$idRegistro = $this->db->hEscapeString($idRegistro);

		$qry = "SELECT r.*, f.*, p.* 
			FROM smc_reg_%s AS r 
			LEFT JOIN smc_reg_%s_fotos AS f ON (f.foto_idRegistro = r.id_registro) 
			LEFT JOIN smc_catPaises AS p ON (p.pais_idPais = r.pais) 
			WHERE r.id_registro = %d 
			LIMIT 1";

		$qry = sprintf($qry, $clave_evento, $clave_evento, $idRegistro);

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

	public function getRegistroTalleres($idRegistro, $clave_evento)
	{
		$talleres = array();
		$idRegistro = $this->db->hEscapeString($idRegistro);

		$qry = "SELECT i.item_idInterno, i.item_clave, n.eni_nombre 
			FROM smc_reg_%s_items AS i 
			JOIN smc_eventoItemsNombres AS n ON (n.eni_clave = i.item_clave) 
			WHERE i.item_idRegistro = %d
		";

		$qry = sprintf($qry, $clave_evento, $idRegistro);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				$talleres[] = $this->db->hFetchObject();
				$this->db->hFree();
			}
		}

		return $talleres;
	}

	public function getRegistroTaller($idRegistro, $clave_evento, $taller)
	{
		$registro = array();
		$idRegistro = $this->db->hEscapeString($idRegistro);
		$taller = $this->db->hEscapeString($taller);

		$qry = "SELECT r.id_registro, r.nombre, r.app, r.apm, i.item_idInterno, i.item_clave, n.eni_nombre 
			FROM smc_reg_%s AS r 
			JOIN smc_reg_%s_items AS i ON (i.item_idRegistro = r.id_registro) 
			JOIN smc_eventoItemsNombres AS n ON (n.eni_clave = i.item_clave) 
			WHERE (r.id_registro = %d 
				AND i.item_clave = '%s' 
				AND r.impresion_gafete = 1
			) 
			LIMIT 1
		";

		$qry = sprintf($qry, $clave_evento, $clave_evento, $idRegistro, $taller);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				$registro = $this->db->hFetchObject();
				$this->db->hFree();
				//$this->updateConstanciaTaller($idRegistro, $taller);
			}
		}

		return $registro;
	}

	public function getRegistroSillas($idRegistro, $clave_evento)
	{
		$sillas = array();
		$idRegistro = $this->db->hEscapeString($idRegistro);

		$qry = "SELECT rs.id_registro, rs.id_silla, m.nombre_mesa, s.numero_silla 
			FROM registro_silla AS rs 
			JOIN silla_grids AS s ON (s.id_silla = rs.id_silla) 
			JOIN mesas AS m ON (m.id_mesa = s.id_mesa) 
			WHERE rs.id_registro = %d
		";

		$qry = sprintf($qry, $clave_evento, $idRegistro);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				$sillas[] = $this->db->hFetchObject();
				$this->db->hFree();
			}
		}

		return $sillas;
	}

	public function actualizarRegistro(array $post, $idUsuario)
	{
		$qry = "UPDATE %s SET 
			`id_tag` = '%s',
			`folio_pago` = '%s',
			`cat_registro` = '%s' ,
			`idioma` = '%s' ,
			`clave_asociada` = '%s' ,
			`id_costo` = %d ,
			`costo_registro` = '%s' ,
			`costo_total` = '%s' ,
			`divisa` = '%s' ,

			`genero` = '%s' ,
			`titulo` = '%s' ,
			`nombre` = UPPER('%s') ,
			`app` = UPPER('%s') ,
			`apm` = UPPER('%s') ,
			`curp` = UPPER('%s') ,
			`rfc` = UPPER('%s') ,
			`emp_o_ins` = UPPER('%s') ,
			`cargo` = UPPER('%s') ,
			`email` = '%s' ,
			`pais` = %d ,

			`forma_pago` = '%s' ,
			`status` = '%s' ,
			`comentarios` = UPPER('%s') ,
			`numero_factura` = '%s' ,
			`fecha_modificacion` = NOW(),
			`idUsuario_modifico` = %d 
			WHERE `id_registro` = %d 
			LIMIT 1
		";

		$qry = sprintf($qry,
			$post['tabla_registros'],
			$post['id_tag'],
			$post['folio_pago'],
			$post['cat_registro'],
			$post['idioma'],
			$post['clave_asociada'],
			$post['id_costo'],
			$post['costo_registro'],
			$post['costo_total'],
			$post['divisa'],

			$post['genero'],
			$post['titulo'],
			$post['nombre'],
			$post['app'],
			$post['apm'],
			$post['curp'],
			$post['rfc'],
			$post['emp_o_ins'],
			$post['cargo'],
			$post['email'],
			$post['pais'],

			$post['forma_pago'],
			$post['status'],
			$post['comentarios'],
			$post['numero_factura'],
			$idUsuario,
			$post['id_registro']
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

	public function actualizarDatosContacto(array $post, $idUsuario)
	{


		$qry = "UPDATE %s SET 
			`pais` = %d ,
			`cp` = UPPER('%s') ,
			`estado` = UPPER('%s') ,
			`del_o_mun` = UPPER('%s') ,
			`colonia` = UPPER('%s') ,
			`ciudad` = UPPER('%s') ,
			`direccion` = UPPER('%s') ,
			`lada_telefono` = '%s' ,
			`telefono_particular` = '%s' ,
			`telefono_movil` = '%s' ,
			`fecha_modificacion` = NOW() ,
			`idUsuario_modifico` = %d 
			WHERE id_registro = %d 
			LIMIT 1
		";

		$qry = sprintf($qry,
			$post['tabla_registros'],
			$post['pais'],
			$post['cp'],
			$post['estado'],
			$post['del_o_mun'],
			$post['colonia'],
			$post['ciudad'],
			$post['direccion'],
			$post['lada_telefono'],
			$post['telefono_particular'],
			$post['telefono_movil'],
			$idUsuario,
			$post['id_registro']
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

	public function actualizarDatosFacturacion(array $post, $idUsuario)
	{


		$qry = "UPDATE %s SET 
			`requiere_factura` = 1 ,
			`razon_social_RS` = UPPER('%s') ,
			`rfc_RS` = UPPER('%s') ,
			`pais_RS` = '%s' ,
			`cp_RS` = UPPER('%s') ,
			`estado_RS` = UPPER('%s') ,
			`del_o_mun_RS` = UPPER('%s') ,
			`colonia_RS` = UPPER('%s') ,
			`ciudad_RS` = UPPER('%s') ,
			`direccion_RS` = UPPER('%s') ,
			`lada_telefono_RS` = UPPER('%s') ,
			`telefono_RS` = UPPER('%s') ,
			`fax_RS` = '%s' ,
			`email_RS` = '%s' ,
			`fecha_modificacion` = NOW() ,
			`idUsuario_modifico` = %d 
			WHERE id_registro = %d 
			LIMIT 1
		";

		$qry = sprintf($qry,
			$post['tabla_registros'],
			$post['razon_social_RS'],
			$post['rfc_RS'],
			$post['pais_RS'],
			$post['cp_RS'],
			$post['estado_RS'],
			$post['del_o_mun_RS'],
			$post['colonia_RS'],
			$post['ciudad_RS'],
			$post['direccion_RS'],
			$post['lada_telefono_RS'],
			$post['telefono_RS'],
			$post['fax_RS'],
			$post['email_RS'],
			$idUsuario,
			$post['id_registro']
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
	 * Metodo que agregar un acompañante
	 */
	public function setAcompanante(array $acom, $idUsuario)
	{
		$qry = "INSERT INTO smc_reg_%s_acom SET 
			acm_idRegistro = %d,
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
			acm_status = '%s'
		";

		$qry = sprintf($qry,
			$acom['tabla'],
			$acom['acm_idRegistro'],
			$acom['acm_clave'],
			$acom['acm_genero'],
			$acom['acm_titulo'],
			$acom['acm_nombre'],
			$acom['acm_app'],
			$acom['acm_apm'],
			$acom['acm_costo']->idCosto,
			$acom['acm_costo']->costo,
			$idUsuario,
			$acom['acm_status']
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

	/**
	 * Metodo que agregar un acompañante
	 */
	public function updateAcompanante(array $acom, $idUsuario)
	{
		$qry = "UPDATE smc_reg_%s_acom SET 
			acm_idRegistro = %d,
			acm_clave = '%s',
			acm_genero = '%s',
			acm_titulo = '%s',
			acm_nombre = UPPER('%s'),
			acm_app = UPPER('%s'),
			acm_apm = UPPER('%s'),
			acm_id_costo = %d,
			acm_costo = '%s',
			acm_fechaModificacion = '%s',
			acm_usuarioModifico = %d,
			acm_status = '%s' 
			WHERE id_acompanante = %d 
			LIMIT 1
		";

		$qry = sprintf($qry,
			$acom['tabla'],
			$acom['acm_idRegistro'],
			$acom['acm_clave'],
			$acom['acm_genero'],
			$acom['acm_titulo'],
			$acom['acm_nombre'],
			$acom['acm_app'],
			$acom['acm_apm'],
			$acom['acm_costo']->idCosto,
			$acom['acm_costo']->costo,
			$acom['acm_fechaModificacion'],
			$idUsuario,
			$acom['acm_status'],
			$acom['id_acompanante']
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
	 * Metodo que elimina el acompañante
	 */
	public function eliminarAcompanante($idAcompanante, $idRegistro, $tabla)
	{
		$idAcompanante = $this->db->hEscapeString($idAcompanante);
		$idRegistro = $this->db->hEscapeString($idRegistro);
		$tabla = $this->db->hEscapeString($tabla);

		$qry = "DELETE FROM smc_reg_%s_acom 
			WHERE id_acompanante = %d 
			AND acm_idRegistro = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $tabla, $idAcompanante, $idRegistro);

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
	 * Metodo que obtiene los acompañantes de un registro
	 */
	public function getAcompanantes($idRegistro, $tabla)
	{
		$acoms = array();
		$idRegistro = $this->db->hEscapeString($idRegistro);
		$tabla = $this->db->hEscapeString($tabla);

		$qry = "SELECT id_acompanante, acm_idRegistro, acm_idInterno, acm_clave, acm_genero, acm_titulo, 
			acm_nombre, acm_app, acm_apm, acm_id_costo, acm_comentarios, acm_status 
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
	 * Metodo que obtiene los datos del acompañante de un registro
	 */
	public function getAcompanante($idRegistro, $idAcompanante, $tabla)
	{
		$acom = array();
		$idRegistro = $this->db->hEscapeString($idRegistro);
		$idAcompanante = $this->db->hEscapeString($idAcompanante);
		$tabla = $this->db->hEscapeString($tabla);

		$qry = "SELECT id_acompanante, acm_idRegistro, acm_idInterno, acm_clave, acm_genero, acm_titulo, 
			acm_nombre, acm_app, acm_apm, acm_id_costo, acm_comentarios, acm_status, acm_impresion_gafete, 
			acm_fecha_impresion_gafete, acm_impresion_total_gafete 
			FROM smc_reg_%s_acom 
			WHERE acm_idRegistro = %d 
			AND id_acompanante = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $tabla, $idRegistro, $idAcompanante);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				$acom = $this->db->hFetchObject();

				$this->db->hFree();
			}
		}

		return $acom;
	}

	/**
	 * Metodo que agregar un item
	 */
	public function agregarItem(array $post, $idRegistro, $idUsuario)
	{
		$item = array();

		$evento = $this->db->hEscapeString($post['clave_evento']);
		$idRegistro = $this->db->hEscapeString($idRegistro);
		$idUsuario = $this->db->hEscapeString($idUsuario);
		$idItem = $this->db->hEscapeString($post['num_item']);

		// Clave
		if (isset($post["item_clave_" . $idItem]))
		{
			$item['item_clave'] = $post["item_clave_" . $idItem];
		}
		// Cantidad de items
		if (isset($post["item_cantidad_" . $idItem]))
		{
			$item['item_cantidad'] = $post["item_cantidad_" . $idItem];
		}
		// ID costo
		if (isset($post["item_id_costo_" . $idItem]))
		{
			$item['item_id_costo'] = $post["item_id_costo_" . $idItem];
		}
		// Costo unitiario del item
		if (isset($post["item_costo_unitario_" . $idItem]))
		{
			$item['item_costo_unitario'] = $post["item_costo_unitario_" . $idItem];
		}
		// Costo tatal
		if (isset($post["item_costo_total_" . $idItem]))
		{
			$item['item_costo_total'] = $post["item_costo_total_" . $idItem];
		}
		// Status
		if (isset($post["item_status_" . $idItem]))
		{
			$item['item_status'] = $post["item_status_" . $idItem];
		}

		$qry = "INSERT INTO smc_reg_%s_items SET 
			item_idRegistro = %d,
			item_idInterno = %d,
			item_clave = '%s',
			item_cantidad = '%s',
			item_id_costo = '%s',
			item_costo_unitario = '%s',
			item_costo_total = '%s',
			item_fechaModificacion = NOW(),
			item_usuarioModifico = %d,
			item_status = '%s'
		";

		$qry = sprintf($qry,
			$evento,
			$idRegistro,
			$idItem,
			$item['item_clave'],
			$item['item_cantidad'],
			$item['item_id_costo'],
			$item['item_costo_unitario'],
			$item['item_costo_total'],
			$idUsuario,
			$item['item_status']
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
	 * Metodo que elimina el item
	 */
	public function eliminarItem($idItem, $idRegistro, $tabla)
	{
		$idItem = $this->db->hEscapeString($idItem);
		$idRegistro = $this->db->hEscapeString($idRegistro);
		$tabla = $this->db->hEscapeString($tabla);

		$qry = "DELETE FROM smc_reg_%s_items 
			WHERE item_idInterno = %d 
			AND item_idRegistro = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $tabla, $idItem, $idRegistro);

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
	 * Metodo que obtiene los adicionales de un registro
	 */
	public function getItems($idRegistro, $tabla)
	{
		$items = array();
		$idRegistro = $this->db->hEscapeString($idRegistro);
		$tabla = $this->db->hEscapeString($tabla);

		$qry = "SELECT item_idRegistro, item_idInterno, item_clave, item_cantidad, 
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

	/**
	 * Metodo que actualiza un item
	 */
	public function actualizarItem(array $post, $idRegistro, $idUsuario)
	{
		$item = array();

		$evento = $this->db->hEscapeString($post['clave_evento']);
		$idRegistro = $this->db->hEscapeString($idRegistro);
		$idUsuario = $this->db->hEscapeString($idUsuario);
		$idItem = $this->db->hEscapeString($post['num_item']);

		// Clave
		if (isset($post["item_clave_" . $idItem]))
		{
			$item['item_clave'] = $post["item_clave_" . $idItem];
		}
		// Cantidad de items
		if (isset($post["item_cantidad_" . $idItem]))
		{
			$item['item_cantidad'] = $post["item_cantidad_" . $idItem];
		}
		// ID costo
		if (isset($post["item_id_costo_" . $idItem]))
		{
			$item['item_id_costo'] = $post["item_id_costo_" . $idItem];
		}
		// Costo unitiario del item
		if (isset($post["item_costo_unitario_" . $idItem]))
		{
			$item['item_costo_unitario'] = $post["item_costo_unitario_" . $idItem];
		}
		// Costo tatal
		if (isset($post["item_costo_total_" . $idItem]))
		{
			$item['item_costo_total'] = $post["item_costo_total_" . $idItem];
		}
		// Status
		if (isset($post["item_status_" . $idItem]))
		{
			$item['item_status'] = $post["item_status_" . $idItem];
		}

		$qry = "UPDATE smc_reg_%s_items SET 
			item_clave = '%s',
			item_cantidad = '%s',
			item_id_costo = '%s',
			item_costo_unitario = '%s',
			item_costo_total = '%s',
			item_fechaModificacion = NOW(),
			item_usuarioModifico = %d,
			item_status = '%s'
			WHERE item_idInterno = %d 
			AND item_idRegistro = %d 
			LIMIT 1
		";

		$qry = sprintf($qry,
			$evento,
			$item['item_clave'],
			$item['item_cantidad'],
			$item['item_id_costo'],
			$item['item_costo_unitario'],
			$item['item_costo_total'],
			$idUsuario,
			$item['item_status'],
			$idItem,
			$idRegistro
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
	 * Metodo que obtiene el ID mas alto de los acompañantes
	 */
	public function getMaxIdAcom($idRegistro, $tabla)
	{
		$qry = "SELECT MAX(id_acompanante) AS idAcom 
			FROM smc_reg_%s_acom 
			WHERE acm_idRegistro = %d
		";

		$qry = sprintf($qry, $tabla, $idRegistro);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				$max = $this->db->hFetchObject();
				if ($max->idAcom)
				{
					return $max->idAcom + 1;
				}

				$this->db->hFree();
			}
		}

		return 1;
	}

	/**
	 * Metodo que obtiene el ID mas alto de los items
	 */
	public function getMaxIdItem($idRegistro, $tabla)
	{
		$qry = "SELECT MAX(item_idInterno) AS idItem 
			FROM smc_reg_%s_items 
			WHERE item_idRegistro = %d
		";

		$qry = sprintf($qry, $tabla, $idRegistro);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				$max = $this->db->hFetchObject();
				if ($max->idItem)
				{
					return $max->idItem + 1;
				}

				$this->db->hFree();
			}
		}

		return 1;
	}

	/**
	 * Metodo que valida si tiene permisos de administrador
	 * en la seccion de registros
	 */
	public function validarPasswordAdmin($username, $password)
	{
		$username = $this->db->hEscapeString($username);
		$password = $this->db->hEscapeString($password);

		$qry = "SELECT u.usr_idUsuario 
			FROM smc_usuarios AS u 
			JOIN smc_seccionesAccesoUsuarios AS s ON (s.sau_idUsuario = u.usr_idUsuario) 
			WHERE u.usr_usuario = '%s' 
			AND u.usr_password = MD5('%s') 
			AND s.sau_idSeccion = 4 
			AND s.sau_idNivel = 1 
			LIMIT 1
		";

		$qry = sprintf($qry, $username, $password);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() == 1)
			{
				return true;
			}
		}

		return false;
	}

	public function setFolioConstancia($idRegistro, $folio, $clave)
	{
		$idRegistro = $this->db->hEscapeString($idRegistro);
		$folio = $this->db->hEscapeString($folio);
		$clave = $this->db->hEscapeString($clave);

		$qry = "UPDATE smc_reg_%s SET 
			folio_constancia = '%s' 
			WHERE id_registro = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $clave, $folio, $idRegistro);

		if ($this->db->hQuery($qry))
		{
			return true;
		}

		return false;
	}

	/**
	 * Metodo que guarda la foto para el gafete
	 */
	public function setFotografia($type, $img, $idRegistro, $clave_evento)
	{
		$qry = "INSERT INTO smc_reg_%s_fotos SET 
			foto_idRegistro = %d,
			foto_mime = '%s',
			foto_fotografia = '%s'
		";

		$qry = sprintf($qry, $clave_evento, $idRegistro, $type, $img);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}
		}

		return false;
	}

	public function eliminarFotografia($id, $clave_evento)
	{
		$id = $this->db->hEscapeString($id);

		$qry = "DELETE FROM smc_reg_%s_fotos 
			WHERE foto_idRegistro = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $clave_evento, $id);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}
		}

		return false;
	}

	public function pasarAsistencia($id_registro, $tabla, $id_usuario)
	{
		$id_registro = $this->db->hEscapeString($id_registro);

		$qry = "UPDATE smc_reg_%s SET 
			impresion_gafete = 1,
			idUsuario_modifico = %d 
			WHERE id_registro = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $tabla, $id_usuario, $id_registro);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}
		}

		return false;
	}

	public function quitarAsistencia($id_registro, $tabla, $id_usuario)
	{
		$id_registro = $this->db->hEscapeString($id_registro);

		$qry = "UPDATE smc_reg_%s SET 
			impresion_gafete = 0,
			idUsuario_modifico = %d 
			WHERE id_registro = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $tabla, $id_usuario, $id_registro);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}
		}

		return false;
	}

	public function agregarReservacion($post, $tabla, $id_registro, $id_usuario)
	{
		$qry = "INSERT INTO smc_reg_%s_reservaciones SET 
			res_idRegistro = %d,
			res_idHotel = %d,
			res_idHabitacion = %d,
			res_folio = '%s',
			res_clave = '%s',
			res_llegada = '%s',
			res_salida = '%s',
			res_numHabitaciones = %d,
			res_numAdultos = '%s',
			res_numMenores = '%s',
			res_costoTotal = '%s',
			res_anticipo = '%s',
			res_saldo = '%s',
			res_formaPago = '%s',
			res_fechaAlta = NOW(),
			res_usuarioAlta = %d,
			res_status = '%s'
		";

		$qry = sprintf($qry,
			$tabla,
			$id_registro,
			$post['res_idHotel'],
			$post['res_idHabitacion'],
			$post['res_folio'],
			$post['res_clave'],
			$post['res_llegada'],
			$post['res_salida'],
			$post['res_numHabitaciones'],
			$post['json_habs_adultos'],
			$post['json_habs_menores'],
			$post['res_costoTotal'],
			$post['res_anticipo'],
			$post['res_saldo'],
			$post['res_formaPago'],
			$id_usuario,
			$post['res_status']
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

	public function actualizarReservacion($post, $tabla, $id_registro, $id_usuario)
	{
		$qry = "UPDATE smc_reg_%s_reservaciones SET 
			res_idRegistro = %d,
			res_idHotel = %d,
			res_idHabitacion = %d,
			res_llegada = '%s',
			res_salida = '%s',
			res_numHabitaciones = %d,
			res_numAdultos = '%s',
			res_numMenores = '%s',
			res_costoTotal = '%s',
			res_anticipo = '%s',
			res_saldo = '%s',
			res_formaPago = '%s',
			res_fechaModificacion = NOW(),
			res_usuarioModifico = %d,
			res_status = '%s' 
			WHERE id_reservacion = %d 
			LIMIT 1
		";

		$qry = sprintf($qry,
			$tabla,
			$id_registro,
			$post['res_idHotel'],
			$post['res_idHabitacion'],
			$post['res_llegada'],
			$post['res_salida'],
			$post['res_numHabitaciones'],
			$post['json_habs_adultos'],
			$post['json_habs_menores'],
			$post['res_costoTotal'],
			$post['res_anticipo'],
			$post['res_saldo'],
			$post['res_formaPago'],
			$id_usuario,
			$post['res_status'],
			$post['id_reservacion']
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

	public function setLectura($id_registro, $tabla_registros, $post)
	{
		$qry = "UPDATE smc_reg_%s SET 
			`id_tag` = '%s' 
			WHERE id_registro = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $tabla_registros, $post['lectura_pistola'], $id_registro);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}
		}

		return false;
	}

	public function verificarFolioPago($folio_pago, $clave_evento, $id_registro)
	{
		$folio_pago = $this->db->hEscapeString($folio_pago);
		$and = '';

		if ($id_registro <> 0)
		{
			$and = 'AND id_registro <> ' . $id_registro;
		}

		$qry = "SELECT id_registro 
			FROM smc_reg_%s 
			WHERE folio_pago = '%s' 
			%s 
			LIMIT 1
		";

		$qry = sprintf($qry, $clave_evento, $folio_pago, $and);

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
}
?>