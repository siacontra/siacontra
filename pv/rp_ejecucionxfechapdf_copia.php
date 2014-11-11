<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');
require('fphp.php');
connect(); 
extract ($_POST);
extract ($_GET);
global $fechaDesde;
global $fechaHasta;
global $periodoDesde;
global $periodoHasta;
global $Periodo;
//global $Periodo;
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
	$this->SetXY(20, 15); $this->Cell(200, 5, utf8_decode('Dirección de Administración y Servicios'), 0, 0, 'L');
	                       $this->Cell(10,5,utf8_decode('Página:'),0,1,'');
	$this->SetXY(19, 20); $this->Cell(204, 5, '', 0, 0, 'L');
	                       $this->Cell(7,5,utf8_decode('Año:'),0,0,'L');$this->Cell(6,5,date('Y'),0,1,'L');
						   
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
	$this->SetFont('Arial', 'B', 10);
	$this->Cell(105, 10, '', 0, 0, 'C');
	$this->Cell(47, 10, utf8_decode('Ejecución Presupuestaria'), 0, 0, 'C');$this->Cell(10, 10, $Periodo, 0, 1, 'C');
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
	
	
	$this->SetFont('Arial', '', 7);
	$this->Cell(27, 3, 'SECTOR:', 0, 0, 'L');$this->Cell(3, 3, $fieldSector[1], 0, 0, 'L');$this->Cell(30, 3, $fieldSector['descripcion'], 0, 1, 'L');
	$this->Cell(27, 3, 'PROGRAMA:', 0, 0, 'L');$this->Cell(3, 3, $fieldPrograma[1], 0, 0, 'L');$this->Cell(30, 3, $fieldPrograma[0], 0, 1, 'L');
	$this->Cell(27, 3, 'SUBPROGRAMA:', 0, 0, 'L');$this->Cell(3, 3, $fieldSubprog[1], 0, 0, 'L');$this->Cell(30, 3, $fieldSubprog[0], 0, 1, 'L');
	$this->Cell(27, 3, 'PROYECTO:', 0, 0, 'L');$this->Cell(3, 3, $fieldProyecto[1], 0, 0, 'L');$this->Cell(30, 3, $fieldProyecto[0], 0, 1, 'L');
	$this->Cell(27, 3, 'ACTIVIDAD:', 0, 0, 'L');$this->Cell(3, 3, $fieldActividad[1], 0, 0, 'L');$this->Cell(30, 3, $fieldActividad[0], 0, 1, 'L');
	$this->Cell(27, 3, 'UNIDAD EJECUTORA:', 0, 0, 'L');$this->Cell(30, 3, $fieldOrg[0], 0, 1, 'L');
	$this->SetDrawColor(0, 0, 0); $this->SetFillColor(200, 200, 200); $this->SetTextColor(0, 0, 0);
	$this->SetFont('Arial', 'B', 7);
	$this->Cell(25, 3, 'PAR GE ESP SE', 1, 0, 'C', 1);
	$this->Cell(120, 3, 'DENOMINACION', 1, 0, 'C', 1);
	$this->Cell(27, 3, 'T. PRESUPUESTADO', 1, 0, 'C', 1);
	$this->Cell(27, 3, 'T. AJUSTADO', 1, 0, 'C', 1);
	$this->Cell(27, 3, 'T. COMPROMETIDO', 1, 0, 'C', 1);
	$this->Cell(27, 3, 'T. CAUSADO', 1, 0, 'C', 1);
	$this->Cell(27, 3, 'T. PAGADO', 1, 0, 'C', 1);
	$this->Cell(27, 3, 'T. DISPONIBLE', 1, 1, 'C', 1);
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

