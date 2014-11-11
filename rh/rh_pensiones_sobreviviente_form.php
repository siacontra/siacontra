<?php
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
$FechaActual = "$AnioActual-$MesActual-$DiaActual";
if ($opcion == "nuevo") {
	$field['Estado'] = "PR";
	$field['ProcesadoPor'] = $_SESSION['CODPERSONA_ACTUAL'];
	$field['FechaProcesado'] = $Ahora;
	$field['NomProcesadoPor'] = $_SESSION['NOMBRE_USUARIO_ACTUAL'];
	$field['SitTra'] = "I";
	$field['MotivoPension']= "F";
	##
	$titulo = "Procesar Jubilaci&oacute;n";
	$accion = "nuevo";
	$label_submit = "Procesar";
	$disabled_nuevo = "disabled";
	$disabled_conformado = "disabled";
	$disabled_aprobado = "disabled";
	$display_cumple = "display:none;";
	$display_exceso = "display:none;";
	$clkCancelar = "document.getElementById('frmgeneral').submit();";
}
elseif ($opcion == "modificar" || $opcion == "ver" || $opcion == "conformar" || $opcion == "aprobar" || $opcion == "anular") {
	//	consulto datos generales
	$sql = "SELECT
				pp.*,
				(pp.TotalSueldo + pp.TotalPrimas) AS Total,
				((pp.TotalSueldo + pp.TotalPrimas) / 24) AS SueldoBase,
				p1.NomCompleto AS NomPersona,
				p1.Ndocumento,
				p1.Sexo,
				p1.Fnacimiento,
				e.CodEmpleado,
				p2.NomCompleto AS NomProcesadoPor,
				p3.NomCompleto AS NomConformadoPor,
				p4.NomCompleto AS NomAprobadoPor
			FROM
				rh_proceso_pension pp
				INNER JOIN mastpersonas p1 ON (p1.CodPersona = pp.CodPersona)
				INNER JOIN mastempleado e ON (e.CodPersona = pp.CodPersona)
				LEFT JOIN mastpersonas p2 ON (p2.CodPersona = pp.ProcesadoPor)
				LEFT JOIN mastpersonas p3 ON (p3.CodPersona = pp.ConformadoPor)
				LEFT JOIN mastpersonas p4 ON (p4.CodPersona = pp.AprobadoPor)
			WHERE pp.CodProceso = '".$sel_registros."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Registro";
		$accion = "modificar";
		$disabled_modificar = "disabled";
		$disabled_conformado = "disabled";
		$disabled_aprobado = "disabled";
		$display_modificar = "display:none;";
		$label_submit = "Modificar";
		$clkCancelar = "document.getElementById('frmgeneral').submit();";
	}
	
	elseif ($opcion == "conformar") {
		$field['ConformadoPor'] = $_SESSION['CODPERSONA_ACTUAL'];
		$field['FechaConformado'] = $Ahora;
		$field['NomConformadoPor'] = $_SESSION['NOMBRE_USUARIO_ACTUAL'];
		##	
		$titulo = "Conformar Jubilaci&oacute;n";
		$accion = "conformar";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_procesado = "disabled";
		$disabled_aprobado = "disabled";
		$disabled_cese = "disabled";
		$display_modificar = "display:none;";
		$label_submit = "Conformar";
		$clkCancelar = "document.getElementById('frmgeneral').submit();";
	}
	
	elseif ($opcion == "aprobar") {
		$field['AprobadoPor'] = $_SESSION['CODPERSONA_ACTUAL'];
		$field['FechaAprobado'] = $Ahora;
		$field['NomAprobadoPor'] = $_SESSION['NOMBRE_USUARIO_ACTUAL'];
		$field['SitTra'] = "I";
		##	
		$titulo = "Aprobar Jubilaci&oacute;n";
		$accion = "aprobar";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_procesado = "disabled";
		$disabled_conformado = "disabled";
		$disabled_cese = "disabled";
		$display_modificar = "display:none;";
		$label_submit = "Aprobar";
		$clkCancelar = "document.getElementById('frmgeneral').submit();";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Registro";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_procesado = "disabled";
		$disabled_conformado = "disabled";
		$disabled_aprobado = "disabled";
		$disabled_cese = "disabled";
		$display_modificar = "display:none;";
		$display_submit = "display:none;";
		$clkCancelar = "document.getElementById('frmgeneral').submit();";
	}
	
	if ($field['AniosServicioExceso'] == 0) $display_exceso = "display:none;";
	$FlagCumple = "S";
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$clkCancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<br />

