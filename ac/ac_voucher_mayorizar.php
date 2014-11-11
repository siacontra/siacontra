<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
extract($_POST);
extract($_GET);
//	------------------------------------
include("../lib/fphp.php");
include("../lib/ac_fphp.php");
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('04', $concepto);
//	------------------------------------
if ($opcion == "mayorizar") {
	$titulo = "Mayorizarizaci&oacute;n de Vouchers";
} else {
	$titulo = "Des-Mayorizarizaci&oacute;n de Vouchers";
}
//	------------------------------------
if ($filtrar == "default") {
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fperiodo = date("Y-m");
	if ($opcion == "mayorizar") $festado = "AP"; else $festado = "MA";
}
//	------------------------------------
$filtro = "";
if ($forganismo != "") { $corganismo = "checked"; $filtro .= " AND vm.CodOrganismo = '".$forganismo."'"; } else $dorganismo = "disabled";
if ($fvoucherd != "" || $fvoucherh != "") {
	if ($fvoucherd != "") $filtro .= " AND vm.Voucher >= '".$fvoucherd."'";
	if ($fvoucherh != "") $filtro .= " AND vm.Voucher <= '".$fvoucherh."'";
	$cvoucher = "checked";
} else $dvoucher = "disabled";
if ($fdependencia != "") { $cdependencia = "checked"; $filtro .= " AND vm.CodDependencia = '".$fdependencia."'"; } else $ddependencia = "disabled";
if ($flibro_contable != "") { $clibro_contable = "checked"; $filtro .= " AND vm.CodLibroCont = '".$flibro_contable."'"; } else $dlibro_contable = "disabled";
if ($ftipo_voucher != "") { $ctipo_voucher = "checked"; $filtro .= " AND vm.CodVoucher = '".$ftipo_voucher."'"; } else $dtipo_voucher = "disabled";
if ($fperiodo != "") { $cperiodo = "checked"; $filtro .= " AND vm.Periodo = '".$fperiodo."'"; } else $dperiodo = "disabled";
if ($festado != "") { $cestado = "checked"; $filtro .= " AND vm.Estado = '".$festado."'"; } else $destado = "disabled";
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/ac_funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/ac_fscript.js"></script>
</head>

<body onload="document.getElementById('fperiodo').focus();">
<div id="bloqueo" class="divBloqueo"></div>
<div id="cargando" class="divCargando">
<table>
	<tr>
    	<td valign="middle" style="height:50px;">
			<img src="../imagenes/iconos/cargando.gif" /><br />Procesando...
        </td>
    </tr>
</table>
</div>

<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="ac_voucher_mayorizar.php" method="POST">
<input type="hidden" name="festado" id="festado" value="<?=$festado?>" />
<input type="hidden" name="opcion" id="opcion" value="<?=$opcion?>" />
<div class="divBorder" style="width:1100px;">
<table width="1100" class="tblFiltro">
	<tr>
		<td width="125" align="right">Organismo:</td>
		<td>
			<input type="checkbox" <?=$corganismo?> onclick="this.checked=!this.checked;" />
			<select name="forganismo" id="forganismo" style="width:300px;" <?=$dorganismo?> onchange="getFOptions_2(this.id, 'fdependencia', 'chkdependencia');">
				<?=getOrganismos($forganismo, 3);?>
			</select>
		</td>
		<td width="125" align="right">Voucher:</td>
		<td>
			<input type="checkbox" <?=$cvoucher?> onclick="chkFiltro_2(this.checked, 'fvoucherd', 'fvoucherh');" />
			<input type="text" name="fvoucherd" id="fvoucherd" value="<?=$fvoucherd?>" maxlength="7" style="width:65px;" <?=$dvoucher?> />-
			<input type="text" name="fvoucherh" id="fvoucherh" value="<?=$fvoucherh?>" maxlength="7" style="width:65px;" <?=$dvoucher?> />
		</td>
	</tr>
    <tr>
		<td align="right">Dependencia:</td>
		<td>
			<input type="checkbox" <?=$cdependencia?> onclick="chkFiltro(this.checked, 'fdependencia');" />
			<select name="fdependencia" id="fdependencia" style="width:300px;" <?=$ddependencia?>>
            	<option value=""></option>
				<?=getDependencias($fdependencia, $forganismo, 3);?>
			</select>
		</td>
		<td align="right">Libro Contable:</td>
		<td>
			<input type="checkbox" <?=$clibro_contable?> onclick="chkFiltro(this.checked, 'flibro_contable');" />
			<select name="flibro_contable" id="flibro_contable" style="width:150px;" <?=$dlibro_contable?>>
            	<option value=""></option>
				<?=loadSelect("ac_librocontable", "CodLibroCont", "Descripcion", $flibro_contable, 0)?>
			</select>
		</td>
	</tr>
    <tr>
		<td align="right">Tipo de Voucher:</td>
		<td>
			<input type="checkbox" <?=$ctipo_voucher?> onclick="chkFiltro(this.checked, 'ftipo_voucher');" />
			<select name="ftipo_voucher" id="ftipo_voucher" style="width:150px;" <?=$dtipo_voucher?>>
            	<option value=""></option>
				<?=loadSelect("ac_voucher", "CodVoucher", "Descripcion", $ftipo_voucher, 0)?>
			</select>
		</td>
		<td align="right">Per&iacute;odo:</td>
		<td>
			<input type="checkbox" name="chkperiodo" id="chkperiodo" value="1" <?=$cperiodo?> onclick="this.checked=!this.checked;" />
			<input type="text" name="fperiodo" id="fperiodo" value="<?=$fperiodo?>" maxlength="7" style="width:65px;" <?=$dperiodo?> />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center><br />

