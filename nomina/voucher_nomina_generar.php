<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	--------------------------------------
extract($_POST);
extract($_GET);
//	--------------------------------------
include("fphp_nomina.php");
connect();
//	--------------------------------------
list($organismo, $nomina, $periodo, $proceso) = split("[.]", $registro);
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
		<td class="titulo">Generaci&oacute;n de Vouchers</td>
		<td align="right"><a class="cerrar" href="javascript:document.getElementById('frmentrada').submit();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="voucher_nomina.php" method="POST" onsubmit="return abrirVoucherNomina(this);">
<input type="hidden" name="registro" id="registro" value="<?=$registro?>" />
<input type="hidden" name="forganismo" id="forganismo" value="<?=$forganismo?>" />
<input type="hidden" name="ftiponom" id="ftiponom" value="<?=$ftiponom?>" />
<input type="hidden" name="chkinactivos" id="chkinactivos" value="<?=$chkinactivos?>" />

<div style="width:800px" class="divFormCaption">Informaci&oacute;n a procesar</div>
<table width="800" class="tblForm">
    <tr>
        <td class="tagForm">Organismo:</td>
        <td>
        	<select id="organismo" style="width:275px;">
				<?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $organismo, 1)?>
			</select>
        </td>
        <td class="tagForm">Tipo de N&oacute;mina:</td>
        <td>
        	<select id="tiponom" style="width:200px;">
				<?=loadSelect("tiponomina", "CodTipoNom", "Nomina", $nomina, 1)?>
			</select>
        </td>
    </tr>
    <tr>
        <td class="tagForm">Periodo:</td>
        <td><input type="text" id="periodo" value="<?=$periodo?>" style="width:75px;" disabled="disabled" /></td>
        <td class="tagForm">Tipo de Proceso:</td>
        <td>
        	<select name="proceso" id="proceso" style="width:200px;">
				<?=loadSelect("pr_tipoproceso", "CodTipoProceso", "Descripcion", $proceso, 1)?>
			</select>
        </td>
    </tr>
    <tr>
        <td class="tagForm">Fecha:</td>
        <td><input type="text" id="fecha" maxlength="10" style="width:75px;" value="<?=date("d-m-Y")?>" /></td>
    </tr>
</table>

<center> 
<input type="submit" value="Generar Voucher" />
<input type="button" value="Cancelar" onclick="cargarPagina(this.form, 'voucher_nomina.php');" />
</center><br />

<div style="width:800px" class="divFormCaption">Vouchers existentes para el periodo</div>
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:800px; height:150px;">
<table width="1400" class="tblLista">
	<tr class="trListaHead">
		<th width="75" scope="col">Periodo</th>
		<th width="75" scope="col">Organismo</th>
		<th width="75" scope="col">Voucher</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="250" scope="col">Preparado Por</th>
		<th width="100" scope="col">F. Preparaci&oacute;n</th>
		<th width="250" scope="col">Aprobado Por</th>
		<th width="100" scope="col">F. Aprobaci&oacute;n</th>
	</tr>
	<?php 
	//	CONSULTO LA TABLA
	$sql = "SELECT
				ppp.*,
				tn.Nomina,
				tp.Descripcion AS Proceso,
				mp1.NomCompleto AS NomCreadoPor,
				mp2.NomCompleto AS NomAprobadoPor
			FROM
				pr_procesoperiodo ppp
				INNER JOIN tiponomina tn ON (ppp.CodTipoNom = tn.CodTipoNom)
				INNER JOIN pr_tipoproceso tp ON (ppp.CodTipoProceso = tp.CodTipoProceso)
				LEFT JOIN mastpersonas mp1 ON (ppp.CreadoPor = mp1.CodPersona)
				LEFT JOIN mastpersonas mp2 ON (ppp.AprobadoPor = mp2.CodPersona)
			WHERE
				ppp.CodOrganismo = '".$organismo."' AND
				ppp.Periodo = '".$periodo."'
			ORDER BY CodTipoNom, FechaCreado";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[CodOrganismo].$field[CodTipoNom].$field[Periodo].$field[CodTipoProceso]";
		list($anio, $mes) = SPLIT('[/.-]', $field['Periodo']);
		if ($field['Estado'] == "A") {
			if ($field['FlagProcesado'] == "N") $src_status = "imagenes/reloj.png";
			else $src_status = "imagenes/check.png";
		} else $src_status = "imagenes/inactivo.png";
		if ($field['FlagAprobado'] == "S") $src_aprobado = "imagenes/bandera.png";
		else $src_aprobado = "imagenes/menos.png";
		//	---------------------------------------------------
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
        	<td align="center"><?=$field['Periodo']?></td>
        	<td align="center"><?=$field['CodOrganismo']?></td>
        	<td align="center"><?=$field['Voucher']?></td>
        	<td><?=($field['Nomina'])?>, <?=($field['Proceso'])?></td>
        	<td><?=($field['NomCreadoPor'])?></td>
        	<td align="center"><?=$field['FechaCreado']?></td>
        	<td><?=($field['NomAprobadoPor'])?></td>
        	<td align="center"><?=$field['FechaAprobado']?></td>
		</tr>
        <?
	}
	?>
</table> 
</div></td></tr></table>
</table>
</form>

</body>
</html>
