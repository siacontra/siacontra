<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');
require('fphp.php');
connect(); 
extract ($_POST);
extract ($_GET);

class PDF extends FPDF{

function Header(){
    $sql = "select 
					a.MovimientoNumero,
					a.Activo,
					b.FechaAprobacion,
					b.Comentario					 
			  from 
			       af_movimientosdetalle a 
				   inner join af_movimientos b on (a.MovimientoNumero=b.MovimientoNumero)and(a.Organismo=b.Organismo) 
		     where 
			       a.Activo='".$_GET['Activo']."' and 
				   a.Organismo='".$_GET['Organismo']."'"; //echo $sql;
    $qry = mysql_query($sql) or die ($sql.mysql_error());
    $field = mysql_fetch_array($qry);
	
	list($sano, $smes, $sdia) = split('[-]', $field['FechaAprobacion']);
	
	$this->Image('../imagenes/logos/contraloria.jpg', 20, 10, 10, 10);	
	$this->SetFont('Arial', 'B', 8);
	$this->SetXY(30, 10); $this->Cell(100, 5,utf8_decode( 'República Bolivariana de Venezuela'), 0, 1, 'L');
	$this->SetXY(30, 15); $this->Cell(100, 5,utf8_decode('Contraloría del Estado Monagas'), 0, 1, 'L');
	$this->SetXY(20, 10); $this->Cell(155, 5, 'Fecha:', 0, 0, 'R');$this->Cell(10,5,date("d-m-Y"),0,1,'');
	
	$this->SetXY(155, 15); $this->Cell(20, 4, utf8_decode('Pág.:'), 0, 1, 'R'); /// NRO DE PÁGINA
	
	$this->SetXY(20, 23); $this->Cell(20, 4, utf8_decode('Movimiento#:'), 0, 0, 'L');/// NRO DE DOCUMENTO
						  $this->Cell(10, 4, $field['MovimientoNumero'].'-'.$sano, 0, 1, 'L');
	
   $this->SetFont('Arial', 'B', 10);
   $this->SetXY(75, 27); $this->Cell(70, 5, utf8_decode('TRANSFERENCIA DE ACTIVO FIJO'), 0, 1, 'C');
   $this->Ln(10);
	
     $this->Ln();

    $this->SetFont('Arial','B','8');
	$this->SetXY(20,35); $this->Cell(60, 5, utf8_decode('Items a ser transferidos: '), 0, 0, 'L');
					     $this->Cell(20, 5,'', 0, 0, 'L');
						 
						 $this->Cell(20, 5,utf8_decode('Comentario: '), 0, 0, 'L');
						 $this->SetFont('Arial','','8');
						 $this->Cell(20, 5,utf8_decode($field['Comentario']), 0, 1, 'L');
						 
						 
	$this->Rect(21,39,168,'');					 
	$this->Rect(21,43,168,'');
	
	$this->Rect(21,105,168,'');
	
	//$this->SetFont('Arial', 'B', '6');
	$this->SetXY(20, 40); $this->Cell(20, 3, utf8_decode('#'), 0, 0, 'C');
						 $this->Cell(15, 3, 'Activo', 0, 0, 'C');
						 $this->Cell(100, 3, utf8_decode('Descripción'), 0, 0, 'C');
						 $this->Cell(20, 3, utf8_decode('#Serie'),0, 1, 'C'); //$pdf->Ln();
}
//Page footer
function Footer(){
    //Position at 1.5 cm from bottom
    $this->SetXY(153,13);
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
				a.*,
				c.NomCompleto,
				d.Descripcion as DescpActivo,
				b.AprobadoPor
			from 
				af_movimientosdetalle a
				inner join af_movimientos b on (a.MovimientoNumero=b.MovimientoNumero)and(a.Organismo=b.Organismo)
				inner join mastpersonas c on (c.CodPersona=b.AprobadoPor)
				inner join af_activo d on (d.Activo=a.Activo)and(d.CodOrganismo=a.Organismo)
		    where 
			    a.Activo='".$_GET['Activo']."' and 
				b.Estado='AP' and 
				a.Organismo = '".$_GET['Organismo']."'"; 
$qcon = mysql_query($scon) or die ($scon.mysql_error());
$rcon = mysql_num_rows($qcon);

if($rcon!=0)$fcon=mysql_fetch_array($qcon);

//// -------------   CENTRO COSTOS
$CentroCosto[0]= $fcon['CentroCosto']; 
$CentroCosto[1]= $fcon['CentroCostoAnterior'];
$C_Costo = 2;
  	 for($i=0; $i<$C_Costo; $i++){
     $scc = "select Descripcion from ac_mastcentrocosto where CodCentroCosto='$CentroCosto[$i]'";
	 $qcc = mysql_query($scc) or die ($scc.mysql_error());
	 $fcc = mysql_fetch_array($qcc);
	 
	 if($i==0)$cc_actual = $fcc['Descripcion'];	 
     if($i==1)$cc_anterior = $fcc['Descripcion'];	
  }
//// ------------    UBICACION
$ubicacion[0]= $fcon['Ubicacion'];
$ubicacion[1]= $fcon['UbicacionAnterior'];
$v_ubicacion = 2;
	for($x=0; $x<$v_ubicacion; $x++){
	 $su = "select Descripcion from af_ubicaciones where CodUbicacion ='$ubicacion[$x]'";
	 $qu = mysql_query($su) or die ($su.mysql_error());
	 $fu = mysql_fetch_array($qu);
	
	 if($x==0)$ubicacion_actual=$fu['Descripcion'];
	 if($x==1)$ubicacion_anterior=$fu['Descripcion'];
	}
//// ------------    DEPENDENCIA
$dependencia['0']= $fcon['Dependencia'];
$dependencia['1']= $fcon['DependenciaAnterior'];
$v_dependencia = 2;
	for($y=0; $y<$v_dependencia; $y++){
	 $sd = "select Dependencia from mastdependencias where CodDependencia ='$dependencia[$y]'";
	 $qd = mysql_query($sd) or die ($sd.mysql_error());
	 $fd = mysql_fetch_array($qd);
	
	 if($y==0)$dependencia_actual=$fd['Dependencia'];
	 if($y==1)$dependencia_anterior=$fd['Dependencia'];
	}
//// ------------    EMPLEADO USUARIO y EMPLEADO RESPONSABLE
$empleado['0']= $fcon['EmpleadoUsuario'];
$empleado['1']= $fcon['EmpleadoUsuarioAnterior'];
$empleado['2']= $fcon['EmpleadoResponsable'];
$empleado['3']= $fcon['EmpleadoResponsableAnterior'];
$empleado['4']= $fcon['AprobadoPor'];

$v_emp = 5;
	for($z=0; $z<$v_emp; $z++){
	 $se = "select NomCompleto from mastpersonas where CodPersona ='$empleado[$z]'";
	 $qe = mysql_query($se) or die ($se.mysql_error());
	 $fe = mysql_fetch_array($qe);
	
	 if($z==0)$empleado_usuario=$fe['NomCompleto'];
	 if($z==1)$empleado_usuario_anterior=$fe['NomCompleto'];
	 if($z==2)$empleado_responsable=$fe['NomCompleto'];
	 if($z==3)$empleado_responsable_anterior=$fe['NomCompleto'];
	 if($z==4)$AprobadoPor=$fe['NomCompleto'];
	}


$pdf->SetFont('Arial', 'B', 8);
	 $pdf->SetXY(21, 43); $pdf->Cell(20, 5,$fcon['MovimientoNumero'].'  '.$fcon['Activo'], 0, 0, 'L');
	 $pdf->SetXY(70, 43); $pdf->Cell(20, 5,$fcon['DescpActivo'], 0, 1, 'L');
     
	 $pdf->SetXY(20, 49); $pdf->Cell(80, 5, 'Anterior', 0, 0 , 'C');
	 					  $pdf->Cell(80, 5, 'Actual', 0, 1, 'C');
$pdf->SetFont('Arial', '', 6);
     $pdf->SetXY(20, 52); $pdf->Cell(15, 5, 'C.Costos:', 0, 0, 'L');
	 					  $pdf->Cell(15, 5, $fcon['CentroCostoAnterior'], 0, 0, 'L');
						  $pdf->Cell(65, 5, $cc_anterior, 0, 0, 'L');
						  $pdf->Cell(12, 5, $fcon['CentroCosto'], 0, 0, 'L');
						  $pdf->Cell(10, 5, $cc_actual, 0, 1, 'L');
	 
	 $pdf->SetXY(20, 56); $pdf->Cell(15, 5, utf8_decode('Ubicación:'), 0, 0, 'L'); 
	 					  $pdf->Cell(15, 5, $fcon['UbicacionAnterior'], 0, 0, 'L');
						  $pdf->Cell(65, 5, $ubicacion_anterior, 0, 0, 'L');
						  $pdf->Cell(12, 5, $fcon['Ubicacion'], 0, 0, 'L');
						  $pdf->Cell(10, 5, $ubicacion_actual, 0, 1, 'L');
	 	 
	 $pdf->SetXY(20, 60); $pdf->Cell(15, 5, utf8_decode('Dependencia:'), 0, 0, 'L'); 
	 					  $pdf->Cell(15, 5, $fcon['DependenciaAnterior'], 0, 0, 'L');
						  $pdf->Cell(65, 5, $dependencia_anterior, 0, 0, 'L'); 
						  $pdf->Cell(12, 5, $fcon['Dependencia'], 0, 0, 'L'); 
						  $pdf->Cell(10, 5, $dependencia_actual, 0, 1, 'L'); 
	 
	 $pdf->SetXY(20, 64); $pdf->Cell(19, 5, utf8_decode('Emp. Usuario:'), 0, 0, 'L');
	 					  $pdf->Cell(11, 5, $fcon['EmpleadoUsuarioAnterior'], 0, 0, 'L');
						  $pdf->Cell(67, 5, $empleado_usuario_anterior, 0, 0, 'L');
						  $pdf->Cell(10, 5, $fcon['EmpleadoUsuario'], 0, 0, 'L');
						  $pdf->Cell(10, 5, $empleado_usuario, 0, 1, 'L'); 
						      
	 
	 $pdf->SetXY(20, 68); $pdf->Cell(19, 5, utf8_decode('Emp. Responsable:'), 0, 0, 'L'); 
	 					  $pdf->Cell(11, 5, $fcon['EmpleadoResponsableAnterior'], 0, 0, 'L');
						  $pdf->Cell(67, 5, $empleado_responsable_anterior, 0, 0, 'L'); 
						  $pdf->Cell(10, 5, $fcon['EmpleadoResponsable'], 0, 0, 'L');
						  $pdf->Cell(10, 5, $empleado_responsable, 0, 1, 'L');
	 
	 
	 $pdf->SetFont('Arial', 'B', 8);
	 $pdf->SetXY(20, 74); $pdf->Cell(10, 5, utf8_decode('ACTIVOS RELACIONADOS : '), 0, 1, 'L'); 


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
     list($nombreCompleto03, $cargo03, $nivel03) = getfirma($fcon['AprobadoPor']);
	 list($nombreEmpleadoResponsable, $cargoEmpleadoResponsable, $nivelEmpleadoResponsable) = getfirma($fcon['EmpleadoResponsable']);
   
$pdf->Rect(40,120,50,''); $pdf->Rect(120,120,50,'');
$pdf->SetXY(28,122);$pdf->Cell(70, 5,$nivel03.' '.$nombreCompleto03,0,0,'C');
$pdf->SetXY(28,125);$pdf->Cell(70, 5,$cargo03,0,0,'C');
$pdf->SetXY(110,122);$pdf->Cell(70, 5,$nivelEmpleadoResponsable.' '.$nombreEmpleadoResponsable,0,0,'C');
$pdf->SetXY(110,125);$pdf->Cell(70, 5,$cargoEmpleadoResponsable,0,0,'C');


 list($anos, $meses, $dias, $hora) = split('[-, ]', $fcon['UltimaFechaModif']);
 //echo $anos, $meses, $dias, $hora;
 
 list($ano, $mes, $dia) = split('[-]', $fcon['FechaRevisadoPor']);
 $fecha = $dia.'-'.$mes.'-'.$ano; //echo $mes;
 
 switch($mes){
		case "01": $fmes= Enero;break;  
		case "02": $fmes= Febrero;break; 
		case "03": $fmes= Marzo;break;   
		case "04": $fmes= Abril;break;   
		case "05": $fmes= Mayo;break;    
		case "06": $fmes= Junio;break;
		case "07": $fmes= Julio; break;
		case "08": $fmes= Agosto; break;
		case "09": $fmes= Septiembre; break;
		case "10": $fmes= Octubre; break;
		case "11": $fmes= Noviembre; break;
		case "12": $fmes= Diciembre; break;
    }

$montoLocal = number_format($fcon['MontoLocal'],2,',','.');
  
/// Consulta realizada para obtener el cargo actual del empleado Usuario
$scon03 = "select 
				  a.CodPersona,
				  b.DescripCargo 
			 from 
				  rh_empleadonivelacion a 
				  inner join rh_puestos b on (a.CodCargo=b.CodCargo)
 			where 
				  a.Secuencia=(select max(Secuencia) from rh_empleadonivelacion where CodPersona='".$fcon['EmpleadoUsuario']."') and 
				  a.CodPersona='".$fcon['EmpleadoUsuario']."'"; //echo $scon03;
$qcon03 = mysql_query($scon03) or die ($scon03.mysql_error());
$fcon03 = mysql_fetch_array($qcon03);

/// Se obtienen la descripcion de cargos de los firmanmtes
$cargo[0]= $fcon['CargoRevisadoPor']; 
$cargo[1]= $fcon['CargoConformadoPor'];
$cargo[2]= $fcon['CargoAprobadoPor'];
$v_cargo = 3;

for($x=0; $x<$v_cargo; $x++){
 $scargos = "select DescripCargo from rh_puestos where CodCargo ='$cargo[$x]'"; //echo $scargos, $x;
 $qcargos = mysql_query($scargos) or die ($scargos.mysql_error());
 $fcargos = mysql_fetch_array($qcargos);

 if($x==0)$d_cargoRevisadoPor=$fcargos['DescripCargo'];
 if($x==1)$d_cargoConformadoPor=$fcargos['DescripCargo'];
 if($x==2)$d_cargoAprobadoPor=$fcargos['DescripCargo']; 
}

/// Se obtienen los nombres de los firmantes + número de cédula
$cedula[0]=$fcon['RevisadoPor'];
$cedula[1]=$fcon['ConformadoPor'];
$cedula[2]=$fcon['AprobadoPor'];
$v_cedula = 3;

for($y=0; $y<$v_cedula; $y++){
  $scn = "select NomCompleto,Ndocumento  from mastpersonas where CodPersona='$cedula[$y]'";
  $qcn = mysql_query($scn) or die ($scn.mysql_error());
  $fcn = mysql_fetch_array($qcn);
  
  if($y==0){$n_RevisadoPor=$fcn['NomCompleto']; $c_RevisadoPor=$fcn['Ndocumento'];}
  if($y==1){$n_ConformadoPor=$fcn['NomCompleto'];$c_ConformadoPor=$fcn['Ndocumento'];}
  if($y==2){$n_AprobadoPor=$fcn['NomCompleto']; $c_AprobadoPor=$fcn['Ndocumento'];}
}



//$parrafo1 = utf8_decode("En el día de hoy, ").$dia.(" del mes de ").$fmes.utf8_decode(" del año ").$ano.(", siendo las ").$hora.utf8_decode(" reunidos en las instalaciones donde funciona la Contraloría del Estado Monagas, ubicada en la Calle Centurión, Quinta Paola N° 36, Municipio Tucupita, Estado Sucre, los ciudadanos: ").$n_AprobadoPor.utf8_decode(", titular de la cédula de identidad N° ").$c_AprobadoPor.(" ").$d_cargoRevisadoPor.(", ").$n_ConformadoPor.utf8_decode(", titular de la cédula de identidad N° ").$c_ConformadoPor.(", ").$d_cargoConformadoPor.(" y ").$n_RevisadoPor.utf8_decode(", titular de la cédula de identidad N° ").$c_RevisadoPor.(", ").$d_cargoRevisadoPor.(", ").$fcon['NomCompletoUsuario'].utf8_decode(", titular de la cédula de identidad N° ").$fcon['NDocumentoUsuario'].utf8_decode(" quien desempeña el cargo de ").$fcon03['DescripCargo'].utf8_decode(", con el único objeto de hacerle entrega al último de los mencionados como Responsable Patrimonial Primario; en calidad de uso, guarda y custodia del bien mueble que a continuación se especifica:");

//$parrafo2 = utf8_decode("Los mismos son propiedad de este ente de Control Fiscal, tal como se desprende del registro de Dienes e Inventarios llevado ante esta Contraloría, el mismo quedará bajo su responsabilidad absoluta, siendo éste responsable de vigilar, conservar y salvaguardar, los bienes muebles entregados mediante la presente Acta; queda entendido que cualquier daño material que pueda ocurrirle a los referidos bienes muebles; con ocasión de negligencia u omisión en su uso; queda sujeta a las sanciones administrativas previstas en articulo 91 numeral 2 de la Ley Orgánica de la Contraloría General de la República y del Sistema Nacional de Control Fiscal,Disciplinarias prevista en el artículo 33 numeral 7 de la Ley del Estatuto de Función Pública y Penal prevista en el artículo 53 de la Ley Contra la Corrupcion, salvo aquellos daños naturales u hechos fortuitos que se presenten, lo cual deberá ser notificado por escrito ante la división  de Servicios Generales de ésta Contraloría. Es todo, terminó se leyó y conformes firman.");

$pdf->SetFont('Arial', '', 12);
		$pdf->SetXY(20,50);
		$pdf->MultiCell(175, 6, $parrafo1, 0, 'J');
		$pdf->Ln(6);

/*$pdf->SetFont('Arial', '', 7);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(12,'','','');
	$pdf->Cell(20, 3, 'CLASIFICACION', 1, 0, 'C', 1);
	$pdf->Cell(14, 3, 'CANTIDAD', 1, 0, 'C', 1);
	$pdf->Cell(22, 3, utf8_decode('N°IDENTIFICACION'), 1, 0, 'C', 1);
	$pdf->Cell(50, 3, 'DESCRIPCION', 1, 0, 'C', 1);
	$pdf->Cell(35, 3, 'MARCA', 1, 0, 'C', 1);
	$pdf->Cell(30, 3, 'MODELO', 1, 1, 'C', 1); //$pdf->Ln();

$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
$pdf->Cell(12,'','','');
	 $pdf->SetFont('Arial', '', 6.5);
	 $pdf->SetWidths(array(20,14,22,50,35,30));
	 $pdf->SetAligns(array('C','C','C','L','L','R'));
	 $pdf->Row(array($fcon['ClasificacionPublic20'],'1',$fcon['Activo'],$fcon['DescripActivo'],$fcon['Marca'],$fcon['Modelo']));
		
$valor = 4;
  for($i=0; $i<$valor; $i++){ 
	 $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	 $pdf->Cell(12,'','','');
	 $pdf->SetFont('Arial', '', 6.5);
	 $pdf->SetWidths(array(20,14,22,50,35,30));
	 $pdf->SetAligns(array('C','L','R','R','R','R'));
	 $pdf->Row(array('','','','','',''));
  }
 
	    $pdf->SetFont('Arial', '', 12);
		$pdf->SetXY(20,150);
		$pdf->MultiCell(175, 6, $parrafo2, 0, 'J');
	 
	 $pdf->Rect(35,230,50,'');
	 $pdf->Rect(116,230,50,'');
	 $pdf->Rect(35,250,50,'');
	 $pdf->Rect(116,250,50,'');
	 
	 	 
	 //// ------ QUIEN APRUEBA
	 $pdf->SetFont('Arial', 'B', 8);
	 $pdf->SetXY(35, 230); $pdf->Cell(60, 5,$nivel03.'. '.$nombreCompleto03, 0, 1, 'C');
	 $pdf->SetXY(32, 233); $pdf->Cell(60, 5,$cargo03, 0, 1, 'C');
	 
	 //// ------ QUIEN CONFORMA
	 $pdf->SetFont('Arial', 'B', 8);
	 $pdf->SetXY(122, 230); $pdf->Cell(40, 5,$nivel02.'. '.$nombreCompleto02, 0, 1, 'C');
	 $pdf->SetXY(123, 233); $pdf->Cell(40, 5,$cargo02, 0, 1, 'C');
	 
	 //// ------ QUIEN REVISA
	 $pdf->SetFont('Arial', 'B', 8);
	 $pdf->SetXY(40, 250); $pdf->Cell(40, 5,$nivel.'. '.$nombreCompleto, 0, 1, 'C');
	 $pdf->SetXY(40, 253); $pdf->Cell(40, 5,$cargo03, 0, 1, 'C');
	 
	 //// ------ QUIEN RECIBE
	 $pdf->SetFont('Arial', 'B', 8);
	 $pdf->SetXY(121, 250); $pdf->Cell(40, 5,$nivel04.'. '.$nombreCompleto04, 0, 1, 'C');
	 $pdf->SetXY(123, 253); $pdf->Cell(40, 5,$cargo04, 0, 1, 'C');
	 
	/*$pdf->SetWidths(array(25,100,35,35));
	  $pdf->SetAligns(array('C','R','R','R'));
	  $pdf->Row(array('' ,'Total:',$montoTotal,$montoTotal));*/
	//$pdf->Cell(175, 10, 'Total = ', 0, 0, 'R');
	//$pdf->Cell(28, 10, $montoTotal, 0, 0, 'L');	
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
