<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<!-- Deluxe Menu -->
    <script type="text/javascript">var dmWorkPath = "data.files/";</script>
    <script type="text/javascript" src="data.files/dmenu.js"></script>
    <!-- (c) 2007, by Deluxe-Menu.com -->
</head>
<body style="background:url(../imagenes/fondo_menu.jpg)">
<input type="hidden" name="menu" id="menu" value="<?=$_SESSION["PERMISOS_ACTUAL"]?>" />
<input type="hidden" name="admin" id="admin" value="<?=$_SESSION["ADMINISTRADOR_ACTUAL"]?>" />
<input type="hidden" name="regresar" id="regresar" value="framemain"/>
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
            var blankImage="data.files/blank.gif";
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
            var arrowImageMain=["data.files/arrv_white.gif",""];
            var arrowWidthSub=0;
            var arrowHeightSub=0;
            var arrowImageSub=["data.files/arr_white.gif",""];
            
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
                ["itemWidth=94px","itemHeight=21px","itemBackColor=transparent,transparent","itemBackImage=data.files/btn_black.gif,data.files/btn_black2.gif","itemBorderWidth=0","fontStyle='bold 10px Tahoma','bold 10px Tahoma'","fontColor=#FFFFFF,#FFFFFF"],
            ];
            var menuStyles = [
                ["menuBackColor=transparent","menuBorderWidth=0","itemSpacing=0","itemPadding=5px 6px 5px 6px","smOrientation=undefined"],
            ];
            
            var disabled = new Array();
            var admin=document.getElementById("admin").value;
            var opciones=document.getElementById("menu").value;
            
            if (admin=="S") {
                var menuItems = [
                    ["Correspondencia", , , , , , "0", , , , , ],
					   ["|Entrada de Documentos", , , , , , , , , , , ],
					      ["||Nuevo Documento", "cpe_entradaextnuevo.php?limit=0&regresar=framemain&concepto=01-0001", , , , , , , , , , ],
						  ["||Lista Documento", "cpe_entrada.php?limit=0&concepto=01-0002", , , , , , , , , , ],
						  ["||Atender Documento", "cpe_atenderext.php?limit=0&concepto=01-0003", , , , , , , , , , ],
						  ["||Listar Distribuci&oacute;n", "cpe_distribucionext.php?limit=0&concepto=01-0004", , , , , , , , , , ],
					   
					   ["|Salida de Documentos", , , , , , , , , , , ],
					      ["||Nuevo Documento", "cpe_salidanuevo.php?limit=0&regresar=framemain&concepto=01-0005", , , , , , , , , , ],
						  ["||Lista Documento", "cpe_salidalista.php?limit=0&concepto=01-0006", , , , , , , , , , ],
						  ["||Preparar Documento", "cpe_salidapreparar.php?limit=0&concepto=01-0007", , , , , , , , , , ],
						  ["||Envío Documento", "cpe_salidaenvio.php?limit=0&concepto=01-0008", , , , , , , , , , ],
						  ["||Listar Distribuci&oacute;n", "cpe_salidadist.php?limit=0&concepto=01-0009", , , , , , , , , , ],
						  
					   ["|Documentos Internos", , , , , , , , , , , ],
					      ["||Nuevo Documento", "cpi_docinternonuevos.php?limit=0&regresar=framemain&concepto=01-0010", , , , , , , , , , ],
						  ["||Lista Documento", "cpi_docinternoslista.php?limit=0&concepto=01-0011", , , , , , , , , , ],
						  ["||Preparar Documento", "cpi_docinternosprep.php?limit=0&concepto=01-0012", , , , , , , , , , ],
						  ["||Envío Documento", "cpi_docinternosenvio.php?limit=0&concepto=01-0013", , , , , , , , , , ],
						  ["||Listar Distribuci&oacute;n", "cpi_docinternosdist.php?limit=0&concepto=01-0014", , , , , , , , , , ],
						  
					   ["|Dependencia", , , , , , , , , , , ],
					      ["||Documentos Recibidos", "cpi_depenrecibido.php?limit=0&concepto=01-0015", , , , , , , , , , ],
						  ["||Documentos Enviados", "cpi_depenenviado.php?limit=0&concepto=01-0016", , , , , , , , , , ],
						                                            					                                        					
                   ["Documentos", , , , , , "0", , , , , ],
					    ["|Informes", , , , , , , , , , , ],
						   ["||Nuevo Informe", "doc_informenuevo.php?limit=0&concepto=02-0001", , , , , , , , , , ],
						   ["||Lista Informe", "doc_informelista.php?limit=0&concepto=02-0002", , , , , , , , , , ],
						   ["||Distribución Informe", "doc_informedistribuir.php?limit=0&concepto=02-0003", , , , , , , , , , ],
						["|Expedientes", , , , , , , , , , , ],
					
					//// ---- 
					["Reportes", , , , , , "0", , , , , ],
					   ["|Entrada de Documentos", , , , , , , , , , , ],
					     ["||Lista Documentos","rp_entradadocumentoslista.php?limit=0&concepto=06-0001" , , , , , , , , , ],
						 ["||Distribuci&oacute;n x Documento","rp_entradadistdocumento.php?limit=0&concepto=06-0002", , , , , , , , , ],
  					     ["||Distribuci&oacute;n","rp_entradadistdetalle.php?limit=0&concepto=06-0003", , , , , , , , , ],
						 
					   ["|Salida Documentos", , , , , , , , , , , ],
					     ["||Lista Documentos","rp_documentosexternoslista.php?limit=0&concepto=06-0004" , , , , , , , , , ],
						 ["||Distribuci&oacute;n x Documento","rp_documentosexternosdistsalida.php?limit=0&concepto=06-0005" , , , , , , , , , ],
  					     ["||Distribuci&oacute;n","rp_docsalidadistribucion.php?limit=0&concepto=06-0006" , , , , , , , , , ],
					     ["||Historico x Documento","rp_docsalidahistxdoc.php?limit=0&concepto=06-0007" , , , , , , , , , ],
						 ["||Distribuci&oacute;n x Mensajero","rp_docsalidamensajero.php?limit=0&concepto=06-0011" , , , , , , , , , ],
						 
					   ["|Documentos Interno", , , , , , , , , , , ],
					     ["||Lista Documentos","rp_documentosinternoslista.php?limit=0&concepto=06-0008" , , , , , , , , , ],
						 ["||Distribuci&oacute;n x Documento","rp_documentosinternosdistxdoc.php?limit=0&concepto=06-0009" , , , , , , , , , ],
					     ["||Distribuci&oacute;n","rp_documentosinternosdistribucion.php?limit=0&concepto=06-0010" , , , , , , , , , ],
						 
					//// ----	 
					["Maestros", , , , , , "0", , , , , ],					   						 
                        ["|Del Sistema SIA", , , , , , , , , , , ],
                            ["||Personas", "personas.php?limit=0&concepto=04-0007", , , , , , , , , , ],
                            ["||Propios del Sistema", , , , , , , , , , , ],
                                ["|||Aplicaciones", "aplicaciones.php?limit=0&concepto=04-0001", , , , , , , , , , ],
                                ["|||Par&aacute;metros", "parametros.php?limit=0&concepto=04-0002", , , , , , , , , , ],
                        ["|Entes Externos", , , , , , , , , , , ],
					        ["||Organismos","pf_organismos_externos.php?limit=0&concepto=04-0003", , , , , , , , , , ],
						    ["||Dependencias","pf_dependencias_externas.php?limit=0&concepto=04-0004", , , , , , , , , , ],
						    ["||Tipo Correspondencia","cp_tipocorrespondencia.php?limit=0&concepto=04-0005", , , , , , , , , , ],
						    ["||Particular","cpe_particular.php?limit=0&concepto=04-0006", , , , , , , , , , ],
                        					
                    ["Admin.", , , , , , "0", , , , , ],
                        ["|Seguridad", , , , , , , , , , , ],
                            ["||Maesto de Usuarios", "usuarios.php?limit=0&concepto=05-0001", , , , , , , , , , ],
                            ["||Dar Autorizaciones a Usuarios", "usuarios_autorizaciones.php?limit=0&concepto=05-0002", , , , , , , , , , ],
                            ["||Cambio de Clave","../comunes/gehen.php?anz=usuarios_cambio_clave&opcion=modificar&filtrar=default&concepto=01-0011", , , , , , , , , , ],
                        ["|Seguridad Alterna", , , , , , , , , , , ],
                            ["||Dar Autorizaciones a Usuarios", "seguridad_alterna.php?limit=0&concepto=05-0003", , , , , , , , , , ],
                ];	
            }
            
            else if (admin=="N" && opciones!="N") {
                opciones=opciones.split(";");
                for (i=0; i<opciones.length; i++) {
                    var items=opciones[i].split(",");
                    if (items[1]=="S") disabled[items[0]]=""; else disabled[items[0]]="_";
                }
				                
                var menuItems = [
                    ["Correspondencia", , , , , , "0", , , , , ],
					   ["|Entrada de Documentos", , , , , , , , , , , ],
					      ["||Nuevo Documento", "cpe_entradaextnuevo.php?limit=0&regresar=framemain&concepto=01-0001", , , , disabled['01-0001'], , , , , , ],
						  ["||Lista Documento", "cpe_entrada.php?limit=0&concepto=01-0002", , , , disabled['01-0002'], , , , , , ],
						  ["||Atender Documento", "cpe_atenderext.php?limit=0&concepto=01-0003", , , , disabled['01-0003'], , , , , , ],
						  ["||Listar Distribuci&oacute;n", "cpe_distribucionext.php?limit=0&concepto=01-0004", , , , disabled['01-0004'], , , , , , ],
					   
					   ["|Salida de Documentos", , , , , , , , , , , ],
					      ["||Nuevo Documento", "cpe_salidanuevo.php?limit=0&regresar=framemain&concepto=01-0005", , , , disabled['01-0005'], , , , , , ],
						  ["||Lista Documento", "cpe_salidalista.php?limit=0&concepto=01-0006", , , , disabled['01-0006'], , , , , , ],
						  ["||Preparar Documento", "cpe_salidapreparar.php?limit=0&concepto=01-0007", , , , disabled['01-0007'], , , , , , ],
						  ["||Envío Documento", "cpe_salidaenvio.php?limit=0&concepto=01-0008", , , , disabled['01-0008'], , , , , , ],
						  ["||Listar Distribuci&oacute;n", "cpe_salidadist.php?limit=0&concepto=01-0009", , , , disabled['01-0009'], , , , , , ],
						  
					   ["|Documentos Internos", , , , , , , , , , , ],
					      ["||Nuevo Documento", "cpi_docinternonuevos.php?limit=0&regresar=framemain&concepto=01-0010", , , , disabled['01-0010'], , , , , , ],
						  ["||Lista Documento", "cpi_docinternoslista.php?limit=0&concepto=01-0011", , , , disabled['01-0011'], , , , , , ],
						  ["||Preparar Documento", "cpi_docinternosprep.php?limit=0&concepto=01-0012", , , , disabled['01-0012'], , , , , , ],
						  ["||Envío Documento", "cpi_docinternosenvio.php?limit=0&concepto=01-0013", , , , disabled['01-0013'], , , , , , ],
						  ["||Listar Distribuci&oacute;n", "cpi_docinternosdist.php?limit=0&concepto=01-0014", , , , disabled['01-0014'], , , , , , ],
						  
					   ["|Dependencia", , , , , , , , , , , ],
					      ["||Documentos Recibidos", "cpi_depenrecibido.php?limit=0&concepto=01-0015", , , , disabled['01-0015'], , , , , , ],
						  ["||Documentos Enviados", "cpi_depenenviado.php?limit=0&concepto=01-0016", , , , disabled['01-0016'], , , , , , ],
						                                            					                                        					
                    //["Documentos", , , , , , "0", , , , , ],
					  //  ["|Informes", , , , , , , , , , , ],
						 //  ["||Nuevo Informe", "doc_informenuevo.php?limit=0&concepto=02-0001", , , , disabled['02-0001'], , , , , , ],
						 //  ["||Lista Informe", "doc_informelista.php?limit=0&concepto=02-0002", , , , disabled['02-0002'], , , , , , ],
						 //  ["||Distribución Informe", "doc_informedistribuir.php?limit=0&concepto=02-0003", , , , disabled['02-0003'], , , , , , ],
						//["|Expedientes", , , , , , , , , , , ],
					
					
					["Reportes", , , , , , "0", , , , , ],
					   ["|Entrada de Documentos", , , , , , , , , , , ],
					     ["||Lista Documentos","rp_entradadocumentoslista.php?limit=0?concepto=06-0001", , , , disabled['06-0001'], , , , , , ],
						 ["||Distribuci&oacute;n x Documento","rp_entradadistdocumento.php?limit=0&concepto=06-0002" , , , , disabled['06-0002'], , , , , , ],
  					     ["||Distribuci&oacute;n","rp_entradadistdetalle.php?limit=0&concepto=06-0003", , , , disabled['06-0003'], , , , , , ],
						 
					   ["|Salida Documentos", , , , , , , , , , , ],
					     ["||Lista Documentos","rp_documentosexternoslista.php?limit=0?concepto=06-0004" , , , , disabled['06-0004'], , , , , , ],
						 ["||Distribuci&oacute;n x Documento","rp_documentosexternosdistsalida.php?limit=0&concepto=06-0005", , , ,disabled['06-0005'] , , , , ,],
  					     ["||Distribuci&oacute;n", "rp_docsalidadistribucion.php?limit=0&concepto=06-0006", , , ,disabled['06-0006'] , , , , , ],
					     ["||Historico x Documento", "rp_docsalidahistxdoc.php?limit=0&conecpto=06-0007", , , ,disabled['06-0007'] , , , , ,],
						 
					   ["|Documentos Interno", , , , , , , , , , , ],
					     ["||Lista Documentos","rp_documentosinternoslista.php?limit=0&concepto=06-0008", , , , disabled['06-0008'], , , , , ,],
						 ["||Distribuci&oacute;n x Documento","rp_documentosinternosdistxdoc.php?limit=0&concepto=06-0009", , , ,disabled['06-0009'] , , , , ,],
					     ["||Distribuci&oacute;n","rp_documentosinternosdistribucion.php?limit=0&concepto=06-0010", , , ,disabled['06-0010'] , , , , ,],
					
					["Maestros", , , , , , "0", , , , , ],					   						 
                        ["|Del Sistema SIA", , , , , , , , , , , ],
                            ["||Personas", "personas.php?limit=0?concepto=04-0007", , , , disabled['04-0007'], , , , , , ],
                            ["||Propios del Sistema", , , , , , , , , , , ],
                                ["|||Aplicaciones", "aplicaciones.php?concepto=04-0001", , , , disabled['04-0001'], , , , , , ],
                                ["|||Par&aacute;metros", "parametros.php?concepto=04-0002", , , , disabled['04-0002'], , , , , , ],
                        ["|Entes Externos", , , , , , , , , , , ],
					        ["||Organismos","pf_organismos_externos.php?concepto=04-0003", , , , disabled['04-0003'], , , , , , ],
						    ["||Dependencias","pf_dependencias_externas.php?concepto=04-0004", , , , disabled['04-0004'], , , , , , ],
						    ["||Tipo Correspondencia","cp_tipocorrespondencia.php?concepto=04-0005", , , , disabled['04-0005'], , , , , , ],
						    ["||Particular","cpe_particular.php?limit=0&concepto=04-0006", , , , disabled['04-0006'], , , , , , ],
                        					
                    ["Admin.", , , , , , "0", , , , , ],
                        ["|Seguridad", , , , , , , , , , , ],
                            ["||Maesto de Usuarios", "usuarios.php?limit=0&concepto=05-0001", , , , disabled['05-0001'], , , , , , ],
                            ["||Dar Autorizaciones a Usuarios", "usuarios_autorizaciones.php?limit=0&concepto=05-0002", , , , disabled['05-0002'], , , , , , ],
                            ["||Cambio de Clave","../comunes/gehen.php?anz=usuarios_cambio_clave&opcion=modificar&filtrar=default&concepto=01-0011", , , , disabled['01-0011'], , , , , , ],
                        ["|Seguridad Alterna", , , , , , , , , , , ],
                            ["||Dar Autorizaciones a Usuarios", "seguridad_alterna.php?limit=0&concepto=05-0003", , , , disabled['05-0003'], , , , , , ],
                ];
            } 
            
            else if (admin=="N" && opciones=="N")  {
                var menuItems = [
                    ["Correspondencia", , , , , , "0", , , , , ],
					   ["|Entrada de Documentos", , , , , , , , , , , ],
					      ["||Nuevo Documento", "cpe_entradaextnuevo.php?limit=0&regresar=framemain&concepto=01-0001", , , , "_", , , , , , ],
						  ["||Lista Documento", "cpe_entrada.php?limit=0&concepto=01-0002", , , , "_", , , , , , ],
						  ["||Atender Documento", "cpe_atenderext.php?limit=0&concepto=01-0003", , , , "_", , , , , , ],
						  ["||Listar Distribuci&oacute;n", "cpe_distribucionext.php?limit=0&concepto=01-0004", , , , "_", , , , , , ],
					   
					   ["|Salida de Documentos", , , , , , , , , , , ],
					      ["||Nuevo Documento", "cpe_salidanuevo.php?limit=0&regresar=framemain&concepto=01-0005", , , , "_", , , , , , ],
						  ["||Lista Documento", "cpe_salidalista.php?limit=0&concepto=01-0006", , , , "_", , , , , , ],
						  ["||Preparar Documento", "cpe_salidapreparar.php?limit=0&concepto=01-0007", , , , "_", , , , , , ],
						  ["||Envío Documento", "cpe_salidaenvio.php?limit=0&concepto=01-0008", , , , "_", , , , , , ],
						  ["||Listar Distribuci&oacute;n", "cpe_salidadist.php?limit=0&concepto=01-0009", , , , "_", , , , , , ],
						  
					   ["|Documentos Internos", , , , , , , , , , , ],
					      ["||Nuevo Documento", "cpi_docinternonuevos.php?limit=0&regresar=framemain&concepto=01-0010", , , , "_", , , , , , ],
						  ["||Lista Documento", "cpi_docinternoslista.php?limit=0&concepto=01-0011", , , , "_", , , , , , ],
						  ["||Preparar Documento", "cpi_docinternosprep.php?limit=0&concepto=01-0012", , , , "_", , , , , , ],
						  ["||Envío Documento", "cpi_docinternosenvio.php?limit=0&concepto=01-0013", , , , "_", , , , , , ],
						  ["||Listar Distribuci&oacute;n", "cpi_docinternosdist.php?limit=0&concepto=01-0014", , , , "_", , , , , , ],
						  
					   ["|Dependencia", , , , , , , , , , , ],
					      ["||Documentos Recibidos", "cpi_depenrecibido.php?limit=0&concepto=01-0015", , , , "_", , , , , , ],
						  ["||Documentos Enviados", "cpi_depenenviado.php?limit=0&concepto=01-0016", , , , "_", , , , , , ],
						                                            					                                        					
                    ["Documentos", , , , , , "0", , , , , ],
					    ["|Informes", , , , , , , , , , , ],
						   ["||Nuevo Informe", "doc_informenuevo.php?limit=0&concepto=02-0001", , , , "_", , , , , , ],
						   ["||Lista Informe", "doc_informelista.php?limit=0&concepto=02-0002", , , , "_", , , , , , ],
						   ["||Distribución Informe", "doc_informedistribuir.php?limit=0&concepto=02-0003", , , , "_", , , , , , ],
						["|Expedientes", , , , , , , , , , , ],
					
									
					["Reportes", , , , , , "0", , , , , ],
					   ["|Entrada de Documentos", , , , , , , , , , , ],
					     ["||Lista Documentos","rp_entradadocumentoslista.php?limit=0&concepto=06-0001" , , , ,"_" , , , , , ],
						 ["||Distribuci&oacute;n x Documento","rp_entradadistdocumento.php?limit=0&concepto=06-0002", , , , "_", , , , , ],
  					     ["||Distribuci&oacute;n","rp_entradadistdetalle.php?limit=0&concepto=06-0003", , , , "_", , , , , ],
						 
					   ["|Salida Documentos", , , , , , , , , , , ],
					     ["||Lista Documentos","rp_documentosexternoslista.php?limit=0&concepto=06-0004" , , , ,"_" , , , , , ],
						 ["||Distribuci&oacute;n x Documento","rp_documentosexternosdistsalida.php?limit=0&concepto=06-0005" , , , ,"_" , , , , , ],
  					     ["||Distribuci&oacute;n","rp_docsalidadistribucion.php?limit=0&concepto=06-0006" , , , ,"_" , , , , , ],
					     ["||Historico x Documento","rp_docsalidahistxdoc.php?limit=0&conecpto=06-0007" , , , , "_", , , , , ],
						 
					   ["|Documentos Interno", , , , , , , , , , , ],
					     ["||Lista Documentos","rp_documentosinternoslista.php?limit=0&concepto=06-0008" , , , ,"_" , , , , , ],
						 ["||Distribuci&oacute;n x Documento","rp_documentosinternosdistxdoc.php?limit=0&concepto=06-0009" , , , ,"_" , , , , , ],
					     ["||Distribuci&oacute;n","rp_documentosinternosdistribucion.php?limit=0&concepto=06-0010" , , , ,"_" , , , , , ],
					
					
					["Maestros", , , , , , "0", , , , , ],					   						 
                        ["|Del Sistema SIA", , , , , , , , , , , ],
                            ["||Personas", "personas.php?limit=0?concepto=04-0007", , , , "_", , , , , , ],
                            ["||Propios del Sistema", , , , , , , , , , , ],
                                ["|||Aplicaciones", "aplicaciones.php?concepto=04-0001", , , , "_", , , , , , ],
                                ["|||Par&aacute;metros", "parametros.php?concepto=04-0002", , , , "_", , , , , , ],
                        ["|Entes Externos", , , , , , , , , , , ],
					        ["||Organismos","pf_organismos_externos.php&concepto=04-0003", , , , "_", , , , , , ],
						    ["||Dependencias","pf_dependencias_externas.php?concepto=04-0004", , , , "_", , , , , , ],
						    ["||Tipo Correspondencia","cp_tipocorrespondencia.php?concepto=04-0005", , , , "_", , , , , , ],
						    ["||Particular","cpe_particular.php?limit=0?concepto=04-0006", , , , "_", , , , , , ],
                        					
                    ["Admin.", , , , , , "0", , , , , ],
                        ["|Seguridad", , , , , , , , , , , ],
                            ["||Maesto de Usuarios", "usuarios.php?limit=0&concepto=05-0001", , , , "_", , , , , , ],
                            ["||Dar Autorizaciones a Usuarios", "usuarios_autorizaciones.php?limit=0&concepto=05-0002", , , , "_", , , , , , ],
                            ["||Cambio de Clave","../comunes/gehen.php?anz=usuarios_cambio_clave&opcion=modificar&filtrar=default&concepto=01-0011", , , , "_", , , , , , ],
                        ["|Seguridad Alterna", , , , , , , , , , , ],
                            ["||Dar Autorizaciones a Usuarios", "seguridad_alterna.php?limit=0&concepto=05-0003", , , , "_", , , , , , ],
                ];
            }
            
            dm_initFrame("frmSet", 0, 1, 0);

         </script>
		</td>
	</tr>
</table>
</body>
</html>
