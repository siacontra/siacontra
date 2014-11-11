<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<style type="text/css">
<!--
UNKNOWN {
        FONT-SIZE: small
}
#header {
        FONT-SIZE: 93%; BACKGROUND: url(bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal
}
#header UL {
        PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none
}
#header LI {
        PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px
}
#header A {
        PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none
}
#header A {
        FLOAT: none
}
#header A:hover {
        COLOR: #333
}
#header #current {
        BACKGROUND-IMAGE: url(left_on.gif)
}
#header #current A {
        BACKGROUND-IMAGE: url(right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333
}
-->
</style>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Capacitaci&oacute;n | Aprobaci&oacute;n</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), '<?=$regresar?>?limit=0');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="<?=$regresar?>.php" method="POST" onsubmit="return aprobarCapacitacion(this);">

<?php
include("fphp.php");
connect();
list($capacitacion, $organismo)=SPLIT( '[/.-]', $registro);
$sql="SELECT rc.*, mp.NomCompleto, mc.CodMunicipio, mm.CodEstado, me.CodPais, cu.Descripcion AS Curso, ce.Descripcion AS Centro FROM rh_capacitacion rc INNER JOIN mastpersonas mp ON (rc.Solicitante=mp.CodPersona) INNER JOIN mastciudades mc ON (rc.CodCiudad=mc.CodCiudad) INNER JOIN mastmunicipios mm ON (mc.CodMunicipio=mm.CodMunicipio) INNER JOIN mastestados me ON (mm.CodEstado=me.CodEstado) INNER JOIN rh_cursos cu ON (rc.CodCurso=cu.CodCurso) INNER JOIN rh_centrosestudios ce ON (rc.CodCentroEstudio=ce.CodCentroEstudio) WHERE rc.Capacitacion='".$capacitacion."' AND rc.CodOrganismo='".$organismo."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) $field=mysql_fetch_array($query);
list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaDesde']); $fdesde=$d.'/'.$m.'/'.$a;
list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaHasta']); $fhasta=$d.'/'.$m.'/'.$a;
$estimado=number_format($field['CostoEstimado'], 2, ',', '');
$maxasumido=number_format($field['MontoMaxAsumido'], 2, ',', '');
$asumido=number_format($field['MontoAsumido'], 2, ',', '');
$limit=(int) $limit;
echo "
<input type='hidden' name='filtro' id='filtro' value='".$filtro."' />
<input type='hidden' name='regresar' id='regresar' value='".$regresar."' />
<input type='hidden' name='forganismo' id='forganismo' value='".$forganismo."' />
<input type='hidden' name='chkorganismo' id='chkorganismo' value='".$chkorganismo."' />
<input type='hidden' name='fstatus' id='fstatus' value='".$fstatus."' />
<input type='hidden' name='chkstatus' id='chkstatus' value='".$chkstatus."' />
<input type='hidden' name='fcursos' id='fcursos' value='".$fcursos."' />
<input type='hidden' name='chkcursos' id='chkcursos' value='".$chkcursos."' />
<input type='hidden' name='ftcursos' id='ftcursos' value='".$ftcursos."' />
<input type='hidden' name='chktcursos' id='chktcursos' value='".$chktcursos."' />
<input type='hidden' name='fcapacitacion' id='fcapacitacion' value='".$fcapacitacion."' />
<input type='hidden' name='chkcapacitacion' id='chkcapacitacion' value='".$chkcapacitacion."' />";
?>

