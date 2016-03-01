<table id="resumen" width="100%">
	<tr>
		<td style="width: 50%; vertical-align: top;">
			<div class="page-header">
				<h4>Resumen</h4>
			</div>
			<table class="table table-condensed table-bordered">
				<thead>
					<tr>
						<th colspan="2">Nombre del participante</th>
						<th>Costo</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="2">{{ registro.nombre }} {{ registro.app }} {{ registro.apm }}</td>
						<td class="span1" style="text-align: right;"><strong>{{ registro.costo_registro|number_format(2, '.', ',') }}</strong></td>
					</tr>
					<tr>
						<th colspan="3"><strong>Acompañantes</strong> <a href="#setAcompanante" role="button" class="btn btn-mini" data-toggle="modal"><i class="icon-plus-sign"></i> Agregar</a></th>
					</tr>
					{% for acompanante in acompanantes %}
					<tr id='acm-{{ acompanante.id_acompanante }}'>
						<td style="width: 75px;">
							<a class="btn btn-mini del-acompanante" rel="caja/?action=delAcompanante&amp;id={{ acompanante.id_acompanante }}" href="#delAcompanante" role="button" data-toggle="modal"><i class="icon-remove-sign"></i> Eliminar</a>
						</td>
						<td>
							{{ acompanante.acm_nombre }} {{ acompanante.acm_app }} {{ acompanante.acm_apm }}
						</td>
						<td class="span1" style="text-align: right;"><strong>{{ acompanante.acm_costo|number_format(2, '.', ',') }}</strong></td>
					</tr>
					{% endfor %}
					<tr>
						<th colspan="3"><strong>Adicionales</strong> <a href="#setAdicional" role="button" class="btn btn-mini" data-toggle="modal"><i class="icon-plus-sign"></i> Agregar</a></th>
					</tr>
					{% for adicional in adicionales %}
					<tr id="item-{{ adicional.id_item }}">
						<td style="width: 75px;">
							<a class="btn btn-mini del-adicional" rel="caja/?action=delAdicional&amp;id={{ adicional.id_item }}" href="#delAdicional" role="button" data-toggle="modal"><i class="icon-remove-sign"></i> Eliminar</a>
						</td>
						<td>
							{% for item in items %}
								{{ item.eni_clave == adicional.item_clave ? item.eni_nombre : '' }}
							{% endfor %}
							<span style="float: right;">Unit. {{ adicional.item_costo_unitario|number_format(2, '.', ',') }} x {{ adicional.item_cantidad }}</span>
						</td>
						<td class="span1" style="text-align: right;"><strong>{{ adicional.item_costo_total }}</strong></td>
					</tr>
					{% endfor %}
					<tr>
						<td colspan="2" style="text-align: right; font-size: 1.4em;">
							<strong>Total:</strong>
						</td>
						<td style="font-size: 1.4em;"><strong>{{ sumaCostoTotalRegistro }}</strong></td>
					</tr>
				</tbody>
			</table>
		</td>
		<td style="width: 50%; vertical-align: top;">
			<div class="page-header">
				<h4>Pago</h4>
			</div>
			<form action="caja/?action=pagar" class="form-horizontal" id="pagar" method="post" name="pagar">
				<input name="id_registro" type="hidden" value="{{ registro.id_registro }}" />
				<input name="id_cobro" type="hidden" value="{{ registro.id_cobro }}" />

				<div class="control-group">
					<label for="" class="control-label">Costo total:</label>
					<div class="controls">
						<input class="{required:true} span2" id="caja_costo_total" name="caja_costo_total" type="text" value="{{ costoRegistro }}" />
					</div>
				</div>
				<div class="control-group">
					<label for="" class="control-label">Descuento:</label>
					<div class="controls">
						<input class="{number:true} span2" id="caja_costo_descuento" name="caja_costo_descuento" type="text" value="{{ registro.descuento }}" placeholder="0.00" />
					</div>
				</div>
				<div class="control-group">
					<label for="" class="control-label">Folio de pago:</label>
					<div class="controls">
						<input class="" id="caja_folio_pago" name="caja_folio_pago" type="text" value="{{ registro.folio_pago }}" />
					</div>
				</div>
				<div class="control-group">
					<label for="" class="control-label">Forma de pago:</label>
					<div class="controls">
						<select class="{required:true}" name="caja_forma_pago" id="caja_forma_pago">
							<option value="">Seleccione:</option>
							{% for forma_pago in formas_pago %}
							<option value="{{ forma_pago.fpn_clave }}"{{ forma_pago.fpn_clave == registro.forma_pago ? ' selected="selected"' : '' }}>{{ forma_pago.fpn_nombre }}</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="control-group">
					<label for="" class="control-label">Comentarios:</label>
					<div class="controls">
						<textarea class="span3" name="comentarios" id="comentarios" rows="3">{{ registro.comentarios }}</textarea>
					</div>
				</div>
				<div class="control-group">
					<label for="" class="control-label">Status del registro:</label>
					<div class="controls">
						<select class="{required:true}" name="caja_status_pago" id="caja_status_pago">
							<option value="">Seleccione:</option>
							{% for status in status_registro %}
							<option value="{{ status.esr_clave }}"{{ status.esr_clave == registro.status ? ' selected="selected"' : '' }}>{{ status.esr_nombre }}</option>
							{% endfor %}
						</select>
					</div>
				</div>

				<!-- Datos de facturación -->
				<fieldset class="horizontal">
					<div class="control-group">
						<div class="controls">
							<label class="checkbox">
								<input id="requiere_factura" name="requiere_factura" type="checkbox" value="1"{{ registro.requiere_factura == 1 ? ' checked="checked"' : '' }} /> Requiere factura
							</label>
						</div>
					</div>
				</fieldset>

				<fieldset class="horizontal{{ registro.requiere_factura == 1 ? '' : ' hide' }}" id="datos-facturacion-registro">
					<div class="control-group">
						<label class="control-label" for="razon_social_RS">Razon social:</label>
						<div class="controls">
							<input class="span5" id="razon_social_RS" name="razon_social_RS" type="text" value="{{ registro.razon_social_RS }}" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="rfc_RS">RFC:</label>
						<div class="controls">
							<input class="span3" id="rfc_RS" name="rfc_RS" placeholder="Solo para México" type="text" value="{{ registro.rfc_RS }}"{{ registro.pais_RS and registro.pais_RS != 146 ? ' disabled' : '' }} />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="direccion_RS">Dirección (Calle y número):</label>
						<div class="controls">
							<input class="span4" id="direccion_RS" name="direccion_RS" type="text" value="{{ registro.direccion_RS }}" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="cp_RS">Codigo postal:</label>
						<div class="controls">
							<input class="span1" id="cp_RS" name="cp_RS" type="text" value="{{ registro.cp_RS }}" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="colonia_RS">Colonia:</label>
						<div class="controls">
							<input class="span2" id="colonia_RS" name="colonia_RS" type="text" value="{{ registro.colonia_RS }}" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="del_o_mun_RS">Delegación o municipio:</label>
						<div class="controls">
							<input class="span2" id="del_o_mun_RS" name="del_o_mun_RS" placeholder="Solo para México" type="text" value="{{ registro.del_o_mun_RS }}"{{ registro.pais_RS and registro.pais_RS != 146 ? ' disabled' : '' }} />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="ciudad_RS">Ciudad:</label>
						<div class="controls">
							<input class="span2" id="ciudad_RS" name="ciudad_RS" type="text" value="{{ registro.ciudad_RS }}" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="estado_RS">Estado:</label>
						<div class="controls">
							<input class="span2" id="estado_RS" name="estado_RS" type="text" value="{{ registro.estado_RS }}" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="pais_RS">País:</label>
						<div class="controls">
							<select class="" id="pais_RS" name="pais_RS">
								<option value="">Seleccione:</option>
								{% for pais in paises %}
								<option value="{{ pais.pais_idPais }}"

								{% if registro.pais_RS %}
									{% if registro.pais_RS == pais.pais_idPais %}
										 selected
									{% endif %}
								{% else %}
									{% if pais.pais_idPais == 146 %}
										selected
									{% endif %}
								{% endif %}

								>{{ pais.pais_nombreEs }}</option>
								{% else %}
								<option value="">No se recuperaron los paises</option>
								{% endfor %}
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="email_RS">Correo electrónico para facturación electrónica:</label>
						<div class="controls">
							<input class="span4" id="email_RS" name="email_RS" type="email" value="{{ registro.email_RS }}" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="lada_telefono_RS">Lada:</label>
						<div class="controls">
							<input class="input-mini" id="lada_telefono_RS" name="lada_telefono_RS" type="text" value="{{ registro.lada_telefono_RS }}" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="telefono_RS">Teléfono:</label>
						<div class="controls">
							<input class="span2" id="telefono_RS" name="telefono_RS" type="text" value="{{ registro.telefono_RS }}" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="fax_RS">Fax:</label>
						<div class="controls">
							<input class="span2" id="fax_RS" name="fax_RS" type="text" value="{{ registro.fax_RS }}" />
						</div>
					</div>
				</fieldset>

				<div class="form-actions">
					<div class="form-error">&nbsp;</div>
					<button class="btn" id="regresar" type="button"><i class="icon-arrow-left"></i> Regresar</button>
					<button class="btn btn-success" type="submit">Guardar</button>
					<span class="lead checkbox-inline text-success">
						<strong class="checkbox-inline">Revisión factura:</strong> <input class="checkbox-inline" id="revision_factura" name="revision_factura" type="checkbox" value="1"{{ registro.revision_factura == 1 ? ' checked="checked"' : '' }} />
					</span>
				</div>
			</form>
		</td>
	</tr>
