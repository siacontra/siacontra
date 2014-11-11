<?php
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=$archivo.xls");
header("Pragma: no-cache");
header("Expires: 0");
//	------------------------------------
include("fphp_nomina.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('02', $concepto);
?>

<?
if (($chkasignacion == "I" && $chkdeduccion == "" && $chkaporte == "") || ($chkasignacion == "" && $chkdeduccion == "D" && $chkaporte == "") || ($chkasignacion == "" && $chkdeduccion == "" && $chkaporte == "A")) {
	if ($chkasignacion == "I") $ftipo = "I";
	elseif ($chkdeduccion == "D") $ftipo = "D";
        elseif ($chkaporte == "A") $ftipo = "A";
	?>
	<table border="1">
		<tr>
			<th>CEDULA</th>
			<th>NOMBRE Y APELLIDO</th>
			<th>SUELDO BASICO MENSUAL</th>
			<?
			$sql = "SELECT
						  tnec.CodConcepto,
						  c.Abreviatura AS NomConcepto
					FROM
						  pr_tiponominaempleadoconcepto tnec
						  INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto AND c.Tipo = '".$ftipo."')
					WHERE
						  tnec.CodTipoNom = '".$ftiponom."' AND
						  tnec.Periodo = '".$fperiodo."' AND
						  tnec.CodOrganismo = '".$forganismo."' AND
						  tnec.CodTipoProceso = '".$ftproceso."'
					GROUP BY CodConcepto
					ORDER BY CodConcepto";
			$query_conceptos = mysql_query($sql) or die ($sql.mysql_error());
			$rows_conceptos = mysql_num_rows($query_conceptos);
			while ($field_conceptos = mysql_fetch_array($query_conceptos)) {
				$sql="SELECT 
								Monto 
							FROM 
								pr_tiponominaempleadoconcepto 
							WHERE
								CodConcepto = '".$field_conceptos['CodConcepto']."' AND
								CodTipoNom = '".$ftiponom."' AND
								Periodo = '".$fperiodo."' AND
								CodOrganismo = '".$forganismo."' AND
								CodTipoProceso = '".$ftproceso."'";
				$query = mysql_query($sql) or die ($sql.mysql_error());
				$rows = mysql_num_rows($query);
				if($rows!=0){
				$q .= ", (SELECT 
								Monto 
							FROM 
								pr_tiponominaempleadoconcepto 
							WHERE
								CodConcepto = '".$field_conceptos['CodConcepto']."' AND
								CodTipoNom = '".$ftiponom."' AND
								Periodo = '".$fperiodo."' AND
								CodOrganismo = '".$forganismo."' AND
								CodTipoProceso = '".$ftproceso."' AND
								CodPersona = p.CodPersona
							) AS '".$field_conceptos['CodConcepto']."'";
				?><th><?=$field_conceptos['NomConcepto']?></th><?}
			}
			?>
			<th>TOTAL CONCEPTOS</th>
		</tr>
		
		<?
		$sql = "SELECT
					  ptne.CodPersona,
					  p.Ndocumento,
					  p.NomCompleto,
					  pu.NivelSalarial as SueldoActual
					  $q
				FROM
					  pr_tiponominaempleado ptne
					  INNER JOIN mastpersonas p ON (ptne.CodPersona = p.CodPersona)
					  INNER JOIN mastempleado pe ON (ptne.CodPersona = pe.CodPersona)
					  INNER JOIN rh_puestos pu ON (pe.CodCargo = pu.CodCargo)
				WHERE
					  ptne.CodTipoNom = '".$ftiponom."' AND
					  ptne.Periodo = '".$fperiodo."' AND
					  ptne.CodOrganismo = '".$forganismo."' AND
					  ptne.CodTipoProceso = '".$ftproceso."' 
				ORDER BY length(p.Ndocumento), Ndocumento";
		$query_empleados = mysql_query($sql) or die ($sql.mysql_error());
		$rows_empleados = mysql_num_rows($query_empleados);
		while ($field_empleados = mysql_fetch_array($query_empleados)) {
			?>
			<tr>
				<td><?=$field_empleados['Ndocumento']?></td>
				<td><?=utf8_decode($field_empleados['NomCompleto'])?></td>
				<? $montoSA = number_format($field_empleados['SueldoActual'], 2, ',', '');?>
				<td align="right">=DECIMAL(<?=$montoSA?>; 2)</td>
				<?  //<td><?=$field_empleados['SueldoActual']
				$total = 0;
				for ($i=4; $i<=$rows_conceptos+3; $i++) {
					$total += $field_empleados[$i];
					$sum_total[$i] += $field_empleados[$i];
					$monto = number_format($field_empleados[$i], 2, ',', '');
					?><td align="right">=DECIMAL(<?=$monto?>; 2)</td><?
				}
				$total = number_format($total, 2, ',', '');
				?><th align="right">=DECIMAL(<?=$total?>; 2)</th>
			</tr>
			<?
		}
		?>
		
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<?
			for ($i=4; $i<=$rows_conceptos+3; $i++) {
				$sum_total_conceptos += $sum_total[$i];
				$total = number_format($sum_total[$i], 2, ',', '');
				?><th align="right">=DECIMAL(<?=$total?>; 2)</th><?
			}
			$sum_total_conceptos = number_format($sum_total_conceptos, 2, ',', '');
			?><th align="right">=DECIMAL(<?=$sum_total_conceptos?>; 2)</th>
		</tr>
	</table>
	<?
}

