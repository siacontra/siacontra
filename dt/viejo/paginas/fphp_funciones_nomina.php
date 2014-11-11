<?php 

session_start();
error_reporting(-1);
//	--------------------------
if ($accion=="ASIGNAR") {
	

//Termina la Validacion
include('acceso_db.php');


//mysql_query ("SET NAMES 'utf8'");

$query = "
          INSERT INTO 
          
           dt_asesor 
          
               (co_asistencia      , co_asesor) 
        VALUES ('".$_CODASISTENCIA."' , '".$_CODPERSONA."')";

        $reg = mysql_query($query) or die ($query.mysql_error());
              
              
		if($reg) { 
                     echo" <script> alert('Los Datos se Registraron con Exito');window.location='dt_asistencias_lista.php';	</script> ";
                }else { 
                   echo"<script> alert('Ocurrio un Error en el Proceso');window.location='dt_asistencia_nueva.php';</script> ";
                } 
	
}


if ($accion=="ASIGNAR-FUNCIONARIO") {
	

//Termina la Validacion
include('acceso_db.php');


//mysql_query ("SET NAMES 'utf8'");

$query = "
          INSERT INTO 
          
           dt_receptores
          
               (co_asistencia      , co_receptores) 
        VALUES ('".$_CODASISTENCIA."' , '".$_CODPERSONA."')";

        $reg = mysql_query($query) or die ($query.mysql_error());
              
              
		if($reg) { 
                     echo" <script> alert('Los Datos se Registraron con Exito');window.location='dt_asistencias_lista.php';	</script> ";
                }else { 
                   echo"<script> alert('Ocurrio un Error en el Proceso');window.location='dt_asistencia_nueva.php';</script> ";
                } 
	
}
?>



