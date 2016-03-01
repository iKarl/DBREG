{#
<?php
/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/eventos/evento/
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * @version $Id: formDatosGenerales.php 1.0 2012-04-23 22:06 _Karl_ $;
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}
<div class="modal-general" title="Editar datos generales del evento">

	<form action="{{ seccion }}/?action=actualizarDatosGenerales" data-load="ajax" method="post" name="datosGenerales">
		<input id="evt_clave_org" name="evt_clave_org" type="hidden" value="{{ evento.evt_clave }}" />
		<fieldset class="horizontal">
			<div class="control-group">
				<label class="controls-label" for="evt_idTipoEvento">Tipo:</label>
				<div class="controls">
					<select id="evt_idTipoEvento" name="evt_idTipoEvento">
						<option value="">Seleccione:</option>
						{% for tipoEvento in tiposEvento %}
						<option value="{{ tipoEvento.cte_idTipoEvento }}" {{ tipoEvento.cte_idTipoEvento == evento.evt_idTipoEvento ? ' selected' : '' }}>{{ tipoEvento.cte_nombre }}</option>
						{% else %}
						<option value="">No hay tipos de evento</option>
						{% endfor %}
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="controls-label" for="evt_clave">Clave:</label>
				<div class="controls">
					<input class="input-small" id="evt_clave" name="evt_clave" type="text" value="{{ evento.evt_clave }}" />
				</div>
			</div>
		</fieldset>

		<fieldset class="horizontal">
			<div class="control-group">
				<label class="controls-label" for="evt_nombre">Nombre:</label>
				<div class="controls">
					<input class="span6" id="evt_nombre" name="evt_nombre" type="text" value="{{ evento.evt_nombre }}" />
				</div>
			</div>
		</fieldset>

		<fieldset class="horizontal">
			<div class="control-group">
				<label class="controls-label" for="evt_inicio">Fecha de inicio:</label>
				<div class="controls">
					<input class="input-small" id="evt_inicio" name="evt_inicio" type="text" value="{{ evento.evt_inicio }}" />
				</div>
			</div>
			<div class="control-group">
				<label class="controls-label" for="evt_termina">Fecha que termina:</label>
				<div class="controls">
					<input class="input-small" id="evt_termina" name="evt_termina" type="text" value="{{ evento.evt_termina }}" />
				</div>
			</div>
		</fieldset>

		<fieldset class="horizontal">
			<div class="control-group">
				<label class="controls-label" for="evt_divisa">Divisa:</label>
				<div class="controls">
					<select id="evt_divisa" name="evt_divisa">
						<option value="">Seleccione:</option>
						{% for divisa in divisas %}
						<option value="{{ divisa.dvs_clave }}" title="{{ divisa.dvs_nombre }}"{{ divisa.dvs_clave == evento.evt_divisa ? ' selected' : '' }}>{{ divisa.dvs_clave }}</option>
						{% else %}
						<option value="">No hay divisas</option>
						{% endfor %}
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="evt_iva">IVA:</label>
				<div class="controls">
					<input class="span1" id="evt_iva" name="evt_iva" placeholder="0.00" type="text" value="{{ evento.evt_iva }}" />
				</div>
			</div>
			<div class="control-group">
				<label class="controls-label" for="evt_status">Status:</label>
				<div class="controls">
					<select id="evt_status" name="evt_status">
						<option value="">Seleccione:</option>
						{% for status in statusEvento %}
						<option value="{{ status.ecs_idStatus }}"{{ status.ecs_idStatus == evento.evt_status ? ' selected' : '' }}>{{ status.ecs_nombre }}</option>
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
				<button class="btn btn-primary" type="submit">Guardar</button>
			</div>
		</div>
	</form>

</div>