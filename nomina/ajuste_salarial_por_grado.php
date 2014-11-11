<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_nomina.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('05', $concepto);
//	------------------------------------
if ($filtrar == "DEFAULT") {
	$filtro = "WHERE ns.CategoriaCargo = '01'";
	$fcategoria = "01";
} else {
	$filtro = "WHERE ns.CategoriaCargo = '".$fcategoria."'";
}
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
		<td class="titulo">Ajuste Salarial por Grado</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="ajuste_salarial_por_grado.php" method="POST">
<div class="divBorder" style="width:800px;">
<table width="800" class="tblFiltro">
    <tr>
        <td align="right">Categoria del Grado:</td>
        <td>
        	<input type="checkbox" name="chkcategoria" id="chkcategoria" value="1" onclick="forzarCheck('chkcategoria');" checked="checked" />
			<select name="fcategoria" id="fcategoria" style="width:250px;">
				<?=getMiscelaneos($fcategoria, "CATCARGO", 0)?>
			</select>
        </td>
        <td align="right"><input type="submit" name="btBuscar" value="Mostrar Grados Salariales"></td>
    </tr>
</table>
</div>
</form>
<br />

<form name="frmelementos" id="frmelementos">
<table width="800" class="tblBotones">
    <tr>
    	<td>Periodo: <input type="text" name="periodo" id="periodo" size="10" maxlength="7" value="<?=date("Y-m")?>" /></td>
        <td align="right">
        	<input name="btGuardar" type="button" id="btGuardar" value="Guardar Todo" onclick="validarAjusteSalarial(this.form);" />
        </td>
    </tr>
</table>

<table width="800" class="tblLista">
	<tr class="trListaHead">
		<th width="25" scope="col">&nbsp;</th>
		<th width="75" scope="col">Grado</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="90" scope="col">Sueldo Actual</th>
		<th width="90" scope="col">Porcentaje Aumentar</th>
		<th width="90" scope="col">Monto Aumentar</th>
		<th width="90" scope="col">Sueldo Nuevo</th>
	</tr>
	<?php
	//	CONSULTO LA TABLA
	$sql="SELECT 
				ns.*, 
				md.Descripcion AS Categoria 
			FROM 
				rh_nivelsalarial ns 
				INNER JOIN mastmiscelaneosdet md ON (ns.CategoriaCargo = md.CodDetalle AND CodMaestro = 'CATCARGO')
			$filtro
			ORDER BY ns.CategoriaCargo, ns.Grado";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		if ($field['Estado']=="A") $status="Activo";
		elseif ($field['Estado']=="I") $status="Inactivo";
		
		if ($grupo != $field['CategoriaCargo']) {
			$grupo = $field['CategoriaCargo'];
			?><tr class="trListaBody2"><td>&nbsp;</td><td colspan="7"><?=$field['Categoria']?></td></tr><?
		}
		?>
		<tr class="trListaBody" id="<?=$field['CodNivel']?>">
        	<td align="center"><input type="checkbox" name="grados" id="<?=$field['Grado']?>" onclick="enabledAjusteSalarial(this.id, this.checked);" /></td>
			<td align="center"><?=$field['Grado']?></td>
			<td><?=($field['Descripcion'])?></td>
			<td align="center"><input type="text" name="actual" id="A_<?=$field['Grado']?>" style="width:95%;" dir="rtl" value="<?=number_format($field['SueldoPromedio'], 2, ',', '.')?>" disabled="disabled" /></td>
			<td align="center"><input type="text" name="porcentaje" id="P_<?=$field['Grado']?>" dir="rtl" style="width:95%;" onchange="setAjusteSalarial('P', '<?=$field['Grado']?>', <?=$field['SueldoPromedio']?>)" disabled="disabled" /></td>
			<td align="center"><input type="text" name="monto" id="M_<?=$field['Grado']?>" style="width:95%;" onchange="setAjusteSalarial('M', '<?=$field['Grado']?>', <?=$field['SueldoPromedio']?>)" disabled="disabled" /></td>
			<td align="center"><input type="text" name="nuevo" id="N_<?=$field['Grado']?>" dir="rtl" style="width:95%; font-weight:bold;" disabled="disabled" /></td>
		</tr>
		<?
	}
	?>
</table>
</form>


</body>
</html>
