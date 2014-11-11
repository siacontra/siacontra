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

	
$Periodo=$_POST["fPeriodo"];
//---------------------------------------------------
//---------------------------------------------------
//echo $Periodo;
class PDF extends FPDF
{
//Page header
function Header(){
    
	global $Periodo, $filtro2;
	$this->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$this->SetFont('Arial', 'B', 8);
	$this->SetXY(20, 10); 
	$this->Cell(146, 5,utf8_decode( 'Contralor铆a del Estado Monagas'), 0, 0, 'L');
	$this->Cell(10,5,'Fecha:',0,0,'');
	$this->Cell(10,5,date('d/m/Y'),0,1,'');
	$this->SetXY(20, 15); 
	$this->Cell(145, 5, utf8_decode('Direcci贸n de Administraci贸n'), 0, 0, 'L');
	$this->Cell(10,5,utf8_decode('P谩gina:'),0,1,'');
	$this->SetXY(19, 20); 
	$this->Cell(150, 5, '', 0, 0, 'L');
	$this->Cell(7,5,utf8_decode('A帽o:'),0,0,'L');
	$this->Cell(6,5,date('Y'),0,1,'L');
						   
	/*list($fano, $fmes) = SPLIT('[-]', $Periodo);
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
    }*/
	//echo $fdesde.''.$fhasta;exit();
	
	if($Periodo == ''){		
		
		$Peri="".date("Y").'-'.'01'."";	
		$Periac=date("Y-m");	   
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(50, 5, '', 0, 0, 'C');
		$this->Cell(100, 5, utf8_decode('BALANCE DE COMPROBACI脫N'), 0, 1, 'C');
		$this->SetFont('Arial', '', 10);
		$this->Cell(104, 5, utf8_decode('Periodo: ').$Peri, 0, 0, 'R'); 
		$this->Cell(25, 5, ' AL '.$Periac, 0, 1, 'C'); $this->Ln(2);
	}else{
	    $this->SetFont('Arial', 'B', 10);
		$this->Cell(50, 5, '', 0, 0, 'C');
		$this->Cell(100, 5, utf8_decode('BALANCE DE COMPROBACION'), 0, 1, 'C');
		$this->SetFont('Arial', '', 10);
		$this->Cell(104, 5, utf8_decode('Periodo: ').$Periodo, 0, 0, 'R');
	   $this->Cell(25, 5, '   ', 0, 1, 'C'); 
	   $this->Ln(2);
	}
	
	//echo $fmes;					   
	/*$this->SetFont('Arial', 'B', 10);
	$this->Cell(50, 10, '', 0, 0, 'C');
	$this->Cell(55, 10, utf8_decode('BALANCE DE COMPROBACION'), 0, 1, 'C');*/
    //$this->Cell(15, 10, $mes, 0, 0, 'C'); $this->Cell(8, 10, utf8_decode('DE'), 0, 0, 'C');
	//$this->Cell(8, 10, $fano, 0, 1, 'C');
	
	
	$this->SetFont('Arial', 'B', 7);
	//$this->Rect(10,34,195,'','');
	//$this->Rect(10,38,195,'','');
	
	$this->Cell(20, 10,'CUENTA', 1, 0, 'C');
	$this->Cell(60, 10,'DESCRIPCION', 1, 0, 'C');
	
	$this->Cell(40, 5,'MOVIMIENTO DEL MES', 1, 0, 'C');
	$this->Cell(40, 5,'SUMAS DEL MAYOR', 1, 0, 'C');
	
	$this->Cell(40, 5,'SALDOS', 1, 1, 'C');
	
	$this->Cell(20, 0,'', 0, 0, 'C');
	$this->Cell(60, 0,'', 0, 0, 'C');
	
	$this->Cell(20, 5,'DEBE', 1, 0, 'C');
	$this->Cell(20, 5,'HABER', 1, 0, 'C');
	
	$this->Cell(20, 5,'DEBITOS', 1, 0, 'C');
	$this->Cell(20, 5,'CREDITOS', 1, 0, 'C');
	
	$this->Cell(20, 5,'DEBITOS', 1, 0, 'C');
	$this->Cell(20, 5,'CREDITOS', 1, 1, 'C');
	
	
	
	//$this->Cell(8, 4, '', 0, 1, 'C');
	
	///// ******************	
}}
//Page footer
function Footer(){
    //Position at 1.5 cm from bottom
    $this->SetXY(154,13);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,10,' '.$this->PageNo().'/{nb}',0,0,'C');
}

