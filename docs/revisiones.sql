CREATE  TABLE IF NOT EXISTS `smcTecnoRegistro`.`smc_eventoCategoriasNombres` (
  `enc_idEvento` INT(11) NOT NULL ,
  `enc_clave` VARCHAR(10) NOT NULL ,
  `enc_idNombreCategoria` TINYINT(2) NOT NULL DEFAULT 1 ,
  `enc_idioma` VARCHAR(3) NOT NULL ,
  `enc_nombre` VARCHAR(45) NOT NULL )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci, 
COMMENT = 'Nombre de las categorias' 