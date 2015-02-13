/****************************************************************************************
* DEV: CONTRALORIA DEL ESTADO SUCRE - VENEZUELA
* PROYECTO: SAICOM
* OPERADORES_____________________________________________________________________________________________________________________________
* | # | PROGRAMADOR          |  |   FECHA    |  |   HORA   |   CELULAR  |   VERSION PAG  | DESCRIPCION DEL CAMBIO 
* | 2 | Christian Hernandez   |  | 02/09/2013 |  | 02:16:18 | 04128354891|      0.1.1.A   | creacion del script
* |________________________________________________________|_____________________________________________________________________________
* TIPO: JS
* DESCRIPCION: 
* UBICACION: VENEZUELA, SUCRE, CUMANA
* VERSION: 0.1.1.A 
* SOPORTE: Christian Hernandez 
* CONTACTO: www.cgesucre.gob.ve, @CESucre,
*******************************************************************************************/


function buscarMaestroUtiles()
{
	var campoNumMaestroUtiles = xGetElementById('numMaestroUtiles');
	var cuerpoTablaBusqueda = xGetElementById('resultadoBusqueda');
	var objFila= xGetElementById('fila');
	var objEstiloFila= xGetElementById('estiloFila');
	var objCod = xGetElementById('codigoMaestroUtiles');
	var queryString;
	

	var queryString = 'numMaestroUtiles='+campoNumMaestroUtiles.value;
	
	
	AjaxRequest.post
	(
		{
			'url':'lib/controladorCes.php',
			'parameters':{'caso':'buscarMaestroUtiles'},
			'queryString':queryString,
			'onSuccess': function(req)
					{
						var docXML = req.responseXML;

						if (docXML.getElementsByTagName('resultado')[0].attributes[0].value > 0)
						{
							if(docXML.hasChildNodes())
							{
			
			
								objXML = docXML.getElementsByTagName('fila');
			
								eliminaHijo(cuerpoTablaBusqueda);
			
									for (var i=0; i < objXML.length; i++)
									{
					
				
										fila = crearObjeto('tr',{'id':'maestroUtiles'+i,'align':"center",'class':'trListaBody','onclick':'cambiarEstiloFilaClic(this.id,\'trListaBodySel\',\'trListaBody\',\'fila\',\'estiloFila\');asignarCodigo(\'codigoMaestroUtiles\',\''+objXML[i].attributes[0].value+'\')'},null,null);
										columnaUno = crearObjeto('td',{},objXML[i].attributes[1].value,fila);
										columnaDos = crearObjeto('td',{},objXML[i].attributes[2].value,fila);
										columnaTres = crearObjeto('td',{},objXML[i].attributes[3].value,fila);
										columnaCuatro = crearObjeto('td',{},toCurrency(objXML[i].attributes[4].value),fila);
										
				
					
										asociaHijo(fila,cuerpoTablaBusqueda);
							
										xGetElementById('cantResultados').value = objXML.length;
										objFila.value = 0;
										objEstiloFila.value = 0;
										objCod.value = 0;
				
	
								}
		
							}
		
						} else {
		
							eliminaHijo(cuerpoTablaBusqueda);
							alert("No se encontraron registros");
		
						}
					
					},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);

}

function guardarMaestroUtiles()
{
	
	
	
	objNumBeneficio = xGetElementById('numBeneficio');
	objDescripcionUtiles = xGetElementById('descripcionUtiles');
	objPeriodoUtiles = xGetElementById('periodoUtiles');
	objMontoUtiles = xGetElementById('montoUtiles');
	
	
	var queryString;
	
	if(objNumBeneficio.value == '')
	{
		
		alert('Introduzca el número del beneficio de útiles');
		return;
	}
	
	if(objDescripcionUtiles.value == '')
	{
		
		alert('Introduzca la descripción');
		return;
	}
	
	if(objPeriodoUtiles.value == '')
	{
		
		alert('Introduzca el periodo');
		return;
	}
	
	if(!valPeriodo(objPeriodoUtiles.value))
	{
		alert('Periodo Incorrecto');
		return;
			
	}
	
	if(objMontoUtiles.value == '')
	{
		
		alert('Introduzca el monto');
		return;
	}

	var queryString = 'numBeneficio='+objNumBeneficio.value
	+'&descripcionUtiles='+objDescripcionUtiles.value
	+'&periodoUtiles='+objPeriodoUtiles.value
	+'&montoUtiles='+objMontoUtiles.value;
	
	
	AjaxRequest.post
	(
		{
			'url':'lib/controladorCes.php',
			'parameters':{'caso':'guardarMaestroUtiles'},
			'queryString':queryString,
			'onSuccess': function(req)
					{
						var respuesta = req.responseText;

						if (respuesta == '1')
						{
							alert('Registro Guardado');
							location.href='beneficio_utiles.php';
							return;
							
						} else if (respuesta == '0') {
							
							alert('No se pudo guardar el registro');
							return;
							
						} else if (respuesta == '2') {
							
							alert('Ya existe un registro con el número '+numBeneficio.value);
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

function cargarDatosModificarMaestroutiles()
{
	if(xGetElementById('codigoMaestroUtiles').value == 0)
	{
		alert('Seleccione un registro');
		return;	
		
	}
	
	location.href = 'rh_maestro_modificar_utiles.php?codigoMaestroUtiles='+xGetElementById('codigoMaestroUtiles').value;
}

function modificarMaestroUtiles()
{
	
	
	
	objNumBeneficio = xGetElementById('numBeneficio');
	objDescripcionUtiles = xGetElementById('descripcionUtiles');
	objPeriodoUtiles = xGetElementById('periodoUtiles');
	objMontoUtiles = xGetElementById('montoUtiles');
	
	
	var queryString;
	
	if(objNumBeneficio.value == '')
	{
		
		alert('Introduzca el número del beneficio de útiles');
		return;
	}
	
	if(objDescripcionUtiles.value == '')
	{
		
		alert('Introduzca la descripción');
		return;
	}
	
	if(objPeriodoUtiles.value == '')
	{
		
		alert('Introduzca el periodo');
		return;
	}
	
	if(!valPeriodo(objPeriodoUtiles.value))
	{
		alert('Periodo Incorrecto');
		return;
			
	}
	
	if(objMontoUtiles.value == '')
	{
		
		alert('Introduzca el monto');
		return;
	}

	var queryString = 'numBeneficio='+objNumBeneficio.value
	+'&descripcionUtiles='+objDescripcionUtiles.value
	+'&periodoUtiles='+objPeriodoUtiles.value
	+'&montoUtiles='+objMontoUtiles.value
	+'&codUtilesAyuda='+xGetElementById('codUtilesAyuda').value;
	
	
	AjaxRequest.post
	(
		{
			'url':'lib/controladorCes.php',
			'parameters':{'caso':'modificarMaestroUtiles'},
			'queryString':queryString,
			'onSuccess': function(req)
					{
						var respuesta = req.responseText;

						if (respuesta == '1')
						{
							alert('Registro Modificado');
							location.href='beneficio_utiles.php';
							return;
							
						} else if (respuesta == '0') {
							
							alert('No se pudo modificar el registro');
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

function eliminarMaestroUtiles()
{
	
	var queryString = 'codUtilesAyuda='+xGetElementById('codigoMaestroUtiles').value;
	
	if(xGetElementById('codigoMaestroUtiles').value == 0)
	{
		alert('Seleccione un registro');
		return;	
		
	}
	
	if(!window.confirm('¿Esta seguro de eliminar el registro?'))
	{
		return;	
	}
	
	
	AjaxRequest.post
	(
		{
			'url':'lib/controladorCes.php',
			'parameters':{'caso':'eliminarMaestroUtiles'},
			'queryString':queryString,
			'onSuccess': function(req)
					{
						var respuesta = req.responseText;

						if (respuesta == '1')
						{
							alert('Registro eliminado');
							location.href='beneficio_utiles.php';
							return;
							
						} else if (respuesta == '0') {
							
							alert('No se pudo eliminar el registro');
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

function generarReporteGeneralUtiles()
{
	
	valorTipoNomina = devolverValorLista('ftiponom');
	valorPeriodo = devolverValorLista('fperiodo');
	
	if(valorPeriodo == '-1')
	{
		alert('Seleccione el Periodo');
		return;	
	}
	
	window.open ("reporte_utiles_general_pdf.php?tipoNomina="+valorTipoNomina+"&periodo="+valorPeriodo,"iReporte","status=1");	
	
}

function generarReporteDetalladoUtiles()
{
	
	valorTipoNomina = devolverValorLista('ftiponom');
	valorPeriodo = devolverValorLista('fperiodo');
	
	if(valorPeriodo == '-1')
	{
		alert('Seleccione el Periodo');
		return;	
	}
	
	window.open ("reporte_utiles_detallado_pdf.php?tipoNomina="+valorTipoNomina+"&periodo="+valorPeriodo,"iReporte","status=1");	
	
}

function toCurrency(cnt){
		cnt = cnt.toString().replace(/\$|\,/g,'');
		if (isNaN(cnt))
			return 0;    
		var sgn = (cnt == (cnt = Math.abs(cnt)));
		cnt = Math.floor(cnt * 100 + 0.5);
		cvs = cnt % 100;
		cnt = Math.floor(cnt / 100).toString();
		if (cvs < 10)
		cvs = '0' + cvs;
		for (var i = 0; i < Math.floor((cnt.length - (1 + i)) / 3); i++)
			cnt = cnt.substring(0, cnt.length - (4 * i + 3)) + '.' + cnt.substring(cnt.length - (4 * i + 3));
		return (((sgn) ? '' : '-') + cnt + ',' + cvs);
	}
	

function formatoMoneda(fld, milSep,decSep, e)
{
	/*
	ENTRADA: fld (NOMBRE DEL CAMPO DE TEXTO), milSep (separadores miles), decSep(separador de decimales) e (evento)
	SALIDA: 
	DESCRIPCIÓN: FUNCION QUE SE ENCARGA DE TRANFORMAR LOS NUMEROS INTRODUCISDOS EN FORMATO DE MILES Y DECIMALES
	PROGRAMADOR: ERNESTO RIVAS
	fecha: 01-10-2012
	*/
	
	/* */
	
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
   // alert(whichCode);
    //if (whichCode == 13) return true; // Enter 
    
    //key = String.fromCharCode(whichCode); // Get key value from key code
    //alert(whichCode);
    
    if(whichCode!=8) //PARA QUE PERMITA ACEPTAR LA TECHA <- (BORRAR)
    {
    	key = String.fromCharCode(whichCode); // Get key value from key code
    	//alert(strCheck.indexOf(key));
    	if (strCheck.indexOf(key) == -1) return false; // Not a valid key
    	len = fld.value.length;    	
   		// alert(len);
    }
    
    else len = fld.value.length-1; //PARA QUE PERMITA BORRAR
   // alert(len);
    for(i = 0; i < len; i++) 
     if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != 44)) break; 
    aux = ''; 
    for(; i < len; i++) 
     if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i); 
    aux += key;
    len = aux.length;
    if (len == 0) fld.value = '0,00'; 
    if (len == 1) fld.value = '0'+ decSep + '0' + aux; 
    if (len == 2) fld.value = '0'+ decSep + aux; 
    if (len > 2) 
    { 
	     aux2 = ''; 
	     for (j = 0, i = len - 3; i >= 0; i--) 
	     { 
		      if (j == 3) 
		      { 
			       aux2 += milSep; 
			       j = 0; 
		      } 
		      aux2 += aux.charAt(i); 
		      j++; 
	     } 
	     fld.value = ''; 
	     len2 = aux2.length; 
	     for (i = len2 - 1; i >= 0; i--) 
	      	fld.value += aux2.charAt(i); 
	     fld.value += decSep + aux.substr(len - 2, len);
    } //decSep +
    return false;
}//----------------------------------------------------------------------------------------------------------------------------------



