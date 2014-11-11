<?php
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
$FechaActual = "$DiaActual-$MesActual-$AnioActual";
if ($opcion == "nuevo") {
	$field['Estado'] = "AB";
	$field['CodOrganismo'] = $_SESSION["ORGANISMO_ACTUAL"];
	$field['IniciadoPor'] = $_SESSION["CODPERSONA_ACTUAL"];
	$field['NomIniciadoPor'] = $_SESSION["NOMBRE_USUARIO_ACTUAL"];
	$field['FechaIniciado'] = "$AnioActual-$MesActual-$DiaActual";
	$field['Periodo'] = "$AnioActual-$MesActual";
	$accion = "nuevo";
	$titulo = "Desarrollo de Carreras y Sucesi&oacute;n";
	$label_submit = "Guardar";
	$disabled_nuevo = "disabled";
	$disabled_actualizar = "disabled";
}
elseif ($opcion == "modificar" || $opcion == "ver" || $opcion == "terminar") {
	list($CodOrganismo, $Secuencia, $Codigo) = split("[.]", $registro);
	//	consulto datos generales
	$sql = "SELECT
				ac.CodOrganismo,
				ac.Secuencia,
				ac.Codigo,
				ac.CodPersona,
				ac.CodCargo,
				ac.CodDependencia,
				ac.Periodo,
				ac.Estado,
				ac.IniciadoPor,
				ac.FechaIniciado,
				ac.TerminadoPor,
				ac.FechaTerminado,
				p1.NomCompleto AS NomPersona,
				p1.Ndocumento,
				e1.CodEmpleado,
				e1.Fingreso,
				pt1.Grado,
				md1.Descripcion AS CategoriaCargo,
				p2.NomCompleto AS NomIniciadoPor,
				p3.NomCompleto AS NomTerminadoPor
			FROM
				rh_asociacioncarreras ac
				INNER JOIN mastpersonas p1 ON (p1.CodPersona = ac.CodPersona)
				INNER JOIN mastempleado e1 ON (e1.CodPersona = p1.CodPersona)
				INNER JOIN rh_puestos pt1 ON (pt1.CodCargo = e1.CodCargo)
				LEFT JOIN mastmiscelaneosdet md1 ON (md1.CodDetalle = pt1.CategoriaCargo AND
													  md1.CodMaestro = 'CATCARGO')
				LEFT JOIN mastpersonas p2 ON (p2.CodPersona = ac.IniciadoPor)
				LEFT JOIN mastpersonas p3 ON (p3.CodPersona = ac.TerminadoPor)
			WHERE
				ac.CodOrganismo = '".$CodOrganismo."' AND
				ac.Secuencia = '".$Secuencia."' AND
				ac.Codigo = '".$Codigo."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
	
	if ($opcion == "modificar") {
		$titulo = "Desarrollo de Carreras y Sucesi&oacute;n";
		$accion = "modificar";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$display_modificar = "display:none;";
		$display_ver = "display:none;";
		$label_submit = "Actualizar";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Desarrollo de Carreras y Sucesi&oacute;n";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_actualizar = "disabled";
		$display_submit = "display:none;";
		$display_modificar = "display:none;";
		$display_ver = "display:none;";
	}
	
	elseif ($opcion == "terminar") {
		$titulo = "Desarrollo de Carreras y Sucesi&oacute;n";
		$accion = "terminar";
		$label_submit = "Terminar";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_actualizar = "disabled";
		$display_modificar = "display:none;";
		$display_ver = "display:none;";
		$field['TerminadoPor'] = $_SESSION["CODPERSONA_ACTUAL"];
		$field['NomTerminadoPor'] = $_SESSION["NOMBRE_USUARIO_ACTUAL"];
		$field['FechaTerminado'] = "$AnioActual-$MesActual-$DiaActual";
	}
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="document.getElementById('frmentrada').submit()">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<table width="950" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current"><a href="#" onclick="mostrarTab('tab', 1, 10);">General</a></li>
            <li id="li2" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 2, 10);">Experiencia</a></li>
            <li id="li3" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 3, 10);">Estudios</a></li>
            <li id="li4" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 4, 10);">Cursos</a></li>
            <li id="li5" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 5, 10);">Competencias</a></li>
            <li id="li6" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 6, 10);">Fortalezas</a></li>
            <li id="li7" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 7, 10);">Capacitaci&oacute;n</a></li>
            <li id="li8" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 8, 10);">Habilidades</a></li>
            <li id="li9" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 9, 10);">Evaluaci&oacute;n</a></li>
            <li id="li10" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 10, 10);">Metas</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_desarrollo_carreras_lista" method="POST" onsubmit="return desarrollo_carreras(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="fCodOrganismo" id="fCodOrganismo" value="<?=$fCodOrganismo?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fCodDependencia" id="fCodDependencia" value="<?=$fCodDependencia?>" />
