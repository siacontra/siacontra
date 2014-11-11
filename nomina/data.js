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
		["Gesti&oacute;n", , , , , , "0", , , , , ],
			["|Empleados", "empleados.php?limit=0", , , , , , , , , , ],
			
		["Maestros", , , , , , "0", , , , , ],
			["|Del Sistema SIA", , , , , , , , , , , ],
				["||Personas", "personas.php?limit=0", , , , , , , , , , ],
				["||Propios del Sistema", , , , , , , , , , , ],
					["|||Aplicaciones", "aplicaciones.php", , , , , , , , , , ],
					["|||Par&aacute;metros", "parametros.php", , , , , , , , , , ],
				["||Otros Maestros", , , , , , , , , , , ],
					["|||Paises", "paises.php", , , , , , , , , , ],
					["|||Estados", "estados.php", , , , , , , , , , ],
					["|||Municipios", "municipios.php", , , , , , , , , , ],
					["|||Ciudades", "ciudades.php?limit=0", , , , , , , , , , ],
					["|||Tipos de Pago", "tipospago.php", , , , , , , , , , ],
					["|||Bancos", "bancos.php", , , , , , , , , , ],
			["|Organizaci&oacute;n", , , , , , , , , , , ],
				["||Organismos", "organismos.php", , , , , , , , , , ],
				["||Dependencias", "dependencias.php", , , , , , , , , , ],
				["||-",, , , , , , , , , , ],
				["||-",, , , , , , , , , , ],
				["||Grupo Ocupacional", "grupoocupacional.php", , , , , , , , , , ],						
				["||Serie Ocupacional", "serieocupacional.php", , , , , , , , , , ],
				["||Tipos de Cargo", "tiposcargo.php", , , , , , , , , , ],
				["||Nivel de Tipos de Cargo", "nivelcargo.php", , , , , , , , , , ],
				["||Cargos", "cargos.php", , , , , , , , , , ],
			["|Capacitaci&oacute;n", , , , , , , , , , , ],
				["||Grado de Instrucci&oacute;n", "ginstruccion.php", , , , , , , , , , ],
				["||Profesiones", "profesiones.php", , , , , , , , , , ],
				["||Centros de Estudios", "centroestudio.php", , , , , , , , , , ],
				["||Cursos", "cursos.php", , , , , , , , , , ],
				["||Idiomas", "idiomas.php", , , , , , , , , , ],
			["|Relaciones Laborales", , , , , , , , , , , ],
				["||Tipos de Trabajador", "tipostrabajador.php", , , , , , , , , , ],
				["||Tipos de N&oacute;mina", "tiposnomina.php", , , , , , , , , , ],
				["||Perfiles de N&oacute;mina", "tiposperfil.php", , , , , , , , , , ],
				["||Tipos de Contrato", "tiposcontrato.php", , , , , , , , , , ],
				["||Formatos de Contrato", "formatoscontrato.php", , , , , , , , , , ],
				["||Motivos de Cese", "motivoscese.php", , , , , , , , , , ],
			["|Afiliaciones", , , , , , , , , , , ],
				["||Cooperativas", "cooperativas.php", , , , , , , , , , ],
				["||Sindicatos", "sindicatos.php", , , , , , , , , , ],
				["||Tipos de Seguro", "tiposseguro.php", , , , , , , , , , ],
				["||Planes de Seguro", "planesseguro.php", , , , , , , , , , ],
			["|Clima Laboral", , , , , , , , , , , ],
				["||Preguntas", "preguntas.php", , , , , , , , , , ],
				["||Plantillas", "plantillas.php", , , , , , , , , , ],
			["|Gesti&oacute;n de Competencias", , , , , , , , , , , ],
				["||Evaluaciones", "evaluaciones.php", , , , , , , , , , ],
				["||Tipos de Evaluaci&oacute;n", "tevaluacion.php", , , , , , , , , , ],
				["||Grupos de Competencias", "aevaluaciones.php", , , , , , , , , , ],
				["||Grado de Calificaciones", "gcompetencias.php", , , , , , , , , , ],
				["||Competencias", "competencias.php", , , , , , , , , , ],
				["||Plantilla de Competencias", "pcompetencias.php", , , , , , , , , , ],
			["|N&oacute;minas", , , , , , , , , , , ],
				["||Tipos de Proceso", "tiposproceso.php", , , , , , , , , , ],
				["||Formulas", "formulas.php", , , , , , , , , , ],
				["||Conceptos", "conceptos.php", , , , , , , , , , ],
			["|Otros", , , , , , , , , , , ],
				["||Miscel&aacute;neos", "miscelaneos.php", , , , , , , , , , ],
			
		["Admin.", , , , , , "0", , , , , ],
			["|Seguridad", , , , , , , , , , , ],
				["||Maesto de Usuarios", "usuarios.php?limit=0", , , , , , , , , , ],
				["||Conceptos de Seguridad", "usuarios_conceptos.php?limit=0", , , , , , , , , , ],
				["||Dar Autorizaciones a Usuarios", "usuarios_autorizaciones.php?limit=0", , , , , , , , , , ],
			["|Seguridad Alterna", , , , , , , , , , , ],
				["||Dar Autorizaciones a Usuarios", "seguridad_alterna.php?limit=0", , , , , , , , , , ],
	];	
}

