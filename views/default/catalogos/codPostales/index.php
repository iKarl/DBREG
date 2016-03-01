{#
<?php
 /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/catalogos/codPostales/
 * @version $Id: index.php 1.0 2012-03-19 16:32 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com, son_gohan_khan@hotmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}
<div class="page-header">
	<h3>
		Directorio postal
		<small>Codigos postales de México</small>
	</h3>
</div>

<ul class="nav-seccion">
	<li>
		<form class="form-inline" data-load="ajax" action="{{ seccion }}/?action=buscar" method="post">
			<div class="control-group">
				<label class="control-label inline" for="codigoPostal">Codigo postal:</label>
				<div class="controls">
					<div class="input-append">
						<input class="span1" id="codigoPostal" name="codigoPostal" type="text" />
						<button class="btn" type="submit">Buscar</button>
					</div>
				</div>
			</div>
		</form>
	</li>
</ul>

<table class="table table-striped table-bordered table-condensed" id="lista-cps">
	<thead>
		<tr>
			<th>No.</th>
			<th>Codigo Postal</th>
			<th>Nombre (Colonia, Unidad habitacional, etc.)</th>
			<th>Tipo</th>
			<th>Nombre del Municipio</th>
			<th>Estado</th>
			<th>Ciudad</th>
			<th>Opciones</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th colspan="8">&nbsp;</th>
		</tr>
	</tbody>
</table>