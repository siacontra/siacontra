//var RutaImg="estilos/temas/"+xGetElementById('TEMA_USUARIO').value;
var RutaImg="estilos/temas/default";


function VentanaCentrar(id){
	var PosAlto, PosAncho;
	if(xGetElementById(id+'X')){
		PosAlto = ((window.innerHeight-parseInt(xGetElementById(id+'X').style.height))/2)+20;
		if(PosAlto<40)
			PosAlto=40;
		PosAncho = (window.innerWidth-parseInt(xGetElementById(id+'X').style.width))/2;
		if(PosAncho<0)
			PosAncho=0;
		xMoveTo(id+'X',PosAncho,PosAlto);
		}
	}

function VentanaCentrarX(id,PosAlto){
	var PosAncho;
	if(xGetElementById(id+'X')){
		PosAncho = (window.innerWidth-parseInt(xGetElementById(id+'X').style.width))/2;
		xMoveTo(id+'X',PosAncho,PosAlto);
		}
	}

function VentanaCerrar(id){
	xGetElementById(id).innerHTML = "";
	xGetElementById('AREA_VENTANAS').removeChild(xGetElementById(id));
	//quito el div cubrir
	if(xGetElementById("DIV_CUBRIR_"+id))
		xGetElementById('AREA_VENTANAS').removeChild(xGetElementById("DIV_CUBRIR_"+id));
	AcomodarTareas();
	}

function VentanaCambiarZIndex(_ID,_ZIndex){
	if(!xGetElementById(_ID))
		return;
	xStyle('zIndex',_ZIndex,_ID);
	xStyle('zIndex',_ZIndex,_ID+'X');
	xStyle('zIndex',_ZIndex,_ID+'X_CONTENIDO');
	xStyle('zIndex',_ZIndex,_ID+'X_CONTENIDO_CARGADO');
	xStyle('zIndex',_ZIndex,_ID+'X_ESI');
	xStyle('zIndex',_ZIndex,_ID+'X_CS');
	xStyle('zIndex',_ZIndex,_ID+'X_TITULO');
	xStyle('zIndex',_ZIndex,_ID+'X_ESD');
	xStyle('zIndex',_ZIndex,_ID+'X_EII');
	xStyle('zIndex',_ZIndex,_ID+'X_B_INFERIOR');
	xStyle('zIndex',_ZIndex,_ID+'X_EID');
	xStyle('zIndex',_ZIndex,_ID+'X_BOTON_CERRAR');
	if(xGetElementById(_ID+'X_BOTON_MINIMIZAR'))
		xStyle('zIndex',_ZIndex,_ID+'X_BOTON_MINIMIZAR');
	}

var ValorZIndexModalConstante=2500;
var ValorZIndexModalActual=2500;
function VentanaModal(_ID){
	//Crear una capa semi transparente que ocupe toda la pantalla...
	//Para evitar que toquen las ventanas que estan por debajo
	var DIV_CUBRIR=xCreateElement('DIV');
	DIV_CUBRIR.setAttribute('id',"DIV_CUBRIR_"+_ID);
	DIV_CUBRIR.setAttribute('style',"background-image : url('"+RutaImg+"/ventana/fondo_modal.png'); background-repeat : repeat; height : 100%; left : 0px; position : absolute; top : 0px; width : 100%; z-index: "+ValorZIndexModalActual+";");
	xGetElementById('AREA_VENTANAS').appendChild(DIV_CUBRIR);

	VentanaCambiarZIndex(_ID,ValorZIndexModalActual+1);
	ValorZIndexModalActual=ValorZIndexModalActual+2;

	if(ValorZIndexModalActual>3000)
		ValorZIndexModalActual=ValorZIndexModalConstante;
	}

var VentanaIndiceActual=300;
function DarFocoVentana(IDVentana){
	if(!ListaVentanas)
		return;
	if(xGetElementById(IDVentana).style.zIndex==(VentanaIndiceActual-1)||xGetElementById(IDVentana).style.zIndex>=ValorZIndexModalConstante)
		return;
	VentanaCambiarZIndex(IDVentana,VentanaIndiceActual);
	VentanaIndiceActual++;
	if(VentanaIndiceActual>=500){
		var ArregloVentana=new Array(ListaVentanas.length);
		var K=0;
		for(var I=0;I<ListaVentanas.length;I++)
			if(xGetElementById(ListaVentanas[I])){
				ArregloVentana[K]=new Array(2);
				ArregloVentana[K][0]=xGetElementById(ListaVentanas[I]).style.zIndex;
				ArregloVentana[K][1]=ListaVentanas[I];
				K++;
				}
		ArregloVentana.sort();
		for(var I=0;I<K;I++)
			VentanaCambiarZIndex(ArregloVentana[I][1],300+I);
		VentanaIndiceActual=300+K;
		}
	}

