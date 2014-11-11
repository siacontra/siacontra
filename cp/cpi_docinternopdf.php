<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');
require('fphp.php');
connect(); 
extract ($_POST);
extract ($_GET);

class PDF extends FPDF
{
var $B;
var $I;
var $U;
var $HREF;	
//Page header
function Header(){
    
	global $Periodo;
	$this->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	//$this->Image('../imagenes/logos/LOGOSNCF.jpg', 190, 10, 10, 10);	
	$this->SetFont('Arial', 'B', 11);
	$this->SetXY(20, 20); $this->Cell(160, 5, utf8_decode('N°'), 0, 1, 'R');
		
	$this->SetXY(20, 10); $this->Cell(170, 5, utf8_decode('REPÚBLICA BOLIVARIANA DE VENEZUELA'), 0, 1, 'C');
	$this->SetXY(20, 14); $this->Cell(170, 5, utf8_decode('CONTRALORÍA DEL ESTADO MONAGAS'), 0, 1, 'C');
	$this->SetXY(20, 18); $this->Cell(170, 5, utf8_decode('DEPENDENCIA'), 0, 1, 'C');
	
	
	/*$this->SetXY(20, 10);$this->Cell(10,5,'Fecha:',0,0,'');$this->Cell(10,5,date('d/m/Y'),0,1,'');
	$this->SetXY(20, 15); $this->Cell(10,5,utf8_decode('Página:'),0,1,'');
	$this->SetXY(19, 20); $this->Cell(7,5,utf8_decode('Año:'),0,0,'L');$this->Cell(6,5,date('Y'),0,1,'L');*/
				   
	$this->SetFont('Arial', 'B', 11);
	$this->Cell(190, 15, utf8_decode('MEMORANDUM'), 0, 1, 'C');
}
//Page footer
/*function Footer(){
    //Position at 1.5 cm from bottom
    $this->SetXY(125,13);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,10,' '.$this->PageNo().'/{nb}',0,0,'C');
}*/

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

//Instanciation of inherited class
$pdf=new PDF('P','mm','Legal');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

//// --------------------------------------------------------------------------------- */
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(27, 3, 'PARA:', 0, 0, 'L');  $pdf->Cell(3, 3, $field[1], 0, 1, 'L');
$pdf->Cell(27, 3, 'DE:', 0, 0, 'L');    $pdf->Cell(3, 3, $field[1], 0, 1, 'L');
$pdf->Cell(27, 3, 'FECHA:', 0, 0, 'L'); $pdf->Cell(3, 3, $field[1], 0, 1, 'L');
$pdf->Cell(27, 3, 'ASUNTO:', 0, 0, 'L');$pdf->Cell(3, 3, $field[1], 0, 1, 'L');
//// --------------------------------------------------------------------------------- */

$s_cpint = "select * from cp_documentointerno where Cod_DocumentoCompleto='02-02-0001-2012' and Cod_TipoDocumento='0003'";
$q_cpint = mysql_query($s_cpint) or die ($s_cpint.mysql_error());
$f_cpint = mysql_fetch_array($q_cpint);

$pdf->SetLeftMargin(45);
$pdf->SetFontSize(14);

//$pdf->WriteHTML($f_cpint['Contenido']);
$html = $f_cpint['Contenido'];
/*$html = 'Ahora puede imprimir fácilmente texto mezclando diferentes estilos: <b>negrita</b>, <i>itálica</i>,
<u>subrayado</u>, o ¡ <b><i><u>todos a la vez</u></i></b>!<br><br>También puede incluir enlaces en el
texto, como <a href="http://www.fpdf.org">www.fpdf.org</a>, o en una imagen: pulse en el logotipo.';*/

$pdf->WriteHTML($html);

$pdf->Output();
?>  
