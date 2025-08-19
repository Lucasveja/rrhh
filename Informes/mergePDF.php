<?php
$hoy = date("Y_m_d_His");
include 'PDFMerger.php';

$pdf = new PDFMerger;
$pdf1 = new PDFMerger;

$i=$_GET[cant];
for($j=1;$j<=$i;$j++){
	$n="PDF/PARTES/Informe_Completo_Parte_{$j}.pdf";
	$pdf->addPDF($n, 'all');
	$pdf1->addPDF($n, 'all');
}
// $pdf->addPDF('PDF/PARTES/Informe_Completo_Parte_1.pdf', 'all');
// $pdf->addPDF('PDF/PARTES/Informe_Completo_Parte_2.pdf', 'all');

$name="Informe_Completo_Parte_{$hoy}";

$path="../Informes/PDF/{$name}.pdf";
//$pdf->merge('file', $name);
ob_clean(); // cleaning the buffer before Output()
//$pdf->merge('file', $name);
$pdf->merge('browser', $path);
//$pdf1->merge('browser', $path);

	
	//REPLACE 'file' WITH 'browser', 'download', 'string', or 'file' for output options
	//You do not need to give a file path for browser, string, or download - just the name.

 ?>

