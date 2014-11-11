<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
include("ControlCorrespondencia.php");
//	------------------------------------
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="cp_script.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Salida de Documentos Externos</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
echo"<input type='hidden' id='regresar' name='regresar' value='cpe_salidalista' />";

if(!$_POST) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; else { $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; $corganismo = "checked"; }
if(!$_POST){$fEstado="PR"; $cEstado="checked";}
if(!$_POST) $fElaboradoPor = $_SESSION["FILTRO_DEPENDENCIA_ACTUAL"]; else{ $fElaboradoPor = $_POST['fElaboradoPor']; $cElaborado = "checked"; }

$filtro = "";

if ($forganismo != "") { $filtro .= " AND (CodOrganismo = '".$forganismo."')"; $corganismo = "checked"; } else $dorganismo = "disabled"; // listo
if ($fcodocumento !="") { $filtro .= "AND (CodTipoDocumento= '".$fcodocumento."')"; $codocumento="checked";} else $documento = "disabled";
if ($fDestinatario !=""){ $filtro .="AND (Remitente= '".$fDestinatario."')"; $cDestinatario = "checked";}else $dDestinatario = "disabled"; // listo 
if ($fElaboradoPor !=""){ $filtro .="AND (Cod_Dependencia= '".$fElaboradoPor."')"; $cElaborado= "checked";}else $dElaborado = "disabled"; // listo
if ($fordenardoc !=""){ $filtro .="AND (Cod_TipoDocumento= '".$fordenardoc."')"; $cOrdenarDoc= "checked";}else $dOrdenarDoc = "disabled";
if ($fEstado !=""){ $filtro .="AND (Estado='".$fEstado."')"; $cEstado="checked";} else $dEstado="disabled";
if ($fTdocumento !=""){ $filtro .="AND (Cod_TipoDocumento='".$fTdocumento."')"; $cTdocumento="checked";} else $dTdocumento="disabled"; // Listo

if ($fdesde != "" and $fhasta != "") { // FECHA DE REGISTRO DEL DOCUMENTO

  list($d, $m, $a)=SPLIT('[/.-]', $_POST['fdesde']); $fechadesde=$a.'-'.$m.'-'.$d;
  list($d, $m, $a)=SPLIT('[/.-]', $_POST['fhasta']); $fechahasta=$a.'-'.$m.'-'.$d;
  
	if ($fdesde != "") $filtro .= " AND (FechaRegistro >= '$fechadesde')";
	if ($fhasta != "") $filtro .= " AND (FechaRegistro <= '$fechahasta')"; 
	$cFechaRecibido = "checked"; 
	
	list($a, $m, $d)=SPLIT('[/.-]', $fechadesde); $fechadesde=$d.'-'.$m.'-'.$a;
    list($a, $m, $d)=SPLIT('[/.-]', $fechahasta); $fechahasta=$d.'-'.$m.'-'.$a;
	
} else $dFechaRecibido = "disabled";


$MAXLIMIT=30;

echo "
<form name='frmentrada' action='cpe_salidalista.php?limit=0' method='POST'>
<input type='hidden' name='limit' value='".$limit."'>
<div class='divBorder' style='width:1000px;'>
<table width='1000' class='tblFiltro'>
<tr>
   <td width='125' align='right'>Organismo:</td>
   <td> 
      <input type='checkbox' id='checkorganismos' name='checkorganismos' value='1' $corganismo onclick='this.checked=true;'/>
	     <select name='forganismo' id='forganismo' class='selectBig' $dorganismo>";
		   getOrganismos(3,$_SESSION['ORGANISMO_ACTUAL']);
		 echo"
		 </select>
   </td>
   <td width='125' align='right' >Fecha Elaborado:</td>
   <td> 
    <input type='checkbox' id='checkFechaRecibido' name='checkFechaRecibido' value='1' $cFechaRecibido onclick='enabledFechaRecibido(this.form);'/>
	 desde:<input type='text' name='fdesde' id='fdesde' size='8' maxlength='10' $dFechaRecibido value='$fechadesde'/>
	 hasta:<input type='text' name='fhasta' id='fhasta' size='8' maxlength='10' $dFechaRecibido value='$fechahasta'/>
   </td>
