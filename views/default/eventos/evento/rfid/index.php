{#
<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/eventos/evento/hospedaje/
 * @version $Id: index.php 1.0 2014-03-18 21:44 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}

<div id="rfid">

	<div class="well well-small">
		<strong>RFID</strong>
	</div>

	<!-- Modal -->
	<form action="{{ seccion }}/?action=cerrarSesion" id="cerrarSesion" class="form-horizontal span5" method="post" name="cerrarSesion">
		<div class="control-group">
			<label for="tablas" class="control-label">Tablas:</label>
			<div class="controls">
				<select class="{required:true} span3" name="tablas[]" id="tablas" multiple>
					{% for tabla in tablas %}
					<option value="{{ tabla }}">{{ tabla }}</option>
					{% endfor %}
				</select>
			</div>
		</div>
		<div class="control-group">
			<label for="fecha_salida" class="control-label">Fecha salida:</label>
			<div class="controls">
				<input class="{required:true} span2" type="text" id="fecha_salida" name="fecha_salida" readonly="readonly" />
			</div>
		</div>
		<div class="control-group">
			<label for="hora_salida" class="control-label">Hora salida:</label>
			<div class="controls">
				<input class="{required:true} input-small" type="time" id="hora_salida" name="hora_salida" />
			</div>
		</div>
		<div class="control-group">
			<label for="numero_antena_salida" class="control-label">Numero de antena de salida:</label>
			<div class="controls">
				<input class="{required:true,number:true} span1" type="text" id="numero_antena_salida" name="numero_antena_salida" />
			</div>
		</div>
		<div class="control-group">
			<label for="numero_sesion" class="control-label">Numero de sesion:</label>
			<div class="controls">
				<input class="{required:true,number:true} span1" type="text" id="numero_sesion" name="numero_sesion" />
			</div>
		</div>
		
		<div class="form-actions">
			<div class="form-alert">&nbsp;</div>
			<button class="btn btn-danger" type="reset">Cancelar</button>
			<button class="btn btn-success">Cerrar sesiones</button>
		</div>
	</form>
</div>
<script>
$(function() {
	/**
	 * AGREGAR hotel
	 */
	$('#cerrarSesion').validate({
		submitHandler: function(form) {
			var _form = $(form),
				_action = _form.attr('action'),
				qryStr = $(form).serialize();

			$.ajax({
				type: 'POST',
				url: _action,
				data: qryStr,
				dataType: 'json',
				beforeSend: function() {
					_form.find('button').attr('disabled', 'disabled');
					_form.find('.form-alert').html('&nbsp;');
				},
				success: function(sRes) {
					if (!sRes.status) {
						_form.find('.form-alert').html(sRes.mensaje);
					} else {
						_form.find('.form-alert').html(sRes.mensaje);
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

	$('#fecha_salida').datepicker({
		dateFormat: 'yy-mm-dd'
	});
});
</script>