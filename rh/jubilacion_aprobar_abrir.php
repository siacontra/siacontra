<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
$fecha_actual = date("d-m-Y");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<style type="text/css">
<!--
UNKNOWN {
        FONT-SIZE: small
}
#header {
        FONT-SIZE: 93%; BACKGROUND: url(bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal
}
#header UL {
        PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none
}
#header LI {
        PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px
}
#header A {
        PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none
}
#header A {
        FLOAT: none
}
#header A:hover {
        COLOR: #333
}
#header #current {
        BACKGROUND-IMAGE: url(left_on.gif)
}
#header #current A {
        BACKGROUND-IMAGE: url(right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333
}
-->
</style>
</head>

<body>
<?
if ($accion == "PROCESAR") {
	$titulo_pagina = "Proceso de";
	$titulo_boton = "Procesar";
	$daprobado = "disabled";
	$planilla = 1;
}
elseif ($accion == "APROBAR") {
	$titulo_pagina = "Aprobar";
	$titulo_boton = "Aprobar";
	$dprocesado = "disabled";
	$planilla = 0;
}
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo_pagina?> Jubilaci&oacute;n</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'jubilacion_aprobar_listado.php?limit=0');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="jubilacion_aprobar_listado.php" method="POST" onsubmit="return verificarProcesoJubilacion(this, '<?=$accion?>');">
<input name="chkorganismo" type="hidden" id="chkorganismo" value="<?=$chkorganismo?>" />
<input name="forganismo" type="hidden" id="forganismo" value="<?=$forganismo?>" />
<input name="chkedoreg" type="hidden" id="chkedoreg" value="<?=$chkedoreg?>" />
<input name="fedoreg" type="hidden" id="fedoreg" value="<?=$fedoreg?>" />
<input name="chkdependencia" type="hidden" id="chkdependencia" value="<?=$chkdependencia?>" />
<input name="fdependencia" type="hidden" id="fdependencia" value="<?=$fdependencia?>" />
<input name="chksittra" type="hidden" id="chksittra" value="<?=$chksittra?>" />
<input name="fsittra" type="hidden" id="fsittra" value="<?=$fsittra?>" />
<input name="chktiponom" type="hidden" id="chktiponom" value="<?=$chktiponom?>" />
<input name="ftiponom" type="hidden" id="ftiponom" value="<?=$ftiponom?>" />
<input name="chkbuscar" type="hidden" id="chkbuscar" value="<?=$chkbuscar?>" />
<input name="sltbuscar" type="hidden" id="sltbuscar" value="<?=$sltbuscar?>" />
<input name="fbuscar" type="hidden" id="fbuscar" value="<?=$fbuscar?>" />
<input name="chktipotra" type="hidden" id="chktipotra" value="<?=$chktipotra?>" />
<input name="ftipotra" type="hidden" id="ftipotra" value="<?=$ftipotra?>" />
<input name="chkndoc" type="hidden" id="chkndoc" value="<?=$chkndoc?>" />
<input name="fndoc" type="hidden" id="fndoc" value="<?=$fndoc?>" />
<input name="chkordenar" type="hidden" id="chkordenar" value="<?=$chkordenar?>" />
<input name="fordenar" type="hidden" id="fordenar" value="<?=$fordenar?>" />
<input name="chkpersona" type="hidden" id="chkpersona" value="<?=$chkpersona?>" />
<input name="sltpersona" type="hidden" id="sltpersona" value="<?=$sltpersona?>" />
<input name="fpersona" type="hidden" id="fpersona" value="<?=$fpersona?>" />
<input name="chkedad" type="hidden" id="chkedad" value="<?=$chkedad?>" />
<input name="sltedad" type="hidden" id="sltedad" value="<?=$sltedad?>" />
<input name="fedad" type="hidden" id="fedad" value="<?=$fedad?>" />
<input name="chkingreso" type="hidden" id="chkingreso" value="<?=$chkingreso?>" />
<input name="fingresod" type="hidden" id="fingresod" value="<?=$fingresod?>" />
<input name="fingresoh" type="hidden" id="fingresoh" value="<?=$fingresoh?>" />
<input name="limit" type="hidden" id="limit" value="<?=$limit?>" />
<input name="filtro" type="hidden" id="filtro" value="<?=$filtro?>" />
<input name="ordenar" type="hidden" id="ordenar" value="<?=$ordenar?>" />
<input name="registro" type="hidden" id="registro" value="<?=$registro?>" />
<?php
include("fphp.php");
connect();
?>

