<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');
require('fphp.php');
connect(); 
extract ($_POST);
extract ($_GET);
  list($cod_tipodocumento, $cod_documentocompleto)=SPLIT( '[|]', $_GET['registro']);
   $detalles=$cod_documentocompleto;
$det = explode("-", $detalles);
	   $i=0;
	
	   foreach ($det as $detalle) {
		   list($siglas, $cod_documento, $periodo)=SPLIT( '[|]', $detalle);$i++;
          // echo"$cod_documento, $periodo, $secuencia";
	  }	
class PDF extends FPDF
{
var $B;
var $I;
var $U;
var $HREF;	

// VARIBALES
var $cod_documento;	
function inicio($var){
		$this->cod_documento = $var;
	}
function periodo($var){
		$this->periodo = $var;
	}
	
//Page header
function Header(){
    
	global $Periodo;
	$this->Image('../imagenes/logos/logo oficio.jpg', 15, 10, 40, 40);	
	$this->Image('../imagenes/logos/LOGOSNCF.jpg', 170, 15, 30, 30);	
	$this->Cell(190, 20, '', 0, 1, 'R'); // espacio de separacion entre el membrete y el contenido
}

//Page footer
function Footer(){
    //
    $this->SetFont('Arial','I',7);
    //
    $this->SetXY(0, 260); $this->Cell(0, 0,utf8_decode('Hacia la Transparencia, Fortalecimiento y Consolidadión del Sistema Nacional de Control Fiscal'),0,1,'C');
    $this->SetFont('Arial','',7);
    $this->SetXY(0, 263); $this->Cell(0, 0,'___________________________________________________________________________________________________________________  ',0,1,'C');
    $this->SetXY(0, 266); $this->Cell(0, 0,utf8_decode('Dirección: Calle Sucre c/c Monagas, Edificio Sede de la Contraloría del estado Monagas, Maturín. Telefono: 0291-6410441 - 6432713'),0,1,'C');
    $this->SetXY(0, 269); $this->Cell(0, 0,utf8_decode('Correo Electrónico: contraloriamonagas@contraloriamonagas.gob.ve, www.contraloriamonagas.gob.ve'),0,1,'C');
    $this->SetXY(0, 272); $this->Cell(0, 0,utf8_decode('RIF: G20001397-4'),0,1,'C');
}


function WriteHTML($html){
    // Intérprete de HTML
    $html = str_replace("\n",' ',$html);
    $a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
    foreach($a as $i=>$e)
    {
        if($i%2==0)
        {
            // Text
            if($this->HREF)
                $this->PutLink($this->HREF,$e);
            else
                $this->Write(5,$e);
        }
        else
        {
            // Etiqueta
            if($e[0]=='/')
                $this->CloseTag(strtoupper(substr($e,1)));
            else
            {
                // Extraer atributos
                $a2 = explode(' ',$e);
                $tag = strtoupper(array_shift($a2));
                $attr = array();
                foreach($a2 as $v)
                {
                    if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                        $attr[strtoupper($a3[1])] = $a3[2];
                }
                $this->OpenTag($tag,$attr);
            }
        }
    }
}

function OpenTag($tag, $attr){
    // Etiqueta de apertura
    if($tag=='B' || $tag=='I' || $tag=='U')
        $this->SetStyle($tag,true);
    if($tag=='A')
        $this->HREF = $attr['HREF'];
    if($tag=='BR')
        $this->Ln(5);
}

function CloseTag($tag){
    // Etiqueta de cierre
    if($tag=='B' || $tag=='I' || $tag=='U')
        $this->SetStyle($tag,false);
    if($tag=='A')
        $this->HREF = '';
}

function SetStyle($tag, $enable){
    // Modificar estilo y escoger la fuente correspondiente
    $this->$tag += ($enable ? 1 : -1);
    $style = '';
    foreach(array('B', 'I', 'U') as $s)
    {
        if($this->$s>0)
            $style .= $s;
    }
    $this->SetFont('',$style);
}

function PutLink($URL, $txt){
    // Escribir un hiper-enlace
    $this->SetTextColor(0,0,255);
    $this->SetStyle('U',true);
    $this->Write(5,$txt,$URL);
    $this->SetStyle('U',false);
    $this->SetTextColor(0);
}
}


