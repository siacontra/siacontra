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
		<td class="titulo">Maestro de Cargos | Nuevo Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'cargos.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="cargos.php" method="POST" onsubmit="return verificarCargo(this, 'GUARDAR');">

<?php
include("fphp.php");
connect();
echo "
<input type='hidden' name='codigo' id='codigo' value='' />
<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />";
?>

<table width="750" align="center">
  <tr>
		<td>
			<div id="header">
			<ul>
			<!-- CSS Tabs -->
			<li><a onclick="document.getElementById('tab1').style.display='block';" href="#">Cargo</a></li>
			<li><a onclick="javascript:alert('&iexcl;DEBE GUARDAR PRIMERO EL CARGO!');" href="#" disabled>Relaciones</a></li>
			<li><a onclick="javascript:alert('&iexcl;DEBE GUARDAR PRIMERO EL CARGO!');" href="#" disabled>Competencias</a></li>
			<li><a onclick="javascript:alert('&iexcl;DEBE GUARDAR PRIMERO EL CARGO!');" href="#" disabled>Funciones</a></li>
			<li><a onclick="javascript:alert('&iexcl;DEBE GUARDAR PRIMERO EL CARGO!');" href="#" disabled>Formaci&oacute;n</a></li>
			<li><a onclick="javascript:alert('&iexcl;DEBE GUARDAR PRIMERO EL CARGO!');" href="#" disabled>Experiencia Previa</a></li>
			<li><a onclick="javascript:alert('&iexcl;DEBE GUARDAR PRIMERO EL CARGO!');" href="#" disabled>Riesgos de Trabajo</a></li>
			<li><a onclick="javascript:alert('&iexcl;DEBE GUARDAR PRIMERO EL CARGO!');" href="#" disabled>Evaluaci&oacute;n - Reclutamiento</a></li>
			<li><a onclick="javascript:alert('&iexcl;DEBE GUARDAR PRIMERO EL CARGO!');" href="#" disabled>Puestos Subordinados</a></li>
			<li><a onclick="javascript:alert('&iexcl;DEBE GUARDAR PRIMERO EL CARGO!');" href="#" disabled>Otros Estudios</a></li>
			<li><a onclick="javascript:alert('&iexcl;DEBE GUARDAR PRIMERO EL CARGO!');" href="#" disabled>Objetivos y/o Metas</a></li>
			<li><a onclick="javascript:alert('&iexcl;DEBE GUARDAR PRIMERO EL CARGO!');" href="#" disabled>Ambiente de Trabajo</a></li>
			<li><a onclick="javascript:alert('&iexcl;DEBE GUARDAR PRIMERO EL CARGO!');" href="#" disabled>Habilidades/Destrezas</a></li>
			</ul>
			</div>
		</td>
	</tr>
</table>

<div name="tab1" id="tab1" style="display:block;">
<div style="width:750px" class="divFormCaption">Datos del Cargo</div>
<table width="750" class="tblForm">
    <tr>
        <td class="tagForm">Tipo:</td>
        <td>
            <select name="tipocargo" id="tipocargo" class="selectMed" onchange="getOptions_2(this.id, 'nivelcargo'); setCargo(this.form);">
                <option value=""></option>
                <?php getTCargo('', 0); ?>
            </select>*
        </td>
    </tr>
    <tr>
        <td class="tagForm">Nivel:</td>
        <td>
            <select name="nivelcargo" id="nivelcargo" class="selectMed" disabled>
                <option value=""></option>
            </select>*
        </td>
    </tr>
    <tr>
        <td class="tagForm">Grupo Ocupacional:</td>
        <td>
            <select name="grupo" id="grupo" class="selectMed" onchange="getOptions_2(this.id, 'serie')">
                <option value=""></option>
                <?php getGrupos('', 0); ?>
            </select>*
        </td>
    </tr>
    <tr>
        <td class="tagForm">Serie Ocupacional:</td>
        <td>
            <select name="serie" id="serie" class="selectMed" disabled>
            	<option value=""></option>
            </select>*
        </td>
    </tr>
    <tr>
        <td class="tagForm">Clasificaci&oacute;n:</td>
        <td><input type="text" name="codcargo" id="codcargo" size="8" maxlength="6" />*</td>
    </tr>
    <tr>
        <td class="tagForm">Cargo:</td>
        <td><input name="descripcion" type="text" id="descripcion" size="60" maxlength="100" />*</td>
    </tr>
    <tr>
        <td class="tagForm">Descripci&oacute;n Gen&eacute;rica:</td>
        <td><textarea name="descripcion_generica" id="descripcion_generica" cols="100" rows="3"></textarea>*</td>
    </tr>
    <tr>
        <td class="tagForm">Categor&iacute;a del Cargo:</td>
        <td>
            <select name="ttra" id="ttra" class="selectMed" onchange="getGradosCargo(this.form, this.value);">
                <option value=""></option>
                <?php getMiscelaneos("", "CATCARGO", 0); ?>
            </select>*
        </td>
    </tr>
    <tr>
    	<td class="tagForm">Grado del Cargo:</td>
        <td>
            <select name="gcargo" id="gcargo" disabled="disabled">
            	<option value="">&nbsp;&nbsp;&nbsp;</option>
            </select>*
        </td>
    </tr>
    <tr>
        <td class="tagForm">Sueldo B&aacute;sico:</td>
        <td><input name="sueldo" type="text" id="sueldo" size="20" maxlength="15" readonly />*</td>
    </tr>
    <tr>
    	<td class="tagForm">Plantilla de Competencias:</td>
        <td>
            <select name="plantilla_competencias" id="plantilla_competencias">
                <option value=""></option>
                <?php getPlantillas('', 0); ?>
            </select>
        </td>
    </tr>
    <tr>
        <td class="tagForm">Estado:</td>
        <td>
            <input name="status" type="radio" value="A" checked /> Activo
            <input name="status" type="radio" value="I" /> Inactivo
        </td>
    </tr>
    <tr>
        <td class="tagForm">&Uacute;ltima Modif.:</td>
        <td>
            <input name="ult_usuario" type="text" id="ult_usuario" size="30" readonly />
            <input name="ult_fecha" type="text" id="ult_fecha" size="25" readonly />
        </td>
    </tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onclick="cargarPagina(this.form, 'cargos.php');" />
</center><br />
</div>
</form>

<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
