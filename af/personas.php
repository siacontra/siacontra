<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
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
		<td class="titulo">Maestro de Personas</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
$MAXLIMIT=30;
//	ELIMINO EL REGISTRO
if ($_GET['accion']=="ELIMINAR") {
	//
	$sql="DELETE FROM mastpersonas WHERE CodPersona='".$_POST['registro']."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$_GET['limit']=0;
}

if ($factualizar == "") $factualizar = "Empleado";

//	VALORES POR DEFECTO Y VALORES SELECCIONADOS DEL FILTRO AL CARGAR O RECARGAR LA PAGINA...............
if ($_POST['chkordenar']=="1") { $obj[0]="checked"; $obj[1]=""; $obj[2]=$_POST['fordenar']; }
else { $obj[0]=""; $obj[1]="disabled"; $obj[2]="0"; }
if ($_POST['chkedoreg']=="1") { $obj[3]="checked"; $obj[4]=""; $obj[5]=$_POST['fedoreg']; }
else { $obj[3]=""; $obj[4]="disabled"; $obj[5]="0"; }
if ($_POST['chkactualizar']=="1") { $obj[6]="checked"; $obj[7]=""; $obj[8]=$_POST['factualizar']; }
else { $obj[6]=""; $obj[7]="disabled"; $obj[8]="0"; }
if ($_POST['chkcpersona']=="1") { $obj[10]="checked"; $obj[11]=""; $obj[12]=$_POST['fcpersona']; }
else { $obj[10]=""; $obj[11]="disabled"; $obj[12]="0"; }
if ($_POST['chktpersona']=="1") { $obj[13]="checked"; $obj[14]=""; $obj[15]=$_POST['ftpersona']; }
else { $obj[13]="";	$obj[14]="disabled"; $obj[15]="0"; }
if ($_POST['chkbpersona']=="1") { $obj[16]="checked";	$obj[17]=""; $obj[18]=$_POST['fbpersona']; }
else { $obj[16]="";	$obj[17]="disabled"; $obj[18]=""; }
//
echo "
<form name='frmentrada' action='personas.php?filtro=".$_GET['filtro']."' method='POST' onsubmit='return false'>
<input type='hidden' name='limit' value='".$_GET['limit']."'>
<div class='divBorder' style='width:1000px;'>
<table width='1000' class='tblFiltro'>
	<tr>
		<td width='125' align='right'>Ordenar Por:</td>
		<td>
			<input type='checkbox' name='chkordenar' value='1' $obj[0] onclick='enabledOrdenarPersona(this.form);' />
			<select name='fordenar' id='fordenar' class='selectMed' $obj[1]>
				<option value=''></option>";
				getOrdenarPersona($obj[2], 0);
				echo "
			</select>
		</td>
		<td width='125' align='right'>Estado Reg.:</td>
		<td>
			<input type='checkbox' name='chkedoreg' value='1' $obj[3] onclick='enabledEdoReg(this.form);' />
			<select name='fedoreg' id='fedoreg' class='selectMed' $obj[4]>
				<option value=''></option>";
				getStatus($obj[5], 0);
				echo "
			</select>
		</td>
	</tr>
	<tr>
		<td width='125' align='right'>Actualizar:</td>
		<td>
			<input type='checkbox' name='chkactualizar' value='1' checked />";
			echo "
			<select name='factualizar' id='factualizar' class='selectMed' onchange='filtroPersonas(this.form, ".$_GET['limit'].");'>";
				getTPersona($factualizar, 0);
			echo "
			</select>
		</td>
		<td width='125' align='right'>Clase de Persona:</td>
		<td>
			<input type='checkbox' name='chkcpersona' value='1' $obj[10] onclick='enabledCPersona(this.form);' />
			<select name='fcpersona' id='fcpersona' class='selectMed' $obj[11]>
				<option value=''></option>";
				getCPersona($obj[12], 0);
				echo "
			</select>
		</td>
	</tr>
	<tr>
		<td width='125' align='right'>Tipo de Persona:</td>
		<td>
			<input type='checkbox' name='chktpersona' value='1' $obj[13] onclick='enabledTPersona(this.form);' />
			<select name='ftpersona' id='ftpersona' class='selectMed' $obj[14]>
				<option value=''></option>";
				getTPersona($obj[15], 0);
				echo "
			</select>
		</td>
		<td width='125' align='right'>Buscar:</td>
		<td>
			<input type='checkbox' name='chkbpersona' value='1' $obj[16] onclick='enabledBPersona(this.form);' />
			<input type='text' name='fbpersona' size='50' $obj[17] value='$obj[18]' />
		</td>
	</tr>
</table>
</div>
<center><input type='button' name='btBuscar' value='Buscar' onclick='filtroPersonas(this.form, ".$_GET['limit'].");'></center>
<br /><div class='divDivision'>Lista de Personas</div><br />";

