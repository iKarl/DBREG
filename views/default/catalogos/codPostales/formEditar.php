{#
<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/catalogos/codPostales/
 * @version $Id: formEditar.php 1.0 2012-03-19 22:34 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com, son_gohan_khan@hotmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}
<div class="modal-general" title="Actualizar el codigo postal">
	<form name="editarCP" data-load="ajax" action="{{ seccion }}/?action=actualizar" method="post">
		<input id="cp_idCodigoPostal" name="cp_idCodigoPostal" type="hidden" value="{{ cp.cp_idCodigoPostal }}" />
		<fieldset>
			<div class="control-group">
				<label class="control-label" for="cp_codigoPostal">Codigo Postal:</label>
				<div class="controls">
					<input class="span1" id="cp_codigoPostal" name="cp_codigoPostal" type="text" value="{{ cp.cp_codigoPostal }}" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="cp_asenta">Nombre (Colonia, Unidad habitacional, etc.):</label>
				<div class="controls">
					<input class="span3" id="cp_asenta" name="cp_asenta" type="text" value="{{ cp.cp_asenta }}" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="cp_tipoAsenta">Tipo:</label>
				<div class="controls">
					<input class="span3" id="cp_tipoAsenta" name="cp_tipoAsenta" type="text" value="{{ cp.cp_tipoAsenta }}" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="cp_nombreMnpio">Municipio/delegecion:</label>
				<div class="controls">
					<input class="span3" id="cp_nombreMnpio" name="cp_nombreMnpio" type="text" value="{{ cp.cp_nombreMnpio }}" />
				</div>
			</div>
			<div class="control-group">
				<div class="controls">
					<label class="control-label" for="cp_nombreEstado">Estado:</label>
				<input class="span3" id="cp_nombreEstado" name="cp_nombreEstado" type="text" value="{{ cp.cp_nombreEstado }}" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="cp_nombreCiudad">Ciudad:</label>
				<div class="controls">
					<input class="span3" id="cp_nombreCiudad" name="cp_nombreCiudad" type="text" value="{{ cp.cp_nombreCiudad }}" />
				</div>
			</div>
		</fieldset>

		<div class="form-actions">
			<div class="form-error">&nbsp;</div>
			<div>
				<input class="btn btn-primary" type="submit" value="Guardar" />
			</div>
		</div>
	</form>
</div>