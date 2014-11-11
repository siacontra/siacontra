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
if ($acc == "AGREGAR") {
	$titulo = "Agregar Caja Chica";
	$label_submit = "Agregar";
	$style_submit = "";
	$d_codigo = "";
	$d_ver = "";
	$d_motivo = "disabled";
	$onclick = "document.getElementById('frmentrada').submit();";
	##
	$flagcajachica = "C";
	$periodo = date("Y");
	$codorganismo = $forganismo;
	$coddependencia = $_SESSION["DEPENDENCIA_ACTUAL"];
	$estado = "PR";
	$codbeneficiario = $_SESSION["CODPERSONA_ACTUAL"];
	$nombeneficiario = $_SESSION["NOMBRE_USUARIO_ACTUAL"];
	$codpagara = $_SESSION["CODPERSONA_ACTUAL"];
	$nompagara = $_SESSION["NOMBRE_USUARIO_ACTUAL"];
	$preparadopor = $_SESSION["CODPERSONA_ACTUAL"];
	$nompreparadopor = $_SESSION["NOMBRE_USUARIO_ACTUAL"];
	$fpreparadopor = date("d-m-Y");
	$aprobadopor = "";
	$nomaprobadopor = "";
	$faprobadopor = "";
	$codclasificacion = "CC";
	$codtipopago = "02";
}
elseif ($acc == "ACTUALIZAR" || $acc == "VER" || $acc == "APROBAR" || $acc == "ANULAR") {
	list($flagcajachica, $periodo, $nrocajachica) = split("[.]", $registro);
	//	consulto los datos del registro
	$sql = "SELECT
				cc.*,
				p1.NomCompleto AS NomBeneficiario,
				p2.NomCompleto AS NomPreparadoPor,
				p3.NomCompleto AS NomAprobadoPor
			FROM
				ap_cajachica cc
				INNER JOIN mastpersonas p1 ON (cc.CodBeneficiario = p1.CodPersona)
				INNER JOIN mastpersonas p2 ON (cc.PreparadoPor = p2.CodPersona)
				INNER JOIN mastpersonas p3 ON (cc.PreparadoPor = p3.CodPersona)
			WHERE
				cc.FlagCajaChica = '".$flagcajachica."' AND
				cc.Periodo = '".$periodo."' AND
				cc.NroCajaChica = '".$nrocajachica."'";

	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	##
	if ($acc == "ACTUALIZAR") {
		if ($field['Estado'] == "AP" || $field['Estado'] == "AN") {
			$label_submit = "";
			$style_submit = "display:none;";
			$d_codigo = "disabled";
			$d_ver = "disabled";
			$d_motivo = "disabled";
		} else {
			$label_submit = "Actualizar";
			$style_submit = "";
			$d_codigo = "disabled";
			$d_ver = "";
			$d_motivo = "disabled";
		}
		$titulo = "Modificar Caja Chica";
		$onclick = "document.getElementById('frmentrada').submit();";
	}
	elseif ($acc == "APROBAR" || $acc == "ANULAR") {
		if ($acc == "APROBAR") {
			$titulo = "Aprobar Caja Chica"; 
			$label_submit = "Aprobar"; 
			$d_motivo = "disabled";
			$aprobadopor = $_SESSION["CODPERSONA_ACTUAL"];
			$nomaprobadopor = $_SESSION["NOMBRE_USUARIO_ACTUAL"];
			$faprobadopor = date("d-m-Y");
		}
		elseif ($acc == "ANULAR") {
			if ($field['Estado'] == "AN") {
				$label_submit = "";
				$style_submit = "display:none;";
				$d_codigo = "disabled";
				$d_ver = "disabled";
				$d_motivo = "disabled";
			} else {
				$label_submit = "Anular"; 
				$d_motivo = "";
			}
			$titulo = "Anular Caja Chica";
			$aprobadopor = "";
			$nomaprobadopor = "";
		}
		$style_submit = "";
		$d_codigo = "disabled";
		$d_ver = "disabled";
		$onclick = "document.getElementById('frmentrada').submit();";
	}
	elseif ($acc == "VER") {
		$titulo = "Ver Caja Chica";
		$label_submit = "";
		$style_submit = "display:none;";
		$d_codigo = "disabled";
		$d_ver = "disabled";
		$d_motivo = "disabled";
		$onclick = "window.close();";
	}
	$periodo = $field['Periodo'];
	$codorganismo = $field['CodOrganismo'];
	$coddependencia = $field['CodDependencia'];
	$estado = $field['Estado'];
	$codbeneficiario = $field['CodBeneficiario'];
	$nombeneficiario = $field['NomBeneficiario'];
	$codpagara = $field['CodPersonaPagar'];
	$nompagara = $field['NomPersonaPagar'];
	$preparadopor = $field['PreparadoPor'];
	$nompreparadopor = $field['NomPreparadoPor'];
	$fpreparadopor = formatFechaDMA($field['FechaPreparacion']);
	if ($field['AprobadoPor'] != "") {
		$aprobadopor = $field['AprobadoPor'];
		$nomaprobadopor = $field['NomAprobadoPor'];
		$faprobadopor = formatFechaDMA($field['FechaAprobacion']);
	}
	$codclasificacion = $field['CodClasificacion'];
	$codtipopago = $field['CodTipoPago'];
}
//	obtengo el monto autorizado del beneficiario
$monto_autorizado = getMontoAutorizadoCajaChica($codorganismo, $codbeneficiario);
if ($monto_autorizado == 0) {
	$label_submit = "";
	$style_submit = "display:none;";
	$d_codigo = "disabled";
	$d_ver = "disabled";
	$d_motivo = "disabled";
	$divMsj = "display:block;";
} else $divMsj = "display:none;";
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
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="<?=$onclick?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<center><div id="divMsj" style="background-color: #FFFFB0; color: #BB0000; width: 1000px; height: 25px; border: 1px solid #BB0000; text-align: center; vertical-align:middle; font-size:14px; font-weight:bold; <?=$divMsj?>">
	El Beneficiario Actual no Tiene Monto Autorizado para Crear una Reposición de Caja Chica