//echo $periodoDesde.'-';
//list($ano, $mes) = split('[-]', $periodoDesde);

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
                partida,
				generica,
				especifica,
			    subespecifica,
				tipocuenta,
				CodPresupuesto,
				Organismo,
				FlagsReformulacion 
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
 
 if(($fieldet['partida']!=00) and (($cont1==0) or ($pCapturada!=$fieldet['partida']))){
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
   $sqldet="SELECT MontoAprobado, cod_partida, Organismo 
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
													 (b.FechaAjuste>='$fechaDesde' and 
													  b.FechaAjuste<='$fechaHasta' and 
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
$scompro="(select
			   a.CodTipoDocumento,
			   a.NroDocumento,
			   b.NroDocumento,
			   a.cod_partida as cod_partida_lg,
			   a.Periodo,
			   a.Monto as Monto_lg,
			   b.MontoObligacion as Monto,
			   a.Origen,
			   b.FechaPago as FechaAprobado,
			   '1' as Tabla
		from 
			  lg_distribucioncompromisos a 
			  inner join ap_obligaciones b on (b.NroDocumento=a.NroDocumento)and
                                      		  (b.CodProveedor=a.CodProveedor)and
                                              (b.CodTipodocumento=a.CodTipodocumento)and
                                              (b.CodOrganismo=a.CodOrganismo)and
											  (b.FechaPago>='$fechaDesde' and b.FechaPago<='$fechaHasta' and 
											   b.Estado='PA' and b.FlagPresupuesto='S')
		where 
			  a.CodOrganismo='".$fdet['Organismo']."' and 
			  a.Periodo>='$periodoDesde' and 
			  a.Periodo<='$periodoHasta' and 
			  a.Estado='CO' and 
			  a.cod_partida = '".$fdet['cod_partida']."')
        UNION
		(select
			   a.CodTipoDocumento,
			   a.NroDocumento,
			   c.NroOrden as NroDocumento,
			   a.cod_partida as cod_partida_lg,
			   a.Periodo,
			   a.Monto as Monto_lg,
			   c.MontoIva as Monto,
			   a.Origen,
			   c.FechaAprobacion as FechaAprobado,
			   '2' as Tabla
		from 
			  lg_distribucioncompromisos a 
			  inner join lg_ordenservicio c on (c.NroOrden=a.NroDocumento)and
											   (c.CodOrganismo=a.CodOrganismo)and
											   (c.FechaAprobacion>='$fechaDesde' and c.FechaAprobacion<='$fechaHasta' and c.Estado<>'AN')
		where 
			  a.CodOrganismo='".$fdet['Organismo']."' and 
			  a.Periodo>='$periodoDesde' and 
			  a.Periodo<='$periodoHasta' and 
			  a.Estado='CO' and 
			  a.CodTipoDocumento='OS' and 
			  a.cod_partida = '".$fdet['cod_partida']."')
		UNION
		(select
			   a.CodTipoDocumento,
			   a.NroDocumento,
			   d.NroOrden as NroDocumento,
			   a.cod_partida as cod_partida_lg,
			   a.Periodo,
			   a.Monto as Monto_lg,
			   d.MontoTotal as Monto,
			   a.Origen,
			   d.FechaAprobacion as FechaAprobado,
			   '3' as Tabla
		from 
			  lg_distribucioncompromisos a 
			  inner join lg_ordencompra d on (d.NroOrden=a.NroDocumento)and
											 (d.CodOrganismo=a.CodOrganismo)and
											 (d.FechaAprobacion>='$fechaDesde' and d.FechaAprobacion<='$fechaHasta' and d.Estado<>'AN')
		where 
			  a.CodOrganismo='".$fdet['Organismo']."' and 
			  a.Periodo>='$periodoDesde' and 
			  a.Periodo<='$periodoHasta' and 
			  a.Estado='CO' and 
			  a.CodTipoDocumento='OC' and 
			  a.cod_partida = '".$fdet['cod_partida']."')
		UNION
		(select
			   a.CodTipoDocumento,
			   a.NroDocumento,
			   e.CodigoReferenciaBanco as NroDocumento,
			   a.cod_partida as cod_partida_lg,
			   a.Periodo,
			   a.Monto as Monto_lg,
			   e.Monto,
			   a.Origen,
			   e.FechaTransaccion as FechaAprobado,
			   '4' as Tabla
		from 
			  lg_distribucioncompromisos a 
			  inner join ap_bancotransaccion e on (e.CodigoReferenciaBanco=a.NroDocumento)and
												  (e.CodOrganismo=a.CodOrganismo)and
												  (e.FechaTransaccion>='$fechaDesde' and e.FechaTransaccion<='$fechaHasta' and 
												   e.Estado<>'AN' and e.FlagPresupuesto='S')
		where 
			  a.CodOrganismo='".$fdet['Organismo']."' and 
			  a.Periodo>='$periodoDesde' and 
			  a.Periodo<='$periodoHasta' and 
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
$montoCompromisoPartida01=number_format($montoCompromisoPartida,2,',','.');
/// ********************************************************************************************** ///
///                                 CALCULANDO MONTO CAUSADOS
/// ********************************************************************************************** ///	
$scausados = "(select
				   a.CodTipoDocumento,
				   a.NroDocumento,
				   b.NroDocumento,
				   a.cod_partida as cod_partida_lg,
				   a.Periodo,
				   a.Monto as Monto_lg,
				   b.MontoObligacion as Monto,
				   a.Origen,
				   b.FechaPago as FechaAprobado,
				   '1' as Tabla
			from 
				  ap_distribucionobligacion a 
				  inner join ap_obligaciones b on (b.NroDocumento=a.NroDocumento)and
				  								  (b.CodTipoDocumento=a.CodTipoDocumento)and
												  (b.CodProveedor=a.CodProveedor)and
												  (b.CodOrganismo=a.CodOrganismo)and
												  (b.FechaPago>='$fechaDesde' and b.FechaPago<='$fechaHasta' and 
												   b.Estado='PA' and b.FlagPresupuesto='S')
			where 
				  a.CodOrganismo='".$fdet['Organismo']."' and 
				  a.Periodo>='$periodoDesde' and 
				  a.Periodo<='$periodoHasta' and 
				  a.Estado='CA'and 
				  a.cod_partida='".$fdet['cod_partida']."')
			UNION
			(select
				   a.CodTipoDocumento,
				   a.NroDocumento,
				   e.CodigoReferenciaBanco as NroDocumento,
				   a.cod_partida as cod_partida_lg,
				   a.Periodo,
				   a.Monto as Monto_lg,
				   e.Monto,
				   a.Origen,
				   e.FechaTransaccion as FechaAprobado,
				   '2' as Tabla
			from 
				  ap_distribucionobligacion a 
				  inner join ap_bancotransaccion e on (e.CodigoReferenciaBanco=a.NroDocumento)and
													  (e.CodOrganismo=a.CodOrganismo)and
													  (e.FechaTransaccion>='$fechaDesde' and e.FechaTransaccion<='$fechaHasta' and 
													   e.Estado<>'AN' and e.FlagPresupuesto='S')
			where 
				  a.CodOrganismo='".$fdet['Organismo']."' and 
				  a.Periodo>='$periodoDesde' and 
				  a.Periodo<='$periodoHasta' and 
				  a.Estado='CA'and 
				  a.cod_partida='".$fdet['cod_partida']."')
			order by CodTipoDocumento";
$qcausados = mysql_query($scausados) or die ($scausados.mysql_error());
$rcausados = mysql_num_rows($qcausados);
if($rcausados!=0){
  for($z=0; $z<$rcausados;$z++){
    $fcausados = mysql_fetch_array($qcausados);
	$montoCausados = $montoCausados + $fcausados['Monto_lg'];
  }
}
$montoCausados01 = number_format($montoCausados,2,',','.');                    	
/// ********************************************************************************************** ///
///                                 CALCULANDO MONTO PAGADOS
/// ********************************************************************************************** ///	
$spago="(select
			   a.CodTipoDocumento,
			   a.NroDocumento,
			   b.NroDocumento,
			   a.cod_partida as cod_partida_lg,
			   a.Periodo,
			   a.Monto as Monto_lg,
			   b.MontoTotal as Monto,
			   a.Origen,
			   b.FechaOrdenPago as FechaAprobado,
			   '1' as Tabla
		from 
			  ap_ordenpagodistribucion a 
			  inner join ap_ordenpago b on (b.NroDocumento=a.NroDocumento)and
			  							   (b.CodTipoDocumento=a.CodTipoDocumento)and
										   (b.CodProveedor=a.CodProveedor)and
										   (b.CodOrganismo=a.CodOrganismo)and
										   (b.FechaOrdenPago>='$fechaDesde' and 
											b.FechaOrdenPago<='$fechaHasta' and 
											b.Estado='PA')
		where 
			  a.CodOrganismo='".$fdet['Organismo']."' and 
			  a.Periodo>='$periodoDesde' and 
			  a.Periodo<='$periodoHasta' and 
			  a.Estado='PA'and 
			  a.cod_partida='".$fdet['cod_partida']."')
		UNION
		(select
			   a.CodTipoDocumento,
			   a.NroDocumento,
			   e.CodigoReferenciaBanco as NroDocumento,
			   a.cod_partida as cod_partida_lg,
			   a.Periodo,
			   a.Monto as Monto_lg,
			   e.Monto,
			   a.Origen,
			   e.FechaTransaccion as FechaAprobado,
			   '2' as Tabla
		from 
			  ap_ordenpagodistribucion a 
			  inner join ap_bancotransaccion e on (e.CodigoReferenciaBanco=a.NroDocumento)and
												  (e.CodOrganismo=a.CodOrganismo)and
												  (e.FechaTransaccion>='$fechaDesde' and e.FechaTransaccion<='$fechaHasta' and 
												   e.Estado<>'AN' and e.FlagPresupuesto='S')
		where 
			  a.CodOrganismo='".$fdet['Organismo']."' and 
			  a.Periodo>='$periodoDesde' and 
			  a.Periodo<='$periodoHasta' and 
			  a.Estado='PA'and 
			  a.cod_partida='".$fdet['cod_partida']."')
		order by CodTipoDocumento";
