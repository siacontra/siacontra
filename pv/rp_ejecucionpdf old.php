<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');
require('fphp.php');
connect(); 
extract ($_POST);
extract ($_GET);
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
class PDF extends FPDF{
//Page header
function Header(){
    
	global $Periodo;
	global $EjercicioPpto;
	$this->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$this->SetFont('Arial', 'B', 8);
	$this->SetXY(20, 10); $this->Cell(200, 5,utf8_decode( 'Contraloría del estado Monagas'), 0, 0, 'L');
	                      $this->Cell(10,5,'Fecha:',0,0,'');$this->Cell(10,5,date('d/m/Y'),0,1,'');
	$this->SetXY(20, 15); $this->Cell(200, 5, utf8_decode('Dirección de Administración y Presupuesto'), 0, 0, 'L');
	                       $this->Cell(10,5,utf8_decode('Página:'),0,1,'');
	$this->SetXY(19, 20); $this->Cell(204, 5, '', 0, 0, 'L');
	                       $this->Cell(7,5,utf8_decode('Año:'),0,0,'L');$this->Cell(6,5,date('Y'),0,1,'L');
						   
	list($fano, $fmes) = SPLIT('[-]',$Periodo);
	//echo $fano, $fmes;
    /*switch ($fmes) {
		case 01: $mes = Enero; break;  
		case 02: $mes = Febrero;break; 
		case 03: $mes = Marzo;break;   
		case 04: $mes = Abril;break;   
		case 05: $mes = Mayo;break;    
		case 06: $mes = Junio;break;
		case 07: $mes = Julio; break;
		case 08: $mes = Agosto; break;
		case 09: $mes = Septiembre; break;
		case 10: $mes = Octubre; break;
		case 11: $mes = Noviembre; break;
		case 12: $mes = Diciembre; break;
    }*/
	//echo $fmes;					   
	$this->SetFont('Arial', 'B', 10);
	$this->Cell(105, 10, '', 0, 0, 'C');
	$this->Cell(47, 10, utf8_decode('Ejecución Presupuestaria'), 0, 0, 'C');
    $this->Cell(10, 10, $EjercicioPpto, 0, 0, 'C'); $this->Cell(10, 10,'', 0, 1, 'C');
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
	
	
	$this->SetFont('Arial', '', 7);
	$this->Cell(27, 3, 'SECTOR:', 0, 0, 'L');$this->Cell(3, 3, $fieldSector[1], 0, 0, 'L');$this->Cell(30, 3, $fieldSector['descripcion'], 0, 1, 'L');
	$this->Cell(27, 3, 'PROGRAMA:', 0, 0, 'L');$this->Cell(3, 3, $fieldPrograma[1], 0, 0, 'L');$this->Cell(30, 3, $fieldPrograma[0], 0, 1, 'L');
	$this->Cell(27, 3, 'Actividad:', 0, 0, 'L');$this->Cell(3, 3, $fieldSubprog[1], 0, 0, 'L');$this->Cell(30, 3, $fieldSubprog[0], 0, 1, 'L');
	$this->Cell(27, 3, 'PROYECTO:', 0, 0, 'L');$this->Cell(3, 3, $fieldProyecto[1], 0, 0, 'L');$this->Cell(30, 3, $fieldProyecto[0], 0, 1, 'L');
	$this->Cell(27, 3, 'Sub-Programa:', 0, 0, 'L');$this->Cell(3, 3, $fieldActividad[1], 0, 0, 'L');$this->Cell(30, 3, $fieldActividad[0], 0, 1, 'L');
	$this->Cell(27, 3, 'UNIDAD EJECUTORA:', 0, 0, 'L');$this->Cell(30, 3, $fieldOrg[0], 0, 1, 'L');
	$this->SetDrawColor(0, 0, 0); $this->SetFillColor(200, 200, 200); $this->SetTextColor(0, 0, 0);
	$this->SetFont('Arial', 'B', 6);
	$this->Cell(18, 3, 'PAR GE ESP SE', 1, 0, 'C', 1);
	$this->Cell(75, 3, 'DENOMINACION', 1, 0, 'C', 1);
	$this->Cell(25, 3, 'T. PRESUPUESTADO', 1, 0, 'C', 1);
	$this->Cell(25, 3, 'T. INCREMENTO', 1, 0, 'C', 1);
	$this->Cell(25, 3, 'T. DISMINUCION', 1, 0, 'C', 1);
	$this->Cell(25, 3, 'T. CREDITO A.', 1, 0, 'C', 1);
	$this->Cell(25, 3, 'T. AJUSTADO', 1, 0, 'C', 1);
	$this->Cell(25, 3, 'T. COMPROMETIDO', 1, 0, 'C', 1);
	$this->Cell(25, 3, 'T. CAUSADO', 1, 0, 'C', 1);
	$this->Cell(25, 3, 'T. PAGADO', 1, 0, 'C', 1);
	$this->Cell(25, 3, 'T. REINTEGRO', 1, 0, 'C', 1);
	$this->Cell(25, 3, 'T. DISPONIBLE', 1, 1, 'C', 1);
	$this->SetFillColor(255, 255, 255);
	///// ******************	
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
//Instanciation of inherited class
$pdf=new PDF('L','mm','Legal');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

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

$sqlDet = "SELECT cod_partida,
                MontoAprobado,
				MontoCompromiso,
				MontoCausado,
				MontoPagado,
                partida,
				generica,
				especifica,
			    subespecifica,
				tipocuenta,
				CodPresupuesto,
				MontoAjustado,
                                MontoReintegrado
		   FROM 
		        pv_presupuestodet 
		  WHERE 
		        CodPresupuesto='".$fieldCon['CodPresupuesto']."' and 
				Organismo = '".$fieldCon['Organismo']."'
		  ORDER BY cod_partida"; //echo $sqlDet;
$qryDet = mysql_query($sqlDet) or die ($sqlDet.mysql_error());
$rows = mysql_num_rows($qryDet);
for($i=0; $i<$rows ; $i++){
 $fieldet=mysql_fetch_array($qryDet);
 
  if($fieldet['cod_partida']=='401.01.11.00')
			  {
			//  print_r ($fieldet);
			  	// echo $fieldet['cod_partida']." <br>";
			  	 //$montoPago03 =1000000;
			  	// echo $spago;
			  }
 
 
//// --------------------------------------------------------------------------------------------------
//// **** **** **** **** Capturando Partida Tipo "T" 301-00-00-00
//// --------------------------------------------------------------------------------------------------
        $montoAprobado = 0;
	$montoAjustado = 0;
	$montoCompromiso = 0;
	$montoCausado = 0;
	$montoPagado = 0;
	$montoReintegrado = 0;
	$montoDisponible = 0;
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
   $sqldet="SELECT MontoAprobado, cod_partida, MontoAjustado,MontoPagado,MontoCausado,MontoCompromiso, MontoReintegrado
		      FROM pv_presupuestodet 
		     WHERE partida='".$fpar['partida1']."' AND 
			       tipocuenta='".$fpar['cod_tipocuenta']."' AND 
				   CodPresupuesto='".$fieldet['CodPresupuesto']."'";
   $qrydet=mysql_query($sqldet) or die ($sqldet.mysql_error());
   $rwdet=mysql_num_rows($qrydet);
   
   
   
   
   

   /// ********************************************************************************************** ///
	///                                 CALCULANDO MONTO CREDITO ADICIONAL
	/// ********************************************************************************************** ///	
	
	
   for($a=0; $a<$rwdet; $a++){
    $fdet=mysql_fetch_array($qrydet);
	$cont1 = $cont1 + 1;


        $montoAprobado = $montoAprobado + $fdet['MontoAprobado'];
	$montoAjustado = $montoAjustado + $fdet['MontoAjustado'];
	$montoCompromiso = $montoCompromiso + $fdet['MontoCompromiso'];
	$montoCausado = $montoCausado + $fdet['MontoCausado'];
	$montoPagado = $montoPagado + $fdet['MontoPagado'];
	$montoReintegrado = $montoReintegrado + $fdet['MontoReintegrado'];
	$montoDisponible = ($montoAjustado - $montoCompromiso)+$montoReintegrado;
   }
   
   
   /// - Consulta de partida incrementada o con ajuste positivo
	/// ************************************************************
	 $ConIn01 = "select 
						  
						sum(  ajdet.MontoAjuste) as Incremento
					  from 				   
							pv_ajustepresupuestario aj
                            inner join pv_ajustepresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodAjuste=ajdet.CodAjuste))
					where 
					       aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   
						   aj.Estado = 'AP' and aj.TipoAjuste='IN' and
						   ajdet.cod_partida LIKE '".substr($fieldet['cod_partida'], 0, 3)."%'
                                                    and aj.MotivoAjuste <>'CR'";	//echo $s_consulta03;				   
						   
