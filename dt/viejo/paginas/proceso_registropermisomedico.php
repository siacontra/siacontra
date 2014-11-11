<?
include('acceso_db.php');
				$fechaeditado=date("Y/m/d");
				$tipo="Medico";
				
			  $sql = mysql_query("SELECT * FROM permiso WHERE nroreposo='".$nroreposo."'"); 
            if(mysql_num_rows($sql) > 0) { 
                echo"
 					<script>
					 history.back();
    					alert('En Numero del Reposo ya Existe');
	 				</script>
				 ";
            }else{
if ($institucion=="- Seleccione -"){
					echo"
 					<script>
   						history.back();
    					alert('Seleccione el Instituto Médico');
	 				</script>
 					";
					}else{
					
						
if ($fechainic<=$fechaculm){
			  $sql = mysql_query("SELECT * FROM permiso WHERE (CI='".$CI."' and fechaculm>'".$fechainic."') and (fechaculm>'".$fechaeditado."') "); 
            if(mysql_num_rows($sql) > 0) { 
                echo"
 					<script>
					history.back();
    					alert('El Funcionario ya se encuentra de Reposo');
	 				</script>
				 ";
            }else { 
	
			
                $reg = mysql_query("INSERT INTO permiso (idpermiso, CI, motivo, fechainic, fechaculm, fechaeditado, nroreposo, institucion, nombreinsti,tipo) values (Null,'$CI','$motivo','$fechainic','$fechaculm','$fechaeditado','$nroreposo', '$institucion', '$nombreinsti','$tipo')"); 
                if($reg) { 
                     echo"
 					<script>
  						 window.location='principal.php';
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
		}else{
		 echo"
 					<script>
					history.back();
    					alert('La Fecha DESDE es mayor que HASTA');
	 				</script>
				 ";
		
		
		}		}
			}
			
			
?>