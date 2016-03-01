{#
<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/eventos/evento/
 * @version $Id: statusRegistros.php 1.0 2012-06-05 23:55 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}
<div class="modal-general" title="Catalogo status de los registros">

	<div class="status-registros" id="status-registros">

		<!-- formulario nuevo status para registro -->
		<div id="nuevo-status-registro">
			<div class="page-header">
				<h4>Agrege los status de una forma adecuada ya que son utilizados en los registros</h4>
			</div>
			<ol>
				<li>Para cambiar un status eliminelo y vuelva a crearlo.</li>
				<li>Una vez iniciado el registro no se recomienda eliminar los status.</li>
			</ol>
			<br />
			<hr />
			<form action="{{ seccion }}/statusRegistros?action=agregarStatusReg" data-load="ajax" method="post" name="altaStatusReg">
				<input id="esr_idStatus" name="esr_idStatus" type="hidden" value="{{ idNuevo }}" />
				<fieldset class="horizontal">
					<div class="control-group">
						<label class="control-label" for="esr_clave">Clave:</label>
						<div class="controls">
							<input class="span1" id="esr_clave" name="esr_clave" type="text" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="esr_nombre">Nombre:</label>
						<div class="controls">
							<input class="span2" id="esr_nombre" name="esr_nombre" type="text" />
						</div>
					</div>
				</fieldset>
				<div class="acciones-form">
					<div class="form-error">&nbsp;</div>
					<div>
						<button class="btn btn-success" type="submit">Agregar</button>
						<button class="btn" type="button" id="cancelar-nuevo-statusReg">Cancelar</button>
					</div>
				</div>				
			</form>
		</div>

		<div class="alert alert-error">&nbsp;</div>

		<!-- Status de registro -->
		<table class="table table-striped table-bordered table-condensed">
			<thead>
				<tr>
					<th>Clave</th>
					<th>Nombre</th>
					<th style="width: 150px;"><a class="btn btn-mini" href="#nuevoStatusReg"><i class="icon-plus-sign"></i> Agregar</a></th>
				</tr>
			</thead>
			<tbody>
				{% for statusReg in statusRegs %}
				<tr id="statusReg-{{ statusReg.esr_idStatus }}">
					<td>{{ statusReg.esr_clave }}</td>
					<td>{{ statusReg.esr_nombre }}</td>
					<th>
						<a class="btn btn-mini editarStatusReg" href="{{ seccion }}/statusRegistros?action=editar&amp;id={{ statusReg.esr_idStatus }}"><i class="icon-edit"></i></a>
						{% if statusReg.esr_idStatus not in [1, 2, 3, 4, 5] %}
						<a class="btn btn-mini btn-danger" data-load="accion" href="{{ seccion }}/statusRegistros?action=eliminar&amp;id={{ statusReg.esr_idStatus }}"><i class="icon-trash icon-white"></i></a>
						{% endif %}
					</th>
				</tr>
				{% else %}
				<tr id="no-statusReg">
					<th colspan="4">No se han agregado Status de Registro</th>
				</tr>
				{% endfor %}
			</tbody>
		</table>

	</div>

</div>