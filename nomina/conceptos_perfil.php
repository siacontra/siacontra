<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_nomina.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
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
if ($filtro!="") $filtrado="WHERE (CodConceptoPerfil LIKE '%".$filtro."%' OR Descripcion LIKE '%".$filtro."%') "; else $filtrado="";
//	----------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Perfil de Conceptos</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="conceptos_perfil.php" method="POST">
<table width="900" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">Filtro: <input type="text" name="filtro" id="filtro" value="<?=$filtro?>" size="30" /></td>
		<td align="right">
			<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'conceptos_perfil_nuevo.php');" />
			<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'conceptos_perfil_editar.php', 'SELF');" />
			<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'conceptos_perfil_ver.php', 'BLANK', 'height=300, width=800, left=50, top=50, resizable=no');" />
			<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistro(this.form, 'conceptos_perfil.php', '1', 'CONCEPTO-PERFIL');" />
			<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'conceptos_perfil_pdf.php', 'height=900, width=900, left=200, top=200, resizable=yes');" /> | 
            <input name="btDetalle" type="button" id="btDetalle" value="Detalle de Conceptos Vs. Cuenta" onclick="cargarOpcion(this.form, 'conceptos_perfil_detalle.php', 'BLANK', 'height=700, width=1000, left=50, top=50, resizable=no');" />
		</td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:900px; height:300px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th width="100" scope="col">Concepto</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="75" scope="col">Estado</th>
	</tr>
	<?php 
	//	CONSULTO LA TABLA
	$sql = "SELECT
				cp.CodPerfilConcepto,
				cp.Descripcion, 
				cp.Estado 
			FROM 
				pr_conceptoperfil cp
			$filtrado";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		if ($field['Estado']=="A") $status="Activo"; else $status="Inactivo";
		//---------------------------------------------------
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$field['CodPerfilConcepto']?>">
			<td align='center'><?=$field['CodPerfilConcepto']?></td>
			<td><?=($field['Descripcion'])?></td>
			<td align='center'><?=$status?></td>
		</tr>
        <?
	}
?>
</table>
</div></td></tr></table>
</form>


<script type="text/javascript" language="javascript">
	totalRegistros(<?=intval($rows)?>, "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
</script>
</body>
</html>