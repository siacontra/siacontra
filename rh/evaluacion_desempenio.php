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
<script type="text/javascript" language="javascript" src="rh_fscript.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Evaluaci&oacute;n de Desempe&ntilde;o</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?
$MAXLIMIT=30;
if ($filtrar=="DEFAULT") {
	$periodo_actual=date("Y-m");
	$forganismo=$_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fdependencia=$_SESSION["FILTRO_DEPENDENCIA_ACTUAL"];
	$btEmpleado="disabled"; $btResponsable="disabled";
}
$filtro = "WHERE (ree.CodOrganismo='".$forganismo."')";
if ($fdependencia != "") { $chkdependencia = "checked"; $filtro .= " AND me.CodDependencia = '".$fdependencia."'"; } else $ddependencia = "disabled";
if ($chkempleado=="S")  $chkempleado="checked"; else { $fempleado=""; $fnomempleado=""; $btEmpleado="disabled"; }
if ($chkresponsable=="S")  $chkresponsable="checked"; else { $fresponsable=""; $fnomresponsable=""; $btResponsable="disabled"; }

$sql = "SELECT 
			mp.Estado AS EsReg, 
			mp.CodPersona, 
			mp.NomCompleto, 
			mp.Ndocumento, 
			me.CodEmpleado, 
			me.Fingreso, 
			me.Estado AS SitTra, 
			md.Dependencia, 
			rmc.FlagFaltaGrave 
		FROM 
			mastpersonas mp 
			INNER JOIN mastempleado me ON (mp.CodPersona = me.CodPersona) 
			INNER JOIN mastdependencias md ON (me.CodDependencia = md.CodDependencia)
			INNER JOIN seguridad_alterna sa ON (me.CodDependencia = sa.CodDependencia AND
												sa.CodAplicacion = '".$_SESSION["APLICACION_ACTUAL"]."' AND
												sa.Usuario = '".$_SESSION["USUARIO_ACTUAL"]."' AND
												sa.FlagMostrar = 'S')
			LEFT JOIN rh_motivocese rmc ON (me.CodMotivoCes = rmc.CodMotivoCes) 
		WHERE (mp.EsEmpleado = 'S') $filtrado";


?>

<form name="frmentrada" id="frmentrada" action="evaluacion_desempenio.php?limit=0" method="POST">
<div class="divBorder" style="width:1100px;">
<table width="1100" class="tblFiltro">
	<tr>
		<td width="125" align="right">Organismo:</td>
		<td>
			<input type="checkbox" name="chkorganismo" id="chkorganismo" value="S" checked onclick="forzarCheck(this.id);" />
			<select name="forganismo" id="forganismo" class="selectBig" onchange="getOptions_2(this.id, 'fpevaluacion');">
				<?=getOrganismos($forganismo, 3)?>
			</select>
		</td>
		<td align="right">Periodo de Evaluaci&oacute;n:</td>
		<td>
			<input type="checkbox" name="chkevaluacion" id="chkevaluacion" value="S" checked onclick="forzarCheck(this.id);" />
			<select name="fpevaluacion" id="fpevaluacion" class="selectBig">
				<?=getPeriodosEvaluacion("", $forganismo, 0);?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Dependencia:</td>
		<td>
			<input type='checkbox' name='chkdependencia' id='chkdependencia' value='1' <?=$chkdependencia?> onclick='enabledDependencia(this.form);' />
			<select name='fdependencia' id='fdependencia' class='selectBig' <?=$ddependencia?>>
				<option value=''></option>
				<?=getDependencias($fdependencia, $forganismo, 3)?>
			</select>
		</td>
		<td align="right">Empleado:</td>
		<td>
			<input type="checkbox" name="chkempleado" id="chkempleado" value="S" <?=$chkempleado?> onclick="enabledListadoEmpleado(this.form);" />
			<input name="fempleado" type="text" id="fempleado" size="10" value="<?=$fempleado?>" readonly />
			<input name="fnomempleado" type="text" id="fnomempleado" size="50" value="<?=$fnomempleado?>" readonly />
			<input name="btEmpleado" type="button" id="btEmpleado" value="..." onclick="cargarVentana(this.form, 'listado_empleados.php?limit=0&cod=fempleado&nom=fnomempleado&filtrar=DEFAULT', 'height=600, width=1100, left=50, top=50, resizable=yes');" <?=$btEmpleado?> />
		</td>
	</tr>
	<tr>
		<td align="right">Responsable:</td>
		<td>
			<input type="checkbox" name="chkresponsable" id="chkresponsable" value="S" <?=$chkresponsable?> onclick="enabledListadoResponsable(this.form);" />
			<input name="fresponsable" type="text" id="fresponsable" size="10" value="<?=$fresponsable?>" readonly />
			<input name="fnomresponsable" type="text" id="fnomresponsable" size="50" value="<?=$fnomresponsable?>" readonly />
			<input name="btResponsable" type="button" id="btResponsable" value="..." onclick="cargarVentana(this.form, 'listado_empleados.php?limit=0&cod=fresponsable&nom=fnomresponsable&filtrar=DEFAULT', 'height=600, width=1100, left=50, top=50, resizable=yes');" <?=$btResponsable?> />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center>
