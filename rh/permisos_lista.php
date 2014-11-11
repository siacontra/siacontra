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
		<td class="titulo">Permisos del Empleado</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
$MAXLIMIT=30;
if ($filtrar=="DEFAULT") {
	$annio_actual=date("Y");
	$mes_actual=date("m"); $m=(int) $mes_actual;
	$dia_actual=date("d");	
	$fecha_desde="$annio_actual-$mes_actual-01";
	$fecha_hasta="$annio_actual-$mes_actual-$dia_actual";	
	$_GET['filtro']="(rh_permisos.FechaIngreso>=*$fecha_desde* AND rh_permisos.FechaIngreso<=*$fecha_hasta*) AND (mastempleado.CodOrganismo=*".$_SESSION["FILTRO_ORGANISMO_ACTUAL"]."* AND mastempleado.CodDependencia=*".$_SESSION["FILTRO_DEPENDENCIA_ACTUAL"]."*)";
	$_POST['chkfingreso']="1";
	$_POST['ffingresod']="01-$mes_actual-$annio_actual";
	$_POST['ffingresoh']="$dia_actual-$mes_actual-$annio_actual";
	$_POST['chkorganismo']="1"; 
	$_POST['chkdependencia']="1"; 
	$_POST['forganismo']=$_SESSION["FILTRO_ORGANISMO_ACTUAL"]; 
	$_POST['fdependencia']=$_SESSION["FILTRO_DEPENDENCIA_ACTUAL"];
}
//	VALORES POR DEFECTO Y VALORES SELECCIONADOS DEL FILTRO AL CARGAR O RECARGAR LA PAGINA...............
if ($_POST['chkorganismo']=="1") { $obj[0]="checked"; $obj[1]=""; $obj[2]=$_POST['forganismo']; }
else { $obj[0]=""; $obj[1]="disabled"; $obj[2]=""; }
if ($_POST['chkpermiso']=="1") { $obj[3]="checked"; $obj[4]=""; $obj[5]=$_POST['fpermiso']; }
else { $obj[3]=""; $obj[4]="disabled"; $obj[5]=""; }
if ($_POST['chkdependencia']=="1") { $obj[6]="checked"; $obj[7]=""; $obj[9]=$_POST['fdependencia']; }
else { $obj[6]=""; $obj[7]="disabled"; $obj[9]=""; }
if ($_POST['chkfingreso']=="1") { $obj[10]="checked"; $obj[11]=""; $obj[12]=$_POST['ffingresod']; $obj[13]=$_POST['ffingresoh']; }
else { $obj[10]=""; $obj[11]="disabled"; $obj[12]=""; $obj[13]=""; }
if ($_POST['chkempleado']=="1") { $obj[14]="checked"; $obj[15]=""; $obj[16]=$_POST['fempleado']; }
else { $obj[14]=""; $obj[15]="disabled"; $obj[16]=""; }
if ($_POST['chkstatus']=="1") { $obj[17]="checked"; $obj[18]=""; $obj[19]=$_POST['fstatus']; }
else { $obj[17]=""; $obj[18]="disabled"; $obj[19]="0"; }
echo "
<form name='frmentrada' action='permisos_lista.php?filtro=".$_GET['filtro']."' method='POST' onsubmit='return false'>
<input type='hidden' name='limit' id='limit' value='".$_GET['limit']."'>
<input type='hidden' name='filtro' id='filtro' value='".$_GET['filtro']."'>
<div class='divBorder' style='width:900px;'>
<table width='900' class='tblFiltro'>
	<tr>
		<td width='125' align='right'>Organismo:</td>
		<td>
			<input type='checkbox' name='chkorganismo' id='chkorganismo' value='1' $obj[0] onclick='enabledOrganismo(this.form);' />
			<select name='forganismo' id='forganismo' class='selectBig' $obj[1] onchange='getFOptions_2(this.id, \"fdependencia\", \"chkdependencia\");'>
				<option value=''></option>";
				getOrganismos($obj[2], 3);
				echo "
			</select>
		</td>
		<td width='125' align='right'>Nro. Permiso:</td>
		<td>
			<input type='checkbox' name='chkpermiso' value='1' $obj[3] onclick='enabledPermiso(this.form);' />
			<input type='text' name='fpermiso' size='15' maxlength='10' $obj[4] value='$obj[5]' />
		</td>
	</tr>
	<tr>
		<td width='125' align='right'>Dependencia:</td>
		<td>
			<input type='checkbox' name='chkdependencia' id='chkdependencia' value='1' $obj[6] onclick='enabledDependencia(this.form);' />";
			echo "
			<select name='fdependencia' id='fdependencia' class='selectBig' $obj[7]>
				<option value=''></option>";
				getDependencias($obj[9], $obj[2], 3);
			echo "
			</select>
		</td>
		<td width='125' align='right'>Fecha de Registro:</td>
		<td>
			<input type='checkbox' name='chkfingreso' value='1' $obj[10] onclick='enabledFIngreso(this.form);' />
			<input type='text' name='ffingresod' size='15' maxlength='10' $obj[11] value='$obj[12]' /> - 
			<input type='text' name='ffingresoh' size='15' maxlength='10' $obj[11] value='$obj[13]' />
		</td>
	</tr>
	<tr>
		<td width='125' align='right'>Empleado:</td>
		<td>
			<input type='checkbox' name='chkempleado' value='1' $obj[14] onclick='enabledEmpleado(this.form);' />
			<input type='text' name='fempleado' size='8' maxlength='6' $obj[15] value='$obj[16]' />
		</td>
		<td width='125' align='right' rowspan='2'>Estado:</td>
		<td>
			<input type='checkbox' name='chkstatus' value='1' disabled checked onclick='enabledStatus(this.form);' />
			<select name='fstatus' id='fstatus' class='selectMed' $obj[18]>";
				getStatusPermisos("P", 1);
				echo "
			</select>
		</td>
	</tr>