/////////////////////    Texto en columnas(parametrizable), Justificado, con FPDF sin librerias extra    ////////////////////////////////////////////////////////
// Parámetros: Cadena original, Numero de columnas a imprimir la cadena, Variable del FPDF para imprimir devuelta, Número máximo de caracteres por renglón

function textIntoCols($strOriginal,$noCols,$pdf,$anchoColumna)
{
    $iAlturaRow = 4; //Altura entre renglones
    $iMaxCharRow = $anchoColumna; //Número máximo de caracteres por renglón
    $iSizeMultiCell = $iMaxCharRow / $noCols; //Tamaño ancho para la columna
    $iTotalCharMax = 9957; //Número máximo de caracteres por página
    $iCharPerCol = $iTotalCharMax / $noCols; //Caracteres por Columna
    $iCharPerCol = $iCharPerCol - 290; //Ajustamos el tamaño aproximado real del número de caracteres por columna
    $iLenghtStrOriginal = strlen($strOriginal); //Tamaño de la cadena original
    $iPosStr = 0; // Variable de la posición para la extracción de la cadena.
    // get current X and Y
    $start_x = $pdf->GetX(); //Posición Actual eje X
    $start_y = $pdf->GetY(); //Posición Actual eje Y
    $cont = 0;
    while($iLenghtStrOriginal > $iPosStr) // Mientras la posición sea menor al tamaño total de la cadena entonces imprime
    {
        $strCur = substr($strOriginal,$iPosStr,$iCharPerCol);//Obtener la cadena actual a pintar
        if($cont != 0) //Evaluamos que no sea la primera columna
        {
            // seteamos a X y Y, siendo el nuevo valor para X
            // el largo de la multicelda por el número de la columna actual,
            // más 10 que sumamos de separación entre multiceldas
            $pdf->SetXY(($iSizeMultiCell*$cont)+10,$start_y); //Calculamos donde iniciará la siguiente columna
        }
        $pdf->MultiCell($iSizeMultiCell,$iAlturaRow,$strCur); //Pintamos la multicelda actual
        $iPosStr = $iPosStr + $iCharPerCol; //Posicion actual de inicio para extracción de la cadena
        $cont++; //Para el control de las columnas
    }    
    return $pdf;
} 


//Instanciation of inherited class
$pdf=new PDF('P','mm','Letter');
$pdf->inicio($cod_documento);
$pdf->periodo($periodo);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

///***************************************************************************
   $sa = "select 
               cps.FechaDocumento,
			   cps.Contenido,
			   cpd.Cod_Organismos,
			   cpd.Cod_Dependencia,
			   cpd.Representante,
			   cps.Cod_DocumentoCompleto,
			   cpd.Cargo,
			   cps.MediaFirma,
			   cpd.FlagEsParticular
			  
		  from 
		       cp_documentoextsalida cps
			   inner join cp_documentodistribucionext cpd on (cps.Cod_Documento = cpd.Cod_Documento)
          where 
		       cps.Cod_DocumentoCompleto = '$cod_documentocompleto'";
			   
 $qa = mysql_query($sa) or die ($sa.mysql_error());
 $ra = mysql_num_rows($qa); 
 $fa = mysql_fetch_array($qa); 
 
	//// CONSULTA PARA OBTNER DATOS A MOSTRAR
	if($fa['FlagEsParticular']=='N'){
	
	 if($fa['Cod_Dependencia']==''){
	  $sb = "select 
				   Organismo, Cargo, Direccion, RepresentLegal
			  from 
				   pf_organismosexternos
			 where 
				   CodOrganismo = '".$fa['Cod_Organismos']."'";
				   
	 } else{
	 
	   $sb = "select 
				   pfo.Organismo,
				   pfd.Cargo,
				   pfd.Dependencia,
				   pfd.Direccion,
				   pfo.RepresentLegal
			  from 
				   pf_organismosexternos pfo,
				   pf_dependenciasexternas pfd
			 where 
				   pfo.CodOrganismo = '".$fa['Cod_Organismos']."' and 
				   pfd.CodDependencia = '".$fa['Cod_Dependencia']."'";
	 }
	 
	  
	  
	} else {
	  
	   $sb = "select  *  from 
				   cp_particular
			 where 
				   CodParticular = '".$fa['Cod_Organismos']."'";
	}

	$qb = mysql_query($sb) or die ($sb.mysql_error());
  
	$fb = mysql_fetch_array($qb);
   

//fecha del documento
list($a, $m, $d)=SPLIT( '[/.-]', $fa['FechaDocumento']); 

