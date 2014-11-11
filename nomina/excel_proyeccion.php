<?php


$archivo='proyeccion';
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
              
              
              // creo un array con las partidas y montos
$montopartida = array();
?>


<html>
	
</head>

	 <body>
<?
$CodProyeccion = $ftproyeccion;



  $sql_partidas2= "SELECT
pr_conceptoperfildetalle.cod_partida,
pv_partida.denominacion
FROM

py_empleadoprocesoconcepto AS a

INNER JOIN tiponomina ON a.CodTipoNom = tiponomina.CodTipoNom

INNER JOIN pr_conceptoperfildetalle ON a.CodTipoProceso = pr_conceptoperfildetalle.CodTipoProceso AND a.CodConcepto = pr_conceptoperfildetalle.CodConcepto AND tiponomina.CodPerfilConcepto = pr_conceptoperfildetalle.CodPerfilConcepto

INNER JOIN pv_partida ON pv_partida.cod_partida = pr_conceptoperfildetalle.cod_partida
where  	pr_conceptoperfildetalle.cod_partida != ''
AND a.CodProyeccion = '$CodProyeccion'
GROUP BY  pr_conceptoperfildetalle.cod_partida
ORDER BY  pr_conceptoperfildetalle.cod_partida";
// Consulto los Periodos por Proyeccion

 $sql_periodos=  "SELECT
pyp.CodProyeccion, pyp.CodTipoNom,
pyp.Periodo, pyp.CodTipoProceso,
pyp.Mes, pyp.Anio, pyp.Descripcion
FROM
py_proceso AS pyp 
WHERE
pyp.CodProyeccion = '$CodProyeccion'
GROUP BY pyp.Periodo
ORDER BY pyp.Periodo ASC";
$query_periodos = mysql_query($sql_periodos) or die ($sql_periodos.mysql_error());

while ($field_periodos = mysql_fetch_array($query_periodos)) {
	$field_periodos['Periodo'];

// CONSULTO LA CANTIDA DE PARTIDAS
  $sql_partidas= "SELECT
pr_conceptoperfildetalle.cod_partida,
pv_partida.denominacion
FROM
py_empleadoprocesoconcepto AS a
INNER JOIN tiponomina ON a.CodTipoNom = tiponomina.CodTipoNom
INNER JOIN pr_conceptoperfildetalle ON a.CodTipoProceso = pr_conceptoperfildetalle.CodTipoProceso AND a.CodConcepto = pr_conceptoperfildetalle.CodConcepto AND tiponomina.CodPerfilConcepto = pr_conceptoperfildetalle.CodPerfilConcepto
INNER JOIN pv_partida ON pv_partida.cod_partida = pr_conceptoperfildetalle.cod_partida
where  	pr_conceptoperfildetalle.cod_partida != ''
AND a.CodProyeccion = '$CodProyeccion'
AND a.Periodo = '".$field_periodos['Periodo']."'

GROUP BY  pr_conceptoperfildetalle.cod_partida ORDER BY  pr_conceptoperfildetalle.cod_partida ";

$query_partidas = mysql_query($sql_partidas) or die ($sql_partidas.mysql_error());
$rows_partidas = mysql_num_rows($query_partidas);

	$codorganismo = '0001';
$codpresupuesto = '0001';


while ($field_partidas = mysql_fetch_array($query_partidas)) { 
	
$montopartida[$field_periodos['Periodo']][$field_partidas['cod_partida']]['cod_partida']=$field_partidas['cod_partida'];
$montopartida[$field_periodos['Periodo']][$field_partidas['cod_partida']]['denominacion']=$field_partidas['denominacion'];
       // Obtengo los montos
		  $sql_monto= "SELECT
		SUM(a.Monto) as Monto,
		pr_conceptoperfildetalle.cod_partida
		FROM
		py_empleadoprocesoconcepto AS a
		INNER JOIN tiponomina ON a.CodTipoNom = tiponomina.CodTipoNom
		INNER JOIN pr_conceptoperfildetalle ON a.CodTipoProceso = pr_conceptoperfildetalle.CodTipoProceso AND a.CodConcepto = pr_conceptoperfildetalle.CodConcepto AND tiponomina.CodPerfilConcepto = pr_conceptoperfildetalle.CodPerfilConcepto
		where  	pr_conceptoperfildetalle.cod_partida != ''
		AND a.CodProyeccion = '$CodProyeccion'
		AND  pr_conceptoperfildetalle.cod_partida = '".$field_partidas['cod_partida']."'
		AND a.Periodo = '".$field_periodos['Periodo']."'
		";
		
		$query_monto = mysql_query($sql_monto) or die ($sql_monto.mysql_error());
		//$rows_monto = mysql_num_rows($query_monto);	
		$field_monto = mysql_fetch_array($query_monto);  
		
		//optengo la disponibilidad por partida
		/*
		$sql_disponibilidad= "
		SELECT
		pv_presupuestodet.MontoAjustado,
		pv_presupuestodet.cod_partida,
		pv_presupuestodet.MontoPagado,
		pv_presupuestodet.MontoCompromiso,
		pv_presupuestodet.MontoAjustado - pv_presupuestodet.MontoCompromiso as Disponible
		FROM
		pv_presupuestodet
		where pv_presupuestodet.cod_partida = '".$field_partidas['cod_partida']."'
		ORDER BY  pv_presupuestodet.cod_partida";
		
		$query_disponibilidad = mysql_query($sql_disponibilidad) or die ($sql_disponibilidad.mysql_error());
	//	$rows_disponibilidad = mysql_num_rows($query_disponibilidad);	
		$field_disponibilidad = mysql_fetch_array($query_disponibilidad);  
	//	print_r ($field_disponibilidad);*/
	
	  
	
	  
$montopartida[$field_periodos['Periodo']][$field_partidas['cod_partida']]['Monto']=$field_monto['Monto'];			  
$montopartida[$field_periodos['Periodo']][$field_partidas['cod_partida']]['Disponible']= $disponible03;

} 
//print_r ($montopartida);
}


