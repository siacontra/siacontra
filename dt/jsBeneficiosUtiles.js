
//04-09-2013
var montoPeriodo =0.00;

var OBJ_BENEFICIO_UTILES=new itemHijo();
//alert('dfdggf');
function itemHijo()
{	
	/*
	ENTRADA: 
	SALIDA:
	DESCRIPCIÓN: 
	PROGRAMADOR: ERNESTO RIVAS
	fecha: 10-09-2013
	*/
		
	this.nu_codigo     	= new Array();
	this.nombre			= new Array();
	this.monto      	= new Array();
	
}


function eliminarItem(i)
{
	/*
	ENTRADA: 
	SALIDA:
	DESCRIPCIÓN: FUNCIÓN QUE PERMITE ELIMINAR EL ITEM SELECCIONADO
	PROGRAMADOR: ERNESTO RIVAS
	fecha: 10-09-2013
	*/
			
	OBJ_BENEFICIO_UTILES.nu_codigo.splice(i,1);
	OBJ_BENEFICIO_UTILES.nombre.splice(i,1);
	OBJ_BENEFICIO_UTILES.monto.splice(i,1);
	j= OBJ_BENEFICIO_UTILES.nu_codigo.length;		
	mostrarItemBeneficio(j,'');
	
}


function agregarItem()
{	
	/*
	ENTRADA: 
	SALIDA:
	DESCRIPCIÓN: FUNCIÓN QUE PERMITE AGREGAR EL VALOR DE LOS CAMPOS DE CODIFICACIÓN EN LOS ATRIBUTOS
	PROGRAMADOR: ERNESTO RIVAS
	fecha: 09-10-2012
	*/

	//montoPeriodo

	

	var montoIntroducido =quitarMiles(xGetElementById('txt_monto').value);

	montoIntroducido = montoIntroducido.replace(',','.');
		montoPeriodo=parseFloat(montoPeriodo);
		montoIntroducido=parseFloat(montoIntroducido);
		
		var total = montoPeriodo + montoIntroducido;
	
		total=Math.round (total * 100) / 100;

		var i= OBJ_BENEFICIO_UTILES.nu_codigo.length;
		var hijo = xGetElementById('sel_familia_funcionario').value;
		var arrayHijo = hijo.split("-");
		var bandera =0;
	
	if(montoIntroducido>montoPeriodo)
	{alert('El monto es superior que el monto asignado en el periodo. Verifique el monto introducido');}
	
	else if(xGetElementById('codempleado').value=='')
	{
		alert('Debes introducir el funcionario');
		xGetElementById('codempleado').focus();
	}

	else if(xGetElementById('sel_familia_funcionario').value=='0')
	{
		alert('Debes seleccionar el hijo del funcionario');
		xGetElementById('sel_familia_funcionario').focus();
	}
	
	else if(xGetElementById('txt_monto').value=='0,00')
	{
		alert('Debes introducir el monto');
		xGetElementById('txt_monto').focus();
	}
	
	else
	{
		//alert('asasdas');
		
		
		for(var j=0; j<i; j++)
		{
			//alert(arrayHijo[0]+'=='+OBJ_BENEFICIO_UTILES.nu_codigo[j]);
			if(arrayHijo[0]==OBJ_BENEFICIO_UTILES.nu_codigo[j])
			{
				bandera=1;// se encuentra el registro			
				break;
			}
			else
				bandera=0;// no se encuentra						
		}						
		
		if(bandera==1)
			alert('El familiar: '+arrayHijo[1]+', se encuentra agregado. Verifique');
		else
		{					
			OBJ_BENEFICIO_UTILES.nu_codigo[i] 	 	= arrayHijo[0];								
			OBJ_BENEFICIO_UTILES.nombre[i] 	 		= arrayHijo[1];
			OBJ_BENEFICIO_UTILES.monto[i]		 	= xGetElementById('txt_monto').value+' ';											
			i= OBJ_BENEFICIO_UTILES.nu_codigo.length;
			mostrarItemBeneficio(i,'');
		}
	}
}


