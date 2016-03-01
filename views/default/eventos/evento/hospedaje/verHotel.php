{#
<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/eventos/evento/hospedaje/
 * @version $Id: verHotel.php 1.0 2014-03-19 22:18 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}

<div id="hotel">

	<div class="well well-small">
		<strong>{{ hotel.htl_nombre }}</strong>
		<div class="pull-right">
			<a data-load="seccion" data-opts='{"target":"evento"}' class="btn btn-mini" href="{{ seccion }}/"><i class="icon-arrow-left"></i> Regresar</a>
			<a href="#setHabitacion" role="button" class="btn btn-info btn-mini" data-toggle="modal"><i class="icon-plus-sign icon-white"></i> Agregar habitación</a>
		</div>
	</div>

	<table class="table table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th>Clave</th>
				<th>Número de habitaciones</th>
				<th>Número maximo de habitaciones por registro</th>
				<th style="width: 53px;">Status</th>
				<th style="width: 100px;">Administrar</th>
			</tr>
		</thead>
		<tbody id="lista-habitaciones">
			{% for habitacion in habitaciones %}
			<tr>
				<td>{{ habitacion.hhb_clave }}</td>
				<td>{{ habitacion.hhb_pax }}</td>
				<td>{{ habitacion.hhb_paxMaxReservacion }}</td>
				<th>
					{% if habitacion.hhb_status == 1 %}
					<span class="label label-success">Activo</span>
					{% else %}
					<span class="label label-important">Inactivo</span>
					{% endif %}
				</th>
				<th>
					<a href="{{ seccion }}/?action=verHabitacion&amp;id={{ habitacion.hhb_id }}" class="btn btn-mini" data-load="seccion" data-opts='{"target":"evento"}'><i class="icon-cog"></i></a>
				</th>
			</tr>
			{% else %}
			<tr id="no-habitaciones">
				<th colspan="5">Aun no hay habitaciones | <a href="#setHabitacion" role="button" data-toggle="modal"><i class="icon icon-plus-sign"></i> Agregar habitación</a></th>
			</tr>
			{% endfor %}
		</tbody>
	</table>

	<!-- Modal -->
	<form action="{{ seccion }}/?action=setHabitacion" id="setHabitacion" class="modal hide fade form-horizontal" name="setHabitacion" tabindex="-1" role="dialog" aria-labelledby="setHabitacionLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="setHabitacionLabel">Alta de habitación</h3>
		</div>
		<div class="modal-body">
			<div class="control-group">
				<label for="hhb_clave" class="control-label">Clave:</label>
				<div class="controls">
					<input class="{required:true,maxlength:10} span2" type="text" id="hhb_clave" name="hhb_clave" placeholder="SGL" required />
					<span class="help-block">Ejemplos: SGL = Sencilla, DBL = Doble, HLSGL = Habitación de Lujo SGL</span>
				</div>
			</div>
			<div class="control-group">
				<label for="hhb_pax" class="control-label">Número de habitaciones:</label>
				<div class="controls">
					<input class="{required:true,number:true,maxlength:4} span1" type="text" id="hhb_pax" name="hhb_pax" placeholder="10" required />
				</div>
			</div>
			<div class="control-group">
				<label for="hhb_paxMaxReservacion" class="control-label">Habitaciones por registro:</label>
				<div class="controls">
					<input class="{required:true,number:true,maxlength:2} span1" type="text" id="hhb_paxMaxReservacion" name="hhb_paxMaxReservacion" placeholder="5" required />
					<span class="help-block">Es el número maximo de habitaciones que puede reservar un participante.</span>
				</div>
			</div>
			<div class="control-group">
				<label for="hhb_paxAdultos" class="control-label">Maximo número adultos:</label>
				<div class="controls">
					<input class="{number:true,maxlength:2} span1" type="text" id="hhb_paxAdultos" name="hhb_paxAdultos" placeholder="2" />
				</div>
			</div>
			<div class="control-group">
				<label for="hhb_paxMenores" class="control-label">Maximo número menores:</label>
				<div class="controls">
					<input class="{number:true,maxlength:2} span1" type="text" id="hhb_paxMenores" name="hhb_paxMenores" placeholder="2" />
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancelar</button>
			<button class="btn btn-success">Agregar</button>
		</div>
	</form>
</div>
<script>
$(function() {
	/**
	 * AGREGAR hotel
	 */
	$('#setHabitacion').validate({
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
					_form.find('.form-error').html('&nbsp;');
				},
				success: function(sRes) {
					if (!sRes.status) {
						_form.find('.form-error').html(sRes.mensaje);
					} else {

						if ($('#no-habitaciones').length) {
							$('#no-habitaciones').remove();
						}

						_hotel = '<tr>' +
							'<td>' + sRes.habitacion.hhb_clave + '</td>' +
							'<td>' + sRes.habitacion.hhb_pax + '</td>' +
							'<td>' + sRes.habitacion.hhb_paxMaxReservacion + '</td>' +
							'<th><span class="label label-success">Activo</span></th>' +
							'<th>' +
								'<a href="{{ seccion }}/?action=verHabitacion&amp;id=' + sRes.id_habitacion + '" class="btn btn-mini" data-load="seccion" data-opts=\'{"target":"evento"}\'><i class="icon-cog"></i></a>' +
							'</th>' +
						'</tr>';

						$(_hotel).appendTo('#lista-habitaciones');
						
						$('#htl_nombre').val('');
						$('#htl_direccion').val('');

						$('#setHabitacion').modal('hide');
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