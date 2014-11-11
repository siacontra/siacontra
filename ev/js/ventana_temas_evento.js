function Form_TEMA__Mensaje(MSG,color){
	if(!MSG)
		MSG="&nbsp;";
	if(color=="VERDE")
		MSG="<DIV style='color:#006600'>"+MSG+"</DIV>";
	else if(color=="ROJO")
		MSG="<DIV style='color:#FF0000'>"+MSG+"</DIV>";	
	xGetElementById("MSG_TEMA").innerHTML=MSG;
	}

/**
* Muestra los mensajes en la parte superior del listado
* @param {string} MSG Mensaje a mostrar
* @param {string} color del mensaje
*/
function Form_TEMA__MensajeListado(MSG,color){
	if(!MSG)
		MSG="&nbsp;";
	if(color=="VERDE")
		MSG="<DIV style='color:#006600'>"+MSG+"</DIV>";
	else if(color=="ROJO")
		MSG="<DIV style='color:#FF0000'>"+MSG+"</DIV>";
	xGetElementById("MSG_TEMA_LISTADO").innerHTML=MSG;
	}



/**
* Activa todos los campos del formulario entrada de datos
*/
function Form_TEMA__ActivarFormulario(){
	xGetElementById("DENOMINACION_TEMA").readOnly=false;
	xGetElementById("DENOMINACION_TEMA").setAttribute('class','TextoCampoInputObligatorios');
	}

/**
* Desactiva todos los campos del formulario entrada de datos
*/
function Form_TEMA__DesactivarFormulario(){
	xGetElementById("DENOMINACION_TEMA").readOnly=true;
	xGetElementById("DENOMINACION_TEMA").setAttribute('class','TextoCampoInputDesactivado');
	}

/**
* Activa el boton modificar
*/
function Form_TEMA__ActivarBotonModificar(){
	//ActivarBoton("BOTON_MODIFICAR_FCG","IMG_MODIFICAR_FCG",'modificar');
	}

/**
* Desactiva el boton modificar
*/
function Form_TEMA__DesactivarBotonModificar(){
	//DesactivarBoton("BOTON_MODIFICAR_FCG","IMG_MODIFICAR_FCG",'modificar');
	}

/**
* Activa el boton guardar
*/
function Form_TEMA__ActivarBotonGuardar(){
	//ActivarBoton("BOTON_GUARDAR_FCG","IMG_GUARDAR_FCG",'guardar');
	}

/**
* Desactiva el boton guardar
*/
function Form_TEMA__DesactivarBotonGuardar(){
	//DesactivarBoton("BOTON_GUARDAR_FCG","IMG_GUARDAR_FCG",'guardar');
	}

/**
* Activa el boton eliminar
*/
function Form_TEMA__ActivarBotonEliminar(){
	//ActivarBoton("BOTON_ELIMINAR_FCG","IMG_ELIMINAR_FCG",'eliminar');
	}

/**
* Desactiva el boton eliminar
*/
function Form_TEMA__DesactivarBotonEliminar(){
	//DesactivarBoton("BOTON_ELIMINAR_FCG","IMG_ELIMINAR_FCG",'eliminar');
	}

/**
* Indica el elemento que se tiene seleccionado actualmente en el listado. Necesario para eliminar y para modificar
*/
var Form_TEMA__IDSeleccionActualLista=-1;

/*Se llena cuando se selecciona un banco del listado. Esta sirve para saber si el usuario a modificado el nombre del banco. Es usada en Guardar*/
var Form_TEMA__Denominacion="";




/**
* Nueva definicion
*/
function Form_DEFINICIONES_TEMAS__Nuevo(){
	Form_TEMA__LimpiarInputTextBuscarListado();
	xGetElementById("LISTADO_BUSCAR_TEMA").value="";
	Form_TEMA__TabPane.setSelectedIndex(0);
	DarFocoCampo("DENOMINACION_TEMA",1000);
	cargarTemasporEvento();
	}




