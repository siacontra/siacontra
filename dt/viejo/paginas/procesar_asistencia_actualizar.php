<?php

session_start();
error_reporting(-1);

//Termina la Validacion
include('acceso_db.php');

//mysql_query ("SET NAMES 'utf8'");

$query = "
          INSERT INTO   dt_asistencia
         ( tx_status,  
           co_unidad,
           co_persona ,   
           fe_solicitud,    
           tx_asunto,    
           co_modalidad,    
           tx_observacion ) 
           
           
           values (
             
             '1',       
             '".$dependencia."',
             '".$funcionario."', 
             '".date('Y-m-d h:i:s')."' ,  
             '".$tx_asunto."',  
             '".$tx_modalidad."', 
             '".$tx_observacion."')";

        $reg = mysql_query($query) or die ($query.mysql_error());
              
              
		if($reg) { 
                     echo" <script> alert('Los Datos se Registraron con Exito');window.location='dt_asistencias_lista.php';	</script> ";
                }else { 
                     echo"<script> alert('Ocurrio un Error en el Proceso');window.location='dt_asistencia_nueva.php';</script> ";
                } 
            
?> 
