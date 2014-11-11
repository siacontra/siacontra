<?
include('acceso_db.php');
if($mm=mysql_query("select * from proveedor where rif='".$rif."'")){
	$m= mysql_fetch_array($mm);
	$rif=$m[rif];
	$nombre=$m[nombre];
	$direccion=$m[direccion];
	$telefono=$m[telefono];
	
	if ($nombre=="") {
	echo" <script>
				window.location='consultaeditarproveedor.php';
				alert('El Proveedor No Existe.');
			</script>";
	}else echo"
		<script>
			window.location='Editarproveedor.php?rif=$rif&nombre=$nombre&direccion=$direccion&telefono=$telefono'; 
		</script>";

			}
?>
	