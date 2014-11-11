<?php
//	------------------------------------
if ($filtrar == "default") {
	$fOrderBy = "FechaGraduacion";
}
//	------------------------------------
?>
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=empleados_instruccion_carreras_lista" method="post" autocomplete="off">
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
            <input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=empleados_instruccion_carreras_form&opcion=nuevo');" />
            
            <input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=empleados_instruccion_carreras_form&opcion=modificar', 'SELF', '', $('#registro').val());" />
            
            <input type="button" id="btEliminar" value="Eliminar" style="width:75px; <?=$btEliminar?>" onclick="opcionRegistroParent(this.form, this.form.registro.value, 'empleados_instruccion_carreras', 'eliminar');" />
            
            <input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=empleados_instruccion_carreras_form&opcion=ver', 'SELF', '', $('#registro').val());" />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:885px; height:350px;">
<table width="1200" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="225" onclick="order('NomGradoInstruccion')"><a href="javascript:">Grado de Instrucci&oacute;n</a></th>
        <th scope="col" width="300" onclick="order('NomProfesion')"><a href="javascript:">Profesi&oacute;n</a></th>
        <th scope="col" onclick="order('NomCentroEstudio')"><a href="javascript:">Centro de Estudio</a></th>
        <th scope="col" width="75" onclick="order('FechaGraduacion')"><a href="javascript:">Fecha Graduaci&oacute;n</a></th>
        <th scope="col" width="50" onclick="order('Tiempo')"><a href="javascript:">Tiempo</a></th>
    </tr>
    </thead>
    
    <tbody>
    <?php
    //	consulto lista
    $sql = "SELECT
				ei.CodPersona,
				ei.Secuencia,
				ei.FechaGraduacion,
				((YEAR(CURDATE()) - YEAR(ei.FechaGraduacion)) - (RIGHT(CURDATE(), 5) < RIGHT(ei.FechaGraduacion, 5))) AS Tiempo,
				gi.Descripcion AS NomGradoInstruccion,
				pf.Descripcion AS NomProfesion,
				ce.Descripcion AS NomCentroEstudio
            FROM
				rh_empleado_instruccion ei
				INNER JOIN rh_gradoinstruccion gi ON (gi.CodGradoInstruccion = ei.CodGradoInstruccion)
				INNER JOIN rh_centrosestudios ce ON (ce.CodCentroEstudio = ei.CodCentroEstudio)
				LEFT JOIN rh_profesiones pf ON (pf.CodProfesion = ei.CodProfesion)
            WHERE ei.CodPersona = '".$CodPersona."'
            ORDER BY $fOrderBy";
    $query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
    while ($field = mysql_fetch_array($query)) {
        $id = "$field[CodPersona]_$field[Secuencia]";
        ?>
        <tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
            <td><?=htmlentities($field['NomGradoInstruccion'])?></td>
            <td><?=htmlentities($field['NomProfesion'])?></td>
            <td><?=htmlentities($field['NomCentroEstudio'])?></td>
            <td align="center"><?=formatFechaDMA($field['FechaGraduacion'])?></td>
            <td align="center"><?=$field['Tiempo']?> a&ntilde;os</td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
</center>
</form>