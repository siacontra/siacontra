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
		<td class="titulo">Iniciar/Terminar Capacitaci&oacute;n</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
$MAXLIMIT=30;
if ($filtrar=="DEFAULT") {
	$filtro="(r.Estado=*A*)AND (r.CodOrganismo=*".$_SESSION["FILTRO_ORGANISMO_ACTUAL"]."*)";
	$_POST['chkstatus']="1";
	$_POST['fstatus']="A";
	$_POST['chkorganismo']="1";
	$_POST['forganismo']=$_SESSION["FILTRO_ORGANISMO_ACTUAL"];
}
//	VALORES POR DEFECTO Y VALORES SELECCIONADOS DEL FILTRO AL CARGAR O RECARGAR LA PAGINA...............
if ($_POST['chkorganismo']=="1") { $obj[0]="checked"; $obj[1]=""; $obj[2]=$_POST['forganismo']; }
else { $obj[0]=""; $obj[1]="disabled"; $obj[2]=""; }
if ($_POST['chkcapacitacion']=="1") { $obj[3]="checked"; $obj[4]=""; $obj[5]=$_POST['fcapacitacion']; }
else { $obj[3]=""; $obj[4]="disabled"; $obj[5]=""; }
if ($_POST['chkdependencia']=="1") { $obj[7]="checked"; $obj[8]=""; $obj[9]=$_POST['fdependencia']; }
else { $obj[7]=""; $obj[8]="disabled"; $obj[9]="0"; }
if ($_POST['chkstatus']=="1") { $obj[10]="checked"; $obj[11]=""; $obj[12]=$_POST['fstatus']; }
else { $obj[10]=""; $obj[11]="disabled"; $obj[12]="0"; }
if ($_POST['chktcursos']=="1") { $obj[13]="checked"; $obj[14]=""; $obj[15]=$_POST['ftcursos']; }
else { $obj[13]=""; $obj[14]="disabled"; $obj[15]="0"; }
if ($_POST['chkcursos']=="1") { $obj[16]="checked"; $obj[17]=""; $obj[18]=$_POST['fcursos']; }
else { $obj[16]=""; $obj[17]="disabled"; $obj[18]="0"; }

echo "
<form name='frmentrada' action='capacitacion_lista.php' method='POST' onsubmit='return false'>
<input type='hidden' name='limit' id='limit' value='".$limit."'>
<input type='hidden' name='filtro' id='filtro' value='".$filtro."'>
<input type='hidden' name='regresar' id='regresar' value='capacitacion_iniciar' />
<div class='divBorder' style='width:1000px;'>
<table width='1000' class='tblFiltro'>
	<tr>
		<td align='right'>Organismo:</td>
		<td>
			<input type='checkbox' name='chkorganismo' id='chkorganismo' value='1' $obj[0] onclick='enabledOrganismo2(this.form);' />
			<select name='forganismo' id='forganismo' class='selectBig' $obj[1]>
				<option value=''></option>";
				getOrganismos($obj[2], 0);
				echo "
			</select>
		</td>
		<td align='right'>Estado:</td>
		<td>
			<input type='checkbox' name='chkstatus' id='chkstatus' value='1' $obj[10] onclick='forzarCheck(this.id);' />
			<select name='fstatus' id='fstatus' class='selectMed' $obj[11]>";
				getStatusCapacitacion($obj[12], 2);
				echo "
			</select>
		</td>
	</tr>
	<tr>
		<td align='right'>Curso:</td>
		<td>
			<input type='checkbox' name='chkcursos' value='1' $obj[16] onclick='enabledCursos(this.form);' />
			<select name='fcursos' id='fcursos' class='selectBig' $obj[17]>
				<option value=''></option>";
				getCursos($obj[18], 0);
				echo "
			</select>
		</td>
		<td align='right'>Tipo de Curso:</td>
		<td>
			<input type='checkbox' name='chktcursos' value='1' $obj[13] onclick='enabledTCursos(this.form);' />
			<select name='ftcursos' id='ftcursos' class='selectMed' $obj[14]>
				<option value=''></option>";
				getMiscelaneos($obj[15], 'TIPOCURSO', 0);
				echo "
			</select>
		</td>
	</tr>
	<tr>		
		<td align='right'># Capacitaci&oacute;n:</td>
		<td colspan='3'>
			<input type='checkbox' name='chkcapacitacion' value='1' $obj[3] onclick='enabledCapacitacion(this.form);' />
			<input type='text' name='fcapacitacion' size='10' maxlength='6' $obj[4] value='$obj[5]' />
		</td>
	</tr>
</table>
</div>
<center><input type='button' name='btBuscar' value='Buscar' onclick='filtroCapacitaciones(this.form, ".$limit.", \"capacitacion_iniciar.php\");'></center>
<br /><div class='divDivision'>Listado de Capacitaciones</div><br />";

$filtro=strtr($filtro, "*", "'");
$filtro=strtr($filtro, ";", "%");
if ($filtro!="") $filtro="WHERE (".$filtro.")";
//	CONSULTO LA TABLA SOLO PARA SABER EL TOTAL DE REGISTROS
$sql="SELECT r.*, ru.Descripcion AS Curso, re.Descripcion AS Centro, mo.Organismo, md.Descripcion AS TipoCurso FROM rh_capacitacion r INNER JOIN rh_cursos ru ON (r.CodCurso=ru.CodCurso) INNER JOIN rh_centrosestudios re ON (r.CodCentroEstudio=re.CodCentroEstudio) INNER JOIN mastorganismos mo ON (r.CodOrganismo=mo.CodOrganismo) INNER JOIN mastmiscelaneosdet md ON (r.TipoCurso=md.CodDetalle AND md.CodMaestro='TIPOCURSO') INNER JOIN seguridad_alterna sa ON (r.CodOrganismo=sa.CodOrganismo AND sa.CodAplicacion='".$_SESSION["APLICACION_ACTUAL"]."' AND sa.Usuario='".$_SESSION["USUARIO_ACTUAL"]."' AND sa.FlagMostrar='S') ".$filtro." GROUP BY r.Capacitacion, r.CodOrganismo";
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
?>

