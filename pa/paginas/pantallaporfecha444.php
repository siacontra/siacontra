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
	<body>
			<div id="container">
				<div id="banner">
					<img class="displayed" src="../../rendicion/images/banner.jpg" border="0" width="100%" height="98%"/>
				</div>
				<div id="fondomenu">
					<div id="liston">	
						  <?	if ($tipo=='USU') {
							include('../../rendicion/paginas/menureg.php');
					  }
						
						?>
					</div> 
				</div>
				<div id="right"><br>Bienvenido:
                     <center><br><strong>
                     
                       <?	if ($tipo=='USU') {
						   
					 	$aa=mysql_query("select codigo from usuarios where usuario_nombre='".$usuario_nombre."'");
		 				while($a=mysql_fetch_assoc($aa)){
						$rif=$a[codigo];	
		    			echo"<br><strong>Rif:</strong> $rif";
						}
					 $bb=mysql_query("select nombre from consejocomunal where rif='".$rif."'");
		 				while($b=mysql_fetch_assoc($bb)){
		    			echo"<br><strong>Consejo Comunal:</strong><br> $b[nombre]";
							}
					 ?>
						   </strong><br /></center><br>
					 <center><img class="displayed" src="../../rendicion/images/usuario.png" border="0" width="62" height="60"/></center>	
					  
                      <? }
						?>
                        <?	if ($tipo=='ADMIN') {
						   ?>
                           <center><strong><?=$_SESSION["USUARIO_ACTUAL"]?></strong><br /></center><br>
					 <center><img class="displayed" src="../../rendicion/images/administrador.png" border="0" width="62" height="60"/></center>
                     <center><strong>ADMINISTRADOR</strong></center>	
					  
                      <? }
						?>
            		<br><center><a href="../../rendicion/paginas/logout.php">Cerrar Sesión</a></center> 	
				</div>
				<div id="Contenido">
				  <div align="justify" >
				    	<blockquote>
                        <br><br><br><br><br>
                        <form action="../../rendicion/procesos/proceso_porfechausu.php?a=1" method="post">
<table align="center" aling="center" bgcolor="#F8F8F8" font size="2">
										
                                        <tr>
											<td width="58" colspan="4" align="center" style="font-size:15px;"><strong>Reporte por Fechas</strong><br></td>
			</tr>
                                        <tr>
											<td width="58" height="37" colspan="4" align="center" style="font-size:13px;"></td>
			</tr>		
<tr style="font-size:11px;">
											<td width="58" ><label><strong>Desde:</strong></label>											  <br /></td>
											<td width="144"><input type="text" name="desde" maxlength="10" id="datep" /><br /></td>
										
											<td width="55"><label><strong>Hasta:</strong></label>											  <br /></td>
											<td width="153"><input type="text" name="hasta" maxlength="10" id="datepicker"/><br /></td>
                                            <input type="hidden" name="cod_veh" value="<? echo "$cod_veh" ?>" />
										</tr>
                                        <tr>
											<td align="center" colspan="4"><input type="submit" name="enviar" value="Enviar" />
											  <input type="reset" value="Borrar" /></td>
										</tr>
									</table>
								</form>	 </blockquote>
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