/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/js/home/registros/
 * @version $Id: registros.js 1.0 2012-05-08 23:46 Daniel $;
 * @author: Daniel
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
$(function() {

	// Colocamos el foco en el campo de busqueda ID
	//$("#id_registro_b").focus();

	// Muestra el formulario alta de registro
	formAltaRegistro = function(sRes) {
		$("#registros").hide();

		// Cargamos el formulario
		$.get(sRes.seccion + "/?action=formAltaRegistro", function(respuesta) {
			$("#cuerpo").append(respuesta);
			$("#nombre").focus();

			// Asignamos costos del registro dependiendo de la categoria del registro
			$("#cat_registro").change(function() {
				var clave = $(this).val();

				$.ajax({
					type: "POST",
					url: sRes.seccion + "/?action=listaCostosCategoria",
					data: "&cve=" + clave,
					success: function(sRes) {
						$("#id_costo").html(sRes).removeAttr("disabled").focus();
					}
				});
			});

			// Habilitar campos de RFC y Del o Mun si es México de datos de dirección
			$("#pais").change(function() {
				$this = $(this),
				$valor =  $this.val();
				$("#cp").val('').focus();
				$("#cp_RS").val('');

				if ($valor == 146) {
					$("#del_o_mun").removeAttr("disabled");
				} else {
					$("#del_o_mun").attr("disabled", "disabled");
				}
			});

			// Habilitar campos de RFC y Del o Mun si es México de facturación
			$("#pais_RS").change(function() {
				$this = $(this),
				$valor =  $this.val();
				$("#cp_RS").val('');

				if ($valor == 146) {
					$("#rfc_RS, #del_o_mun_RS").removeAttr("disabled");
				} else {
					$("#rfc_RS, #del_o_mun_RS").attr("disabled", "disabled");
				}
			});

			// Asignamos costos de los acompañantes dependiendo de la categoria
			$("body").on({
				change: function() {
					var $input = $(this),
						clave = $input.val(),
						$p = $input.parents(".control-group"),
						$s = $p.next().children(".controls").children("select");

					$.ajax({
						type: "POST",
						url: sRes.seccion + "/?action=listaCostosCategoriaAcom",
						data: "cve=" + clave,
						success: function(sRes) {
							$s.html(sRes).removeAttr("disabled").focus();
						}
					});
				}
			}, "select[name^='acm_clave']");

			// Asignamos costos del item dependiendo de la categoria
			$("body").on({
				change: function() {
					var $input = $(this),
						clave = $input.val(),
						$p = $input.parents(".control-group"),
						$s = $p.next().children(".controls").children("select");

					$.ajax({
						type: "POST",
						url: sRes.seccion + "/?action=listaCostosCategoriaItem",
						data: "cve=" + clave,
						success: function(sRes) {
							$("input[name^='item_cantidad_']").focus();
							$s.html(sRes).removeAttr("disabled");
						}
					});
				}
			}, "select[name^='item_clave_']");

			// Colocamos el costo total de los items
			$("body").on("keyup blur", "input[name^='item_cantidad_']", function() {
				var $input = $(this),
					$num = $input.val(),
					$p = $input.parents(".horizontal"),
					$clave = $p.find("select[name^='item_clave_']").val(),
					$idCosto = $p.find("select[name^='item_id_costo_']").val();

				$.ajax({
					type: "POST",
					url: sRes.seccion + "/?action=getCostoTotalItem",
					data: {"cantidad": $num, "clave": $clave, "idCosto": $idCosto},
					success: function(sRes) {
						$p.find("input[name^='item_costo_total_']").val(sRes);
					}
				});
			});

			// Seteamos a cero los campos cantidad y costo total de los items
			$("select[name^='item_id_costo_'], select[name^='item_clave_']").on({
				change: function() {
					var $input = $(this),
					$p = $input.parents(".horizontal");
					// Seteamos a cero el costo total
					$p.find("input[name^='item_cantidad_']").val('');
					$p.find("input[name^='item_costo_total_']").val('0.00');
				}
			});

			autoCompletar(sRes);
		}); // carga formulario
	};

	// Completar datos de dirección
	autoCompletar = function(sRes) {
		// Datos de contacto
		$( "#cp" ).blur(function() {
			var $input = $(this),
				$copo = $input.val(),
				pais = $("#pais").val();

			if (pais == 146 && $copo != '' && $copo != 0) {
				$.ajax({
					type: "POST",
					url: sRes.seccion + "/?action=detallesCP",
					data: "cp=" + $input.val(),
					dataType: "json",
					success: function(sRes) {

						if (sRes.cps.length > 0) {
							cps = sRes.cps;
							select = "";

							$("#del_o_mun").val(cps[0].cp_nombreMnpio);
							$("#estado").val(cps[0].cp_nombreEstado);
							$("#ciudad").val(cps[0].cp_nombreCiudad);

							if (cps.length > 0) {

								select = '<select id="colonia" name="colonia">';
								select += '<option value="">Seleccione:</option>';

								for (cp in cps) {
									select += '<option value="' + cps[cp].cp_asenta + '">' + cps[cp].cp_asenta + '</option>';
								}

								select += '</select>';

								$("#colonia").replaceWith(select);

							} else {
								$("#colonia").val(cps[0].cp_asenta);
							}
						} else {
							$("#colonia").replaceWith('<input class="span2" id="colonia" name="colonia" type="text" />');
						}
					}
				});
			} else {
				$("#colonia").replaceWith('<input class="span2" id="colonia" name="colonia" type="text" />');
			}
		});

		// Datos de facturación
		$( "#cp_RS" ).blur(function() {
			var $input = $(this),
				pais = $("#pais_RS").val();

			if (pais == 146 && $input.val()) {
				$.ajax({
					type: "POST",
					url: sRes.seccion + "/?action=detallesCP",
					data: "cp=" + $input.val(),
					dataType: "json",
					success: function(sRes) {

						if (sRes.cps.length > 0) {
							cps = sRes.cps;
							select = "";

							$("#del_o_mun_RS").val(cps[0].cp_nombreMnpio);
							$("#estado_RS").val(cps[0].cp_nombreEstado);
							$("#ciudad_RS").val(cps[0].cp_nombreCiudad);

							if (cps.length > 0) {

								select = '<select id="colonia_RS" name="colonia_RS">';
								select += '<option value="">Seleccione:</option>';

								for (cp in cps) {
									select += '<option value="' + cps[cp].cp_asenta + '">' + cps[cp].cp_asenta + '</option>';
								}

								select += '</select>';

								$("#colonia_RS").replaceWith(select);

							} else {
								$("#colonia_RS").val(cps[0].cp_asenta);
							}
						} else {
							$("#colonia_RS").replaceWith('<input class="span2" id="colonia_RS" name="colonia_RS" type="text" />');
						}

					}
				});
			} else {
				$("#colonia_RS").replaceWith('<input class="span2" id="colonia_RS" name="colonia_RS" type="text" />');
			}
		});
	};

	// Copiar datos de dirección a facturación
	$("body").on({
		click: function() {
			$check = $(this);

			if ($check.is(":checked")) {
				$("#direccion_RS").val($("#direccion").val());
				$("#cp_RS").val($("#cp").val());
				$("#colonia_RS").val($("#colonia").val());
				$("#estado_RS").val($("#estado").val());
				$("#ciudad_RS").val($("#ciudad").val());
				$("#pais_RS").val($("#pais").val());
				// Si es México habilitamos campos RFC y Del o Mun
				if ($("#pais").val() == 146) {
					$("#del_o_mun_RS").val($("#del_o_mun").val());
					$("#rfc_RS, #del_o_mun_RS").removeAttr("disabled");
				} else {
					$("#rfc_RS, #del_o_mun_RS").attr("disabled", "disabled");
				}
				// Copia de la empresa o institución a razon social
				if ($("#emp_o_ins").val() != "") {
					$("#razon_social_RS").val($("#emp_o_ins").val());
				}
				// Copia de email a email factura
				if ($("#email").val() != "") {
					$("#email_RS").val($("#email").val());
				}
				// Copia de teléfono a email factura
				if ($("#telefono_particular").val() != "") {
					$("#telefono_RS").val($("#telefono_particular").val());
				}
			} else {
				$("#razon_social_RS").val("");
				$("#direccion_RS").val("");
				$("#cp_RS").val("");
				$("#colonia_RS").val("");
				$("#del_o_mun_RS").val("");
				$("#estado_RS").val("");
				$("#ciudad_RS").val("");
				$("#pais_RS").val();
				$("#email_RS").val("");
				$("#telefono_RS").val("");
			}
		}
	}, "#copiar_datos_direccion");

	// Función fin alta de registro
	finNuevoRegistro = function(sRes) {
		$("#datos-generales-registro").hide();
		$("#total-registros").text(sRes.totalRegistros);

		// Si el registro es pagado
		if (sRes.totalRegPagados > 0) {
			$("#total-registros-pagados").text(sRes.totalRegPagados);
		}

		$("#fin-captura-reg").attr("value", sRes.idRegistro);

		$('button[data-show-form!="generales"]').removeAttr("disabled");

		$('button[data-show-form="generales"]').addClass("btn-success");

		$('#salon').attr('onclick', "window.open('http://tecnoregistro.mx/cena/?id_registro=" + sRes.idRegistro + "', '_blank');");

		$("#datos-contacto-registro").show();
	};

	// Función fin alta datos de contacto
	finDatosContacto = function(sRes) {
		mensaje = sRes.mensaje;

		$('button[data-show-form="contacto"]').attr("disabled", "disabled").removeClass("btn-primary").addClass("btn-success");

		$("#datos-contacto-registro form").hide();
		$("#datos-contacto-registro").append(mensaje);
	};

	// Nuevo formulario de acompañante
	formNuevoAcm = function(sRes) {
		var $boton = $("a[data-id-form-acm]"),
			$idAcmForm = $boton.data("id-form-acm"),
			$nuevoIdAcmForm = $idAcmForm + 1;

		generos = sRes.generos;
		titulos = sRes.titulos;

		$boton.data("id-form-acm", $nuevoIdAcmForm);
		$("#num_acm_act").val($nuevoIdAcmForm);

		// Clone categorias
		$categorias = $("#acm_clave_1").html();

		// Clone genero
		$generos = $("#acm_genero_1").html();

		// Clone titulos
		$titulos = $("#acm_titulo_1").html();

		$form = '<fieldset class="horizontal" id="acm-form-' + $idAcmForm + '">' +
				'<div class="control-group">' +
					'<label class="control-label" for="acm_clave_' + $idAcmForm + '">Tipo de acompañante:</label>' +
					'<div class="controls">' +
						'<select class="" id="acm_clave_' + $idAcmForm + '" name="acm_clave_' + $idAcmForm + '">' +
							$categorias +
						'</select>' +
					'</div>' +
				'</div>' +
				'<div class="control-group">' +
					'<label class="control-label" for="acm_id_costo_' + $idAcmForm + '">Costo del acompañante:</label>' +
					'<div class="controls">' +
						'<select class="span2" id="acm_id_costo_' + $idAcmForm + '" name="acm_id_costo_' + $idAcmForm + '" disabled>' +
							'<option value="">Seleccione:</option> ' +
						'</select>' +
					'</div>' +
				'</div>' +
				'<div class="control-group">' +
					'<label class="control-label" for="acm_genero_' + $idAcmForm + '">Genero</label>' +
					'<div class="controls">' +
						'<select id="acm_genero_' + $idAcmForm + '" name="acm_genero_' + $idAcmForm + '">' +
							$generos +
						'</select>' +
					'</div>' +
				'</div>' +
				'<div class="control-group">' +
					'<label class="control-label">Eliminar este aconpañante</label>' +
					'<div>' +
						'<button class="btn btn-danger eliminar-form-acm" data-eliminar-form-acm="' + $idAcmForm + '" type="button">' +
							'<i class="icon-remove-sign icon-white"></i> Eliminar' +
						'</button>' +
					'</div>' +
				'</div><br />' +
				'<div class="control-group">' +
					'<label class="control-label" for="acm_nombre_' + $idAcmForm + '">Nombre</label>' +
					'<div>' +
						'<input class="span2" id="acm_nombre_' + $idAcmForm + '" name="acm_nombre_' + $idAcmForm + '" type="text" />' +
					'</div>' +
				'</div>' +
				'<div class="control-group">' +
					'<label class="control-label" for="acm_app_' + $idAcmForm + '">Apellido paterno</label>' +
					'<div>' +
						'<input class="span2" id="acm_app_' + $idAcmForm + '" name="acm_app_' + $idAcmForm +'" type="text" />' +
					'</div>' +
				'</div>' +
				'<div class="control-group">' +
					'<label class="control-label" for="acm_apm_' + $idAcmForm + '">Apellido materno</label>' +
					'<div>' +
						'<input class="span2" id="acm_apm_' + $idAcmForm + '" name="acm_apm_' + $idAcmForm + '" type="text" />' +
					'</div>' +
				'</div>' +
				'<div class="control-group">' +
					'<label class="control-label" for="acm_titulo_' + $idAcmForm + '">Titulo</label>' +
					'<div class="controls">' +
						'<select id="acm_titulo_' + $idAcmForm + '" name="acm_titulo_' + $idAcmForm + '">' +
							$titulos +
						'</select>' +
					'</div>' +
				'</div>' +
			'</fieldset>';

		$("#agr-acms").append($form);

		// Elimina un form
		$(".eliminar-form-acm").click(function() {
			$formAcmEliminar = $(this).data("eliminar-form-acm");
			$("#acm-form-" + $formAcmEliminar).remove();
		});
	};

	// Nuevo formulario de acompañante -- new version
	formAcompanante = function(sRes) {
		var $boton = $("a[data-id-form-acm]"),
			$idAcm = $boton.data("id-form-acm"),
			$nuevoIdAcm = $idAcm + 1;

		evento = sRes.evento;
		seccion = sRes.seccion;
		categorias = sRes.categorias;
		generos = sRes.generos;
		titulos = sRes.titulos;
		statusReg = sRes.statusReg;

		$boton.data("id-form-acm", $nuevoIdAcm);

		$form = '<form action="' + seccion + '/?action=agregarAcompanante" data-load="ajax" id="num-acm-' + $idAcm + '" method="post" name="datos-acompanante-' + $idAcm + '">' +
			'<input name="clave_evento" type="hidden" value="' + evento.evt_clave + '" />' +
			'<input name="num_acm" type="hidden" value="' + $idAcm + '" />' +
			'<fieldset class="horizontal">' +
				'<div class="control-group">' +
					'<label class="control-label" for="acm_clave_' + $idAcm + '">Tipo de acompañante:</label>' +
					'<div class="controls">' +
						'<select id="acm_clave_' + $idAcm + '" name="acm_clave_' + $idAcm + '">' +
							'<option value="">Seleccione:</option>';

						for (i in categorias ) {
							$form += '<option value="' + categorias[i].ecan_clave + '">' + categorias[i].ecan_nombre + '</option>';
						}

						
		$form +=		'</select>' +
					'</div>' +
				'</div>' +
				'<div class="control-group">' +
					'<label class="control-label" for="acm_id_costo_' + $idAcm + '">Costo del acompañante:</label>' +
					'<div class="controls">' +
						'<select class="span2" id="acm_id_costo_' + $idAcm + '" name="acm_id_costo_' + $idAcm + '" disabled>' +
							'<option value="">Seleccione:</option> ' +
						'</select>' +
					'</div>' +
				'</div>' +
				'<div class="control-group">' +
					'<label class="control-label" for="acm_genero_' + $idAcm + '">Genero:</label>' +
					'<div class="controls">' +
						'<select id="acm_genero_' + $idAcm + '" name="acm_genero_' + $idAcm + '">' +
							'<option value="">Seleccione:</option>';

						for ( i in generos ) {
							$form += '<option value="' + generos[i].gen_clave + '">' + generos[i].gen_nombre + '</option>';
						}

		$form +=		'</select>' +
					'</div>' +
				'</div>' +
				'<div class="control-group">' +
					'<label class="control-label" for="acm_status_' + $idAcm + '">Status:</label>' +
					'<div class="controls">' +
						'<select id="acm_status_' + $idAcm + '" name="acm_status_' + $idAcm + '">' +
							'<option value="">Seleccione:</option>';

				for ( i in statusReg ) {
					$form += '<option value="' + statusReg[i].esr_clave + '">' + statusReg[i].esr_nombre + '</option>';
				}

		$form +=		'</select>' +
					'</div>' +
				'</div>' +
			'</fieldset>' +
			'<fieldset class="horizontal">' +
				'<div class="control-group">' +
					'<label class="control-label" for="acm_nombre_' + $idAcm + '">Nombre:</label>' +
					'<div>' +
						'<input class="span2" id="acm_nombre_' + $idAcm + '" name="acm_nombre_' + $idAcm + '" type="text" />' +
					'</div>' +
				'</div>' +
				'<div class="control-group">' +
					'<label class="control-label" for="acm_app_' + $idAcm + '">Apellido paterno:</label>' +
					'<div>' +
						'<input class="span2" id="acm_app_' + $idAcm + '" name="acm_app_' + $idAcm +'" type="text" />' +
					'</div>' +
				'</div>' +
				'<div class="control-group">' +
					'<label class="control-label" for="acm_apm_' + $idAcm + '">Apellido materno:</label>' +
					'<div>' +
						'<input class="span2" id="acm_apm_' + $idAcm + '" name="acm_apm_' + $idAcm + '" type="text" />' +
					'</div>' +
				'</div>' +
				'<div class="control-group">' +
					'<label class="control-label" for="acm_titulo_' + $idAcm + '">Titulo:</label>' +
					'<div class="controls">' +
						'<select id="acm_titulo_' + $idAcm + '" name="acm_titulo_' + $idAcm + '">' +
							'<option value="">Seleccione:</option>';
						
						for (i in titulos) {
							$form += '<option value="' + titulos[i].ect_clave + '">' + titulos[i].ect_clave + '</option>';
						}

		$form +=		'</select>' +
					'</div>' +
				'</div>' +
			'</fieldset>' +
			'<div class="acciones-form">' +
				'<div class="form-error">&nbsp;</div>' +
				'<div>' +
					'<button class="btn btn-success" type="submit">' +
						'<i class="icon-ok-sign icon-white"></i> Agregar ' +
					'</button>' +
				'</div>' +
			'</div><br />' +
		'</form>';

		dialog = $("<div />", {
			id: "form-nuevo-acom",
			title: "Agregar acompañante",
			html: $form
		});

		dialog.dialog({
			close: function() {
				$(this).remove();
			},
			modal: true,
			resizable: false,
			width: 784
		});
	};

	// Función fin alta acompañante -- new version
	finAgregarAcompanante = function(sRes) {
		acom = sRes.idAcom;
		seccion = sRes.seccion;
		clave = $("select#acm_clave_" + acom + " option:selected").val();
		costo = $("select#acm_id_costo_" + acom + " option:selected").val();
		genero = $("select#acm_genero_" + acom + " option:selected").val();
		titulo = $("select#acm_titulo_" + acom + " option:selected").val();
		status = $("select#acm_status_" + acom + " option:selected").val();

		$form = $("#num-acm-" + acom).clone();

		$($form).appendTo("#agr-acms");

		$("#form-nuevo-acom").dialog("close");

		$("select#acm_clave_" + acom + " option[value='" +  clave + "']").attr("selected", "selected");
		$("select#acm_id_costo_" + acom + " option[value='" + costo + "']").attr("selected", "selected");
		$("select#acm_genero_" + acom + " option[value='" + genero + "']").attr("selected", "selected");
		$("select#acm_titulo_" + acom + " option[value='" + titulo + "']").attr("selected", "selected");
		$("select#acm_status_" + acom + " option[value='" + status + "']").attr("selected", "selected");

		botones = '<button class="btn btn-mini btn-primary" type="submit">' +
					'<i class="icon-ok-sign icon-white"></i> Actualizar' +
				'</button> ' +
				'<a class="btn btn-mini btn-danger" data-load="accion" href="' +  seccion + '/?action=confEliminarAcom&id=' + acom + '">' +
					'<i class="icon-remove-sign icon-white"></i> Eliminar ' +
				'</a>';

		if (status == "PAG" || status == "COR" || status == "CCC") {
			botones += ' <span class="btns-imp-acompanante"><a class="btn btn-mini btn-success" data-load="accion" href="' +  seccion + '/?action=impresionGafeteAcom&amp;id=' + acom + '">' +
							'<i class="icon-barcode icon-white"></i> Imprimir Gafete' +
						'</a></span>';
		} else {
			botones += '<span class="btns-imp-acompanante"></span>';
		}

		$("#num-acm-" + acom).attr("action", seccion + "/?action=actualizarAcompanante");
		$("#num-acm-" + acom + " .acciones-form div:eq(1)").html(botones);
	};

	// Confirmacion para eliminar un acompañante
	confEliminarAcom = function(sRes) {
		idAcom = sRes.idAcom;
		seccion = sRes.seccion;
		titulo = sRes.titulo;
		mensaje = sRes.mensaje;

		var $form = '<form action="' + seccion + '/?action=eliminarAcompanante" data-load="ajax" name="eliminar-acompanante" method="post">' +
			'<input name="num_acm" type="hidden" value="' + idAcom + '" />' +
			'<p class="alertas">' + mensaje + '</p>' +
			'<div class="acciones-form">' +
				'<div class="form-error">&nbsp;</div>' +
				'<div>' +
					'<input class="btn btn-danger" type="submit" value="Aceptar" /> ' +
					'<input class="btn" id="cancelar-eliminar-acom" type="button" value="Cancelar" />' +
				'</div>' +
			'</div>' +
		'</form>';

		$dialog = $("<div />", {
			id: "alerta-eliminar-acompanante",
			title: titulo,
			html: $form
		});

		$dialog.dialog({
			close: function() {
				$(this).remove();
			},
			modal: true,
			resizable: false,
			width: 366
		});

		$("#cancelar-eliminar-acom").click(function() {
			$("#alerta-eliminar-acompanante").dialog("close");
		});
	};

	// Elimina el acompañante
	eliminarAcompanante = function(sRes) {
		idAcom = sRes.idAcom;
		$("form#updateAcompanante_" + idAcom).fadeOut("slow");
		$("#alerta-eliminar-acompanante").dialog("close");
	};

	// Función fin alta acompañantes
	finAltaAcompanantes = function(sRes) {
		mensaje = sRes.mensaje;

		$('button[data-show-form="acompanantes"]').attr("disabled", "disabled").removeClass("btn-info").addClass("btn-success");

		$("#datos-acompanantes-registro form").remove();
		$("#datos-acompanantes-registro").html(mensaje);
	};

	// Nuevo formulario de item
	$("body").on("click", "#nuevo-item", function(event) {
		event.preventDefault();

		var $boton = $(this),
			$idItemForm = $boton.data("id-form-item"),
			$nuevoIdItemForm = $idItemForm + 1;

		$boton.data("id-form-item", $nuevoIdItemForm);
		$("#num_item_act").val($nuevoIdItemForm);

		// Clon de categorias
		$categorias = $("#item_clave_1").html();

		$form = '<fieldset class="horizontal" id="item-form-' + $idItemForm + '">' +
				'<div class="control-group">' +
					'<label class="control-label" for="item_clave_' + $idItemForm + '">Items:</label>' +
					'<div class="controls">' +
						'<select id="item_clave_' + $idItemForm + '" name="item_clave_' + $idItemForm + '">' +
							$categorias +
						'</select>' +
					'</div>' +
				'</div>' +
				'<div class="control-group">' +
					'<label class="control-label" for="item_id_costo_' + $idItemForm + '">Costo unitario:</label>' +
					'<div class="controls">' +
						'<select id="item_id_costo_' + $idItemForm + '" name="item_id_costo_' + $idItemForm + '" disabled>' +
							'<option value="">Seleccione:</option>' +
						'</select>' +
					'</div>' +
				'</div>' +
				'<div class="control-group">' +
					'<label class="control-label" for="item_cantidad_' + $idItemForm + '">Cantidad:</label>' +
					'<div class="controls">' +
						'<input class="span1" id="item_cantidad_' + $idItemForm + '" name="item_cantidad_' + $idItemForm + '" type="text" />' +
					'</div>' +
				'</div>' +
				'<div class="control-group" style="min-width: 164px;">' +
					'<label class="control-label" for="item_costo_total_' + $idItemForm + '">Total:</label>' +
					'<div class="controls">' +
						'$ <input class="input-small form-control" id="item_costo_total_' + $idItemForm + '" name="item_costo_total_' + $idItemForm + '" readonly value="0.00" />' +
					'</div>' +
				'</div>' +
				'<div class="control-group" style="min-width: 164px;">' +
					'<label class="control-label">&nbsp;</label>' +
					'<div class="controls">' +
						'<button class="btn btn-danger eliminar-form-item" data-eliminar-form-item="' + $idItemForm + '" type="button">' +
							'<i class="icon-remove-sign icon-white"></i> Eliminar' +
						'</button> ' +
					'</div>' +
				'</div>' +
			'</fieldset>';

		$("#agr-items").append($form);

		// Seteamos a cero los campos cantidad y costo total de los items
		$("select[name^='item_id_costo_'], select[name^='item_clave_']").change(function() {
			var $input = $(this),
			$p = $input.parents(".horizontal");
			// Seteamos a cero el costo total
			$p.find("input[name^='item_cantidad_']").val('');
			$p.find("input[name^='item_costo_total_']").val('0.00');
		});

		// Elimina un form
		$(".eliminar-form-item").click(function() {
			$formItemEliminar = $(this).data("eliminar-form-item");
			$("#item-form-" + $formItemEliminar).remove();
		});
	});

	// Función fin alta items
	finAltaItems = function(sRes) {
		mensaje = sRes.mensaje;

		$('button[data-show-form="adicionales"]').attr("disabled", "disabled").removeClass("btn-info").addClass("btn-success");

		$("#datos-adicionales-registro form").remove();
		$("#datos-adicionales-registro").html(mensaje);
	};

	// Nuevo formulario de item -- new version
	formItem = function(sRes) {
		var $boton = $("a[data-id-form-item]"),
			$idItem = $boton.data("id-form-item"),
			$nuevoIdItem = $idItem + 1;

		seccion = sRes.seccion;
		evento = sRes.evento;
		statusReg = sRes.statusReg;
		items = sRes.items;

		$boton.data("id-form-item", $nuevoIdItem);

		$form = '<form action="' + seccion + '/?action=agregarItem" data-load="ajax" id="num-item-' + $idItem + '" method="post" name="datos-item-' + $idItem + '">' +
			'<input name="clave_evento" type="hidden" value="' + evento.evt_clave + '" />' +
			'<input name="num_item" type="hidden" value="' + $idItem + '" />' +

			'<fieldset class="horizontal">' +
				'<div class="control-group">' +
					'<label class="control-label" for="item_clave_' + $idItem + '">Items:</label>' +
					'<div class="controls">' +
						'<select id="item_clave_' + $idItem + '" name="item_clave_' + $idItem + '">' +
							'<option value="">Seleccione:</option>';

				for (i in items ) {
					$form += '<option value="' + items[i].eni_clave + '"';
					$form += items[i].eci_paxMaximos != 0 && parseInt(items[i].total_conf.filas) >= parseInt(items[i].eci_paxMaximos) ? ' disabled' : '';
					$form += '>' + items[i].eni_nombre;
					$form += items[i].eci_paxMaximos != 0 && parseInt(items[i].total_conf.filas) >= parseInt(items[i].eci_paxMaximos) ? ' - Agotado' : '';
					$form += '</option>';
				}

		$form +=		'</select>' +
					'</div>' +
				'</div>' +
				'<div class="control-group">' +
					'<label class="control-label" for="item_id_costo_' + $idItem + '">Costo unitario:</label>' +
					'<div class="controls">' +
						'<select id="item_id_costo_' + $idItem + '" name="item_id_costo_' + $idItem + '" disabled>' +
							'<option value="">Seleccione:</option>' +
						'</select>' +
					'</div>' +
				'</div>' +
				'<div class="control-group" style="min-width: 78px;">' +
					'<label class="control-label" for="item_cantidad_' + $idItem + '">Cantidad:</label>' +
					'<div class="controls">' +
						'<input autocomplete="off" class="span1" id="item_cantidad_' + $idItem + '" name="item_cantidad_' + $idItem + '" type="text" value="1" />' +
					'</div>' +
				'</div>' +
				'<div class="control-group" style="min-width: 110px;">' +
					'<label class="control-label" for="item_costo_total_' + $idItem + '">Total:</label>' +
					'<div class="controls">' +
						'$ <input class="input-small" id="item_costo_total_' + $idItem + '" name="item_costo_total_' + $idItem + '" readonly />' +
					'</div>' +
				'</div>' +
				'<div class="control-group">' +
					'<label class="control-label" for="item_status_' + $idItem + '">Status:</label>' +
					'<div class="controls">' +
						'<select id="item_status_' + $idItem + '" name="item_status_' + $idItem + '">' +
							'<option value="">Seleccione:</option>';

				for ( i in statusReg ) {
					$form += '<option value="' + statusReg[i].esr_clave + '">' + statusReg[i].esr_nombre + '</option>';
				}

		$form +=		'</select>' +
					'</div>' +
				'</div>' +
			'</fieldset>' +
			'<div class="acciones-form">' +
				'<div class="form-error">&nbsp;</div>' +
				'<div>' +
					'<button class="btn btn-success" type="submit">' +
						'<i class="icon-ok-sign icon-white"></i> Agregar' +
					'</button>' +
				'</div>' +
			'</div>' +
			'<br />' +
		'</form>';

		dialog = $("<div />", {
			id: "form-nuevo-item",
			title: "Agregar item",
			html: $form
		});

		dialog.dialog({
			close: function() {
				$(this).remove();
			},
			modal: true,
			resizable: false,
			width: 900
		});
	};

	// Función fin alta item -- new version
	finAgregarItem = function(sRes) {
		item = sRes.idItem;
		seccion = sRes.seccion;
		clave = $("select#item_clave_" + item + " option:selected").val();
		costo = $("select#item_id_costo_" + item + " option:selected").val();
		status = $("select#item_status_" + item + " option:selected").val();

		$form = $("#num-item-" + item).clone();

		$($form).appendTo("#agr-items");

		$("#form-nuevo-item").dialog("close");

		$("select#item_clave_" + item + " option[value='" +  clave + "']").attr("selected", "selected");
		$("select#item_id_costo_" + item + " option[value='" + costo + "']").attr("selected", "selected");
		$("select#item_status_" + item + " option[value='" + status + "']").attr("selected", "selected");

		botones = '<button class="btn btn-mini btn-success" type="submit">' +
					'<i class="icon-ok-sign icon-white"></i> Actualizar' +
				'</button> ' +
				'<a class="btn btn-mini btn-danger" data-load="accion" href="' +  seccion + '/?action=confEliminarItem&id=' + item + '">' +
					'<i class="icon-remove-sign icon-white"></i> Eliminar ' +
				'</a>';

		$("#num-item-" + item).attr("action", seccion + "/?action=actualizarItem");
		$("#num-item-" + item + " .acciones-form div:eq(1)").html(botones);
	};

	// Confirmacion para eliminar un item
	confEliminarItem = function(sRes) {
		idItem = sRes.idItem;
		seccion = sRes.seccion;
		titulo = sRes.titulo;
		mensaje = sRes.mensaje;

		var $form = '<form action="' + seccion + '/?action=eliminarItem" data-load="ajax" name="eliminar-item" method="post">' +
			'<input name="num_item" type="hidden" value="' + idItem + '" />' +
			'<p class="alertas">' + mensaje + '</p>' +
			'<div class="acciones-form">' +
				'<div class="form-error">&nbsp;</div>' +
				'<div>' +
					'<input class="btn btn-danger" type="submit" value="Aceptar" /> ' +
					'<input class="btn" id="cancelar-eliminar-item" type="button" value="Cancelar" />' +
				'</div>' +
			'</div>' +
		'</form>';

		$dialog = $("<div />", {
			id: "alerta-eliminar-item",
			title: titulo,
			html: $form
		});

		$dialog.dialog({
			close: function() {
				$(this).remove();
			},
			modal: true,
			resizable: false,
			width: 366
		});

		$("#cancelar-eliminar-item").click(function() {
			$("#alerta-eliminar-item").dialog("close");
		});
	};

	// Elimina el item
	eliminarItem = function(sRes) {
		idItem = sRes.idItem;
		$("form#num-item-" + idItem).fadeOut("slow");
		$("#alerta-eliminar-item").dialog("close");
	};

	// Función fin alta datos de facturación
	finDatosFacturacion = function(sRes) {
		mensaje = sRes.mensaje;

		$('button[data-show-form="facturacion"]').attr("disabled", "disabled").removeClass("btn-inverse").addClass("btn-success");

		$("#datos-facturacion-registro").html(mensaje);
	};

	// Muestra la seccion del registro elegida
	$("body").on({
		click: function() {
			$button = $(this),
			$seccion = $button.data("show-form");

			$("#datos-generales-registro, #datos-contacto-registro, #datos-acompanantes-registro, #datos-adicionales-registro, #datos-facturacion-registro, #datos-hospedaje-registro").hide();

			$("#datos-" + $seccion + "-registro").show();

			if ($seccion == 'acompanantes') {
				if ($('.updateAcompanante').length <= 0) {
					$('#setAcompanante').modal('show');
				}
			}

			if ($seccion == 'hospedaje') {
				//$('#setHospedaje').modal('show');
			}
		}
	}, 'button[data-show-form]');

	// Cancelar alta de registro
	$("body").on({
		click: function() {
			$("#nuevo-registro, #editar-registro").remove();
			$("#registros").show();
			$("#id_registro_b").focus();
		}
	}, "#cancelar-registro");

	// Finaliza la captura de un registro
	$("body").on({
		click: function() {
			$boton = $(this),
			$idRegistro = $boton.attr("value");

			$("#nuevo-registro").remove();
			$("#registros").show();
			$("#id_registro_b").focus().val($idRegistro);

			$("#buscarRegistros").submit();
		}
	}, "#fin-captura-reg");

	// Autocompleta el ID
	$( "#id_registro_b" ).on({
		keyup: function() {
			$("#buscarRegistros").submit();
		}
	});

	// Autocompleta el app
	$( "#app_b" ).on({
		keyup: function() {
			$("#buscarRegistros").submit();
		}
	});

	// Autocompleta el apm
	$( "#apm_b" ).on({
		keyup: function() {
			$("#buscarRegistros").submit();
		}
	});

	// Autocompleta el nombre
	$( "#nombre_b" ).on({
		keyup: function() {
			$("#buscarRegistros").submit();
		}
	});

	// Buscar por correo electrónico
	$( "#email_b" ).on({
		keyup: function() {
			$("#buscarRegistros").submit();
		}
	});

	// Buscar por empresa o institución
	$( "#emp_o_ins_b" ).on({
		keyup: function() {
			$("#buscarRegistros").submit();
		}
	});

	// Buscar por acompañante
	$( "#acom_b" ).on({
		keyup: function() {
			$("#buscarAcompanante").submit();
		}
	});

	mostrarRegistros = function(sRes) {
		seccion = sRes.seccion;
		registro = sRes.registros;
		$tr = "";

		//if (sRes.registros != undefined) {

			for (i in registro) {

				style = "";

				if (registro[i].status == "PAG") {
					style = " btn-success";
				} else if (registro[i].status == "COR" || registro[i].status == "CCC") {
					style = " btn-info";
				} else if (registro[i].status == "PEN") {
					style = " btn-warning";
				} else if (registro[i].status == "CAN") {
					style = " btn-danger";
				}

				$tr += '<tr>';
					$tr += '<td>';

						$tr += '<div class="btn-group">';
							$tr += '<a class="btn btn-mini' + style + '" data-load="accion" href="' + seccion + '/?action=editarRegistro&amp;id=' + registro[i].id_registro + '">';
								$tr += '<i class="icon-eye-open icon-white"></i>';
							$tr += '</a>';

						if (registro[i].status == "PAG" || registro[i].status == "COR" || registro[i].status == "CCC") {
							$tr += '<a href="#" data-toggle="dropdown" class="btn btn-mini' + style + ' dropdown-toggle"><i class="icon-print icon-white"></i></a>';
							$tr += '<ul class="dropdown-menu idReg-' + registro[i].id_registro + '">';
								$tr += '<li>';
									$tr += '<a data-load="accion" href="' + seccion + '/?action=impresionGafete&amp;id=' + registro[i].id_registro + '">';
										$tr += '<i class="icon-barcode"></i> Imprimir Gafete';
										$tr += (registro[i].impresion_gafete == 1) ? ' <i class="icon-ok-circle"></i>' : '';
									$tr += '</a>';
								$tr += '</li>';
								$tr += '<li>';
									$tr += '<a data-load="accion" href="' + seccion + '/?action=impresionConstancia&amp;id=' + registro[i].id_registro + '">';
										$tr += '<i class="icon-qrcode"></i> Imprimir Constancia';
										$tr += (registro[i].impresion_constancia == 1) ? ' <i class="icon-ok-circle"></i>' : '';
									$tr += '</a>';
								$tr += '</li>';
							$tr += '</ul>';
						}

						$tr += '</div>';
					$tr += '</td>';
					/* asistencia */
					/*$tr += '<td><input type="checkbox" name="impresion_gafete" value="' + registro[i].id_registro + '"'; 
					$tr += (registro[i].impresion_gafete == 1) ? 'checked' : '';
					$tr += '/></td>';*/
					/* end asistencia */
					$tr += '<td>' + registro[i].id_registro + '</td>';
					$tr += '<td>' + registro[i].app + '</td>';
					$tr += '<td>' + registro[i].apm + '</td>';
					$tr += '<td>' + registro[i].nombre + '</td>';
					$tr += '<td style="text-align: center; font-size: 1.4em;"><strong>' + registro[i].enc_nombre + '</strong></td>';
					$tr += '<td>' + registro[i].emp_o_ins + '</td>';
				$tr += '</tr>';

			} // recorrido de registros encontrados

			$("#resultado-registros").html($tr);
		//}

	};

	$('body').on({
		click: function() {
			var _input = $(this);

			if (_input.is(':checked')) {
				$.ajax({
					type: 'POST',
					url: 'home/registros/?action=pasarAsistencia',
					data: {id_registro: _input.val()},
					dataType: 'json',
					beforeShow: function() {
						_input.prop('disabled', true);
					},
					success: function(sRes) {
						alert('Asistencia de: ' + sRes.registro.app + ' ' + sRes.registro.apm + ' ' + sRes.registro.nombre + ' - Categoria: ' + sRes.registro.cat_registro);
					}
				});
			} else {
				$.ajax({
					type: 'POST',
					url: 'home/registros/?action=quitarAsistencia',
					data: {id_registro: _input.val()},
					beforeShow: function() {
						_input.prop('disabled', true);
					},
					success: function(sRes) {
						alert('Asistencia eliminada');
					}
				});
			}
		}
	}, 'input[name="impresion_gafete"]');

	// Finaliza la actualizacion de un registro
	$("body").on({
		click: function() {
			//$boton = $(this),
			//$idRegistro = $boton.attr("value");

			$("#editar-registro").remove();
			$("#registros").show();
			$("#id_registro_b").focus().val('');

			//$("#buscarRegistros").submit();
		}
	}, "#fin-actualizar-reg");

	// Muestra el formulario para editar de registro
	formEditarRegistro = function(sRes) {
		$("#registros").hide();

		var idRegistro = sRes.idReg;

		// Cargamos el formulario
		$.get(sRes.seccion + "/?action=formEditarRegistro", {idReg: idRegistro}, function(respuesta) {
			$("#cuerpo").append(respuesta);
			$("#nombre").focus();

			// Asignamos costos del registro dependiendo de la categoria del registro
			$("#cat_registro").change(function() {
				var clave = $(this).val();

				$.ajax({
					type: "POST",
					url: sRes.seccion + "/?action=listaCostosCategoria",
					data: "&cve=" + clave,
					success: function(sRes) {
						$("#id_costo").html(sRes).removeAttr("disabled").focus();
					}
				});
			});

			// Habilitar campos de RFC y Del o Mun si es México de datos de dirección
			$("#pais").change(function() {
				$this = $(this),
				$valor =  $this.val();
				$("#cp").val('').focus();
				$("#cp_RS").val('');

				if ($valor == 146) {
					$("#del_o_mun").removeAttr("disabled");
				} else {
					$("#del_o_mun").attr("disabled", "disabled");
				}
			});

			// Habilitar campos de RFC y Del o Mun si es México de facturación
			$("#pais_RS").change(function() {
				$this = $(this),
				$valor =  $this.val();
				$("#cp_RS").val('');

				if ($valor == 146) {
					$("#rfc_RS, #del_o_mun_RS").removeAttr("disabled");
				} else {
					$("#rfc_RS, #del_o_mun_RS").attr("disabled", "disabled");
				}
			});

			// Asignamos costos de los acompañantes dependiendo de la categoria
			$("body").on({
				change: function() {
					var $input = $(this),
						clave = $input.val(),
						$p = $input.parents(".control-group"),
						$s = $p.next().children(".controls").children("select");

					$.ajax({
						type: "POST",
						url: sRes.seccion + "/?action=listaCostosCategoriaAcom",
						data: "cve=" + clave,
						success: function(sRes) {
							$s.html(sRes).removeAttr("disabled").focus();
						}
					});
				}
			}, "select[name^='acm_clave']");

			// Asignamos costos del item dependiendo de la categoria
			$("body").on({
				change: function() {
					var $input = $(this),
						clave = $input.val(),
						$p = $input.parents(".control-group"),
						$s = $p.next().children(".controls").children("select");

					$.ajax({
						type: "POST",
						url: sRes.seccion + "/?action=listaCostosCategoriaItem",
						data: "cve=" + clave,
						success: function(sRes) {
							$("input[name^='item_cantidad_']").focus();
							$s.html(sRes).removeAttr("disabled");
						}
					});
				}
			}, "select[name^='item_clave_']");

			// Colocamos el costo total de los items
			$("body").on("keyup blur", "input[name^='item_cantidad_']", function() {
				var $input = $(this),
					$num = $input.val(),
					$p = $input.parents(".horizontal"),
					$clave = $p.find("select[name^='item_clave_']").val(),
					$idCosto = $p.find("select[name^='item_id_costo_']").val();

				$.ajax({
					type: "POST",
					url: sRes.seccion + "/?action=getCostoTotalItem",
					data: {"cantidad": $num, "clave": $clave, "idCosto": $idCosto},
					success: function(sRes) {
						$p.find("input[name^='item_costo_total_']").val(sRes);
					}
				});
			});

			// Seteamos a cero los campos cantidad y costo total de los items
			$("select[name^='item_id_costo_'], select[name^='item_clave_']").on({
				change: function() {
					var $input = $(this),
					$p = $input.parents(".horizontal");
					// Seteamos a cero el costo total
					$p.find("input[name^='item_cantidad_']").val('');
					$p.find("input[name^='item_costo_total_']").val('0.00');
				}
			});

			autoCompletar(sRes);

		}); // carga formulario
	};

	// Función que muestra mensaje de exito de actualización
	exitoActualizacion = function(seccion, msg) {
		$mensaje = $("<strong />", {
			html: '<strong id="msj-actualiza-reg">' + msg + '</strong>'
		});

		$(seccion).find(".form-error").html($mensaje);

		$("#msj-actualiza-reg").fadeOut(2500);
		setTimeout(function() {
			$("#msj-actualiza-reg").replaceWith("&nbsp;");
		}, 2500);
	};

	// Función que actualiza los datos generales del registro
	finActualizarRegistro = function(sRes) {

		idRegistro = sRes.idReg;
		status = sRes.statusReg;
		formaPago = sRes.formaPago;
		seccion = sRes.seccion;
		botones = "";
		cartaCompromiso = false;

		// Si el registro es pagado
		if (sRes.totalRegPagados > 0) {
			$("#total-registros-pagados").text(sRes.totalRegPagados);
		}

		if (status == "PAG" || status == "COR" || status == "CCC") {
			botones = '<a class="btn btn-success" data-load="accion" href="' + seccion + '/?action=impresionGafete&amp;id=' + idRegistro + '">' +
					'<i class="icon-barcode icon-white"></i> Imprimir Gafete' +
				'</a> ' +
				'<a class="btn btn-success" data-load="accion" href="' + seccion + '/?action=impresionConstancia&amp;id=' + idRegistro + '">' +
					'<i class="icon-qrcode icon-white"></i> Imprimir Constancia' +
				'</a>';
		}

		if (formaPago == 'CC' || status == 'CCC') {
			cartaCompromiso = true;
		}

		if (cartaCompromiso) {
			$('#imprimirCartaCompromiso').removeClass('hide');
		} else {
			$('#imprimirCartaCompromiso').addClass('hide');
		}

		if ($('#save-photo').val() == 1) {
			$('#save-photo').val(0);
			$('#type-photo').val('');
			$('#photo').val('');

			$('#delete-photo').remove();

			$('#module-photo').append(
				'<a class="btn btn-danger" data-load="accion" id="delete-photo-saved" href="' + seccion + '/?action=eliminarFotografia&amp;id=' + idRegistro + '">' +
					'<i class="icon-remove icon-white"></i> Eliminar foto' +
				'</a>'
			);
		}

		$("#btns-imp-docs").html(botones);

		exitoActualizacion("#datos-generales-registro", sRes.mensaje);
	};

	// Función fin actualizacion datos de contacto
	finActualizarDatosContacto = function(sRes) {

		exitoActualizacion("#datos-contacto-registro", sRes.mensaje);
	};

	// Función que actualiza un acompañante
	finActualizarAcompanante = function(sRes) {
		seccion = sRes.seccion;
		acom = sRes.idAcom;
		status = sRes.statusReg;
		mensaje = sRes.mensaje;
		botonGafete = "";

		$("form#num-acm-" + acom).find(".form-error").html("<span class='msg-actualizacion-acom'>" + mensaje + "</span>");

		if (status == "PAG" || status == "COR" || status == "CCC") {
			botonGafete = ' <a class="btn btn-mini btn-success" data-load="accion" href="' + seccion + '/?action=impresionGafeteAcom&amp;id=' + acom + '">' +
							'<i class="icon-barcode icon-white"></i> Imprimir Gafete' +
						'</a>';
		}

		$("form#num-acm-" + acom).find(".btns-imp-acompanante").html(botonGafete);

		$(".msg-actualizacion-acom").fadeOut(2000);
	};

	// Función que actualiza un acompañante
	finActualizarItem = function(sRes) {
		idItem = sRes.idItem;
		mensaje = sRes.mensaje;

		$("form#num-item-" + idItem).find(".form-error").html("<span class='msg-actualizacion-item'>" + mensaje + "</span>");

		$(".msg-actualizacion-item").fadeOut(2000);
	};

	// Función fin actualizacion datos de facturación
	finActualizarDatosFacturacion = function(sRes) {

		exitoActualizacion("#datos-facturacion-registro", sRes.mensaje);
	};

	// Función para imprimir gafete
	impresionGafete = function(sRes) {
		if (typeof sRes.seccion != "undefined") {
			seccion = sRes.seccion;
			idReg = sRes.idReg;
			$("#formImpresion").attr("action", seccion);
			$("#formImpresion").submit();
			$("#total-gafetes-impresos").text(sRes.totalRegistrosImp);
			$(".idReg-" + idReg + ' li:first a').append(' <i class="icon-ok-circle"></i>');
		}
	};

	// Función para imprimir gafete
	lecturaImpresion = function(sRes) {
		if (typeof sRes.seccion != "undefined") {
			seccion = 'home/registros/?action=lecturaImpresion';
			idReg = sRes.idReg;

			$html = '<div style="border-top: 1px solid #CCC; padding-top: 10px;">';
				$html += '<form action="' + seccion + '" target="_blank" method="post" name="lecturaImpresion" id="lecturaImpresion">';
					$html += '<fieldset class="horizontal">';
						$html += '<input name="id_registro_re" type="hidden" value="' + idReg + '" />';
						$html += '<input name="reimpresion" type="hidden" value="GFT" />';
						$html += '<div class="control-group">';
							//$html += '<label class="control-label" for="lectura_pistola">Codigo:</label>';
							$html += '<div class="controls">';
								$html += '<input class="span5" id="lectura_pistola" name="lectura_pistola" autofocus="autofocus" required type="text" autocomplete="off" />';
							$html += '</div>';
						$html += '</div>';
					$html += '</fieldset>';

					$html += '<div class="acciones-form">';
						$html += '<div class="form-error">&nbsp;</div>';
						$html += '<div>';
							$html += '<button class="btn btn-success" type="submit" disabled="disabled" id="btn-lectura">';
								$html += 'Imprimir <i class="icon-print icon-white"></i>';
							$html += '</button>';
						$html += '</div>';
					$html += '</div>';

				$html += '</form>';
			$html += '</div>';

			$dialog = $("<div />", {
				id: "lectura-barcode",
				title: "Toma de lectura",
				html: $html
			});

			$dialog.dialog({
				close: function () {
					$(this).remove();
				},
				modal: true,
				resizable: false,
				width: 500
			});

			$('#lecturaImpresion').submit(function() {
				setTimeout(function() {
					$("#lectura-barcode").dialog("close");
				}, 3000);				
			});
		}
	};

	// Función que reimprime el gafete
	reImpresionGafate = function(sRes) {
		registro = sRes.registro;
		seccion = sRes.seccion;

		$html = '<p style="text-align: center;"><strong>Este gafete ya fue impreso</strong></p>';
		$html += '<p><strong>Fecha de última impresión: </strong><br />' + registro.ultimaImpresion + '</p>';
		$html += '<p><strong>Total de impresiones: </strong><br />' + registro.totalImpresiones + '</p>';
		$html += '<div style="border-top: 1px solid #CCC; padding-top: 10px;">';
			$html += '<form action="' + seccion + '" data-load="ajax" method="post" name="setReImpresionGafate">';
				$html += '<fieldset class="horizontal">';
					$html += '<input name="id_registro_re" type="hidden" value="' + registro.id + '" />';
					$html += '<input name="reimpresion" type="hidden" value="GFT" />';
					$html += '<div class="control-group">';
						$html += '<label class="control-label" for="username">Usuario administrador:</label>';
						$html += '<div class="controls">';
							$html += '<input class="span3" id="username" name="username" required type="email" value="eperez@tecnoregistro.com.mx" />';
						$html += '</div>';
					$html += '</div>';
					$html += '<div class="control-group">';
						$html += '<label class="control-label" for="password">Contraseña:</label>';
						$html += '<div class="controls">';
							$html += '<input class="span2" id="password" name="password" required type="password" />';
						$html += '</div>';
					$html += '</div>';
				$html += '</fieldset>';

				$html += '<div class="acciones-form">';
					$html += '<div class="form-error">&nbsp;</div>';
					$html += '<div>';
						$html += '<button class="btn btn-success" type="submit">';
							$html += 'Reimprimir <i class="icon-print icon-white"></i>';
						$html += '</button>';
					$html += '</div>';
				$html += '</div>';

			$html += '</form>';
		$html += '</div>';

		$dialog = $("<div />", {
			id: "alerta-reimpresion",
			title: "Reimpresión de gafate",
			html: $html
		});

		$dialog.dialog({
			close: function () {
				$(this).remove();
			},
			modal: true,
			resizable: false,
			width: 500
		});
	};

	impresionNoPermitida = function(sRes) {
		alert(sRes.mensaje);
	};

	// Función para imprimir constancia
	impresionConstancia = function(sRes) {
		if (typeof sRes.seccion != "undefined") {
			registro = sRes.idRegistro;
			seccion = sRes.seccion;
			$html = "";

			$html += '<form action="' + seccion + '" id="folio-constancia" method="post" name="folio-constancia" target="_blank">';
				$html += '<fieldset class="horizontal">';
					$html += '<input name="id_registro_re" type="hidden" value="' + registro + '" />';

						$html += '<div class="control-group">';
							$html += '<label class="control-label" for="folioConstancia">Folio de la constancia:</label>';
							$html += '<div class="controls">';
								$html += '<input class="span2" id="folioConstancia" name="folioConstancia" required type="text" />';
							$html += '</div>';
						$html += '</div>';
				$html += '</fieldset>';

				$html += '<div class="acciones-form">';
					$html += '<div class="form-error">&nbsp;</div>';
					$html += '<div>';
						$html += '<button class="btn btn-success" type="submit">';
							$html += 'Imprimir <i class="icon-print icon-white"></i>';
						$html += '</button>';
					$html += '</div>';
				$html += '</div>';

			$html += '</form>';

			$dialog = $("<div />", {
				id: "alerta-impresion-constancia",
				title: "Impresion de constancia",
				html: $html
			});

			$dialog.dialog({
				close: function () {
					$('#id_registro_re_re').focus().select();
					$(this).remove();
				},
				modal: true,
				resizable: false,
				width: 250
			});

			$("#folio-constancia").submit(function() {
				setTimeout(function() {
					$(".idReg-" + registro + ' li:last a').append(' <i class="icon-ok-circle"></i>');
					$("#alerta-impresion-constancia").dialog("close");
				}, 3000);
			});
		}
	};

	// Función que reimprime la constancia
	reImpresionConstancia = function(sRes) {
		registro = sRes.registro;
		seccion = sRes.seccion;

		$html = '<p style="text-align: center;"><strong>Esta constancia ya fue impresa</strong></p>';
		$html += '<p><strong>Fecha de última impresión: </strong><br />' + registro.ultimaImpresion + '</p>';
		$html += '<p><strong>Total de impresiones: </strong><br />' + registro.totalImpresiones + '</p>';
		$html += '<div style="border-top: 1px solid #CCC; padding-top: 10px;">';
			$html += '<form action="' + seccion + '" data-load="ajax" method="post" name="setReImpresionConstancia">';
				$html += '<fieldset class="horizontal">';
					$html += '<input name="id_registro_re" type="hidden" value="' + registro.id + '" />';
					$html += '<input name="reimpresion" type="hidden" value="CTC" />';

						$html += '<div class="control-group">';
							$html += '<label class="control-label" for="username">Usuario administrador:</label>';
							$html += '<div class="controls">';
								$html += '<input class="span3" id="username" name="username" required type="email" value="eperez@tecnoregistro.com.mx" />';
							$html += '</div>';
						$html += '</div>';
						$html += '<div class="control-group">';
							$html += '<label class="control-label" for="password">Contraseña:</label>';
							$html += '<div class="controls">';
								$html += '<input class="span2" id="password" name="password" required type="password" />';
							$html += '</div>';
						$html += '</div>';
						$html += '<div class="control-group">';
							$html += '<label class="control-label" for="folioConstancia">Folio de la constancia:</label>';
							$html += '<div class="controls">';
								$html += '<input class="span2" id="folioConstancia" name="folioConstancia" required type="text" />';
							$html += '</div>';
						$html += '</div>';
				$html += '</fieldset>';

				$html += '<div class="acciones-form">';
					$html += '<div class="form-error">&nbsp;</div>';
					$html += '<div>';
						$html += '<button class="btn btn-success" type="submit">';
							$html += 'Reimprimir <i class="icon-print icon-white"></i>';
						$html += '</button>';
					$html += '</div>';
				$html += '</div>';

			$html += '</form>';
		$html += '</div>';

		$dialog = $("<div />", {
			id: "alerta-reimpresion",
			title: "Reimpresión de constancia",
			html: $html
		});

		$dialog.dialog({
			close: function () {
				$('#id_registro_re_re').focus().select();
				$(this).remove();
			},
			modal: true,
			resizable: false,
			width: 500
		});
	};

	// Función para imprimir gafete del acompañante
	impresionGafeteAcom = function(sRes) {
		if (typeof sRes.seccion != "undefined") {
			seccion = sRes.seccion;
			$("#formImpresion").attr("action", seccion);
			$("#formImpresion").submit();
			//$("#total-gafetes-impresos").text(sRes.totalRegistrosImp);
		}
	};

	// Función que reimprime el gafete del acompañante
	reImpresionGafateAcom = function(sRes) {
		acompanante = sRes.acompanante;
		seccion = sRes.seccion;

		$html = '<p style="text-align: center;"><strong>Este gafete ya fue impreso</strong></p>';
		$html += '<p><strong>Fecha de última impresión: </strong><br />' + acompanante.ultimaImpresion + '</p>';
		$html += '<p><strong>Total de impresiones: </strong><br />' + acompanante.totalImpresiones + '</p>';
		$html += '<div style="border-top: 1px solid #CCC; padding-top: 10px;">';
			$html += '<form action="' + seccion + '" data-load="ajax" method="post" name="setReImpresionGafateAcom">';
				$html += '<fieldset class="horizontal">';
					$html += '<input name="id_registro_re" type="hidden" value="' + acompanante.id + '" />';
					$html += '<input name="reimpresion" type="hidden" value="GFT_AC" />';
					$html += '<div class="control-group">';
						$html += '<label class="control-label" for="username">Usuario administrador:</label>';
						$html += '<div class="controls">';
							$html += '<input class="span3" id="username" name="username" required type="email" value="eperez@tecnoregistro.com.mx" />';
						$html += '</div>';
					$html += '</div>';
					$html += '<div class="control-group">';
						$html += '<label class="control-label" for="password">Contraseña:</label>';
						$html += '<div class="controls">';
							$html += '<input class="span2" id="password" name="password" required type="password" />';
						$html += '</div>';
					$html += '</div>';
				$html += '</fieldset>';

				$html += '<div class="acciones-form">';
					$html += '<div class="form-error">&nbsp;</div>';
					$html += '<div>';
						$html += '<button class="btn btn-success" type="submit">';
							$html += 'Reimprimir <i class="icon-print icon-white"></i>';
						$html += '</button>';
					$html += '</div>';
				$html += '</div>';

			$html += '</form>';
		$html += '</div>';

		$dialog = $("<div />", {
			id: "alerta-reimpresion",
			title: "Reimpresión de gafate",
			html: $html
		});

		$dialog.dialog({
			close: function () {
				$(this).remove();
			},
			modal: true,
			resizable: false,
			width: 500
		});
	};

	reimpresion = function(sRes) {
		if (typeof sRes.seccion != "undefined") {
			seccion = sRes.seccion;
			$("#formImpresion").attr("action", seccion);
			$("#formImpresion").submit();
			setTimeout(function() {
				$("#alerta-reimpresion").dialog("close");
			}, 3000);
		}
	}

	eliminarFotografia = function(sRes) {
		seccion = sRes.seccion;
		$('#delete-photo-saved').remove();
		$('#module-photo').children('a').show();
	};

	errorEliminarFotografia = function(sRes) {
		$('#cuerpo').append(
			'<div class="modal-general" title="Error!">' +
				'<p>' + sRes.mensaje + '</p>' +
				'<button class="btn btn-success" id="dialog-error-foto">' +
					'Aceptar' +
				'</button>' +
			'</div>'
		);

		$('.modal-general').dialog({
			close: function() {
				$(this).remove();
			},
			modal: true,
			resizable: false
		});

		$('#dialog-error-foto').click(function() {
			$('.modal-general').dialog('close');
		});
	};

	formAgregarHospedaje = function(sRes) {
		var $seccion = sRes.seccion;
		$hoteles = sRes.hoteles;
		formasPago = sRes.formasPago;
		statusRes = sRes.statusRes;

		$fechaInicio = sRes.evento.evt_inicio;
		$fechaTermina = sRes.evento.evt_termina;
		$inicia = $fechaInicio.split('/');
		$termina = $fechaTermina.split('/');

		$form = '<form action="' + $seccion + '/?action=validarReservacion" data-load="ajax" id="validar-reservacion" method="post" name="validar_reservacion">';
		$form += '<input type="hidden" id="maxAdultos" name="maxAdultos" value="0" />';
		$form += '<input type="hidden" id="maxMenores" name="maxMenores" value="0" />';

			$form += '<fieldset class="horizontal">';
				$form += '<div class="control-group">';
					$form += '<label class="control-label" for="res_idHotel">Elija un hotel:</label>';
					$form += '<div class="controls">';
						$form += '<select id="res_idHotel" name="res_idHotel">';
							$form += '<option value="">Seleccione:</option>';

						for (hotel in $hoteles) {
							$form += '<option value="' + $hoteles[hotel].htl_idHotel + '">' + $hoteles[hotel].htl_nombre + '</option>';
						}

						$form += '</select>';
					$form += '</div>';
				$form += '</div>';

				$form += '<div class="control-group">';
					$form += '<label class="control-label" for="res_idHabitacion">Elija el tipo de habitación:</label>';
					$form += '<div class="controls">';
						$form += '<select id="res_idHabitacion" name="res_idHabitacion">';
							$form += '<option value="">Seleccione:</option>';
						$form += '</select>';
					$form += '</div>';
				$form += '</div>';
			$form += '</fieldset>';

			$form += '<fieldset class="horizontal">';
				$form += '<div class="control-group">';
					$form += '<label class="control-label">Costo por noche:</label>';
					$form += '<div class="controls" id="costos-habitaciones">0.00';
					$form += '</div>';
				$form += '</div>';
				$form += '<div class="control-group">';
					$form += '<label class="control-label" for="res_llegada">Fecha de llegada:</label>';
					$form += '<div class="controls">';
						$form += '<input class="input-small" id="res_llegada" name="res_llegada" readonly type="text" value="' + $fechaInicio + '" />';
					$form += '</div>';
				$form += '</div>';

				$form += '<div class="control-group">';
					$form += '<label class="control-label" for="res_salida">Fecha de salida:</label>';
					$form += '<div class="controls">';
						$form += '<input class="input-small" id="res_salida" name="res_salida" readonly type="text" value="' + $fechaTermina + '" />';
					$form += '</div>';
				$form += '</div>';
			$form += '</fieldset>';

			$form += '<fieldset class="horizontal">';
				$form += '<div class="control-group">';
					$form += '<label class="control-label" for="res_numHabitaciones">Número de cuartos:</label>';
					$form += '<div class="controls">'
						$form += '<select class="span2" id="res_numHabitaciones" name="res_numHabitaciones">';
							$form += '<option value="">Seleccione:</option>';
						$form += '</select>';
					$form += '</div>';
				$form += '</div>';

				$form += '<div class="control-group">';
					$form += '<label class="control-label titulo-pax-cuartos">Ocupación <strong>Adultos</strong>&nbsp;<strong>Menores</strong></label>';
					$form += '<div class="controls" id="pax-habitaciones">';
						$form += '&nbsp;';
					$form += '</div>';
				$form += '</div>';
			$form += '</fieldset>';

			$form += '<fieldset class="horizontal">';
				$form += '<div class="control-group">';
					$form += '<label class="control-label" for="res_numHabitaciones">Anticipo:</label>';
					$form += '<div class="controls">'
						$form += '<input class="span2" name="res_anticipo" type="text" placeholder="0.00" />';
					$form += '</div>';
				$form += '</div>';
				$form += '<div class="control-group">';
					$form += '<label class="control-label" for="res_formaPago">Forma de pago:</label>';
					$form += '<div class="controls">'
						$form += '<select class="span2" name="res_formaPago" id="res_formaPago">';
							$form += '<option value="">Seleccione:</option>';

							for (i in formasPago) {
								$form += '<option value="' + formasPago[i].fpn_clave + '">' + formasPago[i].fpn_nombre + '</option>';
							}

						$form += '</select>';
					$form += '</div>';
				$form += '</div>';
				$form += '<div class="control-group">';
					$form += '<label class="control-label" for="res_status">Status:</label>';
					$form += '<div class="controls">';
						$form += '<select class="span2" name="res_status" id="res_status">';
							$form += '<option value="">Seleccione:</option>';

							for (i in statusRes) {
								$form += '<option value="' + statusRes[i].esr_clave + '">' + statusRes[i].esr_nombre + '</option>';
							}

						$form += '</select>';
					$form += '</div>';
				$form += '</div>';
			$form += '</fieldset>';

			$form += '<div class="form-actions">';
				$form += '<div class="form-error">&nbsp;</div>';
				$form += '<button class="btn" type="submit">';
					$form += 'Continuar <i class="icon-arrow-right"></i>';
				$form += '</button>';
			$form += '</div>';

		$form += '</form>';

		$dialog = $('<div />', {
			id: 'reg-hospedaje',
			title: 'Hospedaje',
			html: $form
		});

		$dialog.dialog({
			autoOpen: true,
			close: function() {
				$(this).remove();
			},
			modal: true,
			resizable: false,
			width: '600',
			height: 'auto'
		});

		// Fecha de llegada
		$('#res_llegada').datepicker({
			minDate: new Date($inicia[2], $inicia[1] - 1, $inicia[0] - 1),
			maxDate: new Date($termina[2], $termina[1] - 1, parseInt($termina[0]) + 1),
			beforeShow: function (e) {
				$llegada = $('#res_salida').val();

				if ($llegada) {
					var $day = $llegada.split('/');

					$(e).datepicker(
						'option', 'maxDate', new Date($day[2], $day[1] - 1, parseInt($day[0]) - 1)
					);
				}
			}
		});

		// Fecha de salida
		$('#res_salida').datepicker({
			minDate: new Date($inicia[2], $inicia[1] - 1, $inicia[0] - 1),
			maxDate: new Date($termina[2], $termina[1] - 1, parseInt($termina[0]) + 1),
			beforeShow: function (e) {
				$llegada = $('#res_llegada').val();

				if ($llegada) {
					var $day = $llegada.split('/');

					$(e).datepicker(
						'option', 'minDate', new Date($day[2], $day[1] - 1, parseInt($day[0]) + 1)
					);
				}
			}
		});

		// Obtener habitaciones del hotel
		$('#res_idHotel').change(function() {
			$.ajax({
				type: 'POST',
				url: $seccion + '/?action=getHabitaciones',
				dataType: 'json',
				data: {idHotel: $(this).val()},
				success: function(sRes) {
					habitaciones = sRes.habitaciones;
					$options = '<option value="">Seleccione:</option>';

					for (i in habitaciones) {
						$options += '<option value="' + habitaciones[i].hhn_idHabitacion + '">' + habitaciones[i].hhn_nombre + '</option>';
					}

					$('#res_idHabitacion').html($options);
				}
			});
		}); // Habitaciones del hotel

		// Obtener especificaciones de la habitación elegida
		$('#res_idHabitacion').change(function() {
			$.ajax({
				type: 'POST',
				url: $seccion + '/?action=getEspecHabitacion',
				dataType: 'json',
				data: {idHotel: $('#res_idHotel').val(), idHabitacion: $(this).val()},
				success: function(sRes) {
					costo = sRes.costo;
					cuartos = sRes.cuartos;
					$('#maxAdultos').val(sRes.maxAdultos);
					$('#maxMenores').val(sRes.maxMenores);

					$options = '<strong>' + costo + '</strong>';
					$optionsHab = '<option value="">Seleccione:</option>';

					/**
					 * Lista de costos
					for (i in costos) {
						$options += '<label class="radio">';
							$options += '<input id="res_idCostos" name="res_idCostos" type="radio" value="' + costos[i].hhc_id + '" />';
							$options += 'Adultos: ' + costos[i].hhc_costoAdulto;
							$options += ' - Menores: ' + costos[i].hhc_costoMenor;
						$options += '</label>';
					}*/

					//$options += '&nbsp;';

					if (cuartos > 0) {
						for ( i = 1 ; i <= cuartos ; i++ ) {
							$optionsHab += '<option value="' + i + '">' + i +'</option>';
						}
						$('#res_numHabitaciones').html($optionsHab);
					} else {
						$('#res_numHabitaciones').html('<option value="">Seleccione:</option>');
					}

					$('#costos-habitaciones').html($options);
				}
			});
		}); // Especificaciones de la habitacion elegida

		// Mostrar el número de cuartos a reservar
		$('body').on('change', '#res_numHabitaciones', function() {
			$habitaciones = $(this).val();
			maxAdultos = $('#maxAdultos').val();
			maxMenores = $('#maxMenores').val();
			$cuartos = '';

			for ( i = 1 ; i <= $habitaciones ; i++ ) {
				$cuartos += '<div class="pax-cuartos">';
						$cuartos += 'Cuarto ' + i + ': <select class="input-mini" name="cuarto[' + i + '][adultos]">';
						for ( j = 0 ; j <= maxAdultos ; j++) {
							$cuartos += '<option value="' + j + '">' + j + '</option>';
						}
						$cuartos += '</select>';

						$cuartos += '<select class="input-mini" name="cuarto[' + i + '][menores]">';
						for ( j = 0 ; j <= maxMenores ; j++) {
							$cuartos += '<option value="' + j + '">' + j + '</option>';
						}
						$cuartos += '</select>';
				$cuartos += '</div>';
			}

			$('#pax-habitaciones').html($cuartos);
		}); // Número de habitaciones a reservar
	}

	finAgregarReservacion = function() {
		alert('Reservacion exitosa!');
	};

	/* Buscar tag */
	$('body').on({
		keyup: function() {
			var $input = $(this),
				$boton = $('#actualizarRegistro');
			$.get('home/registros/?action=buscarTag', {id_tag: $input.val()}, function(sRes) {
				if (sRes.status) {
					$boton.attr('disabled', 'disabled');
					$input.parent().next().text('Otro registro ya tiene este ID TAG');
				} else {
					$boton.removeAttr('disabled');
					$input.parent().next().text('');
				}
			}, 'json');
		}
	}, '#id_tag');

	/* Buscar tag */
	$('body').on({
		keyup: function() {
			var $input = $('#lectura_pistola'),
				$boton = $('#btn-lectura');
			$.get('home/registros/?action=buscarTagOtro', {id_tag: $input.val()}, function(sRes) {
				if (sRes.status) {
					$boton.attr('disabled', 'disabled');
					$('#lecturaImpresion').find('.form-error').text('Otro registro ya tiene este ID TAG');
				} else {
					$boton.removeAttr('disabled');
					$('#lecturaImpresion').find('.form-error').text('');
				}
			}, 'json');
		}
	}, '#lectura_pistola');
});