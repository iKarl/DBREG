<div id="caja">
	<div class="page-header" style="margin-bottom: 5px;">
		<h4>
			{{ tituloSeccion }}
		</h4>
	</div>

	<div>
		<a class="btn btn-info" href="#guardarRegistro" role="button" data-toggle="modal">
			<i class="icon-plus-sign icon-white"></i> Agregar
		</a>

		<form action="{{ seccion }}/index?action=buscarRegistros" data-load="ajax" data-validate="false" id="buscarRegistros" method="post" name="buscarRegistros">
			<table class="table table-bordered table-condensed table-hover">
				<thead>
					<tr>
						<th>ID:</th>
						<th>Paterno</th>
						<th>Nombre</th>
						<th>Empresa o institución</th>
					</tr>
					<tr>
						<th>
							<input class="span1" type="text" name="id_registro" id="id_registro" autofocus />
						</th>
						<th>
							<input class="input-block" type="text" name="app" id="app" />
						</th>
						<th>
							<input class="input-block" type="text" name="nombre" id="nombre" />
						</th>
						<th>
							<input class="input-block" type="text" name="emp_o_ins" id="emp_o_ins" />
						</th>
					</tr>
				</thead>
				<tbody id="registros"></tbody>
			</table>
		</form>
	</div>

	<form action="{{ seccion }}/?action=guardarRegistro" id="guardarRegistro" method="post" name="guardarRegistro" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="guardarRegistroLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="guardarRegistroLabel">Agregar usuario</h3>
		</div>
		<div class="modal-body">
			<fieldset class="horizontal">
				<div class="control-group">
					<label class="control-label" for="titulo">Titulo</label>
					<div class="controls">
						<select name="titulo">
							<option value="">Seleccione:</option>
							{% for titulo in titulos %}
							<option value="{{ titulo.ect_clave }}">{{ titulo.ect_clave }}</option>
							{% else %}
							<option value="">Aun no existen titulos para el evento</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="nombre">Nombre</label>
					<div>
						<input class="{required:true} span2" name="nombre" type="text" required />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="app">Apellido paterno</label>
					<div>
						<input class="{required:true} span2" name="app" type="text" required />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="apm">Apellido materno</label>
					<div>
						<input class="span2" name="apm" type="text" />
					</div>
				</div>
				<div class="control-group">	
					<label class="control-label" for="cat_registro">Categoria (Tipo de registro):</label>
					<div class="controls">
						<select class="span2" id="cat_registro" name="cat_registro" required>
							<option value="">Seleccione:</option>
							{% for categoria in categorias %}
							<option value="{{ categoria.enc_clave }}">{{ categoria.enc_nombre }}</option>
							{% else %}
							<option value="">No hay categorias para el evento</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="id_costo">Costo:</label>
					<div class="controls">
						<div class="input-append">
							<select class="span2" id="id_costo" name="id_costo" required>
								<option value="">Seleccione:</option>
							</select>
						</div>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="forma_pago">Forma de Pago:</label>
					<div class="controls">
						<select class="span2" id="forma_pago" name="forma_pago" required>
							<option value="">Seleccione:</option>
							{% for formaPago in formasPago %}
							<option value="{{ formaPago.fpn_clave }}">{{ formaPago.fpn_nombre }}</option>
							{% else %}
							<option value="">Aun no hay formas de pago para este evento</option>
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
</div>

<div id="cobrar"></div>

