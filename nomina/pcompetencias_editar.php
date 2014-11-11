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
<?php
include("fphp.php");
connect();
$ahora=date("Y-m-d H:i:s");
//	------------------------
$sql="SELECT * FROM rh_evaluacionfactoresplantilla WHERE Plantilla='".$registro."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) $field=mysql_fetch_array($query);
//	------------------------
if ($field['Estado']=="A") $activo="checked"; else $inactivo="checked";
if ($field['FlagTipoEvaluacion']=="M") $multiple="checked"; else $unico="checked";
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Plantilla de Competencias | Actualizaci&oacute;n</td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="pcompetencias.php" method="POST" onsubmit="return verificarPCompetencia(this, 'ACTUALIZAR');">
<?php echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />"; ?>

<div style="width:700px" class="divFormCaption">Datos de la Plantilla</div>
<table width="700" class="tblForm">
	<tr>
		<td class="tagForm">Plantilla:</td>
		<td><input name="codigo" type="text" id="codigo" size="5" value="<?=$field['Plantilla']?>" readonly /></td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input name="descripcion" type="text" id="descripcion" size="75" maxlength="100" value="<?=$field['Descripcion']?>" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Tipo de Evaluaci&oacute;n:</td>
		<td>
			<input type="radio" name="ftipo" id="M" value="M" <?=$multiple?> /> M&uacute;ltiple 
			<input type="radio" name="ftipo" id="U" value="U" <?=$unico?> /> &Uacute;nico &nbsp;&nbsp;&nbsp;&nbsp;
			<select name="tipo" id="tipo" class="selectMed">
				<option value=""></option>
				<?=getTEvaluaciones($field['TipoEvaluacion'], 0);?>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
			<input id="A" name="status" type="radio" value="A" <?=$activo?> /> Activo
			<input id="I" name="status" type="radio" value="I" <?=$inactivo?> /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$field['UltimoUsuario']?>" readonly />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field['UltimaFecha']?>" readonly />
		</td>
	</tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'pcompetencias.php');" />
</center>
</form>

<div style="width:700px" class="divMsj">Campos Obligatorios *</div>

<br /><div class="divDivision">Lista de Competencias</div><br />

<center><iframe name="iCompetencias" id="iCompetencias" class="frameTab" style="height:500px; width:900px;" src="pcompetencias_plantilla.php?accion=EDITAR&plantilla=<?=$field['Plantilla']?>"></iframe></center>
</body>
</html>
