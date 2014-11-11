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
<form name="frmentrada" id="frmentrada" method="post" action="cargos_evaluacion.php" onsubmit="return verificarCargoEvaluacion(this)">
<?php
include("fphp.php");
connect();
if ($accion=="NUEVO") $disabled="disabled";
elseif ($accion=="VER") { $disabled="disabled"; $ver="disabled"; }
?>

<table width="550" class="tblForm">
	<tr>
		<td width="50">&nbsp;</td>
		<td width="80">* Etapa:</td>
		<td>* Evaluaci&oacute;n:</td>
		<td width="80">* Factor:</td>
		<td width="75">* Estado:</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input name="etapa" type="text" id="etapa" size="10" maxlength="3" <?=$disabled?> /></td>
        <td>
        	<select name="evaluacion" id="evaluacion" style="width:225px;" <?=$disabled?>>
            	<option value=""></option>
            	<?=getEvaluaciones('', 0);?>
            </select>
        </td>
		<td><input name="factor" type="text" id="factor" size="10" maxlength="3" <?=$disabled?> /></td>
        <td>
        	<select name="status" id="status" <?=$disabled?>>
            	<option value=""></option>
            	<?=getStatus('', 0);?>
            </select>
        </td>
	</tr>
</table>

<input type="hidden" name="inserto" id="inserto" value="NUEVO" />
<input type="hidden" name="sec" id="sec" value="" />
<input type="hidden" name="secuencia" id="secuencia" value="" />
<input type="hidden" name="codcargo" id="codcargo" value="<?=$codcargo?>" />
<table width="550" class="tblBotones">
 <tr>
	<td align="right">
		<input name="btInsertar" type="submit" class="btLista" id="btInsertar" value="Insertar" <?=$disabled?> />
		<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="optCargoEvaluacion(this.form, 'EDITAR');" <?=$ver?> />
		<input name="btBorrar" type="button" class="btLista" id="btBorrar" value="Borrar" onclick="optCargoEvaluacion(this.form, 'ELIMINAR');" <?=$ver?> />
	</td>
 </tr>
</table>

<table width="550" class="tblLista">
	<tr class="trListaHead">
		<th scope="col" width="50">#</th>
		<th scope="col" width="80">Etapa</th>
		<th scope="col">Evaluaci&oacute;n</th>
		<th scope="col" width="80">Factor</th>
		<th scope="col" width="75">Estado</th>
	</tr>
	<?php
	$sql = "SELECT
				ce.*, 
				e.Descripcion AS Evaluacion 
			FROM 
				rh_cargoevaluacion ce 
				INNER JOIN rh_evaluacion e ON (ce.Evaluacion = e.Evaluacion) 
			WHERE ce.CodCargo = '".$codcargo."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	while($field=mysql_fetch_array($query)) {
		if ($field['Estado']=="A") $status="Activo";
		elseif ($field['Estado']=="I") $status="Inactivo";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'sec');" id="<?=$field['Secuencia']?>">
			<td align="center"><?=$field['Secuencia']?></td>
			<td align="center"><?=$field['Etapa']?></td>
			<td><?=$field['Evaluacion']?></td>
			<td align="center"><?=$field['Factor']?></td>
			<td align="center"><?=$status?></td>
		</tr>
		<?
	}
	$rows=(int) $rows;
	?>
</table>

<? if ($accion!="VER") { ?>
<script type="text/javascript" language="javascript">
	totalElementos(<?=$rows?>);
</script>
<? } ?>

</form>
</body>
</html>