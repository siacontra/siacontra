<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../../index.php");
//	------------------------------------
include("../fphp.php");
//	------------------------------------
if ($filtrar == "default") {
	$fOrderBy = "FechaNacimiento";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
$filtro = "";
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<link type="text/css" rel="stylesheet" href="../../css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../../css/estilo.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" src="../../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/fscript.js" charset="utf-8"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Carga Familiar</td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="listado_carga_familiar.php?" method="post">
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="campo3" id="campo3" value="<?=$campo3?>" />
<input type="hidden" name="campo4" id="campo4" value="<?=$campo4?>" />
<input type="hidden" name="ventana" id="ventana" value="<?=$ventana?>" />
<input type="hidden" name="seldetalle" id="seldetalle" value="<?=$seldetalle?>" />
<input type="hidden" name="marco" id="marco" value="<?=$marco?>" />
<input type="hidden" name="CodPersona" id="CodPersona" value="<?=$CodPersona?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />

<center>
<table width="900" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
	</tr>
</table>

<div style="overflow:scroll; width:900px; height:300px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="75" align="right" onclick="order('LENGTH(Ndocumento), Ndocumento')"><a href="javascript:">Cedula</a></th>
        <th scope="col" align="left" onclick="order('NomCompleto')"><a href="javascript:">Nombre</a></th>
        <th scope="col" width="100" onclick="order('NomParentesco')"><a href="javascript:">Parentesco</a></th>
        <th scope="col" width="25" onclick="order('FechaNacimiento')"><a href="javascript:">Fecha Nacimiento</a></th>
        <th scope="col" width="75" onclick="order('Sexo')"><a href="javascript:">Sexo</a></th>
    </tr>
    </thead>
    
    <tbody>
    <?php
    //	consulto lista
    $sql = "SELECT
				cf.CodPersona,
				cf.CodSecuencia,
				cf.Ndocumento,
				CONCAT(cf.NombresCarga, ' ', cf.ApellidosCarga) AS NomCompleto,
				md.Descripcion AS NomParentesco,
				cf.FechaNacimiento,
				cf.Sexo,
				cf.Estado
            FROM
				rh_cargafamiliar cf
				LEFT JOIN mastmiscelaneosdet md ON (md.CodDetalle = cf.Parentesco AND
													md.CodMaestro = 'PARENT')
            WHERE cf.CodPersona = '".$CodPersona."'
            ORDER BY $fOrderBy";
    $query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
    while ($field = mysql_fetch_array($query)) {
        $id = "$field[CodSecuencia]";
		
		if($ventana == "selListadoIFrame") {
			?><tr class="trListaBody" onclick="selListadoIFrame('<?=$marco?>', '<?=$id?>', '<?=($field["NomCompleto"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$id?>"><?
		}
		else {
			?><tr class="trListaBody" onclick="selListado2('<?=$id?>', '<?=($field["NomCompleto"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$id?>"><? 
		}
        ?>
            <td align="right"><?=$field['Ndocumento']?></td>
            <td><?=htmlentities($field['NomCompleto'])?></td>
            <td align="center"><?=htmlentities($field['NomParentesco'])?></td>
            <td align="center"><?=formatFechaDMA($field['FechaNacimiento'])?></td>
            <td align="center"><?=printValoresGeneral("SEXO", $field['Sexo'])?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
</center>
</form>
<script type="text/javascript" language="javascript">
	totalRegistros(parseInt(<?=$rows_total?>));
</script>
</body>
</html>