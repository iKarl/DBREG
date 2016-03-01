{#
<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/header/
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * @version $Id: index.php 1.0 2011-06-09 23:01 _Karl_ $;
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}
<!DOCTYPE html>
<html class="no-js">
	<head>
		<base href="{{ url }}/" />
		<meta charset="utf-8" />
		<title>Soft Manage Congress :: Inicio</title>
		<link rel="stylesheet" type="text/css" href="{{ layout }}/css/jquery-ui/redmond/jquery-ui-1.8.20.custom.css" />
		<link rel="stylesheet" type="text/css" href="{{ layout }}/css/bootstrap/css/bootstrap.min.old.css" />
		<link rel="stylesheet" type="text/css" href="{{ layout }}/js/kendoui.trial.2012.2.710/styles/kendo.dataviz.min.css" />
		<link rel="stylesheet" type="text/css" href="{{ layout }}/css/jquery-upload/jquery.fileupload-ui.css" />
		<link rel="stylesheet" type="text/css" href="{{ layout }}/css/Jcrop/jquery.Jcrop.min.css" />
		<link rel="stylesheet" type="text/css" href="{{ layout }}/css/layout.css" />
		<link id="dynamicCSS" rel="stylesheet" type="text/css" />
		<!--<link rel="shortcut icon" href="{{ images }}/favicon.ico" />-->
		<style type="text/css">
		/*.hide {
			display: none !important;
		}*/
		</style>
	</head>

	<body>

		<!-- Inicio contenedor -->
		<div id="contenedor">

			<!-- Inicia cabecera -->
			<header>
				<section class="titulo"><a href="." title="Inicio">Tecnoregistro</a></section>
				<!-- Inicia el menu principal -->
				<nav>
					<li class="dropdown">
						<a href="#" data-toggle="dropdown" class="dropdown-toggle" id="menuPrincipal">Menu principal</a>
						<ul class="dropdown-menu">
							<li>
								<a data-load="seccion" data-opts='{"tools":{"css":false,"js":true,"file":"eventos"}}' href="eventos/">
									<img src="{{ images }}/ui-megaphone.png" />Eventos
								</a>
							</li>
							<li>
								<a data-load="seccion" data-opts='{"tools":{"css":false,"js":true,"file":"caja"}}' href="caja/">
									<img src="{{ images }}/ui-zone-money.png" />Caja
								</a>
							</li>
							<!--<li>
								<a href="#clientes" class="menuPrincipal">
									<img src="{{ images }}/ui-clients.png" />Clientes
								</a>
							</li>-->
							<li class="divider"></li>
							<li>
								<a data-load="seccion" data-opts='{"tools":{"css":true,"js":true,"file":"paises"}}' href="catalogos/paises/">
									<img src="{{ images }}/ui-countrys.png" />Paises
								</a>
							</li>
							<li>
								<a data-load="seccion" data-opts='{"tools":{"css":true,"js":true,"file":"codPostales"}}' href="catalogos/codPostales/">
									<img src="{{ images }}/ui-book-open.png" />Directorio postal
								</a>
							</li>
							<li class="divider"></li>
							<li>
								<a data-load="seccion" data-opts='{"tools":{"css":false,"js":true,"file":"usuarios"}}' href="usuarios/">
									<img src="{{ images }}/ui-users.png" />Usuarios
								</a>
							</li>
						</ul>
					</li>
				</nav>
				<!-- Termina el menu principal -->

				<!-- Inicia datos del usuario en sesion -->
				<section class="datosUsuario">
					<ul>
						<!--<li>
							<a href="#importar" id="importarInet">
								Importar registros
							</a>
						</li>-->
						<li><strong>{{ user.saludo }}:</strong> <span>{{ user.usr_nombre}} {{ user.usr_app }}</span></li>
						<li>Ultimo acceso: <span>{{ ultimoAcceso }}</span></li>
						<li>
							<a href="{{ url }}/login/?action=logout" class="logout">Cerrar session</a>
						</li>
						<li id="mostrar-actividad">Cargando</li>
					</ul>
				</section>
				<!-- Termina datos de usuario en sesion -->
			</header>
			<!-- Termina la cabecera -->

			<!-- Inicia el cuerpo -->
			<div id="cuerpo">
