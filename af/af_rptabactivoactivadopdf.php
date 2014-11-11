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
	
	 $cod_personaDependencia=$fcon02['CodPersona']; //echo $cod_personaDependencia;
	
	
	
	/*$this->SetFont('Arial', '', 8);
	$this->SetXY(10,22);$this->Cell(19, 3, 'Dependencia:', 0, 0, 'L');$this->Cell(3, 3, $fcon01['Dependencia'], 0,1, 'L');
	$this->SetXY(10,25);$this->Cell(19, 3, 'Responsable:', 0, 0, 'L');$this->Cell(3, 3, $fcon02['NomCompleto'], 0, 1, 'L');
	$this->SetXY(10,28);$this->Cell(19, 3, 'Cargo:', 0, 0, 'L');$this->Cell(3, 3, $fcon02['DescripCargo'], 0, 1, 'L');*/
	 
	$this->SetFont('Arial', 'B', 9);
	$this->Cell(240, 3, utf8_decode('Activos Asignados (Activados)'), 0, 1, 'C');$this->Ln(4);
	
	$this->SetFont('Arial', '', 8);
	  $this->Cell(130,4,'__________________________________________________________________________________________________________________________________________________________________________________',0,1,'L');
	/*$this->SetDrawColor(0, 0, 0); $this->SetFillColor(200, 200, 200); $this->SetTextColor(0, 0, 0);
	$this->SetFont('Arial', 'B', 8);
	$this->Cell(17, 4, 'Voucher', 0, 0, 'C', 0);
	$this->Cell(17, 4, '#Documento', 0, 0, 'C',0);
	//$this->Cell(20, 4, utf8_decode('Fecha Adquisición'), 0, 0, 'C', 0);
	$this->MultiCell(20,4,utf8_decode('Fecha Adquisición'),0,'C',0);
	$this->MultiCell(17, 4, 'Activo', 0, 0, 'C', 0);
	$this->Cell(20, 4, 'Cód. Interno', 0, 0, 'C', 0);
	$this->Cell(17, 4, utf8_decode('Descripción'), 0, 0, 'C', 0);
	$this->Cell(90, 4, 'Nuevo', 0, 0, 'C', 0);
	$this->Cell(50, 4, 'Anterior', 0, 1, 'L', 0);*/
	
	$this->SetDrawColor(255, 255, 255);
	//$this->SetDrawColor(0, 0, 0);
	$this->SetFillColor(255, 255, 255);
	$this->SetWidths(array(20,20,20,20,20,40,20,20,20,20,20,20,20));
	$this->SetAligns(array('C','C','C','C','C','C','C','C','C','C','C','C','C'));
	$this->Row(array('Voucher', '#Documento', utf8_decode('Fecha Adquisición'), 'Activo', utf8_decode('Cód. Interno'), utf8_decode('Descripción'), 'Orden Compra', 'Serie', utf8_decode('Ubicación Física'), 'Monto Local', utf8_decode('Período Ingreso'), utf8_decode('Período Deprec.'), utf8_decode('Período Inflación')));
	//$this->SetFillColor(255, 255, 255);
	
	$this->SetFont('Arial', '', 8);
	  $this->Cell(130,2,'__________________________________________________________________________________________________________________________________________________________________________________',0,1,'L');
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
if($_POST['forganismo']!="") $filtro.= " AND (CodOrganismo = '".$forganismo."')"; 
if($_POST['fDependencia']!="") $filtro.="AND (CodDependencia='".$fDependencia."')";
if($_POST['fNaturaleza']!="") $filtro.="and(Naturaleza='".$fNaturaleza."')"; 
if(($_POST['fFechaAprobacionDesde']!="")and($_POST['fFechaAprobacionHasta']!="")) 
   $filtro.=" and FechaRevisado>='".$fFechaAprobacionDesde."' and FechaRevisado<='".$fFechaAprobacionHasta."'";


 
//if($_POST['ubicacion']!="") $filtro .= " AND (a.Ubicacion = '".$ubicacion."')";
//if($_POST['fSituacionActivo']!= "") $filtro .= " AND (SituacionActivo = '".$fSituacionActivo."')";


//if($_POST['centro_costos']!= "") $filtro .= " AND (CentroCosto = '".$centro_costos."')";
//if($_POST['fNaturaleza']!="") $filtro.=" AND (Naturaleza = '".$fNaturaleza."')"; 


