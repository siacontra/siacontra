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
//	---------------------------------
$sql="SELECT u.*, m.NomCompleto FROM usuarios u INNER JOIN mastpersonas m ON (u.CodPersona=m.CodPersona) WHERE Usuario='$registro'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) $field=mysql_fetch_array($query);
list($annio, $mes, $dia)=SPLIT( '[/.-]', $field['FechaExpirar']); if ($annio=="0000") $fexpira=""; else $fexpira="$dia-$mes-$annio"; 
if ($field['FlagFechaExpirar']=="S") $checked="checked"; else $disabled="disabled";
if ($field['Estado']=="A") $activo="checked"; else $inactivo="checked";
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Usuarios | Actualizaci&oacute;n</td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="usuarios.php" method="POST" onsubmit="return verificarUsuario(this, 'ACTUALIZAR');">
<?php echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />"; ?>

<div style="width:650px" class="divFormCaption">Datos del Usuario</div>
<table width="650" class="tblForm">
    <tr>
        <td class="tagForm">Empleado:</td>
        <td>
            <input name="codempleado" type="hidden" id="codempleado" size="10" value="<?=$field['CodPersona']?>"  />
            <input name="nomempleado" type="text" id="nomempleado" size="75" value="<?=$field['NomCompleto']?>" readonly />
        </td>
    </tr>
    <tr>
        <td class="tagForm">Usuario:</td>
        <td><input name="usuario" type="text" id="usuario" size="35" maxlength="20" readonly="readonly" value="<?=$field['Usuario']?>" /></td>
    </tr>
    <tr>
        <td class="tagForm">Contrase&ntilde;a:</td>
        <td><input name="clave" type="password" id="clave" size="35" maxlength="20" value="<?=$field['Clave']?>" />*</td>
    </tr>
    <tr>
        <td class="tagForm">Confirmar Contrase&ntilde;a:</td>
        <td><input name="confirmar" type="password" id="confirmar" size="35" maxlength="20" value="<?=$field['Clave']?>" />*</td>
    </tr>
    <tr>
    	<td class="tagForm"><input type="checkbox" name="flag" id="flag" value="S" <?=$checked?> onclick="forzarFechaExpira(this.checked);" /></td>
        <td>Forzar Expiración de la Contrase&ntilde;a</td>
	</tr>
    <tr>
        <td class="tagForm">Fecha Expiraci&oacute;n:</td>
        <td><input name="fexpira" type="text" id="fexpira" size="15" maxlength="10" <?=$disabled?> value="<?=$fexpira?>" />*<em>(dd-mm-yyyy)</em></td>
    </tr>
    <tr>
        <td class="tagForm">Estado:</td>
        <td>
            <input name="status" type="radio" value="A" <?=$activo?> /> Activo
            <input name="status" type="radio" value="I" <?=$inactivo?> /> Inactivo
        </td>
    </tr>
    <tr>
        <td class="tagForm">&Uacute;ltima Modif.:</td>
        <td>
            <input name="ult_usuario" type="text" id="ult_usuario" size="30" readonly />
            <input name="ult_fecha" type="text" id="ult_fecha" size="25" readonly />
        </td>
    </tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onclick="cargarPagina(this.form, 'usuarios.php');" />
</center><br />
</form>

<div style="width:650px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
