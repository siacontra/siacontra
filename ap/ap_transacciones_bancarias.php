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
	$titulo = "Lista de Transacciones Bancarias";
	$btActualizar = "disabled";
	$btDesactualizar = "disabled";
}
elseif ($accion == "ACTUALIZAR") {
	$titulo = "Actualizar Transacciones Bancarias";
	$btNuevo = "disabled";
	$btEditar = "disabled";
	$btEliminar = "disabled";
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
}
//	-------------------------------
if ($forganismo != "") { $corganismo = "checked"; $filtro.=" AND (bt.CodOrganismo = '".$forganismo."')"; } else $dorganismo = "disabled";
if ($fprocesod != "" || $fprocesoh != "") { 
	$cfproceso = "checked"; 
	if ($fprocesod != "") $filtro.=" AND (bt.FechaOrdenPago >= '".formatFechaAMD($fprocesod)."')";
	if ($fprocesoh != "") $filtro.=" AND (bt.FechaOrdenPago <= '".formatFechaAMD($fprocesoh)."')"; 
} else $dfproceso = "disabled";
if ($fttransaccion != "") { $cttransaccion = "checked"; $filtro.=" AND (bt.CodTipoTransaccion = '".$fttransaccion."')"; } else $dttransaccion = "disabled";
if ($ftdoc != "") { $ctdoc = "checked"; $filtro.=" AND (bt.CodTipoDocumento = '".$ftdoc."')"; } else $dtdoc = "disabled";
if ($fperiodo != "") { $cperiodo = "checked"; $filtro.=" AND (bt.Periodo LIKE '%".$fperiodo."%')"; } else $dperiodo = "disabled";
if ($fbanco != "") {
	$cbanco = "checked";
	if ($fctabancaria != "") $filtro.= " AND (bt.NroCuenta = '".$fctabancaria."')";
} else {
	$dbanco = "disabled";
	$dctabancaria = "disabled";
}
if ($festado != "") { $cestado = "checked"; $filtro.=" AND (bt.Estado = '".$festado."')"; } else $destado = "disabled";
?>

<form name="frmfiltro" id="frmfiltro" action="ap_transacciones_bancarias.php" method="get">
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
		<td width="125" align="right">Fecha: </td>
		<td>
			<input type="checkbox" name="chkfproceso" value="1" <?=$cfproceso?> onclick="chkFiltro_2(this.checked, 'fprocesod', 'fprocesoh');" />
			<input type="text" name="fprocesod" id="fprocesod" value="<?=$fprocesod?>" <?=$dfproceso?> maxlength="10" style="width:95px;" /> - 
            <input type="text" name="fprocesoh" id="fprocesoh" value="<?=$fprocesoh?>" <?=$dfproceso?> maxlength="10" style="width:95px;" />
        </td>
	</tr>
	<tr>
		<td align="right">Tipo de Transacci&oacute;n:</td>
		<td>
			<input type="checkbox" name="chkttransaccion" id="chkttransaccion" value="1" <?=$cttransaccion?> onclick="chkFiltro(this.checked, 'fttransaccion');" />
			<select name="fttransaccion" id="fttransaccion" style="width:300px;" <?=$dttransaccion?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("ap_bancotipotransaccion", "CodTipoTransaccion", "Descripcion", $fttransaccion, 0)?>
			</select>
		</td>
		<td align="right">Tipo Doc.:</td>
		<td>
			<input type="checkbox" name="chktdoc" id="chktdoc" value="1" <?=$ctdoc?> onclick="chkFiltro(this.checked, 'ftdoc');" />
			<select name="ftdoc" id="ftdoc" style="width:215px;" <?=$dtdoc?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("ap_tipodocumento", "CodTipoDocumento", "Descripcion", $ftdoc, 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Periodo:</td>
		<td>
			<input type="checkbox" name="chkperiodo" id="chkperiodo" value="1" <?=$cperiodo?> onclick="chkFiltro(this.checked, 'fperiodo');" />
			<input type="text" name="fperiodo" id="fperiodo" value="<?=$fperiodo?>" maxlength="6" style="width:85px;" <?=$dperiodo?> />
		</td>
		<td align="right">Banco:</td>
		<td>
			<input type="checkbox" name="chkbanco" id="chkbanco" value="1" <?=$cbanco?> onclick="chkFiltro_2(this.checked, 'fbanco', 'fctabancaria');" />
			<select name="fbanco" id="fbanco" style="width:215px;" <?=$dbanco?> onchange="getOptions_2(this.id, 'fctabancaria');">
            	<option value="">&nbsp;</option>
                <?=loadSelect("mastbancos", "CodBanco", "Banco", $fbanco, 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Estado: </td>
		<td>
			<input type="checkbox" name="chkestado" id="chkestado" value="1" <?=$cestado?> onclick="chkFiltro(this.checked, 'festado');" />
            <select name="festado" id="festado" style="width:90px;" <?=$destado?>>
            	<option value="">&nbsp;</option>
                <?=loadSelectValores("ESTADO-TRANSACCION-BANCARIA", $festado, 0)?>
            </select>
        </td>
		<td align="right">Cta. Bancaria:</td>
		<td>
			<input type="checkbox" style="visibility:hidden;" />
			<select name="fctabancaria" id="fctabancaria" style="width:215px;" <?=$dctabancaria?>>
            	<option value="">&nbsp;</option>
                <?=loadSelectDependiente("ap_ctabancaria", "NroCuenta", "NroCuenta", "CodBanco", $fctabancaria, $fbanco, 0);?>
			</select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>
<input type="hidden" name="registro" id="registro" />
<br />
<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input type="button" class="btLista" id="btNuevo" value="Agregar" onclick="cargarPagina(this.form, 'ap_transacciones_bancarias_nuevo.php');" <?=$btNuevo?> />
			<input type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'ap_transacciones_bancarias_editar.php?acc=MODIFICAR', 'SELF');" <?=$btEditar?> />
			<input type="button" class="btLista" id="btEliminar" value="Eliminar" <?=$btEliminar?> />
			<input type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'ap_transacciones_bancarias_editar.php?acc=VER', 'SELF');" />
			<input type="button" class="btLista" id="btImprimir" value="Imprimir" onclick="cargarOpcion(this.form, 'ap_transacciones_bancarias_pdf.php?', 'BLANK');" /> | 
			<input type="button" style="width:100px;" id="btActualizar" value="Actualizar" onclick="cargarOpcion(this.form, 'ap_transacciones_bancarias_editar.php?acc=ACTUALIZAR', 'SELF');" <?=$btActualizar?> />
			<input type="button" style="width:100px;" id="btDesactualizar" value="Desactualizar" onclick="cargarOpcion(this.form, 'ap_transacciones_bancarias_editar.php?acc=DESACTUALIZAR', 'SELF');" <?=$btDesactualizar?> />
		</td>
	</tr>
