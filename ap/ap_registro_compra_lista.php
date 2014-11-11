<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$maxlimit = $_SESSION["MAXLIMIT"];
	$Periodo = "$Anio-$Mes";
}
if ($fCodOrganismo != "") { $cCodOrganismo = "checked"; $filtro.=" AND (rc.CodOrganismo = '".$fCodOrganismo."')"; } else $dCodOrganismo = "disabled";
if ($fCodProveedor != "") { $cCodProveedor = "checked"; $filtro.=" AND (rc.CodProveedor = '".$fCodProveedor."')"; } else $dCodProveedor = "visibility:hidden;";
if ($fSistemaFuente != "") { $cSistemaFuente = "checked"; $filtro.=" AND (rc.SistemaFuente = '".$fSistemaFuente."')"; } else $dSistemaFuente = "disabled";
if ($fPeriodo != "") { $cPeriodo = "checked"; $filtro.=" AND (rc.Periodo = '".$fPeriodo."')"; } else $dPeriodo = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Listado de Registros de Compra</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=ap_registro_compra_lista" method="post">
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
		<td align="right">Sistema Fuente:</td>
		<td>
			<input type="checkbox" <?=$cSistemaFuente?> onclick="chkFiltro(this.checked, 'fSistemaFuente');" />
			<select name="fSistemaFuente" id="fSistemaFuente" style="width:150px;" <?=$dSistemaFuente?>>
            	<option value="">&nbsp;</option>
				<?=loadSelectValores("SISTEMA-FUENTE-REGISTRO-COMPRA", $fSistemaFuente, 0)?>
			</select>
		</td>
		<td align="right">Periodo:</td>
		<td>
			<input type="checkbox" <?=$cPeriodo?> onclick="chkFiltro(this.checked, 'fPeriodo');" />
			<input type="text" name="fPeriodo" id="fPeriodo" value="<?=$fPeriodo?>" maxlength="7" style="width:50px;" <?=$dPeriodo?> />
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
			<input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=ap_registro_compra_form&opcion=nuevo&origen=ap_registro_compra_lista');" />
            
			<input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=obligacion_modificar', 'gehen.php?anz=ap_registro_compra_form&opcion=modificar&origen=ap_registro_compra_lista', 'SELF', '');" />
            
			<input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=ap_registro_compra_form&opcion=ver&origen=ap_registro_compra_lista', 'SELF', '', 'registro');" />
            
			<input type="button" id="btEliminar" value="Eliminar" style="width:75px; <?=$btEliminar?>" onclick="opcionRegistro2(this.form, $('#registro').val(), 'registro_compra', 'eliminar');" /> | 
            
			<input type="button" id="btVerDoc" value="Ver Doc. Fuente" style="width:125px; <?=$btVerDoc?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=ap_obligacion_form&opcion=ver&origen=ap_registro_compra_lista', 'SELF', '', 'registro');" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:1050px; height:300px;">
<table width="1400" class="tblLista">
	<thead>
		<th width="60" scope="col">Periodo</th>
		<th width="25" scope="col">Sist.</th>
		<th width="100" scope="col">R.I.F.</th>
		<th scope="col" align="left">Proveedor</th>
		<th width="100" scope="col">Imponible</th>
		<th width="100" scope="col">No Afecto</th>
		<th width="100" scope="col">Otros Imp.</th>
		<th width="100" scope="col">Total Obligaci&oacute;n</th>
		<th width="150" scope="col" align="left">Documento</th>
		<th width="75" scope="col">Fecha</th>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				rc.*,
				p.NomCompleto AS NomProveedor
			FROM
				ap_registrocompras rc
				INNER JOIN mastpersonas p ON (rc.CodProveedor = p.CodPersona)
			WHERE 1 $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				rc.*,
				p.NomCompleto AS NomProveedor
			FROM
				ap_registrocompras rc
				INNER JOIN mastpersonas p ON (rc.CodProveedor = p.CodPersona)
			WHERE 1 $filtro
			ORDER BY Periodo, SistemaFuente, Secuencia
			LIMIT ".intval($limit).", ".intval($maxlimit);
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[Periodo].$field[SistemaFuente].$field[Secuencia]";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$field['Periodo']?></td>
			<td align="center"><?=$field['SistemaFuente']?></td>
			<td align="center"><?=$field['RifProveedor']?></td>
			<td><?=($field['NomProveedor'])?></td>
			<td align="right"><?=number_format($field['MontoImponible'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field['MontoNoAfecto'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field['MontoImpuestoVentas'], 2, ',', '.')?></td>
			<td align="right"><strong><?=number_format($field['MontoObligacion'], 2, ',', '.')?></strong></td>
			<td><?=$field['CodTipoDocumento']?>-<?=$field['NroDocumento']?></td>
			<td align="center"><?=formatFechaDMA($field['FechaDocumento'])?></td>
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