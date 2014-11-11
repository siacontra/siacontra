function Form_DEFINICIONES_TIPOS_DE_CUENTA__Mensaje(MSG,color){
	if(!MSG)
		MSG="&nbsp;";
	if(color=="VERDE")
		MSG="<DIV style='color:#006600'>"+MSG+"</DIV>";
	else if(color=="ROJO")
		MSG="<DIV style='color:#FF0000'>"+MSG+"</DIV>";	
	xGetElementById("MSG_FDTDC").innerHTML=MSG;
	}

/**
* Muestra los mensajes en la parte superior del listado
* @param {string} MSG Mensaje a mostrar
* @param {string} color del mensaje
*/
function Form_DEFINICIONES_TIPOS_DE_CUENTA__MensajeListado(MSG,color){
	if(!MSG)
		MSG="&nbsp;";
	if(color=="VERDE")
		MSG="<DIV style='color:#006600'>"+MSG+"</DIV>";
	else if(color=="ROJO")
		MSG="<DIV style='color:#FF0000'>"+MSG+"</DIV>";
	xGetElementById("MSG_FDTDC_LISTADO").innerHTML=MSG;
	}




/**
* Activa todos los campos del formulario entrada de datos
*/
function Form_DEFINICIONES_TIPOS_DE_CUENTA__ActivarFormulario(){
	xGetElementById("DENOMINACION_FDTDC").readOnly=false;

	xGetElementById("DENOMINACION_FDTDC").setAttribute('class','TextoCampoInputObligatorios');
	}

/**
* Desactiva todos los campos del formulario entrada de datos
*/
function Form_DEFINICIONES_TIPOS_DE_CUENTA__DesactivarFormulario(){
	xGetElementById("DENOMINACION_FDTDC").readOnly=true;

	xGetElementById("DENOMINACION_FDTDC").setAttribute('class','TextoCampoInputDesactivado');
	}

/**
* Activa el boton modificar
*/
function Form_DEFINICIONES_TIPOS_DE_CUENTA__ActivarBotonModificar(){
	//ActivarBoton("BOTON_MODIFICAR_FDTDC","IMG_MODIFICAR_FDTDC",'modificar');
	}

/**
* Desactiva el boton modificar
*/
function Form_DEFINICIONES_TIPOS_DE_CUENTA__DesactivarBotonModificar(){
	//DesactivarBoton("BOTON_MODIFICAR_FDTDC","IMG_MODIFICAR_FDTDC",'modificar');
	}

/**
* Activa el boton guardar
*/
function Form_DEFINICIONES_TIPOS_DE_CUENTA__ActivarBotonGuardar(){
	//ActivarBoton("BOTON_GUARDAR_FDTDC","IMG_GUARDAR_FDTDC",'guardar');
	}

/**
* Desactiva el boton guardar
*/
function Form_DEFINICIONES_TIPOS_DE_CUENTA__DesactivarBotonGuardar(){
	//DesactivarBoton("BOTON_GUARDAR_FDTDC","IMG_GUARDAR_FDTDC",'guardar');
	}

/**
* Activa el boton eliminar
*/
function Form_DEFINICIONES_TIPOS_DE_CUENTA__ActivarBotonEliminar(){
	//ActivarBoton("BOTON_ELIMINAR_FDTDC","IMG_ELIMINAR_FDTDC",'eliminar');
	}

/**
* Desactiva el boton eliminar
*/
function Form_DEFINICIONES_TIPOS_DE_CUENTA__DesactivarBotonEliminar(){
	//DesactivarBoton("BOTON_ELIMINAR_FDTDC","IMG_ELIMINAR_FDTDC",'eliminar');
	}

/**
* Indica el elemento que se tiene seleccionado actualmente en el listado. Necesario para eliminar y para modificar
*/
var Form_DEFINICIONES_TIPOS_DE_CUENTA__IDSeleccionActualLista=-1;

/*Se llena cuando se selecciona un banco del listado. Esta sirve para saber si el usuario a modificado el nombre del banco. Es usada en Guardar*/
var Form_DEFINICIONES_TIPOS_DE_CUENTA__Denominacion="";




