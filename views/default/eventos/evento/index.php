{#
<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/views/default/eventos/evento/
 * @version $Id: index.php 1.0 2012-04-05 23:13 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }
?>
#}
<div class="page-header" style="margin-bottom: 5px;">
	<h5 id="nombre-evento">
		{{ evento.evt_nombre }}
	</h5>
</div>

<div class="navbar">
	<div class="navbar-inner">
		<ul class="nav">
			<li class="active">
				<a data-load="seccion" data-opts='{"tools":{"css":true,"js":true,"file":"evento"}}' data-method="post" href="{{ seccion }}/">
					<i class="icon-folder-open"></i> Datos generales
				</a>
			</li>
			<li class="dropdown">
				<a href="#" data-toggle="dropdown" class="dropdown-toggle">
					<i class="icon-tags"></i> Catalogos <b class="caret"></b>
				</a>
				<ul class="dropdown-menu">
					<li>
						<a data-load="modal" data-modal='{"height":400}' href="{{ seccion }}/items"><i class="icon-shopping-cart"></i> Adicionales</a>
					</li>
					<li>
						<a data-load="modal" data-modal='{"width":700}' href="{{ seccion }}/titulos"><i class="icon-tag"></i> Titulos</a>
					</li>
					<li>
						<a data-load="modal" data-modal='{"width":700}' href="{{ seccion }}/statusRegistros"><i class="icon-flag"></i> Status de Registros</a>
					</li>
					<li>
						<a data-load="modal" data-modal='{"width":500}' href="{{ seccion }}/formasPago"><i class="icon-certificate"></i> Formas de Pago</a>
					</li>
				</ul>
			</li>
			<li><a data-load="seccion" data-opts='{"target":"evento"}' href="{{ seccion }}/reportes/"><i class="icon-list-alt"></i> Reportes</a></li>
			<li><a data-load="accion" href="#"><i class="icon-qrcode"></i> Diseño de gafetes</a></li>
			<li><a data-load="accion" href="#"><i class="icon-barcode"></i> Diseño de constancia</a></li>
			<li><a data-load="accion" href="#"><i class="icon-briefcase"></i> Diseño de factura</a></li>
			<li><a data-load="seccion" data-opts='{"target":"evento"}' href="{{ seccion }}/reportes/?action=cajeros"><i class="icon-list-alt"></i> Cajeros</a></li>
			<li><a data-load="seccion" data-opts='{"target":"evento"}' href="{{ seccion }}/hospedaje/"><i class="icon-plane"></i> Hospedaje</a></li>
			<li><a data-load="seccion" data-opts='{"target":"evento"}' href="{{ seccion }}/rfid/"><i class="icon-signal"></i> RFDI</a></li>
			<!--<li><a data-load="accion" href="#">Formulario</a></li>-->
		</ul>
	</div>
</div>

