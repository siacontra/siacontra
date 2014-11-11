<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
include("ControlCorrespondencia.php");
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/ecmascript" language="javascript" src="cp_script.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Documentos Recibidos</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333"/>

<?php

if(!$_POST)$forganismo=$_SESSION["FILTRO_ORGANISMO_ACTUAL"]; else $corganismo= "checked";
if(!$_POST){$fEstado="EV"; $cEstado="checked";}else{$fEstado=$_POST['fEstado']; $cEstado="checked";};

if(!$_POST) $cDestinataria="checked";

$filtro = "";

if($forganismo!= ""){$filtro.=" AND(Cod_Organismo = '".$forganismo."')";$corganismo = "checked"; } else $dorganismo = "disabled";
if ($fcodocumento !="") { $filtro .= " AND (CodTipoDocumento= '".$fcodocumento."')"; $codocumento="checked";} else $documento = "disabled";
if ($f_Remitente !=""){ $filtro .=" AND (CodDependencia= '".$f_Remitente."')"; $cRemitente = "checked";}else $dRemitente = "disabled";
if ($fRecibidoPor !=""){ $filtro .=" AND (RecibidoPor= '".$fRecibidoPor."')"; $cRecibido= "checked";}else $dRecibido = "disabled";
if ($fordenardoc !=""){ $filtro .=" AND (Cod_TipoDocumento= '".$fordenardoc."')"; $cOrdenarDoc= "checked";}else $dOrdenarDoc = "disabled";
if ($fEstado !=""){ $filtro .=" AND (Estado='".$fEstado."')"; $cEstado="checked";} else $dEstado="disabled";
if ($fProcedencia !=""){ $filtro .=" AND (Procedencia='".$fProcedencia."')"; $cProcedencia="checked";} else $dProcedencia="disabled";
if ($fTdocumento !=""){ $filtro .=" AND (Cod_TipoDocumento='".$fTdocumento."')"; $cTDocumento="checked";} else $dTDocumento="disabled";

if ($fdesde != "" and $fhasta != "") { // FECHA DE REGISTRO DEL DOCUMENTO

  list($d, $m, $a)=SPLIT('[/.-]', $_POST['fdesde']); $fechadesde=$a.'-'.$m.'-'.$d;
  list($d, $m, $a)=SPLIT('[/.-]', $_POST['fhasta']); $fechahasta=$a.'-'.$m.'-'.$d;
  
	if ($fdesde != "") $filtro .= " AND (FechaDistribucion >= '$fechadesde')";
	if ($fhasta != "") $filtro .= " AND (FechaDistribucion <= '$fechahasta')"; 
	$cFechaRecibido = "checked"; 
	
	list($a, $m, $d)=SPLIT('[/.-]', $fechadesde); $fechadesde=$d.'-'.$m.'-'.$a;
    list($a, $m, $d)=SPLIT('[/.-]', $fechahasta); $fechahasta=$d.'-'.$m.'-'.$a;
	
} else $dFechaRecibido = "disabled";
	
if($fDestinataria!=""){$cDestinataria = "checked";}

$MAXLIMIT=30;

echo "
<form name='frmentrada' action='cpi_depenrecibido.php?limit=0' method='POST'>
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
   <td width='125' align='right' >Fecha Recibidos:</td>
   <td> 
     <input type='checkbox' id='checkFechaRecibido' name='checkFechaRecibido' value='1' $cFechaRecibido onclick='enabledFechaRecibido(this.form);'/>
	 desde:<input type='text' name='fdesde' id='fdesde' size='8' maxlength='10' $dFechaRecibido value='$fechadesde'/>
	 hasta:<input type='text' name='fhasta' id='fhasta' size='8' maxlength='10' $dFechaRecibido value='$fechahasta'/>
   </td>
</tr>
<tr>
 <td width='125' align='right'>Dep. Remitente:</td>
 <td>
    <input type='checkbox' id='checkRemitente' name='checkRemitente' value='1' $cRemitente onclick='enabledRemitente(this.form);'/>
	 <select id='f_Remitente' name='f_Remitente' class='selectBig' $dRemitente>
	 <option value=''></option>";
	  getRemitenteDepRecibido(0, $f_Remitente);
	echo"
	</select>
 </td>
