<?
	include('acceso_db.php');
   			if ($sql = "SELECT * FROM proveedor WHERE rif = $rif" ){
   			$result = mysql_query($sql);
   			$sql = "UPDATE proveedor SET nombre='$nombre', direccion='$direccion', telefono='$telefono' WHERE rif = '$rif'";
   			$result = mysql_query($sql); 
			
		echo"
					 <script>
    					window.location='principal.php'; 
    					alert('Se realizo la Modificacion con Exito!');
					</script>
	";
	}else{
		echo"
					 <script>
    					window.location='modificarproveedor.php'; 
    					alert('NO se realizo la Modificacion!');
					</script>
	";
		}
?>
