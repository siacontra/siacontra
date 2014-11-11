<?php

include("../lib/fphp.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">

		<!-- Deluxe Menu -->

    <script type="text/javascript">var dmWorkPath = "<?=$_PARAMETRO["PATHSIA"]?>rh/data.files/";</script>

    <script type="text/javascript" src="<?=$_PARAMETRO["PATHSIA"]?>rh/data.files/dmenu.js"></script>



    <!-- (c) 2007, by Deluxe-Menu.com -->

</head>





<body style="background:url(../imagenes/fondo_menu.jpg)" >



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

				["Compras", , , , , , "0", , , , , ],

					["|Requerimientos", , , , , , , , , , , ],

						["||Nuevo Requerimiento", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_requerimiento_form&opcion=nuevo&origen=framemain&concepto=01-0001", , , , d['01-0001'], , , , , , ],

						["||Listar Requerimientos", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_requerimiento_lista&lista=todos&filtrar=default&concepto=01-0002", , , , d['01-0002'], , , , , , ],

						["||Listar Requerimientos (Detalle)", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_requerimiento_detalle&filtrar=default&concepto=01-0003", , , , d['01-0003'], , , , , , ],

						["||Revisar Requerimientos", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_requerimiento_lista&lista=revisar&filtrar=default&concepto=01-0004", , , , d['01-0004'], , , , , , ],

						["||Conformar Requerimientos", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_requerimiento_lista&lista=conformar&filtrar=default&concepto=01-0018", , , , d['01-0018'], , , , , , ],

						["||Aprobar Requerimientos", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_requerimiento_lista&lista=aprobar&filtrar=default&concepto=01-0005", , , , d['01-0005'], , , , , , ],

						["||Requerimientos Pendientes", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_requerimiento_pendiente_lista&lista=todos&filtrar=default&concepto=01-0019", , , , d['01-0019'], , , , , , ],

						

							

					["|Cotizaciones", , , , , , , , , , , ],

						["||Invitar/Cotizar Proveedores", "<?=$_PARAMETRO["PATHSIA"]?>lg/lg_cotizaciones_invitar.php?concepto=01-0006&limit=0&filtrar=default", , , , d['01-0006'], , , , , , ],
						
						["||Acta de Inicio de Adquisición", "<?=$_PARAMETRO["PATHSIA"]?>lg/lg_vergeneraractainicio.php?concepto=01-0020&limit=0&filtrar=default", , , , d['01-0020'], , , , , , ],
						
						["||Evaluaci&oacute;n Cualitativa/Cuantitativa", "<?=$_PARAMETRO["PATHSIA"]?>lg/lg_veracta_inicio_evaluacion.php?concepto=01-0021&limit=0&filtrar=default", , , , d['01-0021'], , , , , , ],
						
						["||Generar Informe Recomendaci&oacute;n", "<?=$_PARAMETRO["PATHSIA"]?>lg/lg_ver_recomendacion.php?concepto=01-0026&limit=0&filtrar=default", , , , d['01-0026'], , , , , , ],
						
						["||Revisar Recomendación", "<?=$_PARAMETRO["PATHSIA"]?>lg/lg_revisar_recomendacion.php?concepto=01-0024&limit=0&filtrar=default", , , , d['01-0024'], , , , , , ],

						["||Aprobar Recomendación", "<?=$_PARAMETRO["PATHSIA"]?>lg/lg_aprobar_recomendacion.php?concepto=01-0024&limit=0&filtrar=default", , , , d['01-0025'], , , , , , ],

						["||Informe Adjudicación", "<?=$_PARAMETRO["PATHSIA"]?>lg/lg_recomendacion_busqueda.php?concepto=01-0022&limit=0&filtrar=default", , , , d['01-0022'], , , , , , ],
						
						["||Declaración Desierto", "<?=$_PARAMETRO["PATHSIA"]?>lg/lg_desierto_recomendacion_busqueda.php?concepto=01-0023&limit=0&filtrar=default", , , , d['01-0023'], , , , , , ],

						["||Listar Invitaciones de Proveedores", "<?=$_PARAMETRO["PATHSIA"]?>lg/lg_cotizaciones_invitaciones_lista.php?concepto=01-0007&limit=0&filtrar=default", , , , d['01-0007'], , , , , , ],

						["||Generar Ordenes Pendientes", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_ordenes_pendientes_lista&lista=todos&filtrar=default&concepto=01-0008", , , , d['01-0008'], , , , , , ],

						

					["|Ordenes de Compras", , , , , , , , , , , ],

						["||Listar Ordenes de Compras", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_orden_compra_lista&lista=todos&filtrar=default&concepto=01-0009", , , , d['01-0009'], , , , , , ],

						["||Listar Ordenes de Compras (Detalle)", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_orden_compra_detalle&lista=todos&filtrar=default&concepto=01-0010", , , , d['01-0010'], , , , , , ],

						["||Revisar Ordenes de Compras", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_orden_compra_lista&lista=revisar&filtrar=default&concepto=01-0012", , , , d['01-0012'], , , , , , ],

						["||Aprobar Ordenes de Compras", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_orden_compra_lista&lista=aprobar&filtrar=default&concepto=01-0011", , , , d['01-0011'], , , , , , ],

						

					["|Ordenes de Servicios", , , , , , , , , , , ],

						["||Listar Ordenes de Servicios", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_orden_servicio_lista&lista=todos&filtrar=default&concepto=01-0013", , , , d['01-0013'], , , , , , ],

						["||Listar Ordenes de Servicios (Det)", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_orden_servicio_detalle&lista=todos&filtrar=default&concepto=01-0014", , , , d['01-0014'], , , , , , ],

						["||Revisar Ordenes de Servicios", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_orden_servicio_lista&lista=revisar&filtrar=default&concepto=01-0015", , , , d['01-0015'], , , , , , ],

						["||Aprobar Ordenes de Servicios", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_orden_servicio_lista&lista=aprobar&filtrar=default&concepto=01-0016", , , , d['01-0016'], , , , , , ],

						["||Confirmar Realización de Servicios", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_orden_servicio_confirmar_lista&filtrar=default&concepto=01-0017", , , , d['01-0017'], , , , , , ],

						

							

				["Almacen", , , , , , "0", , , , , ],

					["|Recepción en Almacen", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_almacen_recepcion_lista&lista=todos&filtrar=default&concepto=05-0003", , , , d['05-0003'], , , , , , ],

					

					["|Despachos de Almacen", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_almacen_despacho_lista&lista=todos&filtrar=default&concepto=05-0001", , , , d['05-0001'], , , , , , ],

					

					["|Control de Almacen", , , , , , , , , , , ],

						["||Listado de Transacciones", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_transaccion_almacen_lista&lista=todos&filtrar=default&concepto=05-0002", , , , d['05-0002'], , , , , , ],

						["||Ejecutar Transacciones", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_transaccion_almacen_lista&lista=ejecutar&filtrar=default&concepto=05-0006", , , , d['05-0006'], , , , , , ],

						

					["|Control de Commodities", , , , , , , , , , , ],

						["||Transacciones Especiales", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_transaccion_commodity_especial_lista&filtrar=default&concepto=05-0004", , , , d['05-0004'], , , , , , ],

						["||Listado de Transacciones", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_transaccion_commodity_lista&lista=todos&filtrar=default&concepto=05-0004", , , , d['05-0005'], , , , , , ],

						["||Ejecutar Transacciones", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_transaccion_commodity_lista&lista=ejecutar&filtrar=default&concepto=05-0007", , , , d['05-0007'], , , , , , ],

						

					["|Control de Caja Chica", , , , , , , , , , , ],

						["||Transacciones de Caja Chica", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_transaccion_cajachica_lista&lista=todos&filtrar=default&concepto=05-0008", , , , d['05-0008'], , , , , , ],

						

							

				["Consultas", , , , , , "0", , , , , ],

					["|Sobre Item", , , , , , , , , , , ],

						["||Inventario Actual", "<?=$_PARAMETRO["PATHSIA"]?>lg/inventario_actual.php?limit=0&filtrar=DEFAULT&concepto=06-0001", , , , d['06-0001'], , , , , , ],

						["||Stock Actual de un Item", "<?=$_PARAMETRO["PATHSIA"]?>lg/lg_stock_actual_item.php?limit=0&filtrar=default&concepto=06-0002", , , , d['06-0002'], , , , , , ],

						
				["Procesos", , , , , , "0", , , , , ],

					["||Control Perceptivo", "<?=$_PARAMETRO["PATHSIA"]?>lg/lg_control_perceptivo_ordencompra.php?limit=0&filtrar=DEFAULT&concepto=08-0012", , , , d['08-0001'], , , , , , ],

							

				["Reportes", , , , , , "0", , , , , ],

					["|Maestros", , , , , , , , , , , ],

						["||Catálogo de Proveedores", "<?=$_PARAMETRO["PATHSIA"]?>lg/reporte_catalogo_proveedores.php?limit=0&filtrar=DEFAULT&concepto=07-0001", , , , d['07-0001'], , , , , , ],

						["||Listado de Items", "<?=$_PARAMETRO["PATHSIA"]?>lg/reporte_listado_items.php?limit=0&filtrar=DEFAULT&concepto=07-0009", , , , d['07-0009'], , , , , , ],

						["||Listado de Commodities", "<?=$_PARAMETRO["PATHSIA"]?>lg/reporte_listado_commodities.php?limit=0&filtrar=DEFAULT&concepto=07-0010", , , , d['07-0010'], , , , , , ],

						["||Listado de Stock", "<?=$_PARAMETRO["PATHSIA"]?>lg/reporte_listado_stock.php?limit=0&filtrar=DEFAULT&concepto=07-0011", , , , d['07-0011'], , , , , , ],

							

					["|Requerimientos", , , , , , , , , , , ],

						["||Detalle del Requerimiento", "<?=$_PARAMETRO["PATHSIA"]?>lg/reporte_detalle_requerimiento.php?limit=0&filtrar=DEFAULT&concepto=07-0002", , , , d['07-0002'], , , , , , ],
                                                ["||Consumo por Dependencia", "<?=$_PARAMETRO["PATHSIA"]?>lg/reporte_detalle_dependencia.php?limit=0&filtrar=DEFAULT&concepto=07-0002", , , , d['07-0002'], , , , , , ],
                                               // ["||Consumo por Item", "<?=$_PARAMETRO["PATHSIA"]?>lg/reporte_detalle_requerimientocopia1.php?limit=0&filtrar=DEFAULT&concepto=07-0002", , , , d['07-0002'], , , , , , ],

					

					["|Cotizaciones", , , , , , , , , , , ],

						["||...", "xxx.php?limit=0&filtrar=DEFAULT&concepto=00-0000", , , , d['00-0000'], , , , , , ],

					

					["|Compras", , , , , , , , , , , ],
					
						["||Ordenes de Compras Comprometidas", "<?=$_PARAMETRO["PATHSIA"]?>lg/reporte_compromiso_ordenes_compras.php?limit=0&filtrar=DEFAULT&concepto=07-0015", , , , d['07-0015'], , , , , , ],

						["||Ordenes de Compras", "<?=$_PARAMETRO["PATHSIA"]?>lg/reporte_ordenes_compras.php?limit=0&filtrar=DEFAULT&concepto=07-0006", , , , d['07-0006'], , , , , , ],

						["||Últimas Compras Realizadas", "<?=$_PARAMETRO["PATHSIA"]?>lg/reporte_ultimas_compras.php?limit=0&filtrar=DEFAULT&concepto=07-0007", , , , d['07-0007'], , , , , , ],
						

					

					["|Servicios", , , , , , , , , , , ],
					
						["||Ordenes de Servicios de Comprometidas", "<?=$_PARAMETRO["PATHSIA"]?>lg/reporte_compromiso_ordenes_servicios.php?limit=0&filtrar=DEFAULT&concepto=07-0016", , , , d['07-0016'], , , , , , ],

						["||Ordenes de Servicios", "<?=$_PARAMETRO["PATHSIA"]?>lg/reporte_ordenes_servicios.php?limit=0&filtrar=DEFAULT&concepto=07-0008", , , , d['07-0008'], , , , , , ],

					

					["|Almacen", , , , , , , , , , , ],
					
						["||Ubicaciones por Almacén", "<?=$_PARAMETRO["PATHSIA"]?>lg/reporte_ubicacion_almacen.php?limit=0&filtrar=DEFAULT&concepto=07-0019", , , , d['07-0019'], , , , , , ],
						
						["||Ingreso por Proveedor", "<?=$_PARAMETRO["PATHSIA"]?>lg/reporte_ingreso_por_proveedor.php?limit=0&filtrar=DEFAULT&concepto=07-0018", , , , d['07-0018'], , , , , , ],
						
						["||Stock por Punto de Reposición", "<?=$_PARAMETRO["PATHSIA"]?>lg/reporte_stock_punto_reposicion.php?limit=0&filtrar=DEFAULT&concepto=07-0017", , , , d['07-0017'], , , , , , ],

						["||Movimientos de Almacen", "<?=$_PARAMETRO["PATHSIA"]?>lg/reporte_movimientos_de_almacen.php?limit=0&filtrar=DEFAULT&concepto=07-0004", , , , d['07-0004'], , , , , , ],

						["||Inventario Valorizado", "<?=$_PARAMETRO["PATHSIA"]?>lg/reporte_inventario_valorizado.php?limit=0&filtrar=DEFAULT&concepto=07-0005", , , , d['07-0005'], , , , , , ],
						
						["||Ítems sin Movimientos de Almacen", "<?=$_PARAMETRO["PATHSIA"]?>lg/reporte_item_sin_movimientos_de_almacen.php?limit=0&filtrar=DEFAULT&concepto=07-0013", , , , d['07-0013'], , , , , , ],
						
						["||Ítems con Movimientos de Almacen", "<?=$_PARAMETRO["PATHSIA"]?>lg/reporte_item_con_movimientos_de_almacen.php?limit=0&filtrar=DEFAULT&concepto=07-0014", , , , d['07-0014'], , , , , , ],

					

					["|Transacciones por Item", , , , , , , , , , , ],

						["||Consumo por Dependencia", "<?=$_PARAMETRO["PATHSIA"]?>lg/reporte_consumo_dependencia.php?limit=0&filtrar=DEFAULT&concepto=07-0003", , , , d['07-0003'], , , , , , ],

						

					["|Cierre Mensual", , , , , , , , , , , ],

						["||...", "xxx.php?limit=0&filtrar=DEFAULT&concepto=00-0000", , , , d['00-0000'], , , , , , ],

						

						

				["Maestros", , , , , , "0", , , , , ],

					["|Del Sistema SIA", , , , , , , , , , , ],

						["||Propios del Sistema", , , , , , , , , , , ],

							["|||Aplicaciones", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=aplicaciones_lista&filtrar=default&concepto=03-0002", , , , d['03-0002'], , , , , , ],

							["|||Par&aacute;metros", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=parametros_lista&filtrar=default&concepto=03-0003", , , , d['03-0003'], , , , , , ],

						["||Relacionados a Personas", , , , , , , , , , , ],

							["|||Personas", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=personas_lista&filtrar=default&concepto=03-0001", , , , d['03-0001'], , , , , , ],

							["|||Organismos", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=organismos_lista&filtrar=default&concepto=03-0004", , , , d['03-0004'], , , , , , ],

							["|||Dependencias", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=dependencias_lista&filtrar=default&concepto=03-0005", , , , d['03-0005'], , , , , , ],

						["||Relacionados a Contabilidad", , , , , , , , , , , ],

							["|||Plan de Cuentas", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=plan_cuentas_lista&filtrar=default&concepto=03-0025", , , , d['03-0025'], , , , , , ],

							["|||Grupos de Centros de Costos", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=grupo_centro_costos_lista&filtrar=default&concepto=03-0026", , , , d['03-0026'], , , , , , ],

							["|||Centros de Costos", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=centro_costos_lista&filtrar=default&concepto=03-0027", , , , d['03-0027'], , , , , , ],

						["||Relacionados a Presupuesto", , , , , , , , , , , ],

							["|||Tipos de Cuenta", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=tipo_cuenta_lista&filtrar=default&concepto=03-0034", , , , d['03-0034'], , , , , , ],

							["|||Clasificador Presupuestario", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=clasificador_presupuestario_lista&filtrar=default&concepto=03-0035", , , , d['03-0035'], , , , , , ],

						["||Otros Maestros", , , , , , , , , , , ],

							["|||Paises", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=paises_lista&filtrar=default&concepto=03-0004", , , , d['03-0004'], , , , , , ],

							["|||Estados", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=estados_lista&filtrar=default&concepto=03-0005", , , , d['03-0005'], , , , , , ],

							["|||Municipios", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=municipios_lista&filtrar=default&concepto=03-0006", , , , d['03-0006'], , , , , , ],

							["|||Ciudades", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=ciudades_lista&filtrar=default&concepto=03-0007", , , , d['03-0007'], , , , , , ],

							["|||Tipos de Pago", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=tipos_pago_lista&filtrar=default&concepto=03-0008", , , , d['03-0008'], , , , , , ],

							["|||Bancos", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=bancos_lista&filtrar=default&concepto=03-0009", , , , d['03-0009'], , , , , , ],

							

					["|Relativos a Compras", , , , , , , , , , , ],

						["||Formas de Pago", "<?=$_PARAMETRO["PATHSIA"]?>lg/formas_pago.php?limit=0&concepto=03-0033", , , , d['03-0033'], , , , , , ],

						["||Clasificaciones", "<?=$_PARAMETRO["PATHSIA"]?>lg/lg_clasificaciones_lista.php?limit=0&concepto=03-0011", , , , d['03-0011'], , , , , , ],

						["||-",, , , , , , , , , , ],

						["||-",, , , , , , , , , , ],

						["||Clasificaci&oacute;n de Commodities", "<?=$_PARAMETRO["PATHSIA"]?>lg/clasificacion_commodities.php?limit=0&concepto=03-0012", , , , d['03-0012'], , , , , , ],

						["||Commodities", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_commodity_lista&filtrar=default&concepto=03-0013", , , , d['03-0013'], , , , , , ],

						

					["|Relativos a Almacen", , , , , , , , , , , ],

						["||Almacenes", "<?=$_PARAMETRO["PATHSIA"]?>lg/almacenes.php?limit=0&concepto=03-0014", , , , d['03-0014'], , , , , , ],

						["||-",, , , , , , , , , , ],

						["||-",, , , , , , , , , , ],

						["||Tipos de Documentos", "<?=$_PARAMETRO["PATHSIA"]?>lg/tipos_documentos.php?limit=0&concepto=03-0015", , , , d['03-0015'], , , , , , ],

						["||Tipos de Transacciones", "<?=$_PARAMETRO["PATHSIA"]?>lg/tipos_transacciones.php?limit=0&concepto=03-0016", , , , d['03-0016'], , , , , , ],

					["|Relativos a Items", , , , , , , , , , , ],

						["||Items", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_item_lista&filtrar=default&concepto=03-0017", , , , d['03-0017'], , , , , , ],

						["||-",, , , , , , , , , , ],

						["||-",, , , , , , , , , , ],

						["||Items x Almacén", "<?=$_PARAMETRO["PATHSIA"]?>lg/items_x_almacen.php?limit=0&concepto=03-0037&filtrar=DEFAULT", , , , d['03-0037'], , , , , , ],

						["||-",, , , , , , , , , , ],

						["||-",, , , , , , , , , , ],

						["||Lineas", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_linea_lista&filtrar=default&concepto=03-0018", , , , d['03-0018'], , , , , , ],

						["||Familias", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_familia_lista&filtrar=default&concepto=03-0019", , , , d['03-0018'], , , , , , ],

						["||Sub-Familias", "<?=$_PARAMETRO["PATHSIA"]?>lg/gehen.php?anz=lg_subfamilia_lista&filtrar=default&concepto=03-0020", , , , d['03-0020'], , , , , , ],

						["||-",, , , , , , , , , , ],

						["||-",, , , , , , , , , , ],

						["||Tipos de Items", "<?=$_PARAMETRO["PATHSIA"]?>lg/tipos_items.php?limit=0&concepto=03-0021", , , , d['03-0021'], , , , , , ],

						["||Procedencias", "<?=$_PARAMETRO["PATHSIA"]?>lg/procedencias.php?limit=0&concepto=03-0022", , , , d['03-0022'], , , , , , ],

						["||Unidades de Medida", "<?=$_PARAMETRO["PATHSIA"]?>lg/unidades_medida.php?limit=0&concepto=03-0023", , , , d['03-0023'], , , , , , ],

						["||-",, , , , , , , , , , ],

						["||-",, , , , , , , , , , ],

						["||Marcas", "<?=$_PARAMETRO["PATHSIA"]?>lg/marcas.php?limit=0&concepto=03-0024", , , , d['03-0024'], , , , , , ],

					["|Relativos a Commodities", , , , , , , , , , , ],

						["||Tipos de Transacciones", "<?=$_PARAMETRO["PATHSIA"]?>lg/tipos_transacciones_commodities.php?limit=0&concepto=03-0038", , , , d['03-0038'], , , , , , ],

					["|Otros", , , , , , , , , , , ],

						["||Miscel&aacute;neos", "<?=$_PARAMETRO["PATHSIA"]?>lg/miscelaneos.php?concepto=03-0010", , , , d['03-0010'], , , , , , ],

						["||-",, , , , , , , , , , ],

						["||-",, , , , , , , , , , ],

						["||Régimen Fiscal", "<?=$_PARAMETRO["PATHSIA"]?>lg/regimen_fiscal.php?concepto=03-0029", , , , d['03-0029'], , , , , , ],

						["||Impuestos", "<?=$_PARAMETRO["PATHSIA"]?>lg/impuestos.php?concepto=03-0030", , , , d['03-0030'], , , , , , ],

						["||Tipos de Documentos Ctas. por Pagar", "<?=$_PARAMETRO["PATHSIA"]?>lg/tipo_doc_cxp.php?concepto=03-0031", , , , d['03-0031'], , , , , , ],

						["||Tipos de Servicio", "<?=$_PARAMETRO["PATHSIA"]?>lg/tipos_servicio.php?concepto=03-0032", , , , d['03-0032'], , , , , , ],

						["||-",, , , , , , , , , , ],

						["||-",, , , , , , , , , , ],

						["||Tipos de Voucher", "<?=$_PARAMETRO["PATHSIA"]?>lg/tipos_voucher.php?concepto=03-0028", , , , d['03-0028'], , , , , , ],

					

				["Admin.", , , , , , "0", , , , , ],

					["|Seguridad", , , , , , , , , , , ],

						["||Maesto de Usuarios", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_lista&lista=usuarios&filtrar=default&concepto=04-0001", , , , d['04-0001'], , , , , , ],

						["||Dar Autorizaciones a Usuarios", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_lista&lista=autorizaciones&filtrar=default&concepto=04-0002", , , , d['04-0002'], , , , , , ],

						["||Cambio de Clave","<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_cambio_clave&opcion=modificar&filtrar=default&concepto=04-0001", , , , d['04-0001'], , , , , , ],
					["|Seguridad Alterna", , , , , , , , , , , ],

						["||Dar Autorizaciones a Usuarios", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_lista&lista=alterna&filtrar=default&concepto=04-0003", , , , d['04-0003'], , , , , , ],

					["|Control de Periodos", "<?=$_PARAMETRO["PATHSIA"]?>lg/control_periodos.php?limit=0&concepto=04-0004&filtrar=DEFAULT", , , , d['04-0004'], , , , , , ],

					["|Cierre Mensual", "<?=$_PARAMETRO["PATHSIA"]?>lg/lg_cierre_mensual.php?limit=0&concepto=04-0005&filtrar=default", , , , d['04-0005'], , , , , , ],

			];

            dm_initFrame("frmSet", 0, 1, 0);



         </script>

		</td>

	</tr>

</table>

</body>

</html>
