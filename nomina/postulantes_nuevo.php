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
		<td class="titulo">Registro de Postulantes | Nuevo</td>
        <td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'postulantes.php?filtro=<?=$filtro?>&limit=<?=$limit?>');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
?>
<form name="frmentrada" id="frmentrada" action="postulantes.php" method="POST" onsubmit="return verificarPostulante(this, 'GUARDAR');">
<table width="905" align="center">
  <tr>
   	<td>
		<div id="header">
		<ul>
		<!-- CSS Tabs -->
		<li><a onclick="document.getElementById('tab1').style.display='block'; document.getElementById('tab2').style.display='none';" href="#">Datos Personales</a></li>
		<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block';" href="#">Otros Datos</a></li>
		<li><a onclick="javascript:alert('¡Debe guardar el registro primero!');" href="#">Instrucci&oacute;n</a></li>
		<li><a onclick="javascript:alert('¡Debe guardar el registro primero!');" href="#">Cursos</a></li>
		<li><a onclick="javascript:alert('¡Debe guardar el registro primero!');" href="#">Experiencias Laborales</a></li>
		<li><a onclick="javascript:alert('¡Debe guardar el registro primero!');" href="#">Referencias Personales</a></li>
		<li><a onclick="javascript:alert('¡Debe guardar el registro primero!');" href="#">Documentos</a></li>
		<li><a onclick="javascript:alert('¡Debe guardar el registro primero!');" href="#">Cargos Aplicables</a></li>
		</ul>
		</div>
	</td>
  </tr>
</table>

<div id="tab1" style="display:block;">
<div style="width:905px" class="divFormCaption">Datos Generales</div>
<table width="905" class="tblForm">
  <tr>
	<td class="tagForm">Postulante:</td>
	<td><input name="postulante" type="text" id="postulante" size="10" readonly /></td>
	<td class="tagForm">Estado:</td>
	<td><input name="status" type="text" id="status" size="20" value="Postulante" readonly /></td>
  </tr>
  <tr>
	<td class="tagForm">Apellido Paterno:</td>
	<td><input name="apellido1" type="text" id="apellido1" size="25" maxlength="20" /></td>
	<td class="tagForm">Materno:</td>
	<td><input name="apellido2" type="text" id="apellido2" size="25" maxlength="20" />*</td>
  </tr>
  <tr>
	<td class="tagForm">Nombres:</td>
	<td><input name="nombres" type="text" id="nombres" size="40" maxlength="30" />*</td>
	<td class="tagForm">Sexo:</td>
	<td>
			<select name="sexo">
				<?php getSexo('', 0); ?>
			</select>
		</td>
  </tr>
</table>
<div style="width:905px" class="divFormCaption">Resumen Ejecutivo</div>
<table width="905" class="tblForm">
	<tr><td align="center"><textarea name="resumen" id="resumen" cols="200" rows="2"></textarea></td></tr>
</table>
<div style="width:905px" class="divFormCaption">Lugar y Fecha de Nacimiento</div>
<table width="905" class="tblForm">
	<tr>
		<td class="tagForm">Pais:</td>
		<td>
			<select name="pais" id="pais" class="selectMed" onchange="getOptions_4(this.id, 'estado', 'municipio', 'ciudad');">
				<option value=""></option>
				<?php getPaises('', 0); ?>
			</select>*
		</td>
		<td class="tagForm">Estado:</td>
		<td>
			<select name="estado" id="estado" class="selectMed" disabled>
				<option value=""></option>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Municipio:</td>
		<td>
			<select name="municipio" id="municipio" class="selectMed" disabled>
				<option value=""></option>
			</select>*
		</td>
		<td class="tagForm">Ciudad:</td>
		<td>
			<select name="ciudad" id="ciudad" class="selectMed" disabled>
				<option value=""></option>
			</select>*
	</td>
  </tr>
  <tr>
	<td class="tagForm">Fecha:</td>
	<td><input name="fnac" type="text" id="fnac" size="15" maxlength="10" onKeyUp="getEdad(this.form, this.value);" />*<em>(dd-mm-yyyy)</em></td>
	<td class="tagForm">Edad:</td>
	<td>
			<input name="anac" type="text" id="anac" size="5" readonly />a
			<input name="mnac" type="text" id="mnac" size="5" readonly />m
			<input name="dnac" type="text" id="dnac" size="5" readonly />d
		</td>
  </tr>