<input type="hidden" name="fSecuencia" id="fSecuencia" value="<?=$fSecuencia?>" />
<input type="hidden" name="fPeriodo" id="fPeriodo" value="<?=$fPeriodo?>" />
<input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />
<input type="hidden" name="registro" id="registro" value="<?=$registro?>" />
<input type="hidden" name="imprimir" id="imprimir" value="no" />

<div id="tab1" style="display:block;">
<table width="950" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Datos del Empleado</td>
    </tr>
    <tr>
		<td class="tagForm">* Empleado:</td>
		<td class="gallery clearfix">
            <input type="hidden" id="CodPersona" value="<?=$field['CodPersona']?>" />
            <input type="text" id="CodEmpleado" style="width:50px;" value="<?=$field['CodEmpleado']?>" disabled="disabled" />
			<input type="text" id="NomPersona" style="width:235px;" value="<?=htmlentities($field['NomPersona'])?>" disabled="disabled" />
            <a href="../lib/listas/listado_empleados.php?filtrar=default&ventana=desarrollo_carreras_empleado_sel&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe1]" style=" <?=$display_modificar?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td class="tagForm">Nro. Documento:</td>
		<td><input type="text" id="Ndocumento" value="<?=$field['Ndocumento']?>" style="width:100px;" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">* Organismo:</td>
		<td>
			<select id="CodOrganismo" style="width:300px;" disabled="disabled">
				<option value="">&nbsp;</option>
				<?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $field['CodOrganismo'], 0)?>
			</select>
		</td>
		<td class="tagForm">F. Ingreso:</td>
		<td><input type="text" id="Fingreso" value="<?=formatFechaDMA($field['Fingreso'])?>" style="width:100px;" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">* Dependencia:</td>
		<td>
			<select id="CodDependencia" style="width:300px;" disabled="disabled">
				<option value="">&nbsp;</option>
				<?=loadSelect("mastdependencias", "CodDependencia", "Dependencia", $field['CodDependencia'], 0)?>
			</select>
		</td>
		<td class="tagForm">Categoria:</td>
		<td><input type="text" id="CategoriaCargo" value="<?=htmlentities($field['CategoriaCargo'])?>" style="width:100px;" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">* Cargo:</td>
		<td>
			<select id="CodCargo" style="width:300px;" disabled="disabled">
				<option value="">&nbsp;</option>
				<?=loadSelect("rh_puestos", "CodCargo", "DescripCargo", $field['CodCargo'], 0)?>
			</select>
		</td>
		<td class="tagForm">Grado Cargo:</td>
		<td><input type="text" id="Grado" value="<?=$field['Grado']?>" style="width:35px;" disabled="disabled" /></td>
	</tr>
	<tr>
    	<td colspan="4" class="divFormCaption">Informaci&oacute;n del Registro</td>
    </tr>
	<tr>
		<td class="tagForm">* Evaluaci&oacute;n:</td>
		<td>
			<select id="Secuencia" style="width:300px;" onchange="desarrollo_carreras_evaluacion_sel();" <?=$disabled_modificar?>>
				<option value="">&nbsp;</option>
				<?=loadSelectEvaluaciones($field['CodOrganismo'], $field['Secuencia'], 0)?>
			</select>
		</td>
		<td class="tagForm">N&uacute;mero:</td>
		<td><input type="text" id="Codigo" value="<?=$field['Codigo']?>" class="codigo" style="width:100px;" disabled="disabled" /></td>
	</tr>
    <tr>
        <td class="tagForm">Preparado Por:</td>
        <td>
            <input type="hidden" id="IniciadoPor" value="<?=$field['IniciadoPor']?>" />
            <input type="text" id="NomIniciadoPor" value="<?=htmlentities($field['NomIniciadoPor'])?>" style="width:225px;" disabled="disabled" />
            <input type="text" id="FechaIniciado" value="<?=formatFechaDMA($field['FechaIniciado'])?>" style="width:60px;" disabled="disabled" />
        </td>
		<td class="tagForm">Estado:</td>
		<td>
			<input type="hidden" id="Estado" value="<?=$field['Estado']?>" />
			<input type="text" value="<?=htmlentities(strtoupper(printValores("ESTADO-CARRERAS", $field['Estado'])))?>" class="codigo" style="width:100px;" disabled="disabled" />
		</td>
    </tr>
    <tr>
        <td class="tagForm">Terminado Por:</td>
        <td>
            <input type="hidden" id="TerminadoPor" value="<?=$field['TerminadoPor']?>" />
            <input type="text" id="NomTerminadoPor" value="<?=htmlentities($field['NomTerminadoPor'])?>" style="width:225px;" disabled="disabled" />
            <input type="text" id="FechaTerminado" value="<?=formatFechaDMA($field['FechaTerminado'])?>" style="width:60px;" disabled="disabled" />
        </td>
		<td class="tagForm">Periodo:</td>
		<td><input type="text" id="Periodo" value="<?=$field['Periodo']?>" style="width:100px;" disabled="disabled" /></td>
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
<input type="submit" style="display:none;" />
</form>