</table>
</div>
<center><input type='button' name='btBuscar' value='Buscar' onclick='filtroPermisos(this.form, ".$_GET['limit'].", \"permisos_lista.php\");'></center>
<br /><div class='divDivision'>Lista de Permisos</div><br />";

$_GET['filtro']=strtr($_GET['filtro'], "*", "'");
$_GET['filtro']=strtr($_GET['filtro'], ";", "%");
if ($_GET['filtro']!="") $_GET['filtro']="AND (".$_GET['filtro'].")";
//	CONSULTO LA TABLA SOLO PARA SABER EL TOTAL DE REGISTROS
$sql="SELECT rh_permisos.CodPermiso, mastempleado.CodEmpleado, mastpersonas.NomCompleto, rh_permisos.TipoFalta, rh_permisos.FechaIngreso, (SELECT mastmiscelaneosdet.Descripcion FROM mastmiscelaneosdet WHERE mastmiscelaneosdet.CodMaestro='PERMISOS' AND mastmiscelaneosdet.CodDetalle=rh_permisos.TipoPermiso) AS TipoPermiso, (SELECT mastmiscelaneosdet.Descripcion FROM mastmiscelaneosdet WHERE mastmiscelaneosdet.CodMaestro='TIPOFALTAS' AND mastmiscelaneosdet.CodDetalle=rh_permisos.TipoFalta) AS TipoFalta, rh_permisos.Estado, seguridad_alterna.CodOrganismo FROM rh_permisos, mastempleado, mastpersonas, seguridad_alterna WHERE (rh_permisos.CodPersona=mastpersonas.CodPersona) AND (mastpersonas.CodPersona=mastempleado.CodPersona) AND (rh_permisos.Estado='P') AND (mastempleado.CodDependencia=seguridad_alterna.CodDependencia AND seguridad_alterna.CodAplicacion='".$_SESSION["APLICACION_ACTUAL"]."' AND seguridad_alterna.Usuario='".$_SESSION["USUARIO_ACTUAL"]."' AND seguridad_alterna.FlagMostrar='S') ".$_GET['filtro'];
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
    <td align="right">
		<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'permisos_ver.php', 'BLANK', 'height=500, width=750, left=200, top=200, resizable=no');" /> | 
		<input name="btAprobar" type="button" class="btLista" id="btAprobar" value="Aprobar" onclick="cargarOpcion(this.form, 'permisos_aprobar.php?btAprobar=1', 'SELF');" />
		<input name="btRechazar" type="button" class="btLista" id="btRechazar" value="Rechazar" onclick="cargarOpcion(this.form, 'permisos_aprobar.php?btAprobar=0', 'SELF');" style="display:none;" />
	</td>
  </tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="900" class="tblLista">
  <tr class="trListaHead">
	<th width="100" scope="col"># Permiso</th>
	<th width="75" scope="col">Empleado</th>
	<th scope="col">Nombre Completo</th>
	<th width="150" scope="col">Tipo Permiso</th>	
	<th width="150" scope="col">Tipo Falta</th>	
	<th width="100" scope="col">F.Registro</th>
	<th width="75" scope="col">Estado</th>
  </tr>
	<?php
	if ($registros!=0) {
		//	CONSULTO LA TABLA
		$sql="SELECT rh_permisos.CodPermiso, mastempleado.CodEmpleado, mastpersonas.NomCompleto, rh_permisos.TipoFalta, rh_permisos.FechaIngreso, (SELECT mastmiscelaneosdet.Descripcion FROM mastmiscelaneosdet WHERE mastmiscelaneosdet.CodMaestro='PERMISOS' AND mastmiscelaneosdet.CodDetalle=rh_permisos.TipoPermiso) AS TipoPermiso, (SELECT mastmiscelaneosdet.Descripcion FROM mastmiscelaneosdet WHERE mastmiscelaneosdet.CodMaestro='TIPOFALTAS' AND mastmiscelaneosdet.CodDetalle=rh_permisos.TipoFalta) AS TipoFalta, rh_permisos.Estado, seguridad_alterna.CodOrganismo FROM rh_permisos, mastempleado, mastpersonas, seguridad_alterna WHERE (rh_permisos.CodPersona=mastpersonas.CodPersona) AND (mastpersonas.CodPersona=mastempleado.CodPersona) AND (rh_permisos.Estado='P') AND (mastempleado.CodDependencia=seguridad_alterna.CodDependencia AND seguridad_alterna.CodAplicacion='".$_SESSION["APLICACION_ACTUAL"]."' AND seguridad_alterna.Usuario='".$_SESSION["USUARIO_ACTUAL"]."' AND seguridad_alterna.FlagMostrar='S') ".$_GET['filtro']." ORDER BY rh_permisos.CodPermiso LIMIT ".$_GET['limit'].", $MAXLIMIT";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//	MUESTRO LA TABLA
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaIngreso']); $fingreso=$d."-".$m."-".$a;
			if ($field["Estado"]=="P") $status="PENDIENTE";
			if ($field["Estado"]=="A") $status="APROBADO";
			if ($field["Estado"]=="R") $status="RECHAZADO";
			if ($field["Estado"]=="N") $status="ANULADO";			
			echo "
			<tr class='trListaBody' onclick='mClk(this, \"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['CodPermiso']."'>
				<td align='center'>".$field['CodPermiso']."</td>
				<td align='center'>".$field['CodEmpleado']."</td>
				<td>".$field['NomCompleto']."</td>
				<td>".$field['TipoPermiso']."</td>
				<td align='center'>".$field['TipoFalta']."</td>
				<td align='center'>".$fingreso."</td>
				<td align='center'>".$status."</td>
			</tr>";
		}
	}
	$rows=(int)$rows;
	echo "
	<script type='text/javascript' language='javascript'>
		totalPermisosLista($registros, \"$_ADMIN\", \"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
		totalLotes($registros, $rows, ".$_GET['limit'].");
	</script>";
	?>
</table>
</form>
</body>
</html>