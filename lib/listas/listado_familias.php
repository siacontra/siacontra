<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../../index.php");
//	------------------------------------
extract($_POST);
extract($_GET);
//	------------------------------------
include("../fphp.php");
//	------------------------------------
if ($filtrar == "default") {
	$maxlimit = $_SESSION["MAXLIMIT"];
	$fEstado = "A";
}
if ($fCodLinea != "") { $cCodLinea = "checked"; $filtro.=" AND (csf.CodLinea = '".$fCodLinea."')"; } else $dCodLinea = "disabled";
if ($fCodFamilia != "") { $cCodFamilia = "checked"; $filtro.=" AND (csf.CodFamilia = '".$fCodFamilia."')"; } else $dCodFamilia = "disabled";
if ($fBuscar != "") { 
	$cBuscar = "checked"; 
	$filtro.=" AND (csf.CodFamilia LIKE '%".$fBuscar."%' OR
					csf.Descripcion LIKE '%".utf8_decode($fBuscar)."%' OR
					cf.Descripcion LIKE '%".utf8_decode($fBuscar)."%' OR
					cl.Descripcion LIKE '%".utf8_decode($fBuscar)."%')";
} else $dBuscar = "disabled";
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
		<td class="titulo">Listado de Familias</td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="listado_familias.php?" method="post">
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
		<td align="right">Linea:</td>
		<td>
            <input type="checkbox" <?=$cCodLinea?> onclick="chkFiltro(this.checked, 'fCodLinea');" />
            <select name="fCodLinea" id="fCodLinea" style="width:255px;" <?=$dCodLinea?> onChange="getOptionsSelect(this.value, 'familia', 'fCodFamilia', true);">
                <option value="">&nbsp;</option>
                <?=loadSelect("lg_claselinea", "CodLinea", "Descripcion", $fCodLinea, 0)?>
            </select>
		</td>
		<td align="right">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fBuscar');" />
			<input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:250px;" <?=$dBuscar?> />
		</td>
	</tr>
	<tr>
		<td align="right">Familia:</td>
		<td>
            <input type="checkbox" <?=$cCodFamilia?> onclick="chkFiltro(this.checked, 'fCodFamilia');" />
            <select name="fCodFamilia" id="fCodFamilia" style="width:255px;" <?=$dCodFamilia?>>
                <option value="">&nbsp;</option>
                <?=loadSelectDependiente("lg_clasefamilia", "CodFamilia", "Descripcion", "CodLinea", $fCodFamilia, $fCodLinea, 0)?>
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
		<th width="100" scope="col">Sub-Familia</th>
		<th scope="col">Descripci&oacute;n</th>
	</tr>
    </thead>
	<?php
	//	consulto todos
	$sql = "SELECT
				csf.*,
				cf.Descripcion AS NomFamilia,
				cl.Descripcion AS NomLinea
			FROM
				lg_clasesubfamilia csf
				INNER JOIN lg_clasefamilia cf ON (csf.CodFamilia = cf.CodFamilia AND csf.CodLinea = cf.CodLinea)
				INNER JOIN lg_claselinea cl ON (csf.CodLinea = cl.CodLinea)
			WHERE csf.Estado = 'A' $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				csf.*,
				cf.Descripcion AS NomFamilia,
				cl.Descripcion AS NomLinea
			FROM
				lg_clasesubfamilia csf
				INNER JOIN lg_clasefamilia cf ON (csf.CodFamilia = cf.CodFamilia AND csf.CodLinea = cf.CodLinea)
				INNER JOIN lg_claselinea cl ON (csf.CodLinea = cl.CodLinea)
			WHERE csf.Estado = 'A' $filtro
			ORDER BY csf.Codlinea, csf.CodFamilia, csf.CodSubFamilia
			LIMIT ".intval($limit).", ".intval($maxlimit);
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[CodLinea].$field[CodFamilia].$field[CodSubFamilia]";
		if ($grupo1 != $field['CodLinea']) {
			$grupo1 = $field['CodLinea'];
			$grupo2 = "";
			?>
			<tr class="trListaBody2">
				<td align="center"><?=$field['CodLinea']?></td>
				<td><?=($field['NomLinea'])?></td>
			</tr>
			<?
		}		
		if ($grupo2 != $field['CodFamilia']) {
			$grupo2 = $field['CodFamilia'];
			?>
			<tr class="trListaBody3">
				<td align="center"><?=$field['CodFamilia']?></td>
				<td><?=($field['NomFamilia'])?></td>
			</tr>
			<?
		}
		?>
		<tr class="trListaBody" onclick="selListado2('<?=$field['CodLinea']?>', '<?=($field["CodFamilia"])?>', '<?=$cod?>', '<?=$nom?>', '<?=($field["CodSubFamilia"])?>', '<?=$campo3?>');" id="<?=$id?>">
			<td align="center"><?=$field['CodSubFamilia']?></td>
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
	totalRegistros(parseInt(<?=$rows_total?>));
</script>
</body>
</html>