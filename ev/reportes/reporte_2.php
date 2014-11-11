<?php
	include("../../clases/MySQL.php");
	//include_once("../../comunes/objConexion.php");
	include("../include/funciones_php.php");

	$host = "192.168.0.133";
	$usuario = $_SESSION["MYSQL_USER"];
	$clave = $_SESSION["MYSQL_CLAVE"];
	$baseDatos = $_SESSION["MYSQL_BD"];
	$puerto = "3306";
		
	$host = "localhost";
	$usuario = $_SESSION["MYSQL_USER"];
	$clave = $_SESSION["MYSQL_CLAVE"];
	$baseDatos = $_SESSION["MYSQL_BD"];
	$puerto = "3306";

	
		
	$objConexion = new MySQL($host,$usuario,$clave,$baseDatos,$puerto);
	
   
$anio=2013;
$codEventoS=$_GET["codEventoS"];

	
	$sql= "select A.co_id_evento,A.hh_fecha1,A.hh_fecha2,A.co_lugar,A.co_id,A.tx_nombre_evento,A.tx_descripcion_evento,A.hh_hora1,A.hh_hora2,A.bo_certificado,A.eliminado,B.CodPersona,B.bo_culmino_evento,B.bo_recibio_certificado,B.bo_ponente,B.tx_nu_certificado, C.CodPersona, C.NomCompleto,C.Ndocumento from ev_evento_capacitacion as A, ev_persona_evento as B, mastpersonas as C where A.co_id_evento=B.co_id_evento and B.CodPersona=C.CodPersona and A.eliminado=false and B.bo_ponente=1 and A.co_id_evento=".$codEventoS.";";
    $eventos = $objConexion->consultar($sql,'matriz');


    $sql= "select A.co_id_persona_evento, A.CodPersona, A.bo_culmino_evento, A.bo_recibio_certificado, A.bo_ponente, A.tx_nu_certificado, A.co_id_evento, A.eliminado, B.NomCompleto, REPLACE (B.Ndocumento, '.', '') AS cedulasinpuntos from ev_persona_evento as A, mastpersonas as B  where A.CodPersona=B.CodPersona and A.bo_ponente=0 and A.bo_ponente_1=0 and A.eliminado=false and A.co_id_evento=".$codEventoS." order by tx_nu_certificado;";
    $personas = $objConexion->consultar($sql,'matriz');


    //Esto nunca debería pasar, porque está validado que no se guarden eventos sin participantes

    if($personas[0]["co_id_persona_evento"]=="null" || $personas[0]["co_id_persona_evento"]=="" || $personas[0]["co_id_persona_evento"]==0 || $personas[0]["co_id_persona_evento"]=="NULL")
    {
   
        echo "<DIV align='center'>No hay participantes registrados</DIV>";
       
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
$pdf->SetFillColor(255,255,255);
$pdf->SetLineWidth(0.1);

$pdf->SetFont('helvetica','B',10);
$pdf->MultiCell($area_tabla,5,"EVENTOS Y PARTICIPANTES",'','C',1);


$pdf->Cell(30,1,'','',0,'C',1);
$pdf->Cell(110,1,'','',0,'C',1);
$pdf->Cell(40,1,'','',1,'C',1);


$pdf->SetFont('helvetica','B',7);

$pdf->Cell($TamColCodigo,5,'EVENTO:','',0,'L',1);
$pdf->Cell($TamColLugar*2,5,$eventos[0]["tx_nombre_evento"],'',0,'L',1);
//$pdf->Cell($TamColCodigo,5,'','',0,'L',1);
$pdf->Cell($TamColCodigo,5,'PONENTE:','',0,'L',1);
$pdf->Cell($TamColLugar,5,$eventos[0]["NomCompleto"],'',1,'L',1);

$pdf->SetFillColor(200,200,200);
$pdf->SetFont('helvetica','B',6.5);

$pdf->Cell($TamColCodigo,5,'N°','LRTB',0,'C',1);
$pdf->Cell($TamHoras*2+7,5,'CÉDULA','LRTB',0,'C',1);
$pdf->Cell($TamColLugar+$TamColNombre,5,'PARTICIPANTES','LRTB',0,'C',1);
$pdf->Cell($TamColFecha+10,5,'CERTIFICADO','LRTB',0,'C',1);
$pdf->Cell($TamColFecha*2,5,'N° CERTIFICADO','LRTB',1,'C',1);

$pdf->SetFillColor(255,255,255);
$pdf->SetFont('helvetica','',7);

    for($i=0;$i<count($personas);$i++)
    {

        if($personas[$i]["bo_recibio_certificado"]==0)
        $recibio="No";
       
        if($personas[$i]["bo_recibio_certificado"]==1)
        $recibio="Si";

        $pdf->Cell($TamColCodigo,5,$i+1,'LRTB',0,'C',1);
        $pdf->Cell($TamHoras*2+7,5,number_format($personas[$i]["cedulasinpuntos"],0,"","."),'LRTB',0,'C',1);
        $pdf->Cell($TamColLugar+$TamColNombre,5,$personas[$i]["NomCompleto"],'LRTB',0,'L',1);
        $pdf->Cell($TamColFecha+10,5,$recibio,'LRTB',0,'C',1);
        $pdf->Cell($TamColFecha*2,5,$personas[$i]["tx_nu_certificado"],'LRTB',1,'C',1);
    }



$pdf->Output("eventos_participantes.pdf","I");

?>