/**
* Verifica la existencia de los datos (duplicidad) antes de guardar
*/
function Form_TEMA__GuardarVerificar(){
	Form_TEMA__TabPane.setSelectedIndex(0);
	var denominacion = xTrim(strtoupper(xGetElementById("DENOMINACION_TEMA").value));

	if(!denominacion){
		var msg="Por favor introduzca el Tema."
		Form_TEMA__Mensaje(msg,"ROJO");
		Form_TEMA__MensajeListado("");
		return;
		}

	/*Si el nombre del tipo de cuenta no se modifico. No guardar.*/
	if(Form_TEMA__Denominacion==denominacion){		
		return;
		}

	Form_TEMA__DesactivarFormulario();
	var denominacion = xTrim(strtoupper(xGetElementById("DENOMINACION_TEMA").value));
	
	AjaxRequest.post({'parameters':{ 'caso':"TEMA__Existe",
									'id_tipo_cta_bancaria':Form_TEMA__IDSeleccionActualLista,
									'nombre_tema':denominacion},
					 'onSuccess':Form_TEMA__Guardar,
					 'url':'lib/controladorCes.php',
					 'onError':function(req){alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
					 });
					 
						 	
	}

/**
* Guarda los datos en la BD
*/
function Form_TEMA__Guardar(req){

	var denominacion = xTrim(strtoupper(xGetElementById("DENOMINACION_TEMA").value));
	
		var respuesta = req.responseText;	
		var resultado = eval("(" + respuesta + ")");	
		var msg="";
	
	

	//Si ya existe un tipo de cuenta con el mismo nombre. No guardar.
	if(resultado[0]['num3']>=1){
		Form_TEMA__Mensaje("No se puedo guardar los datos. Ya existe un tema con la misma denominación","ROJO");
		Form_TEMA__MensajeListado("");
		Form_TEMA__ActivarFormulario();
		return;
		}

	

	/*Si es guardar nuevo*/
	if(Form_TEMA__IDSeleccionActualLista==-1){
		AjaxRequest.post({'parameters':{ 'caso':"TEMAS__Guardar",
										'nombre_tema':denominacion},
						'onSuccess':Form_TEMA__GuardarMensaje,
						'url':'lib/controladorCes.php',
						'onError':function(req){alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
						});
		}
	/*Si es modificar*/
	else{
		if(!confirm("¿Esta seguro que desea guardar los cambios?")){
			Form_TEMA__ActivarFormulario();
			return;
			}
		AjaxRequest.post({'parameters':{ 'caso':"TEMAS__Modificar",
										'co_tema':Form_TEMA__IDSeleccionActualLista,
										'nombre_tema':denominacion},
						'onSuccess':Form_TEMA__GuardarMensaje,
						'url':'lib/controladorCes.php',
						'onError':function(req){alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
						});		
		}
	}

/**
* Muestra el mensaje despues de guardar los datos
* @param {Array} req respuesta luego de guardar los datos 1=exito, !1=fracaso
*/
function Form_TEMA__GuardarMensaje(req){
	Form_TEMA__ActivarFormulario();
	var respuesta = req.responseText;
	if(respuesta==1){		
		Form_DEFINICIONES_TEMAS__Nuevo();
		Form_TEMA__Mensaje("Los datos se guadaron satisfactoriamente.","VERDE");
		Form_DEFINICIONES_TEMA__ActualizarSelectTipoCuenta();
		}
	else
		Form_TEMA__Mensaje("Error. No se pudo guardar los datos. Vuelva a intentarlo.","ROJO");
	}





/**
* Cuando se escribe en el INPUT TEXT buscar, el sistema va a la BD y actualiza el listado. Sirve para evitar que se vaya a la BD cuando la cadena nueva sea igual a la que se envio anteriormente
*/
var Form_TEMA__BuscarListado_CadenaBuscar="";

/**
* Es llamada cuando se introduce texto en el INPUT TEXT buscar de la pestaña lista
*/
function Form_TEMA__BuscarListado(){
	
	xGetElementById("TABLA_LISTA_TEMA").innerHTML="";
	Form_TEMA__IDSeleccionActualLista=-1;
	xGetElementById("FORMULARIO_TEMA").reset();
	Form_TEMA__ActivarFormulario();
	Form_TEMA__DesactivarBotonModificar();
	Form_TEMA__DesactivarBotonEliminar();
	Form_TEMA__ActivarBotonGuardar();

	var CadenaBuscarA=quitarCodigoCeros(xTrim(strtoupper(xGetElementById("LISTADO_BUSCAR_TEMA").value)));
	
	if(CadenaBuscarA!="")
		if(Form_TEMA__BuscarListado_CadenaBuscar==CadenaBuscarA)
			return;
	Form_TEMA__BuscarListado_CadenaBuscar=CadenaBuscarA;


	AjaxRequest.post({'parameters':{ 'caso':"TEMAS__BuscarListado",
									'CadenaBuscar':CadenaBuscarA},
					 'onSuccess':Form_TEMA__MostrarListado,
					 'url':'lib/controladorCes.php',
					 'onError':function(req){alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
					 });
	}

/**
* Muestra el listado (Crea tabla dinamicamente)
* @param {Array} req Datos provenientes de la BD
*/




function Form_TEMA__MostrarListado(req){
	var respuesta = req.responseText;
	var resultado = eval("(" + respuesta + ")");
	var n=resultado.length;


	
	var TextoBuscarA_A=xTrim(strtoupper(xGetElementById("LISTADO_BUSCAR_TEMA").value));
	
	xGetElementById("TABLA_LISTA_TEMA").innerHTML="";
	xGetElementById("SELECT_EP_FILM").innerHTML="";
	
	var CadAux1_1;
	
	var Contenido="";
	var Contenido2="";
	var FuncionOnclick="";
	var FuncionOnDblclick="";
	var FuncionOnMouseOver="";
	var FuncionOnMouseOut="";
	

		//Contenido2+="<option value='*'>TODOS</option>";
		
		for(var i=0;i<n; i++)
		{
		FuncionOnclick="Form_TEMA__SeleccionarElementoTabla('"+resultado[i]['co_tema']+"','"
							   +resultado[i]['tx_tema']+"')";
 		FuncionOnDblclick="Form_TEMA__TabPane.setSelectedIndex(0);";
 		FuncionOnMouseOver="pintarFila(\"FAAT"+resultado[i]['co_tema']+"\")";		
 		FuncionOnMouseOut="despintarFila(\"FAAT"+resultado[i]['co_tema']+"\")";


		Contenido+="<TR id='FAAT"+resultado[i]['co_tema']+"' onclick=\""+FuncionOnclick+"\" ondblclick='"+FuncionOnDblclick+"' onmouseover='"+FuncionOnMouseOver+"' onmouseout='"+FuncionOnMouseOut+"'>";
		
		CadAux1_1=str_replace(strtoupper(resultado[i]['tx_tema']),"<strong>"+TextoBuscarA_A+"</strong>",TextoBuscarA_A);
		
		Contenido+="<TD width='5%' class='FilaEstilo' align='center'>"+resultado[i]['co_tema']+"</TD>";
		Contenido+="<TD width='45%' class='FilaEstilo'>"+CadAux1_1+"</TD>";
	
		Contenido+="</TR>";		
		
		
		Contenido2+="<option value='"+resultado[i]["co_tema"]+"'>"+resultado[i]["tx_tema"]+"</option>";	
		
		
		
			}			

	xGetElementById("TABLA_LISTA_TEMA").innerHTML=Contenido;	
	xGetElementById("SELECT_EP_FILM").innerHTML=Contenido2;
		
	}
	

/**

*/
function Form_TEMA__SeleccionarElementoTabla(IDSeleccion, Denominacion){
	
	if(Form_TEMA__IDSeleccionActualLista!=-1)
		xGetElementById("FAAT"+Form_TEMA__IDSeleccionActualLista).bgColor=colorFondoTabla;
		colorBase=colorSeleccionTabla;
		xGetElementById("FAAT"+IDSeleccion).bgColor=colorBase;
	
		Form_TEMA__IDSeleccionActualLista=IDSeleccion;
		Form_TEMA__Denominacion=Denominacion;
		xGetElementById("DENOMINACION_TEMA").value=Denominacion;
		
		Form_TEMA__DesactivarFormulario();
		
		Form_TEMA__DesactivarBotonGuardar();
		Form_TEMA__ActivarBotonEliminar();
		Form_TEMA__ActivarBotonModificar();
		
		
		Form_TEMA__Mensaje("");
		Form_TEMA__MensajeListado("");
	
		
		}

/**
* Es llamada cuando se presiona sobre el boton limpiar. 
* Este borra el contenido de INPUT TEXT buscar y muestra el listado completo
*/
function Form_TEMA__LimpiarInputTextBuscarListado(){
	Form_TEMA__IDSeleccionActualLista=-1;
	Form_TEMA__Denominacion="";
	Form_TEMA__DesactivarBotonModificar();
	Form_TEMA__DesactivarBotonEliminar();
	Form_TEMA__ActivarBotonGuardar();
	Form_TEMA__ActivarFormulario();
	xGetElementById("FORMULARIO_TEMA").reset();
	xGetElementById("LISTADO_BUSCAR_TEMA").value="";
	Form_TEMA__Mensaje("");
	Form_TEMA__MensajeListado("");
	Form_TEMA__BuscarListado("");	
	DarFocoCampo("LISTADO_BUSCAR_TEMA",1000);
	}






/**
* Es llamada cuando se presiona el boton de modificar
*/
function Form_TEMA__Modificar(){
	Form_TEMA__ActivarFormulario();
	Form_TEMA__ActivarBotonGuardar();
	Form_TEMA__DesactivarBotonModificar();
	Form_TEMA__TabPane.setSelectedIndex(0);
	}





/**
* Es llamada cuando se presiona el boton de eliminar. Esta hace un borrado logico.
*/
function Form_TEMA__Eliminar(){
	//OJO. NUNCA DEBERIA CUMPLIRSE, PORQUE EL BOTON ELIMINAR ESTA DESACTIVADO. NO HAY ELEMENTO SELECIONADO.
	if(Form_TEMA__IDSeleccionActualLista==-1)
		return;
		
	if(!confirm("¿Esta seguro que quiere eliminarlo?"))
		return;
	AjaxRequest.post({'parameters':{ 'caso':"TEMA__Eliminar",
									'co_tema':Form_TEMA__IDSeleccionActualLista},
					 'onSuccess':Form_TEMA__EliminarMensaje,
					 'url':'lib/controladorCes.php',
					 'onError':function(req){alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
					 });	
	}

/**
* Muestra el mensaje al eliminar el elemento seleccionado
* @param {Array} req respuesta luego de guardar los datos 1=exito, !1=fracaso
*/
function Form_TEMA__EliminarMensaje(req){
	var respuesta = req.responseText;
	
	//alert(respuesta);
	
	if(respuesta==1){
		Form_TEMA__LimpiarInputTextBuscarListado();
		Form_TEMA__Mensaje("La eliminación se realizó satisfactoriamente.","VERDE");
		Form_TEMA__MensajeListado("La eliminación se realizó satisfactoriamente.","VERDE");
		//Actualizar las ventanas que esten abiertas que tengan el campo tipo de cuenta
		//Form_DEFINICIONES_CUENTAS_BANCARIAS__ActualizarSelectTipoCuenta();
		Form_DEFINICIONES_TEMA__ActualizarSelectTipoCuenta();
		}
	else{
		Form_TEMA__Mensaje("Error. No se pudo eliminar los datos. Verifique si hay eventos asociados a ese lugar.","ROJO");
		Form_TEMA__MensajeListado("Error. No se pudo eliminar los datos. Verifique si hay eventos asociados a ese lugar.","ROJO");
		}	
	}

