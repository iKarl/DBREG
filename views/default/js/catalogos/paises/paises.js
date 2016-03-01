/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/{layout}/js/catalogos/paises/
 * @version $Id: paises.js 1.0 2012-02-27 23:05 Daniel $;
 * @author: Daniel
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
$(function() {

	cargarPaises = function(sRes) {

		var $paises = "";

		paises = sRes.paises;

		num = parseInt(sRes.num);

		var i = 1;
		for (var j in paises) {

			$paises += '<tr id="pais-' + paises[j].pais_idPais + '">' +
				'<td>' + (num + i) + '</td>' +
				'<th><img alt="" src="' + sRes.images + '/' + paises[j].pais_imagen + '" /></th>' +
				'<td>' + paises[j].pais_nombreEs + '</td>' +
				'<td>' + paises[j].pais_nombreEn + '</td>' +
				'<td>' + paises[j].pais_iso2 + '</td>' +
				'<td>' + paises[j].pais_iso3 + '</td>' +
				'<th><a class="btn btn-mini btn-warning" data-load="modal" data-method="post" href="' + sRes.seccion + '/?action=formEditar&id=' + paises[j].pais_idPais + '">Editar</a></th>' +
			'</tr>';

			i++;

		}

		$("#lista-paises tbody").html($paises);

		$("#mostrar-actividad").css("visibility", "hidden");

	};

	$("body").on({
		change: function() {
			$("#mostrar-actividad").css("visibility", "visible");
			$("form").submit();
		}
	}, "#pPagina");

	actualizarPais = function(sRes) {

		var $pais = sRes.pais,
			$tr = $("#pais-" + $pais.idPais);

			$tr.children().eq(1).html('<img src="' + $pais.imagen + '" />');
			$tr.children().eq(2).text($pais.nombreEs);
			$tr.children().eq(3).text($pais.nombreEn);
			$tr.children().eq(4).text($pais.iso2);
			$tr.children().eq(5).text($pais.iso3);

		$(".modal-general").dialog("close");

	};

});