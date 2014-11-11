<?php
$Ahora = ahora();
//$FactorImpuesto = getFactorImpuesto();
$dFlagPresupuesto = "disabled";
$dFlagDistribucionManual = "disabled";
if ($opcion == "nuevo") {

	$field_obligacion['FechaAprobado'] = date("Y-m-d");
	
	$accion = "nuevo";
	$titulo = "Nueva Obligaci&oacute;n";
	$label_submit = "Guardar";
	$field_obligacion['Estado'] = "PR";
	$field_obligacion['CodOrganismo'] = $_SESSION["ORGANISMO_ACTUAL"];
	$field_obligacion['IngresadoPor'] = $_SESSION["CODPERSONA_ACTUAL"];
	$field_obligacion['NomIngresadoPor'] = $_SESSION["NOMBRE_USUARIO_ACTUAL"];
	$field_obligacion['CodCentroCosto'] = $_PARAMETRO["CCOSTOCXP"];
	$field_obligacion['FechaFactura'] = date("Y-m-d");
	$field_obligacion['FechaRegistro'] = date("Y-m-d");
	$field_obligacion['FechaDocumento'] = date("Y-m-d");
	$field_obligacion['FechaRecepcion'] = date("Y-m-d");
	$field_obligacion['FechaVencimiento'] = date("Y-m-d");
	$field_obligacion['FechaProgramada'] = date("Y-m-d");
	$field_obligacion['FlagGenerarPago'] = "S";
	$field_obligacion['FlagCompromiso'] = "N";
	$field_obligacion['FlagPresupuesto'] = "S";
	$field_obligacion['FlagDistribucionManual'] = "N";
	$disabled_impuesto = "disabled";
	$disabled_documento = "disabled";
	$disabled_distribucion = "disabled";
	$disabled_anular = "disabled";
	$dFlagCompromiso = "disabled";
	$mostrarTabDistribucion = "mostrarTabDistribucionObligacion();";
}
elseif ($opcion == "modificar" || $opcion == "ver" || $opcion == "revisar" || $opcion == "conformar" || $opcion == "aprobar" || $opcion == "anular") {
	if ($origen == "ap_registro_compra_lista") {
		list($Periodo, $SistemaFuente, $Secuencia) = split("[.]", $registro);
		//	consulto datos generales
		$sql = "SELECT
					o.*,
					p1.DocFiscal,
					p1.NomCompleto,
					p1.Busqueda,
					pv.DiasPago,
					p2.NomCompleto AS NomProveedorPagar,
					td.FlagProvision,
					td.CodVoucher
				FROM
					ap_registrocompras rc
					INNER JOIN ap_obligaciones o ON (rc.CodProveedor = o.CodProveedor AND
													 rc.CodTipoDocumento = o.CodTipoDocumento AND
													 rc.NroDocumento = o.NroDocumento)
					INNER JOIN mastpersonas p1 ON (o.CodProveedor = p1.CodPersona)
					INNER JOIN ap_tipodocumento td ON (o.CodTipoDocumento = td.CodTipoDocumento)
					LEFT JOIN mastproveedores pv ON (p1.CodPersona = pv.CodProveedor)
					LEFT JOIN mastpersonas p2 ON (o.CodProveedorPagar = p2.CodPersona)
				WHERE
					rc.Periodo = '".$Periodo."' AND
					rc.SistemaFuente = '".$SistemaFuente."' AND
					rc.Secuencia = '".$Secuencia."'";
		$query_obligacion = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_obligacion)) $field_obligacion = mysql_fetch_array($query_obligacion);
	}
	else {
		list($CodOrganismo, $CodProveedor, $CodTipoDocumento, $NroDocumento) = split("[.]", $registro);
		//	consulto datos generales
		$sql = "SELECT
					o.*,
					p1.DocFiscal,
					p1.NomCompleto,
					p1.Busqueda,
					pv.DiasPago,
					p2.NomCompleto AS NomProveedorPagar,
					td.FlagProvision,
					td.CodVoucher,
					p3.NomCompleto AS NomIngresadoPor,
					p4.NomCompleto AS NomRevisadoPor,
					p5.NomCompleto AS NomAprobadoPor,
					p6.NomCompleto AS NomConformadoPor
				FROM
					ap_obligaciones o
					INNER JOIN mastpersonas p1 ON (o.CodProveedor = p1.CodPersona)
					INNER JOIN ap_tipodocumento td ON (o.CodTipoDocumento = td.CodTipoDocumento)
					LEFT JOIN mastproveedores pv ON (p1.CodPersona = pv.CodProveedor)
					LEFT JOIN mastpersonas p2 ON (o.CodProveedorPagar = p2.CodPersona)
					LEFT JOIN mastpersonas p3 ON (o.IngresadoPor = p3.CodPersona)
					LEFT JOIN mastpersonas p4 ON (o.RevisadoPor = p4.CodPersona)
					LEFT JOIN mastpersonas p5 ON (o.AprobadoPor = p5.CodPersona)
					LEFT JOIN mastpersonas p6 ON (o.ConformadoPor = p6.CodPersona)
				WHERE
					o.CodProveedor = '".$CodProveedor."' AND
					o.CodTipoDocumento = '".$CodTipoDocumento."' AND
					o.NroDocumento = '".$NroDocumento."'";
		$query_obligacion = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_obligacion)) $field_obligacion = mysql_fetch_array($query_obligacion);
	}
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Obligaci&oacute;n";
		$accion = "modificar";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
		$label_submit = "Modificar";
		$disabled_anular = "disabled";
		$mostrarTabDistribucion = "mostrarTabDistribucionObligacion();";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Obligaci&oacute;n";
		$disabled_ver = "disabled";
		$display_ver = "display:none;";
		$display_submit = "display:none;";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
		$dFlagCompromiso = "disabled";
		$disabled_impuesto = "disabled";
		$disabled_documento = "disabled";
		$disabled_distribucion = "disabled";
		$disabled_anular = "disabled";
		$mostrarTabDistribucion = "mostrarTab('tab', 4, 5);";
	}
	
	elseif ($opcion == "revisar") {
	
	
 	 $field_obligacion['RevisadoPor'] = $_SESSION["CODPERSONA_ACTUAL"];
	
	  $field_obligacion['NomRevisadoPor'] = $_SESSION["NOMBRE_USUARIO_ACTUAL"];
	
	  $field_obligacion['FechaRevision'] = date("Y-m-d");

		$titulo = "Revisar Obligaci&oacute;n";
		$accion = "revisar";
		$label_submit = "Revisar";
		$disabled_ver = "disabled";
		$display_ver = "display:none;";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
		$dFlagCompromiso = "disabled";
		$disabled_impuesto = "disabled";
		$disabled_documento = "disabled";
		$disabled_distribucion = "disabled";
		$disabled_anular = "disabled";
		$mostrarTabDistribucion = "mostrarTab('tab', 4, 5);";
	}
	
	elseif ($opcion == "conformar") {
	
	
 	  $field_obligacion['ConformadoPor'] = $_SESSION["CODPERSONA_ACTUAL"];
	
	  $field_obligacion['NomConformadoPor'] = $_SESSION["NOMBRE_USUARIO_ACTUAL"];
	
	  $field_obligacion['FechaConformado'] = date("Y-m-d");

		$titulo = "Conformar Obligaci&oacute;n";
		$accion = "conformar";
		$label_submit = "Conformar";
		$disabled_ver = "disabled";
		$display_ver = "display:none;";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
		$dFlagCompromiso = "disabled";
		$disabled_impuesto = "disabled";
		$disabled_documento = "disabled";
		$disabled_distribucion = "disabled";
		$disabled_anular = "disabled";
		$mostrarTabDistribucion = "mostrarTab('tab', 4, 5);";
	}
	
	elseif ($opcion == "aprobar") {
	
		

$field_obligacion['AprobadoPor'] = $_SESSION["CODPERSONA_ACTUAL"];

  $field_obligacion['NomAprobadoPor'] = $_SESSION["NOMBRE_USUARIO_ACTUAL"];

  $field_obligacion['FechaAprobado'] = date("Y-m-d");
  
		$titulo = "Aprobar Obligaci&oacute;n";
		$accion = "aprobar";
		$label_submit = "Aprobar";
		$disabled_ver = "disabled";
		$display_ver = "display:none;";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
		$dFlagCompromiso = "disabled";
		$disabled_impuesto = "disabled";
		$disabled_documento = "disabled";
		$disabled_distribucion = "disabled";
		$disabled_anular = "disabled";
		$mostrarTabDistribucion = "mostrarTab('tab', 4, 5);";
	}
	
	elseif ($opcion == "anular") {
		$titulo = "Anular Obligaci&oacute;n";
		$accion = "anular";
		$label_submit = "Anular";
		$disabled_ver = "disabled";
		$display_ver = "display:none;";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
		$dFlagCompromiso = "disabled";
		$disabled_impuesto = "disabled";
		$disabled_documento = "disabled";
		$disabled_distribucion = "disabled";
		$mostrarTabDistribucion = "mostrarTab('tab', 4, 5);";
	}
	
	$disabled_documento = "disabled";
	if ($field_obligacion['FlagDistribucionManual'] != "S") {
		$disabled_distribucion = "disabled";
		$dFlagCompromiso = "disabled";
		$dFlagPresupuesto = "disabled";
		$dFlagDistribucionManual = "disabled";
	}
	if (!afectaTipoServicio($field_obligacion['CodTipoServicio'])) {
		$dFlagNoAfectoIGV = "disabled";
	}
	$FactorImpuesto = getPorcentajeIVA($field_obligacion['CodTipoServicio']);
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="document.getElementById('frmentrada').submit()">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<table width="1100" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current"><a href="#" onclick="mostrarTab('tab', 1, 5);">Informaci&oacute;n General</a></li>
            <li id="li2" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 2, 5);">Informaci&oacute;n Monetaria</a></li>
            <li id="li3" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 3, 5);">Dist. Contable y Presup.</a></li>
            <li id="li4" onclick="currentTab('tab', this);">
            	<a href="#" onclick="<?=$mostrarTabDistribucion?>">Resumen Contable y Presup.</a>
            </li>
            <li id="li5" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 5, 5);">Adelantos y Pagos Parciales</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=<?=$origen?>" method="POST" onsubmit="return obligacion(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="fCodOrganismo" id="fCodOrganismo" value="<?=$fCodOrganismo?>" />
