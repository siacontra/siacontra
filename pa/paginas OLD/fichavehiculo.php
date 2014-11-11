<?php
    session_start();
    include('../paginas/acceso_db.php');
    if(isset($_SESSION["USUARIO_ACTUAL"])) {
?> 
<html>
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<link href='http:../images/icon/logo.png' rel='shortcut icon' type='image/png'> 
				<title>Módulo de Parque Automotor</title>
				<link type="text/css" href="../css/estilos.css" rel="stylesheet" media="screen" />
                <link type="text/css" href="../css/stilomenu.css" rel="stylesheet" media="screen" />
                <link rel="stylesheet" type="text/css" href="css/jquery-ui-1.7.2.custom.css" />
                <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
                
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
			$result = mysql_query("select MAX(id_ficha) from fichavehiculo"); 
							$resultado = mysql_result ($result, 0); 
							$id_mant = $resultado; 
							$id_ficha=$id_ficha+1;
  				  				
			?>	
			<body>
				<div id="container1">
					<div id="banner">
						<img class="displayed" src="../../imagenes/banner_parqueautomotor.png" border="0" width="100%" height="98%"/>
					</div>
					<div id="fondomenu">
						<div id="liston">	
							 <?	
							include('menu.php');
						?>
						</div> 
					</div>
					<div id="right1">
						Bienvenido: <strong><?=$_SESSION["USUARIO_ACTUAL"]?></strong><br />
						<a href="logout.php">Cerrar Sesión</a> 	
					</div>
					<div id="Contenido">
						<div align="justify" >
							<blockquote>
								<br>
								<br>
								<br>
                        	<center>
                        	  <strong>
                        	  <bold><font size="4">Ficha del Vehiculo</font></bold>
                        	  </strong>
               	        </center>
                            <br>
                            <form name="form1" action="proceso_fichavehiculo.php?a=1" method="post" onSubmit="return valid(this)">
                            	<table width="463" align="center" bgcolor="#F8F8F8" aling="center" >
        							<tr>
                                    <td width="193" align="center" style="font-size:11px"><blockquote>
                                      <p>&nbsp;</p>
                                    </blockquote></td> 
                   		  <td width="268" style="font-size:11px"><strong>Nro.</strong>
<input name="id_mant" type="hidden" size="3" maxlength="11"  value="<? echo"$id_mant";?>"> 
														<input name="id_mant" type="text" size="3" maxlength="11" disabled value="<? echo"$id_mant";?>">
                                                         <strong>Fecha</strong>
                   		      <input name="fecha" type="hidden" value="<? echo date('d/m/y');?>" size="4" > 
                   		      <input name="fecha" type="text" value="<? echo date('d/m/y');?>" size="4" disabled>
                                       
                              

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
                               	    Básico:</strong></label>
                                	  <?
									  echo"$basico";
	  								  ?>
                                      <input name="basico" type="hidden" value="<? echo "$basico"?>">
                                  <br /></td>
                                    <td style="font-size:11px">&nbsp;</td>
       							  </tr>
                                   <tr>
                                	<td align="left" style="font-size:11px" colspan="2"><label><strong><br>
                               	    Color:</strong></label>
                                	  <?
									  echo"$color";
	  								  ?>
                                      <input name="color" type="hidden" value="<? echo "$color"?>">
                                  <br /></td>
                                    <td style="font-size:11px">&nbsp;</td>
       							  </tr>
                                  
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
									  echo"$dependencia";
	  								  ?>
                                          <input name="dependencia" type="hidden" value="<? echo "$dependencia"?>">
                                          <br />
                                        </p></td>
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
				<div id="left1">
					<div id="fondomenu2">
      				</div>
				</div>
			</div>
		</body>

</html>
<?php
    }
?>