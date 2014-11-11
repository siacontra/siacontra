<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<?php
require('fpdf.php');
require('fphp.php');
connect();
//
class PDF extends FPDF {
	//	Cabecera de página
	function Header() {
		$this->Image('../imagenes/logos/contraloria.jpg', 60, 10, 10, 10);
		$this->Image('../imagenes/logo_rrhh.png', 145, 10, 10, 10);
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(190, 5,utf8_decode( 'Contraloría del Estado Delta Amacuro'), 0, 1, 'C');
		$this->Cell(190, 5, utf8_decode('Dirección de Recursos Humanos'), 0, 1, 'C'); $this->Ln();
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(190, 10, 'Maestro de Tipos de Pago', 0, 1, 'C');
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(0, 80, 180);
		$this->SetTextColor(255, 255, 255);
		$this->SetFont('Arial', 'B', 6);
		$this->Cell(30, 5, 'TIPO', 1, 0, 'C', 1);
		$this->Cell(140, 5, 'DESCRIPCIÓN', 1, 0, 'C', 1);
		$this->Cell(20, 5, 'ESTADO', 1, 1, 'C', 1);
	}
	//	Pie de página
	function Footer() {
		//Posición: a 1,5 cm del final
	  $this->SetY(-15);
	  //Arial italic 8
	  $this->SetFont('Arial','I',8);
	  //Número de página
	  $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	// Para crear un multicell
	var $widths;
	var $aligns;

	function SetWidths($w) {
		//Set the array of column widths
		$this->widths=$w;
	}

	function SetAligns($a) {
		//Set the array of column alignments
		$this->aligns=$a;
	}

	function Row($data) {
		//Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=5*$nb;
		//Issue a page break first if needed
		$this->CheckPageBreak($h);
		//Draw the cells of the row
		for($i=0;$i<count($data);$i++) {
			$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			//Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
			//Draw the border
			$this->Rect($x,$y,$w,$h);
			//Print the text
			$this->MultiCell($w,5,$data[$i],0,$a);
			//Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
		//Go to the next line
		$this->Ln($h);
	}

	function CheckPageBreak($h) {
		//If the height h would cause an overflow, add a new page immediately
		if($this->GetY()+$h>$this->PageBreakTrigger)
			$this->AddPage($this->CurOrientation);
	}

	function NbLines($w,$txt) {
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

//	Creación del objeto de la clase heredada
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 6);
/////	Cuerpo
$pdf->SetWidths(array(30, 140, 20));
$pdf->SetAligns(array('C', 'L', 'C', 'C', 'C'));
//	Consulta
$sql="SELECT CodTipoPago, TipoPago, Estado FROM masttipopago ORDER BY CodTipoPago";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
for ($i=0; $i<$rows; $i++) {
	$field=mysql_fetch_array($query);
	$pdf->Row(array($field[0], $field[1], $field[2]));
}
/////
$pdf->Output();
?>