<?php

include('remplace1.php');
include('conexion.php');

require_once('../config/lang/eng.php');
require_once('../tcpdf.php');
$tabla1=$_GET['tabla'];

 
 $tabla=nl2br($tabla);
$tabla=remplace($tabla1);

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 

//set some language-dependent strings
$pdf->setLanguageArray($l); 

// ---------------------------------------------------------

$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 8);
$pdf->Write($h=0, $txt, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

$pdf->AddPage();

$conex=conectarse();
$usuario=$_SESSION['s_usuario'];
$encargado=$_GET['usuario'];

$estatus=$_GET["estatus"];

	 $sql = "SELECT * FROM bienes WHERE estatus='$estatus';";
$resultado=mysql_query($sql);

	                
          	$result=mysql_query($sql);

	while ($row = mysql_fetch_array($result)){


         $estatus=				$row['estatus'];
		 $codigo_i=				$row['codigo_i'];
		 $codigo_d=				$row['codigo_d'];
         if($codigo_i=="")
	{$codigo_busqueda=$codigo_d;
	}
	else
	{$codigo_busqueda=$codigo_i;
	}
         
          		
}

	if($clasificacion!="")
	{$cadbusca0="SELECT * FROM clasificacion WHERE clasificacion='$clasificacion';";
	$result=mysql_query($cadbusca0);

	while ($row = mysql_fetch_array($result)){
	$tipo_de_bien=			$row['tipo_de_bien']; 
	}
}
if($codigo_i!="")
	{$cadbusca0="SELECT * FROM incorporacion WHERE codigo_i=$codigo_busqueda;";
	$result=mysql_query($cadbusca0);

	while ($row = mysql_fetch_array($result)){
	$concepto=			$row['concepto']; 
	$estatus2=			$row['estatus2']; 
	}
	
	
}

	if($codigo_d!="")
	{$cadbusca0="SELECT * FROM desincorporacion WHERE codigo_d=$codigo_busqueda;";
	$result=mysql_query($cadbusca0);

	while ($row = mysql_fetch_array($result)){
	$concepto=			$row['concepto'];
	$estatus1=			$row['estatus1']; 
	}
	
	
}

$estatus= remplace ($estatus);
$tipo_de_bien= remplace ($tipo_de_bien);
$estatus1= remplace ($estatus1);
$estatus2= remplace ($estatus2);

$inventario.="

<table  border='0' width='99%'>
		<tr>
		<td width='150' align='left'><img src='../images/logo_nuevo1.jpg' /></td>
		<td width='67%'><div align='center'><b><br /><br />REPORTE GENERAL SEG&Uacute;N SU ESTATUS </b></div></td>
		<td width='120' align='center'><b><br /><br /></b></td>
		</tr>";

$inventario.="</table>	
  <br>
<table width='99%' border='0' align='center' cellpadding='2' cellspacing='2'>

  		<tr style='background-color:#FFFFFF;'>
  		<td width='140' align='left'><b>ENTIDAD PROPIETARIA</b></td>
  		<td width='600' colspan='6' align='left'>Contralor&iacute;a del Estado Bolivar</td>
  		</tr>
  		<tr>
  		<td width='140' align='left'><b>ESTATUS:</b></td>
  		<td width='600' align='left' colspan='6'>$estatus</td>
		</tr>
 </table>
<br>
	
<table width='90%' border='1' align='center'>
    
   	 	<tr>
        <td colspan='3' width='135' align='center'><b>Clasificaci&oacute;n</b></td>
		<td rowspan='2' width='80' align='center'><b> N&ordm; <br />Identificaci&oacute;n</b></td>
		<td rowspan='2' width='58%' align='center'><b><br /> Nombre y Descripci&oacute;n de los Elementos</b></td>
		<td rowspan='2' width='60' align='center'><br /><br />Precio</td>
   	    <td rowspan='2' width='80' align='center'><br /><br />Estatus</td>
		<td rowspan='2' width='70' align='center'><br /><br /> <b> Ubicacion</b></td>
		</tr>
		<tr>
      	<td width='40' align='center'><b>Grupo</b></td>
		<td width='45' align='center'><b>Sub-<br />Grupo</b></td>
		<td width='50' align='center'><b>Secci&oacute;n</b></td>
		</tr>";
		  

$estatus=$_GET["estatus"];

if($estatus!=""){


	  $sql = "SELECT * FROM bienes WHERE estatus='$estatus' ORDER BY clasificacion ASC;";
	  
$resultado=mysql_query($sql);
                 $cantidad=mysql_num_rows($resultado);
                 $contador=0;
             while ($registro=mysql_fetch_object($resultado))
              { $contador++;
	  	
$clasificar=$registro->clasificacion;
$cla=explode("-",$clasificar);
$grupo=$cla[0];
$subgrupo=$cla[1];
$seccion=$cla[2];
$seccion=$registro->seccion;
if($seccion!="") {$seccion;} else {$seccion="&nbsp;";}
$codigo_bien=$registro->codigo_bien	;
$denominacion=remplace ($registro->denominacion);
$descripcion_adicional=remplace ($registro->descripcion_adicional);
$codigo_seccion=remplace ($registro->codigo_seccion);
$precio=$registro->precio	;
$estatus=$registro->estatus	;
$estatus1= remplace ($estatus1);
$estatus2= remplace ($estatus2);


	$inventario.="<tr>
 	  	<td width='40' align='center'>$grupo</td>
      	<td width='45' align='center'>$subgrupo</td>
      	<td width='50' align='center'>$seccion</td>
		<td width='80' align='center'>$codigo_bien</td>
        <td width='58%' align='left'>&nbsp;$denominacion&nbsp;$descripcion_adicional</td>
		<td width='60' align='center'>$precio</td>
		<td width='80' align='center'>$estatus1$estatus2</td>
		<td width='70' align='center'>$codigo_seccion</td>
    	</tr>";

}
  
 	$inventario.="	</table>";

 		
  
// echo $inventario;
$vocales = array ( "'");
$inventario = str_replace ($vocales, '"', $inventario);
$pdf->writeHTML($inventario, true, false, false, false, '');
$pdf->Output('Clasificacion.pdf', 'I');
}	
	 
	?>