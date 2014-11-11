<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<?php
define('FPDF_FONTPATH','font/');
require('mc_table.php');
require('fphp.php');
require('../clases/montoLetra.php');

$objMontoLetra = new EnLetras();

connect();

//include("../clases/MySQL.php");
//include("../comunes/objConexion.php");



$codigo=$_REQUEST['registro'];
 $sql = "SELECT DISTINCT
					  a.codBeneficio, a.nroBeneficio, a.tipoSolicitud,
					  e.NomCompleto, a.codPersona, a.montoTotal ,
					  a.codFamiliar,
					  a.codAyudaE,
					  a.codRamaS,
					  a.fechaEjecucion,
					  a.montoTotal,
					  a.*,
					  a.codFamiliar,
					  d.Dependencia,
					  f.decripcionAyudaE,
					  e.Ndocumento as cedula,
					  g.descripcionRamaS,
					  h.descripcioninsthcm,
					  i.Estado
				FROM
					  rh_beneficio AS a,
					
					  rh_empleadonivelacion AS c,
					  mastdependencias AS d,
					  mastpersonas AS e,
					  rh_ayudamedicaespecifica as f,
					  rh_ramaservicio as g,
					  rh_institucionhcm as h,
					  mastempleado as i
				WHERE
					
					  a.codBeneficio = $codigo AND
					  a.CodPersona = e.CodPersona AND
					  a.CodPersona = c.CodPersona AND
					
					  d.CodDependencia = c.CodDependencia AND
					  a.codAyudaE = f.codAyudaE AND
					  a.codRamaS = g.codRamaS AND
					  a.idInstHcm = h.idInstHcm AND
					  i.CodPersona=e.CodPersona
				ORDER BY
					  c.Secuencia DESC";

//$objConexion->ejecutarQuery($sql);
//$resp= $objConexion->getMatrizCompleta();	

$query=mysql_query($sql) or die ($sql.mysql_error());
$field=mysql_fetch_array($query);

//print_r ($field);

//$usuarioPreparado= $field['preparadoPor'];

if($field['fechaEjecucion']>='2013-03-20' && $field['fechaEjecucion']<='2013-05-20')
	$dir_administracion='MILAGROS RIVAS';

else if($field['fechaEjecucion'] < '2013-07-16' || $field['fechaEjecucion']>='2013-09-13') // && $field['fechaEjecucion']<='2013-05-20')
	$dir_administracion='VICENTE JOSÉ ARCIA MEJÍAS';

else if($field['fechaEjecucion']>= '2013-07-16' && $field['fechaEjecucion']<='2013-09-12') // && $field['fechaEjecucion']<='2013-05-20')
	$dir_administracion='ENRIQUE LUIS FIGUEROA LARES';


if($field['codFamiliar']!='0')
{

  $sql2 = "SELECT b.NombresCarga, b.Parentesco, b.Ndocumento, b.CodSecuencia, b.ApellidosCarga
				FROM rh_cargafamiliar AS b
				WHERE b.CodSecuencia = '".$field['codFamiliar']."' AND CodPersona ='".$field['codPersona']."'";

//$objConexion->ejecutarQuery($sql2);
//$resp2 = $objConexion->getMatrizCompleta();	

$query2=mysql_query($sql2) or die ($sql2.mysql_error());
$field2=mysql_fetch_array($query2);

}
$pdf=new PDF_MC_Table();
$pdf->Open();

