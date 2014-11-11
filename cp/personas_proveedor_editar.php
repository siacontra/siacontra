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
		<td class="titulo">Maestro de Personas | Actualizaci&oacute;n</td>
	</tr>
</table><hr width="100%" color="#333333" />


<form name="frmentrada" action="personas.php" method="POST" onsubmit="return verificarPersona(this, 'ACTUALIZAR');">

<?php
include("fphp.php");
connect();
	$sql="SELECT mastpersonas.CodPersona, mastpersonas.Busqueda, mastpersonas.TipoPersona, mastpersonas.EsCliente, mastpersonas.EsProveedor, mastpersonas.EsEmpleado, mastpersonas.EsOtros, mastpersonas.Apellido1, mastpersonas.Apellido2, mastpersonas.Nombres, mastpersonas.Sexo, mastpersonas.Fnacimiento, mastpersonas.EstadoCivil, mastpersonas.Direccion, mastpaises.CodPais, mastestados.CodEstado, mastmunicipios.CodMunicipio, mastciudades.CodCiudad, mastpersonas.Email, mastpersonas.NomEmerg1, mastpersonas.DirecEmerg1, mastpersonas.Telefono1, mastpersonas.Telefono2, mastpersonas.Fax, mastpersonas.TipoDocumento, mastpersonas.Ndocumento, mastpersonas.DocFiscal, mastpersonas.Estado, mastpersonas.UltimoUsuario, mastpersonas.UltimaFecha, mastpersonas.NomCompleto FROM mastpersonas, mastpaises, mastestados, mastmunicipios, mastciudades WHERE (mastpersonas.CodPersona='".$_POST['registro']."') AND (mastpersonas.CiudadDomicilio=mastciudades.CodCiudad AND mastciudades.CodMunicipio=mastmunicipios.CodMunicipio AND mastmunicipios.CodEstado=mastestados.CodEstado AND mastestados.CodPais=mastpaises.CodPais)";
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
	$sql1="SELECT CodBanco, Ncuenta, TipoCuenta FROM bancopersona WHERE (CodPersona='".$_POST['registro']."' AND FlagPrincipal='S')";
	$query1=mysql_query($sql1) or die ($sql1.mysql_error());
	$rows1=mysql_num_rows($query1);
	if ($rows1!=0) $field1=mysql_fetch_array($query1);
}
?>

<table width="800" align="center">
  <tr>
   	<td>
			<div id="header">
			<ul>
			<!-- CSS Tabs -->
			<li><a onClick="document.getElementById('tab1').style.display='block'; document.getElementById('tab2').style.display='none';" href="#">Inf. General</a></li>
			<li><a onClick="document.getElementById('tab2').style.display='block'; document.getElementById('tab1').style.display='none';" href="#">Proveedores</a></li>
			</ul>
			</div>
		</td>
	</tr>
</table>


<div id="tab1" style="display:block;">
<input type="hidden" name="proveedor" id="proveedor" value="S" />
<div style="width:800px" class="divFormCaption">Datos Generales</div>
<table width="800" class="tblForm">
	<tr>
		<td class="tagForm">Persona:</td>
		<td><input name="persona" type="text" id="persona" size="10" readonly value="<?=$field[0] ?>" /></td>
		<td class="tagForm">Nombre B&uacute;squeda:</td>
		<td><input name="busqueda" type="text" id="busqueda" size="70" maxlength="100" value="<?=$field[1] ?>" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Clase de Persona:</td>
		<td>
			<select name="cpersona" onchange="setClase(this.form, this.value);">
				<?php getCPersona($field[2], 0); ?>
			</select>
		</td>
		<td class="tagForm">Nombre Completo:</td>
		<td><input name="nom_completo" type="text" id="nom_completo" size="70" maxlength="100" value="<?=$field['NomCompleto']?>" onkeyup="setBusquedaPersona2(this.form);" <?=$dbclase ?> />*</td>
	</tr>
    <tr>
		<td class="tagForm">Tipo de Persona:</td>
		<td colspan="3">
			<input name="escliente" type="checkbox" id="escliente" value="S" <?=$escliente ?> disabled />Cliente &nbsp;&nbsp;
			<input name="esproveedor" type="checkbox" id="esproveedor" value="S" onclick="forzarCheck(this.id)" checked="checked" />Proveedor &nbsp;&nbsp;
			<input name="esempleado" type="checkbox" id="esempleado" value="S" <?=$esempleado ?> disabled />Empleado &nbsp;&nbsp;
			<input name="esotro" type="checkbox" id="esotro" value="S" <?=$esotro ?> />Otro &nbsp;&nbsp;
		</td>
	</tr>
