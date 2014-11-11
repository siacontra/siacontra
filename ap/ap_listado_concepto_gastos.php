<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
extract($_POST);
extract($_GET);
//	------------------------------------
include("fphp_ap.php");
connect();
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_ap.js"></script>
</head>

<body onload="document.getElementById('filtro').focus();">
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Concepto de Gastos</td>
		<td align="right"><a class="cerrar" href="#" onclick="window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="ap_listado_concepto_gastos.php" method="POST">
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="ventana" id="ventana" value="<?=$ventana?>" />
<table width="750" class="tblBotones">
	<tr>
		<td align="right">
			Grupo: 
            <select name="fgrupo" id="fgrupo" style="width:300px;" onchange="this.form.submit();">
            	<option value="">&nbsp;</option>
                <?=loadSelect("ap_conceptogastogrupo", "CodGastoGrupo", "Descripcion", $fgrupo, 0);?>
            </select>
		</td>
		<td align="right">
			Filtro: <input type="text" name="filtro" id="filtro" value="<?=$filtro?>" size="30" />
		</td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:750px; height:500px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th width="75" scope="col">Concepto</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="90" scope="col">Partida</th>
		<th width="70" scope="col">Cuenta</th>
		<th width="60" scope="col">Estado</th>
	</tr>
	<?php
	$filtro = trim($filtro);
	$where = "";
	if ($filtro != "") 
		$where .= "AND (cg.CodConceptoGasto LIKE '%".$filtro."%' OR
						 cg.Descripcion LIKE '%".$filtro."%' OR
						 cg.CodPartida LIKE '%".$filtro."%' OR
						 cg.CodCuenta LIKE '%".$filtro."%' OR
						 cg.CodGastoGrupo LIKE '%".$filtro."%')";
	if ($fgrupo != "") $where .= "AND (cg.CodGastoGrupo = '".$fgrupo."')";
	//	CONSULTO LA TABLA
	$sql = "SELECT
				cg.*,
				cgg.Descripcion AS NomGastoGrupo,
				p.denominacion AS NomPartida,
				pc.Descripcion AS NomCuenta
			FROM
				ap_conceptogastos cg
				INNER JOIN ap_conceptogastogrupo cgg ON (cg.CodGastoGrupo = cgg.CodGastoGrupo)
				LEFT JOIN pv_partida p ON (cg.CodPartida = p.cod_partida)
				LEFT JOIN ac_mastplancuenta pc ON (cg.CodCuenta = pc.CodCuenta)
			WHERE 1 $where
			ORDER BY CodGastoGrupo, CodConceptoGasto";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		if ($grupo != $field['CodGastoGrupo']) {
			$grupo = $field['CodGastoGrupo'];
			?>
            <tr class="trListaBody2">
                <td align="center"><?=$field['CodGastoGrupo']?></td>
                <td><?=($field['NomGastoGrupo'])?></td>
            </tr>
            <?
		}
		if ($ventana == "selListadoConceptoDistribucion") {
			?>
			<tr class="trListaBody" onclick="mClk(this, 'registro'); selListadoConceptoDistribucion('<?=($field["Descripcion"])?>', '<?=$cod?>', '<?=$nom?>', '<?=$field["CodPartida"]?>', '<?=($field["NomPartida"])?>', '<?=$field["CodCuenta"]?>', '<?=($field["NomCuenta"])?>');" id="<?=$field['CodConceptoGasto']?>">
				<td align="center"><?=$field['CodConceptoGasto']?></td>
				<td><?=($field['Descripcion'])?></td>
				<td align="center"><?=$field['CodPartida']?></td>
				<td align="center"><?=$field['CodCuenta']?></td>
				<td align="center"><?=printValores("ESTADO", $field['Estado'])?></td>
			</tr>
			<?
		}
		else {
			?>
			<tr class="trListaBody" onclick="mClk(this, 'registro'); selListado('<?=($field["Descripcion"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['CodConceptoGasto']?>">
				<td align="center"><?=$field['CodConceptoGasto']?></td>
				<td><?=($field['Descripcion'])?></td>
				<td align="center"><?=$field['CodPartida']?></td>
				<td align="center"><?=$field['CodCuenta']?></td>
				<td align="center"><?=printValores("ESTADO", $field['Estado'])?></td>
			</tr>
			<?
		}
	}
	?>
</table>
</div></td></tr></table>
</form>
</body>
</html>