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
<script type="text/javascript" language="javascript" src="fscript_2.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Constancias de Trabajo</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<form name="frmentrada" action="reportes_constancias.php?filtro=<?=$_GET["filtro"]?>" method="POST" onsubmit="return false">

<?php
$MAXLIMIT=30;
if ($filtrar=="DEFAULT") {
	$_POST['chkorganismo']="1"; 
	$_POST['chkdependencia']="1"; 
	$_POST['forganismo']=$_SESSION["FILTRO_ORGANISMO_ACTUAL"]; 
	$_POST['fdependencia']=$_SESSION["FILTRO_DEPENDENCIA_ACTUAL"];
	$_GET['filtro']="AND (me.CodOrganismo=*".$_SESSION["FILTRO_ORGANISMO_ACTUAL"]."* AND me.CodDependencia=*".$_SESSION["FILTRO_DEPENDENCIA_ACTUAL"]."*)";
}
//	ELIMINO EL REGISTRO
if ($_GET['accion']=="ELIMINAR") {
	$sql="DELETE FROM mastempleado WHERE CodPersona='".$_POST['registro']."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	//
	$sql="DELETE FROM mastpersonas WHERE CodPersona='".$_POST['registro']."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
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
echo "
<input type='hidden' name='limit' value='".$_GET['limit']."'>
<input type='hidden' name='filtro' value='".$_GET['filtro']."'>
<input type='hidden' name='ordenar' value='".$_GET['ordenar']."'>
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
<center><input type='button' name='btBuscar' value='Buscar' onclick='filtroPDFConstancias(this.form, ".$_GET['limit'].");'></center>
<br /><div class='divDivision'>Lista de Empleados</div><br />";

$_GET['filtro']=strtr($_GET['filtro'], "*", "'");
$_GET['filtro']=strtr($_GET['filtro'], ";", "%");
//	CONSULTO LA TABLA PARA SABER EL TOTAL DE REGISTROS SOLAMENTE............
if ($_GET['filtro']!="") $filtrado=$_GET['filtro'];
$sql="SELECT mp.Estado AS EsReg, mp.CodPersona, mp.NomCompleto, mp.Ndocumento, me.CodEmpleado, me.Fingreso, me.Estado AS SitTra, md.Dependencia, rmc.FlagFaltaGrave FROM mastpersonas mp INNER JOIN mastempleado me ON (mp.CodPersona=me.CodPersona) INNER JOIN mastdependencias md ON (me.CodDependencia=md.CodDependencia) INNER JOIN seguridad_alterna sa ON (me.CodDependencia=sa.CodDependencia AND sa.CodAplicacion='".$_SESSION["APLICACION_ACTUAL"]."' AND sa.Usuario='".$_SESSION["USUARIO_ACTUAL"]."' AND sa.FlagMostrar='S') LEFT JOIN rh_motivocese rmc ON (me.CodMotivoCes=rmc.CodMotivoCes) WHERE (mp.EsEmpleado='S') $filtrado";
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
?>

<table width="1000" class="tblBotones">
	<tr>
    	<td><div id="rows"></div></td>
        <td align="center">
            <input name="btPrimero" type="button" id="btPrimero" value="&lt;&lt;" onclick="setLotes(this.form, 'P', <?=$registros?>, <?=$_GET["limit"]?>, '<?=$_GET["ordenar"]?>');" />
            <input name="btAtras" type="button" id="btAtras" value="&lt;" onclick="setLotes(this.form, 'A', <?=$registros?>, <?=$_GET["limit"]?>, '<?=$_GET["ordenar"]?>');" />
        </td>
        <td>Del</td><td><div id="desde"></div></td>
        <td>Al</td><td><div id="hasta"></div></td>
        <td align="center">
            <input name="btSiguiente" type="button" id="btSiguiente" value="&gt;" onclick="setLotes(this.form, 'S', <?=$registros?>, <?=$_GET["limit"]?>, '<?=$_GET["ordenar"]?>');" />
            <input name="btUltimo" type="button" id="btUltimo" value="&gt;&gt;" onclick="setLotes(this.form, 'U', <?=$registros?>, <?=$_GET["limit"]?>, '<?=$_GET["ordenar"]?>');" />
        </td>
        <td align="right" width="400">
            <input name="btAbrir" type="button" id="btAbrir" class="btLista" value="Abrir" onclick="abrirConstancia(this.form, 'constancias_abrir.php', 'height=500, width=700, left=100, top=100, resizable=no');" />
        </td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="1000" class="tblLista">
    <tr class="trListaHead">
		<th width="25" scope="col">Sel.</th>
        <th width="25" scope="col">Es. Reg.</th>
        <th width="25" scope="col">Sit. Tra.</th>
        <th width="25" scope="col">Fal. Gr.</th>
        <th width="70" scope="col">C&oacute;digo</th>
        <th scope="col">Nombre Completo</th>
        <th width="75" scope="col">Nro. Documento</th>
        <th width="75" scope="col">Fecha de Ingreso</th>
        <th width="250" scope="col">Dependencia</th>
    </tr>
	<?php 
    if ($registros!=0) {
		//	CONSULTO LA TABLA
		if ($_GET['ordenar']=="") $_GET['ordenar']="ORDER BY mp.CodPersona";
		$sql="SELECT mp.Estado AS EsReg, mp.CodPersona, mp.NomCompleto, mp.Ndocumento, me.CodEmpleado, me.Fingreso, me.Estado AS SitTra, md.Dependencia, rmc.FlagFaltaGrave FROM mastpersonas mp INNER JOIN mastempleado me ON (mp.CodPersona=me.CodPersona) INNER JOIN mastdependencias md ON (me.CodDependencia=md.CodDependencia) INNER JOIN seguridad_alterna sa ON (me.CodDependencia=sa.CodDependencia AND sa.CodAplicacion='".$_SESSION["APLICACION_ACTUAL"]."' AND sa.Usuario='".$_SESSION["USUARIO_ACTUAL"]."' AND sa.FlagMostrar='S') LEFT JOIN rh_motivocese rmc ON (me.CodMotivoCes=rmc.CodMotivoCes) WHERE (mp.EsEmpleado='S') $filtrado ".$_GET['ordenar']." LIMIT ".$_GET['limit'].", $MAXLIMIT";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//	MUESTRO LA TABLA
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			list($a, $m, $d)=SPLIT( '[/.-]', $field['Fingreso']); $fingreso=$d."-".$m."-".$a;
			if ($field['EsReg']=="A") $srcesreg="imagenes/arriba.png"; 
			elseif ($field['EsReg']=="I") $srcesreg="imagenes/abajo.png";
			if ($field['SitTra']=="A") $srcsittra="imagenes/arriba.png"; 
			elseif ($field['SitTra']=="I") $srcsittra="imagenes/abajo.png";
			if ($field['FlagFaltaGrave']=="S") $srcflag="imagenes/warning.png"; 
			else $srcflag="";
			echo "
			<tr class='trListaBody' onclick='mClk(this, \"registro\");' id='".$field['CodPersona']."'>
				<td align='center'><input type='checkbox' name='sel' value='".$field['CodPersona']."' id='s_".$field['CodPersona']."' /> </td>
				<td align='center'><img src='".$srcesreg."' height='16' width='16' /></td>
				<td align='center'><img src='".$srcsittra."' height='16' width='16' /></td>
				<td align='center'><img src='".$srcflag."' height='20' width='20' /></td>
				<td align='center'>".$field['CodPersona']."</td>
				<td>".htmlentities($field['NomCompleto'])."</td>
				<td>".$field['Ndocumento']."</td>
				<td align='center'>".$fingreso."</td>
				<td>".htmlentities($field['Dependencia'])."</td>
			</tr>";
    	}
    }
    $rows=(int)$rows;
    echo "
    <script type='text/javascript' language='javascript'>
		totalConstancias($registros, \"$_ADMIN\", \"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
		totalLotes($registros, $rows, ".$_GET['limit'].");
    </script>";				
    ?>
</table>
</form>
</body>
</html>