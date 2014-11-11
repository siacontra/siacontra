<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
extract($_GET);
extract($_POST);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_ap.js"></script>
<style type="text/css">
<!--
UNKNOWN {
        FONT-SIZE: small
}
#header {
        FONT-SIZE: 93%; BACKGROUND: url(bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal
}
#header UL {
        PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none
}
#header LI {
        PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px
}
#header A {
        PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none
}
#header A {
        FLOAT: none
}
#header A:hover {
        COLOR: #333
}
#header #current {
        BACKGROUND-IMAGE: url(left_on.gif)
}
#header #current A {
        BACKGROUND-IMAGE: url(right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333
}
-->
</style>
</head>

<body>
<?php
include("fphp_ap.php");
connect();
//	------------------------------
list($nrotransaccion, $secuencia, $estado)=SPLIT( '[|]', $registro);
$sql = "SELECT 
			bt.*,
			p.NomCompleto AS NomPreparadoPor,
			btt.Descripcion AS NomTipoTransaccion
		FROM 
			ap_bancotransaccion bt
			INNER JOIN mastpersonas p ON (bt.PreparadoPor = p.CodPersona)
			INNER JOIN ap_bancotipotransaccion btt ON (bt.CodTipoTransaccion = btt.CodTipoTransaccion)
		WHERE 
			bt.NroTransaccion = '".$nrotransaccion."' AND
			bt.Secuencia = '".$secuencia."'";

$query = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
//	------------------------------
if ($acc == "MODIFICAR") {
	$titulo = "Modificar Transacci&oacute;n Bancaria";
	$titulo_boton = "Modificar";
	$btTransaccion = "disabled";
	$btInsertar = "disabled";
	$btQuitar = "disabled";
	$display_actualizar = "display:none;";
	$display_desactualizar = "display:none;";
}
elseif ($acc == "VER") {
	$titulo = "Transacci&oacute;n Bancaria";
	$titulo_boton = "Desactualizar";
	$btCCosto = "disabled";
	$btPersona = "disabled";
	$btTransaccion = "disabled";
	$btInsertar = "disabled";
	$btQuitar = "disabled";
	$disabled = "disabled";
	$display_actualizar = "display:none;";
	$display_desactualizar = "display:none;";
	$btSubmit = "style='display:none;'";
}
elseif ($acc == "ACTUALIZAR") {
	$titulo = "Actualizar Transacci&oacute;n Bancaria";
	$titulo_boton = "Actualizar";
	$btCCosto = "disabled";
	$btPersona = "disabled";
	$btTransaccion = "disabled";
	$btInsertar = "disabled";
	$btQuitar = "disabled";
	$disabled = "disabled";
	$display_desactualizar = "display:none;";	
	if ($estado == "AP" || $estado == "CO") $btSubmit = "style='display:none;'";
	else $display_actualizar = "display:none;";
}
elseif ($acc == "DESACTUALIZAR") {
	$titulo = "Desactualizar Transacci&oacute;n Bancaria";
	$titulo_boton = "Desactualizar";
	$btCCosto = "disabled";
	$btPersona = "disabled";
	$btTransaccion = "disabled";
	$btInsertar = "disabled";
	$btQuitar = "disabled";
	$disabled = "disabled";
	$display_actualizar = "display:none;";
	if ($estado == "PR") $btSubmit = "style='display:none;'";
	else $display_desactualizar = "display:none;";
}
//	------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="document.getElementById('frmentrada').submit();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<center>
<div style="background-color: #FFFFB0; color: #BB0000; width: 800px; border: 1px solid #BB0000; text-align: center; vertical-align:middle; font-size:14px; font-weight:bold; <?=$display_actualizar?>">
	No se puede actualizar esta transacci&oacute;n porque ya esta en estado: <?=printValores("ESTADO-TRANSACCION-BANCARIA", $field['Estado'])?>
</div>

<div style="background-color: #FFFFB0; color: #BB0000; width: 800px; border: 1px solid #BB0000; text-align: center; vertical-align:middle; font-size:14px; font-weight:bold; <?=$display_desactualizar?>">
	No se puede desactualizar esta transacci&oacute;n porque se encuentra en estado: <?=printValores("ESTADO-TRANSACCION-BANCARIA", $field['Estado'])?>
</div>
</center><br />