<br /><div class="divDivision">Lista de Permisos</div><br />

<?
//	CONSULTO LA TABLA SOLO PARA SABER EL TOTAL DE REGISTROS
$sql = "SELECT
			ree.*,
			mp.NomCompleto,
			me.CodEmpleado,
			rp.DescripCargo,
			mp2.NomCompleto AS NomEvaluador,
			me2.CodEmpleado AS CodEvaluador,
			mp3.NomCompleto AS NomSupervisor,
			me3.CodEmpleado AS CodSupervisor,
			(ree.TotalDesempenio + ree.TotalFuncion + ree.TotalMetas) AS Puntaje
		FROM
			rh_evaluacionempleado ree
			LEFT JOIN mastpersonas mp2 ON (ree.Evaluador = mp2.CodPersona)
			LEFT JOIN mastempleado me2 ON (mp2.CodPersona = me2.CodPersona)
			LEFT JOIN mastpersonas mp3 ON (ree.Supervisor = mp3.CodPersona)
			LEFT JOIN mastempleado me3 ON (mp3.CodPersona = me3.CodPersona)
			INNER JOIN mastpersonas mp ON (ree.CodPersona = mp.CodPersona)
			INNER JOIN mastempleado me ON (mp.CodPersona = me.CodPersona)
			INNER JOIN seguridad_alterna sa ON (me.CodDependencia = sa.CodDependencia AND
												sa.CodAplicacion = '".$_SESSION["APLICACION_ACTUAL"]."' AND
												sa.Usuario = '".$_SESSION["USUARIO_ACTUAL"]."' AND
												sa.FlagMostrar = 'S')
			INNER JOIN rh_puestos rp ON (me.CodCargo = rp.CodCargo) $filtro
		 LIMIT ".$limit.", $MAXLIMIT";
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
?>

<table width="1100" class="tblBotones">
  <tr>
	<td><div id="rows"></div></td>
	<td align="center">
		<table align="center">
			<tr>
				<td>
					<input name="btPrimero" type="button" id="btPrimero" value="&lt;&lt;" disabled onclick="setLotes(this.form, 'P', <?=$registros?>, <?=$limit?>);" />
					<input name="btAtras" type="button" id="btAtras" value="&lt;" disabled onclick="setLotes(this.form, 'A', <?=$registros?>, <?=$limit?>);" />
				</td>
				<td>Del</td><td><div id="desde"></div></td>
				<td>Al</td><td><div id="hasta"></div></td>
				<td>
					<input name="btSiguiente" type="button" id="btSiguiente" value="&gt;" disabled onclick="setLotes(this.form, 'S', <?=$registros?>, <?=$limit?>);" />
					<input name="btUltimo" type="button" id="btUltimo" value="&gt;&gt;" disabled onclick="setLotes(this.form, 'U', <?=$registros?>, <?=$limit?>);" />
				</td>
			</tr>
		</table>
	</td>
    <td align="right">
    	<input name="btEvaluar" type="button" class="btLista" id="btEvaluar" value="Evaluar" onclick="cargarOpcionEditarEvaluacion(this.form, 'evaluacion_desempenio_evaluar.php?accion=EVALUAR', 'SELF', '', 'EVALUAR');" /> | 
		<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Agregar" onclick="cargarPagina(this.form, 'evaluacion_desempenio_nuevo.php');" />
        <input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcionEditarEvaluacion(this.form, 'evaluacion_desempenio_editar.php?accion=EDITAR', 'SELF', '', 'EDITAR');" />
        <input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'evaluacion_desempenio_ver.php', 'BLANK', 'height=800, width=1200, left=0, top=0, resizable=yes');" />
        <input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistro(this.form, 'evaluacion_desempenio.php?accion=ELIMINAR', '1', 'EVALUACIONDESEMPENIO');" />
        <input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'evaluacion_desempenio_pdf.php', 'height=800, width=800, left=200, top=200, resizable=yes');" />
	</td>
  </tr>
