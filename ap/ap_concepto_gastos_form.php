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
	$sql = "SELECT * FROM ap_conceptogastos WHERE CodConceptoGasto = '".$registro."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	//	consulto las clasificaciones de gastos asociadas
	$sql = "SELECT * FROM ap_conceptoclasificaciongastos WHERE CodConceptoGasto = '".$registro."' ORDER BY CodClasificacion";
	$query_clasificacion = mysql_query($sql) or die($sql.mysql_error());
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_ap.js"></script>
</head>

<body onload="document.getElementById('descripcion').focus();">
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Concepto de Gastos | <?=$titulo?></td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="<?=$onclick?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="ap_concepto_gastos.php" method="POST" onsubmit="return verificarConceptoGasto(this, '<?=$accion?>');">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<div style="width:500px" class="divFormCaption">Datos del Registro</div>
<table width="500" class="tblForm">
	<tr>
		<td class="tagForm" width="125">Concepto:</td>
		<td><input type="text" id="codigo" style="width:50px;" maxlength="4" value="<?=$field['CodConceptoGasto']?>" <?=$d_codigo?> /></td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input type="text" id="descripcion" style="width:300px;" maxlength="50" value="<?=($field['Descripcion'])?>" <?=$d_ver?> />*</td>
	</tr>
	<tr>
        <td class="tagForm">Grupo de Concepto:</td>
        <td>
            <select id="grupo" style="width:306px;" <?=$d_ver?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("ap_conceptogastogrupo", "CodGastoGrupo", "Descripcion", $field['CodGastoGrupo'], 10);?>
            </select>*
        </td>
    </tr>
    <tr>
        <td class="tagForm">Partida:</td>
        <td>
        	<input type="text" id="codpartida" value="<?=$field['CodPartida']?>" style="width:100px;" disabled="disabled" />
			<input type="hidden" id="nompartida" />
			<input type="button" value="..." onclick="cargarVentana(this.form, 'lista_clasificador_presupuestario.php?cod=codpartida&nom=nompartida&destino=selPartidaCuenta', 'height=500, width=900, left=50, top=50, resizable=yes');" <?=$d_ver?> />*
        </td>
    </tr>
    <tr>
        <td class="tagForm">Cuenta:</td>
        <td>
        	<input type="text" id="codcuenta" value="<?=$field['CodCuenta']?>" style="width:100px;" disabled="disabled" />
			<input type="hidden" id="nomcuenta" />
			<input type="button" value="..." onclick="cargarVentana(this.form, 'listado_cuentas_contables.php?cod=codcuenta&nom=nomcuenta', 'height=500, width=900, left=50, top=50, resizable=yes');" <?=$d_ver?> />*
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

<div style="width:500px" class="divFormCaption">Clasificaciones V&aacute;lidas</div>
<form name="frmdetalles" id="frmdetalles">
<input type="hidden" id="seldetalle" />
<table width="500" class="tblBotones">
    <tr>
        <td align="right">
            <input type="button" class="btLista" value="Insertar" onclick="insertarClasificacionGasto();" />
            <input type="button" class="btLista" value="Quitar" onclick="quitarClasificacionGasto(document.getElementById('seldetalle').value);" />
        </td>
    </tr>
</table>
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:500px; height:125px;">
<table width="100%" class="tblLista">
    <tr class="trListaHead">
        <th scope="col" width="30">#</th>
        <th scope="col">Clasificaci&oacute;n</th>
    </tr>
    
    <tbody id="listaDetalles">
    <?php
	if ($accion == "ACTUALIZAR" || $accion == "VER") {
		while ($field_clasificacion = mysql_fetch_array($query_clasificacion)) {
			$nrodetalles++;
			?>
			<tr class="trListaBody" onclick="mClk(this, 'seldetalle');" id="<?=$nrodetalles?>">
				<td align="center"><?=$nrodetalles?></td>
				<td align="center">
					<select name="clasificacion" style="width:99%">
						<?=loadSelect("ap_clasificaciongastos", "CodClasificacion", "Descripcion", $field_clasificacion['CodClasificacion'], 0);?>
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