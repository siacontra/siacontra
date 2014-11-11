<?
include('acceso_db.php');
switch( $_POST['opcion'] ) {
		case "Editar":
	$mm=mysql_query("SELECT * FROM usuarios where usuario_id='".$usuario_id."'");
	  			$m=mysql_fetch_assoc($mm);
	  				$usuario_id=$m[usuario_id];
	  				$usuario_nom=$m[usuario_nombre];					
   	echo"<script>window.location='pantallamodifiusu.php?usuario_id=$usuario_id&usuario_nom=$usuario_nom'; </script>";
break;
case "Borrar": 
	$mm=mysql_query("SELECT * FROM  usuarios where usuario_id='".$usuario_id."'");
	if ($usuario_nombre="Mandre07"){
		echo"<script>
   						history.back();
    					alert('Usuario Administrador no se puede eliminar');
	 				</script>";
	}else{
		$sql1 = mysql_query("delete from usuarios where usuario_id='".$usuario_id."'") or die("Error: ".mysql_error());
		echo"
	 					<script>
							history.back();
	 					</script>
 			";
	}
break;

}
?>