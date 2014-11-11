<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro Proyecto | Actualizaci&oacute;n</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
$sql="SELECT * FROM pv_proyecto1 WHERE id_proyecto='".$_POST['registro']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if($rows!=0) {
  $field=mysql_fetch_array($query);
  //////   PRUEBA  //////    //////    //////  //////    //////
  echo "
  <form name='frmentrada' action='mproyecto.php?accion=editarProyecto' method='POST' onsubmit='return verificarProyecto2(this, \"EDITAR\");'>
  <input type='hidden' name='filtro' id='filtro' value='".$_GET['filtro']."'>
  <input type='hidden' name='idproyecto' id='idproyecto' value='".$field['id_proyecto']."'>
  <div style='width:700px' class='divFormCaption'>Datos del Proyecto</div>
  <table width='700' class='tblForm'>
  <tr>
	<td class='tagForm'>Proyecto:</td>
	<td><input name='codproyecto' type='text' id='codproyecto' size='3' maxlength='2' value='".$field['cod_proyecto']."' readonly /></td>
  </tr>
  <tr>
	<td class='tagForm'>Descripci&oacute;n:</td>
	<td><input name='descripcion' type='text' id='descripcion' size='60' maxlength='60' value='".htmlentities($field['descp_proyecto'])."'/></td>
  </tr>
  <tr>";$sql2=mysql_query("SELECT descp_subprog,cod_subprog,id_sub FROM pv_subprog1 WHERE id_sub='".$field['id_sub']."'");
	    $rows2=mysql_num_rows($sql2);
	    if($rows2!=0){
          $field2=mysql_fetch_array($sql2);
		}
	    echo"<td class='tagForm'>Sub-Programa:</td>
	         <td><select name='selectSubprograma'>
	             <option value='$field2[id_sub]'>$field2[cod_subprog]-$field2[descp_subprog]</option>";
	             $sql3="SELECT * FROM pv_subprog1 WHERE 1 ORDER BY id_sub";
	             $rs=mysql_query($sql3);
	             while($reg=mysql_fetch_assoc($rs)){
				  $id=$reg['id_sub'];//  Id de Subprograma
	              $cs=$reg['cod_subprog'];// Codigo de Subprograma
	              $cp=$reg['descp_subprog'];// Descripcion de Subprograma
				  if($field2[id_sub]!=$id){
				    echo "<option value=$id>$cs-$cp</option>";
				  }
	            }
       echo"</select>*</td>
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
	<input name='bt_cancelar' type='button' id='bt_cancelar' value='Cancelar' onclick='cargarPagina(this.form, \"mproyecto.php\");' />
	</center><br />
	</form>
	<div style='width:600px' class='divMsj'>Campos Obligatorios *</div>";
}
?>
<SCRIPT LANGUAGE="JavaScript">
function verificarProyecto2(formulario) {
		   //VALIDACION DESCRIPCION
		   if (formulario.descripcion.value.length <2) {
	  		 alert("Escriba los datos correctos en el campo \"Descripción\".");
	   		 formulario.descripcion.focus();
	      return (false);
	      }
          var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ" + " ._/" + "0123456789";
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
	return (true); 
	} 
</SCRIPT>
<? include "gmsector.php";?>
</body>
</html>

