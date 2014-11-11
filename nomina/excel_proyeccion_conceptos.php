<?php


$archivo='ConceptosProyeccion';
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=$archivo.xls");
header("Pragma: no-cache");
header("Expires: 0");
//	------------------------------------
include("fphp_nomina.php");
connect();


list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('02', $concepto);


$meses = array (
1 => "Enero",
2 => "Febrero",
3 => "Marzo",
4 => "Abril",
5 => "Mayo",
6 => "Junio",
7 => "Julio",
8 => "Agosto",
9 => "Septiembre",
10 => "Octubre",
11 => "Noviembre",
12 => "Diciembre",
              );
              

?>


<html>
	
</head>

	 <body>
<?
//

$sql = "SELECT py_proyeccion.CodProyeccion, py_proyeccion.Descripcion, py_proyeccion.Desde, py_proyeccion.Hasta 
        FROM py_proyeccion
        WHERE py_proyeccion.CodProyeccion='$ftproyeccion'
        ";
$query_proyeccion = mysql_query($sql) or die ($sql.mysql_error());
$field_proyeccion = mysql_fetch_array($query_proyeccion) ;
?>




<table border="1" style="font-size: 8.5px; " cellspacing="0" cellpadding="0" >
		<tr>
			
			<th>CEDULAS</th>
			<th>NOMBRE Y APELLIDO</th>
			<?
         $sql = "SELECT
					c.Tipo,
					
					UPPER(c.Descripcion)  AS NomConcepto,
					epc.CodConcepto
					FROM
					py_empleadoprocesoconcepto AS epc
					INNER JOIN pr_concepto AS c ON epc.CodConcepto = c.CodConcepto AND c.Tipo ='I'
					WHERE
						epc.CodProyeccion='$ftproyeccion'
					GROUP BY
						c.CodConcepto
					ORDER BY
						c.Tipo ASC,
						c.CodConcepto ASC";
				
		$query_asignaciones = mysql_query($sql) or die ($sql.mysql_error());
	    $rows_asignaciones = mysql_num_rows($query_asignaciones);
	    
	    // LISTADO DE ASIGNACIONES	(TITULOS)  //////////////////////////////////////////////////////////////////////   	
	    	while ($field_asignaciones = mysql_fetch_array($query_asignaciones)) {
				$q .= ", (SELECT 
								SUM(Monto)
							FROM 
								py_empleadoprocesoconcepto as epc
							WHERE
							epc.CodProyeccion='$ftproyeccion' AND
								CodConcepto = '".$field_asignaciones['CodConcepto']."' AND
								
								CodPersona = p.CodPersona
							) AS '".$field_asignaciones['CodConcepto']."'";
				?> 
				      <th width="50" height="60" ><?=$field_asignaciones['NomConcepto']?></th> 
					  
			      <?
			}
			?>

			<th width="50" height="60" bgcolor="#D3D3D3">TOTAL ASIGNACION</th>  
			
			<?php
			//  LISTADO DE DEDUCCIONES (TITULOS) //////////////////////////////////////////////////////
         			$sql = "SELECT
					c.Tipo,
							UPPER(c.Descripcion)  AS NomConcepto,
					epc.CodConcepto
					FROM
					py_empleadoprocesoconcepto AS epc
					INNER JOIN pr_concepto AS c ON epc.CodConcepto = c.CodConcepto AND c.Tipo ='D'
					WHERE
						epc.CodProyeccion='$ftproyeccion'
					GROUP BY
						c.CodConcepto
					ORDER BY
						c.Tipo ASC,
						c.CodConcepto ASC";
			$query_deducciones = mysql_query($sql) or die ($sql.mysql_error());
			$rows_deducciones = mysql_num_rows($query_deducciones);
			while ($field_deducciones = mysql_fetch_array($query_deducciones)) {
						$q .= ", (SELECT 
								SUM(Monto)
							FROM 
								py_empleadoprocesoconcepto as epc
							WHERE
							    
							    epc.CodProyeccion='$ftproyeccion' AND
								CodConcepto = '".$field_deducciones['CodConcepto']."' AND
								
								CodPersona = p.CodPersona
							) AS '".$field_deducciones['CodConcepto']."'";
							
				?><th width="50" height="60" ><?=$field_deducciones['NomConcepto']?></th>
			
				
				 <?
				 
			}
			?> <th width="60" height="60"  bgcolor="#D3D3D3">TOTAL DEDUCCION</th>
			<th  width="60" height="60" bgcolor="#D3D3D3">TOTAL NETO</th>