<input type="hidden" name="fCodProveedor" id="fCodProveedor" value="<?=$fCodProveedor?>" />
<input type="hidden" name="fNomProveedor" id="fNomProveedor" value="<?=$fNomProveedor?>" />
<input type="hidden" name="fCodTipoDocumento" id="fCodTipoDocumento" value="<?=$fCodTipoDocumento?>" />
<input type="hidden" name="fCodIngresadoPor" id="fCodIngresadoPor" value="<?=$fCodIngresadoPor?>" />
<input type="hidden" name="fNomIngresadoPor" id="fNomIngresadoPor" value="<?=$fNomIngresadoPor?>" />
<input type="hidden" name="fNroDocumento" id="fNroDocumento" value="<?=$fNroDocumento?>" />
<input type="hidden" name="fCodCentroCosto" id="fCodCentroCosto" value="<?=$fCodCentroCosto?>" />
<input type="hidden" name="fNomCentroCosto" id="fNomCentroCosto" value="<?=$fNomCentroCosto?>" />
<input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />
<input type="hidden" name="fFechaDocumentod" id="fFechaDocumentod" value="<?=$fFechaDocumentod?>" />
<input type="hidden" name="fFechaDocumentoh" id="fFechaDocumentoh" value="<?=$fFechaDocumentoh?>" />
<input type="hidden" name="fReferenciaNroDocumento" id="fReferenciaNroDocumento" value="<?=$fReferenciaNroDocumento?>" />
<input type="hidden" name="fFechaRegistrod" id="fFechaRegistrod" value="<?=$fFechaRegistrod?>" />
<input type="hidden" name="fFechaRegistroh" id="fFechaRegistroh" value="<?=$fFechaRegistroh?>" />
<input type="hidden" name="FlagPagoDiferido" id="FlagPagoDiferido" value="<?=$FlagPagoDiferido?>" />
<input type="hidden" name="FactorImpuesto" id="FactorImpuesto" value="<?=$FactorImpuesto?>" />
<input type="hidden" name="Periodo" id="Periodo" value="<?=$field_obligacion['Periodo']?>" />
<input type="hidden" name="PeriodoActual" id="PeriodoActual" value="<?=substr($Ahora, 0, 7)?>" />
<input type="hidden" name="FlagProvision" id="FlagProvision" value="<?=$field_obligacion['FlagProvision']?>" />
<input type="hidden" name="CodVoucher" id="CodVoucher" value="<?=$field_obligacion['CodVoucher']?>" />

