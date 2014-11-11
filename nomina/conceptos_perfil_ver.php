<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");

include("fphp_nomina.php");
connect();
//	---------------------------------------
$sql = "SELECT * FROM pr_conceptoperfil WHERE CodPerfilConcepto = '".$registro."'";
$query = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
//	---------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_nomina.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Perfil de Conceptos | Ver Registro</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />


<div style="width:750px" class="divFormCaption">Datos del Concepto</div>
<table width="750" class="tblForm">
    <tr>
        <td class="tagForm">Concepto:</td>
        <td colspan="3"><input type="text" name="codigo" id="codigo" size="10" value="<?=$field['CodPerfilConcepto']?>" disabled="disabled" /></td>
    </tr>
    <tr>
        <td class="tagForm">Descripci&oacute;n:</td>
        <td colspan="3"><input name="descripcion" type="text" id="descripcion" size="60" maxlength="50" value="<?=($field['Descripcion'])?>" disabled="disabled" />*</td>
    </tr>
    <tr>
        <td class="tagForm">Estado:</td>
        <td colspan="3">
        	<? if ($field['Estado'] = "A") $flagactivo = "checked"; else $flaginactivo = "checked"; ?>
            <input name="status" id="activo" type="radio" value="A" <?=$flagactivo?> disabled="disabled" /> Activo
            <input name="status" id="inactivo" type="radio" value="I" <?=$flaginactivo?> disabled="disabled" /> Inactivo
        </td>
    </tr>
    <tr>
        <td class="tagForm">&Uacute;ltima Modif.:</td>
        <td colspan="3">
            <input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$field['UltimoUsuario']?>" disabled="disabled" />
            <input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field['UltimaFecha']?>" disabled="disabled" />
        </td>
    </tr>
</table>
<center> 
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cerrar Ventana" onclick="javascript:window.close();" />
</center>
</body>
</html>
