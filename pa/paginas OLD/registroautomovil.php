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
                <link type="text/css" href="../css/stilomenu.css" rel="stylesheet" media="screen" />
                <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
					<script type="text/javascript" src="js/jquery-1.3.2.js"></script>
					<script type="text/javascript" src="js/vanadium_es.js"></script>
					<script type="text/javascript" src="js/jqueryForm.js"></script>
					<link rel="stylesheet" type="text/css" href="css/vanadium.css" />
				<meta http-equiv="X-UA-Compatible" content="IE=8" />
                <script type="text/javascript">
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
								
                        	<center>
                        	  <bold><font size="4">Registro de Automóvil</font></bold></center>
                            <br>
                            <form name="form1" action="proceso_automovil.php?a=1" method="post" onSubmit="return valid(this)">
                            	<table align="center" aling="center">
        							 <tr>
                                		<td align="right" style="font-size:13px"><label><strong>Codigo:</strong></label>                                		  <br /></td>
                                        <td ><input type="text" name="cod_veh" maxlength="15" class=":required"/><br /></td>
                                    </tr>
                                    <tr>
                                		<td align="right" style="font-size:13px"><label><strong>Placa:</strong></label>                                		  <br /></td>
                                        <td ><input type="text" name="placa" maxlength="15" class=":required"/><br /></td>
                                    </tr>
                                    <tr>
                                	<tr>
                                		<td align="right" style="font-size:13px"><strong>Año:</strong></td> 
      									<td><label> 
        										<select name="ano"> 
          											
          											<? 
          										$i=0; $y= date("Y");
        										$list='<option>--</option>';
        										do { 
													
													$list=$list.'<option';
													if ($ano ==$y) 
														$list=$list.' selected ';
													$list=$list.'>'.$y.'</option>';
													$y=$y-1;
													$i=$i+1;
                                                    /*<option>2015</option>
                                                    <option>2014</option>
                                                    <option>2013</option>
                                                    <option>2012</option>
                                                    <option>2011</option>
                                                    <option>2010</option>
                                                    <option>2009</option>
                                                    <option>2008</option>
                                                    <option>2007</option>
                                                    <option>2006</option>
                                                    <option>2005</option>
                                                    <option>2004</option>
                                                    <option>2003</option>
                                                    <option>2002</option>
                                                    <option>2001</option>
                                                    <option>2000</option>
                                                    <option>1999</option> 
                                                    <option>1998</option>
                                                    <option>1997</option>
                                                    <option>1996</option>
                                                    <option>1995</option>
                                                    <option>1994</option>
                                                    <option>1993</option> 
          											<option>1992</option> 
          											<option>1991</option>
                                                    <option>1990</option>*/
                                                    }while ($i<=30); echo $list;?>
                                                  </select>
      									</label> 	
                                         </td> 
                                    </tr>
                                    <tr>
                                		<td align="right" style="font-size:13px"><label><strong>Modelo:</strong></label>                                		  <br /></td>
                                        <td ><input type="text" name="modelo" class=":required"/><br /></td>
      						 		</tr>
                                    <tr>
                                		<td align="right" style="font-size:13px"><label><strong>Básico:</strong></label>  
                                		                              		  <br /></td>
                                        <td ><select name="basico">
											<option>--</option>
													<option  >SEDAN</option>
                                                    <option  >FAMILIAR</option>
                                                    <option  >CUPE</option>
                                                    <option  >HARDTOP</option>
                                                    <option  >DEPORTIVO</option>
                                                    <option  >RUSTICO</option>
                                                    <option  >CAMIONETA</option>
                                                    <option  >MACHITO</option>
											</selected> 
											</td>
      						 		</tr>
                                    <tr>
                                		<td align="right" style="font-size:13px"><label><strong>Marca:</strong></label>                                		  <br /></td>
                                        <td ><input type="text" name="marca" class=":required"/><br /></td>
      						 		</tr>
                                    <tr>
                                		<td align="right" style="font-size:13px"><label><strong>Color:</strong></label>                                		  <br /></td>
      						 			<td><input type="text" name="color"  class=":required" onKeyPress="return sololetras(event)"/><br /></td>
                                    </tr>  
                                    <tr>
                                		<td align="right" style="font-size:13px"><label><strong>Serial Motor:</strong></label>                                		  <br /></td>
      						 			<td ><input type="text" name="serialmotor" class=":required"/><br /></td>
                                    </tr>
                                    <tr>
                                		<td align="right" style="font-size:13px"><label><strong>Serial Carro:</strong></label>                                		  <br /></td>
      						 			<td ><input type="text" name="serialcarro" class=":required"/><br /></td>
                                    </tr>
                                    <tr>
                                		<td align="right" style="font-size:13px"><label><strong>Serial Carrocería:</strong></label>                                		  <br /></td>
      						 			<td ><input type="text" name="nrocarroceria" class=":required"/><br /></td>
                                    </tr>
                                    <tr>
                                		<td align="center" style="font-size:13px"><label><strong>Asignado a:</strong></label>                                		  <br /></td>
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
