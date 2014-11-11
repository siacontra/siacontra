<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');
require('fphp.php');
connect(); 
extract ($_POST);
extract ($_GET);
$Periodo= $_POST['fperiodo'];
global $Periodo;
//echo $_SESSION["MYSQL_BD"];
/// ----------------------------------------------------
//---------------------------------------------------
$filtro=strtr($filtro, "*", "'");
$filtro2=strtr($filtro2, "*", "'");
$filtro3=strtr($filtro3, "*", "'");
//$Periodo = $Periodo;
//$filtro=strtr($filtro, ";", "%");
//---------------------------------------------------
//---------------------------------------------------
//echo $Periodo;

class PDF extends FPDF
{
//Page header
function Header(){
    
	global $Periodo;
	$this->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$this->SetFont('Arial', 'B', 8);
	$this->SetXY(20, 10); $this->Cell(200, 5,utf8_decode( 'Contraloría del Estado Monagas'), 0, 0, 'L');
	                      $this->Cell(10,5,'Fecha:',0,0,'');$this->Cell(10,5,date('d/m/Y'),0,1,'');
	$this->SetXY(20, 15); $this->Cell(200, 5, utf8_decode('Dirección de Administración y Presupuesto'), 0, 0, 'L');
	                       $this->Cell(10,5,utf8_decode('Página:'),0,1,'');
	$this->SetXY(19, 20); $this->Cell(204, 5, '', 0, 0, 'L');
	                       $this->Cell(7,5,utf8_decode('Año:'),0,0,'L');$this->Cell(6,5,date('Y'),0,1,'L');
						   
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
	$this->SetFont('Arial', 'B', 10);
	$this->Cell(105, 10, '', 0, 0, 'C');
	$this->Cell(47, 10, utf8_decode('Ejecución Presupuestaria'), 0, 0, 'C');
    $this->Cell(13, 10, $mes, 0, 0, 'C'); $this->Cell(13, 10, $fano, 0, 1, 'C');
	///// PRUEBA ***********
	$this->SetFont('Arial', 'B', 8);
	
	$sql =  "SELECT Sector,Programa,SubPrograma,Proyecto,Actividad,Organismo,CodPresupuesto,UnidadEjecutora 
	                    FROM pv_presupuesto 
					   WHERE Organismo<>'' $filtro";
	$qry=mysql_query($sql) or die ($sql.mysql_error());
	$field=mysql_fetch_array($qry);
	// Sector
	$sqlSector="SELECT descripcion,cod_sector FROM pv_sector WHERE cod_sector='".$field[0]."'";
	$qrySector=mysql_query($sqlSector) or die ($sqlSector.mysql_error());
	$fieldSector=mysql_fetch_array($qrySector);
	// Programa
	$sqlPrograma="SELECT descp_programa,cod_programa FROM pv_programa1 WHERE id_programa='".$field[1]."'";
	$qryPrograma=mysql_query($sqlPrograma) or die ($sqlPrograma.mysql_error());
	$fieldPrograma=mysql_fetch_array($qryPrograma);
	// SubPrograma
	$sqlSubprog="SELECT descp_subprog,cod_subprog FROM pv_subprog1 WHERE id_sub='".$field[2]."'";
	$qrySubprog=mysql_query($sqlSubprog) or die ($sqlSubprog.mysql_error());
	$fieldSubprog=mysql_fetch_array($qrySubprog);
	// Proyecto
	$sqlProyecto="SELECT descp_proyecto,cod_proyecto FROM pv_proyecto1 WHERE id_proyecto='".$field[3]."'";
	$qryProyecto=mysql_query($sqlProyecto) or die ($sqlProyecto.mysql_error());
	$fieldProyecto=mysql_fetch_array($qryProyecto);
	// Actividad
	$sqlActividad="SELECT descp_actividad,cod_actividad FROM pv_actividad1 WHERE id_actividad='".$field[4]."'";
	$qryActividad=mysql_query($sqlActividad) or die ($sqlActividad.mysql_error());
	$fieldActividad=mysql_fetch_array($qryActividad);
	// Organismo o Unidad Ejecutora
	$sqlOrg="SELECT Organismo FROM mastorganismos WHERE CodOrganismo='".$field[5]."'";
	$qryOrg=mysql_query($sqlOrg) or die ($sqlOrg.mysql_error());
	$fieldOrg=mysql_fetch_array($qryOrg);
	
        //echo $Periodo.'-';

if($fmes=='01'){
	
	$this->SetFont('Arial', '', 7);
	$this->Cell(27, 3, 'SECTOR:', 0, 0, 'L');$this->Cell(3, 3, $fieldSector[1], 0, 0, 'L');$this->Cell(30, 3, $fieldSector['descripcion'], 0, 1, 'L');
	$this->Cell(27, 3, 'PROGRAMA:', 0, 0, 'L');$this->Cell(3, 3, $fieldPrograma[1], 0, 0, 'L');$this->Cell(30, 3, $fieldPrograma[0], 0, 1, 'L');
	$this->Cell(27, 3, 'SUBPROGRAMA:', 0, 0, 'L');$this->Cell(3, 3, $fieldSubprog[1], 0, 0, 'L');$this->Cell(30, 3, $fieldSubprog[0], 0, 1, 'L');
	$this->Cell(27, 3, 'PROYECTO:', 0, 0, 'L');$this->Cell(3, 3, $fieldProyecto[1], 0, 0, 'L');$this->Cell(30, 3, $fieldProyecto[0], 0, 1, 'L');
	$this->Cell(27, 3, 'ACTIVIDAD:', 0, 0, 'L');$this->Cell(3, 3, $fieldActividad[1], 0, 0, 'L');$this->Cell(30, 3, $fieldActividad[0], 0, 1, 'L');
	$this->Cell(27, 3, 'UNIDAD EJECUTORA:', 0, 0, 'L');$this->Cell(30, 3, $fieldOrg[0], 0, 1, 'L');
	$this->SetDrawColor(0, 0, 0); $this->SetFillColor(200, 200, 200); $this->SetTextColor(0, 0, 0);
	$this->SetFont('Arial', 'B', 6);
	$this->Cell(18, 3, 'PAR GE ESP SE', 1, 0, 'C', 1);
	$this->Cell(80, 3, 'DENOMINACION', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'PRESUP. DE LEY.', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'AUMENTO', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'DISMINUCION', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'CRED. ADICIONAL', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'PRESUP. AJUST', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'COMPROMETIDO', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'CAUSADO', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'PAGADO', 1, 0, 'C', 1);
        $this->Cell(20, 3, 'REINTEGRO', 1, 0, 'C', 1);
        $this->Cell(20, 3, 'DISP. PRESUP.', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'DISP. FINAN.', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'VARIACION', 1, 1, 'C', 1);
	$this->SetFillColor(255, 255, 255);
	///// ******************
}else{
    $this->SetFont('Arial', '', 7);
	$this->Cell(27, 3, 'SECTOR:', 0, 0, 'L');$this->Cell(3, 3, $fieldSector[1], 0, 0, 'L');$this->Cell(30, 3, $fieldSector['descripcion'], 0, 1, 'L');
	$this->Cell(27, 3, 'PROGRAMA:', 0, 0, 'L');$this->Cell(3, 3, $fieldPrograma[1], 0, 0, 'L');$this->Cell(30, 3, $fieldPrograma[0], 0, 1, 'L');
	$this->Cell(27, 3, 'SUBPROGRAMA:', 0, 0, 'L');$this->Cell(3, 3, $fieldSubprog[1], 0, 0, 'L');$this->Cell(30, 3, $fieldSubprog[0], 0, 1, 'L');
	$this->Cell(27, 3, 'PROYECTO:', 0, 0, 'L');$this->Cell(3, 3, $fieldProyecto[1], 0, 0, 'L');$this->Cell(30, 3, $fieldProyecto[0], 0, 1, 'L');
	$this->Cell(27, 3, 'ACTIVIDAD:', 0, 0, 'L');$this->Cell(3, 3, $fieldActividad[1], 0, 0, 'L');$this->Cell(30, 3, $fieldActividad[0], 0, 1, 'L');
	$this->Cell(27, 3, 'UNIDAD EJECUTORA:', 0, 0, 'L');$this->Cell(30, 3, $fieldOrg[0], 0, 1, 'L');
	$this->SetDrawColor(0, 0, 0); $this->SetFillColor(200, 200, 200); $this->SetTextColor(0, 0, 0);
	$this->SetFont('Arial', 'B', 6);
	$this->Cell(18, 3, 'PAR GE ESP SE', 1, 0, 'C', 1);
	$this->Cell(80, 3, 'DENOMINACION', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'PRESUP. DE LEY.', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'PRESUP. AJUST', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'AUMENTO', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'DISMINUCION', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'CRED. ADICIONAL', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'SALDO. AJUST.', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'D.P MES ANTE.', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'D.F MES ANTE.', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'COMPROMETIDO', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'CAUSADO', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'PAGADO', 1, 0, 'C', 1);
        $this->Cell(20, 3, 'REINTEGRO', 1, 0, 'C', 1);
        $this->Cell(20, 3, 'DISP. PRESUP.', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'DISP. FINAN.', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'VARIACION', 1, 1, 'C', 1);
	$this->SetFillColor(255, 255, 255);
	///// ******************
}
}
//Page footer
function Footer(){
    //Position at 1.5 cm from bottom
    $this->SetXY(125,13);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,10,' '.$this->PageNo().'/{nb}',0,0,'C');
}
}


list($ano, $mes) = split('[-]', $Periodo);
//Instanciation of inherited class
if($mes=='01'){
$pdf=new PDF('L','mm',array('210','360'));
}else{
$pdf=new PDF('L','mm',array('210','414'));
}
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

//echo $Periodo.'-';

