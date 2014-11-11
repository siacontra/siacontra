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
		<td class="titulo">Nuevo Contrato</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
$sql="SELECT mastpersonas.CodPersona, mastpersonas.NomCompleto, mastempleado.CodOrganismo, mastempleado.CodEmpleado FROM mastpersonas, mastempleado WHERE mastpersonas.CodPersona='".$_GET['registro']."' AND mastempleado.CodPersona='".$_GET['registro']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) {
	$field=mysql_fetch_array($query);
	echo "
	<form name='frmentrada' action='contratos_nuevo.php' method='POST' onsubmit='return verificarContrato(this, \"NUEVO\");'>
	<input type='hidden' name='filtro' id='filtro' value='".$_GET['filtro']."'>
	<div class='divBorder' style='width:700px;'>
	<input name='persona' type='hidden' id='persona' value='".$field['CodPersona']."' />
	<table width='700' class='tblFiltro'>
	  <tr>
	    <td align='right'>Empleado:</td>
	    <td><input type='text' size='10' value='".$field['CodEmpleado']."' readonly /></td>
	  </tr>
	  <tr>
	    <td align='right'>Nombre Completo:</td>
	    <td><input name='nompersona' type='text' id='nompersona' size='75' value='".$field['NomCompleto']."' readonly /></td>
	  </tr>
	</table>
	</div><br />
	
	<div style='width:700px' class='divFormCaption'>Datos del Contrato</div>
	<table width='700' class='tblForm'>
		<tr>
	    <td class='tagForm'>Organismo:</td>
	    <td>
				<select name='organismo' id='organismo' class='selectBig'>";
					getOrganismos($field['CodOrganismo'], 1);
				echo "
				</select>*
			</td>
	  </tr>
		<tr>
	    <td class='tagForm'>Tipo de Contrato:</td>
	    <td>
				<select name='tcon' id='tcon' class='selectBig' onchange='setTipoContrato(this.value);'>
					<option value=''></option>";
					getTContratos('', 0);
				echo "
				</select>*
			</td>
	  </tr>
		<tr>
	    <td class='tagForm'>Vigencia de Contrato:</td>
	    <td>
				<input name='vcontratod' type='text' id='vcontratod' size='15' maxlength='10' disabled /> -
				<input name='vcontratoh' type='text' id='vcontratoh' size='15' maxlength='10' disabled />
				*<i>(dd-mm-yyyy)</i>
			</td>
	  </tr>
		<tr>
	    <td class='tagForm'>Form. Impresi&oacute;n:</td>
	    <td>
				<input type='hidden' name='fcon' id='fcon' />
				<input type='text' name='nfcon' id='nfcon' size='50' readonly />
			</td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Estado del Contrato:</td>
	    <td>";
				if ($field['Estado']=="VI" || $field['Estado']!="VE") echo "<input name='status' type='radio' value='VI' checked /> Vigente";
				else echo "<input name='status' type='radio' value='VI' /> Vigente";
				if ($field['Estado']=="VE") echo "<input name='status' type='radio' value='VE' checked /> Vencido";
				else echo "<input name='status' type='radio' value='VE' /> Vencido";
			echo "
			</td>
	  </tr>
		<tr>
	    <td class='tagForm'>Firma de Contrato?:</td>
	    <td><input name='flagfirma' type='checkbox' id='flagfirma' value='S' onclick='enabledFirmaContrato(this.form);' /></td>
	  </tr>
		<tr>
	    <td class='tagForm'>Fecha de Firma:</td>
	    <td><input name='firma' type='text' id='firma' size='15' maxlength='10' disabled onkeyup='forzarFecha(this.form, this);' /><i>(dd-mm-yyyy)</i></td>
	  </tr>
		<tr>
	    <td class='tagForm'>Comentarios:</td>
	    <td><textarea name='coment' id='coment' rows='4' cols='100'></textarea></td>
	  </tr>
	  <tr class='tr4'>
		  <td class='tagForm'>&Uacute;ltima Modif.:</td>
		  <td>
				<input name='ult_usuario' type='text' id='ult_usuario' size='30' value='".$field['UltimoUsuario']."' readonly />
				<input name='ult_fecha' type='text' id='ult_fecha' size='25' value='".$field['UltimaFecha']."' readonly />
			</td>
		</tr>
	</table>
	<center> 
	<input type='submit' value='Guardar Registro' />
	<input name='bt_cancelar' type='button' id='bt_cancelar' value='Cancelar' onclick='javascript:window.close();' />
	</center><br />
	</form>
	<div style='width:700px' class='divMsj'>Campos Obligatorios *</div>";
}
?>
</body>
</html>
