<?
	include('acceso_db.php');
	$sql = mysql_query("SELECT nomdependencia FROM dependencia WHERE nomdependencia='".$nomdependencia."'"); 
    if(mysql_num_rows($sql) > 0) { 
                echo"
 					<script>
   						window.location='principal.php';
    					alert('La Dependencia ha sido Registrada anteriormente');
	 				</script>
 					";
    	}else { 
				 
				if(mysql_query("INSERT INTO dependencia (id, nomdependencia) VALUES (NULL,'".$nomdependencia."')")){
   			echo"
 					<script>
   						window.location='principal.php';
    					alert('El Registro se ha completado con Exito');
	 				</script>
 					";}
				else echo"
					 <script>
    					window.location='Registropermiso.php'; 
    					alert('Error en el proceso!');
					</script>
	
				";
		}
?>