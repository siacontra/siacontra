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
    					var rif=campo1+"-"+campo2;
						document.getElementById('rif').value=rif;
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
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>

                        	<center><bold><font size="4">Proveedor a Editar</font></bold></center>
                            <form name="form1"action="proceso_consultaeditarproveedor.php?a=1" method="post" onSubmit="return valid(this)" >
                            <br>
                            	<table align="center" aling="center" bgcolor="#F8F8F8" >
        							<tr> 
      									<td>Rif:</td> 
      									<td><label> 
        										<select name="ci1" id="ci1"> 
          											<option>--</option> 
          											<option>J</option> 
          											<option>G</option> 
                                                    <option>V</option> 
        										</select> 
      										</label> 
        									-  
      										<label> 
      											<input name="ci2" type="text" id="ci2" size="8" maxlength="10" class=":required" onKeyPress="return solonumeros(event)"/> 
      										</label> 
                                           <input type="hidden" name="rif" value=" " id="rif" >	
                                         </td> 
                                	<tr>
                                    	<td><input type="submit" name="enviar" value="Siguiente"onClick="javascript:procesar()";/></td>
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