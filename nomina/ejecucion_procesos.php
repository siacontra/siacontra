<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_nomina.php");
$ahora=date("Y-m-d H:i:s");
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('05', $concepto);
//	------------------------------------
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
		<td class="titulo">Ejecuci&oacute;n de Procesos </td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?
$MAXLIMIT=30;
if ($filtrar == "DEFAULT") {
	$ftiponom = $_SESSION["NOMINA_ACTUAL"];
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$dSeleccion1 = "disabled"; 
	$dSeleccion2 = "disabled"; 
	$dfsittra = "disabled";
	//	---------------------------------
} else {
	//	ESTOS SON ALGUNOS DE LOS VALORES QUE SE NECESTIAN PARA EJECUTAR LAS FORMULAS
	$_SESSION['_PROCESO'] = $ftproceso;
	$_SESSION['_NOMINA'] = $ftiponom;
	$_SESSION['_PERIODO'] = $fperiodo;
	//	---------------------------------
	//	Consulto el periodo del proceso abierto....
	$sql = "SELECT FechaDesde, FechaHasta FROM pr_procesoperiodo WHERE CodOrganismo = '".$forganismo."' AND CodTipoNom = '".$ftiponom."' AND Periodo = '".$fperiodo."' AND CodTipoProceso = '".$ftproceso."'";
	$query_periodo = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_periodo) != 0) {
		$field_periodo = mysql_fetch_array($query_periodo);
		$filtro_periodo = "AND (me.Fingreso <= '".$field_periodo['FechaHasta']."')";
	}	
	
	if ($fsittra == "1") { $filtro_status = "AND (me.Estado = 'A' OR me.Estado='I')"; $cfsittra="checked"; } else $filtro_status = "AND (me.Estado = 'A')";
	
		//	Consulto los disponibles....
	$sql = "SELECT 
				me.CodPersona, 
				me.CodEmpleado, 
				me.Estado, 
				mp.NomCompleto, 
				mp.Ndocumento, 
				md.Dependencia, 
				rp.DescripCargo 
			FROM 
				mastempleado me 
				INNER JOIN mastpersonas mp ON (me.CodPersona = mp.CodPersona AND mp.Estado = 'A') 
				INNER JOIN mastdependencias md ON (me.CodDependencia = md.CodDependencia) 
				INNER JOIN rh_puestos rp ON (me.CodCargo=rp.CodCargo) 
			WHERE 
				(me.CodPersona NOT IN (SELECT 
											CodPersona 
									   FROM 
									   		pr_tiponominaempleado 
									   WHERE 
									   		CodTipoNom = '".$ftiponom."' AND 
											Periodo = '".$fperiodo."' AND 
											CodTipoProceso = '".$ftproceso."' AND 
											CodOrganismo = '".$forganismo."')) AND 
				(me.CodOrganismo = '".$forganismo."' AND me.CodTipoNom = '".$ftiponom."') $filtro_periodo $filtro_status 
				ORDER BY mp.Apellido1 ASC";
			//ORDER BY length(mp.Ndocumento) ASC, mp.Ndocumento ASC";
			
		
			
	/*$sql = "SELECT me.CodPersona, me.CodEmpleado, me.Estado, mp.NomCompleto, mp.Ndocumento, md.Dependencia, rp.DescripCargo FROM mastempleado me INNER JOIN mastpersonas mp ON (me.CodPersona = mp.CodPersona) INNER JOIN mastdependencias md ON (me.CodDependencia = md.CodDependencia) INNER JOIN rh_puestos rp ON (me.CodCargo=rp.CodCargo) WHERE (me.CodPersona NOT IN (SELECT CodPersona FROM pr_tiponominaempleado WHERE CodTipoNom = '".$ftiponom."' AND Periodo = '".$fperiodo."' AND CodTipoProceso = '".$ftproceso."' AND CodOrganismo = '".$forganismo."')) AND (me.CodOrganismo = '".$forganismo."' AND me.CodTipoNom = '".$ftiponom."') AND (me.CodPersona IN (SELECT CodPersona FROM pr_empleadoconcepto WHERE ((TipoAplicacion = 'T' AND '".$fperiodo."' >= PeriodoDesde AND '".$fperiodo."' <= PeriodoHasta) OR (TipoAplicacion = 'P' AND '".$fperiodo."' >= PeriodoDesde)) AND (Procesos LIKE '%".$ftproceso."%' OR Procesos = '[TODOS]'))) $filtro_periodo $filtro_status ORDER BY length(mp.Ndocumento) ASC, mp.Ndocumento ASC";*/
	$query_disp = mysql_query($sql) or die ($sql.mysql_error());
	$rows_disp = mysql_num_rows($query_disp);
	if ($rows_disp == 0) $dSeleccion1 = "disabled"; 
	
	//	Consulto los disponibles para aprobar....
	$sql = "SELECT me.CodPersona, me.CodEmpleado, me.Estado, mp.NomCompleto, mp.Ndocumento, md.Dependencia, rp.DescripCargo FROM mastempleado me INNER JOIN mastpersonas mp ON (me.CodPersona = mp.CodPersona) INNER JOIN mastdependencias md ON (me.CodDependencia = md.CodDependencia) INNER JOIN rh_puestos rp ON (me.CodCargo=rp.CodCargo) WHERE (me.CodPersona IN (SELECT CodPersona FROM pr_tiponominaempleado WHERE CodTipoNom = '".$ftiponom."' AND Periodo = '".$fperiodo."' AND CodTipoProceso = '".$ftproceso."' AND CodOrganismo = '".$forganismo."')) AND (me.CodOrganismo = '".$forganismo."' 
	 -- AND me.CodTipoNom = '".$ftiponom."'
	) $filtro_periodo ORDER BY length(mp.Ndocumento) ASC, mp.Ndocumento ASC";
	$query_apro = mysql_query($sql) or die ($sql.mysql_error());
	$rows_apro = mysql_num_rows($query_apro);
	if ($rows_apro == 0) $dSeleccion2 = "disabled"; 
}
?>
<form id="frmentrada" name="frmentrada" action="ejecucion_procesos.php" method="POST" onsubmit="return cargarDisponiblesProcesar(this.form);">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
    <tr>
        <td align="right">Organismo:</td>
        <td>
        	<input type="checkbox" name="chkorganismo" id="chkorganismo" value="1" onclick="forzarCheck('chkorganismo');" checked="checked" />
			<select name="forganismo" id="forganismo" class="selectBig">
				<?=getOrganismos($forganismo, 3)?>
			</select>
        </td>
        <td align="right">N&oacute;mina:</td>
        <td>
        	<input type="checkbox" name="chktiponom" id="chktiponom" value="1" onclick="forzarCheck('chktiponom');" checked="checked" />
			<select name="ftiponom" id="ftiponom" class="selectBig" onchange="getFOptions_Periodo(this.id, 'fperiodo', 'chkperiodo', document.getElementById('forganismo').value); getFOptions_Proceso('ftiponom', 'ftproceso', 'chktproceso', this.value, document.getElementById('forganismo').value);">
				<?=getTNomina($ftiponom, 0)?>
			</select>
        </td>
    </tr>
    <tr>
        <td align="right">Per&iacute;odo:</td>
        <td>
        	<input type="checkbox" name="chkperiodo" id="chkperiodo" value="1" onclick="forzarCheck('chkperiodo');" checked="checked" />
			<select name="fperiodo" id="fperiodo" style="width:90px;" onchange="getFOptions_Proceso(this.id, 'ftproceso', 'chktproceso', document.getElementById('ftiponom').value, document.getElementById('forganismo').value);">
				<option value=""></option>
				<?=getPeriodos($fperiodo, $ftiponom, $forganismo, 0);?>
			</select>
		</td>
        <td align="right">Proceso:</td>
        <td>
        	<input type="checkbox" name="chktproceso" id="chktproceso" value="1" onclick="forzarCheck('chktproceso');" checked="checked" />
			<select name="ftproceso" id="ftproceso" class="selectBig">
				<option value=""></option>
                <?=getTipoProcesoNomina($ftproceso, $fperiodo, $ftiponom, $forganismo, 1)?>
			</select>
		</td>
    </tr>
    <tr><td colspan="4"><hr align="center" width="900" size="2px;" color="#323232;" /></td></tr>
    <tr>
        <td>&nbsp;</td>
        <td><input type="checkbox" name="fsittra" id="fsittra" value="1" <?=$cfsittra?> /> Mostrar Cesados </td>
    </tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Mostrar Empleados"></center><br />

