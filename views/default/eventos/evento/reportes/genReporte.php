{#
<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/eventos/evento/reportes/
 * @version $Id: genReporte.php 1.0 2012-09-05 23:46 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}
<div class="page-header">
	<h5>Generación de reporte financiero</h5>
</div>

<form action="{{ seccion }}/?action=repGeneral" class="form-inline" method="post" name="reporte-general" target="_blank">
	<fieldset class="horizontal">
		<div class="control-group">
			<label class="control-label" for="campos">Campos:</label>
			<div class="controls">
				<select class="span2" id="campos" name="campos[]" multiple required style="height: 175px;">
					{% for campo in campos %}
						<option value="r.{{ campo.orgname }}">{{ campo.name|replace({'_': ' '})|capitalize }}</option>
					{% endfor %}
				</select>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="cat_registro">La categoria es igual a:</label>
			<div class="controls">
				<select class="span2" id="cat_registro" multiple name="cat_registro[]" required style="height: 175px;">
				{% for categoria in categorias %}
					<option value="{{ categoria.enc_clave }}">{{ categoria.enc_nombre|replace({'_': ' '})|capitalize }}</option>
				{% endfor %}
				</select>
			</div>
		</div>
	
		<div class="control-group">
			<label class="control-label" for="forma_pago">La forma de pago es igual a:</label>
			<div class="controls">
				<select class="span2" id="forma_pago" name="forma_pago[]" multiple required style="height: 175px;">
					{% for formaPago in formasPago %}
						<option value="{{ formaPago.fpn_clave }}">{{ formaPago.fpn_nombre|replace({'_': ' '})|capitalize }}</option>
					{% endfor %}
				</select>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="status">Y el status es igual a:</label>
			<div class="controls">
				<select class="span2" id="status" name="status[]" multiple required style="height: 175px;">
					{% for status in statusReg %}
						<option value="{{ status.esr_clave }}">{{ status.esr_nombre|replace({'_': ' '})|capitalize }}</option>
					{% endfor %}
				</select>
			</div>
		</div>
	</fieldset>

	<div class="form-actions">
		<button class="btn" type="submit">
			<i class="icon-refresh"></i> Generar
		</button>
	</div>
</form>