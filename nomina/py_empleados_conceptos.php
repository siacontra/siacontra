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
<script type="text/javascript" language="javascript" src="fscript_proyeccion.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Asignaci&oacute;n de Conceptos Proyeccion </td>
		<td align="right"><a class="cerrar" href="py_ejecucion_procesos.php?CodProyeccion=<?=$ftproyeccion?>&chkproyeccion=01&ftproyeccion=<?=$ftproyeccion?>&chktiponom=1&ftiponom=<?=$CodTipoNomina?>&chkperiodo=1&fperiodo=<?=$periodo?>&chktproceso=1&ftproceso=<?=$proceso?>">[Atras]</a></td>
	</tr>

</table><hr width="100%" color="#333333" />
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="py_empleados_conceptos.php" method="POST" onsubmit="return verificarEmpleadoConceptosProyeccion(this);">
<?
include("fphp_nomina.php");
connect();
//	------------
 $sql="SELECT mp.CodPersona, mp.NomCompleto, me.CodEmpleado, me.CodTipoNom, me.CodOrganismo, tn.Nomina FROM mastpersonas mp INNER JOIN mastempleado me ON (mp.CodPersona=me.CodPersona) INNER JOIN tiponomina tn ON (me.CodTipoNom=tn.CodTipoNom) WHERE mp.CodPersona='".$registro."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query)!=0) $field=mysql_fetch_array($query);


 $sql_proceso="SELECT
py_proceso.CodTipoProceso,
py_proceso.CodTipoNom,
py_proceso.Periodo,
py_proceso.Anio,
py_proceso.Descripcion,
py_proceso.CodProyeccion,
tiponomina.Nomina as NomTNomina,
pr_tipoproceso.Descripcion as NomTProceso
FROM
py_proceso
INNER JOIN tiponomina ON tiponomina.CodTipoNom = py_proceso.CodTipoNom
INNER JOIN pr_tipoproceso ON pr_tipoproceso.CodTipoProceso = py_proceso.CodTipoProceso
WHERE
py_proceso.CodTipoProceso = '".$proceso."'  AND
py_proceso.Periodo = '".$periodo."'  AND
py_proceso.CodTipoNom = '".$CodTipoNomina."' AND
py_proceso.CodProyeccion = '".$ftproyeccion."'";

$sql_proceso=mysql_query($sql_proceso) or die ($sql_proceso.mysql_error());
if (mysql_num_rows($sql_proceso)!=0) $field_proceso=mysql_fetch_array($sql_proceso);
?>
<div class="divBorder" style="width:100%;">
<table width="100%" class="tblFiltro">
    <tr>
        <td align="left" width="100">Persona:</td>
        <td><input name="persona" type="text" id="persona" size="10" value="<?=$field["CodEmpleado"]?>" readonly /></td>
    </tr>
    <tr>
        <td align="left" width="100">Nombre Completo:</td>
        <td><input name="nompersona" type="text" id="nompersona" size="75" value="<?=$field["NomCompleto"]?>" readonly /></td>
    </tr>
    
    <tr>
        <td align="left" width="100">Tipo de N&oacute;mina:</td>
        <td><input name="nomnomina" type="text" id="nomnomina" size="75" value="<?=$field["Nomina"]?>" readonly /></td>
    </tr>
</table>
</div><br />

<div style="width:100%" class="divFormCaption">Datos del Concepto</div>
<table width="100%" class="tblForm">
   
        <tr>
        <td align="left" width="100">Nomina:</td>
        <td>
            <input name="codnomina" type="text" id="codnomina" size="6" value="<?= $field_proceso["CodTipoNom"]?>" readonly />
               <input name="nomina" type="hidden" id="nomina" value="<?= $field_proceso["CodTipoNom"]?>" />
            <input name="nomnomina" type="text" id="nomnomina" size="50" value="<?= $field_proceso["NomTNomina"]?>" readonly />            
        </td>

    </tr>
    
    <tr>
        <td align="left" width="100">Proceso:</td>
        <td>
            <input name="codproceso" type="text" id="codproceso" size="6" value="<?= $field_proceso["CodTipoProceso"]?>" readonly />
               <input name="proceso" type="hidden" id="proceso" value="<?= $field_proceso["CodTipoProceso"]?>" />
            <input name="nomproceso" type="text" id="nomproceso" size="50" value="<?= $field_proceso["NomTProceso"]?>" readonly />            
        </td>

    </tr>
    <tr>
        <td align="left" width="100">Periodo:</td>
        <td>
            <input name="codperiodo" type="text" id="codperiodo" size="6" value="<?= $field_proceso["Periodo"]?>" readonly />
               <input name="periodo" type="hidden" id="periodo" value="<?= $field_proceso["Periodo"]?>" />
            <input name="nomperiodo" type="text" id="nomperiodo" size="50" value="<?= $field_proceso["Periodo"]?>" readonly />
            
        </td>

    </tr>
    <tr>
        <td align="left" width="100">Proyeccion:</td>
        <td>
            <input name="codproyeccion" type="text" id="codproyeccion" size="6" value="<?= $field_proceso["CodProyeccion"]?>" readonly />
               <input name="proyeccion" type="hidden" id="proyeccion" value="<?= $field_proceso["CodProyeccion"]?>" />
            <input name="nomproyeccion" type="text" id="nomproyeccion" size="50" value="<?= $field_proceso["Descripcion"]?>" readonly />
            
        </td>

    </tr>
    
     <tr>
		<td align="left" width="100">Concepto:</td>
        <td colspan=2 align="left" width="100" >  
          <input name="btConcepto" type="button" id="btConcepto" value="..." onclick="window.open('py_lista_conceptos.php?limit=0&codpersona=<?=$registro?>&codtiponom=<?=$field['CodTipoNom']?>', 'wLista', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=500, width=800, left=200, top=200, resizable=yes');" />*
          <input name="codconcepto" type="hidden" id="codconcepto" />
          <input name="nomconcepto" type="text" id="nomconcepto" size="56" readonly />
        </td>
 
    </tr>
    
     <tr>
		<td align="left" width="100">Monto:</td>
    	<td ><input type="text" name="monto" id="monto" size="20" maxlength="14" /></td>
             
    
    </tr>
      <tr>
        <td align="left" width="100">Cantidad:</td>
        <td  ><input type="text" name="cantidad" id="cantidad" size="20" maxlength="14" /></td>
    
    </tr>

