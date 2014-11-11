<?php
include("../lib/fphp.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
		<!-- Deluxe Menu -->
    <script type="text/javascript">var dmWorkPath = "<?=$_PARAMETRO["PATHSIA"]?>rh/data.files/";</script>
    <script type="text/javascript" src="<?=$_PARAMETRO["PATHSIA"]?>rh/data.files/dmenu.js"></script>
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
			
			var menuItems = [
				["Act. Fiscal", , , , , , "0", , , , , ],
					["|Planificación", , , , , , , , , , , ],
						["||Nueva Planificación", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_actuacion_fiscal_form&opcion=nuevo&action=framemain&concepto=01-0001", , , , d['01-0001'], , , , , , ],
						["||Listar Planificación", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_actuacion_fiscal_lista&filtrar=default&lista=todos&concepto=01-0002", , , , d['01-0002'], , , , , , ],
						["||Revisar Planificación", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_actuacion_fiscal_lista&filtrar=default&lista=revisar&concepto=01-0003", , , , d['01-0003'], , , , , , ],
						["||Aprobar Planificación", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_actuacion_fiscal_lista&filtrar=default&lista=aprobar&concepto=01-0004", , , , d['01-0004'], , , , , , ],
					
					["|Detalle de Actividades", , , , , , , , , , , ],
						["||Ejecución Actividades", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_actuacion_fiscal_actividades&lista=terminar&filtrar=default&concepto=01-0005", , , , d['01-0005'], , , , , , ],
						["||Listar Detalle de Actividades", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_actuacion_fiscal_actividades&lista=listar&filtrar=default&lista=todos&concepto=01-0006", , , , d['01-0006'], , , , , , ],

					["|Prórrogas", , , , , , , , , , , ],
						["||Nueva Prórroga", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_actuacion_fiscal_prorrogas_form&opcion=nuevo&action=framemain&concepto=01-0007", , , , d['01-0007'], , , , , , ],
						["||Listar Prórroga", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_actuacion_fiscal_prorrogas_lista&filtrar=default&lista=todos&concepto=01-0008", , , , d['01-0008'], , , , , , ],
						["||Revisar Prórroga", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_actuacion_fiscal_prorrogas_lista&filtrar=default&lista=revisar&concepto=01-0009", , , , d['01-0009'], , , , , , ],
						["||Aprobar Prórroga", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_actuacion_fiscal_prorrogas_lista&filtrar=default&lista=aprobar&concepto=01-0010", , , , d['01-0010'], , , , , , ],

				["Potestad Inv.", , , , , , "0", , , , , ],
					["|Val. Jurídicas", , , , , , , , , , , ],
						["||Listar Actuaciones Fiscales", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_actuacion_fiscal_lista&filtrar=default&lista=generar&concepto=02-0001", , , , d['02-0001'], , , , , , ],
						
						["||Planificación", , , , , , , , , , , ],
							["|||Listar Planificación", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_valoracion_juridica_lista&filtrar=default&lista=todos&concepto=02-0002", , , , d['02-0002'], , , , , , ],
							["|||Revisar Planificación", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_valoracion_juridica_lista&filtrar=default&lista=revisar&concepto=02-0003", , , , d['02-0003'], , , , , , ],
							["|||Aprobar Planificación", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_valoracion_juridica_lista&filtrar=default&lista=aprobar&concepto=02-0004", , , , d['02-0004'], , , , , , ],
						
						["||Detalle de Actividades", , , , , , , , , , , ],
							["|||Ejecución Actividades", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_valoracion_juridica_actividades&lista=terminar&filtrar=default&concepto=02-0005", , , , d['02-0005'], , , , , , ],
							["|||Listar Detalle de Actividades", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_valoracion_juridica_actividades&lista=listar&filtrar=default&lista=todos&concepto=02-0006", , , , d['02-0006'], , , , , , ],
	
						["||Prórrogas", , , , , , , , , , , ],
							["|||Nueva Prórroga", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_valoracion_juridica_prorrogas_form&opcion=nuevo&action=framemain&concepto=02-0007", , , , d['02-0007'], , , , , , ],
							["|||Listar Prórroga", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_valoracion_juridica_prorrogas_lista&filtrar=default&lista=todos&concepto=02-0008", , , , d['02-0008'], , , , , , ],
							["|||Revisar Prórroga", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_valoracion_juridica_prorrogas_lista&filtrar=default&lista=revisar&concepto=02-0009", , , , d['02-0009'], , , , , , ],
							["|||Aprobar Prórroga", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_valoracion_juridica_prorrogas_lista&filtrar=default&lista=aprobar&concepto=02-0010", , , , d['02-0010'], , , , , , ],
							
					["|Potestad Inv.", , , , , , , , , , , ],
						["||Listar Valoraciones Jurídicas", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_valoracion_juridica_lista&filtrar=default&lista=generar&concepto=02-0011", , , , d['02-0011'], , , , , , ],
						
						["||Planificación", , , , , , , , , , , ],
							["|||Listar Planificación", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_potestad_investigativa_lista&filtrar=default&lista=todos&concepto=02-0012", , , , d['02-0012'], , , , , , ],
							["|||Revisar Planificación", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_potestad_investigativa_lista&filtrar=default&lista=revisar&concepto=02-0013", , , , d['02-0013'], , , , , , ],
							["|||Aprobar Planificación", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_potestad_investigativa_lista&filtrar=default&lista=aprobar&concepto=02-0014", , , , d['02-0014'], , , , , , ],
						
						["||Detalle de Actividades", , , , , , , , , , , ],
							["|||Ejecución Actividades", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_potestad_investigativa_actividades&lista=terminar&filtrar=default&concepto=02-0015", , , , d['02-0015'], , , , , , ],
							["|||Listar Detalle de Actividades", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_potestad_investigativa_actividades&lista=listar&filtrar=default&lista=todos&concepto=02-0016", , , , d['02-0016'], , , , , , ],
	
						["||Prórrogas", , , , , , , , , , , ],
							["|||Nueva Prórroga", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_potestad_investigativa_prorrogas_form&opcion=nuevo&action=framemain&concepto=02-0017", , , , d['02-0017'], , , , , , ],
							["|||Listar Prórroga", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_potestad_investigativa_prorrogas_lista&filtrar=default&lista=todos&concepto=02-0018", , , , d['02-0018'], , , , , , ],
							["|||Revisar Prórroga", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_potestad_investigativa_prorrogas_lista&filtrar=default&lista=revisar&concepto=02-0019", , , , d['02-0019'], , , , , , ],
							["|||Aprobar Prórroga", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_potestad_investigativa_prorrogas_lista&filtrar=default&lista=aprobar&concepto=02-0020", , , , d['02-0020'], , , , , , ],

				["Det. Resp.", , , , , , "0", , , , , ],
					["|Listar Potestades Investigativas", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_potestad_investigativa_lista&filtrar=default&lista=generar&concepto=03-0001", , , , d['03-0001'], , , , , , ],
						
					["|Planificación", , , , , , , , , , , ],
						["||Listar Planificación", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_determinacion_responsabilidad_lista&filtrar=default&lista=todos&concepto=03-0002", , , , d['03-0002'], , , , , , ],
						["||Revisar Planificación", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_determinacion_responsabilidad_lista&filtrar=default&lista=revisar&concepto=03-0003", , , , d['03-0003'], , , , , , ],
						["||Aprobar Planificación", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_determinacion_responsabilidad_lista&filtrar=default&lista=aprobar&concepto=03-0004", , , , d['03-0004'], , , , , , ],
					
					["|Detalle de Actividades", , , , , , , , , , , ],
						["||Ejecución Actividades", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_determinacion_responsabilidad_actividades&lista=terminar&filtrar=default&concepto=03-0005", , , , d['03-0005'], , , , , , ],
						["||Listar Detalle de Actividades", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_determinacion_responsabilidad_actividades&lista=listar&filtrar=default&lista=todos&concepto=03-0006", , , , d['03-0006'], , , , , , ],

					["|Prórrogas", , , , , , , , , , , ],
						["||Nueva Prórroga", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_determinacion_responsabilidad_prorrogas_form&opcion=nuevo&action=framemain&concepto=03-0007", , , , d['03-0007'], , , , , , ],
						["||Listar Prórroga", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_determinacion_responsabilidad_prorrogas_lista&filtrar=default&lista=todos&concepto=03-0008", , , , d['03-0008'], , , , , , ],
						["||Revisar Prórroga", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_determinacion_responsabilidad_prorrogas_lista&filtrar=default&lista=revisar&concepto=03-0009", , , , d['03-0009'], , , , , , ],
						["||Aprobar Prórroga", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_determinacion_responsabilidad_prorrogas_lista&filtrar=default&lista=aprobar&concepto=03-0010", , , , d['03-0010'], , , , , , ],

				["Reportes", , , , , , "0", , , , , ],
					["|Act. Fiscales", , , , , , , , , , , ],
						["||Planificación", "<?=$_PARAMETRO["PATHSIA"]?>pf/pf_pdf_actuacion_fiscal_filtro.php?fproceso=01&pdf=planificacion&concepto=04-0001", , , , d['04-0001'], , , , , , ],
						["||Ejecución", "<?=$_PARAMETRO["PATHSIA"]?>pf/pf_pdf_actuacion_fiscal_filtro.php?fproceso=01&pdf=ejecucion&concepto=04-0002", , , , d['04-0002'], , , , , , ],

					//["|Potestad Inv.", , , , , , , , , , , ],
						//["||Planificación", "<?=$_PARAMETRO["PATHSIA"]?>pf/pf_pdf_actuacion_fiscal_filtro.php?fproceso=02&pdf=planificacion&concepto=04-0003", , , , d['04-0003'], , , , , , ],
						//["||Ejecución", "<?=$_PARAMETRO["PATHSIA"]?>pf/pf_pdf_actuacion_fiscal_filtro.php?fproceso=02&pdf=ejecucion&concepto=04-0004", , , , d['04-0004'], , , , , , ],

					//["|Det. Resp.", , , , , , , , , , , ],
						//["||Planificación", "<?=$_PARAMETRO["PATHSIA"]?>pf/pf_pdf_actuacion_fiscal_filtro.php?fproceso=03&pdf=planificacion&concepto=04-0005", , , , d['04-0005'], , , , , , ],
						//["||Ejecución", "<?=$_PARAMETRO["PATHSIA"]?>pf/pf_pdf_actuacion_fiscal_filtro.php?fproceso=03&pdf=ejecucion&concepto=04-0006", , , , d['04-0006'], , , , , , ],
						
				["Maestros", , , , , , "0", , , , , ],
					["|Del Sistema SIA", , , , , , , , , , , ],
						["||Propios del Sistema", , , , , , , , , , , ],
							["|||Aplicaciones", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=aplicaciones_lista&filtrar=default&concepto=05-0001", , , , d['05-0001'], , , , , , ],
							["|||Par&aacute;metros", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=parametros_lista&filtrar=default&concepto=05-0002", , , , d['05-0002'], , , , , , ],
						["||Relacionados a Personas", , , , , , , , , , , ],
							["|||Personas", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=personas_lista&filtrar=default&concepto=05-0003", , , , d['05-0003'], , , , , , ],
							["|||Organismos", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=organismos_lista&filtrar=default&concepto=05-0004", , , , d['05-0004'], , , , , , ],
							["|||Dependencias", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=dependencias_lista&filtrar=default&concepto=05-0005", , , , d['05-0005'], , , , , , ],
						["||Relacionados a Contabilidad", , , , , , , , , , , ],
							["|||Plan de Cuentas", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=plan_cuentas_lista&filtrar=default&concepto=05-0006", , , , d['05-0006'], , , , , , ],
							["|||Grupos de Centros de Costos", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=grupo_centro_costos_lista&filtrar=default&concepto=05-0007", , , , d['05-0007'], , , , , , ],
							["|||Centros de Costos", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=centro_costos_lista&filtrar=default&concepto=05-0008", , , , d['05-0008'], , , , , , ],
						["||Relacionados a Presupuesto", , , , , , , , , , , ],
							["|||Tipos de Cuenta", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=tipo_cuenta_lista&filtrar=default&concepto=05-0009", , , , d['05-0009'], , , , , , ],
							["|||Clasificador Presupuestario", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=clasificador_presupuestario_lista&filtrar=default&concepto=05-0010", , , , d['05-0010'], , , , , , ],
						["||Otros Maestros", , , , , , , , , , , ],
							["|||Paises", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=paises_lista&filtrar=default&concepto=05-0011", , , , d['05-0011'], , , , , , ],
							["|||Estados", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=estados_lista&filtrar=default&concepto=05-0012", , , , d['05-0012'], , , , , , ],
							["|||Municipios", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=municipios_lista&filtrar=default&concepto=05-0013", , , , d['05-0013'], , , , , , ],
							["|||Ciudades", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=ciudades_lista&filtrar=default&concepto=05-0014", , , , d['05-0014'], , , , , , ],
							["|||Tipos de Pago", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=tipos_pago_lista&filtrar=default&concepto=05-0015", , , , d['05-0015'], , , , , , ],
							["|||Bancos", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=bancos_lista&filtrar=default&concepto=05-0016", , , , d['05-0016'], , , , , , ],
							["|||Unidad Tributaria", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=unidad_tributaria_lista&filtrar=default&concepto=05-0024", , , , d['05-0024'], , , , , , ],
							
					["|Relacionados a Planificación", , , , , , , , , , , ],
						["||Procesos", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_procesos_lista&filtrar=default&concepto=05-0017", , , , d['05-0017'], , , , , , ],
						["||Fases", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_fases_lista&filtrar=default&concepto=05-0018", , , , d['05-0018'], , , , , , ],
						["||Actividades", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_actividades_lista&filtrar=default&concepto=05-0019", , , , d['05-0019'], , , , , , ],						
						["||Tipos de Actuación Fiscal", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_tipo_actuacion_fiscal_lista&filtrar=default&concepto=05-0020", , , , d['05-0020'], , , , , , ],
							
					["|Entes Externos Sujetos a Control", , , , , , , , , , , ],
						["||Organismos Externos", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_organismos_externos_lista&filtrar=default&concepto=05-0021", , , , d['05-0021'], , , , , , ],
						["||Dependencias Externas", "<?=$_PARAMETRO["PATHSIA"]?>pf/gehen.php?anz=pf_dependencias_externas_lista&filtrar=default&concepto=05-0022", , , , d['05-0022'], , , , , , ],
						
					["|Otros", , , , , , , , , , , ],
						["||Miscel&aacute;neos", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=miscelaneos_lista&filtrar=default&concepto=05-0023", , , , d['05-0023'], , , , , , ],
					
				["Admin.", , , , , , "0", , , , , ],
					["|Seguridad", , , , , , , , , , , ],
						["||Maesto de Usuarios", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_lista&lista=usuarios&filtrar=default&concepto=06-0001", , , , d['06-0001'], , , , , , ],
						["||Dar Autorizaciones a Usuarios", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_lista&lista=autorizaciones&filtrar=default&concepto=06-0002", , , , d['06-0002'], , , , , , ],
						["||Cambio de Clave","<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_cambio_clave&opcion=modificar&filtrar=default&concepto=04-0001", , , , d['04-0001'], , , , , , ],
					["|Seguridad Alterna", , , , , , , , , , , ],
						["||Dar Autorizaciones a Usuarios", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_lista&lista=alterna&filtrar=default&concepto=06-0003", , , , d['06-0003'], , , , , , ],
			];
            dm_initFrame("frmSet", 0, 1, 0);

         </script>
		</td>
	</tr>
</table>
</body>
</html>
