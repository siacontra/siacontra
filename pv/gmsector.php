<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   ////////////////////////  ************************  MAESTRO SECTOR *****************************  ///////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
include "conexion_.php";
extract ($_POST);
extract ($_GET);

$ahora=date("Y-m-d H:m:s");
if($accion==guardarSector){
   ////  SE COMPARA LOS DATOS PARA VERIFICAR SI YA FUERON INTRODUCIDOS   //////
   $result=mysql_query("SELECT * FROM pv_sector WHERE descripcion='".$_POST['descripcion']."'", $conexion);
   if (mysql_num_rows($result)!=0){
       echo "<script>";
       echo "alert('LOS DATOS HAN SIDO INGRESADOS CON ANTERIORIDAD')";
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
	  if($accion==eliminar){
		/////////// ******** ELIMINAR REGISTRO DE SECTOR ******* /////////////
		$sql="DELETE FROM pv_sector WHERE Cod_sector='".$_POST['registro']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	  }
   }
 }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////  ************************  MAESTRO PARTIDA *****************************  ////////////////////////////	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
////////////////////////////////////////// ******** GUARDAR DATOS DE PARTIDA  ******  /////////////////////////////////////////
if($accion==guardarpartida){
  $partidaconc=$_POST['tcpartida'].$_POST['partida1'].".".$_POST['generica'].".".$_POST['especifica'].".".$_POST['subespecifica'];
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
	   echo "alert('LOS DATOS HAN SIDO INGRESADOS CON ANTERIORIDAD')";
	   echo "</script>";
   }else{
	 if($tcpartida=='Egreso'){$tc=3; }else{$tc=4;}
	 //if($_POST['partida1']!='')
	$sql="INSERT INTO pv_partida( cod_partida, 
	                                 partida1, 
									 generica, 
									 especifica, 
									 subespecifica,
								     cod_tipocuenta, 
									 denominacion, 
									 Estado, 
									 UltimoUsuario, 
									 UltimaFecha, 
									 tipo, 
									 nivel)
						 VALUES
								('$partidaconc',
								 '".$_POST['partida1']."',
								 '".$_POST['generica']."',
								 '".$_POST['especifica']."',
								 '".$_POST['subespecifica']."',
								 '$tc',
								 '".utf8_decode($_POST['denominacion'])."',
								 '".$_POST['status']."',
								 '".$_SESSION['USUARIO_ACTUAL']."',
								 '$ahora',
								 '".$_POST['opcion']."', 
								 '".$_POST['nivel']."' )";
	$qry=mysql_query($sql) or die ($sql.mysql_error());
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////  ****   EDITAR PARTIDA   ****** //////////////////////////////////////////////////
if($accion==editarPartida){
   $fechaactual=date("Y-m-d H:m:s");
   $codpartida1=$_POST['codpartida'];
   $tcpartida=$_POST['tcpartida'];
   $sql=mysql_query("SELECT * FROM pv_partida WHERE cod_partida='$codpartida1'", $conexion);
   if(mysql_num_rows($sql)!=0){
     if($tcpartida=='Egreso'){$tc=3; }else{$tc=4;}
	 $partidaconc=$_POST['tcpartida'].$_POST['partida1'].".".$_POST['generica'].".".$_POST['especifica'].".".$_POST['subespecifica'];
	 $sql="UPDATE pv_partida 
			 SET denominacion='".utf8_decode($_POST['denominacion'])."',
			     UltimaFecha='$fechaactual',
				 UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."' 
		   WHERE cod_partida='$codpartida1'";
	 $qry=mysql_query($sql) or die ($sql.mysql_error());
   }
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////  ************************  MAESTRO TIPO CUENTA *****************************  /////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////// *****  GUARDAR DATOS TIPO CUENTA ****** ////////////////////////////////////////////
if($accion==guardartcuenta){
   $sql=mysql_query("SELECT * FROM pv_tipocuenta WHERE 
		                   (cod_tipocuenta='".$_POST['tipocuenta']."' AND 
						   descp_tipocuenta='".$_POST['descptipocuenta']."')", $conexion);
   if (mysql_num_rows($sql)!=0){
      echo "<script>";
	  echo "alert('LOS DATOS HAN SIDO INGRESADOS CON ANTERIORIDAD')";
	  echo "</script>";
   }else{
	  $qry=mysql_query("INSERT INTO pv_tipocuenta(cod_tipocuenta,
	                                              descp_tipocuenta, 
	                                              Estado,
												  UltimoUsuario,
												  UltimaFecha) 
						                 VALUES ('".$_POST['tipocuenta']."',
										         '".$_POST['descptipocuenta']."',
									            '".$_POST['status']."',
												'".$_SESSION['USUARIO_ACTUAL']."',
												'$ahora')");
	}
}
///////////////////////////////////////////// ****** EDITAR TIPO CUENTA ***** /////////////////////////////////////////////////
if($accion==editarTcuenta){
	$codigo=$_POST['codtipocuenta'];
	$fechaactua=date("Y-m-d H:m:s");
	$sql=mysql_query("SELECT * FROM pv_tipocuenta WHERE cod_tipocuenta='$codigo'", $conexion);
	if(mysql_num_rows($sql)!=0){
	 $qry=mysql_query("UPDATE pv_tipocuenta 
					  SET 
					   descp_tipocuenta='".$_POST['descripcion']."',
					   cod_tipocuenta='".$_POST['codtipocuenta']."',
					   UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
					   Estado='".$_POST['status']."',
					   UltimaFecha='$fechaactua' 
					  WHERE 
					   cod_tipocuenta='$codigo'");
   }
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////  ************************  MAESTRO PROGRAMA *****************************   ///////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////// *****************  GUARDAR PROGRAMA **************** ///////////////////////////////////////
if($accion==guardarPrograma){
 $sql=mysql_query("SELECT * FROM pv_programa1 WHERE cod_sector='".$_POST['selectSector']."' AND descp_programa='".$_POST['descripcion']."'", $conexion);
 if(mysql_num_rows($sql)!=0){
   echo"<script>";
   echo"alert('LOS DATOS HAN SIDO INGRESADOS CON ANTERIORIDAD')";
   echo"</script>";
 }else{
   $sql1="SELECT MAX(cod_programa) FROM pv_programa1 WHERE cod_sector='".$_POST['selectSector']."'";
   $query=mysql_query($sql1) or die ($sql1.mysql_error());
   $field=mysql_fetch_array($query);
   $codprog=(int) ($field[0]+1);
   $codprog=(string) str_repeat("0",2-strlen($codprog)).$codprog;
   $sidprog=mysql_query("SELECT MAX(id_programa) FROM pv_programa1") or die (mysql_error());
   $fidprog=mysql_fetch_array($sidprog);
   $idprog=(int) ($fidprog[0]+1);
   $idprog=(string) str_repeat("0",4-strlen($idprog)).$idprog;
   $sql=mysql_query("INSERT INTO pv_programa1(id_programa,
                                              cod_programa,
											  descp_programa,
											  cod_sector,
											  Estado,
											  UltimoUsuario,
											  UltimaFecha)
									   VALUES ('$idprog',
									           '$codprog', 
											   '".$_POST['descripcion']."', 
											   '".$_POST['selectSector']."',
											   '".$_POST['status']."',
											   '".$_SESSION['USUARIO_ACTUAL']."', 
											   '$ahora')");
}
////////////////////////////////////////    ****************** EDITAR PROGRAMA ******************   /////////////////////////
  if($accion==editarPrograma){
    $sql="SELECT * FROM pv_programa1 
	              WHERE cod_sector='".$_POST['codsect']."' AND
				        cod_programa='".$_POST['codprograma']."'";
	$qry=mysql_query($sql) or die ($sql.mysql_error());
	if(mysql_num_rows($qry)!=0){
	  $sql=mysql_query("UPDATE pv_programa1 SET descp_programa='".$_POST['descripcionp']."',
	                                            cod_sector='".$_POST['descripcionsector']."',
	  										    UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
											    UltimaFecha='$ahora',
											    Estado='".$_POST['status']."'
										  WHERE cod_sector='".$_POST['codsect']."' AND cod_programa='".$_POST['codprograma']."'");
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
	echo"alert('LOS DATOS HAN SIDO INGRESADOS CON ANTERIORIDAD')";
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
	$sp=mysql_query("SELECT MAX(id_sub) FROM pv_subprog1") or die (mysql_error());
	$fp=mysql_fetch_array($sp);
	$idsub= (int) ($fp[0]+1);
	$idsub=(string) str_repeat("0",4-strlen($idsub)).$idsub;
	
	///////************************** INSERTAMOS LOS DATOS EN LA TABLA *****************************///////
	$sql=mysql_query("INSERT INTO pv_subprog1(id_programa,
	                                          id_sub,
	                                          cod_subprog,
											  descp_subprog,
											  UltimoUsuario,
											  UltimaFecha,
											  Estado) 
									   VALUES ('".$_POST['selectPrograma']."',
									           '$idsub',
									           '$codsubprog',
											   '".$_POST['descripcion']."',
											   '".$_SESSION['USUARIO_ACTUAL']."',
											   '$ahora',
											   '".$_POST['status']."')") or die (mysql_error());
  }
}else{
	if($accion==editarSubprog){///////////////// ************* EDITAR SUB-PROGRAMA *************** //////////////////
	  $codigo=$_POST[idsub];
	  $fechaactual=date("Y-m-d H:m:s");
	  $sql="SELECT * FROM pv_subprog1 WHERE id_sub='$codigo'";
	  $qry=mysql_query($sql) or die ($sql.mysql_error());
	  if(mysql_num_rows($qry)!=0){
	    $sql2="SELECT * FROM pv_programa1 WHERE id_programa='".$_POST['selectPrograma']."'";
		$qry2=mysql_query($sql2) or die ($sql2.mysql_error());
		if(mysql_num_rows($qry2)!=0){
         $field=mysql_fetch_array($qry2);
		 $sql="UPDATE pv_subprog1 SET id_programa='".$_POST['selectPrograma']."',
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
  $result=mysql_query("SELECT * FROM pv_proyecto1 WHERE (id_sub='".$field2['id_sub']."' AND descp_proyecto='".$_POST['descripcion']."')", $conexion);
   if(mysql_num_rows($result)!=0){
       echo "<script>";
       echo "alert('LOS DATOS HAN SIDO INGRESADOS CON ANTERIORIDAD')";
       echo "</script>"; 
   }else{
	   $sql1="SELECT MAX(cod_proyecto) FROM pv_proyecto1 WHERE id_sub='".$_POST['selectSubprog']."'";
	   $query=mysql_query($sql1) or die ($sql1.mysql_error());
	   $field=mysql_fetch_array($query);
	   $codproy=(int) ($field[0]+1);
	   $codproy=(string) str_repeat("0", 2-strlen($codproy)).$codproy;
	   $sp=mysql_query("SELECT MAX(id_proyecto) FROM pv_proyecto1");
	   $fp=mysql_fetch_array($sp);
	   $idproyecto=(int) ($fp[0]+1);
	   $idproyecto=(string) str_repeat("0",4-strlen($idproyecto)).$idproyecto;
	  // INSERTAMOS LOS DATOS EN LA TABLA UNA VEZ COMPROBADOS 
	  $sql="INSERT INTO pv_proyecto1 (id_sub,
	                                  id_proyecto,
	                                  cod_proyecto,
	                                  descp_proyecto,
									  UltimoUsuario, 
									  UltimaFecha, 
									  Estado) 
	                          VALUES ('".$_POST['selectSubprog']."',
							          '$idproyecto',
							          '$codproy',
							          '".$_POST['descripcion']."',
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
	$sql2=mysql_query("SELECT * FROM pv_subprog1 WHERE id_sub='".$_POST['selectSubprograma']."'");
    if(mysql_num_rows($sql2)!=0){
	   $field2=mysql_fetch_array($sql2);
	   $qry=mysql_query("UPDATE pv_proyecto1 SET descp_proyecto='".$_POST['descripcion']."',
	                                             id_sub='".$_POST['selectSubprograma']."',
												 Estado='".$_POST['status']."',
												 UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
											     UltimaFecha='$ahora'
										   WHERE id_proyecto='$codigo'"); 
    }
  }
 }
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////  ************************  MAESTRO ACTIVIDAD *****************************  /////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////// ****** GUARDAR NUEVO ACTIVIDAD ****** /////////////////////////////////////////////
if($accion==guardarActividad){
   $result=mysql_query("SELECT * FROM pv_actividad1 WHERE (id_proyecto='".$_POST['selectProyecto']."' AND descp_actividad='".$_POST['descripcion']."')", $conexion);
   if (mysql_num_rows($result)!=0){
       echo "<script>";
       echo "alert('LOS DATOS HAN SIDO INGRESADOS CON ANTERIORIDAD')";
       echo "</script>"; 
   }else{
      $sqlProyecto=mysql_query("SELECT * FROM pv_proyecto1 WHERE id_proyecto='".$_POST['selectProyecto']."'");
	  if(mysql_num_rows($sqlProyecto)!=0){
	    $fieldProyecto=mysql_fetch_array($sqlProyecto);	
	   }
	  $sql1="SELECT MAX(cod_actividad) FROM pv_actividad1 WHERE id_proyecto='".$_POST['selectProyecto']."'";
      $query=mysql_query($sql1) or die ($sql1.mysql_error());
      $field=mysql_fetch_array($query);
      $codactiv=(int) ($field[0]+1);
      $codactiv=(string) str_repeat("0", 2-strlen($codactiv)).$codactiv;
	  $sp=mysql_query("SELECT MAX(id_actividad) FROM pv_actividad1");
	  $fp=mysql_fetch_array($sp);
	  $idactividad=(int) ($fp[0]+1);
	  $idactividad=(string) str_repeat("0",4-strlen($idactividad)).$idactividad;
	  //////////////////////************************************************************////////////////////////
	  $qry="INSERT INTO pv_actividad1(cod_actividad,
	                                  id_actividad,
									  descp_actividad,
									  id_proyecto,
									  Estado,
									  UltimoUsuario,
									  UltimaFecha)
							  VALUES ('$codactiv',
							          '$idactividad',
									  '".$_POST['descripcion']."',
									  '".$_POST['selectProyecto']."',
									  '".$_POST['status']."',
									  '".$_SESSION['USUARIO_ACTUAL']."',
									  '$ahora')";
	$query=mysql_query($qry) or die ($qry.mysql_error());
}
}
///////////////////////////////////////////// ****** EDITAR ACTIVIDAD ******* ////////////////////////////////////////////////
if($accion==editarActividad){
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
    }
  }
}
///////////////////////////////////////////// ****** ELIMINAR ACTIVIDAD ******* //////////////////////////////////////////////
if($accion==eliminarActividad){
	  $sql="DELETE FROM pv_actividad1 WHERE id_proyecto='".$_POST['registro']."'";
	  $query=mysql_query($sql) or die ($sql.mysql_error());
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
     $idUnidad=(string) str_repeat("0",4-strlen($idUnidad)).$idUnidad;
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
	  if($accion==eliminarUnidad){
		/////////// ******** ELIMINAR REGISTRO DE UNIDAD EJECUTORA ******* /////////////
		$sql="DELETE FROM pv_unidadejecutora WHERE id_unidadejecutora='".$_POST['registro']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	  }
   }
 }
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////  ************PROCESOS MODULO  ANTEPRESUPUESTO ************* /////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($accion==GuardarDatosPres){
   $fecha=date("Y-m-d");
   $sql="SELECT * FROM pv_antepresupuesto WHERE EjercicioPpto='".$_POST['ejercicioPpto']."'";
   $query=mysql_query($sql) or die ($sql.mysql_error());
   $rows=mysql_num_rows($query);
   if($rows!=0){
     echo"<script>";
	 echo"alert('Ya existe un Anteproyecto para el Ejercicio Presupuestario ingresado, dar Click en Aceptar para verlo.')";
	 echo"</script>";
   }else{
     $sql="SELECT MAX(CodAnteproyecto) FROM pv_antepresupuesto";
     $query=mysql_query($sql) or die ($sql.mysql_error());
     $field=mysql_fetch_array($query);
     $idAnte=(int) ($field[0]+1);
     $idAnte=(string) str_repeat("0",4-strlen($idAnte)).$idAnte;
      $fechaCreacion=$_POST['fcreacion']; $fechaCreacion=date("Y-m-d",strtotime($fechaCreacion)); 
	  $fechainicio=$_POST['fdesde']; $fechainicio=date("Y-m-d",strtotime($fechainicio)); 
	  $fechafin=$_POST['fhasta']; $fechafin=date("Y-m-d",strtotime($fechafin)); 
	  if($_POST['fgaceta']!=''){$fgaceta=$_POST['fgaceta']; $fgaceta=date("Y-m-d",strtotime($fgaceta));} 
	  if($_POST['fdecreto']!=''){$fdecreto=$_POST['fdecreto']; $fdecreto=date("Y-m-d",strtotime($fdecreto));}
	 //$fechaInicio=date("Y-m-d", mktime('".$_POST['finicio']."'));
     $sql="INSERT INTO pv_antepresupuesto(CodAnteproyecto,
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
										  FechaPreparacion,
										  NumeroGaceta,
										  FechaGaceta,
										  NumeroDecreto,
										  FechaDecreto,
										  Unidadejecutora) 
	                                VALUES ('$idAnte',
									        '".$_POST['organismo']."',
									        '".$_POST['ejercicioPpto']."',
											'$fechaCreacion',
											'PE',
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
											'$fecha',
											'".$_POST['gaceta']."',
											'$fgaceta',
											'".$_POST['decreto']."',
											'$fdecreto',
											'".$_POST['unidadejecutora']."')";
	 $query=mysql_query($sql) or die ($sql.mysql_error());
   }
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// *******  ACTUALIZAR DATOS EN ENVIADOS DESDE ANTEPROYECTO_EDITARGEN   ******** ////////////////
if($accion==ActualizarAnte){
  $fechaCreacion=$_POST['fcreacion']; $fechaCreacion=date("Y-m-d",strtotime($fechaCreacion)); 
  $fechainicio=$_POST['fdesde']; $fechainicio=date("Y-m-d",strtotime($fechainicio)); 
  $fechafin=$_POST['fhasta']; $fechafin=date("Y-m-d",strtotime($fechafin)); 
  $sqlAct="UPDATE pv_antepresupuesto SET Organismo='".$_POST['organismo']."',
	 										FechaAnteproyecto='$fechaCreacion',
											FechaInicio='$fechainicio',
											FechaFin='$fechafin',
	   										UltimaFecha='$ahora',
											UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."'
									  WHERE CodAnteproyecto='".$_POST['CodAnteproyecto']."'";
  $qryAct=mysql_query($sqlAct) or die ($sqlAct.mysql_error());
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////  ****************  MAESTRO ANTEPRESUPUESTO DETALLE ************* ////////////////////////
if($accion==GuardarMontoPartidas){
 $sql="SELECT * FROM pv_antepresupuesto WHERE EjercicioPpto='".$_POST['ejercicioPpto']."'";
 $qry=mysql_query($sql) or die ($sql.mysql_error());
 if(mysql_num_rows($qry)!=0){
  $field=mysql_fetch_array($qry);
  $sqlDet="SELECT CodAnteproyecto,cod_partida,Estado,Secuencia,MontoPresupuestado
             FROM pv_antepresupuestodet 
	        WHERE CodAnteproyecto='".$field['CodAnteproyecto']."' 
		 ORDER BY cod_partida";
  $query=mysql_query($sqlDet) or die ($sqlDet.mysql_error());
  $rows=mysql_num_rows($query);
  for($i=0; $i<$rows; $i++){
   $fielDet=mysql_fetch_array($query);
   $id=$fielDet['Secuencia'];
   $monto=$_POST[$id];
   $monto=cambioFormato($monto);
   $montoA=$montoA + $monto;
   $sql="UPDATE pv_antepresupuestodet 
            SET MontoPresupuestado='$monto',
                UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
	            UltimaFechaModif='$ahora'
          WHERE Secuencia='$id' AND CodAnteproyecto='".$field['CodAnteproyecto']."' AND Organismo='".$field['Organismo']."'";
   $qry=mysql_query($sql) or die ($sql.mysql_error());
 }
  $sqlP="UPDATE pv_antepresupuesto 
            SET MontoPresupuestado='$montoA',
			    UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
	            UltimaFechaModif='$ahora'
		  WHERE CodAnteproyecto='".$field['CodAnteproyecto']."'";
  $qryP=mysql_query($sqlP) or die ($sqlP.mysql_error());
}}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////  ****************  MAESTRO ANTEPRESUPUESTO DETALLE ************* ////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($accion==GuardarMontoEditado){
 $fechaCreacion=$_POST['fcreacion']; $fechaCreacion=date("Y-m-d",strtotime($fechaCreacion)); 
 $fechainicio=$_POST['fdesde']; $fechainicio=date("Y-m-d",strtotime($fechainicio)); 
 $fechafin=$_POST['fhasta']; $fechafin=date("Y-m-d",strtotime($fechafin));
 $registro= $_POST['registro'];
 $totalAnteproyecto=$_POST['totalAnteproyecto'];
 $totalAnteproyecto=cambioFormato($totalAnteproyecto);
      ///// ***** ACTUALIZACION TABLA PV_ANTEPRESUPUESTO ***** ////// 
 $sqlUpAntepresupuesto="UPDATE pv_antepresupuesto SET Organismo='".$_POST['organismo']."',
	                                                  EjercicioPpto='".$_POST['anop']."',
													  Sector='".$_POST['sector']."',
													  Programa='".$_POST['programa']."',
													  SubPrograma='".$_POST['subprograma']."',
													  Proyecto='".$_POST['proyecto']."',
													  Actividad='".$_POST['actividad']."',
													  UnidadEjecutora='".$_POST['unidadejecutora']."',
													  FechaInicio='$fechainicio',
													  FechaFin='$fechafin',
													  MontoPresupuestado='$totalAnteproyecto',
													  PreparadoPor='".$_POST['prepor']."',
													  UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
													  AprobadoPor='".$_POST['nomempleado']."',
													  UltimaFechaModif='$ahora'
	                                            WHERE CodAnteproyecto='".$_POST['registro']."'"; 
  $qryUpAntepresupuesto=mysql_query($sqlUpAntepresupuesto) or die ($sqlUpAntepresupuesto.mysql_error());
//////////////////////////////////// ***** ACTUALIZACION TABLA PV_ANTEPRESUPUESTODET ***** ///////////////////////////////
 $sql="SELECT * FROM pv_antepresupuesto WHERE CodAnteproyecto='".$_POST['registro']."'";
 $qry=mysql_query($sql) or die ($sql.mysql_error());
 if(mysql_num_rows($qry)!=0){
  $field=mysql_fetch_array($qry);
  $sqlDet="SELECT cod_partida,Estado,Secuencia,MontoPresupuestado 
             FROM pv_antepresupuestodet 
			WHERE CodAnteproyecto='".$field['CodAnteproyecto']."' 
		 ORDER BY cod_partida";
  $query=mysql_query($sqlDet) or die ($sqlDet.mysql_error());
  $rows=mysql_num_rows($query);
  for($i=0; $i<$rows; $i++){
   $fielDet=mysql_fetch_array($query);
   $id=$fielDet['Secuencia'];
   $monto=$_POST[$id];
   $monto=cambioFormato($monto);
   $montoA=$montoA + $monto;
   $sql="UPDATE pv_antepresupuestodet SET MontoPresupuestado='$monto',
                                          UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
									      UltimaFechaModif='$ahora'
								    WHERE Secuencia='$id' AND CodAnteproyecto='".$_POST['registro']."' AND Organismo='".$field['Organismo']."'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
 }
 $sqlA="UPDATE pv_antepresupuesto SET MontoPresupuestado='$montoA' 
                                WHERE Organismo='".$field['Organismo']."' AND 
								      CodAnteproyecto='".$_POST['registro']."'";
 $qryA=mysql_query($sqlA) or die ($sqlA.mysql_error());
 }
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// ****   *****  GUADAR ESTADO REVISADAO DEL ANTEPROYECTO ******** ********** ///////////////////
if($accion==AnteproyectoRevisado){
 $sql="UPDATE pv_antepresupuesto SET  Estado='RV',
                                      UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
									  UltimaFechaModif='$ahora' 
								WHERE CodAnteproyecto='".$_POST['CodAnteproyecto']."'";
 $qry=mysql_query($sql) or die ($sql.mysql_error());
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// ****   *****  GUADAR ESTADO APROBADO DEL ANTEPROYECTO ******** ********** ///////////////////
if($accion==AnteproyectoAprobado){
 $fAprob=date("Y-m-d");
 $sql="UPDATE pv_antepresupuesto pa,pv_antepresupuestodet pdet SET pa.Estado='AP',
                                                           pa.FechaAprobacion='$fAprob',
														   pa.UltimaFechaModif='$ahora',
														   pa.UltimoUsuario ='".$_SESSION['USUARIO_ACTUAL']."',
                                                           pdet.Estado='AP',
														   pdet.UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
														   pdet.UltimaFechaModif='$ahora'
											         WHERE pa.CodAnteproyecto='".$_POST['CodAnteproyecto']."' AND 
													       pdet.CodAnteproyecto='".$_POST['CodAnteproyecto']."'";
 $qry=mysql_query($sql) or die ($sql.mysql_error());
}

////----------------------------------------------------------------------------------------------------------////
///////////////////////////*******   PROCESO GENERRAR ANTEPROYECTO ******/////////////////////////////////////////
////----------------------------------------------------------------------------------------------------------////
if($accion==AnteproyectoGenerar){
  $fecha=date("Y-m-d H:i:s"); 
  $sql="SELECT * FROM pv_antepresupuestodet WHERE CodAnteproyecto='".$_POST['registro']."' ORDER BY cod_partida"; //echo $sql;
  $qry=mysql_query($sql) or die ($sql.mysql_error());
  $rows=mysql_num_rows($qry);
//// *** VERIFICA CUANDO EL MONTO AUTORIZADO ES IGUAL AL MONTO PRESUPUESTADO
if(($_POST['diferencia']='0,00')and($_POST['totalAnteproyecto']==$_POST['m_autorizado'])){
  for($i=0; $i<$rows; $i++){
     $field=mysql_fetch_array($qry);
     $id=$field['Secuencia'];
     //$monto=$_POST[$id];
	 $monto=$field['MontoPresupuestado'];
     //$monto=cambioFormato($monto);
     $sqlG="UPDATE pv_antepresupuestodet 
               SET MontoPresupuestado='$monto',
		           UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
			       UltimaFechaModif='$ahora' 
		     WHERE Secuencia='$id' AND 
		           CodAnteproyecto='".$_POST['reg']."'";
    $qryG=mysql_query($sqlG) or die ($sqlG.mysql_error());
  }
   $montoAut = $_POST['montoautori'];
   $montoAut = cambioFormato($montoAut);
   //// *** SE ACTUALIZAN DATOS EN PV_ANTEPRESUPUESTO
   $sqlP="UPDATE pv_antepresupuesto SET Estado='GE', MontoPresupuestado='$montoAut', FechaGenerado = '".date("Y-m-d")."',
									    UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFechaModif='$fecha'
							      WHERE 
								        CodAnteproyecto='".$_POST['reg']."'";
   $qryP=mysql_query($sqlP) or die ($sqlP.mysql_error());
   
 //// *** CARGA DE LA TABLA PV_PRESUPUESTO
 $sqlAP="SELECT * FROM pv_antepresupuesto WHERE CodAnteproyecto='".$_POST['reg']."'";
 $qryAP=mysql_query($sqlAP) or die ($sqlAP.mysql_error());
 $fieldAP=mysql_fetch_array($qryAP);
 $sql="SELECT MAX(CodPresupuesto) FROM pv_presupuesto";
 $qry=mysql_query($sql) or die ($sql.mysql_error());
 $field=mysql_fetch_array($qry);
 $idP=(int) ($field[0]+1);
 $idP=(string) str_repeat("0",4-strlen($idP)).$idP;
 $sqlP="INSERT INTO pv_presupuesto(Organismo,CodPresupuesto,EjercicioPpto,FechaPresupuesto,
								   FechaInicio,FechaFin,AprobadoPor,PreparadoPor,
								   Estado,UltimoUsuario,UltimaFechaModif,Sector,
								   Programa,SubPrograma,Proyecto,Actividad,
								   MontoAprobado,MontoAjustado,UnidadEjecutora) 
						    VALUES ('".$fieldAP['Organismo']."','$idP','".$fieldAP['EjercicioPpto']."','$fecha',
									'".$fieldAP['FechaInicio']."','".$fieldAP['FechaFin']."','".$fieldAP['AprobadoPor']."','".$fieldAP['PreparadoPor']."',
									'AP','".$_SESSION['USUARIO_ACTUAL']."','".$fieldAP['UltimaFechaModif']."','".$fieldAP['Sector']."',
									'".$fieldAP['Programa']."','".$fieldAP['SubPrograma']."','".$fieldAP['Proyecto']."','".$fieldAP['Actividad']."',
									'$montoAut','$montoAut','".$fieldAP['UnidadEjecutora']."')";
 $qryP=mysql_query($sqlP) or die ($sqlP.mysql_error());
 ///////////////////////////////////////////////////////////////////////////
 //// ***  TRASLADO DEL ANTEPROYECTO APROBADO A PV_PRESUPUESTODET  *** /////
 $ANTE="SELECT * FROM pv_antepresupuestodet WHERE CodAnteproyecto='".$_POST['reg']."'";
 $QANTE=mysql_query($ANTE) or die ($ANTE.mysql_error());
 $RANTE=mysql_num_rows($QANTE);
 for($i=0; $i<$RANTE; $i++){
  $FANTE=mysql_fetch_array($QANTE);
  $PRES="INSERT INTO pv_presupuestodet(Organismo,CodPresupuesto,Secuencia,cod_partida,
									   partida,generica,especifica,subespecifica,
									   tipocuenta,tipo,MontoAprobado,MontoAjustado,
									   Estado,UltimoUsuario,UltimaFechaModif)
                                VALUES('".$FANTE['Organismo']."','$idP','".$FANTE['Secuencia']."','".$FANTE['cod_partida']."',
									   '".$FANTE['partida']."','".$FANTE['generica']."', '".$FANTE['especifica']."','".$FANTE['subespecifica']."',
									   '".$FANTE['tipocuenta']."','".$FANTE['tipo']."','".$FANTE['MontoPresupuestado']."','".$FANTE['MontoPresupuestado']."',
									   'AP', '".$FANTE['UltimoUsuario']."','".$FANTE['UltimaFechaModif']."')";
  $QPRES=mysql_query($PRES) or die ($PRES.mysql_error());
  
  //// Update tabla pv_antepresupuestodet del campo MontoAprobado
  $s_update = "update pv_antepresupuestodet 
                  set MontoAprobado = '$monto'
				where Organismo = '".$FANTE['Organismo']."' and 
				      CodAnteproyecto = '".$FANTE['CodAnteproyecto']."' and 
					  cod_partida = '".$FANTE['cod_partida']."'"; 
  $q_update = mysql_query($s_update) or die ($s_update.mysql_error());
  
 }
}else{
//// *** SE EJECUTA EL PROCESO GENERAR, ACTUALIZANDO LOS DATOS DE LAS TABLAS PV_ANTEPROYECTO,PV_ANTEPROYECTODET *** /////
//// *** SE TRASLADAN LOS DATOS A LAS TABLAS PV_PRESUPUESTO, PV_PRESUPUESTODET *** /////
for($i=0; $i<$rows; $i++){
 
   $field=mysql_fetch_array($qry);
   $id=$field['Secuencia'];
   $monto=$_POST[$id]; //echo "MontoI= ".$monto;
   $monto=cambioFormato($monto);
   $sqlG="UPDATE pv_antepresupuestodet 
             SET Estado='AP',
	  	         UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
			     UltimaFechaModif='$ahora' 
		   WHERE Secuencia='$id' AND 
		         CodAnteproyecto='".$_POST['registro']."'";
  $qryG=mysql_query($sqlG) or die ($sqlG.mysql_error());
  
  /// --- INSERT EN PV_PRESUPUESTODET --- ///
 if(($field['MontoPresupuestado']=='')or($field['MontoPresupuestado']=='0.00')){
  $PRES="INSERT INTO pv_presupuestodet(Organismo,
                                       CodPresupuesto,
									   Secuencia,
									   cod_partida,
									   partida,
									   generica,
									   especifica,
									   subespecifica,
									   tipocuenta,
									   tipo,
									   MontoAprobado,
									   MontoAjustado,
									   Estado,
									   UltimoUsuario,
									   UltimaFechaModif,
									   FlagsAnexa)
                                VALUES('".$field['Organismo']."',
								       '".$field['CodAnteproyecto']."',
									   '".$field['Secuencia']."',
									   '".$field['cod_partida']."',
									   '".$field['partida']."',
									   '".$field['generica']."',
									   '".$field['especifica']."',
									   '".$field['subespecifica']."',
									   '".$field['tipocuenta']."',
									   '".$field['tipo']."',
									   '$monto',
									   '$monto',
									   'AP',
									   '".$field['UltimoUsuario']."',
									   '".$field['UltimaFechaModif']."',
									   'S')";
  $QPRES=mysql_query($PRES) or die ($PRES.mysql_error());
  
  //// Update tabla pv_antepresupuestodet del campo MontoAprobado
  $s_update = "update pv_antepresupuestodet 
                  set MontoAprobado = '$monto'
				where Organismo = '".$field['Organismo']."' and 
				      CodAnteproyecto = '".$field['CodAnteproyecto']."' and 
					  cod_partida = '".$field['cod_partida']."'";
  $q_update = mysql_query($s_update) or die ($s_update.mysql_error());
  
 }else{
   if(($monto!='')and($monto!='0.00')){
   $PRES="INSERT INTO pv_presupuestodet(Organismo,
                                       CodPresupuesto,
									   Secuencia,
									   cod_partida,
									   partida,
									   generica,
									   especifica,
									   subespecifica,
									   tipocuenta,
									   tipo,
									   MontoAprobado,
									   MontoAjustado,
									   Estado,
									   UltimoUsuario,
									   UltimaFechaModif)
                                VALUES('".$field['Organismo']."',
								       '".$field['CodAnteproyecto']."',
									   '".$field['Secuencia']."',
									   '".$field['cod_partida']."',
									   '".$field['partida']."',
									   '".$field['generica']."',
									   '".$field['especifica']."',
									   '".$field['subespecifica']."',
									   '".$field['tipocuenta']."',
									   '".$field['tipo']."',
									   '$monto',
									   '$monto',
									   'AP',
									   '".$field['UltimoUsuario']."',
									   '".$field['UltimaFechaModif']."')";
  $QPRES=mysql_query($PRES) or die ($PRES.mysql_error());
  
  //// Update tabla pv_antepresupuestodet del campo MontoAprobado
  $s_update = "update pv_antepresupuestodet 
                  set MontoAprobado = '$monto'
				where Organismo = '".$field['Organismo']."' and 
				      CodAnteproyecto = '".$field['CodAnteproyecto']."' and 
					  cod_partida = '".$field['cod_partida']."'";
  $q_update = mysql_query($s_update) or die ($s_update.mysql_error());
  
  }
 }
}
 $montoAut = $_POST['montoautori'];
 $montoAut = cambioFormato($montoAut);
 $sqlP="UPDATE pv_antepresupuesto SET Estado='GE',
                                      MontoGenerado='$montoAut',
									  FechaGenerado ='".date("Y-m-d")."',
									  UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
									  UltimaFechaModif='$fecha'
							    WHERE CodAnteproyecto='".$_POST['registro']."'";
 $qryP=mysql_query($sqlP) or die ($sqlP.mysql_error());
 
 
////----------------------------------------------------------------------------------------------------------////
//// 									 CARGANDO TABLA PV_PRESUPUESTO
////----------------------------------------------------------------------------------------------------------////
 $sqlAP="SELECT * FROM pv_antepresupuesto WHERE CodAnteproyecto='".$_POST['registro']."'";
 $qryAP=mysql_query($sqlAP) or die ($sqlAP.mysql_error());
 $fieldAP=mysql_fetch_array($qryAP);
 $sql="SELECT MAX(CodPresupuesto) FROM pv_presupuesto";
 $qry=mysql_query($sql) or die ($sql.mysql_error());
 $field=mysql_fetch_array($qry);
 $idP=(int) ($field[0]+1);
 $idP=(string) str_repeat("0",4-strlen($idP)).$idP;
 $sqlP="INSERT INTO pv_presupuesto(Organismo,
                                   CodPresupuesto,
								   EjercicioPpto,
								   FechaPresupuesto,
								   FechaInicio,
								   FechaFin,
								   AprobadoPor,
								   PreparadoPor,
								   Estado,
								   UltimoUsuario,
								   UltimaFechaModif,
								   Sector,
								   Programa,
								   SubPrograma,
								   Proyecto,
								   Actividad,
								   MontoAprobado,
								   UnidadEjecutora) 
						    VALUES ('".$fieldAP['Organismo']."',
							        '$idP',
									'".$fieldAP['EjercicioPpto']."',
									'$fecha',
									'".$fieldAP['FechaInicio']."',
									'".$fieldAP['FechaFin']."',
									'".$fieldAP['AprobadoPor']."',
									'".$fieldAP['PreparadoPor']."',
									'AP',
									'".$_SESSION['USUARIO_ACTUAL']."',
									'".$fieldAP['UltimaFechaModif']."',
									'".$fieldAP['Sector']."',
									'".$fieldAP['Programa']."',
									'".$fieldAP['SubPrograma']."',
									'".$fieldAP['Proyecto']."',
									'".$fieldAP['Actividad']."',
									'$montoAut',
									'".$fieldAP['UnidadEjecutora']."')";
 $qryP=mysql_query($sqlP) or die ($sqlP.mysql_error());
 
  //// Update tabla pv_antepresupuestodet del campo MontoGenerado
  /*$s_update = "update pv_antepresupuesto 
                  set MontoGenerado = '$montoAut', 
				where Organismo = '".$FANTE['Organismo']."' and 
				      CodAnteproyecto = '$idP'"; echo $s_update;
  $q_update = mysql_query($s_update) or die ($s_update.mysql_error());*/
  
 
}
 ///// PRUEBA
 /*$sql="DELETE FROM pv_antepresupuestodet 
               WHERE CodAnteproyecto='".$_POST['registro']."' AND 
			         Organismo='".$_SESSION['ORGANISMO_ACTUAL']."' AND 
					 MontoPresupuestado='0.00'";
 $qry=mysql_query($sql) or die ($sql.mysql_error());*/
 
}
//// ***************************************************************************////
////                      PROCESO CIERRE MES PRESUPUESTARIO
//// ***************************************************************************////
if($accion == "guardarCierreMesPresupuestario"){
  /// CONSULTA QUE VERIFICA SI EL MES HA SIDO CERRADO CON ANTERIORIDAD
  list($ano, $mes) = split('[-]',$_POST['Periodo']);
 
$sql = "select  
               * 
		 from 
		      pv_ejecucionpresupuestaria 
	    where 
		      Periodo='".$Periodo."' and 
			  (CodOrganismo='".$Organismo."' or  
			  CodPresupuesto='".$CodPresupuesto."')"; //echo $sql;
$qry = mysql_query($sql) or die ($sql.mysql_error());
$row = mysql_num_rows($qry); 

if($row!=0){
   echo "!EL PERIODO QUE INTENTA CERRAR, FUE CERRADO CON ANTERIORIDAD!";
}else{
//// CONSULTA PARA VERIFICAR SI EXISTE EL PRESUPUESTO
$sqlCon = "select 
                 CodPresupuesto,
				 Organismo 
		    from 
			     pv_presupuesto 
		    where
				Organismo = '".$Organismo."' and 
				(EjercicioPpto = '".$EjercicioPpto."' or 
				CodPresupuesto = '".$CodPresupuesto."') and 
				Estado = 'AP'"; //echo $sqlCon;
$qryCon = mysql_query($sqlCon) or die ($sqlCon.mysql_error());
$rowCon = mysql_num_rows($qryCon); //echo $rowCon;
$fieldCon = mysql_fetch_array($qryCon);

$sqlDet="SELECT cod_partida,MontoAprobado,
                partida,generica,especifica,
			    subespecifica,tipocuenta,CodPresupuesto,Organismo 
		   FROM 
		        pv_presupuestodet 
		  WHERE 
		        CodPresupuesto='".$fieldCon['CodPresupuesto']."' and 
				Organismo = '".$fieldCon['Organismo']."'
		  ORDER BY cod_partida"; //echo $sqlDet;
$qryDet=mysql_query($sqlDet) or die ($sqlDet.mysql_error());
$rows=mysql_num_rows($qryDet);
for($i=0; $i<$rows ; $i++){
 $montoReintegro = 0;
 $fieldet=mysql_fetch_array($qryDet);
 $montoIncremento = 0; $montoDisminucion = 0; 
 $montoCompromiso = 0; $montoPptoAjustado = 0;
 $montoCausado = 0;
 $montoPagado = 0;  $montoAjusteP = 0; $montoAjusteN = 0; $montoReintegroT = 0;
 
 /// - CONSULTA PARA OBTENER LOS INCREMENTOS O DISMINUCIONES POPR PARTIDA
 /// ********************************************************************
 
if($mes=='01'){   
	/// MONTO AJUSTE
	//echo "Valor Mes=".$mes;
	  $s_ajuste = "select 
					    aj.CodAjuste,
					    aj.TipoAjuste,
					    aj.Periodo,
					    ajdet.cod_partida,
					    ajdet.MontoAjuste
				  from 				   
						pv_ajustepresupuestario aj
						inner join pv_ajustepresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodAjuste=ajdet.CodAjuste))
				where 
					   aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
					   aj.Periodo = '$Periodo' and 
					   ajdet.cod_partida='".$fieldet['cod_partida']."' and 
					   aj.Organismo='".$fieldet['Organismo']."'";	//echo $s_ajuste;				   	   
	  $q_ajuste = mysql_query($s_ajuste) or die ($s_ajuste.mysql_error());
	  $r_ajuste = mysql_num_rows($q_ajuste);
	
      for($a=0; $a<$r_ajuste; $a++){	
	     $f_ajuste = mysql_fetch_array($q_ajuste); 
	     if($f_ajuste['TipoAjuste']=='IN'){
	       $montoAjusteP = $montoAjuste + $f_ajuste['MontoAjuste'];
	     }else{ 
	       $montoAjusteN = $montoAjuste + $f_ajuste['MontoAjuste'];
		 }
	  }
          $s_Reintegro = "select 
					    aj.CodReintegro,
					    aj.Periodo,
					    ajdet.cod_partida,
					    ajdet.MontoReintegro
				  from 				   
						pv_ReintegroPresupuestario aj
						inner join pv_ReintegroPresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodReintegro=ajdet.CodReintegro))
				where 
					   aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
					   aj.Periodo = '$Periodo' and 
					   ajdet.cod_partida='".$fieldet['cod_partida']."' and 
					   aj.Organismo='".$fieldet['Organismo']."'";	//echo $s_ajuste;				   	   
	  $q_Reintegro = mysql_query($s_Reintegro) or die ($s_Reintegro.mysql_error());
	  $r_Reintegro = mysql_num_rows($q_Reintegro);
	
      for($a=0; $a<$r_Reintegro; $a++){	
	     $f_Reintegro = mysql_fetch_array($q_Reintegro); 
	       $montoReintegroT = $montoReintegroT + $f_Reintegro['MontoReintegro'];
	   
	  }
	  
	  //$montoAjuste = number_format($montoAjuste,2,',','.');
	/// ****************************************************************
	///                     MONTO COMPROMISO
	/// ****************************************************************
	  $s_compromiso = "select
	                         Monto 
						from
						     lg_distribucioncompromisos 
						where 
						      Estado <> 'AN' and 
							  Periodo = '".$Periodo."' and 
							  cod_partida = '".$fieldet['cod_partida']."' and 
							  CodOrganismo = '".$fieldet['Organismo']."'"; //echo $s_compromiso;
	  $q_compromiso = mysql_query($s_compromiso) or die ($s_compromiso.mysql_error());
	  $r_compromiso = mysql_num_rows($q_compromiso);
	  
	  for($b=0;$b<$r_compromiso; $b++){
		$f_compromiso = mysql_fetch_array($q_compromiso);
		if($f_compromiso['Monto']>=0)$montoCompromiso = $montoCompromiso + $f_compromiso['Monto'];
	  }
	  
	 ///$montoCompromiso = number_format($montoCompromiso,2,',','.');
	
	/// ****************************************************************
	///                        MONTO CAUSADO
	/// ****************************************************************
	  $s_causado = "select 
	                     *
					 from
					     ap_distribucionobligacion
					 where 
					     Estado<>'AN' and 
						 Periodo = '".$Periodo."' and 
						 cod_partida = '".$fieldet['cod_partida']."' and 
						 CodOrganismo = '".$fieldet['Organismo']."'";
	$q_causado = mysql_query($s_causado) or die ($s_causado.mysql_error());
	$r_causado = mysql_num_rows($q_causado);
	
	for($c=0; $c<$r_causado; $c++){
	  $f_causado = mysql_fetch_array($q_causado);
	  if($f_causado['Monto']>=0)$montoCausado =  $montoCausado + $f_causado['Monto'];
	}  
	 
	 //$montoCausado  = number_format($montoCausado,2,',','.');
	
	/// ****************************************************************
	///                         MONTO PAGADO
	/// ****************************************************************
	  $s_pagado = "select
	                       *
					  from 
					       ap_ordenpagodistribucion 
					 where
					       Estado = 'PA' and 
						   Periodo = '".$Periodo."' and 
						   cod_partida = '".$fieldet['cod_partida']."' and 
						   CodOrganismo = '".$fieldet['Organismo']."'";
	  $q_pagado = mysql_query($s_pagado) or die ($s_pagado.mysql_error());
	  $r_pagado = mysql_num_rows($q_pagado);
	  
	  /*for($d=0; $d<$r_pagado; $d++){
	    $f_pagado = mysql_fetch_array($q_pagado);
		$montoPagado = $montoPagado + $f_pagado['Monto'];
	  }*/
	   for($d=0; $d<$r_pagado; $d++){
	     $f_pagado = mysql_fetch_array($q_pagado);
		 if($f_pagado['Monto']>=0){
		   $montoPagado = $montoPagado + $f_pagado['Monto'];
		 }else{
		   $r_01 = -1*($f_pagado['Monto']);
		   $montoReintegro = $montoReintegro +  $r_01;
		 }
	   }
    /// ************ Calculo Monto Ajustado / Disponibilidad Presupuestaria / Disponibilidad Financiera
	$montoPptoAjustado = ($fieldet['MontoAprobado']  + $montoAjusteP) - $montoAjusteN; /// MONTO PRESUPUESTO AJUSTADO
	$montoDisponPresupuestaria = ($montoPptoAjustado - $montoCompromiso) + $montoReintegroT; /// MONTO DISPONIBILIDAD PRESUPUESTAERIA
	$montoDisponFinanciera = ( $montoPptoAjustado - $montoPagado) + $montoReintegroT; /// MONTO DISPONIBILIDAD FINANCIERA
	$montoVariacion = $montoDisponFinanciera - $montoDisponPresupuestaria; /// MONTO VARIACION
	
	/// INSERT PARA TABLA PV_EJECUCIONPRESUPUESTARIA
	  $sql1="select
	               MAX(Secuencia) 
			   from 
			       pv_ejecucionpresupuestaria 
			  where 
			       CodOrganismo = '".$fieldet['Organismo']."' and 
				   Periodo = '".$_POST['Periodo']."' and 
				   CodPresupuesto = '".$fieldet['CodPresupuesto']."'";
      $query=mysql_query($sql1) or die ($sql1.mysql_error());
      $field=mysql_fetch_array($query);
      $Secuencia=(int) ($field[0]+1);
      $Secuencia=(string) str_repeat("0",4-strlen($Secuencia)).$Secuencia;
	  
	$s_ejecucion = "insert into pv_ejecucionpresupuestaria(	CodOrganismo,
															Periodo,
															CodPresupuesto,
															CodPartida,
															Secuencia,
															DispPresuInicial,
															DispFinanInicial,
															Reintegro,
															Incremento,
															Disminucion,
															PptoAjustado,
															Compromisos,
															Causado,
															Pagado,
															DispPresupuestaria,
															DispFinanciera,
															Variacion,
															UltimoUsuario,
															UltimaFechaModif)
												     values ('".$fieldet['Organismo']."',
													         '".$_POST['Periodo']."',
															 '".$fieldet['CodPresupuesto']."',
															 '".$fieldet['cod_partida']."',
															 '$Secuencia',
															 '".$fieldet['MontoAprobado']."',
															 '".$fieldet['MontoAprobado']."',
															 '$montoReintegroT',
															 '$montoAjusteP',
															 '$montoAjusteN',
															 '$montoPptoAjustado',
															 '$montoCompromiso',
															 '$montoCausado',
															 '$montoPagado',
															 '$montoDisponPresupuestaria',
															 '$montoDisponFinanciera',
															 '$montoVariacion',
															 '".$_POST['Usuario']."',
															 '".date("Y-m-d H:i:s")."')";
	 $q_ejecucion = mysql_query($s_ejecucion) or die ($s_ejecucion.mysql_error());

}else{
	
   $mesAnterior = $mes - 01; //echo "MesAnterior= ".$mesAnterior;
   if($mesAnterior<'10'){
     $periodoAnterior = $ano.'-'.'0'.$mesAnterior;// echo "periodoAnterior= ".$periodoAnterior;
   }else{
     $periodoAnterior = $ano.'-'.$mesAnterior; //echo "periodoAnterior= ".$periodoAnterior;
   }
   
   $s_ejecucionPresup = "select 
                               DispPresupuestaria,
							   DispFinanciera,
                                                           PptoAjustado
						   from 
						        pv_ejecucionpresupuestaria 
						  where 
						        CodOrganismo = '".$fieldet['Organismo']."' and 
								CodPartida = '".$fieldet['cod_partida']."' and 
								Periodo = '$periodoAnterior'"; //echo $s_ejecucionPresup;
   $q_ejecucionPresup = mysql_query($s_ejecucionPresup) or die ($s_ejecucionPresup.mysql_error());
   $f_ejecucionPresup = mysql_fetch_array($q_ejecucionPresup);
	
   /// MONTO AJUSTE
	  $s_ajuste = "select 
					  aj.CodAjuste,
					  aj.TipoAjuste,
					  aj.Periodo,
					  ajdet.cod_partida,
					  ajdet.MontoAjuste
				  from 				   
						pv_ajustepresupuestario aj
						inner join pv_ajustepresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodAjuste=ajdet.CodAjuste))
				where 
					   aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
					   aj.Periodo = '$Periodo' and 
					   ajdet.cod_partida='".$fieldet['cod_partida']."' and 
					   aj.Organismo='".$fieldet['Organismo']."'";				   	   
	  $q_ajuste = mysql_query($s_ajuste) or die ($s_ajuste.mysql_error());
	  $r_ajuste = mysql_num_rows($q_ajuste);
	
      for($a=0; $a<$r_ajuste; $a++){	
	     $f_ajuste = mysql_fetch_array($q_ajuste); 
	     if($f_ajuste['TipoAjuste']=='IN'){
	       $montoAjusteP = $montoAjusteP + $f_ajuste['MontoAjuste'];
		   $montoIncremento = $f_ajuste['MontoAjuste'];
	     }else{ 
	       $montoAjusteN = $montoAjusteN + $f_ajuste['MontoAjuste'];
		   $montoDisminucion = $f_ajuste['MontoAjuste'];
		 }
	  }
	  $s_Reintegro = "select 
					  aj.CodReintegro,
					  aj.Periodo,
					  ajdet.cod_partida,
					  ajdet.MontoReintegro
				  from 				   
						pv_ReintegroPresupuestario aj
						inner join pv_ReintegroPresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodReintegro=ajdet.CodReintegro))
				where 
					   aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
					   aj.Periodo = '$Periodo' and 
					   ajdet.cod_partida='".$fieldet['cod_partida']."' and 
					   aj.Organismo='".$fieldet['Organismo']."'";				   	   
	  $q_Reintegro = mysql_query($s_Reintegro) or die ($s_Reintegro.mysql_error());
	  $r_Reintegro = mysql_num_rows($q_Reintegro);
	
      for($a=0; $a<$r_Reintegro; $a++){	
	     $f_Reintegro = mysql_fetch_array($q_Reintegro); 
	     
	       $montoReintegroT = $montoReintegroT + $f_Reintegro['MontoReintegro'];
	     
	  }
	/// ************************************************************
	///                     MONTO COMPROMISO
	/// ************************************************************
	  $s_compromiso = "select
	                         Monto 
						from
						     lg_distribucioncompromisos 
						where 
						      Estado <> 'AN' and 
							  Periodo = '".$Periodo."' and 
							  cod_partida = '".$fieldet['cod_partida']."' and 
							  CodOrganismo = '".$fieldet['Organismo']."'"; //echo $s_compromiso;
	  $q_compromiso = mysql_query($s_compromiso) or die ($s_compromiso.mysql_error());
	  $r_compromiso = mysql_num_rows($q_compromiso);
	  
	  for($b=0;$b<$r_compromiso; $b++){
		$f_compromiso = mysql_fetch_array($q_compromiso);
	    if($f_compromiso['Monto']>0)$montoCompromiso = $montoCompromiso + $f_compromiso['Monto'];
	  }
	/// ************************************************************
	///                      MONTO CAUSADO
	/// ************************************************************
	  $s_causado = "select 
	                     *
					 from
					     ap_distribucionobligacion
					 where 
					     Estado<>'AN' and 
						 Periodo = '".$Periodo."' and 
						 cod_partida = '".$fieldet['cod_partida']."' and 
						 CodOrganismo = '".$fieldet['Organismo']."'";
	$q_causado = mysql_query($s_causado) or die ($s_causado.mysql_error());
	$r_causado = mysql_num_rows($q_causado);
	
	  for($c=0; $c<$r_causado; $c++){
	  $f_causado = mysql_fetch_array($q_causado);
	  if($f_causado['Monto']>0)$montoCausado =  $montoCausado + $f_causado['Monto'];
	}  
	/// ************************************************************
	///                      MONTO PAGADO
	/// ************************************************************
	  $s_pagado = "select
	                       *
					  from 
					       ap_ordenpagodistribucion 
					 where
					       Estado = 'PA' and 
						   Periodo = '".$Periodo."' and 
						   cod_partida = '".$fieldet['cod_partida']."' and 
						   CodOrganismo = '".$fieldet['Organismo']."'";
	  $q_pagado = mysql_query($s_pagado) or die ($s_pagado.mysql_error());
	  $r_pagado = mysql_num_rows($q_pagado);
	  for($d=0; $d<$r_pagado; $d++){
	     $f_pagado = mysql_fetch_array($q_pagado);
		 if($f_pagado['Monto']>0){
		   $montoPagado = $montoPagado + $f_pagado['Monto'];
		 }else{
		   $r_01 = -1*($f_pagado['Monto']);
		   $montoReintegro = $montoReintegro +  $r_01;
		 }
	   }
	  
    /// ************ Calculo Monto Ajustado / Disponibilidad Presupuestaria / Disponibilidad Financiera
	/// MONTO PRESUPUESTO AJUSTADO
	$montoPptoAjustadoG = ($f_ejecucionPresup['PptoAjustado'] + $montoAjusteP) - $montoAjusteN; 
	$montoPptoAjustado = ($f_ejecucionPresup['DispPresupuestaria'] + $montoAjusteP) - $montoAjusteN; 
	/// MONTO DISPONIBILIDAD PRESUPUESTAERIA
	$montoDisponPresupuestaria = ($montoPptoAjustado - $montoCompromiso) + $montoReintegroT; 
	/// MONTO DISPONIBILIDAD FINANCIERA
	$montoDisponFinanciera = ( $montoPptoAjustado - $montoPagado) + $montoReintegroT; 
	$montoVariacion = $montoDisponFinanciera - $montoDisponPresupuestaria; /// MONTO VARIACION
	
	
	/// INSERT PARA TABLA PV_EJECUCIONPRESUPUESTARIA
	  $sql1="select
	               MAX(Secuencia) 
			   from 
			       pv_ejecucionpresupuestaria 
			  where 
			       CodOrganismo = '".$fieldet['Organismo']."' and 
				   Periodo = '".$_POST['Periodo']."' and 
				   CodPresupuesto = '".$fieldet['CodPresupuesto']."'";
      $query=mysql_query($sql1) or die ($sql1.mysql_error());
      $field=mysql_fetch_array($query);
      $Secuencia=(int) ($field[0]+1);
      $Secuencia=(string) str_repeat("0",4-strlen($Secuencia)).$Secuencia;
	  
	$s_ejecucion = "insert into pv_ejecucionpresupuestaria(	CodOrganismo,
															Periodo,
															CodPresupuesto,
															CodPartida,
															Secuencia,
															DispPresuInicial,
															DispFinanInicial,
															Reintegro,
															Incremento,
															Disminucion,
															PptoAjustado,
															Compromisos,
															Causado,
															Pagado,
															DispPresupuestaria,
															DispFinanciera,
															Variacion,
															UltimoUsuario,
															UltimaFechaModif)
												     values ('".$fieldet['Organismo']."',
													         '".$_POST['Periodo']."',
															 '".$fieldet['CodPresupuesto']."',
															 '".$fieldet['cod_partida']."',
															 '$Secuencia',
															 '".$f_ejecucionPresup['DispPresupuestaria']."',
															 '".$f_ejecucionPresup['DispFinanciera']."',
															 '$montoReintegroT',
															 '$montoAjusteP',
															 '$montoAjusteN',
															 '$montoPptoAjustadoG',
															 '$montoCompromiso',
															 '$montoCausado',
															 '$montoPagado',
															 '$montoDisponPresupuestaria',
															 '$montoDisponFinanciera',
															 '$montoVariacion',
															 '".$_POST['Usuario']."',
															 '".date("Y-m-d H:i:s")."')";
	 $q_ejecucion = mysql_query($s_ejecucion) or die ($s_ejecucion.mysql_error());
 }
 }
}
}
?> 