<div id="tab2" style="display:none;">
<center>
<div style="width:950px" class="divFormCaption">EXPERIENCIA LABORAL EN LA INSTITUCION</div>
<div style="overflow:scroll; width:950px; height:300px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15" rowspan="2">#</th>
        <th scope="col" width="75" rowspan="2">Fecha</th>
        <th scope="col" align="left" rowspan="2">Cargo</th>
        <th scope="col" width="175" rowspan="2">Tipo</th>
        <th scope="col" colspan="3">Tiempo</th>
    </tr>
    <tr>
        <th scope="col" width="35">A</th>
        <th scope="col" width="35">M</th>
        <th scope="col" width="35">D</th>
    </tr>
    </thead>
    
    <tbody id="lista_experiencia" class="fichas_carrera">
    <?php
    //	consulto
    $sql = "SELECT
    			enh.Secuencia,
    			enh.Fecha,
    			enh.Cargo,
    			enh.TipoAccion,
    			en.CodCargo,
    			en.FechaHasta
			FROM
				rh_empleadonivelacionhistorial enh
				INNER JOIN rh_empleadonivelacion en ON (en.CodPersona = enh.CodPersona AND en.Secuencia = enh.Secuencia)
			WHERE enh.CodPersona = '".$field['CodPersona']."'
			ORDER BY Secuencia";
	$query_experiencia = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$nro_experiencia=0;
	while($field_experiencia = mysql_fetch_array($query_experiencia)) {
		$_DESDE = formatFechaDMA($field_experiencia['Fecha']);
		if ($field_experiencia['FechaHasta'] == '0000-00-00') $_HASTA = $FechaActual; else $_HASTA = formatFechaDMA($field_experiencia['FechaHasta']);
		list($A, $M, $D) = getTiempo($_DESDE, $_HASTA);
		?>
        <tr class="trListaBody">
    		<th>
				<?=++$nro_experiencia?>
        	</th>
        	<td align="center">
				<?=$_DESDE?>
    	    </td>
        	<td>
				<?=htmlentities($field_experiencia['Cargo'])?>
    	    </td>
        	<td>
				<?=htmlentities($field_experiencia['TipoAccion'])?>
    	    </td>
        	<td align="center">
				<?=$A?>
    	    </td>
        	<td align="center">
				<?=$M?>
    	    </td>
        	<td align="center">
				<?=$D?>
    	    </td>
	    </tr>
        <?
	}
    ?>
    </tbody>
</table>
</div>
</center>
</div>

<div id="tab3" style="display:none;">
<center>
<div style="width:950px" class="divFormCaption">NIVEL ACADEMICO</div>
<div style="overflow:scroll; width:950px; height:300px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15">#</th>
        <th scope="col" align="left">Profesi&oacute;n</th>
        <th scope="col" width="100">Fecha Graduaci&oacute;n</th>
    </tr>
    </thead>
    
    <tbody id="lista_academico" class="fichas_carrera">
    </tbody>
</table>
</div>
</center>
</div>