</tr>
<tr>
 <td width='125' align='right'>Destinatario:</td>
 <td>
    <input type='checkbox' id='checkDestinatario' name='checkDestinatario' value='1' $cDestinatario onclick='enabledDestinatarioSailda(this.form);'/>
	 <select id='fDestinatario' name='fDestinatario' class='selectBig' $dDestinatario>
	 <option value=''></option>";
	  getDestinatario(1, $fDestinatario);
	echo"
	</select>
 </td>
 <td width='125' align='right'>Elaborado por:</td>
 <td><input type='checkbox' id='checkElaborado' name='checkElaborado' value='1' $cElaborado onclick='this.checked=true;'/>
     <select id='fElaboradoPor' name='fElaboradoPor' class='selectBig' $dElaborado>
	  <option value=''></option>";
	   getDependenciaSeguridad($fElaboradoPor, $forganismo, 3);
     echo"
	 </select>
  </td>
</tr>
<tr>
<td width='125' align='right'>Tipo Documento:</td>
<td>
  <input type='checkbox' id='checkTdocumento' name='checkTdocumento' value='1' $cTdocumento onclick='enabledTdocumento(this.form);'/>
	 <select id='fTdocumento' name='fTdocumento' class='selectMed' $dTdocumento>
	 <option value=''></option>";
	  getTdocumento(0, $fTdocumento);
	echo"
	</select>
</td>
<td width='125' align='right'>Estado:</td>
<td>
	<input type='checkbox' id='checkEstado' name='checkEstado' value='1' $cEstado onclick='enabledEstado(this.form);'/>
	<select name='fEstado' id='fEstado' class='selectMed' $dEstado>
		 <option value=''></option>";
		getEstado( 3, $fEstado);
		echo "
	</select>
</td>
</tr>
</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar'/></center>
<br /><div class='divDivision'>Listado de Documentos</div><br />";
///_________________________________________________________________________________________
$year = date("Y");
$sql="SELECT * 
        FROM 
		     cp_documentoextsalida 
	   WHERE 
	         CodOrganismo = '".$_SESSION['ORGANISMO_ACTUAL']."' 
			 $filtro
	ORDER BY 
	         Cod_Documento"; //echo $sql;
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
$rows=$registros;  //echo $rows;
?>
<table width="1000" class="tblBotones">
<tr>
<td><div id="rows"></div></td>
<td width="250">
		</td>
		<td align="right">
<input type="button" id="btNuevo" name="btNuevo" class="btLista" value="Nuevo" onclick="cargarPagina(this.form,'cpe_salidanuevo.php?regresar=cpe_salidalista&fElaboradoPor=<?=$fElaboradoPor?>');"/>
<input type="button" id="btEditar" name="btEditar" value="Editar" class="btLista" onclick="CargarOpcionEditarSalida(this.form, 'cpe_salidaeditar.php?regresar=cpe_salidalista&fEstado=<?=$fEstado?>&fElaboradoPor=<?=$fElaboradoPor?>&fEstado=<?=$fEstado?>','SELF');"/>
<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'cpe_salidaver.php', 'BLANK', 'height=580, width=1000, left=200, top=70, resizable=yes');" />
<? $inactiva='disabled';
	$sql_permiso="select * from seguridad_autorizaciones
			  where Usuario='".$_SESSION['USUARIO_ACTUAL']."' and CodAplicacion='CP'";
	$qry_permiso=mysql_query($sql_permiso) or die ($sql_permiso.mysql_error());
	$field_permiso=mysql_fetch_array($qry_permiso);

if ($field_permiso['FlagEliminar']=='S')
	$inactiva='';
else 
	$inactiva='disabled';

?>
<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Anular" onclick="AnularRegistroSalidaExterna(this.form, 'cpe_salidalista.php?limit=0', '1', 'PERSONAS');" <?=$inactiva?>/>
<input name="btModifRest" type="button" class="btLista" id="btModifRest" value="Modif.Rest" onclick="CargarOpcionEditDocSalida(this.form, 'cpe_editor.php?fEstado=<?=$fEstado?>&fElaboradoPor=<?=$fElaboradoPor?>', 'SELF')"/>|
<input type="button" class="btLista" id="btImprimir" name="btImprimir" value="Imprimir" onclick="cargarOpcionDocumentoSalida(this.form, 'cpe_docsalidaformatos.php', 'BLANK', 'height=800, width=800, left=200, top=70, resizable=yes');"/></td>
	</tr>
</table>
<input type="hidden" name="registro" id="registro" />
<table align="center">
<tr>
  <td align="center">
  <div style="overflow:scroll; width:1000px; height:300px;">
