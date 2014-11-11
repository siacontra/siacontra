<?
include('acceso_db.php');
if ($desde>$hasta){
	echo"
 					<script>
						alert('La Fecha DESDE es mayor que HASTA');
   						window.location='pantallaporfechaentradatallervehiculos.php';
    					
	 				</script>
 					";
	
	}else{
		
		echo"
 					<script>
   						window.location='entradavehiculostallerxfecha.php?desde=$desde&hasta=$hasta';
   	 				</script>
 					";
	}
?>