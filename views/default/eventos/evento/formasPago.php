{#
<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/eventos/evento/
 * @version $Id: formasPago.php 1.0 2012-06-08 21:52 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}
<div class="modal-general" title="Catalogo formas de pago">

	<div class="formas-pago">

		<table class="table table-striped table-bordered table-condensed">
			<thead>
				<tr>
					<th>Clave</th>
					<th style="width: 300px;">Nombre(s)</th>
					<th style="width: 50px;">Eliminar</th>
				</tr>
			</thead>
			<tbody>
				{% for formaPago in formasPago %}
				<tr id="formaPago-{{ formaPago.efp_clave }}">
					<td>{{ formaPago.efp_clave }}</td>
					<td>
						<div class="nuevo-nombre-formaPago">
							<a class="btn btn-mini btn-warning" data-load="accion" href="{{ seccion }}/formasPago?action=formAgregarNombre&amp;cve={{ formaPago.efp_clave }}&amp;id={{ formaPago.idNuevoNombre }}" title="Agregar nombre">
								<i class="icon-plus-sign icon-white"></i>
							</a>
						</div>
						{% for nombre in formaPago.nombres %}
						<div class="nombre-formaPago" id="nombre-{{ formaPago.efp_clave }}-{{ nombre.fpn_idNombre }}">
							<span>{{ nombre.fpn_nombre }}</span>
							<span class="eliminar-nombre-formaPago">
								<a class="btn btn-mini btn-danger" data-load="accion" href="{{ seccion }}/formasPago?action=eliminarNombre&amp;cve={{ formaPago.efp_clave }}&amp;id={{ nombre.fpn_idNombre }}" title="Eliminar este nombre">
									<i class="icon-trash icon-white"></i>
								</a>
							</span>
						</div>
						{% endfor %}
					</td>
					<th>
						<a class="btn btn-mini btn-danger" data-load="accion" href="{{ seccion }}/formasPago?action=eliminar&amp;cve={{ formaPago.efp_clave }}">
							<i class="icon-trash icon-white"></i>
						</a>
					</th>
				</tr>
				{% endfor %}
			</tbody>
		</table>

		<div style="float: right;">
			<form action="{{ seccion }}/formasPago?action=agregar" data-load="ajax" class="well form-horizontal" method="post" name="altaFormaPago">
				<div class="control-group">
					<label class="control-label" for="efp_clave">Nueva forma de pago:</label>
					<div class="controls">
						<input class="span1" id="efp_clave" name="efp_clave" placeholder="Clave" type="text" />
						<button class="btn">
							<i class="icon-plus-sign"></i>
						</button>
					</div>
					<label class="inline-label-error" for="efp_clave">Este campo es obligatorio</label>
				</div>
				<div class="form-error"></div>
			</form>
		</div>

	</div>

</div>