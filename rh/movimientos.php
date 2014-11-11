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
//
$estado["E"]="Entregado"; $estado["D"]="Devuelto"; $estado["P"]="Perdido";
//	EDITO EL REGISTRO
if ($_GET['accion']=="EDITAR") {
	$sql="SELECT rh_documentos_historia.*, mastpersonas.NomCompleto FROM rh_documentos_historia, mastpersonas WHERE (rh_documentos_historia.Secuencia='".$_GET['secuencia']."' AND rh_documentos_historia.CodPersona='".$_GET['registro']."' AND rh_documentos_historia.CodDocumento='".$_GET['documento']."') AND (rh_documentos_historia.Responsable=mastpersonas.CodPersona)";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	if ($rows!=0) {
		$field=mysql_fetch_array($query);
		$_POST['secuencia']=$field['Secuencia'];
		$_POST['responsable']=$field['Responsable'];
		$_POST['nomresponsable']=$field['NomCompleto'];
		list($a, $m, $d)=SPLIT('[/.-]', $field['FechaEntrega']); $_POST['fentrega']=$d."-".$m."-".$a;
		if ($_POST['fentrega']=="00-00-0000" || $_POST['fentrega']=="00/00/0000") $_POST['fentrega']="";
		list($a, $m, $d)=SPLIT('[/.-]', $field['FechaDevuelto']); $_POST['fstatus']=$d."-".$m."-".$a;
		if ($_POST['fstatus']=="00-00-0000" || $_POST['fstatus']=="00/00/0000") $_POST['fstatus']="";
		$_POST['obsentrega']=$field['ObsEntrega'];
		$_POST['obsestado']=$field['ObsDevuelto'];
		$reg=2; $dfentrega="disabled"; $dfestado=""; $dobsentrega="disabled"; $dobsestado=""; $dresponsable="disabled"; $_POST['status']="D";
	}
} else { $reg=1; $dfentrega=""; $dfestado="disabled"; $dobsentrega=""; $dobsestado="disabled"; $_POST['status']="E"; $dresponsable=""; }
//	ELIMINO EL REGISTRO
if ($_GET['accion']=="ELIMINAR") {
	$sql="DELETE FROM rh_documentos_historia WHERE CodDocumento='".$_GET['documento']."' AND CodPersona='".$_GET['registro']."' AND Secuencia='".$_GET['secuencia']."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}

if ($_POST['registro']=="") $_POST['registro']=$_GET['registro'];
echo "
<form id='frmentrada' name='frmentrada' action='movimientos.php' method='POST' onsubmit='return verificarMovimiento(this, \"INSERTAR\");'>
<input type='hidden' name='secuencia' id='secuencia' value='".$_POST['secuencia']."' />
<input type='hidden' name='registro' id='registro' value='".$_POST['registro']."' />
<input type='hidden' name='documento' id='documento' value='".$_GET['documento']."' />
<table width='750' class='tblForm'>
  <tr>
    <td width='125' class='tagForm'>Fecha Entrega:</td>
    <td><input name='fentrega' type='text' id='fentrega' size='15' maxlength='10' value='".$_POST['fentrega']."' $dfentrega />*<em>(dd-mm-yyyy)</em></td>
    <td class='tagForm'>Persona Relacionada:</td>
    <td colspan='2'>
			<input name='codpersona' type='hidden' id='codpersona' value='".$_POST['resposable']."' />
			<input name='persona' type='text' id='persona' size='50' value='".$_POST['nomresponsable']."' readonly />*
			<input name='bt_examinar' type='button' id='bt_examinar' value='...' onclick='window.open(\"lista_personas.php?limit=0\", \"wLista\", \"toolbar=no, menubar=no, location=no, scrollbars=yes, height=500, width=800, left=200, top=200, resizable=yes\");' $dresponsable />
		</td>
  </tr>
	<tr>
    <td class='tagForm'>Estado:</td>
    <td>
			<select name='status' id='status'>";
				getStatusDoc($_POST['status'], $reg);
			echo "
			</select>
		</td>
    <td class='tagForm'>Fecha Estado:</td>
    <td><input name='fstatus' type='text' id='fstatus' size='15' maxlength='10' value='".$_POST['fstatus']."' $dfestado />*<em>(dd-mm-yyyy)</em></td>
  </tr>
  <tr>
    <td class='tagForm'>Observaciones Entrega:</td>
    <td colspan='3'><textarea name='obsentrega' id='obsentrega' rows='1' cols='75' $dobsentrega>".$_POST['obsentrega']."</textarea></td>
  </tr>
  <tr>
    <td class='tagForm'>Observaciones de Devoluci&oacute;n o P&eacute;rdida:</td>
    <td colspan='3'><textarea name='obsestado' id='obsestado' rows='1' cols='75' $dobsestado>".$_POST['obsestado']."</textarea></td>
  </tr>
</table>
<center>
<input type='submit' value='Guardar Registro' />
<input type='button' value='Resetear' onclick='resetMovimiento(this.form);' />
</center>
	<br /><div style='width:750px' class='divMsj'>Campos Obligatorios *</div><br />
</form>

<br /><div class='divDivision'>Lista de Movimientos</div><br />

<form name='frmtabla'>
<table width='750' class='tblBotones'>
 <tr>
	<td align='right'>
		<input name='btEditar' type='button' class='btLista' id='btEditar' value='Editar' onclick='optMovimiento(this.form, \"EDITAR\");' />
		<input name='btBorrar' type='button' class='btLista' id='btBorrar' value='Borrar' onclick='optMovimiento(this.form, \"ELIMINAR\");' />
	</td>
 </tr>
</table>";

//	CONSULTO LA TABLA
$sql="SELECT rh_documentos_historia.*, mastpersonas.NomCompleto FROM rh_documentos_historia, mastpersonas WHERE (rh_documentos_historia.CodDocumento='".$_GET['documento']."' AND rh_documentos_historia.CodPersona='".$_POST['registro']."') AND (rh_documentos_historia.Responsable=mastpersonas.CodPersona) ORDER BY rh_documentos_historia.Secuencia";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
<input type='hidden' name='sec' id='sec' />	
<table class='tblLista'>
  <tr class='trListaHead'>
    <th width='50' scope='col'>#</th>
    <th width='100' scope='col'>Fecha Entrega</th>
    <th width='400' scope='col'>Responsable</th>
    <th width='75' scope='col'>Estado</th>
    <th width='100' scope='col'>Fecha Estado</th>
  </tr>";
for ($i=0; $i<$rows; $i++) {
	$field=mysql_fetch_array($query);
	list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaEntrega']); $fentrega=$d."-".$m."-".$a; if ($fentrega=="00-00-0000") $fentrega="";
	list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaDevuelto']); $fdevuelto=$d."-".$m."-".$a; if ($fdevuelto=="00-00-0000") $fdevuelto="";
	echo "
	<tr class='trListaBody' onclick='mClk(this, \"sec\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field[2]."'>
    <td colspan='5'>
			<table cellpadding='0' cellspacing='0'>
	      <tr>
	        <td>
						<table cellpadding='0' cellspacing='0'>
	            <tr>
	              <td align='center' width='50'>".$field['Secuencia']."</td>
	            </tr>
						</table>
					</td>
	        <td>
						<table>
	            <tr>
	              <td align='center' width='100'>".$fentrega."</td>
	              <td width='400'>".$field['NomCompleto']."</td>
	              <td align='center' width='75'>".$estado[$field['Estado']]."</td>
	              <td align='center' width='100'>".$fdevuelto."</td>
	            </tr>
						</table>
						<table width='100%' cellpadding='0' cellspacing='0' border='1' style='border-color:#FFFFFF;'>
	            <tr>
	              <td width='150' class='tdLista' height='25' scope='col'>Observaciones Entrega: &nbsp;</th>
	              <td> &nbsp; ".$field['ObsEntrega']."</td>
	            </tr>
	            <tr>
	              <th class='tdLista' height='25' scope='col'>Observaciones Devoluci&oacute;n o P&eacute;rdida: &nbsp;</th>
	              <td> &nbsp; ".$field['ObsDevuelto']."</td>
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