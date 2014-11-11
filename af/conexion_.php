<?
$dbhost="localhost";  // host del MySQL (generalmente localhost)
$dbusuario=$_SESSION["MYSQL_USER"]; // ingresar el nombre de usuario para acceder a la base
$dbpassword=$_SESSION["MYSQL_USER"]; // password de acceso para el usuario
$db="siaceda";        // Seleccionamos la base con la cual trabajar
$conexion = mysql_connect($dbhost, $dbusuario, $dbpassword);
mysql_select_db($db, $conexion);
?>