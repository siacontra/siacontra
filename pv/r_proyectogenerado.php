<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location:index.php");
//	------------------------------------
include("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);
//	------------------------------------
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="r_fscript.js"></script>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Reporte | Proyecto Generado</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?php

if(!$_POST) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
$MAXLIMIT=30;

$filtro = "";
if ($forganismo != ""){ $filtro .= " AND (Organismo = '".$forganismo."')"; $corganismo = "checked"; } else $dorganismo = "disabled";
if ($elaboradoPor != "") { $filtro .= " AND (ElaboradoPor = '".$elaboradoPor."')"; $cElaboradoPor = "checked"; } else $dElaboradoPor = "disabled";
if ($fnproyecto != "") { $filtro .= " AND (pr.CodPresupuesto = '".$fnproyecto."')"; $cnproyecto = "checked"; } else $dnproyecto = "disabled";
//if ($fstatus != "") { $filtro .= " AND (aj.Estado = '".$fstatus."')"; $cstatus = "checked"; } else $dstatus = "disabled";
if ($fdesde != "" || $fhasta != "") {
	if ($fdesde != "") $filtro .= " AND (aj.FechaAjuste >= '".$fdesde."')";
	if ($fhasta != "") $filtro .= " AND (aj.FechaAjuste <= '".$fhasta."')"; 
	$cajuste = "checked"; 
} else $dajuste = "disabled";
//	-------------------------------------------------------------------------------
$MAXLIMIT=30;
//// ------------------------------------------------------------------------------
echo "
<form name='frmentrada' action='r_proyectopresupuesto.php?limit=0' method='POST'>";
echo" <input type='hidden' name='limit' id='limit' value='".$limit."'/>
      <input type='hidden' name='registros' id='registros' value='".$registros."'/>

<div class='divBorder' style='width:900px;'>
<table width='900' class='tblFiltro'>
<tr>
 <td align='right'>Organismo:</td>
 <td>
  <input type='checkbox' name='chkorganismo' id='chkorganismo' value='1' $corganismo onclick='this.checked=true' />
  <select name='forganismo' id='forganismo' class='selectBig' $dorganismo onchange='getFOptions_2(this.id, \"fnanteproyecto\", \"chknanteproyecto\");'>";
		getOrganismos($forganismo, 3, $_SESSION[ORGANISMO_ACTUAL]);
		echo "
   </select>
 </td>
 <td align='right'>Elaborado Por:</td>
 <td>
  <input type='checkbox' name='chkelaboradoPor' id='chkelaboradoPor' value='1' $cElaboradoPor onclick='enabledElaboradoPor(this.form);' />
  <select name='elaboradoPor' id='elaboradoPor' class='selectBig' $dElaboradoPor>
    <option value=''></option>";
    getElaboradoPor($forganismo,0);
	echo"
  </select>
  </td>
</tr>
<tr>
  <td align='right'>Nro. Proyecto:</td>
  <td>
	<input type='checkbox' name='chknpresupuesto' id='chknpresupuesto' value='1' $cnpresupuesto onclick='enabledPNpresupuesto(this.form);' />
	<input type='text' name='fnpresupuesto' id='fnpresupuesto' size='6' maxlength='4' $dnpresupuesto value='$fnpresupuesto' />
  </td>
  <td align='right'>Fecha Elaboraci&oacute;n:</td>
  <td align='left'>
    <input type='checkbox' name='chkfajuste' id='chkfajuste' value='1' $cajuste onclick='enabledPFajuste(this.form);' />
    <input type='text' name='fdesde' id='fdesde' size='15' maxlength='10' $dajuste value='$fdesde'  onkeyup='getTotalDiasPermisos();' /> - 
    <input type='text' name='fhasta' id='fhasta' size='15' maxlength='10' $dajuste value='$fhasta'  onkeyup='getTotalDiasPermisos();' />
  </td>
</tr>
<tr><td height='2'></td></tr>
</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar'></center>
<br/><br/>
<form/>"; 
//// ------------------------------------------------------------------------------
$fecha=date("Y-m");
$ano=date("Y");
$sql="SELECT 
            * 
	    FROM 
		    pv_antepresupuesto  
	  WHERE 
	        Organismo='".$_SESSION['ORGANISMO_ACTUAL']."' and Estado='GE' $filtro 
   ORDER BY 
            CodAnteproyecto";
$qry=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($qry);
$registros=$rows; //echo $rows;
?>

<table width="900px" class="tblBotones">
<tr>
<td><div id="rows"><? echo"Registros: ".$rows ?></div></td>
<td width="250">
<?php 
/*echo "
<table align='center'>
   <tr>
     <td>
		<input name='btPrimero' type='button' id='btPrimero' value='&lt;&lt;' onclick='setLotes(this.form, \"P\", $registros,".$limit.");' />
		<input name='btAtras' type='button' id='btAtras' value='&lt;' onclick='setLotes(this.form, \"A\", $registros, ".$limit.");' />
      </td>
      <td>Del</td><td><div id='desde'></div></td>
      <td>Al</td><td><div id='hasta'></div></td>
      <td>
		<input name='btSiguiente' type='button' id='btSiguiente' value='&gt;' onclick='setLotes(this.form, \"S\", $registros,".$limit.");' />
		<input name='btUltimo' type='button' id='btUltimo' value='&gt;&gt;' onclick='setLotes(this.form, \"U\", $registros,".$limit.");' />
      </td>
  </tr>
</table>";*/
?>		</td>
		<td align="right">
<!--<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'r_proyectogeneradover.php', 'BLANK', 'height=500, width=950, left=200, top=200, resizable=no');" />--> <input type="button" class="btLista" id="btImprimir" name="btImprimir" value="Ver" onclick="cargarOpcion(this.form, 'r_proyectogeneradopdf.php', 'BLANK', 'height=800, width=800, left=200, top=70, resizable=yes');"/>
     </td>
  </tr>
</table>
<input type="hidden" name="registro" id="registro" />
<table align="center">
<tr>
  <td align="center">
  <div style="overflow:scroll; width:900px; height:300px;">
<table width="1300" class="tblLista">
	<tr class="trListaHead">
      <th>Nro. Proyecto</th>
      <th>Ejercicio</th>
      <th>Fecha Elaborado</th>
      <th>Unidad Ejecutora</th>
      <th>Preparado Por</th>
      <th>Estado</th>
   </tr>
<?

if($registros!=0){ 
  for($i=0; $i<$registros; $i++){
     $field=mysql_fetch_array($qry); 
      
     list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaAnteproyecto']); $fechaElaborado=$d.'-'.$m.'-'.$a;
     if($field['Estado']=='GE'){$estado=Generado;}
    
	  echo "
	  <tr class='trListaBody' onclick='mClk(this,\"registro\");' id='".$field['CodAnteproyecto']."'>
	  <td align='center'>".$field['CodAnteproyecto']."</td>
	  <td align='center'>".$field['EjercicioPpto']."</td>
	  <td align='center'>$fechaElaborado</td>
	  <td align='justify'>".$field['UnidadEjecutora']."</td>
	  <td align='justify'>".$field['PreparadoPor']."</td>
	  <td align='center'>$estado</td>";
}}
$rows=(int)$rows;
echo "
<script type='text/javascript' language='javascript'>
	totalAnteproyectos($registros,\"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
	totalLotes($registros, $rows, ".$limit.");
</script>";	
//	-------------------------------------------------------------------------------
?>
</body>
</html>
