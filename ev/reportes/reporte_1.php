<?php
	include("../../clases/MySQL.php");
	//include_once("../../comunes/objConexion.php");
	include("../include/funciones_php.php");


	$host = "192.168.0.133";
	$usuario = $_SESSION["MYSQL_USER"];
	$clave = $_SESSION["MYSQL_CLAVE"];
	$baseDatos = $_SESSION["MYSQL_BD"];
	$puerto = "3306";
		
	$objConexion = new MySQL($host,$usuario,$clave,$baseDatos,$puerto);
	
   

$anio=2013;
$mes=$_GET["mes"];
$tipo=$_GET["tipo"];


switch($tipo){
	case 1:

		switch($mes)
		{
			case 1: $fecha_inicio="$anio-01-01";$fecha_final="$anio-01-31";$mes="ENERO";break;
			case 2: $fecha_inicio="$anio-02-01";
					if(esBisiesto($anio))$fecha_final="$anio-02-29";
					else $fecha_final="$anio-02-28";
					$mes="FEBRERO";break;

			case 3: $fecha_inicio="$anio-03-01";$fecha_final="$anio-03-31";$mes="MARZO";break;
			case 4: $fecha_inicio="$anio-04-01";$fecha_final="$anio-04-30";$mes="ABRIL";break;
			case 5: $fecha_inicio="$anio-05-01";$fecha_final="$anio-05-31";$mes="MAYO";break;
			case 6: $fecha_inicio="$anio-06-01";$fecha_final="$anio-06-30";$mes="JUNIO";break;
			case 7: $fecha_inicio="$anio-07-01";$fecha_final="$anio-07-31";$mes="JULIO";break;
			case 8: $fecha_inicio="$anio-08-01";$fecha_final="$anio-08-31";$mes="AGOSTO";break;
			case 9: $fecha_inicio="$anio-09-01";$fecha_final="$anio-09-30";$mes="SEPTIEMBRE";break;
			case 10:$fecha_inicio="$anio-10-01";$fecha_final="$anio-10-31";$mes="OCTUBRE";break;
			case 11:$fecha_inicio="$anio-11-01";$fecha_final="$anio-11-30";$mes="NOVIEMBRE";break;
			case 12:$fecha_inicio="$anio-12-01";$fecha_final="$anio-12-31";$mes="DICIEMBRE";break;
			default: exit;
		}
		break;

	case 2:


		switch($mes){
			case 1: $fecha_inicio="$anio-01-01";$fecha_final="$anio-03-31";$num="I";$mes="ENERO - MARZO";break;
			case 2: $fecha_inicio="$anio-04-01";$fecha_final="$anio-06-30";$num="II";$mes="ABRIL - JUNIO";break;
			case 3: $fecha_inicio="$anio-07-01";$fecha_final="$anio-09-30";$num="III";$mes="JULIO - SEPTIEMBRE";break;
			case 4: $fecha_inicio="$anio-10-01";$fecha_final="$anio-12-31";$num="IV";$mes="OCTUBRE - DICIEMBRE";break;
			default: exit;
			}
		break;
		
		
	default:
		exit;
	}


	$sql="select A.co_id_evento,A.hh_fecha1,A.hh_fecha2,A.co_lugar,A.co_id,A.tx_nombre_evento,A.tx_descripcion_evento,A.hh_hora1,A.hh_hora2,A.bo_certificado,A.eliminado,B.CodPersona,B.bo_culmino_evento,B.bo_recibio_certificado,B.bo_ponente,B.tx_nu_certificado, C.CodPersona, C.NomCompleto,C.Ndocumento, D.nombre_lugar from ev_evento_capacitacion as A, ev_persona_evento as B, mastpersonas as C, ev_lugares_evento as D where A.co_id_evento=B.co_id_evento and B.CodPersona=C.CodPersona and D.co_lugares=A.co_lugar and A.eliminado=false and bo_ponente=1 AND A.hh_fecha1 between '$fecha_inicio' and '$fecha_final';";


	$EVENTOS=$objConexion->consultar($sql,'matriz');


	if($EVENTOS[0]["co_id_evento"]=="null" || $EVENTOS[0]["co_id_evento"]=="" || $EVENTOS[0]["co_id_evento"]==0 || $EVENTOS[0]["co_id_evento"]=="NULL")
	{ 

		if($tipo==1)
		echo "<DIV align='center'>No hay eventos registrados durante este mes</DIV>";
		if($tipo==2)
		echo "<DIV align='center'>No hay eventos registrados durante este trimestre</DIV>";
	return;
	}


 
require ('../include/fpdf/fpdf.php');


class PDF_P extends FPDF{
	function Header()
	{
	


		$this->Image("../img/contraloria.jpg", 20, 5, 26.125, 16.75);
		
		if(count($GLOBALS['LISTA_IMPRIMIR'])==1)
 			$this->Cell(80,5,'Página: '.$this->PageNo().' de {nb}','',1,'R');
 		else			
			$this->Cell(80,5,'','',1,'R');



		$this->Image("../img/membrete.jpg", 100, 5, 70.33, 12.16);
		
		if(count($GLOBALS['LISTA_IMPRIMIR'])==1)
 			$this->Cell(80,5,'Página: '.$this->PageNo().' de {nb}','',1,'R');
 		else			
			$this->Cell(80,5,'','',1,'R');


			
				
		$this->Image("../img/LOGOSNCF.jpg", 240, 5, 16.25, 17.75);
		if(count($GLOBALS['LISTA_IMPRIMIR'])==1)
 			$this->Cell(80,5,'Página: '.$this->PageNo().' de {nb}','',1,'R');			
 		else
			$this->Cell(80,5,'','',1,'R');
			
			

	}




