<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
//	------------------------------------
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
		<td class="titulo">Reporte | Distribuci&oacute;n</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php

if(!$_POST) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; else $corganismo = "checked";
if(!$_POST){$fEstado="EV"; $cEstado="checked";}

$filtro = "";

if ($forganismo != "") $corganismo = "checked"; else $dorganismo = "disabled";
if ($fRegInt !="") $cCodDocumento="checked"; else $dCodDocumento = "disabled";
if ($fremitente !="") $cRemitente = "checked"; else $dRemitente = "disabled";
if ($fEstado !="") $cEstado="checked"; else $dEstado="disabled";
if ($fTdocumento !="") $cTDocumento="checked"; else $dTDocumento="disabled";
if ($fdesde != "" and $fhasta != "") $cFechaRecibido = "checked"; else $dFechaRecibido = "disabled";

$MAXLIMIT=30;

//// -------------------------------------------------------------------------------------------
echo "
<form name='frmentrada' action='cp_entradadistdetallepdf.php?limit=0' method='POST' target='reporte'>
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
   <td width='125' align='right' >Fecha Enviado:</td>
  <td>
     <input type='checkbox' id='checkFechaRecibido' name='checkFechaRecibido' value='1' $cFechaRecibido onclick='enabledFechaRecibido(this.form);'/>
	 desde:<input type='text' name='fdesde' id='fdesde' size='8' maxlength='10' $dFechaRecibido value='$fechadesde'/>
	 hasta:<input type='text' name='fhasta' id='fhasta' size='8' maxlength='10' $dFechaRecibido value='$fechahasta'/>
   </td>
</tr>
<tr>
 <td width='125' align='right'>Dep. Destinataria:</td>
 <td>
    <input type='checkbox' id='checkRemitente' name='checkRemitente' value='1' $cRemitente onclick='getRemitenteRpEntradaDistDetalle(this.form);'/>
	 <select id='fremitente' name='fremitente' class='selectBig' $dRemitente>
	 <option value=''></option>";
	  getRemitente(4, $fremitente);
	echo"
	</select>
 </td>
 <td width='125' align='right'>Nro. Documento:</td>
<td>
	<input type='checkbox' id='chkRegInt' name='chkRegInt' value='1' $cCodDocumento onclick='enabledRegistroInt(this.form);'/>
	<input type='text' id='fRegInt' name='fRegInt' $dCodDocumento/>
</td>
</tr>
<tr>
<td width='125' align='right'>Tipo Documento</td>
<td>
  <input type='checkbox' id='checkTdocumento' name='checkTdocumento' value='1' $cTDocumento onclick='enabledTdocumento(this.form);'/>
	 <select id='fTdocumento' name='fTdocumento' class='selectMed' $dTDocumento>
	 <option value=''></option>";
	  getTdocumento(1, $fTdocumento);
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
</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar'  onclick='filtroDocumentosEntradaDistDetalle(this.form, 0);'></center>
<br /><div class='divDivision'>Listado de Documentos</div><br />";
///________________________________________________________________________________________
?>
<div style="width:1000px" class="divFormCaption"></div>
<center>
<iframe name="reporte" id="reporte" style="border:solid 1px #CDCDCD; width:1000px; height:300px;">
</iframe>
</center>
</form>
</body>
</html>