<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
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
   $sa = "select 
               cps.FechaDocumento,
			   cps.Contenido,
			   cpd.Cod_Organismos,
			   cpd.Cod_Dependencia,
			   cpd.Representante,
			   cps.Cod_DocumentoCompleto,
			   cpd.Cargo,
			   cps.MediaFirma
		  from 
		       cp_documentoextsalida cps
			   inner join cp_documentodistribucionext cpd on (cps.Cod_Documento = cpd.Cod_Documento)
          where 
		       cps.Cod_DocumentoCompleto = '".$_GET['registro']."'";
 $qa = mysql_query($sa) or die ($sa.mysql_error());
 $ra = mysql_num_rows($qa); 
 $fa = mysql_fetch_array($qa); 
 
 //// CONSULTA PARA OBTNER DATOS A MOSTRAR
 if($fa['Cod_Dependencia']==''){
  $sb = "select 
               Organismo, Cargo, Direccion
		  from 
		       pf_organismosexternos
		 where 
		       CodOrganismo = '".$fa['Cod_Organismos']."'";
 }else{
   $sb = "select 
               pfo.Organismo,
			   pfd.Cargo,
			   pfd.Dependencia,
			   pfd.Direccion
		  from 
		       pf_organismosexternos pfo,
			   pf_dependenciasexternas pfd
		 where 
		       pfo.CodOrganismo = '".$fa['Cod_Organismos']."' and 
			   pfd.CodDependencia = '".$fa['Cod_Dependencia']."'";
 }
 
  $qb = mysql_query($sb) or die ($sb.mysql_error());
  $fb = mysql_fetch_array($qb);
 
?>
<table id="principal"  align="center">
<tr><td width="707">
  
  <!-- CABECERA DEL DOCUMENTO -->
  <table width="637" align="center" id="cabecera" cellpadding="0" cellspacing="0">
  <tr>
   <td width="20"></td>
   <td width="102" align="center"><img src="imagenes/logoLlaves.jpg" style="height:80px; width:80px" /></td>
   <td width="10"></td>
   <td width="378">
   
   <table cellpadding="0" cellspacing="2">
   <tr>
      <td align="center" width="387"><font size="3"><i><?=ucwords(strtolower('REP&Uacute;BLICA BOLIVARIANA DE VENEZUELA'))?></i></font></td>
   </tr>
   <tr>
      <td align="center"><font size="3"><i><?=ucwords(strtolower('CONTRALOR&Iacute;A DEL ESTADO MONAGAS'))?></i></font></td>
   </tr>
   <tr>
      <td align="center"><font size="3"><i><?=ucwords(strtolower('DESPACHO DEL CONTRALOR'))?></i></font></td>
    </tr>
   </table>
   
   </td>
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
   <table width="675" id="numero_doc"> 
  <tr>
    <td width="3"></td>
    <td width="3"></td>
    <td width="4"></td>
    <td width="431"></td>
   <?
	 list($a, $m, $d)=SPLIT( '[-]', $fa['FechaDocumento']); $f_documento= $d.'-'.$m.'-'.$a;
	 switch ($m) {
		case 01: $mes = Enero; break;  case 07: $mes = Julio; break;
		case 02: $mes = Febrero;break; case 08: $mes = Agosto; break;
		case 03: $mes = Marzo;break;   case 09: $mes = Septiembre; break;
		case 04: $mes = Abril;break;   case 10: $mes = Octubre; break;
		case 05: $mes = Mayo;break;    case 11: $mes = Noviembre; break;
		case 06: $mes = Junio;break;   case 12: $mes = Diciembre; break;
	 }
	?>
    <td width="210" align="right">Tucupita, <?=$d?> de <?=$mes?> de <?=$a?></b></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td width="431"></td>
    <td align="right">201° y 152°</td>
  </tr>
  </table>
</td></tr>

<tr><td>
  <!-- CUERPO 1 DEL DOCUMENTO -->
  <table id="cuerpo1" cellpadding="0" cellspacing="0">
    <tr>
      <td width="80"></td>
      <td width="351"><b>Oficio Circular N°: <?=$fa['Cod_DocumentoCompleto']?></b></td>
    </tr>
    <tr>
      <td width="80"></td>
      <td width="351"></td>
      <td width="180"></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
    </tr>
     <tr>
      <td></td>
      <td height="15"></td>
    </tr>
    <tr>
      <td></td>
      <td>Ciudadano(a):</td>
    </tr>
    <tr>
      <td></td>
      <td><b><?=htmlentities($fa['Representante'])?></b></td>
    </tr>
    <tr>
      <td></td>
      <td><?=ucwords(strtolower(htmlentities($fa['Cargo'])))?></td>
    </tr>
    <tr>
      <td></td>
      <td><?=ucwords(strtolower(htmlentities(htmlentities($fb['Direccion']))))?></td>
    </tr>
  </table></td>
</tr>

<tr><td>
  <!-- CONTENIDO DEL DOCUMENTO -->
  <table width="701">
  <tr>
    <td width="80"></td>
    <td width="504"><div style="width:600px;height:360px"><font size="3" face="Times New Roman"><?=$fa['Contenido']?></font></div></td>
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
    <td align="center"></td>
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
     <td width="17"><font size="1"><?=$fa['MediaFirma']?></font></td>
     <td width="450"></td>
     <td width="134" align="center"></td>
  </tr>
  <tr>
    <td height="15"></td>
  </tr>
  </table>
  <!-- *********************** -->
</td></tr>

<tr><td>
  <!-- *********************** -->
  <table id="pie_pagina2">
  <tr>
     <td width="63"></td>
     <td width="17"></td>
     <td width="504">
       <table cellpadding="0" cellspacing="0">
       <tr>
         <td width="55"></td>
         <td width="436" align="center"><font size="1">Hacia la Tansparencia, Fortalecimiento y Consolidación del Sistema Nacional de Control Fiscal</font></td>
       </tr>
        <tr>
        <td></td>
          <td align="center"><font size="1">Calle Sucre con calle Monagas, Edificio Contraloría del estado Monagas / Tel&eacute;fono (0291) 641.04.41</font></td>
       </tr>
       <tr>
         <td></td>
          <td align="center"><font size="1">Correo Electr&oacute;nico: ontraloriamonagas@contraloriamonagas.gob.ve. Maturín, Estado Monagas.</font></td>
       </tr>
       </table>
     </td>
     <td width="99" align="center"><img src="imagenes/logoContraloria.jpg" style="height:80px; width:80px" /></td>
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