	  $QueConIn01 = mysql_query($ConIn01) or die ($ConIn01.mysql_error());
	  $ReConIn01 = mysql_fetch_array($QueConIn01);
	/// ************************************************************
	 $ConIn011 = "select 
						  
						sum(  ajdet.MontoAjuste) as Credito
					  from 				   
							pv_ajustepresupuestario aj
                            inner join pv_ajustepresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodAjuste=ajdet.CodAjuste))
					where 
					       aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   
						   aj.Estado = 'AP'  and
						   ajdet.cod_partida LIKE '".substr($fieldet['cod_partida'], 0, 3)."%'
                                                    and aj.MotivoAjuste ='CR'";	//echo $s_consulta03;				   
						   
	  $QueConIn011 = mysql_query($ConIn011) or die ($ConIn011.mysql_error());
	  $ReConIn011 = mysql_fetch_array($QueConIn011);
	/// ************************************************************
	 $ConDi01 = "select 
						  
						sum(  ajdet.MontoAjuste) as Decremento
					  from 				   
							pv_ajustepresupuestario aj
                            inner join pv_ajustepresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodAjuste=ajdet.CodAjuste))
					where 
					       aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   
						   aj.Estado = 'AP' and aj.TipoAjuste='DI' and
						   ajdet.cod_partida LIKE '".substr($fieldet['cod_partida'], 0, 3)."%'
                                                   and aj.MotivoAjuste <>'CR'";
         
	  $QueConDi01 = mysql_query($ConDi01) or die ($ConDi01.mysql_error());
	  $ReConDi01 = mysql_fetch_array($QueConDi01);
	/// - Consulta de partida incrementada por Reintegro
	/// ************************************************************
	 
         
          if(!isset($ReConIn01['Incremento'])){
              $InCre=0;
          }else{
              $InCre=$ReConIn01['Incremento'];
          }
          if(!isset($ReConIn011['Credito'])){
              $CreCre=0;
          }else{
              $CreCre=$ReConIn011['Credito'];
          }
          if(!isset($ReConDi01['Decremento'])){
              $DiCre=0;
          }else{
              $DiCre=$ReConDi01['Decremento'];
          }
        $IntTotal1 = number_format( $InCre ,2,',','.');
        $DitTotal1 = number_format( $DiCre ,2,',','.');
        $CretTotal1 = number_format( $CreCre ,2,',','.');
        $montoAprobado = number_format($montoAprobado,2,',','.');
	 $montoAjustado = number_format($montoAjustado,2,',','.');
	 
	 $montoCreditoA = number_format($montoCreditoA,2,',','.');
	 $montoReintegrado = number_format($montoReintegrado,2,',','.');
	
	
	 
	 
	 $montoCompromiso = number_format($montoCompromiso,2,',','.');
	 $montoCausado = number_format($montoCausado,2,',','.');
	 $montoPagado = number_format($montoPagado,2,',','.');
	 $montoDisponible = number_format($montoDisponible,2,',','.');

	$codigo_partida = $fpar['cod_partida'];
	$pCapturada = $fpar['partida1'];
	
	///**** mostrando los resultados para partida 
	$pdf->SetFillColor(202, 202, 202); 
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array(18, 75,25,25,25,25,25,25,25,25,25,25));
	$pdf->SetAligns(array('C','L','R','R','R','R','R','R','R','R','R','R'));
	$pdf->Row(array($codigo_partida,$fpar['denominacion'],$montoAprobado,$IntTotal1,$DitTotal1,$CretTotal1,$montoAjustado,$montoCompromiso,$montoCausado,$montoPagado,$montoReintegrado,$montoDisponible));
  }
 }
