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
<form name="frmentrada" id="frmentrada" method="post" action="cargos_funciones.php" onsubmit="return verificarCargoFunciones(this)">
<?php
include("fphp.php");
connect();
if ($accion == "NUEVO" || $accion == "VER") $disabled = "disabled";
?>

<table width="600" class="tblForm">
	<tr>
		<td>* Funci&oacute;n:</td>
    	<td>
        	<select name="funciones" id="funciones" class="selectMed" <?=$disabled?>>
                <?=getMiscelaneos('', 'FUNCION', 0);?>
            </select>
        </td>
		<td>* Estado:</td>
    	<td>
        	<select name="status" id="status" <?=$disabled?>>
                <?=getStatus('', 0);?>
            </select>
        </td>
	</tr>
	<tr>
		<td>* Comentarios:</td>
    	<td colspan="3"><textarea name="comentarios" id="comentarios" cols="100" rows="5" <?=$disabled?>></textarea></td>
	</tr>
</table>

<input type="hidden" name="inserto" id="inserto" value="INSERTAR" />
<input type="hidden" name="codigo" id="codigo" value="" />
<input type="hidden" name="sec" id="sec" value="" />
<input type="hidden" name="codcargo" id="codcargo" value="<?=$codcargo?>" />
<table width="600" class="tblBotones">
 <tr>
	<td align="right">
		<input name="btInsertar" type="submit" class="btLista" id="btInsertar" value="Insertar" <?=$disabled?> />
		<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="optCargosFunciones(this.form, 'EDITAR');" <?=$disabled?> />
		<input name="btBorrar" type="button" class="btLista" id="btBorrar" value="Borrar" onclick="optCargosFunciones(this.form, 'ELIMINAR');" <?=$disabled?> />
	</td>
 </tr>
</table>

<table width="600" class="tblLista">
	<tr class="trListaHead">
		<th scope="col" width="25">#</th>
		<th scope="col">Descripcion</th>
		<th scope="col" width="50">Est.</th>
	</tr>
	<?php
	$sw=0;
	$sql = "SELECT
				rh_cargofunciones.Funcion,
				rh_cargofunciones.CodFuncion,
				mastmiscelaneosdet.Descripcion AS NomFuncion,
				rh_cargofunciones.Descripcion,
				rh_cargofunciones.Estado
			FROM
				rh_cargofunciones,
				mastmiscelaneosdet
			WHERE
				(rh_cargofunciones.CodCargo = '".$codcargo."') AND
				(mastmiscelaneosdet.CodMaestro = 'FUNCION') AND
				(rh_cargofunciones.Funcion = mastmiscelaneosdet.CodDetalle)
			ORDER BY Funcion DESC, CodFuncion";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	while($field=mysql_fetch_array($query)) {
		if ($grupo != $field['Funcion']) {
			$grupo = $field['Funcion'];
			$generales++;
			$especificas = 0;
			?>
			<tr class="trListaBody2">
				<td align="center"><?=$generales?></td>
				<td colspan="2"><?=($field['NomFuncion'])?></td>
			</tr>
			<?
		}
		$especificas++;
		?>
		<tr class="trListaBody" onclick="mClk(this, 'sec');" id="<?=$field['CodFuncion']?>">
			<td align='right'><?=$especificas?></td>
			<td align='left'><?=($field['Descripcion'])?></td>
			<td align='center'><?=$field['Estado']?></td>
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