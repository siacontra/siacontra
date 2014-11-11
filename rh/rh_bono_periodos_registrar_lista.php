<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
if ($filtrar == "default") {
	$fdOrderBy = "CodEmpleado";
	$maxlimit = $_SESSION["MAXLIMIT"];
	$fdCodDependencia = $_SESSION["DEPENDENCIA_ACTUAL"];
}
if ($fdCodDependencia != "") { $cCodDependencia = "checked"; $filtro.=" AND (e.CodDependencia = '".$fdCodDependencia."')"; } else $dCodDependencia = "disabled";
if ($fdCodCentroCosto != "") { $cCodCentroCosto = "checked"; $filtro.=" AND (e.CodCentroCosto = '".$fdCodCentroCosto."')"; } else $dCodCentroCosto = "disabled";
if ($fdCodPersona != "") { $cCodPersona = "checked"; $filtro.=" AND (e.CodPersona = '".$fdCodPersona."')"; } else $dCodPersona = "visibility:hidden;";
if ($fdBuscar != "") {
	$cBuscar = "checked";
	$filtro .= " AND (p.NomCompleto LIKE '%".$fdBuscar."%' OR
					  p.Ndocumento LIKE '%".$fdBuscar."%' OR
					  e.CodEmpleado LIKE '%".$fdBuscar."%' OR
					  e.CodCentroCosto LIKE '%".$fdBuscar."%' OR
					  o.Organismo LIKE '%".$fdBuscar."%' OR
					  d.Dependencia LIKE '%".$fdBuscar."%')";
} else $dBuscar = "disabled";
//	------------------------------------
list($Anio, $CodOrganismo, $CodBonoAlim) = split("_", $registro);
//	consulto lista
$sql = "SELECT CodOrganismo
		FROM rh_bonoalimentacion
		WHERE
			Anio = '".$Anio."' AND
			CodOrganismo = '".$CodOrganismo."' AND
			CodBonoAlim = '".$CodBonoAlim."'";
$query = mysql_query($sql) or die ($sql.mysql_error());	$i=0;
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Registro de Eventos</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'gehen.php?anz=rh_bono_periodos_lista');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_bono_periodos_registrar_lista" method="post" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="registro" id="registro" value="<?=$registro?>" />

<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />
<input type="hidden" name="fCodOrganismo" id="fCodOrganismo" value="<?=$fCodOrganismo?>" />

<input type="hidden" name="dregistro" id="dregistro" />
<input type="hidden" name="fdOrderBy" id="fdOrderBy" value="<?=$fdOrderBy?>" />

<input type="hidden" name="Anio" id="Anio" value="<?=$Anio?>" />
<input type="hidden" name="CodOrganismo" id="CodOrganismo" value="<?=$CodOrganismo?>" />
<input type="hidden" name="CodBonoAlim" id="CodBonoAlim" value="<?=$CodBonoAlim?>" />
<div class="divBorder" style="width:950px;">
<table width="950" class="tblFiltro">
	<tr>
		<td align="right" width="100">Dependencia:</td>
		<td>
			<input type="checkbox" <?=$cCodDependencia?> onclick="chkFiltro(this.checked, 'fdCodDependencia');" />
			<select name="fdCodDependencia" id="fdCodDependencia" style="width:270px;" onChange="getOptionsSelect(this.value, 'centro_costo', 'fdCodCentroCosto', true)" <?=$dCodDependencia?>>
            	<option value=""></option>
				<?=getDependencias($fdCodDependencia, $field['CodOrganismo'], 3)?>
			</select>
		</td>
		<td class="tagForm">Empleado:</td>
		<td class="gallery clearfix">
			<input type="checkbox" <?=$cCodPersona?> onclick="chkFiltroLista_3(this.checked, 'fdCodPersona', 'fdCodEmpleado', 'fdNomPersona', 'btPersona');" />
            <input type="hidden" name="fdCodPersona" id="fdCodPersona" value="<?=$fdCodPersona?>" />
            <input type="hidden" name="fdCodEmpleado" id="fdCodEmpleado" value="<?=$fdCodEmpleado?>" />
			<input type="text" name="fdNomPersona" id="fdNomPersona" style="width:264px;" class="disabled" value="<?=$fdNomPersona?>" readonly />
            <a href="../lib/listas/listado_empleados.php?filtrar=default&cod=fdCodEmpleado&nom=fdNomPersona&campo3=fdCodPersona&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" id="btPersona" style=" <?=$dCodPersona?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
	</tr>
	<tr>
		<td align="right">C.Costo:</td>
		<td>
			<input type="checkbox" <?=$cCodCentroCosto?> onclick="chkFiltro(this.checked, 'fdCodCentroCosto');" />
			<select name="fdCodCentroCosto" id="fdCodCentroCosto" style="width:270px;" <?=$dCodCentroCosto?>>
            	<option value="">&nbsp;</option>
				<?=loadSelectDependiente("ac_mastcentrocosto", "CodCentroCosto", "Descripcion", "CodDependencia", $fdCodCentroCosto, $fdCodDependencia, 0)?>
			</select>
		</td>
		<td align="right">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fdBuscar');" />
			<input type="text" name="fdBuscar" id="fdBuscar" value="<?=$fdBuscar?>" style="width:264px;" <?=$dBuscar?> />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="950" class="tblBotones">
    <tr>
        <td><div id="rows"></div></td>
        <td align="right" class="gallery clearfix">
            <a id="a_detalle" href="pagina.php?iframe=true&width=100%&height=500" rel="prettyPhoto[iframe2]" style="display:none;"></a>
            <input type="button" style="width:100px;" value="Ver Detalle" onclick="bono_periodos_registrar_detalle();" />
            <input type="button" style="width:100px;" value="Registrar Eventos" onclick="cargarOpcion2(this.form, 'gehen.php?anz=rh_bono_periodos_registrar_eventos', 'SELF', '', $('#dregistro').val());" />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:950px; height:350px;">
