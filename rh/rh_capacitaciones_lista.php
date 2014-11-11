<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
if ($lista == "todos") {
	$titulo = "Lista de Capacitaciones";
	$btAprobar = "display:none;";
	$btIniciar = "display:none;";
	$btTerminar = "display:none;";
}
elseif ($lista == "aprobar") {
	$titulo = "Aprobar Capacitaciones";
	$btNuevo = "display:none;";
	$btModificar = "display:none;";
	$btIniciar = "display:none;";
	$btTerminar = "display:none;";
}
elseif ($lista == "iniciar") {
	$titulo = "Iniciar/Terminar Capacitaciones";
	$btNuevo = "display:none;";
	$btModificar = "display:none;";
	$btAprobar = "display:none;";
}
//	------------------------------------
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$maxlimit = $_SESSION["MAXLIMIT"];
	if ($lista == "todos") $fEstado = "PE";
	elseif ($lista == "aprobar") $fEstado = "PE";
	elseif ($lista == "iniciar") $fEstado = "AP";
}
if ($fCodOrganismo != "") { $cCodOrganismo = "checked"; $filtro.=" AND (c.CodOrganismo = '".$fCodOrganismo."')"; } else $dCodOrganismo = "disabled";
if ($fEstado != "") { $cEstado = "checked"; $filtro.=" AND (c.Estado = '".$fEstado."')"; } else $dEstado = "disabled";
if ($fTipoCurso != "") { $cTipoCurso = "checked"; $filtro.=" AND (c.TipoCurso = '".$fTipoCurso."')"; } else $dTipoCurso = "disabled";
if ($fFechaD != "" || $fFechaH != "") {
	$cFecha = "checked";
	if ($fFechaD != "") $filtro.=" AND ('".formatFechaAMD($fFechaD)."' >= c.FechaDesde AND '".formatFechaAMD($fFechaD)."' <= c.FechaHasta)";
	if ($fFechaH != "") $filtro.=" AND ('".formatFechaAMD($fFechaH)."' >= c.FechaDesde AND '".formatFechaAMD($fFechaH)."' <= c.FechaHasta)";
} else $dFecha = "disabled";
if ($fBuscar != "") {
	$cBuscar = "checked";
	$filtro .= " AND (c.Capacitacion LIKE '%".$fBuscar."%' OR
					  c.FechaDesde LIKE '%".$fBuscar."%' OR
					  c.CostoEstimado LIKE '%".setNumero($fBuscar)."%' OR
					  cs.Descripcion LIKE '%".$fBuscar."%' OR
					  ce.Descripcion LIKE '%".$fBuscar."%' OR
					  md.Descripcion LIKE '%".$fBuscar."%')";
} else $dBuscar = "disabled";
if ($fCodCurso != "") { $cCodCurso = "checked"; $filtro.=" AND (r.CodCurso = '".$fCodCurso."')"; } else $dCodCurso = "visibility:hidden;";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_capacitaciones_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:1050px;">
<table width="1050" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
			<input type="checkbox" <?=$cCodOrganismo?> onclick="this.checked=!this.checked" />
			<select name="fCodOrganismo" id="fCodOrganismo" style="width:304px;" <?=$dCodOrganismo?> onChange="getOptionsSelect(this.value, 'dependencia_filtro', 'fCodDependencia', true)">
				<?=getOrganismos($fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right" width="125">Fecha: </td>
		<td>
			<input type="checkbox" <?=$cFecha?> onclick="chkFiltro_2(this.checked, 'fFechaD', 'fFechaH');" />
			<input type="text" name="fFechaD" id="fFechaD" value="<?=$fFechaD?>" <?=$dFecha?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFecha(this);" /> -
            <input type="text" name="fFechaH" id="fFechaH" value="<?=$fFechaH?>" <?=$dFecha?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFecha(this);" />
        </td>
	</tr>
	<tr>
		<td align="right">Curso: </td>
		<td class="gallery clearfix">
            <input type="checkbox" <?=$cCodCurso?> onclick="chkFiltroLista_3(this.checked, 'fCodCurso', 'fNomCurso', '', 'btCurso');" />
            <input type="hidden" id="fCodCurso" value="<?=$fCodCurso?>" />
			<input type="text" id="fNomCurso" style="width:300px;" class="disabled" value="<?=$fNomCurso?>" disabled />
            <a href="../lib/listas/listado_cargos.php?filtrar=default&cod=fCodCurso&nom=fNomCurso&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe1]" id="btCurso" style=" <?=$dCodCurso?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td align="right">Tipo de Curso: </td>
		<td>
            <input type="checkbox" <?=$cTipoCurso?> onclick="chkFiltro(this.checked, 'fTipoCurso');" />
            <select name="fTipoCurso" id="fTipoCurso" style="width:143px;" <?=$dTipoCurso?>>
                <option value=""></option>
                <?=getMiscelaneos($fTipoCurso, "TIPOCURSO", 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td align="right">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fBuscar');" />
			<input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:299px;" <?=$dBuscar?> />
		</td>
		<td align="right">Estado:</td>
		<td>
        	<? 
			if ($lista == "aprobar") {
				?>
				<input type="checkbox" onclick="this.checked=!this.checked;" checked="checked" />
                <select name="fEstado" id="fEstado" style="width:143px;">
                    <?=loadSelectValores("ESTADO-CAPACITACION", $fEstado, 1)?>
                </select>
                <?
			} 
			elseif ($lista == "iniciar") {
				?>
				<input type="checkbox" onclick="this.checked=!this.checked;" checked="checked" />
                <select name="fEstado" id="fEstado" style="width:143px;">
                    <?=loadSelectValores("ESTADO-CAPACITACION2", $fEstado, 0)?>
                </select>
                <?
			}
			else {
				?>
                <input type="checkbox" <?=$cEstado?> onclick="chkFiltro(this.checked, 'fEstado');" />
                <select name="fEstado" id="fEstado" style="width:143px;" <?=$dEstado?>>
                    <option value=""></option>
                    <?=loadSelectValores("ESTADO-CAPACITACION", $fEstado, 0)?>
                </select>
                <?
			} 
			?>
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
			<input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=rh_capacitaciones_form&opcion=nuevo&origen=rh_capacitaciones_lista');" />
            
			<input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=capacitaciones_modificar', 'gehen.php?anz=rh_capacitaciones_form&opcion=modificar&origen=rh_capacitaciones_lista', 'SELF', '');" />
            
			<input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=rh_capacitaciones_form&opcion=ver&origen=rh_capacitaciones_lista', 'SELF', '', 'registro');" /> | 
            
			<input type="button" id="btAprobar" value="Aprobar" style="width:75px; <?=$btAprobar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=rh_capacitaciones_form&opcion=aprobar&origen=rh_capacitaciones_lista', 'SELF', '', 'registro');" />
            
			<input type="button" id="btIniciar" value="Iniciar" style="width:75px; <?=$btIniciar?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=capacitaciones_iniciar', 'gehen.php?anz=rh_capacitaciones_form&opcion=iniciar&origen=rh_capacitaciones_lista', 'SELF', '');" />
            
			<input type="button" id="btTerminar" value="Terminar" style="width:75px; <?=$btTerminar?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=capacitaciones_terminar', 'gehen.php?anz=rh_capacitaciones_form&opcion=terminar&origen=rh_capacitaciones_lista', 'SELF', '');" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:1050px; height:300px;">
<table width="1400" class="tblLista">
	<thead>
    <tr>
		<th scope="col" width="60"># Cap.</th>
		<th scope="col" width="125">Tipo</th>
		<th scope="col" width="400" align="left">Curso</th>
		<th scope="col" width="60">Inicio</th>
		<th scope="col" width="75">Estado</th>
		<th scope="col" width="100" align="right">Costo Estimado</th>
		<th scope="col" align="left">Centro</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				c.Capacitacion,
				c.CodOrganismo,
				c.Anio,
				c.Estado,
				c.FechaDesde,
				c.FechaHasta,
				c.CostoEstimado,
				o.Organismo,
				cs.Descripcion AS NomCurso,
				ce.Descripcion AS NomCentroEstudio,
				md.Descripcion AS NomTipoCurso
			FROM
				rh_capacitacion c
				INNER JOIN mastorganismos o ON (o.CodOrganismo = c.CodOrganismo)
				INNER JOIN rh_cursos cs ON (cs.CodCurso = c.CodCurso)
				INNER JOIN rh_centrosestudios ce ON (ce.CodCentroEstudio = c.CodCentroEstudio)
				LEFT JOIN mastmiscelaneosdet md ON (md.CodDetalle = c.TipoCurso AND
													md.CodMaestro = 'TIPOCURSO')
			WHERE 1 $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				c.Capacitacion,
				c.CodOrganismo,
				c.Anio,
				c.Estado,
				c.FechaDesde,
				c.FechaHasta,
				c.CostoEstimado,
				o.Organismo,
				cs.Descripcion AS NomCurso,
				ce.Descripcion AS NomCentroEstudio,
				md.Descripcion AS NomTipoCurso
			FROM
				rh_capacitacion c
				INNER JOIN mastorganismos o ON (o.CodOrganismo = c.CodOrganismo)
				INNER JOIN rh_cursos cs ON (cs.CodCurso = c.CodCurso)
				INNER JOIN rh_centrosestudios ce ON (ce.CodCentroEstudio = c.CodCentroEstudio)
				LEFT JOIN mastmiscelaneosdet md ON (md.CodDetalle = c.TipoCurso AND
													md.CodMaestro = 'TIPOCURSO')
			WHERE 1 $filtro
			ORDER BY CodOrganismo, Capacitacion
			LIMIT ".intval($limit).", ".intval($maxlimit);
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[Anio].$field[CodOrganismo].$field[Capacitacion]";
		if ($Grupo != $field['CodOrganismo']) {
			$Grupo = $field['CodOrganismo'];
			?>
			<tr class="trListaBody2">
				<td colspan="7"><?=$field['Organismo']?></td>
			</tr>
			<?
		}
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$field['Capacitacion']?></td>
			<td align="center"><?=$field['NomTipoCurso']?></td>
			<td><?=$field['NomCurso']?></td>
			<td align="center"><?=formatFechaDMA($field['FechaDesde'])?></td>
			<td align="center"><?=printValores("ESTADO-CAPACITACION", $field['Estado'])?></td>
			<td align="right"><?=number_format($field['CostoEstimado'], 2, ',', '.')?></td>
			<td><?=$field['NomCentroEstudio']?></td>
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