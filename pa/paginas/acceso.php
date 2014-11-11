<?php
    session_start();
    include('acceso_db.php');
    if(empty($_SESSION["USUARIO_ACTUAL"])) { // comprobamos que las variables de sesin estn vacas 
        if(isset($_POST['enviar'])) { // comprobamos que se hayan enviado los datos del formulario
            // comprobamos que los campos usuarios_nombre y usuario_clave no estn vacos
            if(empty($_POST['usuario_nombre']) || empty($_POST['usuario_clave'])) {
                echo"
 					<script>
  						 window.location='acceso.php';
    					alert('La Contraseña o Usuario no han sido ingresados');
	 				</script>
				 ";
            }else {
                // "limpiamos" los campos del formulario de posibles cdigos maliciosos
                $usuario_nombre = mysql_real_escape_string($_POST['usuario_nombre']);
                $usuario_clave = mysql_real_escape_string($_POST['usuario_clave']);
                //$usuario_clave = md5($usuario_clave);
                // comprobamos que los datos ingresados en el formulario coincidan con los de la BD
                $sql = mysql_query("SELECT usuario_id, usuario_nombre, usuario_clave FROM usuarios WHERE usuario_nombre='".$usuario_nombre."' AND usuario_clave='".$usuario_clave."'");
                if($row = mysql_fetch_array($sql)) {
                    $_SESSION['usuario_id'] = $row['usuario_id']; // creamos la sesion "usuario_id" y le asignamos como valor el campo usuario_id
                    $_SESSION["USUARIO_ACTUAL"] = $row["usuario_nombre"]; // creamos la sesion "usuario_nombre" y le asignamos como valor el campo usuario_nombre
?>
                    <script type="text/javascript">
                        var pagina = "principal.php" <!-- Redirigimos al index o la pgina que se desee -->
                        function redireccionar() {
                            location.href = pagina
                        }
                        setTimeout ("redireccionar()", 1000);
                    </script>
<?php
                }else {
                    echo "
 					<script>
  						 window.location='acceso.php';
    					alert('La Contraseña o Usuario no son correctos');
	 				</script>
				 ";
                }
            }
        }else {
?>
      <html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href='http:../images/icon/logo.png' rel='shortcut icon' type='image/png'> 
		<title>Módulo de Parque Automotor</title>
		<link type="text/css" href="../css/estilos.css" rel="stylesheet" media="screen" />
        <meta http-equiv="X-UA-Compatible" content="IE=8" />
	</head>
	<body>
			<div id="container">
				<div id="banner">
					<img class="displayed" src="../../imagenes/banner_parqueautomotor.png" border="0" width="100%" height="98%"/>
				</div>
				<div id="fondomenu">
					<div id="liston">	
						
					</div> 
				</div>
				<div id="right">
						
				</div>
				<div id="Contenido">
				  <div align="justify" >
				    	<blockquote>
                        	<br>
                            <br>
                            <br>
                            <br>
                        	<center><img class="displayed" src="../images/titulop.png" border="0" width="76%"  height="12%"/></center>
                            	<form form action="<?=$_SERVER['PHP_SELF']?>" method="post">
                                <p><a href="../DOCUMENTOS/MANUAL.pdf"></a>
       			    				<table align="center" aling="center" bgcolor="#CCCCCC" >
                            			<tr>
                        					<td colspan="2" b><center><strong>Iniciar Sesión</strong></center></td>
                                      </tr>
                                        <tr>   
                        					<td>Usuario:</td>
                            				<td><input type="text" name="usuario_nombre" /><br /></td>
                        				</tr>
                        				<tr>
                        					<td>Contraseña:</td>
                            				<td><input type="password" name="usuario_clave" /><br /><br></td>
                        				</tr>
                                		<tr>
                                			<td align="center" colspan="2"><input type="submit" name="enviar" value="Ingresar" /> </td>
                                		</tr>
                       			  </table>
       			    				<center><a href="../DOCUMENTOS/MANUAL.pdf">Manual de Ayuda</a></p></center>
                          </form>
                          <center></center>
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
    }else {
		?>
      <script type="text/javascript">
                        var pagina = "logout.php" <!-- Redirigimos al index o la pgina que se desee -->
                        function redireccionar() {
                            location.href = pagina
                        }
                        setTimeout ("redireccionar()", 1000);
                    </script>
         <?
    }
?> 