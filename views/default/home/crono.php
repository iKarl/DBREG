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
		<link rel="stylesheet" type="text/css" href="{{ layout }}/css/bootstrap/css/bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" href="{{ layout }}/js/kendoui.trial.2012.2.710/styles/kendo.dataviz.min.css" />
		<link rel="stylesheet" type="text/css" href="{{ layout }}/css/jquery-upload/jquery.fileupload-ui.css" />
		<link rel="stylesheet" type="text/css" href="{{ layout }}/css/Jcrop/jquery.Jcrop.min.css" />
		<link rel="stylesheet" type="text/css" href="{{ layout }}/css/layout.css" />
		<link id="dynamicCSS" rel="stylesheet" type="text/css" />
		<!--<link rel="shortcut icon" href="{{ images }}/favicon.ico" />-->
	</head>

	<body>

		<!-- Inicio contenedor -->
		<div id="contenedor">

			<!-- Inicia cabecera -->
			<header>
				<section class="titulo"><a href="." title="Inicio">Tecnoregistro</a></section>
			</header>
			<!-- Termina la cabecera -->

			<!-- Inicia el cuerpo -->
			<div id="cuerpo">

				<div class="container">
					<form action="tecnoRegistro/home/crono?action=mostrarHoras" class="form-horizontal well well-small" id="mostrarHoras" method="post" name="mostrarHoras">
						<div class="control-group">
							<label for="id_registro" class="control-label">ID registro o TAG:</label>
							<div class="controls">
								<input class="{required:true}" autofocus="autofocus" type="text" id="id_registro" name="id_registro" required />
								<button class="btn">Buscar</button>
								<strong class="form-error">No fue posible proscesar su petición.</strong>
							</div>
						</div>
					</form>
				</div>

				<div>
					<div class="span6" id="nombre" style="font-size: 1.8em;">&nbsp;</div>
					<div class="span6" id="horas" style="font-size: 1.8em;">&nbsp;</div>
				</div>

			</div>

			<div class="slice"></div>
		</div>

		<footer>
			<span>Soft Manage Congress | All Right Reserverd by Tecnoregistro | &copy; 2011</span>
		</footer>

		<script src="{{ layout }}/js/jquery/jquery-1.8.1.min.js"></script>
		<script src="{{ layout }}/js/modernizr.js"></script>
		<script src="{{ layout }}/js/jquery/jquery-ui/jquery-ui-1.9.2.custom.min.js"></script>
		<script src="{{ layout }}/js/jquery/jquery-ui/i18n/jquery.ui.datepicker-es.js"></script>
		<script>var pathTools = "{{ layout }}";</script>
		<script src="{{ layout }}/js/bootstrap/bootstrap.min.js"></script>
		<script src="{{ layout }}/js/jquery-validation-1.10.0/jquery.metadata.js"></script>
		<script src="{{ layout }}/js/jquery-validation-1.10.0/jquery.validate.min.js"></script>
		<script src="{{ layout }}/js/jquery-validation-1.10.0/localization/messages_es.js"></script>
		<script src="{{ layout }}/js/kendoui.trial.2012.2.710/js/kendo.dataviz.min.js"></script>
		<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
		<script src="{{ layout }}/js/plugins/jquery-upload/jquery.iframe-transport.js"></script>
		<!-- The basic File Upload plugin -->
		<script src="{{ layout }}/js/plugins/jquery-upload/jquery.fileupload.js"></script>
		<!-- The File Upload file processing plugin -->
		<script src="{{ layout }}/js/plugins/jquery-upload/jquery.fileupload-fp.js"></script>
		<!-- The File Upload user interface plugin -->
		<script src="{{ layout }}/js/plugins/jquery-upload/jquery.fileupload-ui.js"></script>
		<!-- The localization script -->
		<script src="{{ layout }}/js/plugins/jquery-upload/locale.js"></script>
		<!-- The cute image -->
		<script src="{{ layout }}/js/plugins/Jcrop/jquery.Jcrop.min.js"></script>

		<script src="{{ layout }}/js/general.js"></script>
		<script>
		$(function() {
			/**
			 * AGREGAR ADICIONAL
			 */
			$('#mostrarHoras').validate({
				submitHandler: function(form) {
					var _form = $(form),
						_action = _form.attr('action'),
						queryString = $(form).serialize();

					$.ajax({
						type: 'POST',
						url: _action,
						data: queryString,
						dataType: 'json',
						beforeSend: function() {
							_form.find('button').attr('disabled', 'disabled');
							_form.find('.form-error').html('&nbsp;').hide();
						},
						success: function(sRes) {
							_form.find('.form-error').html(sRes.mensaje).show();
							if (!sRes.status) {
								_form.find('button').removeAttr('disabled');
							} else {
								$('#nombre').html(sRes.nombre.nombre + ' ' + sRes.nombre.app + ' ' + sRes.nombre.apm);
								$('#horas').html(sRes.horas);
							}
						}
					}).always(function() {
						_form.find('button').removeAttr('disabled');
					});

					return false;
				},
				errorPlacement: function(error, element) {
					error.appendTo(element.parent().append());
				}
			});
		});
		</script>
	</body>
</html>