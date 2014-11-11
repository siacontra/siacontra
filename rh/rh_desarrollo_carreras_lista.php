<?php
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
if ($lista == "todos") {
	$titulo = "Desarrollo de Carreras y Sucesi&oacute;n (Listado)";
	$btModificar = "display:none;";
	$btTerminar = "display:none;";
}
elseif ($lista == "modificar") {
	$titulo = "Desarrollo de Carreras y Sucesi&oacute;n (Actualizar)";
	$btNuevo = "display:none;";
	$btTerminar = "display:none;";
}
elseif ($lista == "terminar") {
	$titulo = "Desarrollo de Carreras y Sucesi&oacute;n (Terminar)";
	$btNuevo = "display:none;";
	$btModificar = "display:none;";
}
//	------------------------------------
if ($filtrar == "default") {
	$fEstado = "AB";
	$fCodOrganismo = $_SESSION["ORGANISMO_ACTUAL"];
	$fCodDependencia = $_SESSION["DEPENDENCIA_ACTUAL"];
	$fPeriodo = "$AnioActual";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fEstado != "") { $cEstado = "checked"; $filtro.=" AND (ac.Estado = '".$fEstado."')"; } else $dEstado = "disabled";
if ($fBuscar != "") {
	$cBuscar = "checked";
	$filtro.=" AND (ac.CodPersona LIKE '%".$fBuscar."%' OR
					 ac.NomCompleto LIKE '%".$fBuscar."%' OR
					 ac.Ndocumento LIKE '%".$fBuscar."%' OR
					 d.Dependencia LIKE '%".$fBuscar."%' OR
					 o.Organismo LIKE '%".$fBuscar."%' OR
					 ep.Descripcion LIKE '%".$fBuscar."%')";
} else $dBuscar = "disabled";
if ($fCodOrganismo != "") { $cCodOrganismo = "checked"; $filtro.=" AND (ac.CodOrganismo = '".$fCodOrganismo."')"; } else $dCodOrganismo = "disabled";
if ($fCodDependencia != "") { $cCodDependencia = "checked"; $filtro.=" AND (ac.CodDependencia = '".$fCodDependencia."')"; } else $dCodDependencia = "disabled";
if ($fPeriodo != "") { $cPeriodo = "checked"; $filtro.=" AND (ac.Periodo LIKE '".$fPeriodo."-%')"; } else $dPeriodo = "disabled";
if ($fSecuencia != "") { $cSecuencia = "checked"; $filtro.=" AND (ac.Secuencia LIKE '".$fSecuencia."-%')"; } else $dSecuencia = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_desarrollo_carreras_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="registro" id="registro" value="<?=$registro?>" />
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
        <td>
            <input type="checkbox" <?=$cCodOrganismo?> onclick="this.checked=!this.checked;" />
            <select name="fCodOrganismo" id="fCodOrganismo" style="width:300px;" onChange="getOptionsSelect(this.value, 'dependencia_filtro', 'fCodDependencia', true);" <?=$dCodOrganismo?>>
                <?=getOrganismos($fCodOrganismo, 3)?>
            </select>
		</td>
		<td align="right" width="125">Buscar:</td>
        <td>
            <input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fBuscar');" />
            <input type="text" name="fBuscar" id="fBuscar" style="width:295px;" value="<?=$fBuscar?>" <?=$dBuscar?> />
		</td>
	</tr>
	<tr>
		<td align="right" width="125">Dependencia:</td>
        <td>
            <input type="checkbox" <?=$cCodDependencia?> onclick="chkFiltro(this.checked, 'fCodDependencia');" />
            <select name="fCodDependencia" id="fCodDependencia" style="width:300px;" <?=$dCodDependencia?>>
            	<option value="">&nbsp;</option>
                <?=getDependencias($fCodDependencia, $fCodOrganismo, 0)?>
            </select>
		</td>
		<td align="right">Evaluaci&oacute;n:</td>
		<td>
            <input type="checkbox" <?=$cSecuencia?> onclick="chkFiltro(this.checked, 'fSecuencia');" />
            <select name="fSecuencia" id="fSecuencia" style="width:300px;" <?=$dSecuencia?>>
            	<option value="">&nbsp;</option>
                <?=loadSelectEvaluaciones($fCodOrganismo, $fSecuencia, 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td align="right">Periodo:</td>
        <td>
            <input type="checkbox" <?=$cPeriodo?> onclick="chkFiltro(this.checked, 'fPeriodo');" />
            <input type="text" name="fPeriodo" id="fPeriodo" style="width:50px;" maxlength="4" value="<?=$fPeriodo?>" <?=$dPeriodo?> />
		</td>
		<td align="right">Estado:</td>
		<td>
		<?php
		if ($lista == "modificar" || $lista == "terminar") {
			?>
           	<input type="checkbox" <?=$cEstado?> onclick="this.checked=!this.checked;" />
       		<select name="fEstado" id="fEstado" style="width:125px;" <?=$dEstado?>>
               	<?=loadSelectValores("ESTADO-CARRERAS", $fEstado, 1)?>
           	</select>
			<?
		}
		else {
			?>
           	<input type="checkbox" <?=$cEstado?> onclick="chkFiltro(this.checked, 'fEstado');" />
       		<select name="fEstado" id="fEstado" style="width:125px;" <?=$dEstado?>>
               	<option value="">&nbsp;</option>
               	<?=loadSelectValores("ESTADO-CARRERAS", $fEstado, 0)?>
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
<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right" class="gallery clearfix">
			<a id="aImprimir" href="pagina.php?iframe=true" rel="prettyPhoto[iframe1]" style="display:none;"></a>
			<input type="button" id="btNuevo" value="Iniciar" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=rh_desarrollo_carreras_form&opcion=nuevo');" />
			<input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=rh_desarrollo_carreras_form&opcion=ver', 'SELF', '', $('#registro').val());" /> | 
			<input type="button" id="btImprimir" value="Imprimir" style="width:75px; <?=$btImprimir?>" onclick="abrirReporte(this.form, 'aImprimir', 'rh_desarrollo_carreras_pdf', '100%', '100%');" />
			<input type="button" id="btModificar" value="Actualizar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=desarrollo_carreras_modificar', 'gehen.php?anz=rh_desarrollo_carreras_form&opcion=modificar', 'SELF', '');" />
			<input type="button" id="btTerminar" value="Terminar" style="width:75px; <?=$btTerminar?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=desarrollo_carreras_terminar', 'gehen.php?anz=rh_desarrollo_carreras_form&opcion=terminar', 'SELF', '');" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:1000px; height:300px;">
<table width="1800" class="tblLista">
	<thead>
    <tr>
		<th scope="col" width="60">Empleado</th>
		<th scope="col" align="left">Nombre Completo</th>
		<th scope="col" width="75">Nro. Documento</th>
		<th scope="col" width="75">Estado</th>
		<th scope="col" width="75">F.Ingreso</th>
		<th scope="col" width="500" align="left">Cargo</th>
		<th scope="col" width="500" align="left">Dependencia</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				ac.CodOrganismo,
				ac.Secuencia,
				ac.Codigo
			FROM
				rh_asociacioncarreras ac
				INNER JOIN mastpersonas p ON (p.CodPersona = ac.CodPersona)
				INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
				INNER JOIN mastorganismos o ON (o.CodOrganismo = ac.CodOrganismo)
				INNER JOIN rh_evaluacionperiodo ep ON (ep.CodOrganismo = ac.CodOrganismo AND
														ep.Secuencia = ac.Secuencia)
				INNER JOIN mastdependencias d ON (d.CodDependencia = ac.CodDependencia)
			WHERE 1 $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				ac.CodOrganismo,
				ac.Secuencia,
				ac.Codigo,
				ac.CodPersona,
				ac.DescripCargo,
				ac.Periodo,
				ac.Estado,
				p.NomCompleto,
				p.Ndocumento,
				e.CodEmpleado,
				e.Fingreso,
				o.Organismo,
				ep.Descripcion AS NomEvaluacion,
				d.Dependencia
			FROM
				rh_asociacioncarreras ac
				INNER JOIN mastpersonas p ON (p.CodPersona = ac.CodPersona)
				INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
				INNER JOIN mastorganismos o ON (o.CodOrganismo = ac.CodOrganismo)
				INNER JOIN rh_evaluacionperiodo ep ON (ep.CodOrganismo = ac.CodOrganismo AND
														ep.Secuencia = ac.Secuencia)
				INNER JOIN mastdependencias d ON (d.CodDependencia = ac.CodDependencia)
			WHERE 1 $filtro
			ORDER BY CodOrganismo, Secuencia DESC, CodPersona
			LIMIT ".intval($limit).", ".intval($maxlimit);
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[CodOrganismo].$field[Secuencia].$field[Codigo]";
		if ($Grupo1 != $field['CodOrganismo']) {
			$Grupo1 = $field['CodOrganismo'];
			$Grupo2 = "";
			?>
			<tr class="trListaBody2">
				<td colspan="7"><?=$field['Organismo']?></td>
			</tr>
			<?
		}
		if ($Grupo2 != $field['Secuencia']) {
			$Grupo2 = $field['Secuencia'];
			?>
			<tr class="trListaBody3">
				<td colspan="7"><?=$field['NomEvaluacion']?></td>
			</tr>
			<?
		}
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$field['CodEmpleado']?></td>
			<td><?=htmlentities($field['NomCompleto'])?></td>
			<td align="right"><?=$field['Ndocumento']?></td>
			<td align="center"><?=printValores("ESTADO-CARRERAS", $field['Estado'])?></td>
			<td align="center"><?=formatFechaDMA($field['Fingreso'])?></td>
			<td><?=htmlentities($field['DescripCargo'])?></td>
			<td><?=htmlentities($field['Dependencia'])?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
<table width="1000">
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

<?php
//	si aprobo un registro muestro el reporte
if ($imprimir == "si") {
	?>
    <script type="text/javascript">
	$(document).ready(function() {
        abrirReporte(document.getElementById('frmentrada'), 'aImprimir', 'rh_desarollo_carreras_pdf.php', '100%', '100%');
    });
    </script>
    <?
}
?>