function mostrarItemBeneficio(i,cond)
{
	//alert(cond);
	/*
	ENTRADA: i (TAMAÑO DEL ARREGLO)
	SALIDA:
	DESCRIPCIÓN: FUNCIÓN QUE PERMITE MOSTRAR LOS ITEM QUE ESTAN REGISTRADO 
	PROGRAMADOR: ERNESTO RIVAS
	fecha: 09-10-2012
	*/
	total='0.00';
	var tabla = xGetElementById('tablaItem');
	var montoDis = 0.00;
	//xGetElementById(idfx[14]).value='0,00';
	
		// get the reference for the body
        // creates a <table> element and a <tbody> element
        var tbl       = xCreateElement("table");
			tbl.width = '100%';
			tbl.setAttribute('border','0px');		
			tbl.setAttribute("cellpadding", "0");
	    	tbl.setAttribute("cellspacing", "0");
			//tbl.setAttribute("class", "tblLista");
		
		//tbl.setAttribute('style','table-layout: fixed');
		
		//tbl.setAttribute("id","tabla");
        var tblBody = xCreateElement("tbody");

        // creating all cells
		//alert(datos.length);
			
		
		if(i==0)// no existe
		{
			total='0.00';
			var row = xCreateElement("tr");
				var br = xCreateElement("br");
				var cell1 = xCreateElement("td");
				cell1.width='100%';
				cell1.setAttribute('align','center');
					var cellText1 =document.createTextNode('No existe movimiento');
					cell1.appendChild(br);
					cell1.appendChild(cellText1);
					//cell1.appendChild(br);
								
			row.appendChild(cell1);
			tblBody.appendChild(row);
			tbl.appendChild(tblBody);
			tabla.innerHTML='';
			tabla.appendChild(tbl);
			
			tbl.setAttribute("border", "0");
			//actualizarMotnoTotal('0');		
		}
        else
		{
			
			for(var j=0;j<i;j++)	
			{								
					// creates a table row
					var row = document.createElement("tr");
					row.setAttribute("class","trListaBody");									
					
					
			
					// Create a <td> element and a text node, make the text
					// node the contents of the <td>, and put the <td> at
					// the end of the table row
					var cell1 = xCreateElement("td");
					var cell2 = xCreateElement("td");
					var cell3 = xCreateElement("td");
					var cell4 = xCreateElement("td");
					var cell5 = xCreateElement("td");
					
										
				
																										
					cell1.width = '20';cell1.setAttribute('align','center');										
					cell2.width = '20';//cell3.setAttribute('style','overflow: hidden;text-overflow: ellipsis;white-space: nowrap;');				
					cell3.width = '400';cell3.setAttribute('align','left');
					cell4.width = '160';cell4.setAttribute('align','right');
					cell5.width = '20';cell5.setAttribute('align','center');
					
					//alert(OBJ_CESTA.co_presupuesto[j]);
																
						var cellText1 = document.createTextNode(j+1);											
						
						
						
						
						
						var cellText2 = document.createTextNode(OBJ_BENEFICIO_UTILES.nu_codigo[j]);
						
						var cellText3 = document.createTextNode(OBJ_BENEFICIO_UTILES.nombre[j]);												
						
						var cellText4 = document.createTextNode(OBJ_BENEFICIO_UTILES.monto[j]);
						//OBJ_CESTA.monto[j]
					//alert(cond+' for');
						if(cond!='VER' && cond!='APROBAR'){
								var cellText5 = document.createElement('img');
								cellText5.src='../imagenes/circle_red_16.png';
								cellText5.align='center';//setAttribute('align','center');
								cellText5.heigth='16';
								cellText5.width='16';
							
								cellText5.title='Eliminar Item';
								cellText5.setAttribute('onclick','eliminarItem('+j+')');									
							}
						else
						{
								var cellText5 = document.createElement('h1');
								//cellText5.src='../imagenes/circle_red_16.png';
						}
															
					
						cell1.appendChild(cellText1);
						cell2.appendChild(cellText2);
						cell3.appendChild(cellText3);
						cell4.appendChild(cellText4);
						cell5.appendChild(cellText5);
						
						
						
						row.appendChild(cell1);
						row.appendChild(cell2);	
						row.appendChild(cell3);
						row.appendChild(cell4);	
						row.appendChild(cell5);
						
						
						//row.setAttribute("bgcolor",color);				

					tblBody.appendChild(row);
			}
			
	        // put the <tbody> in the <table>
			tblBody.setAttribute('border','0px');
			tbl.appendChild(tblBody);
			// appends <table> into <body>
			tabla.innerHTML='';
			
			tabla.appendChild(tbl);
						
		}
}




