/****************************************************************************************
* DEV: CONTRALORIA DEL ESTADO SUCRE - VENEZUELA
* PROYECTO: SIACEDA
* OPERADORES_____________________________________________________________________________________________________________________________
* | # | PROGRAMADOR          |  |   FECHA    |  |   HORA   |   CELULAR  |   VERSION PAG  | DESCRIPCION DEL CAMBIO 
* | 2 | Christian Hernandez   |  | 27/09/2012 |  | 02:16:18 | 04128354891|      0.1.1.A   | creacion del script
* |________________________________________________________|_____________________________________________________________________________
* TIPO: JS
* DESCRIPCION: 
* UBICACION: VENEZUELA, SUCRE, CUMANA
* VERSION: 0.1.1.A 
* SOPORTE: Christian Hernandez 
* CONTACTO: www.cgesucre.gob.ve, @CESucre, contraloria.estado.sucre@cgesucre.gob.ve
*******************************************************************************************/

var ventanaEvento = null;//variable que contiene la ventana emergente para los alerta de los stock min y max
var ventanaVerificarPresu;
var ventanaVerificarImpu;

var xCoord = 70, yCoord = 70;
var codRequerimientoSecuenciaDetalle;
var codRequeActa;
var tamanoCadCodRequeSecu;

var puntajeCualitativoUno, puntajeCualitativoDos, puntajeCualitativoTres;
var	valorListaRequeTecUno = 0;
var	valorListaRequeTecDos = 0;
var	valorListaRequeTecTres = 0;

var	valorListaTiempoEntregaUno = 0;
var	valorListaTiempoEntregaDos = 0;
var	valorListaTiempoEntregaTres = 0;

var	valorListaCondicionPagoUno = 0;
var	valorListaCondicionPagoDos = 0;
var	valorListaCondicionPagoTres = 0;


