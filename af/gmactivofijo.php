<?
include("fphp.php");

function rellenarConCero($cadena, $cantidadRelleno)

{

	$cantidadCadena = strlen($cadena);

	

	for($i = 0; $i < ($cantidadRelleno-$cantidadCadena); $i++)

	{

			$cadena = '0'.$cadena;

		

	}			

	

	return $cadena;

}

$year_completa= date("Y-m-d H:i:s");
//// _____________________________________________________________
////               GUARDAR CATEGORIAS DEPRECIACION            ////
//// _____________________________________________________________
/// FUNCION PARA INSERTAR LINEAS EN CATEGORIAS NUEVA 
if ($accion == "insertarLineaCatNueva") {
connect();	
    $sa = "select * from ac_contabilidades";
    $qa = mysql_query($sa) or die ($sa.mysql_error());
    $ra = mysql_num_rows($qa);
    ?>
	<td>
		<select name="select1" style="width:100%;">
        <?
         if($ra!=0){
           for($i=0;$i<$ra;$i++){
            $fa = mysql_fetch_array($qa); 
        ?>
			<option value="<?=$fa['CodContabilidad']?>"><?=$fa['Descripcion']?></option>
        <? }}?>  
		</select>	
	</td>
	<td>
		<input type="text" name="descripcion" style="width:100%;"/>
	</td>
	<?
}
//// _____________________________________________________________
  //                  GUARDAR NUEVA CATEGORIA                   //
//// _____________________________________________________________
if($accion=="guardarNuevaCategoria"){
 connect();
 $sql="SELECT * FROM af_categoriadeprec WHERE CodCategoria='".$_POST['codcategoria']."'";
 $qry=mysql_query($sql) or die ($sql.mysql_error());
 $row=mysql_num_rows($qry);
 if($row!=0){
    echo"¡LOS DATOS HAN SIDO INTRODUCIDOS ANTERIORMENTE¡";
 }else{
  $insert="INSERT INTO af_categoriadeprec (CodCategoria,
										  DescripcionLocal,
										  CuentaHistorica,
										  CuentaHistoricaVariacion,
										  CuentaHistoricaRevaluacion,
										  CuentaDepreciacion,
										  CuentaDepreciacionVariacion,
										  CuentaDepreciacionRevaluacion,
										  CuentaGastos,
										  CuentaGastosRevaluacion,
										  CuentaNeto,
										  CuentaREI,
										  CuentaResultado,
										  InventariableFlag,
										  GrupoCateg,
										  TipoDepreciacion,
										  Estado,
										  UltimoUsuario,
										  UltimaFechaModif) 
								 VALUES ('".$_POST['codcategoria']."',
										 '".$_POST['descp_local']."',
										 '".$_POST['v_historico']."',
										 '".$_POST['cc_adiciones']."',
										 '".$_POST['cc_ajinflacion']."',
										 '".$_POST['cd_pdepreciacion']."',
										 '".$_POST['cd_adiciones']."',
										 '".$_POST['cd_ajinflacion']."',
										 '".$_POST['cg_depreciacion']."',
										 '".$_POST['cg_ajinflacion']."',
										 '".$_POST['occ_valorneto']."',
										 '".$_POST['occ_rei']."',
										 '".$_POST['occ_ctaresultado']."',
										 '".$_POST['cat_invent']."',
										 '".$_POST['g_categoria']."',
										 '".$_POST['t_depreciacion']."',
								        '".$_POST['radioEstado']."',
										'".$_SESSION['USUARIO_ACTUAL']."',
										'".date("Y-m-d H:i:s")."')";
   $qinsert=mysql_query($insert) or die ($insert.mysql_error());
   
   //// _______________________________________________
  ////       GUARDAR CATEGORIA CONTABILIDAD       ////
  //// _______________________________________________
  
  $linea = split(";", $detalles);
	  foreach ($linea as $registro) {
		list($codContabilidad, $depreciacion) = SPLIT( '[|]', $registro);
		
		$sqlin="INSERT INTO af_categoriacontabilidad(CodCategoria,
													CodContabilidad,
													DepreciacionPorcentaje) 
											 VALUES ('".$_POST['codcategoria']."',
													'$codContabilidad',
													'$depreciacion')";
       $qryin=mysql_query($sqlin) or die ($sqlin.mysql_error());
	  }
 }
 //echo "";
}
//// _____________________________________________________________
////               EDITAR CATEGORIAS DEPRECIACION             ////
//// _____________________________________________________________
if($accion=="editarCategoria"){
 connect();	
 $sql="UPDATE af_categoriadeprec SET DescripcionLocal='".$_POST['descp_local']."',
									  CuentaHistorica= '".$_POST['v_historico']."',
									  CuentaHistoricaVariacion='".$_POST['cc_adiciones']."',
									  CuentaHistoricaRevaluacion='".$_POST['cc_ajinflacion']."',
									  CuentaDepreciacion='".$_POST['cd_pdepreciacion']."',
									  CuentaDepreciacionVariacion='".$_POST['cd_adiciones']."',
									  CuentaDepreciacionRevaluacion='".$_POST['cd_ajinflacion']."',
									  CuentaGastos='".$_POST['cg_depreciacion']."',
									  CuentaGastosRevaluacion='".$_POST['cg_ajinflacion']."',
									  CuentaNeto='".$_POST['occ_valorneto']."',
									  CuentaREI= '".$_POST['occ_rei']."',
									  CuentaResultado='".$_POST['occ_ctaresultado']."',
									  InventariableFlag='".$_POST['cat_invent']."',
									  GrupoCateg='".$_POST['g_categoria']."',
									  TipoDepreciacion='".$_POST['t_depreciacion']."',
									  Estado='".$_POST['radioEstado']."',
									  UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
									  UltimaFechaModif='".date("Y-m-d H:i:s")."'
							   WHERE  
							          CodCategoria='".$_POST['codcategoria']."'";
 $qry=mysql_query($sql) or die ($sql.mysql_error());
 
  //// ____________________________________________
  ////      EDITAR CATEGORIA CONTABILIDAD      ////
  //// ____________________________________________
 	  $s_delete = "delete from  af_categoriacontabilidad where CodCategoria='".$_POST['codcategoria']."'";
	  $q_delete = mysql_query($s_delete) or die ($s_delete.mysql_error());
	  
	  $linea = split(";", $detalles);
	  foreach ($linea as $registro) {
		list($codContabilidad, $depreciacion) = SPLIT( '[|]', $registro);
		
		$sqlin="INSERT INTO af_categoriacontabilidad(CodCategoria,
													CodContabilidad,
													DepreciacionPorcentaje) 
											 VALUES ('".$_POST['codcategoria']."',
													'$codContabilidad',
													'$depreciacion')";
       $qryin=mysql_query($sqlin) or die ($sqlin.mysql_error());
	  }
	  
}
//// _____________________________________________________________
////               GUARDAR LIBRO CONTABLE                     ////
//// _____________________________________________________________
if($accion==guardarLibroContable){
  connect();
  $sql="SELECT * FROM ac_librocontable 
                WHERE CodLibroCont='".$_POST['cod_librocontable']."' OR
				      Descripcion='".$_POST['descp_libro']."'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
  $row=mysql_num_rows($qry);
  if($row==0){
   $insert="INSERT INTO ac_librocontable (CodLibroCont,
                                          Descripcion,
										  Estado,
										  UltimoUsuario,
										  UltimaFechaModif) 
								  VALUES ('".$_POST['cod_librocontable']."',
								         '".$_POST['descp_libro']."',
										 '".$_POST['radioEstado']."',
										 '".$_SESSION['USUARIO_ACTUAL']."',
										 '".date("Y-m-d H:i:s")."')";
   $qinsert=mysql_query($insert) or die ($insert.mysql_error());
  }else{
   //echo"<script>";
   echo"¡LOS DATOS HAN SIDO INTRODUCIDOS ANTERIORMENTE¡";
   /*echo"</script>";*/
  }
}
//// _____________________________________________________________
////               EDITAR LIBRO CONTABLE                      ////
//// _____________________________________________________________
if($accion==editarLibroContable){
 connect();
 $supdate="UPDATE ac_librocontable SET   Descripcion='".$_POST['descp_libro']."',
										 Estado='".$_POST['radioEstado']."',
										 UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
										 UltimaFechaModif='".date("Y-m-d H:i:s")."' 
								 WHERE  
								         CodLibroCont='".$_POST['registro']."'";
  $qupdate=mysql_query($supdate) or die ($supdate.mysql_error());
}
//// _____________________________________________________________
////               GUARDAR CONTABILIDADES                     ////
//// _____________________________________________________________
if($accion==guardarContabilidades){
  connect();	
  $sql="SELECT * FROM 
                     ac_contabilidades 
                WHERE 
				     CodContabilidad='".$_POST['cod_contabilidad']."' OR
				     Descripcion='".$_POST['descp_contabilidad']."'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
  $row=mysql_num_rows($qry);
  if($row==0){
   $insert="INSERT INTO ac_contabilidades(CodContabilidad,
                                         Descripcion,
										 Estado,
										 UltimoUsuario,
										 UltimaFechaModif) 
								 VALUES ('".$_POST['cod_contabilidad']."',
								        '".$_POST['descp_contabilidad']."',
										'".$_POST['radioEstado']."',
										'".$_SESSION['USUARIO_ACTUAL']."',
										'".date("Y-m-d H:i:s")."')";
   $qinsert=mysql_query($insert) or die ($insert.mysql_error());
  }else{
   echo"¡LOS DATOS HAN SIDO INTRODUCIDOS ANTERIORMENTE¡";
   die;
  }
  
  $linea = split(";", $detalles);
  foreach($linea as $registro){
	  //$cont++; echo "cont=".$cont;
	  list($l_contable, $codigo) = SPLIT( '[|]', $registro);
      
	  $s_insert = "insert into ac_librocontabilidades (CodContabilidad,
	  												   CodLibroCont) 
												values('".$_POST['cod_contabilidad']."',
												       '$l_contable')";
	  $q_insert = mysql_query($s_insert) or die ($s_insert.mysql_error());
  }
}
//// _____________________________________________________________
////               EDITAR CONTABILIDADES                     ////
if($accion==editarContabilidades){
  connect();	
   /// Actualización de tabla AC_CONTABILIDADES
   $s_update = "update ac_contabilidades set Descripcion = '".$descp_contabilidad."',
		                                          Estado = '".$radioEstado."',
												  UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												  UltimaFechaModif = '".date("Y-m-d H:i:s")."'
											 where
											      CodContabilidad = '".$cod_contabilidad."' ";
  $q_update = mysql_query($s_update) or die ($s_update.mysql_error());

  /// Actualizo tabla AC_LIBROCONTABILIDADES
   $s_delete = "delete from  af_librocontabilidades where CodContabilidad='".$cod_contabilidad."'";
   $q_delete = mysql_query($s_delete) or die ($s_delete.mysql_error());
  
  
  $linea = split(";", $detalles);
  foreach($linea as $registro){
	  //$cont++; echo "cont=".$cont;
	  list($l_contable, $codigo) = SPLIT( '[|]', $registro);
	  
	    $s_insert = "insert into ac_librocontabilidades (CodContabilidad,
	  												   CodLibroCont) 
												values('".$_POST['cod_contabilidad']."',
												       '$l_contable')";
 	    $q_insert = mysql_query($s_insert) or die ($s_insert.mysql_error());
  }
}
//// _____________________________________________________________
////               GUARDAR SITUACION DEL ACTIVO               ////
//// _____________________________________________________________
if($accion==guardarSituactivo){
  connect();  
  $sql="SELECT * 
          FROM 
               af_situacionactivo 
         WHERE 
               CodSituActivo='".$_POST['cod_situactivo']."' AND
               Descripcion='".$_POST['descp_situactivo']."'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
  $row=mysql_num_rows($qry);
  if($row==0){
   $insert="INSERT INTO af_situacionactivo(CodSituActivo,
                                         Descripcion,
										 DepreciacionFlag,
										 RevaluacionFlag,
										 Estado,
                                         UltimoUsuario,
                                         UltimaFechaModif) 
								 VALUES ('".$_POST['cod_situactivo']."',
								        '".$_POST['descp_situactivo']."',
										'".$_POST['proceso_situactivo']."',
										'".$_POST['proceso_ajuste']."',
										'".$_POST['status_situactivo']."',
                                        '".$_SESSION['USUARIO_ACTUAL']."',
                                        '".date("Y-m-d H:i:s")."')";
   $qinsert=mysql_query($insert) or die ($insert.mysql_error());
  }else{
   echo"<script>";
   echo"alert('¡LOS DATOS HAN SIDO INTRODUCIDOS ANTERIORMENTE¡')";
   echo"</script>";
  }
}
//// _____________________________________________________________
////               EDITAR SITUACION DEL ACTIVO                ////
//// _____________________________________________________________
if($accion==editarSituactivo){
    connect(); 
   $supdate="UPDATE af_situacionactivo SET Descripcion='".$_POST['descp_situactivo']."',
											 DepreciacionFlag='".$_POST['proceso_situactivo']."',
											 RevaluacionFlag='".$_POST['proceso_ajuste']."',
											 Estado='".$_POST['status_situactivo']."',
                                             UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
                                             UltimaFechaModif='".date("Y-m-d H:i:s")."' 
									   WHERE 
                                             CodSituActivo='".$_POST['cod_situactivo']."' ";
   $qupdate=mysql_query($supdate) or die ($supdate.mysql_error());
}
//// _____________________________________________________________
////               GUARDAR TIPO SEGURO                        ////
//// _____________________________________________________________
if($accion==guardarTseguro){
  $sql="SELECT * FROM af_tiposeguro 
                WHERE CodTiposeguro='".$_POST['cod_tseguro']."' AND
				      Descripcion='".$_POST['descp_tseguro']."'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
  $row=mysql_num_rows($qry);
  if($row==0){
   $insert="INSERT INTO af_tiposeguro(CodTipoSeguro,
									 Descripcion,
									 Estado) 
							  VALUES ('".$_POST['cod_tseguro']."',
									'".$_POST['descp_tseguro']."',
									'".$_POST['status_tseguro']."')";
   $qinsert=mysql_query($insert) or die ($insert.mysql_error());
  }else{
   echo"<script>";
   echo"alert('¡LOS DATOS HAN SIDO INTRODUCIDOS ANTERIORMENTE¡')";
   echo"</script>";
}
}
//// _____________________________________________________________
////               EDITAR TIPO SEGURO                        ////
//// _____________________________________________________________
if($accion==editarTseguro){
 $supdate="UPDATE af_tiposeguro SET  
                                   Descripcion='".$_POST['descp_tseguro']."',
								   Estado='".$_POST['status_tseguro']."',
								   UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
								   UltimaFechaModif='$year_completa' 
						     WHERE  
							       CodTipoSeguro='".$_POST['registro']."'";
 $qupdate=mysql_query($supdate) or die ($supdate.mysql_error());
}
//// _____________________________________________________________
////               GUARDAR TIPO VEHICULOS                     ////
//// _____________________________________________________________
if($accion==guardarTvehiculo){
  $sql="SELECT * FROM af_tipovehiculo 
                WHERE CodTipoVehiculo='".$_POST['cod_tvehiculo']."' AND
				      Descripcion='".$_POST['descp_tvehiculo']."'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
  $row=mysql_num_rows($qry);
  if($row==0){
   $insert="INSERT INTO af_tipovehiculo(CodTipoVehiculo,
									   Descripcion,
									   Estado) 
							   VALUES ('".$_POST['cod_tvehiculo']."',
									  '".$_POST['descp_tvehiculo']."',
									  '".$_POST['status_tvehiculo']."')";
   $qinsert=mysql_query($insert) or die ($insert.mysql_error());
  }else{
   echo"<script>";
   echo"alert('¡LOS DATOS HAN SIDO INTRODUCIDOS ANTERIORMENTE¡')";
   echo"</script>";
}
}
//// _____________________________________________________________
////               EDITAR TIPO SEGURO                        ////
//// _____________________________________________________________
if($accion==editarTvehiculo){
 $supdate="UPDATE af_tipovehiculo SET  
                                   Descripcion='".$_POST['descp_tvehiculo']."',
								   Estado='".$_POST['status_tvehiculo']."',
								   UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
								   UltimaFechaModif='$year_completa' 
						     WHERE  
							       CodTipoVehiculo='".$_POST['registro']."'";
 $qupdate=mysql_query($supdate) or die ($supdate.mysql_error());
}
//// _____________________________________________________________
////               GUARDAR POLIZA DE SEGURO                   ////
//// _____________________________________________________________
if($accion==guardarPseguro){
  $sql="SELECT * FROM af_polizaseguro 
                WHERE CodPolizaSeguro='".$_POST['cod_pseguro']."' AND 
				      DescripcionLocal='".$_POST['descp_pseguro']."'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
  $row=mysql_num_rows($qry); 
  if($row==0){
    $sin="INSERT INTO af_polizaseguro (CodPolizaSeguro,
	                                   DescripcionLocal,
									   EmpresaAseguradora,
									   MontoCobertura,
									   AgenteSeguros,
									   FechaVencimiento,
									   costoPoliza,
									   Estado) 
							   VALUES  ('".$_POST['cod_pseguro']."',
							            '".$_POST['descp_pseguro']."',
										'".$_POST['empa_pseguro']."',
										'".$_POST['ages_pseguro']."',
										'".$_POST['fvenc_pseguro']."',
										'".$_POST['mcober_pseguro']."',
										'".$_POST['cpoli_pseguro']."',
										'".$_POST['status_tvehiculo']."')";
	$qin=mysql_query($sin) or die ($sin.mysql_error());echo $sin;
  }
  
}
//// _____________________________________________________________________
////               GUARDAR CATASTRO                           ////
//// _____________________________________________________________________
//// _____________________________________________________________________
        /// MAESTRO DE CATASTRO INSERTANDO FILAS 