</div></center>

<table width="1000" align="center">
    <tr>
        <td>
            <div id="header">
            <ul>
            <!-- CSS Tabs -->
            <li><a onclick="document.getElementById('tab1').style.display='block'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none';" href="#">Informaci&oacute;n General</a></li>
            <li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block'; document.getElementById('tab3').style.display='none';" href="#">Detalles</a></li>
            <li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='block';" href="#">Distribuci&oacute;n</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<div id="tab1" style="display:block;">
<form name="frmentrada" id="frmentrada" action="ap_caja_chica_listar.php" method="POST" onsubmit="return verificarCajaChica(this, '<?=$acc?>');">
<input type="hidden" name="accion" value="<?=$accion?>" />
<input type="hidden" name="forganismo" value="<?=$forganismo?>" />
<input type="hidden" name="fcodpersona" value="<?=$fcodpersona?>" />
<input type="hidden" name="fnompersona" value="<?=$fnompersona?>" />
<input type="hidden" name="fdependencia" value="<?=$fdependencia?>" />
<input type="hidden" name="festado" value="<?=$festado?>" />
<input type="hidden" name="fcchica" value="<?=$fcchica?>" />
<input type="hidden" name="fcodccosto" value="<?=$fcodccosto?>" />
<input type="hidden" name="fnomccosto" value="<?=$fnomccosto?>" />
<input type="hidden" name="ffpreparaciond" value="<?=$ffpreparaciond?>" />
<input type="hidden" name="ffpreparacionh" value="<?=$ffpreparacionh?>" />
<div style="width:1000px" class="divFormCaption">Informaci&oacute;n General</div>
<table width="1000" class="tblForm">
	<tr>
		<td class="tagForm" width="125">Caja Chica:</td>
		<td>
        	<input type="text" id="codigo" style="width:35px; font-weight:bold; text-align:center; font-size:14px;" value="<?=$field['NroCajaChica']?>" disabled="disabled" /> - 
        	<input type="text" id="periodo" style="width:35px; font-weight:bold; text-align:center; font-size:14px;" value="<?=$periodo?>" disabled="disabled" />
        	<input type="hidden" id="flagcajachica" value="<?=$flagcajachica?>" />
		</td>
		<td class="tagForm" width="125">Beneficiario: </td>
		<td>
        	<input type="hidden" id="codbeneficiario" value="<?=$codbeneficiario?>" />
			<input type="text" id="nombeneficiario" value="<?=($nombeneficiario)?>" style="width:250px;" disabled="disabled" />
			<input type="button" value="..." onclick="cargarVentana(this.form, 'listado_personas.php?ventana=ap_caja_chica&cod=codbeneficiario&nom=nombeneficiario&limit=0&flagempleado=S&codorganismo='+document.getElementById('codorganismo').value, 'height=600, width=775, left=50, top=50, resizable=yes');" />*
        </td>
	</tr>
	<tr>
        <td class="tagForm">Organismo:</td>
        <td>
            <select id="codorganismo" style="width:300px;" onchange="getOptions_2(this.id, 'coddependencia')" <?=$d_codigo?>>
                <?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $codorganismo, 0);?>
            </select>*
        </td>
		<td class="tagForm">Cheque a Nombre de: </td>
		<td>
        	<input type="hidden" id="codpagara" value="<?=$codpagara?>" />
			<input type="text" id="nompagara" value="<?=($nompagara)?>" style="width:250px;" <?=$d_ver?> />
			<input type="button" value="..." onclick="cargarVentana(this.form, 'listado_personas.php?ventana=&cod=codpagara&nom=nompagara&limit=0&flagpersona=S', 'height=600, width=775, left=50, top=50, resizable=yes');" <?=$d_ver?> />*
        </td>
    </tr>
	<tr>
        <td class="tagForm">Dependencia:</td>
        <td>
            <select id="coddependencia" style="width:300px;" <?=$d_codigo?>>
                <?=loadSelectDependiente("mastdependencias", "CodDependencia", "Dependencia", "CodOrganismo", $coddependencia, $codorganismo, 0);?>
            </select>*
        </td>
        <td class="tagForm">Clasificaci&oacute;n:</td>
        <td>
            <select id="codclasificacion" style="width:150px;" <?=$d_ver?>>
                <?=loadSelect("ap_clasificaciongastos", "CodClasificacion", "Descripcion", $codclasificacion, 1);?>
            </select>*
        </td>
    </tr>
	<tr>
		<td class="tagForm">Obligaci&oacute;n:</td>
		<td>
        	<input type="text" id="obligaciontipodoc" style="width:35px;" value="<?=$field['ObligacionTipoDocumento']?>" disabled="disabled" />
        	<input type="text" id="obligacionnrodoc" style="width:100px;" value="<?=$field['ObligacionNroDocumento']?>" disabled="disabled" />
		</td>
        <td class="tagForm">Tipo de Pago:</td>
        <td>
            <select id="codtipopago" style="width:150px;" <?=$d_ver?>>
                <?=loadSelect("masttipopago", "CodTipopago", "Tipopago", $codtipopago, 0);?>
            </select>*
        </td>
	</tr>
	<tr>
		<td class="tagForm">Nro. Doc. Interno:</td>
		<td><input type="text" id="nrodocinterno" style="width:145px;" value="<?=$field['NroDocInterno']?>" disabled="disabled" /></td>
		<td class="tagForm">Monto a Reembolsar:</td>
		<td><input type="text" id="monto_reembolsar" style="width:144px; text-align:right;" value="<?=number_format($field['MontoNeto'], 2, ',', '.')?>" /></td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
        	<input type="hidden" id="estado" value="<?=$estado?>" />
        	<input type="text" style="width:100px;" value="<?=printValores("ESTADO-CAJA-CHICA", $estado)?>" disabled="disabled" />
		</td>
		<td class="tagForm">Monto Autorizado:</td>
		<td><input type="text" id="monto_autorizado" style="width:144px; text-align:right;" value="<?=number_format($monto_autorizado, 2, ',', '.')?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">Preparado Por:</td>
		<td>
        	<input type="hidden" id="codpreparadopor" value="<?=$preparadopor?>" />
        	<input type="text" id="nompreparadopor" style="width:250px;" value="<?=$nompreparadopor?>" disabled="disabled" />
        	<input type="text" id="fpreparadopor" style="width:65px;" value="<?=$fpreparadopor?>" <?=$d_ver?> />
		</td>
        <td class="tagForm">C.Costo:</td>
        <td>
        	<input type="text" id="codccosto" value="<?=$field['CodCentroCosto']?>" style="width:50px;" disabled="disabled" />
			<input type="hidden" id="nomccosto" value="<?=$field['NomCentroCosto']?>" />
			<input type="button" value="..." onclick="cargarVentana(this.form, 'listado_centro_costos.php?ventana=&cod=codccosto&nom=nomccosto&limit=0', 'height=600, width=825, left=50, top=50, resizable=yes');" <?=$d_ver?> />*
        </td>
	</tr>
	<tr>
		<td class="tagForm">Aprobado Por:</td>
		<td colspan="3">
        	<input type="hidden" id="codaprobadopor" value="<?=$aprobadopor?>" />
        	<input type="text" id="nomaprobadopor" style="width:250px;" value="<?=$nomaprobadopor?>" disabled="disabled" />
        	<input type="text" id="faprobadopor" style="width:65px;" value="<?=$faprobadopor?>" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td colspan="3"><textarea id="descripcion" style="width:98%; height:40px;" <?=$d_ver?>><?=($field['Descripcion'])?></textarea></td>
	</tr>
    <tr>
		<td class="tagForm">Motivo Rechazo:</td>
		<td colspan="3"><textarea id="motivo" style="width:98%; height:40px;" <?=$d_motivo?>><?=($field['RazonRechazo'])?></textarea></td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td colspan="3">
			<input name="ult_usuario" type="text" id="ult_usuario" value="<?=$field['UltimoUsuario']?>" size="30" disabled="disabled" />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center> 
