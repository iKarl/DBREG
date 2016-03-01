{#
<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/eventos/evento/reportes/
 * @version $Id: index.php 1.0 2012-08-30 21:44 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}

<ul class="nav nav-tabs">
	<li class="active">
		<a data-load="seccion" data-opts='{"target":"evento"}' href="{{ seccion }}/">
			<i class="icon-tasks"></i> Gráfica concentrada
		</a>
	</li>
	<li>
		<a data-load="seccion" data-opts='{"target":"charts"}' href="{{ seccion }}/?action=asistencia">
			<i class="icon-tasks"></i> Gráfica de asistencia
		</a>
	</li>
	<li>
		<a data-load="seccion" data-opts='{"target":"charts"}' href="{{ seccion }}/?action=asistenciaPais">
			<i class="icon-tasks"></i> Gráfica por país
		</a>
	</li>
	<li>
		<a data-load="seccion" data-opts='{"target":"charts"}' href="{{ seccion }}/?action=asistenciaPaisLlegados">
			<i class="icon-tasks"></i> Gráfica asistencia por país
		</a>
	</li>
	<li>
		<a data-load="seccion" data-opts='{"target":"charts"}' href="{{ seccion }}/?action=adicionales">
			<i class="icon-tasks"></i> Gráfica de adicionales
		</a>
	</li>
	<li>
		<a data-load="seccion" data-opts='{"target":"charts"}' href="{{ seccion }}/?action=statusPago">
			<i class="icon-tasks"></i> Gráfica de status
		</a>
	</li>
	<li class="dropdown">
		<a class="dropdown-toggle" data-toggle="dropdown" href="#">
			<i class="icon-list-alt"></i> Reportes <b class="caret"></b>
		</a>
		<ul class="dropdown-menu">
			<li>
				<a data-load="seccion" data-opts='{"target":"charts"}' href="{{ seccion }}/?action=genAdicionales">
					<i class="icon-list-alt"></i> Reporte adicionales
				</a>
			</li>
			<li>
				<a data-load="seccion" data-opts='{"target":"charts"}' href="{{ seccion }}/?action=genReporte">
					<i class="icon-list-alt"></i> Reporte general
				</a>
			</li>
			<li>
				<a data-load="seccion" data-opts='{"target":"charts"}' href="{{ seccion }}/?action=genReporteFotos">
					<i class="icon-list-alt"></i> Reporte con fotografía
				</a>
			</li>
			<li>
				<a href="{{ seccion }}/?action=expReporteGeneral" target="_blank">
					<i class="icon-list-alt"></i> Reporte simple
				</a>
			</li>
			<li>
				<a href="{{ seccion }}/?action=expReporteGeneralItems" target="_blank">
					<i class="icon-list-alt"></i> Reporte adicionales
				</a>
			</li>
			<li>
				<a href="{{ seccion }}/?action=expReporteGeneralAcomp" target="_blank">
					<i class="icon-list-alt"></i> Reporte acompañantes
				</a>
			</li>
			<li>
				<a data-load="seccion" data-opts='{"target":"charts"}' href="{{ seccion }}/?action=rfid">
					<i class="icon-tasks"></i> Reporte RFID
				</a>
			</li>
		</ul>
	</li>
	<!--<li>
		<a data-load="seccion" data-opts='{"target":"charts"}' href="{{ seccion }}/?action=genReporte">
			<i class="icon-signal"></i> Reporte financiero
		</a>
	</li>
	<li>
		<a data-load="accion" data-opts='{"target":"charts"}' href="{{ seccion }}/?action=genReporte">
			<i class="icon-cog"></i> Acompañantes
		</a>
	</li>
	<li>
		<a data-load="accion" data-opts='{"target":"charts"}' href="{{ seccion }}/?action=genReporte">
			<i class="icon-cog"></i> Adicionales (items)
		</a>
	</li>-->
</ul>

<div id="charts">
	<div id="chart"></div>
	<div class="text-right">Total: {{ total }}</div>

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
</div>