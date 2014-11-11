<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');
require('fphp.php');
connect(); 
extract ($_POST);
extract ($_GET);
 list($cod_documento, $periodo, $secuencia,$tipodoc)=SPLIT( '[|]', $_GET['documento']);
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
	
//Page header
function Header(){
    
	global $Periodo;
	$this->Image('../imagenes/logos/contraloria.jpg', 10, 10, 20, 15);	
	$this->Image('../imagenes/logos/LOGOSNCF.jpg', 190, 10, 10, 10);	
	$pdf->Cell(190, 10, '', 0, 1, 'R'); // espacio de separacion entre el membrete y el contenido
}

//Page footer
function Footer(){
    //
    $this->SetFont('Arial','I',7);
    //
    $this->SetXY(0, 260); $this->Cell(0, 0,utf8_decode('Hacia la Transparencia, Fortalecimiento y Consolidación del Sistema Nacional de Control Fiscal'),0,1,'C');
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

//Instanciation of inherited class
$pdf=new PDF('P','mm','Letter');
$pdf->inicio($cod_documento);
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
list($a, $m, $d)=SPLIT( '[/.-]', $fa['FechaDocumento']); $f_documento=$d.'-'.$m.'-'.$a;
switch ($m) {
		case '01': $mes = Enero; break;  case '07': $mes = Julio; break;
		case '02': $mes = Febrero;break; case '08': $mes = Agosto; break;
		case '03': $mes = Marzo;break;   case '09': $mes = Septiembre; break;
		case '04': $mes = Abril;break;   case '10': $mes = Octubre; break;
		case '05': $mes = Mayo;break;    case '11': $mes = Noviembre; break;
		case '06': $mes = Junio;break;   case '12': $mes = Diciembre; break;
	 }
//fecha del oficio
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(190, 15, 'Maturín, '.$d.' de '.$mes.' de '.$a, 0, 1, 'R');
//// --------------------------------------------------------------------------------- */
$pdf->SetFont('Arial', 'B', 11);
//$pdf->SetXY(20, 20); $this->Cell(145, 5, utf8_decode('N°'), 0, 1, 'R');
$pdf->SetXY(20, 20); $pdf->Cell(180, 5, $pdf->cod_documento, 0, 1, 'L');
$pdf->Cell(35, 7, 'Ciudadano (a)', 0, 0, 'L');  
$pdf->SetFont('Arial', 'B', 12); $pdf->Cell(3, 7, ': '.$fb['Representante'] ,0, 1, 'L');
$pdf->SetFont('Arial', '', 12); $pdf->Cell(3,7,  ': '.$fb['Cargo'] ,0, 1, 'L');
$pdf->Cell(3, 7, htmlentities($fc['Direccion']), 0, 1, 'L');
$pdf->Cell(3, 7, 'Telefonos: '.htmlentities($fc['Telefono1'].'  '.$fc['Telefono2']), 0, 1, 'L');
$pdf->Cell(190, 10, '', 0, 1, 'R'); // espacio de separacion entre el membrete y el contenido


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
$html = $f_cpint['Contenido'];
/*$html = 'Ahora puede imprimir fácilmente texto mezclando diferentes estilos: <b>negrita</b>, <i>itálica</i>,
<u>subrayado</u>, o ¡ <b><i><u>todos a la vez</u></i></b>!<br><br>También puede incluir enlaces en el
texto, como <a href="http://www.fpdf.org">www.fpdf.org</a>, o en una imagen: pulse en el logotipo.';*/

$pdf->WriteHTML($html);

// firma al pie de ultima pagina

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 1, '',0, 1, 'C');
$pdf->Cell(0, 40, 'Atentamente,',0, 1, 'C');
$pdf->Cell(0, 10, 'Lcdo. FREDDY CUDJOE', 0, 1, 'C');  
$pdf->Cell(0,5,'CONTRALOR DEL ESTADO MONAGAS (P)' ,0, 1, 'C');
$pdf->Cell(0, 5, 'Designado mediante Resolución Nº. 01-00-000159 de  Fecha 18-09-2013,', 0, 1, 'C'); 
$pdf->Cell(0, 5, 'Emanada del Despacho de la Contralora General de la República,,', 0, 1, 'C'); 
$pdf->Cell(0, 5, 'publicada en G.O.Nº 40.254 de fecha 19-09-2013,', 0, 1, 'C'); 

$pdf->Output();
?>  
