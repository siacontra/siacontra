<?php

include("../fphp.php");
connect();

switch ($accion) {
	
	case 'GUARDAR':
	    $error=0;
		//	Guardar registro...
		$sql = "INSERT INTO py_proyeccion 
							(
								
								CodProyeccion,
								Desde,
								Hasta,
								Descripcion
							)
							VALUES (
							
							'".$tx_codigo."',
							'".$tx_desde."',
							'".$tx_hasta."',
							'".$tx_descripcion."' 
							)";
		$query = mysql_query($sql) or die ($sql.mysql_error());
        

	break;
	
	case 'ELIMINAR':
	    $error=0;
		//	Eliminar registro....
		$sql = "DELETE FROM py_proceso WHERE CodProceso = '".$tx_codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
        

	break;
	
	case 'ACTUALIZAR':
	    $error=0;
	
			//	Modificar registro...
			$sql = "UPDATE py_proceso SET
											Proceso = '".$tx_proceso."',
											Periodo = '".$tx_periodo."', 
											Descripcion = '".$tx_descripcion."', 
											Anio    = '".$tx_anio."'
												 
											WHERE 
												 	CodProceso = '".$tx_codigo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		
	break;
	
	
	///////////////////////////////////////////
	case 'QUITAR-SEL-NOMINA':
	
	 
		$seleccion = explode("|;|", $seleccionados);
		foreach ($seleccion as $registro) {
			list($codpersona)=SPLIT( '[|:|]', $registro);			
			//	Elimino los datos del empleado si tiene...
			$sql = "DELETE FROM py_empleadoprocesoconcepto 
					WHERE 
						CodPersona = '".$codpersona."' AND 
						CodProceso='".$proceso."'";
			$query_delete = mysql_query($sql) or die ($sql.mysql_error());
			//	---------------------------
			$sql = "DELETE FROM py_empleadoproceso 
					WHERE 
						CodPersona = '".$codpersona."' AND 
						CodProceso='".$proceso."'";
			$query_delete = mysql_query($sql) or die ($sql.mysql_error());
		}
	
    break;
    
    ///////////////////////////////////////////
	case 'AGREGAR-SEL-NOMINA':
	
	 
		$seleccion = explode("|;|", $seleccionados);
		foreach ($seleccion as $registro) {
			list($codpersona)=SPLIT( '[|:|]', $registro);			
			//	agrego los datos del empleado si tiene...
		
	
		$sql = "INSERT INTO `py_empleadoproceso` 
		        (`CodProceso`, `CodPersona`, `SueldoBasico`, `TotalIngresos`, `TotalEgreso`, `TotalPatronales`, `TotalNeto`)
		        VALUES ('".$proceso."', '".$codpersona."', '0.00', '0.00', '0.00', '0.00', '0.00')	";		
						
			$query_delete = mysql_query($sql) or die ($sql.mysql_error());
		}
	
    break;
    
     ///////////////////////////////////////////
	case 'PROCESAR-NOMINA':
	
	
	$empleado = explode("|:|", $aprobados);

	//	Recorro cada empleado seleccionado
	foreach ($empleado as $codpersona) {
		
		
	//	Elimino los datos del empleado si tiene...
	$sql = "DELETE FROM py_empleadoprocesoconcepto 
			WHERE 
				CodPersona = '".$codpersona."' AND 
				CodProceso='".$proceso."'";
				
	$query_delete = mysql_query($sql) or die ($sql.mysql_error());
	
	//	Selecciono los datos de la ultimo mes
		$sql ="	SELECT
			tnec.CodTipoNom,
			tnec.Periodo,
			tnec.CodPersona,
			tnec.CodOrganismo,
			tnec.CodTipoProceso,
			tnec.CodConcepto,
			tnec.Monto,
			tnec.Cantidad,
			tnec.Saldo,
			tnec.UltimoUsuario,
			tnec.UltimaFecha
			FROM
			pr_tiponominaempleadoconcepto AS tnec

			where tnec.Periodo = '2014-02' AND
			tnec.CodPersona = '".$codpersona."' AND 
			tnec. CodTipoProceso ='FIN'";
			
			$query_concepto = mysql_query($sql) or die ($sql.mysql_error());
			$rows_conceptos = mysql_num_rows($query_concepto);
			
			while ($field_concepto = mysql_fetch_array($query_concepto)) {
				
			//	print_r ($field_concepto);
				
    
			echo	$query_insert ="INSERT INTO `py_empleadoprocesoconcepto`
				      (
				      `CodProceso`, 
				      `CodPersona`,
				      `CodConcepto`, 
				      `Monto`, 
				      `MontoP`, 
				      `Cantidad`, 
				      `Saldo`) 
				      VALUES 
				      ('".$proceso."', 
				       '".$codpersona."',
				       '".$field_concepto['CodConcepto']."', 
				       '".$field_concepto['Monto']."', 
				       '".$field_concepto['Monto']."', 
				       '".$field_concepto['Cantidad']."',
				       '".$field_concepto['Saldo']."')";
				$query_insert = mysql_query($query_insert) or die ($query_insert.mysql_error());
				
			}
		
     }		
	
	 
	 
	 
	   
	
    break;
    
    
    
    case 'CALCULAR-PROYECCION':
	  echo  $error=0;
		
		 $sql = "SELECT epc.Porcentaje, epc.CodProyeccion, epc.Desde, epc.Hasta, epc.CodConcepto 
         FROM py_conceptoporcentaje AS epc WHERE  CodProyeccion='$ftproyeccion'";
         
 $query_porcentajes = mysql_query($sql) or die ($sql.mysql_error());
 $rows_porcentajes = mysql_num_rows($query_porcentajes);
 $porcentajes = array();

for ($i=0; $i<$rows_porcentajes;$i++) {
	
	$field_procentaje = mysql_fetch_array($query_porcentajes);
	$porcentajes[$field_procentaje['CodConcepto']]['CodProyeccion']=$field_procentaje['CodProyeccion'];
	$porcentajes[$field_procentaje['CodConcepto']]['Porcentaje']=$field_procentaje['Porcentaje']/100;
	$porcentajes[$field_procentaje['CodConcepto']]['Porcentajetx']="+".$field_procentaje['Porcentaje'];
	$porcentajes[$field_procentaje['CodConcepto']]['Desde']=$field_procentaje['Desde'];
	$porcentajes[$field_procentaje['CodConcepto']]['Hasta']=$field_procentaje['Hasta'];
	$porcentajes[$field_procentaje['CodConcepto']]['CodConcepto']=$field_procentaje['CodConcepto'];
 }
       
      
 // obtengo toda la nomina   CodProceso- CodConcepto - CodPersona - Monto - MontoP    
   $sql = "  SELECT
				py_empleadoprocesoconcepto.CodConcepto,
				py_empleadoprocesoconcepto.Monto,
				py_empleadoprocesoconcepto.MontoP,
				py_empleadoprocesoconcepto.CodPersona,
				mastpersonas.NomCompleto,
				py_proceso.CodProceso,
				CONCAT(py_proceso.Anio,'-',py_proceso.Periodo) as periodo
				FROM
				py_empleadoprocesoconcepto
				INNER JOIN py_proceso ON py_proceso.CodProceso = py_empleadoprocesoconcepto.CodProceso
				INNER JOIN mastpersonas ON mastpersonas.CodPersona = py_empleadoprocesoconcepto.CodPersona";
       
	$query_nominas = mysql_query($sql) or die ($sql.mysql_error());
	$rows_nominas = mysql_num_rows($query_nominas);
	$nominas = array();

     while ($field_nomina = mysql_fetch_array($query_nominas)) {
	
	$desde=0;
	$hasta=0;
	$periodo=0;
//	$field_nomina = mysql_fetch_array($query_nominas);
	
//	echo "<br>".$field_nomina['CodConcepto'];
	
	if (  (!strcmp ($field_nomina['CodConcepto'] ,$porcentajes[$field_nomina['CodConcepto']]['CodConcepto'])) )
	{

      
				$desde=$porcentajes[$field_nomina['CodConcepto']]['Desde'] ; 
				$hasta=$porcentajes[$field_nomina['CodConcepto']]['Hasta'] ;
				$periodo=$field_nomina['periodo'] ; 
				$porcentaje=$porcentajes[$field_nomina['CodConcepto']]['Porcentaje'] ; 
				  
				$inicio = strtotime($desde."-01");$fin = strtotime($hasta."-01"); $evaluado = strtotime($periodo."-01");
				if (($evaluado >= $inicio) && ($evaluado <= $fin)) // echo "<br> dentro del rango";
			   // else  echo "<br> fuera del rango";
				 //echo "<br> UPDATE ";
						{
						  $monto = $field_nomina['Monto'] + $field_nomina['Monto'] *  $porcentaje;
						  $sql= "UPDATE `py_empleadoprocesoconcepto` SET `MontoP`='".$monto."' WHERE (`CodProceso`='".$field_nomina['CodProceso']."') AND (`CodPersona`='".$field_nomina['CodPersona']."') AND (`CodConcepto`='".$field_nomina['CodConcepto']."')";
						 //echo "<br>". $sql;
						  $result = mysql_query($sql) or die ($sql.mysql_error());
						 }
						 
   
     }  else  
     
      {
		  $sql= "UPDATE `py_empleadoprocesoconcepto` SET `MontoP`='".$field_nomina['Monto']."' WHERE (`CodProceso`='".$field_nomina['CodProceso']."') AND (`CodPersona`='".$field_nomina['CodPersona']."') AND (`CodConcepto`='".$field_nomina['CodConcepto']."')";
          //echo "<br>". $sql;
          $result = mysql_query($sql) or die ($sql.mysql_error());
       }
    }  
	break;

	

}


?>