/**
* Nueva definicion
*/
function Form_DEFINICIONES_TIPOS_DE_CUENTA__Nuevo(){
	Form_DEFINICIONES_TIPOS_DE_CUENTA__LimpiarInputTextBuscarListado();
	xGetElementById("LISTADO_BUSCAR_FDTDC").value="";
	Form_DEFINICIONES_TIPOS_DE_CUENTA__TabPane.setSelectedIndex(0);
	//xGetElementById("DENOMINACION_FDTDC").focus();
	DarFocoCampo("DENOMINACION_FDTDC",1000);
	}




/**
* Verifica la existencia de los datos (duplicidad) antes de guardar
*/
function Form_DEFINICIONES_TIPOS_DE_CUENTA__GuardarVerificar(){
	Form_DEFINICIONES_TIPOS_DE_CUENTA__TabPane.setSelectedIndex(0);
	var denominacion = strtoupper(xGetElementById("DENOMINACION_FDTDC").value);

	if(!denominacion){
		var msg="Por favor introduzca el lugar."
		Form_DEFINICIONES_TIPOS_DE_CUENTA__Mensaje(msg,"ROJO");
		Form_DEFINICIONES_TIPOS_DE_CUENTA__MensajeListado("");
		return;
		}

	/*Si el nombre del tipo de cuenta no se modifico. No guardar.*/
	if(Form_DEFINICIONES_TIPOS_DE_CUENTA__Denominacion==denominacion){		
		return;
		}

	Form_DEFINICIONES_TIPOS_DE_CUENTA__DesactivarFormulario();
	var denominacion = strtoupper(xGetElementById("DENOMINACION_FDTDC").value);
	
	AjaxRequest.post({'parameters':{ 'caso':"LUGAR__Existe",
									'id_tipo_cta_bancaria':Form_DEFINICIONES_TIPOS_DE_CUENTA__IDSeleccionActualLista,
									'nombre_lugar':denominacion},
					 'onSuccess':Form_DEFINICIONES_TIPOS_DE_CUENTA__Guardar,
					 'url':'lib/controladorCes.php',
					 'onError':function(req){alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
					 });
					 
						 	
	}

/**
* Guarda los datos en la BD
*/
function Form_DEFINICIONES_TIPOS_DE_CUENTA__Guardar(req){

	var denominacion = strtoupper(xGetElementById("DENOMINACION_FDTDC").value);
	
		var respuesta = req.responseText;	
		var resultado = eval("(" + respuesta + ")");	
		var msg="";
	
	

	//Si ya existe un tipo de cuenta con el mismo nombre. No guardar.
	if(resultado[0]['num2']>=1){
		Form_DEFINICIONES_TIPOS_DE_CUENTA__Mensaje("No se puedo guardar los datos. Ya existe un lugar con la misma denominación","ROJO");
		Form_DEFINICIONES_TIPOS_DE_CUENTA__MensajeListado("");
		Form_DEFINICIONES_TIPOS_DE_CUENTA__ActivarFormulario();
		return;
		}

	

	/*Si es guardar nuevo*/
	if(Form_DEFINICIONES_TIPOS_DE_CUENTA__IDSeleccionActualLista==-1){
		AjaxRequest.post({'parameters':{ 'caso':"LUGARES__Guardar",
										'nombre_lugar':denominacion},
						'onSuccess':Form_DEFINICIONES_TIPOS_DE_CUENTA__GuardarMensaje,
						'url':'lib/controladorCes.php',
						'onError':function(req){alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
						});
		}
	/*Si es modificar*/
	else{
		if(!confirm("¿Esta seguro que desea guardar los cambios?")){
			Form_DEFINICIONES_TIPOS_DE_CUENTA__ActivarFormulario();
			return;
			}
		AjaxRequest.post({'parameters':{ 'caso':"LUGARES__Modificar",
										'co_lugar':Form_DEFINICIONES_TIPOS_DE_CUENTA__IDSeleccionActualLista,
										'nombre_lugar':denominacion},
						'onSuccess':Form_DEFINICIONES_TIPOS_DE_CUENTA__GuardarMensaje,
						'url':'lib/controladorCes.php',
						'onError':function(req){alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
						});		
		}
	}

/**
* Muestra el mensaje despues de guardar los datos
* @param {Array} req respuesta luego de guardar los datos 1=exito, !1=fracaso
*/
function Form_DEFINICIONES_TIPOS_DE_CUENTA__GuardarMensaje(req){
	Form_DEFINICIONES_TIPOS_DE_CUENTA__ActivarFormulario();
	var respuesta = req.responseText;
	if(respuesta==1){		
		Form_DEFINICIONES_TIPOS_DE_CUENTA__Nuevo();
		Form_DEFINICIONES_TIPOS_DE_CUENTA__Mensaje("Los datos se guadaron satisfactoriamente.","VERDE");
		//Actualizar las ventanas que esten abiertas que tengan el campo tipo de cuenta
		//Form_DEFINICIONES_CUENTAS_BANCARIAS__ActualizarSelectTipoCuenta();
		Form_DEFINICIONES_LUGARES__ActualizarSelectTipoCuenta();
		}
	else
		Form_DEFINICIONES_TIPOS_DE_CUENTA__Mensaje("Error. No se pudo guardar los datos. Vuelva a intentarlo.","ROJO");
	}





/**
* Cuando se escribe en el INPUT TEXT buscar, el sistema va a la BD y actualiza el listado. Sirve para evitar que se vaya a la BD cuando la cadena nueva sea igual a la que se envio anteriormente
*/
var Form_DEFINICIONES_TIPOS_DE_CUENTA__BuscarListado_CadenaBuscar="";

/**
* Es llamada cuando se introduce texto en el INPUT TEXT buscar de la pestaña lista
*/








function Form_DEFINICIONES_TIPOS_DE_CUENTA__BuscarListado(){
	
	Form_DEFINICIONES_TIPOS_DE_CUENTA__IDSeleccionActualLista=-1;
	xGetElementById("TABLA_LISTA_FDTDC").innerHTML="";	
	xGetElementById("FORMULARIO_FDTDC").reset();
	Form_DEFINICIONES_TIPOS_DE_CUENTA__ActivarFormulario();
	Form_DEFINICIONES_TIPOS_DE_CUENTA__DesactivarBotonModificar();
	Form_DEFINICIONES_TIPOS_DE_CUENTA__DesactivarBotonEliminar();
	Form_DEFINICIONES_TIPOS_DE_CUENTA__ActivarBotonGuardar();

	
	var CadenaBuscarA=xTrim(strtoupper(xGetElementById("LISTADO_BUSCAR_FDTDC").value));
	xGetElementById("TABLA_LISTA_FDTDC").innerHTML="";
	
	/*if(CadenaBuscarA!="")
		if(Form_DEFINICIONES_TIPOS_DE_CUENTA__BuscarListado_CadenaBuscar==CadenaBuscarA)
			return;
	Form_DEFINICIONES_TIPOS_DE_CUENTA__BuscarListado_CadenaBuscar=CadenaBuscarA;*/


	AjaxRequest.post({'parameters':{ 'caso':"LUGARES__BuscarListado",
									'CadenaBuscar':CadenaBuscarA},
					 'onSuccess':Form_DEFINICIONES_TIPOS_DE_CUENTA__MostrarListado,
					 'url':'lib/controladorCes.php',
					 'onError':function(req){alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
					 });
	}
	

/**
* Muestra el listado (Crea tabla dinamicamente)
* @param {Array} req Datos provenientes de la BD
*/




function Form_DEFINICIONES_TIPOS_DE_CUENTA__MostrarListado(req){
	var respuesta = req.responseText;
	var resultado = eval("(" + respuesta + ")");
	var n=resultado.length;


	
	//var TextoBuscarA=xTrim(strtoupper(xGetElementById("LISTADO_BUSCAR_FDTDC").value));
	//var TextoBuscarB=quitarCodigoCeros(xTrim(strtoupper(xGetElementById("LISTADO_BUSCAR_FDTDC").value)));
	var TextoBuscarB=xTrim(strtoupper(xGetElementById("LISTADO_BUSCAR_FDTDC").value));	
	xGetElementById("TABLA_LISTA_FDTDC").innerHTML="";
	
	var CadAux1, CadAux2;
	
	var Contenido="";
	var FuncionOnclick="";
	var FuncionOnDblclick="";
	var FuncionOnMouseOver="";
	var FuncionOnMouseOut="";

		
		for(var i=0;i<n; i++)
		{
		FuncionOnclick="Form_DEFINICIONES_TIPOS_DE_CUENTA__SeleccionarElementoTabla('"+resultado[i]['co_lugares']+"','"
							   +resultado[i]['nombre_lugar']+"')";
 		FuncionOnDblclick="Form_DEFINICIONES_TIPOS_DE_CUENTA__TabPane.setSelectedIndex(0);";
 		FuncionOnMouseOver="pintarFila(\"FAA"+resultado[i]['co_lugares']+"\")";		
 		FuncionOnMouseOut="despintarFila(\"FAA"+resultado[i]['co_lugares']+"\")";


		Contenido+="<TR id='FAA"+resultado[i]['co_lugares']+"' onclick=\""+FuncionOnclick+"\" ondblclick='"+FuncionOnDblclick+"' onmouseover='"+FuncionOnMouseOver+"' onmouseout='"+FuncionOnMouseOut+"'>";
		
		
		if(TextoBuscarB!="" ){
			CadAux1=str_replace(strtoupper(resultado[i]['nombre_lugar']),"<strong>"+TextoBuscarB+"</strong>",TextoBuscarB);

			}
		else{
			CadAux1=strtoupper(resultado[i]['nombre_lugar']);

			}
		
		
		
		//CadAux1=str_replace(strtoupper(resultado[i]['nombre_lugar']),"<strong>"+TextoBuscarB+"</strong>",TextoBuscarB);	
			
		Contenido+="<TD width='5%' class='FilaEstilo' align='center'>"+resultado[i]['co_lugares']+"</TD>";
		Contenido+="<TD width='45%' class='FilaEstilo'>"+CadAux1+"</TD>";
	
		Contenido+="</TR>";		
			}			

	xGetElementById("TABLA_LISTA_FDTDC").innerHTML=Contenido;		
	}
	

/**

*/
function Form_DEFINICIONES_TIPOS_DE_CUENTA__SeleccionarElementoTabla(IDSeleccion, Denominacion){
	
	if(Form_DEFINICIONES_TIPOS_DE_CUENTA__IDSeleccionActualLista!=-1)
		xGetElementById("FAA"+Form_DEFINICIONES_TIPOS_DE_CUENTA__IDSeleccionActualLista).bgColor=colorFondoTabla;
		colorBase=colorSeleccionTabla;
		xGetElementById("FAA"+IDSeleccion).bgColor=colorBase;
	
		Form_DEFINICIONES_TIPOS_DE_CUENTA__IDSeleccionActualLista=IDSeleccion;
		Form_DEFINICIONES_TIPOS_DE_CUENTA__Denominacion=Denominacion;
		xGetElementById("DENOMINACION_FDTDC").value=Denominacion;
		
		Form_DEFINICIONES_TIPOS_DE_CUENTA__DesactivarFormulario();
		Form_DEFINICIONES_TIPOS_DE_CUENTA__ActivarBotonModificar();
		Form_DEFINICIONES_TIPOS_DE_CUENTA__ActivarBotonEliminar();
		Form_DEFINICIONES_TIPOS_DE_CUENTA__DesactivarBotonGuardar();
		Form_DEFINICIONES_TIPOS_DE_CUENTA__Mensaje("");
		Form_DEFINICIONES_TIPOS_DE_CUENTA__MensajeListado("");
	
		
		}

/**
* Es llamada cuando se presiona sobre el boton limpiar. 
* Este borra el contenido de INPUT TEXT buscar y muestra el listado completo
*/
function Form_DEFINICIONES_TIPOS_DE_CUENTA__LimpiarInputTextBuscarListado(){
	Form_DEFINICIONES_TIPOS_DE_CUENTA__IDSeleccionActualLista=-1;
	Form_DEFINICIONES_TIPOS_DE_CUENTA__Denominacion="";
	Form_DEFINICIONES_TIPOS_DE_CUENTA__DesactivarBotonModificar();
	Form_DEFINICIONES_TIPOS_DE_CUENTA__DesactivarBotonEliminar();
	Form_DEFINICIONES_TIPOS_DE_CUENTA__ActivarBotonGuardar();
	Form_DEFINICIONES_TIPOS_DE_CUENTA__ActivarFormulario();
	xGetElementById("FORMULARIO_FDTDC").reset();
	xGetElementById("LISTADO_BUSCAR_FDTDC").value="";
	Form_DEFINICIONES_TIPOS_DE_CUENTA__Mensaje("");
	Form_DEFINICIONES_TIPOS_DE_CUENTA__MensajeListado("");
	Form_DEFINICIONES_TIPOS_DE_CUENTA__BuscarListado("");	
	DarFocoCampo("LISTADO_BUSCAR_FDTDC",1000);
	}






/**
* Es llamada cuando se presiona el boton de modificar
*/
function Form_DEFINICIONES_TIPOS_DE_CUENTA__Modificar(){
	Form_DEFINICIONES_TIPOS_DE_CUENTA__ActivarFormulario();
	Form_DEFINICIONES_TIPOS_DE_CUENTA__ActivarBotonGuardar();
	Form_DEFINICIONES_TIPOS_DE_CUENTA__DesactivarBotonModificar();
	Form_DEFINICIONES_TIPOS_DE_CUENTA__TabPane.setSelectedIndex(0);
	}





/**
* Es llamada cuando se presiona el boton de eliminar. Esta hace un borrado logico.
*/
function Form_DEFINICIONES_TIPOS_DE_CUENTA__Eliminar(){
	//OJO. NUNCA DEBERIA CUMPLIRSE, PORQUE EL BOTON ELIMINAR ESTA DESACTIVADO. NO HAY ELEMENTO SELECIONADO.
	if(Form_DEFINICIONES_TIPOS_DE_CUENTA__IDSeleccionActualLista==-1)
		return;
		
	if(!confirm("¿Esta seguro que quiere eliminarlo?"))
		return;
	AjaxRequest.post({'parameters':{ 'caso':"lugar__Eliminar",
									'co_lugar':Form_DEFINICIONES_TIPOS_DE_CUENTA__IDSeleccionActualLista},
					 'onSuccess':Form_DEFINICIONES_TIPOS_DE_CUENTA__EliminarMensaje,
					 'url':'lib/controladorCes.php',
					 'onError':function(req){alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
					 });	
	}

/**
* Muestra el mensaje al eliminar el elemento seleccionado
* @param {Array} req respuesta luego de guardar los datos 1=exito, !1=fracaso
*/
function Form_DEFINICIONES_TIPOS_DE_CUENTA__EliminarMensaje(req){
	var respuesta = req.responseText;
	
	//alert(respuesta);
	
	if(respuesta==1){
		Form_DEFINICIONES_TIPOS_DE_CUENTA__LimpiarInputTextBuscarListado();
		Form_DEFINICIONES_TIPOS_DE_CUENTA__Mensaje("La eliminación se realizó satisfactoriamente.","VERDE");
		Form_DEFINICIONES_TIPOS_DE_CUENTA__MensajeListado("La eliminación se realizó satisfactoriamente.","VERDE");
		//Actualizar las ventanas que esten abiertas que tengan el campo tipo de cuenta
		//Form_DEFINICIONES_CUENTAS_BANCARIAS__ActualizarSelectTipoCuenta();
		Form_DEFINICIONES_LUGARES__ActualizarSelectTipoCuenta();
		}
	else{
		Form_DEFINICIONES_TIPOS_DE_CUENTA__Mensaje("Error. No se pudo eliminar los datos. Verifique si hay eventos asociados a ese lugar.","ROJO");
		Form_DEFINICIONES_TIPOS_DE_CUENTA__MensajeListado("Error. No se pudo eliminar los datos. Verifique si hay eventos asociados a ese lugar.","ROJO");
		}	
	}