<div id="tab4" style="display:none;">
<center>
<div style="width:950px" class="divFormCaption">CURSOS REALIZADOS EN EL AREA</div>
<div style="overflow:scroll; width:950px; height:150px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15">#</th>
        <th scope="col" align="left">Curso</th>
        <th scope="col" width="100">Periodo</th>
    </tr>
    </thead>
    
    <tbody id="lista_cursos_area" class="fichas_carrera">
    </tbody>
</table>
</div>
<div style="width:950px" class="divFormCaption">CURSOS REALIZADOS EN FORMACION GENERAL</div>
<div style="overflow:scroll; width:950px; height:150px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15">#</th>
        <th scope="col" align="left">Curso</th>
        <th scope="col" width="100">Periodo</th>
    </tr>
    </thead>
    
    <tbody id="lista_cursos_general" class="fichas_carrera">
    </tbody>
</table>
</div>
</center>
</div>

<div id="tab5" style="display:none;">
<center>
<div style="width:950px" class="divFormCaption">COMPETENCIAS CONDUCTUALES ADQUIRIDAS</div>
<div style="overflow:scroll; width:950px; height:150px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15">#</th>
        <th scope="col" align="left">Descripci&oacute;n</th>
    </tr>
    </thead>
    
    <tbody id="lista_competencias_adquiridas" class="fichas_carrera">
    </tbody>
</table>
</div>
<div style="width:950px" class="divFormCaption">COMPETENCIAS CONDUCTUALES O GENERICAS POR ADQUIRIR</div>
<div style="overflow:scroll; width:950px; height:150px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15">#</th>
        <th scope="col" align="left">Descripci&oacute;n</th>
    </tr>
    </thead>
    
    <tbody id="lista_competencias_adquirir" class="fichas_carrera">
    </tbody>
</table>
</div>
</center>
</div>

<div id="tab6" style="display:none;">
<center>
<div style="width:950px" class="divFormCaption">HABILIDADES Y DESTREZAS TECNICAS ADQUIRIDAS</div>
<div style="overflow:scroll; width:950px; height:150px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15">#</th>
        <th scope="col" align="left">Descripci&oacute;n</th>
    </tr>
    </thead>
    
    <tbody id="lista_habilidades_adquiridas" class="fichas_carrera">
    </tbody>
</table>
</div>
<div style="width:950px" class="divFormCaption">HABILIDADES Y DESTREZAS TECNICAS POR ADQUIRIR</div>
<div style="overflow:scroll; width:950px; height:150px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15">#</th>
        <th scope="col" align="left">Descripci&oacute;n</th>
    </tr>
    </thead>
    
    <tbody id="lista_habilidades_adquirir" class="fichas_carrera">
    </tbody>
</table>
</div>
</center>
</div>

<div id="tab7" style="display:none;">
<center>
<div style="width:950px" class="divFormCaption">CAPACITACION REQUERIDAS PARA DESARROLLAR COMPETENCIAS CONDUCTUALES</div>
<div style="overflow:scroll; width:950px; height:150px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15">#</th>
        <th scope="col" align="left">Necesidad/Objetivo</th>
        <th scope="col" width="300">Curso</th>
        <th scope="col" width="60">Prioridad</th>
    </tr>
    </thead>
    
    <tbody id="lista_capacitacion_conductual" class="fichas_carrera">
    </tbody>
</table>
</div>
<form name="frm_captecnica" id="frm_captecnica">
<input type="hidden" name="sel_captecnica" id="sel_captecnica" />
<div style="width:950px" class="divFormCaption">CAPACITACION PARA DESARROLLAR COMPETENCIAS TECNICAS REQUERIDAS PARA EJECUTAR SUS FUNCIONES DE ACUERDO AL MANUAL DESCRIPTIVO DE CARGOS</div>
<table width="950" class="tblBotones">
	<tr>
		<td align="right" width="200">
			<input type="button" class="btLista" value="Insertar" onclick="insertarLinea2(this, 'desarrollo_carreras_capacitacion_tecnica_insertar', 'captecnica', true);" <?=$disabled_actualizar?> />
			<input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'captecnica');" <?=$disabled_actualizar?> />
		</td>
	</tr>
