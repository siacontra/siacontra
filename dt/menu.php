<?php
include("../lib/fphp.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
		<!-- Deluxe Menu -->
    <script type="text/javascript">var dmWorkPath = "<?php echo $_PARAMETRO["PATHSIA"]?>rh/data.files/";</script>
    <script type="text/javascript" src="<?php echo$_PARAMETRO["PATHSIA"]?>rh/data.files/dmenu.js"></script>
    <!-- (c) 2007, by Deluxe-Menu.com -->
</head>
<body style="background:url(../imagenes/fondo_menu.jpg)">

<input type="hidden" name="menu" id="menu" value="<?php echo $_SESSION["PERMISOS_ACTUAL"]?>" />
<input type="hidden" name="admin" id="admin" value="<?php echo $_SESSION["ADMINISTRADOR_ACTUAL"]?>" />
<table width="100%">
	<tr>
    	<td>
         <script type="text/javascript">
			/*
               Deluxe Menu Data File
               Created by Deluxe Tuner v3.2
               http://deluxe-menu.com
            */
            // -- Deluxe Tuner Style Names
            var itemStylesNames=["Top Item",];
            var menuStylesNames=["Top Menu",];
            // -- End of Deluxe Tuner Style Names
            
            console.log(dmWorkPath);
            //--- Common
            var isHorizontal=1;
            var smColumns=1;
            var smOrientation=0;
            var dmRTL=0;
            var pressedItem=-2;
            var itemCursor="default";
            var itemTarget="_self";
            var statusString="link";
            var blankImage="<?php echo $_PARAMETRO["PATHSIA"]?>rh/data.files/blank.gif";
            var pathPrefix_img="";
            var pathPrefix_link="";
            
            //--- Dimensions
            var menuWidth="";
            var menuHeight="23px";
            var smWidth="";
            var smHeight="";
            
            //--- Positioning
            var absolutePos=0;
            var posX="10px";
            var posY="10px";
            var topDX=0;
            var topDY=1;
            var DX=-5;
            var DY=0;
            var subMenuAlign="center";
            var subMenuVAlign="top";
            
            //--- Font
            var fontStyle=["normal 10px Tahoma","normal 10px Tahoma"];
            var fontColor=["#FFFFFF","#F5FDF4"];
            var fontDecoration=["none","none"];
            var fontColorDisabled="#585858";
            
            //--- Appearance
            var menuBackColor="#000000";
            var menuBackImage="";
            var menuBackRepeat="repeat";
            var menuBorderColor="#727272";
            var menuBorderWidth=1;
            var menuBorderStyle="ridge";
            
            //--- Item Appearance
            var itemBackColor=["#000000","#8F0303"];
            var itemBackImage=["",""];
            var beforeItemImage=["",""];
            var afterItemImage=["",""];
            var beforeItemImageW="";
            var afterItemImageW="";
            var beforeItemImageH="";
            var afterItemImageH="";
            var itemBorderWidth=0;
            var itemBorderColor=["#FA1D1D","#DD0404"];
            var itemBorderStyle=["solid","groove"];
            var itemSpacing=2;
            var itemPadding="3px";
            var itemAlignTop="center";
            var itemAlign="left";
            
            //--- Icons
            var iconTopWidth=16;
            var iconTopHeight=16;
            var iconWidth=16;
            var iconHeight=16;
            var arrowWidth=7;
            var arrowHeight=7;
            var arrowImageMain=["<?php echo $_PARAMETRO["PATHSIA"]?>rh/data.files/arrv_white.gif",""];
            var arrowWidthSub=0;
            var arrowHeightSub=0;
            var arrowImageSub=["<?php echo $_PARAMETRO["PATHSIA"]?>rh/data.files/arr_white.gif",""];
            
            //--- Separators
            var separatorImage="";
            var separatorWidth="100%";
            var separatorHeight="3px";
            var separatorAlignment="left";
            var separatorVImage="";
            var separatorVWidth="3px";
            var separatorVHeight="100%";
            var separatorPadding="0px";
            
            //--- Floatable Menu
            var floatable=0;
            var floatIterations=6;
            var floatableX=1;
            var floatableY=1;
            var floatableDX=15;
            var floatableDY=15;
            
            //--- Movable Menu
            var movable=0;
            var moveWidth=12;
            var moveHeight=20;
            var moveColor="#DECA9A";
            var moveImage="";
            var moveCursor="move";
            var smMovable=0;
            var closeBtnW=15;
            var closeBtnH=15;
            var closeBtn="";
            
            //--- Transitional Effects & Filters
            var transparency="100";
            var transition=24;
            var transOptions="gradientSize=0.4, wipestyle=1, motion=forward";
            var transDuration=350;
            var transDuration2=200;
            var shadowLen=3;
            var shadowColor="#B1B1B1";
            var shadowTop=0;
            
            //--- CSS Support (CSS-based Menu)
            var cssStyle=0;
            var cssSubmenu="";
            var cssItem=["",""];
            var cssItemText=["",""];
            
            //--- Advanced
            var dmObjectsCheck=0;
            var saveNavigationPath=1;
            var showByClick=0;
            var noWrap=1;
            var smShowPause=200;
            var smHidePause=1000;
            var smSmartScroll=1;
            var topSmartScroll=0;
            var smHideOnClick=1;
            var dm_writeAll=1;
            var useIFRAME=0;
            var dmSearch=0;
            
            //--- AJAX-like Technology
            var dmAJAX=0;
            var dmAJAXCount=0;
            var ajaxReload=0;
            
            //--- Dynamic Menu
            var dynamic=0;
            
            //--- Keystrokes Support
            var keystrokes=0;
            var dm_focus=1;
            var dm_actKey=113;
            
            //--- Sound
            var onOverSnd="";
            var onClickSnd="";
            
            var itemStyles = [
                ["itemWidth=94px","itemHeight=21px","itemBackColor=transparent,transparent","itemBackImage=<?=$_PARAMETRO["PATHSIA"]?>rh/data.files/btn_black.gif,<?=$_PARAMETRO["PATHSIA"]?>rh/data.files/btn_black2.gif","itemBorderWidth=0","fontStyle='bold 10px Tahoma','bold 10px Tahoma'","fontColor=#FFFFFF,#FFFFFF"],
            ];
            var menuStyles = [
                ["menuBackColor=transparent","menuBorderWidth=0","itemSpacing=0","itemPadding=5px 6px 5px 6px","smOrientation=undefined"],
            ];
            
            var d = new Array();
            var admin=document.getElementById("admin").value;
            var opciones=document.getElementById("menu").value;
		
			opciones=opciones.split(";");
			for (i=0; i<opciones.length; i++) {
				var items=opciones[i].split(",");
				if (items[1]=="S") d[items[0]]=""; else d[items[0]]="_";
			}
			var _A = "RH";
			
			var menuItems = [
				["Servicio", , , , , , "0", , , , , ],
				
					["|HCM", , , , , , , , , , , ],
						["||Crear Solicitud", "<?=$_PARAMETRO["PATHSIA"]?>dt/servicioCrear.php.php?concepto=05-0014", , , , d['05-0014'], , , , , , ],
						["||Listar Solicitudes", "<?=$_PARAMETRO["PATHSIA"]?>dt/servicioListar.php?concepto=05-0015", , , , d['05-0015'], , , , , , ],
						["||Revisar Solicitudes", "<?=$_PARAMETRO["PATHSIA"]?>dt/servicioRevisar.php?concepto=05-0016", , , , d['05-0016'], , , , , , ],
						["||Aprobar Solicitudes", "<?=$_PARAMETRO["PATHSIA"]?>dt/servicioAprobar.php?concepto=05-0017", , , , d['05-0017'], , , , , , ],
					    ["||Planilla de Solicitud", "<?=$_PARAMETRO["PATHSIA"]?>dt/servicio_planilla.php?concepto=05-0015", , , , d['05-00015'], , , , , , ],
					
				
				["Reportes", , , , , , "0", , , , , ],
					["|Empleados", , , , , , , , , , , ],
						["||Empleados", "<?=$_PARAMETRO["PATHSIA"]?>rh/reportes_empleado.php?filtrar=DEFAULT&concepto=02-0004&_APLICACION="+_A, , , , d['02-0004'], , , , , , ],
						["||Carga Familiar", "<?=$_PARAMETRO["PATHSIA"]?>rh/reportes_carga_familiar.php?concepto=02-0001&_APLICACION="+_A, , , , d['02-0001'], , , , , , ],
						
						["||Constancias de Trabajo", "<?=$_PARAMETRO["PATHSIA"]?>rh/reportes_constancias.php?limit=0&filtrar=DEFAULT&concepto=02-0005&_APLICACION="+_A, , , , d['02-0005'], , , , , , ],
						["||-",, , , , , , , , , , ],
						["||-",, , , , , , , , , , ],
						["||Aniversarios", "<?=$_PARAMETRO["PATHSIA"]?>rh/reportes_aniversario.php?concepto=02-0003&_APLICACION="+_A, , , , d['02-0003'], , , , , , ],
						["||Cumpleaños", "<?=$_PARAMETRO["PATHSIA"]?>rh/reportes_cumpleanios.php?concepto=02-0002&_APLICACION="+_A, , , , d['02-0002'], , , , , , ],
["||Disponibilidad presupuestaria partidas 401, 407", "<?=$_PARAMETRO["PATHSIA"]?>rh/rh_disponibilidad.php?concepto=02-0015&_APLICACION="+_A, , , , d['02-0015'], , , , , , ],
						
					["|Bono de Alimentación", , , , , , , , , , , ],
						["||Control de Asistencias", "<?=$_PARAMETRO["PATHSIA"]?>rh/reportes_eventos_control.php?filtrar=DEFAULT&concepto=02-0006&_APLICACION="+_A, , , , d['02-0006'], , , , , , ],
						
						["||Resumen de Eventos", "<?=$_PARAMETRO["PATHSIA"]?>rh/gehen.php?anz=rh_eventos_resumen_reporte&filtrar=default&concepto=02-0011&_APLICACION="+_A, , , , d['02-0011'], , , , , , ],
						
					["|Vacaciones", , , , , , , , , , , ],
						["||Lista de Solicitudes", "<?=$_PARAMETRO["PATHSIA"]?>rh/gehen.php?anz=rh_vacaciones_lista_reporte&filtrar=default&concepto=02-0008&_APLICACION="+_A, , , , d['02-0008'], , , , , , ],
						["||Disfrute de Vacaciones", "<?=$_PARAMETRO["PATHSIA"]?>rh/gehen.php?anz=rh_vacaciones_disfrute_reporte&filtrar=default&concepto=02-0009&_APLICACION="+_A, , , , d['02-0009'], , , , , , ],
						
					["|Retenciones Judiciales", , , , , , , , , , , ],
						["||Retenciones Judiciales", "<?=$_PARAMETRO["PATHSIA"]?>rh/gehen.php?anz=rh_retenciones_judiciales_reporte&filtrar=default&concepto=02-0010&_APLICACION="+_A, , , , d['02-0010'], , , , , , ],
						
					["|Evaluación de Desempeño", , , , , , , , , , , ],
						["||Evaluación de Desempeño", "<?=$_PARAMETRO["PATHSIA"]?>rh/rh_evaluacion_desempenio_reporte.php?filtrar=default&concepto=02-0007&_APLICACION="+_A, , , , d['02-0007'], , , , , , ],

					["|HCM", , , , , , , , , , , ],
						["||Consumido x Funcionario", "<?=$_PARAMETRO["PATHSIA"]?>rh/hcm_consumo_partida_reporte.php?filtrar=default&concepto=02-0014", , , , d['02-0014'], , , , , , ],
						["||Consumido x Institución", "<?=$_PARAMETRO["PATHSIA"]?>rh/hcm_institucion_reporte.php?filtrar=default&concepto=02-0013", , , , d['02-0013'], , , , , , ],
						["||Consumo por Partidas", "<?=$_PARAMETRO["PATHSIA"]?>rh/hcm_consumido_x_partida.php?concepto=02-0012", , , , d['02-0012'], , , , , , ],
				        ["||Reporte de Empleados para HCM", "<?=$_PARAMETRO["PATHSIA"]?>rh/reportes_hcm.php?concepto=02-0001&_APLICACION="+_A, , , , d['02-0001'], , , , , , ],
					["|Útiles", , , , , , , , , , , ],
						["||Reporte Útiles General", "<?=$_PARAMETRO["PATHSIA"]?>rh/reporte_utiles_general.php?filtrar=default&concepto=02-0016", , , , d['02-0016'], , , , , , ],
						["||Reporte Útiles Detallado", "<?=$_PARAMETRO["PATHSIA"]?>rh/reporte_utiles_detallado.php?filtrar=default&concepto=02-0017", , , , d['02-0017'], , , , , , ],

					
				["Maestros", , , , , , "0", , , , , ],
					["|Del Sistema SIA", , , , , , , , , , , ],
						["||Propios del Sistema", , , , , , , , , , , ],
							["|||Aplicaciones", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=aplicaciones_lista&filtrar=default&concepto=03-0002&_APLICACION="+_A, , , , d['03-0002'], , , , , , ],
							["|||Par&aacute;metros", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=parametros_lista&filtrar=default&concepto=03-0003&_APLICACION="+_A, , , , d['03-0003'], , , , , , ],
						["||Relacionados a Personas", , , , , , , , , , , ],
							["|||Personas", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=personas_lista&filtrar=default&concepto=03-0001&_APLICACION="+_A, , , , d['03-0001'], , , , , , ],
							["|||Organismos", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=organismos_lista&filtrar=default&concepto=03-0010&_APLICACION="+_A, , , , d['03-0010'], , , , , , ],
							["|||Dependencias", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=dependencias_lista&filtrar=default&concepto=03-0011&_APLICACION="+_A, , , , d['03-0011'], , , , , , ],
						["||Relacionados a Contabilidad", , , , , , , , , , , ],
							["|||Plan de Cuentas", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=plan_cuentas_lista&filtrar=default&concepto=03-0043&_APLICACION="+_A, , , , d['03-0043'], , , , , , ],
							["|||Grupos de Centros de Costos", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=grupo_centro_costos_lista&filtrar=default&concepto=03-0044&_APLICACION="+_A, , , , d['03-0044'], , , , , , ],
							["|||Centros de Costos", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=centro_costos_lista&filtrar=default&concepto=03-0045&_APLICACION="+_A, , , , d['03-0045'], , , , , , ],
						["||Relacionados a Presupuesto", , , , , , , , , , , ],
							["|||Tipos de Cuenta", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=tipo_cuenta_lista&filtrar=default&concepto=03-0053&_APLICACION="+_A, , , , d['03-0053'], , , , , , ],
							["|||Clasificador Presupuestario", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=clasificador_presupuestario_lista&filtrar=default&concepto=03-0054&_APLICACION="+_A, , , , d['03-0054'], , , , , , ],
						["||Otros Maestros", , , , , , , , , , , ],
							["|||Paises", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=paises_lista&filtrar=default&concepto=03-0004&_APLICACION="+_A, , , , d['03-0004'], , , , , , ],
							["|||Estados", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=estados_lista&filtrar=default&concepto=03-0005&_APLICACION="+_A, , , , d['03-0005'], , , , , , ],
							["|||Municipios", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=municipios_lista&filtrar=default&concepto=03-0006&_APLICACION="+_A, , , , d['03-0006'], , , , , , ],
							["|||Ciudades", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=ciudades_lista&filtrar=default&concepto=03-0007", , , , d['03-0007'], , , , , , ],
							["|||Tipos de Pago", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=tipos_pago_lista&filtrar=default&concepto=03-0008", , , , d['03-0008'], , , , , , ],
							["|||Bancos", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=bancos_lista&filtrar=default&concepto=03-0009", , , , d['03-0009'], , , , , , ],
							["|||Unidad Tributaria", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=unidad_tributaria_lista&filtrar=default&concepto=03-0056&_APLICACION="+_A, , , , d['03-0056'], , , , , , ],
							
					["|Organizaci&oacute;n", , , , , , , , , , , ],
						["||Tipos de Cargo", "<?=$_PARAMETRO["PATHSIA"]?>rh/tiposcargo.php?concepto=03-0014&_APLICACION="+_A, , , , d['03-0014'], , , , , , ],
						["||Nivel de Tipos de Cargo", "<?=$_PARAMETRO["PATHSIA"]?>rh/nivelcargo.php?concepto=03-0015&_APLICACION="+_A, , , , d['03-0015'], , , , , , ],
						["||Grupo Ocupacional", "<?=$_PARAMETRO["PATHSIA"]?>rh/grupoocupacional.php?concepto=03-0012&_APLICACION="+_A, , , , d['03-0012'], , , , , , ],						
						["||Serie Ocupacional", "<?=$_PARAMETRO["PATHSIA"]?>rh/serieocupacional.php?concepto=03-0013&_APLICACION="+_A, , , , d['03-0013'], , , , , , ],
						["||Grado Salarial", "<?=$_PARAMETRO["PATHSIA"]?>rh/grado_salarial.php?concepto=03-0042&_APLICACION="+_A, , , , d['03-0042'], , , , , , ],
						["||Cargos", "<?=$_PARAMETRO["PATHSIA"]?>rh/cargos.php?concepto=03-0016&_APLICACION="+_A, , , , d['03-0016'], , , , , , ],
						
					["|Capacitaci&oacute;n", , , , , , , , , , , ],
						["||Grado de Instrucci&oacute;n", "<?=$_PARAMETRO["PATHSIA"]?>rh/ginstruccion.php?concepto=03-0017&_APLICACION="+_A, , , , d['03-0017'], , , , , , ],
						["||Profesiones", "<?=$_PARAMETRO["PATHSIA"]?>rh/profesiones.php?concepto=03-0018&_APLICACION="+_A, , , , d['03-0018'], , , , , , ],
						["||Centros de Estudios", "<?=$_PARAMETRO["PATHSIA"]?>rh/centroestudio.php?concepto=03-0019&_APLICACION="+_A, , , , d['03-0019'], , , , , , ],
						["||Cursos", "<?=$_PARAMETRO["PATHSIA"]?>rh/cursos.php?concepto=03-0020&_APLICACION="+_A, , , , d['03-0020'], , , , , , ],
						["||Idiomas", "<?=$_PARAMETRO["PATHSIA"]?>rh/idiomas.php?concepto=03-0021&_APLICACION="+_A, , , , d['03-0021'], , , , , , ],
						
					["|Relaciones Laborales", , , , , , , , , , , ],
						["||Tipos de Trabajador", "<?=$_PARAMETRO["PATHSIA"]?>rh/tipostrabajador.php?concepto=03-0022&_APLICACION="+_A, , , , d['03-0022'], , , , , , ],
						["||Tipos de N&oacute;mina", "<?=$_PARAMETRO["PATHSIA"]?>relaciones_laborales/gehen.php?anz=tipo_nomina_lista&filtrar=default&concepto=03-0023&_APLICACION="+_A, , , , d['03-0023'], , , , , , ],
						
						["||Perfiles de N&oacute;mina", "<?=$_PARAMETRO["PATHSIA"]?>rh/tiposperfil.php?concepto=03-0024&_APLICACION="+_A, , , , d['03-0024'], , , , , , ],
						["||Tipos de Contrato", "<?=$_PARAMETRO["PATHSIA"]?>rh/tiposcontrato.php?concepto=03-0025&_APLICACION="+_A, , , , d['03-0025'], , , , , , ],
						["||Formatos de Contrato", "<?=$_PARAMETRO["PATHSIA"]?>rh/formatoscontrato.php?concepto=03-0026&_APLICACION="+_A, , , , d['03-0026'], , , , , , ],
						["||Motivos de Cese", "<?=$_PARAMETRO["PATHSIA"]?>rh/motivoscese.php?concepto=03-0027&_APLICACION="+_A, , , , d['03-0027'], , , , , , ],
						["||Horario Laboral", "<?=$_PARAMETRO["PATHSIA"]?>relaciones_laborales/gehen.php?anz=horario_laboral_lista&filtrar=default&concepto=03-0057&_APLICACION="+_A, , , , d['03-0057'], , , , , , ],
						
					["|Clima Laboral", , , , , , , , , , , ],
						["||Preguntas", "<?=$_PARAMETRO["PATHSIA"]?>rh/preguntas.php?concepto=03-0032&_APLICACION="+_A, , , , d['03-0032'], , , , , , ],
						["||Plantillas", "<?=$_PARAMETRO["PATHSIA"]?>rh/plantillas.php?concepto=03-0033&_APLICACION="+_A, , , , d['03-0033'], , , , , , ],
						
					["|Gesti&oacute;n de Evaluaciones", , , , , , , , , , , ],
						["||Tipos de Evaluaci&oacute;n", "<?=$_PARAMETRO["PATHSIA"]?>rh/gehen.php?anz=rh_evaluacion_tipo_lista&lista=todos&filtrar=default&concepto=03-0035&_APLICACION="+_A, , , , d['03-0035'], , , , , , ],
						["||Evaluaciones", "<?=$_PARAMETRO["PATHSIA"]?>rh/gehen.php?anz=rh_evaluacion_lista&lista=todos&filtrar=default&concepto=03-0034&_APLICACION="+_A, , , , d['03-0034'], , , , , , ],
						["||Items o Preguntas", "<?=$_PARAMETRO["PATHSIA"]?>rh/gehen.php?anz=rh_evaluacion_items_lista&lista=todos&filtrar=default&concepto=03-0055&_APLICACION="+_A, , , , d['03-0055'], , , , , , ],
						
					["|Gesti&oacute;n de Competencias", , , , , , , , , , , ],
						["||Grupos de Competencias", "<?=$_PARAMETRO["PATHSIA"]?>rh/gehen.php?anz=rh_competencias_grupo_lista&lista=todos&filtrar=default&concepto=03-0036&_APLICACION="+_A, , , , d['03-0036'], , , , , , ],
						["||Competencias", "<?=$_PARAMETRO["PATHSIA"]?>rh/gehen.php?anz=rh_competencias_lista&lista=todos&filtrar=default&concepto=03-0038&_APLICACION="+_A, , , , d['03-0038'], , , , , , ],
						["||Plantilla de Competencias", "<?=$_PARAMETRO["PATHSIA"]?>rh/gehen.php?anz=rh_competencias_plantilla_lista&lista=todos&filtrar=default&concepto=03-0039&_APLICACION="+_A, , , , d['03-0039'], , , , , , ],
						["||Grados de Calificación General", "<?=$_PARAMETRO["PATHSIA"]?>rh/gehen.php?anz=rh_grados_calificacion_lista&lista=todos&filtrar=default&concepto=03-0052&_APLICACION="+_A, , , , d['03-0052'], , , , , , ],

					["|Beneficio", , , , , , , , , , , ],
						["||Asignación de Beneficio", "<?=$_PARAMETRO["PATHSIA"]?>rh/asignacion_beneficio.php?filtrar=default&concepto=03-0060", , , , d['03-0060'], , , , , , ],
						["||Asignación Global", "<?=$_PARAMETRO["PATHSIA"]?>rh/asignacion_global.php?filtrar=default&concepto=03-0061", , , , d['03-0061'], , , , , , ],					
					
					    ["||Medicos", "<?=$_PARAMETRO["PATHSIA"]?>rh/hcm_medicos.php?filtrar=default&concepto=03-0061", , , , d['03-0061'], , , , , , ],
					    ["||Inticiones Medicas", "<?=$_PARAMETRO["PATHSIA"]?>rh/hcm_instituciones.php?filtrar=default&concepto=03-0061", , , , d['03-0061'], , , , , , ],																
					
					["|Útiles", , , , , , , , , , , ],
						["||Beneficio Útiles", "<?=$_PARAMETRO["PATHSIA"]?>rh/beneficio_utiles.php?filtrar=default&concepto=03-0062", , , , d['03-0062'], , , , , , ],

					["|Otros", , , , , , , , , , , ],
						["||Miscel&aacute;neos", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=miscelaneos_lista&filtrar=default&concepto=03-0040&_APLICACION="+_A, , , , d['03-0040'], , , , , , ],
						["||Maestro de Feriados", "<?=$_PARAMETRO["PATHSIA"]?>rh/feriados.php?concepto=03-0041&_APLICACION="+_A, , , , d['03-0041'], , , , , , ],
						["||Tabla de Sueldos Mínimos", "<?=$_PARAMETRO["PATHSIA"]?>rh/sueldos_minimos.php?concepto=03-0051&_APLICACION="+_A, , , , d['03-0051'], , , , , , ],
					
				["Admin.", , , , , , "0", , , , , ],
					["|Seguridad", , , , , , , , , , , ],
						["||Maesto de Usuarios", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_lista&lista=usuarios&filtrar=default&concepto=04-0001&_APLICACION="+_A, , , , d['04-0001'], , , , , , ],
						["||Dar Autorizaciones a Usuarios", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_lista&lista=autorizaciones&filtrar=default&concepto=04-0002&_APLICACION="+_A, , , , d['04-0002'], , , , , , ],
						["||Cambio de Clave","<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_cambio_clave&opcion=modificar&filtrar=default&concepto=04-0001", , , , d['04-0001'], , , , , , ],
					    ["||Listado de Usuario Reporte","<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_reporte&opcion=modificar&filtrar=default&concepto=04-0001", , , , d['04-0001'], , , , , , ],
					["|Seguridad Alterna", , , , , , , , , , , ],
						["||Dar Autorizaciones a Usuarios", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_lista&lista=alterna&filtrar=default&concepto=04-0003&_APLICACION="+_A, , , , d['04-0003'], , , , , , ],
			];
            dm_initFrame("frmSet", 0, 1, 0);

         </script>
		</td>
	</tr>
</table>
</body>
</html>
