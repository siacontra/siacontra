<?php
include("fphp.php");
include("pr_fphp.php");
extract($_POST);
extract($_GET);
//	----------------
if ($accion == "fideicomiso_calculo") {
	//	consulto los datos del trabajador
	$sql = "SELECT
				mp.CodPersona,
				mp.NomCompleto,
				mp.Ndocumento,
				me.CodEmpleado,
				me.Fingreso
			FROM
				mastpersonas mp
				INNER JOIN mastempleado me ON (mp.CodPersona = me.CodPersona)
			WHERE mp.CodPersona = '".$codpersona."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		list($anios, $meses, $dias) = getTiempo(formatFechaDMA($field['Fingreso']), date("d-m-Y"));
		echo "$field[CodPersona]|$field[CodEmpleado]|$field[NomCompleto]|$field[Ndocumento]|$anios|$meses|$dias|$field[Fingreso]|";
	} else echo "||||||||";
	
	//	consulto los datos del calculo de antiguedad
	?>
    <table width="1500" class="tblLista">
    <thead>
    <tr class="trListaHead">
		<th width="60" scope="col">PERIODO</th>
		<th width="80" scope="col">SUELDO MENSUAL</th>
        <?
		$sql = "SELECT CodConcepto, Descripcion, Abreviatura
				FROM pr_concepto
				WHERE FlagBonoRemuneracion = 'S'
				ORDER BY CodConcepto";
		$query_conceptos = mysql_query($sql) or die($sql.mysql_error());	$filtro_remuneraciones = "";
		while($field_conceptos = mysql_fetch_array($query_conceptos)) {
			$filtro_remuneraciones .= ", (SELECT tnec1.Monto
										  FROM
										  		pr_tiponominaempleadoconcepto tnec1
										  WHERE
										  		tnec1.Periodo = afd.Periodo AND
												tnec1.CodPersona = afd.CodPersona AND
												tnec1.CodConcepto = '".$field_conceptos['CodConcepto']."') AS _".$field_conceptos['CodConcepto'];
			?><th width="80" scope="col" title="<?=($field_conceptos['Descripcion'])?>"><?=$field_conceptos['Abreviatura']?></th><?
		}
		?>
		<th width="60" scope="col">ALI. B. VAC.</th>
		<th width="60" scope="col">ALI. B. FIN AÑO</th>
		<th width="80" scope="col">REMUN. DIARIA</th>
		<th width="80" scope="col">SUELDO + ALICUOTAS</th>
		<th width="35" scope="col">DIAS</th>
		<th width="80" scope="col">PREST. ANTIG. MENSUAL</th>
		<th width="80" scope="col">PREST. COMPL. (2 DIAS)</th>
		<th width="80" scope="col">PREST. ACUMULADA</th>
		<th width="50" scope="col">TASA DE INTERES (%)</th>
		<th width="50" scope="col">DIAS DEL MES</th>
		<th width="80" scope="col">INTERES MENSUAL</th>
		<th width="80" scope="col">INTERES ACUMULADO</th>
		<th width="80" scope="col">ANTICIPO PRESTACION</th>
        <th>&nbsp;</th>
	</tr>
    </thead>
    
    <tbody>
    <?
	$sql = "SELECT
				afd.*,
				s.SueldoNormal,
				af.AcumuladoInicialProv,
				(SELECT Porcentaje FROM masttasainteres WHERE Periodo = afd.Periodo) AS TasaInteres,
				(SELECT tnec1.Monto
				 FROM pr_tiponominaempleadoconcepto tnec1
				 WHERE
					tnec1.Periodo = afd.Periodo AND
					tnec1.CodPersona = afd.CodPersona AND
					tnec1.CodConcepto = '".$_PARAMETRO["ALIVAC"]."') AS AliVac,
				(SELECT tnec1.Monto
				 FROM pr_tiponominaempleadoconcepto tnec1
				 WHERE
					tnec1.Periodo = afd.Periodo AND
					tnec1.CodPersona = afd.CodPersona AND
					tnec1.CodConcepto = '".$_PARAMETRO["ALIFIN"]."') AS AliFin
				$filtro_remuneraciones
			FROM
				pr_acumuladofideicomisodetalle afd
				INNER JOIN rh_sueldos s ON (afd.Periodo = s.Periodo AND afd.CodPersona = s.CodPersona)
				INNER JOIN pr_acumuladofideicomiso af ON (afd.CodPersona = af.CodPersona)
			WHERE
				SUBSTRING(afd.Periodo, 1, 4) =  '".$periodo."' AND
				afd.CodPersona = '".$codpersona."'
			ORDER BY Periodo";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {		
		?>
		<tr class="trListaBody">
			<td align="center"><?=$field['Periodo']?></td>
			<td align="right"><strong><?=number_format($field['SueldoNormal'], 2, ',', '.')?></strong></td>
			<?
			$bonos = 0;
            $sql = "SELECT CodConcepto FROM pr_concepto WHERE FlagBonoRemuneracion = 'S' ORDER BY CodConcepto";
            $query_conceptos = mysql_query($sql) or die($sql.mysql_error());
            while($field_conceptos = mysql_fetch_array($query_conceptos)) {
				$id = "_".$field_conceptos['CodConcepto'];
				$bonos += $field[$id];
				?><td align="right"><?=number_format($field[$id], 2, ',', '.')?></td><?
			}
			$remuneracion_diaria = REDONDEO((($field['SueldoNormal'] + $bonos) / 30), 2);
			$sueldo_alicuotas = $remuneracion_diaria + $field['AliVac'] + $field['AliFin'];
			$Complemento += $field['Complemento'];
			$prestacion_acumulada = $field['AnteriorProv'] + $field['Transaccion'] + $Complemento;
			$tasa = tasaInteres($field['Periodo']);
			$dias_mes = getDiasMes($field['Periodo']);
			$interes_acumulado = $field['AnteriorFide'] + $field['TransaccionFide'];
            ?>
			<td align="right"><?=number_format($field['AliVac'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field['AliFin'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($remuneracion_diaria, 2, ',', '.')?></td>
			<td align="right"><?=number_format($sueldo_alicuotas, 2, ',', '.')?></td>
			<td align="center"><?=$field['Dias']?></td>
			<td align="right"><?=number_format($field['Transaccion'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field['Complemento'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($prestacion_acumulada, 2, ',', '.')?></td>
			<td align="right"><?=number_format($tasa, 2, ',', '.')?></td>
			<td align="center"><?=$dias_mes?></td>
			<td align="right"><?=number_format($field['TransaccionFide'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($interes_acumulado, 2, ',', '.')?></td>
        	<td>&nbsp;</td>
		</tr>
		<?
	}
    ?>
    </tbody>
    </table>
	<?
}
?>