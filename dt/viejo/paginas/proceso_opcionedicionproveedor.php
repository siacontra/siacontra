<?
	include('acceso_db.php');
		switch( $_POST['opcion'] ) {
 			case "Editar":
				$mm=mysql_query("select * from automotor.proveedor where rif='".$rif."'");
				$m= mysql_fetch_array($mm);
				$rif=$m[rif];
				$nombre=$m[nombre];
				$direccion=$m[direccion];
				$telefono=$m[telefono];
	 			echo"
					<script>					window.location='modificarproveedor.php?rif=$rif&nombre=$nombre&direccion=$direccion&telefono=$telefono'; 
					</script>";
			break;		
			case "Borrar";
				$mm=mysql_query("select * from proveedor where rif='".$rif."'");
				$m= mysql_fetch_array($mm);
				$rif=$m[rif];
				if ($sql1=mysql_query("delete from proveedor where rif='$rif'") or die("Error: ".mysql_error())){
				echo"
	 				<script>
	 					window.location='principal.php';
						alert('El Proveedor ha sido eliminado!');
	 				</script>";	}
				break;
	}	
?>


