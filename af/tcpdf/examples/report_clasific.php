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
		 $ubicacion=			$row['ubicacion'];
         
          		
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

$descripcion_ubic=remplace ($descripcion_ubic); 
$descripcion=remplace ($descripcion);

if($_GET){
$fecha_ini=$_GET["fecha_ini"];
/*$fch=explode("/",$fecha_ini);
$fecha_ini=$fch[2]."-".$fch[1]."-".$fch[0];
*/
$fecha_fin=$_GET["fecha_fin"];
/*
$fch=explode("/",$fecha_fin);
$fecha_fin=$fch[2]."-".$fch[1]."-".$fch[0];
*/
if($fecha_fin=="--")
{ $fecha = " ";} 
else
{ $fecha = "fecha_incorporacion >='$fecha_ini' AND fecha_incorporacion<='$fecha_fin'AND ";}
$codigo_seccion=$_GET["codigo_seccion"];
$estatus=$_GET["estatus"];
if($estatus=="FALTANTE")
{ $estatus = " ";} 
$clasificacion=$_GET["clasificacion"];
$codigo_seccion=$_GET["codigo_seccion"];
$ubicacion=$_GET["ubicacion"];
if($codigo_seccion==$ubicacion)
{ $seccion = "";} 
else
 { $seccion = "  codigo_seccion='$codigo_seccion' AND ";}
if($clasificacion!="" && $codigo_seccion!="" && $ubicacion!=""){

		if($fecha_fin=="--")
{ $fecha2 = " ";}
else   	  
		{$fecha2 = "fecha_desincorporacion >='$fecha_ini' AND fecha_desincorporacion<='$fecha_fin'AND ";} 
		
		$sql2 = "SELECT * FROM bienes WHERE clasificacion='$clasificacion' AND $fecha2 ubicacion='$ubicacion' AND $seccion  estatus!='FALTANTE' AND estatus!='INCORPORADO' ORDER BY codigo_bien ASC;";



	  $sql = "SELECT * FROM bienes WHERE clasificacion='$clasificacion' AND $fecha ubicacion='$ubicacion' AND $seccion  estatus!='FALTANTE' AND estatus!='DESINCORPORADO' ORDER BY codigo_bien ASC;";
		
$resul=mysql_query($sql); 
		  $filas=mysql_num_rows($resul);
		//  echo $filas/12;
		  if($filas>0)
		  {for($e=0;$e<3;$e++)
		  {
				  
$inventario.="

	<table  border='0' width='99%'>
		<tr>
		<td width='150' align='left'><img src='../images/logo_nuevo1.jpg' /></td>
		<td width='63%'><div align='center'><b><br /><br />INVENTARIO DE BIENES MUEBLES</b></div></td>
		<td width='120' align='center'><b><br /><br />FORMULARIO MB-1</b></td>
		</tr>";

if($descripcion!="TODAS") {$descripcion;} else {$descripcion="";}
$inventario.="	  
<table width='99%' border='0' align='center' cellpadding='2' cellspacing='2'>

  		<tr style='background-color:#FFFFFF;'>
  		<td width='160' align='left'><b>ENTIDAD PROPIETARIA</b></td>
  		<td width='180' colspan='2' align='left'>Contralor&iacute;a del Estado Bolivar</td>
  		<td width='80' align='left'><b>SERVICIO:</b></td>
    	<td width='450' colspan='3' align='left'>$descripcion_ubic</td>
		</tr>
  		<tr>
  		<td width='160' align='left'><b>UNIDAD DE TRABAJO:</b></td>
    	<td width='600' colspan='6' align='left'>$descripcion</td>
		</tr>
  		<tr>
  		<td width='160' align='left'><b>ESTADO:</b></td>
  		<td width='60' align='left'>Bol&iacute;var</td>
  		<td width='90' align='left'><b>DISTRITO:</b></td>
  		<td width='60' align='left'>HERES</td>
   		<td width='92' colspan='2' align='left'><b>MUNICIPIO:</b></td>
  		<td align='left' width='350'>HERES</td>
  		</tr>
    	<tr>
    	<td align='left' width='160'><b>DIRECCI&Oacute;N O LUGAR:</b></td>
    	<td width='350' align='left' colspan='4'>Bulevar Bol&iacute;var, Casco Hist&oacute;rico, Edificio Roque Center N&ordm; 63</td>
		<td width='70'>FECHA:</td>
   	 	<td align='left' width='300'>&nbsp;<b>$fecha_ini</b>&nbsp;Al&nbsp;<b>$fecha_fin</b></td>
		</tr>
</table>
<br>
<br>

		
<table width='90%' border='1' align='center'>
    
   	 	<tr>
        <td colspan='3' width='135' align='center'><b>Clasificaci&oacute;n</b></td>
		<td rowspan='2' width='60' align='center'><b><br />Cantidad</b></td>
   	   	<td rowspan='2' width='80' align='center'><b> N&ordm; <br />Identificaci&oacute;n</b></td>
		<td rowspan='2' width='49%' align='center'><b><br /> Nombre y Descripci&oacute;n de los Elementos</b></td>
   	    <td rowspan='2' width='91' align='center'><br />Valor Unitario<br />  BsF: </td>
		<td rowspan='2' width='90' align='center'><br />Valor Total<br />BsF: </td>
		</tr>
		<tr>
      	<td width='40' align='center'><b>Grupo</b></td>
		<td width='45' align='center'><b>Sub-<br />Grupo</b></td>
		<td width='50' align='center'><b>Secci&oacute;n</b></td>
		</tr>";
		  

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
//$seccion=$registro->seccion;
if($seccion!="") {$seccion;} else {$seccion="&nbsp;";}
$codigo_bien=$registro->codigo_bien	;
$denominacion=remplace ($registro->denominacion);
$descripcion_adicional=remplace ($registro->descripcion_adicional);
$estatus=$registro->estatus	;
$codigo_seccion=$registro->codigo_seccion	;
$marca=$registro->marca;
$modelo=$registro->modelo	;
$precio=$registro->precio;
$suma=$suma+$precio;  
$ubicacion=$registro->ubicacion	;

	$inventario.="<tr>
 	  	<td width='40' align='center'>$grupo</td>
      	<td width='45' align='center'>$subgrupo</td>
      	<td width='50' align='center'>$seccion</td>
		<td width='60' align='center'>1</td>
        <td width='80' align='center'>$codigo_bien</td>
        <td width='49%' align='center'>$denominacion&nbsp;$descripcion_adicional&nbsp;$marca&nbsp;$modelo</td>
		<td width='91' align='right'>$precio</td>
		<td width='90' align='right'>$precio</td>
    	</tr>";
}

		$resultado=mysql_query($sql2);
                 $cantidad=mysql_num_rows($resultado);
                 $contador=0;
             while ($registro=mysql_fetch_object($resultado))
              { $contador++;
	  	
$clasificar=$registro->clasificacion;
$cla=explode("-",$clasificar);
$grupo=$cla[0];
$subgrupo=$cla[1];
$seccion=$cla[2];
//$seccion=$registro->seccion;
if($seccion!="") {$seccion;} else {$seccion="&nbsp;";}
$codigo_bien=$registro->codigo_bien	;
$denominacion=remplace ($registro->denominacion);
$descripcion_adicional=remplace ($registro->descripcion_adicional);
$estatus=$registro->estatus	;
$codigo_seccion=$registro->codigo_seccion	;
$marca=$registro->marca;
$modelo=$registro->modelo	;
$precio=$registro->precio;
$suma=$suma+$precio;  
$ubicacion=$registro->ubicacion	;

	$inventario.="<tr>
 	  	<td width='40' align='center'>$grupo</td>
      	<td width='45' align='center'>$subgrupo</td>
      	<td width='50' align='center'>$seccion</td>
		<td width='60' align='center'>1</td>
        <td width='80' align='center'>$codigo_bien</td>
        <td width='49%' align='center'>$denominacion&nbsp;$descripcion_adicional&nbsp;$marca&nbsp;$modelo</td>
		<td width='91' align='right'>$precio</td>
		<td width='90' align='right'>$precio</td>
    	</tr>";
}

		
		
		
	if($filas<10) 
		  { 	for($i=0;$i<10-$filas;$i++) 
		  		{
				
				$inventario.="<tr>
 	  	<td width='40' align='center'>&nbsp;</td>
      	<td width='45' align='center'>&nbsp;</td>
      	<td width='50' align='center'>&nbsp;</td>
		<td width='60' align='center'>&nbsp;</td>
        <td width='80' align='center'>&nbsp;</td>
        <td width='49%' align='center'>&nbsp;</td>
		<td width='91' align='right'>&nbsp;</td>
		<td width='90' align='right'>&nbsp;</td>
    	</tr>";
		}
				
				}
				
		else if($filas<20) 
		  { 	for($i=0;$i<40-$filas;$i++) 
		  		{ 
				
				$inventario.="<tr>
 	  	<td width='40' align='center'>&nbsp;</td>
      	<td width='45' align='center'>&nbsp;</td>
      	<td width='50' align='center'>&nbsp;</td>
		<td width='60' align='center'>&nbsp;</td>
        <td width='80' align='center'>&nbsp;</td>
        <td width='49%' align='center'>&nbsp;</td>
		<td width='91' align='right'>&nbsp;</td>
		<td width='90' align='right'>&nbsp;</td>
    	</tr>";
		}
				
				}		
				
				
 	$inventario.="</table>

  		<table width='100%' border='1'>
        <tr>
            <td width='692' align='right'>&nbsp;</td>
			<td width='91' align='right'><b>$suma</b></td>
          <td width='90' align='right'><b>$suma</b></td>
        </tr>
       </table>
  <br>
  <br>
   <br>
	<table width='100%' border='0' align='center'>
        <tr>
            <td width='681' align='right'>Firma: Del Jefe de la Unidad de Trabajo</td>
			<td width='180' align='right'>________________________</td>
        </tr>
        </table>";
		
		
// echo $inventario;
$vocales = array ( "'");
$inventario = str_replace ($vocales, '"', $inventario);

$pdf->writeHTML($inventario, true, false, false, false, '');
$pdf->Output('inventario_BM1.pdf', 'I');
 }
}
}
}
	  ?>