<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');
require('fphp.php');
connect(); 
extract ($_POST);
extract ($_GET);

class PDF extends FPDF{

function Header(){
	
    $sql = "select FechaRevisadoPor,NroIncorporacion from af_activo where Activo='".$_GET['Activo']."'";  //echo $sql;
    $qry = mysql_query($sql) or die ($sql.mysql_error());
    $field = mysql_fetch_array($qry);
	
	list($sano,$smes,$sdia)= split('[-]',$field['FechaRevisadoPor']);
	
	//global $Periodo;
	global $fp_hasta,$fp_desde;
	//echo $Periodo.'/'.$fp_hasta.'****';
	$this->Image('../imagenes/logos/contraloria.jpg', 20, 10, 10, 10);	
	$this->SetFont('Arial', 'B', 8);
	$this->SetXY(70, 10); $this->Cell(100, 5,utf8_decode( 'República Bolivariana de Venezuela'), 0, 1, 'L');
						  
	$this->SetXY(69, 15); $this->Cell(100, 5,utf8_decode('Contraloría del Estado Sucre'), 0, 1, 'L');
						  
	$this->SetXY(20, 20); $this->Cell(155, 5, 'Fecha:', 0, 0, 'R');$this->Cell(10,5,date("d-m-Y"),0,1,'');
	
	$this->SetXY(20, 25); $this->Cell(155, 4, utf8_decode('Pág.:'), 0, 1, 'R'); /// NRO DE PÁGINA
	
	$this->SetXY(20, 35); $this->Cell(155, 4, utf8_decode('Nro.:'), 0, 0, 'R');/// NRO DE DOCUMENTO
						  $this->Cell(10, 4, $field['NroIncorporacion'].'-'.$sano, 0, 1, 'L');
	
	$this->SetFont('Arial', 'B', 10);
	   $this->Cell(50, 5, '', 0, 0, 'C');
	   $this->Cell(70, 5, utf8_decode('ACTA DE INCORPORACION DE BIENES MUEBLES'), 0, 1, 'C');
	   $this->Ln(10);
	
     $this->Ln();

}
//Page footer
function Footer(){
    //Position at 1.5 cm from bottom
    $this->SetXY(152,23);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,8,' '.$this->PageNo().'/{nb}',0,0,'C');
}
}
//Instanciation of inherited class
$pdf=new PDF('P','mm','letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

$scon = "select 
				mp.NomCompleto,
				mp.NDocumento,
				a.ClasificacionPublic20,
				c.Descripcion as DescripClasificacion20,
				a.Activo,
				a.Descripcion as DescripActivo,
				a.Ubicacion,
				b.Descripcion as DescripUbicacion,
				a.MontoLocal,
				a.NroIncorporacion,
				a.NroActaEntrega,
				a.RevisadoPor,
				a.CargoRevisadoPor,
				a.ConformadoPor,
				a.CargoConformadoPor,
				a.AprobadoPor,
				a.CargoAprobadoPor
			from 
				af_activo a
				inner join mastpersonas mp on (mp.CodPersona=a.RevisadoPor) 
				inner join af_ubicaciones b on (b.CodUbicacion=a.Ubicacion) 
				inner join af_clasificacionactivo20 c on (c.CodClasificacion=a.ClasificacionPublic20)
		   where 
			    a.Activo='".$_GET['Activo']."' and 
				a.Estado='AP' and 
				a.CodOrganismo = '".$_GET['CodOrganismo']."'"; //echo $scon;
$qcon = mysql_query($scon) or die ($scon.mysql_error());
$rcon = mysql_num_rows($qcon);

if($rcon!=0)$fcon=mysql_fetch_array($qcon);

  $montoLocal = number_format($fcon['MontoLocal'],2,',','.');
  
//// Consulta realizada para obtener el cargo actual del empleado 
$cargo[0]= $fcon['CargoRevisadoPor'];
$cargo[1]= $fcon['CargoConformadoPor'];
$cargo[2]= $fcon['CargoAprobadoPor'];
$valor_cargo= 3;

for($x=0; $x<$valor_cargo; $x++){ 
  $scargo = "select DescripCargo from rh_puestos where CodCargo='$cargo[$x]'";
  $qcargo = mysql_query($scargo) or die ($scargo.mysql_error());
  $fcargo = mysql_fetch_array($qcargo);
  if($x==0)$dc_revisadopor=$fcargo['DescripCargo']; 
  if($x==1)$dc_conformadopor=$fcargo['DescripCargo']; 
  if($x==2)$dc_aprobadopor=$fcargo['DescripCargo'];
}

$scon03 = "select 
 				  b.DescripCargo 
		     from 
			      rh_empleadonivelacion a 
				  inner join rh_puestos b on (a.CodCargo=b.CodCargo) 
		     where 
			      a.Secuencia='".$fcon02['0']."' and 
				  a.CodPersona='".$fcon['AprobadoPor']."'"; //echo $scon03;
$qcon03 = mysql_query($scon03) or die ($scon03.mysql_error());
$fcon03 = mysql_fetch_array($qcon03);


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

list($nombreCompleto, $cargo, $nivel) = getfirma($fcon['RevisadoPor']);
list($nombreCompleto02, $cargo02, $nivel02) = getfirma($fcon['ConformadoPor']);
list($nombreCompleto03, $cargo03, $nivel03) = getfirma($fcon['AprobadoPor']);
//echo $nombreCompleto, $cargo, $nivel;

$parrafo1 = utf8_decode("El (la) suscrito(a) ").$nivel.". ".utf8_decode($fcon['NomCompleto']).utf8_decode(", C.I: N° ").($fcon['NDocumento'].' '.$dc_revisadopor).utf8_decode(" de la Contraloría del Estado, en cumplimiento al Artículo 8, Titulo Once de la Ley de Contraloria del Estado Sucre, hace cosntar por medio de la presente, que los bienes que a continuación se especifican han sido incorporados al Inventario General de esta Institución.");


$pdf->SetFont('Arial', '', 12);
		$pdf->SetXY(20,50);
		$pdf->MultiCell(175, 6, $parrafo1, 0, 'J');
		$pdf->Ln(6);

$pdf->SetFont('Arial', '', 7);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->Cell(12,'','','');
	$pdf->Cell(20, 3, 'CLASIFICACION', 1, 0, 'C', 1);
	$pdf->Cell(14, 3, 'CANTIDAD', 1, 0, 'C', 1);
	$pdf->Cell(24, 3, utf8_decode('N°IDENTIFICACION'), 1, 0, 'C', 1);
	$pdf->Cell(50, 3, 'DESCRIPCION', 1, 0, 'C', 1);
	$pdf->Cell(50, 3, 'UBICACION', 1, 0, 'C', 1);
	$pdf->Cell(13, 3, 'PRECIO', 1, 1, 'C', 1); //$pdf->Ln();

$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
$pdf->Cell(12,'','','');
	 $pdf->SetFont('Arial', '', 6.5);
	 $pdf->SetWidths(array(20,14,24,50,50,13));
	 $pdf->SetAligns(array('C','C','C','L','L','R'));
	 $pdf->Row(array($fcon['DescripClasificacion20'],'1',$fcon['Activo'],$fcon['DescripActivo'],utf8_decode($fcon['DescripUbicacion']),$montoLocal));
		
$valor = 4;
  for($i=0; $i<$valor; $i++){ 
	 $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	 $pdf->Cell(12,'','','');
	 $pdf->SetFont('Arial', '', 6.5);
	 $pdf->SetWidths(array(20,14,24,50,50,13));
	 $pdf->SetAligns(array('C','L','R','R','R','R'));
	 $pdf->Row(array('','','','','',''));
  }
  
     $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	 $pdf->Cell(12,'','','');
	 $pdf->SetFont('Arial', 'B', 6.5);
	 $pdf->SetWidths(array('','',158,13));
	 $pdf->SetAligns(array('C','L','R','',''));
	 $pdf->Row(array('','','Total en Bs.==> ',$montoLocal)); $pdf->Ln();
	 
	 $pdf->Rect(40,140,50,'');
	 $pdf->Rect(116,140,50,'');
	 $pdf->Rect(76,159,50,'');
	 
	 /// ------------ QUIEN REVISA
	 $pdf->SetFont('Arial', 'B', 8);
	 $pdf->SetXY(35, 141); $pdf->Cell(60, 5,$nivel.' '.$nombreCompleto, 0, 1, 'C');
	 $pdf->SetXY(35, 144); $pdf->Cell(60, 5,$cargo, 0, 1, 'C'); 
	 
	 /// ------------ QUIEN CONFORMA
	 $pdf->SetFont('Arial', 'B', 8);
	 $pdf->SetXY(112, 141); $pdf->Cell(60, 5,$nivel02.' '.$nombreCompleto02, 0, 1, 'C');
	  $pdf->SetXY(110, 144); $pdf->Cell(60, 5,$cargo02, 0, 1, 'C');
	 
	 /// ------------ QUIEN APRUEBA
	 $pdf->SetFont('Arial', 'B', 8);
	 $pdf->SetXY(72, 160); $pdf->Cell(60, 5,$nivel03.' '.$nombreCompleto03, 0, 1, 'C');
	 $pdf->SetXY(72, 163); $pdf->Cell(62, 5,$cargo03, 0, 1, 'C');
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