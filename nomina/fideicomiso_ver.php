<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
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
include("fphp.php");
connect();
//	-------------------
$sql = "SELECT 
			mp.CodPersona,
			mp.NomCompleto
		FROM
			mastpersonas mp
		WHERE
			mp.CodPersona = '".$registro."'";
$query_persona = mysql_query($sql) or die($sql.mysql_error());
if (mysql_num_rows($query_persona) != 0) $field_persona = mysql_fetch_array($query_persona);
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Dep&oacute;sitos de Fideicomiso</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<table width="700" class="tblForm">
	<tr>
		<td>
			<input name="codpersona" type="hidden" id="codpersona" value="<?=$field_persona['CodPersona']?>" />
			<span style="font-size:14px; font-weight:bold;"><?=$field_persona['NomCompleto']?></span>
		</td>
	</tr>
</table>


<table width="700" class="tblLista">
	<tr class="trListaHead">
		<th width="100" scope="col">Periodo</th>
		<th width="175" scope="col">Anterior Anti.</th>
		<th width="175" scope="col">Anterior Fide.</th>
		<th width="175" scope="col">Transacci&oacute;n</th>
		<th scope="col">Dias</th>
	</tr>
	<?
	$sql = "SELECT * FROM pr_acumuladofideicomisodetalle WHERE CodPersona = '".$registro."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	while ($field = mysql_fetch_array($query)) {
		$sum_transaccion += $field['Transaccion'];
		$sum_dias += $field['Dias'];
		?>
		<tr class="trListaBody">
			<td align="center"><?=$field['Periodo']?></td>
			<td align="right"><?=number_format($field['AnteriorProv'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field['AnteriorFide'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field['Transaccion'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field['Dias'], 2, ',', '.')?></td>
		</tr>
		<?
	}
	?>
	<tr class="trListaBody">
		<td colspan="3">&nbsp;</td>
		<td align="right"><b><?=number_format($sum_transaccion, 2, ',', '.')?></b></td>
		<td align="right"><b><?=number_format($sum_dias, 2, ',', '.')?></b></td>
	</tr>
</table>
</body>
</html>
