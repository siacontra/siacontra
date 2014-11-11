<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../../index.php");
//	------------------------------------
include("../fphp.php");
//	------------------------------------
if ($filtrar == "default") {
	$fordenar = "p.CodPersona";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
$filtro = "";
if ($fordenar != "") { $cordenar = "checked"; $orderby = "ORDER BY $fordenar"; } else $dordenar = "disabled";
if ($fbuscar != "") {
	$cbuscar = "checked";
	$filtro.=" AND (p.CodPersona LIKE '%".$fbuscar."%' OR 
					p.NomCompleto LIKE '%".$fbuscar."%' OR 
					p.Ndocumento LIKE '%".$fbuscar."%' OR 
					p.DocFiscal LIKE '%".$fbuscar."%')";
} else $dbuscar = "disabled";
//	------------------------------------
$filtro_flag = "";
if ($EsCliente == "S") { 
	$filtro_flag .= " AND (p.EsCliente = 'S' ";
}
if ($EsProveedor == "S") {
	if ($filtro_flag == "") $filtro_flag .= " AND (p.EsProveedor = 'S' ";
	else $filtro_flag .= " OR p.EsProveedor = 'S' ";
}
if ($EsEmpleado == "S") {
	if ($filtro_flag == "") $filtro_flag .= " AND (p.EsEmpleado = 'S' ";
	else $filtro_flag .= " OR p.EsEmpleado = 'S' ";
	$filtro_estado = " AND e.Estado = 'A'";
}
if ($EsOtros == "S") {
	if ($filtro_flag == "") $filtro_flag .= " AND (p.EsOtros = 'S' ";
	else $filtro_flag .= " OR p.EsOtros = 'S' ";
}
if ($filtro_flag != "") $filtro_flag = "$filtro_flag)";
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
		<td class="titulo">Listado de Personas</td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="listado_personas.php?" method="post">
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="campo3" id="campo3" value="<?=$campo3?>" />
<input type="hidden" name="campo4" id="campo4" value="<?=$campo4?>" />
<input type="hidden" name="ventana" id="ventana" value="<?=$ventana?>" />
<input type="hidden" name="seldetalle" id="seldetalle" value="<?=$seldetalle?>" />
<input type="hidden" name="EsEmpleado" id="EsEmpleado" value="<?=$EsEmpleado?>" />
<input type="hidden" name="EsProveedor" id="EsProveedor" value="<?=$EsProveedor?>" />
<input type="hidden" name="EsOtros" id="EsOtros" value="<?=$EsOtros?>" />
<input type="hidden" name="EsCliente" id="EsCliente" value="<?=$EsCliente?>" />
<input type="hidden" name="marco" id="marco" value="<?=$marco?>" />
<div class="divBorder" style="width:900px;">
<table width="900" class="tblFiltro">
	<tr>
		<td align="right" width="125">Buscar:</td>
        <td>
            <input type="checkbox" <?=$cbuscar?> onclick="chkFiltro(this.checked, 'fbuscar');" />
            <input type="text" name="fbuscar" id="fbuscar" style="width:200px;" value="<?=$fbuscar?>" <?=$dbuscar?> />
		</td>
		<td align="right" width="125">Ordenar Por:</td>
		<td>
            <input type="checkbox" <?=$cordenar?> onclick="this.checked=!this.checked;" />
            <select name="fordenar" id="fordenar" style="width:100px;" <?=$dordenar?>>
                <?=loadSelectGeneral("ORDENAR-PERSONA", $fordenar, 0)?>
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