<table width="800" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current"><a href="#" onclick="mostrarTab('tab', 1, 5);">Informaci&oacute;n General</a></li>
            <li id="li2" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 2, 5);">Antecedentes de Servicio</a></li>
            <li id="li3" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 3, 5);">Relaci&oacute;n de Sueldos</a></li>
            <li id="li4" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 4, 5);">Beneficiarios</a></li>
            <li id="li5" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 5, 5);">Relaci&oacute;n Laboral</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<div id="tab1" style="display:block;">
<form name="frmgeneral" id="frmgeneral" action="gehen.php?anz=rh_pensiones_sobreviviente_lista" method="POST" enctype="multipart/form-data" onsubmit="return false;" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />
<input type="hidden" name="fCodOrganismo" id="fCodOrganismo" value="<?=$fCodOrganismo?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" id="FlagCumple" value="<?=$FlagCumple?>" />
<input type="hidden" id="AniosServicioExceso" value="<?=$field['AniosServicioExceso']?>" />
<input type="hidden" id="CodProceso" value="<?=$field['CodProceso']?>" />
<input type="hidden" id="TipoProceso" value="P" />
<input type="hidden" id="TipoPension" value="S" />

<table width="800" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Datos del Empleado</td>
    </tr>
    <tr>
		<td class="tagForm" width="125">Empleado:</td>
		<td class="gallery clearfix">
        	<input type="hidden" id="CodPersona" value="<?=$field['CodPersona']?>" />
        	<input type="text" id="CodEmpleado" value="<?=$field['CodEmpleado']?>" style="width:40px;" disabled="disabled" />
			<input type="text" id="NomPersona" value="<?=htmlentities($field['NomPersona'])?>" style="width:245px;" disabled="disabled" />
			<a href="../lib/listas/listado_empleados.php?filtrar=default&ventana=pensiones_sobreviviente_empleados_sel&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" style=" <?=$display_modificar?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td class="tagForm" width="125">Nro. Documento:</td>
		<td>
            <input type="text" id="Ndocumento" style="width:94px;" value="<?=$field['Ndocumento']?>" disabled="disabled" />
		</td>
    </tr>
    <tr>
		<td class="tagForm">Organismo:</td>
		<td>
			<select id="CodOrganismo" style="width:300px;" disabled="disabled">
            	<option value="">&nbsp;</option>
				<?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $field['CodOrganismo'], 0)?>
			</select>
		</td>
		<td class="tagForm">Sexo:</td>
		<td>
			<select id="Sexo" style="width:100px;" disabled="disabled">
            	<option value="">&nbsp;</option>
				<?=loadSelectGeneral("SEXO", $field['Sexo'], 0)?>
			</select>
		</td>
	</tr>
    <tr>
		<td class="tagForm">Dependencia:</td>
		<td>
			<select id="CodDependencia" style="width:300px;" disabled="disabled">
            	<option value="">&nbsp;</option>
				<?=loadSelect("mastdependencias", "CodDependencia", "Dependencia", $field['CodDependencia'], 0)?>
			</select>
		</td>
		<td class="tagForm">Fecha de Nacimiento:</td>
		<td>
            <input type="text" id="Fnacimiento" style="width:60px;" value="<?=formatFechaDMA($field['Fnacimiento'])?>" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Cargo:</td>
		<td>
			<select id="CodCargo" style="width:300px;" disabled="disabled">
            	<option value="">&nbsp;</option>
				<?=loadSelect("rh_puestos", "CodCargo", "DescripCargo", $field['CodCargo'], 0)?>
			</select>
		</td>
		<td class="tagForm">Edad:</td>
		<td>
            <input type="text" id="Edad" style="width:20px;" value="<?=$field['Edad']?>" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Sueldo Actual:</td>
		<td>
            <input type="text" id="UltimoSueldo" style="width:100px; text-align:right;" value="<?=number_format($field['UltimoSueldo'], 2, ',', '.')?>" disabled="disabled" />
		</td>
		<td class="tagForm">Fecha de Ingreso:</td>
		<td>
            <input type="text" id="Fingreso" style="width:60px;" value="<?=formatFechaDMA($field['Fingreso'])?>" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td colspan="3">
			<input type="text" size="30" value="<?=$field['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" value="<?=$field['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
    <tr>
        <td colspan="4">
        	<br />
            <!-- Highlight / Error-->
            <center>
            <div class="ui-widget" id="nocumple" style="display:none;">
                <div class="ui-state-error ui-corner-all" style="width:708px; text-align:left;">
                    <p>
                    <span class="ui-icon ui-icon-alert" style="float: left;"></span>
                    <strong>El Empleado NO cumple con los requisitos para optar a la Jubilaci&oacute;n</strong>
                    </p>
                </div>
            </div>
        	<br />
            <div class="ui-widget" id="cumple" style="<?=$display_cumple?>">
                <div class="ui-state-check ui-corner-all" style="width:708px; text-align:left;">
                    <p>
                    <span class="ui-icon ui-icon-check" style="float: left;"></span>
                    <strong>El empleado cumple con los requisitos para optar a la Jubilaci&oacute;n</strong>
                    </p>
                </div>
            </div>
        	<br />
            <div class="ui-widget" id="exceso" style="<?=$display_exceso?>">
                <div class="ui-state-highlight ui-corner-all" style="width:708px; text-align:left;">
                    <p>
                    <span class="ui-icon ui-icon-info" style="float: left;"></span>
                    <strong>Al empleado se le sumaron (<span id="_AniosServicioExceso"><?=$field['AniosServicioExceso']?></span>) A&ntilde;os de Servicio en Exceso a la Edad para optar a la Jubilaci&oacute;n</strong>
                    </p>
                </div>
            </div>
        	<br />
            </center>
        </td>
    </tr>