<div id="evento">

	<!-- Inicia detalles generales -->
	<div class="detalles">
		<h6>
			Datos generales
			<small><a class="btn btn-mini" data-load="modal" href="{{ seccion }}/?action=formDatosGenerales"><i class="icon-edit"></i></a></small>
		</h6>

		<table class="table table-striped table-bordered table-condensed">
			<thead>
				<tr>
					<th>Clave</th>
					<th>Tipo</th>
					<th>Divisa</th>
					<th>IVA</th>
					<th>Inicia</th>
					<th>Finaliza</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th>{{ evento.evt_clave }}</th>
					<th>{{ evento.cte_nombre }}</th>
					<th>{{ evento.evt_divisa }}</th>
					<th>{{ evento.evt_iva }}</th>
					<th>{{ evento.evt_inicio }}</th>
					<th>{{ evento.evt_termina }}</th>
					<th>{{ evento.ecs_nombre }}</th>
				</tr>
			</tbody>
		</table>
	</div>
	<!-- Finaliza detalles generales -->

	<!-- Inicia tipo de cambio -->
	<div class="tipoCambioDivisa">
		<h6>Tipo de cambio</h6>

		<table class="table table-striped table-bordered table-condensed">
			<thead>
				<tr>
					<th>Divisa</th>
					<th colspan="2">Tipo de cambio</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th id="divisa">{{ evento.evt_divisa }}</th>
					<td>{{ evento.evt_tipoCambio }}</td>
					<th>
						<a class="btn btn-mini" data-divisa='evt_tipoCambio' href="{{ seccion }}/?action=editarDivisa"><i class="icon-edit"></i></a>
					</th>
				</tr>
			</tbody>
		</table>
	</div>
	<!-- Finaliza tipo de cambio -->

	<!-- Inicia categorias -->
	<div class="catCategorias">
		<h6>
			Catalogo de categorias
			<small><a class="btn btn-mini" data-load="modal" href="{{ seccion }}/?action=formAltaCategoria"><i class="icon-plus-sign"></i></a></small>
		</h6>

		<table class="table table-striped table-bordered table-condensed">
			<thead>
				<tr>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<th colspan="5">Costos</th>
					<th>&nbsp;</th>
				</tr>
				<tr>
					<th style="width: 115px;">Clave</th>
					<th style="width: 300px;">Nombre/s</th>
					<th style="width: 115px;">Costo 1</th>
					<th style="width: 115px;">Costo 2</th>
					<th style="width: 115px;">Costo 3</th>
					<th style="width: 115px;">En sitio</th>
					<th style="width: 115px;">Otro</th>
					<th style="width: 70px;">Opciones</th>
				</tr>
			</thead>
			<tbody>
				{% for categoria in categorias %}
					<tr id="{{ categoria.ecc_clave }}">
						<td>{{ categoria.ecc_clave }}</td>
						<!-- Inicia nombres de las categorias -->
						<td class="nombres-categorias {{ categoria.ecc_clave }}">
							<div class="accion-agregar-categoria">
								<a data-load="modal" class="btn btn-mini btn-warning" href="{{ seccion }}/?action=formAltaNombreCategoria&amp;cve={{ categoria.ecc_clave }}&amp;id={{ categoria.idNombre }}" title="Agregar nombre a la categoria">
									<i class="icon-plus-sign icon-white"></i>
								</a>
							</div>
							{% for nombre in categoria.nombres %}
							<div class="nombre-categoria" id="{{ categoria.ecc_clave }}-{{ nombre.enc_idNombreCategoria }}">
								<span>{{ nombre.enc_nombre }}</span>
								<span class="acciones-nombre-categoria">
									<a class="btn btn-mini btn-danger" data-load="accion" href="{{ seccion }}/?action=confEliminarNombreCategoria&amp;cve={{ nombre.enc_clave }}&amp;id={{ nombre.enc_idNombreCategoria }}" title="Eliminar nombre">
										<i class="icon-trash icon-white"></i>
									</a>
								</span>
							</div>
							{% endfor %}
						</td>
						<!-- Termina nombres de las categorias -->
						<td>{{ categoria.ecc_costo_fecha_1 }}</td>
						<td>{{ categoria.ecc_costo_fecha_2 }}</td>
						<td>{{ categoria.ecc_costo_fecha_3 }}</td>
						<td>{{ categoria.ecc_costo_sitio }}</td>
						<td>{{ categoria.ecc_costo_otro }}</td>
						<th>
							<a class="btn btn-mini" data-load="modal" href="{{ seccion }}/?action=formEditarCategoria&amp;cve={{ categoria.ecc_clave }}" title="Editar la categoria">
								<i class="icon-edit"></i>
							</a>
							<a class="btn btn-mini btn-danger" data-load="accion" href="{{ seccion }}/?action=confEliminarCategoria&amp;cve={{ categoria.ecc_clave }}" title="Eliminar la categoria">
								<i class="icon-trash icon-white"></i>
							</a>
						</th>
					</tr>
				{% else %}
					<tr>
						<th colspan="8">
							Aun no hay categorias | <a data-load="modal" href="{{ seccion }}/?action=formAltaCategoria"><i class="icon-plus-sign"></i> Agregar una ahora</a>
						</th>
					</tr>
				{% endfor %}
			</tbody>
		</table>
	</div>
	<!-- Finaliza categorias -->

	<div class="idiomas">
		<h6>
			Idiomas del registro
			<small>
				<a class="btn btn-mini" data-load="modal" href="{{ seccion }}/?action=formAltaIdioma">
					<i class="icon-plus-sign"></i>
				</a>
			</small>
		</h6>
		<table class="table table-striped table-bordered table-condensed">
			<thead>
				<tr>
					<th>Clave</th>
					<th>Nombre</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				{% for idioma in idiomas %}
					<tr id="idioma-{{ idioma.eis_idioma }}">
						<th>{{ idioma.eis_idioma }}</th>
						<th>{{ idioma.eis_nombre }}</th>
						<th>
							<a class="btn btn-mini btn-danger" data-load="accion" href="{{ seccion }}/?action=confEliminarIdioma&amp;cve={{ idioma.eis_idioma }}" title="Eliminar idioma">
								<i class="icon-trash icon-white"></i>
							</a>
						</th>
					</tr>
				{% endfor %}
			</tbody>
		</table>
	</div>

	<div class="observaciones">
		<h6>
			Observaciones
			<small><a class="btn btn-mini" data-load="modal" href="{{ seccion }}/?action=formObservaciones"><i class="icon-edit"></i></a></small>
		</h6>
		<div>{{ evento.evt_observaciones }}</div>
	</div>

	<div class="controlCambios">
		<h6>Control de cambios</h6>
		<table class="table table-striped table-bordered table-condensed">
			<thead>
				<tr>
					<th>&nbsp;</th>
					<th>Nombre</th>
					<th>Fecha</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Creado por:</td>
					<td>{{ evento.evt_usuarioAlta }}</td>
					<td>{{ evento.evt_fechaAlta }}</td>
				</tr>
				<tr>
					<td>Última modificación por:</td>
					<td>{{ evento.evt_usuarioModifico }}</td>
					<td>{{ evento.evt_fechaModificacion }}</td>
				</tr>
			</tbody>
		</table>
	</div>

</div>