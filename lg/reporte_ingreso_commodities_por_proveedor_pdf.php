<?php
require('fpdf.php');
require('fphp_lg.php');

include_once ("../clases/MySQL.php");
include_once("../comunes/objConexion.php");
ob_end_clean();
connect();
extract($_POST);
extract($_GET);

define('fecha1',$fpreparacionh);
define('fecha2',$fpreparaciond);
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
		$this->SetXY(5, 20); $this->Cell(195, 5, utf8_decode('Recepciones por Proveedor'), 0, 1, 'C');
//		$this->Ln(5);
		$this->SetFont('Arial', '', 6);
		$this->SetXY(5, 23); $this->Cell(195, 5, utf8_decode('Entre '.fecha2.' y '.fecha1), 0, 1, 'L');
		$this->Ln(3);
		//$this->Line(5,28,207,28);
		//$this->Ln();
		//$this->Line(5,41,207,41);
		//$this->Ln(1);
		
		$this->SetDrawColor(255, 255, 255);
		$this->SetFillColor(255, 255, 255);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 7);
		$this->SetWidths(array(21, 30, 28,70,15,15,26));
		$this->SetAligns(array('C', 'C', 'C', 'C','C', 'C', 'C', 'C'));
		
		$this->Row(array(utf8_decode('Fecha Mov.'),
						 utf8_decode('Documento'),
						 'Commodity',
						 utf8_decode('Descripción'),
						 'Und.',
						 'Cantidad',
						 'Almacen'));
		//$this->Ln(1);
		/*$this->SetAligns(array('R', 'R'));
		$this->SetWidths(array(103,102));
		$this->Row(array(utf8_decode('Fecha Aprob.'),
						 utf8_decode('P.U.Local')));*/
		$this->Ln(3);
			
	}
	
	//	Pie de página.
	function Footer() {
		
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('P', 'mm', 'Letter');
//$pdf->Header($fpreparacionh,$fpreparaciond);
$pdf->AliasNbPages();
$pdf->SetMargins(5, 1, 1);
$pdf->SetAutoPageBreak(5, 1);
$pdf->AddPage();
//---------------------------------------------------
$filtro = "";
$filtro1 = "";
$filtro2 = "";
if ($forganismo != "") $filtro.=" AND (C.CodOrganismo = '".$forganismo."')";
//if ($fclasificacion != "") $filtro.=" AND (os.Clasificacion = '".$fclasificacion."')";
if ($fproveedor != "") $filtro2.=" AND (C.CodProveedor = '".$fproveedor."')";

//if ($fedoreg != "") $filtro.=" AND (os.Estado = '".$fedoreg."')";

	if ($fpreparaciond != "")
	{
		$filtro1.=" AND (A.FechaDocumento >= '".formatFechaAMD($fpreparaciond)."')";
		
	} else {
		
		echo '<script language="javascript">
				alert(\'Debe intoducir la fecha de inicio de aprobación\');
			</script>';
			exit;
	}

	if ($fpreparacionh != "") 
	{
		$filtro1.=" AND (A.FechaDocumento <= '".formatFechaAMD($fpreparacionh)."')";
		
	} else {
		
		echo '<script language="javascript">
				alert(\'Debe intoducir la fecha de inicio de aprobación\');
			</script>';
		exit;
	}
		
if ($falmacen != "") $filtro.=" AND (A.CodAlmacen = '".$falmacen."')";

//if ($fmontod != "") $filtro.=" AND (os.TotalMontoIva >= ".setNumero($fmontod).")";
//if ($fmontoh != "") $filtro.=" AND (os.TotalMontoIva <= ".setNumero($fmontoh).")";
/*if ($fatraso != "") $filtro.=" AND (DATEDIFF(NOW(), os.FechaPrometida) >= '".$fatraso."')";
if ($fedodet != "") $filtro.=" AND (ocd.Estado = '".$fedodet."')";
if ($fcoditem != "") $filtro.=" AND (oc.CodItem = '".$fcoditem."')";
if ($falmacen != "") $filtro.=" AND (oc.CodAlmacen = '".$falmacen."')";
if ($fcodcommodity != "") $filtro.=" AND (ocd.CommoditySub = '".$fcodcommodity."')";*/
//---------------------------------------------------

//$pdf->SetXY(5, 0); $pdf->Cell(195, 5, utf8_decode('Recepciones por Proveedor'), 0, 1, 'L');			
			
			$sql = "select distinct A.CodProveedor, A.NomProveedor
					from lg_ordencompra as A where 1 $filtro2";


			$resp = $objConexion->consultar($sql,'matriz');

			/*$pdf->SetDrawColor(0, 0, 0);
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 7);*/
			
				
			for($i = 0; $i < count($resp); $i++)
			{
				$sql1 = "select C.CodProveedor,A.CodDocumento,C.FechaAprobacion, C.NroOrden, B.Secuencia, A.Periodo, A.FechaDocumento,
						A.CodAlmacen, A.Comentarios, B.CodOrganismo, B.NroDocumento, B.Secuencia, B.CommoditySub,
						B.Descripcion, B.CodUnidad, B.Cantidad, B.ReferenciaCodDocumento, B.ReferenciaNroDocumento, 
						B.ReferenciaSecuencia, B.PrecioUnit
						from lg_commoditytransaccion as A 
						join lg_commoditytransacciondetalle as B on A.NroDocumento=B.NroDocumento 
						
						join lg_ordencompra as C on B.ReferenciaNroDocumento = C.NroOrden
						
						where A.CodDocumento='NI'  and  A.CodTransaccion='RCD'  and C.CodProveedor='".$resp[$i]['CodProveedor']."'
						$filtro1 $filtro";
				
				$resp1 = $objConexion->consultar($sql1,'matriz');
				
				$pdf->SetDrawColor(0, 0, 0);
			
				$pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 8);
			
				$pdf->SetDrawColor(255, 255, 255);
				$pdf->SetFillColor(255, 255, 255);
				$pdf->SetWidths(array(205));
				$pdf->SetAligns(array('L'));
				
				if(count($resp1) > 0)
				{
					$pdf->Row(array('Proveedor: '.$resp[$i]['CodProveedor'].' '.utf8_decode($resp[$i]['NomProveedor'])));
//					$pdf->Ln(3);
				} 
				/*$pdf->SetAligns(array('C'));
				$pdf->Row(array('Nro Orden: '.$resp[$i]['NroOrden'].'						'.$resp[$i]['FechaAprobacion'].'						'.$estado.'						Monto Afecto: '.$resp[$i]['MontoOriginal'].'						Monto No Afecto: '.$resp[$i]['MontoNoAfecto'].'						IVA: '.$resp[$i]['MontoIva'].'						Total: '.$resp[$i]['TotalMontoIva']));*/
				

				for($j = 0; $j < count($resp1); $j++)
				{
					
					
					
					/*$pdf->SetWidths(array(32, 62, 22, 62,27));
					$pdf->SetAligns(array('C', 'C', 'C', 'C','C'));
			
					$pdf->Row(array($resp1[$j]['cod_partida'],
						utf8_decode($resp1[$j]['denominacion']),
						 $resp1[$j]['CodCuenta'],
						 utf8_decode($resp1[$j]['Descripcion']),
						 $resp1[$j]['Monto']));
						 */
				
					$pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', '', 7);
					$pdf->SetDrawColor(255, 255, 255);
					$pdf->SetFillColor(255, 255, 255);
					$pdf->SetWidths(array(21, 30, 28,70,15,15,26));
					$pdf->SetAligns(array('C', 'C', 'C', 'C','C', 'C', 'C','C'));
					
/*					if($resp1[$j]['Descripcion'] == '')
					{
						$descripcion = '';
					}*/
					
					$pdf->Row(array(utf8_decode($resp1[$j]['FechaDocumento']),
									 utf8_decode($resp1[$j]['CodDocumento']."-".$resp1[$j]['NroDocumento']),
									 $resp1[$j]['CommoditySub'],
									 utf8_decode($resp1[$j]['Descripcion']),
									 $resp1[$j]['CodUnidad'],
									 $resp1[$j]['Cantidad'],
									 $resp1[$j]['CodAlmacen']));


					
//					$pdf->Ln(3);
					/*$pdf->SetAligns(array('R', 'R'));
					$pdf->SetWidths(array(103,102));
					$pdf->Row(array(utf8_decode($resp1[$j]['FechaAprobacion']),
									$resp1[$j]['PrecioUnit']));
*/
					$pdf->Ln(1);
							 
					
				}
				
//				$pdf->Row(array('Total: '.$resp[$i]['CodProveedor'].' '.$resp[$i]['NomProveedor']));
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
