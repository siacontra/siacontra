<?php
	session_start();
	include('acceso_db.php');
//    			echo $placa;
// 	   		exit;
			mysql_query ("SET NAMES 'utf8'");
 			$sql = "update automovil set  
					 cod_veh = '".$cod_veh."', placa = '".$placa."', modelo = '".$modelo."',
					 ano = '".$ano."', basico = '".$basico."', color = '".$color."',
					 serialmotor = '".$serialmotor."', serialcarro = '".$serialcarro."',
					 nrocarroceria = '".$nrocarroceria."', marca = '".$marca."', dependencia = '".$dependencia."'
                where
				     placa ='".$placa."'";
   			$result = mysql_query($sql); 
	if($result) { 			
 		echo"
 					 <script>
					alert('Se realizo la Modificacion con Exito!');
    					window.location='../paginas/editarautomovil.php';
     					
					</script>
 	";
 	}else{
 		echo"
 					 <script>
					alert('NO se realizo la Modificacion!');
     					history.back();    					
 					</script>
 	";
 		}
?>
