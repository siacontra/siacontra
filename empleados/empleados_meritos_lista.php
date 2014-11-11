<?php
//	------------------------------------
if ($filtrar == "default") {
	$fOrderBy = "Secuencia";
}
//	------------------------------------
?>
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=empleados_meritos_lista" method="post" autocomplete="off">
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
            <input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=empleados_meritos_form&opcion=nuevo');" />
            
            <input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=empleados_meritos_form&opcion=modificar', 'SELF', '', $('#sel_registros').val());" />
            
            <input type="button" id="btEliminar" value="Eliminar" style="width:75px; <?=$btEliminar?>" onclick="opcionRegistroParent(this.form, this.form.sel_registros.value, 'empleados_meritos', 'eliminar');" />
            
            <input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=empleados_meritos_form&opcion=ver', 'SELF', '', $('#sel_registros').val());" />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:885px; height:400px;">
<table width="100%" class="tblLista">
    <tbody id="lista_registros">
    <?php
    //	consulto lista
    $sql = "SELECT
				mf.CodPersona,
				mf.Secuencia,
				mf.Documento,
				mf.FechaDoc,
				mf.Observacion,
				mf.Externo,
				p.NomCompleto AS NomResponsable,
				md.Descripcion AS NomClasificacion
            FROM
				rh_meritosfaltas mf
				LEFT JOIN mastpersonas AS p ON (p.CodPersona = mf.Responsable)
				LEFT JOIN mastmiscelaneosdet md ON (md.codDetalle = mf.Clasificacion AND
													md.CodMaestro = 'MERITO' AND
													md.CodAplicacion = 'RH')
            WHERE
				mf.CodPersona = '".$CodPersona."' AND
				mf.Tipo = 'M'
            ORDER BY $fOrderBy";
    $query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);	$i=0;
    while ($field = mysql_fetch_array($query)) {
        $id = "$field[CodPersona]_$field[Secuencia]";
		if ($field['NomResponsable'] != "") $NomResponsable = $field['NomResponsable']; else $NomResponsable = $field['Externo'];
        ?>
        <tr class="trListaBody" onclick="clk($(this), 'registros', '<?=$id?>');">
        	<td>
            	<table border="1" width="100%">
                	<tr>
                    	<td class="th" width="60">
                        	Clasificaci&oacute;n:
                        </td>
                        <td>
							<strong style="font-size:12px;"><?=htmlentities($field['NomClasificacion'])?></strong>
                        </td>
                    	<td class="th" width="60">
                        	Documento:
                        </td>
                        <td width="250">
							<?=htmlentities($field['Documento'])?>
                        </td>
                    	<td class="th" width="60">
                        	Fecha:
                        </td>
                        <td align="center" width="75">
							<?=formatFechaDMA($field['FechaDoc'])?>
                        </td>
					</tr>
                    <tr>
                    	<td class="th">
                        	Responsable:
                        </td>
                        <td colspan="5">
							<?=htmlentities($NomResponsable)?>
                        </td>
					</tr>
                    <tr>
                    	<td class="th">
                        	Observaciones:
                        </td>
                        <td colspan="5">
							<?=htmlentities($field['Observacion'])?>
                        </td>
					</tr>
                </table>
            </td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
</center>
</form>