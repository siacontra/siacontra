<?php
    session_start();
    include('acceso_db.php');
	include("../funciones/fechas.php");
	$fechar=$_REQUEST[fecha];
	$fecha=explota($fechar);
		mysql_query ("SET NAMES 'utf8'");		
                $reg = mysql_query("INSERT INTO tallerentrada(id_talleren, fecha, hora, cod_veh, placa, modelo, kilometraje, nombretaller, observaciones, motivo, seguro, institucion) values (NULL,'$fecha','$hora','$cod_veh','$placa','$modelo','$kilometraje','$nombretaller','$observaciones','$motivo','$seguro','$institucion')"); 
                if($reg) { 
                     echo"
 					<script>
						alert('Los Datos se Registraron con Exito');
  						window.location='../paginas/entradavehiculoplacataller.php';
    					
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