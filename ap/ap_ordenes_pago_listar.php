<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
extract($_GET);
extract($_POST);
//	------------------------------------
include("fphp_ap.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('02', $concepto);
//	------------------------------------
if ($accion == "LISTAR") {
	$titulo = "Lista de Ordenes de Pago";
	$btPrepago = "disabled";
	$btPagoParcial = "disabled";
	$btBeneficiario = "disabled";
}
elseif ($accion == "PREPAGO") {
	$titulo = "Preparaci&oacute;n del Pre-Pago";
	$btImprimir = "disabled";
	$filtrop = " AND op.Estado = 'PE'";
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
	$fordenar = "NomProveedorPagar, CodTipoDocumento, NroDocumento";
	$fprocesod = date("01-m-Y");
	$fprocesoh = date("d-m-Y");
}
$dsfuente = "disabled";
$csfuente = "";
//	-------------------------------
if ($forganismo != "") { $corganismo = "checked"; $filtrop.=" AND (op.CodOrganismo = '".$forganismo."')"; } else $dorganismo = "disabled";
if ($fcodproveedor != "") { $cproveedor = "checked"; $filtrop.=" AND (op.CodProveedor = '".$fcodproveedor."')"; } else $dproveedor = "disabled";
if ($ftdoc != "") { $ctdoc = "checked"; $filtrop.=" AND (op.CodTipoDocumento = '".$ftdoc."')"; } else $dtdoc = "disabled";
if ($fndoc != "") { $cndoc = "checked"; $filtrop.=" AND (op.NroDocumento LIKE '%".$fndoc."%')"; } else $dndoc = "disabled";
if ($chkflagpago) { $cflagpago = "checked"; $filtrop.=" AND (op.FlagPagoDiferido = 'S')"; }
if ($fbanco != "") {
	$cbanco = "checked";
	if ($fctabancaria != "") $filtrop.= " AND (op.NroCuenta = '".$fctabancaria."')";
} else {
	$dbanco = "disabled";
	$dctabancaria = "disabled";
}
if ($fedoreg != "") { $cedoreg = "checked"; $filtrop.=" AND (op.Estado = '".$fedoreg."')"; } else $dedoreg = "disabled"; 
if ($fprocesod != "" || $fprocesoh != "") { 
	$cfproceso = "checked"; 
	if ($fprocesod != "") $filtrop.=" AND (op.FechaOrdenPago >= '".formatFechaAMD($fprocesod)."')";
	if ($fprocesoh != "") $filtrop.=" AND (op.FechaOrdenPago <= '".formatFechaAMD($fprocesoh)."')"; 
} else $dfproceso = "disabled";
if ($fmontosd != "" || $fmontosh != "") {
	$montosd = ereg_replace(",", "", $fmontosd);
	$montosh = ereg_replace(",", "", $fmontosh);	
	$cfmontos = "checked";
	if ($fmontosd != "") $filtrop.=" AND (op.MontoTotal >= ".$montosd.")";
	if ($fmontosh != "") $filtrop.=" AND (op.MontoTotal <= ".$montosh.")"; 
} else $dfmontos = "disabled";
if ($fordenar != "") $cordenar = "checked"; else $dordenar = "disabled";
?>

<form name="frmfiltro" id="frmfiltro" action="ap_ordenes_pago_listar.php" method="get">
<input type="hidden" name="accion" id="accion" value="<?=$accion?>" />
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
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
		<td align="right">Tipo Doc.:</td>
		<td>
			<input type="checkbox" name="chktdoc" id="chktdoc" value="1" <?=$ctdoc?> onclick="chkFiltro(this.checked, 'ftdoc');" />
			<select name="ftdoc" id="ftdoc" style="width:300px;" <?=$dtdoc?>>
            	<option value=""></option>
                <?=loadSelect("ap_tipodocumento", "CodTipoDocumento", "Descripcion", $ftdoc, 0)?>
			</select>
		</td>
		<td align="right">Sistema Fuente:</td>
		<td>
			<input type="checkbox" name="chksfuente" id="chksfuente" value="1" <?=$csfuente?> onclick="chkFiltro(this.checked, 'fsfuente');" />
			<select name="fsfuente" id="fsfuente" style="width:206px;" <?=$dsfuente?>>
            	<option value=""></option>
                <?=loadSelect("ac_sistemafuente", "CodSistemaFuente", "Descripcion", $fsfuente, 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Nro Doc.:</td>
		<td>
			<input type="checkbox" name="chkndoc" id="chkndoc" value="1" <?=$cndoc?> onclick="chkFiltro(this.checked, 'fndoc');" />
			<input type="text" name="fndoc" id="fndoc" value="<?=$fndoc?>" maxlength="6" style="width:95px;" <?=$dndoc?> />
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
		<td align="right">Estado:</td>
		<td>
            <input type="checkbox" <?=$cedoreg?>  onclick="chkFiltro(this.checked, 'fedoreg');" />
            <select name="fedoreg" id="fedoreg" style="width:100px;" <?=$dedoreg?>>
            	<option value="">&nbsp;</option>
                <?=loadSelectValores("ESTADO-ORDEN-PAGO", $fedoreg, 0)?>
            </select>
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
		<td align="right">F. Proceso: </td>
		<td>
			<input type="checkbox" name="chkfproceso" value="1" <?=$cfproceso?> onclick="chkFiltro_2(this.checked, 'fprocesod', 'fprocesoh');" />
			<input type="text" name="fprocesod" id="fprocesod" value="<?=$fprocesod?>" <?=$dfproceso?> maxlength="10" style="width:95px;" /> - 
            <input type="text" name="fprocesoh" id="fprocesoh" value="<?=$fprocesoh?>" <?=$dfproceso?> maxlength="10" style="width:95px;" />
        </td>
		<td align="right">Montos: </td>
		<td>
			<input type="checkbox" name="chkfmontos" value="1" <?=$cfmontos?> onclick="chkFiltro_2(this.checked, 'fmontosd', 'fmontosh');" />
			<input type="text" name="fmontosd" id="fmontosd" value="<?=$fmontosd?>" <?=$dfmontos?> maxlength="10" style="width:95px;" /> - 
            <input type="text" name="fmontosh" id="fmontosh" value="<?=$fmontosh?>" <?=$dfmontos?> maxlength="10" style="width:95px;" />
        </td>
	</tr>
	<tr>
		<td align="right">Ordenar por: </td>
    	<td>
            <input type="checkbox" name="chkordenar" id="chkordenar" value="1" <?=$cordenar?> onclick="forzarCheck(this.id)" />
            <select name="fordenar" id="fordenar" style="width:175px;" <?=$dordenar?>>
                <?=loadSelectValores("ORDENAR-ORDEN-PAGO", $fordenar, 0)?>
            </select>
        </td>
		<td>&nbsp;</td>
		<td><input type="checkbox" name="chkflagpago" id="chkflagpago" value="1" <?=$cflagpago?> /> Pago Diferido</td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>
<input type="hidden" name="registro" id="registro" />
</form>
<br />
<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input type="button" class="btLista" id="btVer" value="Ver" />
			<input type="button" class="btLista" id="btEditar" value="Editar" onclick="validarSeleccionOrden('ACTUALIZAR');" />
			<input type="button" class="btLista" id="btAnular" value="Anular" onclick="validarSeleccionOrden('ANULAR');" />
			<input type="button" class="btLista" id="btImprimir" value="Imprimir" onclick="validarSeleccionOrden('IMPRIMIR');" <?=$btImprimir?> /> | 
			<input type="button" style="width:100px;" id="btPrepago" value="Generar Prepago" onclick="validarSeleccionOrden('PREPAGO');" <?=$btPrepago?> />
			<input type="button" style="width:100px;" id="btPagoParcial" value="Pago Parcial" <?=$btPagoParcial?> />
			<input type="button" style="width:100px;" id="btBeneficiario" value="Ret. Beneficiario" <?=$btBeneficiario?> />
		</td>
	</tr>
</table>

<form name="frmdetalles" id="frmdetalles">
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:350px;">
<table width="2100" class="tblLista">
	<tr class="trListaHead">
		<th width="25" scope="col">Sist.</th>
		<th width="75" scope="col">Organismo</th>
		<th scope="col">Pagar A</th>
		<th width="100" scope="col">Doc. Fiscal</th>
		<th width="150" scope="col">Nro. Documento</th>
		<th width="100" scope="col">Total a Pagar</th>        
		<th width="90" scope="col">Fecha Venc.</th>
		<th width="90" scope="col">Fecha Prog. Pago</th>
		<th width="100" scope="col">Cta. Bancaria</th>
		<th width="125" scope="col">Tipo Pago</th>
		<th width="100" scope="col">Voucher</th>
		<th width="100" scope="col">Nro. Registro</th>
		<th scope="col">Proveedor</th>
		<th width="80" scope="col">Estado</th>
		<th width="90" scope="col">Fecha Pago</th>
		<th width="50" scope="col">Pago Dif.</th>
		<th width="60" scope="col">C.Costo</th>
	</tr>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT
				op.*,
				p1.NomCompleto AS NomProveedor,
				p1.DocFiscal AS DocFiscalProveedor,
				p2.DocFiscal AS DocFiscalPagarA,
				tp.TipoPago
			FROM
				ap_ordenpago op
				INNER JOIN mastpersonas p1 ON (p1.CodPersona = op.CodProveedor)
				INNER JOIN mastpersonas p2 ON (p2.CodPersona = op.CodProveedorPagar)
				INNER JOIN masttipopago tp ON (tp.CodTipoPago = op.CodTipoPago)
			WHERE 1 $filtrop
			ORDER BY $fordenar";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while($field = mysql_fetch_array($query)) {
		$estado = printValores("ESTADO-ORDEN-PAGO", $field['Estado']);
		$flagpagodif = printFlag($field['FlagPagoDiferido']);
		$id = $field['CodOrganismo']."|".$field['NroOrden'];
		?>
		<tr class="trListaBody" onclick="mClkMulti(this);" id="row_<?=$id?>">
			<td align="center">
            	<input type="checkbox" name="detalle" id="<?=$id?>" value="<?=$id?>" style="display:none;" />
				<?=$field['CodAplicacion']?>
			</td>
			<td align="center"><?=$field['CodOrganismo']?></td>
			<td><?=($field['NomProveedorPagar'])?></td>
			<td><?=$field['DocFiscalPagarA']?></td>
			<td align="center"><?=$field['CodTipoDocumento']?>-<?=$field['NroDocumento']?></td>
			<td align="right"><strong><?=number_format($field['MontoTotal'], 2, ',', '.')?></strong></td>
			<td align="center"><?=formatFechaDMA($field['FechaVencimiento'])?></td>
			<td align="center"><?=formatFechaDMA($field['FechaProgramada'])?></td>
			<td align="center"><?=$field['NroCuenta']?></td>
			<td><?=($field['TipoPago'])?></td>
			<td align="center"><?=$field['Voucher']?></td>
			<td align="center"><?=$field['NroRegistro']?></td>
			<td><?=($field['NomProveedor'])?></td>
			<td align="center"><?=$estado?></td>
			<td align="center"><?=formatFechaDMA($field['FechaOrdenPago'])?></td>
			<td align="center"><?=$flagpagodif?></td>
			<td align="center"><?=$field['CodCentroCosto']?></td>
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