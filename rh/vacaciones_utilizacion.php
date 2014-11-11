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
if ($rows!=0) { 
	$field=mysql_fetch_array($query); 
	$vac_pendientes = $field['Pendientes']; 
	if ($derecho > $vac_pendientes) $vac_pendientes=$derecho; 
} else $vac_pendientes=$derecho;
//	---------------------------------
$sql="SELECT v.*, m.descripcion AS TipoUtilizacion FROM rh_vacacionutilizacion v INNER JOIN mastmiscelaneosdet m ON (v.TipoUtilizacion=m.CodDetalle AND m.CodMaestro='USOVACACIO' AND m.CodAplicacion='RH') WHERE v.CodPersona='".$persona."' AND v.NroPeriodo='".$nro_periodo."' ORDER BY v.Secuencia";
$query=mysql_query($sql) or die ($sql.mysql_error());
$dias_derecho=$derecho;
$derecho=strtr($derecho, '.', ',');
?>

<form name="frmentrada" id="frmentrada" method="post" action="vacaciones_utilizacion.php" onsubmit="return verificarVacacionesUtilizacion(this)">
<input type="hidden" name="persona" id="persona" value="<?=$persona?>" />
<input type="hidden" name="fingreso" id="fingreso" value="<?=$fingreso?>" />
<input type="hidden" name="nro_periodo" id="nro_periodo" value="<?=$nro_periodo?>" />
<input type="hidden" name="derecho" id="derecho" value="<?=$derecho?>" />
<input type="hidden" name="vac_pendientes" id="vac_pendientes" value="<?=$vac_pendientes?>" />
<input type="hidden" name="mes_programado" id="mes_programado" value="<?=$mes_programado?>" />
<input type="hidden" name="sub_accion" id="sub_accion" value="INSERTAR" />
<input type="hidden" name="elemento" id="elemento" />
<input type="hidden" name="secuencia" id="secuencia" />


<table width="85%" class="tblForm">
	<tr>
        <td>* Utilizaci&oacute;n</td>
        <td width="17%">* Dias</td>
    	<td width="24%">* Inicio</td>
        <td width="24%">* Fin</td>
    </tr>
    <tr>
    	<td>
        	<select name="utilizacion" id="utilizacion" style="width:125px;" <?=$disabled?>>
            	<option value=""></option>
            	<? getMiscelaneos('', "USOVACACIO", 0); ?>
            </select>
        </td>
        <td><input type="text" name="dias" id="dias" size="8" <?=$disabled?> onkeyup="getFechaFin(document.getElementById('finicio').value, this.value);" /></td>
    	<td><input type="text" name="finicio" id="finicio" size="15" maxlength="10" <?=$disabled?> onkeyup="getFechaFin(this.value, document.getElementById('dias').value);" /></td>
    	<td><input type="text" name="ffin" id="ffin" size="15" maxlength="10" /></td>
        
    </tr>
</table>

<table width="85%" class="tblBotones">
	<tr>
		<td align="right">
			<input class="btLista" type="submit" id="btNuevo" value="Insertar" <?=$disabled?> />
			<input class="btLista" type="button" id="btEditar" value="Editar" <?=$disabled?> onclick="editarSubElemento(this.form);" />
			<input class="btLista" type="button" id="btBorrar" value="Eliminar" <?=$disabled?> onclick="eliminarSubElemento(this.form, 'VACACIONES', 'UTILIZACION', 'ELIMINAR', '<?=$persona."/".$nro_periodo?>', frmentrada.elemento.value);" />
		</td>
	</tr>
</table>

<table width="85%" class="tblLista">
	<tr class="trListaHead">
		<th scope="col">Utilizaci&oacute;n</th>
		<th scope="col" width="17%">Dias</th>
		<th scope="col" width="24%">Inicio</th>
		<th scope="col" width="24%">Fin</th>
	</tr>
    <?php
	while ($field=mysql_fetch_array($query)) {
		list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaInicio']); $finicio=$d."-".$m."-".$a;
		list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaFin']); $ffin=$d."-".$m."-".$a;
		$dias=number_format($field['DiasUtiles'], 2, ',', '.');
		?>
        <tr class="trListaBody" onclick="mClk(this, 'elemento');" id="<?=$field['Secuencia']?>">
            <td align="center"><?=$field['TipoUtilizacion']?></td>
            <td align="center"><?=$dias?></td>
            <td align="center"><?=$finicio?></td>
            <td align="center"><?=$ffin?></td>
        </tr>
        <?
	}
	?>
</table>
</form>

</body>
</html>