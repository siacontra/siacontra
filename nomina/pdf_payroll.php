<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<?php

define('FPDF_FONTPATH','font/');
require('mc_table3.php');
require('fphp.php');
connect();


$organismo = $forganismo;
$periodo = $fperiodo;
$nomina = $ftiponom;


/*
$forganismo	0001
$fperiodo	2014-01
$ftiponom	01
$ftproceso	PRQ
*/
function titulo ($pdf,$nomina) 

{
////////////////////////////////////////////////
/*	
$pdf->SetDrawColor(255, 255, 255); 
$pdf->SetFillColor(255, 255, 255); 
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Times', 'B', 12);
$pdf->SetWidths(array(210));
$pdf->SetAligns(array('R'));

//$pdf->Row(array('Pagina: '.$pdf->PageNo().'/{nb}'));

$pdf->SetFont('Times', 'B', 14);

$pdf->SetXY(10, 5); $pdf->Cell(150, 8, utf8_decode( 'Contraloría del Estado Monagas'), 0, 1, 'L');
$pdf->SetFont('Times', 'B', 10);	
$pdf->Ln(3);
$pdf->Cell(190, 5, 'Fecha de Emision:'.$pdf->getFechaEmision(), 0, 1, 'R');
$pdf->Ln(5);
$pdf->SetDrawColor(0, 0, 0); 
$pdf->SetFillColor(255, 255, 255); 
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Times', 'B', 11);
$pdf->SetWidths(array(70,65,60));
$pdf->SetAligns(array('L','L','L','L'));
$pdf->Row(array('Nomina','Nombre del Proceso','Periodo'));
$pdf->SetFont('Times', 'B', 10);	
$pdf->Row(array($pdf->getNomina(), $pdf->getNombreProceso(), $pdf->getDesde().' A: '.$pdf->getHasta()));
$pdf->Ln(1);	*/
///////////////////////////////////////////////
	}


class PDF extends PDF_MC_Table
{
//Page header

//atrib

private $fechaEmision;
private $desde;
private $hasta;
private $nomina;
private $periodo;
private $organismo;


private $proceso;
private $nombreProceso;

// funciones

function setFechaEmision($var) {
	$this->fechaEmision= $var;
}

function getFechaEmision() {
	return $this->fechaEmision;
}
function setDesde($var) {
	$this->desde= $var;
}

function getDesde() {
	return $this->desde;
}
function setHasta($var) {
	$this->hasta= $var;
}

function getHasta() {
	return $this->hasta;
}
function setNomina($var) {
	$this->nomina= $var;
}

function getNomina() {
	return $this->nomina;
}
function setPeriodo($var) {
	$this->periodo= $var;
}

function getPeriodo() {
	return $this->periodo;
}

function setProceso($var) {
	$this->proceso= $var;
}

function getProceso() {
	return $this->proceso;
}


function setNombreProceso($var) {
	$this->nombreProceso= $var;
}

function getNombreProceso() {
	return $this->nombreProceso;
}


///////////////////////////////////////
function titulo(){

/*
$this->SetDrawColor(255, 255, 255); 
$this->SetFillColor(255, 255, 255); 
$this->SetTextColor(0, 0, 0);
$this->SetFont('Times', 'B', 12);
$this->SetWidths(array(210));
$this->SetAligns(array('R'));

$this->Row(array('Pagina: '.$this->PageNo().'/{nb}'));

$this->SetFont('Times', 'B', 14);

$this->SetXY(10, 5); $this->Cell(150, 8, utf8_decode( 'Contraloría del Estado Monagas'), 0, 1, 'L');
$this->SetFont('Times', 'B', 10);	
$this->Ln(3);
$this->Cell(190, 5, 'Fecha de Emision:'.$this->getFechaEmision(), 0, 1, 'R');
$this->Ln(5);
$this->SetDrawColor(0, 0, 0); 
$this->SetFillColor(255, 255, 255); 
$this->SetTextColor(0, 0, 0);
$this->SetFont('Times', 'B', 11);
$this->SetWidths(array(70,65,60));
$this->SetAligns(array('L','L','L','L'));
$this->Row(array('Nomina','Nombre del Proceso','Periodo'));
$this->SetFont('Times', 'B', 10);	
$this->Row(array($this->getNomina(), $this->getNombreProceso(), $this->getDesde().' A: '.$this->getHasta()));
$this->Ln(1);
*/
//$this->Cell(100, 5, ('De: '.$this->getDesde().' A: '.$this->getHasta()), 0, 0, 'L');

//$this->Cell(100, 5, 'Nomina:'.$this->getNomina(), 0, 1, 'L');
//$this->Cell(50, 5, 'Proceso:'.$this->getProceso(), 0, 1, 'L');
//$this->Cell(50, 5, 'Nombre Proceso:'.$this->getNombreProceso(), 0, 1, 'L');

}


}
//	--------------------------------------------------
//	Creación del objeto de la clase heredada