<div style="overflow:scroll; width:900px; height:250px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th scope="col" width="55">Persona</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col" width="25">Emp.</th>
		<th scope="col" width="25">Pro.</th>
		<th scope="col" width="25">Cli.</th>
		<th scope="col" width="25">Otr.</th>
		<th scope="col" width="100">Nro. Documento</th>
		<th scope="col" width="100">Doc. Fiscal</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos	
	$sql = "SELECT
				p.CodPersona,
				p.NomCompleto,
				p.EsEmpleado,
				p.EsProveedor,
				p.EsCliente,
				p.EsOtros,
				p.TipoPersona,
				p.Ndocumento,
				p.DocFiscal,
				p.Estado
			FROM
				mastpersonas p
				LEFT JOIN mastempleado e ON (p.CodPersona = e.CodPersona $filtro_estado)
			WHERE p.Estado = 'A' $filtro $filtro_flag";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				p.CodPersona,
				p.NomCompleto,
				p.EsEmpleado,
				p.EsProveedor,
				p.EsCliente,
				p.EsOtros,
				p.TipoPersona,
				p.Ndocumento,
				p.DocFiscal,
				p.Estado
			FROM
				mastpersonas p
				LEFT JOIN mastempleado e ON (p.CodPersona = e.CodPersona $filtro_estado)
			WHERE p.Estado = 'A' $filtro $filtro_flag
			$orderby
			LIMIT ".intval($limit).", $maxlimit";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		if ($ventana == "selListadoObligacionPersona") {
			?><tr class="trListaBody" onclick="selListadoObligacionPersona('<?=$field['CodPersona']?>');" id="<?=$field['CodPersona']?>"><? 
		}
		elseif ($ventana == "selListadoOrdenCompraPersona") {
			?><tr class="trListaBody" onclick="selListadoOrdenCompraPersona('<?=$field['CodPersona']?>');" id="<?=$field['CodPersona']?>"><? 
		}
		elseif ($ventana == "selListadoOrdenServicioPersona") {
			?><tr class="trListaBody" onclick="selListadoOrdenServicioPersona('<?=$field['CodPersona']?>');" id="<?=$field['CodPersona']?>"><? 
		}
		elseif ($ventana == "selListadoLista") {
			?><tr class="trListaBody" onclick="selListadoLista('<?=$seldetalle?>', '<?=$field["CodPersona"]?>', '<?=$field["NomCompleto"]?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['CodPersona']?>"><?
		}
		elseif ($ventana == "requerimiento") {
			?><tr class="trListaBody" onclick="selListado2('<?=$field["CodPersona"]?>', '<?=$field["NomCompleto"]?>', '<?=$cod?>', '<?=$nom?>', '<?=$field["DocFiscal"]?>', 'ProveedorDocRef');" id="<?=$field['CodPersona']?>"><?
		}
		elseif ($ventana == "registro_compra_form") {
			?><tr class="trListaBody" onclick="selListado2('<?=$field['CodPersona']?>', '<?=($field["NomCompleto"])?>', '<?=$cod?>', '<?=$nom?>', '<?=($field["DocFiscal"])?>', '<?=$campo3?>');" id="<?=$field['CodPersona']?>"><? 
		}
		elseif($ventana == "selListadoIFrame") {
			?><tr class="trListaBody" onclick="selListadoIFrame('<?=$marco?>', '<?=$field['CodPersona']?>', '<?=($field["NomCompleto"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['CodPersona']?>"><?
		}
		else {
			?><tr class="trListaBody" onclick="selListado2('<?=$field['CodPersona']?>', '<?=($field["NomCompleto"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['CodPersona']?>"><? 
		}
        ?>
			<td align="center"><?=$field['CodPersona']?></td>
			<td><?=($field['NomCompleto'])?></td>
			<td align="center"><?=printFlag($field['EsEmpleado'])?></td>
			<td align="center"><?=printFlag($field['EsProveedor'])?></td>
			<td align="center"><?=printFlag($field['EsCliente'])?></td>
			<td align="center"><?=printFlag($field['EsOtros'])?></td>
			<td><?=$field['Ndocumento']?></td>
			<td><?=$field['DocFiscal']?></td>
		</tr>
		<?
	}
	?>
    </tbody>
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