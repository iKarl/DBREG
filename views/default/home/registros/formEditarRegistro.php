{#
<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/home/registros/
 * @version $Id: formEditarRegistro.php 1.0 2012-06-04 22:28 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}
<div id="editar-registro">
	<div class="barra-principal-registro">
		<span class="costo-total"><strong>ID:</strong> {{ registro.id_registro }}</span> |
		<div class="opciones-registro">
			<button class="btn btn-danger" id="fin-actualizar-reg" type="button" value="{{ registro.id_registro }}">
				<i class="icon-ok-sign icon-white"></i> Finalizar
			</button>
			<button class="btn" data-show-form="generales" type="button">
				<i class="icon-user"></i> Datos generales
			</button>
			<!--<button class="btn btn-default" id="salon" type="button" onclick="window.open('http://localhost/Dropbox/proyectosHome/cena/?id_registro={{ registro.id_registro }}', '_blank');">
				<i class="icon-tag"></i> Cena
			</button>-->
			<button class="btn btn-primary" data-show-form="contacto" type="button">
				<i class="icon-envelope icon-white"></i> Datos de contacto
			</button>
			<button class="btn btn-info" data-show-form="acompanantes" type="button">
				<i class="icon-th-list icon-white"></i> Acompañantes
			</button>
			<button class="btn btn-warning" data-show-form="adicionales" type="button">
				<i class="icon-shopping-cart icon-white"></i> Adicionales
			</button>
			<button class="btn btn-inverse" data-show-form="facturacion" type="button">
				<i class="icon-inbox icon-white"></i> Datos de Facturación
			</button>
			<button class="btn btn-danger" data-show-form="hospedaje" type="button">
				<i class="icon-briefcase icon-white"></i> Hospedaje
			</button>
		</div>
	</div>

	<!-- Datos generales -->
	<div id="datos-generales-registro">
		<form action="{{ seccion }}/?action=actualizarRegistro" data-load="ajax" method="post" name="datos-generales">
			<input name="id_registro" type="hidden" value="{{ registro.id_registro }}" />
			<input name="tabla_registros" type="hidden" value="{{ evento.evt_nombreTablaAsistentes }}" />
			<input name="divisa" type="hidden" value="{{ evento.evt_divisa }}" />

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
					<label class="control-label" for="nombre">Nombre(s):</label>
					<div class="controls">
						<input class="span2" id="nombre" name="nombre" type="text" value="{{ registro.nombre }}" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="app">Paterno:</label>
					<div class="controls">
						<input class="span2" id="app" name="app" type="text" value="{{ registro.app }}" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="apm">Materno:</label>
					<div class="controls">
						<input class="span2" id="apm" name="apm" type="text" value="{{ registro.apm }}" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="pais">País:</label>
					<div class="controls">
						<select class="" id="pais" name="pais">
							<option value="">Seleccione:</option>
							{% for pais in paises %}
							<option value="{{ pais.pais_idPais }}"

							{% if registro.pais %}
								{% if registro.pais == pais.pais_idPais %}
									 selected
								{% endif %}
							{% else %}
								{% if pais.pais_idPais == 146 %}
									selected
								{% endif %}
							{% endif %}

							>{{ pais.pais_nombreEs }}</option>
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
							<option value="{{ titulo.ect_clave }}"{{ registro.titulo == titulo.ect_clave ? ' selected' : '' }}>{{ titulo.ect_clave }}</option>
							{% else %}
							<option value="">Aun no existen titulos para el evento</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="control-group" style="display: none;">
					<label class="control-label" for="rfc">RFC:</label>
					<div class="controls">
						<input class="span2" id="rfc" name="rfc" type="text" value="{{ registro.rfc }}" />
					</div>
				</div>
				<div class="control-group" style="display: none;">
					<label class="control-label" for="curp">CURP:</label>
					<div class="controls">
						<input class="span2" id="curp" name="curp" type="text" value="{{ registro.curp }}" />
					</div>
				</div>
				<div class="control-group" style="display: none;">
					<label class="control-label" for="genero">Genero:</label>
					<div class="controls">
						<select class="span2" id="genero" name="genero">
							<option value="">Seleccione:</option>
							{% for genero in generos %}
							<option value="{{ genero.gen_clave }}"{{ registro.genero == genero.gen_clave ? ' selected' : '' }}>{{ genero.gen_nombre }}</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="control-group" style="display: none;">
					<label class="control-label" for="idioma">Idioma preferente:</label>
					<div class="controls">
						<select class="span2" id="idioma" name="idioma">
							<option value="">Seleccione:</option>
							{% for idioma in idiomas %}
							<option value="{{ idioma.eis_idioma }}"{{ registro.idioma == idioma.eis_idioma ? ' selected' : '' }}>{{ idioma.eis_nombre }}</option>
							{% else %}
							<option value="">No hay idiomas</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="emp_o_ins">Empresa o Institución:</label>
					<div class="controls">
						<input class="span4" id="emp_o_ins" name="emp_o_ins" type="text"  value="{{ registro.emp_o_ins }}" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="cargo">Cargo o Puesto:</label>
					<div class="controls">
						<input class="span2" id="cargo" name="cargo" type="text" value="{{ registro.cargo }}" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="email">Correo electrónico:</label>
					<div class="controls">
						<input class="span3" id="email" name="email" type="email" value="{{ registro.email }}" />
					</div>
				</div>
				<div class="control-group" style="display: none;">
					<label class="control-label" for="clave_asociada">Clave:</label>
					<div class="controls">
						<input class="span3" id="clave_asociada" name="clave_asociada" type="text" value="{{ registro.clave_asociada }}" />
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
							<option value="{{ categoria.enc_clave }}"{{ registro.cat_registro == categoria.enc_clave ? ' selected' : '' }}>{{ categoria.enc_nombre }}</option>
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
								<option value="">Seleccione:</option>
								{% for key, costoCategoria in costosCategoria %}
								<option value="{{ key }}"{{ registro.id_costo == key ? ' selected' : '' }}>{{ costoCategoria.nombre }} - {{ costoCategoria.costo }}</option>
								{% else %}
								<option value="">No hay categorias para el evento</option>
								{% endfor %}
							</select>
						</div>
					</div>
				</div>
				<div class="control-group" style="display: none;">
					<label class="control-label" for="forma_pago">Forma de Pago:</label>
					<div class="controls">
						<select class="span2" id="forma_pagos" name="forma_pago">
							<option value="">Seleccione:</option>
							{% for formaPago in formasPago %}
							<option value="{{ formaPago.fpn_clave }}"{{ registro.forma_pago == formaPago.fpn_clave ? ' selected' : '' }}>{{ formaPago.fpn_nombre }}</option>
							{% else %}
							<option value="">Aun no hay formas de pago para este evento</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="status">Status:</label>
					<div class="controls">
						<select class="span2" id="status" name="status">
							<option value="">Seleccione:</option>
							{% for statusReg in statusRegs %}
							<option value="{{ statusReg.esr_clave }}"{{ registro.status == statusReg.esr_clave ? ' selected' : '' }}>{{ statusReg.esr_nombre }}</option>
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
				<!--<div class="control-group">
					<label class="control-label" for="costo_registro">Costo del registro:</label>
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on">$</span>
							<input class="input-small" id="costo_registro" name="costo_registro" readonly type="text" value="{{ costoRegistro }}" />
						</div>
					</div>
				</div>-->
				<div class="control-group">
					<label class="control-label" for="comentarios">Observaciones o Comentarios:</label>
					<div class="controls">
						<textarea class="span4" id="comentarios" name="comentarios" placeholder="..." rows="3">{{ registro.comentarios }}</textarea>
					</div>
				</div>
				<div class="control-group" id="module-photo">
					<label class="control-label">Fotografia:</label>
				
					<a class="btn btn-info" data-load="modal" id="hacer-fotografia" href="{{ seccion }}/?action=fotoWeb" {{ (registro.foto_fotografia != '') ? ' style="display: none;"' : '' }}>
						<i class="icon-picture icon-white"></i> Tomar fotografia
					</a>
					<a class="btn btn-info" data-load="modal" id="subir-fotografia" href="{{ seccion }}/?action=fotoUpload" {{ (registro.foto_fotografia != '') ? ' style="display: none;"' : '' }}>
						<i class="icon-picture icon-white	"></i> Subir fotografia
					</a>

					{% if (registro.foto_fotografia != '') %}
					<a class="btn btn-danger" data-load="accion" id="delete-photo-saved" href="{{ seccion }}/?action=eliminarFotografia&amp;id={{ registro.id_registro }}">
						<i class="icon-remove icon-white"></i> Eliminar foto
					</a>
					{% endif %}
				</div>
				<div class="control-group" style="display: none;">
					<label for="costo_total" class="control-label text-error lead"><strong>Total:</strong></label>
					<input type="text" class="span2" name="costo_total" id="costo_total" value="{{ registro.costo_total|number_format(2, '.', ',') }}" style="font-size: 1.2em;" /><br />
					<label for="numero_factura" class="control-label text-error lead"><strong>No. Factura:</strong></label>
					<input type="text" class="span2" name="numero_factura" id="numero_factura" value="{{ registro.numero_factura }}" style="font-size: 1.2em;" />
				</div>
			</fieldset>

			<div class="acciones-form">
				<div class="form-error">&nbsp;</div>
				<div>
					<button class="btn btn-success" id="actualizarRegistro" type="submit">
						<i class="icon-ok-sign icon-white"></i> Actualizar
					</button>
					<span id="btns-imp-docs">
					{% if registro.status in ["PAG","COR","CCC"]  %}
					<a class="btn btn-success" data-load="accion" href="{{ seccion }}/?action=impresionGafete&amp;id={{ registro.id_registro }}">
						<i class="icon-barcode icon-white"></i> Imprimir Gafete
						{{ registro.impresion_gafete ? '<i class="icon-ok-circle icon-white"></i>' : '' }}
					</a>
					<a class="btn btn-success" data-load="accion" href="{{ seccion }}/?action=impresionConstancia&amp;id={{ registro.id_registro }}">
						<i class="icon-qrcode icon-white"></i> Imprimir Constancia
						{{ registro.impresion_constancia ? '<i class="icon-ok-circle icon-white"></i>' : '' }}
					</a>
					{% endif %}
					</span>
					{% if registro.status == "CCC" or registro.forma_pago == 'CC' %}
					<a class="btn btn-success" id="imprimirCartaCompromiso" target="_blank" href="{{ seccion }}/?action=impresionCarta&amp;id={{ registro.id_registro }}">
						<i class="icon-print icon-white"></i> Carta compromiso
					</a>
					{% endif %}
				</div>
			</div>
		</form>
	</div>

	<!-- Datos de contacto -->
	<div id="datos-contacto-registro">
		<form action="{{ seccion }}/?action=actualizarDatosContacto" data-load="ajax" method="post" name="datos-contacto">
			<input name="id_registro" type="hidden" value="{{ registro.id_registro }}" />
			<input name="tabla_registros" type="hidden" value="{{ evento.evt_nombreTablaAsistentes }}" />

			<fieldset class="horizontal">
				<div class="control-group">
					<label class="control-label" for="direccion">Dirección (Calle y número):</label>
					<div class="controls">
						<input class="span4" id="direccion" name="direccion" type="text" value="{{ registro.direccion }}" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="cp">Codigo postal:</label>
					<div class="controls">
						<input class="span1" id="cp" name="cp" type="text" value="{{ registro.cp }}" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="colonia">Colonia:</label>
					<div class="controls">
						<input class="span2" id="colonia" name="colonia" type="text" value="{{ registro.colonia }}" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="del_o_mun">Delegación o municipio:</label>
					<div class="controls">
						<input class="span2" id="del_o_mun" name="del_o_mun" placeholder="Solo para México" type="text" value="{{ registro.del_o_mun }}" {{ registro.pais and registro.pais != 146 ? ' disabled' : ''}} />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="ciudad">Ciudad:</label>
					<div class="controls">
						<input class="span2" id="ciudad" name="ciudad" type="text" value="{{ registro.ciudad }}" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="estado">Estado:</label>
					<div class="controls">
						<input class="span2" id="estado" name="estado" type="text" value="{{ registro.estado }}" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="pais">País:</label>
					<div class="controls">
						<select class="" id="pais" name="pais">
							<option value="">Seleccione:</option>
							{% for pais in paises %}
							<option value="{{ pais.pais_idPais }}"

							{% if registro.pais %}
								{% if registro.pais == pais.pais_idPais %}
									 selected
								{% endif %}
							{% else %}
								{% if pais.pais_idPais == 146 %}
									selected
								{% endif %}
							{% endif %}

							>{{ pais.pais_nombreEs }}</option>
							{% else %}
							<option value="">No se recuperaron los paises</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="lada_telefono">Lada:</label>
					<div class="controls">
						<input class="input-mini" id="lada_telefono" name="lada_telefono" type="text" value="{{ registro.lada_telefono }}" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="telefono_particular">Teléfono particular:</label>
					<div class="controls">
						<input class="span2" id="telefono_particular" name="telefono_particular" type="text" value="{{ registro.telefono_particular }}" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="telefono_movil">Teléfono movil:</label>
					<div class="controls">
						<input class="span2" id="telefono_movil" name="telefono_movil" type="text" value="{{ registro.telefono_movil }}" />
					</div>
				</div>
			</fieldset>

			<div class="acciones-form">
				<div class="form-error">&nbsp;</div>
				<div>
					<button class="btn btn-info" type="reset">
						<i class="icon-ok-sign icon-white"></i> Restaurar
					</button>
					<button class="btn btn-primary" type="submit">
						<i class="icon-ok-sign icon-white"></i> Actualizar
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

		{% for acompanante in acompanantes %}
		<form action="{{ seccion }}/?action=updateAcompanante" class="updateAcompanante" id="updateAcompanante_{{ acompanante.id_acompanante }}" method="post" name="updateAcompanante_{{ acompanante.id_acompanante }}">
			<input type="hidden" name="id_acompanante" value="{{ acompanante.id_acompanante }}" />
			<fieldset class="horizontal">
				<div class="control-group">
					<label class="control-label" for="acm_clave_{{ acompanante.id_acompanante }}">Tipo de acompañante</label>
					<div class="controls">
						<select class="{required:true}" id="acm_clave_{{ acompanante.id_acompanante }}" name="acm_clave">
							<option value="">Seleccione:</option>
							{% for categoria in categoriasAcom %}
							<option value="{{ categoria.ecan_clave }}"{{ acompanante.acm_clave == categoria.ecan_clave ? ' selected' : '' }}>{{ categoria.ecan_nombre }}</option>
							{% else %}
							<option value="">No hay categorias de acompañantes</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="acm_id_costo_{{ acompanante.id_acompanante }}">Costo del acompañante:</label>
					<div class="controls">
						<select class="{required:true} span2" id="acm_id_costo_{{ acompanante.id_acompanante }}" name="acm_id_costo">
							{% for key, costo in acompanante.costos %}
							<option value="{{ key }}"{{ acompanante.acm_id_costo == key ? ' selected' : '' }}>{{ costo.nombre }} - {{ costo.costo }}</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="acm_genero_{{ acompanante.id_acompanante }}">Genero:</label>
					<div class="controls">
						<select id="acm_genero_{{ acompanante.id_acompanante }}" name="acm_genero">
							<option value="">Seleccione:</option>
							{% for genero in generos %}
							<option value="{{ genero.gen_clave }}"{{ acompanante.acm_genero == genero.gen_clave ? ' selected' : '' }}>{{ genero.gen_nombre }}</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="acm_status_{{ acompanante.id_acompanante }}">Status:</label>
					<div class="controls">
						<select class="{required:true}" id="acm_status_{{ acompanante.id_acompanante }}" name="acm_status">
							<option value="">Seleccione:</option>
							{% for statusReg in statusRegs %}
							<option value="{{ statusReg.esr_clave }}"{{ acompanante.acm_status == statusReg.esr_clave ? ' selected' : '' }}>{{ statusReg.esr_nombre }}</option>
							{% endfor %}
						</select>
					</div>
				</div><br />
				<div class="control-group">
					<label class="control-label" for="acm_nombre_{{ acompanante.id_acompanante }}">Nombre</label>
					<div>
						<input class="{required:true} span2" id="acm_nombre_{{ acompanante.id_acompanante }}" name="acm_nombre" type="text" value="{{ acompanante.acm_nombre }}" required />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="acm_app_{{ acompanante.id_acompanante }}">Apellido paterno</label>
					<div>
						<input class="{required:true} span2" id="acm_app_{{ acompanante.id_acompanante }}" name="acm_app" type="text" value="{{ acompanante.acm_app }}" required />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="acm_apm_{{ acompanante.id_acompanante }}">Apellido materno</label>
					<div>
						<input class="span2" id="acm_apm_{{ acompanante.id_acompanante }}" name="acm_apm" type="text" value="{{ acompanante.acm_apm }}" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="acm_titulo_{{ acompanante.id_acompanante }}">Titulo</label>
					<div class="controls">
						<select id="acm_titulo_{{ acompanante.id_acompanante }}" name="acm_titulo">
							<option value="">Seleccione:</option>
							{% for titulo in titulos %}
							<option value="{{ titulo.ect_clave }}"{{ acompanante.acm_titulo == titulo.ect_clave ? ' selected' : '' }}>{{ titulo.ect_clave }}</option>
							{% else %}
							<option value="">Aun no existen titulos para el evento</option>
							{% endfor %}
						</select>
					</div>
				</div>
			</fieldset>
			<div class="acciones-form">
				<div class="form-error"></div>
				<div>
					<button class="btn btn-mini btn-primary" type="submit">
						<i class="icon-ok-sign icon-white"></i> Actualizar
					</button>
					<a class="btn btn-mini btn-danger" data-load="accion" href="{{ seccion }}/?action=confEliminarAcom&amp;id={{ acompanante.id_acompanante }}">
						<i class="icon-remove-sign icon-white"></i> Eliminar
					</a>
					<span class="imp-acompanante{{ acompanante.acm_status in ["PAG","COR","CCC"] ? '' : ' hide' }}">
						<a class="btn btn-mini btn-success" data-load="accion" href="{{ seccion }}/?action=impresionGafeteAcom&amp;id={{ acompanante.id_acompanante }}">
							<i class="icon-barcode icon-white"></i> Imprimir Gafete
						</a>
					</span>
				</div>
			</div>
		</form>
		<br />
		{% endfor %}
		<div id="agr-acms"></div>

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

		<div style="position: fixed; left: 81%;">
			<a class="btn btn-large btn-warning" data-load="accion" data-id-form-item="{{ maxIdItem }}" href="{{ seccion }}/?action=formItem" id="nuevo-item-act">
				<i class="icon-plus-sign icon-white"></i> Agregar item
			</a>
		</div>

		{% for adicional in adicionales %}
		<form action="{{ seccion }}/?action=actualizarItem" data-load="ajax" id="num-item-{{ adicional.item_idInterno }}" method="post" name="datos-item-{{ adicional.item_idInterno }}">
			<input name="clave_evento" type="hidden" value="{{ evento.evt_clave }}" />
			<input name="num_item" type="hidden" value="{{ adicional.item_idInterno }}" />
			<fieldset class="horizontal">
				<div class="control-group">
					<label class="control-label" for="item_clave_{{ adicional.item_idInterno }}">Items:</label>
					<div class="controls">
						<select id="item_clave_{{ adicional.item_idInterno }}" name="item_clave_{{ adicional.item_idInterno }}">
							<option value="">Seleccione:</option>
							{% for item in items %}
							<option value="{{ item.eci_paxMaximos != 0 and item.total_conf.filas >= item.eci_paxMaximos ? '' : item.eni_clave }}"{{ adicional.item_clave == item.eni_clave ? ' selected' : '' }}{{ item.eci_paxMaximos != 0 and item.total_conf.filas >= item.eci_paxMaximos ? ' disabled' : '' }}>
								{{ item.eni_nombre }} {{ item.eci_paxMaximos != 0 and item.total_conf.filas >= item.eci_paxMaximos ? '- Agotado' : '' }}
							</option>
							{% else %}
							<option value="">No hay categorias de items</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="item_id_costo_{{ adicional.item_idInterno }}">Costo unitario:</label>
					<div class="controls">
						<select id="item_id_costo_{{ adicional.item_idInterno }}" name="item_id_costo_{{ adicional.item_idInterno }}">
							<option value="">Seleccione:</option>
							{% for key, costo in adicional.costos %}
							<option value="{{ key }}"{{ adicional.item_id_costo == key ? ' selected' : '' }}>{{ costo.nombre }} - {{ costo.costo }}</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="control-group" style="min-width: 78px;">
					<label class="control-label" for="item_cantidad_{{ adicional.item_idInterno }}">Cantidad:</label>
					<div class="controls">
						<input autocomplete="off" class="span1" id="item_cantidad_{{ adicional.item_idInterno }}" name="item_cantidad_{{ adicional.item_idInterno }}" type="text" value="{{ adicional.item_cantidad }}" />
					</div>
				</div>
				<div class="control-group" style="min-width: 110px;">
					<label class="control-label" for="item_costo_total_{{ adicional.item_idInterno }}">Total:</label>
					<div class="controls">
						$ <input class="input-small" id="item_costo_total_{{ adicional.item_idInterno }}" name="item_costo_total_{{ adicional.item_idInterno }}" readonly type="text" value="{{ adicional.item_costo_total }}" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="item_status_{{ adicional.item_idInterno }}">Status:</label>
					<div class="controls">
						<select id="item_status_{{ adicional.item_idInterno }}" name="item_status_{{ adicional.item_idInterno }}">
							<option value="">Seleccione:</option>
							{% for statusReg in statusRegs %}
							<option value="{{ statusReg.esr_clave }}"{{ adicional.item_status == statusReg.esr_clave ? ' selected' : '' }}>{{ statusReg.esr_nombre }}</option>
							{% endfor %}
						</select>
					</div>
				</div>
			</fieldset>
			<div class="acciones-form">
				<div class="form-error">&nbsp;</div>
				<div>
					<button class="btn btn-mini btn-success" type="submit">
						<i class="icon-ok-sign icon-white"></i> Actualizar
					</button>
					<a class="btn btn-mini btn-success" href="{{ seccion }}/?action=imprimirConstanciaTaller&amp;idReg={{ adicional.item_idRegistro }}&amp;tllr={{ adicional.item_clave }}&amp;id={{ adicional.item_idInterno }}" target="_blank">
						<i class="icon-print icon-white"></i> Imprimir constancia
					</a>
					<a class="btn btn-mini btn-danger" data-load="accion" href="{{ seccion }}/?action=confEliminarItem&amp;id={{ adicional.item_idInterno }}">
						<i class="icon-remove-sign icon-white"></i> Eliminar
					</a>
				</div>
			</div>
		</form>
		<br />
		{% endfor %}

		<div id="agr-items"></div>
	</div>

	<!-- Datos de facturación -->
	<div id="datos-facturacion-registro">
		<form action="{{ seccion }}/?action=actualizarDatosFacturacion" data-load="ajax" method="post" name="datos-facturacion">
			<input name="id_registro" type="hidden" value="{{ registro.id_registro }}" />
			<input name="tabla_registros" type="hidden" value="{{ evento.evt_nombreTablaAsistentes }}" />

			<fieldset class="horizontal">
				<div class="control-group">
					<div class="controls">
						<label class="checkbox">
							<input id="copiar_datos_direccion" name="copiar_datos_direccion" type="checkbox" /> Copiar de los datos de contacto
						</label>
					</div>
				</div>
			</fieldset>

			<fieldset class="horizontal">
				<div class="control-group">
					<label class="control-label" for="razon_social_RS">Razon social:</label>
					<div class="controls">
						<input class="span5" id="razon_social_RS" name="razon_social_RS" type="text" value="{{ registro.razon_social_RS }}" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="rfc_RS">RFC:</label>
					<div class="controls">
						<input class="span3" id="rfc_RS" name="rfc_RS" placeholder="Solo para México" type="text" value="{{ registro.rfc_RS }}"{{ registro.pais_RS and registro.pais_RS != 146 ? ' disabled' : '' }} />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="direccion_RS">Dirección (Calle y número):</label>
					<div class="controls">
						<input class="span4" id="direccion_RS" name="direccion_RS" type="text" value="{{ registro.direccion_RS }}" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="cp_RS">Codigo postal:</label>
					<div class="controls">
						<input class="span1" id="cp_RS" name="cp_RS" type="text" value="{{ registro.cp_RS }}" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="colonia_RS">Colonia:</label>
					<div class="controls">
						<input class="span2" id="colonia_RS" name="colonia_RS" type="text" value="{{ registro.colonia_RS }}" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="del_o_mun_RS">Delegación o municipio:</label>
					<div class="controls">
						<input class="span2" id="del_o_mun_RS" name="del_o_mun_RS" placeholder="Solo para México" type="text" value="{{ registro.del_o_mun_RS }}"{{ registro.pais_RS and registro.pais_RS != 146 ? ' disabled' : '' }} />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="ciudad_RS">Ciudad:</label>
					<div class="controls">
						<input class="span2" id="ciudad_RS" name="ciudad_RS" type="text" value="{{ registro.ciudad_RS }}" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="estado_RS">Estado:</label>
					<div class="controls">
						<input class="span2" id="estado_RS" name="estado_RS" type="text" value="{{ registro.estado_RS }}" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="pais_RS">País:</label>
					<div class="controls">
						<select class="" id="pais_RS" name="pais_RS">
							<option value="">Seleccione:</option>
							{% for pais in paises %}
							<option value="{{ pais.pais_idPais }}"

							{% if registro.pais_RS %}
								{% if registro.pais_RS == pais.pais_idPais %}
									 selected
								{% endif %}
							{% else %}
								{% if pais.pais_idPais == 146 %}
									selected
								{% endif %}
							{% endif %}

							>{{ pais.pais_nombreEs }}</option>
							{% else %}
							<option value="">No se recuperaron los paises</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="email_RS">Correo electrónico para facturación electrónica:</label>
					<div class="controls">
						<input class="span4" id="email_RS" name="email_RS" type="email" value="{{ registro.email_RS }}" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="lada_telefono_RS">Lada:</label>
					<div class="controls">
						<input class="input-mini" id="lada_telefono_RS" name="lada_telefono_RS" type="text" value="{{ registro.lada_telefono_RS }}" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="telefono_RS">Teléfono:</label>
					<div class="controls">
						<input class="span2" id="telefono_RS" name="telefono_RS" type="text" value="{{ registro.telefono_RS }}" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="fax_RS">Fax:</label>
					<div class="controls">
						<input class="span2" id="fax_RS" name="fax_RS" type="text" value="{{ registro.fax_RS }}" />
					</div>
				</div>
			</fieldset>

			<div class="acciones-form">
				<div class="form-error">&nbsp;</div>
				<div>
					<button class="btn btn-info" type="reset">
						<i class="icon-ok-sign icon-white"></i> Restaurar
					</button>
					<button class="btn btn-inverse" type="submit">
						<i class="icon-ok-sign icon-white"></i> Actualizar
					</button>
				</div>
			</div>
		</form>
	</div>

	<!-- Datos de hospedaje -->
	<div id="datos-hospedaje-registro">

		<form action="{{ reservacion.id_reservacion ? seccion ~ '/?action=setUpdateHospedaje' : seccion ~ '/?action=setHospedaje' }}" id="{{ reservacion.id_reservacion ? 'setUpdateHospedaje' : 'setHospedaje' }}" method="post" name="{{ reservacion.id_reservacion ? 'setUpdateHospedaje' : 'setHospedaje' }}">
			<input type="hidden" name="id_reservacion" value="{{ reservacion.id_reservacion }}" />
			<input type="hidden" id="maxAdultos" name="maxAdultos" value="{{ specHabitacion.hhb_paxAdultos }}" />
			<input type="hidden" id="maxMenores" name="maxMenores" value="{{ specHabitacion.hhb_paxMenores }}" />

			<fieldset class="horizontal">
				<div class="control-group">
					<label class="control-label" for="res_idHotel">Elija un hotel:</label>
					<div class="controls">
						<select id="res_idHotel" name="res_idHotel" required>
							<option value="">Seleccione:</option>
							{% for hotel in hoteles %}
							<option value="{{ hotel.htl_id }}"{{ reservacion.res_idHotel == hotel.htl_id ? ' selected="selected"' : '' }}>{{ hotel.htl_nombre }}</option>
							{% endfor %}
						</select>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="res_idHabitacion">Elija el tipo de habitación:</label>
					<div class="controls">
						<select id="res_idHabitacion" name="res_idHabitacion" required>
							<option value="">Seleccione:</option>
							{% for habitacion in habitaciones %}
							<option value="{{ habitacion.hhn_idHabitacion }}"{{ reservacion.res_idHabitacion == habitacion.hhn_idHabitacion ? ' selected="selected"' : '' }}>{{ habitacion.hhn_nombre }}</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="res_llegada">Fecha de llegada:</label>
					<div class="controls">
						<input class="input-small" id="res_llegada" name="res_llegada" readonly type="text" value="{{ reservacion.res_llegada ? reservacion.res_llegada|date('d/m/Y') : '' }}" required />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="res_salida">Fecha de salida:</label>
					<div class="controls">
						<input class="input-small" id="res_salida" name="res_salida" readonly type="text" value="{{ reservacion.res_salida ? reservacion.res_salida|date('d/m/Y') : '' }}" required />
					</div>
				</div>
			</fieldset>

			<fieldset class="horizontal">
				<div class="control-group">
					<label class="control-label">Costo por noche adulto:</label>
					<div class="controls" id="costo-adulto">{{ specHabitacion.hhc_costoAdulto }}</div>
				</div>
				<div class="control-group">
					<label class="control-label">Costo por noche menor:</label>
					<div class="controls" id="costo-menor">{{ specHabitacion.hhc_costoMenor }}</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="res_numHabitaciones">Número de habitaciones:</label>
					<div class="controls">
						<select class="span2" id="res_numHabitaciones" name="res_numHabitaciones" required>
							<option value="">Seleccione:</option>
							{% for num_hab in 1..specHabitacion.hhb_paxMaxReservacion if reservacion.res_numHabitaciones %}
							<option value="{{ num_hab }}"{{ num_hab == reservacion.res_numHabitaciones ? ' selected="selected"' : '' }}>{{ num_hab }}</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label titulo-pax-cuartos">Ocupación&nbsp;&nbsp;&nbsp;&nbsp;<strong>Adultos</strong>&nbsp;<strong>Menores</strong></label>
					<div class="controls" id="pax-habitaciones">
						<div class="pax-cuartos">
							{% if reservacion.res_numHabitaciones %}
							{% for key_hab, num_hab in 1..reservacion.res_numHabitaciones %}
								Habitación {{ num_hab }}:
								<select class="input-mini" name="habitacion[adultos][{{ num_hab }}]" required>
								{% for ocupacion in 1..specHabitacion.hhb_paxAdultos %}
								<option value="{{ ocupacion }}"
								{% for key, current in res_numAdultos.adultos if key == key_hab %}
								{{ current.hab == ocupacion ? ' selected="selected"' : '' }}
								{% endfor %}
								>{{ ocupacion }}</option>
								{% endfor %}
							</select>
							<select class="input-mini" name="habitacion[menores][{{ num_hab }}]" required>
								{% for ocupacion in 0..specHabitacion.hhb_paxMenores %}
								<option value="{{ ocupacion }}"
								{% for key, current in res_numMenores.menores if key == key_hab %}
								{{ current.hab == ocupacion ? ' selected="selected"' : '' }}
								{% endfor %}
								>{{ ocupacion }}</option>
								{% endfor %}
							</select>
							<br />
							{% endfor %}
							{% else %}
							&nbsp;
							{% endif %}
						</div>
					</div>
				</div>
			</fieldset>

			<fieldset class="horizontal">
				<div class="control-group">
					<label class="control-label" for="res_anticipo">Deja anticipo:</label>
					<div class="controls">
						<input class="span2" name="res_anticipo" type="text" value="{{ reservacion.res_anticipo }}" placeholder="0.00" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="res_formaPago">Forma de pago:</label>
					<div class="controls">
						<select class="span2" name="res_formaPago" id="res_formaPago" required>
							<option value="">Seleccione:</option>
							{% for formaPago in formasPago %}
							<option value="{{ formaPago.fpn_clave }}"{{ formaPago.fpn_clave == reservacion.res_formaPago ? ' selected="selected"' : '' }}>{{ formaPago.fpn_nombre }}</option>
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
							<option value="{{ statusReg.esr_clave }}"{{ statusReg.esr_clave == reservacion.res_status ? ' selected="selected"' : '' }}>{{ statusReg.esr_nombre }}</option>
							{% else %}
							<option value="">No hay status de registro</option>
							{% endfor %}
						</select>
					</div>
				</div>
			</fieldset>

			<div class="form-error">&nbsp;</div>
			<button class="btn btn-success" type="submit">
				<i class="icon-ok-sign icon-white"></i> {{ reservacion.id_reservacion ? 'Actualizar' : 'Agregar' }}
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
						if (!sRes.status) {
							_form.find('.form-error').html(sRes.mensaje);
						} else {
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
									'</a>' +
									' <span class="imp-acompanante' + mostrar + '">' +
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

							$('#agr-acms').append(update_form);

							update_form.find("select[name='acm_clave'] option[value='" + sRes.acomp.acm_clave + "']").attr("selected", "selected");
							update_form.find("select[name='acm_id_costo'] option[value='" + sRes.acomp.acm_id_costo + "']").attr("selected", "selected");
							update_form.find("select[name='acm_genero'] option[value='" + sRes.acomp.acm_genero + "']").attr("selected", "selected");
							update_form.find("select[name='acm_titulo'] option[value='" + sRes.acomp.acm_titulo + "']").attr("selected", "selected");
							update_form.find("select[name='acm_status'] option[value='" + sRes.acomp.acm_status + "']").attr("selected", "selected");
							update_form.find("input[name='acm_nombre']").val(sRes.acomp.acm_nombre);
							update_form.find("input[name='acm_app']").val(sRes.acomp.acm_app);
							update_form.find("input[name='acm_apm']").val(sRes.acomp.acm_apm);
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
					data: {folio_pago: clave, id_registro: $('input[name="id_registro"]:last').val()},
					dataType: 'json',
					success: function(sRes) {
						if (sRes.status) {
							$('#actualizarRegistro').attr("disabled", "disabled");
							$('#actualizarRegistro').parent().prev().text('Error: otro registro ya tiene este folio asignado!');
						} else {
							$('#actualizarRegistro').removeAttr("disabled");
							$('#actualizarRegistro').parent().prev().html('&nbsp;');
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
	});
	</script>
</div>