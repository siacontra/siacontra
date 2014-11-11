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
//	CONSULTO LA TABLA
$sql="SELECT rh_postulantes_documentos.Secuencia, mastmiscelaneosdet.Descripcion, rh_postulantes_documentos.FlagPresento, rh_postulantes_documentos.Observaciones FROM rh_postulantes_documentos, mastmiscelaneosdet WHERE (rh_postulantes_documentos.Documento=mastmiscelaneosdet.CodDetalle AND mastmiscelaneosdet.CodMaestro='DOCUMENTOS') AND rh_postulantes_documentos.Postulante='".$registro."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
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
	<tr class='trListaBody' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['Secuencia']."'>
    	<td align='center'>".$i."</td>
    	<td>".($field['Descripcion'])."</td>
    	<td>".($field['Observaciones'])."</td>
    	<td align='center'><input type='checkbox' name='fpresento' id='fpresento' $checked disabled /></td>
  	</tr>";
}
echo "</table>";
?>
</body>
</html>