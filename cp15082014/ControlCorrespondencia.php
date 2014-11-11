<?
//// _____________________________________________________________________
$fecha=date("Y-m-d");
$hora=time("H:i:s");
$fechaCompleta=date("Y-m-d H:i:s");
$year=date("Y");
//include "fphp.php";
//// _____________________________________________________________________
//// ---------------------------------------------------------------------
//// GUARDAR INFORMACION DOCUMENTO EXTERNO ENTRADA
if($accion==GuardarEntradaExterna){
    //$connect();
    $scon="select max(Cod_Documento) from cp_documentoextentrada where Periodo='$year'";
	$qcon=mysql_query($scon) or die ($scon.mysql_error());
	$fcon=mysql_fetch_array($qcon);
	$cod_documento=(int) ($fcon[0]+1); /// CODIGO DEL DOCUMENTO
    $cod_documento=(string) str_repeat("0",4-strlen($cod_documento)).$cod_documento;
	//// ---------------------------------------------------------------------------------------------
	/*$scon2="select max(RegistroInt) from cp_documentoextentrada";
	$qcon2=mysql_query($scon2) or die ($scon2.mysql_error());
	$fcon2=mysql_fetch_array($qcon2);
	$registroInt=(int) ($fcon2[0]+1); /// CODIGO DEL DOCUMENTO
    $registroInt=(string) str_repeat("0",3-strlen($registroInt)).$registroInt;*/
	$num_registroInt = $cod_documento."-".$year;
	//// ---------------------------------------------------------------------------------------------
	$fecharegistro= $_POST['fecha_recibido']; $fecharegistro=date("Y-m-d",strtotime($fecharegistro));
	$fechadocumento= $_POST['fecha_documento']; $fechadocumento=date("Y-m-d",strtotime($fechadocumento)); 
    
	if($_POST['organismo']!=''){
    $sinsert="insert into cp_documentoextentrada( CodOrganismo,
												  Cod_Documento,
												  Cod_TipoDocumento,
												  FechaRegistro,
												  Cod_Organismos,
												  Cod_Dependencia,
												  Remitente,
												  Cargo,
												  NumeroDocumentoExt,
												  FechaDocumentoExt,
												  Asunto,
												  Descripcion,
												  RecibidoPor,
												  CargoRecibidoPor,
												  Folio,
												  AnexoFolio,
												  DescpFolio,
												  Estado,
												  Carpetas,
												  Mensajero,
												  CedulaMensajero,
												  Periodo,
												  NumeroRegistroInt,
												  UltimoUsuario,
												  UltimaFechaModif,
												  FlagEsParticular)
											values ('".$_SESSION['ORGANISMO_ACTUAL']."',
											       '$cod_documento',
												   '".$_POST['t_documento']."', 
											       '$fecharegistro',
												   '".$_POST['organismo']."',
												   '".$_POST['dep_externa']."',
												   '".utf8_decode($_POST['remitente_ext'])."',
												   '".utf8_decode($_POST['cargoremitente_ext'])."',
												   '".$_POST['n_documento']."',
												   '$fechadocumento',
												   '".$_POST['asunto']."',
												   '".$_POST['descripcion']."',
												   '".$_POST['codempleado']."',
												   '".$_POST['cod_cargoremit']."',
												   '".$_POST['folio']."',
												   '".$_POST['anexofolio']."',
												   '".$_POST['descpfolio']."',
												   'PE',
												   '".$_POST['nro_carpeta']."',
												   '".$_POST['mensajero']."',
												   '".$_POST['ci_mensajero']."',
												   '$year',
												   '$num_registroInt',
												   '".$_SESSION['USUARIO_ACTUAL']."',
												   '$fechaCompleta',
												   'N')";
    $qinsert=mysql_query($sinsert) or die ($sinsert.mysql_error());
	
	}else{
	   $sinsert="insert into cp_documentoextentrada( CodOrganismo,
												  Cod_Documento,
												  Cod_TipoDocumento,
												  FechaRegistro,
												  Cod_Organismos,
												  Remitente,
												  Cargo,
												  NumeroDocumentoExt,
												  FechaDocumentoExt,
												  Asunto,
												  Descripcion,
												  RecibidoPor,
												  Folio,
												  AnexoFolio,
												  DescpFolio,
												  Estado,
												  Carpetas,
												  Mensajero,
												  CedulaMensajero,
												  Periodo,
												  NumeroRegistroInt,
												  UltimoUsuario,
												  UltimaFechaModif,
												  FlagEsParticular,
												  CargoRecibidoPor)
											values ('".$_SESSION['ORGANISMO_ACTUAL']."',
											       '$cod_documento',
												   '".$_POST['t_documento']."', 
											       '$fecharegistro',
												   '".$_POST['codParticular']."',
												   '".utf8_decode($_POST['p_nombre'])."',
												   '".utf8_decode($_POST['p_cargo'])."',
												   '".$_POST['n_documento']."',
												   '$fechadocumento',
												   '".$_POST['asunto']."',
												   '".$_POST['descripcion']."',
												   '".$_POST['codempleado']."',
												   '".$_POST['folio']."',
												   '".$_POST['anexofolio']."',
												   '".$_POST['descpfolio']."',
												   'PE',
												   '".$_POST['nro_carpeta']."',
												   '".$_POST['mensajero']."',
												   '".$_POST['ci_mensajero']."',
												   '$year',
												   '$num_registroInt',
												   '".$_SESSION['USUARIO_ACTUAL']."',
												   '$fechaCompleta',
												   'S',
												   '".$_POST['cod_cargoremit']."')";
          $qinsert=mysql_query($sinsert) or die ($sinsert.mysql_error());
	}
 }
