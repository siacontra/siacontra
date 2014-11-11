<?
	include('acceso_db.php');
   			if($mm=mysql_query("select * from dependencia where id='".$id."'")){
			$m= mysql_fetch_array($mm);
			$nombre=$m[nomdependencia];
 			$sql = "UPDATE personal SET dependencia='$nomdependencia' WHERE dependencia = '$nombre'";
   			$result = mysql_query($sql); 
			$sql = "UPDATE controlasis SET dependencia='$nomdependencia' WHERE dependencia = '$nombre'";
   			$result = mysql_query($sql);
			$sql = "UPDATE llegadastardias SET dependencia='$nomdependencia' WHERE dependencia = '$nombre'";
   			$result = mysql_query($sql);
   			$sql = "UPDATE dependencia SET nomdependencia='$nomdependencia' WHERE id = '$id'";
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
    					history.back();
    					alert('NO se realizo la Modificacion!');
					</script>
	";
		}
?>