<div id="tab1" style="display:block;">
<table width="1100" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Informaci&oacute;n del Proveedor</td>
    </tr>
    <tr>
		<td class="tagForm" width="125">* Proveedor:</td>
		<td class="gallery clearfix">
        	<input type="text" id="CodProveedor" value="<?=$field_obligacion['CodProveedor']?>" disabled="disabled" style="width:100px;" />
			<input type="text" id="NomCompleto" value="<?=($field_obligacion['NomCompleto'])?>" disabled="disabled" style="width:250px;" />
			<a href="../lib/listas/listado_personas.php?filtrar=default&cod=fCodProveedor&nom=fNomProveedor&EsEmpleado=S&EsProveedor=S&EsOtros=S&ventana=selListadoObligacionPersona&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" style=" <?=$display_modificar?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td class="tagForm" width="125">Dias Pago:</td>
		<td><input type="text" id="DiasPago" style="width:50px;" value="<?=$field_obligacion['DiasPago']?>" <?=$disabled_ver?> /></td>
	</tr>
    <tr>
		<td class="tagForm">R.I.F:</td>
		<td>
        	<input type="text" id="DocFiscal" style="width:100px;" value="<?=$field_obligacion['DocFiscal']?>" disabled="disabled" />
            <input type="text" id="Busqueda" style="width:250px;" value="<?=($field_obligacion['Busqueda'])?>" disabled="disabled" />
        </td>
		<td class="tagForm">* Pagar A:</td>
		<td class="gallery clearfix">
        	<input type="text" id="CodProveedorPagar" value="<?=$field_obligacion['CodProveedorPagar']?>" maxlength="6" style="width:50px;" onchange="getDescripcionLista('accion=getDescripcionPersona&flagproveedor=S&flagempleado=S&flagotros=S', this, 'nompagara');" disabled="disabled" />
			<input type="text" id="NomProveedorPagar" value="<?=($field_obligacion['NomProveedorPagar'])?>" style="width:250px;" disabled="disabled" />
			<a href="../lib/listas/listado_personas.php?filtrar=default&cod=CodProveedorPagar&nom=NomProveedorPagar&EsEmpleado=S&EsProveedor=S&EsOtros=S&iframe=true&width=950&height=525" rel="prettyPhoto[iframe2]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
	</tr>
	<tr>
		<td class="tagForm">* Organismo:</td>
		<td>
        	<select id="CodOrganismo" style="width:300px;" <?=$disabled_modificar?>>
            	<?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $field_obligacion['CodOrganismo'], 0)?>
            </select>
		</td>
		<td class="tagForm">* Centro Costo:</td>
		<td class="gallery clearfix">
        	<input type="text" id="CodCentroCosto" value="<?=$field_obligacion['CodCentroCosto']?>" style="width:50px;" onchange="getDescripcionLista('accion=getDescripcionCCosto', this, 'nomccosto');" disabled="disabled" />
			<input type="hidden" id="NomCentroCosto" value="<?=($field_obligacion['CodCentroCosto'])?>" />
			<a href="../lib/listas/listado_centro_costos.php?filtrar=default&cod=CodCentroCosto&nom=NomCentroCosto&iframe=true&width=950&height=525" rel="prettyPhoto[iframe3]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
	</tr>
    <tr>
		<td class="tagForm">* Tipo de Documento:</td>
		<td>
        	<select id="CodTipoDocumento" style="width:300px;" onchange="getOptionsSelect(this.value, 'tipo_servicio_documento', 'CodTipoServicio', true); afectaTipoServicioObligacion($('#CodTipoServicio').val());" <?=$disabled_modificar?>>
                <?
				if ($opcion == "nuevo") {
					loadSelectTipoDocumentoObligacion($field_obligacion['CodTipoDocumento'], 0);
				} else {
					loadSelect("ap_tipodocumento", "CodTipoDocumento", "Descripcion", $field_obligacion['CodTipoDocumento'], 1);
				}
                
				?>
            </select>
        </td>
		<td class="tagForm">Nro. Registro:</td>
		<td><input type="text" id="NroRegistro" value="<?=$field_obligacion['NroRegistro']?>" style="width:100px;" class="codigo" disabled="disabled" /></td>
	</tr>
    <tr>
		<td class="tagForm">* Nro. Control:</td>
		<td><input type="text" id="NroDocumento" maxlength="20" style="width:150px;" value="<?=$field_obligacion['NroDocumento']?>" <?=$disabled_modificar?> /></td>
		<td class="tagForm">* Nro. Factura:</td>
		<td><input type="text" id="NroControl" maxlength="20" value="<?=$field_obligacion['NroControl']?>" style="width:150px;" <?=$disabled_ver?> /></td>
	</tr>
    <tr>
		<td height="22" class="tagForm">Estado:</td>
		<td>
       	  <input type="hidden" id="Estado" value="<?=$field_obligacion['Estado']?>" />
        	<input type="text" style="width:100px;" class="codigo" value="<?=printValores("ESTADO-OBLIGACIONES", $field_obligacion['Estado'])?>" disabled="disabled" />
		</td>
        <td class="tagForm">Fecha Factura:</td>
		<td><input type="text" id="FechaFactura" value="<?=formatFechaDMA($field_obligacion['FechaFactura'])?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> /></td>
	</tr>
</table>

<table width="1100" class="tblForm">
    <tr>
		<td width="50%" valign="top">
        	<table width="100%">
            	<tr><td colspan="2" class="divFormCaption">Fechas del Documento</td></tr>
            	<tr>
                	<td class="tagForm" width="125">Registro:</td>
                    <td><input type="text" id="FechaRegistro" value="<?=formatFechaDMA($field_obligacion['FechaRegistro'])?>" style="width:100px;" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> /></td>
                </tr>
            	<tr>
                	<td class="tagForm">Emisi&oacute;n:</td>
                    <td><input type="text" id="FechaDocumento" value="<?=formatFechaDMA($field_obligacion['FechaDocumento'])?>" style="width:100px;" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> /></td>
                </tr>
            	<tr>
                	<td class="tagForm">Recepci&oacute;n:</td>
                    <td><input type="text" id="FechaRecepcion" value="<?=formatFechaDMA($field_obligacion['FechaRecepcion'])?>" style="width:100px;" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> /></td>
                </tr>
            	<tr>
                	<td class="tagForm">Vencimiento:</td>
                    <td><input type="text" id="FechaVencimiento" value="<?=formatFechaDMA($field_obligacion['FechaVencimiento'])?>" style="width:100px;" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> /></td>
                </tr>
            	<tr>
                	<td class="tagForm">Prog. Pago:</td>
                    <td><input type="text" id="FechaProgramada" value="<?=formatFechaDMA($field_obligacion['FechaProgramada'])?>" style="width:100px;" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> /></td>
                </tr>
            </table>
        </td>
        
		<td width="50%" valign="top">
        	<table width="100%">
            	<tr><td colspan="2" class="divFormCaption">Informaci&oacute;n Adicional</td></tr>
            	<tr>
                	<td class="tagForm" width="125">* Tipo de Servicio:</td>
                    <td>
                        <select id="CodTipoServicio" style="width:150px;" onchange="afectaTipoServicioObligacion(this.value);" <?=$disabled_ver?>>
                            <?=loadSelect("masttiposervicio", "CodTipoServicio", "Descripcion", $field_obligacion['CodTipoServicio'], 0)?>
                        </select>
                    </td>
                </tr>
            	<tr>
                	<td class="tagForm">* Tipo de Pago:</td>
                    <td>
                        <select id="CodTipoPago" style="width:150px;" <?=$disabled_ver?>>
                            <?=loadSelect("masttipopago", "CodTipoPago", "TipoPago", $field_obligacion['CodTipoPago'], 0)?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="tagForm">Ingresado Por:</td>
                    <td>
                        <input type="hidden" id="IngresadoPor" value="<?=$field_obligacion['IngresadoPor']?>" />
                        <input type="text" id="NomIngresadoPor" value="<?=($field_obligacion['NomIngresadoPor'])?>" style="width:245px;" class="disabled" disabled="disabled" />
                        <input type="text" id="FechaPreparacion" value="<?=formatFechaDMA($field_obligacion['FechaPreparacion'])?>" style="width:55px;" class="disabled" />
                    </td>
                </tr>
                <tr>
                    <td class="tagForm">Revisado Por:</td>
                    <td>
                        <input type="hidden" id="RevisadoPor" value="<?=$field_obligacion['RevisadoPor']?>" />
                        <input type="text" id="NomRevisadoPor" value="<?=($field_obligacion['NomRevisadoPor'])?>" style="width:245px;" class="disabled" disabled="disabled" />
                        <input type="text" id="FechaRevision" value="<?=formatFechaDMA($field_obligacion['FechaRevision'])?>" style="width:55px;" class="disabled" />
                    </td>
                </tr>
                <tr>
                    <td class="tagForm">Conformado Por:</td>
                    <td>
                        <input type="hidden" id="ConformadoPor" value="<?=$field_obligacion['ConformadoPor']?>" />
                        <input type="text" id="NomConformadoPor" value="<?=($field_obligacion['NomConformadoPor'])?>" style="width:245px;" class="disabled" disabled="disabled" />
                        <input type="text" id="FechaConformado" value="<?=formatFechaDMA($field_obligacion['FechaConformado'])?>" style="width:55px;" class="disabled" />
                    </td>
                </tr>
                <tr>
                    <td class="tagForm">Aprobador CxP:</td>
                    <td>
                        <input type="hidden" id="AprobadoPor" value="<?=$field_obligacion['AprobadoPor']?>" />
                        <input type="text" id="NomAprobadoPor" value="<?=($field_obligacion['NomAprobadoPor'])?>" style="width:245px;" class="disabled" disabled="disabled" />
                        <input type="text" id="FechaAprobado" value="<?=formatFechaDMA($field_obligacion['FechaAprobado'])?>" style="width:55px;" class="disabled" />
                    </td>
                </tr>
            </table>
        </td>
	</tr>