switch ($m) {
		case '01': $mes = Enero; break;  case '07': $mes = Julio; break;
		case '02': $mes = Febrero;break; case '08': $mes = Agosto; break;
		case '03': $mes = Marzo;break;   case '09': $mes = Septiembre; break;
		case '04': $mes = Abril;break;   case '10': $mes = Octubre; break;
		case '05': $mes = Mayo;break;    case '11': $mes = Noviembre; break;
		case '06': $mes = Junio;break;   case '12': $mes = Diciembre; break;
	 }

	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(190, 35, utf8_decode('Maturín, '.$d.' de '.$mes.' de '.$a), 0, 1, 'R');
//// --------------------------------------------------------------------------------- */
$pdf->SetFont('Arial', 'B', 12);
/*if ($fa['CodInterno']=='DC') // si es un oficio externo de despacho cambia las siglas DC por CEM
{$pdf->SetXY(14, 60); $pdf->Cell(3, 10, utf8_decode('CEM-').$pdf->cod_documento.'-'.$pdf->periodo, 0, 1, 'L');}
else // si no mantiene las siglas de la direccion */
$pdf->SetXY(14, 60); $pdf->Cell(3, 10, utf8_decode('CEM-').$fa['Cod_DocumentoCompleto'], 0, 1, 'L');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(55, 5, 'Ciudadano (a)', 0, 1, 'L');  
$pdf->SetFont('Arial', 'B', 12); $pdf->Cell(3, 5, $fb['RepresentLegal'] ,0, 1, 'L');
$pdf->SetFont('Arial', '', 12); textIntoCols($fb['Cargo'],1,$pdf,100); 
//$pdf->Cell(3,5, $fb['Cargo'] ,0, 1, 'L');

$pdf->SetFont('Arial', '', 12); $pdf->Cell(3,5, strtoupper($fb['Organismo']) ,0, 1, 'L');


//////////////////////      llamada de la funcion texto en columnas ////////////////////////////////////////////////////////////
textIntoCols($fb['Direccion'],1,$pdf,100); 

$pdf->Cell(3, 10, '', 0, 1, 'R'); // espacio de separacion entre el membrete y el contenido


//// ------------------------------------  datos del documento BD --------------------------------------------- */
/*
$pdf->SetFont('Arial', '', 12);
$s_cpint ="select * from cp_documentointerno where Cod_DocumentoCompleto = '$cod_documento'";
$q_cpint = mysql_query($s_cpint) or die ($s_cpint.mysql_error());
$f_cpint = mysql_fetch_array($q_cpint);

$sd = "select * from rh_puestos where CodCargo = '".$fa['Cod_CargoRemitente']."'";
$qd = mysql_query($sd) or die ($sd.mysql_error()); //echo $sa;
$fd = mysql_fetch_array($qd); */


$pdf->SetLeftMargin(10);
$pdf->SetFontSize(11);

//$pdf->WriteHTML($f_cpint['Contenido']);

$html = $fa['Contenido'];
/*$html = 'Ahora puede imprimir fácilmente texto mezclando diferentes estilos: <b>negrita</b>, <i>itálica</i>,
<u>subrayado</u>, o ¡ <b><i><u>todos a la vez</u></i></b>!<br><br>También puede incluir enlaces en el
texto, como <a href="http://www.fpdf.org">www.fpdf.org</a>, o en una imagen: pulse en el logotipo.';*/

$pdf->WriteHTML($html);

// firma al pie de ultima pagina

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 1, '',0, 1, 'C');
$pdf->Cell(0, 40, 'Atentamente,',0, 1, 'C');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 5, utf8_decode('FREDDY JOSÉ CUDJOE'), 0, 1, 'C');  
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 5, utf8_decode('CONTRALOR PROVISIONAL DEL ESTADO MONAGAS' ),0, 1, 'C');
$pdf->Cell(0, 5, utf8_decode('Designado mediante Resolución Nº. 01-00-000159 de  Fecha 18-09-2013,'), 0, 1, 'C'); 
$pdf->Cell(0, 5, utf8_decode('Emanada del Despacho de la Contralora General de la República,'), 0, 1, 'C'); 
$pdf->Cell(0, 5, utf8_decode('publicada en G.O.Nº 40.254 de fecha 19-09-2013'), 0, 1, 'C'); 

$pdf->Output();
  






