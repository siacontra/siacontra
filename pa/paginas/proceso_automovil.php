<?php
if ($ano=="0"){
					echo"
 					<script>
   						 history.back();
    					alert('Seleccione la fecha que corresponde el Automóvil');
	 				</script>
 					";
					}else
					
    session_start();
	
		//Termina la Validacion
    include('acceso_db.php');
	    mysql_query ("SET NAMES 'utf8'");
            $sql = mysql_query("SELECT placa FROM automovil WHERE placa='".$placa."'"); 
            if(mysql_num_rows($sql) > 0) { 
                echo"
 					<script>
 						alert('Este Automovil se ha registrado con anterioridad');
  						 history.back();
    					
	 				</script>
				 ";
            }else { 
		mysql_query ("SET NAMES 'utf8'");		
                $reg = mysql_query("INSERT INTO automovil (cod_veh, placa, modelo, ano, basico, color, serialmotor, serialcarro, nrocarroceria, marca, dependencia) values ('$cod_veh','$placa','$modelo','$ano','$basico','$color','$serialmotor','$serialcarro','$nrocarroceria','$marca','$dependencia')"); 
                //echo $reg;
		if($reg) { 
                     echo"
 					<script>
						alert('Los Datos se Registraron con Exito');
  						window.location='../paginas/registroautomovil.php';
    					
	 				</script>
				 ";
                }else { 
                     echo"
 					<script>
						alert('Ocurrio un Error en el Proceso');
  						 history.back();
    					
	 				</script>
				 ";
                } 
            } 
?> 