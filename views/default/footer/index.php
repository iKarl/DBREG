{#
<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/header/
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * @version $Id: index.php 1.0 2011-06-09 23:01 _Karl_ $;
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}

			</div>

			<div class="slice"></div>
		</div>

		<footer>
			<span>Soft Manage Congress | All Right Reserverd by Tecnoregistro | &copy; 2011</span>
		</footer>

		<script src="{{ layout }}/js/jquery/jquery-1.8.1.min.js"></script>
		<script src="{{ layout }}/js/modernizr.js"></script>
		<script src="{{ layout }}/js/jquery/jquery-ui/jquery-ui-1.9.2.custom.min.js"></script>
		<script src="{{ layout }}/js/jquery/jquery-ui/i18n/jquery.ui.datepicker-es.js"></script>
		<script>var pathTools = "{{ layout }}";</script>
		<script src="{{ layout }}/js/bootstrap/bootstrap.min.old.js"></script>
		<script src="{{ layout }}/js/jquery-validation-1.10.0/jquery.metadata.js"></script>
		<script src="{{ layout }}/js/jquery-validation-1.10.0/jquery.validate.min.js"></script>
		<script src="{{ layout }}/js/jquery-validation-1.10.0/localization/messages_es.js"></script>
		<script src="{{ layout }}/js/kendoui.trial.2012.2.710/js/kendo.dataviz.min.js"></script>
		<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
		<script src="{{ layout }}/js/plugins/jquery-upload/jquery.iframe-transport.js"></script>
		<!-- The basic File Upload plugin -->
		<script src="{{ layout }}/js/plugins/jquery-upload/jquery.fileupload.js"></script>
		<!-- The File Upload file processing plugin -->
		<script src="{{ layout }}/js/plugins/jquery-upload/jquery.fileupload-fp.js"></script>
		<!-- The File Upload user interface plugin -->
		<script src="{{ layout }}/js/plugins/jquery-upload/jquery.fileupload-ui.js"></script>
		<!-- The localization script -->
		<script src="{{ layout }}/js/plugins/jquery-upload/locale.js"></script>
		<!-- The cute image -->
		<script src="{{ layout }}/js/plugins/Jcrop/jquery.Jcrop.min.js"></script>

		<script src="{{ layout }}/js/general.js"></script>
		<script type="text/javascript">
		$(function() {
			$("#chart").kendoChart({
				theme: "blueOpal",
				dataSource: {
					data: {{ series|raw }}
				},
				title: {
					text: "Estadística General"
				},
				legend: {
					position: "bottom"
				},
				seriesDefaults: {
					type: "column",
					labels: {
						visible: true,
						format: "{0}"
					}
				},
				series: [{
					field: "value"
				}],
				categoryAxis: {
					field: "nombre"
				},
				tooltip: {
					visible: true,
					format: "N0"
				}
			});
			$("#chart-status").kendoChart({
				theme: "blueOpal",
				dataSource: {
					data: {{ status|raw }}
				},
				title: {
					text: "Status"
				},
				legend: {
					position: "bottom"
				},
				seriesDefaults: {
					type: "column",
					labels: {
						visible: true,
						format: "{0}"
					}
				},
				series: [{
					field: "value"
				}],
				categoryAxis: {
					field: "nombre"
				},
				tooltip: {
					visible: true,
					format: "N0"
				}
			});
			$("#chart-asis").kendoChart({
					theme: "blueOpal",
					dataSource: {
						data: {{ asistencias|raw }}
					},
					title: {
						text: "Asistencia"
					},
					legend: {
						position: "bottom"
					},
					seriesDefaults: {
						type: "column",
						labels: {
							visible: true,
							format: "{0}"
						}
					},
					series: [{
						field: "value"
					}],
					categoryAxis: {
						field: "nombre",
						labels: {
							rotation: -90
						}
					},
					tooltip: {
						visible: true,
						format: "N0"
					}
				});
		});
		</script>
	</body>
</html>