$qpago = mysql_query($spago) or die ($spago.mysql_error());
$rpago = mysql_num_rows($qpago);
if($rpago!=0){
 for($t=0; $t<$rpago; $t++){
   $fpago=mysql_fetch_array($qpago);
   $montoPago = $montoPago + $fpago['Monto_lg'];
 }
}
$montoPago01 = number_format($montoPago,2,',','.');

}
   $codigo_partida = $fpar['cod_partida'];
	$pCapturada = $fpar['partida1'];
	$montoDisponible = $montoAj - $montoCompromisoPartida;
	$montoAprobado01 = number_format($montoAprob,2,',','.');
	$montoAj01 = number_format($montoAj,2,',','.');
	$montoDisponible01 = number_format($montoDisponible,2,',','.');
  ///**** mostrando los resultados para partida 
	$pdf->SetFillColor(202, 202, 202); 
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetWidths(array(25, 120,27,27,27,27,27,27));
	$pdf->SetAligns(array('C','L','R','R','R','R','R','R'));
	$pdf->Row(array($codigo_partida,$fpar['denominacion'],$montoAprobado01,$montoAj01,$montoCompromisoPartida01,$montoCausados01,$montoPago01,$montoDisponible01));
}
}

 //// ----------------------------------------------------------------------------------------------- */
 ////           **** **** **** Capturando Partida Tipo "T" 301-01-00-00
 //// ----------------------------------------------------------------------------------------------- */
 $montoAprob2 = 0; $montoAprobado02 = 0; $montoAj2 = 0; $montoAjuste02 = 0; $montoCompromisoPartida2 = 0; $montoCausados2 = 0; 
 $montoCausados02 = 0; $montoPago2 = 0; $montoPago02 = 0;   $montoCompromisoPartida02 = 0;
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
				b.TipoAjuste
			FROM 
				pv_ajustepresupuestariodet a 
				inner join pv_ajustepresupuestario b on (b.Organismo = a.Organismo) and 
													 (b.CodAjuste = a.CodAjuste) and 
													 (b.FechaAprobacion>='$fechaDesde' and 
													  b.FechaAprobacion<='$fechaHasta' and 
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
	 if($fajuste['TipoAjuste']=='DI') $montoAj2 = $montoAj2 + (-1*($fajuste['MontoAjuste']));
	 else $montoAj2 = $montoAj2 + $fajuste['MontoAjuste'];
  }
  $montoAj2 = $montoAj2 + $fdet2['MontoAprobado'];
}else $montoAj2 = $montoAj2 + $fdet2['MontoAprobado'];
/// ********************************************************************************************** ///
///                                 CALCULANDO MONTO COMPROMISO
/// ********************************************************************************************** ///
$scompro ="(select
				   a.CodTipoDocumento,
				   a.NroDocumento,
				   b.NroDocumento,
				   a.cod_partida as cod_partida_lg,
				   a.Periodo,
				   a.Monto as Monto_lg,
				   b.MontoObligacion as Monto,
				   a.Origen,
				   b.FechaPago as FechaAprobado,
				   '1' as Tabla
			from 
				  lg_distribucioncompromisos a 
				  inner join ap_obligaciones b on (b.NroDocumento=a.NroDocumento)and
												  (b.CodProveedor=a.CodProveedor)and
												  (b.CodTipoDocumento=a.CodTipoDocumento)and
												  (b.CodOrganismo=a.CodOrganismo)and
												  (b.FechaPago>='$fechaDesde' and 
												   b.FechaPago<='$fechaHasta' and 
												   b.Estado='PA' and 
												   b.FlagPresupuesto='S')
			where 
				  a.CodOrganismo='".$fdet2['Organismo']."' and 
				  a.Periodo>='$periodoDesde' and 
				  a.Periodo<='$periodoHasta' and 
				  a.Estado='CO'and 
				  a.cod_partida='".$fdet2['cod_partida']."')
			union
			(select
				   a.CodTipoDocumento,
				   a.NroDocumento,
				   c.NroOrden as NroDocumento,
				   a.cod_partida as cod_partida_lg,
				   a.Periodo,
				   a.Monto as Monto_lg,
				   c.MontoIva as Monto,
				   a.Origen,
				   c.FechaAprobacion as FechaAprobado,
				   '2' as Tabla
			from 
				  lg_distribucioncompromisos a 
				  inner join lg_ordenservicio c on (c.NroOrden=a.NroDocumento)and
												   (c.CodOrganismo=a.CodOrganismo)and
												   (c.FechaAprobacion>='$fechaDesde' and 
													c.FechaAprobacion<='$fechaHasta' and 
													c.Estado<>'AN')
			where 
				  a.CodOrganismo='".$fdet2['Organismo']."' and 
				  a.Periodo>='$periodoDesde' and 
				  a.Periodo<='$periodoHasta' and 
				  a.Estado='CO' and 
				  a.CodTipoDocumento='OS'and 
				  a.cod_partida='".$fdet2['cod_partida']."')
			union
			(select
				   a.CodTipoDocumento,
				   a.NroDocumento,
				   d.NroOrden as NroDocumento,
				   a.cod_partida as cod_partida_lg,
				   a.Periodo,
				   a.Monto as Monto_lg,
				   d.MontoTotal as Monto,
				   a.Origen,
				   d.FechaAprobacion as FechaAprobado,
				   '3' as Tabla
			from 
				  lg_distribucioncompromisos a 
				  inner join lg_ordencompra d on (d.NroOrden=a.NroDocumento)and
												 (d.CodOrganismo=a.CodOrganismo)and
												 (d.FechaAprobacion>='$fechaDesde' and 
												  d.FechaAprobacion<='$fechaHasta' and 
												  d.Estado<>'AN')
			where 
				  a.CodOrganismo='".$fdet2['Organismo']."' and 
				  a.Periodo>='$periodoDesde' and 
				  a.Periodo<='$periodoHasta' and 
				  a.Estado='CO' and a.CodTipoDocumento='OC'and 
				  a.cod_partida='".$fdet2['cod_partida']."')
			union
			(select
				   a.CodTipoDocumento,
				   a.NroDocumento,
				   e.CodigoReferenciaBanco as NroDocumento,
				   a.cod_partida as cod_partida_lg,
				   a.Periodo,
				   a.Monto as Monto_lg,
				   e.Monto,
				   a.Origen,
				   e.FechaTransaccion as FechaAprobado,
				   '4' as Tabla
			from 
				  lg_distribucioncompromisos a 
				  inner join ap_bancotransaccion e on (e.CodigoReferenciaBanco=a.NroDocumento)and
													  (e.CodOrganismo=a.CodOrganismo)and
													  (e.FechaTransaccion>='$fechaDesde' and 
													   e.FechaTransaccion<='$fechaHasta' and 
													   e.Estado<>'AN' and 
													   e.FlagPresupuesto='S')
			where 
				  a.CodOrganismo='".$fdet2['Organismo']."' and 
				  a.Periodo>='$periodoDesde' and 
				  a.Periodo<='$periodoHasta' and 
				  a.Estado='CO' and 
				  a.cod_partida='".$fdet2['cod_partida']."')"; //echo $scompro;