//// ---------------------------------------------------------------------
//// EDITAR DOCUMENTO EXTERNO (SOLO DATOS GENERALES)
//// ---------------------------------------------------------------------
if($accion==EditarEntradaExterna){
 //$connect();
 $fechadocumento= $_POST['fecha_documento']; $fechadocumento=date("Y-m-d",strtotime($fechadocumento));
 if($_POST['flagParticular'] =='N'){
  $sql="UPDATE cp_documentoextentrada SET
                                          Asunto = '".$_POST['asunto']."',
										  Cod_TipoDocumento='".$_POST['t_documento']."',
                                          NumeroDocumentoExt='".$_POST['n_documento']."',
										  FechaDocumentoExt='$fechadocumento',
										  Cod_Organismos='".$_POST['organismo']."',
										  Cod_Dependencia='".$_POST['dep_externa']."',
										  Remitente='".utf8_decode($_POST['remitente_ext'])."',
										  Cargo='".utf8_decode($_POST['cargoremitente_ext'])."',
										  RecibidoPor='".$_POST['codempleado']."',
										  CargoRecibidoPor='".$_POST['codempleado']."',
										  Descripcion='".$_POST['descripcion']."',
										  Folio='".$_POST['folio']."',
										  AnexoFolio='".$_POST['anexofolio']."',
										  DescpFolio='".$_POST['descpfolio']."',
										  Carpetas = '".$_POST['nro_carpeta']."',
										  Mensajero = '".$_POST['mensajero']."',
										  CedulaMensajero = '".$_POST['ci_mensajero']."',
										  UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
										  UltimaFechaModif='$fechaCompleta'
								    WHERE
									      CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' AND
										  NumeroRegistroInt='".$_POST['numero_registro']."'"; //echo $sql;
 $qry=mysql_query($sql) or die ($sql.mysql_error());
 }else{
   $sql="UPDATE cp_documentoextentrada SET
                                          Asunto = '".$_POST['asunto']."',
										  Cod_TipoDocumento='".$_POST['t_documento']."',
                                          NumeroDocumentoExt='".$_POST['n_documento']."',
										  FechaDocumentoExt='$fechadocumento',
										  Cod_Organismos='".$_POST['codParticular']."',
										  Cod_Dependencia='".$_POST['dep_externa']."',
										  Remitente='".utf8_decode($_POST['remitente_ext'])."',
										  Cargo='".utf8_decode($_POST['cargoremitente_ext'])."',
										  RecibidoPor='".$_POST['codempleado']."',
										  CargoRecibidoPor='".$_POST['cod_cargoremit']."',
										  Descripcion='".$_POST['descripcion']."',
										  Folio='".$_POST['folio']."',
										  AnexoFolio='".$_POST['anexofolio']."',
										  DescpFolio='".$_POST['descpfolio']."',
										  Carpetas = '".$_POST['nro_carpeta']."',
										  Mensajero = '".$_POST['mensajero']."',
										  CedulaMensajero = '".$_POST['ci_mensajero']."',
										  UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
										  UltimaFechaModif='$fechaCompleta'
								    WHERE
									      CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' AND
										  NumeroRegistroInt='".$_POST['numero_registro']."'"; //echo $sql;
   $qry=mysql_query($sql) or die ($sql.mysql_error());
 }
}
//// ---------------------------------------------------------------------
//// GUARDAR INFORMACION DE SALIDA EXTERNA DE DOCUMENTOS
if($accion==GuardarSalidaExterna){
 //$connect();
   $sql="select max(Cod_Documento) 
           from 
		        cp_documentoextsalida 
		  where 
		        Organismo='".$_SESSION['ORGANISMO_ACTUAL']."' and
			    Cod_TipoDocumento='".$_POST['t_documento']."'";
	$qry=mysql_query($sql) or die ($sql.mysql_error());
	$field=mysql_fetch_array($qry);
	
	$cod_documento=(int) ($field[0]+1); /// CODIGO DEL DOCUMENTO
    $cod_documento=(string) str_repeat("0",2-strlen($cod_documento)).$cod_documento;
	$dep=$_POST['dependencia']; 
	echo substr('$dep',2,2);
	//$dep=substr('$dep',);
	//$cod_documentocompleto=CEDA"-"
	
	
    $sinsert="insert into cp_documentoextsalida(CodOrganismo ,
											  Cod_Documento ,
											  Cod_TipoDocumento ,
											  Cod_DocumentoCompleto,
											  Cod_Dependencia ,
											  FechaRegistro,
											  Cod_Organismos ,
											  FlagRemitenteOrg,
											  FlagRemitenteDep,
											  Destinatario,
											  CargoDestinatario,
											  Remitente,
											  Cargo,
											  Asunto,
											  Descripcion,
											  FlagConfidencial)
										values('".$_SESSION['ORGANISMO_ACTUAL']."',
										      '$cod_documento')";
   $qinsert=mysql_query($sinsert) or die ($sinsert.mysql_error());
 } 
