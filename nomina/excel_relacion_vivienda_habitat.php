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

<table border="1">
	<tr>
    	<th>NUMERO DE RIF</th>
    	<th>CODIGO DE AGENCIA</th>
    	<th>NACIONALIDAD</th>
    	<th>CEDULA</th>
    	<th>FECHA APORTE</th>
    	<th>APELLIDOS Y NOMBRES</th>
    	<th>APORTE EMPLEADO</th>
    	<th>APORTE EMPRESA</th>
    	<th>F. DE NACIMIENTO</th>
    	<th>SEXO</th>
    	<th>APARTADO POSTAL</th>
    	<th>CODIGO DE LA EMPRESA</th>
    	<th>ESTATUS</th>
    </tr>
	<?
    //	Cuerpo
    $sql = "SELECT 
                mp.CodPersona,
                mp.Ndocumento,
                mp.Busqueda,
				mp.Nacionalidad,
				mp.Fnacimiento,
                ptne.TotalIngresos,
                ptnec.Monto,
                (SELECT SUM(TotalIngresos) 
                    FROM pr_tiponominaempleado 
                        WHERE CodPersona = mp.CodPersona AND CodTipoNom = '".$ftiponom."' AND Periodo = '".$fperiodo."') AS TotalIngresosMes,
                (SELECT Monto 
                    FROM pr_tiponominaempleadoconcepto 
                        WHERE 
                            CodPersona = mp.CodPersona AND 
                            CodTipoNom = '".$ftiponom."' AND 
                            Periodo = '".$fperiodo."' AND 
                            CodTipoproceso = '".$ftproceso."' AND CodConcepto = '0031') AS Aporte
            FROM
                mastpersonas mp
                INNER JOIN pr_tiponominaempleado ptne ON (mp.CodPersona = ptne.CodPersona)
                INNER JOIN pr_tiponominaempleadoconcepto ptnec ON (ptne.CodPersona = ptnec.CodPersona AND ptne.CodTipoNom = ptnec.CodTipoNom AND ptne.Periodo = ptnec.Periodo AND ptne.CodTipoproceso = ptnec.CodTipoProceso AND ptnec.CodConcepto = '0026')
            WHERE
                ptne.CodTipoNom = '".$ftiponom."' AND
                ptne.Periodo = '".$fperiodo."' AND
                ptne.CodTipoProceso = '".$ftproceso."'
            ORDER BY length(mp.Ndocumento), mp.Ndocumento";
    $query = mysql_query($sql) or die ($sql.mysql_error());
    while ($field = mysql_fetch_array($query)) {
        $sum_ingresos += $field['TotalIngresos'];
        $sum_retenciones += $field['Monto'];
        $sum_aportes += $field['Aporte'];
		list($a, $m, $d)=SPLIT( '[/.-]', $field['Fnacimiento']); $fnac = "$d/$m/$a";
        ?>
        <tr>
        	<td>G200005688</td>
        	<td>20</td>
        	<td><?=$field['Nacionalidad']?></td>
        	<td><?=$field['Ndocumento']?></td>
        	<td><?=date("d/m/Y")?></td>
        	<td><?=($field['Busqueda'])?></td>
        	<td><?=number_format($field['Monto'], 2, ',', '.')?></td>
        	<td><?=number_format($field['Aporte'], 2, ',', '.')?></td>
        	<td><?=$fnac?></td>
        	<td><?=$field['Sexo']?></td>
        	<td>6401</td>
        	<td></td>
        	<td>1</td>
        </tr>
        <?
    }
    ?>
</table>