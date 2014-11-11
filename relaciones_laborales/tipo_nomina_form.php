<?php
if ($opcion == "nuevo") {
	$field['Estado'] = "A";
	##
	$titulo = "Nuevo Registro";
	$accion = "nuevo";
	$label_submit = "Guardar";
	$disabled_nuevo = "disabled";
	$clkCancelar = "document.getElementById('frmentrada').submit();";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	//	consulto datos generales
	$sql = "SELECT * FROM tiponomina WHERE CodTipoNom = '".$registro."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Registro";
		$accion = "modificar";
		$disabled_modificar = "disabled";
		$label_submit = "Modificar";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Registro";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_periodos = "disabled";
		$disabled_procesos = "disabled";
		$display_submit = "display:none;";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
	}
}
if ($_APLICACION == "RH") $display = "display:none;";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$clkCancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<table cellpadding="5" cellspacing="5" align="center">
	<tr>
    	<td valign="top">
            <form name="frmentrada" id="frmentrada" action="gehen.php?anz=tipo_nomina_lista" method="POST" enctype="multipart/form-data" onsubmit="return tipo_nomina(this, '<?=$accion?>');" autocomplete="off">
            <input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
            <input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
            <input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
            <input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
            <input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
            <input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />
            <table width="600" class="tblForm">
                <tr>
                    <td colspan="2" class="divFormCaption">Datos de N&oacute;mina</td>
                </tr>
                <tr>
                    <td class="tagForm" width="125">* C&oacute;digo:</td>
                    <td>
                        <input type="text" id="CodTipoNom" style="width:35px;" class="codigo" value="<?=$field['CodTipoNom']?>" maxlength="2" <?=$disabled_modificar?> />
                    </td>
                </tr>
                <tr>
                    <td class="tagForm">* Descripci&oacute;n:</td>
                    <td>
                        <input type="text" id="Nomina" style="width:350px;" maxlength="30" value="<?=$field['Nomina']?>" <?=$disabled_ver?> />
                    </td>
                </tr>
                <tr>
                    <td class="tagForm">* Titulo en Boleta:</td>
                    <td>
                        <input type="text" id="TituloBoleta" style="width:350px;" maxlength="50" value="<?=$field['TituloBoleta']?>" <?=$disabled_ver?> />
                    </td>
                </tr>
                <tr>
                    <td class="tagForm">Perfil de Concepto:</td>
                    <td>
                        <select id="CodPerfilConcepto" style="width:200px;" <?=$disabled_ver?>>
                            <option value="">&nbsp;</option>
                            <?=loadSelect("pr_conceptoperfil", "CodPerfilConcepto", "Descripcion", $field['CodPerfilConcepto'], 0)?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="tagForm">&nbsp;</td>
                    <td>
                        <input type="checkbox" id="FlagPagoMensual" <?=chkFlag($field['FlagPagoMensual'])?> <?=$disabled_ver?> /> Pago Mensual
                    </td>
                </tr>
                <tr>
                    <td class="tagForm">Estado:</td>
                    <td>
                        <input type="radio" name="Estado" id="Activo" value="A" <?=chkOpt($field['Estado'], "A");?> <?=$disabled_nuevo?> /> Activo
                        &nbsp; &nbsp; &nbsp; 
                        <input type="radio" name="Estado" id="Inactivo" value="I" <?=chkOpt($field['Estado'], "I");?> <?=$disabled_nuevo?> /> Inactivo
                    </td>
                </tr>
                <tr>
                    <td class="tagForm">&Uacute;ltima Modif.:</td>
                    <td>
                        <input type="text" size="30" value="<?=$field['UltimoUsuario']?>" disabled="disabled" />
                        <input type="text" size="25" value="<?=$field['UltimaFecha']?>" disabled="disabled" />
                    </td>
                </tr>
            </table>
            <center>
            <input type="submit" value="<?=$label_submit?>" style="width:75px; <?=$display_submit?>" />
            <input type="button" value="Cancelar" style="width:75px;" onclick="<?=$clkCancelar?>" />
            </center>
            </form>
            <div style="width:600px" class="divMsj">Campos Obligatorios *</div>
        </td>
        
    	<td rowspan="2" valign="top" style=" <?=$display?>">
            <form name="frm_periodos" id="frm_periodos">
            <input type="hidden" id="sel_periodos" />
            <table width="250" class="tblBotones">
                <tr>
                    <td class="divFormCaption">Periodos Disponibles</td>
                </tr>
                <tr>
                	<td align="right">
                        <input type="button" class="btLista" value="Insertar" onclick="tipo_nomina_periodos_insertar(this);" <?=$disabled_periodos?> />
                        <input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'periodos');" <?=$disabled_periodos?> />
                    </td>
                </tr>
            </table>
            <div style="overflow:scroll; width:250px; height:540px;">
            <table width="100%" class="tblLista">
                <thead>
                <tr>
                    <th scope="col" width="20"></th>
                    <th scope="col">A&ntilde;o</th>
                    <th scope="col" width="40">Mes</th>
                    <th scope="col" width="40">#</th>
                </tr>
                </thead>
                
                <tbody id="lista_periodos">
                <?php
                $sql = "SELECT *
						FROM pr_tiponominaperiodo
						WHERE CodTipoNom = '".$field['CodTipoNom']."'
						ORDER BY Periodo, Mes";
                $query_periodos = mysql_query($sql) or die ($sql.mysql_error());
                while ($field_periodos = mysql_fetch_array($query_periodos)) {	$nro_periodos++;
                    ?>
                    <tr class="trListaBody" onclick="mClk(this, 'sel_periodos');" id="periodos_<?=$nro_periodos?>">
                        <th>
                            <?=$nro_periodos?>
                        </th>
                        <td>
                            <input type="text" name="Periodo" class="cell" style="text-align:center;" maxlength="4" value="<?=$field_periodos['Periodo']?>" <?=$disabled_periodos?> />
                        </td>
                        <td>
                            <select name="Mes" class="cell" <?=$disabled_periodos?>>
                                <?=loadSelectGeneral("MES", $field_periodos['Mes'], 1)?>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="Secuencia" class="cell" style="text-align:center;" maxlength="2" value="<?=$field_periodos['Secuencia']?>" <?=$disabled_periodos?> />
                        </td>
                    </tr>
                    <?
                }
                ?>
                </tbody>
            </table>
            </div>
            <input type="hidden" id="nro_periodos" value="<?=$nro_periodos?>" />
            <input type="hidden" id="can_periodos" value="<?=$nro_periodos?>" />
            </form>
        </td>
    </tr>
	<tr>
    	<td valign="top" style=" <?=$display?>">
            <form name="frm_procesos" id="frm_procesos">
            <input type="hidden" id="sel_procesos" />
            <table width="600" class="tblBotones">
                <tr>
                    <td class="divFormCaption">Procesos Aplicables</td>
                </tr>
                <tr>
                	<td align="right">
                        <input type="button" class="btLista" value="Insertar" onclick="insertar(this, 'procesos', true, 'accion=tipo_nomina_procesos_insertar');" <?=$disabled_procesos?> />
                        <input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'procesos');" <?=$disabled_procesos?> />
                    </td>
                </tr>
            </table>
            <div style="overflow:scroll; width:600px; height:300px;">
            <table width="100%" class="tblLista">
                <thead>
                <tr>
                    <th scope="col" width="20"></th>
                    <th scope="col">Proceso</th>
                    <th scope="col" width="260">Tipo de Documento</th>
                </tr>
                </thead>
                
                <tbody id="lista_procesos">
                <?php
                $sql = "SELECT *
						FROM pr_tiponominaproceso
						WHERE CodTipoNom = '".$field['CodTipoNom']."'
						ORDER BY CodTipoProceso";
                $query_procesos = mysql_query($sql) or die ($sql.mysql_error());
                while ($field_procesos = mysql_fetch_array($query_procesos)) {	$nro_procesos++;
                    ?>
                    <tr class="trListaBody" onclick="mClk(this, 'sel_procesos');" id="procesos_<?=$nro_procesos?>">
                        <th>
                            <?=$nro_procesos?>
                        </th>
                        <td>
                            <select name="CodTipoProceso" class="cell" <?=$disabled_procesos?>>
                                <?=loadSelect("pr_tipoproceso", "CodTipoProceso", "Descripcion", $field_procesos['CodTipoProceso'], 0)?>
                            </select>
                        </td>
                        <td>
                            <select name="CodTipoDocumento" class="cell" <?=$disabled_procesos?>>
                            	<option value="">&nbsp;</option>
                                <?=loadSelectTipoDocumentoCxP($field_procesos['CodTipoDocumento'], "S", 0)?>
                            </select>
                        </td>
                    </tr>
                    <?
                }
                ?>
                </tbody>
            </table>
            </div>
            <input type="hidden" id="nro_procesos" value="<?=$nro_procesos?>" />
            <input type="hidden" id="can_procesos" value="<?=$nro_procesos?>" />
            </form>
        </td>
    </tr>
</table>