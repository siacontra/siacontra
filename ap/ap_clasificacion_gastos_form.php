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
	$sql = "SELECT cg.* FROM ap_clasificaciongastos cg WHERE cg.CodClasificacion = '".$registro."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	//	consulto las clasificaciones de gastos asociadas
	$sql = "SELECT * FROM ap_conceptoclasificaciongastos WHERE CodClasificacion = '".$registro."' ORDER BY CodConceptoGasto";
	$query_concepto = mysql_query($sql) or die($sql.mysql_error());
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_ap.js"></script>
</head>

<body onload="document.getElementById('codigo').focus();">
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Clasificaci&oacute;n de Gastos | <?=$titulo?></td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="<?=$onclick?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="ap_clasificacion_gastos.php" method="POST" onsubmit="return verificarClasificacionGasto(this, '<?=$accion?>');">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<div style="width:500px" class="divFormCaption">Datos del Registro</div>
<table width="500" class="tblForm">
	<tr>
		<td class="tagForm" width="125">Clasificaci&oacute;n:</td>
		<td><input type="text" id="codigo" style="width:50px;" maxlength="2" value="<?=$field['CodClasificacion']?>" <?=$d_codigo?> />*</td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input type="text" id="descripcion" style="width:300px;" maxlength="50" value="<?=($field['Descripcion'])?>" <?=$d_ver?> />*</td>
	</tr>
	<tr>
        <td class="tagForm">Aplicaci&oacute;n:</td>
        <td>
            <select id="aplicacion" style="width:150px;" <?=$d_ver?>>
                <?=loadSelectValores("APLICACION-GASTO", $field['Aplicacion'], 0);?>
            </select>*
        </td>
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
<div style="width:500px" class="divMsj">Campos Obligatorios *</div>
</form>

<div style="width:500px" class="divFormCaption">Conceptos V&aacute;lidos</div>
<form name="frmdetalles" id="frmdetalles">
<input type="hidden" id="seldetalle" />
<table width="500" class="tblBotones">
    <tr>
        <td align="right">
            <input type="button" class="btLista" value="Insertar" onclick="insertarConceptoGasto();" />
            <input type="button" class="btLista" value="Quitar" onclick="quitarConceptoGasto(document.getElementById('seldetalle').value);" />
        </td>
    </tr>
</table>
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:500px; height:125px;">
<table width="100%" class="tblLista">
    <tr class="trListaHead">
        <th scope="col" width="30">#</th>
        <th scope="col">Concepto</th>
    </tr>
    
    <tbody id="listaDetalles">
    <?php
	if ($accion == "ACTUALIZAR" || $accion == "VER") {
		while ($field_concepto = mysql_fetch_array($query_concepto)) {
			$nrodetalles++;
			?>
			<tr class="trListaBody" onclick="mClk(this, 'seldetalle');" id="<?=$nrodetalles?>">
				<td align="center"><?=$nrodetalles?></td>
				<td align="center">
					<select name="concepto" style="width:99%">
						<?=loadSelect("ap_conceptogastos", "CodConceptoGasto", "Descripcion", $field_clasificacion['CodConceptoGasto'], 0);?>
					</select>
				</td>
			</tr>
			<?
		}
	}
	?>
    </tbody>
</table>
</div></td></tr></table>
<input type="hidden" id="nrodetalles" value="<?=$nrodetalles?>" />
<input type="hidden" id="cantdetalles" value="<?=$nrodetalles?>" />
</form>
</body>
</html>