<table width="750" align="center">
  <tr>
		<td>
			<div id="header">
			<ul>
			<!-- CSS Tabs -->
			<li><a onclick="document.getElementById('tab1').style.display='block'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none';" href="#">General</a></li>
			<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none';" href="#" disabled>Antecedentes de Servicio</a></li>
			<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='block'; document.getElementById('tab4').style.display='none';" href="#" disabled>Relaci&oacute;n de Sueldos</a></li>
			<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='block';" href="#" disabled>Jubilaci&oacute;n</a></li>
			</ul>
			</div>
		</td>
	</tr>
</table>

<div id="tab1" style="display:block;">
<div style="width:750px" class="divFormCaption">Datos del Empleado</div>
<?php
$sql = "SELECT  
			mp.NomCompleto, 
			mp.Ndocumento, 
			mp.Fnacimiento, 
			mp.Sexo, 
			me.CodEmpleado, 
			me.CodOrganismo, 
			me.CodDependencia, 
			me.Fingreso, 
			me.SueldoActual, 
			me.CodTipoNom, 
			me.CodTipoTrabajador
		FROM 
			mastpersonas mp 
			INNER JOIN mastempleado me ON (mp.CodPersona = me.CodPersona)
		WHERE mp.CodPersona = '".$registro."'";
$query_datos = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_datos) != 0) $field_datos = mysql_fetch_array($query_datos);
list($a, $m, $d)=SPLIT( '[/.-]', $field_datos['Fnacimiento']); $fnacimiento = $d."-".$m."-".$a;
list($a, $m, $d)=SPLIT( '[/.-]', $field_datos['Fingreso']); $fingreso = $d."-".$m."-".$a;
list ($a, $m, $d) = getEdadAMD($fnacimiento, $fecha_actual); $edad_empleado = $a;
?>
<table width="750" class="tblForm">
	<tr>
		<td class="tagForm" width="150">Empleado:</td>
		<td>
			<input name="codpersona" type="hidden" id="codpersona" value="<?=$registro?>" />
			<input name="codempleado" type="text" id="codempleado" size="6" value="<?=$field_datos['CodEmpleado']?>" disabled="disabled" />
			<input name="nomempleado" type="text" id="nomempleado" size="50" value="<?=$field_datos['NomCompleto']?>" disabled="disabled" />
		</td>
		<td class="tagForm" width="150">Sexo:</td>
		<td>
			<select name="sexo" id="sexo" style="width:125px;">
				<?=getSexo($field_datos['Sexo'], 1);?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tagForm" width="150">Nro. Documento:</td>
		<td><input name="ndoc" type="text" id="ndoc" size="15" value="<?=$field_datos['Ndocumento']?>" disabled="disabled" /></td>
		<td class="tagForm" width="150">Fecha de Nacimiento:</td>
		<td>
			<input name="edad_empleado" type="hidden" id="edad_empleado" value="<?=$edad_empleado?>" />
			<input name="fnac" type="text" id="fnac" size="15" value="<?=$fnacimiento?>" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm" width="150">Fecha de Ingreso:</td>
		<td><input name="fingreso" type="text" id="fingreso" size="15" value="<?=$fingreso?>" disabled="disabled" /></td>
		<td class="tagForm" width="150">Sueldo Actual:</td>
		<td><input name="sueldo" type="text" id="sueldo" size="15" value="<?=number_format($field_datos['SueldoActual'], 2, ',', '.')?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm" width="150">Organismo:</td>
		<td>
			<select name="organismo" id="organismo" class="selectBig">
				<?=getOrganismos($field_datos['CodOrganismo'], 1);?>
			</select>
		</td>
		<td class="tagForm" width="150">Edad:</td>
		<td><input type="text" size="15" value="<?=$edad_empleado?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm" width="150">Dependencia:</td>
		<td>
			<select name="dependencia" id="dependencia" class="selectBig">
				<?=getDependencias($field_datos['CodDependencia'], $field_datos['CodOrganismo'], 1);?>
			</select>
		</td>
	</tr>
