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
 list($cod_documento, $codpersona)=SPLIT( '[|]', $_GET['registro']);
 //echo $codpersona;

 $sa = "select * from cp_documentointerno where Cod_DocumentoCompleto = '$cod_documento'";
 $qa = mysql_query($sa) or die ($sa.mysql_error());
 $fa = mysql_fetch_array($qa);
 
 //// CONSULTA PARA OBTNER DATOS A MOSTRAR
 $sb = "select 
               md.Dependencia,
			   mp.NomCompleto,
			   rp.DescripCargo,
			   md.CodPersona 
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
  <table width="637" align="center" id="cabecera">
  <tr>
   <td width="20"></td>
   <td width="102"><img src="imagenes/logoLlaves.jpg" style="height:80px; width:80px" /></td>
   <td width="10"></td>
   <td width="378"><p align="center" style=""><font size="2">REPUBLICA BOLIVARIANA DE VENEZUELA</font></p>
                   <p align="center"><font size="2">CONTRALORIA DEL ESTADO MONAGAS</font></p>
                   <p align="center"><font size="2"><?=$fb['Dependencia']?></font></p></td>
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
   <table width="691" id="numero_doc"> 
  <tr>
    <td width="2"></td>
    <td width="2"></td>
    <td width="2"></td>
    <td width="514"></td>
    <td width="134">N°: <b><?=$cod_documento?></b></td>
  </tr>
  </table>
</td></tr>

<tr><td>
   <table id="titulo">
   <tr>
    <td></td>
    <td></td>
    <td width="295"></td>
    <td align="center"><font size="3"><b>MEMORANDUM</b></font></td>
    <td></td>
  </tr>
  <tr><td height="10"></td></tr>
  </table>
</td></tr>

<tr><td>
  <!-- CUERPO 1 DEL DOCUMENTO -->
  <table id="cuerpo1">
  <?
   $sc="select * from cp_documentodistribucion where Cod_Documento = '$cod_documento' and Procedencia = 'INT' and CodPersona='$codpersona'";
   $qc= mysql_query($sc) or die ($sc.mysql_error());
   $fc= mysql_fetch_array($qc);
   
   /// -------------------------------------------------------------------
   $sd="select 
              md.Dependencia,
			  mp.NomCompleto
		  from
		      mastpersonas mp
			  inner join mastempleado me on (me.CodPersona = mp.CodPersona)
			  inner join mastdependencias md on (md.CodDependencia = me.CodDependencia)
		 where 
		      mp.Codpersona = '$codpersona'";
   $qd= mysql_query($sd) or die ($sd.mysql_error());
   $fd= mysql_fetch_array($qd);
   
  ?>
  <tr>
    <td width="80"></td>
    <td width="48"><b>Para:</b></td>
    <td width="361"><?=$fd['Dependencia']?></td>
  </tr>
    <tr>
    <td width="63"></td>
    <td width="48"></td>
    <td width="361"><?=$fd['NomCompleto']?></td>
  </tr>
  <tr>
    <td></td>
    <td><b>De:</b></td>
    <td><?=$fb['Dependencia']?></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td><?=$fb['NomCompleto']?></td>
  </tr>
  <tr>
    <td></td>
    <td><b>Fecha:</b></td>
    <? 
	 list($a, $m, $d)=SPLIT( '[/.-]', $fa['FechaDocumento']); $f_documento=$d.'-'.$m.'-'.$a
	?>
    <td><?=$f_documento?></td>
  </tr>
  <tr>
    <td></td>
    <td><b>Asunto:</b></td>
    <td><?=$fa['Asunto']?></td>
  </tr>
  </table>
</td></tr>

<tr><td>
  <!-- CONTENIDO DEL DOCUMENTO -->
  <table width="701">
  <tr>
    <td width="80"></td>
    <td width="504"><div style="width:600px;height:300px"><?=$fa['Contenido']?></div></td>
    <td width="11"></td>
  </tr>
  </table>

<tr><td>
  <!-- *********************** -->
  <table align="center" id="atentamente">
  <tr>
    <td align="center">Atentamente,</td>
  </tr>
  <tr>
    <td height="25"></td>
  </tr>
  <?
   //// **********************
   $se = "select max(Secuencia) from rh_empleadonivelacion where CodPersona = '".$fb['CodPersona']."'"; 
   $qe = mysql_query($se) or die ($se.mysql_error());
   $fe = mysql_fetch_array($qe);
   
   //// **********************
   $sf = "select 
                 rp.DescripCargo
			from 
			     rh_empleadonivelacion rh
				 inner join rh_puestos rp on (rh.CodCargo = rp.CodCargo)
			where 
			     rh.CodPersona = '".$fb['CodPersona']."' and 
				 rh.Secuencia = '".$fe['0']."'";
   $qf = mysql_query($sf) or die ($sf.mysql_error());
   $ff = mysql_fetch_array($qf);
  ?>
  <tr>
    <td align="center">________________________________</td>
  </tr>
  <tr>
    <td align="center"><?=$fb['NomCompleto']?></td>
  </tr>
  <tr>
    <td align="center"><?=$ff['DescripCargo']?></td>
  </tr>
  </table>
</td></tr>

<tr><td>
  <!-- *********************** -->
  <table id="pie_pagina">

  <tr>
     <td width="63"></td>
     <td width="17"><?=$fa['MediaFirma']?></td>
     <td width="450"></td>
     <td width="135" align="center"><img src="imagenes/logoContraloria.jpg" style="height:80px; width:80px" /></td>
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

