{#
<?php if (!defined("SimpleMVC")) { die("Not Access Directa"); }
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/eventos/evento/registros/
 * @version $Id: index.php 1.0 2012-05-01 00:20 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
?>
#}

<div class="page-header" style="margin-bottom: 5px;">
	<h4>Inicio <small>Lista de eventos en progreso</small></h4>
</div>

<div id="eventos">

	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th colspan="2">Eventos</th>
			</tr>
		</thead>
		<tbody>
			{% for evento in eventos %}
			<tr>
				<th style="width: 100px;">
					<a data-load="seccion" data-method="post" data-opts='{"tools":{"css":true,"js":true,"file":"registros"}}' href="{{ seccion }}/registros/?id={{ evento.evt_idEvento }}"><i class="icon-list"></i> Registros</a>
				</th>
				<td>{{ evento.evt_nombre|upper }}</td>
			</tr>
			{% else %}
			<tr>
				<th>No hay eventos en progreso</th>
			</tr>
			{% endfor %}
		</tbody>
	</table>

	<div>
		<div id="charts">
			<div id="chart"></div>
			<div class="text-right">Total: {{ total }}</div>
			<hr />
			<div id="chart-asis"></div>
			<div class="text-right">Total: {{ total_asis }}</div>
			<hr />
			<div id="chart-status"></div>
		</div>
	</div>
</div>