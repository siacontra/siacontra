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
//	---------------------------------
$sql="SELECT Pendientes FROM rh_vacacionperiodo WHERE CodPersona='".$persona."' AND NroPeriodo='".$nro_periodo."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) { $field=mysql_fetch_array($query); $vac_pendientes=$field['Pendientes']; } else $vac_pendientes=$derecho;
//	---------------------------------
$sql="SELECT v.* FROM rh_vacacionpago v WHERE v.CodPersona='".$persona."' AND v.NroPeriodo='".$nro_periodo."' ORDER BY v.Secuencia";
$query=mysql_query($sql) or die ($sql.mysql_error());
$dias_derecho=$derecho;
$derecho=strtr($derecho, '.', ',');
?>

<form name="frmentrada" id="frmentrada" method="post" action="vacaciones_pagos.php" onsubmit="return verificarVacacionesPagos(this)">
<input type="hidden" name="persona" id="persona" value="<?=$persona?>" />
<input type="hidden" name="fingreso" id="fingreso" value="<?=$fingreso?>" />
<input type="hidden" name="nro_periodo" id="nro_periodo" value="<?=$nro_periodo?>" />
<input type="hidden" name="derecho" id="derecho" value="<?=$derecho?>" />
<input type="hidden" name="mes_programado" id="mes_programado" value="<?=$mes_programado?>" />
<input type="hidden" name="sub_accion" id="sub_accion" value="INSERTAR" />
<input type="hidden" name="elemento" id="elemento" />

<table width="100%" class="tblForm">
	<tr>
        <td>* Periodo</td>
        <td width="17%">* Dias</td>
    	<td width="24%">* Inicio</td>
        <td width="24%">* Fin</td>
        <td width="24%">Concepto</td>
    </tr>
    <tr>
    	<td><input type="text" name="periodo" id="periodo" size="10" maxlength="7" <?=$disabled?> /></td>
        <td><input type="text" name="dias" id="dias" size="8" <?=$disabled?> onkeyup="getFechaFin(document.getElementById('finicio').value, this.value);" /></td>
    	<td><input type="text" name="finicio" id="finicio" size="15" maxlength="10" <?=$disabled?> onkeyup="getFechaFin(this.value, document.getElementById('dias').value);" /></td>
    	<td><input type="text" name="ffin" id="ffin" size="15" maxlength="10" readonly="readonly" /></td>
    	<td><input type="text" name="concepto" id="concepto" size="30" maxlength="50" <?=$disabled?> /></td>
        
    </tr>
</table>

<table width="100%" class="tblBotones">
	<tr>
		<td align="right">
			<input name="btNuevo" class="btLista" type="submit" id="btNuevo" value="Insertar" <?=$disabled?> />
			<input name="btBorrar" class="btLista" type="button" id="btBorrar" value="Eliminar" <?=$disabled?> onclick="eliminarSubElemento(this.form, 'VACACIONES', 'PAGOS', 'ELIMINAR', '<?=$persona."/".$nro_periodo?>', frmentrada.elemento.value);" />
		</td>
	</tr>
</table>

<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th scope="col" width="15%">Periodo</th>
		<th scope="col" width="13%">Dias</th>
		<th scope="col" width="20%">Inicio</th>
		<th scope="col" width="20%">Fin</th>
		<th scope="col">Concepto</th>
	</tr>
    <?php
	while ($field=mysql_fetch_array($query)) {
		list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaInicio']); $finicio=$d."-".$m."-".$a;
		list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaFin']); $ffin=$d."-".$m."-".$a;
		$dias=number_format($field['DiasPago'], 2, ',', '.');
		?>
        <tr class="trListaBody" onclick="mClk(this, 'elemento');" id="<?=$field['Secuencia']?>">
            <td align="center"><?=$field['Periodo']?></td>
            <td align="center"><?=$dias?></td>
            <td align="center"><?=$finicio?></td>
            <td align="center"><?=$ffin?></td>
            <td align="center"><?=$field['Concepto']?></td>
        </tr>
        <?
	}
	?>
</table>
</form>

</body>
</html>