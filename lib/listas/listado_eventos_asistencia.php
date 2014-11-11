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
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Listado del Control de Eventos</td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="listado_eventos_asistencia.php?" method="post">
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="campo3" id="campo3" value="<?=$campo3?>" />
<input type="hidden" name="campo4" id="campo4" value="<?=$campo4?>" />
<input type="hidden" name="ventana" id="ventana" value="<?=$ventana?>" />
<input type="hidden" name="seldetalle" id="seldetalle" value="<?=$seldetalle?>" />
<input type="hidden" name="CodPersona" id="CodPersona" value="<?=$CodPersona?>" />
<input type="hidden" name="FechaInicio" id="FechaInicio" value="<?=$FechaInicio?>" />
<input type="hidden" name="FechaFin" id="FechaFin" value="<?=$FechaFin?>" />
<input type="hidden" name="secuencia" id="secuencia" value="<?=$secuencia?>" />

<center>
<div style="overflow:scroll; width:100%; height:340px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th scope="col" width="25">#</th>
		<th scope="col" width="100">Fecha</th>
		<th scope="col" width="100">Hora</th>
		<th scope="col">Evento</th>
	</tr>
    </thead>
	<?php
	//	consulto lista
	$sql = "SELECT *
			FROM rh_controlasistencia
			WHERE
				CodPersona = '".$CodPersona."' AND
				FechaFormat >= '".formatFechaAMD($FechaInicio)."' AND
				FechaFormat <= '".formatFechaAMD($FechaFin)."' AND
				Estado = 'P'
			ORDER BY Fechaformat, HoraFormat";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[CodEvento]_$field[CodPersona]";
		if ($ventana == "bono_periodos_registrar_eventos") {
			?><tr class="trListaBody" onclick="bono_periodos_registrar_eventos_control('<?=$id?>');" id="<?=$id?>"><?
		}
		?>
			<th><?=++$i?></th>
			<td align="center"><?=formatFechaDMA($field['FechaFormat'])?></td>
			<td align="center"><?=formatHora12($field['HoraFormat'], true)?></td>
			<td align="center"><?=$field['Event_Puerta']?></td>
        </tr>
		<?
	}
	?>
</table>
</div>
</center>
</form>
</body>
</html>