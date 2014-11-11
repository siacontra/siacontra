<?
	include('acceso_db.php');
			$usuario_clave = md5($_REQUEST= $usuario_clave); 
   			if ($sql = "SELECT * FROM usuarios WHERE usuario_id = $usuario_id" ){
   			$result = mysql_query($sql);
   			$sql = "UPDATE usuarios SET usuario_clave='$usuario_clave' WHERE usuario_id='$usuario_id'";
   			$result = mysql_query($sql); 
			
		echo"
					 <script>
    					history.back();
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
