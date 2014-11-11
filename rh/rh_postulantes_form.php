<?php
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
$FechaActual = "$DiaActual-$MesActual-$AnioActual";
if ($opcion == "nuevo") {
	$field['Estado'] = "P";
	$field['CodPaisNac'] = $_PARAMETRO["PAISDEFAULT"];
	$field['CodEstadoNac'] = $_PARAMETRO["ESTADODEFAULT"];
	$field['CodMunicipioNac'] = $_PARAMETRO["MUNICIPIODEFAULT"];
	$field['CodCiudadNac'] = $_PARAMETRO["CIUDADDEFAULT"];
	$field['CodPaisDom'] = $_PARAMETRO["PAISDEFAULT"];
	$field['CodEstadoDom'] = $_PARAMETRO["ESTADODEFAULT"];
	$field['CodMunicipioDom'] = $_PARAMETRO["MUNICIPIODEFAULT"];
	$field['CodCiudadDom'] = $_PARAMETRO["CIUDADDEFAULT"];
	$accion = "nuevo";
	$titulo = "Nuevo Postulante";
	$label_submit = "Guardar";
	$disabled_nuevo = "disabled";
	$display_tabs = "display:none;";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	//	consulto datos generales
	$sql = "SELECT
				p.*,
				c1.CodMunicipio AS CodMunicipioNac,
				m1.CodEstado AS CodEstadoNac,
				e1.CodPais AS CodPaisNac,
				c2.CodMunicipio AS CodMunicipioDom,
				m2.CodEstado AS CodEstadoDom,
				e2.CodPais AS CodPaisDom
			FROM
				rh_postulantes p
				INNER JOIN mastciudades c1 ON (c1.Codciudad = p.CiudadNacimiento)
				INNER JOIN mastmunicipios m1 ON (m1.CodMunicipio = c1.CodMunicipio)
				INNER JOIN mastestados e1 ON (e1.CodEstado = m1.CodEstado)
				
				INNER JOIN mastciudades c2 ON (c2.Codciudad = p.CiudadDomicilio)
				INNER JOIN mastmunicipios m2 ON (m2.CodMunicipio = c2.CodMunicipio)
				INNER JOIN mastestados e2 ON (e2.CodEstado = m2.CodEstado)
			WHERE p.Postulante = '".$registro."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Postulante";
		$accion = "modificar";
		$label_submit = "Modificar";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
		$visible_modificar = "visibility:hidden;";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Postulante";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$display_modificar = "display:none;";
		$display_submit = "display:none;";
		$visible_modificar = "visibility:hidden;";
	}
	
	list($Anios, $Meses, $Dias) = getEdad(formatFechaDMA($field['Fnacimiento']), $FechaActual);
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="document.getElementById('frmentrada').submit()">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<table width="900" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current">
            	<a href="#" onclick="mostrarTab('tab', 1, 8);">Datos Personales</a>
            </li>
            <li id="li2" onclick="currentTab('tab', this);">
            	<a href="#" onclick="mostrarTab('tab', 2, 8);">Otros Datos</a>
            </li>
            <li id="li3" onclick="currentTab('tab', this);" style=" <?=$display_tabs?>">
            	<a href="#" onclick="mostrarTab('tab', 3, 8);">Instrucci&oacute;n</a>
            </li>
            <li id="li4" onclick="currentTab('tab', this);" style=" <?=$display_tabs?>">
            	<a href="#" onclick="mostrarTab('tab', 4, 8);">Cursos</a>
            </li>
            <li id="li5" onclick="currentTab('tab', this);" style=" <?=$display_tabs?>">
            	<a href="#" onclick="mostrarTab('tab', 5, 8);">Experiencia Laboral</a>
            </li>
            <li id="li6" onclick="currentTab('tab', this);" style=" <?=$display_tabs?>">
            	<a href="#" onclick="mostrarTab('tab', 6, 8);">Referencias</a>
            </li>
            <li id="li7" onclick="currentTab('tab', this);" style=" <?=$display_tabs?>">
            	<a href="#" onclick="mostrarTab('tab', 7, 8);">Documentos</a>
            </li>
            <li id="li8" onclick="currentTab('tab', this);" style=" <?=$display_tabs?>">
            	<a href="#" onclick="mostrarTab('tab', 8, 8);">Cargos Aplicables</a>
            </li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_postulantes_lista" method="POST" onsubmit="return postulantes(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" id="Anio" value="<?=$AnioActual?>" />

<div id="tab1" style="display:block;">
<table width="900" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Datos Generales</td>
    </tr>
    <tr>
		<td class="tagForm">Postulante:</td>
		<td width="37%">
            <input type="text" id="Postulante" style="width:75px;" class="codigo" value="<?=$field['Postulante']?>" disabled="disabled" />
		</td>
		<td class="tagForm">Estado:</td>
		<td width="37%">
            <input type="hidden" id="Estado" value="<?=$field['Estado']?>" />
            <input type="text" style="width:100px;" class="codigo" value="<?=strtoupper(printValores("ESTADO-POSTULANTE", $field['Estado']))?>" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Apellido Paterno:</td>
		<td>
			<input type="text" id="Apellido1" style="width:95%;" maxlength="25" value="<?=$field['Apellido1']?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">* Materno:</td>
		<td>
			<input type="text" id="Apellido2" style="width:95%;" maxlength="25" value="<?=$field['Apellido2']?>" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Nombres:</td>
		<td>
			<input type="text" id="Nombres" style="width:95%;" maxlength="50" value="<?=$field['Nombres']?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">* Sexo:</td>
		<td>
			<select id="Sexo" style="width:100px;" <?=$disabled_ver?>>
				<?=loadSelectGeneral("SEXO", $field['Sexo'], 0)?>
			</select>
		</td>
	</tr>
	<tr>
    	<td colspan="4" class="divFormCaption">Resumen Ejecutivo</td>
    </tr>
    <tr>
		<td colspan="4" align="center">
			<textarea id="ResumenEjec" style="width:99%; height:75px;" <?=$disabled_ver?>><?=$field['ResumenEjec']?></textarea>
		</td>
	</tr>
	<tr>
    	<td colspan="4" class="divFormCaption">Lugar y Fecha de Nacimiento</td>
    </tr>
	<tr>
		<td class="tagForm">* Pais:</td>
		<td>
            <select id="CodPais" style="width:200px;" onchange="getOptionsSelect(this.value, 'estado', 'CodEstadoNac', true, 'CodMunicipioNac', 'CiudadNacimiento');" <?=$disabled_ver?>>
                <?=loadSelect("mastpaises", "CodPais", "Pais", $field['CodPaisNac'], 0);?>
            </select>
		</td>
		<td class="tagForm">* Estado:</td>
		<td>
            <select id="CodEstado" style="width:200px;" onchange="getOptionsSelect(this.value, 'municipio', 'CodMunicipio', true, 'CiudadNacimiento');" <?=$disabled_ver?>>
                <?=loadSelectDependienteEstado($field['CodEstadoNac'], $field['CodPaisNac'], 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Municipio:</td>
		<td>
            <select id="CodMunicipio" style="width:200px;" onchange="getOptionsSelect(this.value, 'ciudad', 'CiudadNacimiento', true);" <?=$disabled_ver?>>
                <?=loadSelectDependiente("mastmunicipios", "CodMunicipio", "Municipio", "CodEstado", $field['CodMunicipioNac'], $field['CodEstadoNac'], 0);?>
            </select>
		</td>
		<td class="tagForm">* Ciudad:</td>
		<td>
            <select id="CiudadNacimiento" style="width:200px;" <?=$disabled_ver?>>
                <?=loadSelectDependiente("mastciudades", "CodCiudad", "Ciudad", "CodMunicipio", $field['CiudadNacimiento'], $field['CodMunicipioNac'], 0);?>
            </select>
		</td>
	</tr>
    <tr>
        <td class="tagForm">* Fecha:</td>
		<td colspan="2">
        	<input type="text" id="Fnacimiento" value="<?=formatFechaDMA($field['Fnacimiento'])?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" onchange="getEdad(this.value, '<?=$FechaActual?>', $('#Anios'), $('#Meses'), $('#Dias'));" <?=$disabled_ver?> />
        </td>
        <td>
        	<input type="text" id="Anios" style="width:25px;" class="disabled" value="<?=$Anios?>" disabled="disabled" /> a &nbsp; 
        	<input type="text" id="Meses" style="width:25px;" class="disabled" value="<?=$Meses?>" disabled="disabled" /> m &nbsp; 
        	<input type="text" id="Dias" style="width:25px;" class="disabled" value="<?=$Dias?>" disabled="disabled" /> d &nbsp; 
        </td>
    </tr>
	<tr>
    	<td colspan="4" class="divFormCaption">Domicilio Actual</td>
    </tr>
    <tr>
		<td class="tagForm">* Direcci&oacute;n:</td>
		<td colspan="3">
			<textarea id="Direccion" style="width:99%; height:30px;" <?=$disabled_ver?>><?=$field['Direccion']?></textarea>
		</td>
	</tr>
    <tr>
		<td class="tagForm">Referencia:</td>
		<td colspan="3">
			<textarea id="Referencia" style="width:99%; height:30px;" <?=$disabled_ver?>><?=$field['Referencia']?></textarea>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Pais:</td>
		<td>
            <select id="CodPais" style="width:200px;" onchange="getOptionsSelect(this.value, 'estado', 'CodEstadoDom', true, 'CodMunicipioDom', 'CiudadDomicilio');" <?=$disabled_ver?>>
                <?=loadSelect("mastpaises", "CodPais", "Pais", $field['CodPaisDom'], 0);?>
            </select>
		</td>
		<td class="tagForm">* Estado:</td>
		<td>
            <select id="CodEstadoDom" style="width:200px;" onchange="getOptionsSelect(this.value, 'municipio', 'CodMunicipioDom', true, 'CiudadDomicilio');" <?=$disabled_ver?>>
                <?=loadSelectDependienteEstado($field['CodEstadoDom'], $field['CodPaisDom'], 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Municipio:</td>
		<td>
            <select id="CodMunicipioDom" style="width:200px;" onchange="getOptionsSelect(this.value, 'ciudad', 'CiudadDomicilio', true);" <?=$disabled_ver?>>
                <?=loadSelectDependiente("mastmunicipios", "CodMunicipio", "Municipio", "CodEstado", $field['CodMunicipioDom'], $field['CodEstadoDom'], 0);?>
            </select>
		</td>
		<td class="tagForm">* Ciudad:</td>
		<td>
            <select id="CiudadDomicilio" style="width:200px;" <?=$disabled_ver?>>
                <?=loadSelectDependiente("mastciudades", "CodCiudad", "Ciudad", "CodMunicipio", $field['CiudadDomicilio'], $field['CodMunicipioDom'], 0);?>
            </select>
		</td>
	</tr>
    <tr>
		<td class="tagForm">Tel&eacute;fono:</td>
		<td>
			<input type="text" id="Telefono1" style="width:200px;" maxlength="15" value="<?=$field['Telefono1']?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">E-mail:</td>
		<td>
			<input type="text" id="Email" style="width:95%;" maxlength="255" value="<?=$field['Email']?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
    	<td colspan="4" class="divFormCaption">Documentos de Identificaci&oacute;n</td>
    </tr>
    <tr>
		<td class="tagForm">* Documento:</td>
		<td>
            <select id="TipoDocumento" style="width:200px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=getMiscelaneos($field['TipoDocumento'], "DOCUMENTOS", 0);?>
            </select>
		</td>
		<td class="tagForm">* Nro. Documento:</td>
		<td>
			<input type="text" id="Ndocumento" style="width:200px;" maxlength="20" value="<?=$field['Ndocumento']?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td colspan="3">
			<input type="text" size="30" class="disabled" value="<?=$field_obligacion['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" class="disabled" value="<?=$field_obligacion['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center>
<input type="submit" value="<?=$label_submit?>" style=" <?=$display_submit?>" />
<input type="button" value="Cancelar" onclick="this.form.submit();" />
</center>
</div>

<div id="tab2" style="display:none;">
<table width="900" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Otros Datos Personales</td>
    </tr>
    <tr>
		<td class="tagForm">Grupo Sangu&iacute;neo:</td>
		<td width="37%">
            <select id="GrupoSanguineo" style="width:200px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=getMiscelaneos($field['GrupoSanguineo'], "SANGRE", 0);?>
            </select>
		</td>
		<td class="tagForm">Situaci&oacute;n Domicilio:</td>
		<td width="37%">
            <select id="SituacionDomicilio" style="width:200px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=getMiscelaneos($field['SituacionDomicilio'], "SITDOM", 0);?>
            </select>
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Edo. Civil:</td>
		<td>
            <select id="EstadoCivil" style="width:200px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=getMiscelaneos($field['EstadoCivil'], "EDOCIVIL", 0);?>
            </select>
		</td>
        <td class="tagForm">Fecha:</td>
		<td>
        	<input type="text" id="FedoCivil" value="<?=formatFechaDMA($field['FedoCivil'])?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> />
        </td>
	</tr>
	<tr>
    	<td colspan="4" class="divFormCaption">Informaci&oacute;n Adicional</td>
    </tr>
    <tr>
		<td colspan="4" align="center">
			<textarea id="InformacionAdic" style="width:99%; height:35px;" <?=$disabled_ver?>><?=$field['InformacionAdic']?></textarea>
		</td>
	</tr>
	<tr>
    	<td colspan="4" class="divFormCaption">Actividades Extralaborales</td>
    </tr>
    <tr>
        <td class="tagForm">Beneficas:</td>
		<td align="center">
			<textarea id="Beneficas" style="width:98%; height:40px;" <?=$disabled_ver?>><?=$field['Beneficas']?></textarea>
		</td>
        <td class="tagForm">Laborales:</td>
		<td align="center">
			<textarea id="Laborales" style="width:98%; height:40px;" <?=$disabled_ver?>><?=$field['Laborales']?></textarea>
		</td>
	</tr>
    <tr>
        <td class="tagForm">Culturales:</td>
		<td align="center">
			<textarea id="Culturales" style="width:98%; height:40px;" <?=$disabled_ver?>><?=$field['Culturales']?></textarea>
		</td>
        <td class="tagForm">Deportivas:</td>
		<td align="center">
			<textarea id="Deportivas" style="width:98%; height:40px;" <?=$disabled_ver?>><?=$field['Deportivas']?></textarea>
		</td>
	</tr>
    <tr>
        <td class="tagForm">Religiosas:</td>
		<td align="center">
			<textarea id="Religiosas" style="width:98%; height:40px;" <?=$disabled_ver?>><?=$field['Religiosas']?></textarea>
		</td>
        <td class="tagForm">Sociales:</td>
		<td align="center">
			<textarea id="Sociales" style="width:98%; height:40px;" <?=$disabled_ver?>><?=$field['Sociales']?></textarea>
		</td>
	</tr>
</table>
</div>
</form>

<div id="tab3" style="display:none;">
<table align="center" width="900" cellpadding="0" cellspacing="0">
	<tr>
    	<td colspan="2">
        	<iframe name="instruccion" id="instruccion" src="gehen.php?anz=rh_postulantes_instruccion_lista&Postulante=<?=$registro?>" style="border:solid 1px #CDCDCD; width:100%; height:260px;"></iframe>
        </td>
    </tr>
	<tr>
    	<td width="50%">
        	<iframe src="gehen.php?anz=rh_postulantes_idiomas_lista&Postulante=<?=$registro?>" style="border:solid 1px #CDCDCD; width:100%; height:200px;"></iframe>
        </td>
    	<td width="50%">
        	<iframe src="gehen.php?anz=rh_postulantes_informatica_lista&Postulante=<?=$registro?>" style="border:solid 1px #CDCDCD; width:100%; height:200px;"></iframe>
        </td>
    </tr>
</table>
</div>

<div id="tab4" style="display:none;">
<table align="center" width="900" cellpadding="0" cellspacing="0">
	<tr>
    	<td>
        	<iframe name="cursos" id="cursos" src="gehen.php?anz=rh_postulantes_cursos_lista&Postulante=<?=$registro?>" style="border:solid 1px #CDCDCD; width:100%; height:461px;"></iframe>
        </td>
    </tr>
</table>
</div>

<div id="tab5" style="display:none;">
<table align="center" width="900" cellpadding="0" cellspacing="0">
	<tr>
    	<td>
        	<iframe src="gehen.php?anz=rh_postulantes_experiencias_lista&Postulante=<?=$registro?>" style="border:solid 1px #CDCDCD; width:100%; height:260px;"></iframe>
        </td>
    </tr>
	<tr>
    	<td>
        	<iframe src="gehen.php?anz=rh_postulantes_referencias_lista&Postulante=<?=$registro?>" style="border:solid 1px #CDCDCD; width:100%; height:200px;"></iframe>
        </td>
    </tr>
</table>
</div>

<div id="tab6" style="display:none;">
<table align="center" width="900" cellpadding="0" cellspacing="0">
	<tr>
    	<td>
        	<iframe src="gehen.php?anz=rh_postulantes_referencias_personales_lista&Postulante=<?=$registro?>" style="border:solid 1px #CDCDCD; width:100%; height:461px;"></iframe>
        </td>
    </tr>
</table>
</div>

<div id="tab7" style="display:none;">
<table align="center" width="900" cellpadding="0" cellspacing="0">
	<tr>
    	<td>
        	<iframe src="gehen.php?anz=rh_postulantes_documentos_lista&Postulante=<?=$registro?>" style="border:solid 1px #CDCDCD; width:100%; height:461px;"></iframe>
        </td>
    </tr>
</table>
</div>

<div id="tab8" style="display:none;">
<table align="center" width="900" cellpadding="0" cellspacing="0">
	<tr>
    	<td>
        	<iframe name="cargos" id="cargos" src="gehen.php?anz=rh_postulantes_cargos_lista&Postulante=<?=$registro?>" style="border:solid 1px #CDCDCD; width:100%; height:461px;"></iframe>
        </td>
    </tr>
</table>
</div>

<br />
<div style="width:900px" class="divMsj">Campos Obligatorios *</div>

<div class="gallery clearfix">
<a href="../lib/listas/listado_centro_estudio.php?filtrar=default&cod=CodCentroEstudio&nom=NomCentroEstudio&FlagEstudio=S&FlagCurso=N&marco=instruccion&ventana=selListadoIFrame&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe1]" id="btCentro1" style=" display:none;">
    <img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
</a>
<a href="../lib/listas/listado_centro_estudio.php?filtrar=default&cod=CodCentroEstudio&nom=NomCentroEstudio&FlagEstudio=S&FlagCurso=N&marco=cursos&ventana=selListadoIFrame&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe2]" id="btCentro2" style=" display:none;">
    <img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
</a>
<a href="../lib/listas/listado_cursos.php?filtrar=default&cod=CodCurso&nom=NomCurso&marco=cursos&ventana=selListadoIFrame&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe3]" id="btCurso1" style=" display:none;">
    <img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
</a>
<a href="../lib/listas/listado_cargos.php?filtrar=default&cod=CodCargo&nom=DescripCargo&marco=cargos&ventana=selListadoIFrame&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe4]" id="btCargo1" style=" display:none;">
    <img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
</a>
</div>