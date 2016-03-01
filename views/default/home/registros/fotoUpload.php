<div class="modal-general" title="Subir imagen">
	<form id="fileupload" action="{{ seccion }}/?action=subirFoto" method="POST" enctype="multipart/form-data">

		<div style="display: block;">
			<img id="photo-uploaded" src="" />
		</div>
		<!--<div style="width: 160px; height: 180px; overflow: hidden; display: inline-block; vertical-align: top;">
			<img id="photo-preview" src="" />
		</div>-->

		<!-- The global progress information -->
		<div class="span5 fileupload-progress fade">
			<!-- The global progress bar -->
			<div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
				<div class="bar" style="width:0%;"></div>
			</div>
			<!-- The extended global progress information -->
			<div class="progress-extended">&nbsp;</div>
		</div>

		<!-- The loading indicator is shown during file processing -->
		<div class="fileupload-loading"></div>

		<div class="form-actions">
			<!-- The fileinput-button span is used to style the file input field as button -->
			<button class="btn btn-success" id="photo-save" type="button" style="display: none;">
				<i class="icon-ok-sign icon-white"></i> Asignar imagen
			</button>

			<button class="btn btn-danger" id="photo-cancel" type="button" style="display: none;">
				<i class="icon-remove-circle icon-white"></i> Cambiar imagen
			</button>

			<span class="btn btn-success fileinput-button">
				<i class="icon-plus icon-white"></i>
				<span>Agregar imagen</span>
				<input type="file" name="photo" />
			</span>
		</div>
	</form>
</div>

<script>
$(function () {
	$('#fileupload').fileupload({
		url: 'home/registros/?action=subirFoto',
		add: function(e, data) {
			data.submit();
		},
		done: function (e, data) {
			if (data.result.status) {
				$('.fileinput-button').hide();
				$('#photo-cancel, #photo-save').show();

				// Tipo de la imagen
				$('#type-photo').val(data.result.type);
				// La imagen
				$('#photo').val(data.result.img);
				// Tamaño real de la imagen
				$('#w_o').val(data.result.pw);
				$('#h_o').val(data.result.ph);

				// La seleccion de area de la imagen
				$('#photo-uploaded').attr('src', data.result.photo)
				.Jcrop({
					//minSize: [160, 180], //[113, 170]
					//maxSize: [160, 180],
					addClass: 'jcrop-dark',
					setSelect: [0, 0, 160, 180],
					onChange: foto,
					onSelect: foto
				});
				/*$('#photo-preview').attr({
					src: data.result.photo,
					width: data.result.pw + 'px',
					height: data.result.ph + 'px'
				});*/
			} else {
				alert(data.result.mensaje);
			}
		},
		progressall: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
			$('.progress .bar').css(
				'width',
				progress + '%'
			).html(progress + '%');
		}
	});

	// Cambiar a otra foto
	$('#photo-cancel').click(function() {
		$('.modal-general').dialog('close');
		$('#photo-uploaded').attr('src', '');
		$('#type-photo').val('');
		$('#photo').val('');
	});

	$('#photo-save').click(function() {
		$('.modal-general').dialog('close');
		$('#save-photo').val(1);
		$('#module-photo').children('a').hide()
		$('#module-photo').append(
			'<button class="btn btn-danger" id="delete-photo" type="button">' +
				'<i class="icon-remove icon-white"></i> Eliminar foto' +
			'</button>'
		);
	});

	// Cambiar a otra foto
	$('body').on('click', '#delete-photo', function() {
		$(this).remove();
		$('#save-photo').val(0);
		$('#photo-uploaded').attr('src', '');
		$('#type-photo').val('');
		$('#photo').val('');
		$('#module-photo').children('a').show();
	});

	foto = function(c) {
		$('#x').val(c.x);
		$('#y').val(c.y);
		$('#x2').val(c.x2);
		$('#y2').val(c.y2);
		$('#w').val(c.w);
		$('#h').val(c.h);

		/*$('#photo-preview').css({
			marginLeft: '-' + c.x + 'px',
			marginTop: '-' + c.y + 'px',
		});*/
	};

});
</script>