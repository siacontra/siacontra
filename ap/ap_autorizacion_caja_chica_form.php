<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	--------------------------
extract($_POST);
extract($_GET);
//	--------------------------
include("fphp_ap.php");
connect();
//	--------------------------
if ($accion == "INSERTAR") {
	$titulo = "Nuevo Registro";
	$label_submit = "Guardar Registro";
	$style_submit = "";
	$onclick = "document.getElementById('frmentrada').submit();";
	$codorganismo = $_SESSION['ORGANISMO_ACTUAL'];
}
elseif ($accion == "ACTUALIZAR" || $accion == "VER") {
	if ($accion == "ACTUALIZAR") {
		$titulo = "Actualizar Registro";
		$label_submit = "Actualizar Registro";
		$style_submit = "";
		$d_codigo = "disabled";
		$onclick = "document.getElementById('frmentrada').submit();";
	}
	elseif ($accion == "VER") {
		$titulo = "Ver Registro";
		$label_submit = "";
		$style_submit = "display:none;";
		$d_codigo = "disabled";
		$d_ver = "disabled";
		$onclick = "window.close();";
	}
	//	consulto los datos del registro
	list($codorganismo, $codempleado) = split("[.]", $registro);
	$sql = "SELECT
				cca.*,
				p.NomCompleto
			FROM
				ap_cajachicaautorizacion cca
				INNER JOIN mastempleado e ON (cca.CodEmpleado = e.CodEmpleado)
				INNER JOIN mastpersonas p ON (e.CodPersona = p.CodPersona)
			WHERE
				cca.CodEmpleado = '".$codempleado."' AND
				cca.CodOrganismo = '".$codorganismo."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_ap.js"></script>
</head>

<body onload="document.getElementById('codorganismo').focus();">
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Autorizaci&oacute;n de Caja Chica | <?=$titulo?></td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="<?=$onclick?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="ap_autorizacion_caja_chica.php" method="POST" onsubmit="return verificarAutorizacionCajaChica(this, '<?=$accion?>');">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<div style="width:700px" class="divFormCaption">Datos del Registro</div>
<table width="700" class="tblForm">
	<tr>
        <td class="tagForm" width="125">Organismo:</td>
        <td>
            <select id="codorganismo" style="width:300px;" onchange="limpiar_empleado();" <?=$d_codigo?>>
                <?=getOrganismos($codorganismo, 0);?>
            </select>*
        </td>
    </tr>
    <tr>
        <td class="tagForm">Empleado:</td>
        <td>
            <input type="hidden" id="codpersona" readonly="readonly" />
            <input type="text" id="codempleado" style=" width:50px;" value="<?=$codempleado?>" disabled="disabled" />
            <input type="text" id="nomempleado" style=" width:233px;" value="<?=($field['NomCompleto'])?>" disabled="disabled" />
            <input type="button" value="..." onclick="window.open('listado_empleados.php?forganismo=<?=$codorganismo?>&fdependencia=<?=$_SESSION["DEPENDENCIA_ACTUAL"]?>&fedoreg=A&fsittra=A&limit=0&cod=codempleado&nom=nomempleado&id=codpersona&ventana=autorizacion_empleados', 'wLista', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=650, width=1000, left=0, top=0, resizable=yes');" />*
        </td>
    </tr>
	<tr>
		<td class="tagForm" width="125">Monto:</td>
		<td><input type="text" id="monto" style="width:100px; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" value="<?=number_format($field['Monto'], 2, ',', '.')?>" <?=$d_ver?> /></td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
        	<?php if ($field['Estado'] == "A" || $accion == "INSERTAR") $flagactivo = "checked"; else $flaginactivo = "checked"; ?>
			<input id="activo" name="estado" type="radio" value="A" <?=$flagactivo?> <?=$d_ver?> /> Activo &nbsp;&nbsp;
			<input id="inactivo" name="estado" type="radio" value="I" <?=$flaginactivo?> <?=$d_ver?> /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input name="ult_usuario" type="text" id="ult_usuario" value="<?=$field['UltimoUsuario']?>" size="30" disabled="disabled" />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center> 
<input type="submit" value="<?=$label_submit?>" style=" <?=$style_submit?>" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="<?=$onclick?>" />
</center>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</form>
</body>
</html>
