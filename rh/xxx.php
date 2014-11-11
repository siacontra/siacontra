<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
extract($_POST);
extract($_GET);
//	------------------------------------
include("../lib/fphp.php");
include("lib/fphp.php");
//	------------------------------------
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Desarrollo de Carreras y Sucesi&oacute;n";
	$cancelar = "document.getElementById('frmentrada').submit();";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	list($Anio, $CodPersona) = split("[.]", $registro);
	//	consulto datos generales
	$sql = "SELECT
				p.CodPersona,
				p.NomCompleto,
				p.Ndocumento,
				e.CodEmpleado,
				e.Fingreso,
				pu1.DescripCargo AS NomCargo,
				pu2.DescripCargo AS NomCargoTemp,
				pu1.Grado AS Grado,
				pu2.Grado AS GradoTemp
			FROM
				rh_carreras c
				INNER JOIN mastpersonas p ON (c.CodPersona = p.CodPersona)
				INNER JOIN mastempleado e ON (p.CodPersona = e.CodPersona)
				INNER JOIN rh_puestos pu1 ON (e.CodCargo = pu1.CodCargo)
				LEFT JOIN rh_puestos pu2 ON (e.CodCargoTemp = pu2.CodCargo)
			WHERE
				Anio = '".$Anio."' AND
				CodPersona = '".$CodPersona."'";
	$query_datos = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query_datos)) $field_datos = mysql_fetch_array($query_datos);
	
	//	habllidades y destrezas tecnicas adquiridas
	$sql = "SELECT
				ed.Descripcion
			FROM
				rh_empleado_desempenio ed
				INNER JOIN rh_evaluacionempleado ee ON (ed.CodOrganismo = ee.CodOrganismo AND
														ed.Periodo = ee.Periodo AND
														ed.Secuencia = ee.Secuencia AND
														ed.CodPersona = ee.CodPersona AND
														ed.Evaluador = ee.Evaluador)
			WHERE
				ed.CodPersona = '".$CodPersona."' AND
				ed.Tipo = 'F' AND
				ee.Estado = 'EV'
			ORDER BY ed.Tipo, ed.SecuenciaDesempenio";
	$query_fortalezasa = mysql_query($sql) or die($sql.mysql_error());
	
	//	habllidades y destrezas tecnicas por adquirir
	$sql = "SELECT
				ed.Descripcion
			FROM
				rh_empleado_desempenio ed
				INNER JOIN rh_evaluacionempleado ee ON (ed.CodOrganismo = ee.CodOrganismo AND
														ed.Periodo = ee.Periodo AND
														ed.Secuencia = ee.Secuencia AND
														ed.CodPersona = ee.CodPersona AND
														ed.Evaluador = ee.Evaluador)
			WHERE
				ed.CodPersona = '".$CodPersona."' AND
				ed.Tipo = 'D' AND
				ee.Estado = 'EV'
			ORDER BY ed.Tipo, ed.SecuenciaDesempenio";
	$query_fortalezaspa = mysql_query($sql) or die($sql.mysql_error());
	
	//	capacitacion requeridas para desarrollar competencias conductuales
	$sql = "SELECT
				en.Necesidad,
				en.Objetivo,
				en.Prioridad,
				c.Descripcion AS NomCurso
			FROM
				rh_empleado_necesidad en
				INNER JOIN rh_cursos c ON (en.CodCurso = c.CodCurso)
				INNER JOIN rh_evaluacionempleado ee ON (en.CodOrganismo = ee.CodOrganismo AND
														en.Periodo = ee.Periodo AND
														en.Secuencia = ee.Secuencia AND
														en.CodPersona = ee.CodPersona AND
														en.Evaluador = ee.Evaluador)
			WHERE
				en.CodPersona = '".$CodPersona."' AND
				ee.Estado = 'EV'
			ORDER BY en.SecuenciaDesempenio";
	$query_capacitacioncc = mysql_query($sql) or die($sql.mysql_error());
	
	if ($opcion == "modificar") {
		$accion = "modificar";
		$titulo = "Desarrollo de Carreras y Sucesi&oacute;n";
		$cancelar = "document.getElementById('frmentrada').submit();";
	}
	
	elseif ($opcion == "ver") {
		$disabled_ver = "disabled";
		$titulo = "Desarrollo de Carreras y Sucesi&oacute;n";
		$cancelar = "window.close();";
		$display_submit = "display:none;";
	}
	
	if ($field_datos['CodCargoTemp']) $CodCargo = $field_datos['CodCargoTemp']; else $CodCargo = $field_datos['CodCargo'];
	if ($field_datos['NomCargoTemp']) $DescripCargo = $field_datos['NomCargoTemp']; else $DescripCargo = $field_datos['NomCargo'];
	if ($field_datos['GradoTemp']) $Grado = $field_datos['GradoTemp']; else $Grado = $field_datos['Grado'];
}
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<link type="text/css" rel="stylesheet" href="../css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../css/estilo.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" src="../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/fscript.js" charset="utf-8"></script>
<script type="text/javascript" src="js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="js/fscript.js" charset="utf-8"></script>
<script type="text/javascript" src="js/form.js" charset="utf-8"></script>
</head>

