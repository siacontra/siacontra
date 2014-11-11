<?php

    include('acceso_db.php');
	include('funcfechas.php');
	$fechac=$_REQUEST[fecha];
	
	$fechab=implota($fechac);
 	
	    mysql_query ("SET NAMES 'utf8'");  				
            $sql = mysql_query("SELECT id_mant FROM mantenimiento WHERE id_mant='".$id_mant."'"); 
            if(mysql_num_rows($sql) > 0) { 
                echo" 
 					<script>
  						 history.back();
    					alert('Este Automovil se ha registrado con anterioridad');
	 				</script>
				 ";
            }else { 
		mysql_query ("SET NAMES 'utf8'");
                
                $reg = mysql_query("INSERT INTO mantenimiento (id_mant, fecha, cod_veh, placa, modelo, ano, serialmotor, serialcarro, nrocarroceria, marca, dependencia, amotor, ahidraulico, n_condicion, n_presion, cambiobu, lavadog, cambiofreno, grafito, alineabalan, filtroaceite, filtroaire, filtrogasolina, otros, especificar, personal, proveedor, observaciones) values ('$id_mant','$fechab','$cod_veh','$placa','$modelo','$ano','$serialmotor','$serialcarro','$nrocarroceria','$marca','$dependencia','$amotor','$ahidraulico','$n_condicion','$n_presion','$cambiobu','$lavadog','$cambiofreno','$grafito','$alineabalan','$filtroaceite','$filtroaire','$filtrogasolina','$otros','$especificar','$personal','$proveedor','$observaciones')"); 
                if($reg) { 
				 include('acceso_db.php');
				 $result = mysql_query("select MAX(id_mant) from mantenimiento");
				 $resultado = mysql_result ($result,0);
				 $id_mant = $resultado; 
                     echo"
 					<script>
  						window.location='../paginas/imprimirmantenimiento.php?id_mant=$id_mant';
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