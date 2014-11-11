<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
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
		<td class="titulo">Lista de Empleados</td>
		<td align="right"><a class="cerrar"; href="javascript:window.close();">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" action="listado_empleados.php" method="POST" onsubmit="return false">
<input type="hidden" id="limit" name="limit" value="<?=$limit?>">
<input type="hidden" id="cod" name="cod" value="<?=$cod?>">
<input type="hidden" id="nom" name="nom" value="<?=$nom?>">
<input type="hidden" id="id" name="id" value="<?=$id?>">
<input type="hidden" name="ventana" id="ventana" value="<?=$ventana?>" />
<input type="hidden" name="detalles" id="detalles" value="<?=$detalles?>" />
<?
include("fphp.php");
connect();
$MAXLIMIT=30;
if ($filtrar == "DEFAULT") {
	$_POST['chkorganismo'] = "1"; 
	$_POST['chkdependencia'] = "1"; 
	$_POST['chksittra'] = "1";
	$_POST['fsittra'] = "A";
	$_POST['forganismo'] = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; 
	$_POST['fdependencia'] = $_SESSION["FILTRO_DEPENDENCIA_ACTUAL"];
	$filtro = "AND (me.CodOrganismo=*".$_SESSION["FILTRO_ORGANISMO_ACTUAL"]."* AND me.CodDependencia=*".$_SESSION["FILTRO_DEPENDENCIA_ACTUAL"]."* AND me.Estado=*".$_POST['fsittra']."*)";
}
//	VALORES POR DEFECTO Y VALORES SELECCIONADOS DEL FILTRO AL CARGAR O RECARGAR LA PAGINA...............
if ($_POST['chkorganismo']=="1") { $obj[0]="checked"; $obj[1]=""; $obj[2]=$_POST['forganismo']; }
else { $obj[0]=""; $obj[1]="disabled"; $obj[2]=$forganismo; }
if ($_POST['chkedoreg']=="1") { $obj[3]="checked"; $obj[4]=""; $obj[5]=$_POST['fedoreg']; }
else { $obj[3]=""; $obj[4]="disabled"; $obj[5]="0"; }
if ($_POST['chkdependencia']=="1") { $obj[6]="checked"; $obj[7]=""; $obj[8]=$_POST['fdependencia']; $obj[9]=$_POST['forganismo']; }
else { $obj[6]=""; $obj[7]="disabled"; $obj[8]=$fdependencia; $obj[9]="0"; }
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
?>