<body onload="document.getElementById('Anio').focus();">
<div id="bloqueo" class="divBloqueo"></div>
<div id="cargando" class="divCargando">
<table>
	<tr>
    	<td valign="middle" style="height:50px;">
			<img src="../imagenes/iconos/cargando.gif" /><br />Procesando...
        </td>
    </tr>
</table>
</div>
<!-- ui-dialog -->
<div id="cajaModal"></div>
<!-- pretty -->
<span class="gallery clearfix"></span>

<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$cancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="rh_desarrollo_carreras_lista.php" method="POST" onsubmit="return desarrollo_carreras(this, '<?=$accion?>');">
<input type="hidden" name="fedoreg" id="fedoreg" value="<?=$fedoreg?>" />
<input type="hidden" name="fbuscar" id="fbuscar" value="<?=$fbuscar?>" />
<input type="hidden" name="fordenar" id="fordenar" value="<?=$fordenar?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="forganismo" id="forganismo" value="<?=$forganismo?>" />
<input type="hidden" name="fdependencia" id="fdependencia" value="<?=$fdependencia?>" />
<input type="hidden" name="fperiodo" id="fperiodo" value="<?=$fperiodo?>" />
<input type="hidden" name="Anio" id="Anio" value="<?=$Anio?>" />

<table width="1000" class="tblForm">
	<tr>
    	<td class="divFormCaption" colspan="6">Datos Generales</td>
    </tr>
	<tr>
		<td class="tagForm" width="100">* Empleado:</td>
		<td class="gallery clearfix">
            <input type="hidden" id="CodPersona" value="<?=$CodPersona?>" />
            <input type="text" id="CodEmpleado" style="width:60px;" value="<?=$field_datos['CodEmpleado']?>" disabled="disabled" />
            <a href="../lib/listas/listado_empleados.php?filtrar=default&ventana=sellistado_desarrollo_carreras&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
		<td class="tagForm" width="150">Nombres y Apellidos:</td>
		<td>
        	<input type="text" id="NomCompleto" style="width:250px;" value="<?=($field_datos['NomCompleto'])?>" disabled="disabled" />
		</td>
		<td class="tagForm" width="100">Nro. Documento:</td>
		<td>
        	<input type="text" id="Ndocumento" style="width:60px;" value="<?=($field_datos['Ndocumento'])?>" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">F. Ingreso:</td>
		<td>
        	<input type="text" id="Fingreso" style="width:60px;" value="<?=formatFechaDMA($field_datos['Fingreso'])?>" disabled="disabled" />
		</td>
		<td class="tagForm">Cargo:</td>
		<td>
			<input type="hidden" id="CodCargo" value="<?=$CodCargo?>" />
        	<input type="text" id="DescripCargo" style="width:250px;" value="<?=($DescripCargo)?>" disabled="disabled" />
		</td>
		<td class="tagForm">Grado del Cargo:</td>
		<td>
        	<input type="text" id="Grado" style="width:60px;" value="<?=($Grado)?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center>
<input type="submit" value="Aceptar" <?=$display_submit?> />
<input type="button" value="Cancelar" onclick="<?=$cancelar?>" />
</center>
</form>