/*$scompro="(select
			   a.CodTipoDocumento,
			   a.NroDocumento,
			   b.NroDocumento,
			   a.cod_partida as cod_partida_lg,
			   a.Periodo,
			   a.Monto as Monto_lg,
			   b.MontoObligacion as Monto,
			   a.Origen,
			   b.FechaPago as FechaAprobado,
			   '1' as Tabla
		from 
			  lg_distribucioncompromisos a 
			  inner join ap_obligaciones b on (b.NroDocumento=a.NroDocumento)and 
											  (b.CodTipoDocumento=a.CodTipoDocumento)and 
											  (b.CodProveedor=a.CodProveedor)and 
											  (b.CodOrganismo=a.CodOrganismo)and
											  (b.FechaPago>='$fechaDesde' and b.FechaPago<='$fechaHasta' and 
											   b.Estado='PA' and b.FlagPresupuesto='S')
		where 
			  a.CodOrganismo='".$fdet2['Organismo']."' and 
			  a.Periodo>='$periodoDesde' and 
			  a.Periodo<='$periodoHasta' and 
			  a.Estado='CO' and 
			  a.cod_partida = '".$fdet2['cod_partida']."')
        UNION
		(select
			   a.CodTipoDocumento,
			   a.NroDocumento,
			   c.NroOrden as NroDocumento,
			   a.cod_partida as cod_partida_lg,
			   a.Periodo,
			   a.Monto as Monto_lg,
			   c.MontoIva as Monto,
			   a.Origen,
			   c.FechaAprobacion as FechaAprobado,
			   '2' as Tabla
		from 
			  lg_distribucioncompromisos a 
			  inner join lg_ordenservicio c on (c.NroOrden=a.NroDocumento)and
											   (c.CodOrganismo=a.CodOrganismo)and
											   (c.FechaAprobacion>='$fechaDesde' and c.FechaAprobacion<='$fechaHasta' and c.Estado<>'AN')
		where 
			  a.CodOrganismo='".$fdet2['Organismo']."' and 
			  a.Periodo>='$periodoDesde' and 
			  a.Periodo<='$periodoHasta' and 
			  a.Estado='CO' and 
			  a.CodTipoDocumento='OS' and 
			  a.cod_partida = '".$fdet2['cod_partida']."')
		UNION
		(select
			   a.CodTipoDocumento,
			   a.NroDocumento,
			   d.NroOrden as NroDocumento,
			   a.cod_partida as cod_partida_lg,
			   a.Periodo,
			   a.Monto as Monto_lg,
			   d.MontoTotal as Monto,
			   a.Origen,
			   d.FechaAprobacion as FechaAprobado,
			   '3' as Tabla
		from 
			  lg_distribucioncompromisos a 
			  inner join lg_ordencompra d on (d.NroOrden=a.NroDocumento)and
											 (d.CodOrganismo=a.CodOrganismo)and
											 (d.FechaAprobacion>='$fechaDesde' and d.FechaAprobacion<='$fechaHasta' and d.Estado<>'AN')
		where 
			  a.CodOrganismo='".$fde2t['Organismo']."' and 
			  a.Periodo>='$periodoDesde' and 
			  a.Periodo<='$periodoHasta' and 
			  a.Estado='CO' and 
			  a.CodTipoDocumento='OC' and 
			  a.cod_partida = '".$fdet2['cod_partida']."')
		UNION
		(select
			   a.CodTipoDocumento,
			   a.NroDocumento,
			   e.CodigoReferenciaBanco as NroDocumento,
			   a.cod_partida as cod_partida_lg,
			   a.Periodo,
			   a.Monto as Monto_lg,
			   e.Monto,
			   a.Origen,
			   e.FechaTransaccion as FechaAprobado,
			   '4' as Tabla
		from 
			  lg_distribucioncompromisos a 
			  inner join ap_bancotransaccion e on (e.CodigoReferenciaBanco=a.NroDocumento)and
												  (e.CodOrganismo=a.CodOrganismo)and
												  (e.FechaTransaccion>='$fechaDesde' and e.FechaTransaccion<='$fechaHasta' and 
												   e.Estado<>'AN' and e.FlagPresupuesto='S')
		where 
			  a.CodOrganismo='".$fdet2['Organismo']."' and 
			  a.Periodo>='$periodoDesde' and 
			  a.Periodo<='$periodoHasta' and 
			  a.Estado='CO' and 
			  a.cod_partida = '".$fdet2['cod_partida']."')"; echo $scompro;*/
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
/// ********************************************************************************************** ///
///                                 CALCULANDO MONTO CAUSADOS
/// ********************************************************************************************** ///	
$scausados = "(select
				   a.CodTipoDocumento,
				   a.NroDocumento,
				   b.NroDocumento,
				   a.cod_partida as cod_partida_lg,
				   a.Periodo,
				   a.Monto as Monto_lg,
				   b.MontoObligacion as Monto,
				   a.Origen,
				   b.FechaPago as FechaAprobado,
				   '1' as Tabla
			from 
				  ap_distribucionobligacion a 
				  inner join ap_obligaciones b on (b.NroDocumento=a.NroDocumento)and 
				                                  (b.CodTipoDocumento=a.CodTipoDocumento)and 
												  (b.CodProveedor=a.CodProveedor) and 
												  (b.CodOrganismo=a.CodOrganismo)and
												  (b.FechaPago>='$fechaDesde' and b.FechaPago<='$fechaHasta' and 
												   b.Estado='PA' and b.FlagPresupuesto='S')
			where 
				  a.CodOrganismo='".$fdet2['Organismo']."' and 
				  a.Periodo>='$periodoDesde' and 
				  a.Periodo<='$periodoHasta' and 
				  a.Estado='CA'and 
				  a.cod_partida='".$fdet2['cod_partida']."')
			UNION
			(select
				   a.CodTipoDocumento,
				   a.NroDocumento,
				   e.CodigoReferenciaBanco as NroDocumento,
				   a.cod_partida as cod_partida_lg,
				   a.Periodo,
				   a.Monto as Monto_lg,
				   e.Monto,
				   a.Origen,
				   e.FechaTransaccion as FechaAprobado,
				   '2' as Tabla
			from 
				  ap_distribucionobligacion a 
				  inner join ap_bancotransaccion e on (e.CodigoReferenciaBanco=a.NroDocumento)and
													  (e.CodOrganismo=a.CodOrganismo)and
													  (e.FechaTransaccion>='$fechaDesde' and e.FechaTransaccion<='$fechaHasta' and 
													   e.Estado<>'AN' and e.FlagPresupuesto='S')
			where 
				  a.CodOrganismo='".$fdet2['Organismo']."' and 
				  a.Periodo>='$periodoDesde' and 
				  a.Periodo<='$periodoHasta' and 
				  a.Estado='CA'and 
				  a.cod_partida='".$fdet2['cod_partida']."')
			order by CodTipoDocumento";
$qcausados = mysql_query($scausados) or die ($scausados.mysql_error());
$rcausados = mysql_num_rows($qcausados);
if($rcausados!=0){
  for($z=0; $z<$rcausados;$z++){
    $fcausados = mysql_fetch_array($qcausados);
	$montoCausados2 = $montoCausados2 + $fcausados['Monto_lg'];
  }
}
$montoCausados02 = number_format($montoCausados2,2,',','.');                    	
/// ********************************************************************************************** ///
///                                 CALCULANDO MONTO PAGADOS
/// ********************************************************************************************** ///	
$spago="(select
			   a.CodTipoDocumento,
			   a.NroDocumento,
			   b.NroDocumento,
			   a.cod_partida as cod_partida_lg,
			   a.Periodo,
			   a.Monto as Monto_lg,
			   b.MontoTotal as Monto,
			   a.Origen,
			   b.FechaOrdenPago as FechaAprobado,
			   '1' as Tabla
		from 
			  ap_ordenpagodistribucion a 
			  inner join ap_ordenpago b on (b.NroDocumento=a.NroDocumento)and
			  							   (b.CodTipoDocumento=a.CodTipoDocumento)and
										   (b.CodProveedor=a.CodProveedor)and
										   (b.FechaOrdenPago>='$fechaDesde' and 
											b.FechaOrdenPago<='$fechaHasta' and 
											b.Estado='PA')
		where 
			  a.CodOrganismo='".$fdet2['Organismo']."' and 
			  a.Periodo>='$periodoDesde' and 
			  a.Periodo<='$periodoHasta' and 
			  a.Estado='PA'and 
			  a.cod_partida='".$fdet2['cod_partida']."')
		UNION
		(select
			   a.CodTipoDocumento,
			   a.NroDocumento,
			   e.CodigoReferenciaBanco as NroDocumento,
			   a.cod_partida as cod_partida_lg,
			   a.Periodo,
			   a.Monto as Monto_lg,
			   e.Monto,
			   a.Origen,
			   e.FechaTransaccion as FechaAprobado,
			   '2' as Tabla
		from 
			  ap_ordenpagodistribucion a 
			  inner join ap_bancotransaccion e on (e.CodigoReferenciaBanco=a.NroDocumento)and
												  (e.CodOrganismo=a.CodOrganismo)and
												  (e.FechaTransaccion>='$fechaDesde' and e.FechaTransaccion<='$fechaHasta' and 
												   e.Estado<>'AN' and e.FlagPresupuesto='S')
		where 
			  a.CodOrganismo='".$fdet2['Organismo']."' and 
			  a.Periodo>='$periodoDesde' and 
			  a.Periodo<='$periodoHasta' and 
			  a.Estado='PA'and 
			  a.cod_partida='".$fdet2['cod_partida']."')
		order by CodTipoDocumento";
