{#
<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/eventos/evento/reportes/
 * @version $Id: adicionales.php 1.0 2012-09-12 22:24 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}
<div id="chart"></div>

<script type="text/javascript">
$(function() {
	$("#chart").kendoChart({
		theme: "blueOpal",
		dataSource: {
			data: {{ adicionales|raw }}
		},
		title: {
			text: "Adicionales (Items)"
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
			field: 'total',
			name: 'Total'
		},{
			field: 'confirmados',
			name: 'Confirmados'
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