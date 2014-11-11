<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');
require('fphp.php');
connect(); 
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
	//$this->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$this->SetFont('Arial', 'B', 8);
	$this->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$this->SetXY(20, 10); $this->Cell(70, 5,utf8_decode('República Bolivariana de Venezuela'), 0, 1, 'L');
	$this->SetXY(20, 14); $this->Cell(70, 5,utf8_decode('Contraloría del Estado Monagas'), 0, 1, 'L'); 
	$this->Ln(4);
	
	$this->SetXY(180, 10);$this->Cell(10,5,'Fecha:',0,0,'');$this->Cell(10,5,date('d/m/Y'),0,1,'');
	$this->SetXY(179, 15);$this->Cell(10,3,utf8_decode('Página:'),0,1,'');
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
	$this->SetFont('Arial', 'B', 10);
	$this->Cell(200, 10, utf8_decode('CLASIFICACION ACTIVOS'), 0, 1, 'C');
	
	$this->SetDrawColor(0, 0, 0); $this->SetFillColor(200, 200, 200); $this->SetTextColor(0, 0, 0);
	$this->SetFont('Arial', 'B', 7);
	$this->Cell(18, 3, 'CODIGO', 1, 0, 'C', 1);
	$this->Cell(80, 3, 'DESCRIPCION', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'NIVEL', 1, 0, 'C', 1);
	$this->Cell(30, 3, 'ESTADO', 1, 1, 'C', 1);
	$this->SetFillColor(255, 255, 255);
	///// ******************	
}
//Page footer
function Footer(){
    //Position at 1.5 cm from bottom
    $this->SetXY(182,12);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,10,' '.$this->PageNo().'/{nb}',0,0,'C');
}
}
//Instanciation of inherited class
$pdf=new PDF('P','mm','letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

//// ---- Consulta para obtener datos 
$sql = "select * from af_clasificacionactivo where CodClasificacion<>''";
$qry = mysql_query($sql) or die ($sql.mysql_error());
$row = mysql_num_rows($qry);

if($row!=0)
   for($i=0; $i<$row; $i++){
      $fiel = mysql_fetch_array($qry);
	  if($fiel['Estado']=='A')$estado='Activo';
	  else $estado='Inactivo';
	  
	  
	  $pdf->SetFillColor(255, 255, 255); 
	  $pdf->SetFont('Arial', 'B', 7);
	  $pdf->SetWidths(array(18,80,20,30));
	  $pdf->SetAligns(array('C','L','C','C'));
	  $pdf->Row(array($fiel['CodClasificacion'],$fiel['Descripcion'],$fiel['Nivel'],$estado));
   }
   
 /*  $scon03 = "select 
   					 CodPersona
			    from 
				     mastdependencias
				where     
					CodDependencia=(select ValorParam from mastparametros where ParametroClave='FIRMAINVENTARIODEP') and 
					CodOrganismo='".$factivo['CodOrganismo']."' ";
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
	/*$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	%23%23
	list($Nombre) = split("[ ]", $field['Nombres']);
	if ($field['Apellido1'] != "") $Apellido = $field['Apellido1']; else $Apellido = $field['Apellido2'];
	$NomCompleto = "$Nombre $Apellido";
	%23%23
	if ($field['CargoEncargado'] != "") {
		if ($field['GradoEncargado'] == "99" && $_PARAMETRO['PROV99'] == $CodPersona) $tmp = "(P)"; else $tmp = "(E)";
		$Cargo = $field['CargoEncargado'];
	}
	else { $Cargo = $field['Cargo']; $tmp = ""; }
	%23%23
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
	//%23%23	consulto el nivel de instruccion
	/*$sql = "SELECT
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
	%23%23
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
   
    $pdf->Cell(20,10,'Total de Bienes: '.$ractivo,0,1,'L');
$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(202, 202, 202); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(80,5,'_____________________________',0,0,'C');$pdf->Cell(100,5,'RECIBI CONFORME: _____________________________',0,1,'C');
	$pdf->Cell(80,2,$nivel.'. '.$nombreCompleto,0,0,'C');    $pdf->Cell(120,2,$nivel02.'. '.$nombreCompleto02,0,1,'C');
	$pdf->Cell(80,3,$cargo,0,0,'C');
	$pdf->Cell(25,3,'',0,0,'C');                             $pdf->MultiCell(80,3,$cargo02,0,'C');*/
$pdf->Output();
?>  
