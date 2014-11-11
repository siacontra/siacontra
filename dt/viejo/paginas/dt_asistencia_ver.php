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
   					/*	campo1=document.getElementById('ci1').value;
   						campo2=document.getElementById('ci2').value;
    					var cedula=campo1+"-"+campo2;
						document.getElementById('cedula').value=cedula;*/
						document.form.form1.submit();
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
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Asistencia Tecnica | Ver</td>
		<td align="right"><a class="cerrar" href="<?= $regresar?>.php" onclick="window.close();">[cerrar]</a></td>
	</tr>
</table>
<hr width="100%" color="#333333" />

<div id="Contenido">
						<div>
							<blockquote>
								<br>
								
                        	
                            <form name="form1" action="" method="post" onSubmit="return valid(this)">
                            	<table align="center" align="center" width="800px" border='0'>
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
                               
    							</table>
    							
    							<table>
    							<!------------------------------------------------------------------------------>	
                                   
                                 <!------------------------------------------------------------------------------>	
                                   
                                	<tr>
                                    	<td >
                                    	<!--------------------------------------------->
                                    	<div style="background:#F9F9F9; height:150px; overflow: scroll; width:400px;">         
												
													<TABLE id="tabla" name="tabla" width="400px" border="1" class="tblLista" width="1000px" style="background:#F9F9F9; overflow: scroll; width:700px;">
														
														<tr class="trListaHead">
														<th colspan=6 align="left" >Analistas Asignados</th>
													    </tr>
														
														<tr class="trListaHead">
														<th scope="col" width="10">&nbsp;</th>
														<th scope="col" width="75">C&oacute;digo</th>
														<th scope="col" width="700">Nombre</th>
														<th scope="col" width="300">Cargo</th>
														<th scope="col">Dependencia</th>
														<th scope="col" width="75">Sit. Trab.</th>
													</tr>
													<?php
													
													$query = "
																	SELECT
																	dt_asesor.co_asistencia,
																	dt_asesor.co_asesor
																	FROM
																	dt_asistencia
																	INNER JOIN dt_asesor ON dt_asesor.co_asistencia = dt_asistencia.co_asistencia
																	where 
																	dt_asesor.co_asistencia = '".$registro."' ";
													
													
																	
														
													 
														 $resultado = mysql_query($query) or die ($query.mysql_error());
														 
														 while ( $row = mysql_fetch_array($resultado)  )	
															{	 	
																 /// -------------------------------------------
																	echo"<tr class='trListaBody' onclick='mClk(this, \"registro\");' id='".$row['co_asistencia']."'>
																	<td align='center'>".$row['co_asistencia']."</td>
																	<td align='center'>".$row['co_asistencia']."</td>
																	<td align='center'>".$row['co_asesor']."</td>
																	
																	
																	<td align='center'>".$row['co_asesor']."</td>
																	<td align='center'>".$row['co_asesor']."</td>
																	<td align='center'>".$row['co_asesor']."</td>
																	
																	</tr>";
																}
													
													?>
													<!---------------------------------------_>
													 <?php
													//  include('acceso_db.php');
													/*
														echo "<tr class='trListaBody'  id=''>
														
																	<td align='center'></td>
																	<td align='center'> </td>
																	<td align='center'></td>
																	
																	
																	<td align='center'></td>
																	<td align='center'></td>
																	<td align='center'></td>
																	
																	</tr>";

													 //  for($i=0;$i<$ra;$i++){
														// mysql_query ("SET NAMES 'utf8'");
														 /// -------------------------------------------
														 $query = "
																	SELECT
																	dt_asesor.co_asistencia,
																	dt_asesor.co_asesor
																	FROM
																	asistencias.dt_asistencia
																	INNER JOIN dt_asesor ON dt_asesor.co_asistencia = dt_asistencia.co_asistencia
																	where 
																	dt_asesor.co_asistencia = '".$row['co_asistencia']."' ";
														 $resultado = mysql_query($query) or die ($query.mysql_error());
														 
														 while ( $row = mysql_fetch_array($resultado)  )	
															{	 	
																 /// -------------------------------------------
																	echo"<tr class='trListaBody' onclick='mClk(this, \"registro\");' id='".$row['co_asistencia']."'>
																	<td align='center'>".$row['co_asistencia']."</td>
																	<td align='center'>".$row['co_asistencia']."</td>
																	<td align='center'>".$row['co_asesor']."</td>
																	
																	
																	<td align='center'>".$row['co_asesor']."</td>
																	<td align='center'>".$row['co_asesor']."</td>
																	<td align='center'>".$row['co_asesor']."</td>
																	
																	</tr>";
																}
														
														
													 */ 
													 
													 ?>
													
													
													<!---------------------------------------->
													
													</TABLE>
																
												</div>
                                    	
                                    	<!---------------------------------------------->
                                    	</td>
        								<td >
        								<!--------------------------------------------->
                                    	<div style="background:#F9F9F9; height:150px; overflow: scroll; width:400px;">         
												
													<TABLE id="tabla1" name="tabla1" width="400px" border="1" class="tblLista" width="1000px" style="background:#F9F9F9; overflow: scroll; width:700px;">
													
														<tr class="trListaHead">
														<th colspan=6 align="left" >Funcionarios Receptores de la Asistencia</th>
													    </tr>	
														<tr class="trListaHead">
														<th scope="col" width="10">&nbsp;</th>
														<th scope="col" width="75">C&oacute;digo</th>
														<th scope="col" width="700">Nombre</th>
														<th scope="col" width="300">Cargo</th>
														<th scope="col">Dependencia</th>
														<th scope="col" width="75">Sit. Trab.</th>
													</tr>
													
													<?php
													
													
													
													$query = "
																SELECT
																dt_receptores.co_asistencia,
																dt_receptores.co_receptores
																FROM
																dt_asistencia
																INNER JOIN dt_receptores ON dt_asistencia.co_asistencia = dt_receptores.co_asistencia

																where  dt_receptores.co_asistencia  = '".$registro."' ";

													 
														 $resultado = mysql_query($query) or die ($query.mysql_error());
														 
														 while ( $row = mysql_fetch_array($resultado)  )	
															{	 	
																 /// -------------------------------------------
																	echo"<tr class='trListaBody' onclick='mClk(this, \"registro\");' id='".$row['co_asistencia']."'>
																	<td align='center'>".$row['co_asistencia']."</td>
																	<td align='center'>".$row['co_asistencia']."</td>
																	<td align='center'>".$row['co_asistencia']."</td>
																	
																	
																	<td align='center'>".$row['co_asistencia']."</td>
																	<td align='center'>".$row['co_asistencia']."</td>
																	<td align='center'>".$row['co_asistencia']."</td>
																	
																	</tr>";
																}
													
														
													
													?>
													</TABLE>
																
												</div>
                                    	
                                    	<!---------------------------------------------->
        								
        								
        								</td>
                                    </tr>
    							
    							</table>
                            </form>	 
						</blockquote>
			  	  </div>
		    	</div>

<!-- ****************************************************** COMIENZO TAB4 ************************************************ -->
