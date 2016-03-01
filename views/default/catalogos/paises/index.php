{#
<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/catalogos/paises/
 * @version $Id: index.php 1.0 2012-02-27 22:51 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com, son_gohan_khan@hotmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}
<div class="page-header">
	<h3>
		Catalogo de países
	</h3>
</div>

<ul class="nav-seccion">
	<li>
		<a class="btn" data-load="modal" href="{{ seccion }}/?action=formAgregar">
			<i class="icon-plus"></i> Agregar
		</a>
	</li>
	<li>
		<form class="form-inline" data-load="ajax" action="{{ seccion }}/?action=paises" method="get">
			<label for="p">Página:</label>
			<select id="pPagina" name="pPagina">
				{{ paginas|raw }}
			</select>
		</form>
	</li>
</ul>

<table class="table table-striped table-bordered table-condensed" id="lista-paises">
	<thead>
		<tr>
			<th>No.</th>
			<th>Bandera</th>
			<th>Nombre en español</th>
			<th>Nombre en ingles</th>
			<th>Clave ISO 2</th>
			<th>Clave ISO 3</th>			
			<th>Opciones</th>
		</tr>
	</thead>
	<tbody>
	{% for pais in paises %}
		<tr id="pais-{{ pais.pais_idPais }}">
			<td>{{ pais.pais_idPais }}</td>
			<th>
				<img alt="" src="{{ images }}/flags/{{ pais.pais_imagen }}" />
			</th>
			<td>{{ pais.pais_nombreEs }}</td>
			<td>{{ pais.pais_nombreEn }}</td>
			<td>{{ pais.pais_iso2 }}</td>
			<td>{{ pais.pais_iso3 }}</td>
			<th>
				<a class="btn btn-mini btn-warning" data-load="modal" data-method="post" href="{{ seccion }}/?action=formEditar&amp;id={{ pais.pais_idPais }}">Editar</a>
			</th>
		</tr>
	{% else %}
		<tr>
			<th colspan="8">No hay paíeses</th>
		</tr>
	{% endfor %}
	</tbody>
</table>