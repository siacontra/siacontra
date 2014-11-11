<?php

include("../fphp.php");
connect();

switch ($accion) {
	
	case 'GUARDAR':
	    $error=0;
		//	Guardar registro...
		$sql = "INSERT INTO rh_medicoshcm 
							(
								nombremedico,
								telefono
							)
							VALUES (
							
							'".$nombremedico."',
							'".$telefono."' 
							)";
		$query = mysql_query($sql) or die ($sql.mysql_error());
        

	break;
	
	case 'ELIMINAR':
	    $error=0;
		//	Eliminar registro....
		$sql = "DELETE FROM rh_medicoshcm WHERE idMedHcm = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
        

	break;
	
	case 'ACTUALIZAR':
	    $error=0;
	
			//	Modificar registro...
			$sql = "UPDATE rh_medicoshcm SET
			                                   nombremedico = '".$nombremedico."', 
												telefono    = '".$telefono."'
												 
											WHERE 
												 	idMedHcm = '".$codigo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		
	break;
	

	

}


?>
