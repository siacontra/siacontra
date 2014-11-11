<?php
/*
define('FPDF_FONTPATH','font/');
require('fpdf.php');
require('fphp.php');
connect(); 

//function hex2dec
//returns an associative array (keys: R, G, B) from
//a hex html code (e.g. #3FE5AA)
function hex2dec($couleur = "#000000"){
    $R = substr($couleur, 1, 2);
    $rouge = hexdec($R);
    $V = substr($couleur, 3, 2);
    $vert = hexdec($V);
    $B = substr($couleur, 5, 2);
    $bleu = hexdec($B);
    $tbl_couleur = array();
    $tbl_couleur['R']=$rouge;
    $tbl_couleur['G']=$vert;
    $tbl_couleur['B']=$bleu;
    return $tbl_couleur;
}

//conversion pixel -> millimeter in 72 dpi
function px2mm($px){
    return $px*25.4/72;
}

function txtentities($html){
    $trans = get_html_translation_table(HTML_ENTITIES);
    $trans = array_flip($trans);
    return strtr($html, $trans);
}

extract ($_POST);
extract ($_GET);
$det = explode(";", $detalles);
	   $i=0;
	
	   foreach ($det as $detalle) {
		   list($cod_documento, $periodo, $secuencia)=SPLIT( '[|]', $detalle);$i++;
          // echo"$cod_documento, $periodo, $secuencia";
	  }	
class PDF extends FPDF
{

//variables of html parser
var $B;
var $I;
var $U;
var $HREF;
var $fontList;
var $issetfont;
var $issetcolor;



function PDF($orientation='P', $unit='mm', $format='Letter')
{
    //Call parent constructor
    $this->FPDF($orientation, $unit, $format);
    //Initialization
    $this->B=0;
    $this->I=0;
    $this->U=0;
    $this->HREF='';

    $this->tableborder=0;
    $this->tdbegin=false;
    $this->tdwidth=0;
    $this->tdheight=0;
    $this->tdalign="L";
    $this->tdbgcolor=false;

    $this->oldx=10;
    $this->oldy=0;

    $this->fontlist=array("arial", "times", "courier", "helvetica", "symbol");
    $this->issetfont=false;
    $this->issetcolor=false;
}
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

//////////////////////////////////////
//html parser

function WriteHTML($html)
{
	
	
    $html=strip_tags($html, "<b><u><i><a><img><p>
<strong><em><font><tr><blockquote><hr><td><tr><table><sup>"); //remove all unsupported tags
    $html=str_replace("\n", '', $html); //replace carriage returns by spaces
    $html=str_replace("\t", '', $html); //replace carriage returns by spaces
    $a=preg_split('/<(.*)>/U', $html, -1, PREG_SPLIT_DELIM_CAPTURE); //explodes the string
    
  
    foreach($a as $i=>$e)
    {
		
        if($i%2==0)
        {
			
            //Text
            if($this->HREF)
                $this->PutLink($this->HREF, $e);
                
                
            elseif($this->tdbegin) {
				
				
                if(trim($e)!='' and $e!=" ") {
					   
                    $this->Cell($this->tdwidth, $this->tdheight, $e, $this->tableborder, '', $this->tdalign, $this->tdbgcolor); 
                    
                }
                elseif($e==" ") {
                    $this->Cell($this->tdwidth, $this->tdheight, '', $this->tableborder, '', $this->tdalign, $this->tdbgcolor);
                }
            }
            else
                $this->Write(5, stripslashes(txtentities($e)));
        }
        else
        {
            //Tag
            if($e{0}=='/')
                $this->CloseTag(strtoupper(substr($e, 1)));
            else
            {
                //Extract attributes
                $a2=explode(' ', $e);
                $tag=strtoupper(array_shift($a2));
                $attr=array();
                foreach($a2 as $v)
                    if(ereg('^([^=]*)=["\']?([^"\']*)["\']?$', $v, $a3))
                        $attr[strtoupper($a3[1])]=$a3[2];
                $this->OpenTag($tag, $attr);
            }
        }
    }
}

function OpenTag($tag, $attr)
{
    //Opening tag
    switch($tag){

        case 'SUP':
            if($attr['SUP'] != '') {    
                //Set current font to: Bold, 6pt     
                $this->SetFont('', '', 6);
                //Start 125cm plus width of cell to the right of left margin         
                //Superscript "1"
                $this->Cell(2, 2, $attr['SUP'], 0, 0, 'J');
            }
            break;

        case 'TABLE': // TABLE-BEGIN
           echo $this->Ln(5);
            if( $attr['BORDER'] != '' ) $this->tableborder=$attr['BORDER'];
            else $this->tableborder=0;
            break;
        case 'TR': //TR-BEGIN
            break;
        case 'TD': // TD-BEGIN
            if( $attr['WIDTH'] != '' ) $this->tdwidth=($attr['WIDTH']/4);
            else $this->tdwidth=40; // SET to your own width if you need bigger fixed cells
            if( $attr['HEIGHT'] != '') $this->tdheight=($attr['HEIGHT']/6);
            else $this->tdheight=6; // SET to your own height if you need bigger fixed cells
            if( $attr['ALIGN'] != '' ) {
                $align=$attr['ALIGN'];        
                if($align=="LEFT") $this->tdalign="L";
                if($align=="CENTER") $this->tdalign="C";
                if($align=="RIGHT") $this->tdalign="R";
                if($align=="JUSTIFY") $this->tdalign="J";
            }
            else $this->tdalign="L"; // SET to your own
            if( $attr['BGCOLOR'] != '' ) {
                $coul=hex2dec($attr['BGCOLOR']);
                    $this->SetFillColor($coul['R'], $coul['G'], $coul['B']);
                    $this->tdbgcolor=true;
                }
            $this->tdbegin=true;
            break;

        case 'HR':
            if( $attr['WIDTH'] != '' )
                $Width = $attr['WIDTH'];
            else
                $Width = $this->w - $this->lMargin-$this->rMargin;
            $x = $this->GetX();
            $y = $this->GetY();
            $this->SetLineWidth(0.2);
            $this->Line($x, $y, $x+$Width, $y);
            $this->SetLineWidth(0.2);
            $this->Ln(1);
            break;
        case 'STRONG':
            $this->SetStyle('B', true);
            break;
        case 'EM':
            $this->SetStyle('I', true);
            break;
        case 'B':
        case 'I':
        case 'U':
            $this->SetStyle($tag, true);
            break;
        case 'A':
            $this->HREF=$attr['HREF'];
            break;
        case 'IMG':
            if(isset($attr['SRC']) and (isset($attr['WIDTH']) or isset($attr['HEIGHT']))) {
                if(!isset($attr['WIDTH']))
                    $attr['WIDTH'] = 0;
                if(!isset($attr['HEIGHT']))
                    $attr['HEIGHT'] = 0;
                $this->Image($attr['SRC'], $this->GetX(), $this->GetY(), px2mm($attr['WIDTH']), px2mm($attr['HEIGHT']));
            }
            break;
        //case 'TR':
        case 'BLOCKQUOTE':
        case 'BR':
            $this->Ln(5);
            break;
        case 'P':
            $this->Ln(10);
            break;
        case 'FONT':
            if (isset($attr['COLOR']) and $attr['COLOR']!='') {
                $coul=hex2dec($attr['COLOR']);
                $this->SetTextColor($coul['R'], $coul['G'], $coul['B']);
                $this->issetcolor=true;
            }
            if (isset($attr['FACE']) and in_array(strtolower($attr['FACE']), $this->fontlist)) {
                $this->SetFont(strtolower($attr['FACE']));
                $this->issetfont=true;
            }
            if (isset($attr['FACE']) and in_array(strtolower($attr['FACE']), $this->fontlist) and isset($attr['SIZE']) and $attr['SIZE']!='') {
                $this->SetFont(strtolower($attr['FACE']), '', $attr['SIZE']);
                $this->issetfont=true;
            }
            break;
    }
}

function CloseTag($tag)
{
    //Closing tag
    if($tag=='SUP') {
    }

    if($tag=='TD') { // TD-END
        $this->tdbegin=false;
        $this->tdwidth=0;
        $this->tdheight=0;
        $this->tdalign="J";
        $this->tdbgcolor=false;
    }
    if($tag=='TR') { // TR-END
        $this->Ln();
    }
    if($tag=='TABLE') { // TABLE-END
        //$this->Ln();
        $this->tableborder=0;
    }

    if($tag=='STRONG')
        $tag='B';
    if($tag=='EM')
        $tag='I';
    if($tag=='B' or $tag=='I' or $tag=='U')
        $this->SetStyle($tag, false);
    if($tag=='A')
        $this->HREF='';
    if($tag=='FONT'){
        if ($this->issetcolor==true) {
            $this->SetTextColor(0);
        }
        if ($this->issetfont) {
            $this->SetFont('arial');
            $this->issetfont=false;
        }
    }
}

function SetStyle($tag, $enable)
{
    //Modify style and select corresponding font
    $this->$tag+=($enable ? 1 : -1);
    $style='';
    foreach(array('B', 'I', 'U') as $s)
        if($this->$s>0)
            $style.=$s;
    $this->SetFont('', $style);
}

function PutLink($URL, $txt)
{
    //Put a hyperlink
    $this->SetTextColor(0, 0, 255);
    $this->SetStyle('U', true);
    $this->Write(5, $txt, $URL);
    $this->SetStyle('U', false);
    $this->SetTextColor(0);
}




/*
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
}*/
/*}

/////////////////////    Texto en columnas(parametrizable), Justificado, con FPDF sin librerias extra    ////////////////////////////////////////////////////////
// Parámetros: Cadena original, Numero de columnas a imprimir la cadena, Variable del FPDF para imprimir devuelta, Número máximo de caracteres por renglón
/*
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
 $sa = "select * from cp_documentoextsalida where Cod_Documento = '$cod_documento' and Periodo='$periodo'";
 $qa = mysql_query($sa) or die ($sa.mysql_error());
 $fa = mysql_fetch_array($qa);
 
 //// CONSULTA PARA OBTNER DATOS A MOSTRAR
 
 
 $sb = "select 
               md.Dependencia,
			   mp.NomCompleto,
			   rp.DescripCargo 
		  from 
		       mastdependencias md
			   inner join mastpersonas mp on (md.CodPersona=mp.CodPersona) 
			   inner join mastempleado me on (mp.CodPersona = me.Codpersona)
			   inner join rh_puestos rp on (rp.CodCargo = me.CodCargo)
		 where 
		       md.CodInterno = '".$fa['CodInterno']."'";
 $qb = mysql_query($sb) or die ($sb.mysql_error());
 $fb = mysql_fetch_array($qb);

  $sb = "select * from cp_documentodistribucionext where Cod_Documento='$cod_documento' and Periodo= '$periodo' and Secuencia= '$secuencia'";
   $qb = mysql_query($sb) or die ($sb.mysql_error());
   $fb = mysql_fetch_array($qb);
   
   /// Consulta para obtener datos a mostrar en pantalla
   if($fb[FlagEsParticular]=='N'){
     $sc= "select * from pf_organismosexternos where CodOrganismo = '".$fb['Cod_Organismos']."'";
     $qc= mysql_query($sc) or die ($sc.mysql_error());
     $fc= mysql_fetch_array($qc);
   }else{
     $sc= "select * from cp_particular where CodParticular = '".$fb['Cod_Organismos']."'";
     $qc= mysql_query($sc) or die ($sc.mysql_error());
     $fc= mysql_fetch_array($qc);
   }

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
/*
$pdf->SetFont('Arial', 'B', 12);
if ($fa['CodInterno']=='DC') // si es un oficio externo de despacho cambia las siglas DC por CEM
{$pdf->SetXY(14, 60); $pdf->Cell(3, 10, utf8_decode('CEM-').$pdf->cod_documento.'-'.$pdf->periodo, 0, 1, 'L');}
else // si no mantiene las siglas de la direccion 
{$pdf->SetXY(14, 60); $pdf->Cell(3, 10, $fa['Cod_DocumentoCompleto'], 0, 1, 'L');}
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(55, 5, 'Ciudadano (a)', 0, 1, 'L');  
$pdf->SetFont('Arial', 'B', 12); $pdf->Cell(3, 5, $fb['Representante'] ,0, 1, 'L');
$pdf->SetFont('Arial', '', 12); textIntoCols($fb['Cargo'],1,$pdf,100); 
//$pdf->Cell(3,5, $fb['Cargo'] ,0, 1, 'L');
$pdf->SetFont('Arial', '', 12); $pdf->Cell(3,5, strtoupper($fc['Organismo']) ,0, 1, 'L');


//////////////////////      llamada de la funcion texto en columnas ////////////////////////////////////////////////////////////
textIntoCols($fc['Direccion'],1,$pdf,100); 

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
/*

$pdf->SetLeftMargin(10);
$pdf->SetFontSize(11);

//$pdf->WriteHTML($f_cpint['Contenido']);

$html = $fa['Contenido'];
/*$html = 'Ahora puede imprimir fácilmente texto mezclando diferentes estilos: <b>negrita</b>, <i>itálica</i>,
<u>subrayado</u>, o ¡ <b><i><u>todos a la vez</u></i></b>!<br><br>También puede incluir enlaces en el
texto, como <a href="http://www.fpdf.org">www.fpdf.org</a>, o en una imagen: pulse en el logotipo.';*/
/*
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
  */






