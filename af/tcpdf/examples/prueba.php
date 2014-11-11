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
$usuario=$_SESSION['s_usuario'];
$cargo=$_SESSION['cargo'];
$conex=conectarse();
$ubicacion=$_GET["ubicacion"];
$codigo_seccion=$_GET["codigo_seccion"];
$cargo=$_GET["cargo"];
$usuario=$_GET['encargado'];
$usuario1=$usuario;

	  $sql = "SELECT * FROM bienes WHERE encargado= '$usuario' AND cargo='$cargo' AND ubicacion='$ubicacion' AND codigo_seccion='$codigo_seccion';";
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
		<td width='65%'><div align='center'><b><br /><br />RELACI&Oacute;N DEL MOVIMIENTO DE BIENES MUEBLES</b></div></td>
		<td width='120' align='center'><b><br /><br />FORMULARIO BM-1</b></td>
		</tr>";

if($descripcion!="TODAS") {$descripcion;} else {$descripcion="";}
$inventario.="</table>

<table width='99%' border='0' align='center' cellpadding='2' cellspacing='2'>

  		<tr style='background-color:#FFFFFF;'>
  		<td width='200' align='left'><b>ENTIDAD PROPIETARIA</b></td>
  		<td width='180' colspan='2' align='left'>Contralor&iacute;a del Estado Bolivar</td>
  		<td width='80' align='left'><b>SERVICIO:</b></td>
    	<td width='450' colspan='4' align='left'>$descripcion_ubic</td>
		</tr>
  		<tr>
  		<td width='200' align='left'><b>UNIDAD DE TRABAJO O AREA:</b></td>
    	<td width='600' colspan='6' align='left'>$descripcion</td>
		</tr>
  		<tr>
  		<td width='200' align='left'><b>ESTADO:</b></td>
  		<td width='60' align='left'>Bol&iacute;var</td>
  		<td width='90' align='left'><b>DISTRITO:</b></td>
  		<td width='60' align='left'>HERES</td>
   		<td width='92' colspan='2' align='left'><b>MUNICIPIO:</b></td>
  		<td  width='350' align='left'>HERES</td>
  	</tr>
    	
  		<tr>
    	<td align='left' width='200'><b>DIRECCI&Oacute;N O LUGAR:</b></td>
    	<td width='350' align='left' colspan='4'>Bulevar Bol&iacute;var, Casco Hist&oacute;rico, Edificio Roque Center N&ordm; 63</td>
   	 	<td width='75'><b>FECHA:</b></td>
    	<td width='250' align='left'><b>$fecha_f</b></td>
  		</tr>
