<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../../index.php");
//	------------------------------------
include("../fphp.php");
//	------------------------------------
if ($filtrar == "default") {
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fBuscar != "") { 
	$cBuscar = "checked"; 
	$filtro.=" AND (tt.CodOperacion LIKE '%".$fBuscar."%' OR
					tt.Descripcion LIKE '%".utf8_decode($fBuscar)."%')";
} else $dBuscar = "disabled";
if ($fTipoMovimiento != "") { $cTipoMovimiento = "checked"; $filtro.=" AND (tt.TipoMovimiento = '".$fTipoMovimiento."')"; } else $dTipoMovimiento = "disabled";
$filtro.="AND (CodOperacion <> '".$_PARAMETRO["TRANSDESPA"]."' AND CodOperacion <> '".$_PARAMETRO["TRANSRECEPCOM"]."' AND CodOperacion <> '".$_PARAMETRO["TRANSDESPACC"]."' AND CodOperacion <> '".$_PARAMETRO["TRANSRECEPCC"]."')";
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<link type="text/css" rel="stylesheet" href="../../css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../../css/estilo.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" src="../../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/fscript.js" charset="utf-8"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Listado de Tipo de Transacciones</td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="listado_tipo_transacciones.php?" method="post">
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="campo3" id="campo3" value="<?=$campo3?>" />
<input type="hidden" name="campo4" id="campo4" value="<?=$campo4?>" />
<input type="hidden" name="ventana" id="ventana" value="<?=$ventana?>" />
<input type="hidden" name="seldetalle" id="seldetalle" value="<?=$seldetalle?>" />
<div class="divBorder" style="width:900px;">
<table width="900" class="tblFiltro">
    <tr>
		<td align="right">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fBuscar');" />
			<input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:300px;" <?=$dBuscar?> />
		</td>
		<td align="right">Tipo de Movimiento:</td>
		<td>
			<input type="checkbox" <?=$cTipoMovimiento?> onclick="chkFiltro(this.checked, 'fTipoMovimiento')" />
			<select name="fTipoMovimiento" id="fTipoMovimiento" style="width:150px;" <?=$dTipoMovimiento?>>
				<option value="">&nbsp;</option>
				<?=loadSelectGeneral("TIPO-MOVIMIENTO-TRANSACCION", $fTipoMovimiento, 0)?>
			</select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="900" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
	</tr>
</table>

<div style="overflow:scroll; width:900px; height:300px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th width="50" scope="col">C&oacute;digo</th>
		<th scope="col">Descripci&oacute;n</th>
	</tr>
    </thead>
	<?php
	//	consulto todos
	$sql = "SELECT
				tt.CodOperacion,
				tt.Descripcion,
				tt.TipoMovimiento,
				tt.TipoDocGenerado,
				tt.TipoDocTransaccion
			FROM lg_operacioncommodity tt
			WHERE tt.Estado = 'A' $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				tt.CodOperacion,
				tt.Descripcion,
				tt.TipoMovimiento,
				tt.TipoDocGenerado,
				tt.TipoDocTransaccion
			FROM lg_operacioncommodity tt
			WHERE tt.Estado = 'A' $filtro
			ORDER BY TipoMovimiento, CodOperacion
			LIMIT ".intval($limit).", ".intval($maxlimit);
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		if ($grupo != $field['TipoMovimiento']) {
			$grupo = $field['TipoMovimiento'];
			?>
            <tr class="trListaBody2">
                <td colspan="2"><?=printValoresGeneral("TIPO-MOVIMIENTO-TRANSACCION", $field['TipoMovimiento'])?></td>
            </tr>
            <?
		}
		
		if ($ventana == "transaccion_almacen_sel") {
			?><tr class="trListaBody" onclick="transaccion_almacen_sel('<?=$field["CodOperacion"]?>', '<?=$field["Descripcion"]?>', '<?=$field["TipoMovimiento"]?>', '<?=$field["TipoDocGenerado"]?>', '<?=$field["TipoDocTransaccion"]?>');" id="<?=$field['CodOperacion']?>"><?
		}
		else {
			?><tr class="trListaBody" onclick="selListado2('<?=$field['CodOperacion']?>', '<?=($field["Descripcion"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['CodOperacion']?>"><?
		}
		?>
			<td align="center"><?=$field['CodOperacion']?></td>
			<td><?=($field['Descripcion'])?></td>
        </tr>
		<?
	}
	?>
</table>
</div>
<table width="900">
	<tr>
    	<td>
        	Mostrar: 
            <select name="maxlimit" style="width:50px;" onchange="this.form.submit();">
                <?=loadSelectGeneral("MAXLIMIT", $maxlimit, 0)?>
            </select>
        </td>
        <td align="right">
        	<?=paginacion(intval($rows_total), intval($rows_lista), intval($maxlimit), intval($limit));?>
        </td>
    </tr>
</table>
</center>
</form>
<script type="text/javascript" language="javascript">
	totalRegistros(parseInt(<?=$rows_lista?>));
</script>
</body>
</html>