<form name="frmentrada" id="frmentrada" action="ap_transacciones_bancarias.php" method="POST" onsubmit="return verificarTransaccionesBancarias(this, '<?=$acc?>');">
<input type="hidden" name="forganismo" id="forganismo" value="<?=$forganismo?>" />
<input type="hidden" name="fprocesod" id="fprocesod" value="<?=$fprocesod?>" />
<input type="hidden" name="fprocesoh" id="fprocesoh" value="<?=$fprocesoh?>" />
<input type="hidden" name="fttransaccion" id="fttransaccion" value="<?=$fttransaccion?>" />
<input type="hidden" name="ftdoc" id="ftdoc" value="<?=$ftdoc?>" />
<input type="hidden" name="fperiodo" id="fperiodo" value="<?=$fperiodo?>" />
<input type="hidden" name="fbanco" id="fbanco" value="<?=$fbanco?>" />
<input type="hidden" name="festado" id="festado" value="<?=$festado?>" />
<input type="hidden" name="fctabancaria" id="fctabancaria" value="<?=$fctabancaria?>" />
<input type="hidden" name="accion" id="accion" value="<?=$accion?>" />
<input type="hidden" id="secuencia" value="<?=$secuencia?>" />

<div style="width:800px" class="divFormCaption">Informaci&oacute;n General</div>
<table width="800" class="tblForm">
	<tr>
		<td class="tagForm" width="150">Organismo:</td>
		<td>
        	<select id="organismo" style="width:300px;" disabled="disabled">
            	<?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $field['CodOrganismo'], 1)?>
            </select>
		</td>
		<td class="tagForm">Periodo:</td>
		<td><input type="text" id="periodo" value="<?=$field['PeriodoContable']?>" style="width:60px;" disabled="disabled" /></td>
	</tr>
    <tr>
		<td class="tagForm"># Transacci&oacute;n:</td>
		<td><input type="text" id="nrotransaccion" value="<?=$field['NroTransaccion']?>" style="width:60px;" disabled="disabled" /></td>
		<td class="tagForm">Estado:</td>
		<td>
        	<input type="hidden" id="estado" value="<?=$field['']?>" />
        	<input type="text" value="<?=printValores("ESTADO-TRANSACCION-BANCARIA", $field['Estado'])?>" style="width:60px;" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Fecha Transaccion:</td>
		<td><input type="text" id="ftransaccion" value="<?=formatFechaDMA($field['FechaTransaccion'])?>" style="width:60px;" <?=$disabled?> /></td>
	</tr>
    <tr>
		<td class="tagForm">Comentarios:</td>
		<td colspan="3"><textarea id="comentarios" style="width:90%; height:50px;" <?=$disabled?>><?=($field['Comentarios'])?></textarea></td>
	</tr>
    <tr>
        <td class="tagForm">Preparado Por:</td>
        <td colspan="3">
            <input type="hidden" id="codpreparado" value="<?=$field['PreparadoPor']?>" />
            <input type="text" id="nompreparado" value="<?=($field['NomPreparadoPor'])?>" disabled="disabled" style="width:295px;" />
            <input type="text" id="fpreparado" value="<?=formatFechaDMA($field['FechaPreparacion'])?>" disabled="disabled" style="width:60px;" />
        </td>
    </tr>
    <tr>
        <td class="tagForm">&nbsp;</td>
        <td colspan="3">
        	<? if ($field['FlagPresupuesto'] == "S") $FlagPresupuesto = "checked"; ?>
        	<input type="checkbox" name="FlagPresupuesto" id="FlagPresupuesto" onclick="document.getElementById('btPartida').disabled=!this.checked;" <?=$FlagPresupuesto?> /> Afecta Presupuesto
        </td>
    </tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td colspan="3">
			<input name="ult_usuario" type="text" id="ult_usuario" size="35" value="<?=$field['UltimoUsuario']?>" disabled="disabled" />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center> 
<input type="submit" value="<?=$titulo_boton?>" <?=$btSubmit?> />
<input type="button" value="Cancelar" onclick="document.getElementById('frmentrada').submit();" />
</center>
</form>

<form name="frmdetalles" id="frmdetalles">
<input type="hidden" id="nrodetalles" value="1" />
<input type="hidden" id="cantdetalles" value="1" />
<input type="hidden" id="seldetalle" />
<table width="800" class="tblBotones">
    <tr>
        <td>
            <input type="button" class="btLista" value="Sel. C.Costo" onclick="abrirListado(document.getElementById('seldetalle').value, 'listado_centro_costos.php?ventana=listadoCentroCosto');" <?=$btCCosto?> />
            <input type="button" class="btLista" value="Sel. Persona" onclick="abrirListado(document.getElementById('seldetalle').value, 'listado_personas.php?ventana=listadoPersonas&limit=0');" <?=$btPersona?> />
            <input type="button" class="btLista" value="Sel. Partida" onclick="abrirListado(document.getElementById('seldetalle').value, 'listado_clasificador_presupuestario.php?ventana=listadoPartidas&limit=0&filtrar=default');" <?=$btPartida?> />
            <input type="button" style="width:100px;" value="Sel. Transacci&oacute;n" onclick="abrirListado(document.getElementById('seldetalle').value, 'listado_tipo_transacciones_bancarias.php?ventana=listadoTipoTransaccionBancaria&limit=0');" <?=$btTransaccion?> />
        </td>
        <td align="right">
            <input type="button" class="btLista" value="Insertar" onclick="insertarLinea(this, 'nrodetalles', 'cantdetalles', 'insertarLineaTransaccionBancaria', 'seldetalle', 'listaDetalles');" <?=$btInsertar?> />
            <input type="button" class="btLista" value="Quitar" onclick="quitarLinea('seldetalle', 'listaDetalles');" <?=$btQuitar?> />
        </td>
    </tr>
