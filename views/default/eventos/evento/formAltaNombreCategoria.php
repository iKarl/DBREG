<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/eventos/evento/
 * @version $Id: formAltaNombreCategoria.php 1.0 2012-05-02 17:29 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); } 
?>
<div class="modal-general" title="Alta de nombre para la categoria">

	<form action="<?php echo $this->seccion; ?>/?action=agregarNombreCategoria" data-load="ajax" method="post" name="altaNombreCategoria">
		<input id="enc_clave" name="enc_clave" type="hidden" value="<?php echo $this->enc_clave; ?>" />
		<input id="enc_idNombreCategoria" name="enc_idNombreCategoria" type="hidden" value="<?php echo $this->enc_idNombreCategoria; ?>" />
		<div class="control-group">
			<label class="control-label" for="enc_idioma">Idioma:</label>
			<div class="controls">
				<select id="enc_idioma" name="enc_idioma">
					<?php echo $this->listaLenguajes; ?>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="enc_nombre">Nombre:</label>
			<div class="controls">
				<input class="span2" id="enc_nombre" name="enc_nombre" type="text" />
			</div>
		</div>

		<div class="acciones-form">
			<div class="form-error">&nbsp;</div>
			<div>
				<input class="btn btn-primary" type="submit" value="Agregar" />
			</div>
		</div>

	</form>

</div>