<input type="submit" id="bt_submit" value="<?=$label_submit?>" style="width:75px; <?=$style_submit?>" />
<input type="button" id="bt_cancelar" value="Cancelar" style="width:75px;" onClick="<?=$onclick?>" />
</center>
<div style="width:1000px" class="divMsj">Campos Obligatorios *</div>
</form>
</div>

<div id="tab2" style="display:none;">
<div style="width:1000px" class="divFormCaption">Detalles de la Reposici&oacute;n</div>
<form name="frmdetalles" id="frmdetalles">
<input type="hidden" id="seldetalle" />
<table width="1000" class="tblBotones">
    <tr>
    	<td>
            <input type="button" style="width:80px;" id="btSelConcepto" value="Sel. Concepto" onclick="abrirSelLista('frmdetalles', 'seldetalle', 'concepto', 'nomconcepto', 'ap_listado_concepto_gastos.php?limit=0&ventana=&', 600, 775);" <?=$d_ver?> />
            <input type="button" style="width:80px;" id="btSelPersona" value="Sel. Persona" onclick="abrirSelLista('frmdetalles', 'seldetalle', 'codpersona', 'nompersona', 'listado_personas.php?limit=0&flagproveedor=S&ventana=caja_chica&', 600, 775, 'rif');" <?=$d_ver?> />
            <input type="button" style="width:80px;" id="btSelDistribucion" value="Distribuci&oacute;n" onclick="agregarDistribucionCajaChica();" <?=$d_ver?> />
        </td>
        <td align="right">
            <input type="button" class="btLista" value="Agregar" onclick="insertarCajaChicaDetalle(this);" <?=$d_ver?> />
            <input type="button" class="btLista" value="Borrar" onclick="quitarCajaChicaDetalle(document.getElementById('seldetalle').value);" <?=$d_ver?> />
        </td>
    </tr>
