<?php
if ($lista == "todos") {
	$titulo = "Lista de Transacciones";
	$btEjecutar = "display:none;";
}
elseif ($lista == "ejecutar") {
	$titulo = "Ejecutar Transacciones";
	$btNuevo = "display:none;";
	$btModificar = "display:none;";
	$btCopiar = "display:none;";
	$btReversa = "display:none;";
	$btDevolucion = "display:none;";
	$fEstado = "PR";
}
//	------------------------------------
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$maxlimit = $_SESSION["MAXLIMIT"];
	if ($lista == "todos") {
		$fEstado = "CO";
		$fPeriodo = substr($Ahora, 0, 7);
	}
}
if ($fCodOrganismo != "") { $cCodOrganismo = "checked"; $filtro.=" AND (t.CodOrganismo = '".$fCodOrganismo."')"; } else $dCodOrganismo = "disabled";
if ($fBuscar != "") { 
	$cBuscar = "checked"; 
	$filtro.=" AND (t.CodDocumento LIKE '%".$fBuscar."%' OR 
					t.NroDocumento LIKE '%".$fBuscar."%' OR 
					t.CodTransaccion LIKE '%".$fBuscar."%' OR 
					tt.Descripcion LIKE '%".$fBuscar."%' OR 
					a.Descripcion LIKE '%".$fBuscar."%' OR 
					t.CodCentroCosto LIKE '%".$fBuscar."%' OR 
					t.Periodo LIKE '%".$fBuscar."%' OR 
					t.CodDocumentoReferencia LIKE '%".$fBuscar."%' OR 
					t.NroDocumentoReferencia LIKE '%".$fBuscar."%' OR 
					t.DocumentoReferenciaInterno LIKE '%".$fBuscar."%')";
} else $dBuscar = "disabled";
if ($fCodDependencia != "") { $cCodDependencia = "checked"; $filtro.=" AND (t.CodDependencia = '".$fCodDependencia."')"; } else $dCodDependencia = "disabled";
if ($fFechaDocumentod != "" || $fFechaDocumentoh != "") {
	$cFechaDocumento = "checked";
	if ($fFechaDocumentod != "") $filtro.=" AND (t.FechaDocumento >= '".formatFechaAMD($fFechaDocumentod)."')";
	if ($fFechaDocumentoh != "") $filtro.=" AND (t.FechaDocumento <= '".formatFechaAMD($fFechaDocumentoh)."')";
} else $dFechaDocumento = "disabled";
if ($fCodCentroCosto != "") { $cCodCentroCosto = "checked"; $filtro.=" AND (t.CodCentroCosto = '".$fCodCentroCosto."')"; } else $dCodCentroCosto = "disabled";
if ($fEstado != "") { $cEstado = "checked"; $filtro.=" AND (t.Estado = '".$fEstado."')"; } else $dEstado = "disabled";
if ($fCodTransaccion != "") { $cCodTransaccion = "checked"; $filtro.=" AND (t.CodTransaccion = '".$fCodTransaccion."')"; } else $dCodTransaccion = "disabled";
if ($fCodDocumento != "" || $fNroDocumento != "") { 
	$cCodDocumento = "checked";
	if ($fCodDocumento != "") $filtro.=" AND (t.CodDocumento = '".$fCodDocumento."')";
	if ($fNroDocumento != "") $filtro.=" AND (t.NroDocumento = '".$fNroDocumento."')";
} else $dCodDocumento = "disabled";
if ($fPeriodo != "") { $cPeriodo = "checked"; $filtro.=" AND (t.Periodo = '".$fPeriodo."')"; } else $dPeriodo = "disabled";
if ($fCodDocumentoReferencia != "" || $fNroDocumentoReferencia != "") { 
	$cCodDocumentoReferencia = "checked";
	if ($fCodDocumentoReferencia != "") $filtro.=" AND (t.CodDocumentoReferencia = '".$fCodDocumentoReferencia."')";
	if ($fNroDocumentoReferencia != "") $filtro.=" AND (t.NroDocumentoReferencia = '".$fNroDocumentoReferencia."')";
} else $dCodDocumentoReferencia = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=lg_transaccion_almacen_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:1050px;">
<table width="1050" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
			<input type="checkbox" <?=$cCodOrganismo?> onclick="this.checked=!this.checked" />
			<select name="fCodOrganismo" id="fCodOrganismo" style="width:300px;" onchange="getOptionsSelect(this.value, 'dependencia', 'fCodDependencia', true, 'fCodCentroCosto');" <?=$dCodOrganismo?>>
				<?=getOrganismos($fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right" width="125">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fBuscar');" />
			<input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:295px;" <?=$dBuscar?> />
		</td>
	</tr>
    <tr>
		<td align="right">Dependencia:</td>
		<td>
			<input type="checkbox" <?=$cCodDependencia?> onclick="chkFiltro(this.checked, 'fCodDependencia')" />
			<select name="fCodDependencia" id="fCodDependencia" style="width:300px;" onchange="getOptionsSelect(this.value, 'centro_costo', 'fCodCentroCosto', true);" <?=$dCodDependencia?>>
				<option value="">&nbsp;</option>
				<?=getDependencias($fCodDependencia, $fCodOrganismo, 3);?>
			</select>
		</td>
		<td align="right">F.Documento: </td>
		<td>
			<input type="checkbox" <?=$cFechaDocumento?> onclick="chkFiltro_2(this.checked, 'fFechaDocumentod', 'fFechaDocumentoh');" />
			<input type="text" name="fFechaDocumentod" id="fFechaDocumentod" value="<?=$fFechaDocumentod?>" <?=$dFechaDocumento?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" />-
            <input type="text" name="fFechaDocumentoh" id="fFechaDocumentoh" value="<?=$fFechaDocumentoh?>" <?=$dFechaDocumento?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" />
        </td>
	</tr>
	<tr>
		<td align="right">Centro de Costo:</td>
		<td>
			<input type="checkbox" <?=$cCodCentroCosto?> onclick="chkFiltro(this.checked, 'fCodCentroCosto')" />
			<select name="fCodCentroCosto" id="fCodCentroCosto" style="width:300px;" <?=$dCodCentroCosto?>>
				<option value="">&nbsp;</option>
				<?=loadSelectDependiente("ac_mastcentrocosto", "CodCentroCosto", "Descripcion", "CodDependencia", $fCodCentroCosto, $fCodDependencia, 0)?>
			</select>
		</td>
		<td align="right">Estado:</td>
		<td>
        	<? 
			if ($lista == "ejecutar") {
				?>
				<input type="checkbox" onclick="this.checked=!this.checked;" checked="checked" />
                <select name="fEstado" id="fEstado" style="width:150px;">
                    <?=loadSelectValores("ESTADO-TRANSACCION", $fEstado, 1)?>
                </select>
                <?
			} else {
				?>
                <input type="checkbox" <?=$cEstado?> onclick="chkFiltro(this.checked, 'fEstado');" />
                <select name="fEstado" id="fEstado" style="width:140px;" <?=$dEstado?>>
					<option value="">&nbsp;</option>
                    <?=loadSelectValores("ESTADO-TRANSACCION", $fEstado, 0)?>
                </select>
                <?
			}
			?>
		</td>
	</tr>
    <tr>
		<td align="right">Tipo de Transaccion: </td>
		<td class="gallery clearfix">
            <input type="checkbox" <?=$cCodTransaccion?> onclick="chkFiltroLista_3(this.checked, 'fCodTransaccion', 'fNomTransaccion', '', 'btTransaccion');" />
            <input type="text" name="fCodTransaccion" id="fCodTransaccion" style="width:45px;" value="<?=$fCodTransaccion?>" readonly="readonly" />
			<input type="text" name="fNomTransaccion" id="fNomTransaccion" style="width:240px;" value="<?=$fNomTransaccion?>" readonly="readonly" />
            <a href="../lib/listas/listado_personas.php?filtrar=default&cod=fCodTransaccion&nom=fNomTransaccion&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" id="btTransaccion" style=" <?=$dCodTransaccion?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td align="right">Doc. Generado:</td>
        <td>
            <input type="checkbox" <?=$cCodDocumento?> onclick="chkFiltro_2(this.checked, 'fCodDocumento', 'fNroDocumento');" />
        	<select id="fCodDocumento" style="width:42px;" <?=$dCodDocumento?>>
            	<option value="">&nbsp;</option>
				<?=loadSelect("lg_tipodocumento", "CodDocumento", "Descripcion", $fCodDocumento, 10);?>
			</select>
            <input type="text" id="fNroDocumento" maxlength="20" style="width:100px;" value="<?=$fNroDocumento?>" <?=$dCodDocumento?> />
        </td>
	</tr>
	<tr>
		<td align="right">Periodo:</td>
		<td>
			<input type="checkbox" <?=$cPeriodo?> onclick="chkFiltro(this.checked, 'fPeriodo');" />
			<input type="text" name="fPeriodo" id="fPeriodo" value="<?=$fPeriodo?>" maxlength="7" style="width:45px;" <?=$dPeriodo?> />
		</td>
		<td align="right">Doc. Referencia:</td>
        <td>
            <input type="checkbox" <?=$cCodDocumentoReferencia?> onclick="chkFiltro_2(this.checked, 'fCodDocumentoReferencia', 'fNroDocumentoReferencia');" />
        	<select id="fCodDocumentoReferencia" style="width:42px;" <?=$dCodDocumentoReferencia?>>
            	<option value="">&nbsp;</option>
				<?=loadSelect("lg_tipodocumento", "CodDocumento", "Descripcion", $fCodDocumentoReferencia, 10);?>
			</select>
            <input type="text" id="fNroDocumentoReferencia" maxlength="20" style="width:100px;" value="<?=$fNroDocumentoReferencia?>" <?=$dCodDocumentoReferencia?> />
        </td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="1050" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="opcionValidar(this.form, 'gehen.php?anz=lg_transaccion_almacen_form&opcion=nuevo', 'CodOrganismo=<?=$fCodOrganismo?>&accion=periodoAbierto');" />
            
			<input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=transaccion_almacen_modificar', 'gehen.php?anz=lg_transaccion_almacen_form&opcion=modificar', 'SELF', '');" />
            
			<input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=lg_transaccion_almacen_form&opcion=ver', 'SELF', '', $('#registro').val());" />
            
            <input type="button" id="btImprimir" value="Imprimir" style="width:75px; <?=$btImprimir?>" onclick="imprimir_transaccion_almacen();" /> | 
            
			<input type="button" id="btEjecutar" value="Ejecutar" style="width:75px; <?=$btEjecutar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=lg_transaccion_almacen_form&opcion=ejecutar', 'SELF', '', $('#registro').val());" />
            
			<input type="button" id="btCopiar" value="Copiar" style="width:75px; <?=$btCopiar?>" />
            
			<input type="button" id="btReversa" value="Reversa" style="width:75px; <?=$btReversa?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=transaccion_almacen_reversa', 'gehen.php?anz=lg_transaccion_almacen_form&opcion=reversa', 'SELF', '');" />
            
			<input type="button" id="btDevolucion" value="Devolucion O/C" style="width:100px; <?=$btDevolucion?>" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:1050px; height:300px;">
<table width="1200" class="tblLista">
	<thead>
    <tr>
		<th scope="col" colspan="2">Doc. Generado</th>
		<th scope="col" width="65">Fecha Doc.</th>
		<th scope="col" colspan="2">Transacci&oacute;n</th>
		<th scope="col" width="175">Almac&eacute;n</th>
		<th scope="col" width="35">C.C.</th>
		<th scope="col" width="60">Periodo</th>
		<th scope="col" width="75">Estado</th>
		<th scope="col" colspan="2">Doc. Referencia</th>
		<th scope="col" width="125">Doc. Ref. Interno</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				t.CodOrganismo,
				t.CodDocumento,
				t.NroDocumento,
				t.NroInterno,
				t.FechaDocumento,
				t.CodTransaccion,
				t.CodCentrocosto,
				t.Periodo,
				t.Estado,
				t.CodDocumentoReferencia,
				t.NroDocumentoReferencia,
				t.DocumentoReferenciaInterno,
				a.Descripcion AS NomAlmacen,
				tt.Descripcion AS NomTransaccion,
				tt.TipoMovimiento
			FROM
				lg_transaccion t 
				INNER JOIN lg_almacenmast a ON (a.CodAlmacen = t.CodAlmacen AND a.FlagCommodity = 'N')
				INNER JOIN lg_tipotransaccion tt ON (t.CodTransaccion = tt.CodTransaccion)
			WHERE 1 $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				t.CodOrganismo,
				t.CodDocumento,
				t.NroDocumento,
				t.NroInterno,
				t.FechaDocumento,
				t.CodTransaccion,
				t.CodCentrocosto,
				t.Periodo,
				t.Estado,
				t.CodDocumentoReferencia,
				t.NroDocumentoReferencia,
				t.DocumentoReferenciaInterno,
				a.Descripcion AS NomAlmacen,
				tt.Descripcion AS NomTransaccion,
				tt.TipoMovimiento
			FROM
				lg_transaccion t 
				INNER JOIN lg_almacenmast a ON (a.CodAlmacen = t.CodAlmacen AND a.FlagCommodity = 'N')
				INNER JOIN lg_tipotransaccion tt ON (t.CodTransaccion = tt.CodTransaccion)
			WHERE 1 $filtro
			ORDER BY CodDocumento, NroDocumento
			LIMIT ".intval($limit).", ".intval($maxlimit);
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[CodOrganismo].$field[CodDocumento].$field[NroDocumento].$field[TipoMovimiento]";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
            <td align="center" width="10">
				<?=$field['CodDocumento']?>
            </td>
            <td width="40">
				<?=$field['NroInterno']?>
            </td>
            <td align="center"><?=formatFechaDMA($field['FechaDocumento'])?></td>
            <td align="center" width="25">
				<?=$field['CodTransaccion']?>
			</td>
            <td>
				<?=($field['NomTransaccion'])?>
			</td>
            <td align="center"><?=($field['NomAlmacen'])?></td>
            <td align="center"><?=$field['CodCentroCosto']?></td>
            <td align="center"><?=$field['Periodo']?></td>
            <td align="center"><?=printValores("ESTADO-TRANSACCION", $field['Estado'])?></td>
            <td align="center" width="10">
				<?=$field['CodDocumentoReferencia']?>
			</td>
            <td width="115">
				<?=$field['NroDocumentoReferencia']?>
            </td>
            <td><?=$field['DocumentoReferenciaInterno']?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
<table width="1050">
	<tr>
    	<td>
        	Mostrar: 
            <select name="maxlimit" style="width:50px;" onchange="this.form.submit();">
                <?=loadSelectGeneral("MAXLIMIT", $maxlimit, 0)?>
            </select>
        </td>
        <td align="right">
        	<?=paginacion(intval($rows_total), intval($rows_lista), intval($maxlimit), intval($limit));?>
        </td>
    </tr>
</table>
</center>
</form>