<table width="1000" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current"><a href="#" onclick="mostrarTab('tab', 1, 5);">Nivel Acad&eacute;mico</a></li>
            <li id="li2" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 2, 5);">Cursos</a></li>
            <li id="li3" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 3, 5);">Competencias</a></li>
            <li id="li4" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 4, 5);">Fortalezas y Debilidades</a></li>
            <li id="li5" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 5, 5);">Capacitaci&oacute;n</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<div id="tab1" style="display:block;">
<form name="frm_nivel" id="frm_nivel">
<input type="hidden" name="sel_nivel" id="sel_nivel" />
<table width="1000" class="tblBotones">
	<tr>
    	<td>
        	<strong>NIVEL ACAD&Eacute;MICO</strong>
        </td>
		<td align="right">
			<input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'nivel');" <?=$disabled_submit?> />
		</td>
	</tr>
</table>
<center>
<div style="overflow:scroll; width:1000px; height:400px;">
<table width="100%" class="tblLista">
	<thead>
	<tr class="trListaHead">
        <th scope="col" width="25">#</th>
        <th scope="col" align="left">Profesi&oacute;n</th>
        <th scope="col" width="100">Fecha Graduaci&oacute;n</th>
    </tr>
    </thead>
    
    <tbody id="lista_nivel">
    <?
	$nronivel = 0;
	if ($opcion != "nuevo") {
		//	nivel academico
		$sql = "SELECT
					ca.*,
					gi.Descripcion AS NomGradoInstruccion,
					md.Descripcion AS NomArea,
					p.Descripcion AS NomProfesion
				FROM
					rh_carreras_academico ca
					INNER JOIN rh_gradoinstruccion gi ON (ca.CodGradoInstruccion = gi.CodGradoInstruccion)
					LEFT JOIN mastmiscelaneosdet md ON (ca.Area = md.CodDetalle AND md.CodMaestro = 'AREA')
					LEFT JOIN rh_profesiones p ON (ca.CodProfesion = p.CodProfesion)
				WHERE
					ca.Anio = '".$Anio."' AND
					ca.CodPersona = '".$CodPersona."'
				ORDER BY FechaGraduacion DESC, Secuencia";
		$query_nivel = mysql_query($sql) or die($sql.mysql_error());
		while ($field_nivel = mysql_fetch_array($query_nivel)) {
			$nronivel++;
			if ($field_nivel['CodProfesion'] != "") $Profesion = $field_nivel['NomProfesion'];
			else $Profesion = $field_nivel['NomGradoInstruccion']." EN ".$field_nivel['NomArea'];
			?>
			<tr class="trListaBody" onclick="mClk(this, 'sel_nivel');" id="nivel_<?=$nronivel?>">
				<td align="center">
                	<input type="hidden" name="Secuencia" value="<?=$field_nivel['Secuencia']?>" />
                	<input type="hidden" name="CodGradoInstruccion" value="<?=$field_nivel['CodGradoInstruccion']?>" />
                	<input type="hidden" name="Area" value="<?=$field_nivel['Area']?>" />
                	<input type="hidden" name="CodProfesion" value="<?=$field_nivel['CodProfesion']?>" />
                	<input type="hidden" name="Nivel" value="<?=$field_nivel['Nivel']?>" />
                	<input type="hidden" name="CodCentroEstudio" value="<?=$field_nivel['CodCentroEstudio']?>" />
                	<input type="hidden" name="FechaGraduacion" value="<?=$field_nivel['FechaGraduacion']?>" />
					<?=$nronivel?>
                </td>
                <td align="center">
                	<?=$Profesion?>
                </td>
                <td align="center">
                	<?=formatFechaDMA($field_nivel['FechaGraduacion'])?>
                </td>
			</tr>
			<?
		}
	}
	?>
    </tbody>
</table>
</div>
</center>
<input type="hidden" id="nro_nivel" value="<?=$nronivel?>" />
<input type="hidden" id="can_nivel" value="<?=$nronivel?>" />
</form>
</div>

<div id="tab2" style="display:none;">
<form name="frm_cursosa" id="frm_cursosa">
<input type="hidden" name="sel_cursosa" id="sel_cursosa" />
<table width="1000" class="tblBotones">
	<tr>
    	<td>
        	<strong>CURSOS REALIZADOS EN EL AREA</strong>
        </td>
		<td align="right">
			<input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'cursosa');" <?=$disabled_submit?> />
		</td>
	</tr>