</table>
<center>
<input type="submit" style="display:none;" />
</center>
</form>
</div>

<div id="tab2" style="display:none;">
<center>
<form name="frm_antecedentes" id="frm_antecedentes">
<input type="hidden" id="sel_antecedentes" />
<div style="overflow:scroll; width:800px; height:529px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15">&nbsp;</th>
        <th scope="col" align="left">Organismo</th>
        <th scope="col" width="75">Fecha Ingreso</th>
        <th scope="col" width="75">Fecha Egreso</th>
        <th scope="col" width="40">Años</th>
        <th scope="col" width="40">Meses</th>
        <th scope="col" width="40">Dias</th>
    </tr>
    </thead>
    
    <tbody id="lista_antecedentes">
    <?php
	$sql = "SELECT *
			FROM rh_empleado_antecedentes
			WHERE CodPersona = '".$field['CodPersona']."'
			ORDER BY FIngreso";
	$query_antecedentes = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_antecedentes = mysql_fetch_array($query_antecedentes)) {	$nro_antecedentes++;
		$TotalTiempoD += $field_antecedentes['Dias'];
		$TotalTiempoM += $field_antecedentes['Meses'];
		$TotalTiempoA += $field_antecedentes['Anios'];
		?>
		<tr class="trListaBody">
			<th>
				<?=$nro_antecedentes?>
			</th>
			<td>
            	<input type="text" name="Organismo" class="cell2" value="<?=htmlentities($field_antecedentes['Organismo'])?>" />
			</td>
			<td>
            	<input type="text" name="FIngreso" class="cell2" style="text-align:center;" value="<?=formatFechaDMA($field_antecedentes['FIngreso'])?>" />
			</td>
			<td>
            	<input type="text" name="FEgreso" class="cell2" style="text-align:center;" value="<?=formatFechaDMA($field_antecedentes['FEgreso'])?>" />
			</td>
			<td>
            	<input type="text" name="Anios" class="cell2" style="text-align:center;" value="<?=$field_antecedentes['Anios']?>" />
			</td>
			<td>
            	<input type="text" name="Meses" class="cell2" style="text-align:center;" value="<?=$field_antecedentes['Meses']?>" />
			</td>
			<td>
            	<input type="text" name="Dias" class="cell2" style="text-align:center;" value="<?=$field_antecedentes['Dias']?>" />
			</td>
		</tr>
		<?
	}
    ?>
    </tbody>
    
    <tfoot>
		<?php
		list($anios_antecedentes, $meses_antecedentes, $dias_antecedentes) = totalTiempo($TotalTiempoA, $TotalTiempoM, $TotalTiempoD);
		list($anios_organismo_antecedentes, $meses_organismo_antecedentes, $dias_organismo_antecedentes) = getEdad(formatFechaDMA($field['Fingreso']), formatFechaDMA($field['FechaProcesado']));
		list($AniosServicio, $MesesServicio, $DiasServicio) = totalTiempo($anios_antecedentes+$anios_organismo_antecedentes, $meses_antecedentes+$meses_organismo_antecedentes, $dias_antecedentes+$dias_organismo_antecedentes);
		?>
    	<tr>
        	<td colspan="2"></td>
        	<th colspan="2" align="right">Antecedentes:</th>
        	<th id="anios_antecedentes">
            	<?=$anios_antecedentes?>
            </th>
        	<th id="meses_antecedentes">
            	<?=$meses_antecedentes?>
            </th>
        	<th id="dias_antecedentes">
            	<?=$dias_antecedentes?>
            </th>
        </tr>
        <tr>
        	<td colspan="2"></td>
        	<th colspan="2" align="right">En el Organismo:</th>
        	<th id="anios_organismo_antecedentes">
            	<?=$anios_organismo_antecedentes?>
            </th>
        	<th id="meses_organismo_antecedentes">
            	<?=$meses_organismo_antecedentes?>
            </th>
        	<th id="dias_organismo_antecedentes">
            	<?=$dias_organismo_antecedentes?>
            </th>
        </tr>
        <tr>
        	<td colspan="2"></td>
        	<th colspan="2" align="right">Tiempo de Servicio:</th>
        	<th>
            	<input type="hidden" id="AniosServicio" value="<?=$AniosServicio?>" />
            	<span id="_AniosServicio"><?=$AniosServicio?></span>
            </th>
        	<th id="MesesServicio">
            	<?=$MesesServicio?>
            </th>
        	<th id="DiasServicio">
            	<?=$DiasServicio?>
            </th>
        </tr>
    </tfoot>
