<?php

    include('acceso_db.php');
	include('funcfechas.php');
	$fechac=$_REQUEST['fecha'];
	
	$fechab=implota($fechac);
 	
	    mysql_query ("SET NAMES 'utf8'");  				
           $sql = mysql_query("SELECT id_mant FROM mantenimiento WHERE id_mant='".$_REQUEST['id_mant']."'"); 
            if(mysql_num_rows($sql) > 0) { 
                echo" 
 					<script>
  						 history.back();
    					alert('Este Automovil se ha registrado con anterioridad');
	 				</script>
				 ";
            }else { 
		mysql_query ("SET NAMES 'utf8'");
                
                $reg = mysql_query("INSERT INTO mantenimiento (id_mant, fecha, cod_veh, placa, modelo, ano, serialmotor, serialcarro, nrocarroceria, marca, dependencia, amotor, ahidraulico, n_condicion, n_presion, cambiobu, lavadog, cambiofreno, grafito, alineabalan, filtroaceite, filtroaire, filtrogasolina, otros, especificar, personal, proveedor, observaciones) values ('".$_REQUEST['id_mant']."','".$fechab."','".$_REQUEST['cod_veh']."','".$_REQUEST['placa']."','".$_REQUEST['modelo']."','".$_REQUEST['ano']."','".$_REQUEST['serialmotor']."','".$_REQUEST['serialcarro']."','".$_REQUEST['nrocarroceria']."','".$_REQUEST['marca']."','".$_REQUEST['dependencia']."','".$_REQUEST['amotor']."','".$_REQUEST['ahidraulico']."','".$_REQUEST['n_condicion']."','".$_REQUEST['n_presion']."','".$_REQUEST['cambiobu']."','".$_REQUEST['lavadog']."','".$_REQUEST['cambiofreno']."','".$_REQUEST['grafito']."','".$_REQUEST['alineabalan']."','".$_REQUEST['filtroaceite']."','".$_REQUEST['filtroaire']."','".$_REQUEST['filtrogasolina']."','".$_REQUEST['otros']."','".$_REQUEST['especificar']."','".$_REQUEST['personal']."','".$_REQUEST['proveedor']."','".$_REQUEST['observaciones']."')"); 
                if($reg) { 
				 include('acceso_db.php');
				 $result = mysql_query("select MAX(id_mant) from mantenimiento");
				 $resultado = mysql_result ($result,0);
				 $id_mant = $resultado; 
                     echo"
 					<script>
 					alert('Los Datos se Registraron con Exito'); 
  						window.location='../paginas/imprimirmantenimiento.php?id_mant=".$_REQUEST['id_mant']."';
    					
						
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