session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
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

<body style="margin:0">
<? 
 $det = explode(";", $detalles);
	   $i=0;
	
	   foreach ($det as $detalle) {
		   list($cod_documento, $periodo, $secuencia)=SPLIT( '[|]', $detalle);$i++;
          // echo"$cod_documento, $periodo, $secuencia";
	  }	

 $sa = "select * from cp_documentoextsalida where Cod_Documento = '$cod_documento' and Periodo='$periodo'";
 $qa = mysql_query($sa) or die ($sa.mysql_error());
 $fa = mysql_fetch_array($qa);
 
 //// CONSULTA PARA OBTNER DATOS A MOSTRAR
 $sb = "select 
               md.Dependencia,
			   mp.NomCompleto,
			   rp.DescripCargo 
		  from 
		       mastdependencias md
			   inner join mastpersonas mp on (md.CodPersona=mp.CodPersona) 
			   inner join mastempleado me on (mp.CodPersona = me.Codpersona)
			   inner join rh_puestos rp on (rp.CodCargo = me.CodCargo)
		 where 
		       md.CodInterno = '".$fa['CodInterno']."'";
 $qb = mysql_query($sb) or die ($sb.mysql_error());
 $fb = mysql_fetch_array($qb);
?>
<table id="principal"  align="center" HEIGHT="900px" valign="top" style="margin-y:0" >
<tr><td width="707">
  <!-- CABECERA DEL DOCUMENTO -->
  <table width="687" align="center" id="cabecera" cellpadding="0" cellspacing="0" HEIGHT="120px">
  <tr>
   <td width="20"></td>
   <td width="102"><img src="../imagenes/logos/logo oficio.jpg" width="103" height="57" style="height:110px; width:130px" /></td>
   <td width="10"></td>
   <td width="378px">
	  
     </td>
   <td width="103" align="right"><img src="imagenes/logoContraloria.jpg" style="height:80px; width:80px" /></td>
  </tr>
  <tr>
    <td width="10"></td>
    <td ><font face="Times" style=" font-size:11; line-height: 1.2em" >CEM-<?=$fa['Cod_DocumentoCompleto']?></font></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  </table>
