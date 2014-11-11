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
<form name="frmentrada" id="frmentrada" method="post" action="pcompetencias_plantilla.php" onsubmit="return verificarPlantillaCompetencia(this)">
<?php
include("fphp.php");
connect();
if ($accion=="NUEVO") $disabled="disabled";
?>

<table width="850" class="tblForm">
	<tr>
		<td class="tagForm" width="100">Factor:</td>
		<td>
			<input name="codcompetencia" type="hidden" id="codcompetencia" />
			<input name="nomcompetencia" type="text" id="nomcompetencia" size="75" readonly />*
			<input type="button" name="btBrowse" id="btBrowse" value="..." onclick="cargarVentana(this.form, 'lista_competencias.php?limit=0', 'height=500, width=800, left=200, top=200, resizable=yes');" <?=$disabled?> />
		</td>
		<td class="tagForm">Peso:</td>
		<td><input name="peso" type="text" id="peso" size="10" maxlength="5" <?=$disabled?> />*</td>
		<td class="tagForm">Orden Factor:</td>
		<td><input name="orden" type="text" id="orden" size="10" maxlength="2" <?=$disabled?> />*</td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td colspan="5">
			<input type="checkbox" name="potencial" id="potencial" value="S" <?=$disabled?> /> Potencial &nbsp;&nbsp;&nbsp;&nbsp;
			<input type="checkbox" name="competencia" id="competencia" value="S" <?=$disabled?> /> Competencia &nbsp;&nbsp;
			<input type="checkbox" name="conceptual" id="conceptual" value="S" <?=$disabled?> /> Conceptual
		</td>
		
	</tr>
</table>

<input type="hidden" name="inserto" id="inserto" value="NUEVO" />
<input type="hidden" name="sec" id="sec" value="" />
<input type="hidden" name="plantilla" id="plantilla" value="<?=$plantilla?>" />
<table width="850" class="tblBotones">
 <tr>
	<td align="right">
		<input name="btInsertar" type="submit" class="btLista" id="btInsertar" value="Insertar" <?=$disabled?> />
		<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="optCompetencia(this.form, 'EDITAR');" />
		<input name="btBorrar" type="button" class="btLista" id="btBorrar" value="Borrar" onclick="optCompetencia(this.form, 'ELIMINAR');" />
	</td>
 </tr>
</table>

<table width="850" class="tblLista">
	<tr class="trListaHead">
		<th scope="col" width="75" align="center">Comp.</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col" width="125">Nivel</th>
		<th scope="col" width="50">Peso</th>
		<th scope="col" width="50">Orden Present.</th>
		<th scope="col" width="75">Potencial</th>
		<th scope="col" width="75">Competencia</th>
		<th scope="col" width="75">Conceptual</th>
	</tr>
	<?php
	$sql="SELECT fvp.*, ef.Descripcion AS NomCompetencia, md.Descripcion AS NomNivel FROM rh_factorvalorplantilla fvp INNER JOIN rh_evaluacionfactores ef ON (fvp.Competencia=ef.Competencia) INNER JOIN mastmiscelaneosdet md ON (ef.Nivel=md.CodDetalle AND md.CodMaestro='NIVELCOMPE') WHERE fvp.Plantilla='".$plantilla."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	while($field=mysql_fetch_array($query)) {
		if ($field['FlagPotencial']=="S") $potencial="checked"; else $potencial="";
		if ($field['FlagCompetencia']=="S") $competencia="checked"; else $competencia="";
		if ($field['FlagConceptual']=="S") $conceptual="checked"; else $conceptual="";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'sec');" id="<?=$field['Competencia']?>">
			<td align="center"><?=$field['Competencia']?></td>
			<td><?=$field['NomCompetencia']?></td>
			<td><?=$field['NomNivel']?></td>
			<td align="right"><?=$field['Peso']?></td>
			<td align="right"><?=$field['OrdenFactor']?></td>
			<td align="center"><input type="checkbox" disabled="disabled" <?=$potencial?> /></td>
			<td align="center"><input type="checkbox" disabled="disabled" <?=$competencia?> /></td>
			<td align="center"><input type="checkbox" disabled="disabled" <?=$conceptual?> /></td>
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