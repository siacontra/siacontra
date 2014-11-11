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

<?php
include("fphp.php");
connect();
if ($accion=="INICIAR") $disabled="disabled";
?>

<form id="frmentrada" name="frmentrada" action="capacitacion_horarios.php" method="POST" onsubmit="return verificarCapacitacionHorarios(this, 'INSERTAR');">
<table class="tblForm" width="650">
	<tr>
		<td width="125">Estado:</td>
		<td>
			<select name="status" id="status" <?=$disabled?>>
				<?=getStatus('A', 0);?>
			</select>
		</td>
		<td>&nbsp;</td>
		<td>L <input type="checkbox" name="flunes" id="flunes" value="S" onclick="setHorasCapacitacion('flunes', 'dlunes', 'hlunes');" <?=$disabled?> /></td>
		<td>M <input type="checkbox" name="fmartes" id="fmartes" value="S" onclick="setHorasCapacitacion('fmartes', 'dmartes', 'hmartes');" <?=$disabled?> /></td>
		<td>M <input type="checkbox" name="fmiercoles" id="fmiercoles" value="S" onclick="setHorasCapacitacion('fmiercoles', 'dmiercoles', 'hmiercoles');" <?=$disabled?> /></td>
		<td>J <input type="checkbox" name="fjueves" id="fjueves" value="S" onclick="setHorasCapacitacion('fjueves', 'djueves', 'hjueves');" <?=$disabled?> /></td>
		<td>V <input type="checkbox" name="fviernes" id="fviernes" value="S" onclick="setHorasCapacitacion('fviernes', 'dviernes', 'hviernes');" <?=$disabled?> /></td>
		<td>S <input type="checkbox" name="fsabado" id="fsabado" value="S" onclick="setHorasCapacitacion('fsabado', 'dsabado', 'hsabado');" <?=$disabled?> /></td>
		<td>D <input type="checkbox" name="fdomingo" id="fdomingo" value="S" onclick="setHorasCapacitacion('fdomingo', 'ddomingo', 'hdomingo');" <?=$disabled?> /></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>Desde:</td><td><input type="text" name="fdesde" id="fdesde" size="15" maxlength="10" /></td>
		<td>De:</td>
		<td><input type="text" name="dlunes" id="dlunes" size="3" maxlength="5" disabled="disabled" /></td>
		<td><input type="text" name="dmartes" id="dmartes" size="3" maxlength="5" disabled="disabled" /></td>
		<td><input type="text" name="dmiercoles" id="dmiercoles" size="3" maxlength="5" disabled="disabled" /></td>
		<td><input type="text" name="djueves" id="djueves" size="3" maxlength="5" disabled="disabled" /></td>
		<td><input type="text" name="dviernes" id="dviernes" size="3" maxlength="5" disabled="disabled" /></td>
		<td><input type="text" name="dsabado" id="dsabado" size="3" maxlength="5" disabled="disabled" /></td>
		<td><input type="text" name="ddomingo" id="ddomingo" size="3" maxlength="5" disabled="disabled" /></td>
		<td><input type="text" id="dias" size="15" readonly /></td>
	</tr>
	<tr>
		<td>Hasta:</td><td><input type="text" name="fhasta" id="fhasta" size="15" maxlength="10" /></td>
		<td>A:</td>
		<th><input type="text" name="hlunes" id="hlunes" size="3" maxlength="5" disabled="disabled" /></th>
		<th><input type="text" name="hmartes" id="hmartes" size="3" maxlength="5" disabled="disabled" /></th>
		<th><input type="text" name="hmiercoles" id="hmiercoles" size="3" maxlength="5" disabled="disabled" /></th>
		<th><input type="text" name="hjueves" id="hjueves" size="3" maxlength="5" disabled="disabled" /></th>
		<th><input type="text" name="hviernes" id="hviernes" size="3" maxlength="5" disabled="disabled" /></th>
		<th><input type="text" name="hsabado" id="hsabado" size="3" maxlength="5" disabled="disabled" /></th>
		<th><input type="text" name="hdomingo" id="hdomingo" size="3" maxlength="5" disabled="disabled" /></th>
		<td><input type="text" id="horas" size="15" readonly /></td>
	</tr>
</table>
<center>
<input type="submit" value="Guardar Registro" <?=$disabled?> />
<input type="button" value="Limpiar Formulario" onclick="limpiarCapacitacionHorario();" <?=$disabled?> />
</center><br />

<div class="divDivision">Detalle de los horarios por lapsos de fechas</div><br />

<table width="642" class="tblBotones">
 <tr>
	<td align="right">
		<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="optCapacitacionHorario(this.form, 'EDITAR');" <?=$disabled?> />
		<input name="btBorrar" type="button" class="btLista" id="btBorrar" value="Borrar" onclick="optCapacitacionHorario(this.form, 'ELIMINAR');" <?=$disabled?> />
	</td>
 </tr>
</table>

