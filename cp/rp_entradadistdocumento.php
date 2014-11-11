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
<script type="text/javascript" language="javascript" src="cp_script.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Reporte | Distribuci&oacute;n x Documentos de Entrada</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php

if(!$_POST) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; else $corganismo = "checked"; 
if(!$_POST){$fEstado="RE"; $cEstado="checked";}
if(!$_POST) $valor = "disabled";
//echo "Estado=".$fEstado;
$filtro = "";

if ($forganismo != "") $corganismo = "checked"; else $dorganismo = "disabled"; // ORGANISMO INTERNO
if ($fdesde != "" and $fhasta != "") $cFechaRecibido = "checked"; else $dFechaRecibido = "disabled"; // FECHA RECIBIDO
if ($fremitente !="")$cRemitente = "checked";else $dRemitente = "disabled"; // ORGANISMO EXTERNO
if ($fRecibidoPor !="") $cRecibido= "checked"; else $dRecibido = "disabled"; // DEPENDENCIA RECIBIDO
if ($DepRemitente !="") $cDepRemitente="checked"; else $dDepRemitente="disabled";// DEPENDENCIA EXTERNA
if ($fNroDocumento !="") $cNroDocumento="checked"; else $dNroDocumento="disabled";// NUMERO DOCUMENTO
if ($fTdocumento !="") $cTDocumento="checked"; else $dTDocumento="disabled"; // TIPO DE DOCUMENTO
if ($fEstado !="") $cEstado="checked"; else $dEstado="disabled"; // ESTADO DEL DOCUMENTO


/*if ($forganismo != "") { $filtro .= " AND (CodOrganismo = '".$forganismo."')"; $corganismo = "checked"; } else $dorganismo = "disabled"; // ORGANISMO INTERNO
if ($fcodocumento !="") { $filtro .= "AND (CodTipoDocumento= '".$fcodocumento."')"; $codocumento="checked";} else $documento = "disabled";// CODIGO 
//if ($fechaRecibido !=""){ $filtro .= "AND (FechaRegistro= '".$fechaRecibido."')"; $cFechaRecibido = "checked";} else $dFechaRecibido = "disabled";

if ($fRecibidoPor !=""){ $filtro .="AND (RecibidoPor= '".$fRecibidoPor."')"; $cRecibido= "checked";}else $dRecibido = "disabled";
if ($fordenardoc !=""){ $filtro .="AND (Cod_TipoDocumento= '".$fordenardoc."')"; $cOrdenarDoc= "checked";}else $dOrdenarDoc = "disabled";


if ($fTdocumento !=""){ $filtro .="AND (Cod_TipoDocumento='".$fTdocumento."')"; $cTDocumento="checked";} else $dTDocumento="disabled"; // TIPO DE DOCUMENTO
if ($fremitente !=""){ $filtro .="AND (Cod_Organismos= '".$fremitente."')"; $cRemitente = "checked";}else $dRemitente = "disabled"; // ORGANISMO EXTERNO
if ($DepRemitente !=""){ $filtro .="AND (Cod_Dependencia='".$DepRemitente."')"; $cDepRemitente="checked";} else $dDepRemitente="disabled";// DEPENDENCIA EXTERNA
if ($fEstado !=""){ $filtro .="AND (Estado='".$fEstado."')"; $cEstado="checked";} else $dEstado="disabled"; // ESTADO DEL DOCUMENTO
if ($fNroDocumento !=""){ $filtro .="AND (NumeroRegistroInt='".$fNroDocumento."')"; $cNroDocumento="checked";} else $dNroDocumento="disabled"; */// NUMERO DOCUMENTO


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
<form name='frmentrada' method='POST' action='rp_entradadistdocumentopdf.php' target='reporte' >
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
   <td width='125' align='right' >Fecha Recibido:</td>
   <td>
     <input type='checkbox' id='checkFechaRecibido' name='checkFechaRecibido' value='1' $cFechaRecibido onclick='enabledRpFechaRecibido(this.form);'/>
	 desde:<input type='text' name='fdesde' id='fdesde' size='8' maxlength='10' $dFechaRecibido value='$fechadesde'/>
	 hasta:<input type='text' name='fhasta' id='fhasta' size='8' maxlength='10' $dFechaRecibido value='$fechahasta'/>
   </td>
</tr>

<tr>
 <td width='125' align='right'>Org. Remitente:</td>
 <td>
    <input type='checkbox' id='checkRemitente' name='checkRemitente' value='1' $cRemitente onclick='enabledOrgRemitenteExterno(this.form);'/>
	 <select id='fremitente' name='fremitente' class='selectBig' $dRemitente>
	 <option value=''></option>";
	  getRemitente(2,$fremitente);
	echo"
	</select>
 </td>
 <td width='125' align='right'>Recibido por:</td>
 <td><input type='checkbox' id='checkRecibido' name='checkRecibido' value='1' $cRecibido onclick='enabledRecibidoPor(this.form);'/>
     <select id='fRecibidoPor' name='fRecibidoPor' class='selectBig' $dRecibido>
	  <option value=''></option>";
	   getRecibidoPor(0,$fRecibidoPor);
     echo"
	 </select>
  </td>
</tr>

<tr>
 <td width='125' align='right'>Dep. Remitente:</td>
 <td>
    <input type='checkbox' id='checkDepRemitente' name='checkDepRemitente' value='1' $cDepRemitente onclick='enabledDepRemitente(this.form);'/>
	 <select id='DepRemitente' name='DepRemitente' class='selectBig' $dDepRemitente>
	 <option value=''></option>";
	  getDepRemitente(0, $DepRemitente);
	echo"
	</select>
 </td>
 <td width='125' align='right'>Nro. Documento:</td>
<td>
	<input type='checkbox' id='checkNroDocumento' name='checkNroDocumento' value='1' $cNroDocumento onclick='enabledNroDocumento(this.form);'/>
	<input type='text' name='fNroDocumento' id='fNroDocumento' value='$fNroDocumento' $dNroDocumento>
</td>
</tr>

<tr>
<td width='125' align='right'>Tipo Documento:</td>
<td>
  <input type='checkbox' id='checkTdocumento' name='checkTdocumento' value='1' $cTDocumento onclick='enabledTdocumento(this.form);'/>
	 <select id='fTdocumento' name='fTdocumento' class='selectMed' $dTDocumento>
	 <option value=''></option>";
	  getTdocumento( 1, $fTdocumento);
	echo"
	</select>
</td>
</td> 
<td width='125' align='right'>Estado:</td>
<td>
	<input type='checkbox' id='checkEstado' name='checkEstado' value='1' $cEstado onclick='enabledEstado(this.form);'/>
	<select name='fEstado' id='fEstado' class='selectMed' $dEstado>
		 <option value=''></option>";
		getEstado( 6, $fEstado);
		echo "
	</select>
</td>
</tr>
</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar'  onclick='filtroDocumentosEntradaDistXDoc(this.form, 0);'></center>
<br /><div class='divDivision'>Listado de Documentos</div><br />";
///_________________________________________________________________________________________
?>
<div style="width:1000px" class="divFormCaption"></div>
<center>
<iframe name="reporte" id="reporte" style="border:solid 1px #CDCDCD; width:1000px; height:300px;">
</iframe>
</center>
</form>
</body>
</html>