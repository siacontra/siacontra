<?
if ($placa=="0"){
					echo"
 					<script>
   						 history.back();
    					alert('Seleccione la Placa');
	 				</script>
 					";
					}else{ 
		//Termina la Validacion
$placa=$_REQUEST[placa];
include('acceso_db.php');
mysql_query ("SET NAMES 'utf8'");  
if($mm=mysql_query("select * from automovil where placa='".$placa."'")){
	$m= mysql_fetch_array($mm);
	$modelo=$m[modelo];
	$cod_veh=$m[cod_veh];
	$ano=$m[ano];
	$serialmotor=$m[serialmotor];
	$serialcarro=$m[serialcarro];
	$nrocarroceria=$m[nrocarroceria];
	$marca=$m[marca];
	$dependencia=$m[dependencia];
	

echo"
		<script>
			window.location='mantenimientopreventivo.php?placa=$placa&modelo=$modelo&cod_veh=$cod_veh&ano=$ano&serialmotor=$serialmotor&serialcarro=$serialcarro&nrocarroceria=$nrocarroceria&marca=$marca&dependencia=$dependencia'; 
		</script>";
}
					}
?>
		