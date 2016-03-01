<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/models/eventos/evento/reportes/
 * @version $Id: index.php 1.0 2012-08-30 21:34 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Model\Model_Controller as Model;

class Reportes_Model extends Model
{
	public function concentrado($clave, $idioma)
	{
		$data = array();

		$qry = "SELECT r.cat_registro AS stack, c.enc_nombre as nombre, COUNT( cat_registro ) AS value 
			FROM smc_reg_%s AS r 
			LEFT JOIN smc_eventoCategoriasNombres AS c ON (c.enc_clave = r.cat_registro) 
			WHERE c.enc_idioma = '%s' 
			AND r.status <> 'CAN' 
			GROUP BY r.cat_registro
		";

		$qry = sprintf($qry, $clave, $idioma);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($row = $this->db->hFetchAssoc())
				{
					$data[] = $row;
				}

				$this->db->hFree();
			}
		}

		return $data;
	}

	public function getCategorias($idEvento)
	{
		$categorias = array();

		$qry = "SELECT ecc_clave 
			FROM smc_eventoCategorias 
			WHERE ecc_idEvento = %d
		";

		$qry = sprintf($qry, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($categoria = $this->db->hFetchAssoc())
				{
					$categorias[] = $categoria['ecc_clave'];
				}
				$this->db->hFree();
			}
		}

		return $categorias;
	}

	public function getAsistencias($clave, $idioma)
	{
		$data = array();

		$qry = "SELECT r.cat_registro AS stack, c.enc_nombre as nombre, COUNT( cat_registro ) AS value 
			FROM smc_reg_%s AS r 
			LEFT JOIN smc_eventoCategoriasNombres AS c ON (c.enc_clave = r.cat_registro) 
			WHERE r.impresion_gafete = 1 
			AND c.enc_idioma = '%s' 
			GROUP BY r.cat_registro
		";

		$qry = sprintf($qry, $clave, $idioma);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($row = $this->db->hFetchAssoc())
				{
					$data[] = $row;
				}

				$this->db->hFree();
			}
		}

		return $data;
	}

	public function getAsistenciasPais($clave)
	{
		$data = array();

		$qry = "SELECT r.pais AS stack, p.pais_nombreEs AS nombre, COUNT( pais ) AS value 
			FROM smc_reg_%s AS r 
			LEFT JOIN smc_catPaises AS p ON (p.pais_idPais = r.pais) 
			WHERE 1 
			AND r.pais <> 0 
			GROUP BY r.pais
		";

		$qry = sprintf($qry, $clave);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($row = $this->db->hFetchAssoc())
				{
					$data[] = $row;
				}

				$this->db->hFree();
			}
		}

		return $data;
	}

	public function getAsistenciasPaisLlegados($clave)
	{
		$data = array();

		$qry = "SELECT r.pais AS stack, p.pais_nombreEs AS nombre, COUNT( pais ) AS value 
			FROM smc_reg_%s AS r 
			LEFT JOIN smc_catPaises AS p ON (p.pais_idPais = r.pais) 
			WHERE r.impresion_gafete = 1 
			AND r.pais <> 0 
			GROUP BY r.pais
		";

		$qry = sprintf($qry, $clave);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($row = $this->db->hFetchAssoc())
				{
					$data[] = $row;
				}

				$this->db->hFree();
			}
		}

		return $data;
	}

	/**
	 * Pasar id de evento
	 */
	public function getAcompanantes($clave, $idioma)
	{
		$data = array();

		$qry = "SELECT a.acm_clave AS stack, n.ecan_nombre as nombre, COUNT( a.acm_clave ) AS value 
			FROM smc_reg_%s_acom AS a 
			LEFT JOIN smc_eventoCategoriasAcomNombres AS n ON (n.ecan_clave = a.acm_clave) 
			WHERE n.ecan_idioma = '%s' 
			GROUP BY a.acm_clave
		";

		$qry = sprintf($qry, $clave, $idioma);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($row = $this->db->hFetchAssoc())
				{
					$data[] = $row;
				}

				$this->db->hFree();
			}
		}

		return $data;
	}

	public function getAcompanantesAsitencia($clave, $idioma)
	{
		$data = array();

		$qry = "SELECT a.acm_clave AS stack, n.ecan_nombre as nombre, COUNT( a.acm_clave ) AS value 
			FROM smc_reg_%s_acom AS a 
			LEFT JOIN smc_eventoCategoriasAcomNombres AS n ON (n.ecan_clave = a.acm_clave) 
			WHERE a.acm_impresion_gafete = 1 
			AND n.ecan_idioma = '%s' 
			GROUP BY a.acm_clave
		";

		$qry = sprintf($qry, $clave, $idioma);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($row = $this->db->hFetchAssoc())
				{
					$data[] = $row;
				}

				$this->db->hFree();
			}
		}

		return $data;
	}

	public function getAdicionales($clave, $idioma)
	{
		$dataTotal =  array();
		$data = array();

		$qry = "SELECT i.item_clave AS stack, n.eni_nombre AS nombre, SUM( i.item_cantidad ) AS confirmados 
			FROM smc_reg_%s_items AS i 
			LEFT JOIN smc_eventoItemsNombres AS n ON (n.eni_clave = i.item_clave) 
			WHERE n.eni_idioma = '%s' 
			AND (i.item_status = 'PAG' 
			OR i.item_status = 'COR' 
			OR i.item_status = 'CCC' 
			OR i.item_status = 'DEM') 
			GROUP BY i.item_clave
		";

		$qry = sprintf($qry, $clave, $idioma);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($row = $this->db->hFetchObject())
				{
					$data[] = array(
						'stack' => $row->stack,
						'nombre' => $row->nombre,
						'confirmados' => $row->confirmados
					);
				}
				$this->db->hFree();
			}
		}

		$qry = "SELECT SUM( i.item_cantidad ) AS total 
			FROM smc_reg_%s_items AS i 
			LEFT JOIN smc_eventoItemsNombres AS n ON (n.eni_clave = i.item_clave) 
			WHERE n.eni_idioma = '%s' 
			GROUP BY i.item_clave
		";

		$qry = sprintf($qry, $clave, $idioma);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($row = $this->db->hFetchObject())
				{
					$dataTotal[] = $row->total;
				}
				$this->db->hFree();
			}
		}

		foreach ($data as $key => $value) {
			$data[$key] = array_merge($data[$key], array('total' => $dataTotal[$key]));
		}

		return $data;
	}

	public function getStatusPago($clave, $idioma)
	{
		$dataTotal =  array();
		$data = array();

		$qry = "SELECT r.status AS stack, COUNT( r.status ) AS total, s.esr_nombre 
			FROM smc_reg_%s AS r 
			JOIN smc_eventoStatusRegistros AS s ON ( s.esr_clave = r.status ) 
			WHERE 1 
			GROUP BY r.status
		";

		$qry = sprintf($qry, $clave);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($row = $this->db->hFetchObject())
				{
					$data[] = array(
						'stack' => $row->stack,
						'nombre' => $row->esr_nombre,
						'value' => $row->total
					);
				}
				$this->db->hFree();
			}
		}

		/*$qry = "SELECT SUM( i.item_cantidad ) AS total 
			FROM smc_reg_%s_items AS i 
			LEFT JOIN smc_eventoItemsNombres AS n ON (n.eni_clave = i.item_clave) 
			WHERE n.eni_idioma = '%s' 
			GROUP BY i.item_clave
		";

		$qry = sprintf($qry, $clave, $idioma);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($row = $this->db->hFetchObject())
				{
					$dataTotal[] = $row->total;
				}
				$this->db->hFree();
			}
		}

		foreach ($data as $key => $value) {
			$data[$key] = array_merge($data[$key], array('total' => $dataTotal[$key]));
		}*/

		return $data;
	}

	public function getRegistrosFotos($clave, $post)
	{
		$data = array();
		$limit = '';

		if (!empty($post['cat_registro']))
		{
			if (count($post['cat_registro']) > 1)
			{
				foreach($post['cat_registro'] as $k => $categoria)
				{
					$post['cat_registro'][$k] = "'" . $categoria . "'";
				}

				$where = 'WHERE r.cat_registro IN (' . implode(", ", $post['cat_registro']) . ')';
			}
			else
			{
				$cat_registro = "'" . $post['cat_registro'][0] . "'";
				$where = 'WHERE r.cat_registro = ' . $cat_registro;
			}
		}

		if (!empty($post['status']))
		{
			if (count($post['status']) > 1)
			{
				foreach($post['status'] as $k => $forma_pago)
				{
					$post['status'][$k] = "'" . $forma_pago . "'";
				}
				$where .= ' AND r.status IN (' . implode(', ', $post['status']) . ')';
			}
			else
			{
				$status = "'" . $post['status'][0] . "'";
				$where .= ' AND r.status = ' . $status;
			}
		}

		if ($post['inicio'] !== '')
		{
			$inicio = $this->db->hEscapeString($post['inicio']);
			$limit = 'LIMIT ' . $inicio;

			if ($post['fin'] != '' && $post['fin'] > $post['inicio'])
			{
				$fin = $this->db->hEscapeString($post['fin']);
				$limit .= ' , ' . $fin;
			}
			else
			{
				$limit .= ' , 50';
			}
		}

		$qry = "SELECT r.id_registro, r.nombre, r.app, r.apm, f.foto_fotografia, f.foto_mime,
			p.pais_nombreEs AS pais 
			FROM  smc_reg_%s AS r 
			LEFT JOIN smc_reg_%s_fotos AS f ON (f.foto_idRegistro = r.id_registro) 
			LEFT JOIN smc_catPaises AS p ON (p.pais_idPais = r.pais) 
			%s 
			ORDER BY r.app ASC 
			%s
		";

		$qry = sprintf($qry, $clave, $clave, $where, $limit);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($row = $this->db->hFetchObject())
				{
					$data[] = $row;
				}

				$this->db->hFree();
			}
		}

		return $data;
	}

	public function getCajeros()
	{
		$usuarios = array();

		$qry = "SELECT u.usr_idUsuario, u.usr_nombre, u.usr_app,
			u.usr_apm 
			FROM smc_usuarios AS u 
			JOIN smc_seccionesAccesoUsuarios AS a ON (a.sau_idUsuario = u.usr_idUsuario) 
			WHERE a.sau_idSeccion = 5 
			GROUP BY a.sau_idUsuario
		";

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($usuario = $this->db->hFetchObject())
				{
					$usuarios[] = $usuario;
				}

				$this->db->hFree();
			}
		}

		return $usuarios;
	}
}
?>