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
	$this->Image('../imagenes/logos/contraloria.jpg', 10, 10, 20, 15);	
	$this->SetFont('Arial', 'B', 9);
	$this->SetXY(30, 10); $this->Cell(70, 5,utf8_decode('República Bolivariana de Venezuela'), 0, 1, 'L');
	$this->SetXY(30, 14); $this->Cell(70, 5,utf8_decode('Contraloría del Estado Monagas'), 0, 1, 'L'); 
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
	
	$sql = "select a.* from af_activo a where Estado='DE' and CodOrganismo<>'' $filtro group by Categoria"; //echo $sql;
	$qry = mysql_query($sql) or die ($sql.mysql_error());
	$field = mysql_fetch_array($qry); 
	
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
	$this->Cell(240, 3, utf8_decode('Transacciones de Baja'), 0, 1, 'C');$this->Ln(4);
	
	$this->SetFont('Arial', 'B', 8);
	  $this->Cell(130,4,'_________________________________________________________________________________________________________________________________________________________________________________________________________',0,1,'L');
		
	$this->SetDrawColor(255, 255, 255);
	//$this->SetDrawColor(0, 0, 0);
	$this->SetFillColor(255, 255, 255);
	$this->SetWidths(array(20,20,130,18,18,80,30));
	$this->SetAligns(array('C','C','C','C','C','C','C','C','C'));
	$this->Row(array('Activo', utf8_decode('Código Interno'), utf8_decode('Descripcion'), utf8_decode('Fecha Ingreso'), utf8_decode('Fecha Baja'), utf8_decode('Ubicación'), 'Monto Activo Local'));
	//$this->SetFillColor(255, 255, 255);
	
	$this->SetFont('Arial', 'B', 8);
	  $this->Cell(130,2,'_________________________________________________________________________________________________________________________________________________________________________________________________________',0,1,'L');
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
$pdf=new PDF('L','mm','LEGAL');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

//// -----------------------------------------------------------------------------------------
if($forganismo!="")$filtro.= " AND (a.CodOrganismo = '".$forganismo."')"; 
if($fDependencia!="")$filtro.="AND (a.CodDependencia='".$fDependencia."')";
if($centro_costos!="")$filtro.="AND (a.CentroCosto='".$fcentro_costos."')";
if($fCategoria!="")$filtro.="AND (a.Categoria='".$fCategoria."')";
if($festado!="") $filtro.="AND a.(Estado='".$festado."')";
if(($fperiodo_desde!="")and($fperiodo_hasta!="")) $filtro.=" AND (a.PeriodoBaja>='".$fperiodo_desde."' AND a.PeriodoBaja<='".$fperiodo_hasta."')";
if(($fvoucher_desde!="")and($fvoucher_hasta!="")) $filtro.=" AND (a.VoucherBaja>='".$fvoucher_desde."' AND VoucherBaja<='".$fvoucher_hasta."')";
if($fcuenta!="") $filtro2="and(c.CuentaHistorica='".$fcuenta."')";
if($fContabilidad!="") $cContabilidad="checked";else $dContabilidad="disabled";
//// -----------------------------------------------------------------------------------------
	$sql2 = "select Categoria from af_activo a where Estado='DE' and CodOrganismo<>'' $filtro group by Categoria order by Categoria"; //echo $sql;
	$qry2 = mysql_query($sql2) or die ($sql2.mysql_error());
	//$field2 = mysql_fetch_array($qry2);
