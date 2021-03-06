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
		
		$this->Image($_PATHLOGO.'contraloria.jpg', 5, 5, 10, 10);		
		$this->SetFont('Arial', '', 8);
		$this->SetXY(15, 5); $this->Cell(100, 5, $_SESSION['NOMBRE_ORGANISMO_ACTUAL'], 0, 1, 'L');
		$this->SetXY(15, 10); $this->Cell(100, 5, utf8_decode('DIVISIÓN DE ADMINISTRACIÓN Y PRESUPUESTO'), 0, 0, 'L');
		
		$this->SetXY(175, 5); $this->Cell(15, 5, utf8_decode('Fecha: '), 0, 0, 'R'); 
		$this->Cell(60, 5, date("d-m-Y"), 0, 1, 'L');
		$this->SetXY(175, 10); $this->Cell(15, 5, utf8_decode('Página: '), 0, 0, 'R'); 
		$this->Cell(60, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		
		$this->SetFont('Arial', 'B', 9);
		$this->SetXY(5, 20); $this->Cell(195, 5, utf8_decode('Ordenes de Servicios Comprometidas'), 0, 1, 'C');
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
if ($forganismo != "") $filtro.=" AND (os.CodOrganismo = '".$forganismo."')";
if ($fclasificacion != "") $filtro.=" AND (os.Clasificacion = '".$fclasificacion."')";
if ($fproveedor != "") $filtro.=" AND (os.CodProveedor = '".$fproveedor."')";
if ($fedoreg != "") $filtro.=" AND (os.Estado = '".$fedoreg."')";
if ($fpreparaciond != "") $filtro2.=" AND ( dsom.Periodo >= '".$fpreparaciond."')";
if ($fpreparacionh != "") $filtro2.=" AND ( dsom.Periodo <= '".$fpreparacionh."')";
if ($fmontod != "") $filtro.=" AND (os.TotalMontoIva >= ".setNumero($fmontod).")";
if ($fmontoh != "") $filtro.=" AND (os.TotalMontoIva <= ".setNumero($fmontoh).")";
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
			
			$sql = "SELECT os.CodOrganismo, os.Estado, os.CodProveedor, os.NomProveedor, os.TotalMontoIva, os.NroOrden, os.FechaAprobacion, os.MontoOriginal, os.MontoIva, os.MontoNoAfecto
			FROM lg_ordenservicio os
			
			WHERE 1 
			$filtro
			ORDER BY os.CodOrganismo, os.CodProveedor, os.NroOrden";


			$resp = $objConexion->consultar($sql,'matriz');

			$pdf->SetDrawColor(0, 0, 0);
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 7);
			
				
			for($i = 0; $i < count($resp); $i++)
			{
				$sql1 = "SELECT DISTINCT pa.denominacion, dsom.cod_partida, dsom.Monto, dsom.Periodo, dsom.Origen
						FROM lg_ordenservicio os
						JOIN lg_distribucioncompromisos AS dsom ON ( os.CodOrganismo = dsom.CodOrganismo
						AND os.CodProveedor = dsom.CodProveedor
						AND dsom.NroDocumento = os.NroOrden
						AND os.NroOrden = '".$resp[$i]['NroOrden']."' )
						JOIN pv_partida pa ON ( pa.cod_partida = dsom.cod_partida )
						WHERE 1 $filtro2
						AND dsom.origen = 'OS'
				$filtro";
				
				$resp1 = $objConexion->consultar($sql1,'matriz');
				
				if (count($resp1) <= 0)
				{
					
					break;	
				}
				$pdf->SetWidths(array(205));
				$pdf->SetAligns(array('L'));
				$pdf->Row(array('Proveedor: '.$resp[$i]['CodProveedor'].' '.$resp[$i]['NomProveedor'].' Total proveedor: '.$resp[$i]['TotalMontoIva']));
				$pdf->SetAligns(array('C'));
				//$pdf->Row(array('Nro Orden: '.$resp[$i]['NroOrden'].'						'.$resp[$i]['FechaAprobacion'].'						'.$estado.'						Monto Bruto: '.$resp[$i]['MontoBruto'].'						IVA: '.$resp[$i]['MontoIGV'].'						Total: '.$resp[$i]['MontoTotal']));
				$pdf->Row(array('Nro Orden: '.$resp[$i]['NroOrden'].'						'.$resp[$i]['FechaAprobacion'].'						'.$estado.'						Monto Afecto: '.$resp[$i]['MontoOriginal'].'						Monto No Afecto: '.$resp[$i]['MontoNoAfecto'].'						IVA: '.$resp[$i]['MontoIva'].'						Total: '.$resp[$i]['TotalMontoIva']));


				for($j = 0; $j < count($resp1); $j++)
				{
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
