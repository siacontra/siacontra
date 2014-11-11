<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_pf.php");
connect();
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_pf.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Actuaciones</td>
		<td align="right"><a class="cerrar" href="#" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?
$MAXLIMIT=30;
//	-------------------------------
if ($filtrar == "DEFAULT") {
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fdependencia = $_SESSION["DEPENDENCIA_ACTUAL"];
	$fedoreg = "AP";
	$fanio = date("Y");
}
//	-------------------------------
if ($forganismo != "") { $corganismo = "checked"; $filtro.=" AND (af.CodOrganismo = '".$forganismo."')"; } else $dorganismo = "disabled";
if ($forganismoext != "") { 
	$corganismoext = "checked"; 
	$filtro.=" AND (af.CodOrganismoExterno = '".$forganismoext."')";
	if ($fdependenciaext != "") $filtro.=" AND (af.CodDependenciaExterna = '".$fdependenciaext."')";
} else {
	$dorganismoext = "disabled";
}
if ($fedoreg != "") { $cedoreg = "checked"; $filtro.=" AND (af.Estado = '".$fedoreg."')"; } else $dedoreg = "disabled";
if ($fregistrod != "" || $fregistroh != "") {
	$cfregistro = "checked";
	if ($fregistrod != "") $filtro .= "AND (af.FechaRegistro >= '".formatFechaAMD($fregistrod)."')";
	if ($fregistroh != "") $filtro .= "AND (af.FechaRegistro <= '".formatFechaAMD($fregistroh)."')";
} else $dfregistro = "disabled";
if ($fdependencia != "") { $cdependencia = "checked"; $filtro.=" AND (af.CodDependencia = '".$fdependencia."')"; } else $ddependencia = "disabled";
if ($fanio != "") { $cfanio = "checked"; $filtro.=" AND (af.Anio = '".$fanio."')"; } else $dfanio = "disabled";
?>

<form name="frmentrada" id="frmentrada" action="pf_listado_actuaciones.php" method="POST">
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="ventana" id="ventana" value="<?=$ventana?>" />
<input type="hidden" name="proceso" id="proceso" value="<?=$proceso?>" />

