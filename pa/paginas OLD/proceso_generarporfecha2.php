<?
include('acceso_db.php');
$desde=$_REQUEST['desde'];
$hasta=$_REQUEST['hasta'];
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
   						//window.location='funcionariospermisomedico.php?desde=$desde&hasta=$hasta';
   						window.open('funcionariospermisomedico.php?desde=$desde&hasta=$hasta','toolbar=no,location=no,resizable=no,height=200' );
   	 				</script>
 					";
	}
?>
