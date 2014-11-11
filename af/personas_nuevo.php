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
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Personas | Nuevo Registro</td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
?>
<form name="frmentrada" action="personas.php" method="POST" onsubmit="return verificarPersona(this, 'GUARDAR');">
<input type="hidden" name="proveedor" id="proveedor" value="N" />
<div style="width:800px" class="divFormCaption">Datos Generales</div>
<table width="800" class="tblForm">
	<tr>
		<td class="tagForm">Persona:</td>
		<td><input name="persona" type="text" id="persona" size="10" readonly /></td>
		<td class="tagForm">Nombre B&uacute;squeda:</td>
		<td><input name="busqueda" type="text" id="busqueda" size="70" maxlength="100" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Clase de Persona:</td>
		<td>
			<select name="cpersona" onchange="setClase(this.form, this.value);">
				<?php getCPersona('', 0); ?>
			</select>
		</td>
		<td class="tagForm">Nombre Completo:</td>
		<td><input name="nom_completo" type="text" id="nom_completo" size="70" maxlength="100" onkeyup="setBusquedaPersona2(this.form);" disabled="disabled" />*</td>
	</tr>
    <tr>
		<td class="tagForm">Tipo de Persona:</td>
		<td colspan="3">
			<input name="escliente" type="checkbox" id="escliente" value="S" disabled />Cliente &nbsp;&nbsp;
			<input name="esproveedor" type="checkbox" id="esproveedor" value="S" disabled />Proveedor &nbsp;&nbsp;
			<input name="esempleado" type="checkbox" id="esempleado" value="S" disabled />Empleado &nbsp;&nbsp;
			<input name="esotro" type="checkbox" id="esotro" value="S" />Otro &nbsp;&nbsp;
		</td>
	</tr>
</table>
<div id="clase" style="width:800px" class="divFormCaption">Datos Persona Natural</div>
<table width="800" class="tblForm">
	<tr>
		<td class="tagForm">Apellido Paterno:</td>
		<td><input name="apellido1" type="text" id="apellido1" size="25" maxlength="25" onkeyup="setBusquedaPersona(this.form);" />*</td>
		<td class="tagForm">Materno:</td>
		<td><input name="apellido2" type="text" id="apellido2" size="25" maxlength="25" onkeyup="setBusquedaPersona(this.form);" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Nombres:</td>
		<td><input name="nombres" type="text" id="nombres" size="40" maxlength="50" onkeyup="setBusquedaPersona(this.form);" />*</td>
		<td class="tagForm">Sexo:</td>
		<td>
			<select name="sexo">
				<?php getSexo('', 0); ?>
			</select>
		</td>
	</tr>
		<td class="tagForm"><div id="fclase">Fecha de Nac.:</div></td>
		<td><input name="fnac" type="text" id="fnac" size="15" maxlength="10" />*<em>(dd-mm-yyyy)</em></td>
		<td class="tagForm">Estado Civil:</td>
		<td>
			<select name="edocivil" id="edocivil">
				<option value=""></option>
				<?php getMiscelaneos('', "EDOCIVIL", 0); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Direcci&oacute;n:</td>
		<td colspan="3"><input name="dir" type="text" id="dir" size="100" maxlength="70" />*</td>
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
		<td class="tagForm">e-mail:</td>
		<td colspan="3"><input name="email" type="text" id="email" size="45" maxlength="30" /></td>
	</tr>
	<tr>
		<td class="tagForm">Nombre Emerg:</td>
		<td><input name="nomcon1" type="text" id="nomcon1" size="30" maxlength="30" /></td>
		<td class="tagForm">Direcci&oacute;n Emerg.:</td>
		<td><input name="dircon1" type="text" id="dircon1" size="50" maxlength="50" /></td>
	</tr>	
</table>
<table width="800" class="tblForm">
	<tr>
		<td class="tagForm">Tel&eacute;fono:</td>
		<td><input name="tel1" type="text" id="tel1" size="25" maxlength="20" /></td>
		<td class="tagForm">Celular:</td>
		<td><input name="tel2" type="text" id="tel2" size="25" maxlength="20" /></td>
		<td class="tagForm">Fax:</td>
		<td><input name="tel3" type="text" id="tel3" size="25" maxlength="20" /></td>
	</tr>
</table>

<table align="center">
<div style="width:800px" class="divFormCaption">Documentos de Identificaci&oacute;n</div>
<table width="800" class="tblForm">
	<tr>
		<td width="125" class="tagForm">Principal:</td>
		<td>
			<select name="tdoc" id="tdoc" class="selectMed">
				<option value=""></option>
				<?php getMiscelaneos('', "DOCUMENTOS", 0); ?>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Nro. Documento:</td>
		<td><input name="ndoc" type="text" id="ndoc" size="20" maxlength="20" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Doc. Fiscal:</td>
		<td><input name="rif" type="text" id="rif" size="20" maxlength="20" />*</td>
	</tr>
</table>

<div style="width:800px" class="divFormCaption">Informaci&oacute;n Bancaria</div>
<table width="800" class="tblForm">
	<tr>
		<td width="125" class="tagForm">Banco:</td>
		<td>
			<select name="banco" id="banco" class="selectMed">
				<option value=""></option>
				<?php getBancos('', 0); ?>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Nro. Cuenta:</td>
		<td><input name="ncta" type="text" id="ncta" size="50" maxlength="30" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Tipo Cuenta:</td>
		<td>
			<select name="tcta" id="tcta" class="selectMed">
				<option value=""></option>
				<?php getMiscelaneos('', "TIPOCTA", 0); ?>
			</select>*
		</td>
	</tr>
</table>

<div style="width:800px" class="divFormCaption">Datos de Auditor&iacute;a</div>
<table width="800" class="tblForm">
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

<?php
echo "
<input type='submit' value='Guardar Registro' />
<input name='bt_cancelar' type='button' id='bt_cancelar' value='Cancelar' onclick='cargarPagina(this.form, \"personas.php?filtro=".$_POST['filtro']."&limit=".$_POST['limit']."\");' />";
?>
</center><br />
<div style="width:800px" class="divMsj">Campos Obligatorios *</div>

<?php
//	FILTRO..............
echo "
<input type='hidden' name='chkordenar' value='".$_POST['chkordenar']."' />
<input type='hidden' name='fordenar' value='".$_POST['fordenar']."' />
<input type='hidden' name='chkedoreg' value='".$_POST['chkedoreg']."' />
<input type='hidden' name='fedoreg' value='".$_POST['fedoreg']."' />
<input type='hidden' name='chkactualizar' value='".$_POST['chkactualizar']."' />
<input type='hidden' name='factualizar' value='".$_POST['factualizar']."' />
<input type='hidden' name='chkcpersona' value='".$_POST['chkcpersona']."' />
<input type='hidden' name='fcpersona' value='".$_POST['fcpersona']."' />
<input type='hidden' name='chktpersona' value='".$_POST['chktpersona']."' />
<input type='hidden' name='ftpersona' value='".$_POST['ftpersona']."' />
<input type='hidden' name='chkbpersona' value='".$_POST['chkbpersona']."' />
<input type='hidden' name='fbpersona' value='".$_POST['fbpersona']."' />
<input type='hidden' name='filtro' value='".$_POST['filtro']."' />
<input type='hidden' name='ordenar' value='".$_POST['ordenar']."' />
<input type='hidden' name='limit' value='".$_POST['limit']."' />";
?>
</form>
</body>
</html>