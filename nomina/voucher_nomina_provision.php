<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	--------------------------------------
extract($_POST);
extract($_GET);
//	--------------------------------------
include("fphp_nomina.php");
connect();
//	--------------------------------------
list($organismo, $nomina, $periodo, $proceso) = split("[.]", $registro);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_nomina.js"></script>
</head>

<body>
<?php
$sql = "SELECT
			ppp.*,
			tn.Nomina,
			tn.CodPerfilConcepto,
			tp.Descripcion AS Proceso,
			(SELECT PrefVoucherPD FROM mastaplicaciones WHERE CodAplicacion = 'NOMINA') AS CodVoucher,
			(SELECT CodSistemaFuente FROM mastaplicaciones WHERE CodAplicacion = 'NOMINA') AS CodSistemaFuente
		FROM
			pr_procesoperiodo ppp
			INNER JOIN tiponomina tn ON (ppp.CodTipoNom = tn.CodTipoNom)
			INNER JOIN pr_tipoproceso tp ON (ppp.CodTipoProceso = tp.CodTipoProceso)
		WHERE
			ppp.CodOrganismo = '".$organismo."' AND
			ppp.Periodo = '".$periodo."' AND
			ppp.CodTipoNom = '".$nomina."' AND
			ppp.CodTipoProceso = '".$proceso."'
		ORDER BY CodTipoNom, FechaCreado";
$query_voucher = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_voucher) != 0) $field_voucher = mysql_fetch_array($query_voucher);
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Generaci&oacute;n de Vouchers</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" method="POST" onsubmit="return generarVoucher(this);">
<input type="hidden" id="organismo" value="<?=$organismo?>" />
<input type="hidden" id="nomina" value="<?=$nomina?>" />
<input type="hidden" id="periodo" value="<?=$periodo?>" />
<input type="hidden" id="proceso" value="<?=$proceso?>" />
<input type="hidden" id="codsistemafuente" value="<?=$field_voucher['CodSistemaFuente']?>" />
<input type="hidden" id="voucher" value="<?=$field_voucher['CodVoucher']?>" />
<input type="hidden" id="comentarios" value="<?=($field_voucher['Nomina'])?>, <?=($field_voucher['Proceso'])?>" />

