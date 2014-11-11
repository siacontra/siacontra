<?php


require('../fpdf/fpdf.php');
require('../funciones/conexion.php');
require('../paginas/funcfechas.php');

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
	$this->text(78, 22, 'CONTRALORIA DEL ESTADO MONAGAS');
    $this->text(78,27,'UNIDAD DE PREVENCION SEGURIDAD');
	$this->text(88, 32, ' Y SERVICIOS GENERALES');
	$this->Ln(45);
	
}
function titulo()
{
    $this->SetFont('Arial','B',12);
   	$this->text(50,55,'FICHA DE MANTENIMIENTO DEL PARQUE AUTOMOTOR');
	

	
}
function titulotabla()
{
    $this->SetFont('Arial','B',10);
   //	$this->text(19,95,'II. IDENTIFICACION DE MIEMBROS DE LA UNIDAD DE CONTRALORIA SOCIAL ');
	

	
}

function DatosCC()
{
	//PRIMERA PARTE MAS ABAJO DEL ENCABEZADO
	$this->SetFont('Arial','B',9);
	$this->text(20,65, 'CODIGO DEL VEHICULO: ');
	$this->text(20, 70, 'DEPENDENCIA ASIGNADA: ');
	$this->text(20, 75, 'FECHA DEL MANTENIMIENTO: ');
	
	//CUADRO N 1 
	$this->text(27, 89, 'MARCA ');
	$this->text(60, 89, 'PLACA');
	$this->text(93, 89, 'SERIAL MOTOR');
	$this->text(135, 89, 'SERIAL CARROCERIA');
	$this->text(176, 89, 'MODELO/ANO');
	
	//CUADRO N 2 LADO 1
	$this->text(23,122, 'CAMBIO DE BUJIAS: ');
	$this->text(23, 130, 'LAVADO GAMUZADO: ');
	$this->text(23, 138, 'CAMBIO DE ACEITE: ');
	$this->text(23, 148, 'CAMBIO DEL SISTEMA DE FRENOS: ');
	$this->text(20, 179, 'OTROS ESPECIFIQUE: ');
	$this->text(20, 190, 'OBSERVACIONES: ');
	
	//CUADRO N 2 LADO 2
	$this->text(115,122, 'PRESION DE NEUMATICOS: ');
	$this->text(115, 130, 'GRAFITO: ');
	$this->text(115, 138, 'ALINEACION Y BALANCEO: ');
	$this->text(115, 157, 'CAMBIO DE FILTRO: ');
	
	//CUADRO N 2 LADO 3
	$this->text(155,147, 'ACEITE: ');
	$this->text(155, 157, 'AIRE: ');
	$this->text(155, 167, 'GASOLINA: ');


	
	

	
}
function Identificacion()
{
	//CUADRO DE DESCRIPCION DEL VEHICULO
    //PRIMERA LINEA (ARRIBA)
	$this->text(80, 82, 'DESCRIPCION DEL VEHICULO: ');
	$this->Line(200,84,19,84);
	//SEGUNDA LINEA
	$this->Line(200,100,19,100);
	// PRIMERA LINEA IZQUIERDA VERTICAL
	$this->Line(19,84,19,100);
	// SEGUNDA LINEA IZQUIERDA VERTICAL
	$this->Line(50,84,50,100);
	// TERCERA LINEA IZQUIERDA VERTICAL
	$this->Line(85,84,85,100);
	// CUARTA LINEA IZQUIERDA VERTICAL
	$this->Line(127,84,127,100);
	// QUINTA LINEA IZQUIERDA VERTICAL
	$this->Line(175,84,175,100);
	// SEXTA LINEA IZQUIERDA VERTICAL
	$this->Line(200,84,200,100);
	//SEGUNDA LINEA HORIZONTAL(ABAJO)
	$this->Line(200,90,19,90);
	
	
	//TERCER TITULO
	$this->text(80, 115, 'MANTENIMIENTO PREVENTIVO: ');
	//PRIMERA LINEA HORIZONTAL DESPUES DEL TERCER TITULO
	$this->Line(200,117,19,117);
	//2DA LINEA HORIZONTAL DESPUES DEL TERCER TITULO
	$this->Line(200,125,19,125);
	//3RA LINEA HORIZONTAL DESPUES DEL TERCER TITULO
	$this->Line(200,133,19,133);
	//4TA LINEA HORIZONTAL DESPUES DEL TERCER TITULO
	$this->Line(200,141,19,141);
	//5TA LINEA HORIZONTAL DESPUES DEL TERCER TITULO
	$this->Line(200,171,19,171);
	
	// PRIMERA LINEA IZQUIERDA VERTICAL
	$this->Line(19,171,19,117);
	// 2DA LINEA IZQUIERDA VERTICAL
	$this->Line(110,171,110,117);
	// 3RA LINEA IZQUIERDA VERTICAL
	$this->Line(200,171,200,117);
	// 4TA LINEA IZQUIERDA VERTICAL PEQUEÑA
	$this->Line(150,171,150,141);

	


	
}
function firma()
{
	$nombres= $_GET['nombres'].' '.$_GET['apellidos'];
	$cargo= $_GET['cargo'];
	
	$this->text(33, 220, 'Responsable:');
    $this->Line(20,235,71,235);
	$this->text(160, 220, 'Recibido por: ');
	$this->SetFont('Arial','B',7);
	$this->text(155, 239, 'Nombre y Apellido/Firma/Fecha: ');
    $this->Line(150,235,200,235);
	$this->Text(85,220,$nombres,0,1);
	$this->Text(85,225,$cargo,0,1);
	
   
}

