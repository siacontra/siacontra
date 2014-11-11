<?php
include("fphp.php");
include("../../lib/fphp.php");
extract($_POST);
extract($_GET);
//	--------------------------
if ($accion == "grupo_centro_costos_sub_insertar") {
	?>
    <tr class="trListaBody" onclick="mClk(this, 'sel_sub');" id="sub_<?=$nrodetalle?>">
        <td align="center">
            <input type="text" name="CodSubGrupoCentroCosto" maxlength="4" class="cell" style="text-align:center;" />
        </td>
        <td align="center">
            <input type="text" name="Descripcion" maxlength="50" class="cell" />
        </td>
        <td align="center">
            <select name="Estado" class="cell">
                <?=loadSelectGeneral("ESTADO", "", 0)?>
            </select>
        </td>
	</tr>
	<?
}

elseif ($accion == "miscelaneos_det_insertar") {
	?>
    <tr class="trListaBody" onclick="mClk(this, 'sel_det');" id="det_<?=$nrodet?>">
        <td align="center">
            <input type="text" name="CodDetalle" maxlength="2" class="cell" style="text-align:center;" value="<?=$field_det['CodDetalle']?>" />
        </td>
        <td align="center">
            <input type="text" name="Descripcion" maxlength="50" class="cell" style="" value="<?=$field_det['Descripcion']?>" />
        </td>
        <td align="center">
            <select name="Estado" class="cell">
                <?=loadSelectGeneral("ESTADO", "A", 0)?>
            </select>
        </td>
    </tr>
	<?
}
?>