</table>
<br>
	 <table width='100%' border='1' align='center'>	
		<tr>
   	    <td colspan='3' align='center' width='135'><b>Clasificaci&oacute;n</b></td>
   	   	<td rowspan='2' align='center' width='58'><b><br />Cantidad</b></td>
   	   	<td rowspan='2' align='center' width='84'><b> N&ordm; <br />Identificaci&oacute;n</b></td>
   	    <td rowspan='2' align='center' width='50%'><b><br />Nombre y Descripci&oacute;n de los Elementos</b></td>
		<td rowspan='2' align='center' width='90'><b><br />Valor Unitario  BsF: </b></td>
   	    <td rowspan='2' align='center' width='80'><b><br />Valor Total<br />BsF:</b></td>
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
$precio=$registro->precio;
$suma=$suma+$precio;  
$ubicacion=$registro->ubicacion	;

    	$inventario.="<tr>
      	<td width='40' align='center'>$grupo</td>
      	<td width='45' align='center'>$subgrupo</td>
      	<td width='50' align='center'>$seccion</td>
      	<td width='58' align='center'>1</td>
      	<td width='84' align='center'>$codigo_bien</td>
      	<td width='50%' align='center'>$denominacion&nbsp;$descripcion_adicional</td>
      	<td width='90' align='right'>$precio</td>
      	<td width='80' align='right'>$precio</td>
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
//$marca=$registro->marca;
//$modelo=$registro->modelo	;
$precio=$registro->precio;
$suma=$suma+$precio;  
$ubicacion=$registro->ubicacion	;

    	$inventario.="<tr>
      	<td width='40' align='center'>$grupo</td>
      	<td width='45' align='center'>$subgrupo</td>
      	<td width='50' align='center'>$seccion</td>
      	<td width='58' align='center'>1</td>
      	<td width='84' align='center'>$codigo_bien</td>
      	<td width='50%' align='center'>$denominacion&nbsp;$descripcion_adicional</td>
      	<td width='90' align='right'>$precio</td>
      	<td width='80' align='right'>$precio</td>
		</tr>";
	} 
		
		    if($filas<9) 
		  { 	for($i=0;$i<9-$filas;$i++) 
		  		{ 
				
				$inventario.="<tr>
      	<td width='40' align='center'>&nbsp;</td>
      	<td width='45' align='center'>&nbsp;</td>
      	<td width='50' align='center'>&nbsp;</td>
		<td width='58' align='center'>&nbsp;</td>
      	<td width='84' align='center'>&nbsp;</td>
      	<td width='50%' align='center'>&nbsp;</td>
      	<td width='90' align='right'>&nbsp;</td>
      	<td width='80' align='right'>&nbsp;</td>
		</tr>";}
				
				}
				 else if($filas<20) 
		  { 	for($i=0;$i<40-$filas;$i++) 
		  		{ 
				
				 $inventario.="<tr>
      	<td width='40' align='center'>&nbsp;</td>
      	<td width='45' align='center'>&nbsp;</td>
      	<td width='50' align='center'>&nbsp;</td>
		<td width='58' align='center'>&nbsp;</td>
      	<td width='84' align='center'>&nbsp;</td>
      	<td width='50%' align='center'>&nbsp;</td>
      	<td width='90' align='right'>&nbsp;</td>
      	<td width='80' align='right'>&nbsp;</td>
		</tr>";
				
				}
				
		   } 		    
		
    	  
$inventario.="	</table>

  		<table width='100%' border='1'>
        <tr>
            <td width='748' align='right'>&nbsp;</td>
			<td width='90' align='center'><b>TOTAL</b></td>
          <td width='80' align='right'><b>$suma</b></td>
        </tr>";
       
		
  $inventario.=" </table>

	<table width='100%' border='1' align='center'>
      <tr>
        <td width='130' colspan='2' align='center'>&nbsp;</td>
      	<td width='200' colspan='2' align='center'>PREPARACI&Oacute;N</td>
		<td width='195' colspan='2' align='center'>CONFORMACI&Oacute;N</td>
		<td width='223' colspan='2' align='center'>APROBACI&Oacute;N</td>
      	<td width='170' colspan='2' rowspan='4' align='center'>
		<br />	
  <br /><br />	
  <br />SELLO</td>
      </tr>
	  <tr>
        <td width='130' colspan='2' align='left'>NOMBRE</td>
      	<td width='200' colspan='2' align='left'>$encargado</td>
		<td width='195' colspan='2' align='left'><br /><b>Karina Rodr&iacute;guez</b></td>
		<td width='223' colspan='2' align='center'>&nbsp;</td>
      
        </tr>
		<tr>
         <td width='130' colspan='2' align='left'>CARGO</td>
      	<td width='200' colspan='2' align='left'>$cargo</td>
		<td width='195' colspan='2' align='left'><b>JEFE D.S.G</b></td>
		<td width='223' colspan='2' align='left'>&nbsp;</td>
      
        </tr>
		<tr>
        <td width='130' colspan='2' align='left'>FIRMA</td>
      	<td width='200' colspan='2' align='left'>&nbsp;</td>
		<td width='195' colspan='2' align='left'>&nbsp;</td>
		<td width='223' colspan='2' align='left'>&nbsp;</td>
      
        </tr>
        </table>";


  
// echo $inventario;
// echo $inventario2;
$vocales = array ( "'");
$inventario = str_replace ($vocales, '"', $inventario);
$pdf->writeHTML($inventario, true, false, false, false, '');
$pdf->Output('inventario_BM1.pdf', 'I');
 }
}
}
}
	  ?>