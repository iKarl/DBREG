/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/js/eventos/
 * @version $Id: eventos.js 1.0 2012-04-12 01:00 Daniel $;
 * @author: Daniel
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
$(function() {

	$("body").on({
		click: function() {
			$(this).datepicker({
				showOn: 'focus',
				changeMonth: true,
				changeYear: true
			}).focus();
		}
	}, '#evt_inicio');

	$("body").on({
		click: function() {
			$(this).datepicker({
				showOn: 'focus',
				changeMonth: true,
				changeYear: true
			}).focus();
		}
	}, '#evt_termina');

	altaEvento = function(sRes) {

		idEvento = sRes.idEvento;
		seccion = sRes.seccion;
		evento = sRes.evento;

		if ($("#no-eventos").length == 1) {
			$("#no-eventos").remove();
		}

		$tbody = $("#eventos table tbody");

		$tr = '<tr>' +
			'<td>' + evento.evt_clave + '</td>' +
			'<td>' +
				'<a data-load="seccion" data-opts=\'{"tools":{"css":true,"js":true,"file":"evento"}}\' data-method="post" href="' + seccion + '/evento/?id=' + idEvento + '">' + evento.evt_nombre + '</a>' +
			'</td>' +
			'<td>' + evento.tipoEvento + '</td>' +
			'<th>' + evento.evt_inicio + ' - ' + evento.evt_termina + '</th>' +
			'<th>' + evento.statusEvento + '</th>' +
		'</tr>';

		$tbody.append($tr);

		$(".modal-general").dialog("close");

	}

});