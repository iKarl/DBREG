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
	<h5>Generación de reporte de adicionales (items)</h5>
</div>

<form action="{{ seccion }}/?action=repAdicionales" class="form-inline" method="post" name="reportes-financiero" target="_blank">

	<fieldset class="horizontal">
		<div class="control-group">
			<label class="control-label" for="campos">Elije los campos:</label>
			<div class="controls">
				<select class="span2" id="campos" name="campos[]" multiple required style="height: 175px;">
					{% for campo in campos %}
						<option value="i.{{ campo.orgname }}">{{ campo.name|replace({'_': ' '})|capitalize }}</option>
					{% endfor %}
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="reg_status">Registros con status igual(es) a:</label>
			<div class="controls">
				<select class="span2" id="reg_status" name="reg_status[]" multiple required style="height: 175px;">
					{% for status in statusReg %}
						<option value="{{ status.esr_clave }}">{{ status.esr_nombre|replace({'_': ' '})|capitalize }}</option>
					{% endfor %}
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="item_clave">Categorias igual(es) a:</label>
			<div class="controls">
				<select class="span2" id="item_clave" multiple name="item_clave[]" required style="height: 175px;">
				{% for categoria in categorias %}
					<option value="{{ categoria.eni_clave }}">{{ categoria.eni_nombre|replace({'_': ' '})|capitalize }}</option>
				{% endfor %}
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="item_status">Con status de items igual(es) a:</label>
			<div class="controls">
				<select class="span2" id="item_status" name="item_status[]" multiple required style="height: 175px;">
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