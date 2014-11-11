<?php
define('FPDF_FONTPATH','font/');
include "script/Barcode39.php"; 
require('fpdf.php');
require('fphp.php');
require('tcpdf/tcpdf.php');
connect(); 
mysql_query("SET NAMES 'utf8'");
extract ($_POST);
extract ($_GET);
function borrar_directorio($dir) { 
   if (is_dir($dir)) { 
     $objetos = scandir($dir); 
     foreach ($objetos as $objeto) { 
       if ($objeto != "." && $objeto != "..") { 
         if (filetype($dir."/".$objeto) == "dir") borrar_directorio($dir."/".$objeto); else unlink($dir."/".$objeto); 
       } 
     } 
     reset($objetos); 
     rmdir($dir); 
   } 
 } 
//global $Periodo;
//echo $_SESSION["MYSQL_BD"];
/// ----------------------------------------------------
//---------------------------------------------------
$filtro=strtr($filtro, "*", "'");

//// ---- Consulta para obtener datos 
$sactivo = "select 
				  a.*, 
				  b.Descripcion as DescpClasificacion20,
				  c.Descripcion as DescpUbicacion,
                                  d.Descripcion as CentroCosto,
                                  e.Dependencia as Dependencia
			  from
				  af_activo a 
				  inner join af_clasificacionactivo20 b on (b.CodClasificacion=a.ClasificacionPublic20) 
				  inner join af_ubicaciones c on (c.CodUbicacion=a.Ubicacion)
                                  inner join ac_mastcentrocosto d on (d.CodCentroCosto=a.CentroCosto)
				  inner join mastdependencias e on (e.CodDependencia=a.CodDependencia)				  
			 where 
			      a.CodOrganismo<>'' $filtro
   			ORDER BY a.ClasificacionPublic20, a.Descripcion"; //echo $sactivo;
$qactivo = mysql_query($sactivo) or die ($sactivo.mysql_error());
$ractivo = mysql_num_rows($qactivo);

$pdf=new FPDF('L','mm',array(35,60));

if($ractivo!=0)
{
   for($i=0; $i<$ractivo; $i++)
   {
      
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',5);
    $pdf->SetMargins(0, 0, 0);
      $factivo = mysql_fetch_array($qactivo);
          
        $codigobarra = new Barcode39($factivo['CodigoInterno']); 

        // set text size 
        $codigobarra->barcode_text_size = 5; 

        // set barcode bar thickness (thick bars) 
        $codigobarra->barcode_bar_thick = 4; 

        // set barcode bar thickness (thin bars) 
        $codigobarra->barcode_bar_thin = 2; 

        // save barcode GIF file
        $ruta="codigo_barra";
        mkdir($ruta, 0755);
        $codigobarra->draw($ruta."/codigo_barra".$i.".gif");
        $ubicacion_tmp=explode(" - ",utf8_decode($factivo['DescpUbicacion']));
        $ubicacion=$ubicacion_tmp[1];
	$pdf->Image('../imagenes/logos/contraloria.jpg', 1, 2.3, 12.1, 6.7);
        $pdf->SetXY(15, 2); $pdf->Cell(40, 2.5,utf8_decode('REPÚBLICA BOLIVARIANA DE VENEZUELA'), 0, 1, 'L');
	$pdf->SetXY(18, 4); $pdf->Cell(40, 2.5,utf8_decode('CONTRALORÍA DEL ESTADO MONAGAS'), 0, 1, 'L');
        $pdf->SetXY(22, 6); $pdf->Cell(40, 2.7,utf8_decode('MATURÍN - ESTADO MONAGAS'), 0, 1, 'L');
        
        $pdf->SetFont('Arial','B',5);
        
        $pdf->SetXY(0, 10); $pdf->Cell(40,0.8,utf8_decode('DIRECCIÓN:'), 0, 1, 'L');
        $pdf->SetXY(15, 10); $pdf->Cell(40,0.8,utf8_decode($factivo['Dependencia']), 0, 1, 'L');
	$pdf->SetXY(0, 12); $pdf->Cell(40,0.8,utf8_decode('DEPENDENCIA:'), 0, 1, 'L');
        $pdf->SetXY(15, 12); $pdf->Cell(40,0.8,strtoupper($ubicacion), 0, 1, 'L');
        $pdf->SetXY(0, 14); $pdf->Cell(40,0.8,utf8_decode('DESCRIPCIÓN:'), 0, 1, 'L');
        $pdf->SetXY(15, 14); $pdf->Cell(40,0.8,strtoupper(utf8_decode($factivo['DescpClasificacion20'])), 0, 1, 'L');
        $pdf->Image($ruta.'/codigo_barra'.$i.'.gif',5,16,50,17.3);
	$CodDependencia = $factivo['CodDependencia'];
	
	  
	 // $pdf->Row(array($cod, $cod2, $cod3, '1', $factivo['CodigoInterno'], utf8_decode($factivo['Descripcion']),$MONTO,$MONTO));
   }
   $pdf->Output();
   borrar_directorio($ruta);
}
              
	
	

?>  
