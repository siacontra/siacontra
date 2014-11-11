<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_nomina.php");
$ahora=date("Y-m-d H:i:s");
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('05', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_nomina_2.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Interfase Cuentas por Pagar </td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?
$MAXLIMIT=30;
if ($filtrar == "DEFAULT") {
	$ftiponom = $_SESSION["NOMINA_ACTUAL"];
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$dSeleccion1 = "disabled"; 
	$dSeleccion2 = "disabled"; 
	$dfsittra = "disabled";
	$fperiodo = date("Y-m");
}
?>
<form id="frmfiltro" name="frmfilro" action="interfase_cuentas_por_pagar.php" method="POST">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
    <tr>
        <td align="right">Organismo:</td>
        <td>
        	<input type="checkbox" name="chkorganismo" id="chkorganismo" value="1" onclick="this.checked=!this.checked" checked="checked" />
			<select name="forganismo" id="forganismo" class="selectBig">
				<?=getOrganismos($forganismo, 3)?>
			</select>
        </td>
        <td align="right">N&oacute;mina:</td>
        <td>
        	<input type="checkbox" name="chktiponom" id="chktiponom" value="1" onclick="this.checked=!this.checked" checked="checked" />
			<select name="ftiponom" id="ftiponom" class="selectBig" onchange="getFOptions_Periodo(this.id, 'fperiodo', 'chkperiodo', document.getElementById('forganismo').value); getFOptions_Proceso('ftiponom', 'ftproceso', 'chktproceso', this.value, document.getElementById('forganismo').value);">
				<?=getTNomina($ftiponom, 0)?>
			</select>
        </td>
    </tr>
    <tr>
        <td align="right">Per&iacute;odo:</td>
        <td>
        	<input type="checkbox" name="chkperiodo" id="chkperiodo" value="1" onclick="this.checked=!this.checked" checked="checked" />
			<select name="fperiodo" id="fperiodo" style="width:100px;" onchange="getFOptions_Proceso(this.id, 'ftproceso', 'chktproceso', document.getElementById('ftiponom').value, document.getElementById('forganismo').value);">
				<option value=""></option>
				<?=getPeriodos($fperiodo, $ftiponom, $forganismo, 0);?>
			</select>
		</td>
        <td align="right">Proceso:</td>
        <td>
        	<input type="checkbox" name="chktproceso" id="chktproceso" value="1" onclick="this.checked=!this.checked" checked="checked" />
			<select name="ftproceso" id="ftproceso" class="selectBig">
                <?=getTipoProcesoNomina($ftproceso, $fperiodo, $ftiponom, $forganismo, 1)?>
			</select>
		</td>
    </tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Mostrar Resultados"></center><br />
</form>

<table width="1000" class="tblBotones">
	<tr>
		<td align="right">
			<input type="button" id="btSelTodos" value="Sel. Todos" style="width:75px;" onclick="selTodosObligaciones()" /> | 
			<input type="button" id="btCalcular" value="Calcular Obligaciones" style="width:125px;" onclick="calcularObligaciones(this);" />
			<input type="button" id="btConsolidar" value="Consolidar Obligacion" style="width:125px;" onclick="consolidarObligacion(this);" /> | 
			<input type="button" id="btGenerar" value="Generar Obligaciones" style="width:125px;" onclick="generarObligaciones(this);" />
		</td>
	</tr>
</table>

<table width="1000" align="center">
    <tr>
        <td>
            <div id="header">
            <ul>
            <!-- CSS Tabs -->
            <li><a onclick="document.getElementById('tab1').style.display='block'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none';" href="#">Interfase Bancaria</a></li>
            <li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none';" href="#">Cheques</a></li>
            <li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='block'; document.getElementById('tab4').style.display='none';" href="#">Pago a Terceros</a></li>
            <li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='block';" href="#">Retenciones Judiciales</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<div id="tab1" style="display:block;">
<form name="frmbancos" id="frmbancos">
<div style="width:1000px" class="divFormCaption">Interfase Bancaria</div>
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:300px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th scope="col" width="75">Proveedor</th>
		<th scope="col">Nombre del Proveedor</th>
		<th scope="col" width="75">Tipo Documento</th>
		<th scope="col" width="75">Nro. Documento</th>
		<th scope="col" width="75">Nro. Registro</th>
		<th scope="col" width="75">Fecha Registro</th>
		<th scope="col" width="50">Transf.</th>
		<th scope="col" width="100">Monto Obligaci&oacute;n</th>
	</tr>
	<?php
	$sql = "SELECT
				o.CodProveedor,
				o.NroDocumento,
				o.NroRegistro,
				o.FechaRegistro,
				o.FlagTransferido,
				o.MontoObligacion,
				o.TipoObligacion,
				mp.NomCompleto AS NomProveedor,
				td.CodTipoDocumento,
				td.Descripcion AS NomTipoDocumento
			FROM
				pr_obligaciones o
				INNER JOIN mastpersonas mp ON (o.CodProveedor = mp.CodPersona)
				INNER JOIN ap_tipodocumento td ON (o.CodTipoDocumento = td.CodTipoDocumento)
			WHERE
				o.TipoObligacion = '01' AND
				o.CodOrganismo = '".$forganismo."' AND
				o.CodTipoNom = '".$ftiponom."' AND
				o.Periodo = '".$fperiodo."' AND
				o.CodTipoProceso = '".$ftproceso."'";
	$query_bancos = mysql_query($sql) or die ($sql.mysql_error());
	$rows_bancos = mysql_num_rows($query_bancos);
	//	MUESTRO LA TABLA
	while ($field_bancos = mysql_fetch_array($query_bancos)) {
		$id = "$field_bancos[CodProveedor].$field_bancos[CodTipoDocumento].$field_bancos[NroDocumento].$field_bancos[Secuencia].$field_bancos[TipoObligacion]";
		$flagtransferido = printFlag($field_bancos['FlagTransferido']);
		?>
		<tr class="trListaBody" onclick="mClkMulti(this);" id="row_bancos<?=$id?>">
			<td align="center">
            	<input type="checkbox" name="bancos" id="bancos<?=$id?>" value="<?=$id?>" style="display:none" />
				<?=$field_bancos['CodProveedor']?>
			</td>
			<td><?=($field_bancos['NomProveedor'])?></td>
			<td align="center"><?=$field_bancos['NomTipoDocumento']?></td>
			<td align="center"><?=$field_bancos['NroDocumento']?></td>
			<td align="center"><?=$field_bancos['NroRegistro']?></td>
			<td align="center"><?=formatFechaDMA($field_bancos['FechaRegistro'])?></td>
			<td align="center"><?=$flagtransferido?></td>
			<td align="right"><?=number_format($field_bancos['MontoObligacion'], 2, ',', '.')?></td>
		</tr>
		<?
	}
	?>
</table>
</div></td></tr></table>
</form>
</div>

<div id="tab2" style="display:none;">
<form name="frmcheques" id="frmcheques">
<div style="width:1000px" class="divFormCaption">Cheques</div>
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:300px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th scope="col" width="75">Proveedor</th>
		<th scope="col">Nombre del Empleado</th>
		<th scope="col" width="75">Tipo Documento</th>
		<th scope="col" width="75">Nro. Documento</th>
		<th scope="col" width="75">Nro. Registro</th>
		<th scope="col" width="75">Fecha Registro</th>
		<th scope="col" width="50">Transf.</th>
		<th scope="col" width="100">Monto Obligaci&oacute;n</th>
	</tr>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT
				o.CodProveedor,
				o.NroDocumento,
				o.NroRegistro,
				o.FechaRegistro,
				o.FlagTransferido,
				o.MontoObligacion,
				mp.NomCompleto AS NomProveedor,
				td.Descripcion AS NomTipoDocumento,
				td.CodTipoDocumento
			FROM
				pr_obligaciones o
				INNER JOIN mastpersonas mp ON (o.CodProveedor = mp.CodPersona)
				INNER JOIN ap_tipodocumento td ON (o.CodTipoDocumento = td.CodTipoDocumento)
			WHERE
				o.TipoObligacion = '02' AND
				o.CodOrganismo = '".$forganismo."' AND
				o.CodTipoNom = '".$ftiponom."' AND
				o.Periodo = '".$fperiodo."' AND
				o.CodTipoProceso = '".$ftproceso."'";
	$query_cheques = mysql_query($sql) or die ($sql.mysql_error());
	$rows_cheques = mysql_num_rows($query_cheques);
	//	MUESTRO LA TABLA
	while ($field_cheques = mysql_fetch_array($query_cheques)) {
		$id = "$field_cheques[CodProveedor].$field_cheques[CodTipoDocumento].$field_cheques[NroDocumento].$field_cheques[Secuencia].$field_cheques[TipoObligacion]";
		$flagtransferido = printFlag($field_cheques['FlagTransferido']);
		?>
		<tr class="trListaBody" onclick="mClkMulti(this);" id="row_cheques<?=$id?>">
			<td align="center">
            	<input type="checkbox" name="cheques" id="cheques<?=$id?>" value="<?=$id?>" style="display:none" />
				<?=$field_cheques['CodProveedor']?>
			</td>
			<td><?=($field_cheques['NomProveedor'])?></td>
			<td align="center"><?=$field_cheques['NomTipoDocumento']?></td>
			<td align="center"><?=$field_cheques['NroDocumento']?></td>
			<td align="center"><?=$field_cheques['NroRegistro']?></td>
			<td align="center"><?=formatFechaDMA($field_cheques['FechaRegistro'])?></td>
			<td align="center"><?=$flagtransferido?></td>
			<td align="right"><?=number_format($field_cheques['MontoObligacion'], 2, ',', '.')?></td>
		</tr>
		<?
	}
	?>
</table>
</div></td></tr></table>
</form>
</div>

<div id="tab3" style="display:none;">
<form name="frmterceros" id="frmterceros">
<div style="width:1000px" class="divFormCaption">Pago a Terceros</div>
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:300px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th scope="col" width="75">Proveedor</th>
		<th scope="col">Nombre del Empleado</th>
		<th scope="col" width="75">Tipo Documento</th>
		<th scope="col" width="75">Nro. Documento</th>
		<th scope="col" width="75">Nro. Registro</th>
		<th scope="col" width="75">Fecha Registro</th>
		<th scope="col" width="50">Transf.</th>
		<th scope="col" width="100">Monto Obligaci&oacute;n</th>
	</tr>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT
				o.CodProveedor,
				o.NroDocumento,
				o.NroRegistro,
				o.FechaRegistro,
				o.FlagTransferido,
				o.MontoObligacion,
				mp.NomCompleto AS NomProveedor,
				td.Descripcion AS NomTipoDocumento,
				td.CodTipoDocumento
			FROM
				pr_obligaciones o
				INNER JOIN mastpersonas mp ON (o.CodProveedor = mp.CodPersona)
				INNER JOIN ap_tipodocumento td ON (o.CodTipoDocumento = td.CodTipoDocumento)
			WHERE
				o.TipoObligacion = '03' AND
				o.CodOrganismo = '".$forganismo."' AND
				o.CodTipoNom = '".$ftiponom."' AND
				o.Periodo = '".$fperiodo."' AND
				o.CodTipoProceso = '".$ftproceso."'";
	$query_terceros = mysql_query($sql) or die ($sql.mysql_error());
	$rows_terceros = mysql_num_rows($query_terceros);
	//	MUESTRO LA TABLA
	while ($field_terceros = mysql_fetch_array($query_terceros)) {
		$id = "$field_terceros[CodProveedor].$field_terceros[CodTipoDocumento].$field_terceros[NroDocumento].$field_terceros[Secuencia].$field_terceros[TipoObligacion]";
		$flagtransferido = printFlag($field_terceros['FlagTransferido']);
		?>
		<tr class="trListaBody" onclick="mClkMulti(this);" id="row_terceros<?=$id?>">
			<td align="center">
            	<input type="checkbox" name="terceros" id="terceros<?=$id?>" value="<?=$id?>" style="display:none" />
				<?=$field_terceros['CodProveedor']?>
			</td>
			<td><?=($field_terceros['NomProveedor'])?></td>
			<td align="center"><?=$field_terceros['NomTipoDocumento']?></td>
			<td align="center"><?=$field_terceros['NroDocumento']?></td>
			<td align="center"><?=$field_terceros['NroRegistro']?></td>
			<td align="center"><?=formatFechaDMA($field_terceros['FechaRegistro'])?></td>
			<td align="center"><?=$flagtransferido?></td>
			<td align="right"><?=number_format($field_terceros['MontoObligacion'], 2, ',', '.')?></td>
		</tr>
		<?
	}
	?>
</table>
</div></td></tr></table>
</form>
</div>

<div id="tab4" style="display:none;">
<form name="frmjudiciales" id="frmjudiciales">
<div style="width:1000px" class="divFormCaption">Retenciones Judiciales</div>
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:300px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th scope="col" width="75">Proveedor</th>
		<th scope="col">Nombre del Empleado</th>
		<th scope="col" width="75">Tipo Documento</th>
		<th scope="col" width="75">Nro. Documento</th>
		<th scope="col" width="75">Nro. Registro</th>
		<th scope="col" width="75">Fecha Registro</th>
		<th scope="col" width="50">Transf.</th>
		<th scope="col" width="100">Monto Obligaci&oacute;n</th>
	</tr>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT
				o.CodProveedor,
				o.NroDocumento,
				o.NroRegistro,
				o.FechaRegistro,
				o.FlagTransferido,
				o.MontoObligacion,
				mp.NomCompleto AS NomProveedor,
				td.Descripcion AS NomTipoDocumento,
				td.CodTipoDocumento
			FROM
				pr_obligaciones o
				INNER JOIN mastpersonas mp ON (o.CodProveedor = mp.CodPersona)
				INNER JOIN ap_tipodocumento td ON (o.CodTipoDocumento = td.CodTipoDocumento)
			WHERE
				o.TipoObligacion = '04' AND
				o.CodOrganismo = '".$forganismo."' AND
				o.CodTipoNom = '".$ftiponom."' AND
				o.Periodo = '".$fperiodo."' AND
				o.CodTipoProceso = '".$ftproceso."'";
	$query_judiciales = mysql_query($sql) or die ($sql.mysql_error());
	$rows_judiciales = mysql_num_rows($query_judiciales);
	//	MUESTRO LA TABLA
	while ($field_judiciales = mysql_fetch_array($query_judiciales)) {
		$flagtransferido = printFlag($field_judiciales['FlagTransferido']);
		$id = "$field_judiciales[CodProveedor].$field_judiciales[CodTipoDocumento].$field_judiciales[NroDocumento].$field_judiciales[Secuencia].$field_judiciales[TipoObligacion]";
		?>
		<tr class="trListaBody" onclick="mClkMulti(this);" id="row_judiciales<?=$id?>">
			<td align="center">
            	<input type="checkbox" name="judiciales" id="judiciales<?=$id?>" value="<?=$id?>" style="display:none" />
				<?=$field_judiciales['CodProveedor']?>
			</td>
			<td><?=($field_judiciales['NomProveedor'])?></td>
			<td align="center"><?=$field_judiciales['NomTipoDocumento']?></td>
			<td align="center"><?=$field_judiciales['NroDocumento']?></td>
			<td align="center"><?=$field_judiciales['NroRegistro']?></td>
			<td align="center"><?=formatFechaDMA($field_judiciales['FechaRegistro'])?></td>
			<td align="center"><?=$flagtransferido?></td>
			<td align="right"><?=number_format($field_judiciales['MontoObligacion'], 2, ',', '.')?></td>
		</tr>
		<?
	}
	?>
</table>
</div></td></tr></table>
</form>
</div>

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

</body>
</html>
