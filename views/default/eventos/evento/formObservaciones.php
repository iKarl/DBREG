{#
<?php
/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/eventos/evento/
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * @version $Id: formObservaciones.php 1.0 2012-04-23 00:43 _Karl_ $;
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}
<div class="modal-general" title="Editar las observaciones">

	<form action="{{ seccion }}/?action=actualizarObservaciones" data-load="ajax" method="post" name="observaciones">
		<div class="control-group">
			<label class="controls-label" for="evt_observaciones">Observaciones:</label>
			<div class="controls">
				<textarea class="span4" id="evt_observaciones" name="evt_observaciones" rows="5">{{ observaciones }}</textarea>
			</div>
		</div>

		<div class="acciones-form">
			<div class="form-error">&nbsp;</div>
			<div>
				<button class="btn btn-primary" type="submit">Guardar</button>
			</div>
		</div>
	</form>

</div>