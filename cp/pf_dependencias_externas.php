<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_pf.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('02', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_pf.js"></script>
<script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Dependencias Externas</td>
		<td align="right"><a class="cerrar" href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" action="pf_dependencias_externas.php" method="POST">
<table width="1000" class="tblBotones">
    <tr>
        <td><div id="rows"></div></td>
        <td align="right">Filtro: <input name='filtro' type='text' id='filtro' size='30' maxlength='30' value='<?=$filtro?>' /></td>
        <td align="right">
            <input type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'pf_dependencias_externas_nuevo.php');" />
			<input type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'pf_dependencias_externas_editar.php', 'SELF');" />
			<input type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'pf_dependencias_externas_ver.php', 'BLANK', 'height=400, width=750, left=200, top=100, resizable=no');" />
			<input type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="opcionRegistro(this.form, document.getElementById('registro').value, 'DEPENDENCIAS-EXTERNAS', 'ELIMINAR');" />
			<input type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'pf_dependencias_externas_pdf.php', 'height=800, width=800, left=200, top=200, resizable=yes');" />
        </td>
    </tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table align="center">
<tr>
  <td align="center">
  <div style="overflow:scroll; width:1000px; height:450px;">
  
<table width="1000" class="tblLista">
    <tr class="trListaHead">
        <th width="75" scope="col">Dependencia</th>
        <th scope="col">Descripci&oacute;n</th>
        <th width="350" scope="col">Representante</th>
        <th width="75" scope="col">Estado</th>
    </tr>
	<?php
    //	CONSULTO LA TABLA
	if ($filtro != "") $filtro_sql = " AND (CodDependencia LIKE '%$filtro%' OR 
											Dependencia LIKE '%$filtro%')";
	$sql = "SELECT * FROM pf_dependenciasexternas WHERE 1 $filtro_sql";
    $query = mysql_query($sql) or die ($sql.mysql_error());
    $rows = mysql_num_rows($query);
    //	MUESTRO LA TABLA
    while ($field = mysql_fetch_array($query)) {
		$estado = printValores("ESTADO", $field['Estado']);
		?>
        <tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$field['CodDependencia']?>">
            <td align="center"><?=$field['CodDependencia']?></td>
            <td><?=htmlentities($field['Dependencia'])?></td>
            <td><?=htmlentities($field['Representante'])?></td>
            <td align="center"><?=$estado?></td>
        </tr>
        <?
	}
    ?>
</table>
</div>
</td></tr></table></table>
</form>


<script type="text/javascript" language="javascript">
	totalRegistros(<?=$rows?>, "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
</script>
</body>
</html>