</table>
<center>
<div style="overflow:scroll; width:1000px; height:200px;">
<table width="100%" class="tblLista">
	<thead>
	<tr class="trListaHead">
        <th scope="col" width="25">#</th>
        <th scope="col" align="left">Curso</th>
        <th scope="col" width="100">Periodo de Culminaci&oacute;n</th>
    </tr>
    </thead>
    
    <tbody id="lista_cursosa">
    <?
	$nrocursosa = 0;
	if ($opcion != "nuevo") {	
		//	cursos realizados en el area
		$sql = "SELECT
					cc.*,
					c.Descripcion AS NomCurso
				FROM
					rh_carreras_cursos cc
					INNER JOIN rh_cursos c ON (ec.CodCurso = c.CodCurso)
				WHERE
					cc.Anio = '".$Anio."' AND
					cc.CodPersona = '".$CodPersona."' AND
					cc.FlagArea = 'S'
				ORDER BY FechaCulminacion DESC, Secuencia";
		$query_cursosa = mysql_query($sql) or die($sql.mysql_error());
		while ($field_cursosa = mysql_fetch_array($query_cursosa)) {
			$nrocursosa++;
			?>
			<tr class="trListaBody" onclick="mClk(this, 'sel_cursosa');" id="cursosa_<?=$nrocursosa?>">
				<td align="center">
                	<input type="hidden" name="Secuencia" value="<?=$field_cursosa['Secuencia']?>" />
                	<input type="hidden" name="CodCurso" value="<?=$field_cursosa['CodCurso']?>" />
                	<input type="hidden" name="TipoCurso" value="<?=$field_cursosa['TipoCurso']?>" />
                	<input type="hidden" name="CodCentroEstudio" value="<?=$field_cursosa['CodCentroEstudio']?>" />
                	<input type="hidden" name="FechaCulminacion" value="<?=$field_cursosa['FechaCulminacion']?>" />
                	<input type="hidden" name="TotalHoras" value="<?=$field_cursosa['TotalHoras']?>" />
                	<input type="hidden" name="AniosVigencia" value="<?=$field_cursosa['AniosVigencia']?>" />
                	<input type="hidden" name="FlagInstitucional" value="<?=$field_cursosa['FlagInstitucional']?>" />
                	<input type="hidden" name="FlagPago" value="<?=$field_cursosa['FlagPago']?>" />
                	<input type="hidden" name="FlagArea" value="<?=$field_cursosa['FlagArea']?>" />
					<?=$nrocursosa?>
                </td>
                <td align="center">
                	<?=$field_cursosa['NomCurso']?>
                </td>
                <td align="center">
                	<?=($field_cursosa['FechaCulminacion'])?>
                </td>
			</tr>
			<?
		}
	}
	?>
    </tbody>
</table>
</div>
</center>
<input type="hidden" id="nro_cursosa" value="<?=$nrocursosa?>" />
<input type="hidden" id="can_cursosa" value="<?=$nrocursosa?>" />
</form>

<form name="frm_cursosfg" id="frm_cursosfg">
<input type="hidden" name="sel_cursosfg" id="sel_cursosfg" />
<table width="1000" class="tblBotones">
	<tr>
    	<td>
        	<strong>CURSOS REALIZADOS EN FORMACI&oacute;N GENERAL</strong>
        </td>
		<td align="right">
			<input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'cursosfg');" <?=$disabled_submit?> />
		</td>
	</tr>