else if (admin=="N" && opciones!="N") {
	opciones=opciones.split(";");
	for (i=0; i<opciones.length; i++) {
		var items=opciones[i].split(", ", ".");
		if (items[1]=="N") disabled[i]="_"; else disabled[i]="";
	}
	
	i=0;
	var menuItems = [
		["Gesti&oacute;n", , , , , , "0", , , , , ],
			["|Empleados", "empleados.php?limit=0", , , , disabled[i], , , , , , ],
			
		["Maestros", , , , , , "0", , , , , ],
			["|Del Sistema SIA", , , , , , , , , , , ],
				["||Personas", "personas.php?limit=0", , , , disabled[++i], , , , , , ],
				["||Propios del Sistema", , , , , , , , , , , ],
					["|||Aplicaciones", "aplicaciones.php", , , , disabled[++i], , , , , , ],
					["|||Par&aacute;metros", "parametros.php", , , , disabled[++i], , , , , , ],
				["||Otros Maestros", , , , , , , , , , , ],
					["|||Paises", "paises.php", , , , disabled[++i], , , , , , ],
					["|||Estados", "estados.php", , , , disabled[++i], , , , , , ],
					["|||Municipios", "municipios.php", , , , disabled[++i], , , , , , ],
					["|||Ciudades", "ciudades.php?limit=0", , , , disabled[++i], , , , , , ],
					["|||Tipos de Pago", "tipospago.php", , , , disabled[++i], , , , , , ],
					["|||Bancos", "bancos.php", , , , disabled[++i], , , , , , ],
			["|Organizaci&oacute;n", , , , , , , , , , , ],
				["||Organismos", "organismos.php", , , , disabled[++i], , , , , , ],
				["||Dependencias", "dependencias.php", , , , disabled[++i], , , , , , ],
				["||-",, , , , , , , , , , ],
				["||-",, , , , , , , , , , ],
				["||Grupo Ocupacional", "grupoocupacional.php", , , , disabled[++i], , , , , , ],						
				["||Serie Ocupacional", "serieocupacional.php", , , , disabled[++i], , , , , , ],
				["||Tipos de Cargo", "tiposcargo.php", , , , disabled[++i], , , , , , ],
				["||Nivel de Tipos de Cargo", "nivelcargo.php", , , , disabled[++i], , , , , , ],
				["||Cargos", "cargos.php", , , , disabled[++i], , , , , , ],
			["|Capacitaci&oacute;n", , , , , , , , , , , ],
				["||Grado de Instrucci&oacute;n", "ginstruccion.php", , , , disabled[++i], , , , , , ],
				["||Profesiones", "profesiones.php", , , , disabled[++i], , , , , , ],
				["||Centros de Estudios", "centroestudio.php", , , , disabled[++i], , , , , , ],
				["||Cursos", "cursos.php", , , , disabled[++i], , , , , , ],
				["||Idiomas", "idiomas.php", , , , disabled[++i], , , , , , ],
			["|Relaciones Laborales", , , , , , , , , , , ],
				["||Tipos de Trabajador", "tipostrabajador.php", , , , disabled[++i], , , , , , ],
				["||Tipos de N&oacute;mina", "tiposnomina.php", , , , disabled[++i], , , , , , ],
				["||Perfiles de N&oacute;mina", "tiposperfil.php", , , , disabled[++i], , , , , , ],
				["||Tipos de Contrato", "tiposcontrato.php", , , , disabled[++i], , , , , , ],
				["||Formatos de Contrato", "formatoscontrato.php", , , , disabled[++i], , , , , , ],
				["||Motivos de Cese", "motivoscese.php", , , , disabled[++i], , , , , , ],
			["|Afiliaciones", , , , , , , , , , , ],
				["||Cooperativas", "cooperativas.php", , , , disabled[++i], , , , , , ],
				["||Sindicatos", "sindicatos.php", , , , disabled[++i], , , , , , ],
				["||Tipos de Seguro", "tiposseguro.php", , , , disabled[++i], , , , , , ],
				["||Planes de Seguro", "planesseguro.php", , , , disabled[++i], , , , , , ],
			["|Clima Laboral", , , , , , , , , , , ],
				["||Preguntas", "preguntas.php", , , , disabled[++i], , , , , , ],
				["||Plantillas", "plantillas.php", , , , disabled[++i], , , , , , ],
			["|Gesti&oacute;n de Competencias", , , , , , , , , , , ],
				["||Evaluaciones", "evaluaciones.php", , , , disabled[++i], , , , , , ],
				["||Tipos de Evaluaci&oacute;n", "tevaluacion.php", , , , disabled[++i], , , , , , ],
				["||Grupos de Competencias", "aevaluaciones.php", , , , disabled[++i], , , , , , ],
				["||Grado de Calificaciones", "gcompetencias.php", , , , disabled[++i], , , , , , ],
				["||Competencias", "competencias.php", , , , disabled[++i], , , , , , ],
				["||Plantilla de Competencias", "pcompetencias.php", , , , disabled[++i], , , , , , ],
			["|N&oacute;minas", , , , , , , , , , , ],
				["||Tipos de Proceso", "tiposproceso.php", , , , disabled[++i], , , , , , ],
				["||Formulas", "formulas.php", , , , disabled[++i], , , , , , ],
				["||Conceptos", "conceptos.php", , , , disabled[++i], , , , , , ],
			["|Otros", , , , , , , , , , , ],
				["||Miscel&aacute;neos", "miscelaneos.php", , , , disabled[++i], , , , , , ],
			
		["Admin.", , , , , , "0", , , , , ],
			["|Seguridad", , , , , , , , , , , ],
				["||Maesto de Usuarios", "usuarios.php?limit=0", , , , disabled[++i], , , , , , ],
				["||Conceptos de Seguridad", "usuarios_conceptos.php?limit=0", , , , disabled[++i], , , , , , ],
				["||Dar Autorizaciones a Usuarios", "usuarios_autorizaciones.php?limit=0", , , , disabled[++i], , , , , , ],
			["|Seguridad Alterna", , , , , , , , , , , ],
				["||Dar Autorizaciones a Usuarios", "seguridad_alterna.php?limit=0", , , , disabled[++i], , , , , , ],
	];
} 

