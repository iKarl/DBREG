/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/{layout}/js/catalogos/codPostales/
 * @version $Id: codPostales.js 1.0 2012-03-19 20:11 Daniel $;
 * @author: Daniel
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
$(function() {

	listaCodigosPostales = function(sRes) {

		var codigos = sRes.codigosPostales,
			$cps = "";

		i = 1;
		for (j in codigos) {

			$cps += '<tr id="cp-' + codigos[j].cp_idCodigoPostal + '">' +
				'<td>' + i + '</td>' +
				'<td>' + codigos[j].cp_codigoPostal + '</td>' +
				'<td>' + codigos[j].cp_asenta + '</td>' +
				'<td>' + codigos[j].cp_tipoAsenta + '</td>' +
				'<td>' + codigos[j].cp_nombreMnpio + '</td>' +
				'<td>' + codigos[j].cp_nombreEstado + '</td>' +
				'<td>' + codigos[j].cp_nombreCiudad + '</td>' +
				'<th><a class="btn btn-warning btn-mini" data-load="modal" href="' + sRes.seccion + '/?action=formEditar&id=' + codigos[j].cp_idCodigoPostal + '">Editar</a></th>' +
			'</tr>';

			i++;

		}

		$("#lista-cps tbody").html($cps);

	};

	noCodigosPostales = function(sRes) {

		$("#lista-cps tbody").html("<tr><th colspan='8'>" + sRes.mensaje + "</th></tr>");

	};

	actualizarCodigoPostal = function(sRes) {

		var $tds = $("#cp-" + sRes.cp.cp_idCodigoPostal + " td");

		cp = sRes.cp;

		$tds.eq(1).text(cp.cp_codigoPostal);
		$tds.eq(2).text(cp.cp_asenta);
		$tds.eq(3).text(cp.cp_tipoAsenta);
		$tds.eq(4).text(cp.cp_nombreMnpio);
		$tds.eq(5).text(cp.cp_nombreEstado);
		$tds.eq(6).text(cp.cp_nombreCiudad);

		$(".modal-general").dialog("close");

	};

});