//// --------------------------------------------------------------------------------------------------
//// **** **** **** Capturando Partida Tipo "T" 301-01-00-00
//// --------------------------------------------------------------------------------------------------
        $montoAprobado1 = 0;
	$montoAjustado1 = 0;
	$montoCompromiso1 = 0;
	$montoCausado1 = 0;
	$montoPagado1 = 0;
        $montoReintegrado1 = 0;
	$montoDisponible1 = 0;
        $montoIncremento2=0;
        $montoDisminucion2=0;
        $montoCredito2=0;
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
	  $cont2=0;
	  $sqldet2="SELECT MontoAprobado, 
	                   cod_partida, 
					   MontoAjustado, 
					   MontoCompromiso, 
					   MontoCausado,
					   MontoPagado,
                                           MontoReintegrado
			      FROM pv_presupuestodet 
			     WHERE partida='".$fpar2['partida1']."' AND 
				       generica='".$fpar2['generica']."' AND 
					   tipocuenta='".$fpar2['cod_tipocuenta']."' AND 
				       CodPresupuesto='".$fieldet['CodPresupuesto']."'";
	  $qrydet2=mysql_query($sqldet2) or die ($sqldet2.mysql_error());
	  $rwdet2=mysql_num_rows($qrydet2);
	  
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
						  
						   aj.Estado = 'AP' and
						   ajdet.cod_partida LIKE '".substr($fieldet['cod_partida'], 0, 6)."%'"
                   . "                               and aj.MotivoAjuste <>'CR'";	//echo $s_consulta03;				   
						   
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
						  
						   aj.Estado = 'AP' and
						   ajdet.cod_partida LIKE '".substr($fieldet['cod_partida'], 0, 6)."%'"
                   . "                               and aj.MotivoAjuste ='CR'";	//echo $s_consulta03;				   
						   
	  $q_consulta022 = mysql_query($s_consulta022) or die ($s_consulta022.mysql_error());
	  $r_consulta022 = mysql_num_rows($q_consulta022);
	
      for($c=0; $c<$r_consulta022; $c++){	
	     $f_consulta022 = mysql_fetch_array($q_consulta022); 
	       $montoCredito2 = $f_consulta022['MontoAjuste'] + $montoCredito2;
	    
	  }
	   
	  /// ********************************************************************************************** ///
		///                                 CALCULANDO MONTO CREDITO ADICIONAL
		/// ********************************************************************************************** ///
		
		
	  
	  
	  
	  for($b=0; $b<$rwdet2; $b++){
	    $fdet2 = mysql_fetch_array($qrydet2);	
		$cont2 =  $cont2 + 1;
		   
                $montoAprobado1 = $montoAprobado1 + $fdet2['MontoAprobado'];
		$montoAjustado1 = $montoAjustado1 + $fdet2['MontoAjustado'];
		$montoCompromiso1 = $montoCompromiso1 + $fdet2['MontoCompromiso'];
		$montoCausado1 = $montoCausado1 + $fdet2['MontoCausado'];
		$montoPagado1 = $montoPagado1 + $fdet2['MontoPagado'];
		$montoReintegrado1 = $montoReintegrado1 + $fdet2['MontoReintegrado'];
		$montoDisponible1 = ($montoAjustado1 - $montoCompromiso1)+$montoReintegrado1;
	   
	  }
	  
	  
	  	  	 	  
	   $montoAprobado1 = number_format($montoAprobado1,2,',','.');
	   $montoAjustado1 = number_format($montoAjustado1,2,',','.');	   
	   $montoCredito2 = number_format($montoCredito2,2,',','.');	 
	   
	   $montoCompromiso1 = number_format($montoCompromiso1,2,',','.');
	   $montoCausado1 = number_format($montoCausado1,2,',','.');
	   $montoPagado1 = number_format($montoPagado1,2,',','.');
	   $montoReintegrado1 = number_format($montoReintegrado1,2,',','.');
	   $montoDisponible1 = number_format($montoDisponible1,2,',','.');
	   $montoDisminucion2 = number_format($montoDisminucion2,2,',','.');
	   $montoIncremento2 = number_format($montoIncremento2,2,',','.');
	  
	  
	  $montoGen=number_format($montoG,2,',','.');
	  $codigo_partida = $fpar2['cod_partida'];
	  $gCapturada = $fpar2['generica'];
	  $pCapturada2 = $fpar2['partida1'];
	  /// Monto Incrementado
	  $montoInc2 = number_format($montoConsulta02,2,',','.');
	  ///**** mostrando los resultados para partida 
	  $pdf->SetFillColor(202, 202, 202);
	  $pdf->SetFont('Arial', 'B', 7);
	  $pdf->SetWidths(array(18, 75,25,25,25,25,25,25,25,25,25,25));
	  $pdf->SetAligns(array('C','L','R','R','R','R','R','R','R','R','R','R'));
	  $pdf->Row(array($codigo_partida,$fpar2['denominacion'],$montoAprobado1,$montoIncremento2,$montoDisminucion2,$montoCredito2,$montoAjustado1,$montoCompromiso1,$montoCausado1,$montoPagado1,$montoReintegrado1,$montoDisponible1));
   }
  }
