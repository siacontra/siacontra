<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
extract($_POST);
extract($_GET);
//	------------------------------------
include("../lib/fphp.php");
include("../lib/ap_fphp.php");
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('02', $concepto);
//	------------------------------------
if ($filtrar == "default") {
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fdesde = "01-".date("m-Y");
	$fhasta = getDiasMes(date("Y-m"))."-".date("m-Y");
	$fedoreg = "IM";
}
if ($forganismo != "") { $corganismo = "checked"; $filtro.=" AND (p.CodOrganismo = '".$forganismo."')"; } else $dorganismo = "disabled";
if ($fnroprepago != "") { $cnroprepago = "checked"; $filtro.=" AND (p.NroOrden = '".$fnroprepago."')"; } else $dnroprepago = "disabled";
if ($fproveedor != "") { $cproveedor = "checked"; $filtro.=" AND (p.CodProveedor = '".$fproveedor."')"; } else $dproveedor = "disabled"; 
if ($fedoreg != "") { $cedoreg = "checked"; $filtro.=" AND (p.Estado = '".$fedoreg."')"; } else $dedoreg = "disabled"; 
if ($fdesde != "" || $fhasta != "") {
	$cfecha = "checked";
	list($d, $m, $a) = split("[/.-]", $fdesde); $desde = "$a-$m-$d";
	list($d, $m, $a) = split("[/.-]", $fhasta); $hasta = "$a-$m-$d";
	if ($fdesde != "") $filtro .= " AND (p.FechaPago >= '".$desde."')";
	if ($fhasta != "") $filtro .= " AND (p.FechaPago <= '".$hasta."')";
} else $dfecha = "disabled";
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/ap_funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/ap_fscript.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Pagos</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="ap_pagos_lista.php" method="post">
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
			<input type="checkbox" <?=$corganismo?> onclick="this.checked=!this.checked;" />
			<select name="forganismo" id="forganismo" <?=$dorganismo?> style="width:300px;">
				<option value=""></option>
				<?=getOrganismos($forganismo, 3);?>
			</select>
		</td>
		<td align="right" width="125">Nro. Pre-pago:</td>
		<td>
			<input type="checkbox" <?=$cnroprepago?> onclick="chkFiltro(this.checked, 'nroprepago')" />
			<input type="text" name="nroprepago" value="<?=$nroprepago?>" style="width:75px;" <?=$dnroprepago?> />
		</td>
	</tr>
	<tr>
		<td align="right">Proveedor:</td>
		<td>
			<input type="checkbox" onclick="chkFiltroLista(this.checked, 'fproveedor', 'fnomproveedor', 'btProveedor');" />
			<input type="hidden" name="fproveedor" id="fproveedor" />
			<input type="text" name="fnomproveedor" id="fnomproveedor" style="width:250px;" disabled="disabled" />
			<input type="button" value="..." id="btProveedor" onclick="cargarVentana(this.form, '../lib/listado_personas.php?limit=0&cod=fproveedor&nom=fnomproveedor&flagproveedor=S', 'height=800, width=775, left=50, top=0, resizable=yes');" disabled="disabled" />
		</td>
		<td align="right">Nro. Pago:</td>
		<td>
			<input type="checkbox" <?=$cnropago?> onclick="chkFiltro(this.checked, 'nropago')" />
			<input type="text" name="nropago" value="<?=$nropago?>" style="width:75px;" <?=$dnropago?> />
		</td>
	</tr>
	<tr>
		<td align="right">F. Pago:</td>
		<td>
			<input type="checkbox" <?=$cfecha?> onclick="chkFiltro_2(this.checked, 'fdesde', 'fhasta')" />
			<input type="text" name="fdesde" id="fdesde" maxlength="10" style="width:60px;" value="<?=$fdesde?>" <?=$dfecha?> /> - 
			<input type="text" name="fhasta" id="fhasta" maxlength="10" style="width:60px;" value="<?=$fhasta?>" <?=$dfecha?> />
		</td>
        
		<td align="right">Estado:</td>
		<td>
            <input type="checkbox" <?=$cedoreg?> onclick="this.checked=!this.checked;" />
            <select name="fedoreg" id="fedoreg" style="width:144px;" <?=$dedoreg?>>
                <?=loadSelectValores("ESTADO-PAGO", $fedoreg, 1)?>
            </select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input type="button" id="btSustento" value="Imprimir Sutento" style="width:115px;" onclick="cargarOpcion(this.form, 'ap_pagos_sustento_pdf.php?', 'BLANK', 'height=900, width=1000, left=100, top=0, resizable=no');" />
            
			<input type="button" id="btCheque" value="Re-Imprimir Cheque" style="width:115px;" onclick="cargarOpcion(this.form, 'ap_pagos_cheque_pdf.php?', 'BLANK', 'height=900, width=1000, left=100, top=0, resizable=no');" /> | 
            
			<input type="button" id="btVoucher" value="Voucher" style="width:75px;" onclick="cargarOpcion(this.form, 'ap_pagos_voucher.php?', 'BLANK', 'height=650, width=1000, left=100, top=100, resizable=no');" />
            
			<input type="button" id="btVer" value="Ver Pago" style="width:75px;" onclick="cargarOpcion(this.form, 'ap_cheques_form.php?opcion=ver', 'BLANK', 'height=500, width=1050, left=100, top=0, resizable=no');" />
		</td>
	</tr>
