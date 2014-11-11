<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../../index.php");
/// ------------------------
include("fphp.php");
connect();
?>
<html>
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<link href='http:../images/icon/logo.png' rel='shortcut icon' type='image/png'> 
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
				datetext=datetext+" "+d.getHours()+": "+d.getMinutes()+": "+d.getSeconds();
				$('#datepicker').val(datetext);
				},
});
        });
        
        
						function procesar() {
   						campo1=document.getElementById('ci1').value;
   						campo2=document.getElementById('ci2').value;
    					var cedula=campo1+"-"+campo2;
						document.getElementById('cedula').value=cedula;
						document.form.form1.submit();
						}
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
			</script>
			</head>
			<body>
				<div id="container">
					<div id="right">
						
					</div>
					<div id="Contenido">
						<div>
							<blockquote>
								<br>
								
                        	<center>   <bold><font size="4">Solicitud de Asistencia</font></bold></center>
                            <br>
                            <form name="form1" action="proceso_automovil.php?a=1" method="post" onSubmit="return valid(this)">
                            	<table align="center" align="center">
        							  <tr>
                                		<td align="center" style="font-size:13px"><label><strong>Unidad Organizativa:</strong></label>                                		  <br /></td>
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
                                		<td align="left" style="font-size:13px" colspan="2"><label><strong>Funcionario:</strong></label>
                                		  <?
											 	//include('../paginas/acceso_db.php');
												include('../paginas/acceso_db_siaces.php');
												$query="select * from mastpersonas as mp,mastempleado as me WHERE mp.CodPersona = me.CodPersona "; 
												$resp=mysql_query($query); 
												echo '<select name="personal" class=":required">';
												echo '<option selected value="0">Seleccione el Personal que Solicita la Asistencia';   
												for($i=0;$i<mysql_num_rows($resp); $i++) 
													{
													$fila=mysql_fetch_array($resp); 
													$nombresapellidos=$fila['NomCompleto'];
  													echo '<option value="'.$nombresapellidos.'">'.htmlentities($nombresapellidos).'</option>'; 
													} 
												echo '</select>';  
	  											?>
                                        </td>
        							</tr>
                                    
                               <!--     <tr>
											<td width="58" font size="4"><label>Fecha de Solicitud:</label><br /></td>
											<td width="144"><input type="text" name="desde" maxlength="10" id="datepicker" ></td>
									</tr>
                                    <tr> -->
                                		<td align="right" style="font-size:13px"><label><strong>Asunto:</strong></label>                                		  <br /></td>
                                                <td><textarea name="tx_asunto" size="80" cols="60" rows="2" class=":required"/></textarea></td>
                                    </tr>
                                  
                                    <tr>
                                		<td align="right" style="font-size:13px"><label><strong>Modalidad:</strong></label>                                		  <br /></td>
                                        <td ><input type="text" name="tx_modalidad" class=":required"/><br /></td>
      						 		</tr>
                                    <tr>
                                		<td align="right" style="font-size:13px"><label><strong>Funcionarios Receptores:</strong></label>                                		  <br /></td>
                                        <td ><input type="text" name="co_receptores" class=":required"/><br /></td>
      						 		</tr>
                                    <tr>
                                		<td align="right" style="font-size:13px"><label><strong>Observacion:</strong></label>                                		  <br /></td>
                                         <td><textarea name="tx_observacion" size="80" cols="60" rows="2" class=":required"/></textarea></td>
      						 		</tr>
                               
                                   
                                	<tr>
                                    	<td align="center"><input type="submit" name="enviar" value="Registrar" onClick="javascript:procesar();"/></td>
        								<td align="center"><input type="reset" value="Borrar" /></td>
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