<td width='125' align='right'>Procedencia</td>
<td>
  <input type='checkbox' id='checkProcedencia' name='checkProcedencia' value='1' $cProcedencia onclick='enabledProcedencia(this.form);'/>
	<select name='fProcedencia' id='fProcedencia' class='selectMed' $dProcedencia>
		 <option value=''></option>";
		getProcedencia( 0, $fProcedencia);
		echo "
	</select>
</td>
</tr>

<tr>
 <td width='125' align='right'>Dep. Destinataria:</td>
 <td>
    <input type='checkbox' id='checkDestinataria' name='checkDestinataria' value='1' $cDestinataria onclick='this.checked=true'/>
	 <select id='fDestinataria' name='fDestinataria' class='selectBig' $dDestinataria>";
	 getDepDestinataria(1, $fDestinataria);
	
	getDependenciaSeguridad($fDestinataria, $forganismo, 3);
	  
	echo"
	</select>
 </td>
<td width='125' align='right'>Estado:</td>
<td>
	<input type='checkbox' id='checkEstado' name='checkEstado' value='1' $cEstado onclick='enabledEstado(this.form);'/>
	<select name='fEstado' id='fEstado' class='selectMed' $dEstado>
		 <option value=''></option>";
		getEstadoAtender( 1, $fEstado);
		echo "
	</select>
</td>
</tr>
<tr>
<td width='125' align='right'>Tipo Documento:</td>
<td>
  <input type='checkbox' id='checkTdocumento' name='checkTdocumento' value='1' $cTDocumento onclick='enabledTdocumento(this.form);'/>
	 <select id='fTdocumento' name='fTdocumento' class='selectMed' $dTDocumento>
	 <option value=''></option>";
	  getTdocumento(4, $fTdocumento);
	echo"
	</select>
</td>
<td width='125' align='right'></td>
<td></td>
</tr>
</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar'></center>
<br /><div class='divDivision'>Listado de Documentos</div><br />
<form/>";


///_________________________________________________________________________________________
/// CONSULTA PREVIA PARA OBTENER EL PERMISO PARA DAR ACUSE
   $consultaAcuse="SELECT sa.FlagAgregar, sa.FlagMostrar,sa.FlagModificar 
               FROM seguridad_autorizaciones AS sa 
               WHERE  sa.Usuario = '".$_SESSION['USUARIO_ACTUAL']."' AND sa.CodAplicacion = 'CP' AND sa.Concepto = '01-0017'";
   $qconsultaAcuse=mysql_query($consultaAcuse) or die ($consultaAcuse.mysql_error());
   $fconsultaAcuse=mysql_fetch_array($qconsultaAcuse);

///_________________________________________________________________________________________
///_________________________________________________________________________________________
/// CONSULTA PREVIA PARA OBTENER EL CODPERSONA DEL USUARIO ACTUAL
   $sconsulta="select * from usuarios where Usuario='".$_SESSION['USUARIO_ACTUAL']."'";
   $qconsulta=mysql_query($sconsulta) or die ($sconsulta.mysql_error());
   $fconsulta=mysql_fetch_array($qconsulta);
///_________________________________________________________________________________________
//echo $_SESSION['USUARIO_ACTUAL'];

$year = date("Y");
$sql="SELECT * 
        FROM 
		     cp_documentodistribucion 
	   WHERE 
	         Cod_Organismo= '".$_SESSION['ORGANISMO_ACTUAL']."' AND
			 CodPersona='".$fconsulta['CodPersona']."'	 $filtro
	ORDER BY 
	         Cod_Documento"; //echo $sql;
	         
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
$rows=$registros; //echo $rows;
?>
<table width="1000" class="tblBotones">
<tr>
<td><div id="rows"></div></td>
<td width="250">

		</td>
		<td align="right">