$qpago = mysql_query($spago) or die ($spago.mysql_error());
$rpago = mysql_num_rows($qpago);
if($rpago!=0){
 for($t=0; $t<$rpago; $t++){
   $fpago=mysql_fetch_array($qpago);
   $montoPago2 = $montoPago2 + $fpago['Monto_lg'];
 }
}
$montoPago02 = number_format($montoPago2,2,',','.');
}

$montoDisponible2 = $montoAj2 - $montoCompromisoPartida2;
$montoDisponible02 = number_format($montoDisponible2,2,',','.');
$montoAjuste02 = number_format($montoAj2,2,',','.');
$montoAprobado02 = number_format($montoAprob2,2,',','.');
$codigo_partida = $fpar2['cod_partida'];
$gCapturada = $fpar2['generica'];
$pCapturada2 = $fpar2['partida1'];


///**** mostrando los resultados para partida 
$pdf->SetFillColor(202, 202, 202);
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetWidths(array(25, 120,27,27,27,27,27,27));
$pdf->SetAligns(array('C','L','R','R','R','R','R','R'));
$pdf->Row(array($codigo_partida,$fpar2['denominacion'],$montoAprobado02,$montoAjuste02,$montoCompromisoPartida02,$montoCausados02,$montoPago02,$montoDisponible02));
}
}
  
  
 //// ----------------------------------------------------------------------------------------------- */
 //// **** **** **** Capturando Partida Tipo "D" 301-01-01-00 ó 301-01-01-01
 //// ----------------------------------------------------------------------------------------------- */
$montoCompromisoPartida03 = 0; $montoCompromisoPartida3 = 0; $montoAj03 = 0; $montoAj3 = 0;
$montoCausados03 = 0; $montoCausados3 = 0; $montoPago03 = 0; $montoPago3 = 0; $montoAprobado03 = 0;
 