function asignarMontoPeriodo()
{
	var cod =  xGetElementById('fperiodo').value;
	var array = cod.split('#');
	var codigo=array[0];
	montoPeriodo = array[1];
	
	//xGetElementById('txt_monto').value=codigo;
	
	//alert(xGetElementById('txt_monto').value+'  '+ montoPeriodo);
	
	
}


function comparaFecha(){

var str1 = document.getElementById("fdesde").value;
var str2 = document.getElementById("fhasta").value;

var dt1  = parseInt(str1.substring(0,2),10);
var mon1 = parseInt(str1.substring(3,5),10);
var yr1  = parseInt(str1.substring(6,10),10);
var dt2  = parseInt(str2.substring(0,2),10);
var mon2 = parseInt(str2.substring(3,5),10);
var yr2  = parseInt(str2.substring(6,10),10);
var date1 = new Date(yr1, mon1, dt1);
var date2 = new Date(yr2, mon2, dt2); 

var diferencia = date1 - date2;
var Dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
   Dias = -1 * Dias;
   document.getElementById("dias").value= Dias + 1;
}




/*-------------------------------------------------------------------------------------------------*/







function campoVacio(q) { 

	/*
	ENTRADA: q (CADENA DE TEXTO)
	SALIDA: TRUE O FALSE
	DESCRIPCIÓN: FUNCION QUE SE ENCARGA DE VERIFICAR SI EXISTE TEXTO EN BLAMCO 
	PROGRAMADOR: ERNESTO RIVAS
	fecha: 10-10-2012
	*/
	
	 
         for ( i = 0; i < q.length; i++ ) {  
                 if ( q.charAt(i) != " " ) {  
                        return true;  
                 }  
         }  
       return false;  
 }



function formatoNumerico(number) {
	/*
	ENTRADA: number (MONTO SIN FORMATO)
	SALIDA: MONTO TRANFORMADO CON LOS MILES Y DECIMALES
	DESCRIPCIÓN: FUNCION QUE SE ENCARGA TRANFORMAR =>  
	PROGRAMADOR: ERNESTO RIVAS
	fecha: 10-10-2012
	*/
	
	/* */
   var comma = '.',
       string = Math.max(0, number).toFixed(0),
       length = string.length,
       end = /^\d{4,}$/.test(string) ? length % 3 : 0;
   return (end ? string.slice(0, end) + comma : '') + string.slice(end).replace(/(\d{3})(?=\d)/g, '$1' + comma);
   
}/////////////////////////////////////////////////////////////////////////////////////////////////////////////////




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




function quitarMiles(valor)
{	
	/*
	ENTRADA: VALOR (MONTO EN FORMATO DE MILES)
	SALIDA: VALOR (MONTO TRANFORMADO SIN LOS MILES)
	DESCRIPCIÓN: FUNCION QUE SE ENCARGA TRANFORMAR 9.999.999 => 9999999 
	PROGRAMADOR: ERNESTO RIVAS
	fecha: 10-10-2012
	*/
	
	/* */
	
	while (valor.indexOf('.')>-1) {
			pos= valor.indexOf('.');
			valor = "" + (valor.substring(0, pos) + '' + 
			valor.substring((pos + '.'.length), valor.length));
	}
	return valor;
}


