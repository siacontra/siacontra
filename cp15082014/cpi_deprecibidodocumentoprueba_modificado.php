<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
//	------------------------------------
extract($_POST);
extract($_GET);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
<style>
<!--
@page { size: 21.59cm 27.94cm }

}
-->
</style>
<script language="JavaScript"> 
function printPage() {  
if(document.all) {  
document.all.divButtons.style.visibility = 'hidden';  
document.all.divButtons2.style.visibility = 'hidden';  
window.print();  
document.all.divButtons.style.visibility = 'visible';  
document.all.divButtons2.style.visibility = 'visible';
} else {  
document.getElementById('divButtons').style.visibility = 'hidden';  
document.getElementById('divButtons2').style.visibility = 'hidden'; 
window.print();  
document.getElementById('divButtons').style.visibility = 'visible';  
document.getElementById('divButtons2').style.visibility = 'visible';  
}  
}  
/// VISTA PREVIA
function printPage2() {  
if(document.all) {  
document.all.divButtons2.style.visibility = 'hidden';
document.all.divButtons.style.visibility = 'hidden';   
window.print();  
document.all.divButtons2.style.visibility = 'visible'; 
document.all.divButtons.style.visibility = 'visible'; 
} else {  
document.getElementById('divButtons2').style.visibility = 'hidden';  
document.getElementById('divButtons').style.visibility = 'hidden'; 
window.print();  
document.getElementById('divButtons').style.visibility = 'visible';  
document.getElementById('divButtons2').style.visibility = 'visible';
}  
}  
</script>
</head>

