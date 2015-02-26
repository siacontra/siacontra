<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<!--<script type="text/javascript" language="javascript" src="fscript01.js"></script>-->
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>

<body>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<table width="100%" cellspacing="0" cellpadding="0">
 <tr>
  <td class="titulo">Maestro de Proyecto | Nuevo Registro</td>
  <td align="right">
   <a class="cerrar" href="framemain.php">[cerrar]</a>
  </td>
 </tr>
</table>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<hr width="100%" color="#333333" />
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<form name="fentrada" id="fentrada" action="mproyecto.php?accion=guardarProyecto" method="POST" onsubmit="return  verificarproyecto(this, 'GUARDAR');">
<?php echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />"; ?>

<div style="width:700px" class="divFormCaption">Datos</div>
<table width="700" class="tblForm">
  <tr>
    <td class="tagForm">Proyecto:</td>
    <td><input name="codproyecto" type="text" id="codproyecto" size="3" maxlength="2"/>*</td>
  </tr>
  <tr>
    <td class="tagForm">Descripci&oacute;n:</td>
    <td><input name="descripcion" type="text" id="descripcion" size="60" maxlength="100" />*</td>
  </tr>
  <tr>
    <td class="tagForm">Actividad:</td>
	<td><select name="selectSubprog">
        <option value="-1">- -</option>

<?php 
    // segundo bloque php //* Conectamos a los datos *//
	include "conexion_.php";
	$sql="SELECT * FROM pv_subprog1 ORDER BY id_sub";
	$rs=mysql_query($sql);
	while($reg=mysql_fetch_assoc($rs)){
	   $id=$reg['id_sub'];// Id Subprograma
	   $cs=$reg['cod_subprog'];// Codigo Subprograma
	   $cp=$reg['descp_subprog'];// Descripcion Subprograma
	   if (($cod_sector==$cs)){
	      echo "<option value=$id>$cs-$cp</option>";
	   }else {
	      echo "<option value=$id>$cs-$cp</option>";
	   }
	}
    ?></select>*</td>
  </tr>
   <tr>
	    <td class='tagForm'>Estado:</td>
	    <td>
		   <input name='status' type='radio' value='A' checked="checked" /> Activo
		   <input name='status' type='radio' value='I' /> Inactivo
	   </td>
  </tr>
<tr><? 
     $ahora=date("Y-m-d H:m:s");
     echo"
	<td class='tagForm'>&Uacute;ltima Modif.:</td>
	<td>
	  <input name='ult_usuario' type='text' id='ult_usuario' size='30' value='".$_SESSION['USUARIO_ACTUAL']."' readonly />
	  <input name='ult_fecha' type='text' id='ult_fecha' size='25' value='$ahora' readonly />
	</td>";
	?>
  </tr>

</table>
  <!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
  <center>
    <input name="guardar" type="submit" id="guardar" value="Guardar Registro"/>
    <input name="cancelar" type="button" id="cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'mproyecto.php');" />
  </center><br />
  <!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
</form>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>

<SCRIPT LANGUAGE="JavaScript">
function verificarproyecto(formulario) {

			//VALIDACION codigo proyecto
		   if (formulario.codproyecto.value.length <1) {
	  		 alert("Escriba mas de un digito en el campo \"Proyecto\".");
	   		 formulario.codproyecto.focus();
	      return (false);
	      }
          var checkOK ="0123456789";
	      var checkStr = formulario.codproyecto.value;
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
	         alert("Escriba sólo números en el campo \"Proyecto\"."); 
	         formulario.codproyecto.focus(); 
	         return (false); 
	       } 

			//VALIDACION DESCRIPCION
		   if (formulario.descripcion.value.length <2) {
	  		 alert("Escriba mas de dos letras en el campo \"Descripción\".");
	   		 formulario.descripcion.focus();
	      return (false);
	      }
          var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ"  + " ._/";
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

	        //validacion sub-programa
			if(formulario.selectSubprog.value < 0) {
			  alert("Porfavor, seleccione una opcion el campo \"Actvidad\".");
			  return (false);
			}
		   
	return (true); 
	} 
</SCRIPT>
</body>
</html>

