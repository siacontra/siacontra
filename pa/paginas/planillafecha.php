<?php
    session_start();
    include('acceso_db.php');
	
	//definir Variables a usar
    if(isset($_SESSION["USUARIO_ACTUAL"])) {
?> 
<html>
	<head>
   		 <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
		<link type="text/css" href="css/estiloreport.css" rel="stylesheet" media="screen" />
        <link href="css/estiloreporimp.css" rel="stylesheet" type="text/css" media="print"> 
    </head>
	<body>
    <div id="container">
    	<img class="displayed" src="../images/encabezado.jpg" border="0" width="110%" height="15%"/>
<br>
         <br>
    	<center><strong>LISTADO DE FUNCIONARIOS DE PERMISO ENTRE FECHAS <? echo"$desde";?> - <? echo"$hasta";?></strong></center>
        <br>
</table>
        <br>
        <table border="1" align="center" width="660">
        	<tr style="font-size:11px; background:#E2E2E2;" align="center">
                <td width="20"><strong>N�</strong></td>
                <td width="74"><strong>C.I</strong></td>
                <td width="103"><strong>Nombre y Apellido</strong></td>
          <td width="55"><strong>Cargo</strong></td>
                <td width="72"><strong>Dependencia</strong></td>
                <td width="132"><strong>Motivo</strong></td>
              <td width="72"><strong>Fecha de Inicio</strong></td>
                <td width="80"><strong>Fecha de Culminaci�n</strong></td>
            </tr>
            <?
					
						$dd=mysql_query( "SELECT * FROM permiso WHERE  fechainic>='".$desde."' AND Fechaculm<='".$hasta."'");
		 				while($d=mysql_fetch_assoc($dd)){
							$cedula=$d[CI];
							$xx=mysql_query( "SELECT * FROM personal WHERE'$d[CI]'=cedula ");
		 				while($x=mysql_fetch_assoc($xx)){
							?>
							<tr style="font-size:10px;" align="center">
                            	<td>
							<?
								$j=1+$j;
								echo "
									$j</td>
									<td>$d[CI]</td>
									<td>$x[nombres] $x[apellidos]</td>
									<td>$x[cargo]</td>
									<td>$x[dependencia]</td>
									<td>$d[motivo]</td>
									<td>$d[fechainic]</td>
									<td>$d[fechaculm]</td>
								</tr>
										";
								
					}
						}
					//
	    	?>
			
        </table>
<br>
          
        </div>
         <div id="pie">
         <br><br>
         <strong>CONSTANCIA DE REPRESENTACION DE RENDICION DE CUENTAS EMITIDA POR LA CONTRALOR�A DEL ESTADO TRUJILLO<BR>
        	En Fecha:</strong><?php echo date ( "d-m-Y H:i:s" , time () ); ?></br>
         <img class="displayed" src="../images/pie.jpg" border="0" width="110%" height="88%" align="middle"/>
        
         </div>
          <div id="cen">
          	<div id="estilob">
            	<table align="center" width="250px">
         	 	<tr><td><form>
         		  	<input type="button" name="imprimir" value="Imprimir" onClick="window.print();">
                    <input type="button" name="atras" value="Atras" onClick="javascript:history.go(-1);">
       			 </form></td></tr>
                 </table>
        	</div>
          </div>
    </body>
</html>
<?php
    }
?>