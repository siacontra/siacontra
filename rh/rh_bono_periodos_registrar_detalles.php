<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
list($Anio, $CodOrganismo, $CodBonoAlim, $CodPersona) = split("[_]", $registro);
//	empleado
$sql = "SELECT
			p.CodPersona,
			p.NomCompleto,
			e.CodEmpleado,
			e.CodHorario
		FROM
			mastpersonas p
			INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
		WHERE p.CodPersona = '".$CodPersona."'";
$query_empleado = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_empleado) != 0) $field_empleado = mysql_fetch_array($query_empleado);
//	------------------------------------
$sql = "SELECT *
		FROM rh_bonoalimentacion
		WHERE
			Anio = '".$Anio."' AND
			CodOrganismo = '".$CodOrganismo."' AND
			CodBonoAlim = '".$CodBonoAlim."'";
$query = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
?>
<div class="divBorder" style="width:800px;">
<table width="800" class="tblFiltro">
	<tr>
    	<td colspan="2" class="divFormCaption">Datos del Empleado</td>
    </tr>
	<tr>
		<td align="right" width="125">Empleado:</td>
		<td>
        	<input type="text" id="CodEmpleado" style="width:40px;" class="codigo" value="<?=$field_empleado['CodEmpleado']?>" disabled />
        	<input type="text" id="NomCompleto" style="width:300px;" class="codigo" value="<?=htmlentities($field_empleado['NomCompleto'])?>" disabled />
		</td>
	</tr>
</table>
</div>
<br />
<center>
<div style="overflow:scroll; width:800px; height:100px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
    	<?php
		//	consulto los dias
		$sql = "SELECT *
				FROM rh_bonoalimentaciondet
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					CodBonoAlim = '".$CodBonoAlim."' AND
					CodPersona = '".$CodPersona."'";
   		$query_dias = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_dias) != 0) $field_dias = mysql_fetch_array($query_dias);
		
		//	imprimo 
		$f = formatFechaDMA($field['FechaInicio']);
		for($i=1;$i<=$field_dias['DiasPeriodo'];$i++) {
			list($d, $m, $a) = split("[-./]", $f);
			?><th scope="col" width="8" style="font-size:9px; padding-left:0px; padding-right:0px;"><?=$d?></th><?
			$f = obtenerFechaFin($f, 2);
		}
		?>
    </tr>
    </thead>
    
    <tbody>
    <?php
    for($i=1;$i<=$field_dias['DiasPeriodo'];$i++) {
		$id = "Dia".$i;
		if ($field_dias[$id] == "X") $color = "color:#090;";
		elseif ($field_dias[$id] == "D") $color = "color:#900;";
		else $color = "";
		?><td align="center" style="font-size:12px; padding:2px; font-weight:bold; <?=$color?>"><?=$field_dias[$id]?></th><?
	}
    ?>
    </tbody>
</table>
</div>
</center>
<br />
<table align="center" width="800">
	<tr>
    	<td width="40%" valign="top">
        	<div class="divBorder" style="width:100%;">
            <table width="100%" class="tblFiltro">
                <tr>
                    <td colspan="4" class="divFormCaption">Leyenda</td>
                </tr>
                <tr>
                    <td width="20" height="20" align="right" style="color:#090;">
                        X :
                    </td>
                    <td style="color:#090;">
                        ASISTENCIA
                    </td>
                    <td width="20" align="right">
                        F :
                    </td>
                    <td>
                        FERIADO
                    </td>
                </tr>
                <tr>
                    <td align="right" height="20" style="color:#900;">
                        D :
                    </td>
                    <td style="color:#900;">
                        DESCUENTO
                    </td>
                    <td align="right">
                        I :
                    </td>
                    <td>
                        INACTIVO
                    </td>
                </tr>
            </table>
            </div>
        </td>
        <td width="60%" valign="top">
        	<?php
			$Trabajados = $field_dias['DiasPago'] - $field_dias['DiasDescuento'];
			?>
            <div class="divBorder" style="width:100%;">
            <table width="100%" class="tblFiltro">
                <tr>
                    <td colspan="6" class="divFormCaption">Resumen</td>
                </tr>
                <tr>
                    <td align="right" width="17%" height="20">Total:</td>
                    <td align="right" width="17%">H&aacute;biles:</td>
                    <td align="right" width="17%">Trabajados:</td>
                    <td align="right" width="17%">Descuento:</td>
                    <td align="right" width="17%">Feriados:</td>
                    <td align="right">Inactivos:</td>
                </tr>
                <tr>
                    <td align="right" height="20">
                        <input type="text" style="width:50px; text-align:right;" class="codigo" value="<?=$field_dias['DiasPeriodo']?>" disabled />
                    </td>
                    <td align="right">
                        <input type="text" style="width:50px; text-align:right;" class="codigo" value="<? echo $field_dias['DiasPago'] -($field_dias['DiasInactivos']+$field_dias['DiasFeriados']);?>" disabled />
                    </td>
                    <td align="right">
                        <input type="text" style="width:50px; text-align:right;" class="codigo" value="<? echo $field_dias['DiasPago'] -($field_dias['DiasInactivos']+$field_dias['DiasFeriados']+$field_dias['DiasDescuento']);?>" disabled />
                    </td>
                    <td align="right">
                        <input type="text" style="width:50px; text-align:right;" class="codigo" value="<?=$field_dias['DiasDescuento']?>" disabled />
                    </td>
                    <td align="right">
                        <input type="text" style="width:50px; text-align:right;" class="codigo" value="<?=$field_dias['DiasFeriados']?>" disabled />
                    </td>
                    <td align="right">
                        <input type="text" style="width:50px; text-align:right;" class="codigo" value="<?=$field_dias['DiasInactivos']?>" disabled />
                    </td>
                </tr>
            </table>
            </div>
        </td>
    </tr>
</table>
</form>