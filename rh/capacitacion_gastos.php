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

<form id="frmentrada" name="frmentrada" action="capacitacion_gastos.php" method="POST" onsubmit="return verificarCapacitacionGastos(this, 'INSERTAR');">
<table class="tblForm" width="600">
	<tr>
		<td>Comprobante:</td>
		<td>Fecha:</td>
		<td>Sub-Total:</td>
		<td>Impuestos:</td>
		<td>Total:</td>
	</tr>
	<tr>
		<td><input type="text" name="numero" id="numero" size="20" maxlength="15" /></td>
		<td><input type="text" name="fecha" id="fecha" size="15" maxlength="10" /></td>
		<td><input type="text" name="subtotal" id="subtotal" size="25" /></td>
		<td><input type="text" name="impuesto" id="impuesto" size="25" /></td>
		<td><input type="text" name="total" id="total" size="25" readonly /></td>
	</tr>
</table>
<center>
<input type="submit" value="Guardar Registro" <?=$disabled?> />
<input type="button" value="Limpiar Formulario" onclick="limpiarCapacitacionGastos();" <?=$disabled?> />
</center><br />

<div class="divDivision">Gastos</div><br />

<table width="600" class="tblBotones">
 <tr>
	<td align="right">
		<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="optCapacitacionGasto(this.form, 'EDITAR');" <?=$disabled?> />
		<input name="btBorrar" type="button" class="btLista" id="btBorrar" value="Borrar" onclick="optCapacitacionGasto(this.form, 'ELIMINAR');" <?=$disabled?> />
	</td>
 </tr>
</table>

<?php
//	ELIMINAR
if ($accion=="ELIMINAR") {
	$sql="DELETE FROM rh_capacitacion_gastos WHERE Capacitacion='".$capacitacion."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
//	CONSULTO LA TABLA
$sql="SELECT * FROM rh_capacitacion_gastos WHERE Capacitacion='".$capacitacion."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
?>

<input type="hidden" name="sec" id="sec" />
<input type="hidden" name="organismo" id="organismo" value="<?=$organismo?>" />
<input type="hidden" name="capacitacion" id="capacitacion" value="<?=$capacitacion?>" />
<table class="tblLista" width="600">
	<tr class="trListaHead">
		<th scope="col">Comprobante</th>
		<th scope="col" width="90">Fecha</th>
		<th scope="col" width="125">Sub-Total</th>
		<th scope="col" width="125">Impuestos</th>
		<th scope="col" width="125">Total</th>
	</tr>
<?php
for ($i=1; $i<=$rows; $i++) { 
	$field=mysql_fetch_array($query);
	list($a, $m, $d)=SPLIT( '[/.-]', $field['Fecha']); $fecha=$d."-".$m."-".$a;
	$subtotal=number_format($field["SubTotal"], 2, ',', '.');
	$impuesto=number_format($field["Impuestos"], 2, ',', '.');
	$total=number_format($field["Total"], 2, ',', '.');
?>
	<tr class="trListaBody" onclick="mClk(this, 'sec');" onmouseover="mOvr(this);" onmouseout="mOut(this);" id="<?=$field["Capacitacion"]?>">
		<td align="center"><?=$field['Numero']?></td>
		<td align="center"><?=$fecha?></td>
		<td align="right"><?=$subtotal?></td>
		<td align="right"><?=$impuesto?></td>
		<td align="right"><?=$total?></td>
	</tr>
<? } ?>
</table>
</form>

</body>
</html>