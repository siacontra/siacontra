<?php
//	------------------------------------
if ($filtrar == "default") {
	$fdOrderBy = "FechaNacimiento";
	$CodPersona = $registro;
}
//	------------------------------------
//	datos del empleado
$sql = "SELECT
			p.CodPersona,
			p.NomCompleto,
			e.CodEmpleado
		FROM
			mastpersonas p
			INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
		WHERE p.CodPersona = '".$CodPersona."'";
$query_empleado = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_empleado)) $field_empleado = mysql_fetch_array($query_empleado);
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Carga Familiar del Empleado</td>
		<td align="right"><a class="cerrar" href="#" onclick="$('#frmentrada').attr('action', 'gehen.php?anz=empleados_lista'); document.getElementById('frmentrada').submit()">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=empleados_carga_familiar_lista" method="post" autocomplete="off">
<?=filtroEmpleados()?>
<input type="hidden" name="fdOrderBy" id="fdOrderBy" value="<?=$fdOrderBy?>" />
<input type="hidden" name="CodPersona" id="CodPersona" value="<?=$field_empleado['CodPersona']?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:900px;">
<table width="900" class="tblFiltro">
	<tr>
    	<td colspan="2" class="divFormCaption">Datos del Empleado</td>
    </tr>
	<tr>
		<td align="right" width="125">Empleado:</td>
		<td>
        	<input type="text" id="CodEmpleado" style="width:60px;" class="codigo" value="<?=$field_empleado['CodEmpleado']?>" disabled />
		</td>
	</tr>
	<tr>
		<td align="right">Nombre Completo:</td>
		<td>
        	<input type="text" id="NomCompleto" style="width:500px;" class="codigo" value="<?=$field_empleado['NomCompleto']?>" disabled />
		</td>
	</tr>
</table>
</div><br />

<center>
<table width="900" class="tblBotones">
    <tr>
        <td><div id="rows"></div></td>
        <td align="right">
            <input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=empleados_carga_familiar_form&opcion=nuevo');" />
            
            <input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=empleados_carga_familiar_form&opcion=modificar', 'SELF', '', $('#registro').val());" />
            
            <input type="button" id="btEliminar" value="Eliminar" style="width:75px; <?=$btEliminar?>" onclick="opcionRegistro2(this.form, this.form.registro.value, 'empleados_carga_familiar', 'eliminar');" />
            
            <input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=empleados_carga_familiar_form&opcion=ver', 'SELF', '', $('#registro').val());" />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:900px; height:300px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="75" align="right" onclick="order('LENGTH(Ndocumento), Ndocumento', '', 'fdOrderBy')"><a href="javascript:">Nro. Documento</a></th>
        <th scope="col" align="left" onclick="order('NomCompleto', '', 'fdOrderBy')"><a href="javascript:">Nombre</a></th>
        <th scope="col" width="100" onclick="order('NomParentesco', '', 'fdOrderBy')"><a href="javascript:">Parentesco</a></th>
        <th scope="col" width="25" onclick="order('FechaNacimiento', '', 'fdOrderBy')"><a href="javascript:">Fecha Nacimiento</a></th>
        <th scope="col" width="75" onclick="order('Sexo', '', 'fdOrderBy')"><a href="javascript:">Sexo</a></th>
        <th scope="col" width="75" onclick="order('Estado', '', 'fdOrderBy')"><a href="javascript:">Estado</a></th>
    </tr>
    </thead>
    
    <tbody>
    <?php
    //	consulto lista
    $sql = "SELECT
				cf.CodPersona,
				cf.CodSecuencia,
				cf.Ndocumento,
				CONCAT(cf.NombresCarga, ' ', cf.ApellidosCarga) AS NomCompleto,
				md.Descripcion AS NomParentesco,
				cf.FechaNacimiento,
				cf.Sexo,
				cf.Estado
            FROM
				rh_cargafamiliar cf
				LEFT JOIN mastmiscelaneosdet md ON (md.CodDetalle = cf.Parentesco AND
													md.CodMaestro = 'PARENT')
            WHERE cf.CodPersona = '".$CodPersona."'
            ORDER BY $fdOrderBy";

    $query = mysql_query($sql) or die ($sql.mysql_error());
	
	$rows_total = mysql_num_rows($query);
    while ($field = mysql_fetch_array($query)) {
        $id = "$field[CodPersona]_$field[CodSecuencia]";
        ?>
        <tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
            <td align="right"><?=$field['Ndocumento']?></td>
            <td><?=(($field['NomCompleto']))?></td>
            <td align="center"><?=htmlentities($field['NomParentesco'])?></td>
            <td align="center"><?=formatFechaDMA($field['FechaNacimiento'])?></td>
            <td align="center"><?=printValoresGeneral("SEXO", $field['Sexo'])?></td>
            <td align="center"><?=printValoresGeneral("ESTADO", $field['Estado'])?></td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
</center>
</form>
