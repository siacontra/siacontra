<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
//	------------------------------------
if ($filtrar == "default") {
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fdependencia = $_SESSION["FILTRO_DEPENDENCIA_ACTUAL"];
	$fedoreg = "A";
	$fsittra = "A";
	$fordenar = "e.CodEmpleado";
}
$filtro = "";
if ($forganismo != "") { $corganismo = "checked"; $filtro .= " AND e.CodOrganismo = '".$forganismo."'"; } else $dorganismo = "disabled";
if ($fedoreg != "") { $cedoreg = "checked"; $filtro .= " AND mp.Estado = '".$fedoreg."'"; } else $dedoreg = "disabled";
if ($fdependencia != "") { $cdependencia = "checked"; $filtro .= " AND e.CodDependencia = '".$fdependencia."'"; } else $ddependencia = "disabled";
if ($fsittra != "") { $csittra = "checked"; $filtro .= " AND e.Estado = '".$fsittra."'"; } else $dsittra = "disabled";
if ($ftiponom != "") { $ctiponom = "checked"; $filtro .= " AND e.CodTipoNom = '".$ftiponom."'"; } else $dtiponom = "disabled";
if ($fbuscar != "") {
	$cbuscar = "checked";
	if ($sltbuscar == "") $filtro.=" AND (mp.Apellido1 LIKE '%".($fbuscar)."%' OR
										  mp.Apellido2 LIKE '%".($fbuscar)."%' OR
										  mp.Nombres LIKE '%".($fbuscar)."%' OR
										  mp.Busqueda LIKE '%".($fbuscar)."%' OR
										  mp.NomCompleto LIKE '%".($fbuscar)."%')";
	else $filtro.=" AND $sltbuscar LIKE '%".($fbuscar)."%'";
} else { $dbuscar = "disabled"; $sltbuscar=""; }
if ($ftipotra != "") { $ctipotra = "checked"; $filtro .= " AND e.CodTipoTrabajador = '".$ftipotra."'"; } else $dtipotra = "disabled";
if ($fndoc != "") { $cndoc = "checked"; $filtro .= " AND mp.Ndocumento = '".$fndoc."'"; } else $dndoc = "disabled";
if ($fpersona != "") { $cpersona = "checked"; $filtro .= " AND e.CodEmpleado $sltpersona '".$fpersona."'"; } else $dpersona = "disabled";
if ($fedad != "") {
	$fedad = "checked";
	list($fini, $ffin) = getFechasTiempo($fedad);
	if ($sltedad == "=") $filtro .= " AND (e.Fingreso >= '".$fini."' AND e.Fingreso <= '".$ffin."')";
	elseif ($sltedad == ">") $filtro .= " AND (e.Fingreso < '".$fini."')";
	elseif ($sltedad == "<") $filtro .= " AND (e.Fingreso > '".$ffin."')";
	elseif ($sltedad == ">=") $filtro .= " AND (e.Fingreso <= '".$ffin."')";
	elseif ($sltedad == "<=") $filtro .= " AND (e.Fingreso >= '".$finicio."')";
	elseif ($sltedad == "<>") $filtro .= " AND (e.Fingreso < '".$fini."' AND e.Fingreso > '".$ffin."')";
} else $dedad = "disabled";
if ($fingresod != "" || $fingresoh != "") {
	$cfingreso = "checked";
	list($d, $m, $a) = split("[/.-]", $fingresod); $desde = "$a-$m-$d";
	list($d, $m, $a) = split("[/.-]", $fingresoh); $hasta = "$a-$m-$d";
	if ($fingresod != "") $filtro .= " AND (e.Fingreso >= '".$desde."')";
	if ($fingresoh != "") $filtro .= " AND (e.Fingreso <= '".$hasta."')";
} else $dfingreso = "disabled";
if ($fordenar != "") $cordenar = "checked"; else $dordenar = "disabled";
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/fscript.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Listado de Empleados</td>
		<td align="right"><a class="cerrar" href="#" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="listado_empleados.php" method="POST">
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="php" id="php" value="<?=$php?>" />
<input type="hidden" name="ventana" id="ventana" value="<?=$ventana?>" />
<input type="hidden" name="codempleado" id="codempleado" value="<?=$codempleado?>" />

