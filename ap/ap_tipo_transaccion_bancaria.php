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
		<td class="titulo">Tipo de Transacciones Bancarias</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="ap_tipo_transaccion_bancaria.php" method="POST">
<table width="950" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">Filtro: <input type="text" name="filtro" id="filtro" value="<?=$filtro?>" size="30" /></td>
		<td align="right">
			<input type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'ap_tipo_transaccion_bancaria_nuevo.php');" />
			<input type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'ap_tipo_transaccion_bancaria_editar.php', 'SELF');" />
			<input type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'ap_tipo_transaccion_bancaria_ver.php', 'BLANK', 'height=350, width=750, left=100, top=0, resizable=no');" />
			<input type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="opcionRegistro(this.form, document.getElementById('registro').value, 'TIPO-TRANSACCION-BANCARIA', 'ELIMINAR');" />
			<!--<input type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'ap_tipo_transaccion_bancaria_pdf.php', 'height=800, width=800, left=200, top=200, resizable=yes');" />-->
		</td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:950px; height:400px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th width="75" scope="col">Tipo</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="50" scope="col">Vou.</th>
		<th width="100" scope="col">Cuenta</th>
		<th width="100" scope="col">Estado</th>
	</tr>
	<?php
	$filtro = trim($filtro); 
	if ($filtro != "") 
		$filtro = "AND (CodTipoTransaccion LIKE '%".$filtro."%' OR 
						Descripcion LIKE '%".$filtro."%' OR 
						CodCuenta LIKE '%".$filtro."%')";
	//	CONSULTO LA TABLA
	$sql = "SELECT *
			FROM ap_bancotipotransaccion
			WHERE 1 $filtro
			ORDER BY TipoTransaccion, CodTipoTransaccion";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field = mysql_fetch_array($query);
		$estado = printValores("ESTADO", $field['Estado']);
		$flagvoucher = printFlag($field['FlagVoucher']);
		
		if ($grupo != $field['TipoTransaccion']) {
			$grupo = $field['TipoTransaccion'];
			?>
            <tr class="trListaBody2">
                <td colspan="5"><?=printValores("TIPO-TRANSACCION", $field['TipoTransaccion'])?></td>
            </tr>
            <?
		}
		
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$field['CodTipoTransaccion']?>">
			<td align="center"><?=$field['CodTipoTransaccion']?></td>
			<td><?=($field['Descripcion'])?></td>
			<td align="center"><?=$flagvoucher?></td>
			<td align="center"><?=$field['CodCuenta']?></td>
			<td align="center"><?=$estado?></td>
		</tr>
		<?
	}
	?>
	<script type="text/javascript" language="javascript">
		totalRegistros(<?=intval($rows)?>, "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
	</script>
</table>
</div></td></tr></table>
</form>
</body>
</html>