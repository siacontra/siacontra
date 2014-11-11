<?php
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

}
-->
</style>
<script language="JavaScript"> 
function printPage() {  

document.getElementById('divButtons').style.visibility = 'hidden';  
document.getElementById('imprimir').style.visibility = 'hidden';  
window.print();  
document.getElementById('divButtons').style.visibility = 'visible';  
document.getElementById('imprimir').style.visibility = 'visible';  
}  


</script>

</head>

<body>
<?
 list($cod_documento, $codpersona)=SPLIT( '[|]', $_GET['registro']);
 //echo $codpersona;

$sa = "select * from cp_documentointerno where Cod_DocumentoCompleto = '$cod_documento'";
 $qa = mysql_query($sa) or die ($sa.mysql_error());
 $fa = mysql_fetch_array($qa);
 
 //// consulta para verificar datos
 $scon="select *  from cp_documentodistribucion where Cod_Documento = '$cod_documento'";
 $qcon = mysql_query($scon) or die ($scon.mysql_error());
 $rcon = mysql_num_rows($qcon);
// echo $scon;

//if ($fcon['Procedencia']=='INT'){ 
 //// CONSULTA PARA OBTNER DATOS A MOSTRAR
 
 if($rcon!=0){
 
   for($i=0; $i<$rcon; $i++){
     $fcon = mysql_fetch_array($qcon);
	 
	 $sb = "select 
				   md.Dependencia,
				   mp.NomCompleto,
				   rp.DescripCargo,
				   md.CodPersona 
			  from 
				   mastdependencias md
				   inner join mastpersonas mp on (md.CodPersona=mp.CodPersona) 
				   inner join mastempleado me on (mp.CodPersona = me.Codpersona)
				   inner join rh_puestos rp on (rp.CodCargo = me.CodCargo)
			 where 
				   md.CodInterno = '".$fa['CodInterno']."'"; 
	 $qb = mysql_query($sb) or die ($sb.mysql_error());
	 $fb = mysql_fetch_array($qb);
	// echo $sb;
	 //// CONSULTA PARA OBTENER LOS DATOS A QUIEN VA DIRIGIDO EL DOCUMENTO
	 $sc = "select
	               mp.NomCompleto,
				   rp.DescripCargo,
				   md.Dependencia
			  from
			      mastpersonas mp,
				  rh_puestos rp,
				  mastdependencias md
			 where
			      mp.CodPersona = '".$fcon['CodPersona']."' and
				  rp.CodCargo = '".$fcon['CodCargo']."' and 
				  md.CodDependencia = '".$fcon['CodDependencia']."'"; 
	 $qc = mysql_query($sc) or die ($sc.mysql_error());
	 $fc = mysql_fetch_array( $qc);
	 
?>
<table id="Padre" name="Padre" align="center" cellpadding="0" cellspacing="0" HEIGHT="850" valign="top" >
<tr>
<td width="1200px" valign="top">

<table id="principal"  align="center" valign="top">
<tr><td width="707">
  <!-- *********************** -->
  <table align="center" valign="top">
  <tr><td>
  <!-- CABECERA DEL DOCUMENTO -->
  <table width="679" align="right" id="cabecera" cellpadding="0" cellspacing="0" valign="top">
  <tr>
   <td width="3"></td>
   <td width="124" align="center"><img src="../imagenes/logos/contraloria.jpg" style="height:75px; width:90px" /></td>
   <td width="10"></td>
   <td width="420">
   <!-- *********************** -->
   <table cellpadding="0" cellspacing="0">
   <tr>
      <td align="center" width="414"><font size="3" face="Arial"></font></td>
   </tr>
   <tr>
      <td align="center"><font size="3" face="Arial"></font></td>
   </tr>
   <tr>
      <td align="center"><font size="3" face="Arial"><?//=htmlentities($fb['Dependencia']);?></font></td>
    </tr>
   </table>
   <!-- *********************** -->
   </td>
   <td width="120" align="center"></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  </table>
  <!-- FIN CABECERA DEL DOCUMENTO -->
  </td></tr>
  </table>
  <!-- *********************** -->
  
</td></tr>
<tr><td>
   <!-- *********************** -->
   <table width="688" height="27" id="numero_doc" valign="top"> 
   <tr>
    <td width="26" height="21"></td>
    <td width="26"></td>
    <td width="26"></td>
    <td width="436"></td>
    <td width="150"><font face="Arial"><b>N°:<?=$cod_documento?></b></font></td>
   </tr>
   </table>
   <!-- *********************** -->
</td></tr>

<tr><td>
   <!-- *********************** -->
   <table id="titulo">
   <tr>
    <td width="0"></td>
    <td width="0"></td>
    <td width="268"></td>
    <td width="148" align="center"><font size="3" face="Arial"><b>MEMORANDUM</b></font></td>
    <td width="0"></td>
  </tr>
  <tr><td height="5"></td></tr>
  </table>
  <!-- *********************** -->
</td></tr>

<tr><td>
  <!-- CUERPO 1 DEL DOCUMENTO -->
  <table id="cuerpo1" cellpadding="0" cellspacing="0" valign="top">
  <tr>
    <td width="34"></td>
    <td width="79"><font face="Arial"  style=" font-size:14px; line-height: 1.2em"><b>PARA</b></font></td>
    <td width="563"><font face="Arial"  style=" font-size:14px; line-height: 1.2em"><b>: <?=htmlentities($fc['DescripCargo']);?></b></font></td>
  </tr>
  <tr>
    <td width="34"></td>
    <td width="79"></td>
    <td width="563"><font face="Arial" style=" line-height: 1em">: <?=htmlentities($fc['NomCompleto']);?></font></td>
  </tr>
   <tr><td height="5"></td></tr>
  <tr>
    <td></td>
    <td><font face="Arial"  style=" font-size:14px; line-height: 1.2em"><b>DE</b></font></td>
    <td><font face="Arial"  style=" font-size:14px; line-height: 1.2em"><b>: <?=htmlentities($fb['Dependencia']);?></b></font></td>
    </tr>
   <tr>
    <td width="34"></td>
    <td width="79"></td>
    <td><font face="Arial"  style=" font-size:14px; line-height: 1.2em">: <?=htmlentities($fb['NomCompleto']);?></font></td>
  </tr>
   <tr><td height="5"></td></tr>
  <tr>
    <td></td>
    <td><font face="Arial"  style=" font-size:14px; line-height: 1.2em"><b>FECHA</b></font></td>
    <? 
	 list($a, $m, $d)=SPLIT( '[/.-]', $fa['FechaDocumento']); $f_documento=$d.'-'.$m.'-'.$a
	?>
    <td><font face="Arial"  style=" font-size:14px; line-height: 1.2em"><b>: <?=$f_documento?></b></font></td>
  </tr>
  <tr>
    <td height="5"></td>
  </tr>
  <tr>
    <td></td>
    <td><font face="Arial"  style=" font-size:14px; line-height: 1.2em"><b>ASUNTO</b></font></td>
    <td><font face="Arial"  style=" font-size:14px; line-height: 1.2em"><b>: <?=$fa['Asunto']?></b></font></td>
  </tr>
  <td></td>
    <td colspan="2">---------------------------------------------------------------------------------------------------------------------------------</td>
    
  </tr>
  </table>
  <!-- *********************** -->
</td></tr>

<tr><td>
  <!-- CONTENIDO DEL DOCUMENTO -->
  <table width="701">
  <tr>
    <td width="28"></td>
<td width="646" align="justify"><div style="width:650px;" id="cont" name="cont"><font face="Arial" style=" font-size:13px; line-height: 1.2em; text-align:justify"><?
    echo $fa['Contenido']; ?> </font></div></td>
    
    <td width="11"></td>
  </tr>
  </table>
  <!-- *********************** -->

  <!-- *********************** -->

  <!-- *********************** -->
</td></tr>

  
  </table>
  <!-- *********************** -->
   <div align="center" style="bottom:0;">
   <table align="center" >
		 
			  
			   <tr><td>
				
				<table align="center" id="atentamente" width="500" valign="baseline">
					<tr>
						<td width="71"></td>
						<td height="25"></td>
						<td width="81"></td>
						</tr>
						<tr>
						<td width="71"></td>
						<td align="center"><font face="Arial" >Atentamente,</font></td>
						<td width="81"></td>
						</tr>
						<tr>
						<td width="71"></td>
						<td height="25"></td>
						<td width="81"></td>
						</tr>
						<tr>
						<td width="71"></td>
						<td align="center">___________________________________________________</td>
						<td width="81"></td>
						</tr>
						<tr>
						<?
						$sd = "select * from rh_puestos where CodCargo = '".$fa['Cod_CargoRemitente']."'";
						$qd = mysql_query($sd) or die ($sd.mysql_error()); //echo $sa;
						$fd = mysql_fetch_array($qd); 
						?>
						<td align="center" width="71"></td>
						<td align="center" width="365"><font face="Arial" style=" font-size:14px; line-height: 1.2em"><?=htmlentities($fb['NomCompleto']);?></font></td>
						<td width="81"></td>
						</tr>
						<tr>
						<td width="71"></td>
						<td align="center" width="365"><font face="Arial" style=" font-size:14px; line-height: 1.2em"><?=htmlentities($fd['DescripCargo']);?></font></td>
						<td width="81"></td>
						</tr>
						</table>

			  </td></tr>

			   <tr><td>
<table width="686" id="pie_pagina" >
  <tr>
     <td width="26"></td>
     <td width="70"><font face="Arial" size="2"><?=$fa['MediaFirma']?></font></td>
     <td width="421"></td>
     <td width="149" align="right"></td>
  </tr>
  </table>
	
			  </td></tr>	   
	</table>	  
   </div>		  
 
  <!-- *********************** -->
</td></tr>
<!--<center><input type="button" name="imprimir" value="Imprimir" onclick="window.print();"></center>-->
<table  width="686" id="pie_pagina" valign="baseline" align="center" >
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
						<td align="center" style=" font-size:9px; line-height: 0.5em;" colspan="2" >Hacia la transparencia, fortalecimiento y consolidación del Sistema Nacional de Control Fiscal</td>
						</tr><tr><td width="90"></td>
						<td align="center" style=" font-size:8px; line-height: 0.8em;">_____________________________________________________________________________________________________________________________________________________________     <font face="Arial" size="2">              <?//=$fa['MediaFirma']?></font></td>
						</tr><tr>
						<td align="center" style=" font-size:8px; line-height: 0.8em;" colspan="2">Dirección: Calle Sucre c/c Monagas, Edificio Sede de la Contraloría del estado Monagas, Maturín. Telefono: 0291-6410441 - 6432713</td>
						</tr><tr>
						<td  align="center" style=" font-size:8px; line-height: 0.8em;" colspan="2">Correo Electrónico: contraloriamonagas@contraloriamonagas.gob.ve, www.contraloriamonagas.gob.ve</td>
						</tr>
						<tr><td colspan="2" align="right" style=" font-size:10px; line-height: 0.8em;"><b>CEM-M005</b></td></tr>
	</table>
</table>

</td></tr>
<div id="divButtons" name="divButtons">  
<input type="button" id="imprimir" name="imprimir" value = "Imprimir" onclick="printPage()"/> 
</div> 
</table><? 

}}
?>
</body>
</html>





