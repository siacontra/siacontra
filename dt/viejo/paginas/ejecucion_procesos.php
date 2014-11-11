<?php
session_start();



$ahora=date("Y-m-d H:i:s");

include('../paginas/acceso_db_siaces.php');


mysql_query ("SET NAMES 'utf8'");
$query_disp = "SELECT * FROM siacem01.mastempleado
INNER JOIN 
siacem01.mastpersonas ON 
siacem01.mastempleado.CodPersona = siacem01.mastpersonas.CodPersona";
$query_disp =mysql_query($query_disp) or die ($query_disp.mysql_error());

//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_nomina.js"></script>
<link href="../../css/estilo.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="../../af/af_fscript.js"></script>
<script type="text/javascript" language="javascript" src="../../af/af_fscript_02.js"></script>
<script type="text/javascript" src="../../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="fscript_nomina.js" charset="utf-8"></script>


</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Asignacion de Funcionarios </td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />


<form id="frmentrada" name="frmentrada" action="ejecucion_procesos.php" method="POST" onsubmit="return cargarDisponiblesProcesar(this.form);">



<table align="center" width="1000">
   <tr>
      
      	<td>
			<input type="hidden" name="archivo" id="archivo" value="<?=$fperiodo."_".$ftproceso."_".date("His")?>" />
			
		   <input type="button" value="Guardar." style="width:90px;" onclick="AsignarAnalistas('dataTable2',1)" /> | 
		   <input type="button" name="btVer" 			id="btVer"        class="btLista" value="Agregar"       onclick="cargarVentanaLista(this.form,'dt_listado_funcionarios.php?','BLANK', 'height=350, width=500px, left=250, top=50, resizable=no');"/>
	       <INPUT type="button" value="Quitar Sel." onclick="EliminarFila('dataTable2')" />
	      <INPUT type="button" value="Asignar." onclick="AsignarAnalistas('dataTable2',1)" />
	      </td>	
		</tr>
		<tr>

<!------------------------------------------------------------------------------->

<table align="left">
				<tr>
					<td>
						<div style="background:#F9F9F9; height:200px; overflow: scroll; width:700px;">         
								
									<TABLE id="dataTable2" name="dataTable2" width="350px" border="1" class="tblLista" width="1000px" style="background:#F9F9F9; overflow: scroll; width:700px;">
										
										<tr class="trListaHead">
										<th scope="col" width="10">&nbsp;</th>
										<th scope="col" width="75">C&oacute;digo</th>
										<th scope="col" width="700">Nombre</th>
										<th scope="col" width="300">Cargo</th>
										<th scope="col">Dependencia</th>
										<th scope="col" width="75">Sit. Trab.</th>
									</tr>
									</TABLE>
												
								</div>
						</td>
					</tr>
</table>
<!----------------------------------------------------------------------------------------------->	
			
				
				
<!----------------------------------------------------------------------------------------------->	
			<!-- <table align="left">
				<tr>
					<td>
						<div style="background:#F9F9F9; height:200px; overflow: scroll; width:700px;">         
								<table width="1000" id="tablaFuncionarios" class="tblLista">
									<tr class="trListaHead">
										<th scope="col" width="25">&nbsp;</th>
										<th scope="col" width="75">C&oacute;digo</th>
										<th scope="col" width="250">Nombre</th>
										<th scope="col" width="300">Cargo</th>
										<th scope="col">Dependencia</th>
										<th scope="col" width="75">Sit. Trab.</th>
									</tr>
									
									<tbody id="tblAprobados">
								-->	<?/*
									if ($filtrar != "DEFAULT") {
										while($field_apro = mysql_fetch_array($query_apro)) {
											if ($field_apro["Estado"] == "A") $status = "Activo"; else $status = "Inactivo";
											$fila = $field_apro['CodPersona']."|:|".$field_apro['NomCompleto']."|:|".$field_apro['DescripCargo']."|:|".$field_apro['Dependencia']."|:|".$status."|:|".$field_apro['CodEmpleado'];
											*/?> 
											<? /*
											<tr class="trListaBody" id="trApro<?=$field_apro['CodPersona']?>" ondblclick="switchDblTR(this, 'tblDisponibles', 'trDisp', 'tblAprobados', 'trApro', 'chkDisp', 'chkDisponibles', 'chkApro', 'chkAprobados', '<?=($fila)?>');">
												<td align="center"><input type="checkbox" name="chkAprobados" id="chkApro<?=$field_apro['CodPersona']?>" value="<?=($fila)?>" /></td>
												<td align="center"><?=$field_apro['Ndocumento']?></td>
												<td><?=($field_apro['NomCompleto'])?></td>
												<td><?=($field_apro['DescripCargo'])?></td>
												<td><?=($field_apro['Dependencia'])?></td>
												<td align="center"><?=$status?></td>
											</tr> */?>
											<?/*
										}
									}
									*/?>
									<!--
									</tbody>
								</table>
							</div>
						</td>
					</tr> 
<!----------------------------------------------------------------------------------------------->	
			</table>
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