</table>
</div>
<input type="hidden" id="nro_antecedentes" value="<?=$nro_antecedentes?>" />
<input type="hidden" id="can_antecedentes" value="<?=$nro_antecedentes?>" />
</form>
</center>
</div>

<div id="tab3" style="display:none;">
<center>
<form name="frm_sueldos" id="frm_sueldos">
<div id="lista_sueldos">
<?php
	$Secuencia = 0;
//	consulto los conceptos disponibles para el calculo de jubilacion
$sql = "SELECT
			CodConcepto,
			Descripcion
		FROM pr_concepto
		WHERE
			FlagJubilacion = 'S' AND
			Tipo = 'I'
		ORDER BY CodConcepto";
$query_conceptos = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
while($field_conceptos = mysql_fetch_array($query_conceptos)) {
	?>
    <div style="width:796px;" class="divFormCaption"><?=htmlentities($field_conceptos['Descripcion'])?></div>
    <div style="overflow:scroll; width:800px; height:235px;">
    <div style="float:left; width:33%">
    <table width="100%" class="tblLista">
        <thead>
        <tr>
            <th scope="col" width="15">#</th>
            <th scope="col" width="75">Periodo</th>
            <th scope="col" align="right">Monto</th>
        </tr>
        </thead>
        
        <tbody>
        <?php
		$nro_sueldos = 0;
		$sql = "(SELECT
					Periodo,
					Monto
				 FROM pr_tiponominaempleadoconcepto
				 WHERE
					CodPersona = '".$field['CodPersona']."' AND
					CodConcepto = '".$field_conceptos['CodConcepto']."' AND
					CodTipoProceso = 'FIN'
				)
				UNION
				(SELECT
					Periodo,
					SUM(Monto) AS Monto
				 FROM pr_tiponominaempleadoconcepto
				 WHERE
					CodPersona = '".$field['CodPersona']."' AND
					CodConcepto = '".$field_conceptos['CodConcepto']."' AND
					CodTipoProceso <> 'FIN' AND CodTipoProceso <> 'ADE'
					 GROUP BY CodPersona, CodConcepto
				)
				ORDER BY Periodo DESC
				LIMIT 0, 8";
        $query_sueldos = mysql_query($sql) or die ($sql.mysql_error());
        while ($field_sueldos = mysql_fetch_array($query_sueldos)) {
            ?>
            <tr class="trListaBody">
                <th>
                    <input type="hidden" name="Secuencia" value="<?=++$Secuencia?>" />
                    <?=++$nro_sueldos?>
                </th>
                <td>
                    <input type="text" name="Periodo" class="cell2" style="text-align:center;" value="<?=$field_sueldos['Periodo']?>" />
                </td>
                <td>
                   	<input type="hidden" name="CodConcepto" value="<?=$field_sueldos['CodConcepto']?>" />
                    <input type="text" name="Monto" class="cell2" style="text-align:right;" value="<?=number_format($field_sueldos['Monto'], 2, ',', '.')?>" />
                </td>
            </tr>
            <?
        }
        ?>
        </tbody>
    </table>
    </div>
    
    <div style="float:left; width:33%">
    <table width="100%" class="tblLista">
        <thead>
        <tr>
            <th scope="col" width="15">#</th>
            <th scope="col" width="75">Periodo</th>
            <th scope="col" align="right">Monto</th>
        </tr>
        </thead>
        
        <tbody>
        <?php
		$sql = "(SELECT
					Periodo,
					Monto
				 FROM pr_tiponominaempleadoconcepto
				 WHERE
					CodPersona = '".$field['CodPersona']."' AND
					CodConcepto = '".$field_conceptos['CodConcepto']."' AND
					CodTipoProceso = 'FIN'
				)
				UNION
				(SELECT
					Periodo,
					SUM(Monto) AS Monto
				 FROM pr_tiponominaempleadoconcepto
				 WHERE
					CodPersona = '".$field['CodPersona']."' AND
					CodConcepto = '".$field_conceptos['CodConcepto']."' AND
					CodTipoProceso <> 'FIN' AND CodTipoProceso <> 'ADE'
					 GROUP BY CodPersona, CodConcepto
				)
				ORDER BY Periodo DESC
				LIMIT 8, 8";
        $query_sueldos = mysql_query($sql) or die ($sql.mysql_error());
        while ($field_sueldos = mysql_fetch_array($query_sueldos)) {
            ?>
            <tr class="trListaBody">
                <th>
                    <input type="hidden" name="Secuencia" value="<?=++$Secuencia?>" />
                    <?=++$nro_sueldos?>
                </th>
                <td>
                    <input type="text" name="Periodo" class="cell2" style="text-align:center;" value="<?=$field_sueldos['Periodo']?>" />
                </td>
                <td>
                   	<input type="hidden" name="CodConcepto" value="<?=$field_sueldos['CodConcepto']?>" />
                    <input type="text" name="Monto" class="cell2" style="text-align:right;" value="<?=number_format($field_sueldos['Monto'], 2, ',', '.')?>" />
                </td>
            </tr>
            <?
        }
        ?>
        </tbody>
    </table>
    </div>
    
    <div style="float:left; width:34%">
    <table width="100%" class="tblLista">
        <thead>
        <tr>
            <th scope="col" width="15">#</th>
            <th scope="col" width="75">Periodo</th>
            <th scope="col" align="right">Monto</th>
        </tr>
        </thead>
        
        <tbody>
        <?php
		$sql = "(SELECT
					Periodo,
					Monto
				 FROM pr_tiponominaempleadoconcepto
				 WHERE
					CodPersona = '".$field['CodPersona']."' AND
					CodConcepto = '".$field_conceptos['CodConcepto']."' AND
					CodTipoProceso = 'FIN'
				)
				UNION
				(SELECT
					Periodo,
					SUM(Monto) AS Monto
				 FROM pr_tiponominaempleadoconcepto
				 WHERE
					CodPersona = '".$field['CodPersona']."' AND
					CodConcepto = '".$field_conceptos['CodConcepto']."' AND
					CodTipoProceso <> 'FIN' AND CodTipoProceso <> 'ADE'
					 GROUP BY CodPersona, CodConcepto
				)
				ORDER BY Periodo DESC
				LIMIT 16, 8";
        $query_sueldos = mysql_query($sql) or die ($sql.mysql_error());
        while ($field_sueldos = mysql_fetch_array($query_sueldos)) {
            ?>
            <tr class="trListaBody">
                <th>
                    <input type="hidden" name="Secuencia" value="<?=++$Secuencia?>" />
                    <?=++$nro_sueldos?>
                </th>
                <td>
                    <input type="text" name="Periodo" class="cell2" style="text-align:center;" value="<?=$field_sueldos['Periodo']?>" />
                </td>
                <td>
                   	<input type="hidden" name="CodConcepto" value="<?=$field_sueldos['CodConcepto']?>" />
                    <input type="text" name="Monto" class="cell2" style="text-align:right;" value="<?=number_format($field_sueldos['Monto'], 2, ',', '.')?>" />
                </td>
            </tr>
            <?
        }
        ?>
        </tbody>
    </table>
    </div>
    </div>
    <?
}
?>
</div>
<input type="hidden" id="NroCotizaciones" value="<?=$NroCotizaciones?>" />
</form>