elseif (($chkasignacion == "I" && $chkdeduccion == "D"  && $chkaporte == "A") or ($chkasignacion != "I" && $chkdeduccion == "D"  && $chkaporte == "A") or ($chkasignacion == "I" && $chkdeduccion != "D"  && $chkaporte == "A") or ($chkasignacion == "I" && $chkdeduccion == "D"  && $chkaporte != "A")) {
	?>
	<table border="1">
		<tr>
			<th>CEDULA</th>
			<th>NOMBRE Y APELLIDO</th>
			<th>SUELDO BASICO MENSUAL</th>
			<?
			if($chkasignacion == "I" ){
			$sql = "SELECT
						  tnec.CodConcepto,
						  c.Abreviatura AS NomConcepto,
						  c.Tipo
					FROM
						  pr_tiponominaempleadoconcepto tnec
						  INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto AND c.Tipo = 'I')
					WHERE
						  tnec.CodTipoNom = '".$ftiponom."' AND
						  tnec.Periodo = '".$fperiodo."' AND
						  tnec.CodOrganismo = '".$forganismo."' AND
						  tnec.CodTipoProceso = '".$ftproceso."'
					GROUP BY CodConcepto
					ORDER BY Tipo, CodConcepto";
			$query_asignaciones = mysql_query($sql) or die ($sql.mysql_error());
			$rows_asignaciones = mysql_num_rows($query_asignaciones);
			while ($field_asignaciones = mysql_fetch_array($query_asignaciones)) {
				$sql="SELECT 
								Monto 
							FROM 
								pr_tiponominaempleadoconcepto 
							WHERE
								CodConcepto = '".$field_asignaciones['CodConcepto']."' AND
								CodTipoNom = '".$ftiponom."' AND
								Periodo = '".$fperiodo."' AND
								CodOrganismo = '".$forganismo."' AND
								CodTipoProceso = '".$ftproceso."'";
				$query = mysql_query($sql) or die ($sql.mysql_error());
				$rows = mysql_num_rows($query);
				if($rows!=0){
				$q .= ", (SELECT 
								Monto 
							FROM 
								pr_tiponominaempleadoconcepto 
							WHERE
								CodConcepto = '".$field_asignaciones['CodConcepto']."' AND
								CodTipoNom = '".$ftiponom."' AND
								Periodo = '".$fperiodo."' AND
								CodOrganismo = '".$forganismo."' AND
								CodTipoProceso = '".$ftproceso."' AND
								CodPersona = p.CodPersona
							) AS '".$field_asignaciones['CodConcepto']."'";
				?><th><?=$field_asignaciones['NomConcepto']?></th><?}
			}
			}
			?>
			<th>TOTAL ASIGNACION</th>
			
			<?
			
			if($chkdeduccion == "D" ){
			$sql = "SELECT
						  tnec.CodConcepto,
						  c.Abreviatura AS NomConcepto,
						  c.Tipo
					FROM
						  pr_tiponominaempleadoconcepto tnec
						  INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto AND c.Tipo = 'D')
					WHERE
						  tnec.CodTipoNom = '".$ftiponom."' AND
						  tnec.Periodo = '".$fperiodo."' AND
						  tnec.CodOrganismo = '".$forganismo."' AND
						  tnec.CodTipoProceso = '".$ftproceso."'
					GROUP BY CodConcepto
					ORDER BY Tipo, CodConcepto";
			$query_deducciones = mysql_query($sql) or die ($sql.mysql_error());
			$rows_deducciones = mysql_num_rows($query_deducciones);
			while ($field_deducciones = mysql_fetch_array($query_deducciones)) {
				$sql="SELECT 
								Monto 
							FROM 
								pr_tiponominaempleadoconcepto 
							WHERE
								CodConcepto = '".$field_deducciones['CodConcepto']."' AND
								CodTipoNom = '".$ftiponom."' AND
								Periodo = '".$fperiodo."' AND
								CodOrganismo = '".$forganismo."' AND
								CodTipoProceso = '".$ftproceso."'";
				$query = mysql_query($sql) or die ($sql.mysql_error());
				$rows = mysql_num_rows($query);
				if($rows!=0){
				$q .= ", (SELECT 
								Monto 
							FROM 
								pr_tiponominaempleadoconcepto 
							WHERE
								CodConcepto = '".$field_deducciones['CodConcepto']."' AND
								CodTipoNom = '".$ftiponom."' AND
								Periodo = '".$fperiodo."' AND
								CodOrganismo = '".$forganismo."' AND
								CodTipoProceso = '".$ftproceso."' AND
								CodPersona = p.CodPersona
							) AS '".$field_deducciones['CodConcepto']."'";
				?><th><?=$field_deducciones['NomConcepto']?></th><?}
			}
			}
			?>
			<th>TOTAL DEDUCCION</th>
                       
                      <?
			
			if($chkaporte == "A" ){
		        $sql = "SELECT
						  tnec.CodConcepto,
						  c.Abreviatura AS NomConcepto,
						  c.Tipo
					FROM
						  pr_tiponominaempleadoconcepto tnec
						  INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto AND c.Tipo = 'A')
					WHERE
						  tnec.CodTipoNom = '".$ftiponom."' AND
						  tnec.Periodo = '".$fperiodo."' AND
						  tnec.CodOrganismo = '".$forganismo."' AND
						  tnec.CodTipoProceso = '".$ftproceso."'
					GROUP BY CodConcepto
					ORDER BY Tipo, CodConcepto";
			$query_aportes = mysql_query($sql) or die ($sql.mysql_error());
			$rows_aportes = mysql_num_rows($query_aportes);
			while ($field_aportes = mysql_fetch_array($query_aportes)) {
				$sql="SELECT 
								Monto 
							FROM 
								pr_tiponominaempleadoconcepto 
							WHERE
								CodConcepto = '".$field_aportes['CodConcepto']."' AND
								CodTipoNom = '".$ftiponom."' AND
								Periodo = '".$fperiodo."' AND
								CodOrganismo = '".$forganismo."' AND
								CodTipoProceso = '".$ftproceso."'";
				$query = mysql_query($sql) or die ($sql.mysql_error());
				$rows = mysql_num_rows($query);
				if($rows!=0){
				$q .= ", (SELECT 
								Monto 

							FROM 
								pr_tiponominaempleadoconcepto 
							WHERE
								CodConcepto = '".$field_aportes['CodConcepto']."' AND

								CodTipoNom = '".$ftiponom."' AND
								Periodo = '".$fperiodo."' AND
								CodOrganismo = '".$forganismo."' AND
								CodTipoProceso = '".$ftproceso."' AND
								CodPersona = p.CodPersona
							) AS '".$field_aportes['CodConcepto']."'";
				?><th><?=$field_aportes['NomConcepto']?></th><?}

			}
			}
			?>
			<th>TOTAL APORTES</th>
			
			<th>TOTAL NETO</th>
		</tr>
		
		<?
		$sql = "SELECT
					  ptne.CodPersona,
					  p.Ndocumento,
					  p.NomCompleto,
					  pu.NivelSalarial as SueldoActual
					  $q
				FROM
					  pr_tiponominaempleado ptne
					  INNER JOIN mastpersonas p ON (ptne.CodPersona = p.CodPersona)
					  INNER JOIN mastempleado pe ON (ptne.CodPersona = pe.CodPersona)
					   INNER JOIN rh_puestos pu ON (pe.CodCargo = pu.CodCargo)
				WHERE
					  ptne.CodTipoNom = '".$ftiponom."' AND
					  ptne.Periodo = '".$fperiodo."' AND
					  ptne.CodOrganismo = '".$forganismo."' AND
					  ptne.CodTipoProceso = '".$ftproceso."'
				ORDER BY length(p.Ndocumento), Ndocumento"; 
		$query_empleados = mysql_query($sql) or die ($sql.mysql_error());
		$rows_empleados = mysql_num_rows($query_empleados);
		while ($field_empleados = mysql_fetch_array($query_empleados)) {
			?>
			<tr>
				<td><?=$field_empleados['Ndocumento']?></td>
				<td><?=utf8_decode($field_empleados['NomCompleto'])?></td>
				<? $montoSA = number_format($field_empleados['SueldoActual'], 2, ',', '');?>
				<td align="right">=DECIMAL(<?=$montoSA?>; 2)</td>
				
				<?

				$total_asignaciones = 0;
				for ($i=4; $i<=$rows_asignaciones+3; $i++) {
					$sum_total[$i] += $field_empleados[$i];
					$total_asignaciones += $field_empleados[$i];
					$monto = number_format($field_empleados[$i], 2, ',', '');
					?><td align="right">=DECIMAL(<?=$monto?>; 2)</td><?
				}
				$total_asignaciones2=$total_asignaciones;
				$total_asignaciones = number_format($total_asignaciones, 2, ',', '');
				?><th align="right">=DECIMAL(<?=$total_asignaciones?>; 2)</th>
				
				<?
				$total_deducciones = 0;
				for ($j=$i; $j<=$rows_deducciones+$i-1; $j++) {
					$sum_total[$j] += $field_empleados[$j];
					$total_deducciones += $field_empleados[$j];
					$monto = number_format($field_empleados[$j], 2, ',', '');
					?><td align="right">=DECIMAL(<?=$monto?>; 2)</td><?
				}
				$total_deducciones2=$total_deducciones;
				$total_deducciones = number_format($total_deducciones, 2, ',', '');
				
				?>
				<th align="right">=DECIMAL(<?=$total_deducciones?>; 2)</th>
				
				<?
				$total_aportes = 0;
				$total_neto= 0;
				for ($h=$j; $h<=$rows_aportes+$j-1; $h++) {
					$sum_total[$h] += $field_empleados[$h];
					$total_aportes += $field_empleados[$h];
					$monto = number_format($field_empleados[$h], 2, ',', '');
					?><td align="right">=DECIMAL(<?=$monto?>; 2)</td><?
				}
				$total_aportes2=$total_aportes;
				$total_aportes = number_format($total_aportes, 2, ',', '');
				$total_neto = number_format(($total_asignaciones2 - $total_deducciones2 + $total_aportes2), 2, ',', '');
				?>
				<th align="right">=DECIMAL(<?=$total_aportes?>; 2)</th>				
				<th align="right">=DECIMAL(<?=$total_neto?>; 2)</th>
			</tr>
			<?
			$total_asignaciones=0;
			$total_deducciones=0;
			$total_aportes=0;
		}
		?>
		
		
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<?
			$total = 0;
			for ($i=4; $i<=$rows_asignaciones+3; $i++) {
				$sum_total_asignaciones += $sum_total[$i];
				$total = number_format($sum_total[$i], 2, ',', '');
				?><th align="right">=DECIMAL(<?=$total?>; 2)</th><?
			}
			$asignaciones = number_format($sum_total_asignaciones, 2, ',', '');
			?><th align="right">=DECIMAL(<?=$asignaciones?>; 2)</th>
			
			<?
			$total = 0;
			for ($k=$i; $k<=$rows_deducciones+$i-1; $k++) {
				$sum_total_deducciones += $sum_total[$k];
				$total = number_format($sum_total[$k], 2, ',', '');
				?><th align="right">=DECIMAL(<?=$total?>; 2)</th><?
			}
			$deducciones = number_format($sum_total_deducciones, 2, ',', '');
			?>
			<th align="right">=DECIMAL(<?=$deducciones?>; 2)</th>				

			<?
			$total = 0;
			for ($j=$k; $j<=$rows_aportes+$k-1; $j++) {
				$sum_total_aportes += $sum_total[$j];
				$total = number_format($sum_total[$j], 2, ',', '');
				?><th align="right">=DECIMAL(<?=$total?>; 2)</th><?
			}
			$aportes = number_format($sum_total_aportes, 2, ',', '');
			$sum_total_neto = number_format(($sum_total_asignaciones - $sum_total_deducciones + $sum_total_aportes), 2, ',', '');
			?>
			<th align="right">=DECIMAL(<?=$aportes?>; 2)</th>	
			<th align="right">=DECIMAL(<?=$sum_total_neto?>; 2)</th>
		</tr>
	</table>
	<?
}
?>