<table width="900" align="center">
  <tr>
		<td>
			<div id="header">
			<ul>
			<!-- CSS Tabs -->
			<? if($tab1){?><li><a onclick="document.getElementById('tab1').style.display='block'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none';" href="#">Capacitaci&oacute;n</a></li><? }?>
			<? if($tab2){?><li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none';" href="#">Fundamentaci&oacute;n</a></li><? }?>
			<? if($tab3){?><li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='block'; document.getElementById('tab4').style.display='none';" href="#">Participantes</a></li><? }?>
			<? if($tab4){?><li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='block';" href="#">Horario</a></li><? }?>
			<? if($tab5){?><li><a onclick="javascript:alert('&iexcl;DEBE GUARDAR PRIMERO LA CAPACITACI&Oacute;N!');" href="#">Gastos</a></li><? }?>
			<? if($tab6){?><li><a onclick="javascript:alert('&iexcl;DEBE GUARDAR PRIMERO LA CAPACITACI&Oacute;N!');" href="#">Evaluaci&oacute;n del Curso</a></li><? }?>
			<? if($tab7){?><li><a onclick="javascript:alert('&iexcl;DEBE GUARDAR PRIMERO LA CAPACITACI&Oacute;N!');" href="#">Reporte de Evaluaci&oacute;n</a></li><? }?>
			</ul>
			</div>
		</td>
	</tr>
</table>

<div id="tab1" style="display:block;">
<div style="width:900px" class="divFormCaption">Datos de la Capacitaci&oacute;n</div>
<table width="900" class="tblForm">
	<tr>
		<td class="tagForm">Organismo:</td>
		<td>
			<select name="organismo" id="organismo" class="selectBig">
				<?php getOrganismos($field['CodOrganismo'], 1); ?>
			</select>
		</td>
		<td class="tagForm">Capacitaci&oacute;n:</td>
		<td><input name="codigo" type="text" id="codigo" size="10" value="<?=$field['Capacitacion']?>" readonly /></td>
	</tr>	
	<tr>
		<td class="tagForm">Solicitante:</td>
		<td>
			<input name="codempleado" type="hidden" id="codempleado" value="<?=$field['Solicitante']?>" />
			<input name="nomempleado" type="text" id="nomempleado" size="75" value="<?=$field['NomCompleto']?>" readonly />
		</td>		
		<td class="tagForm">Estado:</td>
		<td>
			<input name="status" type="hidden" id="status" value="P" />
			<input type="text" size="25" value="En Planificaci&oacute;n" readonly />
		</td>
	</tr>
	<tr>		
		<td class="tagForm">Curso:</td>
		<td>
			<input name="codcurso" type="hidden" id="codcurso" value="<?=$field['CodCurso']?>" />
			<input name="nomcurso" type="text" id="nomcurso" size="75" value="<?=$field['Curso']?>" readonly />
		</td>
		<td class="tagForm">Tipo:</td>
		<td>
			<select name="tcurso" id="tcurso">
				<?php getMiscelaneos($field['TipoCurso'], "TIPOCURSO", 1); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Centro de Estudio:</td>
		<td colspan="3">
			<input name="codcentro" type="hidden" id="codcentro" value="<?=$field['CodCentroEstudio']?>" />
			<input name="nomcentro" type="text" id="nomcentro" size="75" value="<?=$field['Centro']?>" readonly />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Pais:</td>
		<td>
			<select name="pais" id="pais" class="selectMed">
				<?php getPaises($field['CodPais'], 1); ?>
			</select>
		</td>
		<td class="tagForm">Estado:</td>
		<td>
			<select name="estado" id="estado" class="selectMed">
				<?php getEstados($field['CodEstado'], $field['CodPais'], 1); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Municipio:</td>
		<td>                                                                              
			<select name="municipio" id="municipio" class="selectMed">
				<?php getMunicipios($field['CodMunicipio'], $field['CodEstado'], 1); ?>
			</select>
		</td>
		<td class="tagForm">Ciudad:</td>
		<td>
			<select name="ciudad" id="ciudad" class="selectMed">
				<?php getCiudades($field['CodCiudad'], $field['CodMunicipio'], 1); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Tel&eacute;fono:</td>
	    <td><input name="tel" type="text" id="tel" size="15" maxlength="15" value="<?=$field['TelefonoContacto']?>" readonly /></td>
		<td class="tagForm">Aula:</td>
		<td><input name="aula" type="text" id="aula" size="10" maxlength="5" value="<?=$field['Aula']?>" readonly /></td>
	</tr>
	<tr>
		<td class="tagForm">Expositor:</td>
	    <td colspan="3"><input name="expositor" type="text" id="expositor" size="75" maxlength="100" value="<?=$field['Expositor']?>" readonly /></td>
	</tr>	
	<tr>
		<td class="tagForm">Tipo de Capacitaci&oacute;n:</td>
		<td>
			<select name="tcapacitacion" id="tcapacitacion">
				<?php getTipoCapacitacion($field['TipoCapacitacion'], 1); ?>
			</select>
		</td>
		<td class="tagForm">Modalidad:</td>
		<td>
			<select name="modalidad" id="modalidad">
				<?php getMiscelaneos($field['Modalidad'], "MODACAPAC", 1); ?>
			</select>
		</td>
	</tr>	
	<tr>
		<td class="tagForm">Vacantes:</td>
	    <td><input name="vacantes" type="text" id="vacantes" size="10" maxlength="3" value="<?=$field['Vacantes']?>" readonly />
	    </td>
		<td class="tagForm">Participantes:</td>
	    <td><input name="participantes" type="text" id="participantes" size="10" value="<?=$field['Participantes']?>" readonly /></td>
	</tr>
	<tr>
		<td class="tagForm">Per&iacute;odo:</td>
	    <td>
			<input name="fdesde" type="text" id="fdesde" size="15" maxlength="10" value="<?=$fdesde?>" readonly /> - 
	    	<input name="fhasta" type="text" id="fhasta" size="15" maxlength="10" value="<?=$fhasta?>" readonly />
		</td>
		<td class="tagForm">Duraci&oacute;n:</td>
	    <td>
			<input name="dias" type="text" id="dias" size="5" value="<?=$dias?>" readonly />Dias 
	    	<input name="horas" type="text" id="horas" size="5" value="<?=$horas?>" readonly />Horas
		</td>
	</tr>
	<tr>
		<td class="tagForm">Costo Estimado Total:</td>
	    <td><input name="costo" type="text" id="costo" size="30" dir="rtl" value="<?=$estimado?>" readonly /></td>
		<td class="tagForm">Monto Asumido:</td>
	    <td><input name="asumido" type="text" id="asumido" size="30" dir="rtl" value="<?=$maxasumido?>" readonly /></td>
	</tr>
	<tr>
		<td class="tagForm">Monto Utilizado:</td>
	    <td><input name="utilizado" type="text" id="utilizado" size="30" dir="rtl" value="<?=$asumido?>" readonly /></td>
		<td class="tagForm">Saldo:</td>
	    <td><input name="saldo" type="text" id="saldo" size="30" dir="rtl" value="<?=$saldo?>" readonly /></td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td colspan="3">
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$field['UltimoUsuario']?>" readonly />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field['UltimaFecha']?>" readonly />
		</td>
	</tr>
</table>
<center>
<input type="submit" value="Aprobar Capacitaci&oacute;n" />

<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onclick="cargarPagina(this.form, '<?=$regresar?>.php?limit=<?=$limit?>');" />
</center><br />
</div>

<div id="tab2" style="display:none;">
<div style="width:900px" class="divFormCaption">Fundamentaci&oacute;n de Requerimiento (Para ser llenado por el jefe inmediato) </div>
<table width="900" class="tblForm">
	<tr><td>1. &iquest;Cu&aacute;l es el objetivo de la capacitaci&oacute;n?:</td></tr>
	<tr><td><textarea name="funda1" id="funda1" cols="200" rows="3" readonly><?=$field['Fundamentacion1']?></textarea></td></tr>
	<tr><td>2. &iquest;En qu&eacute; medida la capacitaci&oacute;n va en relaci&oacute;n de los objetivos del area y de la organizaci&oacute;n?:</td></tr>
	<tr><td><textarea name="funda2" id="funda2" cols="200" rows="3" readonly><?=$field['Fundamentacion2']?></textarea></td></tr>
	<tr><td>3. Dias y horario mas factibles para el dictado:</td></tr>
	<tr><td><textarea name="funda3" id="funda3" cols="200" rows="3" readonly><?=$field['Fundamentacion3']?></textarea></td></tr>
	<tr><td>4. &iquest;Qu&eacute; se espera desp&uacute;es de la capacitaci&oacute;n?:</td></tr>
	<tr><td><textarea name="funda4" id="funda4" cols="200" rows="3" readonly><?=$field['Fundamentacion4']?></textarea></td></tr>
	<tr><td>5. &iquest;C&oacute;mo hace hoy su trabajo?:</td></tr>
	<tr><td><textarea name="funda5" id="funda5" cols="200" rows="3" readonly><?=$field['Fundamentacion5']?></textarea></td></tr>
	<tr><td>6. &iquest;Hay colaboradores dentro de la empresa que puedan dictar este curso?:</td></tr>
	<tr><td><textarea name="funda6" id="funda6" cols="200" rows="3" readonly><?=$field['Fundamentacion6']?></textarea></td></tr>
	</tr>
</table>
</div>

<div id="tab3" style="display:none;">
<div style="width:900px" class="divFormCaption">Participantes </div>
<table align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td><iframe name="iParticipante" class="frameTab" style="height:600px; width:898px;" id="iParticipante" src="capacitacion_participantes.php?capacitacion=<?=$field['Capacitacion']?>&organismo=<?=$field['CodOrganismo']?>&accion=APROBAR"></iframe></td>
	</tr>
	</tr>
</table>
</div>

<div id="tab4" style="display:none;">
<div style="width:900px" class="divFormCaption">Horarios </div>
<table align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td><iframe name="iHorario" class="frameTab" style="height:600px; width:898px;" id="iHorario" src="capacitacion_horarios.php?capacitacion=<?=$field['Capacitacion']?>&organismo=<?=$field['CodOrganismo']?>"></iframe></td>
	</tr>
	</tr>
</table>
</div>

</form>
</body>
</html>
