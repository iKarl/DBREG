<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/libs/
 * @version $Id: class.Pdf.php 1.0 2011-09-27 22:55 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com, son_gohan_khan@hotmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Model\Model_Controller AS Model;

require_once('./'. PATH_SMVC . '/' . PATH_EXT . '/tcpdf/config/lang/eng.php');
require_once('./'. PATH_SMVC . '/' . PATH_EXT . '/tcpdf/tcpdf.php');

		// Extend the TCPDF class to create custom Header and Footer
		class MyPDF extends TCPDF
		{
			//Page header
			public function Header()
			{
				// Logo
				$image_file = K_PATH_IMAGES.'logo_example.jpg';
				//$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
				// Set font
				$this->SetFont('helvetica', 'B', 20);
				// Title
				$this->Cell(0, 1, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
			}

			// Page footer
			public function Footer()
			{
				// Position at 15 mm from bottom
				$this->SetY(-15);
				// Set font
				$this->SetFont('helvetica', 'I', 8);
				// Page number
				$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
			}
		}

class Pdf
{

	public function reporteFotosPDF($registros, $func)
	{

		// create new PDF document
		$pdf = new MyPDF(PDF_PAGE_ORIENTATION, 'cm', PDF_PAGE_FORMAT, true, 'UTF-8', false);

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
		$pdf->setLanguageArray('es');

		// ---------------------------------------------------------

		// set font
		$pdf->SetFont('helvetica', '', 9);

		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage(); // medidas en inch

		//$pdf->Image($img_file, 0, 0, 8.5, 5.5, '', '', '', false, 300, '', false, false, 0);

		$count = 0;
		$posFoto = 6;
		foreach ($registros as $key => $registro)
		{
			if ($count == 0)
			{
				$pdf->Ln(6);
			}
			else
			{
				$pdf->Ln(1);

				if ($count = 6)
				{
					$count = 0;
				}
			}

			if (isset($registro->foto_fotografia))
			{
				//echo '<img src="data:' . $registro->foto_mime . ';base64,' . $registro->foto_fotografia . '" />';
				$pdf->Image('@' . base64_decode($registro->foto_fotografia), 18.7, $posFoto, 1.5, 2);
			}

			// Set some content to print
			$html = '<table border="1" width="95%">';
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
			$pdf->writeHTML($html, true, false, false, false, 'C');
			// Nombre
			//$pdf->writeHTMLCell('', '', $x='', $y=$posFoto, $html, $border=0, $ln=1, $fill=0, $reseth=false, $align='C', $autopadding=false);

			$posFoto += 4;

			// Categoria
			//$pdf->SetFont('helvetica', '', 14);
			//$categoria = $this->getNombreCategoria($idEvento, $acompanante->acm_clave);
			//$pdf->writeHTMLCell('40', '5', $x='56', $y='102', $categoria, $border=0, $ln=1, $fill=0, $reseth=false, $align='C', $autopadding=false);

			$count++;
		}
		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$pdf->Output('repo_asistentes_' . time() . '.pdf', 'I');
	}

}
?>