//$num = mysql_num_rows($qry2);
while($field2=mysql_fetch_array($qry2)){
$s_con_01 = "select 
					a.*,
					c.DescripcionLocal,
					c.CuentaHistorica
			   from 
			    	af_activo a
					
					inner join af_categoriadeprec c on (c.CodCategoria=a.Categoria) $filtro2
			   where 
			        a.Estado='DE' and a.Categoria='".$field2['Categoria']."' and a.CodOrganismo<>'' $filtro 
			  order by 
			        PeriodoIngreso"; //echo $s_con_01;
$q_con_01 = mysql_query($s_con_01) or die ($s_con_01.mysql_error());
$r_con_01 = mysql_num_rows($q_con_01); //echo $r_con_01;
      $f_con_01 = mysql_fetch_array($q_con_01);
	  
	  /*$s_categoria = "select DescripcionLocal from af_categoriadeprec where CodCategoria='".$f_con_01['Categoria']."'"; //echo $s_categoria;
	  $q_categoria = mysql_query($s_categoria) or die ($s_categoria.mysql_error());
	  $r_categoria = mysql_num_rows($q_categoria);
	  $f_categoria=mysql_fetch_array($q_categoria);*/
		  
	  $pdf->SetFont('Arial', 'B', 8);$pdf->Cell(15,4,utf8_decode('Categoría:'), 0,0,'C');
	   $pdf->SetFont('Arial', '', 8);$pdf->Cell(70,4,utf8_decode($f_con_01['DescripcionLocal']), 0,0,'L');
	   $pdf->SetFont('Arial', 'B', 8);$pdf->Cell(10,4,'Cuenta:  ', 0,0,'L');
	   $pdf->SetFont('Arial', '', 8);$pdf->Cell(32,4,'  '.$f_con_01['Categoria'], 0,1,'L');	
	   
	  $s_con_01 = "select 
					a.*,
					c.DescripcionLocal,
					c.CuentaHistorica
			   from 
			    	af_activo a
					
					inner join af_categoriadeprec c on (c.CodCategoria=a.Categoria) $filtro2
			   where 
			        a.Estado='DE' and a.Categoria='".$field2['Categoria']."' and a.CodOrganismo<>'' $filtro 
			  order by 
			        PeriodoIngreso"; //echo $s_con_01;
	$q_con_01 = mysql_query($s_con_01) or die ($s_con_01.mysql_error());
	$r_con_01 = mysql_num_rows($q_con_01); //echo $r_con_01;
     // $f_con_01 = mysql_fetch_array($q_con_01);
     $monto=0;
   while($f_con_01 = mysql_fetch_array($q_con_01)){

	  /// --- Clasificacion
	  $s_clasificacion = "select Descripcion from af_clasificacionactivo where CodClasificacion='".$f_con_01['Clasificacion']."'";
	  $q_clasificacion = mysql_query($s_clasificacion) or die ($s_clasificacion.mysql_error());
	  $r_clasificacion = mysql_num_rows($q_clasificacion);
	  $f_clasificacion = mysql_fetch_array($q_clasificacion);
	  
	  /// --- Ubicación
	  $s_ubicacion = "select Descripcion from af_ubicaciones where CodUbicacion='".$f_con_01['Ubicacion']."' ";
	  $q_ubicacion = mysql_query($s_ubicacion) or die ($s_ubicacion.mysql_error());
	  $r_ubicacion = mysql_num_rows($q_ubicacion);
	  $f_ubicacion = mysql_fetch_array($q_ubicacion);
	  
	  /// --- Situación
	  $s_situacion = "select Descripcion from af_situacionactivo where CodSituActivo='".$f_con_01['SituacionActivo']."'";
	  $q_situacion = mysql_query($s_situacion) or die ($s_situacion.mysql_error());
	  $r_situacion = mysql_num_rows($q_situacion);
	   $f_situacion=mysql_fetch_array($q_situacion);
	  
	  /// --- Contador de Activos
	  $cont_act += 1; 
	  
	 
	
	////  ---  Categoria
	/*$s_categoria = "select * from af_categoriadeprec where CodCategoria='".$f_con_01['Categoria']."'"; //echo $s_categoria;
    $q_categoria = mysql_query($s_categoria) or die ($s_categoria.mysql_error());
    $r_categoria = mysql_num_rows($q_categoria);
	if($r_categoria!=0)$f_categoria=mysql_fetch_array($q_categoria); */
	
	////  --- Construcion del cuerpo del reporte
	list($ft_ano, $ft_mes, $ft_dia) = split('[-]', $f_con_01['FacturaFecha']);
	
	list($fing_ano, $fing_mes, $fing_dia) = split('[-]', $f_con_01['FechaIngreso']); //// --- Fecha factura ingreso
	$factura_ingreso = $fing_dia.'-'.$fing_mes.'-'.$fing_ano; 
	
	list($fb_ano, $fb_mes, $fb_dia) = split('[-]', $f_con_01['FechaRevisadoPor']); //// --- Fecha Baja de af_transaccionbaja
	$fecha_baja = $fb_dia.'-'.$fb_mes.'-'.$fb_ano; 
	$categoriaCapturada=$f_con_01['Categoria'];       //Categoria del Activo 
	$periodoCapturado = $f_con_01['PeriodoIngreso'];	//Periodo de ingreso del activo
	
	
	  $Descp_cat = substr(utf8_decode($f_con_01['DescripcionLocal']),0,50);
		/// Imprime titulo
	  
	    /// Imprime Datos de Categoria 
		$contador_categoria=1;

	    $pdf->SetFont('Arial', '', 8);$pdf->Cell(20,4,$f_con_01['Activo'],'L',0,'L');
									$pdf->Cell(20,4,$f_con_01['CodigoInterno'], 'L',0,'C');
									$pdf->Cell(130,4,substr(utf8_decode($f_con_01['Descripcion']),0,90), 'L',0,'L');
									$pdf->Cell(18,4,$factura_ingreso,'L',0,'C');
									//$pdf->Cell(20,4,$f_con_01['CodigoBarras'], 0,0,'C');
									$pdf->Cell(18,4,$fecha_baja, 'L',0,'C');
									$pdf->Cell(80,4,substr(utf8_decode($f_ubicacion['Descripcion']),0,60), 'L',0,'L');

									$pdf->Cell(30,4,number_format($f_con_01['MontoLocal'],2,',','.'), 'L',1,'R'); $pdf->Ln(1);	
	  $Pase=1; 
	  $monto+=$f_con_01['MontoLocal'];
	  $montoGeneral+=$f_con_01['MontoLocal'];
	

	
	

   }//$cont_veces+=1;
   $pdf->SetFont('Arial','B', 8); $pdf->Cell(177,4,utf8_decode('Total Categoría:'),0,0,'R');
									   $pdf->SetFont('Arial','', 8); $pdf->Cell(95,4,$r_con_01,0,0,'L');
									   $pdf->Cell(45,4,'______________',0,1,'R');
									   
									   $pdf->Cell(285,4,'',0,0,'R');
									   $pdf->Cell(10,4,'Total: ',0,0,'L');
									   $pdf->SetFont('Arial','B', 8);$pdf->Cell(21,4,number_format($monto,2,',','.'),0,1,'R');
   //$num-=1;
}
		$pdf->SetFont('Arial','B', 8); $pdf->Cell(282,4,'',0,0,'R');
									   $pdf->Cell(35,4,'____________________',0,1,'R');
		$pdf->SetFont('Arial','B', 10); $pdf->Cell(285,4,'Total General: ',0,0,'R');
									   $pdf->Cell(32,4,number_format($montoGeneral,2,',','.'),0,1,'R');
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
