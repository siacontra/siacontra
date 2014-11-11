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
                <link type="text/css" href="../css/stilomenu.css" rel="stylesheet" media="screen" />
				<link type="text/css" href="../css/estilos.css" rel="stylesheet" media="screen" />
                <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
				<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.7.2.custom.css" />
                <link type="text/css" href="../css/stylemenupersonal.css" rel="stylesheet" media="screen" />

					<script type="text/javascript" src="js/jquery-1.3.2.js"></script>
					<script type="text/javascript" src="js/vanadium_es.js"></script>
					<script type="text/javascript" src="js/jqueryForm.js"></script>
                    <script type="text/javascript" src="js/jquery.min.js"></script>
                    <script type="text/javascript" src="js/jquery-ui.min.js"></script> 
					<link rel="stylesheet" type="text/css" href="css/vanadium.css" />
				<meta http-equiv="X-UA-Compatible" content="IE=8" />
                <script type="text/javascript">
					jQuery(function($){
						$.datepicker.regional['es'] = {
						closeText: 'Cerrar',
						prevText: '&#x3c;Ant',
						nextText: 'Sig&#x3e;',
						currentText: 'Hoy',
						monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
						'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
						monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
						dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
						dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
						dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
						weekHeader: 'Sm',
						dateFormat: 'yy/mm/dd',
						firstDay: 1,
						isRTL: false,
						showMonthAfterYear: false,
						yearSuffix: ''};
						$.datepicker.setDefaults($.datepicker.regional['es']);
					});    

        $(document).ready(function() {
           $("#datepicker").datepicker();
        });
		 $(document).ready(function() {
           $("#datep").datepicker();
        });
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
				
					function procesar() {
   						campo1=document.getElementById('rif1').value;
   						campo2=document.getElementById('rif2').value;
						campo3=document.getElementById('rif3').value;
    					var rif=campo1+"-"+campo2+"-"+campo3;
						document.getElementById('rif').value=rif;
						document.form.form1.submit();
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
                          <br><br><br>
                        <table align="center">
                      	<tr style="font-weight:bold; font-size:15px">
                        	<td align="center">
                          		CONTROL DE ASISTENCIA<br>
                         	</td>
                         </tr>
                          <tr>
                        	<td align="center" style="font-size:11px">
                          		C.I: <? echo"$_GET[cedula]";?>
                               
                            </td>
                          </tr>
                         <tr>
                         	<td align="center" style="font-size:13px">
                          		<bold>Funcionario: </bold><? $mayusculas=strtoupper($nombres); echo"$mayusculas"; $mayusculas1=strtoupper($apellidos); echo" $mayusculas1";?>
                          	</td>
                         </tr>
                         <tr>
                        	<td align="center" style="font-size:13px">
                          		Dependencia: <? echo"$_GET[dependencia]";?>
                               
                            </td>
                          </tr>
					  </table>
                      <br><br>
                      <table align="center">
                      	<tr>
                        	<td>
                          		<ul id="menupersonal" class="topmenu">
									<li class="topfirst"><a href="llegadastardias.php?cedula=<? echo"$_GET[cedula]";?>&nombres=<? echo"$_GET[nombres]";?>&apellidos=<? echo"$_GET[apellidos]";?>" style="width:138px;"><img src="../images/diagram-20.png" alt="LLegadas Tardias"/>LLegadas Tardias</a></li>
									<li class="topmenu"><a href="#" style="width:138px;"><span><img src="../images/note.png" alt="Permisos"/>Permisos</span></a>
										<ul>
											<li class="subfirst"><a href="Registropermiso.php?cedula=<? echo"$_GET[cedula]";?>&nombres=<? echo"$_GET[nombres]";?>&apellidos=<? echo"$_GET[apellidos]";?>" ><img src="../images/remind_manager1.png" alt="Personal"/>Personal</a></li>
											<li><a href="Registromedico.php?cedula=<? echo"$_GET[cedula]";?>&nombres=<? echo"$_GET[nombres]";?>&apellidos=<? echo"$_GET[apellidos]";?>"><img src="../images/archives (2).png" alt="Medico"/>Medico</a></li>
                                            <li><a target="_blank" href="proceso_generarfuncionario.php?cedula=<? echo"$_GET[cedula]";?>"  style="width:138px;"><img src="../images/256sub1.png" alt="Reporte"/>Reporte</a></li>
											</ul>
									</li>
                                    
								</ul>
                         	</td>
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
