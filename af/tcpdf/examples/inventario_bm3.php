<?php
include('remplace1.php');
include('conexion.php');

require_once('../config/lang/eng.php');
require_once('../tcpdf.php');
$tabla1=$_POST['tabla'];

 
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
$ubicacion_actual=$_GET["ubicacion_actual"];
$ubicacion=$_GET["ubicacion"];
$codigo_seccion=$_GET["codigo_seccion"];
$cargo=$_GET["cargo"];
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


$fecha_i=$_GET["fecha_ini"];
$fch=explode("/",$fecha_i);
$fecha_ini=$fch[2]."-".$fch[1]."-".$fch[0];

$fecha_f=$_GET["fecha_fin"];
$fch=explode("/",$fecha_f);
$fecha_fin=$fch[2]."-".$fch[1]."-".$fch[0];
if($fecha_fin=="--")
{ $fecha_f =date("d/m/Y");}
else
{ $fecha = " bienes_faltantes.fecha_operacion >='$fecha_ini' AND bienes_faltantes.fecha_operacion<='$fecha_fin'AND  ";}
$clasificacion=$_GET["clasificacion"];
$codigo_seccion=$_GET["codigo_seccion"];
$ubicacion=$_GET["ubicacion"];
if($codigo_seccion==$ubicacion)
{ $seccion = "";} 
else
 { $seccion = "  codigo_seccion='$codigo_seccion' AND";}
if($clasificacion!="" && $codigo_seccion!="" && $ubicacion!=""){


    
	  $sql = "SELECT bienes.clasificacion,bienes.codigo_bien,bienes.denominacion,bienes.descripcion_adicional,bienes.precio, bienes_faltantes.existencia_fisica,bienes_faltantes.registro_contable,bienes_faltantes.cantidad FROM bienes,bienes_faltantes WHERE clasificacion='$clasificacion' AND $fecha $seccion ubicacion='$ubicacion' AND bienes.codigo_bien=bienes_faltantes.codigo_bien ORDER BY clasificacion ASC;";
	  
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
		<td width='67%'><div align='center'><b><br /><br />BIENES MUEBLES FALTANTES</b></div></td>
		<td width='120' align='center'><b><br /><br />FORMULARIO BM-3</b></td>
		</tr>
</table>";

if($descripcion!="Todas") {$descripcion;} else {$descripcion="";}
$inventario.="

<table width='99%' border='0'>
  <tr>
  <td width='75%'>
<table width='80%' border='0' align='center' cellpadding='2' cellspacing='2'>
		
  		<tr style='background-color:#FFFFFF;'>
  		<td width='160' align='left'><b>ENTIDAD PROPIETARIA</b></td>
  		<td width='140' colspan='2' align='left'>Contralor&iacute;a del Estado Bolivar</td>
  		<td width='60' align='left'><b>SERVICIO:</b></td>
    	<td width='330' colspan='4' align='left'>$descripcion_ubic</td>
		</tr>
  		<tr>
  		<td width='160' align='left'><b>UNIDAD DE TRABAJO O AREA:</b></td>
    	<td width='450' colspan='6' align='left'>$descripcion</td>
		</tr>
  		<tr>
  		<td width='160' align='left'><b>ESTADO:</b></td>
  		<td width='60' align='left'>Bol&iacute;var</td>
  		<td width='90' align='left'><b>DISTRITO:</b></td>
  		<td width='60' align='left'>HERES</td>
   		<td width='92' colspan='2' align='left'><b>MUNICIPIO:</b></td>
  		<td align='left' width='100'>HERES</td>
  		</tr>
    	<tr>
    	<td align='left' width='160'><b>DIRECCI&Oacute;N O LUGAR:</b></td>
    	<td width='300' align='left' colspan='4'>Bulevar Bol&iacute;var, Casco Hist&oacute;rico, Edificio Roque Center N&ordm; 63
		</td>
   	 	<td width='75'>&nbsp;</td>
    	<td align='left' width='100'></td>
  		</tr>
</table>
		</td>
		<td width='25%'>
<table width='29%' border='1' align='left'>
      
      <tr colspan='3'>
      <td width='164' align='right' height='25'>Codigo concepto movimiento </td>
      <td width='70' align='center' height='25'>60</td>
      </tr>
      <tr>
      <td width='164' align='right' height='25'>N&ordm; del comprobante </td>
      <td width='70' height='10'>&nbsp;</td>
      </tr>
      <tr>
      <td width='164' align='right' height='25'>Fecha de la operaci&oacute;n
      </td>
      <td width='70' align='center' height='25'><b>$fecha_f</b></td>
      </tr>
</table>
	</td>
	</tr>
</table>
<br />

<table width='100%' border='1' align='center'>	
		<tr>
   	    <td colspan='3' align='center' width='135'><b>Clasificaci&oacute;n</b></td>
		<td rowspan='2' align='center' width='72'><b>N&ordm; <br /> Identificaci&oacute;n</b></td>
   	   	<td rowspan='2' align='center' width='42%'><b><br />Nombre y Descripci&oacute;n de los Elementos</b></td>
		<td colspan='2' align='center' width='140'><b>Cantidad</b></td>
		<td rowspan='2' align='center' width='52'><b><br />Valor Unitario </b></td>
		<td colspan='3' align='center' width='140'><b>Diferencia</b></td>
		</tr>
   	  	<tr>
      	<td width='40' align='center'><b>Grupo</b></td>
      	<td width='45' align='center'><b>Sub-<br />Grupo</b></td>
      	<td width='50' align='center'><b>Secci&oacute;n</b></td>
		
		
   	    <td width='70' align='center'><b>Existencia fisica </b></td>
   	    <td width='70' align='center'><b>Registro contable </b></td>
   	    <td width='70' align='center'><b>Cantidad</b></td>
  	    <td width='70' align='center'><b>Valor total </b></td>
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

$precio=$registro->precio;
$suma=$suma+$precio; 
$cantidad=$registro->cantidad;
$suma1=$suma1+$cantidad; 
$existencia_fisica=$registro->existencia_fisica;
$suma2=$suma2+$existencia_fisica;
$registro_contable=$registro->registro_contable;
$suma3=$suma3+$registro_contable;	





		  
	  
	  $inventario.="<tr>
      	<td width='40' align='center'>$grupo</td>
      	<td width='45' align='center'>$subgrupo</td>
      	<td width='50' align='center'>$seccion</td>
      	<td width='72' align='center'>$codigo_bien</td>
       	<td width='42%' align='center'>$denominacion,$descripcion_adicional</td>
      	<td width='70' align='center'>$existencia_fisica</td>
      	<td width='70' align='center'>$registro_contable</td>
		<td width='52' align='center'>$precio</td>
		<td width='70' align='center'>$cantidad</td>
		<td width='70' align='center'>$precio</td>
    	</tr>";
	 } 
		
		if($filas<8) 
		  { 	for($i=0;$i<8-$filas;$i++) 
		  		{ 
				 $inventario.="<tr>
      	<td width='40' align='center'>&nbsp;</td>
      	<td width='45' align='center'>&nbsp;</td>
      	<td width='50' align='center'>&nbsp;</td>
      	<td width='72' align='center'>&nbsp;</td>
       	<td width='42%' align='center'>&nbsp;</td>
      	<td width='70' align='center'>&nbsp;</td>
      	<td width='70' align='center'>&nbsp;</td>
		<td width='52' align='center'>&nbsp;</td>
		<td width='70' align='center'>&nbsp;</td>
		<td width='70' align='center'>&nbsp;</td>
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
       	<td width='42%' align='center'>&nbsp;</td>
      	<td width='70' align='center'>&nbsp;</td>
      	<td width='70' align='center'>&nbsp;</td>
		<td width='52' align='center'>&nbsp;</td>
		<td width='70' align='center'>&nbsp;</td>
		<td width='70' align='center'>&nbsp;</td>
    	</tr>";
				
				}
				
		   } 		
		   
