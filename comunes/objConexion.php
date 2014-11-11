<?php
	/*$host = "192.168.1.9";
	$usuario = "siaces";
	$clave = "s1m0nd14z";
	$baseDatos = "siaces";
	$puerto = "3306";*/

    $host = "localhost";
	$usuario = "root";
	$clave = '123';
	$baseDatos = "siacem01";
	$puerto = "3306";


	$objConexion = new MySQL($host,$usuario,$clave,$baseDatos,$puerto);
	



	if(!$objConexion)
	{	
		//echo "fallo";
		exit;
	}	


?>
