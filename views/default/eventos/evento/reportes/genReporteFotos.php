{#
<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/eventos/evento/reportes/
 * @version $Id: genReporteFotos.php 1.0 2012-09-28 10:17 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}
<div class="page-header">
	<h5>Generación de reporte con fotografía</h5>
</div>

<form action="{{ seccion }}/?action=validarFormRepFotosPDF" class="form-inline" data-load="ajax" id="validarFormRepFotosPDF" method="post" name="validarFormRepFotosPDF">

	<fieldset class="horizontal">
		<!--<div class="control-group">
			<label class="control-label" for="campos">Campos:</label>
			<div class="controls">
				<select class="span2" id="campos" name="campos[]" multiple required style="height: 175px;">
					{% for campo in campos %}
						<option value="r.{{ campo.orgname }}">{{ campo.name|replace({'_': ' '})|capitalize }}</option>
					{% endfor %}
				</select>
			</div>
		</div>-->
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
		<!--<div class="control-group">
			<label class="control-label" for="forma_pago">La forma de pago es igual a:</label>
			<div class="controls">
				<select class="span2" id="forma_pago" name="forma_pago[]" multiple required style="height: 175px;">
					{% for formaPago in formasPago %}
						<option value="{{ formaPago.fpn_clave }}">{{ formaPago.fpn_nombre|replace({'_': ' '})|capitalize }}</option>
					{% endfor %}
				</select>
			</div>
		</div>-->
		<div class="control-group">
			<label class="control-label" for="inicio">Indique los números de inicio y final:</label>
			<div class="controls">
				<label>
					Iniciando en el: <input class="input-mini" id="inicio" name="inicio" placeholder="1" min="1" type="number" />
				</label>
				<label>
					Obteniendo: <input class="input-mini" id="fin" name="fin" placeholder="50" type="number" /> registros.
				</label>
				<span class="help-block">
					<small>
						Al generar el reporte puede ser un archivo muy pesado<br />
						lo cual producira un error, para evitar esto puede generar<br />
						el reporte por piezas, ejemplo:<br />
						Iniciando en el: 1 y Obteniendo registris de: 50 en 50<br />
						Generara el reporte de los registros desde el 1 hasta el 50,<br />
						Iniciando en el: 51 y Obteniendo registris de: 50 en 50<br />
						Generara el reporte de los registros desde el 51 hasta el 100,<br />
					</small>
				</span>
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
		<div class="form-error">&nbsp;</div>
		<button class="btn" type="submit">
			<i class="icon-refresh"></i> Generar
		</button>
	</div>
</form>