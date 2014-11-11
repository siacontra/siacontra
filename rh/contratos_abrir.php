<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<?php
include("fphp.php");
connect();

$sql="SELECT ValorParam AS Ruta FROM mastparametros WHERE ParametroClave='PATHFORM'";
$query_param=mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_param)!=0) $field_param=mysql_fetch_array($query_param);
//	--------------------
$sql="SELECT mp.Ndocumento, mp.NomCompleto, rc.TipoContrato, rfc.RutaPlant FROM mastpersonas mp INNER JOIN rh_contratos rc ON (mp.CodPersona=rc.CodPersona) INNER JOIN rh_formatocontrato rfc ON (rc.TipoContrato=rfc.TipoContrato) WHERE mp.CodPersona='".$registro."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) {
	$field=mysql_fetch_array($query);
	
	$archivo=fopen($field_param['Ruta'].$field["RutaPlant"], "r");
	if ($archivo) {
		//	OBTENGO LA INFORMACION DEL ARCHIVO
		while (!feof($archivo)) $texto.=fgets($archivo, 255);
		
		//	SUSTITUYO LOS CAMPOS CON LOS DATOS DEL EMPLEADO
		$texto=ereg_replace("_cedula_", "{\\b $field[0] }", $texto);
		$texto=ereg_replace("_nombre_", "{\\b $field[1] }", $texto);
		$texto=ereg_replace("_organismo_", "{\\b $field[2] }", $texto);
		
		//	GENERO MI DOCUMENTO RTF ()PUEDO ABRIRLO CON WORD
		header('Content-type: application/msword');
		header('Content-Disposition: inline; filename='.$_GET['registro'].'.rtf');
		$output="{\\rtf1";
		$output.=$texto;
		$output.="}";
		echo $output;
		///////////////////////////////////////////////////
	}
}
?>