</table>
</div>

<div id="tab2" style="display:none;">
<table width="750" class="tblLista">
	<tr class="trListaHead">
		<th scope="col">Organismo</th>
		<th scope="col" width="75" >Fecha de Ingreso</th>
		<th scope="col" width="75">Fecha de Egreso</th>
		<th scope="col" width="50">A&ntilde;os</th>
		<th scope="col" width="50">Meses</th>
		<th scope="col" width="50">Dias</th>
	</tr>

	<?php
	//	Consulto los antecedentes del empleado...
	$sql = "SELECT * FROM rh_empleado_antecedentes WHERE CodPersona = '".$registro."' ORDER BY FIngreso DESC";
	$query_ant = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_ant = mysql_fetch_array($query_ant)) {
		list($a, $m, $d)=SPLIT( '[/.-]', $field_ant['FIngreso']); $fingreso=$d."-".$m."-".$a;
		list($a, $m, $d)=SPLIT( '[/.-]', $field_ant['FEgreso']); $fegreso=$d."-".$m."-".$a;
		
		list ($a, $m, $d) = getEdadAMD($fingreso, $fegreso);
		$anios = $a; 
		$meses = $m; 
		$dias = $d;
		
		if ($dias == 30) { $meses++; $dias = 0; }
		if ($meses == 13) { $anios++; $meses = 0; }
		
		$total_anios += (int) $anios;
		$total_meses += (int) $meses;
		$total_dias += (int) $dias;
		
		?>
		<tr class="trListaBody">
			<td><?=($field_ant['Organismo'])?></td>
			<td align="center"><?=$fingreso?></td>
			<td align="center"><?=$fegreso?></td>
			<td align="center"><?=$anios?></td>
			<td align="center"><?=$meses?></td>
			<td align="center"><?=$dias?></td>
		</tr>
		<?
	}
	
	if ($total_dias >= 30) {
		$div = (int) ($total_dias / 30);
		$total_meses = $total_meses + $div;
		$total_dias = $total_dias - ($div * 30);
	}
	if ($total_meses >= 12) {
		$div = (int) ($total_meses / 12);
		$total_anios = $total_anios + $div;
		$total_meses = $total_meses - ($div * 12);
	}
	?>
	<tr>
		<td colspan="3">&nbsp;</td>
		<td align="center" class="trListaBody2"><?=$total_anios?></td>
		<td align="center" class="trListaBody2"><?=$total_meses?></td>
		<td align="center" class="trListaBody2"><?=$total_dias?></td>
	</tr>
	<? if ($total_meses >= 8) { 
		$total_total_anios = $total_anios + 1; 
		?>
		<tr>
			<td colspan="3">&nbsp;</td>
			<td align="center" class="trListaBody2"><?=$total_total_anios?></td>
			<td align="center" class="trListaBody2">0</td>
			<td align="center" class="trListaBody2"><?=$total_dias?></td>
		</tr>
	<? } else $total_total_anios = $total_anios; ?>
</table>
<input name="anios_servicio" type="hidden" id="anios_servicio" value="<?=$total_total_anios?>" />
</div>

<div id="tab3" style="display:none;">
<?
$mes_actual = date("m");
$ano_actual = date("Y");

$m = (int) $mes_actual - 1; 
$a = (int) $ano_actual;

