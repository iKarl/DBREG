<?php
/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: registros/
 * @author: Carlos A. García Hernández <cgarcia@turycon.com.mx, carlos.agh@gmail.com>
 * @version $Id: conf.proyect.php 1.3 2011-10-09 10:25 _Karl_ $;
 * 
 * Configuraciones generales
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

define ("URL_PATH", "http://" . $_SERVER['SERVER_NAME'] . '/DBREG');

/**
 * 
 * Ruta del proyecto
 */
define ("PATH_PROJECT", "./../DBREG");

/**
 * Ruta del Framework
 */
define ("PATH_SMVC", "./../SimpleMVC");

/**
 * Ruta de los controladores
 */
define ("PATH_CTRLS", "controllers");

/**
 * Ruta para los modelos
 */
define ("PATH_MODELS", "models");

/**
 * Ruta de las vistas
 */
define ("PATH_VIEWS", "views");

/**
 * Ruta de las vistas
 */
define ("PATH_IMAGES", "views/images");

/**
 * Ruta de utilidades del proyecto
 */
define ("PATH_LIBS", "libs");

/**
 * Ruta de la clase error
 */
define("PATH_ERROR", "errors");

/**
 * 
 * Configuraciones del gestor de base de datos
 * 
 * @var DB_HANDLER
 * @var DB_SERVERNAME
 * @var DB_PORT
 * @var DB_SERVER
 * @var DB_USERNAME
 * @var DB_USERPASS
 * @var DB_NAME
 */
define ("USE_DB", true);
define ("DB_HANDLER", "MySQLi");
define ("DB_SERVERNAME", "localhost");
define ("DB_PORT", "3306");
define ("DB_USERNAME", "root");
define ("DB_USERPASS", "t3cn0w3b");
define ("DB_NAME", "MICHEL");
?>
