<div class="page-header" style="margin-bottom: 5px;">
	<h5>
		{{ tituloSeccion }} <a href="#setUsuario" class="btn btn-mini" role="button" data-toggle="modal">Agregar</a>
	</h5>
</div>

<div id="eventos">

	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>ID</th>
				<th>Nombre</th>
				<th>Usuario</th>
				<th>Status</th>
				<th>Config.</th>
			</tr>
		</thead>
		<tbody id="lista-usuarios">
			{% for usuario in usuarios %}
			<tr>
				<td>
					{{ usuario.usr_idUsuario }}
				</td>
				<td>
					{{ usuario.usr_nombre }} {{ usuario.usr_app }} {{ usuario.usr_apm }}
				</td>
				<td>
					{{ usuario.usr_usuario }}
				</td>
				<th>
					{{ usuario.usr_status == 1 ? 'Habilitado' : 'Inhabilitado' }}
				</th>
				<th style="width: 60px;">
					<a class="btn btn-mini" data-load="seccion" href="{{ seccion }}/?action=getUsuario&amp;id={{ usuario.usr_idUsuario }}"><i class="icon-user"></i> Editar</a>
				</th>
			</tr>
			{% endfor %}
		</tbody>
	</table>

	<form action="{{ seccion }}/?action=setUsuario" id="setUsuario" method="post" name="setUsuario" class="modal hide fade form-horizontal" tabindex="-1" role="dialog" aria-labelledby="setUsuarioLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="setUsuarioLabel">Agregar usuario</h3>
		</div>
		<div class="modal-body">
			<div class="control-group">
				<label for="usr_nombre" class="control-label">Nombre(s):</label>
				<div class="controls">
					<input class="{required:true}" type="text" id="usr_nombre" name="usr_nombre" />
				</div>
			</div>
			<div class="control-group">
				<label for="usr_app" class="control-label">A Paterno:</label>
				<div class="controls">
					<input class="{required:true}" type="text" id="usr_app" name="usr_app" />
				</div>
			</div>
			<div class="control-group">
				<label for="usr_apm" class="control-label">A. Materno:</label>
				<div class="controls">
					<input type="text" id="usr_apm" name="usr_apm" />
				</div>
			</div>
			<div class="control-group">
				<label for="usr_genero" class="control-label">Sexo:</label>
				<div class="controls">
					<select class="{required:true}" id="usr_genero" name="usr_genero">
						<option value="">Seleccione:</option>
						<option value="F">Mujer</option>
						<option value="M">Hombre</option>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label for="usr_usuario" class="control-label">Usuario:</label>
				<div class="controls">
					<input class="{required:true} span3" type="email" id="usr_usuario" name="usr_usuario" placeholder="Correo electrónico" />
				</div>
			</div>
			<div class="control-group">
				<label for="usr_password" class="control-label">Contraseña:</label>
				<div class="controls">
					<input class="{required:true}" type="text" id="usr_password" name="usr_password" />
				</div>
			</div>
			<div class="control-group">
				<label for="usr_status" class="control-label">Status:</label>
				<div class="controls">
					<select class="{required:true}" id="usr_status" name="usr_status">
						<option value="">Seleccione:</option>
						<option value="1">Habilitado</option>
						<option value="2">Inhabilitado</option>
					</select>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<div class="form-error">&nbsp;</div>
			<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
			<button class="btn btn-success" type="submit">
				<i class="icon-ok-sign icon-white"></i> Aceptar
			</button>
		</div>
	</form>

</div>