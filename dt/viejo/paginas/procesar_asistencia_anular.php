<?php

session_start();


//Termina la Validacion
include('acceso_db.php');

//mysql_query ("SET NAMES 'utf8'");

$query = " UPDATE  dt_asistencia SET tx_status = 'ANULADO' WHERE (co_asistencia='".$co_asistencia."')";
         
         
        $reg = mysql_query($query) or die ($query.mysql_error());
              
              
		if($reg) { 
                     echo" <script> alert('La Solicitud fue Anulada');window.location='dt_asistencias_lista.php';	</script> ";
                }else { 
                     echo"<script> alert('Ocurrio un Error en el Proceso');window.location='dt_asistencia_nueva.php';</script> ";
                } 
            
?> 
