{#
<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/eventos/evento/reportes/
 * @version $Id: asistencia.php 1.0 2012-09-05 23:45 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}
<div id="chart"></div>
<div class="text-right">Total: {{ total }}</div>

<script type="text/javascript">
$(function() {
	$("#chart").kendoChart({
		theme: "blueOpal",
		dataSource: {
			data: {{ asistencias|raw }}
		},
		title: {
			text: "Registros por país"
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