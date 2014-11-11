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
$ubicacion=$_GET["ubicacion"];
$codigo_seccion=$_GET["codigo_seccion"];

	  $sql = "SELECT * FROM bienes WHERE ubicacion='$ubicacion' AND codigo_seccion='$codigo_seccion';";
$resultado=mysql_query($sql);

	                
          	$result=mysql_query($sql);

	while ($row = mysql_fetch_array($result)){


         $codigo_seccion=		$row['codigo_seccion'];
		 $ubicacion=			$row['ubicacion'];$codigo_i=				$row['codigo_i'];
		 $codigo_d=				$row['codigo_d'];
         if($codigo_i=="")
	{$codigo_busqueda=$codigo_d;
	}
	else
	{$codigo_busqueda=$codigo_i;
	}
}
          		
	if($codigo_seccion!="")
	{$cadbusca0="SELECT * FROM seccion WHERE codigo_seccion='$codigo_seccion';";
	$result=mysql_query($cadbusca0);

	while ($row = mysql_fetch_array($result)){
	$descripcion=			$row['descripcion']; 
	}
}


	if($ubicacion!="")
	{$cadbusca0="SELECT * FROM ubicacion WHERE codigo_ubicacion='$ubicacion';";
	$result=mysql_query($cadbusca0);

	while ($row = mysql_fetch_array($result)){
	$descripcion_ubic=			$row['descripcion_ubic']; 
	
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


$descripcion_ubic= remplace ($descripcion_ubic); 
$descripcion= remplace ($descripcion);
$estatus1= remplace ($estatus1);
$estatus2= remplace ($estatus2);

$inventario.="

<table  border='0' width='99%'>
		<tr>
		<td width='150' align='left'><img src='../images/logo_nuevo1.jpg' /></td>
		<td width='67%'><div align='center'><b><br /><br />REPORTE POR TIPO DE BIENES </b></div></td>
		<td width='120' align='center'><b><br /><br /></b></td>
		</tr>";
if($descripcion!="TODAS") {$descripcion;} else {$descripcion="";}
$inventario.="</table>	
  
<table width='99%' border='0' align='center' cellpadding='2' cellspacing='2'>

  		<tr style='background-color:#FFFFFF;'>
  		<td width='170' align='left'><b>ENTIDAD PROPIETARIA</b></td>
  		<td width='180' colspan='2' align='left'>Contralor&iacute;a del Estado Bolivar</td>
  		<td width='80' align='left'><b>SERVICIO:</b></td>
    	<td width='350' colspan='4' align='left'>$descripcion_ubic</td>
		</tr>
  		<tr>
  		<td width='170' align='left'><b>UNIDAD DE TRABAJO O AREA:</b></td>
    	<td width='600' colspan='6' align='left'>$descripcion</td>
		</tr>
  		<tr>
  		<td width='170' align='left'><b>ESTADO:</b></td>
  		<td width='60' align='left'>Bol&iacute;var</td>
  		<td width='90' align='left'><b>DISTRITO:</b></td>
  		<td width='60' align='left'>HERES</td>
   		<td width='92' colspan='2' align='left'><b>MUNICIPIO:</b></td>
  		<td align='left' width='350'>HERES</td>
  		</tr>
    	
  		<tr>
    	<td align='left' width='170'><b>DIRECCI&Oacute;N O LUGAR:</b></td>
    	<td width='350' align='left' colspan='4'>Bulevar Bol&iacute;var, Casco Hist&oacute;rico, Edificio Roque Center N&ordm; 63</td>
   	 	<td width='313'>&nbsp;</td>
		</tr>
</table>
<br>
<br>
		
<table width='90%' border='1' align='center'>
    
   	 	<tr>
        <td colspan='3' width='135' align='center'><b>Clasificaci&oacute;n</b></td>
		<td rowspan='2' width='80' align='center'><b> N&ordm; <br />Identificaci&oacute;n</b></td>
		<td rowspan='2' width='55%' align='center'><b><br /> Nombre y Descripci&oacute;n de los Elementos</b></td>
   	    <td rowspan='2' width='160' align='center'><br /><br />Estatus</td>
		<td rowspan='2' width='80' align='center'><br /><br /> <b> Ubicacion</b></td>
		</tr>
		<tr>
      	<td width='40' align='center'><b>Grupo</b></td>
		<td width='45' align='center'><b>Sub-<br />Grupo</b></td>
		<td width='50' align='center'><b>Secci&oacute;n</b></td>
		</tr>";
		  

$clasificacion=$_GET["clasificacion"];
$codigo_seccion=$_GET["codigo_seccion"];
$ubicacion=$_GET["ubicacion"];
if($codigo_seccion==$ubicacion)
{ $seccion = "";} 
else
 { $seccion = " AND codigo_seccion='$codigo_seccion' ";}
if($clasificacion!="" && $codigo_seccion!="" && $ubicacion!=""){


	  $sql = "SELECT * FROM bienes WHERE clasificacion='$clasificacion' $seccion AND ubicacion='$ubicacion' ORDER BY codigo_bien ASC;";
	  
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
$estatus=$registro->estatus	;
$codigo_seccion=$registro->codigo_seccion	;
//$marca=$registro->marca;
//$modelo=$registro->modelo	;
$ubicacion=$registro->ubicacion	;
$estatus1= remplace ($estatus1);
$estatus2= remplace ($estatus2);


	$inventario.="<tr>
 	  	<td width='40' align='center'>$grupo</td>
      	<td width='45' align='center'>$subgrupo</td>
      	<td width='50' align='center'>$seccion</td>
		<td width='80' align='center'>$codigo_bien</td>
        <td width='55%' align='center'>$denominacion&nbsp;$descripcion_adicional</td>
		<td width='160' align='center'>$estatus&nbsp;/&nbsp;$estatus1$estatus2</td>
		<td width='80' align='center'>$codigo_seccion</td>
    	</tr>";

}
  
 	$inventario.="	</table>

  		<table width='100%' border='1'>
        <tr>
        </tr>
        </table>";
		
		
  
// echo $inventario;
$vocales = array ( "'");
$inventario = str_replace ($vocales, '"', $inventario);
$pdf->writeHTML($inventario, true, false, false, false, '');
$pdf->Output('Clasificacion.pdf', 'I');
}	
	 
	?>