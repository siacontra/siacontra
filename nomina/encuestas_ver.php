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
<style type="text/css">
<!--
UNKNOWN {
        FONT-SIZE: small
}
#header {
        FONT-SIZE: 93%; BACKGROUND: url(bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal
}
#header UL {
        PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none
}
#header LI {
        PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px
}
#header A {
        PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none
}
#header A {
        FLOAT: none
}
#header A:hover {
        COLOR: #333
}
#header #current {
        BACKGROUND-IMAGE: url(left_on.gif)
}
#header #current A {
        BACKGROUND-IMAGE: url(right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333
}
-->
</style>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Control de Encuestas | Ver Registro</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
$sql="SELECT CodOrganismo, Secuencia, PeriodoContable, Titulo, Fecha, Observaciones, Muestra, UltimoUsuario, UltimaFecha FROM rh_encuestas WHERE Secuencia='".$registro."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) $field=mysql_fetch_array($query);
?>

<table width="750" align="center">
  <tr>
   	<td>
		<div id="header">
		<ul>
		<!-- CSS Tabs -->
		<li><a onClick="document.getElementById('tab1').style.display='block'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('iResumen').src='';" href="#">Encuesta</a></li>
		<li><a onClick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('iResumen').src='';" href="#">Preguntas</a></li>
		<li><a onClick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='block'; document.getElementById('tab4').style.display='none'; document.getElementById('iResumen').src='';" href="#">Respuestas</a></li>
		<li><a onClick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='block'; document.getElementById('iResumen').src='encuestas_resumen.php?registro=<?=$registro?>';" href="#">Resumen</a></li>
		</ul>
		</div>
	</td>
  </tr>
</table>

<div name="tab1" id="tab1" style="display:block;">
<?php
echo "
<div style='width:750px' class='divFormCaption'>Datos de la Encuesta</div>
<table width='750' class='tblForm'>
	<tr>
		<td class='tagForm'>Organismo:</td>
		<td>
			<select name='organismo' id='organismo' class='selectBig'>";
				getOrganismos($field['CodOrganismo'], 1);
			echo "
			</select>
		</td>
		<td class='tagForm'>Secuencia:</td>
		<td><input name='codigo' type='text' id='codigo' size='10' maxlength='7' value='".$field['Secuencia']."' disabled /></td>
		<td class='tagForm'>Per&iacute;odo:</td>
		<td><input name='periodo' type='text' id='periodo' size='10' maxlength='7' value='".$field['PeriodoContable']."' readonly /></td>
	</tr>
	<tr>
		<td class='tagForm'>Titulo:</td>
		<td colspan='5'><input name='titulo' type='text' id='titulo' size='125' maxlength='255' value='".$field['Titulo']."' readonly /></td>
	</tr>
	<tr>
		<td class='tagForm'>Fecha:</td>
		<td><input name='fecha' type='text' id='fecha' size='15' maxlength='10' value='".$field['Fecha']."' /></td>
		<td class='tagForm'>Muestra:</td>
		<td colspan='3'><input name='muestra' type='text' id='muestra' size='10' value='".$field['Muestra']."' disabled /></td>
	</tr>
	<tr>
		<td class='tagForm'>Observaciones:</td>
		<td colspan='5'><textarea name='obs' id='obs' cols='125' rows='3' readonly>".$field['Observaciones']."</textarea></td>
	</tr>
	<tr>
		<td class='tagForm'>&Uacute;ltima Modif.:</td>
		<td>
			<input name='ult_usuario' type='text' id='ult_usuario' size='30' value='".$field['UltimoUsuario']."' readonly />
			<input name='ult_fecha' type='text' id='ult_fecha' size='25' value='".$field['UltimaFecha']."' readonly />
		</td>
	</tr>
</table>";
?>
</div>

<div name="tab2" id="tab2" style="display:none;">
<div style="width:750px" class="divFormCaption">Preguntas</div>
<table align="center" cellpadding="0" cellspacing="0">
	<tr>
    <td valign="top">
		<iframe class="frameTab" id="iPreguntas" name="iPreguntas" style="height:450px; width:748px;" src="encuestas_preguntas_ver.php?registro=<?=$registro?>"></iframe>
	</td>
  </tr>
</table>
</div>

<div name="tab3" id="tab3" style="display:none;">
<div style="width:750px" class="divFormCaption">Respuestas</div>
<table align="center" cellpadding="0" cellspacing="0">
	<tr>
    <td valign="top">
		<iframe class="frameTab" id="iRespuestas" name="iRepuestas" style="height:450px; width:748px;" src="encuestas_respuestas_ver.php?registro=<?=$registro?>"></iframe>
	</td>
  </tr>
</table>
</div>

<div name="tab4" id="tab4" style="display:none;">
<div style="width:750px" class="divFormCaption">Resumen</div>
<table align="center" cellpadding="0" cellspacing="0">
	<tr>
    <td valign="top">
		<iframe class="frameTab" id="iResumen" name="iResumen" style="height:450px; width:748px;" src=""></iframe>
	</td>
  </tr>
</table>
</div>
</form>
</body>
</html>
