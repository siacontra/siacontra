<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_lg.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Proveedores</td>
		<td align="right"><a class="cerrar"; href="javascript:window.close();">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
$MAXLIMIT=30;
//	CONSULTO LA TABLA PARA SABER EL TOTAL DE REGISTROS SOLAMENTE............
if ($filtro!="") $filtro = " WHERE(mp.CodPersona LIKE '%".$filtro."%' OR mp.Busqueda LIKE '%".$filtro."%' OR mp.Ndocumento LIKE '%".$filtro."%' OR mp.DocFiscal LIKE '%".$filtro."%') ";

$sql="SELECT
			mp.CodPersona,
			mp.NomCompleto, 
			mp.EsCliente, 
			mp.EsProveedor, 
			mp.EsEmpleado, 
			mp.EsOtros, 
			mp.Ndocumento, 
			mp.DocFiscal,
			pr.CodFormaPago
		FROM
			mastpersonas mp
			INNER JOIN mastproveedores pr ON (mp.CodPersona = pr.CodProveedor)
		$filtro";
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
?>
<form name='frmlista' id='frmlista' method='post' action='lista_proveedores_insertar.php'>
<input type="hidden" name="limit" id="limit" value="<?=$limit?>" />
<input type="hidden" name="ventana_desde" id="ventana_desde" value="<?=$ventana_desde?>" />
<table width="700" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td>Buscar: <input type="text" name="filtro" id="filtro" size="40" /></td>
		<td width="250">
			<?php 
			echo "
			<table align='center'>
				<tr>
					<td>
						<input name='btPrimero' type='button' id='btPrimero' value='&lt;&lt;' onclick='setLotes(this.form, \"P\", $registros, ".$limit.", \"".$_GET['ordenar']."\");' />
						<input name='btAtras' type='button' id='btAtras' value='&lt;' onclick='setLotes(this.form, \"A\", $registros, ".$limit.", \"".$_GET['ordenar']."\");' />
					</td>
					<td>Del</td><td><div id='desde'></div></td>
					<td>Al</td><td><div id='hasta'></div></td>
					<td>
						<input name='btSiguiente' type='button' id='btSiguiente' value='&gt;' onclick='setLotes(this.form, \"S\", $registros, ".$limit.", \"".$_GET['ordenar']."\");' />
						<input name='btUltimo' type='button' id='btUltimo' value='&gt;&gt;' onclick='setLotes(this.form, \"U\", $registros, ".$limit.", \"".$_GET['ordenar']."\");' />
					</td>
				</tr>
			</table>";
			?>
		</td>
	</tr>
</table>
<input type="hidden" name="registro" id="registro" />
<table width="700" class="tblLista">
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
		$sql="SELECT
					mp.CodPersona,
					mp.NomCompleto, 
					mp.EsCliente, 
					mp.EsProveedor, 
					mp.EsEmpleado, 
					mp.EsOtros, 
					mp.Ndocumento, 
					mp.DocFiscal,
					pr.CodFormaPago
				FROM
					mastpersonas mp
					INNER JOIN mastproveedores pr ON (mp.CodPersona = pr.CodProveedor)
				$filtro
				ORDER BY CodPersona LIMIT ".$limit.", $MAXLIMIT";
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
			<tr class='trListaBody' onclick='mClk(this, \"registro\"); insertarProveedorCotizacion(\"".$field['CodPersona']."\", \"".($field['NomCompleto'])."\", \"".$field['CodFormaPago']."\", \"".$ventana_desde."\");' id='".$field['CodPersona']."'>
				<td align='center'>".$field['CodPersona']."</td>
				<td align='left'>".($field['NomCompleto'])."</td>
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
		totalLista($registros);
		totalLotes($registros, $rows, ".$limit.");
	</script>";				
	?>
</table>
</form>
</body>
</html>