SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `smcTecnoRegistro` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ;
USE `smcTecnoRegistro` ;

-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_catCodigosPostales`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_catCodigosPostales` (
  `cp_idCodigoPostal` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `cp_codigoPostal` VARCHAR(10) NOT NULL ,
  `cp_asenta` VARCHAR(150) NOT NULL COMMENT 'Nombre de la unidad, colonia, fraccionamiento, etc.' ,
  `cp_tipoAsenta` VARCHAR(100) NOT NULL ,
  `cp_nombreMnpio` VARCHAR(100) NOT NULL ,
  `cp_nombreEstado` VARCHAR(100) NOT NULL ,
  `cp_nombreCiudad` VARCHAR(100) NULL ,
  PRIMARY KEY (`cp_idCodigoPostal`) ,
  INDEX `index2` (`cp_codigoPostal` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Tabla que contiene todos los códigos postales' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_catPaises`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_catPaises` (
  `pais_idPais` SMALLINT(5) NOT NULL AUTO_INCREMENT ,
  `pais_isoNum` SMALLINT(5) NOT NULL ,
  `pais_iso2` CHAR(2) NOT NULL ,
  `pais_iso3` CHAR(3) NOT NULL ,
  `pais_nombre` VARCHAR(90) NOT NULL ,
  `pais_imagen` VARCHAR(100) NULL ,
  `pais_fechaModificacion` DATETIME NULL ,
  `pais_usuarioModifico` INT(11) NULL ,
  PRIMARY KEY (`pais_idPais`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Contiene los paises del mundo' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_usuarios`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_usuarios` (
  `usr_idUsuario` INT(11) NOT NULL AUTO_INCREMENT ,
  `usr_nombre` VARCHAR(90) NOT NULL ,
  `usr_app` VARCHAR(45) NOT NULL ,
  `usr_apm` VARCHAR(45) NULL ,
  `usr_genero` VARCHAR(1) NOT NULL COMMENT 'F = Femenino, M = Masculino' ,
  `usr_usuario` VARCHAR(120) NOT NULL COMMENT 'Correo electrónico' ,
  `usr_password` VARCHAR(32) NOT NULL ,
  `usr_tiempoSesion` INT(11) NULL DEFAULT 0 ,
  `usr_enSesion` TINYINT(1) NOT NULL DEFAULT 0 ,
  `usr_ultimaSesion` DATETIME NULL ,
  `usr_fechaAlta` DATETIME NOT NULL ,
  `usr_usuarioAlta` INT(11) NOT NULL ,
  `usr_fechaModificacion` DATETIME NULL ,
  `usr_usuarioModifico` INT(11) NULL ,
  `usr_status` TINYINT(1) NOT NULL COMMENT '1 Habilitado, 2 Inhabilitado' ,
  PRIMARY KEY (`usr_idUsuario`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Usuarios del sistema' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_secciones`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_secciones` (
  `sec_idSeccion` TINYINT(2) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `sec_nombre` VARCHAR(120) NOT NULL ,
  `sec_url` VARCHAR(75) NOT NULL COMMENT 'Dirección url de acceso al área en el sistema' ,
  `sec_descripcion` TEXT NULL ,
  `sec_fechaAlta` DATETIME NOT NULL ,
  `sec_usuarioAlta` INT(11) NOT NULL ,
  `sec_fechaModificacion` DATETIME NULL ,
  `sec_usuarioModifico` INT(11) NULL ,
  `sec_status` TINYINT(1) NOT NULL COMMENT '1 Habilitado, 2 Inhabilitado' ,
  PRIMARY KEY (`sec_idSeccion`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Contiene las diferentes áreas del sistema' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_seccionesNiveles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_seccionesNiveles` (
  `nvl_idSeccion` TINYINT(2) UNSIGNED NOT NULL COMMENT 'ID del área a la que pertenece el nivel' ,
  `nvl_nivel` TINYINT(1) NOT NULL COMMENT 'Numero del nivel: 1 Administrador, 2 Moderador, 3 Consultor' ,
  `nvl_nombre` VARCHAR(75) NOT NULL COMMENT 'Nombre del nivel' ,
  `nvl_descripcion` VARCHAR(255) NULL ,
  INDEX `fk_smc_nivielesAreas_smc_areas1` (`nvl_idSeccion` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Niveles de las secciones' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_seccionesAccesoUsuarios`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_seccionesAccesoUsuarios` (
  `sau_idUsuario` INT(11) NOT NULL COMMENT 'ID del usuario' ,
  `sau_idSeccion` TINYINT(2) UNSIGNED NOT NULL COMMENT 'ID del área a la que tiene acceso el usuario' ,
  `sau_idNivel` VARCHAR(45) NULL COMMENT 'ID del nivel de acceso al área' ,
  INDEX `fk_smc_usuarioAccesoAreas_smc_usuarios` (`sau_idUsuario` ASC) ,
  INDEX `fk_smc_usuarioAreaAcceso_smc_areas1` (`sau_idSeccion` ASC) ,
  INDEX `fk_smc_usuarioAreaAcceso_smc_nivielesAreas1` (`sau_idNivel` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Contiene los permisos de acceso a las áreas' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_catIdiomas`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_catIdiomas` (
  `idio_idIdioma` TINYINT(2) NOT NULL AUTO_INCREMENT ,
  `idio_clave` VARCHAR(5) NOT NULL ,
  `idio_nombre` VARCHAR(45) NOT NULL ,
  `idio_fechaAlta` DATETIME NOT NULL ,
  `idio_usuarioAlta` INT(11) NOT NULL ,
  `idio_fechaModificacion` DATETIME NULL ,
  `idio_usuarioModifico` INT(11) NULL ,
  PRIMARY KEY (`idio_idIdioma`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Contiene los idiomas que puede manejar el sistema' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_eventos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_eventos` (
  `evt_idEvento` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `evt_idTipoEvento` TINYINT(2) NOT NULL COMMENT 'Indica el tipo de evento' ,
  `evt_nombre` VARCHAR(175) NOT NULL ,
  `evt_clave` VARCHAR(15) NOT NULL COMMENT 'Clave del evento' ,
  `evt_idioma` VARCHAR(2) NOT NULL DEFAULT 'es' COMMENT 'Idioma preferente para el evento' ,
  `evt_inicio` DATE NOT NULL COMMENT 'Fecha en que inicio el evento' ,
  `evt_termina` DATE NOT NULL COMMENT 'Fecha que termina el evento' ,
  `evt_divisa` VARCHAR(3) NOT NULL DEFAULT 'MXN' COMMENT 'Moneda que manejara el evento' ,
  `evt_tipoCambio` DECIMAL(5,2) NOT NULL DEFAULT '0.00' ,
  `evt_iva` DECIMAL(5,2) NULL DEFAULT '0.00' COMMENT 'Impuesto IVA' ,
  `evt_noInicioFacturacion` INT(11) NULL DEFAULT 1 COMMENT 'Numero donde comienza a generarse la facturación' ,
  `evt_plantillaConstancia` VARCHAR(75) NULL COMMENT 'Nombre de la plantilla de la constancia' ,
  `evt_plantillaFactura` VARCHAR(75) NOT NULL COMMENT 'Nombre de la plantilla para la factura' ,
  `evt_observaciones` TEXT NULL ,
  `evt_nombreTablaAsistentes` VARCHAR(30) NOT NULL COMMENT 'Nombre de la tabla donde están los registros de este evento.' ,
  `evt_fechaAlta` DATETIME NOT NULL ,
  `evt_usuarioAlta` INT(11) NOT NULL ,
  `evt_fechaModificacion` DATETIME NULL ,
  `evt_usuarioModifico` INT(11) NULL ,
  `evt_status` TINYINT(1) NOT NULL ,
  PRIMARY KEY (`evt_idEvento`) ,
  UNIQUE INDEX `evt_clave_UNIQUE` (`evt_clave` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Congresos' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_catStatusDisponibilidad`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_catStatusDisponibilidad` (
  `std_idRegistro` TINYINT(2) NOT NULL AUTO_INCREMENT ,
  `std_nombre` VARCHAR(20) NOT NULL ,
  `std_descripcion` VARCHAR(50) NOT NULL ,
  PRIMARY KEY (`std_idRegistro`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Catalogo de disponibilidad para el sistema' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_eventoPlantillasGafetes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_eventoPlantillasGafetes` (
  `epg_nombre` VARCHAR(75) NOT NULL COMMENT 'Nombre de la plantilla' ,
  `epg_clave` VARCHAR(10) NOT NULL COMMENT 'Clave de la plantilla' ,
  `epg_descripcion` VARCHAR(250) NULL ,
  `epg_fechaAlta` DATETIME NOT NULL ,
  `epg_usuarioAlta` INT(11) NOT NULL ,
  `epg_fechaModificacion` DATETIME NULL ,
  `epg_usuarioModifico` INT(11) NULL ,
  INDEX `index_clavePlantilla` (`epg_clave` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Plantillas de los Gafetes del Evento' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_eventoCategorias`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_eventoCategorias` (
  `ecc_idEvento` INT(11) UNSIGNED NOT NULL ,
  `ecc_clave` VARCHAR(10) NOT NULL ,
  `ecc_idCosto_1` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'ID costo fecha 1' ,
  `ecc_costo_fecha_1` DECIMAL(9,2) NULL DEFAULT '0.00' COMMENT 'Costo del registro antes de la primera fecha' ,
  `ecc_fecha_1` DATE NULL ,
  `ecc_idCosto_2` TINYINT(1) NOT NULL DEFAULT 2 COMMENT 'ID costo fecha 2' ,
  `ecc_costo_fecha_2` DECIMAL(9,2) NULL DEFAULT '0.00' COMMENT 'Costo del registro antes de la segunda fecha' ,
  `ecc_fecha_2` DATE NULL ,
  `ecc_idCosto_3` TINYINT(1) NOT NULL DEFAULT 3 COMMENT 'ID costo fecha 3' ,
  `ecc_costo_fecha_3` DECIMAL(9,2) NULL DEFAULT '0.00' COMMENT 'Costo del registro antes de una tercera fecha' ,
  `ecc_fecha_3` VARCHAR(45) NULL ,
  `ecc_idCosto_4` TINYINT(1) NOT NULL DEFAULT 4 COMMENT 'ID otro costo' ,
  `ecc_costo_otro` DECIMAL(9,2) NULL DEFAULT '0.00' ,
  `ecc_idCosto_5` TINYINT(1) NOT NULL DEFAULT 5 COMMENT 'ID costo en sitio' ,
  `ecc_costo_sitio` DECIMAL(9,2) NULL DEFAULT '0.00' COMMENT 'Costo del registro en sitio' ,
  `acc_aWeb` TINYINT(1) NULL DEFAULT 0 COMMENT '0 solo se muestra la opción para administradores, 1 mostrarse en el formulario por fuera' ,
  `ecc_fechaAlta` DATETIME NOT NULL ,
  `ecc_usuarioAlta` INT(11) NOT NULL ,
  `ecc_fechaModifacion` DATETIME NULL ,
  `ecc_usuarioModifico` INT(11) NULL ,
  INDEX `fk_ecc_clave` (`ecc_clave` ASC, `ecc_idEvento` ASC) ,
  INDEX `fk_smc_eventoCategorias_smc_eventos1` (`ecc_idEvento` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Catalogo de categorías para el evento' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_eventoCatalogoTipos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_eventoCatalogoTipos` (
  `cte_idTipoEvento` TINYINT(2) NOT NULL AUTO_INCREMENT ,
  `cte_clave` VARCHAR(5) NOT NULL ,
  `cte_nombre` VARCHAR(35) NOT NULL ,
  `cte_fechaAlta` DATETIME NOT NULL ,
  `cte_usuarioAlta` INT(11) NOT NULL ,
  `cte_fechaModificacion` DATETIME NULL ,
  `cte_usuarioModifico` INT(11) NULL ,
  PRIMARY KEY (`cte_idTipoEvento`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci, 
COMMENT = 'Catalogo de tipos de evento' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_eventoCatalogoStatus`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_eventoCatalogoStatus` (
  `ecs_idStatus` TINYINT(1) NOT NULL AUTO_INCREMENT ,
  `ecs_clave` VARCHAR(5) NOT NULL ,
  `ecs_nombre` VARCHAR(15) NOT NULL ,
  `ecs_descripcion` VARCHAR(75) NULL ,
  `ecs_fechaAlta` DATETIME NOT NULL ,
  `ecs_usuarioAlta` INT(11) NOT NULL ,
  `ecs_fechaModificacion` DATETIME NULL ,
  `ecs_usuarioModifico` INT(11) NULL ,
  PRIMARY KEY (`ecs_idStatus`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Catalogo de los status para evento' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_eventoTitulos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_eventoTitulos` (
  `ect_idEvento` INT(11) UNSIGNED NOT NULL COMMENT 'ID del evento al que pertenece el titulo' ,
  `ect_idTitulo` TINYINT(1) NOT NULL COMMENT 'ID interno del titulo' ,
  `ect_clave` VARCHAR(20) NOT NULL ,
  `ect_idioma` VARCHAR(2) NOT NULL COMMENT 'Idioma del titulo' ,
  `ect_nombre` VARCHAR(35) NOT NULL ,
  `ect_fechaAlta` DATETIME NOT NULL ,
  `ect_usuarioAlta` INT(11) NOT NULL ,
  INDEX `fk_smc_eventoCatalogoTitulos_smc_evento1` (`ect_idEvento` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Catalogo de títulos para el evento' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_catDivisas`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_catDivisas` (
  `dvs_idDivisa` INT(11) NOT NULL AUTO_INCREMENT ,
  `dvs_clave` VARCHAR(3) NOT NULL ,
  `dvs_nombre` VARCHAR(35) NULL ,
  `dvs_simbolo` VARCHAR(5) NULL ,
  `dvs_fechaAlta` DATETIME NOT NULL ,
  `dvs_usuarioAlta` INT(11) NOT NULL ,
  `dvs_fechaModificacion` DATETIME NULL ,
  `dvs_usuarioModifico` INT(11) NULL ,
  PRIMARY KEY (`dvs_idDivisa`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Catalogo de monedas' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_reg_interface`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_reg_interface` (
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
  `genero` CHAR(1) NULL COMMENT 'F = Femenino, M = Masculino, F = Female, M = Male' ,
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
  `telefono_RS` VARCHAR(25) NULL ,
  `fax_RS` VARCHAR(25) NULL ,
  `email_RS` VARCHAR(125) NULL ,
  `politicas_terminos_condiciones` TINYINT(1) NOT NULL DEFAULT 2 COMMENT '1 = Si acepta, 2 = No acepta' ,
  `forma_pago` VARCHAR(2) NOT NULL COMMENT 'DB = Deposito Bancario, TC = Tarjeta de Crédito, PP = PayPal, EF = Efectivo, OT = Otro' ,
  `fecha_registro` DATETIME NOT NULL ,
  `status` VARCHAR(10) NOT NULL DEFAULT 'REG' COMMENT 'REG = Registro, PEN = Pendiente, PAG = Pagado, CAN = Cancelado, COR = Cortesía' ,
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
  INDEX `clave_reg` (`clave_registro` ASC, `cat_registro` ASC, `clave_asociada` ASC, `nombre` ASC, `app` ASC, `apm` ASC) ,
  INDEX `index_id_tag` (`id_tag` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Interface tabla de registros asistentes del evento' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_eventoCategoriasNombres`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_eventoCategoriasNombres` (
  `enc_idEvento` INT(11) UNSIGNED NOT NULL ,
  `enc_clave` VARCHAR(10) NOT NULL COMMENT 'Clave de la categoria' ,
  `enc_idNombreCategoria` TINYINT(2) NOT NULL DEFAULT 1 ,
  `enc_idioma` VARCHAR(2) NOT NULL COMMENT 'Idioma del nombre de la categoría' ,
  `enc_nombre` VARCHAR(45) NOT NULL ,
  INDEX `enc_clave` (`enc_idEvento` ASC, `enc_clave` ASC, `enc_idNombreCategoria` ASC) ,
  INDEX `fk_smc_eventoCategoriasNombres_smc_eventos1` (`enc_idEvento` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Nombre de las categorias' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_eventoIdiomas`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_eventoIdiomas` (
  `eis_idEvento` INT(11) UNSIGNED NOT NULL ,
  `eis_idioma` VARCHAR(2) NOT NULL ,
  `eis_nombre` VARCHAR(45) NOT NULL ,
  INDEX `eis_clave` (`eis_idEvento` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Idiomas que soporta el evento' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_eventoStatusRegistros`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_eventoStatusRegistros` (
  `esr_idEvento` INT(11) UNSIGNED NOT NULL ,
  `esr_idStatus` TINYINT(1) NOT NULL COMMENT 'ID interno del status' ,
  `esr_clave` VARCHAR(10) NOT NULL COMMENT 'Clave del status' ,
  `esr_nombre` VARCHAR(35) NOT NULL ,
  `esr_fechaAlta` DATETIME NOT NULL ,
  `esr_usuarioAlta` INT(11) NOT NULL ,
  `esr_fechaModificacion` DATETIME NULL ,
  `esr_usuarioModifico` INT(11) NULL ,
  INDEX `fk_smc_eventoStatusRegistros_smc_eventos1` (`esr_idEvento` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Catalogo de status para los registros' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_eventoFormasPago`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_eventoFormasPago` (
  `efp_idEvento` INT(11) UNSIGNED NOT NULL COMMENT 'ID del evento' ,
  `efp_clave` VARCHAR(3) NOT NULL COMMENT 'Clave de la forma de pago' ,
  `efp_aWeb` TINYINT(1) NULL DEFAULT 0 COMMENT '0 solo se muestra la opción para administradores, 1 mostrarse en el formulario por fuera' ,
  `efp_fechaAlta` DATETIME NOT NULL ,
  `efp_usuarioAlta` INT(11) NOT NULL ,
  INDEX `fk_smc_eventoFormasPago_smc_eventos1` (`efp_idEvento` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Catalogo de formas de pago' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_eventoFormasPagoNombres`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_eventoFormasPagoNombres` (
  `fpn_idEvento` INT(11) UNSIGNED NOT NULL ,
  `fpn_idNombre` TINYINT(1) NOT NULL COMMENT 'ID del nombre interno' ,
  `fpn_clave` VARCHAR(3) NOT NULL COMMENT 'Clave de la forma de pago' ,
  `fpn_idioma` VARCHAR(2) NOT NULL ,
  `fpn_nombre` VARCHAR(45) NOT NULL ,
  INDEX `fk_smc_eventoFormasPagoNombres_smc_eventos1` (`fpn_idEvento` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Nombres de las formas de pago' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_reg__acom`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_reg__acom` (
  `acm_idRegistro` INT(11) NOT NULL ,
  `acm_idInterno` TINYINT(1) NOT NULL COMMENT 'ID interno del acompañante' ,
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
  INDEX `fk_smc_reg_acom__smc_reg_interface1` (`acm_idRegistro` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Tabla de acompañantes de los asistentes' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_reg__items`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_reg__items` (
  `item_idRegistro` INT(11) NOT NULL ,
  `item_idInterno` TINYINT(1) NOT NULL COMMENT 'ID interno del ítem' ,
  `item_clave` VARCHAR(10) NOT NULL ,
  `item_cantidad` INT(11) NOT NULL DEFAULT 0 COMMENT 'Numero total de este ítem adquiridos' ,
  `item_id_costo` TINYINT(1) NOT NULL COMMENT 'ID del costo con el que se aplica el ítem' ,
  `item_costo_unitario` DECIMAL(9,2) NOT NULL DEFAULT '0.00' ,
  `item_costo_total` DECIMAL(9,2) NOT NULL DEFAULT '0.00' ,
  `item_comentarios` TEXT NULL ,
  `item_fechaModificacion` DATETIME NULL ,
  `item_usuarioModifico` INT(11) NULL ,
  `item_status` VARCHAR(10) NULL ,
  INDEX `fk_smc_reg_items__smc_reg_interface1` (`item_idRegistro` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Tabla de los ítems adquiridos por los asistentes' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_eventoItems`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_eventoItems` (
  `eci_idEvento` INT(11) UNSIGNED NOT NULL ,
  `eci_clave` VARCHAR(10) NOT NULL ,
  `eci_idCosto_1` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'ID costo fecha 1' ,
  `eci_costo_fecha_1` DECIMAL(9,2) NULL DEFAULT '0.00' COMMENT 'Costo del ítem antes de la primera fecha' ,
  `eci_fecha_1` DATE NULL ,
  `eci_idCosto_2` TINYINT(1) NULL DEFAULT 2 COMMENT 'ID costo fecha 2' ,
  `eci_costo_fecha_2` DECIMAL(9,2) NULL DEFAULT '0.00' COMMENT 'Costo del ítem antes de la segunda fecha' ,
  `eci_fecha_2` DATE NULL ,
  `eci_idCosto_3` TINYINT(1) NULL DEFAULT 3 COMMENT 'ID costo fecha 3' ,
  `eci_costo_fecha_3` DECIMAL(9,2) NULL DEFAULT '0.00' COMMENT 'Costo del ítem antes de una tercera fecha' ,
  `eci_fecha_3` DATE NULL ,
  `eci_idCosto_4` TINYINT(1) NULL DEFAULT 4 COMMENT 'ID costo otro' ,
  `eci_costo_otro` DECIMAL(9,2) NULL DEFAULT '0.00' ,
  `eci_idCosto_5` TINYINT(1) NULL DEFAULT 5 COMMENT 'ID costo en sitio' ,
  `eci_costo_sitio` DECIMAL(9,2) NULL DEFAULT '0.00' COMMENT 'Costo del ítem en sitio' ,
  `eci_aWeb` TINYINT(1) NULL DEFAULT 0 COMMENT '0 solo se muestra la opción para administradores, 1 mostrarse en el formulario por fuera' ,
  `eci_paxMaximos` SMALLINT NULL DEFAULT 0 COMMENT 'El máximo de permitido,\nEspecifique a 0 para ilimitado' ,
  `eci_fechaAlta` DATETIME NOT NULL ,
  `eci_usuarioAlta` INT(11) NOT NULL ,
  `eci_fechaModifacion` DATETIME NULL ,
  `eci_usuarioModifico` INT(11) NULL ,
  INDEX `fk_eci_clave` (`eci_clave` ASC, `eci_idEvento` ASC) ,
  INDEX `fk_smc_eventoItems_smc_eventos1` (`eci_idEvento` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Catalogo de items\n' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_eventoItemsNombres`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_eventoItemsNombres` (
  `eni_idEvento` INT(11) UNSIGNED NOT NULL ,
  `eni_clave` VARCHAR(10) NOT NULL COMMENT 'Clave del item' ,
  `eni_idNombreItem` TINYINT(2) NOT NULL DEFAULT 1 ,
  `eni_idioma` VARCHAR(2) NOT NULL COMMENT 'Idioma del nombre del item' ,
  `eni_nombre` VARCHAR(45) NOT NULL ,
  INDEX `eni_clave` (`eni_idEvento` ASC, `eni_clave` ASC, `eni_idNombreItem` ASC) ,
  INDEX `fk_smc_eventoItemsNombres_smc_eventos1` (`eni_idEvento` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Nombre de los Items' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_eventoCategoriasAcom`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_eventoCategoriasAcom` (
  `eca_idEvento` INT(11) UNSIGNED NOT NULL ,
  `eca_clave` VARCHAR(10) NOT NULL ,
  `eca_idCosto_1` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'ID costo fecha 1' ,
  `eca_costo_fecha_1` DECIMAL(9,2) NULL DEFAULT '0.00' COMMENT 'Costo del acompañante antes de la primera fecha' ,
  `eca_fecha_1` DATE NULL ,
  `eca_idCosto_2` TINYINT(1) NULL DEFAULT 2 COMMENT 'ID costo fecha 2' ,
  `eca_costo_fecha_2` DECIMAL(9,2) NULL DEFAULT '0.00' COMMENT 'Costo del acompañante antes de la segunda fecha' ,
  `eca_fecha_2` DATE NULL ,
  `eca_idCosto_3` TINYINT(1) NULL DEFAULT 3 COMMENT 'ID costo fecha 3' ,
  `eca_costo_fecha_3` DECIMAL(9,2) NULL DEFAULT '0.00' COMMENT 'Costo del acompañante antes de una tercera fecha' ,
  `eca_fecha_3` DATE NULL ,
  `eca_idCosto_4` TINYINT(1) NULL DEFAULT 4 COMMENT 'ID costo otro' ,
  `eca_costo_otro` DECIMAL(9,2) NULL DEFAULT '0.00' ,
  `eca_idCosto_5` TINYINT(1) NULL DEFAULT 5 COMMENT 'ID costo en sitio' ,
  `eca_costo_sitio` DECIMAL(9,2) NULL DEFAULT '0.00' COMMENT 'Costo del acompañante en sitio' ,
  `eca_aWeb` TINYINT(1) NULL DEFAULT 0 COMMENT '0 solo se muestra la opción para administradores, 1 mostrarse en el formulario por fuera' ,
  `eca_fechaAlta` DATETIME NOT NULL ,
  `eca_usuarioAlta` INT(11) NOT NULL ,
  `eca_fechaModifacion` DATETIME NULL ,
  `eca_usuarioModifico` INT(11) NULL ,
  INDEX `fk_eci_clave` (`eca_clave` ASC, `eca_idEvento` ASC) ,
  INDEX `fk_smc_eventoItems_smc_eventos1` (`eca_idEvento` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Catalogo categorías de acompañantes' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_eventoCategoriasAcomNombres`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_eventoCategoriasAcomNombres` (
  `ecan_idEvento` INT(11) UNSIGNED NOT NULL ,
  `ecan_clave` VARCHAR(10) NOT NULL COMMENT 'Clave de la categoria' ,
  `ecan_idNombre` TINYINT(2) NOT NULL DEFAULT 1 ,
  `ecan_idioma` VARCHAR(2) NOT NULL COMMENT 'Idioma del nombre del item' ,
  `ecan_nombre` VARCHAR(45) NOT NULL ,
  INDEX `eni_clave` (`ecan_idEvento` ASC, `ecan_clave` ASC, `ecan_idNombre` ASC) ,
  INDEX `fk_smc_eventoItemsNombres_smc_eventos1` (`ecan_idEvento` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Nombre de las categorías de los acompañantes' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_hoteles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_hoteles` (
  `htl_idEvento` INT(11) NOT NULL ,
  `htl_idHotel` TINYINT(3) NOT NULL ,
  `htl_nombre` VARCHAR(175) NOT NULL ,
  `htl_direccion` VARCHAR(255) NULL ,
  `htl_fechaAlta` DATETIME NOT NULL ,
  `htl_usuarioAlta` INT(11) NOT NULL ,
  `htl_fechaModificacion` DATETIME NULL ,
  `htl_usuarioModifico` INT(11) NULL ,
  `htl_status` TINYINT(1) NOT NULL COMMENT '0 Inactivo, 1 Activo, 2 Cerrado, 3 Cancelado' ,
  INDEX `index1` (`htl_idHotel` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Tabla de hoteles para el evento' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_hotelesHabitaciones`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_hotelesHabitaciones` (
  `hhb_idEvento` INT(11) NOT NULL ,
  `hhb_idHotel` TINYINT(3) NOT NULL COMMENT 'Clave del hotel al que pertenece la habitación' ,
  `hhb_idHabitacion` SMALLINT(4) NOT NULL COMMENT 'ID interno de la habitación' ,
  `hhb_clave` VARCHAR(15) NOT NULL COMMENT 'Clave de la habitación Ej.: SGL = Single, DBL = Double' ,
  `hhb_pax` SMALLINT(5) NOT NULL COMMENT 'Numero disponible de habitaciones' ,
  `hhb_paxMaxReservacion` TINYINT(2) NULL DEFAULT 1 COMMENT 'Maximo numero de cuartos a reservar' ,
  `hhb_paxMaxAdultos` TINYINT(2) NOT NULL DEFAULT 1 COMMENT 'Número máximo de adultos por habitación' ,
  `hhb_paxMaxMenores` TINYINT(2) NOT NULL DEFAULT 1 COMMENT 'Número máximo de menores por habitación' ,
  `hhb_costoNoche` DECIMAL(9,2) NOT NULL DEFAULT '0.00' ,
  `hhb_fechaAlta` DATETIME NOT NULL ,
  `hhb_usuarioAlta` INT(11) NOT NULL ,
  `hhb_fechaModificacion` DATETIME NULL ,
  `hhb_usuarioModifico` INT(11) NULL ,
  `hhb_status` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '0 No disponible, 1 Disponible, 2 Bloqueada, 3 Cancelada' ,
  INDEX `fk_smc_hotelesHabitaciones_smc_hoteles1` (`hhb_idHotel` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Tipos de habitaciones para el hotel' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_hotelesHabitacionesNombres`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_hotelesHabitacionesNombres` (
  `hhn_idEvento` INT(11) NOT NULL ,
  `hhn_idHotel` TINYINT(3) NOT NULL COMMENT 'Clave del hotel' ,
  `hhn_idHabitacion` SMALLINT(4) NOT NULL ,
  `hhn_id` TINYINT(3) NOT NULL COMMENT 'ID interno' ,
  `hhn_idioma` VARCHAR(2) NOT NULL ,
  `hhn_nombre` VARCHAR(175) NOT NULL ,
  INDEX `fk_smc_hotelesHabitacionesNombres_smc_hotelesHabitaciones1` (`hhn_idHabitacion` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Nombre de las habitaciones' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_reg__fotos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_reg__fotos` (
  `foto_idRegistro` INT(11) NOT NULL ,
  `foto_mime` VARCHAR(50) NOT NULL ,
  `foto_fotografia` BLOB NOT NULL ,
  INDEX `fk_smc_reg__fotos_smc_reg_interface1` (`foto_idRegistro` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Fotografias de los registros' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_hotelesHabitacionesCostos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_hotelesHabitacionesCostos` (
  `hhc_idEvento` INT(11) NOT NULL ,
  `hhc_id` TINYINT(3) NOT NULL COMMENT 'ID interno' ,
  `hhc_costoNoches` DECIMAL(9,2) NULL DEFAULT '0.00' ,
  `hhc_fechaAlta` DATETIME NOT NULL ,
  `hhc_usuarioAlta` INT(11) NOT NULL ,
  `hhc_fechaModificacion` DATETIME NULL ,
  `hhc_usuarioModifico` INT(11) NULL )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Costos de las habitaciones' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_reg__reservaciones`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_reg__reservaciones` (
  `res_idRegistro` INT(11) NOT NULL ,
  `res_idHotel` INT(11) NULL ,
  `res_idHabitacion` SMALLINT(4) NULL ,
  `res_idCostos` TINYINT(3) NOT NULL ,
  `res_folio` VARCHAR(15) NOT NULL COMMENT 'Folio de la reservación' ,
  `res_clave` VARCHAR(32) NOT NULL COMMENT 'Clave de reservación' ,
  `res_llegada` DATETIME NOT NULL COMMENT 'Fecha de llegada' ,
  `res_salida` DATETIME NOT NULL COMMENT 'Fecha de salida' ,
  `res_numHabitaciones` SMALLINT(4) NULL DEFAULT 0 ,
  `res_numAdultos` SMALLINT(5) NULL DEFAULT 0 ,
  `res_numMenores` SMALLINT(5) NULL DEFAULT 0 ,
  `res_costoTotal` DECIMAL(9,2) NOT NULL DEFAULT '0.00' ,
  `res_anticipo` DECIMAL(9,2) NULL DEFAULT '0.00' ,
  `res_saldo` DECIMAL(9,2) NULL DEFAULT '0.00' ,
  `res_formaPago` VARCHAR(3) NOT NULL ,
  `res_fechaAlta` DATETIME NOT NULL ,
  `res_usuarioAlta` INT(11) NULL ,
  `res_fechaModificacion` DATETIME NULL ,
  `res_usuarioModifico` INT(11) NULL ,
  `res_status` VARCHAR(10) NOT NULL DEFAULT 'RES' COMMENT 'RES = Reservación, PAG = Pagada, COR = Cortecia, CAN = cancelada' ,
  INDEX `fk_smc__reservaciones_smc_reg_interface1` (`res_idRegistro` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Contiene las reservaciones de los registrados' ;


-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_reg__RFID`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_reg__RFID` (
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

-- -----------------------------------------------------
-- Table `smcTecnoRegistro`.`smc_reg__caja`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_reg__caja` (
  `id_cobro` INT(11) NOT NULL AUTO_INCREMENT ,
  `id_usuario` INT(11) NOT NULL ,
  `id_registro` INT(11) NOT NULL ,
  `forma_pago` VARCHAR(2) NOT NULL COMMENT 'Forma de pago' ,
  `costo` DECIMAL(9,2) NOT NULL ,
  `descuento` DECIMAL(9,2) NULL ,
  `costo_total` DECIMAL(9,2) NOT NULL ,
  `comentarios` TEXT NULL ,
  `fecha_cobro` DATETIME NOT NULL ,
  `status` VARCHAR(5) NOT NULL ,
  INDEX `index_id_usuario` (`id_usuario` ASC) ,
  PRIMARY KEY (`id_cobro`) )
ENGINE = InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
