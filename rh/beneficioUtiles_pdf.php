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


//echo $_REQUEST['registro'];
$codigo=$_REQUEST['registro'];

//echo $codigo
  $sql = "SELECT a. * , b.NomCompleto AS persona, c.NomCompleto AS proveedor, b.Ndocumento, d.CodDependencia
		FROM rh_beneficiarioutiles AS a
		LEFT JOIN mastpersonas AS b ON b.CodPersona = a.codpersonabeneficiario
		INNER JOIN mastpersonas AS c ON c.CodPersona = a.CodProveedor
		INNER JOIN mastempleado AS d ON d.CodPersona = b.CodPersona

	 WHERE a.codbeneficiarioutiles =".$codigo."";

//$objConexion->ejecutarQuery($sql);
//$resp= $objConexion->getMatrizCompleta();	

$query=mysql_query($sql) or die ($sql.mysql_error());
$field=mysql_fetch_array($query);
$fecha=explode(' ',$field['ultimafecha']);
//echo $fecha[0];
//if($fecha[0] < '2013-07-16' || $field['fechaEjecucion']>='2013-09-13') // && $field['fechaEjecucion']<='2013-05-20')
	$dir_administracion='VICENTE JOSÉ ARCIA MEJÍAS';

 	


$sql2 ="SELECT a . * , b.ApellidosCarga, b.NombresCarga, b.Ndocumento
	FROM rh_familarutilesbeneficio AS a, rh_cargafamiliar AS b
	WHERE a.codbeneficiarioutiles =".$codigo." AND
		b.CodPersona =	'".$field['codpersonabeneficiario']."' AND
	      b.CodSecuencia = a.codsecuenciafamiliar";

  
//$objConexion->ejecutarQuery($sql2);
//$resp2 = $objConexion->getMatrizCompleta();	

$query2=mysql_query($sql2) or die ($sql2.mysql_error());


//array(100,150)
$pdf=new PDF_MC_Table();

$pdf->Open();

//---------------------------------------------------


//	Imprime la cabedera del documento


		$rows=mysql_num_rows($query2);
		//	MUESTRO LA TABLA
		for ($i=0; $i<$rows; $i++) {
			$field2=mysql_fetch_array($query2);
			$pdf->AddPage('P',array(205,137));
			$pdf->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->SetXY(20, 10); $pdf->Cell(190, 5,utf8_decode( 'CONTRALORÍA DEL ESTADO SUCRE'), 0, 1, 'L');
			$pdf->SetXY(10, 10); $pdf->Cell(190, 5, utf8_decode('Fecha : '.date('d-m-Y')), 0, 1, 'R');
	
			$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode('Dirección de Recursos Humanos'), 0, 1, 'L');	
			$pdf->SetXY(10, 15); $pdf->Cell(190, 5, ('Personal Administrativo'), 0, 1, 'R');	
	
			$pdf->SetXY(20, 20); $pdf->Cell(190, 5, ('Control de Beneficios'), 0, 1, 'L');
			$pdf->SetXY(10, 20); $pdf->Cell(190, 5, utf8_decode('Número : '.$field['nroBeneficioUtiles']), 0, 1, 'R');
	
			$pdf->SetXY(20, 25); $pdf->Cell(190, 5, ('Servicio : Utiles Escolares'), 0, 1, 'L');
			$pdf->SetXY(10, 25); $pdf->Cell(190, 5, ('Hora : '. date('H:m:s')), 0, 1, 'R');
	
			$pdf->SetXY(20, 30); $pdf->Cell(190, 5, ('Especialidad : Utiles Escolares'), 0, 1, 'L');
			$pdf->SetXY(20, 35); $pdf->Cell(190, 5, utf8_decode('Institución : '.$field['proveedor']), 0, 1, 'L');
	
	
			/*
			$pdf->SetFont('Arial', 'B', 12);
			if($field['tipoSolicitud']=='R')	
				$solicitud='R E E M B O L S O';
			if($field['tipoSolicitud']=='E')
				$solicitud='E M I S I Ó N';*/
		
	
			$pdf->Cell(190, 5, 'Monto (Bs.): '.number_format($field2['montoutilesfamiliar'],2,',','.'), 0, 1, 'R');
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(190, 5, strtoupper($objMontoLetra->ValorEnLetras($field2['montoutilesfamiliar'],'BOLIVARES COM')), 0, 1, 'R');

			$pdf->SetFont('Arial', '', 12);
			$pdf->Cell(190, 5, utf8_decode('Atención : '.$field['proveedor']), 0, 1, 'L');
			$pdf->Cell(190, 5, '<< A G R A D E Z C O L E     A T E N D E R     A >>', 0, 1, 'C');	
			$pdf->Cell(190, 5, '', 0, 1, 'J');

			$pdf->Cell(190, 5, utf8_decode('Ciudadano(a):'.$field2['Ndocumento'].' - '.$field2['NombresCarga'].' '.$field2['ApellidosCarga']), 0, 1, 'J');
			$pdf->Cell(190, 5, utf8_decode('Parentesco : HIJO del FUNCIONARIO :'.$field['Ndocumento'].' - '.$field['persona']), 0, 1, 'J');
			//$pdf->Cell(190, 5, 'Adscrito al departamento de :', 0, 1, 'J');
			//$pdf->Cell(190, 5, 'Organo al Personal adscrito a este Ente Contralor', 0, 1, 'J');


				$sql3 ="SELECT * FROM mastdependencias WHERE CodDependencia='".$field['CodDependencia']."'";

		  


				$query3=mysql_query($sql3) or die ($sql3.mysql_error());
				$field3=mysql_fetch_array($query3);


			//$pdf->SetFont('Arial', 'B', 12);		
			
			//$pdf->Cell(190, 5, 'FUNCIONARIO : '.$resp[0]['cedula'].' - '.$field['NomCompleto'], 0, 1, 'L');
		
			$pdf->Cell(190, 5, utf8_decode('Adscrito al Departamento de : '.$field3['Dependencia']), 0, 1, 'L');


			$pdf->Cell(190, 5, 'Quien quiere de sus servicios en lo referente a : (VER ANEXO)', 0, 1, 'L');

	
	
	
	
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 9);
			$pdf->SetXY(10, 70);
			$pdf->Cell(190, 20, '', 0, 1, 'J');
			$pdf->Cell(47, 20, '', 1, 0, 'C', 0);
			$pdf->Cell(47, 20, '', 1, 0, 'C', 0);
			$pdf->Cell(47, 20, '', 1, 0, 'C', 0);
			$pdf->Cell(47, 20, '', 1, 0, 'C', 0);
			$pdf->SetXY(10, 110);
	
			$pdf->Cell(47, 5, 'Elaborado Por:', 0, 0, 'C', 0);
			$pdf->Cell(47, 5, 'Dir. RRHH:', 0, 0, 'C', 0);
			$pdf->Cell(47, 5, 'Control Interno:', 0, 0, 'C', 0);
			$pdf->Cell(47, 5, utf8_decode('Dir. Administración:'), 0, 0, 'C', 0);
			$pdf->Ln();
			$pdf->Cell(47, 1, utf8_decode('NELLYS ASTUDILLO MARTÍNEZ'), 0, 0, 'C', 0);
			$pdf->Cell(47, 1, utf8_decode('ROSALBA GÓMEZ DÍAZ'), 0, 0, 'C', 0);
			$pdf->Cell(47, 1, '',0, 0, 'C', 0);
			$pdf->Cell(47, 1, utf8_decode($dir_administracion), 0, 0, 'C', 0);
			$rows=(int)$rows;
		}
	
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada


//	Cuerpo


$pdf->Output();
?>
