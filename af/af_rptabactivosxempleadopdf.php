<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');
require('fphp.php');
connect(); 
mysql_query("SET NAMES 'utf8'");
extract ($_POST);
extract ($_GET);

/// ----------------------------------------------------
//---------------------------------------------------
$filtro=strtr($filtro, "*", "'");

class PDF extends FPDF
{
//Page header
function Header(){
    
	global $Periodo, $filtro;
	$this->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$this->SetFont('Arial', 'B', 9);
	$this->SetXY(20, 10); $this->Cell(70, 5,utf8_decode('República Bolivariana de Venezuela'), 0, 1, 'L');
	$this->SetXY(20, 14); $this->Cell(70, 5,utf8_decode('Contraloría del Estado Monagas'), 0, 1, 'L'); 
	$this->Ln(4);
	
	$this->SetXY(220, 10);$this->Cell(11,5,'Fecha: ',0,0,'L');$this->Cell(10,5,date('d/m/Y'),0,1,'');
	$this->SetXY(220, 15);$this->Cell(11,5,utf8_decode('Página:'),0,1,'');
	//$this->SetXY(183, 20);$this->Cell(7,5,utf8_decode('Año:'),0,0,'');$this->Cell(6,5,date('Y'),0,1,'L');
						   
	list($fano, $fmes) = SPLIT('[-]',$Periodo);
    switch ($fmes) {
		case "01": $mes = Enero; break;  
		case "02": $mes = Febrero;break; 
		case "03": $mes = Marzo;break;   
		case "04": $mes = Abril;break;   
		case "05": $mes = Mayo;break;    
		case "06": $mes = Junio;break;
		case "07": $mes = Julio; break;
		case "08": $mes = Agosto; break;
		case "09": $mes = Septiembre; break;
		case "10": $mes = Octubre; break;
		case "11": $mes = Noviembre; break;
		case "12": $mes = Diciembre; break;
    }
	//echo $fmes;					   
	/*$this->SetFont('Arial', 'B', 10);
	$this->Cell(105, 10, '', 0, 0, 'C');
	$this->Cell(47, 10, utf8_decode('Ejecución Presupuestaria'), 0, 0, 'C');
    $this->Cell(13, 10, $mes, 0, 0, 'C'); $this->Cell(13, 10, $fano, 0, 1, 'C');*/
	///// PRUEBA ***********
	$this->SetFont('Arial', 'B', 8);
	
	/*$sql = "select a.* from af_activo a where CodOrganismo<>'' $filtro"; //echo $sql;
	$qry = mysql_query($sql) or die ($sql.mysql_error());
	$field = mysql_fetch_array($qry); */
	
	$scon01 = "select 
					 CodDependencia, Dependencia, CodPersona 
				from 
				     mastdependencias a 
			   where 
			   	     CodDependencia='".$field['CodDependencia']."'";
	$qcon01 = mysql_query($scon01) or die ($scon01.mysql_error());
	$fcon01 = mysql_fetch_array($qcon01);
	
	$scon02 = "select 
					 a.*,
					 b.DescripCargo,
					 c.NomCompleto,
					 c.CodPersona 
				 from 
				     rh_empleadonivelacion a 
					 inner join rh_puestos b on (b.CodCargo=a.CodCargo) 
					 inner join mastpersonas c on (c.CodPersona=a.CodPersona)
				where 
				     a.Secuencia=(select max(Secuencia) from rh_empleadonivelacion where CodPersona='".$fcon01['CodPersona']."') and 
					 a.CodPersona = '".$fcon01['CodPersona']."'";
	 $qcon02 = mysql_query($scon02) or die ($scon02.mysql_error());
	 $fcon02 = mysql_fetch_array($qcon02);
	
	$this->SetFont('Arial', 'B', 9);
	$this->Cell(240, 3, utf8_decode('LISTA DE ACTIVOS ASIGNADOS POR PERSONA').$Periodo, 0, 1, 'C');$this->Ln(4);
	
	$this->SetFont('Arial', '', 8);
	  $this->Cell(130,4,'________________________________________________________________________________________________________________________________________________________________________________',0,1,'L');
	$this->SetFont('Arial','B',8);	
	$this->SetDrawColor(255, 255, 255);
	//$this->SetDrawColor(0, 0, 0);
	$this->SetFillColor(255, 255, 255);
	$this->SetWidths(array(20,60,30,60,60,25,25));
	$this->SetAligns(array('C','C','C','C','C','C','C'));
	$this->Row(array('Activo',utf8_decode('Descripción'),utf8_decode('Cód. Barras'),utf8_decode('Ubicación'),'Centro de Costos', 'Naturaleza del Activo', 'Estado'));
	//$this->SetFillColor(255, 255, 255);
	
	$this->SetFont('Arial', '', 8);
	  $this->Cell(130,4,'________________________________________________________________________________________________________________________________________________________________________________',0,1,'L');
}
//Page footer
function Footer(){
    //Position at 1.5 cm from bottom
    $this->SetXY(187,13);
    //Arial italic 8
    $this->SetFont('Arial','B',8);
    //Page number
    $this->Cell(0,9,' '.$this->PageNo().'/{nb}',0,0,'C');
}
}
//Instanciation of inherited class
$pdf=new PDF('L','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

$scon = "select 
				a.*,
				b.NomCompleto,
				c.Descripcion as DecpUbicacion,
				d.Descripcion as DecpCentroCosto
		   from 
				af_activo a
				inner join mastpersonas b on (b.CodPersona = a.EmpleadoUsuario)
				inner join af_ubicaciones c on (c.CodUbicacion = a.Ubicacion)
				inner join ac_mastcentrocosto d on (d.CodCentroCosto = a.CentroCosto)
		   where 
				a.CodOrganismo<>'' $filtro 
		  order by 
				a.EmpleadoUsuario"; 
$qcon = mysql_query($scon) or die ($scon.mysql_error());
$rcon = mysql_num_rows($qcon); //echo $r_con_01;


if($rcon!=0)
   for($i=0; $i<$rcon; $i++){
      $fcon = mysql_fetch_array($qcon);
	  if($fcon['Naturaleza']=='AM') $naturaleza = 'Activo Menor'; else $naturaleza = 'Activo Mayor';
	  if($fcon['Estado']=='PE') $estado='Pendiente';else $estado='Activado';
	  
      $filtro2=" and CodDependencia='".$fcon['CodDependencia']."' and Naturaleza='".$fcon['Naturaleza']."' and 
	  			  CentroCosto='".$fcon['CentroCosto']."' and Ubicacion='".$fcon['Ubicacion']."'";
	  
	  if($fcon['EmpleadoUsuario']!=$empleado_usuario){
		 $scon_2 = "select * from af_activo where EmpleadoUsuario='".$fcon['EmpleadoUsuario']."' $filtro2";
		 $qcon_2 = mysql_query($scon_2) or die ($scon2.mysql_error());
		 $rcon_2 = mysql_num_rows($qcon_2);
		 
		 
		$pdf->SetFont('Arial', 'B', 8);  $pdf->Cell(35, 4, 'Usuario Responsable: ' , 0, 0, 'L');
										 $pdf->Cell(80, 4, utf8_decode($fcon['NomCompleto']) , 0, 0, 'L');
										 $pdf->Cell(20, 4, 'Nro. Activos: ' , 0, 0, 'L');
										 $pdf->Cell(20, 4, $rcon_2 , 0, 1, 'L');
		$pdf->SetFont('Arial', '', 8); 
		$pdf->SetDrawColor(255, 255, 255);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetWidths(array(20,60,30,60,60,25,25));
		$pdf->SetAligns(array('C','L','C','L','L','L','C'));
		$pdf->Row(array($fcon['Activo'], utf8_decode($fcon['Descripcion']), $fcon['CodigoBarras'], utf8_decode($fcon['DecpUbicacion']), utf8_decode($fcon['DecpCentroCosto']), $naturaleza, $estado));
		 
		 $empleado_usuario = $fcon['EmpleadoUsuario']; 
	  }else{
		$pdf->SetFont('Arial', '', 8); 
		$pdf->SetDrawColor(255, 255, 255);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetWidths(array(20,60,30,60,60,25,25));
		$pdf->SetAligns(array('C','L','C','L','L','L','C'));
		$pdf->Row(array($fcon['Activo'], utf8_decode($fcon['Descripcion']), $fcon['CodigoBarras'], utf8_decode($fcon['DecpUbicacion']), utf8_decode($fcon['DecpCentroCosto']), $naturaleza, $estado));
	  }
	  
   }
$pdf->Output();
?>  
