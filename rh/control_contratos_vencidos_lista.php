<?php
//	------------------------------------
//	filtro
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["_CODORGANISMO"];
	$maxlimit = $_SESSION["_MAXLIMIT"];
}
if ($fCodOrganismo != "") { $cCodOrganismo = "checked"; $filtro.=" AND (c.CodOrganismo = '".$fCodOrganismo."')"; } else $dCodOrganismo = "disabled";
if ($fCodDependencia != "") { $cCodDependencia = "checked"; $filtro.=" AND (e.CodDependencia = '".$fCodDependencia."')"; } else $dCodDependencia = "disabled";
if ($fBuscar != "") {
	$cBuscar = "checked";
	$filtro.=" AND (e.CodEmpleado LIKE '%".$fBuscar."%' OR
					p.NomCompleto LIKE '%".$fBuscar."%' OR
					tc.Descripcion LIKE '%".$fBuscar."%' OR
					e.Fingreso LIKE '%".$fBuscar."%' OR
					pt.DescripCargo LIKE '%".$fBuscar."%')";
} else $dBuscar = "disabled";
if ($fFechaFirmaD != "" || $fFechaFirmaH != "") {
	$cFechaFirma = "checked";
	if ($fFechaFirmaD != "") $filtro .= " AND (e.FechaFirma >= '".formatFechaAMD($fFechaFirmaD)."')";
	if ($fFechaFirmaH != "") $filtro .= " AND (e.FechaFirma <= '".formatFechaAMD($fFechaFirmaH)."')";
} else $dFechaFirma = "disabled";
if ($fFechaDesde != "" || $fFechaHasta != "") {
	$cFecha = "checked";
	if ($fFechaDesde != "") $filtro .= " AND (e.FechaDesde >= '".formatFechaAMD($fFechaDesde)."')";
	if ($fFechaHasta != "") $filtro .= " AND (e.FechaHasta <= '".formatFechaAMD($fFechaHasta)."')";
} else $dFecha = "disabled";
//	------------------------------------
?>
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=control_contratos_sincontrato_lista" method="post">
<input type="hidden" name="_CONCEPTO" id="_CONCEPTO" value="<?=$_CONCEPTO?>" />
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="fCodOrganismo" id="fCodOrganismo" value="<?=$fCodOrganismo?>" />
<input type="hidden" name="fTipoContrato" id="fTipoContrato" value="<?=$fTipoContrato?>" />
<input type="hidden" name="fCodDependencia" id="fCodDependencia" value="<?=$fCodDependencia?>" />
<input type="hidden" name="fFechaDesde" id="fFechaDesde" value="<?=$fFechaDesde?>" />
<input type="hidden" name="fFechaHasta" id="fFechaHasta" value="<?=$fFechaHasta?>" />
<input type="hidden" name="fFechaFirmaD" id="fFechaFirmaD" value="<?=$fFechaFirmaD?>" />
<input type="hidden" name="fFechaFirmaH" id="fFechaFirmaH" value="<?=$fFechaFirmaH?>" />
<input type="hidden" name="registro" id="registro" />

<table width="100%" class="opcionesLista">
    <tr>
        <td valign="middle" align="right">
            <button type="button" title="Editar" class="button1" onclick="cargarOpcion(this.form, 'gehen.php?anz=control_contratos_form&opcion=modificar&action=control_contratos_vigentes_lista', 'SELF', '', 'registro');">
            <img src="../../img/editar.png" /><br />Edtar
            </button>
        </td>
    </tr>
</table>

