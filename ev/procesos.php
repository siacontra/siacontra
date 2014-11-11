<?php

session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");


include("include/inc_jscalendar-1.0.php");

//session_start();


//echo '<link type="text/css" rel="stylesheet" href="js/tabpane/css/tab.webfx.css">';

echo '<link type="text/css" rel="stylesheet" href="css/tabpane.css">';
echo '<link type="text/css" rel="stylesheet" href="css/estilo_contenido_ventana.css">';
echo '<link type="text/css" rel="stylesheet" href="css/menu_desplegable_h.css">';
echo '<link type="text/css" rel="stylesheet" href="../css/estilo.css">';
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
		<link href="../imagenes/icono.ico" type="image/x-icon" rel="shortcut icon" />
		<title>M&oacute;dulo de Eventos | <?=$_SESSION['NOMBRE_USUARIO_ACTUAL']?></title>
		
			<script language='JavaScript' type='text/JavaScript' src='../rh/fscript.js'></script>
			<script language='JavaScript' type="text/javascript" src='js/tabpane/js/tabpane.js'></script>
			
			
			<script language='JavaScript' type='text/JavaScript' src='../js/vEmergente.js'></script>    
	    	<script language='JavaScript' type='text/JavaScript' src='../js/AjaxRequest.js'></script>
	   	    <script language='JavaScript' type='text/JavaScript' src='../js/xCes.js'></script>
	   	    
	   	    <script language='JavaScript' type='text/JavaScript' src='js/dom.js'></script>   	    
   	        <script language='JavaScript' type="text/javascript" src='js/ventana.js'></script>
		    <script language='JavaScript' type="text/javascript" src='js/funciones_generales.js'></script>
		    <script language='JavaScript' type='text/JavaScript' src='js/funcionalidadCes.js'></script>
		    <script language='JavaScript' type="text/javascript" src='js/ventana_lugares_eventos_siaces.js'></script>
		    <script language='JavaScript' type="text/javascript" src='js/ventana_temas_evento.js'></script>
		    <script language='JavaScript' type="text/javascript" src='js/ventana_participantes_siaces.js'></script>
		    <script language='JavaScript' type="text/javascript" src='js/manipularDom.js'></script>
			
			<script type="text/javascript" src="js/xenabledrag.js"></script>
			<script type="text/javascript" src="js/xenabledrag_ventana.js"></script>
			<script type="text/javascript" src="js/xenabledrag2_ventana.js"></script>
		
			<script type="text/javascript" src="js/x.js"></script>
			<script type="text/javascript" src="js/xtrim.js"></script>
			<script type="text/javascript" src="js/xstyle.js"></script>
		
</head>

			<!-- INCLUSION DE LOS ARCHIVOS FUNCIONALIDADES CES -->
			<link rel="stylesheet" href="../css/vEmergente.css" type="text/css" />
		    <link rel="stylesheet" href="../css/estiloCes.css" type="text/css"  />



