<?php
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
$FechaActual = "$DiaActual-$MesActual-$AnioActual";
if ($opcion == "nuevo") {
	$field['Estado'] = "A";
	##
	$titulo = "Nuevo Registro";
	$accion = "nuevo";
	$label_submit = "Guardar";
	$disabled_nuevo = "disabled";
	$disabled_baja = "disabled";
	$disabled_trabaja = "disabled";
	$clkCancelar = "document.getElementById('frmentrada').submit();";
	$actualizarFoto = "document.getElementById('Foto').click();";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	list($CodPersona, $CodSecuencia) = split("[_]", $registro);
	//	consulto datos generales
	$sql = "SELECT *
			FROM rh_cargafamiliar
			WHERE
				CodPersona = '".$CodPersona."' AND
				CodSecuencia = '".$CodSecuencia."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Registro";
		$accion = "modificar";
		$disabled_modificar = "disabled";
		$label_submit = "Modificar";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
		if ($field['MotivoBaja'] == "") $disabled_baja = "disabled";
		if ($field['FlagTrabaja'] != "S") $disabled_trabaja = "disabled";
		$actualizarFoto = "document.getElementById('Foto').click();";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Registro";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_baja = "disabled";
		$disabled_trabaja = "disabled";
		$display_submit = "display:none;";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
	}
	
	list($EdadAnios, $EdadMeses, $EdadDias) = getEdad(formatFechaDMA($field['FechaNacimiento']), $FechaActual);
}
//	------------------------------------

if ($field['Foto'] == "") {
	$field['Foto'] = "foto_blank.png";
	$FotoAnterior = "";
} else $FotoAnterior = $field['Foto'];
$Foto = $_PARAMETRO["PATHFOTO"].$field['Foto'];

if (!file_exists($Foto)) $Foto = $_PARAMETRO["PATHFOTO"]."foto_blank.png";

?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$clkCancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<table width="800" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current"><a href="#" onclick="mostrarTab('tab', 1, 2);">Datos Generales</a></li>
            <li id="li2" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 2, 2);">Educaci&oacute;n y Ocupaci&oacute;n</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=empleados_carga_familiar_lista" method="POST" enctype="multipart/form-data" onsubmit="return empleados_carga_familiar(this, '<?=$accion?>');" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />

<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="fCodOrganismo" id="fCodOrganismo" value="<?=$fCodOrganismo?>" />
<input type="hidden" name="fCodDependencia" id="fCodDependencia" value="<?=$fCodDependencia?>" />
<input type="hidden" name="fCodCentroCosto" id="fCodCentroCosto" value="<?=$fCodCentroCosto?>" />
<input type="hidden" name="fCodTipoNom" id="fCodTipoNom" value="<?=$fCodTipoNom?>" />
<input type="hidden" name="fCodTipoTrabajador" id="fCodTipoTrabajador" value="<?=$fCodTipoTrabajador?>" />
<input type="hidden" name="fEdoReg" id="fEdoReg" value="<?=$fEdoReg?>" />
<input type="hidden" name="fSitTra" id="fSitTra" value="<?=$fSitTra?>" />
<input type="hidden" name="fFingresoD" id="fFingresoD" value="<?=$fFingresoD?>" />
<input type="hidden" name="fFingresoH" id="fFingresoH" value="<?=$fFingresoH?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />

<input type="hidden" name="fdOrderBy" id="fdOrderBy" value="<?=$fdOrderBy?>" />
<input type="hidden" name="CodPersona" id="CodPersona" value="<?=$CodPersona?>" />
<input type="hidden" id="CodSecuencia" value="<?=$CodSecuencia?>" />
<input type="hidden" id="numAleatorioFoto" value="" />
<input type="hidden" name="FlagCopiarImagen" id="FlagCopiarImagen" value="N" />

<div id="tab1" style="display:block;">
<table  width="800" class="tblForm">
	<tr>
		<td >
