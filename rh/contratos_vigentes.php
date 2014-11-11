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

<?php echo"<form name='frmentrada' action='contratos_vigentes.php?filtro=".$_GET['filtro']."' method='POST'>"; ?>
<?php echo"<input type='hidden' name='filtro' id='filtro' value='".$_GET['filtro']."'>"; ?>
<?php
$MAXLIMIT=30;
//	ACTUALIZO LA TABLA rh_contratos
$sql="UPDATE rh_contratos SET Estado='VE' WHERE NOW() >= FechaHasta AND (FechaHasta <> '0000-00-00' AND FechaDesde <> '0000-00-00')";
$query=mysql_query($sql) or die ($sql.mysql_error());

$_GET['filtro']=strtr($_GET['filtro'], "*", "'"); 
$_GET['filtro']=strtr($_GET['filtro'], ";", "%");

//	CONSULTO LA TABLA PARA SABER EL TOTAL DE REGISTROS SOLAMENTE............
if ($_GET['filtro']!="") $filtrado=$_GET['filtro'];
$sql="SELECT rc.CodPersona, mp.NomCompleto, rtc.Descripcion AS TipoContrato, me.Fingreso, me.CodEmpleado, rp.DescripCargo, mo.Organismo, rc.FechaDesde, rc.FechaHasta, rc.FlagFirma, rc.FechaFirma, DATEDIFF(rc.FechaHasta, NOW()) AS Faltan FROM rh_contratos rc INNER JOIN mastpersonas mp ON (rc.CodPersona=mp.CodPersona) LEFT JOIN rh_tipocontrato rtc ON (rc.TipoContrato=rtc.TipoContrato) INNER JOIN mastempleado me ON (rc.CodPersona=me.CodPersona) INNER JOIN mastorganismos mo ON (me.CodOrganismo=mo.CodOrganismo) INNER JOIN rh_puestos rp ON (me.CodCargo=rp.CodCargo) INNER JOIN seguridad_alterna sa ON (me.CodDependencia=sa.CodDependencia AND sa.CodAplicacion='".$_SESSION["APLICACION_ACTUAL"]."' AND sa.Usuario='".$_SESSION["USUARIO_ACTUAL"]."' AND sa.FlagMostrar='S') WHERE rc.Estado='VI' $filtrado ORDER BY rc.CodPersona";
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
						<input name='btPrimero' type='button' id='btPrimero' value='&lt;&lt;' disabled onclick='setLotes(this.form, \"P\", $registros, ".$_GET['limit'].");' />
						<input name='btAtras' type='button' id='btAtras' value='&lt;' disabled onclick='setLotes(this.form, \"A\", $registros, ".$_GET['limit'].");' />
					</td>
					<td>Del</td><td><div id='desde'></div></td>
					<td>Al</td><td><div id='hasta'></div></td>
					<td>
						<input name='btSiguiente' type='button' id='btSiguiente' value='&gt;' disabled onclick='setLotes(this.form, \"S\", $registros, ".$_GET['limit'].");' />
						<input name='btUltimo' type='button' id='btUltimo' value='&gt;&gt;' disabled onclick='setLotes(this.form, \"U\", $registros, ".$_GET['limit'].");' />
					</td>
				</tr>
			</table>";
			?> 
			
		</td>
		<td width="500" align="right">
			<input name="btNuevo" type="button" id="btNuevo" class="btLista" value="Nuevo" disabled />
			<input name="btEditar" type="button" id="btEditar" class="btLista" value="Editar" onclick="cargarOpcion2(this.form, 'contratos_editar.php?filtro='+document.getElementById('filtro').value, 'BLANK', 'height=500, width=800, left=100, top=100, resizable=no');" />
			<input name="btVer" type="button" id="btVer" class="btLista" value="Ver" onclick="cargarOpcion(this.form, 'contratos_ver.php', 'BLANK', 'height=450, width=800, left=100, top=100, resizable=no');" />
			<input name="btEliminar" type="button" id="btEliminar" class="btLista" value="Eliminar" /> |
			<input name="btAbrir" type="button" id="btAbrir" class="btLista" value="Abrir" onclick="abrirContrato(this.form, 'contratos_abrir.php', 'height=500, width=700, left=100, top=100, resizable=no');" />
			<input name="btImprimir" type="button" id="btImprimir" class="btLista" value="Imprimir" />
		</td>
	</tr>