//if($_POST['fActivo']!="") $filtro.=" AND (Activo = '".$fActivo."')";
//if(($_POST['fFechaAprobacionDesde']!="")and($_POST['fFechaAprobacionHasta']!="")) $filtro.=" and FechaAprobacion>='".$fFechaAprobacionDesde."' and FechaAprobacion<='".$fFechaAprobacionHasta."'";
//if(($_POST['fFechaPreparacionDesde']!="")and($_POST['fFechaPreparacionHasta']!="")) $filtro.=" and FechaPreparacion>='".$fFechaPreparacionDesde."' and FechaPreparacion<='".$fFechapreparacionHasta."'";

//if($_POST['fub_actual']!="") $filtro.=" and Ubicacion='".$fub_actual."'";
//if($_POST['fub_anterior']!="") $filtro.=" and UbicacionAnterior='".$fub_anterior."'";
//// -----------------------------------------------------------------------------------------


$s_con_01 = "select 
					*
			   from 
			    	af_activo
			   where 
			        Estado='AP' and CodOrganismo<>'' and Categoria<>'' $filtro 
			  order by 
			        Categoria"; //echo $s_con_01;
$q_con_01 = mysql_query($s_con_01) or die ($s_con_01.mysql_error());
$r_con_01 = mysql_num_rows($q_con_01); //echo $r_con_01;

