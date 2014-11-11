<?php
if ($filtrar == "default") {
	$faplicacion = "AP";
	$fsistema_fuente = $_SESSION["SISTEMA_FUENTE"];
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fperiodo = date("Y-m");
}
$filtro = "";
if ($forganismo != "") $filtro .= " AND (o.CodOrganismo = '".$forganismo."')";
if ($fpreparadopor != "") { $cfpreparadopor = "checked"; $filtro .= " AND (o.IngresadoPor = '".$fpreparadopor."')"; } else $btProveedor = "disabled";
if ($fperiodo != "") $filtro .= " AND (SUBSTRING(o.FechaAprobado, 1, 7) = '".$fperiodo."')";
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Generaci&oacute;n de Vouchers de Provisi&oacute;n de Obligaciones</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=ap_generar_vouchers_provision" method="post">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td width="125" align="right">Aplicaci&oacute;n:</td>
		<td>
        	<input type="checkbox" style="visibility:hidden;" />
        	<input type="text" name="faplicacion" id="faplicacion" value="<?=$faplicacion?>" style="width:25px;" readonly="readonly" />
        </td>
		<td width="125" align="right">Sistema Fuente:</td>
		<td>
        	<input type="checkbox" style="visibility:hidden;" />
        	<input type="text" name="fsistema_fuente" id="fsistema_fuente" value="<?=$fsistema_fuente?>" style="width:75px;" readonly="readonly" />
        </td>
	</tr>
	<tr>
		<td align="right">Organismo:</td>
		<td>
			<input type="checkbox" checked="checked" onclick="this.checked=!this.checked" />
			<select name="forganismo" id="forganismo" style="width:300px;">
				<?=getOrganismos($forganismo, 3);?>
			</select>
		</td>
		<td align="right">Preparado Por:</td>
		<td>
			<input type="checkbox" onclick="chkFiltroLista(this.checked, 'fpreparadopor', 'fnompreparadopor', 'btPreparadoPor');" <?=$cfpreparadopor?> />
			<input type="hidden" name="fpreparadopor" id="fpreparadopor" value="<?=$fpreparadopor?>" />
			<input type="hidden" name="codempleado" id="codempleado" value="<?=$codempleado?>" />
			<input type="text" name="fnompreparadopor" id="fnompreparadopor" style="width:250px;" value="<?=$fnompreparadopor?>" readonly />
			<input type="button" value="..." id="btPreparadoPor" onclick="cargarVentana(this.form, '../lib/listas/listado_empleados.php?ventana=&cod=codempleado&nom=fnompreparadopor&campo3=fpreparadopor&limit=0&filtrar=default', 'height=800, width=1050, left=50, top=0, resizable=yes');" <?=$btProveedor?> />
		</td>
	</tr>
	<tr>
		<td align="right">Periodo:</td>
		<td colspan="3">
			<input type="checkbox" checked="checked" onclick="this.checked=!this.checked" />
			<input type="text" name="fperiodo" id="fperiodo" maxlength="7" style="width:60px;" value="<?=$fperiodo?>" />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center><br />
</form>

<form name="frm_detalle" id="frm_detalle" method="post" action="gehen.php?anz=ap_generar_vouchers_provision">
<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<!--<input type="button" id="btVer" value="Ver Obligaci&oacute;n" onclick="cargarOpcion2(document.getElementById('frmentrada'), 'ap_obligaciones_editar.php?accion=VER', 'BLANK', 'height=550, width=1200, left=0, top=0, resizable=no', 'registro');" />-->
			<input type="button" id="btGenerar" value="Generar Voucher" onclick="generar_vouchers_abrir(this.form.registro.value, 'ap_generar_vouchers_provision_voucher');" />
		</td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />
<center>
<div style="overflow:scroll; width:1000px; height:400px;">
<table width="1300" class="tblLista">
	<thead>
	<tr class="trListaHead">
		<th scope="col" width="75">Organismo</th>
		<th scope="col" width="100"># Registro</th>
		<th scope="col">Proveedor/Empleado</th>
		<th scope="col" width="100">Fecha</th>
		<th scope="col" width="200">Documento</th>
		<th scope="col" width="100">Monto Obligaci&oacute;n</th>
		<th scope="col" width="350">Preparado Por</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT
				o.*,
				p1.NomCompleto AS NomProveedor,
				p2.NomCompleto AS NomPreparadoPor,
				td.CodVoucher
			FROM
				ap_obligaciones o
				INNER JOIN mastpersonas p1 ON (o.CodProveedor = p1.CodPersona)
				INNER JOIN mastpersonas p2 ON (o.IngresadoPor = p2.CodPersona)
				INNER JOIN ap_tipodocumento td ON (o.CodTipoDocumento = td.CodTipoDocumento)
			WHERE
				(o.Estado = 'AP' OR o.Estado = 'PA') AND
				o.FlagContabilizacionPendiente = 'S' AND
				td.FlagProvision = 'S'
				$filtro
			ORDER BY o.CodOrganismo, o.NroRegistro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[CodOrganismo].$field[CodProveedor].$field[CodTipoDocumento].$field[NroDocumento].$field[CodVoucher]";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$field['CodOrganismo']?></td>
			<td align="center"><?=$field['NroRegistro']?></td>
			<td><?=($field['NomProveedor'])?></td>
			<td align="center"><?=formatFechaDMA($field['FechaAprobado'])?></td>
			<td><?=$field['CodTipoDocumento']."-".$field['NroDocumento']?></td>
			<td align="right"><strong><?=number_format($field['MontoObligacion'], 2, ',', '.')?></strong></td>
			<td><?=($field['NomPreparadoPor'])?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
</center>
</form>