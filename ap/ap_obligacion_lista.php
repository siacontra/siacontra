<?php
$Ahora = ahora();
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
if ($lista == "todos") {
	$titulo = "Obligaciones con los Proveedores";
	$btRevisar = "display:none;";
	$btAprobar = "display:none;";
	$btConformar  = "display:none;";
}
elseif ($lista == "revisar") {
	$titulo = "Revisar Obligaciones";
	$btNuevo = "display:none;";
	$btModificar = "display:none;";
	$btAprobar = "display:none;";
	$btConformar  = "display:none;";
	$fEstado = "PR";
}
elseif ($lista == "conformar") {
	$titulo = "Conformar Obligaciones";
	$btNuevo = "display:none;";
	$btRevisar = "display:none;";
	$btModificar = "display:none;";
	$btAprobar = "display:none;";
	$fEstado = "RV";
}
elseif ($lista == "aprobar") {
	$titulo = "Aprobar Obligaciones";
	$btNuevo = "display:none;";
	$btModificar = "display:none;";
	$btRevisar = "display:none;";
	$btConformar  = "display:none;";
	$fEstado = "CO";
}
//	------------------------------------
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$maxlimit = $_SESSION["MAXLIMIT"];
	if ($lista == "todos") {
		$fEstado = "PR";
		$fFechaDocumentod = "01-$Mes-$Anio";
		$fFechaDocumentoh = "$Dia-$Mes-$Anio";
	}
	$FlagPagoDiferido = "";
}
if ($fCodOrganismo != "") { $cCodOrganismo = "checked"; $filtro.=" AND (o.CodOrganismo = '".$fCodOrganismo."')"; } else $dCodOrganismo = "disabled";
if ($fCodProveedor != "") { $cCodProveedor = "checked"; $filtro.=" AND (o.CodProveedor = '".$fCodProveedor."')"; } else $dCodProveedor = "visibility:hidden;";
if ($fCodTipoDocumento != "") { $cCodTipoDocumento = "checked"; $filtro.=" AND (o.CodTipoDocumento = '".$fCodTipoDocumento."')"; } else $dCodTipoDocumento = "disabled";
if ($fCodIngresadoPor != "") { $cIngresadoPor = "checked"; $filtro.=" AND (o.IngresadoPor = '".$fCodIngresadoPor."')"; } else $dIngresadoPor = "visibility:hidden;";
if ($fNroDocumento != "") { $cNroDocumento = "checked"; $filtro.=" AND (o.NroDocumento LIKE '%".$fNroDocumento."%')"; } else $dNroDocumento = "disabled";
if ($fCodCentroCosto != "") { $cCodCentroCosto = "checked"; $filtro.=" AND (o.CodCentroCosto = '".$fCodCentroCosto."')"; } else $dCodCentroCosto = "visibility:hidden;";
if ($fEstado != "") { $cEstado = "checked"; $filtro.=" AND (o.Estado = '".$fEstado."')"; } else $dEstado = "disabled";
if ($fFechaDocumentod != "" || $fFechaDocumentoh != "") {
	$cFechaDocumento = "checked";
	if ($fFechaDocumentod != "") $filtro.=" AND (o.FechaDocumento >= '".formatFechaAMD($fFechaDocumentod)."')";
	if ($fFechaDocumentoh != "") $filtro.=" AND (o.FechaDocumento <= '".formatFechaAMD($fFechaDocumentoh)."')";
} else $dFechaDocumento = "disabled";
if ($fReferenciaNroDocumento != "") { $cReferenciaNroDocumento = "checked"; $filtro.=" AND (o.ReferenciaNroDocumento LIKE '%".$fReferenciaNroDocumento."%')"; } else $dReferenciaNroDocumento = "disabled";
if ($fFechaRegistrod != "" || $fFechaRegistroh != "") {
	$cFechaRegistro = "checked";
	if ($fFechaRegistrod != "") $filtro.=" AND (o.FechaRegistro >= '".$fFechaRegistrod."')";
	if ($fFechaRegistroh != "") $filtro.=" AND (o.FechaRegistro <= '".$fFechaRegistroh."')";
} else $dFechaRegistro = "disabled";
if ($FlagPagoDiferido == "S") { $cFlagPagoDiferido = "checked"; $filtro.=" AND (o.FlagPagoDiferido = 'S')"; }
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=ap_obligacion_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:1050px;">
<table width="1050" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
			<input type="checkbox" <?=$cCodOrganismo?> onclick="this.checked=!this.checked" />
			<select name="fCodOrganismo" id="fCodOrganismo" style="width:300px;" <?=$dCodOrganismo?>>
				<?=getOrganismos($fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right" width="125">Proveedor: </td>
		<td class="gallery clearfix">
            <input type="checkbox" <?=$cCodProveedor?> onclick="chkFiltroLista_3(this.checked, 'fCodProveedor', 'fNomProveedor', '', 'btProveedor');" />
            
            <input type="text" name="fCodProveedor" id="fCodProveedor" style="width:50px;" value="<?=$fCodProveedor?>" readonly="readonly" />
			<input type="text" name="fNomProveedor" id="fNomProveedor" style="width:200px;" value="<?=$fNomProveedor?>" readonly="readonly" />
            <a href="../lib/listas/listado_personas.php?filtrar=default&cod=fCodProveedor&nom=fNomProveedor&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" id="btProveedor" style=" <?=$dCodProveedor?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
	</tr>
	<tr>
		<td align="right">Tipo Doc.:</td>
		<td>
			<input type="checkbox" <?=$cCodTipoDocumento?> onclick="chkFiltro(this.checked, 'fCodTipoDocumento');" />
			<select name="fCodTipoDocumento" id="fCodTipoDocumento" style="width:300px;" <?=$dCodTipoDocumento?>>
            	<option value=""></option>
                <?=loadSelect("ap_tipodocumento", "CodTipoDocumento", "Descripcion", $fCodTipoDocumento, 0)?>
			</select>
		</td>
		<td align="right">Ingresado Por: </td>
		<td class="gallery clearfix">
            <input type="checkbox" <?=$cIngresadoPor?> onclick="chkFiltroLista_3(this.checked, 'fCodIngresadoPor', 'fNomIngresadoPor', '', 'btIngresadoPor');" />
            
            <input type="text" name="fCodIngresadoPor" id="fCodIngresadoPor" style="width:50px;" value="<?=$fCodIngresadoPor?>" readonly="readonly" />
			<input type="text" name="fNomIngresadoPor" id="fNomIngresadoPor" style="width:200px;" value="<?=$fNomIngresadoPor?>"v />
            <a href="../lib/listas/listado_empleados.php?filtrar=default&cod=fCodIngresadoPor&nom=fNomIngresadoPor&iframe=true&width=950&height=525" rel="prettyPhoto[iframe2]" id="btIngresadoPor" style=" <?=$dIngresadoPor?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
	</tr>
	<tr>
		<td align="right">Nro Doc.:</td>
		<td>
			<input type="checkbox" <?=$cNroDocumento?> onclick="chkFiltro(this.checked, 'fNroDocumento');" />
			<input type="text" name="fNroDocumento" id="fNroDocumento" value="<?=$fNroDocumento?>" maxlength="20" style="width:100px;" <?=$dNroDocumento?> />
		</td>
		<td align="right">C. Costo: </td>
		<td class="gallery clearfix">
            <input type="checkbox" <?=$cCodCentroCosto?> onclick="chkFiltroLista_3(this.checked, 'fCodCentroCosto', 'fNomCentroCosto', '', 'btCodCentroCosto');" />
            
            <input type="text" name="fCodCentroCosto" id="fCodCentroCosto" style="width:50px;" value="<?=$fCodCentroCosto?>" readonly="readonly" />
			<input type="hidden" name="fNomCentroCosto" id="fNomCentroCosto" value="<?=$fNomCentroCosto?>" />
            <a href="../lib/listas/listado_centro_costos.php?filtrar=default&cod=fCodCentroCosto&nom=fNomCentroCosto&iframe=true&width=950&height=525" rel="prettyPhoto[iframe3]" id="btCodCentroCosto" style=" <?=$dCodCentroCosto?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
	</tr>
	<tr>
		<td align="right">Estado:</td>
		<td>
        	<? 
			if ($lista == "revisar" || $lista == "aprobar" || $lista == "conformar") {
				?>
				<input type="checkbox" onclick="this.checked=!this.checked;" checked="checked" />
                <select name="fEstado" id="fEstado" style="width:105px;">
                    <?=loadSelectValores("ESTADO-OBLIGACIONES", $fEstado, 1)?>
                </select>
                <?
			} else {
				?>
                <input type="checkbox" <?=$cEstado?> onclick="chkFiltro(this.checked, 'fEstado');" />
                <select name="fEstado" id="fEstado" style="width:105px;" <?=$dEstado?>>
                    <option value=""></option>
                    <?=loadSelectValores("ESTADO-OBLIGACIONES", $fEstado, 0)?>
                </select>
                <?
			} 
			?>
		</td>
		<td align="right">F.Documento: </td>
		<td>
			<input type="checkbox" <?=$cFechaDocumento?> onclick="chkFiltro_2(this.checked, 'fFechaDocumentod', 'fFechaDocumentoh');" />
			<input type="text" name="fFechaDocumentod" id="fFechaDocumentod" value="<?=$fFechaDocumentod?>" <?=$dFechaDocumento?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" />-
            <input type="text" name="fFechaDocumentoh" id="fFechaDocumentoh" value="<?=$fFechaDocumentoh?>" <?=$dFechaDocumento?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" />
        </td>
	</tr>
	<tr>
		<td align="right">Doc. Interno:</td>
		<td>
			<input type="checkbox" <?=$cReferenciaNroDocumento?> onclick="chkFiltro(this.checked, 'fReferenciaNroDocumento');" />
			<input type="text" name="fReferenciaNroDocumento" id="fReferenciaNroDocumento" value="<?=$fReferenciaNroDocumento?>" maxlength="10" style="width:100px;" <?=$dReferenciaNroDocumento?> />
		</td>
		<td align="right">F.Registro: </td>
		<td>
			<input type="checkbox" <?=$cFechaRegistro?> onclick="chkFiltro_2(this.checked, 'fFechaRegistrod', 'fFechaRegistroh');" />
			<input type="text" name="fFechaRegistrod" id="fFechaRegistrod" value="<?=$fFechaRegistrod?>" <?=$dFechaRegistro?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" />-
            <input type="text" name="fFechaRegistroh" id="fFechaRegistroh" value="<?=$fFechaRegistroh?>" <?=$dFechaRegistro?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" />
        </td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;</td>
		<td><input type="checkbox" name="FlagPagoDiferido" id="FlagPagoDiferido" value="S" <?=$cFlagPagoDiferido?> /> Pago Diferido</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="1050" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			
			 	
            <input type="button" id="btConform" value="Conformar" style="width:75px; <?=$btConformar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=ap_obligacion_form&opcion=conformar&origen=ap_obligacion_lista', 'SELF', '', 'registro');" />  
			
			<input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=ap_obligacion_form&opcion=nuevo&origen=ap_obligacion_lista');" />
            
			<input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=obligacion_modificar', 'gehen.php?anz=ap_obligacion_form&opcion=modificar&origen=ap_obligacion_lista', 'SELF', '');" />
            
			<input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=ap_obligacion_form&opcion=ver&origen=ap_obligacion_lista', 'SELF', '', 'registro');" /> 
            
			<input type="button" id="btRevisar" value="Revisar" style="width:75px; <?=$btRevisar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=ap_obligacion_form&opcion=revisar&origen=ap_obligacion_lista', 'SELF', '', 'registro');" />
		
            
			<input type="button" id="btAprobar" value="Aprobar" style="width:75px; <?=$btAprobar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=ap_obligacion_form&opcion=aprobar&origen=ap_obligacion_lista', 'SELF', '', 'registro');" />

			<input type="button" id="btAnular" value="Anular" style="width:75px; <?=$btAnular?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=obligacion_anular', 'gehen.php?anz=ap_obligacion_form&opcion=anular&origen=ap_obligacion_lista', 'SELF', '');" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:1050px; height:300px;">
<table width="2000" class="tblLista">
	<thead>
		<th scope="col">Proveedor</th>
		<th width="50" scope="col">Tipo</th>
		<th width="100" scope="col">Nro. Documento</th>
		<th width="75" scope="col">Fecha Documento</th>
		<th width="100" scope="col">Monto</th>
		<th width="100" scope="col">Estado</th>
		<th width="100" scope="col">Nro. Registro</th>
		<th width="75" scope="col">Fecha Registro</th>
		<th width="75" scope="col">Fecha Prog. Pago</th>
		<th width="75" scope="col">Fecha Pago</th>
		<th width="50" scope="col">Pago Dif.</th>
		<th width="50" scope="col">C.Costo</th>
		<th width="100" scope="col">Voucher</th>
		<th width="150" scope="col">Obligaci&oacute;n</th>
		<th width="125" scope="col">Tipo Pago</th>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				o.*,
				mp.NomCompleto AS NomProveedor,
				tp.TipoPago
			FROM 
				ap_obligaciones o
				INNER JOIN mastpersonas mp ON (o.CodProveedor = mp.CodPersona)
				INNER JOIN masttipopago tp ON (o.CodtipoPago = tp.CodTipoPago)
			WHERE 1 $filtro
			ORDER BY NomProveedor, CodTipoDocumento, NroDocumento";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				o.*,
				mp.NomCompleto AS NomProveedor,
				tp.TipoPago
			FROM 
				ap_obligaciones o
				INNER JOIN mastpersonas mp ON (o.CodProveedor = mp.CodPersona)
				INNER JOIN masttipopago tp ON (o.CodtipoPago = tp.CodTipoPago)
			WHERE 1 $filtro
			ORDER BY NomProveedor, CodTipoDocumento, NroDocumento
			LIMIT ".intval($limit).", ".intval($maxlimit);
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[CodOrganismo].$field[CodProveedor].$field[CodTipoDocumento].$field[NroDocumento]";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td><?=($field['NomProveedor'])?></td>
			<td align="center"><?=$field['CodTipoDocumento']?></td>
			<td align="center"><?=$field['NroDocumento']?></td>
			<td align="center"><?=formatFechaDMA($field['FechaDocumento'])?></td>
			<td align="right"><strong><?=number_format($field['MontoObligacion'], 2, ',', '.')?></strong></td>
			<td align="center"><?=printValores("ESTADO-OBLIGACIONES", $field['Estado'])?></td>
			<td align="center"><?=$field['NroRegistro']?></td>
			<td align="center"><?=formatFechaDMA($field['FechaRegistro'])?></td>
			<td align="center"><?=formatFechaDMA($field['FechaProgramada'])?></td>
			<td align="center"><?=formatFechaDMA($field['FechaPago'])?></td>
			<td align="center"><?=printFlag($field['FlagPagoDiferido'])?></td>
			<td align="center"><?=$field['CodCentroCosto']?></td>
			<td align="center"><?=$field['Voucher']?></td>
			<td align="center"><?=$field['CodTipoDocumento']?>-<?=$field['NroDocumento']?></td>
			<td><?=($field['TipoPago'])?></td>
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
