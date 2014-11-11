<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$maxlimit = $_SESSION["MAXLIMIT"];
}
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Calculo de Fideicomiso</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="pr_fideicomiso_calculo_pdf.php" method="post" target="iReporte">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td align="right" width="125">Periodo:</td>
		<td>
        	<input type="text" name="PeriodoDesde" id="PeriodoDesde" style="width:40px;" value="<?=$Anio?>" />
            <strong>hasta:</strong>
            <input type="text" name="PeriodoHasta" id="PeriodoHasta" style="width:40px;" value="<?=$Anio?>" />
        </td>
		<td align="right" width="125"></td>
		<td>
		</td>
	</tr>
	<tr>
		<td align="right">Empleado:</td>
		<td class="gallery clearfix">
        	<input type="hidden" name="CodPersona" id="CodPersona" />
			<input type="text" name="NomCompleto" id="NomCompleto" style="width:250px;" class="disabled" readonly />
			<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=CodPersona&nom=NomCompleto&ventana=fideicomiso_calculo_empleado_sel&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td align="right">Documento:</td>
		<td><input type="text" name="Ndocumento" id="Ndocumento" style="width:100px;" class="disabled" disabled /></td>
	</tr>
	<tr>
		<td align="right">Antiguedad:</td>
		<td>
        	<input type="text" name="Anios" id="Anios" style="width:25px; text-align:right;" class="disabled" readonly /><i>Anios</i> &nbsp; &nbsp;
        	<input type="text" name="Meses" id="Meses" style="width:25px; text-align:right;" class="disabled" readonly /><i>Meses</i> &nbsp; &nbsp;
        	<input type="text" name="Dias" id="Dias" style="width:25px; text-align:right;" class="disabled" readonly /><i>Dias</i> &nbsp; &nbsp;
		</td>
		<td align="right">Fecha de Ingreso:</td>
		<td><input type="text" name="Fingreso" id="Fingreso" style="width:100px;" class="disabled" readonly /></td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>

<br />

<center>
<iframe name="iReporte" id="iReporte" style="border:solid 1px #CDCDCD; width:1000px; height:600px;"></iframe>
</center>
</form> 