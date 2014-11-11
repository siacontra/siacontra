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
<style type="text/css">
<!--
UNKNOWN {
        FONT-SIZE: small
}
#header {
        FONT-SIZE: 93%; BACKGROUND: url(bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal
}
#header UL {
        PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none
}
#header LI {
        PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px
}
#header A {
        PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none
}
#header A {
        FLOAT: none
}
#header A:hover {
        COLOR: #333
}
#header #current {
        BACKGROUND-IMAGE: url(left_on.gif)
}
#header #current A {
        BACKGROUND-IMAGE: url(right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333
}
-->
</style>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Control de Eventos</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
$MAXLIMIT=30;
if ($filtrar=="DEFAULT") {
	$_POST['chkorganismo']="1"; 
	$_POST['chkdependencia']="1"; 
	$_POST['chksittra']="1"; 
	$_POST['forganismo']=$_SESSION["FILTRO_ORGANISMO_ACTUAL"]; 
	$_POST['fdependencia']=$_SESSION["FILTRO_DEPENDENCIA_ACTUAL"]; 
	$_POST['fsittra']="A"; 
	$_GET['filtro']="AND (me.CodOrganismo=*".$_SESSION["FILTRO_ORGANISMO_ACTUAL"]."* AND me.CodDependencia=*".$_SESSION["FILTRO_DEPENDENCIA_ACTUAL"]."*) AND (me.Estado=*".$_POST['fsittra']."*)";
}
//	VALORES POR DEFECTO Y VALORES SELECCIONADOS DEL FILTRO AL CARGAR O RECARGAR LA PAGINA...............
if ($_POST['chkorganismo']=="1") { $obj[0]="checked"; $obj[1]=""; $obj[2]=$_POST['forganismo']; }
else { $obj[0]=""; $obj[1]="disabled"; $obj[2]="0"; }

if ($_POST['chkedoreg']=="1") { $obj[3]="checked"; $obj[4]=""; $obj[5]=$_POST['fedoreg']; }
else { $obj[3]=""; $obj[4]="disabled"; $obj[5]="0"; }
if ($_POST['chkdependencia']=="1") { $obj[6]="checked"; $obj[7]=""; $obj[8]=$_POST['fdependencia']; $obj[9]=$_POST['forganismo']; }
else { $obj[6]=""; $obj[7]="disabled"; $obj[8]="0"; $obj[9]="0"; }
if ($_POST['chksittra']=="1") { $obj[10]="checked"; $obj[11]=""; $obj[12]=$_POST['fsittra']; }
else { $obj[10]=""; $obj[11]="disabled"; $obj[12]="0"; }
if ($_POST['chktiponom']=="1") { $obj[13]="checked"; $obj[14]=""; $obj[15]=$_POST['ftiponom']; }
else { $obj[13]="";	$obj[14]="disabled"; $obj[15]="0"; }
if ($_POST['chkbuscar']=="1") { $obj[16]="checked";	$obj[17]=""; $obj[18]=$_POST['sltbuscar'];  $obj[19]=""; $obj[20]=$_POST['fbuscar']; }
else { $obj[16]="";	$obj[17]="disabled"; $obj[18]="0";  $obj[19]="disabled"; $obj[20]=""; }
if ($_POST['chkperfilnom']=="1") { $obj[21]="checked"; $obj[22]=""; $obj[23]=$_POST['fperfilnom']; }
else { $obj[21]=""; $obj[22]="disabled"; $obj[23]="0"; }
if ($_POST['chkpersona']=="1") { $obj[24]="checked";	$obj[25]=""; $obj[26]=$_POST['sltpersona'];  $obj[27]=""; $obj[28]=$_POST['fpersona']; }
else { $obj[24]="";	$obj[25]="disabled"; $obj[26]="0";  $obj[27]="disabled"; $obj[28]=""; }
if ($_POST['chkfingreso']=="1") { $obj[34]="checked"; $obj[35]=""; $obj[36]=$_POST['ffingresod']; $obj[37]=$_POST['ffingresoh']; }
else { $obj[34]=""; $obj[35]="disabled"; $obj[36]=""; $obj[37]=""; }
if ($_POST['chkndoc']=="1") { $obj[38]="checked"; $obj[39]=""; $obj[40]=$_POST['fndoc']; }
else { $obj[38]=""; $obj[39]="disabled"; $obj[40]=""; }
if ($_POST['chkordenar']=="1") { $obj[41]="checked"; $obj[42]=""; $obj[43]=$_POST['fordenar']; }
else { $obj[41]=""; $obj[42]="disabled"; $obj[43]=""; }
if ($_POST['chkcargo']=="1") { $obj[44]="checked"; $obj[45]=""; $obj[46]=$_POST['fcargo']; }
else { $obj[44]=""; $obj[45]="disabled"; $obj[46]="0"; }
//
echo "
<form name='frmentrada' id='frmentrada' method='post' action='pdf_eventos_reposos.php'>
<div class='divBorder' style='width:1000px;'>
<table width='1000' class='tblFiltro'>
	<tr>
		<td width='125' align='right'>Organismo:</td>
		<td>
			<input type='checkbox' name='chkorganismo' id='chkorganismo' value='1' $obj[0] onclick='enabledOrganismo(this.form);' />
			<select name='forganismo' id='forganismo' class='selectBig' $obj[1] onchange='getFOptions_2(this.id, \"fdependencia\", \"chkdependencia\");'>
				<option value=''></option>";
				getOrganismos($obj[2], 3);
				echo "
			</select>
		</td>
		<td width='125' align='right'>Estado Reg.:</td>
		<td>
			<input type='checkbox' name='chkedoreg' value='1' $obj[3] onclick='enabledEdoReg(this.form);' />
			<select name='fedoreg' id='fedoreg' class='select1' $obj[4]>
				<option value=''></option>";
				getStatus($obj[5], 0);
				echo "
			</select>
		</td>
	</tr>
	<tr>
		<td width='125' align='right'>Dependencia:</td>
		<td>
			<input type='checkbox' name='chkdependencia' id='chkdependencia' value='1' $obj[6] onclick='enabledDependencia(this.form);' />";
			echo "
			<select name='fdependencia' id='fdependencia' class='selectBig' $obj[7]>
				<option value=''></option>";
				getDependencias($obj[8], $obj[2], 3);
			echo "
			</select>
		</td>
		<td width='125' align='right'>Situac. Trab.:</td>
		<td>
			<input type='checkbox' name='chksittra' value='1' $obj[10] onclick='enabledSitTra(this.form);' />
			<select name='fsittra' id='fsittra' class='select1' $obj[11]>
				<option value=''></option>";
				getStatus($obj[12], 0);
				echo "
			</select>
		</td>
	</tr>
	<tr>
		<td width='125' align='right'>N&oacute;mina:</td>
		<td>
			<input type='checkbox' name='chktiponom' value='1' $obj[13] onclick='enabledTipoNom(this.form);' />
			<select name='ftiponom' id='ftiponom' class='selectBig' $obj[14]>
				<option value=''></option>";
				getTNomina($obj[15], 0);
				echo "
			</select>
		</td>
		<td width='125' align='right' rowspan='2'>Buscar:</td>
		<td>
			<input type='checkbox' name='chkbuscar' value='1' $obj[16] onclick='enabledBuscar(this.form);' />
			<select name='sltbuscar' id='sltbuscar' class='select1' $obj[17]>
				<option value=''></option>";
				getBuscar($obj[18]);
				echo "
			</select>
		</td>
	</tr>
	<tr>
		<td width='125' align='right'>Perfil N&oacute;mina:</td>
		<td>
			<input type='checkbox' name='chkperfilnom' value='1' $obj[21] onclick='enabledPerfilnomina(this.form);' />
			<select name='fperfilnom' id='fperfilnom' class='select2' $obj[22]>
				<option value=''></option>";
				getPNomina($obj[23], 0);
				echo "
			</select>
		</td>
		<td><input type='text' name='fbuscar' size='50' $obj[19] value='$obj[20]' /></td>
	</tr>
	<tr>
		<td width='125' align='right'>Nro. Documento:</td>
		<td>
			<input type='checkbox' name='chkndoc' value='1' $obj[38] onclick='enabledNDoc(this.form);' />
			<input type='text' name='fndoc' size='50' maxlength='20' $obj[39] value='$obj[40]' />
		</td>
		<td width='125' align='right'>Ordenar por:</td>
		<td>
			<input type='checkbox' name='chkordenar' value='1' $obj[41] onclick='enabledOrdenarPersona(this.form);' />
			<select name='fordenar' id='fordenar' class='select2' $obj[42]>
				<option value=''></option>";
				getOrdenarEmpleado($obj[43], 0);
				echo "
			</select>
		</td>
	</tr>