//// ---------------------------------------------------------------------
if ($accion == "insertarLinea") {
	?>
	<td><input type="hidden" name="id" id="id">
        <input type="text" name="ano" id="ano" style="width:100%; text-align:center"></td>
    <td><input type="text" name="precio_Oficial" id="precio_Oficial" style="width:100%; text-align:right"></td>
    <td><input type="text" name="precio_Mercado" id="precio_Mercado" style="width:100%; text-align:right"></td>
    <td><input type="text" name="fecha_Referencial" id="fecha_Referencial" style="width:100%; text-align:center"></td>
	<?
}
//// ---------------------------------------------------------------------
//// GUARDAR REGISTRO DE CATASTRO
if ($accion==guardarCatastro) {
	connect();
	/// ------ Consulta para generar código de catastro
	$scon = "select max(CodCatastro) from af_catastro";
	$qcon = mysql_query($scon) or die ($scon.mysql_error());
	$rcon = mysql_num_rows($qcon); //echo $rcon;																						
	
	if($rcon!=0){
	   $fcon = mysql_fetch_array($qcon);
	   //$contador = $contador + 1; echo $contador;
	   $cod_catastro = (int) ($fcon[0]+1);
	   $cod_catastro = (string) str_repeat("0",8-strlen($cod_catastro)).$cod_catastro;
	   
	   $sql = "INSERT INTO af_catastro (CodCatastro, 
	                                    Descripcion, 
									    Estado,
										UltimoUsuario,
										UltimaFechaModif) 
							   VALUES ('$cod_catastro',
							           '$descp_catastro',
								 	   '$radioEstado',
									   '".$_SESSION['USUARIO_ACTUAL']."',
									   NOW())";
      $query_insert = mysql_query($sql) or die ($sql.mysql_error());		
	  
	  
	  //	detalles
	  $linea = split(";", $detalles);
	  foreach ($linea as $registro) {
		list($ano, $precio_Oficial, $precio_Mercado, $fecha_Referencial) = SPLIT( '[|]', $registro);
		
		$s_catanual = "select max(IdCatastroAnual) from af_catastroanual";
		$q_catanual = mysql_query($s_catanual) or die ($s_catanual.mysql_error());
		$f_catanual = mysql_fetch_array($q_catanual);
		
		$id_catastroanual = (int) ($f_catanual['0']+1);
		$id_catastroanual = (string) str_repeat("0",5-strlen($id_catastroanual)).$id_catastroanual;
		
		list($d, $m, $a)=SPLIT( '[-]', $fecha_Referencial); $f_referencial=$a.'-'.$m.'-'.$d;
				
		$s_insert = "INSERT INTO af_catastroanual (IdCatastroAnual,
												  CodCatastro, 
												  Ano, 
												  PrecioOficial,
												  PrecioMercado,
												  FechaReferencia,
												  UltimoUsuario,
												  UltimaFechaModif) 
										  VALUES ('$id_catastroanual',
												  '$cod_catastro',
												  '$ano', 
												  '$precio_Oficial', 
												  '$precio_Mercado',
												  '$f_referencial',
												  '".$_SESSION['USUARIO_ACTUAL']."',
												  NOW())";
	   $q_insert = mysql_query($s_insert) or die ($s_insert.mysql_error());
	}
  }
}
//// EDITAR CATASTRO
if ($accion==guardarCatastroEditar){
  connect();
  
  $supdate = "update af_catastro set Descripcion ='".$_POST['descp_catastro']."' ,
                                     Estado = '".$_POST['radioEstado']."',
									 UltimaFechaModif = '".date("Y-m-d H:i:s")."', 
									 UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."'
								where 
								     CodCatastro = '".$_POST['cod_catastro']."'";
  $qupdate = mysql_query($supdate) or die ($supdate.mysql_error());
  
   $linea = split(";", $detalles);
	  foreach ($linea as $registro) {
		list($id, $ano, $precio_Oficial, $precio_Mercado, $fecha_Referencial) = SPLIT( '[|]', $registro);
		
		$s_conexiste = "select * from af_catastroanual where IdCatastroanual ='$id' and CodCatastro = '".$_POST['cod_catastro']."' ";
		$q_conexiste = mysql_query($s_conexiste) or die ($s_conexiste.mysql_error());
		$r_conexiste = mysql_fetch_array($q_conexiste);
		
		
		/// ------------------ 
		if($r_conexiste!=0){
		  $f_conexiste = mysql_fetch_array($q_conexiste);
		  if(($f_conexiste['Ano']!=$ano)or($f_conexiste['PrecioOficial']!=$precio_Oficial)or($f_conexiste['PrecioMercado']!=$precio_Mercado)or($f_conexiste['FechaReferencia']!=$fecha_Referencial)){
		   
		     list($d, $m, $a)=SPLIT( '[-]', $fecha_Referencial); $f_referencia=$a.'-'.$m.'-'.$d;
			 
		     $s_update = "update af_catastroanual set Ano ='$ano',
			                                         PrecioOficial = '$precio_Oficial',
													 PrecioMercado = '$precio_Mercado',
													 FechaReferencia = '$f_referencia',
													 UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
													 UltimaFechaModif = '".date("Y-m-d H:i:s")."'
												 where
												     CodCatastro = '".$_POST['cod_catastro']."' and 
													 IdCatastroanual = '$id'";
			 $q_update = mysql_query($s_update) or die ($s_update.mysql_error());
		  }
		}else{
		  		
		$s_catanual = "select max(IdCatastroAnual) from af_catastroanual";
		$q_catanual = mysql_query($s_catanual) or die ($s_catanual.mysql_error());
		$f_catanual = mysql_fetch_array($q_catanual);
		
		$id_catastroanual = (int) ($f_catanual['0']+1);
		$id_catastroanual = (string) str_repeat("0",5-strlen($id_catastroanual)).$id_catastroanual;
		
		list($d, $m, $a)=SPLIT( '[-]', $fecha_Referencial); $f_referencia=$a.'-'.$m.'-'.$d;
				
		$s_insert = "INSERT INTO af_catastroanual (IdCatastroAnual,
												  CodCatastro, 
												  Ano, 
												  PrecioOficial,
												  PrecioMercado,
												  FechaReferencia,
												  UltimoUsuario,
												  UltimaFechaModif) 
										  VALUES ('$id_catastroanual',
												  '$cod_catastro',
												  '$ano', 
												  '$precio_Oficial', 
												  '$precio_Mercado',
												  '$f_referencia',
												  '".$_SESSION['USUARIO_ACTUAL']."',
												  NOW())";
	   $q_insert = mysql_query($s_insert) or die ($s_insert.mysql_error());
	  }
	}
}
//// _____________________________________________________________________
//// CARGAR SELECT PARA DEPENDENCIAS EN TRANSFERIRDATOSGENERALES.PHP
//// _____________________________________________________________________
if($_POST['accion']=="obtenerDep") {
	if($_POST['tabla']=="dependencia") {
	  echo"
	   <select name='dependencia' id='dependencia' class='selectBig'>
			<option value=''>";
				getDependenciaTransferir("", $_POST['opcion']);
		echo "</select>";
	}
}
//// _____________________________________________________________________
//// 					CARGAR CAMPO CATEGORIA
//// _____________________________________________________________________
if($accion==cargarCampoCategoria){
  $s_categoria = "select * from af_categoriadeprec where CodCategoria = '".$valorEnviado."'";
  $q_categoria = mysql_query($s_categoria) or die ($s_categoria.mysql_error());
  $r_categoria = mysql_num_rows($q_categoria);
  
  if($r_categoria!=0){ $f_categoria = mysql_fetch_array($q_categoria); echo"<input type='text' id ";}
  
}
//// ---------------------------------------------------------------------
if($accion==EliminarCatastroEditado){
    connect();
   $s_delete = "delete from af_catastroanual where  IdCatastroanual  = '".$id_catanual."' and CodCatastro = '".$cod_catastro."'";
   $q_delete = mysql_query($s_delete) or die ($s_delete.mysql_error());
   
}
//// _____________________________________________________________________
//// 						GUARDAR TIPO SEGURO
//// _____________________________________________________________________
if($accion==guardarTipoSeguro){
   connect();	
   $sql = "insert into af_tiposeguro(CodTipoSeguro,
									 Descripcion,
									 Estado,
									 UltimoUsuario,
									 UltimaFechaModif)
								values('".$_POST['cod_tseguro']."',
									  '".$_POST['descp_tseguro']."',
									  '".$_POST['radioEstado']."',
									  '".$_SESSION['USUARIO_ACTUAL']."',
									  '".date("Y-m-d H:i:s")."')";
  $qry = mysql_query($sql) or die ($sql.mysql_error());
}
//// _____________________________________________________________________
//// 					ELIMINAR TIPO SEGURO
//// _____________________________________________________________________
if($accion==ELIMINARTIPOSEGUROS){
  connect();	
  $s_delete = "delete from af_tiposeguro where CodTipoSeguro='".$codigo."'";
  $q_delete = mysql_query($s_delete) or die ($s_delete.mysql_error());	
}
//// _____________________________________________________________________
////                    EDITAR TIPO SEGURO
//// _____________________________________________________________________
if($accion=='EditarTipoSeguro'){
  connect();
  $s_update = "update af_tiposeguro set Descripcion = '".$descp_tseguro."',
                                        Estado = '".$radioEstado."',
										UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										UltimaFechaModif = '".date("Y-m-d H:i:s")."'
								  where
								        CodTipoSeguro = '".$cod_tseguro."'";
  $q_update = mysql_query($s_update) or die ($s_update.mysql_error());
}
//// _____________________________________________________________________
////                           GUARDAR TIPO VEHICULO
//// _____________________________________________________________________
if($accion=='guardarTipoVehiculo'){
  connect();
  $s_insert = "insert into af_tipovehiculo(CodTipoVehiculo,
                                               Descripcion,
											   Estado,
											   UltimoUsuario,
											   UltimaFechaModif) 
									     values('".$cod_tvehiculo."',
										        '".$descp_tvehiculo."',
												'".$radioEstado."',
												'".$_SESSION['USUARIO_ACTUAL']."',
												'".date("Y-m-d H:i:s")."')";
 $q_insert = mysql_query($s_insert) or die ($s_insert.mysql_error());
}
//// _____________________________________________________________________
////                          EDITAR TIPO VEHICULO
//// _____________________________________________________________________
if($accion=='EditarTipoVehiculo'){
  connect();
  $s_update = "update af_tipovehiculo set Descripcion = '".$descp_tvehiculo."',
                                        Estado = '".$radioEstado."',
										UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										UltimaFechaModif = '".date("Y-m-d H:i:s")."'
								  where 
								        CodTipoVehiculo = '".$cod_tvehiculo."'";
  $q_update = mysql_query($s_update) or die ($s_update.mysql_error()); 
}
//// _____________________________________________________________________
////                           GUARDAR POLIZA SEGUROS
//// _____________________________________________________________________
if($accion=='guardarPolizaSeguro'){
  connect();
   list($d,$m,$a,$h,$i,$s) = SPLIT('[-: ]', $_POST['fvenc_pseguro']);
  $fecha_vencimiento = $a.'-'.$m.'-'.$d.' '.$h.':'.$i.':'.$s;
  
  
  $s_insert = "insert into af_polizaseguro(CodPolizaSeguro,
  								           DescripcionLocal,
										   EmpresaAseguradora,
										   MontoCobertura,
										   AgenteSeguros,
										   FechaVencimiento,
										   CostoPoliza,
										   Estado,
										   UltimoUsuario,
										   UltimaFechaModif)
									 values('".$cod_pseguro."',
									        '".$descp_pseguro."',
											'".$empa_pseguro."',
											'".$mcober_pseguro."',
											'".$ages_pseguro."',
											'".$fecha_vencimiento."',
											'".$cpoli_pseguro."',
											'".$radioEstado."',
											'".$_SESSION['USUARIO_ACTUAL']."',
											'".date("Y-m-d H:i:s")."')";	
  $q_insert = mysql_query($s_insert) or die ($s_insert.mysql_error());

}
//// -------------------------------------------------------------------------------------------
////                                ELIMINAR POLIZA SEGUROS
//// -------------------------------------------------------------------------------------------
if($accion=='ELIMINARPOLIZASEGUROS'){
  connect();	
  $s_delete = "delete from af_polizaseguro where CodPolizaSeguro='".$codigo."'";
  $q_delete = mysql_query($s_delete) or die ($s_delete.mysql_query());	
}
//// _____________________________________________________________________
////                          EDITAR POLIZA SEGUROS
//// _____________________________________________________________________
if($accion=='EditarPolizaSeguros'){
  connect();	
  list($d,$m,$a,$h,$i,$s) = SPLIT('[-: ]', $_POST['fvenc_pseguro']);
  $fecha_vencimiento = $a.'-'.$m.'-'.$d.' '.$h.':'.$i.':'.$s;
  
  $s_update = "update af_polizaseguro set DescripcionLocal = '".$descp_pseguro."',
										  EmpresaAseguradora = '".$empa_pseguro."',
										  MontoCobertura = '".$mcober_pseguro."',
										  AgenteSeguros = '".$ages_pseguro."',
										  FechaVencimiento = '".$fecha_vencimiento."',
										  CostoPoliza = '".$cpoli_pseguro."',
										  Estado = '".$radioEstado."',
										  UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										  UltimaFechaModif = '".date("Y-m-d H:i:s")."'
									 where
									      CodPolizaSeguro = '".$cod_pseguro."'";	
  $q_update = mysql_query($s_update) or die ($s_update.mysql_error());
}
//// _____________________________________________________________________
////                      INSERTAR LINEAS EN CONTABILIDADES
//// _____________________________________________________________________
if ($accion=="insertarLineaContabilidad") {
	connect();
	?>
	<td>
		<input type="text" name="codigo" id="codigo_<?=$_POST['candetalle']?>"  style="width:100%;" readonly="readonly"/>
	</td>
	<td>
        <?
        $s_con = "select * from ac_librocontable";
		$q_con = mysql_query($s_con) or die ($s_con.mysql_error());
		$r_con = mysql_num_rows($q_con);
		?>
		<select name="l_contable" id="l_contable_<?=$_POST['candetalle']?>" style="width:100%;" onchange="cargarCodigo(this.id, <?=$_POST['candetalle']?>);" >
           <option value=""></option>
			<?
             if($r_con!=0){
				while ($f_con = mysql_fetch_array($q_con)){
			?>
			<option value="<?=$f_con['CodLibroCont'];?>"><?=$f_con['Descripcion'];?></option>
			<?
			 } }
			?>
		</select>	
	</td>
	<?
}
//// _____________________________________________________________________
////                             ELIMINAR  CONTABILIDADES
//// _____________________________________________________________________
if($accion =='EliminarEditarContabilidades'){
  echo "CODIGO=".$_POST['codigo'];	
  echo "ID=".$_POST['id_contabilidades'];
  list($A,$B,$C)= SPLIT('[_]',$_POST['id_contabilidades']);	
  echo $A,$B,$C;
  connect();	
  $s_delete = "delete from ac_librocontabilidades where CodContabilidad='$A' and CodLibroCont='$B'";
  $q_delete = mysql_query($s_delete) or die ($s_delete.mysql_query());	
   	
}
//// -------------------------------------------------------------------------------------------
////                   GUARDAR ACTIVO FIJO TRASNFERIDOS DESDE LOGISTICA
//// -------------------------------------------------------------------------------------------
if($accion == 'GuardarActivosTransferidos'){
connect();

//$sql_codigointerno = "select max(CodigoInterno) from af_activo WHERE ClasificacionPublic20='".$ClasificacionPublic20."'"; 

///$sql_codigointerno = "select max(CodigoInterno) from af_activo WHERE ClasificacionPublic20='".$ClasificacionPublic20."'"; 

//$consulta_codigointerno = mysql_query($sql_codigointerno) or die ($sql_codigointerno.mysql_error());

///$cantidad_filas=mysql_num_rows($consulta_codigointerno);

 // // if($cantidad_filas==1)
// //  {
 
 ///	$ejecuto = mysql_fetch_array($consulta_codigointerno); 
 ///	$codigo_interno=$ejecuto[0];	
	//obtenemos la base
	//$base=substr($cod_ant,0,-5)
	//se obitienen los últimos cinco dígitos y se convierten a entero
///	$serial=intval(substr($codigo_interno,-5),10); 
	//se le suma 1 para obtener el próximo valor
///	$serial=$serial+1;
	//se le concatenan los ceros al serial	
  //      $serial=rellenarConCero($serial, 5);	
	//se concatena la base con el serial siguiente
//	$codigo_interno=$ClasificacionPublic20.$serial;	
//  //  }
 // //  else
 //  // {
////	//$serial="00001";
//	//$codigo_interno=$ClasificacionPublic20.$serial;
//  //  }
 
   $CodigoInterno= $_POST['CodigoInterno'];


   /// Consultar activo
   $scon = "select max(Activo) from af_activo ";
   $qcon = mysql_query($scon) or die ($scon.mysql_error());
   $fcon = mysql_fetch_array($qcon);
   
   $activo = (int) ($fcon[0]+1);
   $activo = (string) str_repeat("0",10-strlen($activo)).$activo;
   
   if($_POST['FechaIngreso']!='00-00-0000')$FechaIngreso = date("Y-m-d", strtotime($_POST['FechaIngreso']));
   if($_POST['NumeroOrdenFecha']!='00-00-0000')$NumeroOrdenFecha = date("Y-m-d", strtotime($_POST['NumeroOrdenFecha']));
   if($_POST['NumeroGuiaFecha']!='00-00-0000')$NumeroGuiaFecha = date("Y-m-d", strtotime($_POST['NumeroGuiaFecha'])); 
   if($_POST['DocAlmacenFecha']!='00-00-0000')$DocAlmacenFecha = date("Y-m-d", strtotime($_POST['DocAlmacenFecha'])); 
   if($_POST['InventarioFisicoFecha']!='00-00-0000')$InventarioFisicoFecha = date("Y-m-d", strtotime($_POST['InventarioFisicoFecha'])); 
   if($_POST['FacturaFecha']!='00-00-0000')$FacturaFecha = date("Y-m-d", strtotime($_POST['FacturaFecha']));
    
   $MontoCatastro   = cambioFormato($_POST['MontoCatastro']);	
   $MontoLocal      = cambioFormato($_POST['MontoLocal']);
   $MontoReferencia = cambioFormato($_POST['MontoReferencia']);
   $MontoMercado = cambioFormato($_POST['MontoMercado']);
    
   $s_insert ="insert into af_activo(Activo, CodOrganismo, CodDependencia, Descripcion, TipoActivo,
									 EstadoConserv, CodigoBarras, CodigoInterno, TipoSeguro, TipoVehiculo,
									 Categoria, Clasificacion,  ClasificacionPublic20, Ubicacion, TipoMejora,
									 ActivoConsolidado, EmpleadoUsuario, EmpleadoResponsable, CentroCosto, Marca,
									 Modelo, NumeroSerie, NumeroSerieMotor, NumeroPlaca, MarcaMotor, NumeroAsiento,
									 Material, Dimensiones, NumerodeParte, Color, FabricacionPais,  FabricacionAno,
									 PolizaSeguro, NumeroUnidades, CodigoCatastro,  AreaFisicaCatastro, MontoCatastro,
									 GenerarVoucherIngresoFlag, CodProveedor, FacturaTipoDocumento, FacturaNumeroDocumento,
									 FacturaFecha, NumeroOrden, NumeroOrdenFecha, NumeroGuia, NumeroGuiaFecha,
									 NumeroDocAlmacen, DocAlmacenFecha, InventarioFisicoFecha, InventarioFisicoComentario, FechaIngreso,
									 PeriodoIngreso, PeriodoInicioDepreciacion, PeriodoInicioRevaluacion, PeriodoBaja, VoucherBaja,
									 MontoLocal, MontoReferencia, MontoMercado, VoucherIngreso, Estado, UltimoUsuario,
									 UltimaFechaModif, SituacionActivo, FlagParaMantenimiento, FlagParaOperaciones, OrigenActivo,
									 UnidadMedida, DepreEspecificaFlag, PreparadoPor, FechaPreparacion, Naturaleza,
									 CodTipoMovimiento, EstadoRegistro)
                               values
                                    ('$activo', '".$CodOrganismo."', '".$CodDependencia."', '".$Descripcion."', '".$TipoActivo."',
									'".$EstadoConserv."', '".$CodigoBarras."', '".$CodigoInterno."', '".$TipoSeguro."', '".$TipoVehiculo."',
									'".$Categoria."', '".$Clasificacion."', '".$ClasificacionPublic20."', '".$Ubicacion."', '".$TipoMejora."',
									'".$ActivoConsolidado."', '".$EmpleadoUsuario."', '".$EmpleadoResponsable."', '".$CentroCosto."', '".$Marca."',
									'".$Modelo."', '".$NumeroSerie."', '".$NumeroSerieMotor."', '".$NumeroPlaca."', '".$MarcaMotor."','".$NumeroAsiento."', 
									'".$Material."', '".$Dimensiones."', '".$NumerodeParte."', '".$Color."', '".$FabricacionPais."', '".$FabricacionAno."', 
									'".$PolizaSeguro."', '".$NumeroUnidades."', '".$CodigoCatastro."', '".$AreaFisicaCatastro."', '$MontoCatastro',
									'".$GenerarVoucherIngresoFlag."', '".$CodProveedor."', '".$FacturaTipoDocumento."', '".$FacturaNumeroDocumento."',
									'$FacturaFecha', '".$NumeroOrden."', '$NumeroOrdenFecha', '".$NumeroGuia."', '$NumeroGuiaFecha',
									'".$NumeroDocAlmacen."', '$DocAlmacenFecha', '$InventarioFisicoFecha', '".$InventarioFisicoComentario."', '$FechaIngreso',
									'".$PeriodoIngreso."', '".$PeriodoInicioDepreciacion."', '".$PeriodoInicioRevaluacion."', '".$PeriodoBaja."', '".$VoucherBaja."',
									'$MontoLocal', '$MontoReferencia', '$MontoMercado', '".$VoucherIngreso."', 'PE', '".$_SESSION['USUARIO_ACTUAL']."',
									'".date("Y-m-d H:i:s")."', '".$SituacionActivo."', '".$FlagParaMantenimiento."', '".$FlagParaOperaciones."', '".$OrigenActivo."',
									'".$UnidadMedida."', '".$DepreEspecificaFlag."', '".$PreparadoPor."', '".date("Y-m-d")."', '$Naturaleza',
									'".$CodTipoMovimiento."', 'PE')";
 $q_insert = mysql_query($s_insert) or die ($s_insert.mysql_error());
   
   /// INSERT INFORMACION CONTABLE
  $s_insert2 = "insert into af_activohistoricontable(CodActivo, CodContabilidad, LocalInicio, LocalMesFinal,
													 LocalNeto, ALocalInicio, ALocalMesFinal, AlocalNeto,
												     Origen, UltimoUsuario, UltimaFechaModif, Periodo)
											  values('$activo', '".$Contabilidad."', '".$LocalInicio."', '".$LocalMesFinal."',
													 '".$LocalNeto."', '".$ALoclalInicio."', '".$ALocalMesFinal."', '".$ALocalNeto."',
													 '".$OrigenActivo."', '".$_SESSION['USUARIO_ACTUAL']."', '".date("Y-m-d H:i:s")."', '".date("Y-m")."')";
   $q_insert2 = mysql_query($s_insert2) or die ($s_insert2.mysql_error());
   
  // UPDATE QUE REALIZA CAMBIOS EN LA TABLA LG_ACTIVOFIJO
  // CAMBIO DE ESTADO Y NUMERO DE ACTIVO
  $s_insert3 = "update lg_activofijo set Estado = 'TR', 
                                         Activo = '$activo' 
									where 
									     CodOrganismo = '".$CodOrganismo."' and 
										 NroOrden = '".$NroOrden."' and 
										 Secuencia = '".$Secuencia."' and 
										 NroSecuencia = '".$NroSecuencia."'";  
  $q_insert3 = mysql_query($s_insert3) or die ($s_insert3.mysql_error()); 
  
  /// CONSULTA PARA OBTENER DATOS DE LA TRANSACCION
  if($_PARAMETRO['VOUINGP20']='S'){
  $scon4 = "select * from af_tipotranscuenta where TipoTransaccion = '".$TipoTransaccion."' order by Contabilidad";
  $qcon4 = mysql_query($scon4) or die ($scon4.mysql_error());
  $rcon4 = mysql_num_rows($qcon4);
  
  if($rcon4!=0){
    for($i=0; $i<$rcon4; $i++){
	   $fcon4 = mysql_fetch_array($qcon4);
	   $s_insert4 = "insert into af_activodistribcontable ( Activo, TipoTransaccion, Contabilidad, 
	   												        Secuencia, CuentaContable, Monto, 
															UltimoUsuario, UltimaFechaModif) 
  											        values('$activo', '".$fcon4['TipoTransaccion']."', '".$fcon4['Contabilidad']."',
														   '".$fcon4['Secuencia']."', '".$fcon4['CuentaContable']."', '".$MontoLocal."',
														   '".$_SESSION['USUARIO_ACTUAL']."', '".date("Y-m-d H:i:s")."')";
	   $q_insert4 = mysql_query($s_insert4) or die($s_insert4.mysql_error());
		
	}
  }
  
  }
  
}
//// -------------------------------------------------------------------------------------------
////                        GUARDAR ACTIVO FIJO "LISTOS DE ACTIVOS"
//// -------------------------------------------------------------------------------------------
if($accion == 'GuardarActivosListaActivos'){
connect();
$sql_codigointerno = "select max(CodigoInterno) from af_activo WHERE ClasificacionPublic20='".$ClasificacionPublic20."'"; 
$consulta_codigointerno = mysql_query($sql_codigointerno) or die ($sql_codigointerno.mysql_error());
$cantidad_filas=mysql_num_rows($consulta_codigointerno);

   if($cantidad_filas==1)
   {
 
 	$ejecuto = mysql_fetch_array($consulta_codigointerno); 
 	$codigo_interno=$ejecuto[0];	
	//obtenemos la base
	//$base=substr($cod_ant,0,-5)
	//se obitienen los últimos cinco dígitos y se convierten a entero
	$serial=intval(substr($codigo_interno,-5),10); 
	//se le suma 1 para obtener el próximo valor
	$serial=$serial+1;
	//se le concatenan los ceros al serial	
        $serial=rellenarConCero($serial, 5);	
	//se concatena la base con el serial siguiente
	$codigo_interno=$ClasificacionPublic20.$serial;	
    }
    else
    {
	$serial="00001";
	$codigo_interno=$ClasificacionPublic20.$serial;
    }
 
   $CodigoInterno=$codigo_interno;
   $CodigoInterno=$_POST['CodigoInterno'];
   /// Consultar activo
   $scon = "select max(Activo) from af_activo where CodOrganismo = '".$CodOrganismo."'";
   $qcon = mysql_query($scon) or die ($scon.mysql_error());
   $fcon = mysql_fetch_array($qcon);
   
   $activo = (int) ($fcon[0]+1);
   $activo = (string) str_repeat("0",8-strlen($activo)).$activo; //echo $activo.'-';
   
   if($_POST['FechaIngreso']!='00-00-0000')$FechaIngreso = date("Y-m-d", strtotime($_POST['FechaIngreso']));
   if($_POST['NumeroOrdenFecha']!='00-00-0000')$NumeroOrdenFecha = date("Y-m-d", strtotime($_POST['NumeroOrdenFecha']));
   if($_POST['NumeroGuiaFecha']!='00-00-0000')$NumeroGuiaFecha = date("Y-m-d", strtotime($_POST['NumeroGuiaFecha'])); 
   if($_POST['DocAlmacenFecha']!='00-00-0000')$DocAlmacenFecha = date("Y-m-d", strtotime($_POST['DocAlmacenFecha'])); 
   if($_POST['InventarioFisicoFecha']!='00-00-0000')$InventarioFisicoFecha = date("Y-m-d", strtotime($_POST['InventarioFisicoFecha'])); 
   if($_POST['FacturaFecha']!='00-00-0000')$FacturaFecha = date("Y-m-d", strtotime($_POST['FacturaFecha']));
    
   $MontoCatastro = cambioFormato($_POST['MontoCatastro']);	
   $MontoLocal = cambioFormato($_POST['MontoLocal']);
   $MontoReferencia = cambioFormato($_POST['MontoReferencia']);
   $MontoMercado = cambioFormato($_POST['MontoMercado']);
    
   $s_insert ="insert into af_activo(Activo, CodOrganismo, CodDependencia, Descripcion, TipoActivo, EstadoConserv,
									 CodigoBarras, CodigoInterno, TipoSeguro, TipoVehiculo, Categoria, Clasificacion,
									 ClasificacionPublic20, Ubicacion, TipoMejora, ActivoConsolidado, EmpleadoUsuario,
									 EmpleadoResponsable, CentroCosto, Marca, Modelo,NumeroSerie,NumeroSerieMotor,
									 NumeroPlaca, MarcaMotor,NumeroAsiento, Material, Dimensiones,NumerodeParte,
									 Color, FabricacionPais,FabricacionAno, PolizaSeguro, NumeroUnidades, CodigoCatastro,
									 AreaFisicaCatastro,MontoCatastro, GenerarVoucherIngresoFlag,CodProveedor,
									 FacturaTipoDocumento,FacturaNumeroDocumento,FacturaFecha,NumeroOrden, NumeroOrdenFecha,NumeroGuia,
									 NumeroGuiaFecha,NumeroDocAlmacen,DocAlmacenFecha,InventarioFisicoFecha,InventarioFisicoComentario,FechaIngreso,
									 PeriodoIngreso, PeriodoInicioDepreciacion, PeriodoInicioRevaluacion,PeriodoBaja,VoucherBaja,MontoLocal,
									 MontoReferencia, MontoMercado,VoucherIngreso, Estado,UltimoUsuario, UltimaFechaModif,
									 SituacionActivo, FlagParaMantenimiento,FlagParaOperaciones,OrigenActivo,UnidadMedida,DepreEspecificaFlag,
									 PreparadoPor, FechaPreparado,Naturaleza,ConceptoMovimiento)
                               values
								('$activo', '".$CodOrganismo."', '".$CodDependencia."', '".$Descripcion."', '".$TipoActivo."', '".$EstadoConserv."',
								'".$CodigoBarras."', '".$CodigoInterno."', '".$TipoSeguro."', '".$TipoVehiculo."', '".$Categoria."', '".$Clasificacion."',
								'".$ClasificacionPublic20."','".$Ubicacion."','".$TipoMejora."','".$ActivoConsolidado."','".$EmpleadoUsuario."','".$EmpleadoResponsable."',
								'".$CentroCosto."',	'".$Marca."','".$Modelo."',	'".$NumeroSerie."',	'".$NumeroSerieMotor."','".$NumeroPlaca."',
								'".$MarcaMotor."','".$NumeroAsiento."',	'".$Material."','".$Dimensiones."',	'".$NumerodeParte."','".$Color."',
								'".$FabricacionPais."',	'".$FabricacionAno."','".$PolizaSeguro."','".$NumeroUnidades."','".$CodigoCatastro."','".$AreaFisicaCatastro."',
								'$MontoCatastro','".$GenerarVoucherIngresoFlag."','".$CodProveedor."','".$FacturaTipoDocumento."','".$FacturaNumeroDocumento."','FacturaFecha',
								'".$NumeroOrden."','$NumeroOrdenFecha','".$NumeroGuia."','$NumeroGuiaFecha','".$NumeroDocAlmacen."','$DocAlmacenFecha',
								'$InventarioFisicoFecha','".$InventarioFisicoComentario."','$FechaIngreso','".$PeriodoIngreso."','".$PeriodoInicioDepreciacion."',
								'".$PeriodoInicioRevaluacion."','".$PeriodoBaja."',	'".$VoucherBaja."',	'$MontoLocal','$MontoReferencia','$MontoMercado',
								'".$VoucherIngreso."','PE','".$_SESSION['USUARIO_ACTUAL']."',	'".date("Y-m-d H:i:s")."','".$SituacionActivo."','".$FlagParaMantenimiento."',
								'".$FlagParaOperaciones."',	'".$OrigenActivo."','".$UnidadMedida."','".$DepreEspecificaFlag."','".$PreparadoPor."','".date("Y-m-d")."',
								'".'AN'."','".$ConceptoMovimiento."')";
 $q_insert = mysql_query($s_insert) or die ($s_insert.mysql_error());
   
   /// INSERT INFORMACION CONTABLE
  $s_insert2 = "insert into af_activohistoricontable(CodActivo, CodContabilidad, LocalInicio, LocalMesFinal, 
                                                       LocalNeto, ALocalInicio, ALocalMesFinal, AlocalNeto,
													   Origen, UltimoUsuario, UltimaFechaModif)
												values('$activo', '".$Contabilidad."', '".$LocalInicio."', '".$LocalMesFinal."', 
												      '".$LocalNeto."', '".$ALocalInicio."', '".$ALocalMesFinal."','".$ALocalNeto."',
													  'MA', '".$_SESSION['USUARIO_ACTUAL']."', '".date("Y-m-d H:i:s")."')";
   $q_insert2 = mysql_query($s_insert2) or die ($s_insert2.mysql_error());
   
  // UPDATE QUE REALIZA CAMBIOS EN LA TABLA LG_ACTIVOFIJO
  // CAMBIO DE ESTADO Y NUMERO DE ACTIVO
  $s_insert3 = "update lg_activofijo set Estado = 'TR', 
                                         Activo = '$activo' 
									where 
									     CodOrganismo = '".$CodOrganismo."' and 
										 NroOrden = '".$NroOrden."' and 
										 Secuencia = '".$Secuencia."' and 
										 NroSecuencia = '".$NroSecuencia."'";  
  $q_insert3 = mysql_query($s_insert3) or die ($s_insert3.mysql_error()); 
}
//// -------------------------------------------------------------------------------------------
////                 MODIFICACIONES EN LISTA DE ACTIVOS   "ACTIVO MAYOR"
//// -------------------------------------------------------------------------------------------
if($accion == 'GuardarModificacionesListaActivos'){
connect();
   
$sql_codigointerno = "select max(CodigoInterno) from af_activo WHERE ClasificacionPublic20='".$ClasificacionPublic20."'"; 
$consulta_codigointerno = mysql_query($sql_codigointerno) or die ($sql_codigointerno.mysql_error());
$cantidad_filas1=mysql_num_rows($consulta_codigointerno);

// OBTENGO EL CODIGO INTERNO - HAY QUE SACAR ESTOS DOS CAMPOS PARA PODER MODIFICAR
$sql_CodInterno = "select CodigoInterno from af_activo WHERE Activo='".$Activo."'"; 
$consulta_CodInterno = mysql_query($sql_CodInterno) or die ($sql_CodInterno.mysql_error());
$row_CodInterno = mysql_fetch_array($consulta_CodInterno); 


$sql_pub20_ant = "select ClasificacionPublic20 from af_activo WHERE Activo='".$Activo."'"; 
$consulta_pub20_ant = mysql_query($sql_pub20_ant) or die ($sql_pub20_ant.mysql_error());
$cantidad_filas2=mysql_num_rows($consulta_pub20_ant);
$ejecuto1 = mysql_fetch_array($consulta_pub20_ant); 
$pub20_ant=$ejecuto1[0];

if ($pub20_ant!=$ClasificacionPublic20)
{
   if($cantidad_filas1==1)
   {
 
 ///	$ejecuto2 = mysql_fetch_array($consulta_codigointerno); 
 ///	$codigo_interno=$ejecuto2[0];	
	//obtenemos la base
	//$base=substr($cod_ant,0,-5)
	//se obitienen los últimos cinco dígitos y se convierten a entero
	///$serial=intval(substr($codigo_interno,-5),10); 
	//se le suma 1 para obtener el próximo valor
	///$serial=$serial+1;
	//se le concatenan los ceros al serial	
     ///   $serial=rellenarConCero($serial, 5);	
	//se concatena la base con el serial siguiente
	///$codigo_interno=$ClasificacionPublic20.$serial;	
    }
    else
    {
	///$serial="00001";
	///$codigo_interno=$ClasificacionPublic20.$serial;
    }
 
  /// $CodigoInterno=$codigo_interno;
}
      
   $FechaIngreso = $_POST['FechaIngreso']; $FechaIngreso = date("Y-m-d", strtotime($FechaIngreso));
   $NumeroOrdenFecha = date("Y-m-d", strtotime($_POST['NumeroOrdenFecha']));
   $NumeroGuiaFecha = date("Y-m-d", strtotime($_POST['NumeroGuiaFecha'])); 
   $DocAlmacenFecha = date("Y-m-d", strtotime($_POST['DocAlmacenFecha'])); 
   $InventarioFisicoFecha = date("Y-m-d", strtotime($_POST['InventarioFisicoFecha'])); 
    
   $MontoCatastro = cambioFormato($_POST['MontoCatastro']);	
   $MontoLocal = cambioFormato($_POST['MontoLocal']);
   $MontoReferencia = cambioFormato($_POST['MontoReferencia']);
   $MontoMercado = cambioFormato($_POST['MontoMercado']);
  
   
  $s_update ="update af_activo set  
					 CodDependencia = '".$CodDependencia."', Descripcion = '".$Descripcion."', TipoActivo = '".$TipoActivo."',
					 EstadoConserv = '".$EstadoConserv."', CodigoBarras = '".$CodigoBarras."', CodigoInterno = '".$CodigoInterno."',
					 TipoSeguro = '".$TipoSeguro."', TipoVehiculo = '".$TipoVehiculo."', Categoria = '".$Categoria."',
					 Clasificacion = '".$Clasificacion."', ClasificacionPublic20 = '".$ClasificacionPublic20."', Ubicacion = '".$Ubicacion."',
					 TipoMejora = '".$TipoMejora."', ActivoConsolidado = '".$ActivoConsolidado."', EmpleadoUsuario = '".$EmpleadoUsuario."',
					 EmpleadoResponsable = '".$EmpleadoResponsable."', CentroCosto = '".$CentroCosto."', Marca = '".$Marca."',
					 Modelo = '".$Modelo."', NumeroSerie = '".$NumeroSerie."', NumeroSerieMotor = '".$NumeroSerieMotor."',
					 NumeroPlaca = '".$NumeroPlaca."', MarcaMotor = '".$MarcaMotor."', NumeroAsiento = '".$NumeroAsiento."',
					 Material = '".$Material."', Dimensiones = '".$Dimensiones."', NumerodeParte = '".$NumerodeParte."',
					 Color = '".$Color."', FabricacionPais = '".$FabricacionPais."', FabricacionAno = '".$FabricacionAno."',
					 PolizaSeguro = '".$PolizaSeguro."', NumeroUnidades = '".$NumeroUnidades."', CodigoCatastro = '".$CodigoCatastro."',
					 AreaFisicaCatastro='".$AreaFisicaCatastro."', MontoCatastro='$MontoCatastro', GenerarVoucherIngresoFlag='".$GenerarVoucherIngresoFlag."', 
					 CodProveedor = '".$CodProveedor."', FacturaTipoDocumento='".$FacturaTipoDocumento."',FacturaNumeroDocumento='".$FacturaNumeroDocumento."',
					 FacturaFecha='".$FacturaFecha."', NumeroOrden = '".$NumeroOrden."', NumeroOrdenFecha = '$NumeroOrdenFecha', 
					 NumeroGuia = '".$NumeroGuia."', NumeroGuiaFecha = '$NumeroGuiaFecha', NumeroDocAlmacen = '".$NumeroDocAlmacen."', 
					 DocAlmacenFecha = '$DocAlmacenFecha',InventarioFisicoFecha = '$InventarioFisicoFecha', InventarioFisicoComentario = '".$InventarioFisicoComentario."',
					 FechaIngreso = '$FechaIngreso', PeriodoIngreso = '".$PeriodoIngreso."', PeriodoInicioDepreciacion = '".$PeriodoInicioDepreciacion."',
					 PeriodoInicioRevaluacion = '".$PeriodoInicioRevaluacion."', PeriodoBaja = '".$PeriodoBaja."', VoucherBaja = '".$VoucherBaja."',
					 MontoLocal = '$MontoLocal', MontoReferencia = '$MontoReferencia', MontoMercado = '$MontoMercado',
					 VoucherIngreso = '".$VoucherIngreso."', UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', UltimaFechaModif = '".date("Y-m-d H:i:s")."',
					 SituacionActivo = '".$SituacionActivo."', FlagParaMantenimiento = '".$FlagParaMantenimiento."', FlagParaOperaciones = '".$FlagParaOperaciones."',
					 DepreEspecificaFlag = '".$DepreEspecificaFlag."', CodTipoMovimiento = '".$CodTipoMovimiento."'
                where
				     Activo ='".$Activo."' and CodOrganismo = '".$CodOrganismo."' and  CodigoInterno ='".$row_CodInterno['CodigoInterno']."'";

 $q_update = mysql_query($s_update) or die ($s_update.mysql_error());
 
 
  /// UPDATE INFORMACION CONTABLE
  $s_update2 = "update af_activohistoricontable set  CodContabilidad = '".$Contabilidad."', LocalInicio = '".$LocalInicio."', 
  													   LocalMesFinal ='".$LocalMesFinal."', LocalNeto = '".$LocalNeto."', 
													   ALocalInicio = '".$ALocalInicio."',  ALocalMesFinal ='".$ALocalMesFinal."' , 
													   AlocalNeto = '".$ALocalNeto."', UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', 
													   UltimaFechaModif = '".date("Y-m-d H:i:s")."' 
												 where 
												       CodActivo='".$Activo."'";
													  
												
   $q_update2 = mysql_query($s_update2) or die ($s_update2.mysql_error());
 
 echo "asddasdd";
/// CONSULTA PARA OBTENER DATOS DE LA TRANSACCION
if($_PARAMETRO['VOUINGP20']='S'){
	  
 $s = "select * from af_activodistribcontable where Activo='".$Activo."'"; 
 $q = mysql_query($s) or die ($s.mysql_error());
 $r = mysql_num_rows($q);	  
 
 if($r!=0) $f = mysql_fetch_array($q);
 if($f['TipoTransaccion']!= $_POST['TipoTransaccion']){
    $s_delete = "delete from af_activodistribcontable where Activo='".$Activo."'";
	$q_delete = mysql_query($s_delete) or die ($s_delete.mysql_error());
	
	$scon4 = "select * from af_tipotranscuenta where TipoTransaccion = '".$TipoTransaccion."' order by Contabilidad";
    $qcon4 = mysql_query($scon4) or die ($scon4.mysql_error());
    $rcon4 = mysql_num_rows($qcon4);
	
	if($rcon4!=0){
      for($i=0; $i<$rcon4; $i++){
	   $fcon4 = mysql_fetch_array($qcon4);
	   $s_insert4 = "insert into af_activodistribcontable ( Activo, TipoTransaccion, Contabilidad, 
	   												        Secuencia, CuentaContable, Monto, 
															UltimoUsuario, UltimaFechaModif) 
  											        values('".$Activo."', '".$fcon4['TipoTransaccion']."', '".$fcon4['Contabilidad']."',
														   '".$fcon4['Secuencia']."', '".$fcon4['CuentaContable']."', '".$MontoLocal."',
														   '".$_SESSION['USUARIO_ACTUAL']."', '".date("Y-m-d H:i:s")."')";
	   $q_insert4 = mysql_query($s_insert4) or die($s_insert4.mysql_error());
		
	 }
   }
 
 }
  
  }
}
//// -------------------------------------------------------------------------------------------
////                         INSERTA NUEVO MOVIMIENTO DE ACTIVO
//// -------------------------------------------------------------------------------------------
if($accion=='guardarNuevoMovimientoActivo'){
connect();
 
 /// Consulta para obtener el último número de movimiento por organismo
 $s_consulta = "select MAX(MovimientoNumero) from af_movimientos where Organismo ='".$Organismo."'"; 
 $q_consulta = mysql_query($s_consulta) or die ($s_consulta.mysql_error());
 $f_consulta = mysql_fetch_array($q_consulta);
 
 /// Crear el número de movimiento
 $Mov_Numero = (int) ($f_consulta['0'] + 1);
 $Mov_Numero = (string) str_repeat("0",10-strlen($Mov_Numero)).$Mov_Numero;
 
 $s_movimientos = "insert into af_movimientos(Organismo, MovimientoNumero, PreparadoPor,
											  FechaPreparacion, Estado, UltimoUsuario,
											  UltimaFechaModif, InternoExternoFlag, Comentario,
											  MotivoTraslado) 
									  values ('".$Organismo."', '$Mov_Numero', '".$PreparadoPor."',
											 '".date("Y-m-d")."', 'PR', '".$_SESSION['USUARIO_ACTUAL']."',
											 '".date("Y-m-d H:i:s")."', '".$InternoExternoFlag."', '".$Comentario."',
											 '".$MotivoTraslado."')";
 $q_movimientos = mysql_query($s_movimientos) or die ($s_movimientos.mysql_error());

 $s_mdetalle = "insert into af_movimientosdetalle(Organismo, Activo, MovimientoNumero, CentroCosto,
												 CentroCostoAnterior, Ubicacion, UbicacionAnterior, Dependencia,
												 DependenciaAnterior, EmpleadoUsuario,  EmpleadoUsuarioAnterior, EmpleadoResponsable,
												 EmpleadoResponsableAnterior, OrganismoActual, OrganismoAnterior)
										  values ('".$Organismo."', '".$Activo."', '$Mov_Numero', '".$CentroCosto."',
												  '".$CentroCostoAnterior."', '".$Ubicacion."', '".$UbicacionAnterior."', '".$Dependencia."',
												  '".$DependenciaAnterior."', '".$EmpleadoUsuario."', '".$EmpleadoUsuarioAnterior."', '".$EmpleadoResponsable."',
												  '".$EmpleadoResponsableAnterior."', '".$OrganismoActual."', '".$OrganismoAnterior."')";
 $q_mdetalle = mysql_query($s_mdetalle) or die ($s_mdetalle.mysql_error());
 

