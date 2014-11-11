<?php
session_start();

include_once ("../clases/MySQL.php");
include_once("objConexion.php");

 /*
$birth = new DateTime('2014-02-19 09:30:00');
$today = new DateTime();
$diff = $birth->diff($today);

echo "<br>".$diff->format('%y %h %i %s');

echo " COMPARACION: ";

if ($diff->format('%i') > 5) echo "ES MAYOR QUE 5";*/

/*
// consulta
$session_array  = $objConexion->compararSesion($_SESSION["CADENA_USUARIO"]);

//var auxiales
$session_usuario = $session_array ["Usuario"];
$session_ip = $session_array ["IP"];
$session_hostname = $session_array ["HOSTNAME"];
$session_ultimasesion = $session_array ["UltimaSesion"];


// comparar tiempo
 if( $objConexion->compareTime($session_ultimasesion, new DateTime())) echo "SU SESION DEBE CERRAR";

if ( strcmp ( $_SERVER['REMOTE_ADDR'] , $session_ip) ==0 )  {
	echo "esta en el mismo hots";
	} else
	{
		echo "Se cerrara su sesion";
	}
*/	
	//	$_SESSION["IP"]= '192.168.0.1w3';
	//$_SESSION["CADENA_USUARIO"]= 'CMARCANO';
	//echo "asdasdasddad;
//	echo  $objConexion->compareIP();
	 if (!$objConexion->compareIP()) {
			 $_SESSION = array();
		
             //echo "sdadd";
			if (session_destroy())
			{
			        
				echo $objConexion->devolverXML(array(array("resultadoCerrarSesion"=>"1")));
			       
			        
			} else {
			        
				echo $objConexion->devolverXML(array(array("resultadoCerrarSesion"=>"0")));
			}
     }// if compare
	
?>
