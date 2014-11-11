<?php

include("../fphp.php");
connect();

switch ($accion) {
	
	case 'GUARDAR':
	    $error=0;
		//	Guardar registro...
		$sql = "INSERT INTO rh_institucionhcm 
							(
								descripcioninsthcm,
								direccion,
								telefonos
							)
							VALUES (
							
							'".$descripcioninsthcm."',
							'".$direccion."',
							'".$telefonos."' 
							)";
		$query = mysql_query($sql) or die ($sql.mysql_error());
        

	break;
	
	case 'ELIMINAR':
	    $error=0;
		//	Eliminar registro....
		$sql = "DELETE FROM rh_institucionhcm WHERE idInstHcm = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
        

	break;
	
	case 'ACTUALIZAR':
	    $error=0;
	
			//	Modificar registro...
			$sql = "UPDATE rh_institucionhcm SET
											descripcioninsthcm = '".$descripcioninsthcm."',
											direccion = '".$direccion."', 
											telefonos    = '".$telefonos."'
												 
											WHERE 
												 	idInstHcm = '".$codigo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		
	break;
	

	

}


?>
