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
					function CheckTime(str) 
					{ 
						hora=str.value 
						if (hora=='') {return} 
						if (hora.length>8) {alert("Introdujo una cadena mayor a 8 caracteres");return} 
						if (hora.length!=8) {alert("Introducir HH:MM:SS");return} 
						a=hora.charAt(0) //<=2 
						b=hora.charAt(1) //<4 
						c=hora.charAt(2) //: 
						d=hora.charAt(3) //<=5 
						e=hora.charAt(5) //: 
						f=hora.charAt(6) //<=5 
						if ((a==2 && b>3) || (a>2)) {alert("El valor que introdujo en la Hora no corresponde, introduzca un digito entre 00 y 23");return} 
						if (d>5) {alert("El valor que introdujo en los minutos no corresponde, introduzca un digito entre 00 y 59");return} 
						if (f>5) {alert("El valor que introdujo en los segundos no corresponde");return} 
						if (c!=':' || e!=':') {alert("Introduzca el caracter ':' para separar la hora, los minutos y los segundos");return} 

}  
				
			</script>
			</head>
            <?
			$result = mysql_query("select MAX(id_talleren) from tallerentrada"); 
							$resultado = mysql_result ($result, 0); 
							$id_talleren = $resultado; 
							$id_talleren=$id_talleren+1;
  				  				
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
                        	  <bold><font size="4">Taller Entrada de Vehiculo/Automóvil</font></bold>
                        	  </strong>
               	        </center>                           
                            <form name="form1" action="proceso_entradavehiculotaller.php?a=1" method="post" onSubmit="return valid(this)">
                            	<table width="463" align="center">
       							  <tr>
                                    <td width="138" align="center" style="font-size:11px"><blockquote>&nbsp;</blockquote></td> 
                   		  <td align="left" width="313" style="font-size:11px"><blockquote>
                   		    <p><strong>Nro.</strong>
                   		      
                   		      <input name="id_talleren" type="text" size="2" maxlength="11" disabled value="<? echo"$id_talleren";?>">
                   		      <strong>Fecha</strong>
               				  <input type="text" name="fecha" size="8" id="datepicker"/>
                                <input name="fecha" type="hidden" class=":required" value="<? echo date('d/m/y');?>" size="4" > 

               		        </p>
                 		    </blockquote></td>
                                </tr>
                                 <tr>
                                	<td align="right" style="font-size:13px"><label><strong>Cod Vehiculo:</strong></label>
                                	  <br /></td>
                                    <td style="font-size:13px"><?
									  echo"$cod_veh";
	  								  ?>
                                      <input name="cod_veh" type="hidden" value="<? echo "$cod_veh"?>"></td>
        							</tr>
                                    <tr>
                                	<td align="right" style="font-size:13px"><label><strong>Placa:</strong></label>
                                	  <br /></td>
                                    <td style="font-size:13px">
										<?
									 		echo"$placa";
	  								 	?>
                                      <input name="placa" type="hidden" value="<? echo "$placa"?>"></td>
        							</tr>
                                    <tr>
                                		<td align="right" style="font-size:13px"><label><strong>Modelo:</strong></label><br /></td>
                                        <td style="font-size:13px"><p>
                                          <?
									 		 echo"$modelo";
	  									  ?>
                                          <input name="modelo" type="hidden" value="<? echo "$modelo"?>">
                                        </p></td>
      						 		</tr>
                                    <tr>
                                		<td align="right" style="font-size:13px"><label><strong>Hora:</strong></label>                                		  <br /></td>
      						 			<td><input type="text" name="hora" onBlur="CheckTime(this)" size="12" maxlength="10" class=":required"/><br /></td>
                                    </tr>
                                     <tr>
                                		<td align="right" style="font-size:13px"><label><strong>Motivo:</strong></label><br /></td>
   						 			    <td><textarea name="motivo" size="20" cols="33" rows="3" class=":required"/></textarea></td>
                                    </tr>
                                    <tr>
                                		<td align="right" style="font-size:13px"><label><strong>Kilometraje:</strong></label>                                		  <br /></td>
      						 			<td style="font-size:11px"><input type="text" size="15" name="kilometraje" maxlength="11" class=":required" onKeyPress="return solonumeros(event)"/>
      						 			km<br /></td>
                                    </tr>
                                     <tr>
                                		<td align="center" style="font-size:13px"><label><strong>Nombre del Taller:</strong></label>                                		  <br /></td>
      						 			<td ><input type="text" size="42" name="nombretaller" class=":required"/><br /></td>
                                    </tr>
                                    <tr>
                                		<td align="center" style="font-size:12px"><p>&nbsp;</p>
                                		    <label><strong>Cobertura de Reparación:</strong></label>
                              		    	   <td style="font-size:11"> <input type="checkbox" value="si" name="seguro">Por Seguro
                                               <input type="checkbox" value="si" name="institucion"> Por Contraloria</td>
                                		  <p><br />
                           		      </td>
                                      </tr>
                                    <tr>
                                		<td align="right" style="font-size:13px"><label><strong>Observaciones:</strong></label><br /></td>
   						 			    <td><textarea name="observaciones" size="20" cols="33" rows="3" class=":required"/></textarea></td>
                                    </tr>
    							    <tr>
                                    	<td align="center" style="font-size:13px"><p>
                                    	  <input type="submit" name="enviar" value="Registrar" />
                           	    </p></td>
        								<td align="center" style="font-size:13px"><p>
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