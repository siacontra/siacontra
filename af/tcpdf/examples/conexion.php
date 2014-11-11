<?php
function conectarse()
{
	$dbhost='localhost'; 
	$dbusername= 'root'; 
	$dbuserpass= 'root'; 
	$dbname= 'bienesnacionales'; 
		$conex=mysql_connect($dbhost, $dbusername, $dbuserpass)
		or die("No se ha podido establecer conexion con la base de datos");
		mysql_select_db($dbname)
		or die("Error al tratar de seleccionar la base de datos");
		return $conex; 
	
}
	$conex=conectarse();
?>