</table>
<div style="overflow:scroll; width:950px; height:150px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15">#</th>
        <th scope="col" align="left">Descripci&oacute;n</th>
    </tr>
    </thead>
    
    <tbody id="lista_captecnica" class="fichas_carrera">
    <?php
    //	consulto
    $sql = "SELECT *
			FROM rh_asociacioncarrerascaptecnica
			WHERE
				CodOrganismo = '".$CodOrganismo."' AND
				Secuencia = '".$Secuencia."' AND
				Codigo = '".$Codigo."'
			ORDER BY Linea";
	$query_captecnica = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$nro_captecnica=0;
	while($field_captecnica = mysql_fetch_array($query_captecnica)) {
		?>
        <tr class="trListaBody" onclick="mClk(this, 'sel_captecnica');" id="captecnica_<?=$nro_captecnica?>">
    		<th>
				<?=++$nro_captecnica?>
        	</th>
        	<td>
        		<textarea name="Descripcion" class="cell" style="height:30px;" <?=$disabled_actualizar?>><?=$field_captecnica['Descripcion']?></textarea>
    	    </td>
	    </tr>
        <?
	}
    ?>
    </tbody>
</table>
</div>
<input type="hidden" id="nro_captecnica" value="<?=$nro_captecnica?>" />
<input type="hidden" id="can_captecnica" value="<?=$nro_captecnica?>" />
</form>
</center>
</div>

<div id="tab8" style="display:none;">
<form name="frm_habilidad" id="frm_habilidad">
<input type="hidden" name="sel_habilidad" id="sel_habilidad" />
<center>
<div style="width:950px" class="divFormCaption">HABILIDADES, DESTREZAS Y CAPACIDAD PARA REALIZAR ACTIVIDADES EXTRAORDINARIAS (NO CONTEMPLADAS EN EL MANUAL DESCRIPTIVO DE CARGOS)</div>
<table width="950" class="tblBotones">
	<tr>
		<td align="right" width="200">
			<input type="button" class="btLista" value="Insertar" onclick="insertarLinea2(this, 'desarrollo_carreras_habilidad_insertar', 'habilidad', true);" <?=$disabled_actualizar?> />
			<input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'habilidad');" <?=$disabled_actualizar?> />
		</td>
	</tr>
</table>
<div style="overflow:scroll; width:950px; height:300px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15">#</th>
        <th scope="col" width="100">Tipo</th>
        <th scope="col" align="left">Descripci&oacute;n</th>
    </tr>
    </thead>
    
    <tbody id="lista_habilidad" class="fichas_carrera">
    <?php
    //	consulto
    $sql = "SELECT *
			FROM rh_asociacioncarrerashabilidad
			WHERE
				CodOrganismo = '".$CodOrganismo."' AND
				Secuencia = '".$Secuencia."' AND
				Codigo = '".$Codigo."'
			ORDER BY Tipo, Linea";
	$query_habilidad = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$nro_habilidad=0;
	while($field_habilidad = mysql_fetch_array($query_habilidad)) {
		?>
        <tr class="trListaBody" onclick="mClk(this, 'sel_habilidad');" id="habilidad_<?=$nro_habilidad?>">
    		<th>
				<?=++$nro_habilidad?>
        	</th>
	        <td>
    	    	<select name="Tipo" class="cell" <?=$disabled_actualizar?>>
        	    	<?=loadSelectValores("TIPO-HABILIDAD", $field_habilidad['Tipo'], 0)?>
            	</select>
	        </td>
        	<td>
        		<textarea name="Descripcion" class="cell" style="height:30px;" <?=$disabled_actualizar?>><?=$field_habilidad['Descripcion']?></textarea>
    	    </td>
	    </tr>
        <?
	}
    ?>
    </tbody>
</table>
</div>
</center>
<input type="hidden" id="nro_habilidad" value="<?=$nro_habilidad?>" />
<input type="hidden" id="can_habilidad" value="<?=$nro_habilidad?>" />
</form>
</div>

<div id="tab9" style="display:none;">
<form name="frm_evaluacion" id="frm_evaluacion">
<input type="hidden" name="sel_evaluacion" id="sel_evaluacion" />
<center>
<div style="width:950px" class="divFormCaption">EVALUACION CUALITATIVA PARA SER ASCENDIDO AL CARGO INMEDIATAMENTE SUPERIOR</div>
<table width="950" class="tblBotones">
	<tr>
		<td align="right" width="200">
			<input type="button" class="btLista" value="Insertar" onclick="insertarLinea2(this, 'desarrollo_carreras_evaluacion_insertar', 'evaluacion', true);" <?=$disabled_actualizar?> />
			<input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'evaluacion');" <?=$disabled_actualizar?> />
		</td>
	</tr>
