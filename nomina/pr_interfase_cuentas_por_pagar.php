<?php
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["ORGANISMO_ACTUAL"];
	$fCodTipoNom = $_SESSION["NOMINA_ACTUAL"];
	$fPeriodo = "$AnioActual-$MesActual";
	//	proceso por defecto
	$sql = "SELECT CodTipoProceso
			FROM pr_procesoperiodo
			WHERE
				Periodo = '".$fPeriodo."' AND
				CodTipoNom = '".$fCodTipoNom."' AND
				CodOrganismo = '".$fCodOrganismo."' AND
				Estado = 'A' AND
				FlagProcesado = 'N' AND
				FlagAprobado = 'S'
			GROUP BY Periodo
			ORDER BY Periodo DESC
			LIMIT 0, 1";
	$query_def = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_def) != 0) $field_def = mysql_fetch_array($query_def);
	$fCodTipoProceso = $field_def['CodTipoProceso'];
}
if ($fCodOrganismo != "") { $cCodOrganismo = "checked"; $filtro.=" AND (o.CodOrganismo = '".$fCodOrganismo."')"; } else $dCodOrganismo = "disabled";
if ($fCodTipoNom != "") { $cCodTipoNom = "checked"; $filtro.=" AND (o.CodTipoNom = '".$fCodTipoNom."')"; } else $dCodTipoNom = "disabled";
if ($fCodTipoProceso != "") { $cCodTipoProceso = "checked"; $filtro.=" AND (o.CodTipoProceso = '".$fCodTipoProceso."')"; } else $dCodTipoProceso = "disabled";
if ($fPeriodo != "") { $cPeriodo = "checked"; $filtro.=" AND (o.PeriodoNomina = '".$fPeriodo."')"; } else $dPeriodo = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Interfase Cuentas x Pagar</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=pr_interfase_cuentas_por_pagar" method="post" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td align="right" width="100">Organismo:</td>
		<td>
			<input type="checkbox" <?=$cCodOrganismo?> onclick="this.checked=!this.checked" />
			<select name="fCodOrganismo" id="fCodOrganismo" style="width:270px;" onChange="loadSelectPeriodosNomina(1);" <?=$dCodOrganismo?>>
				<?=getOrganismos($fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right" width="100">N&oacute;mina:</td>
		<td>
			<input type="checkbox" <?=$cCodTipoNom?> onclick="this.checked=!this.checked" />
			<select name="fCodTipoNom" id="fCodTipoNom" style="width:270px;" onChange="loadSelectPeriodosNomina(1);" <?=$dCodTipoNom?>>
				<?=loadSelect("tiponomina", "CodTipoNom", "Nomina", $fCodTipoNom, 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Periodo:</td>
		<td>
			<input type="checkbox" <?=$cPeriodo?> onclick="this.checked=!this.checked" />
			<select name="fPeriodo" id="fPeriodo" style="width:75px;" onChange="loadSelectPeriodosNominaProcesos(1);" <?=$dPeriodo?>>
            	<?=loadSelectPeriodosNomina($fPeriodo, $fCodOrganismo, $fCodTipoNom, 1)?>
			</select>
		</td>
		<td align="right">Proceso:</td>
		<td>
			<input type="checkbox" <?//=$cCodTipoProceso?> onclick="this.checked=this.checked" />
			<select name="fCodTipoProceso" id="fCodTipoProceso" style="width:270px;" <?//=$dCodTipoProceso?>>
            	<?=loadSelectPeriodosNominaProcesos($fCodTipoProceso, $fPeriodo, $fCodOrganismo, $fCodTipoNom, 1)?>
			</select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center>
</form>
<br />

<center>
<table width="1000" class="tblBotones">
    <tr>
        <td align="right">
        	<input type="button" value="Calcular Obligaciones" style="width:130px;" onclick="interfase_cuentas_por_pagar_calcular();" />
        </td>
    </tr>
</table>
<table width="1000" align="center" cellpadding="0" cellspacing="0">
	<tr>
    	<td>
            <div class="header" style="width:1000px;">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current"><a href="#" onclick="mostrarTab('tab', 1, 4);">Interfase Bancaria</a></li>
            <li id="li2" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 2, 4);">Cheques</a></li>
            <li id="li3" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 3, 4);">Pago a Terceros</a></li>
            <li id="li4" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 4, 4);">Retenciones Judiciales</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<div id="tab1" style="display:block;">
