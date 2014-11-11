<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>

<body>
<?php
include("fphp.php");
connect();
//	----------------------
$sql="SELECT ValorParam AS DiasVacacion, (SELECT ValorParam FROM mastparametros WHERE ParametroClave='PAGOVACA' AND Estado='A' AND CodAplicacion='RH') AS DiasPago FROM mastparametros WHERE ParametroClave='DERECHO' AND Estado='A' AND CodAplicacion='RH'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) $field=mysql_fetch_array($query);
$dias_derecho=$field['DiasVacacion'];
$dias_pago=$field['DiasPago'];
//	----------------------
$aactual=date("Y"); $mactual=date("m"); $dactual=date("d"); $factual=date("d-m-Y");
list($dingreso, $mingreso, $aingreso)=SPLIT( '[/.-]', $fingreso);
//	----------------------
?>

<form name="frmentrada" id="frmentrada" method="post" action="vacaciones_periodo.php">
<input type="hidden" name="persona" id="persona" value="<?=$persona?>" />
<input type="hidden" name="elemento" id="elemento" />

<table width="98%" class="tblBotones">
    <tr>
    	<td width="12%">Fecha de Ingreso:</td>
    	<td width="15%"><input type="text" class="cellText" name="fingreso" id="fingreso" value="<?=$fingreso?>" readonly="readonly" /></td>
    	<td width="6%">Fecha:</td>
        <td width="15%"><input name="fecha" type="text" id="fecha" class="cellText" value="<?=$factual?>" readonly="readonly" /></td>
        <td align="right"><input name="btActualizar" type="submit" class="btLista" id="btActualizar" value="Actualizar" /></td>
    </tr>
</table>