if($fieldet['partida']!=00){
	 
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
    						a.FechaAprobacion >= '2012-01-01' and 
    						a.FechaAprobacion <= '2012-06-12'";	
   $qreformulacion = mysql_query($sreformulacion) or die ($sreformulacion.mysql_error());
   $rreformulacion = mysql_num_rows($qreformulacion); 
   if($rreformulacion!=0){
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
													 (b.FechaAprobacion>='$fechaDesde' and 
													  b.FechaAprobacion<='$fechaHasta' and 
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
/// ********************************************************************************************** ///
///                                 CALCULANDO MONTO COMPROMISO
/// ********************************************************************************************** ///
$scompro="(select
			   a.CodTipoDocumento,
			   a.NroDocumento,
			   b.NroDocumento,
			   a.cod_partida as cod_partida_lg,
			   a.Periodo,
			   a.Monto as Monto_lg,
			   b.MontoObligacion as Monto,
			   a.Origen,
			   b.FechaPago as FechaAprobado,
			   '1' as Tabla
		from 
			  lg_distribucioncompromisos a 
			  inner join ap_obligaciones b on (b.NroDocumento=a.NroDocumento)and 
											  (b.CodTipoDocumento=a.CodTipoDocumento)and 
											  (b.CodProveedor=a.CodProveedor)and 
											  (b.CodOrganismo=a.CodOrganismo)and
											  (b.FechaPago>='$fechaDesde' and b.FechaPago<='$fechaHasta' and 
											   b.Estado='PA' and b.FlagPresupuesto='S')
		where 
			  a.CodOrganismo='".$fieldet['Organismo']."' and 
			  a.Periodo>='$periodoDesde' and 
			  a.Periodo<='$periodoHasta' and 
			  a.Estado='CO' and 
			  a.cod_partida = '".$fieldet['cod_partida']."')
        UNION
		(select
			   a.CodTipoDocumento,
			   a.NroDocumento,
			   c.NroOrden as NroDocumento,
			   a.cod_partida as cod_partida_lg,
			   a.Periodo,
			   a.Monto as Monto_lg,
			   c.MontoIva as Monto,
			   a.Origen,
			   c.FechaAprobacion as FechaAprobado,
			   '2' as Tabla
		from 
			  lg_distribucioncompromisos a 
			  inner join lg_ordenservicio c on (c.NroOrden=a.NroDocumento)and
											   (c.CodOrganismo=a.CodOrganismo)and
											   (c.FechaAprobacion>='$fechaDesde' and c.FechaAprobacion<='$fechaHasta' and c.Estado<>'AN')
		where 
			  a.CodOrganismo='".$fieldet['Organismo']."' and 
			  a.Periodo>='$periodoDesde' and 
			  a.Periodo<='$periodoHasta' and 
			  a.Estado='CO' and 
			  a.CodTipoDocumento='OS' and 
			  a.cod_partida = '".$fieldet['cod_partida']."')
		UNION
		(select
			   a.CodTipoDocumento,
			   a.NroDocumento,
			   d.NroOrden as NroDocumento,
			   a.cod_partida as cod_partida_lg,
			   a.Periodo,
			   a.Monto as Monto_lg,
			   d.MontoTotal as Monto,
			   a.Origen,
			   d.FechaAprobacion as FechaAprobado,
			   '3' as Tabla
		from 
			  lg_distribucioncompromisos a 
			  inner join lg_ordencompra d on (d.NroOrden=a.NroDocumento)and
											 (d.CodOrganismo=a.CodOrganismo)and
											 (d.FechaAprobacion>='$fechaDesde' and d.FechaAprobacion<='$fechaHasta' and d.Estado<>'AN')
		where 
			  a.CodOrganismo='".$fieldet['Organismo']."' and 
			  a.Periodo>='$periodoDesde' and 
			  a.Periodo<='$periodoHasta' and 
			  a.Estado='CO' and 
			  a.CodTipoDocumento='OC' and 
			  a.cod_partida = '".$fieldet['cod_partida']."')
		UNION
		(select
			   a.CodTipoDocumento,
			   a.NroDocumento,
			   e.CodigoReferenciaBanco as NroDocumento,
			   a.cod_partida as cod_partida_lg,
			   a.Periodo,
			   a.Monto as Monto_lg,
			   e.Monto,
			   a.Origen,
			   e.FechaTransaccion as FechaAprobado,
			   '4' as Tabla
		from 
			  lg_distribucioncompromisos a 
			  inner join ap_bancotransaccion e on (e.CodigoReferenciaBanco=a.NroDocumento)and
												  (e.CodOrganismo=a.CodOrganismo)and
												  (e.FechaTransaccion>='$fechaDesde' and e.FechaTransaccion<='$fechaHasta' and 
												   e.Estado<>'AN' and e.FlagPresupuesto='S')
		where 
			  a.CodOrganismo='".$fieldet['Organismo']."' and 
			  a.Periodo>='$periodoDesde' and 
			  a.Periodo<='$periodoHasta' and 
			  a.Estado='CO' and 
			  a.cod_partida = '".$fieldet['cod_partida']."')"; 
$qcompro = mysql_query($scompro) or die ($scompro.mysql_error());
$rcompro = mysql_num_rows($qcompro); 
if($rcompro!=0){
  for($x=0; $x<$rcompro; $x++){
     $fcompro= mysql_fetch_array($qcompro); 
     $montoCompromisoPartida3 = $montoCompromisoPartida3 + $fcompro['Monto_lg'];
  }
}
$montoCompromisoPartida03 = number_format($montoCompromisoPartida3,2,',','.');
/// ********************************************************************************************** ///
///                                 CALCULANDO MONTO CAUSADOS
/// ********************************************************************************************** ///	
$scausados = "(select
				   a.CodTipoDocumento,
				   a.NroDocumento,
				   b.NroDocumento,
				   a.cod_partida as cod_partida_lg,
				   a.Periodo,
				   a.Monto as Monto_lg,
				   b.MontoObligacion as Monto,
				   a.Origen,
				   b.FechaPago as FechaAprobado,
				   '1' as Tabla
			from 
				  ap_distribucionobligacion a 
				  inner join ap_obligaciones b on (b.NroDocumento=a.NroDocumento)and
				                                  (b.CodTipoDocumento=a.CodTipoDocumento)and
												  (b.CodProveedor=a.CodProveedor)and 
												  (b.CodOrganismo=a.CodOrganismo)and
												  (b.FechaPago>='$fechaDesde' and b.FechaPago<='$fechaHasta' and 
												   b.Estado='PA' and b.FlagPresupuesto='S')
			where 
				  a.CodOrganismo='".$fieldet['Organismo']."' and 
				  a.Periodo>='$periodoDesde' and 
				  a.Periodo<='$periodoHasta' and 
				  a.Estado='CA'and 
				  a.cod_partida='".$fieldet['cod_partida']."')
			UNION
			(select
				   a.CodTipoDocumento,
				   a.NroDocumento,
				   e.CodigoReferenciaBanco as NroDocumento,
				   a.cod_partida as cod_partida_lg,
				   a.Periodo,
				   a.Monto as Monto_lg,
				   e.Monto,
				   a.Origen,
				   e.FechaTransaccion as FechaAprobado,
				   '2' as Tabla
			from 
				  ap_distribucionobligacion a 
				  inner join ap_bancotransaccion e on (e.CodigoReferenciaBanco=a.NroDocumento)and
													  (e.CodOrganismo=a.CodOrganismo)and
													  (e.FechaTransaccion>='$fechaDesde' and e.FechaTransaccion<='$fechaHasta' and 
													   e.Estado<>'AN' and e.FlagPresupuesto='S')
			where 
				  a.CodOrganismo='".$fieldet['Organismo']."' and 
				  a.Periodo>='$periodoDesde' and 
				  a.Periodo<='$periodoHasta' and 
				  a.Estado='CA'and 
				  a.cod_partida='".$fieldet['cod_partida']."')
			order by CodTipoDocumento";
$qcausados = mysql_query($scausados) or die ($scausados.mysql_error());
$rcausados = mysql_num_rows($qcausados);
if($rcausados!=0){
  for($z=0; $z<$rcausados;$z++){
    $fcausados = mysql_fetch_array($qcausados);
	$montoCausados3 = $montoCausados3 + $fcausados['Monto_lg'];
  }
}
$montoCausados03 = number_format($montoCausados3,2,',','.');                    	
/// ********************************************************************************************** ///
///                                 CALCULANDO MONTO PAGADOS
/// ********************************************************************************************** ///	
$spago="(select
			   a.CodTipoDocumento,
			   a.NroDocumento,
			   b.NroDocumento,
			   a.cod_partida as cod_partida_lg,
			   a.Periodo,
			   a.Monto as Monto_lg,
			   b.MontoTotal as Monto,
			   a.Origen,
			   b.FechaOrdenPago as FechaAprobado,
			   '1' as Tabla
		from 
			  ap_ordenpagodistribucion a 
			  inner join ap_ordenpago b on (b.NroDocumento=a.NroDocumento)and
			  							   (b.CodTipoDocumento=a.CodTipoDocumento)and
										   (b.CodProveedor=a.CodProveedor)and
										   (b.CodOrganismo=a.CodOrganismo)and
										   (b.FechaOrdenPago>='$fechaDesde' and 
											b.FechaOrdenPago<='$fechaHasta' and 
											b.Estado='PA')
		where 
			  a.CodOrganismo='".$fieldet['Organismo']."' and 
			  a.Periodo>='$periodoDesde' and 
			  a.Periodo<='$periodoHasta' and 
			  a.Estado='PA'and 
			  a.cod_partida='".$fieldet['cod_partida']."')
		UNION
		(select
			   a.CodTipoDocumento,
			   a.NroDocumento,
			   e.CodigoReferenciaBanco as NroDocumento,
			   a.cod_partida as cod_partida_lg,
			   a.Periodo,
			   a.Monto as Monto_lg,
			   e.Monto,
			   a.Origen,
			   e.FechaTransaccion as FechaAprobado,
			   '2' as Tabla
		from 
			  ap_ordenpagodistribucion a 
			  inner join ap_bancotransaccion e on (e.CodigoReferenciaBanco=a.NroDocumento)and
												  (e.CodOrganismo=a.CodOrganismo)and
												  (e.FechaTransaccion>='$fechaDesde' and e.FechaTransaccion<='$fechaHasta' and 
												   e.Estado<>'AN' and e.FlagPresupuesto='S')
		where 
			  a.CodOrganismo='".$fieldet['Organismo']."' and 
			  a.Periodo>='$periodoDesde' and 
			  a.Periodo<='$periodoHasta' and 
			  a.Estado='PA'and 
			  a.cod_partida='".$fieldet['cod_partida']."')
		order by CodTipoDocumento";
$qpago = mysql_query($spago) or die ($spago.mysql_error());
$rpago = mysql_num_rows($qpago);
if($rpago!=0){
 for($t=0; $t<$rpago; $t++){
   $fpago=mysql_fetch_array($qpago);
   $montoPago3 = $montoPago3 + $fpago['Monto_lg'];
 }
}
$montoPago03 = number_format($montoPago3,2,',','.');

 $montoAj03 = number_format($montoAj3,2,',','.');