<table width="1000" class="tblBotones">
    <tr>
        <td class="gallery clearfix">
        	<a id="a_check" href="pagina.php?iframe=true&width=500&height=500" rel="prettyPhoto[iframe1]" style="display:none;"></a>
        	<input type="button" value="Verificar Presupuesto" style="width:130px;" onclick="interfase_cuentas_por_pagar_abrir_check('bancos');" />
        	<input type="button" value="Consolidar Obligaciones" style="width:130px;" onclick="interfase_cuentas_por_pagar_consolidar('bancos');" />
        </td>
        <td align="right">
        	<input type="button" value="Generar Obligaciones" style="width:130px;" onclick="interfase_cuentas_por_pagar_generar('bancos');" />
        </td>
    </tr>
</table>
<form name="frm_bancos" id="frm_bancos">
<div style="overflow:scroll; width:1000px; height:350px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="50">Proveedor</th>
        <th scope="col">Nombre del Proveedor</th>
        <th scope="col" width="15">Doc.</th>
        <th scope="col" width="125">Nro. Documento</th>
        <th scope="col" width="50">Nro. Registro</th>
        <th scope="col" width="75">Fecha Registro</th>
        <th scope="col" width="25">Trf.</th>
        <th scope="col" width="25">Ver.</th>
        <th scope="col" width="100">Monto Obligaci&oacute;n</th>
    </tr>
    </thead>
    
    <tbody>
    <?php
    //	consulto lista
    $sql = "SELECT
				o.CodProveedor,
				o.NroDocumento,
				o.NroRegistro,
				o.FechaRegistro,
				o.FlagTransferido,
				o.MontoObligacion,
				o.TipoObligacion,
				o.FlagVerificado,
				o.TipoObligacion,
				mp.NomCompleto AS NomProveedor,
				td.CodTipoDocumento,
				td.Descripcion AS NomTipoDocumento
			FROM
				pr_obligaciones o
				INNER JOIN mastpersonas mp ON (o.CodProveedor = mp.CodPersona)
				INNER JOIN ap_tipodocumento td ON (o.CodTipoDocumento = td.CodTipoDocumento)
			WHERE o.TipoObligacion = '01' $filtro
			ORDER BY NomProveedor";
    $query_bancos = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
    while ($field_bancos = mysql_fetch_array($query_bancos)) {
        $id = "$field_bancos[CodProveedor]_$field_bancos[CodTipoDocumento]_$field_bancos[NroDocumento]_$field_bancos[TipoObligacion]";
        ?>
        <tr class="trListaBody" onclick="clkMulti($(this), 'bancos_<?=$id?>');">
            <td align="center">
            	<input type="checkbox" name="bancos" id="bancos_<?=$id?>" value="<?=$id?>" style="display:none;" />
                <input type="hidden" name="FlagVerificado" value="<?=$field_bancos['FlagVerificado']?>" />
				<?=$field_bancos['CodProveedor']?>
            </td>
            <td><?=($field_bancos['NomProveedor'])?></td>
            <td align="center"><?=$field_bancos['CodTipoDocumento']?></td>
            <td><?=$field_bancos['NroDocumento']?></td>
            <td align="center"><?=$field_bancos['NroRegistro']?></td>
            <td align="center"><?=formatFechaDMA($field_bancos['FechaRegistro'])?></td>
            <td align="center"><?=printFlag($field_bancos['FlagTransferido'])?></td>
            <td align="center"><?=printFlag($field_bancos['FlagVerificado'])?></td>
            <td align="right"><strong><?=number_format($field_bancos['MontoObligacion'], 2, ',', '.')?></strong></td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
</form>
</div>

<div id="tab2" style="display:none;">
<table width="1000" class="tblBotones">
    <tr>
        <td class="gallery clearfix">
        	<a id="a_check" href="pagina.php?iframe=true&width=500&height=500" rel="prettyPhoto[iframe2]" style="display:none;"></a>
        	<input type="button" value="Verificar Presupuesto" style="width:130px;" onclick="interfase_cuentas_por_pagar_abrir_check('cheques');" />
        </td>
        <td align="right">
        	<input type="button" value="Generar Obligaciones" style="width:130px;" onclick="interfase_cuentas_por_pagar_generar('cheques');" />
        </td>
    </tr>
</table>
<form name="frm_cheques" id="frm_cheques">
<div style="overflow:scroll; width:1000px; height:350px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="50">Proveedor</th>
        <th scope="col">Nombre del Proveedor</th>
        <th scope="col" width="15">Doc.</th>
        <th scope="col" width="125">Nro. Documento</th>
        <th scope="col" width="50">Nro. Registro</th>
        <th scope="col" width="75">Fecha Registro</th>
        <th scope="col" width="25">Trf.</th>
        <th scope="col" width="100">Monto Obligaci&oacute;n</th>
    </tr>
    </thead>
    
    <tbody>
    <?php
    //	consulto lista
    $sql = "SELECT
				o.CodProveedor,
				o.NroDocumento,
				o.NroRegistro,
				o.FechaRegistro,
				o.FlagTransferido,
				o.MontoObligacion,
				o.FlagVerificado,
				o.TipoObligacion,
				mp.NomCompleto AS NomProveedor,
				td.Descripcion AS NomTipoDocumento,
				td.CodTipoDocumento
			FROM
				pr_obligaciones o
				INNER JOIN mastpersonas mp ON (o.CodProveedor = mp.CodPersona)
				INNER JOIN ap_tipodocumento td ON (o.CodTipoDocumento = td.CodTipoDocumento)
			WHERE o.TipoObligacion = '02' $filtro
			ORDER BY NomProveedor";
    $query_cheques = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
    while ($field_cheques = mysql_fetch_array($query_cheques)) {
        $id = "$field_cheques[CodProveedor]_$field_cheques[CodTipoDocumento]_$field_cheques[NroDocumento]_$field_cheques[TipoObligacion]";
        ?>
        <tr class="trListaBody" onclick="clkMulti($(this), 'cheques_<?=$id?>');">
            <td align="center">
            	<input type="checkbox" name="cheques" id="cheques_<?=$id?>" value="<?=$id?>" style="display:none;" />
                <input type="hidden" name="FlagVerificado" value="<?=$field_cheques['FlagVerificado']?>" />
				<?=$field_cheques['CodProveedor']?>
            </td>
            <td><?=htmlentities($field_cheques['NomProveedor'])?></td>
            <td align="center"><?=$field_cheques['CodTipoDocumento']?></td>
            <td><?=$field_cheques['NroDocumento']?></td>
            <td align="center"><?=$field_cheques['NroRegistro']?></td>
            <td align="center"><?=formatFechaDMA($field_cheques['FechaRegistro'])?></td>
            <td align="center"><?=printFlag($field_cheques['FlagTransferido'])?></td>
            <td align="right"><strong><?=number_format($field_cheques['MontoObligacion'], 2, ',', '.')?></strong></td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
</form>
</div>

<div id="tab3" style="display:none;">
<table width="1000" class="tblBotones">
    <tr>
        <td class="gallery clearfix">
        	<a id="a_check" href="pagina.php?iframe=true&width=500&height=500" rel="prettyPhoto[iframe3]" style="display:none;"></a>
        	<input type="button" value="Verificar Presupuesto" style="width:130px;" onclick="interfase_cuentas_por_pagar_abrir_check('terceros');" />
        </td>
        <td align="right">
        	<input type="button" value="Generar Obligaciones" style="width:130px;" onclick="interfase_cuentas_por_pagar_generar('terceros');" />
        </td>
    </tr>
</table>
<form name="frm_terceros" id="frm_terceros">
<div style="overflow:scroll; width:1000px; height:350px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="50">Proveedor</th>
        <th scope="col">Nombre del Proveedor</th>
        <th scope="col" width="15">Doc.</th>
        <th scope="col" width="125">Nro. Documento</th>
        <th scope="col" width="50">Nro. Registro</th>
        <th scope="col" width="75">Fecha Registro</th>
        <th scope="col" width="25">Trf.</th>
        <th scope="col" width="100">Monto Obligaci&oacute;n</th>
    </tr>
    </thead>
    
    <tbody>
    <?php
    //	consulto lista
    $sql = "SELECT
				o.CodProveedor,
				o.NroDocumento,
				o.NroRegistro,
				o.FechaRegistro,
				o.FlagTransferido,
				o.MontoObligacion,
				o.FlagVerificado,
				o.TipoObligacion,
				mp.NomCompleto AS NomProveedor,
				td.Descripcion AS NomTipoDocumento,
				td.CodTipoDocumento
			FROM
				pr_obligaciones o
				INNER JOIN mastpersonas mp ON (o.CodProveedor = mp.CodPersona)
				INNER JOIN ap_tipodocumento td ON (o.CodTipoDocumento = td.CodTipoDocumento)
			WHERE o.TipoObligacion = '03' $filtro
			ORDER BY NomProveedor";
    $query_terceros = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
    while ($field_terceros = mysql_fetch_array($query_terceros)) {
        $id = "$field_terceros[CodProveedor]_$field_terceros[CodTipoDocumento]_$field_terceros[NroDocumento]_$field_terceros[TipoObligacion]";
        ?>
        <tr class="trListaBody" onclick="clkMulti($(this), 'terceros_<?=$id?>');">
            <td align="center">
            	<input type="checkbox" name="terceros" id="terceros_<?=$id?>" value="<?=$id?>" style="display:none;" />
                <input type="hidden" name="FlagVerificado" value="<?=$field_terceros['FlagVerificado']?>" />
				<?=$field_terceros['CodProveedor']?>
            </td>
            <td><?=htmlentities($field_terceros['NomProveedor'])?></td>
            <td align="center"><?=$field_terceros['CodTipoDocumento']?></td>
            <td><?=$field_terceros['NroDocumento']?></td>
            <td align="center"><?=$field_terceros['NroRegistro']?></td>
            <td align="center"><?=formatFechaDMA($field_terceros['FechaRegistro'])?></td>
            <td align="center"><?=printFlag($field_terceros['FlagTransferido'])?></td>
            <td align="right"><strong><?=number_format($field_terceros['MontoObligacion'], 2, ',', '.')?></strong></td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
</form>
</div>

<div id="tab4" style="display:none;">
<table width="1000" class="tblBotones">
    <tr>
        <td class="gallery clearfix">
        </td>
        <td align="right">
        	<input type="button" value="Generar Obligaciones" style="width:130px;" onclick="interfase_cuentas_por_pagar_generar('judiciales');" />
        </td>
    </tr>
</table>
<form name="frm_judiciales" id="frm_judiciales">
<div style="overflow:scroll; width:1000px; height:350px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="50">Proveedor</th>
        <th scope="col">Nombre del Proveedor</th>
        <th scope="col" width="15">Doc.</th>
        <th scope="col" width="125">Nro. Documento</th>
        <th scope="col" width="50">Nro. Registro</th>
        <th scope="col" width="75">Fecha Registro</th>
        <th scope="col" width="25">Trf.</th>
        <th scope="col" width="100">Monto Obligaci&oacute;n</th>
    </tr>
    </thead>
    
    <tbody>
    <?php
    //	consulto lista
    $sql = "SELECT
				o.CodProveedor,
				o.NroDocumento,
				o.NroRegistro,
				o.FechaRegistro,
				o.FlagTransferido,
				o.MontoObligacion,
				o.FlagVerificado,
				o.TipoObligacion,
				mp.NomCompleto AS NomProveedor,
				td.Descripcion AS NomTipoDocumento,
				td.CodTipoDocumento
			FROM
				pr_obligaciones o
				INNER JOIN mastpersonas mp ON (o.CodProveedor = mp.CodPersona)
				INNER JOIN ap_tipodocumento td ON (o.CodTipoDocumento = td.CodTipoDocumento)
			WHERE o.TipoObligacion = '04' $filtro
			ORDER BY NomProveedor";
    $query_judiciales = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
    while ($field_judiciales = mysql_fetch_array($query_judiciales)) {
        $id = "$field_judiciales[CodProveedor]_$field_judiciales[CodTipoDocumento]_$field_judiciales[NroDocumento]_$field_judiciales[TipoObligacion]";
        ?>
        <tr class="trListaBody" onclick="clkMulti($(this), 'judiciales_<?=$id?>');">
            <td align="center">
            	<input type="checkbox" name="judiciales" id="judiciales_<?=$id?>" value="<?=$id?>" style="display:none;" />
                <input type="hidden" name="FlagVerificado" value="<?=$field_judiciales['FlagVerificado']?>" />
				<?=$field_judiciales['CodProveedor']?>
            </td>
            <td><?=htmlentities($field_judiciales['NomProveedor'])?></td>
            <td align="center"><?=$field_judiciales['CodTipoDocumento']?></td>
            <td><?=$field_judiciales['NroDocumento']?></td>
            <td align="center"><?=$field_judiciales['NroRegistro']?></td>
            <td align="center"><?=formatFechaDMA($field_judiciales['FechaRegistro'])?></td>
            <td align="center"><?=printFlag($field_judiciales['FlagTransferido'])?></td>
            <td align="right"><strong><?=number_format($field_judiciales['MontoObligacion'], 2, ',', '.')?></strong></td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
</form>
</div>

</center>