function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-45);
    // Arial italic 8
    $this->SetFont('Arial','',9);

	$this->text(53, 258, 'Calle Sucre con calle Monagas, Edificio Contraloría del estado Monagas, Telefono (0291) 641.04.41');	
	$this->text(56, 261, 'E-mail: contraloriamonagas@contraloriamonagas.gob.ve');
   
}
}

	$id_man= $_GET['id_mant'];
	$id_mant= $id_mant-1;
	$con = new DB;
	$consejocomunal = $con->conectar();	
	$strConsulta = "SELECT * from mantenimiento where id_mant =  '$id_mant'";
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
	$pdf->SetFont('Arial','',10);
	
    $pdf->Text(59,65,$fila['cod_veh'],0,1);
	$pdf->Text(64,70,$fila['dependencia'],0,1);
	$fechab=implota($fila['fecha']);
	$pdf->Text(68,75,$fechab,0,1);
	$pdf->SetFont('Arial','',9);
	$pdf->Text(20,95,$fila['marca'],0,1);
	$pdf->Text(58,95,$fila['placa'],0,1);
	$pdf->Text(88,95,$fila['serialmotor'],0,1);
	$pdf->Text(130,95,$fila['nrocarroceria'],0,1);
	$pdf->Text(180,95,$fila['modelo'],0,1);
	$pdf->Text(180,99,$fila['ano'],0,1);
	$pdf->Text(60,122,$fila['cambiobu'],0,1);
	$pdf->Text(60,130,$fila['lavadog'],0,1);
	$pdf->Text(60,138,$fila['amotor'],0,1);
	$pdf->Text(83,148,$fila['cambiofreno'],0,1);
	$pdf->Text(160,122,$fila['n_presion'],0,1);
	$pdf->Text(133,130,$fila['grafito'],0,1);
	$pdf->Text(161,138,$fila['alineabalan'],0,1);
	$pdf->Text(170,147,$fila['filtroaceite'],0,1);
	$pdf->Text(167,157,$fila['filtroaire'],0,1);
	$pdf->Text(175,167,$fila['filtroaceite'],0,1);
	$pdf->Text(56,179,$fila['especificar'],0,1);
	$pdf->Text(50,190,$fila['observaciones'],0,1);
	$pdf->SetFont('Arial','',7);
	$pdf->Text(28, 239,$fila['personal'],0,1);
	
	
	
	$pdf->Ln(45);
	$pdf->SetWidths(array(20, 60, 70, 25));
	$pdf->SetFont('Arial','B',10);
	$pdf->SetFillColor(100,100,100);
    $pdf->SetTextColor(255);

		
$pdf->Output();

   
?>
