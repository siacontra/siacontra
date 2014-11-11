<?php
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
$FechaActual = "$DiaActual-$MesActual-$AnioActual";
//	------------------------------------
if ($filtrar == "default") {
	$fdOrderBy = "FechaDesde";
	$CodPersona = $registro;
}
//	------------------------------------
//	datos del empleado
$sql = "SELECT
			p.CodPersona,
			p.NomCompleto,
			e.CodEmpleado
		FROM
			mastpersonas p
			INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
		WHERE p.CodPersona = '".$CodPersona."'";
$query_empleado = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_empleado)) $field_empleado = mysql_fetch_array($query_empleado);
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Experiencia Laboral del Empleado</td>
		<td align="right"><a class="cerrar" href="#" onclick="$('#frmentrada').attr('action', 'gehen.php?anz=empleados_lista'); document.getElementById('frmentrada').submit()">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=empleados_experiencia_laboral_lista" method="post" autocomplete="off">
<?=filtroEmpleados()?>
<input type="hidden" name="fdOrderBy" id="fdOrderBy" value="<?=$fdOrderBy?>" />
<input type="hidden" name="CodPersona" id="CodPersona" value="<?=$field_empleado['CodPersona']?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:900px;">
<table width="900" class="tblFiltro">
	<tr>
    	<td colspan="2" class="divFormCaption">Datos del Empleado</td>
    </tr>
	<tr>
		<td align="right" width="125">Empleado:</td>
		<td>
        	<input type="text" id="CodEmpleado" style="width:60px;" class="codigo" value="<?=$field_empleado['CodEmpleado']?>" disabled />
		</td>
	</tr>
	<tr>
		<td align="right">Nombre Completo:</td>
		<td>
        	<input type="text" id="NomCompleto" style="width:500px;" class="codigo" value="<?=$field_empleado['NomCompleto']?>" disabled />
		</td>
	</tr>
</table>
</div><br />

<center>
<table width="900" class="tblBotones">
    <tr>
        <td><div id="rows"></div></td>
        <td align="right">
            <input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=empleados_experiencia_laboral_form&opcion=nuevo');" />
            
            <input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=empleados_experiencia_laboral_form&opcion=modificar', 'SELF', '', $('#registro').val());" />
            
            <input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=empleados_experiencia_laboral_form&opcion=ver', 'SELF', '', $('#registro').val());" />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:900px; height:300px;">
<table width="100%" class="tblLista">
    <tbody>
    <?php
    //	consulto lista
    $sql = "SELECT
				ee.*,
				md1.Descripcion AS NomAreaExperiencia,
				md2.Descripcion AS NomMotivoCese,
				md3.Descripcion AS NomTipoEnte
            FROM
				rh_empleado_experiencia ee
				LEFT JOIN mastmiscelaneosdet md1 ON (md1.CodDetalle = ee.AreaExperiencia AND
													 md1.CodMaestro = 'AREAEXP' AND
													 md1.CodAplicacion = 'RH')
				LEFT JOIN mastmiscelaneosdet md2 ON (md2.CodDetalle = ee.MotivoCese AND
													 md2.CodMaestro = 'MOTCESE' AND
													 md2.CodAplicacion = 'RH')
				LEFT JOIN mastmiscelaneosdet md3 ON (md3.CodDetalle = ee.TipoEnte AND
													 md3.CodMaestro = 'TIPOENTE' AND
													 md3.CodAplicacion = 'RH')
            WHERE ee.CodPersona = '".$CodPersona."'
            ORDER BY $fdOrderBy";
    $query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);	$i=0;
    while ($field = mysql_fetch_array($query)) {
        $id = "$field[CodPersona]_$field[Secuencia]";
		list($TiempoAnios, $TiempoMeses, $TiempoDias) = getEdad(formatFechaDMA($field['FechaDesde']), formatFechaDMA($field['FechaHasta']));
        ?>
        <tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
            <td>
            	<table border="1" width="100%">
                	<tr>
                    	<td class="th">
                        	Empresa:
                        </td>
                        <td colspan="5">
							<strong style="font-size:12px;"><?=htmlentities($field['Empresa'])?></strong>
                        </td>
                    	<td class="th">
                        	Entidad:
                        </td>
                        <td colspan="5">
							<?=htmlentities($field['NomTipoEnte'])?>
                        </td>
					</tr>
                	<tr>
                    	<td class="th">
                        	Cargo Ocupado:
                        </td>
                        <td colspan="5">
							<?=htmlentities($field['CargoOcupado'])?>
                        </td>
                    	<td class="th">
                        	Motivo:
                        </td>
                        <td colspan="5">
							<?=htmlentities($field['NomMotivoCese'])?>
                        </td>
                    </tr>
                	<tr>
                    	<td class="th" width="100">
                        	Area:
                        </td>
                        <td>
							<?=htmlentities($field['NomAreaExperiencia'])?>
                        </td>
                    	<td class="th" width="60">
                        	Desde:
                        </td>
                        <td align="center" width="75">
							<?=formatFechaDMA($field['FechaDesde'])?>
                        </td>
                    	<td class="th" width="60">
                        	Hasta:
                        </td>
                        <td align="center" width="75">
							<?=formatFechaDMA($field['FechaHasta'])?>
                        </td>
                    	<td class="th" width="60">
                        	Tiempo:
                        </td>
                        <td align="center" width="35" style="background-color:#ABABAB; margin:5px;">
							<strong><?=$TiempoAnios?></strong>
                        </td>
                        <td width="5"></td>
                        <td align="center" width="35" style="background-color:#ABABAB; margin:5px;">
							<strong><?=$TiempoMeses?></strong>
                        </td>
                        <td width="5"></td>
                        <td align="center" width="35" style="background-color:#ABABAB; margin:5px;">
							<strong><?=$TiempoDias?></strong>
                        </td>
                    </tr>
                </table>
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