//array ();
$total_neto_reporte=0.0;
$pdf=new PDF('P', 'mm', 'Legal');
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetMargins(5,5, 5);
$pdf->SetAutoPageBreak(10);

// Obtengo la fecha del payroll	...
$sql = "SELECT * FROM pr_procesoperiodo WHERE CodOrganismo = '".$organismo."' AND Periodo = '".$periodo."' AND CodTipoNom = '".$nomina."' AND CodTipoProceso = '".$ftproceso."'";
$query_fecha = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_fecha) != 0) $field_fecha = mysql_fetch_array($query_fecha);
list($a, $m, $d)=SPLIT( '[-./]', $field_fecha['FechaDesde']); $de= "$d/$m/$a";
list($a, $m, $d)=SPLIT( '[-./]', $field_fecha['FechaHasta']); $a= "$d/$m/$a";


//	Imprimo el tiupo de nomina
$sql = "SELECT * FROM tiponomina WHERE CodTipoNom = '".$nomina."'";
$query_nomina = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_nomina) != 0) $field_nomina = mysql_fetch_array($query_nomina);

// AGREGAR PAGINA
$pdf->setDesde($de);
$pdf->setHasta($a);
$pdf->setFechaEmision(date('Y-m-d H:i:s'));

$pdf->setNomina(utf8_decode($field_nomina['TituloBoleta']));
//$pdf->setPeriodo('123123');


//	Imprimo el tiupo de nomina
$query_proceso = "SELECT
pr_tipoproceso.CodTipoProceso,
pr_tipoproceso.Descripcion,
pr_tipoproceso.FlagAdelanto,
pr_tipoproceso.Estado,
pr_tipoproceso.UltimoUsuario,
pr_tipoproceso.UltimaFecha
FROM
pr_tipoproceso
where 
pr_tipoproceso.CodTipoProceso = '".$ftproceso."'";

$query_proceso = mysql_query($query_proceso) or die ($query_proceso.mysql_error());
if (mysql_num_rows($query_proceso) != 0) $field_proceso = mysql_fetch_array($query_proceso);

$pdf->setProceso( $ftproceso);
$pdf->setNombreProceso(utf8_decode($field_proceso['Descripcion']));

//print_r ($field_nomina);

// PRIMERA PAGINA
/*
$pdf->AddPage();
$pdf->SetFont('Times', 'B', 16);
$pdf->SetXY(5, $y); $pdf->Cell(150, 8, utf8_decode( 'Contraloría del Estado Monagas'), 0, 1, 'L');
$pdf->SetFont('Times', 'B', 12);	
$pdf->Cell(100, 5, ('De: '.$de.' A: '.$a), 0, 0, 'L');
$pdf->Cell(80, 5, utf8_decode($nomproceso), 0, 1, 'R');
$pdf->SetFont('Times', 'B', 14);
$pdf->Cell(205, 5, strtoupper(utf8_decode($field_nomina['TituloBoleta'])), 0, 1, 'C');
*/




$pdf->AddPage();


 $sql_personas = "SELECT
mp.CodPersona
FROM
mastpersonas AS mp
INNER JOIN pr_tiponominaempleadoconcepto AS prt ON mp.CodPersona = prt.CodPersona
 WHERE CodOrganismo = '".$organismo."' AND Periodo = '".$periodo."' AND CodTipoNom = '".$nomina."' AND CodTipoProceso = '".$ftproceso."'
 
GROUP BY mp.CodPersona
 ";
