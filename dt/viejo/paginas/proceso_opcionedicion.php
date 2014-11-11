<?
	include('acceso_db.php');
		switch( $_POST['opcion'] ) {
 			case "Editar":
				$mm=mysql_query("select * from personal where cedula='".$cedula."'");
				$m= mysql_fetch_array($mm);
				$cedula=$m[cedula];
				$nombres=$m[nombres];
				$apellidos=$m[apellidos];
				$dependencia=$m[dependencia];
				$cargo=$m[cargo];
				$telefono=$m[telefono];
	 			echo"
					<script>					window.location='modificarpersonal.php?cedula=$cedula&nombres=$nombres&apellidos=$apellidos&dependencia=$dependencia&cargo=$cargo&telefono=$telefono'; 
					</script>";
			break;		
			case "Borrar";
				$mm=mysql_query("select * from personal where cedula='".$cedula."'");
				$m= mysql_fetch_array($mm);
				$cedula=$m[cedula];
				if ($sql1=mysql_query("delete from personal where cedula='$cedula'") or die("Error: ".mysql_error())){
				echo"
	 				<script>
	 					window.location='principal.php';
						alert('El Funcionario ha sido eliminado!');
	 				</script>";	}
				break;
	}	
?>