//// -------------------  SE CARGAN DATOS EN TABLA HISTORICA DE MOVIMIENTOS DEL ACTIVO
/*$sact = "select	
			a.MovimientoNumero,
			b.Activo
		from  
			af_movimientos a 
			inner join af_movimientosdetalle b on (a.Organismo = b.Organismo) and 
												  (a.MovimientoNumero = b.MovimientoNumero)
		where  
			a.MovimientoNumero=(SELECT MAX(MovimientoNumero) FROM af_movimientos)";
$qact = mysql_query($sact) or die ($sact.mysql_error());
$fact = mysql_fetch_array($qact);

$sth = "select 
              *, Secuencia 
		 from 
		      af_historicotransaccion 
	     where 
		      Secuencia=(SELECT MAX(Secuencia) FROM af_historicotransaccion and Activo='".$Activo."' and CodOrganismo = '".$Organismo."')";
$qth = mysql_query($sth) or die ($sth.mysql_error());
	$secuencia = (int) (0 + 1);
	$secuencia = (string) str_repeat("0",4-strlen($secuencia)).$secuencia;


$sin = "insert into af_historicotransaccion(CodOrganismo, Activo, Secuencia, CodDependencia,
											CentroCosto, CodigoInterno, SituacionActivo, CodTipoMovimiento,
											Ubicacion, InternoExternoFlag, MotivoTraslado, FechaIngreso,
											FechaTransaccion, FechaBaja, PeriodoIngreso, PeriodoTransaccion,
											NroOrden, MontoActivo, UltimoUsuario,UltimaFechaModif)
									  value ('".$Organismo."', '".$Activo."', '$secuencia', '".$fact['CodDependencia']."',
											'".$fact['CentroCosto']."',	'".$fact['CodigoInterno']."', '".$fact['SituacionActivo']."', '".$fact['CodTipoMovimiento']."',
											'".$fact['Ubicacion']."', 'I', '01', '".$fact['FechaIngreso']."',
											'".date("Y-m-d")."', '".date("Y-m-d")."', '".$fact['PeriodoIngreso']."', '".date("Y-m")."',
											'".$fact['NumeroOrden']."', '".$fact['MontoLocal']."', '".$_SESSION['USUARIO_ACTUAL']."','".date("Y-m-d H:i:s")."')";
$qin = mysql_query($sin) or die ($sin.mysql_error());*/
 
  
 echo $Mov_Numero;
 
 }
