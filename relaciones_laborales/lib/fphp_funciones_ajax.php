<?php
session_start();
include("../../lib/fphp.php");
include("fphp.php");
//	--------------------------
//	inserto linea en lista
if ($accion == "tipo_nomina_procesos_insertar") {
	?>
    <tr class="trListaBody" onclick="mClk(this, 'sel_procesos');" id="procesos_<?=$nro_detalle?>">
        <th>
            <?=$nro_detalle?>
        </th>
        <td>
            <select name="CodTipoProceso" class="cell">
            	<option value="">&nbsp;</option>
                <?=loadSelect("pr_tipoproceso", "CodTipoProceso", "Descripcion", "", 0)?>
            </select>
        </td>
        <td>
            <select name="CodTipoDocumento" class="cell">
            	<option value="">&nbsp;</option>
                <?=loadSelectTipoDocumentoCxP("", "S", 0)?>
            </select>
        </td>
    </tr>
	<?
}

//	inserto linea en lista
elseif ($accion == "tipo_nomina_periodos_insertar") {
	$Secuencia = 1;
	if ($Periodo != "") {
		if (intval($Mes) == 12) { ++$Periodo; $Mes = "01"; }
		else {
			$M = intval($Mes);
			++$M;
			if ($M < 10) $Mes = "0$M"; else $Mes = "$M";
		}
	} else {
		$Periodo = substr($Ahora, 0, 4);
		$Mes = substr($Ahora, 6, 2);
	}
	?>
    <tr class="trListaBody" onclick="mClk(this, 'sel_periodos');" id="periodos_<?=$nro_detalle?>">
        <th>
            <?=$nro_detalle?>
        </th>
        <td>
            <input type="text" name="Periodo" class="cell" style="text-align:center;" maxlength="4" value="<?=$Periodo?>" />
        </td>
        <td>
            <select name="Mes" class="cell">
                <?=loadSelectGeneral("MES", $Mes, 0)?>
            </select>
        </td>
        <td>
            <input type="text" name="Secuencia" class="cell" style="text-align:center;" maxlength="2" value="<?=$Secuencia?>" />
        </td>
    </tr>
	<?
}
?>