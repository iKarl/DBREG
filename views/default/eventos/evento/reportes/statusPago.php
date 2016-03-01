{#
<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/eventos/evento/reportes/
 * @version $Id: statusPago.php 1.0 2013-09-09 17:14 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}
<div id="chart"></div>
<!--<div class="text-right">Total: {{ total }}</div>-->

<script type="text/javascript">
$(function() {
	$("#chart").kendoChart({
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
});
</script>