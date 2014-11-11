var Form_LISTA_PARTICIPANTES__IDSeleccionActualLista="";
var Form_LISTA_PARTICIPANTES__IDcedulaSeleccionActualLista="";
var Form_LISTA_PARTICIPANTES__IDNombreSeleccionActualLista="";

var Form_LISTA_PARTICIPANTES__ID= "";
var Form_LISTA_PARTICIPANTES__IDCedula= "";
var Form_LISTA_PARTICIPANTES__IDNombre= "";	

	var K=0;
	var ArregloP=new Array();
	var m=0;
	var p=0;
	var pp=0;
	var contador=0;
	var contador1=0;
	var e=0;
	var a=0;
	var ari=0;
	var ari1=0;
	var syr=0;
	var s=0;
	var y;

function Form_LISTA_PROVEEDOR__LimpiarInputTextBuscarListado(){
	
	Form_LISTA_PARTICIPANTES__IDSeleccionActualLista=-1;
 	xGetElementById("LISTADO_BUSCAR_FLP1").value=""; 	
 	xGetElementById("TABLA_LISTA_PARTICIPANTES_FLP1").innerHTML="";
 	LISTA_PARTICIPANTES__BuscarListado();
 	DarFocoCampo("TABLA_LISTA_PARTICIPANTES_FLP1",1000);
	}



	function Form_LISTA_PARTICIPANTES__Abrir(_ID,_IDCedula,_IDNombre,_FuncionPostAcceptar)
	{
	Form_LISTA_PARTICIPANTES__ID=_ID;
	Form_LISTA_PARTICIPANTES__IDCedula=_IDCedula;
	Form_LISTA_PARTICIPANTES__IDNombre=_IDNombre;
	
	
		
	Form_LISTA_PARTICIPANTES__FuncionPostAcceptar="";
	if(!_FuncionPostAcceptar)
		Form_LISTA_PARTICIPANTES__FuncionPostAcceptar="";
	else{
		
		
		Form_LISTA_PARTICIPANTES__FuncionPostAcceptar=_FuncionPostAcceptar;
	}
		
		
	
	_VentanaNueva('VENTANA_LISTA_PARTICIPANTES','LISTA DE PARTICIPANTES',680,350,'include/ventana_participantes_siaces.php',true);
	}



	function LISTA_PARTICIPANTES__Buscar()
	{
		if(EstadoCheckBoxSombra=xGetElementById("BUSCAR_CHECKBOX_FLP1").checked)
		return;
	LISTA_PARTICIPANTES__BuscarListado();
	}

	function LISTA_PARTICIPANTES__PresionarEnter(ev){
 	if(ev.keyCode==13)
		LISTA_PARTICIPANTES__BuscarListado();
	}


	/** Buscar a las PARTICIPANTES que aparecerán en la ventana externa ***/
	function LISTA_PARTICIPANTES__BuscarListado()
	{
	Form_LISTA_PARTICIPANTES__IDSeleccionActualLista=-1;

	
	var CadenaBuscarParticipantes=xTrim(strtoupper(xGetElementById("LISTADO_BUSCAR_FLP1").value));
	
	if(CadenaBuscarParticipantes!="")
		if(LISTA_PARTICIPANTES__BuscarListado_CadenaBuscar==CadenaBuscarParticipantes)
		return;

	LISTA_PARTICIPANTES__BuscarListado_CadenaBuscar=CadenaBuscarParticipantes;
	
	var ponente=xGetElementById('COD_PONENTE_ODC').value;
	var ponente2=xGetElementById('COD_PONENTE2_ODC').value;

	Form_LISTA_PARTICIPANTES__MensajeCargando1();
	
	
	if (xGetElementById("DEPENDENCIA").checked==false)
	{
		AjaxRequest.post({'parameters':{'caso':"LISTA_PARTICIPANTES__BuscarListado",
									'ponente':ponente,
									'ponente2':ponente2,
									'CadenaBuscarparticipantes':CadenaBuscarParticipantes},
					'onSuccess':LISTA_PARTICIPANTES__MostrarListado,
					'url':'lib/controladorCes.php',
					'onError':function(req){alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
					});
	}
	
	if (xGetElementById("DEPENDENCIA").checked==true)
	{
		AjaxRequest.post({'parameters':{'caso':"LISTA_PARTICIPANTES_DEPENDENCIA__BuscarListado",
									'ponente':ponente,
									'CadenaBuscarparticipantes':CadenaBuscarParticipantes},
					'onSuccess':LISTA_PARTICIPANTES__MostrarListado,
					'url':'lib/controladorCes.php',
					'onError':function(req){alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
					});
		
	}	
	}

	var sw_FLP=false;


	function LISTA_PARTICIPANTES__MostrarListado(req)
	{
	var respuesta = req.responseText;
	var resultado = eval("(" + respuesta + ")");
	
	//alert(resultado);
	var n=resultado.length;

	

	//Si hay mas de 1000 registros Desactivar Busqueda rapida y resaldado en las coincidencias.
	if(n>1000 && sw_FLP==false){
		sw_FLP=true;
		xGetElementById("SOMBRA_CHECKBOX_FLP1").checked=false;
		xGetElementById("BUSCAR_CHECKBOX_FLP1").checked=true;
		}

	//var TextoBuscar=xGetElementById("LISTADO_BUSCAR_FLP1").value;
	var TextoBuscar11=xTrim(strtoupper(xGetElementById("LISTADO_BUSCAR_FLP1").value));
	var EstadoCheckBoxSombra=xGetElementById("SOMBRA_CHECKBOX_FLP1").checked;

	xGetElementById("TABLA_LISTA_PARTICIPANTES_FLP1").innerHTML="";

	var Contenido="";
	var FuncionOnclick;
	var FuncionOnDblclick;
	var FuncionOnMouseOver;
	var FuncionOnMouseOut;
	var CadAuxAA, CadAuxBB;
	

	for(var i=0;i<n; i++){
	


		FuncionOnclick="LISTA_PARTICIPANTES__SeleccionarElementoTabla('"
					+resultado[i]['CodPersona']+"','"
					+resultado[i]['Ndocumento']+"','"			
					+resultado[i]['NomCompleto']+"','"			
					+i+"')";
		//FuncionOnDblclick="LISTA_PARTICIPANTES__Aceptar()";
		FuncionOnMouseOver="pintarFila(\"FLP1"+resultado[i]['CodPersona']+"\")";
		FuncionOnMouseOut="despintarFila(\"FLP1"+resultado[i]['CodPersona']+"\")";

		Contenido+="<TR id='FLP1"+resultado[i]['CodPersona']+"' onclick=\""+FuncionOnclick+"\" ondblclick='"+FuncionOnDblclick+"' onmouseover='"+FuncionOnMouseOver+"' onmouseout='"+FuncionOnMouseOut+"'>";
		
	
	/*	if(TextoBuscar!="" && EstadoCheckBoxSombra)
		{			
		//	CadAuxAA=str_replace(strtoupper(resultado[i]['Ndocumento']),"<strong>"+TextoBuscar+"</strong>",TextoBuscar);
			
			CadAuxAA=strtoupper(resultado[i]['Ndocumento']);	
		//	CadAuxBB=str_replace(strtoupper(resultado[i]['NomCompleto']),"<strong>"+TextoBuscar+"</strong>",TextoBuscar);
			//CadAuxBB=(strtoupper(resultado[i]['NomCompleto']);
		}
		else{
			
			CadAuxAA=strtoupper(resultado[i]['Ndocumento']);
			CadAuxBB=strtoupper(resultado[i]['NomCompleto']);
			}
*/

			CadAuxAA=strtoupper(resultado[i]['Ndocumento']);
			CadAuxBB=strtoupper(resultado[i]['NomCompleto']);
			
		Contenido+="<TD width='5%' class='FilaEstilo'><input type='checkbox' id='checkpart"+i+"'><input type='hidden' id='CodPersona"+i+"' value="+resultado[i]['CodPersona']+"></TD>";
		Contenido+="<TD width='25%' class='FilaEstilo' id='Ndocumento"+i+"'>"+CadAuxAA+"</TD>";
		Contenido+="<TD width='70%' class='FilaEstilo' id='NomCompleto"+i+"'>"+CadAuxBB+"</TD>";

		Contenido+="</TR>";
		Form_LISTA_PARTICIPANTES__MensajeCargando();
		//xGetElementById("MSG_CARGANDO_FLP1").innerHTML="";
		//xGetElementById("MSG_CARGANDO_FLP1").innerHTML="<DIV style=\" display:block;color : #959595; font-family : 'sans-serif', 'Arial','Bitstream Vera Sans'; font-size : 20px; font-style : normal; font-weight : bold; text-align : left;  \" align=\"top\">Cargando... Por favor espere...</DIV>";
		
	}


	xGetElementById("TABLA_LISTA_PARTICIPANTES_FLP1").innerHTML=Contenido;
	xGetElementById("MSG_CARGANDO_FLP1").innerHTML="";
	//xGetElementById("MSG_CARGANDO_FLP1").innerHTML="<DIV style=\" display:none;color : #959595; font-family : 'sans-serif', 'Arial','Bitstream Vera Sans'; font-size : 20px; font-style : normal; font-weight : bold; text-align : left;  \" align=\"top\">Cargando... Por favor espere...</DIV>";
	
		
	
	}


	

	function LISTA_PARTICIPANTES__SeleccionarElementoTabla(IDSeleccion, Ced, Nombre, i)
	{

	if(Form_LISTA_PARTICIPANTES__IDSeleccionActualLista!=-1)
		xGetElementById("FLP1"+Form_LISTA_PARTICIPANTES__IDSeleccionActualLista).bgColor=colorFondoTabla;
		
		
	colorBase=colorSeleccionTabla;
	xGetElementById("FLP1"+IDSeleccion).bgColor=colorBase;	

		
		Form_LISTA_PARTICIPANTES__IDSeleccionActualLista=IDSeleccion;
		Form_LISTA_PARTICIPANTES__IDCedSeleccionActualLista=Ced;
		Form_LISTA_PARTICIPANTES__IDNombreSeleccionActualLista=Nombre;
	
	
	
	}



	function seleccionarTodos()
	{
		if(xGetElementById("SEL_TODOS").checked==true)
		{
		
			var tabla_a=xGetElementById('TABLA_LISTA_PARTICIPANTES_FLP1');			

			for(var i=0;i<tabla_a.rows.length;i++){
				xGetElementById("checkpart"+i).checked=true;
				
				
				//Form_LISTA_PARTICIPANTES__MensajeCargando();
				//xGetElementById("MSG_CARGANDO_FLP1").innerHTML="<DIV style=\" display:none;color : #959595; font-family : 'sans-serif', 'Arial','Bitstream Vera Sans'; font-size : 20px; font-style : normal; font-weight : bold; text-align : left;  \" align=\"top\">Cargando... Por favor espere...</DIV>";
								
			}
				
		
		}	
		else
		{
			var tabla_a=xGetElementById('TABLA_LISTA_PARTICIPANTES_FLP1');	

			for(var i=0;i<tabla_a.rows.length;i++){
				xGetElementById("checkpart"+i).checked=false;
			}
				
			//	xGetElementById("MSG_CARGANDO_FLP1").innerHTML="";
		
		}
	}
	
	

	/*** Cuando se le de al botón aceptar***/



	function LISTA_PARTICIPANTES__Cargar(CodPersona,Ndocumento,NomCompleto)
	{	
			
	xGetElementById(Form_LISTA_PARTICIPANTES__ID).value = CodPersona;
	xGetElementById(Form_LISTA_PARTICIPANTES__IDCedula).value = Ndocumento;
	xGetElementById(Form_LISTA_PARTICIPANTES__IDNombre).value = NomCompleto;

	if(Form_LISTA_PARTICIPANTES__FuncionPostAcceptar)
	{				
		eval(Form_LISTA_PARTICIPANTES__FuncionPostAcceptar);			
	}
		
		
	xGetElementById(Form_LISTA_PARTICIPANTES__ID).value = "";
	xGetElementById(Form_LISTA_PARTICIPANTES__IDCedula).value = "";
	xGetElementById(Form_LISTA_PARTICIPANTES__IDNombre).value = "";	
	}
	
	

	function prueba()
	{
		
		if(traePart!=0 && (ari==0))
		{
			ari=1;	
			
			var numPartGuardadoEvento=Form_PARTICIPANTES__ArregloArticulos.length;					
			
			for(var Z=0;Z<numPartGuardadoEvento;Z++)
			{					
				ArregloP[K]=new Array(3);
				ArregloP[K][0]=Form_PARTICIPANTES__ArregloArticulos[Z][0];//Codigo de LA PERSONA
				ArregloP[K][1]=Form_PARTICIPANTES__ArregloArticulos[Z][1];//cédula
				ArregloP[K][2]=Form_PARTICIPANTES__ArregloArticulos[Z][2];//nombre completo
				K++;								
			}			
		
		}
			
		
		for(var g=0;g<ArregloP.length;g++)
		{
			if(cedSeleccionada[0]==ArregloP[g][1])
			{
				ArregloP[g][0]="";//Codigo de LA PERSONA
				ArregloP[g][1]="";//cédula
				ArregloP[g][2]="";//nombre completo					
			}			
		}		
		
		//alert(ArregloP);		
	}
	
	
	
	/*function ParticipantesMensaje(MSG,color){
	if(!MSG)
		MSG="&nbsp;";
	if(color=="VERDE")
		MSG="<DIV style='color:#006600'>"+MSG+"</DIV>";
	else if(color=="ROJO")
		MSG="<DIV style='color:#FF0000'>"+MSG+"</DIV>";
	xGetElementById("mensajeParticipantes").innerHTML=MSG;
	}*/
	
	function SINO(cual) {
   var elElemento=xGetElementById(cual);
   if(elElemento.style.display == 'block') {
      elElemento.style.display = 'none';
   } else {
      elElemento.style.display = 'block';
   }
}
	
	
	

	function LISTA_PARTICIPANTES__Aceptar()
	{	
	
	//Form_LISTA_PARTICIPANTES__MensajeCargando();
	//xGetElementById("MSG_CARGANDO_FLP1").innerHTML="<DIV style=\" display:block;color : #959595; font-family : 'sans-serif', 'Arial','Bitstream Vera Sans'; font-size : 20px; font-style : normal; font-weight : bold; text-align : left;  \" align=\"top\">Cargando... Por favor espere...</DIV>";
		
		
					
	var numPartGuardadoEvento=Form_PARTICIPANTES__ArregloArticulos.length;

		if(traePart!=0 && (ari==0))
		{
			ari=1;						
			
			for(var Z=0;Z<numPartGuardadoEvento;Z++)
			{					
				ArregloP[K]=new Array(3);
				ArregloP[K][0]=Form_PARTICIPANTES__ArregloArticulos[Z][0];//Codigo de LA PERSONA
				ArregloP[K][1]=Form_PARTICIPANTES__ArregloArticulos[Z][1];//cédula
				ArregloP[K][2]=Form_PARTICIPANTES__ArregloArticulos[Z][2];//nombre completo
				K++;								
			}			
		
		}
		
	

	var tabla=xGetElementById('TABLA_LISTA_PARTICIPANTES_FLP1');	

		var cent=0;	

		for(var i=0;i<tabla.rows.length;i++)
		{
						
			if(xGetElementById("checkpart"+i).checked)
			{	
					
									
				cent=1;
				contador1++;
				var sy=0;
				var CodPersona=xGetElementById("CodPersona"+i).value;
				var Ndocumento=xGetElementById("Ndocumento"+i).innerHTML;
				var NomCompleto=xGetElementById("NomCompleto"+i).innerHTML;				
				
				ArregloP[K]=new Array(3);				

				ArregloP[K][0]=CodPersona;//Codigo de LA PERSONA
				ArregloP[K][1]=Ndocumento;//cédula
				ArregloP[K][2]=NomCompleto;//nombre completo				
				K++;			
															
								
				if(ari==0)
				{
					
					if(contador==0 && m==0)
					{
						m=1;
						LISTA_PARTICIPANTES__Cargar(CodPersona,Ndocumento,NomCompleto);
					}
					else
					{
					
					}
				}
				
				else
				{
					if(contador==0 && s==0)
					{
						s=1;	
						for (var fga=(K-1); fga>0; fga--)
						{								
							if(Ndocumento!=ArregloP[fga-1][1])
							{
								syr++;
										
							}
							else
							{										
								var u=(fga-1);						
							}
															
						}
					
								if(syr==(K-1))
								{
									LISTA_PARTICIPANTES__Cargar(CodPersona,Ndocumento,NomCompleto);
								}
								else
								{				
									alert("La persona: "+NomCompleto+" ya está en la lista");
									
								}					
					}					
					
				}			
				
				
							
				if (contador>= 1)
				{				
								
					for (e=p;e<contador;e++)
					{
					var sy=0;	
							if(ArregloP[e][1]!=Ndocumento)
							{
								for (a=(K-1); a>0; a--)
								{								
									if(Ndocumento!=ArregloP[a-1][1])
									{
										sy++;
										
									}	
									else
									{										
										 y=(a-1);
									}							
								}
							}
									
																				
								if(sy==(K-1))
								{
									LISTA_PARTICIPANTES__Cargar(CodPersona,Ndocumento,NomCompleto);
								}
								else
								{				
									
									alert("La persona: "+NomCompleto+" ya está en la lista");	
									xGetElementById(Form_LISTA_PARTICIPANTES__ID).value = "";
									xGetElementById(Form_LISTA_PARTICIPANTES__IDCedula).value = "";
									xGetElementById(Form_LISTA_PARTICIPANTES__IDNombre).value = "";
								}						
						}			
					
					p++;					
					}
					
					contador++;	
					
										
				}//fin if q comprueba si está checked	
						
		}	//fin for

		
		if (cent==0)
		{
		Form_LISTA_PARTICIPANTES__IDSeleccionActualLista=-1;
		xGetElementById(Form_LISTA_PARTICIPANTES__ID).value = "";
		xGetElementById(Form_LISTA_PARTICIPANTES__IDCedula).value = "";
		xGetElementById(Form_LISTA_PARTICIPANTES__IDNombre).value = "";		
		}
		
		VentanaCerrar('VENTANA_LISTA_PARTICIPANTES');
		return;

	}





	function Form_LISTA_PARTICIPANTES__MensajeCargando()
	{
	xGetElementById("MSG_CARGANDO_FLP1").innerHTML="<DIV style=\" display:block;color : #959595; font-family : 'sans-serif', 'Arial','Bitstream Vera Sans'; font-size : 20px; font-style : normal; font-weight : bold; text-align : left;  \" align=\"top\">Cargando... Por favor espere...</DIV>";
	
	}
	
	function Form_LISTA_PARTICIPANTES__MensajeCargando1()
	{
	xGetElementById("MSG_CARGANDO_FLP1").innerHTML="<DIV style=\" display:block;color : #959595; font-family : 'sans-serif', 'Arial','Bitstream Vera Sans'; font-size : 20px; font-style : normal; font-weight : bold; text-align : left;  \" align=\"top\">Usuario No Encontrado...</DIV>";
		}
