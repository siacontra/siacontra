<?php
if ($lista == "todos") {
	$titulo = "Lista de Potestad Investigativa";
	$btRevisar = "display:none;";
	$btAprobar = "display:none;";
	$btGenerar = "display:none;";
}
elseif ($lista == "revisar") {
	$titulo = "Revisar Potestad Investigativa";
	$btModificar = "display:none;";
	$btAprobar = "display:none;";
	$btGenerar = "display:none;";
}
elseif ($lista == "aprobar") {
	$titulo = "Aprobar Potestad Investigativa";
	$btModificar = "display:none;";
	$btRevisar = "display:none;";
	$btGenerar = "display:none;";
}
elseif ($lista == "generar") {
	$titulo = "Generar Determinaci&oacute;n de Responsabilidad";
	$btModificar = "display:none;";
	$btRevisar = "display:none;";
	$btAprobar = "display:none;";
	$btCerrar = "display:none;";
}
//	------------------------------------
if ($filtrar == "default") {
	$fordenar = "vj.Anio, vj.Secuencia, vj.CodPotestad";
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
if ($fedoreg != "") {
	$cedoreg = "checked";
	$filtro.=" AND (vj.Estado = '".$fedoreg."')";
} else {
	$dedoreg = "disabled";
	if ($lista == "generar") $filtro.=" AND (vj.Estado = 'AP' OR vj.Estado = 'TE' OR vj.Estado = 'CO') AND (vj.EstadoDeterminacion = 'PE')";
}
if ($fbuscar != "") {
	$cbuscar = "checked";
	$filtro.=" AND (vj.CodPotestad LIKE '%".$fbuscar."%' OR
					oe.Organismo LIKE '%".$fbuscar."%' OR
					de.Dependencia LIKE '%".$fbuscar."%' OR
					vj.ObjetivoGeneral LIKE '%".$fbuscar."%')";
} else $dbuscar = "disabled";
if ($forganismo != "") { $corganismo = "checked"; $filtro.=" AND (vj.CodOrganismo = '".$forganismo."')"; } else $dorganismo = "disabled";
if ($fdependencia != "") { $cdependencia = "checked"; $filtro.=" AND (vj.CodDependencia = '".$fdependencia."')"; } else $ddependencia = "disabled";
if ($fccosto != "") { $cccosto = "checked"; $filtro.=" AND (vj.CodCentroCosto = '".$fccosto."')"; } else $dccosto = "disabled";
if ($forganismoext != "") { $corganismoext = "checked"; $filtro.=" AND (vj.CodOrganismoExterno = '".$forganismoext."')"; } 
else $dorganismoext = "visibility:hidden;";
if ($fdependenciaext != "") { $cdependenciaext = "checked"; $filtro.=" AND (vj.CodDependenciaExterna = '".$fdependenciaext."')"; } 
else $ddependenciaext = "visibility:hidden;";
if ($fregistrod != "" || $fregistroh != "") {
	$cregistro = "checked";
	if ($fregistrod != "") $filtro.=" AND (vj.FechaRegistro >= '".formatFechaAMD($fregistrod)."')";
	if ($fregistroh != "") $filtro.=" AND (vj.FechaRegistro <= '".formatFechaAMD($fregistroh)."')";
} else $dregistro = "disabled";
if ($fanio != "") { $canio = "checked"; $filtro.=" AND (vj.Anio = '".$fanio."')"; } else $danio = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=pf_potestad_investigativa_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:1050px;">
<table width="1050" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
            <input type="checkbox" <?=$corganismo?> onclick="this.checked=!this.checked;">
            <select name="forganismo" id="forganismo" style="width:300px;" <?=$dorganismo?> onchange="getOptionsSelect(this.value, 'dependencia_fiscal', 'fdependencia', true, 'fccosto');">
                <?=getOrganismos($forganismo, 0);?>
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
                    <?=loadSelectGeneral("ESTADO-VALORACION", $fedoreg, 0)?>
                </select>
            <? } 
			elseif ($lista == "generar") { ?>
                <input type="checkbox" <?=$cedoreg?> onclick="chkFiltro(this.checked, 'fedoreg');" />
                <select name="fedoreg" id="fedoreg" style="width:100px;" <?=$dedoreg?>>
                    <option value="">&nbsp;</option>
                    <?=loadSelectGeneral("ESTADO-ACTUACION-GENERAR", $fedoreg, 0)?>
                </select>
            <? } 
			else { ?>
                <input type="checkbox" <?=$cedoreg?> onclick="this.checked=!this.checked;" />
                <select name="fedoreg" id="fedoreg" style="width:100px;" <?=$dedoreg?>>
                    <?=loadSelectGeneral("ESTADO-VALORACION", $fedoreg, 1)?>
                </select>
            <? } ?>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Centro de Costo:</td>
		<td>
            <input type="checkbox" <?=$cccosto?> onclick="chkFiltro(this.checked, 'fccosto');" />
            <select name="fccosto" id="fccosto" style="width:300px;" <?=$dccosto?>>
            	<option value="">&nbsp;</option>
                <?=loadSelectDependiente("ac_mastcentrocosto", "CodCentroCosto", "Descripcion", "CodDependencia", $fccosto, $fdependencia, 0);?>
            </select>
		</td>
		<td align="right">Buscar:</td>
        <td>
            <input type="checkbox" <?=$cbuscar?> onclick="chkFiltro(this.checked, 'fbuscar');" />
            <input type="text" name="fbuscar" id="fbuscar" style="width:200px;" value="<?=$fbuscar?>" <?=$dbuscar?> />
		</td>
	</tr>
	<tr>
		<td align="right">Ente Externo:</td>
		<td class="gallery clearfix">
            <input type="checkbox" <?=$corganismoext?> onclick="chkFiltroLista_2(this.checked, 'forganismoext', 'fnomorganismoext', 'fdependenciaext', 'fnomdependenciaext', 'btEnteExt');" />
            <input type="hidden" name="forganismoext" id="forganismoext" value="<?=$forganismoext?>" />
			<input type="text" name="fnomorganismoext" id="fnomorganismoext" style="width:295px;" value="<?=$fnomorganismoext?>" readonly="readonly" />
            <a href="../lib/listas/listado_organismos_externos.php?filtrar=default&&cod=forganismoext&nom=fnomorganismoext&iframe=true&width=950&height=525" rel="prettyPhoto[iframe2]" style=" <?=$dorganismoext?>" id="btEnteExt">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
		<td align="right">A&ntilde;o Fiscal:</td>
        <td>
            <input type="checkbox" <?=$canio?> onclick="chkFiltro(this.checked, 'fanio');" />
            <input type="text" name="fanio" id="fanio" value="<?=$fanio?>" style="width:50px;" maxlength="4" <?=$danio?> />
		</td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td>
            <input type="checkbox" style="visibility:hidden;" />
            <input type="hidden" name="fdependenciaext" id="fdependenciaext" value="<?=$fdependenciaext?>" />
			<input type="text" name="fnomdependenciaext" id="fnomdependenciaext" style="width:295px;" value="<?=$fnomdependenciaext?>" readonly="readonly" />
		</td>
		<td align="right">Ordenar Por:</td>
		<td>
            <input type="checkbox" <?=$cordenar?> onclick="this.checked=!this.checked;" />
            <select name="fordenar" id="fordenar" style="width:100px;" <?=$dordenar?>>
                <?=loadSelectGeneral("ORDENAR-VALORACION", $fordenar, 0)?>
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
			<input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=potestad_investigativa_modificar', 'gehen.php?anz=pf_potestad_investigativa_form&opcion=modificar&action=pf_potestad_investigativa_lista', 'SELF', '');" />
            
			<input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=pf_potestad_investigativa_form&opcion=ver', 'BLANK', 'height=700, width=900, left=100, top=0, resizable=no', $('#registro').val());" /> | 
            
			<input type="button" id="btRevisar" value="Revisar" style="width:75px; <?=$btRevisar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=pf_potestad_investigativa_form&opcion=revisar&action=pf_potestad_investigativa_lista', 'SELF', '', 'registro');" />
            
			<input type="button" id="btAprobar" value="Aprobar" style="width:75px; <?=$btAprobar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=pf_potestad_investigativa_form&opcion=aprobar&action=pf_potestad_investigativa_lista', 'SELF', '', 'registro');" />
            
			<input type="button" id="btCerrar" value="Cerrar" style="width:75px; <?=$btCerrar?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=potestad_investigativa_cerrar', 'gehen.php?anz=pf_potestad_investigativa_form&opcion=cerrar&action=pf_potestad_investigativa_lista', 'SELF', '');" />
            
			<input type="button" id="btGenerar" value="Generar" style="width:75px; <?=$btGenerar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=pf_determinacion_responsabilidad_form&opcion=generar&action=pf_potestad_investigativa_lista', 'SELF', '', 'registro');" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:1050px; height:300px;">