    function MultiCelda($w,$h,$txt,$border,$align,$fill)
    {
        $x=$this->GetX();
        $y=$this->GetY();
        $this->MultiCell($w,$h,$txt,$border,$align,$fill);
        $this->SetXY($x+$w,$y);
    }
}

$pdf=new PDF_P("L","mm","letter");


$area_tabla=260;

$MARGEN_LEFT=8;
$MARGEN_TOP=14;

$TamColCodigo=14;
$TamColNombre=55;
$TamColLugar=90;
$TamColFecha=18;
$TamPonente=50;
$TamHoras=15;

$pdf->SetFillColor(255,255,255);

$pdf->SetLeftMargin($MARGEN_LEFT);
$pdf->SetTopMargin($MARGEN_TOP);

$pdf->AddPage();
$pdf->SetFont('helvetica','',5);
$pdf->SetFillColor(255,255,255);
$pdf->SetLineWidth(0.1);




//titulo
//$pdf->Cell($area_tabla,3,'',0,1,'',1);
$pdf->SetFont('helvetica','B',10);

if($tipo==1)
$pdf->MultiCell($area_tabla,5,"EVENTOS REALIZADOS DURANTE EL MES DE ".$mes." DE ".$anio,'','C',1);

if($tipo==2)
$pdf->MultiCell($area_tabla,5,"EVENTOS REALIZADOS DURANTE EL ".$num." TRIMESTRE (".$mes.") DEL AÑO ".$anio,'','C',1);

$pdf->Cell(30,1,'','',0,'C',1);
$pdf->Cell(110,1,'','',0,'C',1);
$pdf->Cell(40,1,'','',1,'C',1);

$pdf->SetFillColor(200,200,200);
$pdf->SetFont('helvetica','B',6.5);

$pdf->Cell($TamColCodigo,5,'CÓD.','LRT',0,'C',1);
$pdf->Cell($TamColNombre,5,'NOMBRE','LRT',0,'C',1);
$pdf->Cell($TamColLugar,5,'LUGAR','LRT',0,'C',1);
$pdf->Cell($TamColFecha,5,'FECHA','LRT',0,'C',1);
$pdf->Cell($TamColFecha,5,'FECHA','LRT',0,'C',1);
$pdf->Cell($TamPonente,5,'PONENTE','LRT',0,'C',1);
$pdf->Cell($TamHoras,5,'HORA','LRT',1,'C',1);
//$pdf->Cell($TamHoras,5,'HORA','LRT',1,'C',1);

$pdf->Cell($TamColCodigo,5,'EVENTO','LRB',0,'C',1);
$pdf->Cell($TamColNombre ,5,'','LRB',0,'C',1);
$pdf->Cell($TamColLugar,5,'','LRB',0,'C',1);
$pdf->Cell($TamColFecha,5,'INICIO','LRB',0,'C',1);
$pdf->Cell($TamColFecha,5,'CULMINACIÓN','LRB',0,'C',1);
$pdf->Cell($TamPonente,5,'','LRB',0,'C',1);
$pdf->Cell($TamHoras,5,'ENTRADA','LRB',1,'C',1);
//$pdf->Cell($TamHoras,5,'SALIDA','LRB',1,'C',1);

$pdf->SetFillColor(255,255,255);
$pdf->SetFont('helvetica','',6);

	for($i=0;$i<count($EVENTOS);$i++)
	{

	$longitud=$pdf->GetStringWidth($EVENTOS[$i]["tx_nombre_evento"]);
  	$numFilas=ceil($longitud/$TamColNombre);

		
		$pdf->Cell($TamColCodigo,5*$numFilas,$i+1,'TLRB',0,'C',1);
		$pdf->MultiCelda($TamColNombre,5,$EVENTOS[$i]["tx_nombre_evento"],'TLRB','L',1);
		$pdf->Cell($TamColLugar,5*$numFilas,$EVENTOS[$i]["nombre_lugar"],'TLRB',0,'L',1);				
		$pdf->Cell($TamColFecha,5*$numFilas,formatearFecha($EVENTOS[$i]["hh_fecha1"]),'TLRB',0,'C',1);
		$pdf->Cell($TamColFecha,5*$numFilas,formatearFecha($EVENTOS[$i]["hh_fecha2"]),'TLRB',0,'C',1);
		$pdf->Cell($TamPonente,5*$numFilas,$EVENTOS[$i]["NomCompleto"],'TLRB',0,'L',1);		
		$pdf->Cell($TamHoras,5*$numFilas,$EVENTOS[$i]["hh_hora1"],'TLRB',1,'C',1);
		
	}



$pdf->Output("eventos_mensuales.pdf","I");

?>
