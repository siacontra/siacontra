<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include ("fphp.php");
include ("controlActivoFijo.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<link href="css1.css" rel="stylesheet" type="text/css" />
<link href="../../af/css/estilo.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../../af/css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" src="../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript_02.js"></script>
<script type="text/javascript" src="../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/fscript.js" charset="utf-8"></script>-->

<link href="../../css/estilo.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="../css/estilos.css" rel="stylesheet" media="screen" />
<link type="text/css" rel="stylesheet" href="../../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="../../af/af_fscript.js"></script>
<script type="text/javascript" language="javascript" src="../../af/af_fscript_02.js"></script>
<script type="text/javascript" src="../../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/fscript.js" charset="utf-8"></script>

<link type="text/css" href="../css/estilos.css" rel="stylesheet" media="screen" />
				<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.7.2.custom.css" />
                <link type="text/css" href="../css/stilomenu.css" rel="stylesheet" media="screen" />
                <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
					<script type="text/javascript" src="js/jquery-1.3.2.js"></script>
					<script type="text/javascript" src="js/vanadium_es.js"></script>
					<script type="text/javascript" src="js/jqueryForm.js"></script>
					
                    <script type="text/javascript" src="js/jquery.min.js"></script>
                    <script type="text/javascript" src="js/jquery-ui.min.js"></script> 
					<link rel="stylesheet" type="text/css" href="css/vanadium.css" />
					
					<link rel="stylesheet" type="text/css" href="css/vanadium.css" />
				<meta http-equiv="X-UA-Compatible" content="IE=8" />
				<!--<script type="text/javascript" src="fscript_nomina.js" charset="utf-8"></script> -->
<style type="text/css">
<!--
UNKNOWN {FONT-SIZE: small}
#header {FONT-SIZE: 93%; BACKGROUND: url(imagenes/bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal}
#header UL {PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none}
#header LI {
        PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(imagenes/left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px}
#header A {
        PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(imagenes/right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none}
#header A { FLOAT: none}
#header A:hover {  COLOR: #333 }
#header #current { BACKGROUND-IMAGE: url(imagenes/left_on.gif)}
#header #current A { BACKGROUND-IMAGE: url(imagenes/right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333 }
-->
</style>

 <script type="text/javascript">
					
						jQuery(function($){
						$.datepicker.regional['es'] = {
						closeText: 'Cerrar',
						prevText: '&#x3c;Ant',
						nextText: 'Sig&#x3e;',
						currentText: 'Hoy',
						monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
						'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
						monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
						dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
						dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
						dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
						weekHeader: 'Sm',
						dateFormat: 'dd/mm/yy',
						firstDay: 1,
						isRTL: false,
						showMonthAfterYear: false,
						yearSuffix: ''};
						$.datepicker.setDefaults($.datepicker.regional['es']);
					});    
					
					$(document).ready(function() {
                 $('#datepicker').datepicker({
				dateFormat: 'yy-dd-mm',
				
				onSelect: function(datetext){
				var d = new Date(); // for now
				datetext=datetext+" "+d.getHours()+":"+d.getMinutes()+":"+d.getSeconds();
				$('#datepicker').val(datetext);
				},
});
        });
						function procesar() {
   					   document.form1.submit();
						}
						
						function sololetras(e) { // 1
    				/*	tecla = (document.all) ? e.keyCode : e.which; // 2
   					    if (tecla==8) return true; // 3
   					  	patron =/[A-Za-z\s]/; // 4
    					te = String.fromCharCode(tecla); // 5
    					return patron.test(te); // 6*/
						} 
						function solonumeros(e) { // 1
    				/*	tecla = (document.all) ? e.keyCode : e.which; // 2
    					if (tecla==8) return true; // 3
    					patron = /\d/; // 4
    					te = String.fromCharCode(tecla); // 5
   						 return patron.test(te); // 6*/
						} 
			</script>
<script type="text/javascript">
function asignar(tabla,registro)  {
	                    
	                  var error =  AsignarFuncionario(tabla,registro)
	                  
	                  
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
<div id="cajaModal"></div>
<!-- pretty -->
<span class="gallery clearfix"></span>
<?

list($organismo, $nroOrden, $secuencia, $nrosecuencia)=SPLIT('[-]',$_GET['registro']);
//// CONSULTA PRINCIPAL
$sa = "select * from lg_activofijo where CodOrganismo = '$organismo' and NroOrden = '$nroOrden' and Secuencia='$secuencia' and NroSecuencia='$nrosecuencia'";
$qa = mysql_query($sa) or die ($sa.mysql_error()); //echo $sa;
$ra = mysql_num_rows($qa); 

if($ra!='0')$fa=mysql_fetch_array($qa);
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Asistencia Tecnica | Nueva</td>
		<td align="right"><a class="cerrar" href="<?= $regresar?>.php" onclick="window.close();">[cerrar]</a></td>
	</tr>
</table>
<hr width="100%" color="#333333" />

<div id="Contenido">
						<div>
							<blockquote>
								<br>
								
                        	
                            <form name="form1" action="procesar_asistencia.php?a=1" method="post" onSubmit="return valid(this)">
                            	<table align="center" align="center" width="800px" border='0'>
							 <!-- 		 <tr>
											<td width="58" font size="4"><label>Codigo Asistencia:</label><br /></td>
											<td width="100"><input type="text" name="co_asistencia" maxlength="10" id="co_asistencia" ></td>
									</tr> -->
									
        							  <tr>
                                		<td align="left" style="font-size:13px"><label><strong>Unidad Organizativa:</strong></label>                                		  <br /></td>
                                        <td>
                                        
                                        <?
											 	//include('../paginas/acceso_db.php');
												include('../paginas/acceso_db_siaces.php');
												$query="select * from mastdependencias"; 
												$resp=mysql_query($query); 
												echo '<select name="dependencia" class=":required">';
												echo '<option selected value="0">Seleccione la Dependencia';   
												for($i=0;$i<mysql_num_rows($resp); $i++) 
													{ 
    												$fila=mysql_fetch_array($resp); 
  													echo '<option value="'.$fila['Dependencia'].'">'.$fila['Dependencia'].'</option>'; 
													} 
												echo '</select>';  
	  											?>
                                        </td>
        							</tr>
                           
                                     <tr>
                                		<td align="left" style="font-size:13px" > <label><strong>Funcionario:</strong></label> </td>
                                		<td>
                                		  <?
											 	//include('../paginas/acceso_db.php');
												include('../paginas/acceso_db_siaces.php');
												 mysql_query ("SET NAMES 'utf8'");
												$query="select * from mastpersonas as mp,mastempleado as me WHERE mp.CodPersona = me.CodPersona "; 
												$resp=mysql_query($query); 
												echo '<select name="funcionario" class=":required">';
												echo '<option selected value="0">Seleccione el Personal que Solicita la Asistencia';   
												for($i=0;$i<mysql_num_rows($resp); $i++) 
													{
													$fila=mysql_fetch_array($resp); 
													$nombresapellidos= $fila['NomCompleto'];
  													echo '<option value="'.$nombresapellidos.'">'.htmlentities($nombresapellidos).'</option>'; 
													} 
												echo '</select>';  
	  											?>
                                        </td>
        							</tr>
                                    
                             <!--       <tr>
											<td width="58" font size="4"><label>Fecha de Solicitud:</label><br /></td>
											<td width="144"><input type="text" name="fe_solicitud" maxlength="10" id="datepicker" ></td>
									</tr> -->
                                    <tr>
                                		<td align="left" style="font-size:13px"><label><strong>Descripcion del Problema:</strong></label>                                		  <br /></td>
                                                <td><textarea name="tx_asunto" size="80" cols="60" rows="2" class=":required"/></textarea></td>
                                    </tr>
                                  
                                  <!--  <tr>
                                		<td align="left" style="font-size:13px"><label><strong>Modalidad:</strong></label>                                		  <br /></td>
                                        <td ><input type="text" name="tx_modalidad" class=":required"/><br /></td>
      						 		</tr> -->
                                   
                                    <tr>
                                		<td align="left" style="font-size:13px"><label><strong>Observacion:</strong></label>                                		  <br /></td>
                                         <td><textarea name="tx_observacion" size="80" cols="60" rows="2" class=":required"/></textarea></td>
      						 		</tr>
                               
                               <!--    
                                	<tr>
                                    	<td align="left"><input type="submit" name="enviar" value="Solicitar" onClick="javascript:procesar();"/></td>
        								<td align="left"><input type="reset" value="Borrar" /></td>
                                    </tr> -->
    							</table>
    							
   <!------------------------------------------------------------------------------->
<!--
								<table align="center">
												<tr>

														<td>
																
																<input type="button" name="btVer" 			id="btVer"        class="btLista" value="Agregar"       onclick="cargarVentanaLista(this.form,'dt_listado_funcionarios.php?','BLANK', 'height=600, width=700px, left=250, top=50, resizable=no');"/>
																<INPUT type="button" value="Quitar Sel." onclick="EliminarFila('dataTable2')" />
																
														</td>	
												</tr>
									
												<tr>
													<td>
														<div style="background:#F9F9F9; height:150px; overflow: scroll; width:700px;">         
																
																	<TABLE id="dataTable2" name="dataTable2"  border="1" class="tblLista" width="100%" style="background:#F9F9F9; overflow: scroll; width:100%;">
																		
																		<tr class="trListaHead">
																		<th scope="col" width="10">&nbsp;</th>
																		<th scope="col" width="75">C&oacute;digo</th>
																		<th scope="col" width="700">Nombre</th>
																		<th scope="col" width="700">Cargo</th>
																		<th scope="col" width="700">Dependencia</th>
																		<th scope="col" width="75">Sit. Trab.</th>
																	</tr>
																	</TABLE>
																				
																</div>
														</td>
													</tr>
								   </table> -->
<!----------------------------------------------------------------------------------------------->	


<!------------------------------------------------------------------------->
									<table align="center" width="1000" height="100">
									   <tr>
										  
											<td align="center">
												<INPUT type="button" value="Asignar."  type="submit" name="enviar"  onclick="javascript:procesar()" /> |
												
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

<!-- ****************************************************** COMIENZO TAB4 ************************************************ -->
