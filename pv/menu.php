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
      var _A = "PV";
    
var menuItems = [
["Presupuesto", , , , , , "0", , , , , ],
 ["|Proyecto", , , , , , , , , , , ],
  ["||Crear Proyecto","<?=$_PARAMETRO["PATHSIA"]?>pv/anteproyecto_datosgenerales.php?limit=0&filtrar=DEFAULT&regresar=framemain&concepto=01-0001", , , ,d['01-0001'], , , , , , ],
  ["||Listar Proyecto","<?=$_PARAMETRO["PATHSIA"]?>pv/anteproyecto_listar.php?limit=0&concepto=01-0002&filtrar=DEFAULT", , , , d['01-0002'], , , , , , ],	
  ["||Revisar Proyecto","<?=$_PARAMETRO["PATHSIA"]?>pv/anteproyecto_revisar.php?limit=0&concepto=01-0003&filtrar=DEFAULT", , , ,d['01-0003'] , , , , , , ],	
  ["||Aprobar Proyecto","<?=$_PARAMETRO["PATHSIA"]?>pv/anteproyecto_aprobar.php?limit=0&concepto=01-0004&filtrar=DEFAULT", , , , d['01-0004'], , , , , , ],
  ["||Generar Proyecto","<?=$_PARAMETRO["PATHSIA"]?>pv/anteproyecto_generar.php?limit=0&concepto=01-0005&filtrar=DEFAULT", , , ,d['01-0005'] , , , , , , ],	
 ["|Formulación", , , , , , , , , , , ],
  ["||Listar Presupuesto","<?=$_PARAMETRO["PATHSIA"]?>pv/presupuesto_listar.php?limit=0&concepto=01-0006", , , , d['01-0006'], , , , , , ],
  //["||Aprobar Presupuesto", "presupuesto_listar.php?limit=0&concepto=01-0007", , , , , , , , , , ],
 ["|Reformulación", , , , , , , , , , , ],
  ["||Crear Reformulación","<?=$_PARAMETRO["PATHSIA"]?>pv/reformulacion_datosgenerales.php?limit=0&regresar=framemain&concepto=01-0008", , , , d['01-0008'], , , , , , ],
  ["||Listar Reformulación","<?=$_PARAMETRO["PATHSIA"]?>pv/reformulacion_listar.php?limit=0&concepto=01-0009&filtrar=DEFAULT", , , ,d['01-0009'] , , , , , , ],
  ["||Aprobar Reformulación","<?=$_PARAMETRO["PATHSIA"]?>pv/reformulacion_aprobar.php?limit=0&concepto=01-0010&filtrar=DEFAULT", , , ,d['01-0010'] , , , , , , ],
 ["|Ajustes", , , , , , , , , , , ],
  ["||Crear Ajuste","<?=$_PARAMETRO["PATHSIA"]?>pv/presupuesto_ajustecrear.php?limit=0&regresar=framemain&concepto=01-0011&filtrar=DEFAULT", , , ,d['01-0011'] , , , , ,],
  ["||Listar Ajustes","<?=$_PARAMETRO["PATHSIA"]?>pv/presupuesto_ajustelistar.php?limit=0&concepto=01-0012&filtrar=DEFAULT", , , ,d['01-0012'] , , , , ,],
  ["||Aprobar Ajustes","<?=$_PARAMETRO["PATHSIA"]?>pv/presupuesto_ajusteaprobar.php?limit=0&concepto=01-0013&filtrar=DEFAULT", , , ,d['01-0013'] , , , , ,], 
  ["|Credito Adicionales", , , , , , , , , , , ],
	["||Crear Solicitud", "<?=$_PARAMETRO["PATHSIA"]?>pv/crearCreditoAdicional.php?limit=0&concepto=01-0014&filtrar=DEFAULT", , , ,d['01-0014'] , , , , ,],	
	["||Listar Solicitud", "<?=$_PARAMETRO["PATHSIA"]?>pv/creditoAdicionalListar.php?limit=0&concepto=01-0015&filtrar=DEFAULT", , , ,d['01-0015'] , , , , ,],	
	["||Revisar Solicitud", "<?=$_PARAMETRO["PATHSIA"]?>pv/creditoAdicionalRevisar.php?limit=0&concepto=01-0016&filtrar=DEFAULT", , , ,d['01-0016'] , , , , ,],
	["||Conformar Solicitud", "<?=$_PARAMETRO["PATHSIA"]?>pv/creditoAdicionalConformar.php?limit=0&concepto=01-0017&filtrar=DEFAULT", , , ,d['01-0017'] , , , , ,],			
	["||Aprobar Solicitud", "<?=$_PARAMETRO["PATHSIA"]?>pv/creditoAdicionalAprobar.php?limit=0&concepto=01-0018&filtrar=DEFAULT", , , ,d['01-0018'] , , , , ,],
                                    
["Procesos", , , , , , "0", , , , , ],
 ["|Cierre Mensual", , , , , , , , , , , ],
  //["||Listar Cierres", "proceso_cierremeslistar.php?limit=0&concepto=02-0001", , , , , , , , , , ],
  ["||Ejecutar Cierre","<?=$_PARAMETRO["PATHSIA"]?>pv/proceso_cierremes.php?limit=0&concepto=02-0002&filtrar=DEFAULT", , , ,d['02-0001'] , , , , , , ],		
  ["|Proyección","<?=$_PARAMETRO["PATHSIA"]?>pv/proyeccion.php??limit=0&concepto=02-0002&filtrar=DEFAULT", , , ,d['02-0002'] , , , , , , ], 
               
["Reportes", , , , , , "0", , , , , ],
 ["|Proyecto", , , , , , , , , , , ],
  ["||Proyecto Presupuesto", "<?=$_PARAMETRO["PATHSIA"]?>pv/r_proyectopresupuesto.php?limit=0&concepto=03-0001&filtrar=DEFAULT", , , ,d['03-0001'] , , , , , , ],
 ["|Formulaci&oacute;n", , , , , , , , , , , ],
  ["||Presupuesto", "<?=$_PARAMETRO["PATHSIA"]?>pv/r_formulacionpresupuesto.php?limit=0&concepto=03-0002&filtrar=DEFAULT", , , ,d['03-0002'] , , , , , , ],
  ["||Presupuesto Ajustado", "<?=$_PARAMETRO["PATHSIA"]?>pv/r_presupuestoajustado.php?limit=0&concepto=03-0003&filtrar=DEFAULT", , , ,d['03-0003'] , , , , , , ],
 ["|Presupuesto", , , , , , , , , , ,],
  ["||Incrementado","<?=$_PARAMETRO["PATHSIA"]?>pv/rp_presincrementado.php?limit=0&concepto=03-0004&filtrar=DEFAULT", , , ,d['03-0004'] , , , , , ,],
  ["||Compromisos","<?=$_PARAMETRO["PATHSIA"]?>pv/rp_compromisos.php?limit=0&concepto=03-0005&filtrar=DEFAULT", , , ,d['03-0005'] , , , , , ,],
  ["||Causados","<?=$_PARAMETRO["PATHSIA"]?>pv/rp_causados.php?limit=0&concepto=03-0006&filtrar=DEFAULT", , , ,d['03-0006'] , , , , , ,],
  ["||Pagados","<?=$_PARAMETRO["PATHSIA"]?>pv/rp_pagados.php?limit=0&concepto=03-0007&filtrar=DEFAULT", , , ,d['03-0007'] , , , , , ,],
  ["||Cierres Mes","<?=$_PARAMETRO["PATHSIA"]?>pv/rp_cierremes.php?limit=0&concepto=03-0008&filtrar=DEFAULT", , , ,d['03-0008'] , , , , , ,],
  ["||Disminuido","<?=$_PARAMETRO["PATHSIA"]?>pv/rp_presdisminuido.php?limit=0&concepto=03-0009&filtrar=DEFAULT" , , , ,d['03-0009'] , , , , , ,],
  ["||Proyeccion Gasto","<?=$_PARAMETRO["PATHSIA"]?>pv/rp_proyeccionGasto.php?limit=0&concepto=03-0010&filtrar=DEFAULT" , , , ,d['03-0010'] , , , , , ,],
  //["||Modificado","rp_presmodificado.php?limit=0&concepto=03-0006" , , , , , , , , , ,],
  ["||Ejecución","<?=$_PARAMETRO["PATHSIA"]?>pv/rp_ejecucion.php?limit=0&concepto=03-0011&filtrar=DEFAULT" , , , ,d['03-0011'] , , , , , ,],
  ["||Ejecución Presupuestaria Mensual", "<?=$_PARAMETRO["PATHSIA"]?>pv/rp_ejecucionxmes.php?limit=0&concepto=03-0012&filtrar=DEFAULT", , , ,d['03-0012'] , , , , , , ],
  ["||Ejecución Compromisos","<?=$_PARAMETRO["PATHSIA"]?>pv/rp_ejecucion_compromiso.php?limit=0&concepto=03-0013&filtrar=DEFAULT" , , , ,d['03-0013'] , , , , , ,],
  ["||Ejecución Presupuestaria","<?=$_PARAMETRO["PATHSIA"]?>pv/rp_ejecucionpresupuestaria01.php?limit=0&concepto=03-0014&filtrar=DEFAULT", , , ,d['03-0014'] , , , , , , ],
  ["||Ejecución Presupuestaria x Fecha", "<?=$_PARAMETRO["PATHSIA"]?>pv/rp_ejecucionxfecha.php?limit=0&concepto=03-0015&filtrar=DEFAULT", , , ,d['03-0015'] , , , , , , ],
 
  ["||Ejecución por Partida","<?=$_PARAMETRO["PATHSIA"]?>pv/rp_ejecucionpartida.php?limit=0&concepto=03-0016&filtrar=DEFAULT", , , , d['03-0016'], , , , , , ],
  ["||Ejecución por Especifica","<?=$_PARAMETRO["PATHSIA"]?>pv/rp_ejecucionespecifica.php?limit=0&concepto=03-0017&filtrar=DEFAULT", , , ,d['03-0017'] , , , , , , ],
  ["||Disponibilidad Presup. Resumida","<?=$_PARAMETRO["PATHSIA"]?>pv/rp_dispopresresumida.php?limit=0&concepto=03-0018&filtrar=DEFAULT", , , ,d['03-0018'] , , , , , , ],
  ["||Disponibilidad Presup. Detallada","<?=$_PARAMETRO["PATHSIA"]?>pv/rp_dispopresdetallada.php?limit=0&concepto=03-0019&filtrar=DEFAULT", , , ,d['03-0019'] , , , , , , ],		
  ["||Clasificador Presupuestario", "<?=$_PARAMETRO["PATHSIA"]?>pv/rp_clasificadorPresupuestario.php?limit=0&concepto=03-0020", , , ,d['03-0020'], , , , , ,],
  ["||Credito Adicional", "<?=$_PARAMETRO["PATHSIA"]?>pv/rp_creditoAdicional.php?limit=0&concepto=03-0021", , , ,d['03-0021'] , , , , , , ],
  ["||Ajuste", "<?=$_PARAMETRO["PATHSIA"]?>pv/rp_ajuste.php?limit=0&concepto=03-0022", , , ,d['03-0022'] , , , , , , ],	
  ["||Disponibilidad", "<?=$_PARAMETRO["PATHSIA"]?>pv/rp_disponibilidad.php?limit=0&concepto=03-0023", , , ,d['03-0023'] , , , , , ,],
  ["||Situado Constitucional", "<?=$_PARAMETRO["PATHSIA"]?>pv/rp_situadoConstitucional.php?limit=0&concepto=03-0024", , , ,d['03-0024'] , , , , , ,],	

["Maestros", , , , , , "0", , , , , ],
 ["|Del Sistema SIA", , , , , , , , , , , ],
  ["||Propios del Sistema", , , , , , , , , , , ],
	["|||Aplicaciones","<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=aplicaciones_lista&filtrar=default&concepto=04-0011", , , ,d['04-0011'], , , , , , ],
	["|||Par&aacute;metros","<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=parametros_lista&filtrar=default&concepto=04-0012", , , ,d['04-0012'], , , , , , ],
  ["||Relacionados a Personas", , , , , , , , , , , ],
	["|||Personas", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=personas_lista&filtrar=default&concepto=04-0013", , , ,d['04-0013'], , , , , , ],
	["|||Organismos", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=organismos_lista&filtrar=default&concepto=04-0014", , , ,d['04-0014'], , , , , , ],
	["|||Dependencias", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=dependencias_lista&filtrar=default&concepto=04-0015", , , ,d['04-0015'], , , , , , ],
  ["||Relacionados a Contabilidad", , , , , , , , , , , ],
	["|||Plan de Cuentas","<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=plan_cuentas_lista&filtrar=default&concepto=04-0016", , , ,d['04-0016'], , , , , , ],
	["|||Grupos de Centros de Costos","<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=grupo_centro_costos_lista&filtrar=default&concepto=04-0017", , , ,d['04-0017'], , , , , , ],
	["|||Centros de Costos","<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=centro_costos_lista&filtrar=default&concepto=04-0018", , , ,d['04-0018'], , , , , , ],
  ["||Relacionados a Presupuesto", , , , , , , , , , , ],
	["|||Tipos de Cuenta", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=tipo_cuenta_lista&filtrar=default&concepto=04-0019", , , ,d['04-0019'], , , , , , ],
	["|||Clasificador Presupuestario","<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=clasificador_presupuestario_lista&filtrar=default&concepto=04-0020", , , ,d['04-0020'], , , , , , ],  
  ["|Presupuesto", , , , , , , , , , , ],
	["||Sector", "<?=$_PARAMETRO["PATHSIA"]?>pv/msector.php?limit=0&concepto=04-0001", , , ,d['04-0001'], , , , , , ],
	["||Programa", "<?=$_PARAMETRO["PATHSIA"]?>pv/mprograma.php?limit=0&concepto=04-0002", , , ,d['04-0002'], , , , , , ],
  ["||Unidad Ejecutora", "<?=$_PARAMETRO["PATHSIA"]?>pv/munidadejecutora.php?limit=0&concepto=04-0003", , , ,d['04-0003'], , , , , , ],
	["||Actividad", "<?=$_PARAMETRO["PATHSIA"]?>pv/msubprog.php?limit=0&concepto=04-0004", , , ,d['04-0004'], , , , , , ],
	["||Proyecto", "<?=$_PARAMETRO["PATHSIA"]?>pv/mproyecto.php?limit=0&concepto=04-0005", , , ,d['04-0005'], , , , , , ],
  ["||Sub-Programa", "<?=$_PARAMETRO["PATHSIA"]?>pv/mactividad.php?limit=0&concepto=04-0006", , , ,d['04-0006'], , , , , , ],
  ["|Partida", , , , , , , , , , , ],
	["||Tipo de cuenta", "<?=$_PARAMETRO["PATHSIA"]?>pv/mtipocuenta.php?limit=0&concepto=04-0007", , , ,d['04-0007'], , , , , , ],
	["||Partida", "<?=$_PARAMETRO["PATHSIA"]?>pv/mpartida.php?limit=0&concepto=04-0008", , , ,d['04-0008'], , , , , , ],
  ["|Otros", , , , , , , , , , ,],
	["||Control de Cierres Mensuales","<?=$_PARAMETRO["PATHSIA"]?>pv/pv_controlcierremensual.php?limit=0&concepto=04-0009", , , ,d['04-0009'], , , , , , ],
	["||Misceláneos","<?=$_PARAMETRO["PATHSIA"]?>pv/miscelaneos.php?limit=0&concepto=04-0010", , , ,d['04-0010'], , , , , , ],
						
						
["Admin.", , , , , , "0", , , , , ],
 ["|Seguridad", , , , , , , , , , , ],
  ["||Maestro de Usuarios","<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_lista&lista=usuarios&filtrar=default&concepto=05-0001", , , , d['05-0001'], , , , , , ],
  ["||Dar Autorizaciones a Usuarios","<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_lista&lista=autorizaciones&filtrar=default&concepto=05-0002", , , , d['05-0002'], , , , , , ],
  ["||Cambio de Clave","<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_cambio_clave&opcion=modificar&filtrar=default&concepto=05-0003", , , , d['05-0003'], , , , , , ],
 ["|Seguridad Alterna", , , , , , , , , , , ],
  ["||Dar Autorizaciones a Usuarios", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_lista&lista=alterna&filtrar=default&concepto=05-0004", , , , d['05-0004'], , , , , , ],
];	
            
            dm_initFrame("frmSet", 0, 1, 0);

         </script>
		</td>
	</tr>
</table>
</body>
</html>
