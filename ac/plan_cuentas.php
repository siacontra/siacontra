<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_sia.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_sia.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Plan de Cuentas</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="plan_cuentas.php" method="POST">
<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">Filtro: <input type="text" name="filtro" id="filtro" value="<?=$filtro?>" size="30" /></td>
		<td align="right">
			<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'plan_cuentas_nuevo.php');" />
			<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'plan_cuentas_editar.php', 'SELF');" />
			<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'plan_cuentas_ver.php', 'BLANK', 'height=275, width=750, left=200, top=200, resizable=no');" />
			<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="opcionRegistro(this.form, document.getElementById('registro').value, 'PLAN-CUENTAS', 'ELIMINAR');" />
			<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'plan_cuentas_pdf.php', 'height=800, width=800, left=200, top=200, resizable=yes');" />
		</td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:600px;">
<table width="980" class="tblLista">
	<tr class="trListaHead">
		<th width="100" scope="col">Cuenta</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="125" scope="col">Tipo de Cuenta</th>
		<th width="75" scope="col">Naturaleza</th>
		<th width="75" scope="col">Estado</th>
	</tr>
	<?php
	$filtro = trim($filtro); 
	if ($filtro != "") $filtro = "WHERE (ampc.CodCuenta LIKE '%".$filtro."%' OR ampc.Descripcion LIKE '%".$filtro."%' OR mmd.Descripcion LIKE '%".$filtro."%')"; 
	else $filtro = "";
	//	CONSULTO LA TABLA
	$sql = "SELECT
				ampc.*,
				mmd.Descripcion AS NomTipoCuenta
			FROM
				ac_mastplancuenta ampc
				INNER JOIN mastmiscelaneosdet mmd ON (ampc.TipoCuenta = mmd.CodDetalle AND mmd.CodMaestro = 'CUENTAS')
			$filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "A") $status = "Activo"; else $status = "Inactivo";
		if ($field['TipoSaldo'] == "D") $naturaleza = "Deudora"; else $naturaleza = "Acreeedora";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$field['CodCuenta']?>">
			<td><?=$field['CodCuenta']?></td>
			<td><?=htmlentities($field['Descripcion'])?></td>
			<td align="center"><?=htmlentities($field['NomTipoCuenta'])?></td>
			<td align="center"><?=htmlentities($naturaleza)?></td>
			<td align="center"><?=htmlentities($status)?></td>
		</tr>
		<?
	}
	?>
	<script type="text/javascript" language="javascript">
		totalRegistros(<?=$rows?>, "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
	</script>
</table>
</div></td></tr></table>
</form>
</body>
</html>