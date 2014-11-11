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
  <td class="titulo">Maestro de Actividad | Nuevo Registro</td>
  <td align="right">
   <a class="cerrar" href="framemain.php">[cerrar]</a>
  </td>
 </tr>
</table>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<hr width="100%" color="#333333" />
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<form name="fentrada" id="fentrada" action="mactividad.php?accion=guardarActividad" method="POST" onsubmit="return  verificarActividad(this, 'GUARDAR');">
<?php echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />"; ?>

<div style="width:700px" class="divFormCaption">Datos</div>
<table width="700" class="tblForm">
  <tr>
    <td class="tagForm">Actividad:</td>
    <td><input name="codactividad" type="text" id="codactividad" size="3" maxlength="2"  readonly/></td>
  </tr>
  <tr>
    <td class="tagForm">Descripci&oacute;n:</td>
    <td><input name="descripcion" type="text" id="descripcion" size="60" maxlength="100" />*</td>
  </tr>
  <tr>
    <td class="tagForm">Proyecto:</td>
	<td><select name="selectProyecto">
        <option value="-1">- -</option>

<?php 
    // segundo bloque php //* Conectamos a los datos *//
	include "conexion_.php";
	$sql="SELECT * FROM pv_proyecto1 ORDER BY id_proyecto";
	$rs=mysql_query($sql);
	while($reg=mysql_fetch_assoc($rs)){
	 $id=$reg['id_proyecto'];//   ID DEL PROYECTO
	 $codproy=$reg['cod_proyecto'];// CODIGO PROYECTO
	 $dproy=$reg['descp_proyecto'];// DESCRIPCION DEL PROYECTO
	 if($id==$codproy){
	   echo "<option value=$id>$codproy-$dproy</option>";
	 }else{
	   echo "<option value=$id>$codproy-$dproy</option>";
	 }
	}
    ?></select>*</td>
  </tr>
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
    <input name="cancelar" type="button" id="cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'mactividad.php');" />
  </center><br />
  <!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
</form>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>

<SCRIPT LANGUAGE="JavaScript">
function verificarActividad(formulario) {
	
		   //VALIDACION DESCRIPCION
		   if (formulario.descripcion.value.length <5) {
	  		 alert("Escriba los datos correctos en el campo \"Descripción\".");
	   		 formulario.descripcion.focus();
	      return (false);
	      }
          var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZ" + "abcdefghijklmnopqrstuvwxyz" + " ._" + "0123456789";
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
	         alert("Escriba slo letras en el campo \"Descripción\"."); 
	         formulario.descripcion.focus(); 
	         return (false); 
	       } 
		   //VALIDACION COD_PROYECTO
		   if (formulario.sactividad.value.length <1) {
	  		 alert("Seleccione el Proyecto a Asociar \"Código Proyecto\".");
	   		 formulario.sactividad.focus();
	      return (false);
	      }
          var checkOK ="0123456789";
	      var checkStr = formulario.sactividad.value;
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
	         alert("Seleccione el Proyecto a Asociar \"Código Proyecto\"."); 
	         formulario.sactividad.focus(); 
	         return (false); 
	       }
	return (true); 
	} 
</SCRIPT>
</body>
</html>