<!--<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'cpi_depenrecibidover.php', 'BLANK', 'height=500, width=1000, left=150, top=70, resizable=yes');" />
<input name="btPDF" type="button" class="btLista" id="btPDF" value="Documento" onclick="window.open('cpi_deprecibidodocumentoprueba.php?registro='+document.getElementById('registro').value,'blank','height=800,width=800,left=200,top=200, resizable=yes')"/>-->
<input name="btPDF" type="button" class="btLista" id="btPDF" value="Documento" onclick="cargarOpcionImprimirDepenRecibido(this.form,'cpi_deprecibidodocumentoprueba.php?registro='+document.getElementById('registro').value,'blank','height=800,width=800,left=200,top=200, resizable=yes')"/>
<?php if ($fconsultaAcuse['FlagMostrar'] == 'S') { ?>
<input name="btVer" type="button" class="btLista" id="btAcuse" value="Acuse" onclick="CargarAcuseDepRecibidos(this.form, 'cpi_acuserecibo.php?regresar=cpi_depenrecibido&fEstado=fEstado','SELF');" />
<?php } ?>
		</td>
	</tr>
</table>
<input type="hidden" name="registro" id="registro"/>
<table align="center">
<tr>
  <td align="center">
  <div style="overflow:scroll; width:1000px; height:300px;">
<table width="1300" class="tblLista">
	<tr class="trListaHead">
      <th></th>
      <th>Nro. Documento</th>
      <th>A&ntilde;o</th>
      <th>Tipo Documento</th>
      <th>Asunto</th>
      <th>Dependencia</th>
      <th>Destinatario</th>
      <th>Cargo</th>
      <th>Fecha Enviado</th>
      <th>Plazo Atenci&oacute;n</th>
      <th>Estado</th>
   </tr>