</table>

<form action="caja/?action=setAcompanante" id="setAcompanante" method="post" name="setAcompanante" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="cajaAcompananteAltaLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="cajaAcompananteAltaLabel">Agregar Acompañanate</h3>
	</div>
	<div class="modal-body">
		<input name="acm_idRegistro" type="hidden" value="{{ registro.id_registro }}" />

		<fieldset class="horizontal">
			<div class="control-group">
				<label class="control-label" for="acm_clave">Tipo de acompañante</label>
				<div class="controls">
					<select class="{required:true}" id="acm_clave" name="acm_clave" required>
						<option value="">Seleccione:</option>
						{% for categoria in categoriasAcom %}
						<option value="{{ categoria.ecan_clave }}">{{ categoria.ecan_nombre }}</option>
						{% else %}
						<option value="">No hay categorias de acompañantes</option>
						{% endfor %}
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="acm_id_costo">Costo del acompañante:</label>
				<div class="controls">
					<select class="{required:true} span2" required id="acm_id_costo" name="acm_id_costo" disabled>
						<option value="">Seleccione:</option>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="acm_genero">Genero</label>
				<div class="controls">
					<select id="acm_genero" name="acm_genero">
						<option value="">Seleccione:</option>
						{% for genero in generos %}
						<option value="{{ genero.gen_clave }}">{{ genero.gen_nombre }}</option>
						{% endfor %}
					</select>
				</div>
			</div><br />
			<div class="control-group">
				<label class="control-label" for="acm_nombre">Nombre</label>
				<div>
					<input class="{required:true} span2" id="acm_nombre" name="acm_nombre" type="text" required />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="acm_app">Apellido paterno</label>
				<div>
					<input class="{required:true} span2" id="acm_app" name="acm_app" type="text" required />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="acm_apm">Apellido materno</label>
				<div>
					<input class="span2" id="acm_apm" name="acm_apm" type="text" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="acm_titulo">Titulo</label>
				<div class="controls">
					<select id="acm_titulo" name="acm_titulo">
						<option value="">Seleccione:</option>
						{% for titulo in titulos %}
						<option value="{{ titulo.ect_clave }}">{{ titulo.ect_clave }}</option>
						{% else %}
						<option value="">Aun no existen titulos para el evento</option>
						{% endfor %}
					</select>
				</div>
			</div>
		</fieldset>
	</div>
	<div class="modal-footer">
		<div class="form-error">&nbsp;</div>
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		<button class="btn btn-success" type="submit">
			<i class="icon-ok-sign icon-white"></i> Agregar
		</button>
	</div>
