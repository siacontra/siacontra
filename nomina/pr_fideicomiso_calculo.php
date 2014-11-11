<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Prestaci&oacute;n de Antiguedad</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=pr_fideicomiso_calculo" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<div class="divBorder" style="width:1100px;">
<table width="1100" class="tblFiltro">
	<tr>
		<td align="right" width="125">Periodo:</td>
		<td><input type="text" id="Periodo" style="width:60px; font-weight:bold; font-size:12px;" value="<?=$Anio?>" /></td>
		<td align="right" width="125"></td>
		<td>
		</td>
	</tr>
	<tr>
		<td align="right">Empleado:</td>
		<td class="gallery clearfix">
        	<input type="hidden" id="CodPersona" value="<?=$CodPersona?>" />
			<input type="text" id="NomCompleto" style="width:250px;" class="disabled" value="<?=$NomCompleto?>" disabled="disabled" />
			<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=CodPersona&nom=NomCompleto&ventana=fideicomiso_calculo_empleado_sel&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td align="right">Documento:</td>
		<td><input type="text" id="Ndocumento" style="width:100px;" class="disabled" disabled /></td>
	</tr>
	<tr>
		<td align="right">Antiguedad:</td>
		<td>
        	<input type="text" id="Anios" style="width:25px; text-align:right;" class="disabled" disabled /><i>Anios</i> &nbsp; &nbsp;
        	<input type="text" id="Meses" style="width:25px; text-align:right;" class="disabled" disabled /><i>Meses</i> &nbsp; &nbsp;
        	<input type="text" id="Dias" style="width:25px; text-align:right;" class="disabled" disabled /><i>Dias</i> &nbsp; &nbsp;
		</td>
		<td align="right">Fecha de Ingreso:</td>
		<td><input type="text" id="Fingreso" style="width:100px;" class="disabled" disabled /></td>
	</tr>
    <tr>
    	<td colspan="4" align="center">
			<input type="button" value="Mostrar" onClick="fideicomiso_calculo_listado(this.form);" />
        </td>
    </tr>
</table>
</div>
</form><br />

<form name="frm_calculo" id="frm_calculo">
<center>
<table width="1100" class="tblBotones">
	<tr>
		<td align="right">
			<input type="button" value="Procesar Calculo" style="width:100px;" onClick="fideicomiso_calculo(this.form, 'procesar');" />
		</td>
	</tr>
</table>
<div style="overflow:scroll; width:1100px; height:350px;" id="listaCalculo">
<table class="tblLista">
    <thead>
    <tr class="trListaHead">
        <th width="15" scope="col">Pr.</th>
        <th width="60" scope="col">PERIODO</th>
        <th width="80" scope="col">SUELDO MENSUAL</th>
        <?
        $sql = "SELECT CodConcepto, Descripcion, Abreviatura
                FROM pr_concepto
                WHERE FlagBonoRemuneracion = 'S'
                ORDER BY CodConcepto";
        $query_conceptos = mysql_query($sql) or die($sql.mysql_error());	$filtro_remuneraciones = "";
        while($field_conceptos = mysql_fetch_array($query_conceptos)) {
            $filtro_remuneraciones .= ", (SELECT tnec1.Monto
                                          FROM
                                                pr_tiponominaempleadoconcepto tnec1
                                          WHERE
                                                tnec1.Periodo = afd.Periodo AND
                                                tnec1.CodPersona = afd.CodPersona AND
                                                tnec1.CodConcepto = '".$field_conceptos['CodConcepto']."') AS _".$field_conceptos['CodConcepto'];
            ?><th width="80" scope="col" title="<?=($field_conceptos['Descripcion'])?>"><?=$field_conceptos['Abreviatura']?></th><?
        }
        ?>
        <th width="60" scope="col">ALI. B. VAC.</th>
        <th width="60" scope="col">ALI. B. FIN AÑO</th>
        <th width="80" scope="col">REMUN. DIARIA</th>
        <th width="80" scope="col">SUELDO + ALICUOTAS</th>
        <th width="35" scope="col">DIAS</th>
        <th width="80" scope="col">PREST. ANTIG. MENSUAL</th>
        <th width="80" scope="col">PREST. COMPL. (2 DIAS)</th>
        <th width="80" scope="col">PREST. ACUMULADA</th>
        <th width="50" scope="col">TASA DE INTERES (%)</th>
        <th width="50" scope="col">DIAS DEL MES</th>
        <th width="80" scope="col">INTERES MENSUAL</th>
        <th width="80" scope="col">INTERES ACUMULADO</th>
        <th width="80" scope="col">ANTICIPO PRESTACION</th>
    </tr>
    </thead>
</table>
</div>
</center>
</form>
