<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');
require('fphp.php');
connect(); 
extract ($_POST);
extract ($_GET);
global $Periodo;
/// -------------------------------------------------
//---------------------------------------------------
$filtro1=strtr($filtro1, "*", "'");
//---------------------------------------------------
//---------------------------------------------------
//echo $Periodo;
class PDF extends FPDF
{
//Page header
function Header(){
    
	global $Periodo;
        //Cabecera del pdf 
	$this->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$this->SetFont('Arial', 'B', 8);
	$this->SetXY(20, 10); $this->Cell(146, 5,utf8_decode( 'Contraloría del Estado Monagas'), 0, 0, 'L');
	                      $this->Cell(10,5,'Fecha:',0,0,'');$this->Cell(10,5,date('d/m/Y'),0,1,'');
	$this->SetXY(20, 15); $this->Cell(145, 5, utf8_decode('Dirección de Administración y Presupuesto'), 0, 0, 'L');
	                       $this->Cell(10,5,utf8_decode('Página:'),0,1,'');
	$this->SetXY(19, 20); $this->Cell(150, 5, '', 0, 0, 'L');
	                       $this->Cell(7,5,utf8_decode('Año:'),0,0,'L');$this->Cell(6,5,date('Y'),0,1,'L');
						   
	list($fano, $fmes) = SPLIT('[-]', $Periodo);
    switch ($fmes) {
		case "01": $mes = ENERO; break;  
		case "02": $mes = FEBRERO;break; 
		case "03": $mes = MARZO;break;   
		case "04": $mes = ABRIL;break;   
		case "05": $mes = MAYO;break;    
		case "06": $mes = JUNIO;break;
		case "07": $mes = JULIO; break;
		case "08": $mes = AGOSTO; break;
		case "09": $mes = SEPTIEMBRE; break;
		case "10": $mes = OCTUBRE; break;
		case "11": $mes = NOVIEMBRE; break;
		case "12": $mes = DICIEMBRE; break;
    }
	//echo $fmes;
	if($fmes!=0){					   
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(50, 10, '', 0, 0, 'C');
		$this->Cell(48, 10, utf8_decode('LIBRO DIARIO AL MES DE'), 0, 0, 'C');
		$this->Cell(18, 10, $mes, 0, 0, 'C'); $this->Cell(8, 10, utf8_decode('DE'), 0, 0, 'C');
		$this->Cell(8, 10, $fano, 0, 1, 'C');
	}else{
	    $this->SetFont('Arial', 'B', 10);
		$this->Cell(50, 10, '', 0, 0, 'C');
		$this->Cell(35, 10, utf8_decode('LIBRO DIARIO DEL'), 0, 0, 'L');
		$this->Cell(10, 10, date("Y"), 0, 0, 'C'); 
                $this->Cell(16, 10, utf8_decode('A LA FECHA'), 0, 1, 'l');
	}
	
	
	$this->SetFont('Arial', 'B', 7);
	$this->Rect(10,34,195,'','');
	$this->Rect(10,38,195,'','');
	$this->Cell(8, 3, 'LIN', 0, 0, 'C');
        $this->Cell(13, 3,'CUENTA', 0, 0, 'C');
        $this->Cell(15, 3, 'F. VOUCHER', 0, 0, 'L');
	$this->Cell(13, 3, 'PERS.', 0, 0, 'C');
        $this->Cell(15, 3, 'C.COSTOS', 0, 0, 'L');
        $this->Cell(17, 3, 'NRO. DOC.', 0, 0, 'R');
	$this->Cell(23, 3, 'REFERENCIA', 0, 0, 'R');
        $this->Cell(55, 3, 'DESCRIPCION', 0, 0, 'C');
        $this->Cell(18, 3, 'DEBE', 0, 0, 'C');
	$this->Cell(18, 3, 'HABER', 0, 1, 'C');
	
	$this->Cell(8, 4, '', 0, 1, 'C');
        
	//fin de la cabecera del pdf
	///// ******************	
}
//Page footer
function Footer(){
    //Position at 1.5 cm from bottom
    $this->SetXY(154,13);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,10,' '.$this->PageNo().'/{nb}',0,0,'C');
}
}
//Instanciation of inherited class
$pdf=new PDF('P','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

$s_con01 = "select 
                  a.Voucher,
				  a.Periodo,
				  a.Linea,
				  a.Descripcion,
				  a.CodCentroCosto,
				  a.ReferenciaTipoDocumento,
				  a.ReferenciaNroDocumento,
				  a.CodCuenta,
				  a.CodPersona,
				  a.MontoVoucher,
				  b.FechaVoucher,
				  b.TituloVoucher,
				  b.Creditos,
				  b.MotivoAnulacion
		    from 
			      ac_voucherdet a
				  inner join
                                  ac_vouchermast b on ((b.Voucher = a.Voucher) and (b.Periodo = a.Periodo) and (b.CodOrganismo = a.CodOrganismo))
		    where
			  	  
				  b.Estado!='AB' and a.CodOrganismo<>'' $filtro1
			order by 
			      a.Voucher, a.Linea"; //echo $s_con01;

$q_con01 = mysql_query($s_con01) or die ($s_con01.mysql_error());
$r_con01 = mysql_num_rows($q_con01); //echo $r_con01;
if($r_con01!=0){
  $debe01 = "0,00"; 
  $haber01 ="0,00"; 
  
    $debe = "0,00"; 
    $haber ="0,00";	
    $t_debe = 0; 
    $t_haber = 0; 
    $cont = 0;	
    
  for($i=0; $i<$r_con01; $i++){ //echo $i.'/';
     //fecha del voucher
      $f_con01 = mysql_fetch_array($q_con01);
	 list($ano, $mes, $dia) = split('[-]',$f_con01['FechaVoucher']);
         $f_vocucher = $dia.'-'.$mes.'-'.$ano;
	 
	 if($f_con01['Voucher'] != $codVoucherCapturada){
		if($cont==1){
			//echo $t_debe.'-'.$t_haber.'/';
		   $t_debe = number_format($t_debe,2,',','.');	
		   $t_haber = number_format($t_haber,2,',','.');	
		   $pdf->SetFillColor(202, 202, 202);
		   $pdf->SetFont('Arial', 'B', 7);
                   $pdf->Cell(111,6, '',0,0,'L');
                   $pdf->Cell(48,6,'TOTAL VOUCHER ->',0,0,'R');
                   $pdf->Cell(18,6,$t_debe,0,0,'R');
		   $pdf->Cell(18,6,$t_haber,0,1,'R');
		$t_debe='';
                $t_haber='';
		}
                
                $titulo="".$f_con01['MotivoAnulacion']." ".$f_con01['TituloVoucher']." ";
		$codVoucherCapturada = $f_con01['Voucher']; 
		$pdf->SetFillColor(202, 202, 202);
		$pdf->SetFont('Arial', 'B', 7);
	    $pdf->Cell(30,6,$f_con01['Periodo'].'-'.$f_con01['Voucher'],0,0,'L'); 
            $pdf->Cell(25,6,$titulo ,0,1,'L');
	 }
	$cont = 1;
	$valor = substr($f_con01['MontoVoucher'],0,1);
	$debe = 0.00; $haber = 0.00;
    if($valor == '-'){
           $haber = substr($f_con01['MontoVoucher'],1,11);  //echo $haber;
	  $haber01 = number_format($haber,2,',','.');
    }else{
           $debe = $f_con01['MontoVoucher'];
	  $debe01 = number_format($debe,2,',','.');
    }
	$t_debe = $t_debe + $debe; //echo $t_debe;
	$t_haber = $t_haber + $haber; //echo $t_haber;
	$debe = 0; $haber = 0;
	//***********
	$contMax += 1;
	$_i = $contMax+1; 
	
	  $pdf->SetFillColor(255, 255, 255);
	  $pdf->SetFont('Arial', '', 7);
	  $pdf->SetWidths(array(6, 15, 15, 15, 10, 25, 18, 55, 18, 18));
	  $pdf->SetAligns(array('C','L','C','C','C','C','C','L','R','R',));
	  $pdf->Row(array($f_con01['Linea'],$f_con01['CodCuenta'],$f_vocucher,$f_con01['CodPersona'],$f_con01['CodCentroCosto'],$f_con01['ReferenciaTipoDocumento'].'-'.$f_con01['ReferenciaNroDocumento'],$f_con01['ReferenciaNroDocumento'],$f_con01['Descripcion'], $debe01, $haber01));
	  
	  $debe01 = "0,00"; $haber01 ="0,00";
    //echo $_i.'/'.$contMax.'*';
	if($_i > $r_con01){
		   $t_debe = number_format($t_debe,2,',','.');	
		   $t_haber = number_format($t_haber,2,',','.');
		   $pdf->SetFillColor(202, 202, 202);
		   $pdf->SetFont('Arial', 'B', 7);
	       $pdf->Cell(111,6, '',0,0,'L'); $pdf->Cell(48,6,'TOTAL VOUCHER ->',0,0,'R'); $pdf->Cell(18,6,$t_debe,0,0,'R');
		   $pdf->Cell(18,6,$t_haber,0,1,'R');
	}
}
}
//---------------------------------------------------*/
/*$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(202, 202, 202); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(100,10,'',0,1,'L');
	$pdf->Cell(100,10,'ELABORADO POR:',0,0,'L');$pdf->Cell(120,10,'REVISADO POR:',0,0,'L');$pdf->Cell(100,10,'CONFORMADO POR:',0,1,'L');
	$pdf->Cell(100,5,'',0,0,'L');$pdf->Cell(120,5,'',0,0,'L');$pdf->Cell(100,5,'',0,1,'L');
	$pdf->Cell(100,5,'T.S.U. MARIANA SALAZAR',0,0,'L');$pdf->Cell(120,5,'LCDA. YOSMAR GREHAM',0,0,'L');$pdf->Cell(100,5,'LCDA. ROSIS REQUENA',0,1,'L');
	$pdf->Cell(100,2,'ASISTENTE DE PRESUPUESTI I',0,0,'L');$pdf->Cell(120,2,'JEFE(A) DIV. ADMINISTRACION Y PRESUPUESTO',0,0,'L');$pdf->Cell(100,2,'DIRECTORA GENERAL',0,1,'L');*/
$pdf->Output();
?>  
