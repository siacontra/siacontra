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
		<td class="titulo">Control de Encuestas</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
$MAXLIMIT=30;
if ($filtrar=="DEFAULT") {
	$annio_actual=date("Y");
	$mes_actual=date("m"); $m=(int) $mes_actual;
	$dia_actual=date("d");
	$_POST['chkperiodo']="1";
	$fperiodo="$annio_actual-$mes_actual";
	$filtro=" AND PeriodoContable=*".$fperiodo."* AND (CodOrganismo=*".$_SESSION["FILTRO_ORGANISMO_ACTUAL"]."*)";
}
if ($accion=="ELIMINAR") {
	$sql="DELETE FROM rh_encuestas WHERE Secuencia='".$registro."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
//	VALORES POR DEFECTO Y VALORES SELECCIONADOS DEL FILTRO AL CARGAR O RECARGAR LA PAGINA...............
if ($_POST['chkorganismo']=="1") { $obj[0]="checked"; $obj[1]=""; $obj[2]=$_POST['forganismo']; }
else { $obj[0]=""; $obj[1]="disabled"; $obj[2]=$_SESSION["FILTRO_ORGANISMO_ACTUAL"]; }
if ($_POST['chkperiodo']=="1") { $obj[3]="checked"; $obj[4]=""; $obj[5]=$fperiodo; }
else { $obj[3]=""; $obj[4]="disabled"; $obj[5]=""; }
echo "
<form name='frmentrada' action='encuestas.php?filtro=".$filtro."' method='POST' onsubmit='return false'>
<input type='hidden' name='limit' id='limit' value='".$limit."'>
<input type='hidden' name='filtro' id='filtro' value='".$filtro."'>
<div class='divBorder' style='width:900px;'>
<table width='900' class='tblFiltro'>
	<tr>
		<td width='125' align='right'>Organismo:</td>
		<td>
			<input type='checkbox' name='chkorganismo' id='chkorganismo' value='1' checked onclick='forzarCheck(this.id);' />
			<select name='forganismo' id='forganismo' class='selectBig' onchange='getOptions_2(this.id, \"fdependencia\");'>";
				getOrganismos($obj[2], 3);
				echo "
			</select>
		</td>
		<td width='125' align='right'>Periodo:</td>
		<td>
			<input type='checkbox' name='chkperiodo' id='chkperiodo' value='1' $obj[3] onclick='enabledPeriodo(this.form);' />
			<input type='text' name='fperiodo' id='fperiodo' size='15' maxlength='7' size='10' $obj[4] value='$obj[5]' />
		</td>
	</tr>
</table>
</div>
<center><input type='button' name='btBuscar' value='Buscar' onclick='filtroEncuesta(this.form, ".$limit.", \"encuestas.php\");'></center>
<br /><div class='divDivision'>Listado de Encuestas</div><br />";

$filtro=strtr($filtro, "*", "'");
$filtro=strtr($filtro, ";", "%");
if ($filtro!="") $filtro=" ".$filtro;
else $filtro="";
//	CONSULTO LA TABLA SOLO PARA SABER EL TOTAL DE REGISTROS
$sql="SELECT Secuencia, PeriodoContable, Fecha, Titulo, Muestra FROM rh_encuestas WHERE Secuencia<>'0' ".$filtro;
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
?>

<table width="900" class="tblBotones">
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
		<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'encuestas_nuevo.php');" />
		<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'encuestas_editar.php', 'SELF');" />
		<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'encuestas_ver.php', 'BLANK', 'height=600, width=900, left=200, top=100, resizable=no');" />
		<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistro(this.form, 'encuestas.php?accion=ELIMINAR', '1', 'ENCUESTAS');" />
		<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'encuestas_pdf.php', 'height=800, width=800, left=200, top=200, resizable=yes');" />
	</td>
  </tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="900" class="tblLista">
  <tr class="trListaHead">
	<th width="125" scope="col">Periodo</th>
	<th width="125" scope="col">Fecha</th>
	<th scope="col">Titulo</th>
	<th width="125" scope="col">Muestra</th>
  </tr>
	<?php
	if ($registros!=0) {
		//	CONSULTO LA TABLA
		$sql="SELECT Secuencia, PeriodoContable, Fecha, Titulo, Muestra FROM rh_encuestas WHERE Secuencia<>'0' ".$filtro." ORDER BY Secuencia LIMIT ".$limit.", $MAXLIMIT";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//	MUESTRO LA TABLA
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			list($a, $m, $d)=SPLIT( '[/.-]', $field['Fecha']); $fecha=$d."-".$m."-".$a;
			echo "
			<tr class='trListaBody' onclick='mClk(this, \"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['Secuencia']."'>
				<td align='center'>".$field['PeriodoContable']."</td>
				<td align='center'>".$fecha."</td>
				<td>".$field['Titulo']."</td>
				<td align='center'>".$field['Muestra']."</td>
			</tr>";
		}
	}
	$rows=(int)$rows;
	echo "
	<script type='text/javascript' language='javascript'>
		totalRegistros($registros, \"$_ADMIN\", \"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
		totalLotes($registros, $rows, ".$limit.");
	</script>";
	?>
</table>
</form>
</body>
</html>