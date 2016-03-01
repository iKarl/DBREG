/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/{layout}/js/
 * @version $Id: general.js 1.0 2011-04-05 23:08 Daniel $;
 * @author: Daniel
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

/**
 * Aviso sección
 */
function avisoSeccion() {
	$dialog = $("<div />", {
		id: "modal-aviso",
		title: "Atención!",
		html: '<span class="alertas">La sección a la que intentas ingresar no esta disponible o esta en construcción, disculpa las molestias.</span>'
	});

	$dialog.dialog({
		modal: true,
		close: function() {
			$(this).remove();
		},
		buttons: {
			"Aceptar": function() {
				$(this).dialog("close");
			}
		},
		resizable: false
	});
};

/**
 * Aviso sesión
 */
function avisoSesion() {
	$dialog = $("<div />", {
		id: "modal-aviso",
		title: "Atención!!!",
		html: '<span class="alertas">Estubo mucho tiempo sin actividad y por seguridad se ha cerrado su sesion, ingrese de nuevo por favor.</span>'
	});

	$dialog.dialog({
		modal: true,
		close: function() {
			$(this).remove();
			window.location.href = "login/?action=logout&sess=activity";
		},
		buttons: {
			"Aceptar": function() {
				$(this).dialog("close");
			}
		}
	});
};

/**
 * Aviso niveles
 */
function avisoNiveles() {
	$dialog = $("<div />", {
		id: "modal-aviso",
		title: "Atención!!!",
		html: '<span class="alertas">Tu nivel actual de permisos no te permite esta acción.</span>'
	});

	$dialog.dialog({
		modal: true,
		close: function() {
			$(this).remove();
		},
		buttons: {
			"Aceptar": function() {
				$(this).dialog("close");
			}
		}
	});
};

/**
 * Aviso error
 */
function avisoError() {
	$dialog = $("<div />", {
		id: "modal-aviso",
		title: "Atención!!!",
		html: '<span class="alertas">Se detecto un error, ingresa a la sección nuevamente.</span>'
	});

	$dialog.dialog({
		modal: true,
		close: function() {
			$(this).remove();
		},
		buttons: {
			"Aceptar": function() {
				$(this).dialog("close");
			}
		}
	});
};

