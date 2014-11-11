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
    <script type="text/javascript" src="<?php echo $_PARAMETRO["PATHSIA"]?>rh/data.files/dmenu.js"></script>
    <!-- (c) 2007, by Deluxe-Menu.com -->
</head>
<body style="background:url(../imagenes/fondo_menu.jpg)">

<input type="hidden" name="menu" id="menu" value="<?=$_SESSION["PERMISOS_ACTUAL"]?>" />
<input type="hidden" name="admin" id="admin" value="<?=$_SESSION["ADMINISTRADOR_ACTUAL"]?>" />
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
            
            //--- Common
            var isHorizontal=1;
            var smColumns=1;
            var smOrientation=0;
            var dmRTL=0;
            var pressedItem=-2;
            var itemCursor="default";
            var itemTarget="_self";
            var statusString="link";
            var blankImage="<?=$_PARAMETRO["PATHSIA"]?>rh/data.files/blank.gif";
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
            var arrowImageMain=["<?=$_PARAMETRO["PATHSIA"]?>rh/data.files/arrv_white.gif",""];
            var arrowWidthSub=0;
            var arrowHeightSub=0;
            var arrowImageSub=["<?=$_PARAMETRO["PATHSIA"]?>rh/data.files/arr_white.gif",""];
            
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
			var _A = "NOMINA";
			
			var menuItems = [
				["Gesti&oacute;n", , , , , , "0", , , , , ],
					["|Empleados", "<?=$_PARAMETRO["PATHSIA"]?>empleados/gehen.php?anz=empleados_lista&filtrar=default&concepto=01-0001&_APLICACION="+_A, , , , d['01-0001'], , , , , , ],
					
                    ["Procesos", , , , , , "0", , , , , ],
                        ["|Asignacion de Conceptos", , , , , , , , , , , ],
                            ["||Temporales", "<?=$_PARAMETRO["PATHSIA"]?>nomina/conceptos_temporales.php?concepto=05-0001&limit=0&filtrar=DEFAULT&_APLICACION="+_A, , , , d['05-0001'], , , , , , ],
                            ["||Permanentes", "<?=$_PARAMETRO["PATHSIA"]?>nomina/conceptos_permanentes.php?concepto=05-0002&limit=0&filtrar=DEFAULT&_APLICACION="+_A, , , , d['05-0002'], , , , , , ],
                        //["|Conceptos Autom&aacute;ticos", "<?=$_PARAMETRO["PATHSIA"]?>nomina/conceptos_automaticos.php?concepto=05-0003&limit=0&filtrar=DEFAULT&_APLICACION="+_A, , , , d['05-0003'], , , , , , ],
						["|-",, , , , , , , , , , ],
						["|-",, , , , , , , , , , ],
                        ["|Ajuste Salarial", , , , , , , , , , , ],
                            ["||Listar Ajustes", "<?=$_PARAMETRO["PATHSIA"]?>nomina/ajuste_salarial_listar.php?opcion=LISTAR&concepto=05-0010&limit=0&filtrar=DEFAULT&_APLICACION="+_A, , , , d['05-0010'], , , , , , ],
                            ["||Aprobar Ajustes", "<?=$_PARAMETRO["PATHSIA"]?>nomina/ajuste_salarial_listar.php?opcion=APROBAR&concepto=05-0014&limit=0&filtrar=DEFAULT&_APLICACION="+_A, , , , d['05-0014'], , , , , , ],
						["|-",, , , , , , , , , , ],
						["|-",, , , , , , , , , , ],
                        ["|Fideicomiso", , , , , , , , , , , ],
                            ["||Cálculo de Fideicomiso", "<?=$_PARAMETRO["PATHSIA"]?>nomina/gehen.php?anz=pr_fideicomiso_calculo&concepto=01-0026&_APLICACION="+_A, , , , d['05-0015'], , , , , , ],
                            ["||Acumulados del Fideicomiso", "<?=$_PARAMETRO["PATHSIA"]?>nomina/fideicomiso.php?concepto=05-0007&limit=0&filtrar=DEFAULT&_APLICACION="+_A, , , , d['05-0007'], , , , , , ],
                            ["||Depósitos de Antiguedad", "<?=$_PARAMETRO["PATHSIA"]?>nomina/pr_fideicomiso_depositos_antiguedad.php?concepto=05-0008&limit=0&filtrar=default&_APLICACION="+_A, , , , d['05-0008'], , , , , , ],
						["|-",, , , , , , , , , , ],
						["|-",, , , , , , , , , , ],
                        ["|Pre Nómina", , , , , , , , , , , ],
                            ["||Control de Procesos", "<?=$_PARAMETRO["PATHSIA"]?>nomina/procesos_control_prenomina.php?concepto=05-0011&limit=0&filtrar=DEFAULT&_APLICACION="+_A, , , , d['05-0011'], , , , , , ],
                            ["||Ejecución de Procesos", "<?=$_PARAMETRO["PATHSIA"]?>nomina/ejecucion_procesos_prenomina.php?concepto=05-0012&limit=0&filtrar=DEFAULT&_APLICACION="+_A, , , , d['05-0012'], , , , , , ],
						["|-",, , , , , , , , , , ],
						["|-",, , , , , , , , , , ],
                        ["|Control de Procesos", "<?=$_PARAMETRO["PATHSIA"]?>nomina/procesos_control.php?concepto=05-0004&limit=0&filtrar=DEFAULT&_APLICACION="+_A, , , , d['05-0004'], , , , , , ],
                        ["|Aprobación de Procesos", "<?=$_PARAMETRO["PATHSIA"]?>nomina/procesos_control_aprobacion.php?concepto=05-0005&limit=0&filtrar=DEFAULT&_APLICACION="+_A, , , , d['05-0005'], , , , , , ],
						["|-",, , , , , , , , , , ],
						["|-",, , , , , , , , , , ],
                        ["|Ejecución de Procesos", "<?=$_PARAMETRO["PATHSIA"]?>nomina/ejecucion_procesos.php?concepto=05-0006&limit=0&filtrar=DEFAULT&_APLICACION="+_A, , , , d['05-0006'], , , , , , ],
						["|-",, , , , , , , , , , ],
						["|-",, , , , , , , , , , ],
                        ["|Modificar Payroll", "<?=$_PARAMETRO["PATHSIA"]?>nomina/modificar_payroll.php?concepto=05-0009&limit=0&filtrar=DEFAULT&_APLICACION="+_A, , , , d['05-0009'], , , , , , ],
						["|-",, , , , , , , , , , ],
						["|-",, , , , , , , , , , ],
                        ["|Interfase Cuentas por Pagar", "<?=$_PARAMETRO["PATHSIA"]?>nomina/gehen.php?anz=pr_interfase_cuentas_por_pagar&filtrar=default&concepto=05-0013&_APLICACION="+_A, , , , d['05-0013'], , , , , , ],
                    
                    ["Reportes", , , , , , "0", , , , , ],
                        ["|Empleados", , , , , , , , , , , ],
							["||Empleados", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_empleado.php?filtrar=DEFAULT&concepto=02-0004&_APLICACION="+_A, , , , d['02-0004'], , , , , , ],
							["||-",, , , , , , , , , , ],
							["||-",, , , , , , , , , , ],
							["||Carga Familiar", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_carga_familiar.php?concepto=02-0001&_APLICACION="+_A, , , , d['02-0001'], , , , , , ],
							["||Cumplea&ntilde;os", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_cumpleanios.php?concepto=02-0002&_APLICACION="+_A, , , , d['02-0002'], , , , , , ],
							["||Aniversario", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_aniversario.php?concepto=02-0003&_APLICACION="+_A, , , , d['02-0003'], , , , , , ],
							
                        ["|Fideicomiso", , , , , , , , , , , ],
							["||Calculo de Fideicomiso", "<?=$_PARAMETRO["PATHSIA"]?>nomina/gehen.php?anz=pr_fideicomiso_calculo_pdf_filtro&filtrar=default&concepto=02-0030&_APLICACION="+_A, , , , d['02-0030'], , , , , , ],
                        ["|Nómina", , , , , , , , , , , ],
							["||Resumen de Conceptos", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_resumen_conceptos.php?concepto=02-0005&_APLICACION="+_A, , , , d['02-0005'], , , , , , ],
							["||Resumen de Conceptos por Proceso", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_resumen_conceptos_proceso.php?concepto=02-0020&_APLICACION="+_A, , , , d['02-0020'], , , , , , ],
							["||Resumen de Conceptos con Partidas", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_resumen_conceptos_proceso_partidas.php?concepto=02-0026&_APLICACION="+_A, , , , d['02-0026'], , , , , , ],
							["||Relación de Nómina Cuenta", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_relacion_quincena.php?concepto=02-0006&_APLICACION="+_A, , , , d['02-0006'], , , , , , ],
							["||Relación de Nómina Años de Servicio", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_relacion_quincena2.php?concepto=02-0006&_APLICACION="+_A, , , , d['02-0006'], , , , , , ],
							["||Relación de Nómina (Prueba)", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_relacion_nomina.php?concepto=02-0006&_APLICACION="+_A, , , , d['02-0006'], , , , , , ],
							["||Payroll de Pago", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_payroll.php?concepto=02-0013&_APLICACION="+_A, , , , d['02-0013'], , , , , , ],
							["||Nómina de Trabajadores", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_nomina.php?concepto=02-0012&_APLICACION="+_A, , , , d['02-0012'], , , , , , ],
							["||Resumen Detallado de Conceptos", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_detallado_conceptos.php?concepto=02-0017&_APLICACION="+_A, , , , d['02-0017'], , , , , , ],
							["||-",, , , , , , , , , , ],
							["||-",, , , , , , , , , , ],
							["||Generar TXT Nómina", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_generar_txt.php?concepto=02-0022&_APLICACION="+_A, , , , d['02-0022'], , , , , , ],
							["||-",, , , , , , , , , , ],
							["||-",, , , , , , , , , , ],							
							["||Reporte de Auditoria General", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_auditoria_general.php?concepto=02-0018&_APLICACION="+_A, , , , d['02-0018'], , , , , , ],
							["||Reporte de Auditoria Detallado", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_auditoria_detallado.php?concepto=02-0019&_APLICACION="+_A, , , , d['02-0019'], , , , , , ],
							["||-",, , , , , , , , , , ],
							["||-",, , , , , , , , , , ],
							["||Relación de Ingresos", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_relacion_ingresos.php?concepto=02-0023&_APLICACION="+_A, , , , d['02-0023'], , , , , , ],
							["||-",, , , , , , , , , , ],
							["||-",, , , , , , , , , , ],
							["||Conceptos por Listado", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_conceptos_listado.php?concepto=02-0024&_APLICACION="+_A, , , , d['02-0024'], , , , , , ],
							["||Conceptos por Listado Anual", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_conceptos_listado_anual.php?concepto=02-0027&_APLICACION="+_A, , , , d['02-0027'], , , , , , ],
							["||Listado por Proceso", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_procesos_listado.php?concepto=02-0025&_APLICACION="+_A, , , , d['02-0025'], , , , , , ],
							["||-",, , , , , , , , , , ],
							["||-",, , , , , , , , , , ],
							["||Reporte por Conceptos", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reporte_conceptos.php?concepto=02-0028&_APLICACION="+_A, , , , d['02-0028'], , , , , , ],
							["||-",, , , , , , , , , , ],
							["||-",, , , , , , , , , , ],
							["||Conceptos por Periodo", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reporte_conceptos_periodo.php?concepto=02-0029&_APLICACION="+_A, , , , d['02-0029'], , , , , , ],
                        ["|Retenciones y Aportes", , , , , , , , , , , ],
							["||Retenciones de Impuesto Sobre la Renta", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_relacion_islr.php?concepto=02-0007&_APLICACION="+_A, , , , d['02-0007'], , , , , , ],
							["||Retenciones Vivienda y Hábitat", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_relacion_vivienda_habitat.php?concepto=02-0008&_APLICACION="+_A, , , , d['02-0008'], , , , , , ],
							["||Retenciones Jubilación y Pensión", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_relacion_jubilacion_pension.php?concepto=02-0009&_APLICACION="+_A, , , , d['02-0009'], , , , , , ],
							["||Retenciones Seguro Social Obligatorio", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_relacion_sso.php?concepto=02-0010&_APLICACION="+_A, , , , d['02-0010'], , , , , , ],
							["||Retenciones Paro Forzoso", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_relacion_paro_forzoso.php?concepto=02-0011&_APLICACION="+_A, , , , d['02-0011'], , , , , , ],
							//["||Retenciones FUNDECEDA", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_fundeceda.php?concepto=02-0014&_APLICACION="+_A, , , , d['02-0014'], , , , , , ],
							//["||Retenciones Medida Preventiva de Embargo", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_medida_embargo.php?concepto=02-0015&_APLICACION="+_A, , , , d['02-0015'], , , , , , ],
							//["||Retenciones Embargo Prima por Hijos", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_embargo_hijos.php?concepto=02-0016&_APLICACION="+_A, , , , d['02-0016'], , , , , , ],
							["||Otras Retenciones", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_retenciones.php?concepto=02-0021&_APLICACION="+_A, , , , d['02-0021'], , , , , , ],
							["||Retenciones de Caja de Ahorro", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_retenciones.php?concepto=02-0021&_APLICACION="+_A, , , , d['02-0021'], , , , , , ],
							["||Prestamos de Caja de Ahorro", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_retenciones1.php?concepto=02-0021&_APLICACION="+_A, , , , d['02-0021'], , , , , , ],
					
					["|Proyeccion", , , , , , , , , , , ],	
						    ["||Proyeccion", "<?=$_PARAMETRO["PATHSIA"]?>nomina/py_proyeccion.php?concepto=02-0017&_APLICACION="+_A, , , , d['02-0017'], , , , , , ],		
							//["||Proyeccion Concepto Porcentaje", "<?=$_PARAMETRO["PATHSIA"]?>nomina/py_conceptos_porcentaje.php?concepto=02-0017&_APLICACION="+_A, , , , d['02-0017'], , , , , , ],									
							//["||Proyeccion Nominas", "<?=$_PARAMETRO["PATHSIA"]?>nomina/proyeccion_procesos.php?concepto=02-0017&_APLICACION="+_A, , , , d['02-0017'], , , , , , ],								
							//["||Proyeccion Proceso-Empleados", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_proyeccion.php?concepto=02-0017&_APLICACION="+_A, , , , d['02-0017'], , , , , , ],									
							//["||Proyeccion Proceso-Empleados-Conceptos", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_proyeccion.php?concepto=02-0017&_APLICACION="+_A, , , , d['02-0017'], , , , , , ],
							//["||Proyeccion Reporte", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_proyeccion.php?concepto=02-0017&_APLICACION="+_A, , , , d['02-0017'], , , , , , ],							 
                            ["|| Reporte Partidas por Periodo", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_proyeccion.php?concepto=02-0017&_APLICACION="+_A, , , , d['02-0017'], , , , , , ],
							["|| Reporte Conceptos por Periodo", "<?=$_PARAMETRO["PATHSIA"]?>nomina/reportes_proyeccion_conceptos.php?concepto=02-0017&_APLICACION="+_A, , , , d['02-0017'], , , , , , ],							 							 
                        
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
							["|||Plan de Cuentas", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=plan_cuentas_lista&filtrar=default&concepto=03-0045&_APLICACION="+_A, , , , d['03-0045'], , , , , , ],
							["|||Grupos de Centros de Costos", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=grupo_centro_costos_lista&filtrar=default&concepto=03-0046&_APLICACION="+_A, , , , d['03-0046'], , , , , , ],
							["|||Centros de Costos", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=centro_costos_lista&filtrar=default&concepto=03-0047&_APLICACION="+_A, , , , d['03-0047'], , , , , , ],
						["||Relacionados a Presupuesto", , , , , , , , , , , ],
							["|||Tipos de Cuenta", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=tipo_cuenta_lista&filtrar=default&concepto=03-0056&_APLICACION="+_A, , , , d['03-00563'], , , , , , ],
							["|||Clasificador Presupuestario", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=clasificador_presupuestario_lista&filtrar=default&concepto=03-0057&_APLICACION="+_A, , , , d['03-0057'], , , , , , ],
						["||Otros Maestros", , , , , , , , , , , ],
							["|||Paises", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=paises_lista&filtrar=default&concepto=03-0004&_APLICACION="+_A, , , , d['03-0004'], , , , , , ],
							["|||Estados", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=estados_lista&filtrar=default&concepto=03-0005&_APLICACION="+_A, , , , d['03-0005'], , , , , , ],
							["|||Municipios", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=municipios_lista&filtrar=default&concepto=03-0006&_APLICACION="+_A, , , , d['03-0006'], , , , , , ],
							["|||Ciudades", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=ciudades_lista&filtrar=default&concepto=03-0007&_APLICACION="+_A, , , , d['03-0007'], , , , , , ],
							["|||Tipos de Pago", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=tipos_pago_lista&filtrar=default&concepto=03-0008&_APLICACION="+_A, , , , d['03-0008'], , , , , , ],
							["|||Bancos", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=bancos_lista&filtrar=default&concepto=03-0009&_APLICACION="+_A, , , , d['03-0009'], , , , , , ],
							["|||Unidad Tributaria", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=unidad_tributaria_lista&filtrar=default&concepto=03-0058&_APLICACION="+_A, , , , d['03-0058'], , , , , , ],
					["|Organizaci&oacute;n", , , , , , , , , , , ],
						["||Tipos de Cargo", "<?=$_PARAMETRO["PATHSIA"]?>nomina/tiposcargo.php?concepto=03-0012&_APLICACION="+_A, , , , d['03-0012'], , , , , , ],
						["||Nivel de Tipos de Cargo", "<?=$_PARAMETRO["PATHSIA"]?>nomina/nivelcargo.php?concepto=03-0013&_APLICACION="+_A, , , , d['03-0013'], , , , , , ],
						["||Grupo Ocupacional", "<?=$_PARAMETRO["PATHSIA"]?>nomina/grupoocupacional.php?concepto=03-0014&_APLICACION="+_A, , , , d['03-0014'], , , , , , ],						
						["||Serie Ocupacional", "<?=$_PARAMETRO["PATHSIA"]?>nomina/serieocupacional.php?concepto=03-0015&_APLICACION="+_A, , , , d['03-0015'], , , , , , ],
						["||Grado Salarial", "<?=$_PARAMETRO["PATHSIA"]?>nomina/grado_salarial.php?concepto=03-0044&_APLICACION="+_A, , , , d['03-0044'], , , , , , ],
						["||Cargos", "<?=$_PARAMETRO["PATHSIA"]?>nomina/cargos.php?concepto=03-0016&_APLICACION="+_A, , , , d['03-0016'], , , , , , ],
						
					["|Relaciones Laborales", , , , , , , , , , , ],
						["||Tipos de Trabajador", "<?=$_PARAMETRO["PATHSIA"]?>nomina/tipostrabajador.php?concepto=03-0022", , , , d['03-0022'], , , , , , ],
						
						["||Tipos de N&oacute;mina", "<?=$_PARAMETRO["PATHSIA"]?>relaciones_laborales/gehen.php?anz=tipo_nomina_lista&filtrar=default&concepto=03-0023&_APLICACION="+_A, , , , d['03-0023'], , , , , , ],
						
						["||Perfiles de N&oacute;mina", "<?=$_PARAMETRO["PATHSIA"]?>nomina/tiposperfil.php?concepto=03-0024", , , , d['03-0024'], , , , , , ],
						["||Tipos de Contrato", "<?=$_PARAMETRO["PATHSIA"]?>nomina/tiposcontrato.php?concepto=03-0025", , , , d['03-0025'], , , , , , ],
						["||Formatos de Contrato", "<?=$_PARAMETRO["PATHSIA"]?>nomina/formatoscontrato.php?concepto=03-0026", , , , d['03-0026'], , , , , , ],
						["||Motivos de Cese", "<?=$_PARAMETRO["PATHSIA"]?>nomina/motivoscese.php?concepto=03-0027&_APLICACION="+_A, , , , d['03-0027'], , , , , , ],
						["||Horario Laboral", "<?=$_PARAMETRO["PATHSIA"]?>relaciones_laborales/gehen.php?anz=horario_laboral_lista&filtrar=default&concepto=03-0059&_APLICACION="+_A, , , , d['03-0059'], , , , , , ],
						
					["|N&oacute;minas", , , , , , , , , , , ],
						["||Tipos de Proceso", "<?=$_PARAMETRO["PATHSIA"]?>nomina/tiposproceso.php?concepto=03-0041&_APLICACION="+_A, , , , d['03-0041'], , , , , , ],
						["||Conceptos", "<?=$_PARAMETRO["PATHSIA"]?>nomina/conceptos.php?concepto=03-0043&_APLICACION="+_A, , , , d['03-0043'], , , , , , ],
						["||Perfil de Conceptos", "<?=$_PARAMETRO["PATHSIA"]?>nomina/conceptos_perfil.php?concepto=03-0055&_APLICACION="+_A, , , , d['03-0055'], , , , , , ],
						["||Check Sum", "<?=$_PARAMETRO["PATHSIA"]?>nomina/nuevochecksum.php?concepto=03-0057&_APLICACION="+_A, , , , d['03-0057'], , , , , , ],
					["|Otros", , , , , , , , , , , ],
						["||Miscel&aacute;neos", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=miscelaneos_lista&filtrar=default&concepto=03-0040&_APLICACION="+_A, , , , d['03-0040'], , , , , , ],
						
						["||Tasa de Intereses", "<?=$_PARAMETRO["PATHSIA"]?>nomina/tasa_intereses.php?concepto=03-0048&_APLICACION="+_A, , , , d['03-0048'], , , , , , ],
						["||Tabla de Sueldos Minimos", "<?=$_PARAMETRO["PATHSIA"]?>nomina/sueldos_minimos.php?concepto=03-0054&_APLICACION="+_A, , , , d['03-0054'], , , , , , ],
					
				["Admin.", , , , , , "0", , , , , ],
					["|Seguridad", , , , , , , , , , , ],
						["||Maesto de Usuarios", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_lista&lista=usuarios&filtrar=default&concepto=04-0001&_APLICACION="+_A, , , , d['04-0001'], , , , , , ],
						["||Dar Autorizaciones a Usuarios", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_lista&lista=autorizaciones&filtrar=default&concepto=04-0002&_APLICACION="+_A, , , , d['04-0002'], , , , , , ],
						["||Cambio de Clave","<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_cambio_clave&opcion=modificar&filtrar=default&concepto=04-0001", , , , d['04-0001'], , , , , , ],
					["|Seguridad Alterna", , , , , , , , , , , ],
						["||Dar Autorizaciones a Usuarios", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_lista&lista=alterna&filtrar=default&concepto=04-0003&_APLICACION="+_A, , , , d['04-0003'], , , , , , ],
                        ["|Generar Voucher de Nómina", "<?=$_PARAMETRO["PATHSIA"]?>nomina/voucher_nomina.php?concepto=04-0004&limit=0&filtrar=DEFAULT&_APLICACION="+_A, , , , d['04-0004'], , , , , , ],
			];
            dm_initFrame("frmSet", 0, 1, 0);

         </script>
		</td>
	</tr>
</table>
</body>
</html>