/*
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
<style>
<!--
@page { size: 21.59cm 27.94cm }
-->
</style>
<script language="JavaScript"> 
function printPage() {  
if(document.all) {  
document.all.divButtons.style.visibility = 'hidden';  
window.print();  
document.all.divButtons.style.visibility = 'visible';  
} else {  
document.getElementById('divButtons').style.visibility = 'hidden';  
window.print();  
document.getElementById('divButtons').style.visibility = 'visible';  
}  
}  
</script>
</head>

<body>
<?
   list($cod_tipodocumento, $cod_documentocompleto)=SPLIT( '[|]', $_GET['registro']);
   
   $sa = "select 
               cps.FechaDocumento,
			   cps.Contenido,
			   cpd.Cod_Organismos,
			   cpd.Cod_Dependencia,
			   cpd.Representante,
			   cps.Cod_DocumentoCompleto,
			   cpd.Cargo,
			   cps.MediaFirma,
			   cpd.FlagEsParticular
			  
		  from 
		       cp_documentoextsalida cps
			   inner join cp_documentodistribucionext cpd on (cps.Cod_Documento = cpd.Cod_Documento)
          where 
		       cps.Cod_DocumentoCompleto = '$cod_documentocompleto'";
			   
 $qa = mysql_query($sa) or die ($sa.mysql_error());
 $ra = mysql_num_rows($qa); 
 $fa = mysql_fetch_array($qa); 
 
	//// CONSULTA PARA OBTNER DATOS A MOSTRAR
	if($fa['FlagEsParticular']=='N'){
	
	 if($fa['Cod_Dependencia']==''){
	  $sb = "select 
				   Organismo, Cargo, Direccion
			  from 
				   pf_organismosexternos
			 where 
				   CodOrganismo = '".$fa['Cod_Organismos']."'";
				   
	 } else{
	 
	   $sb = "select 
				   pfo.Organismo,
				   pfd.Cargo,
				   pfd.Dependencia,
				   pfd.Direccion
			  from 
				   pf_organismosexternos pfo,
				   pf_dependenciasexternas pfd
			 where 
				   pfo.CodOrganismo = '".$fa['Cod_Organismos']."' and 
				   pfd.CodDependencia = '".$fa['Cod_Dependencia']."'";
	 }
	 
	  
	  
	} else {
	  
	   $sb = "select  *  from 
				   cp_particular
			 where 
				   CodParticular = '".$fa['Cod_Organismos']."'";
	}

	$qb = mysql_query($sb) or die ($sb.mysql_error());
  
	$fb = mysql_fetch_array($qb);
  //// ------------------------------------------------------------
  list($a, $m, $d)=SPLIT( '[-]', $fa['FechaDocumento']); $f_documento= $d.'-'.$m.'-'.$a;
	 switch ($m) {
		case '01': $mes = Enero; break;  case '07': $mes = Julio; break;
		case '02': $mes = Febrero;break; case '08': $mes = Agosto; break;
		case '03': $mes = Marzo;break;   case '09': $mes = Septiembre; break;
		case '04': $mes = Abril;break;   case '10': $mes = Octubre; break;
		case '05': $mes = Mayo;break;    case '11': $mes = Noviembre; break;
		case '06': $mes = Junio;break;   case '12': $mes = Diciembre; break;
	 }
  //// ------------------------------------------------------------
  //// consulta para verficar tipo de correspondencia
	 $stcuenta = "select * from cp_tipocorrespondencia where Cod_TipoDocumento = '$cod_tipodocumento'";
	 $qtcuenta = mysql_query($stcuenta) or die ($stcuenta.mysql_error());
	 $rtcuenta = mysql_num_rows($qtcuenta); //echo $rtcuenta;
	 $ftcuenta = mysql_fetch_array($qtcuenta); 
 //// -------------------------------------------------------------
?>
<table id="Padre" name="Padre" align="center" cellpadding="0" cellspacing="0">
<tr><td>

<? 
  if($ftcuenta['DescripCorta']=='OF'){
  //// ---------------------------------- OFICIO ------------------------------
?>

<table id="principal"  align="center">
<tr><td width="707">
  <!-- CABECERA DEL DOCUMENTO -->
  <table width="637" align="center" id="cabecera" cellpadding="0" cellspacing="0">
  <tr>
   <td width="20"></td>
   <td width="102" align="center"><img src="../imagenes/logos/contraloria.jpg" style="height:60px; width:80px" /></td>
   <td width="10"></td>
   <td width="378">
   
   <table cellpadding="0" cellspacing="2">
   <tr>
      <td align="center" width="387"><font size="3"><i><?=ucwords(strtolower('REP&Uacute;BLICA BOLIVARIANA DE VENEZUELA'))?></i></font></td>
   </tr>
   <tr>
      <td align="center"><font size="3"><i><?=ucwords(strtolower('CONTRALOR&Iacute;A DEL ESTADO MONAGAS'))?></i></font></td>
   </tr>
   <tr>
      <td align="center"><font size="3"><i><?=ucwords(strtolower('DESPACHO DEL CONTRALOR'))?></i></font></td>
    </tr>
   </table>
   
   </td>
   <td width="103"><img src="imagenes/logoContraloria.jpg" style="height:80px; width:80px" /></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  </table>
</td></tr>

<tr><td>
   <!-- xxxxxxxxxxxxxxxxxxx -->
   <table width="675" id="numero_doc"> 
   <tr>
    <td width="3"></td>
    <td width="3"></td>
    <td width="4"></td>
    <td width="375"></td>
    <td width="266" align="right">Cuman&aacute;, <?=$d;?> de <?=$mes;?> de <?=$a;?></td>
   </tr>
   <tr>
    <td></td>
    <td></td>
    <td></td>
    <td width="375"></td>
    <td align="right"><!--201° y 152°--></td>
   </tr>
   </table>
</td></tr>

<tr><td>
  <!-- CUERPO 1 DEL DOCUMENTO -->
  <table id="cuerpo1" cellpadding="0" cellspacing="0">
    <tr>
      <td width="80"></td>
      <td width="351"><b>Oficio N°: <?=$fa['Cod_DocumentoCompleto']?></b></td>
    </tr>
    <tr>
      <td width="80"></td>
      <td width="351"></td>
      <td width="180"></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
    </tr>
     <tr>
      <td></td>
      <td height="15"></td>
    </tr>
    <tr>
      <td></td>
      <td>Ciudadano(a):</td>
    </tr>
    <tr>
      <td></td>
      <td><b><?=$fa['Representante']?></b></td>
    </tr>
    <tr>
      <td></td>
      <td><?=(($fa['Cargo']))?></td>
    </tr>
    <tr>
      <td></td>
      <td><?=((($fb['Direccion'])))?></td>
    </tr>
  </table>
</td></tr>

<tr><td>
  <!-- CONTENIDO DEL DOCUMENTO -->
  <table width="701">
  <tr>
    <td width="80"></td>
    <td width="504"><div style="width:600px;height:auto;"><font size="3" face="Times New Roman"><?=$fa['Contenido']?></font></div></td>
    <td width="11"></td>
  </tr>
  </table>
</td></tr>

<tr><td>
  <!-- *********************** -->
  <table align="center" id="atentamente" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center">Atentamente,</td>
  </tr>
  <tr>
    <td height="25"></td>
  </tr>
  <tr>
    <td align="center"></td>
  </tr>

	<?/* list($a, $m, $d)=SPLIT( '[-]', $fa['FechaDocumento']); $f_documento= $a.$m.$d; 
	
	if(($f_documento<'20130227') || ( $f_documento>'20130301' && $f_documento <='20130922')){ */?>
