<? ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   ////////////////////////  ************************  MAESTRO SECTOR *****************************  ////////////////////////////
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$ahora=date("Y-m-d H:m:s");
if($accion==guardarSector){
   ////  SE COMPARA LOS DATOS PARA VERIFICAR SI YA FUERON INTRODUCIDOS   //////
   $result=mysql_query("SELECT * FROM pv_sector WHERE descripcion='".$_POST['descripcion']."'", $conexion);
   if (mysql_num_rows($result)!=0){
       echo "<script>";
       echo "alert('Los datos ya han sido registrados con anterioridad')";
       echo "</script>"; 
   }else{
      $sql1="SELECT MAX(cod_sector) FROM pv_sector";
      $query=mysql_query($sql1) or die ($sql1.mysql_error());
      $field=mysql_fetch_array($query);
      $codsector=(int) ($field[0]+1);
      $codsector=(string) str_repeat("0",2-strlen($codsector)).$codsector;
	  // INSERTAMOS LOS DATOS EN LA TABLA PV_SECTOR UNA VEZ COMPROBADOS 
	  $qry=mysql_query("INSERT INTO pv_sector(cod_sector, 
	                                         descripcion, 
											 Estado, 
											 UltimoUsuario, 
											 UltimaFecha) 
					                  VALUES ('$codsector',
									          '".$_POST['descripcion']."',
									          '".$_POST['status']."',
											  '".$_SESSION['USUARIO_ACTUAL']."',
											  '$ahora')");
   }
}else{//////////////////GARDAR LOS DATOS MODIFICADOS DE MAESTRO SECTOR/////////////////////////
   if($accion==editarSector){
     ////  SE COMPARA LOS DATOS PARA VERIFICAR SI YA FUERON INTRODUCIDOS   //////
     $result=mysql_query("SELECT * FROM pv_sector WHERE cod_sector='".$_POST['codigo']."'", $conexion);
     if(mysql_num_rows($result)!=0){
	   $qry=mysql_query("UPDATE pv_sector SET descripcion='".$_POST['descripcion']."',
											 Estado='".$_POST['status']."',
											 UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
											 UltimaFecha='$ahora'
										 WHERE cod_sector='".$_POST['codigo']."'");
     }
   }else{
	  if($accion==eliminar){/////////// ******** ELIMINAR REGISTRO DE SECTOR ******* /////////////
		$sql="DELETE FROM pv_sector WHERE Cod_sector='".$_POST['registro']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	  }
   }
 }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////  ************************  MAESTRO PARTIDA *****************************  ////////////////////////////	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
if($accion==guardarpartida){/////// ******** GUARDAR DATOS DE PARTIDA  ******  ////////////////////////////////////////////////
  $partidaconc=$_POST['tcpartida'].$_POST['partida1']."-".$_POST['generica']."-".$_POST['especifica']."-".$_POST['subespecifica'];
  $sql=mysql_query("SELECT * FROM
						   pv_partida 
					 WHERE (cod_partida='$partidaconc' AND
						   partida1='".$_POST['partida1']."' AND 
						   generica='".$_POST['generica']."' AND
						   especifica='".$_POST['especifica']."' AND
						   subespecifica='".$_POST['subespecifica']."' AND
						   denominacion='".$_POST['denominacion']."' AND 
						   cod_tipocuenta='".$_POST['tipocuenta']."') AND
						   Estado='".$_POST['status']."'",$conexion);
   if (mysql_num_rows($sql)!=0){
	   echo "<script>";
	   echo "alert('Los datos ya han sido introducidos con anterioridad')";
	   echo "</script>";
   }else{
	 if($tcpartida=='Egreso'){$tc=3; }else{$tc=4;}
	 //if($_POST['partida1']!='')
	$sql=mysql_query("INSERT 
							 INTO 
								pv_partida( cod_partida, partida1, generica, especifica, subespecifica,
								cod_tipocuenta, denominacion, Estado, UltimoUsuario, UltimaFecha, tipo,
								nivel)
							 VALUES
								('$partidaconc','".$_POST['partida1']."','".$_POST['generica']."',
								'".$_POST['especifica']."','".$_POST['subespecifica']."','$tc',
								'".$_POST['denominacion']."','".$_POST['status']."','".$_SESSION['USUARIO_ACTUAL']."',
								 '$ahora','".$_POST['opcion']."', '".$_POST['nivel']."' )");
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($accion==editarPartida){////////////////  ****   EDITAR PARTIDA   ****** ////////////////
   $fechaactual=date("Y-m-d H:m:s");
   $codpartida1=$_POST['codpartida'];
   $tcpartida=$_POST['tcpartida'];
   $sql=mysql_query("SELECT * FROM pv_partida WHERE cod_partida='$codpartida1'", $conexion);
   if(mysql_num_rows($sql)!=0){
     if($tcpartida=='Egreso'){$tc=3; }else{$tc=4;}
	    $partidaconc=$_POST['tcpartida'].$_POST['partida1']."-".$_POST['generica']."-".$_POST['especifica']."-".$_POST['subespecifica'];
	    //echo"$partidaconc";
		$qry=mysql_query("UPDATE pv_partida SET 
						                       denominacion='".$_POST['denominacion']."',
											   partida1='".$_POST['partida1']."',
											   generica='".$_POST['generica']."', 
											   especifica='".$_POST['especifica']."',
											   subespecifica='".$_POST['subespecifica']."', 
											   UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', Estado='".$_POST['status']."',
											   UltimaFecha='$fechactual', cod_partida='$partidaconc', tipo='".$_POST['opcion']."',
											   nivel='".$_POST['nivel']."', cod_tipocuenta='".$_POST['tc']."'
										   WHERE 
										       cod_partida='$codpartida1'");
	  }
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////  ************************  MAESTRO TIPO CUENTA *****************************  /////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($accion==guardartcuenta){//////////// *****  GUARDAR DATOS TIPO CUENTA ****** ////////////
   $sql=mysql_query("SELECT * FROM pv_tipocuenta WHERE 
		                   (cod_tipocuenta='".$_POST['codtipocuenta']."' AND 
						   descp_tipocuenta='".$_POST['descripcion']."')");
   if (mysql_num_rows($sql)!=0){
      echo "<script>";
	  echo "alert('Los datos ya han sido ingresados con anterioridad')";
	  echo "</script>";
   }else{
	  $qry=mysql_query("INSERT INTO pv_tipocuenta(cod_tipocuenta,
	                                              descp_tipocuenta, 
	                                              Estado,
												  UltimoUsuario,
												  UltimaFecha) 
						                 VALUES ('".$_POST['codtipocuenta']."',
										         '".$_POST['descripcion']."',
									            '".$_POST['status']."',
												'".$_SESSION['USUARIO_ACTUAL']."',
												'$ahora')");
	}
}else{
  if($accion==editarTcuenta){///////// ****** EDITAR TIPO CUENTA ***** /////////////
	$codigo=$_POST['codtipocuenta'];
	$fechaactua=date("Y-m-d H:m:s");
	$sql=mysql_query("SELECT * FROM pv_tipocuenta WHERE cod_tipocuenta='$codigo'") or die (mysql_error());
	if(mysql_num_rows($sql)!=0){
	 $qry=mysql_query("UPDATE pv_tipocuenta 
					  SET 
					   descp_tipocuenta='".$_POST['descripcion']."',
					   cod_tipocuenta='".$_POST['codtipocuenta']."',
					   UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
					   Estado='".$_POST['status']."', UltimaFecha='$fechaactua' 
					   WHERE cod_tipocuenta='$codigo'");
    }
  }
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////  ************************  MAESTRO PROGRAMA *****************************   ///////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($accion==guardarPrograma){/// *****************  GUARDAR PROGRAMA ****************
 $sql=mysql_query("SELECT * FROM pv_programa1 WHERE cod_sector='".$_POST['selectSector']."' AND descp_programa='".$_POST['descripcion']."'", $conexion);
 if(mysql_num_rows($sql)!=0){
   echo"<script>";
   echo"alert('Los datos ya han sido ingresados con anterioridad')";
   echo"</script>";
 }else{
   $sql1="SELECT MAX(cod_programa) FROM pv_programa1 WHERE cod_sector='".$_POST['selectSector']."'";
   $query=mysql_query($sql1) or die ($sql1.mysql_error());
   $field=mysql_fetch_array($query);
   $codprog=(int) ($field[0]+1);
   $codprog=(string) str_repeat("0", 2-strlen($codprog)).$codprog;
   $sql=mysql_query("INSERT INTO pv_programa1(cod_programa,
											  descp_programa,
											  cod_sector,
											  Estado,
											  UltimoUsuario,
											  UltimaFecha)
									   VALUES ('$codprog', 
											   '".$_POST['descripcion']."', 
											   '".$_POST['selectSector']."',
											   '".$_POST['status']."',
											   '".$_SESSION['USUARIO_ACTUAL']."', 
											   '$ahora')");
 }
}else{
  if($accion==editarPrograma){/// *********************************** EDITAR PROGRAMA ******************************************
    $sql=mysql_query("SELECT * FROM pv_programa1 WHERE (cod_sector='".$_POST['codsector']."' AND cod_programa='".$_POST['codprograma']."')", $conexion);
	if(mysql_num_rows($sql)!=0){
	  $sql=mysql_query("UPDATE pv_programa1 SET descp_programa='".$_POST['descripcionp']."',
	                                            cod_sector='".$_POST['descripcionsector']."',
	  										    UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
											    UltimaFecha='$ahora',
											    Estado='".$_POST['status']."'
										  WHERE cod_sector='".$_POST['codsector']."' AND cod_programa='".$_POST['codprograma']."'");
	}
  }
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////  ************************  MAESTRO SUB-PROGRAMA *****************************  ////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($accion==guardarSubprog){ ///////// ****** GUARDAR NUEVO SUB-PROGRAMA ****** ////////////////
  $sql=mysql_query("SELECT * FROM pv_subprog1 WHERE id_programa='".$_POST['selectPrograma']."' AND descp_subprog='".$_POST['descripcion']."'", $conexion);
  if(mysql_num_rows($sql)!=0){
    echo"<script>";
	echo"alert('Los Datos ya han sido ingresados con anterioridad')";
	echo"</script>";
  }else{
    $sql2=mysql_query("SELECT id_programa,cod_programa FROM pv_programa1 WHERE id_programa='".$_POST['selectPrograma']."'");
	if(mysql_num_rows($sql2)!=0){
	  $fieldPrograma=mysql_fetch_array($sql2);	
	}
    $qry="SELECT MAX(cod_subprog) FROM pv_subprog1 WHERE id_programa='".$_POST['selectPrograma']."'";
	$query=mysql_query($qry) or die ($qry.mysql_error());
	$field=mysql_fetch_array($query);
	$codsubprog=(int) ($field[0]+1);
	$codsubprog=(string) str_repeat("0", 2-strlen($codsubprog)).$codsubprog;
	///////************************** INSERTAMOS LOS DATOS EN LA TABLA *****************************///////
	$sql=mysql_query("INSERT INTO pv_subprog1(cod_programa,
	                                          cod_subprog,
											  descp_subprog,
											  id_programa,
											  UltimoUsuario,
											  UltimaFecha,
											  Estado) 
									   VALUES ('".$fieldPrograma['cod_programa']."',
									           '$codsubprog',
											   '".$_POST['descripcion']."',
											   '".$_POST['selectPrograma']."',
											   '".$_SESSION['USUARIO_ACTUAL']."',
											   '$ahora',
											   '".$_POST['status']."')");
  }
}else{
	if($accion==editarSubprog){///////////////// ************* EDITAR SUB-PROGRAMA *************** //////////////////
	  $codigo=$_POST[idsub];
	  $fechaactual=date("Y-m-d H:m:s");
	  $sql="SELECT * FROM pv_subprog1 WHERE id_sub='$codigo'";
	  $qry=mysql_query($sql) or die ($sql.mysql_error());
	  if(mysql_num_rows($qry)!=0){
	    $sql2="SELECT id_programa,cod_programa FROM pv_programa1 WHERE id_programa='".$_POST['selectPrograma']."'";
		$qry2=mysql_query($sql2) or die ($sql2.mysql_error());
		if(mysql_num_rows($qry2)!=0){
         $field=mysql_fetch_array($qry2);
		 $sql="UPDATE pv_subprog1 SET id_programa='".$_POST['selectPrograma']."',
		                                          cod_programa='".$field['cod_programa']."',
		                                          Estado='".$_POST['status']."', 
												  UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', 
												  UltimaFecha='$fechaactual', 
												  descp_subprog='".$_POST['descripcion']."' 
										    WHERE id_sub='$codigo'";
		 $qry=mysql_query($sql) or die ($sql.mysql_error());
	   }
	 }
   }
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////  ************************  MAESTRO PROYECTO *****************************  ////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($accion==guardarProyecto){///////// ****** GUARDAR NUEVO PROYECTO PROYECTO *** /////////////
  ////  SE COMPARA LOS DATOS PARA VERIFICAR SI YA FUERON INTRODUCIDOS   //////
  $sql2=mysql_query("SELECT * FROM pv_subprog1 WHERE id_sub='".$_POST['selectSubprog']."'");
  if(mysql_num_rows($sql2)!=0){
    $field2=mysql_fetch_array($sql2);
  }
  $result=mysql_query("SELECT * FROM pv_proyecto1 WHERE (cod_subprog='".$field2['cod_subprog']."' AND descp_proyecto='".$_POST['descripcion']."')", $conexion);
   if(mysql_num_rows($result)!=0){
       echo "<script>";
       echo "alert('Los datos ya han sido registrados con anterioridad')";
       echo "</script>"; 
   }else{
	   $sql1="SELECT MAX(cod_proyecto) FROM pv_proyecto1 WHERE cod_subprog='".$field2['cod_subprog']."' AND id_sub='".$_POST['selectSubprog']."'";
	   $query=mysql_query($sql1) or die ($sql1.mysql_error());
	   $field=mysql_fetch_array($query);
	   $codproy=(int) ($field[0]+1);
	   $codproy=(string) str_repeat("0", 2-strlen($codproy)).$codproy;
	  // INSERTAMOS LOS DATOS EN LA TABLA UNA VEZ COMPROBADOS 
	  $sql="INSERT INTO pv_proyecto1 (id_sub,
	                                  cod_proyecto,
	                                  descp_proyecto,
									  cod_subprog, 
									  UltimoUsuario, 
									  UltimaFecha, 
									  Estado) 
	                          VALUES ('".$_POST['selectSubprog']."',
							          '$codproy',
							          '".$_POST['descripcion']."',
									  '".$field2['cod_subprog']."',
									  '".$_SESSION['USUARIO_ACTUAL']."',
									  '$ahora',
									  '".$_POST['status']."')";
	  $query=mysql_query($sql) or die ($sql.mysql_error());
   }
}else{
  if($accion==editarProyecto){/////////////// ************** EDITAR PROYECTO ************ /////////////////////////
  $codigo=$_POST[idproyecto];
  $sql=mysql_query("SELECT * FROM pv_proyecto1 WHERE id_proyecto='$codigo'", $conexion);
  if(mysql_num_rows($sql)!=0){
    $field=mysql_fetch_array($sql);
	$sql2=mysql_query("SELECT id_sub,cod_subprog FROM pv_subprog1 WHERE id_sub='".$_POST['selectSubprograma']."'");
    if(mysql_num_rows($sql2)!=0){
	   $field2=mysql_fetch_array($sql2);
	   $qry=mysql_query("UPDATE pv_proyecto1 SET descp_proyecto='".$_POST['descripcion']."',
	                                             id_sub='".$_POST['selectSubprograma']."',
												 cod_subprog='".$field2['cod_subprog']."',
												 Estado='".$_POST['status']."',
												 UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
											     UltimaFecha='$ahora'
										   WHERE id_proyecto='$codigo'"); 
    }
  }
 }
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////  ************************  MAESTRO ACTIVIDAD *****************************  ////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($accion==guardarActividad){///////// ****** GUARDAR NUEVO ACTIVIDAD ****** ////////////////
   $result=mysql_query("SELECT * FROM pv_actividad1 WHERE (id_proyecto='".$_POST['selectProyecto']."' AND descp_actividad='".$_POST['descripcion']."')", $conexion);
   if (mysql_num_rows($result)!=0){
       echo "<script>";
       echo "alert('Los datos ya han sido registrados con anterioridad')";
       echo "</script>"; 
   }else{
      $sqlProyecto=mysql_query("SELECT id_proyecto, cod_proyecto FROM pv_proyecto1 WHERE id_proyecto='".$_POST['selectProyecto']."'");
	  if(mysql_num_rows($sqlProyecto)!=0){
	    $fieldProyecto=mysql_fetch_array($sqlProyecto);	
	   }
	  $sql1="SELECT MAX(cod_actividad) FROM pv_actividad1 WHERE id_proyecto='".$_POST['selectProyecto']."'";
      $query=mysql_query($sql1) or die ($sql1.mysql_error());
      $field=mysql_fetch_array($query);
      $codactiv=(int) ($field[0]+1);
      $codactiv=(string) str_repeat("0", 2-strlen($codactiv)).$codactiv;
	  //////////////////////************************************************************////////////////////////
	  $qry="INSERT INTO pv_actividad1(cod_actividad,
	                                                descp_actividad,
												    cod_proyecto,
												    id_proyecto,
												    Estado,
												    UltimoUsuario,
												    UltimaFecha)
	                                        VALUES ('$codactiv',
										            '".$_POST['descripcion']."',
												    '".$fieldProyecto['cod_proyecto']."',
												    '".$_POST['selectProyecto']."',
												    '".$_POST['status']."',
												    '".$_SESSION['USUARIO_ACTUAL']."',
												    '$ahora')";
	$query=mysql_query($qry) or die ($qry.mysql_error());
     }
}else{
  if($accion==editarActividad){///////////////////// ****** EDITAR ACTIVIDAD ******* ///////////////////
   $idActividad=$_POST['idactividad'];
   $sql=mysql_query("SELECT * FROM pv_actividad1 WHERE id_actividad='$idActividad'", $conexion);
   if(mysql_num_rows($sql)!=0){
	 $field=mysql_fetch_array($sql);
	 $sql2=mysql_query("SELECT id_proyecto,cod_proyecto FROM pv_proyecto1 WHERE id_proyecto='".$_POST['selectProyecto']."'");
	 if(mysql_num_rows($sql2)!=0){
	   $field2=mysql_fetch_array($sql2);
	   $qry=mysql_query("UPDATE pv_actividad1 SET descp_actividad='".$_POST['descripcion']."',
	                                            id_proyecto='".$_POST['selectProyecto']."',
												cod_proyecto='".$field2['cod_proyecto']."',
												UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
					                            Estado='".$_POST['status']."',
												UltimaFecha='$ahora' 
										  WHERE id_actividad='$idActividad'");
   }}
 }else{
    if($accion==eliminarActividad){
	  $sql="DELETE FROM pv_actividad1 WHERE cod_proyecto='".$_POST['registro']."'";
	  $query=mysql_query($sql) or die ($sql.mysql_error());
    } 
  }
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////  ************************  MAESTRO UNIDAD EJECUTORA *********************   ///////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($accion==guardarUnidad){////////// ****** GUARDAR UNIDAD EJECUTORA ****** ///////////
   $result=mysql_query("SELECT * FROM pv_unidadejecutora WHERE Unidadejecutora='".$_POST['descripcion']."'", $conexion);
   if(mysql_num_rows($result)!=0){
      echo "<script>";
      echo "alert('Los datos ya han sido registrados con anterioridad')";
      echo "</script>"; 
   }else{
     $sql="SELECT MAX(id_unidadejecutora) FROM pv_unidadejecutora";
     $query=mysql_query($sql) or die ($sql.mysql_error());
     $field=mysql_fetch_array($query);
     $idUnidad=(int) ($field[0]+1);
     $idUnidad=(string) str_repeat("0",2-strlen($idUnidad)).$idUnidad;
     ////// INSERTAMOS LOS DATOS EN LA TABLA PV_UNIDADEJECUTORA UNA VEZ COMPROBADOS 
     $qry=mysql_query("INSERT INTO pv_unidadejecutora(id_unidadejecutora, 
                                                  Unidadejecutora, 
   									              Estado, 
											      UltimoUsuario, 
											      UltimaFecha) 
					                        VALUES ('$idUnidad',
									               '".$_POST['descripcion']."',
									               '".$_POST['status']."',
											       '".$_SESSION['USUARIO_ACTUAL']."',
											       '$ahora')");
  }
}else{//////////////////GARDAR LOS DATOS MODIFICADOS DE MAESTRO UNIDAD EJECUTORA/////////////////////////
   if($accion==editarUnidad){
     ////  SE COMPARA LOS DATOS PARA VERIFICAR SI YA FUERON INTRODUCIDOS   //////
     $result=mysql_query("SELECT * FROM pv_unidadejecutora WHERE id_unidadejecutora='".$_POST['codigo']."'", $conexion);
     if(mysql_num_rows($result)!=0){
	   $qry=mysql_query("UPDATE pv_unidadejecutora SET Unidadejecutora='".$_POST['descripcion']."',
											           Estado='".$_POST['status']."',
											           UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
											           UltimaFecha='$ahora'
										         WHERE id_unidadejecutora='".$_POST['codigo']."'");
     }
   }else{
	  if($accion==eliminarUnidad){/////////// ******** ELIMINAR REGISTRO DE UNIDAD EJECUTORA ******* /////////////
		$sql="DELETE FROM pv_unidadejecutora WHERE id_unidadejecutora='".$_POST['registro']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	  }
   }
 }
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////  ************************  MAESTRO ANTEPRESUPUESTO ************* ////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($accion==GuardarDatosPres){
   $fecha=date("Y-m-d");
   $sql="SELECT * FROM pu_antepresupuesto WHERE EjercicioPpto='".$_POST['anop']."'";
   $query=mysql_query($sql) or die ($sql.mysql_error());
   $rows=mysql_num_rows($query);
   if($rows!=0){
     echo"<script>";
	 echo"alert('los datos ya han sido ingresados con anterioridad')";
	 echo"</script>";
   }else{
     $sql="SELECT MAX(CodAnteProyecto) FROM pu_antepresupuesto";
     $query=mysql_query($sql) or die ($sql.mysql_error());
     $field=mysql_fetch_array($query);
     $idAnte=(int) ($field[0]+1);
     $idAnte=(string) str_repeat("0",3-strlen($idAnte)).$idAnte;
      $fechaCreacion=$_POST['fcreacion']; $fechaCreacion=date("Y-m-d",strtotime($fechaCreacion)); 
	  $fechainicio=$_POST['fdesde']; $fechainicio=date("Y-m-d",strtotime($fechainicio)); 
	  $fechafin=$_POST['fhasta']; $fechafin=date("Y-m-d",strtotime($fechafin)); 
	 //$fechaInicio=date("Y-m-d", mktime('".$_POST['finicio']."'));
     $sql="INSERT INTO pu_antepresupuesto(CodAnteProyecto,
	                                                  Organismo,
	                                                  EjercicioPpto,
													  FechaAnteproyecto,
													  Estado,
													  Sector,
													  Programa,
													  SubPrograma,
													  Proyecto,
													  Actividad,
													  FechaInicio,
													  FechaFin,
													  MontoPresupuestado,
													  PreparadoPor,
													  UltimoUsuario,
													  AprobadoPor,
													  UltimaFecha) 
	                                VALUES ('$idAnte',
									        '".$_POST['organismo']."',
									        '".$_POST['ejercicioPpto']."',
											'$fechaCreacion',
											'".$_POST['estado']."',
											'".$_POST['sector']."',
											'".$_POST['programa']."',
											'".$_POST['subprograma']."',
											'".$_POST['proyecto']."',
											'".$_POST['actividad']."',
											'$fechainicio',
											'$fechafin',
											'".$_POST['montoautori']."',
											'".$_POST['prepor']."',
											'".$_SESSION['USUARIO_ACTUAL']."',
											'".$_POST['nomempleado']."',
											'$ahora')";
	 $query=mysql_query($sql) or die ($sql.mysql_error());
	///////////////////  CARGANDO DATOS EN LA TABLA PU_ANTEPROYECTO ///////////////////////////
	$sql="SELECT MAX(IdAnteproyecto), MAX(Secuencia) FROM pu_anteproyecto";
	$qry=mysql_query($sql) or die ($sql.mysql_error());
	$fieldAntp=mysql_fetch_array($qry);
	$IdAnteproyecto=(int) ($fieldAntp[0]+1);
    $IdAnteproyecto=(string) str_repeat("0", 3-strlen($IdAnteproyecto)).$IdAnteproyecto;
	$secuencia=(int) ($fieldAntp[1]+1);
    $secuencia=(string) str_repeat("0", 3-strlen($secuencia)).$secuencia;
	$sqlAnteproyecto="INSERT INTO pu_anteproyecto(IdAnteproyecto,
	                                              CodAnteProyecto,
												  Secuencia,
												  MontoAsignado,
												  UltimoUsuario,
												  UltimaFecha)
										  VALUES ('$IdAnteproyecto',
										          '$idAnte',
												  '$secuencia',
												  '".$_POST['montoautori']."',
												  '".$_SESSION['USUARIO_ACTUAL']."',
												  '$ahora')";
	$qryAnteproyecto=mysql_query($sqlAnteproyecto) or die ($sqlAnteproyecto.mysql_error());
   }
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////  ***************      ANTEPROYECTO ACTUALIZAR     ************* ////////////////////////////
/*if($accion==GuardarDatosAnte){
   $fecha=date("Y-m-d");
   $sql="SELECT * FROM pu_antepresupuesto WHERE CodAnteproyecto='".$_POST['CodAnteproyecto']."'";
   $query=mysql_query($sql) or die ($sql.mysql_error());
   $rows=mysql_num_rows($query);
   if($rows!=0){
      $fechaCreacion=$_POST['fcreacion']; $fechaCreacion=date("Y-m-d",strtotime($fechaCreacion)); 
	  $fechainicio=$_POST['fdesde']; $fechainicio=date("Y-m-d",strtotime($fechainicio)); 
	  $fechafin=$_POST['fhasta']; $fechafin=date("Y-m-d",strtotime($fechafin)); 
	 //$fechaInicio=date("Y-m-d", mktime('".$_POST['finicio']."'));
     $sql="UPDATE pu_antepresupuesto SET (CodAnteProyecto,
	                                                  Organismo,
	                                                  EjercicioPpto,
													  FechaAnteproyecto,
													  Estado,
													  Sector,
													  Programa,
													  SubPrograma,
													  Proyecto,
													  Actividad,
													  FechaInicio,
													  FechaFin,
													  MontoPresupuestado,
													  PreparadoPor,
													  UltimoUsuario,
													  AprobadoPor,
													  UltimaFecha) 
	                                VALUES ('$idAnte',
									        '".$_POST['organismo']."',
									        '".$_POST['ejercicioPpto']."',
											'$fechaCreacion',
											'".$_POST['estado']."',
											'".$_POST['sector']."',
											'".$_POST['programa']."',
											'".$_POST['subprograma']."',
											'".$_POST['proyecto']."',
											'".$_POST['actividad']."',
											'$fechainicio',
											'$fechafin',
											'".$_POST['montoautori']."',
											'".$_POST['prepor']."',
											'".$_SESSION['USUARIO_ACTUAL']."',
											'".$_POST['nomempleado']."',
											'$ahora')";
	 $query=mysql_query($sql) or die ($sql.mysql_error());
 }
}else{*/
/////////////////// *******  ACTUALIZAR DATOS EN ENVIADOS DESDE ANTEPROYECTO_EDITARGEN   ******** /////////////////
if($accion==ActualizarAnte){
  $fechaCreacion=$_POST['fcreacion']; $fechaCreacion=date("Y-m-d",strtotime($fechaCreacion)); 
  $fechainicio=$_POST['fdesde']; $fechainicio=date("Y-m-d",strtotime($fechainicio)); 
  $fechafin=$_POST['fhasta']; $fechafin=date("Y-m-d",strtotime($fechafin)); 
  /*echo "<script>";
  echo "alert('VOY POR AQUI CARAJO')";
  echo "</script>"; */
  $sqlAct="UPDATE pu_antepresupuesto SET Organismo='".$_POST['organismo']."',
	 										FechaAnteproyecto='$fechaCreacion',
											FechaInicio='$fechainicio',
											FechaFin='$fechafin',
	   										UltimaFecha='$ahora',
											UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
											Modif='M'
									  WHERE CodAnteproyecto='".$_POST['CodAnteproyecto']."'";
  $qryAct=mysql_query($sqlAct) or die ($sqlAct.mysql_error());
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////  ****************  MAESTRO ANTEPRESUPUESTO DETALLE ************* ////////////////////////////
if($accion==GuardarMontoPartidas){
 $sql="SELECT MAX(CodAnteproyecto) FROM pu_antepresupuesto";
 $qry=mysql_query($sql) or die ($sql.mysql_error());
 if(mysql_num_rows($qry)!=0){
  $field=mysql_fetch_array($qry);
  $sqlDet="SELECT cod_partida,Estado,IdAnteProyectoDet,Secuencia,MontoAsignado FROM pu_antepresupuestodet WHERE CodAnteProyecto='".$field['0']."' ORDER BY cod_partida";
  $query=mysql_query($sqlDet) or die ($sqlDet.mysql_error());
  $rows=mysql_num_rows($query);
  for($i=0; $i<$rows; $i++){
   $fielDet=mysql_fetch_array($query);
   $id=$fielDet['IdAnteProyectoDet'];
   $monto=$_POST[$id];
 /*if(!empty($_POST['campos'])){
   for($i=0;$i<count($_POST['campos']);$i++) {
      $field=explode(",",$_POST['campos'][$i]);
	  $field2=explode(",",$_POST['montoAsignado'][$i]);
	  $id = $fielDet['IdAnteProyectoDet'];
	  $monto = $_POST[$id];
      /*$sql= "INSERT INTO pv_antepresupuestodet (MontoAsignado,
	                                           UltimoUsuario,
											   UltimaFechaModif) 
	                                    VALUES ('$field2[$i]',
										       '".$_SESSION['USUARIO_ACTUAL']."',
											   '$ahora') 
										WHERE  IdAnteProyectoDet='$field[$i]'";*/
      $sql="UPDATE pu_antepresupuestodet SET MontoAsignado='$monto',
                                              UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
									          UltimaFechaModif='$ahora'
								        WHERE IdAnteProyectoDet='$id'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
}}}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////  ****************  MAESTRO ANTEPRESUPUESTO DETALLE ************* ////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($accion==GuardarMontoEditado){
 echo"<script>";
 echo" alert('Por aqui voy')";
 echo"</script>";
 $fechaCreacion=$_POST['fcreacion']; $fechaCreacion=date("Y-m-d",strtotime($fechaCreacion)); 
 $fechainicio=$_POST['fdesde']; $fechainicio=date("Y-m-d",strtotime($fechainicio)); 
 $fechafin=$_POST['fhasta']; $fechafin=date("Y-m-d",strtotime($fechafin));
      ///// ***** ACTUALIZACION TABLA PU_ANTEPRESUPUESTO ***** ////// 
 $sqlUpAntepresupuesto="UPDATE pu_antepresupuesto SET Organismo='".$_POST['organismo']."',
	                                                  EjercicioPpto='".$_POST['ejercicioPpto']."',
													  FechaAnteproyecto='$fechaCreacion',
													  Estado='".$_POST['estado']."',
													  Sector='".$_POST['sector']."',
													  Programa='".$_POST['programa']."',
													  SubPrograma='".$_POST['subprograma']."',
													  Proyecto='".$_POST['proyecto']."',
													  Actividad='".$_POST['actividad']."',
													  FechaInicio='$fechainicio',
													  FechaFin='$fechafin',
													  MontoPresupuestado='".$_POST['montoautori']."',
													  PreparadoPor='".$_POST['prepor']."',
													  UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
													  AprobadoPor='".$_POST['nomempleado']."',
													  UltimaFecha='$ahora'
	                                            WHERE CodAnteproyecto='".$_POST['registro']."'"; 
  $qryUpAntepresupuesto=mysql_query($sqlUpAntepresupuesto) or die ($sqlUpAntepresupuesto.mysql_error());
      ///// ***** ACTUALIZACION TABLA PU_ANTEPRESUPUESTODET ***** /////
 $sql="SELECT * FROM pu_antepresupuesto WHERE CodAnteproyecto='".$_POST['registro']."'";
 $qry=mysql_query($sql) or die ($sql.mysql_error());
 if(mysql_num_rows($qry)!=0){
  $field=mysql_fetch_array($qry);
  $sqlDet="SELECT cod_partida,Estado,IdAnteProyectoDet,Secuencia,MontoAsignado FROM pu_antepresupuestodet WHERE CodAnteProyecto='".$field['CodAnteproyecto']."' ORDER BY cod_partida";
  $query=mysql_query($sqlDet) or die ($sqlDet.mysql_error());
  $rows=mysql_num_rows($query);
  for($i=0; $i<$rows; $i++){
   $fielDet=mysql_fetch_array($query);
   $id=$fielDet['IdAnteProyectoDet'];
   $monto=$_POST[$id];
   $sql="UPDATE pu_antepresupuestodet SET MontoAsignado='$monto',
                                          UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
									      UltimaFechaModif='$ahora'
								    WHERE IdAnteProyectoDet='$id'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
}}}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////// ****   *****  GUARDAR AJUSTES ******** ********** ////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($accion==GuardarAjuste){
  if(!empty($_POST['ajuste'])){
    $Ajuste=array_keys($_POST['ajuste']);
	$qryAjuste="";
	for($i=1; $i<=$filas; $i++){
	 if($_POST[$i]!=''){
	  $sqlDet="";
	 }
	}
  }
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// ****   *****  GUADAR ESTADO REVISADAO DEL ANTEPROYECTO ******** ********** ///////////////////
if($accion==AnteproyectoRevisado){
 $sql="UPDATE pu_antepresupuesto SET  Estado='".$_POST['estado']."',
                                      UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
									  UltimaFecha='$ahora' 
								WHERE CodAnteproyecto='".$_POST['CodAnteproyecto']."'";
 $qry=mysql_query($sql) or die ($sql.mysql_error());
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// ****   *****  GUADAR ESTADO APROBADO DEL ANTEPROYECTO ******** ********** ///////////////////
if($accion==AnteproyectoAprobado){
 $sql="UPDATE pu_antepresupuesto SET  Estado='".$_POST['estado']."',
                                      UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
									  UltimaFecha='$ahora' 
								WHERE CodAnteproyecto='".$_POST['CodAnteproyecto']."'";
 $qry=mysql_query($sql) or die ($sql.mysql_error());
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////*******   GENERRAR ANTEPROYECTO ******    /////////////////////////////////////////////////
if($accion==AnteproyectoGenerar){
echo"<script>
     alert('PASO')
	 </script>";
 $sql="INSERT INTO pu_anteproyectogenerar (cod_partida,
										   MontoAsignado,
										   UltimoUsuario,
										   UltimaFechaModif) 
								  VALUES ( '$partida',
								           '$monto',
										   '".$_SESSION['USUARIO_ACTUAL']."',
										   '$ahora')";
 $query=mysql_query($sql) or die ($sql.mysql_error());
}
?> 