function fechaJStoMySql(fecha){
	if(fecha){
		var pedazo = fecha.split("-");
		var newFecha=pedazo[2]+'-'+pedazo[1]+'-'+pedazo[0];
		return newFecha;
	}
	else
		return '';
}




function limpiarCampoFactura()
{
	//xGetElementById('txt_codigo').value='';
	//xGetElementById('txt_codigo').focus();
	//xGetElementById('txt_descripcion').value='';
	
	OBJ_BENEFICIO_UTILES.nu_codigo     	= new Array();
	OBJ_BENEFICIO_UTILES.nombre			= new Array();	
	OBJ_BENEFICIO_UTILES.monto      	= new Array();
	
	xGetElementById('txt_monto').value='0,00';						
}

function limpiarCamposBeneficio()
{
	
	limpiarCampoFactura();
	
	
	/*xGetElementById('sel_servicio').value = '0';		
	xGetElementById('sel_rama').value = '0';			
	xGetElementById('sel_institucion').value = '0';
	xGetElementById('sel_medico').value = '0';*/
	
	/*xGetElementById('nomempleadoA').value = '';
	xGetElementById('codempleadoA').value = '';
	*/
	xGetElementById('nomempleado').value = '';
	xGetElementById('codempleado').value = '';
	
	
	xGetElementById('sel_familia_funcionario').length=1;	
	xGetElementById('sel_familia_funcionario').options[0].value = '0';
	xGetElementById('sel_familia_funcionario').options[0].text  = '..';
	
	
	/*xGetElementById('ch_planilla').checked=false;
	xGetElementById('ch_informe').checked=false;
	xGetElementById('ch_factura').checked=false;
	xGetElementById('ch_recipe').checked=false;
	xGetElementById('ch_otro').checked=false;	*/
	
	/*xGetElementById('td_disponible').innerHTML='0,00';
	xGetElementById('td_consumido').innerHTML='0,00';
	xGetElementById('td_disponible_serv').innerHTML='0,00';
	xGetElementById('td_consumido_serv').innerHTML='0,00';*/
	
	//mostrarItemBeneficio(0,'');
	
}








var codPersona = new Array();
var nomPersona = new Array();
var codigo	   = new Array();


function colocarResponsable()
{
	//alert('asdasdasd');
	var cod = xGetElementById('sel_servicio').value;
	
	for(var i=0; i<codigo.length; i++)
	{
		if(cod==codigo[i])
		{
				xGetElementById('codempleadoA').value = codPersona[i];
				xGetElementById('nomempleadoA').value = nomPersona[i];
		}
	}
	//alert(codigo);
	
	//xGetElementById('codempleado').value = codigo;
	//xGetElementById('nomempleado').value = persona;
}






