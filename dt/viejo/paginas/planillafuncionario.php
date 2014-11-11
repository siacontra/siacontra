<?php
    session_start();
    include('acceso_db.php');
	$mayusculas=strtoupper($dependencia);
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
    	<center><strong>LISTADO DE PERMISOS POR FUNCIONARIO</strong></center>
        <br>
        <table style="font-size:11px;">
        	<tr>
        		<td><strong>Cedula de Identidad:</strong><td><td><? echo"$cedula";?><td>
        	<td><strong>Funcionario:</strong><td><td><? echo"$nombres $apellidos";?><td></tr>
            <tr><td><strong>Dependencia:</strong><td><td><? echo"$dependencia";?> <td><td><strong>Cargo:</strong><td><td><? echo"$cargo";?></tr>
		</table>
        <br>
        <table border="1" align="center" width="660">
        	<tr style="font-size:11px; background:#E2E2E2;" align="center">
                <td width="20"><strong>N�</strong></td>
                <td width="132"><strong>Motivo</strong></td>
              	<td width="72"><strong>Fecha de Inicio</strong></td>
                <td width="80"><strong>Fecha de Culminaci�n</strong></td>
            </tr>
            <?
						$xx=mysql_query( "SELECT * FROM permiso WHERE'$cedula'=CI");
		 				while($x=mysql_fetch_assoc($xx)){
							?>
							<tr style="font-size:10px;" align="center">
                            	<td>
							<?
								$j=1+$j;
								echo "
									$j</td>
									<td>$x[motivo]</td>
									<td>$x[fechainic]</td>
									<td>$x[fechaculm]</td>
										</tr>
										";
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