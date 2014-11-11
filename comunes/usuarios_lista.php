<?php
if ($lista == "usuarios") {
	$btAutorizaciones = "style='display:none;'";
	$btVerAutorizaciones = "style='display:none;'";
	$btAlterna = "style='display:none;'";
	$btVerAlterna = "style='display:none;'";
	$titulo = "Maestro de Usuarios";
}
elseif ($lista == "autorizaciones") {
	$btNuevo = "style='display:none;'";
	$btModificar = "style='display:none;'";
	$btVer = "style='display:none;'";
	$btAlterna = "style='display:none;'";
	$btVerAlterna = "style='display:none;'";
	$titulo = "Dar Autorizaciones a Usuarios";
}
elseif ($lista == "alterna") {
	$btNuevo = "style='display:none;'";
	$btModificar = "style='display:none;'";
	$btVer = "style='display:none;'";
	$btAutorizaciones = "style='display:none;'";
	$btVerAutorizaciones = "style='display:none;'";
	$titulo = "Dar Autorizaciones a Usuarios";
}
//	------------------------------------
if ($filtrar == "default") {
	$fordenar = "u.Usuario";
	$fedoreg = "A";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fordenar != "") { $cordenar = "checked"; $orderby = "ORDER BY $fordenar"; } else $dordenar = "disabled";
if ($fedoreg != "") { $cedoreg = "checked"; $filtro.=" AND (u.Estado = '".$fedoreg."')"; } else $dedoreg = "disabled";
if ($fbuscar != "") {
	$cbuscar = "checked";
	$filtro.=" AND (u.Usuario LIKE '%".$fbuscar."%' OR 
					p.NomCompleto LIKE '%".$fbuscar."%' OR 
					u.FechaExpirar LIKE '%".$fbuscar."%')";
} else $dbuscar = "disabled";
//	------------------------------------
?>

<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=usuarios_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<div class="divBorder" style="width:900px;">
<table width="900" class="tblFiltro">
	<tr>
		<td align="right" width="125">Estado Reg.:</td>
		<td>
            <input type="checkbox" <?=$cedoreg?> onclick="chkFiltro(this.checked, 'fedoreg');" />
            <select name="fedoreg" id="fedoreg" style="width:100px;" <?=$dedoreg?>>
            	<?php
				if ($lista == "usuarios") {
					?>
                    <option value="">&nbsp;</option>
                    <?=loadSelectGeneral("ESTADO", $fedoreg, 0)?>
                    <?
				}
				else {
					?>
                    <?=loadSelectGeneral("ESTADO", "A", 1)?>
                    <?
				}
				?>
            </select>
		</td>
		<td align="right" width="125">Buscar:</td>
        <td>
            <input type="checkbox" <?=$cbuscar?> onclick="chkFiltro(this.checked, 'fbuscar');" />
            <input type="text" name="fbuscar" id="fbuscar" style="width:200px;" value="<?=$fbuscar?>" <?=$dbuscar?> />
		</td>
	</tr>
	<tr>
		<td align="right">Ordenar Por:</td>
		<td>
            <input type="checkbox" <?=$cordenar?> onclick="this.checked=!this.checked;" />
            <select name="fordenar" id="fordenar" style="width:100px;" <?=$dordenar?>>
                <?=loadSelectGeneral("ORDENAR-USUARIO", $fordenar, 0)?>
            </select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="900" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'gehen.php?anz=usuarios_form&opcion=nuevo');" <?=$btNuevo?> />
            
			<input type="button" class="btLista" id="btModificar" value="Modificar" onclick="cargarOpcion2(this.form, 'gehen.php?anz=usuarios_form&opcion=modificar', 'SELF', '', $('#registro').val());" <?=$btModificar?> />
            
			<input type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion2(this.form, 'gehen.php?anz=usuarios_form&opcion=ver', 'BLANK', 'height=300, width=800, left=100, top=0, resizable=no', $('#registro').val());" <?=$btVer?> />
            
			<input type="button" class="btLista" id="btAutorizaciones" value="Agregar" <?php echo $btAutorizaciones; ?> onclick="cargarOpcion2(this.form, 'gehen.php?anz=usuarios_autorizaciones&opcion=agregar', 'SELF', '', $('#registro').val());"/>
            
			<input type="button" class="btLista" id="btVerAutorizaciones" value="Ver" onclick="cargarOpcion2(this.form, 'gehen.php?anz=usuarios_autorizaciones&opcion=ver', 'BLANK', 'height=800, width=900, left=100, top=0, resizable=no', $('#registro').val());" <?=$btVerAutorizaciones?> />
            
			<input type="button" class="btLista" id="btAlterna" value="Agregar" onclick="cargarOpcion2(this.form, 'gehen.php?anz=usuarios_alterna&opcion=agregar', 'SELF', '', $('#registro').val());" <?=$btAlterna?> />
            
			<input type="button" class="btLista" id="btVerAlterna" value="Ver" onclick="cargarOpcion2(this.form, 'gehen.php?anz=usuarios_alterna&opcion=ver', 'BLANK', 'height=700, width=900, left=100, top=0, resizable=no', $('#registro').val());" <?=$btVerAlterna?> />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:900px; height:300px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th scope="col" align="left">Persona</th>
		<th scope="col" width="200" align="left">Usuario</th>
		<th scope="col" width="35">Expira</th>
		<th scope="col" width="100">Fecha Expira</th>
		<th scope="col" width="75">Estado</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				u.*,
				p.NomCompleto AS NomEmpleado
			FROM
				usuarios u
				INNER JOIN mastpersonas p ON (u.CodPersona = p.CodPersona)
			WHERE 1 $filtro";
	$query_lista = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	$rows_total = mysql_num_rows($query_lista);
	
	//	consulto lista
	$sql = "SELECT
				u.*,
				p.NomCompleto AS NomEmpleado
			FROM
				usuarios u
				INNER JOIN mastpersonas p ON (u.CodPersona = p.CodPersona)
			WHERE 1 $filtro
			$orderby
			LIMIT ".intval($limit).", $maxlimit";
	$query_lista = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	$rows_lista = mysql_num_rows($query_lista);
	while ($field_lista = mysql_fetch_array($query_lista)) {
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$field_lista['Usuario']?>">
			<td><?=($field_lista['NomEmpleado'])?></td>
			<td><?=$field_lista['Usuario']?></td>
			<td align="center"><?=printFlag($field_lista['FlagFechaExpirar'])?></td>
			<td align="center"><?=formatFechaDMA($field_lista['FechaExpirar'])?></td>
			<td align="center"><?=printValoresGeneral("ESTADO", $field_lista['Estado'])?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
<table width="900">
	<tr>
    	<td>
        	Mostrar: 
            <select name="maxlimit" style="width:50px;" onchange="this.form.submit();">
                <?=loadSelectGeneral("MAXLIMIT", $maxlimit, 0)?>
            </select>
        </td>
        <td align="right">
        	<?=paginacion(intval($rows_total), intval($rows_lista), intval($maxlimit), intval($limit));?>
        </td>
    </tr>
</table>
</center>
</form>