<?php

	//  LISTADO DE APORTES (TITULOS) //////////////////////////////////////////////////////

         			$sql = "SELECT
					c.Tipo,
							UPPER(c.Descripcion)  AS NomConcepto,
					epc.CodConcepto
					FROM
					py_empleadoprocesoconcepto AS epc
					INNER JOIN pr_concepto AS c ON epc.CodConcepto = c.CodConcepto AND c.Tipo ='A'
					WHERE
						epc.CodProyeccion='$ftproyeccion'
					GROUP BY
						c.CodConcepto
					ORDER BY
						c.Tipo ASC,
						c.CodConcepto ASC";
			$query_aportes = mysql_query($sql) or die ($sql.mysql_error());
			$rows_aportes = mysql_num_rows($query_aportes);
			while ($field_aportes = mysql_fetch_array($query_aportes)) {
						$q .= ", (SELECT 
								SUM(Monto)
							FROM 
								py_empleadoprocesoconcepto as epc
							WHERE
							    
							    epc.CodProyeccion='$ftproyeccion' AND
								CodConcepto = '".$field_aportes['CodConcepto']."' AND
								
								CodPersona = p.CodPersona
							) AS '".$field_aportes['CodConcepto']."'";
							
				?><th width="50" height="60"><?=$field_aportes['NomConcepto']?></th>
			
				
				 <?
				 
			}
			?> <th width="60" height="60" bgcolor="#D3D3D3">TOTAL APORTES</th> <?
	
//  LISTADO DE RETENCIONES (TITULOS) //////////////////////////////////////////////////////

         			$sql = "SELECT
					c.Tipo,
							UPPER(c.Descripcion)  AS NomConcepto,
					epc.CodConcepto
					FROM
					py_empleadoprocesoconcepto AS epc
					INNER JOIN pr_concepto AS c ON epc.CodConcepto = c.CodConcepto AND c.Tipo ='A'
					WHERE
						epc.CodProyeccion='$ftproyeccion'
					GROUP BY
						c.CodConcepto
					ORDER BY
						c.Tipo ASC,
						c.CodConcepto ASC";
			$query_retenciones = mysql_query($sql) or die ($sql.mysql_error());
			$rows_retenciones = mysql_num_rows($query_retenciones);
			while ($field_retenciones= mysql_fetch_array($query_retenciones)) {
						$q .= ", (SELECT 
								SUM(Monto)
							FROM 
								py_empleadoprocesoconcepto as epc
							WHERE
							    
							    epc.CodProyeccion='$ftproyeccion' AND
								CodConcepto = '".$field_retenciones['CodConcepto']."' AND
								
								CodPersona = p.CodPersona
							) AS '".$field_retenciones['CodConcepto']."'";
							
				?><th width="50" height="60" ><?=$field_retenciones['NomConcepto']?></th>
			
				
				 <?
				 
			}
			?> <th width="60" height="60" bgcolor="#D3D3D3">TOTAL RETENCIONES</th> <?	
			
// LISTADO DE EMPLEADOS CON LOS MONTOS (TITULOS)////////////////////////////////////////////

            	
	$sql = "SELECT
						ep.CodPersona,
						p.Ndocumento,
						p.NomCompleto
					  $q
				
				FROM
					py_empleadoproceso AS ep
				INNER JOIN mastpersonas AS p ON (ep.CodPersona = p.CodPersona)
				WHERE
					ep.CodProyeccion='$ftproyeccion' 
					GROUP BY CodPersona
					ORDER BY
					length(p.Ndocumento) ASC, p.Ndocumento ASC";
		$query_empleados = mysql_query($sql) or die ($sql.mysql_error());
		$rows_empleados = mysql_num_rows($query_empleados);
// LOS TITULOS DE LAS TABLAS (TITULOS)		
		for($i = 0; $i < mysql_num_fields($query_empleados); $i++) 
		  { $field_info[$i] = mysql_fetch_field($query_empleados, $i)->name;
		 
          }
		
