<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('02', $concepto);
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
		<td class="titulo">Reporte de Empleados</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
$MAXLIMIT=30;
if ($filtrar=="DEFAULT") {
	$_POST['chkorganismo']="1"; 
	$_POST['chkdependencia']="1"; 
	$_POST['forganismo']=$_SESSION["FILTRO_ORGANISMO_ACTUAL"]; 
	$_POST['fdependencia']=$_SESSION["FILTRO_DEPENDENCIA_ACTUAL"];
	$_GET['filtro']="AND (me.CodOrganismo=*".$_SESSION["FILTRO_ORGANISMO_ACTUAL"]."* AND me.CodDependencia=*".$_SESSION["FILTRO_DEPENDENCIA_ACTUAL"]."*)";
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
if ($_POST['chktipotra']=="1") { $obj[21]="checked"; $obj[22]=""; $obj[23]=$_POST['ftipotra']; }
else { $obj[21]=""; $obj[22]="disabled"; $obj[23]="0"; }
if ($_POST['chkpersona']=="1") { $obj[24]="checked";	$obj[25]=""; $obj[26]=$_POST['sltpersona'];  $obj[27]=""; $obj[28]=$_POST['fpersona']; }
else { $obj[24]="";	$obj[25]="disabled"; $obj[26]="0";  $obj[27]="disabled"; $obj[28]=""; }
if ($_POST['chkedad']=="1") { $obj[29]="checked";	$obj[30]=""; $obj[31]=$_POST['sltedad'];  $obj[32]=""; $obj[33]=$_POST['fedad']; }
else { $obj[29]="";	$obj[30]="disabled"; $obj[31]="0";  $obj[32]="disabled"; $obj[33]=""; }
if ($_POST['chkfingreso']=="1") { $obj[34]="checked"; $obj[35]=""; $obj[36]=$_POST['ffingresod']; $obj[37]=$_POST['ffingresoh']; }
else { $obj[34]=""; $obj[35]="disabled"; $obj[36]=""; $obj[37]=""; }
if ($_POST['chkndoc']=="1") { $obj[38]="checked"; $obj[39]=""; $obj[40]=$_POST['fndoc']; }
else { $obj[38]=""; $obj[39]="disabled"; $obj[40]=""; }
if ($_POST['chkordenar']=="1") { $obj[41]="checked"; $obj[42]=""; $obj[43]=$_POST['fordenar']; }
else { $obj[41]=""; $obj[42]="disabled"; $obj[43]=""; }
//

if ($_POST['chkprofesion']=="1") { $obj[44]="checked"; $obj[45]=""; $obj[46]=$_POST['fprofesion']; }
else { $obj[44]=""; $obj[45]="disabled"; $obj[46]=""; }

echo "
<form name='frmentrada' id='frmentrada' method='post' action='pdf_empleados.php' target='iPDF'>
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
		<td width='125' align='right'>Tipo Trabajador:</td>
		<td>
			<input type='checkbox' name='chktipotra' value='1' $obj[21] onclick='enabledTipoTra(this.form);' />
			<select name='ftipotra' id='ftipotra' class='select2' $obj[22]>
				<option value=''></option>";
				getTTrabajador($obj[23], 0);
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
	
	<tr>
		
		<td width='125' align='right'>Profesion:</td>
		<td>
			<input type='checkbox' name='chkprofesion' value='1' $obj[44] onclick='enabledGInstrucc2(this.form);' />
			<select name='fprofesion' id='fprofesion' class='select2' $obj[45]>
				<option value=''></option>";
				getGInstruccion($obj[45], 0);
			
			  
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
		<td width='125' align='right'>Edad:</td>
		<td>
			<input type='checkbox' name='chkedad' value='1' $obj[29] onclick='enabledEdad(this.form);' />
			<select name='sltedad' id='sltedad' class='select3' $obj[30]>";
				getRelacionales($obj[31]);
				echo "
			</select>
			<input type='text' name='fedad' size='8' maxlength='3' $obj[32] value='$obj[33]' />
		</td>
		<td width='125' align='right'>Fecha de Ingreso:</td>
		<td>
			<input type='checkbox' name='chkfingreso' value='1' $obj[34] onclick='enabledFIngreso(this.form);' />
			<input type='text' name='ffingresod' size='15' maxlength='10' $obj[35] value='$obj[36]' /> - 
			<input type='text' name='ffingresoh' size='15' maxlength='10' $obj[35] value='$obj[37]' />
		</td>
	</tr>
</table>
</div>
<center><input type='button' name='btBuscar' value='Buscar' onclick='filtroPDFEmpleados(this.form, 0);'></center>
<br /><div class='divDivision'>Reporte de Empleados</div><br />";
?>

<center>
<iframe name="iPDF" id="iPDF" style="border:solid 1px #CDCDCD; width:1000px; height:750px;"></iframe>
</center>

</form>
</body>
</html>
