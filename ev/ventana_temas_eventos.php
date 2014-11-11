<?php
echo '<link type="text/css" rel="stylesheet" href="css/tabpane.css">';
echo '<link type="text/css" rel="stylesheet" href="css/estilo_contenido_ventana.css">';
echo '<link type="text/css" rel="stylesheet" href="css/menu_desplegable_h.css">';
echo '<link type="text/css" rel="stylesheet" href="../css/estilo.css">';
?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <script language='JavaScript' type='text/JavaScript' src='../js/vEmergente.js'></script>    
    <script language='JavaScript' type='text/JavaScript' src='../js/AjaxRequest.js'></script>
    <script language='JavaScript' type='text/JavaScript' src='../js/xCes.js'></script>
    
    <!-- <script language='JavaScript' type='text/JavaScript' src='../js/comun.js'></script> -->
    
    <script language='JavaScript' type='text/JavaScript' src='js/dom.js'></script>
    <script language='JavaScript' type='text/JavaScript' src='js/funcionalidadCes.js'></script>
    <script language='JavaScript' type="text/javascript" src='js/tabpane.js'></script>
    <script language='JavaScript' type="text/javascript" src='js/ventana.js'></script>
    <script language='JavaScript' type="text/javascript" src='js/funciones_generales.js'></script>
    <script language='JavaScript' type="text/javascript" src='js/ventana_temas_evento.js'></script>
    <!--script language='JavaScript' type="text/javascript" src='js/ventana_lugares_eventos_siaces.js'></script>
    <script language='JavaScript' type="text/javascript" src='js/ventana_participantes_siaces.js'></script-->
    <script language='JavaScript' type="text/javascript" src='js/manipularDom.js'></script>
	
	<script type="text/javascript" src="js/xenabledrag.js"></script>
	<script type="text/javascript" src="js/xenabledrag_ventana.js"></script>
	<script type="text/javascript" src="js/xenabledrag2_ventana.js"></script>

	<script type="text/javascript" src="js/x.js"></script>
	<script type="text/javascript" src="js/xtrim.js"></script>
	<script type="text/javascript" src="js/xstyle.js"></script>

</head>	


    <!--*********************************************** -->
<body style="margin-top:0px; margin-left:0px;" onload="">
	
	

