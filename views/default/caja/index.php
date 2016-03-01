<div class="page-header" style="margin-bottom: 5px;">
	<h4>
		{{ tituloSeccion }}
	</h4>
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
					<a data-load="seccion" data-method="post" href="{{ seccion }}/index?action=caja&amp;id={{ evento.evt_idEvento }}"><i class="icon-list"></i> Caja</a>
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

</div>