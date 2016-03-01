<div class="modal-general" title="Fotografia">

	<div style="display: inline-block;">
		<video id="live-cam" autoplay height="264px" width="352px"></video>
	</div>

	<canvas id="canvas-cam" height="264px" width="350px" style="display: none;"></canvas>

	<div class="take-photo">
		
	</div>

	<div class="form-actions">
		<button class="btn" id="tomar-foto">Tomar fotografia</button>
		<button class="btn btn-success" id="asignar-foto" style="display: none;">Asignar fotografia</button>
		<button class="btn btn-danger" id="cancelar-foto">Cancelar</button>
	</div>

	<script type="text/javascript">
		var video = document.getElementById("live-cam");
		var canvas = document.getElementById("canvas-cam");

		var ctx = canvas.getContext('2d');
		var stream = true;

		function snapshot() {
			if (stream) {
				ctx.drawImage(video, 0, 0, 350, 264);
			}
		}

		function finaly() {
			// "image/webp" works in Chrome 18. In other browsers, this will fall back to image/png.
			var image = document.getElementById("foto-cam");
			image.src = "";
			image.src = canvas.toDataURL('image/jpg');

			$(image).Jcrop({
					minSize: [160, 180], //[113, 170]
					//maxSize: [160, 180],
					addClass: 'jcrop-dark',
					setSelect: [0, 0, 160, 180],
					onChange: foto,
					onSelect: foto
			});
		}

		function foto(c) {
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

		// Inicializa camara web
		navigator.webkitGetUserMedia({video: true}, function(stream) {
			video.src = window.webkitURL.createObjectURL(stream);
		}, function(err) {
			console.log("Video deshabilitado!");
		});

		function hasGetUserMedia() {
			// Note: Opera builds are unprefixed.
			return !!(navigator.getUserMedia || navigator.webkitGetUserMedia ||
			navigator.mozGetUserMedia || navigator.msGetUserMedia);
		}

		if (hasGetUserMedia()) {
			// Good to go!
		} else {
			alert('La funcion: getUserMedia(), no es soportado en tu actual navegador!');
		}

		$(function() {
			$('#tomar-foto').click(function() {
				img = $('<img />', {
					id: 'foto-cam',
					height: '264px',
					width: '350px',
					style: 'vertical-align: top;'
				});

				$('.take-photo').html(img);

				snapshot();

				setTimeout(function () {
					finaly();
					$('#asignar-foto').show();
				}, 150);

			});

			$('#asignar-foto').click(function(event) {

				event.preventDefault();

				photo = $('#foto-cam').attr('src');

				if (photo != '') {
					$('#save-photo').val(1);

					var n = photo.indexOf(",");
					photo = photo.substr(n + 1);

					$('#type-photo').val('image/jpg');
					$('#photo').val(photo);

					//$('.modal-general').dialog('close');
					//$('#save-photo').val(1);
					$('#module-photo').children('a').hide()
					$('#module-photo').append(
						'<button class="btn btn-danger" id="delete-photo" type="button">' +
							'<i class="icon-remove icon-white"></i> Eliminar foto' +
						'</button>'
					);

					$('.modal-general').dialog('close');

				} else {
					$('#asignar-foto').hide();
				}

			});

			// Cambiar a otra foto
			$('body').on('click', '#delete-photo', function() {
				$(this).remove();
				$('#save-photo').val(0);
				$('#type-photo').val('');
				$('#photo').val('');
				$('#module-photo').children('a').show();
			});

			// Cambiar a otra foto
			$('body').on('click', '#cancelar-foto', function() {
				$('.modal-general').dialog('close');
			});			
		});
	</script>

</div>