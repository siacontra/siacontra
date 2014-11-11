<?php
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
if ($lista == "todos") {
	$titulo = "Lista de Pensiones x Sobreviviente";
	$btConformar = "display:none;";
	$btAprobar = "display:none;";
}
elseif ($lista == "conformar") {
	$titulo = "Conformar Pensiones x Sobreviviente";
	$btNuevo = "display:none;";
	$btModificar = "display:none;";
	$btAprobar = "display:none;";
	$fEstado = "PR";
}
elseif ($lista == "aprobar") {
	$titulo = "Aprobar Pensiones x Sobreviviente";
	$btNuevo = "display:none;";
	$btModificar = "display:none;";
	$btConformar = "display:none;";
	$fEstado = "CN";
}
//	------------------------------------
if ($filtrar == "default") {
	$fOrderBy = "Ndocumento";
	$maxlimit = $_SESSION["MAXLIMIT"];
	$fCodOrganismo = $_SESSION["ORGANISMO_ACTUAL"];
}
if ($fCodOrganismo != "") { $cCodOrganismo = "checked"; $filtro.=" AND (pp.CodOrganismo = '".$fCodOrganismo."')"; } else $dCodOrganismo = "disabled";
if ($fEstado != "") { $cEstado = "checked"; $filtro.=" AND (pp.Estado = '".$fEstado."')"; } else $dEstado = "disabled";
if ($fBuscar != "") {
	$cBuscar = "checked";
	$filtro .= " AND (e.CodEmpleado LIKE '%".$fBuscar."%' OR
					  p1.NomCompleto LIKE '%".$fBuscar."%' OR
					  p1.Ndocumento LIKE '%".$fBuscar."%' OR
					  pp.CodProceso LIKE '%".$fBuscar."%' OR
					  p2.NomCompleto LIKE '%".$fBuscar."%' OR
					  pp.FechaAprobacion LIKE '%".formatFechaAMD($fBuscar)."%' OR
					  d.Dependencia LIKE '%".$fBuscar."%')";
} else $dBuscar = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_pensiones_sobreviviente_lista" method="post" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="sel_registros" id="sel_registros" />

