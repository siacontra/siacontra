<?
include('acceso_db.php');

if($mm=mysql_query("select * from personal where cedula='".$cedula."'")){
	$m= mysql_fetch_array($mm);
	$cedula=$m[cedula];
	$nombres=$m[nombres];
	$apellidos=$m[apellidos];
	$dependencia=$m[dependencia];
	$cargo=$m[cargo];
	if ($nombres=="") {
	echo" <script>
				history.back();
				alert('El Empleado no Existe.');
			</script>";
	}else echo"
		<script>
			window.location='pdfporfuncionario.php?cedula=$cedula&nombres=$nombres&apellidos=$apellidos&dependencia=$dependencia&cargo=$cargo'; 
		</script>";

			}
?>