$montoDisponible3 = $montoAj3 - $montoCompromisoPartida3;
 $montoDisponible03 = number_format($montoDisponible3,2,',','.');
 $montoAprobado03 = number_format($fieldet['MontoAprobado'],2,',','.');
 
 $montoAprobadoT = $montoAprobadoT + $fieldet['MontoAprobado'];
 $montoAjustadoT = $montoAjustadoT + $montoAj3;
 $montoCompromisoT = $montoCompromisoT + $montoCompromisoPartida3;
 $montoCausadoT = $montoCausadoT + $montoCausados3;
 $montoPagadoT = $montoPagadoT + $montoPago3;
 $montoDisponibleT = $montoDisponibleT + $montoDisponible3;

 $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
 $pdf->SetFont('Arial', '', 6.5);
 $pdf->SetWidths(array(25, 120,27,27,27,27,27,27));
 $pdf->SetAligns(array('C','L','R','R','R','R','R','R'));
 $pdf->Row(array($fieldet['cod_partida'],$field['denominacion'],$montoAprobado03,$montoAj03,$montoCompromisoPartida03,$montoCausados03,$montoPago03,$montoDisponible03));
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
													 (b.FechaAprobacion>='$fechaDesde' and 
													  b.FechaAprobacion<='$fechaHasta' and 
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
/// ********************************************************************************************** ///
///                                 CALCULANDO MONTO COMPROMISO
/// ********************************************************************************************** ///
$scompro="(select
			   a.CodTipoDocumento,
			   a.NroDocumento,
			   b.NroDocumento,
			   a.cod_partida as cod_partida_lg,
			   a.Periodo,
			   a.Monto as Monto_lg,
			   b.MontoObligacion as Monto,
			   a.Origen,
			   b.FechaPago as FechaAprobado,
			   '1' as Tabla
		from 
			  lg_distribucioncompromisos a 
			  inner join ap_obligaciones b on (b.NroDocumento=a.NroDocumento)and 
											  (b.CodTipoDocumento=a.CodTipoDocumento)and 
											  (b.CodProveedor=a.CodProveedor)and 
											  (b.CodOrganismo=a.CodOrganismo)and
											  (b.FechaPago>='$fechaDesde' and b.FechaPago<='$fechaHasta' and 
											   b.Estado='PA' and b.FlagPresupuesto='S')
		where 
			  a.CodOrganismo='".$fieldet['Organismo']."' and 
			  a.Periodo>='$periodoDesde' and 
			  a.Periodo<='$periodoHasta' and 
			  a.Estado='CO' and 
			  a.cod_partida = '".$fieldet['cod_partida']."')
        UNION
		(select
			   a.CodTipoDocumento,
			   a.NroDocumento,
			   c.NroOrden as NroDocumento,
			   a.cod_partida as cod_partida_lg,
			   a.Periodo,
			   a.Monto as Monto_lg,
			   c.MontoIva as Monto,
			   a.Origen,
			   c.FechaAprobacion as FechaAprobado,
			   '2' as Tabla
		from 
			  lg_distribucioncompromisos a 
			  inner join lg_ordenservicio c on (c.NroOrden=a.NroDocumento)and
											   (c.CodOrganismo=a.CodOrganismo)and
											   (c.FechaAprobacion>='$fechaDesde' and c.FechaAprobacion<='$fechaHasta' and c.Estado<>'AN')
		where 
			  a.CodOrganismo='".$fieldet['Organismo']."' and 
			  a.Periodo>='$periodoDesde' and 
			  a.Periodo<='$periodoHasta' and 
			  a.Estado='CO' and 
			  a.CodTipoDocumento='OS' and 
			  a.cod_partida = '".$fieldet['cod_partida']."')
		UNION
		(select
			   a.CodTipoDocumento,
			   a.NroDocumento,
			   d.NroOrden as NroDocumento,
			   a.cod_partida as cod_partida_lg,
			   a.Periodo,
			   a.Monto as Monto_lg,
			   d.MontoTotal as Monto,
			   a.Origen,
			   d.FechaAprobacion as FechaAprobado,
			   '3' as Tabla
		from 
			  lg_distribucioncompromisos a 
			  inner join lg_ordencompra d on (d.NroOrden=a.NroDocumento)and
											 (d.CodOrganismo=a.CodOrganismo)and
											 (d.FechaAprobacion>='$fechaDesde' and d.FechaAprobacion<='$fechaHasta' and d.Estado<>'AN')
		where 
			  a.CodOrganismo='".$fieldet['Organismo']."' and 
			  a.Periodo>='$periodoDesde' and 
			  a.Periodo<='$periodoHasta' and 
			  a.Estado='CO' and 
			  a.CodTipoDocumento='OC' and 
			  a.cod_partida = '".$fieldet['cod_partida']."')
		UNION
		(select
			   a.CodTipoDocumento,
			   a.NroDocumento,
			   e.CodigoReferenciaBanco as NroDocumento,
			   a.cod_partida as cod_partida_lg,
			   a.Periodo,
			   a.Monto as Monto_lg,
			   e.Monto,
			   a.Origen,
			   e.FechaTransaccion as FechaAprobado,
			   '4' as Tabla
		from 
			  lg_distribucioncompromisos a 
			  inner join ap_bancotransaccion e on (e.CodigoReferenciaBanco=a.NroDocumento)and
												  (e.CodOrganismo=a.CodOrganismo)and
												  (e.FechaTransaccion>='$fechaDesde' and e.FechaTransaccion<='$fechaHasta' and 
												   e.Estado<>'AN' and e.FlagPresupuesto='S')
		where 
			  a.CodOrganismo='".$fieldet['Organismo']."' and 
			  a.Periodo>='$periodoDesde' and 
			  a.Periodo<='$periodoHasta' and 
			  a.Estado='CO' and 
			  a.cod_partida = '".$fieldet['cod_partida']."')";
$qcompro = mysql_query($scompro) or die ($scompro.mysql_error());
$rcompro = mysql_num_rows($qcompro); 
if($rcompro!=0){
  for($x=0; $x<$rcompro; $x++){
     $fcompro= mysql_fetch_array($qcompro); 
     $montoCompromisoPartida3 = $montoCompromisoPartida3 + $fcompro['Monto_lg'];
  }
}
$montoCompromisoPartida03 = number_format($montoCompromisoPartida3,2,',','.');
/// ********************************************************************************************** ///
///                                 CALCULANDO MONTO CAUSADOS
/// ********************************************************************************************** ///	
$scausados = "(select
				   a.CodTipoDocumento,
				   a.NroDocumento,
				   b.NroDocumento,
				   a.cod_partida as cod_partida_lg,
				   a.Periodo,
				   a.Monto as Monto_lg,
				   b.MontoObligacion as Monto,
				   a.Origen,
				   b.FechaPago as FechaAprobado,
				   '1' as Tabla
			from 
				  ap_distribucionobligacion a 
				  inner join ap_obligaciones b on (b.NroDocumento=a.NroDocumento)and
				                                  (b.CodTipoDocumento=a.CodTipoDocumento)and
												  (b.CodProveedor=a.CodProveedor)and 
												  (b.CodOrganismo=a.CodOrganismo)and
												  (b.FechaPago>='$fechaDesde' and b.FechaPago<='$fechaHasta' and 
												   b.Estado='PA' and b.FlagPresupuesto='S')
			where 
				  a.CodOrganismo='".$fieldet['Organismo']."' and 
				  a.Periodo>='$periodoDesde' and 
				  a.Periodo<='$periodoHasta' and 
				  a.Estado='CA'and 
				  a.cod_partida='".$fieldet['cod_partida']."')
			UNION
			(select
				   a.CodTipoDocumento,
				   a.NroDocumento,
				   e.CodigoReferenciaBanco as NroDocumento,
				   a.cod_partida as cod_partida_lg,
				   a.Periodo,
				   a.Monto as Monto_lg,
				   e.Monto,
				   a.Origen,
				   e.FechaTransaccion as FechaAprobado,
				   '2' as Tabla
			from 
				  ap_distribucionobligacion a 
				  inner join ap_bancotransaccion e on (e.CodigoReferenciaBanco=a.NroDocumento)and
													  (e.CodOrganismo=a.CodOrganismo)and
													  (e.FechaTransaccion>='$fechaDesde' and e.FechaTransaccion<='$fechaHasta' and 
													   e.Estado<>'AN' and e.FlagPresupuesto='S')
			where 
				  a.CodOrganismo='".$fieldet['Organismo']."' and 
				  a.Periodo>='$periodoDesde' and 
				  a.Periodo<='$periodoHasta' and 
				  a.Estado='CA'and 
				  a.cod_partida='".$fieldet['cod_partida']."')
			order by CodTipoDocumento";
