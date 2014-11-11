<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');
require('fphp.php');
connect(); 
extract ($_POST);
extract ($_GET);
/*global $fechaDesde;
global $fechaHasta;
global $periodoDesde;
global $periodoHasta;
global $Periodo;*/


global $mesReferencia ;
global $anio;
global $codPresupuesto;
global $mesesletras;
global $periodoInicial;

//global $Periodo;
//echo $_SESSION["MYSQL_BD"];
/// ----------------------------------------------------
//---------------------------------------------------
/*$filtro=strtr($filtro, "*", "'");
$filtro2=strtr($filtro2, "*", "'");
$filtro3=strtr($filtro3, "*", "'");*/

//$Periodo = $Periodo;
//$filtro=strtr($filtro, ";", "%");
//---------------------------------------------------
//---------------------------------------------------
//echo $Periodo;
class PDF extends FPDF
{
//Page header
function Header(){
    	/*global $fechaDesde; $dateDesde=explode('-',$fechaDesde);
		global $fechaHasta; $dateHasta=explode('-',$fechaHasta);*/
        
		$mesReferencia 	= $_REQUEST['mesReferencia'];
 		$anio 			= $_REQUEST['anio'];
 		$codPresupuesto = $_REQUEST['codPresupuesto'];
 		$mesesletras 	= $_REQUEST['mesesletras'];

	//global $Periodo;
	
	
	$mesesletras = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Nobiembre','Diciembre');
	
	/*global $mesReferencia;
	global $anio;
	global $codPresupuesto;
	global $mesesletras  = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Nobiembre','Diciembre');*/

	$this->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$this->SetFont('Arial', 'B', 8);
	$this->SetXY(20, 10); $this->Cell(200, 5,( 'Contraloría del estado Monagas'), 0, 0, 'L');
	                      $this->Cell(10,5,'Fecha:',0,0,'');$this->Cell(10,5,date('d/m/Y'),0,1,'');
	$this->SetXY(20, 15); $this->Cell(200, 5, ('Dirección de Administración y Presupuesto'), 0, 0, 'L');
	                       $this->Cell(10,5,('Página:'),0,1,'');
	$this->SetXY(19, 20); $this->Cell(204, 5, '', 0, 0, 'L');
	                       $this->Cell(7,5,('Año:'),0,0,'L');$this->Cell(6,5,date('Y'),0,1,'L');
						   
	/*list($fano, $fmes) = SPLIT('[-]',$Periodo);
    switch ($fmes) {
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
	$this->SetFont('Arial', 'B', 12);
	$this->Cell(105, 10, '', 0, 0, 'C');
	$this->Cell(47, 10, ('Ejecución Presupuestaria del '.date("d/m",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1)).'/'.$anio), 0, 1, 'C');
//$this->Cell(10, 10, '', 0, 1, 'C');
	///// PRUEBA ***********
	$this->SetFont('Arial', 'B', 8);
	
	$sql =  "SELECT Sector,Programa,SubPrograma,Proyecto,Actividad,Organismo,CodPresupuesto,UnidadEjecutora 
	                    FROM pv_presupuesto 
					   WHERE Organismo<>'' $filtro"; //echo $sql;
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
	
	
	$this->SetFont('Arial', '', 6);
	$this->Cell(27, 3, 'SECTOR:', 0, 0, 'L');$this->Cell(3, 3, $fieldSector[1], 0, 0, 'L');$this->Cell(30, 3, $fieldSector['descripcion'], 0, 1, 'L');
	$this->Cell(27, 3, 'PROGRAMA:', 0, 0, 'L');$this->Cell(3, 3, $fieldPrograma[1], 0, 0, 'L');$this->Cell(30, 3, $fieldPrograma[0], 0, 1, 'L');
	$this->Cell(27, 3, 'SUBPROGRAMA:', 0, 0, 'L');$this->Cell(3, 3, $fieldSubprog[1], 0, 0, 'L');$this->Cell(30, 3, $fieldSubprog[0], 0, 1, 'L');
	$this->Cell(27, 3, 'PROYECTO:', 0, 0, 'L');$this->Cell(3, 3, $fieldProyecto[1], 0, 0, 'L');$this->Cell(30, 3, $fieldProyecto[0], 0, 1, 'L');
	$this->Cell(27, 3, 'ACTIVIDAD:', 0, 0, 'L');$this->Cell(3, 3, $fieldActividad[1], 0, 0, 'L');$this->Cell(30, 3, $fieldActividad[0], 0, 1, 'L');
	$this->Cell(27, 3, 'UNIDAD EJECUTORA:', 0, 0, 'L');$this->Cell(30, 3, $fieldOrg[0], 0, 1, 'L');
	$this->SetDrawColor(0, 0, 0); $this->SetFillColor(200, 200, 200); $this->SetTextColor(0, 0, 0);
	$this->SetFont('Arial', 'B', 5.5);
	
	
	//aqui va
	

	//$this->MultiCell(15, 3, 'Sueldo al '.date("d/m",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1)).'/'.$anio,1,'L',1);
	//$a=15;
	//$cont=0;
	
	
		    $cont_=3;
			$money_[0]='PAR GE ESP SE';//CODIGO DE LA PARTIDA
			$arraySetWidths_[0]='20';
			$arraySetAligns_[0]='C';
			
			$money_[1]='DENOMINACION';//DESCRIPCIÓN DE LA PARTIDA
			$arraySetWidths_[1]='50';
			$arraySetAligns_[1]='L';
			
			$money_[2]='Saldo Ini al '.date("d/m",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1)).'/'.$anio;//SALDO INICIAL
			$arraySetWidths_[2]='14';
			$arraySetAligns_[2]='R';
			
			for($i=$mesReferencia+1; $i<=12; $i++){			 												
				$money_[$cont_] = 'Egreso al '.date("d/m",(mktime(0,0,0,$i+1,1,$anio)-1)).'/'.$anio;
				$money_[$cont_+1] = 'Disponible al '.date("d/m",(mktime(0,0,0,$i+1,1,$anio)-1)).'/'.$anio;	
				$arraySetWidths_[$cont_]='14';
				$arraySetAligns_[$cont_]='R';				
																			
				$arraySetWidths_[$cont_+1]='14';				
				$arraySetAligns_[$cont_+1]='R';
				$cont_=$cont_+2;				
			}
		//	$this->SetFillColor(255, 255, 255);
		//	$this->SetFont('Arial', 'B', 5);
			$this->SetWidths($arraySetWidths_);
			$this->SetAligns($arraySetAligns_);
			$this->Row($money_);
	
	
	
	

	
	
	///// ******************	
}
//Page footer
function Footer(){
    //Position at 1.5 cm from bottom
    $this->SetXY(60,12.5);//125.13
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
$pdf->SetFont('Times','',5.5);


// INSERTANDO


$sqlCon = "select 
                 CodPresupuesto,
				 Organismo 
		    from 
			     pv_presupuesto 
		    where
				EjercicioPpto='$anio' and Organismo<>'' $filtro"; //echo $sqlCon;
$qryCon = mysql_query($sqlCon) or die ($sqlCon.mysql_error());
$rowCon = mysql_num_rows($qryCon); //echo $rowCon;
$fieldCon = mysql_fetch_array($qryCon);

$sqlDet="SELECT cod_partida,
                MontoAprobado,
                partida,
				generica,
				especifica,
			    subespecifica,
				tipocuenta,
				CodPresupuesto,
				Organismo,
				FlagsReformulacion ,
				MontoAjustado,
				MontoCompromiso
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
//// **** **** **** **** Capturando Partida Tipo "T" 301-00-00-00 ******* ********* ******* ********
//// ----------------------------------------------------------------------------------------------- */
 $montoAprob = 0; $montoAprobado01 = 0; $montoAj = 0; $montoAjuste02 = 0; $montoCompromisoPartida = 0; $montoCausados = 0; 
 $montoCausados01 = 0; $montoPago = 0; $montoPago01 = 0;   $montoCompromisoPartida01 = 0;
 $ajustado=0; $compromiso=0;
 
 
 if(($fieldet['partida']!=00) and (($cont1==0) or ($pCapturada!=$fieldet['partida']))){
 //echo $cont1.'-';
	 //echo '$cont1='.$cont1.'-'.$pCapturada.'-'.$fieldet['partida'].'***';
     	 $sqlPar="SELECT cod_partida,partida1,denominacion,cod_tipocuenta 
			 FROM pv_partida 
			WHERE partida1='".$fieldet['partida']."' AND 
			      cod_tipocuenta='".$fieldet['tipocuenta']."' AND 
				  tipo='T' AND 
				  generica='00'";
  $qryPar=mysql_query($sqlPar) or die ($sqlPar.mysql_error());
  $rwPar=mysql_num_rows($qryPar);
  
  if($rwPar!=0){
   $fpar=mysql_fetch_array($qryPar);
   $montoP=0; $cont1=0; $montoConsulta01=0;
   $sqldet="SELECT *
		      FROM pv_presupuestodet 
		     WHERE partida='".$fpar['partida1']."' AND 
			       tipocuenta='".$fpar['cod_tipocuenta']."' AND 
				   CodPresupuesto='".$fieldet['CodPresupuesto']."'"; //echo $sqldet;
   $qrydet=mysql_query($sqldet) or die ($sqldet.mysql_error());
   $rwdet=mysql_num_rows($qrydet);
   
   for($a=0; $a<$rwdet; $a++){
	   
    $fdet=mysql_fetch_array($qrydet);
	$montoAprob = $montoAprob + $fdet['MontoAprobado'];
    $cont1 = $cont1 + 1;
	
	$ajustado=$ajustado+$fdet['MontoAjustado'];
	$compromiso=$compromiso+$fdet['MontoCompromiso'];
	
	
	
	/// ********************************************************************************************** ///
	///                                 CALCULANDO MONTO AJUSTADO
	/// ********************************************************************************************** ///	
	$sajuste = "SELECT 
					a.MontoAjuste,
					b.TipoAjuste
				FROM 
					pv_ajustepresupuestariodet a 
					inner join pv_ajustepresupuestario b on (b.Organismo = a.Organismo) and 
														 (b.CodAjuste = a.CodAjuste) and 
														 (b.FechaAprobacion>='".$anio.'-'.date("m",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."-01' and 
													      b.FechaAprobacion<='".$anio.'-'.date("m-d",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."' and 
														  b.Estado='AP')
				WHERE 
					a.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
					a.Organismo = '".$fdet['Organismo']."' and 
					a.cod_partida = '".$fdet['cod_partida']."'";
	$qajuste = mysql_query($sajuste) or die ($sajuste.mysql_error());
	$rajuste = mysql_num_rows($qajuste);
	if($rajuste!=0){
	  for($y=0; $y<$rajuste; $y++){
		 $fajuste = mysql_fetch_array($qajuste);
		 if($fajuste['TipoAjuste']=='DI') $montoAj = $montoAj + (-1*($fajuste['MontoAjuste']));
		 else $montoAj = $montoAj + $fajuste['MontoAjuste'];
	  }
	  $montoAj = $montoAj + $fdet['MontoAprobado'];
	}else $montoAj = $montoAj + $fdet['MontoAprobado'];
		
	
	/// ********************************************************************************************** ///
		///                                 CALCULANDO MONTO COMPROMISO
		/// ********************************************************************************************** ///
		
			//echo $fieldet['cod_partida'];
			
			/*$mesReferencia = $_REQUEST['mesReferencia'];
			$anio = $_REQUEST['anio'];*/
			$periodoEgreso = $anio.'-'.date("m",(mktime(0,0,0,$mesReferencia+1,1,$anio))-1);
				
			$scompro="(select
					   sum(a.Monto) as Monto_lg
				from 
					  lg_distribucioncompromisos a 
					  inner join ap_obligaciones b on (b.NroDocumento=a.NroDocumento)and
													  (b.CodProveedor=a.CodProveedor)and
													  (b.CodTipodocumento=a.CodTipodocumento)and
													  (b.CodOrganismo=a.CodOrganismo)and
													  (b.Periodo<='$periodoEgreso' and 											  
													   b.Estado='PA' and 
													   b.FlagPresupuesto='S')
				where 
					  a.CodOrganismo='".$fdet['Organismo']."' and 
					  a.Periodo>='$periodoEgreso' and 
					  
					  a.Estado='CO' and 
					  a.cod_partida = '".$fdet['cod_partida']."')
				UNION
				(select
					   sum(a.Monto) as Monto_lg
				from 
					  lg_distribucioncompromisos a 
					  inner join lg_ordenservicio c on (c.NroOrden=a.NroDocumento)and
													   (c.CodOrganismo=a.CodOrganismo)and
													   (c.FechaAprobacion>='".$anio.'-'.date("m",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."-01' and
													    c.FechaAprobacion<='".$anio.'-'.date("m-d",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."' and 											   
														c.Estado<>'AN')
				where 
					  a.CodOrganismo='".$fdet['Organismo']."' and 
					  a.Periodo<='$periodoEgreso' and 			 
					  a.Estado='CO' and 
					  a.CodTipoDocumento='OS' and 
					  a.cod_partida = '".$fdet['cod_partida']."')
				UNION
				(select
					   sum(a.Monto) as Monto_lg
				from 
					  lg_distribucioncompromisos a 
					  inner join lg_ordencompra d on (d.NroOrden=a.NroDocumento)and
													 (d.CodOrganismo=a.CodOrganismo)and
													 (d.FechaAprobacion >='".$anio.'-'.date("m",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."-01' and 
													 d.FechaAprobacion <='".$anio.'-'.date("m-d",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."' and 											  
													  d.Estado<>'AN')
				where 
					  a.CodOrganismo='".$fdet['Organismo']."' and 
					  a.Periodo<='$periodoEgreso' and 			 
					  a.Estado='CO' and 
					  a.CodTipoDocumento='OC' and 
					  a.cod_partida = '".$fdet['cod_partida']."')
				UNION
				(select
					 sum(a.Monto) as Monto_lg
				from 
					  lg_distribucioncompromisos a 
					  inner join ap_bancotransaccion e on (e.CodigoReferenciaBanco=a.NroDocumento)and
														  (e.CodOrganismo=a.CodOrganismo)and
														  (e.FechaTransaccion>='".$anio.'-'.date("m",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."-01' and
														  e.FechaTransaccion<='".$anio.'-'.date("m-d",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."' and 												  
														   e.Estado<>'AN' and 
														   e.FlagPresupuesto='S')
				where 
					  a.CodOrganismo='".$fdet['Organismo']."' and 
					  a.Periodo>='$periodoEgreso' and 					  					 
					  a.Estado='CO' and 
					  a.cod_partida = '".$fdet['cod_partida']."')"; //echo $scompro;
		$qcompro = mysql_query($scompro) or die ($scompro.mysql_error());
		$rcompro = mysql_num_rows($qcompro); //echo $rcompro;
		if($rcompro!=0){
		  for($x=0; $x<$rcompro; $x++){
			 $fcompro= mysql_fetch_array($qcompro); 
			 $montoCompromisoPartida = $montoCompromisoPartida + $fcompro['Monto_lg'];
		  }
		}
		
		
		
	}//FOR
			$pCapturada=$fieldet['partida'];
			
			$disponible =$ajustado-$compromiso;
			$cont_1=3;
			$money55[0]=$fpar['cod_partida'];//CODIGO DE LA PARTIDA
			$arraySetWidths55[0]='20';
			$arraySetAligns55[0]='C';
			$money55[1]=$fpar['denominacion'];//DESCRIPCIÓN DE LA PARTIDA
			$arraySetWidths55[1]='50';
			$arraySetAligns55[1]='L';
			$money55[2]=number_format($disponible,2,',','.');;//SALDO INICIAL
			$arraySetWidths55[2]='14';
			$arraySetAligns55[2]='R';
			$disponible01=	$disponible-$montoCompromisoPartida;	
			for($y=$mesReferencia+1; $y<=12; $y++){	
			    	 												
				$money55[$cont_1] = number_format($montoCompromisoPartida,2,',','.');//Egreso
				$money55[$cont_1+1] = number_format($disponible01,2,',','.');//Disponible
				$arraySetWidths55[$cont_1]='14';
				$arraySetWidths55[$cont_1+1]='14';
				$arraySetAligns55[$cont_1]='R';
				$arraySetAligns55[$cont_1+1]='R';	
				$cont_1=$cont_1+2;
				$disponible01=	$disponible01-$montoCompromisoPartida;
			}
			
			$pdf->SetFillColor(215, 215, 215);
			$pdf->SetFont('Arial', 'B', 5);
			$pdf->SetWidths($arraySetWidths55);
			$pdf->SetAligns($arraySetAligns55);
			$pdf->Row($money55);    
			
	}//IF $rwPar!=0     				    
 }//$fieldet['partida']!=00) and (($cont1==0) or ($pCapturada!=$fieldet['partida']
 

			
			
 
 
 
 