if ($m == 0) { $m = 12; $a--; }
if ($m < 10) $m_fin = "0$m";
$periodo_fin = "$a-$m_fin";

$m_ini = $m;
$a_ini = $a; 
for ($i=1; $i<=24; $i++) {
	$m_ini--;
	if ($m_ini == 0) { $m_ini = 12; $a_ini--; }
}
if ($m_ini < 10) $m_ini = "0$m_ini";
$periodo_ini = "$a_ini-$m_ini";
?>
<table class="tblLista">
	<tr>
		<td>
			<table>
				<tr class="trListaHead">
					<th scope="col" width="30">#</th>
					<th scope="col" width="70">Periodo</th>
					<th scope="col" width="135">Sueldo</th>
				</tr>
			</table>
		</td>
		
		<td>
			<table>
				<tr class="trListaHead">
					<th scope="col" width="30">#</th>
					<th scope="col" width="70">Periodo</th>
					<th scope="col" width="135">Sueldo</th>
				</tr>
			</table>
		</td>
		
		<td>
			<table>
				<tr class="trListaHead">
					<th scope="col" width="30">#</th>
					<th scope="col" width="70">Periodo</th>
					<th scope="col" width="135">Sueldo</th>
				</tr>
			</table>
		</td>
		
	</tr>
	
	<tr>
		<td>
			<table>
				<?php
				$i = 0;
				//	Consulto los antecedentes del empleado...
				$sql = "SELECT 
							Sueldo, 
							Periodo 
						FROM 
							rh_sueldos 
						WHERE 
							CodPersona = '".$registro."'
						ORDER BY Periodo Desc LIMIT 0, 8";
				$query = mysql_query($sql) or die ($sql.mysql_error());
				while ($field = mysql_fetch_array($query)) { 
					$i++;
					$SUMA_BASICO += $field['Sueldo'];
					?>
					<tr class="trListaBody">
						<td width="30" align="center" style="background-color:#666666; color:#FFFFFF;"><?=$i?></td>
						<td width="70" align="center"><?=$field['Periodo']?></td>
						<td width="135" align="right"><?=number_format($field['Sueldo'], 2, ',', '.')?></td>
					</tr>
				<? } ?>
			</table>
		</td>
		
		<td>
			<table>
				<?php
				//	Consulto los antecedentes del empleado...
				$sql = "SELECT 
							Sueldo, 
							Periodo 
						FROM 
							rh_sueldos 
						WHERE 
							CodPersona = '".$registro."'
						ORDER BY Periodo Desc LIMIT 8, 8";
				//$sql = "SELECT * FROM rh_sueldos WHERE CodPersona = '".$registro."' ORDER BY Periodo DESC LIMIT 8, 8";
				$query = mysql_query($sql) or die ($sql.mysql_error());
				while ($field = mysql_fetch_array($query)) { 
					$i++;
					$SUMA_BASICO += $field['Sueldo'];
					?>
					<tr class="trListaBody">
						<td width="30" align="center" style="background-color:#666666; color:#FFFFFF;"><?=$i?></td>
						<td width="70" align="center"><?=$field['Periodo']?></td>
						<td width="135" align="right"><?=number_format($field['Sueldo'], 2, ',', '.')?></td>
					</tr>
				<? } ?>
			</table>
		</td>
		
		<td>
			<table>
				<?php
				//	Consulto los antecedentes del empleado...
				$sql = "SELECT 
							Sueldo, 
							Periodo 
						FROM 
							rh_sueldos 
						WHERE 
							CodPersona = '".$registro."'
						ORDER BY Periodo Desc LIMIT 16, 8";
				//$sql = "SELECT * FROM rh_sueldos WHERE CodPersona = '".$registro."' ORDER BY Periodo DESC LIMIT 16, 8";
				$query = mysql_query($sql) or die ($sql.mysql_error());
				while ($field = mysql_fetch_array($query)) { 
					$i++;
					$SUMA_BASICO += $field['Sueldo'];
					?>
					<tr class="trListaBody">
						<td width="30" align="center" style="background-color:#666666; color:#FFFFFF;"><?=$i?></td>
						<td width="70" align="center"><?=$field['Periodo']?></td>
						<td width="135" align="right"><?=number_format($field['Sueldo'], 2, ',', '.')?></td>
					</tr>
				<? } ?>
			</table>
		</td>
		
	</tr>