<body>
<?
 list($cod_tipodocumento, $cod_documentocompleto, $codPersona)=SPLIT( '[|]', $_GET['registro']);
 //echo $cod_tipodocumento;
 //echo $cod_documentocompleto;
 $sa = "select * from cp_documentointerno where Cod_DocumentoCompleto = '$cod_documentocompleto' and Cod_TipoDocumento = '$cod_tipodocumento'";
 $qa = mysql_query($sa) or die ($sa.mysql_error()); //echo $sa;
 $fa = mysql_fetch_array($qa); 
 
 //// consulta para verificar datos
 $scon="select *  from 
                      cp_documentodistribucion 
			     where 
				       Cod_Documento = '$cod_documentocompleto' and 
					   Cod_TipoDocumento = '$cod_tipodocumento'";
 $qcon = mysql_query($scon) or die ($scon.mysql_error()); //echo $scon;
 $rcon = mysql_num_rows($qcon); //echo $rcon;
 
 //// consulta para verficar tipo de correspondencia
 $stcuenta = "select * from cp_tipocorrespondencia where Cod_TipoDocumento = '$cod_tipodocumento'";
 $qtcuenta = mysql_query($stcuenta) or die ($stcuenta.mysql_error());
 $rtcuenta = mysql_num_rows($qtcuenta); //echo $rtcuenta;
 $ftcuenta = mysql_fetch_array($qtcuenta);
 
 
 if($rcon!=0){
 
   for($i=0; $i<$rcon; $i++){
     $fcon = mysql_fetch_array($qcon);
	 
	 $sb = "select 
				   md.Dependencia,
				   mp.NomCompleto,
				   rp.DescripCargo,
				   md.CodPersona 
			  from 
				   mastdependencias md
				   inner join mastpersonas mp on (md.CodPersona = mp.CodPersona) 
				   inner join mastempleado me on (mp.CodPersona = me.Codpersona)
				   inner join rh_puestos rp on (rp.CodCargo = me.CodCargo)
			 where 
				   md.CodInterno = '".$fa['CodInterno']."'";
	 $qb = mysql_query($sb) or die ($sb.mysql_error());
	 $fb = mysql_fetch_array($qb);
	 
	 //// CONSULTA PARA OBTENER LOS DATOS A QUIEN VA DIRIGIDO EL DOCUMENTO
	 $sc = "select
	               mp.NomCompleto,
				   rp.DescripCargo,
				   md.Dependencia
			  from
			      mastpersonas mp,
				  rh_puestos rp,
				  mastdependencias md
			 where
			      mp.CodPersona = '".$fcon['CodPersona']."' and
				  rp.CodCargo = '".$fcon['CodCargo']."' and 
				  md.CodDependencia = '".$fcon['CodDependencia']."'";
	 $qc = mysql_query($sc) or die ($sc.mysql_error());
	 $fc = mysql_fetch_array( $qc);
	 
?>
<table id="Padre" name="Padre" align="center" cellpadding="0" cellspacing="0" HEIGHT="900">
<tr>
<td>
<? 
  //echo $ftcuenta['DescripCorta'];
  if($ftcuenta['DescripCorta']=='ME'){
?>
<table id="principal"  align="center">
<tr><td width="707">
  <!-- *********************** -->
  <table align="center">
  <tr><td>
  <!-- CABECERA DEL DOCUMENTO -->
  <table width="679" align="right" id="cabecera" cellpadding="0" cellspacing="0">
  <tr>
   <td width="3"></td>
   <td width="124" align="center"></td>
   <td width="10"></td>
   <td width="420">
   <!-- *********************** -->
   <table cellpadding="0" cellspacing="0">
   <tr>
      <td align="center" width="414"><font size="3" face="Arial"></font></td>
   </tr>
   <tr>
      <td align="center"><font size="3" face="Arial"></font></td>
   </tr>
   <tr>
      <td align="center"><font size="3" face="Arial"></font></td>
    </tr>
   </table>
   <!-- *********************** -->
   </td>
   <td width="120" align="center"><img src="imagenes/logoContraloria.jpg" style="height:80px; width:80px"/></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  </table>
  <!-- FIN CABECERA DEL DOCUMENTO -->
  </td></tr>
  </table>
  <!-- *********************** -->
  
</td></tr>
<tr><td>
   <!-- *********************** -->
   <table width="688" height="27" id="numero_doc"> 
   <tr>
    <td width="26" height="21"></td>
    <td width="26"></td>
    <td width="26"></td>
    <td width="436"></td>
    <td width="150"><font face="Arial"><b>N°:<?=$cod_documentocompleto?></b></font></td>
   </tr>
   </table>
   <!-- *********************** -->
</td></tr>

<tr><td>
   <!-- *********************** -->
   <table id="titulo">
   <tr>
    <td width="0"></td>
    <td width="0"></td>
    <td width="268"></td>
    <td width="148" align="center"><font size="3" face="Arial"><b>MEMORANDUM</b></font></td>
    <td width="0"></td>
  </tr>
  <tr><td height="5"></td></tr>
  </table>
  <!-- *********************** -->
</td></tr>

<tr><td>
  <!-- CUERPO 1 DEL DOCUMENTO -->
  <table id="cuerpo1" cellpadding="0" cellspacing="0">
  <tr>
    <td width="34"></td>
        <td width="79"><font face="Arial"  style=" font-size:14px; line-height: 1.2em"><b>PARA</b></font></td>
    <td width="563"><font face="Arial"  style=" font-size:14px; line-height: 1.2em"><b>: <?=htmlentities($fc['DescripCargo']);?></b></font></td>
  </tr>
  <tr>
    <td width="34"></td>
    <td width="79"></td>
    <td width="563"><font face="Arial" style=" line-height: 1em">: <?=htmlentities($fc['NomCompleto']);?></font></td>
  </tr>
   <tr><td height="5"></td></tr>
  <tr>
    <td></td>
    <td><font face="Arial"  style=" font-size:14px; line-height: 1.2em"><b>DE</b></font></td>
    <td><font face="Arial"  style=" font-size:14px; line-height: 1.2em"><b>: <?=htmlentities($fb['Dependencia']);?></b></font></td>
    </tr>
   <tr>
    <td width="34"></td>
    <td width="79"></td>
    <td><font face="Arial"  style=" font-size:14px; line-height: 1.2em">: <?=htmlentities($fb['NomCompleto']);?></font></td>
  <tr><td height="5"></td></tr>
  <tr>
    <td></td>
    <td><font face="Arial"  style=" font-size:14px; line-height: 1.2em"><b>FECHA</b></font></td>
    <? 
	 list($a, $m, $d)=SPLIT( '[/.-]', $fa['FechaDocumento']); $f_documento=$d.'-'.$m.'-'.$a
	?>
    <td><font face="Arial"  style=" font-size:14px; line-height: 1.2em"><b>: <?=$f_documento?></b></font></td>
  </tr>
  <tr>
    <td height="5"></td>
  </tr>
  <tr>
    <td></td>
    <td><font face="Arial"  style=" font-size:14px; line-height: 1.2em"><b>ASUNTO</b></font></td>
    <td><font face="Arial"  style=" font-size:14px; line-height: 1.2em"><b>: <?=$fa['Asunto']?></b></font></td>
  </tr>
  <td></td>
    <td colspan="2">------------------------------------------------------------------------------------------------------------------------------</td>
    
  </tr>
  </table>
  <!-- *********************** -->
</td></tr>

<tr><td>
  <!-- CONTENIDO DEL DOCUMENTO -->
  <table width="701">
  <tr>
    <td width="28"></td>
    <td width="646"><div style="width:650px;"><font face="Arial" size="3"><?=$fa['Contenido']?></font></div></td>
    <td width="11"></td>
  </tr>
  </table>
  <!-- *********************** -->
<tr><td>
  <!-- *********************** -->
  <table align="center" id="atentamente" width="500">
  <tr>
    <td width="58"></td>
    <td align="center"><font face="Arial" size="3">Atentamente,</font></td>
    <td width="61"></td>
  </tr>
  <tr>
    <td width="58"></td>
    <td height="25"></td>
    <td width="61"></td>
  </tr>
  <tr>
    <td width="58"></td>
    <td align="center"></td>
    <td width="61"></td>
  </tr>
  <tr>
  <?
   $sd = "select * from rh_puestos where CodCargo = '".$fa['Cod_CargoRemitente']."'";
   $qd = mysql_query($sd) or die ($sd.mysql_error()); //echo $sa;
   $fd = mysql_fetch_array($qd); 
  ?>
    <td width="58"></td>
    <td align="center" width="365"><font face="Arial" size="3"><?=$fb['NomCompleto']?></font></td>
    <td width="61"></td>
  </tr>
  <tr>
    <td width="58"></td>
    <td align="center" width="600"><font face="Arial" size="3"><?=htmlentities($fd['DescripCargo'])?></font></td>
    <td width="61"></td>
  </tr>
  </table>
  <!-- *********************** -->
</td></tr>

<tr><td>
  <!-- *********************** -->
  <table width="686" id="pie_pagina">
  <tr>
     <td width="26"></td>
     <td width="70"><font face="Arial" size="2"><?=$fa['MediaFirma']?></font></td>
     <td width="421"></td>
     <td width="149" align="right"></td>
  </tr>
  </table>
  <!-- *********************** -->
</td></tr>

<tr valign="bottom"><td height="20">
  <!-- *********************** -->
 
			   <table>

			   <tr><td>
						<table  width="670" id="pie_pagina" valign="baseline" >
						
						<tr>
						<td align="center" style=" font-size:9px; line-height: 0.5em;" colspan="2" >Hacia la transparencia, fortalecimiento y consolidación del Sistema Nacional de Control Fiscal</td>
						</tr><tr><td width="90"></td>
						<td align="center" style=" font-size:8px; line-height: 0.8em;">_____________________________________________________________________________________________________________________________________________________________     <font face="Arial" size="2">              <?//=$fa['MediaFirma']?></font></td>
						</tr><tr>
						<td align="center" style=" font-size:8px; line-height: 0.8em;" colspan="2">Dirección: Calle Sucre c/c Monagas, Edificio Sede de la Contraloría del estado Monagas, Maturín. Telefono: 0291-6410441 - 6432713</td>
						</tr><tr>
						<td  align="center" style=" font-size:8px; line-height: 0.8em;" colspan="2">Correo Electrónico: contraloriamonagas@contraloriamonagas.gob.ve, www.contraloriamonagas.gob.ve</td>
						</tr>
						<tr><td colspan="2" align="right" style=" font-size:10px; line-height: 0.8em;"><b>CEM-M005</b></td></tr>
						</table>
			  </td></tr>	   

<!--<center><input type="button" name="imprimir" value="Imprimir" onclick="window.print();"></center>-->
</table>
<? 
  }else{
  //// ------------------------------- PUNTO DE CUENTA -------------------------
  //echo $ftcuenta['DescripCorta'];	
   if($ftcuenta['DescripCorta']=='PC'){
?>
<table id="principal" name="principal" width="700" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
  <td>
  <table align="center" width="700" border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td width="119" align="center"><img src="imagenes/logoLlaves.jpg" width="64" height="83"/></td>
    <td width="397">
    <table width="395" height="101" border="1" align="center">
    <tr>
       <td width="402" height="35" align="center"><font size="2" face="Arial, Helvetica, sans-serif"><b>CONTRALORIA DEL ESTADO MONAGAS</b></font></td>
    </tr>
    <tr>
       <td align="center" height="25"><font size="2" face="Arial, Helvetica, sans-serif"><b>PUNTO DE CUENTA</b></font></td>
    </tr>   
    </table>
    </td>
    <td width="176">
    <table width="175" height="100" border="1" align="center" cellpadding="0" cellspacing="0">
    <tr>
       <td colspan="2" align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b>CODIGO:</b></font></td>
    </tr>
    <tr>
       <td align="center" colspan="2"><font size="1" face="Arial, Helvetica, sans-serif"><b>FOR-CEDA-001</b></font></td>
    </tr>
    <tr>
       <td colspan="2" align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b>REVISION</b></font></td>
    </tr>
    <tr>
       <td width="87" align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b>N°:</b></font></td>
       <td width="82" align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b>FECHA</b></font></td>
    </tr>
     <tr>
       <td height="15" align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b>0</b></font></td>
       <td height="15" align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b>05/2010</b></font></td>
    </tr>
    <tr>
       <td colspan="2" align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b>PAGINA</b></font></td>
    </tr>
    <tr>
       <td colspan="2" align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b>1 DE 1</b></font></td>
    </tr>
    </table>
    </td>
  </tr>
  </table>
  </td>
</tr>
</table>
<table width="700"><tr><td height="5"></td></tr></table>
<!-- ////////// ---------- TABLA DATOS GENERALES ---------- /////////// --->
<table align="center" width="700" border="1">
<tr>
  <td width="87" rowspan="2"><font size="1" face="Arial, Helvetica, sans-serif"><b>PRESENTADO:</b></font></td>
  <td width="33"><font size="1" face="Arial, Helvetica, sans-serif"><b>A:</b></font></td>
  <td width="385"><font size="1" face="Arial, Helvetica, sans-serif"><b><?=$fc['DescripCargo']?></b></font></td>
  <td width="167"><font size="1" face="Arial, Helvetica, sans-serif"><b>NUMERO: <?=$cod_documentocompleto;?></b></font></td>
</tr>
<tr>
    <? 
	 list($a, $m, $d)=SPLIT( '[/.-]', $fa['FechaDocumento']); $f_documento=$d.'-'.$m.'-'.$a
	?>
  <td><font size="1" face="Arial, Helvetica, sans-serif"><b>POR:</b></font></td>
  <td><font size="1" face="Arial, Helvetica, sans-serif"><b><?=htmlentities($fb['Dependencia'])?></b></font></td>
  <td><font size="1" face="Arial, Helvetica, sans-serif"><b>FECHA: <?=$f_documento;?></b></font></td>
</tr>
</table>
<table width="700"><tr><td height="5"></td></tr></table>
<!-- ////////// ---------- ASUNTO ---------- /////////// --->
<table id="asunto" align="center" width="700" border="1">
<tr>
  <td width="20" align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b>ASUNTO:</b></font></td>
  <td><font size="1" face="Arial, Helvetica, sans-serif"><b><?=$fa['Asunto']?></b></font></td>
</tr>
<tr>
  <td colspan="2" align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b>PROPUESTA:</b></font></td>
</tr>
<tr>
 <td height="300" colspan="2" align="center"><div align="center" style="width:690px;height:300px; text-align:justify"><font face="Arial" size="2"><?=$fa['Contenido']?></font></div><!--<textarea cols="83" rows="17" id="instrucciones"><?=$fa['Contenido']?></textarea>--></td>
</tr>
</table><br />
<!-- ////////// ---------- RESULTADO ---------- /////////// --->
<table id="resultado" align="center" width="700" cellpadding="0" cellspacing="0" border="1">
<tr>
   <td colspan="4" align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b>RESULTADO:</b></font></td>
</tr>
<tr>
 <td align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b>APROBADO</b></font></td>
 <td align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b>NEGADO</b></font></td>
 <td align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b>DIFERIDO</b></font></td>
 <td align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b>VISTO</b></font></td>
</tr>
<tr>
 <td align="center" height="15"><font size="1" face="Arial, Helvetica, sans-serif"><b></b></font></td>
 <td align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b></b></font></td>
 <td align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b></b></font></td>
 <td align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b></b></font></td>
</tr>
<tr>
 <td><font size="1" face="Arial, Helvetica, sans-serif"><b></b></font></td>
 <td><font size="1" face="Arial, Helvetica, sans-serif"><b></b></font></td>
 <td><font size="1" face="Arial, Helvetica, sans-serif"><b></b></font></td>
 <td><font size="1" face="Arial, Helvetica, sans-serif"><b></b></font></td>
</tr>
<tr>
 <td colspan="4" align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b>INSTRUCCIONES U OBSERVACIONES</b></font></td>
</tr>
<tr>
 <td colspan="4" height="150"><font size="1" face="Arial, Helvetica, sans-serif"><b></b></font></td>
</tr>
</table>
<table width="700"><tr><td height="5"></td></tr></table>
<!-- ////////// ---------- FIRMAS ---------- /////////// --->
<table align="center" id="firmas" cellpadding="0" cellspacing="0" width="700" border="1">
<tr>
   <td width="175"><font size="1" face="Arial, Helvetica, sans-serif"><b>PREPARADO POR:</b></font></td>
   <td width="175"><font size="1" face="Arial, Helvetica, sans-serif"><b>SELLO</b></font></td>
   <td width="175"><font size="1" face="Arial, Helvetica, sans-serif"><b>CONFORMADO POR:</b></font></td>
   <td width="175"><font size="1" face="Arial, Helvetica, sans-serif"><b>SELLO</b></font></td>
</tr>
<tr>
   <td align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b>FIRMA:</b></font></td>
   <td rowspan="3"></td>
   <td align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b>FIRMA:</b></font></td>
   <td rowspan="3"></td>
</tr>
<tr>
   <td height="25"><font size="1" face="Arial, Helvetica, sans-serif"><b></b></font></td>
   <td height="10"><font size="1" face="Arial, Helvetica, sans-serif"><b></b></font></td>
</tr>
<tr><? 
      list($a,$m,$d)=SPLIT('[-]',$fa['FechaDocumento']);
      $fechaDocumento = $d.'-'.$m.'-'.$a;
	?>
   <td height="20"><font size="1" face="Arial, Helvetica, sans-serif"><b>FECHA: <?=$fechaDocumento?></b></font></td>
   <td><font size="1" face="Arial, Helvetica, sans-serif"><b>FECHA:</b></font></td>
</tr>
</table>
<table width="700"><tr><td height="5"></td></tr></table>
<!-- ////////// ---------- ANEXOS ---------- /////////// --->
<table align="center" id="anexos" width="700" border="1">
<tr>
<?
 if($fa['FlagsAnexo']=='S'){
	 $valor1 = 'X'; $valor2 = '';
 }else{
   if($fa['FlagsAnexo']=='N'){
	 $valor1 = ''; $valor2 = 'X';
 }
 }
?>
   <td width="44" rowspan="2"><font size="1" face="Arial, Helvetica, sans-serif"><b>ANEXOS:</b></font></td>
   <td width="35" align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b>SI</b></font></td>
   <td width="26" align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b><?=$valor1;?></b></font></td>
    <td width="567" rowspan="2"><font size="1" face="Arial, Helvetica, sans-serif"><b>LISTA DE ANEXOS:<?=$fa['DescripcionAnexo']?></b></font></td>
</tr>
<tr>
   <td align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b>NO</b></font></td>
   <td rowspan="2" align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b><?=$valor2;?></b></font></td>
</tr>
</table>
<?	
 }else{
 if($ftcuenta['DescripCorta']=='CR'){
?>
<table id="principal"  align="center">
<tr><td width="707">
  <!-- *********************** -->
  <table align="center">
  <tr><td>
  <!-- CABECERA DEL DOCUMENTO -->
  <table width="679" align="right" id="cabecera" cellpadding="0" cellspacing="0">
  <tr>
   <td width="3"></td>
   <td width="124" align="center"><img src="imagenes/logoLlaves.jpg" style="height:80px; width:80px" /></td>
   <td width="10"></td>
   <td width="420">
   <!-- *********************** -->
   <table cellpadding="0" cellspacing="0">
   <tr>
      <td align="center" width="414"><font size="3" face="Arial">REPUBLICA BOLIVARIANA DE VENEZUELA</font></td>
   </tr>
   <tr>
      <td align="center"><font size="3" face="Arial">CONTRALORIA DEL ESTADO MONAGAS</font></td>
   </tr>
  <tr>
      <td align="center"><font size="3" face="Arial"><?=htmlentities($fb['Dependencia'])?></font></td>
    </tr>
   </table>
   <!-- *********************** -->
   </td>
   <td width="120" align="center"><img src="imagenes/logoContraloria.jpg" style="height:80px; width:80px"/></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  </table>
  <!-- FIN CABECERA DEL DOCUMENTO -->
  </td></tr>
  </table>
  <!-- *********************** -->
  
</td></tr>
<tr><td>
   <!-- *********************** -->
   <table width="688" height="27" id="numero_doc"> 
   <!-- <tr><? list($ano, $meses, $dia) = SPLIT('[-]',$fa['FechaRegistro']);
   switch ($meses) {
		case 01: $mes = Enero; break;  case 07: $mes = Julio; break;
		case 02: $mes = Febrero;break; case 08: $mes = Agosto; break;
		case 03: $mes = Marzo;break;   case 09: $mes = Septiembre; break;
		case 04: $mes = Abril;break;   case 10: $mes = Octubre; break;
		case 05: $mes = Mayo;break;    case 11: $mes = Noviembre; break;
		case 06: $mes = Junio;break;   case 12: $mes = Diciembre; break;
	 }
       ?>
     <td colspan="5" align="right">Tucupita, <?=$dia;?> de <?=$mes;?> de <?=$ano;?></td>
   </tr>
   <tr>
     <td colspan="5" align="right">202º y 153º</td>
   </tr>-->
   <tr>
     <td height="20"></td>
   </tr>
   <tr>
    <td width="30"></td>
    <td width="250" height="21"><font face="Arial"><b><?=$cod_documentocompleto?></b></font></td>
    <td width="26"></td>
    <td width="26"></td>
    <td width="436"></td>
    <td width="26"></td>
   </tr>
   </table>
   <!-- *********************** -->
</td></tr>

<tr><td>
   <!-- *********************** -->
   <table id="titulo">
   <tr>
    <td width="0"></td>
    <td width="0"></td>
    <td width="268"></td>
    <td width="148" align="center"><font size="3" face="Arial"><b>CREDENCIAL</b></font></td>
    <td width="0"></td>
  </tr>
  <tr><td height="5"></td></tr>
  </table>
  <!-- *********************** -->
</td></tr>

<tr><td>
  <!-- CUERPO 1 DEL DOCUMENTO -->
  
  <!-- *********************** -->
</td></tr>

<tr><td>
  <!-- CONTENIDO DEL DOCUMENTO -->
  <table width="701">
  <tr>
    <td width="28"></td>
    <td width="646"><div style="width:650px;"><font face="Arial" size="2"><?=$fa['Contenido']?></font></div></td>
    <td width="11"></td>
  </tr>
  </table>
  <!-- *********************** -->
<tr><td>
  <!-- *********************** -->
  
  
  <tr><td>
  <!-- *********************** -->
  <table align="center" id="atentamente" width="500">
  <tr>
    <td width="58"></td>
    <td align="center"><font face="Arial" size="3"></font></td>
    <td width="61"></td>
  </tr>
  <tr>
    <td width="58"></td>
    <td height="25"></td>
    <td width="61"></td>
  </tr>
  <tr>
    <td width="58"></td>
    <td align="center"></td>
    <td width="61"></td>
  </tr>
  <tr>
  <?
   $sd = "select * from rh_puestos where CodCargo = '".$fa['Cod_CargoRemitente']."'";
   $qd = mysql_query($sd) or die ($sd.mysql_error()); //echo $sa;
   $fd = mysql_fetch_array($qd); 
  ?>
    <td width="58"></td>
    <td align="center" width="365"><font face="Arial" size="2"><?=$fb['NomCompleto']?></font></td>
    <td width="61"></td>
  </tr>
  <tr>
    <td width="58"></td>
    <td align="center" width="600"><font face="Arial" size="2"><?=$fd['DescripCargo']?></font></td>
    <td width="61"></td>
  </tr>
  <?
    $s_nivelacion = "select max(Secuencia) from rh_empleadonivelacion where CodPersona='".$fb['CodPersona']."'";
	$q_nivelacion = mysql_query($s_nivelacion) or die ($s_nivelacion.mysql_error());
	$f_nivelacion = mysql_fetch_array($q_nivelacion);
	
    $s_nivelacion2 = "select Documento from rh_empleadonivelacion where CodPersona='".$fb['CodPersona']."' and Secuencia = '".$f_nivelacion['0']."'";
	$q_nivelacion2 = mysql_query($s_nivelacion2) or die ($s_nivelacion2.mysql_error());
	$f_nivelacion2 = mysql_fetch_array($q_nivelacion2);
  ?>
  <!--<tr>
    <td width="58"></td>
    <td align="center" width="600"><font size="1"><?=$f_nivelacion2['Documento'];?></font></td>
    <td width="61"></td>
  </tr>-->
  </table>
  <!-- *********************** -->
</td></tr>

  <tr><td>
  <!-- *********************** -->
  <? if($fb['CodPersona'] == '000071'){?>
  <table align="center" id="atentamente" cellpadding="0" cellspacing="0">
  <tr>
    <td height="1"></td>
  </tr>
  <tr>
    <td align="center"></td>
  </tr>
  <!--<tr>
    <td align="center">Licdo. FREDDY CUDJOE</td>
  </tr>
  <tr>
    <td align="center">CONTRALOR (I) DEL ESTADO DELTA AMACURO</td>
  </tr> -->
  <tr>
    <td align="center"><font size="1">Designado mediante Resolución Nº. 01-00-000002 de  Fecha 05-01-2009,</font></td>
  </tr>
  <tr>
    <td align="center"><font size="1">Emanada del Despacho del Contralor General de la República,</font></td>
  </tr>
  <tr>
    <td align="center"><font size="1">publicada en G.O.Nº 39.092 de fecha 06-01-2009</font></td>
  </tr>
  </table>
  <? }else{
	   if($fb['CodPersona'] == '000049'){?>
  <table align="center" id="atentamente" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center"></td>
  </tr>
  <!--<tr>
    <td align="center">Licdo. FREDDY CUDJOE</td>
  </tr>
  <tr>
    <td align="center">CONTRALOR (I) DEL ESTADO DELTA AMACURO</td>
  </tr> -->
  <tr>
    <td align="center"><font size="1">Seg&uacute;n Resolución Nº. CEDA 0003-2011 de  Fecha 07-01-2011,</font></td>
  </tr>
  <tr>
    <td align="center"><font size="1">Publicada en Gaceta Oficial Estado Delta Amacuro N° 01 Extraordinario de Fecha 10-01-2011</font></td>
  </tr>
  </table>
  <? }else{
	   if($fb['CodPersona'] == '000061'){?>
         <table align="center" id="atentamente" cellpadding="0" cellspacing="0">
        <tr>
        <td align="center"></td>
        </tr>
        <!--<tr>
        <td align="center">Licdo. FREDDY CUDJOE</td>
        </tr>
        <tr>
        <td align="center">CONTRALOR (I) DEL ESTADO DELTA AMACURO</td>
        </tr> -->
        <tr>
        <td align="center"><font size="1">Seg&uacute;n Resolución Nº. CEDA 0029-2012 de  Fecha 22-02-2012</font></td>
        </tr>
        <!--<tr>
        <td align="center"><font size="1">Publicada en Gaceta Oficial Estado Delta Amacuro N° 01 Extraordinario de Fecha 10-01-2011</font></td>
        </tr>-->
        </table>
  <?   }
	  }}?>
</td></tr>

<tr><td>
  <!-- *********************** -->
  <table width="686" id="pie_pagina">
  <tr>
     <td width="26"></td>
     <td width="70"><font face="Arial" size="2"><?=$fa['MediaFirma']?></font></td>
     <td width="421"></td>
     <td width="149" align="right"></td>
  </tr>
  </table>
  <!-- *********************** -->
</td></tr>
<!--<center><input type="button" name="imprimir" value="Imprimir" onclick="window.print();"></center>-->
</table>
<?
 }}}}}else
 if($fcon['Procedencia']=='EXT'){    
?>
<table align="center">
<tr>
  <td><font size="4"><b>Documento de Procedencia Externa</b></font></td>
</tr>
</table>

<?}?>

</td></tr>
<div id="divButtons" name="divButtons">  
<input type="button" id="imprimir" name="imprimir" value = "Imprimir" onclick="printPage()"/> 
</div> 
<div id="divButtons2" name="divButtons2">  
<input type="button" id="vista" name="vista" value = "Vista Previa" onclick="printPage2()"/> 
</div> 
</table>
</body>
</html>
