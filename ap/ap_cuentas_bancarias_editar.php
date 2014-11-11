<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_ap.js"></script>
</head>

<body onload="document.getElementById('descripcion').focus();">
<?php
include("fphp_ap.php");
connect();
//	------------------------------------------------
$sql = "SELECT *
		FROM ap_ctabancaria
		WHERE NroCuenta = '".$registro."'";
$query_mast = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_mast) != 0) $field_mast = mysql_fetch_array($query_mast);
//	------------------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Cuentas Bancarias | Modificar Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'ap_cuentas_bancarias.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="ap_cuentas_bancarias.php" method="POST" onsubmit="return verificarCuentaBancaria(this, 'ACTUALIZAR');">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<div style="width:700px" class="divFormCaption">Datos de la Cuenta</div>
<table width="700" class="tblForm">
	<tr>
		<td class="tagForm" width="125">Organismo:</td>
		<td>
        	<select id="organismo" style="width:300px;">
            	<?=getOrganismos($field_mast['CodOrganismo'], 0);?>
            </select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Banco:</td>
		<td>
        	<select id="banco" style="width:300px;">
            	<?=loadSelect("mastbancos", "CodBanco", "Banco", $field_mast['CodBanco'], 0);?>
            </select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm" width="125">Nro. Cuenta:</td>
		<td><input type="text" id="nrocuenta" maxlength="20" style="width:125px;" value="<?=$field_mast['NroCuenta']?>" disabled="disabled" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input type="text" id="descripcion" maxlength="100" style="width:297px;" value="<?=($field_mast['Descripcion'])?>" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Cuenta (Banco):</td>
		<td><input type="text" id="ctabanco" maxlength="20" style="width:125px;" value="<?=$field_mast['NroCuenta']?>" />*</td>
	</tr>
</table>

<table width="700" class="tblForm">
	<tr>
    	<td width="50%" valign="top">
			<div style="width:100%" class="divFormCaption">Caracter&iacute;sticas de la Cuenta</div>
            <table width="100%">
                <tr>
                    <td class="tagForm" width="125">Tipo de Cuenta:</td>
                    <td>
                        <select id="tcuenta" style="width:150px;">
                            <?=getMiscelaneos($field_mast['TipoCuenta'], "TIPOCTA", 0);?>
                        </select>*
                    </td>
                </tr>
                <tr>
                    <td class="tagForm">Fecha Apertura:</td>
                    <td><input type="text" id="fapertura" maxlength="10" style="width:100px;" value="<?=formatFechaDMA($field_mast['FechaApertura'])?>" />*</td>
                </tr>
                <tr>
                    <td class="tagForm">Cta. Contable:</td>
                    <td>
                        <input type="text" name="codcuenta" id="codcuenta" size="15" value="<?=$field_mast['CodCuenta']?>" disabled="disabled" />
                        <input type="hidden" name="nomcuenta" id="nomcuenta" value="<?=($field_mast['NomCuenta'])?>" />
                        <input type="button" value="..." onclick="cargarVentana(this.form, 'listado_cuentas_contables.php?cod=codcuenta&nom=nomcuenta', 'height=500, width=900, left=50, top=50, resizable=yes');" />*
                    </td>
                </tr>
            </table>        
        </td>
    	<td width="50%" valign="top">
        	<div style="width:100%" class="divFormCaption">Informaci&oacute;n para Cartas de Transferencia de Fondo</div>
            <table width="100%">
                <tr>
                    <td class="tagForm" width="75">Agencia:</td>
                    <td><input type="text" id="agencia" maxlength="100" value="<?=($field_mast['Agencia'])?>" style="width:200px;" /></td>
                </tr>

                <tr>
                    <td class="tagForm">Distrito:</td>
                    <td><input type="text" id="distrito" maxlength="100" value="<?=($field_mast['Distrito'])?>" style="width:200px;" /></td>
                </tr>
                <tr>
                    <td class="tagForm">Atenci&oacute;n:</td>
                    <td><input type="text" id="atencion" maxlength="100" value="<?=($field_mast['Atencion'])?>" style="width:200px;" /></td>
                </tr>
                <tr>
                    <td class="tagForm">Cargo:</td>
                    <td><input type="text" id="cargo" maxlength="100" value="<?=($field_mast['Cargo'])?>" style="width:200px;" /></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<div style="width:700px" class="divFormCaption">Procesos Aplicables</div>