// CALCULO DE LOS MONTOS/////////////////////////////////////////////////////////////////////////
			while ($field_empleados = mysql_fetch_array($query_empleados)) {
				
				//print_r ($field_empleados);
			?>
			
			<tr>
				<td><?=$field_empleados['Ndocumento']?></td>
				<td><?=$field_empleados['NomCompleto']?></td>
				<?
				//ASIGNACIONES /////////////////////////////////////////////////////////////////////////////////
				$total = 0;
				for ($i=3; $i<=$rows_asignaciones+2; $i++) {
						              
					$sum_total[$i] += $field_empleados[$i];
					$total_asignaciones += $field_empleados[$i];

					$monto = number_format($field_empleados[$i], 2, ',', '');

					?><td align="right"  ><?=$monto?></td> <?
				}
				$total_asignaciones = number_format($total_asignaciones, 2, ',', '');
				?><th align="right" bgcolor="#D3D3D3"><?=$total_asignaciones?></th>
				 
				<?
				
				
			    $monto =0;
				//DEDUCCIONES /////////////////////////////////////////////////////////////////////////////////
				
				$total = 0;
				for ($k=$i; $k<=$rows_deducciones+$i-1; $k++) {


				
				$sum_total[$k] += $field_empleados[$k];
				$total_deducciones += $field_empleados[$k];


				$monto = number_format($field_empleados[$k], 2, ',', '');

					?><td align="right"><?=$monto?></td>  <?
				}
				$total_deducciones = number_format($total_deducciones, 2, ',', '');
				$total_neto = number_format(($total_asignaciones - $total_deducciones), 2, ',', '');
				?>
				
				<th align="right" bgcolor="#D3D3D3"><?=$total_deducciones?></th>
				<th align="right" bgcolor="#D3D3D3"><?=$total_neto?></th>

			<?
//APORTES /////////////////////////////////////////////////////////////////////////////////
				$total = 0;
			
				for ($l=$k; $l<=$rows_aportes+$k-1; $l++) {
						              
					$sum_total[$l] += $field_empleados[$l];
					$total_aportes += $field_empleados[$l];

					$monto = number_format($field_empleados[$l], 2, ',', '');

					?><td align="right"  ><?=$monto?></td> <?
				}
				
				//	echo print_r ($sum_total);
				$total_aportes = number_format($total_aportes, 2, ',', '');
				?><th align="right" bgcolor="#D3D3D3"><?=$total_aportes?></th>
				
						 
				<?
				
			    $monto =0;
//////////////////////////////////////////////////////////////////////
//RETENCIONES ////////////////////////////////////////////////////////
				$total = 0;
				for ($m=$l; $m<=$rows_retenciones+$l-1; $m++) {
						           
					$sum_total[$m] += $field_empleados[$m];
					$total_retenciones += $field_empleados[$m];

					$monto = number_format($field_empleados[$m], 2, ',', '');

					?><td align="right"  ><?=$monto?></td> <?
				}
				$total_retenciones = number_format($total_retenciones, 2, ',', '');
				?><th align="right" bgcolor="#D3D3D3"><?=$total_retenciones?></th>
				
			</tr>				 
				<?
				
			    $monto =0;
//////////////////////////////////////////////////////////////////////
			$total_retenciones =0;
            $total_aportes =0;
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
				?><th align="right"><?=$total?></th><?
			}
			$asignaciones = number_format($sum_total_asignaciones, 2, ',', '');
			?><th align="right"><?=$asignaciones?></th>
			
			<?
			$total = 0;
			for ($k=$i; $k<=$rows_deducciones+$i-1; $k++) {
				$sum_total_deducciones += $sum_total[$k];
				$total = number_format($sum_total[$k], 2, ',', '');
				?><th align="right"><?=$total?></th><?
			}
			$deducciones = number_format($sum_total_deducciones, 2, ',', '');
			$sum_total_neto = number_format(($sum_total_asignaciones - $sum_total_deducciones), 2, ',', '');
			?>
			<th align="right"><?=$sum_total_deducciones?></th>				
			<th align="right"><?=$sum_total_neto?></th>
			
			<?
			$total = 0;
			for ($l=$k; $l<=$rows_aportes+$k-1; $l++) {
				$sum_total_aportes += $sum_total[$l];
				$total = number_format($sum_total[$l], 2, ',', '');
				?><th align="right"><?=$total?></th><?
			}
			$aportes = number_format($sum_total_aportes, 2, ',', '');
			?>
			<th align="right"><?=$sum_total_aportes?></th>	
			<?
			$total = 0;
			
			for ($m=$l; $m<=$rows_retenciones+$l-1; $m++) {
				
				$sum_total_retenciones += $sum_total[$m];
				$total = number_format($sum_total[$m], 2, ',', '');
				?><th align="right"><?=$total?></th><?
			}
			$retenciones = number_format($sum_total_retenciones, 2, ',', '');
			?>
			<th align="right"><?=$sum_total_retenciones?></th>	
		</tr>
</table>
</body>
</html>
