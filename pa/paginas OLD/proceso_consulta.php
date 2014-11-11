<?
include('acceso_db.php');

if($mm=mysql_query("select * from personal where cedula='".$cedula."'")){
	$m= mysql_fetch_array($mm);
	$cedula=$m[cedula];
	$nombres=$m[nombres];
	$apellidos=$m[apellidos];
	$dependencia=$m[dependencia];
	if ($nombres=="") {
	echo" <script>
				window.location='consultaci.php';
				alert('El Empleado No Existe.');
			</script>";
	}else echo"
		<script>
			window.location='personal.php?cedula=$cedula&nombres=$nombres&apellidos=$apellidos&dependencia=$dependencia'; 
		</script>";

			}
?>
		