//// --------------------------------------------------------------------------------------------------
////             **** **** **** Capturando Partida Tipo "D" 301-01-01-00 ó 301-01-01-01
//// --------------------------------------------------------------------------------------------------
 if($fieldet['partida']!=00){
	 
	 //$montoDisponible2 = $fieldet['MontoAjustado'] - $fieldet['MontoCompromiso'];
	 
	 
	   /// ********************************************************************************************** ///
	///                                 CALCULANDO MONTO CREDITO ADICIONAL
	/// ********************************************************************************************** ///	
	
	 $montoCredito3 = 0;
         $montoIncremento3=0;
         $montoDisminucion3=0;
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
						   aj.Estado = 'AP' and
						   ajdet.cod_partida='".$fieldet['cod_partida']."' and aj.MotivoAjuste='CR'";		
	  $q_consulta033 = mysql_query($s_consulta033) or die ($s_consulta033.mysql_error());
	  $r_consulta033 = mysql_num_rows($q_consulta033); //echo $r_consulta03;
	
            for($c=0; $c<$r_consulta033; $c++){	
                   $f_consulta033 = mysql_fetch_array($q_consulta033); 
                   
                     $montoCredito3 = $f_consulta033['MontoAjuste'] + $montoCredito3;
                   
                }
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
	
        $TotalIn=$montoIncremento3+$TotalIn;
        $TotalDi=$montoDisminucion3+$TotalDi;
        $TotalCre=$montoCredito3+$TotalCre;
	 
	 $montoIncremento3 = number_format($montoIncremento3 ,2,',','.');
	 $montoDisminucion3 = number_format($montoDisminucion3 ,2,',','.');
	 $montoAprobado2 = number_format($fieldet['MontoAprobado'] ,2,',','.');
	 $montoAjustado2 = number_format($fieldet['MontoAjustado'] ,2,',','.');
	
	 
	 $montoCompromiso2 = number_format($fieldet['MontoCompromiso'] ,2,',','.');
	 $montoCausado2 = number_format($fieldet['MontoCausado'] ,2,',','.');
	 $montoPagado2 = number_format($fieldet['MontoPagado'] ,2,',','.');
	 $montoReintegro2 = number_format($fieldet['MontoReintegrado'] ,2,',','.');
	 $montoDisponible2 = number_format((($fieldet['MontoAjustado'] - $fieldet['MontoCompromiso']))+$fieldet['MontoReintegrado'],2,',','.');
	 
	 
	 $montoCreditoA2 = number_format($montoCredito3 ,2,',','.');	  	
	 $totalMontoAprobado =  $totalMontoAprobado + $fieldet['MontoAprobado'];
	 $totalMontoAjustado =  $totalMontoAjustado + $fieldet['MontoAjustado'];
	 
	 $totalMontoCompromiso = $totalMontoCompromiso + $fieldet['MontoCompromiso'];
	 $totalMontoCausado = $totalMontoCausado + $fieldet['MontoCausado'];
	 $totalMontoPagado = $totalMontoPagado + $fieldet['MontoPagado'];
	 $totalMontoReintegro = $totalMontoReintegro + $fieldet['MontoReintegrado'];
	 $totalDisponible = ($totalMontoAjustado - $totalMontoCompromiso)+$totalMontoReintegro;
	 
	 
     //$pdf->Cell(5,3.5,$fieldet['partida']);
	 $sql="SELECT denominacion FROM pv_partida WHERE cod_partida='".$fieldet['cod_partida']."'";
	 $qry=mysql_query($sql) or die ($sql.mysql_error());
	 $field=mysql_fetch_array($qry);
	 $monto=$fieldet['MontoAprobado'];
	 $montoT=$montoT + $monto;
	 $monto=number_format($monto,2,',','.');
	 $montoTotal=number_format($montoT,2,',','.');
	 $montoDet=number_format($fieldet['MontoAprobado']);
	 
	 
	 
	 
	
	 
	 
	 
	 
		 ///
	 $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	 $pdf->SetFont('Arial', '', 6.5);
	 $pdf->SetWidths(array(18, 75,25,25,25,25,25,25,25,25,25,25));
	$pdf->SetAligns(array('C','R','R','R','R','R','R','R','R','R','R','R'));
	 $pdf->Row(array($fieldet['cod_partida'],$field['denominacion'],$montoAprobado2,$montoIncremento3,$montoDisminucion3,$montoCreditoA2,$montoAjustado2,$montoCompromiso2,$montoCausado2, $montoPagado2,$montoReintegro2, $montoDisponible2));
 }
}
	///// *** Mostrar *** /////
	 $totalMontoAprobado =  number_format($totalMontoAprobado,2,',','.');
	 $totalMontoAjustado =  number_format($totalMontoAjustado,2,',','.');
	 
	 
	 
	 $TotalIn = number_format($TotalIn,2,',','.');
	 $TotalDi = number_format($TotalDi,2,',','.');
	 $TotalCre = number_format($TotalCre,2,',','.');
	 $totalMontoCompromiso = number_format($totalMontoCompromiso,2,',','.');
	 $totalMontoCausado = number_format($totalMontoCausado,2,',','.');
	 $totalMontoPagado = number_format($totalMontoPagado,2,',','.');
	 $totalMontoReintegro = number_format($totalMontoReintegro,2,',','.');
	 $totalDisponible = number_format($totalDisponible,2,',','.');
	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(202, 202, 202); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8.5);
	 $pdf->SetWidths(array(18, 75,25,25,25,25,25,25,25,25,25,25));
	$pdf->SetAligns(array('C','R','R','R','R','R','R','R','R','R','R','R'));
	$pdf->Row(array('' ,'Total:',$totalMontoAprobado,$TotalIn,$TotalDi,$TotalCre,$totalMontoAjustado,$totalMontoCompromiso,$totalMontoCausado,$totalMontoPagado,$totalMontoReintegro,$totalDisponible));
	/////
	
	    $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(100,10,'',0,1,'L');
