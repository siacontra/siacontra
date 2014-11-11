<?php
session_start();
/******************************************/
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include ("fphp.php");
include ("controlActivoFijo.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
/******************************************/
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


<script type="text/javascript">
function asignar(tabla,registro)  {
	                    
	                  var error =  AsignarAnalistas(tabla,registro)
	                  
	                  
	                  if (error==true) 
	                      {
							  alert ('Ocurrio un error en la asignacion de analistas');
						  }
					else  {
						
						  alert ('Se asignarion los analistas correctamente');
					
					    document.form1.submit();
						  } 	  						
}
						
</script>						
</head>

<body>
	
	
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Asignacion de Analistas </td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />


 <!-- <form id="frmentrada" name="frmentrada" action="ejecucion_procesos.php" method="POST" > --

<!-------------------------------------------------------------------------------->
<?
list($organismo, $nroOrden, $secuencia, $nrosecuencia)=SPLIT('[-]',$_GET['registro']);
//// CONSULTA PRINCIPAL
$sa = "select * from lg_activofijo where CodOrganismo = '$organismo' and NroOrden = '$nroOrden' and Secuencia='$secuencia' and NroSecuencia='$nrosecuencia'";
$qa = mysql_query($sa) or die ($sa.mysql_error()); //echo $sa;
$ra = mysql_num_rows($qa); 

if($ra!='0')$fa=mysql_fetch_array($qa);



//*********************************************//
 include('../paginas/acceso_db.php');
 /// -------------------------------------------
     $query = "
						SELECT
						dt_asistencia.co_asistencia,
						dt_asistencia.co_persona,
						dt_asistencia.co_unidad,
						dt_asistencia.co_modalidad,
						dt_asistencia.co_evaluacion,
						dt_asistencia.fe_solicitud,
						dt_asistencia.fe_aprobacion,
						dt_asistencia.fe_ejecucion,
						dt_asistencia.fe_finalizacion,
						dt_asistencia.tx_status,
						dt_asistencia.tx_observacion,
						dt_asistencia.tx_asunto
						FROM
						dt_asistencia  where co_asistencia= '".$registro."'";
     
	$resultado = mysql_query($query) or die ($query.mysql_error());
	$row = mysql_fetch_array($resultado)  	;


///*********************************************//

?>

<div id="Contenido">
						<div>
							<blockquote>

 <form name='form1' id='form1' action="procesar_asistencia_aprobar.php?a=1" method="post" onSubmit="return valid(this)">
                            	
                            	
                            	
<table align="center" align="center" width="800px" border='1'>
							 <!-- 		 <tr>
											<td width="58" font size="4"><label>Codigo Asistencia:</label><br /></td>
											<td width="100"><input type="text" name="co_asistencia" maxlength="10" id="co_asistencia" ></td>
									</tr> -->
									<tr>
                                		<td align="left" width="200px" style="font-size:13px"><label><strong>Codigo Asistencia</strong></label>                                		  <br /></td>
                                        </td>
                                         <td  width="600px" > <?php echo $row['co_asistencia'] ?>  </td>
                                         <input type="hidden" name="co_asistencia" maxlength="10" value="<?php echo $row['co_asistencia'] ?>" id="co_asistencia" >
        							</tr>
        							<tr>
                                		<td align="left" style="font-size:13px"><label><strong>Unidad Organizativa:</strong></label>                                		  <br /></td>
                                        </td>
                                         <td > <?php echo $row['co_unidad'] ?>  </td>
        							</tr>
                           
                                     <tr>
                                		<td align="left" style="font-size:13px" > <label><strong>Funcionario:</strong></label> </td>
                                		<td ><?php echo $row['co_persona'] ?> </td>
        							</tr>
                                    
                             <!--       <tr>
											<td width="58" font size="4"><label>Fecha de Solicitud:</label><br /></td>
											<td width="144"><input type="text" name="fe_solicitud" maxlength="10" id="datepicker" ></td>
									</tr> -->
                                    <tr>
                                		<td align="left" style="font-size:13px"><label><strong>Asunto:</strong></label>                                		  <br /></td>
                                         <td ><?php echo $row['tx_asunto'] ?>  </td>
                                    </tr>
                                  
                                    <tr>
                                		<td align="left" style="font-size:13px"><label><strong>Modalidad:</strong></label>                                		  <br /></td>
                                        <td ><?php echo $row['co_modalidad'] ?> </td>
      						 		</tr>
                                    <tr>
                                		<td align="left" style="font-size:13px"><label><strong>Funcionarios Receptores:</strong></label>                                		  <br /></td>
                                       <td > <?php echo $row['co_persona'] ?> </td>
      						 		</tr>
                                    <tr>
                                		<td align="left" style="font-size:13px"><label><strong>Observacion:</strong></label>                                		  <br /></td>
                                         <td ><?php echo $row['tx_observacion'] ?> </td>
      						 		</tr>
      						 	<!------------------------------------------------------------------------------>	
      						 		<tr>
                                		<td align="left" style="font-size:13px"><label><strong>Fecha Solicitud:</strong></label>                                		  <br /></td>
                                         <td ><?php echo $row['fe_solicitud'] ?> </td>
      						 		</tr>
      						 		
      						 		<tr>
                                		<td align="left" style="font-size:13px"><label><strong>Fecha Aprobacion:</strong></label>                                		  <br /></td>
                                         <td ><?php echo $row['fe_aprobacion'] ?> </td>
      						 		</tr>
      						 		<tr>
                                		<td align="left" style="font-size:13px"><label><strong>Fecha Ejecucion:</strong></label>                                		  <br /></td>
                                         <td > <?php echo $row['fe_ejecucion'] ?>  </td>
      						 		</tr>
      						 		<tr>
                                		<td align="left" style="font-size:13px"><label><strong>Fecha Finalizacion:</strong></label>                                		  <br /></td>
                                         <td ><?php echo $row['fe_finalizacion'] ?> </td>
      						 		</tr>
                               <!------------------------------------------------------------------------------>	
                                   
                                	
    							</table>
                         
					

<!-- ************************************************************************************************** -->



<!------------------------------------------------------------------------------->

				<table align="center">
								<tr>

										<td>
												
												<input type="button" name="btVer" 			id="btVer"        class="btLista" value="Agregar"       onclick="cargarVentanaLista(this.form,'dt_listado_funcionarios.php?','BLANK', 'height=350, width=500px, left=250, top=50, resizable=no');"/>
												<INPUT type="button" value="Quitar Sel." onclick="EliminarFila('dataTable2')" />
												
										</td>	
								</tr>
					
								<tr>
									<td>
										<div style="background:#F9F9F9; height:150px; overflow: scroll; width:700px;">         
												
													<TABLE id="dataTable2" name="dataTable2" width="400px" border="1" class="tblLista" width="1000px" style="background:#F9F9F9; overflow: scroll; width:700px;">
														
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


<!------------------------------------------------------------------------->
<table align="center" width="1000" height="100">
   <tr>
      
      	<td align="center">
			<INPUT type="button" value="Asignar."  type="submit" name="enviar"  onclick="javascript:asignar('dataTable2','<?= $registro ?>')" /> |
			
	     </td>	
	</tr>
	<tr>
		<td align="left"></td>

	</tr>
	<tr>
		<td align="left"></td>

	</tr>
</table>		
<!------------------------------------------------------------------------->
                         </form>

	        </blockquote>
	  </div>
</div>

 <!-- </form> -->
</body>
</html>
