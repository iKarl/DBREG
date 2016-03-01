{#
<?php
/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/eventos/evento/
 * @version $Id: items.php 1.0 2012-06-28 16:47 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
 if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}
<div class="modal-general" title="Catalogo de adicionales">

	<form action="{{ seccion }}/items?action=agregarItem" data-load="ajax" id="form-alta-item" method="post" name="agregarItem">
		<fieldset class="horizontal">
			<div class="control-group">
				<label class="control-label" for="eci_clave">Clave:</label>
				<div class="controls">
					<input class="input-small" id="eci_clave" name="eci_clave" type="text" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="eci_costo_fecha_1">Costo 1:</label>
				<div class="controls">
					<input class="input-small" id="eci_costo_fecha_1" name="eci_costo_fecha_1" placeholder="0.00" type="text" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="eci_fecha_1">1er Fecha limite:</label>
				<div class="controls">
					<input class="input-small" id="eci_fecha_1" name="eci_fecha_1" readonly type="text" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="eci_costo_fecha_2">Costo 2:</label>
				<div class="controls">
					<input class="input-small" id="eci_costo_fecha_2" name="eci_costo_fecha_2" placeholder="0.00" type="text" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="eci_fecha_2">2er Fecha limite:</label>
				<div class="controls">
					<input class="input-small" id="eci_fecha_2" name="eci_fecha_2" readonly type="text" />
				</div>
			</div>
		</fieldset>

		<fieldset class="horizontal">
			<div class="control-group">
				<label class="control-label" for="eci_costo_fecha_3">Costo 3:</label>
				<div class="controls">
					<input class="input-small" id="eci_costo_fecha_3" name="eci_costo_fecha_3" placeholder="0.00" type="text" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="eci_fecha_3">3er Fecha limite:</label>
				<div class="controls">
					<input class="input-small" id="eci_fecha_3" name="eci_fecha_3" readonly type="text" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="eci_costo_sitio">Costo en sitio:</label>
				<div class="controls">
					<input class="input-small" id="eci_costo_sitio" name="eci_costo_sitio" placeholder="0.00" type="text" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="eci_costo_otro">Otro tipo de costo:</label>
				<div class="controls">
					<input class="input-small" id="eci_costo_otro" name="eci_costo_otro" placeholder="0.00" type="text" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="eci_paxMaximos">Pax maximos (0 cero = ilimitado):</label>
				<div class="controls">
					<input class="input-small" id="eci_paxMaximos" name="eci_paxMaximos" type="text" value="0" />
				</div>
			</div>
		</fieldset>

		<div class="acciones-form">
			<div class="form-error">&nbsp;</div>
			<div>
				<button class="btn btn-primary" type="submit">Agregar</button>
				<button class="btn cancelar-nuevo-item" type="reset">Cancelar</button>
			</div>
		</div>
	</form>

	<table class="table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th colspan="5">Costos</th>
				<th>
					<a class="btn btn-mini" href="#nuevo-item">
						<i class="icon-plus-sign"></i> Agregar
					</a>
				</th>
			</tr>
			<tr>
				<th style="width: 105px;">Clave</th>
				<th style="width: 290px;">Nombre/s</th>
				<th style="width: 105px;">Costo fecha 1</th>
				<th style="width: 105px;">Costo fecha 2</th>
				<th style="width: 105px;">Costo fecha 3</th>
				<th style="width: 105px;">En sitio</th>
				<th style="width: 105px;">Otro</th>
				<th style="width: 70px;">Pax maximo</th>
				<th style="width: 70px;">Opciones</th>
			</tr>
		</thead>
		<tbody class="catItems">
			{% for item in items %}
				<tr id="{{ item.eci_clave }}">
					<td>{{ item.eci_clave }}</td>
					<!-- Inicia nombres de los items -->
					<td class="nombres-items {{ item.eci_clave }}">
						<div class="agregar-nombre-item">
							<a data-load="accion" class="btn btn-mini btn-warning" href="{{ seccion }}/items?action=formAltaNombreItem&amp;cve={{ item.eci_clave }}" title="Agregar nombre al item">
								<i class="icon-plus-sign icon-white"></i>
							</a>
						</div>
						{% for nombre in item.nombres %}
						<div class="nombre-item" id="{{ item.eci_clave }}-{{ nombre.eni_idNombreItem }}">
							<span>{{ nombre.eni_nombre }}</span>
							<span class="acciones-nombre-item">
								<a class="btn btn-mini btn-danger eliminar-nombre-item" href="{{ seccion }}/items?action=eliminarNombre&amp;cve={{ nombre.eni_clave }}&amp;id={{ nombre.eni_idNombreItem }}" title="Eliminar nombre">
									<i class="icon-trash icon-white"></i>
								</a>
							</span>
						</div>
						{% endfor %}
					</td>
					<!-- Termina nombres de las categorias -->
					<td>
						<div>{{ item.eci_costo_fecha_1 }}</div>
						<div class="fechaLimite">{{ item.eci_fecha_1 }}</div>
					</td>
					<td>
						<div>{{ item.eci_costo_fecha_2 }}</div>
						<div class="fechaLimite">{{ item.eci_fecha_2 }}</div>
					</td>
					<td>
						<div>{{ item.eci_costo_fecha_3 }}</div>
						<div class="fechaLimite">{{ item.eci_fecha_3 }}</div>
					</td>
					<td>{{ item.eci_costo_sitio }}</td>
					<td>{{ item.eci_costo_otro }}</td>
					<td>{{ item.eci_paxMaximos }}</td>
					<td>
						<a class="btn btn-mini" data-load="accion" href="{{ seccion }}/items?action=formEditarItem&amp;cve={{ item.eci_clave }}" title="Editar el item">
							<i class="icon-edit"></i>
						</a>
						<a class="btn btn-mini btn-danger eliminar-item" href="{{ seccion }}/items?action=eliminarItem" data-cve-item="{{ item.eci_clave }}" title="Eliminar el item">
							<i class="icon-trash icon-white"></i>
						</a>
					</td>
				</tr>
			{% else %}
				<tr>
					<th colspan="8">
						Aun no hay items
					</th>
				</tr>
			{% endfor %}
		</tbody>
	</table>

</div>