</table>
<div id="clase" style="width:800px" class="divFormCaption"> <?=$ttl1 ?></div>
<table width="800" class="tblForm">
	<tr>
		<td class="tagForm">Apellido Paterno:</td>
		<td><input name="apellido1" type="text" id="apellido1" size="25" maxlength="25" value="<?=$field[7] ?>" <?=$dbclase ?> disabled />*</td>
		<td class="tagForm">Materno:</td>
		<td><input name="apellido2" type="text" id="apellido2" size="25" maxlength="25" value="<?=$field[8] ?>" <?=$dbclase ?> disabled />*</td>
	</tr>
	<tr>
		<td class="tagForm">Nombres:</td>
		<td><input name="nombres" type="text" id="nombres" size="40" maxlength="50" value="<?=$field[9] ?>" disabled <?=$dbclase ?> />*</td>
		<td class="tagForm">Sexo:</td>
		<td>
			<select <?=$dbclase ?> name="sexo">
				<?php getSexo($field[10], 0); ?>
			</select>
		</td>
	</tr>
		<td class="tagForm"><div id="fclase"><?=$ttl2 ?></div></td>
		<td><input name="fnac" type="text" id="fnac" size="15" maxlength="10" value="<?=$fnac ?>" disabled />*<em>(dd-mm-yyyy)</em></td>
		<td class="tagForm">Estado Civil:</td>
		<td>
			<select name="edocivil"  <?=$dbclase ?> id="edocivil">
				<option value=""></option>
				<?php getMiscelaneos($field[12], "EDOCIVIL", 0); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Direcci&oacute;n:</td>
		<td colspan="3"><input name="dir" type="text" id="dir" size="100" maxlength="70" value="<?=$field[13] ?>" disabled />*</td>
	</tr>
	<tr>
		<td class="tagForm">Pais:</td>
		<td>
			<select name="pais2" id="pais2" class="selectMed" disabled>
				<?php getPaises($field[14], 1); ?>
			</select>*
		</td>
		<td class="tagForm">Estado:</td>
		<td>
			<select name="estado2" id="estado2" class="selectMed" disabled>
				<?php getEstados($field[15], $field[14], 1); ?>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Municipio:</td>
		<td>
			<select name="municipio2" id="municipio2" class="selectMed" disabled>
				<?php getMunicipios($field[16], $field[15], 1); ?>
			</select>*
		</td>
		<td class="tagForm">Ciudad:</td>
		<td>
			<select name="ciudad2" id="ciudad2" class="selectMed" disabled>
				<?php getCiudades($field[17], $field[16], 1); ?>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">e-mail:</td>
		<td colspan="3"><input name="email" type="text" id="email" size="45" maxlength="30" value="<?=$field[18] ?>" disabled /></td>
	</tr>
	<tr>
		<td class="tagForm">Nombre Emerg:</td>
		<td><input name="nomcon1" type="text" id="nomcon1" size="30" maxlength="30" value="<?=$field[19] ?>" <?=$dbclase ?> disabled /></td>
		<td class="tagForm">Direcci&oacute;n Emerg.:</td>
		<td><input name="dircon1" type="text" id="dircon1" size="50" maxlength="50" value="<?=$field[20] ?>" <?=$dbclase ?> disabled /></td>
	</tr>	
</table>
<table width="800" class="tblForm">
	<tr>
		<td class="tagForm">Tel&eacute;fono:</td>
		<td><input name="tel1" type="text" id="tel1" size="25" maxlength="20" value="<?=$field[21] ?>" disabled /></td>
		<td class="tagForm">Celular:</td>
		<td><input name="tel2" type="text" id="tel2" size="25" maxlength="20" value="<?=$field[22] ?>" disabled /></td>
		<td class="tagForm">Fax:</td>
		<td><input name="tel3" type="text" id="tel3" size="25" maxlength="20" value="<?=$field[23] ?>" disabled /></td>
	</tr>