<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td width="125" align="right">Organismo:</td>
		<td>
			<input type="checkbox" name="chkorganismo" id="chkorganismo" value="1" <?=$obj[0]?> checked onclick="forzarCheck(this.id);" />
			<select name="forganismo" id="forganismo" class="selectBig" onchange="getOptions_2(this.id, 'fdependencia');">
				<?=getOrganismos($obj[2], 3)?>
			</select>
		</td>
		<td width="125" align="right">Estado Reg.:</td>
		<td>
			<input type="checkbox" name="chkedoreg" value="1" <?=$obj[3]?> onclick="enabledEdoReg(this.form);" />
			<select name="fedoreg" id="fedoreg" class="select1" <?=$obj[4]?>>
				<option value=""></option>
				<?=getStatus($obj[5], 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td width="125" align="right">Dependencia:</td>
		<td>
			<input type="checkbox" name="chkdependencia" id="chkdependencia" value="1" <?=$obj[6]?> checked onclick="forzarCheck(this.id);" />
			<select name="fdependencia" id="fdependencia" class="selectBig">
				<?=getDependencias($obj[8], $obj[2], 3)?>
			</select>
		</td>
		<td width="125" align="right">Situac. Trab.:</td>
		<td>
			<input type="checkbox" name="chksittra" value="1" <?=$obj[10]?> onclick="enabledSitTra(this.form);" />
			<select name="fsittra" id="fsittra" class="select1" <?=$obj[11]?>>
				<option value=""></option>
				<?=getStatus($obj[12], 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td width="125" align="right">N&oacute;mina:</td>
		<td>
			<input type="checkbox" name="chktiponom" value="1" <?=$obj[13]?> onclick="enabledTipoNom(this.form);" />
			<select name="ftiponom" id="ftiponom" class="selectBig" <?=$obj[14]?>>
				<option value=""></option>
				<?=getTNomina($obj[15], 0)?>
			</select>
		</td>
		<td width="125" align="right" rowspan="2">Buscar:</td>
		<td>
			<input type="checkbox" name="chkbuscar" value="1" <?=$obj[16]?> onclick="enabledBuscar(this.form);" />
			<select name="sltbuscar" id="sltbuscar" class="select1" <?=$obj[17]?>>
				<option value=""></option>
				<?=getBuscar($obj[18])?>
			</select>
		</td>
	</tr>
	<tr>
		<td width="125" align="right">Tipo Trabajador:</td>
		<td>
			<input type="checkbox" name="chktipotra" value="1" <?=$obj[21]?> onclick="enabledTipoTra(this.form);" />
			<select name="ftipotra" id="ftipotra" class="select2" <?=$obj[22]?>>
				<option value=""></option>
				<?=getTTrabajador($obj[23], 0)?>
			</select>
		</td>
		<td><input type="text" name="fbuscar" size="50" <?=$obj[19]?> value="<?=$obj[20]?>" /></td>
	</tr>
	<tr>
		<td width="125" align="right">Nro. Documento:</td>
		<td>
			<input type="checkbox" name="chkndoc" value="1" <?=$obj[38]?> onclick="enabledNDoc(this.form);" />
			<input type="text" name="fndoc" size="50" maxlength="20" <?=$obj[39]?> value="<?=$obj[40]?>" />
		</td>
		<td width="125" align="right">Ordenar por:</td>
		<td>
			<input type="checkbox" name="chkordenar" value="1" <?=$obj[41]?> onclick="enabledOrdenarPersona(this.form);" />
			<select name="fordenar" id="fordenar" class="select2" <?=$obj[42]?>>
				<option value=""></option>
				<?=getOrdenarEmpleado($obj[43], 0)?>
			</select>
		</td>
	</tr>
</table>
<table width="1000" class="tblFiltro">
	<tr>
		<td width="125" align="right">Persona:</td>
		<td>
			<input type="checkbox" name="chkpersona" value="1" <?=$obj[24]?> onclick="enabledPersona(this.form);" />
			<select name="sltpersona" id="sltpersona" class="select3" <?=$obj[25]?>>
				<?=getRelacionales($obj[26])?>
			</select>
			<input type="text" name="fpersona" size="8" maxlength="6" <?=$obj[27]?> value="<?=$obj[28]?>" />
		</td>
		<td width="125" align="right">Edad:</td>
		<td>
			<input type="checkbox" name="chkedad" value="1" <?=$obj[29]?> onclick="enabledEdad(this.form);" />
			<select name="sltedad" id="sltedad" class="select3" <?=$obj[30]?>>
				<?=getRelacionales($obj[31])?>
			</select>
			<input type="text" name="fedad" size="8" maxlength="3" <?=$obj[32]?> value="<?=$obj[33]?>" />
		</td>
		<td width="125" align="right">Fecha de Ingreso:</td>
		<td>
			<input type="checkbox" name="chkfingreso" value="1" <?=$obj[34]?> onclick="enabledFIngreso(this.form);" />
			<input type="text" name="ffingresod" size="15" maxlength="10" <?=$obj[35]?> value="<?=$obj[36]?>" /> - 
			<input type="text" name="ffingresoh" size="15" maxlength="10" <?=$obj[35]?> value="<?=$obj[37]?>" />
		</td>
	</tr>
</table>
</div>
<center><input type="button" name="btBuscar" value="Buscar" onclick="filtroListadoEmpleados(this.form, <?=$limit?>)"></center>
<br /><div class="divDivision">Lista de Empleados</div><br />

<?
$filtro=strtr($filtro, "*", "'");
$filtro=strtr($filtro, ";", "%");
//	CONSULTO LA TABLA PARA SABER EL TOTAL DE REGISTROS SOLAMENTE............
if ($filtro != "") $filtrado = $filtro; 
else $filtrado = "AND (me.CodOrganismo = '".$forganismo."' AND me.CodDependencia = '".$fdependencia."')";

$sql = "SELECT
			mp.Estado AS EsReg,
			mp.CodPersona,
			mp.NomCompleto,
			mp.Ndocumento,
			me.CodEmpleado,
			me.Fingreso,
			me.Estado AS SitTra,
			md.Dependencia,
			rp.DescripCargo
		FROM
			mastpersonas mp
			INNER JOIN mastempleado me ON (mp.CodPersona = me.CodPersona)
			INNER JOIN rh_puestos rp ON (me.CodCargo = rp.CodCargo)
			LEFT OUTER JOIN mastdependencias md ON (me.CodDependencia = md.CodDependencia)
		WHERE
			(mp.EsEmpleado = 'S') $filtrado";
$query = mysql_query($sql) or die ($sql.mysql_error());
$registros = mysql_num_rows($query);
?>
<table width="910" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td width="250">
			<table align="center">
				<tr>
					<td>
						<input name="btPrimero" type="button" id="btPrimero" value="&lt;&lt;" onclick="setLotes(this.form, 'P', <?=$registros?>, <?=$limit?>);" />
						<input name="btAtras" type="button" id="btAtras" value="&lt;" onclick="setLotes(this.form, 'A', <?=$registros?>, <?=$limit?>);" />
					</td>
					<td>Del</td><td><div id="desde"></div></td>
					<td>Al</td><td><div id="hasta"></div></td>
					<td>
						<input name="btSiguiente" type="button" id="btSiguiente" value="&gt;" onclick="setLotes(this.form, 'S', <?=$registros?>, <?=$limit?>);" />
						<input name="btUltimo" type="button" id="btUltimo" value="&gt;&gt;" onclick="setLotes(this.form, 'U', <?=$registros?>, <?=$limit?>);" />
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="910" class="tblLista">
    <tr class="trListaHead">
        <th width="25" scope="col">Es. Reg.</th>
        <th width="25" scope="col">Sit. Tra.</th>
        <th width="25" scope="col">Fal. Gr.</th>
        <th width="70" scope="col">Persona</th>
        <th scope="col">Nombre Completo</th>
        <th width="75" scope="col">Nro. Documento</th>
        <th width="75" scope="col">Fecha de Ingreso</th>
        <th width="250" scope="col">Dependencia</th>
    </tr>
    <?php 
    if ($registros!=0) {
        //	CONSULTO LA TABLA
        if ($ordenar=="") $ordenar="ORDER BY mp.CodPersona";
		$sql = "SELECT
					mp.Estado AS EsReg,
					mp.CodPersona,
					mp.NomCompleto,
					mp.Ndocumento,
					me.CodEmpleado,
					me.Fingreso,
					me.Estado AS SitTra,
					md.Dependencia,
					rp.DescripCargo
				FROM
					mastpersonas mp
					INNER JOIN mastempleado me ON (mp.CodPersona = me.CodPersona)
					INNER JOIN rh_puestos rp ON (me.CodCargo = rp.CodCargo)
					LEFT OUTER JOIN mastdependencias md ON (me.CodDependencia = md.CodDependencia)
				WHERE
					(mp.EsEmpleado = 'S') $filtrado
				 ".$ordenar." 
				 LIMIT ".$limit.", $MAXLIMIT";
        $query = mysql_query($sql) or die ($sql.mysql_error());
        $rows = mysql_num_rows($query);
        //	MUESTRO LA TABLA
        for ($i=0; $i<$rows; $i++) {
            $field = mysql_fetch_array($query);
            list($a, $m, $d)=SPLIT( '[/.-]', $field['Fingreso']); $fingreso=$d."-".$m."-".$a;
			
			if ($ventana == "selListadoEmpleadoCargo") {
				?>
				<tr class="trListaBody" onclick="mClk(this, 'registro'); selListadoEmpleadoCargo('<?=$field["NomCompleto"]?>', '<?=$cod?>', '<?=$nom?>', '<?=$id?>', '<?=$field['CodPersona']?>', '<?=$field['DescripCargo']?>');" id="<?=$field["CodPersona"]?>">
					<td align="center"><?=$field["EsReg"]?></td>
					<td align="center"><?=$field["SitTra"]?></td>
					<td align="center">&nbsp;</td>
					<td align="center"><?=$field["CodEmpleado"]?></td>
					<td><?=htmlentities($field["NomCompleto"])?></td>
					<td><?=$field["Ndocumento"]?></td>
					<td align="center"><?=$fingreso?></td>
					<td><?=htmlentities($field["Dependencia"])?></td>
				</tr>
				<?
			} 
			elseif ($ventana == "selEmpleadoFideicomiso") {
				list($anos, $meses, $dias) = getEdadAMD(formatFechaDMA($field['Fingreso']), date("d-m-Y"));
				?>
				<tr class="trListaBody" onclick="mClk(this, 'registro'); selEmpleadoFideicomiso('<?=$field['CodPersona']?>', '<?=$field["CodEmpleado"]?>', '<?=$field["NomCompleto"]?>', '<?=$field["Ndocumento"]?>', '<?=$anos?>', '<?=$meses?>', '<?=$dias?>', '<?=formatFechaDMA($field["Fingreso"])?>');" id="<?=$field["CodPersona"]?>">
					<td align="center"><?=$field["EsReg"]?></td>
					<td align="center"><?=$field["SitTra"]?></td>
					<td align="center">&nbsp;</td>
					<td align="center"><?=$field["CodEmpleado"]?></td>
					<td><?=htmlentities($field["NomCompleto"])?></td>
					<td><?=$field["Ndocumento"]?></td>
					<td align="center"><?=$fingreso?></td>
					<td><?=htmlentities($field["Dependencia"])?></td>
				</tr>
				<?
			} else {	
				?>
				<tr class="trListaBody" onclick="mClk(this, 'registro'); selListado('<?=$field["NomCompleto"]?>', '<?=$cod?>', '<?=$nom?>', '<?=$id?>', '<?=$field['CodPersona']?>');" id="<?=$field["CodPersona"]?>">
					<td align="center"><?=$field["EsReg"]?></td>
					<td align="center"><?=$field["SitTra"]?></td>
					<td align="center">&nbsp;</td>
					<td align="center"><?=$field["CodEmpleado"]?></td>
					<td><?=htmlentities($field["NomCompleto"])?></td>
					<td><?=$field["Ndocumento"]?></td>
					<td align="center"><?=$fingreso?></td>
					<td><?=htmlentities($field["Dependencia"])?></td>
				</tr>
				<?
			}
        }
    }
    $rows=(int)$rows;
    ?>
</table>
</form>
<script type="text/javascript" language="javascript">
	totalLista(<?=$rows?>);
	totalLotes(<?=$registros?>, <?=$rows?>, <?=$limit?>);
</script>
</body>
</html>