<body style="margin-top:0px; margin-left:0px;" onload="EventosNuevo();">
	
	
	<table border="0" cellpadding="0" cellspacing="0" width="90%" align="center">
	<tbody>

		<tr>
			<TD align="right">
				<br>
				<!--BUTTON class="BotonesVentana" onmouseover="IMG_NUEVO_FDCB.src='img/iconos/nuevo_con_foco.png';" onmouseout="IMG_NUEVO_FDCB.src='img/iconos/nuevo_activo.png'" onClick="recargar();">
					<IMG src="img/iconos/nuevo_activo.png" width="22" height="22" border="0" id="IMG_NUEVO_FDCB"><br>Nuevo
				</BUTTON>
				<BUTTON id="BOTON_GUARDAR_FDCB" class="BotonesVentana" onmouseover="IMG_GUARDAR_FDCB.src='img/iconos/guardar_con_foco.png';" onmouseout="IMG_GUARDAR_FDCB.src='img/iconos/guardar_activo.png'" onclick="EventosGuardarVerificar();">
					<IMG src="img/iconos/guardar_activo.png" width="22" height="22" border="0" id="IMG_GUARDAR_FDCB"><br>Guardar
				</BUTTON>
				<BUTTON id="BOTON_MODIFICAR_FDCB" class="BotonesVentana" onmouseover="IMG_MODIFICAR_FDCB.src='img/iconos/modificar_con_foco.png';" onmouseout="IMG_MODIFICAR_FDCB.src='img/iconos/modificar_activo.png'" onclick="EventosModificar();">
					<IMG src="img/iconos/modificar_activo.png" width="22" height="22" border="0"  id="IMG_MODIFICAR_FDCB"><br>Modificar
				</BUTTON>
				<BUTTON id="BOTON_ELIMINAR_FDCB" class="BotonesVentana" onmouseover="IMG_ELIMINAR_FDCB.src='img/iconos/eliminar_con_foco.png';" onmouseout="IMG_ELIMINAR_FDCB.src='img/iconos/eliminar_activo.png'" onclick="EventosEliminar();">
					<IMG src="img/iconos/eliminar_activo.png" width="22" height="22" border="0"  id="IMG_ELIMINAR_FDCB"><br>Eliminar
				</BUTTON>
				<BUTTON id="BOTON_IMPRIMIR_FDCB" class="BotonesVentana" onMouseOver="IMG_IMPRIMIR_CERT.src='img/iconos/visualizar_con_foco.png';" onMouseOut="IMG_IMPRIMIR_CERT.src='img/iconos/visualizar_activo.png'" onClick="imprimirRecibosPago();">
                    <IMG src="img/iconos/visualizar_activo.png" width="22" height="22" border="0" id="IMG_IMPRIMIR_CERT"><br>Certificados
                </BUTTON-->	
        
			
			<input type="button" value="Nuevo" onClick="recargar();"></input>	
			<input type="button" value="Guardar" onclick="EventosGuardarVerificar();"></input>
			<input type="button" value="Modificar" onClick="EventosModificar();"></input>	
			<input type="button" value="Eliminar" onclick="EventosEliminar();"></input>
			<input type="button" value="Certificados" onclick="imprimirRecibosPago();"></input>
			
					
			</TD>
		</tr>

		<tr>	
		<td valign="top">
		<br>
	
	
	
	<div class="tab-pane" id="tab-pane-1">
		
		<script type="text/javascript">
			var tabPane1 = new WebFXTabPane(document.getElementById( "tab-pane-1" ));
		</script>

			<!-- ******************** ******************** Primera Pestaña ****************** *********************-->
		<div class="tab-page"  id="tab-page-1" style="height : 100%;">
				<h2 class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Entrada de datos&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h2>
				   
			    <DIV id="MSG_EV" class="MensajesPestanas">&nbsp;</DIV><br>
				<fieldset>
				<legend><b>DATOS DEL EVENTO: </b></legend>
				<FORM id="FORMULARIO_EVENTOS" name="FORMULARIO_EVENTOS">
				
				<table cellspacing='5px' align="center" border="0" width="100%">
		<tbody>		
		

	<!-- ******************** ******************** DATOS DEL EVENTO ****************** *********************-->		
			<tr>
				<td class='TitulosCampos'>Nombre del evento:</td>
				<td class=''><TEXTAREA id=NOMBRE_EVENTO cols="46" rows="3"></TEXTAREA></td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><INPUT id='COD_EVENTO_SIACES' type="hidden" value="" size='2'><INPUT id='NUM_CERTIFICADO_PONENTE' type="hidden" value="" size='4'><INPUT id='NUM_CERTIFICADO_PONENTE2' type="hidden" value="" size='4'>
				
				<td class='TitulosCampos'>Fecha inicio:</td>
				<td>
				<INPUT id='FECHA_APERTURA_FDCB' type='text' readonly="true" size='10' maxlength='10'  ondblclick="showCalendar('FECHA_APERTURA_FDCB','%d/%m/%Y');"><IMG id="IMG_FECHA_APERTURA_FDCB" class='BotonesParaCampos' src='img/iconos/calendario_activo.png' onmouseover="src='img/iconos/calendario_con_foco.png';" onmouseout="src='img/iconos/calendario_activo.png'" width='20' height='20'>
				&nbsp;&nbsp;&nbsp;
				Fecha culminación:
				<INPUT id='FECHA_CULMINACION_FDCB' type='text' readonly="true" size='10' maxlength='10' ondblclick="showCalendar('FECHA_CULMINACION_FDCB','%d/%m/%Y');"><IMG id="IMG_FECHA_CULMINACION_FDCB" class='BotonesParaCampos' src='img/iconos/calendario_activo.png' onmouseover="src='img/iconos/calendario_con_foco.png';" onmouseout="src='img/iconos/calendario_activo.png'" width='20' height='20'>
				</td>					
			</tr>

			<tr>
				<td class='TitulosCampos'>					
					Lugar:<br /><br /><br />
					Tipo de Evento:
				</td>
				<td class='' valign="middle" class='TitulosCampos'>					
					<SELECT id="LUGAR_EVENTO_2" class=''></SELECT><IMG id="IMG_LUGAR_EVENTO_2" alt="AGREGAR LUGAR" class='BotonesParaCampos' src='img/iconos/agregar_activo.png' onmouseover="src='img/iconos/agregar_con_foco.png';" onmouseout="src='img/iconos/agregar_activo.png'" width='20' height='20' onClick="Form_DEFINICIONES_TIPOS_DE_CUENTA__Nuevo();">
					<br /><br /><br />
					<SELECT id="TIPO_EVENTO" class=''></SELECT>&nbsp;&nbsp;Cantidad de Horas: <INPUT id='HORAS_EVENTO' type="text" value="" size='5' onkeypress="return soloNum(event)">
				</td>
									

				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

				<td class='tagForm'>Temas:</td>
				<td class=''><SELECT multiple="false" class="TextoCampoInput" style="height : 100%; width : 85%;" id="SELECT_EP_FILM"></SELECT>
				<!--td class=''><TEXTAREA class='TextoCampoInput' id=TEMA_EVENTO cols="40" rows="2"></TEXTAREA-->					
				<IMG id="IMG_TEMA_EVENTO_2" class='BotonesParaCampos' src='img/iconos/agregar_activo.png' onmouseover="src='img/iconos/agregar_con_foco.png';" onmouseout="src='img/iconos/agregar_activo.png'" width='20' height='20' onClick="Form_DEFINICIONES_TEMAS__Nuevo();">	
				<IMG id="IMG_TEMA_EVENTO_AGREGAR" align="center" class='BotonesParaCampos' src='img/iconos/aceptar_activo.png' onmouseover="src='img/iconos/aceptar_con_foco.png';" onmouseout="src='img/iconos/aceptar_activo.png'" width='20' height='20' onClick="Form_IMPRIMIR_TEMAS__Visualizar();">				
				</td>

			</tr>

			<tr>
				<td class='TitulosCampos'>Descripción del evento:</td>
				<td class=''><TEXTAREA class='TextoCampoInputObligatorios' id=DESCRIPCION_EVENTO cols="60" rows="5"></TEXTAREA></td>
				
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

				<td valign="middle" class='TitulosCampos'></br></br>Temas agregados: </br></br></br> Certificados:</td>
				<td valign="middle" ><TEXTAREA class='TextoCampoInputObligatorios' id="TEMA_EVENTO" cols="56" rows="6" readonly="true"></TEXTAREA><input type="hidden" size="8" id="temasOcultos"></br>	
					
				<b>Participantes:</b>
				<INPUT id="ESTADO_FDCB" name="ESTADO_CERTIFICADO" type="radio" checked value="true">SI&nbsp;
				<INPUT id="ESTADO_FDCB" name="ESTADO_CERTIFICADO" type="radio" value="false">NO		
					
					
					
				&nbsp;&nbsp;| | &nbsp;&nbsp;<b>Ponentes:</b>
				<INPUT id="ESTADO_FDCB1" name="ESTADO_CERTIFICADO1" type="radio" value="true">SI&nbsp;
				<INPUT id="ESTADO_FDCB1" name="ESTADO_CERTIFICADO1" type="radio" checked value="false">NO		
						
										
			</tr>
												

			<tr>
				<td class='TitulosCampos'>Hora de Entrada:</td>
				<td class='TextCampos'>

					<select id='sel_hora_ini' name='sel_hora_ini'> 
                				        <option value='01'>01</option>
                					<option value='02'>02</option>
                					<option value='03'>03</option>
                					<option value='04'>04</option>
                					<option value='05'>05</option>
                					<option value='06'>06</option>
                					<option value='07'>07</option>
                					<option value='08' selected='selected'>08</option>
                					<option value='09'>09</option>
                					<option value='10'>10</option>
                					<option value='11'>11</option>
                					<option value='12'>12</option>
					</select>:
                			<select id='sel_minutos_ini' > 
                					<option value='00' selected='selected'>00</option>
                					<option value='10'>10</option>
                					<option value='20'>20</option>
                					<option value='30'>30</option>
                					<option value='40'>40</option>
                					<option value='50'>50</option>                		               	
                			</select>-
                			<select id='sel_turno_ini' > 
                					<option value='AM' selected='selected'>AM</option>
                					<option value='PM'>PM</option>
                			</select> 

				</td>
				
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				
				<td class='TitulosCampos'>Ponente Nº 1:</td>
				<td class='TextCampos'>
				<INPUT id='CED_PONENTE_ODC' class='TextoCampoInputDesactivado' type='text' size='10' maxlength='15' value="" readonly="true"><INPUT id='NOMBRE_PONENTE_ODC' class='TextoCampoInputDesactivado' readonly="true" type='text' size='38' value=""><IMG id="IMG_BUSCAR_PONENTE_ODC" class='BotonesParaCampos' src='img/iconos/buscar_activo.png' onmouseover="src='img/iconos/buscar_con_foco.png';" onmouseout="src='img/iconos/buscar_activo.png'" width='20' height='20'>
				<IMG id="IMG_CERTIFICADO_PONENTE" class='BotonesParaCampos' src='img/iconos/modificar_activo.png' onmouseover="src='img/iconos/modificar_con_foco.png';" onmouseout="src='img/iconos/modificar_activo.png'" width='20' height='20' onclick="imprimirRecibosPago2();">
				<INPUT id='COD_PONENTE_ODC' type="hidden" value="" size='2'>
				</td>

			</tr>

			<tr>
				<td class='TitulosCampos'>Hora de Salida:</td>
				<td class='TextCampos'>

					<select id='sel_hora_cul'> 
                				        <option value='01'>01</option>
                					<option value='02'>02</option>
                					<option value='03'>03</option>
                					<option value='04'>04</option>
                					<option value='05'>05</option>
                					<option value='06'>06</option>
                					<option value='07'>07</option>
                					<option value='08' selected='selected'>08</option>
                					<option value='09'>09</option>
                					<option value='10'>10</option>
                					<option value='11'>11</option>
                					<option value='12'>12</option>
					</select>:
                			<select id='sel_minutos_cul' > 
                					<option value='00' selected='selected'>00</option>
                					<option value='10'>10</option>
                					<option value='20'>20</option>
                					<option value='30'>30</option>
                					<option value='40'>40</option>
                					<option value='50'>50</option>                		               	
                			</select>-
                			<select id='sel_turno_cul' > 
                					<option value='AM' selected='selected'>AM</option>
                					<option value='PM'>PM</option>
                			</select> 

				</td>
				
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

				<td class='TitulosCampos'>Ponente Nº 2:</td>
				<td class='TextCampos'>
				<INPUT id='CED_PONENTE2_ODC' class='TextoCampoInputDesactivado' type='text' size='10' maxlength='15' value="" readonly="true"><INPUT id='NOMBRE_PONENTE2_ODC' class='TextoCampoInputDesactivado' readonly="true" type='text' size='38' value=""><IMG id="IMG_BUSCAR_PONENTE2_ODC" class='BotonesParaCampos' src='img/iconos/buscar_activo.png' onmouseover="src='img/iconos/buscar_con_foco.png';" onmouseout="src='img/iconos/buscar_activo.png'" width='20' height='20'>
				<IMG id="IMG_CERTIFICADO_PONENTE2" class='BotonesParaCampos' src='img/iconos/modificar_activo.png' onmouseover="src='img/iconos/modificar_con_foco.png';" onmouseout="src='img/iconos/modificar_activo.png'" width='20' height='20' onclick="imprimirRecibosPago22();">
				<INPUT id='COD_PONENTE2_ODC' type="hidden" value="" size='2'>
				</td>
			</tr>

			</tbody>
			</table>
	</fieldset>
	<br/>


	<fieldset>
	<legend><b>DATOS DE LOS PARTICIPANTES: </b></legend>

	<!-- ******************** ******************** DATOS DE LOS PARTICIPANTES ****************** *********************-->	

	<DIV style="height : 3px;"></DIV>
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tbody>
			<tr class="CabeceraTablaEstilo">
				<!--td width="5%">N°</td-->
				<td width="15%">C&Eacute;DULA</td>
				<td width="40%">NOMBRE Y APELLIDO</td>
				<td width="15%">CULMINÓ</td>
				<td width="15%">RECIBIÓ CERTIFICADO</td>
				<td width="15%">N° CERTIFICADO</td>				
			</tr>
		</tbody>
		</table>


	<DIV class="AreaTablaListado" style="height : 160px;">
		<table id="TABLA_LISTA_PARTICIPANTES_FRDB" border="0" cellspacing="0" cellpadding="0" width="100%">
		</table>
	</DIV>

	<table border="0" cellpadding="4" cellspacing="0" width="100%">
		<tbody>
		
			
			<tr class="CabeceraTablaEstilo">
				<td align="left">
				<!--BUTTON id="BOTON_AGREGAR_FRDB" class="BotonesParaCampos" style="font-size : 14px; vertical-align : top;" onmouseover="IMG_AGREGAR_FRDB.src='img/iconos/agregar_con_foco.png';" onmouseout="IMG_AGREGAR_FRDB.src='img/iconos/agregar_activo.png'" type="BUTTON" onclick="Form_LISTA_PARTICIPANTES__Abrir('COD_PARTICIPANTES_AGREGAR_FRDB','CEDULA_PARTICIPANTES_AGREGAR_FRDB','NOMBRE_PARTICIPANTES_AGREGAR_FRDB','Form_PARTICIPANTES__AgregarArticuloTabla();');Form_LISTA_PARTICIPANTES__MensajeCargando();"><IMG id="IMG_AGREGAR_FRDB" src='img/iconos/agregar_activo.png' width='18' height='18' style="vertical-align : middle;">&nbsp;Agregar
				</BUTTON>

				<BUTTON id="BOTON_QUITAR_FRDB" class="BotonesParaCampos" style="font-size : 14px; vertical-align : top;" onmouseover="IMG_QUITAR_FRDB.src='img/iconos/quitar_con_foco.png';" onmouseout="IMG_QUITAR_FRDB.src='img/iconos/quitar_activo.png'" type="BUTTON" onclick="Form_PARTICIPANTES__QuitarArticuloTabla();"><IMG id="IMG_QUITAR_FRDB" src='img/iconos/quitar_activo.png' width='18' height='18' style="vertical-align : middle;">&nbsp;Quitar&nbsp;&nbsp;
				</BUTTON-->
				
					
				<input id="BOTON_AGREGAR_FRDB" type="button" value="Agregar" onclick="Form_LISTA_PARTICIPANTES__Abrir('COD_PARTICIPANTES_AGREGAR_FRDB','CEDULA_PARTICIPANTES_AGREGAR_FRDB','NOMBRE_PARTICIPANTES_AGREGAR_FRDB','Form_PARTICIPANTES__AgregarArticuloTabla();')"></input>

				
				<input id="BOTON_QUITAR_FRDB" type="button" value="Quitar" onclick="Form_PARTICIPANTES__QuitarArticuloTabla();"></input>
				
				</td>

				<td align="right"> Número de participantes: </td>
				<td id="numparticipantes" align="left"></td>
			</tr>


		</tbody>
	</table>

			<INPUT type="hidden" value="" id="COD_PARTICIPANTES_AGREGAR_FRDB">
			<INPUT type="hidden" value="" id="CEDULA_PARTICIPANTES_AGREGAR_FRDB">
			<INPUT type="hidden" value="" id="NOMBRE_PARTICIPANTES_AGREGAR_FRDB">

		<!-- Boton Buscar --->

	<!--DIV class='TitulosCampos' style="text-align : center;">Buscar&nbsp;</DIV-->
	<INPUT id="LISTADO_BUSCAR_PARTICIPANTES" class='TextoCampoInput' type="hidden" value="" size="50" onkeyup="Form_PARTICIPANTES__MostrarTablaArticulos();">

	<!--BUTTON class="BotonesParaCampos" style="font-size : 14px; vertical-align : top;" onmouseover="IMG_LIMPIAR_FAP.src='img/iconos/limpiar_con_foco.png';" onmouseout="IMG_LIMPIAR_FAP.src='img/iconos/limpiar_activo.png'" onclick="LimpiarInputTextBuscarListadoParticipantes();"><IMG id="IMG_LIMPIAR_FAP" src='img/iconos/limpiar_activo.png' width='18' height='18' style="vertical-align : middle;">&nbsp;Limpiar
	</BUTTON-->
	<!--/DIV-->	
				

