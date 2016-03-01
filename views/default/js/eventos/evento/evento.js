/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/{layout}/js/eventos/evento/
 * @version $Id: evento.js 1.0 2012-04-12 01:00 Daniel $;
 * @author: Daniel
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
$(function() {

	/**
	 * Marcamos activa la seccion indicada
	 * .nav-pills a[data-load="seccion"]
	 */
	$('body').on('click', '.nav a[data-load="seccion"]', function(event) {
		event.preventDefault();

		var $target = $(this),
			$active = $(".nav li"),
			$targets = $active.children("a");

		$active.removeClass("active");

		$target.parent().addClass("active");

		//return false;
	});

	// Editar divisa
	$('a[data-divisa]').click(function(event) {
		event.preventDefault();

		var $target = $(this),
			$uri = $target.attr("href"),
			$campo = $target.data("divisa"),
			$target = $target.parent().prev(),
			$precio = $target.text();

		// Removemos los formularios
		$(".tipoCambioDivisa").find("form").remove();

		if (!$target.has(".editarDivisa").length) {

			$form = '<form action="' + $uri + '" class="editarDivisa" id="f-d-' + $campo + '" data-load="ajax" method="post" name="cambioDivisa">' +
				'<input class="input-mini" id="' + $campo + '" name="' + $campo + '" type="text" value="' + $.trim($precio) + '" placeholder="0.00" />' +
				'<input style="display: none;" type="submit" value="enviar" />'
			'</form>';

			$target.append($form);

			$("#" + $campo).focus();

			// Cancelar edición
			$('a[data-divisa="cancelar"]').click(function() {
				$(this).parent().remove();
				return false;
			});

		} else {
			$("#" + $campo).focus();
		}

		$("#" + $campo).blur(function() {
			$("#f-d-" + $campo).remove();
		});
	});

	// Función fin edición divisa
	editarDivisa = function(sRes) {

		$("#f-d-" + sRes.campo).parent().html(sRes.valor);
	};

	// Función edición divisa
	errorActualizarDivisa = function() {

		alert("error");
	};

	/**
	 * Función que agregar una categoria
	 */
	nuevaCategoria = function(sRes) {
		$.ajax({
			type: "GET",
			url: sRes.seccion,
			dataType: "json",
			beforeSend: function() {
				$(".modal-general").dialog("close");
			},
			success: function(sRes) {

				categorias = sRes.categorias;
				seccion = sRes.seccion;
				$trs = "";

				for (i in categorias) {

					$trs += '<tr id="' + categorias[i].ecc_clave + '">' +
						'<td>' + categorias[i].ecc_clave + '</td>' +
						'<td class="nombres-categorias ' + categorias[i].ecc_clave + '">' +
							'<div class="accion-agregar-categoria">' +
								'<a class="btn btn-mini btn-warning" data-load="modal" href="' + seccion + '/?action=formAltaNombreCategoria&amp;cve=' + categorias[i].ecc_clave + '&amp;id=' + categorias[i].idNombre + '">' +
									'<i class="icon-plus-sign icon-white"></i>' +
								'</a>' +
							'</div>';

					// Recorremos los nombres de las categorias
					for (j in categorias[i].nombres) {
						$trs += '<div id="' + categorias[i].ecc_clave + '-' + categorias[i].nombres[j].enc_idNombreCategoria + '" class="nombre-categoria">' +
							'<span>' + categorias[i].nombres[j].enc_nombre + '</span>' +
							' <div class="acciones-nombre-categoria">' +
								'<a class="btn btn-mini btn-danger" data-load="accion" href="' + seccion + '/?action=confEliminarNombreCategoria&amp;cve=' + categorias[i].nombres[j].enc_clave + '&amp;id=' + categorias[i].nombres[j].enc_idNombreCategoria + '" title="Eliminar nombre">' +
									'<i class="icon-trash icon-white"></i>' +
								'</a>' +
							'</div>' +
						'</div>';
					}

					$trs += '</td>' +
						'<td>' + categorias[i].ecc_costo_fecha_1 + '</td>' +
						'<td>' + categorias[i].ecc_costo_fecha_2 + '</td>' +
						'<td>' + categorias[i].ecc_costo_fecha_3 + '</td>' +
						'<td>' + categorias[i].ecc_costo_sitio + '</td>' +
						'<td>' + categorias[i].ecc_costo_otro + '</td>' +
						'<th>' +
							'<a class="btn btn-mini" data-load="modal" href="' + seccion + '/?action=formEditarCategoria&amp;cve=' + categorias[i].ecc_clave + '" title="Editar la categoria">' +
								'<i class="icon-edit"></i>' +
							'</a> ' +
							'<a class="btn btn-mini btn-danger" data-load="accion" href="' + seccion + '/?action=confEliminarCategoria&amp;cve=' + categorias[i].ecc_clave + '" title="Eliminar la categoria">' +
								'<i class="icon-trash icon-white"></i>' +
							'</a>' +
						'</th>' +
					'</tr>';

				}

				$(".catCategorias table tbody").html($trs);

			}
		});
	};

	/**
	 * Función que actualiza los datos generales del evento
	 */
	actualizarDatosGeneralesEvento = function(sRes) {
		evento = sRes.evento; // asignación del evento

		$("#nombre-evento").html(evento.evt_nombre);

		$ths = $(".detalles table tbody tr th");

		$ths.eq(0).html(evento.evt_clave);
		$ths.eq(1).html(evento.tipoEvento);
		$ths.eq(2).html(evento.evt_divisa);
		$ths.eq(3).html(evento.evt_iva);
		$ths.eq(4).html(evento.evt_inicio);
		$ths.eq(5).html(evento.evt_termina);
		$ths.eq(6).html(evento.statusEvento);

		$("#divisa").html(evento.evt_divisa);

		$(".modal-general").dialog("close");
	};

	/**
	 * Función que actualiza las observaciones del evento
	 */
	actualizarObservacionesEvento = function(sRes) {
		$(".observaciones > div").html(sRes.observaciones);
		$(".modal-general").dialog("close");
	};

	/**
	 * Función error eliminar categoria
	 */
	errorEliminarCategoria = function(sRes) {
		$div = $("<div />", {
			"class": "modal-general",
			title: "Error al eliminar la categoria",
			html: '<span class="alertas">' + sRes.mensaje + '</span>'
		});

		$div.dialog({
			buttons: {
				"Aceptar": function() {
					$(this).dialog("close");
				}
			},
			close: function() {
				$(this).remove();
			},
			modal: true,
			resizable: false
		});
	};

	/**
	 * Función que muestra una confirmación para eliminar el nombre de una categoria
	 */
	confEliminarCategoria = function(sRes) {
		categoria = sRes.categoria;
		seccion = sRes.seccion;
		mensaje = sRes.mensaje;

		var $html = '<form action="' + seccion + '" method="post" data-load="ajax" name="eliminarCategoria" data-validate="false">' +
			'<span class="alertas">' + mensaje + '</span>' +
			'<input name="ecc_clave" type="hidden" value="' + categoria + '" />' +
			'<div class="acciones-form">' +
				'<div class="form-error">&nbsp;</div>' +
				'<input class="btn btn-primary" type="submit" value="Aceptar" />' +
			'</div>' +
		'</form>';

		$form = $("<div />", {
			"class": "modal-general",
			title: "Eliminar categoria",
			html: $html
		});

		$form.dialog({
			close: function() {
				$(this).remove();
			},
			modal: true,
			resizable: false
		});
	};

	/**
	 * Función que elimina una categoria
	 */
	eliminarCategoria = function(sRes) {
		$categoria = sRes.categoria;
		$("tr#" + $categoria).fadeOut("slow");
		$(".modal-general").dialog("close");
	};

	/**
	 * Función que muestra una confirmación para eliminar el nombre de una categoria
	 */
	confEliminarNombreCategoria = function(sRes) {
		$nomCategoria = sRes.nombreCategoria;
		seccion = sRes.seccion;
		mensaje = sRes.mensaje;

		var $html = '<form action="' + seccion + '" data-load="ajax" method="post" name="eliminarNombreCategoria" data-validate="false">' +
			'<span class="alertas">' + mensaje + '</span>' +
			'<input name="enc_clave" type="hidden" value="' + $nomCategoria.cve + '" />' +
			'<input name="enc_idNombreCategoria" type="hidden" value="' + $nomCategoria.id + '" />' +
			'<div class="acciones-form">' +
				'<div class="form-error">&nbsp;</div>' +
				'<input class="btn btn-primary" type="submit" value="Aceptar" />' +
			'</div>' +
		'</form>';

		$form = $("<div />", {
			"class": "modal-general",
			title: "Eliminar nombre de la categoria",
			html: $html
		});

		$form.dialog({
			close: function() {
				$(this).remove();
			},
			modal: true,
			resizable: false
		});
	};

	/**
	 * Función que elimina el nombre de una categoria
	 */
	eliminarNombreCategoria = function(sRes) {

		nuevaCategoria(sRes);
	};

	altaIdioma = function(sRes) {
		var $idioma = sRes.idioma,
			$seccion = sRes.seccion,
			$tbody = $(".idiomas table tbody");

		$tr = '<tr id="idioma-' + $idioma.eis_idioma + '">' +
			'<th>' + $idioma.eis_idioma + '</th>' +
			'<th>' + $idioma.eis_nombre + '</th>' +
			'<th>' +
				'<a class="btn btn-mini btn-danger" data-load="accion" href="' + $seccion + '/?action=confEliminarIdioma&amp;cve=' + $idioma.eis_idioma + '" title="Eliminar idioma">' +
					'<i class="icon-trash icon-white"></i>' +
				'</a>' +
			'</th>' +
		'</tr>';

		$tbody.append($tr);

		$(".modal-general").dialog("close");
	};

	/**
	 * Función que muestra una confirmación para eliminar idioma
	 */
	confEliminarNombreIdioma = function(sRes) {
		$clave = sRes.idioma;
		seccion = sRes.seccion;
		mensaje = sRes.mensaje;

		var $html = '<form action="' + seccion + '" data-load="ajax" method="post" name="eliminarIdioma" data-validate="false">' +
			'<span class="alertas">' + mensaje + '</span>' +
			'<input name="eis_idioma" type="hidden" value="' + $clave + '" />' +
			'<div class="acciones-form">' +
				'<div class="form-error">&nbsp;</div>' +
				'<input class="btn btn-primary" type="submit" value="Aceptar" />' +
			'</div>' +
		'</form>';

		$form = $("<div />", {
			"class": "modal-general",
			title: "Eliminacion de idioma",
			html: $html
		});

		$form.dialog({
			close: function() {
				$(this).remove();
			},
			modal: true,
			resizable: false
		});
	};

	/**
	 * Función que elimina el idioma
	 */
	eliminarIdioma = function(sRes) {
		$idioma = sRes.idioma;
		$("tr#idioma-" + $idioma).hide();
		$(".modal-general").dialog("close");
	};

	/**
	 * Agregar titulo
	 */
	$("body").on({
		click: function() {
			$("#nuevo-titulo").slideDown("fast");
			$("#ect_clave").focus();
			return false;
		}
	}, 'a[href="#nuevoTitulo"]');

	/**
	 * Cancelar agregar titulo
	 */
	$("body").on({
		click: function() {
			$("#nuevo-titulo").slideUp("fast");
		}
	}, '#cancelar-nuevo-titulo');

	/**
	 * Función que agrega el titulo
	 */
	altaTitulo = function(sRes) {
		$titulo = sRes.titulo;

		if ($("#no-titulos").length == 1) {
			$("#no-titulos").remove();
		}

		$("#ect_clave, #ect_nombre, #ect_idioma").val('');

		$tbody = $("#lista-titulos table tbody");

		$tr = '<tr id="titulo-' + $titulo.ect_idTitulo + '">' +
			'<td>' + $titulo.ect_clave + '</td>' +
			'<td>' + $titulo.ect_nombre + '</td>' +
			'<th>' + $titulo.ect_idioma + '</th>' +
			'<th>' +
				'<a class="btn btn-mini btn-danger" data-load="accion" href="' + sRes.seccion + '/titulos?action=eliminar&amp;id=' + $titulo.ect_idTitulo + '">' +
					'<i class="icon-trash icon-white"></i>' +
				'</a>' +
			'</th>' +
		'</tr>';

		$tbody.append($tr);

		idNuevoTitulo = parseInt($titulo.ect_idTitulo) + 1;
		$("#ect_idTitulo").val(idNuevoTitulo);

		$("#nuevo-titulo").slideUp("slow");
	};

	/**
	 * Función que elimina el titulo
	 */
	eliminarTitulo = function(sRes) {
		$id = "#titulo-" + sRes.id;

		$($id).remove();
	};

	/**
	 * Agregar nuevo status para registros
	 */
	$("body").on({
		click: function() {
			$("#nuevo-status-registro").slideDown("fast");
			$("#esr_clave").focus();
			return false;
		}
	}, 'a[href="#nuevoStatusReg"]');

	/**
	 * Cancelar agregar status para registros
	 */
	$("body").on({
		click: function() {
			$("#nuevo-status-registro").slideUp("fast");
		}
	}, '#cancelar-nuevo-statusReg');

	/**
	 * Función que agrega el titulo
	 */
	altaStatusReg = function(sRes) {
		$statusReg = sRes.statusReg;

		if ($("#no-statusReg").length == 1) {
			$("#no-statusReg").remove();
		}

		$("#esr_clave, #esr_nombre").val('');

		$tbody = $("#status-registros table tbody");

		$tr = '<tr id="statusReg-' + $statusReg.esr_idStatus + '">' +
			'<td>' + $statusReg.esr_clave + '</td>' +
			'<td>' + $statusReg.esr_nombre + '</td>' +
			'<th>' +
				'<a class="btn btn-mini editarStatusReg" href="' + sRes.seccion + '/statusRegistros?action=editar&amp;id=' + $statusReg.esr_idStatus + '">' +
					'<i class="icon-edit"></i>' +
				'</a> ' +
				'<a class="btn btn-mini btn-danger" data-load="accion" href="' + sRes.seccion + '/statusRegistros?action=eliminar&amp;id=' + $statusReg.esr_idStatus + '">' +
					'<i class="icon-trash icon-white"></i>' +
				'</a>' +
			'</th>' +
		'</tr>';

		$tbody.append($tr);

		idNuevostatusReg = parseInt($statusReg.esr_idStatus) + 1;
		$("#esr_idStatus").val(idNuevostatusReg);

		$("#nuevo-status-registro").slideUp("slow");
	};

	/**
	 * Función que elimina el status de registro
	 */
	eliminarStatusReg = function(sRes) {
		$id = "#statusReg-" + sRes.id;

		$($id).remove();
	};

	/**
	 * Función que muestra un error al tratar de eliminar un status
	 */
	errorEliminarStatusReg = function(sRes) {
		$(".alert-error").html(sRes.mensaje).slideDown("slow").delay(2500).fadeOut("slow");
	};

	/**
	 * Editar Status de registro
	 */
	$( "body" ).on({
		click: function(event) {

			event.preventDefault();

			$("#editarStatusReg").remove();

			$target = $(this);
			$url = $target.attr("href");
			$td = $target.parent().prev();
			$nombre = $td.text();

			$form = '<form id="editarStatusReg" data-load="ajax" action="' + $url + '" method="post" name="editarStatusReg">' +
					'<div class="control-group">' +
						'<div class="controls">' +
							'<div class="input-append">' +
								'<input class="span2" id="esr_nombre_nuevo" name="esr_nombre_nuevo" type="text" value="' + $nombre + '" />' +
								'<button class="btn" type="submit"><i class="icon-ok-sign"></i></button>' +
								'<button class="btn c-esr" type="button"><i class="icon-remove-sign"></i></button>' +
							'</div>' +
						'</div>' +
					'</div>' +
					'<div class="form-error">&nbsp;</div>' +
				'</form>';

			$td.append($form);

			$("#esr_nombre_nuevo").focus();

			$(".c-esr").click(function() {
				$("#editarStatusReg").remove();
				return false;
			});

		}
	}, ".editarStatusReg");

	/**
	 * Funcion que finaliza la edicion del Status de registro
	 */ 
	actualizarStatusReg = function(sRes) {
		statusReg = sRes.statusReg;

		$("#statusReg-" + statusReg.id + " td:eq(1)").text(statusReg.nombre);

		$("#editarStatusReg").remove();
	};

	/**
	 * Función que agrega una nueva forma de pago
	 */
	altaFormaPago = function(sRes) {
		formaPago = sRes.formaPago;
		seccion = sRes.seccion;

		$tbody = $(".formas-pago table tbody");

		$tr = '<tr id="formaPago-' + formaPago.efp_clave + '">' +
			'<td>' + formaPago.efp_clave + '</td>' +
			'<td>' +
				'<div class="nuevo-nombre-formaPago">' +
					'<a class="btn btn-mini btn-warning" data-load="accion" href="' + seccion + '/formasPago?action=formAgregarNombre&cve=' + formaPago.efp_clave + '&id=1" title="Agregar nombre">' +
						'<i class="icon-plus-sign icon-white"></i>' +
					'</a>' +
				'</div>' +
			'</td>' +
			'<th>' +
				'<a class="btn btn-mini btn-danger" data-load="accion" href="' + seccion + '/formasPago?action=eliminar&amp;cve=' + formaPago.efp_clave + '">' +
					'<i class="icon-trash icon-white"></i>' +
				'</a>' +
			'</th>' +
		'</tr>';

		$tbody.append($tr);

		$("#efp_clave").val('');
	};

	/**
	 * Función que elimina una forma de pago
	 */
	eliminarFormaPago = function(sRes) {
		formaPago = sRes.formaPago;

		$("#formaPago-" + formaPago).remove();
	};

	/**
	 * Función que muestra el formulario para agregar nombre de forma de pago
	 */
	formAgregarNombreFormaPago = function(sRes) {
		seccion = sRes.seccion;
		clave = sRes.clave;
		idNombre = sRes.idNombre;
		idiomas = sRes.idiomas;

		$html = '<form action="' + seccion + '/formasPago?action=agregarNombre" data-load="ajax" method="post" name="agregarNombreFormaPago">' +
			'<input id="fpn_clave" name="fpn_clave" type="hidden" value="' + clave + '" />' +
			'<input id="fpn_idNombre" name="fpn_idNombre" type="hidden" value="' + idNombre + '" />' +
			'<div class="control-group">' +
				'<label class="control-label" for="fpn_nombre">Nombre:</label>' +
				'<div class="controls">' +
					'<input class="span2" id="fpn_nombre" name="fpn_nombre" type="text" />' +
				'</div>' +
			'</div>' +
			'<div class="control-group">' +
				'<label class="control-label" for="fpn_idioma">Idioma:</label>' +
				'<div class="controls">' +
					'<select id="fpn_idioma" name="fpn_idioma">' +
					idiomas +
					'</select>' +
				'</div>' +
			'</div>' +
			'<div class="acciones-form">' +
				'<div class="form-error">&nbsp;</div>' +
				'<input class="btn btn-success" type="submit" value="Agregar" />'
			'</div>' +
			'</form>';


		$modal = $("<div />", {
			id: "nuevo-nombre-formaPago",
			title: "Alta de nuevo nombre",
			html: $html
		});

		$modal.dialog({
			close: function() {
				$(this).remove();
			},
			modal: true,
			resizable: false,
		});
	};

	/**
	 * Función que finaliza la alta del nombre de la forma de pago
	 */
	finaAltaNombreFormaPago = function(sRes) {
		formaPago = sRes.formaPago;
		seccion = sRes.seccion;

		$tr = $("#formaPago-" + formaPago.fpn_clave + " td:eq(1)");
		$target = $("#formaPago-" + formaPago.fpn_clave + " td:eq(1) div:eq(0) a");

		$nuevaFormaPago = '<div class="nombre-formaPago" id="nombre-' + formaPago.fpn_clave + '-' + formaPago.fpn_idNombre + '">' +
			'<span>' + formaPago.fpn_nombre + '</span> ' +
			'<span class="eliminar-nombre-formaPago">' +
				'<a class="btn btn-mini btn-danger" data-load="accion" href="' + seccion + '/formasPago?action=eliminarNombre&amp;cve=' + formaPago.fpn_clave + '&amp;id=' + formaPago.fpn_idNombre + '" title="Eliminar este nombre">' +
					'<i class="icon-trash icon-white"></i>' +
				'</a>' +
			'</span>' +
		'</div>';

		$tr.append($nuevaFormaPago);

		$target.attr("href" , seccion + '/formasPago?action=formAgregarNombre&cve=' + formaPago.fpn_clave + '&id=' + formaPago.fpn_nuevoID);

		$("#nuevo-nombre-formaPago").dialog("close");
	};

	/**
	 * Función que elimina el nombre de la forma de pago
	 */
	eliminarNombreFormaPago = function(sRes) {
		nombreFormaPago = sRes.nombreFormaPago;

		$("#nombre-" + nombreFormaPago.cve + "-" + nombreFormaPago.id).remove();
	};

	$("body").on({
		click: function() {
			$(this).datepicker({
				showOn: 'focus',
				changeMonth: true,
				changeYear: true
			}).focus();
		}
	}, '#eci_fecha_1, #eci_fecha_2, #eci_fecha_3, #eci_fecha_1_a, #eci_fecha_2_a, #eci_fecha_3_a');

	/**
	 * Agregar item
	 */
	$("body").on({
		click: function(event) {
			event.preventDefault();
			$("#form-alta-item").slideDown("slow");
			$("#ecc_clave").focus();
		}
	}, 'a[href="#nuevo-item"]');

	/**
	 * Cancelar nuevo item
	 */
	$("body").on({
		click: function() {
			$("#form-alta-item").slideUp("fast");
		}
	}, '.cancelar-nuevo-item');

	/**
	 * Función que agrega un item
	 */
	nuevaItem = function(sRes) {
		item = sRes.item;
		seccion = sRes.seccion;

		$trs = '<tr id="' + item.eci_clave + '">' +
			'<td>' + item.eci_clave + '</td>' +
			'<td class="nombres-items ' + item.eci_clave + '">' +
				'<div class="agregar-nombre-item">' +
					'<a class="btn btn-mini btn-warning" data-load="accion" href="' + seccion + '/items?action=formAltaNombreItem&amp;cve=' + item.eci_clave + '" title="Agregar nombre al item">' +
						'<i class="icon-plus-sign icon-white"></i>' +
					'</a>' +
				'</div>' +
			'</td>' +
			'<td>' +
				'<div>' + item.eci_costo_fecha_1 + '</div>' +
				'<div class="fechaLimite">' + item.eci_fecha_1 + '</div>' +
			'</td>' +
			'<td>' +
				'<div>' + item.eci_costo_fecha_2 + '</div>' +
				'<div class="fechaLimite">' + item.eci_fecha_2 + '</div>' +
			'</td>' +
			'<td>' +
				'<div>' + item.eci_costo_fecha_3 + '</div>' +
				'<div class="fechaLimite">' + item.eci_fecha_3 + '</div>' +
			'</td>' +
			'<td>' + item.eci_costo_sitio + '</td>' +
			'<td>' + item.eci_costo_otro + '</td>' +
			'<td>' + item.eci_paxMaximos + '</td>' +
			'<td>' +
				'<a class="btn btn-mini" data-load="accion" href="' + seccion + '/items?action=formEditarItem&amp;cve=' + item.eci_clave + '" title="Editar el item">' +
					'<i class="icon-edit"></i>' +
				'</a> ' +
				'<a class="btn btn-mini btn-danger eliminar-item" href="' + seccion + '/items?action=eliminarItem" data-cve-item="' + item.eci_clave + '" title="Eliminar el item">' +
					'<i class="icon-trash icon-white"></i>' +
				'</a>' +
			'</td>' +
		'</tr>';

		$(".catItems").append($trs);
		$("#form-alta-item").slideUp("slow");
	};

	/**
	 * Funcion que muestra el formulario de alta para los nombres
	 * de items
	 */
	formAltaNombreItem = function(sRes) {
		seccion = sRes.seccion;
		idiomas = sRes.idiomas;
		idiomasExs = sRes.idiomasExs;

		$options = '<option value="">Seleccione:</option>';
		for (i in idiomas) {
			$options += '<option value="' + idiomas[i].eis_idioma + '"';
			for (var j = 0 ; j <= idiomasExs.length ; j++) {
				if (idiomasExs[j] != undefined) {
					$options += (idiomasExs[j].eni_idioma == idiomas[i].eis_idioma) ? ' disabled' : '';
				}
			}
			$options += '>' + idiomas[i].eis_nombre + '</option>';
		}

		$html = '<form action="' + seccion + '/items?action=agregarNombre" data-load="ajax" method="post" name="nombreItem">' +
			'<input name="eni_clave" type="hidden" value="' + sRes.clave + '" />' +
			'<div class="control-group">' +
				'<label class="control-label" for="eni_idioma">Idioma:</label>' +
				'<div class="control">' +
					'<select id="eni_idioma" name="eni_idioma" required>' +
					$options +
					'</select>' +
				'</div>' +
			'</div>' +
			'<div class="control-group">' +
				'<label class="control-label" for="eni_nombre">Nombre:</label>' +
				'<div class="control">' +
					'<input class="span2" id="eni_nombre" name="eni_nombre" type="text" required />' +
				'</div>' +
			'</div>' +
			'<div class="acciones-form">' +
				'<div class="form-error">&nbsp;</div>' +
				'<div>' +
					'<input class="btn btn-success" type="submit" value="Enviar" />' +
				'</div>' +
			'</div>' +
		'<form>';

		$form = $("<div />", {
			id: "form-alta-nombre-item",
			title: "Alta de nombre para el item",
			html: $html
		});

		$form.dialog({
			close: function() {
				$form.remove();
			},
			height: 'auto',
			modal: true,
			resizable: false,
			width: 'auto'
		});
	};

	/**
	 * Función que agrega el nuevo item
	 */
	nuevoNombreItem = function(sRes) {
		idItem = sRes.idItem;
		item = sRes.item;
		seccion = sRes.seccion;

		$html = '<span>' + item.eni_nombre + '</span> ' +
			'<span class="acciones-nombre-item">' +
				'<a class="btn btn-mini btn-danger eliminar-nombre-item" href="' + seccion + '/items?action=eliminarNombre&cve=' + item.eni_clave + '&id=' + idItem + '" title="Eliminar nombre">' +
					'<i class="icon-trash icon-white"></i>' +
				'</a>' +
			'</span>';

		$item = $("<div />", {
			"class": "nombre-item",
			id: item.eni_clave + "-" + idItem,
			html: $html
		});

		$(".catItems ." + item.eni_clave).append($item);

		$("#form-alta-nombre-item").dialog("close");
	};

	/**
	 * Eliminar el nombre del item
	 */
	$("body").on('click', '.eliminar-nombre-item', function(event) {
		event.preventDefault();

		var $target = $(this),
			seccion = $target.attr("href");

		$.get(seccion, function(sRes) {
			if (sRes.status) {
				$target.parents(".nombre-item").remove();
			} else {
				alert(sRes.mensaje);
			}
		}, "json");
	});

	/**
	 * Función que muestra el formulario para la deción del item
	 */
	formEditarItem = function(sRes) {
		seccion = sRes.seccion;
		item = sRes.item;

		$form = '<form action="' + seccion + '/items?action=actualizarItem" data-load="ajax" id="form-editar-item" method="post" name="actualizarItem">' +
			'<input name="eci_clave_anterior" type="hidden" value="' + item.eci_clave + '" />' +
			'<fieldset class="horizontal">' +
				'<div class="control-group">' +
					'<label class="control-label" for="eci_clave_a">Clave:</label>' +
					'<div class="controls">' +
						'<input class="input-small" id="eci_clave_a" name="eci_clave_a" type="text" value="' + item.eci_clave + '" />' +
					'</div>' +
				'</div>' +
				'<div class="control-group">' +
					'<label class="control-label" for="eci_costo_fecha_1_a">Costo 1:</label>' +
					'<div class="controls">' +
						'<input class="input-small" id="eci_costo_fecha_1_a" name="eci_costo_fecha_1_a" placeholder="0.00" type="text" value="' + item.eci_costo_fecha_1 + '" />' +
					'</div>' +
				'</div>' +
				'<div class="control-group">' +
					'<label class="control-label" for="eci_fecha_1_a">1er Fecha limite:</label>' +
					'<div class="controls">' +
						'<input class="input-small" id="eci_fecha_1_a" name="eci_fecha_1_a" readonly value="' + item.eci_fecha_1 + '" />' +
					'</div>' +
				'</div>' +
				'<div class="control-group">' +
					'<label class="control-label" for="eci_costo_fecha_2_a">Costo 2:</label>' +
					'<div class="controls">' +
						'<input class="input-small" id="eci_costo_fecha_2_a" name="eci_costo_fecha_2_a" placeholder="0.00" type="text" value="' + item.eci_costo_fecha_2 + '" />' +
					'</div>' +
				'</div>' +
				'<div class="control-group">' +
					'<label class="control-label" for="eci_fecha_2_a">2er Fecha limite:</label>' +
					'<div class="controls">' +
						'<input class="input-small" id="eci_fecha_2_a" name="eci_fecha_2_a" readonly value="' + item.eci_fecha_2 + '" />' +
					'</div>' +
				'</div>' +
			'</fieldset>' +

			'<fieldset class="horizontal">' +
				'<div class="control-group">' +
					'<label class="control-label" for="eci_costo_fecha_3_a">Costo 3:</label>' +
					'<div class="controls">' +
						'<input class="input-small" id="eci_costo_fecha_3_a" name="eci_costo_fecha_3_a" placeholder="0.00" type="text" value="' + item.eci_costo_fecha_3 + '" />' +
					'</div>' +
				'</div>' +
				'<div class="control-group">' +
					'<label class="control-label" for="eci_fecha_3_a">3er Fecha limite:</label>' +
					'<div class="controls">' +
						'<input class="input-small" id="eci_fecha_3_a" name="eci_fecha_3_a" readonly value="' + item.eci_fecha_3 + '" />' +
					'</div>' +
				'</div>' +
				'<div class="control-group">' +
					'<label class="control-label" for="eci_costo_sitio_a">Costo en sitio:</label>' +
					'<div class="controls">' +
						'<input class="input-small" id="eci_costo_sitio_a" name="eci_costo_sitio_a" placeholder="0.00" type="text" value="' + item.eci_costo_sitio + '" />' +
					'</div>' +
				'</div>' +
				'<div class="control-group">' +
					'<label class="control-label" for="eci_costo_otro_a">Otro tipo de costo:</label>' +
					'<div class="controls">' +
						'<input class="input-small" id="eci_costo_otro_a" name="eci_costo_otro_a" placeholder="0.00" type="text" value="' + item.eci_costo_otro + '" />' +
					'</div>' +
				'</div>' +
				'<div class="control-group">' +
					'<label class="control-label" for="eci_paxMaximos_a">Pax maximos (0 cero = ilimitado):</label>' +
					'<div class="controls">' +
						'<input class="input-small" id="eci_paxMaximos_a" name="eci_paxMaximos_a" type="text" value="' + item.eci_paxMaximos + '" />' +
					'</div>' +
				'</div>' +
			'</fieldset>' +

			'<div class="acciones-form">' +
				'<div class="form-error">&nbsp;</div>' +
				'<div>' +
					'<button class="btn btn-primary" type="submit">Guardar</button> ' +
					'<button class="btn cancelar-editar-item" type="reset">Cancelar</button>' +
				'</div>' +
			'</div>' +
		'</form>';

		$("#form-alta-item").after($form);
		$("#form-editar-item").slideDown("fast");

		$(".cancelar-editar-item").click(function() {
			$("#form-editar-item").remove();
		});
	};

	/**
	 * Función que actualiza el item
	 */
	actualizarItem = function(sRes) {
		$item = sRes.item;
		seccion = sRes.seccion;

		$tr = $("#" + $item.eci_clave_anterior);
		$tds = $("td", $tr);

		$tds.eq(0).text($item.eci_clave_a);

		// Cambiamos la url para los nombres del item
		$(".agregar-nombre-item a", $tds.eq(1)).attr("href", seccion + "/items?action=formAltaNombreItem&cve=" + $item.eci_clave_a);

		// Cambiamos las urls de los nombres
		$(".eliminar-nombre-item", $tds.eq(1)).each(function(i, v) {
			uri = $(this).attr("href");
			uri = uri.replace($item.eci_clave_anterior, $item.eci_clave_a);	
			$(this).attr("href", uri);
		});

		// Cambiamos el ID del item
		$tr.attr("id", $item.eci_clave_a);
		// Cambiamos el class
		$tds.eq(1).removeClass($item.eci_clave_anterior).addClass($item.eci_clave_a);

		$("div:eq(0)", $tds.eq(2)).text($item.eci_costo_fecha_1_a);
		$("div:eq(1)", $tds.eq(2)).text($item.eci_fecha_1_a);

		$("div:eq(0)", $tds.eq(3)).text($item.eci_costo_fecha_2_a);
		$("div:eq(1)", $tds.eq(3)).text($item.eci_fecha_2_a);

		$("div:eq(0)", $tds.eq(4)).text($item.eci_costo_fecha_3_a);
		$("div:eq(1)", $tds.eq(4)).text($item.eci_fecha_3_a);

		$tds.eq(5).text($item.eci_costo_sitio_a);
		$tds.eq(6).text($item.eci_costo_otro_a);
		$tds.eq(7).text($item.eci_paxMaximos_a);

		// Cambiamos la url para la edición e iliminación del item
		$("a:eq(0)", $tds.eq(8)).attr("href", seccion + "/items?action=formEditarItem&cve=" + $item.eci_clave_a);
		$("a:eq(1)", $tds.eq(8)).attr("href", seccion + "/items?action=eliminarItem").data("cve-item", $item.eci_clave_a);

		$("#form-editar-item").remove();
	};

	/**
	 * Formulario de confirmación de eliminación de item
	 */
	$("body").on('click', ".eliminar-item", function(event) {
		event.preventDefault();

		$target = $(this);
		$uri = $target.attr("href");
		$item = $target.data("cve-item");

		$form = '<form action="' + $uri + '" data-load="ajax" method="post" name="eliminar-item">' +
			'<input id="eci_clave_e" name="eci_clave_e" type="hidden" value="' + $item + '" />' +
			'<span class="alertas">¿Realmente desea eliminar el item?</span>' +
			'<div class="acciones-form">' +
				'<div class="form-error">&nbsp;</div>' +
				'<input class="btn btn-primary" type="submit" value="Aceptar" />' +
			'</div>' +
		'</form>';

		$dialog = $("<div />", {
			id: "eliminar-item",
			title: "Eliminar item",
			html: $form
		});

		$dialog.dialog({
			close: function() {
				$(this).remove();
			},
			modal: true,
			width: 'auto',
			resizable: false
		});
	});

	/**
	 * Elimina el item eliminado
	 */	
	eliminarItem = function(sRes) {
		$item = sRes.item;
		$("#" + $item).fadeOut("slow");
		$("#eliminar-item").dialog("close");
	};

	reporteFotosPDF = function(sRes) {
		$fields = $('#validarFormRepFotosPDF').serialize();

		window.location.href = sRes.seccion + '/?action=reporteFotosPDF&' + $fields;
	};

});