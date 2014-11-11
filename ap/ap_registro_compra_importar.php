<?php
$Ahora = ahora();
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Importar Datos para el Registro de Compras</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=ap_registro_compra_importar" method="post" onsubmit="return registro_compra_importar(this, 'importar');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<div class="divBorder" style="width:500px;">
<table width="500" class="tblFiltro">
	<tr>
    	<td>
        	<table width="100%">
                <tr>
                    <td align="right" width="125">Organismo:</td>
                    <td>
                        <select name="CodOrganismo" id="CodOrganismo" style="width:300px;">
                            <?=getOrganismos("", 3)?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right">Periodo:</td>
                    <td>
                        <input type="text" name="Periodo" id="Periodo" value="<?=$Anio?>-<?=$Mes?>" maxlength="7" style="width:60px;" />
                    </td>
                </tr>            	
            </table>
        </td>
    </tr>
    <tr>
    	<td>
        	<table width="100%">
                <tr>
                    <td align="center" class="divFormCaption" width="50%">Sistema Fuente</td>
                    <td align="center" class="divFormCaption" width="50%">Reg. Importados</td>
                </tr>
                <tr>
                    <td style="padding-left:60px;"><input type="checkbox" name="FlagCP" id="FlagCP" value="S" /> Cuentas x Pagar</td>
                    <td><div id="divCP" style="font-weight:bold; text-align:center;">0</div></td>
                </tr>
                <tr>
                    <td style="padding-left:60px;"><input type="checkbox" name="FlagCC" id="FlagCC" value="S" /> Caja Chica</td>
                    <td><div id="divCC" style="font-weight:bold; text-align:center;">0</div></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</div>
<center><input type="submit" value="Importar Datos"></center><br />
</form>