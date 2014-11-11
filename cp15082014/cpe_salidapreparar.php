<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
include("ControlCorrespondencia.php");
//	------------------------------------
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('', $concepto);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Salida de Documentos | Preparar</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
echo"<input type='hidden' id='regresar' name='regresar' value='cpe_salidapreparar'";

if(!$_POST) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; else $corganismo = "checked";
if(!$_POST){$fEstado="PR"; $cEstado="checked";} else $cEstado="checked";

$filtro = "";

if ($forganismo != "") { $filtro .= " AND (CodOrganismo = '".$forganismo."')"; $corganismo = "checked"; } else $dorganismo = "disabled"; // listo
if ($fcodocumento !="") { $filtro .= "AND (CodTipoDocumento= '".$fcodocumento."')"; $codocumento="checked";} else $documento = "disabled";
if ($fDestinatario !=""){ $filtro .="AND (Remitente= '".$fDestinatario."')"; $cDestinatario = "checked";}else $dDestinatario = "disabled"; 
if ($fElaboradoPor !=""){ $filtro .="AND (Cod_Dependencia= '".$fElaboradoPor."')"; $cElaborado= "checked";}else $dElaborado = "disabled"; // listo
//if ($fordenardoc !=""){ $filtro .="AND (Cod_TipoDocumento= '".$fordenardoc."')"; $cOrdenarDoc= "checked";}else $dOrdenarDoc = "disabled";
if ($fEstado !=""){ $filtro .="AND (Estado='".$fEstado."')"; $cEstado="checked";} else $dEstado="disabled";
if ($fTdocumento !=""){ $filtro .="AND (Cod_TipoDocumento='".$fTdocumento."')"; $cTDocumento="checked";} else $dTDocumento="disabled";

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
//	ELIMINO EL REGISTRO
if ($_GET['accion']=="ELIMINAR") {
	//
	$sql="DELETE FROM mastpersonas WHERE CodPersona='".$_POST['registro']."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$_GET['limit']=0;
}
echo "
<form name='frmentrada' action='cpe_salidapreparar.php?limit=0' method='POST'>
<input type='hidden' name='limit' value='".$limit."'>
<div class='divBorder' style='width:1000px;'>
<table width='1000' class='tblFiltro'>
<tr>
   <td width='125' align='right'>Organismo:</td>
   <td> 
      <input type='checkbox' id='checkorganismos' name='checkorganismos' value='1' $corganismo onclick='this.checked=true'>
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
    <input type='checkbox' id='checkDestinatario' name='checkDestinatario' value='1' $cDestinatario onclick='enabledDestinatario(this.form);'/>
	 <select id='fDestinatario' name='fDestinatario' class='selectBig' $dDestinatario>
	 <option value=''></option>";
	  getDestinatario(1, $fDestinatario);
	echo"
	</select>
 </td>
 <td width='125' align='right'>Elaborado por:</td>
 <td><input type='checkbox' id='checkElaborado' name='checkElaborado' value='1' $cElaborado onclick='enabledElaboradoPor(this.form);'/>
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
  <input type='checkbox' id='checkTdocumento' name='checkTdocumento' value='1' $cTDocumento onclick='enabledTdocumento(this.form);'/>
	 <select id='fTdocumento' name='fTdocumento' class='selectMed' $dTDocumento>
	 <option value=''></option>";
	  getTdocumento(0, $fTdocumento);
	echo"
	</select>
</td>
<td width='125' align='right'>Estado:</td>
<td>
	<input type='checkbox' id='checkEstado' name='checkEstado' value='1' $cEstado onclick='this.checked=true'/>
	<select name='fEstado' id='fEstado' class='selectMed' $dEstado>
		 <option value='PR'>Preparacion</option>";
		echo "
	</select>
</td>
</tr>
</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar'></center>
<br /><div class='divDivision'>Listado de Documentos</div><br />
<form/>";
///_________________________________________________________________________________________
$year = date("Y");
$sql="SELECT * 
        FROM 
		     cp_documentoextsalida 
	   WHERE 
	         CodOrganismo = '".$_SESSION['ORGANISMO_ACTUAL']."' and Estado='PR'
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

<!--<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'cpe_salidaver.php', 'BLANK', 'height=500, width=900, left=200, top=200, resizable=yes');" />-->

<? if($registros==0){ ?>
  <input type="button" id="btContenido" name="btContenido" class="btLista" value="Contenido" disabled  onclick="cargarPagina(this.form,'cpe_editorprepararsalida.php?regresar=cpe_salidapreparar');"/><? }else { ?>
  <input type="button" id="btContenido" name="btContenido" class="btLista" value="Contenido"  onclick="cargarPagina(this.form,'cpe_editorprepararsalida.php?regresar=cpe_salidapreparar');"/><? } ?>
		</td>
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
		
		if($field['Estado']=='PR') $estado='Preparación';
		//// _____ CAMBIO DE FORMATO DE FECHA PARA MOSTRAR
		list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaRegistro']); $f_elaborado=$d.'-'.$m.'-'.$a;
		list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaEnvio']); $f_envio=$d.'-'.$m.'-'.$a;
		if($field['FlagConfidencial']==1){$conf='checked onclick="this.checked=!this.checked"';}else{$conf='disabled="disabled"';}
		//// ____________________________________________________________________________________________
		$sdest="select 
		               md.Dependencia
				   from 
					   mastpersonas mp
					   inner join mastdependencias md on (mp.CodPersona = md.CodPersona) 
				   where 
					   mp.CodPersona='".$field['Remitente']."'";
		$qdest= mysql_query($sdest) or die ($sdest.mysql_error());
		$fdest=mysql_fetch_array($qdest);
		//// ____________________________________________________________________________________________
		//// ____________________________________________________________________________________________
		echo "
		<tr class='trListaBody' onclick='mClk(this, \"registro\");' id='".$field['Cod_DocumentoCompleto']."'>
		    <td align='center'><img src='imagenes/activo2.png' style='width:20px;height=10px;' /></td>
			<td align='center'>".$field['Cod_DocumentoCompleto']."</td>
			<td align='center'>".$field['Periodo']."</td>
			<td align='center'>".$fieldtipodoc['Descripcion']."</td>
			<td align='center'>".htmlentities($fdest['0'])."</td>
			<td align='left'>".$field['Asunto']."</td>
			<td align='left'>".$field['Descripcion']."</td>
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
	totalRegistrosSalPre(<?=$registros?>, "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
</script>
</body>
</html>