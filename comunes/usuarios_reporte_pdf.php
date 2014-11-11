<?php



define('FPDF_FONTPATH','../nomina/font/');
require('../nomina/mc_table3.php');
require('../nomina/fphp.php');
connect(); 
extract ($_POST);
extract ($_GET);
global $Periodo;



/// -------------------------------------------------
//---------------------------------------------------
$filtro1=strtr($filtro1, "*", "'");
//---------------------------------------------------
//---------------------------------------------------
//echo $Periodo;
class PDF extends PDF_MC_Table
{
//Page header
function Header(){
    
	
	//$this->SetFont('Times','',12);
//$pdf->Cell(8, 4, 'PRUEBA'.$_POST['faplicaciones'], 0, 1, 'C');
	   $this->SetFont('Times','',11);
	    $this->SetDrawColor(255, 255, 255); $this->SetFillColor(255, 255, 255); $this->SetTextColor(0, 0, 0);
		$this->SetWidths(array(10,160,10));
		$this->SetAligns(array('L','C','R'));
		$this->Row(array('',utf8_decode('REPORTE LISTADO DE USUARIOS Y ACCESOS') ,''));
		 
		$this->Ln();
		
		$this->SetFont('Times','B',11);
	   $this->SetDrawColor(255, 255, 255); $this->SetFillColor(255, 255, 255); $this->SetTextColor(0, 0, 0);
		$this->SetWidths(array(15,70,20,20,20));
		$this->SetAligns(array('C','L','C','C','C'));
      /// $this->Cell(100, 4, 'Listado de Usuarios del Sistema SIACEM', 0, 1, 'C');
	///// ******************	
}
//Page footer
function Footer(){
    //Position at 1.5 cm from bottom
    $this->SetXY(154,8);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,10,' Pagina:'.$this->PageNo().'/{nb}',0,0,'C');
}
}

//Instanciation of inherited class
$pdf=new PDF('P','mm','Letter');


$pdf->AliasNbPages();
$pdf->AddPage();





       
/////////////////////////////////////////////////////////////////////////////////////////////////

$dependencias = array(

						/*'0001',
						'0002',
					    '0003',
						'0004',
						'0005',
						'0006',
						'0007',
						'0008',*/
						'0010',
						/*'0011',
						'0012'*/
					  );

$aplicaciones = array(

'RH',
'AC',
'AF',
'AP',
'CP',
'GE',
'LG',
'NOMINA',
'PF',
'PV',

'EV',
'PA');





////////////////////////////////////////////////////
/*
for ($d=0; $d< count ($dependencias); $d++)
{
	*/
//$pdf->AddPage();


//$_GET["fdependencia"]=$dependencias[$d];

$DEPENDENCIA= $_POST["fdependencia"];


