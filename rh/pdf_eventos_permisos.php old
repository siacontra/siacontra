<?php
define('FPDF_FONTPATH','font/');
require('mc_table_permisos.php');
require('fphp.php');
connect();
//---------------------------------------------------
$filtro=strtr($filtro, "*", "'");
$filtro=strtr($filtro, ";", "%");
$filtro_evento=strtr($filtro_evento, "*", "'");
$filtro_evento=strtr($filtro_evento, ";", "%");
$filtro_permiso=strtr($filtro_permiso, "*", "'");
$filtro_permiso=strtr($filtro_permiso, ";", "%");
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->AliasNbPages();
$pdf->Open();
$pdf->SetMargins(5, 5, 5);
$pdf->SetAutoPageBreak(1, 30);

list($dd, $md, $ad)=SPLIT( '[/.-]', $fingresod); $mesd = (int) $md;
list($dh, $mh, $ah)=SPLIT( '[/.-]', $fingresoh); $mesh = (int) $mh;

if ($md == $mh) $periodo = "Del ".$dd." Al ".$dh." De ".getNombreMes($mesd);
else $periodo = "Del ".$dd." de ".getNombreMes($mesd)." Al ".$dh." De ".getNombreMes($mesh);
$pdf->SetPeriodo($periodo);


$pdf->AddPage('P', 'Letter');
//	Cuerpo
$sql = "SELECT 
			mp.Busqueda, 
			mp.Ndocumento, 
			me.CodEmpleado,
			me.CodPerfil,
			me.CodPersona, 
			me.CodDependencia, 
			me.CodCarnetProv, 
			md.Dependencia, 
			rp.DescripCargo 
		FROM 
			mastempleado me 
			INNER JOIN mastpersonas mp ON (me.CodPersona = mp.CodPersona) 
			INNER JOIN mastdependencias md ON (me.CodDependencia = md.CodDependencia)
			INNER JOIN rh_empleadonivelacion en ON (me.CodPersona = en.CodPersona) 
			INNER JOIN rh_puestos rp ON (en.CodCargo = rp.CodCargo) 
		WHERE
			en.Secuencia = (SELECT MAX(Secuencia) FROM rh_empleadonivelacion WHERE CodPersona = me.CodPersona)
			$filtro 
		ORDER BY CodDependencia, CodEmpleado";
$query_empleado = mysql_query($sql) or die ($sql.mysql_error());
while ($field_empleado = mysql_fetch_array($query_empleado)) {
	$codempleado = $field_empleado['CodEmpleado'];	
	$sql = "SELECT 
				rp.*,
				mmd1.Descripcion AS NomTipoFalta,
				mmd2.Descripcion AS NomTipoPermiso
			FROM
				rh_permisos rp
				INNER JOIN mastmiscelaneosdet mmd1 ON (rp.TipoFalta = mmd1.CodDetalle AND mmd1.CodMaestro = 'TIPOFALTAS')
				INNER JOIN mastmiscelaneosdet mmd2 ON (rp.TipoPermiso = mmd2.CodDetalle AND mmd2.CodMaestro = 'PERMISOS')
			WHERE
				CodPersona = '".$field_empleado['CodPersona']."'
				$filtro_permiso
			ORDER BY rp.FechaDesde, rp.HoraDesde";
	$query_eventos = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_eventos) != 0) {
		if ($dependencia != $field_empleado['CodDependencia']) {
			$dependencia = $field_empleado['CodDependencia'];
			
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(190, 5, utf8_decode($field_empleado['Dependencia']), 0, 1, 'L');	
		}	
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(5, 5);
		$pdf->Cell(15, 5, $field_empleado['CodEmpleado'], 0, 0, 'L');
		$pdf->Cell(15, 5, number_format($field_empleado['Ndocumento'], 0, '', '.'), 0, 0, 'R');	
		$pdf->Cell(70, 5, utf8_decode($field_empleado['Busqueda']), 0, 0, 'L');	
		$pdf->Cell(75, 5, utf8_decode($field_empleado['DescripCargo']), 0, 1, 'L');
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(10, 4);
		$pdf->Cell(30, 4, 'Tipo Falta', 1, 0, 'C');
		$pdf->Cell(60, 4, 'Tipo Permiso', 1, 0, 'C');
		$pdf->Cell(35, 4, 'Fecha', 1, 0, 'C');
		$pdf->Cell(20, 4, 'Hora', 1, 0, 'C');
		$pdf->Cell(15, 4, 'Dias', 1, 0, 'C');
		$pdf->Cell(15, 4, 'Horas', 1, 1, 'C');
		while ($field_eventos = mysql_fetch_array($query_eventos)) {
			list($a, $m, $d)=SPLIT( '[/.-]', $field_eventos['FechaDesde']); $fdesde = "$d/$m/$a";
			list($a, $m, $d)=SPLIT( '[/.-]', $field_eventos['FechaHasta']); $fhasta = "$d/$m/$a";
			list($h, $m, $s)=SPLIT( '[:.:]', $field_eventos['HoraDesde']); 
			if ($h > 12) { 
				$hora = (int) $h;
				$hora = $hora -12;
				if ($hora < 10) $hora = "0$hora";
				$hdesde = "$hora:$m";
			} else $hdesde = "$h:$m";
			list($h, $m, $s)=SPLIT( '[:.:]', $field_eventos['HoraHasta']);
			if ($h > 12) { 
				$hora = (int) $h;
				$hora = $hora -12;
				if ($hora < 10) $hora = "0$hora";
				$hhasta = "$hora:$m";
			} else $hhasta = "$h:$m";
			if ($field_eventos['TotalMinutos'] < 10) $totalminutos = "0".$field_eventos['TotalMinutos'];
			else $totalminutos = $field_eventos['TotalMinutos'];
			$sum_dias += $field_eventos['TotalDias'];
			$sum_horas += $field_eventos['TotalHoras'];
			$sum_minutos += $field_eventos['TotalMinutos'];
			$pdf->SetFont('Arial', '', 8);
			$pdf->Cell(10, 4);
			$pdf->Cell(30, 4, $field_eventos['NomTipoFalta'], 0, 0, 'C');
			$pdf->Cell(60, 4, $field_eventos['NomTipoPermiso'], 0, 0, 'L');
			$pdf->Cell(35, 4, $fdesde.' - '.$fhasta, 0, 0, 'C');
			$pdf->Cell(20, 4, $hdesde.' - '.$hhasta, 0, 0, 'C');
			$pdf->Cell(15, 4, $field_eventos['TotalDias'], 0, 0, 'R');
			$pdf->Cell(15, 4, $field_eventos['TotalHoras'].':'.$totalminutos, 0, 1, 'R');
			$pdf->Ln(2);
		}
		if ($sum_minutos >= 60) {
			$minutos_a_horas = (int) ($sum_minutos / 60);
			$minutos_diferencia = $sum_minutos - ($minutos_a_horas * 60);
			$sum_horas = $sum_horas + $minutos_a_horas;
			$sum_minutos = $minutos_diferencia;
		}
		if ($sum_minutos < 10) $sum_minutos = "0$sum_minutos";
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(155, 4);
		$pdf->Cell(15, 6, $sum_dias, 0, 0, 'R');
		$pdf->Cell(15, 6, $sum_horas.':'.$sum_minutos, 0, 1, 'R');
		$sum_dias = 0;
		$sum_horas = 0;
		$sum_minutos = 0;
		$pdf->Ln(7);
	}
}
//---------------------------------------------------
$pdf->Output();
?>