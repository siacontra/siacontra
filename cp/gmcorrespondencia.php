<?php 
include ("fphp.php");
$fechaCompleta=date("Y-m-d H:i:s");
$year=date("Y");
//// ___________________________________________________
////      INSERTAR DESTINATARIOS
if ($accion == "insertarDestinatarioEmp") {
	connect();
	
	if ($ventana == "insertarDestinatarioEmp") $ddesc = "disabled"; else $ddesc = "";
	
	$detalle = split(";", $detalles);
	foreach ($detalle as $registro) {
		if ($codigo == $registro) die("¡No se puede insertar dos veces el mismo $tabla!");
	}
	
	//	si no se encontraron errores inserta en la tabla los datos
	echo "||";
	
        //// Consulta para obtener el maximo secuencia que posee el empleado al momento	
	    $sa="select max(Secuencia) from rh_empleadonivelacion where CodPersona='".$codigo."'";
		$qa=mysql_query($sa) or die ($sa.mysql_error());
		$fa=mysql_fetch_array($qa);
		
		//// Consulta para obtener el cargo
		$sb="select * from rh_empleadonivelacion where CodPersona='".$codigo."' and Secuencia='".$fa['0']."' ";
		$qb=mysql_query($sb) or die ($sb.mysql_error());
		$fb=mysql_fetch_array($qb);
		
		//// Consulta para obtener el resto de la información	
		$sql = "SELECT 
		               mp.NomCompleto as NomCompleto,
					   rp.DescripCargo as DescripCargo,
					   md.Dependencia as  nombre_dependencia,
					   md.CodDependencia as CodDependencia,
					   rp.CodCargo as CodCargo
		          FROM 
				      mastdependencias md,
					  rh_puestos rp,
					  mastpersonas mp
					  inner join mastempleado me on (me.CodPersona = mp.CodPersona)
				WHERE 
				      mp.CodPersona = '".$codigo."' and rp.CodCargo = '".$fb['CodCargo']."' and md.CodDependencia = '".$fb['CodDependencia']."'"; //echo $sql;
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);

	?>
		<td align="center" width="20">
        	<input type="hidden" name="codpersona" value="<?=$codigo?>" />
            <input type="hidden" name="cod_dependencia" value="<?=$field['CodDependencia']?>" />
			<?=utf8_encode($field['nombre_dependencia'])?>
		</td>
        <td align="left" width="70"><?=utf8_encode($field['NomCompleto']);?></td>
        <td align="left" width="70">
        	<input type="hidden" name="cargo" value="<?=$field['CodCargo']?>" />
			<?=utf8_encode($field['DescripCargo']);?>
        </td>
        <td align="left" width="70">
			<input type="checkbox" name="cc" id="cc" value="N" onClick="if(this.value=='N') this.value='S'; else if(this.value=='S') this.value='N'; "> 
        	
        </td>
	<?
}else{
  if ($accion == "insertarDestinatarioDep") {
	connect();
	
	if ($ventana == "insertarDestinatarioDep") $ddesc = "disabled"; else $ddesc = "";
	
	$detalle = split(";", $detalles);
	foreach ($detalle as $registro) {
		if ($codigo == $registro) die("¡No se puede insertar dos veces el mismo $tabla!");
	}
	
	//	si no se encontraron errores inserta en la tabla los datos
	echo "||";
		$sd = "select * from mastdependencias where CodDependencia = '".$codigo."'";
		$qd = mysql_query($sd) or die ($sd.mysql_error());
		$fd = mysql_fetch_array($qd); 
		/// Consulta para obenter la maxima secuencia en la tabla que tenga el empleado $codigo
		$sa = "select max(secuencia) from rh_empleadonivelacion where CodPersona = '".$fd['CodPersona']."'";
		$qa = mysql_query($sa) or die ($sa.mysql_error());
		$fa = mysql_fetch_array($qa);
		
		/// Consulto para obtener los datos segun la secuencia obtenida anteriormente$codigo
		$sb = "select * from rh_empleadonivelacion where CodPersona = '".$fd['CodPersona']."' and Secuencia = '".$fa['0']."'";
		$qb = mysql_query($sb) or die ($sb.mysql_error());
		$fb = mysql_fetch_array($qb);
				
		/// Consulta para obtener mas datos relacionados al empleado $codigo and md.CodDependencia = '".$fb['CodDependencia']."'
		$sql = "SELECT 
		               mp.NomCompleto as NomCompleto,
					   rp.DescripCargo as DescripCargo,
					   md.Dependencia as  nombre_dependencia,
					   md.CodDependencia as CodDependencia,
					   rp.CodCargo as CodCargo
		          FROM 
				      rh_puestos rp,
					  mastdependencias md,
				      mastpersonas mp
					  inner join mastempleado me on (me.CodPersona = mp.CodPersona)
				WHERE 
				      mp.CodPersona = '".$fd['CodPersona']."' and rp.CodCargo = '".$fb['CodCargo']."' ";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);

	?>
		<td align="center" width="20">
        	<input type="hidden" name="codpersona" value="<?=$fd['CodPersona']?>" />
            <input type="hidden" name="cod_dependencia" value="<?=$fd['CodDependencia']?>" />
			<?=utf8_encode($fd['Dependencia'])?>
		</td>
        <td align="left" width="70">
			<?=utf8_encode($field['NomCompleto']);?>
        </td>
        <td align="left" width="70">
        	<input type="hidden" name="cargo" value="<?=$field['CodCargo']?>" />
			<?=utf8_encode($field['DescripCargo']);?>
        </td>
        <td align="left" width="70">
			<input type="checkbox" name="cc" id="cc" value="N" onClick="if(this.value=='N') this.value='S'; else if(this.value=='S') this.value='N'; "> 
        	
        </td>
	<?
}
}
////   ____________________________________________________________
////   INSERTAR DESTINATARIO PARA SALIDA DE DOCUMENTOS EXTERNOS
////   ____________________________________________________________
if ($accion == "insertarDestinatarioDepExt") {
	connect();
	
	if ($ventana == "insertarDestinatarioDepExt") $ddesc = "disabled"; else $ddesc = "";
	
	$detalle = split(";", $detalles);
	foreach ($detalle as $registro) {
		if ($cod2 == $registro) die("¡No se puede insertar dos veces el mismo $tabla!");
	}
	
	//	si no se encontraron errores inserta en la tabla los datos
	echo "||";
		$sql = "SELECT * FROM pf_dependenciasexternas WHERE CodDependencia='".$cod2."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);

	?>
		<td width="250">
           <input type="hidden" name="codigo_organismo" value="<?=$field['CodOrganismo']?>" />
           <input type="hidden" name="codigo_dependencia" value="<?=$cod2?>" />
		   <input type="text" name="ente" id="ente" size="70" value="<?=htmlentities($field['Dependencia'])?>" readonly/>
           <input type="hidden" name="EsParticular"  value="N"/>
        </td>
        <td>
           <input type="text" name="representante" id="representante" size="70" value="<?=htmlentities($field['Representante'])?>"/>
        </td>
        <td align="left" width="70">
            <input type="text" name="cargorepresentante"  size="60" id="cargorepresentante" value="<?=htmlentities($field['Cargo'])?>"/>
        	<input type="hidden" name="cargo" value="<?=$field['CodCargo']?>"/>
        </td>
        <td align="left" width="70">
			<input type="checkbox" name="atencion" id="atencion" value="N" onClick="if(this.value=='N') this.value='S'; else if(this.value=='S') this.value='N'; "> 
        	
        	
        </td>
	<?
}else{
 if ($accion == "insertarDestinatarioOrgExt") {
	connect();
	
	if ($ventana == "insertarDestinatarioOrgExt") $ddesc = "disabled"; else $ddesc = "";
	
	$detalle = split(";", $detalles);
	foreach ($detalle as $registro) {
		if ($codigo == $registro) die("¡No se puede insertar dos veces el mismo $tabla!");
	}
	
	//	si no se encontraron errores inserta en la tabla los datos
	echo "||";
		$sql = "SELECT * FROM pf_organismosexternos WHERE CodOrganismo='".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);

	?>
		<td align="center" width="20">
            <input type="text" name="ente" id="ente" size="70" value="<?=htmlentities($field['Organismo'])?>" readonly/>
        	<input type="hidden" name="codigo_organismo" value="<?=$codigo?>" />
            <input type="hidden" name="codigo_dependencia" value="" />
            <input type="hidden" name="EsParticular" value="N"/>
		</td>
        <td align="left" width="70">
            <input type="text" name="representante" id="representante" size="70" value="<?=htmlentities($field['RepresentLegal'])?>"/>
        </td>
        <td align="left" width="70">
            <input type="text" name="cargorepresentante"  size="60" id="cargorepresentante" value="<?=htmlentities($field['Cargo'])?>"/>
        	<input type="hidden" name="cargo" id="cargo" value="<?=htmlentities($field['Cargo'])?>"/>
        </td>
        <td align="left" width="70">
			<input type="checkbox" name="atencion" id="atencion" value="N" onClick="if(this.value=='N') this.value='S'; else if(this.value=='S') this.value='N'; "> 
        	
        	
        </td>
	<?
  }else{
    ////  -------------------------------------------------------------------------------
    ////  INSERTAR DESTINATARIO PARA SALIDA DE DOCUMENTOS EXTERNOS "PARTICULAR"
    ////  -------------------------------------------------------------------------------
    if($accion==insertarDestinatarioParticularExt){
      connect();
	
	if ($ventana == "insertarDestinatarioParticularExt") $ddesc = "disabled"; else $ddesc = "";
	
	$detalle = split(";", $detalles);
	foreach ($detalle as $registro) {
		if ($codigo == $registro) die("¡No se puede insertar dos veces el mismo $tabla!");
	}
	
	//	si no se encontraron errores inserta en la tabla los datos
	echo "||";
		$sql = "SELECT * FROM cp_particular WHERE CodParticular='".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);

	?>
		<td align="center" width="20">
            <input type="text" name="ente" id="ente" size="70" value="PARTICULAR" readonly/>
        	<input type="hidden" name="codigo_organismo" value="<?=$codigo?>" />
            <input type="hidden" name="codigo_dependencia" value="" />
            <input type="hidden" name="EsParticular" value="S"/>
		</td>
        <td align="left" width="70">
            <input type="text" name="representante" id="representante" size="70" value="<?=($field['Nombre'])?>"/>
        </td>
        <td align="left" width="70">
            <input type="text" name="cargorepresentante"  size="60" id="cargorepresentante" value="<?=($field['Cargo'])?>"/>
        	<input type="hidden" name="cargo" id="cargo" value="<?=($field['Cargo'])?>"/>
        </td>
        <td align="left" width="70">
			<input type="checkbox" name="atencion" id="atencion" value="N" onClick="if(this.value=='N') this.value='S'; else if(this.value=='S') this.value='N'; "> 
        	 
        	
        </td>
	<?
	
	}
  }
}
//// -------------------------------------------------------------------------------------
////  GUARDAR NUEVO DOCUMENTO DE ENTRADA EXTERNA
//// -------------------------------------------------------------------------------------
if($accion==guardarDestinatarios){
connect();
   if($_POST['organismo'] != '') $org = $_POST['organismo'];
   else $org = $_POST['codparticular'];
   
	$sql = "UPDATE cp_documentoextentrada
			SET
				FlagInformeEscrito = '".$infor_escrito."',
				FlagHablarConmigo = '".$hablar_alrespecto."',
				FlagCoordinarcon = '".$coord_con."',
				CoordinarCon = '".$coord_con2."',
				FlagPrepararMemo = '".$pre_memo."',
				PrepararMemo = '".$pre_memo2."',
				FlagInvestigarInformar = '".$inv_inforver."',
				FlagTramitarConclusion = '".$tram_conclusion."',
				FlagDistribuir = '".$distribuir."',
				FlagConocimiento = '".$pconocimiento_fp."',
				FlagPrepararConstentacion = '".$pre_contfirm."',
				FlagArchivar = '".$archivar."',
				FlagRegistrode = '".$registro_de."',
				RegistroDe = '".$registro_de2."',
				FlagPrepararOficio = '".$prep_oficio."',
				PrepararOficio = '".$prep_oficio2."',
				FlagConocerOpinion = '".$conocer_opinion."',
				FlagTramitarloCaso = '".$tram_casoproceden."',
				FlagAcusarRecibo = '".$acusar_recibo."',
				FlagTramitarEn = '".$tram_dias."',
				TramitarEn = '".$tram_dias2."',
				Estado='RE',
				UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
				UltimaFechaModif='".date("Y-m-d H:i:s")."'
			WHERE
				Cod_Organismos = '$org' AND
				NumeroDocumentoExt = '".$n_documento."' AND
				Cod_TipoDocumento = '".$t_documento."' AND
				NumeroRegistroInt = '".$cod_documento."'";
	$qry=mysql_query($sql) or die ($sql.mysql_error());
	
 	 
	$lineas = split(";", $detalles);
	foreach ($lineas as $linea) {
		list($codpersona, $cargo)=SPLIT( '[|]', $linea);
		/// CALCULAR SECUENCIA *********************************
		$sql = "INSERT INTO cp_documentodistribucion(
							Cod_Organismo,
							CodDependencia,
							CodPersona,
							Cod_Documento,
							Cod_TipoDocumento,
							CodCargo,
							CC,
							FechaDistribucion,
							FechaEnvio,
							Estado,
							UltimoUsuario,
							UltimaFechaModif,
							Periodo,
							PlazoAtencion,
							Procedencia)
					 VALUES (
							'".$_SESSION['ORGANISMO_ACTUAL']."',
							'".$depenOrigen."',
							'".$codpersona."',
							'".$reg_interno."',
							'".$t_documento."',
							'".$cargo."',
							'".$cc."',
							'".date("Y-m-d")."',
							'".date("Y-m-d")."',
							'EV',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							'".date("Y-m-d H:i:s")."',
							'".date("Y")."',
							'".$tram_dias2."',
							'EXT')";
		$qrydocent=mysql_query($sql) or die ($sql.mysql_error());

 }
}
////  ------------------------------------------------------------------------------------
////   GUARDAR DATOS CORRESPONDENCIA DE SALIDA EXT
////  ------------------------------------------------------------------------------------
if($accion==guardarSalidaNueva){
connect();
    $scon= "select max(Cod_Documento) from 
	                                     cp_documentoextsalida 
								    where 
									     CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and
										 Cod_TipoDocumento='".$_POST['t_documento']."' and
										 Periodo = '".date("Y")."'";
	$qcon= mysql_query($scon) or die ($scon.mysql_error());
	$fcon=mysql_fetch_array($qcon);
	$cod_documento=(int) ($fcon[0]+1); /// CODIGO DEL DOCUMENTO
    $cod_documento=(string) str_repeat("0",4-strlen($cod_documento)).$cod_documento;
	$cod_int = $codigo_interno;
	//$codigoCompleto = CEDA."-".$cod_int."-".$cod_documento."-".$year; echo $codigoCompleto;
	$codigoCompleto = $cod_int."-".$cod_documento."-".$year; //echo $codigoCompleto;
	$sql = "INSERT cp_documentoextsalida( CodOrganismo,
										  Cod_Documento,
										  Cod_TipoDocumento,
										  Cod_DocumentoCompleto,
										  Cod_Dependencia,
										  CodInterno,
										  FechaRegistro,
										  Remitente,
										  Cargo,
										  Asunto,
										  Descripcion,
										  Periodo,
										  PlazoAtencion,
										  Estado,
										  UltimoUsuario,
										  UltimaFechaModif,
										  FechaDocumento)
								    VALUES('".$_SESSION['ORGANISMO_ACTUAL']."',								         
										  '$cod_documento',
										  '".$t_documento."',
										  '$codigoCompleto',
										  '".$dep_interna."',
										  '".$codigo_interno."',
										  '".date("Y-m-d")."',
										  '".$codigo_persona."',
										  '".$codigo_cargo."',
										  '".$asunto."',
										  '".$descrip."',
										  '".date("Y")."',
										  '".$plazo."',
										  'PR',
										  '".$_SESSION['USUARIO_ACTUAL']."',
										  '".date("Y-m-d H:i:s")."',
										  '".date("Y-m-d")."')";
	$qry=mysql_query($sql) or die ($sql.mysql_error());
 	 
	$lineas = split(";", $detalles);
	foreach ($lineas as $linea) { 
		list($codigo_organismo, $codigo_dependencia, $EsParticular, $representante, $cargorepresentante, $atencion)=SPLIT( '[|]', $linea);
		
		$scon= "select max(Secuencia) from 
	                                     cp_documentodistribucionext 
								    where 
									     CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and
										 Cod_TipoDocumento='".$_POST['t_documento']."' and
										 Cod_Documento = '$cod_documento'";
	   $qcon= mysql_query($scon) or die ($scon.mysql_error());
	   $fcon=mysql_fetch_array($qcon);
	   $secuencia=(int) ($fcon[0]+1); /// CODIGO DEL DOCUMENTO
       $secuencia=(string) str_repeat("0",4-strlen($secuencia)).$secuencia;
	   
	   if($EsParticular=='S'){$valorPart = 'S';}else{$valorPart = 'N'; }
		
		$sql = "INSERT INTO cp_documentodistribucionext ( CodOrganismo ,
														Cod_Documento,
														Cod_TipoDocumento,
														Periodo,
														Secuencia,
														Cod_Organismos,
														Cod_Dependencia,
														Representante,
														Cargo,
														Atencion,
														FechaDistribucion,
														PlazoAtencion,
														Estado,
														FlagEsParticular,
														UltimoUsuario,
														UltimaFechaModif)
												VALUES ('".$_SESSION['ORGANISMO_ACTUAL']."',
												        '$cod_documento',
														'".$t_documento."',
														'".date("Y-m-d")."',
														'$secuencia',
														'".$codigo_organismo."',
														'".$codigo_dependencia."',
														'".$representante."',
														'".$cargorepresentante."',
														'".$atencion."',
														'".date("Y-m-d")."',
														'".$plazo."',
												        'PR',
														'$valorPart',
														'".$_SESSION["USUARIO_ACTUAL"]."',
														'".date("Y-m-d H:i:s")."')";
		$qry=mysql_query($sql) or die ($sql.mysql_error());
   }	
}
////  ------------------------------------------------------------------------------------
////  PROCEDIMIENTO PARA GUARDAR NUEVO DOCUMENTO INTERNO
////  ------------------------------------------------------------------------------------ 
if($accion==guardarDocumentoInterno){
connect();
    ////  Consulta para verificar el Periodo
	$s_periodo = "select 
	                     max(Periodo) 
					from 
					     cp_documentointerno 
				   where 
				         CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and 
						 Cod_Dependencia='".$_POST['dep_interna']."' and 
						 Cod_TipoDocumento='".$_POST['t_documento']."'";
	$q_periodo = mysql_query($s_periodo) or die ($s_periodo.mysql_error());
	$f_periodo = mysql_fetch_array($q_periodo);
	
	//// en caso de Periodo ser igual al consultado
	if($f_periodo[0]==date("Y")){
	
	   $scon= "select 
			   max(Cod_Documento) 
		from 
			   cp_documentointerno 
		where 
			   CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and
			   Cod_TipoDocumento='".$_POST['t_documento']."' and 
			   Cod_Dependencia='".$_POST['dep_interna']."' and
			   Periodo = '".date("Y")."'";
	   $qcon= mysql_query($scon) or die ($scon.mysql_error());
	   $fcon=mysql_fetch_array($qcon);
	   
	   $cod_documento=(int) ($fcon[0]+1); /// CODIGO DEL DOCUMENTO
       $cod_documento=(string) str_repeat("0",4-strlen($cod_documento)).$cod_documento;
	   $cod_int = $codigo_interno;
	   $codigoCompleto = $cod_int."-".$cod_documento."-".$year; //echo $codigoCompleto;
	   
	   /// PREGUNTO SI ANEXSI1 VIENE VACIO O NO
	   if($_POST['anexsi1']=='S'){
	     $sql = "INSERT cp_documentointerno( CodOrganismo,
										  Cod_Documento,
										  Cod_TipoDocumento ,
										  Cod_DocumentoCompleto,
										  Cod_Dependencia,
										  CodInterno,
										  FechaRegistro,
										  Cod_Remitente,
										  Cod_CargoRemitente,
										  Asunto,
										  Descripcion,
										  Periodo,
										  PlazoAtencion,
										  Estado,
										  UltimoUsuario,
										  UltimaFechaModif,
										  FlagsAnexo,
										  FechaDocumento,
										  DescripcionAnexo)
								    VALUES('".$_SESSION['ORGANISMO_ACTUAL']."',								         
										  '$cod_documento',
										  '".$t_documento."',
										  '$codigoCompleto',
										  '".$dep_interna."',
										  '".$codigo_interno."',
										  '".date("Y-m-d")."',
										  '".$codigo_persona."',
										  '".$codigo_cargo."',
										  '".$asunto."',
										  '".$descrip."',
										  '".date("Y")."',
										  '".$plazo."',
										  'PR',
										  '".$_SESSION['USUARIO_ACTUAL']."',
										  '".date("Y-m-d H:i:s")."',
										  '".$_POST['anexsi1']."',
										  '".date("Y-m-d")."',
										  '".$_POST['anexDescp']."')";
	   $qry=mysql_query($sql) or die ($sql.mysql_error());
	   }else{
		 /// -----------------------------------------------
		 if($_POST['anexsi2']=='N'){  
		   $sql = "INSERT cp_documentointerno( CodOrganismo,
										  Cod_Documento ,
										  Cod_TipoDocumento ,
										  Cod_DocumentoCompleto,
										  Cod_Dependencia,
										  CodInterno,
										  FechaRegistro,
										  Cod_Remitente,
										  Cod_CargoRemitente,
										  Asunto,
										  Descripcion,
										  Periodo,
										  PlazoAtencion,
										  Estado,
										  UltimoUsuario,
										  UltimaFechaModif,
										  FlagsAnexo,
										  FechaDocumento,
										  DescripcionAnexo)
								    VALUES('".$_SESSION['ORGANISMO_ACTUAL']."',								         
										  '$cod_documento',
										  '".$t_documento."',
										  '$codigoCompleto',
										  '".$dep_interna."',
										  '".$codigo_interno."',
										  '".date("Y-m-d")."',
										  '".$codigo_persona."',
										  '".$codigo_cargo."',
										  '".$asunto."',
										  '".$descrip."',
										  '".date("Y")."',
										  '".$plazo."',
										  'PR',
										  '".$_SESSION['USUARIO_ACTUAL']."',
										  '".date("Y-m-d H:i:s")."',
										  '".$_POST['anexsi2']."',
										  '".date("Y-m-d")."',
										  '".$_POST['anexDescp']."')";
	    $qry=mysql_query($sql) or die ($sql.mysql_error());
		 }else{
		   $sql = "INSERT cp_documentointerno( CodOrganismo,
										  Cod_Documento ,
										  Cod_TipoDocumento ,
										  Cod_DocumentoCompleto,
										  Cod_Dependencia,
										  CodInterno,
										  FechaRegistro,
										  Cod_Remitente,
										  Cod_CargoRemitente,
										  Asunto,
										  Descripcion,
										  Periodo,
										  PlazoAtencion,
										  Estado,
										  UltimoUsuario,
										  UltimaFechaModif,
										  FechaDocumento)
								    VALUES('".$_SESSION['ORGANISMO_ACTUAL']."',								         
										  '$cod_documento',
										  '".$t_documento."',
										  '$codigoCompleto',
										  '".$dep_interna."',
										  '".$codigo_interno."',
										  '".date("Y-m-d")."',
										  '".$codigo_persona."',
										  '".$codigo_cargo."',
										  '".$asunto."',
										  '".$descrip."',
										  '".date("Y")."',
										  '".$plazo."',
										  'PR',
										  '".$_SESSION['USUARIO_ACTUAL']."',
										  '".date("Y-m-d H:i:s")."',
										  '".date("Y-m-d")."')";
	      $qry=mysql_query($sql) or die ($sql.mysql_error());
		}
	   }
		
	$lineas = split(";", $detalles);
	foreach ($lineas as $linea) {
		list($codpersona, $cod_dependencia, $cargo, $cc)=SPLIT( '[|]', $linea);
				
		$sql = "INSERT INTO cp_documentodistribucion (
							Cod_Organismo,
							CodDependencia,
							CodPersona,
							Cod_Documento,
							Cod_TipoDocumento,
							CodCargo,
							CC,
							FechaDistribucion,
							Estado,
							UltimoUsuario,
							UltimaFechaModif,
							Periodo,
							PlazoAtencion,
							Procedencia)
					 VALUES ('".$_SESSION['ORGANISMO_ACTUAL']."',
							'".$cod_dependencia."',
							'".$codpersona."',
							'$codigoCompleto',
							'".$t_documento."',
							'".$cargo."',
							'".$cc."',
							'".date("Y-m-d")."',
							'PR',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							'".date("Y-m-d H:i:s")."',
							'".date("Y")."',
							'".$plazo."',
							'INT')";
		$qrydocent=mysql_query($sql) or die ($sql.mysql_error());
	}
   }
   
   /// en caso de Periodo ser distinto al consultado
   if($f_periodo[0]!=date("Y")){
	   
	    $scon= "select 
			          max(Cod_Documento) 
		         from 
			          cp_documentointerno 
		         where 
					   CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and
					   Cod_TipoDocumento='".$_POST['t_documento']."' and 
					   Cod_Dependencia='".$_POST['dep_interna']."' and
					   Periodo = '".date("Y")."'";
	   $qcon= mysql_query($scon) or die ($scon.mysql_error());
	   $fcon=mysql_fetch_array($qcon);
	   $cod_documento=(int) ($fcon[0]+1); /// CODIGO DEL DOCUMENTO
       $cod_documento=(string) str_repeat("0",4-strlen($cod_documento)).$cod_documento;
	   $cod_int = $codigo_interno;
	   $codigoCompleto = $cod_int."-".$cod_documento."-".$year; //echo $codigoCompleto;
	
	   if($_POST['anexsi1']=='S'){
	     $sql = "INSERT cp_documentointerno( CodOrganismo,
										  Cod_Documento ,
										  Cod_TipoDocumento ,
										  Cod_DocumentoCompleto,
										  Cod_Dependencia,
										  CodInterno,
										  FechaRegistro,
										  Cod_Remitente,
										  Cod_CargoRemitente,
										  Asunto,
										  Descripcion,
										  Periodo,
										  PlazoAtencion,
										  Estado,
										  UltimoUsuario,
										  UltimaFechaModif,
										  FlagsAnexo,
										  FechaDocumento,
										  DescripcionAnexo)
								    VALUES('".$_SESSION['ORGANISMO_ACTUAL']."',								         
										  '$cod_documento',
										  '".$t_documento."',
										  '$codigoCompleto',
										  '".$dep_interna."',
										  '".$codigo_interno."',
										  '".date("Y-m-d")."',
										  '".$codigo_persona."',
										  '".$codigo_cargo."',
										  '".$asunto."',
										  '".$descrip."',
										  '".date("Y")."',
										  '".$plazo."',
										  'PR',
										  '".$_SESSION['USUARIO_ACTUAL']."',
										  '".date("Y-m-d H:i:s")."',
										  '".$_POST['anexsi1']."',
										  '".date("Y-m-d")."',
										  '".$_POST['anexDescp']."')";
	     $qry=mysql_query($sql) or die ($sql.mysql_error());
	   }else{
		 /// ------------------------------------------------
		 if($_POST['anexsi2']=='S'){  
	       $sql = "INSERT cp_documentointerno( CodOrganismo,
										  Cod_Documento ,
										  Cod_TipoDocumento ,
										  Cod_DocumentoCompleto,
										  Cod_Dependencia,
										  CodInterno,
										  FechaRegistro,
										  Cod_Remitente,
										  Cod_CargoRemitente,
										  Asunto,
										  Descripcion,
										  Periodo,
										  PlazoAtencion,
										  Estado,
										  UltimoUsuario,
										  UltimaFechaModif,
										  FlagsAnexo,
										  FechaDocumento,
										  DescripcionAnexo)
								    VALUES('".$_SESSION['ORGANISMO_ACTUAL']."',								         
										  '$cod_documento',
										  '".$t_documento."',
										  '$codigoCompleto',
										  '".$dep_interna."',
										  '".$codigo_interno."',
										  '".date("Y-m-d")."',
										  '".$codigo_persona."',
										  '".$codigo_cargo."',
										  '".$asunto."',
										  '".$descrip."',
										  '".date("Y")."',
										  '".$plazo."',
										  'PR',
										  '".$_SESSION['USUARIO_ACTUAL']."',
										  '".date("Y-m-d H:i:s")."',
										  '".$_POST['anexsi2']."',
										  '".date("Y-m-d")."',
										  '".$_POST['anexDescp']."')";
	       $qry=mysql_query($sql) or die ($sql.mysql_error());
		 }else{
		   $sql = "INSERT cp_documentointerno( CodOrganismo,
										  Cod_Documento,
										  Cod_TipoDocumento,
										  Cod_DocumentoCompleto,
										  Cod_Dependencia,
										  CodInterno,
										  FechaRegistro,
										  Cod_Remitente,
										  Cod_CargoRemitente,
										  Asunto,
										  Descripcion,
										  Periodo,
										  PlazoAtencion,
										  Estado,
										  UltimoUsuario,
										  UltimaFechaModif,
										  FechaDocumento)
								    VALUES('".$_SESSION['ORGANISMO_ACTUAL']."',								         
										  '$cod_documento',
										  '".$t_documento."',
										  '$codigoCompleto',
										  '".$dep_interna."',
										  '".$codigo_interno."',
										  '".date("Y-m-d")."',
										  '".$codigo_persona."',
										  '".$codigo_cargo."',
										  '".$asunto."',
										  '".$descrip."',
										  '".date("Y")."',
										  '".$plazo."',
										  'PR',
										  '".$_SESSION['USUARIO_ACTUAL']."',
										  '".date("Y-m-d H:i:s")."',
										  '".date("Y-m-d")."')";
	       $qry=mysql_query($sql) or die ($sql.mysql_error());
		 }
	   }
 	 
	$lineas = split(";", $detalles);
	foreach ($lineas as $linea) {
		list($codpersona, $cod_dependencia, $cargo , $cc)=SPLIT( '[|]', $linea);
				
		$sql = "INSERT INTO cp_documentodistribucion (
							Cod_Organismo,
							CodDependencia,
							CodPersona,
							Cod_Documento,
							Cod_TipoDocumento,
							CodCargo,
							CC,
							FechaDistribucion,
							Estado,
							UltimoUsuario,
							UltimaFechaModif,
							Periodo,
							PlazoAtencion,
							Procedencia)
					 VALUES ('".$_SESSION['ORGANISMO_ACTUAL']."',
							'".$cod_dependencia."',
							'".$codpersona."',
							'$codigoCompleto',
							'".$t_documento."',
							'".$cargo."',
							'".$cc."',
							'".date("Y-m-d")."',
							'PR',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							'".date("Y-m-d H:i:s")."',
							'".date("Y")."',
							'".$plazo."',
							'INT')";
		$qrydocent=mysql_query($sql) or die ($sql.mysql_error());
	}
   }
}
////  -----------------------------------------------------------------------------------
////  PROCEDIMIENTO PARA EDITAR DOCUMENTO INTERNO
////  ----------------------------------------------------------------------------------- 
if($accion==guardarDocumentoInternoEditar){
connect();

/// Actualizo los campos en la tabla cp_documento internos que resulten ser modificados   
$sa="update cp_documentointerno set 
									Asunto = '".$_POST['asunto']."',
									Descripcion = '".$_POST['descrip']."',
									PlazoAtencion = '".$_POST['plazo']."',
									DescripcionAnexo = '".$_POST['anexDescp']."',
									FlagsAnexo = '".$_POST['Anexos']."'
                              where 
							        CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and
								    Periodo = '".date("Y")."' and 
									Cod_DocumentoCompleto ='".$_POST['n_documento']."' and 
									Cod_TipoDocumento = '".$_POST['t_documento']."' ";
$qa=mysql_query($sa) or die ($sa.mysql_error());

if($_POST['Estado']=='PP') $estado = 'PE'; elseif($_POST['Estado']=='PR') $estado = 'PR';
//// Verifico  y actualizo cambios en cp_distribucion, se eliminan o añaden destinatarios según
//// se requiera.
$c="delete from cp_documentodistribucion where Cod_Documento = '".$_POST['n_documento']."'";
$qc=mysql_query($c) or die ($c.mysql_error());

$lineas = split(";", $detalles);
	foreach ($lineas as $linea) {
		list($codpersona, $cod_dependencia, $cargo, $cc)=SPLIT( '[|]', $linea);
		if(($codpersona!='') and ($cod_dependencia!='') and ($cargo!='')){		
		$sql = "INSERT INTO cp_documentodistribucion (
							Cod_Organismo,
							CodDependencia,
							CodPersona,
							Cod_Documento,
							Cod_TipoDocumento,
							CodCargo,
							CC,
							FechaDistribucion,
							UltimoUsuario,
							UltimaFechaModif,
							Periodo,
							PlazoAtencion,
							Procedencia,
							Estado)
					 VALUES ('".$_SESSION['ORGANISMO_ACTUAL']."',
							'".$cod_dependencia."',
							'".$codpersona."',
							'".$_POST['n_documento']."',
							'".$t_documento."',
							'".$cargo."',
							'".$cc."',
							'".date("Y-m-d")."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							'".date("Y-m-d H:i:s")."',
							'".date("Y")."',
							'".$plazo."',
							'INT',
							'$estado')";
	$qrydocent=mysql_query($sql) or die ($sql.mysql_error());
		}
   }
}
////  -----------------------------------------------------------------------------------
////  PROCEDIMIENTO PARA EDITAR DOCUMENTO EXTERNO DE SALIDA
////  ----------------------------------------------------------------------------------- 
if($accion==guardarSalidaExtEditar){
connect();

/// Actualizo los campos en la tabla cp_documentoextsalida que resulten ser modificados    
$sa="update cp_documentoextsalida set Cod_tipoDocumento = '".$_POST['t_documento']."',
									Asunto = '".$_POST['asunto']."',
									Descripcion = '".$_POST['descrip']."',
									PlazoAtencion = '".$_POST['plazo']."'
                              where 
							        CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and
								    Periodo = '".date("Y")."' and 
									Cod_DocumentoCompleto ='".$_POST['n_documento']."' ";
$qa=mysql_query($sa) or die ($sa.mysql_error());

/// Verifico  y actualizo cambios en cp_distribucion, se eliminan o añaden destinatarios según
/// se requiera.
$c="delete from cp_documentodistribucionext where Cod_Documento = '".$_POST['cod_documento']."'";
$qc=mysql_query($c) or die ($c.mysql_error());

// Inserto las destinatario editados y agregados
$lineas = split(";", $detalles);
	foreach ($lineas as $linea) {
		list($codigo_organismo, $codigo_dependencia, $EsParticular, $representante, $cargorepresentante)=SPLIT( '[|]', $linea);
		
		$scon= "select max(Secuencia) from 
	                                     cp_documentodistribucionext 
								    where 
									     CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and
										 Cod_TipoDocumento='".$_POST['t_documento']."' and
										 Cod_Documento = '$cod_documento' and
										 Periodo = '".date("Y")."'"; //echo $scon;
	   $qcon= mysql_query($scon) or die ($scon.mysql_error());
	   $fcon=mysql_fetch_array($qcon); //echo "Secuencia=".$fcon[Seciencia];
	   $secuencia=(int) ($fcon[0]+1); /// CODIGO DEL DOCUMENTO
       $secuencia=(string) str_repeat("0",4-strlen($secuencia)).$secuencia; //echo "NroSecuencia=".$secuencia;
	   
	   if($EsParticular=='S'){$valorPart = 'S';}else{$valorPart = 'N'; }
		
		$sql = "INSERT INTO cp_documentodistribucionext ( CodOrganismo,
														Cod_Documento,
														Cod_TipoDocumento,
														Periodo,
														Secuencia,
														Cod_Organismos,
														Cod_Dependencia,
														Representante,
														Cargo,
														FechaDistribucion,
														PlazoAtencion,
														Estado,
														FlagEsParticular,
														UltimoUsuario,
														UltimaFechaModif)
												VALUES ('".$_SESSION['ORGANISMO_ACTUAL']."',
												        '$cod_documento',
														'".$t_documento."',
														'".date("Y-m-d")."',
														'$secuencia',
														'".$codigo_organismo."',
														'".$codigo_dependencia."',
														'".$representante."',
														'".$cargorepresentante."',
														'".date("Y-m-d")."',
														'".$plazo."',
												        'PR',
														'$valorPart',
														'".$_SESSION["USUARIO_ACTUAL"]."',
														'".date("Y-m-d H:i:s")."')";
		$qry=mysql_query($sql) or die ($sql.mysql_error()); //echo $sql;
   }	
}
///// ----------------------------------------------------------------------------------- 
/////  GUARDAR ENVIO DOCUMENTO SALIDA  
///// -----------------------------------------------------------------------------------
if($accion==guardarEnvio){
connect();
   
   $lineas = split(";", $detalles);
	foreach ($lineas as $linea) {
		list($cod_documento, $periodos, $secuencia)=SPLIT( '[|]', $linea);
		//echo"$cod_documento, $periodos, $secuencia";		      
        $CONT++; echo "CONTADOR= ".$CONT; echo"Cod_Documento=".$cod_documento; echo"Periodos=".$periodos; echo"Secuencia=".$secuencia;
        $s="update cp_documentodistribucionext set Cod_PersonaResp = '".$codempleado."' ,
													    Cod_CargoResp = '".$cod_cargoremit."',
													    FechaEnvio = '".date("Y-m-d")."',
													    Estado = 'EV',
													    UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
													    UltimaFechaModif = '".date("Y-m-d H:i:s")."'
											        where
													    CodOrganismo = '".$_SESSION['ORGANISMO_ACTUAL']."' and
													    Periodo = '".$periodos."' and
														Secuencia = '".$secuencia."' and
														Cod_Documento = '".$cod_documento."'";
        $q=mysql_query($s) or die ($s.mysql_error());
   
        //// SELECT PARA OBTENER DATOS QUE SERAN INTRODUCIDOS EN CP_HISTORICODOCUMENTOEXTSALIDA
	    $scon="select 
	   				   cpdist.Cod_TipoDocumento as Cod_TipoDocumento,
					   cpdext.Cod_Dependencia as CodDependenciaInterna,
					   cpdist.Cod_Dependencia as Cod_DependenciaExterna,
					   cpdext.FechaRegistro as FechaRegistro,
					   cpdist.Cod_Organismos as Cod_Organismos,
					   cpdist.Representante as Destinatario,
					   cpdist.Cargo as CargoDestinatario,
					   cpdext.Remitente as Remitente,
					   cpdext.Cargo as CargoRemitente,
					   cpdext.Asunto as Asunto,
					   cpdext.Descripcion as Descripcion,
					   cpdext.FechaDocumento as FechaDocumento,
					   cpdext.Contenido as Contenido,
					   cpdist.Cod_PersonaResp as Cod_PersonaResp,
					   cpdist.Cod_CargoResp as Cod_CargoResp,
					   cpdist.FechaEnvio as FechaEnvio,
					   cpdist.Estado as Estado,
					   cpdist.FlagEsParticular as flagparticular
				  from 
					   cp_documentodistribucionext cpdist
					   inner join cp_documentoextsalida cpdext on (cpdext.Cod_Documento = cpdist.Cod_Documento)
				 where 
					   cpdist.CodOrganismo = '".$_SESSION['ORGANISMO_ACTUAL']."' and
					   cpdist.Periodo = '".$periodos."' and
					   cpdist.Secuencia = '".$secuencia."' and
					   cpdist.Cod_Documento = '".$cod_documento."'";
       $qcon=mysql_query($scon) or die ($scon.mysql_error());
	   $fcon=mysql_fetch_array($qcon);
	  
	   //// SELECT PARA OBTENER SECUENCIA MAX
	   $smax= "select max(Secuencia) from 
	                                    cp_historicodocumentoextsalida 
								   where 
								        CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and 
										Cod_Documento = '".$cod_documento."' and
										Periodo = '".$periodos."' and
										Cod_Historico = '".$secuencia."'";
	  $qmax= mysql_query($smax) or die ($smax.mysql_error());
	  
	  if(($fcon['flagparticular']=='N')or($fcon['flagparticular']=='N')){
	    $fmax= mysql_fetch_array($qmax);
	  
	    $valorSecuencia=(int) ($fmax[0]+1);
	    $valorSecuencia=(string) str_repeat("0",4-strlen($valorSecuencia)).$valorSecuencia;
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
												  		 '".$cod_documento."',
														 '".$fcon['Cod_TipoDocumento']."',
														 '".$secuencia."',
														 '".$periodos."',
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
														 '".$fcon['Estado']."',
														 '".$_SESSION['USUARIO_ACTUAL']."',
														 '".date("Y-m-d H:i:s")."')";
	  $qhist= mysql_query($shist) or die ($shist.mysql_error());
   
    }
	}
   $sa = "update cp_documentoextsalida set Estado = 'EV',
   										   FechaEnvio = '".date("Y-m-d")."',
										   UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										   UltimaFechaModif = '".date("Y-m-d H:i:s")."'
									  where 
										   CodOrganismo = '".$_SESSION['ORGANISMO_ACTUAL']."' and
										   Periodo = '".$periodo."' and
										   Cod_DocumentoCompleto = '".$_POST['ndoc_completo']."'";
  $qa=mysql_query($sa) or die ($sa.mysql_error());											
}
///// ----------------------------------------------------------------------------------- 
/////  GUARDAR ENVIO DOCUMENTO INTERNO
///// -----------------------------------------------------------------------------------
if($accion==guardarEnvioInterno){
connect();
   
   $lineas = split(";", $detalles);
	foreach ($lineas as $linea) {
		list($cod_documento, $periodos, $secuencia)=SPLIT( '[|]', $linea);
		//echo"$cod_documento, $periodos, $secuencia";		      
   
       $s="update cp_documentodistribucion set Cod_PersonaResp = '".$codempleado."' ,
													    Cod_CargoResp = '".$cod_cargoremit."',
													    FechaEnvio = '".date("Y-m-d")."',
													    Estado = 'EV',
													    UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
													    UltimaFechaModif = '".date("Y-m-d H:i:s")."'
											        where
													    Cod_Organismo = '".$_SESSION['ORGANISMO_ACTUAL']."' and
													    Periodo = '".$periodos."' and
														CodPersona = '".$secuencia."' and
														Cod_Documento = '".$cod_documento."' and 
														Cod_TipoDocumento = '".$cod_tipodocumento."'";
       $q=mysql_query($s) or die ($s.mysql_error());
	   
	   //// --------------------------------------------------------------------------------------------------
	   $sa="update cp_documentointerno set
										 Estado = 'EV',
										 UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										 UltimaFechaModif = '".date("Y-m-d H:i:s")."'
									where
										 CodOrganismo = '".$_SESSION['ORGANISMO_ACTUAL']."' and
										 Periodo = '".$periodos."' and
										 Cod_DocumentoCompleto = '".$cod_documento."' and 
										 Cod_TipoDocumento = '".$cod_tipodocumento."'";
       $qa=mysql_query($sa) or die ($sa.mysql_error());
	   
   }										
}
//// ------------------------------------------------------------------------------------
//// OPERACION IMPRIMIR GMCORRESPONDENCIA.PHP
//// ------------------------------------------------------------------------------------
if ($accion == "consultarPersonasDocInterno") {
	connect();
	
	list($cod_tipodocumento, $cod_documentocompleto)=SPLIT( '[|]', $_POST['codigo']);
	
	$sql = "SELECT *
			FROM cp_documentodistribucion
			WHERE Cod_Documento = '$cod_documentocompleto' AND 
			      Cod_TipoDocumento = '$cod_tipodocumento'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	echo mysql_num_rows($query);
}
//// -----------------------------------------------------------------------------------
//// PERMITE REALIZAR CAMBIOS VIA MODIFICACION CODIGO
if ($accion == "activarCambio") {
	connect();
	
	$sql = "SELECT *
			FROM cp_documentoacuserecibo
			WHERE CodOrganismo = '0001' AND 
			      Periodo = '2011' and 
				  FechaAcuse = '0000-00-00'";
	$qry = mysql_query($sql) or die ($sql.mysql_error());
	$row = mysql_num_rows($qry);
	if($row!=0){
	  for($i=0;$i<$row;$i++){
	     $field = mysql_fetch_array($qry);
	     list($a,$b) = SPLIT('[ ]',$field['UltimaFechaModif']); 
		 //echo $a.'*'.$b;
		 $s_update = "update 
		                    cp_documentoacuserecibo 
						set 
						    FechaAcuse='$a' 
					  where 
					        CodOrganismo='0001' and 
							Periodo='2011' and 
							Cod_Documento='".$field['Cod_Documento']."' and 
							Cod_TipoDocumento='".$field['Cod_TipoDocumento']."'";
	    
		$q_update = mysql_query($s_update) or die ($s_update.mysql_error());
	  }	
	}
}
?>