<div class="divBorder" style="width:950px;">
<table width="950" class="tblFiltro">
	<tr>
		<td align="right" width="100">Organismo:</td>
		<td>
			<input type="checkbox" <?=$cCodOrganismo?> onclick="this.checked=!this.checked" />
			<select name="fCodOrganismo" id="fCodOrganismo" style="width:270px;" <?=$dCodOrganismo?>>
				<?=getOrganismos($fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right" width="75">Estado: </td>
		<td>
        <?php
		if ($lista == "todos") {
			?>
        	<input type="checkbox" <?=$cEstado?> onclick="chkFiltro(this.checked, 'fEstado');" />
            <select name="fEstado" id="fEstado" style="width:100px;" <?=$dEstado?>>
                <option value=""></option>
                <?=loadSelectValores("ESTADO-PENSION", $fEstado, 0)?>
            </select>
            <?
		} else {
			?>
        	<input type="checkbox" <?=$cEstado?> onclick="this.checked=!this.checked;" />
            <select name="fEstado" id="fEstado" style="width:125px;" <?=$dEstado?>>
                <?=loadSelectValores("ESTADO-PENSION", $fEstado, 1)?>
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
			<input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:264px;" <?=$dBuscar?> />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="950" class="tblBotones">
    <tr>
        <td><div id="rows"></div></td>
        <td align="right">
            <input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=rh_pensiones_sobreviviente_form&opcion=nuevo');" />
            
            <input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcionValidar2(this.form, $('#sel_registros').val(), 'accion=pensiones_modificar', 'gehen.php?anz=rh_pensiones_sobreviviente_form&opcion=modificar', 'SELF', '');" />
            
            <input type="button" id="btConformar" value="Conformar" style="width:75px; <?=$btConformar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=rh_pensiones_sobreviviente_form&opcion=conformar', 'SELF', '', $('#sel_registros').val());" />
            
            <input type="button" id="btAprobar" value="Aprobar" style="width:75px; <?=$btAprobar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=rh_pensiones_sobreviviente_form&opcion=aprobar', 'SELF', '', $('#sel_registros').val());" />
            
            <input type="button" id="btAnular" value="Anular" style="width:75px; <?=$btAnular?>" onclick="cargarOpcionValidar2(this.form, $('#sel_registros').val(), 'accion=pensiones_anular', 'gehen.php?anz=rh_pensiones_sobreviviente_form&opcion=anular', 'SELF', '');" />
            
            <input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=rh_pensiones_sobreviviente_form&opcion=ver', 'SELF', '', $('#sel_registros').val());" />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:950px; height:350px;">
<table width="1800" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="60" onclick="order('CodEmpleado')"><a href="javascript:">Empleado</a></th>
        <th scope="col" align="left" onclick="order('NomCompleto')"><a href="javascript:">Nombre Completo</a></th>
        <th scope="col" width="75" align="right" onclick="order('Ndocumento')"><a href="javascript:">Nro. Documento</a></th>
        <th scope="col" width="75" onclick="order('CodProceso')"><a href="javascript:">Nro. Proceso</a></th>
        <th scope="col" width="100" onclick="order('Estado')"><a href="javascript:">Estado</a></th>
        <th scope="col" width="350" align="left" onclick="order('NomAprobadoPor')"><a href="javascript:">Aprobado Por</a></th>
        <th scope="col" width="75" onclick="order('FechaAprobacion')"><a href="javascript:">Fecha Aprobaci&oacute;n</a></th>
        <th scope="col" width="500" align="left" onclick="order('Dependencia')"><a href="javascript:">Dependencia</a></th>
    </tr>
    </thead>
    
    <tbody id="lista_registros">
    <?php
    //	consulto todos
    $sql = "SELECT pp.CodProceso
            FROM
				rh_proceso_pension pp
				INNER JOIN mastpersonas p1 ON (p1.CodPersona = pp.CodPersona)
				INNER JOIN mastempleado e ON (e.CodPersona = p1.CodPersona)
				INNER JOIN mastdependencias d ON (d.CodDependencia = pp.CodDependencia)
				LEFT JOIN mastpersonas p2 ON (p2.CodPersona = pp.AprobadoPor)
            WHERE
				pp.TipoPension = 'S'
				$filtro";
    $query = mysql_query($sql) or die ($sql.mysql_error());
    $rows_total = mysql_num_rows($query);
    
    //	consulto lista
    $sql = "SELECT
				pp.CodOrganismo,
				pp.CodPersona,
				pp.CodProceso,
				pp.Estado,
				pp.FechaAprobado,
				p1.NomCompleto,
				p1.Ndocumento,
				e.CodEmpleado,
				d.Dependencia,
				p2.NomCompleto AS NomAprobadoPor
            FROM
				rh_proceso_pension pp
				INNER JOIN mastpersonas p1 ON (p1.CodPersona = pp.CodPersona)
				INNER JOIN mastempleado e ON (e.CodPersona = p1.CodPersona)
				INNER JOIN mastdependencias d ON (d.CodDependencia = pp.CodDependencia)
				LEFT JOIN mastpersonas p2 ON (p2.CodPersona = pp.AprobadoPor)
            WHERE
				pp.TipoPension = 'S'
				$filtro
            ORDER BY $fOrderBy
            LIMIT ".intval($limit).", ".intval($maxlimit);
    $query = mysql_query($sql) or die ($sql.mysql_error());
    $rows_lista = mysql_num_rows($query);
    while ($field = mysql_fetch_array($query)) {
        $id = "$field[CodProceso]";
        ?>
        <tr class="trListaBody" onclick="clk($(this), 'registros', '<?=$id?>');">
            <td align="center"><?=$field['CodEmpleado']?></td>
            <td><?=htmlentities($field['NomCompleto'])?></td>
            <td align="right"><?=$field['Ndocumento']?></td>
            <td align="center"><strong><?=$field['CodProceso']?></strong></td>
            <td align="center"><strong><?=printValores("ESTADO-PENSION", $field['Estado'])?></strong></td>
            <td><?=htmlentities($field['NomAprobadoPor'])?></td>
            <td align="center"><?=formatFechaDMA(substr($field['FechaAprobado'], 0, 10))?></td>
            <td><?=htmlentities($field['Dependencia'])?></td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
<table width="950">
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