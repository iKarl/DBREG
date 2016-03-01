$(function() {
	/**
	 * AGREGAR ADICIONAL
	 */
	$('#setUsuario').validate({
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
						_usuario = '<tr>' +
								'<td>' + sRes.id_usuario + '</td>' +
								'<td>' + sRes.usuario.usr_nombre + ' ' + sRes.usuario.usr_app + ' ' + sRes.usuario.usr_apm + '</td>' +
								'<td>' + sRes.usuario.usr_usuario + '</td>' +
								'<th>';

								if (sRes.usuario.usr_status == 1) {
									_usuario += 'Habilitado';
								} else {
									_usuario += 'Inhabilitado';
								}

								_usuario += '</th>' +
								'<th style="width: 60px;">' +
									'<a class="btn btn-mini" data-load="seccion" data-method="post" href="usuarios/?action=getUsuario&amp;id=' + sRes.id_usuario + '"><i class="icon-user"></i> Editar</a>' +
								'</th>' +
							'</tr>';

						$('#lista-usuarios').append(_usuario);

						$('#setUsuario').modal('hide');
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

	$('#setUsuario').on('hidden', function () {
		$('#setUsuario').find('input, select').val('');
	});
});