<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<?php
include("fphp.php");
connect();
$dia_actual=date("d");
$mes_actual=date("m"); 
$anio_actual=date("Y");

$sql="SELECT ValorParam AS Ruta FROM mastparametros WHERE ParametroClave='PATHFORM'";
$query_param=mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_param)!=0) $field_param=mysql_fetch_array($query_param);
//	--------------------
$sql = "SELECT
			ptne.*
		FROM
			pr_tiponominaempleado ptne
		WHERE
			ptne.CodPersona = '".$registro."' AND 
			ptne.CodTipoProceso = 'FIN' AND
			ptne.Periodo = (SELECT
								MAX(Periodo)
							FROM
								pr_tiponominaempleado
							WHERE
								CodPersona = '".$registro."' AND
								CodTipoProceso = 'FIN'
							)";
$query_primas = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_primas) != 0) {
	$field_primas = mysql_fetch_array($query_primas);
	
	$primas = $field_primas['TotalIngresos'] - $field_primas['SueldoBasico'];
	$total_primas = number_format($primas, 2, ',', '.');
	
	$total_ingresos = $field_primas['TotalIngresos']; 
}						
//	--------------------
$sql="SELECT mp.NomCompleto, mp.Nacionalidad, mp.Ndocumento, mp.Sexo, me.Fingreso, rp.DescripCargo, rp.NivelSalarial FROM mastpersonas mp INNER JOIN mastempleado me ON (mp.CodPersona=me.CodPersona) INNER JOIN rh_puestos rp ON (me.CodCargo=rp.CodCargo) WHERE mp.CodPersona='".$registro."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) {
	$field=mysql_fetch_array($query);
	
	$nombre=$field['NomCompleto'];
	$ced=number_format($field['Ndocumento'], 0, '', '.');
	$cedula=$field['Nacionalidad']."-".$ced;
	list($a, $m, $d)=SPLIT( '[/.-]', $field['Fingreso']); $fingreso=$d."-".$m."-".$a;
	$cargo=$field['DescripCargo'];
	
	$sueldo=number_format($field['NivelSalarial'], 2, ',', '.');
	$sueldo="(Bs. $sueldo)";
	$sueldo_letras=convertir_a_letras($field['NivelSalarial']);
	$sueldo=utf8_decode(strtoupper("$sueldo_letras $sueldo"));
	
	$primas_letras = convertir_a_letras($primas);
	$_PRIMAS = utf8_decode(strtoupper("$primas_letras (Bs. $total_primas)"));
	
	$sueldo_normal = number_format($total_ingresos, 2, ',', '.');
	$sueldo_normal_letras = convertir_a_letras($total_ingresos);
	$SueldoNormal = utf8_decode(strtoupper("$sueldo_normal_letras (Bs. $sueldo_normal)"));
	
	if ($field['Sexo']=="F") $funcionario="la funcionaria"; else $funcionario="el funcionario";
	$dia_letras=entero_a_letras($dia_actual);
	$dia="$dia_letras ($dia_actual)";
	$anio_letras=entero_a_letras($anio_actual);
	$anio="$anio_letras ($anio_actual)";
	$m=(int) $mes_actual; 
	$mes=getNombreMes($m);
	
	$archivo=fopen($field_param['Ruta']."CONSTANCIA.rtf", "r");
	if ($archivo) {
		//	OBTENGO LA INFORMACION DEL ARCHIVO
		while (!feof($archivo)) $texto.=fgets($archivo, 255);
		
		//	SUSTITUYO LOS CAMPOS CON LOS DATOS DEL EMPLEADO
		$texto=ereg_replace("NomCompleto", "{\\b $nombre}", $texto);
		$texto=ereg_replace("Ndocumento", "{\\b $cedula}", $texto);
		$texto=ereg_replace("Fingreso", "{\\b $fingreso}", $texto);
		$texto=ereg_replace("DescripCargo", "{\\b $cargo}", $texto);
		$texto=ereg_replace("NivelSalarial", "{\\b $sueldo}", $texto);
		$texto=ereg_replace("el funcionario", "{ $funcionario}", $texto);
		$texto=ereg_replace("DiaActual", "{$dia}", $texto);
		$texto=ereg_replace("MesActual", "{$mes}", $texto);
		$texto=ereg_replace("AnioActual", "{$anio}", $texto);
		$texto=ereg_replace("_PRIMAS_", "{$_PRIMAS}", $texto);
		$texto=ereg_replace("SueldoNormal", "{$SueldoNormal}", $texto);
		
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