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
<form name="frmentrada" id="frmentrada" method="post" action="cargos_habilidades.php" onsubmit="return verificarCargosHabilidades(this)">
<?php
include("fphp.php");
connect();
if ($accion=="NUEVO") $disabled="disabled";
?>

<table width="600" class="tblForm">
	<tr>
		<td>* Tipo:</td>
    	<td>
        	<select name="tipo" id="tipo">
            	<option value=""></option>
                <?=getTipoHabilidad("", 0);?>
            </select>
        </td>
	</tr>
	<tr>
		<td>* Descripci&oacute;n:</td>
    	<td><textarea name="descripcion" id="descripcion" cols="100" rows="2" <?=$disabled?>></textarea></td>
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
		<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="optCargosHabilidades(this.form, 'EDITAR');" />
		<input name="btBorrar" type="button" class="btLista" id="btBorrar" value="Borrar" onclick="optCargosHabilidades(this.form, 'ELIMINAR');" />
	</td>
 </tr>
</table>

<table width="600" class="tblLista">
	<tr class="trListaHead">
		<th scope="col">Descripcion</th>
	</tr>
	<?php
	$sw=0;
	$sql = "SELECT *
			FROM rh_cargohabilidades
			WHERE CodCargo = '".$codcargo."'
			ORDER BY Tipo, Secuencia";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	while($field=mysql_fetch_array($query)) {
		if ($field['Tipo'] == "D") $tipo="Destreza";
		elseif ($field['Tipo'] == "H") $tipo="Habilidades";
		
		if ($grupo != $field['Tipo']) {
			$grupo = $field['Tipo'];
			?>
            <tr class="trListaBody2">
            	<td><?=($tipo)?></td>
            </tr>
            <?
		}
		
		?>
		<tr class="trListaBody" onclick="mClk(this, 'sec');" id="<?=$field['Secuencia']?>">
			<td><?=($field['Descripcion'])?></td>
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