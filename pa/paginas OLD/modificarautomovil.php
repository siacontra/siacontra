<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../../index.php");
/// ------------------------
include("fphp.php");

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
								<center><bold><font size="4">Modificar Automóvil</font></bold></center>
								<br>
								<form action="proceso_editarautomovil.php" method="post">
									<table align="center" aling="center" font size="2">
										<tr>
											<td align="right" style="font-size:13px"><label><strong>Placa:</strong></label>											  <br /></td>
											<td><input type="text" id="placa" name="placa" maxlength="15" value="<? echo"$placa";?>" class=":required"/></td>
										<input type="hidden" id="placa" name="placa"  value="<? echo"$placa";?>"/>
                                        </tr>
                                        <tr>
                                		<td align="right" style="font-size:13px"><label><strong>Codigo:</strong></label>                                		  <br /></td>
                                          <td ><input type="text" name="cod_veh"  value="<? echo"$cod_veh";?>"/></td>
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
                                        <input type="hidden" name="ano"  value="<? echo"$ano";?>"/></td> 
                                    </tr>
                                    <tr>
                                		<td align="right" style="font-size:13px"><label><strong>Modelo:</strong></label>                                		  <br /></td>
                                        <td ><input type="text" name="modelo"  value="<? echo"$modelo";?>"/></td>
                                       
      						 		</tr>
                                    <tr>
                                		<td align="right" style="font-size:13px"><label><strong>Básico:</strong></label>                                		  <br /></td>
                                        <td>
											<select name="basico">
											<option>--</option>
													<option <? if ($basico =='SEDAN') echo ' selected ';?> >SEDAN</option>
                                                    <option <? if ($basico =='FAMILIAR') echo ' selected ';?> >FAMILIAR</option>
                                                    <option <? if ($basico =='CUPE') echo ' selected ';?> >CUPE</option>
                                                    <option <? if ($basico =='HARDTOP') echo ' selected ';?> >HARDTOP</option>
                                                    <option <? if ($basico =='DEPORTIVO') echo ' selected ';?> >DEPORTIVO</option>
                                                    <option <? if ($basico =='RUSTICO') echo ' selected ';?> >RUSTICO</option>
                                                    <option <? if ($basico =='CAMIONETA') echo ' selected ';?> >CAMIONETA</option>
                                                    <option <? if ($basico =='MACHITO') echo ' selected ';?> >MACHITO</option>
											</selected> 
											
											
											
											</td>
      						 		</tr>
                                    <tr>
                                		<td align="right" style="font-size:13px"><label><strong>Marca:</strong></label>                                		  <br /></td>
                                        <td><input type="text" name="marca"  value="<? echo"$marca";?>"/></td>
      						 		</tr>
                                   <tr>
                                		<td align="right" style="font-size:13px"><label><strong>Color:</strong></label>                                		  <br /></td>
                                        <td><input type="text" name="color"  value="<? echo"$color";?>"/></td>
      						 		</tr>
                                   <tr>
                                		<td align="right" style="font-size:13px"><label><strong>Serial Motor:</strong></label>                                		  <br /></td>
                                        <td><input type="text" name="serialmotor"  value="<? echo"$serialmotor";?>"/></td>
      						 		</tr>
                                    <tr>
                                		<td align="right" style="font-size:13px"><label><strong>Serial Carro:</strong></label>                                		  <br /></td>
      						 			 <td><input type="text" name="serialcarro"  value="<? echo"$serialcarro";?>"/></td>
                                    </tr>
                                    <tr>
                                		<td align="right" style="font-size:13px"><label><strong>Serial Carroceria:</strong></label>                                		  <br /></td>
      						 			 <td><input type="text" name="nrocarroceria"  value="<? echo"$nrocarroceria";?>"/></td>
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
                                    
											<td><input type="submit" name="enviar" value="Modificar" /></td>
											<td><input type="reset" value="Borrar" /></td>
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
