<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/libs/
 * @version $Id: class.Eventos.php 1.0 2012-05-10 18:06 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com, son_gohan_khan@hotmail.com>
 * 
 * Eventos
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
use SimpleMVC\Model\Model_Controller as Model;

class Eventos extends Model
{
	/**
	 * Metodo que obtiene los tipos de evento
	 * 
	 * @param int $id
	 */
	public function getTiposEvento($id = 0)
	{
		$tiposEvento = array();

		$qry = "SELECT cte_idTipoEvento, cte_clave, cte_nombre 
			FROM smc_eventoCatalogoTipos";

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while($tipoEvento = $this->db->hFetchObject())
				{
					$tiposEvento[] = $tipoEvento;
				}
				$this->db->hFree();
			}
		}

		return $tiposEvento;
	}

	/**
	 * Metodo que obtiene el nombre del tipo de evento
	 * 
	 * @param int $id
	 */
	public function getTipoEvento($id)
	{
		$tipo = "";
		$id = $this->db->hEscapeString($id);

		$qry = "SELECT cte_nombre 
			FROM smc_eventoCatalogoTipos 
			WHERE cte_idTipoEvento = %d 
			LIMIT 1";

		$qry = sprintf($qry, $id);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() == 1)
			{
				$tipo = $this->db->hFetchObject();
				$tipo = $tipo->cte_nombre;
				$this->db->hFree();
			}
		}

		return $tipo;
	}

	/**
	 * Metodo que regresa los status para el evento
	 *
	 * @param int $id
	 */
	public function getStatusEvento($id = 0)
	{
		$status = array();

		$qry = "SELECT ecs_idStatus, ecs_clave, ecs_nombre, ecs_descripcion 
			FROM smc_eventoCatalogoStatus 
			ORDER BY ecs_nombre ASC";

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while($sts = $this->db->hFetchObject())
				{
					$status[] = $sts;
				}
				$this->db->hFree();
			}
		}

		return $status;
	}

	/**
	 * Metodo que regresa el nombre del status del evento
	 *
	 * @param int $idStatus ID del status
	 */
	public function getNombreStatusEvento($idStatus)
	{
		$status = "";
		$idStatus = $this->db->hEscapeString($idStatus);

		$qry = "SELECT ecs_nombre 
			FROM smc_eventoCatalogoStatus 
			WHERE ecs_idStatus = %d 
			LIMIT 1";

		$qry = sprintf($qry, $idStatus);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() == 1)
			{
				$status = $this->db->hFetchObject();
				$status = $status->ecs_nombre;
				$this->db->hFree();
			}
		}

		return $status;
	}

	/**
	 *  OLD revisar
	 * Metodo que lista los idiomas del evento
	 * 
	 * @param str $claveIdioma ID del idioma para determinar el idioma actual
	 * @param array $disabled Array con las opciones a deshabilitar
	 */
	public function listaIdiomasSeleccion($idEvento, $claveIdioma = '', $disabled = array())
	{
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "SELECT eis_idEvento, eis_idioma, eis_nombre 
			FROM smc_eventoIdiomas 
			WHERE eis_idEvento = %d 
			ORDER BY eis_idioma ASC
		";

		$qry = sprintf($qry, $idEvento);

		$opciones = '<option value="">Seleccione:</option>';

		if ($this->db->hQuery($qry))
		{

			if ($this->db->hNumRows() > 0)
			{

				while ($idioma = $this->db->hFetchObject())
				{

					$opciones .= '<option value="' . $idioma->eis_idioma . '"';
					$opciones .= ($idioma->eis_idioma == $claveIdioma) ? ' selected="selected"' : '';

					// deshabilitar opciones
					if (is_array($disabled) && array_key_exists($idioma->eis_idioma, $disabled))
					{
						$opciones .= ($disabled[$idioma->eis_idioma]) ? ' disabled="disabled"' : '';
					}

					$opciones .= '>' . $idioma->eis_nombre . '</option>';

				}

				$this->db->hFree();

			}
			else
			{

				$opciones .= '<option value="">Idiomas no disponibles</option>';

			}

		}

		return $opciones;
	}

	/**
	 * 
	 * Metodo que regresa los idiomas del evento
	 * 
	 * @param int $idEvento ID del evento
	 */
	public function getIdiomas($idEvento)
	{
		$idiomas =  array();
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "SELECT eis_idEvento, eis_idioma, eis_nombre 
			FROM smc_eventoIdiomas 
			WHERE eis_idEvento = %d 
			ORDER BY eis_idioma ASC
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
				$this->db->hFree();
			}
		}

		return $idiomas;
	}

	/**
	 * 
	 * Metodo que obtiene las categorias del evento
	 * 
	 * @param str $claveIdioma ID del idioma para determinar el idioma actual
	 * @param array $disabled Array con las opciones a deshabilitar
	 */
	public function getCategoriasRegistro($idEvento)
	{
		$categorias = array();

		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "SELECT c.ecc_idEvento, c.ecc_clave, c.ecc_costo_fecha_1, c.ecc_costo_fecha_2, c.ecc_costo_fecha_3,
			c.ecc_costo_otro, c.ecc_costo_sitio 
			FROM smc_eventoCategorias AS c 
			WHERE c.ecc_idEvento = %d
		";

		$qry = sprintf($qry, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($categoria = $this->db->hFetchObject())
				{
					$categorias[] = $categoria;
				}
				$this->db->hFree();
			}
		}

		return $categorias;
	}

	/**
	 * 
	 * Metodo que obtiene los nombres de las categorias del evento
	 * 
	 * @param int $idEvento ID del evento 
	 * @param str $idioma Idioma de las categorias
	 */
	public function getNombresCategorias($idEvento, $idioma = "es")
	{
		$nombres = array();

		$idEvento = $this->db->hEscapeString($idEvento);
		$idioma = $this->db->hEscapeString($idioma);

		/* Old query
		$qry = "SELECT enc_nombre 
			FROM smc_eventoCategoriasNombres 
			WHERE enc_clave = '%s' 
			AND enc_idEvento = %d 
			AND enc_idioma = '%s'
		";
		*/

		$qry = "SELECT nc.enc_nombre, nc.enc_clave 
			FROM smc_eventoCategoriasNombres AS nc 
			WHERE nc.enc_idEvento = %d 
			AND nc.enc_idioma = '%s'
		";

		$qry = sprintf($qry, $idEvento, $idioma);

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

	/**
	 * 
	 * Metodo que obtiene el nombre de la categoria
	 * 
	 * @param int $idEvento ID del evento 
	 * @param str $clave, Clave de la categoria
	 * @param str $idioma Idioma de las categorias
	 */
	public function getNombreCategoria($idEvento, $clave, $idioma = "es")
	{
		$categoria = "";

		$idEvento = $this->db->hEscapeString($idEvento);
		$clave = $this->db->hEscapeString($clave);
		$idioma = $this->db->hEscapeString($idioma);

		$qry = "SELECT nc.enc_nombre 
			FROM smc_eventoCategoriasNombres AS nc 
			WHERE nc.enc_idEvento = %d 
			AND nc.enc_clave = '%s' 
			AND nc.enc_idioma = '%s'
		";

		$qry = sprintf($qry, $idEvento, $clave, $idioma);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($nombre = $this->db->hFetchObject())
				{
					$categoria = $nombre->enc_nombre;
				}
				$this->db->hFree();
			}
		}

		return $categoria;
	}

	/**
	 * 
	 * Metodo que obtiene los costos de la categoria
	 * 
	 * @param string $clave Clave de la categoria
	 * @param int $idEvento ID del evento
	 */
	public function getCostosCategoria($clave, $idEvento)
	{
		$costos = array();

		$clave = $this->db->hEscapeString($clave);
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "SELECT ecc_idCosto_1, ecc_costo_fecha_1, ecc_idCosto_2, ecc_costo_fecha_2, 
			ecc_idCosto_3, ecc_costo_fecha_3, ecc_idCosto_4, ecc_costo_otro, ecc_idCosto_5, ecc_costo_sitio 
			FROM smc_eventoCategorias 
			WHERE ecc_clave = '%s' 
			AND ecc_idEvento = %d
		";

		$qry = sprintf($qry, $clave, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() == 1)
			{
				$costo = $this->db->hFetchObject();
				$costos = array(
					$costo->ecc_idCosto_1 => array("nombre" => "Costo 1", "costo" => $costo->ecc_costo_fecha_1),
					$costo->ecc_idCosto_2 => array("nombre" => "Costo 2", "costo" => $costo->ecc_costo_fecha_2),
					$costo->ecc_idCosto_3 => array("nombre" => "Costo 3", "costo" => $costo->ecc_costo_fecha_3),
					$costo->ecc_idCosto_4 => array("nombre" => "Otro costo", "costo" => $costo->ecc_costo_otro),
					$costo->ecc_idCosto_5 => array("nombre" => "En sitio", "costo" => $costo->ecc_costo_sitio)
				);
				$this->db->hFree();
			}
		}

		return $costos;
	}

	/**
	 * 
	 * Metodo que obtiene los nombres de las categorias de los acompañantes del evento
	 * 
	 * @param int $idEvento ID del evento 
	 * @param str $idioma Idioma de las categorias
	 */
	public function getNombresCategoriasAcom($idEvento, $idioma = "es")
	{
		$nombres = array();

		$idEvento = $this->db->hEscapeString($idEvento);
		$idioma = $this->db->hEscapeString($idioma);

		$qry = "SELECT nca.ecan_nombre, nca.ecan_clave 
			FROM smc_eventoCategoriasAcomNombres AS nca 
			WHERE nca.ecan_idEvento = %d 
			AND nca.ecan_idioma = '%s'
		";

		$qry = sprintf($qry, $idEvento, $idioma);

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

	/**
	 * 
	 * Metodo que obtiene los costos del item
	 * 
	 * @param string $clave Clave de la categoria
	 * @param int $idEvento ID del evento
	 */
	public function getCostosCategoriaAcom($clave, $idEvento)
	{
		$costos = array();

		$clave = $this->db->hEscapeString($clave);
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "SELECT eca_idCosto_1, eca_costo_fecha_1, eca_idCosto_2, eca_costo_fecha_2, 
			eca_idCosto_3, eca_costo_fecha_3, eca_idCosto_4, eca_costo_otro, eca_idCosto_5, eca_costo_sitio 
			FROM smc_eventoCategoriasAcom 
			WHERE eca_clave = '%s' 
			AND eca_idEvento = %d
		";

		$qry = sprintf($qry, $clave, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() == 1)
			{
				$costo = $this->db->hFetchObject();
				$costos = array(
					$costo->eca_idCosto_1 => array("nombre" => "Costo 1", "costo" => $costo->eca_costo_fecha_1),
					$costo->eca_idCosto_2 => array("nombre" => "Costo 2", "costo" => $costo->eca_costo_fecha_2),
					$costo->eca_idCosto_3 => array("nombre" => "Costo 3", "costo" => $costo->eca_costo_fecha_3),
					$costo->eca_idCosto_4 => array("nombre" => "Otro costo", "costo" => $costo->eca_costo_otro),
					$costo->eca_idCosto_5 => array("nombre" => "En sitio", "costo" => $costo->eca_costo_sitio)
				);
				$this->db->hFree();
			}
		}

		return $costos;
	}

	/**
	 * 
	 * Metodo que obtiene los nombres de las categorias de los items del evento
	 * 
	 * @param int $idEvento ID del evento 
	 * @param str $idioma Idioma de las categorias
	 */
	public function getNombresItems($idEvento, $idioma = "es")
	{
		ini_set('display_errors', 1);
		$nombres = array();

		$idEvento = $this->db->hEscapeString($idEvento);
		$idioma = $this->db->hEscapeString($idioma);

		$qry = "SELECT nci.eni_nombre, nci.eni_clave, i.eci_paxMaximos 
			FROM smc_eventoItemsNombres AS nci 
			JOIN smc_eventoItems AS i ON (i.eci_clave = nci.eni_clave) 
			WHERE nci.eni_idEvento = %d 
			AND nci.eni_idioma = '%s'
		";

		$qry = sprintf($qry, $idEvento, $idioma);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($nombre = $this->db->hFetchObject())
				{
					$nombres[] = $nombre;
				}

				$this->db->hFree();

				uasort($nombres, array($this, "cmp_obj"));
			}
		}

		return $nombres;
	}

	/* Ésta es la función de comparación estática: */
	private function cmp_obj($a, $b)
	{
		$a = strtolower($a->eni_clave);
		$b = strtolower($b->eni_clave);

		return strnatcmp($a, $b);
/*
		if ($a == $b)
		{
			return 0;
		}

		return ($a > $b) ? 1 : -1;*/
	}

	/**
	 * 
	 * Metodo que obtiene los costos de la categoria del item
	 * 
	 * @param string $clave Clave de la categoria
	 * @param int $idEvento ID del evento
	 */
	public function getCostosCategoriaItem($clave, $idEvento)
	{
		$costos = array();

		$clave = $this->db->hEscapeString($clave);
		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "SELECT eci_idCosto_1, eci_costo_fecha_1, eci_idCosto_2, eci_costo_fecha_2, 
			eci_idCosto_3, eci_costo_fecha_3, eci_idCosto_4, eci_costo_otro, eci_idCosto_5, eci_costo_sitio 
			FROM smc_eventoItems 
			WHERE eci_clave = '%s' 
			AND eci_idEvento = %d
		";

		$qry = sprintf($qry, $clave, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() == 1)
			{
				$costo = $this->db->hFetchObject();
				$costos = array(
					$costo->eci_idCosto_1 => array("nombre" => "Costo 1", "costo" => $costo->eci_costo_fecha_1),
					$costo->eci_idCosto_2 => array("nombre" => "Costo 2", "costo" => $costo->eci_costo_fecha_2),
					$costo->eci_idCosto_3 => array("nombre" => "Costo 3", "costo" => $costo->eci_costo_fecha_3),
					$costo->eci_idCosto_4 => array("nombre" => "Otro costo", "costo" => $costo->eci_costo_otro),
					$costo->eci_idCosto_5 => array("nombre" => "En sitio", "costo" => $costo->eci_costo_sitio)
				);
				$this->db->hFree();
			}
		}

		return $costos;
	}

	public function getTotalItems($clave, $tabla)
	{
		$items = array();

		$qry = "SELECT COUNT(i.item_clave) AS filas, SUM(i.item_cantidad) AS totales 
			FROM smc_reg_%s_items AS i 
			WHERE i.item_clave = '%s' 
			AND i.item_status = 'DEM' 
			GROUP BY i.item_clave
		";

		$qry = sprintf($qry, $tabla, $clave);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($item = $this->db->hFetchObject())
				{
					$items = $item;
				}

				$this->db->hFree();
			}
			else
			{
				$items = (object) array("filas" => 0, "totales" => 0);
			}
		}

		return $items;
	}

	/**
	 * 
	 * Metodo que obtiene los titulos de los registros
	 * 
	 * @param int $idEvento ID del evento
	 * @param string $idioma Idioma de los titulos
	 */
	public function getTitulos($idEvento, $idioma = "es")
	{
		$titulos = array();

		$idEvento = $this->db->hEscapeString($idEvento);

		if ($idioma != "")
		{
			$idioma = $this->db->hEscapeString($idioma);
			$idioma = "AND ect_idioma = '" . $idioma . "'";
		}

		$qry = "SELECT ect_idEvento, ect_idTitulo, ect_clave, ect_idioma, ect_nombre 
			FROM smc_eventoTitulos 
			WHERE ect_idEvento = %d 
			%s
		";

		$qry = sprintf($qry, $idEvento, $idioma);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($titulo = $this->db->hFetchObject())
				{
					$titulos[] = $titulo;
				}
				$this->db->hFree();
			}
		}

		return $titulos;
	}

	/**
	 * 
	 * Metodo que obtiene los status de los registros
	 * 
	 * @param int $idEvento ID del evento
	 */
	public function getStatusRegistro($idEvento)
	{
		$status = array();

		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "SELECT esr_idEvento, esr_idStatus, esr_clave, esr_nombre 
			FROM smc_eventoStatusRegistros 
			WHERE esr_idEvento = %d
		";

		$qry = sprintf($qry, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($fila = $this->db->hFetchObject())
				{
					$status[] = $fila;
				}
				$this->db->hFree();
			}
		}

		return $status;
	}

	/**
	 * 
	 * Metodo que obtiene las formas de pago
	 * 
	 * @param int $idEvento ID del evento
	 */
	public function getFormasPago($idEvento)
	{
		$formasPago = array();

		$idEvento = $this->db->hEscapeString($idEvento);

		$qry = "SELECT efp_idEvento, efp_clave 
			FROM smc_eventoFormasPago 
			WHERE efp_idEvento = %d 
			ORDER BY efp_clave DESC
		";

		$qry = sprintf($qry, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() > 0)
			{
				while ($formaPago = $this->db->hFetchObject())
				{
					$formasPago[] = $formaPago;
				}
				$this->db->hFree();
			}
		}

		return $formasPago;
	}

	/**
	 * 
	 * Metodo que obtiene las formas de pago
	 * 
	 * @param int $idEvento ID del evento
	 */
	public function getNombresFormasPago($idEvento, $clave)
	{
		$nombres = array();

		$idEvento = $this->db->hEscapeString($idEvento);
		$clave = $this->db->hEscapeString($clave);

		$qry = "SELECT fpn_idEvento, fpn_idNombre, fpn_nombre 
			FROM smc_eventoFormasPagoNombres 
			WHERE fpn_idEvento = %d 
			AND fpn_clave = '%s'
		";

		$qry = sprintf($qry, $idEvento, $clave);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() > 0)
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

	/**
	 * 
	 * Metodo que obtiene los nombres de las formas de pago del evento
	 * 
	 * @param int $idEvento ID del evento 
	 * @param string $idioma Idioma de las categorias
	 */
	public function getNombresFormasPagosReg($idEvento, $idioma = "es")
	{
		$nombres = array();

		$idEvento = $this->db->hEscapeString($idEvento);
		$idioma = $this->db->hEscapeString($idioma);

		$qry = "SELECT fp.fpn_nombre, fp.fpn_clave 
			FROM smc_eventoFormasPagoNombres  AS fp 
			WHERE fp.fpn_idEvento = %d 
			AND fp.fpn_idioma = '%s'
		";

		$qry = sprintf($qry, $idEvento, $idioma);

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

	/**
	 * Función que trae el costo de la categoria del registro
	 */
	public function getCostoCategoria($clave, $idCosto, $idEvento)
	{
		$clave = $this->db->hEscapeString($clave);
		$idCosto = $this->db->hEscapeString($idCosto);
		$idEvento = $this->db->hEscapeString($idEvento);

		$costo = array();

		if ($idCosto == 1)
		{
			$campos = "ecc_idCosto_1 AS idCosto, ecc_costo_fecha_1 AS costo";
		}
		else if ($idCosto == 2)
		{
			$campos = "ecc_idCosto_2 AS idCosto, ecc_costo_fecha_2 AS costo";
		}
		else if ($idCosto == 3)
		{
			$campos = "ecc_idCosto_3 AS idCosto, ecc_costo_fecha_3 AS costo";
		}
		else if ($idCosto == 4)
		{
			$campos = "ecc_idCosto_4 AS idCosto, ecc_costo_otro AS costo";
		}
		else
		{
			$campos = "ecc_idCosto_5 AS idCosto, ecc_costo_sitio AS costo";
		}

		$qry = "SELECT %s 
			FROM smc_eventoCategorias 
			WHERE ecc_clave = '%s' 
			AND ecc_idEvento = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $campos, $clave, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() == 1)
			{
				$costo = $this->db->hFetchObject();
				$this->db->hFree();
			}
		}

		return $costo;
	}

	/**
	 * Función que trae el costo de la categoria del acompañante
	 */
	public function getCostoCategoriaAcom($clave, $idCosto, $idEvento)
	{
		$clave = $this->db->hEscapeString($clave);
		$idCosto = $this->db->hEscapeString($idCosto);
		$idEvento = $this->db->hEscapeString($idEvento);

		$costo = array();

		if ($idCosto == 1)
		{
			$campos = "eca_idCosto_1 AS idCosto, eca_costo_fecha_1 AS costo";
		}
		else if ($idCosto == 2)
		{
			$campos = "eca_idCosto_2 AS idCosto, eca_costo_fecha_2 AS costo";
		}
		else if ($idCosto == 3)
		{
			$campos = "eca_idCosto_3 AS idCosto, eca_costo_fecha_3 AS costo";
		}
		else if ($idCosto == 4)
		{
			$campos = "eca_idCosto_4 AS idCosto, eca_costo_otro AS costo";
		}
		else
		{
			$campos = "eca_idCosto_5 AS idCosto, eca_costo_sitio AS costo";
		}

		$qry = "SELECT %s 
			FROM smc_eventoCategoriasAcom 
			WHERE eca_clave = '%s' 
			AND eca_idEvento = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $campos, $clave, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() == 1)
			{
				$costo = $this->db->hFetchObject();
				$this->db->hFree();
			}
		}

		return $costo;
	}

	/**
	 * Función que trae el costo del item
	 */
	public function getCostoItem($clave, $idCosto, $idEvento)
	{
		$clave = $this->db->hEscapeString($clave);
		$idCosto = $this->db->hEscapeString($idCosto);
		$idEvento = $this->db->hEscapeString($idEvento);

		$costo = array();

		if ($idCosto == 1)
		{
			$campos = "eci_idCosto_1 AS idCosto, eci_costo_fecha_1 AS costo";
		}
		else if ($idCosto == 2)
		{
			$campos = "eci_idCosto_2 AS idCosto, eci_costo_fecha_2 AS costo";
		}
		else if ($idCosto == 3)
		{
			$campos = "eci_idCosto_3 AS idCosto, eci_costo_fecha_3 AS costo";
		}
		else if ($idCosto == 4)
		{
			$campos = "eci_idCosto_4 AS idCosto, eci_costo_otro AS costo";
		}
		else
		{
			$campos = "eci_idCosto_5 AS idCosto, eci_costo_sitio AS costo";
		}

		$qry = "SELECT %s 
			FROM smc_eventoItems 
			WHERE eci_clave = '%s' 
			AND eci_idEvento = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $campos, $clave, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() == 1)
			{
				$costo = $this->db->hFetchObject();
				$this->db->hFree();
			}
		}

		return $costo;
	}

	/**
	 * Regresa el total de registros del evento
	 */ 
	public function getTotalRegistros($clave, $status = "", $and = "")
	{
		$total = 0;
		$where = 1;

		if ($status != "")
		{
			$where = "status = '" . $status . "'";
		}

		if ($and != "")
		{
			$where .= $and;
		}

		$qry = "SELECT id_registro 
			FROM smc_reg_%s 
			WHERE %s
		";

		$qry = sprintf($qry, $clave, $where);

		if ($this->db->hQuery($qry))
		{
			$total = $this->db->hNumRows();
			$this->db->hFree();
		}

		return $total;
	}

	/**
	 * Regresa el total de registros llegados del evento
	 */ 
	public function getTotalRegistrosImp($clave)
	{
		$total = 0;

		$qry = "SELECT id_registro 
			FROM smc_reg_%s 
			WHERE impresion_gafete = 1
		";

		$qry = sprintf($qry, $clave);

		if ($this->db->hQuery($qry))
		{
			$total = $this->db->hNumRows();
			$this->db->hFree();
		}

		return $total;
	}

	/**
	 * 
	 * Metodo que obtiene el registro por ID
	 * 
	 * @param int $idRegistro ID del registro a buscar
	 * @param string $tabla Nombre de la tabla de los registros
	 */
	public function getRegistroID($idRegistro, $tabla)
	{
		$registro = array();

		$idRegistro = $this->db->hEscapeString($idRegistro);
		$tabla = $this->db->hEscapeString($tabla);

		$qry = "SELECT r.id_registro, r.app, r.apm, r.nombre, r.email, r.emp_o_ins,
			r.status, r.impresion_gafete, r.impresion_constancia, c.enc_nombre 
			FROM %s AS r 
			LEFT JOIN smc_eventoCategoriasNombres AS c ON (c.enc_clave = r.cat_registro) 
			WHERE r.id_registro = %d 
			AND c.enc_idioma = 'es'
		";

		$qry = sprintf($qry, $tabla, $idRegistro);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				$registro[] = $this->db->hFetchObject();
				$this->db->hFree();
			}
		}

		return $registro;
	}

	/**
	 * 
	 * Metodo que obtiene los registros por correo electrónico
	 * 
	 * @param string $email
	 * @param string $tabla Nombre de la tabla donde estan los registros
	 */
	public function getRegistroEmail($email, $tabla)
	{
		$registros = array();

		$email = $this->db->hEscapeString($email);
		$tabla = $this->db->hEscapeString($tabla);

		$qry = "SELECT r.id_registro, r.app, r.apm, r.nombre, r.email, r.emp_o_ins,
			r.status, r.impresion_gafete, r.impresion_constancia, c.enc_nombre 
			FROM %s AS r 
			LEFT JOIN smc_eventoCategoriasNombres AS c ON (c.enc_clave = r.cat_registro) 
			WHERE r.clave_asociada LIKE '%%%s%%' 
			AND c.enc_idioma = 'es' 
			ORDER BY r.email ASC
		";

		$qry = sprintf($qry, $tabla, $email);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($registro = $this->db->hFetchObject())
				{
					$registros[] = $registro;
				}
				$this->db->hFree();
			}
		}

		return $registros;
	}

	/**
	 * 
	 * Metodo que obtiene los registros por empresa
	 * 
	 * @param string $emp_o_ins Nombre de la empresa o institución
	 * @param string $tabla Nombre de la tabla de los registros
	 */
	public function getRegistroEmpOInt($emp_o_ins, $tabla)
	{
		$registros = array();

		$emp_o_ins = $this->db->hEscapeString($emp_o_ins);
		$tabla = $this->db->hEscapeString($tabla);

		$qry = "SELECT r.id_registro, r.app, r.apm, r.nombre, r.email, r.emp_o_ins,
			r.status, r.impresion_gafete, r.impresion_constancia, c.enc_nombre 
			FROM %s AS r 
			LEFT JOIN smc_eventoCategoriasNombres AS c ON (c.enc_clave = r.cat_registro) 
			WHERE r.emp_o_ins LIKE '%%%s%%' 
			AND c.enc_idioma = 'es' 
			ORDER BY r.emp_o_ins ASC
		";

		$qry = sprintf($qry, $tabla, $emp_o_ins);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($registro = $this->db->hFetchObject())
				{
					$registros[] = $registro;
				}
				$this->db->hFree();
			}
		}

		return $registros;
	}

	/**
	 * 
	 * Metodo que obtiene los registros por app o apm o nombre
	 * 
	 * @param string $app Apellido paterno
	 * @param string $apm Apellido materno
	 * @param string $nombre Nombre
	 * @param string $tabla Nombre de la tabla de los registros
	 */
	public function getRegistros($app, $apm, $nombre, $tabla)
	{
		$registros = array();

		$app = $this->db->hEscapeString($app);
		$apm = $this->db->hEscapeString($apm);
		$nombre = $this->db->hEscapeString($nombre);
		$tabla = $this->db->hEscapeString($tabla);

		// Solo por app
		if ($app != "" && $apm == "" && $nombre == "")
		{
			$where = "r.app LIKE '" . $app . "%%' ORDER BY r.app ASC";
		}
		// Por app y apm
		else if ($app != "" && $apm != "" && $nombre == "")
		{
			$where = "r.app LIKE '" . $app . "%%' ";
			$where .= "AND r.apm LIKE '" . $apm . "%%' ORDER BY r.app, r.apm ASC";
		}
		// Por app y apm y nombre
		else if ($app != "" && $apm != "" && $nombre != "")
		{
			$where = "r.app LIKE '" . $app . "%%' ";
			$where .= "AND r.apm LIKE '" . $apm . "%%' ";
			$where .= "AND r.nombre LIKE '" . $nombre . "%%' ORDER BY r.app, r.apm, r.nombre ASC";
		}
		// Por app y nombre
		else if ($app != "" && $nombre != "" && $apm == "")
		{
			$where = "r.app LIKE '" . $app . "%%' ";
			$where .= "AND r.nombre LIKE '" . $nombre . "%%' ORDER BY r.app, r.nombre ASC";
		}
		// Por apm y nombre
		else if ($apm != "" && $nombre != "" && $app == "")
		{
			$where = "r.apm LIKE '" . $apm . "%%' ";
			$where .= "AND r.nombre LIKE '" . $nombre . "%%' ORDER BY r.apm, r.nombre ASC";
		}
		// Solo por apm
		else if ($apm != "" && $app == "" && $nombre == "")
		{
			$where = "r.apm LIKE '" . $apm . "%%' ORDER BY r.apm ASC";
		}
		// solo por nombre
		else // ($nombre != "" && $app == "" && $apm == "")
		{
			$where = "r.nombre LIKE '" . $nombre . "%%' ORDER BY r.nombre ASC";
		}

		$qry = "SELECT r.id_registro, r.app, r.apm, r.nombre, r.email, r.emp_o_ins,
			r.status, r.impresion_gafete, r.impresion_constancia, c.enc_nombre 
			FROM %s AS r 
			LEFT JOIN smc_eventoCategoriasNombres AS c ON (c.enc_clave = r.cat_registro) 
			AND c.enc_idioma = 'es' 
			WHERE %s
		";

		$qry = sprintf($qry, $tabla, $where);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($registro = $this->db->hFetchObject())
				{
					$registros[] = $registro;
				}
				$this->db->hFree();
			}
		}

		return $registros;
	}

	/**
	 * 
	 * Metodo que obtiene los registros por acompañantes
	 * 
	 * @param string $nombre Nombre del acompañante
	 * @param string $tabla Nombre de la tabla de los registros
	 */
	public function getRegistroAcompanante($nombre, $claveEvento)
	{
		$registros = array();

		$nombre = $this->db->hEscapeString($nombre);
		$claveEvento = $this->db->hEscapeString($claveEvento);

		$qry = "SELECT r.id_registro, r.app, r.apm, r.nombre, r.email, r.emp_o_ins, r.status, r.impresion_gafete, r.impresion_constancia 
			FROM smc_reg_%s_acom AS a 
			JOIN smc_reg_%s AS r ON (r.id_registro = a.acm_idRegistro) 
			WHERE a.acm_nombre LIKE '%%%s%%' 
			OR a.acm_app LIKE '%%%s%%' 
			GROUP BY r.id_registro 
			ORDER BY r.app, r.nombre ASC
		";

		$qry = sprintf($qry, $claveEvento, $claveEvento, $nombre, $nombre);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($registro = $this->db->hFetchObject())
				{
					$registros[] = $registro;
				}
				$this->db->hFree();
			}
		}

		return $registros;
	}

	/**
	 * Metodo que obtiene los hoteles del evento
	 */
	public function getHoteles($idEvento)
	{
		$hoteles = array();

		$qry = "SELECT htl_id, htl_nombre, htl_incluye 
			FROM smc_hoteles 
			WHERE htl_idEvento = %d 
			AND htl_status = 1
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

	/**
	 * Metodo que obtiene las habitaciones
	 */
	public function getHabitaciones($idEvento, $idHotel, $idioma = 'es')
	{
		$habitaciones = array();

		$qry = "SELECT hhn_idHabitacion, hhn_idHotel, hhn_nombre 
			FROM smc_hotelesHabitacionesNombres 
			WHERE hhn_idEvento = %d 
			AND hhn_idHotel = %d 
			AND hhn_idioma = '%s'
		";

		$qry = sprintf($qry, $idEvento, $idHotel, $idioma);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($hotel = $this->db->hFetchObject())
				{
					$habitaciones[] = $hotel;
				}
				$this->db->hFree();
			}
		}

		return $habitaciones;
	}

	/*
	 * Metodo que obtiene los costos de las habitaciones
	public function getCostosHabitacion($idEvento, $idHotel, $idHabitacion)
	{
		$costos = array();

		$qry = "SELECT hhc_id, hhc_costoAdulto, hhc_costoMenor, hhc_costoBellBoys 
			FROM smc_hotelesHabitacionesCostos 
			WHERE hhc_idEvento = %d 
			AND hhc_idHotel = %d 
			AND hhc_idHabitacion = %d
		";

		$qry = sprintf($qry, $idEvento, $idHotel, $idHabitacion);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($costo = $this->db->hFetchObject())
				{
					$costos[] = $costo;
				}
				$this->db->hFree();
			}
		}

		return $costos;
	}*/

	/**
	 * Metodo que obtiene el numero maximo de habitaciones a reservar
	 */
	public function getEspecHabitacion($idEvento, $idHotel, $idHabitacion)
	{
		$caurto = array();

		$qry = "SELECT h.hhb_clave, h.hhb_pax, h.hhb_paxMaxReservacion,
			h.hhb_paxAdultos, h.hhb_paxMenores, c.hhc_costoAdulto,
			c.hhc_costoMenor, c.hhc_costoCamaristaNoche, c.hhc_costoBellBoys 
			FROM smc_hotelesHabitaciones AS h 
			JOIN smc_hotelesHabitacionesCostos AS c ON (c.hhc_id_habitacion = h.hhb_id) 
			WHERE h.hhb_idEvento = %d 
			AND h.hhb_id_hotel = %d 
			AND h.hhb_id = %d
		";

		$qry = sprintf($qry, $idEvento, $idHotel, $idHabitacion);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				$caurto = $this->db->hFetchObject();
				$this->db->hFree();
			}
		}

		return $caurto;
	}

	/**
	 * 
	 * Metodo que obtiene los primeros 15 registros
	 * 
	 * @param string $tabla nombre de la tabla de los registro
	 */
	public function getRegistrosInicio($clave)
	{
		$registros = array();

		$tabla = $this->db->hEscapeString($clave);

		$qry = "SELECT r.id_registro, r.app, r.apm, r.nombre, r.email, r.emp_o_ins,
			r.status, r.impresion_gafete, r.impresion_constancia, c.enc_nombre 
			FROM %s AS r 
			LEFT JOIN smc_eventoCategoriasNombres AS c ON (c.enc_clave = r.cat_registro) 
			WHERE c.enc_idioma = 'es' 
			ORDER BY id_registro ASC 
			LIMIT 15
		";

		$qry = sprintf($qry, $clave);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($registro = $this->db->hFetchObject())
				{
					$registros[] = $registro;
				}
				$this->db->hFree();
			}
		}

		return $registros;
	}

	/**
	 * Metodo que regresa el costo total del registro
	 * incluyendo acompañanes e items
	 */
	public function costoTotalRegistro($idRegistro, $clave)
	{
		$total = 0;
		$registro = 0;
		$acoms = 0;
		$items = 0;

		// Costo total del registro
		$qryReg = "SELECT costo_registro 
			FROM smc_reg_%s 
			WHERE id_registro = %d 
			LIMIT 1
		";

		$qryReg = sprintf($qryReg, $clave, $idRegistro);

		if ($this->db->hQuery($qryReg))
		{
			if ($this->db->hNumRows() == 1)
			{
				$registro = $this->db->hFetchObject();
				$registro = $registro->costo_registro;
				$this->db->hFree();
			}
		}

		// Costo total de los acompañantes
		$qryAcoms = "SELECT SUM(acm_costo) AS costo_total 
			FROM smc_reg_%s_acom 
			WHERE acm_idRegistro = %d
		";

		$qryAcoms = sprintf($qryAcoms, $clave, $idRegistro);

		if ($this->db->hQuery($qryAcoms))
		{
			if ($this->db->hNumRows() == 1)
			{
				$acoms = $this->db->hFetchObject();
				$acoms = $acoms->costo_total;
				$this->db->hFree();
			}
		}

		// Costo total de los items
		$qryItems = "SELECT SUM(item_costo_total) AS costo_total 
			FROM smc_reg_%s_items 
			WHERE item_idRegistro = %d
		";

		$qryItems = sprintf($qryItems, $clave, $idRegistro);

		if ($this->db->hQuery($qryItems))
		{
			if ($this->db->hNumRows() == 1)
			{
				$items = $this->db->hFetchObject();
				$items = $items->costo_total;
				$this->db->hFree();
			}
		}

		return ($registro + $acoms + $items);
	}

	/**
	 * Metodo que genera el PDF del congresista
	 */
	public function cargarGafetePDF($idEvento, $evento, $registro, $func, $ctrl = null)
	{
		require_once('./'. PATH_SMVC . '/' . PATH_EXT . '/tcpdf/config/lang/eng.php');
		require_once('./'. PATH_SMVC . '/' . PATH_EXT . '/tcpdf/tcpdf.php');

		// create new PDF document
		$orientation = "L"; //PDF_PAGE_ORIENTATION;
		$pdf_unit = "cm"; // PDF_UNIT;
		$formato = array(20, 12); // PDF_PAGE_FORMAT

		$pdf = new TCPDF($orientation, $pdf_unit, $formato, true, 'UTF-8', false);

		// create new PDF document
		//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator("Tecnoregistro");
		$pdf->SetAuthor($evento->evt_clave);
		$pdf->SetTitle('Gafete');

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		//set margins
		$pdf->SetMargins(0, 0, 0);

		//set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 0);

		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		//set some language-dependent strings
		$pdf->setLanguageArray($l);

		// ---------------------------------------------------------

		// set font
		$pdf->SetFont('helvetica', 'B', 12);

		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage(); // medidas en inch

		// X se mueve de izquiera a derecha 
		// Y se nueve de arriba a abajo

		// Set some content to print
		$nombre = $func->mayusStr($registro->nombre);
		$app = $func->mayusStr($registro->app);
		$apm = $func->mayusStr($registro->apm);
		$apellidos = trim($registro->app . ' ' . $registro->apm);
		$pais = $func->mayusStr($registro->pais_nombreEs);
		$emp_o_ins =  $func->mayusStr($registro->emp_o_ins);
		//$categoria = $this->getNombreCategoria($idEvento, $registro->cat_registro);

		//$img_file = './' . PATH_IMAGES . '/gafetes/gafete.png';
		//$border = array('LTRB' => array('width' => 5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(28, 334, 34)));
		//$pdf->Image($img_file, 0, 0, 235, 235, '', '', '', false, 300, '', false, 0, 0, 0);

		// Nombre
		$pdf->SetFont('helvetica', 'B', 22);
		//$pdf->writeHTMLCell('100', '', $x='-15', $y='80', $nombre, $border=0, $ln=0, $fill=0, $reseth=true, $align='C', $autopadding=false);
		$pdf->writeHTMLCell('9', '', $x='1', $y='8', $nombre, $border=1, $ln=0, $fill=0, $reseth=true, $align='C', $autopadding=false);

		$pdf->SetFont('helvetica', '', 16);
		//$pdf->writeHTMLCell('100', '', $x='-15', $y='90', $apellidos, $border=0, $ln=0, $fill=0, $reseth=true, $align='C', $autopadding=false);
		$pdf->writeHTMLCell('9', '', $x='1', $y='9', $apellidos, $border=1, $ln=0, $fill=0, $reseth=true, $align='C', $autopadding=false);

		//$pdf->writeHTMLCell('120', '20', $x='45', $y='100', $registro->talleres[0]->eni_nombre, $border=0, $ln=0, $fill=0, $reseth=true, $align='C', $autopadding=false);

		//$pdf->SetFont('helvetica', '', 14);
		//$pdf->writeHTMLCell('85', '', $x='18', $y='64', $registro->emp_o_ins, $border=0, $ln=0, $fill=0, $reseth=false, $align='C', $autopadding=false);
		//$pdf->writeHTMLCell('85', '', $x='100', $y='64', $registro->emp_o_ins, $border=0, $ln=0, $fill=0, $reseth=false, $align='C', $autopadding=false);

		//$pdf->SetFont('helvetica', '', 20);
		//$pdf->writeHTMLCell('', '', 10, 60, $registro->ciudad, $border=0, $ln=0, $fill=0, $reseth=false, $align='L', $autopadding=false);

		//$pdf->SetFont('helvetica', '', 18);
		//$pdf->writeHTMLCell('', '', 10, 70, $registro->emp_o_ins, $border=0, $ln=0, $fill=0, $reseth=false, $align='L', $autopadding=false);

		if (isset($registro->foto_fotografia))
		{
			//echo '<img src="data:' . $registro->foto_mime . ';base64,' . $registro->foto_fotografia . '" />';
			$pdf->Image('@' . base64_decode($registro->foto_fotografia), $x='4', $y='2', 4, 4.2); //  40, 42
		}

		// define barcode style
		$style = array(
			'align' => 'C',
			'stretch' => false,
			'fitwidth' => true,
			'cellfitalign' => 'C',
			'border' => false,
			'hpadding' => 'auto',
			'vpadding' => 'auto',
			'fgcolor' => array(0,0,0),
			'bgcolor' => false, //array(255,255,255),
			'text' => true,
			'font' => 'helvetica',
			'fontsize' => 11,
			'stretchtext' => 5
		);

		// Codigo de barra
		//$pdf->write1DBarcode($func->nombreImagenBarcode($registro->id_registro), 'C128A', $x='-140', $y='98', '', 16, 0.4, $style, 'N');
		//$pdf->write1DBarcode($func->nombreImagenBarcode($registro->id_registro), 'C128A', $x='90', $y='180', '', 16, 0.4, $style, 'N');

		// ---------------------------------------------------------

		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$pdf->Output('gafete_' . time() . '.pdf', 'I');
	}

	/**
	 * Metodo que genera el PDF del congresista
	 */
	public function cargarGafetePDFOr($idEvento, $evento, $registro, $func)
	{
		require_once('./'. PATH_SMVC . '/' . PATH_EXT . '/tcpdf/config/lang/eng.php');
		require_once('./'. PATH_SMVC . '/' . PATH_EXT . '/tcpdf/tcpdf.php');

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator("Tecnoregistro");
		$pdf->SetAuthor($evento->evt_clave);
		$pdf->SetTitle('Gafete');

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		//set margins
		$pdf->SetMargins(0, 0, 0);

		//set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 0);

		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		//set some language-dependent strings
		$pdf->setLanguageArray($l);

		// ---------------------------------------------------------

		// set font
		$pdf->SetFont('helvetica', 'B', 26);

		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage(); // medidas en inch

		// Set some content to print
		//$html = '<div style="width: 200px; height: 4.9in;">' . $func->mayusStr($registro->nombre) . "<br />";
		$nombre = $func->mayusStr($registro->nombre);# . "</div>";

		$apellido = $func->mayusStr($registro->app . " " . $registro->apm);# . "</div>";

		//$emp_o_ins = $func->mayusStr($registro->emp_o_ins);# . "</div>";

		// Nombre
		$pdf->writeHTMLCell('0', '', $x='', $y='94', $nombre, $border=0, $ln=1, $fill=0, $reseth=false, $align='C', $autopadding=false);
		//$pdf->writeHTMLCell('0', '', $x='95', $y='43', $nombre, $border=0, $ln=1, $fill=0, $reseth=false, $align='C', $autopadding=false);
		// Apellido
		//$pdf->SetFont('helvetica', 'B', 26);
		$pdf->writeHTMLCell('0', '', $x='0', $y='102', $apellido, $border=0, $ln=1, $fill=0, $reseth=false, $align='C', $autopadding=false);
		//$pdf->writeHTMLCell('0', '', $x='95', $y='54', $apellido, $border=0, $ln=1, $fill=0, $reseth=false, $align='C', $autopadding=false);
		//$pdf->SetFont('helvetica', 'B', 10);
		//$pdf->writeHTMLCell('0', '', $x='5', $y='111', $registro->id_registro, $border=0, $ln=1, $fill=0, $reseth=false, $align='C', $autopadding=false);
		//$pdf->SetFont('helvetica', 'B', 12);
		//$pdf->writeHTMLCell('0', '', $x='5', $y='98', $emp_o_ins, $border=0, $ln=1, $fill=0, $reseth=false, $align='C', $autopadding=false);

		//$pdf->SetFont('helvetica', '', 12);
		//$pdf->writeHTMLCell('0', '', $x='5', $y='125', '*' . $func->nombreImagenBarcode($registro->id_registro, 4) . '*', $border=0, $ln=1, $fill=0, $reseth=false, $align='C', $autopadding=false);

		//if ($registro->cat_registro == 'ASST' || $registro->cat_registro == 'PREFIR')
		//{
			//$QR = $func->nombreImagenBarcode($registro->id_registro, 3);
			//$img_file = './' . PATH_IMAGES . '/gafetes/' . $evento->evt_clave . '/QR/' . $QR . '.png';
		//}

		//if (is_file($img_file))
		//{
			//$pdf->Image($img_file, 116, 46, 36, 36, '', '', '', false, 300, '', false, false, 0);
		//}

		// define barcode style
		$style = array(
			'align' => 'C',
			'stretch' => false,
			'fitwidth' => true,
			'cellfitalign' => 'C',
			'border' => false,
			'hpadding' => 'auto',
			'vpadding' => 'auto',
			'fgcolor' => array(0,0,0),
			'bgcolor' => false, //array(255,255,255),
			'text' => true,
			'font' => 'helvetica',
			'fontsize' => 11,
			'stretchtext' => 5
		);

		// Codigo de barra
		$pdf->write1DBarcode($registro->id_registro, 'C128A', 0, 110, '', 16, 0.4, $style, 'N');
		//$pdf->write1DBarcode($registro->id_registro, 'C128A', 95, 64, '', 16, 0.4, $style, 'N');

		// ---------------------------------------------------------

		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$pdf->Output('gafete_' . time() . '.pdf', 'I');
	}

	/**
	 * Metodo que genera el PDF del congresista
	 * Gafete Normal
	 */
	public function cargarGafetePDFCarta($idEvento, $evento, $registro, $func)
	{
		require_once('./'. PATH_SMVC . '/' . PATH_EXT . '/tcpdf/config/lang/eng.php');
		require_once('./'. PATH_SMVC . '/' . PATH_EXT . '/tcpdf/tcpdf.php');

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator("Tecnoregistro");
		$pdf->SetAuthor($evento->evt_clave);
		$pdf->SetTitle('Gafete');

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		//set margins
		$pdf->SetMargins(0, 0, 0);

		//set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 0);

		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		//set some language-dependent strings
		$pdf->setLanguageArray($l);

		// ---------------------------------------------------------

		// set font
		$pdf->SetFont('helvetica', '', 10);

		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage(); // medidas en inch

		// Set some content to print
		$html = '<div style="width: 170px; height: 4.9in;">' . $func->mayusStr($registro->nombre) . " ";
		$html .= $func->mayusStr($registro->app . " " . $registro->apm) . "</div>";

		// Nombre
		$pdf->writeHTMLCell('', '', $x='5', $y='112', $html, $border=0, $ln=1, $fill=0, $reseth=false, $align='C', $autopadding=false);

		// Categoria
		//$pdf->SetFont('helvetica', '', 14);
		//$categoria = $this->getNombreCategoria($idEvento, $registro->cat_registro);
		//$pdf->writeHTMLCell('40', '5', $x='56', $y='110', $categoria, $border=0, $ln=1, $fill=0, $reseth=false, $align='C', $autopadding=false);

		// define barcode style
		$style = array(
			'align' => 'L',
			'stretch' => false,
			'fitwidth' => true,
			'cellfitalign' => 'L',
			'border' => false,
			'hpadding' => 'auto',
			'vpadding' => 'auto',
			'fgcolor' => array(0,0,0),
			'bgcolor' => false, //array(255,255,255),
			'text' => true,
			'font' => 'helvetica',
			'fontsize' => 8,
			'stretchtext' => 5
		);

		// Codigo de barra
		//$pdf->write1DBarcode($func->nombreImagenBarcode($registro->id_registro), 'C128A', 110, 113, '', 16, 0.4, $style, 'N');

		// ---------------------------------------------------------

		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$pdf->Output('gafete_' . time() . '.pdf', 'I');
	}

	/**
	 * Metodo que genera el PDF del congresista
	 */
	public function cargarGafetePDFPVC($idEvento, $evento, $registro, $func)
	{
		require_once('./'. PATH_SMVC . '/' . PATH_EXT . '/tcpdf/config/lang/eng.php');
		require_once('./'. PATH_SMVC . '/' . PATH_EXT . '/tcpdf/tcpdf.php');

		// create new PDF document
		$orientation = "P"; //PDF_PAGE_ORIENTATION;
		$pdf_unit = "cm"; // PDF_UNIT;
		$formato = array(8.5, 5.5); // PDF_PAGE_FORMAT

		$pdf = new TCPDF($orientation, $pdf_unit, $formato, true, 'UTF-8', false);
		$fontname = $pdf->addTTFfont('./' . PATH_VIEWS . '/fonts/Calibri.ttf', 'TrueTypeUnicode', '', 32);

		// set document information
		$pdf->SetCreator("Tecnoregistro");
		$pdf->SetAuthor($evento->evt_clave);
		$pdf->SetTitle('Gafete');

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		//set margins
		$pdf->SetMargins(0, 0, 0);

		//set auto page breaks
		$pdf->SetAutoPageBreak(FALSE, 0);

		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		//set some language-dependent strings
		$pdf->setLanguageArray($l);

		// ---------------------------------------------------------

		// set font
		$pdf->SetFont($fontname, '', 10); // bgothl

		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage();

		/*if ($registro->cat_registro == 'GOB')
		{
			$img_file = './' . PATH_IMAGES . '/gafetes/AGA/GAFETE.jpg';
			$color_font = 'Green';
		}
		else if ($registro->cat_registro == 'ORGANIZADO')
		{
			$img_file = './' . PATH_IMAGES . '/gafetes/AGA/GAFETE.jpg';
			$color_font = 'Red';
		}
		else if ($registro->cat_registro == 'SERVICIOS')
		{
			$img_file = './' . PATH_IMAGES . '/gafetes/AGA/GAFETE.jpg';
			$color_font = '#4b3621';
		}
		else
		{
			$img_file = './' . PATH_IMAGES . '/gafetes/AGA/GAFETE.jpg';
			$color_font = 'Black';
		}*/

		$color_font = '#ffffff';
		if ($registro->cat_registro == 'GOB') {
			$img_file = './' . PATH_IMAGES . '/gafetes/AGA/GEN.png';
		} else {
			$img_file = './' . PATH_IMAGES . '/gafetes/AGA/' . $registro->cat_registro . '.png';
		}

		$pdf->Image($img_file, 0, 0, 5.5, 8.5, '', '', '', false, 300, '', false, false, 0);

		$nombre = $func->mayusStr($registro->nombre);
		$apellidos = $func->mayusStr($registro->app . " " . $registro->apm);
		$cargo = $func->mayusStr($registro->cargo);

		// Nombre
		$pdf->SetFont($fontname, '', 13);
		$pdf->writeHTMLCell('5.0', '', 0.3, 2.5, '<div style="color: ' . $color_font . '">' . $nombre . '</div>', $border=0, $ln=0, $fill=0, $reseth=true, $align='L', $autopadding=false);
		$pdf->writeHTMLCell('5.0', '', 0.3, 3.1, '<div style="color: ' . $color_font . '">' . $apellidos . '</div>', $border=0, $ln=0, $fill=0, $reseth=true, $align='L', $autopadding=false);

		if (isset($registro->fotografia) && is_file('./' . PATH_IMAGES . '/agaFotos/' . $registro->fotografia))
		{
			//echo '<img src="data:' . $registro->foto_mime . ';base64,' . $registro->foto_fotografia . '" />';
			//$pdf->Image('@' . base64_decode($registro->fotografia), 0.2, 2, 2.5, 3.3);
			$pdf->Image('./' . PATH_IMAGES . '/agaFotos/' . $registro->fotografia, 0.3, 3.90, 2.2, 2.75);
		}

		// define barcode style
		$style = array(
			'align' => 'C',
			'stretch' => false,
			'fitwidth' => true,
			'cellfitalign' => 'C',
			'border' => false,
			'hpadding' => 'auto',
			'vpadding' => 'auto',
			'fgcolor' => array(0,0,0),
			'bgcolor' => array(255,255,255), //array(255,255,255),
			'text' => true,
			'font' => 'helvetica',
			'fontsize' => 6,
			'stretchtext' => 8
		);

		// Codigo de barra
		$pdf->write1DBarcode($func->nombreImagenBarcode($registro->id_registro), 'C128A', 0.7, 7.0, 4, 1, 0.09, $style, 'N');

		// ---------------------------------------------------------

		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$pdf->Output('gafete_' . time() . '.pdf', 'I');
	}

	/**
	 * Metodo que ingresa la primera impresion de gafete
	 */
	public function setPrimerGafate($registro, $clave, $idUsuario)
	{
		$qry = "UPDATE smc_reg_%s SET 
			impresion_gafete = 1,
			fecha_impresion_gafete = NOW(),
			impresion_total_gafete = 1,
			idUsuario_modifico = %d 
			WHERE id_registro = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $clave, $idUsuario, $registro);

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
	 * Metodo que incrementa la reimpresion de gafate
	 */
	public function setReImpresionGafate($registro, $clave, $idUsuario)
	{
		$qry = "UPDATE smc_reg_%s SET 
			fecha_impresion_gafete = NOW(),
			impresion_total_gafete = (impresion_total_gafete + 1),
			idUsuario_modifico = %d 
			WHERE id_registro = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $clave, $idUsuario, $registro);

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
	 * Metodo que genera el PDF
	 */
	public function cargarConstanciaPDF($idEvento, $evento, $registro, $func)
	{
		require_once('./'. PATH_SMVC . '/' . PATH_EXT . '/tcpdf/config/lang/eng.php');
		require_once('./'. PATH_SMVC . '/' . PATH_EXT . '/tcpdf/tcpdf.php');

		// create new PDF document
		$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator("Tecnoregistro");
		$pdf->SetAuthor($evento->evt_clave);
		$pdf->SetTitle('Constancia');

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		//set margins
		$pdf->SetMargins(0, 0, 0);

		//set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 0);

		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		//set some language-dependent strings
		$pdf->setLanguageArray($l);

		// ---------------------------------------------------------

		// set font
		$pdf->SetFont('times', 'B', 16);

		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage(); // medidas en inch

		$pdf->setJPEGQuality(100);

		// Set some content to print
		$nombre = $func->mayusStr($registro->nombre);
		$app = $func->mayusStr($registro->app);
		$apm = $func->mayusStr($registro->apm);
		$apellidos = trim($registro->app . ' ' . $registro->apm);
		$nombres = $registro->titulo . ' ' . $nombre . ' ' . $apellidos;
		$pais = $func->mayusStr($registro->pais_nombreEs);
		$emp_o_ins =  $func->mayusStr($registro->emp_o_ins);
		$cargo = $registro->cargo;
		//$categoria = $this->getNombreCategoria($idEvento, $registro->cat_registro);

		// set font
		$pdf->SetFont('times', 'B', 22);
		//$pdf->Ln(25, true);
		$pdf->writeHTMLCell('', '', $x='', $y='75', $nombres, $border=0, $ln=1, $fill=0, $reseth=false, $align='C', $autopadding=false);
		//$pdf->writeHTMLCell('', '', $x='', $y='120', $apellidos, $border=0, $ln=1, $fill=0, $reseth=false, $align='C', $autopadding=false);

		/*if (isset($post->total_horas) AND $post->total_horas >= 6)
		{
			$pdf->SetFont('helvetica', '', 8);
			$pdf->writeHTMLCell('', '', $x='175', $y='201', 'VALOR CURRICULAR 6 PUNTOS CMC-058-2013.', $border=0, $ln=1, $fill=0, $reseth=false, $align='C', $autopadding=false);
		}*/

		//$pdf->Image('./'. PATH_IMAGES . '/firmas/clementepoon.png', 50, 155, 35, 45, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);
		//$pdf->Image('./'. PATH_IMAGES . '/firmas/carlosalberto.png', 200, 150, 55, 45, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);

		// ---------------------------------------------------------

		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$pdf->Output('constancia_' . time() . '.pdf', 'I');
	}

	/**
	 * Metodo que genera el PDF de la constancia para taller
	 */
	public function cargarConstanciaTallerPDF($idEvento, $evento, $registro, $taller, $func)
	{
		require_once('./'. PATH_SMVC . '/' . PATH_EXT . '/tcpdf/config/lang/eng.php');
		require_once('./'. PATH_SMVC . '/' . PATH_EXT . '/tcpdf/tcpdf.php');

		// create new PDF document
		$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator("Tecnoregistro");
		$pdf->SetAuthor($evento->evt_clave);
		$pdf->SetTitle('Constancia');

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		//set margins
		$pdf->SetMargins(0, 0, 0);

		//set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 0);

		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		//set some language-dependent strings
		$pdf->setLanguageArray($l);

		// ---------------------------------------------------------

		// set font
		$pdf->SetFont('helvetica', '', 16);

		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage(); // medidas en inch

		$pdf->setJPEGQuality(100);

		// Print text using writeHTMLCell()
		$pdf->writeHTMLCell('', '', $x='', $y='85', 'Hotorga el siguiente reconocimiento a:', $border=0, $ln=1, $fill=0, $reseth=false, $align='C', $autopadding=false);

		// Nombre para la constancia
		$nombre = $func->mayusStr(
			$registro->titulo . " " .
			$registro->nombre . " " .
			$registro->app . " "
		);

		// set font
		$pdf->SetFont('helvetica', 'B', 16);

		// Print text using writeHTMLCell()
		$pdf->writeHTMLCell('', '', $x='', $y='100', $nombre, $border=0, $ln=1, $fill=0, $reseth=false, $align='C', $autopadding=false);

		// set font
		$pdf->SetFont('helvetica', '', 16);

		// Print text using writeHTMLCell()
		$pdf->writeHTMLCell('', '', $x='', $y='115', 'Por haber asistido al taller:', $border=0, $ln=1, $fill=0, $reseth=false, $align='C', $autopadding=false);

		// set font
		$pdf->SetFont('helvetica', 'B', 18);

		// Print text using writeHTMLCell()
		$pdf->writeHTMLCell('', '', $x='', $y='130', $taller->eni_nombre, $border=0, $ln=1, $fill=0, $reseth=false, $align='C', $autopadding=false);

		// ---------------------------------------------------------

		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$pdf->Output('constancia_' . time() . '.pdf', 'I');
	}

	public function setPrimerConstancia($registro, $clave, $idUsuario)
	{
		$qry = "UPDATE smc_reg_%s SET 
			impresion_constancia = 1,
			fecha_impresion_constancia = NOW(),
			impresion_total_constancia = 1,
			idUsuario_modifico = %d 
			WHERE id_registro = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $clave, $idUsuario, $registro);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}
		}

		return false;
	}

	public function setReImpresionConstancia($registro, $clave, $idUsuario)
	{
		$qry = "UPDATE smc_reg_%s SET 
			fecha_impresion_constancia = NOW(),
			impresion_total_constancia = (impresion_total_constancia + 1),
			idUsuario_modifico = %d 
			WHERE id_registro = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $clave, $idUsuario, $registro);

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
	 * Metodo que genera el PDF del acompañante
	 * Gafete normal
	 */
	public function cargarGafetePDFAcomElbueno($idEvento, $evento, $acompanante, $func)
	{
		require_once('./'. PATH_SMVC . '/' . PATH_EXT . '/tcpdf/config/lang/eng.php');
		require_once('./'. PATH_SMVC . '/' . PATH_EXT . '/tcpdf/tcpdf.php');

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator("Tecnoregistro");
		$pdf->SetAuthor($evento->evt_clave);
		$pdf->SetTitle('Gafete');

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		//set margins
		$pdf->SetMargins(0, 0, 0);

		//set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 0);

		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		//set some language-dependent strings
		$pdf->setLanguageArray($l);

		// ---------------------------------------------------------

		// set font
		$pdf->SetFont('helvetica', '', 22);

		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage(); // medidas en inch

		// Set some content to print
		$nombre = $func->mayusStr($acompanante->acm_nombre);
		$app = $func->mayusStr($acompanante->acm_app);
		$apm = $func->mayusStr($acompanante->acm_apm);
		$apellidos = trim($acompanante->acm_app . ' ' . $acompanante->acm_apm);
		//$categoria = $this->getNombreCategoria($idEvento, $registro->cat_registro);

		// Nombre
		$pdf->SetFont('helvetica', 'B', 18);
		//$pdf->writeHTMLCell('100', '', $x='-15', $y='80', $nombre, $border=0, $ln=0, $fill=0, $reseth=true, $align='C', $autopadding=false);
		$pdf->writeHTMLCell('', '', $x='0', $y='80', $nombre, $border=0, $ln=0, $fill=0, $reseth=true, $align='C', $autopadding=false);

		$pdf->SetFont('helvetica', '', 16);
		//$pdf->writeHTMLCell('100', '', $x='-15', $y='90', $apellidos, $border=0, $ln=0, $fill=0, $reseth=true, $align='C', $autopadding=false);
		$pdf->writeHTMLCell('', '', $x='0', $y='90', $apellidos, $border=0, $ln=0, $fill=0, $reseth=true, $align='C', $autopadding=false);

		// Categoria
		//$pdf->SetFont('helvetica', '', 14);
		//$categoria = $this->getNombreCategoria($idEvento, $acompanante->acm_clave);
		//$pdf->writeHTMLCell('40', '5', $x='56', $y='110', $categoria, $border=0, $ln=1, $fill=0, $reseth=false, $align='C', $autopadding=false);

		// define barcode style
		$style = array(
			'align' => 'C',
			'stretch' => false,
			'fitwidth' => true,
			'cellfitalign' => 'C',
			'border' => false,
			'hpadding' => 'auto',
			'vpadding' => 'auto',
			'fgcolor' => array(0,0,0),
			'bgcolor' => false, //array(255,255,255),
			'text' => true,
			'font' => 'helvetica',
			'fontsize' => 11,
			'stretchtext' => 5
		);

		// Codigo de barra
		//$pdf->write1DBarcode($func->nombreImagenBarcode($acompanante->id_acompanante), 'C128A', 110, 113, '', 16, 0.4, $style, 'N');
		$pdf->write1DBarcode($func->nombreImagenBarcode($acompanante->id_acompanante), 'C128A', $x='0', $y='100', '', 16, 0.4, $style, 'N');

		// ---------------------------------------------------------

		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$pdf->Output('gafete_acom_' . time() . '.pdf', 'I');
	}

	/**
	 * Metodo que genera el PDF del acompañante
	 * Gafete normal
	 */
	public function cargarGafetePDFAcom($idEvento, $evento, $acompanante, $func)
	{
		require_once('./'. PATH_SMVC . '/' . PATH_EXT . '/tcpdf/config/lang/eng.php');
		require_once('./'. PATH_SMVC . '/' . PATH_EXT . '/tcpdf/tcpdf.php');

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator("Tecnoregistro");
		$pdf->SetAuthor($evento->evt_clave);
		$pdf->SetTitle('Gafete');

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		//set margins
		$pdf->SetMargins(0, 0, 0);

		//set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 0);

		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		//set some language-dependent strings
		$pdf->setLanguageArray($l);

		// ---------------------------------------------------------

		// set font
		$pdf->SetFont('helvetica', 'B', 26);

		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage(); // medidas en inch
		$nombre = $func->mayusStr($acompanante->acm_nombre);
		$apellido = $func->mayusStr($acompanante->acm_app . " " . $acompanante->acm_apm);

		$pdf->writeHTMLCell('0', '', $x='', $y='94', $nombre, $border=0, $ln=1, $fill=0, $reseth=false, $align='C', $autopadding=false);
		//$pdf->writeHTMLCell('0', '', $x='95', $y='43', $nombre, $border=0, $ln=1, $fill=0, $reseth=false, $align='C', $autopadding=false);
		// Apellido
		//$pdf->SetFont('helvetica', 'B', 26);
		$pdf->writeHTMLCell('0', '', $x='0', $y='102', $apellido, $border=0, $ln=1, $fill=0, $reseth=false, $align='C', $autopadding=false);

		// Categoria
		//$pdf->SetFont('helvetica', '', 14);
		//$categoria = $this->getNombreCategoria($idEvento, $acompanante->acm_clave);
		//$pdf->writeHTMLCell('40', '5', $x='56', $y='110', $categoria, $border=0, $ln=1, $fill=0, $reseth=false, $align='C', $autopadding=false);

		// define barcode style
		$style = array(
			'align' => 'C',
			'stretch' => false,
			'fitwidth' => true,
			'cellfitalign' => 'C',
			'border' => false,
			'hpadding' => 'auto',
			'vpadding' => 'auto',
			'fgcolor' => array(0,0,0),
			'bgcolor' => false, //array(255,255,255),
			'text' => true,
			'font' => 'helvetica',
			'fontsize' => 11,
			'stretchtext' => 5
		);

		// Codigo de barra
		$pdf->write1DBarcode($acompanante->id_acompanante, 'C128A', 0, 110, '', 16, 0.4, $style, 'N');
		//$pdf->write1DBarcode($func->nombreImagenBarcode($acompanante->id_acompanante), 'C128A', 110, 113, '', 16, 0.4, $style, 'N');

		// ---------------------------------------------------------

		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$pdf->Output('gafete_acom_' . time() . '.pdf', 'I');
	}

	/**
	 * Metodo que ingresa la primera impresion de gafete del acompañante
	 */
	public function setPrimerGafateAcom($idRegistro, $idAcompanante, $clave, $idUsuario)
	{
		$qry = "UPDATE smc_reg_%s_acom SET 
			acm_impresion_gafete = 1,
			acm_fecha_impresion_gafete = NOW(),
			acm_impresion_total_gafete = 1,
			acm_usuarioModifico = %d 
			WHERE acm_idRegistro = %d 
			AND id_acompanante = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $clave, $idUsuario, $idRegistro, $idAcompanante);

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
	 * Metodo que incrementa la reimpresion de gafate del acompañante
	 */
	public function setReImpresionGafateAcom($idRegistro, $idAcompanante, $clave, $idUsuario)
	{
		$qry = "UPDATE smc_reg_%s_acom SET 
			acm_fecha_impresion_gafete = NOW(),
			acm_impresion_total_gafete = (acm_impresion_total_gafete + 1),
			acm_usuarioModifico = %d 
			WHERE acm_idRegistro = %d 
			AND id_acompanante = %d 
			LIMIT 1
		";

		$qry = sprintf($qry, $clave, $idUsuario, $idRegistro, $idAcompanante);

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
	 * Metodo que genera el PDF del acompañante
	 */
	public function reporteFotosPDFBack($registros, $func)
	{
		require_once('./'. PATH_SMVC . '/' . PATH_EXT . '/tcpdf/config/lang/eng.php');
		require_once('./'. PATH_SMVC . '/' . PATH_EXT . '/tcpdf/tcpdf.php');

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, 'cm', PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator("Tecnoregistro");
		$pdf->SetAuthor('G2012');
		$pdf->SetTitle('Gafete');

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		//set margins
		$pdf->SetMargins(0, 0, 0);

		//set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 0);

		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		//set some language-dependent strings
		$pdf->setLanguageArray($l);

		// ---------------------------------------------------------

		// set font
		$pdf->SetFont('helvetica', '', 9);

		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage();

		//$pdf->Image($img_file, 0, 0, 8.5, 5.5, '', '', '', false, 300, '', false, false, 0);

		$count = 0;
		foreach ($registros as $key => $registro)
		{
			if ($count == 0)
			{
				$posFoto = 4;

				$pdf->Ln(2);

				// Set some content to print
				$html = '<table border="0" style="border-bottom: 1px solid #777;" width="95%">';
					$html .= '<tr>';
						$html .= '<td style="height: 50px; width: 60px;" valign="bottom">ID</td>';
						$html .= '<td style="height: 50px; width: 140px;" valign="bottom">Nombre</td>';
						$html .= '<td style="height: 50px; width: 140px;" valign="bottom">APP</td>';
						$html .= '<td style="height: 50px; width: 140px;" valign="bottom">APM</td>';
						$html .= '<td style="height: 50px; width: 160px;" valign="bottom">País</td>';
						$html .= '<td style="height: 50px; width: 60px;" valign="bottom">Foto</td>';
					$html .= '</tr>';
				$html .= '</table>';

				$pdf->setX(0.5);
				$pdf->writeHTML($html, false, false, false, false, 'C');

				$pdf->Ln(2);
				
			}
			else
			{
				$pdf->Ln(2);

				if ($count == 1)
				{
					$posFoto = 7.5;
				}
				else if ($count == 2)
				{
					$posFoto = 11.1;
				}
				else if ($count == 3)
				{
					$posFoto = 14.2;
				}
				else if ($count == 4)
				{
					$posFoto = 17.5;
				}
				else if ($count == 5)
				{
					$posFoto = 21;
				}
				else if ($count == 6)
				{
					$posFoto = 24.5;
				}

			}

			if ($registro->foto_fotografia && $registro->foto_mime)
			{
				//echo '<img src="data:' . $registro->foto_mime . ';base64,' . $registro->foto_fotografia . '" />';
				$pdf->Image('@' . base64_decode($registro->foto_fotografia), 18.7, $posFoto, 1.5, 2);
			}

			// Set some content to print
			$html = '<table border="0" style="border-bottom: 1px solid #CCC;" width="95%">';
				$html .= '<tr>';
					$html .= '<td style="height: 50px; width: 60px;" valign="bottom">' . $registro->id_registro . '</td>';
					$html .= '<td style="height: 50px; width: 140px;" valign="bottom">' . $func->mayusStr($registro->nombre) . '</td>';
					$html .= '<td style="height: 50px; width: 140px;" valign="bottom">' . $func->mayusStr($registro->app) . '</td>';
					$html .= '<td style="height: 50px; width: 140px;" valign="bottom">' . $func->mayusStr($registro->apm) . '</td>';
					$html .= '<td style="height: 50px; width: 160px;" valign="bottom">' . $func->mayusStr($registro->pais) . '</td>';
					$html .= '<td style="height: 50px; width: 60px;" valign="bottom">&nbsp;</td>';
				$html .= '</tr>';
			$html .= '</table>';

			$pdf->setX(0.5);
			$pdf->writeHTML($html, false, false, false, false, 'C');
			// Nombre
			//$pdf->writeHTMLCell('', '', $x='', $y=$posFoto, $html, $border=0, $ln=1, $fill=0, $reseth=false, $align='C', $autopadding=false);

			//$posFoto += 3;

			// Categoria
			//$pdf->SetFont('helvetica', '', 14);
			//$categoria = $this->getNombreCategoria($idEvento, $acompanante->acm_clave);
			//$pdf->writeHTMLCell('40', '5', $x='56', $y='102', $categoria, $border=0, $ln=1, $fill=0, $reseth=false, $align='C', $autopadding=false);

			if ($count == 6)
			{
				$count = 0;

				$pdf->AddPage();

				continue;
			}

			$count++;
		}
		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$pdf->Output('repo_asistentes_' . time() . '.pdf', 'I');
	}

	/**
	 * Metodo que genera el PDF del congresista
	 */
	public function cargarCartaCompromiso($idEvento, $evento, $registro, $func, $ctrl = null)
	{
		require_once('./'. PATH_SMVC . '/' . PATH_EXT . '/tcpdf/config/lang/eng.php');
		require_once('./'. PATH_SMVC . '/' . PATH_EXT . '/tcpdf/tcpdf.php');

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator("Tecnoregistro");
		$pdf->SetAuthor($evento->evt_clave);
		$pdf->SetTitle('Gafete');

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		//set margins
		$pdf->SetMargins(0, 0, 0);

		//set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 0);

		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		//set some language-dependent strings
		$pdf->setLanguageArray($l);

		// ---------------------------------------------------------

		// set font
		$pdf->SetFont('helvetica', 'B', 12);

		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage(); // medidas en inch

		// X se mueve de izquiera a derecha 
		// Y se nueve de arriba a abajo

		// Set some content to print
		$nombre = $func->mayusStr($registro->nombre);
		$app = $func->mayusStr($registro->app);
		$apm = $func->mayusStr($registro->apm);
		$apellidos = trim($registro->app . ' ' . $registro->apm);
		$pais = $func->mayusStr($registro->pais_nombreEs);
		$emp_o_ins =  $func->mayusStr($registro->emp_o_ins);
		$categoria = $this->getNombreCategoria($idEvento, $registro->cat_registro);

		$img_file = './' . PATH_IMAGES . '/logo.png';
		//$border = array('LTRB' => array('width' => 5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(28, 334, 34)));
		$pdf->Image($img_file, 75, 30, 65, 20, '', '', '', false, 300, '', false, 0, 0);

		$pdf->writeHTMLCell('185', '', $x='0', $y='55', date('d/m/Y'), $border=0, $ln=0, $fill=0, $reseth=true, $align='R', $autopadding=false);
		$pdf->writeHTMLCell('', '', $x='0', $y='70', 'ASOCIACION MEXICANA DE INGENIERIA DE VIAS TERRESTRES A.C.', $border=0, $ln=0, $fill=0, $reseth=true, $align='C', $autopadding=false);
		$pdf->writeHTMLCell('155', '', $x='33', $y='77', 'PRESENTE', $border=0, $ln=0, $fill=0, $reseth=true, $align='L', $autopadding=false);

		// set font
		$pdf->SetFont('helvetica', 'B', 22);
		$pdf->writeHTMLCell('', '', $x='0', $y='87', 'CARTA COMPROMISO', $border=0, $ln=0, $fill=0, $reseth=true, $align='C', $autopadding=false);

		// set font
		$pdf->SetFont('helvetica', 'B', 12);
		$pdf->writeHTMLCell('155', '', $x='33', $y='107', 'DATOS DEL PARTICIPANTE:', $border=0, $ln=0, $fill=0, $reseth=true, $align='L', $autopadding=false);

		// Nombre
		$pdf->SetFont('helvetica', 'B', 12);
		$pdf->writeHTMLCell('155', '', $x='33', $y='113', $nombre . ' ' . $apellidos, $border=0, $ln=0, $fill=0, $reseth=true, $align='L', $autopadding=false);
		$pdf->writeHTMLCell('155', '', $x='33', $y='119', $emp_o_ins, $border=0, $ln=0, $fill=0, $reseth=true, $align='L', $autopadding=false);

		$pdf->SetFont('helvetica', '', 10);
		$pdf->writeHTMLCell('155', '', $x='33', $y='135', 'Yo <strong>' . $nombre . ' ' . $apellidos . '</strong> me comprometo a realizar el pago por un total de:', $border=0, $ln=0, $fill=0, $reseth=true, $align='L', $autopadding=false);

		$pdf->SetFont('helvetica', '', 10);
		$pdf->writeHTMLCell('155', '', $x='33', $y='144', 'MXN $ <strong>' . number_format($registro->costo_registro, 2, '.', ',') . '</strong>', $border=0, $ln=0, $fill=0, $reseth=true, $align='L', $autopadding=false);

		$pdf->SetFont('helvetica', 'B', 10);
		$pdf->writeHTMLCell('', '', $x='', $y='149', $func->num2letras($registro->costo_registro), $border=0, $ln=0, $fill=0, $reseth=true, $align='C', $autopadding=false);


		$pdf->SetFont('helvetica', '', 10);
		$pdf->writeHTMLCell('155', '', $x='33', $y='155', 'por concepto de inscripción como <strong>' . $categoria . '</strong> a la IX Seminario de Ingeniería Vial, durante el plazo establecido que vence al día 31 de Agosto del 2015.', $border=0, $ln=0, $fill=0, $reseth=true, $align='L', $autopadding=false);

		$pdf->SetFont('helvetica', '', 10);
		$pdf->writeHTMLCell('155', '', $x='33', $y='170', 'En caso de no cumplir con el pago de la fecha establecida me comprometo a pagar los intereses correspondientes generados de mi deuda.', $border=0, $ln=0, $fill=0, $reseth=true, $align='L', $autopadding=false);

		// set font
		$pdf->SetFont('helvetica', 'B', 12);
		$pdf->writeHTMLCell('155', '', $x='33', $y='195', 'ATENTAMENTE', $border=0, $ln=0, $fill=0, $reseth=true, $align='L', $autopadding=false);

		$pdf->SetFont('helvetica', 'B', 10);
		
		$pdf->writeHTMLCell('80', '', $x='33', $y='220', '<p style="border-top: 1px solid #000000;">Autorizó</p>', $border=0, $ln=0, $fill=0, $reseth=true, $align='C', $autopadding=false);
		
		$pdf->writeHTMLCell('80', '', $x='120', $y='220', '<p style="border-top: 1px solid #000000;">' . $nombre . ' ' . $apellidos . '</p>', $border=0, $ln=0, $fill=0, $reseth=true, $align='C', $autopadding=false);

		// define barcode style
		$style = array(
			'align' => 'C',
			'stretch' => false,
			'fitwidth' => true,
			'cellfitalign' => 'C',
			'border' => false,
			'hpadding' => 'auto',
			'vpadding' => 'auto',
			'fgcolor' => array(0,0,0),
			'bgcolor' => false, //array(255,255,255),
			'text' => true,
			'font' => 'helvetica',
			'fontsize' => 11,
			'stretchtext' => 5
		);

		// Codigo de barra
		$pdf->write1DBarcode($func->nombreImagenBarcode($registro->id_registro), 'C128A', 110, 230, '', 16, 0.4, $style, 'N');

		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$pdf->Output('gafete_' . time() . '.pdf', 'I');
	}
}