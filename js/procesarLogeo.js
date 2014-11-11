/****************************************************************************************
* DEV: CONTRALORIA DEL ESTADO SUCRE - VENEZUELA
* PROYECTO: SIACEDA
* OPERADORES_____________________________________________________________________________________________________________________________
* | # | PROGRAMADOR          |  |   FECHA    |  |   HORA   |   CELULAR  |   VERSION PAG  | DESCRIPCION DEL CAMBIO 
* | 2 | Christian Hernandez   |  | 22/04/2012 |  | 02:16:18 | 04128354891|      0.1.1.A   | creacion del script
* |________________________________________________________|_____________________________________________________________________________
* TIPO: JS
* DESCRIPCION: 
* UBICACION: VENEZUELA, SUCRE, CUMANA
* VERSION: 0.1.1.A 
* SOPORTE: Christian Hernandez 
* CONTACTO: www.cgesucre.gob.ve, @CESucre, contraloria.estado.sucre@cgesucre.gob.ve
*******************************************************************************************/
var totalAlerta = 0;//almacena la cantidad de alerta (sumatoria de todas las alertas de  los modulo
var correoEnviado = "no";
var banderaListaAlerta = '';

function iniciarSesion()
{

	
	var campoUsuario = xGetElementById('accesoUsuario');
	var campoClave = xGetElementById('accesoClave');
	

	
	
	var queryString;
	
	if(campoUsuario.value == '')
	{
		alert('Ingrese su Usuario');
		return;	
	}
	
	if(campoClave.value == '')
	{
		alert('Ingrese su contraseña');
		return;	
	}
	
	var queryString = 'accesoUsuario='+campoUsuario.value+'&accesoClave='+campoClave.value;
	
  var auxliar='';
	     
	AjaxRequest.post
	(
		{
			'url':'lib/controladorLogeo.php',
			'parameters':{'caso':'iniciarSesion'},
			'queryString':queryString,
			'onSuccess': function(req)
							{

								var docXML = req.responseXML;
	                            
	                                                   
								
								if (docXML.getElementsByTagName('resultado')[0].attributes[0].value > 0)
								{
									if(docXML.hasChildNodes())
									{
										objXML = docXML.getElementsByTagName('fila');
										


										if(objXML[0].attributes[0].value == 1)
										{
											//window.open('index.php','','');// 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=1024, width=1280, left=0, top=0, resizable=yes'
											location.href = "menuModulo.php";

											
										} else if(objXML[0].attributes[0].value == 0){
											
											alert("Usuario en estado inactivo");
											return;
											
										} else if(objXML[0].attributes[0].value == 2){
											
											alert("Usuario o Clave incorrecto");
											return;
										} 
										
									}
									
									
								} else {
									
									alert("Sin respuesta");
								}
								
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);

}

function cerrarSesion()
{
	
	AjaxRequest.post
	(
		{
			'url':'lib/controladorLogeo.php',
			'parameters':{'caso':'cerrarSesion'},
			'queryString':'',
			'onSuccess': function(req)
							{

								var docXML = req.responseXML;
	
	
								if (docXML.getElementsByTagName('resultado')[0].attributes[0].value > 0)
								{
									if(docXML.hasChildNodes())
									{
										objXML = docXML.getElementsByTagName('fila');
										
										if(objXML[0].attributes[0].value == 1)
										{
											location.href = "index.php";
											
										} else if(objXML[0].attributes[0].value == 0){
											
											alert('No se pudo cerrar sesión');
											return;
											
										}
										
									}
									
									
								} else {
									
									alert("Sin respuesta");
								}
								
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);
	
	
}

function cargarAlerta(llamado)
{
	
	
	
	
	if (llamado == 'inicioPagina')
	{
		
		consularAlertaRRHH();
		consularAlertaPresupuesto();
		consularAlertaNomina();
		consularAlertaCuentasPP();
		consularAlertaActivoFijo();
		consularAlertaControlDocumento();
		consularAlertaPlanificacionFiscal();
		consularAlertaLogistica();
		
		
	} else {
		
		var tiempoActualizacion = 500000;
		var tiempoActualizacion2 = 60000;
		
		setInterval('consularAlertaRRHH()',tiempoActualizacion);
		setInterval('consularAlertaPresupuesto()',tiempoActualizacion);
		setInterval('consularAlertaNomina()',tiempoActualizacion);
		//setInterval('consularAlertaCuentasPP()',tiempoActualizacion);
		setInterval('consularAlertaActivoFijo()',tiempoActualizacion);
		//setInterval('consularAlertaControlDocumento()',tiempoActualizacion);
		//setInterval('consularAlertaPlanificacionFiscal()',tiempoActualizacion);
		//setInterval('consularAlertaLogistica()',tiempoActualizacion);


		setInterval('consularAlertaCuentasPP()',tiempoActualizacion2);
		setInterval('consularAlertaLogistica()',tiempoActualizacion2);
		setInterval('consularAlertaControlDocumento()',tiempoActualizacion2);
		setInterval('consularAlertaPlanificacionFiscal()',tiempoActualizacion2);
		
	}
	
	
	
}
		
function consularAlertaRRHH()
{
	var criterioBusqueda;
	
	var queryString;
	
	eliminarNodo('iconRRHH0');
									
	AjaxRequest.post
	(
		{
			'url':'lib/controladorLogeo.php',
			'parameters':{'caso':'alertaRRHH','banderaListaAlerta':banderaListaAlerta},
			'queryString':'',
			'onSuccess': function(req)
							{

								var docXML = req.responseXML;
	
	
								if (docXML.getElementsByTagName('resultado')[0].attributes[0].value > 0)
								{
									if(docXML.hasChildNodes())
									{
										objXML = docXML.getElementsByTagName('fila');
										
										
										if((objXML.length > 0) && (objXML[0].attributes[2].value > 0))
										{
											objRRHH =  $('#iconRRHH').gips({ 'theme': 'red', autoHide: false, text: '',idGlobo:'iconRRHH',placement:'top'});
											
											tabla='<table width="auto" border="0"><tr>';
											
											for(i = 0; i < objXML.length; i++)
											{
												if(objXML[i].attributes[0].value > 0)
												{
													tabla+='<td width="15" align="center" ><a id="altRRHH'+i+'" onmouseover="crearAlt(\''+objXML[i].attributes[1].value+'\',\'azul\')" onmouseout="hide1();" style="color:#FFFFFF;font-size:11px;">'+objXML[i].attributes[0].value+'&nbsp;</a></td>';
												}
												
											}
											
											tabla+='</tr></table>';
											verificarEnvioCorreo(objXML[0].attributes[2].value);
											
											mostrarGlobo('iconRRHH',tabla);
											
										} else //if(objXML[0].attributes[0].value == 0)
										{
											
											//ocultarGlobo('iconRRHH');
											return;
											
										}
										
									}
									
									
								} else {
									
									alert("Sin respuesta");
								}
								
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);

									
}

function consularAlertaPresupuesto()
{
	var criterioBusqueda;
	
	var queryString;
	
	
	//queryString = 'criterioBusqueda='+criterioBusqueda+'&variableBusqueda='+objCodigoActaBusqueda.value;
	eliminarNodo('iconPresupuesto0');
									
	AjaxRequest.post
	(
		{
			'url':'lib/controladorLogeo.php',
			'parameters':{'caso':'alertaPresupuesto','banderaListaAlerta':banderaListaAlerta},
			'queryString':'',
			'onSuccess': function(req)
							{

								var docXML = req.responseXML;
	
	
								if (docXML.getElementsByTagName('resultado')[0].attributes[0].value > 0)
								{
									if(docXML.hasChildNodes())
									{
										objXML = docXML.getElementsByTagName('fila');
										
										
										if((objXML.length > 0) && (objXML[0].attributes[2].value > 0))
										{
											objPresupuesto =  $('#iconPresupuesto').gips({ 'theme': 'red', autoHide: false, text: '',idGlobo:'iconPresupuesto',placement:'top'});
											tabla='<table width="auto" border="0"><tr>';
											
											for(i = 0; i < objXML.length; i++)
											{
												if(objXML[i].attributes[0].value > 0)
												{
													tabla+='<td width="15" align="center" ><a id="altPresupuesto'+i+'" onmouseover="crearAlt(\''+objXML[i].attributes[1].value+'\',\'azul\')" onmouseout="hide1();" style="color:#FFFFFF;font-size:11px;">'+objXML[i].attributes[0].value+'&nbsp;</a></td>';
												}
												
											}
											
											tabla+='</tr></table>';
											verificarEnvioCorreo(objXML[0].attributes[2].value);
											
											mostrarGlobo('iconPresupuesto',tabla);
											
										} else //if(objXML[0].attributes[0].value == 0)
										{
											
											//ocultarGlobo('iconPresupuesto');
											return;
											
										}
										
									}
									
									
								} else {
									
									alert("Sin respuesta");
								}
								
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);

									
}

function consularAlertaNomina()
{
	var criterioBusqueda;
	
	var queryString;
	
	
	//queryString = 'criterioBusqueda='+criterioBusqueda+'&variableBusqueda='+objCodigoActaBusqueda.value;
	eliminarNodo('iconNomina0');
										
	AjaxRequest.post
	(
		{
			'url':'lib/controladorLogeo.php',
			'parameters':{'caso':'alertaNomina','banderaListaAlerta':banderaListaAlerta},
			'queryString':'',
			'onSuccess': function(req)
							{

								var docXML = req.responseXML;
	
	
								if (docXML.getElementsByTagName('resultado')[0].attributes[0].value > 0)
								{
									if(docXML.hasChildNodes())
									{
										objXML = docXML.getElementsByTagName('fila');
										
										
										if((objXML.length > 0) && (objXML[0].attributes[2].value > 0))
										{
											objNomina =  $('#iconNomina').gips({ 'theme': 'red', autoHide: false, text: '',idGlobo:'iconNomina',placement:'top'});
											tabla='<table width="auto" border="0"><tr>';
											
											for(i = 0; i < objXML.length; i++)
											{
												if(objXML[i].attributes[0].value > 0)
												{
													tabla+='<td width="15" align="center" ><a id="altNomina'+i+'" onmouseover="crearAlt(\''+objXML[i].attributes[1].value+'\',\'azul\')" onmouseout="hide1();" style="color:#FFFFFF;font-size:11px;">'+objXML[i].attributes[0].value+'&nbsp;</a></td>';
												}
												
											}
											
											tabla+='</tr></table>';
											verificarEnvioCorreo(objXML[0].attributes[2].value);
											
											mostrarGlobo('iconNomina',tabla);
											
										} else //if(objXML[0].attributes[0].value == 0)
										{
											
											//ocultarGlobo('iconPresupuesto');
											return;
											
										}
										
									}
									
									
								} else {
									
									alert("Sin respuesta");
								}
								
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);

									
}

function consularAlertaCuentasPP()
{
	var criterioBusqueda;
	
	var queryString;
	
	
	//queryString = 'criterioBusqueda='+criterioBusqueda+'&variableBusqueda='+objCodigoActaBusqueda.value;
	eliminarNodo('iconCPP0');
										
	AjaxRequest.post
	(
		{
			'url':'lib/controladorLogeo.php',
				'parameters':{'caso':'alertaCuentasporPagar','banderaListaAlerta':banderaListaAlerta},
			'queryString':'',
			'onSuccess': function(req)
							{

								var docXML = req.responseXML;
	
	
								if (docXML.getElementsByTagName('resultado')[0].attributes[0].value > 0)
								{
									if(docXML.hasChildNodes())
									{
										objXML = docXML.getElementsByTagName('fila');
										
										
										if((objXML.length > 0) && (objXML[0].attributes[2].value > 0))
										{
											objCPP =  $('#iconCPP').gips({ 'theme': 'red', autoHide: false, text: '',idGlobo:'iconCPP',placement:'top'});
											tabla='<table width="auto" border="0"><tr>';
											
											for(i = 0; i < objXML.length; i++)
											{
												if(objXML[i].attributes[0].value > 0)
												{
													tabla+='<td width="15" align="center" ><a id="altCPP'+i+'" onmouseover="crearAlt(\''+objXML[i].attributes[1].value+'\',\'azul\')" onmouseout="hide1();" style="color:#FFFFFF;font-size:11px;">'+objXML[i].attributes[0].value+'&nbsp;</a></td>';
												}
												
											}
											
											tabla+='</tr></table>';
											verificarEnvioCorreo(objXML[0].attributes[2].value);
											
											mostrarGlobo('iconCPP',tabla);
											
										} else //if(objXML[0].attributes[0].value == 0)
										{
											
											//ocultarGlobo('iconPresupuesto');
											return;
											
										}
										
									}
									
									
								} else {
									
									alert("Sin respuesta");
								}
								
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);

									
}

function consularAlertaActivoFijo()
{
	var criterioBusqueda;
	
	var queryString;
	
	
	//queryString = 'criterioBusqueda='+criterioBusqueda+'&variableBusqueda='+objCodigoActaBusqueda.value;
	eliminarNodo('iconActivoFijo0');
										
	AjaxRequest.post
	(
		{
			'url':'lib/controladorLogeo.php',
			'parameters':{'caso':'alertaActivoFijo','banderaListaAlerta':banderaListaAlerta},
			'queryString':'',
			'onSuccess': function(req)
							{

								var docXML = req.responseXML;
	
	
								if (docXML.getElementsByTagName('resultado')[0].attributes[0].value > 0)
								{
									if(docXML.hasChildNodes())
									{
										objXML = docXML.getElementsByTagName('fila');
										
										
										if((objXML.length > 0) && (objXML[0].attributes[2].value > 0))
										{
											objActivoFijo =  $('#iconActivoFijo').gips({ 'theme': 'red', autoHide: false, text: '',idGlobo:'iconActivoFijo',placement:'top'});
											tabla='<table width="auto" border="0"><tr>';
											
											for(i = 0; i < objXML.length; i++)
											{
												if(objXML[i].attributes[0].value > 0)
												{
													tabla+='<td width="15" align="center" ><a id="altActivoFijo'+i+'" onmouseover="crearAlt(\''+objXML[i].attributes[1].value+'\',\'azul\')" onmouseout="hide1();" style="color:#FFFFFF;font-size:11px;">'+objXML[i].attributes[0].value+'&nbsp;</a></td>';
												}
												
											}
											
											tabla+='</tr></table>';
											verificarEnvioCorreo(objXML[0].attributes[2].value);
											
											mostrarGlobo('iconActivoFijo',tabla);
											
										} else //if(objXML[0].attributes[0].value == 0)
										{
											
											//ocultarGlobo('iconPresupuesto');
											return;
											
										}
										
									}
									
									
								} else {
									
									alert("Sin respuesta");
								}
								
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);

									
}

function consularAlertaControlDocumento()
{
	var criterioBusqueda;
	
	var queryString;
	
	
	//queryString = 'criterioBusqueda='+criterioBusqueda+'&variableBusqueda='+objCodigoActaBusqueda.value;
	eliminarNodo('iconCD0');
										
	AjaxRequest.post
	(
		{
			'url':'lib/controladorLogeo.php',
			'parameters':{'caso':'alertaControlDocumento','banderaListaAlerta':banderaListaAlerta},
			'queryString':'',
			'onSuccess': function(req)
							{

								var docXML = req.responseXML;
	
	
								if (docXML.getElementsByTagName('resultado')[0].attributes[0].value > 0)
								{
									if(docXML.hasChildNodes())
									{
										objXML = docXML.getElementsByTagName('fila');
										
										
										if((objXML.length > 0) && (objXML[0].attributes[2].value > 0))
										{
											objControlDocumetno =  $('#iconCD').gips({ 'theme': 'red', autoHide: false, text: '',idGlobo:'iconCD',placement:'top'});
											tabla='<table width="auto" border="0"><tr>';
											
											for(i = 0; i < objXML.length; i++)
											{
												if(objXML[i].attributes[0].value > 0)
												{
													tabla+='<td width="15" align="center" ><a id="altCD'+i+'" onmouseover="crearAlt(\''+objXML[i].attributes[1].value+'\',\'azul\')" onmouseout="hide1();" style="color:#FFFFFF;font-size:11px;">'+objXML[i].attributes[0].value+'&nbsp;</a></td>';
												}
												
											}
											
											tabla+='</tr></table>';
											verificarEnvioCorreo(objXML[0].attributes[2].value);
											
											mostrarGlobo('iconCD',tabla);
											
										} else //if(objXML[0].attributes[0].value == 0)
										{
											
											//ocultarGlobo('iconPresupuesto');
											return;
											
										}
										
									}
									
									
								} else {
									
									alert("Sin respuesta");
								}
								
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);

									
}

function consularAlertaPlanificacionFiscal()
{
	var criterioBusqueda;
	
	var queryString;
	
	eliminarNodo('iconPF0');
									
	AjaxRequest.post
	(
		{
			'url':'lib/controladorLogeo.php',
			'parameters':{'caso':'alertaPlanificacionFiscal','banderaListaAlerta':banderaListaAlerta},
			'queryString':'',
			'onSuccess': function(req)
							{

								var docXML = req.responseXML;
	
	
								if (docXML.getElementsByTagName('resultado')[0].attributes[0].value > 0)
								{
									if(docXML.hasChildNodes())
									{
										objXML = docXML.getElementsByTagName('fila');
										
										
										if((objXML.length > 0) && (objXML[0].attributes[2].value > 0))
										{
											objPlanificacionFiscal =  $('#iconPF').gips({ 'theme': 'red', autoHide: false, text: '',idGlobo:'iconPF',placement:'top'});
											
											tabla='<table width="auto" border="0"><tr>';	
											
											for(i = 0; i < objXML.length; i++)
											{
												if(objXML[i].attributes[0].value > 0)
												{
													tabla+='<td width="15" align="center" ><a id="altPF'+i+'" onmouseover="crearAlt(\''+objXML[i].attributes[1].value+'\',\'azul\')" onmouseout="hide1();" style="color:#FFFFFF;font-size:11px;">'+objXML[i].attributes[0].value+'&nbsp;</a></td>';
												}
												
											}
											
											tabla+='</tr></table>';
											verificarEnvioCorreo(objXML[0].attributes[2].value);
											
											mostrarGlobo('iconPF',tabla);
											
										} else //if(objXML[0].attributes[0].value == 0)
										{
											
											//ocultarGlobo('iconRRHH');
											return;
											
										}
										
									}
									
									
								} else {
									
									alert("Sin respuesta");
								}
								
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);

									
}

function consularAlertaLogistica()
{
	var criterioBusqueda;
	
	var queryString;
	
	
	//queryString = 'criterioBusqueda='+criterioBusqueda+'&variableBusqueda='+objCodigoActaBusqueda.value;
	eliminarNodo('iconLogistica0');
										
	AjaxRequest.post
	(
		{
			'url':'lib/controladorLogeo.php',
				'parameters':{'caso':'alertaLogistica','banderaListaAlerta':banderaListaAlerta},
			'queryString':'',
			'queryString':'',
			'onSuccess': function(req)
							{

								var docXML = req.responseXML;
	
	
								if (docXML.getElementsByTagName('resultado')[0].attributes[0].value > 0)
								{
									if(docXML.hasChildNodes())
									{
										objXML = docXML.getElementsByTagName('fila');
										
										
										if((objXML.length > 0) && (objXML[0].attributes[2].value > 0))
										{
											objLG =  $('#iconLogistica').gips({ 'theme': 'red', autoHide: false, text: '',idGlobo:'iconLogistica',placement:'top'});
											tabla='<table width="auto" border="0"><tr>';
											
											for(i = 0; i < objXML.length; i++)
											{
												if(objXML[i].attributes[0].value > 0)
												{
													tabla+='<td width="15" align="center" ><a id="altLogistica'+i+'" onmouseover="crearAlt(\''+objXML[i].attributes[1].value+'\',\'azul\')" onmouseout="hide1();" style="color:#FFFFFF;font-size:11px;">'+objXML[i].attributes[0].value+'&nbsp;</a></td>';
												}
												
												if(i == 6)
												{
													//tabla+='</tr><tr>';
												}
												
											}
											
											tabla+='</tr></table>';
											verificarEnvioCorreo(objXML[0].attributes[2].value);
											
											mostrarGlobo('iconLogistica',tabla);
											
										} else //if(objXML[0].attributes[0].value == 0)
										{
											
											//ocultarGlobo('iconPresupuesto');
											return;
											
										}
										
									}
									
									
								} else {
									
									alert("Sin respuesta");
								}
								
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);

									
}

function verificarEnvioCorreo(totalModulo)
{
	totalAlerta += totalModulo;
	var queryString;
	
	if((totalAlerta < 5) || (correoEnviado == "si"))
	{
		return;
		
	} else {
		
		correoEnviado = "si";
		
	}
	
	AjaxRequest.post
	(
		{
			'url':'lib/controladorLogeo.php',
			'parameters':{'caso':'enviarCorreoAlerta'},
			'queryString':'',
			'onSuccess': function(req)
							{

								var docXML = req.responseXML;
	
	
								/*if (docXML.getElementsByTagName('resultado')[0].attributes[0].value > 0)
								{
									if(docXML.hasChildNodes())
									{
										
										
										objXML = docXML.getElementsByTagName('fila');
									
									}
									
								} else {
									
									//alert("Sin respuesta");
								}*/
								
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);	
}


//EJEMPLO DEL LLAMADO EN CASO DE QUE SE QUIERA ENVIAR PARAMETROS A LA FUNCION:
//llamadoGenericoEvento(event,'verificarLoginPassword',this.id,Array('\''+this.id+'\'',5,'\'hola\''))
function llamadoGenericoEvento(event,nomFuncion,idCampotexto,parametros)
{
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
	var cadena = '';
	
	if (parametros != null)	
	{
		cadena = implode(parametros,',');
	}
	
	if (keyCode == 13) 
	{
		xGetElementById(idCampotexto).blur();
		eval(nomFuncion+'('+cadena+');');
	}
}

//-----------------------------------------------------------
function mostrarAlerta()
{
	//alert('ALERTA');
	
	var valorListaAlerta = devolverValorLista('listaAlerta');
	
	if(valorListaAlerta == '-1')
	{
		banderaListaAlerta = '';
		
	} else {
		
		banderaListaAlerta = valorListaAlerta; 
	}
	
	//alert("BANDERA:"+valorListaAlerta);
	cargarAlerta('inicioPagina');
	cargarAlerta('');
}
//---------------------------------------------------------
//---------------------------------------------------------

function cargarListaAlerta()
{
	
	
	AjaxRequest.post
	(
		{
			'url':'lib/controladorLogeo.php',
			'parameters':{'caso':'cargarListaAlerta','banderaListaAlerta':banderaListaAlerta},
			'queryString':'',
			'onSuccess': function(req)
							{

								var docXML = req.responseText;
	
	
								if (docXML != '')
								{
									xGetElementById('columnaAlerta').innerHTML = docXML; 
									
								} 
								
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);	
	
}
