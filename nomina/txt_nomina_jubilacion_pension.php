<?php
session_start();
header('Content-Type: text/html; charset=iso-8859-1');
set_time_limit(-1);
include("fphp_nomina.php");
connect();
$texto="";
$archivo=fopen($nombre_archivo.".txt", "w+");
//---------------
$LF = 0x0A;
$CR = 0x0D;
$nl = sprintf("%c%c",$CR,$LF);
$fecha=date("d/m/y");
//---------------

//---------------
//	Cuerpo
$sql = "SELECT 
			mp.CodPersona,
			mp.Ndocumento,
			mp.Busqueda,
			mp.Nombres,
			mp.Apellido1,
			ptne.TotalIngresos,
			(SELECT SUM(TotalIngresos) 
				FROM pr_tiponominaempleado 
					WHERE CodPersona = mp.CodPersona AND CodTipoNom = '".$ftiponom."' AND Periodo = '".$fperiodo."') AS TotalIngresosMes,
			ptnec.Monto,
			(SELECT Monto FROM pr_tiponominaempleadoconcepto WHERE CodPersona = mp.CodPersona AND CodTipoNom = '".$ftiponom."' AND Periodo = '".$fperiodo."' AND CodTipoproceso = '".$ftproceso."' AND CodConcepto = '0032') AS Aporte
		FROM
			mastpersonas mp
			INNER JOIN pr_tiponominaempleado ptne ON (mp.CodPersona = ptne.CodPersona)
			INNER JOIN pr_tiponominaempleadoconcepto ptnec ON (ptne.CodPersona = ptnec.CodPersona AND ptne.CodTipoNom = ptnec.CodTipoNom AND ptne.Periodo = ptnec.Periodo AND ptne.CodTipoproceso = ptnec.CodTipoProceso AND ptnec.CodConcepto = '0027')
		WHERE
			ptne.CodTipoNom = '".$ftiponom."' AND
			ptne.Periodo = '".$fperiodo."' AND
			ptne.CodTipoProceso = '".$ftproceso."'
		ORDER BY length(mp.Ndocumento), mp.Ndocumento";
$query = mysql_query($sql) or die ($sql.mysql_error());
while ($field = mysql_fetch_array($query)) {
	$sum_ingresos += $field['TotalIngresos'];
	$sum_retenciones += $field['Monto'];
	$sum_aportes += $field['Aporte'];
	$nombre = $field['Nombres']." ".$field['Apellido1'];
	
	$texto.=$field['Ndocumento']."|".date("dmY")."|".$nombre."|".number_format($field['TotalIngresos'], 2, ',', '')."|".number_format($field['Monto'], 2, ',', '')."|".number_format($field['Aporte'], 2, ',', '').$nl;
}

fwrite($archivo, ($texto));
fclose($archivo);

?>