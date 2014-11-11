<?php
session_start();

include("../../lib/fphp.php");
include("fphp.php");
include("calculoFidecomiso.php");




//	--------------------------
//--------------------------------------------//
$objeto =  new CalculoFidecomiso;

$fecha_ingreso= substr($Fingreso, 6, 9)."-". substr($Fingreso, 4, 5)."-". substr($Fingreso, 0, 1); 
$objeto->inicializar( $Fingreso, $Dias, $Meses, $Anios, $CodPersona);
$objeto->calcularPeriodos();

//	--------------------------
if ($accion == "fideicomiso_calculo_listado") {
	//	consulto los datos del calculo de antiguedad
	?>
    <table width="1500" class="tblLista" style= 'width:1700px;'>
    <thead>
    <tr class="trListaHead">
        <th width="15" scope="col">Pr.</th>
		<th width="60" scope="col">PERIODO</th>
		<th width="80" scope="col">SUELDO MENSUAL</th>
        <?php
		$sql = "SELECT CodConcepto, Descripcion, Abreviatura
				FROM pr_concepto
				WHERE FlagBonoRemuneracion = 'S'
				ORDER BY CodConcepto";
				
		$query_conceptos = mysql_query($sql) or die($sql.mysql_error());	
		$filtro_remuneraciones = "";
		
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
    
    <tbody id="listaCalculoCuerpo">
	
	<?php


$sql = "SELECT
				periodo
				
			FROM
				
			   pr_fideicomisocalculo 
				
			WHERE 
				CodPersona = '".$CodPersona."'
			
			ORDER BY Periodo";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	if ($rows==0) {
		
$objeto->setSueldo(2600.22);

$tabla= $objeto->getTabla();

		foreach($tabla as &$k)

		echo "	<tr class='trListaBody' >
			
		<input 	<input type='hidden'	   style= 'width:80;'name='Periodo' value=".$k['inicio']." >    	
		<td align='center'>".printFlag2('S')."</td>
		<td align='center'  style= 'width:150px;align:center'>".$k['inicio']." al ".$k['fin']."</td>
		<td align='center'  ><input type='text'  class='cell' style= 'width:100;' name='SueldoMensual' value=".number_format($k['SueldoNormal'], 2, ',', '.')." ></td>
		<td align='center'></td>
		<td align='center'><input type='text'   class='cell' style= 'width:50;'name='Bonificaciones' value=".$Bonificaciones." ></td>
		<td align='center'><input type='text'   class='cell' style= 'width:80;' name='AliVac' value=".$field['AliVac']." ></td>		
		<td align='center'><input type='text'   class='cell' style= 'width:80;' name='AliFin' value=".$field['AliFin']." ></td>		
		<td align='center'><input type='text'   class='cell' style= 'width:80;'name='SueldoDiario' value=".$SueldoDiario." ></td>		
		<td align='center'><input type='text'   class='cell' style= 'width:80;'name='SueldoDiarioAli' value=".$SueldoDiarioAli." ></td>
		<td align='center'><input type='text'   class='cell' style= 'width:60;'name='Dias' value=".$field['Dias']." ></td>		
		<td align='center'><input type='text'   class='cell' style= 'width:80;'name='PrestAntiguedad' value=".$field['Transaccion']." ></td>	
		<td align='center'><input               class='cell' style= 'width:80;'name='PrestAcumulada' value=".$PrestAcumulada." ></td>
		<td align='center'>	<input type='text'  class='cell' style= 'width:80;' name='DiasComplemento' value=".$field['DiasAdicional']." ></td>	
		
		<td align='center'><input       		class='cell' style= 'width:50;'name='Tasa' value=".$Tasa." ></td>	   
		<td align='center'><input       		class='cell' style= 'width:30;'name='DiasMes' value=".$DiasMes." ></td>	   
		<td align='center'><input               class='cell' style= 'width:80;'name='InteresMensual' value=".$field['TransaccionFide']." ></td>	   
		<td align='center'><input 		   style= 'width:80;'name='InteresAcumulado' value=".$InteresAcumulado." ></td>	
		<td align='center'><input 		   style= 'width:80;'name='Anticipo' value=".$field['Anticipo']." > </td>	   
		<td align='center'>	<input type='hiddens' style= 'width:80;' name='PrestComplemento' value=".$field['Complemento']." ></td>	 
		
	
		
		
		
		</tr>";
		
	
            	
            

	};
		
	
	//<td align='center'>".$k['dias']."</td>
	//	<td align='center'>".$k['diasTrimestres']."</td>	
