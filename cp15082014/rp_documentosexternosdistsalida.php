<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
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
		<td class="titulo">Reporte | Distribuci&oacute;n x Documentos de Salida</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
echo"<input type='hidden' id='regresar' name='regresar' value='cpe_salidalista' />";

if(!$_POST) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; else { $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; $corganismo = "checked"; }
if(!$_POST){$fEstado="PR"; $cEstado="checked";}

$filtro = "";

if ($forganismo != "") $corganismo = "checked"; else $dorganismo = "disabled";
if ($fdesde != "" and $fhasta != "") $cFechaRegistro = "checked"; else $dFechaRegistro = "disabled";
if ($fElaboradoPor !="") $cElaborado= "checked"; else $dElaborado = "disabled";
if ($fNroDocumento !="") $cNroDocumento="checked"; else $dNroDocumento="disabled";
if ($fTdocumento !="") $cTdocumento="checked"; else $dTdocumento="disabled";
if ($fEstado !="") $cEstado="checked"; else $dEstado="disabled";

$MAXLIMIT=30;

echo "
<form name='frmentrada' action='rp_documentosexternosdistsalidapdf.php' method='POST' target='reporte'>
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
    <input type='checkbox' id='checkFechaRegistro' name='checkFechaRegistro' value='1' $cFechaRegistro onclick='enabledFechaRegistro(this.form);'/>
	 desde:<input type='text' name='fdesde' id='fdesde' size='8' maxlength='10' $dFechaRegistro value='$fechadesde'/>
	 hasta:<input type='text' name='fhasta' id='fhasta' size='8' maxlength='10' $dFechaRegistro value='$fechahasta'/>
   </td>
</tr>
<tr>
 <td width='125' align='right'>Dep. Remitente:</td>
<td><input type='checkbox' id='checkElaborado' name='checkElaborado' value='1' $cElaborado onclick='enabledReporteElaboradoPor(this.form);'/>
     <select id='fElaboradoPor' name='fElaboradoPor' class='selectBig' $dElaborado>
	  <option value=''></option>";
	   getDependenciaSeguridad($fElaboradoPor, $forganismo, 3);
     echo"
	 </select>
  </td>
 <td width='125' align='right'>Nro. Documento:</td>
 <td><input type='checkbox' id='chkNroDocumento' name='chkNroDocumento' value='1' $cNroDocumento onclick='enabledNroDocumentoRp(this.form);'/>
     <input type='text' id='fNroDocumento' name='fNroDocumento' value='$fNroDocumento' $dNroDocumento>
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
<center><input type='submit' name='btBuscar' value='Buscar' onclick='filtroDocumentoSalidaExtRP(this.form, 0)'/></center>
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