var CedGlobal="";
var BuscarListado_CadenaBuscar="";
var NDigitos_Codigo_Articulo=4;

var IDSeleccionActualLista=-1;

var mostrarTema=new Array();
var mostrarTema1=new Array();
var IDTipoEventoSeleccionActualLista=-1;

var LISTA_PERSONA__IDSeleccionActualLista=-1;
var LISTA_PERSONA__BuscarListado_CadenaBuscar="";

var LUGARES__IDTipoCuentaSeleccionActualLista=-1;

var Form_DEFINICIONES_TIPOS_DE_CUENTA__TabPane;
var principal__TabPane;

var ContadorParticipantes=0;

var ArregloArticulosContador=new Array();
ArregloArticulosContador[0]=-1;

var ArregloContPart=new Array();
var centinela=new Array();
centinela[0]=0;
var centi=new Array();
var Lista=new Array();
var cedSeleccionada=new Array();
cedSeleccionada[0]=-1;
var posicion=new Array();
var pos=0;
centi[0]=0;
var paz=0;
var contarParteliminados=0;
var Max=0;
var sumador=0;

var traePart=0;


	function EventosMensaje(MSG,color){
	if(!MSG)
		MSG="&nbsp;";
	if(color=="VERDE")
		MSG="<DIV style='color:#006600'>"+MSG+"</DIV>";
	else if(color=="ROJO")
		MSG="<DIV style='color:#FF0000'>"+MSG+"</DIV>";
	xGetElementById("MSG_EV").innerHTML=MSG;
	}


	function LimpiarInputTextBuscarListado()
	{
	xGetElementById("LISTADO_BUSCAR_FA").value="";
	DarFocoCampo("LISTADO_BUSCAR_FA",1000);
	BuscarListado();
	}




	function LimpiarInputTextBuscarListadoParticipantes()
	{
	xGetElementById("LISTADO_BUSCAR_PARTICIPANTES").value="";
	//DarFocoCampo("LISTADO_BUSCAR_PARTICIPANTES",1000);
	//Form_PARTICIPANTES__MostrarTablaArticulos();
	}

	
	/*** Busca el listado de los eventos ****/		
	function BuscarListado()
	{

	CargarSelectEventos();

	xGetElementById("FECHA_APERTURA_FDCB").setAttribute('ondblclick',"showCalendar('FECHA_APERTURA_FDCB','%d/%m/%Y')");
	xGetElementById("IMG_FECHA_APERTURA_FDCB").setAttribute('onclick',"showCalendar('FECHA_APERTURA_FDCB','%d/%m/%Y')");

	xGetElementById("FECHA_CULMINACION_FDCB").setAttribute('ondblclick',"showCalendar('FECHA_CULMINACION_FDCB','%d/%m/%Y')");
	xGetElementById("IMG_FECHA_CULMINACION_FDCB").setAttribute('onclick',"showCalendar('FECHA_CULMINACION_FDCB','%d/%m/%Y')");
	
		
			
	var CadenaBuscar=quitarCodigoCeros(xTrim(strtoupper(xGetElementById("LISTADO_BUSCAR_FA").value)));
	xGetElementById("TIPO_EVENTO").innerHTML="";
	
	CargarSelectTipoArticulo();

	IDSeleccionActualLista=-1;
	LUGARES__IDTipoCuentaSeleccionActualLista=-1;
	IDTipoEventoSeleccionActualLista=-1;
	//DesactivarBoton("BOTON_MODIFICAR_FDCB","IMG_MODIFICAR_FDCB",'modificar');
	//DesactivarBoton("BOTON_ELIMINAR_FDCB","IMG_ELIMINAR_FDCB",'eliminar');
	
	

	if(CadenaBuscar!="")

		if(BuscarListado_CadenaBuscar==CadenaBuscar)
		return;
		BuscarListado_CadenaBuscar=CadenaBuscar;


	AjaxRequest.post({'parameters':{ 'caso':"cargarEventos",'CadenaBuscar':CadenaBuscar},
					 'onSuccess':MostrarListado,
					 'url':'lib/controladorCes.php',
					 'onError':function(req){alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
					 });
	}

	/*** Esta función permite mostrar el listado de los eventos ***/

	function MostrarListado(req)
	{
		var respuesta = req.responseText;	
		var resultado = eval("(" + respuesta + ")");
		var n=resultado.length;		
		//alert(n);
	
	var TextoBuscar=quitarCodigoCeros(xTrim(strtoupper(xGetElementById("LISTADO_BUSCAR_FA").value)));

	xGetElementById("TABLA_LISTA_FA").innerHTML="";
	

	var CadAux1, CadAux2;

	var Contenido="";
	var FuncionOnclick="";
	var FuncionOnDblclick="";
	var FuncionOnMouseOver="";
	var FuncionOnMouseOut="";

		for(var i=0;i<n; i++){
		FuncionOnclick="SeleccionarElementoTabla('"+resultado[i]['co_id_evento']+"','"
							   +resultado[i]['tx_nombre_evento']+"','"
				               +resultado[i]['co_lugar']+"','"	
				               +resultado[i]['nu_horas']+"','"					               			      	
							   +resultado[i]['tx_descripcion_evento']+"','"						
							   +resultado[i]['co_id']+"','"
							   +resultado[i]['bo_certificado']+"','"
							   +resultado[i]['bo_certificado_ponente']+"','"
							   +resultado[i]['tx_nu_certificado']+"','"							   						   
							   +resultado[i]['hh_hora1']+"','"
							   +resultado[i]['hh_hora2']+"','"
							   +resultado[i]['hh_fecha1']+"','"
							   +resultado[i]['hh_fecha2']+"','"
							   +resultado[i]['CodPersona']+"','"
							   +resultado[i]['NomCompleto']+"','"
							   +resultado[i]['Ndocumento']+"','"
							   +resultado[i]['nombre_lugar']+"')";							   
		FuncionOnDblclick="tabPane1.setSelectedIndex(0)"; 	
 		FuncionOnMouseOver="pintarFila(\"FA"+resultado[i]['co_id_evento']+"\")";		
 		FuncionOnMouseOut="despintarFila(\"FA"+resultado[i]['co_id_evento']+"\")";


		Contenido+="<TR id='FA"+resultado[i]['co_id_evento']+"' onclick=\""+FuncionOnclick+"\" ondblclick=\""+FuncionOnDblclick+"\" onmouseover='"+FuncionOnMouseOver+"' onmouseout='"+FuncionOnMouseOut+"'>";
		
		CadAux1=str_replace(strtoupper(resultado[i]['tx_nombre_evento']),"<strong>"+TextoBuscar+"</strong>",TextoBuscar);
		Contenido+="<TD width='5%' class='FilaEstilo' align='center'>"+resultado[i]['co_id_evento']+"</TD>";
		Contenido+="<TD width='45%' class='FilaEstilo'>"+CadAux1+"</TD>";
		
				
		var str=resultado[i]['nombre_lugar'];
		var maximo = 60;
		var longitud = str.length;
		if (longitud > maximo) {
		Contenido+="<TD width='45%' class='FilaEstilo'>"+strtoupper(str.substr(0,60))+"...</TD>";
		} else {
		Contenido+="<TD width='45%' class='FilaEstilo'>"+strtoupper(resultado[i]['nombre_lugar'])+"</TD>";}
				
		Contenido+="<TD width='5%' class='FilaEstilo'>"+FormatearFecha(resultado[i]['hh_fecha1'])+"</TD>";


		Contenido+="</TR>";
		}

	xGetElementById("TABLA_LISTA_FA").innerHTML=Contenido;
	}
	
	


	function SeleccionarElementoTabla(IDSeleccion,NombreEvento,co_Lugar,nu_horas,descripcion,cod_evento,certificado, CertificadoPonente, tx_nu_certificado, hora_inicio,hora_culm,fecha_inicio,fecha_culm,CodPersona,NomCompleto,Ndocumento,nombre_lugar)
	{

	EventosMensaje("");
	if(IDTipoEventoSeleccionActualLista!=-1)
		xGetElementById("FA"+IDTipoEventoSeleccionActualLista).bgColor=colorFondoTabla;
	colorBase=colorSeleccionTabla;
	xGetElementById("FA"+IDSeleccion).bgColor=colorBase;
	IDSeleccionActualLista=IDSeleccion;
	
	
	Form_TEMAS__DesactivarFormulario();

	IDTipoEventoSeleccionActualLista=IDSeleccion;
	IDSeleccionActualLista=cod_evento;
	LUGARES__IDTipoCuentaSeleccionActualLista=co_Lugar;
    xGetElementById("COD_EVENTO_SIACES").value=IDSeleccion;

	xGetElementById("TABLA_LISTA_PARTICIPANTES_FRDB").innerHTM="";
	
	xGetElementById("NOMBRE_EVENTO").value="";	
	xGetElementById("DESCRIPCION_EVENTO").value="";	
	xGetElementById("FECHA_APERTURA_FDCB").value="";
	xGetElementById("FECHA_CULMINACION_FDCB").value="";

	xGetElementById("NOMBRE_EVENTO").value=NombreEvento;	
	xGetElementById("HORAS_EVENTO").value=nu_horas;	
	xGetElementById("DESCRIPCION_EVENTO").value=descripcion;	
	xGetElementById("FECHA_APERTURA_FDCB").value=FormatearFecha(fecha_inicio);
	xGetElementById("FECHA_CULMINACION_FDCB").value=FormatearFecha(fecha_culm);

	/********* Ponente ******/
	
	xGetElementById("NUM_CERTIFICADO_PONENTE").value="";
	xGetElementById("NUM_CERTIFICADO_PONENTE2").value="";
	
	xGetElementById("SELECT_EP_FILM").value="";
	xGetElementById("TEMA_EVENTO").value="";
	xGetElementById("temasOcultos").value="";
	
	
	xGetElementById("CED_PONENTE2_ODC").value="";
	xGetElementById("NOMBRE_PONENTE2_ODC").value="";
	xGetElementById("COD_PONENTE2_ODC").value="";
	
	
	xGetElementById("CED_PONENTE_ODC").value=Ndocumento;
	xGetElementById("NOMBRE_PONENTE_ODC").value=NomCompleto;
	xGetElementById("COD_PONENTE_ODC").value=CodPersona;
	
	xGetElementById("NUM_CERTIFICADO_PONENTE").value=tx_nu_certificado;
	

	CargarSelectTipoArticulo();
	Form_DEFINICIONES_LUGARES__CargarSelectTipoCta();	
	cargarTemasporEvento();
	

		if (certificado==1)
			xGetElementById("FORMULARIO_EVENTOS").ESTADO_CERTIFICADO[0].checked=true;
		if (certificado==0)
			xGetElementById("FORMULARIO_EVENTOS").ESTADO_CERTIFICADO[1].checked=true;
			
		if (CertificadoPonente==1)
			xGetElementById("FORMULARIO_EVENTOS").ESTADO_CERTIFICADO1[0].checked=true;
		if (CertificadoPonente==0)
			xGetElementById("FORMULARIO_EVENTOS").ESTADO_CERTIFICADO1[1].checked=true;
			
			


	var valorLista=hora_inicio.split(":");
	var hora_ini =valorLista[0];
	var picar=valorLista[1];	
	var valorLista2 =picar.split("-");
	var min_ini =valorLista2[0];
	var turno_ini = valorLista2[1];

	var valorLista=hora_culm.split(":");
	var hora_culm =valorLista[0];
	var picar=valorLista[1];	
	var valorLista2 =picar.split("-");
	var min_culm =valorLista2[0];
	var turno_culm = valorLista2[1];

//alert (hora_ini+" "+min_ini+" "+turno_ini);

	restablecerLista(sel_hora_ini,hora_ini);
	restablecerLista(sel_minutos_ini,min_ini);
	restablecerLista(sel_turno_ini,turno_ini);

	restablecerLista(sel_hora_cul,hora_culm);
	restablecerLista(sel_minutos_cul,min_culm);
	restablecerLista(sel_turno_cul,turno_culm);	


    AjaxRequest.post({'parameters':{ 'caso':"BuscarSegundoPonente",'CodEvento':IDSeleccion,'CodPersona':CodPersona},
					 'onSuccess':MostrarSegundoPonente,
					 'url':'lib/controladorCes.php',
					 'onError':function(req){alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
					 });

	AjaxRequest.post({'parameters':{ 'caso':"cargarParticipantesEvento",'CodEvento':IDSeleccion},
					 'onSuccess':MostrarParticipantes,
					 'url':'lib/controladorCes.php',
					 'onError':function(req){alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
					 });

	}
	
	
	
	
	function MostrarSegundoPonente(req)
	{
		
	var respuesta = req.responseText;       
    var resultado = eval("(" + respuesta + ")");	
		
	var CertSegPonente=resultado[0]['tx_nu_certificado'];
	
	var NomSegPonente=resultado[0]['NomCompleto'];
	var CedSegPonente=resultado[0]['Ndocumento'];
	var CodigoSegPonente=resultado[0]['CodPersona'];
	
	xGetElementById("CED_PONENTE2_ODC").value=CedSegPonente;
	xGetElementById("NOMBRE_PONENTE2_ODC").value=NomSegPonente;
	xGetElementById("COD_PONENTE2_ODC").value=CodigoSegPonente;

	xGetElementById("NUM_CERTIFICADO_PONENTE2").value=CertSegPonente;
		
		
		
		
	}


	function restablecerLista(idLista,valorLista)
	{
  	  var objLista = xGetElementById(idLista);
    	  var tam = objLista.getElementsByTagName('option').length;
    		for(var i = 0;  i<tam; i++)             
   		 {   
     		   var valor = objLista.getElementsByTagName('option')[i];

       		 if (valor.value == valorLista)
        	{
            	objLista.getElementsByTagName('option')[i].selected = true;
            	break;
       		}
            
    		}  
	}


	ListaListaSelect=-1;

	function CargarSelectTipoArticulo()
	{

	AjaxRequest.post({'parameters':{ 'caso':"TiposEventosBuscarListado",
									'CadenaBuscar':"",'CadenaBuscarTE':ListaListaSelect},
					 'onSuccess':function(req){
			CargarSELECT(req,"TIPO_EVENTO", IDSeleccionActualLista, "co_id","tx_nombre_cap");},
					 'url':'lib/controladorCes.php',
					 'onError':function(req){alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
					 });
	}
	
	

	function CargarSelectEventos()
	{

	
	AjaxRequest.post({'parameters':{ 'caso':"CargarSelectEventos"},
					 'onSuccess':MostrarEventosSolos,
					 'url':'lib/controladorCes.php',
					 'onError':function(req){alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
					 });


	}
	
	
	function cargarTemasporEvento()
	{

	var temaSeleccionado=xGetElementById("COD_EVENTO_SIACES").value;
	
	AjaxRequest.post({'parameters':{ 'caso':"cargarTemasporEvento",'eventoSeleccionado':temaSeleccionado},
					 'onSuccess':MostrarTemasporCadaEvento,
					 'url':'lib/controladorCes.php',
					 'onError':function(req){alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
					 });


	}
	
	var k=0;	
	
	function MostrarTemasporCadaEvento (req) 
	{
	  	var respuesta = req.responseText;       
    	var resultado = eval("(" + respuesta + ")");
    	var n=resultado.length;
    	    	
    	 var objLista2 = xGetElementById("SELECT_EP_FILM");    	 
    	 var tam = objLista2.getElementsByTagName('option').length;
   	
    	  for(var i=0; i<tam;i++)		  		  		             
   		 {   
   		 	   var k=0;
     		   var valor = objLista2.getElementsByTagName('option')[i];
     	     		   
     		  		for(var k=0; k<n;k++)
     		  		{
						if (valor.value == resultado[k]['co_tema'])
        				{    
		        			    		
		            		objLista2.getElementsByTagName('option')[i].selected = true;
		            		mostrarTema1[k]=resultado[k]['co_tema'];
		            		mostrarTema[k]=resultado[k]['tx_tema'];	            	
		            		
       					}       											
					
					}  		 
            
    	}  
    	
    	//alert(mostrarTema);
       
    		xGetElementById("TEMA_EVENTO").value=mostrarTema;
    		xGetElementById("temasOcultos").value=mostrarTema1;
    	
    	
	}
	
	



	function MostrarEventosSolos(req)
	{
	var respuesta = req.responseText;       
    	var resultado = eval("(" + respuesta + ")");
    	var n=resultado.length;

		var combo=xGetElementById('PARTICIPANTES_POR_EVENTOS');
    
    		limpiarTabla('PARTICIPANTES_POR_EVENTOS');
    
	    	var opcion = mD.agregaNodoElemento("option", null, null, { 'value':"0" } );
	    	mD.agregaHijo(combo, opcion);
	    	mD.agregaNodoTexto(opcion,"<--SELECCIONE-->");

    		for (var i=0; i<n; i++)
    		{
		       	 var opcion = mD.agregaNodoElemento("option", null, null, { 'value':resultado[i]['co_id_evento'] } );
		       	 mD.agregaHijo(combo, opcion);
			 mD.agregaNodoTexto(opcion,resultado[i]['tx_nombre_evento']);
  	 	}

	}

	
	function recargar()
    {
      window.location.reload()
    }

	function EventosNuevo()
	{
		tabPane1.setSelectedIndex(0);
	
		IDTipoEventoSeleccionActualLista=-1;
		Form_TEMAS__ActivarFormulario();
		
		Form_PARTICIPANTES__IDSeleccionActualListaArticulos=-1;
		DarFocoCampo("NOMBRE_EVENTO",1000);
		xGetElementById("FORMULARIO_EVENTOS").reset();
		xGetElementById("TIPO_EVENTO").innerHTML="";
		xGetElementById("TABLA_LISTA_PARTICIPANTES_FRDB").innerHTM="";
		
		xGetElementById("FECHA_APERTURA_FDCB").readOnly=true;
		xGetElementById("FECHA_CULMINACION_FDCB").readOnly=true;

		Form_DEFINICIONES_LUGARES__CargarSelectTipoCta();
		
		Form_PARTICIPANTES__ArregloArticulosContador=0;
		Form_PARTICIPANTES__MostrarTablaArticulos();

		BuscarListado();
		EventosMensaje("");		
		CargarSelectTipoArticulo();	
		
		cargarTemas();	
		
                                                             	
		xGetElementById("ID_RADIO_IMPRIMIR_EVENTO_MES").checked=true;
		xGetElementById("ID_RADIO_IMPRIMIR_EVENTO_TRIMESTRE").checked=false;
		xGetElementById("ID_RADIO_IMPRIMIR_EVENTO_PARTICIPANTES").checked=false;

		xGetElementById("IMG_BUSCAR_PONENTE_ODC").setAttribute('onclick',"Form_LISTA_PERSONA__Abrir('COD_PONENTE_ODC','CED_PONENTE_ODC','NOMBRE_PONENTE_ODC');");
		
		xGetElementById("IMG_BUSCAR_PONENTE2_ODC").setAttribute('onclick',"Form_LISTA_PERSONA__Abrir('COD_PONENTE2_ODC','CED_PONENTE2_ODC','NOMBRE_PONENTE2_ODC');");

		xGetElementById("IMG_LUGAR_EVENTO_2").setAttribute('onclick',"_VentanaNueva('VENTANA_LUGAR_EVENTO','DEFINICIONES DE LOS LUGARES DE LOS EVENTOS',700,410,'ventana_lugares_eventos_siaces.php',true);");
		
		xGetElementById("IMG_TEMA_EVENTO_2").setAttribute('onclick',"_VentanaNueva('VENTANA_TEMA_EVENTO','DEFINICIONES DE LOS TEMAS DE LOS EVENTOS',700,410,'ventana_temas_eventos.php',true);");
		}
	
	function cargarTemas ()
	{
		var Contenido="";
		
		AjaxRequest.post({'parameters':{'caso':"TEMAS__BuscarListado"},
					'onSuccess':
						function(req){
							var respuesta = req.responseText;
							var resultado = eval("(" + respuesta + ")");
							var n=resultado.length;
							//var Contenido="<option value='*'>TODOS</option>";
								for(var i=0;i<n;i++)
									 Contenido+="<option value='"+resultado[i]["co_tema"]+"'>"+resultado[i]["tx_tema"]+"</option>";
							xGetElementById("SELECT_EP_FILM").innerHTML=Contenido;
							},
					'url':'lib/controladorCes.php',
					'onError':function(req){alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
					});
	  
	}
	
var valorSelecMultiple;
var valorSelecMultipleA;
var valorTextMultiple;	
	
	function Form_IMPRIMIR_TEMAS__Visualizar()
	{
		var ListaEP1=ContenidoSelectValueSelected1("SELECT_EP_FILM");
		var ListaEP=ContenidoSelectValueSelected("SELECT_EP_FILM");		
		
		Lista[0]=ListaEP;
		
		xGetElementById("TEMA_EVENTO").value=ListaEP1;
		xGetElementById("temasOcultos").value=ListaEP;
		
	}
	
	
	function EventosGuardarVerificar()
	{	
	
	var NombreEvento 				= xTrim(strtoupper(xGetElementById("NOMBRE_EVENTO").value));
	var TipoEvento 					= xTrim(strtoupper(xGetElementById("TIPO_EVENTO").value));
	//var N_Actividad 				= xTrim(strtoupper(xGetElementById("N_ACTIVIDAD").value));
	var Certificado 				= xGetElementById("FORMULARIO_EVENTOS").ESTADO_CERTIFICADO[0].checked;
	var DescripcionEvento 		    = xTrim(strtoupper(xGetElementById("DESCRIPCION_EVENTO").value));
	var TemaEvento				    = xTrim(strtoupper(xGetElementById("TEMA_EVENTO").value));
	var LugarEvento 				= xGetElementById("LUGAR_EVENTO_2").value;
	var FechaInicio					= xGetElementById('FECHA_APERTURA_FDCB').value;
	var FechaCulminacion			= xGetElementById('FECHA_CULMINACION_FDCB').value;
	var HoraInicio					= xGetElementById('sel_hora_ini').value;
	var MinutoInicio				= xGetElementById('sel_minutos_ini').value;
	var TurnoInicio					= xGetElementById('sel_turno_ini').value;
	var HoraCulmi					= xGetElementById('sel_hora_cul').value;
	var MinutoCulmi					= xGetElementById('sel_minutos_cul').value;
	var TurnoCulmi					= xGetElementById('sel_turno_cul').value;
	var CodPonente			        = xGetElementById('COD_PONENTE_ODC').value;
	

	FechaInicio=DesFormatearFecha(FechaInicio);
		

	if(!NombreEvento){
		EventosMensaje("Por favor introduzca el Nombre del evento.","ROJO");		
		return;
		}

	if(!DescripcionEvento){
		EventosMensaje("Por favor introduzca una breve descripción para este evento.","ROJO");
		return;
		}
		
		
	if(!TemaEvento){
		EventosMensaje("Por favor introduzca tema(s) para este evento.","ROJO");
		return;
		}
		

	if(!TipoEvento){
		EventosMensaje("Por favor seleccione el tipo de evento.","ROJO");		
		return;
		}

	if(!LugarEvento){
		EventosMensaje("Por favor introduzca el lugar del evento.","ROJO");		
		return;
		}
	if(!FechaInicio){
		EventosMensaje("Por favor seleccione la fecha de inicio del evento.","ROJO");		
		return;
		}
	if(!CodPonente){
		EventosMensaje("Por favor introduzca el nombre del ponente.","ROJO");		
		return;
		}

	
	//alert(FechaInicio);
	//alert (NombreEvento+"- "+TipoEvento+"- "+N_Actividad+"- "+Certificado+"- "+DescripcionEvento+"- "+TemaEvento);
	
	AjaxRequest.post({'parameters':{ 'caso':"EventosExiste",
							'NombreEvento':NombreEvento,
							'FechaInicio':FechaInicio,
							'LugarEvento':LugarEvento,
							'hora_inicio':HoraInicio,
							'minuto_inicio':MinutoInicio,
							'turno_inicio':TurnoInicio},
					 'onSuccess':EventosGuardar,
					 'url':'lib/controladorCes.php',
					 'onError':function(req){alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
					 });
	}

/**
* Guarda los datos en la BD
*/
	function EventosGuardar(req)
	{
	var respuesta = req.responseText;
	var resultado = eval("(" + respuesta + ")");

	//alert(IDTipoEventoSeleccionActualLista);
	
		if(IDTipoEventoSeleccionActualLista==-1)
		{
		
			if(resultado[0]['num']>=1)
			{
				EventosMensaje("No se puedo guardar los datos. Ya existe un evento con los mismos datos.","ROJO");
				return;
			}
			else
			GuardarEventos();		
		}
		
		else
		GuardarEventos();
	}



	function GuardarEventos()
	{ 	

	var NombreEvento 				= xTrim(strtoupper(xGetElementById("NOMBRE_EVENTO").value));
	var TipoEvento 					= xTrim(strtoupper(xGetElementById("TIPO_EVENTO").value));
	var horasevento			     	= xGetElementById("HORAS_EVENTO").value;
	var Certificado 				= xGetElementById("FORMULARIO_EVENTOS").ESTADO_CERTIFICADO[0].checked;
	var CertificadoPonente 			= xGetElementById("FORMULARIO_EVENTOS").ESTADO_CERTIFICADO1[0].checked;
	var DescripcionEvento 			= xTrim(strtoupper(xGetElementById("DESCRIPCION_EVENTO").value));
	var Certificado_Ponente		    = xGetElementById("NUM_CERTIFICADO_PONENTE").value;
	var Certificado_Ponente2		= xGetElementById("NUM_CERTIFICADO_PONENTE2").value;
	var LugarEvento 				= xGetElementById("LUGAR_EVENTO_2").value;
	var FechaInicio					= xGetElementById('FECHA_APERTURA_FDCB').value;
	var FechaCulminacion			= xGetElementById('FECHA_CULMINACION_FDCB').value;
	var HoraInicio					= xGetElementById('sel_hora_ini').value;
	var MinutoInicio				= xGetElementById('sel_minutos_ini').value;
	var TurnoInicio					= xGetElementById('sel_turno_ini').value;
	var HoraCulmi					= xGetElementById('sel_hora_cul').value;
	var MinutoCulmi					= xGetElementById('sel_minutos_cul').value;
	var TurnoCulmi					= xGetElementById('sel_turno_cul').value;
	var CodPonente			        = xGetElementById('COD_PONENTE_ODC').value;
	var CodPonente2			        = xGetElementById('COD_PONENTE2_ODC').value;
	var temasOcultos		        = xGetElementById("temasOcultos").value;
	
  

	FechaInicio=DesFormatearFecha(FechaInicio);
	FechaCulminacion=DesFormatearFecha(FechaCulminacion);

	if (Certificado==true)
	Certificado=1;
	else
	Certificado=0;
	
	if (CertificadoPonente==true)
	CertificadoPonente=1;
	else
	CertificadoPonente=0;

	/******** Parte de los participantes ***/


	var K=0;
	var Arreglo=new Array();

		for(i=0;i<Form_PARTICIPANTES__ArregloArticulosContador;i++)
			if(Form_PARTICIPANTES__ArregloArticulos[i][6]==true)
			{
			Arreglo[K]=new Array(6);
			Arreglo[K][0]=Form_PARTICIPANTES__ArregloArticulos[i][0];//Codigo de LA PERSONA
			Arreglo[K][1]=Form_PARTICIPANTES__ArregloArticulos[i][1];//cédula
			Arreglo[K][2]=Form_PARTICIPANTES__ArregloArticulos[i][2];//nombre completo
			Arreglo[K][3]=Form_PARTICIPANTES__ArregloArticulos[i][3];//certificado
			Arreglo[K][4]=Form_PARTICIPANTES__ArregloArticulos[i][4];//recibió certificado
			Arreglo[K][5]=Form_PARTICIPANTES__ArregloArticulos[i][5];//N certificado
			K++;
			}

		if(K==0)
		{
		var msg="Por favor agregue participantes al evento."
		EventosMensaje(msg,"ROJO");	
		return;
		}

	//busca los participantes que esten repetidos
	for(i=0;i<K-1;i++)
		for(j=i+1;j<K;j++)
			if(Arreglo[i][0]==Arreglo[j][0])
			{
				var msg="La cédula "+Arreglo[i][1]+" correspondiente a  "+Arreglo[i][2]+" se encuentra repetida. Deje una y vuelva a intentarlo."
				EventosMensaje(msg,"ROJO");				
				return;
				}

	
	//Buscamos los participantes a los que se les haya entregado certificado, pero que sean cero
	/*for(i=0;i<K;i++)
		if((Arreglo[i][4]=="S" || Arreglo[i][4]=="s") && Arreglo[i][5]=="0000")
		{
			var msg="El participante "+Arreglo[i][1]+" posee certificado, pero no tiene registrado el número del certificado. REVISE."
			EventosMensaje(msg,"ROJO");			
			return;
			}

*/

	/***************************************/


	//Buscamos los participantes a los que NO se les haya entregado certificado, pero que intenten escribirle un número al certificado 
	/*for(i=0;i<K;i++){
		if((Arreglo[i][4]=="N" || Arreglo[i][4]=="n") && Arreglo[i][5]!="0000")
		{
			var msg="El participante de cédula: "+Arreglo[i][1]+" No posee certificado, por tanto no puede registrar el número del certificado. REVISE."
			EventosMensaje(msg,"ROJO");			
			return;
			}
	}*/

	//alert(Lista);
	//alert(mostrarTema1);
	
	
	/*Si es guardar nuevo*/	
	if(IDTipoEventoSeleccionActualLista==-1)
	{
		AjaxRequest.post({'parameters':{ 'caso':"EventosGuardar",
										'nombre_evento':NombreEvento,
										'tipo_evento':TipoEvento,
										'horas_evento':horasevento,
										'certificado':Certificado,
										'CertificadoPonente':CertificadoPonente,										
										'descripcion_evento':DescripcionEvento,
										//'tema_evento':TemaEvento,
										'lugar_evento':LugarEvento,
										'fecha_inicio':FechaInicio,
										'fecha_culminacion':FechaCulminacion,
										'hora_inicio':HoraInicio,
										'minuto_inicio':MinutoInicio,
										'turno_inicio':TurnoInicio,										
										'hora_culmi':HoraCulmi,
										'minuto_culmi':MinutoCulmi,
										'turno_culmi':TurnoCulmi,
										'cod_ponente':CodPonente,
										'cod_ponente2':CodPonente2,
										'Arreglo':Arreglo,
										'tam_arreglo':K,
										'Lista':temasOcultos},
					'onSuccess':GuardarMensaje,
					'url':'lib/controladorCes.php',
					'onError':function(req){alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
					});	

		}
		
	/*Si es modificar*/
	else
	{
		if(!confirm("¿Esta seguro que desea guardar los cambios?")){					
			return;
			}	
			
				
			
		AjaxRequest.post({'parameters':{ 'caso':"EventosModificar",
										'co_id_evento':IDTipoEventoSeleccionActualLista,										             
										'nombre_evento':NombreEvento,
										'tipo_evento':TipoEvento,
										'horas_evento':horasevento,									
										'certificado':Certificado,
										'CertificadoPonente':CertificadoPonente,
										'descripcion_evento':DescripcionEvento,
										'lugar_evento':LugarEvento,
										'fecha_inicio':FechaInicio,
										'fecha_culminacion':FechaCulminacion,
										'hora_inicio':HoraInicio,
										'minuto_inicio':MinutoInicio,
										'turno_inicio':TurnoInicio,										
										'hora_culmi':HoraCulmi,
										'minuto_culmi':MinutoCulmi,
										'turno_culmi':TurnoCulmi,
										'cod_ponente':CodPonente,	
										'cod_ponente2':CodPonente2,									
										'Arreglo':Arreglo,
										'tam_arreglo':K,
										'Certificado_Ponente':Certificado_Ponente,
										'Certificado_Ponente2':Certificado_Ponente2,
										'Lista':temasOcultos},
						'onSuccess':GuardarMensaje,
						'url':'lib/controladorCes.php',
						'onError':function(req){alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
						});
		}
			
		
	}
	
	
	function GuardarMensaje(req)
	{
	
	var respuesta = req.responseText;
	//var resultado = eval("(" + respuesta + ")");
	//alert (respuesta);

		if(respuesta==1){
			
			EventosMensaje("Los datos se guadaron satisfactoriamente.","VERDE");
			alert ("Los datos se guadaron satisfactoriamente");
			BuscarListado();
		//recargar();	
		//Form_TEMAS__DesactivarFormulario();		
		IDTipoEventoSeleccionActualLista=-1;
		DarFocoCampo("NOMBRE_EVENTO",1000);
		xGetElementById("FORMULARIO_EVENTOS").reset();
		xGetElementById("TIPO_EVENTO").innerHTML="";
		xGetElementById("LUGAR_EVENTO_2").innerHTML="";
		xGetElementById("TABLA_LISTA_PARTICIPANTES_FRDB").innerHTML="";		
		BuscarListado();		
		CargarSelectTipoArticulo();
		Form_DEFINICIONES_LUGARES__CargarSelectTipoCta();
		
		}
		else
		EventosMensaje("Error. No se pudo guardar los datos. Vuelva a intentarlo.","ROJO");
	}


	function EventosModificar()
	{
		EventosMensaje("");
		Form_TEMAS__ActivarFormulario();	
		//ActivarBoton("BOTON_GUARDAR_FDCB","IMG_GUARDAR_FDCB",'guardar');
		//DesactivarBoton("BOTON_ELIMINAR_FDCB","IMG_ELIMINAR_FDCB",'eliminar');
		//DesactivarBoton("BOTON_MODIFICAR_FDCB","IMG_MODIFICAR_FDCB",'modificar');
		//DesactivarBoton("BOTON_IMPRIMIR_FDCB","IMG_IMPRIMIR_CERT",'modificar');	

	}

/**
* Muestra el mensaje despues de guardar los datos
* @param {Array} req respuesta luego de guardar los datos 1=exito, !1=fracaso
*/


	function EventosEliminar()
	{
	//OJO. NUNCA DEBERIA CUMPLIRSE, PORQUE EL BOTON ELIMINAR ESTA DESACTIVADO. NO HAY ELEMENTO SELECIONADO.
	if(IDTipoEventoSeleccionActualLista==-1)
		return;

	if(!confirm("¿Está seguro que quiere eliminarlo?"))
		return;
	AjaxRequest.post({'parameters':{ 'caso':"EventosEliminar",
									'co_id_evento':IDTipoEventoSeleccionActualLista},
					 'onSuccess':EventosEliminarMensaje,
					 'url':'lib/controladorCes.php',
					 'onError':function(req){alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
					 });
	}

/**
* Muestra el mensaje al eliminar el elemento seleccionado
* @param {Array} req respuesta luego de guardar los datos 1=exito, !1=fracaso
*/
	function EventosEliminarMensaje(req)
	{
	var respuesta = req.responseText;

	//alert(respuesta);
	if(respuesta==1)
	{
		EventosMensaje("La eliminación se realizó satisfactoriamente.","VERDE");		
		IDTipoEventoSeleccionActualLista=-1;
		recargar();	
		EventosNuevo();		
		DarFocoCampo("NOMBRE_EVENTO",1000);
		xGetElementById("FORMULARIO_EVENTOS").reset();
		xGetElementById("TIPO_EVENTO").innerHTML="";	
		xGetElementById("TEMA_EVENTO").value="";	
		xGetElementById("temasOcultos").value="";
		xGetElementById("TABLA_LISTA_PARTICIPANTES_FRDB").innerHTML="";
	//	BuscarListado();
		CargarSelectTipoArticulo();			
		
		}
		
	else{
		EventosMensaje("Error. No se pudo eliminar los datos. Vuelva a intentarlo.","ROJO");		
		}
	}


	/*** Inicializar las ventanas externas que cargan a las PERSONA ***/
	function VentanaInicializar(id)
	{		
	switch(id){
		
	
		case "VENTANA_LISTA_PERSONA":
				//Form_UNIDADES_DE_MEDIDA__TabPane = new WebFXTabPane(xGetElementById("TABPANE_FUDM"), true);
 				LISTA_PERSONA__BuscarListado();
		break;

		case "VENTANA_LISTA_PARTICIPANTES":
				//Form_UNIDADES_DE_MEDIDA__TabPane = new WebFXTabPane(xGetElementById("TABPANE_FUDM"), true);
 				LISTA_PARTICIPANTES__BuscarListado();
		break;
		
		case "VENTANA_LUGAR_EVENTO":
				Form_DEFINICIONES_TIPOS_DE_CUENTA__TabPane = new WebFXTabPane(xGetElementById("TABPANE_FDTDC"), true);
				Form_DEFINICIONES_TIPOS_DE_CUENTA__Nuevo();
				//Form_DEFINICIONES_TIPOS_DE_CUENTA__Mensaje("Permite la definición o actualización de los posibles lugares donde se realizan Eventos en la CES .");
		break;	
		
		
		case "VENTANA_TEMA_EVENTO":
				Form_TEMA__TabPane = new WebFXTabPane(xGetElementById("TABPANE_TEMA"), true);
				Form_DEFINICIONES_TEMAS__Nuevo();
				//Form_DEFINICIONES_TIPOS_DE_CUENTA__Mensaje("Permite la definición o actualización de los posibles lugares donde se realizan Eventos en la CES .");
		break;		
			}
	}
	
	

	function LISTA_PERSONA__Buscar(){
	if(EstadoCheckBoxSombra=xGetElementById("BUSCAR_CHECKBOX_FLP").checked)
		return;
	LISTA_PERSONA__BuscarListado();
	}

	function LISTA_PERSONA__PresionarEnter(ev){
 	if(ev.keyCode==13)
		LISTA_PERSONA__BuscarListado();
	}

	/** Buscar a las PERSONA que aparecerán en la ventana externa ***/
	function LISTA_PERSONA__BuscarListado()
	{
	LISTA_PERSONA__IDSeleccionActualLista=-1;
	var CadenaBuscarpersona=xTrim(strtoupper(xGetElementById("LISTADO_BUSCAR_FLP").value));

	if(CadenaBuscarpersona!="")
		if(LISTA_PERSONA__BuscarListado_CadenaBuscar==CadenaBuscarpersona)
			return;
	LISTA_PERSONA__BuscarListado_CadenaBuscar=CadenaBuscarpersona;


	var ponentee=xGetElementById('COD_PONENTE_ODC').value;
	var ponente22=xGetElementById('COD_PONENTE2_ODC').value;

	Form_LISTA_PERSONA__MensajeCargando();
			AjaxRequest.post({'parameters':{'caso':"LISTA_PERSONA__BuscarListado",	
									'ponente':ponentee,	
									'ponente22':ponente22,								
									'CadenaBuscarpersona':CadenaBuscarpersona},
					'onSuccess':LISTA_PERSONA__MostrarListado,
					'url':'lib/controladorCes.php',
					'onError':function(req){alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
					});
		


	}

	var sw_FLP=false;


	/*** Cuando se le de al botón aceptar***/
	function LISTA_PERSONA__Aceptar()
	{
		if(LISTA_PERSONA__IDSeleccionActualLista==-1)
		{
		xGetElementById(LISTA_PERSONA__ID).value = "";
		xGetElementById(LISTA_PERSONA__IDRif).value = "";
		xGetElementById(LISTA_PERSONA__IDNombre).value = "";

		VentanaCerrar('VENTANA_LISTA_PERSONA');
		return;
		}

	xGetElementById(LISTA_PERSONA__ID).value = LISTA_PERSONA__IDSeleccionActualLista;
	xGetElementById(LISTA_PERSONA__IDRif).value = LISTA_PERSONA__IDCedSeleccionActualLista;
	xGetElementById(LISTA_PERSONA__IDNombre).value = LISTA_PERSONA__IDNombreSeleccionActualLista;
	
	VentanaCerrar('VENTANA_LISTA_PERSONA');

	}




	/*** Busca la persona que será ponente ****/

	function Form_LISTA_PERSONA__Abrir(_ID,_IDCed,_IDNombre)
	{
	LISTA_PERSONA__ID=_ID;
	LISTA_PERSONA__IDRif=_IDCed;
	LISTA_PERSONA__IDNombre=_IDNombre;

	_VentanaNueva('VENTANA_LISTA_PERSONA','LISTA DE PERSONA',680,330,'include/ventana_personas_siaces.php',true);
	}




	function LISTA_PERSONA__MostrarListado(req)
	{
	var respuesta = req.responseText;
	var resultado = eval("(" + respuesta + ")");
	var n=resultado.length;

	//alert(n);

	//Si hay mas de 1000 registros Desactivar Busqueda rapida y resaldado en las coincidencias.
	if(n>1000 && sw_FLP==false){
		sw_FLP=true;
		xGetElementById("SOMBRA_CHECKBOX_FLP").checked=false;
		xGetElementById("BUSCAR_CHECKBOX_FLP").checked=true;
		}

	var TextoBuscar=xTrim(strtoupper(xGetElementById("LISTADO_BUSCAR_FLP").value));
	var EstadoCheckBoxSombra=xGetElementById("SOMBRA_CHECKBOX_FLP").checked;

	xGetElementById("TABLA_LISTA_FLP").innerHTML="";

	var Contenido="";
	var FuncionOnclick;
	var FuncionOnDblclick;
	var FuncionOnMouseOver;
	var FuncionOnMouseOut;
	var CadAux1, CadAux2;

	for(var i=0;i< n; i++){
		FuncionOnclick="LISTA_PERSONA__SeleccionarElementoTabla('"
					+resultado[i]['CodPersona']+"','"
					+resultado[i]['Ndocumento']+"','"
					+resultado[i]['NomCompleto']+"')";
		FuncionOnDblclick="LISTA_PERSONA__Aceptar()";
		FuncionOnMouseOver="pintarFila(\"FLP"+resultado[i]['CodPersona']+"\")";
		FuncionOnMouseOut="despintarFila(\"FLP"+resultado[i]['CodPersona']+"\")";

		Contenido+="<TR id='FLP"+resultado[i]['CodPersona']+"' onclick=\""+FuncionOnclick+"\" ondblclick='"+FuncionOnDblclick+"' onmouseover='"+FuncionOnMouseOver+"' onmouseout='"+FuncionOnMouseOut+"'>";

		if(TextoBuscar!="" && EstadoCheckBoxSombra)
		{			
			CadAux1=str_replace(strtoupper(resultado[i]['Ndocumento']),"<strong>"+TextoBuscar+"</strong>",TextoBuscar);
			CadAux2=str_replace(strtoupper(resultado[i]['NomCompleto']),"<strong>"+TextoBuscar+"</strong>",TextoBuscar);
		}
		else{
			CadAux1=strtoupper(resultado[i]['Ndocumento']);
			CadAux2=strtoupper(resultado[i]['NomCompleto']);
			}

		Contenido+="<TD width='40%' class='FilaEstilo'>"+CadAux1+"</TD>";
		Contenido+="<TD width='45%' class='FilaEstilo'>"+CadAux2+"</TD>";

		Contenido+="</TR>";
		}

	xGetElementById("TABLA_LISTA_FLP").innerHTML=Contenido;
	xGetElementById("MSG_CARGANDO_FLP").innerHTML="";
	}



	function LISTA_PERSONA__SeleccionarElementoTabla(IDSeleccion, Ced, Nombre)
	{

	if(LISTA_PERSONA__IDSeleccionActualLista!=-1)
		xGetElementById("FLP"+LISTA_PERSONA__IDSeleccionActualLista).bgColor=colorFondoTabla;
	colorBase=colorSeleccionTabla;
	xGetElementById("FLP"+IDSeleccion).bgColor=colorBase;
	LISTA_PERSONA__IDSeleccionActualLista=IDSeleccion;
	LISTA_PERSONA__IDCedSeleccionActualLista=Ced;
	LISTA_PERSONA__IDNombreSeleccionActualLista=Nombre;		
	
	}


	function Form_LISTA_PERSONA__MensajeCargando()
	{
	//xGetElementById("MSG_CARGANDO_FLP").innerHTML="<DIV style=\"color : #959595; font-family : 'sans-serif', 'Arial','Bitstream Vera Sans'; font-size : 20px; font-style : normal; font-weight : bold; text-align : left;\" align=\"top\">Cargando... Por favor espere...</DIV>";
	xGetElementById("MSG_CARGANDO_FLP").innerHTML="<DIV style=\" display:block;color : #959595; font-family : 'sans-serif', 'Arial','Bitstream Vera Sans'; font-size : 20px; font-style : normal; font-weight : bold; text-align : left;  \" align=\"top\">Usuario No Encontrado...</DIV>";
	}



	var Form_PARTICIPANTES__ArregloArticulos=new Array();
	var Form_PARTICIPANTES__ArregloArticulosContador=0;

	
	
	function MostrarParticipantes(req)
	{
		var respuesta = req.responseText;	
		var resultado = eval("(" + respuesta + ")");
		var n=resultado.length;		
		//alert(n);


		for(i=0;i<n;i++)
		{

		Form_PARTICIPANTES__ArregloArticulos[i]=new Array(6);
		Form_PARTICIPANTES__ArregloArticulos[i][0]=resultado[i]['CodPersona'];
		Form_PARTICIPANTES__ArregloArticulos[i][1]=resultado[i]['Ndocumento'];
		Form_PARTICIPANTES__ArregloArticulos[i][2]=resultado[i]['NomCompleto'];

		if (resultado[i]['bo_culmino_evento']==1)
		Form_PARTICIPANTES__ArregloArticulos[i][3]='S';
		else if (resultado[i]['bo_culmino_evento']==0){
		Form_PARTICIPANTES__ArregloArticulos[i][3]='N';}

		if (resultado[i]['bo_recibio_certificado']==1)
		Form_PARTICIPANTES__ArregloArticulos[i][4]='S';
		else if (resultado[i]['bo_recibio_certificado']==0){
		Form_PARTICIPANTES__ArregloArticulos[i][4]='N';}
		
		if(resultado[i]['tx_nu_certificado']==0)
		Form_PARTICIPANTES__ArregloArticulos[i][5]="";
		else if (resultado[i]['tx_nu_certificado']!=0){
		Form_PARTICIPANTES__ArregloArticulos[i][5]=resultado[i]['tx_nu_certificado'];}
		
	//	Form_PARTICIPANTES__ArregloArticulos[i][5]=resultado[i]['tx_nu_certificado'];

		Form_PARTICIPANTES__ArregloArticulos[i][6]=true;
		
		//alert(Form_PARTICIPANTES__ArregloArticulos[i][5]);
		
		//alert(Form_PARTICIPANTES__IDSeleccionActualListaArticulos);

		}

	Form_PARTICIPANTES__ArregloArticulosContador=n;
	ArregloArticulosContador[0]=Form_PARTICIPANTES__ArregloArticulosContador;
	traePart=n;
	Form_PARTICIPANTES__MostrarTablaArticulos();

	}
	
	
	

	
	
	
	function Form_PARTICIPANTES__AgregarArticuloTabla()
	{
	if(xGetElementById("COD_PARTICIPANTES_AGREGAR_FRDB").value=="")
		return;

	var i=Form_PARTICIPANTES__ArregloArticulosContador;
	
	
	Form_PARTICIPANTES__ArregloArticulos[i]=new Array(6);
	Form_PARTICIPANTES__ArregloArticulos[i][0]=xGetElementById("COD_PARTICIPANTES_AGREGAR_FRDB").value;
	Form_PARTICIPANTES__ArregloArticulos[i][1]=xGetElementById("CEDULA_PARTICIPANTES_AGREGAR_FRDB").value;
	Form_PARTICIPANTES__ArregloArticulos[i][2]=xGetElementById("NOMBRE_PARTICIPANTES_AGREGAR_FRDB").value;
	Form_PARTICIPANTES__ArregloArticulos[i][3]="S";

	Form_PARTICIPANTES__ArregloArticulos[i][4]="S";
	Form_PARTICIPANTES__ArregloArticulos[i][5]="";

	Form_PARTICIPANTES__ArregloArticulos[i][6]=true;

	Form_PARTICIPANTES__ArregloArticulosContador++;
	ArregloArticulosContador[0]=Form_PARTICIPANTES__ArregloArticulosContador;
	Form_PARTICIPANTES__MostrarTablaArticulos();
	}
	



	function Form_PARTICIPANTES__MostrarTablaArticulos(Bloquear)
	{

	xGetElementById("TABLA_LISTA_PARTICIPANTES_FRDB").innerHTML="";
	var TextoBuscar1=xTrim(strtoupper(xGetElementById("LISTADO_BUSCAR_PARTICIPANTES").value));
	
		
	var Contenido="";
	var FuncionOnclick="";
	var FuncionOnDblclick="";
	var FuncionOnMouseOver="";
	var FuncionOnMouseOut="";
	var FuncionOnDblclickTDCantidad="";
	var FuncionOnDblclickTDCantidad1="";
	var FuncionOnDblclickTDCantidad2="";
	var Cantidad;
 	var Costo;
	var TOTAL=0;
	var j=1;
	var CadAuxFab, CadAuxFab2;

	valorPart=Form_PARTICIPANTES__ArregloArticulosContador;
	
	ArregloArticulosContador[0]=Form_PARTICIPANTES__ArregloArticulosContador;

	if (centinela[0]==0)
	{
	ArregloContPart[0]=valorPart;	
	}
	else if(centinela[0]==1)
	{	
	ArregloContPart[0]=(valorPart-centi[0]);		
	}
	
		
	
	for(var i=0;i<(Form_PARTICIPANTES__ArregloArticulosContador);i++)
	{
				if(Form_PARTICIPANTES__ArregloArticulos[i][6]==true){
			
				FuncionOnclick="";
				FuncionOnDblclick="";
				FuncionOnMouseOver="";
				FuncionOnMouseOut="";
				FuncionOnDblclickTDCantidad="";
				FuncionOnDblclickTDCantidad1="";
				FuncionOnDblclickTDCantidad2="";
					
				
				FuncionOnclick="Form_PARTICIPANTES__SeleccionarElementoTablaArticulos('"+i+"','"+Form_PARTICIPANTES__ArregloArticulos[i][1]+"')";
				FuncionOnDblclick="Form_PARTICIPANTES__ModificarArticuloTabla();";
				FuncionOnMouseOver="pintarFila(\"FRDB_A"+i+"\")";
				FuncionOnMouseOut="despintarFila(\"FRDB_A"+i+"\")";
				FuncionOnDblclickTDCantidad="Form_PARTICIPANTES__ModificarValorCelda('FRDB_A"+i+"_CANTIDAD')";
				
				FuncionOnDblclickTDCantidad1="Form_PARTICIPANTES__ModificarValorCelda1('FRDB_A"+i+"_CANTIDAD1')";
				FuncionOnDblclickTDCantidad2="Form_PARTICIPANTES__ModificarValorCelda2('FRDB_A"+i+"_CANTIDAD2')";
				
								
				//CadAuxFab=str_replace(strtoupper(Form_PARTICIPANTES__ArregloArticulos[i][1]),"<strong>"+TextoBuscar1+"</strong>",TextoBuscar1);
				
				CadAuxFab=(Form_PARTICIPANTES__ArregloArticulos[i][1]);
				CadAuxFab2=(Form_PARTICIPANTES__ArregloArticulos[i][2]);

			Contenido+="<TR id='FRDB_A"+i+"' onclick=\""+FuncionOnclick+"\" onmouseover='"+FuncionOnMouseOver+"' onmouseout='"+FuncionOnMouseOut+"'>";
			Contenido+="<TD width='1%' class='FilaEstilo' align='center'></TD>";			
			Contenido+="<TD width='14%' class='FilaEstilo'>" +CadAuxFab+"</TD>";
			Contenido+="<TD width='40%' class='FilaEstilo'>" +CadAuxFab2+"</TD>";
			Contenido+="<TD id='FRDB_A"+i+"_CANTIDAD' width='15%' class='FilaEstilo' align='center' ondblclick=\""+FuncionOnDblclickTDCantidad+"\">"+conMayusculas(Form_PARTICIPANTES__ArregloArticulos[i][3])+"</TD>";
			Contenido+="<TD id='FRDB_A"+i+"_CANTIDAD1' width='15%' class='FilaEstilo' align='center' ondblclick=\""+FuncionOnDblclickTDCantidad1+"\">"+conMayusculas(Form_PARTICIPANTES__ArregloArticulos[i][4])+"</TD>";
			Contenido+="<TD id='FRDB_A"+i+"_CANTIDAD2' width='15%' class='FilaEstilo' align='center' ondblclick=\""+FuncionOnDblclickTDCantidad2+"\">"+Form_PARTICIPANTES__ArregloArticulos[i][5]+"</TD>";

			Contenido+="</TR>";
		
	
		}//fin if
	

			}//fin for
			
			
	xGetElementById("TABLA_LISTA_PARTICIPANTES_FRDB").innerHTML=Contenido;
	xGetElementById("numparticipantes").innerHTML=ArregloContPart[0];			
					
	}
	
	
			/*** PARA DISMINUIR PARTICIPANTES, ESTO OCURRE PRESIONANDO EL BOTON QUITAR ***/	
	function Form_PARTICIPANTES__QuitarArticuloTabla()
	{
		if(Form_PARTICIPANTES__IDSeleccionActualListaArticulos==-1)
		return;

	Form_PARTICIPANTES__ArregloArticulos[Form_PARTICIPANTES__IDSeleccionActualListaArticulos][6]=false;	
	
	var valEliminar=(Form_PARTICIPANTES__ArregloArticulos[Form_PARTICIPANTES__IDSeleccionActualListaArticulos][1]);	
		
	ArregloArticulosContador[0]=Form_PARTICIPANTES__ArregloArticulosContador;	
	cedSeleccionada[0]=Form_PARTICIPANTES__IDSeleccionActualListaArticulos;
	
	
	cedSeleccionada[0]=valEliminar;
		
	ArregloContPart[0]=(ArregloContPart[0]-1);	
	centinela[0]=1;
	contarParteliminados++;
	centi[0]=contarParteliminados;	
	
	prueba();
	
	Form_PARTICIPANTES__IDSeleccionActualListaArticulos=-1;
	
	
	
	Form_PARTICIPANTES__MostrarTablaArticulos();
	EventosMensaje("");
	}
	
	
		

	function Form_PARTICIPANTES__ModificarValorCelda(_IDCelda)
	{
		if(xGetElementById("txt_celda_"+_IDCelda))
		return;

	Valor=Form_PARTICIPANTES__ArregloArticulos[Form_PARTICIPANTES__IDSeleccionActualListaArticulos][3];

	xGetElementById(_IDCelda).innerHTML="<INPUT id='txt_celda_"+_IDCelda+"' class='TextoCampoInputTabla' maxlength='1' type='text' size='15' value='"+Valor+"' onblur=\"Form_PARTICIPANTES__ModificarValorCeldaPierdeFoco('"+_IDCelda+"',"+Form_PARTICIPANTES__IDSeleccionActualListaArticulos+")\" onkeypress=\"return soloLet_S_N(event);\" style='text-align : center;'>";
	xGetElementById("txt_celda_"+_IDCelda).focus();
	}



	function Form_PARTICIPANTES__ModificarValorCelda1(_IDCelda)
	{
		if(xGetElementById("txt_celda_"+_IDCelda))
		return;

	Valor=Form_PARTICIPANTES__ArregloArticulos[Form_PARTICIPANTES__IDSeleccionActualListaArticulos][4];

	xGetElementById(_IDCelda).innerHTML="<INPUT id='txt_celda_"+_IDCelda+"' class='TextoCampoInputTabla' maxlength='1' type='text' size='15' value='"+Valor+"' onblur=\"Form_PARTICIPANTES__ModificarValorCeldaPierdeFoco1('"+_IDCelda+"',"+Form_PARTICIPANTES__IDSeleccionActualListaArticulos+")\" onkeypress=\"return soloLet_S_N(event);\" style='text-align : center;'>";
	xGetElementById("txt_celda_"+_IDCelda).focus();
	}


	function Form_PARTICIPANTES__ModificarValorCelda2(_IDCelda)
	{
		if(xGetElementById("txt_celda_"+_IDCelda))
		return;

	Valor=Form_PARTICIPANTES__ArregloArticulos[Form_PARTICIPANTES__IDSeleccionActualListaArticulos][5];

	xGetElementById(_IDCelda).innerHTML="<INPUT id='txt_celda_"+_IDCelda+"' class='TextoCampoInputTabla' maxlength='4' type='text' size='15' value='"+Valor+"' onblur=\"Form_PARTICIPANTES__ModificarValorCeldaPierdeFoco2('"+_IDCelda+"',"+Form_PARTICIPANTES__IDSeleccionActualListaArticulos+")\" onkeypress=\"return soloNum(event);\" style='text-align : center;'>";
	xGetElementById("txt_celda_"+_IDCelda).focus();
	}




	function Form_PARTICIPANTES__ModificarValorCeldaPierdeFoco(_IDCelda,indice_modificar)
	{
	Form_PARTICIPANTES__ArregloArticulos[indice_modificar][3]=(xGetElementById("txt_celda_"+_IDCelda).value);
	xGetElementById(_IDCelda).innerHTML=(Form_PARTICIPANTES__ArregloArticulos[indice_modificar][3]);
	}

	function Form_PARTICIPANTES__ModificarValorCeldaPierdeFoco1(_IDCelda,indice_modificar)
	{
	Form_PARTICIPANTES__ArregloArticulos[indice_modificar][4]=(xGetElementById("txt_celda_"+_IDCelda).value);
	xGetElementById(_IDCelda).innerHTML=(Form_PARTICIPANTES__ArregloArticulos[indice_modificar][4]);
	}

	function Form_PARTICIPANTES__ModificarValorCeldaPierdeFoco2(_IDCelda,indice_modificar)
	{
	Form_PARTICIPANTES__ArregloArticulos[indice_modificar][5]=(xGetElementById("txt_celda_"+_IDCelda).value);
	xGetElementById(_IDCelda).innerHTML=(Form_PARTICIPANTES__ArregloArticulos[indice_modificar][5]);
	}



	var Form_PARTICIPANTES__IDSeleccionActualListaArticulos=-1;



	function Form_PARTICIPANTES__SeleccionarElementoTablaArticulos(IDSeleccion,cedulaSeleccionada)
	{
	if(Form_PARTICIPANTES__IDSeleccionActualListaArticulos!=-1)
		xGetElementById("FRDB_A"+Form_PARTICIPANTES__IDSeleccionActualListaArticulos).bgColor=colorFondoTabla;
	colorBase=colorSeleccionTabla;
	xGetElementById("FRDB_A"+IDSeleccion).bgColor=colorBase;
	
	Form_PARTICIPANTES__IDSeleccionActualListaArticulos=IDSeleccion;
	CedGlobal=cedulaSeleccionada;
	
	//alert(cedulaSeleccionada);
	
	}



	var codEventoSeleccionadoVector=new Array();

	function eventoSeleccionado(codEventoSeleccionado)
	{		
		codEventoSeleccionadoVector[0]=codEventoSeleccionado;
		
	}



	function Form_IMPRIMIR()
	{
	
	var mes=xGetElementById("ID_RADIO_IMPRIMIR_EVENTO_MES").checked;
	var trimestre=xGetElementById("ID_RADIO_IMPRIMIR_EVENTO_TRIMESTRE").checked;
	var participantes=xGetElementById("ID_RADIO_IMPRIMIR_EVENTO_PARTICIPANTES").checked;


	if (codEventoSeleccionadoVector[0]>=1 && codEventoSeleccionadoVector[0]!="" && participantes==true )
	{
		window.open("../ev/reportes/reporte_2.php?codEventoS="+codEventoSeleccionadoVector[0]);
	}


	if (participantes==false)
	{
	if(codEventoSeleccionadoVector[0]!=null || codEventoSeleccionadoVector[0]=="" || codEventoSeleccionadoVector[0]==null || codEventoSeleccionadoVector[0]=="null"  )
	{	

		if (mes==true && trimestre==false && participantes==false)
		{
			var mes= (xGetElementById("SELECT_TIPO_MES").value);		
			window.open("../ev/reportes/reporte_1.php?mes="+mes+"&tipo=1");
		}


		if (mes==false && trimestre==true && participantes==false)
		{
			var mes= (xGetElementById("SELECT_TIPO_TRIMESTRE").value);		
			window.open("../ev/reportes/reporte_1.php?mes="+mes+"&tipo=2");
		}
	}
	}
		
	}
	
	
	/*Actualiza el select Tipo lugares, es llamado desde el formulario ventana_lugares_eventos_siaces.js, al agregar o al eliminar*/
function Form_DEFINICIONES_LUGARES__ActualizarSelectTipoCuenta(){
	if(xGetElementById("LUGAR_EVENTO_2"))
		Form_DEFINICIONES_LUGARES__CargarSelectTipoCta();
	}


/*Carga el listado de tipos de LUGARES en el select*/
function Form_DEFINICIONES_LUGARES__CargarSelectTipoCta(){
				 
	AjaxRequest.post({'parameters':{ 'caso':"LUGARES__BuscarListadoModificar",
									'CadenaBuscar':"",'Modificar':LUGARES__IDTipoCuentaSeleccionActualLista},
					 'onSuccess': Form_LUGARES__CargarSelectMostrarTipoCta,
					 'url':'lib/controladorCes.php',
					 'onError':function(req){alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
					 });	
	}


/*Despues que cargar lo tipos de LUGARES, los mostramos en el select*/
function Form_LUGARES__CargarSelectMostrarTipoCta(req){
	var respuesta = req.responseText;
    var resultado = eval("(" + respuesta + ")");
	var SelectTipoCta = xGetElementById("LUGAR_EVENTO_2");
	SelectTipoCta.innerHTML="";
	var opcion;
	
	//Cuando es nuevo, sale por defecto SELECCIONE | AGREGUE
	if(LUGARES__IDTipoCuentaSeleccionActualLista==-1){
		opcion = mD.agregaNodoElemento("option", null, null, { 'value':"" } );
		opcion.innerHTML="SELECCIONE | AGREGUE";
		mD.agregaHijo(SelectTipoCta, opcion);
		for(var i=0; i<resultado.length; i++){
			opcion = mD.agregaNodoElemento("option", null, null, { 'value':resultado[i]['co_lugares'] } );
			
			var str=resultado[i]['nombre_lugar'];
			
			var maximo = 30;
			//Se saca el total de caracteres
			var longitud = str.length;
			//Comprobamos si supera el maximo de caracteres
			if (longitud > maximo) {
			//Si es asi lo acortamos
				opcion.innerHTML=(str.substr(0,40))+"...";
			} else {
			//Si no lo supera lo dejamos como estaba
				opcion.innerHTML=str;}
			mD.agregaHijo(SelectTipoCta, opcion);
			}
		}
	//Cuando es modificar, sale por defecto el guardado
	else{
		for(var i=0; i<resultado.length; i++){
			if(LUGARES__IDTipoCuentaSeleccionActualLista==resultado[i]['co_lugares'])
						
				opcion = mD.agregaNodoElemento("option", null, null, { 'value':resultado[i]['co_lugares'], 'selected':true} );
			else
				opcion = mD.agregaNodoElemento("option", null, null, { 'value':resultado[i]['co_lugares']} );
				
			var str=resultado[i]['nombre_lugar'];
				
			var maximo = 30;
			//Se saca el total de caracteres
			var longitud = str.length;
			//Comprobamos si supera el maximo de caracteres
			if (longitud > maximo) {
			//Si es asi lo acortamos
				opcion.innerHTML=(str.substr(0,40))+"...";
			} else {
			//Si no lo supera lo dejamos como estaba
				opcion.innerHTML=str;}
				
		//	opcion.innerHTML=resultado[i]['nombre_lugar'];
			mD.agregaHijo(SelectTipoCta, opcion);
			}
		}
	}
	
	function imprimirRecibosPago() {
		
		var Cert = xGetElementById("FORMULARIO_EVENTOS").ESTADO_CERTIFICADO[0].checked;
		
			if(Cert==true)
			{
				codeventosiaces=xGetElementById("COD_EVENTO_SIACES").value;		
				window.open("../ev/reportes/certificados.php?evento="+codeventosiaces);	
			}	
			
			else
			{
				
				alert("El Evento Seleccionado No posee certificados");
			}	
	
		
  }	
  
  	function imprimirRecibosPago2() 
  	{
		
		var Cert = xGetElementById("FORMULARIO_EVENTOS").ESTADO_CERTIFICADO1[0].checked;
		
			if(Cert==true)
			{
				codeventosiaces=xGetElementById("COD_EVENTO_SIACES").value;		
				window.open("../ev/reportes/certificados_ponente.php?evento="+codeventosiaces);	
			}	
			
			else
			{
				
				alert("El Evento Seleccionado No tiene registrado certificados para los ponentes");
			}	
	
		
  }
  
    function imprimirRecibosPago22() 
  	{
		
		var Cert = xGetElementById("FORMULARIO_EVENTOS").ESTADO_CERTIFICADO1[0].checked;
		var Codigoponente2=xGetElementById("COD_PONENTE2_ODC").value;
		
		
			if(Codigoponente2=="" || Codigoponente2==null)	
			{
				alert("El Evento Seleccionado No tiene registrado un segundo Ponente");
				return;
			}
		
			if(Cert==true)
			{
				codeventosiaces=xGetElementById("COD_EVENTO_SIACES").value;		
				window.open("../ev/reportes/certificados_ponente_1.php?evento="+codeventosiaces);	
			}	
			
			else
			{
				
				alert("El Evento Seleccionado No tiene registrado certificados para los ponentes");
				return;
			}
			
			
						
			
	
		
  }
  
  
  
  
    function Form_TEMAS__DesactivarFormulario()
  	{
  	
		xGetElementById("NOMBRE_EVENTO").readOnly=true;
		xGetElementById("HORAS_EVENTO").readOnly=true;		
		xGetElementById("TIPO_EVENTO").readOnly=true;
		xGetElementById("DESCRIPCION_EVENTO").readOnly=true;
		xGetElementById("TEMA_EVENTO").readOnly=true;
		xGetElementById("LUGAR_EVENTO_2").readOnly=true;
		xGetElementById('FECHA_APERTURA_FDCB').readOnly=true;
		xGetElementById('FECHA_CULMINACION_FDCB').readOnly=true;
		xGetElementById('SELECT_EP_FILM').readOnly=true;
	
		
		xGetElementById("TIPO_EVENTO").disabled=true;
		xGetElementById("LUGAR_EVENTO_2").disabled=true;
		
		xGetElementById("sel_hora_ini").disabled=true;
		xGetElementById("sel_minutos_ini").disabled=true;
		xGetElementById("sel_turno_ini").disabled=true;
		
		xGetElementById("sel_hora_cul").disabled=true;
		xGetElementById("sel_minutos_cul").disabled=true;
		xGetElementById("sel_turno_cul").disabled=true;
		
		xGetElementById("SELECT_EP_FILM").disabled=true;
		
			
		xGetElementById("FORMULARIO_EVENTOS").ESTADO_CERTIFICADO[0].readOnly=true;
		xGetElementById("FORMULARIO_EVENTOS").ESTADO_CERTIFICADO[1].readOnly=true;
		
		xGetElementById("FORMULARIO_EVENTOS").ESTADO_CERTIFICADO1[0].readOnly=true;
		xGetElementById("FORMULARIO_EVENTOS").ESTADO_CERTIFICADO1[1].readOnly=true;
			
		xGetElementById("IMG_FECHA_APERTURA_FDCB").setAttribute('onclick',"");		
		DesactivarBoton("IMG_FECHA_APERTURA_FDCB","IMG_FECHA_APERTURA_FDCB",'calendario');
		
		xGetElementById("IMG_FECHA_CULMINACION_FDCB").setAttribute('onclick',"");		
		DesactivarBoton("IMG_FECHA_CULMINACION_FDCB","IMG_FECHA_CULMINACION_FDCB",'calendario');		
		
		xGetElementById("IMG_BUSCAR_PONENTE_ODC").setAttribute('onclick',"");
		DesactivarBoton("IMG_BUSCAR_PONENTE_ODC","IMG_BUSCAR_PONENTE_ODC",'buscar');
		
		xGetElementById("IMG_BUSCAR_PONENTE2_ODC").setAttribute('onclick',"");
		DesactivarBoton("IMG_BUSCAR_PONENTE2_ODC","IMG_BUSCAR_PONENTE2_ODC",'buscar');
		
		
		xGetElementById("IMG_LUGAR_EVENTO_2").setAttribute('onclick',"");
		DesactivarBoton("IMG_LUGAR_EVENTO_2","IMG_LUGAR_EVENTO_2",'agregar');
		
		xGetElementById("IMG_TEMA_EVENTO_2").setAttribute('onclick',"");
		DesactivarBoton("IMG_TEMA_EVENTO_2","IMG_TEMA_EVENTO_2",'agregar');	
		
		xGetElementById("IMG_TEMA_EVENTO_AGREGAR").setAttribute('onclick',"");
		DesactivarBoton("IMG_TEMA_EVENTO_AGREGAR","IMG_TEMA_EVENTO_AGREGAR",'aceptar');
		
		xGetElementById("IMG_CERTIFICADO_PONENTE").setAttribute('onclick',"");
		DesactivarBoton("IMG_CERTIFICADO_PONENTE","IMG_CERTIFICADO_PONENTE",'modificar');
		
		xGetElementById("IMG_CERTIFICADO_PONENTE2").setAttribute('onclick',"");
		DesactivarBoton("IMG_CERTIFICADO_PONENTE2","IMG_CERTIFICADO_PONENTE2",'modificar');
		
		xGetElementById("BOTON_AGREGAR_FRDB").setAttribute('onclick',"");		
		xGetElementById("BOTON_QUITAR_FRDB").setAttribute('onclick',"");		
		
		xGetElementById("NOMBRE_EVENTO").setAttribute('class','TextoCampoInputDesactivado');
		xGetElementById("TIPO_EVENTO").setAttribute('class','TextoCampoInputDesactivado');
		xGetElementById("DESCRIPCION_EVENTO").setAttribute('class','TextoCampoInputDesactivado');
		xGetElementById("TEMA_EVENTO").setAttribute('class','TextoCampoInputDesactivado');
		xGetElementById("LUGAR_EVENTO_2").setAttribute('class','TextoCampoInputDesactivado');
		xGetElementById("FECHA_APERTURA_FDCB").setAttribute('class','TextoCampoInputDesactivado');
		xGetElementById("FECHA_CULMINACION_FDCB").setAttribute('class','TextoCampoInputDesactivado');
		xGetElementById('SELECT_EP_FILM').setAttribute('class','TextoCampoInputDesactivado');
		
		}
	
	
	    function Form_TEMAS__ActivarFormulario()
  		{
  	
		xGetElementById("NOMBRE_EVENTO").readOnly=false;
		xGetElementById("HORAS_EVENTO").readOnly=false;		
		xGetElementById("TIPO_EVENTO").readOnly=false;
		xGetElementById("DESCRIPCION_EVENTO").readOnly=false;
		//xGetElementById("TEMA_EVENTO").readOnly=false;
		xGetElementById("LUGAR_EVENTO_2").readOnly=false;
		xGetElementById('FECHA_APERTURA_FDCB').readOnly=true;
		xGetElementById('FECHA_CULMINACION_FDCB').readOnly=true;
		xGetElementById('SELECT_EP_FILM').readOnly=false;
	
		
		xGetElementById("TIPO_EVENTO").disabled=false;
		xGetElementById("LUGAR_EVENTO_2").disabled=false;
		
		xGetElementById("sel_hora_ini").disabled=false;
		xGetElementById("sel_minutos_ini").disabled=false;
		xGetElementById("sel_turno_ini").disabled=false;
		
		xGetElementById("sel_hora_cul").disabled=false;
		xGetElementById("sel_minutos_cul").disabled=false;
		xGetElementById("sel_turno_cul").disabled=false;
		
		xGetElementById("SELECT_EP_FILM").disabled=false;
		
			
		xGetElementById("FORMULARIO_EVENTOS").ESTADO_CERTIFICADO[0].readOnly=false;
		xGetElementById("FORMULARIO_EVENTOS").ESTADO_CERTIFICADO[1].readOnly=false;
		
		xGetElementById("FORMULARIO_EVENTOS").ESTADO_CERTIFICADO1[0].readOnly=false;
		xGetElementById("FORMULARIO_EVENTOS").ESTADO_CERTIFICADO1[1].readOnly=false;		
		
		
		xGetElementById("IMG_FECHA_APERTURA_FDCB").setAttribute('onclick',"showCalendar('FECHA_APERTURA_FDCB','%d/%m/%Y');");		
		ActivarBoton("IMG_FECHA_APERTURA_FDCB","IMG_FECHA_APERTURA_FDCB",'calendario');
		
		
		xGetElementById("IMG_FECHA_CULMINACION_FDCB").setAttribute('onclick',"showCalendar('FECHA_CULMINACION_FDCB','%d/%m/%Y');");		
		ActivarBoton("IMG_FECHA_CULMINACION_FDCB","IMG_FECHA_CULMINACION_FDCB",'calendario');
		
		
		xGetElementById("IMG_BUSCAR_PONENTE_ODC").setAttribute('onclick',"Form_LISTA_PERSONA__Abrir('COD_PONENTE_ODC','CED_PONENTE_ODC','NOMBRE_PONENTE_ODC');");
		ActivarBoton("IMG_BUSCAR_PONENTE_ODC","IMG_BUSCAR_PONENTE_ODC",'buscar');
		
		xGetElementById("IMG_BUSCAR_PONENTE2_ODC").setAttribute('onclick',"Form_LISTA_PERSONA__Abrir('COD_PONENTE2_ODC','CED_PONENTE2_ODC','NOMBRE_PONENTE2_ODC');");
		ActivarBoton("IMG_BUSCAR_PONENTE2_ODC","IMG_BUSCAR_PONENTE2_ODC",'buscar');
		
		xGetElementById("IMG_LUGAR_EVENTO_2").setAttribute('onclick',"_VentanaNueva('VENTANA_LUGAR_EVENTO','DEFINICIONES DE LOS LUGARES DE LOS EVENTOS',700,410,'ventana_lugares_eventos_siaces.php',true);");	
		ActivarBoton("IMG_LUGAR_EVENTO_2","IMG_LUGAR_EVENTO_2",'agregar');
		
		xGetElementById("IMG_TEMA_EVENTO_2").setAttribute('onclick',"_VentanaNueva('VENTANA_TEMA_EVENTO','DEFINICIONES DE LOS TEMAS DE LOS EVENTOS',700,410,'ventana_temas_eventos.php',true);");
		ActivarBoton("IMG_TEMA_EVENTO_2","IMG_TEMA_EVENTO_2",'agregar');	
		
		xGetElementById("IMG_TEMA_EVENTO_AGREGAR").setAttribute('onclick',"Form_IMPRIMIR_TEMAS__Visualizar();");
		ActivarBoton("IMG_TEMA_EVENTO_AGREGAR","IMG_TEMA_EVENTO_AGREGAR",'aceptar');
		
		xGetElementById("IMG_CERTIFICADO_PONENTE").setAttribute('onclick',"imprimirRecibosPago2();");
		ActivarBoton("IMG_CERTIFICADO_PONENTE","IMG_CERTIFICADO_PONENTE",'modificar');
		
		xGetElementById("IMG_CERTIFICADO_PONENTE2").setAttribute('onclick',"imprimirRecibosPago22();");
		ActivarBoton("IMG_CERTIFICADO_PONENTE2","IMG_CERTIFICADO_PONENTE2",'modificar');
		
		xGetElementById("BOTON_AGREGAR_FRDB").setAttribute('onclick',"Form_LISTA_PARTICIPANTES__Abrir('COD_PARTICIPANTES_AGREGAR_FRDB','CEDULA_PARTICIPANTES_AGREGAR_FRDB','NOMBRE_PARTICIPANTES_AGREGAR_FRDB','Form_PARTICIPANTES__AgregarArticuloTabla();')");		
		xGetElementById("BOTON_QUITAR_FRDB").setAttribute('onclick',"Form_PARTICIPANTES__QuitarArticuloTabla();");
		
				
		xGetElementById("NOMBRE_EVENTO").setAttribute('class','TextoCampoInputObligatorios');
		xGetElementById("TIPO_EVENTO").setAttribute('class','TextoCampoInput');
		xGetElementById("DESCRIPCION_EVENTO").setAttribute('class','TextoCampoInputObligatorios');
		//xGetElementById("TEMA_EVENTO").setAttribute('class','TextoCampoInputObligatorios');
		xGetElementById("LUGAR_EVENTO_2").setAttribute('class','TextoCampoInput');
		xGetElementById("FECHA_APERTURA_FDCB").setAttribute('class','TextoCampoInputObligatorios');
		xGetElementById("FECHA_CULMINACION_FDCB").setAttribute('class','TextoCampoInputObligatorios');
		xGetElementById('SELECT_EP_FILM').setAttribute('class','TextoCampoInput');
			
	                                                           
  		}
  

	