</table>

<table class="tblLista">
	<tr>
		<td>
			<table>
				<tr class="trListaHead">
					<th scope="col" width="30">#</th>
					<th scope="col" width="70">Periodo</th>
					<th scope="col" width="135">Prima Antiguedad</th>
				</tr>
			</table>
		</td>
		
		<td>
			<table>
				<tr class="trListaHead">
					<th scope="col" width="30">#</th>
					<th scope="col" width="70">Periodo</th>
					<th scope="col" width="135">Prima Antiguedad</th>
				</tr>
			</table>
		</td>
		
		<td>
			<table>
				<tr class="trListaHead">
					<th scope="col" width="30">#</th>
					<th scope="col" width="70">Periodo</th>
					<th scope="col" width="135">Prima Antiguedad</th>
				</tr>
			</table>
		</td>
		
	</tr>
	
	<tr>
		<td>
			<table>
				<?php
				$j = 0; 
				//	Consulto los antecedentes del empleado...
				$sql = "SELECT 
							Monto AS Prima, 
							Periodo 
						FROM 
							pr_tiponominaempleadoconcepto 
						WHERE 
							CodConcepto = '0023' AND 
							CodPersona = '".$registro."'
						ORDER BY Periodo Desc LIMIT 0, 8";
				//$sql = "SELECT * FROM rh_sueldos WHERE CodPersona = '".$registro."' ORDER BY Periodo DESC LIMIT 0, 8";
				$query = mysql_query($sql) or die ($sql.mysql_error());
				while ($field = mysql_fetch_array($query)) { 
					$j++;
					$SUMA_ANIOS += $field['Prima'];
					?>
					<tr class="trListaBody">
						<td width="30" align="center" style="background-color:#666666; color:#FFFFFF;"><?=$j?></td>
						<td width="70" align="center"><?=$field['Periodo']?></td>
						<td width="135" align="right"><?=number_format($field['Prima'], 2, ',', '.')?></td>
					</tr>
				<? } ?>
			</table>
		</td>
		
		<td>
			<table>
				<?php
				//	Consulto los antecedentes del empleado...
				$sql = "SELECT 
							Monto AS Prima, 
							Periodo
						FROM 
							pr_tiponominaempleadoconcepto 
						WHERE 
							CodConcepto = '0023' AND 
							CodPersona = '".$registro."'
						ORDER BY Periodo Desc LIMIT 8, 8";
				//$sql = "SELECT * FROM rh_sueldos WHERE CodPersona = '".$registro."' ORDER BY Periodo DESC LIMIT 8, 8";
				$query = mysql_query($sql) or die ($sql.mysql_error());
				while ($field = mysql_fetch_array($query)) { 
					$j++;
					$SUMA_ANIOS += $field['Prima'];
					?>
					<tr class="trListaBody">
						<td width="30" align="center" style="background-color:#666666; color:#FFFFFF;"><?=$j?></td>
						<td width="70" align="center"><?=$field['Periodo']?></td>
						<td width="135" align="right"><?=number_format($field['Prima'], 2, ',', '.')?></td>
					</tr>
				<? } ?>
			</table>
		</td>
		
		<td>
			<table>
				<?php
				//	Consulto los antecedentes del empleado...
				$sql = "SELECT 
							Monto AS Prima, 
							Periodo
						FROM 
							pr_tiponominaempleadoconcepto 
						WHERE 
							CodConcepto = '0023' AND 
							CodPersona = '".$registro."'
						ORDER BY Periodo Desc LIMIT 16, 8";
				//$sql = "SELECT * FROM rh_sueldos WHERE CodPersona = '".$registro."' ORDER BY Periodo DESC LIMIT 16, 8";
				$query = mysql_query($sql) or die ($sql.mysql_error());
				while ($field = mysql_fetch_array($query)) { 
					$j++;
					$SUMA_ANIOS += $field['Prima'];
					?>
					<tr class="trListaBody">
						<td width="30" align="center" style="background-color:#666666; color:#FFFFFF;"><?=$j?></td>
						<td width="70" align="center"><?=$field['Periodo']?></td>
						<td width="135" align="right"><?=number_format($field['Prima'], 2, ',', '.')?></td>
					</tr>
				<? } ?>
			</table>
		</td>
		
	</tr>