</table>
<div style="overflow:scroll; width:950px; height:300px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15">#</th>
        <th scope="col" align="left">Descripci&oacute;n</th>
    </tr>
    </thead>
    
    <tbody id="lista_evaluacion" class="fichas_carrera">
    <?php
    //	consulto
    $sql = "SELECT *
			FROM rh_asociacioncarrerasevaluacion
			WHERE
				CodOrganismo = '".$CodOrganismo."' AND
				Secuencia = '".$Secuencia."' AND
				Codigo = '".$Codigo."'
			ORDER BY Linea";
	$query_evaluacion = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$nro_evaluacion=0;
	while($field_evaluacion = mysql_fetch_array($query_evaluacion)) {
		?>
        <tr class="trListaBody" onclick="mClk(this, 'sel_evaluacion');" id="captecnica_<?=$nro_evaluacion?>">
    		<th>
				<?=++$nro_evaluacion?>
        	</th>
        	<td>
        		<textarea name="Descripcion" class="cell" style="height:30px;" <?=$disabled_actualizar?>><?=$field_evaluacion['Descripcion']?></textarea>
    	    </td>
	    </tr>
        <?
	}
    ?>
    </tbody>
</table>
</div>
</center>
<input type="hidden" id="nro_evaluacion" value="<?=$nro_evaluacion?>" />
<input type="hidden" id="can_evaluacion" value="<?=$nro_evaluacion?>" />
</form>
</div>

<div id="tab10" style="display:none;">
<form name="frm_metas" id="frm_metas">
<input type="hidden" name="sel_metas" id="sel_metas" />
<center>
<div style="width:950px" class="divFormCaption">METAS QUE DEBE SUPERAR PARA LOGRAR ASCENDER</div>
<table width="950" class="tblBotones">
	<tr>
		<td align="right" width="200">
			<input type="button" class="btLista" value="Insertar" onclick="insertarLinea2(this, 'desarrollo_carreras_metas_insertar', 'metas', true);" <?=$disabled_actualizar?> />
			<input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'metas');" <?=$disabled_actualizar?> />
		</td>
	</tr>
</table>
<div style="overflow:scroll; width:950px; height:300px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15">#</th>
        <th scope="col" align="left">Descripci&oacute;n</th>
    </tr>
    </thead>
    
    <tbody id="lista_metas" class="fichas_carrera">
    <?php
    //	consulto
    $sql = "SELECT *
			FROM rh_asociacioncarrerasmetas
			WHERE
				CodOrganismo = '".$CodOrganismo."' AND
				Secuencia = '".$Secuencia."' AND
				Codigo = '".$Codigo."'
			ORDER BY Linea";
	$query_metas = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$nro_metas=0;
	while($field_metas = mysql_fetch_array($query_metas)) {
		?>
        <tr class="trListaBody" onclick="mClk(this, 'sel_metas');" id="metas_<?=$nro_metas?>">
    		<th>
				<?=++$nro_metas?>
        	</th>
        	<td>
        		<textarea name="Descripcion" class="cell" style="height:30px;" <?=$disabled_actualizar?>><?=$field_metas['Descripcion']?></textarea>
    	    </td>
	    </tr>
        <?
	}
    ?>
    </tbody>
</table>
</div>
</center>
<input type="hidden" id="nro_metas" value="<?=$nro_metas?>" />
<input type="hidden" id="can_metas" value="<?=$nro_metas?>" />
</form>
</div>

<center>
<input type="button" value="<?=$label_submit?>" style="width:75px; <?=$display_submit?>" onclick="desarrollo_carreras(document.getElementById('frmentrada'), '<?=$accion?>');" />
<input type="button" value="Cancelar" style="width:75px;" onclick="document.getElementById('frmentrada').submit();" />
</center>
<br />
<div style="width:950px" class="divMsj">Campos Obligatorios *</div>

<?php
if ($opcion != "nuevo") {
	?>
	<script type="text/javascript" language="javascript">
	$(document).ready(function() {
		desarrollo_carreras_evaluacion_sel();
	});
	</script>
	<?
}
?>