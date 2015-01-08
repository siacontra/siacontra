<?php
if ($opcion == "nuevo") 
	{

		$accion = "nuevo";
		$titulo = "Nueva Persona";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$sTipoPersona1 = "Natural";
		$sTipoPersona2 = "Const.";
		$CodPais = $_PARAMETRO['PAISDEFAULT'];
		$CodEstado = $_PARAMETRO['ESTADODEFAULT'];
		$CodMunicipio = $_PARAMETRO['MUNICIPIODEFAULT'];
		$CodCiudad = $_PARAMETRO['CIUDADDEFAULT'];
		$Sexo = "M";
		$EstadoCivil = "01";
		$flagactivo = "checked";
		$flagactivo_empleado = "checked";
		$tabEmpleado = "display:none;";
		$tabProveedor = "display:none;";
		$flagnacional = "checked";
		$CodOrganismo = $_SESSION["ORGANISMO_ACTUAL"];
		$disabled_n = "disabled";

	}elseif ($opcion == "modificar" || $opcion == "ver") 
		{
			//	consulto datos generales	
				$sql = "SELECT
							p.*,
							c.CodMunicipio,
							m.CodEstado,
							e.CodPais,
							bp.CodBanco,
							bp.Ncuenta,
							bp.TipoCuenta,
							me.CodEmpleado,
							me.CodOrganismo,
							me.CodDependencia,
							me.CodCentroCosto,
							me.Estado AS EstadoEmpleado,
							me.Usuario,
							cc.Abreviatura AS NomCentroCosto,
							pr.CodProveedor, 
							pr.CodTipoDocumento, 
							pr.CodTipoPago, 
							pr.CodFormaPago, 
							pr.CodTipoServicio, 
							pr.DiasPago, 
							pr.RegistroPublico, 
							pr.LicenciaMunicipal, 
							pr.FechaConstitucion, 
							pr.RepresentanteLegal, 
							pr.ContactoVendedor, 
							pr.FlagSNC, 
							pr.NroInscripcionSNC, 
							pr.FechaEmisionSNC, 
							pr.FechaValidacionSNC, 
							pr.Nacionalidad, 
							pr.CondicionRNC, 
							pr.Calificacion, 
							pr.Nivel, 
							pr.Capacidad
					FROM
							mastpersonas p
							INNER JOIN mastciudades c ON (p.CiudadNacimiento = c.CodCiudad)
							INNER JOIN mastmunicipios m ON (c.CodMunicipio = m.CodMunicipio)
							INNER JOIN mastestados e ON (m.CodEstado = e.CodEstado)
							LEFT JOIN bancopersona bp ON (p.CodPersona = bp.CodPersona)
							LEFT JOIN mastproveedores pr ON (p.CodPersona = pr.CodProveedor)
							LEFT JOIN mastempleado me ON (p.CodPersona = me.CodPersona)
							LEFT JOIN ac_mastcentrocosto cc ON (me.CodCentroCosto = cc.CodCentroCosto)
					WHERE 	p.CodPersona = '".$registro."'";
				$query_mast = mysql_query($sql) or die($sql.mysql_error());

			if (mysql_num_rows($query_mast)) $field_persona = mysql_fetch_array($query_mast);
	
			if ($opcion == "modificar") 
				{
					$accion = "modificar";
					$titulo = "Modificar Persona";
					$cancelar = "document.getElementById('frmentrada').submit();";
					
					if ($field_persona['Usuario'] == "") $Usuario = setUsuario($registro);
					if ($factualizar == "Persona") 
						{
							$tabProveedor = "display:none;";
							$tabEmpleado = "display:none;";
						}elseif ($factualizar == "Empleado") 
							{
								$tabProveedor = "display:none;";
								$EsEmpleado = "checked";
							}elseif ($factualizar == "Proveedor") 
								{
									$tabEmpleado = "display:none;";
									$EsProveedor = "checked";
								}
					if ($field_persona['TipoPersona'] == "J") $disabled_j = "disabled"; else $disabled_n = "disabled";
						$disabled_ver = "";
        		}elseif ($opcion == "ver") 
        			{
						$disabled_ver = "disabled";
						$titulo = "Ver Persona";
						$cancelar = "document.getElementById('frmentrada').submit();";
						$display_submit = "display:none;";
						$disabled_j = "disabled";
						$disabled_n = "disabled";
					}
			$TipoPersona = printValoresGeneral("CLASE-PERSONA", $field_persona['TipoPersona']);
			if ($field_persona['TipoPersona'] == "N") 
			{
				$sTipoPersona1 = "Natural";
				$sTipoPersona2 = "Nac.";
			}else 
				{
					$sTipoPersona1 = "Jur&iacute;dica";
					$sTipoPersona2 = "Const.";
				}
			$CodPais = $field_persona['CodPais'];
			$CodEstado = $field_persona['CodEstado'];
			$CodMunicipio = $field_persona['CodMunicipio'];
			$CodCiudad = $field_persona['CodCiudad'];
			$Sexo = $field_persona['Sexo'];
			$EstadoCivil = $field_persona['EstadoCivil'];
			if ($field_persona['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked";
			if ($field_persona['EstadoEmpleado'] == "" || $field_persona['EstadoEmpleado'] == "A") $flagactivo_empleado = "checked";
			else $flaginactivo_empleado = "checked";
			$Usuario = $field_persona['Usuario'];
			if ($field_persona['EsEmpleado'] == "S") $EsEmpleado = "checked";
			if ($field_persona['EsProveedor'] == "S") $EsProveedor = "checked";
			if ($field_persona['EsCliente'] == "S") $EsCliente = "checked";
			if ($field_persona['EsOtro'] == "S") $EsOtro = "checked";
			if ($field_persona['Nacionalidad'] == "E") $flagextranjero = "checked"; else $flagnacional = "checked";
			if ($field_persona['FlagSNC'] == "S") $FlagSNC = "checked";
			if ($field_persona['CodOrganismo'] == "") $CodOrganismo = $_SESSION["ORGANISMO_ACTUAL"]; else $CodOrganismo = $field_persona['CodOrganismo'];
			if ($field_persona['EsEmpleado'] == "S") $EsEmpleado = "checked"; //else $tabEmpleado = "display:none;";
			if ($field_persona['EsProveedor'] == "S") $EsProveedor = "checked"; //else $tabProveedor = "display:none;";
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$cancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<table width="900" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current"><a href="#" onclick="mostrarTab('tab', 1, 3);">Informaci&oacute;n General</a></li>
            <li id="li2" onclick="currentTab('tab', this);" style=" <?=$tabEmpleado?>"><a href="#" onclick="mostrarTab('tab', 2, 3);">Empleado</a></li>
            <li id="li3" onclick="currentTab('tab', this);" style=" <?=$tabProveedor?>"><a href="#" onclick="mostrarTab('tab', 3, 3);">Proveedores</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=personas_lista" method="POST" onsubmit="return personas(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fedoreg" id="fedoreg" value="<?=$fedoreg?>" />
<input type="hidden" name="fclase" id="fclase" value="<?=$fclase?>" />
<input type="hidden" name="ftipo" id="ftipo" value="<?=$ftipo?>" />
<input type="hidden" name="fbuscar" id="fbuscar" value="<?=$fbuscar?>" />
<input type="hidden" name="factualizar" id="factualizar" value="<?=$factualizar?>" />
<input type="hidden" name="fordenar" id="fordenar" value="<?=$fordenar?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="CodEmpleado" id="CodEmpleado" value="<?=$field_persona['CodEmpleado']?>" />

<div id="tab1" style="display:block;">
<table width="900px" class="tblForm">
	<tr>
    	<td class="divFormCaption" colspan="4">Datos Generales</td>
    </tr>
	<tr>
		<td class="tagForm" width="125">Persona:</td>
		<td>
        	<input type="text" id="CodPersona" style="width:100px; font-weight:bold; font-size:12px;" value="<?=$field_persona['CodPersona']?>" disabled="disabled" />
		</td>
		<td class="tagForm">* Nombre B&uacute;squeda:</td>
		<td>
        	<input type="text" id="Busqueda" style="width:250px;" maxlength="100" value="<?=($field_persona['Busqueda'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Clase de Persona:</td>
		<td>
            <select id="TipoPersona" style="width:105px;" onchange="setLabelTipoPersona(this.value);" <?=$disabled_ver?>>
                <?=loadSelectGeneral("CLASE-PERSONA", $field_persona['TipoPersona'], 0)?>
            </select>
		</td>
		<td class="tagForm">* Nombre Completo:</td>
		<td>
        	<input type="text" id="NomCompleto" style="width:250px;" maxlength="100" value="<?=($field_persona['NomCompleto'])?>" onchange="setBusqueda();" <?=$disabled_n?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Tipo de Persona:</td>
		<td colspan="3">
            <input type="checkbox" id="EsEmpleado" value="S" onclick="this.checked=this.checked;" <?=$EsEmpleado?> <?=$disabled_ver?> /> Empleado
            &nbsp; &nbsp; &nbsp; 
            <input type="checkbox" id="EsProveedor" value="S"  <?=$EsProveedor?> <?=$disabled_ver?> /> Proveedor
            &nbsp; &nbsp; &nbsp; 
            <input type="checkbox" id="EsCliente" value="S" onclick="this.checked=this.checked;" <?=$EsCliente?> <?=$disabled_ver?> /> Cliente
            &nbsp; &nbsp; &nbsp; 
            <input type="checkbox" id="EsOtros" value="S" <?=$EsOtros?> <?=$disabled_ver?> /> Otro
		</td>
	</tr>
    <tr>
    	<td class="divFormCaption" colspan="4">Datos Persona <span id="sTipoPersona1"><?=$sTipoPersona1?></span></td>
    </tr>
	<tr>
		<td class="tagForm">Apellido Paterno:</td>
		<td>
        	<input type="text" id="Apellido1" style="width:200px;" maxlength="25" value="<?=$field_persona['Apellido1']?>" onchange="setNombreCompleto();" <?=$disabled_ver?> <?=$disabled_j?> />
		</td>
		<td class="tagForm">* Materno:</td>
		<td>
        	<input type="text" id="Apellido2" style="width:200px;" maxlength="25" value="<?=$field_persona['Apellido2']?>" onchange="setNombreCompleto();" <?=$disabled_ver?> <?=$disabled_j?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Nombres:</td>
		<td>
        	<input type="text" id="Nombres" style="width:200px;" maxlength="50" value="<?=$field_persona['Nombres']?>" onchange="setNombreCompleto();" <?=$disabled_ver?> <?=$disabled_j?> />
		</td>
		<td class="tagForm">* Sexo:</td>
		<td>
            <select id="Sexo" style="width:105px;" <?=$disabled_ver?> <?=$disabled_j?>>
            	<option value="">&nbsp;</option>
                <?=loadSelectGeneral("SEXO", $Sexo, 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Fecha de <span id="sTipoPersona2">Nac.</span>:</td>
		<td>
        	<input type="text" id="Fnacimiento" value="<?=formatFechaDMA($field_persona['Fnacimiento'])?>" style="width:75px;" maxlength="10" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">* Estado Civil:</td>
		<td>
            <select id="EstadoCivil" style="width:105px;" <?=$disabled_ver?> <?=$disabled_j?>>
            	<option value="">&nbsp;</option>
                <?=getMiscelaneos($EstadoCivil, "EDOCIVIL", 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Direcci&oacute;n:</td>
		<td colspan="3">
			<textarea id="Direccion" style="width:98%; height:50px;" <?=$disabled_ver?>><?=$field_persona['Direccion']?></textarea>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Pais:</td>
		<td>
            <select id="CodPais" style="width:200px;" onchange="getOptionsSelect(this.value, 'estado', 'CodEstado', true, 'CodMunicipio', 'CodCiudad');" <?=$disabled_ver?>>
                <?=loadSelect("mastpaises", "CodPais", "Pais", $CodPais, 0);?>
            </select>
		</td>
		<td class="tagForm">* Estado:</td>
		<td>
            <select id="CodEstado" style="width:200px;" onchange="getOptionsSelect(this.value, 'municipio', 'CodMunicipio', true, 'CodCiudad');" <?=$disabled_ver?>>
                <?=loadSelectDependienteEstado($CodEstado, $CodPais, 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Municipio:</td>
		<td>
            <select id="CodMunicipio" style="width:200px;" onchange="getOptionsSelect(this.value, 'ciudad', 'CodCiudad', true);" <?=$disabled_ver?>>
                <?=loadSelectDependiente("mastmunicipios", "CodMunicipio", "Municipio", "CodEstado", $CodMunicipio, $CodEstado, 0);?>
            </select>
		</td>
		<td class="tagForm">* Ciudad:</td>
		<td>
            <select id="CodCiudad" style="width:200px;" <?=$disabled_ver?>>
                <?=loadSelectDependiente("mastciudades", "CodCiudad", "Ciudad", "CodMunicipio", $CodCiudad, $CodMunicipio, 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">E-mail:</td>
		<td>
        	<input type="text" id="Email" style="width:200px;" maxlength="100" value="<?=$field_persona['Email']?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">Telefono:</td>
		<td>
        	<input type="text" id="Telefono1" style="width:200px;" maxlength="15" value="<?=$field_persona['Telefono1']?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Celular:</td>
		<td>
        	<input type="text" id="Telefono2" style="width:200px;" maxlength="15" value="<?=$field_persona['Telefono2']?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">Fax:</td>
		<td>
        	<input type="text" id="Fax" style="width:200px;" maxlength="15" value="<?=$field_persona['Fax']?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Nombre Emerg.:</td>
		<td>
        	<input type="text" id="NomEmerg1" style="width:200px;" maxlength="100" value="<?=$field_persona['NomEmerg1']?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">Dir. Emerg.:</td>
		<td>
        	<input type="text" id="DirecEmerg1" style="width:200px;" maxlength="255" value="<?=$field_persona['DirecEmerg1']?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
    	<td class="divFormCaption" colspan="2">Documentos de Identificaci&oacute;n</td>
    	<td class="divFormCaption" colspan="2">Informaci&oacute;n Bancaria</td>
    </tr>
	<tr>
		<td class="tagForm">* Principal:</td>
		<td>
            <select id="TipoDocumento" style="width:205px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=getMiscelaneos($field_persona['TipoDocumento'], "DOCUMENTOS", 0)?>
            </select>
		</td>
		<td class="tagForm">Banco:</td>
		<td>
            <select id="CodBanco" style="width:205px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("mastbancos", "CodBanco", "Banco", $field_persona['CodBanco'], 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Nro. Documento:</td>
		<td>
        	<input type="text" id="Ndocumento" style="width:200px;" maxlength="20" value="<?=$field_persona['Ndocumento']?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">Nro. Cuenta:</td>
		<td>
        	<input type="text" id="Ncuenta" style="width:200px;" maxlength="30" value="<?=$field_persona['Ncuenta']?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Doc. Fiscal:</td>
		<td>
        	<input type="text" id="DocFiscal" style="width:200px;" maxlength="20" value="<?=$field_persona['DocFiscal']?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">Tipo Cuenta:</td>
		<td>
            <select id="TipoCuenta" style="width:105px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=getMiscelaneos($field_persona['TipoCuenta'], "TIPOCTA", 0)?>
            </select>
		</td>
	</tr>
	<tr>
    	<td class="divFormCaption" colspan="4">Datos de Auditor&iacute;a</td>
    </tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td colspan="3">
            <input type="radio" name="Estado" id="activo" value="A" <?=$flagactivo?> <?=$disabled_ver?> /> Activo
            <input type="radio" name="Estado" id="inactivo" value="I" <?=$flaginactivo?> <?=$disabled_ver?> /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td colspan="3">
			<input type="text" size="30" value="<?=$field_persona['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" value="<?=$field_persona['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>

<center>
<input type="submit"  value="Guardar" style="width:80px; <?=$display_submit?>" />
<input type="button" value="Cancelar" style="width:80px;" onclick="<?=$cancelar?>" />
</center>
<br />
<div style="width:900px; <?=$display_submit?>" class="divMsj">(*) Campos Obligatorios</div>
</div>

<div id="tab2" style="display:none;">
<table width="900px" class="tblForm">
	<tr>
		<td class="tagForm" width="125">* Organismo:</td>
		<td>
            <select id="CodOrganismo" style="width:300px;" <?=$disabled_ver?> onchange="getOptionsSelect(this.value, 'dependencia_cc', 'CodDependencia', true, 'CodCentroCosto');">
                <?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $CodOrganismo, 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Dependencia:</td>
		<td>
            <select id="CodDependencia" style="width:300px;" <?=$disabled_ver?> onchange="getOptionsSelect(this.value, 'centro_costo', 'CodCentroCosto', true);">
            	<option value="">&nbsp;</option>
                <?=loadSelectDependiente("mastdependencias", "CodDependencia", "Dependencia", "CodOrganismo", $field_persona['CodDependencia'], $CodOrganismo, 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Centro de Costo:</td>
		<td>
            <select id="CodCentroCosto" style="width:300px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=loadSelectDependiente("ac_mastcentrocosto", "CodCentroCosto", "Descripcion", "CodDependencia", $field_persona['CodCentroCosto'], $field_persona['CodDependencia'], 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Usuario:</td>
		<td>
        	<input type="text" id="Usuario" style="width:200px;" maxlength="20" value="<?=$Usuario?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Estado del Empleado:</td>
		<td>
            <input type="radio" name="EstadoEmpleado" id="activo_empleado" value="A" <?=$flagactivo_empleado?> <?=$disabled_ver?> /> Activo
            <input type="radio" name="EstadoEmpleado" id="inactivo_epleado" value="I" <?=$flaginactivo_empleado?> <?=$disabled_ver?> /> Inactivo
		</td>
	</tr>
</table>
</div>

<div id="tab3" style="display:none;">
<table width="900px" class="tblForm">
	<tr>
    	<td class="divFormCaption" colspan="4">Informaci&oacute;n para Pagos</td>
    </tr>
	<tr>
		<td class="tagForm" width="175">* Documento del Proveedor:</td>
		<td>
            <select id="CodTipoDocumento" style="width:225px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("ap_tipodocumento", "CodTipoDocumento", "Descripcion", $field_persona['CodTipoDocumento'], 0);?>
            </select>
		</td>
		<td class="tagForm">* Documento de Pago:</td>
		<td>
            <select id="CodTipoPago" style="width:225px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("masttipopago", "CodTipoPago", "TipoPago", $field_persona['CodTipoPago'], 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Tipo de Servicio:</td>
		<td>
            <select id="CodTipoServicio" style="width:225px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("masttiposervicio", "CodTipoServicio", "Descripcion", $field_persona['CodTipoServicio'], 0);?>
            </select>
		</td>
		<td class="tagForm">* Forma de Pago:</td>
		<td>
            <select id="CodFormaPago" style="width:225px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("mastformapago", "CodFormaPago", "Descripcion", $field_persona['CodFormaPago'], 0);?>
            </select>
		</td>
	</tr>
	<tr>
    	<td class="divFormCaption" colspan="4">Informaci&oacute;n Adicional</td>
    </tr>
	<tr>
		<td class="tagForm">Nro. Dias para pago:</td>
		<td>
        	<input type="text" id="DiasPago" style="width:50px;" maxlength="4" value="<?=$field_persona['DiasPago']?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">Registro P&uacute;blico:</td>
		<td>
        	<input type="text" id="RegistroPublico" style="width:200px;" maxlength="20" value="<?=$field_persona['RegistroPublico']?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Licencia Municipal:</td>
		<td>
        	<input type="text" id="LicenciaMunicipal" style="width:200px;" maxlength="20" value="<?=$field_persona['LicenciaMunicipal']?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">Fecha de Const.:</td>
		<td>
        	<input type="text" id="FechaConstitucion" value="<?=formatFechaDMA($field_persona['FechaConstitucion'])?>" style="width:75px;" maxlength="10" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Representante Legal:</td>
		<td>
        	<input type="text" id="RepresentanteLegal" style="width:200px;" maxlength="50" value="<?=$field_persona['RepresentanteLegal']?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">Contacto/Vendedor:</td>
		<td>
        	<input type="text" id="ContactoVendedor" style="width:200px;" maxlength="50" value="<?=$field_persona['ContactoVendedor']?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
    	<td class="divFormCaption" colspan="4">Informaci&oacute;n SNC</td>
    </tr>
	<tr>
		<td class="tagForm">Inscripci&oacute;n SNC:</td>
		<td>
        	<input type="checkbox" id="FlagSNC" value="S" <?=$FlagSNC?> <?=$disabled_ver?> onclick="habilitarCondicion(this.id);" />
		</td>
		<td class="tagForm">Nro. Insc. SNC:</td>
		<td>
        	<input type="text" id="NroInscripcionSNC" style="width:200px;" maxlength="20" value="<?=$field_persona['NroInscripcionSNC']?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">F. Emisi&oacute;n SNC:</td>
		<td>
        	<input type="text" id="FechaEmisionSNC" value="<?=formatFechaDMA($field_persona['FechaEmisionSNC'])?>" style="width:75px;" maxlength="10" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">F. Validaci&oacute;n SNC:</td>
		<td>
        	<input type="text" id="FechaValidacionSNC" value="<?=formatFechaDMA($field_persona['FechaValidacionSNC'])?>" style="width:75px;" maxlength="10" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td align="right">Condici&oacute;n RNC:</td>
		<td align="left">
		<select id="condicionRNC" disabled="disabled" >
			<option value="-1"></option>
			<option value="1">Empresa suspendida por el Art. 30 la L.C.P.</option>
			<option value="2">Empresa registrada en el R.N.C.</option>
			<option value="3">Empresa en proceso de descapitalizaci&oacute;n</option>
			<option value="4">Suspendido por el Art. 139</option>
			<option value="5">Empresa del Gobierno</option>

			</select>
		</td>
		<td class="tagForm">Nacionalidad:</td>
		<td colspan="3">
            <input type="radio" name="Nacionalidad" id="nacional" value="N" <?=$flagnacional?> <?=$disabled_ver?> /> Nacional
            <input type="radio" name="Nacionalidad" id="extranjero" value="E" <?=$flagextranjero?> <?=$disabled_ver?> /> Extranjero
		</td>
	</tr>
	<tr>
		<td align="right">Nivel Estimado de Contrataci&oacute;n:</td>
		<td align="left">
		<input id="nivel" name="nivel" type="text" value="<?=$field_persona['Nivel']?>" <?=$disabled_ver?>/>
			
		</td>
		<td class="tagForm">Calificaci&oacute;n Financiera:</td>
		<td >
            <input id="calificacion" name="calificacion" type="text" value="<?=$field_persona['Calificacion']?>" <?=$disabled_ver?>/>
		</td>
		<td class="tagForm">
            Capacidad Financiera:
		</td>
		<td >
            <input id="capacidad" name="capacidad" type="text" value="<?=$field_persona['Capacidad']?>" <?=$disabled_ver?>/>
		</td>
	</tr>
</table>
</div>

</form>

<!-- JS	-->
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	$("#Busqueda").focus();
});

	
	restablecerLista('condicionRNC',<?php echo $field_persona['CondicionRNC']; ?>);

	
	habilitarCondicion('FlagSNC');
	
</script>