<?php    //// Formato FPDF para emitir documentos internos en PDF error al imprimir las tablas
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
////////////////////////////////////

extract ($_POST);
extract ($_GET);
 list($cod_documento, $codpersona)=SPLIT( '[|]', $_GET['registro']);
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
    $this->tdalign="J";
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
	
//Page header
function Header(){
    
	global $Periodo;
	$this->Image('../imagenes/logos/contraloria.jpg', 10, 10, 40, 35);	
	//$this->Image('../imagenes/logos/LOGOSNCF.jpg', 190, 10, 10, 10);	
	$this->SetFont('Arial', 'B', 11);
	$this->SetXY(20, 20); $this->Cell(145, 15, utf8_decode('N°'), 0, 1, 'R');
	$this->SetXY(20, 20); $this->Cell(180, 15, $this->cod_documento, 0, 1, 'R');
	$this->Cell(190, 10, '', 0, 1, 'R'); // espacio de separacion entre el membrete y el contenido
}

//Page footer
function Footer(){
    //
    $this->SetFont('Arial','',7);
    //
    $this->SetXY(0, 260); $this->Cell(0, 0,utf8_decode('Hacia la Transparencia, Fortalecimiento y Consolidadión del Sistema Nacional de Control Fiscal'),0,1,'C');
    $this->SetXY(0, 263); $this->Cell(0, 0,'___________________________________________________________________________________________________________________  ',0,1,'C');
    $this->SetXY(0, 266); $this->Cell(0, 0,utf8_decode('Dirección: Calle Sucre c/c Monagas, Edificio Sede de la Contraloría del estado Monagas, Maturín. Telefono: 0291-6410441 - 6432713'),0,1,'C');
    $this->SetXY(0, 269); $this->Cell(0, 0,utf8_decode('Correo Electrónico: contraloriamonagas@contraloriamonagas.gob.ve, www.contraloriamonagas.gob.ve'),0,1,'C');
    $this->SetFont('Arial','B',8); $this->SetXY(0, 269); $this->Cell(0, 0,'CEM-M005',0,0,'R'); 
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
            else $this->tdalign="J"; // SET to your own
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
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

///***************************************************************************


 
$sa = "select * from cp_documentointerno where Cod_DocumentoCompleto = '$cod_documento'";
 $qa = mysql_query($sa) or die ($sa.mysql_error());
 $fa = mysql_fetch_array($qa);
 
 $td = "select * from cp_tipocorrespondencia where Cod_TipoDocumento ='". $fa['Cod_TipoDocumento']."'";
 $qtd = mysql_query($td) or die ($td.mysql_error());
 $ftd = mysql_fetch_array($qtd);

//// consulta para verificar datos
 $scon="select *  from cp_documentodistribucion where Cod_Documento = '$cod_documento'";
 $qcon = mysql_query($scon) or die ($scon.mysql_error());
 $rcon = mysql_num_rows($qcon);
 

 //// CONSULTA PARA OBTNER DATOS A MOSTRAR
 if($rcon!=0){
 
   for($i=0; $i<$rcon; $i++){
 
     $fcon = mysql_fetch_array($qcon);
	 
	 $sb = "select 
				   md.Dependencia,
				   mp.NomCompleto,
				   rp.DescripCargo,
				   md.CodPersona 
			  from 
				   mastdependencias md
				   inner join mastpersonas mp on (md.CodPersona=mp.CodPersona) 
				   inner join mastempleado me on (mp.CodPersona = me.Codpersona)
				   inner join rh_puestos rp on (rp.CodCargo = me.CodCargo)
			 where 
				   md.CodInterno = '".$fa['CodInterno']."'";
	 $qb = mysql_query($sb) or die ($sb.mysql_error());
	 $fb = mysql_fetch_array($qb);
	 
	 //// CONSULTA PARA OBTENER LOS DATOS A QUIEN VA DIRIGIDO EL DOCUMENTO
	 $sc = "select
	               mp.NomCompleto,
				   rp.DescripCargo,
				   md.Dependencia
			  from
			      mastpersonas mp,
				  rh_puestos rp,
				  mastdependencias md
			 where
			      mp.CodPersona = '".$fcon['CodPersona']."' and
				  rp.CodCargo = '".$fcon['CodCargo']."' and 
				  md.CodDependencia = '".$fcon['CodDependencia']."'";
	 $qc = mysql_query($sc) or die ($sc.mysql_error());
	 $fc = mysql_fetch_array( $qc);




//fecha del documento
list($a, $m, $d)=SPLIT( '[/.-]', $fa['FechaDocumento']); $f_documento=$d.'-'.$m.'-'.$a;

//encabezado primera pagina
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 20, utf8_decode( $ftd['Descripcion']), 0, 1, 'C');
//// --------------------------------------------------------------------------------- */
/*
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(35, 7, 'PARA', 0, 0, 'L');  $pdf->Cell(3, 7, ': '.$fc['DescripCargo'] ,0, 1, 'L');
$pdf->Cell(35, 7, '', 0, 0, 'L');  
$pdf->SetFont('Arial', '', 12); $pdf->Cell(3,7,  ': '.$fc['NomCompleto'] ,0, 1, 'L');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(35, 7, 'DE', 0, 0, 'L');    $pdf->Cell(3, 7, ': '.$fb['Dependencia'], 0, 1, 'L');
$pdf->Cell(35, 7, '', 0, 0, 'L');   
$pdf->SetFont('Arial', '', 12);  $pdf->Cell(3, 7, ': '.$fb['NomCompleto'], 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(35, 7, 'FECHA', 0, 0, 'L'); $pdf->Cell(3, 7, ': '.$f_documento, 0, 1, 'L');
$pdf->Cell(35, 7, 'ASUNTO', 0, 0, 'L');$pdf->Cell(3, 7, ': '.$fa['Asunto'], 0, 1, 'L');
$pdf->Cell(35, 7, '----------------------------------------------------------------------------------------------------------------------------------', 0, 1, 'L');


//// ------------------------------------  datos del documento BD --------------------------------------------- */
/*
$pdf->SetFont('Arial', '', 12);
$s_cpint ="select * from cp_documentointerno where Cod_DocumentoCompleto = '$cod_documento'";
$q_cpint = mysql_query($s_cpint) or die ($s_cpint.mysql_error());
$f_cpint = mysql_fetch_array($q_cpint);

$sd = "select * from rh_puestos where CodCargo = '".$fa['Cod_CargoRemitente']."'";
$qd = mysql_query($sd) or die ($sd.mysql_error()); //echo $sa;
$fd = mysql_fetch_array($qd); 


$pdf->SetLeftMargin(10);
$pdf->SetFontSize(11);

//$pdf->WriteHTML($f_cpint['Contenido']);
$html =  str_replace ("&nbsp;", " ", $f_cpint['Contenido']);  
$pdf->Cell(0, 1, $html,0, 1, 'J');  
/*$html = 'Ahora puede imprimir fácilmente texto mezclando diferentes estilos: <b>negrita</b>, <i>itálica</i>,
<u>subrayado</u>, o ¡ <b><i><u>todos a la vez</u></i></b>!<br><br>También puede incluir enlaces en el
texto, como <a href="http://www.fpdf.org">www.fpdf.org</a>, o en una imagen: pulse en el logotipo.';*/

//$pdf->WriteHTML($html);

// firma al pie de ultima pagina
/*
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 1, '',0, 1, 'C');
$pdf->Cell(0, 40, 'Atentamente,',0, 1, 'C');
$pdf->Cell(0, 10, '___________________________________________________', 0, 1, 'C');  
$pdf->Cell(0,5,  $fb['NomCompleto'] ,0, 1, 'C');
$pdf->Cell(0, 5, $fd['DescripCargo'], 0, 1, 'C'); 
if($i<$rcon-1){
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);}
}}

$pdf->Output();*/
?>  
