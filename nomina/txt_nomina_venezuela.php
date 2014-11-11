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

$fl = sprintf("%c",$CR);

$fecha=date("d/m/y");
//---------------

//---------------
/*$sql = "SELECT
			mp.Ndocumento,
			CONCAT(mp.Apellido1, ' ', mp.Nombres) AS Busqueda,
			ptne.TotalNeto,
			bp.Ncuenta,
			bp.TipoCuenta,
			rbp.CodBeneficiario,
			rbp.NroDocumento,
			rbp.NombreCompleto,

		FROM
			pr_tiponominaempleado ptne
			INNER JOIN mastpersonas mp ON (ptne.CodPersona = mp.CodPersona)
			INNER JOIN bancopersona bp ON (ptne.CodPersona = bp.CodPersona)
			LEFT JOIN rh_beneficiariopension rbp ON (mp.CodPersona = rbp.CodPersona)
		WHERE
			ptne.CodTipoProceso = '".$codproceso."' AND
			ptne.Periodo = '".$periodo."' AND
			ptne.CodOrganismo = '".$organismo."' AND
			ptne.TotalNeto > 0
		ORDER BY ptne.CodTipoNom, length(mp.Ndocumento), mp.Ndocumento";*/

$sql = "SELECT mp.Ndocumento, CONCAT( mp.Apellido1, ' ', mp.Nombres ) AS Busqueda, 
	
	ptne.TotalNeto, bp.Ncuenta, 
	bp.TipoCuenta, 
	rbp.CodBeneficiario, 
	rbp.NroDocumento, 
	rbp.NombreCompleto, 
	me.NombreSobreViviente, 
	me.CedulaSobreViviente, 
	me.NombreSobrevivienteOtro, 
	me.CedulaSobrevivienteOtro

FROM pr_tiponominaempleado ptne
INNER JOIN mastpersonas mp ON ( ptne.CodPersona = mp.CodPersona )
INNER JOIN mastempleado me ON ( me.CodPersona = mp.CodPersona )
INNER JOIN bancopersona bp ON ( ptne.CodPersona = bp.CodPersona )
LEFT JOIN rh_beneficiariopension rbp ON ( mp.CodPersona = rbp.CodPersona )

WHERE
			ptne.CodTipoProceso = '".$codproceso."' AND
			ptne.Periodo = '".$periodo."' AND
			ptne.CodOrganismo = '".$organismo."' AND
			ptne.TotalNeto > 0
		ORDER BY ptne.CodTipoNom, length(mp.Ndocumento), mp.Ndocumento";

$query = mysql_query($sql) or die ($sql.mysql_error());

$cantidadFilas = mysql_num_rows($query);//cantidad de filas que trae la consulta
$t = 0;

while ($field = mysql_fetch_array($query)) {
	$t++;

	if ($field['CodBeneficiario'] != "") {

		$nombre = ereg_replace("[^A-Za-z 0-9]", "", $field['NombreCompleto']);
		$cedula = $field['NroDocumento'];

	} else if ($field['NombreSobreViviente'] != "") {

		$nombre = ereg_replace("[^A-Za-z 0-9]", "", $field['NombreSobreViviente']);
		$cedula = $field['CedulaSobreViviente'];

	} else {
		$nombre = ereg_replace("[^A-Za-z 0-9]", "", $field['Busqueda']);
		$cedula = $field['Ndocumento'];
	}

	if ($field['NombreSobrevivienteOtro'] != "") {

		$nombre = ereg_replace("[^A-Za-z 0-9]", "", $field['NombreSobrevivienteOtro']);
		$cedula = $field['CedulaSobrevivienteOtro'];

	}

	$sum += $field['TotalNeto'];
 	//--
	if ($field['TipoCuenta'] == "CO") $tipo_cuenta = "0"; else $tipo_cuenta = "1";
 	//--
	$nrocuenta = (string) str_repeat("0", 20-strlen($field['Ncuenta'])).$field['Ncuenta'];
	//--
	list($int, $dec)=SPLIT( '[.]', $field['TotalNeto']); $field_monto = "$int$dec";
	$monto = (string) str_repeat("0", 11-strlen($field_monto)).$field_monto;
	//--
	$relleno_1 = "0770";
	//--
	$nombre = (string) $nombre.str_repeat(" ", 40-strlen($nombre));
	//--
	$cedula = (string) str_repeat("0", 10-strlen($cedula)).$cedula;
	//--
	$relleno_2 = "003291  ";
	//--
	
	if($t != $cantidadFilas)
	{
		$texto.=$tipo_cuenta.$nrocuenta.$monto.$relleno_1.$nombre.$cedula.$relleno_2."$nl";

	} else {

		$texto.=$tipo_cuenta.$nrocuenta.$monto.$relleno_1.$nombre.$cedula.$relleno_2."$fl";

	}
}

list($int, $dec)=SPLIT( '[.]', $sum); 

if(strlen($dec) == 1)
{
	$dec = "".$dec."0";
}

if(strlen($dec) == 0)
{
	$dec = "".$dec."00";
}

$total = "$int$dec";

$total_neto = (string) str_repeat("0", 13-strlen($total)).$total;
	
$titulo = "HContraloria del Estado                  0102051313000012385101".$fecha.$total_neto."03291 ".$nl;

fwrite($archivo, $titulo.$texto);
fclose($archivo);

?>
