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
		<td class="titulo">Ver Contrato</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
	
<?php
include("fphp.php");
connect();
$sql="SELECT mastpersonas.CodPersona, mastpersonas.NomCompleto, mastempleado.CodOrganismo, mastempleado.CodEmpleado, rh_contratos.TipoContrato, rh_contratos.FechaDesde, rh_contratos.FechaHasta, rh_contratos.CodFormato, rh_contratos.Estado, rh_contratos.FlagFirma, rh_contratos.FechaFirma, rh_contratos.Comentarios, rh_contratos.UltimoUsuario, rh_contratos.UltimaFecha, rh_formatocontrato.Documento FROM mastpersonas, mastempleado, rh_contratos, rh_formatocontrato WHERE mastpersonas.CodPersona='".$_GET['registro']."' AND mastempleado.CodPersona='".$_GET['registro']."' AND rh_contratos.CodPersona='".$_GET['registro']."' AND rh_contratos.TipoContrato=rh_formatocontrato.TipoContrato";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) {
	$field=mysql_fetch_array($query);
	if ($field[8]=="N") { $checked=""; $disabled="disabled"; } 
	else { $checked="checked"; $disabled=""; }
	list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaDesde']); $vcond=$d."-".$m."-".$a; if ($vcond=="00-00-0000") $vcond="";
	list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaHasta']); $vconh=$d."-".$m."-".$a; if ($vconh=="00-00-0000") { $vconh=""; $dvconh="disabled"; }
	list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaFirma']); $fechafirma=$d."-".$m."-".$a; if ($fechafirma=="00-00-0000") $fechafirma="";
	echo "
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
					getTContratos($field['TipoContrato'], 0);
				echo "
				</select>*
			</td>
	  </tr>
		<tr>
	    <td class='tagForm'>Vigencia de Contrato:</td>
	    <td>
				<input name='vcontratod' type='text' id='vcontratod' size='15' maxlength='10' value='".$vcond."' readonly /> -
				<input name='vcontratoh' type='text' id='vcontratoh' size='15' maxlength='10' value='".$vconh."'  $dvconh readonly />
				*<i>(dd-mm-yyyy)</i>
			</td>
	  </tr>
		<tr>
	    <td class='tagForm'>Form. Impresi&oacute;n:</td>
	    <td>
				<input type='hidden' name='fcon' id='fcon' value='".$field['TipoContrato']."' />
				<input type='text' name='nfcon' id='nfcon' size='50' value='".$field['Documento']."' readonly />
			</td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Estado del Contrato:</td>
	    <td>";
				if ($field['Estado']=="VI" || $field['Estado']!="VE") 				
				echo "<input name='status' type='radio' value='VI' checked /> Vigente";
				else echo "<input name='status' type='radio' value='VI' /> Vigente";				
				if ($field['Estado']=="VE") echo "<input name='status' type='radio' value='VE' checked /> Vencido";
				else echo "<input name='status' type='radio' value='VE' /> Vencido";
			echo "
			</td>
	  </tr>
		<tr>
	    <td class='tagForm'>Firma de Contrato?:</td>
	    <td><input name='flagfirma' type='checkbox' id='flagfirma' value='S' $checked onclick='enabledFirmaContrato(this.form);' /></td>
	  </tr>
		<tr>
	    <td class='tagForm'>Fecha de Firma:</td>
	    <td><input name='firma' type='text' id='firma' size='15' maxlength='10' value='".$fechafirma."' $disabled readonly /><i>(dd-mm-yyyy)</i></td>
	  </tr>
		<tr>
	    <td class='tagForm'>Comentarios:</td>
	    <td><textarea name='coment' id='coment' rows='4' cols='100' readonly>".utf8_decode($field['Comentarios'])."</textarea></td>
	  </tr>
	  <tr>
		  <td class='tagForm'>&Uacute;ltima Modif.:</td>
		  <td>
				<input name='ult_usuario' type='text' id='ult_usuario' size='30' value='".$field['UltimoUsuario']."' readonly />
				<input name='ult_fecha' type='text' id='ult_fecha' size='25' value='".$field['UltimaFecha']."' readonly />
			</td>
		</tr>
	</table>";
}
?>

</body>
</html>
