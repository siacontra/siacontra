<?
include('acceso_db.php');
$fecha=$ano.'-'.$mes.'-'.'00'; //concatenacion de fechas
$fechaeditado=date("Y-m-00");
	if ($mes=="0"){
		echo"
 			<script>
   				history.back();
    			alert('Seleccione el Mes');
	 		</script>
 		";
	}else if ($ano=="0"){
				echo"
 				<script>
   					history.back();
    				alert('Seleccione el Periodo');
	 			</script>
 			";
			}else if ($fecha>$fechaeditado)
			{
				echo"
 				<script>
   					history.back();
    				alert('El Reporte todavia no se puede emitir');
	 			</script>
				";
			}else
			{
				//OJO AQUI SE LLAMA EL REPORTE SI YA EXSITE
					$sql = mysql_query("SELECT * FROM mantenimiento WHERE mes='".$mes."' and ano='".$ano."'"); 
					if(mysql_num_rows($sql)) 
					{
					
							echo "
							<script>
								window.location='pdfcontrolasistencia.php?mes=$mes&ano=$ano';
							</script>"; 
					}else
						{ 
						
							$result = mysql_query("select MAX(nrocontrol) from controlasis"); 
							$resultado = mysql_result ($result, 0); 
							$nrocontrol = $resultado; 
							$nrocontrol=$nrocontrol+1;
							$sql = "SELECT * FROM dependencia";
							$sql = mysql_query($sql);
							while($datos = mysql_fetch_assoc($sql)) 
								{
									$dependencia=$datos[nomdependencia];
									$jj=mysql_query("SELECT COUNT(*) as func FROM personal WHERE dependencia = '".$dependencia."'");
									while($j=mysql_fetch_assoc($jj))
										{
											$nrofuncionarios=$j[func];
											$Totalfunc=$Totalfunc+$nrofuncionarios;
										}
										$jj=mysql_query("SELECT cedula FROM personal WHERE dependencia = '".$dependencia."'");
										while($j=mysql_fetch_assoc($jj))
										{
											$cedula=$j[cedula];
											$jj=mysql_query("SELECT COUNT(*) as nroreposo FROM permiso WHERE CI = '".$cedula."' and  tipo = 'Medico'and ('".$fecha."' BETWEEN fechainic And fechaculm or  month(fechainic)= '".$mes."' and year(fechainic)= '".$ano."')");
											while($j=mysql_fetch_assoc($jj))
											{
												$nroreposo=$j[nroreposo];
												$totalreposo=$totalreposo+$nroreposo;
											}
											$jj=mysql_query("SELECT COUNT(*) as nropersonal FROM permiso WHERE CI = '".$cedula."' and  tipo = 'Personal - Por Horas' and month(fechainic)= '".$mes."' and year(fechainic)= '".$ano."'");
											while($j=mysql_fetch_assoc($jj))
											{
												$nropersonal=$j[nropersonal];
											}
											$jj=mysql_query("SELECT COUNT(*) as nropersona2 FROM permiso WHERE CI = '".$cedula."' and  tipo = 'Personal - Por Dias'and '".$fecha."' BETWEEN fechainic And fechaculm");
											while($j=mysql_fetch_assoc($jj))
											{
												$nropersona2=$j[nropersona2];
											}
											$nropersona=$nropersonal+$nropersona2;
											$totalnropersona=$totalnropersona+$nropersona;
											$jj=mysql_query("SELECT SUM(nro) as nrollegadas FROM llegadastardias WHERE ci = '".$cedula."' and month(desde)= '".$mes."' and year(desde)= '".$ano."'");
											while($j=mysql_fetch_assoc($jj))
											{
												$nrollegadas=$j[nrollegadas];
												$totalnrolledas=$totalnrolledas+$nrollegadas;
											}
										}
										 $jj=mysql_query("SELECT COUNT(DISTINCT ci) as funcionariotre FROM llegadastardias WHERE  nro > 2 and dependencia = '".$dependencia."' and month(desde)= '".$mes."' and year(desde)= '".$ano."'" );
											while($j=mysql_fetch_assoc($jj))
											{
												$funcionariotre=$j[funcionariotre];
												$totalfunctre=$totalfunctre+$funcionariotre;
											}
										$reg = mysql_query("INSERT INTO controlasis (id_a, nrocontrol, dependencia, nrofuncionario, nropermiso, nroreposo, nrollegadatardia, nrofuncionariotres, mes, ano) values (Null,'$nrocontrol','$dependencia','$nrofuncionarios','$nropersona','$nroreposo', '$nrollegadas', '$funcionariotre','$mes', '$ano')"); 
										
								}
								$dependencia="TOTALES";
								$reg = mysql_query("INSERT INTO controlasis (id_a, nrocontrol, dependencia, nrofuncionario, nropermiso, nroreposo, nrollegadatardia, nrofuncionariotres, mes, ano) values (Null,'$nrocontrol','$dependencia','$Totalfunc','$totalnropersona','$totalreposo', '$totalnrolledas', '$totalfunctre','$mes', '$ano')");
								$sql = mysql_query("SELECT * FROM controlasis WHERE mes='".$mes."' and ano='".$ano."'"); 
								if(mysql_num_rows($sql)) 
								{ 
									echo "
									<script>
										//window.location='pdfcontrolasistencia.php?mes=$mes&ano=$ano';
										window.open('pdfcontrolasistencia.php?mes=$mes&ano=$ano','toolbar=no,location=no,resizable=no,height=200' );
									</script>"; 
								}
					
						}
			}
						
?>