<table width="1500" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15">&nbsp;</th>
        <th scope="col" width="30" onclick="order('CodEmpleado', '', 'fdOrderBy')">Empleado</th>
        <th scope="col" width="70" onclick="order('LENGTH(Ndocumento), Ndocumento', '', 'fdOrderBy')">Cedula</th>
        <th scope="col" align="left" onclick="order('NomCompleto', '', 'fdOrderBy')">Nombre Completo</th>
        <th scope="col" width="500" onclick="order('Dependencia', '', 'fdOrderBy')">Dependencia</th>
        <th scope="col" width="40" onclick="order('CodCentroCosto', '', 'fdOrderBy')">C.Costo</th>
        <th scope="col" width="60">Dias Total</th>
        <th scope="col" width="60">Dias H&aacute;biles</th>
        <th scope="col" width="60">Trabajados</th>
        <th scope="col" width="60">Personales</th>
        <th scope="col" width="60">Feriados</th>
        <th scope="col" width="60">Inactivos</th>
    </tr>
    </thead>
    
    <tbody>
    <?php
	list($Anio, $CodOrganismo, $CodBonoAlim, $CodPersona) = split("_", $registro);
    //	consulto lista
    $sql = "SELECT
				bad.Anio,
				bad.CodOrganismo,
				bad.CodBonoAlim,
				bad.CodPersona,
				bad.DiasPeriodo,
				bad.DiasPago,
				(bad.DiasPeriodo - bad.DiasDescuento) AS DiasTrabajados,
				bad.DiasDescuento,
				bad.DiasInactivos,
				bad.DiasFeriados,
				p.NomCompleto,
				p.Ndocumento,
				e.CodEmpleado,
				e.CodCentroCosto,
				o.Organismo,
				d.Dependencia
			FROM
				rh_bonoalimentaciondet bad
				INNER JOIN mastpersonas p ON (p.CodPersona = bad.CodPersona)
				INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
				INNER JOIN mastorganismos o ON (o.CodOrganismo = e.CodOrganismo)
				INNER JOIN mastdependencias d ON (d.CodDependencia = e.CodDependencia)
			WHERE
				bad.Anio = '".$Anio."' AND
				bad.CodOrganismo = '".$CodOrganismo."' AND
				bad.CodBonoAlim = '".$CodBonoAlim."' $filtro
			ORDER BY $fdOrderBy";
  	$query_empleados = mysql_query($sql) or die ($sql.mysql_error());	$i=0;
	$rows_total = mysql_num_rows($query_empleados);
    while ($field_empleados = mysql_fetch_array($query_empleados)) {
        $id = "$field_empleados[Anio]_$field_empleados[CodOrganismo]_$field_empleados[CodBonoAlim]_$field_empleados[CodPersona]";
        ?>
        <tr class="trListaBody" onclick="mClk(this, 'dregistro');" id="<?=$id?>">
			<th>
				<?=++$i?>
			</th>
			<td align="center">
                <?=$field_empleados['CodEmpleado']?>
			</td>
			<td align="right">
                <?=$field_empleados['Ndocumento']?>
			</td>
			<td>
                <?=htmlentities($field_empleados['NomCompleto'])?>
			</td>
			<td>
                <?=htmlentities($field_empleados['Dependencia'])?>
			</td>
			<td align="center">
                <?=$field_empleados['CodCentroCosto']?>
			</td>
			<td align="right">
                <?=number_format($field_empleados['DiasPeriodo'], 2, ',', '.')?>
			</td>
			<td align="right">
                <?=number_format($field_empleados['DiasPago'], 2, ',', '.')?>
			</td>
			<td align="right">
                <?=number_format($field_empleados['DiasTrabajados'], 2, ',', '.')?>
			</td>
			<td align="right">
                <?=number_format($field_empleados['DiasDescuento'], 2, ',', '.')?>
			</td>
			<td align="right">
<!--                <?=number_format($field_empleados['DiasFeriados'], 2, ',', '.')?> no van a existir feriados -->
				<?=number_format(0, 2, ',', '.')?>
			</td>
			<td align="right">
<!--                <?=number_format($field_empleados['DiasInactivos'], 2, ',', '.')?> no van a existir inactivos-->
				 <?=number_format(0, 2, ',', '.')?>
			</td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
</center>
</form>