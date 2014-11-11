<?php
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
$FechaActual = "$DiaActual-$MesActual-$AnioActual";
if ($opcion == "nuevo") {
	$field['EdoReg'] = "A";
	$field['SitTra'] = "A";
	$field['CodOrganismo'] = $_SESSION["ORGANISMO_ACTUAL"];
	$field['CodDependencia'] = $_SESSION["DEPENDENCIA_ACTUAL"];
	$field['CodCentroCosto'] = $_SESSION["CCOSTO_ACTUAL"];
	$field['CodPaisNac'] = $_PARAMETRO["PAISDEFAULT"];
	$field['CodEstadoNac'] = $_PARAMETRO["ESTADODEFAULT"];
	$field['CodMunicipioNac'] = $_PARAMETRO["MUNICIPIODEFAULT"];
	$field['CiudadNacimiento'] = $_PARAMETRO["CIUDADDEFAULT"];
	$field['CodPaisDom'] = $_PARAMETRO["PAISDEFAULT"];
	$field['CodEstadoDom'] = $_PARAMETRO["ESTADODEFAULT"];
	$field['CodMunicipioDom'] = $_PARAMETRO["MUNICIPIODEFAULT"];
//	$field['CiudadDomicilio'] = $_PARAMETRO["CIUDADDEFAULT"];
	$field['CodCiudadDom'] = $_PARAMETRO["CIUDADDEFAULT"];
	//	lugar de nacimiento por defecto
	$sql = "SELECT CONCAT(c.Ciudad, ' ESTADO ', e.Estado) AS Lnacimiento
			FROM
				mastciudades c
				INNER JOIN mastmunicipios m ON (m.CodMunicipio = c.CodMunicipio)
				INNER JOIN mastestados e ON (e.CodEstado = m.CodEstado)
			WHERE c.CodCiudad = '".$_PARAMETRO['CIUDADDEFAULT']."'";
	$query_lnac = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_lnac)) $field_lnac = mysql_fetch_array($query_lnac);
	$field['Lnacimiento'] = $field_lnac['Lnacimiento'];
	##
	$titulo = "Nuevo Empleado";
	$accion = "nuevo";
	$label_submit = "Guardar";
	$disabled_nuevo = "disabled";
	$disabled_cese = "disabled";
	$clkCancelar = "document.getElementById('frmentrada').submit();";
	$actualizarFoto = "document.getElementById('Foto').click();";
}
elseif ($opcion == "modificar" || $opcion == "ver" || $opcion == "contratar") {
	//	consulto datos generales
	if ($opcion == "contratar") {
		$sql = "SELECT
					pt.Apellido1,
					pt.Apellido2,
					pt.Nombres,
					pt.ResumenEjec,
					pt.CiudadNacimiento,
					pt.CiudadDomicilio,
					pt.Fnacimiento,
					pt.Sexo,
					CONCAT(pt.Direccion, ' ', pt.Referencia) AS Direccion,
					pt.Email,
					pt.Telefono1,
					pt.TipoDocumento,
					pt.Ndocumento,
					pt.EstadoCivil,
					pt.FedoCivil,
					pt.GrupoSanguineo,
					pt.SituacionDomicilio,
					pt.InformacionAdic AS Observacion,
					c1.CodMunicipio AS CodMunicipioNac,
					m1.CodEstado AS CodEstadoNac,
					e1.CodPais AS CodPaisNac,
					c2.CodMunicipio AS CodMunicipioDom,
					m2.CodEstado AS CodEstadoDom,
					e2.CodPais AS CodPaisDom
				FROM
					rh_postulantes pt
					INNER JOIN mastciudades c1 ON (c1.CodCiudad = pt.CiudadNacimiento)
					INNER JOIN mastmunicipios m1 ON (m1.CodMunicipio = c1.CodMunicipio)
					INNER JOIN mastestados e1 ON (e1.CodEstado = m1.CodEstado)
					INNER JOIN mastciudades c2 ON (c2.CodCiudad = pt.CiudadDomicilio)
					INNER JOIN mastmunicipios m2 ON (m2.CodMunicipio = c2.CodMunicipio)
					INNER JOIN mastestados e2 ON (e2.CodEstado = m2.CodEstado)
				WHERE pt.Postulante = '".$Postulante."'";
	} else {	
		$sql = "SELECT
					p.CodPersona,
					p.Apellido1,
					p.Apellido2,
					p.Nombres,
					p.NomCompleto,
					p.Busqueda,
					p.Sexo,
					p.Nacionalidad,
					p.Fnacimiento,
					p.CiudadNacimiento,
					p.Lnacimiento,
					p.EstadoCivil,
					p.FedoCivil,
					p.Direccion,
					p.Telefono1,
					p.Telefono2,
					p.Fax,
					p.CiudadDomicilio,
					p.TipoDocumento,
					p.Ndocumento,
					p.DocFiscal,
					p.TipoPersona,
					p.NomEmerg1,
					p.DirecEmerg1,
					p.TelefEmerg1,
					p.CelEmerg1,
					p.ParentEmerg1,
					p.NomEmerg2,
					p.DirecEmerg2,
					p.TelefEmerg2,
					p.CelEmerg2,
					p.ParentEmerg2,
					p.EsProveedor,
					p.EsCliente,
					p.EsEmpleado,
					p.EsOtros,
					p.SituacionDomicilio,
					p.Email,
					p.Foto,
					p.GrupoSanguineo,
					p.Observacion,
					p.TipoLicencia,
					p.Nlicencia,
					p.ExpiraLicencia,
					p.SiAuto,
					p.Estado AS EdoReg,
					
					e1.CodPais AS CodPaisNac,
					m1.CodEstado AS CodEstadoNac,
					c1.CodMunicipio AS CodMunicipioNac,
					
					e2.CodPais AS CodPaisDom,
					m2.CodEstado AS CodEstadoDom,
					c2.CodMunicipio AS CodMunicipioDom,
					
					e.CodEmpleado,
					e.CodPersona,
					e.CodTipoTrabajador,
					e.CodOrganismo,
					e.CodDependencia,
					e.CodCentroCosto,
					e.CodTipoNom,
					e.CodPerfil,
					e.CodCargo,
					e.CodTipoPago,
					e.Fingreso,
					e.SueldoActual,
					e.Observacion,
					e.CodMotivoCes,
					e.ObsCese,
					e.Fegreso,
					e.CodCarnetProv,
					e.Estado AS SitTra,
					e.CodHorario,
					pt.CodGrupOcup,
					pt.CodSerieOcup,
					md.Descripcion AS NomCategoriaCargo,
					pt.NivelSalarial AS SueldoActual
				FROM
					mastpersonas p
					INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
					
					INNER JOIN mastciudades c1 ON (c1.CodCiudad = p.CiudadNacimiento)
					INNER JOIN mastmunicipios m1 ON (m1.CodMunicipio = c1.CodMunicipio)
					INNER JOIN mastestados e1 ON (e1.CodEstado = m1.CodEstado)
					
					INNER JOIN mastciudades c2 ON (c2.CodCiudad = p.CiudadDomicilio)
					INNER JOIN mastmunicipios m2 ON (m2.CodMunicipio = c2.CodMunicipio)
					INNER JOIN mastestados e2 ON (e2.CodEstado = m2.CodEstado)
					
					INNER JOIN rh_puestos pt ON (pt.CodCargo = e.CodCargo)
					LEFT JOIN mastmiscelaneosdet md ON (md.CodDetalle = pt.CategoriaCargo AND
														md.CodMaestro = 'CATCARGO' AND
														md.CodAplicacion = 'RH')
				WHERE p.CodPersona = '".$registro."'";
	}

	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Empleado";
		$accion = "modificar";
		$disabled_modificar = "disabled";
		if ($field['SitTra'] == "A") $disabled_cese = "disabled";
		$label_submit = "Modificar";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
		$actualizarFoto = "document.getElementById('Foto').click();";
	}
	
	elseif ($opcion == "contratar") {
		//	consulto cargo del requerimiento
		$sql = "SELECT
					r.CodCargo,
					r.FechaDesde AS Fingreso,
					r.CodOrganismo,
					r.CodDependencia,
					pt.NivelSalarial AS SueldoActual,
					pt.CodGrupOcup,
					pt.CodSerieOcup,
					md.Descripcion AS CategoriaCargo
				FROM
					rh_requerimiento r
					INNER JOIN rh_puestos pt ON (pt.CodCargo = r.CodCargo)
					LEFT JOIN mastmiscelaneosdet md ON (md.CodDetalle = pt.CategoriaCargo AND
													 	md.CodMaestro = 'CATCARGO')
				WHERE
					r.CodOrganismo = '".$CodOrganismoReq."' AND
					r.Requerimiento = '".$Requerimiento."'";
		$query_req = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_req)) $field_req = mysql_fetch_array($query_req);
		//	-------------------
		$field['EdoReg'] = "A";
		$field['SitTra'] = "A";
		if ($field['Apellido1'] != "") $field['NomCompleto'] = $field['Nombres']." ".$field['Apellido1']." ".$field['Apellido2'];
		else $field['NomCompleto'] = $field['Nombres']." ".$field['Apellido2'];
		$field['Busqueda'] = $field['NomCompleto'];
		$field['CodOrganismo'] = $field_req['CodOrganismo'];
		$field['CodDependencia'] = $field_req['CodDependencia'];
		$field['Fingreso'] = $field_req['Fingreso'];
		$field['CodGrupOcup'] = $field_req['CodGrupOcup'];
		$field['CodSerieOcup'] = $field_req['CodSerieOcup'];
		$field['CodCargo'] = $field_req['CodCargo'];
		$field['CategoriaCargo'] = $field_req['CategoriaCargo'];
		$field['SueldoActual'] = $field_req['SueldoActual'];
		$field['Lnacimiento'] = setLugar($field['CiudadNacimiento']);
		$titulo = "Contratar Cantidato";
		$accion = "nuevo";
		$disabled_nuevo = "disabled";
		$disabled_cese = "disabled";
		$label_submit = "Contratar";
		$clkCancelar = "parent.$.prettyPhoto.close();";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Empleado";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_cese = "disabled";
		$display_modificar = "display:none;";
		$display_ver = "display:none;";
		$display_submit = "display:none;";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
	}
	
	list($EdadAnios, $EdadMeses, $EdadDias) = getEdad(formatFechaDMA($field['Fnacimiento']), "$DiaActual-$MesActual-$AnioActual");
}
if ($field['Foto'] == "") {
	$field['Foto'] = "foto_blank.png";
	$FotoAnterior = "";
} else $FotoAnterior = $field['Foto'];
$Foto = $_PARAMETRO["PATHFOTO"].$field['Foto'];
if (!file_exists($Foto)) $Foto = $_PARAMETRO["PATHFOTO"]."foto_blank.png";
//	------------------------------------
?>
<?php
if ($opcion != "contratar") {
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="$('#frmentrada').attr('action', 'gehen.php?anz=empleados_lista').attr('target', ''); <?=$clkCancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />
<?
}
?>

