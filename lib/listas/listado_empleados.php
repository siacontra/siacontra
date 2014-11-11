<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../../index.php");
//	------------------------------------
include("../fphp.php");
//	------------------------------------
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
$FechaActual = "$AnioActual-$MesActual-$DiaActual";
//	------------------------------------
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fCodDependencia = $_SESSION["DEPENDENCIA_ACTUAL"];
	$fEdoReg = "A";
	$fSitTra = "A";
	$fOrderBy = "CodEmpleado";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fCodOrganismo != "") { $cCodOrganismo = "checked"; $filtro.=" AND (e.CodOrganismo = '".$fCodOrganismo."')"; } else $dCodOrganismo = "disabled";
if ($fCodDependencia != "") { $cCodDependencia = "checked"; $filtro.=" AND (e.CodDependencia = '".$fCodDependencia."')"; } else $dCodDependencia = "disabled";
if ($fCodCentroCosto != "") { $cCodCentroCosto = "checked"; $filtro.=" AND (e.CodCentroCosto = '".$fCodCentroCosto."')"; } else $dCodCentroCosto = "disabled";
if ($fCodTipoNom != "") { $cCodTipoNom = "checked"; $filtro.=" AND (e.CodTipoNom = '".$fCodTipoNom."')"; } else $dCodTipoNom = "disabled";
if ($fCodTipoTrabajador != "") { $cCodTipoTrabajador = "checked"; $filtro.=" AND (e.CodTipoTrabajador = '".$fCodTipoTrabajador."')"; } else $dCodTipoTrabajador = "disabled";
if ($fEdoReg != "") { $cEdoReg = "checked"; $filtro.=" AND (p.Estado = '".$fEdoReg."')"; } else $dEdoReg = "disabled";
if ($fSitTra != "") { $cSitTra = "checked"; $filtro.=" AND (e.Estado = '".$fSitTra."')"; } else $dSitTra = "disabled";
if ($fFingresoD != "" || $fFingresoH != "") {
	$cFingreso = "checked";
	if ($fFingresoD != "") $filtro.=" AND (e.Fingreso >= '".formatFechaAMD($fFingresoD)."')";
	if ($fFingresoH != "") $filtro.=" AND (e.Fingreso <= '".formatFechaAMD($fFingresoH)."')";
} else $dFingreso = "disabled";
if ($fBuscar != "") {
	$cBuscar = "checked";
	$filtro .= " AND (e.CodEmpleado LIKE '%".$fBuscar."%' OR
					  p.NomCompleto LIKE '%".$fBuscar."%' OR
					  p.Ndocumento LIKE '%".$fBuscar."%' OR
					  -- pt.DescripCargo LIKE '%".$fBuscar."%' OR
					  d.Dependencia LIKE '%".$fBuscar."%')";
} else $dBuscar = "disabled";
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<link type="text/css" rel="stylesheet" href="../../css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../../css/estilo.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" src="../../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/fscript.js" charset="utf-8"></script>
</head>

<body>
<!-- ui-dialog -->
<div id="cajaModal"></div>

