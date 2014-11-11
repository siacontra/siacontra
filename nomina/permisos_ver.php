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
		<td class="titulo">Permisos del Empleado | Ver Registro</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
$sql="SELECT rh_permisos.CodPersona, (SELECT mastpersonas.NomCompleto FROM mastpersonas WHERE mastpersonas.CodPersona=rh_permisos.CodPersona) AS NombrePersona, rh_permisos.Aprobador, (SELECT mastpersonas.NomCompleto FROM mastpersonas WHERE mastpersonas.CodPersona=rh_permisos.Aprobador) AS NombreAprobador, rh_permisos.TipoPermiso, rh_permisos.TipoFalta, rh_permisos.PeriodoContable, rh_permisos.FechaDesde, rh_permisos.FechaHasta, rh_permisos.HoraDesde, rh_permisos.HoraHasta, rh_permisos.FlagRemunerado, rh_permisos.FlagJustificativo, rh_permisos.Estado, rh_permisos.ObsMotivo, rh_permisos.UltimoUsuario, rh_permisos.UltimaFecha, rh_permisos.ObsAprobado, rh_permisos.TotalDias, rh_permisos.TotalHoras, rh_permisos.FechaIngreso, rh_permisos.TotalMinutos, rh_permisos.TotalFecha, rh_permisos.TotalTiempo FROM rh_permisos WHERE rh_permisos.CodPermiso='".$registro."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) $field=mysql_fetch_array($query);
list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaDesde']); $fdesde=$d."-".$m."-".$a;
list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaHasta']); $fhasta=$d."-".$m."-".$a;
list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaIngreso']); $fingreso=$d."-".$m."-".$a;
list($h, $m, $s)=SPLIT('[:]', $field['HoraDesde']); if ($h>12) { $h=$h-12; $merdesde="PM"; if ($h<10) $h="0$h"; } else { $merdesde="AM"; } $hdesde=$h.":".$m;
list($h, $m, $s)=SPLIT('[:]', $field['HoraHasta']); if ($h>12) { $h=$h-12; $merhasta="PM"; if ($h<10) $h="0$h"; } else { $merhasta="AM"; } $hhasta=$h.":".$m;
if ($field["FlagRemunerado"]=="S") $remunerado="checked";
if ($field["FlagJustificativo"]=="S") $justifica="checked";
if ($field["Estado"]=="P") $status="PENDIENTE";
if ($field["Estado"]=="A") $status="APROBADO";
if ($field["Estado"]=="R") $status="RECHAZADO";
if ($field["Estado"]=="N") $status="ANULADO";	
?>
<div style="width:700px" class="divFormCaption">Datos del Permiso</div>
<table width="700" class="tblForm">
  <tr>
    <td class="tagForm">Empleado:</td>
    <td colspan="2">
			<input name="codempleado" type="hidden" id="codempleado" value="<?=$field["CodPersona"]?>" />
			<input name="nomempleado" type="text" id="nomempleado" size="75" value="<?=$field["NombrePersona"]?>" readonly />
	</td>
  </tr>
  <tr>
    <td class="tagForm">Aprueba:</td>
    <td colspan="2">
			<input name="codaprueba" type="hidden" id="codaprueba" value="<?=$field["Aprobador"]?>" />
			<input name="nomaprueba" type="text" id="nomaprueba" size="75" value="<?=$field["NombreAprobador"]?>" readonly />
	</td>
  </tr>
  <tr>
    <td class="tagForm">Motivo de Ausencia:</td>
    <td>
		<select name="tpermiso" id="tpermiso" class="selectMed">
			<?php getMiscelaneos($field["TipoPermiso"], "PERMISOS", 1); ?>
		</select>
	</td>
  </tr>
  <tr>
    <td class="tagForm">Tipo de Ausencia:</td>
    <td>
		<select name="tfalta" id="tfalta" class="selectMed">
			<?php getMiscelaneos($field["TipoFalta"], "TIPOFALTAS", 1); ?>
		</select>
	</td>
  </tr>
  <tr>
    <td class="tagForm">Per&iacute;odo Contable:</td>
    <td><input name="periodo" type="text" id="periodo" size="15" maxlength="7" value="<?=$field["PeriodoContable"]?>" readonly /></td>
  </tr>
  <tr>
    <td class="tagForm">Fecha de Ingreso:</td>
    <td><input name="fingreso" type="text" id="fingreso" size="15" maxlength="7" value="<?=$fingreso?>" readonly /></td>
  </tr>
  <tr>
    <td class="tagForm">Fecha:</td>
    <td>
		<input name="fdesde" type="text" id="fdesde" size="15" maxlength="10" value="<?=$fdesde?>" readonly /> - 
		<input name="fhasta" type="text" id="fhasta" size="15" maxlength="10" value="<?=$fhasta?>" readonly /> 
		<input type="text" name="tfecha" id="tfecha" size="4" readonly value="<?=$field['TotalFecha']?>" />
	</td>
  </tr>
  <tr>
    <td class="tagForm">Hora:</td>
    <td>
		<input name="hdesde" type="text" id="hdesde" size="3" maxlength="5" value="<?=$hdesde?>" readonly />
		<select name="turnodesde">
			<?php getMeridian($merdesde, 1); ?>
		</select> - 
		<input name="hhasta" type="text" id="hhasta" size="3" maxlength="5" value="<?=$hhasta?>" readonly />
		<select name="turnohasta">
			<?php getMeridian($merhasta, 1); ?>
		</select> 
		<input type="text" name="ttiempo" id="ttiempo" size="4" readonly value="<?=$field['TotalTiempo']?>" />
	</td>
  </tr>
  <tr>
    <td class="tagForm">Total:</td>
    <td>
		Dias: <input type="text" name="dias" id="dias" size="5" value="<?=$field['TotalDias']?>" disabled="disabled" /> - 
		Horas: <input type="text" name="horas" id="horas" size="5" value="<?=$field['TotalHoras']?>" disabled="disabled" /> - 
		Horas: <input type="text" name="minutos" id="minutos" size="5" value="<?=$field['TotalMinutos']?>" disabled="disabled" />
	</td>
  </tr>
  <tr>
  	<td class="tagForm">Descripci&oacute;n del Motivo :</td>
	<td colspan="2"><textarea name="observaciones" id="observaciones" cols="75" rows="3" readonly><?=$field["ObsMotivo"]?></textarea></td>
  </tr>
  </tr> 
  <tr>
    <td>&nbsp;</td>
	<td>
		¿Remunerado?<input type="checkbox" name="flagremunerado" id="flagremunerado" <?=$remunerado?> disabled="disabled" />&nbsp;&nbsp;&nbsp;
		¿Entregar justificativo?<input type="checkbox" name="flagjustificativo" id="flagjustificativo" <?=$justifica?> disabled="disabled" />&nbsp;&nbsp;&nbsp;
		¿Exento?<input type="checkbox" name="flagexento" id="flagexento" <?=$disabled?> disabled="disabled" />
	</td>
  </tr>
  <?
  if ($field["Estado"]=="A" || $field["Estado"]=="R") {  
  	?>
	  <tr>
		<td class="tagForm">Estado:</td>
		<td colspan="2"><?=$status?></td>
	  </tr>
	  <tr>
		<td class="tagForm">Observaciones:</td>
		<td colspan="2"><textarea name="obsaprobado" id="obsaprobado" cols="75" rows="3"><?=$field["ObsAprobado"]?></textarea></td>
	  </tr>
	<?
  }
  ?>
  <tr>
	  <td class="tagForm">&Uacute;ltima Modif.:</td>
	  <td>
		<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$field["UltimoUsuario"]?>" readonly />
		<input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field["UltimaFecha"]?>" readonly />
	  </td>
	</tr>
</table>
</body>
</html>
