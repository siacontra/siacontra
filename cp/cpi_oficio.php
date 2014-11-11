<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
<style>
<!--
@page { size: 21.59cm 27.94cm }
-->
</style>
<script language="JavaScript"> 
function printPage() {  
if(document.all) {  
document.all.divButtons.style.visibility = 'hidden';  
window.print();  
document.all.divButtons.style.visibility = 'visible';  
} else {  
document.getElementById('divButtons').style.visibility = 'hidden';  
window.print();  
document.getElementById('divButtons').style.visibility = 'visible';  
}  
}  
</script>
</head>

<body>
<?
 $sa = "select * from cp_documentoextsalida where Cod_DocumentoCompleto = 'CEDA-02-03-0001-2011'";
 $qa = mysql_query($sa) or die ($sa.mysql_error());
 $fa = mysql_fetch_array($qa);
 
 //// CONSULTA PARA OBTNER DATOS A MOSTRAR
 $sb = "select 
               md.Dependencia,
			   mp.NomCompleto,
			   rp.DescripCargo 
		  from 
		       mastdependencias md
			   inner join mastpersonas mp on (md.CodPersona=mp.CodPersona) 
			   inner join mastempleado me on (mp.CodPersona = me.Codpersona)
			   inner join rh_puestos rp on (rp.CodCargo = me.CodCargo)
		 where 
		       md.CodInterno = '".$fa['CodInterno']."'";
 $qb = mysql_query($sb) or die ($sb.mysql_error());
 $fb = mysql_fetch_array($qb);
?>
<table id="principal"  align="center">
<tr><td width="707">
  <!-- CABECERA DEL DOCUMENTO -->
  <table width="637" align="center" id="cabecera" cellpadding="0" cellspacing="0">
  <tr>
   <td width="20"></td>
   <td width="102"><img src="imagenes/logoLlaves.jpg" style="height:80px; width:80px" /></td>
   <td width="10"></td>
   <td width="378"><p align="center" style=""><font size="2">REPUBLICA BOLIVARIANA DE VENEZUELA</font></p>
       <p align="center"><font size="2">CONTRALORIA DEL ESTADO MONAGAS</font></p>
       <p align="center"><font size="2">DESPACHO DEL CONTRALOR</font></p></td>
   <td width="103"></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  </table>
</td></tr>

<tr><td>
   <!-- xxxxxxxxxxxxxxxxxxx -->
   <table id="numero_doc"> 
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td width="480"></td>
   <? 
	 list($a, $m, $d)=SPLIT( '[/.-]', $fa['FechaDocumento']); $f_documento=$d.'-'.$m.'-'.$a;
	 switch ($m) {
		case 01: $mes = Enero; break;  case 07: $mes = Julio; break;
		case 02: $mes = Febrero;break; case 08: $mes = Agosto; break;
		case 03: $mes = Marzo;break;   case 09: $mes = Septiembre; break;
		case 04: $mes = Abril;break;   case 10: $mes = Octubre; break;
		case 05: $mes = Mayo;break;    case 11: $mes = Noviembre; break;
		case 06: $mes = Junio;break;   case 12: $mes = Diciembre; break;
	 }
	?>
    <td>Tucupita, <?=$d?> de <?=$mes?> de <?=$a?>
    </b></td>
  </tr>
  </table>
</td></tr>

<tr><td></td></tr>

<tr><td>
  <!-- CUERPO 1 DEL DOCUMENTO -->
  <table id="cuerpo1">
  <tr>
    <td width="80"></td>
    <td width="248"><b>Oficio N°: <?=$fa['Cod_DocumentoCompleto']?></b></td>
  </tr>
    <tr>
    <td width="80"></td>
    <td width="248"></td>
    <td width="283"></td>
  </tr>
  <tr>
    <td></td>
    <td>Ciudadano(a)</td>
  </tr>
  <tr>
    <td></td>
    <td>************************</td>
  </tr>
  <tr>
    <td></td>
    <td>************************</td>
  </tr>
  <tr>
    <td></td>
    <td>************************</td>
  </tr>
  </table>
</td></tr>

<tr><td>
  <!-- CONTENIDO DEL DOCUMENTO -->
  <table width="701">
  <tr>
    <td width="80"></td>
    <td width="504"><div style="width:600px"><?=$fa['Contenido']?></div></td>
    <td width="11"></td>
  </tr>
  </table>

<tr><td>
  <!-- *********************** -->
  <table align="center" id="atentamente" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center">Atentamente,</td>
  </tr>
  <tr>
    <td height="25"></td>
  </tr>
  <tr>
    <td align="center">________________________________</td>
  </tr>
  <tr>
    <td align="center">Licdo. FREDDY CUDJOE</td>
  </tr>
  <tr>
    <td align="center">CONTRALOR (I) DEL ESTADO MONAGAS</td>
  </tr>
  <tr>
   <td align="center"><font size="1">Designado mediante Resolución Nº. 01-00-000159 de  Fecha 18-09-2013,</font></td>
  </tr>
  <tr>
    <td align="center"><font size="1">Emanada del Despacho de la Contralora General de la República,</font></td>
  </tr>
  <tr>
    <td align="center"><font size="1">publicada en G.O.Nº 405.296 de fecha 19-09-2013</font></td>
  </tr>
  </table>
</td></tr>

<tr><td>
  <!-- *********************** -->
  <table id="pie_pagina">
  <tr>
     <td width="63"></td>
     <td><?=$fa['MediaFirma']?></td>
     <td width="450"></td>
     <td><img src="imagenes/logoContraloria.jpg" style="height:80px; width:80px" /></td>
  </tr>
  </table>
</td></tr>


</td></tr>
<div id="divButtons" name="divButtons">  
<input type="button" id="imprimir" name="imprimir" value = "Imprimir" onclick="printPage()"/> 
</div>
</table>


</body>
</html>
