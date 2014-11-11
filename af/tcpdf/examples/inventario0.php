<?php
include('remplace1.php');
include('conexion.php');

$tbl = <<<EOD

<table  border="1" width="99%">
		<tr>
		<td width="98" align="left"><br /><img src="../images/logo_nuevo1.jpg" /></td>
		<td width="67%"><div align="center"><b><br /><br />RELACI&Oacute;N DEL MOVIMIENTO DE BIENES MUEBLES</b></div></td>
		<td width="120" align="center"><b><br /><br />FORMULARIO MB-2</b></td>
		</tr>
</table>




EOD;


$conex=conectarse();
$ubicacion=$_POST["ubicacion"];
$codigo_seccion=$_POST["codigo_seccion"];

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

$descripcion_ubic= remplace ($descripcion_ubic); 
$descripcion=remplace($descripcion);
$inventario1.="

<table width='99%' border='0' align='center' cellpadding='2' cellspacing='2'>

  		<tr style='background-color:#FFFFFF;'>
  		<td width='160' align='left'><b>ENTIDAD PROPIETARIA</b></td>
  		<td width='180' colspan='2' align='left'>Contralor&iacute;a del Estado Bolivar</td>
  		<td width='75' align='left'><b>SERVICIO:</b></td>
    	<td width='450' colspan='4' align='left'>$descripcion_ubic</td>
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
    	<td width='350' align='left' colspan='4'>Bulevar Bol&iacute;var, Casco Hist&oacute;rico, Edificio Roque Center N&ordm; 63						</td>
   	 	<td width='83'><b>FECHA:</b></td>
    	<td width='200'>$fecha_incorporacion</td>
  		</tr>
</table>

	 
    <table width='100%' border='1' align='center'>	
		<tr>
   	    <td colspan='3' align='center' width='135'><b>Clasificaci&oacute;n</b></td>
		<td rowspan='2' align='center' width='72'><b>Concepto Movimiento</b></td>
   	   	<td rowspan='2' align='center' width='58'><b><br />Cantidad</b></td>
   	   	<td rowspan='2' align='center' width='84'><b> N&ordm; Identificaci&oacute;n</b></td>
   	    <td rowspan='2' align='center' width='39%'><b><br />Nombre y Descripci&oacute;n de los Elementos</b></td>
		<td rowspan='2' align='center' width='95'><b><br />Incorporacion  BsF: </b></td>
   	    <td rowspan='2' align='center' width='110'><b><br />DesincorporacionBsF: </b></td>
      	</tr>
   	  	<tr>
      	<td width='40' align='center'><b>Grupo</b>		</td>
      	<td width='45' align='center'><b>Sub-<br />Grupo</b>		</td>
      	<td width='50' align='center'><b>Secci&oacute;n</b>		</td>
   	  </tr>
";
	  

