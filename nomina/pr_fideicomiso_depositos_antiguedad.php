<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("../lib/fphp.php");
include("../lib/pr_fphp.php");
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('05', $concepto);
//	------------------------------------
if ($filtrar == "default") {
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fnomina = $_SESSION["NOMINA_ACTUAL"];
}
$filtro = "";
if ($forganismo != "") { $corganismo = "checked"; $filtro.=" AND (tnec.CodOrganismo = '".$forganismo."')"; } else $dorganismo = "disabled";
if ($fnomina != "") { $cnomina = "checked"; $filtro.=" AND (tnec.CodTipoNom = '".$fnomina."')"; } else $dnomina = "disabled";
$filtro.=" AND (tnec.Periodo = '".$fperiodo."')"; $cperiodo = "checked";
if ($inactivos != "S") $filtro.=" AND me.Estado = 'A'"; else $flaginactivos = "checked";
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/pr_funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/pr_fscript.js"></script>
</head>

<body>
<div id="bloqueo" class="divBloqueo"></div>
<div id="cargando" class="divCargando">
<table>
	<tr>
    	<td valign="middle" style="height:50px;">
			<img src="../imagenes/iconos/cargando.gif" /><br />Procesando...
        </td>
    </tr>
</table>
</div>

<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Actualizar Acumulado de Antiguedad</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="pr_fideicomiso_depositos_antiguedad.php" method="post">
<div class="divBorder" style="width:1100px;">
<table width="1100" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
			<input type="checkbox" <?=$corganismo?> onclick="this.checked=!this.checked;" />
			<select name="forganismo" id="forganismo" <?=$dorganismo?> style="width:300px;" onchange="getOptions_2(this.value, document.getElementById('fnomina').value, 'periodo', 'fperiodo', 75);">
				<option value=""></option>
				<?=getOrganismos($forganismo, 3);?>
			</select>
		</td>
		<td align="right">N&oacute;mina:</td>
		<td>
			<input type="checkbox" <?=$cnomina?> onclick="this.checked=!this.checked;" />
			<select name="fnomina" id="fnomina" style="width:300px;" <?=$dnomina?> onchange="getOptions_2(document.getElementById('forganismo').value, this.value, 'periodo', 'fperiodo', 75);">
				<option value=""></option>
				<?=loadSelect("tiponomina", "CodTipoNom", "Nomina", $fnomina, 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Periodo:</td>
		<td>
			<input type="checkbox" <?=$cperiodo?> onclick="this.checked=!this.checked;" />
            <span>
			<select name="fperiodo" id="fperiodo" style="width:75px;" <?=$dperiodo?>>
				<option value=""></option>
				<?=loadSelectNominaPeriodos($forganismo, $fnomina, $fperiodo)?>
			</select>
            </span>
		</td>
		<td align="right">&nbsp;</td>
		<td><input type="checkbox" name="inactivos" id="inactivos" value="S" <?=$flaginactivos?> /> Mostrar Cesados</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="1100" class="tblBotones">
	<tr>
		<td>
        	<a href="#" onclick="listaMultiSelTodo(document.getElementById('frmentrada'), 'persona', 'row', true);">Seleccionar Todo</a> / 
        	<a href="#" onclick="listaMultiSelTodo(document.getElementById('frmentrada'), 'persona', 'row', false);">Deseleccionar Todo</a>
        </td>
		<td align="right">
			<input type="button" id="btActualizar" style="width:125px;" value="Actualizar Acumulados" onclick="actualizarAcumulados(this.form);" />
			<input type="button" id="btTXT" style="width:100px;" value="Generar TXT" />
			<input type="button" id="btPDF" style="width:100px;" value="Imprimir" onclick="fideicomiso_depositos_antiguedad_imprimir();" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:1100px; height:400px;">
<table width="100%" class="tblLista">
	<thead>
	<tr class="trListaHead">
		<th width="25" scope="col">Act.</th>
		<th width="75" scope="col">C&oacute;digo</th>
		<th scope="col">Nombre</th>
		<th width="150" scope="col">Cuenta</th>
		<th width="75" scope="col">Nro. Documento</th>
		<th width="100" scope="col">Monto</th>
		<th width="50" scope="col">Dias</th>
		<th width="100" scope="col">Complemento</th>
		<th width="50" scope="col">Dias Adic.</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT
				me.CodPersona,
				me.CodEmpleado,
				mp.NomCompleto,
				bp.Ncuenta,
				mp.Ndocumento,
				tnec.Monto,
				tnec.Cantidad,
				me.Fingreso,
				(SELECT COUNT(*)
				 FROM pr_acumuladofideicomisodetalle afd
				 WHERE
				 	afd.CodOrganismo = '".$forganismo."' AND
					afd.Periodo = '".$fperiodo."'  AND
					afd.CodPersona = tnec.CodPersona) AS PeriodoGenerado,
				pp.FechaDesde,
				pp.FechaHasta
			FROM
				mastpersonas mp
				INNER JOIN mastempleado me ON (mp.CodPersona = me.CodPersona)
				INNER JOIN pr_tiponominaempleadoconcepto tnec ON (mp.CodPersona = tnec.CodPersona)
				LEFT JOIN bancopersona bp ON (mp.CodPersona = bp.CodPersona AND bp.Aportes = 'FI')
				LEFT JOIN pr_acumuladofideicomisodetalle afd ON (tnec.CodPersona = afd.CodPersona AND tnec.Periodo = afd.Periodo)
				INNER JOIN pr_procesoperiodo pp ON (pp.CodOrganismo = tnec.CodOrganismo AND
													pp.CodTipoNom = tnec.CodTipoNom AND
													pp.Periodo = tnec.Periodo AND
													pp.CodTipoProceso = tnec.CodTipoProceso)
			WHERE
				(tnec.CodTipoProceso = 'FIN' OR tnec.CodTipoProceso = 'PPA') AND
				tnec.CodConcepto = '".$_PARAMETRO["PROVISION"]."'
				$filtro
			ORDER BY length(mp.Ndocumento), mp.Ndocumento";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		//	obtengo dias adicionales
		//$cantidad = getDiasAdicionales($field['Fingreso'], $fperiodo);
		$cantidad = getDiasAdicionalesTrimestral($field['Fingreso'], $field['FechaDesde'], $field['FechaHasta']);
		//	si tiene dias adicionales calculo el complemento por los dias adicionales
		//if ($cantidad > 0) $complemento = calculo_antiguedad_complemento($fperiodo, $field['CodPersona'], $field['Fingreso']); else $complemento = 0;
		if ($cantidad > 0) $complemento = calculo_antiguedad_complemento_trimestral($field['CodPersona'], $field['Fingreso'], $field['FechaDesde'], $field['FechaHasta']); else $complemento = 0;
		//	verifico si ya se genero
		if ($field['PeriodoGenerado'] > 0) $check = "S"; else $check = "N";
		?>
		<tr class="trListaBody" onclick="mClkMulti(this);" id="row_<?=$field['CodPersona']?>">
        	<td align="center"><?=printFlag($check)?></td>
			<td align="center">
				<?=$field['CodEmpleado']?>
                <input type="checkbox" name="persona" id="<?=$field['CodPersona']?>" value="<?=$field['CodPersona']?>" style="display:none;" />
                <input type="hidden" name="mes_monto" value="<?=$field['Monto']?>" />
                <input type="hidden" name="mes_dias" value="<?=$field['Cantidad']?>" />
                <input type="hidden" name="complemento_monto" value="<?=$complemento?>" />
                <input type="hidden" name="complemento_dias" value="<?=$cantidad?>" />
            </td>
			<td><?=$field['NomCompleto']?></td>
			<td><?=$field['Ncuenta']?></td>
			<td align="right"><?=number_format($field['Ndocumento'], 0, '', '.')?></td>
			<td align="right"><?=number_format($field['Monto'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field['Cantidad'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($complemento, 2, ',', '.')?></td>
			<td align="right"><?=number_format($cantidad, 2, ',', '.')?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
</center>
</form>
<script type="text/javascript" language="javascript">
	totalRegistros(parseInt(<?=$rows?>), "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
</script>
</body>
</html>