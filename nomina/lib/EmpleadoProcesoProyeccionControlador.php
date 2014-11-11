<?php

include("../fphp.php");
connect();

switch ($accion) {

    case 'ELIMINAR':
    
	    $sql="DELETE FROM py_empleadoprocesoconcepto WHERE (CodProceso='".$codproceso."') AND (CodPersona='".$codpersona."') AND (CodConcepto='".$elemento."')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		//echo $error;
		
    break;
    
     case 'EDITAR':
    
	    list($codconcepto, $secuencia)=SPLIT( '[-.-]', $elemento);
		//	------------------------
	     $sql="SELECT
				py_epc.CodPersona,
				py_epc.CodConcepto,
				py_epc.Monto,
				py_epc.MontoP,
				py_epc.Cantidad,
				py_epc.Saldo,
				pr_concepto.Descripcion,
				py_epc.CodTipoProceso,
				py_epc.CodProyeccion,
				py_epc.CodTipoNom
				FROM
								py_empleadoprocesoconcepto AS py_epc
								INNER JOIN pr_concepto ON pr_concepto.CodConcepto = py_epc.CodConcepto

                WHERE
				py_epc.CodTipoProceso = '".$codproceso."' AND
				py_epc.CodPersona = '".$codpersona."' AND
				py_epc.CodConcepto= '".$codconcepto."'";
				
		$query=mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query)!=0) {
			$field=mysql_fetch_array($query);
			$monto=number_format($field['Monto'], 2, ',', '');
			$cantidad=number_format($field['Cantidad'], 0, ',', '.');
			echo "0|:|".$field['CodConcepto']."|:|".$field['Descripcion']."|:|".$monto."|:|".$cantidad."|:|".$field['CodProceso'];
		}
		
    break;
    case 'INSERTAR':

      
       // $sql="SELECT * FROM pr_empleadoconcepto WHERE CodPersona='".$codpersona."' AND CodConcepto='".$codconcepto."' AND PeriodoDesde='".$pdesde."' AND PeriodoHasta='".$phasta."'";
      $sql="SELECT
				py_epc.CodTipoProceso,
				py_epc.CodTipoNom,
				py_epc.Periodo,
				py_epc.CodProyeccion,
				
				py_epc.CodPersona,
				py_epc.CodConcepto,
				py_epc.Monto,
				py_epc.MontoP,
				py_epc.Cantidad,
				py_epc.Saldo
				FROM
				py_empleadoprocesoconcepto AS py_epc

				WHERE

				py_epc.CodProyeccion = '".$codproyeccion."' AND
				py_epc.Periodo = '".$codperiodo."' AND
				py_epc.CodTipoNom = '".$codnomina."' AND
				py_epc.CodTipoProceso = '".$codproceso."' AND
				
				py_epc.CodPersona = '".$codpersona."' AND
				py_epc.CodConcepto= '".$codconcepto."'";
			
        $query=mysql_query($sql) or die ($sql.mysql_error());
        if (mysql_num_rows($query)!=0) $error="CONCEPTO YA INGRESADO PARA ESTE EMPLEADO";
        else {
			
			  $sql = "INSERT INTO `py_empleadoprocesoconcepto`
			   (`CodTipoProceso`, `CodPersona`, `CodConcepto`, `Monto`, `MontoP`, `Cantidad`, CodProyeccion , CodTipoNom , Periodo ) 
			   VALUES 
			   ('".$codproceso."','".$codpersona."', '".$codconcepto."','".$monto."', '1', '".$cantidad."', '".$codproyeccion."', '".$codnomina."', '".$codperiodo."')";
               $query=mysql_query($sql) or die ($sql.mysql_error());
        }
        echo $error;
        
     
       
      
    break;
    
    
    case 'ACTUALIZAR':


			  $sql = "
			  UPDATE 
			  py_empleadoprocesoconcepto 
			  SET Monto='".$monto."', Cantidad='".$cantidad."' 
			  WHERE   
			  CodProceso=  '".$codproceso."' AND  
			  CodPersona=  '".$codpersona."' AND  
			  CodConcepto=  '".$codconcepto."'";

               $query=mysql_query($sql) or die ($sql.mysql_error());
       
        echo $error;
        
     
       
      
    break;



}


?>
