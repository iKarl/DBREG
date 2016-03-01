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

<div id="hospedaje">

	<div class="well well-small">
		<strong>Lista de hoteles del evento</strong>
		<a href="#setHotel" role="button" class="btn btn-info btn-mini pull-right" data-toggle="modal"><i class="icon-plus-sign icon-white"></i> Agregar hotel</a>
	</div>

	<table class="table table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th>Hoteles</th>
				<th style="width:53px;">Status</th>
				<th style="width:100px;">Administrar</th>
			</tr>
		</thead>
		<tbody id="lista-hoteles">
			{% for hotel in hoteles %}
			<tr id="htl-{{ hotel.htl_id }}">
				<td>{{ hotel.htl_nombre }} <small class="muted">Incluye: {{ hotel.htl_incluye }}</small></td>
				<th>
					{% if hotel.htl_status == 1 %}
					<span class="label label-success">Activo</span>
					{% else %}
					<span class="label label-important">Inactivo</span>
					{% endif %}
				</th>
				<th>
					<a href="#setHotel" role="button" class="btn btn-mini actualizarHotel" data-toggle="modal" data-hotel="{{ hotel.htl_id }}"><i class="icon-pencil"></i></a>
					<a href="{{ seccion }}/?action=verHotel&amp;id={{ hotel.htl_id }}" class="btn btn-mini verHotel" data-load="seccion" data-opts='{"target":"evento"}'><i class="icon-cog"></i></a>
				</th>
			</tr>
			{% else %}
			<tr id="no-hoteles">
				<th colspan="5">Aun no hay hoteles | <a href="#setHotel" role="button" data-toggle="modal"><i class="icon icon-plus-sign"></i> Agregar hotel</a></th>
			</tr>
			{% endfor %}
		</tbody>
	</table>

	<!-- Modal -->
	<form action="{{ seccion }}/?action=setHotel" id="setHotel" class="modal hide fade" name="setHotel" tabindex="-1" role="dialog" aria-labelledby="nuevoHotelLabel" aria-hidden="true">
		<input type="hidden" id="accion" name="accion" value="set" />
		<input type="hidden" id="htl_id" name="htl_id" value="0" />
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="nuevoHotelLabel">Agregar hotel</h3>
		</div>
		<div class="modal-body">
			<div class="control-group">
				<label for="htl_nombre" class="control-label">Hotel:</label>
				<div class="controls">
					<input class="{required:true,maxlength:175} span5" type="text" id="htl_nombre" name="htl_nombre" required />
				</div>
			</div>
			<div class="control-group">
				<label for="htl_incluye" class="control-label">Lo que incluye:</label>
				<div class="controls">
					<input class="{maxlength:255} span5" type="text" id="htl_incluye" name="htl_incluye" />
				</div>
			</div>
			<div class="control-group">
				<label for="htl_direccion" class="control-label">Dirección:</label>
				<div class="controls">
					<input class="{maxlength:255} span5" type="text" id="htl_direccion" name="htl_direccion" />
				</div>
			</div>
			<div class="control-group">
				<label for="htl_status" class="control-label">Status:</label>
				<div class="controls">
					<select class="{required:true} span2" id="htl_status" name="htl_status">
						<option value="">Seleccione:</option>
						<option value="0">Inactivio</option>
						<option value="1">Activo</option>
					</select>
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
	$('#setHotel').validate({
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

						if ($('#no-hoteles').length) {
							$('#no-hoteles').remove();
						}

						def_status = sRes.hotel.htl_status == 1 ? 
							'<span class="label label-success">Activo</span>' :
							'<span class="label label-important">Inactivio</span>';

						_hotel = '<tr id="htl-' + sRes.id_hotel + '">' +
							'<td>' + sRes.hotel.htl_nombre + ' <small class="muted">Incluye: ' + sRes.hotel.htl_incluye + '</small></td>' +
							'<th>' + def_status + '</th>' +
							'<th>' +
								'<a href="#setHotel" role="button" class="btn btn-mini actualizarHotel" data-toggle="modal" data-hotel="' + sRes.id_hotel + '"><i class="icon-pencil"></i></a>' +
								' <a href="{{ seccion }}/?action=verHotel&amp;id=' + sRes.id_hotel + '" class="btn btn-mini" data-load="seccion" data-opts=\'{"target":"evento"}\'><i class="icon-cog"></i></a>' +
							'</th>' +
						'</tr>';

						if (sRes.hotel.accion == 'set') {
							$(_hotel).appendTo('#lista-hoteles');
						} else {
							$('#htl-' + sRes.id_hotel).replaceWith(_hotel);
						}
						
						$('#htl_nombre').val('');
						$('#htl_incluye').val('');
						$('#htl_direccion').val('');
						$('#htl_status > option').removeAttr('selected');

						$('#setHotel').modal('hide');
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

	$('a[href="#setHotel"]').click(function() {
		$('#nuevoHotelLabel').text('Agregar hotel');
		$('#setHotel').find('.btn-success').text('Agregar');
		$('#setHotel').attr('action', '{{ seccion }}/?action=setHotel');
		$('#accion').val('set');

		$('#htl_nombre').val('');
		$('#htl_incluye').val('');
		$('#htl_direccion').val('');
		$('#htl_status > option').removeAttr('selected');
	});

	$('body').on('click', '.actualizarHotel', function() {
		$('#nuevoHotelLabel').text('Actualizar hotel');
		$('#setHotel').find('.btn-success').text('Actualizar');
		$('#setHotel').attr('action', '{{ seccion }}/?action=updateHotel');
		$('#accion').val('update');
		$('#htl_id').val($(this).data('hotel'));

		$.ajax({
			type: 'GET',
			url: '{{ seccion }}/?action=getHotel',
			data: {htl_id: $(this).data('hotel')},
			dataType: 'json',
			beforeSend: function () {

			},
			success: function (sRes) {
				if (sRes.hotel) {
					$('#htl_nombre').val(sRes.hotel.htl_nombre);
					$('#htl_incluye').val(sRes.hotel.htl_incluye);
					$('#htl_direccion').val(sRes.hotel.htl_direccion);
					$('#htl_status > option[value="' + sRes.hotel.htl_status + '"]').attr('selected', 'selected');
				} else {

				}
			}
		});
	});
});
</script>