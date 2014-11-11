<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');
require('fphp.php');
require('tcpdf/tcpdf.php');
connect(); 
mysql_query("SET NAMES 'utf8'");
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
	$this->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$this->SetFont('Arial', 'B', 9);
	$this->SetXY(20, 10); $this->Cell(70, 5,utf8_decode('República Bolivariana de Venezuela'), 0, 1, 'L');
	$this->SetXY(20, 14); $this->Cell(70, 5,utf8_decode('Contraloría del Estado Monagas'), 0, 1, 'L'); 
	$this->Ln(4);
	
	$this->SetXY(200, 10);$this->Cell(10,5,'FORMULARIO BM-1',0,1,'');
	$this->SetXY(200, 15);$this->Cell(10,5,utf8_decode('Hoja N°'),0,1,'');
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
	/*$this->SetFont('Arial', 'B', 10);
	$this->Cell(105, 10, '', 0, 0, 'C');
	$this->Cell(47, 10, utf8_decode('Ejecución Presupuestaria'), 0, 0, 'C');
    $this->Cell(13, 10, $mes, 0, 0, 'C'); $this->Cell(13, 10, $fano, 0, 1, 'C');*/
	///// PRUEBA ***********
	$this->SetFont('Arial', 'B', 8);
	
	$sql = "select a.* from af_activo a where CodOrganismo<>'' $filtro"; //echo $sql;
	$qry = mysql_query($sql) or die ($sql.mysql_error());
	$field = mysql_fetch_array($qry); 
	
	$scon01 = "select 
					 CodDependencia, Dependencia, CodPersona 
				from 
				     mastdependencias a 
			   where 
			   	     CodDependencia='".$field['CodDependencia']."'";
	$qcon01 = mysql_query($scon01) or die ($scon01.mysql_error());
	$fcon01 = mysql_fetch_array($qcon01);
	
	$scon02 = "select 
					 a.*,
					 b.DescripCargo,
					 c.NomCompleto,
					 c.CodPersona 
				 from 
				     rh_empleadonivelacion a 
					 inner join rh_puestos b on (b.CodCargo=a.CodCargo) 
					 inner join mastpersonas c on (c.CodPersona=a.CodPersona)
				where 
				     a.Secuencia=(select max(Secuencia) from rh_empleadonivelacion where CodPersona='".$fcon01['CodPersona']."') and 
					 a.CodPersona = '".$fcon01['CodPersona']."'";
	 $qcon02 = mysql_query($scon02) or die ($scon02.mysql_error());
	 $fcon02 = mysql_fetch_array($qcon02);
	
	 $cod_personaDependencia=$fcon02['CodPersona']; //echo $cod_personaDependencia;
	
	
	$s_cons = "select 
	                       b.CodOrganismo, 
						   b.Organismo,
						   c.Descripcion as DescpCentroCosto,                                              
						   d.Dependencia,
					           e.Descripcion
					  from 
					       af_activo a
					       inner join mastorganismos b on (b.CodOrganismo=a.CodOrganismo)
						   inner join ac_mastcentrocosto c on (c.CodCentroCosto=a.CentroCosto)
					       inner join mastdependencias d on (d.CodDependencia=a.CodDependencia)
						inner join af_ubicaciones e on (e.CodUbicacion=a.Ubicacion)
						  where 
					       a.CodOrganismo='".$field['CodOrganismo']."' and 
						   a.CentroCosto='".$field['CentroCosto']."' and
  						   a.Ubicacion='".$field['Ubicacion']."' and  
						   a.CodDependencia='".$field['CodDependencia']."'";  //echo $s_organismo; 
	$q_cons = mysql_query($s_cons) or die ($s_cons.mysql_error());
	$r_cons = mysql_num_rows($q_cons);
	if($r_cons!="") $f_cons=mysql_fetch_array($q_cons);
	
	
	$s_estado = "select 
					   a.Direccion,
	                   d.Estado,
					   c.Municipio 
				   from 
				        mastorganismos a
						inner join mastciudades b on (b.CodCiudad = a.CodCiudad) 
						inner join mastmunicipios c on (c.CodMunicipio = b.CodMunicipio) 
						inner join mastestados d on (d.CodEstado = c.CodEstado) 
				  where 
				        a.CodOrganismo = '".$field['CodOrganismo']."'"; 
	$q_estado = mysql_query($s_estado) or die ($s_estado.mysql_error());
	$r_estado = mysql_num_rows($q_estado);
	
	if($r_estado!="")$f_estado=mysql_fetch_array($q_estado);
	
	
	$cadena=strtoupper(utf8_decode($f_cons['Descripcion']));
	