</table>

<div style="width:800px" class="divFormCaption">Documentos de Identificaci&oacute;n</div>
<table width="800" class="tblForm">
	<tr>
		<td width="125" class="tagForm">Principal:</td>
		<td>
			<select name="tdoc" id="tdoc" class="selectMed" disabled>
				<?php getMiscelaneos($field[24], "DOCUMENTOS", 1); ?>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Nro. Documento:</td>
		<td><input name="ndoc" type="text" id="ndoc" size="20" maxlength="20" value="<?=$field[25] ?>" disabled />*</td>
	</tr>
	<tr>
		<td class="tagForm">Doc. Fiscal:</td>
		<td><input name="rif" type="text" id="rif" size="20" maxlength="20" value="<?=$field[26] ?>" disabled />*</td>
	</tr>
</table>

<div style="width:800px" class="divFormCaption">Informaci&oacute;n Bancaria</div>
<table width="800" class="tblForm">
	<tr>
		<td width="125" class="tagForm">Banco:</td>
		<td>
			<select name="banco" id="banco" class="selectMed" disabled>
				<?php getBancos($field1[0], 0); ?>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Nro. Cuenta:</td>
		<td><input name="ncta" type="text" id="ncta" size="50" maxlength="30" value="<?=$field1[1] ?>" disabled />*</td>
	</tr>
	<tr>
		<td class="tagForm">Tipo Cuenta:</td>
		<td>
			<select name="tcta" id="tcta" class="selectMed" disabled>
				<?php getMiscelaneos($field1[2], "TIPOCTA", 0); ?>
			</select>*
		</td>
	</tr>
</table>

<div style="width:800px" class="divFormCaption">Datos de Auditor&iacute;a</div>
<table width="800" class="tblForm">
	<tr>
    <td class="tagForm">Estado:</td>
    <td>
			<input name="status" type="radio" value="A" <?=$activo ?> disabled /> Activo
			<input name="status" type="radio" value="I" <?=$inactivo ?> disabled /> Inactivo
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

</div>
	
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

<div id="tab2" style="display:none;">
<?
$sql = "SELECT * FROM mastproveedores WHERE CodProveedor = '".$registro."'";
$query_proveedor = mysql_query($sql) or die($sql.mysql_error());
if (mysql_num_rows($query_proveedor) != 0) {
	$field_proveedor  = mysql_fetch_array($query_proveedor);
	
	if ($field_proveedor['FlagSNC'] == "S") {
		$flagsnc = "checked";
		list($a, $m, $d)=SPLIT( '[/.-]', $field_proveedor['FechaConstitucion']); $fconstitucion="$d-$m-$a";
		list($a, $m, $d)=SPLIT( '[/.-]', $field_proveedor['FechaEmisionSNC']); $femision="$d-$m-$a";
		list($a, $m, $d)=SPLIT( '[/.-]', $field_proveedor['FechaValidacionSNC']); $fvalidacion="$d-$m-$a";
	} else $dsnc = "disabled";
		
}
?>

<div style='width:800px' class='divFormCaption'>Informaci&oacute;n para Pagos</div>
<table width="800" class="tblForm">
	<tr>
		<td width="175" class="tagForm">Documento del Proveedor:</td>
		<td>
			<select name="docproveedor" id="docproveedor" style="width:250px;">
				<option value=""></option>
				<?=loadSelect("ap_tipodocumento", "CodTipoDocumento", "Descripcion", $field_proveedor['CodTipoDocumento'], 0);?>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Documento de Pago:</td>
		<td>
			<select name="docpago" id="docpago" style="width:250px;">
				<option value=""></option>
				<?=loadSelect("masttipopago", "CodTipoPago", "TipoPago", $field_proveedor['CodTipoPago'], 0);?>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Forma de Pago:</td>
		<td>
			<select name="formapago" id="formapago" style="width:250px;">
				<option value=""></option>
				<?=loadSelect("mastformapago", "CodFormaPago", "Descripcion", $field_proveedor['CodFormaPago'], 0);?>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Tipo de Servicio:</td>
		<td>
			<select name="servicio" id="servicio" style="width:250px;">
				<option value=""></option>
				<?=loadSelect("masttiposervicio", "CodTipoServicio", "Descripcion", $field_proveedor['CodTipoServicio'], 0);?>
			</select>*
		</td>
	</tr>
