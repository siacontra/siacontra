<?php
//	------------------------------------
if ($filtrar == "default") {
	$fOrderBy = "Secuencia";
}
//	------------------------------------
?>
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=empleados_referencias_personales_lista" method="post" autocomplete="off">
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
            <input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=empleados_referencias_personales_form&opcion=nuevo');" />
            
            <input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=empleados_referencias_personales_form&opcion=modificar', 'SELF', '', $('#sel_registros').val());" />
            
            <input type="button" id="btEliminar" value="Eliminar" style="width:75px; <?=$btEliminar?>" onclick="opcionRegistroParent(this.form, this.form.sel_registros.value, 'empleados_referencias_personales', 'eliminar');" />
            
            <input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=empleados_referencias_personales_form&opcion=ver', 'SELF', '', $('#sel_registros').val());" />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:885px; height:250px;">
<table width="1400" class="tblLista">
    <thead>
    <tr>
        <th scope="col" onclick="order('Nombre')"><a href="javascript:">Nombre del Jefe Anterior</a></th>
        <th scope="col" width="400" onclick="order('Empresa')"><a href="javascript:">Empresa</a></th>
        <th scope="col" width="400" onclick="order('Direccion')"><a href="javascript:">Direcci&oacute;n</a></th>
        <th scope="col" width="100" onclick="order('Telefono')"><a href="javascript:">Tel&eacute;fono</a></th>
    </tr>
    </thead>
    
    <tbody id="lista_registros">
    <?php
    //	consulto lista
    $sql = "SELECT *
            FROM rh_empleado_referencias
            WHERE
				CodPersona = '".$CodPersona."' AND
				Tipo = 'P'
            ORDER BY $fOrderBy";
    $query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
    while ($field = mysql_fetch_array($query)) {
        $id = "$field[CodPersona]_$field[Secuencia]";
        ?>
        <tr class="trListaBody" onclick="clk($(this), 'registros', '<?=$id?>');">
            <td><?=htmlentities($field['Nombre'])?></td>
            <td><?=htmlentities($field['Empresa'])?></td>
            <td><?=htmlentities($field['Direccion'])?></td>
            <td align="center"><?=$field['Telefono']?></td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
</center>
</form>