$query_empleados= mysql_query($sql_personas) or die ($sql_personas.mysql_error());


//	Obtengo los empleados seleccionados
//$dato = explode("|:|", $empleados);

$emp = 0;


//	Imprimo los datos
//foreach ($dato as $persona) {
while ($row = mysql_fetch_array($query_empleados)) {	

  $persona = $row['CodPersona'];
	//	Consulta para obtener el resto de los datos
	$sql = "SELECT CodPersona 
			FROM pr_tiponominaempleado 
			WHERE 
				Periodo = '".$periodo."' AND 
				CodOrganismo = '".$organismo."' AND 
				CodTipoProceso = '".$ftproceso."' AND 
				CodTipoNom = '".$nomina."' AND
				CodPersona = '".$persona."'";
	$query_valido = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_valido) != 0) {
		$emp++;
		$hoy = date("d/m/Y");
		
		$y = $pdf->GetY() + 2; 
		if ($y > 210) {
			
		//	$pdf->SetY(140)
			$pdf->AddPage(); 
	    $pdf->SetY(5);
	    //	$pdf->Cell(23, 5, 'Cargo: ', 0, 0, 'L');
	    }	
		
		else 
		{
		$pdf->SetY($y);
	   // if ($pdf->GetY() >120) $pdf->SetY(150);
	     }
		
		
		
		//	Consulta para obtener el resto de los datos
		$sql = "SELECT 
						mp.Ndocumento, 
						me.Fingreso, 
						me.SueldoActual, 
						tn.TituloBoleta AS Nomina,
						bp.Ncuenta,
						tne.TotalIngresos,
						tne.TotalEgresos,
						tne.TotalPatronales, 
						md.Dependencia, 
						me.CodEmpleado, 
						mp.NomCompleto, 
						rp.DescripCargo,
						rbp.CodBeneficiario,
						rbp.NroDocumento,
						rbp.NombreCompleto,
					    rh_nivelsalarial.SueldoPromedio
				FROM 
						mastempleado me 
						INNER JOIN rh_puestos rp ON (me.CodCargo = rp.CodCargo)
						INNER JOIN mastdependencias md ON (me.CodDependencia = md.CodDependencia) 
						INNER JOIN mastpersonas mp ON (me.CodPersona = mp.CodPersona)
						INNER JOIN tiponomina tn ON (me.CodTipoNom = tn.CodTipoNom)
						LEFT JOIN bancopersona bp ON (me.CodPersona = bp.CodPersona AND bp.FlagPrincipal = 'S')
						LEFT JOIN rh_beneficiariopension rbp ON (me.CodPersona = rbp.CodPersona)
						INNER JOIN pr_tiponominaempleado tne ON (me.CodPersona = tne.CodPersona AND tne.Periodo = '".$periodo."' AND tne.CodOrganismo = '".$organismo."' AND tne.CodTipoProceso = '".$ftproceso."' AND tne.CodTipoNom = '".$nomina."')
						INNER JOIN rh_nivelsalarial ON rp.CategoriaCargo = rh_nivelsalarial.CategoriaCargo AND rp.Grado = rh_nivelsalarial.Grado
				
				WHERE me.CodPersona = '".$persona."'";
		$query_resto = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_resto) != 0) $field_resto = mysql_fetch_array($query_resto);
		if ($field_resto['CodBeneficiario'] != "") {
			$ndoc = number_format($field_resto['NroDocumento'], 0, '', '.');
			$nomcompleto = substr($field_resto['NombreCompleto'], 0, 45);
			$codempleado = $field_resto['CodBeneficiario'];
			$cargo = "Beneficiario Pensión por Sobreviviente";
			$fingreso = "";
		} else {
			$ndoc = number_format($field_resto['Ndocumento'], 0, '', '.');
			$nomcompleto = substr($field_resto['NomCompleto'], 0, 45);
			$codempleado = $field_resto['CodEmpleado'];
			if ($nomina == "04") $cargo = "Pensión por Inválidez";
			elseif ($nomina == "03") $cargo = "Jubilado";
			else $cargo = substr($field_resto['DescripCargo'], 0, 45);
			list($a, $m, $d)=SPLIT( '[-./]', $field_resto['Fingreso']); $fingreso = "$d/$m/$a";
		}
		$total_ingresos = number_format($field_resto['TotalIngresos'], 2, ',', '.');
		$total_egresos = number_format($field_resto['TotalEgresos'], 2, ',', '.');
		$total_patronales = number_format($field_resto['TotalPatronales'], 2, ',', '.');
		$total = $field_resto['TotalIngresos'] - $field_resto['TotalEgresos'];
		$total_neto = number_format($total, 2, ',', '.');
	        $total_neto_reporte =  $total_neto_reporte+  $field_resto['TotalIngresos'] - $field_resto['TotalEgresos'];











