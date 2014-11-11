<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../../index.php");
//	------------------------------------
include("../fphp.php");
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<link type="text/css" rel="stylesheet" href="../../css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../../css/estilo.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" src="../../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/fscript.js" charset="utf-8"></script>
</head>

<body>
<!-- ui-dialog -->
<div id="cajaModal"></div>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Periodos de Vacaciones</td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="listado_periodos_vacaciones.php?" method="post">
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="ventana" id="ventana" value="<?=$ventana?>" />

<center>
<table width="100%" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
	</tr>
</table>

<div style="overflow:scroll; width:100%; height:425px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th scope="col" width="20">#</th>
		<th scope="col">Periodo</th>
		<th scope="col" width="8%">Mes Prog.</th>
		<th scope="col" width="8%">Derecho</th>
		<th scope="col" width="11%">Pendiente Periodos Ant.</th>
		<th scope="col" width="11%">Goce</th>
		<th scope="col" width="11%">Trabaj.</th>
		<th scope="col" width="11%">(Interrup.)</th>
		<th scope="col" width="11%">Total Utiliz.</th>
		<th scope="col" width="11%">Vac. Pend.</th>
	</tr>
	</thead>
    
    <tbody>
    <?php
	//	empleado
	$sql = "SELECT Fingreso
			FROM mastempleado
			WHERE CodPersona = '".$CodPersona."'";
	$query_empleado = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_empleado)) $field_empleado = mysql_fetch_array($query_empleado);
	
	//	obtengo los valores almacenados del empleado para el periodo
	$sql = "SELECT
				NroPeriodo,
				Anio,
				Mes,
				Derecho,
				PendientePeriodo,
				DiasGozados,
				DiasTrabajados,
				DiasInterrumpidos,
				DiasNoGozados,
				TotalUtilizados,
				Pendientes
			FROM rh_vacacionperiodo
			WHERE CodPersona = '".$CodPersona."' order by CodTipoNom, NroPeriodo Asc";
	$query_periodo = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$i=0;
	while ($field_periodo = mysql_fetch_array($query_periodo)) {
		$NroPeriodo[$i] = $field_periodo['NroPeriodo'];
		$Anio[$i] = $field_periodo['Anio'];
		$Mes[$i] = $field_periodo['Mes'];
		$Derecho[$i] = $field_periodo['Derecho'];
		$PendientePeriodo[$i] = $field_periodo['PendientePeriodo'];
		$DiasGozados[$i] = $field_periodo['DiasGozados'];
		$DiasTrabajados[$i] = $field_periodo['DiasTrabajados'];
		$DiasInterrumpidos[$i] = $field_periodo['DiasInterrumpidos'];
		$DiasNoGozados[$i] = $field_periodo['DiasNoGozados'];
		$TotalUtilizados[$i] = $DiasGozados[$i] - $DiasInterrumpidos[$i];
		//$TotalUtilizados[$i] = $field_periodo['TotalUtilizados'];
		$Pendientes[$i] = $field_periodo['Pendientes'];
		$i++;
	}
	
	//	tiempo de servicio
	list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
	list($AnioIngreso, $MesIngreso, $DiaIngreso) = split("[/.-]", $field_empleado['Fingreso']);
	list($Anios, $Meses, $Dias) = getTiempo(formatFechaDMA($field_empleado['Fingreso']), "$DiaActual-$MesActual-$AnioActual");
	$NroPeriodos = $Anios;
	
	//	recorro los periodos y almaceno
	$Quinquenios = 0;
	$Pendiente = 0;
	$Seleccionable = false; 
	for($i=0; $i<$NroPeriodos; $i++) {
		$Anio[$i] = $AnioIngreso + $i;
		if ($NroPeriodo[$i] == "") {
			$NroPeriodo[$i] = $i + 1;
			$Mes[$i] = $MesIngreso;
			##	obtengo los dias de derecho
			if ($i > 0 && $i % 5 == 0) ++$Quinquenios;
			$Derecho[$i] = $_PARAMETRO['DERECHO'] + $i + $Quinquenios;
			$PendientePeriodo[$i] += $Pendientes[$i-1];
			$DiasGozados[$i] = 0;
			$DiasTrabajados[$i] = 0;
			$DiasInterrumpidos[$i] = 0;
			$TotalUtilizados[$i] = 0;
		}  
		$Pendientes[$i] = $Derecho[$i] - $TotalUtilizados[$i];
		if ($Pendientes[$i] > 0) $FlagUtilizarPeriodo[$i] = "S"; else $FlagUtilizarPeriodo[$i] = "N";
		
	}
	
	for($i=$NroPeriodos-1; $i>=0; $i--) {
		if ($Pendientes[$i] > 0) {
			 $style = "font-weight:bold; text-decoration:underline;";
		} else {
			 $style = "font-weight:normal;";
		}
		
		if ($ventana == "mostrar") {
			?><tr class="trListaBody"><?
		} else {
			?><tr class="trListaBody" onclick="vacaciones_insertar_linea('<?=$NroPeriodo[$i]?>', '<?=$Anio[$i]?>', '<?=$Derecho[$i]?>', '<?=$TotalUtilizados[$i]?>', '<?=$Pendientes[$i]?>', '<?=$FlagUtilizarPeriodo[$i]?>', '<?=$i?>')" id="<?=$NroPeriodo[$i]?>"><?
		}
		?>
            <th>
                <input type="hidden" name="NroPeriodo" value="<?=$NroPeriodo[$i]?>" />
                <input type="hidden" name="Anio" value="<?=$Anio[$i]?>" />
                <?=$NroPeriodo[$i]?>
            </th>
            <td align="center" style=" <?=$style?>"><?=$Anio[$i]?> - <?=$Anio[$i]+1?></td>
            <td>
                <input type="text" name="Mes" style="text-align:center;" class="cell2" value="<?=$Mes[$i]?>" readonly />
            </td>
            <td>
                <input type="text" name="Derecho" style="text-align:right;" class="cell2" value="<?=number_format($Derecho[$i], 2, ',', '.')?>" readonly />
            </td>
            <td>
                <input type="text" name="PendientePeriodo" style="text-align:right;" class="cell2" value="<?=number_format($PendientePeriodo[$i], 2, ',', '.')?>" readonly />
            </td>
            <td>
                <input type="text" name="DiasGozados" style="text-align:right;" class="cell2" value="<?=number_format($DiasGozados[$i], 2, ',', '.')?>" readonly />
            </td>
            <td>
                <input type="text" name="DiasTrabajados" style="text-align:right;" class="cell2" value="<?=number_format($DiasTrabajados[$i], 2, ',', '.')?>" readonly />
            </td>
            <td>
                <input type="text" name="DiasInterrumpidos" style="text-align:right;" class="cell2" value="<?=number_format($DiasInterrumpidos[$i], 2, ',', '.')?>" readonly />
            </td>
            <td>
                <input type="text" name="TotalUtilizados" style="text-align:right;" class="cell2" value="<?=number_format($TotalUtilizados[$i], 2, ',', '.')?>" readonly />
            </td>
            <td>
                <input type="text" name="Pendientes" style="text-align:right;" class="cell2" value="<?=number_format($Pendientes[$i], 2, ',', '.')?>" readonly />
            </td>
        </tr>
        <?
	}
	?>
    </tbody>
</table>
</div>
</center>
</form>
</body>
</html>
