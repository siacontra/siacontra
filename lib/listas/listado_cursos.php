<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../../index.php");
//	------------------------------------
include("../fphp.php");
//	------------------------------------
if ($filtrar == "default") {
	$fEstado = "A";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fEstado != "") { $cEstado = "checked"; $filtro.=" AND (c.Estado = '".$fEstado."')"; } else $dEstado = "disabled";
if ($fBuscar != "") {
	$cBuscar = "checked";
	$filtro.=" AND (c.CodCurso LIKE '%".$fBuscar."%' OR
					c.Descripcion LIKE '%".$fBuscar."%')";
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
		<td class="titulo">Listado de Cursos</td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="listado_cursos.php?" method="post">
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="campo3" id="campo3" value="<?=$campo3?>" />
<input type="hidden" name="campo4" id="campo4" value="<?=$campo4?>" />
<input type="hidden" name="ventana" id="ventana" value="<?=$ventana?>" />
<input type="hidden" name="seldetalle" id="seldetalle" value="<?=$seldetalle?>" />
<input type="hidden" name="marco" id="marco" value="<?=$marco?>" />
<div class="divBorder" style="width:100%;">
<table width="100%" class="tblFiltro">
	<tr>
		<td align="right" width="125">Buscar:</td>
        <td>
            <input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fBuscar');" />
            <input type="text" name="fBuscar" id="fBuscar" style="width:200px;" value="<?=$fBuscar?>" <?=$dBuscar?> />
		</td>
		<td align="right" width="125">Estado Reg.:</td>
		<td>
            <input type="checkbox" <?=$cEstado?> onclick="chkFiltro(this.checked, 'fEstado');" />
            <select name="fEstado" id="fEstado" style="width:100px;" <?=$dEstado?>>
                <option value="">&nbsp;</option>
                <?=loadSelectGeneral("ESTADO", $fEstado, 0)?>
            </select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="100%" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
	</tr>
</table>

<div style="overflow:scroll; width:100%; height:300px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th scope="col" width="25">&nbsp;</th>
		<th scope="col" width="85">C&oacute;digo</th>
		<th scope="col">Descripci&oacute;n</th>
	</tr>
    </thead>
	<?php
	//	consulto todos	
	$sql = "SELECT
				c.CodCurso,
				c.Descripcion
			FROM rh_cursos c
			WHERE c.Estado = 'A' $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				c.CodCurso,
				c.Descripcion
			FROM rh_cursos c
			WHERE c.Estado = 'A' $filtro
			ORDER BY Descripcion
			LIMIT ".intval($limit).", $maxlimit";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		if ($ventana == "selListadoLista") {
			?><tr class="trListaBody" onclick="selListadoLista('<?=$seldetalle?>', '<?=$field["CodCurso"]?>', '<?=$field["Descripcion"]?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['CodCurso']?>"><?
		}
		elseif($ventana == "selListadoIFrame") {
			?><tr class="trListaBody" onclick="selListadoIFrame('<?=$marco?>', '<?=$field['CodCurso']?>', '<?=($field["Descripcion"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['CodCurso']?>"><?
		}
		else {
			?><tr class="trListaBody" onclick="selListado2('<?=$field['CodCurso']?>', '<?=($field["Descripcion"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['CodCurso']?>"><?
		}
		?>
            <th><?=++$i?></td>
            <td align="center"><?=$field['CodCurso']?></td>
			<td><?=($field['Descripcion'])?></td>
        </tr>
		<?
	}
	?>
</table>
</div>
<table width="100%">
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