////////////////////////////////////////////////	
$pdf->SetDrawColor(255, 255, 255); 
$pdf->SetFillColor(255, 255, 255); 
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Times', 'B', 12);
$pdf->SetWidths(array(210));
$pdf->SetAligns(array('R'));

//$pdf->Row(array('Pagina: '.$pdf->PageNo().'/{nb}'));

$pdf->SetFont('Times', 'B', 14);
$pdf->Ln(1);
 $pdf->Cell(150, 8,  'Contraloría del Estado Monagas', 0, 1, 'L');
$pdf->SetFont('Times', 'B', 10);	
$pdf->Ln(3);
$pdf->Cell(190, 5, 'Fecha de Emision:'.$pdf->getFechaEmision(), 0, 1, 'R');
$pdf->Ln(5);
$pdf->SetDrawColor(0, 0, 0); 
$pdf->SetFillColor(255, 255, 255); 
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Times', 'B', 11);
$pdf->SetWidths(array(70,65,60));
$pdf->SetAligns(array('L','L','L','L'));
$pdf->Row(array('Nomina','Nombre del Proceso','Periodo'));
$pdf->SetFont('Times', 'B', 10);	
$pdf->Row(array($pdf->getNomina(), $pdf->getNombreProceso(), $pdf->getDesde().' A: '.$pdf->getHasta()));
$pdf->Ln(1);	
///////////////////////////////////////////////

		$pdf->SetFont('Times', 'B', 11);
		
		
		
		
		////////////////////////////////////////////
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetWidths(array(23,30,110,30));
		$pdf->SetAligns(array('L','L','L','L'));
		$pdf->Row(array('Código','Cédula','Empleado','Fecha Ing.'));
		$pdf->Row(array($codempleado,$ndoc,utf8_decode($nomcompleto),$fingreso));
		
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetWidths(array(23,140));
		$pdf->SetAligns(array('L','L'));
		$pdf->Row(array('Cargo:',utf8_decode($cargo)));
		$pdf->SetWidths(array(55,120));
		$pdf->SetAligns(array('L','L'));
		$pdf->Row(array('Sueldo Basico Mensual:',$field_resto['SueldoPromedio']));
		//////////////////////////////////////////////
		$dependencia = substr(utf8_decode($field_resto['Dependencia']), 0, 45);
		
		/*$pdf->Cell(20, 5, utf8_decode('Código'), 0, 0, 'L'); $pdf->Cell(3, 5);
		$pdf->Cell(30, 5, utf8_decode('Cédula'), 0, 0, 'L'); $pdf->Cell(3, 5);
		$pdf->Cell(115, 5, 'Empleado', 0, 0, 'L'); $pdf->Cell(3, 5);
		$pdf->Cell(30, 5, 'Fecha Ing.', 0, 1, 'L');
		$pdf->Cell(20, 5, $codempleado, 0, 0, 'L'); $pdf->Cell(3, 5);
		$pdf->Cell(30, 5, $ndoc, 0, 0, 'L'); $pdf->Cell(3, 5);
		$pdf->Cell(115, 5, utf8_decode($nomcompleto), 0, 0, 'L'); $pdf->Cell(3, 5);
		$pdf->Cell(30, 5, $fingreso, 0, 1, 'L');
		$pdf->Cell(23, 5, 'Cargo: ', 0, 0, 'L');
		$pdf->Cell(185, 5, utf8_decode($cargo), 0, 1, 'L');*/
		$pdf->Ln(2);
		
		//	Imprimo los conceptos
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Times', 'B', 11);
		$y = $pdf->GetY(); $pdf->Rect(10, $y, 195, 0.1);
		$pdf->Cell(104, 5, 'A S I G N A C I O N E S', 0, 0, 'C');	$pdf->Cell(104, 5, 'D E D U C C I O N E S', 0, 1, 'C');
		$y = $pdf->GetY(); $pdf->Rect(10, $y, 195, 0.1);
		$pdf->Ln(1);
		$y = $pdf->GetY();
		
		$yi = $y; $yd = $y;
		$sql = "SELECT 
						tnec.Monto, 
						tnec.Cantidad, 
						tnec.Saldo, 
						c.CodConcepto,
						c.TextoImpresion AS NomConcepto, 
						c.Tipo 
				FROM 
						pr_tiponominaempleadoconcepto tnec 
						INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto) 
				WHERE 
						tnec.CodPersona = '".$persona."' AND 
						tnec.Periodo = '".$periodo."' AND 
						tnec.CodOrganismo = '".$organismo."' AND 
						tnec.CodTipoNom = '".$nomina."' AND 
						tnec.CodTipoProceso = '".$ftproceso."' 
				ORDER BY 
						c.Tipo DESC, c.PlanillaOrden ASC";
		$query_conceptos=mysql_query($sql) or die ($sql.mysql_error()); $contador_conceptos = 0; $linead = 0; $lineai = 0;
		while ($field_conceptos = mysql_fetch_array($query_conceptos)) {
			
			$concepto = substr($field_conceptos['NomConcepto'], 0, 24);
			$monto = number_format($field_conceptos['Monto'], 2, ',', '.');
			$cantidad = number_format($field_conceptos['Cantidad'], 0, '', '.'); if ($cantidad == 0) $cantidad = "";
			
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Times', 'B', 11);
			$pdf->SetWidths(array(70, 25, 1));
			$pdf->SetAligns(array('L', 'R', 'R'));
					
			if ($field_conceptos['Tipo'] == "I") {
				$pdf->SetXY(10, $yi); $pdf->Row(array(utf8_decode($concepto), $monto, ''));
				$yi += 5;
				$lineai++;
			} 
			elseif ($field_conceptos['Tipo'] == "D") {
				$pdf->SetXY(107, $yd); $pdf->Row(array(utf8_decode($concepto), $monto, ''));
				$yd += 5;
				$linead++;
			}
		}
		
		if ($lineai > $linead) $contador_conceptos = $lineai; else $contador_conceptos = $linead;
		
		if ($yi > $yd) $y = $yi + 2; else $y = $yd + 2;
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->Rect(10, $y, 195, 0.1);
		$pdf->Ln(1);
		$pdf->SetFont('Times', 'B', 10);
		$pdf->SetY($y);
		$pdf->Cell(70, 5, 'TOTAL ASIGNACIONES', 0, 0, 'L'); $pdf->Cell(25, 5, $total_ingresos, 0, 0, 'R'); $pdf->Cell(1, 5);
		$pdf->Cell(70, 5, 'TOTAL DEDUCCIONES', 0, 0, 'L'); $pdf->Cell(25, 5, $total_egresos, 0, 1, 'R'); 
		$pdf->Cell(97, 5);	
		$pdf->Cell(70, 5, 'TOTAL A PAGAR', 0, 0, 'L'); $pdf->Cell(25, 5, $total_neto, 0, 1, 'R');
	
	      
		$pdf->SetFont('Times', 'B', 10);
		$pdf->Ln(5); 
		$pdf->SetX(20); $pdf->Cell(80, 5, '____________________', 0, 1, 'C');
		$pdf->SetX(20); $pdf->Cell(80, 5, 'RECIBI CONFORME', 0, 0, 'C');$pdf->Ln(4); 
		$pdf->SetX(43.5); $pdf->Cell(50, 5, 'C.I:', 0, 0, 'L');
		
	
		
	   if ($pdf->GetY() <140) { $pdf->SetY(150); 	$pdf->Ln(20); }
		$total_ingresos=0;
		$total_egresos=0;
		$total_neto=0;
	}
}

//$pdf->Cell(70, 5, 'TOTAL NETO A PAGAR: '.number_format( $total_neto_reporte, 2, ',', '.'), 0, 0, 'L'); ;

//	--------------------------------------------------

$pdf->Output();
?>