<script>
$(function() {

	// Autocompleta el ID
	$( "#id_registro" ).on({
		keyup: function() {
			$("#buscarRegistros").submit();
		}
	});

	// Autocompleta el app
	$( "#app" ).on({
		keyup: function() {
			$("#buscarRegistros").submit();
		}
	});

	// Autocompleta el nombre
	$( "#nombre" ).on({
		keyup: function() {
			$("#buscarRegistros").submit();
		}
	});

	// Autocompleta el nombre
	$( "#emp_o_ins" ).on({
		keyup: function() {
			$("#buscarRegistros").submit();
		}
	});

	mostrarRegistros = function(sRes) {
		seccion = sRes.seccion;
		registro = sRes.registros;
		$tr = "";

		//if (sRes.registros != undefined) {

			for (i in registro) {

				style = "";

				if (registro[i].status == "PAG") {
					style = " btn-success";
				} else if (registro[i].status == "COR" || registro[i].status == "CCC") {
					style = " btn-info";
				} else if (registro[i].status == "PEN") {
					style = " btn-warning";
				} else if (registro[i].status == "CAN") {
					style = " btn-danger";
				}

				$tr += '<tr>';
					$tr += '<td>';
						$tr += '<div style="text-align: center;">';
							$tr += '<a class="btn btn-mini' + style + ' ver-caja" href="' + seccion + '/?action=cobrar&amp;id=' + registro[i].id_registro + '">';
								$tr += '<i class="icon-eye-open icon-white"></i>';
							$tr += '</a>';

						/*if (registro[i].status == "PAG" || registro[i].status == "COR" || registro[i].status == "CCC") {
							$tr += '<a href="#" data-toggle="dropdown" class="btn btn-mini' + style + ' dropdown-toggle"><i class="icon-print icon-white"></i></a>';
							$tr += '<ul class="dropdown-menu idReg-' + registro[i].id_registro + '">';
								$tr += '<li>';
									$tr += '<a data-load="accion" href="' + seccion + '/?action=impresionGafete&amp;id=' + registro[i].id_registro + '">';
										$tr += '<i class="icon-barcode"></i> Imprimir Gafete';
										$tr += (registro[i].impresion_gafete == 1) ? ' <i class="icon-ok-circle"></i>' : '';
									$tr += '</a>';
								$tr += '</li>';
								$tr += '<li>';
									$tr += '<a data-load="accion" href="' + seccion + '/?action=impresionConstancia&amp;id=' + registro[i].id_registro + '">';
										$tr += '<i class="icon-qrcode"></i> Imprimir Constancia';
										$tr += (registro[i].impresion_constancia == 1) ? ' <i class="icon-ok-circle"></i>' : '';
									$tr += '</a>';
								$tr += '</li>';
							$tr += '</ul>';
						}*/

						$tr += '</div>';
					$tr += '</td>';
					//$tr += '<td>' + registro[i].id_registro + '</td>';
					$tr += '<td>' + registro[i].app + '</td>';
					//$tr += '<td>' + registro[i].apm + '</td>';
					$tr += '<td>' + registro[i].nombre + '</td>';
					//$tr += '<td>' + registro[i].email + '</td>';
					$tr += '<td>' + registro[i].emp_o_ins + '</td>';
				$tr += '</tr>';

			} // recorrido de registros encontrados

			$("#registros").html($tr);
		//}
	};

	$('body').on('click', '.ver-caja', function(event) {
		event.preventDefault();

		$.ajax({
			type: 'GET',
			url: $(this).attr('href'),
			beforeSend: function() {
				$('#caja').hide();
			},
			success: function(sRes) {
				$('#cobrar').html(sRes);
			}
		});
	});

	$('body').on('click', '#regresar', function() {
		$('#resumen').remove();
		$('#caja').show();
	});

	// Asignamos costos de los acompañantes dependiendo de la categoria
	$("body").on({
		change: function() {
			var $input = $(this),
				clave = $input.val();

			$.ajax({
				type: "GET",
				url: "caja/?action=listaCostosCategoriaAcom",
				data: "clave=" + clave,
				success: function(sRes) {
					$('#acm_id_costo').html(sRes).removeAttr("disabled").focus();
				}
			});
		}
	}, "select[name='acm_clave']");

	// Asignamos costos del item dependiendo de la categoria
	$("body").on({
		change: function() {
			var $input = $(this),
				clave = $input.val();

			$.ajax({
				type: "GET",
				url: "caja/?action=listaCostosCategoriaItem",
				data: "clave=" + clave,
				success: function(sRes) {
					$('#item_id_costo').html(sRes).removeAttr("disabled").focus();
				}
			});
		}
	}, "select[name^='item_clave']");

	// Asignamos costos del registro dependiendo de la categoria del registro
	$("#cat_registro").change(function() {
		var clave = $(this).val();

		$.ajax({
			type: "POST",
			url: "home/registros/?action=listaCostosCategoria",
			data: "&cve=" + clave,
			success: function(sRes) {
				$("#id_costo").html(sRes).removeAttr("disabled").focus();
			}
		});
	});

	/**
	 * UPDATE HOSPEDAJE
	 */
	$('#guardarRegistro').validate({
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
					console.log(sRes);
					$('#guardarRegistro').modal('hide');
					$('#id_registro').val(sRes);
					$("#buscarRegistros").submit();
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