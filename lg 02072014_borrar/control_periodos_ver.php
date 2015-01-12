<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_lg_2.js"></script>
<body>
<?php
include("fphp_lg.php");
connect();
//	---------------------------------
list($organismo, $periodo)=SPLIT( '[.]', $registro);
$sql = "SELECT * FROM lg_periodocontrol WHERE CodOrganismo = '".$organismo."' AND Periodo = '".$periodo."'";
$query = mysql_query($sql) or die($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
//	---------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Control de Periodos | Ver Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<div style="width:700px;" class="divFormCaption">Datos Generales</div>
<table width="700px" class="tblForm">
	<tr>
		<td class="tagForm" width="150">Organismo:</td>
		<td>
        	<select id="organismo" style="width:300px;" disabled="disabled">
				<?=getOrganismos($field['CodOrganismo'], 3);?>
			</select>
        </td>
	</tr>
	<tr>
		<td class="tagForm">Periodo:</td>
		<td><input type="text" id="periodo" size="15" maxlength="7" value="<?=$field['Periodo']?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">&nbsp;</td>
		<td>
        	<? if ($field['FlagTransaccion'] == "S") $flagtransaccion = "checked"; ?>
			<input type="checkbox" id="flagtransaccion" <?=$flagtransaccion?> disabled="disabled" /> Disponible para Transacci&oacute;n
		</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
        	<? if ($field['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked"; ?>
			<input id="activo" name="status" type="radio" value="A" <?=$flagactivo?> disabled="disabled" /> Activo
			<input id="inactivo" name="status" type="radio" value="I" <?=$flaginactivo?> disabled="disabled" /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$field['UltimoUsuario']?>" disabled="disabled" />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>

</body>
</html>
