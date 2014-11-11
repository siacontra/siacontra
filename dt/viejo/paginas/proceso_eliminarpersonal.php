<?

include('acceso_db.php');
$mm=mysql_query("select * from personal where cedula='".$cedula1."'");
$m= mysql_fetch_array($mm);
$cedula=$m[cedula];
$sql1=mysql_query("delete from personal where cedula='$cedula'") or die("Error: ".mysql_error());
		echo"
	 					<script>
							window.location='principal.php'; 
							alert('El Funcionario de Cedula: $cedula ha sido eliminado de la Base de Datos !');
	 					</script>
 			";
?>