</td></tr>

<tr><td>
   <!-- xxxxxxxxxxxxxxxxxxx -->
   <table id="numero_doc" valign="top" style="margin-y:0" > 
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td width="400"></td>
   <? 
	 list($a, $m, $d)=SPLIT( '[/.-]', $fa['FechaDocumento']); $f_documento=$d.'-'.$m.'-'.$a;
	 switch ($m) {
		case '01': $mes = Enero; break;  case '07': $mes = Julio; break;
		case '02': $mes = Febrero;break; case '08': $mes = Agosto; break;
		case '03': $mes = Marzo;break;   case '09': $mes = Septiembre; break;
		case '04': $mes = Abril;break;   case '10': $mes = Octubre; break;
		case '05': $mes = Mayo;break;    case '11': $mes = Noviembre; break;
		case '06': $mes = Junio;break;   case '12': $mes = Diciembre; break;
	 }
	?>
    <td align="right" width="290" ><font face="Times" style=" font-size:12; line-height: 1.2em">Maturín, <?=$d?> de <?=$mes?> de <?=$a?></font>
    </b></td>
  </tr>
  </table>
</td></tr>


<tr><td>
  <!-- CUERPO 1 DEL DOCUMENTO -->
  <table id="cuerpo1"   valign="top" style="margin-y:0; position: inherit; "  topmargin="0" >
  
    <tr>
    <td width="30"></td>
    <td width="308"></td>
    <td width="223"></td>
  </tr>
  <?
   /// Consulta para obtener datos de distribucion externa
   $sb = "select * from cp_documentodistribucionext where Cod_Documento='$cod_documento' and Periodo= '$periodo' ";
   $qb = mysql_query($sb) or die ($sb.mysql_error());
   //$fb = mysql_fetch_array($qb);
   
   /// Consulta para obtener datos a mostrar en pantalla
   while ($fb = mysql_fetch_array($qb)) {
   if($fb[FlagEsParticular]=='N'){
     $sc= "select * from pf_organismosexternos where CodOrganismo = '".$fb['Cod_Organismos']."'";
     $qc= mysql_query($sc) or die ($sc.mysql_error());
     $fc= mysql_fetch_array($qc);
   }else{
     $sc= "select * from cp_particular where CodParticular = '".$fb['Cod_Organismos']."'";
     $qc= mysql_query($sc) or die ($sc.mysql_error());
     $fc= mysql_fetch_array($qc);
   }
   	
if($fb['Atencion']=='S')	
	{
		
		$nom_at=$fb['Representante'];
		$cargo_at=$fb['Cargo'];
		$at='S';
		//$atencion='			';	
		}
else
	{
		$nom_rec=$fb['Representante'];
		$cargo_rec=$fb['Cargo'];
		$org_rec=$fc['Organismo'];
		$direccion_rec=$fc['Direccion'];
		//$receptor=;//
		}
		
   
   
}
  ?>
  <tr valign="top" >
    <td></td>
    <td><font face="Times" style=" font-size:12; line-height: 1em">Ciudadano(a)</font></td>
  </tr>
  <tr valign="top" >
    <td></td>
    <td><font face="Times" style=" font-size:12; line-height: 1em"><b><?=strtoupper($nom_rec)?></b></font></td>
  </tr>
  <tr valign="top" >
    <td></td>
    <td><font face="Times" style=" font-size:12; line-height: 1em"><?=$cargo_rec?></font></td>
  </tr>
  <tr valign="top" >
    <td></td>
    <td><font face="Times" style=" font-size:12; line-height: 1em"><?=htmlentities($org_rec)?></font></td>
  </tr>
  <tr valign="top" >
    <td></td>
    <td><font face="Times" style=" font-size:12; line-height: 1em"><?=htmlentities($direccion_rec)?></font></td>
  </tr>
  <tr valign="top" > 
    <td></td>
    <td><font face="Times" style=" font-size:12; line-height: 1em">Telefonos: <?=$fc['Telefono1'].' - '.$fc['Telefono2']?></font></td>
  </tr>
  <? if($at=='S')	
	{?>
  <tr >
			      <td></td>
			      <td width="10"></td>
				 <td align="right"><b class="cabeceraDoc" ><font face="Times" style=" font-size:12; line-height: 1em">Atención: <?=$nom_at?></font></b></td>
			    </tr>
			    <tr>
			      <td></td>
			      <td width="10"></td>
				 <td width="20" style="width:380px;text-align : right;" class="cabeceraDoc"  align="right"><font face="Times" style=" font-size:12; line-height: 1em">Cargo: <?=$cargo_at?></font></td>
			    </tr>  <? }?>
  </table>




  <!-- CONTENIDO DEL DOCUMENTO -->
  <table width="701" height="400px" style="margin-y:0; position: inherit; "  topmargin="0"  >
	<tr >
    <td width="80"></td>
    <td valign="top"></td>
    
  </tr>
  <tr >
    <td width="100px">&nbsp; </td>
    <td valign="top"><div style="width:660px"  ><font face="Times" style=" font-size:12; line-height: 1em; text-align:justify" ><?=$fa['Contenido']?></font></div></td>
    
  </tr>
  <tr>
    
    <td align="center" colspan="2"><font face="Times" style=" font-size:12;line-height: 0.5em">Atentamente,</font></td>
    
  </tr>
  
    
  <tr  >
    <td align="center" colspan="2" height="6px"><font face="Times" style=" font-size:12; line-height: 1em"><B>FREDDY JOSÉ CUDJOE</B></font></td>
  </tr>
  <tr>
    <td align="center" colspan="2" height="6px"><font face="Times" style=" font-size:12; line-height: 1em">CONTRALOR PROVISIONAL DEL ESTADO MONAGAS</font> </td>
  </tr>
  <tr>
    <td align="center" colspan="2" height="6px"><font face="Arial" style=" font-size:12px; line-height: 1em">Resolución N° 01-00-000159, de la Contraloría General de la República del 18-09-2013</font></td>
  </tr>
  <tr>
    <td align="center" colspan="2" height="6px"><font face="Arial" style=" font-size:12px;">Gaceta Oficial de la República Bolivariana de Venezuela, N° 40.254 del 19-09-2013</font></td>
  </tr>
  </table>

