<?php
require('fpdf.php');

class PDF_MC_Table extends FPDF
{
var $widths;
var $aligns;
var $periodo;

function Footer()
{
	$this->SetXY(10, -22); $this->Cell(25, 5, 'Aprobado Por:', 0, 0, 'R'); $this->Cell(30, 5, '______________________________', 0, 0, 'L');
	$this->SetXY(10, -15); $this->Cell(25, 5, 'Firma:', 0, 0, 'R'); $this->Cell(30, 5, '______________________________', 0, 0, 'L');
}

function Header()
{
	$this->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$this->SetFont('Arial', 'B', 8);
	$this->SetXY(20, 10); $this->Cell(90, 5, utf8_decode('Contraloría del Estado Sucre'), 0, 1, 'L');
	$this->SetXY(20, 15); $this->Cell(90, 5, utf8_decode('Dirección de Recursos Humanos'), 0, 1, 'L');
	
	$this->SetXY(160, 15); $this->Cell(20, 5, 'Fecha:', 0, 0, 'R'); $this->Cell(15, 5, date("d/m/Y"), 0, 0, 'L');
	$this->SetXY(160, 20); $this->Cell(20, 5, utf8_decode('Página:'), 0, 0, 'R'); $this->Cell(15, 5, $this->PageNo().' de {nb}', 0, 0, 'C');
	$this->Ln(10);
	
	$this->SetFont('Arial', 'B', 10); $this->Cell(190, 5, 'Reporte de Asistencias', 0, 1, 'C');
	$this->SetFont('Arial', 'B', 8); $this->Cell(190, 5, $this->periodo, 0, 1, 'C');
	$this->Ln(5);
}

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

function SetPeriodo($p)
{
	//Set the array of column alignments
	$this->periodo=$p;
}

function Row($data)
{
	//Calculate the height of the row
	$nb=0;
	for($i=0;$i<count($data);$i++)
		$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
	$h=3.5*$nb;
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
		$this->Rect($x,$y,$w,$h,"DF");
		//Print the text
		$this->MultiCell($w,3,$data[$i],0,$a);
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
}
?>
