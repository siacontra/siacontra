<?php
//	------------------------------------
//	si en el formulario el usuario cambio la imagen la guardo
if ($FlagCopiarImagen == "S" && $_CodDocumento != "") {
	$nm = "$CodPersona_"."$_CodDocumento";
	//	elimino la foto anterior
	if ($RutaAnterior != "") unlink($_PARAMETRO["PATHIMGDOC"].$RutaAnterior);
	//	copio la foto
	list($im, $_error) = copiarFoto("Ruta", $nm, $_PARAMETRO["PATHIMGDOC"]);
	//	actualizo el campo foto
	$sql = "UPDATE rh_empleado_documentos
			SET Ruta = '".$im."'
			WHERE
				CodPersona = '".$CodPersona."' AND
				CodDocumento = '".$_CodDocumento."'";
	$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
}
//	------------------------------------
if ($filtrar == "default") {
	$fOrderBy = "CodDocumento";
}
//	------------------------------------
?>
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=empleados_documentos_entregados_lista" method="post" autocomplete="off">
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
            <input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=empleados_documentos_entregados_form&opcion=nuevo');" />
            
            <input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=empleados_documentos_entregados_form&opcion=modificar', 'SELF', '', $('#sel_registros').val());" />
            
            <input type="button" id="btEliminar" value="Eliminar" style="width:75px; <?=$btEliminar?>" onclick="opcionRegistroParent(this.form, this.form.sel_registros.value, 'empleados_documentos_entregados', 'eliminar');" />
            
            <input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=empleados_documentos_entregados_form&opcion=ver', 'SELF', '', $('#sel_registros').val());" />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:885px; height:330px;">
<table width="100%" class="tblLista">
    <tbody id="lista_registros">
    <?php
    //	consulto lista
    $sql = "SELECT
				ed.CodPersona,
				ed.CodDocumento,
				ed.FechaPresento,
				ed.FechaVence,
				ed.FlagPresento,
				ed.Observaciones,
				CONCAT(cf.NombresCarga, ' ', cf.ApellidosCarga) AS NomFamiliar,
				md.Descripcion AS NomDocumento
            FROM
				rh_empleado_documentos ed
				LEFT JOIN rh_cargafamiliar cf ON (cf.CodPersona = ed.CodPersona AND
												  cf.CodSecuencia = ed.CargaFamiliar)
				LEFT JOIN mastmiscelaneosdet md ON (md.CodDetalle = ed.Documento AND
													md.CodMaestro = 'DOCUMENTOS' AND
													md.CodAplicacion = 'RH')
            WHERE ed.CodPersona = '".$CodPersona."'
            ORDER BY $fOrderBy";
    $query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);	$i=0;
    while ($field = mysql_fetch_array($query)) {
        $id = "$field[CodPersona]_$field[CodDocumento]";
		if ($field['NomResponsable'] != "") $NomResponsable = $field['NomResponsable']; else $NomResponsable = $field['Externo'];
        ?>
        <tr class="trListaBody" onclick="clk($(this), 'registros', '<?=$id?>'); parent.$('#a_movimientos').css('display', 'block');">
        	<td>
            	<table border="1" width="100%">
                	<tr>
                    	<td class="th" width="100">
                        	Documento:
                        </td>
                        <td>
							<strong style="font-size:12px;"><?=htmlentities($field['NomDocumento'])?></strong>
                        </td>
                    	<td class="th" width="60">
                        	Presento:
                        </td>
                        <td align="center" width="35">
							<?=printFlag($field['FlagPresento'])?>
                        </td>
                    	<td class="th" width="60">
                        	Fecha:
                        </td>
                        <td align="center" width="75">
							<?=formatFechaDMA($field['FechaPresento'])?>
                        </td>
					</tr>
                	<tr>
                    	<td class="th">
                        	Persona:
                        </td>
                        <td colspan="3">
							<?=($field['NomFamiliar'])?>
                        </td>
                    	<td class="th" width="60">
                        	Vence:
                        </td>
                        <td align="center" width="75">
							<?=formatFechaDMA($field['FechaVence'])?>
                        </td>
					</tr>
                	<tr>
                    	<td class="th">
                        	Observaciones:
                        </td>
                        <td colspan="5">
							<?=htmlentities($field['Observaciones'])?>
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

<script type="text/javascript" language="javascript">
	$(document).ready(function(){
		parent.$(".div-progressbar").css("display", "none");
	});
</script>