<table border="0" cellpadding="0" cellspacing="0" width="90%" align="center">
	<tbody>

		<tr>
			<TD align="right">
				<br>
				<!--BUTTON class="BotonesVentana" onmouseover="IMG_NUEVO_FCG.src='img/iconos/nuevo_con_foco.png';" onmouseout="IMG_NUEVO_FCG.src='img/iconos/nuevo_activo.png'" onClick="Form_DEFINICIONES_TEMAS__Nuevo();">
					<IMG src="img/iconos/nuevo_activo.png" width="22" height="22" border="0" id="IMG_NUEVO_FCG"><br>Nuevo
				</BUTTON>
				<BUTTON id="BOTON_GUARDAR_FCG" class="BotonesVentana" onmouseover="IMG_GUARDAR_FCG.src='img/iconos/guardar_con_foco.png';" onmouseout="IMG_GUARDAR_FCG.src='img/iconos/guardar_activo.png'" onclick="Form_TEMA__GuardarVerificar();">
					<IMG src="img/iconos/guardar_activo.png" width="22" height="22" border="0" id="IMG_GUARDAR_FCG"><br>Guardar
				</BUTTON>
				<BUTTON id="BOTON_MODIFICAR_FCG" class="BotonesVentana" onmouseover="IMG_MODIFICAR_FCG.src='img/iconos/modificar_con_foco.png';" onmouseout="IMG_MODIFICAR_FCG.src='img/iconos/modificar_activo.png'" onclick="Form_TEMA__Modificar();">
					<IMG src="img/iconos/modificar_activo.png" width="22" height="22" border="0"  id="IMG_MODIFICAR_FCG"><br>Modificar
				</BUTTON>
				<BUTTON id="BOTON_ELIMINAR_FCG" class="BotonesVentana" onmouseover="IMG_ELIMINAR_FCG.src='img/iconos/eliminar_con_foco.png';" onmouseout="IMG_ELIMINAR_FCG.src='img/iconos/eliminar_activo.png'" onclick="Form_TEMA__Eliminar();">
					<IMG src="img/iconos/eliminar_activo.png" width="22" height="22" border="0"  id="IMG_ELIMINAR_FCG"><br>Eliminar
				</BUTTON-->	
				
			<input type="button" value="Nuevo" onClick="Form_DEFINICIONES_TEMAS__Nuevo();"></input>	
			<input type="button" value="Guardar" onclick="Form_TEMA__GuardarVerificar();"></input>
			<input type="button" value="Modificar" onClick="Form_TEMA__Modificar();"></input>	
			<input type="button" value="Eliminar" onclick="Form_TEMA__Eliminar();"></input>
				
				
						
			</TD>
		</tr>
		<tr>
			<td valign="top">
				<br>
				<div class="tab-pane" id="TABPANE_TEMA">
					<!-- ******************** ******************** Primera Pestaña ****************** *********************-->
					<div class="tab-page"  style="height : 220px;">
						<h2 class="tab">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Entrada de datos&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</h2>						
						<DIV id="MSG_TEMA" class="MensajesPestanas">&nbsp;</DIV>
						<br>

						<FORM id="FORMULARIO_TEMA">
							<table cellspacing='5px' align="center">
							<tbody>
								<tr>
									<td class='TitulosCampos'>Tema:</td>
									<td class='TextCampos'>
										<INPUT id='DENOMINACION_TEMA' class='TextoCampoInput' type='text' size='60' onkeypress="return noComillas(event);">
									</td>
								</tr>
							</tbody>
							</table>
						</FORM>
					</div>					
					<!-- ************************ ******************** fin ******************* *************************-->		
					<!-- ******************** ******************** Segunda Pestaña ****************** *********************-->
					<div class="tab-page" style="height : 220px;">
						<h2 class="tab">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lista&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</h2>						
						<DIV id="MSG_TEMA_LISTADO" class="MensajesPestanas">&nbsp;</DIV>
						<br>
						<table border="0" cellspacing="0" cellpadding="0" width="100%">
							<tbody>
								<tr class="CabeceraTablaEstilo">	
									<td width="5%">CÓD</td>						
									<td width="95%">LUGAR</td>
								</tr>
							</tbody>
						</table>
						
						
						<DIV class="AreaTablaListado" style="height : 50%;">
							<table id="TABLA_LISTA_TEMA" border="0" cellspacing="0" cellpadding="0" width="100%">
							</table>
						</DIV>
						
						
						
						<br>
						<DIV class='TitulosCampos' style="text-align : center;">
							Buscar&nbsp;
							<INPUT id="LISTADO_BUSCAR_TEMA" class='TextoCampoInput' type="text" value="" size="40" onkeyup="Form_TEMA__BuscarListado();">
							<!--BUTTON class="BotonesParaCampos" style="font-size : 14px; vertical-align : top;" onmouseover="IMG_LIMPIAR_FDTDC.src='img/iconos/limpiar_con_foco.png';" onmouseout="IMG_LIMPIAR_FDTDC.src='img/iconos/limpiar_activo.png'" onclick="Form_TEMA__LimpiarInputTextBuscarListado();">
								<IMG id="IMG_LIMPIAR_FDTDC" src='img/iconos/limpiar_activo.png' width='18' height='18' style="vertical-align : middle;">&nbsp;Limpiar
							</BUTTON-->		
							
							<input type="button" value="Limpiar" onclick="Form_TEMA__LimpiarInputTextBuscarListado();"></input>
							
							
												
						</DIV>
					</div>					
					<!-- ************************************ fin ****************** ********************* ***************-->
				</div>
			</td>
		</tr>
	</tbody>
</table>
</body>
</html>
	
	
	
	
