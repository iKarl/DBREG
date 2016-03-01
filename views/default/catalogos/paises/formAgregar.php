<?php
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
<!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/catalogos/paises/
 * @version $Id: formAgregar.php 1.0 2012-02-27 22:07 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com, son_gohan_khan@hotmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * -->
<div class="modal-general" title="Alta de nuevo país">

	<form action="{{ seccion }}/?action=agregar" data-load="ajax" method="post" name="nuevoPais">

		<fieldset>
			<div class="control-group">
				<label class="control-label" for="pais_nombreEs">Nombre en español:</label>
				<div class="controls">
					<input class="span3" id="pais_nombreEs" name="pais_nombreEs" type="text" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="pais_nombreEn">Nombre en ingles:</label>
				<div class="controls">
					<input class="span3" id="pais_nombreEn" name="pais_nombreEn" type="text" />
				</div>
			</div>
		</fieldset>

		<fieldset>
			<div class="control-group">
				<label class="control-label" for="pais_iso2">Clave ISO 2:</label>
				<div class="controls">
					<input class="span1" id="pais_iso2" name="pais_iso2" type="text" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="pais_iso3">Clave ISO 3:</label>
				<div class="controls">
					<input class="span1" id="pais_iso3" name="pais_iso3" type="text" />
				</div>
			</div>
		</fieldset>

		<div class="form-actions">
			<div class="form-error">&nbsp;</div>
			<input class="btn btn-primary" type="submit" value="Agregar" />
		</div>

	</form>

</div>