<?php 
if ($registros!=0) {
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		
		//// ------------------------------------------------------------------- 
		$sqltipodoc="SELECT * FROM cp_tipocorrespondencia WHERE Cod_TipoDocumento='".$field['Cod_TipoDocumento']."'";
		$qrytipodoc=mysql_query($sqltipodoc) or die ($sqltipodoc.mysql_error());
		$fieldtipodoc=mysql_fetch_array($qrytipodoc);
		//// _________ CONSULTO PARA OBTENER LOS DATOS DE DISTRIBUCION
	  if($field['Procedencia']=='EXT'){
		$sdist="select
					  md.Dependencia,
					  rhp.DescripCargo,
					  mtp.Nombres as nombres,
					  mtp.NomCompleto,
                      cptc.Descripcion as desc_tdocumento,
					  mtp.CodPersona as codpersona,
					  cpdexe.Asunto as Asunto,
					  cpdexe.Periodo as Periodo,
					  cpdist.Cod_Documento
				 from
					  mastdependencias md,
					  cp_documentoextentrada cpdexe,
					  cp_documentodistribucion cpdist,
					  rh_puestos rhp,
					  mastpersonas mtp,
                      cp_tipocorrespondencia cptc
                where
					  md.CodDependencia='".$field['CodDependencia']."' and
					  md.CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and
					  rhp.CodCargo='".$field['CodCargo']."' and
					  mtp.CodPersona='".$field['CodPersona']."' and
                      cptc.Cod_TipoDocumento='".$field['Cod_TipoDocumento']."' and
					  cpdist.CodPersona = '".$field['CodPersona']."' and 
					  cpdexe.NumeroRegistroInt = '".$field['Cod_Documento']."'"; //echo $sdist;
	   }else{
	     /*$sdist="select
					  md.Dependencia as dependencia,
					  rhp.DescripCargo as descpcargo,
					  mtp.Nombres as nombres,
					  mtp.NomCompleto as nombrecompleto,
                      cptc.Descripcion as desc_tdocumento,
					  cptc.Cod_TipoDocumento as Cod_TipoDocumento,
					  mtp.CodPersona as codpersona,
					  cpdexe.Asunto as asunto,
					  cpdexe.Periodo as Periodo,
					  cpdist.Cod_Documento as cod_documento
				 from
					  mastdependencias md,
					  cp_documentointerno cpdexe,
					  cp_documentodistribucion cpdist,
					  rh_puestos rhp,
					  mastpersonas mtp,
                      cp_tipocorrespondencia cptc
                where
					  md.CodDependencia='".$field['CodDependencia']."' and
					  md.CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and
					  rhp.CodCargo='".$field['CodCargo']."' and
					  mtp.CodPersona='".$field['CodPersona']."' and
                      cptc.Cod_TipoDocumento='".$field['Cod_TipoDocumento']."' and
					  cpdist.CodPersona = '".$field['CodPersona']."' and 
					  cpdexe.Cod_DocumentoCompleto = '".$field['Cod_Documento']."'"; echo $sdist;*/
		$sdist="select 
		               cpdist.Cod_Documento,
					   cpdist.Periodo,
					   cpcor.Descripcion,
					   cpdoc.Asunto,
					   mastdep.Dependencia,
					   mastp.NomCompleto,
					   rhp.DescripCargo,
					   cpdist.FechaEnvio,
					   cpdist.PlazoAtencion,
					   cpdist.Estado,
					   cpdoc.Cod_TipoDocumento
				  from  
				       cp_documentointerno cpdoc 
					   inner join cp_documentodistribucion cpdist on (cpdist.Cod_Documento = cpdoc.Cod_DocumentoCompleto)and
					                                                 (cpdist.Cod_TipoDocumento=cpdoc.Cod_TipoDocumento)and
																	 (cpdist.Periodo=cpdoc.Periodo) 
					   inner join cp_tipocorrespondencia cpcor on (cpcor.Cod_TipoDocumento = cpdist.Cod_TipoDocumento)
					   inner join mastpersonas mastp on (mastp.CodPersona = cpdist.CodPersona) 
					   inner join mastdependencias mastdep on (mastdep.CodDependencia = cpdist.CodDependencia)
					   inner join rh_puestos rhp on (rhp.CodCargo = cpdist.CodCargo)
				 where 
				       cpdoc.Cod_DocumentoCompleto = '".$field['Cod_Documento']."' and 
					   cpdoc.Estado='".$field['Estado']."' and 
					   cpdoc.Cod_TipoDocumento = '".$field['Cod_TipoDocumento']."' and 
					   cpdoc.Periodo = '".$field['Periodo']."'"; //echo $sdist;
	   }	
		$qdist=mysql_query($sdist) or die ($sdist.mysql_error());
		$rdist=mysql_num_rows($qdist);
		$fdist=mysql_fetch_array($qdist);	
			
		//echo P.'/*/*'.$fdist['Cod_Documento'].'//'.$fdist['Cod_TipoDocumento'].'//'.$fdist['NomCompleto'].'//'.$field['Cod_TipoDocumento'];
		if($field['Estado']=='EV') $estado='Enviado'; 
		if($field['Estado']=='RE') $estado='Recibido';
		
		//// _______________________________
		if($field['FlagConfidencial']==1){$b='checked onclick="this.checked=!this.checked"';}else{$b='disabled="disabled"';}
		
		//// _______________________________
		//// _____ CAMBIO DE FORMATO DE FECHA PARA MOSTRAR
		list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaDistribucion']); $f_distribucion=$d.'-'.$m.'-'.$a;
		$c_doc=$fdist['cod_documento'];
		
		//$id= $field['Cod_Documento']."|".$field['CodPersona']."|".$field['Cod_TipoDocumento'];
		$id = $field['Cod_TipoDocumento'].'|'.$field['Cod_Documento'].'|'.$field['CodPersona'];
		echo "
		<tr class='trListaBody' onclick='mClk(this, \"registro\");' id='$id'>
		    <td align='center'><img src='imagenes/activo2.png' style='width:20px;height=10px;'/><input type='hidden' name='status' id='status' value='".$field['Estado']."'/><input type='hidden' name='procedencia' id='procedencia' value='".$field['Procedencia']."'/></td>
			<td align='center'>".$field['Cod_Documento']."</td>
			<td align='center'>".$field['Periodo']."</td>
			<td align='center'>".$fieldtipodoc['Descripcion']."</td>
			<td align='left'>".$fdist['Asunto']."</td>
			<td align='left'>".htmlentities($fdist['Dependencia'])."</td>
			<td align='center'>".htmlentities($fdist['NomCompleto'])."</td>
			<td align='left'>".htmlentities($fdist['DescripCargo'])."</td>
			<td align='center'>$f_distribucion</td>
			<td align='center'>'".$field['PlazoAtencion']."' día(s)</td>
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
</body>
</html>
