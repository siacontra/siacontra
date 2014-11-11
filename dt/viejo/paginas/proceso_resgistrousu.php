<?
include('acceso_db.php');
$sql = mysql_query("SELECT usuario_nombre FROM automotor.usuarios WHERE usuario_nombre='".$usuario_nombre."'"); 
            if(mysql_num_rows($sql) > 0) { 
                echo "
				<script>
   						window.location='registro.php';
    					alert('El nombre del Usuario ya ha sido elegido');
	 				</script>"; 
            }else { 
				$usuario_clave = md5($_REQUEST= $usuario_clave); // encriptamos la contraseña ingresada con md5 
				// ingresamos los datos a la BD 
				if(mysql_query("INSERT INTO usuarios (usuario_nombre, usuario_clave, usuario_freg) VALUES ('".$usuario_nombre."', '".$usuario_clave."', NOW())")){
   				echo"
 					<script>
   						window.location='principal.php';
    					alert('El Registro se ha completado con Exito');
	 				</script>
 					";}
				else echo"
					 <script>
    					window.location='registro.php'; 
    					alert('Error en el proceso!');
					</script>
	
				";
			}
?>
