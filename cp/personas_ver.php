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
		<td class="titulo">Maestro de Personas | Ver Registro</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
$sql="SELECT mastpersonas.CodPersona, mastpersonas.Busqueda, mastpersonas.TipoPersona, mastpersonas.EsCliente, mastpersonas.EsProveedor, mastpersonas.EsEmpleado, mastpersonas.EsOtros, mastpersonas.Apellido1, mastpersonas.Apellido2, mastpersonas.Nombres, mastpersonas.Sexo, mastpersonas.Fnacimiento, mastpersonas.EstadoCivil, mastpersonas.Direccion, mastpaises.CodPais, mastestados.CodEstado, mastmunicipios.CodMunicipio, mastciudades.CodCiudad, mastpersonas.Email, mastpersonas.NomEmerg1, mastpersonas.DirecEmerg1, mastpersonas.Telefono1, mastpersonas.Telefono2, mastpersonas.Fax, mastpersonas.TipoDocumento, mastpersonas.Ndocumento, mastpersonas.DocFiscal, mastpersonas.Estado, mastpersonas.UltimoUsuario, mastpersonas.UltimaFecha, mastpersonas.NomCompleto FROM mastpersonas, mastpaises, mastestados, mastmunicipios, mastciudades WHERE (mastpersonas.CodPersona='".$_GET['registro']."') AND (mastpersonas.CiudadDomicilio=mastciudades.CodCiudad AND mastciudades.CodMunicipio=mastmunicipios.CodMunicipio AND mastmunicipios.CodEstado=mastestados.CodEstado AND mastestados.CodPais=mastpaises.CodPais)";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) {
	$field=mysql_fetch_array($query);
	if ($field[2]=="N") { $ttl1="Datos Persona Natural"; $ttl2="Fecha de Nac.:"; } 
	else { $dbclase="disabled"; $ttl1="Datos Persona Juridica"; $ttl2="Fecha de Const.:"; }
	list($a, $m, $d)=SPLIT('[/.-]', $field[11]); $fnac=$d."-".$m."-".$a;
	if ($field[3]=="S") $escliente="checked";
	if ($field[4]=="S") $esproveedor="checked";
	if ($field[5]=="S") $esempleado="checked";
	if ($field[6]=="S") $esotro="checked";
	if ($field[27]=="A") $activo="checked"; else $inactivo="checked";
	//
	$sql1="SELECT CodBanco, Ncuenta, TipoCuenta FROM bancopersona WHERE (CodPersona='".$_GET['registro']."' AND FlagPrincipal='S')";
	$query1=mysql_query($sql1) or die ($sql1.mysql_error());
	$rows1=mysql_num_rows($query1);
	if ($rows1!=0) $field1=mysql_fetch_array($query1);
}
?>
<div style="width:800px" class="divFormCaption">Datos Generales</div>
<table width="800" class="tblForm">
	<tr>
		<td class="tagForm">Persona:</td>
		<td><input name="persona" type="text" id="persona" size="10" readonly value="<?=$field[0] ?>" /></td>
		<td class="tagForm">Nombre B&uacute;squeda:</td>
		<td><input name="busqueda" type="text" id="busqueda" size="70" maxlength="100" value="<?=$field[1] ?>" /></td>
	</tr>
	<tr>
		<td class="tagForm">Clase de Persona:</td>
		<td>
			<select name="cpersona" onchange="setClase(this.form, this.value);">
				<?php getCPersona($field[2], 1); ?>
			</select>
		</td>
		<td class="tagForm">Nombre Completo:</td>
		<td><input name="nom_completo" type="text" id="nom_completo" size="70" maxlength="100" value="<?=$field['NomCompleto']?>" readonly="readonly" />*</td>
	</tr>
    <tr>
		<td class="tagForm">Tipo de Persona:</td>
		<td colspan="3">
			<input name="escliente" type="checkbox" id="escliente" value="S" <?=$escliente ?> disabled />Cliente &nbsp;&nbsp;
			<input name="esproveedor" type="checkbox" id="esproveedor" value="S" <?=$esproveedor ?> disabled />Proveedor &nbsp;&nbsp;
			<input name="esempleado" type="checkbox" id="esempleado" value="S" <?=$esempleado ?> disabled />Empleado &nbsp;&nbsp;
			<input name="esotro" type="checkbox" id="esotro" value="S" <?=$esotro ?> disabled />Otro &nbsp;&nbsp;
		</td>
	</tr>
