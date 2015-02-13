<?php

	include("../../clases/MySQL.php");
	include_once("../../comunes/objConexion.php");
	include("../include/funciones_php.php");

	
	$anio=2013;
	$evento=$_GET["evento"];
	
	
	$sql= "select A.co_id_persona_evento, A.CodPersona, A.bo_culmino_evento, A.bo_recibio_certificado, A.bo_ponente, A.tx_nu_certificado, A.co_id_evento, A.eliminado, B.NomCompleto, B.Ndocumento from ev_persona_evento as A, mastpersonas as B  where A.CodPersona=B.CodPersona and A.bo_ponente=0 and A.eliminado=false and A.co_id_evento=".$evento." order by tx_nu_certificado";
	$certificados = $objConexion->consultar($sql,'matriz');
	
	$nRegistros=count($certificados);
	
	//$sql= "select A.co_id_persona_evento, A.CodPersona, A.bo_culmino_evento, A.bo_recibio_certificado, A.bo_ponente, A.tx_nu_certificado, A.co_id_evento, A.eliminado, B.NomCompleto, B.Ndocumento from ev_persona_evento as A, mastpersonas as B  where A.CodPersona=B.CodPersona and A.bo_ponente=0 and A.eliminado=false and A.co_id_evento=".$evento." order by tx_nu_certificado";
		
	$sql= "SELECT REPLACE (B.Ndocumento, '.', '') AS cedulasinpuntos FROM ev_persona_evento as A, mastpersonas as B  where A.CodPersona=B.CodPersona and A.bo_ponente=0 and A.eliminado=false and A.co_id_evento=".$evento." order by tx_nu_certificado";
	$certificadosCed = $objConexion->consultar($sql,'matriz');
	
	
	$sql= "select A.* from ev_tipo_capacitacion as A, ev_evento_capacitacion as B where A.co_id=B.co_id and B.co_id_evento=".$evento.";";
	$tipocapacitacion = $objConexion->consultar($sql,'matriz');
	
	
	$sql= "select tx_nombre_evento, tx_descripcion_evento, nu_horas from ev_evento_capacitacion where co_id_evento=".$evento.";";
	$nombreevento = $objConexion->consultar($sql,'matriz');
	
	
	$sql= "select NomCompleto from mastpersonas where NDocumento='10870192';";
	$contralor = $objConexion->consultar($sql,'matriz');
	

	
	/*** ponente principal ***/
	$sql= "select A.co_id_evento,A.hh_fecha1,A.hh_fecha2,A.co_lugar,A.co_id,A.tx_nombre_evento,A.tx_descripcion_evento,A.hh_hora1,A.hh_hora2,A.bo_certificado,A.eliminado,B.CodPersona,B.bo_culmino_evento,B.bo_recibio_certificado,B.bo_ponente,B.tx_nu_certificado, C.CodPersona, C.Apellido1, C.Nombres, C.NomCompleto,C.Ndocumento from ev_evento_capacitacion as A, ev_persona_evento as B, mastpersonas as C where A.co_id_evento=B.co_id_evento and B.CodPersona=C.CodPersona and A.eliminado=false and B.bo_ponente=1 and A.co_id_evento=".$evento.";";
	$ponente = $objConexion->consultar($sql,'matriz');
	
	$sql= "select C.CodPersona as ponente from ev_evento_capacitacion as A, ev_persona_evento as B, mastpersonas as C where A.co_id_evento=B.co_id_evento and B.CodPersona=C.CodPersona and A.eliminado=false and B.bo_ponente=1 and A.co_id_evento=".$evento.";";
		$Codponente = $objConexion->consultar($sql,'fila');
		$pon=$Codponente['ponente'];
	
	
	$sql= "select A.Descripcion, C.sexo from rh_nivelgradoinstruccion as A, rh_empleado_instruccion as B, mastpersonas as C where A.CodGradoInstruccion=B.CodGradoInstruccion and A.Nivel=B.nivel and B.CodPersona=C.CodPersona and B.CodPersona='".$pon."';";
	$Profponente = $objConexion->consultar($sql,'matriz');
	
	/*** ponente secundario ***/
	$sql= "select A.co_id_evento,A.hh_fecha1,A.hh_fecha2,A.co_lugar,A.co_id,A.tx_nombre_evento,A.tx_descripcion_evento,A.hh_hora1,A.hh_hora2,A.bo_certificado,A.eliminado,B.CodPersona,B.bo_culmino_evento,B.bo_recibio_certificado,B.bo_ponente,B.tx_nu_certificado, C.CodPersona, C.Apellido1, C.Nombres, C.NomCompleto,C.Ndocumento from ev_evento_capacitacion as A, ev_persona_evento as B, mastpersonas as C where A.co_id_evento=B.co_id_evento and B.CodPersona=C.CodPersona and A.eliminado=false and B.bo_ponente=0 and B.bo_ponente_1=1 and A.co_id_evento=".$evento.";";
	$ponente2 = $objConexion->consultar($sql,'matriz');
	
	$sql= "select C.CodPersona as ponente from ev_evento_capacitacion as A, ev_persona_evento as B, mastpersonas as C where A.co_id_evento=B.co_id_evento and B.CodPersona=C.CodPersona and A.eliminado=false and B.bo_ponente=0 and B.bo_ponente_1=1 and A.co_id_evento=".$evento.";";
		$Codponente = $objConexion->consultar($sql,'fila');
		$pon2=$Codponente['ponente'];
	
		
	$sql= "select A.Descripcion, C.sexo from rh_nivelgradoinstruccion as A, rh_empleado_instruccion as B, mastpersonas as C where A.CodGradoInstruccion=B.CodGradoInstruccion and A.Nivel=B.nivel and B.CodPersona=C.CodPersona and B.CodPersona='".$pon2."';";
	$Profponente2 = $objConexion->consultar($sql,'matriz');
	
	
		
	ob_end_clean();		
	require ('../include/fpdf/fpdf.php');
	

	
	$pdf=new FPDF("L","mm","letter");
	
	for($i=0;$i<$nRegistros;$i++)
	{
		
	$pdf->AddPage();
		
	//$pdf->Image("../img/CERTIFICADOS_BASE.jpg", 5, 15, 270,187);
	//$pdf->Image("../img/CERTIFICADOS_BASE_2.jpg", 5, 15);
	$pdf->Image("../img/CERTIFICADOS_1.jpg", 5, 15,270,187);
	//$pdf->Image("../img/CERTIFICADOS_ponencia-CLES-escala-de-gris.jpg", 5, 15,270,187);
	//$pdf->Image("../img/reconocimiento-47-aniversarios.jpg", 5, 15,270,187);
	
	
	//CERTIFICADOS_2.png
	
	$pdf->Ln(48);
	$pdf->SetFillColor(255,255,255);
	$pdf->SetTextColor(0,0,0);	
	$pdf->SetFont('Arial','',8);
	
	$pdf->Cell(260,4,"Este certificado está inscrito en el registro de Acreditación Académica de la Contraloría del Estado Monagas",'',1,'C',0);
	
	$pdf->Ln(4);
	$pdf->SetFont('Arial','B',22);
	
	$pdf->Ln(7);
	$pdf->SetFont('Arial','',8);
	$pdf->SetTextColor(0,0,0);	
	$pdf->Cell(94,4,"",'',0,'C',0);
	$pdf->Cell(25,4,"Certificado Nº",'',0,'C',0);
	$pdf->Cell(24,4,"Actividad Nº",'',0,'C',0);
	$pdf->Cell(17,4,"Fecha",'',1,'C',0);
	
	
	$pdf->Ln(1);
	$pdf->Cell(94,4,"",'',0,'C',0);
	$pdf->Cell(25,4,$certificados[$i]["tx_nu_certificado"],'',0,'C',0);
	$pdf->Cell(24,4,"000".$ponente[0]["co_id_evento"]."-".$anio,'',0,'C',0);
	$pdf->Cell(17,4,formatearFecha($ponente[0]["hh_fecha2"]),'',1,'C',0);
	
	$pdf->Ln(3);
		$pdf->SetFont('Arial','B',15);
		$pdf->MultiCell(0, 7, "A", '', 'C', 0);
			
	$pdf->Ln(2);	
		
	$tam_ancho=260;
	$MARGEN_LEFT=8;
	$MARGEN_TOP=14;
	

	$TamColCodigo=14;
	$TamColNombre=55;
	$TamColLugar=90;
	$TamColFecha=18;
	$TamPonente=50;
	$TamHoras=15;

	
	if($tipocapacitacion[0]["tx_nombre_cap"]=='CHARLA')
	$conj="A LA";
	
	if($tipocapacitacion[0]["tx_nombre_cap"]=='CONFERENCIA')
	$conj="A LA";
	
	
	if($tipocapacitacion[0]["tx_nombre_cap"]=='TALLER' || $tipocapacitacion[0]["tx_nombre_cap"]=='CURSO' || $tipocapacitacion[0]["tx_nombre_cap"]=='SIMPOSIO' || $tipocapacitacion[0]["tx_nombre_cap"]=='SEMINARIO')
	$conj="AL";
	

	
	$pdf->SetFillColor(255,255,255);
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','B',16);
	
		
		$pdf->MultiCell(0, 7, $certificados[$i]["NomCompleto"], '', 'C', 0);
		$pdf->Ln(1);
		$pdf->MultiCell(0, 7, "C.I ".number_format($certificadosCed[$i]["cedulasinpuntos"],0,"","."), '', 'C', 0);
		
		/*$pdf->MultiCell(0, 7, "", '', 'C', 0);
		$pdf->Ln(1);
		$pdf->MultiCell(0, 7, "",'', 'C', 0);*/
		
		$pdf->Ln(5);	
		$pdf->SetFont('Arial','',11);		
		//$pdf->Text(110, 110, "POR HABER ASISTIDO ".$conj." ".$tipocapacitacion[0]["tx_nombre_cap"].":");
		$pdf->MultiCell(0, 7, "POR HABER ASISTIDO ".$conj." ".$tipocapacitacion[0]["tx_nombre_cap"],'', 'C', 0);		
	
		$pdf->Ln(2);	
		$pdf->SetTextColor(0,0,160);	
		$pdf->SetFont('Arial','BI',17);
		$pdf->MultiCell(0, 7, $nombreevento[0]["tx_nombre_evento"], '', 'C', 0);
		
		
		$pdf->Ln(2);
		$pdf->SetFont('Arial','',10.5);
		$pdf->SetTextColor(0,0,0);
		//$pdf->Cell(120,4,"",'',0,'C',0);
		
		
		$pdf->Cell($tam_ancho,4,"Duración: ".$nombreevento[0]["nu_horas"]." horas académicas",'',1,'C',0);
		
		$pdf->Ln(2);
		if($ponente[0]["hh_fecha2"]!=$ponente[0]["hh_fecha1"])
		{
				
			$cadena1=$ponente[0]["hh_fecha1"];
		    list($ano,$mes1,$dia1)=explode("-",$cadena1);
			
		switch($mes1)
		{
			case 1: $mes1="Enero";break;
			case 2: $mes1="Febrero";break;
			case 3: $mes1="Marzo";break;
			case 4: $mes1="Abril";break;
			case 5: $mes1="Mayo";break;
			case 6: $mes1="Junio";break;
			case 7: $mes1="Julio";break;
			case 8: $mes1="Agosto";break;
			case 9: $mes1="Septiembre";break;
			case 10:$mes1="Octubre";break;
			case 11:$mes1="Noviembre";break;
			case 12:$mes1="Diciembre";break;
			default: exit;
		}		
				
			$cadena2=$ponente[0]["hh_fecha2"];
		    list($ano,$mes2,$dia2)=explode("-",$cadena2);	
		    
		switch($mes2)
		{
			case 1: $mes2="Enero";break;
			case 2: $mes2="Febrero";break;
			case 3: $mes2="Marzo";break;
			case 4: $mes2="Abril";break;
			case 5: $mes2="Mayo";break;
			case 6: $mes2="Junio";break;
			case 7: $mes2="Julio";break;
			case 8: $mes2="Agosto";break;
			case 9: $mes2="Septiembre";break;
			case 10:$mes2="Octubre";break;
			case 11:$mes2="Noviembre";break;
			case 12:$mes2="Diciembre";break;
			default: exit;
		}		
		
			
			if($mes1==$mes2)			
			$pdf->Cell($tam_ancho,4,"Maturín, del ".$dia1." al ".$dia2." de ".$mes2." de ".$ano,'',1,'C',0);
			
			
			if($mes1!=$mes2)
			$pdf->Cell($tam_ancho,4,"Maturín, del ".$dia1." de ".$mes1." al ".$dia2." de ".$mes2." de ".$ano,'',1,'C',0);
			
			
			
		}
		
		
		
		if($ponente[0]["hh_fecha2"]==$ponente[0]["hh_fecha1"])
		{
			$cadena=$ponente[0]["hh_fecha2"];
			list($ano,$mes,$dia)=explode("-",$cadena);
			
			
		switch($mes)
		{
			case 1: $mes="Enero";break;
			case 2: $mes="Febrero";break;
			case 3: $mes="Marzo";break;
			case 4: $mes="Abril";break;
			case 5: $mes="Mayo";break;
			case 6: $mes="Junio";break;
			case 7: $mes="Julio";break;
			case 8: $mes="Agosto";break;
			case 9: $mes="Septiembre";break;
			case 10:$mes="Octubre";break;
			case 11:$mes="Noviembre";break;
			case 12:$mes="Diciembre";break;
			default: exit;
		}
			
			
			$pdf->Cell($tam_ancho,4,"Maturín, ".$dia." de ".$mes." de ".$ano,'',1,'C',0);
		}
		
		$prof=$Profponente[0]["Descripcion"];
		$sexo=$Profponente[0]["sexo"];
		
		$prof2=$Profponente2[0]["Descripcion"];
		$sexo2=$Profponente2[0]["sexo"];


/*** para el primer ponente ***/
if($prof=="ESPECIALIZACIÓN")
$profesion="ESP";

if($prof=="ESPECIALIZACION")
$profesion="ESP.";

if($prof=="MAGISTER")
$profesion="MG";

if($prof=="DOCTORADO" && $sexo=="F")
$profesion="DRA.";

if($prof=="DOCTORADO" && $sexo=="M")
$profesion="DR.";


if($prof=="1ER A 6TO GRADO")
$profesion="BR.";

if($prof=="7MO, 8VO, 9NO GRADO")
$profesion="BR.";

if($prof=="4TO, 5TO AÑO ")
$profesion="BR.";

if($prof=="BACHILLER")
$profesion="BR.";

if($prof=="TECNICO SUPERIOR UNIVERSITARIO")
$profesion="TSU.";

if($prof=="INGENIERO ")
$profesion="ING.";

if($prof=="LICENCIADO" && $sexo=="F")
$profesion="LCDA.";

if($prof=="LICENCIADO" && $sexo=="M")
$profesion="LCDO.";


if($prof=="ECONOMISTA ")
$profesion="ECON.";

if($prof=="ABOGADO")
$profesion="ABG.";

if($prof=="MEDICO")
$profesion="MED.";	




/*** para el segundo ponente ***/
if($prof2=="ESPECIALIZACIÓN")
$profesion2="ESP";

if($prof2=="ESPECIALIZACION")
$profesion2="ESP.";

if($prof2=="MAGISTER")
$profesion2="MG";

if($prof2=="DOCTORADO" && $sexo2=="F")
$profesion2="DRA.";

if($prof2=="DOCTORADO" && $sexo2=="M")
$profesion2="DR.";


if($prof2=="1ER A 6TO GRADO")
$profesion2="BR.";

if($prof2=="7MO, 8VO, 9NO GRADO")
$profesion2="BR.";

if($prof=="4TO, 5TO AÑO ")
$profesion="BR.";

if($prof2=="BACHILLER")
$profesion2="BR.";

if($prof2=="TECNICO SUPERIOR UNIVERSITARIO")
$profesion2="TSU.";

if($prof2=="INGENIERO")
$profesion2="ING.";

if($prof2=="LICENCIADO" && $sexo2=="F")
$profesion2="LCDA.";

if($prof2=="LICENCIADO" && $sexo2=="M")
$profesion2="LCDO.";


if($prof2=="ECONOMISTA")
$profesion2="ECON.";

if($prof2=="ABOGADO")
$profesion2="ABG.";

if($prof2=="MEDICO")
$profesion2="MED.";			
	
			
		//zona firmazo
$pdf->SetY(157);
$pdf->SetFont('Arial','B',10);

	
$pdf->Ln(7);

if($pon2[0]=="" || $pon2[0]==null)
{
	$pdf->Cell($tam_ancho/2,5,"_________________________________",'',0,'C',0);
	$pdf->Cell($tam_ancho/2,5,"_________________________________",'',1,'C',0);
	
	$pdf->Cell($tam_ancho/2,5,"LCDO. ".$contralor[0]["NomCompleto"],'',0,'C',0);
	$pdf->Cell($tam_ancho/2,5,$profesion." ".$ponente[0]["NomCompleto"],'',1,'C',0);
	
	$pdf->Cell($tam_ancho/2,4,"CONTRALOR PROVISIONAL DEL ESTADO MONAGAS",'',0,'C',0);
	$pdf->Cell($tam_ancho/2,4,"FACILITADOR (A)",'',1,'C',0);
	
}

else 
{
	$pdf->Cell($tam_ancho/3,5,"_________________________________",'',0,'C',0);
	$pdf->Cell($tam_ancho/3,5,"_________________________________",'',0,'C',0);
	$pdf->Cell($tam_ancho/3,5,"_________________________________",'',1,'C',0);
	
	$pdf->Cell($tam_ancho/3,5,"LCDO. ".$contralor[0]["NomCompleto"],'',0,'C',0);
	$pdf->Cell($tam_ancho/3,5,$profesion." ".$ponente[0]["NomCompleto"],'',0,'C',0);
	$pdf->Cell($tam_ancho/3,5,$profesion2." ".$ponente2[0]["NomCompleto"],'',1,'C',0);
	
	$pdf->Cell($tam_ancho/3,4,"CONTRALOR PROVISIONAL DEL ESTADO MONAGAS",'',0,'C',0);
	$pdf->Cell($tam_ancho/3,4,"FACILITADOR (A)",'',0,'C',0);
	$pdf->Cell($tam_ancho/3,5,"FACILITADOR (A)",'',1,'C',0);
	
	
}


			
	}

	
	$pdf->Output("Certificados.pdf","I");
	
?>	
