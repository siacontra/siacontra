<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location:../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript02.js"></script>
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
		<td class="titulo">Presupuesto | <?=$accion?></td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), '<?=$regresar?>?limit=0');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="<?=$regresar?>.php" method="POST" onsubmit="return iniciarCapacitacion(this, '<?=$accion?>');">

<?php
include("fphp.php");
connect();
list($capacitacion, $organismo)=SPLIT( '[/.-]', $registro);
$sql="SELECT * FROM mastorganismos, mastpersonas WHERE 1";
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
			<? if($tab1){?><li><a onclick="document.getElementById('tab1').style.display='block'; document.getElementById('tab2').style.display='none';" href="#">Datos Generales</a></li><? }?>
			<? if($tab2){?><li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block'; ;" href="#">Detalle de Presupuesto</a></li><? }?>
			</ul>
			</div>
		</td>
	</tr>
</table>
<!--////////////////////////// **********DATOS GENERALES DEL PRESUPUESTO*************  ///////////////////////-->   
<div id="tab1" style="display:block;">
<div style="width:895px" class="divFormCaption">Informaci&oacute;n de Presupuesto</div>
<table width="895" class="tblForm">
	<tr>
		<td class="tagForm">Organismo:</td>
		<td>
			<select name="organismo" id="organismo" class="selectBig"><?php getOrganismos($field['CodOrganismo'], 1); ?>
			</select>
		</td>
		<td colspan="2"></td>
	</tr>	
	<tr>
		<td class="tagForm">Solicitante:</td>
		<td>
			<input name="solicitante" type="text" id="solicitante" size="50" value="<?=$field['NomCompleto']?>"/> Cargar Partidas: <input name="bt_examinar" type="button" id="bt_examinar" value="Cargar Partidas" onclick="cargarVentana(this.form, 'lista_partidas.php?limit=0&campo=1', 'height=500, width=800, left=200, top=200, resizable=yes');" /> 
		</td>
		<td rowspan="2"></td>		
	</tr>
	<tr>		
		<td class="tagForm">A&ntilde;o P.:</td>
		<td><? $ano = date(Y); // devuelve el año
		       $fcreacion= date("d-m-Y");//Fecha de Creación ?>
			<input name="anop" type="text" id="anop" size="3" value="<?=$ano?>" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">F. Creaci&oacute;n:</td>
		<td colspan="2">
			<input name="fcreacion" type="text" id="fcreacion" size="8" value="<?=$fcreacion?>" />
			<!--<input name="nomcentro" type="text" id="nomcentro" size="75" value="<?=$field['Centro']?>" readonly />-->
		</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
			<select name="estado" id="estado">
			      <OPTION value="NoSelecciono"></OPTION>
				 <OPTION VALUE="PREPARACION">Preparaci&oacute;n</OPTION>
                 <OPTION VALUE="ANULADO">Anulado</OPTION>
                 <OPTION VALUE="APROBADO">Aprobado</OPTION>
			</select>*
		</td>
		<td class="tagForm"></td>
	</tr>
	<tr><td></td></tr>
	<tr>
		<td class="tagForm"></td>
	    <td class="divFormCaption"><b>Duraci&oacute;n de Presupuesto</b></td>
		<td class="tagForm"></td>
	</tr>
	<tr>
		<td class="tagForm">F. Inicio:</td>
	    <td colspan="2"><input name="finicio" type="text" id="finicio" size="10" maxlength="10"/>*</td>
	</tr>	
	<tr>
		<td class="tagForm">F. Termino:</td>
	    <td colspan="2"><input name="ftermino" type="text" id="ftermino" size="10" maxlength="10"/>*</td>
	</tr>	
	<tr>
		<td class="tagForm">Duraci&oacute;n:</td>
	    <td><input name="vacantes" type="text" id="vacantes" size="6" maxlength="3"/> d&iacute;as.</td>
		<td colspan="1"></td>
	</tr>
	<tr><td></td></tr>
	<tr>
		<td class="tagForm"></td>
		<td class="divFormCaption"><b>Presupuestado</b></td><td width="200"></td>
	</tr>
	<tr>
	  <td class="tagForm">Monto Autorizado:</td>
	  <td ><input name="autori" type="text" id="autori" size="20" maxlength="12" dir="rtl" value="<?=$asumido?>"/>
	   Monto Utilizado:<input name="mutuli" id="mutili" type="text" size="20" maxlength="12" dir="rtl" value="<?=$asumido?>"/></td>
	</tr>
	<tr>
	  <td class="tagForm">Monto Restante:</td>
	  <td><input name="mrest" id="mrest" type="text" size="20" maxlength="12" dir="rtl" value="<?=$asumido?>"/></td>
	</tr><tr><td></td></tr>
	<tr>
	   <td class="tagForm">Preparado por:</td>
	   <td><input name="prepor" id="prepor" type="text" size="60" value="<?=$field['NomCompleto']?>" readonly/></td>
	<tr>
	<tr>
	   <td class="tagForm">Aprobado por:</td>
	   <td><input name="apropor" id="apropor" type="text" size="60"/></td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td colspan="1">
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$field['UltimoUsuario']?>" readonly />
			<input name="ult_fecha" type="text" id="ult_fecha" size="9" value="<?=$field['UltimaFecha']?>" readonly />
		</td>
	</tr>
</table>
<center>
<input name="btguardar" type="submit" id="btguardar" value="Guardar Registro" onclick="cargarPagina(this.form, 'presupuesto.php');"/>
<input name="btcancelar" type="button" id="btcancelar" value="Cancelar" onclick="cargarPagina(this.form, 'presupuesto.php');"/>
</center><br />
</div>
<!--////////////////////////// *****************               *********************  ///////////////////////--> 
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
		<td><iframe name="iHorario" class="frameTab" style="height:600px; width:898px;" id="iHorario" src="capacitacion_horarios.php?capacitacion=<?=$field['Capacitacion']?>&organismo=<?=$field['CodOrganismo']?>&accion=INICIAR"></iframe></td>
	</tr>
	</tr>
</table>
</div>

<div id="tab5" style="display:none;">
<div style="width:900px" class="divFormCaption">Gastos </div>
<table align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td><iframe name="iGastos" class="frameTab" style="height:600px; width:898px;" id="iHorario" src="capacitacion_gastos.php?capacitacion=<?=$field['Capacitacion']?>&organismo=<?=$field['CodOrganismo']?>"></iframe></td>
	</tr>
	</tr>
</table>
</div>
</form>
</body>
</html>
