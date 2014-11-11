<?php
if ($opcion == "ver") {
	$disabled_ver = "disabled";
	$display_ver = "display:none;";
}
//	consulto datos generales de la transaccion
list($CodOrganismo, $Periodo, $Voucher) = split("[.]", $registro);
$sql = "SELECT *
		FROM ac_vouchermast
		WHERE
			CodOrganismo = '".$CodOrganismo."' AND
			Periodo = '".$Periodo."' AND
			Voucher = '".$Voucher."'";
$query_mast = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_mast) != 0) $field_mast = mysql_fetch_array($query_mast);

//	consulto si el periodo esta abierto
$sql = "SELECT Estado
		FROM ac_controlcierremensual
		WHERE
			TipoRegistro = 'AB' AND
			CodOrganismo = '".$field_mast['CodOrganismo']."' AND
			Periodo = '".$field_mast['Periodo']."'";
$query_periodo = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_periodo) != 0) $field_periodo = mysql_fetch_array($query_periodo);

//	sistema fuente
$sql = "SELECT CodSistemaFuente FROM mastaplicaciones WHERE CodAplicacion = 'AP'";
$query_prefpa = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_prefpa) != 0) $field_prefpa = mysql_fetch_array($query_prefpa);
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Ver Voucher</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" method="POST">
<table align="center">
	<tr>
    	<td valign="top">
            <table width="400" class="tblBotones">
                <tr><td align="right">&nbsp;</td></tr>
            </table>
            
            <table><tr><td><div style="overflow:scroll; width:400px; height:125px;">
            <table width="400" class="tblLista">
            	<thead>
                <tr>
                    <th width="75" scope="col">Periodo</th>
                    <th width="75" scope="col">Voucher</th>
                    <th width="75" scope="col">Fecha</th>
                    <th width="75" scope="col">Status</th>
                    <th scope="col">Organismo</th>
                </tr>
                </thead>
                
                <tbody id="lista1">
                </tbody>
                
                <tfoot id="foot1">
                <tr><td colspan="5">&nbsp;</td></tr>
                <tr>
                    <th scope="col" colspan="4">&nbsp;</th>
                    <th scope="col" align="right">&nbsp;</th>
                </tr>
                </tfoot>
            </table>
            </div></td></tr></table>
        </td>
        
        <td valign="top">
            <table width="550" class="tblBotones">
                <tr><td align="right">&nbsp;</td></tr>
            </table>
            
            <table><tr><td><div style="overflow:scroll; width:550px; height:125px;">
            <table width="100%" class="tblLista">
            	<thead>
                <tr>
                    <th width="50" scope="col">Linea</th>
                    <th scope="col">Errores Encontrados</th>
                    <th width="75" scope="col">Periodo</th>
                    <th width="75" scope="col">Voucher</th>
                    <th width="75" scope="col">Organismo</th>
                </tr>
                </thead>
                
                <tbody id="lista_errores">
                </tbody>
                
                <tfoot id="foot_errores">
                	<tr><td colspan="5">&nbsp;</td></tr>
                	<tr>
                        <th scope="col" colspan="4">&nbsp;</th>
                        <th scope="col">&nbsp;</th>
					</tr>
                </tfoot>
            </table>
            </div></td></tr></table>
        </td>
    </tr>
    
    <tr>
    	<td colspan="2">
            <table width="960" class="tblForm">
                <tr>
                    <td class="tagForm" width="125">* Organismo:</td>
                    <td>
                        <select id="CodOrganismo" style="width:300px;" <?=$disabled_ver?>>
                            <?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $field_mast['CodOrganismo'], 1)?>
                        </select>
                    </td>
                    <td class="tagForm">Descripci&oacute;n:</td>
                    <td><input type="text" id="ComentariosVoucher" style="width:297px;" value="<?=($field_mast['ComentariosVoucher'])?>" <?=$disabled_ver?> /></td>
                </tr>
                <tr>
                    <td class="tagForm">* Fecha:</td>
                    <td><input type="text" id="FechaVoucher" value="<?=formatFechaDMA($field_mast['FechaVoucher'])?>" style="width:75px;" disabled /></td>
                    <td class="tagForm">Preparado Por:</td>
                    <td>
                        <input type="hidden" id="PreparadoPor" value="<?=$_SESSION['CODPERSONA_ACTUAL']?>" />
                        <input type="text" style="width:297px;" value="<?=$_SESSION['NOMBRE_USUARIO_ACTUAL']?>" disabled />
                    </td>
                </tr>
                <tr>
                    <td class="tagForm">Voucher:</td>
                    <td>
                        <select id="CodVoucher" <?=$disabled_ver?>>
                            <?=loadSelect("ac_voucher", "CodVoucher", "CodVoucher", $field_mast['CodVoucher'], 1)?>
                        </select>
                        <input type="text" id="NroVoucher" style="width:50px;" value="<?=substr($Voucher, 3, 4)?>" disabled="disabled" />
                    </td>
                    <td class="tagForm">Aprobado Por:</td>
                    <td>
                        <input type="hidden" id="AprobadoPor" value="<?=$_SESSION['CODPERSONA_ACTUAL']?>" />
                        <input type="text" style="width:297px;" value="<?=$_SESSION['NOMBRE_USUARIO_ACTUAL']?>" disabled />
                    </td>
                </tr>
                <tr>
                    <td class="tagForm">* Libro Contable:</td>
                    <td colspan="3">
                        <select id="CodLibroCont" style="width:150px;" <?=$disabled_ver?>>
                            <?=loadSelect("ac_librocontable", "CodLibroCont", "Descripcion", $field_mast['CodLibroCont'], 0)?>
                        </select>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    
	<tr>
    	<td valign="top" colspan="2">
            <table><tr><td><div style="overflow:scroll; width:960px; height:225px;">
            <table width="1100" class="tblLista">
            	<thead>
                <tr>
                    <th scope="col" width="30">#</th>
                    <th scope="col" width="110">Cuenta</th>
                    <th scope="col">Descripci&oacute;n</th>
                    <th scope="col" width="125">Monto</th>
                    <th scope="col" width="75">Persona</th>
                    <th scope="col" width="150">Documento</th>
                    <th scope="col" width="75">C.Costo</th>
                    <th scope="col" width="75">Fecha</th>
                </tr>
                </thead>
                
                <tbody>
                <?php
				$sql = "SELECT
							vd.*,
							pc.Descripcion AS NomCuenta
						FROM
							ac_voucherdet vd
							INNER JOIN ac_mastplancuenta pc ON (vd.codcuenta = pc.CodCuenta)
						WHERE
							vd.CodOrganismo = '".$CodOrganismo."' AND
							vd.Periodo = '".$Periodo."' AND
							vd.Voucher = '".$Voucher."'
						ORDER BY CodCuenta";
				$query_det = mysql_query($sql) or die ($sql.mysql_error());
				while($field_det = mysql_fetch_array($query_det)) {
					if ($field_det['MontoVoucher'] < 0) {
						$style = " color:red;";
						$Debitos += $field_det['MontoVoucher'];
					} else {
						$style = "";
						$Creditos += $field_det['MontoVoucher'];
					}
					?>
					<tr class="trListaBody">
                    	<td>
                        	<input type="text" name="Linea" value="<?=++$Linea?>" class="cell2" style="text-align:center;" readonly />
                        </td>
                    	<td>
                        	<input type="text" name="CodCuenta" value="<?=$field_det['CodCuenta']?>" class="cell2" style="text-align:center;" readonly />
                        </td>
                    	<td>
                        	<input type="hidden" name="Descripcion" value="<?=$field_det['Descripcion']?>" />
                        	<input type="text" value="<?=$field_det['NomCuenta']?>" class="cell2" readonly />
                        </td>
                    	<td>
                        	<input type="text" name="MontoVoucher" value="<?=number_format($field_det['MontoVoucher'], 2, ',', '.')?>" class="cell2" style="text-align:right; <?=$style?>" readonly />
                        </td>
                    	<td>
                        	<input type="text" name="CodPersona" value="<?=$field_det['CodPersona']?>" class="cell2" style="text-align:center;" readonly />
                        </td>
                    	<td>
                        	<input type="text" name="ReferenciaTipoDocumento" value="<?=$field_det['ReferenciaTipoDocumento']?>" class="cell2" style="width:20px;" readonly /> - 
                        	<input type="text" name="ReferenciaNroDocumento" value="<?=$field_det['ReferenciaNroDocumento']?>" class="cell2" style="width:120px;" readonly />
                        </td>
                    	<td>
                        	<input type="text" name="CodCentroCosto" value="<?=$field_det['CodCentroCosto']?>" class="cell2" style="text-align:center;" readonly />
                        </td>
                    	<td>
                        	<input type="text" name="FechaVoucher" value="<?=formatFechaDMA($field_det['FechaVoucher'])?>" class="cell2" style="width:75px; text-align:center;" readonly />
                        </td>
					</tr>
					<?
				}
				?>
                </tbody>
            </table>
            </div></td></tr></table>
            
            <table>
                <tr>
                    <th scope="col" width="140">Nro Lineas: <input type="text" id="Lineas" value="<?=$Linea?>" class="cell2" style="text-align:center; font-weight:bold; font-size:12px; width:20px;" readonly /></th>
                    <th scope="col" width="75">&nbsp;</th>
                    <th scope="col" width="150">&nbsp;</th>
                    <th scope="col" width="75">Total:</th>
                    <th scope="col" width="125">
                    	<input type="text" id="Creditos" value="<?=number_format($Creditos, 2, ',', '.')?>" class="cell2" style="text-align:right; font-weight:bold; font-size:12px;" readonly />
                    </th>
                    <th scope="col" width="125">
                    	<input type="text" id="Debitos" value="<?=number_format($Debitos, 2, ',', '.')?>" class="cell2" style="text-align:right; font-weight:bold; font-size:12px; color:red;" readonly />
					</th>
                    <th scope="col" width="125">&nbsp;</th>
                </tr>
			</table>
            
        </td>
    </tr>
</table>
</form>