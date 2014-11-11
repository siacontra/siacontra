<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Retenciones Judiciales</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
//	VALORES POR DEFECTO DEL FILTRO
$MAXLIMIT=30;
if ($filtrar=="DEFAULT") {
	$chkorganismo = "1";
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; 
	$filtro = "WHERE (e.CodOrganismo = '".$_SESSION["FILTRO_ORGANISMO_ACTUAL"]."')";
} else $filtro = "WHERE 1";
//	------------------------------

list($d, $m, $a) = SPLIT( '[/.-]', $finicio); $sql_finicio = "$a-$m-$d";
list($d, $m, $a) = SPLIT( '[/.-]', $ffin); $sql_ffin = "$a-$m-$d";

//	VALORES POR DEFECTO Y VALORES SELECCIONADOS DEL FILTRO AL CARGAR O RECARGAR LA PAGINA
if ($chkorganismo == "1") { $corganismo = "checked"; $filtro .= " AND e.CodOrganismo = '".$forganismo."'"; } else $dorganismo = "disabled";
if ($chkstatus == "1") { $cstatus = "checked"; } else $dstatus = "disabled";
if ($chkinicio == "1") { $cinicio = "checked"; $filtro .= " AND rj.FechaResolucion >= '".$sql_finicio."'"; } else $dinicio = "disabled";
if ($chkfin == "1") { $cfin = "checked"; $filtro .= " AND rj.FechaResolucion <= '".$sql_ffin."'"; } else $dfin = "disabled";
if ($chkexpediente == "1") { $cexpediente = "checked"; $filtro .= " AND rj.Expediente = '".$fexpediente."'"; } else $dexpediente = "disabled";
if ($chkretencion == "1") { $cretencion = "checked"; $filtro .= " AND rj.CodRetencion = '".$fretencion."'"; } else $dretencion = "disabled";
if ($chkempleado == "1") { $cempleado = "checked"; $filtro .= " AND rj.CodPersona = '".$fempleado."'"; } else $dempleado = "disabled";
//	-------------------------------------------------------------------------------------

