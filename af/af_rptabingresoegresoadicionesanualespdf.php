<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');
require('fphp.php');
connect(); 
mysql_query("SET NAMES 'utf8'");
extract ($_POST);
extract ($_GET);
list($ano, $mes) = split('[-]',$_POST['fperiodo_desde']); 
$Periodo = $ano; global $Periodo;
//echo $_SESSION["MYSQL_BD"];
/// ----------------------------------------------------
//---------------------------------------------------
$filtro=strtr($filtro, "*", "'");
//global $filtro;
//$Periodo = $Periodo;
//$filtro=strtr($filtro, ";", "%");
//---------------------------------------------------
//---------------------------------------------------
//echo $Periodo;
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
	$this->Cell(240, 3, utf8_decode('Resumen de Adiciones ').$Periodo, 0, 1, 'C');$this->Ln(4);
	
	$this->SetFont('Arial', '', 8);
	  $this->Cell(130,4,'________________________________________________________________________________________________________________________________________________________________________________',0,1,'L');
	$this->SetFont('Arial','B',8);	
	$this->SetDrawColor(255, 255, 255);
	//$this->SetDrawColor(0, 0, 0);
	$this->SetFillColor(255, 255, 255);
	$this->SetWidths(array(33,20,17,17,17,17,17,17,17,17,17,17,17,17,17));
	$this->SetAligns(array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C'));
	$this->Row(array(utf8_decode('Categoría'), 'Saldo','Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set','Oct','Nov','Dic','Totales'));
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

//// -----------------------------------------------------------------------------------------
if($forganismo!="")$filtro.= " AND (a.CodOrganismo = '".$forganismo."')"; 
if($fDependencia!="")$filtro.="AND (a.CodDependencia='".$fDependencia."')";
if($centro_costos!="")$filtro.="AND (a.CentroCosto='".$fcentro_costos."')";
if($fCategoria!="")$filtro.="AND (a.Categoria='".$fCategoria."')";
if($festado!="") $filtro.="AND a.(Estado='".$festado."')";
if(($fperiodo_desde!="")and($fperiodo_hasta!="")) $filtro.=" AND (a.PeriodoIngreso>='".$fperiodo_desde."' AND a.PeriodoIngreso<='".$fperiodo_hasta."')";
if(($fvoucher_desde!="")and($fvoucher_hasta!="")) $filtro.=" AND (a.VoucherIngreso>='".$fvoucher_desde."' AND VoucherIngreso<='".$fvoucher_hasta."')";
if($fcuenta!="") $filtro2="and(c.CuentaHistorica='".$fcuenta."')";
if($fContabilidad!="") $cContabilidad="checked";else $dContabilidad="disabled";
//// -----------------------------------------------------------------------------------------


$s_con_01 = "select 
					a.*,
					c.DescripcionLocal as DescripcionCategoria,
					c.CuentaHistorica
			   from 
			    	af_activo a
					inner join af_categoriadeprec c on (c.CodCategoria=a.Categoria) $filtro2
			   where 
			        a.CodOrganismo<>'' $filtro 
			  order by 
			        Categoria,PeriodoIngreso"; //echo $s_con_01;
$q_con_01 = mysql_query($s_con_01) or die ($s_con_01.mysql_error());
$r_con_01 = mysql_num_rows($q_con_01); //echo $r_con_01;

if($r_con_01!=0)
   for($i=0; $i<$r_con_01; $i++){
      $f_con_01 = mysql_fetch_array($q_con_01);
	  
	  if($f_con_01['Categoria']!=$categoria_capt){
		 //echo "Categoria=".$f_con_01['Categoria'];
		 if($cont>=1){ //echo $categoria_capt;
			$pdf->SetFont('Arial', '', 8); 
		    $pdf->SetDrawColor(255, 255, 255);
	        //$this->SetDrawColor(0, 0, 0);
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetWidths(array(33,20,17,17,17,17,17,17,17,17,17,17,17,17,20));
			$pdf->SetAligns(array('L','C','C','C','C','C','C','C','C','C','C','C','C','C','R'));
			$pdf->Row(array($categoria_capt.'-'.$DescripcionCategoria, number_format($Saldo,2,',','.'), number_format($Ene,2,',','.'), number_format($Feb,2,',','.'),
			                number_format($Mar,2,',','.'), number_format($Abr,2,',','.'), number_format($May,2,',','.'), number_format($Jun,2,',','.'), 
							number_format($Jul,2,',','.'), number_format($Ago,2,',','.'), number_format($Set,2,',','.'), number_format($Oct,2,',','.'), 
							number_format($Nov,2,',','.'), number_format($Dic,2,',','.'), number_format($Totales,2,',','.')));
			$TGTotales += $Totales;
			$Ene = 0; $Feb=0; $Mar=0; $Abr=0; 
			$May=0; $Jun=0; $Jul=0; $Ago=0; 
			$Set=0; $Oct=0; $Nov=0; $Dic=0; $Totales=0;
		 }
		 
		 $categoria_capt = $f_con_01['Categoria']; 
		 $DescripcionCategoria = $f_con_01['DescripcionCategoria'];
		 list($pano, $pmes)=split('[-]',$f_con_01['PeriodoIngreso']);
		 $monto_mes = $f_con_01['MontoLocal'];
		 $Totales += $monto_mes;
		 //$TGTotales += $Totales;
		 //echo "Totales=".$Totales.'-'."TGTotales=".$TGTotales.'/*/';
		 switch ($pmes) {
				case "01": $Ene+= $monto_mes; $TGEne+= $monto_mes;break;  
				case "02": $Feb+= $monto_mes; $TGFeb+= $monto_mes;break; 
				case "03": $Mar+= $monto_mes; $TGMar+= $monto_mes;break;   
				case "04": $Abr+= $monto_mes; $TGAbr+= $monto_mes;break;   
				case "05": $May+= $monto_mes; $TGMay+= $monto_mes;break;    
				case "06": $Jun+= $monto_mes; $TGJun+= $monto_mes;break;
				case "07": $Jul+= $monto_mes; $TGJul+= $monto_mes;break;
				case "08": $Ago+= $monto_mes; $TGAgo+= $monto_mes;break;
				case "09": $Set+= $monto_mes; $TGSet+= $monto_mes;break;
				case "10": $Oct+= $monto_mes; $TGOct+= $monto_mes;break;
				case "11": $Nov+= $monto_mes; $TGNov+= $monto_mes;break;
				case "12": $Dic+= $monto_mes; $TGDic+= $monto_mes;break;
		  }
		 
		 $cont=1;
	  }else{
		 
		 list($pano, $pmes)=split('[-]',$f_con_01['PeriodoIngreso']);
		 $monto_mes = $f_con_01['MontoLocal'];
		 $Totales += $monto_mes;
		 //$TGTotales += $Totales;
		 //echo "Totales2=".$Totales.'-'."TGTotales2=".$TGTotales.'/*/';
		 switch ($pmes) {
				case "01": $Ene+= $monto_mes; $TGEne+= $monto_mes;break;  
				case "02": $Feb+= $monto_mes; $TGFeb+= $monto_mes;break; 
				case "03": $Mar+= $monto_mes; $TGMar+= $monto_mes;break;   
				case "04": $Abr+= $monto_mes; $TGAbr+= $monto_mes;break;   
				case "05": $May+= $monto_mes; $TGMay+= $monto_mes;break;    
				case "06": $Jun+= $monto_mes; $TGJun+= $monto_mes;break;
				case "07": $Jul+= $monto_mes; $TGJul+= $monto_mes;break;
				case "08": $Ago+= $monto_mes; $TGAgo+= $monto_mes;break;
				case "09": $Set+= $monto_mes; $TGSet+= $monto_mes;break;
				case "10": $Oct+= $monto_mes; $TGOct+= $monto_mes;break;
				case "11": $Nov+= $monto_mes; $TGNov+= $monto_mes;break;
				case "12": $Dic+= $monto_mes; $TGDic+= $monto_mes;break;
		 }
		 //echo $f_con_01['Categoria'].'-'."Febrero2=".$Feb.'/*/';
		  $cont=1;
	  }
	  
	  if($i==($r_con_01 - 1)){
		    $TGTotales += $Totales;
			$pdf->SetFont('Arial', '', 8); 
		    $pdf->SetDrawColor(255, 255, 255);
	        //$this->SetDrawColor(0, 0, 0);
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetWidths(array(33,20,17,17,17,17,17,17,17,17,17,17,17,17,20));
			$pdf->SetAligns(array('L','C','C','C','C','C','C','C','C','C','C','C','C','C','R'));
			$pdf->Row(array($categoria_capt.'-'.$DescripcionCategoria, number_format($Saldo,2,',','.'), number_format($Ene,2,',','.'), number_format($Feb,2,',','.'),
			                number_format($Mar,2,',','.'), number_format($Abr,2,',','.'), number_format($May,2,',','.'), number_format($Jun,2,',','.'), 
							number_format($Jul,2,',','.'), number_format($Ago,2,',','.'), number_format($Set,2,',','.'), number_format($Oct,2,',','.'), 
							number_format($Nov,2,',','.'), number_format($Dic,2,',','.'), number_format($Totales,2,',','.')));
			
			$pdf->Ln(3);
			$pdf->SetFont('Arial', 'B', 9);
		    $pdf->SetDrawColor(255, 255, 255);
	        //$this->SetDrawColor(0, 0, 0);
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetWidths(array(33,20,17,17,17,17,17,17,17,17,17,17,17,17,20));
			$pdf->SetAligns(array('R','C','C','C','C','C','C','C','C','C','C','C','C','C','R'));
			$pdf->Row(array('Total General', number_format($TGSaldo,2,',','.'), number_format($TGEne,2,',','.'), number_format($TGFeb,2,',','.'), 
			                number_format($TGMar,2,',','.'), number_format($TGAbr,2,',','.'), number_format($TGMay,2,',','.'), number_format($TGJun,2,',','.'), 
							number_format($TGJul,2,',','.'), number_format($TGAgo,2,',','.'), number_format($TGSet,2,',','.'), number_format($TGOct,2,',','.'), 
							number_format($TGNov,2,',','.'), number_format($TGDic,2,',','.'), number_format($TGTotales,2,',','.')));
			
			$Ene = 0; $Feb=0; $Mar=0; $Abr=0; $TGEne=0; $TGFeb=0; $TGMar=0; $TGAbr=0;
			$May=0; $Jun=0; $Jul=0; $Ago=0; $TGMay=0; $TGJun=0; $TGJul=0; $TGAgo=0; $TGSet=0;
			$Set=0; $Oct=0; $Nov=0; $Dic=0; $Totales=0; $TGOct=0; $TGNov=0; $TGDic=0; $TGTotales=0;
	  }
   }
$pdf->Output();
?>  