</table>
<?php
//	---------------------------------------------------------------------------
//	Aqui valido si el empleado cumple con las condiciones para su jubilacion...
//	---------------------------------------------------------------------------

// Obtengo los valores de los parametros
$sql = "SELECT 
			ValorParam AS EDADJUBM, 
			(SELECT ValorParam FROM mastparametros WHERE ParametroClave = 'EDADJUBF') AS EDADJUBF, 
			(SELECT ValorParam FROM mastparametros WHERE ParametroClave = 'MINSERVSI') AS MINSERVSI, 
			(SELECT ValorParam FROM mastparametros WHERE ParametroClave = 'MINSERVNO') AS MINSERVNO
		FROM 
			mastparametros
		WHERE
			ParametroClave = 'EDADJUBM'";
$query_parametro = mysql_query($sql) or die ($sql.mysql_error());
$field_parametro = mysql_fetch_array($query_parametro) or die ($sql.mysql_error());
//	---------------------------------------------------------------------------

//	
$anios_exceso = $total_total_anios - $field_parametro['MINSERVSI'];
$edad_exceso = $edad_empleado + $anios_exceso;

if ((((($field_datos['Sexo'] == "M" && $edad_exceso >= $field_parametro['EDADJUBM']) || ($field_datos['Sexo'] == "F" && $edad_exceso >= $field_parametro['EDADJUBF'])) && ($total_total_anios >= $field_parametro['MINSERVSI'])) || ($total_total_anios >= $field_parametro['MINSERVNO'])) && ($i >= 24) && ($j >= 24)) {
	$btProcesar = "";
	$divCumple = "";
	$divNoCumple = "display:none";
	
	if (($field_datos['Sexo'] == "M" && $edad_empleado >= $field_parametro['EDADJUBM']) || ($field_datos['Sexo'] == "F" && $edad_empleado >= $field_parametro['EDADJUBF'])) {
		$divPorExceso = "display:none";
	} else {
		$divPorExceso = "";
		if ($field_datos['Sexo'] == "M") {
			$resta_exceso = $field_parametro['EDADJUBM'] - $edad_empleado;
		} else {
			$resta_exceso = $field_parametro['EDADJUBF'] - $edad_empleado;
		}
		
		$total_total_anios -= $resta_exceso;
	}
	
} else {
	$btProcesar = "style = 'display:none'";
	$divCumple = "display:none";
	$divNoCumple = "";
}



//	CALCULO EL SUELDO DE JUBILACION
$sql = "SELECT * FROM mastparametros WHERE ParametroClave = 'MAXCOEJUB'";
$query_coe = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_coe) != 0) { 
	$field_coe = mysql_fetch_array($query_coe);
	$max_coeficiente = number_format($field_coe['ValorParam'], 2, '.', '');
} else $max_coeficiente = 100;

$_PORCENTAJE_JUBILACION = ($total_total_anios * 2.5);
$_COEFICIENTE_JUBILACION = ($total_total_anios * 2.5) / 100;
$coeficiente = number_format($_COEFICIENTE_JUBILACION, 2, '.', '');

if ($_PORCENTAJE_JUBILACION > $max_coeficiente) $coeficiente = number_format(($max_coeficiente / 100), 2, '.', '');

