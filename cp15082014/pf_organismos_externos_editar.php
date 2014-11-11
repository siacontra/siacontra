<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	----------------------------------
include("fphp_pf.php");
connect();
//	----------------------------------
$sql = "SELECT
			oe.*,
			c.CodMunicipio,
			m.CodEstado,
			e.CodPais
		FROM
			pf_organismosexternos oe
			INNER JOIN mastciudades c ON (oe.CodCiudad = c.CodCiudad)
			INNER JOIN mastmunicipios m ON (c.CodMunicipio = m.CodMunicipio)
			INNER JOIN mastestados e ON (m.CodEstado = e.CodEstado)
		WHERE oe.CodOrganismo = '".$registro."'";
$query = mysql_query($sql) or die($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
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
		<td class="titulo">Maestro de Organismos Externos | Modificar Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="document.getElementById('frmentrada').submit();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="pf_organismos_externos.php" method="POST" onsubmit="return verificarOrganismoExterno(this, 'ACTUALIZAR');">
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
    <td><input name="codigo" type="text" id="codigo" size="6" maxlength="4" value="<?=$field['CodOrganismo']?>" disabled="disabled" /></td>
  </tr>
  <tr>
    <td class="tagForm">Descripci&oacute;n:</td>
    <td><input name="descripcion" type="text" id="descripcion" size="50" maxlength="50" value="<?=htmlentities($field['Organismo'])?>" />*</td>
  </tr>
  <tr>
    <td class="tagForm">Desc. Completa:</td>
    <td><input name="descripcionc" type="text" id="descripcionc" size="75" maxlength="100" value="<?=htmlentities($field['DescripComp'])?>" />*</td>
  </tr>	
  <tr>
    <td class="tagForm">Sujeto a control:</td>
    <td>
    	<? if ($field['Control'] == "AC") $flagcentralizada = "checked"; else $flagdescentralizada = "checked"; ?>
			<input name="control" type="radio" value="AC" <?=$flagcentralizada?> /> Administraci&oacute;n Central
			<input name="control" type="radio" value="AD" <?=$flagdescentralizada?> /> Administraci&oacute;n Descentralizada
	  </td>
  </tr>
  <tr>
    <td class="tagForm">Org. Social:</td>
    <td>
    	<? if ($field['FlagSocial'] == "S") $orgsocial= "checked"; else $orgsocial = ""; ?>
         <input type="checkbox" id="orgsocial" name="orgsocial" value="S"   <?=$orgsocial?>     />
	  </td>
  </tr>
  </tr>
	<tr>
    <td class="tagForm">Persona:</td>
    <td>
			<input name="codpersona" type="text" id="codpersona" size="8" maxlength="6" value="<?=$field['CodPersona']?>" disabled="disabled" />
			<input name="persona" type="text" id="persona" size="45" maxlength="30" value="<?=htmlentities($field['NomCompleto'])?>" disabled="disabled" />
			<input name="bt_examinar" type="button" id="bt_examinar" value="..." onclick="cargarVentana(this.form, 'lista_personas.php?limit=0', 'height=500, width=800, left=200, top=200, resizable=yes');" />
	  </td>
  </tr>
  <tr>
    <td class="tagForm">Repres. Legal:</td>
    <td><input name="rep" type="text" id="rep" size="45" maxlength="40" value="<?=htmlentities($field['RepresentLegal'])?>" /></td>
  </tr>
  <tr>
    <td class="tagForm">Repres. Legal (Doc.):</td>
    <td><input name="docr" type="text" id="docr" size="45" maxlength="30" value="<?=$field['DocRepreLeg']?>" /></td>
  </tr>
  <tr>
    <td class="tagForm">Cargo Rep.:</td>
    <td><input name="cargo" type="text" id="cargo" size="75" maxlength="75" value="<?=$field['Cargo']?>" /></td>
  </tr>
  <tr>
    <td class="tagForm">Doc. Fiscal:</td>
    <td><input name="docf" type="text" id="docf" size="30" maxlength="20" value="<?=$field['DocFiscal']?>" /></td>
  </tr>
  <tr>
    <td class="tagForm">Estado:</td>
    <td>
    	<? if ($field['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked"; ?>
			<input name="status" type="radio" value="A" <?=$flagactivo?> /> Activo
			<input name="status" type="radio" value="I" <?=$flaginactivo?> /> Inactivo
	  </td>
  </tr>
</table>
<div style="width:700px" class="divFormCaption">Informaci&oacute;n Adicional </div>
<table width="700" class="tblForm">
  <tr>
    <td class="tagForm" width="150">Nro. Reg. Mercantil:</td>
    <td width="425"><input name="nreg" type="text" id="nreg" size="15" maxlength="10" value="<?=$field['NumReg']?>" /></td>
    <td><div style='position:absolute;'><img src='<?=$path_blank?><?=$field['Logo']?>' name='img_logo' width='96' height='96' id='img_logo' /></div></td>
  </tr>
  <tr>
    <td class="tagForm">Tomo Reg. Mercantil:</td>
    <td colspan="2"><input name="treg" type="text" id="treg" size="8" maxlength="5" value="<?=$field['TomoReg']?>" /></td>
  </tr>
  <tr>
    <td class="tagForm">Fecha Fundaci&oacute;n:</td>
    <td colspan="2"><input name="fecha" type="text" id="fecha" size="15" maxlength="10" value="<?=formatFechaDMA($field['FechaFundac'])?>" /><i>(dd-mm-yyyy)</i></td>
  </tr>
  <tr>
    <td class="tagForm">Direcci&oacute;n:</td>
    <td colspan="2"><input name="dir" type="text" id="dir" size="90" maxlength="255" value="<?=htmlentities($field['Direccion'])?>" /></td>
  </tr>
	<tr>
    <td class="tagForm">Pais:</td>
    <td colspan="2">
		<select name="pais" id="pais" class="selectMed" onchange="getOptions_4(this.id, 'estado', 'municipio', 'ciudad');">
			<?=loadSelect("mastpaises", "CodPais", "Pais", "", 0)?>
		</select>*	  
	</td>
  </tr>
	<tr>
    <td class="tagForm">Estado:</td>
    <td colspan="2">
		<select name="estado" id="estado" class="selectMed" onchange="getOptions_3(this.id, 'municipio', 'ciudad');">
			<?=loadSelectDependiente("mastestados", "CodEstado", "Estado", "CodPais", $field['CodEstado'], $field['CodPais'], 0)?>
		</select>*	  
	</td>
  </tr>
	<tr>
    <td class="tagForm">Municipio:</td>
    <td colspan="2">
		<select name="municipio" id="municipio" class="selectMed" onchange="getOptions_2(this.id, 'ciudad');">
			<?=loadSelectDependiente("mastmunicipios", "CodMunicipio", "Municipio", "CodEstado", $field['CodMunicipio'], $field['CodEstado'], 0)?>
		</select>*	  
	</td>
  </tr>
	<tr>
    <td class="tagForm">Ciudad:</td>
    <td colspan="2">
		<select name="ciudad" id="ciudad" class="selectMed">
			<?=loadSelectDependiente("mastciudades", "CodCiudad", "Ciudad", "CodMunicipio", $field['CodCiudad'], $field['CodMunicipio'], 0)?>
		</select>*	  
	</td>
  </tr>
  <tr>
    <td class="tagForm">Tel&eacute;fonos:</td>
    <td colspan="2">
		<input name="tel1" type="text" id="tel1" size="20" maxlength="15" value="<?=$field['Telefono1']?>" />
		<input name="tel2" type="text" id="tel2" size="20" maxlength="15" value="<?=$field['Telefono2']?>" />
		<input name="tel3" type="text" id="tel3" size="20" maxlength="15" value="<?=$field['Telefono3']?>" />	  
	</td>
  </tr>
  <tr>
    <td class="tagForm">Fax:</td>
    <td colspan="2">
		<input name="fax1" type="text" id="fax1" size="20" maxlength="15" value="<?=$field['Fax1']?>" />
		<input name="fax2" type="text" id="fax2" size="20" maxlength="15" value="<?=$field['Fax2']?>" />	  
	</td>
  </tr>
  <tr>
    <td class="tagForm">Pagina Web:</td>
    <td colspan="2"><input name="www" type="text" id="www" size="75" maxlength="100" value="<?=htmlentities($field['PaginaWeb'])?>" /></td>
  </tr>
  <tr>
    <td class="tagForm">Logo:</td>
    <td colspan="2">
		<input name="logo" type="text" id="logo" size="30" maxlength="30" value="<?=htmlentities($field['Logo'])?>" onchange="setLogo();" />
	</td>
  </tr>
  <tr>
    <td class="tagForm">&Uacute;ltima Modif.:</td>
    <td colspan="2">
		<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$field['UltimoUsuario']?>" disabled="disabled" />
		<input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field['UltimaFecha']?>" disabled="disabled" />	  
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
