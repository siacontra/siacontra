<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_lg.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_lg_2.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Control de Periodos</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?
$MAXLIMIT=30;
//	-------------------------------
if ($filtrar == "DEFAULT") {
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
}
//	-------------------------------
if ($forganismo != "") { $corganismo = "checked"; $filtro .= " AND (pc.CodOrganismo = '".$forganismo."')";  } else $dorganismo = "disabled";
if ($fperiodo != "") { $cperiodo = "checked"; $filtro .= " AND (pc.Periodo = '".$fperiodo."')";  } else $dperiodo = "disabled";
//	-------------------------------

//	-------------------------------

//	CONSULTO LA TABLA PARA OBTENER EL TOTAL DE REGISTROS
$sql = "SELECT 
			pc.*,
			o.Organismo 
		FROM 
			lg_periodocontrol pc
			INNER JOIN mastorganismos o ON (pc.CodOrganismo = o.CodOrganismo)
		WHERE 1 $filtro";
$query = mysql_query($sql) or die ($sql.mysql_error());
$registros = mysql_num_rows($query);
?>

<form name="frmentrada" id="frmentrada" action="control_periodos.php?filtrar=" method="get">
<input type="hidden" name="limit" id="limit" value="<?=$limit?>" />
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td width="125" align="right">Organismo:</td>
		<td>
			<input type="checkbox" name="chkorganismo" id="chkorganismo" value="1" <?=$corganismo?> onclick="forzarCheck(this.id);" />
			<select name="forganismo" id="forganismo" class="selectBig" <?=$dorganismo?> onchange="getFOptions_2(this.id, 'falmacen', 'chkalmacen');">
				<?=getOrganismos($forganismo, 3);?>
			</select>
		</td>
        <td width="125" align="right">Periodo:</td>
        <td>
			<input type="checkbox" name="chkperiodo" id="chkperiodo" value="1" <?=$cperiodo?> onclick="chkFiltro(this.checked, 'fperiodo');" />
            <input name="fperiodo" type="text" id="fperiodo" size="15" maxlength="7" value="<?=$fperiodo?>" <?=$dperiodo?> />
        </td>
	</tr>
</table>
</div>

<center><input type="submit" name="btBuscar" value="Buscar"></center>
<br /><div class="divDivision">Lista de Periodos</div><br />

<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td width="350">
			<table align="center">
				<tr>
					<td>
						<input name="btPrimero" type="button" id="btPrimero" value="&lt;&lt;" onclick="setLotes(this.form, 'P', <?=$registros?>, <?=$limit?>, '');" />
						<input name="btAtras" type="button" id="btAtras" value="&lt;" onclick="setLotes(this.form, 'A', <?=$registros?>, <?=$limit?>, '');" />
					</td>
					<td>Del</td><td><div id="desde"></div></td>
					<td>Al</td><td><div id="hasta"></div></td>
					<td>
						<input name="btSiguiente" type="button" id="btSiguiente" value="&gt;" onclick="setLotes(this.form, 'S', <?=$registros?>, <?=$limit?>, '');" />
						<input name="btUltimo" type="button" id="btUltimo" value="&gt;&gt;" onclick="setLotes(this.form, 'U', <?=$registros?>, <?=$limit?>, '');" />
					</td>
				</tr>
			</table>
		</td>
		<td align="right">
			<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'control_periodos_nuevo.php');" />
			<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'control_periodos_editar.php', 'SELF');" />
			<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'control_periodos_ver.php', 'BLANK', 'height=500, width=800, left=50, top=100, resizable=no');" />
			<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistro(this.form, 'control_periodos.php', '1', 'CONTROL-PERIODOS', 'ELIMINAR');" />
			<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'control_periodos_pdf.php', 'height=800, width=800, left=200, top=200, resizable=yes');" />
		</td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />

<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:400px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th width="75" scope="col">Organismo</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="75" scope="col">Periodo</th>
		<th width="40" scope="col">Dis.</th>
		<th width="100" scope="col">Estado</th>
		<th width="125" scope="col">Ult. Usuario</th>
		<th width="150" scope="col">Ult. Modif.</th>
	</tr>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT 
				pc.*,
				o.Organismo 
			FROM 
				lg_periodocontrol pc
				INNER JOIN mastorganismos o ON (pc.CodOrganismo = o.CodOrganismo)
			WHERE 1 $filtro
			LIMIT $limit, $MAXLIMIT";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		$id = $field['CodOrganismo'].".".$field['Periodo'];
		$status = printValores("ESTADO", $field['Estado']);
		$flag = printFlag($field['FlagTransaccion']);
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$field['CodOrganismo']?></td>
			<td><?=($field['Organismo'])?></td>
			<td align="center"><?=$field['Periodo']?></td>
			<td align="center"><?=$flag?></td>
			<td align="center"><?=strtoupper($status)?></td>
			<td align="center"><?=($field['UltimoUsuario'])?></td>
			<td align="center"><?=($field['UltimaFecha'])?></td>
		</tr>
		<?
	}
	?>
</table>
</div></td></tr></table>

</form>

<script type="text/javascript" language="javascript">
	totalRegistros(<?=$registros?>, "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
	totalLotes(<?=$registros?>, <?=$rows?>, <?=$limit?>);
</script>
</body>
</html>