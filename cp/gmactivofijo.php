<? include("fphp.php");
$year_completa= date("Y-m-d H:m:s");
//// _____________________________________________________________
////               GUARDAR CATEGORIAS DEPRECIACION                     ////
//// _____________________________________________________________
if($accion==guardarCategoria){
 $sql="SELECT * FROM af_categoriadeprec 
                WHERE CodCategoria='".$_POST['cod_librocontable']."' AND
				      DescripcionLocal='".$_POST['descp_libro']."'";
 $qry=mysql_query($sql) or die ($sql.mysql_error());
 $row=mysql_num_rows($qry);
 if($row==0){
  $insert="INSERT INTO af_categoriadeprec ( CodCategoria,
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
										  Estado) 
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
								        '".$_POST['estado']."')";
  $qinsert=mysql_query($insert) or die ($insert.mysql_error());
 }else{
  echo"<script>";
  echo"alert('¡LOS DATOS HAN SIDO INTRODUCIDOS ANTERIORMENTE¡')";
  echo"</script>";
 }
       //// ________________________________________________
      ////         GUARDAR CATEGORIA CONTABILIDAD       ////
     //// __________________________________________________
 	$sqlin="INSERT INTO af_categoriacontabilidad(CodCategoria,
             								CodContabilidad,
											DepreciacionPorcentaje,
											VidaUtil) 
									 VALUES ('".$_POST['codcategoria']."',
									        '".$_POST['s_contabilidad']."',
											'".$_POST['s_depreciacion']."',
											'2,5')";
  $qryin=mysql_query($sqlin) or die ($sqlin.mysql_error());
}
 
//// _____________________________________________________________
////               EDITAR CATEGORIAS DEPRECIACION             ////
//// _____________________________________________________________
if($accion==editarCategoria){
 $sql="UPDATE af_categoriadeprec SET (DescripcionLocal='".$_POST['descp_local']."',
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
									  Estado='".$_POST['estado']."')
							   WHERE  
							          CodCategoria='".$_POST['codcategoria']."'";
 $qry=mysql_query($sql) or die ($sql.mysql_error());
 
       //// ________________________________________________
      ////         EDITAR CATEGORIA CONTABILIDAD       ////
     //// __________________________________________________
 	$supdate="UPDATE af_categoriacontabilidad(CodContabilidad,
											DepreciacionPorcentaje,
											VidaUtil) 
									 VALUES ('".$_POST['codcategoria']."',
									        '".$_POST['s_contabilidad']."',
											'".$_POST['s_depreciacion']."',
											'2,5')
									 WHERE CodCategoria='".$_POST['codcategoria']."'";
   $qupdate=mysql_query($sqlin) or die ($sqlin.mysql_error());
}

