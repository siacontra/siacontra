<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
extract($_GET);
extract($_POST);
$btPartida = "disabled";
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
list($codorganismo, $nroorden)=SPLIT( '[|]', $registro);
$sql = "SELECT 
			op.*,
			mp.NomCompleto AS NomProveedor,
			mp.Busqueda,
			mp.DocFiscal,
			p.DiasPago,
			o.MontoAdelanto,
			o.MontoPagoParcial,
			(o.MontoObligacion - o.MontoAdelanto - MontoPagoParcial) AS MontoPendiente
		FROM 
			ap_ordenpago op
			INNER JOIN ap_obligaciones o ON (op.CodProveedor = o.CodProveedor AND
											 op.CodTipoDocumento = o.CodTipoDocumento AND
											 op.NroDocumento = o.NroDocumento)
			INNER JOIN mastpersonas mp ON (op.CodProveedor = mp.CodPersona)
			INNER JOIN ap_tipodocumento td ON (op.CodTipoDocumento = td.CodTipoDocumento)
			LEFT JOIN mastproveedores p ON (mp.CodPersona = p.CodProveedor)
		WHERE 
			op.CodOrganismo = '".$codorganismo."' AND
			op.NroOrden = '".$nroorden."'";
$query_orden = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_orden) != 0) $field_orden = mysql_fetch_array($query_orden);
//	------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Agregar Transacci&oacute;n Bancaria</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="document.getElementById('frmentrada').submit();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="ap_transacciones_bancarias.php" method="POST" onsubmit="return verificarTransaccionesBancarias(this, 'INSERTAR');">
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
            	<?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $forganismo, 1)?>
            </select>
		</td>
		<td class="tagForm">Periodo:</td>
		<td><input type="text" id="periodo" value="<?=date("Y-m")?>" style="width:60px;" disabled="disabled" /></td>
	</tr>
    <tr>
		<td class="tagForm"># Transacci&oacute;n:</td>
		<td><input type="text" id="nrotransaccion" style="width:60px;" disabled="disabled" /></td>
		<td class="tagForm">Estado:</td>
		<td>
        	<input type="hidden" id="estado" value="PR" />
        	<input type="text" value="<?=printValores("ESTADO-TRANSACCION-BANCARIA", "PR")?>" style="width:60px;" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Fecha Transaccion:</td>
		<td><input type="text" id="ftransaccion" value="<?=date("d-m-Y")?>" style="width:60px;" onchange="setPeriodoFecha(this.value, 'periodo');" /></td>
	</tr>
    <tr>
		<td class="tagForm">Comentarios:</td>
		<td colspan="3"><textarea id="comentarios" style="width:90%; height:50px;"></textarea></td>
	</tr>
    <tr>
        <td class="tagForm">Preparado Por:</td>
        <td colspan="3">
            <input type="hidden" id="codpreparado" value="<?=$_SESSION["CODPERSONA_ACTUAL"]?>" />
            <input type="text" id="nompreparado" value="<?=($_SESSION["NOMBRE_USUARIO_ACTUAL"])?>" disabled="disabled" style="width:295px;" />
            <input type="text" id="fpreparado" value="<?=date("d-m-Y")?>" disabled="disabled" style="width:60px;" />
        </td>
    </tr>
    <tr>
        <td class="tagForm">&nbsp;</td>
        <td colspan="3">
        	<input type="checkbox" name="FlagPresupuesto" id="FlagPresupuesto" onclick="document.getElementById('btPartida').disabled=!this.checked;" />
            Afecta Presupuesto
        </td>
    </tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td colspan="3">
			<input name="ult_usuario" type="text" id="ult_usuario" size="35" value="<?=$field_obligacion['UltimoUsuario']?>" disabled="disabled" />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field_obligacion['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center> 
<input type="submit" value="Procesar" />
<input type="button" value="Cancelar" onclick="document.getElementById('frmentrada').submit();" />
</center>
</form>

<form name="frmdetalles" id="frmdetalles">
<input type="hidden" id="nrodetalles" value="0" />
<input type="hidden" id="cantdetalles" value="0" />
<input type="hidden" id="seldetalle" />
<table width="800" class="tblBotones">
    <tr>
        <td>
            <input type="button" class="btLista" value="Sel. C.Costo" onclick="abrirListado(document.getElementById('seldetalle').value, 'listado_centro_costos.php?ventana=listadoCentroCosto');" />
            <input type="button" class="btLista" value="Sel. Persona" onclick="abrirListado(document.getElementById('seldetalle').value, 'listado_personas.php?ventana=listadoPersonas&limit=0');" />
            <input type="button" class="btLista" value="Sel. Partida" onclick="abrirListado(document.getElementById('seldetalle').value, '../lib/listas/listado_clasificador_presupuestario.php?ventana=listadoPartidas&limit=0&filtrar=default');" <?=$btPartida?> id="btPartida" />
            <input type="button" style="width:100px;" value="Sel. Transacci&oacute;n" onclick="abrirListado(document.getElementById('seldetalle').value, 'listado_tipo_transacciones_bancarias.php?ventana=listadoTipoTransaccionBancaria&limit=0');" />
        </td>
        <td align="right">
            <input type="button" class="btLista" value="Insertar" onclick="insertarLinea(this, 'nrodetalles', 'cantdetalles', 'insertarLineaTransaccionBancaria', 'seldetalle', 'listaDetalles');" />
            <input type="button" class="btLista" value="Quitar" onclick="quitarLinea('seldetalle', 'listaDetalles');" />
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
    
    </tbody>
</table>
</div></td></tr></table>
</form>

</body>
</html>