</table>
<center>
<div style="overflow:scroll; width:1000px; height:200px;">
<table width="100%" class="tblLista">
	<thead>
	<tr class="trListaHead">
        <th scope="col" width="25">#</th>
        <th scope="col" align="left">Curso</th>
        <th scope="col" width="100">Periodo de Culminaci&oacute;n</th>
    </tr>
    </thead>
    
    <tbody id="lista_cursosfg">
    <?
	$nrocursosfg = 0;
	if ($opcion != "nuevo") {	
		//	cursos realizados en el area
		$sql = "SELECT
					cc.*,
					c.Descripcion AS NomCurso
				FROM
					rh_carreras_cursos cc
					INNER JOIN rh_cursos c ON (ec.CodCurso = c.CodCurso)
				WHERE
					cc.Anio = '".$Anio."' AND
					cc.CodPersona = '".$CodPersona."' AND
					cc.FlagArea = 'N'
				ORDER BY FechaCulminacion DESC, Secuencia";
		$query_cursosfg = mysql_query($sql) or die($sql.mysql_error());
		while ($field_cursosfg = mysql_fetch_array($query_cursosfg)) {
			$nrocursosfg++;
			$codigo = $field_cursosfg['Secuencia'];
			?>
			<tr class="trListaBody" onclick="mClk(this, 'sel_cursosfg');" id="cursosfg_<?=$nrocursosfg?>">
				<td align="center">
                	<input type="hidden" name="Secuencia" value="<?=$field_cursosfg['Secuencia']?>" />
                	<input type="hidden" name="CodCurso" value="<?=$field_cursosfg['CodCurso']?>" />
                	<input type="hidden" name="TipoCurso" value="<?=$field_cursosfg['TipoCurso']?>" />
                	<input type="hidden" name="CodCentroEstudio" value="<?=$field_cursosfg['CodCentroEstudio']?>" />
                	<input type="hidden" name="FechaCulminacion" value="<?=$field_cursosfg['FechaCulminacion']?>" />
                	<input type="hidden" name="TotalHoras" value="<?=$field_cursosfg['TotalHoras']?>" />
                	<input type="hidden" name="AniosVigencia" value="<?=$field_cursosfg['AniosVigencia']?>" />
                	<input type="hidden" name="FlagInstitucional" value="<?=$field_cursosfg['FlagInstitucional']?>" />
                	<input type="hidden" name="FlagPago" value="<?=$field_cursosfg['FlagPago']?>" />
                	<input type="hidden" name="FlagArea" value="<?=$field_cursosfg['FlagArea']?>" />
					<?=$nrocursosfg?>
                </td>
                <td align="center">
                	<?=$field_cursosfg['NomCurso']?>
                </td>
                <td align="center">
                	<?=($field_cursosfg['FechaCulminacion'])?>
                </td>
			</tr>
			<?
		}
	}
	?>
    </tbody>
</table>
</div>
</center>
<input type="hidden" id="nro_cursosfg" value="<?=$nrocursosfg?>" />
<input type="hidden" id="can_cursosfg" value="<?=$nrocursosfg?>" />
</form>
</div>

<div id="tab3" style="display:none;">
<form name="frm_competenciasca" id="frm_competenciasca">
<input type="hidden" name="sel_competenciasca" id="sel_competenciasca" />
<table width="1000" class="tblBotones">
	<tr>
    	<td>
        	<strong>COMPETENCIAS CONDUCTUALES ADQUIRIDAS</strong>
        </td>
		<td align="right">
			<input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'competenciasca');" <?=$disabled_submit?> />
		</td>
	</tr>
