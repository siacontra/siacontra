<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);
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
		<td class="titulo">Permisos del Empleado | Nuevo Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onClick="cargarPagina(document.getElementById('frmentrada'), '<?=$regresar?>.php?limit=<?=$limit?>');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
$annio_actual=date("Y");
$mes_actual=date("m");
$dia_actual=date("d");
$hora_actual=date("H");
$min_actual=date("i");
$periodo=$annio_actual."-".$mes_actual;
$fecha=$dia_actual."-".$mes_actual."-".$annio_actual;
if ($hora_actual<12) $meridiano="AM";
else {
	$meridiano="PM";
	$hora_actual=(int) $hora_actual;
	$hora_actual-=12;
	if ($hora_actual==0) $hora_actual=12;
	if ($hora_actual<10) $hora_actual="0$hora_actual";
}
$hora=$hora_actual.":".$min_actual;
?>
<form id="frmentrada" name="frmentrada" action="permisos.php" method="POST" onsubmit="return verificarPermisos(this, 'GUARDAR');">
<input type="hidden" name="codigo" id="codigo" value="" />
<input type="hidden" name="status" id="status" value="P" />

<div style="width:700px" class="divFormCaption">Datos del Permiso</div>
<table width="700" class="tblForm">
  <tr>
    <td class="tagForm">Empleado:</td>
    <td>
		<input name="codempleado" type="hidden" id="codempleado" value="<?=$_SESSION["CODPERSONA_ACTUAL"]?>" />
		<input name="nomempleado" type="text" id="nomempleado" size="75" value="<?=$_SESSION["NOMBRE_USUARIO_ACTUAL"]?>" readonly />
		<input name="bt_examinar" type="button" id="bt_examinar" value="..." onclick="cargarVentana(this.form, 'lista_empleados.php?limit=0&campo=1', 'height=500, width=800, left=200, top=200, resizable=yes');" />*
	</td>
  </tr>
  <tr>
    <td class="tagForm">Aprueba:</td>
    <td>
		<input name="codaprueba" type="hidden" id="codaprueba" value="" />
		<input name="nomaprueba" type="text" id="nomaprueba" size="75" value="" readonly />
		<input name="bt_examinar" type="button" id="bt_examinar" value="..." onclick="cargarVentana(this.form, 'lista_empleados.php?limit=0&campo=2', 'height=500, width=800, left=200, top=200, resizable=yes');" />*
	</td>
  </tr>
  <tr>
    <td class="tagForm">Motivo de Ausencia:</td>
    <td>
		<select name="tpermiso" id="tpermiso" class="selectMed">
			<option value=""></option>
			<?php getMiscelaneos("", "PERMISOS", 0); ?>
		</select>*
	</td>
  </tr>
  <tr>
    <td class="tagForm">Tipo de Ausencia:</td>
    <td>
		<select name="tfalta" id="tfalta" class="selectMed">
			<option value=""></option>
			<?php getMiscelaneos("", "TIPOFALTAS", 0); ?>
		</select>*
	</td>
  </tr>
  <tr>
    <td class="tagForm">Per&iacute;odo Contable:</td>
    <td><input name="periodo" type="text" id="periodo" size="15" maxlength="7" value="<?=$periodo?>" readonly /></td>
  </tr>
  <tr>
    <td class="tagForm">Fecha de Ingreso:</td>
    <td><input name="fingreso" type="text" id="fingreso" size="15" maxlength="7" value="<?=date("d-m-Y")?>" readonly /></td>
  </tr>
  <tr>
    <td class="tagForm">Fecha:</td>
    <td>
		<input name="fdesde" type="text" id="fdesde" size="15" maxlength="10" value="<?=$fecha?>" onkeyup="getTotalDiasPermisos();" /> - 
		<input name="fhasta" type="text" id="fhasta" size="15" maxlength="10" value="<?=$fecha?>" onkeyup="getTotalDiasPermisos();" /> * 
		<input type="text" name="tfecha" id="tfecha" size="4" readonly />
	</td>
  </tr>
  <tr>
    <td class="tagForm">Hora:</td>
    <td>
		<input name="hdesde" type="text" id="hdesde" size="3" maxlength="5" value="<?=$hora?>" onkeyup="getTotalDiasPermisos();" />
		<select name="turnodesde" id="turnodesde" onchange="getTotalDiasPermisos();">
			<?php getMeridian($meridiano, 0); ?>
		</select> - 
		<input name="hhasta" type="text" id="hhasta" size="3" maxlength="5" value="<?=$hora?>" onkeyup="getTotalDiasPermisos();" />
		<select name="turnohasta" id="turnohasta" onchange="getTotalDiasPermisos();">
			<?php getMeridian($meridiano, 0); ?>
		</select> * 
		<input type="text" name="ttiempo" id="ttiempo" size="4" readonly />
	</td>
  </tr>
  <tr>
    <td class="tagForm">Total:</td>
    <td>
		Dias: <input type="text" name="dias" id="dias" size="5" readonly /> - 
		Horas: <input type="text" name="horas" id="horas" size="5" readonly /> - 
		Minutos: <input type="text" name="minutos" id="minutos" size="5" readonly />
	</td>
  </tr>
  <tr>
  	<td class="tagForm">Descripci&oacute;n del Motivo:</td>
	<td><textarea name="observaciones" id="observaciones" cols="75" rows="3"></textarea></td>
  </tr>
  </tr> 
  <tr>
    <td>&nbsp;</td>
	<td>
		¿Remunerado?<input type="checkbox" name="flagremunerado" id="flagremunerado" checked="checked" disabled="disabled" />&nbsp;&nbsp;&nbsp;
		¿Entregar justificativo?<input type="checkbox" name="flagjustificativo" id="flagjustificativo" disabled="disabled" />&nbsp;&nbsp;&nbsp;
		¿Exento?<input type="checkbox" name="flagexento" id="flagexento" disabled="disabled" />
	</td>
  </tr>
  <tr>
	  <td class="tagForm">&Uacute;ltima Modif.:</td>
	  <td>
		<input name="ult_usuario" type="text" id="ult_usuario" size="30" readonly />
		<input name="ult_fecha" type="text" id="ult_fecha" size="25" readonly />
	  </td>
	</tr>
</table>
<center> 
<input name="btGuardar" id="btGuardar" type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, '<?=$regresar?>.php?limit=<?=$limit?>');" />
</center><br />
<?php
//	FILTRO..............
echo "
<input type='hidden' name='chkorganismo' value='".$_POST['chkorganismo']."' />
<input type='hidden' name='forganismo' value='".$_POST['forganismo']."' />
<input type='hidden' name='chkpermiso' value='".$_POST['chkpermiso']."' />
<input type='hidden' name='fpermiso' value='".$_POST['fpermiso']."' />
<input type='hidden' name='chkdependencia' value='".$_POST['chkdependencia']."' />
<input type='hidden' name='fdependencia' value='".$_POST['fdependencia']."' />
<input type='hidden' name='chkfingreso' value='".$_POST['chkfingreso']."' />
<input type='hidden' name='ffingresod' value='".$_POST['ffingresod']."' />
<input type='hidden' name='ffingresoh' value='".$_POST['ffingresoh']."' />
<input type='hidden' name='chkempleado' value='".$_POST['chkempleado']."' />
<input type='hidden' name='fempleado' value='".$_POST['fempleado']."' />
<input type='hidden' name='filtro' value='".$_POST['filtro']."' />
<input type='hidden' name='limit' value='".$_POST['limit']."' />";
?>


</form>

<script type="text/javascript" language="javascript">
	permisoBoton("btGuardar", "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
</script>

<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
