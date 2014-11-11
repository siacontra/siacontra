<?php
//	------------------------------------
if ($filtrar == "default") {
	$fOrderBy = "Secuencia";
}
//	------------------------------------
?>
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=empleados_patrimonio_cuentas_lista" method="post" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="CodPersona" id="CodPersona" value="<?=$CodPersona?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="sel_registros" id="sel_registros" />

<center>
<table width="885" class="tblBotones">
    <tr>
        <td><div id="rows"></div></td>
        <td align="right">
            <input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=empleados_patrimonio_cuentas_form&opcion=nuevo');" />
            
            <input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=empleados_patrimonio_cuentas_form&opcion=modificar', 'SELF', '', $('#sel_registros').val());" />
            
            <input type="button" id="btEliminar" value="Eliminar" style="width:75px; <?=$btEliminar?>" onclick="eliminarPatrimonio(this.form, this.form.sel_registros.value, 'empleados_patrimonio_cuentas', 'eliminar');" />
            
            <input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=empleados_patrimonio_cuentas_form&opcion=ver', 'SELF', '', $('#sel_registros').val());" />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:885px; height:250px;">
<table width="100%" class="tblLista">
	<thead>
    <tr>
        <th scope="col" align="left" onclick="order('Institucion')"><a href="javascript:">Instituci&oacute;n</a></th>
        <th scope="col" width="125" onclick="order('NomTipoCuenta')"><a href="javascript:">Tipo Cuenta</a></th>
        <th scope="col" width="250" onclick="order('NroCuenta')"><a href="javascript:">Nro. Cuenta</a></th>
        <th scope="col" width="75" align="right" onclick="order('Valor')"><a href="javascript:">Valor</a></th>
        <th scope="col" width="20" onclick="order('FlagGarantia')"><a href="javascript:">Gar.</a></th>
    </tr>
    </thead>
    
    <tbody id="lista_registros">
    <?php
    //	consulto lista
    $sql = "SELECT
				pc.*,
				md.Descripcion AS NomTipoCuenta
            FROM
				rh_patrimonio_cuenta pc
				LEFT JOIN mastmiscelaneosdet md ON (md.CodDetalle = pc.TipoCuenta AND
													md.CodMaestro = 'TIPOCTA' AND
													md.CodAplicacion = 'RH')
            WHERE pc.CodPersona = '".$CodPersona."'
            ORDER BY $fOrderBy";
    $query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);	$i=0;
    while ($field = mysql_fetch_array($query)) {
        $id = "$field[CodPersona]_$field[Secuencia]";
        ?>
        <tr class="trListaBody" onclick="clk($(this), 'registros', '<?=$id?>');">
            <td><?=htmlentities($field['Institucion'])?></td>
            <td align="center"><?=htmlentities($field['NomTipoCuenta'])?></td>
            <td align="center"><?=$field['NroCuenta']?></td>
            <td align="right"><?=number_format($field['Valor'], 2, ',', '.')?></td>
            <td align="center"><?=printFlag($field['FlagGarantia'])?></td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
</center>
</form>

<script type="text/javascript" language="javascript">
	$(document).ready(function(){
		parent.$(".div-progressbar").css("display", "none");
	});
</script>