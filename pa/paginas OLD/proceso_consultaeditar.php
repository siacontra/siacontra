<?
include('acceso_db.php');
if($mm=mysql_query("select * from personal where cedula='".$cedula."'")){
	$m= mysql_fetch_array($mm);
	$cedula=$m[cedula];
	$nombres=$m[nombres];
	$apellidos=$m[apellidos];
	$dependencia=$m[dependencia];
	$cargo=$m[cargo];
	$telefono=$m[telefono];
	
	if ($nombres=="") {
	echo" <script>
				window.location='consultaeditar.php';
				alert('El Empleado No Existe.');
			</script>";
	}else echo"
		<script>
			window.location='Editarpersonal.php?cedula=$cedula&nombres=$nombres&apellidos=$apellidos&dependencia=$dependencia&cargo=$cargo&telefono=$telefono'; 
		</script>";

			}
?>
	