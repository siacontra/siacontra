<?php
if ($ci1=="0"){
					echo"
 					<script>
   						 history.back();
    					alert('Seleccione si es Venezolano o Extanjero');
	 				</script>
 					";
					}else
    session_start();
	//Validacion
		if ($dependencia=="Seleccione la Dependencia"){
					echo"
 					<script>
   						window.location='../paginas/registropersonal.php';
    					alert('Seleccione la Dependencia');
	 				</script>
 					";
					}else{ 
		//Termina la Validacion
    include('../paginas/acceso_db.php');
            $sql = mysql_query("SELECT cedula FROM personal WHERE cedula='".$cedula."'"); 
            if(mysql_num_rows($sql) > 0) { 
                echo"
 					<script>
  						 window.location='../paginas/registropersonal.php';
    					alert('Este empleado se ha registrado con anterioridad');
	 				</script>
				 ";
            }else { 
				
                $reg = mysql_query("INSERT INTO personal (cedula, dependencia, nombres, apellidos, cargo, telefono) values ('$cedula','$dependencia','$nombres','$apellidos','$cargo','$telefono')"); 
                if($reg) { 
                     echo"
 					<script>
  						 window.location='../paginas/registropersonal.php';
    					alert('Los Datos se Registraron con Exito');
	 				</script>
				 ";
                }else { 
                     echo"
 					<script>
  						 window.location='../paginas/registropersonal.php';
    					alert('Ocurrio un Error en el Proceso');
	 				</script>
				 ";
                } 
            } 
					}
?> 