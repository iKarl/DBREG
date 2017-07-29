{#
<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/home/registros/
 * @version $Id: formAltaRegistro.php 1.0 2012-05-09 23:33 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}
<div id="nuevo-registro">

	<div class="barra-principal-registro">
		<div class="opciones-registro">
			<button class="btn btn-danger" id="fin-captura-reg" type="button" value="0" disabled>
				<i class="icon-ok icon-white"></i> Finalizar
			</button>
			<button class="btn" data-show-form="generales" type="button" disabled>
				<i class="icon-user"></i> Datos generales
			</button>
			<!--<button class="btn btn-default" id="salon" type="button" disabled>
				<i class="icon-tag"></i> Cena
			</button>-->
			<button class="btn btn-primary" data-show-form="contacto" type="button" disabled>
				<i class="icon-envelope icon-white"></i> Datos de contacto
			</button>
			<button class="btn btn-info" data-show-form="acompanantes" type="button" disabled>
				<i class="icon-th-list icon-white"></i> Acompañantes
			</button>
			<button class="btn btn-warning" data-show-form="adicionales" type="button" disabled>
				<i class="icon-shopping-cart icon-white"></i> Adicionales
			</button>
			<button class="btn btn-inverse" data-show-form="facturacion" type="button" disabled>
				<i class="icon-inbox icon-white"></i> Datos de Facturación
			</button>
			<button class="btn btn-danger" data-show-form="hospedaje" type="button" disabled>
				<i class="icon-briefcase icon-white"></i> Hospedaje
			</button>
		</div>
	</div>

	<!-- Datos generales -->
	<div id="datos-generales-registro">
		<form action="{{ seccion }}/?action=validarRegistro" id="validarRegistro" data-load="ajax" method="post" name="datos-generales">
			<input id="tabla_registros" name="tabla_registros" type="hidden" value="{{ evento.evt_nombreTablaAsistentes }}" />
			<input id="clave_evento" name="clave_evento" type="hidden" value="{{ evento.evt_clave }}" />
			<input id="divisa" name="divisa" type="hidden" value="{{ evento.evt_divisa }}" />

			<input id="save-photo" name="save_photo" type="hidden" value="0" />
			<input id="type-photo" name="photo[type]" type="hidden" value="" />
			<input id="photo" name="photo[photo]" type="hidden" value="" />
			<input id="x" name="photo[x]" type="hidden" value="0" />
			<input id="y" name="photo[y]" type="hidden" value="0" />
			<input id="x2" name="photo[x2]" type="hidden" value="0" />
			<input id="y2" name="photo[y2]" type="hidden" value="0" />
			<input id="w" name="photo[w]" type="hidden" value="0" />
			<input id="h" name="photo[h]" type="hidden" value="0" />
			<input id="w_o" name="photo[w_o]" type="hidden" value="0" />
			<input id="h_o" name="photo[h_o]" type="hidden" value="0" />

			<!-- Datos del participante -->
			<fieldset class="horizontal">
				<div class="control-group">
					<label class="control-label" for="nombre">Nombre completo:</label>
					<div class="controls">
						<input class="span2" id="nombre" name="nombre" type="text" required />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="app">Paterno:</label>
					<div class="controls">
						<input class="span2" id="app" name="app" type="text" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="apm">Materno:</label>
					<div class="controls">
						<input class="span2" id="apm" name="apm" type="text" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="pais">País:</label>
					<div class="controls">
						<select class="" id="pais" name="pais">
							<option value="">Seleccione:</option>
							{% for pais in paises %}
							<option value="{{ pais.pais_idPais }}"{{ pais.pais_idPais == 146 ? ' selected' : '' }}>{{ pais.pais_nombreEs }}</option>
							{% else %}
							<option value="">No se recuperaron los paises</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="control-group" style="display: none;">
					<label class="control-label" for="titulo">Titulo:</label>
					<div class="controls">
						<select class="span2" id="titulo" name="titulo">
							<option value="">Seleccione:</option>
							{% for titulo in titulos %}
							<option value="{{ titulo.ect_clave }}">{{ titulo.ect_clave }}</option>
							{% else %}
							<option value="">Aun no existen titulos para el evento</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="control-group" style="display: none;">
					<label class="control-label" for="rfc">RFC:</label>
					<div class="controls">
						<input class="span2" id="rfc" name="rfc" type="text" />
					</div>
				</div>
				<div class="control-group" style="display: none;">
					<label class="control-label" for="curp">CURP:</label>
					<div class="controls">
						<input class="span2" id="curp" name="curp" type="text" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="emp_o_ins">Empresa o Institución:</label>
					<div class="controls">
						<input class="span4" id="emp_o_ins" name="emp_o_ins" type="text" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="cargo">Puesto:</label>
					<div class="controls">
						<input class="span3" id="cargo" name="cargo" type="text" />
					</div>
				</div>
				<div class="control-group" style="display: none;">
					<label class="control-label" for="genero">Genero:</label>
					<div class="controls">
						<select class="span2" id="genero" name="genero">
							<option value="">Seleccione:</option>
							{% for genero in generos %}
							<option value="{{ genero.gen_clave }}">{{ genero.gen_nombre }}</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="control-group hide" style="display: none;">
					<label class="control-label" for="idioma">Idioma preferente:</label>
					<div class="controls">
						<select class="span2" id="idioma" name="idioma">
							<option value="">Seleccione:</option>
							{% for idioma in idiomas %}
							<option value="{{ idioma.eis_idioma }}"{{ idioma.eis_idioma == "es" ? ' selected' : '' }}>{{ idioma.eis_nombre }}</option>
							{% else %}
							<option value="">No hay idiomas</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="email">Correo electrónico:</label>
					<div class="controls">
						<input class="span3" id="email" name="email" type="email" />
					</div>
				</div>
				<div class="control-group" style="display: none;">
					<label class="control-label" for="clave_asociada">Clave:</label>
					<div class="controls">
						<input class="span3" id="clave_asociada" name="clave_asociada" type="text" />
					</div>
				</div>
				<div class="control-group" style="display: none;">
					<label class="control-label" for="id_tag">ID Tag: <span class="muted">{{ registro.id_tag.0 }}</span></label>
					<div class="controls">
						<input class="input-large" id="id_tag" name="id_tag" type="text" value="{{ registro.id_tag.1 }}" />
					</div>
					<span class="text-error"></span>
				</div>
			</fieldset>

			<!-- Datos del registro -->
			<fieldset class="horizontal">
				<div class="control-group">	
					<label class="control-label" for="cat_registro">Categoria (Tipo de registro):</label>
					<div class="controls">
						<select class="span2" id="cat_registro" name="cat_registro">
							<option value="">Seleccione:</option>
							{% for categoria in categorias %}
							<option value="{{ categoria.enc_clave }}">{{ categoria.enc_nombre }}</option>
							{% else %}
							<option value="">No hay categorias para el evento</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="control-group" style="display: none;">
					<label class="control-label" for="id_costo">Costo:</label>
					<div class="controls">
						<div class="input-append">
							<select class="span2" id="id_costos" name="id_costo">
								<option value="5">Seleccione:</option>
							</select>
						</div>
					</div>
				</div>
				<div class="control-group" style="display: none;">
					<label class="control-label" for="forma_pago">Forma de Pago:</label>
					<div class="controls">
						<select class="span2" id="forma_pagos" name="forma_pago">
							<option value="EF">Seleccione:</option>
							{% for formaPago in formasPago %}
							<option value="{{ formaPago.fpn_clave }}">{{ formaPago.fpn_nombre }}</option>
							{% else %}
							<option value="">Aun no hay formas de pago para este evento</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="status">Status:</label>
					<div class="controls">
						<select class="span2" id="statuss" name="status">
							<option value="COR">Seleccione:</option>
							{% for statusReg in statusRegs %}
							<option value="{{ statusReg.esr_clave }}"{{ statusReg.esr_clave == "PAG" ? ' selected' : '' }}>{{ statusReg.esr_nombre }}</option>
							{% else %}
							<option value="">No hay status de registro</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="control-group" style="display: none;">
					<label class="control-label" for="folio_pago">Folio de control:</label>
					<div class="controls">
						<input class="span2" id="folio_pago" name="folio_pago" type="text" value="{{ registro.folio_pago }}" />
					</div>
				</div>
				<div class="control-group" style="display: none;">
					<label class="control-label" for="tipo_cambio_divisa">Tipo de cambio:</label>
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on">$</span>
							<input class="input-mini" id="tipo_cambio_divisa" name="tipo_cambio_divisa" type="text" value="{{ evento.evt_tipoCambio }}" />
						</div>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="comentarios">Observaciones o Comentarios:</label>
					<div class="controls">
						<textarea class="span4" id="comentarios" name="comentarios" placeholder="..." rows="3"></textarea>
					</div>
				</div>
				<div class="control-group" style="display: none;">
					<label class="control-label" for="id_clon">Clonar información:</label>
					<div class="controls">
						<input class="span2" type="text" id="id_clon" name="id_clon" placeholder="ID Registro" />
					</div>
				</div>				
				<div class="control-group" id="module-photo" style="display: none;">
					<label class="control-label">Fotografia:</label>
					<a class="btn btn-info" data-load="modal" id="hacer-fotografia" href="{{ seccion }}/?action=fotoWeb">
						<i class="icon-picture icon-white"></i> Tomar fotografia
					</a>
					<a class="btn btn-info" data-load="modal" id="subir-fotografia" href="{{ seccion }}/?action=fotoUpload">
						<i class="icon-picture icon-white"></i> Subir fotografia
					</a>
				</div>
			</fieldset>

			<div class="acciones-form">
				<div class="form-error">&nbsp;</div>
				<div>
					<button class="btn btn-danger" id="cancelar-registro" type="button">
						<i class="icon-remove-sign icon-white"></i> Cancelar registro
					</button>
					<button class="btn btn-success" type="submit">
						<i class="icon-ok-sign icon-white"></i> Agregar registro
					</button>
				</div>
			</div>
		</form>
	</div>

	<!-- Datos de contacto -->
	<div id="datos-contacto-registro">
		<form action="{{ seccion }}/?action=agregarDatosContacto" data-load="ajax" method="post" name="datos-contacto">
			<input name="tabla_registros" type="hidden" value="{{ evento.evt_nombreTablaAsistentes }}" />
			<input name="clave_evento" type="hidden" value="{{ evento.evt_clave }}" />

			<fieldset class="horizontal">
				<div class="control-group">
					<label class="control-label" for="direccion">Dirección (Calle y número):</label>
					<div class="controls">
						<input class="span4" id="direccion" name="direccion" type="text" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="cp">Codigo postal:</label>
					<div class="controls">
						<input class="span1" id="cp" name="cp" type="text" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="colonia">Colonia:</label>
					<div class="controls">
						<input class="span2" id="colonia" name="colonia" type="text" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="del_o_mun">Delegación o municipio:</label>
					<div class="controls">
						<input class="span2" id="del_o_mun" name="del_o_mun" placeholder="Solo para México" type="text" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="ciudad">Ciudad:</label>
					<div class="controls">
						<input class="span2" id="ciudad" name="ciudad" type="text" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="estado">Estado:</label>
					<div class="controls">
						<input class="span2" id="estado" name="estado" type="text" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="pais">País:</label>
					<div class="controls">
						<select class="" id="pais" name="pais">
							<option value="">Seleccione:</option>
							{% for pais in paises %}
							<option value="{{ pais.pais_idPais }}"{{ pais.pais_idPais == 146 ? ' selected' : '' }}>{{ pais.pais_nombreEs }}</option>
							{% else %}
							<option value="">No se recuperaron los paises</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="lada_telefono">Lada:</label>
					<div class="controls">
						<input class="input-mini" id="lada_telefono" name="lada_telefono" type="text" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="telefono_particular">Teléfono particular:</label>
					<div class="controls">
						<input class="span2" id="telefono_particular" name="telefono_particular" type="text" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="telefono_movil">Teléfono movil:</label>
					<div class="controls">
						<input class="span2" id="telefono_movil" name="telefono_movil" type="text" />
					</div>
				</div>
			</fieldset>

			<div class="acciones-form">
				<div class="form-error">&nbsp;</div>
				<div>
					<button class="btn btn-primary" type="submit">
						<i class="icon-ok-sign icon-white"></i> Agregar
					</button>
				</div>
			</div>
		</form>
	</div>

	<!-- Datos de acompañantes -->
	<div id="datos-acompanantes-registro">
		<div style="position: fixed; left: 81.5%;">
			<a class="btn btn-large btn-info" href="#setAcompanante" role="button" data-toggle="modal">
				<i class="icon-plus-sign icon-white"></i> Agregar
			</a>
		</div>

		<form action="{{ seccion }}/?action=setAcompanante" id="setAcompanante" method="post" name="setAcompanante" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="acompananteAltaLabel" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3 id="acompananteAltaLabel">Agregar acompañante</h3>
			</div>
			<div class="modal-body">
				<fieldset class="horizontal">
					<div class="control-group">
						<label class="control-label" for="acm_clave">Tipo de acompañante</label>
						<div class="controls">
							<select class="{required:true}" name="acm_clave" required>
								<option value="">Seleccione:</option>
								{% for categoria in categoriasAcom %}
								<option value="{{ categoria.ecan_clave }}">{{ categoria.ecan_nombre }}</option>
								{% else %}
								<option value="">No hay categorias de acompañantes</option>
								{% endfor %}
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="acm_id_costo">Costo del acompañante:</label>
						<div class="controls">
							<select class="{required:true} span2" name="acm_id_costo" required>
								{% for key, costo in acompanante.costos %}
								<option value="{{ key }}">{{ costo.nombre }} - {{ costo.costo }}</option>
								{% endfor %}
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="acm_genero">Genero:</label>
						<div class="controls">
							<select name="acm_genero">
								<option value="">Seleccione:</option>
								{% for genero in generos %}
								<option value="{{ genero.gen_clave }}">{{ genero.gen_nombre }}</option>
								{% endfor %}
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="acm_status">Status:</label>
						<div class="controls">
							<select class="{required:true}" name="acm_status" required>
								<option value="">Seleccione:</option>
								{% for statusReg in statusRegs %}
								<option value="{{ statusReg.esr_clave }}">{{ statusReg.esr_nombre }}</option>
								{% endfor %}
							</select>
						</div>
					</div><br />
					<div class="control-group">
						<label class="control-label" for="acm_nombre">Nombre</label>
						<div>
							<input class="{required:true} span2" name="acm_nombre" type="text" required />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="acm_app">Apellido paterno</label>
						<div>
							<input class="{required:true} span2" name="acm_app" type="text" required />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="acm_apm">Apellido materno</label>
						<div>
							<input class="span2" name="acm_apm" type="text" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="acm_titulo">Titulo</label>
						<div class="controls">
							<select name="acm_titulo">
								<option value="">Seleccione:</option>
								{% for titulo in titulos %}
								<option value="{{ titulo.ect_clave }}">{{ titulo.ect_clave }}</option>
								{% else %}
								<option value="">Aun no existen titulos para el evento</option>
								{% endfor %}
							</select>
						</div>
					</div>
				</fieldset>
			</div>
			<div class="modal-footer">
				<div class="form-error">&nbsp;</div>
				<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
				<button class="btn btn-success" type="submit">
					<i class="icon-ok-sign icon-white"></i> Agregar
				</button>
			</div>
		</form>
	</div>

	<!-- Datos adicionales -->
	<div id="datos-adicionales-registro">
		<form action="{{ seccion }}/?action=agregarAdicionales" data-load="ajax" method="post" name="datos-adicionales">
			<input name="clave_evento" type="hidden" value="{{ evento.evt_clave }}" />
			<input id="num_item_act" name="num_item_act" type="hidden" value="2" />

			<fieldset class="horizontal">
				<div class="control-group">
					<label class="control-label" for="item_clave_1">Items:</label>
					<div class="controls">
						<select id="item_clave_1" name="item_clave_1">
							<option value="">Seleccione:</option>
							{% for item in items %}
							<option value="{{ item.eni_clave }}"{{ item.eci_paxMaximos != 0 and item.total_conf.filas >= item.eci_paxMaximos ? ' disabled' : '' }}>
								{{ item.eni_nombre }} {{ item.eci_paxMaximos != 0 and item.total_conf.filas >= item.eci_paxMaximos ? '- Agotado' : '' }}
							</option>
							{% else %}
							<option value="">No hay categorias de items</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="item_id_costo_1">Costo unitario:</label>
					<div class="controls">
						<select id="item_id_costo_1" name="item_id_costo_1" disabled>
							<option value="">Seleccione:</option>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="item_cantidad_1">Cantidad:</label>
					<div class="controls">
						<input autocomplete="on" class="span1" id="item_cantidad_1" name="item_cantidad_1" type="text" value="1" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="item_costo_total_1">Total:</label>
					<div class="controls">
						$ <input class="input-small" id="item_costo_total_1" name="item_costo_total_1" readonly type="text" value="0.00" />
					</div>
				</div>
			</fieldset>

			<div id="agr-items"></div>

			<div class="acciones-form">
				<div class="form-error">&nbsp;</div>
				<div>
					<a class="btn btn-info" data-id-form-item="2" href="{{ seccion }}/?action=formNuevoItem" id="nuevo-item">
						<i class="icon-plus-sign icon-white"></i> Agregar otro item
					</a>
					<button class="btn btn-warning" type="submit">
						<i class="icon-ok-sign icon-white"></i> Finalizar captura de items
					</button>
				</div>
			</div>
		</form>
	</div>

	<!-- Datos de facturación -->
	<div id="datos-facturacion-registro">
		<form action="{{ seccion }}/?action=agregarDatosFacturacion" data-load="ajax" method="post" name="datos-facturacion">
			<input name="tabla_registros" type="hidden" value="{{ evento.evt_nombreTablaAsistentes }}" />
			<input name="clave_evento" type="hidden" value="{{ evento.evt_clave }}" />

			<fieldset class="horizontal">
				<div class="control-group">
					<div class="controls">
						<label class="checkbox">
							<input id="copiar_datos_direccion" name="copiar_datos_direccion" type="checkbox" /> Copiar los datos de contacto
						</label>
					</div>
				</div>
			</fieldset>

			<fieldset class="horizontal">
				<div class="control-group">
					<label class="control-label" for="razon_social_RS">Razon social:</label>
					<div class="controls">
						<input class="span5" id="razon_social_RS" name="razon_social_RS" type="text" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="rfc_RS">RFC:</label>
					<div class="controls">
						<input class="span3" id="rfc_RS" name="rfc_RS" placeholder="Solo para México" type="text" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="direccion_RS">Dirección (Calle y número):</label>
					<div class="controls">
						<input class="span4" id="direccion_RS" name="direccion_RS" type="text" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="cp_RS">Codigo postal:</label>
					<div class="controls">
						<input class="span1" id="cp_RS" name="cp_RS" type="text" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="colonia_RS">Colonia:</label>
					<div class="controls">
						<input class="span2" id="colonia_RS" name="colonia_RS" type="text" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="del_o_mun_RS">Delegación o municipio:</label>
					<div class="controls">
						<input class="span2" id="del_o_mun_RS" name="del_o_mun_RS" placeholder="Solo para México" type="text" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="ciudad_RS">Ciudad:</label>
					<div class="controls">
						<input class="span2" id="ciudad_RS" name="ciudad_RS" type="text" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="estado_RS">Estado:</label>
					<div class="controls">
						<input class="span2" id="estado_RS" name="estado_RS" type="text" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="pais_RS">País:</label>
					<div class="controls">
						<select class="" id="pais_RS" name="pais_RS">
							<option value="">Seleccione:</option>
							{% for pais in paises %}
							<option value="{{ pais.pais_idPais }}"{{ pais.pais_idPais == 146 ? ' selected' : '' }}>{{ pais.pais_nombreEs }}</option>
							{% else %}
							<option value="">No se recuperaron los paises</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="email_RS">Correo electrónico para facturación electrónica:</label>
					<div class="controls">
						<input class="span4" id="email_RS" name="email_RS" type="email" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="lada_telefono_RS">Lada:</label>
					<div class="controls">
						<input class="input-mini" id="lada_telefono_RS" name="lada_telefono_RS" type="text" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="telefono_RS">Teléfono:</label>
					<div class="controls">
						<input class="span2" id="telefono_RS" name="telefono_RS" type="text" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="fax_RS">Fax:</label>
					<div class="controls">
						<input class="span2" id="fax_RS" name="fax_RS" type="text" />
					</div>
				</div>
			</fieldset>

			<div class="acciones-form">
				<div class="form-error">&nbsp;</div>
				<div>
					<button class="btn btn-inverse" type="submit">
						<i class="icon-ok-sign icon-white"></i> Agregar
					</button>
				</div>
			</div>
		</form>
	</div>

	<!-- Datos de hospedaje -->
	<div id="datos-hospedaje-registro">
		<form action="{{ seccion }}/?action=setHospedaje" id="setHospedaje" method="post" name="setHospedaje">
			<input type="hidden" name="id_reservacion" value="{{ reservacion.id_reservacion }}" />
			<input type="hidden" id="maxAdultos" name="maxAdultos" value="0" />
			<input type="hidden" id="maxMenores" name="maxMenores" value="0" />

			<fieldset class="horizontal">
				<div class="control-group">
					<label class="control-label" for="res_idHotel">Elija un hotel:</label>
					<div class="controls">
						<select id="res_idHotel" name="res_idHotel" required>
							<option value="">Seleccione:</option>
							{% for hotel in hoteles %}
							<option value="{{ hotel.htl_id }}">{{ hotel.htl_nombre }}</option>
							{% endfor %}
						</select>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="res_idHabitacion">Elija el tipo de habitación:</label>
					<div class="controls">
						<select id="res_idHabitacion" name="res_idHabitacion" required>
							<option value="">Seleccione:</option>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="res_llegada">Fecha de llegada:</label>
					<div class="controls">
						<input class="input-small" id="res_llegada" name="res_llegada" readonly type="text" value="" required />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="res_salida">Fecha de salida:</label>
					<div class="controls">
						<input class="input-small" id="res_salida" name="res_salida" readonly type="text" value="" required />
					</div>
				</div>
			</fieldset>

			<fieldset class="horizontal">
				<div class="control-group">
					<label class="control-label">Costo por noche adulto:</label>
					<div class="controls" id="costo-adulto">0.00</div>
				</div>
				<div class="control-group">
					<label class="control-label">Costo por noche menor:</label>
					<div class="controls" id="costo-menor">0.00</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="res_numHabitaciones">Número de habitaciones:</label>
					<div class="controls">
						<select class="span2" id="res_numHabitaciones" name="res_numHabitaciones" required>
							<option value="">Seleccione:</option>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label titulo-pax-cuartos">Ocupación&nbsp;&nbsp;&nbsp;&nbsp;<strong>Adultos</strong>&nbsp;<strong>Menores</strong></label>
					<div class="controls" id="pax-habitaciones">&nbsp;</div>
				</div>
			</fieldset>

			<fieldset class="horizontal">
				<div class="control-group">
					<label class="control-label" for="res_numHabitaciones">Deja anticipo:</label>
					<div class="controls">
						<input class="span2" name="res_anticipo" type="text" placeholder="0.00" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="res_formaPago">Forma de pago:</label>
					<div class="controls">
						<select class="span2" name="res_formaPago" id="res_formaPago" required>
							<option value="">Seleccione:</option>
							{% for formaPago in formasPago %}
							<option value="{{ formaPago.fpn_clave }}">{{ formaPago.fpn_nombre }}</option>
							{% else %}
							<option value="">Aun no hay formas de pago para este evento</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="res_status">Status:</label>
					<div class="controls">
						<select class="span2" name="res_status" id="res_status" required>
							<option value="">Seleccione:</option>
							{% for statusReg in statusRegs %}
							<option value="{{ statusReg.esr_clave }}">{{ statusReg.esr_nombre }}</option>
							{% else %}
							<option value="">No hay status de registro</option>
							{% endfor %}
						</select>
					</div>
				</div>
			</fieldset>

			<div class="form-error">&nbsp;</div>
			<button class="btn btn-success" type="submit">
				<i class="icon-ok-sign icon-white"></i> Agregar
			</button>
		</form>
	</div>

	<script>
	$(function() {
		// Asignamos costos de los acompañantes dependiendo de la categoria
		$("body").on({
			change: function() {
				var $input = $(this),
					clave = $input.val();

				$.ajax({
					type: "POST",
					url: "{{ seccion }}/?action=listaCostosCategoriaAcom",
					data: "cve=" + clave,
					success: function(sRes) {
						$('#acm_id_costo').html(sRes).removeAttr("disabled").focus();
					}
				});
			}
		}, "#acm_clave");

		// clona un registro
		$("body").on({
			keyup: function() {
				var $id_registro = $(this).val(),
					$clave_evento = $('#clave_evento').val();

				$.ajax({
					type: "GET",
					url: "{{ seccion }}/?action=clonarRegistro",
					data: {id_registro: $id_registro, clave_evento: $clave_evento},
					dataType: 'json',
					success: function(sRes) {
						//$('#nombre').val(sRes.registro.nombre);
						//$('#app').val(sRes.registro.app);
						//$('#apm').val(sRes.registro.apm);
						$('#rfc').val(sRes.registro.rfc);
						$('#curp').val(sRes.registro.curp);
						$('#cargo').val(sRes.registro.cargo);
						$('#emp_o_ins').val(sRes.registro.emp_o_ins);
						$('#email').val(sRes.registro.email);
						$('#clave_asociada').val(sRes.registro.clave_asociada);
						$('#comentarios').val(sRes.registro.comentarios);
						$('#cat_registro option[value="' + sRes.registro.cat_registro + '"]').attr('selected', 'selected');
						$('#id_costo option[value="' + sRes.registro.id_costo + '"]').attr('selected', 'selected');
						$('#forma_pago option[value="' + sRes.registro.forma_pago + '"]').attr('selected', 'selected');
						$('#status option[value="' + sRes.registro.status + '"]').attr('selected', 'selected');

					}
				});
			}
		}, "#id_clon");

		/**
		 * AGREGAR ACOMPANANTE
		 */
		$('#setAcompanante').validate({
			submitHandler: function(form) {
				var _form = $(form),
					_action = _form.attr('action'),
					queryString = $(form).serialize();

				$.ajax({
					type: 'POST',
					url: _action,
					data: queryString,
					dataType: 'json',
					beforeSend: function() {
						_form.find('button').attr('disabled', 'disabled');
						_form.find('.form-error').html('&nbsp;');
					},
					success: function(sRes) {
							nuevo = $('#setAcompanante > .modal-body').html();
							mostrar = ' hide';

							if (sRes.acomp.acm_status == "PAG" || sRes.acomp.acm_status == "COR" || sRes.acomp.acm_status == "CCC") {
								mostrar = '';
							}

							botones = '<div class="acciones-form">' +
								'<div class="form-error"></div>' +
								'<div>' +
									'<button class="btn btn-mini btn-primary" type="submit">' +
										'<i class="icon-ok-sign icon-white"></i> Actualizar' +
									'</button> ' +
									'<a class="btn btn-mini btn-danger" data-load="accion" href="{{ seccion }}/?action=confEliminarAcom&amp;id=' + sRes.acomp.id_acompanante + '">' +
										'<i class="icon-remove-sign icon-white"></i> Eliminar' +
									'</a> ' +
									'<span class="imp-acompanante' + mostrar + '">' +
										'<a class="btn btn-mini btn-success" data-load="accion" href="{{ seccion }}/?action=impresionGafeteAcom&amp;id=' + sRes.acomp.id_acompanante + '">' +
											'<i class="icon-barcode icon-white"></i> Imprimir Gafete' +
										'</a>' +
									'</span>' + 
								'</div>' +
								'<input type="hidden" name="id_acompanante" value="' + sRes.acomp.id_acompanante + '" />' +
							'</div>';

							$('#setAcompanante').modal('hide');

							update_form = $('<form />', {
								html: nuevo,
								action: '{{ seccion }}/?action=updateAcompanante',
								'class': 'updateAcompanante',
								id: 'updateAcompanante_' + sRes.acomp.id_acompanante,
								method: 'post',
								name: 'updateAcompanante_' + sRes.acomp.id_acompanante
							});

							update_form.append(botones);

							$('#datos-acompanantes-registro').append(update_form);

							update_form.find("select[name='acm_clave'] option[value='" + sRes.acomp.acm_clave + "']").attr("selected", "selected");
							update_form.find("select[name='acm_id_costo'] option[value='" + sRes.acomp.acm_id_costo + "']").attr("selected", "selected");
							update_form.find("select[name='acm_genero'] option[value='" + sRes.acomp.acm_genero + "']").attr("selected", "selected");
							update_form.find("select[name='acm_titulo'] option[value='" + sRes.acomp.acm_titulo + "']").attr("selected", "selected");
							update_form.find("select[name='acm_status'] option[value='" + sRes.acomp.acm_status + "']").attr("selected", "selected");
							update_form.find("input[name='acm_nombre']").val(sRes.acomp.acm_nombre);
							update_form.find("input[name='acm_app']").val(sRes.acomp.acm_app);
							update_form.find("input[name='acm_apm']").val(sRes.acomp.acm_apm);
					}
				}).always(function() {
					_form.find('button').removeAttr('disabled');
				});

				return false;
			},
			errorPlacement: function(error, element) {
				error.appendTo(element.parent().append());
			}
		});

		// Asignamos costos de los acompañantes dependiendo de la categoria
		$("body").on({
			change: function() {
				var $input = $(this),
					clave = $input.val(),
					_form = $input.parent().parent().parent();

				$.ajax({
					type: "POST",
					url: "{{ seccion }}/?action=listaCostosCategoriaAcom",
					data: "cve=" + clave,
					success: function(sRes) {
						_form.find('select[name="acm_id_costo"]').html(sRes).removeAttr("disabled").focus();
					}
				});
			}
		}, 'select[id^="acm_clave_"]');

		/**
		 * AGREGAR ACOMPANANTE
		 */
		/*$('.updateAcompanante').each(function() {
			$(this).validate({
				submitHandler: function(form) {
					var _form = $(form),
						_action = _form.attr('action'),
						queryString = $(form).serialize();

					$.ajax({
						type: 'POST',
						url: _action,
						data: queryString,
						dataType: 'json',
						beforeSend: function() {
							_form.find('button').attr('disabled', 'disabled');
							_form.find('.form-error').html('&nbsp;').show();
						},
						success: function(sRes) {
							_form.find('.form-error').html(sRes.mensaje);
							if (sRes.status) {
								btn_gafete = $('#updateAcompanante_' + sRes.acom.id_acompanante).find('.imp-acompanante');
								if (sRes.acom.acm_status == 'PAG' || sRes.acom.acm_status == 'COR' || sRes.acom.acm_status == 'CCC') {
									btn_gafete.show();
								} else {
									btn_gafete.hide();
								}
							}
							setTimeout(function() {
								_form.find('.form-error').fadeOut('slow');
							}, 3500);
						}
					}).always(function() {
						_form.find('button').removeAttr('disabled');
					});

					return false;
				},
				errorPlacement: function(error, element) {
					error.appendTo(element.parent().append());
				}
			});
		});*/

		//$('.updateAcompanante').each(function() {
			$('body').on('click', '.updateAcompanante', function() {
				$(this).validate({
					submitHandler: function(form) {
						var _form = $(form),
							_action = _form.attr('action'),
							queryString = $(form).serialize();

						$.ajax({
							type: 'POST',
							url: _action,
							data: queryString,
							dataType: 'json',
							beforeSend: function() {
								_form.find('button').attr('disabled', 'disabled');
								_form.find('.form-error').html('&nbsp;').show();
							},
							success: function(sRes) {
								_form.find('.form-error').html(sRes.mensaje);
								if (sRes.status) {
									btn_gafete = $('#updateAcompanante_' + sRes.acom.id_acompanante).find('.imp-acompanante');
									if (sRes.acom.acm_status == 'PAG' || sRes.acom.acm_status == 'COR' || sRes.acom.acm_status == 'CCC') {
										btn_gafete.show();
									} else {
										btn_gafete.hide();
									}
								}
								setTimeout(function() {
									_form.find('.form-error').fadeOut('slow');
								}, 3500);
							}
						}).always(function() {
							_form.find('button').removeAttr('disabled');
						});

						return false;
					},
					errorPlacement: function(error, element) {
						error.appendTo(element.parent().append());
					}
				});
			});
		//});

		// Asignamos costos de los acompañantes dependiendo de la categoria
		$("body").on({
			keyup: function() {
				var $input = $(this),
					clave = $input.val();

				$.ajax({
					type: "POST",
					url: "{{ seccion }}/?action=verificarFolioPago",
					data: {folio_pago: clave},
					dataType: 'json',
					success: function(sRes) {
						if (sRes.status) {
							$('#validarRegistro').find('button[type="submit"]').attr("disabled", "disabled");
							$('#validarRegistro').find('.form-error').text('Error: otro registro ya tiene este folio asignado!');
						} else {
							$('#validarRegistro').find('button[type="submit"]').removeAttr("disabled");
							$('#validarRegistro').find('.form-error').html('&nbsp;');
						}
					}
				});
			}
		}, "#folio_pago");

		// Fecha de llegada
		$('#res_llegada').datepicker({
			minDate: new Date('{{ evento.evt_inicio|date("Y") }}', '{{ evento.evt_inicio|date("m") }}' - 1, '{{ evento.evt_inicio|date("d") }}' - 1),
			maxDate: new Date('{{ evento.evt_termina|date("Y") }}', '{{ evento.evt_termina|date("m") }}' - 1, parseInt('{{ evento.evt_termina|date("d") }}') + 1),
			beforeShow: function (e) {
				$llegada = $('#res_salida').val();

				if ($llegada) {
					var $day = $llegada.split('/');

					$(e).datepicker(
						'option', 'maxDate', new Date($day[2], $day[1] - 1, parseInt($day[0]) - 1)
					);
				}
			}
		});

		// Fecha de salida
		$('#res_salida').datepicker({
			minDate: new Date('{{ evento.evt_inicio|date("Y") }}', '{{ evento.evt_inicio|date("m") }}' - 1, '{{ evento.evt_inicio|date("d") }}' - 1),
			maxDate: new Date('{{ evento.evt_termina|date("Y") }}', '{{ evento.evt_termina|date("m") }}' - 1, parseInt('{{ evento.evt_termina|date("d") }}') + 1),
			beforeShow: function (e) {
				$llegada = $('#res_llegada').val();

				if ($llegada) {
					var $day = $llegada.split('/');

					$(e).datepicker(
						'option', 'minDate', new Date($day[2], $day[1] - 1, parseInt($day[0]) + 1)
					);
				}
			}
		});

		// Obtener habitaciones del hotel
		$('#res_idHotel').change(function() {
			$.ajax({
				type: 'GET',
				url: '{{ seccion }}/?action=getHabitaciones',
				dataType: 'json',
				data: {id_hotel: $(this).val()},
				success: function(sRes) {
					habitaciones = sRes.habitaciones;
					$options = '<option value="">Seleccione:</option>';

					for (i in habitaciones) {
						$options += '<option value="' + habitaciones[i].hhn_idHabitacion + '">' + habitaciones[i].hhn_nombre + '</option>';
					}

					$('#res_idHabitacion').html($options);
					$('#costo-adulto').html('<span>0.00</span>');
					$('#costo-menor').html('<span>0.00</span>');
				}
			});
		}); // Habitaciones del hotel

		// Obtener especificaciones de la habitación elegida
		$('#res_idHabitacion').change(function() {
			$.ajax({
				type: 'GET',
				url: '{{ seccion }}/?action=getEspecHabitacion',
				dataType: 'json',
				data: {id_hotel: $('#res_idHotel').val(), id_habitacion: $(this).val()},
				success: function(sRes) {
					costo_adulto = sRes.costo_adulto;
					costo_menor = sRes.costo_menor;
					pax_cuartos = sRes.pax_cuartos;
					$('#maxAdultos').val(sRes.pax_adultos);
					$('#maxMenores').val(sRes.pax_menores);

					adulto = '<span>' + costo_adulto + '</span>';
					menor = '<span>' + costo_menor + '</span>';
					$optionsHab = '<option value="">Seleccione:</option>';

					/**
					 * Lista de costos
					for (i in costos) {
						$options += '<label class="radio">';
							$options += '<input id="res_idCostos" name="res_idCostos" type="radio" value="' + costos[i].hhc_id + '" />';
							$options += 'Adultos: ' + costos[i].hhc_costoAdulto;
							$options += ' - Menores: ' + costos[i].hhc_costoMenor;
						$options += '</label>';
					}*/

					//$options += '&nbsp;';

					if (pax_cuartos > 0) {
						for ( i = 1 ; i <= pax_cuartos ; i++ ) {
							$optionsHab += '<option value="' + i + '">' + i +'</option>';
						}
						$('#res_numHabitaciones').html($optionsHab);
					} else {
						$('#res_numHabitaciones').html('<option value="">Seleccione:</option>');
					}

					$('#costo-adulto').html(adulto);
					$('#costo-menor').html(menor);
				}
			});
		}); // Especificaciones de la habitacion elegida

		// Mostrar el número de cuartos a reservar
		$('body').on('change', '#res_numHabitaciones', function() {
			$habitaciones = $(this).val();
			maxAdultos = $('#maxAdultos').val();
			maxMenores = $('#maxMenores').val();
			$cuartos = '';

			for ( i = 1 ; i <= $habitaciones ; i++ ) {
				$cuartos += '<div class="pax-cuartos">';
						$cuartos += 'Habitación ' + i + ': <select class="input-mini" name="habitacion[adultos][' + i + ']" required>';
						for ( j = 1 ; j <= maxAdultos ; j++) {
							$cuartos += '<option value="' + j + '">' + j + '</option>';
						}
						$cuartos += '</select>';

						$cuartos += '<select class="input-mini" name="habitacion[menores][' + i + ']" required>';
						for ( j = 0 ; j <= maxMenores ; j++) {
							$cuartos += '<option value="' + j + '">' + j + '</option>';
						}
						$cuartos += '</select>';
				$cuartos += '</div>';
			}

			$('#pax-habitaciones').html($cuartos);
		}); // Número de habitaciones a reservar

		/**
		 * AGREGAR HOSPEDAJE
		 */
		$('#setHospedaje').validate({
			submitHandler: function(form) {
				var _form = $(form),
					_action = _form.attr('action'),
					queryString = $(form).serialize();

				$.ajax({
					type: 'POST',
					url: _action,
					data: queryString,
					dataType: 'json',
					beforeSend: function() {
						_form.find('button').attr('disabled', 'disabled');
						_form.find('.form-error').html('&nbsp;');
					},
					success: function(sRes) {
						_form.find('.form-error').html(sRes.mensaje);
						if (sRes.status) {
							_form.attr({
								action: '{{ seccion }}/?action=setUpdateHospedaje',
								id: 'setUpdateHospedaje',
								name: 'setUpdateHospedaje'
							});
							if (sRes.reservacion) {
								$('input[name="id_reservacion"]').val(sRes.reservacion);
							}
							_form.find('button[type="submit"]').html('<i class="icon-ok-sign icon-white"></i> Actualizar');
						} else {

						}
					}
				}).always(function() {
					_form.find('button').removeAttr('disabled');
				});

				return false;
			},
			errorPlacement: function(error, element) {
				error.appendTo(element.parent().append());
			}
		});

		/**
		 * UPDATE HOSPEDAJE
		 */
		$('#setUpdateHospedaje').validate({
			submitHandler: function(form) {
				var _form = $(form),
					_action = _form.attr('action'),
					queryString = $(form).serialize();

				$.ajax({
					type: 'POST',
					url: _action,
					data: queryString,
					dataType: 'json',
					beforeSend: function() {
						_form.find('button').attr('disabled', 'disabled');
						_form.find('.form-error').html('&nbsp;');
					},
					success: function(sRes) {
						_form.find('.form-error').html(sRes.mensaje);
					}
				}).always(function() {
					_form.find('button').removeAttr('disabled');
				});

				return false;
			},
			errorPlacement: function(error, element) {
				error.appendTo(element.parent().append());
			}
		});

	$('input[name="transportacion_santafe"]').click(function() {
		if ($(this).val() == 'No') {
			$('.tipo_servicio').addClass('hidden');
			$('input[name="tipo_servicio"]').attr('disabled', 'disabled').removeAttr('checked').prop('checked', false);

			$('.llevaras_auto').removeClass('hidden');
			$('input[name="llevaras_auto"]').removeAttr('disabled');
	 	} else {
			$('.llevaras_auto').addClass('hidden');
			$('input[name="llevaras_auto"]').attr('disabled', 'disabled').removeAttr('checked').prop('checked', false);

			$('.tipo_servicio').removeClass('hidden');
			$('input[name="tipo_servicio"]').removeAttr('disabled');
		}

		$('.traslado_hacia_bancomer, .traslado_de_bancomer').addClass('hidden');
		$('input[name="traslado_hacia_bancomer"], input[name="traslado_de_bancomer"]').attr('disabled', 'disabled');
		$('input[name="hora_salida"]').attr('disabled','disabled').removeAttr('checked').prop('checked', false);
	});

	$('input[name="llevaras_auto"]').click(function() {
		if ($(this).val() == 'No') {
			//$('.tipo_servicio').removeClass('hidden');
			//$('input[name="tipo_servicio"]').removeAttr('disabled');
	 	} else {
			$('.tipo_servicio').addClass('hidden');
			$('input[name="tipo_servicio"]').attr('disabled', 'disabled').prop('checked', false);
		}

		$('.traslado_hacia_bancomer, .traslado_de_bancomer').addClass('hidden');
		$('input[name="traslado_hacia_bancomer"], input[name="traslado_de_bancomer"]').attr('disabled', 'disabled');
		$('input[name="hora_salida"]').attr('disabled','disabled').removeAttr('checked').prop('checked', false);
	});

	$('input[name="tipo_servicio"]').click(function() {
		if ($(this).val() === 'Sólo llegada a Expo Bancomer') {
			$('.traslado_hacia_bancomer').removeClass('hidden');
			$('#traslado_hacia_bancomer').removeAttr('disabled');
			$('.traslado_de_bancomer').addClass('hidden');
			$('#traslado_de_bancomer').attr('disabled', 'disabled').val('');
			$('input[name="hora_salida"]').attr('disabled','disabled').removeAttr('checked').prop('checked', false);
		} else if ($(this).val() === 'Ambos servicios') {
			$('.traslado_hacia_bancomer').removeClass('hidden');
			$('#traslado_hacia_bancomer').removeAttr('disabled');
			$('.traslado_de_bancomer').removeClass('hidden');
			$('#traslado_de_bancomer').removeAttr('disabled');
			$('input[name="hora_salida"]').removeAttr('disabled');
		} else {
			$('.traslado_de_bancomer').removeClass('hidden');
			$('#traslado_de_bancomer').removeAttr('disabled');
			$('.traslado_hacia_bancomer').addClass('hidden');
			$('#traslado_hacia_bancomer').attr('disabled', 'disabled').val('');
			$('input[name="hora_salida"]').removeAttr('disabled');
		}
	});

	});
	</script>
</div>