//Instanciation of inherited class
$pdf=new PDF('P','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

////------------------------------------------------------------------------ */
//// Consulta para traer los datos segun seleccion de filtro
////------------------------------------------------------------------------ */

if($Periodo==''){$filtro2=" and a.Periodo>='".date("Y").'-'.'00'."' and a.Periodo<='".date("Y-m")."'";} 
else { $filtro2=" and a.Periodo>='".date("Y").'-'.'00'."' and a.Periodo<='".$Periodo."'";}

/// Consulta para obtener los movimientos de las cuentas para el periodo
$s_con01 = "select
        		a.CodOrganismo,
				a.Periodo,
				a.CodCuenta,
				a.SaldoBalance,
				b.Descripcion,
				b.Nivel,
				b.Grupo,
				b.SubGrupo
			from
        		ac_voucherbalance a 
				inner join ac_mastplancuenta b on (b.CodCuenta=a.CodCuenta)
                                
			where
                        
        		a.CodOrganismo<>'' $filtro2 $filtro1
        		
		group by 
				a.CodCuenta"; 


$q_con01 = mysql_query($s_con01) or die ($s_con01.mysql_error());
$r_con01 = mysql_num_rows($q_con01); 

if($r_con01!=0){
  $t_debe = 0; $t_haber = 0; $cont = 0;	
  
  for($i=0; $i<$r_con01; $i++){ //echo $i.'/';
     $f_con01 = mysql_fetch_array($q_con01);
	 list($ano, $mes, $dia) = split('[-]', $f_con01['FechaVoucher']); $f_vocucher = $dia.'-'.$mes.'-'.$ano;
	 
	 ///  Capturando subgrupo
	    if($f_con01['Nivel']=='3')$valorCuentaSubgrupo = substr($f_con01['CodCuenta'],0,-1);
		else if($f_con01['Nivel']=='4')
                    $valorCuentaSubgrupo = substr($f_con01['CodCuenta'],0,-3);
		else if($f_con01['Nivel']=='5')
                    $valorCuentaSubgrupo = substr($f_con01['CodCuenta'],0,-5);
		else if($f_con01['Nivel']=='6')
                    $valorCuentaSubgrupo = substr($f_con01['CodCuenta'],0,-7);
		else if($f_con01['Nivel']=='7')
                    $valorCuentaSubgrupo = substr($f_con01['CodCuenta'],0,-10);
	
	///  Obteniendo Descripci贸n de SubGrupo	
		$s_con03 = "select 
						  CodCuenta,
						  Descripcion,
						  Grupo,
						  subGrupo 
					 from 
					      ac_mastplancuenta 
					 where 
					      CodCuenta = '$valorCuentaSubgrupo'"; 
		$q_con03 = mysql_query($s_con03) or die ($s_con03.mysql_error());
		$r_con03 = mysql_num_rows($q_con03);
		if($r_con03!=0) $f_con03=mysql_fetch_array($q_con03);
			
		if($CuentaCapt != $f_con03['CodCuenta']){ /// condici贸n para mostrar cuenta SubGrupo
		  // $pdf->SetFillColor(202, 202, 202);
		  // $pdf->SetFont('Arial', 'B', 8);
		   //$pdf->Cell(10,6,$f_con03['CodCuenta'],0,0,'L'); 
		  // $pdf->Cell(25,6,$f_con03['Descripcion'],0,1,'L'); 
		   $CuentaCapt = $f_con03['CodCuenta'];   
		}
		    $pdf->SetFillColor(202, 202, 202);
		    $pdf->SetFont('Arial', 'B', 7);
	/// Obteniendo Saldo Anterior
	  if($f_con01['CodCuenta'] != $codCuentaCapturada){
		$CodCuentaCapturada = $f_con01['CodCuenta'];  
	    list($a, $m) = split('[-]',$Periodo);
	   //echo "$m";
		if($m=='01'){ 
			 $sa_debe= '0,00';
			 $sa_haber = '0,00';
		    
		
	      $pdf->Cell(20,4,$f_con01['CodCuenta'],0,0,'L'); 
		   $pdf->Cell(60,4,substr($f_con01['Descripcion'], 0, 40),0,0,'L');
		  // $pdf->Cell(18,6,number_format($sa_debe, 2, ',', '.'),0,0,'R');
		 // $pdf->Cell(18,6,number_format($sa_haber, 2, ',', '.'),0,1,'R');
		
		}
		else{
		  $m = $m - 01 ; //echo $m ;
		  $periodo_anterior = $a.'-'.'0'.$m; 
		  //echo $periodo_anterior;
		 
			
		 
		  $s_saldoanterior = "select 
								    * 
							   from 
								    ac_voucherbalance 
							  where 
								    Periodo = '$periodo_anterior' and 
								    
								    CodCuenta = '".$f_con01['CodCuenta']."'";
	     $q_saldoanterior = mysql_query($s_saldoanterior) or die ($s_saldoanterior.mysql_error());
		 $r_saldoanterior = mysql_num_rows($q_saldoanterior);
		 
		 
		 
		  if($r_saldoanterior =="0"){ 
		 $sa_debe = '0';
		 $sa_haber = '0';
		 } elseif($r_saldoanterior=="1"){
		 	$f_saldoanterior = mysql_fetch_array($q_saldoanterior);
		 	$variable=$f_saldoanterior['SaldoBalance'];
		  if($variable>0){
			$sa_debe = $f_saldoanterior['SaldoBalance'];
			$sa_haber = '0';
		 }else{ 
		    $sa_debe = '0';
		    $sa_haber = $f_saldoanterior['SaldoBalance'];
		 }}
	
		 
		   $pdf->SetFillColor(202, 202, 202);
		    $pdf->SetFont('Arial', 'B', 7);
	      $pdf->Cell(20,4,$f_con01['CodCuenta'],0,0,'L'); 
		  $pdf->Cell(60,4,substr($f_con01['Descripcion'], 0, 40),0,0,'L');
		 // $pdf->Cell(110,6,'SALDO ANTERIOR ->',0,0,'R');
		  
	   }
	  
	 
}
//// ----------------------------------------------------------------------
//// 			CONSULTO TABLA AC_VOUCHERDET Y AC_VOCUHERMAST 
list($pa_ano, $pa_mes) = split('[-]',$f_con01['Periodo']); 
$pa_mes = $pa_mes-1;
$periodoAnterior = $pa_ano.'-'.'0'.''.$pa_mes; 
//echo $periodoAnterior;

$s_con02 = "select
			  vmast.Voucher,
			  vdet.Linea,
			  vmast.FechaVoucher,
			  vdet.CodPersona,
			  vdet.ReferenciaNroDocumento,
			  vdet.MontoVoucher,
			  vmast.TituloVoucher
		from
			  ac_voucherdet vdet
			  inner join ac_vouchermast vmast on ((vmast.Voucher = vdet.Voucher) and (vmast.Periodo = vdet.Periodo))
		where
                          vmast.Estado='MA' and
			  vdet.Periodo = '".$Periodo."' and 
			  vdet.CodCuenta = '".$f_con01['CodCuenta']."' and 
			  vdet.CodOrganismo = '".$f_con01['CodOrganismo']."'"; 

$q_con02 = mysql_query($s_con02) or die ($s_con02.mysql_error());  
$r_con02 = mysql_num_rows($q_con02);
//// ---------------------------------------------------------------------- 
if($r_con02!=0){ 
    $t_debe = 0;
    $t_haber = 0;
 for($a=0; $a<$r_con02; $a++){
	$haber = 0; 
        $debe = 0;
	$f_con02 = mysql_fetch_array($q_con02);
	list($ano02, $mes02, $dia02) = split('[-]',$f_con02['FechaVoucher']); 
        $fecha_Voucher = $dia02.'-'.$mes02.'-'.$ano02;
  
	$valor = substr($f_con02['MontoVoucher'],0,1);
	if($valor == '-'){
	  $haber = $f_con02['MontoVoucher']; //echo ' Haber= '.$haber;
	}else{
	  $debe = $f_con02['MontoVoucher']; //echo ' Debe= '.$debe;
	}
	 $t_debe = $t_debe + $debe;// echo ' T_Debe= '.$t_debe;
	 $t_haber = $t_haber + $haber; //echo ' T_Haber= '.$t_haber;
	 $debe01 = number_format($debe,2,',','.');
	 $haber01 = number_format($haber,2,',','.');
	 
	} 

$t_saldoActualCuenta = $t_debe + $t_haber;
//$t_saldoActualCuenta = number_format($t_saldoActualCuenta,2,',','.');

$t_saldoAnterior = 
$t_debeA = $t_debeA + $debeAnterior;// echo ' T_Debe= '.$t_debe;
$t_haberA = $t_habera + $haberAnterior; //echo ' T_Haber= '.$t_haber;



//Movimientos del mes
		
 // reporde del diario 

 $pdf->Cell(20,4,number_format($t_debe,2,',','.'),'','','R'); //Movimientos del Mes por el debe
 $pdf->Cell(20,4,number_format((-1*$t_haber),2,',','.'),'','','R'); //Movimientos del mes por el haber
 
 
 
 /// Obteniendo Saldo Anterior
 
 $pdf->Cell(20,4,number_format($sa_debe, 2, ',', '.'),'','','R');
 $pdf->Cell(20,4,number_format((-1*$sa_haber), 2, ',', '.'),'','','R');
 
 
 
 $ValorCuenta=$t_debe+$sa_debe;
 $ValorCuentaH=$t_haber+$sa_haber;
 
 
 if($ValorCuenta>$ValorCuentaH){
 	$t_saldoActualCuenta=$ValorCuenta+($ValorCuentaH);
 	
 	}elseif($ValorCuenta==$ValorCuentaH){
 		 $t_saldoActualCuenta="0,00"; 
 		 }
 	else{
 		 $t_saldoActualCuenta=$ValorCuenta+($ValorCuentaH);
 		 
 		 }
 // total del movimiento por mes	

   $fMoviMesDeb=$fMoviMesDeb+$t_debe;
   $fMoviMesHab=$fMoviMesHab+$t_haber;

// Total de sumas del mayor

	  	$sMDebeT=$sMDebeT+$sa_debe;
		$sMHaberT=$sMHaberT+$sa_haber;


		//Saldos De las cuentas y su ubicaci髇 en el modulo del debe o del haber
if($t_saldoActualCuenta>0){
 $pdf->Cell(20,4,number_format($t_saldoActualCuenta,2,',','.'),'',0,'R');
 $pdf->Cell(20,4,'0,00','',1,'R');
 
 $fdebe=$fdebe+$t_saldoActualCuenta;
 
}else{
 
 $pdf->Cell(20,4,'0,00','',0,'R');
 $pdf->Cell(20, 4, number_format((-1*$t_saldoActualCuenta), 2, ',', '.'), '', 1, 'R');

$fhaber=$fhaber+$t_saldoActualCuenta;
}

 //// ----------------------------------------------------------------------
$cont = 1; $debe = $haber = 0;
$valor = substr($f_con01['MontoVoucher'],0,1);
if($valor == '-'){
  $haber = substr($f_con01['MontoVoucher'],1,11); //echo ' *Haber= '.$haber;
}else{
  $debe = $f_con01['MontoVoucher']; //echo ' *Debe= '.$debe;
}
$t_debe = $t_debe + $debe; //echo ' *T_Debe= '.$t_debe;
$t_haber = $t_haber + $haber; //echo ' *T_Haber= '.$t_haber;




	  	// calculo del saldo final por mes
	   $final_cuentadebe=$t_debe+$sa_debe;
	   $final_cuentahaber=$t_haber+$sa_haber;
	   
	   
	   
	   
	  //Condicion si la Cuenta hasta la actualidad no se ha terminado los ajustes o pago de vohucher comprometidos

} else{ 


//Saldo Historico
if($Periodo==''){$filtro3=" and Periodo>='".date("Y").'-'.'00'."' and Periodo<='".date("Y-m")."'";} else{
		$filtro3="and Periodo<'".$Periodo."' ";}
				$s_saldoanterior2 = "select 
								    * 
							   from 
								    ac_voucherbalance 
							  where 
								    								    
								    CodCuenta='".$f_con01['CodCuenta']."'$filtro3  ";
								    
	     $q_saldoanterior2 = mysql_query($s_saldoanterior2) or die ($s_saldoanterior2.mysql_error());
		 $r_saldoanterior2 = mysql_num_rows($q_saldoanterior2); 
 	
 			if($r_saldoanterior==''){}else{}
 			
 			
 			
		  if($r_saldoanterior2 =="0"){ 
		 $sa_debe = '0';
		 $sa_haber = '0';
		 } elseif($r_saldoanterior2=="1"){
		 	$f_saldoanterior2 = mysql_fetch_array($q_saldoanterior2);
		 	$variable2=$f_saldoanterior2['SaldoBalance'];
		  if($variable2>0){
			$sa_debe = $f_saldoanterior2['SaldoBalance'];
			$sa_haber = '0';
		 }else{ 
		    $sa_debe = '0';
		    $sa_haber = $f_saldoanterior2['SaldoBalance'];
		 }}
		
		
		
 // reporde del diario 

 $pdf->Cell(20,4,number_format('0,00',2,',','.'),'','','R'); //Movimientos del Mes por el debe
 $pdf->Cell(20,4,number_format(('0,00'),2,',','.'),'','','R'); //Movimientos del mes por el haber
 
 /// Obteniendo Saldo Anterior
 
 $pdf->Cell(20,4,number_format($sa_debe, 2, ',', '.'),'','','R');
 $pdf->Cell(20,4,number_format((-1*$sa_haber), 2, ',', '.'),'','','R');
 
 $ValorCuenta=$t_debe+$sa_debe;
 $ValorCuentaH=$t_haber+$sa_haber;
 
 
 if($sa_debe>(-1*$sa_haber)){
 	$t_saldoActualCuenta=$sa_debe+($sa_haber);
 	
 	}elseif($sa_debe==(-1*$sa_haber)){ $t_saldoActualCuenta="0,00"; }
 	else{ $t_saldoActualCuenta=$sa_debe+($sa_haber);
 		 }
 // total del movimiento por mes	

  // $fMoviMesDeb=$fMoviMesDeb+$t_debe;
  // $fMoviMesHab=$fMoviMesHab+$t_haber;

// Total de sumas del mayor

	  	$sMDebeT=$sMDebeT+$sa_debe;
		$sMHaberT=$sMHaberT+$sa_haber;


if($t_saldoActualCuenta>0){
 
 //$pdf->Cell(48,4,'SALDO ACTUAL CUENTA '.''.$f_con01['CodCuenta'],0,0,'R'); 
 
 
 $pdf->Cell(20,4,number_format($t_saldoActualCuenta,2,',','.'),'',0,'R');
 $pdf->Cell(20,4,'0,00','',1,'R');
 	$fdebe=$fdebe+$t_saldoActualCuenta;
 }else{
 
 //$t_saldoActualCuenta = number_format((-1*$t_saldoActualCuenta),2,'','');
// $pdf->Cell(48,4,'SALDO ACTUAL CUENTA '.''.$f_con01['CodCuenta'],0,0,'R');
 
 $pdf->Cell(20,4,'0,00','',0,'R');
//$pdf->Cell(20,4,((-1*$t_saldoActualCuenta), 2, ',', '.'),'',1,'');

$pdf->Cell(20, 4, number_format((-1*$t_saldoActualCuenta), 2, ',', '.'), '', 1, 'R');

$fhaber=$fhaber+$t_saldoActualCuenta;

}
 
  } 
 


		} }

	// incremento del total del debe y haber del mayor
		$SaldoFinalDeb=$SaldoFinalDeb+$fdebe;
		$SaldoFinalHab=$SaldoFinalHab+$fhaber;





		//$debe_cuenta = number_format($final_cuentadebe, 2, ',', '.'); 
	   //$haber_cuenta = number_format((-1*$final_cuentahaber), 2, ',', '.');
	 
	   
	   
	   
	  	   		      	   
	   

     /*$pdf->Row(20,4, $f_con01['CodCuenta'],0,0,'L'); $pdf->Row(60,4, substr($f_con01['Descripcion'],0,45),0,0,'L'); $pdf->Row(18,4,$t_debe,0,0,'R');
     $pdf->Row(18,4,$t_haber,0,1,'R');*/  
  
	// Linea del total del los movimientos



	  	
       $pdf->Cell(80, 4, 'Total: ', 1, 0, 'R');	//
	   $pdf->Cell(20, 4, number_format($fMoviMesDeb,2,',','.'), 1, 0, 'R');//	Movimientos del mes debe
	   $pdf->Cell(20, 4, number_format((-1*$fMoviMesHab),2,',','.'), 1, 0, 'R');// 	Movimientos del mes Haber
	   $pdf->Cell(20, 4,number_format( $sMDebeT, 2, ',', '.'), 1, 0, 'R');//	Saldo inicial debe
	   $pdf->Cell(20, 4,number_format((-1* $sMHaberT), 2, ',', '.'), 1, 0, 'R');  // Saldo Inicial Haber
	   $pdf->Cell(20, 4, number_format($SaldoFinalDeb, 2, ',', '.'), 1, 0, 'R');// Saldos finales debe
	   $pdf->Cell(20, 4, number_format((-1*$SaldoFinalHab), 2, ',', '.'), 1, 0, 'R');// Saldos Finales Haber
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



