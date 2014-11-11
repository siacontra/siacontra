<?php
    session_start();		
    include('acceso_db.php');
	include("../funciones/fechas.php");
	$fechar=$_REQUEST[fecha];
	$fecha=explota($fechar);
        mysql_query ("SET NAMES 'utf8'");
	$reg = mysql_query("INSERT INTO entrada (id_ent, fecha, hora, cod_veh, placa, modelo, kilometraje, observaciones) values 	(NULL,'$fecha','$hora','$cod_veh','$placa','$modelo','$kilometraje','$observaciones')"); 
	$r = mysql_query("INSERT INTO kmaceitebuji (cod_veh, placa, fecha, kilometraje) values (NULL,'$cod_veh','$placa','$fechas','$kilometraje')"); 

	
                if($reg) { 
                     echo"
 					<script>
						alert('Los Datos se Registraron con Exito');
  						window.location='../paginas/entradavehiculoplaca.php';
    					
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
          				
?> 

