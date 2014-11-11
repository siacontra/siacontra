<?php
		/* Pagina realizada para que cada usuario pueda hacer su cambio de clave - 
		 * 	Guidmar Espinoza
		 * 	Fecha:  28-01-2014 */

if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Nuevo Usuario";
	$cancelar = "document.getElementById('framemain.php').submit();";
	$flagactivo = "checked";
	$disabled_fechae = "disabled";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	//	consulto datos generales	
	$sql = "SELECT
				u.*,
				p.NomCompleto AS NomEmpleado,
				e.CodEmpleado
			FROM
				usuarios u
				INNER JOIN mastpersonas p ON (u.CodPersona = p.CodPersona)
				INNER JOIN mastempleado e ON (p.CodPersona = e.CodPersona)
			WHERE u.Usuario = '".$_SESSION["USUARIO_ACTUAL"]."'";
			
	$query_form = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_form)) $field_form = mysql_fetch_array($query_form);
	
	if ($opcion == "modificar") {
		$accion = "modificar";
		$titulo = "Cambio de Clave";
		$cancelar = "document.getElementById('framemain.php').submit();";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
	}
	
	elseif ($opcion == "ver") {
		$disabled_ver = "disabled";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
		$titulo = "Ver Usuario";
		$cancelar = "window.close();";
		$display_submit = "display:none;";
	}
	
	if ($field_form['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked";
	if ($field_form['FlagFechaExpirar'] == "S") $FlagFechaExpirar = "checked"; else $disabled_fechae = "disabled";
	
	
	
}
//	------------------------------------


//-------------------------------------

?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=framemain" method="POST" onsubmit="return usuarios(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fedoreg" value="<?=$fedoreg?>" />
<input type="hidden" name="fordenar" value="<?=$fordenar?>" />
<input type="hidden" name="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="fbuscar" value="<?=$fbuscar?>" />
<input type="hidden" name="lista" value="<?=$lista?>" />

	

<table width="700" class="tblForm">
	<tr>
		<td class="tagForm">* Empleado:</td>
		<td class="gallery clearfix">
            <input type="hidden" id="CodPersona" value="<?=$field_form['CodPersona']?>" />
            <input type="text" id="CodEmpleado" style="width:50px;" value="<?=$field_form['CodEmpleado']?>" disabled />
			<input type="text" id="NomEmpleado" style="width:300px;" value="<?=($field_form['NomEmpleado'])?>" disabled="disabled" />
            <a href="../lib/listas/listado_empleados.php?filtrar=default&cod=CodEmpleado&nom=NomEmpleado&campo3=CodPersona&iframe=true&width=950&height=525" rel="prettyPhoto[iframe]" style=" <?=$display_modificar?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Usuario:</td>
		<td>
        	<input type="text" id="Usuario" value="<?=$field_form['Usuario']?>" style="width:200px; font-weight:bold; font-size:12px;" maxlength="20" <?=$disabled_modificar?> />
		</td>
	</tr>
	<tr>
		<!-- Funcion JS para confirmar la clave actual antes de realizar el cambio de clave - 
			Guidmar Espinoza
			Fecha: 30-01-2014 -->
		<script type="text/javascript">
			function confirmaclave(){
				if (document.getElementById('ClaveAc').value==document.getElementById('ClaveAc1').value)
				{ 
					document.getElementById('Clave').disabled=false;
					document.getElementById('Confirmar').disabled=false;
				}
				else{
					alert('Clave incorrecta');
					frmentrada.submit('gehen.php?anz=framemain');
				}
			}
		</script>
		<!-- JS	-->
		<td class="tagForm">* Ingrese su Contraseña Actual:</td>
		<td>
        	<input type="password" id="ClaveAc" style="width:200px;" maxlength="20" value="" onBlur="confirmaclave()" />
        	<input type="password" id="ClaveAc1" style="width:200px;" maxlength="20" value="<?=($field_form['Clave'])?>"  hidden />
		
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Nueva Contraseña:</td>
		<td>
        	<input type="password" id="Clave" style="width:200px;" maxlength="20" value="" disabled  />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Confirmar:</td>
		<td>
        	<input type="password" id="Confirmar" style="width:200px;" maxlength="20" value="" disabled />
		</td>
	</tr>
	<tr>
		<td class="tagForm">&nbsp;</td>
		<td>
        	<input type="checkbox" id="FlagFechaExpirar" value="S" onclick="chkFiltro(this.checked, 'FechaExpirar');" <?=$FlagFechaExpirar?> <?=$disabled_ver?> /> Forzar Expiraci&oacute;n de la Contrase&ntilde;a
		</td>
	</tr>
	<tr>
		<td class="tagForm">Fecha Expiraci&oacute;n</td>
		<td>
        	<input type="text" id="FechaExpirar" value="<?=formatFechaDMA($field_form['FechaExpirar'])?>" style="width:75px;" maxlength="10" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> <?=$disabled_fechae?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
            <input type="radio" name="Estado" id="activo" value="A" <?=$flagactivo?> <?=$disabled_ver?> /> Activo
            <input type="radio" name="Estado" id="inactivo" value="I" <?=$flaginactivo?> <?=$disabled_ver?> /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input type="text" size="30" value="<?=$field_form['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" value="<?=$field_form['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center>
<input type="submit" value="Guardar" style="width:80px; <?=$display_submit?>" />
<input type="button" value="Cancelar" style="width:80px;" onclick="submit('gehen.php?anz=framemain')" />
</center>
<br />
<div style="width:700px; <?=$display_submit?>" class="divMsj">(*) Campos Obligatorios</div>
</form>

<!-- JS	-->
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	$("#NomPersona").focus();
});
</script>
