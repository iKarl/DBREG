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

class Index_Model extends Model
{
	public function searchPaterno($name) {
		$registers = array();

		$qry = "SELECT id_registro, titulo, nombre, app, apm, emp_o_ins, status, impresion_gafete 
			FROM smc_reg_CIPE14 
			WHERE app LIKE '%" . $name . "%' 
			ORDER BY app, apm, nombre ASC
			LIMIT 50";

		if ($this->db->hQuery($qry)) {
			if ($this->db->hNumRows() >= 1) {
				while ($register = $this->db->hFetchObject()) {
					$registers[] = $register;
				}
			}
		}

		return $registers;
	}

	public function searchNombre($name) {
		$registers = array();
		
		$qry = "SELECT id_registro, titulo, nombre, app, apm, emp_o_ins, status, impresion_gafete 
			FROM smc_reg_CIPE14 
			WHERE nombre LIKE '%" . $name . "%' 
			ORDER BY app, apm, nombre ASC
			LIMIT 50";

		if ($this->db->hQuery($qry)) {
			if ($this->db->hNumRows() >= 1) {
				while ($register = $this->db->hFetchObject()) {
					$registers[] = $register;
				}
			}
		}

		return $registers;
	}

	public function searchEmpresa($name) {
		$registers = array();
		
		$qry = "SELECT id_registro, titulo, nombre, app, apm, emp_o_ins, status, impresion_gafete 
			FROM smc_reg_CIPE14 
			WHERE emp_o_ins LIKE '%" . $name . "%' 
			ORDER BY app, apm, nombre ASC
			LIMIT 50";

		if ($this->db->hQuery($qry)) {
			if ($this->db->hNumRows() >= 1) {
				while ($register = $this->db->hFetchObject()) {
					$registers[] = $register;
				}
			}
		}

		return $registers;
	}

	public function searchAmbos($paterno, $nombre) {
		$registers = array();
		
		$qry = "SELECT id_registro, titulo, nombre, app, apm, emp_o_ins, status, impresion_gafete 
			FROM smc_reg_CIPE14 
			WHERE app LIKE '%" . $paterno . "%' 
			AND nombre LIKE '%" . $nombre . "%' 
			ORDER BY app, apm, nombre ASC
			LIMIT 50";

		if ($this->db->hQuery($qry)) {
			if ($this->db->hNumRows() >= 1) {
				while ($register = $this->db->hFetchObject()) {
					$registers[] = $register;
				}
			}
		}

		return $registers;
	}

	public function get($registerId) {
		$register = array();
		if (!empty($registerId)) {
			$qry = "SELECT * 
				FROM smc_reg_CIPE14 
				WHERE id_registro = " . $registerId .  "
				LIMIT 1";

			if ($this->db->hQuery($qry)) {
				if ($this->db->hNumRows() == 1) {
					$register = $this->db->hFetchObject();
				}
			}
		}
		return $register;
	}

	public function set($reg) {
		$folio = time();
		$qry = "INSERT INTO smc_reg_CIPE14 SET 
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
			`impresion_constancia` = %d ,

			`genero` = '%s' ,
			`titulo` = '%s' ,
			`nombre` = UPPER('%s') ,
			`app` = UPPER('%s') ,
			`apm` = UPPER('%s') ,
			`telefono_particular` = '%s' ,
			`rfc` = UPPER('%s') ,

			`emp_o_ins` = UPPER('%s') ,
			`cargo` = '%s' ,

			`comentarios` = '%s' ,
			`email` = '%s' ,

			`politicas_terminos_condiciones` = 1 ,
			`forma_pago` = '%s' ,
			`fecha_registro` = '%s' ,
			`status` = '%s' ,
			`realizado_en` = 'STO'
		";

		$qry = sprintf($qry,
			$folio,
			$folio,
			"EVEN15",
			$folio,
			$reg->categoria,
			'es',
			$reg->clave_asociada,
			5,
			$reg->costo,
			$reg->costo,
			"MXN",
			0,
			$reg->impresion_constancia,

			"",
			$reg->titulo,
			$reg->firstName,
			$reg->lastName,
			$reg->secondLastName,
			$reg->telefono,
			"",
			$reg->empresa,
			"",

			$reg->comentarios,
			$reg->email,

			"EF",
			date("Y-m-d H:s:i"),
			"REG" // $reg->status
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

	public function update($reg) {
		$qry = "UPDATE smc_reg_CIPE14 SET 
			`cat_registro` = '%s' ,
			`titulo` = '%s' ,
			`nombre` = UPPER('%s') ,
			`app` = UPPER('%s') ,
			`apm` = UPPER('%s') ,
			`emp_o_ins` = UPPER('%s') ,
			`telefono_particular` = '%s' ,
			`comentarios` = '%s' ,
			`email` = '%s' ,
			`clave_asociada` = '%s' ,
			`fecha_modificacion` = '%s' ,
			`status` = '%s'
			WHERE id_registro = %d 
			LIMIT 1
		";

		$qry = sprintf($qry,
			$reg->cat_registro,
			$reg->titulo,
			$reg->nombre,
			$reg->app,
			$reg->apm,
			$reg->emp_o_ins,
			$reg->telefono_particular,
			$reg->comentarios,
			$reg->email,
			$reg->clave_asociada,
			date("Y-m-d H:i:s"),
			$reg->status,
			$reg->id_registro
		);

		if ($this->db->hQuery($qry)) {
			if ($this->db->hAffectedRows() == 1) {
				return true;
			}
		}

		return false;
	}

	public function asistencia($registerId) {
		if (!empty($registerId)) {
			$now = date("Y-m-d H:i:s");

			$qry = 'UPDATE smc_reg_CIPE14 SET 
				impresion_gafete = 1,
				fecha_impresion_gafete = "' . $now . '",
				impresion_total_gafete = impresion_total_gafete + 1
				WHERE id_registro = ' . $registerId . ' LIMIT 1';

			if ($this->db->hQuery($qry)) {
				if ($this->db->hAffectedRows() == 1) {
					return true;
				}
			}
		}
		
		return false;
	}

	public function validarFolio($folio) {
		$register = false;
		if (!empty($folio)) {
			$qry = "SELECT * 
				FROM smc_reg_CIPE14 
				WHERE clave_asociada = " . $folio .  "
				LIMIT 1";

			if ($this->db->hQuery($qry)) {
				if ($this->db->hNumRows() == 1) {
					$register = true;
				}
			}
		}
		return $register;
	}

	public function validarFolioId($folio, $id) {
		$register = false;
		
		$qry = "SELECT * 
			FROM smc_reg_CIPE14 
			WHERE clave_asociada = " . $folio .  "
			AND id_registro <> " . $id .  "
			LIMIT 1";

		if ($this->db->hQuery($qry)) {
			if ($this->db->hNumRows() == 1) {
				$register = true;
			}
		}

		return $register;
	}

	public function prueba($post) {
		$qry = "INSERT INTO prueba SET 
			reader_name = '%s',
			mac_address = '%s',
			line_ending = '%s',
			field_delim = '%s',
			field_names = '%s',
			field_values = '%s',
			req_post = '%s'
		";
		$arr = print_r($post, true);

		$qry = sprintf($qry,
			$post['reader_name'],
			$post['mac_address'],
			$post['line_ending'],
			$post['field_delim'],
			$post['field_names'],
			$post['field_values'],
			$arr
		);

		if ($this->db->hQuery($qry))
		{
			return true;
		}
	}
}
?>