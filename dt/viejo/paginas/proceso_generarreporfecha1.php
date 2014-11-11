<?
include('acceso_db.php');
if ($desde>$hasta){
	echo"
 					<script>
						alert('La Fecha DESDE es mayor que HASTA');
   						window.location='pantallaporfecha1.php';
    					
	 				</script>
 					";
	
	}else{
		
		echo"
 					<script>
   						window.location='salidasxfecha.php?desde=$desde&hasta=$hasta';
   	 				</script>
 					";
	}
?>