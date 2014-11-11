<?php
include("fphp_lg.php");
connect();
/// ---------------------------------------------------------------------------
///////////////////////////////////////////////////////////////////////////////
//	SCRIPTS PARA AJAX
///////////////////////////////////////////////////////////////////////////////
switch ($modulo) {

//	CLASIFICACION ACTIVO...
case "CLASIFICACION-ACTIVOS":
	//	INSERTAR NUEVO REGISTRO...
	if ($accion == "GUARDAR") {		
		//	Verifico si ya se ingreso su registro padre ...
		if ($nivel == 2 || $nivel == 3) {
			if ($nivel == 2) $codigo_padre = substr($codigo, 0, 2);
			elseif ($nivel == 3) $codigo_padre = substr($codigo, 0, 4);
			
			$sql = "SELECT * FROM af_clasificacionactivo WHERE CodClasificacion = '".$codigo_padre."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query) == 0) die("¡No existe registro para relacionarlo!");
		}
		
		//	Verifico si existe el registro...
		$sql = "SELECT * FROM af_clasificacionactivo WHERE CodClasificacion = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) die("¡Código existente!");
		
		//	Inserto los datos...
		$sql = "INSERT INTO af_clasificacionactivo (CodClasificacion,
													 Descripcion,
													 Nivel,
													 Estado,
													 UltimoUsuario,
													 UltimaFecha)
											VALUES ('".$codigo."',
													'".utf8_decode($descripcion)."',
													'".$nivel."',
													'".$status."',
													'".$_SESSION['USUARIO_ACTUAL']."',
													'".date("Y-m-d H:i:s")."')";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	MODIFICAR REGISTRO...
	elseif ($accion == "ACTUALIZAR") {
		$sql = "UPDATE af_clasificacionactivo SET Descripcion = '".utf8_decode($descripcion)."',
												  Nivel = '".$nivel."',
												  Estado = '".$status."',
												  UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												  UltimaFecha = '".date("Y-m-d H:i:s")."'
											WHERE 
												  CodClasificacion = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	//	ELIMINAR REGISTRO...
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM af_clasificacionactivo WHERE CodClasificacion = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}	
	break;

//	CLASIFICACION ACTIVO SEGUN PUBLICACION 20...
//case "CLASIFICACION-ACTIVOS-20":
	//	INSERTAR NUEVO REGISTRO...
	//if($accion == "GUARDAR") {		
	  
	  /*list($codClasificacion, $codClasificacion) = split('[-]', $_POST['codigo2']);
	  //echo $codClasificacion, $descripcion;
	   $sigue = 0;
	  if($nivel=='1'){
		  echo "Paso 1";
	    $sql = "select max(CodClasificacion) from af_clasificacionactivo20 where Nivel = '".$nivel."'"; //echo $sql;
	    $qry = mysql_query($sql)  or die ($sql.mysql_error());
	    //$row = mysql_num_rows($qry);
	    $field = mysql_fetch_array($qry);
	    echo $field['0'];
	    //$codClasificacion= $field[0]+1; echo $codClasificacion;
		$codClasificacion = (int) ($field[0] + 1); //echo $codClasificacion.'/';
	    $codClasificacion = (string) str_repeat("0",2-strlen($codClasificacion)).$codClasificacion; //echo $cont.'-';
	  }else
	    if($nivel=='2'){ 
		    $cont = '01';
		    echo "Paso 2";
	        while($sigue=='0'){ 
	         //$codClasificacion = $codClasificacion.''.$cont;  //echo $codClasificacion.'*';
			 $sql = "select max(CodClasificacion) from af_clasificacionactivo20 where CodClasificacion like '$codClasificacion%' and Nivel='2'"; echo $sql;
	         $qry = mysql_query($sql)  or die ($sql.mysql_error());
			 $row = mysql_num_rows($qry);
			 if($row==0) $sigue = 1;
	           $cont = (int) ($cont + 1); //echo $cont.'/';
			   $cont = (string) str_repeat("0",2-strlen($cont)).$cont; //echo $cont.'-';
		    }
	  }else
	    if($nivel=='3'){ 
		   $cont = '001';
		   echo "Paso 3";
	       while($sigue=='0'){ 
	         $codClasificacion = $codClasificacion.''.$cont;  echo $codClasificacion.'*';
			 $sql = "select CodClasificacion from af_clasificacionactivo20 where CodClasificacion = '$codClasificacion'"; //echo $sql;
	         $qry = mysql_query($sql)  or die ($sql.mysql_error());
			 $row = mysql_num_rows($qry);
			 if($row==0) $sigue = 1;
	         $cont = (int) ($cont + 1); //echo $cont.'/';
			 $cont = (string) str_repeat("0",3-strlen($cont)).$cont; //echo $cont.'-';
		   }
	    }else
		 if($nivel=='4'){ 
	       $cont = '001';
		   echo "Paso 4";
	       while($sigue=='0'){ 
	         $codClasificacion = $codClasificacion.''.$cont;  //echo $codClasificacion.'*';
			 $sql = "select CodClasificacion from af_clasificacionactivo20 where CodClasificacion = '$codClasificacion'"; echo $sql;
	         $qry = mysql_query($sql)  or die ($sql.mysql_error());
			 $row = mysql_num_rows($qry);
			 if($row==0) $sigue = 1;
	         $cont = (int) ($cont + 1); //echo $cont.'/';
			 $cont = (string) str_repeat("0",3-strlen($cont)).$cont; //echo $cont.'-';
		   }
	     }
	  $s_insert = "INSERT INTO af_clasificacionactivo20 (CodClasificacion,
														 Descripcion,
														 Nivel,
														 Estado,
														 UltimoUsuario,
														 UltimaFecha)
												VALUES ('$codClasificacion',
														'".utf8_decode($descripcion)."',
														'$nivel',
														'".$status."',
														'".$_SESSION['USUARIO_ACTUAL']."',
														'".date("Y-m-d H:i:s")."')";
		$q_insert = mysql_query($s_insert) or die ($s_insert.mysql_error());
	  
	   
	   /*
		//	Verifico si ya se ingreso su registro padre ...
		if ($nivel == 2 || $nivel == 3 || $nivel == 4) {
			if ($nivel == 2) $codigo_padre = substr($codigo, 0, 2);
			elseif ($nivel == 3) $codigo_padre = substr($codigo, 0, 4);
			elseif ($nivel == 4) $codigo_padre = substr($codigo, 0, 6);
			
			$sql = "SELECT * FROM af_clasificacionactivo20 WHERE CodClasificacion = '".$codigo_padre."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query) == 0) die("¡No existe registro para relacionarlo!");
		}
		
		//	Verifico si existe el registro...
		$sql = "SELECT * FROM af_clasificacionactivo20 WHERE CodClasificacion = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) die("¡Código existente!");
		
		//	Inserto los datos...
		$sql = "INSERT INTO af_clasificacionactivo20 (CodClasificacion,
														 Descripcion,
														 Nivel,
														 Estado,
														 UltimoUsuario,
														 UltimaFecha)
												VALUES ('".$codigo."',
														'".utf8_decode($descripcion)."',
														'".$nivel."',
														'".$status."',
														'".$_SESSION['USUARIO_ACTUAL']."',
														'".date("Y-m-d H:i:s")."')";
		$query = mysql_query($sql) or die ($sql.mysql_error());*/
	//}
	
	//	MODIFICAR REGISTRO...
	/*elseif ($accion == "ACTUALIZAR") {
		$sql = "UPDATE af_clasificacionactivo20 SET Descripcion = '".utf8_decode($descripcion)."',
													  Nivel = '".$nivel."',
													  Estado = '".$status."',
													  UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
													  UltimaFecha = '".date("Y-m-d H:i:s")."'
												WHERE 
													  CodClasificacion = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}*/
	//	ELIMINAR REGISTRO...
	//elseif ($accion == "ELIMINAR") {
		
		//$cod = $_POST['codigo'].''.$cont;
		/*$seguir = 0;
		$s_con = "select Nivel from af_clasificacionactivo20 where CodClasificacion = '".$codigo."'";
		$q_con = mysql_query($s_con) or die ($s_con.mysql_error());
		$r_con = mysql_num_rows($q_con);
		 
		if($r_con!=0) $f_con = mysql_fetch_array($q_con);
		echo $f_con['Nivel'].'*';
		if($f_con['Nivel'] == 1) $cod = '01';
		elseif($f_con['Nivel'] == 2) $cod = '001';
		elseif($f_con['Nivel'] == 3) $cod = '001';
		 
		$codigo1 = $_POST['codigo'].''.$cod; echo $codigo1.'/';
		
		if ($f_con['Nivel'] == 4){
			echo "ELIMINANDO ESTO CARAJO";
			$sql1 = "DELETE FROM af_clasificacionactivo20 WHERE CodClasificacion = '".$codigo."'";
		    $query1 = mysql_query($sql1) or die ($sql1.mysql_error());
		}else
		 while(($seguir == 0)and($f_con['Nivel']!=4)){ 
		   
		   $sql = "select * from af_clasificacionactivo20 WHERE CodClasificacion = '$codigo1'"; echo $sql;
		   $query = mysql_query($sql) or die ($sql.mysql_error());
		   $row = mysql_num_rows($query);
           
		   if(($row==0)and($i==10)){
			 $seguir = 1;
			 $sql1 = "DELETE FROM af_clasificacionactivo20 WHERE CodClasificacion = '".$codigo."'";
		     $query1 = mysql_query($sql1) or die ($sql1.mysql_error());
		   }else{
			 if($row!=0){
			   echo "ESTE REGISTRO POSEE DERIVACION";
			   $seguir = 1;	 
			 }else{  
			   $i++; echo $i.'-'; 
		       if($f_con['Nivel'] = 1){    $cod = (int) ($cod + 1); $cod = (string) str_repeat("0",2-strlen($cod)).$cod; $codigo1 = $_POST['codigo'].''.$cod;}
		       elseif($f_con['Nivel'] = 2){$cod = (int) ($cod + 1); $cod = (string) str_repeat("0",3-strlen($cod)).$cod; $codigo1 = $_POST['codigo'].''.$cod;}
		       elseif($f_con['Nivel'] = 3){$cod = (int) ($cod + 1); $cod = (string) str_repeat("0",3-strlen($cod)).$cod; $codigo1 = $_POST['codigo'].''.$cod;}
			 }
		   }
		}
	}	*/
	//break;

//	UBICACION ACTIVOS
case "UBICACION-ACTIVO":
	//	INSERTAR NUEVO REGISTRO...
	if ($accion == "GUARDAR") {
		//	Verifico si existe el registro...
		$sql = "SELECT * FROM af_ubicaciones WHERE CodUbicacion = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) die("¡Código existente!");
		
		//	Inserto los datos...
		$sql = "INSERT INTO af_ubicaciones (
					CodUbicacion,
					Descripcion,
					Estado,
					UltimoUsuario,
					UltimaFecha
				) VALUES (
					'".$codigo."',
					'".utf8_decode($descripcion)."',
					'".$status."',
					'".$_SESSION['USUARIO_ACTUAL']."',
					'".date("Y-m-d H:i:s")."'
				)";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	MODIFICAR REGISTRO...
	elseif ($accion == "ACTUALIZAR") {
		$sql = "UPDATE af_ubicaciones 
				SET 
					Descripcion = '".utf8_decode($descripcion)."',
					Estado = '".$status."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE 
					CodUbicacion = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	ELIMINAR REGISTRO...
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM af_ubicaciones WHERE CodUbicacion = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}	
	break;
}
//// ----------------------------------------------------------------------------
//// 							ELIMINAR REGISTROS
//// ----------------------------------------------------------------------------
if($accion=='ELIMINARREGISTROS'){
  if($modulo==1){
	 $sql="delete from af_activo where Activo='".$codigo."'";
	 $qry= mysql_query($sql) or die ($sql.mysql_error());  
  }
}
//// ----------------------------------------------------------------------------
//// 						CARGAR CATEGORIA DEPRECIACION
//// ----------------------------------------------------------------------------
if($accion=='cargarCampoCategoria'){
    $s_categoria = "select * from af_categoriadeprec where CodCategoria='".$valorEnviado."'";
    $q_categoria = mysql_query($s_categoria) or die ($s_categoria.mysql_error());
    $r_categoria = mysql_num_rows($q_categoria);
	
	if($r_categoria!=0){
	  $f_categoria = mysql_fetch_array($q_categoria);
	  if($f_categoria['GrupoCateg']=='AN'){
		  $grupoCateg ='Activo Normal'; $valorNaturaleza='AN';
	  }else{ 
	     $grupoCateg = 'Activo Menor'; $valorNaturaleza='AM'; 
	  }
	     $valorEnviado = $f_categoria['DescripcionLocal'].'-'.$grupoCateg.'-'.$valorNaturaleza;
	  echo $valorEnviado;
	}
}
//// ----------------------------------------------------------------------------
////     					CARGAR VALOR CATASTRO
//// ----------------------------------------------------------------------------
if($accion=='cargarMontoCatastro'){
 $scatastro = "select 
                     ca.PrecioOficial
			     from
				     af_catastro ct 
				     inner join af_catastroanual ca on (ca.CodCatastro = ct.CodCatastro) 
			    where 
				     ct.CodCatastro = '".$CodCatastro."'";
 $qcatastro = mysql_query($scatastro) or die ($scatastro.mysql_error());
 $rcatastro = mysql_num_rows($qcatastro);
 
 if($rcatastro!=0){
   $fcatastro = mysql_fetch_array($qcatastro);
   $CodCatastro = number_format($fcatastro['0'],2,',','.');
   echo $CodCatastro;  
 }
}
//// ----------------------------------------------------------------------------
////       CARGAR NOMBRE DE ORGANISMO EN MOVIMIENDO DE ACTIVOS
//// ----------------------------------------------------------------------------
if($accion=='cargarOrganismoMovimiento'){
 $sql = "select * from mastorganismos  where CodOrganismo = '".$organismoActual."'";
 $qry = mysql_query($sql) or die ($sql.mysql_error());
 $row = mysql_num_rows($qry);
 
 if($row!=0){
   $field = mysql_fetch_array($qry);
   $organismo = $field['Organismo'];
   echo $organismo;  
 }
}
//// ----------------------------------------------------------------------------
////                      TIPO DE MOVIMIENTO DE ACTIVOS
//// ----------------------------------------------------------------------------
if($accion=='GuardarTipoMovimiento'){
  $s_con = "select * from af_tipomovimientos where CodTipoMovimiento = '".$codigo."'";
  $q_con = mysql_query($s_con) or die ($s_con.mysql_error());
  $r_con = mysql_num_rows($q_con);
  
  if($r_con!=0) echo"¡EL CODIGO INTRODUCIDO YA EXISTE!";
  else{
	if($_POST['codigo']==''){}  
    $s_insert = "insert into af_tipomovimientos(CodTipoMovimiento,
												TipoMovimiento,
												DescpMovimiento,
												Estado,
												UltimoUsuario,
												UltimaFechaModif) 
										  values('".$_POST['codigo']."',
										  		 '".$_POST['t_movimiento']."',
												 '".$_POST['descripcion']."',
												 '".$_POST['status']."',
												 '".$_SESSION['USUARIO_ACTUAL']."',
												 '".date("Y-m-d H:i:s")."')";
	$q_insert = mysql_query($s_insert) or die ($s_insert.mysql_error());
  }
}
//// ----------------------------------------------------------------------------
//// ----------------------------------------------------------------------------
if($accion == 'EditarTipoMovimiento'){}
?>