<center>
<div class="divLista" style="width:99.8%; height:375px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th scope="col" width="25">&nbsp;</th>
		<th scope="col" width="50">Empleado</th>
		<th scope="col" align="left">Nombre Completo</th>
		<th scope="col" width="120">Tipo de Contrato</th>
		<th scope="col" colspan="2">Fecha de Ingreso</th>
		<th scope="col" width="25">Fir.</th>
		<th scope="col" width="60">Fecha de Firma</th>
		<th scope="col" width="60">Dias Venc.</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				c.CodOrganismo,
				c.CodPersona,
				c.Secuencia,
				c.FechaDesde,
				c.FechaHasta,
				c.FlagFirma,
				c.FechaFirma,
				p.NomCompleto,
				e.CodEmpleado,
				e.Fingreso,
				pt.DescripCargo,
				d.Dependencia,
				tc.Descripcion AS NomTipoContrato,
				DATEDIFF(c.FechaHasta, NOW()) AS Faltan
			FROM
				rh_contratos c
				INNER JOIN mastpersonas p ON (p.CodPersona = c.CodPersona)
				INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
				INNER JOIN rh_puestos pt ON (pt.CodCargo = e.CodCargo)
				INNER JOIN mastdependencias d ON (d.CodDependencia = e.CodDependencia)
				INNER JOIN rh_tipocontrato tc ON (tc.TipoContrato = c.TipoContrato)
			WHERE
				(DATEDIFF(c.FechaHasta, NOW()) >= 0 OR DATEDIFF(c.FechaHasta, NOW()) IS NULL)
				$filtro";
	$query_lista = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	$rows_total = mysql_num_rows($query_lista);
	
	//	consulto lista
	$sql = "SELECT
				c.CodOrganismo,
				c.CodPersona,
				c.Secuencia,
				c.FechaDesde,
				c.FechaHasta,
				c.FlagFirma,
				c.FechaFirma,
				p.NomCompleto,
				e.CodEmpleado,
				e.Fingreso,
				pt.DescripCargo,
				d.Dependencia,
				tc.Descripcion AS NomTipoContrato,
				DATEDIFF(c.FechaHasta, NOW()) AS Faltan
			FROM
				rh_contratos c
				INNER JOIN mastpersonas p ON (p.CodPersona = c.CodPersona)
				INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
				INNER JOIN rh_puestos pt ON (pt.CodCargo = e.CodCargo)
				INNER JOIN mastdependencias d ON (d.CodDependencia = e.CodDependencia)
				INNER JOIN rh_tipocontrato tc ON (tc.TipoContrato = c.TipoContrato)
			WHERE
				(DATEDIFF(c.FechaHasta, NOW()) >= 0 OR DATEDIFF(c.FechaHasta, NOW()) IS NULL)
				$filtro
			ORDER BY CodEmpleado
			LIMIT ".intval($limit).", ".intval($maxlimit);
	$query_lista = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	$rows_lista = mysql_num_rows($query_lista);	$i=0;
	while ($field_lista = mysql_fetch_array($query_lista)) {	$i++;
		$id = "$field_lista[CodOrganismo].$field_lista[CodPersona].$field_lista[Secuencia]";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<th><?=$i?></th>
			<td align="center"><?=$field_lista['CodEmpleado']?></td>
			<td><?=$field_lista['NomCompleto']?></td>
			<td align="center"><?=$field_lista['NomTipoContrato']?></td>
			<td align="center" width="60"><?=formatFechaDMA($field_lista['FechaDesde'])?></td>
			<td align="center" width="60"><?=formatFechaDMA($field_lista['FechaHasta'])?></td>
			<td align="center"><?=printFlag($field_lista['FlagFirma'])?></td>
			<td align="center"><?=formatFechaDMA($field_lista['FechaFirma'])?></td>
			<td align="center"><?=$field_lista['Faltan']?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
<table width="100%">
	<tr>
    	<td width="150">
        	Mostrar: 
            <select name="maxlimit" style="width:50px;" class="ui-corner-all" onchange="this.form.submit();">
                <?=loadSelectGeneral("MAXLIMIT", $maxlimit, 0)?>
            </select>
        </td>
        <td>
        	<span id="rows_lista">Contratos Vencidos: <?=$rows_total?></span>
        </td>
        <td align="right">
        	<?=paginacion(intval($rows_total), intval($rows_lista), intval($maxlimit), intval($limit));?>
        </td>
    </tr>
</table>
</center>
</form>