</table>
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:800px; height:200px;">
<table width="1350" class="tblLista">
    <tr class="trListaHead">
        <th scope="col" width="25">#</th>
        <th scope="col" width="50">Tipo</th>
        <th scope="col">Tipo de Transacci&oacute;n</th>
        <th scope="col" width="15">I/E</th>
        <th scope="col" width="150">Cta. Bancaria</th>
        <th scope="col" width="100">Monto</th>
        <th scope="col" width="200">Documento</th>
        <th scope="col" width="150">Doc. Referencia</th>
        <th scope="col" width="75">Persona</th>
        <th scope="col" width="75">C.Costo</th>
        <th scope="col" width="150">Partida</th>
    </tr>
    
    <tbody id="listaDetalles">
    <tr class="trListaBody" onclick="mClk(this, 'seldetalle');" id="<?=$secuencia?>">
        <td align="center">
            <?=$secuencia?>
            <input type="hidden" name="nrosecuencia" value="<?=$secuencia?>">
        </td>
        <td align="center"><input type="text" name="codtransaccion" id="codtransaccion<?=$secuencia?>" class="cell2" style="text-align:center;" value="<?=$field['CodTipoTransaccion']?>" readonly></td>
        <td align="center"><input type="text" name="nomtransaccion" id="nomtransaccion<?=$secuencia?>" class="cell2" value="<?=$field['NomTipoTransaccion']?>" readonly></td>
        <td align="center"><input type="text" name="tipotransaccion" id="tipotransaccion<?=$secuencia?>" class="cell2" style="text-align:center;" value="<?=$field['TipoTransaccion']?>" readonly></td>
        <td align="center">
			<? //echo $field['Secuencia']; ?>
            <select name="nrocuenta" class="cell2" <?=$disabled?>>
                <?=loadSelect("ap_ctabancaria", "NroCuenta", "NroCuenta", $field['NroCuenta'], 0);
               // =loadSelect("ap_ctabancaria", "NroCuenta", "NroCuenta", $field['NroCuenta'], 0);
               //=loadSelect("ap_ctabancaria", "NroCuenta", "NroCuenta", $field['NroCuenta'], $field['Secuencia']);
               // $field['Secuencia']
                ?>
            </select>
        </td>
        <td align="center">
        	<?php
			if ($field['TipoTransaccion'] == "E") $monto = $field['Monto'] * (-1); else $monto = $field['Monto'];
			?>
        	<input type="text" name="monto" id="monto<?=$secuencia?>" class="cell" style="text-align:right;" onBlur="numeroBlur(this); this.className='cell';" onFocus="numeroFocus(this); this.className='cellFocus';" value="<?=number_format($monto, 2, ',', '.')?>" <?=$disabled?>>
		</td>
        <td align="center">
            <select name="tipodocumento" class="cell2" <?=$disabled?>>
                <?=loadSelect("ap_tipodocumento", "CodTipoDocumento", "Descripcion", $field['CodTipoDocumento'], 0);?>
            </select>
        </td>
        <td align="center"><input type="text" name="referenciabanco" id="referenciabanco<?=$secuencia?>" maxlength="20" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" value="<?=$field['CodigoReferenciaBanco']?>" <?=$disabled?>></td>
        <td align="center"><input type="text" name="persona" id="persona<?=$secuencia?>" class="cell2" style="text-align:center;" value="<?=$field['CodProveedor']?>" readonly></td>
        <td align="center"><input type="text" name="ccosto" id="ccosto<?=$secuencia?>" class="cell2" style="text-align:center;" value="<?=$field['CodCentroCosto']?>" readonly></td>
    <td align="center">
    	<input type="text" name="cod_partida" id="cod_partida<?=$nrodetalles?>" class="cell2" style="text-align:center;" value="<?=$field['CodPartida']?>" readonly>
    </td>
	</tr>
    </tbody>
</table>
</div></td></tr></table>
</form>

</body>
</html>
