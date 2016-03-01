<div class="cajeros">
	<form action="{{ seccion }}/?action=reporteCajero" id="reporteCajero" method="post" name="reporteCajero" target="_blank">
		<div class="control-group pull-left" style="margin-right: 10px;">
			<label for="cajero" class="control-label">Lista de cajeros:</label>
			<div class="controls">
				<select class="{required:true}" name="cajero" id="cajero">
					<option value="">Seleccione:</option>
					{% for cajero in cajeros %}
					<option value="{{ cajero.usr_idUsuario }}">{{ cajero.usr_nombre }} {{ cajero.usr_app }} {{ cajero.usr_apm }}</option>
					{% endfor %}
				</select>
			</div>
		</div>
		<div class="control-group pull-left" style="margin-right: 10px;">
			<label for="fecha_inicio" class="control-label">Fecha inicio:</label>
			<div class="controls">
				<input class="{required:true} span2" type="text" name="fecha_inicio" id="fecha_inicio" readonly="readonly" />
			</div>
		</div>
		<div class="control-group pull-left" style="margin-right: 10px;">
			<label for="fecha_cierre" class="control-label">Fecha cierre:</label>
			<div class="controls">
				<input class="span2" type="text" name="fecha_cierre" id="fecha_cierre" readonly="readonly" />
			</div>
		</div>

		<div class="clearfix">&nbsp;</div>

		<div class="form-actions">
			<button class="btn" type="reset">Limpiar</button>
			<button class="btn" type="submit">Generar reporte</button>
		</div>
	</form>
</div>

<script>
$(function() {
	$('#fecha_inicio, #fecha_cierre').datepicker();

	/**
	 * AGREGAR ADICIONAL
	 */
	$('#reporteCajero').validate({
		submitHandler: function(form) {
			form.submit();
		},
		errorPlacement: function(error, element) {
			error.appendTo(element.parent().append());
		}
	});
});
</script>