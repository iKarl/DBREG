{#
<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/home/registros/
 * @version $Id: index.php 1.0 2012-05-01 00:20 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}
<div class="page-header" style="margin-bottom: 5px;">
	<h5>{{ evento.evt_nombre }} <small>Registros</small></h5>
</div>

<div id="registros">

	<ul class="nav-seccion">
		<li>
			<a class="btn btn-primary" data-load="accion" href="{{ seccion }}/?action=nuevoRegistro" title="Agregar un registro"><i class="icon-plus-sign icon-white"></i> Agregar</a>
			<button class="btn btn-warning" id="importarInet" type="button">Importar</button>
		</li>
		<li>
			<!--<span class="label">Registro</span>
			<span class="label label-warning">Pendiente</span>
			<span class="label label-success">Pagado</span>
			<span class="label label-info">Cortesia</span>
			<span class="label label-important">Cancelado</span>-->
			<form action="{{ seccion }}/?action=impresionConstancia&amp;id=1" id="pre_cons" method="post" target="_blank">
				<input class="{required:true,number:true} span2" id="id_registro_re_re" name="id_registro_re" type="text" placeholder="CONSTANCIA" required />
			</form>
			<form action="{{ seccion }}/?action=impresionGafete&amp;id=1" id="pre_gafete" method="post" target="_blank">
				<input class="{required:true,number:true} span2" id="id_registro_re_ga" name="id_registro_ga" type="text" placeholder="GAFETE" required />
			</form>
		</li>
		<li>
			Total de registros: <strong id="total-registros">{{ totalRegistros }}</strong>
		</li>
		<li>
			Confirmados: <strong id="total-registros-pagados">{{ totalRegistrosPag }}</strong>
		</li>
		<li>
			Llegados: <strong id="total-gafetes-impresos">{{ totalRegistrosImp }}</strong>
		</li>
		<li>
			<form action="{{ seccion }}/?action=buscarAcompanante" data-load="ajax" class="form-inline" data-validate="false" id="buscarAcompanante" method="post" name="buscarAcompanante">
				<div class="control-group">
					<label class="control-label inline" for="acom_b">Buscar:</label>
					<div class="controls">
						<input class="span3" id="acom_b" name="acom_b" placeholder="Acompañante: Nombre o Apellido" type="search" />
					</div>
				</div>
			</form>
		</li>
	</ul>

	<form action="" data-event="true" id="formImpresion" method="post" name="formImpresion" target="_blank" style="display: none;">
		<input name="imprimir" type="submit" value="imprimir" style="display: none;" />
	</form>

	<form action="{{ seccion }}/?action=buscarRegistros" data-load="ajax" data-validate="false" id="buscarRegistros" method="post" name="buscarRegistros">
		<input name="nombre_tabla" type="hidden" value="{{ evento.evt_nombreTablaAsistentes }}" />
		<table class="table table-striped table-bordered table-condensed">
			<thead>
				<tr>
					<th style="width: 60px;">Status</th>
					<!--<th style="width: 10px;">A</th>-->
					<th style="width: 70px;">ID</th>
					<th>Apellido paterno</th>
					<th>Apellido materno</th>
					<th>Nombre</th>
					<th>Correo electrónico</th>
					<th>Empresa</th>
				</tr>
				<tr>
					<th>&nbsp;</th>
					<!--<td>&nbsp;</td>-->
					<th><input autocomplete="off" class="input-mini" id="id_registro_b" name="id_registro_b" type="search" /></th>
					<th><input autocomplete="off" class="input-medium" id="app_b" name="app_b" placeholder="Buscar" type="search" /></th>
					<th><input autocomplete="off" class="input-medium" id="apm_b" name="apm_b" placeholder="Buscar" type="search" /></th>
					<th><input autocomplete="off" class="input-medium" id="nombre_b" name="nombre_b" placeholder="Buscar" type="search" /></th>
					<th><input autocomplete="off" class="input-large" id="email_b" name="email_b" placeholder="Buscar" type="search" /></th>
					<th><input autocomplete="off" class="span3" id="emp_o_ins_b" name="emp_o_ins_b" placeholder="Buscar" type="search" /></th>
				</tr>
			</thead>
			<tbody id="resultado-registros">
				{% for registro in registros %}

					{% set style = "" %}

					{% if registro.status == "PAG" %}
						{% set style = " btn-success" %}
					{% elseif registro.status == "COR" or registro.status == "CCC" or registro.status == "EXE" %}
						{% set style = " btn-info" %}
					{% elseif registro.status == "PEN" %}
						{% set style = " btn-warning" %}
					{% elseif registro.status == "CAN" %}
						{% set style = " btn-danger" %}
					{% endif %}

				<tr>
					<td>
						<div class="btn-group">
							<a class="btn btn-mini{{ style }}" data-load="accion" href="{{ seccion }}/?action=editarRegistro&amp;id={{ registro.id_registro }}">
								<i class="icon-eye-open icon-white"></i>
							</a>
							{% if registro.status in ["PAG","COR","CCC","EXE"]  %}
							<a href="#" data-toggle="dropdown" class="btn btn-mini{{ style }} dropdown-toggle"><i class="icon-print icon-white"></i></a>
							<ul class="dropdown-menu idReg-{{ registro.id_registro }}">
								<li>
									<a data-load="accion" href="{{ seccion }}/?action=impresionGafete&amp;id={{ registro.id_registro }}">
										<i class="icon-barcode"></i> Imprimir Gafete
										{{ registro.impresion_gafete ? '<i class="icon-ok-circle"></i>' : '' }}
									</a>
								</li>
								<li>
									<a data-load="accion" href="{{ seccion }}/?action=impresionConstancia&amp;id={{ registro.id_registro }}">
										<i class="icon-qrcode"></i> Imprimir Constancia
										{{ registro.impresion_constancia ? '<i class="icon-ok-circle"></i>' : '' }}
									</a>
								</li>
							</ul>
							{% endif %}
						</div>
					</td>
					<!--<td>
						<input type="checkbox" name="impresion_gafete" value='{{ registro.id_registro }}' {{ registro.impresion_gafete ? ' checked' : '' }} />
					</td>-->
					<td>{{ registro.id_registro }}</td>
					<td>{{ registro.app }}</td>
					<td>{{ registro.apm }}</td>
					<td>{{ registro.nombre }}</td>
					<td style="text-align: center; font-size: 1.4em;"><strong>{{ registro.enc_nombre }}</strong></td>
					<td>{{ registro.emp_o_ins }}</td>
				</tr>
				{% else %}
				<tr>
					<th colspan="8">Aun no hay registros</th>
				</tr>
				{% endfor %}
			</tbody>
		</table>
		<input class="btn" type="submit" value="Buscar" style="display: none;" />
	</form>
	<script type="text/javascript">
	$(function() {
		$('#id_registro_re_re').focus();
		/**
		 * AGREGAR ACOMPANANTE
		 */
		$('#pre_cons').validate({
			submitHandler: function(form) {
				var _form = $(form),
					_action = _form.attr('action'),
					queryString = $(form).serialize();

				$.ajax({
					type: 'GET',
					url: '{{ seccion }}/?action=impresionConstancia&id=' + $('#id_registro_re_re').val(),
					data: queryString,
					dataType: 'json',
					beforeSend: function() {
						_form.find('button').attr('disabled', 'disabled');
						_form.find('.form-error').html('&nbsp;');
					},
					success: function(sRes) {
						window[sRes.nomFuncion](sRes);
					}
				});
				/*$('#id_registro_re_re').focus(function() {
					$(this).select();
				});
				$(form).submit();*/
				return false;
			},
			errorPlacement: function(error, element) {
				error.appendTo(element.parent().append());
			}
		});

		$('#pre_gafete').validate({
			submitHandler: function(form) {
				var _form = $(form),
					_action = _form.attr('action'),
					queryString = $(form).serialize();

				$.ajax({
					type: 'GET',
					url: '{{ seccion }}/?action=impresionGafete&id=' + $('#id_registro_re_ga').val(),
					data: queryString,
					dataType: 'json',
					beforeSend: function() {
						_form.find('button').attr('disabled', 'disabled');
						_form.find('.form-error').html('&nbsp;');
					},
					success: function(sRes) {
						window[sRes.nomFuncion](sRes);
					}
				});
				/*$('#id_registro_re_re').focus(function() {
					$(this).select();
				});
				$(form).submit();*/
				return false;
			},
			errorPlacement: function(error, element) {
				error.appendTo(element.parent().append());
			}
		});
	});
	</script>
</div>