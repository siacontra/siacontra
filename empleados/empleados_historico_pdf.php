<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");

		$dia_actual=date("d");
		$mes_actual=date("m"); 
		$anio_actual=date("Y");
		$dia_letras = convertir_a_letras($dia_actual, "entero");
				$dia_actual = ("$dia_letras ($dia_actual)");
				$anio_letras = convertir_a_letras($anio_actual, "entero");
				$anio_actual = ("$anio_letras ($anio_actual)");
				$m = (int) $mes_actual; 
				//$mes_actual = getNombreMes($m);
	switch ($m) {
    case 1:
        $mes_actual = "Enero";
        break;
    case 2:
       $mes_actual = "Febrero";
        break;
    case 3:
        $mes_actual = "Marzo";
        break;
    case 4:
        $mes_actual = "Abril";
        break;
    case 5:
        $mes_actual = "Mayo";
        break;
    case 6:
        $mes_actual = "Junio";
        break;
    case 7:
        $mes_actual = "Julio";
        break;
    case 8:
        $mes_actual = "Agosto";
        break;
    case 9:
        $mes_actual = "Septiembre";
        break;
    case 10:
        $mes_actual = "Octubre";
        break;
    case 11:
        $mes_actual = "Noviembre";
        break;
    case 12:
        $mes_actual = "Diciembre";
        break;
}
	
	
			$registro=$_REQUEST['registro'];
			/*$c=(mysql_fetch_array($persona))
			$pdf->SetXY(4.5,10.3); $pdf->MultiCell(17.8, 0.5, "".$c[0]);*/
		
			//cabecera del informe preliminales
			$pdf=new FPDF("P","cm","LETTER");
			$pdf->SetCreator("Creado automaticamente con FPDF"); 
			$pdf->SetAuthor("Cecilio Carvajal");  
			$pdf->SetTitle("HISTORICO DEL EMPLEADO");
			$pdf->AliasNbPages();
			$pdf->AddPage();
			
			$pdf->SetLineWidth(0.05);
			$pdf->SetLeftMargin(1.0);
			//$pdf->Rect( 1.3, 1.0,18.5, 25.8, 'D');
			$pdf->image("../imagenes/CEM.jpg", 1.9, 1.4, 2.5, 2);
			$pdf->SetFont('Arial','IB',14);
			
			//$pdf->SetXY( 12, 2); $pdf->write(0.5 ,"Nro de la Denuncia:");
			/*$pdf->SetXY( 17, 2);
			$pdf->write(0.5 ,"$xcodigo");*/
			$pdf->SetLineWidth(0.00);

			$pdf->SetXY( 8.4, 3.9); $pdf->write(0.5 ,"Recorrido Laboral");
			
			$pdf->SetLineWidth(0.01);
			$pdf->SetDrawColor(0,0,0);
			$pdf->SetFillColor(127,127,127);
					/*datos de los denunciantes*/
			$pdf->SetFont('Arial','BI',12);
			$pdf->SetLineWidth(0.01);
			
			
			$persona=mysql_query("SELECT
							p.CodPersona,
							p.NomCompleto,
							p.Ndocumento,
							p.Estado,
							p.Sexo
                        FROM
                            mastpersonas p
                        WHERE CodPersona='$registro';");
                        
                  
			if($cam=mysql_fetch_array($persona)){
			
			$pdf->SetFont('Arial','I',12);
			
			if ($cam[4]=='M')
				{ $ciudadano="que el Ciudadano:";}
			else
				{ $ciudadano="que la Ciudadana:" ;}
			
			$cedula=$cam[2];
			//$pdf->SetXY(1.6,9.5); $pdf->MultiCell(3.0, 0.5, "".$cedula);
			
			$jefa=mysql_query("SELECT Fingreso, Fegreso
			FROM
			mastempleado
			WHERE 
			mastempleado.CodPersona='$registro';");
			
			if($campo=mysql_fetch_array($jefa)){
				
				
				
			if ($cam[3]=='I')
				{ $fechas=iFecha($campo[0])." hasta el ". iFecha($campo[1]).utf8_decode(", y desempeño los siguientes cargos:");	
			    }
			else
				{ $fechas=iFecha ($campo[0]).utf8_decode(", y ha desempeñado los siguientes cargos:");	
				}
			//$pdf->SetXY(4.5,10.9); $pdf->MultiCell(17.8, 0.5, "".$campo[3]);
			
			}
		    $sql_director="select p.NomCompleto, pu.DescripCargo  from mastpersonas p, mastdependencias d, mastempleado e, rh_puestos pu where 
						p.CodPersona=e.CodPersona and d.CodPersona=e.CodPersona and d.CodDependencia='0010' and pu.CodCargo=e.CodCargo";
			$query_mast = mysql_query($sql_director) or die (mysql_error());
			$dir=mysql_fetch_array($query_mast);
			
			$pdf->SetXY( 1.6, 5.7); 
			$pdf->MultiCell(18 , 0.7,"Quien Suscribe: ".utf8_decode($dir['NomCompleto'])." en su caracter de ".utf8_decode($dir['DescripCargo'])." de la Contraloria del estado Monagas, hace constar ".$ciudadano." ".utf8_decode($cam[1]). utf8_decode(", Titular de la Cédula de Identidad Nro ").number_format($cam[2],0,'','.').utf8_decode(" ingresó a este Órgano de Control Fiscal el ").$fechas, 0, 'J');	
				
			$tip=mysql_query("SELECT Secuencia FROM rh_historial,mastpersonas
			WHERE rh_historial.CodPersona=mastpersonas.CodPersona and CodPersona='$registro';");
			if($campo=mysql_fetch_array($tip)){
			//$pdf->SetXY(4.5,10.3); $pdf->MultiCell(17.8, 0.5, "".$campo[0]);
			
			}
			
			$pdf->SetFont('Arial','I',8);
			$pdf->SetXY(1.6,9.5); $pdf->MultiCell(3.0, 0.5, "CARGO",1,'C');
			$pdf->SetXY(4.6,9.5); $pdf->MultiCell(2.5, 0.5, "NOMINA",1,'C');
			$pdf->SetXY(7.1,9.5); $pdf->MultiCell(4.5, 0.5, "DEPENDENCIA",1,'C');
			$pdf->SetXY(11.6,9.5); $pdf->MultiCell(2.0, 0.5, "DESDE",1,'C');
			$pdf->SetXY(13.6,9.5); $pdf->MultiCell(2.0, 0.5, "HASTA",1,'C');
			$pdf->SetXY(15.6,9.5); $pdf->MultiCell(4.0, 0.5, "OBSERVACION",1,'C');
			
			$historial=mysql_query("SELECT mp.CodPersona, 
			rhh.Secuencia, 
			mp.NomCompleto, 
			rhh.Dependencia, 
			rhh.Organismo, 
			rhh.Cargo, 
			rhh.NivelSalarial, 
			rhh.Fingreso, 
			rhh.Fegreso, 
			rhh.MotivoCese,
			rhh.TipoNomina
			FROM saicom.mastpersonas AS mp
			INNER JOIN saicom.mastempleado AS me ON me.CodPersona = mp.CodPersona
			INNER JOIN saicom.rh_historial AS rhh ON rhh.CodPersona = mp.CodPersona
			WHERE mp.CodPersona='$registro'  order by Fingreso Desc;");
			
			$pdf->SetY(10.0);
			for($x=1.6, $y=10.5; $campo=mysql_fetch_array($historial); $y+=1.5){
			//$pdf->SetXY( $x , $pdf->GetY() - 0.5);
			$pdf->SetXY( $x, $y);
			$pdf->MultiCell(3.0, 0.3, "".utf8_decode($campo[5]),0,'C');
			$pdf->SetXY( 4.5, $y);
			$pdf->MultiCell(2.5, 0.3, "".$campo[10],0,'C');	
			$pdf->SetXY( 7.2, $y);
			$pdf->MultiCell(4.2, 0.3, "".$campo[3],0,'C');
			$pdf->SetXY( 11.3, $y);
			$pdf->MultiCell(2.5, 0.3, "".iFecha($campo[7]),0,'C');
			$pdf->SetXY( 13.3, $y);
			$pdf->MultiCell(2.5, 0.3, "".iFecha($campo[8]),0,'C');		
			
			$pdf->SetXY( 15.3, $y);
			$pdf->MultiCell(4.6, 0.3, "".utf8_decode($campo[9]),0,'C');	
		
			}
			
			$pdf->SetFont('Arial','I',12);
			$parrafo2 = ("Constancia que se expide a petición de la parte interesada. En la Ciudad de Maturín, estado Monagas, a los ".$dia_actual." día(s) del mes de ".$mes_actual." de ".$anio_actual.".");
			$pdf->Ln(2);
		    $pdf->SetXY( 1.5, $y);
			$pdf->MultiCell(18, 0.7, utf8_decode($parrafo2), 0, 'J');
			
			
			
			$pie1 = $dir['NomCompleto'];//("Abg. Karla Azocar");
			$pie2 = $dir['DescripCargo'];//("Directora de Recursos Humanos");
			$pie3 = ("CONTRALORÍA DEL ESTADO MONAGAS");
			//$pdf->Ln(2);
		    $pdf->SetXY( 2, $y+3);
			$pdf->MultiCell(18, 1, 'Atentamente,', 0, 'C');
		
			$pdf->SetFont('Arial', 'B', 12);
			$pdf->SetXY(1, $y+5); $pdf->Cell(20, 0.5, utf8_decode($pie1), 0, 1, 'C');
				
			$pdf->SetFont('Arial', '',12);
			$pdf->SetXY(1, $y+5.5); $pdf->Cell(20, 0.5, utf8_decode($pie2), 0, 1, 'C');
			$pdf->SetXY(1, $y+6); $pdf->Cell(20, 0.5, utf8_decode($pie3), 0, 1, 'C');
			
		}
			$pdf->Output();
			function iFecha($sqq)
	{
		if(!$sqq)
			return "-";
		$uqq=0;
		$yqq="";
		$mqq="";
		$dqq="";
		for($iqq=0;$iqq<strlen($sqq); $iqq++)
		{
			$cqq=substr($sqq,$iqq,1);
			if($cqq=='/' || $cqq=='-')
				$uqq++;
			else
			{
				if($uqq==0)
					$yqq=$yqq.$cqq;
				else if($uqq==1)
					$mqq=$mqq.$cqq;
				else
					$dqq=$dqq.$cqq;
			}
		}
		$fqq="$dqq-$mqq-$yqq";
		return $fqq;
	}
?>
