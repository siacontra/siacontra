<?php


require('../fpdf/fpdf.php');
require('../funciones/conexion.php');
require('../funciones/fechas.php');

class PDF extends FPDF
{
var $widths;
var $aligns;

function SetWidths($w)
{
	//Set the array of column widths
	$this->widths=$w;
}

function SetAligns($a)
{
	//Set the array of column alignments
	$this->aligns=$a;
}

function Row($data)
{
	//Calculate the height of the row
	$nb=0;
	for($i=0;$i<count($data);$i++)
		$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
	$h=5*$nb;
	//Issue a page break first if needed
	$this->CheckPageBreak($h);
	//Draw the cells of the row
	for($i=0;$i<count($data);$i++)
	{
		$w=$this->widths[$i];
		$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
		//Save the current position
		$x=$this->GetX();
		$y=$this->GetY();
		//Draw the border
		
		$this->Rect($x,$y,$w,$h);

		$this->MultiCell($w,5,$data[$i],0,$a,'true');
		//Put the position to the right of the cell
		$this->SetXY($x+$w,$y);
	}
	//Go to the next line
	$this->Ln($h);
}

function CheckPageBreak($h)
{
	//If the height h would cause an overflow, add a new page immediately
	if($this->GetY()+$h>$this->PageBreakTrigger)
		$this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
	//Computes the number of lines a MultiCell of width w will take
	$cw=&$this->CurrentFont['cw'];
	if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
	$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	$s=str_replace("\r",'',$txt);
	$nb=strlen($s);
	if($nb>0 and $s[$nb-1]=="\n")
		$nb--;
	$sep=-1;
	$i=0;
	$j=0;
	$l=0;
	$nl=1;
	while($i<$nb)
	{
		$c=$s[$i];
		if($c=="\n")
		{
			$i++;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
			continue;
		}
		if($c==' ')
			$sep=$i;
		$l+=$cw[$c];
		if($l>$wmax)
		{
			if($sep==-1)
			{
				if($i==$j)
					$i++;
			}
			else
				$i=$sep+1;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
		}
		else
			$i++;
	}
	return $nl;
}

function Header()
{
    // Logo
    $this->Image('../images/contraloria.jpg',22,8,20);
    // Arial bold 15
    $this->SetFont('Arial','',11);
   	$this->text(75, 17, 'REPUBLICA BOLIVARIANA DE VENEZUELA');
	$this->text(78, 22, 'CONTRALORIA DEL ESTADO SUCRE');
    $this->text(78,27,'DIVISION DE SERVICIOS GENERALES');

	$this->Ln(45);
	
}
function titulo()
{
    $this->SetFont('Arial','B',12);
   	$this->text(95,55,'AUTORIZACION');
	

	
}
function titulotabla()
{
    $this->SetFont('Arial','B',10);
   //	$this->text(19,95,'II. IDENTIFICACION DE MIEMBROS DE LA UNIDAD DE CONTRALORIA SOCIAL ');
	$this->Line(200,110,19,110);

	
}

function DatosCC()
{
    $this->SetFont('Arial','B',9);
   	$this->text(21, 67, 'Unidad Solicitante: ');
	$this->text(21, 72, 'Motivo de la Solicitud: ');
	$this->text(21, 77, 'Personal Asignado: ');
	$this->text(21, 82, 'Fecha: ');
	$this->text(70, 82, 'Hora de Salida: ');
	$this->text(130, 82, 'Hora de LLegada: ');
	$this->Line(160,82,185,82);
	$this->text(21, 87, 'Salida Local o Nacional : ');
	$this->text(21, 92, 'Placa : ');
	$this->text(21, 98, 'Vehiculo Asignado a: ');
	$this->text(100, 92, 'Km Entrada: ');
	$this->Line(150,92,125,92);
	$this->text(156, 92, 'Km Salida: ');
	$this->text(21, 104, 'Observaciones: ');
	
	$this->text(150, 45, 'Fecha: ');
	$this->text(150, 40, 'Orden de Salida Nro: ');
	

	
}
function Identificacion()
{
    
	$this->Line(200,63,19,63);
	

	
}
function firma()
{
	$nombres= $_GET['nombres'].' '.$_GET['apellidos'];
	$cargo= $_GET['cargo'];
	$this->SetFont('Arial','B',8);
	$this->text(24, 132, 'Por la Dependencia Solicitante ');
    $this->Line(20,127,71,127);
	$this->text(157, 132, 'Por la Unidad de S.P.S.G. ');
    $this->Line(150,127,200,127);
	$this->Text(85,220,$nombres,0,1);
	$this->Text(85,225,$cargo,0,1);
	
   
}

function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-45);
    // Arial italic 8
    $this->SetFont('Arial','',9);

	$this->text(53, 258, 'Palacio de Gobierno. Telefonos (0272) - 2364151, 2364161, 2365373');	
	$this->text(56, 261, 'E-mail: contraloriaestadotrujillo@contraloriaestadotrujillo.gob.ve');

   
}
}

	$id_salida= $_GET['id_salida'];
	$id_salida= $id_salida;
	$con = new DB;
	$consejocomunal = $con->conectar();	
	$strConsulta = "SELECT * from salida where id_salida =  '$id_salida'";
	$consejocomunal = mysql_query($strConsulta);
	$fila = mysql_fetch_array($consejocomunal);
	$pdf=new PDF('P','mm','letter');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->SetMargins(20,20,20);

	$pdf->titulo();
	$pdf->identificacion();
	$pdf->DatosCC();
	$pdf->titulotabla();
	$pdf->firma();
	$pdf->SetFont('Arial','',9);
	$pdf->Text(183,40,$fila['id_salida'],0,1);
	$fecha=implota($fila['fecha']);
	$pdf->Text(162,45,$fecha,0,1);
	$pdf->Text(52,67,$fila['dependencia'],0,1);
	$pdf->Text(57,72,$fila['motivo'],0,1);
	$pdf->Text(53,77,$fila['personal'],0,1);
	$fechaesti=implota($fila['fechaesti']);
	$pdf->Text(34,82,$fechaesti,0,1);
	$pdf->Text(95,82,$fila['hora'],0,1);
	$pdf->Text(60,87,$fila['salidalocal'],0,1);
	$pdf->Text(34,92,$fila['placa'],0,1);
	$pdf->Text(56,98,$fila['personal'],0,1);
	$pdf->Text(174,92,$fila['kilometraje'],0,1);
	$pdf->Text(48,104,$fila['observaciones'],0,1);
	
	
	$pdf->SetFont('Arial','',9);
	
	
	
	
	$pdf->Ln(45);
	$pdf->SetWidths(array(20, 60, 70, 25));
	$pdf->SetFont('Arial','B',10);
	$pdf->SetFillColor(100,100,100);
    $pdf->SetTextColor(255);

		
$pdf->Output();

   
?>
