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
		<td class="titulo">M&eacute;ritos / Dem&eacute;ritos del Empleado</td>
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
            <li id="li1" onclick="currentTab('tab', this);" class="current"><a href="#" onclick="mostrarTab('tab', 1, 2);">M&eacute;ritos</a></li>
            <li id="li2" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 2, 2);">Dem&eacute;ritos</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<center>
<div id="tab1" style="display:block;">
<iframe name="meritos" id="meritos" style="border:solid 1px #CDCDCD; width:900px; height:450px;" src="gehen.php?anz=empleados_meritos_lista&filtrar=default&CodPersona=<?=$CodPersona?>&concepto=<?=$concepto?>&_APLICACION=<?=$_APLICACION?>"></iframe>
</div>

<div id="tab2" style="display:none;">
<iframe name="demeritos" id="demeritos" style="border:solid 1px #CDCDCD; width:900px; height:450px;" src="gehen.php?anz=empleados_demeritos_lista&filtrar=default&CodPersona=<?=$CodPersona?>&concepto=<?=$concepto?>&_APLICACION=<?=$_APLICACION?>"></iframe>
</div>
</center>
</form>

<div class="gallery clearfix">
<a href="../lib/listas/listado_personas.php?filtrar=default&cod=Responsable&nom=NomResponsable&ventana=selListadoIFrame&marco=meritos&iframe=true&width=950&height=450" rel="prettyPhoto[iframe1]" id="a_ResponsableMerito" style="display:none;"></a>

<a href="../lib/listas/listado_personas.php?filtrar=default&cod=Responsable&nom=NomResponsable&ventana=selListadoIFrame&marco=demeritos&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe2]" id="a_ResponsableDemerito" style="display:none;"></a>
</div>