for ($a=0; $a< count ($aplicaciones); $a++)
{
	
	$APLICACIONES= $aplicaciones[$a];
	
	   $pdf->SetFont('Times','',10);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetWidths(array(40,100));
		$pdf->SetAligns(array('L','L'));

//////////////////////////	
$sql_dependencia= "SELECT  mastdependencias.CodDependencia, mastdependencias.Dependencia
FROM  mastdependencias  where  mastdependencias.CodDependencia='".$DEPENDENCIA."'";

$sql_app= "SELECT mastaplicaciones.CodAplicacion, mastaplicaciones.Descripcion
FROM  mastaplicaciones  where  mastaplicaciones.CodAplicacion='".$APLICACIONES."'";				
			
$sql_dependencia = mysql_query($sql_dependencia) or die(getErrorSql(mysql_errno(), mysql_error(), $sql_dependencia));
$field_dependencia = mysql_fetch_array($sql_dependencia);

$sql_app = mysql_query($sql_app) or die(getErrorSql(mysql_errno(), mysql_error(), $sql_app));
$field_app = mysql_fetch_array($sql_app);

//while ($field_dependencia = mysql_fetch_array($row_dependencia))


		
//////////////////////////////		
		$pdf->SetFont('Times','',15);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetWidths(array(55,120));
		$pdf->SetAligns(array('L','L'));
		//$pdf->Row(array(utf8_decode('DEPENDENCIA:'),utf8_decode($field_dependencia['Dependencia']) ));
		//$pdf->Row(array(utf8_decode('MODULO:'),utf8_decode($field_app['Descripcion']) ));
		
	 //$pdf->Cell(50,10,'DEPENDENCIA: '.$dependencias[$d],0,0,'C');$pdf->Ln(5);
	// $pdf->Cell(50,10,'MODULO: '.$dependencias[$a],0,0,'C');$pdf->Ln(5);
//$_GET["faplicaciones"]=$aplicaciones[$a];



$pdf->SetFont('Times','',12);
//$pdf->Cell(8, 4, 'PRUEBA'.$_POST['faplicaciones'], 0, 1, 'C');

//$pdf->Cell(8, 4, 'PRUEBA', 0, 1, 'C');
/////////////////////////////////////////////////////////////////
$sql= "SELECT
u.Usuario,
mastdependencias.Dependencia,
mastpersonas.NomCompleto
FROM
mastempleado
INNER JOIN usuarios AS u ON mastempleado.CodPersona = u.CodPersona
INNER JOIN mastdependencias ON mastempleado.CodDependencia = mastdependencias.CodDependencia
INNER JOIN mastpersonas ON u.CodPersona = mastpersonas.CodPersona
where
  mastempleado.Estado='A' AND
 mastpersonas.Estado='A' AND
 mastempleado.CodDependencia='".$DEPENDENCIA."'";		
			
$query_lista = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	$rows_total = mysql_num_rows($query_lista);

	
$sql= "SELECT
u.Usuario,
mastdependencias.Dependencia,
mastpersonas.NomCompleto
FROM
mastempleado
INNER JOIN usuarios AS u ON mastempleado.CodPersona = u.CodPersona
INNER JOIN mastdependencias ON mastempleado.CodDependencia = mastdependencias.CodDependencia
INNER JOIN mastpersonas ON u.CodPersona = mastpersonas.CodPersona
where
  mastempleado.Estado='A' AND
 mastpersonas.Estado='A' AND
 
 mastempleado.CodDependencia='".$DEPENDENCIA."'";



$query_lista = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	$rows_lista = mysql_num_rows($query_lista);
	

	
	$y=40;
	while ($field_lista = mysql_fetch_array($query_lista)) {
		
		//DATOS DE USUARIO
///////////////////////////////////////////////////////////////////		
 $quey2 =  "
				
			SELECT
			u.Usuario,
			u.CodPersona,
			p.NomCompleto AS NomEmpleado,
			sa.FlagAdministrador,
			sa.Concepto,
			sa.Grupo,
			seguridad_concepto.Descripcion,
			sa.CodAplicacion
			FROM
			usuarios AS u
			INNER JOIN mastpersonas AS p ON (u.CodPersona = p.CodPersona)
			INNER JOIN mastempleado AS e ON (p.CodPersona = e.CodPersona)
			LEFT JOIN seguridad_autorizaciones AS sa ON (sa.CodAplicacion = '".$APLICACIONES."' 
			
			AND  sa.Usuario = '".$field_lista['Usuario']."') AND u.Usuario = sa.Usuario
			INNER JOIN seguridad_concepto ON seguridad_concepto.Concepto = sa.Concepto
			WHERE u.Usuario = '".$field_lista['Usuario']."'
			AND  e.Estado='A' 
			";

$query_lista2 = mysql_query($quey2) or die(getErrorSql(mysql_errno(), mysql_error(), $quey2));
$rows_lista = mysql_num_rows($query_lista2);

	/*if( $rows_lista >0) {
		$pdf->AddPage();
		$pdf->SetFont('Times','',20);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetWidths(array(55,120));
		$pdf->SetAligns(array('L','L'));
		$pdf->Row(array(utf8_decode('MODULO:'),$field_app['Descripcion'] ));
	}*/


if( $rows_lista >0)

    {
	$pdf->AddPage();
	
/////////////////////////////////////////////////////////////////		
		if( $pdf->GetY() >200) {
			
			$pdf->AddPage();
			
			
		}
		
		$pdf->SetFont('Times','',10);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetWidths(array(30,140));
		$pdf->SetAligns(array('L','L'));
		
		$pdf->Row(array(utf8_decode('DEPENDENCIA:'),utf8_decode($field_lista['Dependencia']) ));
			$pdf->Row(array(utf8_decode('USUARIO:'),utf8_decode($field_lista['Usuario'] )));
			$pdf->Row(array(utf8_decode('NOMBRE:'),utf8_decode($field_lista['NomCompleto'] )));
		//$pdf->Row(array(utf8_decode('APLICACION:'),utf8_decode($_GET["faplicaciones"]) ));
		$pdf->SetFont('Times','B',10);
		$pdf->Row(array(utf8_decode('MODULO:'),utf8_decode($field_app['Descripcion']) ));
	$pdf->SetFont('Times','',10);
		/*
		if ($pdf->GetY() > 50) 
		{
			$pdf->SetXY(0,0); 
			$y=40;  	
			//$pdf->SetX($x+30);
		 }*/
		
		
	
		
			
			//$pdf->Ln(0.5);
		// CONCEPTOS Y PERMISOS
		$pdf->SetFont('Times','B',10);
	   $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetWidths(array(15,100,20,20,20));
		$pdf->SetAligns(array('C','L','C','C','C'));
	//	$pdf->Row(array('-','Funcionalidad','Nuevo','Mostrar','Eliminar'));
        $pdf->Row(array('-','Funcionalidad','','',''));


		
		$sql_permiso = "
				
			SELECT
			u.Usuario,
			u.CodPersona,
			p.NomCompleto AS NomEmpleado,
			sa.FlagAdministrador,
			sa.Concepto,
			sa.Grupo,
			seguridad_concepto.Descripcion,
			sa.CodAplicacion
			FROM
			usuarios AS u
			INNER JOIN mastpersonas AS p ON (u.CodPersona = p.CodPersona)
			INNER JOIN mastempleado AS e ON (p.CodPersona = e.CodPersona)
			LEFT JOIN seguridad_autorizaciones AS sa ON (sa.CodAplicacion = '".$APLICACIONES."' 
			
			AND  sa.Usuario = '".$field_lista['Usuario']."') AND u.Usuario = sa.Usuario
			INNER JOIN seguridad_concepto ON seguridad_concepto.Concepto = sa.Concepto
			WHERE u.Usuario = '".$field_lista['Usuario']."'
			AND  mastempleado.Estado='A' 
			";
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	

	$sql="SELECT 
	
	   c.Concepto, 
	   c.Descripcion AS NomConcepto, 
	   g.Grupo, 
	   g.Descripcion AS NomGrupo, 
	   a.FlagMostrar, 
	   a.FlagAgregar, 
	   a.FlagModificar, 
	   a.FlagEliminar
	   
	   FROM seguridad_concepto c 
	   
	   INNER JOIN seguridad_grupo g ON (c.CodAplicacion=g.CodAplicacion AND c.Grupo=g.Grupo) 
	   LEFT JOIN seguridad_autorizaciones a ON (
	   
	   c.CodAplicacion=a.CodAplicacion 
	   AND c.Grupo=a.Grupo 
	   AND c.Concepto=a.Concepto 
	   AND a.Usuario='".$field_lista['Usuario']."') 
	  
	   WHERE c.CodAplicacion= '".$APLICACIONES."' 
	    -- AND a.FlagMostrar='S'
	   ORDER BY g.Grupo, c.Concepto";
	   
	$query=mysql_query($sql) or die ($sql.mysql_error());
	
	
	while($field=mysql_fetch_array($query)) {
		$chkmostrar='NO';
		if ($field['FlagMostrar']=="S") {
			$concepto="SI"; 
			$dagregar="";
			$dmodificar="";
			$deliminar="";
			 $chkmostrar="SI";
		} else {
			$concepto=""; 
			$dagregar="disabled";
			$dmodificar="disabled";
			$deliminar="disabled";
		}
		if ($field['FlagAgregar']=="S") $chkagregar= utf8_decode("SI"); else $chkagregar="";
		if ($field['FlagModificar']=="S") $chkmodificar=utf8_decode("SI"); else $chkmodificar="";
		if ($field['FlagEliminar']=="S") $chkeliminar=utf8_decode("SI"); else $chkeliminar="";
		
        if ($grupo!=$field['Grupo']) {
			
			$grupo=$field['Grupo'];
	
			// GRUPO
			$pdf->SetFont('Times','B',10);
	    $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(175, 175, 175); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetWidths(array(15,160));
		$pdf->SetAligns(array('L','L'));
		$pdf->Row(array('Acceso:',utf8_decode($field['NomGrupo'])));
		}


			$pdf->SetFont('Times','',8);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetWidths(array(15,70,20,20,20));
			$pdf->SetAligns(array('C','L','C','C','C'));
		  //  $pdf->Row(array($chkmostrar,utf8_decode($field['NomConcepto']),$chkagregar ,$chkmodificar,$chkeliminar));
		   $pdf->Row(array($chkmostrar,utf8_decode($field['NomConcepto']),'' ,'',''));
	}
	$grupo='NULL';
	
	$pdf->Ln(5);
	
	
}// condifcional para que sean cero.
}// usuarios
////////////////////////////////////////////////////////////////
//$s_con01 = ""; //echo $s_con01;
//$q_con01 = mysql_query($s_con01) or die ($s_con01.mysql_error());
//$r_con01 = mysql_num_rows($q_con01); //echo $r_con01;

}// aplicaciones
$pdf->AddPage();
//}// dependencias
$pdf->Output();
?>  