if($mes=='01'){
$sqlCon = "select 
                 CodPresupuesto,
				 Organismo 
		    from 
			     pv_presupuesto 
		    where
				Organismo<>'' $filtro"; //echo $sqlCon;
$qryCon = mysql_query($sqlCon) or die ($sqlCon.mysql_error());
$rowCon = mysql_num_rows($qryCon); //echo $rowCon;
$fieldCon = mysql_fetch_array($qryCon);

$sqlDet="SELECT cod_partida,MontoAprobado,
                partida,generica,especifica,
			    subespecifica,tipocuenta,CodPresupuesto 
		   FROM 
		        pv_presupuestodet 
		  WHERE 
		        CodPresupuesto='".$fieldCon['CodPresupuesto']."' and 
				Organismo = '".$fieldCon['Organismo']."'
		  ORDER BY cod_partida"; //echo $sqlDet;
$qryDet=mysql_query($sqlDet) or die ($sqlDet.mysql_error());
$rows=mysql_num_rows($qryDet);
for($i=0; $i<$rows ; $i++){
 $fieldet=mysql_fetch_array($qryDet);
 //// **** **** **** **** Capturando Partida Tipo "T" 301-00-00-00
 $montoIncremento1 = 0;$montoCredito1 = 0;$montoCreditoT1= 0;$montoReintegro1 = 0;$montoReintegroTotal = 0; $montoDisminucion1 = 0; $montoIncrementoTotal1 = 0; $montoDisminucionTotal1 = 0;
 $montoCompromiso1 = 0; $montoCompromisoTotal1 = 0; $montoPptoAjustado1 = 0;
 $montoCausado1 = 0; $montoCausadoTotal1 = 0;
 $montoPagado1 = 0; $montoPagadoTotal1 = 0;
 if(($fieldet['partida']!=00) and (($cont1==0) or ($pCapturada!=$fieldet['partida']))){
  $sqlPar="SELECT cod_partida,partida1,denominacion,cod_tipocuenta 
			 FROM pv_partida 
			WHERE partida1='".$fieldet['partida']."' AND 
			      cod_tipocuenta='".$fieldet['tipocuenta']."' AND 
				  tipo='T' AND 
				  generica='00'";
  $qryPar=mysql_query($sqlPar) or die ($sqlPar.mysql_error());
  $rwPar=mysql_num_rows($qryPar);//$pdf->Cell(5, 3.5, $rwPar);
  if($rwPar!=0){
   $fpar=mysql_fetch_array($qryPar);
   $montoP=0; $cont1=0; $montoConsulta01=0;
   $sqldet="SELECT MontoAprobado, cod_partida 
		      FROM pv_presupuestodet 
		     WHERE partida='".$fpar['partida1']."' AND 
			       tipocuenta='".$fpar['cod_tipocuenta']."' AND 
				   CodPresupuesto='".$fieldet['CodPresupuesto']."'";
   $qrydet=mysql_query($sqldet) or die ($sqldet.mysql_error());
   $rwdet=mysql_num_rows($qrydet);
   for($a=0; $a<$rwdet; $a++){/*$pdf->Cell(5, 3.5,$rwdet);$pdf->Cell(5, 3.5,$a);*/
    $fdet=mysql_fetch_array($qrydet);
    $cont1 = $cont1 + 1;
    $montoP = $montoP + $fdet['MontoAprobado'];
	/// - Consulta de partida incrementada o con ajuste positivo
	/// ************************************************************
	 $s_consulta01 = "select 
						  aj.CodAjuste,
						  aj.TipoAjuste,
						  aj.Periodo,
						  ajdet.cod_partida,
						  ajdet.MontoAjuste
					  from 				   
							pv_ajustepresupuestario aj
                            inner join pv_ajustepresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodAjuste=ajdet.CodAjuste))
					where 
					       aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   aj.Periodo = '$Periodo' and 
						   aj.Estado = 'AP' and
						   ajdet.cod_partida='".$fdet['cod_partida']."' and aj.MotivoAjuste<>'CR'";	//echo $s_consulta03;				   
						   
	  $q_consulta01 = mysql_query($s_consulta01) or die ($s_consulta01.mysql_error());
	  $r_consulta01 = mysql_num_rows($q_consulta01);
	/// - Consulta de partida incrementada por Reintegro
	/// ************************************************************
	 	
      for($c=0; $c<$r_consulta01; $c++){	
	     $f_consulta01 = mysql_fetch_array($q_consulta01); 
	     if($f_consulta01['TipoAjuste']=='IN'){
	       $montoIncremento1 = $f_consulta01['MontoAjuste'] + $montoIncremento1;
	     }else{ 
	       $montoDisminucion1 = $f_consulta01['MontoAjuste'] + $montoDisminucion1;
		 }
	  }
          
	/// - Consulta de partida incrementada o con ajuste positivo
	/// ************************************************************
	 $s_consulta011 = "select 
						  aj.CodAjuste,
						  aj.TipoAjuste,
						  aj.Periodo,
						  ajdet.cod_partida,
						  ajdet.MontoAjuste
					  from 				   
							pv_ajustepresupuestario aj
                            inner join pv_ajustepresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodAjuste=ajdet.CodAjuste))
					where 
					       aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   aj.Periodo = '$Periodo' and 
						   aj.Estado = 'AP' and
						   ajdet.cod_partida='".$fdet['cod_partida']."' and aj.MotivoAjuste='CR'";	//echo $s_consulta03;				   
						   
	  $q_consulta011 = mysql_query($s_consulta011) or die ($s_consulta011.mysql_error());
	  $r_consulta011 = mysql_num_rows($q_consulta011);
	/// - Consulta de partida incrementada por Reintegro
	/// ************************************************************
	 	
      for($c=0; $c<$r_consulta011; $c++){	
	     $f_consulta011 = mysql_fetch_array($q_consulta011); 
	     
	       $montoCredito1 = $f_consulta011['MontoAjuste'] + $montoCredito1;
	     
	  }
          
          $R_consulta01 = "select 
						  aj.CodReintegro,
						  aj.Periodo,
						  ajdet.cod_partida,
						  ajdet.MontoReintegro
					  from 				   
							pv_ReintegroPresupuestario aj
                            inner join pv_ReintegroPresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodReintegro=ajdet.CodReintegro))
					where 
					       aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   aj.Periodo = '$Periodo' and 
						   aj.Estado = 'AP' and
						   ajdet.cod_partida='".$fdet['cod_partida']."'";	//echo $s_consulta03;				   
						   
	  $qR_consulta01 = mysql_query($R_consulta01) or die ($R_consulta01.mysql_error());
	  $rR_consulta01 = mysql_num_rows($qR_consulta01);
          
          for($c=0; $c<$rR_consulta01; $c++){	
	     $fR_consulta01 = mysql_fetch_array($qR_consulta01); 
	     
	       $montoReintegro1 = $fR_consulta01['MontoReintegro'] + $montoReintegro1;

	  }
	  $montoCreditoT1 = number_format($montoCredito1,2,',','.');
	  $montoReintegroTotal = number_format($montoReintegro1,2,',','.');
	  $montoIncrementoTotal1 = number_format($montoIncremento1,2,',','.');
	  $montoDisminucionTotal1 = number_format($montoDisminucion1,2,',','.');
	/// MONTO COMPROMISO
	  list($ano, $mes) = SPLIT('[-]', $Periodo);
	  $s_compromiso1 = "select
	                         Monto 
						from
						     lg_distribucioncompromisos 
						where 
						      Estado <> 'AN' and 
							  Periodo = '$Periodo' and 
							  cod_partida = '".$fdet['cod_partida']."'";
	  $q_compromiso1 = mysql_query($s_compromiso1) or die ($s_compromiso1.mysql_error());
	  $r_compromiso1 = mysql_num_rows($q_compromiso1);
	  
	  for($c=0;$c<$r_compromiso1; $c++){
		$f_compromiso1 = mysql_fetch_array($q_compromiso1);
	    $montoCompromiso1 = $montoCompromiso1 + $f_compromiso1['Monto'];
	  }
	  
	  $montoCompromisoTotal1 = number_format($montoCompromiso1,2,',','.');
	/// ************************************************************
	/// MONTO CAUSADO
	 $s_causado1 = "select 
	                     *
					 from
					     ap_distribucionobligacion
					 where 
					     Estado<>'AN' and 
						 Periodo = '$Periodo' and 
						 cod_partida = '".$fdet['cod_partida']."'";
	$q_causado1 = mysql_query($s_causado1) or die ($s_causado1.mysql_error());
	$r_causado1 = mysql_num_rows($q_causado1);
	
	for($c=0; $c<$r_causado1; $c++){
	  $f_causado1 = mysql_fetch_array($q_causado1);
	  $montoCausado1 =  $montoCausado1 + $f_causado1['Monto'];
	}  
	  $montoCausadoTotal1  = number_format($montoCausado1,2,',','.');
	/// ************************************************************
	/// MONTO PAGADO
	 $s_pagado1 = "select
	                       *
					  from 
					       ap_ordenpagodistribucion 
					 where
					       Estado = 'PA' and 
						   Periodo = '$Periodo' and 
						   cod_partida = '".$fdet['cod_partida']."'";
	  $q_pagado1 = mysql_query($s_pagado1) or die ($s_pagado1.mysql_error());
	  $r_pagado1 = mysql_num_rows($q_pagado1);
	  
	  for($c=0; $c<$r_pagado1; $c++){
	    $f_pagado1 = mysql_fetch_array($q_pagado1);
		$montoPagado1 = $montoPagado1 + $f_pagado1['Monto'];
	  }
	    $montoPagadoTotal1 = number_format($montoPagado1,2,',','.');
	 /// ************************************************************
   }
    /// ************ Calculo Monto Ajustado / Disponibilidad Presupuestaria / Disponibilidad Financiera
	$montoPptoAjustado1 = ($montoP  + $montoIncremento1) - $montoDisminucion1 + $montoCredito1;
	$montoDisponPresupuestaria1 = ($montoPptoAjustado1 - $montoCompromiso1) + $montoReintegro1;
	$montoDisponFinanciera1 = ($montoPptoAjustado1 - $montoPagado1 )+ $montoReintegro1;
	$montoVariacion1 = $montoDisponFinanciera1 - $montoDisponPresupuestaria1;
	
	$montoPptoAjustado1 = number_format($montoPptoAjustado1,2,',','.');
	$montoDisponPresupuestaria1 = number_format($montoDisponPresupuestaria1,2,',','.');
	$montoDisponFinanciera1 = number_format($montoDisponFinanciera1,2,',','.');
	$montoVariacion1 = number_format($montoVariacion1,2,',','.');
	/// ************ 
	
    $montoPar=number_format($montoP,2,',','.');
	$codigo_partida = $fpar['cod_partida'];
	$pCapturada = $fpar['partida1'];
	/// Monto Incrementado
	$montoInc = number_format($montoConsulta01,2,',','.');
	///**** mostrando los resultados para partida 
	$pdf->SetFillColor(202, 202, 202); 
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array(18, 80,20,20,20,20,20,20,20,20,20,20,20,20));
	$pdf->SetAligns(array('C','L','R','R','R','R','R','R','R','R','R','R','R','R','R','R'));
	$pdf->Row(array($codigo_partida,$fpar['denominacion'],$montoPar,$montoIncrementoTotal1,$montoDisminucionTotal1,$montoCreditoT1,$montoPptoAjustado1,$montoCompromisoTotal1,$montoCausadoTotal1,$montoPagadoTotal1,$montoReintegroTotal,$montoDisponPresupuestaria1,$montoDisponFinanciera1,$montoVariacion1));
  }
 }
 //// **** **** **** Capturando Partida Tipo "T" 301-01-00-00
 $montoIncremento2 = 0;$montoCredito2=0;$montoCreditoT2=0;$montoReintegro2 = 0;$montoReintegroTotal2 = 0; $montoDisminucion2 = 0; $montoIncrementoTotal2 = 0; $montoDisminucionTotal2 = 0;
 $montoCompromiso2 = 0; $montoCompromisoTotal2 = 0;
 $montoCausado2 = 0; $montoCausadoTotal2 = 0;
 $montoPagado2 = 0; $montoPagadoTotal2 = 0;	
 if(($fieldet['partida']!=00) and (($cont2==0) or ($gCapturada!=$fieldet['generica']) or ($pCapturada2!=$fieldet['partida']))){
    $sql2="SELECT cod_partida,partida1,denominacion,cod_tipocuenta,generica,tipo 
			    FROM pv_partida 
			   WHERE partida1='".$fieldet['partida']."' AND
				     cod_tipocuenta='".$fieldet['tipocuenta']."' AND
				     tipo='T' AND 
					 generica='".$fieldet['generica']."' AND 
					 especifica='00'";
	$qry2=mysql_query($sql2) or die ($sql2.mysql_error());
	$rows2=mysql_num_rows($qry2);//$pdf->Cell(5,3.5,$rwPar2);
	if($rows2!=0){
	  $fpar2=mysql_fetch_array($qry2);
	  $montoG=0; $cont2=0; $montoConsulta02=0;
	  $sqldet2="SELECT MontoAprobado, cod_partida 
			      FROM pv_presupuestodet 
			     WHERE partida='".$fpar2['partida1']."' AND 
				       generica='".$fpar2['generica']."' AND 
					   tipocuenta='".$fpar2['cod_tipocuenta']."' AND 
				       CodPresupuesto='".$fieldet['CodPresupuesto']."'";
	  $qrydet2=mysql_query($sqldet2) or die ($sqldet2.mysql_error());
	  $rwdet2=mysql_num_rows($qrydet2);
	  for($b=0; $b<$rwdet2; $b++){
	   $fdet2=mysql_fetch_array($qrydet2);
	   $cont2 = $cont2 + 1;
	   $montoG = $montoG + $fdet2['MontoAprobado'];
	   
	   /// ************************************************************
	   $s_consulta02 = "select 
						  aj.CodAjuste,
						  aj.TipoAjuste,
						  aj.Periodo,
						  ajdet.cod_partida,
						  ajdet.MontoAjuste
					  from 				   
							pv_ajustepresupuestario aj
                            inner join pv_ajustepresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodAjuste=ajdet.CodAjuste))
					where 
					       aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   aj.Periodo = '$Periodo' and 
						   aj.Estado = 'AP' and
						   ajdet.cod_partida='".$fdet2['cod_partida']."' and aj.MotivoAjuste<>'CR'";	//echo $s_consulta03;				   
						   
	  $q_consulta02 = mysql_query($s_consulta02) or die ($s_consulta02.mysql_error());
	  $r_consulta02 = mysql_num_rows($q_consulta02);
	
      for($c=0; $c<$r_consulta02; $c++){	
	     $f_consulta02 = mysql_fetch_array($q_consulta02); 
	     if($f_consulta02['TipoAjuste']=='IN'){
	       $montoIncremento2 = $f_consulta02['MontoAjuste'] + $montoIncremento2;
	     }else{ 
	       $montoDisminucion2 = $f_consulta02['MontoAjuste'] + $montoDisminucion2;
		 }
	  }
	   /// ************************************************************
	   $s_consulta022 = "select 
						  aj.CodAjuste,
						  aj.TipoAjuste,
						  aj.Periodo,
						  ajdet.cod_partida,
						  ajdet.MontoAjuste
					  from 				   
							pv_ajustepresupuestario aj
                            inner join pv_ajustepresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodAjuste=ajdet.CodAjuste))
					where 
					       aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   aj.Periodo = '$Periodo' and 
						   aj.Estado = 'AP' and
						   ajdet.cod_partida='".$fdet2['cod_partida']."' and aj.MotivoAjuste='CR'";	//echo $s_consulta03;				   
						   
	  $q_consulta022 = mysql_query($s_consulta022) or die ($s_consulta022.mysql_error());
	  $r_consulta022 = mysql_num_rows($q_consulta022);
	
      for($c=0; $c<$r_consulta022; $c++){	
	     $f_consulta02 = mysql_fetch_array($q_consulta022); 
	     if($f_consulta022['TipoAjuste']=='IN'){
	       $montoCredito2 = $f_consulta022['MontoAjuste'] + $montoCredito2;
	     }
	  }
	   /// ************************************************************
	   $sR_consulta02 = "select 
						  aj.CodReintegro,
						  aj.Periodo,
						  ajdet.cod_partida,
						  ajdet.MontoReintegro
					  from 				   
							pv_ReintegroPresupuestario aj
                            inner join pv_ReintegroPresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodReintegro=ajdet.CodReintegro))
					where 
					       aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   aj.Periodo = '$Periodo' and 
						   aj.Estado = 'AP' and
						   ajdet.cod_partida='".$fdet2['cod_partida']."'";	//echo $s_consulta03;				   
						   
	  $qR_consulta02 = mysql_query($sR_consulta02) or die ($sR_consulta02.mysql_error());
	  $rR_consulta02 = mysql_num_rows($qR_consulta02);
	
      for($c=0; $c<$rR_consulta02; $c++){	
	     $fR_consulta02 = mysql_fetch_array($qR_consulta02); 
	       
             $montoReintegro2 = $fR_consulta02['MontoReintegro'] + $montoReintegro2;
	     
	  }
	  $montoCreditoT2 = number_format($montoCredito2,2,',','.');
	  $montoReintegroTotal2 = number_format($montoReintegro2,2,',','.');
	  $montoIncrementoTotal2 = number_format($montoIncremento2,2,',','.');
	  $montoDisminucionTotal2 = number_format($montoDisminucion2,2,',','.');	
	   /// ************************************************************
	   /// MONTO COMPROMETIDO
	   list($ano, $mes) = SPLIT('[-]', $Periodo);
	  $s_compromiso2 = "select
	                         Monto 
						from
						     lg_distribucioncompromisos 
						where 
						      Estado <> 'AN' and 
							  Periodo = '$Periodo' and 
							  cod_partida = '".$fdet2['cod_partida']."'";
	  $q_compromiso2 = mysql_query($s_compromiso2) or die ($s_compromiso2.mysql_error());
	  $r_compromiso2 = mysql_num_rows($q_compromiso2);
	  
	  for($c=0;$c<$r_compromiso2; $c++){
		$f_compromiso2 = mysql_fetch_array($q_compromiso2);
	    $montoCompromiso2 = $montoCompromiso2 + $f_compromiso2['Monto'];
	  }
	  
	  $montoCompromisoTotal2 = number_format($montoCompromiso2,2,',','.');
	   /// ************************************************************
	   /// MONTO CAUSADO
	   $s_causado2 = "select 
							 *
						 from
							 ap_distribucionobligacion
						 where 
							 Estado<>'AN' and 
							 Periodo = '$Periodo' and 
							 cod_partida = '".$fdet2['cod_partida']."'";
		$q_causado2 = mysql_query($s_causado2) or die ($s_causado2.mysql_error());
		$r_causado2 = mysql_num_rows($q_causado2);
		
		for($c=0; $c<$r_causado2; $c++){
		  $f_causado2 = mysql_fetch_array($q_causado2);
		  $montoCausado2 =  $montoCausado2 + $f_causado2['Monto'];
		}  
		  $montoCausadoTotal2  = number_format($montoCausado2,2,',','.');
	   /// ************************************************************
	   /// MONTO PAGADO
	   $s_pagado2 = "select
	                       *
					  from 
					       ap_ordenpagodistribucion 
					 where
					       Estado = 'PA' and 
						   Periodo = '$Periodo' and 
						   cod_partida = '".$fdet2['cod_partida']."'";
	   $q_pagado2 = mysql_query($s_pagado2) or die ($s_pagado2.mysql_error());
	   $r_pagado2 = mysql_num_rows($q_pagado2);
	  
	   for($c=0; $c<$r_pagado2; $c++){
	     $f_pagado2 = mysql_fetch_array($q_pagado2);
		 $montoPagado2 = $montoPagado2 + $f_pagado2['Monto'];
	   }
	     $montoPagadoTotal2 = number_format($montoPagado2,2,',','.');
	   /// ************************************************************
	  $montoConsulta02 = $montoConsulta02 + $f_consulta02['MontoAjuste'];
	  }
	  /// ************ Calculo Monto Ajustado / Disponibilidad Presupuestaria
	   $montoPptoAjustado2 = $montoG + $montoIncremento2 - $montoDisminucion2 + $montoCredito2;
	   $montoDisponPresupuestaria2 = ($montoPptoAjustado2 - $montoCompromiso2) +$montoReintegro2;
	   $montoDisponFinanciera2 = ($montoPptoAjustado2 - $montoPagado2) + $montoReintegro2;
	   $montoVariacion2 = $montoDisponFinanciera2 - $montoDisponPresupuestaria2;
	   
	   $montoPptoAjustado2 = number_format($montoPptoAjustado2,2,',','.');
	   $montoDisponPresupuestaria2 = number_format($montoDisponPresupuestaria2,2,',','.');
	   $montoDisponFinanciera2 = number_format($montoDisponFinanciera2,2,',','.');
	   $montoVariacion2 = number_format($montoVariacion2,2,',','.');
	  /// ************ 
	    
	  $montoGen=number_format($montoG,2,',','.');
	  $codigo_partida = $fpar2['cod_partida'];
	  $gCapturada = $fpar2['generica'];
	  $pCapturada2 = $fpar2['partida1'];
	  /// Monto Incrementado
	  $montoInc2 = number_format($montoConsulta02,2,',','.');
	  ///**** mostrando los resultados para partida 
	  $pdf->SetFillColor(202, 202, 202);
	  $pdf->SetFont('Arial', 'B', 7);
	  $pdf->SetWidths(array(18, 80,20,20,20,20,20,20,20,20,20,20,20,20));
	  $pdf->SetAligns(array('C','L','R','R','R','R','R','R','R','R','R','R','R','R'));
	  $pdf->Row(array($codigo_partida,$fpar2['denominacion'],$montoGen,$montoIncrementoTotal2,$montoDisminucionTotal2,$montoCreditoT2,$montoPptoAjustado2,$montoCompromisoTotal2,$montoCausadoTotal2,$montoPagadoTotal2,$montoReintegroTotal2,$montoDisponPresupuestaria2, $montoDisponFinanciera2,$montoVariacion2));
   }
  }
 //// **** **** **** Capturando Partida Tipo "D" 301-01-01-00 ó 301-01-01-01
 $montoIncremento3 = 0;$montoCredito3=0;$montoCreditoT3=0;$montoReintegro3 = 0;$montoReintegroTotal3 = 0; $montoDisminucion3 = 0; $montoIncrementoTotal3 = 0; $montoDisminucionTotal3 = 0; 
 $montoCompromisoTotal = 0; $montoCompromiso3 = 0;
 $montoCausado3 = 0; $montoCausadoTotal3 = 0;
 $montoPagado3 = 0; $montoPagadoTotal3 = 0;
 if($fieldet['partida']!=00){
     //$pdf->Cell(5,3.5,$fieldet['partida']);
	 $sql="SELECT denominacion FROM pv_partida WHERE cod_partida='".$fieldet['cod_partida']."'";
	 $qry=mysql_query($sql) or die ($sql.mysql_error());
	 $field=mysql_fetch_array($qry);
	 $monto=$fieldet['MontoAprobado'];
	 $montoT=$montoT + $monto;
	 $monto=number_format($monto,2,',','.');
	 $montoTotal=number_format($montoT,2,',','.');
	 $montoDet=number_format($fieldet['MontoAprobado']);
	 
	 /// Monto Incrementado y Disminuido
	 /// *************************************************************************  
	   $s_consulta03 = "select 
						  aj.CodAjuste,
						  aj.TipoAjuste,
						  aj.Periodo,
						  ajdet.cod_partida,
						  ajdet.MontoAjuste
					  from 				   
							pv_ajustepresupuestario aj
                            inner join pv_ajustepresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodAjuste=ajdet.CodAjuste))
					where 
					       aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   aj.Periodo = '$Periodo' and 
						   aj.Estado = 'AP' and
						   ajdet.cod_partida='".$fieldet['cod_partida']."' and aj.MotivoAjuste<>'CR'";		
	  $q_consulta03 = mysql_query($s_consulta03) or die ($s_consulta03.mysql_error());
	  $r_consulta03 = mysql_num_rows($q_consulta03); //echo $r_consulta03;
	
      for($c=0; $c<$r_consulta03; $c++){	
	     $f_consulta03 = mysql_fetch_array($q_consulta03); 
	     if($f_consulta03['TipoAjuste']=='IN'){
	       $montoIncremento3 = $f_consulta03['MontoAjuste'] + $montoIncremento3;
	     }else{ 
	       $montoDisminucion3 = $f_consulta03['MontoAjuste'] + $montoDisminucion3;
		 }
	  }
	 /// Monto Incrementado y Disminuido
	 /// *************************************************************************  
	   $s_consulta033 = "select 
						  aj.CodAjuste,
						  aj.TipoAjuste,
						  aj.Periodo,
						  ajdet.cod_partida,
						  ajdet.MontoAjuste
					  from 				   
							pv_ajustepresupuestario aj
                            inner join pv_ajustepresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodAjuste=ajdet.CodAjuste))
					where 
					       aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   aj.Periodo = '$Periodo' and 
						   aj.Estado = 'AP' and
						   ajdet.cod_partida='".$fieldet['cod_partida']."' and aj.MotivoAjuste='CR'";		
	  $q_consulta033 = mysql_query($s_consulta033) or die ($s_consulta033.mysql_error());
	  $r_consulta033 = mysql_num_rows($q_consulta033); //echo $r_consulta03;
	
      for($c=0; $c<$r_consulta033; $c++){	
	     $f_consulta033 = mysql_fetch_array($q_consulta033); 
	     if($f_consulta033['TipoAjuste']=='IN'){
	       $montoCredito3 = $f_consulta033['MontoAjuste'] + $montoCredito3;
	     }
	  }
	 /// Monto Incrementado y Disminuido
	 /// *************************************************************************  
	   $sR_consulta03 = "select 
						  aj.CodReintegro,
						  aj.Periodo,
						  ajdet.cod_partida,
						  ajdet.MontoReintegro
					  from 				   
							pv_ReintegroPresupuestario aj
                            inner join pv_ReintegroPresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodReintegro=ajdet.CodReintegro))
					where 
					       aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   aj.Periodo = '$Periodo' and 
						   aj.Estado = 'AP' and
						   ajdet.cod_partida='".$fieldet['cod_partida']."'";		
	  $qR_consulta03 = mysql_query($sR_consulta03) or die ($sR_consulta03.mysql_error());
	  $rR_consulta03 = mysql_num_rows($qR_consulta03); //echo $r_consulta03;
	
      for($c=0; $c<$rR_consulta03; $c++){	
	     $fR_consulta03 = mysql_fetch_array($qR_consulta03); 
	     
	       $montoReintegro3 = $fR_consulta03['MontoReintegro'] + $montoReintegro3;
	     
	  }
	  
	  $montoReintegroTotal3 = number_format($montoReintegro3,2,',','.');
	  $montoCreditoT3 = number_format($montoCredito3,2,',','.');
	  $montoIncrementoTotal3 = number_format($montoIncremento3,2,',','.');
	  $montoDisminucionTotal3 = number_format($montoDisminucion3,2,',','.');	
	  	 
	  $montoReint3 = $montoReint3 + $montoReintegro3;
          $montoCreditoTotal= $montoCredito3 +$montoCreditoTotal ;
	  $montoReintTotal = number_format($montoReint3,2,',','.');
	  $montoInc3 = $montoInc3 + $montoIncremento3;
	  $montoIncTotal = number_format($montoInc3,2,',','.');
	  $montoDism3 = $montoDism3 + $montoDisminucion3;
	  $montoDismTotal = number_format($montoDism3,2,',','.');
	 /// *************************************************************************
	 /// MONTO COMPROMETIDO
	       list($ano, $mes) = SPLIT('[-]', $Periodo);
	  $s_compromiso3 = "select
	                         Monto 
						from
						     lg_distribucioncompromisos 
						where 
						      Estado <> 'AN' and 
							  Periodo = '$Periodo' and 
							  cod_partida = '".$fieldet['cod_partida']."'";
	  $q_compromiso3 = mysql_query($s_compromiso3) or die ($s_compromiso3.mysql_error());
	  $r_compromiso3 = mysql_num_rows($q_compromiso3);
	  
	  for($c=0;$c<$r_compromiso3; $c++){
		$f_compromiso3 = mysql_fetch_array($q_compromiso3);
	    $montoCompromiso3 = $montoCompromiso3 + $f_compromiso3['Monto'];
	  }
	  $montoCompromisoTotal3 = number_format($montoCompromiso3,2,',','.');
	  $montoCompTotal = $montoCompTotal + $montoCompromiso3;
	 /// ************************************************************************* 
	 /// MONTO CAUSADO
	   $s_causado3 = "select 
							 *
						 from
							 ap_distribucionobligacion
						 where 
							 Estado<>'AN' and 
							 Periodo = '$Periodo' and 
							 cod_partida = '".$fieldet['cod_partida']."'";
		$q_causado3 = mysql_query($s_causado3) or die ($s_causado3.mysql_error());
		$r_causado3 = mysql_num_rows($q_causado3);
		
		for($c=0; $c<$r_causado3; $c++){
		  $f_causado3 = mysql_fetch_array($q_causado3);
		  $montoCausado3 =  $montoCausado3 + $f_causado3['Monto'];
		}  
		  $montoCausadoTotal3  = number_format($montoCausado3,2,',','.');
		  $montoCausTotal = $montoCausTotal + $montoCausado3;
	 /// *************************************************************************
	 /// MONTO PAGADO
	    $s_pagado3 = "select
	                       *
					  from 
					       ap_ordenpagodistribucion 
					 where
					       Estado = 'PA' and 
						   Periodo = '$Periodo' and 
						   cod_partida = '".$fieldet['cod_partida']."'";
	   $q_pagado3 = mysql_query($s_pagado3) or die ($s_pagado3.mysql_error());
	   $r_pagado3 = mysql_num_rows($q_pagado3);
	  
	   for($c=0; $c<$r_pagado3; $c++){
	     $f_pagado3 = mysql_fetch_array($q_pagado3);
		 $montoPagado3 = $montoPagado3 + $f_pagado3['Monto'];
	   }
	     $montoPagadoTotal3 = number_format($montoPagado3,2,',','.');
		 $montoPagaTotal = $montoPagaTotal + $montoPagado3;
	 /// *************************************************************************
	 /// ************ Calculo Monto Ajustado / Disponibilidad Presupuestaria
	   $montoPptoAjustado3 = ($fieldet['MontoAprobado'] + $montoIncremento3) - $montoDisminucion3 +$montoCredito3;
	   $montoPptoAjusTotal = $montoPptoAjusTotal + $montoPptoAjustado3;
	   
	   $montoDisponPresupuestaria3 = ($montoPptoAjustado3 - $montoCompromiso3) + $montoReintegro3;
	   $montoDisponPresupuestTotal = $montoDisponPresupuestTotal + $montoDisponPresupuestaria3;
	   
	   $montoDisponFinanciera3 = ((($fieldet['MontoAprobado'] - $fieldet['MontoAprobado']) + $montoPptoAjustado3) - $montoPagado3) + $montoReintegro3;
	   $montoDisponFinancieraTotal = $montoDisponFinancieraTotal + $montoDisponFinanciera3;
	   
	   $montoVariacion3 = $montoDisponFinanciera3 - $montoDisponPresupuestaria3;
	   $montoVariacionTotal = $montoVariacionTotal + $montoVariacion3;
	  /// ************ 
	   $montoPptoAjustado3 = number_format($montoPptoAjustado3,2,',','.');
	   $montoDisponPresupuestaria3 = number_format($montoDisponPresupuestaria3,2,',','.');
	   $montoDisponFinanciera3 = number_format($montoDisponFinanciera3,2,',','.');
	   $montoVariacion3 = number_format($montoVariacion3,2,',','.');
	 ///
	 $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	 $pdf->SetFont('Arial', '', 6.5);
	 $pdf->SetWidths(array(18, 80,20,20,20,20,20,20,20,20,20,20,20,20));
	 $pdf->SetAligns(array('C','L','R','R','R','R','R','R','R','R','R','R','R','R'));
	 $pdf->Row(array($fieldet['cod_partida'],$field['denominacion'],$monto,$montoIncrementoTotal3,$montoDisminucionTotal3,$montoCreditoT3,$montoPptoAjustado3,$montoCompromisoTotal3,$montoCausadoTotal3,$montoPagadoTotal3,$montoReintegroTotal3,$montoDisponPresupuestaria3,$montoDisponFinanciera3,$montoVariacion3));
 }
}
	///// *** Mostrar *** /////
	$montoT=number_format($montoT,2,',','.');
	$montoCompTotal = number_format($montoCompTotal,2,',','.');
	$montoCausTotal = number_format($montoCausTotal,2,',','.');
	$montoPagaTotal = number_format($montoPagaTotal,2,',','.');
	$montoPptoAjusTotal = number_format($montoPptoAjusTotal,2,',','.');
	$montoDisponPresupuestTotal = number_format($montoDisponPresupuestTotal,2,',','.');
	$montoDisponFinancieraTotal = number_format($montoDisponFinancieraTotal,2,',','.');
	$montoVariacionTotal = number_format($montoVariacionTotal,2,',','.');
	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(202, 202, 202); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 7.5);
	 $pdf->SetWidths(array(18, 80,20,20,20,20,20,20,20,20,20,20,20,20));
	$pdf->SetAligns(array('C','R','R','R','R','R','R','R','R','R','R','R','R','R'));
	$pdf->Row(array('' ,'Total:',$montoTotal,$montoIncTotal,$montoDismTotal,$montoCreditoTotal,$montoPptoAjusTotal,$montoCompTotal,$montoCausTotal,$montoPagaTotal,$montoReintTotal,$montoDisponPresupuestTotal,$montoDisponFinancieraTotal,$montoVariacionTotal));
	/////
}else{
//// --------------------------------------------------------------------------------- */
 $montoTotalDP = 0; $montoCompTotal=0;
 $montoTotalDF = 0;
 $fmes =(int) ($mes - 1);
 $fmes=(string) str_repeat("0",2-strlen($fmes)).$fmes;
 $fperiodo = $ano.'-'.$fmes; //echo $fperiodo;

$sqlCon = "select 
                 CodPresupuesto,
				 Organismo 
		    from 
			     pv_presupuesto 
		    where
				Organismo<>'' $filtro"; //echo $sqlCon;
$qryCon = mysql_query($sqlCon) or die ($sqlCon.mysql_error());
$rowCon = mysql_num_rows($qryCon); //echo $rowCon;
$fieldCon = mysql_fetch_array($qryCon);

$sqlDet="SELECT cod_partida,
                MontoAprobado,
                partida,Organismo,
				generica,
				especifica,
			    subespecifica,
				tipocuenta,
				CodPresupuesto 
		   FROM 
		        pv_presupuestodet 
		  WHERE 
		        CodPresupuesto='".$fieldCon['CodPresupuesto']."' and 
				Organismo = '".$fieldCon['Organismo']."'
		  ORDER BY cod_partida"; //echo $sqlDet;
$qryDet=mysql_query($sqlDet) or die ($sqlDet.mysql_error());
$rows=mysql_num_rows($qryDet);
for($i=0; $i<$rows ; $i++){
 $fieldet=mysql_fetch_array($qryDet);
 //// ----------------------------------------------------------------------------------------------- */
 //// **** **** **** **** Capturando Partida Tipo "T" 301-00-00-00
 //// ----------------------------------------------------------------------------------------------- */
 $montoIncremento1 = 0;$montoCredito1=0;$montoCreditoT1=0; $montoReintegro1= 0; $montoReintegroTotal= 0;$montoDisminucion1 = 0; $montoIncrementoTotal1 = 0; $montoDisminucionTotal1 = 0;
 $montoCompromiso1 = 0; $montoCompromisoTotal1 = 0; $montoPptoAjustado1 = 0; $montoCausado1 = 0; $montoCausadoTotal1 = 0;
 $montoPagado1 = 0; $montoPagadoTotal1 = 0; $montoDisPresu = 0; $montoReintegro = 0; $montoDisFinan = 0; $montoAjustadoA=0;
 
 if(($fieldet['partida']!=00) and (($cont1==0) or ($pCapturada!=$fieldet['partida']))){
  $sqlPar="SELECT cod_partida,partida1,denominacion,cod_tipocuenta 
			 FROM pv_partida 
			WHERE partida1='".$fieldet['partida']."' AND 
			      cod_tipocuenta='".$fieldet['tipocuenta']."' AND 
				  tipo='T' AND 
				  generica='00'";
  $qryPar=mysql_query($sqlPar) or die ($sqlPar.mysql_error());
  $rwPar=mysql_num_rows($qryPar);//$pdf->Cell(5, 3.5, $rwPar);
  
  if($rwPar!=0){
   $fpar=mysql_fetch_array($qryPar);
   $montoP=0; $cont1=0; $montoConsulta01=0;
   $sqldet="SELECT MontoAprobado, cod_partida, Organismo 
		      FROM pv_presupuestodet 
		     WHERE partida='".$fpar['partida1']."' AND 
			       tipocuenta='".$fpar['cod_tipocuenta']."' AND 
				   CodPresupuesto='".$fieldet['CodPresupuesto']."'"; //echo $sqldet;
   $qrydet=mysql_query($sqldet) or die ($sqldet.mysql_error());
   $rwdet=mysql_num_rows($qrydet);
   
   for($a=0; $a<$rwdet; $a++){
	   
    $fdet=mysql_fetch_array($qrydet);
    $cont1 = $cont1 + 1;
    $montoP = $montoP + $fdet['MontoAprobado'];
	//// -------------------------------------------------------------------------
	$s_ejecucion01="SELECT 
   				         CodPartida,
				 		 DispPresupuestaria,
				 		 DispFinanciera,PptoAjustado 
		      		FROM 
			     		pv_ejecucionpresupuestaria
		     		WHERE 
			     		Periodo='$fperiodo' and 
				 		CodPresupuesto='".$fieldet['CodPresupuesto']."' and 
						CodPartida = '".$fdet['cod_partida']."'";
   $q_ejecucion01=mysql_query($s_ejecucion01) or die ($s_ejecucion01.mysql_error());
   $r_ejecucion01=mysql_num_rows($q_ejecucion01);
   if($r_ejecucion01!=0) $f_ejecucion01 = mysql_fetch_array($q_ejecucion01);
   //	EDGAR
   
	//	FIN
   //// -------------------------------------------------------------------------
	
    $montoDisPresu = $montoDisPresu + $f_ejecucion01['DispPresupuestaria'];
    $montoAjustadoA = $montoAjustadoA + $f_ejecucion01['PptoAjustado'];
	$montoDisFinan = $montoDisFinan + $f_ejecucion01['DispFinanciera'];
	/// - Consulta de partida incrementada o con ajuste positivo
	/// ************************************************************
	 $s_consulta01 = "select 
						  aj.CodAjuste,
						  aj.TipoAjuste,
						  aj.Periodo,
						  ajdet.cod_partida,
						  ajdet.MontoAjuste
					  from 				   
							pv_ajustepresupuestario aj
                            inner join pv_ajustepresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodAjuste=ajdet.CodAjuste))
					where 
					       aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   aj.Periodo = '$Periodo' and 
						   aj.Estado = 'AP' and 
						   ajdet.cod_partida='".$fdet['cod_partida']."' and aj.MotivoAjuste<>'CR'";	//echo $s_consulta03;				   
						   
	  $q_consulta01 = mysql_query($s_consulta01) or die ($s_consulta01.mysql_error());
	  $r_consulta01 = mysql_num_rows($q_consulta01);
	
      for($c=0; $c<$r_consulta01; $c++){	
	     $f_consulta01 = mysql_fetch_array($q_consulta01); 
	     if($f_consulta01['TipoAjuste']=='IN'){
	       $montoIncremento1 = $f_consulta01['MontoAjuste'] + $montoIncremento1;
	     }else{ 
	       $montoDisminucion1 = $f_consulta01['MontoAjuste'] + $montoDisminucion1;
		 }
	  }
	 $s_consulta011 = "select 
						  aj.CodAjuste,
						  aj.TipoAjuste,
						  aj.Periodo,
						  ajdet.cod_partida,
						  ajdet.MontoAjuste
					  from 				   
							pv_ajustepresupuestario aj
                            inner join pv_ajustepresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodAjuste=ajdet.CodAjuste))
					where 
					       aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   aj.Periodo = '$Periodo' and 
						   aj.Estado = 'AP' and 
						   ajdet.cod_partida='".$fdet['cod_partida']."' and aj.MotivoAjuste='CR'";	//echo $s_consulta03;				   
						   
	  $q_consulta011 = mysql_query($s_consulta011) or die ($s_consulta011.mysql_error());
	  $r_consulta011 = mysql_num_rows($q_consulta011);
	
      for($c=0; $c<$r_consulta011; $c++){	
	     $f_consulta011 = mysql_fetch_array($q_consulta011); 
	     if($f_consulta011['TipoAjuste']=='IN'){
	       $montoCredito1 = $f_consulta011['MontoAjuste'] + $montoCredito1;
	     }
	  }
          $R_consulta01 = "select 
						  aj.CodReintegro,
						  aj.Periodo,
						  ajdet.cod_partida,
						  ajdet.MontoReintegro
					  from 				   
							pv_ReintegroPresupuestario aj
                            inner join pv_ReintegroPresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodReintegro=ajdet.CodReintegro))
					where 
					       aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   aj.Periodo = '$Periodo' and 
						   aj.Estado = 'AP' and
						   ajdet.cod_partida='".$fdet['cod_partida']."'";	//echo $s_consulta03;				   
						   
	  $qR_consulta01 = mysql_query($R_consulta01) or die ($R_consulta01.mysql_error());
	  $rR_consulta01 = mysql_num_rows($qR_consulta01);
          
          for($c=0; $c<$rR_consulta01; $c++){	
	     $fR_consulta01 = mysql_fetch_array($qR_consulta01); 
	     
	       $montoReintegro1 = $fR_consulta01['MontoReintegro'] + $montoReintegro1;

	  }
	  $montoReintegroTotal = number_format($montoReintegro1,2,',','.');
	  $montoCreditoT1 = number_format($montoCredito1,2,',','.');
	  $montoIncrementoTotal1 = number_format($montoIncremento1,2,',','.');
	  $montoDisminucionTotal1 = number_format($montoDisminucion1,2,',','.');
	/// ************************************************************
	///                        MONTO COMPROMISO
	/// ************************************************************
	  list($ano, $mes) = SPLIT('[-]', $Periodo);
	  $s_compromiso1 = "select
	                         Monto 
						from
						     lg_distribucioncompromisos 
						where 
						      Estado='CO' and 
							  Periodo = '$Periodo' and 
							  cod_partida = '".$fdet['cod_partida']."' and 
							  CodOrganismo = '".$fdet['Organismo']."'";
	  $q_compromiso1 = mysql_query($s_compromiso1) or die ($s_compromiso1.mysql_error());
	  $r_compromiso1 = mysql_num_rows($q_compromiso1);
	  
	  for($c=0; $c<$r_compromiso1; $c++){
		$f_compromiso1 = mysql_fetch_array($q_compromiso1);
		if($f_compromiso1['Monto']>0){$montoCompromiso1 = $montoCompromiso1 + $f_compromiso1['Monto'];}
	  }
	  $montoCompromisoTotal1 = number_format($montoCompromiso1,2,',','.');
	/// ************************************************************
	///                        MONTO CAUSADO
	/// ************************************************************
	 $s_causado1 = "select 
	                     *
					 from
					     ap_distribucionobligacion
					 where 
					     Estado='CA' and 
						 Periodo = '$Periodo' and 
						 cod_partida = '".$fdet['cod_partida']."' and 
						 CodOrganismo = '".$fdet['Organismo']."'"; //echo $s_causado1;
	$q_causado1 = mysql_query($s_causado1) or die ($s_causado1.mysql_error());
	$r_causado1 = mysql_num_rows($q_causado1);
	
	for($c=0; $c<$r_causado1; $c++){
	  $f_causado1 = mysql_fetch_array($q_causado1);
	  if($f_causado1['Monto']>0)$montoCausado1 =  $montoCausado1 + $f_causado1['Monto'];
	}  
	  $montoCausadoTotal1  = number_format($montoCausado1,2,',','.');
	/// ************************************************************
	///                        MONTO PAGADO
	/// ************************************************************
	 $s_pagado1 = "select
	                       *
					  from 
					       ap_ordenpagodistribucion 
					 where
					       Estado = 'PA' and 
						   Periodo = '$Periodo' and 
						   cod_partida = '".$fdet['cod_partida']."' and 
						   CodOrganismo = '".$fdet['Organismo']."'"; //echo $s_pagado1;
	  $q_pagado1 = mysql_query($s_pagado1) or die ($s_pagado1.mysql_error());
	  $r_pagado1 = mysql_num_rows($q_pagado1); //echo $r_pagado1;
	  
	  for($c=0; $c<$r_pagado1; $c++){ 
		  $f_pagado1 = mysql_fetch_array($q_pagado1); //echo $f_pagado1['cod_partida'].'**'.$c.'--';
	      if($f_pagado1['Monto']>0){
			$montoPagado1 = $montoPagado1 + $f_pagado1['Monto'];
		  }else{
			 //echo $f_pagado1['cod_partida'].' + + '.$f_pagado1['Monto'].'//////';
		     //if((($f_pagado1['cod_partida']==$cp)and($f_pagado1['Periodo']==$pr))or($paso==0)){ 
		       //echo $f_pagado1['cod_partida'].' + + '.$f_pagado1['Monto'].'//////';
			   //echo $f_pagado1['cod_partida'].'++'.$cp.' + + '.$f_pagado1['Periodo'].' ++ '.$pr.'//////';
			   $cp = $f_pagado1['cod_partida']; //echo $cp;
			   $pr = $f_pagado1['Periodo']; //echo $pr;
			   $paso = 1 + $paso;
			   $r_01 =  -1*($f_pagado1['Monto']); //echo $r_01.'///';
			   $montoReintegro = $montoReintegro + $r_01;
			   //echo $paso;
		    //}
	     }
	  }
	    $montoPagadoTotal1 = number_format($montoPagado1,2,',','.');
	 /// ************************************************************
   }
   
    /// ************ Calculo Monto Ajustado / Disponibilidad Presupuestaria / Disponibilidad Financiera
	$montoPptoAjustado1 = $montoAjustadoA - $montoDisminucion1 + $montoCredito1;
	$montoDisponPresupuestaria1 = ($montoDisPresu - $montoCompromiso1)+ $montoReintegro1 + $montoCredito1;
	$montoDisponFinanciera1 = ($montoDisFinan - $montoPagado1)+ $montoReintegro1 + $montoCredito1;
	$montoVariacion1 = $montoDisponFinanciera1 - $montoDisponPresupuestaria1;
	
        if($montoVariacion1<0)$montoVariacion1 = $montoVariacion1*(-1);
	
	
	$montoPptoAjustado1 = number_format($montoPptoAjustado1,2,',','.');
	$montoAjustadoAT = number_format($montoAjustadoA,2,',','.');
	$montoDisPresu1 = number_format($montoDisPresu,2,',','.');
	$montoDisFinan1 = number_format($montoDisFinan,2,',','.');
	$montoDisponPresupuestaria1 = number_format($montoDisponPresupuestaria1,2,',','.');
	$montoDisponFinanciera1 = number_format($montoDisponFinanciera1,2,',','.');
	$montoVariacion1 = number_format($montoVariacion1,2,',','.');
	/// ************ 
	
        $montoP=number_format($montoP,2,',','.');
	
	
	
	$codigo_partida = $fpar['cod_partida'];
	$pCapturada = $fpar['partida1'];
	/// Monto Incrementado
	$montoInc = number_format($montoConsulta01,2,',','.');
	///**** mostrando los resultados para partida 
	$pdf->SetFillColor(202, 202, 202); 
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array(18, 80,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20));
	$pdf->SetAligns(array('C','L','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R'));
	$pdf->Row(array($codigo_partida,$fpar['denominacion'],$montoP,$montoAjustadoAT,$montoIncrementoTotal1,$montoDisminucionTotal1,$montoCreditoT1,$montoPptoAjustado1,$montoDisPresu1,$montoDisFinan1,$montoCompromisoTotal1,$montoCausadoTotal1,$montoPagadoTotal1,$montoReintegroTotal,$montoDisponPresupuestaria1,$montoDisponFinanciera1,$montoVariacion1));
  }
 }
 //// ----------------------------------------------------------------------------------------------- */
 //// **** **** **** Capturando Partida Tipo "T" 301-01-00-00
 //// ----------------------------------------------------------------------------------------------- */
 $montoIncremento2 = 0;$montoCredito2=0; $montoCreditoT2=0; $montoReintegro2=0; $montoReintegroTotal2=0; $montoDisminucion2 = 0; $montoIncrementoTotal2 = 0; $montoDisminucionTotal2 = 0;
 $montoCompromiso2 = 0; $montoCompromisoTotal2 = 0; $montoCausado2 = 0; $montoCausadoTotal2 = 0;
 $montoPagado2 = 0; $montoPagadoTotal2 = 0;	 $montoDispPresu2 = 0; $montoDispFinan2 = 0; $montoAjustadoA2=0;
 if(($fieldet['partida']!=00) and (($cont2==0) or ($gCapturada!=$fieldet['generica']) or ($pCapturada2!=$fieldet['partida']))){
    $sql2="SELECT cod_partida,partida1,denominacion,cod_tipocuenta,generica,tipo 
			    FROM pv_partida 
			   WHERE partida1='".$fieldet['partida']."' AND
				     cod_tipocuenta='".$fieldet['tipocuenta']."' AND
				     tipo='T' AND 
					 generica='".$fieldet['generica']."' AND 
					 especifica='00'";
	$qry2=mysql_query($sql2) or die ($sql2.mysql_error());
	$rows2=mysql_num_rows($qry2);//$pdf->Cell(5,3.5,$rwPar2);
	$montoReintegro2 = 0;
	if($rows2!=0){
	  $fpar2=mysql_fetch_array($qry2);
	  $montoG=0; $cont2=0; $montoConsulta02=0;
	   $sqldet2="SELECT MontoAprobado, cod_partida, CodPresupuesto, Organismo 
			      FROM pv_presupuestodet 
			     WHERE partida='".$fpar2['partida1']."' AND 
				       generica='".$fpar2['generica']."' AND 
					   tipocuenta='".$fpar2['cod_tipocuenta']."' AND 
				       CodPresupuesto='".$fieldet['CodPresupuesto']."'";
	  $qrydet2=mysql_query($sqldet2) or die ($sqldet2.mysql_error());
	  $rwdet2=mysql_num_rows($qrydet2);
	  for($b=0; $b<$rwdet2; $b++){
	   $fdet2=mysql_fetch_array($qrydet2);
	   $cont2 = $cont2 + 1;
           $montoG = $montoG + $fdet2['MontoAprobado'];
	   //// ------------------------------------------------------------------
	   //// Consulta de secomparacion de partidas tomando en consideracion el periodo 
	   $s_ejecucion="SELECT
				           DispPresupuestaria,
				           DispFinanciera,PptoAjustado  
		               FROM 
			               pv_ejecucionpresupuestaria
		              WHERE 
			               Periodo='$fperiodo' and 
				           CodPresupuesto = '".$fdet2['CodPresupuesto']."' and 
						   CodPartida = '".$fdet2['cod_partida']."' and 
						   CodOrganismo = '".$fdet2['Organismo']."'"; //echo $s_ejecucion;
	  $q_ejecucion=mysql_query($s_ejecucion) or die ($s_ejecucion.mysql_error());
	  $r_ejecucion=mysql_num_rows($q_ejecucion);
	  if($r_ejecucion!=0) $f_ejecucion=mysql_fetch_array($q_ejecucion);
	  //	
	  //	FIN
	  //// ------------------------------------------------------------------
	   
	   $montoDispPresu2 = $montoDispPresu2 + $f_ejecucion['DispPresupuestaria'];
	   $montoAjustadoA2 = $montoAjustadoA2 + $f_ejecucion['PptoAjustado'];
	   $montoDispFinan2 = $montoDispFinan2 + $f_ejecucion['DispFinanciera'];
	   
	   /// ************************************************************
	   $sR_consulta02 = "select 
						  aj.CodReintegro,
						  aj.Periodo,
						  ajdet.cod_partida,
						  ajdet.MontoReintegro
					  from 				   
							pv_ReintegroPresupuestario aj
                            inner join pv_ReintegroPresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodReintegro=ajdet.CodReintegro))
					where 
					       aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   aj.Periodo = '$Periodo' and 
						   aj.Estado = 'AP' and
						   ajdet.cod_partida='".$fdet2['cod_partida']."'";	//echo $s_consulta03;				   
						   
	  $qR_consulta02 = mysql_query($sR_consulta02) or die ($sR_consulta02.mysql_error());
	  $rR_consulta02 = mysql_num_rows($qR_consulta02);
	
      for($c=0; $c<$rR_consulta02; $c++){	
	     $fR_consulta02 = mysql_fetch_array($qR_consulta02); 
	       
             $montoReintegro2 = $fR_consulta02['MontoReintegro'] + $montoReintegro2;
	     
	  }
	  $montoReintegroTotal2 = number_format($montoReintegro2,2,',','.');
          
	  $montoIncrementoTotal2 = number_format($montoIncremento2,2,',','.');
	  $montoDisminucionTotal2 = number_format($montoDisminucion2,2,',','.');	
	   /// ************************************************************************* 
	   ///                         MONTO COMPROMETIDO
	   /// ************************************************************************* 
	   list($ano, $mes) = SPLIT('[-]', $Periodo);
	  $s_compromiso2 = "select
	                         Monto 
						from
						     lg_distribucioncompromisos 
						where 
						      Estado = 'CO' and 
							  Periodo = '$Periodo' and 
							  cod_partida = '".$fdet2['cod_partida']."' and 
						      CodOrganismo = '".$fdet2['Organismo']."'";
	  $q_compromiso2 = mysql_query($s_compromiso2) or die ($s_compromiso2.mysql_error());
	  $r_compromiso2 = mysql_num_rows($q_compromiso2);
	  
	  for($c=0;$c<$r_compromiso2; $c++){
		$f_compromiso2 = mysql_fetch_array($q_compromiso2);
		if($f_compromiso2['Monto']>0)$montoCompromiso2 = $montoCompromiso2 + $f_compromiso2['Monto'];
	  }
	  
	  $montoCompromisoTotal2 = number_format($montoCompromiso2,2,',','.');
	   /// ************************************************************************* 
	   ///                            MONTO CAUSADO
	   /// ************************************************************************* 
	   $s_causado2 = "select 
							 *
						 from
							 ap_distribucionobligacion
						 where 
							 Estado='CA' and 
							 Periodo = '$Periodo' and 
							 cod_partida = '".$fdet2['cod_partida']."'";
		$q_causado2 = mysql_query($s_causado2) or die ($s_causado2.mysql_error());
		$r_causado2 = mysql_num_rows($q_causado2);
		
		for($c=0; $c<$r_causado2; $c++){
		  $f_causado2 = mysql_fetch_array($q_causado2);
		  if($f_causado2['Monto']>0)$montoCausado2 =  $montoCausado2 + $f_causado2['Monto'];
		}  
		  $montoCausadoTotal2  = number_format($montoCausado2,2,',','.');
	   /// ************************************************************************* 
	   ///                            MONTO PAGADO
	   /// ************************************************************************* 
	   $s_pagado2 = "select
	                       *
					  from 
					       ap_ordenpagodistribucion 
					 where
					       Estado = 'PA' and 
						   Periodo = '$Periodo' and 
						   cod_partida = '".$fdet2['cod_partida']."'"; //echo $s_pagado2; 
	   $q_pagado2 = mysql_query($s_pagado2) or die ($s_pagado2.mysql_error());
	   $r_pagado2 = mysql_num_rows($q_pagado2);
	   
	   for($c=0; $c<$r_pagado2; $c++){
	     $f_pagado2 = mysql_fetch_array($q_pagado2);
		 if($f_pagado2['Monto']>0)$montoPagado2 = $montoPagado2 + $f_pagado2['Monto'];
		 else{  
		   $r_02 =  -1*($f_pagado2['Monto']);
		   $montoReintegro2 = $montoReintegro2 +  $r_02;
		  }
	   }
	     $montoPagadoTotal2 = number_format($montoPagado2,2,',','.');
	   /// *************************************************************************
	  $montoConsulta02 = $montoConsulta02 + $f_consulta02['MontoAjuste'];
	  }
	  /// ************ Calculo Monto Ajustado / Disponibilidad Presupuestaria
	   $montoPptoAjustado2 = $montoG  + $montoIncremento2 - $montoDisminucion2;
	   $montoAjustadoAT2 = $montoAjustadoA2  + $montoIncremento2 - $montoDisminucion2;
	   $montoDisponPresupuestaria2 = ($montoDispPresu2 - $montoCompromiso2)+$montoReintegro2;
	   $montoDisponFinanciera2 = ($montoDispFinan2 - $montoPagado2)+$montoReintegro2;
	   $montoVariacion2 = $montoDisponFinanciera2 - $montoDisponPresupuestaria2;
	   
	   $montoPptoAjustado2 = number_format($montoPptoAjustado2,2,',','.');
	   $montoDisponPresupuestaria2 = number_format($montoDisponPresupuestaria2,2,',','.');
	   $montoDisponFinanciera2 = number_format($montoDisponFinanciera2,2,',','.');
	   $montoAjustadoAT2T = number_format($montoAjustadoAT2,2,',','.');
	   $montoAjustadoAT2T22 = number_format($montoAjustadoA2,2,',','.');
	   $montoDispPresup2 = number_format($montoDispPresu2,2,',','.');
	   $montoDispFinanc2 = number_format($montoDispFinan2,2,',','.');
	   $montoGen = number_format($montoG,2,',','.');
	   $montoVariacion2 = number_format($montoVariacion2,2,',','.');
	  /// ************ 
	   
	   
	   $montoDF2=number_format($montoDispFinan2,2,',','.');
	   
	  
	  
	  $montoGen=number_format($montoG,2,',','.');
	  $codigo_partida = $fpar2['cod_partida'];
	  $gCapturada = $fpar2['generica'];
	  $pCapturada2 = $fpar2['partida1'];
	  /// Monto Incrementado
	  $montoInc2 = number_format($montoConsulta02,2,',','.');
	  ///**** mostrando los resultados para partida 
	  $pdf->SetFillColor(202, 202, 202);
	  $pdf->SetFont('Arial', 'B', 7);
	  $pdf->SetWidths(array(18, 80,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20));
	  $pdf->SetAligns(array('C','L','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R'));
	  $pdf->Row(array($codigo_partida,$fpar2['denominacion'],$montoGen,$montoAjustadoAT2T22,$montoIncrementoTotal2,$montoDisminucionTotal2,'0.00',$montoAjustadoAT2T,$montoDispPresup2,$montoDispFinanc2,$montoCompromisoTotal2,$montoCausadoTotal2,$montoPagadoTotal2,$montoReintegroTotal2,$montoDisponPresupuestaria2, $montoDisponFinanciera2,$montoVariacion2));
   }
  }
 //// **** **** **** Capturando Partida Tipo "D" 301-01-01-00 ó 301-01-01-01
 $montoIncremento3 = 0;$montoCredito3=0;$montoCreditoT3=0;$montoReintegro3 = 0;$montoReintegroTotal3 = 0; $montoDisminucion3 = 0; $montoIncrementoTotal3 = 0; $montoDisminucionTotal3 = 0; 
 $montoCompromisoTotal = 0; $montoCompromiso3 = 0;
 $montoCausado3 = 0; $montoCausadoTotal3 = 0;
 $montoPagado3 = 0; $montoPagadoTotal3 = 0;
 if($fieldet['partida']!=00){
     //$pdf->Cell(5,3.5,$fieldet['partida']);
	 $sql="SELECT denominacion FROM pv_partida WHERE cod_partida='".$fieldet['cod_partida']."'";
	 $qry=mysql_query($sql) or die ($sql.mysql_error());
	 $field=mysql_fetch_array($qry);
	 $monto=$fieldet['MontoAprobado'];
	 $montoT=$montoT + $monto;
	 $montos=number_format($monto,2,',','.');
	 $montoTotal=number_format($montoT,2,',','.');
	 $montoDet=number_format($fieldet['MontoAprobado']);
	 
         //// Consulta de secomparacion de partidas tomando en consideracion el periodo 
	   $s_ejecucion3="SELECT
				           DispPresupuestaria,
				           DispFinanciera,PptoAjustado  
		               FROM 
			               pv_ejecucionpresupuestaria
		              WHERE 
			               Periodo='$fperiodo' and 
				           CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   CodPartida = '".$fieldet['cod_partida']."' and 
						   CodOrganismo = '".$fieldet['Organismo']."'"; //echo $s_ejecucion;
	  $q_ejecucion3=mysql_query($s_ejecucion3) or die ($s_ejecucion3.mysql_error());
	  $r_ejecucion3=mysql_num_rows($q_ejecucion3);
	  if($r_ejecucion3!=0) $f_ejecucion3=mysql_fetch_array($q_ejecucion3);
	  //	
	  //	FIN
	  //// ------------------------------------------------------------------
	   
	   $montoAjusteA3 = $montoAjusteA3 + $f_ejecucion3['PptoAjustado'];
	   $montoDispPresu3 = $montoDispPresu3 + $f_ejecucion3['DispPresupuestaria'];
	   $montoDispFinan3 = $montoDispFinan3 + $f_ejecucion3['DispFinanciera'];
	   
	 /// Monto Incrementado y Disminuido
	 /// *************************************************************************  
	   $s_consulta03 = "select 
						  aj.CodAjuste,
						  aj.TipoAjuste,
						  aj.Periodo,
						  ajdet.cod_partida,
						  ajdet.MontoAjuste
					  from 				   
							pv_ajustepresupuestario aj
                            inner join pv_ajustepresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodAjuste=ajdet.CodAjuste))
					where 
					       aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   aj.Periodo = '$Periodo' and 
						   aj.Estado = 'AP' and
						   ajdet.cod_partida='".$fieldet['cod_partida']."' and aj.MotivoAjuste<>'CR'";		
	  $q_consulta03 = mysql_query($s_consulta03) or die ($s_consulta03.mysql_error());
	  $r_consulta03 = mysql_num_rows($q_consulta03); //echo $r_consulta03;
	
      for($c=0; $c<$r_consulta03; $c++){	
	     $f_consulta03 = mysql_fetch_array($q_consulta03); 
	     if($f_consulta03['TipoAjuste']=='IN'){
	       $montoIncremento3 = $f_consulta03['MontoAjuste'] + $montoIncremento3;
	     }else{ 
	       $montoDisminucion3 = $f_consulta03['MontoAjuste'] + $montoDisminucion3;
		 }
	  }
	 /// Monto Incrementado y Disminuido
	 /// *************************************************************************  
	   $s_consulta033 = "select 
						  aj.CodAjuste,
						  aj.TipoAjuste,
						  aj.Periodo,
						  ajdet.cod_partida,
						  ajdet.MontoAjuste
					  from 				   
							pv_ajustepresupuestario aj
                            inner join pv_ajustepresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodAjuste=ajdet.CodAjuste))
					where 
					       aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   aj.Periodo = '$Periodo' and 
						   aj.Estado = 'AP' and
						   ajdet.cod_partida='".$fieldet['cod_partida']."' and aj.MotivoAjuste='CR'";		
	  $q_consulta033 = mysql_query($s_consulta033) or die ($s_consulta033.mysql_error());
	  $r_consulta033 = mysql_num_rows($q_consulta033); //echo $r_consulta03;
	
      for($c=0; $c<$r_consulta033; $c++){	
	     $f_consulta033 = mysql_fetch_array($q_consulta033); 
	     if($f_consulta033['TipoAjuste']=='IN'){
	       $montoCredito3 = $f_consulta033['MontoAjuste'] + $montoCredito3;
	     }
	  }
	 /// 
	 /// *************************************************************************  
	   $sR_consulta03 = "select 
						  aj.CodReintegro,
						  aj.Periodo,
						  ajdet.cod_partida,
						  ajdet.MontoReintegro
					  from 				   
							pv_ReintegroPresupuestario aj
                            inner join pv_ReintegroPresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodReintegro=ajdet.CodReintegro))
					where 
					       aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   aj.Periodo = '$Periodo' and 
						   aj.Estado = 'AP' and
						   ajdet.cod_partida='".$fieldet['cod_partida']."'";		
	  $qR_consulta03 = mysql_query($sR_consulta03) or die ($sR_consulta03.mysql_error());
	  $rR_consulta03 = mysql_num_rows($qR_consulta03); //echo $r_consulta03;
	
      for($c=0; $c<$rR_consulta03; $c++){	
	     $fR_consulta03 = mysql_fetch_array($qR_consulta03); 
	     
	       $montoReintegro3 = $fR_consulta03['MontoReintegro'] + $montoReintegro3;
	     
	  }
	  
	  $montoReintegroTotal3 = number_format($montoReintegro3,2,',','.');
	  $montoCreditoT3 = number_format($montoCredito3,2,',','.');
	  $montoIncrementoTotal3 = number_format($montoIncremento3,2,',','.');
	  $montoDisminucionTotal3 = number_format($montoDisminucion3,2,',','.');	
	  	 
	  $montoReint3 = $montoReint3 + $montoReintegro3;
	  $montoReintTotal = number_format($montoReint3,2,',','.');
	  $montoCredit3 = $montoCredito3 + $montoCredit3;
	  $montoCreditoTotal = number_format($montoCredit3,2,',','.');
	  $montoInc3 = $montoInc3 + $montoIncremento3;
	  $montoIncTotal = number_format($montoInc3,2,',','.');
	  $montoDism3 = $montoDism3 + $montoDisminucion3;
	  $montoDismTotal = number_format($montoDism3,2,',','.');
	 /// *************************************************************************
	 /// MONTO COMPROMETIDO
	       list($ano, $mes) = SPLIT('[-]', $Periodo);
	  $s_compromiso3 = "select
	                         Monto 
						from
						     lg_distribucioncompromisos 
						where 
						      Estado <> 'AN' and 
							  Periodo = '$Periodo' and 
							  cod_partida = '".$fieldet['cod_partida']."'";
	  $q_compromiso3 = mysql_query($s_compromiso3) or die ($s_compromiso3.mysql_error());
	  $r_compromiso3 = mysql_num_rows($q_compromiso3);
	  
	  for($c=0;$c<$r_compromiso3; $c++){
		$f_compromiso3 = mysql_fetch_array($q_compromiso3);
	    $montoCompromiso3 = $montoCompromiso3 + $f_compromiso3['Monto'];
	  }
	  $montoCompromisoTotal3 = number_format($montoCompromiso3,2,',','.');
	  $montoCompTotal = $montoCompTotal + $montoCompromiso3;
	 /// ************************************************************************* 
	 /// MONTO CAUSADO
	   $s_causado3 = "select 
							 *
						 from
							 ap_distribucionobligacion
						 where 
							 Estado<>'AN' and 
							 Periodo = '$Periodo' and 
							 cod_partida = '".$fieldet['cod_partida']."'";
		$q_causado3 = mysql_query($s_causado3) or die ($s_causado3.mysql_error());
		$r_causado3 = mysql_num_rows($q_causado3);
		
		for($c=0; $c<$r_causado3; $c++){
		  $f_causado3 = mysql_fetch_array($q_causado3);
		  $montoCausado3 =  $montoCausado3 + $f_causado3['Monto'];
		}  
		  $montoCausadoTotal3  = number_format($montoCausado3,2,',','.');
		  $montoCausTotal = $montoCausTotal + $montoCausado3;
	 /// *************************************************************************
	 /// MONTO PAGADO
	    $s_pagado3 = "select
	                       *
					  from 
					       ap_ordenpagodistribucion 
					 where
					       Estado = 'PA' and 
						   Periodo = '$Periodo' and 
						   cod_partida = '".$fieldet['cod_partida']."'";
	   $q_pagado3 = mysql_query($s_pagado3) or die ($s_pagado3.mysql_error());
	   $r_pagado3 = mysql_num_rows($q_pagado3);
	  
	   for($c=0; $c<$r_pagado3; $c++){
	     $f_pagado3 = mysql_fetch_array($q_pagado3);
		 $montoPagado3 = $montoPagado3 + $f_pagado3['Monto'];
	   }
	     $montoPagadoTotal3 = number_format($montoPagado3,2,',','.');
		 $montoPagaTotal = $montoPagaTotal + $montoPagado3;
	 /// *************************************************************************
	 /// ************ Calculo Monto Ajustado / Disponibilidad Presupuestaria
	   $montoPptoAjustado3 = ($fieldet['MontoAprobado'] + $montoIncremento3) - $montoDisminucion3 + $montoCredito3;
	   $montoPptoAjustadoAT3 = ($f_ejecucion3['PptoAjustado'] + $montoIncremento3) - $montoDisminucion3 + $montoCredito3;
	   $montoPptoAjusAT3Total = $montoPptoAjusAT3Total + $montoPptoAjustadoAT3;
	   
	   $montoDisponPresupuestaria3 = ($f_ejecucion3['DispPresupuestaria'] - $montoCompromiso3) + $montoReintegro3 + $montoCredito3;
	   $montoDisponPresupuestTotal = $montoDisponPresupuestTotal + $montoDisponPresupuestaria3;
	   
	   $montoDisponFinanciera3 = ($f_ejecucion3['DispFinanciera'] - $montoPagado3) + $montoReintegro3 + $montoCredito3;
	   $montoDisponFinancieraTotal = $montoDisponFinancieraTotal + $montoDisponFinanciera3;
	   
	   $montoVariacion3 = $montoDisponFinanciera3 - $montoDisponPresupuestaria3;
	   $montoVariacionTotal = $montoVariacionTotal + $montoVariacion3;
	  /// ************ 
           
           $ReintegroTotal= $re;
	   $montoPptoAjustado3 = number_format($montoPptoAjustado3,2,',','.');
	   $montoPptoAjustadoAT33 = number_format($montoPptoAjustadoAT3,2,',','.');
	   $montoDisponPresupuestaria3 = number_format($montoDisponPresupuestaria3,2,',','.');
	   $montoDisponFinanciera3 = number_format($montoDisponFinanciera3,2,',','.');
	   $montoVariacion3 = number_format($montoVariacion3,2,',','.');
	 ///
	 $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	 $pdf->SetFont('Arial', '', 6.5);
	 $pdf->SetWidths(array(18, 80,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20));
	 $pdf->SetAligns(array('C','L','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R'));
	 $pdf->Row(array($fieldet['cod_partida'],$field['denominacion'],$montos,  number_format($f_ejecucion3['PptoAjustado'],2,',','.'),$montoIncrementoTotal3,$montoDisminucionTotal3,$montoCreditoT3,$montoPptoAjustadoAT33,  number_format($f_ejecucion3['DispPresupuestaria'], 2, ',', '.'),  number_format($f_ejecucion3['DispFinanciera'], 2, ',', '.'),$montoCompromisoTotal3,$montoCausadoTotal3,$montoPagadoTotal3,$montoReintegroTotal3,$montoDisponPresupuestaria3,$montoDisponFinanciera3,$montoVariacion3));
 }
}
	///// *** Mostrar *** /////
	$montoT=number_format($montoT,2,',','.');
	$montoPptoAjusAT3Total = number_format($montoPptoAjusAT3Total,2,',','.');
	$montoAjusteA33 = number_format($montoAjusteA3,2,',','.');
	$montoCompTotal = number_format($montoCompTotal,2,',','.');
	$montoDispPresuT = number_format($montoDispPresu3,2,',','.');
	$montoDispFinanT = number_format($montoDispFinan3,2,',','.');
	$montoCausTotal = number_format($montoCausTotal,2,',','.');
	$montoPagaTotal = number_format($montoPagaTotal,2,',','.');
	$montoPptoAjusTotal = number_format($montoPptoAjusTotal,2,',','.');
	$montoDisponPresupuestTotal = number_format($montoDisponPresupuestTotal,2,',','.');
	$montoDisponFinancieraTotal = number_format($montoDisponFinancieraTotal,2,',','.');
	$montoVariacionTotal = number_format($montoVariacionTotal,2,',','.');
	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(202, 202, 202); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 7.5);
	 $pdf->SetWidths(array(18, 80,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20));
	$pdf->SetAligns(array('C','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R'));
	$pdf->Row(array('' ,'Total:',$montoTotal,$montoAjusteA33,$montoIncTotal,$montoDismTotal,$montoCreditoTotal,$montoPptoAjusAT3Total,$montoDispPresuT,$montoDispFinanT,$montoCompTotal,$montoCausTotal,$montoPagaTotal,$montoReintTotal,$montoDisponPresupuestTotal,$montoDisponFinancieraTotal,$montoVariacionTotal));
	/////
}
//---------------------------------------------------*/
$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(100,10,'',0,1,'L');
//	$pdf->Cell(70,10,'ELABORADO POR:',0,0,'L');$pdf->Cell(70,10,'REVISADO POR:',0,0,'L');$pdf->Cell(70,10,'CONFORMADO POR:',0,1,'L');$pdf->Cell(70,10,'APROBADOPOR POR:',0,1,'L');
	//$pdf->Cell(100,5,'',0,0,'L');$pdf->Cell(120,5,'',0,0,'L');$pdf->Cell(100,5,'',0,1,'L');
	 //$pdf->Cell(50,5,utf8_decode ('Lcda: Sorielma Salmerón'),1,0,'L');$pdf->Cell(50,5,'Lcda: Milagros Rivas Mata',1,0,'L');$pdf->Cell(50,5,'Lcdo. Cesar Granado',1,1,'L');$pdf->Cell(50,5,'Lcdo. Freddy Cudjoe',1,1,'L'); 
	 $pdf->SetWidths(array(90, 90,50));
	 
	 $pdf->SetAligns(array('L','L','L'));
	  $pdf->Row(array('ELABORADO POR:','APROBADO POR:','REVISADO POR:'),0);
	  $pdf->Ln(5);
	 $pdf->Row(array(utf8_decode ('Lcda: Sorielma Salmerón'),'Lcda: Roxaida Estrada','Lcdo. Cesar Granado'));
	 $pdf->Row(array(utf8_decode ('JEFE  DIVISIÓN  DE PRESUPUESTO Y CONTABILIDAD'),utf8_decode ('DIRECTORA DE ADMINISTRACIÓN Y PRESUPUESTO (E)'),'DIRECTOR GENERAL'));
	
	 

$pdf->Output();
?>  
