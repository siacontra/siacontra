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

}
-->
</style>
<script language="JavaScript"> 
function printPage() {  

document.getElementById('divButtons').style.visibility = 'hidden';  
window.print();  
document.getElementById('divButtons').style.visibility = 'visible';  

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
 
 //// consulta para verificar datos
 $scon="select *  from cp_documentodistribucion where Cod_Documento = '$cod_documento'";
 $qcon = mysql_query($scon) or die ($scon.mysql_error());
 $rcon = mysql_num_rows($qcon);
 

//if ($fcon['Procedencia']=='INT'){ 
 //// CONSULTA PARA OBTNER DATOS A MOSTRAR
 
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
				   inner join mastpersonas mp on (md.CodPersona=mp.CodPersona) 
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
<td width="1200px">

<table id="principal"  align="center" valign="top">
<tr><td width="707">
  <!-- *********************** -->
  <table align="center" valign="top">
  <tr><td>
  <!-- CABECERA DEL DOCUMENTO -->
  <table width="679" align="right" id="cabecera" cellpadding="0" cellspacing="0" valign="top">
  <tr>
   <td width="3"></td>
   <td width="124" align="center"><img src="../imagenes/logos/contraloria.jpg" style="height:80px; width:110px" /></td>
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
      <td align="center"><font size="3" face="Arial"><?//=htmlentities($fb['Dependencia']);?></font></td>
    </tr>
   </table>
   <!-- *********************** -->
   </td>
   <td width="120" align="center"></td>
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
   <table width="688" height="27" id="numero_doc" > 
   <tr>
    <td width="26" height="21"></td>
    <td width="26"></td>
    <td width="26"></td>
    <td width="436"></td>
    <td width="150"><font face="Arial"><b>N°:<?=$cod_documento?></b></font></td>
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
    <td width="563"><font face="Arial"  style=" font-size:14px; line-height: 1.2em"><b>: <?=htmlentities($fc['Dependencia']);?></b></font></td>
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
    <td><font face="Arial"  style=" font-size:14px; line-height: 1.2em"><b>: <?=htmlentities($f['Dependencia']);?></b></font></td>
    </tr>
   <tr>
    <td width="34"></td>
    <td width="79"></td>
    <td><font face="Arial"  style=" font-size:14px; line-height: 1.2em">: <?=htmlentities($fb['NomCompleto']);?></font></td>
  </tr>
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
<td width="646"><div style="width:650px;" id="cont" name="cont"><font face="Arial" style=" font-size:13px; line-height: 1.2em"><?
    echo substr($fa['Contenido'],0,3000); ?> </font></div></td>
    
    <td width="11"></td>
  </tr>
  </table>
  <!-- *********************** -->

  <!-- *********************** -->

  <!-- *********************** -->
</td></tr>
<? if(strlen($fa['Contenido'])>3000) { ?>
<tr><td> </td></tr>

<tr><td> </td></tr>

<tr><td> </td></tr>

<tr><td> </td></tr>

<tr><td> </td></tr>

<tr><td> </td></tr>

<tr><td> </td></tr>

<tr><td> </td></tr>
<tr><td> </td></tr>

<tr><td> </td></tr>

<tr><td> </td></tr>

<tr><td> </td></tr>

<tr><td> </td></tr>

<tr><td> </td></tr>
<tr><td> </td></tr>

<tr><td> </td></tr>

<tr><td> </td></tr>

<tr><td> </td></tr>

<tr><td> </td></tr>

<tr><td> </td></tr>

<tr><td> </td></tr>

<tr><td> </td></tr>

<tr><td> </td></tr>

<tr><td> </td></tr>

<tr><td> </td></tr>

<tr><td> <table width="679" align="right" id="cabecera" cellpadding="0" cellspacing="0" valign="top">
  <tr>
   <td width="3"></td>
   <td width="124" align="center"><img src="../imagenes/logos/contraloria.jpg" style="height:75px; width:90px" /></td>
   <td width="10"></td>
   <td width="420"></td></tr></table> </td></tr>

<tr><td> </td></tr>

<tr><td> </td></tr>

<tr><td> </td></tr>

<tr><td> </td></tr>

<tr><td> </td></tr>

<tr><td> </td></tr>

<tr><td>  <table width="701">
  <tr>
    <td width="28"></td>
<td width="646"><div style="width:650px;" id="cont" name="cont"><font face="Arial" style=" font-size:13px; line-height: 1.2em"><?
    echo substr($fa['Contenido'],3001,strlen($fa['Contenido'])); ?> </font></div></td>
    
    <td width="11"></td>
  </tr>
  
  <tr>
    <td width="28"></td>
<td width="646" height="600">
	   <table align="center" >
		 
			  
			   <tr><td>
				   
				<table align="center" id="atentamente" width="500">
						<tr>
						<td width="71"></td>
						<td align="center"><font face="Arial" >Atentamente,</font></td>
						<td width="81"></td>
						</tr>
						<tr>
						<td width="71"></td>
						<td height="25"></td>
						<td width="81"></td>
						</tr>
						<tr>
						<td width="71"></td>
						<td align="center">___________________________________________________</td>
						<td width="81"></td>
						</tr>
						<tr>
						<?
						$sd = "select * from rh_puestos where CodCargo = '".$fa['Cod_CargoRemitente']."'";
						$qd = mysql_query($sd) or die ($sd.mysql_error()); //echo $sa;
						$fd = mysql_fetch_array($qd); 
						?>
						<td align="center" width="71"></td>
						<td align="center" width="365"><font face="Arial" style=" font-size:14px; line-height: 1.2em"><?=htmlentities($fb['NomCompleto']);?></font></td>
						<td width="81"></td>
						</tr>
						<tr>
						<td width="71"></td>
						<td align="center" width="365"><font face="Arial" style=" font-size:14px; line-height: 1.2em"><?=htmlentities($fd['DescripCargo']);?></font></td>
						<td width="81"></td>
						</tr>
						</table>

			  </td></tr></table>
	<div style="position:absolute; bottom:100;">
	 
			   <table>

			   <tr><td>
						<table  width="686" id="pie_pagina" valign="baseline" >
						<tr>
						<td width="71"></td>
						<td align="center" width="365"></td>
						<td width="81"></td>
						</tr>
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
		</table>	  
			  
  </div></td>
    
    <td width="11"></td>
  </tr>
  <? } ?>
  
  </table>
  <!-- *********************** -->
  <? if(strlen($fa['Contenido'])<=3000) { ?>
   <table align="center" >
		 
			  
			   <tr><td>
				   
				<table align="center" id="atentamente" width="500">
						<tr>
						<td width="71"></td>
						<td align="center"><font face="Arial" >Atentamente,</font></td>
						<td width="81"></td>
						</tr>
						<tr>
						<td width="71"></td>
						<td height="25"></td>
						<td width="81"></td>
						</tr>
						<tr>
						<td width="71"></td>
						<td align="center">___________________________________________________</td>
						<td width="81"></td>
						</tr>
						<tr>
						<?
						$sd = "select * from rh_puestos where CodCargo = '".$fa['Cod_CargoRemitente']."'";
						$qd = mysql_query($sd) or die ($sd.mysql_error()); //echo $sa;
						$fd = mysql_fetch_array($qd); 
						?>
						<td align="center" width="71"></td>
						<td align="center" width="365"><font face="Arial" style=" font-size:14px; line-height: 1.2em"><?=htmlentities($fb['NomCompleto']);?></font></td>
						<td width="81"></td>
						</tr>
						<tr>
						<td width="71"></td>
						<td align="center" width="365"><font face="Arial" style=" font-size:14px; line-height: 1.2em"><?=htmlentities($fd['DescripCargo']);?></font></td>
						<td width="81"></td>
						</tr>
						</table>

			  </td></tr></table><? } ?>
  <div style="position:absolute; bottom:0;">
	 
			   <table>

			   <tr><td>
						<table  width="686" id="pie_pagina" valign="baseline">
						<tr>
						<td width="71"></td>
						<td align="center" width="365"></td>
						<td width="81"></td>
						</tr>
						<tr>
						<td align="center" style=" font-size:9px; line-height: 0.5em;" colspan="2" >Hacia la transparencia, fortalecimiento y consolidadión del Sistema Nacional de Control Fiscal</td>
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
		</table>	  
			  
  </div>
  <!-- *********************** -->
</td></tr>
<!--<center><input type="button" name="imprimir" value="Imprimir" onclick="window.print();"></center>-->
</table>
<? 
}
} else{
  if ($fcon['Procedencia']=='EXT'){   
   
?>
<table align="center">
<tr>
  <td><font size="4"><b>Documento de Procedencia Externa</b></font></td>
</tr>
</table>
<? 
}}
?>
</td></tr>
<div id="divButtons" name="divButtons">  
<input type="button" id="imprimir" name="imprimir" value = "Imprimir" onclick="printPage()"/> 
</div> 
</table>
</body>
</html>