<table width="1000" class="tblBotones">
  <tr>
	<td><div id="rows"></div></td>
	<td align="center">
		<?php 
		echo "
		<table align='center'>
			<tr>
				<td>
					<input name='btPrimero' type='button' id='btPrimero' value='&lt;&lt;' disabled onclick='setLotes(this.form, \"P\", $registros, ".$limit.");' />
					<input name='btAtras' type='button' id='btAtras' value='&lt;' disabled onclick='setLotes(this.form, \"A\", $registros, ".$limit.");' />
				</td>
				<td>Del</td><td><div id='desde'></div></td>
				<td>Al</td><td><div id='hasta'></div></td>
				<td>
					<input name='btSiguiente' type='button' id='btSiguiente' value='&gt;' disabled onclick='setLotes(this.form, \"S\", $registros, ".$limit.");' />
					<input name='btUltimo' type='button' id='btUltimo' value='&gt;&gt;' disabled onclick='setLotes(this.form, \"U\", $registros, ".$limit.");' />
				</td>
			</tr>
		</table>";
		?> 
		
	</td>
    <td align="right">
		<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" disabled="disabled" />
		<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" disabled="disabled" />
		<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" disabled="disabled" />
		<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" disabled /> |  
		<input name="btAprobar" type="button" class="btLista" id="btAprobar" value="Aprobar" disabled="disabled" />
		<input name="btIniciar" type="button" class="btLista" id="btIniciar" value="Iniciar" onclick="cargarOpcion(this.form, 'capacitacion_inicia.php?tab1=1&tab2=1&tab3=1&tab4=1&tab5=1&tab6=0&tab7=0&accion=Iniciar', 'SELF');" />
		<input name="btTerminar" type="button" class="btLista" id="btTerminar" value="Terminar" onclick="cargarOpcion(this.form, 'capacitacion_inicia.php?tab1=1&tab2=1&tab3=1&tab4=1&tab5=1&tab6=0&tab7=0&accion=Terminar', 'SELF');" />
	</td>
  </tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="1000" class="tblLista">
  <tr class="trListaHead">
	<th scope="col" width="75"># Cap.</th>
	<th scope="col" width="100">Tipo</th>
	<th scope="col" width="275">Curso</th>
	<th scope="col">Centro</th>
	<th scope="col" width="75">Inicio</th>
	<th scope="col" width="100">Estado</th>
	<th scope="col" width="100">Costo Estimado</th>
  </tr>
	<?php
	if ($registros!=0) {
		//	CONSULTO LA TABLA
		$sql="SELECT r.*, ru.Descripcion AS Curso, re.Descripcion AS Centro, mo.Organismo, md.Descripcion AS TipoCurso FROM rh_capacitacion r INNER JOIN rh_cursos ru ON (r.CodCurso=ru.CodCurso) INNER JOIN rh_centrosestudios re ON (r.CodCentroEstudio=re.CodCentroEstudio) INNER JOIN mastorganismos mo ON (r.CodOrganismo=mo.CodOrganismo) INNER JOIN mastmiscelaneosdet md ON (r.TipoCurso=md.CodDetalle AND md.CodMaestro='TIPOCURSO') INNER JOIN seguridad_alterna sa ON (r.CodOrganismo=sa.CodOrganismo AND sa.CodAplicacion='".$_SESSION["APLICACION_ACTUAL"]."' AND sa.Usuario='".$_SESSION["USUARIO_ACTUAL"]."' AND sa.FlagMostrar='S') ".$filtro." GROUP BY r.Capacitacion, r.CodOrganismo ORDER BY r.Capacitacion LIMIT ".$limit.", $MAXLIMIT";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//	MUESTRO LA TABLA
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaDesde']); $finicio=$d."-".$m."-".$a;
			if ($field["Estado"]=="P") $status="En Preparaci&oacute;n";
			elseif ($field["Estado"]=="A") $status="Aprobado";
			elseif ($field["Estado"]=="I") $status="Iniciado";
			elseif ($field["Estado"]=="T") $status="Terminado";
			$estimado=number_format($field['CostoEstimado'], 2, ',', '.');
			echo "
			<tr class='trListaBody' onclick='mClk(this, \"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['Capacitacion']."-".$field['CodOrganismo']."'>
				<td align='center'>".$field['Capacitacion']."</td>
				<td align='center'>".$field['TipoCurso']."</td>
				<td>".$field['Curso']."</td>
				<td>".$field['Centro']."</td>
				<td align='center'>".$finicio."</td>
				<td align='center'>".$status."</td>
				<td align='right'>".$estimado."</td>
			</tr>";
		}
	}
	$rows=(int)$rows;
	echo "
	<script type='text/javascript' language='javascript'>
		totalCapacitaciones($registros, \"$_ADMIN\", \"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
		totalLotes($registros, $rows, ".$limit.");
	</script>";
	?>
</table>
</form>
</body>
</html>