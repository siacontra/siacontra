<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
include "ControlCorrespondencia.php";
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
		<td class="titulo">Documentos Internos | Env&iacute;o</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php

if(!$_POST) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; else{$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; $corganismo = "checked";} 
if(!$_POST) $fremitente = $_SESSION["FILTRO_DEPENDENCIA_ACTUAL"]; else{ $fremitente = $_POST['fremitente']; $cRemitente = "checked"; }
if(!$_POST){$fEstado="PE"; $cEstado="checked";}else {$fEstado="PE"; $cEstado="checked";}

$filtro = "";

if ($forganismo != "") { $filtro .= " AND (Cod_Organismo = '".$forganismo."')"; $corganismo = "checked"; } else $dorganismo = "disabled";
if ($fremitente !=""){ $filtro .="AND (cpdoc.Cod_Dependencia= '".$fremitente."')"; $cRemitente = "checked";}else $dRemitente = "disabled";

if ($fEstado !=""){ $filtro .="AND (cpdist.Estado='".$fEstado."')"; $cEstado="checked";} else $dEstado="disabled";
if ($fTdocumento !=""){ $filtro .="AND (cpdist.Cod_TipoDocumento='".$fTdocumento."')"; $cTDocumento="checked";} else $dTDocumento="disabled";
if ($fDestinatario !=""){ $filtro .="AND (cpdist.CodDependencia='".$fDestinatario."')"; $cDestinatario="checked";} else $dDestinatario="disabled";
if ($NroDocumento !=""){ $filtro .="AND (cpdist.Cod_Documento='".$NroDocumento."')"; $cNroDocumento="checked";} else $dNroDocumento="disabled";

if ($fdesde != "" and $fhasta != "") { // FECHA DE REGISTRO DEL DOCUMENTO

  list($d, $m, $a)=SPLIT('[/.-]', $_POST['fdesde']); $fechadesde=$a.'-'.$m.'-'.$d;
  list($d, $m, $a)=SPLIT('[/.-]', $_POST['fhasta']); $fechahasta=$a.'-'.$m.'-'.$d;
  
	if ($fdesde != "") $filtro .= " AND (cpdist.FechaDistribucion >= '$fechadesde')";
	if ($fhasta != "") $filtro .= " AND (cpdist.FechaDistribucion <= '$fechahasta')"; 
	$cFechaRecibido = "checked"; 
	
	list($a, $m, $d)=SPLIT('[/.-]', $fechadesde); $fechadesde=$d.'-'.$m.'-'.$a;
    list($a, $m, $d)=SPLIT('[/.-]', $fechahasta); $fechahasta=$d.'-'.$m.'-'.$a;
	
} else $dFechaRecibido = "disabled";


$MAXLIMIT=30;

$d=date("Y-m-d H:i:s"); echo "<input type='hidden' name='limit' value='$d'>" ;
echo "
<form name='frmentrada' action='cpi_docinternosenvio.php?limit=0' method='POST'>
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
   <td width='125' align='right' >Fecha Creado:</td>
   <td> 
     <input type='checkbox' id='checkFechaRecibido' name='checkFechaRecibido' value='1' $cFechaRecibido onclick='enabledFechaRecibido(this.form);'/>
	 desde:<input type='text' name='fdesde' id='fdesde' size='8' maxlength='10' $dFechaRecibido value='$fechadesde'/>
	 hasta:<input type='text' name='fhasta' id='fhasta' size='8' maxlength='10' $dFechaRecibido value='$fechahasta'/>
   </td>
</tr>

<tr>
 <td width='125' align='right'>Dep. Remitente:</td>
 <td>
    <input type='checkbox' id='checkRemitente' name='checkRemitente' value='1' $cRemitente onclick='this.checked=true'/>
	 <select id='fremitente' name='fremitente' class='selectBig' $dRemitente>
	 <option value=''></option>";
	  getDependenciaSeguridad($fremitente, $forganismo, 3);
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
 <td width='125' align='right'>Destinatario:</td>
 <td>
    <input type='checkbox' id='checkDestinatario' name='checkDestinatario' value='1' $cDestinatario onclick='enabledDestinatarioDocInt(this.form);'/>
	 <select id='fDestinatario' name='fDestinatario' class='selectBig' $dDestinatario>
	 <option value=''></option>";
	  getRemitente(4, $fDestinatario);
	echo"
	</select>
 </td>
 <td width='125' align='right'>Estado:</td>
<td>
	<input type='checkbox' id='checkEstado' name='checkEstado' value='1' $cEstado onclick='this.checked=true'/>
	<select name='fEstado' id='fEstado' class='selectMed' $dEstado>
		 <option value='PE'>Pendiente</option>";
		//getEstado( 4, $fEstado);
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
	  getTdocumento( 3, $fTdocumento);
	echo"
	</select>
