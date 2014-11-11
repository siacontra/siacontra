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
		<td class="titulo">Maestro Programa | Actualizaci&oacute;n</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333"/>
<?php
include("fphp.php");
connect();
$sql="SELECT * FROM pv_programa1 WHERE id_programa='".$_POST['registro']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) {
   $field=mysql_fetch_array($query);
   /////   PRUEBA  //////    //////    //////  //////    ////// 
   echo "<form name='frmentrada' action='mprograma.php?accion=editarPrograma' method='POST' onsubmit='return verificarPrograma(this, \"EDITAR\");'>
   <input type='hidden' name='filtro' id='filtro' value='".$_GET['filtro']."'>
   <input type='hidden' name='idprograma' id='idprograma' value='".$field['id_programa']."'>
   <div style='width:700px' class='divFormCaption'>Datos del Programa</div>
   <table width='700' class='tblForm'>
	<tr>
	 <td class='tagForm'>Programa:</td>
	 <td><input name='codprograma' type='text' id='codprograma' size='3' maxlength='2' value='".$field['cod_programa']."' readonly /></td>
	</tr>
	</tr>
	<tr>
	  <td class='tagForm'>Descripci&oacute;n:</td>
	  <td><input name='descripcionp' type='text' id='descripcionp' size='60' maxlength='60' value='".htmlentities($field['descp_programa'])."'/></td>
	</tr>
	<tr>";
	      $sql2=mysql_query("SELECT descripcion,cod_sector FROM pv_sector WHERE cod_sector='".$field['cod_sector']."'");
	      $rows2=mysql_num_rows($sql2);
	      if($rows2!=0){
            $field2=mysql_fetch_array($sql2);
		  }
	  echo"<td class='tagForm'>Sector:</td>
	       <td><select name='descripcionsector'>
	           <option value='$field2[cod_sector]'>$field2[cod_sector]-$field2[descripcion]</option>";
	             $sql3="SELECT * FROM pv_sector WHERE 1 ORDER BY cod_sector";
	             $rs=mysql_query($sql3);
	             while($reg=mysql_fetch_assoc($rs)){
	              $cs=$reg['cod_sector'];// Codigo de Sector
	              $cp=$reg['descripcion'];// Codigo Programa
			      if($field2[cod_sector]!=$cs){
	                if(($cod_sector==$cs)){
	                   echo "<option value=$cs>$cs-$cp</option>";
	                }else{
	                   echo "<option value=$cs>$cs-$cp</option>";
	                }
			      }
	            }
        echo" </select>*</td>
  </tr>
  <tr><td><input name='codsect' id='codsect' type='hidden' size='60' value='".$field2['cod_sector']."' readonly /></td></tr>
  <tr>
	  <td class='tagForm'>Estado:</td>
	  <td>";
		   if ($field['Estado']=="A") echo "<input name='status' type='radio' value='A' checked /> Activo"; 
				else echo "<input name='status' type='radio' value='A' /> Activo";
				if ($field['Estado']=="I") echo "<input name='status' type='radio' value='I' checked/> Inactivo"; 
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
   </table>
	<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />
	<center> 
	<input type='submit' value='Guardar Registro' name='editar' id='editar' />
	<input name='bt_cancelar' type='button' id='bt_cancelar' value='Cancelar' onclick='cargarPagina(this.form, \"mprograma.php\");' />
	</center><br />
	</form>
	<div style='width:600px' class='divMsj'>Campos Obligatorios *</div>";
}
?>
<SCRIPT LANGUAGE="JavaScript">
function verificarPrograma(formulario) {
		   //VALIDACION DESCRIPCION
		   if (formulario.descripcionsector.value.length <2) {
	  		 alert("Elija el sector al cual asociar el Programa");
	   		 formulario.descripcionsector.focus();
	      return (false);
	      }
          var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZ" + "abcdefghijklmnopqrstuvwxyz" + " ._/" + "0123456789";
	      var checkStr = formulario.descripcionsector.value;
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
	         alert("Elija el sector al cual asociar el Programa"); 
	         formulario.descripcionsector.focus(); 
	         return (false); 
	       } 
	return (true); 
	} 
</SCRIPT>
</body>
</html>