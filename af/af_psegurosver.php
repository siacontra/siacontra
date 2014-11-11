<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
include ("fphp.php");
connect();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<link href="css1.css" rel="stylesheet" type="text/css" />-->
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
<script type="text/javascript" src="../js/funciones.js" charset="utf-8"></script>
</head>
<?
 //// CONSULTA
 $s_con = "select * from af_polizaseguro where CodPolizaSeguro= '".$registro."'";
 $q_con = mysql_query($s_con) or die ($s_con.mysql_error());
 $f_con = mysql_fetch_array($q_con);
 
?>
<body>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<table width="100%" cellspacing="0" cellpadding="0">
 <tr>
  <td class="titulo">Maestro P&oacute;liza de Seguros | Ver Registro</td>
  <td align="right">
   <a class="cerrar" href="" onclick="window.close();">[cerrar]</a>
  </td>
 </tr>
</table>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<hr width="100%" color="#333333" />
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<form name="fentrada" id="fentrada" action="af_pseguros.php" method="POST" onsubmit="return  editarPolizaSeguros(this);">
<?php echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />"; ?>

<div style="width:700px" class="divFormCaption">Datos</div>
<table width="700" class="tblForm">
  <tr>
    <td width="236" class="tagForm">P&oacute;liza de Seguro:</td>
    <td width="452"><input type="text" name="cod_pseguro"  id="cod_pseguro" size="10" maxlength="8" value="<?=$f_con['CodPolizaSeguro'];?>" readonly/>*</td>
  </tr>
  <tr>
    <td class="tagForm">Descripci&oacute;n Local:</td>
    <td><input type="text" name="descp_pseguro"  id="descp_pseguro" size="60" maxlength="30" value="<?=$f_con['DescripcionLocal'];?>" readonly="readonly"/>*</td>
  </tr>
  <tr><td height="10"></td></tr>
  <tr>
    <td class="tagForm">Empresa Aseguradora:</td>
    <td><input type="text" name="empa_pseguro"  id="empa_pseguro" size="60" maxlength="50" value="<?=$f_con['EmpresaAseguradora'];?>" readonly="readonly"/>*</td>
  </tr>
  <tr>
    <td class="tagForm">Agente de Seguros:</td>
    <td><input type="text" name="ages_pseguro"  id="ages_pseguro" size="60" maxlength="50" value="<?=$f_con['AgenteSeguros'];?>" readonly="readonly"/>*</td>
  </tr>
  <tr>
    <td class="tagForm">Fecha Vencimiento:</td>
    <?
	 list($a,$m,$d,$h,$i,$s) = SPLIT('[-: ]', $f_con['FechaVencimiento']);
     $fecha_vencimiento = $d.'-'.$m.'-'.$a.' '.$h.':'.$i.':'.$s;
	?>
    <td><input type="text" name="fvenc_pseguro"  id="fvenc_pseguro" size="20" maxlength="25" value="<?=$fecha_vencimiento;?>" readonly="readonly"/>*</td>
  </tr>
  <tr><td height="10"></td></tr>
  <tr>
    <td class="tagForm">Monto Cobertura:</td>
    <td><input type="text" name="mcober_pseguro"  id="mcober_pseguro" size="25" maxlength="25" style="text-align:right" value="<?=$f_con['MontoCobertura'];?>" readonly="readonly"/>*</td>
  </tr>
  <tr>
    <td class="tagForm">Costo P&oacute;liza:</td>
    <td><input type="text" name="cpoli_pseguro"  id="cpoli_pseguro" size="25" maxlength="25" style="text-align:right" value="<?=$f_con['CostoPoliza'];?>" readonly="readonly"/>*</td>
  </tr>
   <tr>
	    <td class='tagForm'>Estado:</td>
	    <td><?
             if($f_con['Estado']=='A'){
		   ?>
		   <input name='radio1' type='radio' checked="checked"/> Activo
		   <input name='radio2' type='radio' disabled/> Inactivo
           <? }else{?>
           <input name='radio1' type='radio' disabled/> Activo
		   <input name='radio2' type='radio' checked="checked"/> Inactivo
           <? }?>
	   </td>
  </tr>
  <tr>
   <td height="5"></td>
  </tr>
  <tr><? 
     echo"
	<td class='tagForm'>&Uacute;ltima Modif.:</td>
	<td>
	  <input name='ult_usuario' type='text' id='ult_usuario' size='30' value='".$f_con['UltimoUsuario']."' readonly />
	  <input name='ult_fecha' type='text' id='ult_fecha' size='20' value='".$f_con['UltimaFechaModif']."' readonly />
	</td>";
	?>
  </tr>
</table>
</form>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>

