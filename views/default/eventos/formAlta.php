{#
<?php
/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/eventos/
 * @version $Id: formAlta.php 1.0 2012-04-12 00:46 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}
<div class="modal-general" title="Alta de nuevo evento">

	<form action="{{ seccion }}/?action=agregarEvento" data-load="ajax" method="post">
		<fieldset class="horizontal">
			<div class="control-group">
				<label class="control-label" for="evt_idTipoEvento">Tipo:</label>
				<div class="controls">
					<select id="evt_idTipoEvento" name="evt_idTipoEvento">
						<option value="">Seleccione:</option>
						{% for tipoEvento in tiposEvento %}
						<option value="{{ tipoEvento.cte_idTipoEvento }}">{{ tipoEvento.cte_nombre }}</option>
						{% else %}
						<option value="">No hay tipos de evento</option>
						{% endfor %}
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="evt_clave">Clave:</label>
				<div class="controls">
					<input class="input-small" id="evt_clave" name="evt_clave" type="text" />
				</div>
			</div>
		</fieldset>

		<fieldset class="horizontal">
			<div class="control-group">
				<label class="control-label" for="evt_nombre">Nombre:</label>
				<div class="controls">
					<input class="span6" id="evt_nombre" name="evt_nombre" type="text" />
				</div>
			</div>
		</fieldset>

		<fieldset class="horizontal">
			<div class="control-group">
				<label class="control-label" for="evt_inicio">Fecha de inicio:</label>
				<div class="controls">
					<input class="input-small" id="evt_inicio" name="evt_inicio" readonly="readonly" placeholder="dd/mm/yyyy" type="text" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="evt_termina">Fecha que termina:</label>
				<div class="controls">
					<input class="input-small" id="evt_termina" name="evt_termina" readonly="readonly" placeholder="dd/mm/yyyy" type="text" />
				</div>
			</div>
		</fieldset>

		<fieldset class="horizontal">
		<div class="control-group">
			<label class="control-label" for="evt_divisa">Divisa:</label>
			<div class="controls">
				<select id="evt_divisa" name="evt_divisa">
					<option value="">Seleccione:</option>
					{% for divisa in divisas %}
					<option value="{{ divisa.dvs_clave }}" title="{{ divisa.dvs_nombre }}">{{ divisa.dvs_clave }}</option>
					{% else %}
					<option value="">No hay divisas</option>
					{% endfor %}
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="evt_iva">IVA:</label>
			<div class="controls">
				<input class="span1" id="evt_iva" name="evt_iva" placeholder="0.00" type="text" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="evt_status">Status:</label>
			<div class="controls">
				<select id="evt_status" name="evt_status">
					<option value="">Seleccione:</option>
					{% for status in statusEvento %}
					<option value="{{ status.ecs_idStatus }}">{{ status.ecs_nombre }}</option>
					{% else %}
					<option value="">No hay tipos de evento</option>
					{% endfor %}
				</select>
			</div>
		</div>
		</fieldset>

		<div class="acciones-form">
			<div class="form-error">&nbsp;</div>
			<div>
				<button class="btn btn-primary" type="submit">Agregar</button>
			</div>
		</div>

	</form>

</div>