function validarCampoBeneficio(op)
{
	/*
	ENTRADA: 
	SALIDA:
	DESCRIPCIÓN: FUNCIÓN QUE PERMITE VALIDAR LOS CAMPOS DEL CREDITO ADICIONAL.
	PROGRAMADOR: ERNESTO RIVAS
	fecha: 16-10-2012
	*/		
	
	var nroOrden	  	= 	xGetElementById('txt_nro_orden').value;	
	//var tipoSolicitud	=	xGetElementById('sel_tipo_solicitud').value;
	var codPersona		=	xGetElementById('codempleado').value;
	/*var servicio		=	xGetElementById('sel_servicio').value;
	var rama			=	xGetElementById('sel_rama').value;
	var institucion		=	xGetElementById('sel_institucion').value;
	var medico			=	xGetElementById('sel_medico').value;	*/	
	//var fechaCreacion	=	xGetElementById('fcreacion').value;
	//var estatus			=	xGetElementById('estado').value;
	//var monto 			=	xGetElementById('txt_monto').value;
	
	var periodo			=	xGetElementById('fperiodo').value;
	
	var proveedor			=	xGetElementById('sel_proveedor').value;
	
	
	//var totalAgregado	=	xGetElementById('totalMonto').innerHTML;
	//var aprobado		=	xGetElementById('nomempleadoA').value;
	
	var hijo = xGetElementById('sel_familia_funcionario').value;
	
	
	/*aun no*/
	/*var chPlanilla		=	xGetElementById('ch_planilla').value;
	var chInforme		=	xGetElementById('ch_informe').value;
	var chFactura		=	xGetElementById('ch_factura').value;
	var chRecipe		=	xGetElementById('ch_recipe').value;
	var chOtro			=	xGetElementById('ch_otro').value;
	
	var movimineto		=	OBJ_BENEFICIO.nu_codigo.length;*/
	
	
	
	//alert(totalAgregado+' '+monto)
	
	if(!campoVacio(nroOrden))	
	{
		alert('Debes introducir el número del oficio');	
		xGetElementById('txt_nro_orden').focus();
		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 
	}
	
	/*else if(tipoSolicitud=='0')
	{
		alert('Debes introducir el tipo de solicitud');		
		xGetElementById('sel_tipo_solicitud').focus();
		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 
	}*/
	
	else if(proveedor=='0')
	{
		alert('Debes seleccionar el proveedor');		
		//xGetElementById('sel_proveedor').focus();		
	}

	else if(codPersona=='0')
	{
		alert('Debes seleccionar el periodo');				
	}

	else if(codPersona=='')
	{
		alert('Debes seleccionar el funcionario');				
	}
	
	
			
	/*
	else if(monto=='0,00')
	{
		alert('Debes introducir el monto');	
		xGetElementById('txt_monto').focus();
		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 
	}*/
	
	
	/*
	else if(institucion=='0')
	{
		alert('Debes seleccionar la institucion');
		xGetElementById('sel_institucion').focus();
		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 
	}
	
	else if(medico=='0')
	{
		alert('Debes introducir por quien se debe aprobar el crédito adicional');	
		xGetElementById('nomempleado').focus();
		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 
	}
	
	else if(movimineto<1)
	{
		alert('No existe movimientos agregado');	
		xGetElementById('txt_codigo').focus();
		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 
	}
		*/
	else
	{		
		guardarBeneficio(op);		
	} 
}



