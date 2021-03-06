<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
include("fphp_nomina.php");
connect();
//	--------------------------
list($codorganismo, $codtiponom, $periodo, $codtipoproceso)=SPLIT( '[:.:]', $registro);
$sql="SELECT ppp.*, mp1.NomCompleto AS NomCreadoPor FROM pr_procesoperiodo ppp INNER JOIN mastpersonas mp1 ON (ppp.CreadoPor=mp1.CodPersona) WHERE ppp.CodOrganismo='".$codorganismo."' AND ppp.CodTipoNom='".$codtiponom."' AND ppp.Periodo='".$periodo."' AND ppp.CodTipoProceso='".$codtipoproceso."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query)!=0) $field=mysql_fetch_array($query);
list($a, $m, $d)=SPLIT( '[-./]', $field['FechaDesde']); $fdesde="$d-$m-$a";
list($a, $m, $d)=SPLIT( '[-./]', $field['FechaHasta']); $fhasta="$d-$m-$a";
list($a, $m, $d)=SPLIT( '[-./]', $field['FechaProceso']); $fprocesado="$d-$m-$a";
list($a, $m, $d)=SPLIT( '[-./]', $field['FechaPago']); $fpago="$d-$m-$a";
if ($field['Estado']=="A") $chkactivo="checked"; else $chkinactivo="checked";
if ($field['FlagMensual']=="S") $chkflagmensual="checked";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_nomina.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Control de Procesos | Actualizaci&oacute;n</td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="procesos_control.php" method="POST" onsubmit="return verificarProcesoControlIniciar(this, 'ACTUALIZAR');">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<input type="hidden" name="forganismo" id="forganismo" value="<?=$forganismo?>" />
<input type="hidden" name="ftiponom" id="ftiponom" value="<?=$ftiponom?>" />
<input type="hidden" name="chktiponom" id="chktiponom" value="<?=$chktiponom?>" />
<input type="hidden" name="chkinactivos" id="chkinactivos" value="<?=$chkinactivos?>" />

<div style="width:800px" class="divFormCaption">Datos del Tipo de Proceso</div>
<table width="800" class="tblForm">
    <tr>
        <td class="tagForm">Organismo:</td>
        <td>
        	<select name="organismo" id="organismo" class="selectBig" style="width:225px;">
				<?=getOrganismos($codorganismo, 1)?>
			</select>*
        </td>
        <td class="tagForm">Tipo de N&oacute;mina:</td>
        <td>
        	<select name="tiponom" id="tiponom" class="selectBig" style="width:225px;" onchange="getOptions_2(this.id, 'periodo'); getOptions_2(this.id, 'proceso');">
				<?=getTNomina($codtiponom, 1)?>
			</select>*
        </td>
    </tr>
    <tr>
        <td class="tagForm">Periodo:</td>
        <td>
        	<select name="periodo" id="periodo" style="width:100px;">
                <?=getPeriodos($periodo, $codtiponom, $organismo, 2)?>
			</select>*
        </td>
        <td class="tagForm">Tipo de Proceso:</td>
        <td>
        	<select name="proceso" id="proceso" style="width:225px;">
                 <?=getTipoProceso($codtipoproceso, 1)?>
			</select>*
        </td>
    </tr>
    <tr>
        <td class="tagForm">Desde:</td>
        <td><input name="fdesde" type="text" id="fdesde" size="15" maxlength="10" value="<?=$fdesde?>" />*</td>
        <td class="tagForm">Hasta:</td>
        <td><input name="fhasta" type="text" id="fhasta" size="15" maxlength="10" value="<?=$fhasta?>" />*</td>
    </tr>
    <tr>
        <td class="tagForm">Estado:</td>
        <td>
            <input name="status" id="activo" type="radio" value="A" <?=$chkactivo?> /> Activo
            <input name="status" id="inactivo" type="radio" value="I" <?=$chkinactivo?> /> Inactivo
        </td>
        <td>&nbsp;</td><td><input type="checkbox" name="flagmensual" id="flagmensual" value="S" <?=$chkflagmensual?> /> Proceso mensual?</td>
    </tr>
</table>
<div style="width:800px" class="divFormCaption">Datos de la Planilla</div>
<table width="800" class="tblForm">    
    <tr>
        <td class="tagForm" width="150">Creado Por:</td>
        <td colspan="3"><input name="creado" type="text" id="creado" size="100" value="<?=($field['NomCreadoPor'])?>" readonly="readonly" /></td>
        <td width="125"></td>
    </tr>   
    <tr>
        <td class="tagForm">Fecha de Creaci&oacute;n:</td>
        <td colspan="3"><input name="fcreado" type="text" id="fcreado" size="30" value="<?=$field['FechaCreado']?>" readonly="readonly" /></td>
        <td width="125"></td>
    </tr>  
    <tr>
        <td class="tagForm">Procesado Por:</td>
        <td colspan="3"><input name="procesado" type="text" id="procesado" size="100" value="<?=($field['ProcesadoPor'])?>" readonly="readonly" /></td>
        <td width="125"></td>
    </tr>
    <tr>
        <td class="tagForm">Fecha de Proceso:</td>
        <td><input name="fprocesado" type="text" id="fprocesado" size="15" maxlength="10" value="<?=$fprocesado?>" />*</td>
        <td class="tagForm">Fecha de Pago:</td>
        <td><input name="fpago" type="text" id="fpago" size="15" maxlength="10" value="<?=$fpago?>" />*</td>
        <td width="125"></td>
    </tr>  
    <tr>
        <td class="tagForm">Aprobado Por:</td>
        <td colspan="3"><input name="aprobado" type="text" id="aprobado" size="100" value="<?=($field['AprobadoPor'])?>" readonly="readonly" /></td>
        <td width="125"></td>
    </tr>   
    <tr>
        <td class="tagForm">Fecha de Aprob.:</td>
        <td colspan="3"><input name="faprobado" type="text" id="faprobado" size="30" value="<?=$field['FechaAprobado']?>" readonly="readonly" /></td>
        <td width="125"></td>
    </tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onclick="cargarPagina(this.form, 'procesos_control.php');" />
</center><br />
</form>

<div style="width:800px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
