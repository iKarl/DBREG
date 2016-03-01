<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/models/home/
 * @version $Id: index.php 1.0 2012-05-12 00:20 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Model\Model_Controller as Model;

class Home_Model extends Model
{

	public function getEventos()
	{

		$eventos = array();

		$qry = "SELECT e.evt_idEvento, e.evt_nombre, e.evt_clave, e.evt_inicio, e.evt_termina, 
			e.evt_status, t.cte_nombre, s.ecs_nombre 
			FROM smc_eventos AS e 
			JOIN smc_eventoCatalogoStatus AS s ON (s.ecs_idStatus = e.evt_status) 
			JOIN smc_eventoCatalogoTipos AS t ON (t.cte_idTipoEvento = e.evt_idTipoEvento) 
			WHERE ecs_idStatus = 1 
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

	public function import()
	{
		$registros =  array();

		$qry = "SELECT * 
			FROM smc_reg_EVEN15 
			WHERE exportado = 0
		";

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				while ($registro = $this->db->hFetchObject())
				{
					$registros[] = $registro;
				}
			}
		}

		return $registros;
	}

	public function leido($id_registro)
	{
		$qry = "UPDATE smc_reg_EVEN15 SET 
			exportado = 1 
			WHERE id_registro = %d 
			LIMIT 1
		";

		$qry = sprintf($qry,
			$id_registro
		);

		if ($this->db->hQuery($qry))
		{
			return true;
		}
	}

	public function setRegistro($registro)
	{
		$qry = "INSERT INTO smc_reg_EVEN15 SET 
			idioma = 'es',
			id_costo = 1,
			folio_registro = %d,
			cat_registro = '%s',
			nombre = UPPER('%s'),
			app = UPPER('%s'),
			emp_o_ins = UPPER('%s'),
			pais = 146,
			nombre_pais = '%s',
			estado = '%s',
			ciudad = '%s',
			email = '%s',
			clave_asociada = '%s',
			tipo_registro = %d,
			genero = '%s',
			requiere_factura = 2,
			status = 'PAG',
			forma_pago = 'TC',
			fecha_registro = NOW(),
			realizado_en = 'IMP'
		";

		$qry = sprintf($qry,
			$registro->folio_registro,
			$registro->cat_registro,
			$registro->nombre,
			$registro->app,
			$registro->emp_o_ins,
			$registro->nombre_pais,
			$registro->estado,
			$registro->ciudad,
			$registro->email,
			$registro->clave_asociada,
			$registro->tipo_registro,
			$registro->genero
		);

		if ($this->db->hQuery($qry))
		{
			return $registro->id_registro;
		}
	}
}
?>