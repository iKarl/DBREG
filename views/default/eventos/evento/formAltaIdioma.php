{#
<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/defulat/eventos/evento/
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * @version $Id: formAltaCategoria.php 1.0 2012-04-17 21:36 _Karl_ $;
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}
<div class="modal-general" title="Alta de idioma">

	<form action="{{ seccion }}/?action=agregarIdioma" data-load="ajax" method="post" name="agregarIdioma">
		<fieldset class="horizontal">
			<div class="control-group">
				<label class="control-label" for="eis_idioma">Clave del idioma:</label>
				<div class="controls">
					<input id="eis_idioma" name="eis_idioma" size="3" type="text" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="eis_nombre">Nombre del idioma:</label>
				<div class="controls">
					<input class="input-small" id="eis_nombre" name="eis_nombre" type="text" size="3" />
				</div>
			</div>
		</fieldset>

		<div class="acciones-form">
			<div class="form-error">&nbsp;</div>
			<div>
				<input class="btn btn-primary" type="submit" value="Agregar" />
			</div>
		</div>
	</form>

</div>