if($_POST){
$fecha_ini=$_POST["fecha_ini"];
/*$fch=explode("/",$fecha_ini);
$fecha_ini=$fch[2]."-".$fch[1]."-".$fch[0];
*/
$fecha_fin=$_POST["fecha_fin"];
/*
$fch=explode("/",$fecha_fin);
$fecha_fin=$fch[2]."-".$fch[1]."-".$fch[0];
*/
if($fecha_fin=="--")
{ $fecha = " ";} 
else
{ $fecha = "fecha_incorporacion >='$fecha_ini' AND fecha_incorporacion<='$fecha_fin'AND ";}
$estatus=$_POST["estatus"];
if($estatus=="FALTANTE")
{ $estatus = " ";} 
$codigo_seccion=$_POST["codigo_seccion"];
$ubicacion=$_POST["ubicacion"];
if($codigo_seccion==$ubicacion)
{ $seccion = " ";} 
else
 { $seccion = "  codigo_seccion='$codigo_seccion' AND";}
if($codigo_seccion!="" && $ubicacion!=""){
}

	  $sql = "SELECT clasificacion,codigo_i,codigo_d,codigo_bien,denominacion,descripcion_adicional,marca,serial,precio,estatus FROM bienes WHERE $fecha $seccion  ubicacion='$ubicacion' AND estatus!='FALTANTE' ORDER BY clasificacion ASC;";
	 $resul=mysql_query($sql); 
	 $filas=mysql_num_rows($resul);
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
$marca=$registro->marca;
$serial=$registro->serial;
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
if($contador%12!=0)
{
        $inventario.="<tr>
      	<td width='40' align='center'>$grupo</td>
      	<td width='45' align='center'>$subgrupo</td>
      	<td width='50' align='center'>$seccion</td>
		<td width='72' align='center'>$codigo_i$codigo_d</td>
      	<td width='58' align='center'>1</td>
      	<td width='84' align='center'>$codigo_bien</td>
      	<td width='39%' align='center'>$denominacion&nbsp;$marca&nbsp;$descripcion_adicional&nbsp;$serial</td>
      	<td width='95' align='right'>$precio_i</td>
      	<td width='110' align='right'>$precio_d</td>
		</tr>";
		if($contador==$filas&& ($filas<12||$filas<24))
		{
		   $inventario.="<tr>
      	<td width='40' align='center'>&nbsp;</td>
      	<td width='45' align='center'>&nbsp;</td>
      	<td width='50' align='center'>&nbsp;</td>
		<td width='72' align='center'>&nbsp;</td>
      	<td width='58' align='center'>&nbsp;</td>
      	<td width='84' align='center'>&nbsp;</td>
      	<td width='39%' align='center'>&nbsp;</td>
      	<td width='95' align='right'>&nbsp;</td>
      	<td width='110' align='right'>&nbsp;</td>
		</tr>";
		
		
		
		}
		
		}
		
		else		
		{
		$contador=0;
		$tbl.=$inventario1;
		$tbl.=$inventario;
		 $tbl.="	  <tr>
            <td width='717' align='right' colspan='7'><b>TOTALES:</b></td>
			  <td width='95' align='right'><b>$suma_i</b></td>
          <td width='110' align='right'><b>$suma_d</b></td>
        </tr>";
		$tbl.="	</table>

  		<table width='100%' border='1'>
     
		<tr>
      <td width='173' align='center'>Existencia Anterior Bsf </td>
      <td width='175' align='center'>Mas incorporaciones Bsf </td>
      <td colspan='2' align='center' width='369'>Menos desincorporaciones Bsf</td>
      <td colspan='2' align='center' width='205'>Existencia Final BsF</td>
    </tr>
    <tr>
      <td colspan='2' rowspan='2' width='348'>&nbsp;</td>
      <td align='center' width='180'>Todo los conceptos <br />menos el 60</td>
      <td align='center' width='189'>conceptos faltantes <br />por investigar</td>
      <td  colspan='2' rowspan='2' align='right' width='205'>&nbsp;</td>
    </tr>
    <tr>
      <td width='180' align='right'>&nbsp;</td>
      <td width='189' align='right'>&nbsp;</td>
    </tr>
    <tr>
      <td colspan='2' align='left' width='348'>Observaciones:</td>
      <td align='center' width='180'>Preparado Por:</td>
      <td align='center' width='189'>Jefe de la Unidad de Trabajo:</td>
      <td colspan='2' align='center' width='205'>Sello</td>
    </tr>
    <tr>
      <td colspan='2' height='60' width='348'>&nbsp;</td>
      <td  align='right' width='180'>&nbsp;</td>
      <td align='right' width='189'>&nbsp;</td>
      <td colspan='2' align='right' width='205'>&nbsp;</td>
    </tr>
  </table>";
		
		}
		
	} 
    	    	  
		    
		
    	  
/*$inventario2.="	</table>

  		<table width='100%' border='1'>
        <tr>
            <td width='717' align='right'><b>TOTALES:</b></td>
			  <td width='95' align='right'><b>$suma_i</b></td>
          <td width='110' align='right'><b>$suma_d</b></td>
        </tr>
		<tr>
      <td width='173' align='center'>Existencia Anterior Bsf </td>
      <td width='175' align='center'>Mas incorporaciones Bsf </td>
      <td colspan='2' align='center' width='369'>Menos desincorporaciones Bsf</td>
      <td colspan='2' align='center' width='205'>Existencia Final BsF</td>
    </tr>
    <tr>
      <td colspan='2' rowspan='2' width='348'>&nbsp;</td>
      <td align='center' width='180'>Todo los conceptos <br />menos el 60</td>
      <td align='center' width='189'>conceptos faltantes <br />por investigar</td>
      <td  colspan='2' rowspan='2' align='right' width='205'>&nbsp;</td>
    </tr>
    <tr>
      <td width='180' align='right'>&nbsp;</td>
      <td width='189' align='right'>&nbsp;</td>
    </tr>
    <tr>
      <td colspan='2' align='left' width='348'>Observaciones:</td>
      <td align='center' width='180'>Preparado Por:</td>
      <td align='center' width='189'>Jefe de la Unidad de Trabajo:</td>
      <td colspan='2' align='center' width='205'>Sello</td>
    </tr>
    <tr>
      <td colspan='2' height='60' width='348'>&nbsp;</td>
      <td  align='right' width='180'>&nbsp;</td>
      <td align='right' width='189'>&nbsp;</td>
      <td colspan='2' align='right' width='205'>&nbsp;</td>
    </tr>
  </table>";
  */
// echo $inventario;
echo $tbl;
$vocales = array ( "'");
$inventario = str_replace ($vocales, '"', $inventario);
//  include('inventario_pdf_2.php');
}
	  ?>