<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td width="125" align="right">Organismo:</td>
		<td>
			<input type="checkbox" <?=$corganismo?> onclick="this.checked=!this.checked;" />
			<select name="forganismo" id="forganismo" <?=$dorganismo?> style="width:300px;" onchange="getFOptions_2(this.id, 'fdependencia', 'chkdependencia');">
				<?=getOrganismos($forganismo, 3);?>
			</select>
		</td>
		<td width="125" align="right">Estado Reg.:</td>
		<td>
			<input type="checkbox" <?=$cedoreg?> onclick="chkFiltro(this.checked, 'fedoreg')" />
			<select name="fedoreg" id="fedoreg" style="width:75px;" <?=$dedoreg?>>
				<option value=""></option>
				<?=loadSelectGeneral("ESTADO", $fedoreg, 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Dependencia:</td>
		<td>
			<input type="checkbox" <?=$cdependencia?> onclick="chkFiltro(this.checked, 'fdependencia')" />
			<select name="fdependencia" id="fdependencia" style="width:300px;" <?=$ddependencia?>>
				<option value=""></option>
				<?=getDependencias($fdependencia, $forganismo, 3);?>
			</select>
		</td>
		<td align="right">Situac. Trab.:</td>
		<td>
			<input type="checkbox" <?=$csittra?> onclick="chkFiltro(this.checked, 'fsittra')" />
			<select name="fsittra" id="fsittra" style="width:75px;" <?=$dsittra?>>
				<option value=""></option>
				<?=loadSelectGeneral("ESTADO", $fsittra, 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">N&oacute;mina:</td>
		<td>
			<input type="checkbox" <?=$ctiponom?> onclick="chkFiltro(this.checked, 'ftiponom')" />
			<select name="ftiponom" id="ftiponom" style="width:300px;" <?=$dtiponom?>>
				<option value=""></option>
				<?=loadSelect("tiponomina", "CodTipoNom", "Nomina", $ftiponom, 0)?>
			</select>
		</td>
		<td align="right">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cbuscar?> onclick="chkFiltro_2(this.checked, 'sltbuscar', 'fbuscar')" />
			<select name="sltbuscar" id="sltbuscar" style="width:150px;" <?=$dbuscar?>>
				<option value=""></option>
				<?=loadSelectGeneral("BUSCAR-EMPLEADOS", $sltbuscar, 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Tipo Trabajador:</td>
		<td>
			<input type="checkbox" <?=$ctipotra?> onclick="chkFiltro(this.checked, 'ftipotra')" />
			<select name="ftipotra" id="ftipotra" style="width:300px;" <?=$dtipotra?>>
				<option value=""></option>
				<?=loadSelect("rh_tipotrabajador", "CodTipoTrabajador", "TipoTrabajador", $ftipotra, 0)?>
			</select>
		</td>
        <td>&nbsp;</td>
		<td>
        	<input type="checkbox" style="visibility:hidden" />
        	<input type="text" name="fbuscar" id="fbuscar" style="width:150px;" <?=$dbuscar?> value="<?=$fbuscar?>" />
        </td>
	</tr>
	<tr>
		<td align="right">Nro. Documento:</td>
		<td>
			<input type="checkbox" <?=$cndoc?> onclick="chkFiltro(this.checked, 'fndoc')" />
			<input type="text" name="fndoc" id="fndoc" maxlength="20" style="width:70px;" value="<?=$fndoc?>" <?=$dndoc?> />
		</td>
		<td align="right">Ordenar por:</td>
		<td>
			<input type="checkbox" <?=$cordenar?> onclick="this.checked=!this.checked" />
			<select name="fordenar" id="fordenar" <?=$dordenar?>>
				<?=loadSelectGeneral("ORDENAR-EMPLEADOS", $fordenar, 0)?>
			</select>
		</td>
	</tr>
</table>
<table width="1000" class="tblFiltro">
	<tr>
		<td width="125" align="right">Empleado:</td>
		<td>
			<input type="checkbox" <?=$cpersona?> onclick="chkFiltro_2(this.checked, 'sltpersona', 'fpersona')" />
			<select name="sltpersona" id="sltpersona" style="width:15px;" <?=$dpersona?>>
				<?=loadSelectGeneral("COMPARATIVOS", $sltpersona, 0)?>
			</select>
			<input type="text" name="fpersona" id="fpersona" maxlength="6" style="width:50px;" value="<?=$fpersona?>" <?=$dpersona?> />
		</td>
		<td width="125" align="right">Edad:</td>
		<td>
			<input type="checkbox" <?=$cedad?> onclick="chkFiltro_2(this.checked, 'sltedad', 'fedad')" />
			<select name="sltedad" id="sltedad" style="width:15px;" <?=$dedad?>>
				<?=loadSelectGeneral("COMPARATIVOS", $sltedad, 0)?>
			</select>
			<input type="text" name="fedad" id="fedad" maxlength="3" style="width:50px;" value="<?=$fedad?>" <?=$dedad?> />
		</td>
		<td width="125" align="right">Fecha de Ingreso:</td>
		<td>
			<input type="checkbox" <?=$cfingreso?> onclick="chkFiltro_2(this.checked, 'ffingresod', 'ffingresoh')" />
			<input type="text" name="ffingresod" id="ffingresod" maxlength="10" style="width:60px;" value="<?=$ffingresod?>" <?=$dfingreso?> /> - 
			<input type="text" name="ffingresoh" id="ffingresoh" maxlength="10" style="width:60px;" value="<?=$ffingresoh?>" <?=$dfingreso?> />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center>
<br />

<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
	</tr>
</table>

<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:500px;">
<table width="100%" class="tblLista">
	<thead>
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
    </thead>
    
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT
				mp.Estado AS EsReg,
				mp.CodPersona,
				mp.NomCompleto,
				mp.Ndocumento,
				e.CodEmpleado,
				e.Fingreso,
				e.Estado AS SitTra,
				md.Dependencia,
				rp.DescripCargo
			FROM
				mastpersonas mp
				INNER JOIN mastempleado e ON (mp.CodPersona = e.CodPersona)
				INNER JOIN rh_puestos rp ON (e.CodCargo = rp.CodCargo)
				INNER JOIN mastdependencias md ON (e.CodDependencia = md.CodDependencia)
			WHERE 1 $filtro
			ORDER BY $fordenar";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		if ($ventana == "fideicomiso_calculo") {
			?>
			<tr class="trListaBody" onclick="fideicomiso_calculo('<?=$field['CodPersona']?>', '<?=$php?>');" id="<?=$field['CodPersona']?>">
				<td align="center"><?=printFlagEstado($field["EsReg"])?></td>
				<td align="center"><?=printFlagEstado($field["SitTra"])?></td>
				<td align="center">&nbsp;</td>
				<td align="center"><?=$field["CodEmpleado"]?></td>
				<td><?=($field["NomCompleto"])?></td>
				<td><?=$field["Ndocumento"]?></td>
				<td align="center"><?=formatFechaDMA($field['Fingreso'])?></td>
				<td><?=($field["Dependencia"])?></td>
			</tr>
			<?
		} else {
			?>
			<tr class="trListaBody" onclick="selListado('<?=$field['CodPersona']?>', '<?=($field["NomCompleto"])?>', '<?=$cod?>', '<?=$nom?>', '<?=$field['CodEmpleado']?>', '<?=$codempleado?>');" id="<?=$field['CodPersona']?>">
				<td align="center"><?=printFlagEstado($field["EsReg"])?></td>
				<td align="center"><?=printFlagEstado($field["SitTra"])?></td>
				<td align="center">&nbsp;</td>
				<td align="center"><?=$field["CodEmpleado"]?></td>
				<td><?=($field["NomCompleto"])?></td>
				<td><?=$field["Ndocumento"]?></td>
				<td align="center"><?=formatFechaDMA($field['Fingreso'])?></td>
				<td><?=($field["Dependencia"])?></td>
			</tr>
			<?
		}
	}
	?>
</table>
</div></td></tr></table>
</form>
<script type="text/javascript" language="javascript">
	totalRegistros(parseInt(<?=$rows?>));
</script>
</body>
</html>