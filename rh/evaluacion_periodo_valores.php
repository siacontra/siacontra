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
<form name="frmentrada" id="frmentrada" method="post" action="evaluacion_periodo_valores.php" onsubmit="return verificarEvaluacionDesempenioValor(this)">
<?php
include("fphp.php");
connect();
if ($accion=="NUEVO") $disabled="disabled";
?>

<table width="850" class="tblForm">
	<tr>
		<td>* Descripci&oacute;n:</td>
		<td>Explicacion:</td>
	</tr>
	<tr>
		<td><input name="descripcion" type="text" id="descripcion" size="75" maxlength="50" <?=$disabled?> /></td>
		<td rowspan="3"><textarea name="explicacion" id="explicacion" cols="100" rows="4" <?=$disabled?>></textarea></td>
	</tr>
	<tr>
		<td>* Valor:</td>
    </tr>
	<tr>
		<td rowspan="3"><input name="valor" type="text" id="valor" size="20" maxlength="3" <?=$disabled?> /></td>
    </tr>
</table>

<input type="hidden" name="inserto" id="inserto" value="INSERTAR" />
<input type="hidden" name="sec" id="sec" value="" />
<input type="hidden" name="rango" id="rango" value="" />
<input type="hidden" name="organismo" id="organismo" value="<?=$organismo?>" />
<input type="hidden" name="periodo" id="periodo" value="<?=$periodo?>" />
<input type="hidden" name="secuencia" id="secuencia" value="<?=$secuencia?>" />
<table width="850" class="tblBotones">
 <tr>
	<td align="right">
		<input name="btInsertar" type="submit" class="btLista" id="btInsertar" value="Insertar" <?=$disabled?> />
		<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="optEvaluacionDesempenio(this.form, 'EDITAR');" />
		<input name="btBorrar" type="button" class="btLista" id="btBorrar" value="Borrar" onclick="optEvaluacionDesempenio(this.form, 'ELIMINAR');" />
	</td>
 </tr>
</table>

<table width="850" class="tblLista">
	<tr class="trListaHead">
		<th scope="col" width="25">#</th>
		<th scope="col" width="300">Descripci&oacute;n</th>
		<th scope="col">Explicaci&oacute;n</th>
		<th scope="col" width="150">Valor</th>
	</tr>
	<?php
	$sql="SELECT * FROM rh_evaluacionperiodovalor WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia' ORDER BY Rango";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	while($field=mysql_fetch_array($query)) {
		$i++;
		$valor=number_format($field['Valor'], 2, ',', '.');
		?>
		<tr class="trListaBody" onclick="mClk(this, 'sec');" id="<?=$field['Rango']?>">
			<td align="center"><?=$i?></td>
			<td><?=($field['Descripcion'])?></td>
			<td><?=($field['Explicacion'])?></td>
			<td align="right"><?=$valor?></td>
		</tr>
		<?
	}
	$rows=(int) $rows;
	?>
</table>

<script type="text/javascript" language="javascript">
	totalElementos(<?=$rows?>);
</script>
</form>
</body>
</html>