</table>
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:300px;">
<table width="2400" class="tblLista">
    <tr class="trListaHead">
        <th scope="col" width="30">#</th>
        <th scope="col" width="75">Fecha Documento</th>
        <th scope="col" width="300">Concepto</th>
        <th scope="col">Descripci&oacute;n</th>
        <th scope="col" width="100">Monto Pagado</th>
        <th scope="col" width="125">Tipo Impuesto</th>
        <th scope="col" width="150">Tipo Servicio</th>
        <th scope="col" width="100">Monto Afecto</th>
        <th scope="col" width="100">Monto No Afecto</th>
        <th scope="col" width="100">Monto Impuesto</th>
        <th scope="col" width="100">Monto Retenci&oacute;n</th>
        <th scope="col" width="100">R.I.F.</th>
        <th scope="col" colspan="2">Documento</th>
        <th scope="col" width="100">Factura</th>
        <th scope="col" width="275">Persona</th>
    </tr>
    
    <tbody id="listaDetalles">
    <?php
	$nrodetalles = 0;
	$candetalles = 0;
	if ($acc == "ACTUALIZAR" || $acc == "VER" || $acc == "APROBAR" || $acc == "ANULAR") {
		//	consulto los detalles del registro
		$sql = "SELECT
					ccd.*,
					cg.Descripcion AS NomConceptoGasto
				FROM
					ap_cajachicadetalle ccd
					INNER JOIN ap_conceptogastos cg ON (ccd.CodConceptoGasto = cg.CodConceptoGasto)
				WHERE
					ccd.FlagCajaChica = '".$flagcajachica."' AND
					ccd.Periodo = '".$periodo."' AND
					ccd.NroCajaChica = '".$nrocajachica."'
				ORDER BY FechaDocumento, Secuencia";
													
		$query_detalle = mysql_query($sql) or die($sql.mysql_error());
		while ($field_detalle = mysql_fetch_array($query_detalle)) {
			$sql = "SELECT *
					FROM ap_cajachicadistribucion
					WHERE
						FlagCajaChica = '".$flagcajachica."' AND
						Periodo = '".$periodo."' AND
						NroCajaChica = '".$nrocajachica."' AND
						Secuencia = '".$field_detalle['Secuencia']."'
					ORDER BY Secuencia";
//echo $sql;
			$query_distribucion = mysql_query($sql) or die($sql.mysql_error());	$linea = 0;	$distribucion = "";
			while($field_distribucion = mysql_fetch_array($query_distribucion)) {	$linea++;
				if ($linea == 1) $coma = ""; else $coma = ";";
				$distribucion .= $coma.number_format($field_distribucion['Monto'], 2, ',', '.')."|$field_distribucion[CodConceptoGasto]|$field_distribucion[CodPartida]|$field_distribucion[CodCuenta]|$field_distribucion[CodCentroCosto]";
			}
			//---------------------------------
			$candetalles++;
			$nrodetalles++;
			if ($field_detalle['CodRegimenFiscal'] == "I") { $d_pagado = ""; $d_afecto = ""; $d_noafecto = ""; }
			else if ($field_detalle['CodRegimenFiscal'] == "N") { $d_pagado = ""; $d_afecto = "disabled"; $d_noafecto = "disabled"; }
			else if ($field_detalle['CodRegimenFiscal'] == "R") { $d_pagado = "disabled"; $d_afecto = ""; $d_noafecto = ""; }
			else if ($field_detalle['CodRegimenFiscal'] == "M") { $d_pagado = "disabled"; $d_afecto = ""; $d_noafecto = ""; }			
			?>
			<tr class="trListaBody" onclick="mClk(this, 'seldetalle');" id="det_<?=$nrodetalles?>">
				<td align="center"><?=$candetalles?></td>
                <td align="center"><input type="text" name="fdocumento" value="<?=formatFechaDMA($field_detalle['FechaDocumento'])?>" style="width:94%; text-align:center;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" <?=$d_ver?> /></td>
                <td align="center">
                    <input type="hidden" name="concepto" id="concepto_<?=$candetalles?>" value="<?=$field_detalle['CodConceptoGasto']?>" />
                    <input type="text" name="nomconcepto" id="nomconcepto_<?=$candetalles?>" value="<?=($field_detalle['NomConceptoGasto'])?>" style="width:99%;" class="cell" disabled="disabled" />
                </td>
                <td align="center"><textarea name="descripcion" style="width:99%; height:35px;" class="cell" onBlur="this.className='cell'; this.style.height='35px'" onFocus="this.className='cellFocus'; this.style.height='60px'" <?=$d_ver?>><?=($field_detalle['Descripcion'])?></textarea></td>
                <td align="center"><input type="text" name="pagado" id="pagado_<?=$candetalles?>" value="<?=number_format($field_detalle['MontoPagado'], 2, ',', '.')?>" style="width:95%; text-align:right;" class="cell" onfocus="numeroFocus(this); this.className='cellFocus';" onblur="numeroBlur(this); this.className='cell';" onchange="setMontosCajaChica(<?=$candetalles?>, 'pagado');" <?=$d_pagado?> <?=$d_ver?> /></td>
                <td align="center">
                    <select name="tipoimpuesto" id="tipoimpuesto_<?=$candetalles?>" style="width:100%;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" onchange="setTipoServicioCajaChica(<?=$candetalles?>, this.value); limpiarMontosCajaChicaDetalle(<?=$candetalles?>);" <?=$d_ver?>>
                        <?=loadSelect("ap_regimenfiscal", "CodRegimenFiscal", "Descripcion", $field_detalle['CodRegimenFiscal'], 0);?>
                    </select>
                </td>
                <td align="center">
                    <select name="tiposervicio" id="tiposervicio_<?=$candetalles?>" style="width:100%;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" onchange="limpiarMontosCajaChicaDetalle(<?=$candetalles?>);" <?=$d_ver?>>
                        <?=loadSelect("masttiposervicio", "CodTipoServicio", "Descripcion", $field_detalle['CodTipoServicio'], 1);?>
					</select>
                </td>
                <td align="center"><input type="text" name="afecto" id="afecto_<?=$candetalles?>" value="<?=number_format($field_detalle['MontoAfecto'], 2, ',', '.')?>" style="width:95%; text-align:right;" class="cell" onfocus="numeroFocus(this); this.className='cellFocus';" onblur="numeroBlur(this); this.className='cell';" onchange="setMontosCajaChica(<?=$candetalles?>, 'afecto');" <?=$d_afecto?> <?=$d_ver?> /></td>
                <td align="center"><input type="text" name="noafecto" id="noafecto_<?=$candetalles?>" value="<?=number_format($field_detalle['MontoNoAfecto'], 2, ',', '.')?>" style="width:95%; text-align:right;" class="cell" onfocus="numeroFocus(this); this.className='cellFocus';" onblur="numeroBlur(this); this.className='cell';" onchange="setMontosCajaChica(<?=$candetalles?>, 'noafecto');" <?=$d_noafecto?> <?=$d_ver?> /></td>
                <td align="center"><input type="text" name="impuesto" id="impuesto_<?=$candetalles?>" value="<?=number_format($field_detalle['MontoImpuesto'], 2, ',', '.')?>" style="width:95%; text-align:right;" class="cell" onfocus="numeroFocus(this); this.className='cellFocus';" onblur="numeroBlur(this); this.className='cell';" onchange="setMontosCajaChicaIva(<?=$candetalles?>);" <?=$d_ver?> /></td>
                <td align="center"><input type="text" name="retencion" id="retencion_<?=$candetalles?>" value="<?=number_format($field_detalle['MontoRetencion'], 2, ',', '.')?>" style="width:95%; text-align:right;" class="cell" onfocus="numeroFocus(this); this.className='cellFocus';" onblur="numeroBlur(this); this.className='cell';" <?=$d_ver?> /></td>
                <td align="center"><input type="text" name="rif" id="rif_<?=$candetalles?>" value="<?=$field_detalle['DocFiscal']?>" maxlength="20" style="width:95%;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" <?=$d_ver?> /></td>
                <td align="center" width="40">
                    <select name="tipodocumento" style="width:100%;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" <?=$d_ver?>>
                        <?=loadSelect("ap_tipodocumento", "CodTipoDocumento", "Descripcion", $field_detalle['CodTipoDocumento'], 11);?>
                    </select>
                </td>
                <td align="center" width="108"><input type="text" name="nrodocumento" value="<?=$field_detalle['NroDocumento']?>" maxlength="20" style="width:95%;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" <?=$d_ver?> /></td>
                <td align="center"><input type="text" name="nrofactura" value="<?=$field_detalle['NroRecibo']?>" maxlength="20" style="width:95%;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" <?=$d_ver?> /></td>
                <td align="center">
                    <input type="hidden" name="codpersona" id="codpersona_<?=$candetalles?>" value="<?=$field_detalle['CodProveedor']?>" />
                    <input type="text" name="nompersona" id="nompersona_<?=$candetalles?>" value="<?=($field_detalle['NomProveedor'])?>" style="width:99%;" maxlength="100" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" <?=$d_ver?> />
					<input type="hidden" name="distribucion" id="distribucion_<?=$candetalles?>" value="<?=($distribucion)?>" />
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
<input type="hidden" id="candetalles" value="<?=$nrodetalles?>" />
</form>
</div>

<div id="tab3" style="display:none;">
	
</div>

</body>
</html>