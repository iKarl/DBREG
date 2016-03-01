{#
<?php
 /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/catalogos/paises/
 * @version $Id: formEditar.php 1.0 2012-03-12 23:12 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com, son_gohan_khan@hotmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}
<div class="modal-general" title="Editar país">

	<form action="{{ seccion }}/?action=actualizar" data-load="ajax" method="post" name="actPais">
		<input id="pais_idPais" name="pais_idPais" type="hidden" value="{{ pais.pais_idPais }}" />
		<fieldset>
			<div class="control-group">
				<label class="control-label" for="pais_nombreEs">Nombre en español:</label>
				<div class="controls">
					<input class="span3" id="pais_nombreEs" name="pais_nombreEs" type="text" value="{{ pais.pais_nombreEs }}" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="pais_nombreEn">Nombre en ingles:</label>
				<div class="controls">
					<input class="span3" id="pais_nombreEn" name="pais_nombreEn" type="text" value="{{ pais.pais_nombreEn }}" />
				</div>
			</div>
		</fieldset>

		<fieldset>
			<div class="control-group">
				<label class="control-label" for="pais_iso2">Clave ISO 2:</label>
				<div class="controls">
					<input class="span1" id="pais_iso2" name="pais_iso2" type="text" value="{{ pais.pais_iso2 }}" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="pais_iso3">Clave ISO 3:</label>
				<div class="controls">
					<input class="span1" id="pais_iso3" name="pais_iso3" type="text" value="{{ pais.pais_iso3 }}" />
				</div>
			</div>
		</fieldset>

		<div class="form-actions">
			<div class="form-error">&nbsp;</div>
			<input class="btn btn-primary" type="submit" value="Guardar" />
		</div>
	</form>

</div>