function guardarBeneficio(op)
{
	var nroOrden	  	= 	xGetElementById('txt_nro_orden').value;	
	//var tipoSolicitud	=	xGetElementById('sel_tipo_solicitud').value;
	var codPersona		=	xGetElementById('codempleado').value;
	var codFamilia		=	xGetElementById('sel_familia_funcionario').value;
	var monto 		=	xGetElementById('txt_monto').value;
	var proveedor		=	xGetElementById('sel_proveedor').value;
	
	
	 
	//var organismo		=	xGetElementById('organismo').value;
	
	/*var servicio		=	xGetElementById('sel_servicio').value;
	var rama			=	xGetElementById('sel_rama').value;
	var institucion		=	xGetElementById('sel_institucion').value;
	var medico			=	xGetElementById('sel_medico').value;*/
			
	/*var fechaCreacion	=	xGetElementById('fcreacion').value;
		fechaCreacion	=	fechaJStoMySql(fechaCreacion);*/
						
	//var estatus			=	xGetElementById('co_estado').value;
	
	var ultimaMod		=	xGetElementById('ult_usuario').value; 
	//codempleadoA
	
	var fechaUltimaMod	=	xGetElementById('ult_fecha').value;		
		//fechaUltimaMod	=	fechaJStoMySql(fechaUltimaMod);
	
	//var ejercicio		=	xGetElementById('ejercicioPpto').value;
	
	
	/*
	var totalAgregado	=	xGetElementById('totalMonto').innerHTML;
		totalAgregado	=	quitarMiles(totalAgregado);
		totalAgregado 	=	totalAgregado.replace(',','.');*/
	//var aprobado		=	xGetElementById('codempleadoA').value;
	
	
	
	
	var cod 			=  	xGetElementById('fperiodo').value;
	var array 			= 	cod.split('#');
	var codigo			=	array[0];
	var periodo 		=  	codigo;
	
	//var preparado		=	xGetElementById('codprepor').value;
	
	/*
	var chPlanilla		=	xGetElementById('ch_planilla').checked;
	var chInforme		=	xGetElementById('ch_informe').checked;
	var chFactura		=	xGetElementById('ch_factura').checked;
	var chRecipe		=	xGetElementById('ch_recipe').checked;
	var chOtro			=	xGetElementById('ch_otro').checked;
	*/
	var codBeneficio ='';
	var preparado ='';
	var conf= ''
	if(op=='EDITAR'){conf='Esta seguro de modificar el beneficio escolar seleccionado?';  codBeneficio = xGetElementById('codBeneficio').value;}
	if(op=='GUARDAR'){conf='Esta seguro de guardar el beneficio escolar?';  preparado 		=	xGetElementById('codprepor').value;}
	
	if(confirm(conf))
	{	
		
			var opx	=	op;
			var url 	=	'lib/controladorCes.php';
			AjaxRequest.post
														(
															{
																'parameters':{'caso':opx,
																			  'nroOrden':nroOrden,
																			  'organismo':organismo,
																			 // 'tipoSolicitud': tipoSolicitud,
																			  'codPersona':codPersona,
																			  'codFamilia':codFamilia,
																			  'codBeneficio':codBeneficio,
'proveedor':proveedor,
																			  //'servicio':servicio,
																			 //																	  
																			  //'institucion':institucion,
																			  'monto':monto,
																			  //'ejercicio':ejercicio,
																			  //'fcreacion':fechaCreacion,
																			 // 'estatus':estatus,																	  
																			 // 'totalAgregado':totalAgregado,
																			 // 'aprobado':aprobado,
																			  'preparado':preparado,
																			  'ultimaMod':ultimaMod,
																			  'fechaUltimaMod':fechaUltimaMod,
																			  'arrayCodigo':OBJ_BENEFICIO_UTILES.nu_codigo,							
																			  'arraynombre':OBJ_BENEFICIO_UTILES.nombre,
																			  'arrayMonto':OBJ_BENEFICIO_UTILES.monto,
																			  'periodo':periodo
																			  }
																,'url':url
																,'onSuccess': function (req){respGuardarBeneficio(req,op)}										       
																,'onError':function(req)
																{ 
																	alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
																}         
															}
														);
	}
	
}

function respGuardarBeneficio(req, op)
{
//	alert(op);
	//var datos= eval ("("+req.responseText+")");
	//alert(req.responseText);
	if(req.responseText == '1')
	{
		alert('Se ha guardado con exito el beneficio');
		limpiarCamposBeneficio();
		if(op=='GUARDAR'){window.parent.location.reload();}
		if(op=='EDITAR'){location.href='listarBeneficiosUtiles.php';/*window.parent.document.location='listarBeneficiosUtiles.php'; */		/*window.parent.frame("main").location.href='listarBeneficiosUtiles.php';*/}
		
	}
	else
	{
		alert('Error al guardar el beneficio');
	}
	
}



