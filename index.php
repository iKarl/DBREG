<?php
/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: tecnoregistro/
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * @version $Id: index.php 1.0 2011-06-04 10:26 _Karl_ $;
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
ini_set("display_errors", 1);

// Incluimos el motor del framework
include ("./../SimpleMVC/class.SimpleMVC.php");

// Preparación del sistema
$project = new SimpleMVC();

$project->Main();
?>