// Para pasar a minúsculas
$texto = strtolower($texto);
// Para pasar a mayúsculas
$texto = strtoupper($texto);
// Para pasar a mayúsculas solo la primera letra de cada palabra
$texto = ucwords($texto);
// Para pasar a mayúsculas solo la primera letra de toda la cadena
$texto = ucfirst($texto) ;
	
	
	$this->SetXY(10,22);$this->SetFont('Arial', 'B', 8);
	                    $this->Cell(35, 3, 'ENTIDAD PROPIETARIA:', 0, 0, 'L');
						$this->SetFont('Arial', '', 8);	$this->Cell(80, 3, utf8_decode($f_cons['Organismo']), 0, 0, 'L');
						$this->SetFont('Arial', 'B', 8);
	                    $this->Cell(17, 3, 'SERVICIO:', 0, 0, 'L');
						$this->SetFont('Arial', '', 8);	$this->Cell(20, 3, utf8_decode($f_cons['Dependencia']), 0, 1, 'L');
						
	$this->SetXY(10,25);$this->SetFont('Arial', 'B', 8);
						$this->Cell(45, 3, 'UNIDAD DE TRABAJO O AREA:', 0, 0, 'L');
						$this->SetFont('Arial', '', 8);$this->Cell(100, 3, $cadena, 0, 1, 'L');
						
	$this->SetXY(10,28);$this->SetFont('Arial', 'B', 8);
						$this->Cell(16, 3, 'ESTADO:', 0, 0, 'L');
						$this->SetFont('Arial', '', 8);$this->Cell(35, 3, $f_estado['Estado'], 0, 0, 'L');
						$this->SetFont('Arial', 'B', 8);
						$this->Cell(16, 3, 'DISTRITO:', 0, 0, 'L');
						$this->SetFont('Arial', '', 8);$this->Cell(35, 3, $f_estado['Estado'], 0, 0, 'L');
						$this->SetFont('Arial', 'B', 8);
						$this->Cell(18, 3, 'MUNICIPIO:', 0, 0, 'L');
						$this->SetFont('Arial', '', 8);$this->Cell(35, 3, $f_estado['Municipio'], 0, 1, 'L');
												
	$this->SetXY(10,31);$this->SetFont('Arial', 'B', 8);
					    $this->Cell(32, 3, 'DIRECCION O LUGAR:', 0, 0, 'L');
						$this->SetFont('Arial', '', 8);$this->Cell(100, 3, utf8_decode($f_estado['Direccion']), 0, 0, 'L');
						$this->SetFont('Arial', 'B', 8);
					    $this->Cell(12, 3, 'FECHA:', 0, 0, 'L');
						$this->SetFont('Arial', '', 8);$this->Cell(10, 3, date("d/m/Y"), 0, 1, 'L'); $this->Ln(5);
	
	$this->SetFont('Arial', 'B', 8);
	$this->Cell(48, 7, utf8_decode('Clasificación(Código)'), 1, 0, 'C');
    $this->Cell(15, 14, utf8_decode('Cantidad'), 1, 0, 'C');	
	$this->Cell(30, 14, utf8_decode('N° Identificación'), 1, 0, 'C');
	$this->Cell(105, 14, utf8_decode('Nombre y Descripción de los Elementos'), 1, 0, 'C');
	$this->Cell(30, 14, utf8_decode('Valor Unitario Bs.'), 1, 0, 'C');
	$this->Cell(30, 14, utf8_decode('Valor TotaL Bs.'), 1, 1, 'C');
	
	
	$this->SetXY(10,'46');
	$this->Cell(16, 7, utf8_decode('Grupo'), 1, 0, 'C'); 
	$this->Cell(16, 7, utf8_decode('SubGrupo'), 1, 0, 'C');
	$this->Cell(16, 7, utf8_decode('Sección'), 1, 1, 'C');
	/*$this->MultiCell(10, 7, utf8_decode('Grupo'), 1, 'C', ''); 
	$this->MultiCell(10, 7, utf8_decode('SubGrupo'), 1, 'C', '');
	$this->MultiCell(10, 7, utf8_decode('Cantidad'), 1, 'C', '');
	//$this->MultiCell(10, 7,'',1,'',1);
	
	/*$this->SetFont('Arial', 'B', 9);
	$this->Cell(200, 3, 'INVENTARIO  ACTIVOS COSTO', 0, 1, 'C');*///$this->Ln(4);
	
		
	/*$this->SetDrawColor(0, 0, 0); $this->SetFillColor(200, 200, 200); $this->SetTextColor(0, 0, 0);
	$this->SetFont('Arial', 'B', 8);
	$this->Cell(20, 3, 'ACTIVO', 1, 0, 'C', 1);
	$this->Cell(21, 3, 'COD. INTERNO', 1, 0, 'C', 1);
	$this->Cell(80, 3, 'DESCRIPCION', 1, 0, 'C', 1);
	$this->Cell(50, 3, 'CLASIFICACION', 1, 0, 'C', 1);
	$this->Cell(25, 3, 'MONTO COSTO', 1, 1, 'C', 1);
	$this->SetFillColor(255, 255, 255);*/
	///// ******************	
}
//Page footer
function Footer(){
    //Position at 1.5 cm from bottom
    $this->SetXY(164,13);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,10,' '.$this->PageNo().'/{nb}',0,0,'C');
}
}
//Instanciation of inherited class
$pdf=new PDF('L','mm','letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

//// ---- Consulta para obtener datos 
$sactivo = "select 
				  a.*, 
				  b.Descripcion as DescpClasificacion20,
				  c.Descripcion as DescpUbicacion,
				  b.Nivel,
				  b.CodClasificacion
			  from
				  af_activo a 
				  inner join af_clasificacionactivo20 b on (b.CodClasificacion=a.ClasificacionPublic20) 
				  inner join af_ubicaciones c on (c.CodUbicacion=a.Ubicacion)
			 where 
			      CodOrganismo<>'' $filtro
   			ORDER BY a.ClasificacionPublic20, a.Descripcion"; //echo $sactivo;
$qactivo = mysql_query($sactivo) or die ($sactivo.mysql_error());
$ractivo = mysql_num_rows($qactivo);

if($ractivo!=0)
   for($i=0; $i<$ractivo; $i++){
      $factivo = mysql_fetch_array($qactivo);
	  
	  if($factivo['Nivel']=='4'){
	    $cod = substr($factivo['CodClasificacion'], 0, -8); //echo 'cod=  '.$cod;   //Cola  
	    $cod2 = substr($factivo['CodClasificacion'], 2, -6); //echo 'cod2=  '.$cod2; /// Punta 
		$cod3 = substr($factivo['CodClasificacion'], 4, -3); 
	  }else
	   if($factivo['Nivel']=='3'){
		$cod = substr($factivo['CodClasificacion'], 0, -5); //echo 'cod=  '.$cod;   //Cola  
	    $cod2 = substr($factivo['CodClasificacion'], 2, -3); //echo 'cod2=  '.$cod2; /// Punta 
		$cod3 = substr($factivo['CodClasificacion'], 4);   
	   }else
	   if($factivo['Nivel']=='2'){
	    $cod = substr($factivo['CodClasificacion'], 0, -2); //echo 'cod=  '.$cod;   //Cola  
	    $cod2 = substr($factivo['CodClasificacion'], 2); //echo 'cod2=  '.$cod2; /// Punta 
		$cod3 = ''; 
	   }
	  
	  
	  $CodDependencia = $factivo['CodDependencia'];
	  $MONTO_TOTAL = $MONTO_TOTAL + $factivo['MontoLocal'];
	  $MONTO = number_format($factivo['MontoLocal'],2,',','.');
	  
	  $pdf->SetFillColor(255, 255, 255); 
	  $pdf->SetFont('Arial', '', 9);
	  $pdf->SetWidths(array(16,16,16,15,30,105,30,30));
	  $pdf->SetAligns(array('C','C','C','C','C','L','R','R'));
	  $pdf->Row(array($cod, $cod2, $cod3, '1', $factivo['CodigoInterno'], utf8_decode($factivo['Descripcion']),$MONTO,$MONTO));
   }
   
   $scon03 = "select 
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
   
    $MONTO_TOTAL = number_format($MONTO_TOTAL,2,',','.');$pdf->Ln(4);
    /*$pdf->Cell(20,10,'Total de Bienes: '.$ractivo,0,0,'L'); 
	$pdf->Cell(152,10,'Total Costo=',0,0,'R'); 
	$pdf->Cell(25,10,$MONTO_TOTAL,0,1,'R'); $pdf->Ln(3);*/
	
/*$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(202, 202, 202); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(80,5,'_____________________________',0,0,'C');$pdf->Cell(100,5,'RECIBI CONFORME: _____________________________',0,1,'C');
	$pdf->Cell(80,2,$nivel.' '.$nombreCompleto,0,0,'C');    $pdf->Cell(127,2,$nivel02.' '.$nombreCompleto02,0,1,'C');
	$pdf->Cell(80,3,$cargo,0,0,'C');
	$pdf->Cell(25,3,'',0,0,'C');                             $pdf->MultiCell(80,3,$cargo02,0,'C');*/
	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(202, 202, 202); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(100,5,' ',0,1,'C');
	$pdf->Cell(100,5,' ',0,1,'C');
	$pdf->Cell(120,5,'',0,0,'C');$pdf->Cell(100,5,'RECIBI CONFORME: _____________________________',0,1,'C');
	$pdf->Cell(120,5,'',0,0,'C');    $pdf->Cell(127,5,$nivel02.' '.utf8_decode($nombreCompleto02),0,1,'C');
	$pdf->Cell(120,5,'',0,0,'C');
	$pdf->Cell(25,5,'',0,0,'C');                             $pdf->MultiCell(80,5,utf8_decode($cargo02),0,'C');
	
	
$pdf->Output();
?>  
