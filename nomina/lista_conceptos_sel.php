<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
include("fphp.php");
connect();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_nomina.js"></script>
</head>

<body>
<?php
$ahora=date("Y-m-d H:i:s");
$filtro=trim($filtro); 
if ($filtro!="") $filtrado="AND (pctn.CodConcepto LIKE '%".$filtro."%' OR pc.Descripcion LIKE '%".$filtro."%') "; else $filtrado="";
//	----------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Conceptos</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="lista_conceptos.php" method="POST">
<table width="100%" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">Filtro: <input type="text" name="filtro" id="filtro" value="<?=$filtro?>" size="30" /></td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th width="10%" scope="col">Concepto</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="10%" scope="col">Tipo</th>
		<th width="10%" scope="col">Estado</th>
	</tr>
	<?php 
	//	CONSULTO LA TABLA
	$sql="SELECT pctn.*, pc.Descripcion, pc.Tipo, pc.Estado FROM pr_conceptotiponomina pctn INNER JOIN pr_concepto pc ON (pctn.CodConcepto=pc.CodConcepto) WHERE pctn.CodTipoNom='".$codtiponom."' $filtrado ORDER BY pc.Tipo DESC, pctn.CodConcepto ASC";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		if ($field['Tipo']=="I") $tipo="Ingresos"; 
		elseif ($field['Tipo']=="D") $tipo="Descuentos";
		elseif ($field['Tipo']=="A") $tipo="Aportes";
		elseif ($field['Tipo']=="P") $tipo="Provisiones";
		if ($field['Estado']=="A") $status="Activo"; else $status="Inactivo";
		//---------------------------------------------------
		echo "
		<tr class='trListaBody' onclick='mClk(this, \"registro\"); selConcepto_2(\"".($field['Descripcion'])."\");' id='".$field['CodConcepto']."'>
			<td align='center'>".$field['CodConcepto']."</td>
			<td>".($field['Descripcion'])."</td>
			<td align='center'>".$tipo."</td>
			<td align='center'>".$status."</td>
		</tr>";
	}
	?>
</table>
</form>
</body>
</html>