</table>

<?php 
if ($registros!=0) {
	//	CONSULTO LA TABLA
$sql="SELECT rc.CodPersona, mp.NomCompleto, rtc.Descripcion AS TipoContrato, me.Fingreso, me.CodEmpleado, rp.DescripCargo, mo.Organismo, rc.FechaDesde, rc.FechaHasta, rc.FlagFirma, rc.FechaFirma, DATEDIFF(rc.FechaHasta, NOW()) AS Faltan FROM rh_contratos rc INNER JOIN mastpersonas mp ON (rc.CodPersona=mp.CodPersona) LEFT JOIN rh_tipocontrato rtc ON (rc.TipoContrato=rtc.TipoContrato) INNER JOIN mastempleado me ON (rc.CodPersona=me.CodPersona) INNER JOIN mastorganismos mo ON (me.CodOrganismo=mo.CodOrganismo) INNER JOIN rh_puestos rp ON (me.CodCargo=rp.CodCargo) INNER JOIN seguridad_alterna sa ON (me.CodDependencia=sa.CodDependencia AND sa.CodAplicacion='".$_SESSION["APLICACION_ACTUAL"]."' AND sa.Usuario='".$_SESSION["USUARIO_ACTUAL"]."' AND sa.FlagMostrar='S') WHERE rc.Estado='VI' $filtrado ORDER BY rc.CodPersona LIMIT ".$_GET['limit'].", $MAXLIMIT";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	//	MUESTRO LA TABLA
	echo "
	<input type='hidden' name='registro' id='registro' />
	<table width='1000' class='tblLista'>
	  <tr class='trListaHead'>
			<th width='25' scope='col'>Sel.</th>
			<th width='75' scope='col'>Persona</th>
			<th scope='col'>Nombre Completo</th>
			<th width='125' scope='col'>Tipo de Contrato</th>
			<th width='75' scope='col'>Inicio de Contrato</th>
			<th width='75' scope='col'>Fin de Contrato</th>
			<th width='50' scope='col'>Firma?</th>
			<th width='75' scope='col'>Fecha de Firma</th>
			<th width='75' scope='col'>Dias para Vencer</th>
			
		</tr>";
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaDesde']); $vcond=$d."-".$m."-".$a;
		list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaHasta']); $vconh=$d."-".$m."-".$a;
		list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaFirma']); $firma=$d."-".$m."-".$a;
		echo "
		<tr class='trListaBody' onclick='mClk(this, \"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['CodPersona']."'>
			<td align='center'><input type='checkbox' name='sel' value='".$field['CodPersona']."' id='s_".$field['CodPersona']."' /> </td>
			<td align='center'>".$field['CodPersona']."</td>
			<td>".($field['NomCompleto'])."</td>
			<td align='center'>".$field['TipoContrato']."</td>
			<td align='center'>".$vcond."</td>
			<td align='center'>".$vconh."</td>
			<td align='center'>".$field['FlagFirma']."</td>
			<td align='center'>".$firma."</td>
			<td align='center'>".$field['Faltan']."</td>
		</tr>";
	}
	echo "</table>";
}
$rows=(int)$rows;
echo "
<script type='text/javascript' language='javascript'>
	totalContratos(1, $registros, \"$_ADMIN\", \"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
	totalLotes($registros, $rows, ".$_GET['limit'].");
</script>";
?>

</form>
</body>
</html>