$_MONTO = ($SUMA_BASICO + $SUMA_ANIOS) / 24; $_MONTO = number_format($_MONTO, 2, '.', '');
$_MONTO = $_MONTO * $coeficiente;
?>

<table width="763" class="tblForm">
	<tr>
		<td class="tagForm" width="75">Porcentaje:</td>
		<td><input type="text" size="5" value="<?=number_format(($coeficiente * 100), 2, ',', '.')."%"?>" disabled="disabled" /></td>
		<td class="tagForm" width="75">Total Sueldos:</td>
		<td><input type="text" size="10" value="<?=number_format($SUMA_BASICO, 2, ',', '.')?>" disabled="disabled" /></td>
		<td class="tagForm" width="75">Total Prima:</td>
		<td><input type="text" size="10" value="<?=number_format($SUMA_ANIOS, 2, ',', '.')?>" disabled="disabled" /></td>
		<td class="tagForm" width="75">Total:</td>
		<td><input type="text" size="10" value="<?=number_format(($SUMA_BASICO + $SUMA_ANIOS), 2, ',', '.')?>" disabled="disabled" /></td>
		<td class="tagForm" width="75">Sueldo Jubilaci&oacute;n:</td>
		<td><input type="text" size="10" value="<?=number_format($_MONTO, 2, ',', '.')?>" disabled="disabled" /></td>
	</tr>
</table>
</div>

<div id="tab4" style="display:none;">
<div style="width:750px" class="divFormCaption">Datos Jubilacion</div>
<?
$sql = "SELECT
			*
		FROM
			rh_proceso_jubilacion
		WHERE 
			CodPersona = '".$registro."'";
$query_jub = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_jub) != 0) {
	$field_jub = mysql_fetch_array($query_jub);
	$procesadopor = $field_jub['ProcesadoPor'];
	list($a, $m, $d)=SPLIT( '[/.-]', $field_jub['FechaProcesado']); $fechaprocesado = "$d-$m-$a";
	$aprobadopor = $_SESSION['NOMBRE_USUARIO_ACTUAL'];
	$fechaaprobado = date("d-m-Y");
	$obsprocesado = $field_jub['ObsProcesado'];
	$obsaprobado = $field_jub['ObsAprobado'];
	$status = $field['Estado'];
	if ($status == "P") $estado = "PROCESADO"; else $estado = "APROBADO";
} else {
	$procesadopor = $_SESSION['NOMBRE_USUARIO_ACTUAL'];
	$fechaprocesado = date("d-m-Y");
	$status = "";
	$estado = "SIN PROCESAR";
}
?>
<table width="750" class="tblForm">
	<tr>
		<td class="tagForm" width="150">Estado:</td>
		<td colspan="3">
			<input name="status" id="status" type="hidden" value="<?=$status?>" disabled="disabled" />
			<input type="text" size="20" value="<?=$estado?>" readonly />
		</td>
	</tr>
	<tr>
		<td class="tagForm" width="150">Procesado Por:</td>
		<td>
			<input type="text" name="procesadopor" id="procesadopor" size="50" value="<?=$procesadopor?>" disabled="disabled" />
		</td>
		<td class="tagForm" width="150">Fecha:</td>
		<td>
			<input type="text" name="fechaprocesado" id="fechaprocesado" size="15" value="<?=$fechaprocesado?>" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm" width="150">Observaciones:</td>
		<td colspan="3">
			<textarea name="obsprocesado" id="obsprocesado" <?=$dprocesado?> style="width:98%; height:50px;"><?=$obsprocesado?></textarea>
		</td>
	</tr>
	<tr>
		<td class="tagForm" width="150">Aprobado Por:</td>
		<td>
			<input type="text" name="aprobadopor" id="aprobadopor" size="50" value="<?=$aprobadopor?>" disabled="disabled" />
		</td>
		<td class="tagForm" width="150">Fecha:</td>
		<td>
			<input type="text" name="fechaaprobado" id="fechaaprobado" size="15" value="<?=$fechaaprobado?>" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm" width="150">Observaciones:</td>
		<td colspan="3">
			<textarea name="obsaprobado" id="obsaprobado" <?=$daprobado?> style="width:98%; height:50px;"><?=$field_jub['ObsAprobado']?></textarea>
		</td>
	</tr>