//// ---------------------------------------------------------------------
//// ---------------------------------------------------------------------
////  GUARDAR ACUSE DE RECIBO PARA DOCUMENTO DE SALIDA EXTERNO
//// ---------------------------------------------------------------------
if($accion==guardarAcuseReciboExt){
 //$connect();
   $sql="select max(CodAcuse) 
           from 
		        cp_documentoacuserecibo 
		  where 
		        CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and 
				Periodo = '".$_POST['periodo']."' and 
				Cod_TipoDocumento = '".$_POST['cod_tdocumento']."'";
	$qry=mysql_query($sql) or die ($sql.mysql_error());
	$field=mysql_fetch_array($qry);   
    
    $cod_acuse=(int) ($field[0]+1); /// CODIGO DEL DOCUMENTO
    $cod_acuse=(string) str_repeat("0",4-strlen($cod_acuse)).$cod_acuse;
	
	 $fecha_recibido=$_POST['fecha_recibido']; $fecha_recibido=date("Y-m-d",strtotime($fecha_recibido)); 
	
    $sql="INSERT INTO cp_documentoacuserecibo(CodOrganismo,
   											Cod_Documento,
											Cod_TipoDocumento,
											CodAcuse,
											Periodo,
											FechaAcuse,
											CodPersona,
											CodDependencia ,
											CodCargo,
											PersonaRecibido,
											CargoPersonaRecibido,
											FechaRecibido,
											HoraRecibido,
											CedulaRecibido,
											LugarRecibido,
											Observaciones,
											UltimoUsuario, 
											UltimaFechaModif) 
									 VALUES ('".$_SESSION['ORGANISMO_ACTUAL']."',
									         '".$_POST['cod_documento']."',
											 '".$_POST['cod_tdocumento']."',
											 '$cod_acuse',
											 '".date("Y")."',
											 '".date("Y-m-d")."',
											 '".$_POST['codpRemitente']."',
											 '".$_POST['codDepRemitente']."',
											 '".$_POST['codCargoRemitente']."',
											 '".$_POST['Nombp_recibido']."',
											 '".$_POST['Cargop_recibido']."',
											 '$fecha_recibido',
											 '".date("H:i:s")."',
											 '".$_POST['nro_cedula']."',
											 '".$_POST['lugar']."',
											 '".$_POST['observaciones']."',
											 '".$_SESSION['USUARIO_ACTUAL']."',
											 '".date("Y-m-d H:i:s")."')";
   $qry=mysql_query($sql) or die ($sql.mysql_error());
   
   //// CAMBIA EL ESTADO DE ENVIADO A RECIBIDO EN TABLA CP_DOCUMENTODISTRIBUCIONEXT ------------
   $sa="update cp_documentodistribucionext set  Estado = 'RE',
												UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												UltimaFechaModif = '".date("Y-m-d H:i:s")."'
										  where 
												CodOrganismo = '".$_SESSION['ORGANISMO_ACTUAL']."' and
												Periodo = '".$_POST['periodo']."' and
												Cod_Documento = '".$_POST['cod_documento']."' and
												Cod_TipoDocumento = '".$_POST['cod_tdocumento']."' and
												Secuencia = '".$_POST['secuencia']."'"; 
  $qa= mysql_query($sa) or die ($sa.mysql_error());
  
  //// CONSULTA PARA SABER SI TODAS LAS DISTRIBUCIONES ESTAN EN ESTADO RECIBIDO ----------------
  $sb="select * from 
                   cp_documentodistribucionext 
			  where 
			       Cod_Documento = '".$_POST['cod_documento']."' and 
				   Periodo = '".$_POST['periodo']."' and 
				   CodOrganismo = '".$_SESSION['ORGANISMO_ACTUAL']."' and
				   Estado = 'EV'";
  $qb=mysql_query($sb) or die ($sb.mysql_error());
  $rb=mysql_num_rows($qb); 
  
  if($rb=='0'){
    //// CAMBIO EL ESTADO AL DOCUMENTO DE ENVIADO A RECIBIDO ----------------------------------
	$sc="update cp_documentoextsalida set Estado = 'RE',
	                					  UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										  UltimaFechaModif = '".date("Y-m-d H:i:s")."'
									where 
									      CodOrganismo = '".$_SESSION['ORGANISMO_ACTUAL']."' and 
										  Cod_Documento = '".$_POST['cod_documento']."' and 
										  Cod_TipoDocumento = '".$_POST['cod_tdocumento']."' and 
										  Periodo = '".$_POST['periodo']."' ";
	$qc=mysql_query($sc) or die ($sc.mysql_error());
  }
  
  //// INSERTO EN HISTORICO ------------------------------------------------------------------
  //// SELECT PARA OBTENER SECUENCIA MAX
	  $smax= "select max(Secuencia) from 
	                                    cp_historicodocumentoextsalida 
								   where 
								        CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and 
										Cod_Documento = '".$_POST['cod_documento']."' and
										Periodo = '".$_POST['periodo']."'";
	  $qmax= mysql_query($smax) or die ($smax.mysql_error());
	  $fmax= mysql_fetch_array($qmax);
	  
	  $valorSecuencia=(int) ($fmax[0]+1);
	  $valorSecuencia=(string) str_repeat("0",4-strlen($valorSecuencia)).$valorSecuencia;
	  
	  //// SELECT PARA OBTENER DATOS HISTORICO
	  $sd="select * from 
	                     cp_historicodocumentoextsalida 
	               where 
				         CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and 
						 Cod_Documento = '".$_POST['cod_documento']."' and
						 Periodo = '".$_POST['periodo']."' and
						 Cod_Historico = '".$_POST['secuencia']."'";
	  $qd = mysql_query($sd) or die ($sd.mysql_error());
	  $rd = mysql_num_rows($qd);
	  $fcon = mysql_fetch_array($qd);
	  
	  //// INSERT PARA INGRESAR DATOS OBTENIDOS DE LA CONSULTA ANTERIOR 
	   $shist="insert into cp_historicodocumentoextsalida (CodOrganismo,
														  Cod_Documento,
														  Cod_TipoDocumento,
														  Cod_Historico,
														  Periodo,
														  Secuencia,
														  CodDependencia,
														  Cod_Dependencia,
														  FechaRegistro,
														  Cod_Organismos,
														  Destinatario,
														  CargoDestinatario,
														  Remitente,
														  Cargo,
														  Asunto,
														  Descripcion,
														  FechaDocumento,
														  Contenido,
														  Cod_PersonaResp,
														  Cod_CargoResp,
														  FechaEnvio,
														  Estado,
														  UltimoUsuario,
														  UltimaFechaModif)
												  values ('".$_SESSION['ORGANISMO_ACTUAL']."',
												  		 '".$_POST['cod_documento']."',
														 '".$fcon['Cod_TipoDocumento']."',
														 '".$_POST['secuencia']."',
														 '".$fcon['Periodo']."',
														 '".$valorSecuencia."',
														 '".$fcon['CodDependenciaInterna']."',
   														 '".$fcon['Cod_DependenciaExterna']."',
														 '".$fcon['FechaRegistro']."',
														 '".$fcon['Cod_Organismos']."',
														 '".$fcon['Destinatario']."',
														 '".$fcon['CargoDestinatario']."',
														 '".$fcon['Remitente']."',
														 '".$fcon['CargoRemitente']."',
														 '".$fcon['Asunto']."',
														 '".$fcon['Descripcion']."',
														 '".$fcon['FechaDocumento']."',
														 '".$fcon['Contenido']."',
														 '".$fcon['Cod_PersonaResp']."',
														 '".$fcon['Cod_CargoResp']."',
														 '".$fcon['FechaEnvio']."',
														 'RE',
														 '".$_SESSION['USUARIO_ACTUAL']."',
														 '".date("Y-m-d H:i:s")."')";
	 $qhist= mysql_query($shist) or die ($shist.mysql_error());
 }
 //// --------------------------------------------------------------------
