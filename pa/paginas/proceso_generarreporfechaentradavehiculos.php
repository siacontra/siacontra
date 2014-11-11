<?
include('acceso_db.php');
$desde=$_REQUEST['desde'];
$hasta=$_REQUEST['hasta'];
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
   						//window.location='entradavehiculosxfecha.php?desde=$desde&hasta=$hasta';
   						window.open('entradavehiculosxfecha.php?desde=$desde&hasta=$hasta','toolbar=no,location=no,resizable=no,height=200' );
   	 				</script>
 					";
	}
?>