  //// ----------------------------------------------------------------------------------------------- */
 ////           **** **** **** Capturando Partida Tipo "T" 301-01-00-00
 //// ----------------------------------------------------------------------------------------------- */
 $montoAprob2 = 0; $montoAprobado02 = 0; $montoAj2 = 0; $montoAjuste02 = 0; $montoCompromisoPartida2 = 0; $montoCausados2 = 0; 
 $montoCausados02 = 0; $montoPago2 = 0; $montoPago02 = 0;   $montoCompromisoPartida02 = 0; $montoAj2DI =0; $montoAj2IN = 0;
 
 $ajustado2=0;
		$compromiso2=0;
 
if(($fieldet['partida']!=00) and (($cont2==0) or ($gCapturada!=$fieldet['generica']) or ($pCapturada2!=$fieldet['partida']))){
$sql2="SELECT cod_partida,partida1,denominacion,cod_tipocuenta,generica,tipo 
			FROM pv_partida 
		   WHERE partida1='".$fieldet['partida']."' AND
				 cod_tipocuenta='".$fieldet['tipocuenta']."' AND
				 tipo='T' AND 
				 generica='".$fieldet['generica']."' AND 
				 especifica='00'";
$qry2=mysql_query($sql2) or die ($sql2.mysql_error());
$rows2=mysql_num_rows($qry2);

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
   $montoAprob2 = $montoAprob2 + $fdet2['MontoAprobado'];
   $cont2 = $cont2 + 1;
/// ********************************************************************************************** ///
///                                 CALCULANDO MONTO AJUSTADO
/// ********************************************************************************************** ///	
$sajuste = "SELECT 
				a.MontoAjuste,
				b.TipoAjuste,
				a.cod_partida
			FROM 
				pv_ajustepresupuestariodet a 
				inner join pv_ajustepresupuestario b on (b.Organismo = a.Organismo) and 
													    (b.CodAjuste = a.CodAjuste) and 
													    (b.FechaAprobacion>='".$anio.'-'.date("m",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."-01' and 
													    b.FechaAprobacion<='".$anio.'-'.date("m-d",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."' and 
													     b.Estado='AP')
			WHERE 
				a.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
				a.Organismo = '".$fdet2['Organismo']."' and 
				a.cod_partida = '".$fdet2['cod_partida']."'";
$qajuste = mysql_query($sajuste) or die ($sajuste.mysql_error());
$rajuste = mysql_num_rows($qajuste);
if($rajuste!=0){
  for($y=0; $y<$rajuste; $y++){
     $fajuste = mysql_fetch_array($qajuste); 
	 //echo 'Partida= '.$fajuste['cod_partida'].'TipoAjuste='.$fajuste['TipoAjuste'].'*/ Montoajuste = '.$fajuste['MontoAjuste'];
	 if($fajuste['TipoAjuste']=='DI') $montoAj2 = $montoAj2 +(-1*($fajuste['MontoAjuste']));
	 else $montoAj2 = $montoAj2 + $fajuste['MontoAjuste'];
  }
  $montoAj2 = $montoAj2 + $fdet2['MontoAprobado'];
}else $montoAj2 = $montoAj2 + $fdet2['MontoAprobado'];






//echo '*/ MontoAjuste2= '.$montoAj2;
/// ********************************************************************************************** ///
///                                 CALCULANDO MONTO COMPROMISO
/// ********************************************************************************************** ///
 	 $scompro="(select
					   sum(a.Monto) as Monto_lg
				from 
					  lg_distribucioncompromisos a 
					  inner join ap_obligaciones b on (b.NroDocumento=a.NroDocumento)and
													  (b.CodProveedor=a.CodProveedor)and
													  (b.CodTipodocumento=a.CodTipodocumento)and
													  (b.CodOrganismo=a.CodOrganismo)and
													  (b.Periodo<='$periodoEgreso' and 											  
													   b.Estado='PA' and 
													   b.FlagPresupuesto='S')
				where 
					  a.CodOrganismo='".$fdet2['Organismo']."' and 
					  a.Periodo>='$periodoEgreso' and 
					  
					  a.Estado='CO' and 
					  a.cod_partida = '".$fdet2['cod_partida']."')
				UNION
				(select
					   sum(a.Monto) as Monto_lg
				from 
					  lg_distribucioncompromisos a 
					  inner join lg_ordenservicio c on (c.NroOrden=a.NroDocumento)and
													   (c.CodOrganismo=a.CodOrganismo)and
													   (c.FechaAprobacion>='".$anio.'-'.date("m",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."-01' and
													   c.FechaAprobacion<='".$anio.'-'.date("m-d",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."' and 											   
														c.Estado<>'AN')
				where 
					  a.CodOrganismo='".$fdet2['Organismo']."' and 
					  a.Periodo<='$periodoEgreso' and 			 
					  a.Estado='CO' and 
					  a.CodTipoDocumento='OS' and 
					  a.cod_partida = '".$fdet2['cod_partida']."')
				UNION
				(select
					   sum(a.Monto) as Monto_lg
				from 
					  lg_distribucioncompromisos a 
					  inner join lg_ordencompra d on (d.NroOrden=a.NroDocumento)and
													 (d.CodOrganismo=a.CodOrganismo)and
													 (d.FechaAprobacion >='".$anio.'-'.date("m",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."-01' and
													 d.FechaAprobacion <='".$anio.'-'.date("m-d",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."' and 											  
													  d.Estado<>'AN')
				where 
					  a.CodOrganismo='".$fdet2['Organismo']."' and 
					  a.Periodo<='$periodoEgreso' and 			 
					  a.Estado='CO' and 
					  a.CodTipoDocumento='OC' and 
					  a.cod_partida = '".$fdet2['cod_partida']."')
				UNION
				(select
					 sum(a.Monto) as Monto_lg
				from 
					  lg_distribucioncompromisos a 
					  inner join ap_bancotransaccion e on (e.CodigoReferenciaBanco=a.NroDocumento)and
														  (e.CodOrganismo=a.CodOrganismo)and
														  (e.FechaTransaccion>='".$anio.'-'.date("m",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."-01' and
														  e.FechaTransaccion<='".$anio.'-'.date("m-d",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."' and 												  
														   e.Estado<>'AN' and 
														   e.FlagPresupuesto='S')
				where 
					  a.CodOrganismo='".$fdet2['Organismo']."' and 
					  a.Periodo<='$periodoEgreso' and 
					  					 
					  a.Estado='CO' and 
					  a.cod_partida = '".$fdet2['cod_partida']."')"; //echo $scompro;
$qcompro = mysql_query($scompro) or die ($scompro.mysql_error());
$rcompro = mysql_num_rows($qcompro); //echo $rcompro;
if($rcompro!=0){
  for($x=0; $x<$rcompro; $x++){
     $fcompro= mysql_fetch_array($qcompro); 
	 //if($fcompro['cod_partida_lg']=='401.07.26.00')echo $fcompro['Monto_lg'];
     $montoCompromisoPartida2 = $montoCompromisoPartida2 + $fcompro['Monto_lg'];
  }
}
$montoCompromisoPartida02 = number_format($montoCompromisoPartida2,2,',','.');



///DISPONIBILIDAD ACTUAL

