<?php
if ($lista == "terminar") {
	$titulo = "Ejecuci&oacute;n de Actividades";
}
else {
	$titulo = "Listar Detalle de Actuaciones";
	$btTerminar = "display:none;";
}
//	------------------------------------
if ($filtrar == "default") {
	$fordenar = "a.CodFase, af.Anio, af.Secuencia, af.CodActuacion";
	$maxlimit = $_SESSION["MAXLIMIT"];
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fedoreg = "EJ";
}
if ($fordenar != "") { $cordenar = "checked"; $orderby = "ORDER BY $fordenar"; } else $dordenar = "disabled";
if ($fedoreg != "") { $cedoreg = "checked"; $filtro.=" AND (afd.Estado = '".$fedoreg."')"; } else $dedoreg = "disabled";
if ($fbuscar != "") {
	$cbuscar = "checked";
	$filtro.=" AND (af.CodActuacion LIKE '%".$fbuscar."%' OR
					oe.Organismo LIKE '%".$fbuscar."%' OR
					de.Dependencia LIKE '%".$fbuscar."%' OR
					a.Descripcion LIKE '%".$fbuscar."%')";
} else $dbuscar = "disabled";
if ($forganismo != "") { $corganismo = "checked"; $filtro.=" AND (af.CodOrganismo = '".$forganismo."')"; } else $dorganismo = "disabled";
if ($fdependencia != "") { $cdependencia = "checked"; $filtro.=" AND (af.CodDependencia = '".$fdependencia."')"; } else $ddependencia = "disabled";
if ($forganismoext != "") { $corganismoext = "checked"; $filtro.=" AND (af.CodOrganismoExterno = '".$forganismoext."')"; } else $dorganismoext = "disabled";
if ($fdependenciaext != "") { $cdependenciaext = "checked"; $filtro.=" AND (af.CodDependenciaExterna = '".$fdependenciaext."')"; } else $ddependenciaext = "visibility:hidden;";
if ($fregistrod != "" || $fregistroh != "") {
	$cregistro = "checked";
	if ($fregistrod != "") $filtro.=" AND (af.FechaRegistro >= '".$fregistrod."')";
	if ($fregistroh != "") $filtro.=" AND (af.FechaRegistro <= '".$fregistroh."')";
} else $dregistro = "disabled";
if ($fanio != "") { $canio = "checked"; $filtro.=" AND (af.Anio = '".$fanio."')"; } else $danio = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=pf_actuacion_fiscal_actividades" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<div class="divBorder" style="width:1050px;">
<table width="1050" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
            <input type="checkbox" <?=$corganismo?> onclick="this.checked=!this.checked;" />
            <select name="forganismo" id="forganismo" style="width:300px;" onchange="getOptionsSelect(this.value, 'dependencia_fiscal', 'fdependencia', true);" <?=$dorganismo?>>
                <?=getOrganismos($forganismo, 3)?>
            </select>
		</td>
		<td align="right" width="125">Fecha de Registro:</td>
        <td>
            <input type="checkbox" <?=$cregistro?> onclick="chkFiltro_2(this.checked, 'fregistrod', 'fregistroh');" />
            <input type="text" id="fregistrod" value="<?=$fregistrod?>" style="width:75px;" maxlength="10" class="datepicker" onkeyup="setFechaDMA(this);" <?=$dregistro?> /> - 
            <input type="text" id="fregistroh" value="<?=$fregistroh?>" style="width:75px;" maxlength="10" class="datepicker" onkeyup="setFechaDMA(this);" <?=$dregistro?> />
		</td>
	</tr>
	<tr>
		<td align="right">Dependencia:</td>
		<td>
            <input type="checkbox" <?=$cdependencia?> onclick="chkFiltro(this.checked, 'fdependencia');" />
            <span>
            <select name="fdependencia" id="fdependencia" style="width:300px;" <?=$ddependencia?>>
                <option value="">&nbsp;</option>
                <?=loadDependenciaFiscal($fdependencia, $forganismo, 0)?>
            </select>
            </span>
		</td>
		<td align="right">Estado Reg.:</td>
		<td>
            <?php
			if ($lista == "terminar") {
				?>
        		<input type="checkbox" <?=$cedoreg?> onclick="this.checked=!this.checked;" />
                <select name="fedoreg" id="fedoreg" style="width:100px;" <?=$dedoreg?>>
                    <?=loadSelectValores("ESTADO-ACTUACION-DETALLE", $fedoreg, 1)?>
                </select>
                <?
			}
			else {
				?>
        		<input type="checkbox" <?=$cedoreg?> onclick="chkFiltro(this.checked, 'fedoreg');" />
                <select name="fedoreg" id="fedoreg" style="width:100px;" <?=$dedoreg?>>
                <option value="">&nbsp;</option>
                    <?=loadSelectValores("ESTADO-ACTUACION-DETALLE", $fedoreg, 0)?>
                </select>
                <?
			}
			?>
		</td>
	</tr>
	<tr>
		<td align="right">Ente Externo:</td>
		<td class="gallery clearfix">
            <input type="checkbox" <?=$corganismoext?> onclick="chkFiltroLista_2(this.checked, 'forganismoext', 'fnomorganismoext', 'fdependenciaext', 'fnomdependenciaext', 'btEnte');" />
            <input type="hidden" name="forganismoext" id="forganismoext" value="<?=$forganismoext?>" />
			<input type="text" id="fnomorganismoext" style="width:295px;" value="<?=$fnomorganismoext?>" disabled="disabled" />
            <a href="../lib/listas/listado_organismos_externos.php?filtrar=default&&cod=forganismoext&nom=fnomorganismoext&iframe=true&width=950&height=525" rel="prettyPhoto[iframe]" style=" <?=$ddependenciaext?>" id="btEnte">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
		<td align="right">Buscar:</td>
        <td>
            <input type="checkbox" <?=$cbuscar?> onclick="chkFiltro(this.checked, 'fbuscar');" />
            <input type="text" name="fbuscar" id="fbuscar" style="width:200px;" value="<?=$fbuscar?>" <?=$dbuscar?> />
		</td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td>
            <input type="checkbox" style="visibility:hidden;" />
            <input type="hidden" name="fdependenciaext" id="fdependenciaext" value="<?=$fdependenciaext?>" />
			<input type="text" id="fnomdependenciaext" style="width:295px;" value="<?=$fnomdependenciaext?>" disabled="disabled" />
		</td>
		<td align="right">Ordenar Por:</td>
		<td>
            <input type="checkbox" <?=$cordenar?> onclick="this.checked=!this.checked;" />
            <select name="fordenar" id="fordenar" style="width:100px;" <?=$dordenar?>>
                <?=loadSelectValores("ORDENAR-ACTUACION-DETALLE", $fordenar, 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td align="right">A&ntilde;o Fiscal:</td>
        <td>
            <input type="checkbox" <?=$canio?> onclick="chkFiltro(this.checked, 'fanio');" />
            <input type="text" name="fanio" id="fanio" value="<?=$fanio?>" style="width:50px;" maxlength="4" <?=$danio?> />
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
			<input type="button" id="btTerminar" value="Terminar" style="width:75px; <?=$btTerminar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=pf_actuacion_fiscal_actividades_terminar', 'SELF', '', 'registro');" />
            
			<input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=pf_actuacion_fiscal_form&opcion=ver&origen=ejecucion', 'BLANK', 'height=700, width=900, left=100, top=0, resizable=no', $('#registro').val());" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:1050px; height:300px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th scope="col" width="125">Actuaci&oacute;n</th>
		<th scope="col" width="300">Actividad</th>
		<th scope="col">Ente</th>
		<th scope="col" width="35">Dias</th>
		<th scope="col" width="75">F.Inicio Real</th>
		<th scope="col" width="75">F.Termino Real</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				af.CodActuacion,
				af.ObjetivoGeneral,
				afd.FechaInicioReal,
				afd.FechaTerminoReal,
				afd.Duracion,
				afd.Prorroga,
				oe.Organismo AS NomOrganismoExterno,
				de.Dependencia As NomDependenciaExterna,
				f.CodFase,
				f.Descripcion AS NomFase,
				a.Descripcion AS NomActividad
			FROM
				pf_actuacionfiscaldetalle afd
				INNER JOIN pf_actuacionfiscal af ON (afd.CodActuacion = af.CodActuacion)
				INNER JOIN pf_actividades a ON (afd.CodActividad = a.CodActividad)
				INNER JOIN pf_fases f ON (a.CodFase = f.CodFase)
				INNER JOIN seguridad_alterna sa ON (af.CodDependencia = sa.CodDependencia AND
													sa.CodAplicacion = '".$_SESSION["APLICACION_ACTUAL"]."' AND
													sa.Usuario = '".$_SESSION["USUARIO_ACTUAL"]."' AND
													sa.FlagMostrar = 'S')
				INNER JOIN pf_organismosexternos oe ON (af.CodOrganismoExterno = oe.CodOrganismo)
				LEFT JOIN pf_dependenciasexternas de ON (af.CodDependenciaExterna = de.CodDependencia)
			WHERE 1 $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				af.CodActuacion,
				af.ObjetivoGeneral,
				afd.FechaInicioReal,
				afd.FechaTerminoReal,
				afd.Duracion,
				afd.Prorroga,
				oe.Organismo AS NomOrganismoExterno,
				de.Dependencia As NomDependenciaExterna,
				f.CodFase,
				f.Descripcion AS NomFase,
				a.CodActividad,
				afd.Descripcion AS NomActividad
			FROM
				pf_actuacionfiscaldetalle afd
				INNER JOIN pf_actuacionfiscal af ON (afd.CodActuacion = af.CodActuacion)
				INNER JOIN pf_actividades a ON (afd.CodActividad = a.CodActividad)
				INNER JOIN pf_fases f ON (a.CodFase = f.CodFase)
				INNER JOIN seguridad_alterna sa ON (af.CodDependencia = sa.CodDependencia AND
													sa.CodAplicacion = '".$_SESSION["APLICACION_ACTUAL"]."' AND
													sa.Usuario = '".$_SESSION["USUARIO_ACTUAL"]."' AND
													sa.FlagMostrar = 'S')
				INNER JOIN pf_organismosexternos oe ON (af.CodOrganismoExterno = oe.CodOrganismo)
				LEFT JOIN pf_dependenciasexternas de ON (af.CodDependenciaExterna = de.CodDependencia)
			WHERE 1 $filtro
			$orderby
			LIMIT ".intval($limit).", $maxlimit";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		if ($field['NomDependenciaExterna'] != "") {
			$dep = "<br>($field[NomDependenciaExterna])";
		} else $dep = "";
		$dias = $field['Duracion'] + $field['Prorroga'];
		
		if ($grupo != $field['CodFase']) {
			$grupo = $field['CodFase'];
			?>
            <tr class="trListaBody2">
                <td align="center"><?=$field['CodFase']?></td>
                <td colspan="2"><strong><?=$field['NomFase']?></strong></td>
            </tr>
            <?
		}
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$field['CodActuacion']?>.<?=$field['CodActividad']?>">
			<td align="center"><?=$field['CodActuacion']?></td>
			<td><?=$field['NomActividad']?></td>
			<td>
				<strong><?=$field['NomOrganismoExterno']?></strong>
				<?=$dep?>
            </td>
			<td align="center"><?=$dias?></td>
			<td align="center"><?=formatFechaDMA($field['FechaInicioReal'])?></td>
			<td align="center"><?=formatFechaDMA($field['FechaTerminoReal'])?></td>
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