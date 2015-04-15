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
				["Obligaciones", , , , , , "0", , , , , ],
					["|Nueva Obligación", "<?=$_PARAMETRO["PATHSIA"]?>ap/gehen.php?anz=ap_obligacion_form&opcion=nuevo", , , , d['01-0001'], , , , , , ],
					["|Revisar Obligaciones", "<?=$_PARAMETRO["PATHSIA"]?>ap/gehen.php?anz=ap_obligacion_lista&lista=revisar&filtrar=default&concepto=01-0002", , , , d['01-0002'], , , , , , ],
					["|Conformar Obligaciones", "<?=$_PARAMETRO["PATHSIA"]?>ap/gehen.php?anz=ap_obligacion_lista&lista=conformar&filtrar=default&concepto=01-0006", , , , d['01-0006'], , , , , , ],
					["|Aprobar Obligaciones", "<?=$_PARAMETRO["PATHSIA"]?>ap/gehen.php?anz=ap_obligacion_lista&lista=aprobar&filtrar=default&concepto=01-0003", , , , d['01-0003'], , , , , , ],

					["|Listar Obligaciones", "<?=$_PARAMETRO["PATHSIA"]?>ap/gehen.php?anz=ap_obligacion_lista&lista=todos&filtrar=default&concepto=01-0004", , , , d['01-0004'], , , , , , ],
					["|Facturación de Logística", "<?=$_PARAMETRO["PATHSIA"]?>ap/gehen.php?anz=ap_facturacion_lista&lista=todos&filtrar=default&concepto=01-0005", , , , d['01-0005'], , , , , , ],
						
				["Pagos", , , , , , "0", , , , , ],                        
					["|Lista de Ordenes de Pagos", "<?=$_PARAMETRO["PATHSIA"]?>ap/gehen.php?anz=ap_orden_pago_lista&lista=todos&filtrar=default&concepto=02-0001", , , , d['02-0001'], , , , , , ],
					["|Preparar Prepago", "<?=$_PARAMETRO["PATHSIA"]?>ap/gehen.php?anz=ap_orden_pago_lista&lista=prepago&filtrar=default&concepto=02-0002", , , , d['02-0002'], , , , , , ],
					["|Imprimir/Transferir", "<?=$_PARAMETRO["PATHSIA"]?>ap/gehen.php?anz=ap_orden_pago_transferir_lista&filtrar=default&concepto=02-0003", , , , d['02-0003'], , , , , , ],
					["|Lista de Pagos", "<?=$_PARAMETRO["PATHSIA"]?>ap/gehen.php?anz=ap_pago_lista&filtrar=default&concepto=02-0006", , , , d['02-0006'], , , , , , ],
					["|Entrega/Devolución de Cheques", , , , , , , , , , , ],
						["||Entrega de Cheques", "<?=$_PARAMETRO["PATHSIA"]?>ap/ap_cheques.php?filtrar=DEFAULT&accion=ENTREGA&limit=0&concepto=02-0004", , , , d['02-0004'], , , , , , ],
						["||Devolución de Cheques", "<?=$_PARAMETRO["PATHSIA"]?>ap/ap_cheques.php?filtrar=DEFAULT&accion=DEVOLUCION&limit=0&concepto=02-0005", , , , d['02-0005'], , , , , , ],
						["||Ingreso de Cheques Cobrados", "<?=$_PARAMETRO["PATHSIA"]?>ap/ap_cheques.php?filtrar=DEFAULT&accion=COBRADO&limit=0&concepto=02-0011", , , , d['02-0011'], , , , , , ],
					["|Transacciones Bancarias", , , , , , , , , , , ],
						["||Lista de Transacciones Bancarias", "<?=$_PARAMETRO["PATHSIA"]?>ap/ap_transacciones_bancarias.php?accion=LISTAR&limit=0&concepto=02-0007", , , , d['02-0007'], , , , , , ],
						["||Actualizar Transacciones Bancarias", "<?=$_PARAMETRO["PATHSIA"]?>ap/ap_transacciones_bancarias.php?accion=ACTUALIZAR&festado=PR&limit=0&concepto=02-0008", , , , d['02-0008'], , , , , , ],
						["||Saldo de Bancos", "<?=$_PARAMETRO["PATHSIA"]?>ap/ap_saldo_bancos.php?limit=0&filtrar=DEFAULT&concepto=02-0009", , , , d['02-0009'], , , , , , ],
					//["|Modificación Restringida de Pagos", "<?=$_PARAMETRO["PATHSIA"]?>ap/xxx.php?concepto=02-0010&limit=0&filtrar=DEFAULT", , , , d['02-0010'], , , , , , ],
					
				["Procesos", , , , , , "0", , , , , ],
					//["|Obligaciones", "<?=$_PARAMETRO["PATHSIA"]?>ap/xxx.php?concepto=03-0001&limit=0&filtrar=DEFAULT", , , , d['01-'], , , , , , ],
					//["|Pagos", "<?=$_PARAMETRO["PATHSIA"]?>ap/xxx.php?concepto=03-0002&limit=0&filtrar=DEFAULT", , , , d['01-'], , , , , , ],					
					["|Conciliación Bancaria", "<?=$_PARAMETRO["PATHSIA"]?>ap/gehen.php?anz=ap_conciliacion_bancaria&lista=todos&filtrar=default&concepto=03-0003", , , , d['03-0003'], , , , , , ],
					
				//["Consultas", , , , , , "0", , , , , ],
					//["|XXX", "<?=$_PARAMETRO["PATHSIA"]?>ap/xxx.php?concepto=04-0001&limit=0&filtrar=DEFAULT", , , , d['04-0001'], , , , , , ],
					
				["Reportes", , , , , , "0", , , , , ],
					["|Obligaciones", , , , , , , , , , , ],
						["||Lista de Obligaciones", "<?=$_PARAMETRO["PATHSIA"]?>ap/ap_lista_obligaciones_pdf_filtro.php?concepto=05-0001&limit=0&filtrar=DEFAULT", , , , d['05-0001'], , , , , , ],
						["||Obligaciones Pendientes a Proveedores", "<?=$_PARAMETRO["PATHSIA"]?>ap/ap_obligaciones_pendientes_provedores_pdf_filtro.php?concepto=05-0002&limit=0&filtrar=DEFAULT", , , , d['05-0002'], , , , , , ],
						["||Comparación de Pagos y Obligaciones", "<?=$_PARAMETRO["PATHSIA"]?>ap/ap_obligaciones_pagos_pdf_filtro.php?concepto=05-0003&limit=0&filtrar=DEFAULT", , , , d['05-0003'], , , , , , ],
						
						["||Registro de Compras", "<?=$_PARAMETRO["PATHSIA"]?>ap/gehen.php?anz=ap_registro_compra_filtro&concepto=05-0004&limit=0&filtrar=default", , , , d['05-0004'], , , , , , ],
						
						["||Obligaciones Vs. Distribución Contable", "<?=$_PARAMETRO["PATHSIA"]?>ap/ap_obligaciones_contable_pdf_filtro.php?filtrar=default&concepto=05-0005", , , , d['05-0005'], , , , , , ],
						["||Estado de Cuenta", "<?=$_PARAMETRO["PATHSIA"]?>ap/ap_obligaciones_estado_cuenta_pdf_filtro.php?filtrar=default&concepto=05-0006", , , , d['05-0006'], , , , , , ],
						["||Documentos Emitidos / Cheques Girados", "<?=$_PARAMETRO["PATHSIA"]?>ap/ap_obligaciones_documentos_pdf_filtro.php?filtrar=default&concepto=05-0007", , , , d['05-0007'], , , , , , ],
						
					["|Pagos", , , , , , , , , , , ],
						["||Ordenes de Pago", "<?=$_PARAMETRO["PATHSIA"]?>ap/gehen.php?anz=ap_lista_orden_pago_pdf_filtro&concepto=05-0008&limit=0&filtrar=default", , , , d['05-0008'], , , , , , ],
						["||Pagos a Proveedores", "<?=$_PARAMETRO["PATHSIA"]?>ap/gehen.php?anz=ap_pagos_proveedores_pdf_filtro&concepto=05-0009&limit=0&filtrar=default", , , , d['05-0009'], , , , , , ],
						
					["|Cheques", , , , , , , , , , , ],
						["||Libro de Cheques", "<?=$_PARAMETRO["PATHSIA"]?>ap/gehen.php?anz=ap_libro_cheque_pdf_filtro&concepto=05-0010&limit=0&filtrar=default", , , , d['05-0010'], , , , , , ],
						["||Cheques x Estado de Entrega", "<?=$_PARAMETRO["PATHSIA"]?>ap/gehen.php?anz=ap_cheque_estado_entrega_pdf_filtro&concepto=05-0011&limit=0&filtrar=default", , , , d['05-0011'], , , , , , ],
						
					["|Bancos", , , , , , , , , , , ],
						["||Libro Bancos", "<?=$_PARAMETRO["PATHSIA"]?>ap/gehen.php?anz=ap_libro_banco_pdf_filtro&concepto=05-0012&limit=0&filtrar=default", , , , d['05-0012'], , , , , , ],
						["||Listado de Transacciones", "<?=$_PARAMETRO["PATHSIA"]?>ap/gehen.php?anz=ap_listado_transaccion_pdf_filtro&concepto=05-0013&limit=0&filtrar=default", , , , d['05-0013'], , , , , , ],
					
				["Otros", , , , , , "0", , , , , ],
					["|Caja Chica", , , , , , , , , , , ],
						["||Listar Caja Chica", "<?=$_PARAMETRO["PATHSIA"]?>ap/ap_caja_chica_listar.php?accion=LISTAR&filtrar=DEFAULT&limit=0&concepto=06-0001", , , , d['06-0001'], , , , , , ],
						["||Aprobar Caja Chica", "<?=$_PARAMETRO["PATHSIA"]?>ap/ap_caja_chica_listar.php?accion=APROBAR&filtrar=DEFAULT&limit=0&concepto=06-0002", , , , d['06-0002'], , , , , , ],
						//["||Lista de Requerimientos", "<?=$_PARAMETRO["PATHSIA"]?>ap/ap_caja_chica_requerimientos.php?filtrar=DEFAULT&limit=0&concepto=06-0005", , , , d['06-0005'], , , , , , ],
						
					//["|Reportes de Gastos", "<?=$_PARAMETRO["PATHSIA"]?>ap/xxx.php?concepto=06-0002&limit=0&filtrar=DEFAULT", , , , d['01-0001'], , , , , , ],
					["|Impuestos", , , , , , , , , , , ],
						["||Importar Registro de Compras", "<?=$_PARAMETRO["PATHSIA"]?>ap/gehen.php?anz=ap_registro_compra_importar&concepto=06-0003&limit=0&filtrar=default", , , , d['06-0003'], , , , , , ],
						["||Control de Registro de Compras", "<?=$_PARAMETRO["PATHSIA"]?>ap/gehen.php?anz=ap_registro_compra_lista&concepto=06-0004&limit=0&filtrar=default", , , , d['06-0004'], , , , , , ],
						
				["Maestros", , , , , , "0", , , , , ],
					["|Del Sistema SIA", , , , , , , , , , , ],
						["||Propios del Sistema", , , , , , , , , , , ],
							["|||Aplicaciones", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=aplicaciones_lista&filtrar=default&concepto=07-0002", , , , d['07-0002'], , , , , , ],
							["|||Par&aacute;metros", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=parametros_lista&filtrar=default&concepto=07-0003", , , , d['07-0003'], , , , , , ],
						
						["||Relacionados a Personas", , , , , , , , , , , ],
							["|||Personas", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=personas_lista&filtrar=default&concepto=07-0001", , , , d['07-0001'], , , , , , ],
							["|||Organismos", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=organismos_lista&filtrar=default&concepto=07-0029", , , , d['07-0029'], , , , , , ],
							["|||Dependencias", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=dependencias_lista&filtrar=default&concepto=07-0030", , , , d['07-0030'], , , , , , ],
						
						["||Relacionados a Contabilidad", , , , , , , , , , , ],
							["|||Plan de Cuentas", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=plan_cuentas_lista&filtrar=default&concepto=07-0010", , , , d['07-0010'], , , , , , ],
							["|||Grupos de Centros de Costos", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=grupo_centro_costos_lista&filtrar=default&concepto=07-0010", , , , d['07-0010'], , , , , , ],
							["|||Centros de Costos", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=centro_costos_lista&filtrar=default&concepto=07-0011", , , , d['07-0011'], , , , , , ],
						
						["||Relacionados a Presupuesto", , , , , , , , , , , ],
							["|||Tipos de Cuenta", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=tipo_cuenta_lista&filtrar=default&concepto=07-0014", , , , d['07-0014'], , , , , , ],
							["|||Clasificador Presupuestario", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=clasificador_presupuestario_lista&filtrar=default&concepto=07-0015", , , , d['07-0015'], , , , , , ],
						
						["||Otros Maestros", , , , , , , , , , , ],
							["|||Paises", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=paises_lista&filtrar=default&concepto=07-0004", , , , d['07-0004'], , , , , , ],
							["|||Estados", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=estados_lista&filtrar=default&concepto=07-0005", , , , d['07-0005'], , , , , , ],
							["|||Municipios", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=municipios_lista&filtrar=default&concepto=07-0006", , , , d['07-0006'], , , , , , ],
							["|||Ciudades", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=ciudades_lista&filtrar=default&concepto=07-0007", , , , d['07-0007'], , , , , , ],
							["|||Tipos de Pago", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=tipos_pago_lista&filtrar=default&concepto=07-0008", , , , d['07-0008'], , , , , , ],
							["|||Bancos", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=bancos_lista&filtrar=default&concepto=07-0009", , , , d['07-0009'], , , , , , ],
							["|||Unidad Tributaria", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=unidad_tributaria_lista&filtrar=default&concepto=07-0029", , , , d['07-0029'], , , , , , ],
							
					["|Relacionado a Obligaciones", , , , , , , , , , , ],
						["||Régimen Fiscal", "<?=$_PARAMETRO["PATHSIA"]?>ap/regimen_fiscal.php?concepto=07-0017", , , , d['07-0017'], , , , , , ],
						
						["||Impuestos", "<?=$_PARAMETRO["PATHSIA"]?>ap/gehen.php?anz=ap_impuestos_lista&filtrar=default&concepto=07-0018", , , , d['07-0018'], , , , , , ],
						
						["||Tipos de Documentos Ctas. x Pagar", "<?=$_PARAMETRO["PATHSIA"]?>ap/gehen.php?anz=ap_tipo_documento_cxp_lista&filtrar=default&concepto=07-0019", , , , d['07-0019'], , , , , , ],
						
						["||Tipos de Servicio", "<?=$_PARAMETRO["PATHSIA"]?>ap/tipos_servicio.php?concepto=07-0020", , , , d['07-0020'], , , , , , ],
						
					["|Relacionado a Pagos", , , , , , , , , , , ],
						["||Cuentas Bancarias", "<?=$_PARAMETRO["PATHSIA"]?>ap/ap_cuentas_bancarias.php?concepto=07-0022", , , , d['07-0022'], , , , , , ],
						["||Asignación de Cuentas Bancarias por Defecto", "<?=$_PARAMETRO["PATHSIA"]?>ap/ap_cuentas_bancarias_default.php?concepto=07-0023", , , , d['00-0023'], , , , , , ],
						["||-",, , , , "_", , , , , , ],
						["||-",, , , , "_", , , , , , ],
						["||Tipo de Transacción Bancaria", "<?=$_PARAMETRO["PATHSIA"]?>ap/ap_tipo_transaccion_bancaria.php?concepto=07-0024", , , , d['07-0024'], , , , , , ],
						
					["|Caja Chica y Reportes de Gastos", , , , , , , , , , , ],
						["||Clasificación de Gastos", "<?=$_PARAMETRO["PATHSIA"]?>ap/ap_clasificacion_gastos.php?limit=0&filtrar=DEFAULT&concepto=07-0027", , , , d['07-0027'], , , , , , ],
						["||Grupo de Concepto de Gastos", "<?=$_PARAMETRO["PATHSIA"]?>ap/ap_grupo_concepto_gastos.php?limit=0&filtrar=DEFAULT&concepto=07-0025", , , , d['07-0025'], , , , , , ],
						["||Concepto de Gastos", "<?=$_PARAMETRO["PATHSIA"]?>ap/ap_concepto_gastos.php?limit=0&filtrar=DEFAULT&concepto=07-0026", , , , d['07-0026'], , , , , , ],
						["||Autorización de Caja Chica", "<?=$_PARAMETRO["PATHSIA"]?>ap/ap_autorizacion_caja_chica.php?limit=0&filtrar=DEFAULT&concepto=07-0028", , , , d['07-0028'], , , , , , ],
						
					["|Otros", , , , , , , , , , , ],
						["||Miscel&aacute;neos", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=miscelaneos_lista&filtrar=default&concepto=07-0016", , , , d['07-0016'], , , , , , ],
						["||-",, , , , "_", , , , , , ],
						["||-",, , , , "_", , , , , , ],
						["||Clasificación de Documentos", "<?=$_PARAMETRO["PATHSIA"]?>ap/ap_documentos_clasificacion.php?concepto=07-0021", , , , d['07-0021'], , , , , , ],
						["||-",, , , , , , , , , , ],
						["||-",, , , , , , , , , , ],
						["||Tipos de Voucher", "<?=$_PARAMETRO["PATHSIA"]?>ap/tipos_voucher.php?concepto=07-0013", , , , d['07-0013'], , , , , , ],
					
				["Admin.", , , , , , "0", , , , , ],
					["|Seguridad", , , , , , , , , , , ],
						["||Maesto de Usuarios", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_lista&lista=usuarios&filtrar=default&concepto=06-0001", , , , d['06-0001'], , , , , , ],
						["||Dar Autorizaciones a Usuarios", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_lista&lista=autorizaciones&filtrar=default&concepto=06-0002", , , , d['06-0002'], , , , , , ],
					["|Seguridad Alterna", , , , , , , , , , , ],
						["||Dar Autorizaciones a Usuarios", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_lista&lista=alterna&filtrar=default&concepto=06-0003", , , , d['06-0003'], , , , , , ],
					
					["|Generación de Vouchers", , , , , , , , , , , ],
						["||Provisión de Obligaciones", "<?=$_PARAMETRO["PATHSIA"]?>ap/gehen.php?anz=ap_generar_vouchers_provision&limit=0&filtrar=default&concepto=06-0004", , , , d['06-0004'], , , , , , ],
						["||Pagos", "<?=$_PARAMETRO["PATHSIA"]?>ap/gehen.php?anz=ap_generar_vouchers_pagos&limit=0&filtrar=default&concepto=06-0005", , , , d['06-0005'], , , , , , ],
						["||Transacciones Bancarias", "<?=$_PARAMETRO["PATHSIA"]?>ap/gehen.php?anz=ap_generar_vouchers_transacciones&limit=0&filtrar=default&concepto=06-0006", , , , d['06-0006'], , , , , , ],
			];
            dm_initFrame("frmSet", 0, 1, 0);

         </script>
		</td>
	</tr>
</table>
</body>
</html>
