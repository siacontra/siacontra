<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../../index.php");
//	------------------------------------
include("../fphp.php");
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
<!-- ui-dialog -->
<div id="cajaModal"></div>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Horario del Empleado</td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<center>
<table width="100%" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
	</tr>
</table>

<div style="overflow:scroll; width:100%; height:260px;">
<table width="100%" class="tblLista">
	<thead>
    <tr>
        <th scope="col" width="15" rowspan="2">&nbsp;</th>
        <th scope="col" width="30" rowspan="2">Lab.</th>
        <th scope="col" rowspan="2">Dia</th>
        <th scope="col" colspan="2">1er Turno</th>
        <th scope="col" colspan="2">2do Turno</th>
    </tr>
    <tr>
        <th scope="col" width="55">Entrada</th>
        <th scope="col" width="55">Salida</th>
        <th scope="col" width="55">Entrada</th>
        <th scope="col" width="55">Salida</th>
    </tr>
    </thead>
    
    <tbody>
    <?php
	$sql = "SELECT hld.*
			FROM
				rh_horariolaboraldet hld
				INNER JOIN mastempleado e ON (e.CodHorario = hld.CodHorario)
			WHERE e.CodPersona = '".$CodPersona."' ORDER BY Dia";
	$query_detalles = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_detalles = mysql_fetch_array($query_detalles)) {	$nro_detalles++;
		if ($field_detalles['FlagLaborable'] == "S") $disabled_detalles = ""; else {
			$disabled_detalles = "disabled";
			$field_detalles['Entrada1'] = "";
			$field_detalles['Salida1'] = "";
			$field_detalles['Entrada2'] = "";
			$field_detalles['Salida2'] = "";
		}
		?>
		<tr class="trListaBody">
			<th>
				<?=$nro_detalles?>
			</th>
			<td align="center">
				<?=printFlag($field_detalles['FlagLaborable'])?>
			</td>
			<td>
                <?=printValoresGeneral("DIA-SEMANA", $field_detalles['Dia'])?>
			</td>
			<td>
				<?=formatHora12($field_detalles['Entrada1'])?>
			</td>
			<td>
            	<?=formatHora12($field_detalles['Salida1'])?>
			</td>
			<td>
            	<?=formatHora12($field_detalles['Entrada2'])?>
			</td>
			<td>
            	<?=formatHora12($field_detalles['Salida2'])?>
			</td>
		</tr>
		<?
	}
    ?>
    </tbody>
</table>
</div>
</center>
</body>
</html>