</form>

<form action="" id="delAcompanante" method="post" name="delAcompanante" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="delAcompananteLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="delAcompananteLabel">Confirmación</h3>
	</div>
	<div class="modal-body">
		<input name="acm_idRegistro" type="hidden" value="{{ registro.id_registro }}" />
		<div class="lead" style="text-align: center;"><strong>¿Realmente desea eliminar al acompañante?</strong></div>
	</div>
	<div class="modal-footer">
		<div class="form-error">&nbsp;</div>
		<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
		<button class="btn btn-success" type="submit">
			<i class="icon-ok-sign icon-white"></i> Aceptar
		</button>
	</div>
</form>

<form action="caja/?action=setAdicional" id="setAdicional" method="post" name="setAdicional" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="cajaItemAltaLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="cajaItemAltaLabel">Modal header</h3>
	</div>
	<div class="modal-body">
		<input name="item_idRegistro" type="hidden" value="{{ registro.id_registro }}" />

		<fieldset class="horizontal">
			<div class="control-group">
				<label class="control-label" for="item_clave">Item:</label>
				<div class="controls">
					<select class="{required:true}" id="item_clave" name="item_clave" required>
						<option value="">Seleccione:</option>
						{% for item in items %}
						<option value="{{ item.eni_clave }}"{{ item.eci_paxMaximos != 0 and item.total_conf.filas >= item.eci_paxMaximos ? ' disabled' : '' }}>
							{{ item.eni_nombre }} {{ item.eci_paxMaximos != 0 and item.total_conf.filas >= item.eci_paxMaximos ? '- Agotado' : '' }}
						</option>
						{% else %}
						<option value="">No hay categorias de items</option>
						{% endfor %}
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="item_id_costo">Costo unitario:</label>
				<div class="controls">
					<select class="{required:true}" id="item_id_costo" name="item_id_costo" disabled required>
						<option value="">Seleccione:</option>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="item_cantidad">Cantidad:</label>
				<div class="controls">
					<input class="{required:true} span1" autocomplete="on" id="item_cantidad" name="item_cantidad" type="text" required />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="item_costo_total">Total:</label>
				<div class="controls">
					$ <input class="{required:true} input-small" id="item_costo_total" name="item_costo_total" readonly type="text" value="0.00" required />
				</div>
			</div>
		</fieldset>
	</div>
	<div class="modal-footer">
		<div class="form-error">&nbsp;</div>
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		<button class="btn btn-success" type="submit">
			<i class="icon-ok-sign icon-white"></i> Agregar
		</button>
	</div>
