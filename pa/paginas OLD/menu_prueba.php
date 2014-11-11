<?php
include("../../lib/fphp.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
		<!-- Deluxe Menu -->
    <script type="text/javascript">var dmWorkPath = "../../rh/data.files/";</script>
    <script type="text/javascript" src="../../rh/data.files/dmenu.js"></script>
    <!-- (c) 2007, by Deluxe-Menu.com -->
</head>
<body style="background:url(../../imagenes/fondo_menu.jpg)">

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
            var blankImage="../../rh/data.files/blank.gif";
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
            var arrowImageMain=["../../rh/data.files/arrv_white.gif",""];
            var arrowWidthSub=0;
            var arrowHeightSub=0;
            var arrowImageSub=["../../rh/data.files/arr_white.gif",""];
            
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
                ["itemWidth=94px","itemHeight=21px","itemBackColor=transparent,transparent","itemBackImage=../../rh/data.files/btn_black.gif,../../rh/data.files/btn_black2.gif","itemBorderWidth=0","fontStyle='bold 10px Tahoma','bold 10px Tahoma'","fontColor=#FFFFFF,#FFFFFF"],
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
["Automóvil", , , , , , "0", , , , , ],
  ["|Nuevo","registroautomovil.php?concepto=01-0001&limit=0&filtrar=DEFAULT", , , ,d['01-0001'], , , , , , ], 
  ["|Modificar","editarautomovil.php?concepto=01-0002&limit=0&filtrar=DEFAULT", , , , d['01-0002'], , , , , , ],
 
["Control de Vehículo", , , , , , "0", , , , , ],
  ["|Mantenimiento", , , , , , , , , , , ],
  ["||Preventivo","mantenimientopreventivoplaca.php?concepto=02-0001&limit=0&filtrar=DEFAULT&regresar=framemain", , , ,d['02-0001'] , , , , , , ],
  ["||Taller", , , , , , , , , , , ],
    ["|||Entrada","entradavehiculoplacataller.php?concepto=02-0002&limit=0&filtrar=DEFAULT&regresar=framemain", , , ,d['02-0002'] , , , , , , ],
    ["|||Salida","salidavehiculoplacataller.php?concepto=02-0003&limit=0&filtrar=DEFAULT&regresar=framemain", , , ,d['02-0003'], , , , , , ],
  ["|Entrada Institución","entradavehiculoplaca.php?concepto=02-0004&limit=0&filtrar=DEFAULT&regresar=framemain", , , ,d['02-0004'] , , , , , , ],  
  ["|Salida Institución","salidavehiculoplaca.php?concepto=02-0005&limit=0&filtrar=DEFAULT&regresar=framemain", , , ,d['02-0005'] , , , , , , ],
						
["Reportes", , , , , , "0", , , , , ],
  ["|Específicos", , , , , , , , , , , ],
	["||Mantenimientos x fecha", "pantallaporfecha.php?concepto=03-0001&limit=0&filtrar=DEFAULT", , , ,d['03-0001'] , , , , , , ],
	["||Salidas de Vehiculos por fecha", "pantallaporfecha1.php?concepto=03-0002&limit=0&filtrar=DEFAULT", , , ,d['03-0002'] , , , , , , ],
	["||Entradas de Vehiculos por fecha", "pantallaporfechaentradavehiculos.php?concepto=03-0003&limit=0&filtrar=DEFAULT", , , ,d['03-0003'] , , , , , , ],
	["||Entradas de Vehiculos al Taller por fecha","pantallaporfechaentradatallervehiculos.php?concepto=03-0004&limit=0&filtrar=DEFAULT", , , d['03-0004'], , , , , , , ],
	["||Salidas de Vehiculos del Taller por Fecha","pantallaporfechasalidatallervehiculos.php?concepto=03-0005&limit=0&filtrar=DEFAULT", , , d['03-0005'], , , , , , , ],
							
  ["|Generales", , , , , , , , , , , ],
	["||Automóviles Existentes", "../pdf/pdfautomovilesexistentes.php?concepto=03-0006&limit=0$filtrar=DEFAULT", , , ,d['03-0006'] , , , , , , ],	
	["||Mantenimientos Existentes", "../pdf/pdfmantenimientosexistentes.php?concepto=03-0007&limit=0$filtrar=DEFAULT", , , ,d['03-0007']  , , , , , , ],	
	["||Salidas de Vehiculos", "../pdf/pdfsalidasvehiculosexistentes.php?concepto=03-0008&limit=0$filtrar=DEFAULT", , , , d['03-0008'] , , , , , , ],	
	["||Entradas de Vehiculos", "../pdf/pdfentradasvehiculoexistentes.php?concepto=03-0009&limit=0$filtrar=DEFAULT", , , ,d['03-0009'] , , , , , , ],	
	["||Salidas a Taller de Vehiculos", "../pdf/pdfsalidastallervehiculoexistentes.php?concepto=03-0010&limit=0$filtrar=DEFAULT", , , ,d['03-0010']  , , , , , , ],	
	["||Entradas a Taller de Vehiculos", "../pdf/pdfentradastallervehiculoexistentes.php?concepto=03-0011&limit=0$filtrar=DEFAULT", , , , d['03-0011'] , , , , , , ],						

["Admin.", , , , , , "0", , , , , ],
 ["|Seguridad", , , , , , , , , , , ],
   ["||Maesto de Usuarios", "../../comunes/gehen.php?anz=usuarios_lista&lista=usuarios&filtrar=default&concepto=04-0001", , , , d['04-0001'], , , , , , ],
   ["||Dar Autorizaciones a Usuarios", "../../comunes/gehen.php?anz=usuarios_lista&lista=autorizaciones&filtrar=default&concepto=04-0002", , , , d['04-0002'], , , , , , ],
	["|Seguridad Alterna", , , , , , , , , , , ],
	    ["||Dar Autorizaciones a Usuarios", "../../comunes/gehen.php?anz=usuarios_lista&lista=alterna&filtrar=default&concepto=04-0003", , , , d['04-0003'], , , , , , ],
                ];	
            
            dm_initFrame("frmSet", 0, 1, 0);

         </script>
		</td>
	</tr>
</table>
</body>
</html>
