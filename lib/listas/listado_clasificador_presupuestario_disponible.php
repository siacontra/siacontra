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
	$fordenar = "p.cod_partida";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
$filtro.=" AND (p.Estado = 'A')";
if ($fordenar != "") { $cordenar = "checked"; $orderby = "ORDER BY $fordenar"; } else $dordenar = "disabled";
if ($fbuscar != "") {
	$cbuscar = "checked";
	$filtro.=" AND (p.cod_partida LIKE '%".$fbuscar."%' OR 
					p.denominacion LIKE '%".$fbuscar."%')";
} else $dbuscar = "disabled";
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
		<td class="titulo">Listado Clasificador Presuestario</td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="listado_clasificador_presupuestario_disponible.php?" method="post">
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="campo3" id="campo3" value="<?=$campo3?>" />
<input type="hidden" name="campo4" id="campo4" value="<?=$campo4?>" />
<input type="hidden" name="campo5" id="campo5" value="<?=$campo5?>" />
<input type="hidden" name="campo6" id="campo6" value="<?=$campo6?>" />
<input type="hidden" name="ventana" id="ventana" value="<?=$ventana?>" />
<input type="hidden" name="seldetalle" id="seldetalle" value="<?=$seldetalle?>" />
<input type="hidden" name="CodOrganismo" id="CodOrganismo" value="<?=$CodOrganismo?>" />
<div class="divBorder" style="width:800px;">
<table width="800" class="tblFiltro">
	<tr>
		<td align="right" width="125">Buscar:</td>
        <td>
            <input type="checkbox" <?=$cbuscar?> onclick="chkFiltro(this.checked, 'fbuscar');" />
            <input type="text" name="fbuscar" id="fbuscar" style="width:200px;" value="<?=$fbuscar?>" <?=$dbuscar?> />
		</td>
		<td align="right">Ordenar Por:</td>
		<td>
            <input type="checkbox" <?=$cordenar?> onclick="this.checked=!this.checked;" />
            <select name="fordenar" id="fordenar" style="width:100px;" <?=$dordenar?>>
                <?=loadSelectGeneral("ORDENAR-CLASIFICADOR", $fordenar, 0)?>
            </select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="800" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
	</tr>
</table>

<div style="overflow:scroll; width:800px; height:300px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th scope="col" width="100">C&oacute;digo</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col" width="75">Estado</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos	
	$sql = "SELECT p.cod_partida
			FROM
				pv_partida p
				INNER JOIN pv_presupuestodet pvd ON (pvd.cod_partida = p.cod_partida)
				INNER JOIN pv_presupuesto pv ON (pv.Organismo = pvd.Organismo AND
												 pv.CodPresupuesto = pvd.CodPresupuesto AND
												 pv.EjercicioPpto = '".substr($Ahora, 0, 4)."')
				LEFT JOIN ac_mastplancuenta pc ON (p.CodCuenta = pc.CodCuenta)
				LEFT JOIN ac_mastplancuenta20 pc20 ON (p.CodCuentaPub20 = pc20.CodCuenta)
			WHERE
				pv.Organismo = '".$CodOrganismo."' AND
				pv.EjercicioPpto = '".substr($Ahora, 0, 4)."'
				$filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				p.*,
				pc.Descripcion,
				pc20.Descripcion AS NomCuentaPub20
			FROM
				pv_partida p
				INNER JOIN pv_presupuestodet pvd ON (pvd.cod_partida = p.cod_partida)
				INNER JOIN pv_presupuesto pv ON (pv.Organismo = pvd.Organismo AND
												 pv.CodPresupuesto = pvd.CodPresupuesto AND
												 pv.EjercicioPpto = '".substr($Ahora, 0, 4)."')
				LEFT JOIN ac_mastplancuenta pc ON (p.CodCuenta = pc.CodCuenta)
				LEFT JOIN ac_mastplancuenta20 pc20 ON (p.CodCuentaPub20 = pc20.CodCuenta)
			WHERE
				pv.Organismo = '".$CodOrganismo."' AND
				pv.EjercicioPpto = '".substr($Ahora, 0, 4)."'
				$filtro
			$orderby
			LIMIT ".intval($limit).", $maxlimit";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		if ($ventana == "listadoPartidas") {
			?><tr class="trListaBody" onclick="listadoPartidas('<?=$field["cod_partida"]?>', '<?=$seldetalle?>');" id="<?=$field['cod_partida']?>"><?
		} 
		elseif ($ventana == "selListadoLista") {
			?><tr class="trListaBody" onclick="selListadoLista('<?=$seldetalle?>', '<?=$field["cod_partida"]?>', '<?=$field["denominacion"]?>', '<?=$cod?>', '<?=$nom?>', '<?=$field["CodCuenta"]?>', '<?=$campo3?>', '<?=$field["Descripcion"]?>', '<?=$campo4?>', '<?=$field["CodCuentaPub20"]?>', '<?=$campo5?>', '<?=$field["NomCuentaPub20"]?>', '<?=$campo6?>');" id="<?=$field['cod_partida']?>"><?
		} 
		else {
			?><tr class="trListaBody" onclick="selListado2('<?=$field['cod_partida']?>', '<?=($field["denominacion"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['cod_partida']?>"><?
		}
		?>
        <td align="center"><?=$field['cod_partida']?></td>
        <td><?=($field['denominacion'])?></td>
        <td align="center"><?=printValoresGeneral("ESTADO", $field['Estado'])?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
<table width="800">
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
