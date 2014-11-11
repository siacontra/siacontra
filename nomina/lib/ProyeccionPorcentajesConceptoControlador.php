<?php

include("../fphp.php");
connect();

switch ($accion) {
	
	case 'GUARDAR':
	    $error=0;

		//	Guardar registro...
	
		$sql="		
		  INSERT INTO
		   `py_conceptoporcentaje` (
		`CodConcepto`,
		`Porcentaje`,
		`CodProyeccion`,
		`Desde`,
		`Hasta`
		)
		VALUES
		(
		'".$tx_codigo."',
		'".$tx_porcentaje."',
		'".$CodProyeccion."',
		'".$tx_hasta."',
		'".$tx_desde."'
		)";
	//	$sql="	INSERT INTO `py_conceptoporcentaje` (`CodConcepto`, `Porcentaje`, `CodProyeccion`, `Desde`, `Hasta`) VALUES ('0001', '20', '02', '2014-05', '2014-12')";
	
		$query = mysql_query($sql) or die ($sql.mysql_error());
        

	break;
	
	case 'ELIMINAR':
	    $error=0;
		//	Eliminar registro....
		
		//$sql = "DELETE FROM py_proceso WHERE CodProceso = '".$tx_codigo."'";
		$sql = " DELETE FROM `py_conceptoporcentaje` WHERE (`CodConcepto`='".$tx_codigo."') AND  (`CodProyeccion`='".$CodProyeccion."')";
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

	

}


?>
