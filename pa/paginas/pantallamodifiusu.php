<?php
    session_start();
    include('acceso_db.php');
    if(isset($_SESSION["USUARIO_ACTUAL"])) {
		
?> 
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href='http:../images/icon/logo.png' rel='shortcut icon' type='image/png'> 
		<title>Módulo de Parque Automotor</title>
		<link type="text/css" href="../css/estilos.css" rel="stylesheet" media="screen" />
		<meta http-equiv="X-UA-Compatible" content="IE=8" />
         <link type="text/css" href="../css/stilomenu.css" rel="stylesheet" media="screen" />
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<script type="text/javascript" src="js/jquery-1.3.2.js"></script>
		<script type="text/javascript" src="js/vanadium_es.js"></script>
		<script type="text/javascript" src="js/jqueryForm.js"></script>
		<link rel="stylesheet" type="text/css" href="css/vanadium.css" />
	</head>
			<body>
				<div id="container">
					<div id="banner">
						<img class="displayed" src="../../imagenes/banner_parqueautomotor.png" border="0" width="100%" height="98%"/>
					</div>
					<div id="fondomenu">
						<div id="liston">	
							 <?	
							include('menu.php');
						?>
						</div> 
					</div>
					<div id="right">
						<center><img class="displayed" src="../images/usuario.png" border="0" width="63%"  height="21%"/>Bienvenido: <strong><?=$_SESSION["USUARIO_ACTUAL"]?></strong><br />
						<a href="logout.php">Cerrar Sesión</a> 	
                        </center>
					</div>
					<div id="Contenido">
						<div align="justify" >
							<blockquote>
								<br>
								<br>
								<br>
								<center><bold><font size="4">Editar Clave de Usuario</font></bold></center>
								<form action="proceso_editclave.php" method="post" onSubmit="return valid(this)">
									<table width="409" align="center" bgcolor="#F8F8F8" aling="center" font size="2">
										<tr>
											<td width="204"><label>Usuario:</label><br /></td>
											<td width="193"><input type="text" name="usuario_nombre" value="<? echo"$usuario_nom"; ?>" readonly/><br /></td>
										</tr>
										<tr>
											<td><label>Contraseña:</label><br /></td>
											<td><input id="pass" class=":required" type="password" name="usuario_clave"><br /></td>
										</tr>
										<tr>
											<td><label>Confirmar Contraseña:</label><br /></td>
											<td><input class=":same_as;pass" type="password"><br /></td>
										</tr>
                                        <tr>
                                			<td><input type="hidden" name="usuario_id" value="<? echo"$usuario_id"; ?>"></td>
                                		<tr>
                                        <tr>
											<td><input type="submit" name="enviar" value="Editar" /></td>
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
 <?php
    }
?>