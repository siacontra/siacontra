<?php
require('fpdf.php');
require('fphp_lg.php');

include_once ("../clases/MySQL.php");
include_once("../comunes/objConexion.php");
ob_end_clean();
connect();
extract($_POST);
extract($_GET);
//---------------------------------------------------
$_PATHLOGO = getParametro("PATHLOGO");
//---------------------------------------------------

//---------------------------------------------------
class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PATHLOGO;
		
		$this->Image($_PATHLOGO.'encabezadopdf.jpg', 5, 5, 12, 12);	
		
	    $this->SetFont('Arial', '', 6);
		$this->SetXY(15, 5); $this->Cell(100, 5, "   ".$_SESSION['NOMBRE_ORGANISMO_ACTUAL'], 0, 1, 'L');
		$this->SetXY(15, 8); $this->Cell(100, 5, utf8_decode('   DIRECCIÓN DE ADMINISTRACIÓN'), 0, 1, 'L');
		$this->SetXY(15, 11); $this->Cell(100, 5, utf8_decode('   DIVISIÓN DE COMPRAS'), 0, 1, 'L');
		
		$this->SetXY(170, 5); $this->Cell(12, 5, utf8_decode('Fecha: '), 0, 0, 'L'); 
		$this->Cell(60, 5, date("d-m-Y"), 0, 1, 'L');
		$this->SetXY(170, 10); $this->Cell(12, 5, utf8_decode('Página: '), 0, 0, 'L'); 
		$this->Cell(60, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		
		$this->SetFont('Arial', 'B', 9);
		$this->SetXY(5, 20); $this->Cell(195, 5, utf8_decode('Ordenes de Compras Distribución'), 0, 1, 'C');
		$this->Ln(5);
		
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 6);
		$this->SetWidths(array(18,35,14,18,14,20,25,14,14,14,14,14));
		$this->SetAligns(array('C','C','C','C','C','C','C','C','C','C','C','C'));
		
		$this->Row(array(utf8_decode('Cód.Proveedor'),
			             utf8_decode('Proveedor'),
			             utf8_decode('Cód. Partida'),
						 utf8_decode('Partida Presupuestaria'),
						 utf8_decode('Cód. Cuenta'),
						 'Cuenta Contable',
						 utf8_decode('Nro. de Orden'),
						 'Estatus',
						 utf8_decode('Monto Bruto'),
						 utf8_decode('IVA'),
						 utf8_decode('Total'),
						 ));
		//$this->Ln(1);
						
	}
	
	//	Pie de página.
	function Footer() {
		
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(5, 1, 1);
$pdf->SetAutoPageBreak(5, 1);
$pdf->AddPage();
//---------------------------------------------------
$filtro = "";
if ($forganismo != "") $filtro.=" AND (oc.CodOrganismo = '".$forganismo."')";
if ($fclasificacion != "") $filtro.=" AND (oc.Clasificacion = '".$fclasificacion."')";
if ($fproveedor != "") $filtro.=" AND (oc.CodProveedor = '".$fproveedor."')";
if ($fedoreg != "") $filtro.=" AND (oc.Estado = '".$fedoreg."')";
if ($fpreparaciond != "") $filtro.=" AND (oc.FechaAprobacion >= '".formatFechaAMD($fpreparaciond)."')";
if ($fpreparacionh != "") $filtro.=" AND (oc.FechaAprobacion <= '".formatFechaAMD($fpreparacionh)."')";
if ($fmontod != "") $filtro.=" AND (oc.MontoTotal >= ".setNumero($fmontod).")";
if ($fmontoh != "") $filtro.=" AND (oc.MontoTotal <= ".setNumero($fmontoh).")";

//---------------------------------------------------
		
			
			$sql = "SELECT ac.descripcion, pv.denominacion,  oc.CodCuenta, oc.cod_partida, oc.CodOrganismo, oc.Clasificacion, oc.Estado, oc.CodProveedor, oc.NomProveedor, oc.MontoTotal, oc.NroOrden, oc.FechaAprobacion, oc.MontoBruto, oc.MontoIGV, oc.MontoTotal
			FROM lg_ordencompra oc, pv_partida pv, ac_mastplancuenta ac
			WHERE 1 and pv.cod_partida=oc.cod_partida and ac.CodCuenta=oc.CodCuenta 
			$filtro
			ORDER BY oc.CodOrganismo, oc.CodProveedor, oc.NroOrden";


			$resp = $objConexion->consultar($sql,'matriz');

			$pdf->SetDrawColor(0, 0, 0);
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 7);
			
				
			for($i = 0; $i < count($resp); $i++)
			{

				$sql1 = "SELECT DISTINCT pa.denominacion, plc.Descripcion, doc.cod_partida, doc.CodCuenta, doc.Monto
						FROM lg_ordencompra oc, 
						INNER JOIN lg_distribucionoc doc ON ( oc.CodOrganismo = doc.CodOrganismo
						AND oc.NroOrden = doc.NroOrden
						AND oc.NroOrden = '".$resp[$i]['NroOrden']."' )
						JOIN pv_partida pa ON ( pa.cod_partida = doc.cod_partida )
						JOIN ac_mastplancuenta AS plc ON ( plc.CodCuenta = doc.CodCuenta )
						WHERE 1
				$filtro";  
							
				$resp1 = $objConexion->consultar($sql1,'matriz');
				
				if($resp[$i]['Estado'] == 'AP')
				{
					$estado = 'APROBADO';
					
				} else if($resp[$i]['Estado'] == 'RV')
				{
					$estado = 'REVISADO';
					
				} else if($resp[$i]['Estado'] == 'CO')
				{
					$estado = 'COMPLETADO';
				} 
				
	
				$pdf->SetFont('Arial', '', 6);
				$nro=count($resp1);
				$pdf->SetWidths(array(18,35,14,18,14,20,25,14,14,14,14,14));
		        $pdf->SetAligns(array('L','L','L','L','L','L','L','L','L','L','L','L'));
                $pdf->Row(array($resp[$i]['CodProveedor'],$resp[$i]['NomProveedor'], $resp[$i]['cod_partida'], $resp[$i]['denominacion'], $resp[$i]['CodCuenta'], $resp[$i]['descripcion'], $resp[$i]['NroOrden'], $estado, $resp[$i]['MontoBruto'], $resp[$i]['MontoIGV'],$resp[$i]['MontoTotal']));				
			}
			

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
