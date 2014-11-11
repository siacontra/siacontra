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
                <link rel="stylesheet" type="text/css" href="css/jquery-ui-1.7.2.custom.css" />
                <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
                
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
						dateFormat: 'dd-mm-yy',
						firstDay: 1,
						isRTL: false,
						showMonthAfterYear: false,
						yearSuffix: ''};
						$.datepicker.setDefaults($.datepicker.regional['es']);
					});    

        $(document).ready(function() {
           $("#datepicker").datepicker();
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
						function MOSTRAR(){
     				if (document.form1.otros.value=="si") {
 						document.form1.especificar.type = "text";
						document.getElementById('especifique').style.visibility="visible";
    				}else  if(document.form1.otros.value==""){
            			document.form1.especificar.type = "hidden";
						document.getElementById('especifique').style.visibility="hidden";
 						}
  
					 }
				function CheckTime(str) 
					{ 
						hora=str.value 
						if (hora=='') {return} 
						if (hora.length>8) {alert("Introdujo una cadena mayor a 8 caracteres");return} 
						if (hora.length!=8) {alert("Introducir HH:MM:SS");return} 
						a=hora.charAt(0) //<=2 
						b=hora.charAt(1) //<4 
						c=hora.charAt(2) //: 
						d=hora.charAt(3) //<=5 
						e=hora.charAt(5) //: 
						f=hora.charAt(6) //<=5 
						if ((a==2 && b>3) || (a>2)) {alert("El valor que introdujo en la Hora no corresponde, introduzca un digito entre 00 y 23");return} 
						if (d>5) {alert("El valor que introdujo en los minutos no corresponde, introduzca un digito entre 00 y 59");return} 
						if (f>5) {alert("El valor que introdujo en los segundos no corresponde");return} 
						if (c!=':' || e!=':') {alert("Introduzca el caracter ':' para separar la hora, los minutos y los segundos");return} 

}  
					 
				
			</script>
			</head>
            <?
			$result = mysql_query("select MAX(id_salida) from salida"); 
							$resultado = mysql_result ($result, 0); 
							$id_salida = $resultado; 
							$id_salida=$id_salida+1;
  				  				
			?>	
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
                        	<center>
                        	  <strong>
									  <bold ><strong>Listado Fichas de Vehiculos</strong></bold>
									  </center>
								</div>
								
                        <table border="0" align="center" width="495">
							<tr  style="font-size:10px; background:#069; color:#FFF;" align="center">
						<tr>
                        	<td colspan="4">
                            </td>
                            <td colspan="2" align="center" style="font-size:10px;"><strong>Reportes
                            </strong></td>
                        </tr>
                        <tr  style="font-size:10px;  color:#FFF;" bgcolor="#5A6672" align="center">
                        <td width="10" ><strong>Nº</strong></td>
                        	<td width="100"><strong>Cod Veh</strong></td>
								<td width="100"><strong>Placa</strong></td>
								<td width="140"><strong>Modelo</strong></td>
                                <td width="10"><strong>General</strong></td>
                                <td width="10"><strong>Fechas</strong></td>
								
							</tr>
							<?
							$dd=mysql_query("select * from automovil order by cod_veh");
							while($d=mysql_fetch_assoc($dd)){
								
								?>
								<form action="proceso_decimodificar.php?a=1" method="post" onSubmit="return valid(this)">
								<tr style="font-size:10px; background:#E8E8E8;" align="center" >
									<td>
								<?
								$j=1+$j;
								echo "
									$j</td>
									<td>$d[cod_veh] </td>
									<td>$d[placa]</td>
									<td>$d[modelo]</td>
									";
								 $cod_veh=$d[cod_veh];
									?>
                                    <td>
                                    	<a href="../proceso_controlpersonal.php?ci=<? echo"$cod_veh"?>"><img src="../images/reporte.png" width="16" height="16" border="0" title="Reporte"></a> 
                                    </td>
                                    <td>
                                       <a href="../paginas/pantallaporfecha444.php?cod_veh=<? echo"$cod_veh"?>"><img src="../images/calendar.png" width="16" height="16" border="0"  title="Reporte por fechas"></a>
                            
                                    </td>
                                   
                                   
                                    </form>
                                   
									<?
						echo "			
							</tr>
					";
					}
	    	?>
			
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
    }else 
	{
		?>
		
        <?
	}
?>