//---------------------------------------------------
//	Imprime la cabedera del documento

	$pdf->AddPage();
	$pdf->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5,( 'CONTRALORÍA DEL ESTADO MONAGAS'), 0, 1, 'L');
	$pdf->SetXY(10, 10); $pdf->Cell(190, 5, utf8_decode('Fecha : '.date('d-m-Y')), 0, 1, 'R');
	
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, ('Dirección de Recursos Humanos'), 0, 1, 'L');	
	$pdf->SetXY(10, 15); $pdf->Cell(190, 5, ('Personal Administrativo'), 0, 1, 'R');	
	
	$pdf->SetXY(20, 20); $pdf->Cell(190, 5, ('Control de Beneficios'), 0, 1, 'L');
	$pdf->SetXY(10, 20); $pdf->Cell(190, 5, ('Número : '.$field['nroBeneficio']), 0, 1, 'R');
	
	$pdf->SetXY(20, 25); $pdf->Cell(190, 5, ('Servicio : '.utf8_decode($field['decripcionAyudaE'])), 0, 1, 'L');
	$pdf->SetXY(10, 25); $pdf->Cell(190, 5, ('Hora : '. date('H:m:s')), 0, 1, 'R');
	
	$pdf->SetXY(20, 30); $pdf->Cell(190, 5, ('Especialidad : '.utf8_decode($field['descripcionRamaS'])), 0, 1, 'L');
	$pdf->SetXY(20, 35); $pdf->Cell(190, 5, ('Institución : '.utf8_decode($field['descripcioninsthcm'])), 0, 1, 'L');
	
	
	
	$pdf->SetFont('Arial', 'B', 12);
	if($field['tipoSolicitud']=='R')	
		$solicitud='R E E M B O L S O';
	if($field['tipoSolicitud']=='E')
		$solicitud='E M I S I Ó N';
		
	$pdf->Cell(190, 5, '<< '.$solicitud.' >>', 0, 1, 'C');
	$pdf->Cell(190, 5, 'Monto (Bs.): '.number_format($field['montoTotal'],2,',','.'), 0, 1, 'R');
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(190, 5, $objMontoLetra->ValorEnLetras($field['montoTotal'],' Bolivares con '), 0, 1, 'R');
	$pdf->SetFont('Arial', '', 12);
	$pdf->Cell(190, 5, '', 0, 1, 'J');
	$pdf->Cell(190, 5, 'Por Concepto de Gastos Médicos en atención a los Beneficios socioeconomicos que otorga al', 0, 1, 'J');
	$pdf->Cell(190, 5, 'Personal adscrito a este Organo Contralor', 0, 1, 'J');
	
	if($resp[0]['codFamiliar']!='0')
	{
		$pdf->SetFont('Arial', 'B', 12);
		$pdf->Cell(190, 5, 'Ciudadano(a): '.$field2['Ndocumento'].'  -  '.utf8_decode($field2['NombresCarga']).' '.utf8_decode($field2['ApellidosCarga']), 0, 1, 'L');
		if($field2['Parentesco']=='MA')
			$parentesco = 'MADRE';
		else if($field2['Parentesco']=='PA')
			$parentesco = 'PADRE';
		else if($field2['Parentesco']=='HI')
			$parentesco = 'HIJO';
		else if($field2['Parentesco']=='ES')
			$parentesco = 'ESPOSA(O)';
			
		$pdf->Cell(190, 5, 'Parentesco : '.$parentesco.'   del FUNCIONARIO : '.$field['cedula'].' - '.utf8_decode($field['NomCompleto']), 0, 1, 'L');
		
		if($field['Estado'] == 'A')
		{
			$pdf->Cell(190, 5, 'Adscrito al Dependencia de : '.utf8_decode($field['Dependencia']), 0, 1, 'L');
		}
	
	}
	
	
	else
	{
		$pdf->SetFont('Arial', 'B', 12);		
			
		$pdf->Cell(190, 5, 'FUNCIONARIO : '.$resp[0]['cedula'].' - '. utf8_decode($field['NomCompleto']), 0, 1, 'L');
		
		$pdf->Cell(190, 5, 'Adscrito al Departamento de : '.$field['Dependencia'], 0, 1, 'L');
	}
	
	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetXY(10, 76);
	$pdf->Cell(190, 20, '', 0, 1, 'J');
	$pdf->Cell(47, 20, '', 1, 0, 'C', 0);
	$pdf->Cell(47, 20, '', 1, 0, 'C', 0);
	$pdf->Cell(47, 20, '', 1, 0, 'C', 0);
	$pdf->Cell(47, 20, '', 1, 0, 'C', 0);
	$pdf->SetXY(10, 115);
	

$sql2 = "
SELECT
mastpersonas.NomCompleto,
usuarios.CodPersona,
usuarios.Usuario
FROM
mastpersonas
INNER JOIN usuarios ON mastpersonas.CodPersona = usuarios.CodPersona

where usuarios.Usuario='RDEPABLOS'";

$sql2=mysql_query($sql2) or die ($sql2.mysql_error());
$field=mysql_fetch_array($sql2);

//print_r ($field);
//$sql2=mysql_query($sql2) or die ($sql2.mysql_error());
	//$field2=mysql_fetch_array($sql2);

	$pdf->Cell(47, 10, 'Elaborado Por:', 0, 0, 'C', 0);
	$pdf->Cell(47, 10, 'Revisado Por:', 0, 0, 'C', 0);
	$pdf->Cell(47, 10, 'Control Previo:', 0, 0, 'C', 0);
	$pdf->Cell(47, 10, 'Conformado por:', 0, 0, 'C', 0);
	$pdf->Ln();
	$pdf->Cell(47, 1, utf8_decode($field['NomCompleto']) , 0, 0, 'C', 0);
	$pdf->Cell(47, 1, 'KARLA AZOCAR', 0, 0, 'C', 0);
	//$pdf->Cell(47, 1, 'ROCIO TOUSSAINT',0, 0, 'C', 0);
	$pdf->Cell(47, 1, 'CESAR GRANADO',0, 0, 'C', 0);
	$pdf->Cell(47, 1, 'ROXAIDA ESTRADA', 0, 0, 'C', 0);



	
//---------------------------------------------------

//---------------------------------------------------
//	Creaci�n del objeto de la clase heredada


//	Cuerpo


$pdf->Output();
?>