/*
  <tr>
    <td align="center">Lcdo. FREDDY CUDJOE</td>
  </tr>
  <tr>
    <td align="center">CONTRALOR (I) DEL ESTADO MONAGAS</td>
  </tr>
  <tr>
    <td align="center"><font size="1">Designado mediante Resolución Nº. 01-00-000159 de  Fecha 18-09-2013,</font></td>
  </tr>
  <tr>
    <td align="center"><font size="1">Emanada del Despacho de la Contralora General de la República,</font></td>
  </tr>
  <tr>
    <td align="center"><font size="1">publicada en G.O.Nº 405.296 de fecha 19-09-2013</font></td>
  </tr>
  

<? /*} else if($f_documento>='20130923'){?>

<tr>
    <td align="center">Lcdo. ANDY DAVID VÁSQUEZ MACHACÓN </td>
  </tr>
  <tr>
    <td align="center">CONTRALOR PROVISIONAL DEL ESTADO SUCRE</td>
  </tr>
   <tr>
    <td align="center"><font size="1">Resolución Nº 01-00-000158 de la Contralor&iacute;a General de la República  del 18-09-2013</font></td>
  </tr>
 <!-- <tr>
    <td align="center"><font size="1">Emanada del Despacho de la ,</font></td>
  </tr>-->
  <tr>
    <td align="center"><font size="1">Gaceta Oficial de la Rep&uacute;blica Bolivariana de Venezuela Nº 40.254 del 19-09-2013</font></td>
  </tr>
  
<? } ?>
  </table>
</td></tr>

<tr><td>
  <!-- *********************** -->
  <table id="pie_pagina">
  <tr>
     <td width="63"></td>
     <td width="17"><font size="1"><?=$fa['MediaFirma']?></font></td>
     <td width="450"></td>
     <td width="134" align="center"></td>
  </tr>
  <tr>
    <td height="15"></td>
  </tr>
  </table>
</td></tr>

<tr><td>
  <!-- *********************** -->
  <table id="pie_pagina2">
  <tr>
     <td width="63"></td>
     <td width="17"></td>
     <td width="504">
       <!--<table cellpadding="0" cellspacing="0">
       <tr>
         <td width="55"></td>
         <td width="436" align="center"><font size="1">Hacia la Consolidación y el Fortalecimiento del Sistema Nacional de Control Fiscal</font></td>
       </tr>
        <tr>
        <td></td>
          <td align="center"><font size="1">Calle Centurion - Quinta Paola N°36 / Tel&eacute;fono (0287) 7211344 - Fax(0287) 7233655</font></td>
       </tr>
       <tr>
         <td></td>
          <td align="center"><font size="1">Correo Electr&oacute;nico: contaloriada@cantv.net. Tucupita, Estado Delta Amacuro.</font></td>
       </tr>
       </table>-->
     </td>
     <td width="99" align="center">&nbsp;</td>
  </tr>
  </table>
</td></tr>
</table>

<? 
  }else{
  //// ------------------------------- OFICIO CIRCULAR -------------------------
   if($ftcuenta['DescripCorta']=='OC'){
?>

<table id="principal"  align="center">
<tr><td width="707">
  <!-- CABECERA DEL DOCUMENTO -->
  <table width="637" align="center" id="cabecera" cellpadding="0" cellspacing="0">
  <tr>
   <td width="20"></td>
   <td width="102" align="center"><img src="../imagenes/logos/contraloria.jpg" width="107" height="63" style="height:60px; width:80px" /></td>
   <td width="10"></td>
   <td width="378">
   
   <table cellpadding="0" cellspacing="2">
   <tr>
      <td align="center" width="387"><font size="3"><i><?=ucwords(strtolower('REP&Uacute;BLICA BOLIVARIANA DE VENEZUELA'))?></i></font></td>
   </tr>
   <tr>
      <td align="center"><font size="3"><i><?=ucwords(strtolower('CONTRALOR&Iacute;A DEL ESTADO MONAGAS'))?></i></font></td>
   </tr>
   <tr>
      <td align="center"><font size="3"><i><?=ucwords(strtolower('DESPACHO DEL CONTRALOR'))?></i></font></td>
    </tr>
   </table>
   
   </td>
   <td width="103"><img src="imagenes/logoContraloria.jpg" style="height:80px; width:80px" /></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  </table>
</td></tr>

<tr><td>
   <!-- xxxxxxxxxxxxxxxxxxx -->
   <table width="675" id="numero_doc"> 
  <tr>
    <td width="3"></td>
    <td width="3"></td>
    <td width="4"></td>
    <td width="376"></td>
    <td width="265" align="right">Cuman&aacute;, <?=$d?> de <?=$mes?> de <?=$a?></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td width="376"></td>
    <td align="right"><!--201° y 152°--></td>
  </tr>
  </table>
</td></tr>

<tr><td>
  <!-- CUERPO 1 DEL DOCUMENTO -->
  <table id="cuerpo1" cellpadding="0" cellspacing="0">
    <tr>
      <td width="80"></td>
      <td width="351"><b>Oficio Circular N°: <?=$fa['Cod_DocumentoCompleto']?></b></td>
    </tr>
    <tr>
      <td width="80"></td>
      <td width="351"></td>
      <td width="180"></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
    </tr>
     <tr>
      <td></td>
      <td height="15"></td>
    </tr>
    <tr>
      <td></td>
      <td>Ciudadano(a):</td>
    </tr>
    <tr>
      <td></td>
      <td><b><?=$fa['Representante']?></b></td>
    </tr>
    <tr>
      <td></td>
      <td><?=ucwords(strtolower($fa['Cargo']))?></td>
    </tr>
    <tr>
      <td></td>
      <td><?=ucwords(strtolower($fb['Direccion']))?></td>
    </tr>
  </table>
</td></tr>

<tr><td>
  <!-- CONTENIDO DEL DOCUMENTO -->
  <table width="701">
  <tr>
    <td width="80"></td>
    <td width="504"><div style="width:600px;height:360px"><font size="3" face="Times New Roman"><?=$fa['Contenido']?></font></div></td>
    <td width="11"></td>
  </tr>
  </table>
</td></tr>

<tr><td>
  <!-- *********************** -->
  <table align="center" id="atentamente" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center">Atentamente,</td>
  </tr>
  <tr>
    <td height="25"></td>
  </tr>
  <tr>
    <td align="center"></td>
  </tr>
<?/* list($a, $m, $d)=SPLIT( '[-]', $fa['FechaDocumento']); $f_documento= $a.$m.$d; 
	if($f_documento<'20130227' || $f_documento>'20130301'){ */?>
