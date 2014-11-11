<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_nomina.php");
include("fphp_proyecciones.php");
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
<script type="text/javascript" language="javascript" src="fscript_proyeccion.js"></script>
<script type="text/javascript" language="javascript"> 


var registro="";// CODIGO DE PERSONA 
//var proceso= <?=$registro; ?>;


</script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Proyeccion Ejecuci&oacute;n de Procesos </td>
		<td align="right"><a class="cerrar" href="py_proyeccion.php">[Atras]</a></td>
		<td align="right"><a class="cerrar" href="proyeccion_procesos.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?
$MAXLIMIT=30;


if ($registro !="") {
	                    $filtro_proceso =  "WHERE py_proceso.CodProceso = '".$registro."'";
	                    $filtro_disponibles =   "WHERE 	py_empleadoproceso.Periodo = '".$fperiodo."' AND py_empleadoproceso.CodProyeccion= '".$ftproyeccion."' AND 	py_empleadoproceso.CodTipoNom= '".$ftiponom."' AND py_empleadoproceso.CodTipoProceso = '".$ftproceso."' ";
	                    $filtro_probados =   "WHERE 	py_empleadoproceso.Periodo = '".$fperiodo."' AND py_empleadoproceso.CodProyeccion= '".$ftproyeccion."' AND 	py_empleadoproceso.CodTipoNom= '".$ftiponom."' AND py_empleadoproceso.CodTipoProceso = '".$ftproceso."' ";
	                    
	                   
	                }
	                
	                
if ($ftproceso !="") {
	                    $filtro_proceso =  "WHERE py_proceso.CodProceso = '".$ftproceso."'";
	                    $filtro_disponibles =   "WHERE 	py_empleadoproceso.Periodo = '".$fperiodo."' AND py_empleadoproceso.CodProyeccion= '".$ftproyeccion."' AND 	py_empleadoproceso.CodTipoNom= '".$ftiponom."' AND py_empleadoproceso.CodTipoProceso = '".$ftproceso."' ";
	                    $filtro_probados =   "WHERE 	py_empleadoproceso.Periodo = '".$fperiodo."' AND py_empleadoproceso.CodProyeccion= '".$ftproyeccion."' AND 	py_empleadoproceso.CodTipoNom= '".$ftiponom."' AND py_empleadoproceso.CodTipoProceso = '".$ftproceso."' ";
					  }



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
	
	
	
	if ($fsittra == "1") { $filtro_status = "AND (me.Estado = 'A' OR me.Estado='I')"; $cfsittra="checked"; } else $filtro_status = "AND (me.Estado = 'A')";
	
		//	Consulto los disponibles....
	
			
  $sql = "SELECT
			me.CodEmpleado,
			mp.Ndocumento,
			mp.NomCompleto,
			me.CodPersona,
			me.CodDependencia,
			md.Dependencia,
			rp.CodCargo,
			rp.DescripCargo,
			me.CodTipoNom
			FROM
			mastempleado AS me
			INNER JOIN mastpersonas AS mp ON mp.CodPersona = me.CodPersona
			INNER JOIN mastdependencias AS md ON md.CodDependencia = me.CodDependencia
			INNER JOIN rh_puestos AS rp ON rp.CodCargo = me.CodCargo

			WHERE  

			me.CodPersona not in (
			SELECT 
			me.CodPersona
			FROM
			mastempleado AS me
			INNER JOIN mastpersonas AS mp ON mp.CodPersona = me.CodPersona
			INNER JOIN py_empleadoproceso ON py_empleadoproceso.CodPersona = me.CodPersona
			INNER JOIN mastdependencias AS md ON md.CodDependencia = me.CodDependencia
			INNER JOIN rh_puestos AS rp ON rp.CodCargo = me.CodCargo
			$filtro_disponibles
			 AND  me.CodTipoNom='$ftiponom' 
			


			) 
			AND  me.CodTipoNom='$ftiponom'
			$filtro_status 
			ORDER BY  	me.CodTipoNom ASC
			";
	
	
	$query_disp = mysql_query($sql) or die ($sql.mysql_error());
	$rows_disp = mysql_num_rows($query_disp);
	if ($rows_disp == 0) $dSeleccion1 = "disabled"; 
	
	//	Consulto los disponibles para aprobar....
	   $sql = "SELECT
				me.CodEmpleado,
				mp.NomCompleto,
				mp.Ndocumento,
				py_empleadoproceso.CodProceso,
				me.CodPersona,
				me.CodDependencia,
				md.Dependencia,
				rp.CodCargo,
				rp.DescripCargo
				FROM
				mastempleado AS me
				INNER JOIN mastpersonas AS mp ON mp.CodPersona = me.CodPersona
				INNER JOIN py_empleadoproceso ON py_empleadoproceso.CodPersona = me.CodPersona
				INNER JOIN mastdependencias AS md ON md.CodDependencia = me.CodDependencia
				INNER JOIN rh_puestos AS rp ON rp.CodCargo = me.CodCargo
				 $filtro_probados
				 $filtro_status 
				 AND  me.CodTipoNom='$ftiponom' 
				GROUP BY
				me.CodEmpleado";
				
	$query_apro = mysql_query($sql) or die ($sql.mysql_error());
	$rows_apro = mysql_num_rows($query_apro);
	if ($rows_apro == 0) $dSeleccion2 = "disabled"; 
}


