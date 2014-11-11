<?
include('acceso_db.php');
if ($desde>$hasta){
	echo"
 					<script>
						alert('La Fecha DESDE es mayor que HASTA');
   						window.location='pantallaporfechaentradavehiculos.php';
    					
	 				</script>
 					";
	
	}else{
		
		echo"
 					<script>
   						window.location='entradavehiculosxfecha.php?desde=$desde&hasta=$hasta';
   	 				</script>
 					";
	}
?>