<table width="750" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current"><a href="#" onclick="mostrarTab('tab', 1, 4);">Datos Personales</a></li>
            <li id="li2" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 2, 4);">Datos Personales...</a></li>
            <li id="li3" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 3, 4);">Organizaci&oacute;n</a></li>
            <li id="li4" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 4, 4);">Datos Laborales</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=empleados_lista" method="POST" enctype="multipart/form-data" onsubmit="return empleados(this, '<?=$accion?>');" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="fCodOrganismo" id="fCodOrganismo" value="<?=$fCodOrganismo?>" />
<input type="hidden" name="fCodDependencia" id="fCodDependencia" value="<?=$fCodDependencia?>" />
<input type="hidden" name="fEdoReg" id="fEdoReg" value="<?=$fEdoReg?>" />
<input type="hidden" name="fSitTra" id="fSitTra" value="<?=$fSitTra?>" />
<input type="hidden" name="fCodCentroCosto" id="fCodCentroCosto" value="<?=$fCodCentroCosto?>" />
<input type="hidden" name="fFingresoD" id="fFingresoD" value="<?=$fFingresoD?>" />
<input type="hidden" name="fFingresoH" id="fFingresoH" value="<?=$fFingresoH?>" />
<input type="hidden" name="fCodTipoNom" id="fCodTipoNom" value="<?=$fCodTipoNom?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fCodTipoTrabajador" id="fCodTipoTrabajador" value="<?=$fCodTipoTrabajador?>" />
<input type="hidden" id="CodOrganismoReq" value="<?=$CodOrganismoReq?>" />
<input type="hidden" id="Requerimiento" value="<?=$Requerimiento?>" />
<input type="hidden" id="TipoPostulante" value="<?=$TipoPostulante?>" />
<input type="hidden" id="Postulante" value="<?=$Postulante?>" />
<input type="hidden" name="opcion" id="opcion" value="<?=$opcion?>" />
<input type="hidden" name="FlagCopiarImagen" id="FlagCopiarImagen" value="N" />
<input type="hidden" name="FotoAnterior" id="FotoAnterior" value="<?=$FotoAnterior?>" />
<input type="hidden" id="HSitTra" value="<?=$field['SitTra']?>" />
<input type="hidden" id="HCodTipoTrabajador" value="<?=$field['CodTipoTrabajador']?>" />
<input type="hidden" id="HCodPerfil" value="<?=$field['CodPerfil']?>" />
<input type="hidden" id="HCodTipoPago" value="<?=$field['CodTipoPago']?>" />