//verificar si la session esta iniciada, sino esta enviar al ../index.php
function VentanaNueva(id,Titulo,Tamano_ANCHO,Tamano_ALTO,PaginaContenido,modal,posision_y,posision_x){
	AjaxRequest.post({'onSuccess':
					 	function(req){
							var respuesta = req.responseText;
							if(respuesta==1)
								_VentanaNueva(id,Titulo,Tamano_ANCHO,Tamano_ALTO,PaginaContenido,modal,posision_y,posision_x);
							else
								javascript:document.location.href= '../index.php';
					 		},
					 'url':'../modulo_principal/verificar_sesion.php',
					 'onError':function(req){alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
					 });
	}


function _VentanaNueva(id,Titulo,Tamano_ANCHO,Tamano_ALTO,PaginaContenido,modal,posision_y,posision_x){
	if(!modal)
		modal=false;
	if(HayVentanaModal() && modal==false)
		return;

	if(xGetElementById(id))
		VentanaCerrar(id);

	var TamanoBorde=1;
	var TamanoImgEsquinas_ANCHO=15;
	var TamanoImgEsquinasSuperior_ALTO=20;
	var TamanoImgEsquinasInferior_ALTO=4;
	var SeparacionTituloTop=1;
// 	var ColorBordes="#659fc2";
// 	var ColorTitulo="#344360";

    //Capa contenedora de las demas capas
	var Cadena = "<DIV id='"+id+"X' style=\"height : "+Tamano_ALTO+"px; position : absolute; width : "+Tamano_ANCHO+"px; visibility : hidden;\">";

    //Esquina superior izquierda
	Cadena+=        "<DIV id='"+id+"X_ESI' style=\"background-image : url('"+RutaImg+"/ventana/ventana_superior_izquierda.png'); height : "+TamanoImgEsquinasSuperior_ALTO+"px; left : 0px; position : absolute; top : 0px; width : "+TamanoImgEsquinas_ANCHO+"px;\"></DIV>";

    //Fondo centro superior
	Cadena+=        "<DIV id='"+id+"X_CS' style=\"background-image : url('"+RutaImg+"/ventana/ventana_superior_centro.png'); height : "+TamanoImgEsquinasSuperior_ALTO+"px; left : "+TamanoImgEsquinas_ANCHO+"px; position : absolute; top : 0px; width : "+(Tamano_ANCHO-(TamanoImgEsquinas_ANCHO*2))+"px;\"></DIV>";

    //Titulo y capa que mueve la ventana //29=34-5
	Cadena+=        "<DIV id='"+id+"X_TITULO' class='titulo_ventana' style=\"height : "+(TamanoImgEsquinasSuperior_ALTO-SeparacionTituloTop)+"px; left : "+TamanoImgEsquinas_ANCHO+"px; position : absolute; top : "+SeparacionTituloTop+"px; width : "+(Tamano_ANCHO-(TamanoImgEsquinas_ANCHO*2))+"px; cursor : move; font-family : 'sans serif', 'Arial', 'Bitstream Vera Sans'; font-size : 13px; font-weight : bold; overflow : hidden; text-align : center; white-space : nowrap;\">"+Titulo+"</DIV>";

    //Esquina superior derecha
	Cadena+=        "<DIV id='"+id+"X_ESD' style=\"background-image : url('"+RutaImg+"/ventana/ventana_superior_derecha.png'); height : "+TamanoImgEsquinasSuperior_ALTO+"px; left : "+(Tamano_ANCHO-TamanoImgEsquinas_ANCHO)+"px; position : absolute; top : 0px; width : "+TamanoImgEsquinas_ANCHO+"px;\"></DIV>";

    //Capa para colocar contenido y con fondo
	Cadena+=        "<DIV id='"+id+"X_CONTENIDO' class='bordes_ventana' style=\"background-color : #FFFFFF; background-image : url('"+RutaImg+"/ventana/ventana_fondo.png'); background-repeat : repeat-x; border-bottom-style : solid; border-bottom-width : "+TamanoBorde+"px; border-left-style : solid; border-left-width : "+TamanoBorde+"px; border-right-style : solid; border-right-width : "+TamanoBorde+"px; border-top-style : solid; border-top-width : "+TamanoBorde+"px; height : "+(Tamano_ALTO-(TamanoImgEsquinasSuperior_ALTO+TamanoImgEsquinasInferior_ALTO+(TamanoBorde*2)))+"px; left : 0px; position : absolute; top : "+TamanoImgEsquinasSuperior_ALTO+"px; width : "+(Tamano_ANCHO-(TamanoBorde*2))+"px; overflow : hidden;\"><DIV id='"+id+"X_CONTENIDO_CARGADO'></DIV></DIV>";

    //Esquina inferior izquierda, centro y derecha
	Cadena+=        "<DIV id='"+id+"X_EII' style=\"background-image : url('"+RutaImg+"/ventana/ventana_inferior_izquierda.png'); height : "+TamanoImgEsquinasInferior_ALTO+"px; left : 0px; position : absolute; top : "+(Tamano_ALTO-TamanoImgEsquinasInferior_ALTO-1)+"px; width : "+TamanoImgEsquinas_ANCHO+"px;\"></DIV>";
	Cadena+=        "<DIV id='"+id+"X_B_INFERIOR' class='fondo_centro_barra_inferior_ventana' style=\"height : "+TamanoImgEsquinasInferior_ALTO+"px; left : "+TamanoImgEsquinas_ANCHO+"px; position : absolute; top : "+(Tamano_ALTO-TamanoImgEsquinasInferior_ALTO-1)+"px; width : "+(Tamano_ANCHO-(TamanoImgEsquinas_ANCHO*2))+"px; cursor : move;\"></DIV>";
	Cadena+=        "<DIV id='"+id+"X_EID' style=\"background-image : url('"+RutaImg+"/ventana/ventana_inferior_derecha.png'); height : "+TamanoImgEsquinasInferior_ALTO+"px; left : "+(Tamano_ANCHO-TamanoImgEsquinas_ANCHO)+"px; position : absolute; top : "+(Tamano_ALTO-TamanoImgEsquinasInferior_ALTO-1)+"px; width : "+TamanoImgEsquinas_ANCHO+"px;\"></DIV>";

    //Boton cerrar
	Cadena+=        "<DIV id='"+id+"X_BOTON_CERRAR' onclick=\"VentanaCerrar('"+id+"');\" style=\"background-image : url('"+RutaImg+"/ventana/ventana_boton_cerra.png'); height : 18px; left : "+(Tamano_ANCHO-26)+"px; position : absolute; top : 1px; width : 18px; cursor : pointer;\"></DIV>";

	//Boton minimizar
	if(modal==false){//si la ventana es modal, no colocar el boton de minimizar
		Cadena+=        "<DIV id='"+id+"X_BOTON_MINIMIZAR' onclick=\"MinimizarVentana('"+id+"');\" style=\"background-image : url('"+RutaImg+"/ventana/ventana_boton_minimizar.png'); height : 18px; left : "+(Tamano_ANCHO-45)+"px; position : absolute; top : 1px; width : 18px; cursor : pointer;\"></DIV>";
		}
	//Cierra la capa contenedora
	Cadena+= "</DIV>";


	var TamanaBarraTarea=200;

	//Barra de la tarea/ventana minimizada
	Cadena+= "<DIV id='"+id+"TAREA' onclick=\"MostrarOcultarVentana('"+id+"');\" class='bordes_barra_tarea_ventana' style=\"cursor : pointer; background-image : url('"+RutaImg+"/ventana/fondo_tarea.png'); text-align : left; white-space : nowrap; position : absolute; position : absolute; width : "+TamanaBarraTarea+"px; height: 20px; visibility : hidden; overflow : hidden; border-bottom-style : solid; border-bottom-width : "+TamanoBorde+"px; border-left-style : solid; border-left-width : "+TamanoBorde+"px; border-right-style : solid; border-right-width : "+TamanoBorde+"px; border-top-style : solid; border-top-width : "+TamanoBorde+"px;\">";
	Cadena+= "<DIV style=\"height: 20px; width : "+(TamanaBarraTarea-26)+"px; overflow : hidden;\">";
	Cadena+= "<table width='100%'><tr valign='center' style=\"white-space : nowrap; font-family : 'sans serif', 'Arial', 'Bitstream Vera Sans'; font-size : 12px; font-weight : bold;\"><td class='titulo_ventana'>"+Titulo+"</td></tr></table>";
	Cadena+= "</DIV>";
    //Boton cerrar
	Cadena+= "<DIV onclick=\"VentanaCerrar('"+id+"');\" style=\"background-image : url('"+RutaImg+"/ventana/ventana_boton_cerra.png'); height : 18px; left : "+(TamanaBarraTarea-20)+"px; position : absolute; top : 1px; width : 18px; cursor : pointer;\"></DIV>";
	Cadena+= "</DIV>";


	//Crea un bloque DIV. Este contendra a la ventana
	var DIV_VENTANA=xCreateElement('DIV');
	DIV_VENTANA.setAttribute('id',id);


	//Añade dentro de AREA_VENTANAS el bloque creado anteriormente
	xGetElementById('AREA_VENTANAS').appendChild(DIV_VENTANA);

	xGetElementById(id).innerHTML=Cadena;

	//Activa la movilidad de la ventana
	xEnableDrag2Ventana(id+"X", null,null,null,'AREA_VENTANAS');

	if(!posision_y)
		VentanaCentrar(id);
	else
		VentanaCentrarX(id,posision_y);

	DarFocoVentana(id);
	xGetElementById(id+'X').setAttribute('onmousedown',"DarFocoVentana('"+id+"')");

	if(modal==true){
		VentanaModal(id);
		}
	else{
		OcultarVentanas();
		}

	xStyle('visibility','visible',id+'X');

	AcomodarTareas();

	//Muestra el icono de cargando
	xGetElementById(id+'X_CONTENIDO_CARGADO').innerHTML ="<br><DIV style=\"color : #959595; font-family : 'sans-serif', 'Arial','Bitstream Vera Sans'; font-size : 24px; font-style : normal; font-weight : bold; text-align : center;\" align=\"middle\"><img src='img/cargando.gif' align=\"top\">&nbsp;Cargando...</DIV>";

	CargarContenido(PaginaContenido,id);
	}

function CargarContenido(url,id){
	if(url==''||id=='') return;
	conexion1=crearXMLHttpRequest();
	conexion1.open("GET",url,true);
	conexion1.onreadystatechange = function(){
											if(conexion1.readyState == 4){
												xGetElementById(id+'X_CONTENIDO').innerHTML = "<DIV id='"+id+"X_CONTENIDO_CARGADO' style=\"visibility : hidden;\"></DIV>";
												xGetElementById(id+'X_CONTENIDO_CARGADO').innerHTML = conexion1.responseText;
												xStyle('visibility','inherit',id+'X_CONTENIDO_CARGADO');
												VentanaInicializar(id);
												xStyle('overflow','auto',id+'X_CONTENIDO');
												}
                                             }
	conexion1.send(null);
	}

function crearXMLHttpRequest(){
     try{objetus = new ActiveXObject("Msxml2.XMLHTTP");}
     catch(e){
          try{objetus=new ActiveXObject("Microsoft.XMLHTTP");}
          catch(E){objetus=false;}
          }
     if(!objetus && typeof XMLHttpRequest!='undefined'){objetus=new XMLHttpRequest();}
     return objetus;
     }

function MostrarOcultarVentana(id){
	if(HayVentanaModal())
		return;
	if(!xGetElementById(id+'X'))
		return;
	var Estado=xGetElementById(id+'X').style.visibility;
	OcultarVentanas();
	if(Estado=="hidden"){
		MaximizarVentana(id);
		DarFocoVentana(id);
		}
	}

function MinimizarVentana(id){
	xStyle('visibility','hidden',id+'X');
	}

function MaximizarVentana(id){
	xStyle('visibility','visible',id+'X');
	}

function AcomodarTareas(){
	if(!ListaVentanas)
		return;
	var Salto=25;
	for(var I=0,P=window.innerHeight-Salto;I<ListaVentanas.length;I++)
		if(xGetElementById(ListaVentanas[I])){
			xStyle('left',5+'px',ListaVentanas[I]+'TAREA');
			xStyle('top',P+'px',ListaVentanas[I]+'TAREA');
			xStyle('visibility','visible',ListaVentanas[I]+'TAREA');
			P=P-Salto;
			}
	}

function OcultarVentanas(){
	if(!ListaVentanas)
		return;
	for(var I=0;I<ListaVentanas.length;I++)
		if(xGetElementById(ListaVentanas[I]))
			if(xGetElementById(ListaVentanas[I]).style.zIndex<ValorZIndexModalConstante)
				xStyle('visibility','hidden',ListaVentanas[I]+'X');
	}

function HayVentanaModal(){
	if(!ListaVentanas)
		return;
	for(var I=0;I<ListaVentanas.length;I++)
		if(xGetElementById(ListaVentanas[I]))
			if(xGetElementById(ListaVentanas[I]).style.zIndex>=ValorZIndexModalConstante)
				return true;
	return false;
	}

//Autor: Carlos Pinto
//Email: pintocar83@gmail.com
//Distribuido bajo los terminos de GNU/GPL.