</td>
</td>
<td width='125' align='right'></td>
<td>
</td>
</tr>
</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar'></center>
<br /><div class='divDivision'>Listado de Documentos</div><br />
<form/>";
///_________________________________________________________________________________________
$year = date("Y");
$sql="SELECT 
             cpdist.Cod_TipoDocumento,
			 cpdist.CodDependencia,
			 cpdist.Cod_Organismo,
			 cpdist.CodPersona,
			 cpdist.CodCargo,
			 cpdist.Periodo,
			 cpdist.Cod_Documento,
			 cpdist.Estado 
        FROM 
		     cp_documentodistribucion cpdist
			 inner join cp_documentointerno cpdoc on ((cpdoc.Cod_DocumentoCompleto = cpdist.Cod_Documento) and (cpdoc.Cod_TipoDocumento = cpdist.Cod_TipoDocumento))
	   WHERE 
	         Cod_Organismo = '".$_SESSION['ORGANISMO_ACTUAL']."' AND Procedencia = 'INT'
			 $filtro
	ORDER BY 
	         cpdist.Cod_TipoDocumento"; //echo $sql;
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
		<td align="right"><input type="button" id="btEnvio" name="btEnvio" class="btLista" value="Envío" onclick="cargarEnvioInterno(this.form);"/>
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
      <th>Nro. Documento</th>
      <th>A&ntilde;o</th>
      <th>Tipo Documento</th>
      <th>Asunto</th>
      <th>Dependencia</th>
      <th>Empleado</th>
      <th>Cargo</th>
      <th>Fecha Registro</th>
      <th>Fecha Documento</th>
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
		    $sdep="SELECT 
						mo.Organismo as organismo, 
						md.Dependencia as dependencia,
						mp.NomCompleto as NomCompleto,
						rp.DescripCargo as DescripCargo
				   FROM 
						mastorganismos mo, 
						mastdependencias md,
						mastpersonas mp,
						rh_puestos rp
				  WHERE 
						md.CodDependencia= '".$field['CodDependencia']."' AND
						mo.CodOrganismo= '".$field['Cod_Organismo']."' AND 
						mp.CodPersona = '".$field['CodPersona']."' AND 
						rp.CodCargo = '".$field['CodCargo']."'";
			$qdep=mysql_query($sdep) or die ($sdep.mysql_error());
			$fdep=mysql_fetch_array($qdep);
		
		//// ------------------------------------------------------------------------
		//// CONSULTA PARA OBTENER ALGUNOS DATOS PARA MOSTRAR
		$sdoc = "select * 
		           from 
				        cp_documentointerno 
				  where 
				        CodOrganismo = '".$field['Cod_Organismo']."' and 
						Cod_DocumentoCompleto = '".$field['Cod_Documento']."' and 
						Cod_TipoDocumento = '".$field['Cod_TipoDocumento']."' and
						Periodo = '".$field['Periodo']."'"; //echo $sdoc;
		$qdoc= mysql_query($sdoc) or die ($sdoc.mysql_error());
		$fdoc= mysql_fetch_array($qdoc);
		//// ------------------------------------------------------------------------
		if($field['Estado']=='PE') $estado='Pendiente';
		
		//// _____ CAMBIO DE FORMATO DE FECHA PARA MOSTRAR
		list($a, $m, $d)=SPLIT( '[/.-]', $fdoc['FechaDocumento']); $f_documento=$d.'-'.$m.'-'.$a;
		list($a, $m, $d)=SPLIT( '[/.-]', $fdoc['FechaRegistro']); $f_registro=$d.'-'.$m.'-'.$a;
		
		$id= $field['Cod_Documento']."|".$field['Periodo']."|".$field['CodPersona']."|".$field['Cod_TipoDocumento'];
		echo "
		<tr class='trListaBody' onclick='mClkMulti(this);' id='row_$id'>
		    <td align='center'><img src='imagenes/activo2.png' style='width:20px;height=10px;'/></td>"; 
			
            echo" <input type='checkbox' name='documento' id='$id' value='$id' style='display:none'/></td>
			<td align='center'>".$fdoc['Cod_DocumentoCompleto']."</td>
			<td align='center'>".$field['Periodo']."</td>
			<td align='center'>".$fieldtipodoc['Descripcion']."</td>
			<td align='left'>".($fdoc['Asunto'])."</td>
			<td align='left'>".utf8_encode($fdep['dependencia'])."</td>
			<td align='left'>".utf8_encode($fdep['NomCompleto'])."</td>
			<td align='left'>".$fdep['DescripCargo']."</td>
			<td align='center'>$f_registro</td>
			<td align='center'>$f_documento</td>
			<td align='center'>'2 - días'</td>
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
<script type="text/javascript" language="javascript">
	totalRegistrosDocIntEnv(<?=$registros?>, "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
</script>
</body>
</html>
