{#
<?php
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/index/
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * @version $Id: index.php 1.0 2012-01-30 21:28 _Karl_ $;
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
?>
#}
<!DOCTYPE html>
<html>
	<head>
		<base href="{{ url }}/" />
		<meta charset="utf-8" />
		<title>Soft Manage Congress :: Login</title>
		<meta name="globalsign-domain-verification" content="J9xQGA-KEpD82Q8Ll8DR8RwVRXVq0I5uzmacGiOTRW" />
		<link rel="stylesheet" type="text/css" href="{{ layout }}/css/bootstrap/css/bootstrap.min.old.css" />
		<link rel="stylesheet" type="text/css" href="{{ layout }}/css/login.css" />
		<link rel="shortcut icon" href="{{ images }}/favicon.ico" />
		<script src="{{ layout }}/js/jquery/jquery-1.7.1.min.js"></script>
		<script src="{{ layout }}/js/login.js"></script>
	</head>

	<body>

		<div class="form-login">
			<div class="tec-logo">
				<img src="{{ images }}/logotecno.jpg" width="150px" />
			</div>

			<div class="tec-formulario">
				<h2>Iniciar sesión</h2>
				<form name="tec-login" id="tec-login" action="login/?action=login" method="post">

					<div class="control-group">
						<label class="control-label" for="username">Usuario:</label>
						<div class="controls">
							<input autofocus="autofocus" class="input-large" type="email" name="username" id="username" maxlength="120" value="@tecnoregistro.com.mx" required />
						</div>
					</div>

					<div class="control-group">
						<label class="control-label" for="password">Contraseña:</label>
						<div class="controls">
							<input class="input-large" type="password" name="password" id="password" maxlength="16" required />
						</div>
					</div>

					<div class="form-actions">
						<div class="form-error">&nbsp;</div>
						<a class="recuperarContrasena" href="#recoveryPass">¿Olvide mi contraseña?</a>
						<input class="btn" id="tec-loggin" type="submit" value="Iniciar sesion" />
					</div>

				</form>
			</div>
			<!-- DO NOT EDIT - GlobalSign SSL Site Seal Code - DO NOT EDIT -->
			<!--<table style="float: right; margin: 8px;" width=90 border=0 cellspacing=0 cellpadding=0 title="CLICK TO VERIFY: This site uses a GlobalSign SSL Certificate to secure your personal information." ><tr><td><span id="ss_img_wrapper_gmogs_image_90-35_en_blue"><a href="https://www.globalsign.com/" target=_blank title="GlobalSign Site Seal" rel="nofollow"><img alt="SSL" border=0 id="ss_img" src="//seal.globalsign.com/SiteSeal/images/gmogs_image_90-35_en_blue.png"></a></span><script type="text/javascript" src="//seal.globalsign.com/SiteSeal/gmogs_image_90-35_en_blue.js"></script></td></tr></table>-->
			<!-- DO NOT EDIT - GlobalSign SSL Site Seal Code - DO NOT EDIT -->
			<footer>
				Tecnoregistro All Right Reserved &copy; 2012
			</footer>

		</div>

	</body>
</html>