/*
  <tr>
    <td align="center">Lcdo. FREDDY CUDJOE</td>
  </tr>
  <tr>
    <td align="center">CONTRALOR PROVISIONAL DEL ESTADO SUCRE</td>
  </tr>
  <tr>
   <td align="center"><font size="1">Designado mediante Resolución Nº. 01-00-000159 de  Fecha 18-09-2013,</font></td>
  </tr>
  <tr>
    <td align="center"><font size="1">Emanada del Despacho de la Contralora General de la República,</font></td>
  </tr>
  <tr>
    <td align="center"><font size="1">publicada en G.O.Nº 405.296 de fecha 19-09-2013</font></td>
  </tr>
  

<? /*} else if($f_documento>='20130923'){?>

<tr>
    <td align="center">Lcdo. ANDY DAVID VÁSQUEZ MACHACÓN </td>
  </tr>
  <tr>
    <td align="center">CONTRALOR PROVISIONAL DEL ESTADO SUCRE</td>
  </tr>
   <tr>
    <td align="center"><font size="1">Resolución Nº 01-00-000158 de la Contralor&iacute;a General de la República  del 18-09-2013</font></td>
  </tr>
 <!-- <tr>
    <td align="center"><font size="1">Emanada del Despacho de la ,</font></td>
  </tr>-->
  <tr>
    <td align="center"><font size="1">Gaceta Oficial de la Rep&uacute;blica Bolivariana de Venezuela Nº 40.254 del 19-09-2013</font></td>
  </tr>
  
<? } ?>


  </table>
</td></tr>

<tr><td>
  <!-- *********************** -->
  <table id="pie_pagina">
  <tr>
     <td width="63"></td>
     <td width="17"><font size="1"><?=$fa['MediaFirma']?></font></td>
     <td width="450"></td>
     <td width="134" align="center"></td>
  </tr>
  <tr>
    <td height="15"></td>
  </tr>
  </table>
  <!-- *********************** -->
</td></tr>

<tr><td>
  <!-- *********************** -->
  <table id="pie_pagina2">
  <tr>
     <td width="63"></td>
     <td width="17"></td>
     <td width="504">
       <!--<table cellpadding="0" cellspacing="0">
       <tr>
         <td width="55"></td>
         <td width="436" align="center"><font size="1">Hacia la Consolidación y el Fortalecimiento del Sistema Nacional de Control Fiscal</font></td>
       </tr>
        <tr>
        <td></td>
          <td align="center"><font size="1">Calle Centurion - Quinta Paola N°36 / Tel&eacute;fono (0287) 7211344 - Fax(0287) 7233655</font></td>
       </tr>
       <tr>
         <td></td>
          <td align="center"><font size="1">Correo Electr&oacute;nico: contaloriada@cantv.net. Tucupita, Estado Delta Amacuro.</font></td>
       </tr>
       </table>-->
     </td>
     <td width="99" align="center">&nbsp;</td>
  </tr>
  </table>
</td></tr>
</table>


<?
 } }
?>

</td></tr>
<div id="divButtons" name="divButtons">  
<input type="button" id="imprimir" name="imprimir" value = "Imprimir" onclick="printPage()"/> 
</div>
</table>


</body>
</html>
*/
?>