<table width="800" class="tblFiltro">
	<tr>
		<td align="right" width="60">Sueldos:</td>
		<td>
        	<input type="text" id="TotalSueldo" style="width:60px; text-align:right;" value="<?=number_format($field['TotalSueldo'], 2, ',', '.')?>" disabled />
		</td>
		<td align="right" width="60">Primas:</td>
		<td>
        	<input type="text" id="TotalPrimas" style="width:60px; text-align:right;" value="<?=number_format($field['TotalPrimas'], 2, ',', '.')?>" disabled />
		</td>
		<td align="right" width="60">Total:</td>
		<td>
        	<input type="text" id="Total" style="width:60px; text-align:right;" value="<?=number_format($field['Total'], 2, ',', '.')?>" disabled />
		</td>
		<td align="right" width="60">Porcentaje:</td>
		<td>
        	<input type="text" id="Coeficiente" style="width:30px; text-align:right;" value="<?=number_format($field['Coeficiente'], 2, ',', '.')?>" disabled />
		</td>
		<td align="right" width="60">Base:</td>
		<td>
        	<input type="text" id="SueldoBase" style="width:60px; text-align:right;" value="<?=number_format($field['SueldoBase'], 2, ',', '.')?>" disabled />
		</td>
		<td align="right" width="60">Jubilaci&oacute;n:</td>
		<td>
        	<input type="text" id="MontoJubilacion" style="width:60px; text-align:right;" value="<?=number_format($field['MontoJubilacion'], 2, ',', '.')?>" disabled />
		</td>
	</tr>
