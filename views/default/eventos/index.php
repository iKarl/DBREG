{#
<?php
/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/eventos/
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * @version $Id: index.php 1.0 2011-06-09 23:01 _Karl_ $;
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}
<div class="page-header" style="margin-bottom: 5px;">
	<h4>
		{{ tituloSeccion }}
		<small>Lista de eventos</small>
	</h4>
</div>

<ul class="nav-seccion">
	<li>
		<div class="btn-group">
			<a data-load="modal" href="{{ seccion }}/?action=formAlta" class="btn btn-primary"><i class="icon-plus-sign icon-white"></i> Agregar</a>
			<a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><span class="caret"></span></a>
			<ul class="dropdown-menu">
				<li><a data-load="accion" href="#underconstruct">Catalogo tipos de evento</a></li>
			</ul>
		</div>
	</li>
	<li>Total de eventos: {{ totalEventos }}</li>
</ul>

<div id="eventos">
	<table class="table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<th>Clave</th>
				<th>Nombre</th>
				<th>Tipo de evento</th>
				<th>Fecha</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
			{% for evento in eventos %}
			<tr>
				<td>{{ evento.evt_clave }}</td>
				<td>
					<a data-load="seccion" data-opts='{"tools":{"css":true,"js":true,"file":"evento"}}' data-method="post" href="{{ seccion }}/evento/?id={{ evento.evt_idEvento }}">{{ evento.evt_nombre }}</a>
				</td>
				<td>{{ evento.cte_nombre }}</td>
				<th>{{ evento.evt_inicio }} - {{ evento.evt_termina }}</th>
				<th>{{ evento.ecs_nombre }}</th>
			</tr>
			{% else %}
			<tr id="no-eventos">
				<th colspan="5">No hay eventos</th>
			</tr>
			{% endfor %}
		</tbody>
	</table>
</div>