</FORM>
	</fieldset>
	<!-- ************************ ******************** fin primera pestaña ******************* *************************-->
	</div>

	<!-- ******************** ******************** Segunda Pestaña ****************** *********************-->
	<div class="tab-page" id="tab-page-2" style="height : 100%;">
				<h2 class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lista&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h2>
				<DIV id="MSG_FA_LISTADO" class="MensajesPestanas">&nbsp;</DIV>
	<br>
		<table border="0" cellspacing="0" cellpadding="2" width="100%">
		<tbody>
			<tr class="CabeceraTablaEstilo">
				<td width="5%" align="left">COD.</td>
				<td width="45%" align="left">NOMBRE DEL EVENTO</td>
				<td width="45%" align="left">LUGAR</td>
				<td width="5%" align="left">FECHA</td>
			</tr>
		</tbody>
		</table>

	<DIV class="AreaTablaListado" style="height : 70%;">
		<table id="TABLA_LISTA_FA" border="0" cellspacing="0" cellpadding="0" width="100%">
		</table>
	</DIV>
	<br /><br />
		
		<!-- Boton Buscar --->

	<DIV class='TitulosCampos' style="text-align : center;">Buscar&nbsp;
	<INPUT id="LISTADO_BUSCAR_FA" class='TextoCampoInput' type="text" value="" size="50" onkeyup="BuscarListado();">

	<!--BUTTON class="BotonesParaCampos" style="font-size : 14px; vertical-align : top;" onmouseover="IMG_LIMPIAR_FA.src='img/iconos/limpiar_con_foco.png';" onmouseout="IMG_LIMPIAR_FA.src='img/iconos/limpiar_activo.png'" onclick="LimpiarInputTextBuscarListado();"><IMG id="IMG_LIMPIAR_FA" src='img/iconos/limpiar_activo.png' width='18' height='18' style="vertical-align : middle;">&nbsp;Limpiar
	</BUTTON-->
	<input type="button" value="Limpiar" onclick="LimpiarInputTextBuscarListado();"></input>
	
	
	</DIV>							

		</div>
		
