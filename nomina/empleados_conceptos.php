<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
$_SESSION['_PROCESO'] = "";
$_SESSION['_NOMINA'] = "";
$_SESSION['_PERIODO'] = "";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_nomina.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Asignaci&oacute;n de Conceptos </td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="empleados_conceptos.php" method="POST" onsubmit="return verificarEmpleadoConceptos(this);">
<?
include("fphp_nomina.php");
connect();
//	------------
$sql="SELECT mp.CodPersona, mp.NomCompleto, me.CodEmpleado, me.CodTipoNom, me.CodOrganismo, tn.Nomina FROM mastpersonas mp INNER JOIN mastempleado me ON (mp.CodPersona=me.CodPersona) INNER JOIN tiponomina tn ON (me.CodTipoNom=tn.CodTipoNom) WHERE mp.CodPersona='".$registro."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query)!=0) $field=mysql_fetch_array($query);
?>
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
    <tr>
        <td align="right" width="150">Persona:</td>
        <td><input name="persona" type="text" id="persona" size="10" value="<?=$field["CodEmpleado"]?>" readonly /></td>
        <td align="right" width="150">Tipo de N&oacute;mina:</td>
        <td><input name="nomnomina" type="text" id="nomnomina" size="50" value="<?=$field["Nomina"]?>" readonly /></td>
    </tr>
    <tr>
        <td align="right">Nombre Completo:</td>
        <td><input name="nompersona" type="text" id="nompersona" size="75" value="<?=$field["NomCompleto"]?>" readonly /></td>
        <td align="right">&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</table>
</div><br />

<div style="width:1000px" class="divFormCaption">Datos del Concepto</div>
<table width="1000" class="tblForm">
    <tr>
        <td class="tagForm">Concepto:</td>
        <td>
            <input name="codconcepto" type="hidden" id="codconcepto" />
            <input name="nomconcepto" type="text" id="nomconcepto" size="56" readonly />
            <input name="btConcepto" type="button" id="btConcepto" value="..." onclick="window.open('lista_conceptos.php?limit=0&codpersona=<?=$registro?>&codtiponom=<?=$field['CodTipoNom']?>', 'wLista', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=500, width=800, left=200, top=200, resizable=yes');" />*
        </td>
    	<td class="tagForm">Desde:</td>
        <td>
        	<select name="pdesde" id="pdesde">
                <?=getPeriodos('', $field['CodTipoNom'], $field['CodOrganismo'], 0)?>
            </select>*
        </td>
    	<td class="tagForm">Hasta:</td>
        <td>
        	<select name="phasta" id="phasta">
            	<option value=""></option>
               <?=getPeriodos('', $field['CodTipoNom'], $field['CodOrganismo'], 1)?>
            </select>
        </td>
    </tr>
    <tr>
        <td class="tagForm">Proceso:</td>
        <td>
        	<input type="checkbox" name="flagproceso" id="flagproceso" value="S" onclick="setTipoProcesoTodos(this.checked);" />
            <input name="codproceso" type="hidden" id="codproceso" value="[TODOS]" />
            <input name="nomproceso" type="text" id="nomproceso" size="50" value="[TODOS]" readonly />
            <input name="btProceso" type="button" id="btProceso" value="..." onclick="window.open('lista_tipos_procesos.php?limit=0', 'wLista', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=500, width=800, left=200, top=200, resizable=yes');" disabled="disabled" />*
        </td>
    	<td class="tagForm">Monto:</td>
        <td><input type="text" name="monto" id="monto" size="20" maxlength="14" /></td>
    	<td class="tagForm">Cantidad:</td>
        <td><input type="text" name="cantidad" id="cantidad" size="20" maxlength="14" /></td>
    </tr>
    <tr>
        <td class="tagForm">Estado:</td>
        <td colspan="5">
        	<select name="status" id="status">
            	<option value=""></option>
                <?=getStatus("", 0)?>
            </select>*
        </td>
    </tr>
</table>

<input type="hidden" name="registro" id="registro" value="<?=$registro?>" />
<input type="hidden" name="nomina" id="nomina" value="<?=$field["CodTipoNom"]?>" />
<input type="hidden" name="secuencia" id="secuencia" />
<input type="hidden" name="accion" id="accion" value="INSERTAR" />
<input type="hidden" name="elemento" id="elemento" />
<table width="1000" class="tblBotones">
 <tr>
	<td align="right">
		<input name="btInsertar" type="submit" class="btLista" id="btInsertar" value="Insertar" />
		<input name="btLimpiar" type="button" class="btLista" id="btLimpiar" value="Limpiar" onclick="limpiarEmpleadoConceptos(this.form);" /> | 
		<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="editarEmpleadoConceptos(this.form);" />
		<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarEmpleadoConceptos(this.form);" />
	</td>
 </tr>
</table>

<table width="1000" class="tblLista">
    <tr class="trListaHead">
        <th scope="col" width="50">#</th>
        <th scope="col" colspan="2">Concepto</th>
        <th scope="col" width="75">Desde</th>
        <th scope="col" width="75">Hasta</th>
        <th scope="col" width="125">Monto</th>
        <th scope="col" width="50">Cantidad</th>
        <th scope="col">Procesos</th>
        <th scope="col" width="75">Estado</th>
    </tr>
	<?
    $sql="SELECT pec.*, pc.Descripcion AS NomConcepto FROM pr_empleadoconcepto pec INNER JOIN pr_concepto pc ON (pec.CodConcepto=pc.CodConcepto) WHERE pec.CodPersona='".$registro."' ORDER BY pec.PeriodoDesde, pec.PeriodoHasta, pec.CodConcepto";
    $query_conceptos=mysql_query($sql) or die ($sql.mysql_error());
    while($field_conceptos=mysql_fetch_array($query_conceptos)) {
		list($a, $m, $d)=SPLIT('[/.-]', $field_conceptos['PeriodoDesde']); $pdesde=$d."-".$m."-".$a; if ($pdesde="00-00-0000") $pdesde=""; 
		list($a, $m, $d)=SPLIT('[/.-]', $field_conceptos['PeriodoHasta']); $phasta=$d."-".$m."-".$a; if ($phasta="00-00-0000") $phasta=""; 
		$periodo="$pdesde - $phasta";
		if ($field_conceptos['Estado']=="A") $status="Activo"; else $status="Inactivo";
		$id=$field_conceptos['CodConcepto']."-".$field_conceptos['Secuencia'];
		?>
        <tr class="trListaBody" onclick="mClk(this, 'elemento');" id='<?=$id?>'>
			<td align="center"><?=$field_conceptos['CodConcepto']?></td>
			<td width="200"><?=($field_conceptos['NomConcepto'])?></td>
			<td width="20" align="center"><?=$field_conceptos['TipoAplicacion']?></td>
			<td align="center"><?=$field_conceptos['PeriodoDesde']?></td>
			<td align="center"><?=$field_conceptos['PeriodoHasta']?></td>
			<td align="right"><?=number_format($field_conceptos['Monto'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field_conceptos['Cantidad'], 2, ',', '.')?></td>
			<td align="center"><?=$field_conceptos['Procesos']?></td>
			<td align="center"><?=$status?></td>
		</tr>
	<? } ?>
</table>
</form>
</body>
</html>
