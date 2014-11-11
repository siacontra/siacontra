<?php
    session_start();		
    include('acceso_db.php');
	include("../funciones/fechas.php");
	$fechar=$_REQUEST['fecha'];
	$fecha=explota($fechar);
        mysql_query ("SET NAMES 'utf8'");
	$reg = mysql_query("INSERT INTO entrada (id_ent, fecha, hora, cod_veh, placa, modelo, kilometraje, observaciones) values 	('','".$fecha."','".$_REQUEST['hora']."','".$_REQUEST['cod_veh']."','".$_REQUEST['placa']."','".$_REQUEST['modelo']."','".$_REQUEST['kilometraje']."','".$_REQUEST['observaciones']."')"); 
	$r = mysql_query("INSERT INTO kmaceitebuji (cod_veh, placa, fecha, kilometraje) values ('".$_REQUEST['cod_veh']."','".$_REQUEST['placa']."','".$fecha."','".$_REQUEST['kilometraje']."')"); 

	
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

