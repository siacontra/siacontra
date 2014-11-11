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
                <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
					<script type="text/javascript" src="js/jquery-1.3.2.js"></script>
					<script type="text/javascript" src="js/vanadium_es.js"></script>
					<script type="text/javascript" src="js/jqueryForm.js"></script>
					<link rel="stylesheet" type="text/css" href="css/vanadium.css" />
				<meta http-equiv="X-UA-Compatible" content="IE=8" />
                <script type="text/javascript">
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
						<div align="justify" >
							<blockquote>
								<br>
								<br>
								<br>
                        	<center>
                        	  <strong>
                        	  <bold><font size="4">Taller Entrada Vehiculo/Automóvil</font></bold>
                        	  </strong>
               	        </center>
                            <br>
                            <form name="form1" action="proceso_enviarentradavehiculotaller.php?a=1" method="post" onSubmit="return valid(this)">
                            	<table width="453" align="center" aling="center" >
        							<tr>
                                    <td align="center" style="font-size:13px"><label><strong>Placa</strong></label>                                	  <br /></td>
                                    <td style="font-size:11px">
                                      <?
											 	include('../paginas/acceso_db.php');
												$query="select * from automovil"; 
												$resp=mysql_query($query); 
												echo '<select name="placa" class=":required">';
												echo '<option selected value="0">Seleccione la Placa';   
												for($i=0;$i<mysql_num_rows($resp); $i++) 
													{ 
    												$fila=mysql_fetch_array($resp); 
  													echo '<option value="'.$fila['placa'].'">'.$fila['placa'].'</option>'; 
													} 
												echo '</select>';  
	  								  ?>
                                             
                                      </td>
        							</tr>
                                   
                                  <tr>
                                    	<td align="center" style="font-size:13px"><input type="submit" name="enviar" value="Siguiente" onClick="javascript:procesar();"/></td>
        								<td align="center" style="font-size:13px"><input type="reset" value="Borrar" /></td>
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