//// -------------------------------------------------------------------------------------------
////                   GUARDAR MODIFICACIONES DE MOVIMIENTO DE ACTIVO
//// -------------------------------------------------------------------------------------------
if($accion=='guardarEditarMovimientoActivo'){
connect();
$s_movimientos = "update af_movimientos set
                                            UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
											UltimaFechaModif = '".date("Y-m-d H:i:s")."',
											InternoExternoFlag = '".$InternoExternoFlag."',
											Comentario = '".$Comentario."',
											MotivoTraslado = '".$MotivoTraslado."'
									   where 
									        Organismo = '".$Organismo."' and 
											MovimientoNumero = '".$MovimientoNumero."'";
 $q_movimientos = mysql_query($s_movimientos) or die ($s_movimientos.mysql_error());

 $s_mdetalle = "update af_movimientosdetalle set 
                                  				 CentroCosto = '".$CentroCosto."',
												 CentroCostoAnterior = '".$CentroCostoAnterior."',
												 Ubicacion = '".$Ubicacion."',
												 UbicacionAnterior = '".$UbicacionAnterior."',
												 Dependencia = '".$Dependencia."',
												 DependenciaAnterior = '".$DependenciaAnterior."',
												 EmpleadoUsuario = '".$EmpleadoUsuario."',
												 EmpleadoUsuarioAnterior = '".$EmpleadoUsuarioAnterior."',
												 EmpleadoResponsable = '".$EmpleadoResponsable."',
												 EmpleadoResponsableAnterior = '".$EmpleadoResponsableAnterior."',
												 OrganismoActual = '".$OrganismoActual."',
												 OrganismoAnterior = '".$OrganismoAnterior."'
										   where 
										         Organismo = '".$Organismo."' and 
											     Activo = '".$Activo."' and 
											     MovimientoNumero = '".$MovimientoNumero."'";
 $q_mdetalle = mysql_query($s_mdetalle) or die ($s_mdetalle.mysql_error());
}
//// -------------------------------------------------------------------------------------------
////                     APROBAR MOVIMIENTO DE ACTIVO
//// -------------------------------------------------------------------------------------------
if($accion=="guardarAprobarMovimientoActivo"){
 connect();	
 //// Consulto para saber si el usuario actual es el mismo que preparo el movimiento
 //// si lo es no puede realizarce el update en af_movimientos
 $scon = "select *  from usuarios where Usuario='".$_SESSION['USUARIO_ACTUAL']."'";
 $qcon = mysql_query($scon) or die ($scon.mysql_error()); 
 $rcon = mysql_num_rows($qcon);
 
 if($rcon!=0)$fcon = mysql_fetch_array($qcon); 
 if($_POST['PreparadoPor']==$fcon['CodPersona']){ 
   //echo "EL USUARIO QUE PREPARO EL MOVIMIENTO DEBE SER DISTINTO AL MOMENTO DE APROBAR";
   $cont = 1; echo $cont; 
 }else{
    $sql01 = "update 
	                af_movimientos 
			     set 
				    AprobadoPor = '".$fcon['CodPersona']."',
					FechaAprobacion = '".date("Y-m-d")."',
					Estado = 'AP'
			   where 
			        MovimientoNumero = '".$MovimientoNumero."' and 
					Organismo = '".$Organismo."'"; //echo $sql01;//echo"<option>".$sql01."</option>";
    $qry01 = mysql_query($sql01) or die ($sql01.mysql_error());
	
	$sql = "update af_activo set 
                              CentroCosto = '".$CentroCosto."',
							  Ubicacion = '".$Ubicacion."',
							  EmpleadoUsuario = '".$EmpleadoUsuario."',
							  EmpleadoResponsable = '".$EmpleadoResponsable."',
							  CodDependencia = '".$CodDependencia."',
							  CodOrganismo = '".$CodOrganismo."' 
						where 
						      Activo='".$Activo."' and 
							  CodOrganismo='".$Organismo."'"; //echo $sql;
   $qry = mysql_query($sql) or die ($sql.mysql_error());


$sa = "select 
			 CodigoInterno, SituacionActivo, FechaIngreso, 
			 PeriodoIngreso, MontoLocal
		 from 
		     af_activo 
	    where 
		     Activo='".$Activo."' and CodOrganismo='".$Organismo."'"; 
$qa = mysql_query($sa) or die ($sa.mysql_error());
$fa = mysql_fetch_array($qa);

$sact ="select 
		    a.*,
			a.MovimientoNumero,
			b.*
		from 
			 af_movimientosdetalle a
			 inner join af_movimientos b on (a.MovimientoNumero = b.MovimientoNumero) and 
											(a.Organismo = b.Organismo)
		where 
			a.MovimientoNumero=(select max(MovimientoNumero) from af_movimientosdetalle where Activo='".$Activo."' and Organismo='".$Organismo."') and 
			a.CodOrganismo = '".$Organismo."'";
$qact = mysql_query($sact) or die ($sact.mysql_error());
$fact = mysql_fetch_array($qact);

$sth = "select 
              *, Secuencia 
		 from 
		      af_historicotransaccion 
	     where 
		      Secuencia=(SELECT MAX(Secuencia) FROM af_historicotransaccion where Activo='".$Activo."' and CodOrganismo='".$Organismo."') and 
			  CodOrganismo = '".$Organismo."'";
$qth = mysql_query($sth) or die ($sth.mysql_error());
$fth = mysql_fetch_array($qth);

	$secuencia = (int) ($fth['Secuencia'] + 1);
	//$secuencia = (string) str_repeat("0",4-strlen($secuencia)).$secuencia;


$sin = "insert into af_historicotransaccion(CodOrganismo, Activo, Secuencia, CodDependencia,
											CentroCosto, CodigoInterno, SituacionActivo, CodTipoMovimiento,
											Ubicacion, InternoExternoFlag, MotivoTraslado, FechaIngreso,
											FechaTransaccion, PeriodoIngreso, PeriodoTransaccion, NumeroOrden, 
											OrdenSecuencia, MontoActivo, UltimoUsuario, UltimaFechaModif)
									  value ('".$Organismo."', '".$Activo."', '$secuencia', '".$fact['Dependencia']."',
											'".$fact['CentroCosto']."',	'".$fa['CodigoInterno']."', '".$fa['SituacionActivo']."', '".$fact['CodTipoMovimiento']."',
											'".$fact['Ubicacion']."', '".$fact['InternoExternoFlag']."', '".$fact['MotivoTraslado']."', '".$fa['FechaIngreso']."',
											'".date("Y-m-d")."', '".$fa['PeriodoIngreso']."', '".date("Y-m")."', '".$fth['NumeroOrden']."', 
											'".$fth['OrdenSecuencia']."', '".$fa['MontoLocal']."', '".$_SESSION['USUARIO_ACTUAL']."', '".date("Y-m-d H:i:s")."')"; //echo $sin;
$qin = mysql_query($sin) or die ($sin.mysql_error());  
 
 
   $cont = 2; echo $cont;	
 }
}
//// -------------------------------------------------------------------------------------------
////   APROBAR ACTIVO (ESTADO = AP), PROCESOS => APROBACION ALTA DE ACTIVOS
//// -------------------------------------------------------------------------------------------
if($accion=='AprobarActivo'){
 connect();
 
$parametro[0] = $_PARAMETRO['CONFACTIVOPOR'];  /// Revisado por Dirección de Administradcion y Servicios 0009
$parametro[1] = $_PARAMETRO['APROBACTIVOPOR'] ; /// Conformado Dirección General 0003
$valor = 2; $cont=1;
for($i=0; $i<$valor; $i++){

	$scon = "select 
                  mp.ValorParam,
				  b.CodPersona,
				  d.DescripCargo
			from 
			      mastparametros mp 
			      inner join mastdependencias a on (a.CodDependencia=mp.ValorParam) 
				  inner join mastpersonas b on (b.CodPersona=a.CodPersona) 
				  inner join mastempleado c on (c.CodPersona=a.CodPersona)
				  inner join rh_puestos d on (d.CodCargo=c.CodCargo)
			where 
			      mp.ValorParam='".$parametro[$i]."'";
	$qcon = mysql_query($scon) or die ($scon.mysql_error());
	$rcon = mysql_num_rows($qcon); //echo $rcon.'****';

if($rcon!=0)$fcon = mysql_fetch_array($qcon);

$scon2 = "select 
				max(Secuencia) 
			from 
				rh_empleadonivelacion 
			where 
				CodPersona='".$fcon['CodPersona']."'";
$qcon2 = mysql_query($scon2) or die ($scon2.mysql_error());
$rcon2 = mysql_num_rows($qcon2);
if($rcon2!=0) $fcon2 = mysql_fetch_array($qcon2);

$scon3 = "Select 
				a.CodCargo,
				a.CodPersona,
				b.DescripCargo
		   from 
		   		rh_empleadonivelacion a 
				inner join rh_puestos b on (b.CodCargo=a.CodCargo)
		 where 
		 		a.Secuencia='".$fcon2['0']."' and  
				a.CodPersona='".$fcon['CodPersona']."'"; 
$qcon3 = mysql_query($scon3) or die ($scon3.mysql_error()); 
$rcon3 = mysql_num_rows($qcon3);
if($rcon3!=0)$fcon3 = mysql_fetch_array($qcon3);

	if($cont==1){
		$codRevisa = $fcon['CodPersona']; /// quien revisa
		$cargoRevisa = $fcon3['CodCargo'];
	}else{
		$codConforma = $fcon['CodPersona']; /// quien conforma
		$cargoConforma = $fcon3['CodCargo'];
	}
	$cont++; //echo "<option>$cont</option>";
}

//// -------------------       CAPTURANDO DATOS DE QUIEN APRUEBA
$s_ap = "select CodPersona from usuarios where Usuario='".$_SESSION['USUARIO_ACTUAL']."'";  
$q_ap = mysql_query($s_ap) or die ($s_ap.mysql_error());
$r_ap = mysql_num_rows($q_ap);
if($r_ap!=0) $f_ap = mysql_fetch_array($q_ap);

$sniv = "select max(Secuencia) from rh_empleadonivelacion where CodPersona='".$f_ap['CodPersona']."'";
$qniv = mysql_query($sniv) or die ($sniv.mysql_error());
$fniv = mysql_fetch_array($qniv);

$sql = "select 
			 a.CodPersona as CodPersonaAprueba,
			 b.CodCargo as CodCargoAprueba,
			 b.DescripCargo
	  	from 
			rh_empleadonivelacion a 
			inner join rh_puestos b on (b.CodCargo=a.CodCargo) 
	 	where  
			a.Secuencia='".$fniv['0']."' and 
			a.CodPersona='".$f_ap['CodPersona']."'"; 
$qry = mysql_query($sql) or die ($sql.mysql_error());
$field = mysql_fetch_array($qry);

//// ------------------ OBTENIENDO EL ULTIMO NUMERO DE INCORPORACION Y NUMERO DE ENTREGA 
$numeroActivo = $_POST['Activo'] - 1; //echo $numeroActivo;
$numeroActivo = (int) ($numeroActivo);
$numeroActivo = (string) str_repeat("0",10-strlen($numeroActivo)).$numeroActivo;

$sincorp = "select 
				   NroIncorporacion,
				   NroActaEntrega
			  from 
			       af_activo 
			 where 
			       Activo='".$numeroActivo."'"; 
$qincorp = mysql_query($sincorp) or die ($sincorp.mysql_error());
$fincorp = mysql_fetch_array($qincorp);

  $nroIncorp = (int) ($fincorp['NroIncorporacion']+1);
  $nroIncorp = (string) str_repeat("0",4-strlen($nroIncorp)).$nroIncorp;
  
  $nroEnt = (int) ($fincorp['NroActaEntrega']+1);
  $nroEnt = (string) str_repeat("0",4-strlen($nroEnt)).$nroEnt;

//// ------------------ ACTUALIZANDO TABLA AF_ACTIVO 
  $s_update = "update 
  					af_activo 
				set 
					Estado='AP',
					EstadoRegistro='AP',
					AprobadoPor='".$field['CodPersonaAprueba']."',
					CargoAprobadoPor='".$field['CodCargoAprueba']."',
					FechaRevisadoPor='".date("Y-m-d")."',
					RevisadoPor = '$codRevisa',
					CargoRevisadoPor = '$cargoRevisa',
					ConformadoPor = '$codConforma',
					CargoConformadoPor = '$cargoConforma',
					NroIncorporacion = '$nroIncorp',
					NroActaEntrega = '$nroEnt'
			  where 
			   		Activo='".$Activo."' and 
					CodOrganismo='".$CodOrganismo."'"; 
  $q_update = mysql_query($s_update) or die ($s_update.mysql_error());
  
//// ------------------ SE CARGAN DATOS EN TABLA HISTORICA DE MOVIMIENTOS DEL ACTIVO
$sact = "select 
      			*
			from 
      			af_activo 
			where 
      			Activo='".$Activo."'  and 
				CodOrganismo='".$CodOrganismo."' and 
				Estado='AP'";
$qact = mysql_query($sact) or die ($sact.mysql_error());
$fact = mysql_fetch_array($qact);


	$secuencia = (int) (0 + 1);
	$secuencia = (string) str_repeat("0",4-strlen($secuencia)).$secuencia;


$sin = "insert into af_historicotransaccion(CodOrganismo, Activo, Secuencia, CodDependencia,
											CentroCosto, CodigoInterno, SituacionActivo, CodTipoMovimiento,
											Ubicacion, InternoExternoFlag, MotivoTraslado, FechaIngreso,
											FechaTransaccion,PeriodoIngreso, PeriodoTransaccion,
											NumeroOrden, OrdenSecuencia, MontoActivo, UltimoUsuario,UltimaFechaModif)
									  value ('".$fact['CodOrganismo']."', '".$fact['Activo']."', '$secuencia', '".$fact['CodDependencia']."',
											'".$fact['CentroCosto']."',	'".$fact['CodigoInterno']."', '".$fact['SituacionActivo']."', '".$fact['CodTipoMovimiento']."',
											'".$fact['Ubicacion']."', 'I', '01', '".$fact['FechaIngreso']."',
											'".date("Y-m-d")."', '".$fact['PeriodoIngreso']."', '".date("Y-m")."',
											'".$fact['NumeroOrden']."', 'X', '".$fact['MontoLocal']."', '".$_SESSION['USUARIO_ACTUAL']."','".date("Y-m-d H:i:s")."')";
