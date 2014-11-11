<?php
define('FPDF_FONTPATH','font/');
require('mc_table3.php');
require('fphp.php');
connect();
//---------------------------------------------------
$filtro=strtr($filtro, "*", "'");
$filtro=strtr($filtro, ";", "%");
if ($ordenar == "") $orderby = "ORDER BY me.CodEmpleado";
elseif($ordenar == "mp.Ndocumento") $orderby = "ORDER BY LENGTH(mp.Ndocumento), mp.Ndocumento";
else $orderby = "ORDER BY $ordenar";
//---------------------------------------------------

//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf) {
	$pdf->AddPage('P');
	$pdf->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5,utf8_decode( 'Contraloría del estado Monagas'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode('Dirección de Recursos Humanos'), 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 10, 'Listado de Empleados', 0, 1, 'C');	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
		$pdf->SetWidths(array(10, 40, 15, 40, 40, 40 , 15));
	$pdf->SetAligns(array('C', 'L', 'R', 'L', 'L', 'C', 'C'));
	$pdf->Row(array(utf8_decode('Código'), 'Nombre Completo', 'Documento', 'Cargo', 'Dependencia', 'Grado Instr.', 'Estado'));
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
	$y=$pdf->GetY();
	$pdf->Rect(5, $y, 200, 0.1, "DF");
	$pdf->Ln(2);
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
$pdf->SetMargins(5, 5, 5);
Cabecera($pdf);
//	Cuerpo
//$sql="SELECT me.CodEmpleado, me.Estado, mp.NomCompleto, mp.Ndocumento, rp.DescripCargo, md.Dependencia FROM mastempleado me INNER JOIN mastpersonas mp ON (me.CodPersona=mp.CodPersona) INNER JOIN rh_puestos rp ON (me.CodCargo=rp.CodCargo) INNER JOIN mastdependencias md ON (me.CodDependencia=md.CodDependencia) WHERE me.CodEmpleado<>'' $filtro $orderby";

  $sql="SELECT me.CodEmpleado, me.Estado,
				ei.CodGradoInstruccion,
				gi.Descripcion,
           mp.NomCompleto,
           mp.Ndocumento, rp.DescripCargo, md.Dependencia 
            FROM mastempleado me 
             INNER JOIN mastpersonas     mp ON (me.CodPersona=mp.CodPersona) 
             INNER JOIN rh_puestos       rp ON (me.CodCargo=rp.CodCargo) 
             INNER JOIN mastdependencias md ON (me.CodDependencia=md.CodDependencia)
             INNER JOIN rh_empleado_instruccion AS ei ON me.CodPersona = ei.CodPersona
			 INNER JOIN rh_gradoinstruccion AS gi ON ei.CodGradoInstruccion = gi.CodGradoInstruccion
				
              WHERE me.CodEmpleado<>'' $filtro GROUP BY me.CodPersona $orderby";
              
    
      $sql_totales="SELECT 
      
          
			SUM(if( ei.CodGradoInstruccion = 'I' , 1, 0)) AS secundaria ,
			SUM(if( ei.CodGradoInstruccion = 'II' , 1, 0)) AS bachiller ,
			SUM(if( ei.CodGradoInstruccion = 'III' , 1, 0)) AS tecnico ,
			SUM(if( ei.CodGradoInstruccion = 'IV' , 1, 0)) AS tsu ,
			SUM(if( ei.CodGradoInstruccion = 'V' , 1, 0)) AS magister ,
			SUM(if( ei.CodGradoInstruccion = 'VI' , 1, 0)) AS universitaria ,
			SUM(if( ei.CodGradoInstruccion = 'VII' , 1, 0)) AS postgrado ,
			SUM(if( ei.CodGradoInstruccion = 'PRI' , 1, 0)) AS primaria 

      
            FROM mastempleado me 
             INNER JOIN mastpersonas     mp ON (me.CodPersona=mp.CodPersona) 
             INNER JOIN rh_puestos       rp ON (me.CodCargo=rp.CodCargo) 
             INNER JOIN mastdependencias md ON (me.CodDependencia=md.CodDependencia)
             INNER JOIN rh_empleado_instruccion AS ei ON me.CodPersona = ei.CodPersona
			 INNER JOIN rh_gradoinstruccion AS gi ON ei.CodGradoInstruccion = gi.CodGradoInstruccion
				
              WHERE me.CodEmpleado<>'' $filtro $orderby";          
              
$query=mysql_query($sql) or die ($sql.mysql_error());

$pri=0;
$tsu=0;
$bachiller=0;
$secundaria=0;
$tecnico=0;
$universitaria=0;
$postgrado=0;
$magister=0;



while ($field=mysql_fetch_array($query)) {
	
	
	if ($field['Estado']=="A") $status="Activo"; else $status="Inactivo";
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(10, 40, 15, 40, 40, 40 , 15));
	$pdf->SetAligns(array('C', 'L', 'R', 'L', 'L', 'C', 'C'));
	$pdf->Row(array($field['CodEmpleado'], utf8_decode($field['NomCompleto']), number_format($field['Ndocumento'], 0, '', '.'), utf8_decode($field['DescripCargo']), utf8_decode($field['Dependencia']), utf8_decode($field['Descripcion']), $status));
	
	if( $field['CodGradoInstruccion'] == 'I') $secundaria = $secundaria+1;
	if( $field['CodGradoInstruccion'] == 'II') $bachiller = $bachiller+1;
	if( $field['CodGradoInstruccion'] == 'III') $tecnico = $tecnico+1;
	if( $field['CodGradoInstruccion'] == 'IV') $tsu = $tsu+1;
	if( $field['CodGradoInstruccion'] == 'V') $magister = $magister+1;
	if( $field['CodGradoInstruccion'] == 'VI') $universitaria = $universitaria+1;
	if( $field['CodGradoInstruccion'] == 'VII') $postgrado = $postgrado+1;
	if( $field['CodGradoInstruccion'] == 'PRI') $pri = $pri+1;	

}

$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 8);
	
$pdf->SetWidths(array(40, 15));
$pdf->SetAligns(array('L', 'C'));	
$pdf->ln(10);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(10,20,'');$pdf->Row(array('Primaria        ',$pri ));
$pdf->Cell(10,20,'');$pdf->Row(array('Bachiller       ',$bachiller+$tecnico));
$pdf->Cell(10,20,'');$pdf->Row(array('Tecnico Superior Universitario',$tsu));
$pdf->Cell(10,20,'');$pdf->Row(array('Universitario   ',$universitaria));
$pdf->Cell(10,20,'');$pdf->Row(array('Post Grado       ',$postgrado));
$pdf->Cell(10,20,'');$pdf->Row(array('Magister        ',$magister));
$pdf->SetFont('Arial', 'B', 8);
 $pdf->SetFillColor(190, 190, 190);
$pdf->Cell(10,20,'');$pdf->Row(array('Totales: ', ($pri+$bachiller+ $secundaria+$tecnico+$tsu+$postgrado+$universitaria + $magister)));

//---------------------------------------------------

$pdf->Output();
?>  
