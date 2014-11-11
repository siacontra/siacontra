<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));



if ($lista == "todos") {
	$titulo = "Lista de Solicitud de Vacaciones";
	$btRevisar = "display:none;";
	$btAprobar = "display:none;";
	$btConformar = "display:none;";
}
elseif ($lista == "revisar") {
	$titulo = "Revisar Solicitudes de Vacaciones";
	$btNuevo = "display:none;";
	$btModificar = "display:none;";
	$btAprobar = "display:none;";
	$btConformar = "display:none;";
	$fEstado = "PE";
}
elseif ($lista == "conformar") {
	$titulo = "Conformar Solicitudes de Vacaciones";
	$btNuevo = "display:none;";
	$btModificar = "display:none;";
	$btRevisar = "display:none;";
	$btAprobar = "display:none;";
	$fEstado = "RV";
}
elseif ($lista == "aprobar") {
	$titulo = "Aprobar Solicitudes de Vacaciones";
	$btNuevo = "display:none;";
	$btModificar = "display:none;";
	$btRevisar = "display:none;";
	$btConformar = "display:none;";
	$fEstado = "CO";
}
//	------------------------------------
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$maxlimit = $_SESSION["MAXLIMIT"];
	if ($lista == "todos") {
		$fEstado = "PE";
		$fCodDependencia = $_SESSION["DEPENDENCIA_ACTUAL"];
	}
}
if ($fCodOrganismo != "") { $cCodOrganismo = "checked"; $filtro .= " AND (vs.CodOrganismo = '".$fCodOrganismo."')"; } else $dCodOrganismo = "disabled";
if ($fCodDependencia != "") { $cCodDependencia = "checked"; $filtro .= " AND (vs.CodDependencia = '".$fCodDependencia."')"; } else $dCodDependencia = "disabled";
if ($fCodPersona != "") { $cCodPersona = "checked"; $filtro .= " AND (vs.CodPersona = '".$fCodPersona."')"; } else $dCodPersona = "visibility:hidden;";
if ($fBuscar != "") {
	$cBuscar = "checked";
	$filtro .= " AND (vs.CodDependencia = '".$fBuscar."' OR 
					  vs.CodEmpleado = '".$fBuscar."')";
} else $dBuscar = "disabled";
if ($fFechad != "" || $fFechah != "") {
	$cFecha = "checked";
	if ($fFechad != "") $filtro.=" AND (vs.Fecha >= '".formatFechaAMD($fFechad)."')";
	if ($fFechah != "") $filtro.=" AND (vs.Fecha <= '".formatFechaAMD($fFechah)."')";
} else $dFecha = "disabled";
if ($fEstado != "") { $cEstado = "checked"; $filtro.=" AND (vs.Estado = '".$fEstado."')"; } else $dEstado = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_vacaciones_lista" method="post">
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
		<td align="right">Fecha: </td>
		<td>
			<input type="checkbox" <?=$cFecha?> onclick="chkFiltro_2(this.checked, 'fFechad', 'fFechah');" />
			<input type="text" name="fFechad" id="fFechad" value="<?=$fFechad?>" <?=$dFecha?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" />-
            <input type="text" name="fFechah" id="fFechah" value="<?=$fFechah?>" <?=$dFecha?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" />
        </td>
	</tr>
	<tr>
		<td align="right">Empleado: </td>
		<td class="gallery clearfix">
            <input type="checkbox" <?=$cCodPersona?> onclick="chkFiltroLista_3(this.checked, 'fCodEmpleado', 'fNomPersona', 'fCodPersona', 'btPersona');" />
            <input type="hidden" name="fCodPersona" id="fCodPersona" value="<?=$fCodPersona?>" />
            <input type="text" name="fCodEmpleado" id="fCodEmpleado" style="width:50px;" value="<?=$fCodEmpleado?>" readonly="readonly" />
			<input type="text" name="fNomPersona" id="fNomPersona" style="width:200px;" value="<?=$fNomPersona?>" readonly="readonly" />
            <a href="../lib/listas/listado_empleados.php?filtrar=default&cod=fCodEmpleado&nom=fNomPersona&campo3=fCodPersona&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" id="btPersona" style=" <?=$dCodPersona?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td align="right">Estado: </td>
		<td>
        	<? 
			if ($lista == "aprobar" || $lista == "conformar" || $lista == "revisar") {
				?>
				<input type="checkbox" onclick="this.checked=!this.checked;" checked="checked" />
                <select name="fEstado" id="fEstado" style="width:105px;">
                    <?=loadSelectValores("ESTADO-VACACIONES", $fEstado, 1)?>
                </select>
                <?
			} else {
				?>
                <input type="checkbox" <?=$cEstado?> onclick="chkFiltro(this.checked, 'fEstado');" />
                <select name="fEstado" id="fEstado" style="width:105px;" <?=$dEstado?>>
                    <option value=""></option>
                    <?=loadSelectValores("ESTADO-VACACIONES", $fEstado, 0)?>
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
			<input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=rh_vacaciones_form&opcion=nuevo&origen=rh_vacaciones_lista');" />
            
			<input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=vacaciones_modificar', 'gehen.php?anz=rh_vacaciones_form&opcion=modificar&origen=rh_vacaciones_lista', 'SELF', '');" />
            
			<input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=rh_vacaciones_form&opcion=ver&origen=rh_vacaciones_lista', 'SELF', '', 'registro');" /> | 
            
			<input type="button" id="btRevisar" value="Revisar" style="width:75px; <?=$btRevisar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=rh_vacaciones_form&opcion=revisar&origen=rh_vacaciones_lista', 'SELF', '', 'registro');" />
            
			<input type="button" id="btAprobar" value="Aprobar" style="width:75px; <?=$btAprobar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=rh_vacaciones_form&opcion=aprobar&origen=rh_vacaciones_lista', 'SELF', '', 'registro');" />
            
			<input type="button" id="btConformar" value="Conformar" style="width:75px; <?=$btConformar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=rh_vacaciones_form&opcion=conformar&origen=rh_vacaciones_lista', 'SELF', '', 'registro');" />
            
			<input type="button" id="btAnular" value="Anular" style="width:75px; <?=$btAnular?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=vacaciones_anular', 'gehen.php?anz=rh_vacaciones_form&opcion=anular&origen=rh_vacaciones_lista', 'SELF', '');" /> | 
            
			<input type="button" id="btImprimir" value="Imprimir" style="width:75px; <?=$btImprimir?>" onclick="cargarOpcion2(this.form, 'rh_vacaciones_pdf.php?', 'BLANK', 'height=800, width=750, left=200, top=200, resizable=no', $('#registro').val());" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:1050px; height:300px;">
<table width="100%" class="tblLista">
	<thead>
		<th scope="col" width="100"># Solicitud</th>
		<th scope="col" width="60">Empleado</th>
		<th scope="col" align="left">Nombre Completo</th>
		<th scope="col" width="75">Tipo</th>
		<th scope="col" width="75">Fecha Documento</th>
		<th scope="col" width="100">Estado</th>
    </thead>
    
    <tbody>
	<?php
	if (($_SESSION["USUARIO_ACTUAL"]) != "ADMINISTRADOR") {
		$inner_seguridad = "INNER JOIN seguridad_alterna sa ON (sa.CodOrganismo = e.CodOrganismo AND
																sa.CodDependencia = e.CodDependencia AND
																sa.Usuario = '".$_SESSION["USUARIO_ACTUAL"]."' AND
																sa.CodAplicacion = '".$_SESSION["APLICACION_ACTUAL"]."' AND
																sa.FlagMostrar = 'S')";
	}
	
	//	consulto todos
	$sql = "SELECT
				vs.Anio,
				vs.CodSolicitud,
				vs.Tipo,
				vs.Fecha,
				vs.Estado,
				p.NomCompleto,
				e.CodEmpleado
			FROM
				rh_vacacionsolicitud vs
				INNER JOIN mastpersonas p ON (p.CodPersona = vs.CodPersona)
				INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona) $inner_seguridad
			WHERE 1 $filtro";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				vs.Anio,
				vs.CodSolicitud,
				vs.Tipo,
				vs.Fecha,
				vs.Estado,
				p.NomCompleto,
				e.CodEmpleado
			FROM
				rh_vacacionsolicitud vs
				INNER JOIN mastpersonas p ON (p.CodPersona = vs.CodPersona)
				INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona) $inner_seguridad
			WHERE 1 $filtro
			ORDER BY CodSolicitud
			LIMIT ".intval($limit).", ".intval($maxlimit);
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[Anio].$field[CodSolicitud]";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$field['Anio']?>-<?=$field['CodSolicitud']?></td>
			<td align="center"><?=$field['CodEmpleado']?></td>
			<td><?=($field['NomCompleto'])?></td>
			<td align="center"><?=printValores("TIPO-VACACIONES", $field['Tipo'])?></td>
			<td align="center"><?=formatFechaDMA($field['Fecha'])?></td>
			<td align="center"><?=printValores("ESTADO-VACACIONES", $field['Estado'])?></td>
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