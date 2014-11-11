<?php
//	consulto datos generales	
$sql = "SELECT
			u.*,
			p.NomCompleto AS NomEmpleado
		FROM
			usuarios u
			INNER JOIN mastpersonas p ON (u.CodPersona = p.CodPersona)
		WHERE u.Usuario = '".$registro."'";
$query_usuario = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_usuario)) $field_usuario = mysql_fetch_array($query_usuario);
if ($opcion == "agregar") {
	$accion = "alterna";
	$titulo = "Dar Autorizaciones a Usuario";
	$cancelar = "document.getElementById('frmentrada').submit();";
}
elseif ($opcion == "ver") {
	$disabled_ver = "disabled";
	$titulo = "Ver Autorizaciones";
	$cancelar = "window.close();";
	$display_ver = "display:none;";
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$cancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=usuarios_lista" method="POST" onsubmit="return usuarios_alterna(this, '<?=$accion?>');">
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
</form>

<form name="frmautorizaciones" id="frmautorizaciones">
<center>
<div style="overflow:scroll; width:700px; height:400px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th scope="col" width="25">
        	<input type="checkbox" name="selTodos" id="selTodos" onClick="selChkTodosAutorizaciones(this.form, this.checked);" <?=$disabled_ver?> />
        </th>
		<th scope="col" align="left">Dependencia</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto autorizaciones
	$sql = "SELECT
				o.Organismo,
				o.CodOrganismo,
				d.CodDependencia,
				d.Dependencia,
				sa.FlagMostrar
			FROM
				mastdependencias d
				INNER JOIN mastorganismos o on (d.CodOrganismo = o.CodOrganismo)
				LEFT JOIN seguridad_alterna sa ON (sa.CodAplicacion = '".$_SESSION["APLICACION_ACTUAL"]."' AND
												   sa.Usuario = '".$registro."' AND
												   sa.CodOrganismo = d.CodOrganismo AND
												   sa.CodDependencia = d.CodDependencia)
			ORDER BY CodOrganismo, CodDependencia";
	$query_autorizacion = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	$rows_autorizacion = mysql_num_rows($query_autorizacion);
	while ($field_autorizacion = mysql_fetch_array($query_autorizacion)) {
		$id = "$field_autorizacion[CodOrganismo].$field_autorizacion[CodDependencia]";
		if ($field_autorizacion['FlagMostrar'] == "S") $FlagMostrar = "checked"; else $FlagMostrar = "";
		if ($grupo != $field_autorizacion['CodOrganismo']) {
			$grupo = $field_autorizacion['CodOrganismo'];
			?>
			<tr class="trListaBody2">
            	<td>&nbsp;</td>
				<td>
					<?=$field_autorizacion['Organismo']?>
				</td>
			</tr>
			<?
		}
		?>
		<tr class="trListaBody">
			<td align="center">
        		<input type="checkbox" name="FlagMostrar" id="FlagMostrar_<?=$id?>" value="<?=$id?>" <?=$FlagMostrar?> <?=$dMostrar?> <?=$disabled_ver?> />
            </td>
			<td onClick="selAlterna('<?=$id?>');">
				<?=$field_autorizacion['Dependencia']?>
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