<?php
list($CodOrganismo, $CodDocumento, $NroDocumento, $TipoMovimiento) = split("[.]", $registro);
//---------------------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Transacci&oacute;n de Commodities</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" method="post" target="iReporte">
<input type="hidden" name="registro" id="registro" value="<?=$registro?>" />
<table width="1000" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);">
                <a href="#" onclick="cargarPagina(document.getElementById('frmentrada'), 'lg_transaccion_commodity_pdf.php');">Transacci&oacute;n</a>
            </li>
            <?php
			if ($TipoMovimiento == "I") {
				$sql = "SELECT t.CodOrganismo, t.CodDocumento, t.NroDocumento
						FROM
							lg_commoditytransaccion t
							INNER JOIN lg_ordencompra oc ON (t.CodOrganismo = oc.CodOrganismo AND 
															 t.ReferenciaNroDocumento = oc.NroOrden AND
															 t.Anio = oc.Anio)
							INNER JOIN lg_activofijo af ON (af.CodOrganismo = oc.CodOrganismo AND
															af.Anio = oc.Anio AND
															af.NroOrden = oc.NroOrden AND
															af.CodDocumento = t.CodDocumento AND
															af.NroDocumento = t.NroDocumento)
						WHERE
							t.CodOrganismo = '".$CodOrganismo."' AND
							t.CodDocumento = '".$CodDocumento."' AND
							t.NroDocumento = '".$NroDocumento."'";
				$query = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query) != 0) {
					?>
					<li id="li2" onclick="currentTab('tab', this);">
						<a href="#" onclick="cargarPagina(document.getElementById('frmentrada'), 'lg_transaccion_commodity_activos_pdf.php');">Activos Fijos</a>
					</li>
					<?
				}
			}
			?>
            </ul>
            </div>
        </td>
    </tr>
</table>
</form>

<center>
<iframe name="iReporte" id="iReporte" style="border-left:solid 1px #CDCDCD; border-right:solid 1px #CDCDCD; border-bottom:solid 1px #CDCDCD; border-top:0; width:1000px; height:670px;" src="lg_transaccion_commodity_pdf.php?registro=<?=$registro?>"></iframe>
</center>