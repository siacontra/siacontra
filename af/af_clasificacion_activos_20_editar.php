<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
</head>

<body onload="document.getElementById('descripcion').focus();">
<?php
include("af_fphp.php");
connect();
//	-----------------------------------
$sql = "SELECT * FROM af_clasificacionactivo20 WHERE CodClasificacion = '".$registro."'"; //echo $sql;
$query = mysql_query($sql)  or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Clasificaci&oacute;n de Activos - Publicaci&oacute;n 20 | Modificar Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'af_clasificacion_activos_20.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="af_clasificacion_activos_20.php" method="POST" onsubmit="return EditarClasificacionActivo20(this);">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<div style="width:700px" class="divFormCaption">Datos del Registro</div>
<table width="700" class="tblForm">
	
    <tr>
       <td class="tagForm">Nivel:</td>
       <td><select id="nivel" name="nivel">
             <?
             $snivel = "select Nivel from af_clasificacionactivo20 where CodClasificacion = '".$field['CodClasificacion']."'";
			 $qnivel = mysql_query($snivel) or die ($snivel.mysql_error());
			 $rnivel = mysql_num_rows($qnivel); 
			 for($i=0;$i<$rnivel;$i++){
				  $fnivel = mysql_fetch_array($qnivel);
			      echo"<option value='".$fnivel['Nivel']."' disabled selected>".$fnivel['Nivel']."</option>";
			 }
			 ?>
           </select></td>
    </tr>
    <tr>
		<td class="tagForm">C&oacute;digo:</td>
		<td><input name="codigo" type="text" id="codigo" size="15" maxlength="8" value="<?=$field['CodClasificacion']?>" disabled="disabled" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input name="descripcion" type="text" id="descripcion" maxlength="255" style="width:95%;" value="<?=htmlentities($field['Descripcion'])?>" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
        	<? if ($field['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked"; ?>
			<input id="activo" name="status" type="radio" value="A" <?=$flagactivo?> /> Activo
			<input id="inactivo" name="status" type="radio" value="I" <?=$flaginactivo?> /> Inactivo
		</td>
	</tr>
	<tr>
	<td class="tagForm">&Uacute;ltima Modif.:</td>
	<td>
		<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$field['UltimoUsuario']?>"  disabled="disabled" />
		<input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field['UltimaFecha']?>"  disabled="disabled" />
	</td>
	</tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'af_clasificacion_activos_20.php');" />
</center><br />
</form>

<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