<table align="center">
	<tr>
    	<td valign="top">
            <table width="400" class="tblBotones">
                <tr><td align="right">&nbsp;</td></tr>
            </table>
            
            <table><tr><td><div style="overflow:scroll; width:400px; height:125px;">
            <table width="400" class="tblLista">
                <tr class="trListaHead">
                    <th width="75" scope="col">Periodo</th>
                    <th width="75" scope="col">Voucher</th>
                    <th width="75" scope="col">Fecha</th>
                    <th width="75" scope="col">Status</th>
                    <th scope="col">Organismo</th>
                </tr>
                
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
                <tr class="trListaHead">
                    <th width="50" scope="col">Linea</th>
                    <th scope="col">Errores Encontrados</th>
                    <th width="75" scope="col">Periodo</th>
                    <th width="75" scope="col">Voucher</th>
                    <th width="75" scope="col">Organismo</th>
                </tr>
                
                <tbody id="lista21">
                </tbody>
                
                <tfoot id="foot21">
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
                    <td class="tagForm" width="125">Organismo:</td>
                    <td>
                        <select id="organismo" style="width:275px;">
							<?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $organismo, 1)?>
                        </select>
                    </td>
                    <td class="tagForm">Descripci&oacute;n:</td>
                    <td><input type="text" id="descripcion" style="width:297px;" value="<?=($field_voucher['Nomina'])?>, <?=($field_voucher['Proceso'])?>" /></td>
                </tr>
                <tr>
                    <td class="tagForm">Fecha:</td>
                    <td><input type="text" id="fecha" maxlength="10" value="<?=$fecha?>" style="width:95px;" disabled="disabled" /></td>
                    <td class="tagForm">Preparado Por:</td>
                    <td>
                        <input type="hidden" id="codingresado" value="<?=$_SESSION['CODPERSONA_ACTUAL']?>" />
                        <input type="text" style="width:297px;" value="<?=$_SESSION['NOMBRE_USUARIO_ACTUAL']?>" />
                    </td>
                </tr>
                <tr>
                    <td class="tagForm">Voucher:</td>
                    <td>
                        <select id="codvoucher">
                            <?=loadSelect("ac_voucher", "CodVoucher", "CodVoucher", $field_voucher['CodVoucher'], 1);?>
                        </select>
                        <input type="text" id="nrovoucher" style="width:50px;" disabled="disabled" />
                    </td>
                    <td class="tagForm">Aprobado Por:</td>
                    <td>
                        <input type="hidden" id="codaprobado" value="<?=$_SESSION['CODPERSONA_ACTUAL']?>" />
                        <input type="text" style="width:297px;" value="<?=$_SESSION['NOMBRE_USUARIO_ACTUAL']?>" />
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    
	<tr>
    	<td valign="top" colspan="2">
            <table width="960" class="tblBotones">
                <tr>
                    <td align="right">
                        <input type="submit" class="btLista" value="Aceptar" />
                        <input type="button" class="btLista" value="Rechazar" onclick="javascript:window.close();" />
                    </td>
                </tr>
            </table>
            
            <table><tr><td><div style="overflow:scroll; width:960px; height:250px;">
            <table width="1000" class="tblLista" id="tblListaDis">
                <tr class="trListaHead">
                    <th scope="col" width="30">#</th>
                    <th scope="col" width="75">Cuenta</th>
                    <th scope="col" width="75">Persona</th>
                    <th scope="col" width="125">Documento</th>
                    <th scope="col" width="75">Fecha</th>
                    <th scope="col" width="100">Monto</th>
                    <th scope="col" width="75">C.Costo</th>
                    <th scope="col">Descripci&oacute;n</th>
                </tr>
                
                <tbody id="listaVouchers">
                <?
				$sql = "(SELECT
							cpd.CuentaDebe AS CodCuenta,
							pc.Descripcion AS NomCuenta,
							SUM(tnec.Monto) AS Monto,
							'Debe' AS Tipo,
							'1' AS Orden,
							cpd.cod_partida
						 FROM
							pr_conceptoperfildetalle cpd
							INNER JOIN ac_mastplancuenta pc ON (cpd.CuentaDebe = pc.CodCuenta)
							INNER JOIN pr_tiponominaempleadoconcepto tnec ON (cpd.CodTipoProceso = tnec.CodTipoProceso AND
																			  cpd.CodConcepto = tnec.CodConcepto)
						 WHERE
							cpd.CodPerfilConcepto = '".$field_voucher['CodPerfilConcepto']."' AND
							cpd.CodTipoProceso = '".$proceso."' AND
							tnec.CodTipoNom = '".$nomina."' AND
							tnec.Periodo = '".$periodo."' AND
							tnec.CodOrganismo = '".$organismo."'
						 GROUP BY CodCuenta)
						
						UNION
						
						(SELECT
							cpd.CuentaHaber AS CodCuenta,
							pc.Descripcion AS NomCuenta,
							SUM(tnec.Monto) AS Monto,
							'Haber' AS Tipo,
							'2' AS Orden,
							cpd.cod_partida
						 FROM
							pr_conceptoperfildetalle cpd
							INNER JOIN ac_mastplancuenta pc ON (cpd.CuentaHaber = pc.CodCuenta)
							INNER JOIN pr_tiponominaempleadoconcepto tnec ON (cpd.CodTipoProceso = tnec.CodTipoProceso AND
																			  cpd.CodConcepto = tnec.CodConcepto)
						 WHERE
							cpd.CodPerfilConcepto = '".$field_voucher['CodPerfilConcepto']."' AND
							cpd.CodTipoProceso = '".$proceso."' AND
							tnec.CodTipoNom = '".$nomina."' AND
							tnec.Periodo = '".$periodo."' AND
							tnec.CodOrganismo = '".$organismo."'
						 GROUP BY CodCuenta)
						
						UNION
						
						(SELECT
							cpd.CuentaDebe AS CodCuenta,
							pc.Descripcion AS NomCuenta,
							SUM(tnec.TotalNeto) AS Monto,
							'Debe' AS Tipo,
							'3' AS Orden,
							cpd.cod_partida
						 FROM
							pr_conceptoperfildetalle cpd
							INNER JOIN ac_mastplancuenta pc ON (cpd.CuentaDebe = pc.CodCuenta)
							INNER JOIN pr_tiponominaempleado tnec ON (cpd.CodTipoProceso = tnec.CodTipoProceso)
						 WHERE
							cpd.CodPerfilConcepto = '".$field_voucher['CodPerfilConcepto']."' AND
							cpd.CodTipoProceso = '".$proceso."' AND
							cpd.CodConcepto = (SELECT CodConcepto FROM pr_concepto WHERE Abreviatura = 'TTN') AND
							tnec.CodTipoNom = '".$nomina."' AND
							tnec.Periodo = '".$periodo."' AND
							tnec.CodOrganismo = '".$organismo."'
						 GROUP BY CodCuenta)
						
						UNION
						
						(SELECT
							cpd.CuentaHaber AS CodCuenta,
							pc.Descripcion AS NomCuenta,
							SUM(tnec.TotalNeto) AS Monto,
							'Haber' AS Tipo,
							'4' AS Orden,
							cpd.cod_partida
						 FROM
							pr_conceptoperfildetalle cpd
							INNER JOIN ac_mastplancuenta pc ON (cpd.CuentaHaber = pc.CodCuenta)
							INNER JOIN pr_tiponominaempleado tnec ON (cpd.CodTipoProceso = tnec.CodTipoProceso)
						 WHERE
							cpd.CodPerfilConcepto = '".$field_voucher['CodPerfilConcepto']."' AND
							cpd.CodTipoProceso = '".$proceso."' AND
							cpd.CodConcepto = (SELECT CodConcepto FROM pr_concepto WHERE Abreviatura = 'TTN') AND
							tnec.CodTipoNom = '".$nomina."' AND
							tnec.Periodo = '".$periodo."' AND
							tnec.CodOrganismo = '".$organismo."'
						 GROUP BY CodCuenta)
						 
						ORDER BY CodCuenta";
				$query_voucher_det = mysql_query($sql) or die ($sql.mysql_error());	$linea=0;
                while($field_voucher_det = mysql_fetch_array($query_voucher_det)) {	$linea++;
					if ($proceso == "FIN") {
						$sql = "SELECT SUM(tnec.Monto) AS Monto
								 FROM
									pr_conceptoperfildetalle cpd
									INNER JOIN pr_tiponominaempleadoconcepto tnec ON (cpd.CodTipoProceso = tnec.CodTipoProceso AND
																					  cpd.CodConcepto = tnec.CodConcepto)
									INNER JOIN pr_concepto c ON (cpd.CodConcepto = c.CodConcepto)
								 WHERE
									cpd.cod_partida = '".$field_voucher_det['cod_partida']."' AND
									cpd.CodPerfilConcepto = '".$field_voucher['CodPerfilConcepto']."' AND
									cpd.CodTipoProceso = 'ADE' AND
									tnec.CodTipoNom = '".$nomina."' AND
									tnec.Periodo = '".$periodo."' AND
									tnec.CodOrganismo = '".$organismo."' AND
									c.Tipo = 'I'";
						$query_adelanto = mysql_query($sql) or die ($sql.mysql_error());
                		if (mysql_num_rows($query_adelanto) != 0) $field_adelanto = mysql_fetch_array($query_adelanto);
						else $field_adelanto['Monto'] = 0;
					}
					##
					$sql = "SELECT SUM(tnec.Monto) AS Monto
							 FROM
								pr_conceptoperfildetalle cpd
								INNER JOIN pr_tiponominaempleadoconcepto tnec ON (cpd.CodTipoProceso = tnec.CodTipoProceso AND
																				  cpd.CodConcepto = tnec.CodConcepto)
								INNER JOIN pr_concepto c ON (cpd.CodConcepto = c.CodConcepto)
							 WHERE
								cpd.cod_partida = '".$field_voucher_det['cod_partida']."' AND
								cpd.CodPerfilConcepto = '".$field_voucher['CodPerfilConcepto']."' AND
								cpd.CodTipoProceso = '".$proceso."' AND
								tnec.CodTipoNom = '".$nomina."' AND
								tnec.Periodo = '".$periodo."' AND
								tnec.CodOrganismo = '".$organismo."' AND
								c.Tipo = 'D'";
					$query_descuento = mysql_query($sql) or die ($sql.mysql_error());
					if (mysql_num_rows($query_descuento) != 0) $field_descuento = mysql_fetch_array($query_descuento);
					else $field_descuento['Monto'] = 0;
					##
					if ($field_voucher_det['Tipo'] == "Haber") {
						$style = "style='color:red'";
						$total_haber += ($field_voucher_det['Monto']);
						$tiposaldo = "D";
						$monto = ($field_voucher_det['Monto']) * (-1);
					} else {
						$style = "";
						$total_debe += ($field_voucher_det['Monto'] - $field_adelanto['Monto'] - $field_descuento['Monto']);
						$monto = ($field_voucher_det['Monto'] - $field_adelanto['Monto'] - $field_descuento['Monto']);
						$tiposaldo = "A";
					}
					?>
					<tr class="trListaBody">
						<td align="center">
                            <input type="hidden" name="_codcuenta" value="<?=$field_voucher_det['CodCuenta']?>" />
                            <input type="hidden" name="_monto" value="<?=$monto?>" />
                            <input type="hidden" name="_comentarios" value="<?=($field_voucher_det['NomCuenta'])?>" />
                            <input type="hidden" name="_tiposaldo" value="<?=$tiposaldo?>" />
							<?=$linea?>
						</td>
						<td align="center" title="<?=($field_voucher_det['NomCuenta'])?>"><?=$field_voucher_det['CodCuenta']?></td>
						<td align="center"></td>
						<td align="center"></td>
						<td align="center"><?=date("d-m-Y")?></td>
						<td align="right"><span <?=$style?>><?=number_format($monto, 2, ',', '.')?></span></td>
						<td align="center"><?=$field_voucher_det['Tipo']?> <?=$field_voucher_det['Orden']?></td>
						<td><?=($field_voucher_det['NomCuenta'])?></td>
					</tr>
					<?
                }
                ?>
                </tbody>
                
                <tfoot>
                	<tr><td colspan="8">&nbsp;</td></tr>
                	<tr>
                        <th scope="col" colspan="4">&nbsp;</th>
                        <th scope="col" align="right" style="color:red;"><?=number_format($total_haber, 2, ',', '.')?></th>
                        <th scope="col" align="right"><?=number_format($total_debe, 2, ',', '.')?></th>
					</tr>
                </tfoot>
            </table>
            </div></td></tr></table>
        </td>
    </tr>
</table>
</form>
</body>
</html>
