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
	$this->Cell(240, 3, utf8_decode('Catálogo de Activos Fijos'), 0, 1, 'C');$this->Ln(4);
	
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
	$this->SetWidths(array(14,47,15,38,38,38,11,20,20,20,20,20));
	$this->SetAligns(array('C','C','C','C','C','C','C','C','C','C','C','C'));
	$this->Row(array('Activo', utf8_decode('Descripción'), utf8_decode('Cód. Barras'), utf8_decode('Categoría'), utf8_decode('Clasificación'), utf8_decode('Ubicación'), 'C. Costos','Fabricante', 'Modelo', '# Serie',utf8_decode('Situación'), 'Naturaleza'));
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
if($forganismo!="")$filtro.= " AND (CodOrganismo = '".$forganismo."')"; 
if($fDependencia!="")$filtro.="AND (CodDependencia='".$fDependencia."')";
if($centro_costos!="")$filtro.="AND (CentroCosto='".$fcentro_costos."')";
if($fNaturaleza!="")$filtro.="AND (Naturaleza='".$fNaturaleza."')";
if($fCategoria!="")$filtro.="AND (Categoria='".$fCategoria."')";
if($fubicacion!= "") $filtro.="AND (Ubicacion='".$fubicacion."')";
if($fClasificacion!="") $filtro.="AND (Clasificacion='".$fClasificacion."')";
if($fsituacion!="")$filtro.="AND (SituacionActivo='".$fSituacion."')";
if($festado!="") $filtro.="AND (Estado='".$festado."')";
if(($frango_desde!="")and($frango_hasta!="")) $filtro.="AND (Activo>='".$frango_desde."' and Activo<='".$frango_hasta."')";
//// -----------------------------------------------------------------------------------------


$s_con_01 = "select 
					*
			   from 
			    	af_activo
			   where 
			        CodOrganismo<>'' $filtro 
			  order by 
			        Activo"; //echo $s_con_01;
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
	  
	  $pdf->SetFont('Arial', '', 7);$pdf->Cell(14,4,$f_con_01['Activo'], 0,0,'C');
									$pdf->Cell(47,4,substr(utf8_decode($f_con_01['Descripcion']),0,30), 0,0,'L');
									$pdf->Cell(15,4,$f_con_01['CodigoBarras'], 0,0,'C');
									$pdf->Cell(38,4,substr(utf8_decode($f_categoria['DescripcionLocal']),0,30), 0,0,'L');
									$pdf->Cell(38,4,substr(utf8_decode($f_clasificacion['Descripcion']),0,30), 0,0,'L');
									$pdf->Cell(38,4,substr(utf8_decode($f_ubicacion['Descripcion']),0,30), 0,0,'L');
									$pdf->Cell(11,4,$f_con_01['CentroCosto'], 0,0,'C');
									$pdf->Cell(20,4,substr(utf8_decode($f_con_01['Marca']),0,15), 0,0,'L');
									$pdf->Cell(20,4,substr(utf8_decode($f_con_01['Modelo']),0,15), 0,0,'L');
									$pdf->Cell(20,4,$f_con_01['NumeroSerie'], 0,0,'C');
									$pdf->Cell(20,4,substr(utf8_decode($f_situacion['Descripcion']),0,15), 0,1,'L');
   }


//// Muestra de totalizadores
//$pdf->Ln();
/*$pdf->SetFont('Arial','B','7');$pdf->Cell(195,1,'',0,0,'R');
							   $pdf->Cell(5,1,'',0,0,'L');
							   $pdf->Cell(10,1,'- - - - - - - - - - - - -',0,1,'L');$pdf->Ln();
$pdf->SetFont('Arial','B','7');$pdf->Cell(195,4,'TOTAL GENERAL:',0,0,'R');
							   $pdf->Cell(5,4,'',0,0,'L');
							   $pdf->Cell(10,4, number_format($Total_General,2,',','.'),0,1,'L');*/
$pdf->Ln(2);							   
$pdf->SetFont('Arial','B','7'); $pdf->Cell(40,4,'Nro. de Activos para el Organismo:',0,0,'L');
								$pdf->Cell(10,4,$cont_act,0,1,'C');
$pdf->SetFont('Arial','B','7'); $pdf->Cell(25,4,'Total General Activos:',0,0,'L');
								$pdf->Cell(10,4,$cont_act,0,1,'C');
$pdf->Output();
?>  