</table>

<div style="width:750px" class="divFormCaption">Datos de la Planilla</div>
<table width="750" class="tblForm">
	<tr>
		<td class="tagForm" width="150">N&oacute;mina:</td>
		<td>
			<select name="nomina" id="nomina" class="selectSma">
				<?=getTNomina($field_datos['CodTipoNom'], $planilla)?>
			</select>
		</td>
		<td class="tagForm" width="150">Tipo de Trabajador:</td>
		<td>
			<select name="tipo_trabajador" id="tipo_trabajador" class="selectSma">
				<?=getTTrabajador($field_datos['CodTipoTrabajador'], $planilla)?>
			</select>
		</td>
	</tr>
</table>

<div style="width:750px" class="divFormCaption">Datos del Cese</div>
<table width="750" class="tblForm">
	<tr>
    <td class="tagForm">Situaci&oacute;n del Trab.:</td>
    <td>
		<input name="sittra" id="activo" type="radio" value="A" checked onclick="setCese(this.form);" <?=$daprobado?> /> Activo
		<input name="sittra" id="inactivo" type="radio" value="I" onclick="setCese(this.form);" <?=$daprobado?> /> Inactivo
	</td>
	<td class="tagForm">Motivo del Cese:</td>
	<td>
		<select name="tcese" id="tcese" class="selectBig" disabled="disabled">
			<?=getTCese("", $planilla)?>
		</select>
	</td>
	</tr>
	<tr>
		<td class="tagForm">Fecha de Cese:</td>
		<td><input name="fcese" type="text" id="fcese" size="15" maxlength="10" disabled /><i>(dd-mm-yyyy)</i></td>
		<td class="tagForm">Explicaci&oacute;n:</td>
		<td><input name="explicacion" type="text" id="explicacion" size="45" maxlength="30" disabled /></td>
	</tr>
</table>

</div>

<center> 
<input type="submit" value="<?=$titulo_boton?> Jubilaci&oacute;n" <?=$btProcesar?> />
<input type="button" value="Ver Reporte PDF" <?=$btProcesar?> onclick="pdfJubilacion();" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onclick="cargarPagina(document.getElementById('frmentrada'), 'jubilacion_aprobar_listado.php?limit=0');" />
</center>

<input type="hidden" name="coeficiente" id="coeficiente" value="<?=$_COEFICIENTE_JUBILACION?>" />
<input type="hidden" name="monto_jubilacion" id="monto_jubilacion" value="<?=$_MONTO?>" />
</form><br /> <br /> 

<div style="position:absolute; background-color: #D9FFD9; color: #003E00; width: 50%; height: 25px; left: 25%; border: 1px solid #003E00; text-align: center; vertical-align:middle; font-size:14px; font-weight:bold; <?=$divCumple?>">
	El empleado cumple los requisitos para Procesar su jubilación.
</div> 

<div style="position:absolute; background-color: #FFFFB0; color: #BB0000; width: 50%; height: 25px; left: 25%; border: 1px solid #BB0000; text-align: center; vertical-align:middle; font-size:14px; font-weight:bold; <?=$divNoCumple?>">
	El empleado no cumple con los requisitos para Procesar ni Aprobar su jubilación.</div> <br /> <br /> <br />

<div style="position:absolute; background-color: #FFFFB0; color: #003E00; width: 50%; height: 40px; left: 25%; border: 1px solid #003E00; text-align: center; vertical-align:middle; font-size:14px; font-weight:bold; <?=$divPorExceso?>">
	Al empleado se le sumaron a&ntilde;os de servicio en exceso a su edad para completar la edad reglamentaria para su jubilaci&oacute;n.
</div> 
</html>