<table align="center" width="1000">
   <tr>
      <td>
      	<input type="button" value="Agregar Sel." style="width:90px;" onclick="switchSelTR(this.form, 'tblAprobados', 'trApro', 'tblDisponibles', 'trDisp', 'chkApro', 'chkAprobados', 'chkDisp', 'chkDisponibles');" />
         <table align="center"><tr><td><div style="background:#F9F9F9; height:350px; overflow: scroll; width:500px;">         
         <table width="1000" class="tblLista">
            <tr class="trListaHead">
               <th scope="col" width="25">&nbsp;</th>
               <th scope="col" width="75">C&oacute;digo</th>
               <th scope="col" width="250">Nombre</th>
               <th scope="col" width="300">Cargo</th>
               <th scope="col">Dependencia</th>
               <th scope="col" width="75">Sit. Trab.</th>
            </tr>
            
				<tbody id="tblDisponibles">
            <?
				if ($filtrar != "DEFAULT") {
					while($field_disp = mysql_fetch_array($query_disp)) {
						if ($field_disp["Estado"] == "A") $status = "Activo"; else $status = "Inactivo";
						$fila = $field_disp['CodPersona']."|:|".$field_disp['NomCompleto']."|:|".$field_disp['DescripCargo']."|:|".$field_disp['Dependencia']."|:|".$status;
						?>
						<tr class="trListaBody" id="trDisp<?=$field_disp['CodPersona']?>" ondblclick="switchDblTR(this, 'tblAprobados', 'trApro', 'tblDisponibles', 'trDisp', 'chkApro', 'chkAprobados', 'chkDisp', 'chkDisponibles', '<?=($fila)?>');">
							<td align="center"><input type="checkbox" name="chkDisponibles" id="chkDisp<?=$field_disp['CodPersona']?>" value="<?=($fila)?>" /></td>
							<td align="center"><?=$field_disp['Ndocumento']?></td>
							<td><?=($field_disp['NomCompleto'])?></td>
							<td><?=($field_disp['DescripCargo'])?></td>
							<td><?=($field_disp['Dependencia'])?></td>
							<td align="center"><?=$status?></td>
						</tr>
						<?
					}
				}
            ?>
				</tbody>
        	</table>
        	</div></td></tr></table>
			<table width="95%">
            	<tr>
                	<td>
                        <a onclick="selChkTodos(document.getElementById('frmentrada'), 'chkDisponibles', true);" href="javascript:;">Seleccionar Todos</a> | 
                        <a onclick="selChkTodos(document.getElementById('frmentrada'), 'chkDisponibles', false);" href="javascript:;">Deseleccionar Todos</a>
                    </td>
                    <td align="right">
                    	Disponibles: <?=$rows_disp?>
                    </td>
                </tr>
            </table>
		</td>
      
      
		<td>
			<input type="hidden" name="archivo" id="archivo" value="<?=$fperiodo."_".$ftproceso."_".date("His")?>" />
			<input type="button" value="Quitar Sel." style="width:90px;" onclick="switchSelTR(this.form, 'tblDisponibles', 'trDisp', 'tblAprobados', 'trApro', 'chkDisp', 'chkDisponibles', 'chkApro', 'chkAprobados');" /> |
			<input type="button" value="PayRoll" style="width:90px;" onclick="verPayRoll(form);" />
			<input type="button" value="N&oacute;mina" style="width:90px;" onclick="verNomina(form);" /> | 
			<input type="button" value="Generar" style="width:90px;" onclick="procesarNomina(this.form);" />
			<table align="center"><tr><td><div style="background:#F9F9F9; height:350px; overflow: scroll; width:500px;">         
			<table width="1000" class="tblLista">
				<tr class="trListaHead">
					<th scope="col" width="25">&nbsp;</th>
					<th scope="col" width="75">C&oacute;digo</th>
					<th scope="col" width="250">Nombre</th>
					<th scope="col" width="300">Cargo</th>
					<th scope="col">Dependencia</th>
					<th scope="col" width="75">Sit. Trab.</th>
				</tr>
				
				<tbody id="tblAprobados">
				<?
				if ($filtrar != "DEFAULT") {
					while($field_apro = mysql_fetch_array($query_apro)) {
						if ($field_apro["Estado"] == "A") $status = "Activo"; else $status = "Inactivo";
						$fila = $field_apro['CodPersona']."|:|".$field_apro['NomCompleto']."|:|".$field_apro['DescripCargo']."|:|".$field_apro['Dependencia']."|:|".$status."|:|".$field_apro['CodEmpleado'];
						?>
						<tr class="trListaBody" id="trApro<?=$field_apro['CodPersona']?>" ondblclick="switchDblTR(this, 'tblDisponibles', 'trDisp', 'tblAprobados', 'trApro', 'chkDisp', 'chkDisponibles', 'chkApro', 'chkAprobados', '<?=($fila)?>');">
							<td align="center"><input type="checkbox" name="chkAprobados" id="chkApro<?=$field_apro['CodPersona']?>" value="<?=($fila)?>" /></td>
							<td align="center"><?=$field_apro['Ndocumento']?></td>
							<td><?=($field_apro['NomCompleto'])?></td>
							<td><?=($field_apro['DescripCargo'])?></td>
							<td><?=($field_apro['Dependencia'])?></td>
							<td align="center"><?=$status?></td>
						</tr>
						<?
					}
				}
				?>
				</tbody>
			</table>
			</div></td></tr></table>
			<table width="95%">
            	<tr>
                	<td>
                        <a onclick="selChkTodos(document.getElementById('frmentrada'), 'chkAprobados', true);" href="javascript:;">Seleccionar Todos</a> | 
                        <a onclick="selChkTodos(document.getElementById('frmentrada'), 'chkAprobados', false);" href="javascript:;">Deseleccionar Todos</a>
                    </td>
                    <td align="right">
                    	Aprobados: <?=$rows_apro?>
                    </td>
                </tr>
            </table>
		</td>
	</tr>
</table>

</form>

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


</body>
</html>
