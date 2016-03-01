{#
<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/eventos/evento/hospedaje/
 * @version $Id: verHabitacion.php 1.0 2014-03-20 22:51 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}
<div id="habitacion">

	<div class="well well-small">
		<strong>{{ hotel.htl_nombre }} :: {{ habitacion.hhb_clave }}</strong>
		<div class="pull-right">
			<a href="{{ seccion }}/?action=verHotel&amp;id={{ habitacion.hhb_id_hotel }}" class="btn btn-mini" data-load="seccion" data-opts='{"target":"evento"}'><i class="icon-arrow-left"></i> Regresar</a>
			{% if not habitacion.hhc_costoAdulto %}<a href="#setCostoHabitacion" role="button" class="btn btn-info btn-mini" data-toggle="modal"><i class="icon-plus-sign icon-white"></i> Agregar costos habitación</a>{% endif %}
		</div>
	</div>

	<form action="{{ seccion }}/?action=updateHabitacion" id="updateHabitacion" class="form-vertical span3" name="updateHabitacion">
		<legend>Habitación</legend>

		<div class="control-group">
			<label for="hhb_clave" class="control-label">Clave:</label>
			<div class="controls">
				<input class="{required:true,maxlength:10} span2" type="text" id="hhb_clave" name="hhb_clave" placeholder="SGL" required value="{{ habitacion.hhb_clave }}" />
				<!--<span class="help-block">Ejemplos: SGL = Sencilla, DBL = Doble, HLSGL = Habitación de Lujo SGL</span>-->
			</div>
		</div>
		<div class="control-group">
			<label for="hhb_pax" class="control-label">Número de habitaciones:</label>
			<div class="controls">
				<input class="{required:true,number:true,maxlength:4} span1" type="text" id="hhb_pax" name="hhb_pax" placeholder="10" required value="{{ habitacion.hhb_pax }}" />
			</div>
		</div>
		<div class="control-group">
			<label for="hhb_paxMaxReservacion" class="control-label">Habitaciones por registro:</label>
			<div class="controls">
				<input class="{required:true,number:true,maxlength:2} span1" type="text" id="hhb_paxMaxReservacion" name="hhb_paxMaxReservacion" placeholder="5" required value="{{ habitacion.hhb_paxMaxReservacion }}" />
				<!--<span class="help-block">Es el número maximo de habitaciones que puede reservar un participante.</span>-->
			</div>
		</div>
		<div class="control-group">
			<label for="hhb_paxAdultos" class="control-label">Maximo número adultos:</label>
			<div class="controls">
				<input class="{number:true,maxlength:2} span1" type="text" id="hhb_paxAdultos" name="hhb_paxAdultos" placeholder="2" value="{{ habitacion.hhb_paxAdultos }}" />
			</div>
		</div>
		<div class="control-group">
			<label for="hhb_paxMenores" class="control-label">Maximo número menores:</label>
			<div class="controls">
				<input class="{number:true,maxlength:2} span1" type="text" id="hhb_paxMenores" name="hhb_paxMenores" placeholder="2" value="{{ habitacion.hhb_paxMenores }}" />
			</div>
		</div>
		<div class="control-group">
			<label for="hhb_status" class="control-label">Status:</label>
			<div class="controls">
				<select class="span2" name="hhb_status" id="hhb_status">
					<option value="0"{{ habitacion.hhb_status == 0 ? ' selected="selected"' : '' }}>Inactivo</option>
					<option value="1"{{ habitacion.hhb_status == 1 ? ' selected="selected"' : '' }}>Activo</option>
				</select>
			</div>
		</div>

		<div class="form-actions">
			<div class="form-alert">&nbsp;</div>
			<button class="btn btn-success">Guardar</button>
		</div>
	</form>

	<div class="span9">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th colspan="5">Tabla de costos</th>
				</tr>
				<tr>
					<th>Costo noche x adulto</th>
					<th>Costo noche x menor</th>
					<th>Costo camaristas</th>
					<th>Costo Bell Boys</th>
					<th style="width: 50px;">Editar</th>
				</tr>
			</thead>
			<tbody id="lista-costos-habitacion">
				{% if habitacion.hhc_costoAdulto %}
				<tr>
					<td>{{ habitacion.hhc_costoAdulto|number_format(2, '.', ',') }}</td>
					<td>{{ habitacion.hhc_costoMenor|number_format(2, '.', ',') }}</td>
					<td>{{ habitacion.hhc_costoCamaristaNoche|number_format(2, '.', ',') }}</td>
					<td>{{ habitacion.hhc_costoBellBoys|number_format(2, '.', ',') }}</td>
					<th>
						<a href="#updateCostoHabitacion" class="btn btn-mini" role="button" data-toggle="modal"><i class="icon-pencil"></i></a>
					</th>
				</tr>
				{% else %}
				<tr id="no-costos-habitacion">
					<th colspan="5">Aun no hay costos de habitación | <a href="#setCostoHabitacion" role="button" data-toggle="modal"><i class="icon icon-plus-sign"></i> Agregar costos habitación</a></th>
				</tr>
				{% endif %}
			</tbody>
		</table>
		{# if habitacion.hhc_costoAdulto #}
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th colspan="3">Nombres de la habitación</th>
				</tr>
				<tr>
					<th style="width: 48px;">Idioma</th>
					<th>Nombre</th>
					<th style="width: 44px;">Eliminar</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="3"><a href="#setNombreHabitacion" role="button" data-toggle="modal"><i class="icon icon-plus-sign"></i> Agregar nombre de habitación</a></td>
				</tr>
			</tfoot>
			<tbody id="lista-nombres-habitacion">
				{% for nombre in nombres %}
				<tr id="nh-{{ nombre.hhn_id }}">
					<th>{{ nombre.hhn_idioma }}</th>
					<td>{{ nombre.hhn_nombre }}</td>
					<th>
						<a href="#deleteNombreHabitacion" class="btn btn-mini eliminar-habitacion" role="button" data-toggle="modal" data-id="{{ nombre.hhn_id }}" data-idioma="{{ nombre.hhn_idioma }}"><i class="icon-remove"></i></a>
					</th>
				</tr>
				{% else %}
				<tr id="no-nombres-habitacion">
					<th colspan="5">Aun no hay nombres de habitación | <a href="#setNombreHabitacion" role="button" data-toggle="modal"><i class="icon icon-plus-sign"></i> Agregar nombre de habitación</a></th>
				</tr>
				{% endfor %}
			</tbody>
		</table>
		{# endif #}
	</div>

	<!-- Modal agregar costos -->
	<form action="{{ seccion }}/?action=setCostoHabitacion" id="setCostoHabitacion" class="modal hide fade form-horizontal" name="setCostoHabitacion" tabindex="-1" role="dialog" aria-labelledby="setCostoHabitacionLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="setCostoHabitacionLabel">Costos de la habitación</h3>
		</div>
		<div class="modal-body">
			<div class="control-group">
				<label for="hhc_costoAdulto" class="control-label">Costo noche x adulto:</label>
				<div class="controls">
					<input class="{required:true,number:true,maxlength:10} span2" type="text" id="hhc_costoAdulto" name="hhc_costoAdulto" placeholder="0.00" required />
				</div>
			</div>
			<div class="control-group">
				<label for="hhc_costoMenor" class="control-label">Costo noche x menor:</label>
				<div class="controls">
					<input class="{required:true,number:true,maxlength:10} span2" type="text" id="hhc_costoMenor" name="hhc_costoMenor" placeholder="0.00" required />
				</div>
			</div>
			<div class="control-group">
				<label for="hhc_costoCamaristaNoche" class="control-label">Costo camaristas:</label>
				<div class="controls">
					<input class="{required:true,number:true,maxlength:10} span2" type="text" id="hhc_costoCamaristaNoche" name="hhc_costoCamaristaNoche" placeholder="0.00" required />
				</div>
			</div>
			<div class="control-group">
				<label for="hhc_costoBellBoys" class="control-label">Costo Bell Boys:</label>
				<div class="controls">
					<input class="{required:true,number:true,maxlength:10} span2" type="text" id="hhc_costoBellBoys" name="hhc_costoBellBoys" placeholder="0.00" required />
				</div>
			</div>
			<!--<div class="control-group">
				<label for="hhc_cambioFecha" class="control-label">Fecha cambio de costo:</label>
				<div class="controls">
					<input class="{required:true,number:true,maxlength:10} span2" type="text" id="hhc_cambioFecha" name="hhc_cambioFecha" placeholder="0.00" required />
				</div>
			</div>-->
		</div>
		<div class="modal-footer">
			<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancelar</button>
			<button class="btn btn-success">Agregar</button>
		</div>
	</form>

	<!-- Modal actualizar costos -->
	<form action="{{ seccion }}/?action=updateCostoHabitacion" id="updateCostoHabitacion" class="modal hide fade form-horizontal" name="updateCostoHabitacion" tabindex="-1" role="dialog" aria-labelledby="updateCostoHabitacionLabel" aria-hidden="true">
		<input type="hidden" name="hhc_id" value="{{ habitacion.hhc_id }}" />
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="updateCostoHabitacionLabel">Actualizar costos de la habitación</h3>
		</div>
		<div class="modal-body">
			<div class="control-group">
				<label for="hhc_costoAdulto" class="control-label">Costo noche x adulto:</label>
				<div class="controls">
					<input class="{required:true,number:true,maxlength:10} span2" type="text" id="hhc_costoAdulto" name="hhc_costoAdulto" placeholder="0.00" required value="{{ habitacion.hhc_costoAdulto|number_format(2, '.', ',') }}" />
				</div>
			</div>
			<div class="control-group">
				<label for="hhc_costoMenor" class="control-label">Costo noche x menor:</label>
				<div class="controls">
					<input class="{required:true,number:true,maxlength:10} span2" type="text" id="hhc_costoMenor" name="hhc_costoMenor" placeholder="0.00" required value="{{ habitacion.hhc_costoMenor|number_format(2, '.', ',') }}" />
				</div>
			</div>
			<div class="control-group">
				<label for="hhc_costoCamaristaNoche" class="control-label">Costo camaristas:</label>
				<div class="controls">
					<input class="{required:true,number:true,maxlength:10} span2" type="text" id="hhc_costoCamaristaNoche" name="hhc_costoCamaristaNoche" placeholder="0.00" required value="{{ habitacion.hhc_costoCamaristaNoche|number_format(2, '.', ',') }}" />
				</div>
			</div>
			<div class="control-group">
				<label for="hhc_costoBellBoys" class="control-label">Costo Bell Boys:</label>
				<div class="controls">
					<input class="{required:true,number:true,maxlength:10} span2" type="text" id="hhc_costoBellBoys" name="hhc_costoBellBoys" placeholder="0.00" required value="{{ habitacion.hhc_costoBellBoys|number_format(2, '.', ',') }}" />
				</div>
			</div>
			<!--<div class="control-group">
				<label for="hhc_cambioFecha" class="control-label">Fecha cambio de costo:</label>
				<div class="controls">
					<input class="{required:true,number:true,maxlength:10} span2" type="text" id="hhc_cambioFecha" name="hhc_cambioFecha" placeholder="0.00" required />
				</div>
			</div>-->
		</div>
		<div class="modal-footer">
			<span class="form-alert pull-left">&nbsp;</span>
			<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancelar</button>
			<button class="btn btn-success">Guardar</button>
		</div>
	</form>

	<!-- Modal agregar nombre -->
	<form action="{{ seccion }}/?action=setNombreHabitacion" id="setNombreHabitacion" class="modal hide fade form-horizontal" name="setNombreHabitacion" tabindex="-1" role="dialog" aria-labelledby="setNombreHabitacionLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="setNombreHabitacionLabel">Agregar nombre a la habitación</h3>
		</div>
		<div class="modal-body">
			<div class="control-group">
				<label for="hhn_idioma" class="control-label">Idioma:</label>
				<div class="controls">
					<select class="{required:true}" id="hhn_idioma" name="hhn_idioma" required>
						<option value="">Seleccione:</option>
						{% for idioma in idiomas %}
						<option value="{{ idioma.eis_idioma }}">{{ idioma.eis_nombre }}</option>
						{% endfor %}
					</select>
				</div>
			</div>
			<div class="control-group">
				<label for="hhn_nombre" class="control-label">Nombre:</label>
				<div class="controls">
					<input class="{required:true} span3" type="text" id="hhn_nombre" name="hhn_nombre" required />
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancelar</button>
			<button class="btn btn-success">Agregar</button>
		</div>
	</form>

	<!-- Modal eliminar nombre -->
	<form action="{{ seccion }}/?action=deleteNombreHabitacion" id="deleteNombreHabitacion" class="modal hide fade form-horizontal" name="deleteNombreHabitacion" tabindex="-1" role="dialog" aria-labelledby="deleteNombreHabitacionLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="deleteNombreHabitacionLabel">Eliminar nombre de la habitación</h3>
		</div>
		<div class="modal-body">
			<p class="lead text-important text-center">¿Realmete deseas eliminar el nombre?</p>
			<div class="form-alert">&nbsp;</div>
		</div>
		<div class="modal-footer">
			<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancelar</button>
			<button class="btn btn-success">Agregar</button>
		</div>
		<input class="{required:true}" type="hidden" id="hhn_id" name="hhn_id" value="" />
		<input class="{required:true}" type="hidden" id="hhn_idioma_del" name="hhn_idioma_del" value="" />
	</form>
</div>
<script>
$(function() {
	/**
	 * agregar costos de la habitación
	 */
	$('#setCostoHabitacion').validate({
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

						$('#hhc_id').val(sRes.id_costo_habitacion);

						if ($('#no-costos-habitacion').length) {
							$('#no-costos-habitacion').remove();
						}

						_hotel = '<tr>' +
							'<td>' + sRes.costo_habitacion.hhc_costoAdulto + '</td>' +
							'<td>' + sRes.costo_habitacion.hhc_costoMenor + '</td>' +
							'<td>' + sRes.costo_habitacion.hhc_costoCamaristaNoche + '</td>' +
							'<td>' + sRes.costo_habitacion.hhc_costoBellBoys + '</td>' +
							'<th>' +
								'<a href="#updateCostoHabitacion" class="btn btn-mini" role="button" data-toggle="modal"><i class="icon-pencil"></i></a>' +
							'</th>' +
						'</tr>';

						$(_hotel).appendTo('#lista-costos-habitacion');
						
						$('#hhc_costoAdulto').val('');
						$('#hhc_costoMenor').val('');
						$('#hhc_costoCamaristaNoche').val('');
						$('#hhc_costoBellBoys').val('');

						$('#setCostoHabitacion').modal('hide');
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

	/**
	 * actualizar costos de la habitación
	 */
	$('#updateCostoHabitacion').validate({
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

						/*if ($('#no-costos-habitacion').length) {
							$('#no-costos-habitacion').remove();
						}*/

						_hotel = '<tr>' +
							'<td>' + sRes.costo_habitacion.hhc_costoAdulto + '</td>' +
							'<td>' + sRes.costo_habitacion.hhc_costoMenor + '</td>' +
							'<td>' + sRes.costo_habitacion.hhc_costoCamaristaNoche + '</td>' +
							'<td>' + sRes.costo_habitacion.hhc_costoBellBoys + '</td>' +
							'<th>' +
								'<a href="#updateCostoHabitacion" class="btn btn-mini" role="button" data-toggle="modal"><i class="icon-pencil"></i></a>' +
							'</th>' +
						'</tr>';

						$('#lista-costos-habitacion').html(_hotel);
						
						$('#hhc_costoAdulto').val('');
						$('#hhc_costoMenor').val('');
						$('#hhc_costoCamaristaNoche').val('');
						$('#hhc_costoBellBoys').val('');

						$('#updateCostoHabitacion').modal('hide');
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

	/**
	 * Actuaizar habitación
	 */
	$('#updateHabitacion').validate({
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
					_form.find('.form-alert').html('&nbsp;').removeClass('text-error text-success');
				},
				success: function(sRes) {
					_form.find('.form-alert').html(sRes.mensaje).show();
					if (!sRes.status) {
						_form.find('.form-alert').addClass('text-error');
					} else {
						_form.find('.form-alert').addClass('text-success');
					}
					setTimeout(function() {
						_form.find('.form-alert').fadeOut('slow');
					}, 3000);
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

	/**
	 * agregar costos de la habitación
	 */
	$('#setNombreHabitacion').validate({
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

						if ($('#no-nombres-habitacion').length) {
							$('#no-nombres-habitacion').remove();
						}

						_nombre = '<tr id="nh-' + sRes.id_nombre + '">' +
							'<th>' + sRes.nombre.hhn_idioma + '</th>' +
							'<td>' + sRes.nombre.hhn_nombre + '</td>' +
							'<th>' +
								'<a href="#deleteNombreHabitacion" class="btn btn-mini eliminar-habitacion" role="button" data-toggle="modal" data-id="' + sRes.id_nombre + '" data-idioma="' + sRes.nombre.hhn_idioma + '"><i class="icon-remove"></i></a>' +
							'</th>' +
						'</tr>';

						$('#hhn_idioma > option[value="' + sRes.nombre.hhn_idioma + '"]').remove();

						$(_nombre).appendTo('#lista-nombres-habitacion');
						
						$('#hhn_idioma').val('');
						$('#hhn_nombre').val('');

						$('#setNombreHabitacion').modal('hide');
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

	$('body').on('click', '.eliminar-habitacion', function() {
		$('#hhn_id').val($(this).data('id'));
		$('#hhn_idioma_del').val($(this).data('idioma'));
	});

	/**
	 * agregar costos de la habitación
	 */
	$('#deleteNombreHabitacion').validate({
		submitHandler: function(form) {
			var _form = $(form),
				_action = _form.attr('action'),
				queryString = $(form).serialize();

			$.ajax({
				type: 'GET',
				url: _action,
				data: queryString,
				dataType: 'json',
				beforeSend: function() {
					_form.find('button').attr('disabled', 'disabled');
					_form.find('.form-alert').html('&nbsp;');
				},
				success: function(sRes) {
					if (!sRes.status) {
						_form.find('.form-alert').html(sRes.mensaje);
					} else {

						$('#nh-' + sRes.idioma.hhn_id).remove();
						
						$('#hhn_id').val('');
						$($('<option />', {value: sRes.idioma.hhn_idioma_del, text: sRes.idioma.hhn_nombre_del})).appendTo('#hhn_idioma');

						$('#deleteNombreHabitacion').modal('hide');
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