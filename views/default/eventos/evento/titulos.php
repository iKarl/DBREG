{#
<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/eventos/evento/
 * @version $Id: titulos.php 1.0 2012-05-23 23:15 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}
<div class="modal-general" title="Catalogo de titulos">

	<div class="titulos" id="lista-titulos">

		<!-- formulario nuevo titulo -->
		<div id="nuevo-titulo">
			<div class="page-header">
				<h4>Agrege los titulos de una forma adecuada ya que son utilizados en los registros</h4>
			</div>
			<ol>
				<li>Para cambiar un titulo eliminelo y vuelva a crearlo.</li>
				<li>Agrege el titulo en cada uno de los idiomas mostrados.</li>
				<li>Una vez iniciado el registro no se recomienda cambiar o eliminar los titulos.</li>
			</ol>
			<br />
			<hr />
			<form action="{{ seccion }}/titulos?action=agregarTitulo" data-load="ajax" method="post" name="altaTitulo">
				<input id="ect_idTitulo" name="ect_idTitulo" type="hidden" value="{{ idNuevo }}" />
				<fieldset class="horizontal">
					<div class="control-group">
						<label class="control-label" for="ect_clave">Clave:</label>
						<div class="controls">
							<input class="span1" id="ect_clave" name="ect_clave" type="text" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="ect_nombre">Nombre:</label>
						<div class="controls">
							<input class="span2" id="ect_nombre" name="ect_nombre" type="text" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Idioma</label>
						<div>
							<select id="ect_idioma" name="ect_idioma">
								<option value="">Seleccione:</option>
								{% for idioma in idiomas %}
								<option value="{{ idioma.eis_idioma }}">{{ idioma.eis_nombre }}</option>
								{% else %}
								<option value="">No existen idiomas</option>
								{% endfor %}
							</select>
						</div>
					</div>
					<div class="acciones-form">
						<div class="form-error">&nbsp;</div>
						<div>
							<button class="btn btn-success" type="submit">Agregar</button>
							<button class="btn" type="button" id="cancelar-nuevo-titulo">Cancelar</button>
						</div>
					</div>
				</fieldset>
			</form>
		</div>

		<!-- titulo -->
		<table class="table table-striped table-bordered table-condensed">
			<thead>
				<tr>
					<th>Clave</th>
					<th>Nombre</th>
					<th>Idioma</th>
					<th style="width: 100px;"><a class="btn btn-mini" href="#nuevoTitulo"><i class="icon-plus-sign"></i> Agregar</a></th>
				</tr>
			</thead>
			<tbody>
				{% for titulo in titulos %}
				<tr id="titulo-{{ titulo.ect_idTitulo }}">
					<td>{{ titulo.ect_clave }}</td>
					<td>{{ titulo.ect_nombre }}</td>
					<th>{{ titulo.ect_idioma }}</th>
					<th><a class="btn btn-mini btn-danger" data-load="accion" href="{{ seccion }}/titulos?action=eliminar&amp;id={{ titulo.ect_idTitulo }}"><i class="icon-trash icon-white"></i></a></th>
				</tr>
				{% else %}
				<tr id="no-titulos">
					<th colspan="4">No se han agregado titulos</th>
				</tr>
				{% endfor %}
			</tbody>
		</table>

	</div>

</div>