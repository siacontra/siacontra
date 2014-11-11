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
						Bienvenido: <strong><?=$_SESSION["USUARIO_ACTUAL"]?></strong><br />
						<a href="logout.php">Cerrar Sesión</a> 	
					</div>
					<div id="Contenido">
						<div align="justify" >
							<blockquote>
								<br>
								<br>
								<br>
								<center><bold><font size="4">Modificar Dependencia</font></bold></center>
								<br>
								<form action="proceso_editardependencia.php?a=1" method="post" onSubmit="return valid(this)">
									<table align="center" aling="center" bgcolor="#F8F8F8" font size="2">
										<tr>
											<td><label>Dependencia</label><br /></td>
											<td><input type="text" name="nomdependencia" maxlength="40" value="<? echo"$nomdependencia";?>" class=":required" onKeyPress="return sololetras(event)"/><br /></td>
										<input type="hidden" name="id"  value="<? echo"$id";?>"/>
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
<?php
    }
?>