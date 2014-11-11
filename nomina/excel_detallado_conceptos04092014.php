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
if (($chkasignacion == "I" && $chkdeduccion == "") || ($chkasignacion == "" && $chkdeduccion == "D")) {
	if ($chkasignacion == "I") $ftipo = "I";
	elseif ($chkdeduccion == "D") $ftipo = "D";
	?>
	<table border="1">
		<tr>
			<th>CEDULA</th>
			<th>NOMBRE Y APELLIDO</th>
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
				?><th><?=$field_conceptos['NomConcepto']?></th><?
			}
			?>
			<th>TOTAL CONCEPTOS</th>
		</tr>
		
		<?
		$sql = "SELECT
					  ptne.CodPersona,
					  p.Ndocumento,
					  p.NomCompleto
					  $q
				FROM
					  pr_tiponominaempleado ptne
					  INNER JOIN mastpersonas p ON (ptne.CodPersona = p.CodPersona)
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
				<td><?=$field_empleados['NomCompleto']?></td>
				<?
				$total = 0;
				for ($i=3; $i<=$rows_conceptos+2; $i++) {
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
			<?
			for ($i=3; $i<=$rows_conceptos+2; $i++) {
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

elseif ($chkasignacion == "I" && $chkdeduccion == "D") {
	?>
	<table border="1">
		<tr>
			<th>CEDULA</th>
			<th>NOMBRE Y APELLIDO</th>
			<?
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
				?><th><?=$field_asignaciones['NomConcepto']?></th><?
			}
			?>
			<th>TOTAL ASIGNACION</th>
			
			<?
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
				?><th><?=$field_deducciones['NomConcepto']?></th><?
			}
			?>
			<th>TOTAL DEDUCCION</th>
			<th>TOTAL NETO</th>
		</tr>
		
		<?
		$sql = "SELECT
					  ptne.CodPersona,
					  p.Ndocumento,
					  p.NomCompleto
					  $q
				FROM
					  pr_tiponominaempleado ptne
					  INNER JOIN mastpersonas p ON (ptne.CodPersona = p.CodPersona)
				WHERE
					  ptne.CodTipoNOm = '".$ftiponom."' AND
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
				<td><?=$field_empleados['NomCompleto']?></td>
				<?
				$total = 0;
				for ($i=3; $i<=$rows_asignaciones+2; $i++) {
					$sum_total[$i] += $field_empleados[$i];
					$total_asignaciones += $field_empleados[$i];
					$monto = number_format($field_empleados[$i], 2, ',', '');
					?><td align="right">=DECIMAL(<?=$monto?>; 2)</td><?
				}
				$total_asignaciones = number_format($total_asignaciones, 2, ',', '');
				?><th align="right">=DECIMAL(<?=$total_asignaciones?>; 2)</th>
				
				<?
				$total = 0;
				for ($k=$i; $k<=$rows_deducciones+$i-1; $k++) {
					$sum_total[$k] += $field_empleados[$k];
					$total_deducciones += $field_empleados[$k];
					$monto = number_format($field_empleados[$k], 2, ',', '');
					?><td align="right">=DECIMAL(<?=$monto?>; 2)</td><?
				}
				$total_deducciones = number_format($total_deducciones, 2, ',', '');
				$total_neto = number_format(($total_asignaciones - $total_deducciones), 2, ',', '');
				?>
				<th align="right">=DECIMAL(<?=$total_deducciones?>; 2)</th>				
				<th align="right">=DECIMAL(<?=$total_neto?>; 2)</th>
			</tr>
			<?
			$total_asignaciones=0;
			$total_deducciones=0;
		}
		?>
		
		
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<?
			$total = 0;
			for ($i=3; $i<=$rows_asignaciones+2; $i++) {
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
			$sum_total_neto = number_format(($sum_total_asignaciones - $sum_total_deducciones), 2, ',', '');
			?>
			<th align="right">=DECIMAL(<?=$sum_total_deducciones?>; 2)</th>				
			<th align="right">=DECIMAL(<?=$sum_total_neto?>; 2)</th>
		</tr>
	</table>
	<?
}
?>