?>
<form id="frmentrada" name="frmentrada" action="py_ejecucion_procesos.php" method="POST" onsubmit="return cargarDisponiblesProcesarProyeccion(this.form);">


<div class="divBorder" style="width:1000px;">
	
<table width="1000" class="tblFiltro">

	
        <tr>
            <td align="right">Proyeccion:</td>
        <td>
        	<input  type="checkbox" name="chkproyeccion" id="chkproyeccion" value="<?=$ftproyeccion?>" onclick="forzarCheck('chkproyeccion');" checked="checked" />
			<select name="ftproyeccion" id="ftproyeccion" class="selectBig">
			
                <? //=
                /*
					$sql="SELECT * FROM py_proyeccion  WHERE CodProyeccion='$CodProyeccion'";

					$query=mysql_query($sql) or die ($sql.mysql_error()); $rows=mysql_num_rows($query);
					for ($i=0; $i<$rows; $i++) {
					$field=mysql_fetch_array($query);
					if ($field['Codigo']==$codigo) echo "<option value='".$field['CodProyeccion']."' selected>".($field['Descripcion'])."</option>"; 
					else echo "<option value='".$field['CodProyeccion']."'>".($field['Descripcion'])."</option>";
					}
					*/
					 getProyeccion_py($ftproyeccion, 3) ;
                ?>
			</select>
		</td>
        <td align="right">N&oacute;mina:</td>
        <td>
		
        	<input type="checkbox" name="chktiponom" id="chktiponom" value="1" onclick="forzarCheck('chktiponom');" checked="checked" />
			<select name="ftiponom" id="ftiponom" class="selectBig" onchange="getFOptions_Periodo_py(this.id,    'fperiodo',   'chkperiodo', '',            document.getElementById('ftiponom').value, document.getElementById('ftproyeccion').value,document.getElementById('ftiponom').value); 
			                                                                 getFOptions_Proceso_py(
																			'ftiponom', 
																			'ftproceso', 
																			'chktproceso', 
																			this.value, 
																			'',
																			document.getElementById('ftiponom').value,
																			document.getElementById('ftproyeccion').value,
																			document.getElementById('ftiponom').value,
																			document.getElementById('fperiodo').value);"
																			
																			
																			
																			> 
				 	<?=getTNomina_py($ftproyeccion,$ftiponom, 0)?>
			</select>
        </td>
    </tr>
    <tr>
        <td align="right">Per&iacute;odo:</td>
        <td>
        	<input type="checkbox" name="chkperiodo" id="chkperiodo" value="1" onclick="forzarCheck('chkperiodo');" checked="checked" />
			<select name="fperiodo" id="fperiodo" style="width:90px;" onchange="getFOptions_Proceso_py( 'ftiponom', 
																			'ftproceso', 
																			'chktproceso', 
																			this.value, 
																			'',
																			document.getElementById('ftiponom').value,
																			document.getElementById('ftproyeccion').value,
																			document.getElementById('ftiponom').value,
																			document.getElementById('fperiodo').value);"
														
																		>
																	 
				<option value=""></option>
				<? getPeriodos_py($fperiodo, $ftiponom, $ftproyeccion, 0);?>
			</select>
		</td>
        <td align="right">Proceso:</td>
        <td>
        	<input type="checkbox" name="chktproceso" id="chktproceso" value="1" onclick="forzarCheck('chktproceso');" checked="checked" />
			<select name="ftproceso" id="ftproceso" class="selectBig">
				<option value=""></option>
                <?=getTipoProcesoNomina_py($ftproceso, $fperiodo, $ftiponom, $ftproyeccion, 1)?>
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
      	<input type="button" value="Agregar Sel." style="width:90px;" onclick="switchSelTR_Proyeccion(this.form,'AGREGAR-SEL-NOMINA', 'tblAprobados', 'trApro', 'tblDisponibles', 'trDisp', 'chkApro', 'chkAprobados', 'chkDisp', 'chkDisponibles');" />
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
						<tr class="trListaBody" id="trDisp<?=$field_disp['CodPersona']?>" >
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
			<input type="button" value="Quitar Sel." style="width:90px;" onclick="switchSelTR_Proyeccion(this.form, 'QUITAR-SEL-NOMINA', 'tblDisponibles', 'trDisp', 'tblAprobados', 'trApro', 'chkDisp', 'chkDisponibles', 'chkApro', 'chkAprobados');" /> |
		<!--	<input type="button" value="PayRoll" style="width:90px;" onclick="verPayRoll(form);" /> -->
		<!--	<input type="button" value="N&oacute;mina" style="width:90px;" onclick="verNomina(form);" /> | -->
		 	<input type="button" value="Calcular Nomina" style="width:90px;" onclick="procesarNominaProyeccion(this.form);" />
		 	<input type="button" id="btconcepto" value="Ver Conceptos" style="width:90px;" onclick="cargarPagina(this.form, 'py_empleados_conceptos.php?registro='+registro+'&proceso=<?= $ftproceso; ?>'+'&periodo=<?= $fperiodo; ?>'+'&ftproyeccion=<?= $ftproyeccion; ?>'+'&CodTipoNomina=<?= $ftiponom; ?>'  );" /> 
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
						<tr class="trListaBody" id="trApro<?=$field_apro['CodPersona']?>" onclick="activar('<?=($fila)?>',this, 'trApro<?=$field_apro['CodPersona']?>');" >
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


<script type="text/javascript" language="javascript"> 

 document.getElementById("btconcepto").disabled=true;
 
function activar(valores,src, idfila) {
// habilitp el boton	
 document.getElementById("btconcepto").disabled=false;
 // cambio el valor de la variable para abrie la ventana de los conceptos
 var valor = valores.split("|:|");	
 registro = valor[0];

var seleccionado=document.getElementsByTagName("tr");
	for (var i=0; i<seleccionado.length; i++) {
		if (seleccionado[i].getAttribute((document.all ? 'className' : 'class')) ==	'trListaBodySel') {
			seleccionado[i].setAttribute((document.all ? 'className' : 'class'), "trListaBody");
			break;
		}
	}
	var row=document.getElementById(idfila);	//	OBTENGO LA FILA DEL CLICK
	row.className="trListaBodySel";	//	CAMBIO EL COLOR DE LA FILA
 	
}
</script>

</body>
</html>
