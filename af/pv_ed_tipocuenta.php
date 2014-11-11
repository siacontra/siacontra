<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="pv_fscript.js"></script>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Tipo Cuenta | Actualizaci&oacute;n</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?php
include("fphp.php");
connect();
$sql="SELECT * FROM pv_tipocuenta WHERE cod_tipocuenta='".$_POST['registro']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if($rows!=0) {
  $field=mysql_fetch_array($query);
  //////   PRUEBA  //////    //////    //////  //////    //////
  echo "
  <form name='frmentrada' action='pv_mtipocuenta.php?accion=editarTcuenta' method='POST' onsubmit='return verificarTcuenta(this, \"EDITAR\");'>
  <input type='hidden' name='filtro' id='filtro' value='".$_GET['filtro']."'>
  <div style='width:700px' class='divFormCaption'>Datos de Tipo Cuenta</div>
  <table width='700' class='tblForm'>
  <tr>
	<td class='tagForm'>Tipo Cuenta:</td>
	<td><input name='codtipocuenta' type='text' id='codtipocuenta' size='5' maxlength='1' value='".$field['cod_tipocuenta']."'/></td>
  </tr>
  <tr>
	<td class='tagForm'>Descripci&oacute;n:</td>
	<td><input name='descripcion' type='text' id='descripcion' size='60' maxlength='60' 
		    value='".htmlentities($field['descp_tipocuenta'])."'/></td>
  </tr>
  <tr>
	<td class='tagForm'>Estado:</td>
	<td>";
	 	   if ($field['Estado']=="A") echo "<input name='status' type='radio' value='A' checked /> Activo"; 
				else echo "<input name='status' type='radio' value='A' /> Activo";
				if ($field['Estado']=="I") echo "<input name='status' type='radio' value='I' checked /> Inactivo"; 
				else echo "<input name='status' type='radio' value='I' /> Inactivo";
			echo "
	</td>
  </tr>
  <tr>
	<td class='tagForm'>&Uacute;ltima Modif.:</td>
	<td>
	  <input name='ult_usuario' type='text' id='ult_usuario' size='30' value='".$field['UltimoUsuario']."' readonly />
	  <input name='ult_fecha' type='text' id='ult_fecha' size='25' value='".$field['UltimaFecha']."' readonly />
	</td>
  </tr>
  </table>";
	//////         //////       //////      //////    //////    
	echo "
	<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />
	<center> 
	<input type='submit' value='Guardar Registro' name='editar' id='editar' />
	<input name='bt_cancelar' type='button' id='bt_cancelar' value='Cancelar' onclick='cargarPagina(this.form, \"pv_mtipocuenta.php\");' />
	</center><br />
	</form>
	<div style='width:600px' class='divMsj'>Campos Obligatorios *</div>";
}
?>
<SCRIPT LANGUAGE="JavaScript">
function verificarTcuenta(formulario) {
		   //VALIDACION TIPO CUENTA
		   if (formulario.codtipocuenta.value.length <1) {
	  		 alert("Escriba los datos correctos en el campo \"Tipo Cuenta\".");
	   		 formulario.codtipocuenta.focus();
	      return (false);
	      }
          var checkOK = "123456789";
	      var checkStr = formulario.codtipocuenta.value;
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
	         formulario.codtipocuenta.focus(); 
	         return (false); 
	       } 
	       //VALIDACION DESCRIPCION
		   if (formulario.descripcion.value.length <4) {
	  		 alert("Escriba los datos correctos en el campo \"Descripción\".");
	   		 formulario.descripcion.focus();
	      return (false);
	      }
          var checkOK = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚ" + "abcdefghijklmnñopqrstuvwxyzáéíóú" + "./ ";
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
<? include "pv_gmsector.php";?>
</body>
</html>