function cargarDatosBeneficio(codigo,cond)
{
	//alert(codigo);
	var url   	 = 'lib/controladorCes.php';
	var opx   	 = 'BUSCARBENEFICIO';
	AjaxRequest.post
												(
								       				{
								       					'parameters':{'caso':opx, 
																	  'codigo':codigo}
								            			,'url':url
								            			,'onSuccess': function(req) {respCargarDatosBeneficio(req,cond)}											       
														,'onError':function(req)
														{ 
								                        	alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
								                        }         
								             		}
								        		);		
	
}


function respCargarDatosBeneficio(req, cond)
{
	var datos= eval ("("+req.responseText+")");
	//var filas = datos.length;
		
	//alert(datos[0]['tx_estatus']);
	
				
	/////////////////////////////////////////////////
	xGetElementById('fperiodo').value=datos[0]['periodoutiles']+'#'+datos[0]['montoasignado'];//listo
	
	cargarHijosEmpleadoEditar(datos[0]['codpersonabeneficiario'], 'CARGAHIJOS'); // listo
	
	xGetElementById('codempleado').value=datos[0]['codpersonabeneficiario'];//listo

	xGetElementById('sel_proveedor').value=datos[0]['CodProveedor'];//listo	

	buscarFuncionarioBeneficio(datos[0]['codpersonabeneficiario']);//listo
	
	///////////////////////////////////////////////////
	//PARA BUSCAR QUE VA APROBAR EL CREDITO ADICIONAL//
	///////////////////////////////////////////////////
		//var aprobado = datos[0]['aprobadoPor'];		
		//alert(aprobado);
		//xGetElementById('codempleadoA').value=aprobado;
		//buscarAprobarBeneficio(aprobado);
		//xGetElementById('codempleadoA').value=aprobado;
	
	////////////////////////////////////////////////////////////////////////////////////////
	//xGetElementById('organismo').value=datos[0]['CodOrganismo'];
	//
	//xGetElementById('txt_nro_orden').value=datos[0]['nroBeneficio'];
	//xGetElementById('sel_tipo_solicitud').value=datos[0]['tipoSolicitud'];
	
	
	
	
	
	//codempleado
	
	//cargarFamiliarEmpleadoVer(datos[0]['codPersona'],'CARGAFAMILIAR',datos[0]['codFamiliar']);
				

	///////////////////////////////////////////////////
	//para buscar los item //
	///////////////////////////////////////////////////
	buscarItemBeneficio(datos[0]['codbeneficiarioutiles'],'');	
	
		
	xGetElementById('ult_usuario').value=datos[0]['ultimousuario'];
	
	xGetElementById('ult_fecha').value=fechaJStoMySql(datos[0]['ultimafecha']);
	
	asignarMontoPeriodo();
	
/*
	
	if(datos[0]['estadoBeneficio']=='PE')
		xGetElementById('estado').value = 'Preparado';
	else if(datos[0]['estadoBeneficio']=='RV')
		xGetElementById('estado').value = 'Revisado';
	else if(datos[0]['estadoBeneficio']=='AP')
		xGetElementById('estado').value = 'Aprobado';
	else if(datos[0]['estadoBeneficio']=='GE')
		xGetElementById('estado').value = 'Generado';
	else
		xGetElementById('estado').value = 'Anulado';
		
		*/
	
	//var totalAgregado	=	xGetElementById('totalMonto').value;
	//alert(datos[0]['tx_preparado']);
	//xGetElementById('codprepor').value = datos[0]['tx_preparado '];
	//xGetElementById('prepor').value= datos[0]['NomCompleto'];
	
	
	
	//xGetElementById('ult_usuario').value=datos[0]['UltimoUsuario'];
	//xGetElementById('ult_fecha').value=datos[0]['UltimaFecha'];		
		//fechaUltimaMod	=	fechaJStoMySql(fechaUltimaMod);			*/		
		//preparadoPor();	
}   


// voy por aqui
    