</table>

<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:300px;">
<table width="2250" class="tblLista">
	<tr class="trListaHead">    
		<th scope="col" width="75">N&uacute;mero</th>
		<th scope="col" width="25">#</th>
		<th scope="col" width="75">Organismo</th>
		<th scope="col" width="75">Fecha</th>
		<th scope="col" width="350">Transacci&oacute;n</th>
		<th scope="col" width="125">Monto</th>
		<th scope="col" width="175">Cuenta Bancaria</th>
		<th scope="col" width="75">Periodo</th>
		<th scope="col" width="75">Voucher</th>
		<th scope="col" width="100">Estado</th>
		<th scope="col" width="150">Nro. Documento</th>
		<th scope="col" width="150">Doc. Referencia Banco</th>
		<th scope="col" width="150">Cheque</th>
		<th scope="col">Comentarios</th>
	</tr>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT
				bt.*,
				btt.Descripcion AS NomTipoTransaccion
			FROM
				ap_bancotransaccion bt
				INNER JOIN ap_bancotipotransaccion btt ON (bt.CodTipoTransaccion = btt.CodTipoTransaccion)
			WHERE 1 $filtro
			ORDER BY NroTransaccion, Secuencia";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while($field = mysql_fetch_array($query)) {
		$flagpagodif = printFlag($field['FlagPagoDiferido']);
		$id = $field['NroTransaccion']."|".$field['Secuencia']."|".$field['Estado'];
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$field['NroTransaccion']?></td>
			<td align="center"><?=$field['Secuencia']?></td>
			<td align="center"><?=$field['CodOrganismo']?></td>
			<td align="center"><?=formatFechaDMA($field['FechaTransaccion'])?></td>
			<td><?=($field['NomTipoTransaccion'])?></td>
			<td align="right"><strong><?=number_format($field['Monto'], 2, ',', '.')?></strong></td>
			<td align="center"><?=$field['NroCuenta']?></td>
			<td align="center"><?=$field['PeriodoContable']?></td>
			<td align="center"><?=$field['Voucher']?></td>
			<td align="center"><?=printValores("ESTADO-TRANSACCION-BANCARIA", $field['Estado'])?></td>
			<td align="center"><?=$field['CodigoReferenciaInterno']?></td>
			<td><?=$field['CodigoReferenciaBanco']?></td>
			<td align="center">
				<?
                	echo $field['NroPago']
				?>
            </td>
			<td><?=($field['Comentarios'])?></td>
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