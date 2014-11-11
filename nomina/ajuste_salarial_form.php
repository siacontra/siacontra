<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	--------------------------
extract($_POST);
extract($_GET);
//	--------------------------
include("fphp_nomina.php");
connect();
//	--------------------------
if ($accion == "INSERTAR") {
	$titulo = "Nuevo Registro";
	$label_submit = "Guardar Registro";
	$s_anular = "display:none;";
	$d_anular = "disabled";
	$style_submit = "";
	$onclick = "document.getElementById('frmentrada').submit();";
	$codorganismo = $_SESSION["ORGANISMO_ACTUAL"];
	$periodo = date("Y-m");
	$secuencia = "";
	$preparadopor = $_SESSION["CODPERSONA_ACTUAL"];
	$nompreparadopor = $_SESSION["NOMBRE_USUARIO_ACTUAL"];
	$fpreparacion = date("d-m-Y");
	$estado = "PR";
}
elseif ($accion == "ACTUALIZAR" || $accion == "VER" || $accion == "APROBAR" || $accion == "ANULAR") {
	//	consulto los datos del registro
	list($codorganismo, $periodo, $secuencia) = split("[.]", $registro);
	$sql = "SELECT
				pas.*,
				mp.NomCompleto AS NomPreparadoPor
			FROM
				pr_ajustesalarial pas
				INNER JOIN mastpersonas mp ON (pas.PreparadoPor = mp.CodPersona)
			WHERE
				pas.CodOrganismo = '".$codorganismo."' AND
				pas.Periodo = '".$periodo."' AND
				pas.Secuencia = '".$secuencia."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	//	----------------------------
	if ($accion == "ACTUALIZAR") {
		$titulo = "Actualizar Registro";
		$label_submit = "Actualizar Registro";
		$style_submit = "";
		if ($field["Estado"] != "AN") $s_anular = "display:none;";
		$d_anular = "disabled";
		$d_codigo = "disabled";
		$d_ver = "";
		$d_ajustes = "";
		$onclick = "document.getElementById('frmentrada').submit();";
		if ($field['Estado'] == "AP" || $field['Estado'] == "AN") {
			$style_submit = "display:none;";
			if ($field["Estado"] != "AN") $s_anular = "display:none;";
			$d_ver = "disabled";
			$d_ajustes = "disabled";
		}
	}
	elseif ($accion == "VER") {
		$titulo = "Ver Registro";
		$label_submit = "";
		$style_submit = "display:none;";
		if ($field["Estado"] != "AN") $s_anular = "display:none;";
		$d_anular = "disabled";
		$d_codigo = "disabled";
		$d_ver = "disabled";
	 	$d_ajustes = "disabled";
		$onclick = "window.close();";
	}
	elseif ($accion == "APROBAR") {
		$titulo = "Aprobar Registro";
		$label_submit = "Aprobar Registro";
		$style_submit = "";
		$s_anular = "display:none;";
		$d_anular = "disabled";
		$d_codigo = "disabled";
		$d_ver = "disabled";
	 	$d_ajustes = "disabled";
		$onclick = "document.getElementById('frmentrada').submit();";
	}
	elseif ($accion == "ANULAR") {
		$titulo = "Anular Registro";
		$label_submit = "Anular Registro";
		$style_submit = "";
		$s_anular = "";
		$d_anular = "";
		$d_codigo = "disabled";
		$d_ver = "disabled";
	 	$d_ajustes = "disabled";
		$onclick = "document.getElementById('frmentrada').submit();";
	}
	$preparadopor = $field['PreparadoPor'];
	$nompreparadopor = $field["NomPreparadoPor"];
	$fpreparacion = formatFechaDMA($field['FechaPreparacion']);
	$estado = $field['Estado'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_nomina_2.js"></script>
</head>

<body onload="document.getElementById('descripcion').focus();">
<div id="bloqueo" class="divBloqueo"></div>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Ajuste Salarial por Grado | <?=$titulo?></td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="<?=$onclick?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<table width="750" align="center">
  <tr>
		<td>
			<div id="header">
			<ul>
			<!-- CSS Tabs -->
			<li><a onclick="document.getElementById('tab1').style.display='block'; document.getElementById('tab2').style.display='none';" href="#">Informaci&oacute;n General</a></li>
			<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block';" href="#">Ajustes</a></li>
			</ul>
			</div>
		</td>
	</tr>
</table>

<div id="tab1" style="display:block;">
<form name="frmentrada" id="frmentrada" action="ajuste_salarial_listar.php" method="POST" onsubmit="return verificarAjusteSalarial(this, '<?=$accion?>');">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<input type="hidden" name="opcion" id="opcion" value="<?=$opcion?>" />
<input type="hidden" name="secuencia" id="secuencia" value="<?=$secuencia?>" />
<div style="width:750px" class="divFormCaption">Datos del Registro</div>
<table width="750" class="tblForm">
	<tr>
        <td class="tagForm" width="125">Organismo:</td>
        <td>
            <select id="codorganismo" style="width:300px;" onchange="limpiar_empleado();" <?=$d_codigo?>>
                <?=getOrganismos($codorganismo, 0);?>
            </select>*
        </td>
    </tr>
	<tr>
        <td class="tagForm">Periodo:</td>
        <td><input type="text" id="periodo" style="width:50px; font-weight:bold;" value="<?=$periodo?>" <?=$d_codigo?> /></td>
    </tr>
	<tr>
        <td class="tagForm">Estado:</td>
        <td>
        	<input type="hidden" id="estado" value="<?=$estado?>" />
        	<input type="text" style="width:100px; font-weight:bold;" value="<?=htmlentities(printValores("ESTADO-AJUSTE", $estado))?>" disabled="disabled" />
		</td>
    </tr>
	<tr>
        <td class="tagForm">Descripci&oacute;n:</td>
        <td><textarea id="descripcion" style="width:90%; height:50px;" <?=$d_ver?>><?=($field['Descripcion'])?></textarea>*</td>
    </tr>
	<tr>
        <td class="tagForm">Nro. Resoluci&oacute;n:</td>
        <td><input type="text" id="nroresolucion" maxlength="15" style="width:100px;" value="<?=$field['NroResolucion']?>" <?=$d_ver?> />*</td>
    </tr>
	<tr>
        <td class="tagForm">Nro. Gaceta:</td>
        <td><input type="text" id="nrogaceta" maxlength="15" style="width:100px;" value="<?=$field['NroGaceta']?>" <?=$d_ver?> /></td>
    </tr>
	<tr>
        <td class="tagForm">Preparado Por:</td>
        <td>
        	<input type="hidden" id="preparadopor" value="<?=$preparadopor?>" />
        	<input type="text" id="nompreparadopor" style="width:211px;" value="<?=($nompreparadopor)?>" disabled="disabled" />
        	<input type="text" id="fpreparacion" style="width:55px;" value="<?=$fpreparacion?>" disabled="disabled" />
		</td>
    </tr>
	<tr>
        <td class="tagForm">Aprobado Por:</td>
        <td>
        	<input type="hidden" id="aprobadopor" value=""<?=$field['AprobadoPor']?> />
        	<input type="text" id="nomaprobadopor" style="width:211px;" value=""<?=($field['NomAprobadoPor'])?> disabled="disabled" />
        	<input type="text" id="faprobacion" style="width:55px;" value=""<?=formatFechaDMA($field['FechaAprobacion'])?> disabled="disabled" />
		</td>
    </tr>
	<tr style=" <?=$s_anular?>">
        <td class="tagForm">Motivo Anulaci&oacute;n:</td>
        <td><textarea id="motivo" style="width:90%; height:50px;" <?=$d_anular?>><?=($field['MotivoAnulacion'])?></textarea></td>
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
<div style="width:750px" class="divMsj">Campos Obligatorios *</div>
</form>
</div>

<div name="tab2" id="tab2" style="display:none;">
<div style="width:745px" class="divFormCaption">Detalle </div>
<form name="frmdetalles" id="frmdetalles">
<table align="center" width="100%"><tr><td align="center"><div style="overflow:scroll; height:450px; width:745px;">
<table width="100%" class="tblLista">
    <tr class="trListaHead">
		<th width="25" scope="col">&nbsp;</th>
		<th width="75" scope="col">Grado</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="90" scope="col">Sueldo Actual</th>
		<th width="90" scope="col">Porcentaje Aumentar</th>
		<th width="90" scope="col">Monto Aumentar</th>
		<th width="90" scope="col">Sueldo Nuevo</th>
	</tr>
                
    <tbody id="listaDetalles">
    <?php
	$sql = "SELECT
				ns.*,
				md.Descripcion AS Categoria,
				asa.SueldoPromedio AS Nuevo,
				asa.Porcentaje,
				asa.Monto
			FROM
				rh_nivelsalarial ns
				INNER JOIN mastmiscelaneosdet md ON (ns.CategoriaCargo = md.CodDetalle AND CodMaestro = 'CATCARGO')
				LEFT JOIN pr_ajustesalarialajustes asa ON (ns.CodNivel = asa.CodNivel AND
														   asa.CodOrganismo = '".$codorganismo."' AND
														   asa.Periodo = '".$periodo."' AND
														   asa.Secuencia = '".$secuencia."')
			ORDER BY ns.CategoriaCargo, ns.Grado";

	$query_ajustes = mysql_query($sql) or die($sql.mysql_error());
	while ($field_ajustes = mysql_fetch_array($query_ajustes)) {
		if ($field_ajustes['Nuevo'] > 0) {
			$checked = "checked";
			$disabled = "";
			if ($field_ajustes['Porcentaje'] > 0) {
				$porcentaje = number_format($field_ajustes['Porcentaje'], 2, ',' , '.');
				$monto = "";
			} else {
				$porcentaje = "";
				//$monto = number_format($field_ajustes['Monto'], 2, ',','.');
				$monto = $field_ajustes['Monto'];
			}
			$nuevo = number_format($field_ajustes['Nuevo'], 2, ',' , '.');
		} else {
			$checked = "";
			$disabled = "disabled";
			$porcentaje = "";
			$monto = "";
			$nuevo = "";
		}
		
		if ($grupo != $field_ajustes['CategoriaCargo']) {
			$grupo = $field_ajustes['CategoriaCargo'];
			?><tr class="trListaBody2"><td>&nbsp;</td><td colspan="7"><?=$field_ajustes['Categoria']?></td></tr><?
		}
		?>
		<tr class="trListaBody">
			<td align="center">
				<input type="checkbox" name="grados" id="<?=$field_ajustes['CodNivel']?>" onclick="enabledAjusteSalarial(this.id, this.checked);" <?=$d_ver?> <?=$checked?> />
			</td>
			<td align="center"><?=$field_ajustes['Grado']?></td>
			<td><?=($field_ajustes['Descripcion'])?></td>
			<td align="center">
				<input type="text" name="actual" id="A_<?=$field_ajustes['CodNivel']?>" style="width:95%;" dir="rtl" value="<?=number_format($field_ajustes['SueldoPromedio'], 2, ',', '.')?>" disabled="disabled" />
			</td>
			<td align="center">
				<input type="text" name="porcentaje" id="P_<?=$field_ajustes['CodNivel']?>" dir="rtl" style="width:95%;" onchange="setAjusteSalarial('P', '<?=$field_ajustes['CodNivel']?>', <?=$field_ajustes['SueldoPromedio']?>)" value="<?=$porcentaje?>" onfocus="numeroFocus(this);" onblur="numeroBlur(this);" <?=$d_ver?> <?=$disabled?> />
			</td>
			<td align="center">
				<input type="text" name="monto" id="M_<?=$field_ajustes['CodNivel']?>" dir="rtl" style="width:95%;" onchange="setAjusteSalarial('M', '<?=$field_ajustes['CodNivel']?>', <?=$field_ajustes['SueldoPromedio']?>)" value="<?=$monto?>"  <?=$d_ver?> <?=$disabled?> />
			</td>
			<td align="center">
				<input type="text" name="nuevo" id="N_<?=$field_ajustes['CodNivel']?>" value="<?=$nuevo?>" dir="rtl" style="width:95%; font-weight:bold;" disabled="disabled" />
			</td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div></td></tr></table>
</form>
</div>

</body>
</html>
