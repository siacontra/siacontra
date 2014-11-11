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
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>

<body>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<table width="100%" cellspacing="0" cellpadding="0">
 <tr>
  <td class="titulo">Maestro Situaci&oacute;n de Activo | Ver Registro</td>
  <td align="right">
   <a class="cerrar" href="" onclick="window.close()">[cerrar]</a>
  </td>
 </tr>
</table>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<hr width="100%" color="#333333" />
<?
 $sql="SELECT * FROM af_situacionactivo WHERE CodSituActivo='".$_GET['registro']."'";
 $qry=mysql_query($sql) or die ($sql.mysql_error());
 $field=mysql_fetch_array($qry);
?>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<form name="fentrada" id="fentrada" action="af_situacion.php?accion=editarSituactivo" method="POST" onsubmit="return  verificarSituacion(this, 'GUARDAR');">
<?php echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />"; ?>

<div style="width:700px" class="divFormCaption">Datos</div>
<table width="700" class="tblForm">
  <tr>
    <td class="tagForm">Situaci&oacute;n del Activo:</td>
    <td><input type="text" name="cod_situactivo"  id="cod_situactivo" size="4" maxlength="2" value="<?=$field['CodSituActivo']?>" readonly/></td>
  </tr>
  <tr>
    <td class="tagForm">Descripci&oacute;n:</td>
    <td><input type="text" name="descp_situactivo"  id="descp_situactivo" size="60" maxlength="100" value="<?=$field['Descripcion']?>" READONLY/></td>
  </tr>
  <tr>
   <td>
    <table align="right" width="164">
    <tr>
     <td class="tagForm">Procesos Disponibles:</td>
     </tr>
    </table>
    </td>
    <td>
    <table>
    <tr>
    <td><?
        if($field['DepreciacionFlag']=='S'){?>
          <input type="checkbox" name="proceso_situactivo"  id="proceso_situactivo" checked onclick="this.checked=!this.checked" />
	    <? }else{?>
           <input type="checkbox" name="proceso_situactivo"  id="proceso_situactivo" disabled="disabled" />
        <? }?>
   </td>
     <td>Depreciaci&oacute;n</td>
    </tr>
    <tr>
    <td><?
	     if($field['RevaluacionFlag']=='S'){?>
            <input type="checkbox" name="proceso_ajuste"  id="proceso_ajuste" checked onclick="this.checked=!this.checked"/>
         <? }else{?> 
            <input type="checkbox" name="proceso_ajuste"  id="proceso_ajuste" disabled="disabled"/>  
         <? }?>     
    </td>
     <td>Ajuste x Inflaci&oacute;n</td>
    </tr>
    </table>
    </td>
   </tr>
   <tr>
    <td class='tagForm'>Estado:</td>
    <td>
    <? 
	  if($field['Estado']=='A'){?>
       <input name='status_situactivo' type='radio' value='A' checked="checked" /> Activo
       <input name='status_situactivo' type='radio' value='I' disabled="true" /> Inactivo
    <? }else{?>
       <input name='status_situactivo' type='radio' value='A' disabled="true"/> Activo
       <input name='status_situactivo' type='radio' value='I'  checked="checked"/> Inactivo
   <? }?>
   </td>
  </tr>
  <tr>
   <td height="5"></td>
  </tr>
  <tr>
    <? 
      if($field['UltimaFechaModif']!='0000-00-00 00:00:00'){
	      $ult_fecha=$field['UltimaFechaModif']; $ult_usuario=$field['UltimoUsuario'];
	  }else{
	      $ult_fecha=''; $ult_usuario='';
	  }
     echo"
	<td class='tagForm'>&Uacute;ltima Modif.:</td>
	<td>
	  <input type='text' name='ult_usuario'  id='ult_usuario' size='30' value='$ult_usuario'  readonly />
	  <input type='text' name='ult_fecha'  id='ult_fecha' size='25' value='$ult_fecha' readonly />
	</td>";
	?>
  </tr>
</table>
</form>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->

<SCRIPT LANGUAGE="JavaScript">
function verificarSituacion(formulario) {

 //VALIDACION CODIGO SITUACION
if (formulario.cod_situactivo.value.length <1) {
 alert("Introduzca la situación del activo en el campo \"Situación del Activo\".");
 formulario.cod_situactivo.focus();
return (false);
}
var checkOK = "0123456789" + "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ";
var checkStr = formulario.cod_situactivo.value;
var allValid = true; 
for (i = 0; i < checkStr.length; i++) {
  ch = checkStr.charAt(i); 
  for (j = 0; j < checkOK.length; j++)
	  if (ch == checkOK.charAt(j))
	  break;
	  if (j == checkOK.length) { 
		 allValid = false; 
	  break; 
	  }
}
if (!allValid) { 
 alert("Introduzca carateres permitidos en el campo \"Situación del Activo\"."); 
 formulario.cod_situactivo.focus(); 
 return (false); 
} 

//VALIDACION DESCRIPCION
if (formulario.descp_situactivo.value.length <2) {
 alert("Introduzca la descripción en el Campo \"Descripción\".");
 formulario.descp_situactivo.focus();
return (false);
}
var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ" + " ._/";
var checkStr = formulario.descp_situactivo.value;
var allValid = true; 
for (i = 0; i < checkStr.length; i++) {
  ch = checkStr.charAt(i); 
  for (j = 0; j < checkOK.length; j++)
	  if (ch == checkOK.charAt(j))
	  break;
	  if (j == checkOK.length) { 
		 allValid = false; 
	  break; 
	  }
}
if (!allValid) { 
 alert("Introduzca sólo caracteres permitidos en el campo \"Descripción\"."); 
 formulario.descp_situactivo.focus(); 
 return (false); 
} 

return (true); 
} 
</SCRIPT>
</body>
</html>

