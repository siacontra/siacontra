<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
//	si en el formulario el usuario cambio la imagen la guardo
if ($FlagCopiarImagen == "S" && $_CodEmpleado != "") {
	//	elimino la foto anterior
	if ($FotoAnterior != "") unlink($_PARAMETRO["PATHFOTO"].$FotoAnterior);
	//	copio la foto
	list($im, $_error) = copiarFoto("Foto", $_CodEmpleado, $_PARAMETRO["PATHFOTO"]);
	//	actualizo el campo foto
	$sql = "UPDATE mastpersonas SET Foto = '".$im."' WHERE CodPersona = '".$_CodPersona."'";
	$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
}
//	------------------------------------
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fCodDependencia = $_SESSION["DEPENDENCIA_ACTUAL"];
	$fEdoReg = "A";
	$fSitTra = "A";
	$fOrderBy = "CodEmpleado";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fCodOrganismo != "") { $cCodOrganismo = "checked"; $filtro.=" AND (e.CodOrganismo = '".$fCodOrganismo."')"; } else $dCodOrganismo = "disabled";
if ($fCodDependencia != "") { $cCodDependencia = "checked"; $filtro.=" AND (e.CodDependencia = '".$fCodDependencia."')"; } else $dCodDependencia = "disabled";
if ($fCodCentroCosto != "") { $cCodCentroCosto = "checked"; $filtro.=" AND (e.CodCentroCosto = '".$fCodCentroCosto."')"; } else $dCodCentroCosto = "disabled";
if ($fCodTipoNom != "") { $cCodTipoNom = "checked"; $filtro.=" AND (e.CodTipoNom = '".$fCodTipoNom."')"; } else $dCodTipoNom = "disabled";
if ($fCodTipoTrabajador != "") { $cCodTipoTrabajador = "checked"; $filtro.=" AND (e.CodTipoTrabajador = '".$fCodTipoTrabajador."')"; } else $dCodTipoTrabajador = "disabled";
if ($fEdoReg != "") { $cEdoReg = "checked"; $filtro.=" AND (p.Estado = '".$fEdoReg."')"; } else $dEdoReg = "disabled";
if ($fSitTra != "") { $cSitTra = "checked"; $filtro.=" AND (e.Estado = '".$fSitTra."')"; } else $dSitTra = "disabled";
if ($fFingresoD != "" || $fFingresoH != "") {
	$cFingreso = "checked";
	if ($fFingresoD != "") $filtro.=" AND (e.Fingreso >= '".formatFechaAMD($fFingresoD)."')";
	if ($fFingresoH != "") $filtro.=" AND (e.Fingreso <= '".formatFechaAMD($fFingresoH)."')";
} else $dFingreso = "disabled";
if ($fBuscar != "") {
	$cBuscar = "checked";
	$filtro .= " AND (e.CodEmpleado LIKE '%".$fBuscar."%' OR
					  p.NomCompleto LIKE '%".$fBuscar."%' OR
					  p.Ndocumento LIKE '%".$fBuscar."%' OR
					  pt.DescripCargo LIKE '%".$fBuscar."%' OR
					  d.Dependencia LIKE '%".$fBuscar."%')";
} else $dBuscar = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Empleados</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=empleados_lista" method="post" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
			<input type="checkbox" <?=$cCodOrganismo?> onclick="this.checked=!this.checked" />
			<select name="fCodOrganismo" id="fCodOrganismo" style="width:300px;" <?=$dCodOrganismo?> onChange="getOptionsSelect(this.value, 'dependencia_filtro', 'fCodDependencia', true, 'fCodCentroCosto');">
				<?=getOrganismos($fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right" width="125">Edo. Reg: </td>
		<td>
        	<input type="checkbox" <?=$cEdoReg?> onclick="chkFiltro(this.checked, 'fEdoReg');" />
            <select name="fEdoReg" id="fEdoReg" style="width:143px;" <?=$dEdoReg?>>
                <option value=""></option>
                <?=loadSelectGeneral("ESTADO", $fEdoReg, 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td align="right">Dependencia:</td>
		<td>
			<input type="checkbox" <?=$cCodDependencia?> onclick="chkFiltro(this.checked, 'fCodDependencia');" />
			<select name="fCodDependencia" id="fCodDependencia" style="width:300px;" onChange="getOptionsSelect(this.value, 'centro_costo', 'fCodCentroCosto', true);" <?=$dCodDependencia?>>
            	<option value="">&nbsp;</option>
				<?=getDependencias($fCodDependencia, $fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right">Sit. Tra.: </td>
		<td>
        	<input type="checkbox" <?=$cSitTra?> onclick="chkFiltro(this.checked, 'fSitTra');" />
            <select name="fSitTra" id="fSitTra" style="width:143px;" <?=$dSitTra?>>
                <option value=""></option>
                <?=loadSelectGeneral("ESTADO", $fSitTra, 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td align="right">Centro de Costo:</td>
		<td>
			<input type="checkbox" <?=$cCodCentroCosto?> onclick="chkFiltro(this.checked, 'fCodCentroCosto');" />
			<select name="fCodCentroCosto" id="fCodCentroCosto" style="width:300px;" <?=$dCodCentroCosto?>>
            	<option value="">&nbsp;</option>
				<?=loadSelect("ac_mastcentrocosto", "CodCentroCosto", "Descripcion", $fCodCentroCosto, 0)?>
			</select>
		</td>
		<td align="right">Fecha de Ingreso: </td>
		<td>
			<input type="checkbox" <?=$cFingreso?> onclick="chkFiltro_2(this.checked, 'fFingresoD', 'fFingresoH');" />
			<input type="text" name="fFingresoD" id="fFingresoD" value="<?=$fFingresoD?>" <?=$dFingreso?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFecha(this);" /> -
            <input type="text" name="fFingresoH" id="fFingresoH" value="<?=$fFingresoH?>" <?=$dFingreso?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFecha(this);" />
        </td>
	</tr>
	<tr>
		<td align="right">Tipo de Nomina:</td>
		<td>
			<input type="checkbox" <?=$cCodTipoNom?> onclick="chkFiltro(this.checked, 'fCodTipoNom');" />
			<select name="fCodTipoNom" id="fCodTipoNom" style="width:300px;" <?=$dCodTipoNom?>>
            	<option value="">&nbsp;</option>
				<?=loadSelect("tiponomina", "CodTipoNom", "Nomina", $fCodTipoNom, 0)?>
			</select>
		</td>
		<td align="right">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fBuscar');" />
			<input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:250px;" <?=$dBuscar?> />
		</td>
	</tr>
	<tr>
		<td align="right">Tipo de Trabajador:</td>
		<td>
			<input type="checkbox" <?=$cCodTipoTrabajador?> onclick="chkFiltro(this.checked, 'fCodTipoTrabajador');" />
			<select name="fCodTipoTrabajador" id="fCodTipoTrabajador" style="width:300px;" <?=$dCodTipoTrabajador?>>
            	<option value="">&nbsp;</option>
				<?=loadSelect("rh_tipotrabajador", "CodTipoTrabajador", "TipoTrabajador", $fCodTipoTrabajador, 0)?>
			</select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table>
	<tr>
    	<td class="optmenu8" width="200" valign="top">
            <div id="menu8">
        	<div id="msj" style="height:50px; border:#CCC 1px solid; background-color:#FFC; text-align:center; padding:4px;">
            </div>
            <ul>
            <!-- CSS Tabs -->
            
             <?php
			if ($_APLICACION == "NOMINA") {
				?>
				<li>
                <a href="javascript:" onclick="cargarOpcion2(document.getElementById('frmentrada'), '../nomina/empleados_conceptos.php?', 'BLANK', 'width=1000, height=1000', $('#registro').val());" onmouseover="setMsj(14);" onmouseout="setMsj(0);">
                    Conceptos de N&oacute;mina
                </a>
				</li>
				<?
			}
			?>
            <li>
            <a href="javascript:" onclick="cargarOpcion2(document.getElementById('frmentrada'), 'gehen.php?anz=empleados_vacaciones', 'SELF', '', $('#registro').val());" onmouseover="setMsj(1);" onmouseout="setMsj(0);">
                Vacaciones del Empleado
            </a>
			</li>
            <li>
            <a href="javascript:" onclick="cargarOpcion2(document.getElementById('frmentrada'), 'gehen.php?anz=empleados_patrimonio&filtrar=default', 'SELF', '', $('#registro').val());" onmouseover="setMsj(2);" onmouseout="setMsj(0);">
                Patrimonio del Empleado
            </a>
			</li>
            <li>
            <a href="javascript:" onclick="cargarOpcion2(document.getElementById('frmentrada'), 'gehen.php?anz=empleados_permisos&filtrar=default', 'SELF', '', $('#registro').val());" onmouseover="setMsj(3);" onmouseout="setMsj(0);">
                Permisos del Empleado
            </a>
			</li>
            <li>
            <a href="javascript:" onclick="cargarOpcion2(document.getElementById('frmentrada'), 'gehen.php?anz=empleados_instruccion&filtrar=default', 'SELF', '', $('#registro').val());" onmouseover="setMsj(4);" onmouseout="setMsj(0);">
                Instrucci&oacute;n del Empleado
            </a>
			</li>
            <li>
            <a href="javascript:" onclick="cargarOpcion2(document.getElementById('frmentrada'), 'gehen.php?anz=empleados_referencias&filtrar=default', 'SELF', '', $('#registro').val());" onmouseover="setMsj(5);" onmouseout="setMsj(0);">
                Referencias del Empleado
            </a>
			</li>
            <li>
            <a href="javascript:" onclick="cargarOpcion2(document.getElementById('frmentrada'), 'gehen.php?anz=empleados_documentos&filtrar=default', 'SELF', '', $('#registro').val());" onmouseover="setMsj(6);" onmouseout="setMsj(0);">
                Documentos
            </a>
			</li>
            <li>
            <a href="javascript:" onclick="cargarOpcion2(document.getElementById('frmentrada'), 'gehen.php?anz=empleados_experiencia_laboral_lista&filtrar=default', 'SELF', '', $('#registro').val());" onmouseover="setMsj(7);" onmouseout="setMsj(0);">
                Experiencia Laboral
            </a>
			</li>
            <li>
            <a href="javascript:" onclick="cargarOpcion2(document.getElementById('frmentrada'), 'gehen.php?anz=empleados_meritos_demeritos&filtrar=default', 'SELF', '', $('#registro').val());" onmouseover="setMsj(8);" onmouseout="setMsj(0);">
                M&eacute;ritos / Dem&eacute;ritos
            </a>
			</li>
            <li>
            <a href="javascript:" onclick="cargarOpcion2(document.getElementById('frmentrada'), 'gehen.php?anz=empleados_bancaria_lista&filtrar=default', 'SELF', '', $('#registro').val());" onmouseover="setMsj(9);" onmouseout="setMsj(0);">
                Informaci&oacute;n Bancaria
            </a>
			</li>
            <li>
            <a href="javascript:" onclick="cargarOpcion2(document.getElementById('frmentrada'), 'gehen.php?anz=empleados_carga_familiar_lista&filtrar=default', 'SELF', '', $('#registro').val());" onmouseover="setMsj(10);" onmouseout="setMsj(0);">
                Carga Familiar
            </a>
			</li>
            <li>
            <a href="javascript:" onclick="cargarOpcion2(document.getElementById('frmentrada'), 'gehen.php?anz=empleados_islr_lista&filtrar=default', 'SELF', '', $('#registro').val());" onmouseover="setMsj(11);" onmouseout="setMsj(0);">
                Impuesto s. la Renta
            </a>
			</li>
            <li>
            <a href="javascript:" onclick="cargarOpcion2(document.getElementById('frmentrada'), 'gehen.php?anz=empleados_nivelaciones_form', 'SELF', '', $('#registro').val());" onmouseover="setMsj(12);" onmouseout="setMsj(0);">
                Control de Nivelaciones
            </a>
			</li>
            <li class="gallery clearfix">
            <a id="a_pdf" href="pagina.php?iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe1]" style="display:none;"></a>
            
            <a href="javascript:" onclick="abrirReporteVal('a_pdf', 'empleados_historial_pdf', '100%', '100%', $('#registro').val());" onmouseover="setMsj(13);" onmouseout="setMsj(0);">
                Historial de Modificaciones
            </a>
			</li>
           
            </ul>
            </div>
        </td>
        
    	<td valign="top">
            <table width="800" class="tblBotones">
                <tr>
                    <td><div id="rows"></div></td>
                    <td align="right">
                        <input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=empleados_form&opcion=nuevo&origen=empleados_lista');" />
                        
                        <input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=empleados_form&opcion=modificar&origen=empleados_lista', 'SELF', '', $('#registro').val());" />
                        
                        <input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=empleados_form&opcion=ver&origen=empleados_lista', 'SELF', '', $('#registro').val());" />
                    </td>
                </tr>
            </table>
            <div style="overflow:scroll; width:800px; height:425px;">
            <table width="2000" class="tblLista">
                <thead>
                <tr>
                    <th scope="col" width="25" onclick="order('EdoReg')"><a href="javascript:">Es. Reg.</a></th>
                    <th scope="col" width="25" onclick="order('SitTra')"><a href="javascript:">Sit. Tra.</a></th>
                    <th scope="col" width="25" onclick="order('FlagFaltaGrave')"><a href="javascript:">Fal. Gr.</a></th>
                    <th scope="col" width="60" onclick="order('CodEmpleado')"><a href="javascript:">C&oacute;digo</a></th>
                    <th scope="col" width="400" onclick="order('NomCompleto')"><a href="javascript:">Nombre Completo</a></th>
                    <th scope="col" width="75" onclick="order('LENGTH(Ndocumento), Ndocumento')"><a href="javascript:">Nro. Documento</a></th>
                    <th scope="col" width="75" onclick="order('Fingreso')"><a href="javascript:">Fecha de Ingreso</a></th>
                    <th scope="col" width="500" onclick="order('DescripCargo')"><a href="javascript:">Cargo</a></th>
                    <th scope="col" onclick="order('Dependencia')"><a href="javascript:">Dependencia</a></th>
                </tr>
                </thead>
                
                <tbody>
                <?php
                //	consulto todos
                $sql = "SELECT p.CodPersona
                        FROM
                            mastpersonas p
							INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
                            INNER JOIN rh_puestos pt ON (pt.CodCargo = e.CodCargo)
                            INNER JOIN mastdependencias d ON (d.CodDependencia = e.CodDependencia)
							LEFT JOIN rh_motivocese mc ON (e.CodMotivoCes = mc.CodMotivoCes)
                        WHERE 1 $filtro";
                $query = mysql_query($sql) or die ($sql.mysql_error());
                $rows_total = mysql_num_rows($query);
                
                //	consulto lista
                $sql = "SELECT
							p.CodPersona,
							p.NomCompleto,
							p.Ndocumento,
							p.Estado AS EdoReg,
							e.CodEmpleado,
							e.Fingreso,
							e.Estado AS SitTra,
							pt.DescripCargo,
							d.Dependencia,
							mc.FlagFaltaGrave
                        FROM
                            mastpersonas p
							INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
                            INNER JOIN rh_puestos pt ON (pt.CodCargo = e.CodCargo)
                            INNER JOIN mastdependencias d ON (d.CodDependencia = e.CodDependencia)
							LEFT JOIN rh_motivocese mc ON (e.CodMotivoCes = mc.CodMotivoCes)
                        WHERE 1 $filtro
                        ORDER BY $fOrderBy
                        LIMIT ".intval($limit).", ".intval($maxlimit);
                $query = mysql_query($sql) or die ($sql.mysql_error());
                $rows_lista = mysql_num_rows($query);
                while ($field = mysql_fetch_array($query)) {
                    $id = "$field[CodPersona]";
                    ?>
                    <tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
                        <td align="center"><?=printEstado($field['EdoReg'])?></td>
                        <td align="center"><?=printEstado($field['SitTra'])?></td>
                        <td align="center"><?=printWarning($field['FlagFaltaGrave'])?></td>
                        <td align="center"><?=$field['CodEmpleado']?></td>
                        <td><?=($field['NomCompleto'])?></td>
                        <td><?=$field['Ndocumento']?></td>
                        <td align="center"><?=formatFechaDMA($field['Fingreso'])?></td>
                        <td><?=($field['DescripCargo'])?></td>
                        <td><?=($field['Dependencia'])?></td>
                    </tr>
                    <?
                }
                ?>
                </tbody>
            </table>
            </div>
            <table width="800">
                <tr>
                    <td>
                        Mostrar: 
                        <select name="maxlimit" style="width:50px;" onchange="this.form.submit();">
                            <?=loadSelectGeneral("MAXLIMIT", $maxlimit, 0)?>
                        </select>
                    </td>
                    <td align="right"> 
                        <?php echo paginacion(intval($rows_total), intval($rows_lista), intval($maxlimit), intval($limit));?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</center>
</form>