<table width="98%" class="tblLista">
	<tr class="trListaHead">
		<th width="5%" scope="col">Nro. Periodo</th>
		<th width="10%" scope="col">Periodo</th>
		<th width="4%" scope="col">Mes Prog.</th>
		<th width="8%" scope="col">Derecho</th>
		<th width="9%" scope="col">Pendiente Periodos Ant.</th>
		<th width="9%" scope="col">Goce</th>
		<th width="9%" scope="col">Trabaj.</th>
		<th width="9%" scope="col">(Interrump.)</th>
		<th width="9%" scope="col">Total Utiliz.</th>
		<th width="9%" scope="col">Vac. Pend.</th>
		<th width="9%" scope="col">Cobros</th>
		<th scope="col">Pagos Pend.</th>
	</tr>
	<?php
	if ($mingreso<$mactual || ($mingreso==$mactual && $dingreso<$dactual)) $total_periodos=$aactual-$aingreso+1; else $total_periodos=$aactual-$aingreso;
	//	VERIFICO LA TABLA rh_vacacionperiodo
	$sql="SELECT * FROM rh_vacacionperiodo WHERE CodPersona='".$persona."' ORDER BY NroPeriodo DESC";
	$query_select=mysql_query($sql) or die ($sql.mysql_error());
	$rows_select=mysql_num_rows($query_select);
	if ($rows_select==0) {
		//	SI NO EXSITEN DATOS EN LA TABLA INSERTO TODOS LOS PERIODOS
		$anio_periodo=$aingreso;
		$ley=0;
		for ($i=1; $i<=$total_periodos; $i++) {
			if (($i-1)%5==0 && $i>1) $ley+=2; else $ley++;
			if ($i==1) {
				if ($mes_programado<10) $mes_programado="0".$mes_programado;
				$pendientes=$dias_derecho;
				$pendiente_pago=$dias_pago;	
				$ArrayAnio[$i]=$anio_periodo;
				$ArrayDerecho[$i]=$dias_derecho;
				$ArrayPendientePeriodo[$i]=0;
				$ArrayPendienteVac[$i]=$pendientes;
				$ArrayPendientePago[$i]=$pendiente_pago;
				$pendiente_periodo=$pendientes;
			} else {
				if ($i==$total_periodos) {
					$dias_transcurridos=getFechaDias("$dingreso-$mingreso-$anio_periodo", "$dactual-$mactual-$aactual"); 
					if ($dias_transcurridos>365) $dias_transcurridos=365;
					$derecho_ley=($dias_derecho/365)*$dias_transcurridos; $derecho_ley=$derecho_ley+$ley-1;
				} else $derecho_ley=$dias_derecho+$ley-1;
				if ($derecho_ley>37) $derecho_ley=37;
				$pendiente_pago+=$dias_pago;
				$pendientes=$derecho_ley+$pendiente_periodo;
				$ArrayAnio[$i]=$anio_periodo;
				$ArrayDerecho[$i]=$derecho_ley;
				$ArrayPendientePeriodo[$i]=$pendiente_periodo;
				$ArrayPendienteVac[$i]=$pendientes;
				$ArrayPendientePago[$i]=$pendiente_pago;
				$pendiente_periodo+=$derecho_ley;
			}
			$anio_periodo++;
		}
		for ($i=$total_periodos; $i>=1; $i--) {
			$hasta=$ArrayAnio[$i]+1; $periodo=$ArrayAnio[$i]."-".$hasta;
			$derecho=number_format($ArrayDerecho[$i], 2, ',', '.');
			$pendiente_periodo_ant=number_format($ArrayPendientePeriodo[$i], 2, ',', '.');
			$vac_pendientes=number_format($ArrayPendienteVac[$i], 2, ',', '.');
			$pagos_pendientes=number_format($ArrayPendientePago[$i], 2, ',', '.');
			?>	
			<tr class="trListaBody" onclick="mClk(this, 'elemento'); clkVacaciones(this.id, '<?=$persona?>', '<?=$fingreso?>');" id="<?=$i?>">
				<td align="center"><?=$i?></td>
				<td align="center"><?=$periodo?></td>
				<td align="center"><input type="text" name="mes_programado_<?=$i?>" id="mes_programado_<?=$i?>" value="<?=$mingreso?>" size="2" /></td>
				<td align="center"><input type="text" name="derecho_<?=$i?>" id="derecho_<?=$i?>" value="<?=$derecho?>" size="4" /></td>
				<td align="right"><?=$pendiente_periodo_ant?></td>
				<td align="right">0,00</td>
				<td align="right">0,00</td>
				<td align="right">0,00</td>
				<td align="right">0,00</td>
				<td align="right"><?=$vac_pendientes?></td>
				<td align="right">0,00</td>
				<td align="right"><?=$pagos_pendientes?></td>
			</tr>
			<?
		}
	} else {
		$sql="SELECT * FROM rh_vacacionperiodo WHERE CodPersona='".$persona."' ORDER BY NroPeriodo DESC LIMIT 0, 1";
		$query_mayor=mysql_query($sql) or die ($sql.mysql_error());
		$field_mayor = mysql_fetch_array($query_mayor);
		if (($field_mayor['Anio'] + 1) < date("Y")) {
			$sql = "INSERT INTO rh_vacacionperiodo (
								CodPersona,
								NroPeriodo,
								Anio,
								Mes,
								Derecho,
								PendientePeriodo,
								Pendientes,
								PendientePago
					) VALUES (
								'$persona',
								'".($field_mayor['NroPeriodo']+1)."',
								'".($field_mayor['Anio']+1)."',
								'".($field_mayor['Mes'])."',
								'".($field_mayor['Derecho']+1)."',
								'".($field_mayor['Pendientes'])."',
								'".($field_mayor['Pendientes']+$field_mayor['Derecho']+1)."',
								'".($field_mayor['PendientePago']+$dias_pago)."'
					)";
			$query_insert=mysql_query($sql) or die ($sql.mysql_error());
		}
		
		$sql="SELECT * FROM rh_vacacionperiodo WHERE CodPersona='".$persona."' ORDER BY NroPeriodo DESC";
		$query_select=mysql_query($sql) or die ($sql.mysql_error());
		$rows_select=mysql_num_rows($query_select);	
		for ($i=$rows_select; $i>=1; $i--) {
			$field_select=mysql_fetch_array($query_select);
			$hasta=$field_select['Anio']+1; $periodo=$field_select['Anio']."-".$hasta;
			$derecho=number_format($field_select['Derecho'], 2, ',', '.');
			$pendiente_periodo_ant=number_format($field_select['PendientePeriodo'], 2, ',', '.');
			$goce=number_format($field_select['DiasGozados'], 2, ',', '.');
			$trabajado=number_format($field_select['DiasTrabajados'], 2, ',', '.');
			$interrumpido=number_format($field_select['DiasInterrumpidos'], 2, ',', '.');
			$utilizado=number_format($field_select['TotalUtilizados'], 2, ',', '.');
			$pendiente=number_format($field_select['PendientePago'], 2, ',', '.');
			$vac_pendientes=number_format($field_select['Pendientes'], 2, ',', '.');
			$pagos_pendientes=number_format($field_select['PendientePago'], 2, ',', '.');
			?>	
			<tr class="trListaBody" onclick="mClk(this, 'elemento'); clkVacaciones(this.id, '<?=$persona?>', '<?=$fingreso?>');" id="<?=$i?>">
				<td align="center"><?=$i?></td>
				<td align="center"><?=$periodo?></td>
				<td align="center"><input type="text" name="mes_programado_<?=$i?>" id="mes_programado_<?=$i?>" value="<?=$mingreso?>" size="2" /></td>
				<td align="center"><input type="text" name="derecho_<?=$i?>" id="derecho_<?=$i?>" value="<?=$derecho?>" size="4" /></td>
				<td align="right"><?=$pendiente_periodo_ant?></td>
				<td align="right"><?=$goce?></td>
				<td align="right"><?=$trabajado?></td>
				<td align="right"><?=$interrumpido?></td>
				<td align="right"><?=$utilizado?></td>
				<td align="right"><?=$vac_pendientes?></td>
				<td align="right">0,00</td>
				<td align="right"><?=$pagos_pendientes?></td>
			</tr>
			<?
		}
	}
	?>
</table>
</form>

</body>
</html>