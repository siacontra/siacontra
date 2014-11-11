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
$pdf->SetFont('helvetica', '', 9);
$pdf->Write($h=0, $txt, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

$pdf->AddPage();



$conex=conectarse();
$usuario=$_SESSION['s_usuario'];
$ubicacion=$_GET["ubicacion"];
$codigo_seccion=$_GET["codigo_seccion"];
$usuario=$_GET['encargado'];
$usuario1=$usuario;

	  $sql = "SELECT * FROM bienes WHERE encargado= '$usuario' AND ubicacion='$ubicacion' AND codigo_seccion='$codigo_seccion';";
$resultado=mysql_query($sql);

	                
          	$result=mysql_query($sql);

	while ($row = mysql_fetch_array($result)){


         $codigo_seccion=		$row['codigo_seccion'];
		 $ubicacion=			$row['ubicacion'];
		  $encargado=			$row['encargado'];
         $cargo=				$row['cargo']; 	
                 
          		
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

$descripcion_ubic= remplace ($descripcion_ubic); 
$descripcion=remplace($descripcion);
$encargado=remplace($usuario);



if($_GET){
$fecha_i=$_GET["fecha_ini"];
$fch=explode("/",$fecha_i);
$fecha_ini=$fch[2]."-".$fch[1]."-".$fch[0];

$fecha_f=$_GET["fecha_fin"];
$fch=explode("/",$fecha_f);
$fecha_fin=$fch[2]."-".$fch[1]."-".$fch[0];
if($fecha_fin=="--")
{ $fecha_f =date("d/m/Y");}
else
{ $fecha = "fecha_incorporacion >='$fecha_ini' AND fecha_incorporacion<='$fecha_fin'AND ";}
$estatus=$_GET["estatus"];
if($estatus=="FALTANTE")
{ $estatus = " ";} 
$codigo_seccion=$_GET["codigo_seccion"];
$ubicacion=$_GET["ubicacion"];
if($codigo_seccion==$ubicacion)
{ $seccion = " ";} 
else
 { $seccion = "  codigo_seccion='$codigo_seccion' AND";}
if($codigo_seccion!="" && $ubicacion!=""){
}

		if($fecha_fin=="--")
{ $fecha_f =date("d/m/Y");}
else
	{  
$fecha2 = "fecha_desincorporacion >='$fecha_ini' AND fecha_desincorporacion<='$fecha_fin' AND ";}
		 $sql2 = "SELECT clasificacion,codigo_i,codigo_d,codigo_bien,denominacion,descripcion_adicional,precio,estatus FROM bienes WHERE $fecha2 $seccion  ubicacion='$ubicacion' AND estatus!='FALTANTE'  AND estatus!='INCORPORADO' ORDER BY clasificacion ASC ";

	  $sql = "SELECT clasificacion,codigo_i,codigo_d,codigo_bien,denominacion,descripcion_adicional,precio,estatus FROM bienes WHERE $fecha $seccion  ubicacion='$ubicacion' AND estatus!='FALTANTE'  AND estatus!='DESINCORPORADO' ORDER BY clasificacion ASC ";
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
		<td width='67%'><div align='center'><b><br /><br />RELACI&Oacute;N DEL MOVIMIENTO DE BIENES MUEBLES</b></div></td>
		<td width='120' align='center'><b><br /><br />FORMULARIO BM-2</b></td>
		</tr>
</table>";

if($descripcion!="TODAS") {$descripcion;} else {$descripcion="";}
$inventario.="

<table width='99%' border='0' align='center' cellpadding='2' cellspacing='2'>
		
  		<tr style='background-color:#FFFFFF;'>
  		<td width='180' align='left'><b>ENTIDAD PROPIETARIA</b></td>
  		<td width='180' colspan='2' align='left'>Contralor&iacute;a del Estado Bolivar</td>
  		<td width='75' align='left'><b>SERVICIO:</b></td>
    	<td width='450' colspan='4' align='left'>$descripcion_ubic</td>
		</tr>
  		<tr>
  		<td width='180' align='left'><b>UNIDAD DE TRABAJO O AREA:</b></td>
    	<td width='600' colspan='6' align='left'>$descripcion</td>
		</tr>
  		<tr>
  		<td width='180' align='left'><b>ESTADO:</b></td>
  		<td width='60' align='left'>Bol&iacute;var</td>
  		<td width='90' align='left'><b>DISTRITO:</b></td>
  		<td width='60' align='left'>HERES</td>
   		<td width='92' colspan='2' align='left'><b>MUNICIPIO:</b></td>
  		<td align='left' width='350'>HERES</td>
  		</tr>
    	<tr>
    	<td align='left' width='180'><b>DIRECCI&Oacute;N O LUGAR:</b></td>
    	<td width='350' align='left' colspan='4'>Bulevar Bol&iacute;var, Casco Hist&oacute;rico, Edificio Roque Center N&ordm; 63						</td>
   	 	<td width='75'><b>FECHA:</b></td>
    	<td align='left' width='300'><b>$fecha_f</b></td>
  		</tr>
</table>
<br />
	 
    <table width='100%' border='1' align='center'>	
		<tr>
   	    <td colspan='3' align='center' width='135'><b>Clasificaci&oacute;n</b></td>
		<td rowspan='2' align='center' width='72'><b>Concepto Movimiento</b></td>
   	   	<td rowspan='2' align='center' width='52'><b><br />Cantidad</b></td>
   	   	<td rowspan='2' align='center' width='75'><b> N&ordm; <br />Identificaci&oacute;n</b></td>
   	    <td rowspan='2' align='center' width='42%'><b><br />Nombre y Descripci&oacute;n de los Elementos</b></td>
		<td rowspan='2' align='center' width='90'><b><br />Incorporacion<br />BsF: </b></td>
   	    <td rowspan='2' align='center' width='100'><b><br />Desincorporacion<br />BsF: </b></td>
      	</tr>
   	  	<tr>
      	<td width='40' align='center'><b>Grupo</b></td>
      	<td width='45' align='center'><b>Sub-<br />Grupo</b></td>
      	<td width='50' align='center'><b>Secci&oacute;n</b></td>
   	  </tr>
";
	  
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
//$marca=$registro->marca;
//$serial=$registro->serial;
//$seccion=$registro->seccion;
if($seccion=="") {$seccion="&nbsp;";}
$codigo_bien=$registro->codigo_bien	;
$estatus=$registro->estatus;
$codigo_i=$registro->codigo_i;
$codigo_d=$registro->codigo_d;
if($estatus=="INCORPORADO") 
{$codigo_i;    
$precio_i=$registro->precio;
$suma_i=$suma_i+$precio_i;
$precio_d="";
 }
if($estatus=="DESINCORPORADO")
 {$codigo_d;
  $precio_d=$registro->precio;
$suma_d=$suma_d+$precio_d; 
$precio_i="";
}
$denominacion=remplace ($registro->denominacion);
$descripcion_adicional=remplace ($registro->descripcion_adicional);
$inventario.="<tr>
      	<td width='40' align='center'>$grupo</td>
      	<td width='45' align='center'>$subgrupo</td>
      	<td width='50' align='center'>$seccion</td>
		<td width='72' align='center'>$codigo_i$codigo_d</td>
      	<td width='52' align='center'>1</td>
      	<td width='75' align='center'>$codigo_bien</td>
      	<td width='42%' align='left'>$denominacion&nbsp;$descripcion_adicional</td>
      	<td width='90' align='right'>$precio_i</td>
      	<td width='100' align='right'>$precio_d</td>
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
//$marca=$registro->marca;
//$serial=$registro->serial;
//$seccion=$registro->seccion;
if($seccion=="") {$seccion="&nbsp;";}
$codigo_bien=$registro->codigo_bien	;
$estatus=$registro->estatus;
$codigo_i=$registro->codigo_i;
$codigo_d=$registro->codigo_d;
if($estatus=="INCORPORADO") 
{$codigo_i;    
$precio_i=$registro->precio;
$suma_i=$suma_i+$precio_i;
$precio_d="";
 }
if($estatus=="DESINCORPORADO")
 {$codigo_d;
  $precio_d=$registro->precio;
$suma_d=$suma_d+$precio_d; 
$precio_i="";
}
$denominacion=remplace ($registro->denominacion);
$descripcion_adicional=remplace ($registro->descripcion_adicional);
$inventario.="<tr>
      	<td width='40' align='center'>$grupo</td>
      	<td width='45' align='center'>$subgrupo</td>
      	<td width='50' align='center'>$seccion</td>
		<td width='72' align='center'>$codigo_i$codigo_d</td>
      	<td width='52' align='center'>1</td>
      	<td width='75' align='center'>$codigo_bien</td>
      	<td width='42%' align='left'>$denominacion&nbsp;$descripcion_adicional</td>
      	<td width='90' align='right'>$precio_i</td>
      	<td width='100' align='right'>$precio_d</td>
		</tr>";
	} 
		
		
		
		 if($filas<6) 
		  { 	for($i=0;$i<6-$filas;$i++) 
		  		{ 
				 $inventario.="<tr>
      	<td width='40' align='center'>&nbsp;</td>
      	<td width='45' align='center'>&nbsp;</td>
      	<td width='50' align='center'>&nbsp;</td>
		<td width='72' align='center'>&nbsp;</td>
      	<td width='52' align='center'>&nbsp;</td>
      	<td width='75' align='center'>&nbsp;</td>
      	<td width='42%' align='left'>&nbsp;</td>
      	<td width='90' align='right'>&nbsp;</td>
      	<td width='100' align='right'>&nbsp;</td>
		</tr>";}
				
				}
				
				 else if($filas<22) 
		  { 	for($i=0;$i<36-$filas;$i++) 
		  		{ 
				
				 $inventario.="<tr>
      	<td width='40' align='center'>&nbsp;</td>
      	<td width='45' align='center'>&nbsp;</td>
      	<td width='50' align='center'>&nbsp;</td>
		<td width='72' align='center'>&nbsp;</td>
      	<td width='52' align='center'>&nbsp;</td>
      	<td width='75' align='center'>&nbsp;</td>
      	<td width='42%' align='left'>&nbsp;</td>
      	<td width='90' align='right'>&nbsp;</td>
      	<td width='100' align='right'>&nbsp;</td>
		</tr>";
				
				}
				
		   } 
		
    	  
$inventario.="	<tr>
            <td width='730' colspan='7' align='right'><b>TOTALES:</b></td>
			  <td width='90' align='right'><b>$suma_i</b></td>
          <td width='100' align='right'><b>$suma_d</b></td>
		  </tr>
		  <tr>
		  <td width='920' colspan='7' align='center'><b>RESUMEN</b></td>
		  </tr>
		  </table>

  		<table width='100%' border='1'>
       
		<tr>
      <td width='173' align='center'>Existencia Anterior Bsf </td>
      <td width='175' align='center'>Mas incorporaciones Bsf </td>
      <td colspan='2' align='center' width='380'>Menos desincorporaciones Bsf</td>
      <td colspan='2' align='center' width='192'>Existencia Final BsF</td>
    </tr>
    <tr>
      <td colspan='2' rowspan='2' width='348'>&nbsp;</td>
      <td align='left' width='185'>Todo los conceptos menos el 60</td>
      <td align='left' width='195'>conceptos faltantes por investigar</td>
      <td  colspan='2' rowspan='2' align='right' width='192'>&nbsp;</td>
    </tr>
    <tr>
      <td width='185' align='right'>&nbsp;</td>
      <td width='195' align='right'>&nbsp;</td>
    </tr>
    <tr>
      <td colspan='2' align='left' width='348'>Observaciones:</td>
      <td align='center' width='185'>Preparado Por:</td>
      <td align='center' width='195'>Jefe de la Unidad de Trabajo:</td>
      <td colspan='2' align='center' width='192'>Sello</td>
    </tr>
    <tr>
      <td colspan='2' height='60' width='348'>&nbsp;</td>
      <td  align='center' width='185'><br /><br /><b>$encargado</b></td>
      <td align='center' width='195'><br /><br /><b>Karina Rodr&iacute;guez <br />JEFE D.S.G</b></td>
      <td colspan='2' align='right' width='192'>&nbsp;</td>
    </tr>
  </table>";
//echo $inventario;
// echo $inventario;
$vocales = array ( "'");
$inventario = str_replace ($vocales, '"', $inventario);
$pdf->writeHTML($inventario, true, false, false, false, '');
$pdf->Output('inventario_BM1.pdf', 'I');
 }}
}

	  ?>