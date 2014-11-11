<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');
require('fphp.php');
connect(); 
extract ($_POST);
extract ($_GET);
/// -------------------------------------------------
//---------------------------------------------------
$filtro1=strtr($filtro1, "*", "'");
global $fd, $fh;
//---------------------------------------------------
//---------------------------------------------------
//echo $Periodo;
class PDF extends FPDF
{
//Page header
function Header(){
    
	global $fd, $fh;
	$this->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$this->SetFont('Arial', 'B', 8);
	$this->SetXY(20, 10); $this->Cell(146, 5,utf8_decode( 'Contraloría del Estado Monagas'), 0, 0, 'L');
	                      $this->Cell(10,5,'Fecha:',0,0,'');$this->Cell(10,5,date('d/m/Y'),0,1,'');
	$this->SetXY(20, 15); $this->Cell(145, 5, utf8_decode('Dirección de Administración y Servicios Generales'), 0, 0, 'L');
	                       $this->Cell(10,5,utf8_decode('Página:'),0,1,'');
	$this->SetXY(19, 20); $this->Cell(150, 5, '', 0, 0, 'L');
	                       $this->Cell(7,5,utf8_decode('Año:'),0,0,'L');$this->Cell(6,5,date('Y'),0,1,'L');
						   
	/*list($fano, $fmes) = SPLIT('[-]',$Periodo);
    switch ($fmes) {
		case 01: $mes = ENERO; break;  
		case 02: $mes = FEBRERO;break; 
		case 03: $mes = MARZO;break;   
		case 04: $mes = ABRIL;break;   
		case 05: $mes = MAYO;break;    
		case 06: $mes = JUNIO;break;
		case 07: $mes = JULIO; break;
		case 08: $mes = AGOSTO; break;
		case 09: $mes = SEPTIEMBRE; break;
		case 10: $mes = OCTUBRE; break;
		case 11: $mes = NOVIEMBRE; break;
		case 12: $mes = DICIEMBRE; break;
    }*/
	//echo $fmes;
	if(($fd!='')and($fh!='')and($fd!='0000-00')and($fh!='9999-99')){					   
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(50, 5, '', 0, 0, 'C');
		$this->Cell(100, 5, utf8_decode('BALANCE DE COMPROBACIÓN SUMAS Y SALDOS'), 0, 1, 'C');
		$this->SetFont('Arial', '', 10);
		$this->Cell(104, 5, utf8_decode('Perídodo: ').$fd, 0, 0, 'R'); $this->Cell(25, 5, 'AL  '.$fh, 0, 1, 'C'); $this->Ln(2);
	}else{
	    $this->SetFont('Arial', 'B', 10);
		$this->Cell(50, 5, '', 0, 0, 'C');
		$this->Cell(100, 5, utf8_decode('BALANCE DE COMPROBACIÓN SUMAS Y SALDOS'), 0, 1, 'C');
		$this->SetFont('Arial', '', 10);
		$this->Cell(104, 5, utf8_decode('Perídodo: ').date("Y").'-01', 0, 0, 'R'); $this->Cell(25, 5, 'AL   '.date("Y-m"), 0, 1, 'C'); $this->Ln(2);
	}
						   
	/*$this->SetFont('Arial', 'B', 10);
	$this->Cell(50, 10, '', 0, 0, 'C');
	$this->Cell(55, 10, utf8_decode('BALANCE DE COMPROBACION SUMAS Y SALDOS'), 0, 1, 'C');*/
    //$this->Cell(15, 10, $mes, 0, 0, 'C'); $this->Cell(8, 10, utf8_decode('DE'), 0, 0, 'C');
	//$this->Cell(8, 10, $fano, 0, 1, 'C');
	
	
	$this->SetFont('Arial', 'B', 7);
	//$this->Rect(10,34,195,'','');
	//$this->Rect(10,38,195,'','');
	
	$this->Cell(20, 10,'CODIGO', 1, 0, 'C');
	$this->Cell(70, 10,'CUENTAS', 1, 0, 'C');
	$this->Cell(40, 5,'SUMAS', 1, 0, 'C');
	$this->Cell(40, 5,'SALDOS', 1, 1, 'C');
	
	$this->Cell(20, 0,'', 0, 0, 'C');
	$this->Cell(70, 0,'', 0, 0, 'C');
	$this->Cell(20, 5,'DEBE', 1, 0, 'C');
	$this->Cell(20, 5,'HABER', 1, 0, 'C');
	$this->Cell(20, 5,'DEUDOR', 1, 0, 'C');
	$this->Cell(20, 5,'ACREEDOR', 1, 1, 'C');
	
	
	$this->Cell(8, 4, '', 0, 1, 'C');
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

////------------------------------------------------------------------------ */
//// Consulta para traer los datos segun seleccion de filtro
////------------------------------------------------------------------------ */
/*$s_con01 = "select 
				   ac.CodCuenta,
				   acm.Descripcion,
				   ac.SaldoBalance 
		       from 
			       ac_voucherbalance ac 
				   inner join ac_mastplancuenta acm on (acm.CodCuenta = ac.CodCuenta)
			  where 
			       CodOrganismo<>'' $filtro1 
		      order by ac.Codcuenta ";//echo $s_con01;
$q_con01 = mysql_query($s_con01) or die ($s_con01.mysql_error());
$r_con01 = mysql_num_rows($q_con01); //echo $r_con01;*/

$s_con01 ="select
                 ac.CodCuenta,
				 ac.CodOrganismo
            from
                 ac_voucherbalance ac 
		    where 
			     ac.CodOrganismo<>'' $filtro1 
           group by ac.CodCuenta
           order by ac.CodCuenta"; //echo $s_con01;
$q_con01 = mysql_query($s_con01) or die ($s_con01.mysql_error());
$r_con01 = mysql_num_rows($q_con01);
if($r_con01!=0){
  for($i=0; $i<$r_con01; $i++){
	 $f_con01= mysql_fetch_array($q_con01);
	 /// *********************************************************
	 $s_con02 = "select 
					   ac.CodCuenta,
					   acm.Descripcion,
					   ac.SaldoBalance,
					   acm.Nivel 
				   from 
					   ac_voucherbalance ac 
					   inner join ac_mastplancuenta acm on (acm.CodCuenta = ac.CodCuenta)
				  where 
					   ac.CodOrganismo = '".$f_con01['CodOrganismo']."' and 
					   ac.CodCuenta = '".$f_con01['CodCuenta']."'
			   order by 
			           ac.CodCuenta"; //echo $s_con02;
    $q_con02 = mysql_query($s_con02) or die ($s_con02.mysql_error());
    $r_con02 = mysql_num_rows($q_con02); //echo $r_con01; 
	/// *********************************************************
	$t_haber = '0,00'; $t_debe = '0,00';
	if($r_con02!=0){
	   for($a=0; $a<$r_con02; $a++){
          $f_con02 = mysql_fetch_array($q_con02);
		  
		  if($f_con02['SaldoBalance']>=0){
	        if($f_con02['CodCuenta']==$CuentaCapt){
		      $t_debe+= $f_con02['SaldoBalance'];
		      //$t_haber = '0,00';
		    }else{
		      $t_debe= $f_con02['SaldoBalance'];
		      //$t_haber = '0,00';
		    }
	      }else{
	          //$t_debe = '0,00';
		    if($f_con01['CodCuenta']==$CuentaCapt){
		       $t_haber+= $f_con02['SaldoBalance'];
		    }else{
		       $t_haber = $f_con02['SaldoBalance'];
		    }
	      }
	      $CuentaCapt= $f_con02['CodCuenta'];
		  
		  if($f_con02['Nivel']=='4')$valor = substr($CuentaCapt, 0, -3); 
		  else if($f_con02['Nivel']=='5')$valor = substr($CuentaCapt, 0, -5);
		  else if($f_con02['Nivel']=='6')$valor = substr($CuentaCapt, 0, -7);
		  else if($f_con02['Nivel']=='7')$valor = substr($CuentaCapt, 0, -10);
		   
		  if($valor_capturado==$valor) $cont_cuenta=0;
		  else{
		      if($paso!='paso'){
				 $cont_cuenta=0;
				 $valor_capturado = $valor;
				 $paso='paso';
			  }else $cont_cuenta=1;
		  }
	    }
	      /*if($cont_cuenta==1){
			  
			$s_cuenta = "select * from ac_mastplancuenta where CodCuenta='$valor_capturado'"; 
			$q_cuenta = mysql_query($s_cuenta) or die ($s_cuenta.mysql_error()); 
			$f_cuenta = mysql_fetch_array($q_cuenta);
			
		    $pdf->SetFillColor(202, 202, 202);
		    $pdf->SetFont('Arial', 'B', 7);
		    $pdf->Cell(20,4, $f_cuenta['CodCuenta'],0,0,'L'); 
		    $pdf->Cell(60,4, substr($f_cuenta['Descripcion'],0,20),0,0,'L'); 
		    $pdf->Cell(20,4,'0,00','','','R');
			$pdf->Cell(20,4,'0,00','','','R');
			$pdf->Cell(19,4,'0,00','','','R');
			$pdf->Cell(19,4,'0,00','','','R');
		    $pdf->Cell(18,4,'',0,0,'R');
		    $pdf->Cell(20,4,'',0,1,'R'); 
			
			$paso='';
		  }*/
	       
	   $operacion = $t_debe + $t_haber;
	   if($operacion>=0){
		  $total_debe+=$t_debe; 
		  $total_deudor+=$operacion;
		  $deudor = number_format($operacion, 2, ',', '.');;
		  $acreedor = '0,00';
	   }else{
		  $total_haber+=$t_haber;
		  $total_acreedor +=$operacion;  
		  $acreedor = number_format((-1*$operacion), 2, ',', '.');
		  $deudor = '0,00';
	   }
	   
	   
	     	 
	   $debe_cuenta = number_format($t_debe, 2, ',', '.'); 
	   $haber_cuenta = number_format((-1*$t_haber), 2, ',', '.'); 
       $pdf->SetFillColor(202, 202, 202);
       $pdf->SetFont('Arial', 'B', 7);
       $pdf->Cell(20,4, $f_con02['CodCuenta'],0,0,'L'); 
	   $pdf->Cell(72,4, substr($f_con02['Descripcion'],0,40),0,0,'L'); 
	   $pdf->Cell(18,4,$debe_cuenta,0,0,'R');
       $pdf->Cell(20,4,$haber_cuenta,0,0,'R'); 
	   $pdf->Cell(20,4,$deudor,0,0,'R');
       $pdf->Cell(20,4,$acreedor,0,1,'R'); 
	}
	 
     /*$pdf->Row(20,4, $f_con01['CodCuenta'],0,0,'L'); $pdf->Row(60,4, substr($f_con01['Descripcion'],0,45),0,0,'L'); $pdf->Row(18,4,$t_debe,0,0,'R');
     $pdf->Row(18,4,$t_haber,0,1,'R');*/  
  }
}
       $pdf->Cell(92, 4, 'Total: ', 1, 0, 'R');	
	   $pdf->Cell(18, 4, number_format($total_debe, 2, ',', '.'), 1, 0, 'R');	
	   $pdf->Cell(20, 4, number_format((-1*$total_haber), 2, ',', '.'), 1, 0, 'R');
	   $pdf->Cell(19, 4, number_format($total_deudor, 2, ',', '.'), 1, 0, 'R');
	   $pdf->Cell(20, 4, number_format((-1*$total_acreedor), 2, ',', '.'), 1, 1, 'R');  
////------------------------------------------------------------------------ */
////------------------------------------------------------------------------ */
////------------------------------------------------------------------------ */
////------------------------------------------------------------------------ */
////------------------------------------------------------------------------ */













/*$s_con01 = "select 
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
				  b.Creditos
		    from 
			      ac_voucherdet a
				  inner join ac_vouchermast b on ((b.Voucher = a.Voucher) and (b.Periodo = a.Periodo) and (b.CodOrganismo = a.CodOrganismo))
		    where
			  	  a.Estado='MA' and 
				  a.CodOrganismo<>'' $filtro1
			order by 
			      a.Voucher, a.Linea"; //echo $s_con01;
$q_con01 = mysql_query($s_con01) or die ($s_con01.mysql_error());
$r_con01 = mysql_num_rows($q_con01); //echo $r_con01;
if($r_con01!=0){
  $debe01 = "0,00"; $haber01 ="0,00"; $debe = "0,00"; $haber ="0,00";	
  $t_debe = 0; $t_haber = 0; $cont = 0;	
  for($i=0; $i<$r_con01; $i++){ //echo $i.'/';
     $f_con01 = mysql_fetch_array($q_con01);
	 list($ano, $mes, $dia) = split('[-]',$f_con01['FechaVoucher']); $f_vocucher = $dia.'-'.$mes.'-'.$ano;
	 
	 if($f_con01['Voucher'] != $codVoucherCapturada){
		if($cont==1){
			//echo $t_debe.'-'.$t_haber.'/';
		   $t_debe = number_format($t_debe,2,',','.');	
		   $t_haber = number_format($t_haber,2,',','.');	
		   $pdf->SetFillColor(202, 202, 202);
		   $pdf->SetFont('Arial', 'B', 7);
	       $pdf->Cell(111,6, '',0,0,'L'); $pdf->Cell(48,6,'TOTAL VOUCHER ->',0,0,'R'); $pdf->Cell(18,6,$t_debe,0,0,'R');
		   $pdf->Cell(18,6,$t_haber,0,1,'R');
		}
		$codVoucherCapturada = $f_con01['Voucher']; 
		$pdf->SetFillColor(202, 202, 202);
		$pdf->SetFont('Arial', 'B', 7);
	    $pdf->Cell(30,6,$f_con01['Periodo'].'-'.$f_con01['Voucher'],0,0,'L'); $pdf->Cell(25,6,$f_con01['TituloVoucher'],0,1,'L');
	 }
	$cont = 1;
	$valor = substr($f_con01['MontoVoucher'],0,1);
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
