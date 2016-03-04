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
			FROM smc_reg_REG15 
			WHERE leido = 0
			LIMIT 20
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
		$qry = "UPDATE smc_reg_REG15 SET 
			leido = 1 
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
		$qry = "INSERT INTO smc_reg_REG15 SET 
			id_registro = %d
			id_tag = '%s',
			folio_registro = %d,
			clave_evento = '%s',
			clave_registro = '%s',
			cat_registro = '%s',
			idioma = 'es',
			id_costo = 5,
			titulo = UPPER('%s'),
			nombre = UPPER('%s'),
			app = UPPER('%s'),
			apm = UPPER('%s'),
			emp_o_ins = UPPER('%s'),
			cargo = '%s',
			email = '%s',
			pais = %d,
			nombre_pais = '%s',
			cp = '%s',
			estado = '%s',
			ciudad = '%s',
			requiere_factura = 2,
			politicas_terminos_condiciones = 1,
			fecha_registro = '%s'
			status = '%s',
			impresion_gafete = %d,
			comentarios = '%s',
			gerente = '%s',
			representante = '%s',
			aeropuerto = '%s',
			hotel = '%s',
			contacto_sac = '%s',
			leido = %d
			forma_pago = '%s',
			realizado_en = '%s'
		";

		$qry = sprintf($qry,
			$registro->id_registro,
			$registro->id_tag,
			$registro->folio_registro,
			$registro->clave_evento,
			$registro->clave_registro,
			$registro->cat_registro,
			$registro->titulo,
			$registro->nombre,
			$registro->app,
			$registro->apm,
			$registro->emp_o_ins,
			$registro->cargo,
			$registro->email,
			$registro->pais,
			$registro->nombre_pais,
			$registro->cp,
			$registro->estado,
			$registro->ciudad,
			$registro->fecha_registro,
			$registro->status,
			$registro->impresion_gafete,
			$registro->comentarios,
			$registro->gerente,
			$registro->representante,
			$registro->aeropuerto,
			$registro->hotel,
			$registro->contacto_sac,
			$registro->leido,
			$registro->forma_pago,
			$registro->realizado_en
		);

		if ($this->db->hQuery($qry))
		{
			return $registro->id_registro;
		}
	}
}
?>