<?php
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
$FechaActual = "$AnioActual-$MesActual-$DiaActual";
if ($opcion == "nuevo") {
	$field['Estado'] = "A";
	$field['Periodo'] = "$AnioActual-$MesActual";
	$field['FechaInicio'] = "$AnioActual-$MesActual-21";
	$field['FechaFin'] = formatFechaAMD(obtenerFechaFin("21-$MesActual-$AnioActual", 30));
	$field['TotalDiasPeriodo'] = 30;
	$field['TotalFeriados'] = getDiasFeriados(formatFechaDMA($field['FechaInicio']), formatFechaDMA($field['FechaFin']));
	$field['TotalDiasPago'] = getDiasHabiles(formatFechaDMA($field['FechaInicio']), formatFechaDMA($field['FechaFin']));
	$field['ValorDia'] = getUT($_PARAMETRO['UTANIO']) * $_PARAMETRO['UTPORC'] / 100;
	$field['CodTipoNom'] = $_SESSION["NOMINA_ACTUAL"];
	$field['HorasDiaria'] = $_PARAMETRO['HORADIR'];
	$field['HorasSemanal'] = $_PARAMETRO['HORADIR'] * $_PARAMETRO['HORDIAS'];
	$field['ValorSemanal'] = $field['ValorDia'] * $_PARAMETRO['HORDIAS'];
	$field['ValorMes'] = $field['ValorDia'] * $field['TotalDiasPago'];
	##
	$titulo = "Nuevo Registro";
	$accion = "nuevo";
	$label_submit = "Guardar";
	$disabled_nuevo = "disabled";
	$clkCancelar = "document.getElementById('frmentrada').submit();";
}
elseif ($opcion == "modificar" || $opcion == "ver" || $opcion == "cerrar") {
	list($Anio, $CodOrganismo, $CodBonoAlim) = split("[_]", $registro);
	//	consulto datos generales
	$sql = "SELECT *
			FROM rh_bonoalimentacion
			WHERE
				Anio = '".$Anio."' AND
				CodOrganismo = '".$CodOrganismo."' AND
				CodBonoAlim = '".$CodBonoAlim."'";

	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Registro";
		$accion = "modificar";
		$disabled_modificar = "disabled";
		$label_submit = "Modificar";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Registro";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_empleados = "disabled";
		$display_submit = "display:none;";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
	}
	
	elseif ($opcion == "cerrar") {
		$titulo = "Cerrar Registro";
		$accion = "cerrar";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_empleados = "disabled";
		$label_submit = "Cerrar";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
	}
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$clkCancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<table width="700" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current"><a href="#" onclick="mostrarTab('tab', 1, 2);">Informaci&oacute;n General</a></li>
            <li id="li2" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 2, 2);">Empleados</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<div id="tab1" style="display:block;">
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_bono_periodos_lista" method="POST" enctype="multipart/form-data" onsubmit="return bono_periodos(this, '<?=$accion?>');" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />
<input type="hidden" name="fCodOrganismo" id="fCodOrganismo" value="<?=$fCodOrganismo?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" id="Anio" value="<?=$field['Anio']?>" />

