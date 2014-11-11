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

<?php
include("fphp.php");
connect();
//	EDITO EL REGISTRO
if ($_GET['accion']=="EDITAR") {
	$sql="SELECT * FROM rh_empleado_documentos WHERE CodDocumento='".$_GET['secuencia']."' AND CodPersona='".$_GET['registro']."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	if ($rows!=0) {
		$field=mysql_fetch_array($query);
		$_POST['secuencia']=$field['CodDocumento'];
		$_POST['doc']=$field['Documento'];		
		list($a, $m, $d)=SPLIT('[/.-]', $field['FechaPresento']); $_POST['fentrega']=$d."-".$m."-".$a;
		if ($_POST['fentrega']=="00-00-0000" || $_POST['fentrega']=="00/00/0000") $_POST['fentrega']="";
		list($a, $m, $d)=SPLIT('[/.-]', $field['FechaVence']); $_POST['fvence']=$d."-".$m."-".$a;
		if ($_POST['fvence']=="00-00-0000" || $_POST['fvence']=="00/00/0000") $_POST['fvence']="";
		if ($field['FlagPresento']=="S") { $chkPresento="checked"; $dPresento=""; } else { $chkPresento=""; $dPresento="disabled"; }
		if ($field['FlagCarga']=="S") { $chkCarga="checked"; $dCarga=""; } else { $chkCarga=""; $dCarga="disabled"; }
		$_POST['familiar']=$field['CargaFamiliar'];
		$_POST['nomfamiliar']=$field['CargaFamiliar'];
		$_POST['parentesco']=$field['CargaFamiliar'];
		$_POST['observacion']=$field['Observaciones'];
		$_POST['archivo']=$field['Ruta'];
		$_POST['ult_usuario']=$field['UltimoUsuario'];
		$_POST['ult_fecha']=$field['UltimaFecha'];
	}
} else { $chkPresento=""; $dPresento="disabled"; $chkCarga=""; $dCarga="disabled"; }
//	ELIMINO EL REGISTRO
if ($_GET['accion']=="ELIMINAR") {
	$sql="DELETE FROM rh_empleado_documentos WHERE CodDocumento='".$_GET['secuencia']."' AND CodPersona='".$_GET['registro']."' ";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
if ($_POST['registro']=="") $_POST['registro']=$_GET['registro'];
echo "	
<form id='frmentrada' name='frmentrada' action='documentos.php' method='POST' onsubmit='return verificarDocumento(this, \"INSERTAR\");'>
<input type='hidden' name='secuencia' id='secuencia' value='".$_POST['secuencia']."' />
<input type='hidden' name='registro' id='registro' value='".$_POST['registro']."' />
<table width='750' class='tblForm'>
	<tr>
    <td class='tagForm'>Documento:</td>
    <td>
			<select name='doc' id='doc' class='selectMed'>
				<option value=''></option>";
				getMiscelaneos($_POST['doc'], "DOCUMENTOS", 0);
			echo "
			</select>*
		</td>
    <td class='tagForm'>&iquest;Present&oacute;?:</td>
    <td><input name='flagpresento' type='checkbox' id='flagpresento' value='S' $chkPresento onclick='setFlagPresento(this.form);' /></td>
    <td class='tagForm'>Documento Familiar:</td>
    <td><input name='flagfamiliar' type='checkbox' id='flagfamiliar' value='S' $chkCarga onclick='setFlagFamiliar(this.form);' /></td>
  </tr>
  <tr>
    <td class='tagForm'>Fecha Entrega:</td>
    <td><input name='fentrega' type='text' id='fentrega' size='15' maxlength='10' value='".$_POST['fentrega']."' $dPresento /><em>(dd-mm-yyyy)</em></td>
    <td class='tagForm'>Fecha Vencimiento:</td>
    <td colspan='3'><input name='fvence' type='text' id='fvence' size='15' maxlength='10' value='".$_POST['fvence']."' $dPresento /><em>(dd-mm-yyyy)</em></td>
  </tr>
  <tr>
    <td class='tagForm'>Persona Relacionada:</td>
    <td colspan='2'>
			<input name='familiar' type='hidden' id='familiar' value='".$_POST['familiar']."' />
			<input name='nomfamiliar' type='text' id='nomfamiliar' size='65' value='".$_POST['nomfamiliar']."' readonly />
			<input name='bt_examinar' type='button' id='bt_examinar' value='...' onclick='window.open(\"lista_familiares.php?limit=0&registro=".$_POST['registro']."\", \"wLista\", \"toolbar=no, menubar=no, location=no, scrollbars=yes, height=500, width=800, left=200, top=200, resizable=yes\");' $dCarga />
		</td>
    <td class='tagForm'>Parentesco:</td>
    <td><input name='parentesco' type='text' id='parentesco' size='25' value='".$_POST['parentesco']."' readonly /></td>
  </tr>
  <tr>
    <td class='tagForm'>Observaciones:</td>
    <td colspan='5'><input name='observacion' type='text' id='observacion' size='75' maxlength='255' value='".$_POST['observacion']."' /></td>
  </tr>
  <tr>
    <td class='tagForm'>Archivo:</td>
    <td colspan='5'><input name='archivo' type='text' id='archivo' size='75' maxlength='255' value='".$_POST['archivo']."' /></td>
  </tr>
</table>
<center>
<input type='submit' value='Guardar Registro' />
<input type='button' value='Resetear' onclick='resetDocumento(this.form);' />
</center>
	<br /><div style='width:750px' class='divMsj'>Campos Obligatorios *</div><br />
</form>

<br /><div class='divDivision'>Lista de Documentos</div><br />

<form name='frmtabla'>
<table width='750' class='tblBotones'>
 <tr>
	<td align='right'>
		<input name='btEditar' type='button' class='btLista' id='btEditar' value='Editar' onclick='optDocumento(this.form, \"EDITAR\");' />
		<input name='btBorrar' type='button' class='btLista' id='btBorrar' value='Borrar' onclick='optDocumento(this.form, \"ELIMINAR\");' />
	</td>
 </tr>
</table>";

//	CONSULTO LA TABLA
$sql="SELECT rh_empleado_documentos.CodDocumento, mastmiscelaneosdet.Descripcion, rh_empleado_documentos.FlagPresento, rh_empleado_documentos.FechaPresento, rh_empleado_documentos.FechaVence, rh_empleado_documentos.CargaFamiliar, rh_empleado_documentos.Observaciones, rh_empleado_documentos.Ruta FROM rh_empleado_documentos, mastmiscelaneosdet WHERE (rh_empleado_documentos.Documento=mastmiscelaneosdet.CodDetalle AND mastmiscelaneosdet.CodMaestro='DOCUMENTOS')";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
<input type='hidden' name='sec' id='sec' />	
<table class='tblLista'>
  <tr class='trListaHead'>
    <th width='50' scope='col'>#</th>
    <th width='400' scope='col'>Documento</th>
    <th width='75' scope='col'>&iquest;Present&oacute;?</th>
    <th width='100' scope='col'>Fecha Entrega </th>
    <th width='100' scope='col'>Fecha Vencimiento </th>
  </tr>";
for ($i=0; $i<$rows; $i++) {
	$field=mysql_fetch_array($query);
	list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaPresento']); $fpresento=$d."-".$m."-".$a; if ($fpresento=="00-00-0000") $fpresento="";
	list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaVence']); $fvence=$d."-".$m."-".$a; if ($fvence=="00-00-0000") $fvence="";
	if ($field['ruta']!="") $btVer="<input type='button' id='".$field['CodDocumento']."' value='Ver' />"; else $btVer="";
	echo "
	<tr class='trListaBody' onclick='mClk(this, \"sec\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field[0]."'>
    <td colspan='5'>
			<table cellpadding='0' cellspacing='0'>
	      <tr>
	        <td>
						<table cellpadding='0' cellspacing='0'>
	            <tr>
	              <td align='center' width='50'>".$field['CodDocumento']."</td>
	            </tr>
						</table>
					</td>
	        <td>
						<table>
	            <tr>
	              <td width='400'>".$field[1]."</td>
	              <td align='center' width='75'>".$field['FlagPresento']."</td>
	              <td align='center' width='100'>".$fpresento."</td>
	              <td align='center' width='100'>".$fvence."</td>
	            </tr>
						</table>
						<table width='100%' cellpadding='0' cellspacing='0' border='1' style='border-color:#FFFFFF;'>
	            <tr>
	              <td width='150' class='tdLista' height='25' scope='col'>Persona Relacionada: &nbsp;</th>
	              <td> &nbsp; ".$field['CargaFamiliar']."</td>
	            </tr>
	            <tr>
	              <th class='tdLista' height='25' scope='col'>Observaciones: &nbsp;</th>
	              <td> &nbsp; ".$field['Observaciones']."</td>
	            </tr>
	            <tr>
	              <th class='tdLista' height='25' scope='col'>Archivo: &nbsp; </th>
	              <td> &nbsp; ".$field['Ruta']." $btVer</td>
	            </tr>
						</table>
					</td>
	      </tr>
	    </table>
		</th>
  </tr>";
}
echo "
</table>
</form>";
echo "
<script type='text/javascript' language='javascript'>
	totalElementos($rows);
</script>";
?>
</body>
</html>