//	$pdf->Cell(70,10,'ELABORADO POR:',0,0,'L');$pdf->Cell(70,10,'REVISADO POR:',0,0,'L');$pdf->Cell(70,10,'CONFORMADO POR:',0,1,'L');$pdf->Cell(70,10,'APROBADOPOR POR:',0,1,'L');
	//$pdf->Cell(100,5,'',0,0,'L');$pdf->Cell(120,5,'',0,0,'L');$pdf->Cell(100,5,'',0,1,'L');
	 //$pdf->Cell(50,5,utf8_decode ('Lcda: Sorielma Salmerón'),1,0,'L');$pdf->Cell(50,5,'Lcda: Milagros Rivas Mata',1,0,'L');$pdf->Cell(50,5,'Lcdo. Cesar Granado',1,1,'L');$pdf->Cell(50,5,'Lcdo. Freddy Cudjoe',1,1,'L'); 
	 $pdf->SetWidths(array(90, 90,90));
	 
	 $pdf->SetAligns(array('L','L','L'));
	  $pdf->Row(array('ELABORADO POR:','APROBADO POR:','REVISADO POR:'),0);
	  $pdf->Ln(5);
	$pdf->Row(array(utf8_decode ('Lcda: Sorielma Salmerón'),'Lcda: Roxaida Estrada','Lcdo. Cesar Granado'));
	 $pdf->Row(array(utf8_decode ('JEFE  DIVISIÓN  DE PRESUPUESTO Y CONTABILIDAD'),utf8_decode ('DIRECTORA DE ADMINISTRACIÓN Y PRESUPUESTO (E)'),'DIRECTOR GENERAL'));
	 //$pdf->Row(array(utf8_decode ('Lcda: Sorielma Salmerón'),'Lcda: Milagros Rivas Mata','Lcda. Rocio Toussaint'));
	 //$pdf->Row(array(utf8_decode ('JEFE  DIVISIÓN  DE PRESUPUESTO Y CONTABILIDAD'),utf8_decode ('DIRECTORA DE ADMINISTRACIÓN Y PRESUPUESTO'),'DIRECTORA GENERAL (E)'));
	
	 
	
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
