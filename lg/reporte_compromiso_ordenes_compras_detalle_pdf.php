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
		$this->SetXY(5, 20); $this->Cell(195, 5, utf8_decode('Ordenes de Compras Comprometidas'), 0, 1, 'C');
		$this->Ln(5);
		
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 7);
		$this->SetWidths(array(32, 62, 22, 62,27));
		$this->SetAligns(array('C', 'C', 'C', 'C','C'));
		
		$this->Row(array(utf8_decode('Cód. Partida'),
						 utf8_decode('Partida Presupuestaria'),
						 utf8_decode('Estado'),
						 'Periodo',
						 'Monto'));
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
$filtro2 = "";
if ($forganismo != "") $filtro.=" AND (oc.CodOrganismo = '".$forganismo."')";
if ($fclasificacion != "") $filtro.=" AND (oc.Clasificacion = '".$fclasificacion."')";
if ($fproveedor != "") $filtro.=" AND (oc.CodProveedor = '".$fproveedor."')";
if ($fedoreg != "") $filtro.=" AND (oc.Estado = '".$fedoreg."')";
if ($fpreparaciond != "") $filtro2.=" AND ( dcom.Periodo >= '".$fpreparaciond."')";
if ($fpreparacionh != "") $filtro2.=" AND ( dcom.Periodo <= '".$fpreparacionh."')";
if ($fmontod != "") $filtro.=" AND (oc.MontoTotal >= ".setNumero($fmontod).")";
if ($fmontoh != "") $filtro.=" AND (oc.MontoTotal <= ".setNumero($fmontoh).")";
/*if ($fatraso != "") $filtro.=" AND (DATEDIFF(NOW(), oc.FechaPrometida) >= '".$fatraso."')";
if ($fedodet != "") $filtro.=" AND (ocd.Estado = '".$fedodet."')";
if ($fcoditem != "") $filtro.=" AND (oc.CodItem = '".$fcoditem."')";
if ($falmacen != "") $filtro.=" AND (oc.CodAlmacen = '".$falmacen."')";
if ($fcodcommodity != "") $filtro.=" AND (ocd.CommoditySub = '".$fcodcommodity."')";*/
//---------------------------------------------------

/*oc.CodOrganismo,
			oc.NroOrden,
			oc.CodProveedor,
			oc.NomProveedor,
			oc.FechaPreparacion,
			oc.MontoTotal AS TotalOrden,
			oc.Estado AS EstadoMast,
			(SELECT SUM(oc1.MontoTotal)
			 FROM lg_ordencompra oc1
			 WHERE oc1.CodProveedor = oc.CodProveedor
			 GROUP BY CodProveedor) AS TotalProveedor,
			DATEDIFF(NOW(), oc.FechaPrometida) AS DiasAtrasados,
			ocd.CodItem,
			ocd.CommoditySub,
			ocd.Descripcion,
			ocd.CodUnidad,
			ocd.CantidadPedida,
			ocd.CantidadRecibida,
			ocd.FechaPrometida,
			ocd.Estado AS EstadoDetalle*/
			
			$sql = "SELECT oc.CodOrganismo, oc.Clasificacion, oc.Estado, oc.CodProveedor, oc.NomProveedor, oc.MontoTotal, oc.NroOrden, oc.FechaAprobacion, oc.MontoBruto, oc.MontoIGV
			FROM lg_ordencompra oc
			WHERE 1 
			$filtro
			ORDER BY oc.CodOrganismo, oc.CodProveedor, oc.NroOrden";

			$resp = $objConexion->consultar($sql,'matriz');

			$pdf->SetDrawColor(0, 0, 0);
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 7);
			
				
			for($i = 0; $i < count($resp); $i++)
			{
				$sql1 = "SELECT DISTINCT pa.denominacion, dcom.cod_partida, dcom.Monto, dcom.Periodo, dcom.Origen
						FROM lg_ordencompra oc
						JOIN lg_distribucioncompromisos AS dcom ON ( oc.CodOrganismo = dcom.CodOrganismo
						AND oc.CodProveedor = dcom.CodProveedor
						AND dcom.NroDocumento = oc.NroOrden
						AND oc.NroOrden = '".$resp[$i]['NroOrden']."' )
						JOIN pv_partida pa ON ( pa.cod_partida = dcom.cod_partida )
						WHERE 1 $filtro2
						AND dcom.origen = 'OC'
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


				if (count($resp1) <= 0)
				{
					
					break;	
				}
				$pdf->SetWidths(array(205));
				$pdf->SetAligns(array('L'));
				$pdf->Row(array('Proveedor: '.$resp[$i]['CodProveedor'].' '.$resp[$i]['NomProveedor'].' Total proveedor: '.$resp[$i]['MontoTotal']));
				$pdf->SetAligns(array('C'));
				$pdf->Row(array('Nro Orden: '.$resp[$i]['NroOrden'].'						'.$resp[$i]['FechaAprobacion'].'						'.$estado.'						Monto Bruto: '.$resp[$i]['MontoBruto'].'						IVA: '.$resp[$i]['MontoIGV'].'						Total: '.$resp[$i]['MontoTotal']));


				for($j = 0; $j < count($resp1); $j++)
				{
					
					
					$pdf->SetWidths(array(32, 62, 22, 62,27));
					$pdf->SetAligns(array('C', 'C', 'C', 'C','C'));
			
					$pdf->Row(array($resp1[$j]['cod_partida'],
							utf8_decode($resp1[$j]['denominacion']),
							 $resp1[$j]['Origen'],
							 $resp1[$j]['Periodo'],
							 $resp1[$j]['Monto']));
							 
					/*$pdf->Row(array($resp1[]['cod_partida'],
							utf8_decode($resp1[$i+1]['denominacion']),
							 $resp1[$i+1]['CodTipoDocumento'],
							 $resp1[$i+1]['Periodo'],
							 $resp1[$i+1]['MontoOC']));*/
					//$i = $i+1;
	//				$pdf->Ln(1);
	//				$pdf->Cell(235, 5,'Proveedor: '.$resp[$i]['CodProveedor'].' '.$resp[$i]['NomProveedor'].' Total proveedor: '.$resp[$i]['MontoTotal'], 0, 1, 'L');
				}
			}
			
/*$query = mysql_query($sql) or die ($sql.mysql_error());	$i=0;
while ($field = mysql_fetch_array($query)) {	$i++;
	$estado = printValores("ESTADO-ORDENES", $field['EstadoMast']);
	$edodet = printValores("ESTADO-ORDENES-DET", $field['EstadoDetalle']);
	if ($field['CodItem'] != "") $codigo = $field['CodItem']; else $codigo = $field['CommoditySub'];

	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetDrawColor(255, 255, 255);*/
	
	
//}
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