</table>

<table width="1100" class="tblForm">
	<tr>
		<td class="tagForm" width="125">Glosa del Voucher:</td>
		<td><input type="text" id="Comentarios" value="<?=($field_obligacion['Comentarios'])?>" style="width:95%;" <?=$disabled_ver?> /></td>
	</tr>
	<tr>
		<td class="tagForm">Comentarios Adicional:</td>
		<td><textarea id="ComentariosAdicional" style="width:95%; height:45px;" <?=$disabled_ver?>><?=($field_obligacion['ComentariosAdicional'])?></textarea></td>
	</tr>
	<tr>
		<td class="tagForm">Raz&oacute;n Anulaci&oacute;n:</td>
		<td><input type="text" id="MotivoAnulacion"value="<?=($field_obligacion['MotivoAnulacion'])?>" style="width:95%;" <?=$disabled_anular?> /></td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input type="text" size="30" class="disabled" value="<?=$field_obligacion['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" class="disabled" value="<?=$field_obligacion['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center>
<input type="submit" value="<?=$label_submit?>" style=" <?=$display_submit?>" />
<input type="button" value="Cancelar" onclick="this.form.submit();" />
</center>
<div style="width:1100px" class="divMsj">Campos Obligatorios *</div>
</div>

<div id="tab2" style="display:none;">
<table width="1100" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Informaci&oacute;n Monetaria</td>
    </tr>
    <tr>
		<td class="tagForm" width="150">Ref. Doc. Interno:</td>
		<td><input type="text" id="ReferenciaDocumento" style="width:195px;" value="<?=$field_obligacion['ReferenciaTipoDocumento']?>-<?=$field_obligacion['ReferenciaNroDocumento']?>" disabled="disabled" /></td>
		<td class="tagForm" width="150">Monto Afecto:</td>
		<td>
        	<input type="text" id="MontoAfecto" value="<?=number_format($field_obligacion['MontoAfecto'], 2, ',', '.')?>" style="width:150px; text-align:right;" class="disabled" disabled="disabled" />
        </td>
	</tr>
    <tr>
		<?php $pe=0; if($field_obligacion['NroCuenta']){$pe=1;}// cambiar $pe=1?>

		<td class="tagForm">* Cuenta Bancaria:</td>
		<td>
        	<select id="NroCuenta" style="width:200px;" <?=$disabled_ver?>>
                <?=loadSelect("ap_ctabancaria", "NroCuenta", "NroCuenta", $field_obligacion['NroCuenta'], $pe)?>
            </select>
		<?php //echo $field_obligacion['NroCuenta']; ?>
        </td>
		<td class="tagForm">Monto No Afecto:</td>
		<td>
        	<input type="text" id="MontoNoAfecto" value="<?=number_format($field_obligacion['MontoNoAfecto'], 2, ',', '.')?>" style="width:150px; text-align:right;" class="disabled" disabled="disabled" />
        </td>
	</tr>
    <tr>
		<td class="tagForm">&nbsp;</td>
		<td>
        	<input type="checkbox" id="FlagCajaChica" <?=chkFlag($field_obligacion['FlagCajaChica'])?> <?=$disabled_ver?> /> Pago con Caja Chica (Efectivo)
        </td>
		<td class="tagForm">Impuesto:</td>
		<td>
        	<input type="text" id="MontoImpuesto" value="<?=number_format($field_obligacion['MontoImpuesto'], 2, ',', '.')?>" style="width:150px; text-align:right;" class="disabled" onfocus="numeroFocus(this);" onblur="numeroBlur(this);" onchange="cambiar_monto_impuesto();" />
        </td>
	</tr>
    <tr>
		<td class="tagForm">&nbsp;</td>
		<td>
        	<input type="checkbox" id="FlagPagoIndividual" <?=chkFlag($field_obligacion['FlagPagoIndividual'])?> <?=$disabled_ver?> /> Preparar Pago Individual
        </td>
		<td class="tagForm">Otros Impuestos/Retenciones:</td>
		<td>
        	<input type="text" id="MontoImpuestoOtros" value="<?=number_format($field_obligacion['MontoImpuestoOtros'], 2, ',', '.')?>" style="width:150px; text-align:right;" class="disabled" disabled="disabled" />
        </td>
	</tr>
    <tr>
		<td class="tagForm">&nbsp;</td>
		<td>
        	<input type="checkbox" id="FlagGenerarPago" <?=chkFlag($field_obligacion['FlagGenerarPago'])?> <?=$disabled_ver?> /> Preparar Pago (Autom&aacute;tico)
        </td>
		<td class="tagForm"><strong>Total Obligaci&oacute;n:</strong></td>
		<td>
        	<input type="text" id="MontoObligacion" value="<?=number_format($field_obligacion['MontoObligacion'], 2, ',', '.')?>" style="width:150px; text-align:right; font-size:12px; font-weight:bold;" class="codigo" disabled="disabled" />
        </td>
	</tr>
    <tr>
		<td class="tagForm">&nbsp;</td>
		<td>
        	<input type="checkbox" id="FlagPagoDiferido" <?=chkFlag($field_obligacion['FlagPagoDiferido'])?> <?=$disabled_ver?> /> Diferir el Pago
        </td>
		<td class="tagForm">Adelanto:</td>
		<td>
        	<input type="text" id="MontoAdelanto" value="<?=number_format($field_obligacion['MontoAdelanto'], 2, ',', '.')?>" style="width:150px; text-align:right;" class="disabled" disabled="disabled" />
        </td>
	</tr>
    <tr>
		<td class="tagForm">&nbsp;</td>
		<td>
        	<input type="checkbox" id="FlagDiferido" <?=chkFlag($field_obligacion['FlagDiferido'])?> <?=$disabled_ver?> /> Considerarlo como Diferido
        </td>
		<td class="tagForm"><strong>Total a Pagar:</strong></td>
		<td>
        	<?
			$MontoPagar = $field_obligacion['MontoObligacion'] - $field_obligacion['MontoAdelanto'];
			?>
        	<input type="text" id="MontoPagar" value="<?=number_format($MontoPagar, 2, ',', '.')?>" style="width:150px; text-align:right; font-size:12px; font-weight:bold;" class="codigo" disabled="disabled" />
        </td>
	</tr>
    <tr>
		<td class="tagForm">&nbsp;</td>
		<td>
        	<input type="checkbox" id="FlagAfectoIGV" <?=chkFlag($field_obligacion['FlagAfectoIGV'])?> <?=$disabled_ver?> /> Afecto a Defracción de IGV
        </td>
		<td class="tagForm">Pagos Parciales:</td>
		<td><input type="text" id="MontoPagoParcial" value="<?=number_format($field_obligacion['MontoPagoParcial'], 2, ',', '.')?>" style="width:150px; text-align:right;" class="disabled" disabled="disabled" /></td>
	</tr>
    <tr>
		<td class="tagForm">&nbsp;</td>
		<td>
        	<input type="checkbox" id="FlagCompromiso" <?=chkFlag($field_obligacion['FlagCompromiso'])?> <?=$dFlagCompromiso?> onchange="FlagCompromisoObligacion(this.checked);" /> Refiere Compromiso
        </td>
		<td class="tagForm"><strong>Saldo Pendiente:</strong></td>
		<td>
        	<?
			$MontoPendiente = $MontoPagar - $field_obligacion['MontoPagoParcial'];
			?>
        	<input type="text" id="MontoPendiente" value="<?=number_format($MontoPendiente, 2, ',', '.')?>" style="width:150px; text-align:right; font-size:12px; font-weight:bold;" onfocus="numeroFocus(this);" onblur="numeroBlur(this);" disabled="disabled" />
        </td>
	</tr>
    <tr>
		<td class="tagForm">&nbsp;</td>
		<td>
        	<input type="checkbox" id="FlagPresupuesto" <?=chkFlag($field_obligacion['FlagPresupuesto'])?> <?=$dFlagPresupuesto?> onchange="FlagPresupuestoObligacion(this.checked);" /> Afecta Presupuesto
        </td>
		<td class="tagForm">&nbsp;</td>
		<td class="tagForm">&nbsp;</td>
	</tr>
    <tr>
		<td class="tagForm">&nbsp;</td>
		<td>
        	<input type="checkbox" id="FlagDistribucionManual" <?=chkFlag($field_obligacion['FlagDistribucionManual'])?> <?=$dFlagDistribucionManual?> onchange="setObligacionPagoDirecto(this.checked);" /> Pago Directo
        </td>
		<td class="tagForm">&nbsp;</td>
		<td class="tagForm">&nbsp;</td>
	</tr>
