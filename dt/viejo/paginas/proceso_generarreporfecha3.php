<?
include('acceso_db.php');
if ($desde>$hasta){
	echo"
 					<script>
   						window.location='pantallaporfecha.php';
    					alert('La Fecha DESDE es mayor que HASTA');
	 				</script>
 					";
	
	}else{
		
		echo"
 					<script>
   						window.location='funcionariospermisopersonal.php?desde=$desde&hasta=$hasta';
   	 				</script>
 					";
	}
?>