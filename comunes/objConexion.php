<?php

    $host = "localhost";
	$usuario = "root";
	$clave = '123';
	$baseDatos = "saicom";
	$puerto = "3306";


	$objConexion = new MySQL($host,$usuario,$clave,$baseDatos,$puerto);
	



	if(!$objConexion)
	{	
		//echo "fallo";
		exit;
	}	


?>