</table>

<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:400px;">
<table width="1500" class="tblLista">
	<thead>
	<tr class="trListaHead">
		<th scope="col" width="60">Organismo</th>
		<th scope="col" width="100">Cta. Bancaria</th>
		<th scope="col" width="100">N&uacute;mero</th>
		<th scope="col">Pagar A</th>
		<th scope="col" width="100">Monto</th>
		<th scope="col" width="75">Fecha</th>
		<th scope="col" width="75">Estado</th>
		<th scope="col" width="75">Pre-Pago</th>
		<th scope="col" width="50">Sec.</th>
		<th scope="col" width="100">Tipo de Pago</th>
		<th scope="col" width="100">Origen</th>
		<th scope="col" width="125">Voucher</th>
		<th scope="col" width="125">Voucher Anulaci&oacute;n</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	$sql = "SELECT
				p.*,
				tp.TipoPago,
				sf.Descripcion AS Origen
			FROM
				ap_pagos p
				INNER JOIN masttipopago tp ON (p.CodTipoPago = tp.CodTipoPago)
				LEFT JOIN ac_sistemafuente sf ON (p.CodSistemaFuente = sf.CodSistemaFuente)
			WHERE 1 $filtro
			ORDER BY NroProceso, Secuencia";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		$id = $field['NroProceso'].".".$field['Secuencia'];
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$field['CodOrganismo']?></td>
			<td align="center"><?=$field['NroCuenta']?></td>
			<td align="center"><?=$field['NroPago']?></td>
			<td><?=($field['NomProveedorPagar'])?></td>
			<td align="right"><strong><?=number_format($field['MontoPago'], 2, ',', '.')?></strong></td>
			<td align="center"><?=formatFechaDMA($field['FechaPago'])?></td>
			<td align="center"><?=printValores("ESTADO-PAGO", $field['Estado'])?></td>
			<td align="center"><?=$field['NroProceso']?></td>
			<td align="center"><?=$field['Secuencia']?></td>
			<td align="center"><?=($field['TipoPago'])?></td>
			<td align="center"><?=($field['CodSistemaFuente'])?></td>
			<td align="center"><?=$field['Periodo']?>-<?=$field['VoucherPago']?></td>
			<td align="center"><?=$field['VoucherAnulacion']?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div></td></tr></table>
</form>
<script type="text/javascript" language="javascript">
	totalRegistros(parseInt(<?=$rows?>), "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
</script>
</body>
</html>