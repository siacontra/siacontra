<?php 
include ("fphp_ac.php");
//	--------------------------
if ($accion == "insertarLineaVoucher") {
	connect();
	?>
    <td align="center">
		<?=$nrodetalles?>
        <input type="hidden" name="nrodetalles" value="<?=$nrodetalles?>">
	</td>
    <td align="center"><input type="text" name="cuenta" id="cuenta<?=$nrodetalles?>" class="cell2" style="text-align:center;" readonly></td>
    <td align="center"><input type="text" name="ccosto" id="ccosto<?=$nrodetalles?>" class="cell2" style="text-align:center;" readonly></td>
    <td align="center"><input type="text" name="porcentaje" id="porcentaje<?=$nrodetalles?>" class="cell" style="text-align:right;" onBlur="numeroBlur(this); this.className='cell';" onFocus="numeroFocus(this); this.className='cellFocus';" value="0,00"></td>
    <td align="center"><input type="text" name="monto" id="monto<?=$nrodetalles?>" class="cell" style="text-align:right;" onBlur="numeroBlur(this); this.className='cell';" onFocus="numeroFocus(this); this.className='cellFocus';" value="0,00"></td>
    <td align="center"><input type="text" name="persona" id="persona<?=$nrodetalles?>" class="cell2" style="text-align:center;" readonly></td>
    <td align="center"><input type="text" name="nrodocumento" id="nrodocumento<?=$nrodetalles?>" maxlength="10" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';"></td>
    <?
}
?>