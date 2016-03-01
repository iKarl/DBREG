<div id="usuario">
	<div class="page-header">
		<h4>Administrar usuario
			<a class="btn btn-mini" data-load="seccion" data-opts='{"tools":{"css":false,"js":true,"file":"usuarios"}}' href="usuarios/">
				Regresar
			</a>
		</h4>
	</div>

	<table class="table table-bordered table-condensed">
		<tr>
			<th>
				<strong>Usuario</strong>
			</th>
			<th>
				<strong>Permisos</strong>
			</th>
		</tr>
		<tr>
			<td style="width: 49%">
				<form action="{{ seccion }}/?action=updateUsuario" id="updateUsuario" method="post" name="updateUsuario" class="form-horizontal">
					<input type="hidden" name="usr_idUsuario" value="{{ usuario.usr_idUsuario }}" />

					<div class="control-group">
						<label for="usr_nombre" class="control-label">Nombre(s):</label>
						<div class="controls">
							<input class="{required:true}" type="text" id="usr_nombre" name="usr_nombre" value="{{ usuario.usr_nombre }}" />
						</div>
					</div>
					<div class="control-group">
						<label for="usr_app" class="control-label">A Paterno:</label>
						<div class="controls">
							<input class="{required:true}" type="text" id="usr_app" name="usr_app" value="{{ usuario.usr_app }}" />
						</div>
					</div>
					<div class="control-group">
						<label for="usr_apm" class="control-label">A. Materno:</label>
						<div class="controls">
							<input type="text" id="usr_apm" name="usr_apm" value="{{ usuario.usr_apm }}" />
						</div>
					</div>
					<div class="control-group">
						<label for="usr_genero" class="control-label">Sexo:</label>
						<div class="controls">
							<select class="{required:true}" id="usr_genero" name="usr_genero">
								<option value="">Seleccione:</option>
								<option value="F"{{ usuario.usr_genero == 'F' ? ' selected="selected"' : '' }}>Mujer</option>
								<option value="M"{{ usuario.usr_genero == 'M' ? ' selected="selected"' : '' }}>Hombre</option>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label for="usr_usuario" class="control-label">Usuario:</label>
						<div class="controls">
							<input class="{required:true} span3" type="email" id="usr_usuario" name="usr_usuario" placeholder="Correo electrónico" value="{{ usuario.usr_usuario }}" />
						</div>
					</div>
					<div class="control-group">
						<label for="usr_password" class="control-label">Contraseña:</label>
						<div class="controls">
							<input type="text" id="usr_password" name="usr_password" />
						</div>
					</div>
					<div class="control-group">
						<label for="usr_status" class="control-label">Status:</label>
						<div class="controls">
							<select class="{required:true}" id="usr_status" name="usr_status">
								<option value="">Seleccione:</option>
								<option value="1"{{ usuario.usr_status == 1 ? ' selected="selected"' : '' }}>Habilitado</option>
								<option value="2"{{ usuario.usr_status == 2 ? ' selected="selected"' : '' }}>Inhabilitado</option>
							</select>
						</div>
					</div>

					<div class="form-error">&nbsp;</div>
					<button class="btn btn-success" type="submit">
						<i class="icon-ok-sign icon-white"></i> Guardar
					</button>
				</form>
			</td>
			<td style="width: 49%">
				<form action="{{ seccion }}/?action=setPermisos" id="setPermisos" method="post" name="setPermisos">
					<input type="hidden" name="usr_idUsuario" value="{{ usuario.usr_idUsuario }}" />
					<div class="control-group">
						<label for="lista_permisos" class="control-label">Lista de secciones para el usuario:</label>
						<div class="controls">
							<select class="{required:true} input-block-level" id="lista_permisos" name="lista_permisos[]" multiple="multiple" style="min-height: 325px;">
								{% for smc_seccion in secciones %}
								<option value="{{ smc_seccion.sec_idSeccion }}"{{ smc_seccion.sec_idSeccion in usuario_secciones ? ' selected="selected"' : '' }}>{{ smc_seccion.sec_nombre }} | {{ smc_seccion.sec_descripcion }}</option>
								{% endfor %}
							</select>
						</div>
					</div>

					<div class="form-error">&nbsp;</div>
					<button class="btn btn-success" type="submit">
						<i class="icon-ok-sign icon-white"></i> Guardar
					</button>
				</form>
			</td>
		</tr>
	</table>
</div>
<script>
$(function() {
	/**
	 * AGREGAR ADICIONAL
	 */
	$('#updateUsuario').validate({
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
					_form.find('.form-error').html('&nbsp;').show();
				},
				success: function(sRes) {
					_form.find('.form-error').html(sRes.mensaje);
					if (sRes.status) {
						setTimeout(function() {
							_form.find('.form-error').fadeOut('slow');
						}, 3000);
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
	$('#setPermisos').validate({
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
					_form.find('.form-error').html('&nbsp;').show();
				},
				success: function(sRes) {
					_form.find('.form-error').html(sRes.mensaje);
					if (sRes.status) {
						setTimeout(function() {
							_form.find('.form-error').fadeOut('slow');
						}, 3000);
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