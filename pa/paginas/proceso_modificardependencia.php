<?
include('acceso_db.php');
switch( $_POST['opcion'] ) {
		case "Editar":
	$mm=mysql_query("SELECT * FROM dependencia where id='".$id."'");
	  			$m=mysql_fetch_assoc($mm);
	  				$id=$m[id];
	  				$nomdependencia=$m[nomdependencia];					
   	echo"<script>window.location='modificardependencia.php?id=$id&nomdependencia=$nomdependencia'; </script>";
break;
case "Borrar": 
	$mm=mysql_query("SELECT * FROM dependencia where id='".$id."'");
		$sql1 = mysql_query("delete from dependencia where id='$id'") or die("Error: ".mysql_error());
		echo"
	 					<script>
							window.location='editardependencia.php'; 
	 					</script>
 			";
	
break;

}
?>