</table>
<center>
<div style="overflow:scroll; width:1000px; height:200px;">
<table width="100%" class="tblLista">
	<thead>
	<tr class="trListaHead">
        <th scope="col" width="25">#</th>
        <th scope="col" align="left">Descripci&oacute;n</th>
    </tr>
    </thead>
    
    <tbody id="lista_competenciasca">
    <?
	$nrocompetenciasca = 0;
	if ($opcion != "nuevo") {	
		//	competencias conductuales adquiridas
		$sql = "SELECT
					ef.Competencia,
					ef.Descripcion,
					ef.ValorRequerido,
					ef.ValorMinimo,
					ef.Estado,
					ee.Calificacion,
					fv.Explicacion,
					fv.Explicacion2
				FROM
					rh_carreras_competencias cc
					INNER JOIN rh_evaluacionfactores ef ON (cc.Competencia = ef.Competencia)
					INNER JOIN rh_empleado_evaluacion ee ON (ef.Competencia = ee.Competencia)
					INNER JOIN rh_evaluacionempleado eve ON (ee.CodOrganismo = eve.CodOrganismo AND
															 ee.Periodo = eve.Periodo AND
															 ee.Secuencia = eve.Secuencia AND
															 ee.CodPersona = eve.CodPersona AND
															 ee.Evaluador = eve.Evaluador)
					LEFT JOIN rh_factorvalor fv ON (ee.Competencia = fv.Competencia AND
													ee.Calificacion = fv.Grado)
				WHERE
					cc.Anio = '".$Anio."' AND
					cc.CodPersona = '".$CodPersona."' AND
					cc.FlagAdquiridas = 'S'
				ORDER BY Competencia";
		$query_competenciasca = mysql_query($sql) or die($sql.mysql_error());
		while ($field_competenciasca = mysql_fetch_array($query_competenciasca)) {
			$nrocompetenciasca++;
			?>
			<tr class="trListaBody" onclick="mClk(this, 'sel_competenciasca');" id="competenciasca_<?=$nrocompetenciasca?>">
				<td align="center">
                	<input type="hidden" name="Secuencia" value="<?=$field_competenciasca['Secuencia']?>" />
                	<input type="hidden" name="Competencia" value="<?=$field_competenciasca['Competencia']?>" />
                	<input type="hidden" name="FlagAdquiridas" value="S" />
					<?=$nrocompetenciasca?>
                </td>
                <td>
                    <strong><?=$field_competenciasca['Descripcion']?></strong><br />
                    <?=$field_competenciasca['Explicacion']?><br />
                    <?=$field_competenciasca['Explicacion2']?>
                </td>
			</tr>
			<?
		}
	}
	?>
    </tbody>
</table>
</div>
</center>
<input type="hidden" id="nro_competenciasca" value="<?=$nrocompetenciasca?>" />
<input type="hidden" id="can_competenciasca" value="<?=$nrocompetenciasca?>" />
</form>

<form name="frm_competenciascgpa" id="frm_competenciascgpa">
<input type="hidden" name="sel_competenciascgpa" id="sel_competenciascgpa" />
<table width="1000" class="tblBotones">
	<tr>
    	<td>
        	<strong>COMPETENCIAS CONDUCTUALES O GENERICAS POR ADQUIRIR</strong>
        </td>
		<td align="right">
			<input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'competenciascgpa');" <?=$disabled_submit?> />
		</td>
	</tr>
</table>
<center>
<div style="overflow:scroll; width:1000px; height:200px;">
<table width="100%" class="tblLista">
	<thead>
	<tr class="trListaHead">
        <th scope="col" width="25">#</th>
        <th scope="col" align="left">Descripci&oacute;n</th>
    </tr>
    </thead>
    
    <tbody id="lista_competenciascgpa">
    <?
	$nrocompetenciascgpa = 0;
	if ($opcion != "nuevo") {
		$sql = "SELECT
					ef.Competencia,
					ef.Descripcion,
					ef.ValorRequerido,
					ef.ValorMinimo,
					ef.Estado,
					ee.Calificacion,
					fv.Explicacion,
					fv.Explicacion2
				FROM
					rh_carreras_competencias cc
					INNER JOIN rh_evaluacionfactores ef ON (cc.Competencia = ef.Competencia)
					INNER JOIN rh_empleado_evaluacion ee ON (ef.Competencia = ee.Competencia)
					INNER JOIN rh_evaluacionempleado eve ON (ee.CodOrganismo = eve.CodOrganismo AND
															 ee.Periodo = eve.Periodo AND
															 ee.Secuencia = eve.Secuencia AND
															 ee.CodPersona = eve.CodPersona AND
															 ee.Evaluador = eve.Evaluador)
					LEFT JOIN rh_factorvalor fv ON (ee.Competencia = fv.Competencia AND
													ee.Calificacion = fv.Grado)
				WHERE
					cc.Anio = '".$Anio."' AND
					cc.CodPersona = '".$CodPersona."' AND
					cc.FlagAdquiridas = 'N'
				ORDER BY Competencia";
		while ($field_competenciascgpa = mysql_fetch_array($query_competenciascgpa)) {
			$nrocompetenciascgpa++;
			?>
			<tr class="trListaBody" onclick="mClk(this, 'sel_competenciascgpa');" id="competenciascgpa_<?=$nrocompetenciascgpa?>">
				<td align="center">
                	<input type="hidden" name="Secuencia" value="<?=$field_competenciascgpa['Secuencia']?>" />
                	<input type="hidden" name="Competencia" value="<?=$field_competenciascgpa['Competencia']?>" />
                	<input type="hidden" name="FlagAdquiridas" value="N" />
					<?=$nrocompetenciascgpa?>
                </td>
                <td>
                    <strong><?=$field_competenciascgpa['Descripcion']?></strong><br />
                    <?=$field_competenciascgpa['Explicacion']?><br />
                    <?=$field_competenciascgpa['Explicacion2']?>
                </td>
			</tr>
			<?
		}
	}
	?>
    </tbody>