?>
<form name="frmentrada" id="frmentrada" action="rjudiciales.php" method="POST">
<input type="hidden" name="limit" value="<?=$limit?>">
<input type="hidden" name="ordenar" value="<?=$ordenar?>">
<div class="divBorder" style="width:900px;">
<table width="900" class="tblFiltro">
	<tr>
		<td width="125" align="right">Organismo:</td>
		<td>
			<input type="checkbox" name="chkorganismo" id="chkorganismo" value="1" <?=$corganismo?> onclick="enabledOrganismo2(this.form);" />
			<select name="forganismo" id="forganismo" class="selectBig" <?=$dorganismo?>>
				<option value=""></option>
				<?=getOrganismos($forganismo, 3)?>
			</select>
		</td>
		<td width="125" align="right">Estado:</td>
		<td>
			<input type="checkbox" name="chkstatus" id="chkstatus" value="1" <?=$cstatus?> onclick="enabledStatus(this.form);" />
			<select name="fstatus" id="fstatus" style="width:125px;" <?=$dstatus?>>
				<option value=""></option>
				<?=getStatus($fstatus, 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td width="125" align="right">Fecha de Inicio:</td>
		<td>
			<input type="checkbox" name="chkinicio" id="chkinicio" value="1" <?=$cinicio?> onclick="enabledFInicio(this.form);" />
			<input type="text" name="finicio" id="finicio" size="15" maxlength="10" <?=$dinicio?> value="<?=$finicio?>" />
		</td>
		<td width="125" align="right">Fecha de Fin:</td>
		<td>
			<input type="checkbox" name="chkfin" id="chkfin" value="1" <?=$cfin?> onclick="enabledFFin(this.form);" />
			<input type="text" name="ffin" id="ffin" size="15" maxlength="10" <?=$dfin?> value="<?=$ffin?>" />
		</td>
	</tr>
	<tr>
		<td width="125" align="right">Expediente:</td>
		<td>
			<input type="checkbox" name="chkexpediente" id="chkexpediente" value="1" <?=$cexpediente?> onclick="enabledFExpediente(this.form);" />
			<input type="text" name="fexpediente" id="fexpediente" size="15" maxlength="10" <?=$dexpediente?> value="<?=$fexpediente?>" />
		</td>
		<td width="125" align="right">Empleado:</td>
		<td>
			<input type="checkbox" name="chkempleado" id="chkempleado" value="1" <?=$cempleado?> onclick="enabledFEmpleado(this.form);" />
			<input type="text" name="fempleado" id="fempleado" size="15" maxlength="6" <?=$dempleado?> value="<?=$fempleado?>" />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center>
<br /><div class="divDivision">Lista de Retenciones</div><br />

<?php
//	CONSULTO LA TABLA PARA SABER EL TOTAL DE REGISTROS SOLAMENTE
$sql="SELECT 
			rj.*,
			mp.NomCompleto
		FROM		
			rh_retencionjudicial rj
			INNER JOIN mastpersonas mp ON (rj.CodPersona = mp.CodPersona)
			INNER JOIN mastempleado e ON (mp.CodPersona = e.CodPersona)
			INNER JOIN seguridad_alterna sa ON (e.CodDependencia = sa.CodDependencia AND sa.CodAplicacion = '".$_SESSION["APLICACION_ACTUAL"]."' AND sa.Usuario = '".$_SESSION["USUARIO_ACTUAL"]."' AND sa.FlagMostrar = 'S')
		$filtro";
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
//	------------------------------------------------------------
?>

<table width="900" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td width="350">
			<table align="center">
				<tr>
					<td>
						<input name="btPrimero" type="button" id="btPrimero" value="&lt;&lt;" onclick="setLotes(this.form, 'P', <?=$registros?>, '<?=$limit?>', '<?=$ordenar?>');" />
						<input name="btAtras" type="button" id="btAtras" value="&lt;" onclick="setLotes(this.form, 'A', <?=$registros?>, '<?=$limit?>', '<?=$ordenar?>');" />
					</td>
					<td>Del</td><td><div id="desde"></div></td>
					<td>Al</td><td><div id="hasta"></div></td>
					<td>
						<input name="btSiguiente" type="button" id="btSiguiente" value="&gt;" onclick="setLotes(this.form, 'S', <?=$registros?>, '<?=$limit?>', '<?=$ordenar?>');" />
						<input name="btUltimo" type="button" id="btUltimo" value="&gt;&gt;" onclick="setLotes(this.form, 'U', <?=$registros?>, '<?=$limit?>', '<?=$ordenar?>');" />
					</td>
				</tr>
			</table>
		</td>
		<td align="right">
			<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'rjudiciales_nuevo.php');" />
			<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'rjudiciales_editar.php', 'SELF');" />
			<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'rjudiciales_ver.php', 'BLANK', 'height=500, width=750, left=200, top=200, resizable=no');" />
			<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistro(this.form, 'rjudiciales.php?', '1', 'RETENCIONES-JUDICIALES');" />
			<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'rjudiciales_pdf.php', 'height=900, width=900, left=200, top=200, resizable=yes');" />
		</td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="900" class="tblLista">
	<tr class="trListaHead">
		<th width="125" scope="col">Retención</th>
		<th width="150" scope="col">Expediente</th>
		<th width="100" scope="col">Fecha de Resolución</th>
		<th scope="col">Empleado</th>
		<th width="175" scope="col">Juzgado</th>
	</tr>

	<?php 
	if ($registros != 0) {
		//	CONSULTO LA TABLA
		$sql="SELECT 
					rj.*,
					mp.NomCompleto
				FROM		
					rh_retencionjudicial rj
					INNER JOIN mastpersonas mp ON (rj.CodPersona = mp.CodPersona)
					INNER JOIN mastempleado e ON (mp.CodPersona = e.CodPersona)
					INNER JOIN seguridad_alterna sa ON (e.CodDependencia = sa.CodDependencia AND sa.CodAplicacion = '".$_SESSION["APLICACION_ACTUAL"]."' AND sa.Usuario = '".$_SESSION["USUARIO_ACTUAL"]."' AND sa.FlagMostrar = 'S')
				$filtro
				ORDER BY rj.CodRetencion
				LIMIT $limit, $MAXLIMIT";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		$rows = mysql_num_rows($query);
		//	MUESTRO LA TABLA
		for ($i=0; $i<$rows; $i++) {
			$field = mysql_fetch_array($query);
			list($a, $m, $d) = SPLIT( '[/.-]', $field['FechaResolucion']); $fresolucion = $d."-".$m."-".$a;
			?>
			<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$field["CodRetencion"]?>">
				<td align="center"><?=$field['CodRetencion']?></td>
				<td align="center"><?=$field['Expediente']?></td>
				<td align="center"><?=$fresolucion?></td>
				<td><?=($field['NomCompleto'])?></td>
				<td><?=($field['Juzgado'])?></td>
			</tr>
			<?php
		}
		//	------------------
	}
	$rows = (int) $rows;
	?>
	
	<script type="text/javascript" language="javascript">
		totalRegistros(<?=$registros?>, '<?=$_ADMIN?>', '<?=$_INSERT?>', '<?=$_UPDATE?>', '<?=$_DELETE?>');
		totalLotes(<?=$registros?>, <?=$rows?>, <?=$limit?>);
	</script>
</table>

</form>
</body>
</html>