////  GUARDAR ACUSE DE RECIBO PARA DOCUMENTO DE SALIDA INTERNO
//// ---------------------------------------------------------------------
if($accion==guardarAcuseRecibo){
 //$connect();
   $sql="select 
                max(CodAcuse) 
           from 
		        cp_documentoacuserecibo 
		  where 
		        CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and 
				Cod_TipoDocumento = '".$_POST['cod_tdocumento']."' and  
				Periodo='".$_POST['periodo']."'";  //echo $sql;
	$qry=mysql_query($sql) or die ($sql.mysql_error());
	$field=mysql_fetch_array($qry);   
    
    $cod_acuse=(int) ($field[0]+1); /// CODIGO DEL DOCUMENTO
    $cod_acuse=(string) str_repeat("0",4-strlen($cod_acuse)).$cod_acuse;
	
	$fecha_recibido=$_POST['fecha_recibido']; $fecha_recibido=date("Y-m-d",strtotime($fecha_recibido)); 
	
    $sql="INSERT INTO cp_documentoacuserecibo(CodOrganismo,
   											Cod_Documento,
											Cod_TipoDocumento,
											CodAcuse,
											Periodo,
											FechaAcuse,
											CodPersona,
											CodDependencia ,
											CodCargo,
											PersonaRecibido,
											CargoPersonaRecibido,
											FechaRecibido,
											HoraRecibido,
											Observaciones,
											UltimoUsuario, 
											UltimaFechaModif) 
									 VALUES ('".$_SESSION['ORGANISMO_ACTUAL']."',
									         '".$_POST['cod_documento']."',
											 '".$_POST['cod_tdocumento']."',
											 '$cod_acuse',
											 '".date("Y")."',
											 '".date("Y-m-d")."',
											 '".$_POST['codpRemitente']."',
											 '".$_POST['codDepRemitente']."',
											 '".$_POST['codCargoRemitente']."',
											 '".$_POST['codp_recibido']."',
											 '".$_POST['cod_cargorecibido']."',
											 '$fecha_recibido',
											 '".$_POST['hora_recibido']."',
											 '".$_POST['observaciones']."',
											 '".$_SESSION['USUARIO_ACTUAL']."',
											 '".date("Y-m-d H:i:s")."')";
   $qry=mysql_query($sql) or die ($sql.mysql_error()); //echo $sql;
   
   //// CAMBIA EL ESTADO DE ENVIADO A RECIBIDO EN TABLA CP_DOCUMENTODISTRIBUCION ------------
   $sa="update cp_documentodistribucion set  Estado = 'RE',
											 UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
											 UltimaFechaModif = '".date("Y-m-d H:i:s")."'
									   where 
											 Cod_Organismo = '".$_SESSION['ORGANISMO_ACTUAL']."' and
											 Cod_Documento = '".$_POST['cod_documento']."' and
											 Cod_TipoDocumento = '".$_POST['cod_tdocumento']."' and
											 CodPersona = '".$_POST['codp_recibido']."'"; 
  $qa= mysql_query($sa) or die ($sa.mysql_error());
  
  //// CONSULTA PARA SABER SI TODAS LAS DISTRIBUCIONES ESTAN EN ESTADO RECIBIDO ----------------
  $sb="select * from 
                   cp_documentodistribucion 
			  where 
			       Cod_Documento = '".$_POST['cod_documento']."' and
				   Cod_Organismo = '".$_SESSION['ORGANISMO_ACTUAL']."' and
				   Estado='EV'"; 
  $qb=mysql_query($sb) or die ($sb.mysql_error());
  $rb=mysql_num_rows($qb); //echo $rb;
   //echo "Procedencia = ".$_POST['procedencia'];
  if($rb==0){
    if($_POST['procedencia']=='INT'){
      //// CAMBIO EL ESTADO AL DOCUMENTO A RECIBIDO CASO PROCEDENCIA INTERNA ---------
	  $sc="update cp_documentointerno set  Estado = 'RE',
	                					   UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										   UltimaFechaModif = '".date("Y-m-d H:i:s")."'
									 where 
									      CodOrganismo = '".$_SESSION['ORGANISMO_ACTUAL']."' and 
										  Cod_DocumentoCompleto = '".$_POST['cod_documento']."' and 
										  Cod_TipoDocumento = '".$_POST['cod_tdocumento']."'";
	 $qc=mysql_query($sc) or die ($sc.mysql_error()); //echo $sc;
   }else{
     //// CAMBIO EL ESTADO AL DOCUMENTO A COMPLETADO CASO PROCEDENCIA EXTERNA -------------------
     $sc="update cp_documentoextentrada set Estado = 'CO',
	                					  UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										  UltimaFechaModif = '".date("Y-m-d H:i:s")."'
									where 
									      CodOrganismo = '".$_SESSION['ORGANISMO_ACTUAL']."' and 
										  NumeroRegistroInt = '".$_POST['cod_documento']."' and 
										  Cod_TipoDocumento = '".$_POST['cod_tdocumento']."'";
	 $qc=mysql_query($sc) or die ($sc.mysql_error());
   }
  }
  
}
///// -------------------------------------------------------------------- 
////  GUARDAR CONTENIDO DE SALIDA EXTERNA
///// -------------------------------------------------------------------- 
if($accion == guardarContenido){
   list($d,$m,$a)=SPLIT('[/.-]',$_POST['fecha_documento']);$fecha_documento=$a.'-'.$m.'-'.$d;
   
   $sa="update cp_documentoextsalida set Contenido = '".$_POST['editor1']."',
   										 FechaDocumento = '$fecha_documento',
										 Estado = 'PP',
										 MediaFirma = '".$_POST['iniciales']."',
                                         UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										 UltimaFechaModif = '".date("Y-m-d H:i:s")."'
                                    where
								         Cod_DocumentoCompleto = '".$_POST['ndoc_completo']."'";
  $qa=mysql_query($sa) or die ($sa.mysql_error());
 
  $sb="update cp_documentodistribucionext set Estado = 'PE',
                                               UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										       UltimaFechaModif = '".date("Y-m-d H:i:s")."'
                                          where
											   Cod_Documento = '".$_POST['cod_documento']."' and 
											   Periodo = '".date("Y")."' and
											   CodOrganismo = '".$_SESSION['ORGANISMO_ACTUAL']."'";
  $qb=mysql_query($sb) or die ($sb.mysql_error());
} 
///// -------------------------------------------------------------------- 
////  GUARDAR CONTENIDO DE SALIDA EXTERNA MODIFICACION RESTRINGIDA
///// -------------------------------------------------------------------- 
if($accion == guardarContenidoRestringido){
   list($d,$m,$a)=SPLIT('[/.-]',$_POST['fecha_documento']);$fecha_documento=$a.'-'.$m.'-'.$d;
   
   $sa="update cp_documentoextsalida set Contenido = '".$_POST['editor1']."',
   										 FechaDocumento = '$fecha_documento',
										 MediaFirma = '".$_POST['iniciales']."',
                                         UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										 UltimaFechaModif = '".date("Y-m-d H:i:s")."'
                                    where
								         Cod_DocumentoCompleto = '".$_POST['ndoc_completo']."'";
  $qa=mysql_query($sa) or die ($sa.mysql_error());
 
  $sb="update cp_documentodistribucionext set Estado = 'PE',
                                               UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										       UltimaFechaModif = '".date("Y-m-d H:i:s")."'
                                          where
											   Cod_Documento = '".$_POST['cod_documento']."' and 
											   Periodo = '".date("Y")."' and
											   CodOrganismo = '".$_SESSION['ORGANISMO_ACTUAL']."'";
  $qb=mysql_query($sb) or die ($sb.mysql_error());
} 
///// -------------------------------------------------------------------- 
////  GUARDAR CONTENIDO DOCUMENTO INTERNO
///// -------------------------------------------------------------------- 
if($accion == 'guardarContenidoInterno'){
     /// Actualización de documento /// Actualización detalle del documento
     $sa="update cp_documentointerno set 
	                                     Asunto = '".$_POST['asunto']."',
										 Contenido = '".$_POST['editor1']."',
										 Estado = 'PP',
										 MediaFirma = '".$_POST['iniciales']."',
                                         UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										 UltimaFechaModif = '".date("Y-m-d H:i:s")."'
                                    where
								         Cod_DocumentoCompleto = '".$_POST['n_documento']."' and 
										 CodOrganismo = '".$_POST['CodOrganismo']."' and 
										 Cod_TipoDocumento = '".$_POST['cod_tipodocumento']."'";
    $qa=mysql_query($sa) or die ($sa.mysql_error());
   
    $sb="update cp_documentodistribucion set 
	                                          Estado = 'PE', 
	         								  UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										      UltimaFechaModif = '".date("Y-m-d H:i:s")."'
                                        where
										     Cod_Documento = '".$_POST['n_documento']."' and 
											 Cod_Organismo = '".$_POST['CodOrganismo']."' and 
											 Cod_TipoDocumento = '".$_POST['cod_tipodocumento']."'";
  $qb=mysql_query($sb) or die ($sb.mysql_error());
} 
///// -------------------------------------------------------------------- 
////  GUARDAR DEVOLUCION DE DOCUMENTOS DE SALIDA EXTERNA
///// --------------------------------------------------------------------
if($accion == guardarDevolucionExt){
//// CAMBIA ESTADO A DEVUELTO
list($d,$m,$a)=SPLIT('[/.-]',$_POST['fecha_devolucion']); $fecha_devolucion=$a.'-'.$m.'-'.$d;

$sa="update cp_documentodistribucionext set Estado = 'DV',
                                            MotivoDevolucion = '".$_POST['devolucion']."',
											FechaDevolucion = '$fecha_devolucion', 
										    UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										    UltimaFechaModif = '".date("Y-m-d H:i:s")."'
                                      where
										    Cod_Documento = '".$_POST['cod_documento']."' and 
										    Periodo = '".$_POST['periodo']."' and
										    CodOrganismo = '".$_SESSION['ORGANISMO_ACTUAL']."' and
											Secuencia = '".$_POST['secuencia']."'";
$qa=mysql_query($sa) or die ($sa.mysql_error());

//// INSERTO EN HISTORICO ---------------------------------------------
  //// SELECT PARA OBTENER SECUENCIA MAX
	  $smax= "select max(Secuencia) from 
	                                    cp_historicodocumentoextsalida 
								   where 
								        CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and 
										Cod_Documento = '".$_POST['cod_documento']."' and
										Periodo = '".$_POST['periodo']."' and
										Cod_Historico = '".$_POST['secuencia']."'";
	  $qmax= mysql_query($smax) or die ($smax.mysql_error());
	  $fmax= mysql_fetch_array($qmax);
	  
	  $valorSecuencia=(int) ($fmax[0]+1);
	  $valorSecuencia=(string) str_repeat("0",4-strlen($valorSecuencia)).$valorSecuencia;
	  
	  //// SELECT PARA OBTENER DATOS HISTORICO------------------------------------------------
	  $sd="select * from 
	                     cp_historicodocumentoextsalida 
	               where 
				         CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and 
						 Cod_Documento = '".$_POST['cod_documento']."' and
						 Periodo = '".$_POST['periodo']."' and
						 Cod_Historico = '".$_POST['secuencia']."'";
	  $qd=mysql_query($sd) or die ($sd.mysql_error());
	  $fcon=mysql_fetch_array($qd);
	  
	  //// INSERT PARA INGRESAR DATOS OBTENIDOS DE LA CONSULTA ANTERIOR 
       $shist="insert into cp_historicodocumentoextsalida (CodOrganismo,
														  Cod_Documento,
														  Cod_TipoDocumento,
														  Cod_Historico,
														  Periodo,
														  Secuencia,
														  CodDependencia,
														  Cod_Dependencia,
														  FechaRegistro,
														  Cod_Organismos,
														  Destinatario,
														  CargoDestinatario,
														  Remitente,
														  Cargo,
														  Asunto,
														  Descripcion,
														  FechaDocumento,
														  Contenido,
														  Cod_PersonaResp,
														  Cod_CargoResp,
														  FechaEnvio,
														  Estado,
														  MotivoDevolucion,
														  UltimoUsuario,
														  UltimaFechaModif)
												  values ('".$_SESSION['ORGANISMO_ACTUAL']."',
												  		 '".$_POST['cod_documento']."',
														 '".$fcon['Cod_TipoDocumento']."',
														 '".$_POST['secuencia']."',
														 '".$fcon['Periodo']."',
														 '".$valorSecuencia."',
														 '".$fcon['CodDependenciaInterna']."',
   														 '".$fcon['Cod_DependenciaExterna']."',
														 '".$fcon['FechaRegistro']."',
														 '".$fcon['Cod_Organismos']."',
														 '".$fcon['Destinatario']."',
														 '".$fcon['CargoDestinatario']."',
														 '".$fcon['Remitente']."',
														 '".$fcon['CargoRemitente']."',
														 '".$fcon['Asunto']."',
														 '".$fcon['Descripcion']."',
														 '".$fcon['FechaDocumento']."',
														 '".$fcon['Contenido']."',
														 '".$fcon['Cod_PersonaResp']."',
														 '".$fcon['Cod_CargoResp']."',
														 '".$fcon['FechaEnvio']."',
														 'DV',
														 '".$_POST['devolucion']."',
														 '".$_SESSION['USUARIO_ACTUAL']."',
														 '".date("Y-m-d H:i:s")."')";
	 $qhist= mysql_query($shist) or die ($shist.mysql_error());
}
///// --------------------------------------------------------------------
//// GUARDAR MODIFICACION RESTRINGIDA DE CONTENIDO
///// --------------------------------------------------------------------
if($accion==guardarContenidoInternoRestringido){

  $sa= "update cp_documentointerno set 
                                      Asunto = '".$_POST['asunto']."',          
                                      Contenido = '".$_POST['editor1']."', 
                                      UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
									  UltimaFechaModif =  '".date("Y-m-d H:i:s")."',
									  MediaFirma = '".$_POST['iniciales']."'
								 where 
								      Cod_DocumentoCompleto = '".$_POST['n_documento']."' and 
									  Cod_TipoDocumento = '".$_POST['cod_tipodocumento']."'";
  $qa = mysql_query($sa) or die ($sa.mysql_error());
  
}
?>