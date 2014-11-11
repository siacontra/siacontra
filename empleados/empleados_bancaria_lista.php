<?php
//	------------------------------------
if ($filtrar == "default") {
	$fdOrderBy = "Banco";
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
		<td class="titulo">Informaci&oacute;n Bancaria del Empleado</td>
		<td align="right"><a class="cerrar" href="#" onclick="$('#frmentrada').attr('action', 'gehen.php?anz=empleados_lista'); document.getElementById('frmentrada').submit()">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=empleados_bancaria_lista" method="post" autocomplete="off">
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
            <input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=empleados_bancaria_form&opcion=nuevo');" />
            
            <input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=empleados_bancaria_form&opcion=modificar', 'SELF', '', $('#registro').val());" />
            
            <input type="button" id="btEliminar" value="Eliminar" style="width:75px; <?=$btEliminar?>" onclick="opcionRegistro2(this.form, this.form.registro.value, 'empleados_bancaria', 'eliminar');" />
            
            <input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=empleados_bancaria_form&opcion=ver', 'SELF', '', $('#registro').val());" />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:900px; height:300px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" align="left" onclick="order('Banco', '', 'fdOrderBy')"><a href="javascript:">Banco</a></th>
        <th scope="col" width="15" onclick="order('FlagPrincipal', '', 'fdOrderBy')"><a href="javascript:">Pri.</a></th>
        <th scope="col" width="125" onclick="order('NomTipoCuenta', '', 'fdOrderBy')"><a href="javascript:">Tipo de Cta.</a></th>
        <th scope="col" width="150" onclick="order('Ncuenta', '', 'fdOrderBy')"><a href="javascript:">Nro. de Cta.</a></th>
        <th scope="col" width="125" onclick="order('NomAportes', '', 'fdOrderBy')"><a href="javascript:">Aportes</a></th>
        <th scope="col" width="100" onclick="order('Monto', '', 'fdOrderBy')"><a href="javascript:">Monto</a></th>
    </tr>
    </thead>
    
    <tbody>
    <?php
    //	consulto lista
    $sql = "SELECT
				bp.*,
				b.Banco,
				md1.Descripcion AS NomTipoCuenta,
				md2.Descripcion AS NomAportes
            FROM
				bancopersona bp
				INNER JOIN mastbancos b ON (b.CodBanco = bp.CodBanco)
				LEFT JOIN mastmiscelaneosdet md1 ON (md1.CodDetalle = bp.TipoCuenta AND
													 md1.CodMaestro = 'TIPOCTA')
				LEFT JOIN mastmiscelaneosdet md2 ON (md2.CodDetalle = bp.Aportes AND
													 md2.CodMaestro = 'TIPOAPO')
            WHERE bp.CodPersona = '".$CodPersona."'
            ORDER BY $fdOrderBy";
    $query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
    while ($field = mysql_fetch_array($query)) {
        $id = "$field[CodPersona]_$field[CodSecuencia]";
        ?>
        <tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
            <td><?=htmlentities($field['Banco'])?></td>
            <td align="center"><?=printFlag($field['FlagPrincipal'])?></td>
            <td align="center"><?=htmlentities($field['NomTipoCuenta'])?></td>
            <td align="center"><?=$field['Ncuenta']?></td>
            <td align="center"><?=htmlentities($field['NomAportes'])?></td>
            <td align="right"><?=number_format($field['Monto'], 2, ',', '.')?></td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
</center>
</form>