<table width="1900" class="tblLista">
	<thead>
	<tr>
		<th scope="col" width="125">Valoraci&oacute;n</th>
		<th scope="col">Ente</th>
		<th scope="col" width="75">F.Registro</th>
		<th scope="col" width="75">F.Inicio</th>
		<th scope="col" width="75">F.Termino</th>
		<th scope="col" width="75">F.Termino Real</th>
		<th scope="col" width="100">Estado</th>
		<th scope="col" width="800">Objetivo General</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				vj.CodPotestad,
				vj.ObjetivoGeneral,
				vj.FechaRegistro,
				vj.FechaInicio,
				vj.FechaTermino,
				vj.FechaTerminoReal,
				vj.Estado,
				oe.Organismo AS NomOrganismoExterno,
				de.Dependencia As NomDependenciaExterna
			FROM
				pf_potestad vj
				INNER JOIN seguridad_alterna sa ON (vj.CodDependencia = sa.CodDependencia AND
													sa.CodAplicacion = '".$_SESSION["APLICACION_ACTUAL"]."' AND
													sa.Usuario = '".$_SESSION["USUARIO_ACTUAL"]."' AND
													sa.FlagMostrar = 'S')
				INNER JOIN pf_organismosexternos oe ON (vj.CodOrganismoExterno = oe.CodOrganismo)
				LEFT JOIN pf_dependenciasexternas de ON (vj.CodDependenciaExterna = de.CodDependencia)
			WHERE 1 $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				vj.CodPotestad,
				vj.ObjetivoGeneral,
				vj.FechaRegistro,
				vj.FechaInicio,
				vj.FechaTermino,
				vj.FechaTerminoReal,
				vj.Estado,
				oe.Organismo AS NomOrganismoExterno,
				de.Dependencia As NomDependenciaExterna
			FROM
				pf_potestad vj
				INNER JOIN seguridad_alterna sa ON (vj.CodDependencia = sa.CodDependencia AND
													sa.CodAplicacion = '".$_SESSION["APLICACION_ACTUAL"]."' AND
													sa.Usuario = '".$_SESSION["USUARIO_ACTUAL"]."' AND
													sa.FlagMostrar = 'S')
				INNER JOIN pf_organismosexternos oe ON (vj.CodOrganismoExterno = oe.CodOrganismo)
				LEFT JOIN pf_dependenciasexternas de ON (vj.CodDependenciaExterna = de.CodDependencia)
			WHERE 1 $filtro
			$orderby
			LIMIT ".intval($limit).", $maxlimit";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		if ($field['NomDependenciaExterna'] != "") {
			$dep = "<br>($field[NomDependenciaExterna])";
		} else $dep = "";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$field['CodPotestad']?>">
			<td align="center"><?=$field['CodPotestad']?></td>
			<td>
				<strong><?=$field['NomOrganismoExterno']?></strong>
				<?=$dep?>
            </td>
			<td align="center"><?=formatFechaDMA($field['FechaRegistro'])?></td>
			<td align="center"><?=formatFechaDMA($field['FechaInicio'])?></td>
			<td align="center"><?=formatFechaDMA($field['FechaTermino'])?></td>
			<td align="center"><?=formatFechaDMA($field['FechaTerminoReal'])?></td>
			<td align="center"><?=printValoresGeneral("ESTADO-VALORACION", $field['Estado'])?></td>
			<td><?=$field['ObjetivoGeneral']?></td>
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
