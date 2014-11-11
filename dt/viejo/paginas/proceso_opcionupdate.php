<?
	include('acceso_db.php');
   			if ($sql = "SELECT * FROM personal WHERE cedula = $cedula" ){
   			$result = mysql_query($sql);
   			$sql = "UPDATE personal SET nombres='$nombres', apellidos='$apellidos', dependencia='$dependencia', cargo='$cargo', telefono='$telefono' WHERE cedula = '$cedula'";
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
    					window.location='modificardependencia.php'; 
    					alert('NO se realizo la Modificacion!');
					</script>
	";
		}
?>
