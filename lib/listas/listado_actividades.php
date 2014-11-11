<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../../index.php");
//	------------------------------------
extract($_POST);
extract($_GET);
//	------------------------------------
include("../fphp.php");
//	------------------------------------
if ($filtrar == "default") {
	$fordenar = "a.CodActividad";
	$fedoreg = "A";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fordenar != "") { $cordenar = "checked"; $orderby = "ORDER BY CodProceso, CodFase, $fordenar"; } else $dordenar = "disabled";
if ($fedoreg != "") { $cedoreg = "checked"; $filtro.=" AND (a.Estado = '".$fedoreg."')"; } else $dedoreg = "disabled";
if ($fbuscar != "") {
	$cbuscar = "checked";
	$filtro.=" AND (a.CodActividad LIKE '%".$fbuscar."%' OR 
					a.Descripcion LIKE '%".$fbuscar."%')";
} else $dbuscar = "disabled";
if ($ffase != "") { $cfase = "checked"; $filtro.=" AND (a.CodFase = '".$ffase."')"; } else $dfase = "disabled";
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
<div id="bloqueo" class="divBloqueo"></div>
<div id="cargando" class="divCargando">
<table>
	<tr>
    	<td valign="middle" style="height:50px;">
			<img src="../imagenes/iconos/cargando.gif" /><br />Procesando...
        </td>
    </tr>
</table>
</div>
<!-- ui-dialog -->
<div id="cajaModal"></div>
<!-- pretty -->
<span class="gallery clearfix"></span>

<form name="frmentrada" id="frmentrada" action="listado_actividades.php?" method="post">
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="CodProceso" id="CodProceso" value="<?=$CodProceso?>" />
<div class="divBorder" style="width:800px;">
<table width="800" class="tblFiltro">
	<tr>
		<td align="right">Fase:</td>
		<td>
            <input type="checkbox" <?=$cfase?> onclick="chkFiltro(this.checked, 'ffase');" />
            <span>
            <select name="ffase" id="ffase" style="width:250px;" <?=$dfase?>>
                <option value="">&nbsp;</option>
                <?=loadSelectDependiente("pf_fases", "CodFase", "Descripcion", "CodProceso", $ffase, $CodProceso, 0)?>
            </select>
            </span>
		</td>
		<td align="right">Buscar:</td>
        <td>
            <input type="checkbox" <?=$cbuscar?> onclick="chkFiltro(this.checked, 'fbuscar');" />
            <input type="text" name="fbuscar" id="fbuscar" style="width:200px;" value="<?=$fbuscar?>" <?=$dbuscar?> />
		</td>
	</tr>
	<tr>
		<td align="right">Ordenar Por:</td>
		<td>
            <input type="checkbox" <?=$cordenar?> onclick="this.checked=!this.checked;" />
            <select name="fordenar" id="fordenar" style="width:100px;" <?=$dordenar?>>
                <?=loadSelectGeneral("ORDENAR-ACTIVIDADES", $fordenar, 0)?>
            </select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="800" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
	</tr>
</table>

<div style="overflow:scroll; width:800px; height:350px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th scope="col" width="50">Actividad</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col" width="25">Au. Ar.</th>
		<th scope="col" width="25">No Afe.</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				a.*,
				f.Descripcion AS NomFase,
				p.CodProceso,
				p.Descripcion AS NomProceso
			FROM
				pf_actividades a
				INNER JOIN pf_fases f ON (a.CodFase = f.CodFase)
				INNER JOIN pf_procesos p ON (f.CodProceso = p.CodProceso)
			WHERE
				a.Estado = 'A' AND
				p.CodProceso = '".$CodProceso."'
				$filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				a.*,
				f.Descripcion AS NomFase,
				p.CodProceso,
				p.Descripcion AS NomProceso
			FROM
				pf_actividades a
				INNER JOIN pf_fases f ON (a.CodFase = f.CodFase)
				INNER JOIN pf_procesos p ON (f.CodProceso = p.CodProceso)
			WHERE
				a.Estado = 'A' AND
				p.CodProceso = '".$CodProceso."'
				$filtro
			$orderby
			LIMIT ".intval($limit).", $maxlimit";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		if ($grupo1 != $field['CodProceso']) {
			$grupo1 = $field['CodProceso'];
			$grupo2 = "";
			?>
            <tr class="trListaBody2">
                <td colspan="2"><?=($field['NomProceso'])?></td>
            </tr>
            <?
		}
		if ($grupo2 != $field['CodFase']) {
			$grupo2 = $field['CodFase'];
			?>
            <tr class="trListaBody3">
                <td colspan="2"><?=($field['NomFase'])?></td>
            </tr>
            <?
		}
		
		if ($ventana == "setListaActividadesSel") {
			?>
            <tr class="trListaBody" onclick="setListaActividadesSel('<?=$field['CodActividad']?>');">
            <?
		}
		else {
			?>
            <tr class="trListaBody" onclick="selListado2('<?=$field['CodActividad']?>', '<?=($field["Descripcion"])?>', '<?=$cod?>', '<?=$nom?>');">
            <?
		}
		?>
			<td align="center"><?=$field['CodActividad']?></td>
			<td><?=($field['Descripcion'])?></td>
			<td align="center"><?=printFlag($field['FlagAutoArchivo'])?></td>
			<td align="center"><?=printFlag($field['FlagNoAfectoPlan'])?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
<table width="800">
	<tr>
    	<td>
        	Mostrar: 
            <select name="maxlimit" style="width:50px;" onchange="this.form.submit();">
                <?=loadSelectGeneral("MAXLIMIT", $maxlimit, 0)?>
            </select>
        </td>
        <td align="right">
        	<?=paginacion(intval($rows_total), intval($rows_lista), intval($maxlimit), intval($limit));?>
        </td>
    </tr>
</table>
</center>
</form>
<script type="text/javascript" language="javascript">
	totalRegistros(parseInt(<?=$rows_total?>), "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
</script>
</body>
</html>