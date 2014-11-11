<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
extract($_GET);
extract($_POST);
//	------------------------------------
include("fphp_ap.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('06', $concepto);
//	------------------------------------
if ($accion == "ENTREGA") {
	$titulo = "Entregar Cheques";
	$festado_entrega = "C";
	$proceso = "entregarCheque";
	$display_cobranza = "display:none;";
}
elseif ($accion == "DEVOLUCION") {
	$titulo = "Devolver Cheques";
	$festado_entrega = "E";
	$proceso = "devolverCheque";
	$display_cobranza = "display:none;";
}
elseif ($accion == "COBRADO") {
	$titulo = "Ingreso de Cheques Cobrados";
	$proceso = "cobrarCheque";
	$display_cobranza = "display:block;";
}
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_ap.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?
$MAXLIMIT=30;
//	-------------------------------
if ($filtrar == "DEFAULT") {
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$festado = "IM";
	$ftpago = "02";
	$fsituacion = "N";
}
//	-------------------------------
if ($forganismo != "") { $corganismo = "checked"; $filtro.=" AND (p.CodOrganismo = '".$forganismo."')"; } else $dorganismo = "disabled";
if ($fcodproveedor != "") { $cproveedor = "checked"; $filtro.=" AND (p.CodProveedor = '".$fcodproveedor."')"; } else $dproveedor = "disabled";
if ($fnprepago != "") { $cnprepago = "checked"; $filtro.=" AND (p.NroOrden = '".$fnprepago."')"; } else $dnprepago = "disabled";
if ($fbanco != "") {
	$cbanco = "checked";
	if ($fctabancaria != "") $filtro.= " AND (p.NroCuenta = '".$fctabancaria."')";
} else {
	$dbanco = "disabled";
	$dctabancaria = "disabled";
}
if ($fnpago != "") { $cnpago = "checked"; $filtro.=" AND (p.NroPago = '".$fnpago."')"; } else $dnpago = "disabled";
if ($ffechad != "" || $ffechah != "") { 
	$cffecha = "checked"; 
	if ($ffechad != "") $filtro.=" AND (p.FechaPago >= '".$ffechad."')";
	if ($ffechah != "") $filtro.=" AND (p.FechaPago <= '".$ffechah."')"; 
} else $dffecha = "disabled";
if ($festado != "") { $cestado = "checked"; $filtro.=" AND (p.Estado = '".$festado."')"; } else $destado = "disabled";
if ($festado_entrega != "") { $cestado_entrega = "checked"; $filtro.=" AND (p.EstadoEntrega = '".$festado_entrega."')"; } else $destado_entrega = "disabled";
if ($ftpago != "") { $ctpago = "checked"; $filtro.=" AND (p.CodTipoPago = '".$ftpago."')"; } else $dtpago = "disabled";
if ($fvencido) {
	$cfvencido = "checked";
	$_CHEQUEVENCE = getParametro("CHEQUEVENCE");
	$filtro.=" AND (DATEDIFF(NOW(), p.FechaPago) >= $_CHEQUEVENCE)";
}
if ($fsituacion != "") { $csituacion = "checked"; $filtro.="AND (FlagCobrado = '".$fsituacion."')"; } else $fsituacion = "disabled";
?>

<form name="frmfiltro" id="frmfiltro" action="ap_cheques.php" method="get">
<input type="hidden" name="accion" id="accion" value="<?=$accion?>" />
<div class="divBorder" style="width:900px;">
<table width="900" class="tblFiltro">
	<tr>
		<td width="125" align="right">Organismo:</td>
		<td>
			<input type="checkbox" name="chkorganismo" id="chkorganismo" value="1" checked="checked" onclick="forzarCheck(this.id)" />
			<select name="forganismo" id="forganismo" style="width:300px;">
				<?=getOrganismos($forganismo, 3);?>
			</select>
		</td>
		<td width="125" align="right">Proveedor: </td>
		<td>
			<input type="checkbox" name="chkproveedor" value="1" <?=$cproveedor?> onclick="chkFiltroProveedor(this.checked);" />
        	<input type="hidden" name="fcodproveedor" id="fcodproveedor" value="<?=$fcodproveedor?>" />
			<input type="text" name="fnomproveedor" id="fnomproveedor" value="<?=$fnomproveedor?>" readonly="readonly" style="width:200px;" />
			<input type="button" value="..." id="btProveedor" <?=$dproveedor?> onclick="cargarVentana(this.form, 'listado_personas.php?ventana=&cod=fcodproveedor&nom=fnomproveedor&limit=0&flagproveedor=S', 'height=600, width=775, left=50, top=50, resizable=yes');" />
        </td>
	</tr>
	<tr>
		<td align="right">Nro. Prepago:</td>
		<td>
			<input type="checkbox" name="chknprepago" id="chknprepago" value="1" <?=$cnprepago?> onclick="chkFiltro(this.checked, 'fnprepago');" />
			<input type="text" name="fnprepago" id="fnprepago" value="<?=$fnprepago?>" maxlength="6" style="width:80px;" <?=$dnprepago?> />
		</td>
		<td align="right">Banco:</td>
		<td>
			<input type="checkbox" name="chkbanco" id="chkbanco" value="1" <?=$cbanco?> onclick="chkFiltro_2(this.checked, 'fbanco', 'fctabancaria');" />
			<select name="fbanco" id="fbanco" style="width:206px;" <?=$dbanco?> onchange="getOptions_2(this.id, 'fctabancaria');">
            	<option value=""></option>
                <?=loadSelect("mastbancos", "CodBanco", "Banco", $fbanco, 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Nro. Pago: </td>
		<td>
			<input type="checkbox" name="chknpago" id="chknpago" value="1" <?=$cnpago?> onclick="chkFiltro(this.checked, 'fnpago');" />
			<input type="text" name="fnpago" id="fnpago" value="<?=$fnpago?>" maxlength="6" style="width:80px;" <?=$dnpago?> />
		</td>
		<td align="right">Cta. Bancaria:</td>
		<td>
			<input type="checkbox" style="visibility:hidden;" />
			<select name="fctabancaria" id="fctabancaria" style="width:206px;" <?=$dctabancaria?>>
            	<option value="">&nbsp;</option>
                <?=loadSelectDependiente("ap_ctabancaria", "NroCuenta", "NroCuenta", "CodBanco", $fctabancaria, $fbanco, 0);?>
			</select>
		</td>
	</tr>
    <tr>
		<td align="right">Fecha: </td>
		<td>
			<input type="checkbox" name="chkffecha" value="1" <?=$cffecha?> onclick="chkFiltro_2(this.checked, 'ffechad', 'ffechah');" />
			<input type="text" name="ffechad" id="ffechad" value="<?=$ffechad?>" <?=$dffecha?> maxlength="10" style="width:80px;" /> - 
            <input type="text" name="ffechah" id="ffechah" value="<?=$ffechah?>" <?=$dffecha?> maxlength="10" style="width:80px;" />
        </td>
        
        <?
		if ($accion == "COBRADO") {
			?>
            <td align="right">Situaci&oacute;n: </td>
            <td>
                <input type="hidden" name="festado" id="festado" value="<?=$festado?>" />
                <input type="hidden" name="festado_entrega" id="festado_entrega" value="<?=$festado_entrega?>" />
                <input type="checkbox" name="chkfsituacion" id="chkfsituacion" value="1" <?=$csituacion?> onclick="this.checked=!this.checked" />
                <select name="fsituacion" id="fsituacion" style="width:206px;" <?=$dsituacion?>>
                    <?=loadSelectValores("FLAG-COBRADO", $fsituacion, 0)?>
                </select>
            </td>
            <?
		} else {
			?>
            <td align="right">Estado: </td>
            <td>
                <input type="checkbox" style="visibility:hidden;" />
                <input type="hidden" name="fsituacion" id="fsituacion" value="<?=$fsituacion?>" />
                <input type="hidden" name="festado" id="festado" value="<?=$festado?>" />
                <input type="hidden" name="festado_entrega" id="festado_entrega" value="<?=$festado_entrega?>" />
                <input type="text" style="width:95px; font-weight:bold;" value="<?=printValores("ESTADO-CHEQUE", $festado_entrega)?>" disabled="disabled" />
            </td>
            <?
		}
		?>
	</tr>
    <tr>
		<td align="right">Tipo Pago:</td>
		<td>
			<input type="checkbox" name="chktpago" id="chktpago" value="1" <?=$ctpago?> onclick="chkFiltro(this.checked, 'ftpago');" />
			<select name="ftpago" id="ftpago" style="width:183px;" <?=$dtpago?>>
                <?=loadSelect("masttipopago", "CodTipoPago", "TipoPago", $ftpago, 1)?>
			</select>
		</td>
		<td align="right">&nbsp;</td>
		<td>
			<input type="checkbox" name="fvencido" id="fvencido" value="S" <?=$cfvencido?> /> Ver solo Vencidos
		</td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>
<input type="hidden" name="registro" id="registro" />
<br />
<table width="900" class="tblForm" id="cobranza" style=" <?=$display_cobranza?>">
	<tr>
        <td class="tagForm">Fecha de Cobranza:</td>
		<td><input type="text" id="fcobranza" style="width:100px;" /></td>
        <td class="tagForm">Nro. Operaci&oacute;n:</td>
		<td><input type="text" id="nrooperacion" style="width:100px;" /></td>
	</tr>
</table>
<table width="900" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(document.getElementById('frmfiltro'), 'ap_cheques_form.php?acc=VER&', 'BLANK', 'height=500, width=1100, left=50, top=50, resizable=no');" /> | 
            <input type="button" class="btLista" id="btProcesar" value="Procesar" onclick="cheque(document.getElementById('registro').value, this, this.form, '<?=$proceso?>');" />
		</td>
	</tr>
</table>
</form>

<form name="frmdetalles" id="frmdetalles">
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:900px; height:350px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th width="100" scope="col">Cheque</th>
		<th scope="col">Proveedor</th>
		<th width="100" scope="col">Fecha Pago</th>
		<th width="125" scope="col">Monto</th>
		<th width="100" scope="col">Prepago</th>
	</tr>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT
				p.*,
				(DATEDIFF(NOW(), p.FechaPago)) AS DiasPorVencer,
				mp.NomCompleto AS NomProveedor,
				b.CodBanco,
				b.Banco
			FROM
				ap_pagos p
				INNER JOIN mastpersonas mp ON (p.CodProveedor = mp.CodPersona)
				INNER JOIN ap_ctabancaria cb ON (p.NroCuenta = cb.NroCuenta)
				INNER JOIN mastbancos b ON (cb.CodBanco = b.CodBanco)
			WHERE 1 $filtro
			ORDER BY CodBanco, NroCuenta";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while($field = mysql_fetch_array($query)) {
		$id = "$field[NroProceso].$field[Secuencia]";
		
		if ($grupo != $field['NroCuenta']) {
			$grupo = $field['NroCuenta'];
			?>
            <tr class="trListaBody2">
                <td colspan="5">
                	Cta. Bancaria: &nbsp;
					<?=$field['NroCuenta']?> &nbsp;
					<?=($field['Banco'])?>
				</td>
            </tr>
            <?
		}
		
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$field['NroPago']?></td>
			<td><?=($field['NomProveedor'])?></td>
			<td align="center"><?=formatFechaDMA($field['FechaPago'])?></td>
			<td align="right"><strong><?=number_format($field['MontoPago'], 2, ',', '.')?></strong></td>
			<td align="center"><?=$field['NroProceso']?></td>
		</tr>
		<?
	}
	?>
</table>
</div></td></tr></table>
</form>

<script language="javascript">
totalRegistros(<?=intval($rows)?>, '<?=$_ADMIN?>', '<?=$_INSERT?>', '<?=$_UPDATE?>', '<?=$_DELETE?>');
</script>
</body>
</html>