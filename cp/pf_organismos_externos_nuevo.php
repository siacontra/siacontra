<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	----------------------------------
include("fphp_pf.php");
connect();
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
		<td class="titulo">Maestro de Organismos Externos | Nuevo Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="document.getElementById('frmentrada').submit();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="pf_organismos_externos.php" method="POST" onsubmit="return verificarOrganismoExterno(this, 'INSERTAR');">
<?php
$sql="SELECT ValorParam FROM mastparametros WHERE ParametroClave='PATHLOGO'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$path_logo=mysql_fetch_array($query);
$path_blank=$path_logo['ValorParam'];
echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />";
echo "<input type='hidden' name='path' id='path' value='".$path_logo['ValorParam']."' />";
?>

<div style="width:700px" class="divFormCaption">Datos del Organismo</div>
<table width="700" class="tblForm">
  <tr>
    <td class="tagForm" width="150">Organismo:</td>
    <td><input name="codigo" type="text" id="codigo" size="6" maxlength="4" disabled="disabled" /></td>
  </tr>
  <tr>
    <td class="tagForm">Descripci&oacute;n:</td>
    <td><input name="descripcion" type="text" id="descripcion" size="50" maxlength="50" />*</td>
  </tr>
  <tr>
    <td class="tagForm">Desc. Completa:</td>
    <td><input name="descripcionc" type="text" id="descripcionc" size="75" maxlength="100" />*</td>
  </tr>	
  <tr>
    <td class="tagForm">Sujeto a control:</td>
    <td>
			<input name="control" type="radio" value="AC" checked /> Administraci&oacute;n Central
			<input name="control" type="radio" value="AD" /> Administraci&oacute;n Descentralizada
	  </td>
  </tr>
    <tr>
    <td class="tagForm">Org. Social:</td>
    <td>
			<input name="orgsocial" type="radio" value="S" checked /> Si
			<input name="orgsocial" type="radio" value="N" /> No
	  </td>
   </tr>
	<tr>
    <td class="tagForm">Persona:</td>
    <td>
			<input name="codpersona" type="text" id="codpersona" size="8" maxlength="6" disabled="disabled" />
			<input name="persona" type="text" id="persona" size="45" maxlength="30" disabled="disabled" />
			<input name="bt_examinar" type="button" id="bt_examinar" value="..." onclick="cargarVentana(this.form, 'lista_personas.php?limit=0', 'height=500, width=800, left=200, top=200, resizable=yes');" />
	  </td>
  </tr>
  <tr>
    <td class="tagForm">Repres. Legal:</td>
    <td><input name="rep" type="text" id="rep" size="45" maxlength="40" /></td>
  </tr>
  <tr>
    <td class="tagForm">Repres. Legal (Doc.):</td>
    <td><input name="docr" type="text" id="docr" size="45" maxlength="30" /></td>
  </tr>
  <tr>
    <td class="tagForm">Cargo Rep.:</td>
    <td><input name="cargo" type="text" id="cargo" size="75" maxlength="75" /></td>
  </tr>
  <tr>
    <td class="tagForm">Doc. Fiscal:</td>
    <td><input name="docf" type="text" id="docf" size="30" maxlength="20" /></td>
  </tr>
  <tr>
    <td class="tagForm">Estado:</td>
    <td>
			<input name="status" type="radio" value="A" checked /> Activo
			<input name="status" type="radio" value="I" /> Inactivo
	  </td>
  </tr>
</table>
<div style="width:700px" class="divFormCaption">Informaci&oacute;n Adicional </div>
<table width="700" class="tblForm">
  <tr>
    <td class="tagForm" width="150">Nro. Reg. Mercantil:</td>
    <td width="425"><input name="nreg" type="text" id="nreg" size="15" maxlength="10" /></td>
    <td><div style="position:absolute;"><img src="<?=$path_blank."blank.png"?>" name="img_logo" width="96" height="96" id="img_logo" /></div></td>
  </tr>
  <tr>
    <td class="tagForm">Tomo Reg. Mercantil:</td>
    <td colspan="2"><input name="treg" type="text" id="treg" size="8" maxlength="5" /></td>
  </tr>
  <tr>
    <td class="tagForm">Fecha Fundaci&oacute;n:</td>
    <td colspan="2"><input name="fecha" type="text" id="fecha" size="15" maxlength="10" /><i>(dd-mm-yyyy)</i></td>
  </tr>
  <tr>
    <td class="tagForm">Direcci&oacute;n:</td>
    <td colspan="2"><input name="dir" type="text" id="dir" size="90" maxlength="255" /></td>
  </tr>
	<tr>
    <td class="tagForm">Pais:</td>
    <td colspan="2">
		<select name="pais" id="pais" class="selectMed" onchange="getOptions_4(this.id, 'estado', 'municipio', 'ciudad')">
			<option value="">&nbsp;</option>
			<?=loadSelect("mastpaises", "CodPais", "Pais", "", 0)?>
		</select>*	  
	</td>
  </tr>
	<tr>
    <td class="tagForm">Estado:</td>
    <td colspan="2">
		<select name="estado" id="estado" class="selectMed" disabled>
			<option value="">&nbsp;</option>
		</select>*	  
	</td>
  </tr>
	<tr>
    <td class="tagForm">Municipio:</td>
    <td colspan="2">
		<select name="municipio" id="municipio" class="selectMed" disabled>
			<option value="">&nbsp;</option>
		</select>*	  
	</td>
  </tr>
	<tr>
    <td class="tagForm">Ciudad:</td>
    <td colspan="2">
		<select name="ciudad" id="ciudad" class="selectMed" disabled>
			<option value="">&nbsp;</option>
		</select>*	  
	</td>
  </tr>
  <tr>
    <td class="tagForm">Tel&eacute;fonos:</td>
    <td colspan="2">
		<input name="tel1" type="text" id="tel1" size="20" maxlength="15" />
		<input name="tel2" type="text" id="tel2" size="20" maxlength="15" />
		<input name="tel3" type="text" id="tel3" size="20" maxlength="15" />	  
	</td>
  </tr>
  <tr>
    <td class="tagForm">Fax:</td>
    <td colspan="2">
		<input name="fax1" type="text" id="fax1" size="20" maxlength="15" />
		<input name="fax2" type="text" id="fax2" size="20" maxlength="15" />	  
	</td>
  </tr>
  <tr>
    <td class="tagForm">Pagina Web:</td>
    <td colspan="2"><input name="www" type="text" id="www" size="75" maxlength="100" /></td>
  </tr>
  <tr>
    <td class="tagForm">Logo:</td>
    <td colspan="2">
		<input name="logo" type="text" id="logo" size="30" maxlength="30" onchange="setLogo();" />
	</td>
  </tr>
  <tr>
    <td class="tagForm">&Uacute;ltima Modif.:</td>
    <td colspan="2">
		<input name="ult_usuario" type="text" id="ult_usuario" size="30" disabled="disabled" />
		<input name="ult_fecha" type="text" id="ult_fecha" size="25" disabled="disabled" />	  
	</td>
  </tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onclick="this.form.submit();" />
</center><br />
</form>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>

</body>
</html>
