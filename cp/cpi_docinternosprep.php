<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
include "ControlCorrespondencia.php";
//	------------------------------------
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
		<td class="titulo">Documentos Internos | Preparar</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php

if(!$_POST) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; else{$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; $corganismo = "checked";} 
if(!$_POST) $fremitente = $_SESSION["FILTRO_DEPENDENCIA_ACTUAL"]; else{ $fremitente = $_POST['fremitente']; $cRemitente = "checked"; }
if(!$_POST){$fEstado="PR"; $cEstado="checked";}else $cEstado="checked";

$filtro = "";

if ($forganismo != "") { $filtro .= " AND (CodOrganismo = '".$forganismo."')"; $corganismo = "checked"; } else $dorganismo = "disabled";
if ($fRemitente !=""){ $filtro .="AND (Cod_Dependencia= '".$fRemitente."')"; $cRemitente = "checked";}else $dRemitente = "disabled";
if ($fEstado !=""){ $filtro .="AND (Estado='".$fEstado."')"; $cEstado="checked";} else $dEstado="disabled";
if ($fTdocumento !=""){ $filtro .="AND (Cod_TipoDocumento='".$fTdocumento."')"; $cTDocumento="checked";} else $dTDocumento="disabled";
if ($fDestinatario !=""){ $filtro .="AND (Cod_DependenciaDestinatario='".$fDestinatario."')"; $cDestinatario="checked";} else $dDestinatario="disabled";
if ($NroDocumento !=""){ $filtro .="AND (Cod_Documento='".$NroDocumento."')"; $cNroDocumento="checked";} else $dNroDocumento="disabled";
if ($fEstado !=""){ $filtro .="AND (Estado = '".$fEstado."')"; $cEstado="checked";}else $dEstado="disabled";

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

$d=date("Y-m-d H:i:s"); echo "<input type='hidden' name='limit' value='$d'>" ;
echo "
<form name='frmentrada' action='cpi_docinternosprep.php?limit=0' method='POST'>
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
   <td width='125' align='right' >Fecha Registro:</td>
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
 <td width='125' align='right'>Nro. Documento:</td>
 <td><input type='checkbox' id='checkNroDocumento' name='checkNroDocumento' value='1' $cNroDocumento onclick='enabledNroDocumento(this.form);'/>
     <input type='text' id='NroDocumento' name='NroDocumento' size='20' $dNroDocumento value='$NroDocumento'>
	  <option value=''></option>
 </td>
</tr>

<tr>
 <td width='125' align='right'>Tipo Documento:</td>
<td>
  <input type='checkbox' id='checkTdocumento' name='checkTdocumento' value='1' $cTDocumento onclick='enabledTdocumento(this.form);'/>
	 <select id='fTdocumento' name='fTdocumento' class='selectMed' $dTDocumento>
	 <option value=''></option>";
	  getTdocumento( 3, $fTdocumento);
	echo"
	</select>
</td>
 <td width='125' align='right'>Estado:</td>
<td>
	<input type='checkbox' id='checkEstado' name='checkEstado' value='1' $cEstado onclick='enabledEstado(this.form);'/>
	<select name='fEstado' id='fEstado' class='selectMed' $dEstado>
		 <option value='PR'>Preparaci&oacute;n</option>";
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
		     cp_documentointerno 
	   WHERE 
	         CodOrganismo = '".$_SESSION['ORGANISMO_ACTUAL']."'  AND Estado='PR'
			 $filtro
	ORDER BY 
	         Cod_DocumentoCompleto, Cod_TipoDocumento, Cod_Dependencia, FechaRegistro"; //echo $sql;
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
$rows=$registros; 
?>
<table width="1000" class="tblBotones">
<tr>
<td><div id="rows"></div></td>
<td width="250">
<?php 
echo"<input type='hidden' name='regresar' id='regresar' value='cpe_entrada'/>";
?>
		</td>
		<td align="right">