$(function() {

	/**
	 * Función que muestra errores en el formulario
	 * 
	 * El valor recibido tiene que ser en formato JSON
	 * formato: {"idCampo1":"mensaje","idCampo2":"mensaje", ..}
	 * 
	 * @param Object Lista de objectos
	 */
	validarFormulario = function (elements) {
		// Ocultamos los elementos en linea
		$("label.inline-label-error").hide();
		// Removemos todos los errores
		$("label.appear-label-error").remove();

		if (elements.campos != undefined && elements.campos != "") {

			var fields = elements.campos;

			//
			var total = 0;
			for ( campos in fields ) total++;

			if (total > 0)
			{

				// Recorremos los objectos
				var i = 0;
				$.each(fields, function(keyID, msgError) {

					elemento = $("input[name='" + keyID + "']:last, select[name='" + keyID + "']:last, textarea[name='" + keyID + "']:last");
					etiqueta = $("label[for='" + keyID + "']:hidden");

					if (etiqueta.length == 1) {

						etiqueta.show().html(msgError);

					} else {

						elemento.after('<label class="inline-label-error appear-label-error" for="' + keyID + '">' + msgError + '</label>');

						$("label[for='" + keyID + "']").css("display", "block");

					}

					if (i == 0) {
						$("input[name='" + keyID + "']:first, select[name='" + keyID + "']:first, textarea[name='" + keyID + "']:first").focus();
					}

					i++;

				});

			}

			return false;

		}

		return true;
	}; //validarFormulario

	/*
	 * Carga de una sección
	 */
	$("body").on({
		click: function(event) {

			var $target = $(this),				// Elemento DOM del evento
				$targetRes = $("#cuerpo"),		// Elemento DOM para la respuesta
				$uri = $target.attr("href"),	// URL
				$hash = false,					// Metodo hash
				$method = "GET",				// Metodo de envío
				$dataQry = "",					// Query string solo para metodo de envío por POST
	 			$tools = {						// Cargar css y/o js al vuelo
	 				css: false, 				// Indica carga de css
	 				js: false,					// Indica carga de javascript
	 				fileCSS: "",				// Nombre del archivo css
	 				fileJS: ""					// Nombre del archivo javascript
	 			};

			// Metodo de envío
			if ($target.data("method")) {
				$method = $target.data("method");
				// Si el metodo de envío es POST
				if ($method == "post") {
					qryStr = $uri.split("?");
					$dataQry = qryStr[1];
				}
			}

	 		// Si ocupa el metodo de hash
	 		if ($target.data("hash")) {

	 			$hash = $target.data("hash");

				var $seccion = $uri.split("#");

				$uri = "../" + $seccion[1];

	 		} else {
	 			event.preventDefault();
	 		}

	 		// Si se definen opciones
	 		if ($target.data("opts")) {

	 			options = $target.data("opts");

	 			// Si se define un elemento de respuesta
	 			if (options.target != undefined) {
	 				$targetRes = $("#" + options.target);
	 			}

	 			// Si se definen herramientas
	 			if (options.tools != undefined) {

	 				// Si se define css
	 				if (options.tools.css != undefined) {
	 					$tools.css = options.tools.css;	
	 				}

		 			// Si se define js
		 			if (options.tools.js != undefined) {
		 				$tools.js = options.tools.js;
		 			}

		 			// Si se define el archivo
		 			if (options.tools.file != undefined) {

		 				if ($hash) {
		 					$tools.fileCSS = pathTools + "/css" + $seccion[1] + options.tools.file + ".css";
		 					$tools.fileJS = pathTools + "/js" + $seccion[1] + options.tools.file +  ".js";
		 				} else {

		 					path = $uri.split("?");

		 					if (path.length > 0) {
		 						$uri = path[0];
		 					}

		 					$tools.fileCSS = pathTools + "/css/" + $uri + options.tools.file + ".css";
		 					$tools.fileJS = pathTools + "/js/" + $uri + options.tools.file +  ".js";

		 				}

		 			} else {

		 				if ($hash) {
		 					$tools.fileCSS = pathTools + "/css" + $seccion[1] + ".css";
		 					$tools.fileJS = pathTools + "/js" + $seccion[1] + ".js";
		 				} else {

		 					path = $uri.split("?");

		 					if (path.length > 0) {
		 						path = path[0];
		 					}

		 					$tools.fileCSS = pathTools + "/css/" + path + ".css";
		 					$tools.fileJS = pathTools + "/js/" + path + ".js";

		 				}

		 			}

	 			}

	 		} // de opciones

			//alert($targetRes + " " + $method + " " + $dataQry + " " + $tools.css + " " + $tools.js + " " + $tools.fileJS);

			// Validar la acción
			if (!$target.data("accion"))
			{

				// Cargamos la sección
				var jqXHR = $.ajax({
					type: $method,
					url: $uri,
					cache: false,
					data: $dataQry,
					statusCode: {
						404: avisoSeccion,
						512: avisoSesion,
						513: avisoNiveles,
						514: avisoError
					},
					beforeSend: function() {
						$target.data("accion", "cargando");

						// Si requiere css
						if ($tools.css) {
							$("#dynamicCSS").attr("href", $tools.fileCSS);
						}

					},
					success: function(sRes) {

						$targetRes.html(sRes);

					},
					complete: function() {
						$target.removeData("accion");
					}
				}); // de ajax

	 			// Si requiere js
	 			if ($tools.js) {

					// Carga el js
					jqXHR.done(function() {

						$.ajax({
							url: $tools.fileJS,
							dataType: "script",
							cache: false
						});

					});

				} // de js

			} // de la acción

		}
	}, 'a[data-load="seccion"]'); // carga de una sección

	/**
	 * Carga de un modal
	 */
	$("body").on({
		click: function(event) {

			event.preventDefault();

			var $target = $(this),				// Elemento DOM del evento
				$targetRes = $("#cuerpo"),		// Elemento DOM para la respuesta
				$uri = $target.attr("href"),	// URL
				$method = "GET",				// Metodo de envío
				$dataQry = "",					// Query string solo para metodo de envío por POST
				$options = {					// Opciones basicas del modal
					modal: true,
					resizable: false,
					height: 'auto',
					width: 'auto'
				};

			// Metodo de envío
			if ($target.data("method")) {
				$method = $target.data("method");
				// Si el metodo de envío es POST
				if ($method == "post") {
					qryStr = $uri.split("?");
					$dataQry = qryStr[1];
				}
			}

			// Elemento DOM para la respuesta
			if ($target.data("target")) {
				$targetRes = $("#" + $target.data("target"));
			}

			// Opciones del modal
			if ($target.data("modal")) {

				options = $target.data("modal");

				if (options.modal) { // revizar
					$options.modal = options.modal;
				}

				if (options.resizable) {
					$options.resizable = options.resizable;
				}

				if (options.height) {
					$options.height = options.height;
				}

				if (options.width) {
					$options.width = options.width;
				}

			}

			// Validar la acción
			if (!$target.data("accion")) {

				$.ajax({
					type: $method,
					url: $uri,
					cache: false,
					data: $dataQry,
					statusCode: {
						404: avisoSeccion,
						512: avisoSesion,
						513: avisoNiveles,
						514: avisoError // Revisar
					},
					beforeSend: function() {
						$target.data("accion", "cargando");
					},
					success: function(sRes) {

						$targetRes.append(sRes);

						$(".modal-general").dialog({
							autoOpen: true,
							close: function() {
								$(this).remove();
							},
							height: $options.height,
							maxHeight: '600',
							maxWidth: '960',
							width: $options.width,
							modal: $options.modal,
							resizable: $options.resizable
						});

					},
					complete: function() {
						$target.removeData("accion");
					}
				});

			}

		}
	}, 'a[data-load="modal"]'); // Carga de un modal

	/**
	 * Envío de formularios
	 */
	$("body").on({
		submit: function(event) {

			var $form = $(this),				// Elemento DOM del evento
				$method = $form.attr("method"),	// Metodo de envío
				$uri = $form.attr("action"),	// URL
				$dataQry = $form.serialize(),	// Query string con datos del formulario
				$validar = true,				// Validar el formulario
				$validacion = true,				// Status de la validación
				$response = "json";				// Formato de  de respuesta 

			if (typeof $form.data("event") != "undefined") {
				if ($form.data("event")) {
					return;
				} else {
					event.preventDefault();
				}
			} else {
				event.preventDefault();
			}

			if ($form.data("validate")) {
				if (!$form.data("validate")) {
					$validar = $form.data("validate");
				}
			}

			if ($form.data("response")) {
				$response = $form.data("response");
			}

			$.ajax({
				type: $method,
				url: $uri,
				cache: false,
				data: $dataQry,
				statusCode: {
					404: avisoSeccion,
					512: avisoSesion,
					513: avisoNiveles,
					514: avisoError // Revisar
				},
				dataType: $response,
				beforeSend: function() {
					$form.find(".form-error").html("&nbsp;");
					$form.find("button, input[type='submit']").attr("disabled", "disabled");
				},
				success: function(sRes) {

					if ($validar) {

						$validacion = validarFormulario(sRes);

					}

					if ($validacion) {

						if (sRes.status == "funcion") {
							window[sRes.nomFuncion](sRes);
						} else if (sRes.status == "cerrarModal") {
							$(".modal-general").dialog("close");
						} else {
							$form.find(".form-error").html(sRes.mensaje);
						}

					} else {
						$form.find(".form-error").html(sRes.mensaje);
					}

				},
				complete: function() {
					$form.find("button, input[type='submit']").removeAttr("disabled", "disabled");
				},
				error: function() {
					$form.find(".form-error").html("Error de javascript.");
				}
			});

		}
	}, 'form[data-load="ajax"]'); // envío de formularios

	/**
	 * Envío de una instrucción 
	 */
	$("body").on({
		click: function(event) {

			event.preventDefault();

			var $target = $(this),				// Elemento DOM del evento
				$targetRes = $("#cuerpo"),		// Elemento DOM para la respuesta
				$uri = $target.attr("href"),	// URL
				$method = "GET",				// Metodo de envío
				$dataQry = "",					// Query string solo para metodo de envío por POST
				$response = "json";				// Tipo de respuesta

			// Metodo de envío
			if ($target.data("method")) {
				$method = $target.data("method");
				// Si el metodo de envío es POST
				if ($method == "post") {
					qryStr = $uri.split("?");
					$dataQry = qryStr[1];
				}
			}

			// Elemento DOM para la respuesta
			if ($target.data("target")) {
				$targetRes = $("#" + $target.data("target"));
			}

			// Opciones del modal
			if ($target.data("opts")) {
				options = $target.data("opts");
			}

			// Validar la acción
			if (!$target.data("accion")) {

				$.ajax({
					type: $method,
					url: $uri,
					cache: false,
					data: $dataQry,
					dataType: $response,
					statusCode: {
						404: avisoSeccion,
						512: avisoSesion,
						513: avisoNiveles,
						514: avisoError // Revisar
					},
					beforeSend: function() {
						$target.data("accion", "cargando");
					},
					success: function(sRes) {

						if ($response == "json") {

							if (sRes.status == "funcion") {
								window[sRes.nomFuncion](sRes);
							} else {
								$form.find(".form-error").html(sRes.mensaje);
							}

						} else {

							$targetRes.html(sRes);

						}

					},
					complete: function() {
						$target.removeData("accion");
					}
				});

			}

		}
	}, 'a[data-load="accion"]'); // Envío de una instrucción

	// Actividad
	$("#mostrar-actividad").ajaxStart(function() {
		$(this).css("visibility", "visible");
	}).ajaxStop(function() {
		$(this).css("visibility", "hidden");
	});

	$('body').on('click', '#importarInet', function(event) {
		event.preventDefault();

		$.ajax({
			type: 'GET',
			url: 'home/index?action=import',
			type: 'html',
			beforeSend: function() {

			},
			success: function(sRes) {
				alert(sRes);
			}
		});
	});

});