<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td width="125" align="right">Organismo:</td>
		<td>
			<input type="checkbox" name="chkorganismo" id="chkorganismo" value="1" <?=$corganismo?> onclick="forzarCheck(this.id)" />
			<select name="forganismo" id="forganismo" class="selectBig" <?=$dorganismo?>>
				<?=getOrganismos($forganismo, 3);?>
			</select>
		</td>
		<td width="125" align="right">F. Registro:</td>
		<td>
			<input type="checkbox" name="chkfregistro" value="1" <?=$cfregistro?> onclick="chkFiltro_2(this.checked, 'fregistrod', 'fregistroh');" />
			<input type="text" name="fregistrod" id="fregistrod" maxlength="10" size="15" value="<?=$fregistrod?>" <?=$dfregistro?> /> - 
			<input type="text" name="fregistroh" id="fregistroh" maxlength="10" size="15" value="<?=$fregistroh?>" <?=$dfregistro?> />
		</td>
	</tr>
	<tr>
		<td align="right">Dependencia</td>
		<td>
			<input type="checkbox" name="chkdependencia" id="chkdependencia" value="1" <?=$cdependencia?> onclick="forzarCheck(this.id)" />
			<select name="fdependencia" id="fdependencia" class="selectBig" <?=$ddependencia?>>
				<?=getDependencias($fdependencia, $forganismo, 3);?>
			</select>
        </td>
		<td align="right" valign="top">Estado:</td>
		<td valign="top">
            <input type="checkbox" name="chkedoreg" id="chkedoreg" value="1" <?=$cedoreg?> onclick="forzarCheck(this.id)" />
            <select name="fedoreg" id="fedoreg" style="width:190px;" <?=$dedoreg?>>
                <?=loadSelectValores("ESTADO-ACTUACION", $fedoreg, 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td align="right" valign="top">Ente:</td>
		<td>
			<input type="checkbox" name="chkorganismoext" id="chkorganismoext" value="1" <?=$corganismoext?> onclick="chkFiltro_2(this.checked, 'forganismoext', 'fdependenciaext');" />
			<select name="forganismoext" id="forganismoext" style="width:300px;" <?=$dorganismoext?> onchange="getOptions_2(this.id, 'fdependenciaext');">
            	<option value="">&nbsp;</option>
				<?=loadSelect("pf_organismosexternos", "CodOrganismo", "Organismo", $forganismoext, 0);?>
			</select>
		</td>
		<td width="125" align="right">A&ntilde;o Fiscal:</td>
		<td>
			<input type="checkbox" name="chkanio" value="1" <?=$cfanio?> onclick="chkFiltro(this.checked, 'fanio');" />
			<input type="text" name="fanio" id="fanio" maxlength="4" size="15" value="<?=$fanio?>" <?=$dfanio?> />
		</td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td>
        	<input type="checkbox" style="visibility:hidden" />
            <select name="fdependenciaext" id="fdependenciaext" style="width:300px;" <?=$dorganismoext?>>
            	<option value="">&nbsp;</option>
				<?=loadSelectDependiente("pf_dependenciasexternas", "CodDependencia", "Dependencia", "CodOrganismo", $fdependenciaext, $forganismoext, 0);?>
            </select>
        </td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>
<br /><div class="divDivision">Lista de Actuaciones</div><br />

<input type="hidden" name="registro" id="registro" />
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:350px;">
<table width="1500" class="tblLista">
    <tr class="trListaHead">
        <th scope="col" width="100">Actuaci&oacute;n</th>
        <th scope="col" colspan="2">Ente</th>
        <th scope="col" width="350">Objetivo General</th>
        <th scope="col" width="100">F.Registro</th>
        <th scope="col" width="100">F.Inicio</th>
        <th scope="col" width="100">F.Termino</th>
        <th scope="col" width="100">F.Termino Real</th>
        <th scope="col" width="100">Estado</th>
    </tr>
	<?php
    //	CONSULTO LA TABLA
	//if ($estado != "TODOS") $filtro .= " AND af.Estado = 'AP'";
	$sql = "SELECT
				af.*,
				oe.Organismo AS NomOrganismoExterno,
				de.Dependencia AS NomDependenciaExterna
			FROM
				pf_actuacionfiscal af
				INNER JOIN pf_organismosexternos oe ON (af.CodOrganismoExterno = oe.CodOrganismo)
				LEFT JOIN pf_dependenciasexternas de ON (af.CodDependenciaExterna = de.CodDependencia)
			WHERE 1 $filtro";
    $query = mysql_query($sql) or die ($sql.mysql_error());
    $rows = mysql_num_rows($query);
    //	MUESTRO LA TABLA
    while ($field = mysql_fetch_array($query)) {
		$estado = printValores("ESTADO-ACTUACION", $field['Estado']);
		
		if ($ventana == "setActuacionActividades") {
			?>
			<tr class="trListaBody" onclick="mClk(this, 'registro'); setActuacionActividades('<?=$field['CodActuacion']?>', '<?=htmlentities($field['ObjetivoGeneral'])?>');" id="<?=$field['CodActuacion']?>">
				<td align="center"><?=$field['CodActuacion']?></td>
                <td><?=htmlentities($field['NomOrganismoExterno'])?></td>
                <td><?=htmlentities($field['NomDependenciaExterna'])?></td>
                <td><?=htmlentities($field['ObjetivoGeneral'])?></td>
                <td><?=formatFechaDMA($field['FechaRegistro'])?></td>
                <td><?=formatFechaDMA($field['FechaInicio'])?></td>
                <td><?=formatFechaDMA($field['FechaTermino'])?></td>
                <td><?=formatFechaDMA($field['FechaTerminoReal'])?></td>
                <td align="center"><?=$estado?></td>
			</tr>
			<?
		} else {
			?>
			<tr class="trListaBody" onclick="mClk(this, 'registro'); selListado('<?=htmlentities($field["ObjetivoGeneral"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['CodActuacion']?>">
				<td align="center"><?=$field['CodActuacion']?></td>
                <td><?=htmlentities($field['NomOrganismoExterno'])?></td>
                <td><?=htmlentities($field['NomDependenciaExterna'])?></td>
                <td><?=htmlentities($field['ObjetivoGeneral'])?></td>
                <td><?=formatFechaDMA($field['FechaRegistro'])?></td>
                <td><?=formatFechaDMA($field['FechaInicio'])?></td>
                <td><?=formatFechaDMA($field['FechaTermino'])?></td>
                <td><?=formatFechaDMA($field['FechaTerminoReal'])?></td>
                <td align="center"><?=$estado?></td>
			</tr>
			<?
		}
	}
    ?>
</table>
</div></td></tr></table>
</form>
</body>
</html>
