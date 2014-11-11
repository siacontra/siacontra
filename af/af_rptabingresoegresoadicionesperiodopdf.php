<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');
require('fphp.php');
connect(); 
mysql_query("SET NAMES 'utf8'");
extract ($_POST);
extract ($_GET);
//global $Periodo;
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
	$this->Cell(240, 3, utf8_decode('Adiciones del Período'), 0, 1, 'C');$this->Ln(4);
	
	$this->SetFont('Arial', '', 8);
	  $this->Cell(130,4,'________________________________________________________________________________________________________________________________________________________________________',0,1,'L');
		
	$this->SetDrawColor(255, 255, 255);
	//$this->SetDrawColor(0, 0, 0);
	$this->SetFillColor(255, 255, 255);
	$this->SetWidths(array(20,100,20,30,30,30,20));
	$this->SetAligns(array('C','C','C','C','C','C','C'));
	$this->Row(array('Activo', utf8_decode('Descripción'), utf8_decode('Cód. Barras'), utf8_decode('Fecha Adquisición'), utf8_decode('Inicio Depreciación'), utf8_decode('Voucher'), 'Monto'));
	//$this->SetFillColor(255, 255, 255);
	
	$this->SetFont('Arial', '', 8);
	  $this->Cell(130,2,'________________________________________________________________________________________________________________________________________________________________________',0,1,'L');
	///// ******************	
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
if($forganismo!="")$filtro.= " AND (CodOrganismo = '".$forganismo."')"; 
if($fDependencia!="")$filtro.="AND (CodDependencia='".$fDependencia."')";
if($centro_costos!="")$filtro.="AND (CentroCosto='".$fcentro_costos."')";
if($fCategoria!="")$filtro.="AND (Categoria='".$fCategoria."')";
if($festado!="") $filtro.="AND (Estado='".$festado."')";
if(($fperiodo_desde!="")and($fperiodo_hasta!="")) $filtro.=" AND (PeriodoIngreso>='".$fperiodo_desde."' AND PeriodoIngreso<='".$fperiodo_hasta."')";
if(($fvoucher_desde!="")and($fvoucher_hasta!="")) $filtro.=" AND (VoucherIngreso>='".$fvoucher_desde."' AND VoucherIngreso<='".$fvoucher_hasta."')";
//if($fcuenta!="") $filtro2.=" inner join af_categoriadeprec  cat on (cat.CuentaHistorica==)AND (CodCategoria='".$."')";
if($fContabilidad!="") $cContabilidad="checked";else $dContabilidad="disabled";
//// -----------------------------------------------------------------------------------------


$s_con_01 = "select 
					*
			   from 
			    	af_activo
			   where 
			        CodOrganismo<>'' $filtro 
			  order by 
			        PeriodoIngreso, Categoria"; //echo $s_con_01;
$q_con_01 = mysql_query($s_con_01) or die ($s_con_01.mysql_error());
$r_con_01 = mysql_num_rows($q_con_01); //echo $r_con_01;