<div id="tab1" style="display:block;">
<table width="750" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Datos Generales</td>
    	<td class="divFormCaption" width="100">Foto</td>
    </tr>
	<tr>
		<td class="tagForm" width="125">Empleado:</td>
		<td>
        	<input type="text" id="CodEmpleado" style="width:75px;" class="codigo" value="<?=$field['CodEmpleado']?>" disabled="disabled" />
		</td>
		<td class="tagForm" width="125">Persona:</td>
		<td>
        	<input type="text" id="CodPersona" style="width:75px;" class="codigo" value="<?=$field['CodPersona']?>" disabled="disabled" />
		</td>
        <td rowspan="5" align="center" valign="middle">
        	<img src="<?=$Foto?>" style="max-height:100px; max-width:80px; cursor:pointer;" id="imgFoto" onclick="<?=$actualizarFoto?>" title="Actualizar Foto del Empleado" />
            <input type="file" name="Foto" id="Foto" class="ui-corner-all" style="width:200px; display:none;" onchange="copiarImagenTMP(this.form, this.id, 'imgFoto');" <?=$disabled_ver?> />
			<a href="javascript:ampliarFoto('<?=$Foto?>');">Ampliar Foto</a>
        </td>
	</tr>
	<tr>
		<td class="tagForm">Apellido Paterno:</td>
		<td>
        	<input type="text" id="Apellido1" style="width:97%;" maxlength="25" value="<?=$field['Apellido1']?>" onchange="setNombreCompleto();" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">* Materno:</td>
		<td>
        	<input type="text" id="Apellido2" style="width:97%;" maxlength="25" value="<?=$field['Apellido2']?>" onchange="setNombreCompleto();" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Nombres:</td>
		<td>
        	<input type="text" id="Nombres" style="width:97%;" maxlength="50" value="<?=$field['Nombres']?>" onchange="setNombreCompleto();" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">* Sexo:</td>
		<td>
            <select id="Sexo" style="width:100px;" <?=$disabled_ver?>>
                <?=loadSelectGeneral("SEXO", $field['Sexo'], 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Nombre B&uacute;squeda:</td>
		<td colspan="3">
        	<input type="text" id="Busqueda" style="width:99%;" class="disabled" value="<?=$field['Busqueda']?>" disabled />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Nombre Completo:</td>
		<td colspan="3">
        	<input type="text" id="NomCompleto" style="width:99%;" class="disabled" value="<?=$field['NomCompleto']?>" disabled />
		</td>
	</tr>
	<tr>
    	<td colspan="4" class="divFormCaption">Nacimiento</td>
        <td class="divFormCaption">Estado</td>
    </tr>
	<tr>
		<td class="tagForm">* Pais:</td>
		<td>
            <select id="CodPais" style="width:175px;;" onchange="getOptionsSelect(this.value, 'estado', 'CodEstado', true, 'CodMunicipio', 'CiudadNacimiento');" <?=$disabled_ver?>>
                <?=loadSelect("mastpaises", "CodPais", "Pais", $field['CodPaisNac'], 0);?>
            </select>
		</td>
		<td class="tagForm">* Estado:</td>
		<td>
            <select id="CodEstado" style="width:175px;;" onchange="getOptionsSelect(this.value, 'municipio', 'CodMunicipio', true, 'CiudadNacimiento');" <?=$disabled_ver?>>
                <?=loadSelectDependienteEstado($field['CodEstadoNac'], $field['CodPaisNac'], 0);?>
            </select>
		</td>
        <td>
            <input type="radio" name="EdoReg" id="EdoRegA" value="A" <?=chkOpt($field['EdoReg'], "A")?> <?=$disabled_nuevo?> /> Activo
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Municipio:</td>
		<td>
            <select id="CodMunicipio" style="width:175px;;" onchange="getOptionsSelect(this.value, 'ciudad', 'CiudadNacimiento', true);" <?=$disabled_ver?>>
                <?=loadSelectDependiente("mastmunicipios", "CodMunicipio", "Municipio", "CodEstado", $field['CodMunicipioNac'], $field['CodEstadoNac'], 0);?>
            </select>
		</td>
		<td class="tagForm">* Ciudad:</td>
		<td>
            <select id="CiudadNacimiento" style="width:175px;;" <?=$disabled_ver?>>
                <?=loadSelectDependiente("mastciudades", "CodCiudad", "Ciudad", "CodMunicipio", $field['CiudadNacimiento'], $field['CodMunicipioNac'], 0);?>
            </select>
		</td>
        <td>
            <input type="radio" name="EdoReg" id="EdoRegI" value="I" <?=chkOpt($field['EdoReg'], "I");?> <?=$disabled_nuevo?> /> Inactivo
		</td>
	</tr>
    <tr>
        <td class="tagForm">* Fecha:</td>
		<td>
        	<input type="text" id="Fnacimiento" style="width:60px;" class="datepicker" maxlength="10" value="<?=formatFechaDMA($field['Fnacimiento'])?>" onchange="getEdad($(this).val(), '<?=$FechaActual?>', $('#EdadAnios'), $('#EdadMeses'), $('#EdadDias'))" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">Edad:</td>
		<td>
        	<input type="text" id="EdadAnios" style="width:25px;" class="disabled" value="<?=$EdadAnios?>" disabled />a 
        	<input type="text" id="EdadMeses" style="width:25px;" class="disabled" value="<?=$EdadMeses?>" disabled />m 
        	<input type="text" id="EdadDias" style="width:25px;" class="disabled" value="<?=$EdadDias?>" disabled />d
		</td>
        <td rowspan="12">&nbsp;</td>
    </tr>
	<tr>
		<td class="tagForm">Lugar de Nac.:</td>
		<td colspan="3">
        	<input type="text" id="Lnacimiento" style="width:99%;"  value="<?=$field['Lnacimiento']?>"  />
		</td>
	</tr>
	<tr>
    	<td colspan="4" class="divFormCaption">Domicilio Local</td>
    </tr>
	<tr>
		<td class="tagForm">* Pais:</td>
		<td>
            <select id="CodPaisDom" style="width:175px;;" onchange="getOptionsSelect(this.value, 'estado', 'CodEstadoDom', true, 'CodMunicipioDom', 'CiudadDomicilio');" <?=$disabled_ver?>>
                <?=loadSelect("mastpaises", "CodPais", "Pais", $field['CodPaisDom'], 0);?>
            </select>
		</td>
		<td class="tagForm">* Estado:</td>
		<td>
            <select id="CodEstadoDom" style="width:175px;;" onchange="getOptionsSelect(this.value, 'municipio', 'CodMunicipioDom', true, 'CiudadDomicilio');" <?=$disabled_ver?>>
                <?=loadSelectDependienteEstado($field['CodEstadoDom'], $field['CodPaisDom'], 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Municipio:</td>
		<td>
            <select id="CodMunicipioDom" style="width:175px;;" onchange="getOptionsSelect(this.value, 'ciudad', 'CiudadDomicilio', true);" <?=$disabled_ver?>>
                <?=loadSelectDependiente("mastmunicipios", "CodMunicipio", "Municipio", "CodEstado", $field['CodMunicipioDom'], $field['CodEstadoDom'], 0);?>
            </select>
		</td>
		<td class="tagForm">* Ciudad:</td>
		<td>
            <select id="CiudadDomicilio" style="width:175px;;" <?=$disabled_ver?>>
                <?=loadSelectDependiente("mastciudades", "CodCiudad", "Ciudad", "CodMunicipio", $field['CiudadDomicilio'], $field['CodMunicipioDom'], 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Direcci&oacute;n:</td>
		<td colspan="3">
        	<textarea id="Direccion" style="width:99%; height:30px;" <?=$disabled_ver?>><?=$field['Direccion']?></textarea>
        </td>
	</tr>
	<tr>
		<td class="tagForm">Tel&eacute;fono:</td>
		<td colspan="3">
        	<table cellpadding="0" cellspacing="0">
            	<tr>
                	<td>
                    	<input type="text" id="Telefono1" style="width:100px;" maxlength="15" value="<?=$field['Telefono1']?>" <?=$disabled_ver?> />
                    </td>
					<td class="tagForm" width="125">Celular:</td>
                	<td>
                    	<input type="text" id="Telefono2" style="width:100px;" maxlength="15" value="<?=$field['Telefono2']?>" <?=$disabled_ver?> />
                    </td>
					<td class="tagForm" width="125">Fax:</td>
                	<td>
                    	<input type="text" id="Fax" style="width:100px;" maxlength="15" value="<?=$field['Fax']?>" <?=$disabled_ver?> />
                    </td>
                </tr>
            </table>
        </td>
	</tr>
	<tr>
    	<td colspan="4" class="divFormCaption">Documentos</td>
    </tr>
	<tr>
		<td class="tagForm">* Tipo Doc.:</td>
		<td>
            <select id="TipoDocumento" style="width:175px;;" <?=$disabled_ver?>>
                <?=getMiscelaneos($field['TipoDocumento'], "DOCUMENTOS", 0);?>
            </select>
		</td>
		<td class="tagForm">* Nro. Doc.:</td>
		<td>
           	<input type="text" id="Ndocumento" style="width:100px;;" maxlength="20" value="<?=$field['Ndocumento']?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Nacionalidad:</td>
		<td>
            <select id="Nacionalidad" style="width:175px;;" <?=$disabled_ver?>>
                <?=getMiscelaneos($field['Nacionalidad'], "NACION", 0);?>
            </select>
		</td>
		<td class="tagForm">Doc. Fiscal:</td>
		<td>
           	<input type="text" id="DocFiscal" style="width:100px;;" maxlength="20" value="<?=$field['DocFiscal']?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">E-mail:</td>
		<td colspan="3">
           	<input type="text" id="Email" style="width:99%;" maxlength="255" value="<?=$field['Email']?>" <?=$disabled_ver?> />
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
</div>

<div id="tab2" style="display:none;">
<table width="750" class="tblForm">
	<tr>
    	<td colspan="7" class="divFormCaption">Otros Datos Personales</td>
    </tr>
	<tr>
		<td class="tagForm" width="125">Grupo Sangu&iacute;neo:</td>
		<td colspan="3">
            <select id="GrupoSanguineo" style="width:200px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=getMiscelaneos($field['GrupoSanguineo'], "SANGRE", 0);?>
            </select>
		</td>
		<td class="tagForm" width="125">Situaci&oacute;n Domicilio:</td>
		<td colspan="2">
            <select id="SituacionDomicilio" style="width:200px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=getMiscelaneos($field['SituacionDomicilio'], "SITDOM", 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Edo. Civil:</td>
		<td colspan="3">
            <select id="EstadoCivil" style="width:200px;" <?=$disabled_ver?>>
                <?=getMiscelaneos($field['EstadoCivil'], "EDOCIVIL", 0);?>
            </select>
		</td>
		<td class="tagForm">F. Edo. Civil:</td>
		<td colspan="2">
            <input type="text" id="FedoCivil" style="width:60px;" class="datepicker" maxlength="10" value="<?=formatFechaDMA($field['FedoCivil'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
    	<td colspan="7" class="divFormCaption">Otros Datos Personales</td>
    </tr>
	<tr>
		<td class="tagForm">Nombre:</td>
		<td colspan="3">
        	<input type="text" id="NomEmerg1" style="width:95%;" maxlength="100" value="<?=$field['NomEmerg1']?>" <?=$disabled_ver?> />
		</td>
		<td colspan="3">
        	<input type="text" id="NomEmerg2" style="width:95%;" maxlength="100" value="<?=$field['NomEmerg2']?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Direcci&oacute;n:</td>
		<td colspan="3">
        	<input type="text" id="DirecEmerg1" style="width:95%;" maxlength="255" value="<?=$field['DirecEmerg1']?>" <?=$disabled_ver?> />
		</td>
		<td colspan="3">
        	<input type="text" id="DirecEmerg2" style="width:95%;" maxlength="255" value="<?=$field['DirecEmerg2']?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Tel&eacute;fono:</td>
		<td>
        	<input type="text" id="TelefEmerg1" style="width:100px;;" maxlength="15" value="<?=$field['TelefEmerg1']?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm" width="75">Celular:</td>
		<td>
        	<input type="text" id="CelEmerg1" style="width:100px;;" maxlength="15" value="<?=$field['CelEmerg1']?>" <?=$disabled_ver?> />
		</td>
		<td>
        	<input type="text" id="TelefEmerg2" style="width:100px;" maxlength="15" value="<?=$field['TelefEmerg2']?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm" width="75">Celular:</td>
		<td>
        	<input type="text" id="CelEmerg2" style="width:100px;;" maxlength="15" value="<?=$field['CelEmerg2']?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Parentesco:</td>
		<td colspan="3">
            <select id="ParentEmerg1" style="width:200px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=getMiscelaneos($field['ParentEmerg1'], "PARENT", 0);?>
            </select>
		</td>
		<td colspan="3">
            <select id="ParentEmerg2" style="width:200px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=getMiscelaneos($field['ParentEmerg2'], "PARENT", 0);?>
            </select>
		</td>
	</tr>
	<tr>
    	<td colspan="7" class="divFormCaption">Licencia de Conducir</td>
    </tr>
	<tr>
		<td class="tagForm">Tipo:</td>
		<td colspan="3">
            <select id="TipoLicencia" style="width:150px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=getMiscelaneos($field['TipoLicencia'], "TIPOLIC", 0);?>
            </select>
		</td>
		<td class="tagForm">Nro. Lic.:</td>
		<td colspan="2">
            <input type="text" id="Nlicencia" style="width:150px;" maxlength="30" value="<?=$field['Nlicencia']?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Fecha de Expiraci&oacute;n:</td>
		<td colspan="3">
            <input type="text" id="ExpiraLicencia" style="width:60px;" class="datepicker" maxlength="10" value="<?=formatFechaDMA($field['ExpiraLicencia'])?>" <?=$disabled_ver?> />
		</td>
		<td>&nbsp;</td>
		<td colspan="2">
            <input type="checkbox" id="SiAuto" value="S" <?=chkFlag($field['SiAuto'])?> <?=$disabled_ver?> /> ¿Posee auto?
		</td>
	</tr>
	<tr>
    	<td colspan="7" class="divFormCaption">Observaciones Adicionales</td>
    </tr>
	<tr>
		<td colspan="7" align="center">
        	<textarea id="Observacion" style="width:99%; height:60px;" <?=$disabled_ver?>><?=$field['Observacion']?></textarea>
		</td>
	</tr>
</table>
</div>

<div id="tab3" style="display:none;">
<table width="750" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Organizaci&oacute;n</td>
    </tr>
	<tr>
		<td class="tagForm">* Organismo:</td>
		<td colspan="3">
            <select id="CodOrganismo" style="width:400px;" onchange="getOptionsSelect(this.value, 'dependencia_filtro', 'CodDependencia', true, 'CodCentroCosto');" <?=$disabled_modificar?>>
                <?=getOrganismos($field['CodOrganismo'], 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Dependencia:</td>
		<td colspan="3">
            <select id="CodDependencia" style="width:400px;" onchange="getOptionsSelect(this.value, 'centro_costo', 'CodCentroCosto', true);" <?=$disabled_modificar?>>
                <?=getDependencias($field['CodDependencia'], $field['CodOrganismo'], 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Centro de Costo:</td>
		<td colspan="3">
            <select id="CodCentroCosto" style="width:400px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=loadSelectDependiente("ac_mastcentrocosto", "CodCentroCosto", "Descripcion", "CodDependencia", $field['CodCentroCosto'], $field['CodDependencia'], 0);?>
            </select>
		</td>
	</tr>
	<tr>
    	<td colspan="4" class="divFormCaption">Planilla</td>
    </tr>
	<tr>
		<td class="tagForm">* Tipo de N&oacute;mina:</td>
		<td>
            <select id="CodTipoNom" style="width:200px;" <?=$disabled_modificar?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("tiponomina", "CodTipoNom", "Nomina", $field['CodTipoNom'], 0);?>
            </select>
		</td>
		<td class="tagForm">* Perfil de N&oacute;mina:</td>
		<td>
            <select id="CodPerfil" style="width:200px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("tipoperfilnom", "CodPerfil", "Perfil", $field['CodPerfil'], 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Tipo de Pago:</td>
		<td>
            <select id="CodTipoPago" style="width:200px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("masttipopago", "CodTipoPago", "TipoPago", $field['CodTipoPago'], 0);?>
            </select>
		</td>
		<td class="tagForm">* Tipo de Trabajador:</td>
		<td>
            <select id="CodTipoTrabajador" style="width:200px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("rh_tipotrabajador", "CodTipoTrabajador", "TipoTrabajador", $field['CodTipoTrabajador'], 0);?>
            </select>
		</td>
	</tr>
	<tr>
    	<td colspan="4" class="divFormCaption">Horario de Trabajo</td>
    </tr>
	<tr>
		<td class="tagForm">Cod. Carnet:</td>
		<td>
            <input type="text" id="CodCarnetProv" style="width:50px;" maxlength="6" value="<?=$field['CodCarnetProv']?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">Horario:</td>
		<td>
            <select id="CodHorario" style="width:200px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("rh_horariolaboral", "CodHorario", "Descripcion", $field['CodHorario'], 0);?>
            </select>
		</td>
    </tr>
</table>
</div>

<div id="tab4" style="display:none;">
<table width="750" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Ingreso</td>
    </tr>
	<tr>
		<td class="tagForm">* Fecha de Ingreso:</td>
		<td colspan="3">
        	<input type="text" id="Fingreso" style="width:60px;" class="datepicker" maxlength="10" value="<?=formatFechaDMA($field['Fingreso'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
    	<td colspan="4" class="divFormCaption">Cese</td>
    </tr>
    <tr>
		<td class="tagForm" width="125">Estado:</td>
		<td>
            <input type="radio" name="SitTra" id="SitTraA" value="A" <?=chkOpt($field['SitTra'], "A");?> onclick="setCese($(this).val());" <?=$disabled_nuevo?> /> Activo
            <input type="radio" name="SitTra" id="SitTraI" value="I" <?=chkOpt($field['SitTra'], "I");?> onclick="setCese($(this).val());" <?=$disabled_nuevo?> /> Inactivo
		</td>
		<td class="tagForm" width="125">Motivo del Cese:</td>
		<td>
            <select id="CodMotivoCes" style="width:200px;" class="cese" <?=$disabled_cese?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("rh_motivocese", "CodMotivoCes", "MotivoCese", $field['CodMotivoCes'], 0);?>
            </select>
		</td>
	</tr>
    <tr>
		<td class="tagForm">Fecha de Cese:</td>
		<td>
            <input type="text" id="Fegreso" style="width:60px;" class="datepicker cese" maxlength="10" value="<?=formatFechaDMA($field['Fegreso'])?>" <?=$disabled_cese?> />
		</td>
		<td class="tagForm">Explicaci&oacute;n:</td>
		<td>
            <input type="text" id="ObsCese" style="width:95%;" class="cese" value="<?=$field['ObsCese']?>" <?=$disabled_cese?> />
		</td>
	</tr>
	<tr>
    	<td colspan="4" class="divFormCaption">Estructura del Puesto</td>
    </tr>
	<tr>
		<td class="tagForm">* Grupo Ocupacional:</td>
		<td colspan="3">
            <select id="CodGrupOcup" style="width:400px;" onchange="getOptionsSelect(this.value, 'serie-ocupacional', 'CodSerieOcup', true, 'CodCargo');" <?=$disabled_modificar?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("rh_grupoocupacional", "CodGrupOcup", "GrupoOcup", $field['CodGrupOcup'], 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Serie Ocupacional:</td>
		<td colspan="3">
            <select id="CodSerieOcup" style="width:400px;" onchange="getOptionsSelect(this.value, 'cargo', 'CodCargo', true);" <?=$disabled_modificar?>>
                <?=loadSelectDependiente("rh_serieocupacional", "CodSerieOcup", "SerieOcup", "CodGrupOcup", $field['CodSerieOcup'], $field['CodGrupOcup'], 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Cargo:</td>
		<td colspan="3">
            <select id="CodCargo" style="width:400px;" onchange="getSueldoBasico($(this).val(), $('#CategoriaCargo'), $('#SueldoActual'));" <?=$disabled_modificar?>>
                <?=loadSelectDependiente("rh_puestos", "CodCargo", "DescripCargo", "CodSerieOcup", $field['CodCargo'], $field['CodSerieOcup'], 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Categor&iacute;a:</td>
		<td colspan="3">
        	<input type="text" id="CategoriaCargo" style="width:100px;" class="disabled" value="<?=$field['NomCategoriaCargo']?>" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Sueldo B&aacute;sico:</td>
		<td colspan="3">
        	<input type="text" id="SueldoActual" style="width:100px; text-align:right;" class="disabled" value="<?=number_format($field['SueldoActual'], 2, ',', '.')?>" disabled="disabled" />
		</td>
	</tr>
</table>
</div>

<center>
<input type="submit" value="<?=$label_submit?>" style=" <?=$display_submit?>" />
<input type="button" value="Cancelar" onclick="$('#frmentrada').attr('action', 'gehen.php?anz=empleados_lista').attr('target', ''); <?=$clkCancelar?>" />
</center>

</form>

<div style="width:750px" class="divMsj">Campos Obligatorios *</div>

<iframe name="imagen_tmp" id="imagen_tmp" style="display:none;"></iframe>