</table>
</div>
</center>
<input type="hidden" id="nro_competenciascgpa" value="<?=$nrocompetenciascgpa?>" />
<input type="hidden" id="can_competenciascgpa" value="<?=$nrocompetenciascgpa?>" />
</form>
</div>

<div id="tab4" style="display:none;">
<form name="frm_fortalezasa" id="frm_fortalezasa">
<input type="hidden" name="sel_fortalezasa" id="sel_fortalezasa" />
<table width="1000" class="tblBotones">
	<tr>
    	<td>
        	<strong>HABILIDADES Y DESTREZAS TECNICAS ADQUIRIDAS</strong>
        </td>
		<td align="right">
			<input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'fortalezasa');" <?=$disabled_submit?> />
		</td>
	</tr>
</table>
<center>
<div style="overflow:scroll; width:1000px; height:200px;">
<table width="100%" class="tblLista">
	<thead>
	<tr class="trListaHead">
        <th scope="col" width="25">#</th>
        <th scope="col" align="left">Descripci&oacute;n</th>
    </tr>
    </thead>
    
    <tbody id="lista_fortalezasa">
    <?
	$nrofortalezasa = 0;
	if ($opcion != "nuevo") {
		while ($field_fortalezasa = mysql_fetch_array($query_fortalezasa)) {
			$nrofortalezasa++;
			?>
			<tr class="trListaBody" onclick="mClk(this, 'sel_fortalezasa');" id="fortalezasa_<?=$nrofortalezasa?>">
				<td align="center">
					<?=$nrofortalezasa?>
                </td>
                <td>
					<?=$field_fortalezasa['Descripcion']?>
                </td>
			</tr>
			<?
		}
	}
	?>
    </tbody>
</table>
</div>
</center>
<input type="hidden" id="nro_fortalezasa" value="<?=$nrofortalezasa?>" />
<input type="hidden" id="can_fortalezasa" value="<?=$nrofortalezasa?>" />
</form>

<form name="frm_fortalezaspa" id="frm_fortalezaspa">
<input type="hidden" name="sel_fortalezaspa" id="sel_fortalezaspa" />
<table width="1000" class="tblBotones">
	<tr>
    	<td>
        	<strong>HABILIDADES Y DESTREZAS TECNICAS POR ADQUIRIR</strong>
        </td>
		<td align="right">
			<input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'fortalezaspa');" <?=$disabled_submit?> />
		</td>
	</tr>
</table>
<center>
<div style="overflow:scroll; width:1000px; height:200px;">
<table width="100%" class="tblLista">
	<thead>
	<tr class="trListaHead">
        <th scope="col" width="25">#</th>
        <th scope="col" align="left">Descripci&oacute;n</th>
    </tr>
    </thead>
    
    <tbody id="lista_fortalezaspa">
    <?
	$nrofortalezaspa = 0;
	if ($opcion != "nuevo") {
		while ($field_fortalezaspa = mysql_fetch_array($query_fortalezaspa)) {
			$nrofortalezaspa++;
			?>
			<tr class="trListaBody" onclick="mClk(this, 'sel_fortalezaspa');" id="fortalezaspa_<?=$nrofortalezaspa?>">
				<td align="center">
					<?=$nrofortalezaspa?>
                </td>
                <td>
					<?=$field_fortalezaspa['Descripcion']?>
                </td>
			</tr>
			<?
		}
	}
	?>
    </tbody>
</table>
</div>
</center>
<input type="hidden" id="nro_fortalezaspa" value="<?=$nrofortalezaspa?>" />
<input type="hidden" id="can_fortalezaspa" value="<?=$nrofortalezaspa?>" />
</form>
</div>

