<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/libs/
 * @version $Id: class.Reportes.php 1.0 2012-09-01 01:00 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com, son_gohan_khan@hotmail.com>
 * 
 * Reportes
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace SMC\Lib\Reportes;

use SimpleMVC\Model\Model_Controller as Model;

class Reportes extends Model
{

	public function prepareStatus(array $data)
	{
		if (!empty($data))
		{
			foreach ($data as $k => &$v)
			{
				unset($v->esr_idEvento);
				unset($v->esr_idStatus);
			}
		}

		return $data;
	}

	public function getFieldsTableRegistros($clave)
	{
		$fields = array();
		$clave = $this->db->hEscapeString($clave);

		$qry = "SELECT * 
			FROM smc_reg_%s 
			LIMIT 1
		";

		$qry = sprintf($qry, $clave);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				$fields = $this->db->hFetchFields();
				$this->db->hFree();
			}
		}

		return $fields;
	}

	public function repGeneral($post, $evento)
	{
		// Cargamos la libreria para excel
		ini_set("include_path", './'. PATH_SMVC . '/' . PATH_EXT . '/PEAR/');
		require ("PEAR.php");
		require_once ('Spreadsheet/Excel/Writer.php');

		$registros = array();
		$where = "";

		if (!empty($post['campos']))
		{
			$campos_select = implode(", ", $post['campos']);
		}
		else
		{
			$campos_select = 'r.*';
		}

		if (!empty($post['cat_registro']))
		{
			if (count($post['cat_registro']) > 1)
			{
				foreach($post['cat_registro'] as $k => $categoria)
				{
					$post['cat_registro'][$k] = "'" . $categoria . "'";
				}

				$where = 'WHERE r.cat_registro IN (' . implode(", ", $post['cat_registro']) . ')';
			}
			else
			{
				$cat_registro = "'" . $post['cat_registro'][0] . "'";
				$where = 'WHERE r.cat_registro = ' . $cat_registro;
			}
		}

		if (!empty($post['forma_pago']))
		{
			if (count($post['forma_pago']) > 1)
			{
				foreach($post['forma_pago'] as $k => $forma_pago)
				{
					$post['forma_pago'][$k] = "'" . $forma_pago . "'";
				}
				$where .= ' AND r.forma_pago IN (' . implode(', ', $post['forma_pago']) . ')';
			}
			else
			{
				$forma_pago = "'" . $post['forma_pago'][0] . "'";
				$where .= ' AND r.forma_pago = ' . $forma_pago;
			}
		}

		if (!empty($post['status']))
		{
			if (count($post['status']) > 1)
			{
				foreach($post['status'] as $k => $forma_pago)
				{
					$post['status'][$k] = "'" . $forma_pago . "'";
				}
				$where .= ' AND r.status IN (' . implode(', ', $post['status']) . ')';
			}
			else
			{
				$status = "'" . $post['status'][0] . "'";
				$where .= ' AND r.status = ' . $status;
			}
		}

		$qry = "SELECT %s, n.enc_nombre AS cat_registro 
			FROM smc_reg_%s AS r 
			JOIN smc_eventoCategoriasNombres AS n ON (n.enc_clave = r.cat_registro) 
			%s
		";

		$qry = sprintf($qry, $campos_select, $evento->evt_clave, $where);

		if ($result = $this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				$campos = $this->db->hFetchFields();
				while ($fila = $this->db->hFetchObject())
				{
					$registros[] = $fila;
				}

				$this->db->hFree();
			}
		}

		$workbook = new \Spreadsheet_Excel_Writer ();

		$fTitleHead = $workbook->addFormat ();
		$fTitleHead->setBold ();

		$workbook->setVersion ( 8 );
		$worksheet = $workbook->addWorksheet ( 'Registros' );

		if (is_array ( $registros ) && ! empty ( $registros ))
		{

			$worksheet->write ( 0, 1, "Reporte General", $fTitleHead );

			foreach ( $campos as $k => $campo )
			{
				$worksheet->write ( 1, $k, $campo->name );
			}

			$fila = 3;
			foreach ( $registros as $registro )
			{

				$campo = 0;
				foreach ( $registro as $k => $valor )
				{

					$worksheet->setInputEncoding ( 'utf-8' );

					$worksheet->writeString ( $fila, $campo, $valor );

					$campo ++;

				}

				$fila ++;

			}

			$workbook->send ( "general.xls" );
			$workbook->close ();

		}
	}

	public function getFieldsTableItems($clave)
	{
		$fields = array();
		$clave = $this->db->hEscapeString($clave);

		$qry = "SELECT item_clave AS clave, item_cantidad AS cantidad, item_costo_unitario AS costo_unitario,
			item_costo_total AS costo_total, item_status AS status 
			FROM smc_reg_%s_items 
			LIMIT 1
		";

		$qry = sprintf($qry, $clave);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				$fields = $this->db->hFetchFields();
				$this->db->hFree();
			}
		}

		return $fields;
	}

	public function repAdicionales($post, $evento)
	{
		// Cargamos la libreria para excel
		ini_set("include_path", './'. PATH_SMVC . '/' . PATH_EXT . '/PEAR/');
		require ("PEAR.php");
		require_once ('Spreadsheet/Excel/Writer.php');

		$registros = array();
		$where = "";

		// Obtenemos los registros
		if (!empty($post['reg_status']))
		{
			if (count($post['reg_status']) > 1)
			{
				foreach ($post['reg_status'] as $key => $value)
				{
					$post['reg_status'][$key] = "'" . $value . "'";
				}

				$in = implode(', ', $post['reg_status']);
			}
			else
			{
				$in = "'" . $post['reg_status'][0] . "'";
			}

			$where = 'WHERE r.status IN (' . $in . ')';
		}
		else
		{
			$where = 'WHERE 1';
		}

		$qry = "SELECT r.id_registro AS ID_Registro, r.cat_registro AS Categoria, cn.enc_nombre AS Nombre_categoria,
			r.nombre AS Nombre, r.app AS Apellido_paterno, r.apm AS Apellido_materno, r.status AS Clave_Status,
			s.esr_nombre AS Nombre_Status, i.item_clave, n.eni_nombre AS Nombre_Item, i.item_cantidad,
			i.item_costo_unitario, i.item_costo_total, i.item_status 
			FROM smc_reg_%s AS r 
			JOIN smc_reg_%s_items AS i ON (i.item_idRegistro = r.id_registro) 
			JOIN smc_eventoItemsNombres AS n ON (n.eni_clave = i.item_clave) 
			JOIN smc_eventoCategoriasNombres AS cn ON (cn.enc_clave = r.cat_registro) 
			JOIN smc_eventoStatusRegistros AS s ON (s.esr_clave = r.status) 
			%s 
			AND cn.enc_idioma = '%s' 
			AND s.esr_idEvento = %d
		";

		$qry = sprintf($qry,
			$evento->evt_clave,
			$evento->evt_clave,
			$where,
			$evento->evt_idioma,
			$evento->evt_idEvento
		);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				$campos_regs = $this->db->hFetchFields();
				while ($reg = $this->db->hFetchObject())
				{
					$registros[$reg->ID_Registro] = $reg;
				}
				$this->db->hFree();
			}
		}

		if (!empty($registros))
		{
			$items = array();
			$where = '';

			if (!empty($post['campos']))
			{
				if (count($post['campos']) > 1)
				{
					$campos_select = implode(", ", $post['campos']);
				}
				else
				{
					$campos_select = $post['campos'];
				}
			}
			else
			{
				$campos_select = 'i.*';
			}

			$workbook = new \Spreadsheet_Excel_Writer();

			$workbook->setVersion(8);
			$worksheet = $workbook->addWorksheet ('Adicionales - items');
			$titulo_tecno =& $workbook->addFormat(array(
					'Size' => 14,
					'Align' => 'center',
					'Color' => 'Black'
				)
			);
			$titulo_tecno->setVAlign('vcenter');
			$worksheet->mergeCells(0, 0, 1, 9);
			$worksheet->write( 0, 0, "Tecnoregistro", $titulo_tecno );

			$titulo_head =& $workbook->addFormat(
				array(
					'Size' => 10,
					'Align' => 'center',
					'Bold' => 1,
					'Color' => 'Black'
				)
			);
			$worksheet->mergeCells(3, 0, 3, 9);
			$worksheet->write ( 3, 0, "Reporte de adicionales - Items", $titulo_head );

			$decimal_format =& $workbook->addFormat();
			$decimal_format->setNumFormat('0.00');

			$integer_format =& $workbook->addFormat();
			$integer_format->setNumFormat('0');

			$head_items =& $workbook->addFormat(
				array(
					'Size' => 10,
					'Align' => 'center',
					'Bold' => 1,
					'Color' => 'Black'
				)
			);

			foreach ( $campos_regs as $k => $campo )
			{
				$worksheet->write ( 5, $k, str_replace('_', ' ', $campo->name ), $head_items);
			}

			$fila = 6;
			foreach ( $registros as $registro )
			{
				$i = 0;
				foreach ( $registro as $valor )
				{
					if (is_array($valor) && !empty($valor))
					{
						$fila += 2;
						$num_campo_item = 1;
						foreach ( $campos_items as $campo_item )
						{
							$worksheet->write ( $fila, $num_campo_item, str_replace('_', ' ', $campo_item->name ), $head_items);
							$num_campo_item++;
						}

						$fila++;
						foreach ($valor as $items)
						{
							$num_campo_item = 1;
							foreach ($items as $item)
							{
								if (is_string($item))
								{
									$worksheet->setInputEncoding ( 'utf-8' );
									$worksheet->writeString ( $fila, $num_campo_item, $item );
								}
								else
								{
									if (is_float($item))
									{
										$worksheet->writeNumber( $fila, $num_campo_item, $item , $decimal_format);
									}
									else
									{
										$worksheet->writeNumber( $fila, $num_campo_item, $item , $integer_format);
									}
								}

								$num_campo_item++;
							}
							$fila++;
						}
					}
					else
					{
						$worksheet->setInputEncoding ( 'utf-8' );
						$worksheet->writeString ( $fila, $i, $valor );
						$i++;
					}
				}

				$fila++;
			}

			$workbook->send ( "adicionales.xls" );
			$workbook->close ();

		} // No hay registros
	} // repAdicionales 

	/**
	 * Metodo que genera el PDF del acompañante
	 */
	public function reporteFotosPDF($registros, $evento, $func)
	{
		require_once('./'. PATH_SMVC . '/' . PATH_EXT . '/tcpdf/config/lang/eng.php');
		require_once('./'. PATH_SMVC . '/' . PATH_EXT . '/tcpdf/tcpdf.php');

		// create new PDF document
		$pdf = new \TCPDF(PDF_PAGE_ORIENTATION, 'cm', PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator("Tecnoregistro");
		$pdf->SetAuthor('G2012');
		$pdf->SetTitle('Gafete');

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		//set margins
		$pdf->SetMargins(0, 0, 0);

		//set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 0);

		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		//set some language-dependent strings
		$pdf->setLanguageArray($l);

		// ---------------------------------------------------------

		// set font
		$pdf->SetFont('helvetica', '', 9);

		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage();

		$count = 0;
		foreach ($registros as $key => $registro)
		{
			if ($count == 0)
			{
		$pdf->Image(PATH_IMAGES . '/logotecno.jpg', 0.2, 0.2, 4, 3.5, 'JPEG', '', '', '', false, 300, '', false, false, 0);
		$pdf->writeHTMLCell(15, 3, 4.5, 0.8, '<div>Registros<br /><strong>Evento:</strong> ' . $evento->evt_nombre . '<br /><strong>Categoria: </strong><br /><strong>Generdo el: </strong>' . date('d/m/Y \a \l\a\s H:i:s') . '</div>');

				$posFoto = 4.8;

				$pdf->Ln(2.5);

				// Set some content to print
				$html = '<table border="0" style="border-bottom: 1px solid #777;" width="95%">';
					$html .= '<tr>';
						$html .= '<td style="width: 60px;" valign="bottom">ID</td>';
						$html .= '<td style="width: 140px;" valign="bottom">Nombre</td>';
						$html .= '<td style="width: 140px;" valign="bottom">APP</td>';
						$html .= '<td style="width: 140px;" valign="bottom">APM</td>';
						$html .= '<td style="width: 160px;" valign="bottom">País</td>';
						$html .= '<td style="width: 60px;" valign="bottom">Foto</td>';
					$html .= '</tr>';
				$html .= '</table>';

				$pdf->setX(0.5);
				$pdf->writeHTML($html, false, false, false, false, 'C');

				$pdf->Ln(2);
				
			}
			else
			{
				$pdf->Ln(2);

				if ($count == 1)
				{
					$posFoto = 8.2;
				}
				else if ($count == 2)
				{
					$posFoto = 11.6;
				}
				else if ($count == 3)
				{
					$posFoto = 15;
				}
				else if ($count == 4)
				{
					$posFoto = 18.3;
				}
				else if ($count == 5)
				{
					$posFoto = 21.9;
				}
				else if ($count == 6)
				{
					$posFoto = 25.3;
				}

			}

			if ($registro->foto_fotografia && $registro->foto_mime)
			{
				//echo '<img src="data:' . $registro->foto_mime . ';base64,' . $registro->foto_fotografia . '" />';
				$pdf->Image('@' . base64_decode($registro->foto_fotografia), 18.7, $posFoto, 1.5, 2);
			}

			// Set some content to print
			$html = '<table border="0" style="border-bottom: 1px solid #CCC;" width="95%">';
				$html .= '<tr>';
					$html .= '<td style="height: 50px; width: 60px;" valign="bottom">' . $registro->id_registro . '</td>';
					$html .= '<td style="height: 50px; width: 140px;" valign="bottom">' . $func->mayusStr($registro->nombre) . '</td>';
					$html .= '<td style="height: 50px; width: 140px;" valign="bottom">' . $func->mayusStr($registro->app) . '</td>';
					$html .= '<td style="height: 50px; width: 140px;" valign="bottom">' . $func->mayusStr($registro->apm) . '</td>';
					$html .= '<td style="height: 50px; width: 160px;" valign="bottom">' . $func->mayusStr($registro->pais) . '</td>';
					$html .= '<td style="height: 50px; width: 60px;" valign="bottom">&nbsp;</td>';
				$html .= '</tr>';
			$html .= '</table>';

			$pdf->setX(0.5);
			$pdf->writeHTML($html, false, false, false, false, 'C');
			// Nombre
			//$pdf->writeHTMLCell('', '', $x='', $y=$posFoto, $html, $border=0, $ln=1, $fill=0, $reseth=false, $align='C', $autopadding=false);

			//$posFoto += 3;

			// Categoria
			//$pdf->SetFont('helvetica', '', 14);
			//$categoria = $this->getNombreCategoria($idEvento, $acompanante->acm_clave);
			//$pdf->writeHTMLCell('40', '5', $x='56', $y='102', $categoria, $border=0, $ln=1, $fill=0, $reseth=false, $align='C', $autopadding=false);

			if ($count == 6)
			{
				$count = 0;

				$pdf->AddPage();

				continue;
			}

			$count++;
		}
		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$pdf->Output('repo_asistentes_' . time() . '.pdf', 'I');
	}

	public function reporteCajero($post, $evento, $func)
	{
		// Cargamos la libreria para excel
		ini_set("include_path", './'. PATH_SMVC . '/' . PATH_EXT . '/PEAR/');
		require ("PEAR.php");
		require_once ('Spreadsheet/Excel/Writer.php');

		$registros = array();
		$fecha_inicio = new \DateTime($func->date2db($post['fecha_inicio']));
		$fechas = "AND c.fecha_cobro >= '" .  $fecha_inicio->format('Y-m-d') . "'";

		if (!empty($post['fecha_cierre']))
		{
			$fecha_cierre = new \DateTime($func->date2db($post['fecha_cierre']));
			$fecha_cierre->add(new \DateInterval('P1D'));
			$fechas = "AND c.fecha_cobro BETWEEN '" . $fecha_inicio->format('Y-m-d') . "' AND '" . $fecha_cierre->format('Y-m-d') . "'";
		}

		$qry = "SELECT r.nombre, r.app, r.apm, c.folio_pago,
			c.forma_pago, c.costo_total 
			FROM smc_reg_%s_caja AS c  
			JOIN smc_reg_%s AS r ON (r.id_registro = c.id_registro) 
			WHERE c.id_usuario = %d 
			%s
		";

		$qry = sprintf($qry,
			$evento->evt_clave,
			$evento->evt_clave,
			$post['cajero'],
			$fechas
		);

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				$campos = $this->db->hFetchFields();
				while ($registro = $this->db->hFetchObject())
				{
					$registros[] = $registro;
				}

				$this->db->hFree();
			}
		}

		$workbook = new \Spreadsheet_Excel_Writer ();

		$fTitleHead = $workbook->addFormat ();
		$fTitleHead->setBold ();

		$workbook->setVersion ( 8 );
		$worksheet = $workbook->addWorksheet ( 'Reporte' );

		if (is_array ( $registros ) && ! empty ( $registros ))
		{
			$qry_fpn = "SELECT fpn_clave, fpn_nombre 
				FROM smc_eventoFormasPagoNombres 
				WHERE fpn_idEvento = %d
			";

			$qry_fpn = sprintf($qry_fpn, $evento->evt_idEvento);
			$formas_pago = array();

			if ($this->db->hQuery($qry_fpn))
			{
				if ($this->db->hNumRows() >= 1)
				{
					$campos = $this->db->hFetchFields();
					while ($fila = $this->db->hFetchObject())
					{
						$formas_pago[] = $fila;
					}

					$this->db->hFree();
				}
			}

			$qry_cajero = "SELECT usr_nombre, usr_app, usr_apm 
				FROM smc_usuarios  
				WHERE usr_idUsuario = %d
			";

			$qry_cajero = sprintf($qry_cajero,  $post['cajero']);
			$cajero = array();

			if ($this->db->hQuery($qry_cajero))
			{
				if ($this->db->hNumRows() == 1)
				{
					$cajero = $this->db->hFetchObject();
					$this->db->hFree();
				}
			}

			$worksheet->setInputEncoding ( 'utf-8' );
			$worksheet->writeString ( 0, 0, "REPORTE DE INGRESOS", $fTitleHead );
			$worksheet->writeString ( 3, 0, 'CAJERO: ', $fTitleHead);
			$worksheet->writeString ( 3, 1, $cajero->usr_nombre . ' ' . $cajero->usr_app . ' ' . $cajero->usr_apm);
			$worksheet->writeString ( 4, 0, 'FECHA:', $fTitleHead);
			$worksheet->writeString ( 4, 1, $post['fecha_inicio']);
			if (!empty($post['fecha_cierre']))
			{
				$worksheet->writeString ( 4, 2, $post['fecha_cierre']);
			}

			// Columnas titulos
			$campos_name = array('NOMBRE', 'PATERNO', 'MATERNO', 'FOLIO DE PAGO');
			foreach ($campos_name as $k => $campo) {
				$worksheet->writeString ( 6, $k, $campo, $fTitleHead );
			}
			
			// Columnas formas de pago
			$i_forma_pago = 4;
			foreach ($formas_pago as $forma_pago) {
				$worksheet->writeString ( 6, $i_forma_pago, $forma_pago->fpn_nombre, $fTitleHead );
				$i_forma_pago++;
			}

			$cBody = & $workbook->addFormat(
				array(
					'Size' => 12,
					'Align' => 'left'
				)
			);

			$fila = 7;
			foreach ( $registros as $registro )
			{
				$campo = 0;
				foreach ( $registro as $k => $valor )
				{
					if ($k == 'forma_pago')
					{
						$i_forma_pago = 4;
						foreach ($formas_pago as $forma_pago)
						{
							if ($valor == $forma_pago->fpn_clave)
							{
								$cBody->setNumFormat('_-\$* #,##0.00_-;"-$"* #,##0.00_-;_-\$* -??_-;_-@_-');
								//$worksheet->($this->xlsFila, $this->xlsCampo, $value, $cBody);
								$worksheet->writeNumber ( $fila, $i_forma_pago, $registro->costo_total, $cBody);
								break 2;
							}
							$i_forma_pago++;
						}
					}
					else
					{
						$worksheet->writeString ( $fila, $campo, $valor );
					}

					$campo ++;
				}

				$fila ++;
			}

			//$total_filas = count($registros) + $fila;
			$formula = $fila + 1;
			$cBody = & $workbook->addFormat(
				array(
					'Size' => 14,
					'Align' => 'left'
				)
			);
			$cBody->setBold ();
			$worksheet->writeString ( $formula, 0, 'SUB TOTAL:', $cBody );

			$i_forma_pago = 4;
			$columnas = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N');
			foreach ($formas_pago as $forma_pago) {
				$cBody->setNumFormat('_-\$* #,##0.00_-;"-$"* #,##0.00_-;_-\$* -??_-;_-@_-');
				$worksheet->writeFormula($formula, $i_forma_pago, "=SUM(" . $columnas[$i_forma_pago] . "8:" . $columnas[$i_forma_pago] . $formula . ")", $cBody);
				$i_forma_pago++;
			}

			$workbook->send ( "rep_" . $func->capitalizarStr($cajero->usr_nombre) . ".xls" );
			$workbook->close ();

		}
		else
		{
			echo 'No hay registros para exportar, cierre esta pestaña del navegador.';
		}
	}

	public function expReporteGeneral($post, $evento, $func)
	{
		// Cargamos la libreria para excel
		ini_set("include_path", './'. PATH_SMVC . '/' . PATH_EXT . '/PEAR/');
		require ("PEAR.php");
		require_once ('Spreadsheet/Excel/Writer.php');

		$registros = array();

		$qry = "SELECT r.id_registro, n.enc_nombre AS categoria, r.nombre, r.app, r.apm,
			r.emp_o_ins AS empresa, p.pais_nombreEs AS pais, r.status, r.impresion_gafete AS Llegado 
			FROM smc_reg_%s AS r 
			JOIN smc_eventoCategoriasNombres AS n ON (n.enc_clave = r.cat_registro) 
			JOIN smc_catPaises AS p ON (p.pais_idPais = r.pais) 
		";

		$qry = sprintf($qry, $evento->evt_clave);

		if ($result = $this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				$campos = $this->db->hFetchFields();
				while ($fila = $this->db->hFetchObject())
				{
					$registros[] = $fila;
				}

				$this->db->hFree();
			}
		}

		$workbook = new \Spreadsheet_Excel_Writer ();

		$fTitleHead = $workbook->addFormat ();
		$fTitleHead->setBold ();

		$workbook->setVersion ( 8 );
		$worksheet = $workbook->addWorksheet ( 'Registros' );

		if (is_array ( $registros ) && ! empty ( $registros ))
		{

			$worksheet->write ( 0, 1, "Reporte General", $fTitleHead );

			foreach ( $campos as $k => $campo )
			{
				$worksheet->write ( 1, $k, $campo->name );
			}

			$fila = 3;
			foreach ( $registros as $registro )
			{

				$campo = 0;
				foreach ( $registro as $k => $valor )
				{

					$worksheet->setInputEncoding ( 'utf-8' );

					$worksheet->writeString ( $fila, $campo, $valor );

					$campo ++;

				}

				$fila ++;

			}

			$workbook->send ( "general.xls" );
			$workbook->close ();

		}
	}

	public function expReporteGeneralItems($post, $evento, $func)
	{
		// Cargamos la libreria para excel
		ini_set("include_path", './'. PATH_SMVC . '/' . PATH_EXT . '/PEAR/');
		require ("PEAR.php");
		require_once ('Spreadsheet/Excel/Writer.php');

		$registros = array();

		$qry = "SELECT r.id_registro, r.app, r.apm, r.nombre, ein.eni_nombre, i.item_cantidad,
			i.item_status 
			FROM smc_reg_%s AS r 
			JOIN smc_reg_%s_items as i ON (i.item_idRegistro = r.id_registro) 
			JOIN smc_eventoItemsNombres ein ON (ein.eni_clave = i.item_clave) 
			WHERE ein.eni_idioma = 'es' 
			ORDER BY r.app, r.apm, r.nombre, ein.eni_nombre, i.item_status ASC
		";

		$qry = sprintf($qry, $evento->evt_clave, $evento->evt_clave);

		if ($result = $this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				$campos = $this->db->hFetchFields();
				while ($fila = $this->db->hFetchObject())
				{
					$registros[] = $fila;
				}

				$this->db->hFree();
			}
		}

		$workbook = new \Spreadsheet_Excel_Writer ();

		$fTitleHead = $workbook->addFormat ();
		$fTitleHead->setBold ();

		$workbook->setVersion ( 8 );
		$worksheet = $workbook->addWorksheet ( 'Adicionales' );

		if (is_array ( $registros ) && ! empty ( $registros ))
		{
			$worksheet->write ( 0, 1, "Reporte Adicionales", $fTitleHead );

			foreach ( $campos as $k => $campo )
			{
				$worksheet->write ( 1, $k, $campo->name );
			}

			$fila = 3;
			foreach ( $registros as $registro )
			{
				$campo = 0;
				foreach ( $registro as $k => $valor )
				{
					$worksheet->setInputEncoding ( 'utf-8' );
					$worksheet->writeString ( $fila, $campo, $valor );
					$campo ++;
				}

				$fila ++;
			}

			$workbook->send ( "adicionales.xls" );
			$workbook->close ();
		}
	}

	public function expReporteGeneralAcomp($post, $evento, $func)
	{
		// Cargamos la libreria para excel
		ini_set("include_path", './'. PATH_SMVC . '/' . PATH_EXT . '/PEAR/');
		require ("PEAR.php");
		require_once ('Spreadsheet/Excel/Writer.php');

		$registros = array();

		$qry = "SELECT r.id_registro, r.nombre, r.app, r.apm, a.acm_clave,
			c.ecan_nombre AS Nombre_categoria, a.acm_genero, a.acm_titulo,
			a.acm_nombre, a.acm_app, a.acm_apm, a.acm_costo, a.acm_status,
			a.acm_comentarios 
			FROM smc_reg_%s AS r 
			JOIN smc_reg_%s_acom AS a ON (a.acm_idRegistro = r.id_registro) 
			JOIN smc_eventoCategoriasAcomNombres AS c ON (c.ecan_clave = a.acm_clave) 
			WHERE 1 
			ORDER BY r.id_registro ASC
		";

		$qry = sprintf($qry, $evento->evt_clave, $evento->evt_clave);

		if ($result = $this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				$campos = $this->db->hFetchFields();
				while ($fila = $this->db->hFetchObject())
				{
					$registros[] = $fila;
				}

				$this->db->hFree();
			}
		}

		$workbook = new \Spreadsheet_Excel_Writer ();

		$fTitleHead = $workbook->addFormat ();
		$fTitleHead->setBold ();

		$workbook->setVersion ( 8 );
		$worksheet = $workbook->addWorksheet ( 'Acompanantes' );

		if (is_array ( $registros ) && ! empty ( $registros ))
		{
			$worksheet->write ( 0, 1, "Reporte Acompanantes", $fTitleHead );

			foreach ( $campos as $k => $campo )
			{
				$worksheet->write ( 1, $k, $campo->name );
			}

			$fila = 3;
			foreach ( $registros as $registro )
			{
				$campo = 0;
				foreach ( $registro as $k => $valor )
				{
					$worksheet->setInputEncoding ( 'utf-8' );
					$worksheet->writeString ( $fila, $campo, $valor );
					$campo ++;
				}

				$fila ++;
			}

			$workbook->send ( "acompanantes.xls" );
			$workbook->close ();
		}
	}

	public function expReporteRFID($post, $evento, $func)
	{
		// Cargamos la libreria para excel
		ini_set("include_path", './'. PATH_SMVC . '/' . PATH_EXT . '/PEAR/');
		require ("PEAR.php");
		require_once ('Spreadsheet/Excel/Writer.php');

		$registros = array();

		$qry = "SELECT r.id_registro AS id, r.nombre, r.app, r.apm, rf.* 
			FROM smc_reg_%s_rfid AS rf 
			JOIN smc_reg_%s AS r ON (r.id_tag = rf.id_tag) 
			WHERE 1
		";

		$qry = sprintf($qry, $evento->evt_clave, $evento->evt_clave);

		if ($result = $this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() >= 1)
			{
				$campos = $this->db->hFetchFields();
				while ($fila = $this->db->hFetchObject())
				{
					$registros[] = $fila;
				}

				$this->db->hFree();
			}
		}

		$workbook = new \Spreadsheet_Excel_Writer ();

		$fTitleHead = $workbook->addFormat ();
		$fTitleHead->setBold ();

		$workbook->setVersion ( 8 );
		$worksheet = $workbook->addWorksheet ( 'Registros' );

		if (is_array ( $registros ) && ! empty ( $registros ))
		{

			$worksheet->write ( 0, 1, "Reporte General RFID", $fTitleHead );

			foreach ( $campos as $k => $campo )
			{
				$worksheet->write ( 1, $k, $campo->name );
			}

			$fila = 3;
			foreach ( $registros as $registro )
			{

				$campo = 0;
				foreach ( $registro as $k => $valor )
				{

					$worksheet->setInputEncoding ( 'utf-8' );

					$worksheet->writeString ( $fila, $campo, $valor );

					$campo ++;

				}

				$fila ++;

			}

			$workbook->send ( "general.xls" );
			$workbook->close ();

		}
	}
}
?>