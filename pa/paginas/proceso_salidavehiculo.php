<?php

    session_start();
	//Validacion
		
    include('acceso_db.php');
	include("../funciones/fechas.php");
	$fechar=$_REQUEST['fecha'];
	$fecha=explota($fechar);
	$fechaq=$_REQUEST['fechaesti'];
	$fechaesti=implota($fechaq);
		mysql_query ("SET NAMES 'utf8'");			
            $sql = mysql_query("SELECT id_salida FROM salida WHERE id_salida='".$_REQUEST['id_salida']."'"); 
            if(mysql_num_rows($sql) > 0) { 
                echo"
 					<script>
						alert('Esta Salida de Vehiculo/Automóvil se ha registrado con anterioridad');
  						 history.back();
    					
	 				</script>
				 ";
            }else { 
		mysql_query ("SET NAMES 'utf8'");		
                $reg = mysql_query("INSERT INTO salida (id_salida, fecha, cod_veh, placa, modelo, dependencia, motivo, observaciones, personal, hora, kilometraje, salidalocal, fechaesti) values ('','".$_REQUEST['fecha']."','".$_REQUEST['cod_veh']."','".$_REQUEST['placa']."','".$_REQUEST['modelo']."','".$_REQUEST['dependencia']."','".$_REQUEST['motivo']."','".$_REQUEST['observaciones']."','".$_REQUEST['personal']."','".$_REQUEST['hora']."','".$_REQUEST['kilometraje']."','".$_REQUEST['salidalocal']."','".$fechaesti."')"); 
                if($reg) { 
				
					 $result = mysql_query("select MAX(id_salida) from salida");
					 $resultado = mysql_result ($result,0);
				 	 $id_salida = $resultado; 
                     echo"
 					<script>
						alert('Los Datos se Registraron con Exito'); 
  						window.location='imprimirautorizacion.php?id_salida=".$_REQUEST['id_salida']."';
    					
	 				</script>
				 ";
                }else { 
                     echo"
 					<script>
						alert('Ocurrio un Error en el Proceso');
  						 history.back();
    					
	 				</script>
				 ";
                } 
            } 
					
?> 