<div id="tab5" style="display:none;">
<form name="frm_capacitacioncc" id="frm_capacitacioncc">
<input type="hidden" name="sel_capacitacioncc" id="sel_capacitacioncc" />
<table width="1000" class="tblBotones">
	<tr>
    	<td>
        	<strong>CAPACITACION REQUERIDAS PARA DESARROLLAR COMPETENCIAS CONDUCTUALES</strong>
        </td>
		<td align="right">
			<input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'capacitacioncc');" <?=$disabled_submit?> />
		</td>
	</tr>
</table>
<center>
<div style="overflow:scroll; width:1000px; height:200px;">
<table width="100%" class="tblLista">
	<thead>
	<tr class="trListaHead">
        <th scope="col" width="25">#</th>
        <th scope="col" align="left">Necesidad/Objetivo</th>
        <th scope="col" width="400" align="left">Curso</th>
        <th scope="col" width="50">Prioridad</th>
    </tr>
    </thead>
    
    <tbody id="lista_capacitacioncc">
    <?
	$nrocapacitacioncc = 0;
	if ($opcion != "nuevo") {
		while ($field_capacitacioncc = mysql_fetch_array($query_capacitacioncc)) {
			$nrocapacitacioncc++;
			?>
			<tr class="trListaBody" onclick="mClk(this, 'sel_capacitacioncc');" id="capacitacioncc_<?=$nrocapacitacioncc?>">
				<td align="center">
					<?=$nrocapacitacioncc?>
                </td>
                <td>
                    <strong><?=$field_capacitacioncc['Necesidad']?></strong><br />
                    <?=$field_capacitacioncc['Objetivo']?>
                </td>
                <td>
                    <?=$field_capacitacioncc['NomCurso']?>
                </td>
                <td align="center">
                    <?=strtoupper(printValoresGeneral("PRIORIDAD", $field_capacitacioncc['Prioridad']))?>
                </td>
			</tr>
			<?
		}
	}
	?>
    </tbody>
</table>
</div>
</center>
<input type="hidden" id="nro_capacitacioncc" value="<?=$nrocapacitacioncc?>" />
<input type="hidden" id="can_capacitacioncc" value="<?=$nrocapacitacioncc?>" />
</form>

<form name="frm_capacitacionmdc" id="frm_capacitacionmdc">
<input type="hidden" name="sel_capacitacionmdc" id="sel_capacitacionmdc" />
<table width="1000" class="tblBotones">
	<tr>
    	<td>
        	<strong>CAPACITACION PARA DESARROLLAR COMPETENCIAS TECNICAS REQUERIDAS PARA EJECUTAR SUS FUNCIONES DE ACUERDO AL MANUAL DESCRIPTIVO DE CARGOS</strong>
        </td>
		<td align="right" width="200">
			<input type="button" class="btLista" value="Insertar" onclick="quitarLinea(this, 'capacitacionmdc');" <?=$disabled_submit?> />
			<input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'capacitacionmdc');" <?=$disabled_submit?> />
		</td>
	</tr>
</table>
<center>
<div style="overflow:scroll; width:1000px; height:200px;">
<table width="100%" class="tblLista">
	<thead>
	<tr class="trListaHead">
        <th scope="col" width="25">#</th>
        <th scope="col" align="left">Descripci&oacute;n</th>
    </tr>
    </thead>
    
    <tbody id="lista_capacitacionmdc">
    <?
	$nrocapacitacionmdc = 0;
	if ($opcion != "nuevo") {
		while ($field_capacitacionmdc = mysql_fetch_array($query_capacitacionmdc)) {
			$nrocapacitacionmdc++;
			?>
			<tr class="trListaBody" onclick="mClk(this, 'sel_capacitacionmdc');" id="capacitacionmdc_<?=$nrocapacitacionmdc?>">
				<td align="center">
					<?=$nrocapacitacionmdc?>
                </td>
                <td>
					<?=$field_capacitacionmdc['Descripcion']?>
                </td>
			</tr>
			<?
		}
	}
	?>
    </tbody>
</table>
</div>
</center>
<input type="hidden" id="nro_capacitacionmdc" value="<?=$nrocapacitacionmdc?>" />
<input type="hidden" id="can_capacitacionmdc" value="<?=$nrocapacitacionmdc?>" />
</form>
</div>
</body>
</html>