</table>
<table width='1000' class='tblFiltro'>
	<tr>
		<td width='125' align='right'>Persona:</td>
		<td>
			<input type='checkbox' name='chkpersona' value='1' $obj[24] onclick='enabledPersona(this.form);' />
			<select name='sltpersona' id='sltpersona' class='select3' $obj[25]>";
				getRelacionales($obj[26]);
				echo "
			</select>
			<input type='text' name='fpersona' size='8' maxlength='6' $obj[27] value='$obj[28]' />
		</td>
		<td width='125' align='right'>Fecha de Eventos:</td>
		<td>
			<input type='checkbox' name='chkfingreso' value='1' $obj[34] onclick='enabledFIngreso(this.form);' />
			<input type='text' name='ffingresod' size='15' maxlength='10' $obj[35] value='$obj[36]' /> - 
			<input type='text' name='ffingresoh' size='15' maxlength='10' $obj[35] value='$obj[37]' />
		</td>
	</tr>
</table>
<table width='1000' class='tblFiltro'>
	<tr>
		<td width='125' align='right'>Cargo al cual reporta:</td>
		<td>
			<input type='checkbox' name='chkcargo' value='1' $obj[44] onclick='enabledCargo(this.form);' />
			<select name='fcargo' id='fcargo' class='selectBig' $obj[45]>
				<option value=''></option>";
				getCargosReporta($obj[46], 0);
				echo "
			</select>
		</td>
	</tr>
</table>
</div>
<center><input type='button' name='btBuscar' value='Buscar' onclick='filtroPDFEventosControl(this.form, 0);'></center>
<br /><div class='divDivision'>Reporte de Control de Reposos Médicos</div><br />";
?>



<table width="1000" align="center">
  <tr>
		<td>
			<div id="header">
			<ul>
			<!-- CSS Tabs -->
			
			<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block';" href="#">Reposos Médicos</a></li>
			</ul>
			</div>
		</td>
	</tr>
</table>


<div name="tab1" id="tab1" style="display:none;">
<div style="width:1000px" class="divFormCaption">Control de Asistencia</div>
<center>
<iframe name="iAsistencias" id="iAsistencias" style="border:solid 1px #CDCDCD; width:1000px; height:750px;"></iframe>
</center>
</div>

<div name="tab2" id="tab1" style="display:block;">
<div style="width:1000px" class="divFormCaption">Control de Reposos Médicos</div>
<center>
<iframe name="iPermisos" id="iPermisos" style="border:solid 1px #CDCDCD; width:1000px; height:750px;"></iframe>
</center>
</div>

</form>
</body>
</html>
