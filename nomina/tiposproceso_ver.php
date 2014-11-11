<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_nomina.js"></script>
</head>

<body>
<?php
include("fphp_nomina.php");
connect();
$sql="SELECT * FROM pr_tipoproceso WHERE CodTipoProceso='".($registro)."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) $field=mysql_fetch_array($query);
if ($field['FlagAdelanto']=="S") $flag="checked";
if ($field['Estado']=="A") $activo="checked"; else $inactivo="checked";
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Tipos de Proceso | Ver Registro</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<div style="width:650px" class="divFormCaption">Datos del Tipo de Proceso</div>
<table width="650" class="tblForm">
    <tr>
        <td class="tagForm">Tipo:</td>
        <td><input name="codigo" type="text" id="codigo" size="6" maxlength="3" value="<?=$field['CodTipoProceso']?>" readonly="readonly" /></td>
    </tr>
    <tr>
        <td class="tagForm">Descripci&oacute;n:</td>
        <td><input name="descripcion" type="text" id="descripcion" size="75" maxlength="50" value="<?=$field['Descripcion']?>" readonly="readonly" /></td>
    </tr>
    <tr>
        <td class="tagForm">El Proceso es un Adelanto</td>
        <td><input name="flag" type="checkbox" id="flag" value="S" <?=$flag?> disabled="disabled" /></td>
    </tr>
    <tr>
        <td class="tagForm">Estado:</td>
        <td>
            <input name="status" id="activo" type="radio" value="A" <?=$activo?> disabled="disabled" /> Activo
            <input name="status" id="inactivo" type="radio" value="I" <?=$inactivo?> disabled="disabled" /> Inactivo
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
</body>
</html>
