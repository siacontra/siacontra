<?php
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=$archivo.xls");
header("Pragma: no-cache");
header("Expires: 0");
//---------------------------------------------------
include("fphp_nomina.php");
connect();
?>

<table>
	<tr>
    	<th>CONCEPTO</th>
    	<th>ADELANTO</th>
    	<th>FIN DE MES</th>
    	<th>NETO</th>
	</tr>
<?
if ($ftproceso == "FIN") {
	$sql = "(SELECT
				pc.CodConcepto,
				pc.Descripcion,
				SUM(ptnec.Monto) AS MontoFin,
				(SELECT SUM(Monto)
					FROM pr_tiponominaempleadoconcepto
						WHERE
							CodConcepto = pc.CodConcepto AND
							Tipo = 'I' AND
							CodTipoNom = '".$ftiponom."' AND 
							Periodo = '".$fperiodo."' AND 
							CodTipoProceso = 'ADE'
						GROUP BY
							CodTipoNom,
							Periodo,
							CodTipoProceso) AS MontoAde
				
			FROM
				pr_concepto pc
				INNER JOIN pr_tiponominaempleadoconcepto ptnec ON (pc.CodConcepto = ptnec.CodConcepto)
			WHERE
				pc.Tipo = 'I' AND
				ptnec.CodTipoNom = '".$ftiponom."' AND 
				ptnec.Periodo = '".$fperiodo."' AND 
				ptnec.CodTipoProceso = '".$ftproceso."'
			GROUP BY
				ptnec.CodTipoNom,
				ptnec.Periodo,
				ptnec.CodTipoProceso,
				ptnec.CodConcepto)
				
			UNION 
			
			(SELECT
				pc.CodConcepto,
				pc.Descripcion,
				'' AS MontoFin,
				SUM(ptnec.Monto) AS MontoAde
			FROM
				pr_concepto pc
				INNER JOIN pr_tiponominaempleadoconcepto ptnec ON (pc.CodConcepto = ptnec.CodConcepto)
			WHERE
				pc.Tipo = 'I' AND
				ptnec.CodTipoNom = '".$ftiponom."' AND 
				ptnec.Periodo = '".$fperiodo."' AND 
				ptnec.CodTipoProceso = 'ADE' AND
				ptnec.CodConcepto NOT IN (SELECT CodConcepto FROM pr_tiponominaempleadoconcepto WHERE CodTipoNom = '".$ftiponom."' AND Periodo = '".$fperiodo."' AND CodTipoProceso = 'FIN')
			GROUP BY
				ptnec.CodTipoNom,
				ptnec.Periodo,
				ptnec.CodTipoProceso,
				ptnec.CodConcepto)";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	while ($field = mysql_fetch_array($query)) {
		$total_mes = $field['MontoFin'] - $field['MontoAde'];
		$total_asignaciones += $total_mes;
		$total_ade_a += $field['MontoAde'];
		$total_fin_a += $field['MontoFin'];
		?>
        <tr>
        	<td><?=$field['Descripcion']?></td>
        	<td align="right"><?=number_format($field['MontoAde'], 2, ',', '.')?></td>
        	<td align="right"><?=number_format($field['MontoFin'], 2, ',', '.')?></td>
        	<td align="right"><?=number_format($total_mes, 2, ',', '.')?></td>
        </tr>
        <?
	}
	?>
    <tr>
        <th><?=('TOTAL ASIGNACIONES')?></th>
        <th align="right"><?=number_format($total_ade_a, 2, ',', '.')?></th>
        <th align="right"><?=number_format($total_fin_a, 2, ',', '.')?></th>
        <th align="right"><?=number_format($total_asignaciones, 2, ',', '.')?></th>
    </tr>
    <?
	//---------------------------------------------------
	$sql = "(SELECT
				pc.CodConcepto,
				pc.Descripcion,
				SUM(ptnec.Monto) AS MontoFin,
				(SELECT SUM(Monto)
					FROM pr_tiponominaempleadoconcepto
						WHERE
							CodConcepto = pc.CodConcepto AND
							Tipo = 'D' AND
							CodTipoNom = '".$ftiponom."' AND 
							Periodo = '".$fperiodo."' AND 
							CodTipoProceso = 'ADE'
						GROUP BY
							CodTipoNom,
							Periodo,
							CodTipoProceso) AS MontoAde
			FROM
				pr_concepto pc
				INNER JOIN pr_tiponominaempleadoconcepto ptnec ON (pc.CodConcepto = ptnec.CodConcepto)
			WHERE
				pc.Tipo = 'D' AND
				ptnec.CodTipoNom = '".$ftiponom."' AND 
				ptnec.Periodo = '".$fperiodo."' AND 
				ptnec.CodTipoProceso = '".$ftproceso."'
			GROUP BY
				ptnec.CodTipoNom,
				ptnec.Periodo,
				ptnec.CodTipoProceso,
				ptnec.CodConcepto)
				
			UNION 
			
			(SELECT
				pc.CodConcepto,
				pc.Descripcion,
				'' AS MontoFin,
				SUM(ptnec.Monto) AS MontoAde
			FROM
				pr_concepto pc
				INNER JOIN pr_tiponominaempleadoconcepto ptnec ON (pc.CodConcepto = ptnec.CodConcepto)
			WHERE
				pc.Tipo = 'D' AND
				ptnec.CodTipoNom = '".$ftiponom."' AND 
				ptnec.Periodo = '".$fperiodo."' AND 
				ptnec.CodTipoProceso = 'ADE' AND
				ptnec.CodConcepto NOT IN (SELECT CodConcepto FROM pr_tiponominaempleadoconcepto WHERE CodTipoNom = '".$ftiponom."' AND Periodo = '".$fperiodo."' AND CodTipoProceso = 'FIN')
			GROUP BY
				ptnec.CodTipoNom,
				ptnec.Periodo,
				ptnec.CodTipoProceso,
				ptnec.CodConcepto)";
	$query = mysql_query($sql) or die ($sql.mysql_error()); $total_ade=0; $total_fin=0; $total_mes=0;
	while ($field = mysql_fetch_array($query)) {
		if ($field['CodConcepto'] != "0017") $total_mes = $field['MontoFin'] - $field['MontoAde'];
		$total_deducciones += $total_mes;
		$total_ade_f += $field['MontoAde'];
		$total_fin_f += $field['MontoFin'];
		?>
        <tr>
        	<td><?=$field['Descripcion']?></td>
        	<td align="right"><?=number_format($field['MontoAde'], 2, ',', '.')?></td>
        	<td align="right"><?=number_format($field['MontoFin'], 2, ',', '.')?></td>
        	<td align="right"><?=number_format($total_mes, 2, ',', '.')?></td>
        </tr>
        <?
	}
	?>
    <tr>
        <th><?=('TOTAL DEDUCCIONES')?></th>
        <th align="right"><?=number_format($total_ade_f, 2, ',', '.')?></th>
        <th align="right"><?=number_format($total_fin_f, 2, ',', '.')?></th>
        <th align="right"><?=number_format($total_deducciones, 2, ',', '.')?></th>
    </tr>
    <?
	//---------------------------------------------------
	$total_adelanto = $total_ade_a - $total_ade_f;
	$total_fin = $total_fin_a - $total_fin_f;
	$total_neto = $total_asignaciones - $total_deducciones;
	?>
    <tr>
        <th><?=('TOTAL NETO')?></th>
        <th align="right"><?=number_format($total_adelanto, 2, ',', '.')?></th>
        <th align="right"><?=number_format($total_fin, 2, ',', '.')?></th>
        <th align="right"><?=number_format($total_neto, 2, ',', '.')?></th>
    </tr>
    <?
	//---------------------------------------------------
	$sql = "(SELECT
				pc.CodConcepto,
				pc.Descripcion,
				SUM(ptnec.Monto) AS MontoFin,
				(SELECT SUM(Monto)
					FROM pr_tiponominaempleadoconcepto
						WHERE
							CodConcepto = pc.CodConcepto AND
							Tipo = 'A' AND
							CodTipoNom = '".$ftiponom."' AND 
							Periodo = '".$fperiodo."' AND 
							CodTipoProceso = 'ADE'
						GROUP BY
							CodTipoNom,
							Periodo,
							CodTipoProceso) AS MontoAde
			FROM
				pr_concepto pc
				INNER JOIN pr_tiponominaempleadoconcepto ptnec ON (pc.CodConcepto = ptnec.CodConcepto)
			WHERE
				pc.Tipo = 'A' AND
				ptnec.CodTipoNom = '".$ftiponom."' AND 
				ptnec.Periodo = '".$fperiodo."' AND 
				ptnec.CodTipoProceso = '".$ftproceso."'
			GROUP BY
				ptnec.CodTipoNom,
				ptnec.Periodo,
				ptnec.CodTipoProceso,
				ptnec.CodConcepto)
				
			UNION 
			
			(SELECT
				pc.CodConcepto,
				pc.Descripcion,
				'' AS MontoFin,
				SUM(ptnec.Monto) AS MontoAde
			FROM
				pr_concepto pc
				INNER JOIN pr_tiponominaempleadoconcepto ptnec ON (pc.CodConcepto = ptnec.CodConcepto)
			WHERE
				pc.Tipo = 'A' AND
				ptnec.CodTipoNom = '".$ftiponom."' AND 
				ptnec.Periodo = '".$fperiodo."' AND 
				ptnec.CodTipoProceso = 'ADE' AND
				ptnec.CodConcepto NOT IN (SELECT CodConcepto FROM pr_tiponominaempleadoconcepto WHERE CodTipoNom = '".$ftiponom."' AND Periodo = '".$fperiodo."' AND CodTipoProceso = 'FIN')
			GROUP BY
				ptnec.CodTipoNom,
				ptnec.Periodo,
				ptnec.CodTipoProceso,
				ptnec.CodConcepto)";
	$query = mysql_query($sql) or die ($sql.mysql_error()); $total_ade=0; $total_fin=0; $total_mes=0;
	while ($field = mysql_fetch_array($query)) {
		$total_mes = $field['MontoFin'] - $field['MontoAde'];
		$total_patronales += $total_mes;
		$total_ade_p += $field['MontoAde'];
		$total_fin_p += $field['MontoFin'];
		?>
        <tr>
        	<td><?=$field['Descripcion']?></td>
        	<td align="right"><?=number_format($field['MontoAde'], 2, ',', '.')?></td>
        	<td align="right"><?=number_format($field['MontoFin'], 2, ',', '.')?></td>
        	<td align="right"><?=number_format($total_mes, 2, ',', '.')?></td>
        </tr>
        <?
	}
	?>
    <tr>
        <th><?=('TOTAL APORTES')?></th>
        <th align="right"><?=number_format($total_ade_p, 2, ',', '.')?></th>
        <th align="right"><?=number_format($total_fin_p, 2, ',', '.')?></th>
        <th align="right"><?=number_format($total_patronales, 2, ',', '.')?></th>
    </tr>
    <?
	
} else {

	$sql = "SELECT
				pc.CodConcepto,
				pc.Descripcion,
				SUM(ptnec.Monto) AS Monto
			FROM
				pr_concepto pc
				INNER JOIN pr_tiponominaempleadoconcepto ptnec ON (pc.CodConcepto = ptnec.CodConcepto)
			WHERE
				pc.Tipo = 'I' AND
				ptnec.CodTipoNom = '".$ftiponom."' AND 
				ptnec.Periodo = '".$fperiodo."' AND 
				ptnec.CodTipoProceso = '".$ftproceso."'
			GROUP BY
				ptnec.CodTipoNom,
				ptnec.Periodo,
				ptnec.CodTipoProceso,
				ptnec.CodConcepto";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	while ($field = mysql_fetch_array($query)) {
		$total_asignaciones += $field['Monto'];
		?>
        <tr>
        	<td><?=$field['Descripcion']?></td>
        	<td align="right"><?=number_format($field['Monto'], 2, ',', '.')?></td>
        	<td></td>
        	<td></td>
        </tr>
        <?
	}
	?>
    <tr>
        <th><?=('TOTAL ASIGNACIONES')?></th>
        <th align="right"><?=number_format($total_asignaciones, 2, ',', '.')?></th>
        <th></th>
        <th></th>
    </tr>
    <?
	//---------------------------------------------------
	$sql = "SELECT
				pc.CodConcepto,
				pc.Descripcion,
				SUM(ptnec.Monto) AS Monto
			FROM
				pr_concepto pc
				INNER JOIN pr_tiponominaempleadoconcepto ptnec ON (pc.CodConcepto = ptnec.CodConcepto)
			WHERE
				pc.Tipo = 'D' AND
				ptnec.CodTipoNom = '".$ftiponom."' AND 
				ptnec.Periodo = '".$fperiodo."' AND 
				ptnec.CodTipoProceso = '".$ftproceso."'
			GROUP BY
				ptnec.CodTipoNom,
				ptnec.Periodo,
				ptnec.CodTipoProceso,
				ptnec.CodConcepto";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	while ($field = mysql_fetch_array($query)) {
		$total_deducciones += $field['Monto'];
		?>
        <tr>
        	<td><?=$field['Descripcion']?></td>
        	<td align="right"><?=number_format($field['Monto'], 2, ',', '.')?></td>
        	<td></td>
        	<td></td>
        </tr>
        <?
	}
	?>
    <tr>
        <th><?=('TOTAL DEDUCCIONES')?></th>
        <th></th>
        <th align="right"><?=number_format($total_deducciones, 2, ',', '.')?></th>
        <th></th>
    </tr>
    <?
	//---------------------------------------------------
	$total_neto = $total_asignaciones - $total_deducciones;
	?>
    <tr>
        <th><?=('TOTAL NETO')?></th>
        <th></th>
        <th></th>
        <th align="right"><?=number_format($total_neto, 2, ',', '.')?></th>
    </tr>
    <?
}
?>
</table>