<table width="700" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Informaci&oacute;n del Periodo</td>
    </tr>
	<tr>
		<td class="tagForm" width="125">* Organismo:</td>
		<td>
			<select id="CodOrganismo" style="width:300px;" <?=$disabled_modificar?>>
				<?=getOrganismos($field['CodOrganismo'], 3)?>
			</select>
		</td>
		<td class="tagForm" width="125">N&uacute;mero:</td>
		<td>
            <input type="text" id="CodBonoAlim" style="width:60px;" class="codigo" value="<?=$field['CodBonoAlim']?>" disabled />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Descripci&oacute;n:</td>
		<td>
            <input type="text" id="Descripcion" style="width:295px;" maxlength="100" value="<?=htmlentities($field['Descripcion'])?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">Estado:</td>
		<td>
        	<input type="hidden" id="Estado" value="<?=$field['Estado']?>" />
            <input type="text" style="width:60px;" class="codigo" value="<?=strtoupper(printValores("ESTADO-BONO", $field['Estado']))?>" disabled />
		</td>
    </tr>
	<tr>
		<td class="tagForm">* N&oacute;mina</td>
		<td>
			<select id="CodTipoNom" style="width:175px;" onchange="$('#lista_empleados').html('');" <?=$disabled_modificar?>>
            	<option value="">&nbsp;</option>
				<?=loadSelect("tiponomina", "CodTipoNom", "Nomina", $field['CodTipoNom'], 0)?>
			</select>
		</td>
		<td class="tagForm">* Periodo:</td>
		<td>
            <input type="text" id="Periodo" style="width:60px;" maxlength="7" value="<?=$field['Periodo']?>" <?=$disabled_ver?> />
		</td>
    </tr>
	<tr>
		<td class="tagForm">* Inicio</td>
		<td>
            <input type="text" id="FechaInicio" style="width:60px;" class="datepicker" maxlength="10" value="<?=formatFechaDMA($field['FechaInicio'])?>" onchange="getDiasBonoPeriodo();" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">* Fin:</td>
		<td>
            <input type="text" id="FechaFin" style="width:60px;" class="datepicker" maxlength="10" value="<?=formatFechaDMA($field['FechaFin'])?>" onchange="getDiasBonoPeriodo();" <?=$disabled_ver?> />
		</td>
    </tr>
	<tr>
    	<td colspan="4" class="divFormCaption">Dias del Periodo</td>
    </tr>
	<tr>
		<td class="tagForm">Dias del Periodo:</td>
		<td>
            <input type="text" id="TotalDiasPeriodo" style="width:60px; text-align:right;" value="<?=number_format($field['TotalDiasPeriodo'], 0, '', '.')?>" disabled />
		</td>
		<td class="tagForm">Horas Diaria:</td>
		<td>
            <input type="text" id="HorasDiaria" style="width:60px; text-align:right;" value="<?=number_format($field['HorasDiaria'], 2, ',', '.')?>" disabled />
		</td>
    </tr>
	<tr>
		<td class="tagForm">Dias de Pago:</td>
		<td>
            <input type="text" id="TotalDiasPago" style="width:60px; text-align:right;" value="<?=number_format($field['TotalDiasPago'], 0, '', '.')?>" disabled />
		</td>
		<td class="tagForm">Horas Semanales:</td>
		<td>
            <input type="text" id="HorasSemanal" style="width:60px; text-align:right;" value="<?=number_format($field['HorasSemanal'], 2, ',', '.')?>" disabled />
		</td>
    </tr>
	<tr>
		<td class="tagForm">Dias Feriados:</td>
		<td>
            <input type="text" id="TotalFeriados" style="width:60px; text-align:right;" value="<?=number_format($field['TotalFeriados'], 0, '', '.')?>" disabled />
		</td>
		<td class="tagForm">Valor Semanal:</td>
		<td>
            <input type="text" id="ValorSemanal" style="width:60px; text-align:right;" value="<?=number_format($field['ValorSemanal'], 2, ',', '.')?>" disabled />
		</td>
    </tr>
	<tr>
		<td class="tagForm">Valor Diario:</td>
		<td>
            <input type="text" id="ValorDia" style="width:60px; text-align:right;" value="<?=number_format($field['ValorDia'], 2, ',', '.')?>" disabled />
		</td>
		<td class="tagForm">Valor Mensual:</td>
		<td>
            <input type="text" id="ValorMes" style="width:60px; text-align:right;" value="<?=number_format($field['ValorMes'], 2, ',', '.')?>" disabled />
		</td>
    </tr>
    <tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td colspan="3">
			<input type="text" size="30" value="<?=$field['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" value="<?=$field['UltimaFecha']?>" disabled="disabled" />
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
<form name="frm_empleados" id="frm_empleados">
<input type="hidden" id="sel_empleados" />
<table width="700" class="tblBotones">
    <tr>
        <td>
            <input type="button" value="Importar Empleados" onClick="bono_periodos_empleados_importar();" <?=$disabled_empleados?> />
        </td>
        <td align="right" class="gallery clearfix">
            <a id="a_empleados" href="pagina.php?iframe=true&width=100%&height=500" rel="prettyPhoto[iframe2]" style="display:none;"></a>
            <input type="button" class="btLista" value="Insertar" onclick="bono_periodos_abrir_lista_empleados();" <?=$disabled_empleados?> />
            <input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'empleados');" <?=$disabled_empleados?> />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:700px; height:300px;">
<table width="1500" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15">&nbsp;</th>
        <th scope="col" width="30">Empleado</th>
        <th scope="col" width="70">Cedula</th>
        <th scope="col" width="350" align="left">Nombre Completo</th>
        <th scope="col" width="450" align="left">Cargo</th>
        <th scope="col" align="left">Dependencia</th>
    </tr>
    </thead>
    
    <tbody id="lista_empleados">
    <?php
	$sql = "SELECT
				bad.Anio,
				bad.CodOrganismo,
				bad.CodBonoAlim,
				bad.CodPersona,
				p.NomCompleto,
				p.Ndocumento,
				e.CodEmpleado,
				o.Organismo,
				d.Dependencia,
				pt.DescripCargo
			FROM
				rh_bonoalimentaciondet bad
				INNER JOIN mastpersonas p ON (p.CodPersona = bad.CodPersona)
				INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
				INNER JOIN mastorganismos o ON (o.CodOrganismo = e.CodOrganismo)
				INNER JOIN mastdependencias d ON (d.CodDependencia = e.CodDependencia)
				INNER JOIN rh_puestos pt ON (pt.CodCargo = e.CodCargo)
			WHERE
				bad.Anio = '".$Anio."' AND
				bad.CodOrganismo = '".$CodOrganismo."' AND
				bad.CodBonoAlim = '".$CodBonoAlim."'
			ORDER BY CodEmpleado";
	$query_empleados = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_empleados = mysql_fetch_array($query_empleados)) {	$nro_empleados++;
		?>
		<tr class="trListaBody" onclick="mClk(this, 'sel_empleados');" id="empleados_<?=$field_empleados['CodPersona']?>">
			<th>
				<?=$nro_empleados?>
			</th>
			<td align="center">
				<input type="hidden" name="CodPersona" value="<?=$field_empleados['CodPersona']?>" />
                <?=$field_empleados['CodEmpleado']?>
			</td>
			<td align="right">
                <?=$field_empleados['Ndocumento']?>
			</td>
			<td>
                <?=utf8_decode($field_empleados['NomCompleto'])?>
			</td>
			<td>
                <?=utf8_decode($field_empleados['DescripCargo'])?>
			</td>
			<td>
                <?=utf8_decode($field_empleados['Dependencia'])?>
			</td>
		</tr>
		<?
	}
    ?>
    </tbody>
</table>
</div>
<input type="hidden" id="nro_empleados" value="<?=$nro_empleados?>" />
<input type="hidden" id="can_empleados" value="<?=$nro_empleados?>" />
</form>
</center>
</div>

<center>
<input type="button" value="<?=$label_submit?>" style="width:75px; <?=$display_submit?>" onclick="bono_periodos(document.getElementById('frmentrada'), '<?=$accion?>');" />
<input type="button" value="Cancelar" style="width:75px;" onclick="<?=$clkCancelar?>" />
</center>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
