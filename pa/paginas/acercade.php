<?php
    session_start();
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
						  <p>&nbsp;</p>
				    	  <p>&nbsp;</p>
                          <center>
                          <strong><bold><font size="1">REALIZADO POR:</bold></strong>
				    
				    	  <p>&nbsp;</p>
                          <p>Asistente de Programacion: </p>
                          <p><strong>T.S.U Marialbersy T. Cabrera</strong></p>
                          <p>Correo: marialbersy@hotmail.com</p>
                          <p>Direccion Técnica e Informatica</p>
				    	  <p>Coordinacion de Informatica</p>
				    	 
                          </center>
                          </font>
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
<?php
    }
?>