<table >
	<tr>
    	<td colspan="4" class="divFormCaption">Datos Personales</td>
    </tr>
    <tr>
		<td class="tagForm">* Parentesco:</td>
		<td>
			<select id="Parentesco" style="width:175px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
				<?=getMiscelaneos($field['Parentesco'], "PARENT", 0)?>
			</select>
		</td>
		<td class="tagForm">* Sexo:</td>
		<td>
			<select id="Sexo" style="width:100px;" <?=$disabled_ver?>>
				<?=loadSelectGeneral("SEXO", $field['Sexo'], 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Apellidos:</td>
		<td width="35%">
            <input type="text" id="ApellidosCarga" style="width:95%;" maxlength="50" value="<?=($field['ApellidosCarga'])?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">* Nombres:</td>
		<td width="35%">
            <input type="text" id="NombresCarga" style="width:95%;" maxlength="50" value="<?=($field['NombresCarga'])?>" <?=$disabled_ver?> />
		</td>
    </tr>
    <tr>
        <td class="tagForm">* Fecha Nacimiento:</td>
		<td>
        	<input type="text" id="FechaNacimiento" style="width:60px;" class="datepicker" maxlength="10" value="<?=formatFechaDMA($field['FechaNacimiento'])?>" onchange="getEdad($(this).val(), '<?=$FechaActual?>', $('#EdadAnios'), $('#EdadMeses'), $('#EdadDias'))" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">Edad:</td>
		<td>
        	<input type="text" id="EdadAnios" style="width:25px;" value="<?=$EdadAnios?>" disabled />a 
        	<input type="text" id="EdadMeses" style="width:25px;" value="<?=$EdadMeses?>" disabled />m 
        	<input type="text" id="EdadDias" style="width:25px;" value="<?=$EdadDias?>" disabled />d
		</td>
    </tr>
	<tr>
		<td class="tagForm">Tipo Doc.:</td>
		<td>
            <select id="TipoDocumento" style="width:175px;;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=getMiscelaneos($field['TipoDocumento'], "DOCUMENTOS", 0);?>
            </select>
		</td>
		<td class="tagForm">Nro. Doc.:</td>
		<td>
           	<input type="text" id="Ndocumento" style="width:100px;" maxlength="20" value="<?=$field['Ndocumento']?>" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Direcci&oacute;n:</td>
		<td colspan="3">
        	<textarea id="DireccionFam" style="width:98%; height:30px;" <?=$disabled_ver?>><?=($field['DireccionFam'])?></textarea>
        </td>
	</tr>
    <tr>
		<td class="tagForm">Grupo Sanguineo:</td>
		<td>
            <select id="GrupoSanguineo" style="width:175px;;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=getMiscelaneos($field['GrupoSanguineo'], "SANGRE", 0);?>
            </select>
		</td>
		<td class="tagForm">* Estado Civil:</td>
		<td>
            <select id="EstadoCivil" style="width:175px;;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=getMiscelaneos($field['EstadoCivil'], "EDOCIVIL", 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Tel&eacute;fono:</td>
		<td>
           	<input type="text" id="Telefono" style="width:100px;" maxlength="15" value="<?=$field['Telefono']?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">Celular:</td>
		<td>
           	<input type="text" id="Celular" style="width:100px;" maxlength="15" value="<?=$field['Celular']?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Motivo de Baja:</td>
		<td>
            <select id="MotivoBaja" style="width:175px;;" onchange="setMotivoBaja(this.value);" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=getMiscelaneos($field['MotivoBaja'], "MOTBA", 0);?>
            </select>
		</td>
		<td class="tagForm">Fecha de Baja:</td>
		<td>
        	<input type="text" id="FechaBaja" style="width:60px;" class="datepicker" maxlength="10" value="<?=formatFechaDMA($field['FechaBaja'])?>" <?=$disabled_baja?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
            <input type="radio" name="Estado" id="Activo" value="A" <?=chkOpt("A", $field['Estado'])?> <?=$disabled_nuevo?> /> Activo
            &nbsp; &nbsp; &nbsp; 
            <input type="radio" name="Estado" id="Inactivo" value="I" <?=chkOpt("I", $field['Estado'])?> <?=$disabled_nuevo?> /> Inactivo
		</td>
		<td class="tagForm">&nbsp;</td>
		<td>
           	<input type="checkbox" id="Afiliado" <?=chkFlag($field['Afiliado'])?> <?=$disabled_ver?> /> Afiliado al Seguro M&eacute;dico
		</td>
	</tr>
	<tr>
		<td class="tagForm" colspan="3">&nbsp;</td>
		<td>
           	<input type="checkbox" id="FlagDiscapacidad" <?=chkFlag($field['FlagDiscapacidad'])?> <?=$disabled_ver?> /> Familiar con Discapacidad o Especial
		</td>
	</tr>
    
    <tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td colspan="3">
			<input type="text" size="30" value="<?=$field['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" value="<?=$field['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
		</td>
		<td  align="left" valign="top">
			<table>
				<tr>
					<td width="100" class="divFormCaption">Foto</td>
				</tr>
				<tr>
					<td valign="middle" align="center" rowspan="5">
							<img src="<?=$Foto?>" style="max-height:100px; max-width:80px; cursor:pointer;" id="imgFoto" onclick="<?=$actualizarFoto?>" title="Actualizar Foto del Empleado" />
							<input type="file" name="Foto" id="Foto" class="ui-corner-all" style="width:200px; display:none;" <?=$disabled_ver?> />
							<a id="objLinkCargar" href="javascript:ampliarFoto('<?=$Foto?>');">Ampliar Foto</a>
				  </td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="tab2" style="display:none;">
<table width="800" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Educaci&oacute;n</td>
    </tr>
    <tr>
		<td class="tagForm" width="125">Grado Instrucci&oacute;n:</td>
		<td>
			<select id="CodGradoInstruccion" style="width:250px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
				<?=loadSelect("rh_gradoinstruccion", "CodGradoInstruccion", "Descripcion", $field['Parentesco'], 0)?>
			</select>
		</td>
		<td class="tagForm" width="125">&nbsp;</td>
		<td>
           	<input type="checkbox" id="FlagEstudia" <?=chkFlag($field['FlagEstudia'])?> <?=$disabled_ver?> /> Estudia?
		</td>
	</tr>
    <tr>
		<td class="tagForm">Centro Estudio:</td>
		<td class="gallery clearfix">
            <input type="hidden" id="CodCentroEstudio" value="<?=$field['CodCentroEstudio']?>" />
			<input type="text" id="NomCentroEstudio" style="width:350px;" value="<?=$field['NomCentroEstudio']?>" disabled="disabled" />
            <a href="../lib/listas/listado_centro_estudio.php?filtrar=default&cod=CodCentroEstudio&nom=NomCentroEstudio&FlagEstudio=S&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe1]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td class="tagForm">Tipo Educaci&oacute;n:</td>
		<td>
            <select id="TipoEducacion" style="width:100px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=getMiscelaneos($field['TipoEducacion'], "TEDUCA", 0);?>
            </select>
		</td>
	</tr>
	<tr>
    	<td colspan="4" class="divFormCaption">Ocupaci&oacute;n</td>
    </tr>
    <tr>
		<td class="tagForm">Empresa:</td>
		<td>
           	<input type="text" id="Empresa" class="trabaja" style="width:350px;" maxlength="100" value="<?=$field['Empresa']?>" <?=$disabled_trabaja?> />
		</td>
		<td class="tagForm">&nbsp;</td>
		<td>
           	<input type="checkbox" id="FlagTrabaja" <?=chkFlag($field['FlagTrabaja'])?> onclick="setTrabaja(this.checked);" <?=$disabled_ver?> /> Trabaja?
		</td>
	</tr>
    <tr>
		<td class="tagForm">Direcci&oacute;n:</td>
		<td colspan="3">
        	<textarea id="DireccionEmpresa" class="trabaja" style="width:98%; height:30px;" <?=$disabled_trabaja?>><?=htmlentities($field['DireccionEmpresa'])?></textarea>
        </td>
	</tr>
	<tr>
		<td class="tagForm">Tiempo Servicio:</td>
		<td>
           	<input type="text" id="TiempoServicio" class="trabaja" style="width:50px;" maxlength="2" value="<?=$field['TiempoServicio']?>" <?=$disabled_trabaja?> />
		</td>
		<td class="tagForm">Sueldo Mensual:</td>
		<td>
           	<input type="text" id="SueldoMensual" class="trabaja" style="width:100px; text-align:right" maxlength="15" value="<?=number_format($field['SueldoMensual'], 2, ',', '.')?>" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" <?=$disabled_trabaja?> />
		</td>
	</tr>
	<tr>
    	<td colspan="4" class="divFormCaption">Comentarios</td>
    </tr>
    <tr>
		<td colspan="4" align="center">
        	<textarea id="Comentarios" style="width:99%; height:30px;" <?=$disabled_ver?>><?=htmlentities($field['Comentarios'])?></textarea>
        </td>
	</tr>
</table>
</div>

<center>
<input type="submit" value="<?=$label_submit?>" style=" <?=$display_submit?>" />
<input type="button" value="Cancelar" onclick="<?=$clkCancelar?>" />
</center>

</form>

<div style="width:800px" class="divMsj">Campos Obligatorios *</div>

<script language="javascript">
var upload_input = document.querySelectorAll('#Foto')[0];

upload_input.onchange = function(){
	uploadFile( this.files[0] );
};
</script>