<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'cpi_docinternover.php', 'BLANK', 'height=500, width=1000, left=200, top=200, resizable=yes');" /> | <input type="button" id="btEliminar" name="btEliminar" class="btLista" value="Contenido" onclick="cargarOpcion(this.form,'cpi_docinternoeditor.php','SELF');"/>
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
      <th></th>
      <th>Nro.Documento</th>
      <th>A&ntilde;o</th>
      <th>Tipo Documento</th>
      <th>Dependencia</th>
      <th>Asunto</th>
      <th>Comentario</th>
      <th>Fecha Registro</th>
      <th>Plazo Atenci&oacute;n</th>
      <th>Estado</th>
   </tr>
<?php 
if ($registros!=0) {
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		//// _________ CONSULTO PARA OBTENER LA DESCRIPCION DE TIPO DE DOCUMENTO A MOSTRAR
		$sqltipodoc="SELECT * FROM cp_tipocorrespondencia WHERE Cod_TipoDocumento='".$field['Cod_TipoDocumento']."'";
		$qrytipodoc=mysql_query($sqltipodoc) or die ($sqltipodoc.mysql_error());
		$fieldtipodoc=mysql_fetch_array($qrytipodoc);
		
		//// _________ CONSULTO PARA OBTENER INFORMACION DEL REMITENTE ORGANISMO - DEPENDENCIA
		if($field['Cod_Dependencia']==''){
		  $sqlorgadep="SELECT 
		                    Organismo as organismo, 
		                    RepresentLegal as r_legalorg 
		               FROM 
					        pf_organismosexternos
					  WHERE 
					       CodOrganismo= '".$field['Cod_Organismos']."'";
		}else{
		    $sqlorgadep="SELECT 
			                mo.Organismo as organismo, 
							md.Dependencia as dependencia 
		               FROM 
					        mastorganismos mo, 
							mastdependencias md 
					  WHERE 
					       md.CodDependencia= '".$field['Cod_Dependencia']."' AND
						   mo.CodOrganismo= '".$field['CodOrganismo']."'";
		 }
		$qryorgadep=mysql_query($sqlorgadep) or die ($sqlorgadep.mysql_error());
		$fieldorgadep=mysql_fetch_array($qryorgadep);
		
		
		if($field['Estado']=='PE')$estado='Pendiente';
		if($field['Estado']=='RE')$estado='Recibido';
		if($field['Estado']=='PR')$estado='Preparaci&oacute;n';
		if($field['Estado']=='CO')$estado='Completado';
		
		//// _____ CAMBIO DE FORMATO DE FECHA PARA MOSTRAR
		list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaRegistro']); $f_Registro=$d.'-'.$m.'-'.$a;
		if($field['FechaDocumento']!='0000-00-00'){ 
		    list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaDocumento']); $f_Documento=$d.'-'.$m.'-'.$a;
		}else{ $f_Documento='';}
		
		$id = $field['Cod_TipoDocumento'].'|'.$field['Cod_DocumentoCompleto'];
		
		echo "
		<tr class='trListaBody' onclick='mClk(this, \"registro\");' id='$id'>
		    <td align='center'><img src='imagenes/activo2.png' style='width:20px;height=10px;' /></td>
			<td align='center'>".$field['Cod_DocumentoCompleto']."</td>
			<td align='center'>".$field['Periodo']."</td>
			<td align='center'>".utf8_encode($fieldtipodoc['Descripcion'])."</td>
			<td align='left'>".utf8_encode($fieldorgadep['dependencia'])."</td>
			<td align='left'>".($field['Asunto'])."</td>
			<td align='left'>".($field['Descripcion'])."</td>
			<td align='center'>$f_Registro</td>
			<td align='center'>".$field['PlazoAtencion']." día(s)</td>
			<td align='center'>$estado</td>
		</tr>";
	}
	}
$rows=(int)$rows;
echo "
<script type='text/javascript' language='javascript'>
	totalRegistros($registros, \"$_ADMIN\", \"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
	totalLotes($registros, $rows, ".$_GET['limit'].");
</script>";				
?>
</table>
</div>
</td></tr></table>
</body>
</html>
