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

<?php echo"<form name='frmentrada' action='contratos_sin.php?filtro=".$_GET['filtro']."' method='POST'>"; ?>
<?php echo"<input type='hidden' name='filtro' id='filtro' value='".$_GET['filtro']."'>"; ?>
<?php
$MAXLIMIT=30;
//	ACTUALIZO LA TABLA rh_contratos
$sql="UPDATE rh_contratos SET Estado='VE' WHERE NOW() >= FechaHasta AND (FechaHasta <> '0000-00-00' AND FechaDesde <> '0000-00-00')";
$query=mysql_query($sql) or die ($sql.mysql_error());

$_GET['filtro']=strtr($_GET['filtro'], "*", "'");
$_GET['filtro']=strtr($_GET['filtro'], ";", "%");

//	CONSULTO LA TABLA PARA SABER EL TOTAL DE REGISTROS SOLAMENTE.....
if ($_GET['filtro']!="") $filtrado=$_GET['filtro'];
$sql="SELECT rc.CodPersona, mp.NomCompleto, rtc.Descripcion AS TipoContrato, me.Fingreso, me.CodEmpleado, rp.DescripCargo, mo.Organismo FROM rh_contratos rc INNER JOIN mastpersonas mp ON (rc.CodPersona=mp.CodPersona) LEFT JOIN rh_tipocontrato rtc ON (rc.TipoContrato=rtc.TipoContrato) INNER JOIN mastempleado me ON (rc.CodPersona=me.CodPersona) INNER JOIN mastorganismos mo ON (me.CodOrganismo=mo.CodOrganismo) INNER JOIN rh_puestos rp ON (me.CodCargo=rp.CodCargo) INNER JOIN seguridad_alterna sa ON (me.CodDependencia=sa.CodDependencia AND sa.CodAplicacion='".$_SESSION["APLICACION_ACTUAL"]."' AND sa.Usuario='".$_SESSION["USUARIO_ACTUAL"]."' AND sa.FlagMostrar='S') WHERE rc.TipoContrato='' $filtrado ORDER BY rc.CodPersona";
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);

?>

<table width="1000" class="tblBotones">
	<tr>
		<td width="200"><div id="rows"></div></td>
		<td width="300" align="center">
			<?php 
			echo "
			<table align='center'>
				<tr>
					<td>
						<input name='btPrimero' type='button' id='btPrimero' value='&lt;&lt;' onclick='setLotes(this.form, \"P\", $registros, ".$_GET['limit'].");' />
						<input name='btAtras' type='button' id='btAtras' value='&lt;' onclick='setLotes(this.form, \"A\", $registros, ".$_GET['limit'].");' />
					</td>
					<td>Del</td><td><div id='desde'></div></td>
					<td>Al</td><td><div id='hasta'></div></td>
					<td>
						<input name='btSiguiente' type='button' id='btSiguiente' value='&gt;' onclick='setLotes(this.form, \"S\", $registros, ".$_GET['limit'].");' />
						<input name='btUltimo' type='button' id='btUltimo' value='&gt;&gt;' onclick='setLotes(this.form, \"U\", $registros, ".$_GET['limit'].");' />
					</td>
				</tr>
			</table>";
			?>
			
		</td>
		<td width="500" align="right">
			<input name="btNuevo" type="button" id="btNuevo" class="btLista" value="Nuevo" disabled onclick="cargarOpcion2(this.form, 'contratos_nuevo.php?filtro='+document.getElementById('filtro').value, 'BLANK', 'height=450, width=800, left=100, top=100, resizable=no');" />
			<input name="btEditar" type="button" id="btEditar" class="btLista" value="Editar" disabled />
			<input name="btVer" type="button" id="btVer" class="btLista" value="Ver" disabled />
			<input name="btEliminar" type="button" id="btEliminar" class="btLista" value="Eliminar" disabled /> |
			<input name="btAbrir" type="button" id="btAbrir" class="btLista" value="Abrir" disabled />
			<input name="btImprimir" type="button" id="btImprimir" class="btLista" value="Imprimir" disabled />
		</td>
	</tr>
</table>

<?php 
if ($registros!=0) {
	//	CONSULTO LA TABLA
$sql="SELECT rc.CodPersona, mp.NomCompleto, rtc.Descripcion AS TipoContrato, me.Fingreso, me.CodEmpleado, rp.DescripCargo, mo.Organismo FROM rh_contratos rc INNER JOIN mastpersonas mp ON (rc.CodPersona=mp.CodPersona) LEFT JOIN rh_tipocontrato rtc ON (rc.TipoContrato=rtc.TipoContrato) INNER JOIN mastempleado me ON (rc.CodPersona=me.CodPersona) INNER JOIN mastorganismos mo ON (me.CodOrganismo=mo.CodOrganismo) INNER JOIN rh_puestos rp ON (me.CodCargo=rp.CodCargo) INNER JOIN seguridad_alterna sa ON (me.CodDependencia=sa.CodDependencia AND sa.CodAplicacion='".$_SESSION["APLICACION_ACTUAL"]."' AND sa.Usuario='".$_SESSION["USUARIO_ACTUAL"]."' AND sa.FlagMostrar='S') WHERE rc.TipoContrato='' $filtrado ORDER BY rc.CodPersona LIMIT ".$_GET['limit'].", $MAXLIMIT";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	//	MUESTRO LA TABLA
	echo "
	<input type='hidden' name='registro' id='registro' />
	<table width='1000' class='tblLista'>
	  <tr class='trListaHead'>
			<th width='75' scope='col'>Persona</th>
			<th width='225' scope='col'>Nombre Completo</th>
			<th width='100' scope='col'>Tipo de Contrato</th>
			<th width='75' scope='col'>Fecha de Ingreso</th>
			<th width='175' scope='col'>Cargo</th>
			<th width='225' scope='col'>Organismo</th>
		</tr>";
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		list($a, $m, $d)=SPLIT( '[/.-]', $field['Fingreso']); $fingreso=$d."-".$m."-".$a;
		echo "
		<tr class='trListaBody' onclick='mClk(this, \"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field[0]."'>
			<td align='center'>".$field['CodPersona']."</td>
			<td>".($field['NomCompleto'])."</td>
			<td align='center'>".$field['TipoContrato']."</td>
			<td align='center'>".$fingreso."</td>
			<td>".$field['DescripCargo']."</td>
			<td>".($field['Organismo'])."</td>
		</tr>";
	}
	echo "</table>";
}
$rows=(int)$rows;
echo "
<script type='text/javascript' language='javascript'>
	totalContratos(4, $registros, \"$_ADMIN\", \"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
	totalLotes($registros, $rows, ".$_GET['limit'].");
</script>";
?>

</form>
</body>
</html>