<tr><td>
  <!-- *********************** -->
   <div align="center" style="bottom:0;">


	<div style="bottom:0;">
	 
			   <table>

			   <tr><td>
				   <table id="pie_pagina">
  <tr>
     <td width="33"></td>
     <td  colspan="2" ><font face="Times" style=" font-size:12px; line-height: 1.2em"><?=$fa[MediaFirma]?></font></td>
     
  </tr>
  </table>
						<table  width="686" id="PIE" valign="baseline">
						<tr>
						<td width="71"></td>
						<td align="center" width="365"></td>
						<td width="81"></td>
						</tr>
						<tr>
						<td width="71"></td>
						<td align="center" width="365"></td>
						<td width="81"></td>
						</tr>
						<tr>
						<td align="center" style=" font-size:10px; line-height: 0.5em;" colspan="3" ><I>"Hacia la transparencia, fortalecimiento y consolidación del Sistema Nacional de Control Fiscal"</I></td>
						</tr><tr>
						<td align="center" style=" font-size:9px; line-height: 0.8em;" colspan="3" >_________________________________________________________________________________________________________________________________________________________     <font face="Arial" size="2">              <?//=$fa['MediaFirma']?></font></td>
						</tr><tr>
						<td align="center" style=" font-size:9px; line-height: 0.8em;" colspan="3">Dirección: Calle Sucre c/c Monagas, Edificio Sede de la Contraloría del estado Monagas, Maturín. Telefono: 0291-6410441 - 6432713</td>
						</tr><tr>
						<td  align="center" style=" font-size:9px; line-height: 0.8em;" colspan="3">Correo Electrónico: contraloriamonagas@contraloriamonagas.gob.ve, www.contraloriamonagas.gob.ve</td>
						</tr>
						<tr>
						<td  align="center" style=" font-size:9px; line-height: 0.8em;" colspan="3">RIF: G20001397-4</td></tr>
						</table>
			    
		</table>	  
		</div>	  
  <!-- *********************** -->
  
</td></tr>


</td></tr>
<div id="divButtons" name="divButtons">  
<input type="button" id="imprimir" name="imprimir" value = "Imprimir" onclick="printPage()"/> 
</div> 
</table>


</body>
</html>

