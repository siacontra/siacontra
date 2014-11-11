<?
include('acceso_db.php');
$desde=$_REQUEST['desde'];
$hasta=$_REQUEST['hasta'];
if ($desde>$hasta){
	echo"
 					<script>
						alert('La Fecha DESDE es mayor que HASTA');
   						window.location='pantallaporfechasalidatallervehiculos.php';
    					
	 				</script>
 					";
	
	}else{
		
		echo"
 					<script>
   						//window.location='salidavehiculostallerxfecha.php?desde=$desde&hasta=$hasta';
   						window.open('salidavehiculostallerxfecha.php?desde=$desde&hasta=$hasta','toolbar=no,location=no,resizable=no,height=200' );
   	 				</script>
 					";
	}
?>
