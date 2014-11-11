<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
if ($lista == "todos") {
	$titulo = "Lista de Requerimientos de Personal";
	$btAprobar = "display:none;";
	$btContratar = "display:none;";
	$btTerminar = "display:none;";
	$btAsignar = "display:none;";
}
elseif ($lista == "aprobar") {
	$titulo = "Aprobar Requerimientos";
	$btNuevo = "display:none;";
	$btModificar = "display:none;";
	$btContratar = "display:none;";
	$btTerminar = "display:none;";
	$btAsignar = "display:none;";
	$fEstado = "PE";
}
elseif ($lista == "asignar") {
	$titulo = "Evaluaci&oacute;n de Candidatos";
	$btNuevo = "display:none;";
	$btModificar = "display:none;";
	$btAprobar = "display:none;";
	$btContratar = "display:none;";
	$btTerminar = "display:none;";
}
elseif ($lista == "contratar") {
	$titulo = "Contratar Candidatos";
	$btNuevo = "display:none;";
	$btModificar = "display:none;";
	$btAprobar = "display:none;";
	$btTerminar = "display:none;";
	$btAsignar = "display:none;";
}
elseif ($lista == "finalizar") {
	$titulo = "Finalizar Requerimientos";
	$btNuevo = "display:none;";
	$btModificar = "display:none;";
	$btAprobar = "display:none;";
	$btContratar = "display:none;";
	$btAsignar = "display:none;";
}
//	------------------------------------
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$maxlimit = $_SESSION["MAXLIMIT"];
	if ($lista == "todos") $fEstado = "PE";
	elseif ($lista == "asignar") $fEstado = "AP|EE";
	elseif ($lista == "contratar" || $lista == "finalizar") $fEstado = "EE";
}
if ($fCodOrganismo != "") { $cCodOrganismo = "checked"; $filtro.=" AND (r.CodOrganismo = '".$fCodOrganismo."')"; } else $dCodOrganismo = "disabled";
if ($fCodDependencia != "") { $cCodDependencia = "checked"; $filtro.=" AND (r.CodDependencia = '".$fCodDependencia."')"; } else $dCodDependencia = "disabled";
if ($fCodCargo != "") { $cCodCargo = "checked"; $filtro.=" AND (r.CodCargo = '".$fCodCargo."')"; } else $dCodCargo = "visibility:hidden;";
if ($fEstado != "") {
	$cEstado = "checked";
	if ($fEstado == "AP|EE") $filtro.=" AND (r.Estado = 'AP' OR r.Estado = 'EE')";
	else $filtro.=" AND (r.Estado = '".$fEstado."')";
} else $dEstado = "disabled";
if ($fFechaSolicitudd != "" || $fFechaSolicitudh != "") {
	$cFechaSolicitud = "checked";
	if ($fFechaSolicitudd != "") $filtro.=" AND (r.FechaSolicitud >= '".formatFechaAMD($fFechaSolicitudd)."')";
	if ($fFechaSolicitudh != "") $filtro.=" AND (r.FechaSolicitud <= '".formatFechaAMD($fFechaSolicitudh)."')";
} else $dFechaSolicitud = "disabled";
if ($fBuscar != "") {
	$cBuscar = "checked";
	$filtro .= " AND (r.CodRequerimiento LIKE '%".$fBuscar."%')";
} else $dBuscar = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_requerimientos_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:1050px;">
<table width="1050" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
			<input type="checkbox" <?=$cCodOrganismo?> onclick="this.checked=!this.checked" />
			<select name="fCodOrganismo" id="fCodOrganismo" style="width:300px;" <?=$dCodOrganismo?> onChange="getOptionsSelect(this.value, 'dependencia_filtro', 'fCodDependencia', true)">
				<?=getOrganismos($fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right" width="125">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fBuscar');" />
			<input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:250px;" <?=$dBuscar?> />
		</td>
	</tr>
	<tr>
		<td align="right">Dependencia:</td>
		<td>
			<input type="checkbox" <?=$cCodDependencia?> onclick="chkFiltro(this.checked, 'fCodDependencia');" />
			<select name="fCodDependencia" id="fCodDependencia" style="width:300px;" <?=$dCodDependencia?>>
            	<option value="">&nbsp;</option>
				<?=getDependencias($fCodDependencia, $fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right">Fecha de Solicitud: </td>
		<td>
			<input type="checkbox" <?=$cFechaSolicitud?> onclick="chkFiltro_2(this.checked, 'fFechaSolicitudd', 'fFechaSolicitudh');" />
			<input type="text" name="fFechaSolicitudd" id="fFechaSolicitudd" value="<?=$fFechaSolicitudd?>" <?=$dFechaSolicitud?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFecha(this);" /> -
            <input type="text" name="fFechaSolicitudh" id="fFechaSolicitudh" value="<?=$fFechaSolicitudh?>" <?=$dFechaSolicitud?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFecha(this);" />
        </td>
	</tr>
	<tr>
		<td align="right">Cargo: </td>
		<td class="gallery clearfix">
            <input type="checkbox" <?=$cCodCargo?> onclick="chkFiltroLista_3(this.checked, 'fCodCargo', 'fDescripCargo', 'fCodDesc', 'btCargo');" />
            <input type="hidden" id="fCodCargo" value="<?=$fCodCargo?>" />
            <input type="text" id="fCodDesc" style="width:50px;" class="disabled" value="<?=$fCodDesc?>" disabled />
			<input type="text" id="fDescripCargo" style="width:237px;" class="disabled" value="<?=$fDescripCargo?>" disabled />
            <a href="../lib/listas/listado_cargos.php?filtrar=default&cod=fCodCargo&nom=fDescripCargo&campo3=fCodDesc&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe2]" id="btCargo" style=" <?=$dCodCargo?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td align="right">Estado: </td>
		<td>
        	<? 
			if ($lista == "aprobar" || $lista == "contratar" || $lista == "finalizar") {
				?>
				<input type="checkbox" onclick="this.checked=!this.checked;" checked="checked" />
                <select name="fEstado" id="fEstado" style="width:143px;">
                    <?=loadSelectValores("ESTADO-REQUERIMIENTO", $fEstado, 1)?>
                </select>
                <?
			}
			elseif ($lista == "asignar") {
				?>
				<input type="checkbox" onclick="this.checked=!this.checked;" checked="checked" />
                <select name="fEstado" id="fEstado" style="width:143px;">
                    <?=loadSelectValores("ESTADO-REQUERIMIENTO2", $fEstado, 0)?>
                </select>
                <?
			}
			else {
				?>
                <input type="checkbox" <?=$cEstado?> onclick="chkFiltro(this.checked, 'fEstado');" />
                <select name="fEstado" id="fEstado" style="width:143px;" <?=$dEstado?>>
                    <option value=""></option>
                    <?=loadSelectValores("ESTADO-REQUERIMIENTO", $fEstado, 0)?>
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
			<input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=rh_requerimientos_form&opcion=nuevo&origen=rh_requerimientos_lista');" />
            
			<input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=requerimientos_modificar', 'gehen.php?anz=rh_requerimientos_form&opcion=modificar&origen=rh_requerimientos_lista', 'SELF', '');" />
            
			<input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=rh_requerimientos_form&opcion=ver&origen=rh_requerimientos_lista', 'SELF', '', 'registro');" /> | 
            
			<input type="button" id="btAprobar" value="Aprobar" style="width:75px; <?=$btAprobar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=rh_requerimientos_form&opcion=aprobar&origen=rh_requerimientos_lista', 'SELF', '', 'registro');" />
            
			<input type="button" id="btAsignar" value="Asignar" style="width:75px; <?=$btAsignar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=rh_requerimientos_form&opcion=asignar&origen=rh_requerimientos_lista', 'SELF', '', 'registro');" />
            
			<input type="button" id="btContratar" value="Contratar" style="width:75px; <?=$btContratar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=rh_requerimientos_form&opcion=contratar&origen=rh_requerimientos_lista', 'SELF', '', 'registro');" />
            
			<input type="button" id="btTerminar" value="Terminar" style="width:75px; <?=$btTerminar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=rh_requerimientos_form&opcion=finalizar&origen=rh_requerimientos_lista', 'SELF', '', 'registro');" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:1050px; height:300px;">
<table width="1400" class="tblLista">
	<thead>
    <tr>
		<th scope="col" width="60" rowspan="2"># Req.</th>
		<th scope="col" width="75" rowspan="2">Fecha</th>
		<th scope="col" rowspan="2">Puesto Solicitado</th>
		<th scope="col" width="35" rowspan="2"># Vac.</th>
		<th scope="col" width="35" rowspan="2"># Pen.</th>
		<th scope="col" width="100" rowspan="2">Estado</th>
		<th scope="col" colspan="2">Vigencia</th>
		<th scope="col" width="400" rowspan="2">Dependencia</th>
	</tr>
    <tr>
		<th scope="col" width="75">Desde</th>
		<th scope="col" width="75">Hasta</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				r.Requerimiento,
				r.FechaSolicitud,
				r.NumeroSolicitado,
				r.NumeroPendiente,
				r.CodDependencia,
				r.CodOrganismo,
				r.VigenciaInicio,
				r.VigenciaFin,
				r.CodCargo,
				r.Estado,
				pt.DescripCargo,
				d.Dependencia
			FROM
				rh_requerimiento r
				INNER JOIN rh_puestos pt ON (pt.CodCargo = r.CodCargo)
				INNER JOIN mastdependencias d ON (d.CodDependencia = r.CodDependencia)
			WHERE 1 $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				r.Requerimiento,
				r.FechaSolicitud,
				r.NumeroSolicitado,
				r.NumeroPendiente,
				r.CodDependencia,
				r.CodOrganismo,
				r.VigenciaInicio,
				r.VigenciaFin,
				r.CodCargo,
				r.Estado,
				pt.DescripCargo,
				d.Dependencia
			FROM
				rh_requerimiento r
				INNER JOIN rh_puestos pt ON (pt.CodCargo = r.CodCargo)
				INNER JOIN mastdependencias d ON (d.CodDependencia = r.CodDependencia)
			WHERE 1 $filtro
			ORDER BY CodOrganismo, Requerimiento
			LIMIT ".intval($limit).", ".intval($maxlimit);
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[CodOrganismo].$field[Requerimiento]";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$field['Requerimiento']?></td>
			<td align="center"><?=formatFechaDMA($field['FechaSolicitud'])?></td>
			<td><?=$field['DescripCargo']?></td>
			<td align="center"><?=$field['NumeroSolicitado']?></td>
			<td align="center"><?=$field['NumeroPendiente']?></td>
			<td align="center"><?=printValores("ESTADO-REQUERIMIENTO", $field['Estado'])?></td>
			<td align="center"><?=formatFechaDMA($field['VigenciaInicio'])?></td>
			<td align="center"><?=formatFechaDMA($field['VigenciaFin'])?></td>
			<td><?=$field['Dependencia']?></td>
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