<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript01.js"></script>
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>

<body>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<table width="100%" cellspacing="0" cellpadding="0">
 <tr>
  <td class="titulo">Maestro de Partida | Nuevo Registro</td>
  <td align="right">
   <a class="cerrar" href="framemain.php">[cerrar]</a>
  </td>
 </tr>
</table>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<hr width="100%" color="#333333" />
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<form name="fentrada" id="fentrada" action="npartida.php?accion=guardarpartida" method="POST" onsubmit="return  verificarPartida(this, 'GUARDAR');">
<?php echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />"; ?>

<div style="width:700px" class="divFormCaption">Datos</div>
<table width="700" class="tblForm">
  <table id="tipocuenta" width="700" class="tblForm">
  <tr>
     <td class="tagForm">Tipo Cuenta:</td>
	 <td><select name="tcpartida">
        <option value="-1">- -</option>

<?php 
    // segundo bloque php //* Conectamos a los datos *//
	include "conexion.php";
	$sql="SELECT * FROM pv_tipocuenta WHERE 1";
	$rs=mysql_query($sql);
	$cod_tc='';
	$descp_tc='';
	while($reg=mysql_fetch_assoc($rs)){
	$cod_tc=$reg['cod_tipocuenta'];// CODIGO TIPO CUENTA
	$descp_tc=$reg['descp_tipocuenta'];// Codigo Programa
	if(($cod_tipocuenta==$cod_tc)){ 
	   echo "<option value=$cod_tc>$descp_tc</option>";
	}else{
	   echo "<option value=$cod_tc>$descp_tc</option>";
	}
	}
    ?></select></td>
	<td width="339"></td>
  </tr>
  </table>
  <table id="partida" width="700" class="tblForm">
  <tr>
     <td class="tagForm">Partida:</td>
	 <td colspan="4"><input name="partida1" type="text" id="partida1" size="10" maxlength="2"/>*</td>
  </tr>
  <tr>
     <td class="tagForm">Gen&eacute;rica:</td>
	 <td colspan="4"><input name="generica" type="text" id="generico" size="10" maxlength="2"/>*</td>
  </tr>
  <tr>
     <td class="tagForm">Espec&iacute;fica:</td>
	 <td colspan="4"><input name="especifica" type="text" id="especifica" size="10" maxlength="2"/>*</td>
  </tr>
  <tr>
     <td class="tagForm">Sub-Espec&iacute;fica:</td>
	 <td colspan="4"><input name="subespecifica" type="text" id="subespecifica" size="10" maxlength="2"/>*</td>
  </tr>
  <tr>
	 <td align="right">Denominaci&oacute;n:</td>
     <td width="350"><input name="denominacion" type="text" id="denominacion" size="75" maxlength="70" />*</td>
	 <td width="65"><input name="opcion" type="radio" value="D"/>Detalle
	                <input name="opcion" type="radio" value="T"/>T&iacute;tulo</td>
	 <td width="80"></td>
  </tr>
  </tr>
  </table>
  <table class="tblForm" width="700">
  <tr>
     <td class="tagForm">Nivel:</td>
	 <td><select name="nivel">
         <OPTION VALUE="1">1</OPTION>
         <OPTION VALUE="2">2</OPTION>
         <OPTION VALUE="3">3</OPTION>
		 <OPTION VALUE="4">4</OPTION>
		 <OPTION VALUE="5">5</OPTION>
         </SELECT> 
    </td>
  </tr>
  <tr>
	 <td class='tagForm'>Estado:</td>
	 <td>
	  <input name='status' type='radio' value='A' /> Activo
	  <input name='status' type='radio' value='I' /> Inactivo
	 </td>
	 <td width="60"></td>
  </tr>
  <tr>
	 <td class='tagForm' align="left">&Uacute;ltima Modif.:</td>
	 <td>
	   <input name='ult_usuario' type='text' id='ult_usuario' size='30'  readonly />
	   <input name='ult_fecha' type='text' id='ult_fecha' size='25' readonly />
	 </td>
  </tr>
  </table>
</table>
  <!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
  <center>
    <input name="guardar" type="submit" id="guardar" value="Guardar Registro"/>
    <input name="cancelar" type="button" id="cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'mpartida.php?limit=0');" />
  </center><br />
  <!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
</form>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>

<SCRIPT LANGUAGE="JavaScript">
function verificarPartida(formulario) {
	
	       //VALIDACION TIPO CUENTA
		   if (formulario.tipocuenta.value.length <1) {
	  		 alert("Escriba los datos correctos en el campo \"Tipo Cuenta\".");
	   		 formulario.tipocuenta.focus();
	      return (false);
	      }
          var checkOK ="123456789";
	      var checkStr = formulario.tipocuenta.value;
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
	         alert("Escriba sólo números en el campo \"Tipo Cuenta\"."); 
	         formulario.tipocuenta.focus(); 
	         return (false); 
	       } 
		   //////////////////////////
		   //VALIDACION PARTIDA
		   if (formulario.partida1.value.length <2) {
	  		 alert("Escriba los datos correctos en el campo \"Partida\".");
	   		 formulario.partida1.focus();
	      return (false);
	      }
          var checkOK ="0123456789";
	      var checkStr = formulario.partida1.value;
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
	         alert("Escriba sólo números en el campo \"Partida\"."); 
	         formulario.partida1.focus(); 
	         return (false); 
	       } 
		   ///////////////////////////////
		   //VALIDACION GENERICA
		   if (formulario.generica.value.length <2) {
	  		 alert("Escriba los datos correctos en el campo \"Genérica\".");
	   		 formulario.generica.focus();
	      return (false);
	      }
          var checkOK ="0123456789";
	      var checkStr = formulario.generica.value;
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
	         alert("Escriba sólo números en el campo \"Genérica\"."); 
	         formulario.generica.focus(); 
	         return (false); 
	       } 
		   ///////////////////////////////
		   //VALIDACION ESPECIFICA
		   if (formulario.especifica.value.length <2) {
	  		 alert("Escriba los datos correctos en el campo \"Específica\".");
	   		 formulario.especifica.focus();
	      return (false);
	      }
          var checkOK ="0123456789";
	      var checkStr = formulario.especifica.value;
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
	         alert("Escriba sólo números en el campo \"Específica\"."); 
	         formulario.especifica.focus(); 
	         return (false); 
	       } 
		   ///////////////////////////////
		   //VALIDACION SUB-ESPECIFICA
		   if (formulario.subespecifica.value.length <2) {
	  		 alert("Escriba los datos correctos en el campo \"Sub-Específica\".");
	   		 formulario.subespecifica.focus();
	      return (false);
	      }
          var checkOK ="0123456789";
	      var checkStr = formulario.subespecifica.value;
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
	         alert("Escriba sólo números en el campo \"Sub-Específica\"."); 
	         formulario.subespecifica.focus(); 
	         return (false); 
	       } 
		   //////////////////////////
		   //VALIDACION DENOMINACION
		   if (formulario.descripcion.value.length <5) {
	  		 alert("Escriba los datos correctos en el campo \"Descripción\".");
	   		 formulario.descripcion.focus();
	      return (false);
	      }
          var checkOK = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚ" + "abcdefghijklmnñopqrstuvwxyzáéíóú" + " ._/";
	      var checkStr = formulario.descripcion.value;
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
	         alert("Escriba sólo letras en el campo \"Descripción\"."); 
	         formulario.descripcion.focus(); 
	         return (false); 
	       } 
	return (true); 
	} 
</SCRIPT>
<? include "gmsector.php";?>
</body>
</html>
