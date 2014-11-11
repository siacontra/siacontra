<?php
//session_start();
include("../lib/fphp.php");
include "gpresupuesto.php";

//$_PARAMETRO["PATHSIA"]="../";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<!-- Deluxe Menu -->
    <script type="text/javascript">var dmWorkPath = "../pv/data.files/";</script>
    <script type="text/javascript" src="../pv/data.files/dmenu.js"></script>
    <!-- (c) 2007, by Deluxe-Menu.com -->
</head>
<body style="background:url(../imagenes/fondo_menu.jpg)">
<input type="hidden" name="menu" id="menu" value="<?php $_SESSION["PERMISOS_ACTUAL"]?>" />
<input type="hidden" name="admin" id="admin" value="<?php $_SESSION["ADMINISTRADOR_ACTUAL"]?>" />
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
            var blankImage="../pv/data.files/blank.gif";
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
            var arrowImageMain=["../pv/data.files/arrv_white.gif",""];
            var arrowWidthSub=0;
            var arrowHeightSub=0;
            var arrowImageSub=["../pv/data.files/arr_white.gif",""];
            
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
                ["itemWidth=94px","itemHeight=21px","itemBackColor=transparent,transparent","itemBackImage=../pv/data.files/btn_black.gif","../pv/data.files/btn_black2.gif","itemBorderWidth=0","fontStyle='bold 10px Tahoma','bold 10px Tahoma'","fontColor=#FFFFFF,#FFFFFF"],
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
["Presupuesto", , , , , , "0", , , , , ],
 ["|Proyecto", , , , , , , , , , , ],
  ["||Crear Proyecto","../pv/anteproyecto_datosgenerales.php?limit=0&filtrar=DEFAULT&regresar=framemain&concepto=01-0001", , , ,d['01-0001'], , , , , , ],	
  ["||Listar Proyecto","../pv/anteproyecto_listar.php?limit=0&concepto=01-0002&filtrar=DEFAULT", , , , d['01-0002'], , , , , , ],	
  ["||Revisar Proyecto","../pv/anteproyecto_revisar.php?limit=0&concepto=01-0003&filtrar=DEFAULT", , , ,d['01-0003'] , , , , , , ],	
  ["||Aprobar Proyecto","../pv/anteproyecto_aprobar.php?limit=0&concepto=01-0004&filtrar=DEFAULT", , , , d['01-0004'], , , , , , ],
  ["||Generar Proyecto","../pv/anteproyecto_generar.php?limit=0&concepto=01-0005&filtrar=DEFAULT", , , ,d['01-0005'] , , , , , , ],	
 ["|Formulación", , , , , , , , , , , ],
  ["||Listar Presupuesto","../pv/presupuesto_listar.php?limit=0&concepto=01-0006", , , , d['01-0006'], , , , , , ],
  //["||Aprobar Presupuesto", "presupuesto_listar.php?limit=0&concepto=01-0007", , , , , , , , , , ],
 ["|Reformulación", , , , , , , , , , , ],
  ["||Crear Reformulación","../pv/reformulacion_datosgenerales.php?limit=0&regresar=framemain&concepto=01-0008", , , , d['01-0008'], , , , , , ],
  ["||Listar Reformulación","../pv/reformulacion_listar.php?limit=0&concepto=01-0009&filtrar=DEFAULT", , , ,d['01-0009'] , , , , , , ],
  ["||Aprobar Reformulación","../pv/reformulacion_aprobar.php?limit=0&concepto=01-0010&filtrar=DEFAULT", , , ,d['01-0010'] , , , , , , ],
 ["|Ajustes", , , , , , , , , , , ],
  ["||Crear Ajuste","../pv/presupuesto_ajustecrear.php?limit=0&regresar=framemain&concepto=01-0011&filtrar=DEFAULT", , , ,d['01-0011'] , , , , ,],
  ["||Listar Ajustes","../pv/presupuesto_ajustelistar.php?limit=0&concepto=01-0012&filtrar=DEFAULT", , , ,d['01-0012'] , , , , ,],
  ["||Aprobar Ajustes","../pv/presupuesto_ajusteaprobar.php?limit=0&concepto=01-0013&filtrar=DEFAULT", , , ,d['01-0013'] , , , , ,], 
  ["|Reintegro", , , , , , , , , , , ],
  ["||Crear Reintegro","../pv/ProcesoNuevoReintegro.php?limit=0&regresar=framemain&concepto=01-0011&filtrar=DEFAULT", , , ,d['01-0011'] , , , , ,],
  ["||Listar Reintegro","../pv/ProcesoListarReintegro.php?limit=0&concepto=01-0012&filtrar=DEFAULT", , , ,d['01-0012'] , , , , ,],
  ["||Aprobar Reintegro","../pv/ProcesoAprobarReintegro.php?limit=0&concepto=01-0013&filtrar=DEFAULT", , , ,d['01-0013'] , , , , ,], 
 ["|Credito Adicionales", , , , , , , , , , , ],
	["||Crear Solicitud", "crearCreditoAdicional.php?limit=0&concepto=01-0014", , , , , , , , , , ],	
	["||Listar Solicitud", "creditoAdicionalListar.php?limit=0&concepto=01-0015", , , , , , , , , , ],	
	["||Revisar Solicitud", "creditoAdicionalRevisar.php?limit=0&concepto=01-0016", , , , , , , , , , ],
	["||Conformar Solicitud", "creditoAdicionalConformar.php?limit=0&concepto=01-0018", , , , , , , , , , ],			
	["||Aprobar Solicitud", "creditoAdicionalAprobar.php?limit=0&concepto=01-0017", , , , , , , , , , ],
                                    