else if (admin=="N" && opciones=="N")  {
	var menuItems = [
		["Gesti&oacute;n", , , , , , "0", , , , , ],
			["|Empleados", "empleados.php?limit=0", , , , "_", , , , , , ],
			
		["Maestros", , , , , , "0", , , , , ],
			["|Del Sistema SIA", , , , , , , , , , , ],
				["||Personas", "personas.php?limit=0", , , , "_", , , , , , ],
				["||Propios del Sistema", , , , , , , , , , , ],
					["|||Aplicaciones", "aplicaciones.php", , , , "_", , , , , , ],
					["|||Par&aacute;metros", "parametros.php", , , , "_", , , , , , ],
				["||Otros Maestros", , , , , , , , , , , ],
					["|||Paises", "paises.php", , , , "_", , , , , , ],
					["|||Estados", "estados.php", , , , "_", , , , , , ],
					["|||Municipios", "municipios.php", , , , "_", , , , , , ],
					["|||Ciudades", "ciudades.php?limit=0", , , , "_", , , , , , ],
					["|||Tipos de Pago", "tipospago.php", , , , "_", , , , , , ],
					["|||Bancos", "bancos.php", , , , "_", , , , , , ],
			["|Organizaci&oacute;n", , , , , , , , , , , ],
				["||Organismos", "organismos.php", , , , "_", , , , , , ],
				["||Dependencias", "dependencias.php", , , , "_", , , , , , ],
				["||-",, , , , , , , , , , ],
				["||-",, , , , , , , , , , ],
				["||Grupo Ocupacional", "grupoocupacional.php", , , , "_", , , , , , ],						
				["||Serie Ocupacional", "serieocupacional.php", , , , "_", , , , , , ],
				["||Tipos de Cargo", "tiposcargo.php", , , , "_", , , , , , ],
				["||Nivel de Tipos de Cargo", "nivelcargo.php", , , , "_", , , , , , ],
				["||Cargos", "cargos.php", , , , "_", , , , , , ],
			["|Capacitaci&oacute;n", , , , , , , , , , , ],
				["||Grado de Instrucci&oacute;n", "ginstruccion.php", , , , "_", , , , , , ],
				["||Profesiones", "profesiones.php", , , , "_", , , , , , ],
				["||Centros de Estudios", "centroestudio.php", , , , "_", , , , , , ],
				["||Cursos", "cursos.php", , , , "_", , , , , , ],
				["||Idiomas", "idiomas.php", , , , "_", , , , , , ],
			["|Relaciones Laborales", , , , , , , , , , , ],
				["||Tipos de Trabajador", "tipostrabajador.php", , , , "_", , , , , , ],
				["||Tipos de N&oacute;mina", "tiposnomina.php", , , , "_", , , , , , ],
				["||Perfiles de N&oacute;mina", "tiposperfil.php", , , , "_", , , , , , ],
				["||Tipos de Contrato", "tiposcontrato.php", , , , "_", , , , , , ],
				["||Formatos de Contrato", "formatoscontrato.php", , , , "_", , , , , , ],
				["||Motivos de Cese", "motivoscese.php", , , , "_", , , , , , ],
			["|Afiliaciones", , , , , , , , , , , ],
				["||Cooperativas", "cooperativas.php", , , , "_", , , , , , ],
				["||Sindicatos", "sindicatos.php", , , , "_", , , , , , ],
				["||Tipos de Seguro", "tiposseguro.php", , , , "_", , , , , , ],
				["||Planes de Seguro", "planesseguro.php", , , , "_", , , , , , ],
			["|Clima Laboral", , , , , , , , , , , ],
				["||Preguntas", "preguntas.php", , , , "_", , , , , , ],
				["||Plantillas", "plantillas.php", , , , "_", , , , , , ],
			["|Gesti&oacute;n de Competencias", , , , , , , , , , , ],
				["||Evaluaciones", "evaluaciones.php", , , , "_", , , , , , ],
				["||Tipos de Evaluaci&oacute;n", "tevaluacion.php", , , , "_", , , , , , ],
				["||Grupos de Competencias", "aevaluaciones.php", , , , "_", , , , , , ],
				["||Grado de Calificaciones", "gcompetencias.php", , , , "_", , , , , , ],
				["||Competencias", "competencias.php", , , , "_", , , , , , ],
				["||Plantilla de Competencias", "pcompetencias.php", , , , "_", , , , , , ],
			["|N&oacute;minas", , , , , , , , , , , ],
				["||Tipos de Proceso", "tiposproceso.php", , , , "_", , , , , , ],
				["||Formulas", "formulas.php", , , , "_", , , , , , ],
				["||Conceptos", "conceptos.php", , , , "_", , , , , , ],
			["|Otros", , , , , , , , , , , ],
				["||Miscel&aacute;neos", "miscelaneos.php", , , , "_", , , , , , ],
			
		["Admin.", , , , , , "0", , , , , ],
			["|Seguridad", , , , , , , , , , , ],
				["||Maesto de Usuarios", "usuarios.php?limit=0", , , , "_", , , , , , ],
				["||Conceptos de Seguridad", "usuarios_conceptos.php?limit=0", , , , "_", , , , , , ],
				["||Dar Autorizaciones a Usuarios", "usuarios_autorizaciones.php?limit=0", , , , "_", , , , , , ],
			["|Seguridad Alterna", , , , , , , , , , , ],
				["||Dar Autorizaciones a Usuarios", "seguridad_alterna.php?limit=0", , , , "_", , , , , , ],
	];
}

dm_initFrame("frmSet", 0, 1, 0);