</table>
</center>
</div>

<div id="tab4" style="display:none;">
<center>
<form name="frm_beneficiarios" id="frm_beneficiarios">
<input type="hidden" id="sel_beneficiarios" />
<table width="800" class="tblBotones">
    <tr class="gallery clearfix">
        <td align="right">
            <input type="button" class="btLista" value="Insertar" onclick="insertar(this, 'beneficiarios', 1, 'accion=pensiones_sobreviviente_beneficiarios_insertar');" />
            <input type="button" class="btLista" value="Borrar" onclick="quitar(this, 'beneficiarios');" />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:800px; height:529px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15">&nbsp;</th>
        <th scope="col" width="75" align="right">Documento</th>
        <th scope="col" align="left">Nombre Completo</th>
        <th scope="col" width="15">Pr.</th>
        <th scope="col" width="100">Parentesco</th>
        <th scope="col" width="75">Fecha Nacimiento</th>
        <th scope="col" width="100">Sexo</th>
        <th scope="col" width="40">Edad</th>
    </tr>
    </thead>
    
    <tbody id="lista_beneficiarios">
    <?php
	$sql = "SELECT
				bp.NroDocumento,
				bp.NombreCompleto,
				bp.Parentesco,
				bp.FechaNacimiento,
				bp.Sexo,
				bp.FlagIncapacitados,
				bp.FlagEstudia,
				bp.FlagPrincipal
			FROM
				rh_beneficiariopension bp
				LEFT JOIN mastmiscelaneosdet md ON (md.CodDetalle = bp.Parentesco AND
													md.CodMaestro = 'PARENT')
			WHERE bp.CodPersona = '".$field['CodPersona']."'
			ORDER BY Secuencia";
	$query_beneficiarios = mysql_query($sql) or die ($sql.mysql_error());	$nro_beneficiarios=0;
	while ($field_beneficiarios = mysql_fetch_array($query_beneficiarios)) {
		list($EdadBeneficiario) = getEdad(formatFechaDMA($field_beneficiarios['FechaNacimiento']), formatFechaDMA($FechaActual));
		?>
		<tr class="trListaBody">
            <th>
                <?=++$nro_beneficiarios?>
            </th>
            <td>
                <input type="text" name="NroDocumento" class="cell2" style="text-align:right;" value="<?=$field_beneficiarios['NroDocumento']?>" readonly="readonly" />
            </td>
            <td>
                <input type="text" name="NombreCompleto" class="cell2" value="<?=htmlentities($field_beneficiarios['NombreCompleto'])?>" readonly="readonly" />
            </td>
            <td align="center">
                <input type="checkbox" name="FlagPrincipal" <?=chkFlag($field_beneficiarios['FlagPrincipal'])?> />
            </td>
            <td align="center">
                <select name="Parentesco" class="cell2">
                    <?=getMiscelaneos($field_beneficiarios['Parentesco'], 'PARENT', 1)?>
                </select>
            </td>
            <td>
                <input type="text" name="FechaNacimiento" class="cell2" style="text-align:center;" value="<?=formatFechaDMA($field_beneficiarios['FechaNacimiento'])?>" readonly="readonly" />
            </td>
            <td>
                <select name="Sexo" class="cell2">
                    <?=loadSelectGeneral("SEXO", $field_beneficiarios['Sexo'], 1)?>
                </select>
            </td>
            <td align="center">
                <input type="hidden" name="FlagIncapacitados" value="<?=$field_beneficiarios['FlagIncapacitados']?>" />
                <input type="hidden" name="FlagEstudia" value="<?=$field_beneficiarios['FlagEstudia']?>" />
                <?=$EdadBeneficiario?>
            </td>
        </tr>
		<?
	}
    ?>
    </tbody>
