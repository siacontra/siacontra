<?php
include("../lib/fphp.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
		<!-- Deluxe Menu -->
    <script type="text/javascript">var dmWorkPath = "data.files/";
    
    console.log(dmWorkPath);
    </script>
    <script type="text/javascript" src="data.files/dmenu.js"></script>
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
["Vouchers", , , , , , "0", , , , , ],
  ["|Nuevo Voucher","<?=$_PARAMETRO['PATHSIA']?>ac/ac_voucher_form.php?opcion=nuevo&concepto=01-0001&return=framemain", , , ,d['01-0001'] , , , , , , ],
  ["|Lista de Voucher","<?=$_PARAMETRO['PATHSIA']?>ac/ac_voucher_lista.php?concepto=01-0002&limit=0&filtrar=default", , , ,d['01-0002'] , , , , , , ],
  ["|Aprobar Voucher","<?=$_PARAMETRO['PATHSIA']?>ac/ac_voucher_aprobar.php?concepto=01-0003&limit=0&filtrar=default", , , ,d['01-0003'] , , , , , , ],
  ["|Modificación Restringida de Voucher","<?=$_PARAMETRO['PATHSIA']?>ac/ac_voucher_modificacion.php?concepto=01-0004&limit=0&filtrar=default", , , ,d['01-0004'] , , , , , , ],
						
["Procesos", , , , , , "0", , , , , ],
  ["|XXX", "xxx.php?concepto=02-0001&limit=0&filtrar=DEFAULT", , , , , , , , , , ],
						
["Consultas", , , , , , "0", , , , , ],
  ["|Saldo de Cuenta","<?=$_PARAMETRO['PATHSIA']?>ac/ac_consultas_saldo_cuenta.php?concepto=03-0001&filtrar=default", , , ,d['03-0001'] , , , , , , ],
  ["|Otras Consultas de Saldo", , , , , , , , , , , ],
	["||Saldo de Cuenta Detallado","<?=$_PARAMETRO['PATHSIA']?>ac/ac_consultas_saldo_detallado.php?concepto=03-0002&filtrar=default", , , ,d['03-0002'] , , , , , , ],
	["||Saldo Histórico Acumulado","<?=$_PARAMETRO['PATHSIA']?>ac/ac_consultas_saldo_historico.php?concepto=03-0003&filtrar=default", , , ,d['03-0003'] , , , , , , ],
	["||Saldo a una Fecha Determinada","<?=$_PARAMETRO['PATHSIA']?>ac/ac_consultas_saldo_determinado.php?concepto=03-0004&filtrar=default", , , , d['03-0004'], , , , , , ],
  ["|Vouchers por Cuenta","<?=$_PARAMETRO['PATHSIA']?>ac/ac_consulta_saldo.php?concepto=03-0005&limit=0&filtrar=DEFAULT", , , , d['03-0005'], , , , , , ],
						
["Reportes", , , , , , "0", , , , , ],
  ["|Balance de Comprobación", "<?=$_PARAMETRO['PATHSIA']?>ac/rp_balancecomprobacion.php?concepto=04-0001&limit=0&filtrar=DEFAULT", , , ,d['04-0001'] , , , , , , ],
  ["|Balance de Comprobación Sumas y Saldos","<?=$_PARAMETRO['PATHSIA']?>ac/rp_balancesumassaldos.php?concepto=04-0002&limit=0&filtrar=DEFAULT", , , ,d['04-0002'], , , , , , ],
  ["|Libro Diario", "<?=$_PARAMETRO['PATHSIA']?>ac/rp_librodiario.php?concepto=04-0003&limit=0&filtrar=DEFAULT", , , ,d['04-0003'] , , , , , , ],
  ["|Libro Mayor", "<?=$_PARAMETRO['PATHSIA']?>ac/rp_libromayor.php?concepto=04-0004&limit=0&filtrar=DEFAULT", , , , d['04-0004'], , , , , , ],
						
                    ["Otros", , , , , , "0", , , , , ],
                        ["|Estados Financieros", "ac_estados_financieros.php?concepto=05-0001&limit=0&filtrar=DEFAULT", , , , , , , , , , ],
					
					
		/// REALIZANDO MODIFICACIONES
	
	/// REALIZANDO MODIFICACIONES	
	/// REALIZANDO MODIFICACIONES
	
	
["Maestros", , , , , , "0", , , , , ],
  ["|Del Sistema SIA", , , , , , , , , , , ],
	
	 ["||Propios del Sistema", , , , , , , , , , , ],
		["|||Aplicaciones", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=aplicaciones_lista&filtrar=default&concepto=06-0001", , , , d['06-0001'], , , , , , ],
		["|||Par&aacute;metros", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=parametros_lista&filtrar=default&concepto=06-0002", , , , d['06-0002'], , , , , , ],
	 ["||Relacionados a Personas", , , , , , , , , , , ],
		["|||Personas", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=personas_lista&filtrar=default&concepto=06-0003", , , , d['06-0003'], , , , , , ],
		["|||Organismos", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=organismos_lista&filtrar=default&concepto=06-0004", , , , d['06-0004'], , , , , , ],
		["|||Dependencias", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=dependencias_lista&filtrar=default&concepto=06-0005", , , , d['06-0005'], , , , , , ],
	 ["||Relacionados a Contabilidad", , , , , , , , , , , ],
		["|||Plan de Cuentas", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=plan_cuentas_lista&filtrar=default&concepto=06-0006", , , , d['06-0006'], , , , , , ],
		["|||Grupos de Centros de Costos", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=grupo_centro_costos_lista&filtrar=default&concepto=06-0007", , , , d['06-0007'], , , , , , ],
		["|||Centros de Costos","<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=centro_costos_lista&filtrar=default&concepto=06-0008", , , , d['06-0008'], , , , , , ],
	["||Relacionados a Presupuesto", , , , , , , , , , , ],
		["|||Tipos de Cuenta","<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=tipo_cuenta_lista&filtrar=default&concepto=06-0009", , , , d['06-0009'], , , , , , ],
		["|||Clasificador Presupuestario","<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=clasificador_presupuestario_lista&filtrar=default&concepto=06-0010", , , , d['06-0010'], , , , , , ],
	["||Otros Maestros", , , , , , , , , , , ],
		["|||Paises", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=paises_lista&filtrar=default&concepto=06-0011", , , , d['06-0011'], , , , , , ],
		["|||Estados", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=estados_lista&filtrar=default&concepto=06-0012", , , , d['06-0012'], , , , , , ],
		["|||Municipios", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=municipios_lista&filtrar=default&concepto=06-0013", , , , d['06-0013'], , , , , , ],
		["|||Ciudades", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=ciudades_lista&filtrar=default&concepto=06-0014", , , , d['06-0014'], , , , , , ],
		["|||Tipos de Pago", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=tipos_pago_lista&filtrar=default&concepto=06-0015", , , , d['06-0015'], , , , , , ],
		["|||Bancos", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=bancos_lista&filtrar=default&concepto=06-0016", , , , d['06-0016'], , , , , , ],
		["|||Unmidad Tributaria", "<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=unidad_tributaria_lista&filtrar=default&concepto=06-0017", , , , d['06-0017'], , , , , , ],
		
		
  
  ["|De Contabilidades", , , , , , , , , , , ],
  
	 ["||Libro Contable", "<?=$_PARAMETRO['PATHSIA']?>ac/af_librocontable.php?limit=0&filtrar=default&concepto=06-0017", , , ,d['06-0017'] , , , , , , ],
	 ["||Contabilidades", "<?=$_PARAMETRO['PATHSIA']?>ac/af_contabilidades.php?limit=0&filtrar=default&concepto=06-0018", , , ,d['06-0018'] , , , , , , ],
  ["|Otros", , , , , , , , , , , ],
	 ["||Sistemas Fuente", "<?=$_PARAMETRO['PATHSIA']?>ac/ac_sistemas_fuentes.php?limit=0&filtrar=default&concepto=06-0015", , , ,d['06-0015'] , , , , , , ],
	 ["||Modelo de Voucher", "<?=$_PARAMETRO['PATHSIA']?>ac/ac_modelo_voucher.php?limit=0&filtrar=default&concepto=06-0016", , , ,d['06-0016'], , , , , , ],
	 ["||-",, , , , , , , , , , ],
	 ["||-",, , , , , , , , , , ],
	 ["||Tipos de Cambios de Cierre e Índices de Ajuste por Inflación","<?=$_PARAMETRO['PATHSIA']?>ac/ac_sistemas_fuentes.php?limit=0&filtrar=default&concepto=06-0019", , , ,d['06-0019'] , , , , , , ],
	 ["||Control de Cierres Mensuales","<?=$_PARAMETRO['PATHSIA']?>ac/ac_control_cierres_mensuales.php?filtrar=DEFAULT&concepto=06-0020", , , ,d['06-0020'] , , , , , , ],
	 ["||Últimos Vouchers Generados", "<?=$_PARAMETRO['PATHSIA']?>ac/ac_ultimos_vouchers_generados.php?concepto=06-0021&filtrar=DEFAULT", , , ,d['06-0021'] , , , , , , ],
	 ["||-",, , , , , , , , , , ],
	 ["||-",, , , , , , , , , , ],
	 ["||Miscel&aacute;neos", "<?=$_PARAMETRO['PATHSIA']?>ac/miscelaneos.php?concepto=06-0014", , , , , , , , , , ],
                            
							
							
							/*["||Relacionados a Personas", , , , , , , , , , , ],
                                ["|||Personas", "personas.php?limit=0&concepto=06-0001", , , , , , , , , , ],
                            ["||Relacionados a Contabilidad", , , , , , , , , , , ],
                                ["|||Plan de Cuentas", "plan_cuentas.php?concepto=06-0010", , , , , , , , , , ],
                                ["|||Grupos de Centros de Costos", "grupos_centros_costos.php?concepto=06-0011", , , , , , , , , , ],
                                ["|||Centros de Costos", "centros_costos.php?concepto=06-0012", , , , , , , , , , ],
                                ["|||Tipos de Voucher", "tipos_voucher.php?concepto=06-0013", , , , , , , , , , ],*/
                            /*["||Relacionado a Presupuesto", , , , , , , , , , , ],
                                ["|||Tipos de Cuentas", "pv_tipo_cuenta.php?concepto=06-0022", , , , , , , , , , ],
                                ["|||Clasificador Presupuestario", "pv_clasificador_presupuestario.php?concepto=06-0023", , , , , , , , , , ],
                            ["||Propios del Sistema", , , , , , , , , , , ],
                                ["|||Aplicaciones", "aplicaciones.php?concepto=06-0002", , , , , , , , , , ],
                                ["|||Par&aacute;metros", "parametros.php?concepto=06-0003", , , , , , , , , , ],
                            ["||Otros Maestros", , , , , , , , , , , ],
                                ["|||Paises", "paises.php?concepto=06-0004", , , , , , , , , , ],
                                ["|||Estados", "estados.php?concepto=06-0005", , , , , , , , , , ],
                                ["|||Municipios", "municipios.php?concepto=06-0006", , , , , , , , , , ],
                                ["|||Ciudades", "ciudades.php?limit=0&concepto=06-0007", , , , , , , , , , ],
                                ["|||Tipos de Pago", "tipospago.php?concepto=06-0008", , , , , , , , , , ],
                                ["|||Bancos", "bancos.php?concepto=06-0009", , , , , , , , , , ],*/
["Admin.", , , , , , "0", , , , , ],
  ["|Seguridad", , , , , , , , , , , ],
	["||Maesto de Usuarios","<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_lista&lista=usuarios&filtrar=default&concepto=04-0001", , , , d['04-0001'], , , , , , ],
	["||Dar Autorizaciones a Usuarios","<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_lista&lista=autorizaciones&filtrar=default&concepto=04-0002", , , , d['04-0002'], , , , , , ],
	["||Cambio de Clave","<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_cambio_clave&opcion=modificar&filtrar=default&concepto=04-0001", , , , d['04-0001'], , , , , , ],
  ["|Seguridad Alterna", , , , , , , , , , , ],
	["||Dar Autorizaciones a Usuarios","<?=$_PARAMETRO["PATHSIA"]?>comunes/gehen.php?anz=usuarios_lista&lista=alterna&filtrar=default&concepto=04-0003", , , , d['04-0003'], , , , , , ],
  ["|Actualización de Balance", , , , , , , , , , , ],
   ["||Mayorizar","<?=$_PARAMETRO["PATHSIA"]?>ac/ac_voucher_mayorizar.php?limit=0&concepto=04-0004&filtrar=default&opcion=mayorizar", , , , d['04-0004'], , , , , , ],
   ["||Desmayorizar","<?=$_PARAMETRO["PATHSIA"]?>ac/ac_voucher_mayorizar.php?limit=0&concepto=04-0005&filtrar=default&opcion=desmayorizar", , , ,d['04-0005'] , , , , , , ],
  ["|Confirmación de Cierre Mensual","<?=$_PARAMETRO["PATHSIA"]?>ac/ac_confirmacion_cierre_mensual.php?concepto=04-0006&limit=0&filtrar=DEFAULT", , , ,d['04-0006'] , , , , , , ],
  ["|Cierre Anual","<?=$_PARAMETRO["PATHSIA"]?>ac/ac_cierre_anual.php?concepto=04-0007&limit=0&filtrar=DEFAULT", , , , d['04-0007'], , , , , , ],
  
  
 /* ["|Control de Periodos", "<?=$_PARAMETRO["PATHSIA"]?>lg/control_periodos.php?limit=0&concepto=04-0004&filtrar=DEFAULT", , , , d['04-0004'], , , , , , ],
  ["|Cierre Mensual", "<?=$_PARAMETRO["PATHSIA"]?>lg/lg_cierre_mensual.php?limit=0&concepto=04-0005&filtrar=default", , , , d['04-0005'], , , , , , ],
];	*/							
                        
								
                        
                        
                  /*  ["Admin.", , , , , , "0", , , , , ],
                        ["|Seguridad", , , , , , , , , , , ],
                            ["||Maesto de Usuarios", "usuarios.php?limit=0&concepto=07-0001", , , , , , , , , , ],
                            ["||Dar Autorizaciones a Usuarios", "usuarios_autorizaciones.php?limit=0&concepto=07-0002", , , , , , , , , , ],
                        ["|Seguridad Alterna", , , , , , , , , , , ],
                            ["||Dar Autorizaciones a Usuarios", "seguridad_alterna.php?limit=0&concepto=07-0003", , , , , , , , , , ],
                        ["|Actualización de Balance", , , , , , , , , , , ],
                            ["||Mayorizar", "ac_voucher_mayorizar.php?limit=0&concepto=07-0006&filtrar=default&opcion=mayorizar", , , , , , , , , , ],
                            ["||Desmayorizar", "ac_voucher_mayorizar.php?limit=0&concepto=07-0007&filtrar=default&opcion=desmayorizar", , , , , , , , , , ],
                        ["|Confirmación de Cierre Mensual", "ac_confirmacion_cierre_mensual.php?concepto=07-0004&limit=0&filtrar=DEFAULT", , , , , , , , , , ],
                        ["|Cierre Anual", "ac_cierre_anual.php?concepto=07-0005&limit=0&filtrar=DEFAULT", , , , , , , , , , ],*/
                ];
            
            dm_initFrame("frmSet", 0, 1, 0);

         </script>
		</td>
	</tr>
</table>
</body>
</html>
