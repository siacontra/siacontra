<?php
extract($_POST);
extract($_GET);
//	------------------------------------
include("../lib/fphp.php");
include("lib/fphp.php");
//	------------------------------------
if ($fordenar != "") { $orderby = "ORDER BY $fordenar"; }
if ($fedoreg != "") { $cedoreg = "checked"; $filtro.=" AND (ee.Estado = '".$fedoreg."')"; }
if ($fbuscar != "") {
	$cbuscar = "checked";
	$filtro.=" AND (e.CodEmpleado LIKE '%".$fbuscar."%' OR
					p.NomCompleto LIKE '%".$fbuscar."%' OR
					pu.DescripCargo LIKE '%".$fbuscar."%' OR
					ee.Periodo LIKE '%".$fbuscar."%' OR
					ee.Estado LIKE '%".$fbuscar."%')";
}
if ($forganismo != "") { $filtro.=" AND (e.CodOrganismo = '".$forganismo."')"; }
if ($fdependencia != "") { $filtro.=" AND (e.CodDependencia = '".$fdependencia."')"; }
if ($fempleado != "") { $filtro.=" AND (ee.CodPersona = '".$fpersona."')"; }
if ($fevaluador != "") { $filtro.=" AND (ee.Evaluador = '".$fevaluador."')"; }
if ($fperiodo != "") { $filtro.=" AND (ee.Periodo = '".$fperiodo."')"; }
//	------------------------------------
//	consulto
$sql = "SELECT
			ee.Estado,
			p.NomCompleto,
			p.Ndocumento,
			e.CodEmpleado,
			e.Fingreso,
			pu1.DescripCargo,
			pu2.DescripCargo AS DescripCargoTemp
		FROM
			rh_evaluacionempleado ee
			INNER JOIN mastpersonas p ON (ee.CodPersona = p.CodPersona)
			INNER JOIN mastempleado e ON (p.CodPersona = e.CodPersona)
			LEFT JOIN rh_puestos pu1 ON (e.CodCargo = pu1.CodCargo)
			LEFT JOIN rh_puestos pu2 ON (e.CodCargoTemp = pu2.CodCargo)
		WHERE 1 $filtro
		$orderby";
$query_empleado = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_empleado) != 0) $field_empleado = mysql_fetch_array($query_empleado):
//	------------------------------------
$content = '
<style type="text/css">
    table.page_header {
		border:none; 
		padding:2mm;
		font-weight:bold;
		font-size:12px;
		font-family:Arial, Helvetica, sans-serif;
	}
    table.page_footer { 
		border:none; 
		padding:2mm;
	}
	.label_titulo {
		font-weight:bold;
		font-size:12px;
		font-family:Arial, Helvetica, sans-serif;
		text-align:center;
		line-height:15px;
	}
	.label_celda {
		font-size:10px;
		font-family:Arial, Helvetica, sans-serif;
		line-height:10px;
	}
	.valor {
		font-size:10px;
		font-family:Arial, Helvetica, sans-serif;
		line-height:10px;
	}
	.tabla {
		border-collapse:collapse;
		border:1px solid #000;
	}
	
	
    div.note { border:solid 1mm #DDDDDD; background-color: #EEEEEE;padding:2mm; border-radius:2mm; }
    ul.main { width:95%; list-style-type:square; }
    ul.main li { padding-bottom: 2mm; }
    h1 { text-align:center; font-size:20mm; }
    h3 { text-align:center; font-size:14mm; }
</style>

<page backbottom="20mm" backleft="10mm" backright="10mm">
	<page_header>
	</page_header>
	
	<page_footer>
	<table class="page_footer" align="center">
		<tr>
			<td valign="top">Página [[page_cu]] de [[page_nb]]</td>
		</tr>
	</table>
	</page_footer>
	
	<table class="page_header" align="center">
	<tr>
		<td width="50">
			<img src="../imagenes/logos/contraloria.jpg" height="48" width="48" />
		</td>
		<td width="550" style="font-weight:bold; line-height:17px;">
			República Bolivariana de Venezuela<br />
			Contraloría del Estado Delta Amacuro<br />
			Dirección de Recursos Humanos
		</td>
	</tr>
	</table>
	<br />
	<br />
	
	<div class="label_titulo">PERIODO EVALUADO</div>
	<table align="center" class="tabla" border="1">
		<tr>
			<td width="300" align="center">
				<span class="label_celda">DESDE</span><br />
			</td>
			<td width="300" align="center">
				<span class="label_celda">HASTA</span><br />
			</td>
		</tr>
	</table>
	
	
	<table>
		<tr>
			<td width="100">
				Nombre:
			</td>
			<td>
				'.$field['NomCompleto'].'
			</td>
			<td width="100">
				Cédula:
			</td>
			<td>
				'.$field['Ndocumento'].'
			</td>
		</tr>
		<tr>
			<td>
				Cargo:
			</td>
			<td>
				'.$field['DescripCargo'].'
			</td>
			<td>
				Fecha de Ingreso:
			</td>
			<td>
				'.$field['Fingreso'].'
			</td>
		</tr>
	</table>
</page>
';

require_once('../lib/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('P','A4','fr');
$html2pdf->WriteHTML($content);
$html2pdf->Output('reporte.pdf');
?>
