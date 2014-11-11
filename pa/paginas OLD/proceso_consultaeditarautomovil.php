<?
include('acceso_db.php');
if($mm=mysql_query("select * from personal where cedula='".$placa."'")){
	$m= mysql_fetch_array($mm);
	$placa=$m[placa];
	$modelo=$m[modelo];
	$ano=$m[ano];
	$basico=$m[basico];
	$color=$m[color];
	$serialmotor=$m[serialmotor];
	$serialcarro=$m[serialcarro];
	
	if ($nombres=="") {
	echo" <script>
				window.location='modificarautomovil.php';
				alert('El automovil No Existe.');
			</script>";
	}else echo"
		<script>
			window.location='Editarpersonal.php?cedula=$cedula&nombres=$nombres&apellidos=$apellidos&dependencia=$dependencia&cargo=$cargo&telefono=$telefono'; 
		</script>";

			}
?>
	