$qcausados = mysql_query($scausados) or die ($scausados.mysql_error());
$rcausados = mysql_num_rows($qcausados);
if($rcausados!=0){
  for($z=0; $z<$rcausados;$z++){
    $fcausados = mysql_fetch_array($qcausados);
	$montoCausados3 = $montoCausados3 + $fcausados['Monto_lg'];
  }
}
$montoCausados03 = number_format($montoCausados3,2,',','.');                    	
/// ********************************************************************************************** ///
///                                 CALCULANDO MONTO PAGADOS
/// ********************************************************************************************** ///	
$spago="(select
			   a.CodTipoDocumento,
			   a.NroDocumento,
			   b.NroDocumento,
			   a.cod_partida as cod_partida_lg,
			   a.Periodo,
			   a.Monto as Monto_lg,
			   b.MontoTotal as Monto,
			   a.Origen,
			   b.FechaOrdenPago as FechaAprobado,
			   '1' as Tabla
		from 
			  ap_ordenpagodistribucion a 
			  inner join ap_ordenpago b on (b.NroDocumento=a.NroDocumento)and
			  							   (b.CodTipoDocumento=a.CodTipoDocumento)and
										   (b.CodProveedor=a.CodProveedor)and
										   (b.CodOrganismo=a.CodOrganismo)and
										   (b.FechaOrdenPago>='$fechaDesde' and 
											b.FechaOrdenPago<='$fechaHasta' and 
											b.Estado='PA')
		where 
			  a.CodOrganismo='".$fieldet['Organismo']."' and 
			  a.Periodo>='$periodoDesde' and 
			  a.Periodo<='$periodoHasta' and 
			  a.Estado='PA'and 
			  a.cod_partida='".$fieldet['cod_partida']."')
		UNION
		(select
			   a.CodTipoDocumento,
			   a.NroDocumento,
			   e.CodigoReferenciaBanco as NroDocumento,
			   a.cod_partida as cod_partida_lg,
			   a.Periodo,
			   a.Monto as Monto_lg,
			   e.Monto,
			   a.Origen,
			   e.FechaTransaccion as FechaAprobado,
			   '2' as Tabla
		from 
			  ap_ordenpagodistribucion a 
			  inner join ap_bancotransaccion e on (e.CodigoReferenciaBanco=a.NroDocumento)and
												  (e.CodOrganismo=a.CodOrganismo)and
												  (e.FechaTransaccion>='$fechaDesde' and e.FechaTransaccion<='$fechaHasta' and 
												   e.Estado<>'AN' and e.FlagPresupuesto='S')
		where 
			  a.CodOrganismo='".$fieldet['Organismo']."' and 
			  a.Periodo>='$periodoDesde' and 
			  a.Periodo<='$periodoHasta' and 
			  a.Estado='PA'and 
			  a.cod_partida='".$fieldet['cod_partida']."')
		order by CodTipoDocumento";
$qpago = mysql_query($spago) or die ($spago.mysql_error());
$rpago = mysql_num_rows($qpago);
if($rpago!=0){
 for($t=0; $t<$rpago; $t++){
   $fpago=mysql_fetch_array($qpago);
   $montoPago3 = $montoPago3 + $fpago['Monto_lg'];
 }
}
$montoPago03 = number_format($montoPago3,2,',','.');

 $montoAj03 = number_format($montoAj3,2,',','.');
  
 $montoDisponible3 = $montoAj3 - $montoCompromisoPartida3;
 $montoDisponible03 = number_format($montoDisponible3,2,',','.');
 $montoAprobado03 = number_format($fieldet['MontoAprobado'],2,',','.');
 
 $montoAprobadoT = $montoAprobadoT + $fieldet['MontoAprobado'];
 $montoAjustadoT = $montoAjustadoT + $montoAj3;
 $montoCompromisoT = $montoCompromisoT + $montoCompromisoPartida3;
 $montoCausadoT = $montoCausadoT + $montoCausados3;
 $montoPagadoT = $montoPagadoT + $montoPago3;
 $montoDisponibleT = $montoDisponibleT + $montoDisponible3;
 
 $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
 $pdf->SetFont('Arial', '', 6.5);
 $pdf->SetWidths(array(25, 120,27,27,27,27,27,27));
 $pdf->SetAligns(array('C','L','R','R','R','R','R','R'));
 $pdf->Row(array($fieldet['cod_partida'],$field['denominacion'],$montoAprobado03,$montoAj03,$montoCompromisoPartida03,$montoCausados03,$montoPago03,$montoDisponible03));
 }}}
	///// *** Mostrar *** /////
	//$montoT=number_format($montoT,2,',','.');
	$montoAprobadoTotal = number_format($montoAprobadoT,2,',','.'); 
    $montoAjustadoTotal = number_format($montoAjustadoT,2,',','.');      
    $montoCompromisoTotal = number_format($montoCompromisoT,2,',','.');       
	$montoCausadoTotal = number_format($montoCausadoT,2,',','.'); 
	$montoPagadoTotal = number_format($montoPagadoT,2,',','.'); 
	$montoDisponibleTotal = number_format($montoDisponibleT,2,',','.'); 
	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(202, 202, 202); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8.5);
	$pdf->SetWidths(array(25, 120,27,27,27,27,27,27));
    $pdf->SetAligns(array('C','L','R','R','R','R','R','R'));
	$pdf->Row(array('' ,'Total:',$montoAprobadoTotal,$montoAjustadoTotal,$montoCompromisoTotal,$montoCausadoTotal,$montoPagadoTotal,$montoDisponibleTotal));
///// 
//// --------------------------------------------------------------------------------- */
//---------------------------------------------------*/
$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(202, 202, 202); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(100,10,'',0,1,'L');
	$pdf->Cell(100,10,'ELABORADO POR:',0,0,'L');$pdf->Cell(120,10,'REVISADO POR:',0,0,'L');$pdf->Cell(100,10,'CONFORMADO POR:',0,1,'L');
	$pdf->Cell(100,5,'',0,0,'L');$pdf->Cell(120,5,'',0,0,'L');$pdf->Cell(100,5,'',0,1,'L');
	$pdf->Cell(100,5,'T.S.U. MARIANA SALAZAR',0,0,'L');$pdf->Cell(120,5,'LCDA. YOSMAR GREHAM',0,0,'L');$pdf->Cell(100,5,'LCDA. ROSIS REQUENA',0,1,'L');
	$pdf->Cell(100,2,'ASISTENTE DE PRESUPUESTO I',0,0,'L');$pdf->Cell(120,2,'DIRECTOR(A) DE LA DIRECCION DE ADMINISTRACION Y SERVICIOS ',0,0,'L');$pdf->Cell(100,2,'DIRECTORA GENERAL',0,1,'L');
$pdf->Output();
?>  