//// _____________________________________________________________
////               GUARDAR LIBRO CONTABLE                     ////
//// _____________________________________________________________
if($accion==guardarLibroContable){
  $sql="SELECT * FROM ac_librocontable 
                WHERE CodLibroCont='".$_POST['cod_librocontable']."' AND
				      Descripcion='".$_POST['descp_libro']."'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
  $row=mysql_num_rows($qry);
  if($row==0){
   $insert="INSERT INTO ac_librocontable (CodLibroCont,
                                         Descripcion,
										 Estado) 
								 VALUES ('".$_POST['cod_librocontable']."',
								        '".$_POST['descp_libro']."',
										'".$_POST['status_libro']."')";
   $qinsert=mysql_query($insert) or die ($insert.mysql_error());
  }else{
   echo"<script>";
   echo"alert('¡LOS DATOS HAN SIDO INTRODUCIDOS ANTERIORMENTE¡')";
   echo"</script>";
  }
}
//// _____________________________________________________________
////               EDITAR LIBRO CONTABLE                     ////
//// _____________________________________________________________
if($accion==editarLibroContable){
 $supdate="UPDATE ac_librocontable SET   Descripcion='".$_POST['descp_libro']."',
										 Estado='".$_POST['status_libro']."',
										 UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
										 UltimaFechaModif='$year_completa' 
								 WHERE  CodLibroCont='".$_POST['registro']."'";
  $qupdate=mysql_query($supdate) or die ($supdate.mysql_error());
}
//// _____________________________________________________________
////               GUARDAR CONTABILIDADES                     ////
//// _____________________________________________________________
if($accion==guardarContabilidades){
  $sql="SELECT * FROM ac_contabilidades 
                WHERE CodContabilidad='".$_POST['cod_contabilidad']."' AND
				      Descripcion='".$_POST['descp_contabilidad']."'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
  $row=mysql_num_rows($qry);
  if($row==0){
   $insert="INSERT INTO ac_contabilidades(CodContabilidad,
                                         Descripcion,
										 Estado) 
								 VALUES ('".$_POST['cod_contabilidad']."',
								        '".$_POST['descp_contabilidad']."',
										'".$_POST['status_contabilidad']."')";
   $qinsert=mysql_query($insert) or die ($insert.mysql_error());
  }else{
   echo"<script>";
   echo"alert('¡LOS DATOS HAN SIDO INTRODUCIDOS ANTERIORMENTE¡')";
   echo"</script>";
  }
}
//// _____________________________________________________________
////               EDITAR CONTABILIDADES                     ////
//// _____________________________________________________________
if($accion==editarContabilidades){
 $supdate="UPDATE ac_contabilidades SET  Descripcion='".$_POST['descp_contabilidad']."',
										 Estado='".$_POST['status_contabilidad']."',
										 UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
										 UltimaFechaModif='$year_completa' 
								 WHERE  CodContabilidad='".$_POST['registro']."'";
  $qupdate=mysql_query($supdate) or die ($supdate.mysql_error());
}
//// _____________________________________________________________
////               GUARDAR SITUACION DEL ACTIVO               ////
//// _____________________________________________________________
if($accion==guardarSituactivo){
  $sql="SELECT * FROM af_situacionactivo 
                WHERE CodSituActivo='".$_POST['cod_situactivo']."' AND
				      Descripcion='".$_POST['descp_situactivo']."'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
  $row=mysql_num_rows($qry);
  if($row==0){
   $insert="INSERT INTO af_situacionactivo(CodSituActivo,
                                         Descripcion,
										 DepreciacionFlag,
										 RevaluacionFlag,
										 Estado) 
								 VALUES ('".$_POST['cod_situactivo']."',
								        '".$_POST['descp_situactivo']."',
										'".$_POST['proceso_situactivo']."',
										'".$_POST['proceso_ajuste']."',
										'".$_POST['status_situactivo']."')";
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
   $supdate="UPDATE af_situacionactivo SET Descripcion='".$_POST['descp_situactivo']."',
											 DepreciacionFlag='".$_POST['proceso_situactivo']."',
											 RevaluacionFlag='".$_POST['proceso_ajuste']."',
											 Estado='".$_POST['status_situactivo']."' 
									   WHERE CodSituActivo='".$_POST['cod_situactivo']."' ";
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
//// _____________________________________________________________
////               GUARDAR CATASTRO                           ////
//// _____________________________________________________________
//// _____________________________________________________
        /// MAESTRO DE CATASTRO INSERTANDO FILAS 
//// _____________________________________________________
if ($accion == "insertarLinea") {
	?>
	<td>
		<input name="codempleado" type="hidden" id="codempleado" value="" />
	   <input name="nomempleado" id="nomempleado" type="text" size="60" readonly/>
	   <input name="bt_examinar" id="bt_examinar" type="button" value="..." onclick="cargarVentana(this.form, 'lista_empleados.php?limit=0&campo=1', 'height=500, width=800, left=200, top=200, resizable=yes');" />
	</td>
	<?
}

if ($accion==guardarCatastro) {
	$sql = "INSERT INTO af_catastro (CodCatastro, 
	                                 Descripcion, 
									 Estado) 
							  VALUES ('$cod_catastro',
							          '$descp_catastro',
									  '$status_catastro')";	echo "$sql \n";
    $query_insert = mysql_query($sql) or die ($sql.mysql_error());		
	
	//	detalles
	$linea = split(";", $detalles);
	foreach ($linea as $registro) {
		list($cod_catastro, $ano, $precio_Oficial, $precio_Mercado, $fecha_Referencial) = SPLIT( '[|]', $registro);
		
		$sql = "INSERT INTO af_catastroanual (CodiCatastro, 
		                                      Ano, 
											  PrecioOficial,
											  PrecioMercado,
											  FechaPreferencia) 
									  VALUES ('".$_POST['cod_catastro']."',
									          '$ano', 
									          '$precio_Oficial', 
											  '$precio_Mercado',
											  '$fecha_Referencial')";		echo "$sql \n";
		//$query_insert = mysql_query($sql) or die ($sql.mysql_error());
	}
}





?>