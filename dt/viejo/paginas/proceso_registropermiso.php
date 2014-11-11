<?
	include('acceso_db.php');
	$fechaeditado=date("Y/m/d");
	$nroreposo="-------";
	$institucion="-------";
	$nombreinsti="-------";
	if ($tipo=='Personal - Por Horas'){
		
		if($fechainic<$fechaeditado){
			 echo"
 					<script>
					history.back();
    					alert('El Permiso no puede ser procesado, la Fecha ya ha pasado!');
	 				</script>
				 ";
			}elseif($horas>4){
				 echo"
 					<script>
					history.back();
    					alert('El permiso no debe ser mayor que 4 Horas');
	 				</script>
				 ";
				}else{
					
					$reg = mysql_query("INSERT INTO permiso (idpermiso, CI, motivo, fechainic, fechaculm, fechaeditado, nroreposo, institucion, nombreinsti,tipo, horas) values (Null,'$CI','$motivo','$fechainic','$fechainic','$fechaeditado','$nroreposo', '$institucion', '$nombreinsti','$tipo', '$horas')"); 
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
		
		}else if ($tipo=='Personal - Por Dias'){
			$horas="----";
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

			
                $reg = mysql_query("INSERT INTO permiso (idpermiso, CI, motivo, fechainic, fechaculm, fechaeditado, nroreposo, institucion, nombreinsti,tipo, horas) values (Null,'$CI','$motivo','$fechainic','$fechaculm','$fechaeditado','$nroreposo', '$institucion', '$nombreinsti','$tipo', '$horas')"); 
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
		
		
		}
		
		}
	
	
?>	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
			
				
				
          
			