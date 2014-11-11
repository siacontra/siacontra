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
  <td class="titulo">Maestro de Tipo Cuenta | Nuevo Registro</td>
  <td align="right">
   <a class="cerrar" href="framemain.php">[cerrar]</a>
  </td>
 </tr>
</table>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<hr width="100%" color="#333333" />
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<form name="fentrada" id="fentrada" action="ntipocuenta.php?accion=guardartcuenta" method="POST" onsubmit="return  verificarTipocuenta(this, 'GUARDAR');">
<?php echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />"; ?>

<div style="width:700px" class="divFormCaption">Datos</div>
<table width="700" class="tblForm">
  <table id="tipocuenta" width="700" class="tblForm">
  <tr>
     <td align="left" class="tagForm">Tipo Cuenta:</td>
	 <td><input name="tipocuenta" type="text" id="tipocuenta" size="5" maxlength="1"/>*</td>
  </tr>
  <tr>
     <td class="tagForm">Descripci&oacute;n:</td>
	 <td><input name="descptipocuenta" id="descptipocuenta" size="15" maxlength="7" type="text"/>*</td>
  </tr>
  <tr>
	    <td class='tagForm'>Estado:</td>
	    <td>
		   <input name='status' type='radio' value='A' /> Activo
		   <input name='status' type='radio' value='I' /> Inactivo
	   </td>
  </tr>
  </table>
  </table>
 
</table>
  <!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
  <center>
    <input name="guardar" type="submit" id="guardar" value="Guardar Registro"/>
    <input name="cancelar" type="button" id="cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'mtipocuenta.php');" />
  </center><br />
  <!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
</form>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>

<SCRIPT LANGUAGE="JavaScript">
function verificarTipocuenta(formulario) {
	
	       //VALIDACION TIPO CUENTA
		   if (formulario.tipocuenta.value.length =0) {
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
		   //VALIDACION DESCRIPCION TIPO CUENTA
		   if (formulario.descptipocuenta.value.length <5) {
	  		 alert("Escriba los datos correctos en el campo \"Descripción\".");
	   		 formulario.descptipocuenta.focus();
	      return (false);
	      }
          var checkOK ="ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚ" + "abcdefghijklmnñopqrstuvwxyzáéíóú";
	      var checkStr = formulario.descptipocuenta.value;
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
	         formulario.descptipocuenta.focus(); 
	         return (false); 
	       } 
		   
	return (true); 
	} 
</SCRIPT>
<? include "gmsector.php";?>
</body>
</html>