["Procesos", , , , , , "0", , , , , ],
 ["|Cierre Mensual", , , , , , , , , , , ],
  //["||Listar Cierres", "proceso_cierremeslistar.php?limit=0&concepto=02-0001", , , , , , , , , , ],
  ["||Ejecutar Cierre","../pv/proceso_cierremes.php?limit=0&concepto=02-0002&filtrar=DEFAULT", , , ,d['02-0001'] , , , , , , ],		
  ["|Proyección","proyeccion.php?limit=0&concepto=02-0003" , , , , , , , , , , ],
               
["Reportes", , , , , , "0", , , , , ],
 ["|Proyecto", , , , , , , , , , , ],
  ["||Proyecto Presupuesto", "../pv/r_proyectopresupuesto.php?limit=0&concepto=03-0001&filtrar=DEFAULT", , , ,d['03-0001'] , , , , , , ],
 ["|Formulaci&oacute;n", , , , , , , , , , , ],
  ["||Presupuesto", "../pv/r_formulacionpresupuesto.php?limit=0&concepto=03-0002&filtrar=DEFAULT", , , ,d['03-0002'] , , , , , , ],
  ["||Presupuesto Ajustado", "../pv/r_presupuestoajustado.php?limit=0&concepto=03-0003&filtrar=DEFAULT", , , ,d['03-0003'] , , , , , , ],
 ["|Presupuesto", , , , , , , , , , ,],
  ["||Incrementado","../pv/rp_presincrementado.php?limit=0&concepto=03-0004&filtrar=DEFAULT", , , ,d['03-0004'] , , , , , ,],
  ["||Compromisos","../pv/rp_compromisos.php?limit=0&concepto=03-0005&filtrar=DEFAULT", , , ,d['03-0005'] , , , , , ,],
  ["||Causados","../pv/rp_causados.php?limit=0&concepto=03-0006&filtrar=DEFAULT", , , ,d['03-0006'] , , , , , ,],
  ["||Pagados","../pv/rp_pagados.php?limit=0&concepto=03-0007&filtrar=DEFAULT", , , ,d['03-0007'] , , , , , ,],
  ["||Cierres Mes","../pv/rp_cierremes.php?limit=0&concepto=03-0008&filtrar=DEFAULT", , , ,d['03-0008'] , , , , , ,],
  ["||Disminuido","../pv/rp_presdisminuido.php?limit=0&concepto=03-0009&filtrar=DEFAULT" , , , ,d['03-0009'] , , , , , ,],
  ["||Proyeccion Gasto","../pv/rp_proyeccionGasto.php?limit=0&concepto=03-0009&filtrar=DEFAULT" , , , ,d['03-0009'] , , , , , ,],
  //["||Modificado","rp_presmodificado.php?limit=0&concepto=03-0006" , , , , , , , , , ,],
  ["||Ejecución","../pv/rp_ejecucion.php?limit=0&concepto=03-0010&filtrar=DEFAULT" , , , ,d['03-0010'] , , , , , ,],
  ["||Ejecución Presupuestaria Mensual", "../pv/rp_ejecucionxmes.php?limit=0&concepto=03-0012&filtrar=DEFAULT", , , ,d['03-0012'] , , , , , , ],
  ["||Ejecución Compromisos","../pv/rp_ejecucion_compromiso.php?limit=0&concepto=03-0017&filtrar=DEFAULT" , , , ,d['03-0017'] , , , , , ,],
  ["||Ejecución Presupuestaria","../pv/rp_ejecucionpresupuestaria01.php?limit=0&concepto=03-0011&filtrar=DEFAULT", , , ,d['03-0011'] , , , , , , ],
  ["||Ejecución Presupuestaria x Fecha", "../pv/rp_ejecucionxfecha.php?limit=0&concepto=03-0012&filtrar=DEFAULT", , , ,d['03-0012'] , , , , , , ],
 
  ["||Ejecución por Partida","../pv/rp_ejecucionpartida.php?limit=0&concepto=03-0013&filtrar=DEFAULT", , , , d['03-0013'], , , , , , ],
  ["||Ejecución por Especifica","../pv/rp_ejecucionespecifica.php?limit=0&concepto=03-0014&filtrar=DEFAULT", , , ,d['03-0014'] , , , , , , ],
  ["||Disponibilidad Presup. Resumida","../pv/rp_dispopresresumida.php?limit=0&concepto=03-0015&filtrar=DEFAULT", , , ,d['03-0015'] , , , , , , ],
  ["||Disponibilidad Presup. Detallada","../pv/rp_dispopresdetallada.php?limit=0&concepto=03-0016&filtrar=DEFAULT", , , ,d['03-0016'] , , , , , , ],		
  ["||Clasificador Presupuestario", "rp_clasificadorPresupuestario.php?limit=0&concepto=03-0018", , , ,d['03-0018'], , , , , ,],
  ["||Credito Adicional", "rp_creditoAdicional.php?limit=0&concepto=03-0019", , , ,d['03-0019'] , , , , , , ],
  ["||Ajuste", "rp_ajuste.php?limit=0&concepto=03-0020", , , ,d['03-0020'] , , , , , , ],	
  ["||Disponibilidad", "rp_disponibilidad.php?limit=0&concepto=03-0021", , , ,d['03-0021'] , , , , , ,],
  ["||Situado Constitucional", "rp_situadoConstitucional.php?limit=0&concepto=03-0022", , , ,d['03-0022'] , , , , , ,],	

