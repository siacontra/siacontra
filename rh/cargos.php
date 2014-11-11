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
		<td class="titulo">Maestro de Cargos</td>
		<td align="right"><a class="cerrar" href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" action="cargos.php" method="POST">
<table width="800" class="tblBotones">
  <tr>
		<td><div id="rows"></div></td>
    <td align="right">
			<?php
			if ($_GET['filtro']!="") $_POST['filtro']=$_GET['filtro'];
			echo "Filtro: <input name='filtro' type='text' id='filtro' size='30' maxlength='30' value='".$_POST['filtro']."' />";
			?>
		</td>
    <td align="right">
			<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'cargos_nuevo.php');" />
			<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'cargos_editar.php', 'SELF');" />
			<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'cargos_ver.php', 'BLANK', 'height=750, width=900, left=50, top=50, resizable=no');" />
			<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistro(this.form, 'cargos.php?accion=ELIMINAR', '1', 'CARGOS');" />
			<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'cargos_pdf.php', 'height=800, width=800, left=200, top=200, resizable=yes');" />
		</td>
  </tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="800" class="tblLista">
  <tr class="trListaHead">
    <th width="75" scope="col">Clasificaci&oacute;n</th>
    <th scope="col">Descripci&oacute;n</th>
    <th width="75" scope="col">Estado</th>
  </tr>
	<?php 
	//	ELIMINO EL REGISTRO
	if ($_GET['accion']=="ELIMINAR") {
		$sql="DELETE FROM rh_puestos WHERE CodCargo='".$_POST['registro']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
	//	CONSULTO LA TABLA
	$filtro=trim($_POST['filtro']);
	if ($filtro != "") $where = "(rh_puestos.CodCargo LIKE '%$filtro%' OR rh_puestos.DescripCargo LIKE '%$filtro%' OR rh_puestos.Estado LIKE '%$filtro%' OR rh_tipocargo.TipCargo LIKE '%$filtro%' OR rh_nivelclasecargo.NivelClase LIKE '%$filtro%' OR rh_serieocupacional.SerieOcup LIKE '%$filtro%') AND ";
	
	$sql = "SELECT 
				rh_puestos.CodCargo, 
				rh_puestos.DescripCargo, 
				rh_puestos.Estado, 
				rh_tipocargo.TipCargo, 
				rh_nivelclasecargo.NivelClase, 
				rh_serieocupacional.SerieOcup, 
				rh_puestos.CodGrupOcup, 
				rh_grupoocupacional.GrupoOcup, 
				rh_puestos.CodSerieOcup, 
				rh_puestos.CodTipoCargo, 
				rh_puestos.CodNivelClase, 
				rh_puestos.CodDesc 
			FROM 
				rh_puestos, 
				rh_tipocargo, 
				rh_nivelclasecargo, 
				rh_serieocupacional, 
				rh_grupoocupacional 
			WHERE 
				$where
				(rh_puestos.CodTipoCargo=rh_tipocargo.CodTipoCargo AND 
				 rh_puestos.CodNivelClase=rh_nivelclasecargo.CodNivelClase AND 
				 rh_puestos.CodTipoCargo=rh_nivelclasecargo.CodTipoCargo AND 
				 rh_puestos.CodSerieOcup=rh_serieocupacional.CodSerieOcup) AND 
				(rh_puestos.CodGrupOcup=rh_grupoocupacional.CodGrupOcup) 
			ORDER BY rh_puestos.CodDesc";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		
		if ($field['Estado'] == "A") $status = "Activo"; Else $status = "Inactivo";
		
		if ($grupo != $field['CodGrupOcup']) {
			$grupo = $field['CodGrupOcup'];
			echo "
			<tr class='trListaBody2'>
				<td align='center'>".$field['CodGrupOcup']."</td>
				<td colspan='2'>".$field['GrupoOcup']."</td>
			</tr>";
		}
		if ($serie != $field['CodSerieOcup']) {
			$serie = $field['CodSerieOcup'];
			echo "
			<tr class='trListaBody3'>
				<td align='center'>".$field['CodSerieOcup']."</td>
				<td colspan='2'>".$field['SerieOcup']."</td>
			</tr>";
		}
		echo "
		<tr class='trListaBody' onclick='mClk(this, \"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field[0]."'>
			<td align='center'>".$field['CodDesc']."</td>
			<td>".$field['DescripCargo']."</td>
			<td align='center'>".$status."</td>
		</tr>";
	}
	echo "
	<script type='text/javascript' language='javascript'>
		totalRegistros($rows, \"$_ADMIN\", \"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
	</script>";
?>

</table>
</form>
</body>
</html>