	$sqlD2= "SELECT *
	FROM pv_presupuestodet
	WHERE cod_partida = '".$fdet2['cod_partida']."'
	AND Organismo = '".$fdet2['Organismo']."'
	AND CodPresupuesto = '".$fieldet['CodPresupuesto']."'";
	$qDisp2 = mysql_query($sqlD2) or die ($sqlD2.mysql_error());
	$rDisp2 = mysql_num_rows($qDisp2);
	$fDisp2= mysql_fetch_array($qDisp2);
	
	//for($t=0; $t<$rDisp2;$t++)
	//{
		$ajustado2=$ajustado2+$fDisp2['MontoAjustado'];
		$compromiso2=$compromiso2+$fDisp2['MontoCompromiso'];

}




		
		//$disponible02=$ajustado2-$compromiso2;
	//}
			$disponible2=0;

 
			$codigo_partida = $fpar2['cod_partida'];
			$gCapturada = $fpar2['generica'];
			$pCapturada2 = $fpar2['partida1'];
			$disponible2=($ajustado2 - $compromiso2);

			$cont_2=3;
			$money2[0]=$codigo_partida;//CODIGO DE LA PARTIDA
			$arraySetWidths2[0]='20';
			$arraySetAligns2[0]='C';
			$money2[1]=$fpar2['denominacion'];//DESCRIPCIÓN DE LA PARTIDA
			$arraySetWidths2[1]='50';
			$arraySetAligns2[1]='L';
			$money2[2]=number_format($disponible2,2,',','.');;//SALDO INICIAL
			$arraySetWidths2[2]='14';
			$arraySetAligns2[2]='R';
			$disponible02=$disponible2-$montoCompromisoPartida2;
			for($j=$mesReferencia+1; $j<=12; $j++){	
			    												
				$money2[$cont_2] = number_format($montoCompromisoPartida2,2,',','.');//Egreso
				$money2[$cont_2+1] = number_format($disponible02,2,',','.');//Disponible
				$arraySetWidths2[$cont_2]='14';
				$arraySetWidths2[$cont_2+1]='14';
				$arraySetAligns2[$cont_2]='R';
				$arraySetAligns2[$cont_2+1]='R';	
				$cont_2=$cont_2+2;
				 $disponible02=$disponible02-$montoCompromisoPartida2; 			
				/*$this->Cell(15, 3, 'Egreso al '.date("d/m",(mktime(0,0,0,$i+1,1,$anio)-1)).'/'.$anio, 1, 0, 'L', 1);
				$this->Cell(15, 3, 'Disp. al '.date("d/m",(mktime(0,0,0,$i+1,1,$anio)-1)).'/'.$anio, 1, 0, 'L', 1);*/
			}
			//$pdf->SetFillColor(255, 255, 255);
			$pdf->SetFillColor(235, 235, 235);
			$pdf->SetFont('Arial', 'B', 5);
			$pdf->SetWidths($arraySetWidths2);
			$pdf->SetAligns($arraySetAligns2);
			$pdf->Row($money2);


}
}
 
 
 //// ----------------------------------------------------------------------------------------------- */