</table>
<div id="clase" style="width:800px" class="divFormCaption"> <?=$ttl1 ?></div>
<table width="800" class="tblForm">
	<tr>
		<td class="tagForm">Apellido Paterno:</td>
		<td><input name="apellido1" type="text" id="apellido1" size="25" maxlength="25" value="<?=$field[7] ?>" <?=$dbclase ?> readonly /></td>
		<td class="tagForm">Materno:</td>
		<td><input name="apellido2" type="text" id="apellido2" size="25" maxlength="25" value="<?=$field[8] ?>" <?=$dbclase ?> readonly /></td>
	</tr>
	<tr>
		<td class="tagForm">Nombres:</td>
		<td><input name="nombres" type="text" id="nombres" size="40" maxlength="50" value="<?=$field[9] ?>" readonly /></td>
		<td class="tagForm">Sexo:</td>
		<td>
			<select <?=$dbclase ?> name="sexo">
				<?php getSexo($field[10], 1); ?>
			</select>
		</td>
	</tr>
		<td class="tagForm"><div id="fclase"><?=$ttl2 ?></div></td>
		<td><input name="fnac" type="text" id="fnac" size="15" maxlength="10" value="<?=$fnac ?>" readonly /></td>
		<td class="tagForm">Estado Civil:</td>
		<td>
			<select name="edocivil"  <?=$dbclase ?> id="edocivil">
				<?php getMiscelaneos($field[12], "EDOCIVIL", 1); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Direcci&oacute;n:</td>
		<td colspan="3"><input name="dir" type="text" id="dir" size="100" maxlength="70" value="<?=$field[13] ?>" readonly /></td>
	</tr>
	<tr>
		<td class="tagForm">Pais:</td>
		<td>
			<select name="pais2" id="pais2" class="selectMed">
				<?php getPaises($field[14], 1); ?>
			</select>
		</td>
		<td class="tagForm">Estado:</td>
		<td>
			<select name="estado2" id="estado2" class="selectMed">
				<?php getEstados($field[15], $field[14], 1); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Municipio:</td>
		<td>
			<select name="municipio2" id="municipio2" class="selectMed">
				<?php getMunicipios($field[16], $field[15], 1); ?>
			</select>
		</td>
		<td class="tagForm">Ciudad:</td>
		<td>
			<select name="ciudad2" id="ciudad2" class="selectMed">
				<?php getCiudades($field[17], $field[16], 1); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">e-mail:</td>
		<td colspan="3"><input name="email" type="text" id="email" size="45" maxlength="30" value="<?=$field[18] ?>" readonly /></td>
	</tr>
	<tr>
		<td class="tagForm">Nombre Emerg:</td>
		<td><input name="nomcon1" type="text" id="nomcon1" size="30" maxlength="30" value="<?=$field[19] ?>" <?=$dbclase ?> readonly /></td>
		<td class="tagForm">Direcci&oacute;n Emerg.:</td>
		<td><input name="dircon1" type="text" id="dircon1" size="50" maxlength="50" value="<?=$field[20] ?>" <?=$dbclase ?> readonly /></td>
	</tr>	
</table>
<table width="800" class="tblForm">
	<tr>
		<td class="tagForm">Tel&eacute;fono:</td>
		<td><input name="tel1" type="text" id="tel1" size="25" maxlength="20" value="<?=$field[21] ?>" readonly /></td>
		<td class="tagForm">Celular:</td>
		<td><input name="tel2" type="text" id="tel2" size="25" maxlength="20" value="<?=$field[22] ?>" readonly /></td>
		<td class="tagForm">Fax:</td>
		<td><input name="tel3" type="text" id="tel3" size="25" maxlength="20" value="<?=$field[23] ?>" readonly /></td>
	</tr>
</table>

<table align="center">
<div style="width:800px" class="divFormCaption">Documentos de Identificaci&oacute;n</div>
<table width="800" class="tblForm">
	<tr>
		<td width="125" class="tagForm">Principal:</td>
		<td>
			<select name="tdoc" id="tdoc" class="selectMed">
				<?php getMiscelaneos($field[24], "DOCUMENTOS", 1); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Nro. Documento:</td>
		<td><input name="ndoc" type="text" id="ndoc" size="20" maxlength="20" value="<?=$field[25] ?>" readonly /></td>
	</tr>
	<tr>
		<td class="tagForm">Doc. Fiscal:</td>
		<td><input name="rif" type="text" id="rif" size="20" maxlength="20" value="<?=$field[26] ?>" readonly /></td>
	</tr>
</table>

<div style="width:800px" class="divFormCaption">Informaci&oacute;n Bancaria</div>
<table width="800" class="tblForm">
	<tr>
		<td width="125" class="tagForm">Banco:</td>
		<td>
			<select name="banco" id="banco" class="selectMed">
				<?php getBancos($field1[0], 1); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Nro. Cuenta:</td>
		<td><input name="ncta" type="text" id="ncta" size="50" maxlength="30" value="<?=$field1[1] ?>" readonly /></td>
	</tr>
	<tr>
		<td class="tagForm">Tipo Cuenta:</td>
		<td>
			<select name="tcta" id="tcta" class="selectMed">
				<?php getMiscelaneos($field1[2], "TIPOCTA", 1); ?>
			</select>
		</td>
	</tr>
</table>

<div style="width:800px" class="divFormCaption">Datos de Auditor&iacute;a</div>
<table width="800" class="tblForm">
	<tr>
    <td class="tagForm">Estado:</td>
    <td>
			<input name="status" type="radio" value="A" <?=$activo ?> /> Activo
			<input name="status" type="radio" value="I" <?=$inactivo ?> /> Inactivo
		</td>
  </tr>
  <tr>
	  <td class="tagForm">&Uacute;ltima Modif.:</td>
	  <td>
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$field[28] ?>" readonly />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field[29] ?>" readonly />
		</td>
	</tr>
</table>
</body>
</html>