</table>
</div>
</form>

<div id="tab3" style="display:none;">
<table width="1100" align="center">
	<tr>
    	<td valign="top">
        	<form name="frm_impuesto" id="frm_impuesto">
            <input type="hidden" id="sel_impuesto" />
            <div style="width:500px" class="divFormCaption">Retenciones/Impuestos</div>
            <table width="500" class="tblBotones">
                <tr>
                    <td align="right" class="gallery clearfix">
                        <a id="aInsertarImpuesto" href="../lib/listas/listado_impuestos.php?filtrar=default&ventana=obligacion_impuestos_insertar&CodRegimenFiscal=R&iframe=true&width=1050&height=400" rel="prettyPhoto[iframe4]" style="display:none;"></a>
                        <input type="button" class="btLista" value="Insertar" id="btInsertarImpuesto" onclick="document.getElementById('aInsertarImpuesto').click();" <?=$disabled_impuesto?> />
                        <input type="button" class="btLista" value="Borrar" id="btQuitarImpuesto" onclick="quitarLineaImpuesto(this, 'impuesto');" <?=$disabled_impuesto?> />
                    </td>
                </tr>
            </table>
			<?php
			if ($field_obligacion['ReferenciaTipoDocumento'] == "NO") {
				?>
                <table><tr><td><div style="overflow:scroll; width:500px; height:150px;">
                <table width="100%" class="tblLista">
                    <thead>
                    <tr>
                        <th scope="col" width="15">&nbsp;</th>
                        <th scope="col" align="left">Retencion</th>
                        <th scope="col" width="100" align="right">Monto</th>
                    </tr>
                    </thead>
                    
                    <tbody id="lista_impuesto">
                    <?
					$sql = "SELECT
								oi.*,
								c.Descripcion
                            FROM
								ap_obligacionesimpuesto oi
								INNER JOIN pr_concepto c ON (oi.CodConcepto = c.CodConcepto)
                            WHERE
                                oi.CodProveedor = '".$field_obligacion['CodProveedor']."' AND
                                oi.CodTipoDocumento = '".$field_obligacion['CodTipoDocumento']."' AND
                                oi.NroDocumento = '".$field_obligacion['NroDocumento']."'";
                    $query_impuestos = mysql_query($sql) or die ($sql.mysql_error());
                    while ($field_impuestos = mysql_fetch_array($query_impuestos)) {	$nro_impuesto++;
                        ?>
                        <tr class="trListaBody" onclick="mClk(this, 'sel_impuesto');" id="impuesto_<?=$field_impuestos['CodImpuesto']?>">
                            <th><?=$nro_impuesto?></th>
                            <td>
                                <input type="text" value="<?=$field_impuestos['Descripcion']?>" class="cell2" readonly="readonly" />
                                <input type="hidden" name="CodImpuesto" />
                                <input type="hidden" name="CodConcepto" value="<?=$field_impuestos['CodConcepto']?>" />
                                <input type="hidden" name="Signo" value="N" />
                                <input type="hidden" name="FlagImponible" value="N" />
                                <input type="hidden" name="FlagProvision" value="P" />
                                <input type="hidden" name="CodCuenta" value="<?=$field_impuestos['CodCuenta']?>" />
                                <input type="hidden" name="MontoAfecto" value="<?=$field_impuestos['MontoAfecto']?>" />
                                <input type="hidden" name="FactorPorcentaje" value="<?=$field_impuestos['FactorPorcentaje']?>" />
                            </td>
                            <td>
                                <input type="text" name="MontoImpuesto" value="<?=number_format($field_impuestos['MontoImpuesto'], 2, ',', '.')?>" style="text-align:right;" class="cell2" readonly="readonly" />
                            </td>
                        </tr>
                        <?
                        $impuesto_total += $field_impuestos['MontoImpuesto'];
                    }
                    ?>
                    </tbody>
                    
                    <tfoot>
                    <tr>
                        <th scope="col" colspan="2">&nbsp;</th>
                        <th scope="col">
                            <input type="text" id="impuesto_total" value="<?=number_format($impuesto_total, 2, ',', '.')?>" style="text-align:right; font-weight:bold;" class="cell2" readonly="readonly" />
                        </th>
                    </tr>
                    </tfoot>
                </table>
                </div></td></tr></table>
                <?
			} else {
				?>
                <table><tr><td><div style="overflow:scroll; width:500px; height:150px;">
                <table width="100%" class="tblLista">
                    <thead>
                    <tr>
                        <th scope="col" width="15">&nbsp;</th>
                        <th scope="col" align="left">Impuesto</th>
                        <th scope="col" width="100" align="right">Monto Afecto</th>
                        <th scope="col" width="50" align="right">Factor</th>
                        <th scope="col" width="100" align="right">Monto</th>
                    </tr>
                    </thead>
                    
                    <tbody id="lista_impuesto">
                    <?
                    $sql = "SELECT
                                oi.*,
                                i.Descripcion,
                                i.FlagImponible,
                                i.FlagProvision,
                                i.Signo,
                                i.CodCuenta
                            FROM
                                ap_obligacionesimpuesto oi
                                INNER JOIN mastimpuestos i ON (oi.CodImpuesto = i.CodImpuesto)
                            WHERE
                                oi.CodProveedor = '".$CodProveedor."' AND
                                oi.CodTipoDocumento = '".$CodTipoDocumento."' AND
                                oi.NroDocumento = '".$NroDocumento."'";
                    $query_impuestos = mysql_query($sql) or die ($sql.mysql_error());
                    while ($field_impuestos = mysql_fetch_array($query_impuestos)) {	$nro_impuesto++;
                        ?>
                        <tr class="trListaBody" onclick="mClk(this, 'sel_impuesto');" id="impuesto_<?=$field_impuestos['CodImpuesto']?>">
                            <th><?=$nro_impuesto?></th>
                            <td>
                                <input type="text" value="<?=$field_impuestos['Descripcion']?>" class="cell2" readonly="readonly" />
                                <input type="hidden" name="CodImpuesto" value="<?=$field_impuestos['CodImpuesto']?>" />
                                <input type="hidden" name="CodConcepto" />
                                <input type="hidden" name="Signo" value="<?=$field_impuestos['Signo']?>" />
                                <input type="hidden" name="FlagImponible" value="<?=$field_impuestos['FlagImponible']?>" />
                                <input type="hidden" name="FlagProvision" value="<?=$field_impuestos['FlagProvision']?>" />
                                <input type="hidden" name="CodCuenta" value="<?=$field_impuestos['CodCuenta']?>" />
                            </td>
                            <td>
                                <input type="text" name="MontoAfecto" value="<?=number_format($field_impuestos['MontoAfecto'], 2, ',', '.')?>" style="text-align:right;" class="cell2" readonly="readonly" />
                            </td>
                            <td>
                                <input type="text" name="FactorPorcentaje" value="<?=number_format($field_impuestos['FactorPorcentaje'], 2, ',', '.')?>" style="text-align:right;" class="cell2" readonly="readonly" />
                            </td>
                            <td>
                                <input type="text" name="MontoImpuesto" value="<?=number_format($field_impuestos['MontoImpuesto'], 2, ',', '.')?>" style="text-align:right;" class="cell2" readonly="readonly" />
                            </td>
                        </tr>
                        <?
                        $impuesto_total += $field_impuestos['MontoImpuesto'];
                    }
                    ?>
                    </tbody>
                    
                    <tfoot>
                    <tr>
                        <th scope="col" colspan="4">&nbsp;</th>
                        <th scope="col">
                            <input type="text" id="impuesto_total" value="<?=number_format($impuesto_total, 2, ',', '.')?>" style="text-align:right; font-weight:bold;" class="cell2" readonly="readonly" />
                        </th>
                    </tr>
                    </tfoot>
                </table>
                </div></td></tr></table>
                <?
			}
			?>
            <input type="hidden" id="nro_impuesto" value="<?=$nro_impuesto?>" />
            <input type="hidden" id="can_impuesto" value="<?=$nro_impuesto?>" />
            </form>
        </td>
        
        <td valign="top">
        	<form name="frm_documento" id="frm_documento">
            <input type="hidden" id="sel_documento" />
            <div style="width:100%" class="divFormCaption">Documentos Relacionados</div>
            <table width="100%" class="tblBotones">
                <tr>
                    <td align="right">
                        <input type="button" class="btLista" value="Insertar" id="btInsertarDocumento" onclick="window.open('../lib/listas/listado_documentos_obligaciones.php?CodProveedor='+$('#CodProveedor').val()+'&CodOrganismo='+$('#CodOrganismo').val(), 'listado_documentos_obligaciones', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=525, width=1050, left=50, top=50, resizable=yes');" <?=$disabled_documento?> />
                        <input type="button" class="btLista" value="Borrar" id="btQuitarDocumento" onclick="quitarLineaObligacionDocumento(this, 'documento');" <?=$disabled_documento?> />
                    </td>
                </tr>
            </table>
            
            <table><tr><td><div style="overflow:scroll; width:590px; height:150px;">
            <table width="1500" class="tblLista">
            	<thead>
                <tr>
                    <th width="15" scope="col">&nbsp;</th>
                    <th width="75" scope="col">Clasificacion</th>
                    <th width="125" scope="col">Doc. Referencia</th>
                    <th width="100" scope="col">Fecha</th>
                    <th width="100" scope="col">O.C / O.S</th>
                    <th width="100" scope="col" align="right">Monto Total</th>
                    <th width="100" scope="col" align="right">Monto Afecto</th>
                    <th width="100" scope="col" align="right">Impuesto</th>
                    <th width="100" scope="col" align="right">Monto No Afecto</th>
                    <th scope="col" align="left">Comentarios</th>
                </tr>
                </thead>
                
                <tbody id="lista_documento">
                <?
			$sql = "SELECT *
						FROM ap_documentos
						WHERE
							CodProveedor = '".$field_obligacion['CodProveedor']."' AND
							ObligacionTipoDocumento = '".$field_obligacion['CodTipoDocumento']."' AND
							ObligacionNroDocumento = '".$field_obligacion['NroDocumento']."'";
				$query_documentos = mysql_query($sql) or die ($sql.mysql_error());	$nro_documento = 0;
				while ($field_documentos = mysql_fetch_array($query_documentos)) {	$nro_documento++;
					$iddoc = $field_documentos['ReferenciaTipoDocumento']."|".$field_documentos['ReferenciaNroDocumento']."|".$field_documentos['DocumentoClasificacion']."|".$field_documentos['DocumentoReferencia'];
					if ($field_documentos['ReferenciaTipoDocumento'] == "OC") $clasificacion = "O.Compra"; else $clasificacion = "O.Servicio";
					?>
                    <tr class="trListaBody" id="documento_<?=$iddoc?>">
                    	<th><?=$nro_documento?></th>
                        <td>
                        	<input type="text" value="<?=$clasificacion?>" class="cell2" readonly="readonly" />
                            <input type="hidden" name="Porcentaje" />
                        	<input type="hidden" name="DocumentoClasificacion" value="<?=$field_documentos['DocumentoClasificacion']?>" />
                        </td>
                        <td>
                        	<input type="text" name="DocumentoReferencia" value="<?=$field_documentos['DocumentoReferencia']?>" style="text-align:center;" class="cell2" readonly="readonly" />
                        </td>
                        <td>
                        	<input type="text" name="Fecha" value="<?=formatFechaDMA($field_documentos['Fecha'])?>" style="text-align:center;" class="cell2" readonly="readonly" />
                        </td>
                        <td>
                            <input type="text" name="ReferenciaTipoDocumento" value="<?=$field_documentos['ReferenciaTipoDocumento']?>" style="width:15%;" class="cell2" readonly="readonly" />
                            <input type="text" name="ReferenciaNroDocumento" value="<?=$field_documentos['ReferenciaNroDocumento']?>" style="width:70%;" class="cell2" readonly="readonly" />
                        </td>
                        <td>
                        	<input type="text" name="MontoTotal" value="<?=number_format($field_documentos['MontoTotal'], 2, ',', '.')?>" style="text-align:right;" class="cell2" readonly="readonly" />
                        </td>
                        <td>
                        	<input type="text" name="MontoAfecto" value="<?=number_format($field_documentos['MontoAfecto'], 2, ',', '.')?>" style="text-align:right;" class="cell2" readonly="readonly" />
                        </td>
                        <td>
                        	<input type="text" name="MontoImpuestos" value="<?=number_format($field_documentos['MontoImpuestos'], 2, ',', '.')?>" style="text-align:right;" class="cell2" readonly="readonly" />
                        </td>
                        <td>
                        	<input type="text" name="MontoNoAfecto" value="<?=number_format($field_documentos['MontoNoAfecto'], 2, ',', '.')?>" style="text-align:right;" class="cell2" readonly="readonly" />
                        </td>
                        <td>
                        	<input type="text" name="Comentarios" value="<?=$field_documentos['Comentarios']?>" class="cell2" readonly="readonly" />
                        </td>
					</tr>
                    <?
					$documento_afecto += $field_documentos['MontoAfecto'];
					$documento_noafecto += $field_documentos['MontoNoAfecto'];
					$documento_impuesto += $field_documentos['MontoImpuestos'];
				}
				//$documento_impuesto = $documento_afecto * $FactorImpuesto / 100;
				$documento_total = $documento_afecto + $documento_noafecto + $documento_impuesto;
				?>
                </tbody>
                
                <tfoot id="foot_documento">
                <tr>
                    <th scope="col" colspan="5">&nbsp;</th>
                    <th scope="col">
                       	<input type="text" id="documento_total" value="<?=number_format($documento_total, 2, ',', '.')?>" style="text-align:right; font-weight:bold;" class="cell2" readonly="readonly" />
                    </th>
                    <th scope="col">
                       	<input type="text" id="documento_afecto" value="<?=number_format($documento_afecto, 2, ',', '.')?>" style="text-align:right; font-weight:bold;" class="cell2" readonly="readonly" />
                    </th>
                    <th scope="col">
                       	<input type="text" id="documento_impuesto" value="<?=number_format($documento_impuesto, 2, ',', '.')?>" style="text-align:right; font-weight:bold;" class="cell2" readonly="readonly" />
                    </th>
                    <th scope="col">
                       	<input type="text" id="documento_noafecto" value="<?=number_format($documento_noafecto, 2, ',', '.')?>" style="text-align:right; font-weight:bold;" class="cell2" readonly="readonly" />
                    </th>
                </tr>
                </tfoot>
            </table>
            </div></td></tr></table>
            <input type="hidden" id="nro_documento" value="<?=$nro_documento?>" />
            <input type="hidden" id="can_documento" value="<?=$nro_documento?>" />
            </form>
        </td>
    </tr>
	<tr>
    	<td valign="top" colspan="2">
        	<form name="frm_distribucion" id="frm_distribucion">
            <input type="hidden" id="sel_distribucion" />
            <div style="width:1100px" class="divFormCaption">Distribuci&oacute;n</div>
            <table width="1100" class="tblBotones">
                <tr>
                    <td class="gallery clearfix">
                        <a id="aSelPartida" href="../lib/listas/listado_clasificador_presupuestario_disponible.php?iframe=true&width=1050&height=500" rel="prettyPhoto[iframe5]" style="display:none;"></a>
                        <a id="aSelCuenta" href="../lib/listas/listado_plan_cuentas.php?filtrar=default&cod=CodCuenta&nom=NomCuenta&ventana=selListadoLista&seldetalle=sel_distribucion&iframe=true&width=1050&height=500" rel="prettyPhoto[iframe6]" style="display:none;"></a>
                        <a id="aSelCCosto" href="../lib/listas/listado_centro_costos.php?filtrar=default&cod=CodCentroCosto&nom=CodCentroCosto&ventana=selListadoLista&seldetalle=sel_distribucion&iframe=true&width=1050&height=500" rel="prettyPhoto[iframe7]" style="display:none;"></a>
                        <a id="aSelPersona" href="../lib/listas/listado_personas.php?filtrar=default&cod=CodPersona&nom=NomPersona&ventana=selListadoLista&seldetalle=sel_distribucion&iframe=true&width=1050&height=500" rel="prettyPhoto[iframe8]" style="display:none;"></a>
                        <input type="button" class="btLista" id="btSelPartida" value="Sel. Partida" onclick="abrirListadoPartidasDisponiblesObligacion();" <?=$disabled_distribucion?> />
                        <input type="button" class="btLista" id="btSelCuenta" value="Sel. Cuenta" onclick="validarAbrirLista('sel_distribucion', 'aSelCuenta');" <?=$disabled_distribucion?> />
                        <input type="button" class="btLista" id="btSelCCosto" value="Sel. C.Costo" onclick="validarAbrirLista('sel_distribucion', 'aSelCCosto');" <?=$disabled_distribucion?> />
                        <input type="button" class="btLista" id="btSelPersona" value="Sel. Persona" onclick="validarAbrirLista('sel_distribucion', 'aSelPersona');" <?=$disabled_distribucion?> />
                        <input type="button" class="btLista" id="btSelActivo" value="Sel. Activo" disabled="disabled" />
                    </td>
                    <td align="right">
                        <input type="button" class="btLista" id="btInsertarDistribucion" value="Insertar" onclick="insertarLinea2(this, 'obligacion_distribucion_insertar', 'distribucion', true, 'CodTipoServicio='+$('#CodTipoServicio').val()+'&CodCentroCosto='+$('#CodCentroCosto').val()+'&FlagPresupuesto='+$('#FlagPresupuesto').attr('checked'));" <?=$disabled_distribucion?> />
                        <input type="button" class="btLista" id="btQuitarDistribucion" value="Quitar" onclick="quitarLineaDistribucion(this, 'distribucion');" <?=$disabled_distribucion?> />
                    </td>
                </tr>
            </table>
            
            <table><tr><td><div style="overflow:scroll; width:1100px; height:250px;">
            <table width="1950" class="tblLista" id="tabla_distribucion">
            	<thead>
                <tr>
                    <th scope="col" width="15">#</th>
                    <th scope="col" width="400" align="left">Partida</th>
                    <th scope="col" width="400" align="left">Cta. Contable</th>
                    <th scope="col" width="75">C.Costo</th>
                    <th scope="col" width="35">No Afe.</th>
                    <th scope="col" width="100">Monto</th>
                    <th scope="col" width="200">Nro. Documento</th>
                    <th scope="col" width="125">Referencia</th>
                    <th scope="col" width="300" align="left">Descripci&oacute;n</th>
                    <th scope="col" width="75">Persona</th>
                    <th scope="col" width="75">Activo</th>
                    <th scope="col" width="35">Dif.</th>
                </tr>
                </thead>
                
                <tbody id="lista_distribucion">
				<?
               $sql = "SELECT 
                            oc.*,
                            p.denominacion AS NomPartida,
                            pc.Descripcion AS NomCuenta
                        FROM 
                            ap_obligacionescuenta oc
                            LEFT JOIN pv_partida p ON (oc.cod_partida = p.cod_partida)
                            INNER JOIN ac_mastplancuenta pc ON (oc.CodCuenta = pc.CodCuenta)
                        WHERE
                            oc.CodProveedor = '".$field_obligacion['CodProveedor']."' AND
                            oc.CodTipoDocumento = '".$field_obligacion['CodTipoDocumento']."' AND
                            oc.NroDocumento = '".$field_obligacion['NroDocumento']."'";

                $query_distribucion = mysql_query($sql) or die ($sql.mysql_error());	$nro_distribucion=0;
                while ($field_distribucion = mysql_fetch_array($query_distribucion)) {
                    $nro_distribucion++;
                    ?>
                    <tr class="trListaBody" onclick="mClk(this, 'sel_distribucion');" id="distribucion_<?=$nro_distribucion?>">
                        <th><?=$nro_distribucion?></th>
                        <td align="center">
                            <input type="text" name="cod_partida" id="cod_partida_<?=$nro_distribucion?>" value="<?=$field_distribucion['cod_partida']?>" style="text-align:center; width:20%;" maxlength="12" class="cell cod_partida" onChange="getDescripcionLista2('accion=getDescripcionPartidaDisponible&CodOrganismo='+$('CodOrganismo').val(), this, $('#NomPartida_<?=$nro_distribucion?>'));" <?=$disabled_distribucion?> />
                            <input type="text" name="NomPartida" id="NomPartida_<?=$nro_distribucion?>" value="<?=($field_distribucion['NomPartida'])?>" style="width:75%;" class="cell2" readonly="readonly" />
                        </td>
                        <td align="center">
                            <input type="text" name="CodCuenta" id="CodCuenta_<?=$nro_distribucion?>" value="<?=$field_distribucion['CodCuenta']?>" maxlength="13" style=" width:20%;" class="cell" <?=$disabled_distribucion?> />
                            <input type="text" name="NomCuenta" id="NomCuenta_<?=$nro_distribucion?>" value="<?=($field_distribucion['NomCuenta'])?>" style="width:75%;" class="cell2" readonly="readonly" />
                        </td>
                        <td align="center">
                            <input type="text" name="CodCentroCosto" id="CodCentroCosto_<?=$nro_distribucion?>" value="<?=$field_distribucion['CodCentroCosto']?>" style="text-align:center;" class="cell" <?=$disabled_distribucion?> />
                            <input type="hidden" name="NomCentroCosto" id="NomCentroCosto_<?=$nro_distribucion?>" value="<?=$field_distribucion['NomCentroCosto']?>" />
                        </td>
                        <td align="center">
                            <input type="checkbox" name="FlagNoAfectoIGV" class="FlagNoAfectoIGV" <?=chkFlag($field_distribucion['FlagNoAfectoIGV'])?> onchange="actualizarMontosObligacion();" <?=$disabled_distribucion?> <?=$dFlagNoAfectoIGV?> />
                        </td>
                        <td align="center">
                            <input type="text" name="Monto" value="<?=number_format($field_distribucion['Monto'], 2, ',', '.')?>" style="text-align:right;" class="cell" onfocus="numeroFocus(this);" onblur="numeroBlur(this);" onchange="actualizarMontosObligacion();" <?=$disabled_distribucion?> />
                        </td>
                        <td align="center">
                            <input type="text" name="TipoOrden" value="<?=$field_distribucion['TipoOrden']?>" maxlength="2" style="width:10%;" class="cell" <?=$disabled_distribucion?> />
                            <input type="text" name="NroOrden" value="<?=$field_distribucion['NroOrden']?>" maxlength="100" style="width:82%;" class="cell" <?=$disabled_distribucion?> />
                        </td>
                        <td align="center">
                            <input type="text" name="Referencia" value="<?=$field_distribucion['Referencia']?>" maxlength="25" class="cell" <?=$disabled_distribucion?> />
                        </td>
                        <td align="center">
                            <input type="text" name="Descripcion" value="<?=($field_distribucion['Descripcion'])?>" maxlength="255" class="cell" <?=$disabled_distribucion?> />
                        </td>
                        <td align="center">
                            <input type="text" name="CodPersona" id="CodPersona_<?=$nro_distribucion?>" value="<?=$field_distribucion['CodPersona']?>" maxlength="6" style="text-align:center;" class="cell" <?=$disabled_distribucion?> />
                            <input type="hidden" name="NomPersona" id="NomPersona_<?=$nro_distribucion?>" value="<?=$field_distribucion['NomPersona']?>" />
                        </td>
                        <td align="center">
                            <input type="text" name="NroActivo" id="NroActivo_<?=$nro_distribucion?>" value="<?=$field_distribucion['NroActivo']?>" maxlength="15" style="text-align:center;" class="cell2" readonly="readonly" />
                        </td>
                        <td align="center">
                            <input type="checkbox" name="FlagDiferido" <?=$disabled_distribucion?> />
                        </td>
                    </tr>
                    <?
                    $distribucion_total += $field_distribucion['Monto'];
                }
                ?>
                </tbody>
                
                <tfoot id="foot_distribucion">
                <tr>
                    <th scope="col" colspan="5">&nbsp;</th>
                    <th scope="col">
                       	<input type="text" id="distribucion_total" value="<?=number_format($distribucion_total, 2, ',', '.')?>" style="text-align:right; font-weight:bold;" class="cell2" readonly="readonly" />
                    </th>
                </tr>
                </tfoot>
            </table>
            </div></td></tr></table>
            <input type="hidden" id="nro_distribucion" value="<?=$nro_distribucion?>" />
            <input type="hidden" id="can_distribucion" value="<?=$nro_distribucion?>" />
            </form>
        </td>
    </tr>