</table>
<div style="width:905px" class="divFormCaption">Domicilio Actual</div>
<table width="905" class="tblForm">
  <tr>
	<td class="tagForm">Direcci&oacute;n:</td>
	<td colspan="3"><input name="dir" type="text" id="dir" size="100" maxlength="255" />*</td>
  </tr>
  <tr>
	<td class="tagForm">Referencia:</td>
	<td colspan="3"><input name="referencia" type="text" id="referencia" size="100" maxlength="100" /></td>
  </tr>
	<tr>
	<td class="tagForm">Pais:</td>
	<td>
			<select name="pais2" id="pais2" class="selectMed" onchange="getOptions_4(this.id, 'estado2', 'municipio2', 'ciudad2')">
				<option value=""></option>
				<?php getPaises('', 0); ?>
			</select>*
		</td>
	<td class="tagForm">Estado:</td>
	<td>
			<select name="estado2" id="estado2" class="selectMed" disabled>
				<option value=""></option>
			</select>*
		</td>
  </tr>
	<tr>
	<td class="tagForm">Municipio:</td>
	<td>
			<select name="municipio2" id="municipio2" class="selectMed" disabled>
				<option value=""></option>
			</select>*
		</td>
	<td class="tagForm">Ciudad:</td>
	<td>
			<select name="ciudad2" id="ciudad2" class="selectMed" disabled>
				<option value=""></option>
			</select>*
		</td>
  </tr>
  <tr>
	<td class="tagForm">Tel&eacute;fono:</td>
	<td><input name="tel" type="text" id="tel" size="25" maxlength="20" />*</td>
	<td class="tagForm">e-mail:</td>
	<td colspan="3"><input name="email" type="text" id="email" size="45" maxlength="30" /></td>
  </tr>
</table>
<div style="width:905px" class="divFormCaption">Documentos de Identificaci&oacute;n</div>
<table width="905" class="tblForm">
	<tr>
	<td class="tagForm">Tipo Doc.:</td>
	<td>
			<select name="tdoc" id="tdoc" class="selectMed">
				<option value=""></option>
				<?php getMiscelaneos('', "TIPODOC", 0); ?>
			</select>*
		</td>
	<td class="tagForm">Nro. Documento:</td>
	<td colspan="3"><input name="ndoc" type="text" id="ndoc" size="25" maxlength="20" />*</td>
  </tr>
  <tr>
	  <td class="tagForm">Fecha de Registro:</td>
	  <td colspan="3"><input name="freg" type="text" id="freg" size="15" maxlength="10" readonly /></td>
	</tr>
  <tr>
	  <td class="tagForm">&Uacute;ltima Modif.:</td>
	  <td colspan="3">
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" readonly />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" readonly />
		</td>
	</tr>
</table>
<center>
<input type="submit" value="Guardar Registro" />
<input type="button" value="Cancelar" onclick="cargarPagina(this.form, 'postulantes.php?filtro=<?=$filtro?>&limit=<?=$limit?>');" /><br />
</center>
<div style="width:905px" class="divMsj">Campos Obligatorios *</div>
</div>