//// **** **** **** Capturando Partida Tipo "D" 301-01-01-00 ó 301-01-01-01
//// ----------------------------------------------------------------------------------------------- */
$montoCompromisoPartida03 = 0; $montoCompromisoPartida3 = 0; $montoAj03 = 0; $montoAj3 = 0;
$montoCausados03 = 0; $montoCausados3 = 0; $montoPago03 = 0; $montoPago3 = 0; $montoAprobado03 = 0;

if($fieldet['generica']!=00 && $fieldet['subespecifica']>=00){
//$montoPagadoT=0.00;
	 
	 $sql="SELECT denominacion FROM pv_partida WHERE cod_partida='".$fieldet['cod_partida']."'";
	 $qry=mysql_query($sql) or die ($sql.mysql_error());
	 $field=mysql_fetch_array($qry);

if($fieldet['FlagsReformulacion']=='S'){
   $sreformulacion = "select 
  							a.*,b.* 
					    from 
						    pv_reformulacionppto a 
							inner join pv_reformulacionpptodet b on (b.Organismo = a.Organismo)and
																	(b.CodPresupuesto = a.CodPresupuesto)and 
																	(b.CodRef = a.CodRef)and
																	(b.cod_partida='".$fieldet['cod_partida']."') 
					   where 
					        a.Estado='AP' and 
							a.Organismo = '".$fieldet['Organismo']."' and 
							a.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
    						a.FechaAprobacion >= '".$anio.'-'.date("m",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."-01' and 
    						a.FechaAprobacion <= '".$anio.'-'.date("m-d",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."'";
   $qreformulacion = mysql_query($sreformulacion) or die ($sreformulacion.mysql_error());
   $rreformulacion = mysql_num_rows($qreformulacion); 
   if($rreformulacion!=0){
/// ********************************************************************************************** ///
///                                 CALCULANDO MONTO AJUSTADO
/// ********************************************************************************************** ///	
/// ********************************************************************************************** ///
///                                 CALCULANDO MONTO AJUSTADO
/// ********************************************************************************************** ///	

$sajuste = "SELECT 
				a.MontoAjuste,
				b.TipoAjuste
			FROM 
				pv_ajustepresupuestariodet a 
				inner join pv_ajustepresupuestario b on (b.Organismo = a.Organismo) and 
													 (b.CodAjuste = a.CodAjuste) and 
													 (b.FechaAprobacion>='".$anio.'-'.date("m",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."-01' and 
													  b.FechaAprobacion<='".$anio.'-'.date("m-d",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."' and 
													  b.Estado='AP')
			WHERE 
				a.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
				a.Organismo = '".$fieldet['Organismo']."' and 
				a.cod_partida = '".$fieldet['cod_partida']."'";
$qajuste = mysql_query($sajuste) or die ($sajuste.mysql_error());
$rajuste = mysql_num_rows($qajuste);
if($rajuste!=0){
  for($y=0; $y<$rajuste; $y++){
     $fajuste = mysql_fetch_array($qajuste);
	 if($fajuste['TipoAjuste']=='DI') $montoAj3 = $montoAj3 + (-1*($fajuste['MontoAjuste']));
	 else $montoAj3 = $montoAj3 + $fajuste['MontoAjuste'];
  }
  $montoAj3 = $montoAj3 + $fieldet['MontoAprobado'];
}else $montoAj3 = $montoAj3 + $fieldet['MontoAprobado'];


//echo $montoCA3;
/// ********************************************************************************************** ///
///                                 CALCULANDO MONTO COMPROMISO
/// ********************************************************************************************** ///
	$scompro="(select
					   sum(a.Monto) as Monto_lg
				from 
					  lg_distribucioncompromisos a 
					  inner join ap_obligaciones b on (b.NroDocumento=a.NroDocumento)and
													  (b.CodProveedor=a.CodProveedor)and
													  (b.CodTipodocumento=a.CodTipodocumento)and
													  (b.CodOrganismo=a.CodOrganismo)and
													  (b.Periodo<='$periodoEgreso' and 											  
													   b.Estado='PA' and 
													   b.FlagPresupuesto='S')
				where 
					  a.CodOrganismo='".$fieldet['Organismo']."' and 
					  a.Periodo>='$periodoEgreso' and 
					  
					  a.Estado='CO' and 
					  a.cod_partida = '".$fieldet['cod_partida']."')
				UNION
				(select
					   sum(a.Monto) as Monto_lg
				from 
					  lg_distribucioncompromisos a 
					  inner join lg_ordenservicio c on (c.NroOrden=a.NroDocumento)and
													   (c.CodOrganismo=a.CodOrganismo)and
													   (c.FechaAprobacion>='".$anio.'-'.date("m",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."-01' and
													   c.FechaAprobacion<='".$anio.'-'.date("m-d",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."' and 											   
														c.Estado<>'AN')
				where 
					  a.CodOrganismo='".$fieldet['Organismo']."' and 
					  a.Periodo<='$periodoEgreso' and 			 
					  a.Estado='CO' and 
					  a.CodTipoDocumento='OS' and 
					  a.cod_partida = '".$fieldet['cod_partida']."')
				UNION
				(select
					   sum(a.Monto) as Monto_lg
				from 
					  lg_distribucioncompromisos a 
					  inner join lg_ordencompra d on (d.NroOrden=a.NroDocumento)and
													 (d.CodOrganismo=a.CodOrganismo)and
													 (d.FechaAprobacion>='".$anio.'-'.date("m",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."-01' and
													 d.FechaAprobacion <='".$anio.'-'.date("m-d",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."' and 											  
													  d.Estado<>'AN')
				where 
					  a.CodOrganismo='".$fieldet['Organismo']."' and 
					  a.Periodo<='$periodoEgreso' and 			 
					  a.Estado='CO' and 
					  a.CodTipoDocumento='OC' and 
					  a.cod_partida = '".$fieldet['cod_partida']."')
				UNION
				(select
					 sum(a.Monto) as Monto_lg
				from 
					  lg_distribucioncompromisos a 
					  inner join ap_bancotransaccion e on (e.CodigoReferenciaBanco=a.NroDocumento)and
														  (e.CodOrganismo=a.CodOrganismo)and
														   
														  (e.FechaTransaccion>='".$anio.'-'.date("m",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."-01' and

														  e.FechaTransaccion<='".$anio.'-'.date("m-d",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."' and 												  
														   e.Estado<>'AN' and 
														   e.FlagPresupuesto='S')
				where 
					  a.CodOrganismo='".$fieldet['Organismo']."' and 
					  a.Periodo<='$periodoEgreso' and 					  					 
					  a.Estado='CO' and 
					  a.cod_partida = '".$fieldet['cod_partida']."')"; //echo $scompro;
$qcompro = mysql_query($scompro) or die ($scompro.mysql_error());
$rcompro = mysql_num_rows($qcompro); 
if($rcompro!=0){
  for($x=0; $x<$rcompro; $x++){
     $fcompro= mysql_fetch_array($qcompro); 
     $montoCompromisoPartida3 = $montoCompromisoPartida3 + $fcompro['Monto_lg'];
  }
}
$montoCompromisoPartida03 = number_format($montoCompromisoPartida3,2,',','.');



///DISPONIBILIDAD ACTUAL

	$sqlD= "SELECT *
	FROM pv_presupuestodet
	WHERE cod_partida = '".$fieldet['cod_partida']."'
	AND Organismo = '".$fieldet['Organismo']."'
	AND CodPresupuesto = '".$fieldet['CodPresupuesto']."'";
	$qDisp = mysql_query($sqlD) or die ($sqlD.mysql_error());
	$rDisp = mysql_num_rows($qDisp);
	$fDisp= mysql_fetch_array($qDisp);
	
	$disponible03=$fDisp['MontoAjustado']-$fDisp['MontoCompromiso'];
	
				//ntoCompromiso


			//,
			//$codigo_partida = $fieldet['cod_partida'];
			/*$gCapturada = $fpar2['generica'];
			$pCapturada2 = $fpar2['partida1'];*/
			
			$cont_3_1=3;
			$money3_1[0]=$fieldet['cod_partida'];//CODIGO DE LA PARTIDA
			$arraySetWidths3_1[0]='20';
			$arraySetAligns3_1[0]='C';
			$money3_1[1]=$field['denominacion'];//DESCRIPCIÓN DE LA PARTIDA
			$arraySetWidths3_1[1]='50';
			$arraySetAligns3_1[1]='L';
			$money3_1[2]=number_format($disponible03,2,',','.');//SALDO INICIAL
			$arraySetWidths3_1[2]='14';
			$arraySetAligns3_1[2]='R';
			$disponible03=$disponible03-$montoCompromisoPartida3; 
			for($r=$mesReferencia+1; $r<=12; $r++){	
							 												
				$money3_1[$cont_3_1] = $montoCompromisoPartida03;//Egreso
				$money3_1[$cont_3_1+1] = number_format($disponible03,2,',','.');//Disponible
				
				
				//$colorText[$cont_3_1]
				$arraySetWidths3_1[$cont_3_1]='14';
				$arraySetWidths3_1[$cont_3_1+1]='14';
				$arraySetAligns3_1[$cont_3_1]='R';
				$arraySetAligns3_1[$cont_3_1+1]='R';
				$cont_3_1=$cont_3_1+2;		
				 $disponible03=$disponible03-$montoCompromisoPartida3; 				
			}
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetFont('Arial', 'B', 5);
			$pdf->SetWidths($arraySetWidths3_1);
			$pdf->SetAligns($arraySetAligns3_1);
			$pdf->Row($money3_1);

   }
}else{



/// ********************************************************************************************** ///
///                                 CALCULANDO MONTO AJUSTADO
/// ********************************************************************************************** ///	

$sajuste = "SELECT 
				a.MontoAjuste,
				b.TipoAjuste
			FROM 
				pv_ajustepresupuestariodet a 
				inner join pv_ajustepresupuestario b on (b.Organismo = a.Organismo) and 
													 (b.CodAjuste = a.CodAjuste) and 
													 (b.FechaAprobacion>='".$anio.'-'.date("m",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."-01' and 
													  b.FechaAprobacion<='".$anio.'-'.date("m-d",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."' and 
													  b.Estado='AP')
			WHERE 
				a.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
				a.Organismo = '".$fieldet['Organismo']."' and 
				a.cod_partida = '".$fieldet['cod_partida']."'";
$qajuste = mysql_query($sajuste) or die ($sajuste.mysql_error());
$rajuste = mysql_num_rows($qajuste);
if($rajuste!=0){
  for($y=0; $y<$rajuste; $y++){
     $fajuste = mysql_fetch_array($qajuste);
	 if($fajuste['TipoAjuste']=='DI') $montoAj3 = $montoAj3 + (-1*($fajuste['MontoAjuste']));
	 else $montoAj3 = $montoAj3 + $fajuste['MontoAjuste'];
  }
  $montoAj3 = $montoAj3 + $fieldet['MontoAprobado'];
}else $montoAj3 = $montoAj3 + $fieldet['MontoAprobado'];


//echo $montoCA3;
/// ********************************************************************************************** ///
///                                 CALCULANDO MONTO COMPROMISO
/// ********************************************************************************************** ///
	$scompro="(select
					   sum(a.Monto) as Monto_lg
				from 
					  lg_distribucioncompromisos a 
					  inner join ap_obligaciones b on (b.NroDocumento=a.NroDocumento)and
													  (b.CodProveedor=a.CodProveedor)and
													  (b.CodTipodocumento=a.CodTipodocumento)and
													  (b.CodOrganismo=a.CodOrganismo)and
													  (b.Periodo<='$periodoEgreso' and 											  
													   b.Estado='PA' and 
													   b.FlagPresupuesto='S')
				where 
					  a.CodOrganismo='".$fieldet['Organismo']."' and 
					  a.Periodo>='$periodoEgreso' and 
					  
					  a.Estado='CO' and 
					  a.cod_partida = '".$fieldet['cod_partida']."')
				UNION
				(select
					   sum(a.Monto) as Monto_lg
				from 
					  lg_distribucioncompromisos a 
					  inner join lg_ordenservicio c on (c.NroOrden=a.NroDocumento)and
													   (c.CodOrganismo=a.CodOrganismo)and
													   (c.FechaAprobacion>='".$anio.'-'.date("m",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."-01' and
													   c.FechaAprobacion<='".$anio.'-'.date("m-d",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."' and 											   
														c.Estado<>'AN')
				where 
					  a.CodOrganismo='".$fieldet['Organismo']."' and 
					  a.Periodo<='$periodoEgreso' and 			 
					  a.Estado='CO' and 
					  a.CodTipoDocumento='OS' and 
					  a.cod_partida = '".$fieldet['cod_partida']."')
				UNION
				(select
					   sum(a.Monto) as Monto_lg
				from 
					  lg_distribucioncompromisos a 
					  inner join lg_ordencompra d on (d.NroOrden=a.NroDocumento)and
													 (d.CodOrganismo=a.CodOrganismo)and
													 (d.FechaAprobacion>='".$anio.'-'.date("m",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."-01' and
													 d.FechaAprobacion <='".$anio.'-'.date("m-d",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."' and 											  
													  d.Estado<>'AN')
				where 
					  a.CodOrganismo='".$fieldet['Organismo']."' and 
					  a.Periodo<='$periodoEgreso' and 			 
					  a.Estado='CO' and 
					  a.CodTipoDocumento='OC' and 
					  a.cod_partida = '".$fieldet['cod_partida']."')
				UNION
				(select
					 sum(a.Monto) as Monto_lg
				from 
					  lg_distribucioncompromisos a 
					  inner join ap_bancotransaccion e on (e.CodigoReferenciaBanco=a.NroDocumento)and
														  (e.CodOrganismo=a.CodOrganismo)and
														   
														  (e.FechaTransaccion>='".$anio.'-'.date("m",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."-01' and
														  e.FechaTransaccion<='".$anio.'-'.date("m-d",(mktime(0,0,0,$mesReferencia+1,1,$anio)-1))."' and 												  
														   e.Estado<>'AN' and 
														   e.FlagPresupuesto='S')
				where 
					  a.CodOrganismo='".$fieldet['Organismo']."' and 
					  a.Periodo<='$periodoEgreso' and 					  					 
					  a.Estado='CO' and 
					  a.cod_partida = '".$fieldet['cod_partida']."')"; //echo $scompro;