//------------------------------------------//	
	
	
	
	
	$sql = "SELECT
				afd.*,
				fc.Periodo AS PeriodoProcesado,
				af.AcumuladoInicialProv
				
			FROM
				pr_acumuladofideicomisodetalle afd
				INNER JOIN pr_acumuladofideicomiso af ON (afd.CodPersona = af.CodPersona)
				LEFT JOIN pr_fideicomisocalculo fc ON (fc.CodPersona = afd.CodPersona AND
													   fc.Periodo = afd.Periodo)
			WHERE
				SUBSTRING(afd.Periodo, 1, 4) =  '".$Periodo."' AND
				afd.CodPersona = '".$CodPersona."'
			ORDER BY Periodo";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		//	------------------------
		if ($field['Periodo'] < '2012-05') {
			//	obtengo el sueldo normal
			$sql = "SELECT SueldoNormal
					FROM rh_sueldos
					WHERE
						CodPersona = '".$CodPersona."' AND
						Periodo = '".$field['Periodo']."'
					ORDER BY Periodo DESC
					LIMIT 0, 1";
			$query_sueldo = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_sueldo) != 0) $field_sueldo = mysql_fetch_array($query_sueldo);
			//	------------------------
			//	obtengo las alicuota vacacional
			$sql = "SELECT Monto
					FROM pr_tiponominaempleadoconcepto
					WHERE
						CodPersona = '".$CodPersona."' AND
						CodConcepto = '".$_PARAMETRO["ALIVAC"]."' AND
						Periodo = '".$field['Periodo']."'
					ORDER BY Periodo DESC
					LIMIT 0, 1";
			$query_alivac = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_alivac) != 0) $field_alivac = mysql_fetch_array($query_alivac);
			//	------------------------
			//	obtengo las alicuota fin de año
			$sql = "SELECT Monto
					FROM pr_tiponominaempleadoconcepto
					WHERE
						CodPersona = '".$CodPersona."' AND
						CodConcepto = '".$_PARAMETRO["ALIFIN"]."' AND
						Periodo = '".$field['Periodo']."'
					ORDER BY Periodo DESC
					LIMIT 0, 1";
			$query_alifin = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_alifin) != 0) $field_alifin = mysql_fetch_array($query_alifin);
		} else {
			//	obtengo el sueldo normal
			$sql = "SELECT SueldoNormal
					FROM rh_sueldos
					WHERE
						CodPersona = '".$CodPersona."' AND
						Periodo < '".$field['Periodo']."'
					ORDER BY Periodo DESC
					LIMIT 0, 1";
			$query_sueldo = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_sueldo) != 0) $field_sueldo = mysql_fetch_array($query_sueldo);
			//	------------------------
			//	obtengo las alicuota vacacional
			$sql = "SELECT Monto
					FROM pr_tiponominaempleadoconcepto
					WHERE
						CodPersona = '".$CodPersona."' AND
						CodConcepto = '".$_PARAMETRO["ALIVAC"]."' AND
						Periodo < '".$field['Periodo']."'
					ORDER BY Periodo DESC
					LIMIT 0, 1";
			$query_alivac = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_alivac) != 0) $field_alivac = mysql_fetch_array($query_alivac);
			//	------------------------
			//	obtengo las alicuota fin de año
			$sql = "SELECT Monto
					FROM pr_tiponominaempleadoconcepto
					WHERE
						CodPersona = '".$CodPersona."' AND
						CodConcepto = '".$_PARAMETRO["ALIFIN"]."' AND
						Periodo < '".$field['Periodo']."'
					ORDER BY Periodo DESC
					LIMIT 0, 1";
			$query_alifin = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_alifin) != 0) $field_alifin = mysql_fetch_array($query_alifin);
		}
		//	------------------------
		$field['SueldoNormal'] = $field_sueldo['SueldoNormal'];
		$field['AliVac'] = $field_alivac['Monto'];
		$field['AliFin'] = $field_alifin['Monto'];
		//	------------------------
		if ($field['PeriodoProcesado'] != "") $Procesado = "S"; else $Procesado = "N";
		?>
		<tr class="trListaBody">
			<td align="center"><?=printFlag2($Procesado)?></td>
			<td align="center"><strong><?=$field['Periodo']?></strong></td>
			<td align="right"><?=number_format($field['SueldoNormal'], 2, ',', '.')?></td>
			<?
			$Bonificaciones = 0;
            $sql = "SELECT CodConcepto FROM pr_concepto WHERE FlagBonoRemuneracion = 'S' ORDER BY CodConcepto";
            $query_conceptos = mysql_query($sql) or die($sql.mysql_error());
            while($field_conceptos = mysql_fetch_array($query_conceptos)) {
				$id = "_".$field_conceptos['CodConcepto'];
				$Bonificaciones += $field[$id];
				?><td align="right"><?=number_format($field[$id], 2, ',', '.')?></td><?
			}
			$SueldoDiario = REDONDEO((($field['SueldoNormal'] + $Bonificaciones) / 30), 2);
			$SueldoDiarioAli = $SueldoDiario + $field['AliVac'] + $field['AliFin'];
			$Complemento += $field['Complemento'];
			$PrestAcumulada = $field['AnteriorProv'] + $field['Transaccion'] + $Complemento;
			$Tasa = tasaInteres($field['Periodo']);
			$DiasMes = getDiasMes($field['Periodo']);
			$InteresAcumulado = $field['AnteriorFide'] + $field['TransaccionFide'];
            ?>
			<td align="right"><?=number_format($field['AliVac'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field['AliFin'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($SueldoDiario, 2, ',', '.')?></td>
			<td align="right"><?=number_format($SueldoDiarioAli, 2, ',', '.')?></td>
			<td align="center"><?=$field['Dias']?></td>
			<td align="right"><?=number_format($field['Transaccion'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field['Complemento'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($PrestAcumulada, 2, ',', '.')?></td>
			<td align="right"><?=number_format($Tasa, 2, ',', '.')?></td>
			<td align="center"><?=$DiasMes?></td>
			<td align="right"><?=number_format($field['TransaccionFide'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($InteresAcumulado, 2, ',', '.')?></td>
        	<td>
            	<input type="hidden" name="Periodo" value="<?=$field['Periodo']?>" />
            	<input type="hidden" name="SueldoMensual" value="<?=$field['SueldoNormal']?>" />
            	<input type="hidden" name="Bonificaciones" value="<?=$Bonificaciones?>" />
            	<input type="hidden" name="AliVac" value="<?=$field['AliVac']?>" />
            	<input type="hidden" name="AliFin" value="<?=$field['AliFin']?>" />
            	<input type="hidden" name="SueldoDiario" value="<?=$SueldoDiario?>" />
            	<input type="hidden" name="SueldoDiarioAli" value="<?=$SueldoDiarioAli?>" />
            	<input type="hidden" name="Dias" value="<?=$field['Dias']?>" />
            	<input type="hidden" name="PrestAntiguedad" value="<?=$field['Transaccion']?>" />
            	<input type="hidden" name="DiasComplemento" value="<?=$field['DiasAdicional']?>" />
            	<input type="hidden" name="PrestComplemento" value="<?=$field['Complemento']?>" />
            	<input type="hidden" name="PrestAcumulada" value="<?=$PrestAcumulada?>" />
            	<input type="hidden" name="Tasa" value="<?=$Tasa?>" />
            	<input type="hidden" name="DiasMes" value="<?=$DiasMes?>" />
            	<input type="hidden" name="InteresMensual" value="<?=$field['TransaccionFide']?>" />
            	<input type="hidden" name="InteresAcumulado" value="<?=$InteresAcumulado?>" />
            	<input type="hidden" name="Anticipo" value="<?=$field['Anticipo']?>" />
            </td>
		</tr>
		
	
		
		
		<?
	}
    ?>
    </tbody>
    </table>
	<?
}
?>