<?php
//	ELIMINAR
if ($accion=="ELIMINAR") {
	$sql="DELETE FROM rh_capacitacion_hora WHERE Capacitacion='".$capacitacion."' AND CodOrganismo='".$organismo."' AND Secuencia='".$sec."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
//	CONSULTO LA TABLA
$sql="SELECT ch.* FROM rh_capacitacion_hora ch WHERE ch.Capacitacion='".$capacitacion."' AND ch.CodOrganismo='".$organismo."' GROUP BY ch.Capacitacion, ch.CodOrganismo";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
?>

<input type="hidden" name="sec" id="sec" />
<input type="hidden" name="organismo" id="organismo" value="<?=$organismo?>" />
<input type="hidden" name="capacitacion" id="capacitacion" value="<?=$capacitacion?>" />
<table class="tblLista">
<?php
for ($i=0; $i<$rows; $i++) { 
	$field=mysql_fetch_array($query);
	list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaDesde']); $fdesde=$d."-".$m."-".$a;
	list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaHasta']); $fhasta=$d."-".$m."-".$a;
	if ($field['Lunes']=="S") $lunes="checked";
	if ($field['Martes']=="S") $martes="checked";
	if ($field['Miercoles']=="S") $miercoles="checked";
	if ($field['Jueves']=="S") $jueves="checked";
	if ($field['Viernes']=="S") $viernes="checked";
	if ($field['Sabado']=="S") $sabado="checked";
	if ($field['Domingo']=="S") $domingo="checked";
?>
	<tr class="trListaBody" onclick="mClk(this, 'sec');" onmouseover="mOvr(this);" onmouseout="mOut(this);" id="<?=$field["Secuencia"]?>">
		<td width="75" align="center"><?=$field['Secuencia']?></td>
		<td width="550">
			<table>
				<tr>
					<td>Estado:</td>
					<td>
						<select>
							<?=getStatus($field['Estado'], 1);?>
						</select>
					</td>
					<td>&nbsp;</td>
					<td>L <input type="checkbox" <?=$lunes?> disabled="disabled" /></td>
					<td>M <input type="checkbox" <?=$martes?> disabled="disabled" /></td>
					<td>M <input type="checkbox" <?=$miercoles?> disabled="disabled" /></td>
					<td>J <input type="checkbox" <?=$jueves?> disabled="disabled" /></td>
					<td>V <input type="checkbox" <?=$viernes?> disabled="disabled" /></td>
					<td>S <input type="checkbox" <?=$sabado?> disabled="disabled" /></td>
					<td>D <input type="checkbox" <?=$domingo?> disabled="disabled" /></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>Desde:</td><td><input type="text" name="desde" id="desde" size="15" maxlength="10" value="<?=$fdesde?>" readonly /></td>
					<td>De:</td>
					<td><input type="text" size="3" maxlength="5" value="<?=$field['HoraInicioLunes']?>" readonly /></td>
					<td><input type="text" size="3" maxlength="5" value="<?=$field['HoraInicioMartes']?>" readonly /></td>
					<td><input type="text" size="3" maxlength="5" value="<?=$field['HoraInicioMiercoles']?>" readonly /></td>
					<td><input type="text" size="3" maxlength="5" value="<?=$field['HoraInicioJueves']?>" readonly /></td>
					<td><input type="text" size="3" maxlength="5" value="<?=$field['HoraInicioViernes']?>" readonly /></td>
					<td><input type="text" size="3" maxlength="5" value="<?=$field['HoraInicioSabado']?>" readonly /></td>
					<td><input type="text" size="3" maxlength="5" value="<?=$field['HoraInicioDomingo']?>" readonly /></td>
					<td><input type="text" size="15" readonly /></td>
				</tr>
				<tr>
					<td>Hasta:</td><td><input type="text" name="hasta" id="hasta" size="15" maxlength="10" value="<?=$fhasta?>" readonly /></td>
					<td>A:</td>
					<th><input type="text" size="3" maxlength="5" value="<?=$field['HoraFinLunes']?>" readonly /></th>
					<th><input type="text" size="3" maxlength="5" value="<?=$field['HoraFinMartes']?>" readonly /></th>
					<th><input type="text" size="3" maxlength="5" value="<?=$field['HoraFinMiercoles']?>" readonly /></th>
					<th><input type="text" size="3" maxlength="5" value="<?=$field['HoraFinJueves']?>" readonly /></th>
					<th><input type="text" size="3" maxlength="5" value="<?=$field['HoraFinViernes']?>" readonly /></th>
					<th><input type="text" size="3" maxlength="5" value="<?=$field['HoraFinSabado']?>" readonly /></th>
					<th><input type="text"  size="3" maxlength="5" value="<?=$field['HoraFinDomingo']?>" readonly /></th>
					<td><input type="text" size="15" readonly /></td>
				</tr>
			</table>	
		</td>
	</tr>
<? } ?>
</table>
</form>

</body>
</html>