<form name="frmentrada" id="frmentrada" action="listado_empleados.php?" method="post">
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="ventana" id="ventana" value="<?=$ventana?>" />
<input type="hidden" name="campo3" id="campo3" value="<?=$campo3?>" />
<input type="hidden" name="campo4" id="campo4" value="<?=$campo4?>" />
<input type="hidden" name="detalle" id="detalle" value="<?=$detalle?>" />
<input type="hidden" name="marco" id="marco" value="<?=$marco?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<div class="divBorder" style="width:100%;">
<table width="100%" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
			<input type="checkbox" <?=$cCodOrganismo?> onclick="this.checked=!this.checked" />
			<select name="fCodOrganismo" id="fCodOrganismo" style="width:300px;" <?=$dCodOrganismo?> onChange="getOptionsSelect(this.value, 'dependencia_filtro', 'fCodDependencia', true, 'fCodCentroCosto');">
				<?=getOrganismos($fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right" width="125">Edo. Reg: </td>
		<td>
        	<input type="checkbox" <?=$cEdoReg?> onclick="chkFiltro(this.checked, 'fEdoReg');" />
            <select name="fEdoReg" id="fEdoReg" style="width:143px;" <?=$dEdoReg?>>
                <option value=""></option>
                <?=loadSelectGeneral("ESTADO", $fEdoReg, 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td align="right">Dependencia:</td>
		<td>
			<input type="checkbox" <?=$cCodDependencia?> onclick="chkFiltro(this.checked, 'fCodDependencia');" />
			<select name="fCodDependencia" id="fCodDependencia" style="width:300px;" onChange="getOptionsSelect(this.value, 'centro_costo', 'fCodCentroCosto', true);" <?=$dCodDependencia?>>
            	<option value="">&nbsp;</option>
				<?=getDependencias($fCodDependencia, $fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right">Sit. Tra.: </td>
		<td>
        	<input type="checkbox" <?=$cSitTra?> onclick="chkFiltro(this.checked, 'fSitTra');" />
            <select name="fSitTra" id="fSitTra" style="width:143px;" <?=$dSitTra?>>
                <option value=""></option>
                <?=loadSelectGeneral("ESTADO", $fSitTra, 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td align="right">Centro de Costo:</td>
		<td>
			<input type="checkbox" <?=$cCodCentroCosto?> onclick="chkFiltro(this.checked, 'fCodCentroCosto');" />
			<select name="fCodCentroCosto" id="fCodCentroCosto" style="width:300px;" <?=$dCodCentroCosto?>>
            	<option value="">&nbsp;</option>
				<?=loadSelect("ac_mastcentrocosto", "CodCentroCosto", "Descripcion", $fCodCentroCosto, 0)?>
			</select>
		</td>
		<td align="right">Fecha de Ingreso: </td>
		<td>
			<input type="checkbox" <?=$cFingreso?> onclick="chkFiltro_2(this.checked, 'fFingresoD', 'fFingresoH');" />
			<input type="text" name="fFingresoD" id="fFingresoD" value="<?=$fFingresoD?>" <?=$dFingreso?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFecha(this);" /> -
            <input type="text" name="fFingresoH" id="fFingresoH" value="<?=$fFingresoH?>" <?=$dFingreso?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFecha(this);" />
        </td>
	</tr>
	<tr>
		<td align="right">Tipo de Nomina:</td>
		<td>
        	<?php
			if ($FlagNomina == "S") {
				?>
                <input type="checkbox" <?=$cCodTipoNom?> onclick="this.checked=!this.checked;" />
                <select name="fCodTipoNom" id="fCodTipoNom" style="width:300px;" <?=$dCodTipoNom?>>
                    <?=loadSelect("tiponomina", "CodTipoNom", "Nomina", $fCodTipoNom, 1)?>
                </select>
                <?
			} else {
				?>
                <input type="checkbox" <?=$cCodTipoNom?> onclick="chkFiltro(this.checked, 'fCodTipoNom');" />
                <select name="fCodTipoNom" id="fCodTipoNom" style="width:300px;" <?=$dCodTipoNom?>>
                    <option value="">&nbsp;</option>
                    <?=loadSelect("tiponomina", "CodTipoNom", "Nomina", $fCodTipoNom, 0)?>
                </select>
                <?
			}
			?>
		</td>
		<td align="right">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fBuscar');" />
			<input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:250px;" <?=$dBuscar?> />
		</td>
	</tr>
	<tr>
		<td align="right">Tipo de Trabajador:</td>
		<td>
			<input type="checkbox" <?=$cCodTipoTrabajador?> onclick="chkFiltro(this.checked, 'fCodTipoTrabajador');" />
			<select name="fCodTipoTrabajador" id="fCodTipoTrabajador" style="width:300px;" <?=$dCodTipoTrabajador?>>
            	<option value="">&nbsp;</option>
				<?=loadSelect("rh_tipotrabajador", "CodTipoTrabajador", "TipoTrabajador", $fCodTipoTrabajador, 0)?>
			</select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="100%" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
	</tr>
</table>

<div style="overflow:scroll; width:100%; height:250px;">
<table width="1700" class="tblLista">
	<thead>
	<tr>
        <th scope="col" width="60" onclick="order('CodEmpleado')"><a href="javascript:">C&oacute;digo</a></th>
        <th scope="col" width="400" onclick="order('NomCompleto')"><a href="javascript:">Nombre Completo</a></th>
        <th scope="col" width="75" onclick="order('LENGTH(Ndocumento), Ndocumento')"><a href="javascript:">Nro. Documento</a></th>
        <th scope="col" width="75" onclick="order('Fingreso')"><a href="javascript:">Fecha de Ingreso</a></th>
        <th scope="col" width="500" onclick="order('DescripCargo')"><a href="javascript:">Cargo</a></th>
        <th scope="col" onclick="order('Dependencia')"><a href="javascript:">Dependencia</a></th>
    </tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				e.CodEmpleado,
				e.CodOrganismo,
				e.CodDependencia,
				e.Fingreso,
				e.Estado,
				p.CodPersona,
				p.NomCompleto,
				p.Ndocumento,
				p.DocFiscal,
				d.Dependencia,
				pu.DescripCargo
			FROM
				mastempleado e
				INNER JOIN mastpersonas p ON (e.CodPersona = p.CodPersona)
				INNER JOIN mastdependencias d ON (e.CodDependencia = d.CodDependencia)
				INNER JOIN rh_puestos pu ON (e.CodCargo = pu.CodCargo)
			WHERE 1 $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				e.CodEmpleado,
				e.CodOrganismo,
				e.CodDependencia,
				e.CodCargo,
				e.Fingreso,
				e.Estado,
				e.SueldoActual,
				p.CodPersona,
				p.NomCompleto,
				p.Ndocumento,
				p.DocFiscal,
				p.Fnacimiento,
				p.Sexo,
				d.Dependencia,
				pu.DescripCargo,
				pu.Grado,
				pu.NivelSalarial,
				md.Descripcion AS NomCategoriaCargo
			FROM
				mastempleado e
				INNER JOIN mastpersonas p ON (e.CodPersona = p.CodPersona)
				INNER JOIN mastdependencias d ON (e.CodDependencia = d.CodDependencia)
				INNER JOIN rh_puestos pu ON (e.CodCargo = pu.CodCargo)
				LEFT JOIN mastmiscelaneosdet md ON (md.CodDetalle = pu.CategoriaCargo AND
													 md.CodMaestro = 'CATCARGO')
			WHERE 1 $filtro
			ORDER BY $fOrderBy
			LIMIT ".intval($limit).", $maxlimit";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		if ($ventana == "dependencias") {
			?>
            <tr class="trListaBody" onclick="selListado2('<?=$field['CodEmpleado']?>', '<?=($field["NomCompleto"])?>', '<?=$cod?>', '<?=$nom?>', '<?=$field["CodPersona"]?>', '<?=$campo3?>', '<?=$field["DescripCargo"]?>', '<?=$campo4?>');" id="<?=$field['CodEmpleado']?>">
            <?
		}
		elseif ($ventana == "desarrollo_carreras_empleado_sel") {
			?>
            <tr class="trListaBody" onclick="desarrollo_carreras_empleado_sel('<?=$field['CodPersona']?>', '<?=$field['CodEmpleado']?>', '<?=$field['NomCompleto']?>', '<?=$field['Ndocumento']?>', '<?=formatFechaDMA($field['Fingreso'])?>', '<?=$field['CodOrganismo']?>', '<?=$field['CodDependencia']?>', '<?=$field['CodCargo']?>', '<?=$field['NomCategoriaCargo']?>', '<?=$field['Grado']?>');" id="<?=$field['CodEmpleado']?>">
            <?
		}
		elseif ($ventana == "actuacion_fiscal_auditores_insertar") {
			?>
            <tr class="trListaBody" onclick="actuacion_fiscal_auditores_insertar('<?=$field['CodPersona']?>');" id="<?=$field['CodEmpleado']?>">
            <?
		}
		elseif ($ventana == "fideicomiso_calculo_empleado_sel") {
			?>
            <tr class="trListaBody" onclick="fideicomiso_calculo_empleado_sel('<?=$field['CodPersona']?>');" id="<?=$field['CodEmpleado']?>">
            <?
		}
		elseif ($ventana == "selListadoVacacionPeriodo") {
			?>
            <tr class="trListaBody" onclick="selListadoVacacionPeriodo('<?=$field['CodEmpleado']?>', '<?=($field["NomCompleto"])?>', '<?=$cod?>', '<?=$nom?>', '<?=($field["CodPersona"])?>', '<?=($campo3)?>');" id="<?=$field['CodEmpleado']?>">
            <?
		}
		elseif ($ventana == "insertar_linea_postulante") {
			?>
        	<tr class="trListaBody" onclick="listado_insertar_linea('<?=$detalle?>', 'accion=<?=$ventana?>&CodPersona=<?=$field['CodPersona']?>', '<?=$field['CodEmpleado']?>');">
        	<?
		}
		elseif ($ventana == "insertar_linea_participantes") {
			?>
        	<tr class="trListaBody" onclick="listado_insertar_linea('<?=$detalle?>', 'accion=<?=$ventana?>&CodPersona=<?=$field['CodPersona']?>', '<?=$field['CodEmpleado']?>');">
        	<?
		}
		elseif ($ventana == "bono_periodos_empleados_insertar") {
			?>
        	<tr class="trListaBody" onclick="listado_insertar_linea('<?=$detalle?>', 'accion=<?=$ventana?>&CodPersona=<?=$field['CodPersona']?>', '<?=$field['CodEmpleado']?>');">
        	<?
		}
		elseif ($ventana == "selListadoIFrameRequerimientoPostulante") {
			?><tr class="trListaBody" onclick="selListadoIFrameRequerimientoPostulante('<?=$field['CodPersona']?>', 'I');" id="<?=$field['CodEmpleado']?>"><?
		}
		elseif ($ventana == "jubilaciones_empleados_sel") {
			?><tr class="trListaBody" onclick="jubilaciones_empleados_sel('<?=$field['CodPersona']?>');" id="<?=$field['CodEmpleado']?>"><?
		}
		elseif ($ventana == "pensiones_invalidez_empleados_sel") {
			?><tr class="trListaBody" onclick="pensiones_invalidez_empleados_sel('<?=$field['CodPersona']?>');" id="<?=$field['CodEmpleado']?>"><?
		}
		elseif ($ventana == "pensiones_sobreviviente_empleados_sel") {
			?><tr class="trListaBody" onclick="pensiones_sobreviviente_empleados_sel('<?=$field['CodPersona']?>');" id="<?=$field['CodEmpleado']?>"><?
		}
		else {
			?>
            <tr class="trListaBody" onclick="selListado2('<?=$field['CodEmpleado']?>', '<?=($field["NomCompleto"])?>', '<?=$cod?>', '<?=$nom?>', '<?=($field["CodPersona"])?>', '<?=($campo3)?>');" id="<?=$field['CodEmpleado']?>">
            <?
		}
		?>
			<td align="center"><?=$field['CodEmpleado']?></td>
			<td><?=$field['NomCompleto']?></td>
			<td><?=$field['Ndocumento']?></td>
			<td align="center"><?=formatFechaDMA($field['Fingreso'])?></td>
			<td><?=htmlentities($field['DescripCargo'])?></td>
			<td><?=$field['Dependencia']?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
<table width="100%">
	<tr>
    	<td>
        	Mostrar: 
            <select name="maxlimit" style="width:50px;" onchange="this.form.submit();">
                <?=loadSelectGeneral("MAXLIMIT", $maxlimit, 0)?>
            </select>
        </td>
        <td align="right">
        	<?=paginacion(intval($rows_total), intval($rows_lista), intval($maxlimit), intval($limit));?>
        </td>
    </tr>
</table>
</center>
</form>
<script type="text/javascript" language="javascript">
	totalRegistros(parseInt(<?=$rows_total?>));
</script>
</body>
</html>