function crearVentanaStockMinimo()
{
	
	ventanaNormalEvento();//creando la vEmergente nueva
	ventanaEvento.esconder();
	
	var capaContenidoVent = xGetElementById('stockMinMaxAlmacen');
	capaContenidoVent.style.overflow = "scroll";
	
	capaContenidoVent.className = 'ces_fuente';
	var contenidoCapaVentana;
	
	ventanaEvento.tbar.id = 'capaTitulo';
	xGetElementById('capaTitulo').style.color = '#F66';
	
	AjaxRequest.post
	(
		{
			'url':'lib/controladorCes.php',
			'parameters':{'caso':'buscarItemStockMinimo'},
			'queryString':'',
			'onSuccess': function(req)
							{

								if (req.responseText != 0)
								{
									
									capaContenidoVent.innerHTML = req.responseText;
									ventanaEvento.mostrar();
									
								} else {
									
									ventanaEvento.esconder();
								}
								
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);
	
		
	
	/*if(ventanaEvento != null)
	{
		ventanaEvento.esconder();
	}*/
	
} 

function ventanaNormalEvento() 
{//..............................................
	

	ventanaEvento = new vEmergente
		(
		'stockMinMaxAlmacen',
		'Los cantidad actual de los siguientes Ítem estan igual o por debajo del mínimo',
		xCoord, yCoord,
		750, 450,
		true, 
		true, 
		false, 
		true, 
		null, null, null, null
		);	
		
		//ventanaEvento.esconder();
}//................................................


function consultarEstadoReq(llamado)
{
	
	if (llamado == 'inicioPagina')
	{
		
		buscarRequerimientoPendiente();
		//buscarRequerimientoRevisado();
		//buscarRequerimientoConformado();
		
	} else {
		
		var tiempoActualizacion = 500000;
		setInterval('buscarRequerimientoPendiente()',tiempoActualizacion);
		//setInterval('buscarRequerimientoRevisado()',tiempoActualizacion);
		//setInterval('buscarRequerimientoConformado()',tiempoActualizacion);
	}
	
	
}

function buscarRequerimientoPendiente()
{
	tamanoEspacioVentana = TamVentana();
	
	var ventanaReqPendiente = new vEmergente
		(
		'requerimientoPendiente',
		'Alerta',
		850, 350,
		300, 150,
		true, 
		true, 
		false, 
		true, 
		null, null, null, null, null
		);	
		
		ventanaReqPendiente.esconder();
		ventanaReqPendiente.tbar.id = 'capaTituloReqPen';
		 
		//xGetElementById('capaTituloReqPen').style.color = '#F66';


	
	var capaContenidoVent1 = xGetElementById('requerimientoPendiente');
	
	var ventanaReqRevisado = new vEmergente
		(
		'requerimientoRevisado',
		'Alerta',
		850, 350,
		300, 150,
		true, 
		true, 
		false, 
		true, 
		null, null, null, null, null
		);	
		
		ventanaReqRevisado.esconder();
		ventanaReqRevisado.tbar.id = 'capaTituloReqRev';
		 
		//xGetElementById('capaTituloReqRev').style.color = '#F66';

	
	var capaContenidoVent2 = xGetElementById('requerimientoRevisado');

	var ventanaReqConformado = new vEmergente
		(
		'requerimientoConformado',
		'Alerta',
		850, 350,
		300, 150,
		true, 
		true, 
		false, 
		true, 
		null, null, null, null, null
		);	
		
		ventanaReqConformado.esconder();
		ventanaReqConformado.tbar.id = 'capaTituloReqRev';
		 
		//xGetElementById('capaTituloReqRev').style.color = '#F66';

	
	var capaContenidoVent3 = xGetElementById('requerimientoConformado');
	
	bandera = 0;
	var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=420, height=350, top=0, left=0";
	
	AjaxRequest.post
	(
		{
			'url':'lib/controladorCes.php',
			'parameters':{'caso':'buscarRequerimientoPendiente'},
			'queryString':'',
			'onSuccess': function(req)
							{

								if (req.responseText != 0)
								{
									
									capaContenidoVent1.innerHTML = req.responseText;
									ventanaReqPendiente.mostrar();
									bandera = 1;
									
								} else {
									
									ventanaReqPendiente.esconder();
								}
								
								AjaxRequest.post
								(
									{
										'url':'lib/controladorCes.php',
										'parameters':{'caso':'buscarRequerimientoRevisado'},
										'queryString':'',
										'onSuccess': function(req)
														{
							
															if (req.responseText != 0)
															{
																
																capaContenidoVent2.innerHTML = req.responseText;
																ventanaReqRevisado.mostrar();
																bandera = 1;
																
															} else {
																
																ventanaReqRevisado.esconder();
															}
															
															AjaxRequest.post
															(
																{
																	'url':'lib/controladorCes.php',
																	'parameters':{'caso':'buscarRequerimientoConformado'},
																	'queryString':'',
																	'onSuccess': function(req)
																					{
														
																						if (req.responseText != 0)
																						{
																							
																							capaContenidoVent3.innerHTML = req.responseText;
																							ventanaReqConformado.mostrar();
																							bandera = 1;
																							
																						} else {
																							
																							ventanaReqConformado.esconder();
																						}
																						
																						if (bandera == 1)
																						{
																							popUp = window.open('contenidoPopUp.html','ventanaPopUp',opciones);
																							//popUp.focus();
																							bandera = 0;
																						}
																					},
																	'onError': function(req)
																			{ 
																				alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
																			}
																}
															);
														},
										'onError': function(req)
												{ 
													alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
												}
									}
								);
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);
}

function buscarRequerimientoRevisado()
{
	tamanoEspacioVentana = TamVentana();
	
	var ventanaReqRevisado = new vEmergente
		(
		'requerimientoRevisado',
		'Alerta',
		850, 350,
		300, 150,
		true, 
		true, 
		false, 
		true, 
		null, null, null, null, null
		);	
		
		ventanaReqRevisado.esconder();
		ventanaReqRevisado.tbar.id = 'capaTituloReqRev';
		 
		//xGetElementById('capaTituloReqRev').style.color = '#F66';

	
	var capaContenidoVent = xGetElementById('requerimientoRevisado');
	

	
	AjaxRequest.post
	(
		{
			'url':'lib/controladorCes.php',
			'parameters':{'caso':'buscarRequerimientoRevisado'},
			'queryString':'',
			'onSuccess': function(req)
							{

								if (req.responseText != 0)
								{
									
									capaContenidoVent.innerHTML = req.responseText;
									ventanaReqRevisado.mostrar();
									
								} else {
									
									ventanaReqRevisado.esconder();
								}
								
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);
}

function buscarRequerimientoConformado()
{
	tamanoEspacioVentana = TamVentana();
	
	var ventanaReqConformado = new vEmergente
		(
		'requerimientoConformado',
		'Alerta',
		850, 350,
		300, 150,
		true, 
		true, 
		false, 
		true, 
		null, null, null, null, null
		);	
		
		ventanaReqConformado.esconder();
		ventanaReqConformado.tbar.id = 'capaTituloReqRev';
		 
		//xGetElementById('capaTituloReqRev').style.color = '#F66';

	
	var capaContenidoVent = xGetElementById('requerimientoConformado');
	

	
	AjaxRequest.post
	(
		{
			'url':'lib/controladorCes.php',
			'parameters':{'caso':'buscarRequerimientoConformado'},
			'queryString':'',
			'onSuccess': function(req)
							{

								if (req.responseText != 0)
								{
									
									capaContenidoVent.innerHTML = req.responseText;
									ventanaReqConformado.mostrar();
									
								} else {
									
									ventanaReqConformado.esconder();
								}
								
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);
}

//**********VERIFICACION PRESUPUESTARIA COMPRA*******//
function mostrarVentanaVerificarPresuComp(form)
{
	//tamanoEspacioVentana = TamVentana();
	
	ventanaVerificarPresu = new vEmergente
		(
		'contenidoVerificarPresu',
		'Verificación Presupuestaria',
		350, 150,
		300, 380,
		true, 
		true, 
		false, 
		true, 
		null, null, null, null, null
		);	
		


	var capaContenidoVent = xGetElementById('contenidoVerificarPresu');
	var contenidoVentana;
	
	var contenidoVentana = '<table width="297" height="180" border="0">';
	contenidoVentana +='<tr>';
	contenidoVentana +='<td width="295" align="center"><div align="center" style="font-size: 24px;">';
	contenidoVentana +='¿HA REALIZADO LA VERIFICACI&Oacute;N PRESUPUESTARIA PARA ESTA ORDEN DE COMPRA? MARQUE EL CHECK PARA CONFIRMAR Y PASAR LA ORDEN A REVISADA';
	contenidoVentana +='</div></td></tr>';
	contenidoVentana +='<tr><td align="center"><label>';
	contenidoVentana +='<input type="checkbox" name="checkbox" id="verificar" value="checkbox"></label></td></tr>	';
	
	contenidoVentana +='<tr><td align="center"><label>';
	contenidoVentana +='<input type="button" name="Submit" value="Aceptar" onclick="marcarVerificacionPresupuestoCompra(\''+form.id+'\');" />';
	contenidoVentana +='</label></td></tr></table>';
	capaContenidoVent .innerHTML = contenidoVentana;	
	
	ventanaVerificarPresu.mostrar();

}

function marcarVerificacionPresupuestoCompra(idForm)
{

	
	var objCodOrganismo = xGetElementById('fCodOrganismo');
	var objNroOrden = xGetElementById('NroOrden');
	var objAnio = xGetElementById('Anio');

	var queryString = 'fCodOrganismo='+objCodOrganismo .value
	+'&anio='+objAnio.value
	+'&nroOrden='+objNroOrden.value;

	if (xGetElementById('verificar').checked == false)
	{
		alert('Marque el check de verificación');
		return;
		
	} 


	if(objNroOrden.value == '')
	{
	
		proceso = 'nuevo';
		
	
	} else {
		
		proceso = 'revisar';
	}
	

	
		
		queryString += '&proceso='+proceso;
		
		AjaxRequest.post
		(
			{
				'url':'lib/controladorCes.php',
				'parameters':{'caso':'marcarDisponiPresuCompra'},
				'queryString':queryString,
				'onSuccess': function(req)
								{
	
									if (req.responseText != 0)
									{
										
										ventanaVerificarPresu.esconder();
										//orden_compra(xGetElementById(idForm), proceso);
										xGetElementById(idForm).submit();
										
									} else {
										
	
										alert('No se pudo guardar la verificación presupuestaria');
										return;
									}
									
								},
				'onError': function(req)
						{ 
							alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
						}
			}
		);
	
}
//**********VERIFICACION PRESUPUESTARIA COMPRA*******//


//**********VERIFICACION IMPUTACION COMPRA*******//
function mostrarVentanaVerificarImpuComp(form)
{
	//tamanoEspacioVentana = TamVentana();
	
	ventanaVerificarImpu = new vEmergente
		(
		'contenidoVerificarImpu',
		'Verificación Imputación Correcta',
		350, 150,
		300, 380,
		true, 
		true, 
		false, 
		true, 
		null, null, null, null, null
		);	
		


	var capaContenidoVent = xGetElementById('contenidoVerificarImpu');
	var contenidoVentana;
	
	var contenidoVentana = '<table width="297" height="180" border="0">';
	contenidoVentana +='<tr>';
	contenidoVentana +='<td width="295" align="center"><div align="center" style="font-size: 24px;">';
	contenidoVentana +='¿HA REALIZADO LA VERIFICACI&Oacute;N DE IMPUTACI&Oacute;N CORRECTA PARA ESTA ORDEN DE COMPRA? MARQUE EL CHECK PARA CONFIRMAR Y APROBAR LA ORDEN';
	contenidoVentana +='</div></td></tr>';
	contenidoVentana +='<tr><td align="center"><label>';
	contenidoVentana +='<input type="checkbox" name="checkbox" id="verificar" value="checkbox"></label></td></tr>	';
	
	contenidoVentana +='<tr><td align="center"><label>';
	contenidoVentana +='<input type="button" name="Submit" value="Aceptar" onclick="marcarVerificacionImputacionCompra(\''+form.id+'\');" />';
	contenidoVentana +='</label></td></tr></table>';
	capaContenidoVent .innerHTML = contenidoVentana;	
	
	ventanaVerificarImpu.mostrar();

}

function marcarVerificacionImputacionCompra(idForm)
{

	
	var objCodOrganismo = xGetElementById('fCodOrganismo');
	var objNroOrden = xGetElementById('NroOrden');
	var objAnio = xGetElementById('Anio');

	var queryString = 'fCodOrganismo='+objCodOrganismo .value
	+'&anio='+objAnio.value
	+'&nroOrden='+objNroOrden.value;

	if (xGetElementById('verificar').checked == false)
	{
		alert('Marque el check de verificación');
		return;
		
	} 


	AjaxRequest.post
	(
		{
			'url':'lib/controladorCes.php',
			'parameters':{'caso':'marcarImpuCompra'},
			'queryString':queryString,
			'onSuccess': function(req)
							{

								if (req.responseText != 0)
								{
									
									ventanaVerificarImpu.esconder();
									//orden_compra(xGetElementById(idForm), 'aprobar');
									xGetElementById(idForm).submit();
									
								} else {
									

									alert('No se pudo guardar la verificación de imputación correcta');
									return;
								}
								
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);
}
//**********VERIFICACION IMPUTACION COMPRA*******//

// JavaScript Document//**********VERIFICACION PRESUPUESTARIA SERVICIO*******//
function mostrarVentanaVerificarPresuSer(form)
{
	//tamanoEspacioVentana = TamVentana();
	
	ventanaVerificarPresu = new vEmergente
		(
		'contenidoVerificarPresu',
		'Verificación Presupuestaria',
		350, 150,
		300, 380,
		true, 
		true, 
		false, 
		true, 
		null, null, null, null, null
		);	
		


	var capaContenidoVent = xGetElementById('contenidoVerificarPresu');
	var contenidoVentana;
	
	var contenidoVentana = '<table width="297" height="180" border="0">';
	contenidoVentana +='<tr>';
	contenidoVentana +='<td width="295" align="center"><div align="center" style="font-size: 24px;">';
	contenidoVentana +='¿HA REALIZADO LA VERIFICACI&Oacute;N PRESUPUESTARIA PARA ESTA ORDEN DE SERVICIO? MARQUE EL CHECK PARA CONFIRMAR Y PASAR LA ORDEN A REVISADA';
	contenidoVentana +='</div></td></tr>';
	contenidoVentana +='<tr><td align="center"><label>';
	contenidoVentana +='<input type="checkbox" name="checkbox" id="verificar" value="checkbox"></label></td></tr>	';
	
	contenidoVentana +='<tr><td align="center"><label>';
	contenidoVentana +='<input type="button" name="Submit" value="Aceptar" onclick="marcarVerificacionPresupuestoServicio(\''+form.id+'\');" />';
	contenidoVentana +='</label></td></tr></table>';
	capaContenidoVent .innerHTML = contenidoVentana;	
	
	ventanaVerificarPresu.mostrar();

}

function marcarVerificacionPresupuestoServicio(idForm)
{

	
	var objCodOrganismo = xGetElementById('fCodOrganismo');
	var objNroOrden = xGetElementById('NroOrden');
	var objAnio = xGetElementById('Anio');

	var queryString = 'fCodOrganismo='+objCodOrganismo .value
	+'&anio='+objAnio.value
	+'&nroOrden='+objNroOrden.value;

	if (xGetElementById('verificar').checked == false)
	{
		alert('Marque el check de verificación');
		return;
		
	} 

	if(objNroOrden.value == '')
	{
		
		proceso = 'nuevo';
		
		
	} else {
		
		proceso = 'revisar';
	}
	
	//orden_compra(xGetElementById(idForm), proceso);
	
		
		queryString += '&proceso='+proceso;
		
	AjaxRequest.post
	(
		{
			'url':'lib/controladorCes.php',
			'parameters':{'caso':'marcarDisponiPresuServicio'},
			'queryString':queryString,
			'onSuccess': function(req)
							{

								if (req.responseText != 0)
								{
									
									ventanaVerificarPresu.esconder();
									//orden_servicio(xGetElementById(idForm), proceso);
									xGetElementById(idForm).submit();
									
								} else {
									

									alert('No se pudo guardar la verificación presupuestaria');
									return;
								}
								
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);
}
//**********VERIFICACION PRESUPUESTARIA SERVICIO*******//


//**********VERIFICACION IMPUTACION SERVICIO*******//
function mostrarVentanaVerificarImpuSer(form)
{
	//tamanoEspacioVentana = TamVentana();
	
	ventanaVerificarImpu = new vEmergente
		(
		'contenidoVerificarImpu',
		'Verificación Imputación Correcta',
		350, 150,
		300, 380,
		true, 
		true, 
		false, 
		true, 
		null, null, null, null, null
		);	
		


	var capaContenidoVent = xGetElementById('contenidoVerificarImpu');
	var contenidoVentana;
	
	var contenidoVentana = '<table width="297" height="180" border="0">';
	contenidoVentana +='<tr>';
	contenidoVentana +='<td width="295" align="center"><div align="center" style="font-size: 24px;">';
	contenidoVentana +='¿HA REALIZADO LA VERIFICACI&Oacute;N DE IMPUTACI&Oacute;N CORRECTA PARA ESTA ORDEN DE SERVICIO? MARQUE EL CHECK PARA CONFIRMAR Y APROBAR LA ORDEN';
	contenidoVentana +='</div></td></tr>';
	contenidoVentana +='<tr><td align="center"><label>';
	contenidoVentana +='<input type="checkbox" name="checkbox" id="verificar" value="checkbox"></label></td></tr>	';
	
	contenidoVentana +='<tr><td align="center"><label>';
	contenidoVentana +='<input type="button" name="Submit" value="Aceptar" onclick="marcarVerificacionImputacionServicio(\''+form.id+'\');" />';
	contenidoVentana +='</label></td></tr></table>';
	capaContenidoVent .innerHTML = contenidoVentana;	
	
	ventanaVerificarImpu.mostrar();

}

function marcarVerificacionImputacionServicio(idForm)
{

	
	var objCodOrganismo = xGetElementById('fCodOrganismo');
	var objNroOrden = xGetElementById('NroOrden');
	var objAnio = xGetElementById('Anio');

	var queryString = 'fCodOrganismo='+objCodOrganismo .value
	+'&anio='+objAnio.value
	+'&nroOrden='+objNroOrden.value;

	if (xGetElementById('verificar').checked == false)
	{
		alert('Marque el check de verificación');
		return;
		
	} 


	AjaxRequest.post
	(
		{
			'url':'lib/controladorCes.php',
			'parameters':{'caso':'marcarImpuServicio'},
			'queryString':queryString,
			'onSuccess': function(req)
							{

								if (req.responseText != 0)
								{
									
									ventanaVerificarImpu.esconder();
									//orden_servicio(xGetElementById(idForm), 'aprobar');
									xGetElementById(idForm).submit();
									
								} else {
									

									alert('No se pudo guardar la verificación de imputación correcta');
									return;
								}
								
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);
}
//**********VERIFICACION IMPUTACION SERVICIO*******//


function  iniciarActaInicioCompra(form, pagina, target, param, nombre, idregistro, multi) {
	/*
	.-	form	-> referencia al formulario de la lista que se esta seleccionando (objeto)
	.-	pagina	-> ruta de la pagina que se abrira (string)
	.-	target	-> indica si la pagina se abrira en la misma ventana o en una ventana nueva (SELF, BLANK) (string)
	.-	nombre	-> nombre del check que se activa al seleccionar un registro (string)
	.-	multi	-> indica si se puede seleccionar mas de un registro (true, false) (boolean)
	*/
	
	//	lineas
	var error = "";
	var registro = "";
	var lineas = new Number(0);
	k = 0;
	
	for(i=0; n=form.elements[i]; i++) 
	{
		if (n.name == nombre && n.checked) 
		{
			registro += '&registro['+k+']=' + n.value;// + ";";
			lineas++; 
			k++;
		}
	}
	
	var len = registro.length; len--;
	registro = registro.substr(0, len);
	//document.getElementById(idregistro).value = registro;
	
	
	
	if (lineas == 0) 
	{
		alert("¡Debe seleccionar por lo menos un registro!");
		
	} else if (!multi && lineas > 1) {
		
		alert("¡No puede seleccionar más de un registro!");
		
	} else {
		
		
			pagina = pagina + registro;
			window.open(pagina, target="main");
		
	}
}
//	--------------------------------------

function inicializarCodRequerimientoSecuencia(codRequeSecuencia)
{
	//alert(vectorCodRequerimiento);
	codRequerimientoSecuenciaDetalle = codRequeSecuencia.split(',');
	codRequeActa = codRequerimientoSecuenciaDetalle[0].split('.');
	//alert(codRequerimientoSecuenciaDetalle);
	//tamanoCadCodRequeSecu = codRequeSecuencia.length;
	
	
}	
				
function guardarGenerarActaInicio(registroCodSecGenerarActa,llamado,tipoReque)
{
	var codAsistenteActaInicio = xGetElementById('asistenteActaInicio');
	var codDirectorActaInicio = xGetElementById('directorActaInicio');
	var queryString = 'codAsistenteActaInicio='+codAsistenteActaInicio.value+'&codDirectorActaInicio='+codDirectorActaInicio .value+'&tipoReque='+tipoReque;
	var objCodActa;
	var form = null;
	
	
	
	if (codAsistenteActaInicio.value == '')
	{
		alert('Seleccione el asistente de compras');
		return;
	}
	
	if (codDirectorActaInicio.value == '')
	{
		alert('Seleccione el Administrador de Compras');
		return;
	}
	
	
	if(llamado == 'guardar')
	{
		/*for (i = 0; i < tamanoCadCodRequeSecu; i++)
		{*/
			/*queryString += '&codRequerimientoSecuenciaDetalle='+codRequerimientoSecuenciaDetalle+*/
			queryString += '&codRequeGlobal='+codRequeActa[1];
		//}
		
		//alert(queryString);
		//return;
		AjaxRequest.post
		(
			{
				'url':'lib/controladorCes.php',
				'parameters':{'caso':'guardarGenerarActaInicio'},
				'queryString':queryString,
				'onSuccess': function(req)
								{
	
									if (req.responseText != 0)
									{
	
										respuesta = req.responseText;
										pagina = "odtphp/procesoCompra/inicioCompra"+respuesta+".odt";
										
										 for(i = 0; i< 20; i++)
											pagina = pagina.replace(" ","");        
	
	
										location.href= pagina;//"odtphp/procesoCompra/inicioCompra"+req.responseText+".odt";
										alert('Datos guardados con éxito');
										
										cargarPagina(xGetElementById('fromActa'),'lg_cotizaciones_invitar.php?concepto=01-0006&limit=0&filtrar=default');
										
										
										
									} else {
										
										alert('No se guardo los datos del acta de inicio');
										
	
										//cargarPagina(form,'lg_cotizaciones_invitar.php?concepto=01-0006&limit=0&filtrar=default');
									}
									
								},
				'onError': function(req)
						{ 
							alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
						}
			}
		);
		
	} else if(llamado == 'modificar')
	{
		objCodActa = xGetElementById('codActaInicio');
		
		queryString += '&codActa='+objCodActa.value;
		
		AjaxRequest.post
		(
			{
				'url':'lib/controladorCes.php',
				'parameters':{'caso':'modificarGenerarActaInicio'},
				'queryString':queryString,
				'onSuccess': function(req)
								{
	
									if (req.responseText != 0)
									{
	
										respuesta = req.responseText;
										pagina = "odtphp/procesoCompra/inicioCompra"+respuesta+".odt";
										
										 for(i = 0; i< 20; i++)
											pagina = pagina.replace(" ","");        
	
	
										location.href= pagina;//"odtphp/procesoCompra/inicioCompra"+req.responseText+".odt";
										alert('Datos modificados con éxito');
										
										cargarPagina(xGetElementById('fromActa'),'lg_vergeneraractainicio.php?concepto=01-0006&limit=0&filtrar=default');
										
										
										
									} else {
										
										alert('No se guardo los datos del acta de inicio');
										
	
										//cargarPagina(form,'lg_cotizaciones_invitar.php?concepto=01-0006&limit=0&filtrar=default');
									}
									
								},
				'onError': function(req)
						{ 
							alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
						}
			}
		);
	}
	
}

function guardarPliego(registro,llamado,resp)
{
	//alert(registro+'.'+llamado);
	
		queryString = 'codRequeGlobal='+registro;
		
		//alert(queryString);
		
		AjaxRequest.post
		(
			{
				'url':'lib/controladorCes.php',
				'parameters':{'caso':'guardarPliego'},
				'queryString':queryString,
				'onSuccess': function(req)
								{
	
									if (req.responseText != 0)
									{
	
										respuesta = req.responseText;
										
										pagina = "odtphp/procesoCompra/pliego"+resp+".odt";
										
										 for(i = 0; i< 20; i++)
											pagina = pagina.replace(" ","");        
	
	
										location.href= pagina;
										alert('Datos guardados con éxito');
										
										cargarPagina(xGetElementById('fromActa'),'lg_cotizaciones_invitar.php?concepto=01-0006&limit=0&filtrar=default');
										
										
										
									} else {
										
										alert('No se guardo los datos');
										
	
									}
									
								},
				'onError': function(req)
						{ 
							alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
						}
			}
		);
		
	} 
	




function buscarActaEvaluacion()
{
	var criterioBusqueda;
	var objCodigoActaBusqueda = xGetElementById('codigoActaBusqueda');
	var queryString;
	
	if (objCodigoActaBusqueda.value == '')
	{
		criterioBusqueda = 'todos';
		
	} else {
		
		criterioBusqueda = 'porNombre';
	}
	
	queryString = 'criterioBusqueda='+criterioBusqueda+'&variableBusqueda='+objCodigoActaBusqueda.value;
	
	
	
	AjaxRequest.post
			(
				{
					'url':'lib/controladorCes.php',
					'parameters':{'caso':'buscarActaInicioEvaluacion'},
					'queryString':queryString,
					'onSuccess': function(req)
									{
										
										mostrarResultadoActaInicio(req);

									},
					'onError': function(req)
							{ 
								alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
							}
				}
			);
}

function buscarActa()
{
	var criterioBusqueda;
	var objCodigoActaBusqueda = xGetElementById('codigoActaBusqueda');
	var queryString;
	
	if (objCodigoActaBusqueda.value == '')
	{
		criterioBusqueda = 'todos';
		
	} else {
		
		criterioBusqueda = 'porNombre';
	}
	
	queryString = 'criterioBusqueda='+criterioBusqueda+'&variableBusqueda='+objCodigoActaBusqueda.value;
	
	
	
	AjaxRequest.post
			(
				{
					'url':'lib/controladorCes.php',
					'parameters':{'caso':'buscarActaInicio'},
					'queryString':queryString,
					'onSuccess': function(req)
									{
										
										mostrarResultadoActaInicio(req);

									},
					'onError': function(req)
							{ 
								alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
							}
				}
			);
}

function mostrarResultadoActaInicio(req)
{
	var docXML = req.responseXML;
	var tempColumnaSubMenu = '';
	var cuerpoTablaBusqueda = xGetElementById('resultadoBusquedaActa');
	var objFila= xGetElementById('fila');
	var objEstiloFila= xGetElementById('estiloFila');
	var objCodActa = xGetElementById('codigoActa');
	
	if (docXML.getElementsByTagName('resultado')[0].attributes[0].value > 0)
	{
		if(docXML.hasChildNodes())
		{
			
			
			objXML = docXML.getElementsByTagName('fila');
			
			eliminaHijo(cuerpoTablaBusqueda);
			
			for (var i=0; i < objXML.length; i++)
			{
				
			
				j = i+1;
				
				
				
				
				if (j < objXML.length)
				{
					if (objXML[i+1].attributes[0].value == objXML[i].attributes[0].value)
					{
						tempColumnaSubMenu += objXML[i].attributes[1].value+', ';
						
						
					} else {
					
						fila = crearObjeto('tr',{'id':'actaInicio'+i,'align':"center",'class':'trListaBody','onclick':'cambiarEstiloFilaClic(this.id,\'trListaBodySel\',\'trListaBody\',\'fila\',\'estiloFila\');asignarCodigo(\'codigoActa\',\''+objXML[i].attributes[0].value+'\')'},null,null);
						columnaId = crearObjeto('td',{},'0004-CPAI-'+rellenarConCero(objXML[i].attributes[2].value,3)+'-'+objXML[i].attributes[3].value,fila);
						columnaRequerimiento = crearObjeto('td',{},tempColumnaSubMenu+objXML[i].attributes[1].value,fila);
						tempColumnaSubMenu = '';
						
						asociaHijo(fila,cuerpoTablaBusqueda);
					}
				
				
			    } else {
			    	
			    	
			    	fila = crearObjeto('tr',{'id':'actaInicio'+i,'align':"center",'class':'trListaBody','onclick':'cambiarEstiloFilaClic(this.id,\'trListaBodySel\',\'trListaBody\',\'fila\',\'estiloFila\');asignarCodigo(\'codigoActa\',\''+objXML[i].attributes[0].value+'\')'},null,null);
			    	columnaId = crearObjeto('td',{},'0004-CPAI-'+rellenarConCero(objXML[i].attributes[2].value,3)+'-'+objXML[i].attributes[3].value,fila);
					//columnaId = crearObjeto('td',{},objXML[i].attributes[0].value,fila);
					columnaRequerimiento = crearObjeto('td',{},tempColumnaSubMenu+objXML[i].attributes[1].value,fila);
					tempColumnaSubMenu = '';
						
						asociaHijo(fila,cuerpoTablaBusqueda);
				    }
			    	
			   }
   
		    /*for (var i=0; i<objXML.length; i++)
		    {
		    	fila = crearObjeto('tr',{'id':'actaInicio'+i,'class':'trListaBody','onclick':'cambiarEstiloFilaClic(this.id,\'trListaBodySel\',\'trListaBody\',\'filaActividad\',\'estiloFila\');asignarCodigo(\'codigoActa\',\''+objXML[i].attributes[0].value+'\')'},null,null);
				columnaId = crearObjeto('td',{},objXML[i].attributes[0].value,fila);
				columnaRequerimiento = crearObjeto('td',{},objXML[i].attributes[1].value,fila);
				asociaHijo(fila,cuerpoTablaBusqueda);
				
		    }*/
		   
			xGetElementById('cantResultados').value = objXML.length;
			objFila.value = 0;
			objEstiloFila.value = 0;
			objCodActa.value = 0;
			

		}
		
	} else {
		
		eliminaHijo(cuerpoTablaBusqueda);
		alert("No se encontraron Actas de Inicio");
		
	}
	

	
}

function cargarDatosModificarActa()
{
	var objCodActa = xGetElementById('codigoActa');
	
	if (objCodActa.value == 0)
	{
		alert('Seleccione un registro');
		return;
	}	
	
	queryString = '&variableBusqueda='+objCodActa.value;
	
	pagina = "lg_acta_inicio_compra_modificar.php?"+queryString;
	
	location.href= pagina;
	
	
}

function generarActaInicio()
{
	var objCodActa = xGetElementById('codigoActa');
	
	if (objCodActa.value == 0)
	{
		alert('Seleccione un registro');
		return;
	}	
	
	queryString = '&variableBusqueda='+objCodActa.value;
	
	AjaxRequest.post
	(
		{
			'url':'lib/controladorCes.php',
			'parameters':{'caso':'generarActaInicio'},
			'queryString':queryString,
			'onSuccess': function(req)
							{

								if (req.responseText != 0)
								{

									respuesta = req.responseText;
									pagina = "odtphp/procesoCompra/inicioCompra"+respuesta+".odt";
									
									 for(i = 0; i< 20; i++)
										pagina = pagina.replace(" ","");        


									location.href= pagina;
									//alert('Datos guardados con éxito');
									
									//cargarPagina(xGetElementById('fromActa'),'lg_vergeneraractainicio?concepto=01-0006&limit=0&filtrar=default');
									
									
									
								} else {
									
									alert('No se guardo los datos del acta de inicio');
									

									//cargarPagina(form,'lg_cotizaciones_invitar.php?concepto=01-0006&limit=0&filtrar=default');
								}
								
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);
	
}
function generarPliego()
{
	var objCodActa = xGetElementById('codigoActa');
	
	queryString = '&variableBusqueda='+objCodActa.value;
	
	AjaxRequest.post
	(
		{
			'url':'lib/controladorCes.php',
			'parameters':{'caso':'generarPliego'},
			'queryString':queryString,
			'onSuccess': function(req)
							{

								if (req.responseText != 0)
								{

									respuesta = req.responseText;
									pagina = "odtphp/procesoCompra/pliego"+respuesta+".odt";
									
									 for(i = 0; i< 20; i++)
										pagina = pagina.replace(" ","");        


									location.href= pagina;
									
								} else {
									
									alert('No se guardo los datos del pliego de condiciones');
								
								}
								
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);
	
}
function cambiarEstiloFilaClic(idFila,estiloAplicar,estiloAnterior,idCampoOculto,idCampoEstiloAnterior)
{
	if(xGetElementById(idFila).className != estiloAplicar)
	{
		var filaAnterior = xGetElementById(idCampoOculto).value;
		
		var filaSeleccionada = xGetElementById(idFila);
		
		filaSeleccionada.className = estiloAplicar;
		
		if (filaAnterior!=0)
		{
					
			var filaEstiloAnterior = xGetElementById(filaAnterior);
			filaEstiloAnterior.className = xGetElementById(idCampoEstiloAnterior).value;
		
			
		}
		xGetElementById(idCampoEstiloAnterior).value = estiloAnterior;
		xGetElementById(idCampoOculto).value = idFila;
	}
}

function alternaColor(j)
{
	var mod = j;
	
	if(mod % 2 == 0)
	{
   		estilo = "filaAlternoUno";
		
	} else {
		
       	estilo = "filaAlternoDos";
	}
	
	return estilo;
	
}

function asignarCodigo(id,codigo)
{
	xGetElementById(id).value = codigo;
}


function asignarEstiloFilas(limite,id)
{
	var j = 0;
	var fila;
	var objFila;

	
	for(var i = 0; i < limite; i++)
	{
		fila = id+''+i;
		objFila = xGetElementById(fila);
		
		if(objFila != null)
		{
			objFila.className = alternaColor(j);
			
			propiedades = {'onmouseout':'cambiarEstiloFila(this.id,\''+alternaColor(j)+'\',\'filaClick\')'};
			
			for (pr in propiedades)
			{
				objFila.setAttribute(pr, propiedades[pr]);
				
			}
			j++;
			
		}
		
	}
	
}
//--------------------------------------------------------------------------------
function mostrarPaginaEvaluacion()
{
	if(xGetElementById('codigoActa').value == '0')
	{
		alert('Seleccione un registro');
		return;
	}
	
	location.href='lg_evaluacion_cualitativa.php?codActa='+xGetElementById('codigoActa').value;
}

function sumarAspectoCualitativo(t)
{ 	// se quito el llamado a la función 'devolverValorLista' ya que se cambio el combolist por un cuadro te texto
	//Guidmar Espinoza 20-02-2014
	
	valorListaRequeTecUno = xGetElementById('proveedorRequeTec'+t).value;
	/*valorListaRequeTecUno = devolverValorLista('proveedorRequeTec'+t);
	valorListaRequeTecDos = devolverValorLista('proveedorRequeTec'+t);
	valorListaRequeTecTres = devolverValorLista('proveedorRequeTec'+t);*/
	
	valorListaTiempoEntregaUno  = xGetElementById('proveedorTiempoEntrega'+t).value;
	/*valorListaTiempoEntregaUno = devolverValorLista('proveedorTiempoEntrega'+t);
	valorListaTiempoEntregaDos = devolverValorLista('proveedorTiempoEntrega'+t);
	valorListaTiempoEntregaTres = devolverValorLista('proveedorTiempoEntrega'+t);*/
	
	valorListaCondicionPagoUno = xGetElementById('proveedorCondicionPago'+t).value;
	/*valorListaCondicionPagoUno = devolverValorLista('proveedorCondicionPago'+t);
	valorListaCondicionPagoDos = devolverValorLista('proveedorCondicionPago'+t);
	valorListaCondicionPagoTres = devolverValorLista('proveedorsCondicionPago'+t);*/

	puntajeCualitativoUno =(parseFloat(valorListaRequeTecUno)+parseFloat(valorListaTiempoEntregaUno)+parseFloat(valorListaCondicionPagoUno));
	/*puntajeCualitativoDos = (parseInt(valorListaTiempoEntregaDos)+parseInt(valorListaRequeTecDos)+parseInt(valorListaCondicionPagoDos));
	puntajeCualitativoTres = (parseInt(valorListaRequeTecTres)+parseInt(valorListaTiempoEntregaTres)+parseInt(valorListaCondicionPagoTres));	
	*/
	xGetElementById('totalPuntuacionProvee'+t).value = puntajeCualitativoUno;
	/*xGetElementById('totalPuntuacionProvee'+t).value = puntajeCualitativoDos;
	xGetElementById('totalPuntuacionProvee'+t).value = puntajeCualitativoTres;
	*/

	if(xGetElementById('puntajeCuantitativo'+t).value == '')
	{
		xGetElementById('puntajeCuantitativo'+t).value = 0;
	}
	xGetElementById('puntajeCualitativo'+t).value = xGetElementById('totalPuntuacionProvee'+t).value;
	xGetElementById('puntajeTotal'+t).value = parseFloat(xGetElementById('totalPuntuacionProvee'+t).value)+parseFloat(xGetElementById('puntajeCuantitativo'+t).value);
	

}

function guardarEvaluacionCualiCuanti(llamado)
{  // funcion modificada 20-02-2014 Guidmar Espinoza
	// se adecuó a los cuadros de texto que fueron sustituidos por los combo list en la pantalla lg_evaluacion_cualitativa.php

	var objObjetoEvaluacion = xGetElementById('objetoEvaluacion');
	var objCriterioEvaluacionCualitativa = xGetElementById('criterioEvaluacionCualitativa');
	var objCriterioEvaluacionCuantitativo = xGetElementById('criterioEvaluacionCuantitativo');
	var objPuntajeCualiTotal;
	var valorListaRequeTecUno;
	var valorListaTiempoEntregaUno;
	var valorListaCondicionPagoUno;
	var objPMO_POE;
	var objPP;
	var objCodProveedor;
	
	
	var objConclusionEvaluacion = xGetElementById('conclusionEvaluacion');
	var objRecomendacionEvaluacion = xGetElementById('recomendacionEvaluacion');
	
	var objAsistenteEvaluacion= xGetElementById('asistenteEvaluacion');
	var objDirectorEvaluacion = xGetElementById('directorEvaluacion');
	var queryString='';
	
	if(objObjetoEvaluacion.value == '')
	{
		alert('Introduzca el objeto');
		return;
	}
	
	if(objCriterioEvaluacionCualitativa.value == '')
	{
		alert('Introduzca los criterios de evaluación cualitativo');
		return;
	}
	
	queryString+= '&objetoEvaluacion='+objObjetoEvaluacion.value
	+'&criterioEvaluacionCualitativa='+objCriterioEvaluacionCualitativa.value
	+'&criterioEvaluacionCuantitativo='+objCriterioEvaluacionCuantitativo.value
	+'&conclusionEvaluacion='+objConclusionEvaluacion.value
	+'&recomendacionEvaluacion='+objRecomendacionEvaluacion.value
	+'&asistenteEvaluacion='+objAsistenteEvaluacion.value
	+'&directorEvaluacion='+objDirectorEvaluacion.value
	+'&codActaInicio='+xGetElementById('codActaInicio').value;
	
	
	for (i = 0; i < xGetElementById('cantidadProveedores').value; i++)
	{
		// se cambio a punto flotante, ya que el usuario puede ingresar valores decimales
		var objCodProveedor = xGetElementById('codProveedor'+i).value;
		var objPuntajeCualiTotal = parseFloat(xGetElementById('totalPuntuacionProvee'+i).value);
		var valorListaRequeTecUno = parseFloat(xGetElementById('proveedorRequeTec'+i).value);
		var valorListaTiempoEntregaUno = parseFloat(xGetElementById('proveedorTiempoEntrega'+i).value);
		var valorListaCondicionPagoUno = parseFloat(xGetElementById('proveedorCondicionPago'+i).value);
		var objPMO_POE = parseFloat(xGetElementById('PMO_POE'+i).value);
		var objPP = parseFloat(xGetElementById('PP'+i).value);
		//se alteró para que acepte el valor 0 y solo de el alerta si la sumatoria da negativo o mayor a 40
		if(valorListaCondicionPagoUno < '0')
		{
			alert('Existe puntaje sin seleccionar en la evaluación cualitativa (Condición de Pago)');
			return;
			break;	
		}
		
		if(valorListaTiempoEntregaUno < '0')
		{
			alert('Existe puntaje sin seleccionar en la evaluación cualitativa (Tiempo de Entrega)');
			return;
			break;	
		}
		
		if(valorListaRequeTecUno <'0')
		{
			alert('Existe puntaje sin seleccionar en la evaluación cualitativa (Requerimiento Técnico)');
			return;
			break;	
		}
		
		if(valorListaCondicionPagoUno > '40')
		{
			alert('Existe puntaje mayor a 40 en la evaluación cualitativa (Condición de Pago)');
			return;
			break;	
		}
		
		if(valorListaTiempoEntregaUno > '40')
		{
			alert('Existe puntaje mayor a 40 en la evaluación cualitativa (Tiempo de Entrega)');
			return;
			break;	
		}
		
		if(valorListaRequeTecUno > '40')
		{
			alert('Existe puntaje mayor a 40 en la evaluación cualitativa (Requerimiento Técnico)');
			return;
			break;	
		}
		
		
		queryString+= '&codProveedor['+i+']='+objCodProveedor
		+'&puntajeCualiTotal['+i+']='+objPuntajeCualiTotal
		+'&requeTec['+i+']='+valorListaRequeTecUno
		+'&tiempoEntregaUno['+i+']='+valorListaTiempoEntregaUno
		+'&condicionPagoUno['+i+']='+valorListaCondicionPagoUno
		+'&PMO_POE['+i+']='+objPMO_POE
		+'&PP['+i+']='+objPP;
		
	}
	
	if(objCriterioEvaluacionCuantitativo.value == '')
	{
		alert('Introduzca los criterios cuantitativos');
		return;
	}
	
	if(objConclusionEvaluacion.value == '')
	{
		alert('Introduzca la conclusión');
		return;
	}
	
	if(objRecomendacionEvaluacion.value == '')
	{
		alert('Introduzca la recomendación');
		return;
	}
	
	if(objAsistenteEvaluacion.value == '')
	{
		alert('Seleccione el aistente');
		return;
	}
	
	if(objDirectorEvaluacion.value == '')
	{
		alert('Seleccione el Director');
		return;
	}
	
	//document.write(queryString);
	//return;
	
	if (llamado == 'guardar')
	{
		AjaxRequest.post
		(
			{
				'url':'lib/controladorCes.php',
				'parameters':{'caso':'guardarEvaluacionProveedor'},
				'queryString':queryString,
				'onSuccess': function(req)
								{
	
									if (req.responseText != 0)
									{
	
										respuesta = req.responseText;
										//pagina = "odtphp/procesoCompra/evaluacionCualiCuanti"+respuesta+".odt";
										
										 //for(i = 0; i< 20; i++)
	//										pagina = pagina.replace(" ","");        
	
	
										//location.href= pagina;
										alert('Datos guardados con éxito');
										location.href= 'lg_veracta_inicio_evaluacion.php?concepto=01-0021&limit=0&filtrar=default';
										//cargarPagina(xGetElementById('frm_detalle'),'lg_evaluacion_cualitativa?concepto=01-0021&limit=0&filtrar=default');
										
										
										
									} else {
										
										alert('No se guardo los datos de la  evaluación cualitativa/cuantitativa');
										location.href= 'lg_veracta_inicio_evaluacion.php?concepto=01-0021&limit=0&filtrar=default';
										
										//cargarPagina(xGetElementById('frm_detalle'),'lg_evaluacion_cualitativa?concepto=01-0021&limit=0&filtrar=default');
									}
									
								},
				'onError': function(req)
						{ 
							alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
						}
			}
		);
		
	} else if(llamado == 'modificar')
	{
		queryString += '&CodEvaluacion='+xGetElementById('codEvaluacion').value;
		AjaxRequest.post
		(
			{
				'url':'lib/controladorCes.php',
				'parameters':{'caso':'modificarEvaluacionProveedor'},
				'queryString':queryString,
				'onSuccess': function(req)
								{
	
									if (req.responseText != 0)
									{
	
										respuesta = req.responseText;
										//pagina = "odtphp/procesoCompra/evaluacionCualiCuanti"+respuesta+".odt";
										
										 //for(i = 0; i< 20; i++)
	//										pagina = pagina.replace(" ","");        
	
	
										//location.href= pagina;
										alert('Datos modificados con éxito');
										location.href= 'lg_evaluacion_busqueda.php';
										//cargarPagina(xGetElementById('frm_detalle'),'lg_evaluacion_cualitativa?concepto=01-0021&limit=0&filtrar=default');
										
										
									} else {
										
										alert('No se guardo los datos de la  evaluación cualitativa/cuantitativa');
										location.href= 'lg_evaluacion_busqueda.php?';
										
										//cargarPagina(xGetElementById('frm_detalle'),'lg_evaluacion_cualitativa?concepto=01-0021&limit=0&filtrar=default');
									}
									
								},
				'onError': function(req)
						{ 
							alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
						}
			}
		);
		
	}
	
	
}

function buscarEvaluacion()
{
	var criterioBusqueda;
	var objCodigoActaBusqueda = xGetElementById('codigoActaBusqueda');
	var queryString;
	
	if (objCodigoActaBusqueda.value == '')
	{
		criterioBusqueda = 'todos';
		
	} else {
		
		criterioBusqueda = 'porNombre';
	}
	
	queryString = 'criterioBusqueda='+criterioBusqueda+'&variableBusqueda='+objCodigoActaBusqueda.value;
	
	
	
	AjaxRequest.post
			(
				{
					'url':'lib/controladorCes.php',
					'parameters':{'caso':'buscarEvaluacionModificar'},
					'queryString':queryString,
					'onSuccess': function(req)
									{
										
										mostrarResultadoEvaluacion(req);

									},
					'onError': function(req)
							{ 
								alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
							}
				}
			);
}


function mostrarResultadoEvaluacion(req)
{
	var docXML = req.responseXML;
	var tempColumnaSubMenu = '';
	var cuerpoTablaBusqueda = xGetElementById('resultadoBusquedaActa');
	var objFila= xGetElementById('fila');
	var objEstiloFila= xGetElementById('estiloFila');
	var objCodEvaluacion = xGetElementById('codigoActa');
	
	if (docXML.getElementsByTagName('resultado')[0].attributes[0].value > 0)
	{
		if(docXML.hasChildNodes())
		{
			
			
			objXML = docXML.getElementsByTagName('fila');
			
			eliminaHijo(cuerpoTablaBusqueda);
			
			for (var i=0; i < objXML.length; i++)
			{
				
			
				j = i+1;
				
				if (j < objXML.length)
				{
					if (objXML[i+1].attributes[0].value == objXML[i].attributes[0].value)
					{
						tempColumnaSubMenu += objXML[i].attributes[1].value+', ';
						
						
					} else {
					
						fila = crearObjeto('tr',{'id':'evaluacion'+i,'align':"center",'class':'trListaBody','onclick':'cambiarEstiloFilaClic(this.id,\'trListaBodySel\',\'trListaBody\',\'fila\',\'estiloFila\');asignarCodigo(\'codigoActa\',\''+objXML[i].attributes[0].value+'\')'},null,null);
						//columnaId = crearObjeto('td',{},objXML[i].attributes[0].value,fila);
						columnaId = crearObjeto('td',{},'0004-CPECC-'+rellenarConCero(objXML[i].attributes[2].value,3)+'-'+objXML[i].attributes[3].value,fila);
						columnaRequerimiento = crearObjeto('td',{},tempColumnaSubMenu+objXML[i].attributes[1].value,fila);
						tempColumnaSubMenu = '';
						
						asociaHijo(fila,cuerpoTablaBusqueda);
					}
				
				
			    } else {
			    	
			    	
			    	fila = crearObjeto('tr',{'id':'actaInicio'+i,'align':"center",'class':'trListaBody','onclick':'cambiarEstiloFilaClic(this.id,\'trListaBodySel\',\'trListaBody\',\'fila\',\'estiloFila\');asignarCodigo(\'codigoActa\',\''+objXML[i].attributes[0].value+'\')'},null,null);
					//columnaId = crearObjeto('td',{},objXML[i].attributes[0].value,fila);
					columnaId = crearObjeto('td',{},'0004-CPECC-'+rellenarConCero(objXML[i].attributes[2].value,3)+'-'+objXML[i].attributes[3].value,fila);
					columnaRequerimiento = crearObjeto('td',{},tempColumnaSubMenu+objXML[i].attributes[1].value,fila);
					tempColumnaSubMenu = '';
						
						asociaHijo(fila,cuerpoTablaBusqueda);
				    }
			    	
			   }
   
		 
		   
			xGetElementById('cantResultados').value = objXML.length;
			objFila.value = 0;
			objEstiloFila.value = 0;
			objCodEvaluacion.value = 0;
			

		}
		
	} else {
		
		eliminaHijo(cuerpoTablaBusqueda);
		alert("No se encontraron Evaluaciones");
		
	}
	

	
}

function cargarDatosModificarEvaluacion()
{
	var objCodActa = xGetElementById('codigoActa');
	
	if (objCodActa.value == 0)
	{
		alert('Seleccione un registro');
		return;
	}	
	
	//queryString = '&variableBusqueda='+objCodActa.value;
	
	location.href = 'lg_datos_modificar_evaluacion.php?codEvaluacion='+objCodActa.value+'&concepto=01-0025&limit=0&filtrar=default';
	
	
}

/**
 * FUNCIONES PARA PROCESOS GENERALES
 * 
 *  
 * */
function devolverValorLista(id)
{
	var nodo = xGetElementById(id);
	var valor = nodo.options[nodo.selectedIndex].value;
	return valor;
	
	
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

function TamVentana() {
  var Tamanyo = [0, 0];
  if (typeof window.innerWidth != 'undefined')
  {
    Tamanyo = [
        window.innerWidth,
        window.innerHeight
    ];
  }
  else if (typeof document.documentElement != 'undefined'
      && typeof document.documentElement.clientWidth !=
      'undefined' && document.documentElement.clientWidth != 0)
  {
 Tamanyo = [
        document.documentElement.clientWidth,
        document.documentElement.clientHeight
    ];
  }
  else   {
    Tamanyo = [
        document.getElementsByTagName('body')[0].clientWidth,
        document.getElementsByTagName('body')[0].clientHeight
    ];
  }
  return Tamanyo;
}

function cambiarFormatoFecha(fecha,formato,tipoFecha)
{

	var anio;
	var mes;
	var dia;
	var hora;
	var fech = '';
	
	if(fecha != null)
	{
		
		if (formato == 0)//formato para el cliente
		{
			if ((tipoFecha != null) && (tipoFecha == 'date'))
			{
				anio = fecha.substr(0,4);
				mes = fecha.substr(5,2);
				dia = fecha.substr(8,2);
				
				fech = dia + "-" + mes + "-" + anio;
				
			} else if ((tipoFecha != null) && (tipoFecha == 'timeStamp'))
			{
				anio = fecha.substr(0,4);
				mes = fecha.substr(5,2);
				dia = fecha.substr(8,2);
				
				hora = fecha.substr(11);
				
				fech = dia + "-" + mes + "-" + anio+' '+hora;
				
			}
			
		} else {//formato para la base de datos
			
			if ((tipoFecha != null) && (tipoFecha == 'date'))
			{
				anio = fecha.substr(6,4);
				mes = fecha.substr(3,2);
				dia = fecha.substr(0,2);
				
				fech = anio + "-" + mes + "-" + dia;
				
			} else if ((tipoFecha != null) && (tipoFecha == 'timeStamp'))
			{
				anio = fecha.substr(0,4);
				mes = fecha.substr(5,2);
				dia = fecha.substr(8,2);
				
				hora = fecha.substr(9);
				
				fech = dia + "-" + mes + "-" + anio+' '+hora;
				
			}
		}
		
		return fech;
	}
}


function devolverCadenaLista(id)
{
	var nodo = xGetElementById(id);
	var cadena = nodo.options[nodo.selectedIndex].innerHTML;
	return cadena;
	
}
//-------------------------------------------------------------------------------//

function buscarActaOrdenCompra()
{
	var criterioBusqueda;
	var objCodigoActaBusqueda = xGetElementById('codigoActaBusqueda');
	var queryString;
	
	if (objCodigoActaBusqueda.value == '')
	{
		criterioBusqueda = 'todos';
		
	} else {
		
		criterioBusqueda = 'porNombre';
	}
	
	queryString = 'criterioBusqueda='+criterioBusqueda+'&variableBusqueda='+objCodigoActaBusqueda.value;
	
	
	
	AjaxRequest.post
			(
				{
					'url':'lib/controladorCes.php',
					'parameters':{'caso':'buscarOrdenCompra'},
					'queryString':queryString,
					'onSuccess': function(req)
									{
										
										mostrarResultadoOrdenCompra(req);

									},
					'onError': function(req)
							{ 
								alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
							}
				}
			);
}


function mostrarResultadoOrdenCompra(req)
{
	var docXML = req.responseXML;
	var tempColumnaSubMenu = '';
	var cuerpoTablaBusqueda = xGetElementById('resultadoBusquedaActa');
	var objFila= xGetElementById('fila');
	var objEstiloFila= xGetElementById('estiloFila');
	var objCodActa = xGetElementById('codigoActa');
	
	if (docXML.getElementsByTagName('resultado')[0].attributes[0].value > 0)
	{
		if(docXML.hasChildNodes())
		{
			
			
			objXML = docXML.getElementsByTagName('fila');
			
			eliminaHijo(cuerpoTablaBusqueda);
			
				for (var i=0; i < objXML.length; i++)
				{
					
				
					fila = crearObjeto('tr',{'id':'ordenCompra'+i,'align':"center",'class':'trListaBody','onclick':'cambiarEstiloFilaClic(this.id,\'trListaBodySel\',\'trListaBody\',\'fila\',\'estiloFila\');asignarCodigo(\'codigoActa\',\''+objXML[i].attributes[0].value+'\')'},null,null);
					columnaId = crearObjeto('td',{},objXML[i].attributes[0].value,fila);
					columna = crearObjeto('td',{},objXML[i].attributes[1].value,fila);
					//columna = crearObjeto('td',{},cambiarFormatoFecha(objXML[i].attributes[2].value,'0','date'),fila);
				
					
					asociaHijo(fila,cuerpoTablaBusqueda);
							
					xGetElementById('cantResultados').value = objXML.length;
					objFila.value = 0;
					objEstiloFila.value = 0;
					objCodActa.value = 0;
				
	
			}
		
		}
		
	} else {
		
		eliminaHijo(cuerpoTablaBusqueda);
		alert("No se encontraron Ordenes de compra");
		
	}
	
}

function mostrarPaginaControlPerceptivo()
{
	if(xGetElementById('codigoActa').value == '0')
	{
		alert('Seleccione un registro');
		return;
	}
	
	location.href='lg_control_perceptivo.php?nroOrden='+xGetElementById('codigoActa').value;
}

function guardarGenerarControlPerceptivo(llamado)
{
	var objPersona1 = xGetElementById('persona1');
	var objPersona2 = xGetElementById('persona2');
	var objPersona3 = xGetElementById('persona3');
	var objPersona4 = xGetElementById('persona4');
	var objPersona5 = xGetElementById('persona5');
	var form = null;
	var queryString = '';
	var t = 0;
	
	for (i = 1; i <= 5; i++)
	{
		if(xGetElementById('persona'+i).value != '')
		{
			t++;
		}
	}
	
	if ((objPersona1.value == '')&&(objPersona2.value == '')&&(objPersona3.value == '')&&(objPersona4.value == '')&&(objPersona5.value == ''))
	{
		alert('Seleccione al menos 2 funcionarios');
		return;
	}
	
	
	if (t < 2)
	{
		alert('Seleccione al menos 2 funcionarios');
		return;
	}
	
	
	
	if(llamado == 'guardar')
	{
		
			queryString += '&nroOrden='+nroOrden
			+'&persona[0]='+objPersona1.value
			+'&persona[1]='+objPersona2.value
			+'&persona[2]='+objPersona3.value
			+'&persona[3]='+objPersona4.value
			+'&persona[4]='+objPersona5.value;
		
		//alert(queryString);
		//return;
		AjaxRequest.post
		(
			{
				'url':'lib/controladorCes.php',
				'parameters':{'caso':'guardarGenerarControlPerceptivo'},
				'queryString':queryString,
				'onSuccess': function(req)
								{
	
									if (req.responseText != 0)
									{
	
										respuesta = req.responseText;
										pagina = "odtphp/procesoCompra/controlPerceptivo"+respuesta+".odt";
										
										 for(i = 0; i< 20; i++)
											pagina = pagina.replace(" ","");        
	
	
										location.href= pagina;
										alert('Datos guardados con éxito');
										
										cargarPagina(xGetElementById('fromActa'),'lg_control_perceptivo_ordencompra.php?concepto=01-0006&limit=0&filtrar=default');
										
										
										
									} else {
										
										alert('No se modifico el acta de control perceptivo');
										
	
										//cargarPagina(xGetElementById('fromActa'),'lg_control_perceptivo_ordencompra.php?concepto=01-0006&limit=0&filtrar=default');
									}
									
								},
				'onError': function(req)
						{ 
							alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
						}
			}
		);
		
	} else if(llamado == 'modificar')
	{
		for(i = 0; i < cantidadItem; i++)
		{
			queryString += '&item['+i+']='+xGetElementById('recibido'+i).checked;
			queryString += '&cantidadRecibido['+i+']='+xGetElementById('cantidadRecibido'+i).value;
			queryString += '&observacionRecibido['+i+']='+xGetElementById('observacionRecibido'+i).value;
		}
		
		
		queryString += '&CodControlPerceptivo='+codControlPerceptivo;
		queryString += '&banderaCerrar='+xGetElementById('banderaCerrar').checked;
		
		queryString += '&nroOrden='+nroOrden
			+'&persona[0]='+objPersona1.value
			+'&persona[1]='+objPersona2.value
			+'&persona[2]='+objPersona3.value
			+'&persona[3]='+objPersona4.value
			+'&persona[4]='+objPersona5.value;
		
		//alert(queryString);
		//return;
		AjaxRequest.post
		(
			{
				'url':'lib/controladorCes.php',
				'parameters':{'caso':'modificarGenerarControlPerceptivo'},
				'queryString':queryString,
				'onSuccess': function(req)
								{
	
									if (req.responseText != 0)
									{
	
										respuesta = req.responseText;
										pagina = "odtphp/procesoCompra/controlPerceptivo"+respuesta+".odt";
										
										 for(i = 0; i< 20; i++)
											pagina = pagina.replace(" ","");        
	
	
										location.href= pagina;
										alert('Datos modificados con éxito');
										
										cargarPagina(xGetElementById('fromActa'),'lg_controlperceptivo_busqueda.php?concepto=01-0006&limit=0&filtrar=default');
										
										
										
									} else {
										
										alert('No se guardo los datos del acta de control perceptivo');
										
	
										//cargarPagina(xGetElementById('fromActa'),'lg_control_perceptivo_ordencompra.php?concepto=01-0006&limit=0&filtrar=default');
									}
									
								},
				'onError': function(req)
						{ 
							alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
						}
			}
		);
	}
	
}

function buscarControlPerceptivo()
{
	var criterioBusqueda;
	var objCodigoActaBusqueda = xGetElementById('codigoActaBusqueda');
	var queryString;
	
	if (objCodigoActaBusqueda.value == '')
	{
		criterioBusqueda = 'todos';
		
	} else {
		
		criterioBusqueda = 'porNombre';
	}
	
	queryString = 'criterioBusqueda='+criterioBusqueda+'&variableBusqueda='+objCodigoActaBusqueda.value;
	
	
	
	AjaxRequest.post
			(
				{
					'url':'lib/controladorCes.php',
					'parameters':{'caso':'buscarControlPerceptivo'},
					'queryString':queryString,
					'onSuccess': function(req)
									{
										
										mostrarResultadoControlPerceptivo(req);

									},
					'onError': function(req)
							{ 
								alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
							}
				}
			);
}


function mostrarResultadoControlPerceptivo(req)
{
	var docXML = req.responseXML;
	var tempColumnaSubMenu = '';
	var cuerpoTablaBusqueda = xGetElementById('resultadoBusquedaActa');
	var objFila= xGetElementById('fila');
	var objEstiloFila= xGetElementById('estiloFila');
	var objCodActa = xGetElementById('codigoActa');
	
	if (docXML.getElementsByTagName('resultado')[0].attributes[0].value > 0)
	{
		if(docXML.hasChildNodes())
		{
			
			
			objXML = docXML.getElementsByTagName('fila');
			
			eliminaHijo(cuerpoTablaBusqueda);
			
				for (var i=0; i < objXML.length; i++)
				{
					
				
					fila = crearObjeto('tr',{'id':'controlPerceptivo'+i,'align':"center",'class':'trListaBody','onclick':'cambiarEstiloFilaClic(this.id,\'trListaBodySel\',\'trListaBody\',\'fila\',\'estiloFila\');asignarCodigo(\'codigoActa\',\''+objXML[i].attributes[0].value+'\')'},null,null);
					columnaId = crearObjeto('td',{},objXML[i].attributes[0].value,fila);
					columna = crearObjeto('td',{},objXML[i].attributes[1].value,fila);
					columna = crearObjeto('td',{},objXML[i].attributes[2].value,fila);
					columna = crearObjeto('td',{},cambiarFormatoFecha(objXML[i].attributes[3].value,'0','date'),fila);
				
					
					asociaHijo(fila,cuerpoTablaBusqueda);
							
					xGetElementById('cantResultados').value = objXML.length;
					objFila.value = 0;
					objEstiloFila.value = 0;
					objCodActa.value = 0;
				
	
			}
		
		}
		
	} else {
		
		eliminaHijo(cuerpoTablaBusqueda);
		alert("No se encontraron Controles Perceptivos");
		
	}
	
}

function generarControlPerceptivo()
{
	var objCodActa = xGetElementById('codigoActa');
	
	if (objCodActa.value == 0)
	{
		alert('Seleccione un registro');
		return;
	}	
	
	queryString = '&variableBusqueda='+objCodActa.value;
	
	AjaxRequest.post
	(
		{
			'url':'lib/controladorCes.php',
			'parameters':{'caso':'generarControlPerceptivo'},
			'queryString':queryString,
			'onSuccess': function(req)
							{

								if (req.responseText != 0)
								{

									respuesta = req.responseText;
									pagina = "odtphp/procesoCompra/controlPerceptivo"+respuesta+".odt";
									
									 for(i = 0; i< 20; i++)
										pagina = pagina.replace(" ","");        


									location.href= pagina;
									//alert('Datos guardados con éxito');
									
									//cargarPagina(xGetElementById('fromActa'),'lg_vergeneraractainicio?concepto=01-0006&limit=0&filtrar=default');
									
									
									
								} else {
									
									alert('No se pudo generar el reporte');
									//cargarPagina(form,'lg_cotizaciones_invitar.php?concepto=01-0006&limit=0&filtrar=default');
								}
								
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);
	
}

function cargarDatosModificarControlPerceptivo()
{
	var objCodActa = xGetElementById('codigoActa');
	
	if (objCodActa.value == 0)
	{
		alert('Seleccione un registro');
		return;
	}	
	
	queryString = '&CodControlPerceptivo='+objCodActa.value;
	
	pagina = "lg_control_perceptivo_modificar.php?"+queryString;
	
	location.href= pagina;
	
	
}

function cargarPaginaInformeRecomendacion()
{
	var objCodActa = xGetElementById('codigoActa');
	
	if (objCodActa.value == 0)
	{
		alert('Seleccione un registro');
		return;
	}	
	
	
	location.href = 'lg_informerecomendacion.php?codEvaluacion='+objCodActa.value+'&concepto=01-0025&limit=0&filtrar=default';
	
}

function guardarInformeRecomendacion()
{
	var objAsunto = xGetElementById('asunto');
	var objObjeto = xGetElementById('objeto');
	var objConclusiones = xGetElementById('conclusiones');
	var objRecomendaciones = xGetElementById('recomendaciones');
	var valorListaTipoAdjudicacion = devolverValorLista('tipoAdjudicacion');
	var valorListaProveedor;
	
	var objPersona4 = xGetElementById('persona4');
	var objPersona5 = xGetElementById('persona5');
	var form = null;
	var queryString = '';
	var t = 0;
	
	if (objAsunto.value == '')
	{
		alert('Introduzca el asunto');
		return;
	}
	
	if (objObjeto.value == '')
	{
		alert('Introduzca el objeto de la consulta');
		return;
	}
	 
	if (objConclusiones.value == '')
	{
		alert('Introduzca las conclusiones');
		return;
	}
	
	if (objRecomendaciones.value == '')
	{
		alert('Introduzca las recomendaciones');
		return;
	}
	
	for (t=0; t < cantidadProveedor; t++)
	{
		 valorListaProveedor = devolverValorLista('recomendacionProveedor'+t);
		
		if ((valorListaProveedor == '-1') && (t == 0))
		{
			alert('Seleccione al menos un proveedor como primera opción');
			return;
			break;
		}
		
		if (valorListaProveedor != '-1') 
		{
			queryString += '&codProveedor['+t+']='+valorListaProveedor;
		}
	}
	
	if (valorListaTipoAdjudicacion == '-1') 
	{
		alert('Seleccione el tipo de adjudicación');
		return;
	}
	
	if (objPersona4.value == '')
	{
		alert('Seleccione el asistente');
		return;
	}
	
	if (objPersona5.value == '')
	{
		alert('Seleccione el Director');
		return;
	}

	
	queryString += '&codInformeRecomendacion='+codInformeRecomendacion
	+'&codEvaluacion='+codEvaluacion
	+'&asunto='+objAsunto.value
	+'&objeto='+objObjeto.value
	+'&conclusion='+objConclusiones.value
	+'&recomendacion='+objRecomendaciones.value
	+'&valorListaTipoAdjudicacion='+valorListaTipoAdjudicacion
	//+'&codProveedor='+valorListaProveedor
	+'&persona[3]='+objPersona4.value
	+'&persona[4]='+objPersona5.value
	+proveedor;		
		//alert(queryString);
		//return;
		AjaxRequest.post
		(
			{
				'url':'lib/controladorCes.php',
				'parameters':{'caso':'guardarModificarInformeRecomendacion'},
				'queryString':queryString,
				'onSuccess': function(req)
								{
	
									if (req.responseText != 0)
									{
	
										respuesta = req.responseText;
										pagina = "odtphp/procesoCompra/informeRecomendacion"+respuesta+".odt";
										
										 for(i = 0; i< 20; i++)
											pagina = pagina.replace(" ","");        
	
	
										location.href= pagina;
										alert('Datos guardados con éxito');
										
										cargarPagina(xGetElementById('fromActa'),'lg_evaluacion_busqueda.php?concepto=01-0006&limit=0&filtrar=default');
										
										
										
									} else {
										
										alert('No se guardaron los datos');
										
	
										//cargarPagina(xGetElementById('fromActa'),'lg_control_perceptivo_ordencompra.php?concepto=01-0006&limit=0&filtrar=default');
									}
									
								},
				'onError': function(req)
						{ 
							alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
						}
			}
		);
}

function buscarInformeRecomendacion()
{
	var criterioBusqueda;
	var objCodigoActaBusqueda = xGetElementById('codigoActaBusqueda');
	var queryString;
	
	if (objCodigoActaBusqueda.value == '')
	{
		criterioBusqueda = 'todos';
		
	} else {
		
		criterioBusqueda = 'porNombre';
	}
	
	queryString = 'criterioBusqueda='+criterioBusqueda+'&variableBusqueda='+objCodigoActaBusqueda.value;
	
	
	
	AjaxRequest.post
			(
				{
					'url':'lib/controladorCes.php',
					'parameters':{'caso':'buscarInformeRecomendacion'},
					'queryString':queryString,
					'onSuccess': function(req)
									{
										
										mostrarResultadoInformeRecomendacion(req);

									},
					'onError': function(req)
							{ 
								alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
							}
				}
			);
}


function mostrarResultadoInformeRecomendacion(req)
{
	var docXML = req.responseXML;
	var tempColumnaSubMenu = '';
	var cuerpoTablaBusqueda = xGetElementById('resultadoBusquedaActa');
	var objFila= xGetElementById('fila');
	var objEstiloFila= xGetElementById('estiloFila');
	var objCodRecomendacion = xGetElementById('codigoActa');
	
	if (docXML.getElementsByTagName('resultado')[0].attributes[0].value > 0)
	{
		if(docXML.hasChildNodes())
		{
			
			
			objXML = docXML.getElementsByTagName('fila');
			
			eliminaHijo(cuerpoTablaBusqueda);
			
			for (var i=0; i < objXML.length; i++)
			{
				
			
				fila = crearObjeto('tr',{'id':'informeRecomendacion'+i,'align':"center",'class':'trListaBody','onclick':'cambiarEstiloFilaClic(this.id,\'trListaBodySel\',\'trListaBody\',\'fila\',\'estiloFila\');asignarCodigo(\'codigoActa\',\''+objXML[i].attributes[0].value+'\')'},null,null);
					//columnaId = crearObjeto('td',{},objXML[i].attributes[0].value,fila);
					columnaId = crearObjeto('td',{},'0004-CPIR-'+rellenarConCero(objXML[i].attributes[5].value,3)+'-'+objXML[i].attributes[6].value,fila);
					columna = crearObjeto('td',{},objXML[i].attributes[1].value,fila);
					columna = crearObjeto('td',{},objXML[i].attributes[2].value,fila);
					columna = crearObjeto('td',{},objXML[i].attributes[4].value,fila);
				
					
					asociaHijo(fila,cuerpoTablaBusqueda);
							
					xGetElementById('cantResultados').value = objXML.length;
					objFila.value = 0;
					objEstiloFila.value = 0;
					objCodRecomendacion.value = 0;
				
			

			}
		}
		
	} else {
		
		eliminaHijo(cuerpoTablaBusqueda);
		alert("No se encontraron Informes de Recomendación");
		
	}
	

	
}

function cargarPaginaAdjudicacion()
{
	var objCodActa = xGetElementById('codigoActa');
	
	if (objCodActa.value == 0)
	{
		alert('Seleccione un registro');
		return;
	}	
	
	
	location.href = 'lg_informeadjudicacion.php?codRecomendacion='+objCodActa.value+'&concepto=01-0025&limit=0&filtrar=default';	
}

function guardarInformeAdjudicacion()
{
	//var objCodActa = xGetElementById('codigoActa');
	//var queryString = '&codInformeRecomendacion='+objCodActa.value;
	var valorCodProveedorAdjudicacion = devolverValorLista('adjudicacionProveedor');
	var objCheck;
	var vectorRequeSecue = Array();
	var requeSecueAdjudicaion = '';
	var queryString = 'codProveedor='+valorCodProveedorAdjudicacion+'&codRecomendacion='+codRecomendacion;
	vectorRequeSecue = cadenaCodRequeSecueAdjudicacion.split(',');
	
	if (valorCodProveedorAdjudicacion == '-1')
	{
		alert('Seleccione un proveedor');
		return;
	}	
	
	j = 0;
	
	for(i = 0; i < cantidadItem; i++)
	{
		objCheck = xGetElementById('adjudicar'+i);
		
		if(objCheck.checked == true)
		{
			queryString += '&requeSecueAdjudicaion['+j+']='+vectorRequeSecue[i];
			j++;
		}
		
	}
	
	if (j == 0)
	{
		alert('Selecciona al menos un ítems');
		return;
	}
	
	AjaxRequest.post
	(
		{
			'url':'lib/controladorCes.php',
			'parameters':{'caso':'guardarGenerarInformeAdjudicacion'},
			'queryString':queryString,
			'onSuccess': function(req)
							{
								
								respuesta = req.responseText;
								pagina = "odtphp/procesoCompra/informeAdjudicacion"+respuesta+".odt";
								
								 for(i = 0; i< 20; i++)
									pagina = pagina.replace(" ","");        
	
	
								location.href= pagina;
								alert('Datos guardados con éxito');
								location.href = 'lg_recomendacion_busqueda.php';
								//cargarPagina(xGetElementById('fromActa'),'lg_evaluacion_busqueda.php?concepto=01-0006&limit=0&filtrar=default');
								

							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);
	
	//location.href = 'lg_informeadjudicacion.php?codInformeRecomendacion='+objCodActa.value+'&concepto=01-0025&limit=0&filtrar=default';
	
}



function generarInformeEvaluacion()
{
	var objCodActa = xGetElementById('codigoActa');
	
	if (objCodActa.value == 0)
	{
		alert('Seleccione un registro');
		return;
	}	
	
	var queryString = '&codEvaluacion='+objCodActa.value;
	
	//location.href='odtphp/procesoCompra/evaluacionCualitativaCuantitativa.php?'+queryString;
	
//	return;
	
	AjaxRequest.post
	(
		{
			'url':'lib/controladorCes.php',
			'parameters':{'caso':'generarEvaluacionActa'},
			'queryString':queryString,
			'onSuccess': function(req)
					{
						respuesta = req.responseText;
						pagina = "odtphp/procesoCompra/evaluacionCualitativaCuantitativa"+respuesta+".odt";
						
						 for(i = 0; i< 20; i++)
							pagina = pagina.replace(" ","");        


						location.href= pagina;
						alert('Acta Generada');
						location.href = 'lg_evaluacion_busqueda.php';
					},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);
	
	
//	location.href = 'odtphp/procesoCompra/evaluacionCualitativaCuantitativa.php?codEvaluacion='+objCodActa.value;
	
}

function buscarInformeAdjudicacion()
{
	var criterioBusqueda;
	var objCodigoBusqueda = xGetElementById('codigoBusqueda');
	var queryString;
	
	if (objCodigoBusqueda.value == '')
	{
		criterioBusqueda = 'todos';
		
	} else {
		
		criterioBusqueda = 'porNombre';
	}
	
	queryString = 'criterioBusqueda='+criterioBusqueda+'&variableBusqueda='+objCodigoBusqueda.value;
	
	
	
	AjaxRequest.post
			(
				{
					'url':'lib/controladorCes.php',
					'parameters':{'caso':'buscarInformeAdjudicacion'},
					'queryString':queryString,
					'onSuccess': function(req)
									{
										
										mostrarResultadoInformeAdjudicacion(req);

									},
					'onError': function(req)
							{ 
								alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
							}
				}
			);
}


function mostrarResultadoInformeAdjudicacion(req)
{
	var docXML = req.responseXML;
	var tempColumnaSubMenu = '';
	var cuerpoTablaBusqueda = xGetElementById('resultadoBusqueda');
	var objFila= xGetElementById('fila');
	var objEstiloFila= xGetElementById('estiloFila');
	var objCod = xGetElementById('codigo');
	
	if (docXML.getElementsByTagName('resultado')[0].attributes[0].value > 0)
	{
		if(docXML.hasChildNodes())
		{
			
			
			objXML = docXML.getElementsByTagName('fila');
			
			eliminaHijo(cuerpoTablaBusqueda);
			
			for (var i=0; i < objXML.length; i++)
			{
				
			
				fila = crearObjeto('tr',{'id':'informeAdjudicacion'+i,'align':"center",'class':'trListaBody','onclick':'cambiarEstiloFilaClic(this.id,\'trListaBodySel\',\'trListaBody\',\'fila\',\'estiloFila\');asignarCodigo(\'codigo\',\''+objXML[i].attributes[0].value+'\')'},null,null);
					columnaId = crearObjeto('td',{},rellenarConCero(objXML[i].attributes[7].value,3)+'-'+objXML[i].attributes[8].value,fila);
					columna = crearObjeto('td',{},'0004-CPIR-'+rellenarConCero(objXML[i].attributes[5].value,3)+'-'+objXML[i].attributes[6].value,fila);
					//columna = crearObjeto('td',{},objXML[i].attributes[1].value,fila);
					columna = crearObjeto('td',{},cambiarFormatoFecha(objXML[i].attributes[2].value,0,'date'),fila);
					columna = crearObjeto('td',{},objXML[i].attributes[4].value,fila);
				
					
					asociaHijo(fila,cuerpoTablaBusqueda);
							
					xGetElementById('cantResultados').value = objXML.length;
					objFila.value = 0;
					objEstiloFila.value = 0;
					objCod.value = 0;
				
			

			}
		}
		
	} else {
		
		eliminaHijo(cuerpoTablaBusqueda);
		alert("No se encontraron Informes de Adjudicación");
		
	}
	

	
}

function generarInformeAdjudicacion()
{
	var objCod = xGetElementById('codigo');
	
	if (objCod.value == 0)
	{
		alert('Seleccione un registro');
		return;
	}	
	
	var queryString = '&CodAdjudicacion='+objCod.value;
	
	//location.href='odtphp/procesoCompra/informeAdjudicacion.php?'+queryString;
	
	//return;
	
	AjaxRequest.post
	(
		{
			'url':'lib/controladorCes.php',
			'parameters':{'caso':'generarAdjudicacion'},
			'queryString':queryString,
			'onSuccess': function(req)
							{
								respuesta = req.responseText;
								pagina = "odtphp/procesoCompra/informeAdjudicacion"+respuesta+".odt";
								
								 for(i = 0; i< 20; i++)
									pagina = pagina.replace(" ","");        
	
	
								location.href = pagina;
								alert('Informe Generado');
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);
	
	
//	location.href = 'odtphp/procesoCompra/evaluacionCualitativaCuantitativa.php?codEvaluacion='+objCodActa.value;
	
}

function cargarDatosModificarAdjudicacion()
{
	var objCod = xGetElementById('codigo');
	
	if (objCod.value == 0)
	{
		alert('Seleccione un registro');
		return;
	}	
	
	//queryString = '&variableBusqueda='+objCodActa.value;
	
	location.href = 'lg_informeadjudicacion_modificar.php?CodAdjudicacion='+objCod.value;
	
	
}

function modificarInformeAdjudicacion()
{
	//var objCodActa = xGetElementById('codigoActa');
	//var queryString = '&codInformeRecomendacion='+objCodActa.value;
	var valorCodProveedorAdjudicacion = devolverValorLista('adjudicacionProveedor');
	var objCheck;
	var vectorRequeSecue = Array();
	var requeSecueAdjudicaion = '';
	var queryString = 'codProveedor='+valorCodProveedorAdjudicacion+'&codRecomendacion='+codRecomendacion+'&CodAdjudicacion='+codAdjudi;
	vectorRequeSecue = cadenaCodRequeSecueAdjudicacion.split(',');
	
	if (valorCodProveedorAdjudicacion == '-1')
	{
		alert('Seleccione un proveedor');
		return;
	}	
	
	j = 0;
	
	for(i = 0; i < cantidadItem; i++)
	{
		objCheck = xGetElementById('adjudicar'+i);
		
		if(objCheck.checked == true)
		{
			queryString += '&requeSecueAdjudicaion['+j+']='+vectorRequeSecue[i];
			j++;
		}
		
	}
	
	/*if (j == 0)
	{
		alert('Selecciona al menos un ítems');
		return;
	}*/
	
	AjaxRequest.post
	(
		{
			'url':'lib/controladorCes.php',
			'parameters':{'caso':'modificarGenerarInformeAdjudicacion'},
			'queryString':queryString,
			'onSuccess': function(req)
							{
								
								respuesta = req.responseText;
								
								for(i = 0; i< 20; i++)
									respuesta = respuesta.replace(" ","");
											
								pagina = "odtphp/procesoCompra/informeAdjudicacion"+respuesta+".odt";
								
								if(respuesta != '0')
								{
									
									
									if (respuesta != '-1')
									{
										     
										location.href = pagina;
										
									}
									
									alert('Datos modificados con éxito');
									
									location.href = 'lg_adjudicacion_busqueda.php';
								
								} else if(respuesta == 0) {
									
									alert('Los datos no se pudieron modificar');
									
								}
								
								
								//cargarPagina(xGetElementById('fromActa'),'lg_evaluacion_busqueda.php?concepto=01-0006&limit=0&filtrar=default');
								

							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);
	
	//location.href = 'lg_informeadjudicacion.php?codInformeRecomendacion='+objCodActa.value+'&concepto=01-0025&limit=0&filtrar=default';
	
}

function cargarDeclararDesierto()
{
	var objCod = xGetElementById('codigoActa');
	
	if (objCod.value == 0)
	{
		alert('Seleccione un registro');
		return;
	}	
	
	//queryString = '&variableBusqueda='+objCodActa.value;
	
	location.href = 'lg_declarar_desierto.php?CodRecomendacion='+objCod.value;
	
	
}

function guardarDeclararDesierto(CodRecomendacion)
{
	
	
	var resp;
	var queryString = 'CodRecomendacion='+CodRecomendacion;
	
	resp = window.confirm('¿Esta seguro que desea declarar desierto este procedimiento?');
	
	if(resp == false)
	{
		clocation.href = 'lg_desierto_recomendacion_busqueda.php?concepto=01-0023&limit=0&filtrar=default';
		return;
		
	}
	
	AjaxRequest.post
	(
		{
			'url':'lib/controladorCes.php',
			'parameters':{'caso':'guardarDeclararDesierto'},
			'queryString':queryString,
			'onSuccess': function(req)
							{
								
								respuesta = req.responseText;
								
								
								if(respuesta != 0)
								{
									respuesta = req.responseText;
									pagina = "odtphp/procesoCompra/declararDesierto"+respuesta+".odt";
									
									 for(i = 0; i< 20; i++)
										pagina = pagina.replace(" ","");        
		
		
									location.href= pagina;

									alert('Datos guardados con éxito');
									
									location.href = 'lg_desierto_recomendacion_busqueda.php';
								
								} else if(respuesta == 0) {
									
									alert('Los datos no se pudieron guardar');
									
								}
								
								
								//cargarPagina(xGetElementById('fromActa'),'lg_evaluacion_busqueda.php?concepto=01-0006&limit=0&filtrar=default');
								

							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);
	
	
}

function buscarInformeDesierto()
{
	var criterioBusqueda;
	var objCodigoBusqueda = xGetElementById('codigoBusqueda');
	var queryString;
	
	if (objCodigoBusqueda.value == '')
	{
		criterioBusqueda = 'todos';
		
	} else {
		
		criterioBusqueda = 'porNombre';
	}
	
	queryString = 'criterioBusqueda='+criterioBusqueda+'&variableBusqueda='+objCodigoBusqueda.value;
	
	
	
	AjaxRequest.post
			(
				{
					'url':'lib/controladorCes.php',
					'parameters':{'caso':'buscarInformeDesierto'},
					'queryString':queryString,
					'onSuccess': function(req)
									{
										
										mostrarResultadoInformeDesierto(req);

									},
					'onError': function(req)
							{ 
								alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
							}
				}
			);
}


function mostrarResultadoInformeDesierto(req)
{
	var docXML = req.responseXML;
	var tempColumnaSubMenu = '';
	var cuerpoTablaBusqueda = xGetElementById('resultadoBusqueda');
	var objFila= xGetElementById('fila');
	var objEstiloFila= xGetElementById('estiloFila');
	var objCod = xGetElementById('codigo');
	
	if (docXML.getElementsByTagName('resultado')[0].attributes[0].value > 0)
	{
		if(docXML.hasChildNodes())
		{
			
			
			objXML = docXML.getElementsByTagName('fila');
			
			eliminaHijo(cuerpoTablaBusqueda);
			
			for (var i=0; i < objXML.length; i++)
			{
				
			
				fila = crearObjeto('tr',{'id':'informeDesierto'+i,'align':"center",'class':'trListaBody','onclick':'cambiarEstiloFilaClic(this.id,\'trListaBodySel\',\'trListaBody\',\'fila\',\'estiloFila\');asignarCodigo(\'codigo\',\''+objXML[i].attributes[0].value+'\')'},null,null);
					//columnaId = crearObjeto('td',{},objXML[i].attributes[10].value,fila);
					columnaId = crearObjeto('td',{},rellenarConCero(objXML[i].attributes[20].value,3)+'-'+objXML[i].attributes[21].value,fila);
					columna = crearObjeto('td',{},'0004-CPIR-'+rellenarConCero(objXML[i].attributes[11].value,3)+'-'+objXML[i].attributes[10].value,fila);
					columna = crearObjeto('td',{},objXML[i].attributes[7].value,fila);
					//columna = crearObjeto('td',{},objXML[i].attributes[4].value,fila);

					
					asociaHijo(fila,cuerpoTablaBusqueda);
							
					xGetElementById('cantResultados').value = objXML.length;
					objFila.value = 0;
					objEstiloFila.value = 0;
					objCod.value = 0;
				
			

			}
		}
		
	} else {
		
		eliminaHijo(cuerpoTablaBusqueda);
		alert("No se encontraron Procedimientos Desiertos");
		
	}
	

	
}

function generarInformeDesierto()
{
	var objCod = xGetElementById('codigo');
	
	if (objCod.value == 0)
	{
		alert('Seleccione un registro');
		return;
	}	
	
	var queryString = '&CodRecomendacion='+objCod.value;
	
	//location.href='odtphp/procesoCompra/informeAdjudicacion.php?'+queryString;
	
	//return;
	
	AjaxRequest.post
	(
		{
			'url':'lib/controladorCes.php',
			'parameters':{'caso':'generarInformeDesierto'},
			'queryString':queryString,
			'onSuccess': function(req)
							{
								respuesta = req.responseText;
								pagina = "odtphp/procesoCompra/declararDesierto"+respuesta+".odt";
								
								 for(i = 0; i< 20; i++)
									pagina = pagina.replace(" ","");        
	
	
								location.href = pagina;
								alert('Informe Generado');
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);
	
	
//	location.href = 'odtphp/procesoCompra/evaluacionCualitativaCuantitativa.php?codEvaluacion='+objCodActa.value;
	
}


function rellenarConCero(cadena, cantidadRelleno)
{
	cantidadCadena = cadena.length;
	
	
	for(i = 0; i < (cantidadRelleno-cantidadCadena); i++)
	{
			cadena = "0"+cadena;
		
	}			
	
	return cadena;
}

function buscarRecomendacion()
{
	var criterioBusqueda;
	var objCodigo = xGetElementById('codigoRecomendacionBusqueda');
	var queryString;
	
	if (objCodigo.value == '')
	{
		criterioBusqueda = 'todos';
		
	} else {
		
		criterioBusqueda = 'porCodigo';
	}
	
	queryString = 'criterioBusqueda='+criterioBusqueda+'&variableBusqueda='+objCodigo.value;
	
	
	
	AjaxRequest.post
			(
				{
					'url':'lib/controladorCes.php',
					'parameters':{'caso':'buscarRecomendacion'},
					'queryString':queryString,
					'onSuccess': function(req)
									{
										
										mostrarResultadoRecomendacionRevisar(req);

									},
					'onError': function(req)
							{ 
								alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
							}
				}
			);
}

function mostrarResultadoRecomendacionRevisar(req)
{
	var docXML = req.responseXML;
	var tempColumnaSubMenu = '';
	var cuerpoTablaBusqueda = xGetElementById('resultadoBusqueda');
	var objFila= xGetElementById('fila');
	var objEstiloFila= xGetElementById('estiloFila');
	var objCodOculto = xGetElementById('codigoRecomendacion');
	
	if (docXML.getElementsByTagName('resultado')[0].attributes[0].value > 0)
	{
		if(docXML.hasChildNodes())
		{
			
			
			objXML = docXML.getElementsByTagName('fila');
			
			eliminaHijo(cuerpoTablaBusqueda);
			
			for (var i=0; i < objXML.length; i++)
			{
				
			
				j = i+1;
				
				
				
				
				if (j < objXML.length)
				{
					if (objXML[i+1].attributes[6].value == objXML[i].attributes[6].value)
					{
						tempColumnaSubMenu += objXML[i].attributes[1].value+', ';
						
						
					} else {
					
						fila = crearObjeto('tr',{'id':'revisarRecomendacion'+i,'align':"center",'class':'trListaBody','onclick':'cambiarEstiloFilaClic(this.id,\'trListaBodySel\',\'trListaBody\',\'fila\',\'estiloFila\');asignarCodigo(\'codigoRecomendacion\',\''+objXML[i].attributes[6].value+'\')'},null,null);
						columnaId = crearObjeto('td',{},'0004-CPIR-'+rellenarConCero(objXML[i].attributes[4].value,3)+'-'+objXML[i].attributes[5].value,fila);
						columnaRequerimiento = crearObjeto('td',{},tempColumnaSubMenu+objXML[i].attributes[1].value,fila);
						tempColumnaSubMenu = '';
						
						asociaHijo(fila,cuerpoTablaBusqueda);
					}
				
				
			    } else {
			    	
			    	
			    	fila = crearObjeto('tr',{'id':'revisarRecomendacion'+i,'align':"center",'class':'trListaBody','onclick':'cambiarEstiloFilaClic(this.id,\'trListaBodySel\',\'trListaBody\',\'fila\',\'estiloFila\');asignarCodigo(\'codigoRecomendacion\',\''+objXML[i].attributes[6].value+'\')'},null,null);
			    	columnaId = crearObjeto('td',{},'0004-CPIR-'+rellenarConCero(objXML[i].attributes[4].value,3)+'-'+objXML[i].attributes[5].value,fila);
					//columnaId = crearObjeto('td',{},objXML[i].attributes[0].value,fila);
					columnaRequerimiento = crearObjeto('td',{},tempColumnaSubMenu+objXML[i].attributes[1].value,fila);
					tempColumnaSubMenu = '';
						
						asociaHijo(fila,cuerpoTablaBusqueda);
				    }
			    	
			   }
   
		    /*for (var i=0; i<objXML.length; i++)
		    {
		    	fila = crearObjeto('tr',{'id':'actaInicio'+i,'class':'trListaBody','onclick':'cambiarEstiloFilaClic(this.id,\'trListaBodySel\',\'trListaBody\',\'filaActividad\',\'estiloFila\');asignarCodigo(\'codigoActa\',\''+objXML[i].attributes[0].value+'\')'},null,null);
				columnaId = crearObjeto('td',{},objXML[i].attributes[0].value,fila);
				columnaRequerimiento = crearObjeto('td',{},objXML[i].attributes[1].value,fila);
				asociaHijo(fila,cuerpoTablaBusqueda);
				
		    }*/
		   
			xGetElementById('cantResultados').value = objXML.length;
			objFila.value = 0;
			objEstiloFila.value = 0;
			objCodOculto.value = 0;
			

		}
		
	} else {
		
		eliminaHijo(cuerpoTablaBusqueda);
		alert("No se encontraron Actas de Inicio");
		
	}
	

	
}


function imprimirInformeRecomendacion()
{
	var objCod = xGetElementById('codigoRecomendacion');
	
	if (objCod.value == 0)
	{
		alert('Seleccione un registro');
		return;
	}	
	
	var queryString = '&CodRecomendacion='+objCod.value;
	
	//location.href='odtphp/procesoCompra/informeAdjudicacion.php?'+queryString;
	
	//return;
	
	AjaxRequest.post
	(
		{
			'url':'lib/controladorCes.php',
			'parameters':{'caso':'generarInformeRecomendacion'},
			'queryString':queryString,
			'onSuccess': function(req)
							{
								respuesta = req.responseText;
								pagina = "odtphp/procesoCompra/informeRecomendacion"+respuesta+".odt";
								
								 for(i = 0; i< 20; i++)
									pagina = pagina.replace(" ","");        
	
	
								location.href = pagina;
								alert('Informe Generado');
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);
	
	
}

function imprimirInformeActaInicio()
{
	var objCod = xGetElementById('codigoRecomendacion');
	
	if (objCod.value == 0)
	{
		alert('Seleccione un registro');
		return;
	}	
	
	var queryString = '&CodRecomendacion='+objCod.value;
	
	//location.href='odtphp/procesoCompra/informeAdjudicacion.php?'+queryString;
	
	//return;
	
	AjaxRequest.post
	(
		{
			'url':'lib/controladorCes.php',
			'parameters':{'caso':'generarActaInicioRecomendacion'},
			'queryString':queryString,
			'onSuccess': function(req)
							{
								respuesta = req.responseText;
								pagina = "odtphp/procesoCompra/inicioCompra"+respuesta+".odt";
								
								 for(i = 0; i< 20; i++)
									pagina = pagina.replace(" ","");        
	
	
								location.href = pagina;
								alert('Acta Generada');
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);
}

function revisarRecomendacion()
{
	
	var objCod = xGetElementById('codigoRecomendacion');
	
	if (objCod.value == 0)
	{
		alert('Seleccione un registro');
		return;
	}
	
	cargarPagina(xGetElementById('fromActa'),'lg_revisar_recomendacion_form.php?codRecomendacion='+objCod.value);
}

function guardarRevision()
{
	var objCod = xGetElementById('codigoRecomendacion');
	var resp = window.confirm('¿Ha realizado la revisión correctamente?');
	var queryString = 'codRecomendacion='+objCod.value;
	
	if(resp == false)
	{
		location.href = 'lg_revisar_recomendacion.php?';
		return;
		
	}
	
	
	AjaxRequest.post
	(
		{
			'url':'lib/controladorCes.php',
			'parameters':{'caso':'guardarRevisionRecomendacion'},
			'queryString':queryString,
			'onSuccess': function(req)
							{
								respuesta = req.responseText;
								
								if(respuesta == '1')
								{
									alert('Revisión guardada con éxito');
									location.href = 'lg_revisar_recomendacion.php?';
										
								} else {
									
									alert('No se pudo guardar la revisión');
								}
								
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);
}

function buscarRecomendacionRevisada()
{
	var criterioBusqueda;
	var objCodigo = xGetElementById('codigoRecomendacionBusqueda');
	var queryString;
	
	if (objCodigo.value == '')
	{
		criterioBusqueda = 'todos';
		
	} else {
		
		criterioBusqueda = 'porCodigo';
	}
	
	queryString = 'criterioBusqueda='+criterioBusqueda+'&variableBusqueda='+objCodigo.value;
	
	
	
	AjaxRequest.post
			(
				{
					'url':'lib/controladorCes.php',
					'parameters':{'caso':'buscarRecomendacionRevisada'},
					'queryString':queryString,
					'onSuccess': function(req)
									{
										
										mostrarResultadoRecomendacionRevisada(req);

									},
					'onError': function(req)
							{ 
								alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
							}
				}
			);
}

function mostrarResultadoRecomendacionRevisada(req)
{
	var docXML = req.responseXML;
	var tempColumnaSubMenu = '';
	var cuerpoTablaBusqueda = xGetElementById('resultadoBusqueda');
	var objFila= xGetElementById('fila');
	var objEstiloFila= xGetElementById('estiloFila');
	var objCodOculto = xGetElementById('codigoRecomendacion');
	
	if (docXML.getElementsByTagName('resultado')[0].attributes[0].value > 0)
	{
		if(docXML.hasChildNodes())
		{
			
			
			objXML = docXML.getElementsByTagName('fila');
			
			eliminaHijo(cuerpoTablaBusqueda);
			
			for (var i=0; i < objXML.length; i++)
			{
				
			
				j = i+1;
				
				
				
				
				if (j < objXML.length)
				{
					if (objXML[i+1].attributes[6].value == objXML[i].attributes[6].value)
					{
						tempColumnaSubMenu += objXML[i].attributes[1].value+', ';
						
						
					} else {
					
						fila = crearObjeto('tr',{'id':'revisarRecomendacion'+i,'align':"center",'class':'trListaBody','onclick':'cambiarEstiloFilaClic(this.id,\'trListaBodySel\',\'trListaBody\',\'fila\',\'estiloFila\');asignarCodigo(\'codigoRecomendacion\',\''+objXML[i].attributes[6].value+'\')'},null,null);
						columnaId = crearObjeto('td',{},'0004-CPIR-'+rellenarConCero(objXML[i].attributes[4].value,3)+'-'+objXML[i].attributes[5].value,fila);
						columnaRequerimiento = crearObjeto('td',{},tempColumnaSubMenu+objXML[i].attributes[1].value,fila);
						tempColumnaSubMenu = '';
						
						asociaHijo(fila,cuerpoTablaBusqueda);
					}
				
				
			    } else {
			    	
			    	
			    	fila = crearObjeto('tr',{'id':'revisarRecomendacion'+i,'align':"center",'class':'trListaBody','onclick':'cambiarEstiloFilaClic(this.id,\'trListaBodySel\',\'trListaBody\',\'fila\',\'estiloFila\');asignarCodigo(\'codigoRecomendacion\',\''+objXML[i].attributes[6].value+'\')'},null,null);
			    	columnaId = crearObjeto('td',{},'0004-CPIR-'+rellenarConCero(objXML[i].attributes[4].value,3)+'-'+objXML[i].attributes[5].value,fila);
					//columnaId = crearObjeto('td',{},objXML[i].attributes[0].value,fila);
					columnaRequerimiento = crearObjeto('td',{},tempColumnaSubMenu+objXML[i].attributes[1].value,fila);
					tempColumnaSubMenu = '';
						
						asociaHijo(fila,cuerpoTablaBusqueda);
				    }
			    	
			   }
   
		    /*for (var i=0; i<objXML.length; i++)
		    {
		    	fila = crearObjeto('tr',{'id':'actaInicio'+i,'class':'trListaBody','onclick':'cambiarEstiloFilaClic(this.id,\'trListaBodySel\',\'trListaBody\',\'filaActividad\',\'estiloFila\');asignarCodigo(\'codigoActa\',\''+objXML[i].attributes[0].value+'\')'},null,null);
				columnaId = crearObjeto('td',{},objXML[i].attributes[0].value,fila);
				columnaRequerimiento = crearObjeto('td',{},objXML[i].attributes[1].value,fila);
				asociaHijo(fila,cuerpoTablaBusqueda);
				
		    }*/
		   
			xGetElementById('cantResultados').value = objXML.length;
			objFila.value = 0;
			objEstiloFila.value = 0;
			objCodOculto.value = 0;
			

		}
		
	} else {
		
		eliminaHijo(cuerpoTablaBusqueda);
		alert("No se encontraron Recomendaciones revisadas");
		
	}
	

	
}

function aprobarRecomendacion()
{
	
	var objCod = xGetElementById('codigoRecomendacion');
	
	if (objCod.value == 0)
	{
		alert('Seleccione un registro');
		return;
	}
	
	cargarPagina(xGetElementById('fromActa'),'lg_aprobar_recomendacion_form.php?codRecomendacion='+objCod.value);
}

function guardarAprobacion()
{
	var objCod = xGetElementById('codigoRecomendacion');
	var resp = window.confirm('¿Esta seguro de aprobar esta recomendación?');
	var queryString = 'codRecomendacion='+objCod.value;
	
	if(resp == false)
	{
		location.href = 'lg_aprobar_recomendacion.php?';
		return;
		
	}
	
	
	AjaxRequest.post
	(
		{
			'url':'lib/controladorCes.php',
			'parameters':{'caso':'guardarAprobacionRecomendacion'},
			'queryString':queryString,
			'onSuccess': function(req)
							{
								respuesta = req.responseText;
								
								if(respuesta == '1')
								{
									alert('Aprobación guardada');
									location.href = 'lg_aprobar_recomendacion.php?';
										
								} else {
									
									alert('No se pudo guardar la aprobación');
								}
								
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);
}

function buscarInformeRecomendacionParaAdjudicacion()
{
	var criterioBusqueda;
	var objCodigoActaBusqueda = xGetElementById('codigoActaBusqueda');
	var queryString;
	
	if (objCodigoActaBusqueda.value == '')
	{
		criterioBusqueda = 'todos';
		
	} else {
		
		criterioBusqueda = 'porNombre';
	}
	
	queryString = 'criterioBusqueda='+criterioBusqueda+'&variableBusqueda='+objCodigoActaBusqueda.value;
	
	
	
	AjaxRequest.post
			(
				{
					'url':'lib/controladorCes.php',
					'parameters':{'caso':'buscarInformeRecomendacionParaAdjudicacion'},
					'queryString':queryString,
					'onSuccess': function(req)
									{
										
										mostrarResultadoInformeRecomendacionParaAdjudicacion(req);

									},
					'onError': function(req)
							{ 
								alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
							}
				}
			);
}


function mostrarResultadoInformeRecomendacionParaAdjudicacion(req)
{
	var docXML = req.responseXML;
	var tempColumnaSubMenu = '';
	var cuerpoTablaBusqueda = xGetElementById('resultadoBusquedaActa');
	var objFila= xGetElementById('fila');
	var objEstiloFila= xGetElementById('estiloFila');
	var objCodRecomendacion = xGetElementById('codigoActa');
	
	if (docXML.getElementsByTagName('resultado')[0].attributes[0].value > 0)
	{
		if(docXML.hasChildNodes())
		{
			
			
			objXML = docXML.getElementsByTagName('fila');
			
			eliminaHijo(cuerpoTablaBusqueda);
			
			for (var i=0; i < objXML.length; i++)
			{
				
			
				fila = crearObjeto('tr',{'id':'informeRecomendacion'+i,'align':"center",'class':'trListaBody','onclick':'cambiarEstiloFilaClic(this.id,\'trListaBodySel\',\'trListaBody\',\'fila\',\'estiloFila\');asignarCodigo(\'codigoActa\',\''+objXML[i].attributes[0].value+'\')'},null,null);
					//columnaId = crearObjeto('td',{},objXML[i].attributes[0].value,fila);
					columnaId = crearObjeto('td',{},'0004-CPIR-'+rellenarConCero(objXML[i].attributes[5].value,3)+'-'+objXML[i].attributes[6].value,fila);
					columna = crearObjeto('td',{},objXML[i].attributes[1].value,fila);
					columna = crearObjeto('td',{},objXML[i].attributes[2].value,fila);
					columna = crearObjeto('td',{},objXML[i].attributes[4].value,fila);
				
					
					asociaHijo(fila,cuerpoTablaBusqueda);
							
					xGetElementById('cantResultados').value = objXML.length;
					objFila.value = 0;
					objEstiloFila.value = 0;
					objCodRecomendacion.value = 0;
				
			

			}
		}
		
	} else {
		
		eliminaHijo(cuerpoTablaBusqueda);
		alert("No se encontraron Informes de Recomendación");
		
	}
	

	
}

function buscarRecomendacionRealizada()
{
	var criterioBusqueda;
	var objCodigo = xGetElementById('codigoRecomendacionBusqueda');
	var queryString;
	
	if (objCodigo.value == '')
	{
		criterioBusqueda = 'todos';
		
	} else {
		
		criterioBusqueda = 'porCodigo';
	}
	
	queryString = 'criterioBusqueda='+criterioBusqueda+'&variableBusqueda='+objCodigo.value;
	
	
	
	AjaxRequest.post
			(
				{
					'url':'lib/controladorCes.php',
					'parameters':{'caso':'buscarRecomendacionRealizada'},
					'queryString':queryString,
					'onSuccess': function(req)
									{
										
										mostrarResultadoRecomendacionRealizada(req);

									},
					'onError': function(req)
							{ 
								alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
							}
				}
			);
}

function mostrarResultadoRecomendacionRealizada(req)
{
	var docXML = req.responseXML;
	var tempColumnaSubMenu = '';
	var cuerpoTablaBusqueda = xGetElementById('resultadoBusqueda');
	var objFila= xGetElementById('fila');
	var objEstiloFila= xGetElementById('estiloFila');
	var objCodOculto = xGetElementById('codigoRecomendacion');
	
	if (docXML.getElementsByTagName('resultado')[0].attributes[0].value > 0)
	{
		if(docXML.hasChildNodes())
		{
			
			
			objXML = docXML.getElementsByTagName('fila');
			
			eliminaHijo(cuerpoTablaBusqueda);
			
			for (var i=0; i < objXML.length; i++)
			{
				
			
				j = i+1;
				
				
				
				
				if (j < objXML.length)
				{
					if (objXML[i+1].attributes[6].value == objXML[i].attributes[6].value)
					{
						tempColumnaSubMenu += objXML[i].attributes[1].value+', ';
						
						
					} else {
					
						fila = crearObjeto('tr',{'id':'revisarRecomendacion'+i,'align':"center",'class':'trListaBody','onclick':'cambiarEstiloFilaClic(this.id,\'trListaBodySel\',\'trListaBody\',\'fila\',\'estiloFila\');asignarCodigo(\'codigoRecomendacion\',\''+objXML[i].attributes[6].value+'\')'},null,null);
						columnaId = crearObjeto('td',{},'0004-CPIR-'+rellenarConCero(objXML[i].attributes[4].value,3)+'-'+objXML[i].attributes[5].value,fila);
						columnaRequerimiento = crearObjeto('td',{},tempColumnaSubMenu+objXML[i].attributes[1].value,fila);
						tempColumnaSubMenu = '';
						
						asociaHijo(fila,cuerpoTablaBusqueda);
					}
				
				
			    } else {
			    	
			    	
			    	fila = crearObjeto('tr',{'id':'revisarRecomendacion'+i,'align':"center",'class':'trListaBody','onclick':'cambiarEstiloFilaClic(this.id,\'trListaBodySel\',\'trListaBody\',\'fila\',\'estiloFila\');asignarCodigo(\'codigoRecomendacion\',\''+objXML[i].attributes[6].value+'\')'},null,null);
			    	columnaId = crearObjeto('td',{},'0004-CPIR-'+rellenarConCero(objXML[i].attributes[4].value,3)+'-'+objXML[i].attributes[5].value,fila);
					//columnaId = crearObjeto('td',{},objXML[i].attributes[0].value,fila);
					columnaRequerimiento = crearObjeto('td',{},tempColumnaSubMenu+objXML[i].attributes[1].value,fila);
					tempColumnaSubMenu = '';
						
						asociaHijo(fila,cuerpoTablaBusqueda);
				    }
			    	
			   }
   
		    /*for (var i=0; i<objXML.length; i++)
		    {
		    	fila = crearObjeto('tr',{'id':'actaInicio'+i,'class':'trListaBody','onclick':'cambiarEstiloFilaClic(this.id,\'trListaBodySel\',\'trListaBody\',\'filaActividad\',\'estiloFila\');asignarCodigo(\'codigoActa\',\''+objXML[i].attributes[0].value+'\')'},null,null);
				columnaId = crearObjeto('td',{},objXML[i].attributes[0].value,fila);
				columnaRequerimiento = crearObjeto('td',{},objXML[i].attributes[1].value,fila);
				asociaHijo(fila,cuerpoTablaBusqueda);
				
		    }*/
		   
			xGetElementById('cantResultados').value = objXML.length;
			objFila.value = 0;
			objEstiloFila.value = 0;
			objCodOculto.value = 0;
			

		}
		
	} else {
		
		eliminaHijo(cuerpoTablaBusqueda);
		alert("No se encontraron Recomendaciones revisadas");
		
	}
	

	
}