<table width="1300" class="tblLista">
	<tr class="trListaHead">
      <th width="40"></th>
      <th>Nro. Documento</th>
      <th>A&ntilde;o</th>
      <th>Tipo Documento</th>
      <th>Remitente</th>
      <th>Asunto</th>
      <th>Comentario</th>
      <th>Fecha Documento</th>
      <th>Plazo Atenci&oacute;n</th>
      <th>Estado</th>
   </tr>
<?php 
if ($registros!=0) {
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		//// _________ CONSULTO PARA OBTENER LA DESCRIPCION DE TIPO DE DOCUMENTO A MOSTRAR
		$sqltipodoc="SELECT 
		                    Descripcion 
		               FROM 
					        cp_tipocorrespondencia 
					  WHERE 
					        Cod_TipoDocumento='".$field['Cod_TipoDocumento']."'";
		$qrytipodoc=mysql_query($sqltipodoc) or die ($sqltipodoc.mysql_error());
		$fieldtipodoc=mysql_fetch_array($qrytipodoc);
		
		//// _________ CONSULTO PARA OBTENER INFORMACION DEL REMITENTE ORGANISMO - DEPENDENCIA
		$sdocsalida="select 
		                    pforg.Organismo as organismo, 
		                    pforg.RepresentLegal as r_legalorg, 
							pfdep.Representante as r_legaldep, 
							pfdep.Dependencia as dependencia  
		               from 
					        pf_organismosexternos pforg, 
							pf_dependenciasexternas pfdep
					  where
					        pforg.CodOrganismo= pfdep.CodOrganismo AND 
							pfdep.CodDependencia= '".$field['Cod_Dependencia']."'";
		$qdocsalida=mysql_query($sdocsalida) or die ($sdocsalida.mysql_error());
		$fdocsalida=mysql_fetch_array($qdocsalida);
		
		if($field['Estado']=='PE')$estado='Pendiente';
		if($field['Estado']=='PR')$estado='Preparación';
		if($field['Estado']=='EV')$estado='Enviado';
		if($field['Estado']=='PP')$estado='Preparado';
		if($field['Estado']=='AN')$estado='Anulado';
        if($field['Estado']=='RE')$estado='Recibido';
	
		//// _____ CAMBIO DE FORMATO DE FECHA PARA MOSTRAR
		list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaRegistro']); $f_elaborado=$d.'-'.$m.'-'.$a;
		list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaDocumento']); $f_documento=$d.'-'.$m.'-'.$a;
		if($field['FlagConfidencial']==1){$conf='checked onclick="this.checked=!this.checked"';}else{$conf='disabled="disabled"';}
		//// ____________________________________________________________________________________________
		$sdest="select 
		               md.Dependencia
				   from 
					   mastdependencias md 
				   where 
					   md.CodDependencia='".$field['Cod_Dependencia']."'";
		$qdest= mysql_query($sdest) or die ($sdest.mysql_error());
		$fdest=mysql_fetch_array($qdest);
		//// ____________________________________________________________________________________________
		
		$id = $field['Cod_TipoDocumento'].'|'.$field['Cod_DocumentoCompleto'];
		//// ____________________________________________________________________________________________
		echo "
		<tr class='trListaBody' onclick='mClk(this, \"registro\");' id='$id'>
		    <td align='center'><img src='imagenes/activo2.png' style='width:20px;height=10px;' /><input type='hidden' id='status' name='status' value='".$field['Estado']."'/></td>
			<td align='center'>".$field['Cod_DocumentoCompleto']."</td>
			<td align='center'>".$field['Periodo']."</td>
			<td align='center'>".utf8_decode($fieldtipodoc['Descripcion'])."</td>
			<td align='center'>".utf8_encode($fdest['0'])."</td>
			<td align='left'>".($field['Asunto'])."</td>
			<td align='left'>".($field['Descripcion'])."</td>
			<td align='center'>$f_elaborado</td>
			<td align='center'>".$field['PlazoAtencion']." - día(s)</td>
			<td align='center'>$estado</td>
		</tr>";
	}
	}
$rows=(int)$rows;
/*echo "
<script type='text/javascript' language='javascript'>
	totalRegistros($registros, \"$_ADMIN\", \"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
	totalLotes($registros, $rows, ".$_GET['limit'].");
</script>";	*/			
?>
</table>
</div>
</td></tr></table>
<script type="text/javascript" language="javascript">
	totalRegistrosSalista(<?=$registros?>, "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
</script>

</form>
</body>
</html>
