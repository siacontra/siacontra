<?
include('acceso_db.php');
switch( $_POST['opcion'] ) {
		case "Editar":
	mysql_query ("SET NAMES 'utf8'");
	$mm=mysql_query("SELECT * FROM automovil where placa='".$placa."'");
	  			$m=mysql_fetch_assoc($mm);
					$cod_veh=$m[cod_veh];
	  				$placa=$m[placa];
					$modelo=$m[modelo];
					$ano=$m[ano];
					$basico=$m[basico];	
					$color=$m[color];
					$serialmotor=$m[serialmotor];	
					$serialcarro=$m[serialcarro];
					$nrocarroceria=$m[nrocarroceria];	
					$marca=$m[marca];
					$dependencia=$m[dependencia];
				
   	echo"<script>	window.location='modificarautomovil.php?&cod_veh=$cod_veh&placa=$placa&modelo=$modelo&ano=$ano&basico=$basico&color=$color&serialmotor=$serialmotor&serialcarro=$serialcarro&nrocarroceria=$nrocarroceria&marca=$marca&dependencia=$dependencia'; </script>";
break;
case "Borrar": 
        mysql_query ("SET NAMES 'utf8'");
	$mm=mysql_query("SELECT * FROM automovil where placa='".$placa."'");
		$sql1 = mysql_query("delete from automovil where placa='$placa'") or die("Error: ".mysql_error());
		echo"
	 					<script>
							window.location='editarautomovil.php'; 
	 					</script>
 			";
	
break;

}
?>