<?php
$Ahora = ahora();
$focus = "Descripcion";
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Nueva Familia";
	$label_submit = "Guardar";
	$field_familia['Estado'] = "A";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	list($CodLinea, $CodFamilia) = split("[.]", $registro);
	//	consulto datos generales
	$sql = "SELECT *
			FROM lg_clasefamilia
			WHERE
				CodLinea = '".$CodLinea."' AND
				CodFamilia = '".$CodFamilia."'";
	$query_familia = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_familia)) $field_familia = mysql_fetch_array($query_familia);
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Familia";
		$accion = "modificar";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
		$label_submit = "Modificar";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Familia";
		$disabled_ver = "disabled";
		$display_ver = "display:none;";
		$display_submit = "display:none;";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
	}
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="document.getElementById('frmentrada').submit()">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=lg_familia_lista" method="POST" onsubmit="return familia(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />

<table width="550" class="tblForm">
	<tr>
    	<td colspan="2" class="divFormCaption">Datos Generales</td>
    </tr>
	<tr>
		<td class="tagForm">* Linea:</td>
		<td>
            <select name="CodLinea" id="CodLinea" style="width:255px; height:20px;" class="codigo" <?=$disabled_modificar?>>
                <?=loadSelect("lg_claselinea", "CodLinea", "Descripcion", $field_familia['CodLinea'], 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm" width="125">* Familia:</td>
		<td><input type="text" id="CodFamilia" style="width:75px;" class="codigo" value="<?=$field_familia['CodFamilia']?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">* Descripci&oacute;n:</td>
		<td><input type="text" id="Descripcion" style="width:90%;" maxlength="100" value="<?=($field_familia['Descripcion'])?>" <?=$disabled_ver?> /></td>
	</tr>
	<tr>
    	<td colspan="2" class="divFormCaption">Informaci&oacute;n Contable</td>
    </tr>
	<tr>
		<td class="tagForm">Cta. Inventario:</td>
		<td class="gallery clearfix">
        	<input type="text" id="CuentaInventario" disabled="disabled" style="width:100px;" value="<?=$field_item['CuentaInventario']?>" />
			<input type="hidden" id="NomCuentaInventario" />
			<a href="../lib/listas/listado_plan_cuentas.php?filtrar=default&cod=CuentaInventario&nom=NomCuentaInventario&iframe=true&width=950&height=525" rel="prettyPhoto[iframe2]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Cta. Gasto:</td>
		<td class="gallery clearfix">
        	<input type="text" id="CuentaGasto" disabled="disabled" style="width:100px;" value="<?=$field_item['CuentaGasto']?>" />
			<input type="hidden" id="NomCuentaGasto" />
			<a href="../lib/listas/listado_plan_cuentas.php?filtrar=default&cod=CuentaGasto&nom=NomCuentaGasto&iframe=true&width=950&height=525" rel="prettyPhoto[iframe3]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Cta. Venta:</td>
		<td class="gallery clearfix">
        	<input type="text" id="CuentaVentas" disabled="disabled" style="width:100px;" value="<?=$field_item['CuentaVentas']?>" />
			<input type="hidden" id="NomCuentaVentas" />
			<a href="../lib/listas/listado_plan_cuentas.php?filtrar=default&cod=CuentaVentas&nom=NomCuentaVentas&iframe=true&width=950&height=525" rel="prettyPhoto[iframe4]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Partida Presupuestaria:</td>
		<td class="gallery clearfix">
        	<input type="text" id="PartidaPresupuestal" disabled="disabled" style="width:100px;" value="<?=$field_item['PartidaPresupuestal']?>" />
			<input type="hidden" id="NomPartidaPresupuestal" />
			<a href="../lib/listas/listado_clasificador_presupuestario.php?filtrar=default&cod=PartidaPresupuestal&nom=NomPartidaPresupuestal&iframe=true&width=950&height=525" rel="prettyPhoto[iframe5]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
			<input type="radio" name="Estado" id="Activo" value="A" <?=chkOpt($field_familia['Estado'], "A")?> <?=$disabled_ver?> /> Activo
			<input type="radio" name="Estado" id="Inactivo" value="I" <?=chkOpt($field_familia['Estado'], "I")?> <?=$disabled_ver?> /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input type="text" size="30" value="<?=$field_familia['UltimoUsuario']?>" class="disabled" disabled="disabled" />
			<input type="text" size="25" value="<?=$field_familia['UltimaFecha']?>" class="disabled" disabled="disabled" />
		</td>
	</tr>  
</table>

<center> 
<input type="submit" value="<?=$label_submit?>" style=" <?=$display_submit?>" />
<input type="button" value="Cancelar" onclick="this.form.submit();" />
</center>
</form>
<div style="width:550px" class="divMsj">Campos Obligatorios *</div>