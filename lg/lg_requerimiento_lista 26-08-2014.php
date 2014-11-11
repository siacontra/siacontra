<?php
$Ahora = ahora();
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
if ($lista == "todos") {
	$titulo = "Lista de Requerimientos";
	$btRevisar = "display:none;";
	$btConformar = "display:none;";
	$btAprobar = "display:none;";
}
elseif ($lista == "revisar") {
	$titulo = "Revisar Requerimientos";
	$btNuevo = "display:none;";
	$btModificar = "display:none;";
	$btConformar = "display:none;";
	$btAprobar = "display:none;";
	$fEstado = "PR";
}
elseif ($lista == "conformar") {
	$titulo = "Conformar Requerimientos";
	$btNuevo = "display:none;";
	$btModificar = "display:none;";
	$btRevisar = "display:none;";
	$btAprobar = "display:none;";
	$fEstado = "RV";
}
elseif ($lista == "aprobar") {
	$titulo = "Aprobar Requerimientos";
	$btNuevo = "display:none;";
	$btModificar = "display:none;";
	$btRevisar = "display:none;";
	$btConformar = "display:none;";
	$fEstado = "CN";
}
//	------------------------------------
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$maxlimit = $_SESSION["MAXLIMIT"];
	if ($lista == "todos") {
		$fEstado = "PR";
		$fFechaPreparaciond = "01-$Mes-$Anio";
		$fFechaPreparacionh = "$Dia-$Mes-$Anio";
	}
}
if ($fCodOrganismo != "") { $cOrganismo = "checked"; $filtro.=" AND (r.CodOrganismo = '".$fCodOrganismo."')"; } else $dOrganismo = "disabled";
if ($fCodDependencia != "") { $cCodDependencia = "checked"; $filtro.=" AND (r.CodDependencia = '".$fCodDependencia."')"; } else $dCodDependencia = "disabled";
if ($fCodCentroCosto != "") { $cCodCentroCosto = "checked"; $filtro.=" AND (r.CodCentroCosto = '".$fCodCentroCosto."')"; } else $dCodCentroCosto = "disabled";
if ($fClasificacion != "") { $cClasificacion = "checked"; $filtro.=" AND (r.Clasificacion = '".$fClasificacion."')"; } else $dClasificacion = "disabled";
if ($fEstado != "") { $cEstado = "checked"; $filtro.=" AND (r.Estado = '".$fEstado."')"; } else $dEstado = "disabled";
if ($fTipoClasificacion != "") { $cTipoClasificacion = "checked"; $filtro.=" AND (r.TipoClasificacion = '".$fTipoClasificacion."')"; } else $dTipoClasificacion = "disabled";
if ($fBuscar != "") { 
	$cBuscar = "checked"; 
	$filtro.=" AND (r.CodInterno LIKE '%".$fBuscar."%' OR 
					a.Descripcion LIKE '%".utf8_decode($fBuscar)."%' OR 
					r.CodCentroCosto LIKE '%".utf8_decode($fBuscar)."%' OR 
					c.Descripcion LIKE '%".utf8_decode($fBuscar)."%')";
} else $dBuscar = "disabled";
if ($fFechaPreparaciond != "" || $fFechaPreparacionh != "") {
	$cFechaPreparacion = "checked";
	if ($fFechaPreparaciond != "") $filtro.=" AND (r.FechaPreparacion >= '".formatFechaAMD($fFechaPreparaciond)."')";
	if ($fFechaPreparacionh != "") $filtro.=" AND (r.FechaPreparacion <= '".formatFechaAMD($fFechaPreparacionh)."')";
} else $dFechaPreparacion = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=lg_requerimiento_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:1050px;">
<table width="1050" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
			<input type="checkbox" <?=$cOrganismo?> onclick="this.checked=!this.checked" />
			<select name="fCodOrganismo" id="fCodOrganismo" style="width:300px;" onchange="getOptionsSelect(this.value, 'dependencia', 'fCodDependencia', true, 'fCodCentroCosto');" <?=$dOrganismo?>>
				<?=getOrganismos($fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right">Dirigido a:</td>
		<td>
			<input type="checkbox" <?=$cTipoClasificacion?> onclick="chkFiltro(this.checked, 'fTipoClasificacion')" />
			<select name="fTipoClasificacion" id="fTipoClasificacion" style="width:140px;" <?=$dTipoClasificacion?>>
				<option value="">&nbsp;</option>
				<?=loadSelectValores("DIRIGIDO", $fTipoClasificacion, 0)?>
			</select>
		</td>
	</tr>
    <tr>
		<td align="right">Dependencia:</td>
		<td>
			<input type="checkbox" <?=$cCodDependencia?> onclick="chkFiltro(this.checked, 'fCodDependencia')" />
			<select name="fCodDependencia" id="fCodDependencia" style="width:300px;" <?=$dCodDependencia?>>
				<option value="">&nbsp;</option>
				<?=getDependencias($fCodDependencia, $fCodOrganismo, 3);?>
			</select>
		</td>
		<td align="right">Clasificaci&oacute;n:</td>
		<td>
			<input type="checkbox" <?=$cClasificacion?> onclick="chkFiltro(this.checked, 'fClasificacion')" />
			<select name="fClasificacion" id="fClasificacion" style="width:140px;" <?=$dClasificacion?>>
				<option value="">&nbsp;</option>
				<?=loadSelect("lg_clasificacion", "Clasificacion", "Descripcion", $fClasificacion, 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Centro de Costo:</td>
		<td>
			<input type="checkbox" <?=$cCodCentroCosto?> onclick="chkFiltro(this.checked, 'fCodCentroCosto')" />
			<select name="fCodCentroCosto" id="fCodCentroCosto" style="width:300px;" <?=$dCodCentroCosto?>>
				<option value="">&nbsp;</option>
				<?=loadSelectDependiente("ac_mastcentrocosto", "CodCentroCosto", "Descripcion", "CodDependencia", $fCodCentroCosto, $fCodDependencia, 0)?>
			</select>
		</td>
		<td align="right">Estado:</td>
		<td>
        	<? 
			if ($lista == "revisar" || $lista == "conformar" || $lista == "aprobar") {
				?>
				<input type="checkbox" onclick="this.checked=!this.checked;" checked="checked" />
                <select name="fEstado" id="fEstado" style="width:150px;">
                    <?=loadSelectValores("ESTADO-REQUERIMIENTO", $fEstado, 1)?>
                </select>
                <?
			} else {
				?>
                <input type="checkbox" <?=$cEstado?> onclick="chkFiltro(this.checked, 'fEstado');" />
                <select name="fEstado" id="fEstado" style="width:140px;" <?=$dEstado?>>
					<option value="">&nbsp;</option>
                    <?=loadSelectValores("ESTADO-REQUERIMIENTO", $fEstado, 0)?>
                </select>
                <?
			}
			?>
		</td>
	</tr>
    <tr>
		<td align="right">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fBuscar');" />
			<input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:295px;" <?=$dBuscar?> />
		</td>
		<td align="right">F.Preparaci&oacute;n: </td>
		<td>
			<input type="checkbox" <?=$cFechaPreparacion?> onclick="chkFiltro_2(this.checked, 'fFechaPreparaciond', 'fFechaPreparacionh');" />
			<input type="text" name="fFechaPreparaciond" id="fFechaPreparaciond" value="<?=$fFechaPreparaciond?>" <?=$dFechaPreparacion?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" />-
            <input type="text" name="fFechaPreparacionh" id="fFechaPreparacionh" value="<?=$fFechaPreparacionh?>" <?=$dFechaPreparacion?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" />
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
			<input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=lg_requerimiento_form&opcion=nuevo&origen=lg_requerimiento_lista');" />
            
			<input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=requerimiento_modificar', 'gehen.php?anz=lg_requerimiento_form&opcion=modificar&origen=lg_requerimiento_lista', 'SELF', '');" />
            
			<input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=lg_requerimiento_form&opcion=ver&origen=lg_requerimiento_lista', 'SELF', '', 'registro');" /> | 
            
			<input type="button" id="btRevisar" value="Revisar" style="width:75px; <?=$btRevisar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=lg_requerimiento_form&opcion=revisar&origen=lg_requerimiento_lista', 'SELF', '', $('#registro').val());" />
            
			<input type="button" id="btConformar" value="Conformar" style="width:75px; <?=$btConformar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=lg_requerimiento_form&opcion=conformar&origen=lg_requerimiento_lista', 'SELF', '', $('#registro').val());" />
            
			<input type="button" id="btAprobar" value="Aprobar" style="width:75px; <?=$btAprobar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=lg_requerimiento_form&opcion=aprobar&origen=lg_requerimiento_lista', 'SELF', '', $('#registro').val());" />
            
			<input type="button" id="btAnular" value="Anular" style="width:75px; <?=$btAnular?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=requerimiento_anular', 'gehen.php?anz=lg_requerimiento_form&opcion=anular&origen=lg_requerimiento_lista', 'SELF', '');" />
            
			<input type="button" id="btCerrar" value="Cerrar" style="width:75px; <?=$btCerrar?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=requerimiento_cerrar', 'gehen.php?anz=lg_requerimiento_form&opcion=cerrar&origen=lg_requerimiento_lista', 'SELF', '');" /> | 
            
			<input type="button" id="btImprimir" value="Imprimir" style="width:75px; <?=$btImprimir?>" onclick="cargarOpcion2(this.form, 'lg_requerimiento_pdf.php?', 'BLANK', 'height=900, width=1000, left=100, top=0, resizable=no', $('#registro').val());" />
            
			<input type="button" id="btCuadro" value="Cuadro Comparativo" style="width:125px; <?=$btCuadro?>" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:1050px; height:300px;">
<table width="2100" class="tblLista">
	<thead>
    <tr>	
		<th scope="col" width="100"># Req.</th>
		<th scope="col" width="75">Fecha Preparaci&oacute;n</th>
		<th scope="col" width="75">Fecha Aprobaci&oacute;n</th>
		<th scope="col" width="150">Clasificaci&oacute;n</th>
		<th scope="col">Comentarios</th>
		<th scope="col" width="75">Prioridad</th>
		<th scope="col" width="100">Estado</th>
		<th scope="col" width="60">C. Costo</th>
		<th scope="col" width="60">Dirigido A</th>        
		<th scope="col" width="150">Almacen</th>
		<th scope="col" width="250">Aprobado Por</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				r.*,
				o.Organismo,
				d.Dependencia,
				a.Descripcion AS NomAlmacen,
				c.Descripcion AS NomClasificacion,
				cc.Descripcion AS NomCentroCosto,
				p.Busqueda AS NomAprobadoPor
			FROM
				lg_requerimientos r
				INNER JOIN lg_almacenmast a ON (r.CodAlmacen = a.CodAlmacen)
				INNER JOIN lg_clasificacion c ON (r.Clasificacion = c.Clasificacion)
				INNER JOIN ac_mastcentrocosto cc ON (r.CodCentroCosto = cc.CodCentroCosto)
				INNER JOIN mastorganismos o ON (r.CodOrganismo = o.CodOrganismo)
				INNER JOIN mastdependencias d ON (r.CodDependencia = d.CodDependencia)
				LEFT JOIN mastpersonas p ON (r.AprobadaPor = p.CodPersona)
				INNER JOIN seguridad_alterna sa ON (r.CodDependencia = sa.CodDependencia AND
													sa.CodAplicacion = '".$_SESSION["APLICACION_ACTUAL"]."' AND
													sa.Usuario = '".$_SESSION["USUARIO_ACTUAL"]."' AND
													sa.FlagMostrar = 'S')
			WHERE 1 $filtro
			ORDER BY CodOrganismo, CodDependencia, Anio, Secuencia";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				r.*,
				o.Organismo,
				d.Dependencia,
				a.Descripcion AS NomAlmacen,
				c.Descripcion AS NomClasificacion,
				cc.Descripcion AS NomCentroCosto,
				p.Busqueda AS NomAprobadoPor
			FROM
				lg_requerimientos r
				INNER JOIN lg_almacenmast a ON (r.CodAlmacen = a.CodAlmacen)
				INNER JOIN lg_clasificacion c ON (r.Clasificacion = c.Clasificacion)
				INNER JOIN ac_mastcentrocosto cc ON (r.CodCentroCosto = cc.CodCentroCosto)
				INNER JOIN mastorganismos o ON (r.CodOrganismo = o.CodOrganismo)
				INNER JOIN mastdependencias d ON (r.CodDependencia = d.CodDependencia)
				LEFT JOIN mastpersonas p ON (r.AprobadaPor = p.CodPersona)
				INNER JOIN seguridad_alterna sa ON (r.CodDependencia = sa.CodDependencia AND
													sa.CodAplicacion = '".$_SESSION["APLICACION_ACTUAL"]."' AND
													sa.Usuario = '".$_SESSION["USUARIO_ACTUAL"]."' AND
													sa.FlagMostrar = 'S')
			WHERE 1 $filtro
			ORDER BY CodOrganismo, CodDependencia, Anio, Secuencia
			LIMIT ".intval($limit).", ".intval($maxlimit);
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[CodRequerimiento]";
		if (strlen($field['Comentarios']) > 245) $Comentarios = substr($field['Comentarios'], 0, 245)."...";
		else $Comentarios = $field['Comentarios'];
		##
		if ($grupo != $field['CodDependencia']) {
			$grupo = $field['CodDependencia'];
			?>
            <tr class="trListaBody2">
                <td colspan="5"><?=($field['Dependencia'])?></td>
            </tr>
            <?
		}
		##
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$field['CodInterno']?></td>
			<td align="center"><?=formatFechaDMA($field['FechaPreparacion'])?></td>
			<td align="center"><?=formatFechaDMA($field['FechaAprobacion'])?></td>
			<td><?=($field['NomClasificacion'])?></td>
			<td title="<?=($field['Comentarios'])?>"><?=($Comentarios)?></td>
			<td align="center"><?=printValoresGeneral("PRIORIDAD", $field['Prioridad'])?></td>
			<td align="center"><?=printValores("ESTADO-REQUERIMIENTO", $field['Estado'])?></td>
			<td align="center"><?=($field['CodCentroCosto'])?></td>
			<td align="center"><?=printValores("DIRIGIDO", $field['TipoClasificacion'])?></td>
			<td><?=($field['NomAlmacen'])?></td>
			<td><?=($field['NomAprobadoPor'])?></td>
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