$qcompro = mysql_query($scompro) or die ($scompro.mysql_error());
$rcompro = mysql_num_rows($qcompro); 
if($rcompro!=0){
  for($x=0; $x<$rcompro; $x++){
     $fcompro= mysql_fetch_array($qcompro); 
     $montoCompromisoPartida3 = $montoCompromisoPartida3 + $fcompro['Monto_lg'];
  }
}
$montoCompromisoPartida03 = number_format($montoCompromisoPartida3,2,',','.');



///DISPONIBILIDAD ACTUAL

	$sqlD= "SELECT *
	FROM pv_presupuestodet
	WHERE cod_partida = '".$fieldet['cod_partida']."'
	AND Organismo = '".$fieldet['Organismo']."'
	AND CodPresupuesto = '".$fieldet['CodPresupuesto']."'";
	$qDisp = mysql_query($sqlD) or die ($sqlD.mysql_error());
	$rDisp = mysql_num_rows($qDisp);
	$fDisp= mysql_fetch_array($qDisp);
	
	$disponible03=$fDisp['MontoAjustado']-$fDisp['MontoCompromiso'];
	
				//ntoCompromiso


			//,
			//$codigo_partida = $fieldet['cod_partida'];
			/*$gCapturada = $fpar2['generica'];
			$pCapturada2 = $fpar2['partida1'];*/
			
			$cont_3_1=3;
			$money3_1[0]=$fieldet['cod_partida'];//CODIGO DE LA PARTIDA
			$arraySetWidths3_1[0]='20';
			$arraySetAligns3_1[0]='C';
			$money3_1[1]=$field['denominacion'];//DESCRIPCIÓN DE LA PARTIDA
			$arraySetWidths3_1[1]='50';
			$arraySetAligns3_1[1]='L';
			$money3_1[2]=number_format($disponible03,2,',','.');//SALDO INICIAL
			$arraySetWidths3_1[2]='14';
			$arraySetAligns3_1[2]='R';
			$disponible03=$disponible03-$montoCompromisoPartida3; 
			for($r=$mesReferencia+1; $r<=12; $r++){	
			   												
				$money3_1[$cont_3_1] = $montoCompromisoPartida03;//Egreso
				$money3_1[$cont_3_1+1] = number_format($disponible03,2,',','.');//Disponible
				
				
				//$colorText[$cont_3_1]
				$arraySetWidths3_1[$cont_3_1]='14';
				$arraySetWidths3_1[$cont_3_1+1]='14';
				$arraySetAligns3_1[$cont_3_1]='R';
				$arraySetAligns3_1[$cont_3_1+1]='R';
				$cont_3_1=$cont_3_1+2;			
				 $disponible03=$disponible03-$montoCompromisoPartida3; 	
			}
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetFont('Arial', 'B', 5);
			$pdf->SetWidths($arraySetWidths3_1);
			$pdf->SetAligns($arraySetAligns3_1);
			$pdf->Row($money3_1);