</table>
</div>
<input type="hidden" id="nro_beneficiarios" value="<?=$nro_beneficiarios?>" />
<input type="hidden" id="can_beneficiarios" value="<?=$nro_beneficiarios?>" />
</form>
</center>
</div>

<div id="tab5" style="display:none;">
<form name="frmjubilacion" id="frmjubilacion" action="gehen.php?anz=rh_pensiones_sobreviviente_lista" method="POST" enctype="multipart/form-data" onsubmit="return pensiones_sobreviviente('<?=$accion?>');" autocomplete="off">

<table width="800" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Datos de la Jubilaci&oacute;n</td>
    </tr>
    <tr>
		<td class="tagForm"># Proceso:</td>
		<td>
            <input type="text" style="width:35px;" class="codigo" value="<?=$field['CodProceso']?>" disabled="disabled" />
		</td>
		<td class="tagForm">Motivo:</td>
		<td>
			<select id="MotivoPension" style="width:115px;" <?=$disabled_ver?>>
				<?=loadSelectValores("MOTIVO-PENSION", $field['MotivoPension'], 1)?>
			</select>
		</td>
    </tr>
    <tr>
		<td class="tagForm">Estado:</td>
		<td>
        	<input type="hidden" id="Estado" value="<?=$field['Estado']?>" />
            <input type="text" style="width:94px;" class="codigo" value="<?=strtoupper(printValores("ESTADO-PENSION", $field['Estado']))?>" disabled="disabled" />
		</td>
		<td class="tagForm">Sueldo Pensi&oacute;n:</td>
		<td>
			<input type="text" id="MontoPension" style="width:110px; text-align:right;" value="<?=number_format($field['MontoPension'], 2, ',', '.')?>" onfocus="numeroFocus(this);" onblur="numeroBlur(this);" disabled="disabled" />
		</td>
    </tr>
    <tr>
		<td class="tagForm" width="125">Procesado Por:</td>
		<td class="gallery clearfix">
        	<input type="hidden" id="ProcesadoPor" value="<?=$field['ProcesadoPor']?>" />
			<input type="text" id="NomProcesadoPor" value="<?=htmlentities($field['NomProcesadoPor'])?>" style="width:250px;" disabled="disabled" />
        </td>
		<td class="tagForm" width="125">Fecha:</td>
		<td>
            <input type="text" id="FechaProcesado" style="width:110px;" value="<?=formatDateFull($field['FechaProcesado'], 0)?>" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Observaciones:</td>
		<td colspan="3">
        	<textarea id="ObsProcesado" style="width:95%; height:30px;" <?=$disabled_procesado?>><?=htmlentities($field['ObsProcesado'])?></textarea>
		</td>
    </tr>
    <tr>
		<td class="tagForm">Conformado Por:</td>
		<td class="gallery clearfix">
        	<input type="hidden" id="ConformadoPor" value="<?=$field['ConformadoPor']?>" />
			<input type="text" id="NomConformadoPor" value="<?=htmlentities($field['NomConformadoPor'])?>" style="width:250px;" disabled="disabled" />
        </td>
		<td class="tagForm">Fecha:</td>
		<td>
            <input type="text" id="FechaConformado" style="width:110px;" value="<?=formatDateFull($field['FechaConformado'], 0)?>" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Observaciones:</td>
		<td colspan="3">
        	<textarea id="ObsConformado" style="width:95%; height:30px;" <?=$disabled_conformado?>><?=htmlentities($field['ObsConformado'])?></textarea>
		</td>
    </tr>
    <tr>
		<td class="tagForm">Aprobado Por:</td>
		<td class="gallery clearfix">
        	<input type="hidden" id="AprobadoPor" value="<?=$field['AprobadoPor']?>" />
			<input type="text" id="NomAprobadoPor" value="<?=htmlentities($field['NomAprobadoPor'])?>" style="width:250px;" disabled="disabled" />
        </td>
		<td class="tagForm">Fecha:</td>
		<td>
            <input type="text" id="FechaAprobado" style="width:110px;" value="<?=formatDateFull($field['FechaAprobado'], 0)?>" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Observaciones:</td>
		<td colspan="3">
        	<textarea id="ObsAprobado" style="width:95%; height:30px;" <?=$disabled_aprobado?>><?=htmlentities($field['ObsAprobado'])?></textarea>
		</td>
    </tr>
	<tr>
    	<td colspan="4" class="divFormCaption">Planilla</td>
    </tr>
	<tr>
		<td class="tagForm">Tipo de N&oacute;mina:</td>
		<td>
            <select id="CodTipoNom" style="width:200px;" <?=$disabled_cese?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("tiponomina", "CodTipoNom", "Nomina", $field['CodTipoNom'], 0);?>
            </select>
		</td>
		<td class="tagForm">Tipo de Trabajador:</td>
		<td>
            <select id="CodTipoTrabajador" style="width:200px;" <?=$disabled_cese?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("rh_tipotrabajador", "CodTipoTrabajador", "TipoTrabajador", $field['CodTipoTrabajador'], 0);?>
            </select>
		</td>
	</tr>
	<tr>
    	<td colspan="4" class="divFormCaption">Cese</td>
    </tr>
    <tr>
		<td class="tagForm" width="125">Estado:</td>
		<td>
            <input type="radio" name="SitTra" id="SitTraA" value="A" <?=chkOpt($field['SitTra'], "A");?> disabled /> Activo
			&nbsp; &nbsp; 
            <input type="radio" name="SitTra" id="SitTraI" value="I" <?=chkOpt($field['SitTra'], "I");?> disabled /> Inactivo
		</td>
		<td class="tagForm" width="125">Motivo del Cese:</td>
		<td>
            <select id="CodMotivoCes" style="width:200px;" <?=$disabled_cese?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("rh_motivocese", "CodMotivoCes", "MotivoCese", $field['CodMotivoCes'], 0);?>
            </select>
		</td>
	</tr>
    <tr>
		<td class="tagForm">Fecha de Cese:</td>
		<td>
            <input type="text" id="Fegreso" style="width:60px;" class="datepicker" maxlength="10" value="<?=formatFechaDMA($field['Fegreso'])?>" <?=$disabled_cese?> />
		</td>
		<td class="tagForm">Explicaci&oacute;n:</td>
		<td>
            <input type="text" id="ObsCese" style="width:95%;" value="<?=$field['ObsCese']?>" <?=$disabled_cese?> />
		</td>
	</tr>
</table>
<center>
<input type="submit" style="display:none;" />
</center>
</form>
</div>

<center>
<input type="button" value="<?=$label_submit?>" style="width:75px; <?=$display_submit?>" onclick="pensiones_sobreviviente('<?=$accion?>');" />
<input type="button" value="Cancelar" style="width:75px;" onclick="<?=$clkCancelar?>" />
</center>
<div style="width:800px" class="divMsj">Campos Obligatorios *</div>