if($r_con_01!=0)
   for($i=0; $i<$r_con_01; $i++){
      $f_con_01 = mysql_fetch_array($q_con_01);
	  
	  $s_categoria = "select * from af_categoriadeprec where CodCategoria='".$f_con_01['Categoria']."'"; //echo $s_categoria;
	  $q_categoria = mysql_query($s_categoria) or die ($s_categoria.mysql_error());
	  $r_categoria = mysql_num_rows($q_categoria);
	  
	  if($r_categoria!=0)$f_categoria=mysql_fetch_array($q_categoria);
	  
	  if($f_categoria['CodCategoria']!=$categoria_capturada){
		 
		 $cantidad_categorias += 1; /// Contador de activos por categoria
		 $cantidad_activos += 1; /// Contador de activos general
		 $contador_principal+= 1;
		 $valor+=1;
		 if($f_con_01['FacturaTipoDocumento']!="")$obligacion='-';
		 else $obligacion="";
		if($f_con_01['FacturaFecha']!='0000-00-00'){
		   list($f_ano, $f_mes, $f_dia) = split('[-]', $f_con_01['FacturaFecha']); $fecha_factura = $f_dia.'-'.$f_mes.'-'.$f_ano;
		 }else $fecha_factura ='';
		 
		 if($valor>=2){
		  $pdf->SetFont('Arial','B','7');$pdf->Cell(195,4,utf8_decode('Total Categoría:'),0,0,'R');
							             $pdf->Cell(5,4,$cont_activos_x_categoria,0,0,'L');
							             $pdf->Cell(8,4,'- - - - - - - - - - - - - ',0,1,'L'); 
						       
		  $pdf->SetFont('Arial','B','7');$pdf->Cell(195,4,'Total:',0,0,'R');
		  								 $pdf->Cell(30,4,number_format($monto_x_partidas,2,',','.'),0,1,'C');					   
							   $pdf->Ln();
		  $monto_x_partidas = 0;
		  $cont_activos_x_categoria = 0;
		 }
		 
		 $pdf->SetFont('Arial', 'B', 7);$pdf->Cell(20,4,utf8_decode('Categoría:'), 0,0,'C');
		 $pdf->SetFont('Arial', '', 7);$pdf->Cell(70,4,utf8_decode($f_categoria['DescripcionLocal']), 0,0,'L');
		 $pdf->SetFont('Arial', 'B', 7);$pdf->Cell(10,4,'Cuenta:', 0,0,'L');
		 $pdf->SetFont('Arial', '', 7);$pdf->Cell(30,4,$f_categoria['CodCategoria'], 0,1,'L');
										
		 $pdf->SetFont('Arial', '', 7);$pdf->Cell(20,4,$f_con_01['VoucherIngreso'], 0,0,'C');
		 								$pdf->Cell(20,4,$f_con_01['FacturaTipoDocumento'].$obligacion.$f_con_01['FacturaNumeroDocumento'], 0,0,'C');
										$pdf->Cell(20,4,$fecha_factura, 0,0,'C');
										$pdf->Cell(20,4,$f_con_01['Activo'], 0,0,'C');
										$pdf->Cell(20,4,$f_con_01['CodigoInterno'], 0,0,'C');
										$pdf->Cell(40,4,substr(utf8_decode($f_con_01['Descripcion']),0,25), 0,0,'L');
										$pdf->Cell(20,4,$f_con_01['NumeroOrden'], 0,0,'C');
										$pdf->Cell(20,4,$f_con_01['NumeroSerie'], 0,0,'C');
										$pdf->Cell(20,4,$f_con_01['Ubicacion'], 0,0,'C');
										$pdf->Cell(20,4,number_format($f_con_01['MontoLocal'],2,',','.'), 0,0,'R');
										$pdf->Cell(20,4,$f_con_01['PeriodoIngreso'], 0,0,'C');
										$pdf->Cell(20,4,$f_con_01['PeriodoInicioDepreciacion'], 0,0,'C');
										$pdf->Cell(20,4,$f_con_01['PeriodoInicioRevaluacion'], 0,1,'C'); 
		$categoria_capturada=$f_con_01['Categoria'];
		$valor+=1;
		$cont_activos_x_categoria += 1;
		$monto_x_partidas += $f_con_01['MontoLocal']; /// Sumador de Monto Activo
		$Total_General += $f_con_01['MontoLocal'];
	  }else{
		$contador_principal+= 1;  
		$monto_x_partidas += $f_con_01['MontoLocal'];
		$Total_General += $f_con_01['MontoLocal'];
		$cont_activos_x_categoria += 1;
		$valor=1;
		$cantidad_activos += 1;  
		if($f_con_01['FacturaTipoDocumento']!="")$obligacion='-';
		 else $obligacion="";
		if($f_con_01['FacturaFecha']!='0000-00-00'){
		   list($f_ano, $f_mes, $f_dia) = split('[-]', $f_con_01['FacturaFecha']); $fecha_factura = $f_dia.'-'.$f_mes.'-'.$f_ano;
		 }else $fecha_factura ='';
		 
	    $pdf->SetFont('Arial', '', 7);  $pdf->Cell(20,4,$f_con_01['VoucherIngreso'], 0,0,'C');
		 								$pdf->Cell(20,4,$f_con_01['FacturaTipoDocumento'].$obligacion.$f_con_01['FacturaNumeroDocumento'], 0,0,'C');
										$pdf->Cell(20,4,$fecha_factura, 0,0,'C');
										$pdf->Cell(20,4,$f_con_01['Activo'], 0,0,'C');
										$pdf->Cell(20,4,$f_con_01['CodigoInterno'], 0,0,'C');
										$pdf->Cell(40,4,substr(utf8_decode($f_con_01['Descripcion']),0,25), 0,0,'L');
										$pdf->Cell(20,4,$f_con_01['NumeroOrden'], 0,0,'C');
										$pdf->Cell(20,4,$f_con_01['NumeroSerie'], 0,0,'C');
										$pdf->Cell(20,4,$f_con_01['Ubicacion'], 0,0,'C');
										$pdf->Cell(20,4,number_format($f_con_01['MontoLocal'],2,',','.'), 0,0,'R');
										$pdf->Cell(20,4,$f_con_01['PeriodoIngreso'], 0,0,'C');
										$pdf->Cell(20,4,$f_con_01['PeriodoInicioDepreciacion'], 0,0,'C');
										$pdf->Cell(20,4,$f_con_01['PeriodoInicioRevaluacion'], 0,1,'C'); 
	  }
	  
	  ///echo $contador_principal;
	   if($contador_principal==$r_con_01){
		  $pdf->SetFont('Arial','B','7');$pdf->Cell(195,4,utf8_decode('Total Categoría:'),0,0,'R');
							             $pdf->Cell(5,4,$cont_activos_x_categoria,0,0,'L');
							             $pdf->Cell(8,4,'- - - - - - - - - - - - - ',0,1,'L'); 
						       
		  $pdf->SetFont('Arial','B','7');$pdf->Cell(195,4,'Total:',0,0,'R');
		  								 $pdf->Cell(30,4,number_format($monto_x_partidas,2,',','.'),0,1,'C');					   
							   $pdf->Ln();
		}
	  
	  
	  
	  /// Consultas ///
	  $s_con_02 = "select 
	  					  a.Descripcion as DescpActual,
						  b.Descripcion as DescpAnterior 
					 from 
					      af_ubicaciones a,
						  af_ubicaciones b 
					where 
					      a.CodUbicacion='".$f_con_01['Ubicacion']."' and 
						  b.CodUbicacion='".$f_con_01['UbicacionAnterior']."'";
	  $q_con_02 = mysql_query($s_con_02) or die ($s_con_02.mysql_error());
	  $r_con_02 = mysql_num_rows($q_con_02);
	  if($r_con_02!=0) $f_con_02 = mysql_fetch_array($q_con_02);
	  
	  if($f_con_01['InternoExternoFlag']=='I') $cod_maestro= 'MMOVINTER';
	  else $cod_maestro= 'MMOVINTER';
	  
	   $s_con_03 = "select Descripcion from mastmiscelaneosdet where CodMaestro='$cod_maestro'";
	   $q_con_03 = mysql_query($s_con_03) or die ($s_con_03.mysql_query());
	   $r_con_03 = mysql_num_rows($q_con_03); 
       if($r_con_03!=0)$f_con_03=mysql_fetch_array($q_con_03);	  
	 
	   $s_con_04 = "select 
	                      a.NomCompleto as NombPreparado,
						  b.NomCompleto as NombAprobado,
						  c.NomCompleto as NombEmpleadoUsuario,
						  d.NomCompleto as NombEmpleadoUsuarioAnterior 
				      from 
					       mastpersonas a,
						   mastpersonas b,
						   mastpersonas c,
						   mastpersonas d 
					  where 
					       a.CodPersona='".$f_con_01['PreparadoPor']."' and 
						   b.CodPersona='".$f_con_01['AprobadoPor']."' and 
						   c.CodPersona='".$f_con_01['EmpleadoUsuario']."' and 
						   d.CodPersona='".$f_con_01['EmpleadoUsuarioAnterior']."'";
		$q_con_04 = mysql_query($s_con_04) or die ($s_con_04.mysql_error());
		$r_con_04 = mysql_num_rows($q_con_04);
		if($r_con_04!=0)$f_con_04=mysql_fetch_array($q_con_04);
		
	   list($fano, $fmes, $fdia) = split('[-]', $f_con_01['FechaPreparacion']);
	   list($fa, $fm, $fd )= split('[-]', $f_con_01['FechaAprobacion']);
	   $FechaPreparacion = $fdia.'-'.$fmes.'-'.$fano;
	   $FechaAprobacion = $fd.'-'.$fm.'-'.$fa;
	 
	   $s_con_05 = "select 
	                      a.Descripcion as DescpCentroCostoActual,
						  b.Descripcion as DescpCentroCostoAnterior 
				      from 
					       ac_mastcentrocosto a,
						   ac_mastcentrocosto b 
					  where 
					       a.CodCentroCosto='".$f_con_01['CentroCosto']."' and 
						   b.CodCentroCosto='".$f_con_01['CentroCostoAnterior']."'";
		$q_con_05 = mysql_query($s_con_05) or die ($s_con_05.mysql_error());
		$r_con_05 = mysql_num_rows($q_con_05);
		if($r_con_05!=0) $f_con_05=mysql_fetch_array($q_con_05);
	   
	   $s_con_06 = "select 
	                      a.Dependencia as DependenciaActual,
						  b.Dependencia as DependenciaAnterior,
						  c.Organismo as OrganismoActual,
						  d.Organismo as OrganismoAnterior 
				      from 
					       mastdependencias a,
						   mastdependencias b,
						   mastorganismos c,
						   mastorganismos d 
					  where 
					       a.CodDependencia='".$f_con_01['Dependencia']."' and 
						   b.CodDependencia='".$f_con_01['DependenciaAnterior']."' and 
						   c.CodOrganismo='".$f_con_01['OrganismoActual']."' and
						   d.CodOrganismo='".$f_con_01['Organismoanterior']."'";
		$q_con_06 = mysql_query($s_con_06) or die ($s_con_06.mysql_error());
		$r_con_06 = mysql_num_rows($q_con_06);
		if($r_con_06!=0) $f_con_06=mysql_fetch_array($q_con_06);
	   
	 
	 $DescpActual = myTruncate($f_con_02['DescpActual'], '56', '', '...');
	 $DescpAnterior = myTruncate($f_con_02['DescpAnterior'], '56', '', '...');
	 $DescpCentroCostoActual = myTruncate($f_con_05['DescpCentroCostoActual'], '60', '', '...');
	 $DescpCentroCostoAnterior = myTruncate($f_con_05['DescpCentroCostoAnterior'], '60', '', '...');
	 /*$DependenciaActual = myTruncate($f_con_06['DependenciaActual'], '50', '', '...');
	 $DependenciaAnterior = myTruncate($f_con_06['DependenciaAnterior'], '50', '', '...');*/
	 
	 
	  //// primera línea  
	 /* $pdf->SetFont('Arial', 'B', 7);
		  $pdf->Cell(17,5,$f_con_01['MovimientoNumero'],0,0,'C');
		  $pdf->Cell(17,5,$f_con_01['Activo'],0,0,'C');
		  $pdf->Cell(17,5,$f_con_01['CodigoInterno'],0,0,'C');
		  $pdf->Cell(62,5,$f_con_01['Descripcion'],0,0,'L');
		  $pdf->Cell(20,5,$f_con_01['CodigoBarras'],0,0,'C');
		  $pdf->SetFont('Arial', 'B', 7);$pdf->Cell(19,5,utf8_decode('Ubicación:'),0,0,'L');
	      $pdf->SetFont('Arial', '', 7);$pdf->Cell(7,5,$f_con_01['Ubicacion'],0,0,'L');
	      $pdf->Cell(62,5,substr (utf8_decode($DescpActual),0,70),0,0,'L');
	      $pdf->SetFont('Arial', '', 7); $pdf->Cell(7,5,$f_con_01['UbicacionAnterior'],0,0,'L');
		  $pdf->Cell(62,5,substr (utf8_decode($DescpAnterior),0,70),0,1,'L');
	  
	  if($f_con_01['Naturaleza']=='AN') $Naturaleza = 'Activo Normal'; else $Naturaleza = 'Activo Menor';
	  //// Segunda Línea
	  $pdf->SetFont('Arial', 'B', 7); $pdf->Cell(35,4,'Naturaleza:',0,0,'R');
	  $pdf->SetFont('Arial', '', 7);  $pdf->Cell(30,4,$Naturaleza,0,0,'L');
	  $pdf->SetFont('Arial', 'B', 7); $pdf->Cell(16,4,utf8_decode('Cód Interno:'),0,0,'L');
	  $pdf->SetFont('Arial', '', 7);  $pdf->Cell(52,4,$f_con_01['CodigoInterno'],0,0,'L');
	  $pdf->SetFont('Arial', 'B', 7); $pdf->Cell(19,4,'Centro Costos:',0,0,'L');
	  $pdf->SetFont('Arial', '', 7);  $pdf->Cell(7,4,$f_con_01['CentroCosto'],0,0,'L');
	  								  $pdf->Cell(62,4,substr (utf8_decode($DescpCentroCostoActual),0,60),0,0,'L');
									  $pdf->Cell(7,4,$f_con_01['CentroCostoAnterior'],0,0,'L');
	  								  $pdf->Cell(62,4,substr (utf8_decode($DescpCentroCostoAnterior),0,60),0,1,'L');
	  
	  $MotivoTraslado=strtolower($f_con_03['Descripcion']);$MotivoTraslado=ucwords($MotivoTraslado); /// Conversión a Minúscula MotivoTraslado
	  $Emp_usuar_actual=strtolower($f_con_04['NombEmpleadoUsuario']); $Emp_usuar_actual=ucwords($Emp_usuar_actual);/// Conversión a Minúscula EmpUsuarActual
	  $Emp_usuar_anterior=strtolower($f_con_04['NombEmpleadoUsuarioAnterior']); $Emp_usuar_anterior=ucwords($Emp_usuar_anterior);/// Conversión a Minúscula EmpUsuarAnterior
	  //// Tercera Línea
	  $pdf->SetFont('Arial', 'B', 7);  $pdf->Cell(35,4,'Motivo de Traslado:',0,0,'R');
	  $pdf->SetFont('Arial', '', 7);   $pdf->Cell(98,4,$MotivoTraslado,0,0,'L');
	  $pdf->SetFont('Arial', 'B', 7);  $pdf->Cell(19,4,'Persona:',0,0,'L');
	  $pdf->SetFont('Arial', '', 7);   $pdf->Cell(9,4,$f_con_01['EmpleadoUsuario'],0,0,'L');
	     							   $pdf->Cell(60,4,$Emp_usuar_actual,0,0,'L');
									   $pdf->Cell(9,4,$f_con_01['EmpleadoUsuarioAnterior'],0,0,'L');
	     							   $pdf->Cell(60,4,$Emp_usuar_anterior,0,1,'L');
	  
	  $PreparadoPor = strtolower($f_con_04['NombPreparado']); $PreparadoPor = ucwords($PreparadoPor); /// Conversión a Minúscula Preparado Por
	  $DependenciaActual = strtolower($f_con_06['DependenciaActual']); $DependenciaActual = ucwords($DependenciaActual); /// Conversión a Minúscula Dependencia Actual
	  $DependenciaAnterior = strtolower($f_con_06['DependenciaAnterior']); $DependenciaAnterior = ucwords($DependenciaAnterior); /// Conversión a Minúscula Dependencia Anterior
	  //// Cuarta Línea
	  $pdf->SetFont('Arial', 'B', 7); $pdf->Cell(35,4,'Preparado Por:',0,0,'R');
	  $pdf->SetFont('Arial', '', 7);  $pdf->Cell(60,4,$PreparadoPor,0,0,'L'); $pdf->Cell(38,4,$FechaPreparacion,0,0,'L');
	  $pdf->SetFont('Arial', 'B', 7); $pdf->Cell(19,4,'Dependencia:',0,0,'L');
	  $pdf->SetFont('Arial', '', 7);  $pdf->Cell(7,4,$f_con_01['Dependencia'],0,0,'L');
	  								  $pdf->Cell(62,4,substr(utf8_decode($DependenciaActual),0,50),0,0,'L');
									  $pdf->Cell(7,4,$f_con_01['DependenciaAnterior'],0,0,'L');
	  								  $pdf->Cell(60,4,substr(utf8_decode($DependenciaAnterior),0,50),0,1,'L');
	  
	  
	  $AprobadoPor = strtolower($f_con_04['NombAprobado']); $AprobadoPor = ucwords($AprobadoPor); /// Conversión a Minúscula Aprobado Por
	  $OrganismoActual = strtolower($f_con_06['OrganismoActual']); $OrganismoActual = ucwords($OrganismoActual); /// Conversión a Minúscula Organismo Actual
	  $OrganismoAnterior = strtolower($f_con_06['OrganismoAnterior']); $OrganismoAnterior = ucwords($OrganismoAnterior); /// Conversión a Minúscula Organismo Anterior
	  //// Quinta Línea
	  $pdf->SetFont('Arial', 'B', 7); $pdf->Cell(35,4,'Aprobado Por:',0,0,'R');
	  $pdf->SetFont('Arial', '', 7);  $pdf->Cell(60,4,$AprobadoPor,0,0,'L'); $pdf->Cell(38,4,$FechaAprobacion,0,0,'L');
	  $pdf->SetFont('Arial', 'B', 7); $pdf->Cell(19,4,'Organismo:',0,0,'L');
	  $pdf->SetFont('Arial', '', 7);  $pdf->Cell(7,4,$f_con_01['OrganismoActual'],0,0,'L');
	  								  $pdf->Cell(62,4,$OrganismoActual,0,0,'L');
									  $pdf->Cell(7,4,$f_con_01['Organismoanterior'],0,0,'L');
	  								  $pdf->Cell(60,4,$OrganismoAnterior,0,1,'L');
	  */
	  
	  /*$pdf->SetFont('Arial', '', 8);
	  $pdf->Cell(130,4,'---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------',0,1,'L');*/
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  //$pdf->Rect('','',20,2,2);
	  
	  
	  
	  /*
	  $pdf->SetFont('Arial', 'B', 9);
	  $pdf->Cell(20,4,$f_con_01['Activo'],0,0,'C');
	  $pdf->Cell(20,4,$f_con_01['CodigoInterno'],0,0,'C');
	  $pdf->Cell(70,4,$f_con_01['Descripcion'],0,0,'L');
	  $pdf->Cell(20,4,$f_con_01['CodigoBarras'],0,0,'C');
	  $pdf->Cell(30,4,utf8_decode('Ubicación:'),0,0,'L');
	  $pdf->Cell(10,4,$f_con_01['Ubicacion'],0,0,'L');
	  $pdf->Cell(50,4,utf8_decode($f_con_02['Descripcion']),0,0,'L');
	  $pdf->Cell(50,4,'Ubicacion:',0,1,'C');
	  
	  
	  
	  
	  
	  /*$pdf->SetFillColor(255, 255, 255); 
	  $pdf->SetFont('Arial', 'B', 10);
	  $pdf->SetWidths(array(18,16,68,40,32,26));
	  $pdf->SetAligns(array('C','C','L','L','C','L'));
	  $pdf->Row(array($f_con_01['Activo'],$f_con_01['CodigoInterno'],utf8_decode($factivo['Descripcion']),utf8_decode($factivo['DescpClasficicacion20']),$factivo['NumeroSerie'],utf8_decode($factivo['DescpUbicacion'])));*/
   }


