
<?php
    session_start();
	$id_mant =$_REQUEST[id_mant];
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
                <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
					<script type="text/javascript" src="js/jquery-1.3.2.js"></script>
					<script type="text/javascript" src="js/vanadium_es.js"></script>
					<script type="text/javascript" src="js/jqueryForm.js"></script>
					<link rel="stylesheet" type="text/css" href="css/vanadium.css" />
				<meta http-equiv="X-UA-Compatible" content="IE=8" />
	</head>
	<body>
				<div id="container">
					
					<div id="fondomenu">
						
					</div>
					<div id="right">
						
					</div>
					<div id="Contenido">
						<div align="justify" >
							<blockquote>
								<br>
								<br>
								<br>
                        	<center>
                        	  <bold><font size="4">Reporte de Mantenimiento</font></bold></center>
                            <br>
                        	<center><img class="displayed" src="../images/reporte.png" border="0" width="40%" height="30%"/></center>
                        	<table align="center">
                            <tr> <td>
                            <form action="../pdf/pdfmantenimiento.php?id_mant=<? echo"$id_mant";?>" method="post" onSubmit="return valid(this)" target="_blank">
                             <input name="rif" type="hidden" value="<? echo"$rif";?>"/>
                             <input type="submit" name="agregar" value="Imprimir" />
                             
                            </form></td>
                            </tr>
                            </table> 
                          
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
<?php
    }
?>