$qin = mysql_query($sin) or die ($sin.mysql_error());  
  
}

if($accion=='DesincorporarActivo'){
 connect();
 
$parametro[0] = $_PARAMETRO['CONFACTIVOPOR'];  /// Revisado por Dirección de Administradcion y Servicios 0009
$parametro[1] = $_PARAMETRO['APROBACTIVOPOR'] ; /// Conformado Dirección General 0003
/*
$valor = 2; $cont=1;
for($i=0; $i<$valor; $i++){

	$scon = "select 
                  mp.ValorParam,
				  b.CodPersona,
				  d.DescripCargo
			from 
			      mastparametros mp 
			      inner join mastdependencias a on (a.CodDependencia=mp.ValorParam) 
				  inner join mastpersonas b on (b.CodPersona=a.CodPersona) 
				  inner join mastempleado c on (c.CodPersona=a.CodPersona)
				  inner join rh_puestos d on (d.CodCargo=c.CodCargo)
			where 
			      mp.ValorParam='".$parametro[$i]."'";
	$qcon = mysql_query($scon) or die ($scon.mysql_error());
	$rcon = mysql_num_rows($qcon); //echo $rcon.'****';

if($rcon!=0)$fcon = mysql_fetch_array($qcon);

$scon2 = "select 
				max(Secuencia) 
			from 
				rh_empleadonivelacion 
			where 
				CodPersona='".$fcon['CodPersona']."'";
$qcon2 = mysql_query($scon2) or die ($scon2.mysql_error());
$rcon2 = mysql_num_rows($qcon2);
if($rcon2!=0) $fcon2 = mysql_fetch_array($qcon2);

$scon3 = "Select 
				a.CodCargo,
				a.CodPersona,
				b.DescripCargo
		   from 
		   		rh_empleadonivelacion a 
				inner join rh_puestos b on (b.CodCargo=a.CodCargo)
		 where 
		 		a.Secuencia='".$fcon2['0']."' and  
				a.CodPersona='".$fcon['CodPersona']."'"; 
$qcon3 = mysql_query($scon3) or die ($scon3.mysql_error()); 
$rcon3 = mysql_num_rows($qcon3);
if($rcon3!=0)$fcon3 = mysql_fetch_array($qcon3);

	if($cont==1){
		$codRevisa = $fcon['CodPersona']; /// quien revisa
		$cargoRevisa = $fcon3['CodCargo'];
	}else{
		$codConforma = $fcon['CodPersona']; /// quien conforma
		$cargoConforma = $fcon3['CodCargo'];
	}
	$cont++; //echo "<option>$cont</option>";
}

//// -------------------       CAPTURANDO DATOS DE QUIEN APRUEBA
$s_ap = "select CodPersona from usuarios where Usuario='".$_SESSION['USUARIO_ACTUAL']."'";  
$q_ap = mysql_query($s_ap) or die ($s_ap.mysql_error());
$r_ap = mysql_num_rows($q_ap);
if($r_ap!=0) $f_ap = mysql_fetch_array($q_ap);

$sniv = "select max(Secuencia) from rh_empleadonivelacion where CodPersona='".$f_ap['CodPersona']."'";
$qniv = mysql_query($sniv) or die ($sniv.mysql_error());
$fniv = mysql_fetch_array($qniv);

$sql = "select 
			 a.CodPersona as CodPersonaAprueba,
			 b.CodCargo as CodCargoAprueba,
			 b.DescripCargo
	  	from 
			rh_empleadonivelacion a 
			inner join rh_puestos b on (b.CodCargo=a.CodCargo) 
	 	where  
			a.Secuencia='".$fniv['0']."' and 
			a.CodPersona='".$f_ap['CodPersona']."'"; 
$qry = mysql_query($sql) or die ($sql.mysql_error());
$field = mysql_fetch_array($qry);

//// ------------------ OBTENIENDO EL ULTIMO NUMERO DE INCORPORACION Y NUMERO DE ENTREGA 
$numeroActivo = $_POST['Activo'] - 1; //echo $numeroActivo;
$numeroActivo = (int) ($numeroActivo);
$numeroActivo = (string) str_repeat("0",10-strlen($numeroActivo)).$numeroActivo;

$sincorp = "select 
				   NroIncorporacion,
				   NroActaEntrega
			  from 
			       af_activo 
			 where 
			       Activo='".$numeroActivo."'"; 
$qincorp = mysql_query($sincorp) or die ($sincorp.mysql_error());
$fincorp = mysql_fetch_array($qincorp);

  $nroIncorp = (int) ($fincorp['NroIncorporacion']+1);
  $nroIncorp = (string) str_repeat("0",4-strlen($nroIncorp)).$nroIncorp;
  
  $nroEnt = (int) ($fincorp['NroActaEntrega']+1);
  $nroEnt = (string) str_repeat("0",4-strlen($nroEnt)).$nroEnt;
*/
//// ------------------ ACTUALIZANDO TABLA AF_ACTIVO 
  $s_update = "update 
  					af_activo 
				set 
					Estado='DE',
					EstadoRegistro='DE'					
			  where 
			   		Activo='".$Activo."' and 
					CodOrganismo='".$CodOrganismo."'"; 
  $q_update = mysql_query($s_update) or die ($s_update.mysql_error());
  
//// ------------------ SE CARGAN DATOS EN TABLA HISTORICA DE MOVIMIENTOS DEL ACTIVO
/*
$sact = "select 
      			*
			from 
      			af_activo 
			where 
      			Activo='".$Activo."'  and 
				CodOrganismo='".$CodOrganismo."' and 
				Estado='AP'";
$qact = mysql_query($sact) or die ($sact.mysql_error());
$fact = mysql_fetch_array($qact);


	$secuencia = (int) (0 + 1);
	$secuencia = (string) str_repeat("0",4-strlen($secuencia)).$secuencia;


$sin = "insert into af_historicotransaccion(CodOrganismo, Activo, Secuencia, CodDependencia,
											CentroCosto, CodigoInterno, SituacionActivo, CodTipoMovimiento,
											Ubicacion, InternoExternoFlag, MotivoTraslado, FechaIngreso,
											FechaTransaccion,PeriodoIngreso, PeriodoTransaccion,
											NumeroOrden, OrdenSecuencia, MontoActivo, UltimoUsuario,UltimaFechaModif)
									  value ('".$fact['CodOrganismo']."', '".$fact['Activo']."', '$secuencia', '".$fact['CodDependencia']."',
											'".$fact['CentroCosto']."',	'".$fact['CodigoInterno']."', '".$fact['SituacionActivo']."', '".$fact['CodTipoMovimiento']."',
											'".$fact['Ubicacion']."', 'I', '01', '".$fact['FechaIngreso']."',
											'".date("Y-m-d")."', '".$fact['PeriodoIngreso']."', '".date("Y-m")."',
											'".$fact['NumeroOrden']."', 'X', '".$fact['MontoLocal']."', '".$_SESSION['USUARIO_ACTUAL']."','".date("Y-m-d H:i:s")."')";
$qin = mysql_query($sin) or die ($sin.mysql_error());  
*/ 
}