["Maestros", , , , , , "0", , , , , ],
 ["|Del Sistema SIA", , , , , , , , , , , ],
  ["||Propios del Sistema", , , , , , , , , , , ],
	["|||Aplicaciones","../comunes/gehen.php?anz=aplicaciones_lista&filtrar=default&concepto=04-0011", , , ,d['04-0011'], , , , , , ],
	["|||Par&aacute;metros","../comunes/gehen.php?anz=parametros_lista&filtrar=default&concepto=04-0012", , , ,d['04-0012'], , , , , , ],
  ["||Relacionados a Personas", , , , , , , , , , , ],
	["|||Personas", "../comunes/gehen.php?anz=personas_lista&filtrar=default&concepto=04-0013", , , ,d['04-0013'], , , , , , ],
	["|||Organismos", "../comunes/gehen.php?anz=organismos_lista&filtrar=default&concepto=04-0014", , , ,d['04-0014'], , , , , , ],
	["|||Dependencias", "../comunes/gehen.php?anz=dependencias_lista&filtrar=default&concepto=04-0015", , , ,d['04-0015'], , , , , , ],
  ["||Relacionados a Contabilidad", , , , , , , , , , , ],
	["|||Plan de Cuentas","../comunes/gehen.php?anz=plan_cuentas_lista&filtrar=default&concepto=04-0016", , , ,d['04-0016'], , , , , , ],
	["|||Grupos de Centros de Costos","../comunes/gehen.php?anz=grupo_centro_costos_lista&filtrar=default&concepto=04-0017", , , ,d['04-0017'], , , , , , ],
	["|||Centros de Costos","../comunes/gehen.php?anz=centro_costos_lista&filtrar=default&concepto=04-0018", , , ,d['04-0018'], , , , , , ],
  ["||Relacionados a Presupuesto", , , , , , , , , , , ],
	["|||Tipos de Cuenta", "../comunes/gehen.php?anz=tipo_cuenta_lista&filtrar=default&concepto=04-0019", , , ,d['04-0019'], , , , , , ],
	["|||Clasificador Presupuestario","../comunes/gehen.php?anz=clasificador_presupuestario_lista&filtrar=default&concepto=04-0020", , , ,d['04-0020'], , , , , , ],  
  ["|Presupuesto", , , , , , , , , , , ],
	["||Sector", "../pv/msector.php?limit=0&concepto=04-0001", , , , , , , , , , ],
	["||Programa", "../pv/mprograma.php?limit=0&concepto=04-0002", , , , , , , , , , ],
	["||Sub-Programa", "../pv/msubprog.php?limit=0&concepto=04-0003", , , , , , , , , , ],
	["||Proyecto", "../pv/mproyecto.php?limit=0&concepto=04-0004", , , , , , , , , , ],
	["||Actividad", "../pv/mactividad.php?limit=0&concepto=04-0005", , , , , , , , , , ],
	["||Unidad Ejecutora", "../pv/munidadejecutora.php?limit=0&concepto=04-0006", , , , , , , , , , ],
  ["|Partida", , , , , , , , , , , ],
	["||Tipo de cuenta", "../pv/mtipocuenta.php?limit=0&concepto=04-0007", , , , , , , , , , ],
	["||Partida", "../pv/mpartida.php?limit=0&concepto=04-0008", , , , , , , , , , ],
  ["|Otros", , , , , , , , , , ,],
	["||Control de Cierres Mensuales","../pv/pv_controlcierremensual.php?limit=0&concepto=04-0009", , , , , , , , , ,], 
	["||Misceláneos","../pv/miscelaneos.php?limit=0&concepto=04-0010", , , , , , , , , ,],
						
						
["Admin.", , , , , , "0", , , , , ],
 ["|Seguridad", , , , , , , , , , , ],
  ["||Maesto de Usuarios","../comunes/gehen.php?anz=usuarios_lista&lista=usuarios&filtrar=default&concepto=05-0001", , , , d['05-0001'], , , , , , ],
  ["||Dar Autorizaciones a Usuarios","../comunes/gehen.php?anz=usuarios_lista&lista=autorizaciones&filtrar=default&concepto=05-0002", , , , d['05-0002'], , , , , , ],
  ["||Cambio de Clave","<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_cambio_clave&opcion=modificar&filtrar=default&concepto=04-0001", , , , d['04-0001'], , , , , , ],
 ["|Seguridad Alterna", , , , , , , , , , , ],
  ["||Dar Autorizaciones a Usuarios", "../comunes/gehen.php?anz=usuarios_lista&lista=alterna&filtrar=default&concepto=05-0003", , , , d['05-0003'], , , , , , ],
];	
            
            dm_initFrame("frmSet", 0, 1, 0);

         </script>
		</td>
	</tr>
</table>
</body>
</html>