</table>
</div>

<div id="tab4" style="display:none;">
<center>
<div style="width:1100px;" class="divFormCaption">Distribuci&oacute;n Contable</div>
<div style="overflow:scroll; width:1100px; height:200px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
        <th scope="col" width="125">Cuenta</th>
        <th scope="col">Descripci&oacute;n</th>
        <th scope="col" width="100">Monto</th>
    </tr>
    </thead>
    
    <tbody id="lista_cuentas">
    <?
	$nrocuentas = 0;
	$sql = "SELECT
				do.CodCuenta,
				pc.Descripcion,
				do.Monto
			FROM
				ap_distribucionobligacion do
				INNER JOIN ac_mastplancuenta pc ON (do.CodCuenta = pc.CodCuenta)
			WHERE
				do.CodProveedor = '".$field_obligacion['CodProveedor']."' AND
				do.CodTipoDocumento = '".$field_obligacion['CodTipoDocumento']."' AND
				do.NroDocumento = '".$field_obligacion['NroDocumento']."'
			ORDER BY CodCuenta";

	$query_cuentas = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field_cuentas = mysql_fetch_array($query_cuentas)) {
		$nrocuentas++;
		?>
		<tr class="trListaBody">
			<td align="center">
				<?=$field_cuentas['CodCuenta']?>
            </td>
			<td>
				<?=$field_cuentas['Descripcion']?>
            </td>
			<td align="right">
				<?=number_format($field_cuentas['Monto'], 2, ',', '.')?>
            </td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>

<div style="width:1100px;" class="divFormCaption">Distribuci&oacute;n Presupuestaria</div>
<div style="overflow:scroll; width:1100px; height:200px;">
<form name="frm_partidas" id="frm_partidas">
<table width="100%" class="tblLista">
	<thead>
	<tr>
        <th scope="col" width="125">Partida</th>
        <th scope="col">Descripci&oacute;n</th>
        <th scope="col" width="100">Monto</th>
    </tr>
    </thead>
    
    <tbody id="lista_partidas">
    <?
	$nropartidas = 0;
	$sql = "SELECT
				do.cod_partida,
				p.denominacion,
				do.Monto
			FROM
				ap_distribucionobligacion do
				INNER JOIN pv_partida p ON (do.cod_partida = p.cod_partida)
			WHERE
				do.CodProveedor = '".$field_obligacion['CodProveedor']."' AND
				do.CodTipoDocumento = '".$field_obligacion['CodTipoDocumento']."' AND
				do.NroDocumento = '".$field_obligacion['NroDocumento']."'
			ORDER BY cod_partida";

	$query_partidas = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field_partidas = mysql_fetch_array($query_partidas)) {
		$nropartidas++;
		list($MontoAjustado, $MontoCompromiso, $MontoPendiente) = disponibilidadPartida(substr($field_obligacion['Periodo'], 0, 4), $field_obligacion['CodOrganismo'], $field_partidas['cod_partida']);
		$MontoDisponible = $MontoAjustado - $MontoComprometido;
		if ($field_obligacion['Estado'] == "PR" && $field_obligacion['NroOrden'] != "") $MontoPendiente -= $field_partidas['Monto'];
		//	valido
		if ($field_obligacion['Estado'] == "PR" && $MontoDisponible < $field_partidas['Monto']) $style = "style='font-weight:bold; background-color:#F8637D;'";
		elseif($field_obligacion['Estado'] == "PR" && $MontoDisponible < ($field_partidas['Monto'] + $MontoPendiente)) $style = "style='font-weight:bold; background-color:#FFC;'";
		else $style = "style='font-weight:bold; background-color:#D0FDD2;'";
		?>
		<tr class="trListaBody" <?=$style?>>
			<td align="center">
                <input type="hidden" name="cod_partida" value="<?=$field_partidas['cod_partida']?>" />
                <input type="hidden" name="Monto" value="<?=$field_partidas['Monto']?>" />
                <input type="hidden" name="MontoDisponible" value="<?=$MontoDisponible?>" />
                <input type="hidden" name="MontoPendiente" value="<?=$MontoPendiente?>" />
				<?=$field_partidas['cod_partida']?>
            </td>
			<td>
				<?=$field_partidas['denominacion']?>
            </td>
			<td align="right">
				<?=number_format($field_partidas['Monto'], 2, ',', '.')?>
            </td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</form>
</div>
</center>
</div>

<div id="tab5" style="display:none;"></div>