//// -------------------------------------------------------------------------------------------
///                GUARDAR MAESTRO NUEVA CARACATERISTICA TECNICA DE ACTIVO
//// -------------------------------------------------------------------------------------------
if($accion=="guardarNuevaCaracteristicaTecnica"){
connect();
 $sql = "select * from af_caracteristicatecnica where CodCaractTecnica = '".$CodCaractTecnica."'";
 $qry = mysql_query($sql)  or die ($sql.mysql_error());
 $row = mysql_num_rows($qry);
 if($row!=0){
	echo"¡LOS DATOS HAN SIDO INTRODUCIDOS ANTERIORMENTE¡";
 }else{
    $s_insert = "insert into af_caracteristicatecnica(CodCaractTecnica,
													  DescripcionLocal,
													  Estado,
													  UltimoUsuario,
													  UltimaFechaModif)
											   values 
											         ('".$CodCaractTecnica."',
													  '".$DescripcionLocal."',
													  '".$Estado."',
													  '".$_SESSION['USUARIO_ACTUAL']."',
													  '".date("Y-m-d H:i:s")."')";
	$q_insert = mysql_query($s_insert) or die ($s_insert.mysql_error());
 }
}
//// -------------------------------------------------------------------------------------------
///                     EDITAR MAESTRO CARACATERISTICA TECNICA DE ACTIVO
//// -------------------------------------------------------------------------------------------
if($accion=="editarCaracteristicaTecnica"){
connect();
 $s_update = "update 
                    af_caracteristicatecnica 
				 set 
				    DescripcionLocal = '".$DescripcionLocal."', 
					Estado = '".$Estado."', 
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFechaModif = '".date("Y-m-d H:i:s")."'
			   where 
			        CodCaractTecnica = '".$CodCaractTecnica."'";
 $q_update = mysql_query($s_update)  or die ($s_update.mysql_error());
}
//// -------------------------------------------------------------------------------------------
///                    ELIMINAR MAESTRO CARACTERISTICA TECNICA DE ACTIVO
//// -------------------------------------------------------------------------------------------
if($accion=="eliminarCaractTecnica"){
connect();
 //echo "CODIGO=".$_POST['codigo'];	
$sql = "delete from af_caracteristicatecnica where CodCaractTecnica = '".$codigo."'"; 
$qry = mysql_query($sql) or die ($sql.mysql_error()); 
}
/// ----------------------------------------------------------------------
///            GUARDAR MAESTRO COMPONENTES DE UN EQUIPO
//// ---------------------------------------------------------------------
if($accion=="guardarNuevoComponenteEquipo"){
connect();
 $sql = "select * from af_tipocomponente where CodTipoComp = '".$CodTipoComp."'";
 $qry = mysql_query($sql)  or die ($sql.mysql_error());
 $row = mysql_num_rows($qry);
 if($row!=0){
	echo"¡LOS DATOS HAN SIDO INTRODUCIDOS ANTERIORMENTE¡";
 }else{
    $s_insert = "insert into af_tipocomponente(CodTipoComp,
											  DescripcionLocal,
											  Estado,
											  UltimoUsuario,
											  UltimaFechaModif)
									   values 
											 ('".$CodTipoComp."',
											  '".$DescripcionLocal."',
											  '".$Estado."',
											  '".$_SESSION['USUARIO_ACTUAL']."',
											  '".date("Y-m-d H:i:s")."')";
	$q_insert = mysql_query($s_insert) or die ($s_insert.mysql_error());
 }
}
/// ----------------------------------------------------------------------
///               EDITAR MAESTRO COMPONENTES DE UN EQUIPO
//// ---------------------------------------------------------------------
if($accion=="editarComponenteEquipo"){
connect();
$s_update = "update af_tipocomponente set 
                                         DescripcionLocal = '".$DescripcionLocal."',
										 Estado = '".$Estado."',
										 UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', 
										 UltimaFechaModif = '".date("Y-m-d H:i:s")."' 
								   where 
								         CodTipoComp = '".$CodTipoComp."'";
$q_update = mysql_query($s_update) or die ($s_update.mysql_error()) ;
}
/// ---------------------------------------------------------------------
///          ELIMINAR REGISTRO DE MAESTRO COMPONENTES DE UN EQUIPO
//// ---------------------------------------------------------------------
if($accion == "eliminarComponentesEquipo"){
connect();
 $s_delete = "delete from af_tipocomponente where CodTipoComp = '".$codigo."'";
 $q_delete = mysql_query($s_delete) or die ($s_delete.mysql_error());
}
//// ---------------------------------------------------------------------
/// 	INSERTAR LINEAS EN CARACTERISTICAS TECNICAS DEL ACTIVO
//// ---------------------------------------------------------------------
if ($accion == "insertarLineaCaracTecnicasActivo") {
connect();	
    $sa = "select * from af_caracteristicatecnica where Estado='A'";
    $qa = mysql_query($sa) or die ($sa.mysql_error());
    $ra = mysql_num_rows($qa);
	$cont = $_POST['cont'];
    ?>
    <td align="center">
      <input type="text" id="cont" name="cont" value="<?=$cont;?>" size="1" style="text-align:right" readonly/>
    </td>
	<td>
		<select name="select1" style="width:100%;">
        <?
         if($ra!=0){
           for($i=0;$i<$ra;$i++){
            $fa = mysql_fetch_array($qa); 
        ?>
			<option value="<?=$fa['CodCaractTecnica']?>"><?=$fa['DescripcionLocal']?></option>
        <? }}?>  
		</select>	
	</td>
	<td align="center">
		<input type="text" name="cantidad" style="width:100%; text-align:right"/>
	</td>
    <td>
		<input type="text" name="comentario" style="width:100%;"/>
	</td>
    <td>
		<input type="text" name="observaciones" style="width:100%;"/>
	</td>
	<?
}
//// ---------------------------------------------------------------------
/// 			INSERTAR LINEAS EN PARTES DEL ACTIVO
//// ---------------------------------------------------------------------
if ($accion == "insertarLineaCaracTecnicasActivo2"){
connect();	
    $sa = "select * from af_tipocomponente where Estado='A'";
    $qa = mysql_query($sa) or die ($sa.mysql_error());
    $ra = mysql_num_rows($qa);
	$cont2 = $_POST['cont2'];
    ?>
    <td>
      <input type="text" name="cont2" value="<?=$cont2;?>" size="1" style="text-align:right" readonly />
    </td>
	<td>
		<select name="select1" style="width:100%;">
        <?
         if($ra!=0){
           for($i=0;$i<$ra;$i++){
            $fa = mysql_fetch_array($qa); 
        ?>
			<option value="<?=$fa['CodTipoComp']?>"><?=$fa['DescripcionLocal']?></option>
        <? }}?>  
		</select>	
	</td>
    <td>
		<input type="text" name="descripcion" style="width:100%;"/>
	</td>
	<td>
		<input type="text" name="marca" style="width:100%;"/>
	</td>
    <td>
		<input type="text" name="num_serie" style="width:100%; text-align:right"/>
	</td>
    <td>
		<input type="text" name="fecha_asignacion" style="width:100%;"/>
	</td>
	<?
}
//// ---------------------------------------------------------------------
//// 						GUARDAR ACTIVO MENORES
//// ---------------------------------------------------------------------
if($accion == "GuardarActivosMenores"){
connect();

$sql_codigointerno = "select max(CodigoInterno) from af_activo WHERE ClasificacionPublic20='".$ClasificacionPublic20."'"; 
$consulta_codigointerno = mysql_query($sql_codigointerno) or die ($sql_codigointerno.mysql_error());
$cantidad_filas=mysql_num_rows($consulta_codigointerno);

   if($cantidad_filas==1)
   {
 
 	$ejecuto = mysql_fetch_array($consulta_codigointerno); 
 	$codigo_interno=$ejecuto[0];	
	//obtenemos la base
	//$base=substr($cod_ant,0,-5)
	//se obitienen los últimos cinco dígitos y se convierten a entero
	$serial=intval(substr($codigo_interno,-5),10); 
	//se le suma 1 para obtener el próximo valor
	$serial=$serial+1;
	//se le concatenan los ceros al serial	
        $serial=rellenarConCero($serial, 5);	
	//se concatena la base con el serial siguiente
	$codigo_interno=$ClasificacionPublic20.$serial;	
    }
    else
    {
	$serial="00001";
	$codigo_interno=$ClasificacionPublic20.$serial;
    }
 
   $CodigoInterno=$codigo_interno;

   /// Consultar activo
   $scon = "select max(Activo) from af_activo where CodOrganismo = '".$CodOrganismo."'";
   $qcon = mysql_query($scon) or die ($scon.mysql_error());
   $fcon = mysql_fetch_array($qcon);
   
   $activo = (int) ($fcon[0]+1);
   $activo = (string) str_repeat("0",10-strlen($activo)).$activo; //echo $activo.'-';
   
   if($_POST['FechaIngreso']!='00-00-0000')$FechaIngreso = date("Y-m-d", strtotime($_POST['FechaIngreso']));
   if($_POST['NumeroOrdenFecha']!='00-00-0000')$NumeroOrdenFecha = date("Y-m-d", strtotime($_POST['NumeroOrdenFecha']));
   if($_POST['NumeroGuiaFecha']!='00-00-0000')$NumeroGuiaFecha = date("Y-m-d", strtotime($_POST['NumeroGuiaFecha'])); 
   if($_POST['DocAlmacenFecha']!='00-00-0000')$DocAlmacenFecha = date("Y-m-d", strtotime($_POST['DocAlmacenFecha'])); 
   if($_POST['InventarioFisicoFecha']!='00-00-0000')$InventarioFisicoFecha = date("Y-m-d", strtotime($_POST['InventarioFisicoFecha'])); 
   if($_POST['FacturaFecha']!='00-00-0000')$FacturaFecha = date("Y-m-d", strtotime($_POST['FacturaFecha']));
    
   $MontoCatastro = cambioFormato($_POST['MontoCatastro']);	
   $MontoLocal = cambioFormato($_POST['MontoLocal']);
   $MontoReferencia = cambioFormato($_POST['MontoReferencia']);
   $MontoMercado = cambioFormato($_POST['MontoMercado']);
   
   $s_prepor = "select CodPersona from usuarios where Usuario='".$_SESSION['USUARIO_ACTUAL']."'";
   $q_prepor = mysql_query($s_prepor) or die ($s_prepor.mysql_error());
   $r_prepor = mysql_num_rows($q_prepor);
   if($r_prepor!=0) $f_prepor = mysql_fetch_array($q_prepor);
   
   
    
   $s_insert ="insert into af_activo(Activo, CodOrganismo, CodDependencia, Descripcion, DescpCorta,
									 CodigoBarras, CodigoInterno, Clasificacion,  Ubicacion,
									 ActivoConsolidado, EmpleadoResponsable, CentroCosto, Marca,
									 Modelo, NumeroSerie, Dimensiones, Color, FabricacionPais,
									 FabricacionAno, CodProveedor, FacturaTipoDocumento, FacturaNumeroDocumento,
									 FacturaFecha, NumeroOrden, NumeroOrdenFecha, NumeroGuia, NumeroGuiaFecha,
									 FechaIngreso, PeriodoIngreso, MontoLocal, UltimoUsuario,
									 UltimaFechaModif, SituacionActivo, FlagParaOperaciones, Naturaleza,
									 PreparadoPor, EstadoRegistro, ClasificacionPublic20, CodTipoMovimiento, OrigenActivo, Estado,
									 FechaPreparacion, Categoria, EmpleadoUsuario)
                               values
                                    ('$activo', '".$CodOrganismo."', '".$CodDependencia."', '".$Descripcion."', '".$DescpCorta."',
									'".$CodigoBarras."', '".$CodigoInterno."', '".$Clasificacion."', '".$Ubicacion."',
									'".$ActivoConsolidado."', '".$EmpleadoResponsable."', '".$CentroCosto."', '".$Marca."',
									'".$Modelo."', '".$NumeroSerie."', '".$Dimensiones."', '".$Color."', '".$FabricacionPais."',
									'".$FabricacionAno."', '".$CodProveedor."', '".$FacturaTipoDocumento."', '".$FacturaNumeroDocumento."',
									'$FacturaFecha', '".$NumeroOrden."', '$NumeroOrdenFecha', '".$NumeroGuia."', '$NumeroGuiaFecha',
									'$FechaIngreso', '".$PeriodoIngreso."', '$MontoLocal', '".$_SESSION['USUARIO_ACTUAL']."',
									'".date("Y-m-d H:i:s")."',	'".$SituacionActivo."', '".$FlagParaOperaciones."', '".$Naturaleza."',
									'".$f_prepor['CodPersona']."', 'PE', '".$ClasificacionPublic20."', '".$CodTipoMovimiento."', 'MA', 'PE',
									'".date("Y-m-d")."', '".$Categoria."', '".$EmpleadoUsuario."')";
 $q_insert = mysql_query($s_insert) or die ($s_insert.mysql_error());
}
//// ---------------------------------------------------------------------
//// 						MODIFICAR ACTIVO MENORES
//// ---------------------------------------------------------------------
if($accion == 'GuardarModificacionesActivosMenores'){
connect();

$sql_codigointerno = "select max(CodigoInterno) from af_activo WHERE ClasificacionPublic20='".$ClasificacionPublic20."'"; 
$consulta_codigointerno = mysql_query($sql_codigointerno) or die ($sql_codigointerno.mysql_error());
$cantidad_filas1=mysql_num_rows($consulta_codigointerno);

$sql_pub20_ant = "select ClasificacionPublic20 from af_activo WHERE Activo='".$Activo."'"; 
$consulta_pub20_ant = mysql_query($sql_pub20_ant) or die ($sql_pub20_ant.mysql_error());
$cantidad_filas2=mysql_num_rows($consulta_pub20_ant);
$ejecuto1 = mysql_fetch_array($consulta_pub20_ant); 
$pub20_ant=$ejecuto1[0];
//echo $pub20_ant;
//echo $ClasificacionPublic20;
//exit;
if ($pub20_ant!=$ClasificacionPublic20)
{
   if($cantidad_filas1==1)
   {
 
 	$ejecuto2 = mysql_fetch_array($consulta_codigointerno); 
 	$codigo_interno=$ejecuto2[0];	
	//obtenemos la base
	//$base=substr($cod_ant,0,-5)
	//se obitienen los últimos cinco dígitos y se convierten a entero
	$serial=intval(substr($codigo_interno,-5),10); 
	//se le suma 1 para obtener el próximo valor
	$serial=$serial+1;
	//se le concatenan los ceros al serial	
        $serial=rellenarConCero($serial, 5);	
	//se concatena la base con el serial siguiente
	$codigo_interno=$ClasificacionPublic20.$serial;	
    }
    else
    {
	$serial="00001";
	$codigo_interno=$ClasificacionPublic20.$serial;
    }
 
   $CodigoInterno=$codigo_interno;
}
   
   if($_POST['FechaIngreso']!='00-00-0000')$FechaIngreso = date("Y-m-d", strtotime($_POST['FechaIngreso']));
   if($_POST['NumeroOrdenFecha']!='00-00-0000')$NumeroOrdenFecha = date("Y-m-d", strtotime($_POST['NumeroOrdenFecha']));
   if($_POST['NumeroGuiaFecha']!='00-00-0000')$NumeroGuiaFecha = date("Y-m-d", strtotime($_POST['NumeroGuiaFecha'])); 
   if($_POST['DocAlmacenFecha']!='00-00-0000')$DocAlmacenFecha = date("Y-m-d", strtotime($_POST['DocAlmacenFecha'])); 
   if($_POST['InventarioFisicoFecha']!='00-00-0000')$InventarioFisicoFecha = date("Y-m-d", strtotime($_POST['InventarioFisicoFecha'])); 
   if($_POST['FacturaFecha']!='00-00-0000')$FacturaFecha = date("Y-m-d", strtotime($_POST['FacturaFecha']));
    
   $MontoCatastro = cambioFormato($_POST['MontoCatastro']);	
   $MontoLocal = cambioFormato($_POST['MontoLocal']);
   $MontoReferencia = cambioFormato($_POST['MontoReferencia']);
   $MontoMercado = cambioFormato($_POST['MontoMercado']);
    
   $s_update = "update 
                       af_activo 
				   set
						CodDependencia='".$CodDependencia."', Descripcion='".$Descripcion."', DescpCorta='".$DescpCorta."', 
						CodigoBarras='".$CodigoBarras."', CodigoInterno='".$CodigoInterno."', Clasificacion='".$Clasificacion."',
						Ubicacion='".$Ubicacion."', ActivoConsolidado='".$ActivoConsolidado."', EmpleadoResponsable='".$EmpleadoResponsable."',
						CentroCosto='".$CentroCosto."', Marca='".$Marca."', Modelo='".$Modelo."', NumeroSerie='".$NumeroSerie."', Dimensiones='".$Dimensiones."',
						Color='".$Color."',	FabricacionPais='".$FabricacionPais."', FabricacionAno='".$FabricacionAno."', CodProveedor='".$CodProveedor."',
						FacturaTipoDocumento='".$FacturaTipoDocumento."', FacturaNumeroDocumento='".$FacturaNumeroDocumento."', FacturaFecha='$FacturaFecha',
						NumeroOrden='".$NumeroOrden."', NumeroOrdenFecha='".$NumeroOrdenFecha."', NumeroGuia='".$NumeroGuia."', NumeroGuiaFecha='$NumeroGuiaFecha',
						MontoLocal='$MontoLocal', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFechaModif='".date("Y-m-d H:i:s")."',
						SituacionActivo='".$SituacionActivo."', FlagParaOperaciones='".$FlagParaOperaciones."', EmpleadoUsuario='".$EmpleadoUsuario."',ClasificacionPublic20='".$ClasificacionPublic20."' 
				where  
				         Activo='".$Activo."' and 
						 CodOrganismo = '".$CodOrganismo."'";
 $q_update = mysql_query($s_update) or die ($s_update.mysql_error());
}
//// ---------------------------------------------------------------------
//// 			PARA INSERTAR LINEAS EN AGRUPAR/CONSOLIDAR
//// ---------------------------------------------------------------------
if ($accion == "insertarLineaAgroCons") {
connect();	
     $sql = "select 
	 			   a.CodigoInterno,
				   a.Activo,
				   a.Descripcion,
				   b.Descripcion as DescpUbicacion
			  from 
			       af_activo a 
				   inner join af_ubicaciones b on (b.CodUbicacion=a.Ubicacion)
			 where 
			 	   a.Activo='".$codigo."'";
	 $qry = mysql_query($sql) or die ($sql.mysql_error());
	 $field = mysql_fetch_array($qry);
    ?>
    <tr class="trListaBody" onclick="mClk(this, 'sel_detalle');$('#registro').val('');" id="det_<?=$field['Activo'];?>">
	 <td><img src="imagenes/asignar2.jpg" style="width:20px;height:20px;visibility:visible"/>Interno: <input type="text" id="c_Interno" name="c_Interno" size="23" value="<?=$field['CodigoInterno'];?>" disabled/> 
     # Activo: <input type="text" id="numero_activo" name="numero_activo" size="23" value="<?=$field['Activo'];?>" disabled/> 
     Descripci&oacute;n: <input type="text" id="DescripcionActivo" name="DescripcionActivo" size="67" value="<?=$field['Descripcion'];?>" disabled/>
       Ubicaci&oacute;n: <input type="text" id="ubicacionActivo" name="ubicacionActivo" size="69" value="<?=$field['DescpUbicacion'];?>" disabled/> 
       Sitacui&oacute;n de Alquiler: <input type="text" id="situacionAlquiler" name="situacionAlquiler" size="54" value="" disabled/></td>
     </tr>
     <!--<td># Activo: <input type="text" id="numero_activo" name="numero_activo"/></td>-->
	<? echo "|".$field['Activo'];
}
//// ---------------------------------------------------------------------
//// 			PARA MOSTAR LINEAS EN AGRUPAR/CONSOLIDAR
//// ---------------------------------------------------------------------
if ($accion == "mostrarLineaAgroCons") {
connect();	
     $sql = "select 
	 			   a.CodigoInterno,
				   a.Activo,
				   a.Descripcion,
				   b.Descripcion as DescpUbicacion
			  from 
			       af_activo a 
				   inner join af_ubicaciones b on (b.CodUbicacion=a.Ubicacion)
			 where 
			 	   a.Activo='".$codigo."'";
	 $qry = mysql_query($sql) or die ($sql.mysql_error());
	 $field = mysql_fetch_array($qry);
    ?>
    <tr class="trListaBody" onclick="mClk(this, 'sel_detalle');$('#registro').val('');" id="det_<?=$field['Activo'];?>">
	 <td><img src="imagenes/asignar2.jpg" style="width:20px;height:20px;visibility:visible"/>Interno: <input type="text" id="c_Interno" name="c_Interno" size="23" value="<?=$field['CodigoInterno'];?>" disabled/> 
     # Activo: <input type="text" id="numero_activo" name="numero_activo" size="23" value="<?=$field['Activo'];?>" disabled/> 
     Descripci&oacute;n: <input type="text" id="DescripcionActivo" name="DescripcionActivo" size="67" value="<?=$field['Descripcion'];?>" disabled/>
       Ubicaci&oacute;n: <input type="text" id="ubicacionActivo" name="ubicacionActivo" size="69" value="<?=$field['DescpUbicacion'];?>" disabled/> 
       Sitacui&oacute;n de Alquiler: <input type="text" id="situacionAlquiler" name="situacionAlquiler" size="54" value="" disabled/></td>
     </tr>
     <!--<td># Activo: <input type="text" id="numero_activo" name="numero_activo"/></td>-->
	<? echo "|".$field['Activo'];
}
//// ---------------------------------------------------------------------
//// 			FUNCION PARA INSERTAR LINEAS EN CATEGORIAS NUEVA 
//// ---------------------------------------------------------------------
if ($accion == "insertarLineaTipoTransaccion") {
connect();?>
    <tr class="trListaBody" onclick="mClk(this, 'sel_sub');" id="sub_<?=$nrodetalle?>">
    <? /// CATEGORIA DEPRECIACION
	$sb="select * from af_categoriadeprec";
	$qb=mysql_query($sb) or die ($sb.mysql_error());
	$rb=mysql_num_rows($qb);
    ?>
    <td>
      <select id="select2" name="select2" class="selectSma">
      <option value=''></option>
       <?
        if($rb!=0){
		  for($j=0;$j<$rb;$j++){
		    $fb=mysql_fetch_array($qb); 
		?>
          <option value="<?=$fb['CodCategoria'];?>"><?=$fb['CodCategoria'].' - '.$fb['DescripcionLocal'] ;?></option>
		<? }}  ?>
      </select>
    </td>
	<?
    $sa = "select * from ac_contabilidades";
    $qa = mysql_query($sa) or die ($sa.mysql_error());
    $ra = mysql_num_rows($qa);
    ?>
	<td>
		<select name="select1" id="select1" class="selectSma">
        <option value=''></option>
        <?
         if($ra!=0){
           for($i=0;$i<$ra;$i++){
            $fa = mysql_fetch_array($qa); 
        ?>
			<option value="<?=$fa['CodContabilidad']?>"><?=$fa['Descripcion']?></option>
        <? }}?>  
		</select>	
	</td>
    <td><input type="text" id="secuencia" name="secuencia" size="3" style="text-align:center" value="<?=$_POST['contador'];?>"/></td>
	<td><input type="text" name="descripcion" id="descripcion" size="100"/></td>
    <td><input type="text" name="cuenta" id="cuenta_<?=$contador;?>" size="50" onclick="asumoInsert(this.id);"/></td>
    <td><select name="select3" id="select3">
          <option value=''></option>
          <option value='+'>+</option>
          <option value='-'>-</option>
		</select></td>
    <?
     $sc = "select * from mastmiscelaneosdet where CodMaestro='CML'";
	 $qc = mysql_query($sc) or die ($sc.mysql_error());
	 $rc = mysql_num_rows($qc);
	?>
    <!--<td><select id="select3" name="select3" class="selectSma">
        <option value=''></option>
        <?
         if($rc!=0){
		   for($k=0;$k<$rc;$k++){
			   $fc = mysql_fetch_array($qc);
		     echo" <option value='".$fc['CodDetalle']."'>".$fc['Descripcion']."</option>"; 
		   }
		 }
		?>
        </select></td>-->
        </tr>
	<?
}
//// ----------------------------------------------------------------------
//// 				GUARDAR REGISTRO - MAESTROS DE TIPO TRANSACCIONES
//// ----------------------------------------------------------------------
if($accion=="guardarTipoTransacciones"){
  connect();
  $sql="SELECT * FROM 
                      af_tipotransaccion 
                WHERE 
				      TipoTransaccion='".$_POST['TipoTransaccion']."'";
 $qry=mysql_query($sql) or die ($sql.mysql_error());
 $row=mysql_num_rows($qry);
 if($row==0){
  $insert="INSERT INTO af_tipotransaccion(TipoTransaccion,
                                          FlagAltaBaja,
										  Descripcion,
										  TipoVoucher,
										  Estado,
										  TransaccionesdelSistemaFlag,
										  UltimoUsuario,
										  UltimaFechaModif) 
								 VALUES ('".$TipoTransaccion."',
										 '".$FlagAltaBaja."',
										 '".$Descripcion."',
										 '".$TipoVoucher."',
										 '".$Estado."',
										 '".$flagTranSistema."',
										 '".$_SESSION['USUARIO_ACTUAL']."',
										 '".date("Y-m-d H:i:s")."')";
   $qinsert=mysql_query($insert) or die ($insert.mysql_error());
   
   $linea = split(";", $detalles);
	  foreach ($linea as $registro) {
		//list($Categoria, $Contabilidad, $Secuencia, $Descripcion, $CuentaContable, $SignoFlag, $CampoLocal) = SPLIT( '[|]', $registro);
		list($Categoria, $Contabilidad, $Secuencia, $Descripcion, $CuentaContable, $SignoFlag) = SPLIT( '[|]', $registro);
		//// Consulta para determinar la secuencia del registro
		/*$sc = "select max(Secuencia) from af_tipotranscuenta where TipoTransaccion='".$TipoTransaccion."'";
		$qc = mysql_query($sc) or die ($sc.mysql_error());
		$fc = mysql_fetch_array($qc);
		$Secuencia = (int) ($fc[0]+1);
        $Secuencia = (string) str_repeat("0",3-strlen($Secuencia)).$Secuencia; */
		
		$sqlin="INSERT INTO af_tipotranscuenta(TipoTransaccion,
		                                       Categoria,
											   Contabilidad,
											   Secuencia,
											   Descripcion,
											   CuentaContable,
											   SignoFlag,
											   UltimoUsuario,
											   UltimaFechaModif) 
									   VALUES ('".$TipoTransaccion."',
									           '$Categoria',
									           '$Contabilidad',
											   '$Secuencia',
											   '$Descripcion',
											   '$CuentaContable',
											   '$SignoFlag',
											   '".$_SESSION['USUARIO_ACTUAL']."',
											   '".date("Y-m-d H:i:s")."')";
       $qryin=mysql_query($sqlin) or die ($sqlin.mysql_error());
	  }
 }else{
   echo"¡CODIGO TRANSACCION YA EXISTE¡";
 }
}
//// ----------------------------------------------------------------------
//// ----------------- EDITAR REGISTRO - MAESTROS DE TIPO TRANSACCIONES
if($accion=="editarTipoTransacciones"){
connect();	
$sup = "update af_tipotransaccion set FlagAltaBaja = '".$FlagAltaBaja."',
									  Descripcion = '".$Descripcion."',
									  TipoVoucher ='".$TipoVoucher."',
									  Estado = '".$Estado."',
									  TransaccionesdelSistemaFlag= '".$flagTranSistema."',
									  UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
									  UltimaFechaModif = '".date("Y-m-d H:i:s")."' 
								 where 
								      TipoTransaccion = '".$TipoTransaccion."'";
$qup = mysql_query($sup) or die ($sup.mysql_error());

$linea = split(";", $detalles);
	  foreach ($linea as $registro) {
	  list($Categoria, $Contabilidad, $Secuencia, $Descripcion, $CuentaContable, $SignoFlag) = split( '[|]', $registro);
	  echo $registro;
	  
	  $scon = "select * from af_tipotranscuenta where TipoTransaccion='".$TipoTransaccion."' and Secuencia='$Secuencia'";
	  $qcon = mysql_query($scon) or die ($scon.mysql_error());
	  $rcon = mysql_num_rows($qcon);
	  
	  if($rcon!=0){
	    $sup02="update af_tipotranscuenta set Categoria='$Categoria',
									   		  Contabilidad='$Contabilidad',
									          Descripcion='$Descripcion',
											  CuentaContable='$CuentaContable',
											  SignoFlag='$SignoFlag',
											  UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
											  UltimaFechaModif='".date("Y-m-d H:i:s")."' 
							            where 
										      TipoTransaccion='$TipoTransaccion' and 
											  Secuencia = '$Secuencia'"; 
	     $qup02=mysql_query($sup02) or die ($sup02.mysql_error());
	  }else{
         $sqlin="INSERT INTO af_tipotranscuenta(TipoTransaccion,
		                                       Categoria,
											   Contabilidad,
											   Secuencia,
											   Descripcion,
											   CuentaContable,
											   SignoFlag,
											   UltimoUsuario,
											   UltimaFechaModif) 
									   VALUES ('".$TipoTransaccion."',
									           '$Categoria',
									           '$Contabilidad',
											   '$Secuencia',
											   '$Descripcion',
											   '$CuentaContable',
											   '$SignoFlag',
											   '".$_SESSION['USUARIO_ACTUAL']."',
											   '".date("Y-m-d H:i:s")."')"; 
       $qryin=mysql_query($sqlin) or die ($sqlin.mysql_error());
       
	  }
	  }
}
//// ----------------------------------------------------------------------
//// ----------------- GUARDAR NUEVO REGISTRO PUBLICACION 20
if($accion=='guardarNuevoPublicacion20'){ 
  connect();
  $cod = 0;
	  list($codClasificacion, $descripcion01) = split('[-]', $_POST['codigo2']);
	  if($nivel=='1'){
	    $sql = "select max(CodClasificacion) from af_clasificacionactivo20 where Nivel = '".$nivel."'"; 
	    $qry = mysql_query($sql)  or die ($sql.mysql_error());
	    $field = mysql_fetch_array($qry);
		$cod_clasficiacion20 = (int) ($field[0] + 1);
	    $cod_clasficiacion20 = (string) str_repeat("0",2-strlen($cod_clasficiacion20)).$cod_clasficiacion20;
	  }else
	    if($nivel=='2'){
			 $sql = "select 
			                CodClasificacion,
							Descripcion 
					    from 
						    af_clasificacionactivo20
						where 
						    CodClasificacion =(select max(CodClasificacion) from af_clasificacionactivo20 where CodClasificacion like '$codClasificacion%' and Nivel='2')"; 
	         $qry = mysql_query($sql)  or die ($sql.mysql_error());
			 $row = mysql_num_rows($qry);
			 if($row!=0){ 
			    $field = mysql_fetch_array($qry);
			    $cod = substr($field[0], -2); //echo 'cod=  '.$cod;    /// Cola 
			    $cod2 = substr($field[0], 0, -2); //echo 'cod2=  '.$cod2; /// Punta  
			    if($cod<'99'){
			      $cod_clasficiacion20 = (int)($cod+1);
			      $cod_clasficiacion20 = (string) str_repeat('0', 2-strlen($cod_clasficiacion20)).$cod_clasficiacion20;
				  $cod_clasficiacion20 = $cod2.''.$cod_clasficiacion20;
			    }else echo"!NO PUEDE SER INGRESADO POR SUPERAR EL LIMITE DE REGISTRO POR NIVEL!";
			 }else{
			    $cod_clasficiacion20 = (int)($cod+1);
			    $cod_clasficiacion20 = (string) str_repeat('0', 2-strlen($cod_clasficiacion20)).$cod_clasficiacion20;
				$cod_clasficiacion20 = $codClasificacion.''.$cod_clasficiacion20;
			 }
	  }else
	    if($nivel=='3'){ 
			 $sql = "select 
			                CodClasificacion,
							descripcion
					   from 
					        af_clasificacionactivo20 
					   where 
					        CodClasificacion = (select max(CodClasificacion) from af_clasificacionactivo20 where CodClasificacion like '$codClasificacion%' and Nivel='3')";
	         $qry = mysql_query($sql)  or die ($sql.mysql_error());
			 $row = mysql_num_rows($qry); 
			  if($row!=0){
				 $field = mysql_fetch_array($qry);
				 $cod = substr($field[0], -3);
			     $cod2 = substr($field[0], 0, -3);
				 if($cod<'999'){
				   $cod_clasficiacion20 = (int)($cod+1);
			       $cod_clasficiacion20 = (string) str_repeat('0', 3-strlen($cod_clasficiacion20)).$cod_clasficiacion20;
				   $cod_clasficiacion20 = $cod2.''.$cod_clasficiacion20;
			     }else echo"!NO PUEDE SER INGRESADO POR SUPERAR EL LIMITE DE REGISTRO POR NIVEL!";
			   }else{
			     $cod_clasficiacion20 = (int)($cod+1);
			     $cod_clasficiacion20 = (string) str_repeat('0', 3-strlen($cod_clasficiacion20)).$cod_clasficiacion20;
				 $cod_clasficiacion20 = $codClasificacion.''.$cod_clasficiacion20;
			   }
	    }else
		 if($nivel=='4'){ 
			 $sql = "select 
			                CodClasificacion,
							Descripcion
					   from 
					        af_clasificacionactivo20 
					   where 
					        CodClasificacion = (select max(CodClasificacion) from af_clasificacionactivo20 where CodClasificacion like '$codClasificacion%' and Nivel='4')";
	         $qry = mysql_query($sql)  or die ($sql.mysql_error());
			 $row = mysql_num_rows($qry);
			 if($row!=0){
				 $field = mysql_fetch_array($qry);
				 $cod = substr($field[0], -3);     /// Cola
			     $cod2 = substr($field[0], 0, -3); /// Punta 
				 if($cod<'999'){
				   $cod_clasficiacion20 = (int)($cod+1);
			       $cod_clasficiacion20 = (string) str_repeat('0', 3-strlen($cod_clasficiacion20)).$cod_clasficiacion20;
				   $cod_clasficiacion20 = $cod2.''.$cod_clasficiacion20;
			     }else echo"!NO PUEDE SER INGRESADO POR SUPERAR EL LIMITE DE REGISTRO POR NIVEL!";
			  }else{
			     $cod_clasficiacion20 = (int)($cod+1);
			     $cod_clasficiacion20 = (string) str_repeat('0', 3-strlen($cod_clasficiacion20)).$cod_clasficiacion20;
				 $cod_clasficiacion20 = $codClasificacion.''.$cod_clasficiacion20;
			  }
		 }
	     if ($cod_clasficiacion20!=""){
			 $s_insert = "INSERT INTO af_clasificacionactivo20 (CodClasificacion,
															 Descripcion,
															 Nivel,
															 Estado,
															 UltimoUsuario,
															 UltimaFecha)
													VALUES ('$cod_clasficiacion20',
															'".utf8_decode($descripcion)."',
															'$nivel',
															'".$status."',
															'".$_SESSION['USUARIO_ACTUAL']."',
															'".date("Y-m-d H:i:s")."')"; 
			 $q_insert = mysql_query($s_insert) or die ($s_insert.mysql_error());
		 }
}
//// ----------------------------------------------------------------------
//// ----------------- EDITAR REGISTRO PUBLICACION 20
if($accion == "EditarPublicacion20"){
 connect();	
		$sql = "UPDATE af_clasificacionactivo20 SET   Descripcion = '".utf8_decode($descripcion)."',
													  Estado = '".$status."',
													  UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
													  UltimaFecha = '".date("Y-m-d H:i:s")."'
												WHERE 
													  CodClasificacion = '".$codigo."' AND Nivel = '".$nivel."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
}
//// ----------------------------------------------------------------------
//// ----------------- ELIMINAR REGISTRO PUBLICACION 20
//// ----------------------------------------------------------------------
if($accion=='eliminarClasificacion20'){
 connect();
 	
 $scon = "select * from af_clasificacionactivo20 where CodClasificacion='".$codigo."' ";
 $qcon = mysql_query($scon) or die ($scon.mysql_error());
 $fcon = mysql_fetch_array($qcon);
 
 if($fcon['Nivel']=='1') $cola = '01';
 else if($fcon['Nivel']=='2') $cola = '001';
 else if($fcon['Nivel']=='3') $cola = '001';
 else if($fcon['Nivel']=='4') $cola = '001';
 
 $codigo = $fcon['CodClasificacion'].''.$cola;
 
 /// Consultando si posee nivel siguiente
 $snivel = "select * from af_clasificacionactivo20 where CodClasificacion='$codigo'";
 $qnivel = mysql_query($snivel) or die ($snivel.mysql_error());
 $rnivel = mysql_num_rows($qnivel);
 
 if($rnivel!=0){
	$fnivel=mysql_fetch_array($qnivel);
	echo"!NO PUEDE SER ELIMINADO,REVISE CLASIFICACION: ".$fnivel['CodClasificacion'];
 }else{
   $sd = "delete from af_clasificacionactivo20 where CodClasificacion='".$fcon['CodClasificacion']."'";
   $qd= mysql_query($sd) or die ($sd.mysql_error()); 
 }
}
//// ----------------------------------------------------------------------
//// ----------------- GUARDAR PROCESO NUEVA TRANSACCION BAJA
//// ----------------------------------------------------------------------
if($accion=='guardarTransaccionBaja'){
connect();
   /// Consulto para verificar el tipo de transacción seleccionado
   $s_tiptrans = "select 
     					 a.*,
						 b.* 
				  from   
				  	     af_tipotransaccion a 
						 inner join af_tipotranscuenta b on (b.TipoTransaccion = a.TipoTransaccion) 
				  where  
				  		a.TipoTransaccion = '".$tipobaja."'";
   $q_tiptrans = mysql_query($q_tiptrans) or die ($q_tiptrans.mysql_error());
   $r_tiptrans = mysql_num_rows($q_tiptrans);
   if($r_tiptrans!=0)$f_tiptrans = mysql_fetch_array($q_tiptrans);
   
   /// Insert de datos.....
   $sin = "insert into af_transaccionbaja(Activo, Organismo, TipoTransaccion, Dependencia,
   										  Fecha, CentroCosto, ContabilizadoFlag, Responsable,
										  ConceptoMovimiento, CodigoInterno, Categoria, Estado, 
										  Ubicacion, Comentario, Periodo) 
   								   values('".$Activo."','".$Organismo."','".$TipoTransaccion."','".$Dependencia."',
								   		  '".date("Y-m-d")."','".$CentroCosto."','".$ContabilizadoFlag."','".$Responsable."',
										  '".$ConceptoMovimiento."','".$CodigoInterno."','".$Categoria."','PR',
										  '".$Ubicacion."','".$Comentario."', '".date("Y-m")."')";
   $qin = mysql_query($sin) or die ($sin.mysql_error());
   
   $sin02 = "insert into af_transaccionbajacuenta(Activo, Contabilidad, Secuencia, CuentaContable,
   												  Descripcion, MontoLocal, MontoALocal, Fecha) 
   										   values('".$Activo."', '".$Contabilidad."', '".$Secuencia."', '".$CuentaContable."',
										          '".$Descripcion."','".$MontoLocal."', '".$MontoLocal."', '".date("Y-m-d")."')";
   $qin02 = mysql_query($sin02) or die ($sin02.mysql_error());
}
//// ----------------------------------------------------------------------
//// ----------------- ANULAR TRANSACCION BAJA
//// ----------------------------------------------------------------------
if($accion=="anularRegistroTransaccionActivo"){
 $sql = "select Estado from af_transaccionbaja where Activo='".$codigo."'";
 $qry = mysql_query($sql) or die ($sql.mysql_error());
 $row = mysql_num_rows($qry);
 
 if($row!=0) $field = mysql_fetch_array($qry);
 if($field['Estado']=="AP"){
    $sup = "update af_transaccionbaja set Estado='PR' where Activo='".$codigo."'";
	$qup = mysql_query($sup) or die ($sup.mysql_error());
	
	$sth = "delete from af_historicotransaccion where Activo='".$codigo."' and Secuencia=(select max(Secuencia) from af_historicotransaccion where Activo='".$codigo."')";
	$qth = mysql_query($sth) or die ($sth.mysql_error());
	
 }else{
     $sup = "update af_transaccionbaja set Estado='PR' where Activo='".$codigo."'";
	 $qup = mysql_query($sup) or die ($sup.mysql_error());
 }
}
//// ----------------------------------------------------------------------
//// FUNCION PARA MOSTRAR INFORMACION TRANSACCIONES EN  LISTA ACTIVOS - AGREGAR
//// ----------------------------------------------------------------------
if ($accion == "insertarDatos_1") {
connect();
echo"<table width='890' border='0' align='center'>";
$sql = "select 
			  a.* ,
			  b.Descripcion as DescpContabilidad
		  from 
		      af_tipotranscuenta a 
			  inner join ac_contabilidades b on (b.CodContabilidad=a.Contabilidad) 
		 where 
		      a.TipoTransaccion='".$tipobaja."' 
		order by a.Contabilidad, a.Secuencia";
$qry = mysql_query($sql) or die ($sql.mysql_error());
$row = mysql_num_rows($qry);

if($row!=0){
 for($i=0; $i<$row; $i++){
  $field = mysql_fetch_array($qry);
  if($field['SignoFlag']=='')$SignoFlag='+';else $SignoFlag='-';
  if($contabilidad!=$field['Contabilidad']){
  echo"<tr >
         <td align='center' width='31' style='font-family:Lucida Grande, Verdana, Arial, Helvetica, sans-serif;font-size:10px;background-color:#C0C0C0;border-style:outset;'><b>".$field['Contabilidad']."</b></td>
		 <td align='center' width='117' style='font-family:Lucida Grande, Verdana, Arial, Helvetica, sans-serif;font-size:10px;background-color:#C0C0C0;border-style:outset;'><b>".$field['DescpContabilidad']."</b></td>
      </tr>";
  $contabilidad =$field['Contabilidad'];
  }
  echo"<tr>
    <td width='31' align='right' style='font-family:Lucida Grande, Verdana, Arial, Helvetica, sans-serif;font-size:10px;'>".$field['Secuencia']."</td>
	<td width='117' style='font-family:Lucida Grande, Verdana, Arial, Helvetica, sans-serif;font-size:10px;'>".$field['CuentaContable']."</td>
	<td width='360' style='font-family:Lucida Grande, Verdana, Arial, Helvetica, sans-serif;font-size:10px;'>".$field['Descripcion']."</td>
	<td width='40' style='font-family:Lucida Grande, Verdana, Arial, Helvetica, sans-serif;font-size:10px;text-align:right;'>".$monto."</td>
	<td width='35' style='font-family:Lucida Grande, Verdana, Arial, Helvetica, sans-serif;font-size:10px;' align='center'>".$SignoFlag."</td>
  </tr>";
 }
}

echo"</table>";
}
//// ----------------------------------------------------------------------
//// FUNCION PARA MOSTRAR INFORMACION TRANSACCIONES EN  BAJA ACTIVOS NUEVA TRANSACCION
//// ----------------------------------------------------------------------
if ($accion == "insertarDatos_2") {
connect();
echo"<table width='820' border='0'>";
$sql = "select 
			  a.* ,
			  b.Descripcion as DescpContabilidad
		  from 
		      af_tipotranscuenta a 
			  inner join ac_contabilidades b on (b.CodContabilidad=a.Contabilidad) 
		 where 
		      a.TipoTransaccion='".$tipobaja."' 
		order by a.Contabilidad, a.Secuencia"; 
$qry = mysql_query($sql) or die ($sql.mysql_error());
$row = mysql_num_rows($qry);

if($row!=0){
 for($i=0; $i<$row; $i++){
  $field = mysql_fetch_array($qry);
  if($field['SignoFlag']=='')$SignoFlag='+';else $SignoFlag='-';
  if($contabilidad!=$field['Contabilidad']){
  echo"<tr >
         <td align='center' width='36' style='font-family:Lucida Grande, Verdana, Arial, Helvetica, sans-serif;font-size:10px;background-color:#C0C0C0;border-style:outset;'><b>".$field['Contabilidad']."</b></td>
		 <td align='center' width='150' style='font-family:Lucida Grande, Verdana, Arial, Helvetica, sans-serif;font-size:10px;background-color:#C0C0C0;border-style:outset;'><b>".$field['DescpContabilidad']."</b></td>
		 </tr>";
  $contabilidad =$field['Contabilidad'];
  }
  echo"<tr>
    <td align='right' style='font-family:Lucida Grande, Verdana, Arial, Helvetica, sans-serif;font-size:10px;'>".$field['Secuencia']."</td>
	<td style='font-family:Lucida Grande, Verdana, Arial, Helvetica, sans-serif;font-size:10px;'>".$field['CuentaContable']."</td>
	<td width='370' style='font-family:Lucida Grande, Verdana, Arial, Helvetica, sans-serif;font-size:10px;'>".$field['Descripcion']."</td>
	<td width='70' style='font-family:Lucida Grande, Verdana, Arial, Helvetica, sans-serif;font-size:10px;text-align:right;'>".$monto."</td>
  </tr>";
 }
}

echo"</table>";
}
//// ----------------------------------------------------------------------
////  GUARDAR REGISTRO - AGRUPAR/CONSOLIDAR ACTIVOS - MENÚ OTROS
//// ----------------------------------------------------------------------
if($accion=="grabarAgrupacionConsolidacion"){
  connect();
     
   $linea = split(";", $detalles);
	  foreach ($linea as $registro) {
		 $supdate = " update af_activo set ActivoConsolidado ='$activo' where Activo='$registro' ";
         $qupdate = mysql_query($supdate) or die ($supdate.mysql_error());
 }
}
?>

