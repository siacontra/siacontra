<?php
if ($ci1=="0"){
					echo"
 					<script>
   						 history.back();
    					alert('Seleccione si es Juridico oooooo');
	 				</script>
 					";
					}else
    session_start();
	//Validacion
		if ($dependencia=="Seleccione la Dependencia"){
					echo"
 					<script>
   						window.location='../paginas/RegistroPersonal.php';
    					alert('Seleccione la Dependencia');
	 				</script>
 					";
					}else{ 
		//Termina la Validacion
    include('../paginas/acceso_db.php');
            $sql = mysql_query("SELECT rif FROM proveedor WHERE rif='".$rif."'"); 
            if(mysql_num_rows($sql) > 0) { 
                echo"
 					<script>
  						 window.location='../paginas/Registroproveedor.php';
    					alert('Este proveedor se ha registrado con anterioridad');
	 				</script>
				 ";
            }else { 
				
                $reg = mysql_query("INSERT INTO proveedor (rif, nombre, direccion, telefono) values ('$rif','$nombre','$direccion','$telefono')"); 
                if($reg) { 
                     echo"
 					<script>
  						 window.location='../paginas/Registroproveedor.php';
    					alert('Los Datos se Registraron con Exito');
	 				</script>
				 ";
                }else { 
                     echo"
 					<script>
  						 window.location='../paginas/Registroproveedor.php';
    					alert('Ocurrio un Error en el Proceso');
	 				</script>
				 ";
                } 
            } 
					}
?> 