<table width="700" class="tblForm">
	<tr>
		<td width="125">&nbsp;</td>
		<td>
        	<? if ($field_mast['FlagConciliacionBancaria'] == "S") $flagconciliacionb = "checked"; ?>
        	<input type="checkbox" id="flagconciliacionb" value="S" <?=$flagconciliacionb?> /> Conciliaci&oacute;n Bancaria Contable
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
        	<? if ($field_mast['FlagConciliacionCP'] == "S") $flagconciliacionr = "checked"; ?>
        	<input type="checkbox" id="flagconciliacionr" value="S" <?=$flagconciliacionr?> /> Conciliaci&oacute;n Resumida en CxP
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
        	<? if ($field_mast['FlagDebitoBancario'] == "S") $flagdebito = "checked"; ?>
        	<input type="checkbox" id="flagdebito" value="S" <?=$flagdebito?> /> ITF/Debito Bancario
		</td>
	</tr>
</table>

<div style="width:700px" class="divFormCaption"></div>
<table width="700" class="tblForm">
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
        	<? if ($field_mast['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked"; ?>
			<input id="activo" name="estado" type="radio" value="A" <?=$flagactivo?> /> Activo &nbsp;&nbsp;
			<input id="inactivo" name="estado" type="radio" value="I" <?=$flaginactivo?> /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$field_mast['UltimoUsuario']?>" disabled="disabled" />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field_mast['UltimoFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'ap_cuentas_bancarias.php');" />
</center>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</form>


<div style="width:700px" class="divFormCaption">Tipos de Pago Disponible</div>
<form name="frmdetalles" id="frmdetalles">
<input type="hidden" id="seldetalle" />
<table width="700" class="tblBotones">
    <tr>
        <td><div id="rows"></div></td>
        <td align="right">
            <input type="button" class="btLista" value="Insertar" onclick="insertarTipoPagoDisponible();" />
            <input type="button" class="btLista" value="Quitar" onclick="quitarTipoPagoDisponible(document.getElementById('seldetalle').value);" />
        </td>
    </tr>
</table>
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:700px; height:125px;">
<table width="100%" class="tblLista">
    <tr class="trListaHead">
        <th scope="col" width="50%">Tipo de Pago</th>
        <th scope="col">&Uacute;ltimo N&uacute;mero Generado</th>
    </tr>
    
    <tbody id="listaDetalles">
    <?
    $sql = "SELECT *
			FROM ap_ctabancariatipopago
			WHERE NroCuenta = '".$registro."'";
	$query_det = mysql_query($sql) or die ($sql.mysql_error());
	$nrodetalles = mysql_num_rows($query_det);	$i=0;
	while($field_det = mysql_fetch_array($query_det)) {	$i++;
		?>
		<tr class="trListaBody" onclick="mClk(this, 'seldetalle');" id="<?=$i?>">
			<td width="50%" align="center">
                <select name="tpago" style="width:95%">
                    <?=loadSelect("masttipopago", "CodTipoPago", "TipoPago", $field_det['CodTipoPago'], 0);?>
                </select>
            </td>
            <td align="right"><?=number_format($field_det['UltimoNumero'], 2, ',', '.')?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div></td></tr></table>
<input type="hidden" id="nrodetalles" value="<?=$nrodetalles?>" />
<input type="hidden" id="cantdetalles" value="<?=$nrodetalles?>" />
</form>

</body>
</html>
