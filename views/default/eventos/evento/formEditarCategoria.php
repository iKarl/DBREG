{#
<?php
/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/eventos/evento/
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * @version $Id: formEditarCategoria.php 1.0 2012-04-26 00:15 _Karl_ $;
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}
<div class="modal-general" title="Editar categoria">

	<form action="{{ seccion }}/?action=actualizarCategoria" data-load="ajax" method="post" name="actualizarCategoria">
		<input id="ecc_clave_ant" name="ecc_clave_ant" type="hidden" value="{{ categoria.ecc_clave }}" />
		<fieldset class="horizontal">
			<div class="control-group">
				<label class="control-label" for="ecc_clave">Clave:</label>
				<div class="controls">
					<input class="input-small" id="ecc_clave" name="ecc_clave" type="text" value="{{ categoria.ecc_clave }}" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="ecc_costo_fecha_1">Costo 1:</label>
				<div class="controls">
					<input class="input-small" id="ecc_costo_fecha_1" name="ecc_costo_fecha_1" placeholder="0.00" type="text" value="{{ categoria.ecc_costo_fecha_1 }}" />
				</div>
			</div>
		</fieldset>

		<fieldset class="horizontal">
			<div class="control-group">
				<label class="control-label" for="ecc_costo_fecha_2">Costo 2:</label>
				<div class="controls">
					<input class="input-small" id="ecc_costo_fecha_2" name="ecc_costo_fecha_2" placeholder="0.00" type="text" value="{{ categoria.ecc_costo_fecha_2 }}" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="ecc_costo_fecha_3">Costo 3:</label>
				<div class="controls">
					<input class="input-small" id="ecc_costo_fecha_3" name="ecc_costo_fecha_3" placeholder="0.00" type="text" value="{{ categoria.ecc_costo_fecha_3 }}" />
				</div>
			</div>
		</fieldset>

		<fieldset class="horizontal">
			<div class="control-group">
				<label class="control-label" for="ecc_costo_sitio">Costo en sitio:</label>
				<div class="controls">
					<input class="input-small" id="ecc_costo_sitio" name="ecc_costo_sitio" placeholder="0.00" type="text" value="{{ categoria.ecc_costo_sitio }}" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="ecc_costo_otro">Otro tipo de costo:</label>
				<div class="controls">
					<input class="input-small" id="ecc_costo_otro" name="ecc_costo_otro" placeholder="0.00" type="text" value="{{ categoria.ecc_costo_otro }}" />
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