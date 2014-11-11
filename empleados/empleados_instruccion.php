<?php
//	------------------------------------
if ($filtrar == "default") {
	$CodPersona = $registro;
}
//	------------------------------------
//	datos del empleado
$sql = "SELECT
			p.CodPersona,
			p.NomCompleto,
			e.CodEmpleado
		FROM
			mastpersonas p
			INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
		WHERE p.CodPersona = '".$CodPersona."'";
$query_empleado = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_empleado)) $field_empleado = mysql_fetch_array($query_empleado);
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Instrucci&oacute;n Acad&eacute;mica del Empleado</td>
		<td align="right"><a class="cerrar" href="#" onclick="document.getElementById('frmentrada').submit()">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=empleados_lista" method="post" autocomplete="off">
<?=filtroEmpleados()?>
<input type="hidden" name="CodPersona" id="CodPersona" value="<?=$field_empleado['CodPersona']?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:900px;">
<table width="900" class="tblFiltro">
	<tr>
    	<td colspan="2" class="divFormCaption">Datos del Empleado</td>
    </tr>
	<tr>
		<td align="right" width="125">Empleado:</td>
		<td>
        	<input type="text" id="CodEmpleado" style="width:60px;" class="codigo" value="<?=$field_empleado['CodEmpleado']?>" disabled />
		</td>
	</tr>
	<tr>
		<td align="right">Nombre Completo:</td>
		<td>
        	<input type="text" id="NomCompleto" style="width:500px;" class="codigo" value="<?=$field_empleado['NomCompleto']?>" disabled />
		</td>
	</tr>
</table>
</div><br />

<table width="900" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current"><a href="#" onclick="mostrarTab('tab', 1, 3);">Carreras</a></li>
            <li id="li2" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 2, 3);">Idiomas</a></li>
            <li id="li3" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 3, 3);">Otros Estudios</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<center>
<div id="tab1" style="display:block;">
<iframe name="carreras" id="carreras" style="border:solid 1px #CDCDCD; width:900px; height:400px;" src="gehen.php?anz=empleados_instruccion_carreras_lista&filtrar=default&CodPersona=<?=$CodPersona?>&concepto=<?=$concepto?>&_APLICACION=<?=$_APLICACION?>"></iframe>
</div>

<div id="tab2" style="display:none;">
<iframe name="idiomas" id="idiomas" style="border:solid 1px #CDCDCD; width:900px; height:400px;" src="gehen.php?anz=empleados_instruccion_idiomas_lista&filtrar=default&CodPersona=<?=$CodPersona?>&concepto=<?=$concepto?>&_APLICACION=<?=$_APLICACION?>"></iframe>
</div>

<div id="tab3" style="display:none;">
<iframe name="cursos" id="cursos" style="border:solid 1px #CDCDCD; width:900px; height:400px;" src="gehen.php?anz=empleados_instruccion_cursos_lista&filtrar=default&CodPersona=<?=$CodPersona?>&concepto=<?=$concepto?>&_APLICACION=<?=$_APLICACION?>"></iframe>
</div>
</center>
</form>

<div class="gallery clearfix">
<a href="../lib/listas/listado_centro_estudio.php?filtrar=default&cod=CodCentroEstudio&nom=NomCentroEstudio&FlagEstudio=S&ventana=selListadoIFrame&marco=carreras&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe1]" id="a_CodCentroCosto" style=" display:none;"></a>

<a href="../lib/listas/listado_centro_estudio.php?filtrar=default&cod=CodCentroEstudio&nom=NomCentroEstudio&FlagEstudio=S&ventana=selListadoIFrame&marco=cursos&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe2]" id="a_CodCentroCosto2" style=" display:none;"></a>

<a href="../lib/listas/listado_cursos.php?filtrar=default&cod=CodCurso&nom=NomCurso&ventana=selListadoIFrame&marco=cursos&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe3]" id="a_CodCurso" style=" display:none;"></a>
</div>