<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../../index.php");
/// ------------------------
include("fphp.php");
include('../paginas/acceso_db.php');
?> 
<html>
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<link href='http:../images/icon/logo.png' rel='shortcut icon' type='image/png'> 
				<link type="text/css" href="../css/estilos.css" rel="stylesheet" media="screen" />
                <link type="text/css" href="../css/stilomenu.css" rel="stylesheet" media="screen" />
                <link rel="stylesheet" type="text/css" href="css/jquery-ui-1.7.2.custom.css" />
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                
					<script type="text/javascript" src="js/jquery-1.3.2.js"></script>
					<script type="text/javascript" src="js/vanadium_es.js"></script>
					<script type="text/javascript" src="js/jqueryForm.js"></script>
                    <script type="text/javascript" src="js/jquery.min.js"></script>
                    <script type="text/javascript" src="js/jquery-ui.min.js"></script> 
					<link rel="stylesheet" type="text/css" href="css/vanadium.css" />
				<meta http-equiv="X-UA-Compatible" content="IE=8" />
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
						dateFormat: 'dd-mm-yy',
						firstDay: 1,
						isRTL: false,
						showMonthAfterYear: false,
						yearSuffix: ''};
						$.datepicker.setDefaults($.datepicker.regional['es']);
					});    

        $(document).ready(function() {
           $("#datepicker").datepicker();
        });
						function sololetras(e) { // 1
    					tecla = (document.all) ? e.keyCode : e.which; // 2
   					    if (tecla==8) return true; // 3
   					  	patron =/[A-Za-z\s]/; // 4
    					te = String.fromCharCode(tecla); // 5
    					return patron.test(te); // 6
						} 
						function solonumeros(e) { // 1
    					tecla = (document.all) ? e.keyCode : e.which; // 2
    					if (tecla==8) return true; // 3
    					patron = /\d/; // 4
    					te = String.fromCharCode(tecla); // 5
   						 return patron.test(te); // 6
						} 
						function MOSTRAR(){
     				if (document.form1.otros.value=="si") {
 						document.form1.especificar.type = "text";
						document.getElementById('especifique').style.visibility="visible";
    				}else  if(document.form1.otros.value==""){
            			document.form1.especificar.type = "hidden";
						document.getElementById('especifique').style.visibility="hidden";
 						}
  
					 }
				
			</script>
			</head>
            <?
			//include('../paginas/acceso_db.php');
			$result = mysql_query("select MAX(id_mant) from mantenimiento"); 
							$resultado = mysql_result ($result, 0); 
							$id_mant = $resultado; 
							$id_mant=$id_mant+1;
  				  				
			?>	
			<body>
				<div id="container1">
					
					<div id="right1">
						
					</div>
					<div id="Contenido">
						<div align="justify" >
							<blockquote>
								<br>
								
                        	<center>
                        	  <strong>
                        	  <b><font size="4">Mantenimiento Preventivo</font></b>
                        	  </strong>
               	        </center>
                            <br>
                            <form name="form1" action="proceso_mantenimientopreventivo.php?a=1" method="post" onSubmit="return valid(this)">
                            	<table width="463" align="center" align="center" >
        							<tr>
                                    <td width="193" align="center" style="font-size:11px"><blockquote>
                                      <p>&nbsp;</p>
                                    </blockquote></td> 
                   		  		<td width="268" style="font-size:11px"><strong>Nro.</strong>
									<input name="id_mant" type="hidden" size="3" maxlength="11"  value="<? echo"$id_mant";?>"> 
									<input name="id_mant" type="text" size="3" maxlength="11" disabled value="<? echo"$id_mant";?>">
                                	<strong>Fecha</strong>
               						  <input type="text" name="fecha" size="8" id="datepicker" class=":required"/>
                               </tr>
                                <tr>
                                	<td align="left" style="font-size:11px" colspan="2"><label><strong><br>
                               	    Codigo del Vehiculo:</strong></label>
                                	  <?
									  echo"$cod_veh";
	  								  ?>
                                      <input name="cod_veh" type="hidden" value="<? echo "$cod_veh"?>">
                                  <br /></td>
                                    <td style="font-size:11px">&nbsp;</td>
       							  </tr>
                                   <tr>
                                	<td align="left" style="font-size:11px" colspan="2"><label><strong><br>
                               	    Placa:</strong></label> <?
									  echo"$placa";
	  								  ?>
                                    <input name="placa" type="hidden" value="<? echo "$placa"?>">