<!-- ************************************ fin segunda pestaña ****************** ********************* ***************-->
<!-- ************************************ Tercera Pestaña ****************** ********************* ***************-->		
		
		<div class="tab-page" id="tab-page-3" style="height : 100%;">
			<h2 class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reportes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h2>
		
		
		
		<DIV id="MSG_FA_LISTADO" class="MensajesPestanas">&nbsp;</DIV>
	<br>
		<table border="0" cellspacing="0" cellpadding="2" width="100%" align="center">
		<tbody>
			<tr class="CabeceraTablaEstilo">
				<td width="10%" align="center">N° DE REPORTE</td>
				<td width="40%" align="center">REPORTES</td>
				<td width="50%" align="left"></td>
				<!--td width="5%" align="left">FECHA</td-->
			</tr>



			<tr class="AreaTablaListado">
				<td width="10%" align="center" class='FilaEstilo'>1.-</td>
				<td width="40%" align="left" class='FilaEstilo'>

					<INPUT type="radio" class='TextoCampoInput' name="RADIO_IMPRIMIR_FEI" id="ID_RADIO_IMPRIMIR_EVENTO_MES" value="M" checked>
									<strong>Eventos por mes:</strong>
				</td>

				<td width="50%" align="left" class='FilaEstilo'>

					<SELECT id="SELECT_TIPO_MES" class='TextoCampoInput'>
						<OPTION value='1'>ENERO</OPTION>
						<OPTION value='2'>FEBRERO</OPTION>
						<OPTION value='3'>MARZO</OPTION>
						<OPTION value='4'>ABRIL</OPTION>
						<OPTION value='5'>MAYO</OPTION>
						<OPTION value='6'>JUNIO</OPTION>
						<OPTION value='7'>JULIO</OPTION>
						<OPTION value='8'>AGOSTO</OPTION>
						<OPTION value='9'>SEPTIEMBRE</OPTION>
						<OPTION value='10'>OCTUBRE</OPTION>
						<OPTION value='11'>NOVIEMBRE</OPTION>
						<OPTION value='12'>DICIEMBRE</OPTION>
					</SELECT>
				</td>
			</tr>



			<tr class="AreaTablaListado">
				<td width="10%" align="center" class='FilaEstilo'>2.-</td>
				<td width="40%" align="left" class='FilaEstilo'>

					<INPUT type="radio" class='TextoCampoInput' name="RADIO_IMPRIMIR_FEI" id="ID_RADIO_IMPRIMIR_EVENTO_TRIMESTRE" value="T">
									<strong>Eventos por trimestre:</strong>
				</td>

				<td width="50%" align="left" class='FilaEstilo'>

					<SELECT id="SELECT_TIPO_TRIMESTRE" class='TextoCampoInput'>
						<OPTION value='1'>I</OPTION>
						<OPTION value='2'>II</OPTION>
						<OPTION value='3'>III</OPTION>
						<OPTION value='4'>IV</OPTION>
					</SELECT>
				</td>
			</tr>


			<tr class="AreaTablaListado">
				<td width="10%" align="center" class='FilaEstilo'>3.-</td>
				<td width="40%" align="left" class='FilaEstilo'>

					<INPUT type="radio" class='TextoCampoInput' name="RADIO_IMPRIMIR_FEI" id="ID_RADIO_IMPRIMIR_EVENTO_PARTICIPANTES" value="P">
									<strong>Participantes por Eventos:</strong>
									
									
									
				</td>

				<td width="50%" align="left" class='FilaEstilo'>

					<SELECT id="PARTICIPANTES_POR_EVENTOS" class='TextoCampoInput' onchange='eventoSeleccionado(this.value);'>
					<option value="0">&lt;--Seleccione--&gt;</option>
					</SELECT>
				</td>
			</tr>



		</tbody>
		</table>

	<br>
		<DIV align="center" style="position : absolute; text-align : center; top : 360px; width : 100%;">
		<!--BUTTON class="BotonesParaCampos" style="font-size : 14px; vertical-align : top;" onmouseover="IMG_IMPRIMIR_FPC.src='img/iconos/visualizar_con_foco.png';" onmouseout="IMG_IMPRIMIR_FPC.src='img/iconos/visualizar_activo.png'" onclick="Form_IMPRIMIR();">
		<IMG id="IMG_IMPRIMIR_FPC" src='img/iconos/visualizar_activo.png' width='22' height='22' style="vertical-align : middle;">&nbsp;Visualizar
		</BUTTON-->
		
		<input type="button" value="Visualizar" onclick="Form_IMPRIMIR();"></input>
		
		</DIV>
	<br>
		
		
		
		
		
		</div>
		

<!-- ************************************ fin tercera pestaña****************** ********************* ***************-->
	</div>	



	<script type="text/javascript">
		setupAllTabs();
	</script>


</td>
</td>

		</tr>
	</tbody>
</table>

</div>	


		<DIV id="AREA_VENTANAS" align="center">
			</DIV>
			
		

			
		

</body>

</html>