</table>

<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="limit" id="limit" value="<?=$limit?>" />
<table width="1100" class="tblLista">
    <tr class="trListaHead">
        <th width="75" scope="col">Empleado</th>
        <th scope="col">Nombre Completo</th>
        <th width="250" scope="col">Cargo</th>
        <th width="75" scope="col">Periodo</th>
        <th width="75" scope="col">Desempe&ntilde;o</th>
        <th width="75" scope="col">Metas</th>
        <th width="75" scope="col">Puntaje</th>
        <th width="100" scope="col">Estado</th>
    </tr>
	<?php
	if ($registros!=0) {
		//	CONSULTO LA TABLA
		$sql = "SELECT
					ree.*,
					mp.NomCompleto,
					me.CodEmpleado,
					rp.DescripCargo,
					mp2.NomCompleto AS NomEvaluador,
					me2.CodEmpleado AS CodEvaluador,
					mp3.NomCompleto AS NomSupervisor,
					me3.CodEmpleado AS CodSupervisor,
					(ree.TotalDesempenio + ree.TotalFuncion + ree.TotalMetas) AS Puntaje
				FROM
					rh_evaluacionempleado ree
					LEFT JOIN mastpersonas mp2 ON (ree.Evaluador = mp2.CodPersona)
					LEFT JOIN mastempleado me2 ON (mp2.CodPersona = me2.CodPersona)
					LEFT JOIN mastpersonas mp3 ON (ree.Supervisor = mp3.CodPersona)
					LEFT JOIN mastempleado me3 ON (mp3.CodPersona = me3.CodPersona)
					INNER JOIN mastpersonas mp ON (ree.CodPersona = mp.CodPersona)
					INNER JOIN mastempleado me ON (mp.CodPersona = me.CodPersona)
					INNER JOIN seguridad_alterna sa ON (me.CodDependencia = sa.CodDependencia AND
														sa.CodAplicacion = '".$_SESSION["APLICACION_ACTUAL"]."' AND
														sa.Usuario = '".$_SESSION["USUARIO_ACTUAL"]."' AND
														sa.FlagMostrar = 'S')
					INNER JOIN rh_puestos rp ON (me.CodCargo = rp.CodCargo) $filtro
				 LIMIT ".$limit.", $MAXLIMIT";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//	MUESTRO LA TABLA
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			if ($field['Estado'] == "EE") $status = "En Evaluaci&oacute;n";
			elseif ($field['Estado'] == "EV") $status = "Evaluado";
			$desempenio = number_format($field['TotalDesempenio'], 2, ',', '.');
			$metas = number_format($field['TotalMetas'], 2, ',', '.');
			$puntaje = number_format($field['Puntaje'], 2, ',', '.');
			
			$id = $field['CodOrganismo']."|".$field['Periodo']."|".$field['CodPersona']."|".$field['Secuencia'];
			?>
			<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
				<td align="center"><?=$field["CodEmpleado"]?></td>
				<td><?=($field["NomCompleto"])?></td>
				<td><?=($field["DescripCargo"])?></td>
				<td align="center"><?=$field["Periodo"]?></td>
				<td align="right"><?=$desempenio?></td>
				<td align="right"><?=$metas?></td>
				<td align="right"><?=$puntaje?></td>
				<td align="center"><?=$status?></td>
			</tr>
            <?
		}
	}
	$rows = (int)$rows;
	?>
</table>
</form>

<script type="text/javascript" language="javascript">
	totalRegistros(<?=$registros?>, "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
	totalLotes(<?=$registros?>, <?=$rows?>, <?=$limit?>);
</script>
</body>
</html>