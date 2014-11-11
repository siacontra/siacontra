<?php
session_start();



$ahora=date("Y-m-d H:i:s");

include('../paginas/acceso_db_siaces.php');


mysql_query ("SET NAMES 'utf8'");
$query_disp = "SELECT
mastempleado.CodEmpleado,
mastempleado.CodPersona,
mastempleado.CodTipoTrabajador,
mastempleado.CodPerfil,
mastempleado.CodCargo,
mastempleado.CodDependencia,
mastempleado.Estado,
mastpersonas.Nombres,
mastpersonas.Estado,
mastdependencias.Dependencia,
rh_puestos.DescripCargo,
mastpersonas.NomCompleto,
mastpersonas.Ndocumento
FROM
mastempleado
INNER JOIN mastpersonas ON mastempleado.CodPersona = mastpersonas.CodPersona
INNER JOIN mastdependencias ON mastempleado.CodDependencia = mastdependencias.CodDependencia
INNER JOIN rh_puestos ON mastempleado.CodCargo = rh_puestos.CodCargo";
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
<script type="text/javascript" src="../../js/fscript.js" charset="utf-8"></script>

<script type="text/javascript" src="fscript_nomina.js" charset="utf-8"></script>
</head>

<body>


<table align="center" width="100%">
   <tr>
      <td>
      	   <input type="button" value="Agregar Sel." style="width:90px;" onclick="switchSelTR(this.form, 'tblAprobados', 'trApro', 'tblDisponibles', 'trDisp', 'chkApro', 'chkAprobados', 'chkDisp', 'chkDisponibles');" />
           <input type="button" name="btVer" 			id="btVer"        class="btLista" value="Ver"       onclick="cargarOpcion(this.form,'www.google.com','BLANK', 'height=600, width=920, left=250, top=50, resizable=no');"/>
         
           <table align="center">
			    <tr>
					<td>
						<div style="background:#F9F9F9; height:350px; overflow: scroll; width:600px;">         
							 <table width="1000" class="tblLista">
								<tr class="trListaHead"    >	
								  
								   <th scope="col" width="75">C&oacute;digo</th>
								   <th scope="col" width="250">Nombre</th>
								   <th scope="col" width="300">Cargo</th>
								   <th scope="col">Dependencia</th>
								   <th scope="col" width="75">Sit. Trab.</th>
								</tr>
            
									<tbody id="tblDisponibles">
								<?
										

										while($field_disp = mysql_fetch_array($query_disp)) {
											if ($field_disp["Estado"] == "A") $status = "Activo"; else $status = "Inactivo";
											$fila = $field_disp['CodPersona']."|:|".$field_disp['NomCompleto']."|:|".$field_disp['DescripCargo']."|:|".$field_disp['Dependencia']."|:|".$status;
											?>
											<tr class="trListaBody" id="trDisp<?=$field_disp['CodPersona']?>" ondblclick=" selFuncionario(' <?= $field_disp['CodPersona']  ?>',' <?= $field_disp['NomCompleto']  ?>',' <?= $field_disp['DescripCargo']  ?>',' <?= $field_disp['Dependencia']  ?>');">
												
												<td align="center"><?=$field_disp['Ndocumento']?></td>
												<td><?=($field_disp['NomCompleto'])?></td>
												<td><?=($field_disp['DescripCargo'])?></td>
												<td><?=($field_disp['Dependencia'])?></td>
												<td align="center"><?=$status?></td>
											</tr>
											<?
										
									}
								?>
									</tbody>
								</table>
						</div>
					</td>
				</tr>
        </table>
		
		
			
		</td>
	</tr>
</table>

</form>




</body>
</html>