</form>

<form action="" id="delAdicional" method="post" name="delAdicional" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="delAdicionalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="delAdicionalLabel">Confirmación</h3>
	</div>
	<div class="modal-body">
		<input name="item_idRegistro" type="hidden" value="{{ registro.id_registro }}" />
		<div class="lead" style="text-align: center;"><strong>¿Realmente desea eliminar el adicional?</strong></div>
	</div>
	<div class="modal-footer">
		<div class="form-error">&nbsp;</div>
		<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
		<button class="btn btn-success" type="submit">
			<i class="icon-ok-sign icon-white"></i> Aceptar
		</button>
	</div>
</form>

<script>
$(function() {
	/**
	 * AGREGAR ACOMPANANTE
	 */
	$('#setAcompanante').validate({
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
						$('#setAcompanante').modal('hide');
						$.get('caja/?action=cobrar', {id: sRes.acomp.acm_idRegistro }, function(sRes) {
							$('#cobrar').html(sRes);
						});
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

	$('.del-acompanante').click(function(event) {
		event.preventDefault();
		$('#delAcompanante').attr('action', $(this).attr('rel'));
	});

	/**
	 * ELIMINAR ACOMPAÑANTE
	 */
	$('#delAcompanante').validate({
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
						$('#delAcompanante').modal('hide');
						$.get('caja/?action=cobrar', {id: sRes.registro }, function(sRes) {
							$('#cobrar').html(sRes);
						});
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
	 * AGREGAR ADICIONAL
	 */
	$('#setAdicional').validate({
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
						$('#setAdicional').modal('hide');
						$.get('caja/?action=cobrar', {id: sRes.item.item_idRegistro }, function(sRes) {
							$('#cobrar').html(sRes);
						});
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

	// Colocamos el costo total de los items
	$("body").on("keyup", "#item_cantidad", function() {
		var $input = $(this),
			$num = $input.val(),
			$p = $input.parents(".horizontal"),
			$clave = $p.find("#item_clave").val(),
			$idCosto = $p.find("#item_id_costo").val();

		$.ajax({
			type: "POST",
			url: "caja/?action=getCostoTotalItem",
			data: {"cantidad": $num, "clave": $clave, "idCosto": $idCosto},
			success: function(sRes) {
				$("#item_costo_total").val(sRes);
			}
		});
	});

	// Seteamos a cero los campos cantidad y costo total de los items
	$("#item_id_costo, #item_clave").on({
		change: function() {
			$('#item_cantidad').val('');
			$('#item_costo_total').val('0.00');
		}
	});

	$('.del-adicional').click(function(event) {
		event.preventDefault();
		$('#delAdicional').attr('action', $(this).attr('rel'));
	});

	/**
	 * ELIMINAR ADICIONAL
	 */
	$('#delAdicional').validate({
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
						$('#delAdicional').modal('hide');
						$.get('caja/?action=cobrar', {id: sRes.registro }, function(sRes) {
							$('#cobrar').html(sRes);
						});
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
	 * AGREGAR ADICIONAL
	 */
	$('#pagar').validate({
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
					_form.find('.form-error').html(sRes.mensaje);
					if (!sRes.status) {
						_form.find('button').removeAttr('disabled');
					} else {
						setTimeout(function() {
							$('#resumen').remove();
							$('#caja').show();
						}, 3500);
					}
				}
			});

			return false;
		},
		errorPlacement: function(error, element) {
			error.appendTo(element.parent().append());
		}
	});

	$('#requiere_factura').click(function() {
		if ($(this).is(':checked')) {
			$('#datos-facturacion-registro').removeClass('hide');
		} else {
			$('#datos-facturacion-registro').addClass('hide');
		}
	});
});
</script>