</table>

<input type="hidden" name="registro" id="registro" value="<?=$registro?>" />
<input type="hidden" name="nomina" id="nomina" value="<?=$field["CodTipoNom"]?>" />
<input type="hidden" name="secuencia" id="secuencia" />
<input type="hidden" name="accion" id="accion" value="INSERTAR" />
<input type="hidden" name="elemento" id="elemento" />
<table width="1000" class="tblBotones">
 <tr>
	<td align="left">
		<input name="btInsertar" type="submit"  id="btInsertar" value="Insertar" />
		<input name="btLimpiar" type="button"  id="btLimpiar" value="Limpiar" onclick="limpiarEmpleadoConceptos(this.form);" /> | 
		<input name="btEditar" type="button"  id="btEditar" value="Editar" onclick="editarEmpleadoConceptosProyeccion(this.form);" />
		<input name="btEliminar" type="button"  id="btEliminar" value="Eliminar" onclick="eliminarEmpleadoConceptosProyeccion(this.form);" />
	</td>
 </tr>
</table>

<table width="1000" class="tblLista">
    <tr class="trListaHead">
        <th scope="col" width="50">#</th>
        <th scope="col" >Concepto</th>
        <th scope="col" width="50">Monto</th>
        <th scope="col" width="10">Cantidad</th>
        <th scope="col" width="10">Procesos</th>
        <th scope="col" width="75">Estado</th>
        <th scope="col" width="75">Persona</th>
    </tr>
	<?
   /* $sql="SELECT
prc.Descripcion as NomConcepto,
py_epc.Monto,
py_epc.Cantidad,
py_epc.CodPersona,
py_epc.CodConcepto,
py_epc.CodProceso
FROM
py_empleadoprocesoconcepto AS py_epc
INNER JOIN pr_concepto AS prc ON prc.CodConcepto = py_epc.CodConcepto

WHERE

py_epc.CodProceso = $proceso and
py_epc.CodPersona = $registro"; */


$sql="SELECT
prc.Descripcion AS NomConcepto,
py_epc.Monto,
py_epc.Cantidad,
py_epc.CodPersona,
py_epc.CodConcepto,
py_epc.CodTipoNom,
py_epc.CodProyeccion,
py_epc.Periodo,
py_epc.CodTipoProceso
FROM
	py_empleadoprocesoconcepto AS py_epc
INNER JOIN pr_concepto AS prc ON prc.CodConcepto = py_epc.CodConcepto
where
py_epc.CodPersona = '".$registro."'  AND 
py_epc.CodTipoProceso = '".$proceso."'  AND
py_epc.Periodo = '".$periodo."'  AND
py_epc.CodTipoNom = '".$CodTipoNomina."' AND
py_epc.CodProyeccion = '".$ftproyeccion."'";


    $query_conceptos=mysql_query($sql) or die ($sql.mysql_error());
    while($field_conceptos=mysql_fetch_array($query_conceptos)) {
		//list($a, $m, $d)=SPLIT('[/.-]', $field_conceptos['PeriodoDesde']); $pdesde=$d."-".$m."-".$a; if ($pdesde="00-00-0000") $pdesde=""; 
		//list($a, $m, $d)=SPLIT('[/.-]', $field_conceptos['PeriodoHasta']); $phasta=$d."-".$m."-".$a; if ($phasta="00-00-0000") $phasta=""; 
		//$periodo="$pdesde - $phasta";
		//if ($field_conceptos['Estado']=="A") $status="Activo"; else $status="Inactivo";
		$id=$field_conceptos['CodConcepto'];  /*."-".$field_conceptos['Secuencia'];*/
		?>
        <tr class="trListaBody" onclick="mClk(this, 'elemento'); " id='<?=$id?>'>
			<td align="center"><?=$field_conceptos['CodConcepto']?></td>
			<td width="200"><?=($field_conceptos['NomConcepto'])?></td>
		
			<td align="right"><?=number_format($field_conceptos['Monto'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field_conceptos['Cantidad'], 2, ',', '.')?></td>
			<td align="center"><?=$field_conceptos['CodTipoProceso']?></td>
			<td align="center"><?=$status?></td>
			<td align="center"><?=$field_conceptos['CodPersona']?></td>
		</tr>
	<? } ?>
</table>
</form>
</body>
</html>
