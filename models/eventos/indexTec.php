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
			e.evt_status, t.cte_nombre, s.ecs_nombre 
			FROM smc_eventos AS e 
			JOIN smc_eventoCatalogoStatus AS s ON (s.ecs_idStatus = e.evt_status) 
			JOIN smc_eventoCatalogoTipos AS t ON (t.cte_idTipoEvento = e.evt_idTipoEvento) 
			WHERE 1 
			ORDER BY evt_nombre ASC
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

	public function validarClave($clave)
	{
		$clave = $this->db->hEscapeString($clave);

		$qry = "SELECT evt_idEvento 
			FROM smc_eventos 
			WHERE evt_clave = '%s'";

		$qry = sprintf($qry, $clave);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				return true;
			}
		}

		return false;
	}

	public function validarNombre($nombre)
	{
		$nombre = $this->db->hEscapeString($nombre);

		$qry = "SELECT evt_idEvento 
			FROM smc_eventos 
			WHERE evt_nombre = '%s'";

		$qry = sprintf($qry, $nombre);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				return true;
			}
		}

		return false;
	}

	public function insertarEvento($post, $idUsuario)
	{
		$idEvento = 0;

		$tipoEvento = $this->db->hEscapeString($post['evt_idTipoEvento']);
		$clave = $this->db->hEscapeString($post['evt_clave']);
		$nombre = $this->db->hEscapeString($post['evt_nombre']);
		$inicia = $this->db->hEscapeString($post['evt_inicio']);
		$termina = $this->db->hEscapeString($post['evt_termina']);
		$divisa = $this->db->hEscapeString($post['evt_divisa']);
		$iva = $this->db->hEscapeString($post['evt_iva']);
		$status = $this->db->hEscapeString($post['evt_status']);

		$nombreTablaAsistentes = "smc_reg_" . $clave;

		$qry = "INSERT INTO smc_eventos SET 
			evt_idTipoEvento = %d,
			evt_clave = '%s',
			evt_nombre = '%s',
			evt_inicio = '%s',
			evt_termina = '%s',
			evt_divisa = '%s',
			evt_iva = '%s',
			evt_nombreTablaAsistentes = '%s',
			evt_fechaAlta = NOW(),
			evt_usuarioAlta = %d,
			evt_status = %d
		";

		$qry = sprintf($qry, $tipoEvento, $clave, $nombre, $inicia, $termina, $divisa, $iva, $nombreTablaAsistentes,
			$idUsuario, $status
		);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				$idEvento = $this->db->hInsertID();
				// Creamos la tabla que contendra los registros del evento
				$this->crearTablaRegistros($clave);
				// Creamos la tabla que contendra los acompañantes de los registrados
				$this->crearTablaAcompanantes($clave);
				// Creamos la tabla que contendra los ítems adquiridos por los asistentes
				$this->crearTablaItems($clave);
				// Creamos la tabla que contendra las fotografias de los asistentes
				$this->crearTablaFotografias($clave);
				// Creamos la tabla que contendra las reservaciones de los asistente
				$this->crearTablaReservaciones($clave);
				// Creamos la tabla que contendra los datos de acceso a los salones
				$this->crearTablaRFID($clave);
				// Creamos la tabla de caja
				$this->crearTablaCaja($clave);
				// Insertar idioma por default
				$this->insertarIdioma($idEvento);
				// Insertar status de registros por defecto
				$this->insertarStatusRegistros($idEvento, $idUsuario);
				// Insertar formas de pago por defecto
				$this->insertarFormasPago($idEvento, $idUsuario);
			}
		}

		return $idEvento;
	}

	private function crearTablaRegistros($clave)
	{
		$qry = "CREATE TABLE IF NOT EXISTS `smc_reg_%s` (
			`id_registro` INT(11) NOT NULL AUTO_INCREMENT ,
			`id_tag` VARCHAR(24) NULL COMMENT 'ID para el RFID' ,
			`folio_registro` INT(11) NULL COMMENT 'Folio de registro unico' ,
			`clave_evento` VARCHAR(15) NOT NULL ,
			`clave_registro` VARCHAR(32) NOT NULL ,
			`cat_registro` VARCHAR(10) NOT NULL COMMENT 'Categoría de registro en clave Eje.: SC = Socio, NS = No Socio, BE = Becado' ,
			`idioma` CHAR(2) NOT NULL DEFAULT 'es' ,
			`clave_asociada` VARCHAR(15) NULL ,
			`id_costo` TINYINT(1) NOT NULL COMMENT 'ID del costo con el que se aplica el registro' ,
			`costo_registro` DECIMAL(9,2) NULL DEFAULT '0.00' ,
			`costo_adicional` DECIMAL(9,2) NULL DEFAULT '0.00' ,
			`costo_total` DECIMAL(9,2) NULL DEFAULT '0.00' ,
			`divisa` VARCHAR(3) NULL DEFAULT 'MXN' COMMENT 'Eje.: MXN = peso mexicano, USD = Dolar estadounidense' ,
			`tipo_cambio_divisa` DECIMAL(5,2) NULL DEFAULT '0.00' ,
			`genero` CHAR(1) NULL COMMENT 'F = Femenino, M = Masculino F = Female, M = Male' ,
			`titulo` VARCHAR(20) NULL ,
			`nombre` VARCHAR(100) NOT NULL ,
			`app` VARCHAR(50) NOT NULL ,
			`apm` VARCHAR(50) NULL ,
			`curp` VARCHAR(20) NULL ,
			`rfc` VARCHAR(20) NULL ,
			`emp_o_ins` VARCHAR(125) NULL COMMENT 'Empresa o institución o instituto' ,
			`cargo` VARCHAR(75) NULL ,
			`email` VARCHAR(125) NOT NULL ,
			`pais` SMALLINT(5) NULL ,
			`cp` VARCHAR(10) NULL ,
			`estado` VARCHAR(40) NULL ,
			`del_o_mun` VARCHAR(40) NULL COMMENT 'Delegación o Municipio solo para México' ,
			`colonia` VARCHAR(40) NULL ,
			`ciudad` VARCHAR(40) NULL ,
			`direccion` VARCHAR(125) NULL COMMENT 'Calle y número' ,
			`lada_telefono` VARCHAR(4) NULL COMMENT 'Lada' ,
			`telefono_particular` VARCHAR(25) NULL ,
			`telefono_movil` VARCHAR(25) NULL ,
			`requiere_factura` TINYINT(1) NOT NULL DEFAULT 2 COMMENT '1 = Si requiere, 2 = No requiere' ,
			`razon_social_RS` VARCHAR(200) NULL ,
			`rfc_RS` VARCHAR(20) NULL COMMENT 'Solo para México' ,
			`pais_RS` SMALLINT(5) NULL ,
			`cp_RS` VARCHAR(10) NULL ,
			`estado_RS` VARCHAR(40) NULL ,
			`del_o_mun_RS` VARCHAR(40) NULL COMMENT 'Delegación o Municipio solo para México' ,
			`colonia_RS` VARCHAR(40) NULL ,
			`ciudad_RS` VARCHAR(40) NULL ,
			`direccion_RS` VARCHAR(125) NULL COMMENT 'Calle y número' ,
			`lada_telefono_RS` VARCHAR(4) NULL COMMENT 'Lada' ,
			`telefono_RS` VARCHAR(25) NULL ,
			`fax_RS` VARCHAR(25) NULL ,
			`email_RS` VARCHAR(125) NULL ,
			`politicas_terminos_condiciones` TINYINT(1) NOT NULL DEFAULT 2 COMMENT '1 = Si acepta, 2 = No acepta' ,
			`forma_pago` VARCHAR(2) NULL COMMENT 'DB = Deposito Bancario, TC = Tarjeta de Crédito, PP = PayPal, EF = Efectivo, OT = Otro' ,
			`fecha_registro` DATETIME NOT NULL ,
			`status` VARCHAR(10) NOT NULL DEFAULT 'REG' COMMENT 'REG = Registro, PEN = Pendiente, PAG = Pagado, CAN = Cancelado' ,
			`impresion_gafete` TINYINT(1) NULL DEFAULT 0 COMMENT '0 = no impreso, 1 = impreso' ,
			`fecha_impresion_gafete` DATETIME NULL ,
			`impresion_total_gafete` TINYINT(2) NULL DEFAULT 0 COMMENT 'Total impresiones del gafete' ,

			`impresion_constancia` TINYINT(1) NULL DEFAULT 0 COMMENT '0 = no impresa, 1 = impresa' ,
			`fecha_impresion_constancia` DATETIME NULL ,
			`impresion_total_constancia` TINYINT(2) NULL DEFAULT 0 COMMENT 'Total impresiones de la constancia' ,
			`folio_constancia` VARCHAR(20) NULL ,

			`comentarios` TINYTEXT NULL ,
			`realizado_en` VARCHAR(3) NULL COMMENT 'WEB = Hecho en la pagina, STO = Hecho en sitio' , 
			`idUsuario_alta` INT(11) NULL COMMENT 'ID del usuario que dio de alta el registro en sitio' ,
			`fecha_modificacion` DATETIME NULL COMMENT 'Fecha de la última modificación' ,
			`idUsuario_modifico` INT(11) NULL COMMENT 'ID del usuario que realizo la última modificación' , 
			PRIMARY KEY (`id_registro`) ,
			INDEX `clave_reg` (`clave_registro` ASC, `cat_registro` ASC, `clave_asociada` ASC, `nombre` ASC, `app` ASC, `apm` ASC),
			INDEX `index_id_tag` (`id_tag` ASC) )
			ENGINE = InnoDB
			DEFAULT CHARACTER SET = utf8
			COLLATE = utf8_unicode_ci
			COMMENT = 'Tabla de registros del evento %s'";

		$qry = sprintf($qry, $clave, $clave);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}
		}

		return false;
	}

	private function crearTablaAcompanantes($clave)
	{
		$qry = "CREATE TABLE IF NOT EXISTS `smc_reg_%s_acom` (
			`id_acompanante` INT(11) AUTO_INCREMENT NOT NULL ,
			`acm_idRegistro` INT(11) NOT NULL ,
			`acm_idInterno` TINYINT(1) NULL COMMENT 'ID interno del acompañante' ,
			`acm_clave` VARCHAR(10) NOT NULL ,
			`acm_genero` CHAR(1) NULL ,
			`acm_titulo` VARCHAR(20) NULL ,
			`acm_nombre` VARCHAR(90) NOT NULL ,
			`acm_app` VARCHAR(45) NOT NULL ,
			`acm_apm` VARCHAR(45) NULL ,
			`acm_id_costo` TINYINT(1) NOT NULL COMMENT 'ID del costo con el que se aplica el acompañante' ,
			`acm_costo` DECIMAL(9,2) NULL DEFAULT '0.00' ,
			`acm_fechaModificacion` DATETIME NULL ,
			`acm_usuarioModifico` INT(11) NULL ,
			`acm_comentarios` TEXT NULL ,
			`acm_status` VARCHAR(10) NULL ,
			`acm_impresion_gafete` TINYINT(1) NULL DEFAULT 0 COMMENT '0 = no impreso, 1 = impreso' ,
			`acm_fecha_impresion_gafete` DATETIME NULL ,
			`acm_impresion_total_gafete` TINYINT(2) NULL DEFAULT 0 COMMENT 'Total impresiones de gafete' ,
			INDEX (`id_acompanante`,`acm_idRegistro` ASC) ,
			PRIMARY KEY (id_acompanante))
			ENGINE = InnoDB
			DEFAULT CHARACTER SET = utf8
			COLLATE = utf8_unicode_ci, 
			COMMENT = 'Tabla de acompañantes de los asistenes' 
		";

		$qry = sprintf($qry, $clave, $clave, $clave, $clave);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}
		}

		return false;
	}

	private function crearTablaItems($clave)
	{
		$qry = "CREATE TABLE IF NOT EXISTS `smc_reg_%s_items` (
			`id_item` INT(11) AUTO_INCREMENT NOT NULL ,
			`item_idRegistro` INT(11) NOT NULL ,
			`item_idInterno` TINYINT(1) NULL COMMENT 'ID interno del ítem' ,
			`item_clave` VARCHAR(10) NOT NULL ,
			`item_cantidad` INT(11) NOT NULL DEFAULT 0 COMMENT 'Numero total de este ítem adquiridos' ,
			`item_id_costo` TINYINT(1) NOT NULL COMMENT 'ID del costo con el que se aplica el ítem' ,
			`item_costo_unitario` DECIMAL(9,2) NOT NULL DEFAULT '0.00' ,
			`item_costo_total` DECIMAL(9,2) NOT NULL DEFAULT '0.00' ,
			`item_comentarios` TEXT NULL ,
			`item_fechaModificacion` DATETIME NULL ,
			`item_usuarioModifico` INT(11) NULL ,
			`item_status` VARCHAR(10) NULL ,
			INDEX (`id_item`,`item_idRegistro` ASC) ,
			PRIMARY KEY (`id_item`) )
			ENGINE = InnoDB
			DEFAULT CHARACTER SET = utf8
			COLLATE = utf8_unicode_ci, 
			COMMENT = 'Tabla de los ítems adquiridos por los asistentes'
		";

		$qry = sprintf($qry, $clave, $clave, $clave, $clave);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}
		}

		return false;
	}

	private function crearTablaFotografias($clave)
	{
		$qry = "CREATE TABLE IF NOT EXISTS `smc_reg_%s_fotos` (
			`foto_idRegistro` INT(11) NULL ,
			`foto_mime` VARCHAR(50) NOT NULL ,
			`foto_fotografia` BLOB NOT NULL ,
			PRIMARY KEY (`foto_idRegistro`) )
			ENGINE = InnoDB
			DEFAULT CHARACTER SET = utf8
			COLLATE = utf8_unicode_ci, 
			COMMENT = 'Fotografias de los registros'
		";

		$qry = sprintf($qry, $clave);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}
		}

		return false;
	}

	private function crearTablaReservaciones($clave)
	{
		$qry = "CREATE TABLE IF NOT EXISTS `smc_reg_%s_reservaciones` (
			  `id_reservacion` INT(11) AUTO_INCREMENT NOT NULL ,
			  `res_idRegistro` INT(11) NOT NULL ,
			  `res_idHotel` INT(11) NULL ,
			  `res_idHabitacion` SMALLINT(4) NULL ,
			  `res_idCostos` TINYINT(3) NOT NULL ,
			  `res_folio` VARCHAR(15) NOT NULL COMMENT 'Folio de la reservación' ,
			  `res_clave` VARCHAR(32) NOT NULL COMMENT 'Clave de reservación' ,
			  `res_llegada` DATETIME NOT NULL COMMENT 'Fecha de llegada' ,
			  `res_salida` DATETIME NOT NULL COMMENT 'Fecha de salida' ,
			  `res_numHabitaciones` SMALLINT(4) NULL DEFAULT 0 ,
			  `res_numAdultos` VARCHAR(255) NULL DEFAULT '{}' ,
			  `res_numMenores` VARCHAR(255) NULL DEFAULT '{}' ,
			  `res_costoTotal` DECIMAL(9,2) NOT NULL DEFAULT '0.00' ,
			  `res_anticipo` DECIMAL(9,2) NULL DEFAULT '0.00' ,
			  `res_saldo` DECIMAL(9,2) NULL DEFAULT '0.00' ,
			  `res_fechaAlta` DATETIME NOT NULL ,
			  `res_usuarioAlta` INT(11) NULL ,
			  `res_fechaModificacion` DATETIME NULL ,
			  `res_usuarioModifico` INT(11) NULL ,
			  `res_status` VARCHAR(10) NOT NULL DEFAULT 'RES' COMMENT 'RES = Reservación, PAG = Pagada, COR = Cortecia, CAN = cancelada' ,
			  INDEX (`id_reservacion`,`res_idRegistro` ASC) )
			ENGINE = InnoDB
			DEFAULT CHARACTER SET = utf8
			COLLATE = utf8_unicode_ci, 
			COMMENT = 'Contiene las reservaciones de los registrados'
		";

		$qry = sprintf($qry, $clave, $clave);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}
		}

		return false;
	}

	private function crearTablaRFID($clave)
	{
		$qry = "CREATE TABLE IF NOT EXISTS `smc_reg_%s_RFID` (
			  `id_RFID` INT(11) NOT NULL AUTO_INCREMENT ,
			  `id_tag` VARCHAR(24) NOT NULL COMMENT 'ID para el RFID' ,
			  `id_registro` INT(11) NULL COMMENT 'ID del registro' ,
			  `fecha_acceso` VARCHAR(10) NOT NULL ,
			  `hora_acceso` VARCHAR(5) NOT NULL ,
			  `fechaHora_acceso` DATETIME NULL ,
			  `numero_antena_acceso` TINYINT(3) NULL ,
			  `fechaHora_salida` DATETIME NULL ,
			  `numero_antena_salida` TINYINT(3) NULL ,
			  `nombre_sesion` VARCHAR(255) NULL ,
			  `status` TINYINT(1) NULL DEFAULT 0 ,
			  INDEX `index_id_tag` (`id_tag` ASC) ,
			  PRIMARY KEY (`id_RFID`) )
			ENGINE = InnoDB;
		";

		$qry = sprintf($qry, $clave);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}
		}

		return false;
	}

	private function crearTablaCaja($clave)
	{
		$qry = "CREATE TABLE IF NOT EXISTS `smc_reg_%s_caja` (
			  `id_cobro` INT(11) NOT NULL AUTO_INCREMENT ,
			  `id_usuario` INT(11) NOT NULL ,
			  `id_registro` INT(11) NOT NULL ,
			  `forma_pago` VARCHAR(2) NOT NULL COMMENT 'Forma de pago' ,
			  `costo_original` DECIMAL(9,2) NOT NULL ,
			  `descuento` DECIMAL(9,2) NULL ,
			  `costo_total` DECIMAL(9,2) NOT NULL ,
			  `folio_pago` VARCHAR(25) NOT NULL ,
			  `comentarios` TEXT NULL ,
			  `fecha_cobro` DATETIME NOT NULL ,
			  `status` VARCHAR(5) NOT NULL ,
			  INDEX `index_id_usuario` (`id_usuario` ASC) ,
			  PRIMARY KEY (`id_cobro`) )
			ENGINE = InnoDB;
		";

		$qry = sprintf($qry, $clave);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}
		}

		return false;
	}

	private function insertarIdioma($idEvento)
	{
		$qry = "INSERT INTO smc_eventoIdiomas SET 
			eis_idEvento = %d,
			eis_idioma = 'es',
			eis_nombre = 'Español'
		";

		$qry = sprintf($qry, $idEvento);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hAffectedRows() == 1)
			{
				return true;
			}
		}

		return false;
	}

	private function insertarStatusRegistros($idEvento, $idUsuario)
	{
		$status = array(
			1 => array(
				"clave" => "REG",
				"nombre" => "Registro"
			),
			2 => array(
				"clave" => "PEN",
				"nombre" => "Pendiente"
			),
			3 => array(
				"clave" => "PAG",
				"nombre" => "Pagado"
			),
			4 => array(
				"clave" => "CAN",
				"nombre" => "Cancelado"
			),
			5 => array(
				"clave" => "COR",
				"nombre" => "Cortesia"
			)
		);

		$qry = $this->db->prepare("INSERT INTO smc_eventoStatusRegistros SET 
			esr_idEvento = ?,
			esr_idStatus = ?,
			esr_clave = ?,
			esr_nombre = ?,
			esr_fechaAlta = NOW(),
			esr_usuarioAlta = ?
		");

		$qry->bind_param('sssss', $idEvento, $id, $clave, $nombre, $idUsuario);

		foreach ($status as $key => $value)
		{
			$id = $key;
			$clave = $value['clave'];
			$nombre = $value['nombre'];
			$qry->execute();
		}
	}

	private function insertarFormasPago($idEvento, $idUsuario)
	{
		$formasPagos = array("DB", "TC", "EF", "PP");

		$qry = $this->db->prepare("INSERT INTO smc_eventoFormasPago SET 
			efp_idEvento = ?,
			efp_clave = ?,
			efp_fechaAlta = NOW(),
			efp_usuarioAlta = ?
		");

		$qry->bind_param('sss', $idEvento, $clave, $idUsuario);

		foreach ($formasPagos as $valor)
		{
			$clave = $valor;
			$qry->execute();
		}

		// Inserta nombres
		$nombres = array(
			1 => array(
				"clave" => "DB",
				"nombre" => "Deposito bancario"
			),
			2 => array(
				"clave" => "TC",
				"nombre" => "Tarjeta de Crédito"
			),
			3 => array(
				"clave" => "EF",
				"nombre" => "Efectivo"
			),
			4 => array(
				"clave" => "PP",
				"nombre" => "Pay-Pal"
			)
		);

		$qryFPN = $this->db->prepare("INSERT INTO smc_eventoFormasPagoNombres SET 
			fpn_idEvento = ?,
			fpn_idNombre = 1,
			fpn_clave = ?,
			fpn_idioma = 'es',
			fpn_nombre = ?
		");

		$qryFPN->bind_param('sss', $idEvento, $clave, $nombre);

		foreach ($nombres as $key => $valor)
		{
			$clave = $valor['clave'];
			$nombre = $valor['nombre'];
			$qryFPN->execute();
		}


	}

}