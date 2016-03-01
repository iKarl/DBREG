/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/{layout}/js/
 * @version $Id: login.js 1.0 2011-04-06 21:15 Daniel $;
 * @author: Daniel
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
$(function() {

	$("#tec-login").submit(function(event) {

		event.preventDefault();

		var $form = $(this),
			uri = $form.attr("action"),
			qryStr = $form.serialize();

		$.ajax({
			type: "POST",
			url: uri,
			data: qryStr,
			dataType: "json",
			beforeSend: function() {
				$(".form-error").html("&nbsp;");
			},
			success: function(sRes) {

				if (sRes.status == true) {

					window.location.href = sRes.redirect;

				} else {

					$(".form-error").html(sRes.message);

					$("#password").val('').focus();

				}

			},
			error: function() {

				$(".form-error").html("Error de JavaScript");

			}
		});

	});

});