$inventario.="	<tr>		   
				
	<td width='40' align='center'>&nbsp;</td>
    <td width='45' align='center'>&nbsp;</td>
    <td width='50' align='left'>&nbsp;</td>
    <td width='72' align='left'>&nbsp;</td>
    <td width='42%' align='center'>&nbsp;TOTALES</td>
    <td width='70'>$suma2</td>
    <td width='70'>$suma3</td>
    <td width='52' align='right'>$suma</td>
    <td width='70' align='center'>$suma1</td>
    <td width='70' align='right'>$suma</td>
  </tr>
  <tr>
    <td width='604' align='left' colspan='5'>OBSERVACIONES</td>
    <td width='192' align='left' colspan='3'>Faltante determinado por</td>
    <td width='140' align='left' colspan='2'>Cargo</td>
  </tr>
  <tr>
    <td width='604'  align='left' colspan='5' rowspan='6'>&nbsp;</td>
    <td width='192' height='20' align='left' colspan='3'>$usuario</td>
    <td width='140' height='20' align='left' colspan='2'>$cargo</td>
  </tr>
  <tr>
    <td width='192' align='left' colspan='3'>Dependecia a la cual esta adscrito </td>
    <td width='140' align='left' rowspan='2' colspan='2'>Firma</td>
  </tr>
  <tr>
    <td width='192' height='20' align='left' colspan='3'>$ubicacion_actual</td>
  </tr>
  <tr>
    <td width='192' align='left' colspan='3'>Jefe de la Unidad de Trabajo</td>
    <td width='140' align='left' rowspan='3' colspan='2'>Sello</td>
  </tr>
  <tr>
    <td width='192' height='20'align='left' colspan='3'>Karina Rodr&iacute;guez</td>
  </tr>
  <tr>
    <td width='192' align='left' colspan='3'>Firma</td>
  </tr>";

	  
  $inventario.="</table>";
	
//echo $inventario;
$vocales = array ( "'");
$inventario = str_replace ($vocales, '"', $inventario);
$pdf->writeHTML($inventario, true, false, false, false, '');
$pdf->Output('inventario_BM1.pdf', 'I');
 }}
}
	  ?>