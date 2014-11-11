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
	$sql="SELECT * FROM rh_postulantes_documentos WHERE Secuencia='".$_GET['secuencia']."' AND Postulante='".$_GET['registro']."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	if ($rows!=0) {
		$field=mysql_fetch_array($query);
		$_POST['secuencia']=$field['CodDocumento'];
		$_POST['doc']=$field['Documento'];
		if ($field['FlagPresento']=="S") $chkPresento="checked"; else $chkPresento="";
		$_POST['observacion']=($field['Observaciones']);
	}
} else { $chkPresento=""; $dPresento="disabled"; }
//	ELIMINO EL REGISTRO
if ($_GET['accion']=="ELIMINAR") {
	$sql="DELETE FROM rh_postulantes_documentos WHERE Secuencia='".$_GET['secuencia']."' AND Postulante='".$_GET['registro']."' ";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
if ($_POST['registro']=="") $_POST['registro']=$_GET['registro'];
echo "	
<form id='frmentrada' name='frmentrada' action='postulantes_documentos.php' method='POST' onsubmit='return verificarPostDocumento(this, \"INSERTAR\");'>
<input type='hidden' name='secuencia' id='secuencia' value='".$_POST['secuencia']."' />
<input type='hidden' name='registro' id='registro' value='".$_POST['registro']."' />
<table width='800' class='tblForm'>
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
    <td><input name='flagpresento' type='checkbox' id='flagpresento' value='S' $chkPresento /></td>
  </tr>
  <tr>
    <td class='tagForm'>Observaciones:</td>
    <td colspan='3'><input name='observacion' type='text' id='observacion' size='100' maxlength='255' value='".$_POST['observacion']."' /></td>
  </tr>
</table>
<center>
<input type='submit' value='Guardar Registro' />
<input type='button' value='Resetear' onclick='resetPostDocumento(this.form);' />
</center>
</form>

<form name='frmtabla'>
<table width='800' class='tblBotones'>
 <tr>
	<td align='right'>
		<input name='btEditar' type='button' class='btLista' id='btEditar' value='Editar' onclick='optPostDocumento(this.form, \"EDITAR\");' />
		<input name='btBorrar' type='button' class='btLista' id='btBorrar' value='Borrar' onclick='optPostDocumento(this.form, \"ELIMINAR\");' />
	</td>
 </tr>
</table>";

//	CONSULTO LA TABLA
$sql="SELECT rh_postulantes_documentos.Secuencia, mastmiscelaneosdet.Descripcion, rh_postulantes_documentos.FlagPresento, rh_postulantes_documentos.Observaciones FROM rh_postulantes_documentos, mastmiscelaneosdet WHERE (rh_postulantes_documentos.Documento=mastmiscelaneosdet.CodDetalle AND mastmiscelaneosdet.CodMaestro='DOCUMENTOS') AND rh_postulantes_documentos.Postulante='".$registro."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
<input type='hidden' name='sec' id='sec' />	
<table class='tblLista'>
  <tr class='trListaHead'>
    <th width='50' scope='col'>#</th>
    <th width='250' scope='col'>Documento</th>
    <th width='405' scope='col'>Observaciones </th>
    <th width='75' scope='col'>&iquest;Present&oacute;?</th>
  </tr>";
for ($i=1; $i<=$rows; $i++) {
	$field=mysql_fetch_array($query);
	if ($field['FlagPresento']=="S") $checked="checked"; else $checked="";
	echo "
	<tr class='trListaBody' onclick='mClk(this, \"sec\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['Secuencia']."'>
    	<td align='center'>".$i."</td>
    	<td>".($field['Descripcion'])."</td>
    	<td>".($field['Observaciones'])."</td>
    	<td align='center'><input type='checkbox' name='fpresento' id='fpresento' $checked disabled /></td>
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