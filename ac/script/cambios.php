<?
$dbhost="localhost";  // host del MySQL (generalmente localhost)
$dbusuario=$_SESSION["MYSQL_USER"]; // ingresar el nombre de usuario para acceder a la base
$dbpassword=$_SESSION["MYSQL_USER"]; // password de acceso para el usuario
$db="siaceda";        // Seleccionamos la base con la cual trabajar
$conexion = mysql_connect($dbhost, $dbusuario, $dbpassword);
mysql_select_db($db, $conexion);

$sql = "select * from af_clasificacionactivo20";
$qry = mysql_query($sql) or die ($sql.mysql_error());
$row = mysql_num_rows($qry);
for($i=0; $i<=$row;$i++){
 $field = mysql_fetch_array($qry);

 $codigo = '0'.''.$field['CodClasificacion'];

 $s_update = "update 
                    af_clasificacionactivo20 
			    set 
				    CodClasificacion = '$codigo' 
				where 
				    CodClasificacion = '".$field['CodClasificacion']."'";
 $q_update = mysql_query($s_update) or die ($s_update.mysql_error());
}
?>