//// Muestra de totalizadores
//$pdf->Ln();
$pdf->SetFont('Arial','B','7');$pdf->Cell(195,1,'',0,0,'R');
							   $pdf->Cell(5,1,'',0,0,'L');
							   $pdf->Cell(10,1,'- - - - - - - - - - - - -',0,1,'L');$pdf->Ln();
$pdf->SetFont('Arial','B','7');$pdf->Cell(195,4,'TOTAL GENERAL:',0,0,'R');
							   $pdf->Cell(5,4,'',0,0,'L');
							   $pdf->Cell(10,4, number_format($Total_General,2,',','.'),0,1,'L');
							   
$pdf->SetFont('Arial','B','7'); $pdf->Cell(40,4,'Nro. de Activos para el Organismo:',0,0,'L');
								$pdf->Cell(10,4,$cantidad_activos,0,1,'C');
$pdf->SetFont('Arial','B','7'); $pdf->Cell(25,4,'Total General Activos:',0,0,'L');
								$pdf->Cell(10,4,$cantidad_activos,0,1,'C');





// ---- Consulta para obtener datos 
/*$sactivo = "select 
				  a.*, 
				  b.Descripcion as DescpClasficicacion20,
				  c.Descripcion as DescpUbicacion
			  from
				  af_activo a 
				  inner join af_clasificacionactivo20 b on (b.CodClasificacion=a.ClasificacionPublic20) 
				  inner join af_ubicaciones c on (c.CodUbicacion=a.Ubicacion)
			 where 
			      CodOrganismo<>'' $filtro"; //echo $sactivo;
$qactivo = mysql_query($sactivo) or die ($sactivo.mysql_error());
$ractivo = mysql_num_rows($qactivo);

if($ractivo!=0)
   for($i=0; $i<$ractivo; $i++){
      $factivo = mysql_fetch_array($qactivo);
	  $CodDependencia = $factivo['CodDependencia'];
	  
	  $pdf->SetFillColor(255, 255, 255); 
	  $pdf->SetFont('Arial', 'B', 8);
	  $pdf->SetWidths(array(18,16,68,40,32,26));
	  $pdf->SetAligns(array('C','C','L','L','C','L'));
	  $pdf->Row(array($factivo['Activo'],$factivo['CodigoInterno'],utf8_decode($factivo['Descripcion']),utf8_decode($factivo['DescpClasficicacion20']),$factivo['NumeroSerie'],utf8_decode($factivo['DescpUbicacion'])));
   }
   
   $scon03 = "select 
   					 CodPersona
			    from 
				     mastdependencias
				where     
					CodDependencia=(select ValorParam from mastparametros where ParametroClave='FIRMAINVENTARIODEP') and 
					CodOrganismo='".$factivo['CodOrganismo']."' ";
   $qcon03 = mysql_query($scon03) or die ($scon03.mysql_error());
   $fcon03 = mysql_fetch_array($qcon03);
   */
   function getFirma($CodPersona) {
	global $_PARAMETRO;
	$sql = "SELECT
				mp.Apellido1,
				mp.Apellido2,
				mp.Nombres,
				mp.Sexo,
				p1.DescripCargo AS Cargo,
				p2.DescripCargo AS CargoEncargado,
				p2.Grado AS GradoEncargado
			FROM
				mastpersonas mp
				INNER JOIN mastempleado me ON (mp.CodPersona = me.CodPersona)
				INNER JOIN rh_puestos p1 ON (me.CodCargo = p1.CodCargo)
				LEFT JOIN rh_puestos p2 ON (me.CodCargoTemp = p2.CodCargo)
			WHERE mp.CodPersona = '".$CodPersona."'";
	/*
	$sql = "SELECT
				mp.Busqueda,
				mp.Sexo,
				p1.DescripCargo AS Cargo,
				p2.DescripCargo AS CargoEncargado,
				p2.Grado AS GradoEncargado
			FROM
				mastpersonas mp
				INNER JOIN mastempleado me ON (mp.CodPersona = me.CodPersona)
				INNER JOIN rh_puestos p1 ON (me.CodCargo = p1.CodCargo)
				LEFT JOIN rh_puestos p2 ON (me.CodCargoTemp = p2.CodCargo)
			WHERE mp.CodPersona = '".$CodPersona."'";
	*/
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	##
	list($Nombre) = split("[ ]", $field['Nombres']);
	if ($field['Apellido1'] != "") $Apellido = $field['Apellido1']; else $Apellido = $field['Apellido2'];
	$NomCompleto = "$Nombre $Apellido";
	##
	if ($field['CargoEncargado'] != "") {
		if ($field['GradoEncargado'] == "99" && $_PARAMETRO['PROV99'] == $CodPersona) $tmp = "(P)"; else $tmp = "(E)";
		$Cargo = $field['CargoEncargado'];
	}
	else { $Cargo = $field['Cargo']; $tmp = ""; }
	##
	$Cargo = str_replace("(A)", "", $Cargo);
	if ($field['Sexo'] == "M") {
	} else {
		$Cargo = str_replace("JEFE", "JEFA", $Cargo);
		$Cargo = str_replace("DIRECTOR", "DIRECTORA", $Cargo);
		$Cargo = str_replace("CONTRALOR", "CONTRALORA", $Cargo);
	}
	/*
	if ($field['Sexo'] == "M") {
		$Cargo = str_replace("JEFE (A)", "JEFE", $Cargo);
		$Cargo = str_replace("DIRECTOR (A)", "DIRECTOR $tmp", $Cargo);
		$Cargo = str_replace("CONTRALOR (A)", "CONTRALOR $tmp", $Cargo);
	} else {
		$Cargo = str_replace("JEFE (A)", "JEFA", $Cargo);
		$Cargo = str_replace("DIRECTOR (A)", "DIRECTORA $tmp", $Cargo);
		$Cargo = str_replace("CONTRALOR (A)", "CONTRALORA $tmp", $Cargo);
	}
	*/
	##	consulto el nivel de instruccion
	$sql = "SELECT
				ei.Nivel,
				ngi.AbreviaturaM,
				ngi.AbreviaturaF
			FROM
				rh_empleado_instruccion ei
				INNER JOIN rh_nivelgradoinstruccion ngi ON (ngi.CodGradoInstruccion = ei.CodGradoInstruccion AND
														    ngi.Nivel = ei.Nivel)
			WHERE
				ei.CodPersona = '".$CodPersona."' AND
				ei.FechaGraduacion = (SELECT MAX(ei2.Fechagraduacion) FROM rh_empleado_instruccion ei2 WHERE ei2.CodPersona = ei.CodPersona)";
	$query_nivel = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_nivel) != 0) $field_nivel = mysql_fetch_array($query_nivel);
	if ($field['Sexo'] == "M") $nivel = $field_nivel['AbreviaturaM']; else $nivel = $field_nivel['AbreviaturaF'];
	##
	return array($NomCompleto, $Cargo.$tmp, $nivel);
     }
	 list($nombreCompleto, $cargo, $nivel) = getfirma($fcon03['CodPersona']);
	 
	 $scon04 = "select 
	                  CodPersona 
			     from 
				      mastdependencias 
				where 
				      CodDependencia='$CodDependencia'";
	 $qcon04 = mysql_query($scon04) or die ($scon04.mysql_error());
	 $fcon04 = mysql_fetch_array($qcon04);
	
	 
     list($nombreCompleto02, $cargo02, $nivel02) = getfirma($fcon04['CodPersona']);
   /*
    $pdf->Cell(20,10,'Total de Bienes: '.$ractivo,0,1,'L');
$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(202, 202, 202); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(80,5,'_____________________________',0,0,'C');$pdf->Cell(100,5,'RECIBI CONFORME: _____________________________',0,1,'C');
	$pdf->Cell(80,2,$nivel.' '.$nombreCompleto,0,0,'C');    $pdf->Cell(127,2,$nivel02.' '.$nombreCompleto02,0,1,'C');
	$pdf->Cell(80,3,$cargo,0,0,'C');
	$pdf->Cell(25,3,'',0,0,'C');                             $pdf->MultiCell(80,3,$cargo02,0,'C');*/
$pdf->Output();
?>  
