<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href='http:../images/icon/logo.png' rel='shortcut icon' type='image/png'> 
		<title>Módulo de Parque Automotor</title>
		 <link type="text/css" href="../css/stilomenu.css" rel="stylesheet" media="screen" />
		<link type="text/css" href="../css/estilos.css" rel="stylesheet" media="screen" />
		<script language='JavaScript' type='text/JavaScript' src='fscript.js'></script>
        <meta http-equiv="X-UA-Compatible" content="IE=8" />
	</head>
	<frameset id='frmSet' frameborder='no' border='0' rows='75px, *'>
	<frame noresize scrolling='no'  src='frametop.php'>
	<frame src='framebottom.php'>
	</frameset>
	<noframes></noframes>
	<body>
			
				<div id="right">
					<center><img class="displayed" src="../images/usuario.png" border="0" width="63%"  height="21%"/> Bienvenido: <strong><?=$_SESSION["USUARIO_ACTUAL"]?></strong><br />
            		<a href="logout.php">Cerrar Sesión</a> 	
                    </center>
				</div>
				<div id="Contenido">
				  <div align="justify" >
				    	<blockquote>
                        	<br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                          <center><img src="../images/SPA.jpg" border="0" width="100%" height="30%"></center>
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