<div id="tab2" style="display:none;">
<div style="width:905px" class="divFormCaption">Otros Datos Personales</div>
<table width="905" class="tblForm">
	<tr>
		<td class="tagForm">Grupo Sangu&iacute;neo:</td>
		<td>
			<select name="gsan" id="gsan" class="selectMed">
				<option value=""></option>
				<?php getMiscelaneos('', "SANGRE", 0); ?>
			</select>
		</td>
		<td class="tagForm">Situaci&oacute;n Domicilio:</td>
		<td>
			<select name="sitdom" id="sitdom" class="selectMed">
				<option value=""></option>
				<?php getMiscelaneos('', "SITDOM", 0); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Estado Civil:</td>
		<td>
			<select name="edocivil" id="edocivil" class="selectMed">
				<option value=""></option>
				<?php getMiscelaneos('', "EDOCIVIL", 0); ?>
			</select>*
		</td>
		<td class="tagForm">Fecha Edo. Civil:</td>
		<td><input name="fedocivil" type="text" id="fedocivil" size="15" maxlength="10" />*<i>(dd-mm-yyyy)</i>
		</td>
	</tr>
</table>
<div style="width:905px" class="divFormCaption">Informaci&oacute;n Adicional</div>
<table width="905" class="tblForm">
	<tr><td align="center"><textarea name="obs" id="obs" cols="200" rows="2"></textarea></td></tr>
</table>
<div style="width:905px" class="divFormCaption">Actividades Extralaborales</div>
<table width="905" class="tblForm">
	<tr>
		<td class="tagForm">Ben&eacute;ficas:</td>
		<td>
			<input type="checkbox" value="S" name="flagactividades" id="flagbeneficas" onclick="enabledBeneficas(this.form);" />
			<textarea name="beneficas" id="beneficas" cols="50" rows="2" disabled="disabled"></textarea>
		</td>
		<td class="tagForm">Laborales:</td>
		<td>
			<input type="checkbox" value="S" name="flagactividades" id="flaglaborales" onclick="enabledLaborales(this.form);" />
			<textarea name="laborales" id="laborales" cols="50" rows="2" disabled="disabled"></textarea>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Culturales:</td>
		<td>
			<input type="checkbox" value="S" name="flagactividades" id="flagculturales" onclick="enabledCulturales(this.form);" />
			<textarea name="culturales" id="culturales" cols="50" rows="2" disabled="disabled"></textarea>
		</td>
		<td class="tagForm">Deportivas:</td>
		<td>
			<input type="checkbox" value="S" name="flagactividades" id="flagdeportivas" onclick="enabledDeportivas(this.form);" />
			<textarea name="deportivas" id="deportivas" cols="50" rows="2" disabled="disabled"></textarea>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Religiosas:</td>
		<td>
			<input type="checkbox" value="S" name="flagactividades" id="flagreligiosas" onclick="enabledReligiosas(this.form);" />
			<textarea name="religiosas" id="religiosas" cols="50" rows="2" disabled="disabled"></textarea>
		</td>
		<td class="tagForm">Sociales:</td>
		<td>
			<input type="checkbox" value="S" name="flagactividades" id="flagsociales" onclick="enabledSociales(this.form);" />
			<textarea name="sociales" id="sociales" cols="50" rows="2" disabled="disabled"></textarea>
		</td>
	</tr>
</table>
<br />
<div style="width:905px" class="divMsj">Campos Obligatorios *</div>
</div>

<?php
//	FILTRO..............
echo "
<input type='hidden' name='chksexo' value='".$_POST['chksexo']."' />
<input type='hidden' name='fsexo' value='".$_POST['fsexo']."' />
<input type='hidden' name='chkginstruccion' value='".$_POST['chkginstruccion']."' />
<input type='hidden' name='fginstruccion' value='".$_POST['fginstruccion']."' />
<input type='hidden' name='chkcargo' value='".$_POST['chkcargo']."' />
<input type='hidden' name='fcargo' value='".$_POST['fcargo']."' />
<input type='hidden' name='filtro' value='".$_POST['filtro']."' />
<input type='hidden' name='limit' value='".$_POST['limit']."' />";
?>

</form>
</body>
</html>