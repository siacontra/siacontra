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
        <meta http-equiv="X-UA-Compatible" content="IE=8" />
        <script>
		function elim(){
					if(!confirm("¿Realmente desea eliminar el Automóvil?")){
						return false;
					}
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
                        	<center><bold><font size="4">Listado de Automóviles<br></font></bold></center><br>
            <table border="1" align="center" width="400px">
        	<tr style="font-size:11px; background:#E2E2E2;" align="center">
                <td><strong>Nº</strong></td>
                <td colspan="3" align="center"><strong>Placa del Automóvil</strong></td>
                
            </tr>
            <?
					
						$dd=mysql_query("select * from automovil");
		 				while($d=mysql_fetch_assoc($dd)){
							
							?>
                            <form action="proceso_modificarautomovil.php?a=1" method="post" onSubmit="return valid(this)">
							<tr style="font-size:10px;" align="center">
                            	<td>
							<?
								$j=1+$j;
								echo "
									$j</td>
									<td>$d[placa]</td>
									";
								 $placa=$d[placa];
									?>
                                    <td>
                                    	<input name="placa" type="hidden" value="<? echo"$placa";?>">
                                    	<input type="submit" name="opcion" value="Editar"/>
                                    </td>
                                    <td>
                                        <input type="submit" name="opcion" value="Borrar" onClick="return elim();"/>
                                    </td>
                                   
                                   
                                    </form>
                                   
									<?
						echo "			
							</tr>
					";
					}
	    	?>
			
        </table>
						</blockquote>
                          
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

