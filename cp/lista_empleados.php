<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
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
		<td class="titulo">Lista de Empleados</td>
		<td align="right"><a class="cerrar"; href="javascript:window.close();">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
$MAXLIMIT=30;
//	CONSULTO LA TABLA PARA SABER EL TOTAL DE REGISTROS SOLAMENTE............
if ($_POST['filtro']!="") $sql="SELECT * FROM mastempleado, mastpersonas WHERE (mastempleado.CodPersona LIKE '%".$_POST['filtro']."%' OR mastpersonas.Busqueda LIKE '%".$_POST['filtro']."%' OR mastpersonas.Ndocumento LIKE '%".$_POST['filtro']."%' OR mastpersonas.DocFiscal LIKE '%".$_POST['filtro']."%') AND (mastpersonas.CodPersona=mastempleado.CodPersona) AND (mastpersonas.EsEmpleado='S')";
else $sql="SELECT * FROM mastempleado, mastpersonas WHERE (mastpersonas.CodPersona=mastempleado.CodPersona) AND (mastpersonas.EsEmpleado='S') AND
      (mastempleado.Estado='A')"; //echo $sql;
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query); //echo $registros;
?>
<form name="frmlista" id="frmlista" method="post" action="lista_empleados.php?limit=<?=$limit?>&campo=<?=$campo;?>&ventana=<?=$ventana;?>">
<input type="hidden" name="tabla" id="tabla" value="<?=$tabla?>" />
<input type="hidden" name="limit" id="limit" value="<?=$_GET['limit']?>"/>
<input type="hidden" name="campo" id="campo" value="<?=$_GET['campo']?>"/>
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="ventana" id="ventana" value="<?=$_GET['ventana']?>"/>
<table width="700" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td width="250">
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
		<td align="center">
			<input name="filtro" type="text" id="filtro" size="30" value="<?=$_POST['filtro']?>" /><input type="submit" value="Buscar" />
		</td>
	</tr>
</table>
<table width="700" class="tblLista">
	<tr class="trListaHead">
		<th width="70" scope="col">Empleado</th>
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
		if ($_POST['filtro']!="") $sql="SELECT mastpersonas.NomCompleto,mastempleado.CodEmpleado, mastempleado.CodPersona, mastpersonas.Busqueda, mastpersonas.EsCliente, mastpersonas.EsProveedor, mastpersonas.EsEmpleado, mastpersonas.EsOtros, mastpersonas.Ndocumento, mastpersonas.DocFiscal,mastempleado.CodCargo,rh_puestos.DescripCargo FROM mastempleado, mastpersonas, rh_puestos WHERE (mastempleado.CodPersona LIKE '%".$_POST['filtro']."%' OR mastpersonas.Busqueda LIKE '%".$_POST['filtro']."%' OR mastpersonas.Ndocumento LIKE '%".$_POST['filtro']."%' OR mastpersonas.DocFiscal LIKE '%".$_POST['filtro']."%') AND (mastpersonas.CodPersona=mastempleado.CodPersona) AND (mastpersonas.EsEmpleado='S') AND (mastempleado.Estado='A')AND
      (mastempleado.CodCargo=rh_puestos.CodCargo) ORDER BY mastempleado.CodPersona LIMIT ".$_GET['limit'].", $MAXLIMIT";
		else $sql="SELECT mastpersonas.NomCompleto,mastempleado.CodEmpleado, mastempleado.Estado, mastempleado.CodPersona, mastpersonas.Busqueda, mastpersonas.EsCliente, mastpersonas.EsProveedor, mastpersonas.EsEmpleado, mastpersonas.EsOtros, mastpersonas.Ndocumento, mastpersonas.DocFiscal,rh_puestos.DescripCargo,rh_puestos.CodCargo FROM mastempleado, mastpersonas, rh_puestos WHERE (mastpersonas.CodPersona=mastempleado.CodPersona) AND (mastpersonas.EsEmpleado='S') AND (mastempleado.Estado='A') AND
      (mastempleado.CodCargo=rh_puestos.CodCargo) ORDER BY mastempleado.CodPersona LIMIT ".$_GET['limit'].", $MAXLIMIT"; //echo $sql;
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query); //echo $rows;
		//	MUESTRO LA TABLA
		for ($i=0; $i<$rows; $i++) { //echo $i;
			$field=mysql_fetch_array($query);
			if ($field['EsCliente']=="S") $escliente="checked"; else $escliente="";
			if ($field['EsProveedor']=="S") $esproveedor="checked"; else $esproveedor="";
			if ($field['EsEmpleado']=="S") $esempleado="checked"; else $esempleado="";
			if ($field['EsOtros']=="S") $esotros="checked"; else $esotros="";
			
			if($ventana=="insertarDestinatarioEmp"){
			 echo "
			<tr class='trListaBody' onclick='mClk(this, \"registro\"); insertarDestinatarioEmp(this.id,\"".$ventana."\",\"".$_GET['campo']."\");' id='".$field['CodPersona']."'>
				<td align='center'>".$field['CodEmpleado']."</td>
				<td align='left'>".htmlentities($field['Busqueda'])."</td>
				<td align='center'><input type='checkbox' $escliente disabled /></td>
				<td align='center'><input type='checkbox' $esproveedor disabled /></td>
				<td align='center'><input type='checkbox' $esempleado disabled /></td>
				<td align='center'><input type='checkbox' $esotros disabled /></td>
				<td align='left'>".$field['Ndocumento']."</td>
				<td align='left'>".$field['DocFiscal']."</td>
			</tr>";
			
			}else{
			echo "
			<tr class='trListaBody' onclick='mClk(this, \"registro\"); selEmpleado(\"".htmlentities($field['Busqueda'])."\",\"".$_GET['campo']."\",\"".$field['DescripCargo']."\", \"".$field['CodCargo']."\");' id='".$field['CodPersona']."'>
				<td align='center'>".$field['CodEmpleado']."</td>
				<td align='left'>".htmlentities($field['Busqueda'])."</td>
				<td align='center'><input type='checkbox' $escliente disabled /></td>
				<td align='center'><input type='checkbox' $esproveedor disabled /></td>
				<td align='center'><input type='checkbox' $esempleado disabled /></td>
				<td align='center'><input type='checkbox' $esotros disabled /></td>
				<td align='left'>".$field['Ndocumento']."</td>
				<td align='left'>".$field['DocFiscal']."</td>
			</tr>";
		}}
	}
	$rows=(int)$rows;
	echo "
	<script type='text/javascript' language='javascript'>
		totalLista($registros);
		totalLotes($registros, $rows, ".$_GET['limit'].");
	</script>";				
	?>
</table>
</form>
</body>
</html>