<br /></td>
                                    <td style="font-size:11px">&nbsp;</td>
        							</tr>
                                    <tr>
                                		<td align="left" style="font-size:11px" colspan="2"><label>                                		  <strong><br>
                               		    Modelo:</strong></label>                                		  
                                		  <?
									  echo"$modelo";
	  								  ?>
                                          <input name="modelo" type="hidden" value="<? echo "$modelo"?>">
                                      <br /></td>
                                        <td style="font-size:11px"><p><br />
                                        </p></td>
      						 		</tr>
                                    <tr>
                                	<td align="left" style="font-size:11px" colspan="2"><label><strong><br>
                               	    Año:</strong></label>
                                	  <?
									  echo"$ano";
	  								  ?>
                                      <input name="ano" type="hidden" value="<? echo "$ano"?>">
                                  <br /></td>
                                  
                                  <tr>
                                	<td align="left" style="font-size:11px" colspan="2"><label><strong><br>
                               	    Serial Motor:</strong></label>
                                	  <?
									  echo"$serialmotor";
	  								  ?>
                                      <input name="serialmotor" type="hidden" value="<? echo "$serialmotor"?>">
                                  <br /></td>
                                    <td style="font-size:11px">&nbsp;</td>
       							  </tr>
                                  <tr>
                                		<td align="left" style="font-size:11px" colspan="2"><label>                                		  <strong><br>
                               		    Serial Carro:</strong></label>                                		  
                                		  <?
									  echo"$serialcarro";
	  								  ?>
                                          <input name="serialcarro" type="hidden" value="<? echo "$serialcarro"?>">
                                      <br /></td>
                                        <td style="font-size:11px"><p><br />
                                        </p></td>
      						 		</tr>
                                    <tr>
                                		<td align="left" style="font-size:11px" colspan="2"><label>                                		  <strong><br>
                               		    Serial Carroceria:</strong></label>                                		  
                                		  <?
									  echo"$nrocarroceria";
	  								  ?>
                                          <input name="nrocarroceria" type="hidden" value="<? echo "$nrocarroceria"?>">
                                      <br /></td>
                                        <td style="font-size:11px"><p><br />
                                        </p></td>
      						 		</tr>
                                    <tr>
                                		<td align="left" style="font-size:11px" colspan="2"><label>                                		  <strong><br>
                               		    Marca:</strong></label>                                		  
                                		  <?
									  echo"$marca";
	  								  ?>
                                          <input name="marca" type="hidden" value="<? echo "$marca"?>">
                                      <br /></td>
                                        <td style="font-size:11px"><p><br />
                                        </p></td>
      						 		</tr>
                                     <tr>
                                		<td align="left" style="font-size:11px" colspan="2"><label>                                		  <strong>                               		    <br>
                               		    Dependencia Asignada:</strong></label>
                                		  <?
									  echo $dependencia;
	  								  ?>
                                          <input name="dependencia" type="hidden" value="<? echo "$dependencia"?>">
                                          <br />
                                        </p></td>
      						 		</tr>
                                     <tr>
                                		<td align="left" style="font-size:11px" colspan="2"><label><strong>Responsable:</strong></label>
                                		  <?
											 	//include('../paginas/acceso_db.php');
												include('../paginas/acceso_db_siaces.php');
												$query="select * from mastpersonas as mp,mastempleado as me WHERE mp.CodPersona = me.CodPersona 
													"; 
												$resp=mysql_query($query); 
												echo '<select name="personal" class=":required">';
												echo '<option selected value="0">Seleccione el Personal Asignado';   
												for($i=0;$i<mysql_num_rows($resp); $i++) 
													{
													$fila=mysql_fetch_array($resp); 
													$nombresapellidos=$fila['NomCompleto'];
  													echo '<option value="'.$nombresapellidos.'">'.$nombresapellidos.'</option>'; 
													} 
												echo '</select>';  
	  											?>
                                        </td>
        							</tr>
                                    <tr>
                                		<td align="left" style="font-size:11px" colspan="2"><label><strong> Proveedor:</strong></label>
                                		  <?
											 	//include('../paginas/acceso_db.php');
												include('../paginas/acceso_db_siaces.php');
												$query="select mp.* from mastpersonas as mp,mastempleado as me WHERE (mp.CodPersona = me.CodPersona and me.Estado='A' and mp.EsEmpleado='S') or mp.TipoPersona='J'  group by mp.NomCompleto order by mp.NomCompleto "; 
												$resp=mysql_query($query); 
												echo '<select name="proveedor" class=":required">';
												echo '<option selected value="0">Seleccione el Proveedor ';   
												for($i=0;$i<mysql_num_rows($resp); $i++) 
													{
													$fila=mysql_fetch_array($resp);
													$rifnombre=$fila['DocFiscal'].' '.$fila['NomCompleto'];
    												echo '<option value="'.$rifnombre.'">'.$rifnombre.'</option>'; 
													} 
												echo '</select>';  
	  											?>
                                        </td>
        							</tr>
                                    <tr>
                                		<td align="center" style="font-size:11px"><p>&nbsp;</p>
                                		  <p>
                                		    <label><strong>Aceite:</strong></label>
                              		    </p>
                                		  <p>
                                		    <input type="checkbox" value="si" name="amotor">
                                		    Motor
                                            <input type="checkbox" value="si" name="ahidraulico">
                                          Hidráulico</p>
                                		  <p><br />
                           		      </p></td>
                                        <td align="center" style="font-size:11px"><p>
                                          <label><strong>Filtro:</strong></label>
                                        </p>
                                        <p>
                                          <input type="checkbox" value="si" name="filtroaceite">
                                          Aceite
                                          <input type="checkbox" value="si" name="filtroaire">
                                          Aire
                                          <input type="checkbox" value="si" name="filtrogasolina">
                                        Gasolina </p></td>
      						 		</tr>
                                    <tr>
                                		<td align="center" style="font-size:11px"><strong>Neumáticos:</strong></td> 
      									<td style="font-size:11px"><label> Condición:
   									        <select name="n_condicion"> 
          											<option>--</option>
                                                    <option>Bueno</option>
                                                    <option>Aceptable</option>
                                                    <option>Inaceptable</option>
                                          </select>
      									    <br>
   									      Presión de Aire:</label> 	
                                            <label> 
        										<select name="n_presion"> 
          											<option>--</option>
                                                    <option>Mayor 40L</option>
                                                    <option>Igual 40L</option>
                                                    <option>Menor 40L</option>
                                    </select>
      										</label> 	
                                    	</td> 
                                    </tr>
                                    <tr>
                                		<td colspan="2" style="font-size:11px">
                                        <p>
                                          <label>
                                            <blockquote>
                                              <blockquote>
                                                <blockquote>
                                                  <blockquote>
                                                    <blockquote>
                                                      <blockquote>
                                                        <p>&nbsp;</p>
                                                        <p><strong>Varios:</strong></p>
                                                      </blockquote>
                                                    </blockquote>
                                                  </blockquote>
                                                </blockquote>
                                              </blockquote>
                                            </blockquote>
                                          </label>
                                        </p>
                                        <blockquote>
                                          <blockquote>
                                            <blockquote>
                                              <p>
                                                <input type="checkbox" value="si" name="cambiobu">
                                                Cambio de Bujias</p>
                                              <p>
                                              <input type="checkbox" value="si" name="lavadog">
                                              Lavado Gamuzado</p>
                                              <p>
                                              <input type="checkbox" value="si" name="cambiofreno">
                                              Cambio del Sistema de Frenos</p>
                                              <p>
                                              <input type="checkbox" value="si" name="grafito">
                                              Grafito</p>
                                              <p>
                                              <input type="checkbox" value="si" name="alineabalan">
                                              Alineacion y Balanceo</p>
                                            </blockquote>
                                          </blockquote>
                                        </blockquote>                                        </td>       
   						 		  </tr>
                                    <tr>
                               		   <td align="center" style="font-size:11px"><input type="checkbox" value="si" name="otros" onClick="javascript:MOSTRAR();">Otros<br/></td>
                                       <td style="font-size:11px"><div id="especifique" style="visibility:hidden"><label>Especifique</label></div><p> 
                                       <input type="hidden" name="especificar"/><br />
                                       </p></td>
                                       <tr>
                                		<td align="center" style="font-size:11px"><label><strong>Observaciones:</strong></label><br /></td>
   						 			    <td><textarea name="observaciones" size="20" cols="33" rows="3" class=":required"/></textarea></td>
                                    </tr>
            
                                  <tr>
                                    	<td align="center" style="font-size:13px"><p>&nbsp;
                                    	  </p>
                                    	<input type="submit" name="enviar" value="Registrar" onClick="javascript:procesar();"/>
                           	    </p></td>
        								<td align="center" style="font-size:13px"><p>&nbsp;
        								  </p>
        								  <p>
        								    <input type="reset" value="Borrar" />
   								      </p></td>
                                  </tr>
    							</table>
                            </form>	 
						</blockquote>
			  	  </div>
		    	</div>
				<div id="left">
					<div id="fondomenu2">
      				</div>
				</div>
			</div>
		</body>

</html>
