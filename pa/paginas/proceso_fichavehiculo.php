<?php

    include('acceso_db.php');
	include('funcfechas.php');
	$fechac=$_REQUEST[fecha];
	
	$fechab=implota($fechac);
					
            $sql = mysql_query("SELECT id_ficha FROM fichavehiculo WHERE id_ficha='".$id_ficha."'"); 
            if(mysql_num_rows($sql) > 0) { 
                echo" 
 					<script>
  						 history.back();
    					alert('Esta ficha se ha registrado con anterioridad');
	 				</script>
				 ";
            }else { 
				
                $reg = mysql_query("INSERT INTO fichavehiculo (id_ficha, cod_veh, placa, modelo, basico, marca, color, ano, serial_motor, serial_carro, serial_carroceria, dependencia_asig) values ('$id_ficha','$cod_veh','$placa','$modelo','$basico','$marca','$color','$ano','$serial_motor','$serial_carro','$serial_carroceria','$dependencia_asig')"); 
                if($reg) { 
				 include('acceso_db.php');
				 $result = mysql_query("select MAX(id_ficha) from fichavehiculo");
				 $resultado = mysql_result ($result,0);
				 $id_mant = $resultado; 
                     echo"
 					<script>
  						window.location='../paginas/imprimirfichavehiculo.php?id_mant=$id_mant';
    					alert('Los Datos se Registraron con Exito'); 
						
	 				</script>
				 ";
				
				 						 
                }else { 
                     echo"
 					<script>
  						 history.back();
    					alert('Ocurrio un Error en el Proceso');
	 				</script>
				 ";
                } 
            } 
					
?> 