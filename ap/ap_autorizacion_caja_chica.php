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
		<td class="titulo">Autorizaci&oacute;n de Caja Chica</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="ap_autorizacion_caja_chica.php" method="POST">
<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">Filtro: <input type="text" name="filtro" id="filtro" value="<?=$filtro?>" size="30" /></td>
		<td align="right">
			<input type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'ap_autorizacion_caja_chica_form.php?accion=INSERTAR');" />
			<input type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'ap_autorizacion_caja_chica_form.php?accion=ACTUALIZAR', 'SELF');" />
			<input type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'ap_autorizacion_caja_chica_form.php?accion=VER', 'BLANK', 'height=300, width=750, left=150, top=150, resizable=no');" />
			<input type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="opcionRegistro(this.form, document.getElementById('registro').value, 'AUTORIZACION-CAJA-CHICA', 'ELIMINAR');" />
			<!--<input type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'ap_autorizacion_caja_chica_pdf.php', 'height=800, width=800, left=200, top=200, resizable=yes');" />-->
		</td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:400px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th width="75" scope="col">Empleado</th>
		<th scope="col">Nombre Completo</th>
		<th width="350" scope="col">Organismo</th>
		<th width="125" scope="col">Monto</th>
		<th width="75" scope="col">Estado</th>
	</tr>
	<?php
	$filtro = trim($filtro); 
	if ($filtro != "") 
		$filtro = "AND (cca.CodEmpleado LIKE '%".$filtro."%' OR
						p.NomCompleto LIKE '%".$filtro."%' OR
						o.Organismo LIKE '%".$filtro."%')";
	//	CONSULTO LA TABLA
	$sql = "SELECT
				cca.*,
				p.NomCompleto,
				o.Organismo
			FROM
				ap_cajachicaautorizacion cca
				INNER JOIN mastempleado e ON (cca.CodEmpleado = e.CodEmpleado)
				INNER JOIN mastpersonas p ON (e.CodPersona = p.CodPersona)
				INNER JOIN mastorganismos o ON (cca.CodOrganismo = o.CodOrganismo)
			WHERE 1 $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[CodOrganismo].$field[CodEmpleado]";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$field['CodEmpleado']?></td>
			<td><?=($field['NomCompleto'])?></td>
			<td><?=($field['Organismo'])?></td>
			<td align="right"><?=number_format($field['Monto'], 2, ',', '.')?></td>
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