<?php
//	------------------------------------
if ($filtrar == "default") {
	list($CodPersona, $CodDocumento) = split("[_]", $registro);
	$fOrderBy = "CodDocumento";
}
//	------------------------------------
?>
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=empleados_documentos_movimientos_lista" method="post" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="CodPersona" id="CodPersona" value="<?=$CodPersona?>" />
<input type="hidden" name="CodDocumento" id="CodDocumento" value="<?=$CodDocumento?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="sel_registros" id="sel_registros" />

<center>
<table width="885" class="tblBotones">
    <tr>
        <td><div id="rows"></div></td>
        <td align="right">
            <input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=empleados_documentos_movimientos_form&opcion=nuevo');" />
            
            <input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=empleados_documentos_movimientos_form&opcion=modificar', 'SELF', '', $('#sel_registros').val());" />
            
            <input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=empleados_documentos_movimientos_form&opcion=ver', 'SELF', '', $('#sel_registros').val());" />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:885px; height:330px;">
<table width="100%" class="tblLista">
    <tbody id="lista_registros">
    <?php
    //	consulto lista
    $sql = "SELECT
				dh.*,
				p.NomCompleto AS NomResponsable
            FROM
				rh_documentos_historia dh
				INNER JOIN mastpersonas p ON (p.CodPersona = dh.CodPersona)
            WHERE
				dh.CodPersona = '".$CodPersona."' AND
				dh.CodDocumento = '".$CodDocumento."'
            ORDER BY $fOrderBy";
    $query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);	$i=0;
    while ($field = mysql_fetch_array($query)) {
        $id = "$field[CodPersona]_$field[CodDocumento]_$field[Secuencia]";
        ?>
        <tr class="trListaBody" onclick="clk($(this), 'registros', '<?=$id?>'); parent.$('#a_movimientos').css('display', 'block');">
        	<td>
            	<table border="1" width="100%">
                	<tr>
                    	<td class="th" width="100">
                        	Fecha Entrega:
                        </td>
                        <td align="center" width="75">
							<?=formatFechaDMA($field['FechaEntrega'])?>
                        </td>
                    	<td class="th" width="60">
                        	Responsable:
                        </td>
                        <td>
							<?=htmlentities($field['NomResponsable'])?>
                        </td>
                    	<td class="th" width="60">
                        	Estado:
                        </td>
                        <td width="75">
							<?=printValores("ESTADO-DOCUMENTOS", $field['Estado'])?>
                        </td>
                    	<td class="th" width="60">
                        	Fecha:
                        </td>
                        <td align="center" width="75">
							<?=formatFechaDMA($field['FechaDevuelto'])?>
                        </td>
					</tr>
                	<tr>
                    	<td class="th">
                        	Obs. Entrega:
                        </td>
                        <td colspan="7">
							<?=htmlentities($field['ObsEntrega'])?>
                        </td>
					</tr>
                	<tr>
                    	<td class="th">
                        	Observaciones:
                        </td>
                        <td colspan="7">
							<?=htmlentities($field['ObsDevuelto'])?>
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