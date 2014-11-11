<?php
//	consulto datos generales	
$sql = "SELECT
			u.*,
			p.NomCompleto AS NomEmpleado,
			sa.FlagAdministrador
		FROM
			usuarios u
			INNER JOIN mastpersonas p ON (u.CodPersona = p.CodPersona)
			LEFT JOIN seguridad_autorizaciones sa ON (sa.CodAplicacion = '".$_SESSION["APLICACION_ACTUAL"]."' AND
													  sa.Usuario = '".$registro."')
		WHERE u.Usuario = '".$registro."'";
$query_usuario = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_usuario)) $field_usuario = mysql_fetch_array($query_usuario);
if ($opcion == "agregar") {
	$accion = "autorizaciones";
	$titulo = "Dar Autorizaciones a Usuario";
	$cancelar = "document.getElementById('frmentrada').submit();";
}
elseif ($opcion == "ver") {
	$disabled_ver = "disabled";
	$titulo = "Ver Autorizaciones";
	$cancelar = "window.close();";
	$display_ver = "display:none;";
}
if ($field_usuario['FlagAdministrador'] == "S") {
	$FlagAdministrador = "checked";
	$dMostrar = "disabled";
	$dSelTodos = "disabled";
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$cancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=usuarios_lista" method="POST" onsubmit="return usuarios_autorizaciones(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fedoreg" value="<?=$fedoreg?>" />
<input type="hidden" name="fordenar" value="<?=$fordenar?>" />
<input type="hidden" name="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="fbuscar" value="<?=$fbuscar?>" />
<input type="hidden" name="lista" value="<?=$lista?>" />

<table width="700" class="tblForm">
	<tr>
    	<td class="divFormCaption" colspan="2">Usuario</td>
    </tr>
	<tr>
		<td class="tagForm">Empleado:</td>
		<td>
            <input type="hidden" id="CodPersona" value="<?=$field_usuario['CodPersona']?>" />
			<input type="text" id="NomEmpleado" style="width:300px;" value="<?=($field_usuario['NomEmpleado'])?>" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Usuario:</td>
		<td>
        	<input type="text" id="Usuario" value="<?=$field_usuario['Usuario']?>" style="width:200px; font-weight:bold; font-size:12px;" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input type="text" size="30" value="<?=$field_usuario['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" value="<?=$field_usuario['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center>
<input type="submit" value="Guardar" style="width:80px; <?=$display_ver?>" />
<input type="button" value="Cancelar" style="width:80px;" onclick="<?=$cancelar?>" />
</center>
<br />

<table width="700" align="center">
	<tr>
    	<td align="right">
        	<input type="checkbox" id="FlagAdministrador" value="S" onclick="selChkTodosAdministrador(this.checked)" <?=$FlagAdministrador?> <?=$disabled_ver?> /> Administrador del Sistema
        </td>
    </tr>
</table>
</form>

<form name="frmautorizaciones" id="frmautorizaciones">
<center>
<div style="overflow:scroll; width:700px; height:400px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th scope="col" width="25">
        	<input type="checkbox" name="selTodos" id="selTodos" onClick="selChkTodosAutorizaciones(this.form, this.checked);" <?=$dSelTodos?> <?=$disabled_ver?> />
        </th>
		<th scope="col" align="left">Concepto</th>
		<th scope="col" width="25">N</th>
		<th scope="col" width="25">M</th>
		<th scope="col" width="25">E</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto autorizaciones
	$sql = "SELECT
				sc.Concepto,
				sc.Descripcion,
				sc.Grupo,
				sg.Descripcion AS NomGrupo,
				sa.FlagMostrar,
				sa.FlagAgregar,
				sa.FlagModificar,
				sa.FlagEliminar
			FROM
				seguridad_concepto sc
				INNER JOIN seguridad_grupo sg ON (sc.Grupo = sg.Grupo AND
												  sc.CodAplicacion = sg.CodAplicacion)
				LEFT JOIN seguridad_autorizaciones sa ON (sc.Concepto = sa.Concepto AND
														  sc.CodAplicacion = sa.CodAplicacion AND
														  sa.Usuario = '".$registro."')
			WHERE sc.CodAplicacion = '".$_SESSION["APLICACION_ACTUAL"]."'
			ORDER BY sc.Concepto";
	$query_autorizacion = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	$rows_autorizacion = mysql_num_rows($query_autorizacion);
	while ($field_autorizacion = mysql_fetch_array($query_autorizacion)) {
		if ($field_autorizacion['FlagMostrar'] == "S") {
			$FlagMostrar = "checked";
			$dAgregar = "";
			$dModificar = "";
			$dEliminar = "";
		} else {
			$FlagMostrar = "";
			$dAgregar = "disabled";
			$dModificar = "disabled";
			$dEliminar = "disabled";
		}
		if ($field_autorizacion['FlagAgregar'] == "S") $FlagAgregar = "checked"; else $FlagAgregar = "";
		if ($field_autorizacion['FlagModificar'] == "S") $FlagModificar = "checked"; else $FlagModificar = "";
		if ($field_autorizacion['FlagEliminar'] == "S") $FlagEliminar = "checked"; else $FlagEliminar = "";
		if ($grupo != $field_autorizacion['Grupo']) {
			$grupo = $field_autorizacion['Grupo'];
			?>
			<tr class="trListaBody2">
				<td colspan="2">
					<?=$field_autorizacion['NomGrupo']?>
				</td>
			</tr>
			<?
		}
		?>
		<tr class="trListaBody">
			<td align="center">
        		<input type="checkbox" name="FlagMostrar" id="FlagMostrar_<?=$field_autorizacion['Concepto']?>" value="<?=$field_autorizacion['Concepto']?>" <?=$FlagMostrar?> onClick="selAutorizaciones2('<?=$field_autorizacion['Concepto']?>');" <?=$dMostrar?> <?=$disabled_ver?> />
            </td>
			<td onClick="selAutorizaciones('<?=$field_autorizacion['Concepto']?>');">
				<?=$field_autorizacion['Descripcion']?>
            </td>
			<td align="center">
        		<input type="checkbox" name="FlagAgregar" id="FlagAgregar_<?=$field_autorizacion['Concepto']?>" <?=$FlagAgregar?> <?=$dAgregar?> <?=$disabled_ver?> />
            </td>
			<td align="center">
        		<input type="checkbox" name="FlagModificar" id="FlagModificar_<?=$field_autorizacion['Concepto']?>" <?=$FlagModificar?> <?=$dModificar?> <?=$disabled_ver?> />
            </td>
			<td align="center">
        		<input type="checkbox" name="FlagEliminar" id="FlagEliminar_<?=$field_autorizacion['Concepto']?>" <?=$FlagEliminar?> <?=$dEliminar?> <?=$disabled_ver?> />
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