<input type="hidden" name="registro" id="registro" />
<table width="1100" class="tblBotones">
	<tr>
		<td align="right">
			<input type="button" id="btEjecutar" value="Ejecutar Proceso" onclick="voucher_mayorizar(this.form, '<?=$opcion?>');" />
		</td>
	</tr>
</table>

<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1100px; height:350px;">
<table width="1300" class="tblLista">
<thead>
	<tr class="trListaHead">
		<th width="75" scope="col">Organismo</th>
		<th width="75" scope="col">Periodo</th>
		<th width="125" scope="col">Libro</th>
		<th width="75" scope="col">Voucher</th>
		<th width="75" scope="col">Fecha</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="100" scope="col">Monto</th>
		<th width="125" scope="col">Origen</th>
		<th width="300" scope="col">Dependencia</th>
	</tr>
    </thead>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT
				vm.*,
				o.Organismo,
				d.Dependencia,
				lc.Descripcion AS NomLibroContable,
				sf.Descripcion AS NomSistemaFuente
			FROM
				ac_vouchermast vm
				INNER JOIN mastorganismos o ON (vm.CodOrganismo = o.CodOrganismo)
				INNER JOIN ac_controlcierremensual ccm ON (vm.CodOrganismo AND ccm.CodOrganismo AND
														   vm.Periodo = ccm.Periodo AND
														   ccm.TipoRegistro = 'AB' AND
														   ccm.Estado = 'A')
				LEFT JOIN mastdependencias d ON (vm.CodDependencia = d.CodDependencia)
				LEFT JOIN ac_librocontable lc ON (vm.CodLibroCont = lc.CodLibroCont)
				LEFT JOIN ac_sistemafuente sf ON (vm.CodSistemaFuente = sf.CodSistemaFuente)
			WHERE 1 $filtro
			ORDER BY vm.Periodo, vm.CodOrganismo, vm.Voucher";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		?>
		<tr class="trListaBody" onclick="mClkMulti(this);" id="row_<?=$field['Voucher']?>">
			<td align="center">
            	<input type="checkbox" name="voucher" id="<?=$field['Voucher']?>" value="<?=$field['Voucher']?>" style="display:none;" />
				<?=$field['CodOrganismo']?>
            </td>
			<td align="center"><?=$field['Periodo']?></td>
			<td align="center"><?=htmlentities($field['NomLibroContable'])?></td>
			<td align="center"><?=$field['Voucher']?></td>
			<td align="center"><?=formatFechaDMA($field['FechaVoucher'])?></td>
			<td><?=htmlentities($field['TituloVoucher'])?></td>            
			<td align="right"><?=number_format($field['Debitos'], 2, ',', '.')?></td>
			<td align="center"><?=htmlentities($field['NomSistemaFuente'])?></td>
			<td><?=htmlentities($field['Dependencia'])?></td>
		</tr>
		<?
	}
	?>
</table>
</div></td></tr></table>
</form>
<script type="text/javascript" language="javascript">
	totalRegistros(parseInt(<?=$rows?>), "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
</script>
</body>
</html>