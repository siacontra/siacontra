<?
        //Validacion
if ($placa=="0"){
					echo"
 					<script>
   						 history.back();
    					alert('Seleccione la Placa');
	 				</script>
 					";
					}else{ 
		//Termina la Validacion
$placa=$_REQUEST['placa'];
include('acceso_db.php');
mysql_query ("SET NAMES 'utf8'");
if($mm=mysql_query("select * from automovil where placa='".$placa."'")){
	$m= mysql_fetch_array($mm);
	$modelo=$m[modelo];
	$cod_veh=$m[cod_veh];

echo"
		<script>
			window.location='entradavehiculotaller.php?placa=$placa&cod_veh=$cod_veh&modelo=$modelo'; 
		</script>";
}
					}
?>
		