$disponiblepartida= array();

/// CALCULO DE LA DISPONIBILIDAD PRESUPUESTARIA. 
// CONSULTO LA CANTIDA DE PARTIDAS
		$sql_partidas_dis= "SELECT
		pr_conceptoperfildetalle.cod_partida,
		pv_partida.denominacion
		FROM
		py_empleadoprocesoconcepto AS a
		INNER JOIN tiponomina ON a.CodTipoNom = tiponomina.CodTipoNom
		INNER JOIN pr_conceptoperfildetalle ON a.CodTipoProceso = pr_conceptoperfildetalle.CodTipoProceso AND a.CodConcepto = pr_conceptoperfildetalle.CodConcepto AND tiponomina.CodPerfilConcepto = pr_conceptoperfildetalle.CodPerfilConcepto
		INNER JOIN pv_partida ON pv_partida.cod_partida = pr_conceptoperfildetalle.cod_partida
		where  	pr_conceptoperfildetalle.cod_partida != ''
		AND a.CodProyeccion = '$CodProyeccion'
		GROUP BY  pr_conceptoperfildetalle.cod_partida ORDER BY  pr_conceptoperfildetalle.cod_partida ";

		$query_partidas_dis = mysql_query($sql_partidas_dis) or die ($sql_partidas_dis.mysql_error());

		while ($field_partidas_dis = mysql_fetch_array($query_partidas_dis)) { 

		$sqlD= "SELECT *
		FROM pv_presupuestodet
		WHERE cod_partida = '".$field_partidas_dis['cod_partida']."'
		AND Organismo = '".$codorganismo ."'
		AND CodPresupuesto = '".$codpresupuesto ."'";
		$qDisp = mysql_query($sqlD) or die ($sqlD.mysql_error());
		$rDisp = mysql_num_rows($qDisp);
		$fDisp= mysql_fetch_array($qDisp);

      $disponible03=$fDisp['MontoAjustado']-$fDisp['MontoCompromiso'];
		
		
		$disponiblepartida[$field_partidas_dis['cod_partida']] =  $disponible03;
	
}

//print_r ($disponiblepartida);


///////////////////////////////////////////////////////////////////////////////////////////////		
	?>
		<table border="0.9" style="font-size: 10.5px; width:100%" >
			
		<tr >
		    <th> </th>
		      <th> </th>
		  
		    <? 
			
			   $query_periodos = mysql_query($sql_periodos) or die ($sql_periodos.mysql_error());
				while ($field_periodos = mysql_fetch_array($query_periodos)) 

			 echo "<th bgcolor='#D3D3D3' colspan=3> ".$field_periodos['Periodo']."</th> ";
			 ?>
	    </tr>
	    
	    <tr >
		    <th bgcolor="#D3D3D3"  width= "60px"  >Partida</th>
		     <th bgcolor="#D3D3D3"  width= "60px"  >Denominacion</th>
		       
		    <? 
		     $query_periodos = mysql_query($sql_periodos) or die ($sql_periodos.mysql_error());
		     while ($field_periodos = mysql_fetch_array($query_periodos)) 
		    
			 echo "<th  bgcolor='#D3D3D3'    >Monto</th>
			 <th bgcolor='#D3D3D3' >Disponible</th>
			 <th bgcolor='#D3D3D3' >Total</th>
			 ";
			 ?>
	    </tr>
	    
	    
	<?	
	
	//  $query_periodos = mysql_query($sql_periodos) or die ($sql_periodos.mysql_error());
	  $query_partidas2 = mysql_query($sql_partidas2) or die ($sql_partidas2.mysql_error());
	while ($partida = mysql_fetch_array($query_partidas2)) {
		
		   $PartidaPeriodo = $montopartida[$field_periodos['Periodo']];
		   $codpartida = $partida[0] ;
		   $denominacion = $partida[1] ;
		    
		  
		     
		    //foreach ($PartidaPeriodo as $fila ) {   		 
	             
	            
				  $monto= 0;
				//  $disponible= 0; //  $montopartida[$periodo['Periodo']][$fila['cod_partida']]['Disponible'];
				  $montoMes=0;
			?>
			  
			   
			  
				<tr>
					<td><?= $codpartida ?></td>
						<td><?= $denominacion ?></td>
					
					  <?    $query_periodos = mysql_query($sql_periodos) or die ($sql_periodos.mysql_error());
						while ($periodo = mysql_fetch_array($query_periodos)) { 
							
							  
		                   $disponible= $disponiblepartida[$codpartida ];
							
							//echo $periodo['Periodo'];
					   ?>
					 
					   <td width='60px' align="right" bgcolor="#E0FFFF"> <?=  number_format($montoMes=   $montopartida[$periodo['Periodo']][$codpartida]['Monto'] , 2, ',', '.'); ?> </td>
					   <td align="right"  bgcolor="#E0FFFF" ><?=  number_format($disponible, 2, ',', '.'); ?></td>
					   <td width='60px' align="right"  bgcolor="#E0FFFF" ><?=   number_format($disponible - $montoMes , 2, ',', '.');  ?></td>
					   
					  <?
					    $disponiblepartida[$codpartida ] = $disponible - $montoMes;
					   } ?>
				</tr>
			  <?// } 
		     
		     
		
		}
?>
		
</table>
</body>
</html>
