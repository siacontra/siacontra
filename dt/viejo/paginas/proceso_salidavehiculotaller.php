<?php
    session_start();
	//Validacion
		
    include('acceso_db.php');
	include("../funciones/fechas.php");
		$fechar=$_REQUEST[fecha];
		$fecha=explota($fechar);
	        mysql_query ("SET NAMES 'utf8'");
                $reg = mysql_query("INSERT INTO automotor.tallersalida(id_tallersal, fecha, hora, cod_veh, placa, modelo, condiciones, kilometraje, observaciones, motivo, fechaestim) values (NULL,'$fecha','$hora','$cod_veh','$placa','$modelo','$condiciones','$kilometraje','$observaciones','$motivo','$fechaestim')"); 
                if($reg) { 
                     echo"
 					<script>
						alert('Los Datos se Registraron con Exito');
  						window.location='../paginas/salidavehiculoplacataller.php';
    					
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
