<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_nomina.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('05', $concepto);
//	------------------------------------
extract($_POST);
extract($_GET);
if ($opcion == "LISTAR") {
	$title = "Lista";
	$where = "";
	$btNuevo = "";
	$btEditar = "";
	$btVer = "";
	$btEliminar = "";
	$btAprobar = "disabled";
	$btAnular = "";
}
elseif ($opcion == "APROBAR") {
	$title = "Aprobar";
	$where = " AND (Estado = 'PR') ";
	$btNuevo = "disabled";
	$btEditar = "disabled";
	$btVer = "";
	$btEliminar = "disabled";
	$btAprobar = "";
	$btAnular = "";
}
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_nomina_2.js"></script>
</head>

<body onload="document.getElementById('filtro').focus();">
<div id="bloqueo" class="divBloqueo"></div>

<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Ajuste Salarial | <?=$title?></td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="ajuste_salarial_listar.php" method="POST">
<input type="hidden" name="opcion" id="opcion" value="<?=$opcion?>" />
<table width="900" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">Filtro: <input type="text" name="filtro" id="filtro" value="<?=$filtro?>" size="30" /></td>
		<td align="right">
			<input type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'ajuste_salarial_form.php?accion=INSERTAR');" <?=$btNuevo?> />
			<input type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'ajuste_salarial_form.php?accion=ACTUALIZAR', 'SELF');" <?=$btEditar?> />
			<input type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'ajuste_salarial_form.php?accion=VER', 'BLANK', 'height=600, width=800, left=150, top=0, resizable=no');" <?=$btVer?> />
			<input type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="opcionRegistro(this.form, document.getElementById('registro').value, 'AJUSTE-SALARIAL', 'ELIMINAR');" <?=$btEliminar?> /> | 
			<input type="button" class="btLista" id="btAprobar" value="Aprobar" onclick="cargarOpcion(this.form, 'ajuste_salarial_form.php?accion=APROBAR', 'SELF');" <?=$btAprobar?> />
			<input type="button" class="btLista" id="btAnular" value="Anular" onclick="cargarOpcion(this.form, 'ajuste_salarial_form.php?accion=ANULAR', 'SELF');" <?=$btAnular?> />
		</td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:900px; height:400px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th width="75" scope="col">Organismo</th>
		<th width="75" scope="col">Periodo</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="100" scope="col">Nro. Resoluci&oacute;n</th>
		<th width="100" scope="col">Nro. Gaceta</th>
		<th width="100" scope="col">Estado</th>
	</tr>
	<?php
	$filtro = trim($filtro); 
	if ($filtro != "") {
		$where .= "AND (CodOrganismo LIKE '%".($filtro)."%' OR
						Periodo LIKE '%".($filtro)."%' OR
						Descripcion LIKE '%".($filtro)."%' OR
						NroResolucion LIKE '%".($filtro)."%')";
	}
	//	CONSULTO LA TABLA
	$sql = "SELECT *
			FROM pr_ajustesalarial
			WHERE 1 $where";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[CodOrganismo].$field[Periodo].$field[Secuencia]";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$field['CodOrganismo']?></td>
			<td align="center"><?=$field['Periodo']?></td>
			<td><?=($field['Descripcion'])?></td>
			<td align="center"><?=$field['NroResolucion']?></td>
			<td align="center"><?=$field['NroGaceta']?></td>
			<td align="center"><?=printValores("ESTADO-AJUSTE", $field['Estado'])?></td>
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