function buscarItemBeneficio(codigo, cond)
{
	var persona	 =	xGetElementById('codempleado').value;

	//alert(codigo);
	var url   	 = 'lib/controladorCes.php';
	var opx   	 = 'BUSCARITEMBENEFICIO';
AjaxRequest.post
		(
		{
			'parameters':{'caso':opx, 
    				  'codigo':codigo, 'persona':persona,}
		,'url':url
		,'onSuccess': function(req) {respBuscarItemBeneficio(req,cond)}
				,'onError':function(req)
				{ 
        	alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
        }         
	}
	);	
}

function respBuscarItemBeneficio(req,cond)
{
	//alert(req.responseText);
	var datos= eval("("+req.responseText+")");		
	//xGetElementById('nomempleado').value = datos[0]['NomCompleto'];	
	
	//alert(req.responseText);
	
	
	for(var i=0; i<datos.length;i++)
	{				
				
			OBJ_BENEFICIO_UTILES.nu_codigo[i] 	 	= datos[i]['codsecuenciafamiliar'];						
			OBJ_BENEFICIO_UTILES.nombre[i] 	 		= datos[i]['NombresCarga'];
			OBJ_BENEFICIO_UTILES.monto[i]		 	= toCurrency(datos[i]['montoutilesfamiliar'])+' ';						  
								
		var tam= OBJ_BENEFICIO_UTILES.nu_codigo.length;
		
		
	}
	mostrarItemBeneficio(tam,cond);
			
}


function respBuscarAprobarBeneficio(req)
{
	//alert(req.responseText);
	var datos= eval ("("+req.responseText+")");		
	xGetElementById('nomempleadoA').value = datos[0]['NomCompleto'];	
}






function buscarFuncionarioBeneficio(codigo)
{
	var url   	 = 'lib/controladorCes.php';
	var opx   	 = 'BUSCARAPROBARBENEFICIO';
	AjaxRequest.post
												(
								       				{
								       					'parameters':{'caso':opx, 
																	  'codigo':codigo}
								            			,'url':url
								            			,'onSuccess': respBuscarFuncionarioBeneficio
														,'onError':function(req)
														{ 
								                        	alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
								                        }         
								             		}
								        		);
	
	
	
}

function respBuscarFuncionarioBeneficio(req)
{
	//alert(req.responseText);
	var datos= eval ("("+req.responseText+")");		
	
	xGetElementById('nomempleado').value = datos[0]['NomCompleto'];	
}



function cargarHijosEmpleadoEditar(codigo, accion)
{
	var url = 'lib/controladorCes.php';
	AjaxRequest.post
												(
								       				{
								       					'parameters':{'caso':accion, 
																	  'codigo':codigo
																	  }
								            			,'url':url
								            			,'onSuccess': function(req){respCargarHijosEmpleadoEditar(req,codigo)}
														,'onError':function(req)
														{ 
								                        	alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
								                        }         
								             		}
								        		);
}



function respCargarHijosEmpleadoEditar(req,codigo)
{
	//alert('sdfsdfsdfsdf');
	var resp =eval ("("+req.responseText+")");
	//alert(resp);
	
	//var parentesco ='';
	
		xGetElementById('sel_familia_funcionario').length=1;	
		xGetElementById('sel_familia_funcionario').options[0].value = '0';
		xGetElementById('sel_familia_funcionario').options[0].text  = '..';
	
	
	//var OBJ_BENEFICIO_UTILES=new itemHijo();
	if(resp!=null)
	{
		xGetElementById('sel_familia_funcionario').length=resp.length+1;
	
		xGetElementById('sel_familia_funcionario').options[0].value = '0';
		xGetElementById('sel_familia_funcionario').options[0].text  = '..';
	
		for(var i=0; i<resp.length;i++)
		{											
			xGetElementById('sel_familia_funcionario').options[i+1].value=resp[i]['CodSecuencia']+'-'+resp[i]['NombresCarga'];
			xGetElementById('sel_familia_funcionario').options[i+1].text=resp[i]['NombresCarga'];									
			
		}
	}			
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