$_GET['filtro']=strtr($_GET['filtro'], "*", "'");
$_GET['filtro']=strtr($_GET['filtro'], ";", "%");
//	CONSULTO LA TABLA PARA SABER EL TOTAL DE REGISTROS SOLAMENTE............
if ($_GET['filtro']!="") $sql="SELECT * FROM mastpersonas WHERE (".$_GET['filtro'].") ".$_GET['ordenar'];
else $sql="SELECT * FROM mastpersonas ".$_GET['ordenar'];
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
?>

<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td width="250">
			<?php 
			echo "
			<table align='center'>
				<tr>
					<td>
						<input name='btPrimero' type='button' id='btPrimero' value='&lt;&lt;' onclick='setLotes(this.form, \"P\", $registros, ".$_GET['limit'].", \"".$_GET['ordenar']."\");' />
						<input name='btAtras' type='button' id='btAtras' value='&lt;' onclick='setLotes(this.form, \"A\", $registros, ".$_GET['limit'].", \"".$_GET['ordenar']."\");' />
					</td>
					<td>Del</td><td><div id='desde'></div></td>
					<td>Al</td><td><div id='hasta'></div></td>
					<td>
						<input name='btSiguiente' type='button' id='btSiguiente' value='&gt;' onclick='setLotes(this.form, \"S\", $registros, ".$_GET['limit'].", \"".$_GET['ordenar']."\");' />
						<input name='btUltimo' type='button' id='btUltimo' value='&gt;&gt;' onclick='setLotes(this.form, \"U\", $registros, ".$_GET['limit'].", \"".$_GET['ordenar']."\");' />
					</td>
				</tr>
			</table>";
			?>
		</td>
		<td align="right">
			<? if ($factualizar == "Proveedor") $bteditar = "personas_proveedor_editar"; else $bteditar = "personas_editar"; ?>
			<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'personas_nuevo.php');" />
			<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, '<?=$bteditar?>.php?accion=EDITAR', 'SELF');" />
			<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'personas_ver.php', 'BLANK', 'height=625, width=1000, left=0, top=0, resizable=yes');" />
			<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistro(this.form, 'personas.php?accion=ELIMINAR', '1', 'PERSONAS');" />
			<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'personas_pdf.php', 'height=800, width=800, left=200, top=200, resizable=yes');" />
		</td>
	</tr>
</table>
<input type="hidden" name="registro" id="registro" />
<table width="1000" class="tblLista">
	<tr class="trListaHead">
		<th width="70" scope="col">Persona</th>
		<th scope="col">B&uacute;squeda</th>
		<th width="25" scope="col">Cli</th>
		<th width="25" scope="col">Pro</th>
		<th width="25" scope="col">Emp</th>
		<th width="25" scope="col">Otr</th>
		<th width="90" scope="col">Nro. Documento</th>
		<th width="90" scope="col">Documento Fiscal</th>
	</tr>
	<?php 
	if ($registros!=0) {
		//	CONSULTO LA TABLA
		if ($_GET['filtro']!="") $sql="SELECT CodPersona, Busqueda, EsCliente, EsProveedor, EsEmpleado, EsOtros, Ndocumento, DocFiscal FROM mastpersonas WHERE (".$_GET['filtro'].") ".$_GET['ordenar']." LIMIT ".$_GET['limit'].", $MAXLIMIT";
		else $sql="SELECT CodPersona, Busqueda, EsCliente, EsProveedor, EsEmpleado, EsOtros, Ndocumento, DocFiscal FROM mastpersonas ".$_GET['ordenar']." LIMIT ".$_GET['limit'].", $MAXLIMIT";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//	MUESTRO LA TABLA
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			if ($field['EsCliente']=="S") $escliente="checked"; else $escliente="";
			if ($field['EsProveedor']=="S") $esproveedor="checked"; else $esproveedor="";
			if ($field['EsEmpleado']=="S") $esempleado="checked"; else $esempleado="";
			if ($field['EsOtros']=="S") $esotros="checked"; else $esotros="";
			echo "
			<tr class='trListaBody' onclick='mClk(this, \"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['CodPersona']."'>
				<td align='center'>".$field['CodPersona']."</td>
				<td align='left'>".htmlentities($field['Busqueda'])."</td>
				<td align='center'><input type='checkbox' $escliente disabled /></td>
				<td align='center'><input type='checkbox' $esproveedor disabled /></td>
				<td align='center'><input type='checkbox' $esempleado disabled /></td>
				<td align='center'><input type='checkbox' $esotros disabled /></td>
				<td align='left'>".$field['Ndocumento']."</td>
				<td align='left'>".$field['DocFiscal']."</td>
			</tr>";
		}
	}
	$rows=(int)$rows;
	echo "
	<script type='text/javascript' language='javascript'>
		totalRegistros($registros, \"$_ADMIN\", \"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
		totalLotes($registros, $rows, ".$_GET['limit'].");
	</script>";				
	?>
</table>
</form>
</body>
</html>