<?php
	extract($_POST);
	extract($_GET);
	
	//---------------------------------------------------
	
	require_once('../tcpdf/config/lang/spa.php');
	
	require_once('../tcpdf/tcpdf.php');
	
	ini_set('memory_limit','128M');
	include ("../funciones.php");

   	include_once ("../clases/MySQL.php");
	
	include_once("../comunes/objConexion.php");
	
	ob_end_clean();
	
	
	// extend TCPF with custom functions
	
	class MYPDF extends TCPDF {
	
	
	
		// Load table data from file	
	
		public function Header() {
	
			// Logo
			$this->Image('../imagenes/logos/contraloria.jpg', 10, 10, 15, 10, 'JPG', '', '', true, 150, 'L', false, false, 0, false, false, false);				
			$this->Cell(18, 16, '', 0, 1, 'L', 0, '', 0, false);
			$this->Cell(0, 0, ('CONTRALORÍA DEL ESTADO MONAGAS'), 0, 1, 'L', 0, '', 0);
			$this->Cell(0, 0, ('DIRECCIÓN DE RECURSOS HUMANOS'), 0, 0, 'L', 0, '', 0);
			
		}
	
	}

	

	// create new PDF document
	
	$pdf = new MYPDF('P','mm','A4', true, 'UTF-8', false);
	
	// set document information
	
	$pdf->SetCreator('SIACES');
	
	$pdf->SetAuthor('SIACES');
	
	$pdf->SetTitle(utf8_decode('Reporte General Útiles'));
	
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
	
	
	// set default monospaced font
	
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	
	
	//set margins
	
	
	$pdf->SetMargins(25, 35, 25,15);
	
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	
	
	//set image scale factor
	
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	
	
	
	//set some language-dependent strings
		
	$pdf->setLanguageArray($l);
	
	$sql = "SELECT Nomina
			FROM tiponomina
			WHERE CodTipoNom = '".$tipoNomina."'";
	
	$respTipoNomina = $objConexion->consultar($sql,"fila");
	
	
	$sql2 = "SELECT mp.Nombres, mp.Apellido1, mp.Apellido2, bu.montoutiles, bu.codpersonabeneficiario, ua.codutilesayuda, mp.NDocumento
	
	FROM rh_beneficiarioutiles as bu
	
	join mastpersonas as mp on bu.codpersonabeneficiario = mp.CodPersona
	join mastempleado as me on me.CodPersona = mp.CodPersona
	join rh_empleadonivelacion as en on en.CodPersona = me.CodPersona
	join rh_utilesayuda as ua on bu.codutilesayuda = ua.codutilesayuda
	where en.CodTipoNom='".$tipoNomina."' and en.FechaHasta='0000-00-00' and ua.periodoutiles='".$periodo."'";
	
	$respEmpleados = $objConexion->consultar($sql2,"matriz");
	
	
	
	// ---------------------------------------------------------
	
	$pdf->AddPage();
	
	$pdf->SetFont('times', 'I', 10);
	
	
	$fecha = date("d-m-Y");
	
	$pdf->Cell(0, 0, 'Fecha: '.$fecha, 0, 1, 'R', 0, '', 0);
	
	$pdf->Cell(0, 0, "Tipo Nómina: ".$respTipoNomina['Nomina'], 0, 1, 'R', 0, '', 0);
	$pdf->Cell(0, 0, "Periodo: ".$periodo, 0, 1, 'R', 0, '', 0);
	$pdf->Cell(0, 0, "", 0, 1, 'R', 0, '', 0);
	
	// ---------------------------------------------------------------------------------------------------------------------------
	$strHTML = '';
	
	
	$strHTML .='<tr><td  width="30" align="left" ><strong >N°</strong></td><td  width="80" align="left"><strong >Cédula</strong></td><td width="400" align="left"><strong>Empleado/Hijo</strong></td><td width="100" align="right"><strong>Monto</strong></td></tr>';
	
	$total = 0;
	
	for($i = 0; $i < count($respEmpleados); $i++)
	{
		
		$empleado = $respEmpleados[$i]['Nombres']." ".$respEmpleados[$i]['Apellido1']." ".$respEmpleados[$i]['Apellido2'];
		
		$sql3 = "SELECT cf.NombresCarga, cf.ApellidosCarga, fu.montoutilesfamiliar, cf.Ndocumento 
	
		FROM rh_cargafamiliar as cf
		
		join rh_familarutilesbeneficio as fu on fu.codsecuenciafamiliar=cf.CodSecuencia
						
		join rh_beneficiarioutiles as bu on bu.codbeneficiarioutiles=fu.codbeneficiarioutiles 

		where bu.codpersonabeneficiario='".$respEmpleados[$i]['codpersonabeneficiario']."' 
		and cf.CodPersona='".$respEmpleados[$i]['codpersonabeneficiario']."'
		and cf.Parentesco='HI'";
		
		$respFamiliar = $objConexion->consultar($sql3,"matriz");
	
		$strHTML .='<tr ><td  width="30" align="left" >'.($i+1).'</td><td  width="80" align="left" >'.$respEmpleados[$i]['NDocumento'].'</td><td width="400" align="left">'.$empleado.'</td><td width="100" align="right">'.str_replace(".",",",$respEmpleados[$i]['montoutiles']).'</td></tr>';
		
		for ($f = 0; $f < count($respFamiliar); $f++)
		{
			
			$strHTML .='<tr><td  width="30"  ></td><td  width="80" >'.$respFamiliar[$f]['Ndocumento'].'</td><td width="400">'.$respFamiliar[$f]['NombresCarga']." ".$respFamiliar[$f]['ApellidosCarga'].'</td><td width="100">'.str_replace(".",",",$respFamiliar[$f]['montoutilesfamiliar']).'</td></tr>';
		}	
		
		$total += $respEmpleados[$i]['montoutiles'];
	}
	
	$strHTML .='<tr><td colspan="3" width="510" align="right"><strong>Total</strong></td><td width="100" align="right"><strong>'.str_replace(".",",",$total).'</strong></td></tr>';
	
	
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	
$tb1 = <<<EOD
<br><br><br><table align="" width="100%" cellspacing="2" height="63" border="0">

	   $strHTML
	 
	</table>

	
EOD;
	

	
	$pdf->SetFillColor(255, 255, 255);
	
	//-------------------------------------------------------------------------------------
	
	// set font
	
	$pdf->SetFont('times', 'IB', 12);
	
	
	
	$pdf->Write(0, ("Reporte Detallado Útiles"), '', 0, 'C', true, 0, false, false, 0);
	
	
	$pdf->SetFont('times', 'I', 9);
	
	$pdf->writeHTML($tb1, true, false, false, false, 'C');
	
	$pdf->writeHTML($tb12, true, false, false, false, 'C');
	
	//Close and output PDF document
	
	$pdf->Output('reporte_utiles_detallado.pdf', 'I');
	
	//============================================================+
	
	// END OF FILE                                                
	
	//============================================================+
