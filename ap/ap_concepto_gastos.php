<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_ap.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('07', $concepto);
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
		<td class="titulo">Concepto de Gastos</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="ap_concepto_gastos.php" method="POST">
<table width="900" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">Filtro: <input type="text" name="filtro" id="filtro" value="<?=$filtro?>" size="30" /></td>
		<td align="right">
			<input type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'ap_concepto_gastos_form.php?accion=INSERTAR');" />
			<input type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'ap_concepto_gastos_form.php?accion=ACTUALIZAR', 'SELF');" />
			<input type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'ap_concepto_gastos_form.php?accion=VER', 'BLANK', 'height=300, width=750, left=150, top=150, resizable=no');" />
			<input type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="opcionRegistro(this.form, document.getElementById('registro').value, 'CLASIFICACION-GASTOS', 'ELIMINAR');" />
			<!--<input type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'ap_concepto_gastos_pdf.php', 'height=900, width=900, left=200, top=200, resizable=yes');" />-->
		</td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:900px; height:400px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th width="100" scope="col">Concepto</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="100" scope="col">Partida</th>
		<th width="100" scope="col">Cta. Contable</th>
		<th width="75" scope="col">Estado</th>
	</tr>
	<?php
	$filtro = trim($filtro); 
	if ($filtro != "") 
		$filtro = "AND (cg.CodConceptoGasto LIKE '%".$filtro."%' OR
						cg.Descripcion LIKE '%".$filtro."%')";
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
			WHERE 1 $filtro
			ORDER BY CodGastoGrupo, CodConceptoGasto";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
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
		
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$field['CodConceptoGasto']?>">
			<td align="center"><?=$field['CodConceptoGasto']?></td>
			<td><?=($field['Descripcion'])?></td>
			<td align="center" title="<?=($field['NomPartida'])?>"><?=$field['CodPartida']?></td>
			<td align="center" title="<?=($field['NomCuenta'])?>"><?=$field['CodCuenta']?></td>
			<td align="center"><?=printValores("ESTADO", $field['Estado'])?></td>
		</tr>
		<?
	}
	?>
</table>
</div></td></tr></table>
</form>

<script type="text/javascript" language="javascript">
	totalRegistros(<?=intval($rows)?>, "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
</script>
</body>
</html>