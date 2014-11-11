<?php
if ($lista == "todos") {
	$titulo = "Lista de Prorrogas";
	$btRevisar = "display:none;";
	$btAprobar = "display:none;";
}
elseif ($lista == "revisar") {
	$titulo = "Revisar Prorrogas";
	$btNuevo = "display:none;";
	$btModificar = "display:none;";
	$btAprobar = "display:none;";
}
elseif ($lista == "aprobar") {
	$titulo = "Aprobar Prorrogas";
	$btNuevo = "display:none;";
	$btModificar = "display:none;";
	$btRevisar = "display:none;";
}
//	------------------------------------
if ($filtrar == "default") {
	$fordenar = "p.CodProrroga";
	$maxlimit = $_SESSION["MAXLIMIT"];
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fnomorganismo = $_SESSION["FILTRO_NOMBRE_ORGANISMO_ACTUAL"];
	if ($lista == "todos") {
		$fdependencia = $_SESSION["FILTRO_DEPENDENCIA_ACTUAL"];
		$fnomdependencia = $_SESSION["FILTRO_NOMBRE_DEPENDENCIA_ACTUAL"];
		$fanio = date("Y");
	}
	elseif ($lista == "revisar") $fedoreg = "PR";
	elseif ($lista == "aprobar") $fedoreg = "RV";
}
if ($fordenar != "") { $cordenar = "checked"; $orderby = "ORDER BY $fordenar"; } else $dordenar = "disabled";
if ($fedoreg != "") { $cedoreg = "checked"; $filtro.=" AND (p.Estado = '".$fedoreg."')"; } else $dedoreg = "disabled";
if ($fbuscar != "") {
	$cbuscar = "checked";
	$filtro.=" AND (p.CodProrroga LIKE '%".$fbuscar."%' OR
					a.Descripcion LIKE '%".$fbuscar."%' OR
					p.Motivo LIKE '%".$fbuscar."%' OR
					p.FechaRegistro LIKE '%".$fbuscar."%' OR
					p.Prorroga LIKE '%".$fbuscar."%')";
} else $dbuscar = "disabled";
if ($forganismo != "") { $corganismo = "checked"; $filtro.=" AND (af.CodOrganismo = '".$forganismo."')"; } else $dorganismo = "disabled";
if ($fdependencia != "") { $cdependencia = "checked"; $filtro.=" AND (af.CodDependencia = '".$fdependencia."')"; } else $ddependencia = "disabled";
if ($fregistrod != "" || $fregistroh != "") {
	$cregistro = "checked";
	if ($fregistrod != "") $filtro.=" AND (p.FechaRegistro >= '".$fregistrod."')";
	if ($fregistroh != "") $filtro.=" AND (p.FechaRegistro <= '".$fregistroh."')";
} else $dregistro = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=pf_actuacion_fiscal_prorrogas_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder gallery clearfix" style="width:1050px;">
<table width="1050" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
            <input type="checkbox" <?=$corganismo?> onclick="this.checked=!this.checked;">
            <select name="forganismo" id="forganismo" style="width:300px;" onchange="getOptionsSelect(this.value, 'dependencia_fiscal', 'fdependencia', true);" <?=$dorganismo?>>
                <?=getOrganismos($forganismo, 3);?>
            </select>            
		</td>
		<td align="right" width="125">Fecha de Registro:</td>
        <td>
            <input type="checkbox" <?=$cregistro?> onclick="chkFiltro_2(this.checked, 'fregistrod', 'fregistroh');" />
            <input type="text" name="fregistrod" id="fregistrod" value="<?=$fregistrod?>" style="width:75px;" maxlength="10" class="datepicker" onkeyup="setFechaDMA(this);" <?=$dregistro?> /> - 
            <input type="text" name="fregistroh" id="fregistroh" value="<?=$fregistroh?>" style="width:75px;" maxlength="10" class="datepicker" onkeyup="setFechaDMA(this);" <?=$dregistro?> />
		</td>
	</tr>
	<tr>
		<td align="right">Dependencia:</td>
		<td>
            <input type="checkbox" <?=$cdependencia?> onclick="chkFiltro(this.checked, 'fdependencia');" />
            <select name="fdependencia" id="fdependencia" style="width:300px;" <?=$ddependencia?> onchange="getOptionsSelect(this.value, 'centro_costo', 'fccosto', true);">
                <option value="">&nbsp;</option>
                <?=loadDependenciaFiscal($fdependencia, $forganismo, 0)?>
            </select>            
		</td>
		<td align="right">Estado Reg.:</td>
		<td>
        	<? if ($lista == "todos") { ?>
                <input type="checkbox" <?=$cedoreg?> onclick="chkFiltro(this.checked, 'fedoreg');" />
                <select name="fedoreg" id="fedoreg" style="width:100px;" <?=$dedoreg?>>
                    <option value="">&nbsp;</option>
                    <?=loadSelectValores("ESTADO-ACTUACION-PRORROGA", $fedoreg, 0)?>
                </select>
            <? } 
			else { ?>
                <input type="checkbox" <?=$cedoreg?> onclick="this.checked=!this.checked;" />
                <select name="fedoreg" id="fedoreg" style="width:100px;" <?=$dedoreg?>>
                    <?=loadSelectValores("ESTADO-ACTUACION-PRORROGA", $fedoreg, 1)?>
                </select>
            <? } ?>
		</td>
	</tr>
	<tr>
		<td align="right">Buscar:</td>
        <td>
            <input type="checkbox" <?=$cbuscar?> onclick="chkFiltro(this.checked, 'fbuscar');" />
            <input type="text" name="fbuscar" id="fbuscar" style="width:200px;" value="<?=$fbuscar?>" <?=$dbuscar?> />
		</td>
		<td align="right">Ordenar Por:</td>
		<td>
            <input type="checkbox" <?=$cordenar?> onclick="this.checked=!this.checked;" />
            <select name="fordenar" id="fordenar" style="width:100px;" <?=$dordenar?>>
                <?=loadSelectValores("ORDENAR-ACTUACION-PRORROGA", $fordenar, 0)?>
            </select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="1050" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=pf_actuacion_fiscal_prorrogas_form&opcion=nuevo&action=pf_actuacion_fiscal_prorrogas_lista');" />
            
			<input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=actuacion_fiscal_prorrogas_modificar', 'gehen.php?anz=pf_actuacion_fiscal_prorrogas_form&opcion=modificar&action=pf_actuacion_fiscal_prorrogas_lista', 'SELF', '');" />
            
			<input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=pf_actuacion_fiscal_prorrogas_form&opcion=ver', 'BLANK', 'height=600, width=900, left=100, top=0, resizable=no', $('#registro').val());" />
            
			<input type="button" id="btRevisar" value="Revisar" style="width:75px; <?=$btRevisar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=pf_actuacion_fiscal_prorrogas_form&opcion=revisar&action=pf_actuacion_fiscal_prorrogas_lista', 'SELF', '', 'registro');" />
            
			<input type="button" id="btAprobar" value="Aprobar" style="width:75px; <?=$btAprobar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=pf_actuacion_fiscal_prorrogas_form&opcion=aprobar&action=pf_actuacion_fiscal_prorrogas_lista', 'SELF', '', 'registro');" />
            
			<input type="button" id="btAnular" value="Anular" style="width:75px; <?=$btAnular?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=actuacion_fiscal_prorrogas_anular', 'gehen.php?anz=pf_actuacion_fiscal_prorrogas_form&opcion=anular&action=pf_actuacion_fiscal_prorrogas_lista', 'SELF', '');" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:1050px; height:300px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th scope="col" width="125">Prorroga</th>
		<th scope="col">Motivo</th>
		<th scope="col" width="50">Dias</th>
		<th scope="col" width="75">F.Registro</th>
		<th scope="col" width="100">Estado</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				p.CodProrroga,
				p.CodActuacion,
				p.FechaRegistro,
				p.Prorroga,
				p.Motivo,
				p.Estado,
				a.Descripcion AS NomActividad
			FROM
				pf_prorroga p
				INNER JOIN pf_actuacionfiscal af ON (p.CodActuacion = af.CodActuacion)
				INNER JOIN pf_actividades a ON (p.CodActividad = a.CodActividad)
				INNER JOIN seguridad_alterna sa ON (af.CodDependencia = sa.CodDependencia AND
													sa.CodAplicacion = '".$_SESSION["APLICACION_ACTUAL"]."' AND
													sa.Usuario = '".$_SESSION["USUARIO_ACTUAL"]."' AND
													sa.FlagMostrar = 'S')
			WHERE 1 $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				p.CodProrroga,
				p.CodActuacion,
				p.FechaRegistro,
				p.Prorroga,
				p.Motivo,
				p.Estado,
				a.Descripcion AS NomActividad
			FROM
				pf_prorroga p
				INNER JOIN pf_actuacionfiscal af ON (p.CodActuacion = af.CodActuacion)
				INNER JOIN pf_actividades a ON (p.CodActividad = a.CodActividad)
				INNER JOIN seguridad_alterna sa ON (af.CodDependencia = sa.CodDependencia AND
													sa.CodAplicacion = '".$_SESSION["APLICACION_ACTUAL"]."' AND
													sa.Usuario = '".$_SESSION["USUARIO_ACTUAL"]."' AND
													sa.FlagMostrar = 'S')
			WHERE 1 $filtro
			$orderby
			LIMIT ".intval($limit).", $maxlimit";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$field['CodProrroga']?>">
			<td align="center"><?=$field['CodProrroga']?></td>
			<td>
            	<strong><?=$field['NomActividad']?></strong><br />
				<?=$field['Motivo']?>
            </td>
			<td align="center"><?=$field['Prorroga']?></td>
			<td align="center"><?=formatFechaDMA($field['FechaRegistro'])?></td>
			<td align="center"><?=printValores("ESTADO-ACTUACION-PRORROGA", $field['Estado'])?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
<table width="1050">
	<tr>
    	<td>
        	Mostrar: 
            <select name="maxlimit" style="width:50px;" onchange="this.form.submit();">
                <?=loadSelectGeneral("MAXLIMIT", $maxlimit, 0)?>
            </select>
        </td>
        <td align="right">
        	<?=paginacion(intval($rows_total), intval($rows_lista), intval($maxlimit), intval($limit));?>
        </td>
    </tr>
</table>
</center>
</form>