/*
$montoPago03 = number_format($montoPago3,2,',','.');
//echo $montoAj3.'-';
 $montoAj03 = number_format($montoAj3,2,',','.');
  
 $montoDisponible3 = ($montoAj3 - $montoCompromisoPartida3);
 $montoDisponible03 = number_format($montoDisponible3,2,',','.');
 $montoAprobado03 = number_format($fieldet['MontoAprobado'],2,',','.');
 
 $montoAprobadoT = $montoAprobadoT + $fieldet['MontoAprobado'];
 $montoAjustadoT = $montoAjustadoT + $montoAj3;
 $montoCompromisoT = $montoCompromisoT + $montoCompromisoPartida3;
 $montoCausadoT = $montoCausadoT + $montoCausados3;
 $montoPagadoT = $montoPagadoT + $montoPago3;
 $montoDisponibleT = $montoDisponibleT + $montoDisponible3;
 */
 //$montoCreditoAdicionalT = $montoCreditoAdicionalT+$montoCA3;
 //$montoCreditoAdicionalT = number_format($montoCreditoAdicionalT,2,',','.');
 
 
 
 
 
 
 /*
 $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
 $pdf->SetFont('Arial', '', 6.5);
 $pdf->SetWidths(array(25, 93,27,27,27,27,27,27,27));
 $pdf->SetAligns(array('C','L','R','R','R','R','R','R','R'));
 $pdf->Row(array($fieldet['cod_partida'],$field['denominacion'],$montoAprobado03,$montoAj03,number_format($montoCA3,2,',','.'),$montoCompromisoPartida03,$montoCausados03,$montoPago03,$montoDisponible03));
 */
 }
}
 
 
 
 
}


//////








$pdf->Output();
?>  
