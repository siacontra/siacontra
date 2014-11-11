<form name="frmentrada" id="frmentrada" action="gehen.php?anz=empleados_instruccion_cursos_lista" method="post" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="CodPersona" id="CodPersona" value="<?=$CodPersona?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="registro" id="registro" />

<center>
<table width="885" class="tblBotones">
    <tr>
        <td><div id="rows"></div></td>
        <td align="right">
            <input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=empleados_instruccion_cursos_form&opcion=nuevo');" />
            
            <input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=empleados_instruccion_cursos_form&opcion=modificar', 'SELF', '', $('#registro').val());" />
            
            <input type="button" id="btEliminar" value="Eliminar" style="width:75px; <?=$btEliminar?>" onclick="opcionRegistroParent(this.form, this.form.registro.value, 'empleados_instruccion_cursos', 'eliminar');" />
            
            <input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=empleados_instruccion_cursos_form&opcion=ver', 'SELF', '', $('#registro').val());" />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:885px; height:350px;">
<table width="100%" class="tblLista">    
    <tbody>
    <?php
    //	consulto lista
    $sql = "SELECT
				ec.CodPersona,
				ec.Secuencia,
				ec.FlagInstitucional,
				ec.FlagPago,
				ec.FechaCulminacion,
				ec.FechaDesde,
				ec.FechaHasta,
				ec.TotalHoras,
				ec.AniosVigencia,
				ec.Observaciones,
				c.Descripcion AS Curso,
				ce.Descripcion AS CentroEstudio
            FROM
				rh_empleado_cursos ec
				INNER JOIN rh_cursos c ON (c.CodCurso = ec.CodCurso)
				INNER JOIN rh_centrosestudios ce ON (ce.CodCentroEstudio = ec.CodCentroEstudio)
            WHERE ec.CodPersona = '".$CodPersona."'
            ORDER BY FechaCulminacion, FechaHasta, Secuencia";
    $query = mysql_query($sql) or die ($sql.mysql_error());	$i=0;
	$rows_total = mysql_num_rows($query);
    while ($field = mysql_fetch_array($query)) {
        $id = "$field[CodPersona]_$field[Secuencia]";
        ?>
        <tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
            <td>
            	<table border="1" width="100%">
                	<tr>
                    	<td class="th">
                        	Nro.:
                        </td>
                        <td colspan="2">
							<strong style="font-size:14px;"><?=++$i?></strong>
                        </td>
                        <td colspan="4" align="right">
							Auspiciado por el Organismo: <input type="checkbox" <?=chkFlag($field['FlagInstitucional'])?> disabled />
                        </td>
                        <td colspan="3" align="right">
							Pago N&oacute;mina: <input type="checkbox" <?=chkFlag($field['FlagPago'])?> disabled />
                        </td>
                    </tr>
                	<tr>
                    	<td class="th">
                        	Curso:
                        </td>
                        <td colspan="9">
							<strong style="font-size:12px;"><?=htmlentities($field['Curso'])?></strong>
                        </td>
                    </tr>
                	<tr>
                    	<td class="th">
                        	Centro:
                        </td>
                        <td colspan="9">
							<?=htmlentities($field['CentroEstudio'])?>
                        </td>
                    </tr>
                	<tr>
                    	<td class="th" width="100">
                        	Periodo:
                        </td>
                        <td>
							<?=$field['FechaCulminacion']?>
                        </td>
                    	<td class="th" width="55">
                        	Desde:
                        </td>
           				<td width="100">
							<?=formatFechaDMA($field['FechaDesde'])?>
                        </td>
                    	<td class="th" width="55">
                        	Hasta:
                        </td>
           				<td width="100">
							<?=formatFechaDMA($field['FechaHasta'])?>
                        </td>
                    	<td class="th" width="55">
                        	Horas:
                        </td>
                        <td width="60">
							<?=$field['TotalHoras']?>
                        </td>
                    	<td class="th" width="55">
                        	A&ntilde;os:
                        </td>
                        <td width="60">
							<?=$field['AniosVigencia']?>
                        </td>
                    </tr>
                	<tr>
                    	<td class="th">
                        	Observaciones:
                        </td>
                        <td colspan="9">
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