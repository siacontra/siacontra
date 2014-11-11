<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/fscript.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Personas</td>
		<td align="right"><a class="cerrar"; href="javascript:window.close();">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
//	CONSULTO LA TABLA PARA SABER EL TOTAL DE REGISTROS SOLAMENTE............
if ($filtro != "") $where = " AND (mp.CodPersona LIKE '%".$filtro."%' OR 
								   mp.Busqueda LIKE '%".$filtro."%' OR 
								   mp.Ndocumento LIKE '%".$filtro."%' OR 
								   mp.DocFiscal LIKE '%".$filtro."%') ";
//	-----------------------------------
if ($flagcliente == "S") $filtro_flag .= " AND mp.EsCliente = 'S' ";
elseif ($flagproveedor == "S") $filtro_flag .= " AND mp.EsProveedor = 'S' ";
elseif ($flagempleado == "S") $filtro_flag .= " AND mp.EsEmpleado = 'S' AND me.Estado = 'A' ";
elseif ($flagotros == "S") $filtro_flag .= " AND mp.EsOtros = 'S' ";
?>
<form name="frmlista" id="frmlista" action="listado_personas.php" method="post">
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="detalle" id="detalle" value="<?=$detalle?>" />
<input type="hidden" name="opcion" id="opcion" value="<?=$opcion?>" />
<input type="hidden" name="accion" id="accion" value="<?=$accion?>" />
<input type="hidden" name="php" id="php" value="<?=$php?>" />
<input type="hidden" name="flagcliente" id="flagcliente" value="<?=$flagcliente?>" />
<input type="hidden" name="flagproveedor" id="flagproveedor" value="<?=$flagproveedor?>" />
<input type="hidden" name="flagempleado" id="flagempleado" value="<?=$flagempleado?>" />
<input type="hidden" name="flagotros" id="flagotros" value="<?=$flagotros?>" />
<table width="700" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">Buscar: <input type="text" name="filtro" id="filtro" size="40" value="<?=$filtro?>" /></td>
	</tr>
</table>
<input type="hidden" name="registro" id="registro" />
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:700px; height:700px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th width="70" scope="col">Persona</th>
		<th scope="col">B&uacute;squeda</th>
		<th width="25" scope="col">Cli</th>
		<th width="25" scope="col">Pro</th>
		<th width="25" scope="col">Emp</th>
		<th width="25" scope="col">Otr</th>
		<th width="90" scope="col">Nro. Documento</th>
		<th width="90" scope="col">Documento Fiscal</th>
	</tr>
	<?php 
	//	CONSULTO LA TABLA
	$sql="SELECT
				mp.CodPersona,
				mp.NomCompleto, 
				mp.EsCliente, 
				mp.EsProveedor, 
				mp.EsEmpleado, 
				mp.EsOtros, 
				mp.Ndocumento, 
				mp.DocFiscal,
				pr.CodFormaPago,
				pr.CodTipoServicio,
				pr.CodTipoDocumento,
				pr.CodTipoPago,
				pr.DiasPago,
				mp.Busqueda,
				ts.Descripcion AS NomTipoServicio,
				i.FactorPorcentaje,
				td.CodRegimenFiscal
			FROM
				mastpersonas mp
				LEFT JOIN mastempleado me ON (mp.CodPersona = me.CodPersona)
				LEFT JOIN mastproveedores pr ON (mp.CodPersona = pr.CodProveedor)
				LEFT JOIN masttiposervicio ts ON (pr.CodTipoServicio = ts.CodTipoServicio)
				LEFT JOIN masttiposervicioimpuesto tsi ON (ts.CodTipoServicio = tsi.CodTipoServicio)
				LEFT JOIN mastimpuestos i ON (tsi.CodImpuesto = i.CodImpuesto AND i.CodRegimenFiscal = 'I')
				LEFT JOIN ap_tipodocumento td ON (td.CodTipoDocumento = pr.CodTipoDocumento)
			WHERE mp.Estado = 'A' $filtro_flag $where
			GROUP BY CodPersona
			ORDER BY CodPersona";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		if ($field['EsCliente']=="S") $escliente="checked"; else $escliente="";
		if ($field['EsProveedor']=="S") $esproveedor="checked"; else $esproveedor="";
		if ($field['EsEmpleado']=="S") $esempleado="checked"; else $esempleado="";
		if ($field['EsOtros']=="S") $esotros="checked"; else $esotros="";
		$fhasta = getFechaFin(date("d-m-Y"), $field['DiasPago']);
		
		if ($opcion == "insertarLineaListado") {
			?><tr class="trListaBody" onclick="<?=$opcion?>('<?=$accion?>', '<?=$detalle?>', '<?=$php?>', '<?=$field['CodPersona']?>');"><?
		}
		elseif ($opcion == "selListadoOC") {
			?><tr class="trListaBody" onclick="<?=$opcion?>('<?=$field['CodPersona']?>', '<?=($field["NomCompleto"])?>', '<?=$cod?>', '<?=$nom?>', '<?=$field["CodTipoServicio"]?>', '<?=($field["NomTipoServicio"])?>', '<?=$field["FactorPorcentaje"]?>', '<?=$field["CodFormaPago"]?>');"><?
		}
		elseif ($opcion == "selListadoOS") {
			?><tr class="trListaBody" onclick="<?=$opcion?>('<?=$field['CodPersona']?>', '<?=($field["NomCompleto"])?>', '<?=$cod?>', '<?=$nom?>', '<?=$field["CodTipoServicio"]?>', '<?=($field["NomTipoServicio"])?>', '<?=$field["FactorPorcentaje"]?>', '<?=$field["CodFormaPago"]?>', '<?=$field["CodTipoPago"]?>', '<?=$field["DiasPago"]?>', '<?=$fhasta?>');"><?
		}
		else {
			?><tr class="trListaBody" onclick="selListado('<?=$field['CodPersona']?>', '<?=($field["NomCompleto"])?>', '<?=$cod?>', '<?=$nom?>');"><?
		}
		?>
        <td align="center"><?=$field["CodPersona"]?></td>
        <td align="left"><?=($field["NomCompleto"])?></td>
        <td align="center"><input type="checkbox" <?=$escliente?> disabled /></td>
        <td align="center"><input type="checkbox" <?=$esproveedor?> disabled /></td>
        <td align="center"><input type="checkbox" <?=$esempleado?> disabled /></td>
        <td align="center"><input type="checkbox" <?=$esotros?> disabled /></td>
        <td align="left"><?=$field["Ndocumento"]?></td>
        <td align="left"><?=$field["DocFiscal"]?></td>
        </tr>
        <?
	}
	?>
</table>
</div></td></tr></table>
</form>
<script type="text/javascript" language="javascript">
	totalRegistros(parseInt(<?=$rows?>));
</script>
</body>
</html>