</table>

<div style='width:800px' class='divFormCaption'>Informaci&oacute;n Adicional</div>
<table width="800" class="tblForm">
	<tr>
		<td width="175" class="tagForm">Nro. Dias para Pago:</td>
		<td><input name="dias" type="text" id="dias" size="5" value="<?=$field_proveedor['DiasPago']?>" /></td>
		<td class="tagForm">Registro P&uacute;blico:</td>
		<td><input name="registro" type="text" id="registro" size="20" maxlength="20" value="<?=$field_proveedor['RegistroPublico']?>" /></td>
	</tr>
	<tr>
		<td class="tagForm">Licencia Municipal:</td>
		<td><input name="licencia" type="text" id="licencia" size="20" maxlength="20" value="<?=$field_proveedor['LicenciaMunicipal']?>" /></td>
		<td class="tagForm">Fecha de Constitucion:</td>
		<td><input name="fconstitucion" type="text" id="fconstitucion" size="15" maxlength="10" value="<?=$fconstitucion?>" /></td>
	</tr>
	<tr>
		<td class="tagForm">Representante Legal:</td>
		<td><input name="representante" type="text" id="representante" size="20" maxlength="20" value="<?=$field_proveedor['RepresentanteLegal']?>" /></td>
		<td class="tagForm">Contacto/Vendedor:</td>
		<td><input name="contacto" type="text" id="contacto" size="20" maxlength="20" value="<?=$field_proveedor['ContactoVendedor']?>" /></td>
	</tr>
	<tr>
		<td class="tagForm">Nacionalidad:</td>
		<td>
        	<? if ($field['Nacionalidad'] == "E") $flagextranjero = "checked"; else $flagnacional = "checked";?>
        	<input type="radio" name="nacionalidad" id="nacional" <?=$flagnacional?> /> Nacional &nbsp; &nbsp;
        	<input type="radio" name="nacionalidad" id="extranjero" <?=$flagextranjero?> /> Extranjero
        </td>
	</tr>
</table>

<div style='width:800px' class='divFormCaption'>Informaci&oacute;n SNC</div>
<table width="800" class="tblForm">
	<tr>
		<td width="175" class="tagForm">Incripci&oacute;n SNC:</td>
		<td><input type="checkbox" name="flagsnc" id="flagsnc" value="S" <?=$flagsnc?> onclick="enabledSNCProveedor(this.checked);" /></td>
		<td class="tagForm">Nro. Insc. SNC:</td>
		<td><input name="nrosnc" type="text" id="nrosnc" size="20" maxlength="20" value="<?=$field_proveedor['NroInscripcionSNC']?>" <?=$dsnc?> /></td>
	</tr>
	<tr>
		<td class="tagForm">F. Emision SNC:</td>
		<td><input name="femision" type="text" id="femision" size="15" maxlength="10" value="<?=$femision?>" <?=$dsnc?> /></td>
		<td class="tagForm">F. Validaci&oacute;n SNC:</td>
		<td><input name="fvalidacion" type="text" id="fvalidacion" size="15" maxlength="10" value="<?=$fvalidacion?>" <?=$dsnc?> /></td>
	</tr>
</table>
<input type="hidden" name="actualizo" id="actualizo" value="<?=mysql_num_rows($query_proveedor)?>" />

</div>

<center> 
<?="
<input type='submit' value='Guardar Registro' />
<input name='bt_cancelar' type='button' id='bt_cancelar' value='Cancelar' onclick='cargarPagina(this.form, \"personas.php?filtro=".$_POST['filtro']."&limit=".$_POST['limit']."&ordenar=".$_POST['ordenar']."\");' />";
?>
</center><br />
<div style="width:800px" class="divMsj">Campos Obligatorios *</div>
</form>
</body>
</html>