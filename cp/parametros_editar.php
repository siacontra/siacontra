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
		<td class="titulo">Maestro de Par&aacute;metros | Actualizaci&oacute;n</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'parametros.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="parametros.php" method="POST" onsubmit="return verificarParametro(this, 'ACTUALIZAR');">
	
<?php
include("fphp.php");
connect();
$sql="SELECT * FROM mastparametros WHERE ParametroClave='".$_POST['registro']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) {
	$field=mysql_fetch_array($query);
	if ($field['TipoValor']=="T") { $chktexto="checked"; $dtexto=""; $texto=$field['ValorParam']; }
	else { $chktexto=""; $dtexto="disabled"; $texto=""; }
	if ($field['TipoValor']=="N") { $chknumero="checked"; $dnumero=""; $numero=$field['ValorParam']; }
	else { $chknumero=""; $dnumero="disabled"; $numero=""; }
	if ($field['TipoValor']=="F") { $chkfecha="checked"; $dfecha=""; $fecha=$field['ValorParam']; }
	else { $chkfecha=""; $dfecha="disabled"; $fecha=""; }
	echo "
	<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />
	<div style='width:700px' class='divFormCaption'>Datos del Par&aacute;metro</div>
	<table width='700' class='tblForm'>
	  <tr>
	    <td class='tagForm'>Aplicaci&oacute;n:</td>
	    <td>
				<select name='aplicacion' id='aplicacion' class='selectMed'>
					<option value=''></option>";
					getAplicaciones($field['CodAplicacion'], 0);
				echo "
				</select>*
			</td>
	  </tr>
		<tr>
	    <td class='tagForm'>Organismo:</td>
	    <td>
				<select name='organismo' id='organismo' class='selectBig'>
					<option value=''></option>";
					getOrganismos($field['CodOrganismo'], 0);
				echo "
				</select>*
			</td>
	  </tr>
	  <tr>
	    <td height='20' width='150' align='right'>Par&aacute;metro:</td>
	    <td><input name='codigo' type='text' id='codigo' size='25' maxlength='20' readonly value='".$field['ParametroClave']."' /></td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Descripci&oacute;n:</td>
	    <td><input name='descripcion' type='text' id='descripcion' size='100' maxlength='100' value='".htmlentities($field['DescripcionParam'])."' />*</td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Explicaci&oacute;n:</td>
	    <td><textarea name='explicacion' id='explicacion' cols='100' rows='2'>".htmlentities($field['Explicacion'])."</textarea></td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Estado:</td>
	    <td>";
				if ($field['Estado']=="A") echo "<input name='status' type='radio' value='A' checked /> Activo"; 
				else echo "<input name='status' type='radio' value='A' /> Activo";
				if ($field['Estado']=="I") echo "<input name='status' type='radio' value='I' checked /> Inactivo"; 
				else echo "<input name='status' type='radio' value='I' /> Inactivo";
			echo "
			</td>
	  </tr>
	</table>
	<div style='width:700px' class='divFormCaption'>Valor del Par&aacute;metro</div>
	<table width='700' class='tblForm'>
	  <tr>
	    <td class='tagForm'>Texto:</td>
	    <td>
				<input name='tipo' type='radio' value='T' $chktexto onclick='chkTipoParam(this.form, this.value);' />
				<input name='texto' type='text' id='texto' size='75' maxlength='100' value='$texto' $dtexto />*
			</td>
	  </tr>
	  <tr>
	    <td class='tagForm'>N&uacute;mero:</td>
	    <td>
			<input name='tipo' type='radio' value='N' $chknumero onclick='chkTipoParam(this.form, this.value);' />
			<input name='numero' type='text' id='numero' size='30' maxlength='15' dir='rtl' value='$numero' $dnumero onkeyup='forzarReal(this.form, this);' />* 
			<em>(999999,99)</em>
		</td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Fecha:</td>
	    <td>
				<input name='tipo' type='radio' value='F' $chkfecha onclick='chkTipoParam(this.form, this.value);' />
				<input name='fecha' type='text' id='fecha' size='20' maxlength='10' value='$fecha' $dfecha onkeyup='forzarFecha(this.form, this);' />* <i>(dd-mm-yyyy)</i>
			</td>
	  </tr>	
	  <tr>
	    <td class='tagForm'>&Uacute;ltima Modif.:</td>
	    <td>
				<input name='ult_usuario' type='text' id='ult_usuario' size='30' value='".$field['UltimoUsuario']."' readonly />
				<input name='ult_fecha' type='text' id='ult_fecha' size='25' value='".$field['UltimaFecha']."' readonly />
			</td>
	  </tr>
	</table>
	<center> 
	<input type='submit' value='Guardar Registro' />
	<input name='bt_cancelar' type='button' id='bt_cancelar' value='Cancelar' onclick='cargarPagina(this.form, \"parametros.php\");' />
	</center><br />";
}
?>
</form>

<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