if($r_con_01!=0)
   for($i=0; $i<$r_con_01; $i++){
      $f_con_01 = mysql_fetch_array($q_con_01);
	  
	  /// Consultas
	  /// ---  Categoría
	  $s_categoria = "select DescripcionLocal from af_categoriadeprec where CodCategoria='".$f_con_01['Categoria']."'"; //echo $s_categoria;
	  $q_categoria = mysql_query($s_categoria) or die ($s_categoria.mysql_error());
	  $r_categoria = mysql_num_rows($q_categoria);
	  if($r_categoria!=0)$f_categoria=mysql_fetch_array($q_categoria);
	  
	  /// --- Clasificacion
	  $s_clasificacion = "select Descripcion from af_clasificacionactivo where CodClasificacion='".$f_con_01['Clasificacion']."'";
	  $q_clasificacion = mysql_query($s_clasificacion) or die ($s_clasificacion.mysql_error());
	  $r_clasificacion = mysql_num_rows($q_clasificacion);
	  if($r_clasificacion!=0)$f_clasificacion = mysql_fetch_array($q_clasificacion);
	  
	  /// --- Ubicación
	  $s_ubicacion = "select Descripcion from af_ubicaciones where CodUbicacion='".$f_con_01['Ubicacion']."' ";
	  $q_ubicacion = mysql_query($s_ubicacion) or die ($s_ubicacion.mysql_error());
	  $r_ubicacion = mysql_num_rows($q_ubicacion);
	  if($r_ubicacion!=0) $f_ubicacion = mysql_fetch_array($q_ubicacion);
	  
	  /// --- Situación
	  $s_situacion = "select Descripcion from af_situacionactivo where CodSituActivo='".$f_con_01['SituacionActivo']."'";
	  $q_situacion = mysql_query($s_situacion) or die ($s_situacion.mysql_error());
	  $r_situacion = mysql_num_rows($q_situacion);
	  if($r_situacion!=0) $f_situacion=mysql_fetch_array($q_situacion);
	  
	  /// --- Contador de Activos
	  $cont_act += 1; 
	  
	 
	
	////  ---  Categoria
	$s_categoria = "select * from af_categoriadeprec where CodCategoria='".$f_con_01['Categoria']."'"; //echo $s_categoria;
    $q_categoria = mysql_query($s_categoria) or die ($s_categoria.mysql_error());
    $r_categoria = mysql_num_rows($q_categoria);
	if($r_categoria!=0)$f_categoria=mysql_fetch_array($q_categoria); 
	
	////  --- Construcion del cuerpo del reporte
	list($ft_ano, $ft_mes, $ft_dia) = split('[-]', $f_con_01['FacturaFecha']);
	
	if($periodoCapturado!=$f_con_01['PeriodoIngreso']){	
      
	  if($Pase==1){
	    /// Imprime totales Categoria
		$pdf->SetFont('Arial','B', 8); $pdf->Cell(205,4,utf8_decode('Total Categoría:'),0,0,'R');
									   $pdf->Cell(20,4,$contador_categoria,0,0,'L');
									   $pdf->Cell(20,4,'____________________',0,1,'L');
		/// Imprime Totales
		$pdf->SetFont('Arial','B', 8); $pdf->Cell(205,4,utf8_decode('Total Período:'),0,0,'R');
									   $pdf->Cell(20,4,$periodoCapturado,0,0,'L');
									   $pdf->Cell(25,4,number_format($monto,2,',','.'),0,1,'R');
		$contador_categoria=0;
		$monto = 0;	  
	  }
	  
	  $categoriaCapturada=$f_con_01['Categoria'];
	  $periodoCapturado = $f_con_01['PeriodoIngreso'];	//echo "Periodo=".$periodoCapturado;  
		/// Imprime titulo
	  $pdf->SetFont('Arial', 'B', 8); $pdf->Cell(30,4,utf8_decode('Período de Ingreso:'),0,0,'L');
									  $pdf->Cell(20,4,$f_con_01['PeriodoIngreso'],0,1,'L');
	    /// Imprime Datos de Categoria 
		$contador_categoria=1;
	  $pdf->SetFont('Arial', 'B', 8);$pdf->Cell(15,4,utf8_decode('Categoría:'), 0,0,'C');
	  $pdf->SetFont('Arial', '', 8);$pdf->Cell(70,4,utf8_decode($f_categoria['DescripcionLocal']), 0,0,'L');
	  $pdf->SetFont('Arial', 'B', 8);$pdf->Cell(12,4,'Cuenta:', 0,0,'L');
	  $pdf->SetFont('Arial', '', 8);$pdf->Cell(30,4,$f_categoria['CodCategoria'], 0,1,'L');
	    /// Imprime datos de Archivo
	  $pdf->SetFont('Arial', '', 8);$pdf->Cell(20,4,$f_con_01['Activo'], 0,0,'L');
									$pdf->Cell(100,4,substr(utf8_decode($f_con_01['Descripcion']),0,60), 0,0,'L');
									$pdf->Cell(20,4,$f_con_01['CodigoBarras'], 0,0,'C');
									$pdf->Cell(30,4,$ft_dia.'-'.$ft_mes.'-'.$ft_ano,0,0,'C');
									$pdf->Cell(30,4,$f_con_01['PeriodoInicioDepreciacion'], 0,0,'C');
									$pdf->Cell(30,4,$f_con_01['VoucherBaja'], 0,0,'C');
									$pdf->Cell(20,4,number_format($f_con_01['MontoLocal'],2,',','.'), 0,1,'R'); $pdf->Ln(1);
	  $Pase=1; 
	  $monto+=$f_con_01['MontoLocal'];
	  $montoGeneral+=$f_con_01['MontoLocal'];
	}else{
	  
	  if($categoriaCapturada==$f_con_01['Categoria']){
		  $contador_categoria+=1;
		  $montoGeneral+=$f_con_01['MontoLocal'];
	    /// Imprime datos de Archivo
	    $pdf->SetFont('Arial', '', 8);$pdf->Cell(20,4,$f_con_01['Activo'], 0,0,'L');
									$pdf->Cell(100,4,substr(utf8_decode($f_con_01['Descripcion']),0,60), 0,0,'L');
									$pdf->Cell(20,4,$f_con_01['CodigoBarras'], 0,0,'C');
									$pdf->Cell(30,4,$ft_dia.'-'.$ft_mes.'-'.$ft_ano,0,0,'C');
									$pdf->Cell(30,4,$f_con_01['PeriodoInicioDepreciacion'], 0,0,'C');
									$pdf->Cell(30,4,$f_con_01['VoucherBaja'], 0,0,'C');
									$pdf->Cell(20,4,number_format($f_con_01['MontoLocal'],2,',','.'), 0,1,'R'); $pdf->Ln(1);
		$monto+=$f_con_01['MontoLocal'];
	  }else{
		$montoGeneral+=$f_con_01['MontoLocal'];
	    /// Imprime totales Categoria
		$pdf->SetFont('Arial','B', 8); $pdf->Cell(205,4,utf8_decode('Total Categoría:'),0,0,'R');
									   $pdf->Cell(20,4,$contador_categoria,0,1,'L');
		/// Imprime Datos de Categoria 
	   $pdf->SetFont('Arial', 'B', 8);$pdf->Cell(15,4,utf8_decode('Categoría:'), 0,0,'C');
	   $pdf->SetFont('Arial', '', 8);$pdf->Cell(70,4,utf8_decode($f_categoria['DescripcionLocal']), 0,0,'L');
	   $pdf->SetFont('Arial', 'B', 8);$pdf->Cell(10,4,'Cuenta:', 0,0,'L');
	   $pdf->SetFont('Arial', '', 8);$pdf->Cell(30,4,$f_categoria['CodCategoria'], 0,1,'L');	
	   
	   /// Imprime datos de Archivo
	    $pdf->SetFont('Arial', '', 8);$pdf->Cell(20,4,$f_con_01['Activo'], 0,0,'L');
									$pdf->Cell(100,4,substr(utf8_decode($f_con_01['Descripcion']),0,60), 0,0,'L');
									$pdf->Cell(20,4,$f_con_01['CodigoBarras'], 0,0,'C');
									$pdf->Cell(30,4,$ft_dia.'-'.$ft_mes.'-'.$ft_ano,0,0,'C');
									$pdf->Cell(30,4,$f_con_01['PeriodoInicioDepreciacion'], 0,0,'C');
									$pdf->Cell(30,4,$f_con_01['VoucherBaja'], 0,0,'C');
									$pdf->Cell(20,4,number_format($f_con_01['MontoLocal'],2,',','.'), 0,1,'R'); $pdf->Ln(1);						   
		$Pase=1; $contador_categoria=1;
	  }
	}
	
	$cont_veces+=1;
	if($cont_veces==$r_con_01){
	   /// Imprime totales Categoria
		$pdf->SetFont('Arial','B', 8); $pdf->Cell(205,4,utf8_decode('Total Categoría:'),0,0,'R');
									   $pdf->Cell(20,4,$contador_categoria,0,0,'L');
									   $pdf->Cell(20,4,'____________________',0,1,'L');
		/// Imprime Totales
		$pdf->SetFont('Arial','B', 8); $pdf->Cell(205,4,utf8_decode('Total Período:'),0,0,'R');
									   $pdf->Cell(20,4,$periodoCapturado,0,0,'L');
									   $pdf->Cell(25,4,number_format($monto,2,',','.'),0,1,'R');
	    
		$pdf->SetFont('Arial','B', 8); $pdf->Cell(225,4,'',0,0,'R');
									   $pdf->Cell(20,4,'____________________',0,1,'L');
		$pdf->SetFont('Arial','B', 8); $pdf->Cell(225,4,'Monto General: ',0,0,'R');
									   $pdf->Cell(30,4,number_format($montoGeneral,2,',','.'),0,1,'R');
	}
   }


//// Muestra de totalizadores
//$pdf->Ln();
/*$pdf->SetFont('Arial','B','7');$pdf->Cell(195,1,'',0,0,'R');
							   $pdf->Cell(5,1,'',0,0,'L');
							   $pdf->Cell(10,1,'- - - - - - - - - - - - -',0,1,'L');$pdf->Ln();
$pdf->SetFont('Arial','B','7');$pdf->Cell(195,4,'TOTAL GENERAL:',0,0,'R');
							   $pdf->Cell(5,4,'',0,0,'L');
							   $pdf->Cell(10,4, number_format($Total_General,2,',','.'),0,1,'L');*/
/*$pdf->Ln(2);							   
$pdf->SetFont('Arial','B','7'); $pdf->Cell(40,4,'Nro. de Activos para el Organismo:',0,0,'L');
								$pdf->Cell(10,4,$cont_act,0,1,'C');
$pdf->SetFont('Arial','B','7'); $pdf->Cell(25,4,'Total General Activos:',0,0,'L');
								$pdf->Cell(10,4,$cont_act,0,1,'C');*/
$pdf->Output();
?>  
