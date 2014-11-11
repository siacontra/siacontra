// JavaScript Document

/****************************************************************************************

* MODULO: PRESUPUESTO - SIACEDA

* OPERADORES_________________________________________________________

* | # |   FECHA    |   HORA   | PROGRAMADOR 
* | 1 | 01/01/2012 | 10:21:12 | Ernesto José Rivas Marval
* |__________________________________________________________________

* UBICACION: Venezuela- Sucre- Cumana

* VERSION: 1.0 

* SOPORTE: Ernesto José Rivas Marval

*******************************************************************************************/

 //TIPO::ARCHIVO CONTROLADOR DE LA PAGINA INICIO

 

/*VARIABLES GLOVALES*/


/*
var global_id_sector		= '';

var global_id_programa     	= '';

var global_id_sub_programa 	= '';

var global_id_proyecto     	= '';

var gloval_id_actividad		= '';
*/
var global_id_partida 		= '';





/*******************************************************************************************/





var OBJ_CREDITO_ADICIONAL=new itemCestaPresupuestaria();



/***/

/**/



window.onload=function()

{

	//FUNCION INICIAL

	//alert('CREDITO ADICIONAL');

	

	

}



function itemCestaPresupuestaria()

{

	

	/*

	ENTRADA: 

	SALIDA:

	DESCRIPCIÓN: FUNCIÓN QUE PERMITE INICIAR  CREDITO ADICIONAL

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 09-10-2012

	*/
/*
	this.co_sector      		= new Array();

	this.co_programa		 	= new Array();

	this.co_sub_programa      	= new Array();

	this.co_proyecto			= new Array();

	this.co_actividad 			= new Array();*/

	this.co_partida 			= new Array();

	
/*
	this.nu_sector      		= new Array();

	this.nu_programa		 	= new Array();

	this.nu_sub_programa      	= new Array();

	this.nu_proyecto			= new Array();

	this.nu_actividad 			= new Array();
*/
	this.nu_partida 			= new Array();

	this.descripcion_partida 	= new Array();

	this.monto      	 	 	= new Array();

}

function limpiarCestaPresupuestaria()

{

	/*

	ENTRADA: 

	SALIDA:

	DESCRIPCIÓN: FUNCIÓN QUE PERMITE LIMPIAR LOS ITEM DE LA CESTA DEL CREDITO ADICIONAL

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 09-10-2012

	*/

/*
	OBJ_CREDITO_ADICIONAL.co_sector      		= new Array();

	OBJ_CREDITO_ADICIONAL.co_programa		 	= new Array();

	OBJ_CREDITO_ADICIONAL.co_sub_programa      	= new Array();

	OBJ_CREDITO_ADICIONAL.co_proyecto			= new Array();

	OBJ_CREDITO_ADICIONAL.co_actividad 			= new Array();*/

	OBJ_CREDITO_ADICIONAL.co_partida 			= new Array();

	
/*
	OBJ_CREDITO_ADICIONAL.nu_sector      		= new Array();

	OBJ_CREDITO_ADICIONAL.nu_programa		 	= new Array();

	OBJ_CREDITO_ADICIONAL.nu_sub_programa      	= new Array();

	OBJ_CREDITO_ADICIONAL.nu_proyecto			= new Array();

	OBJ_CREDITO_ADICIONAL.nu_actividad 			= new Array();
	*/

	OBJ_CREDITO_ADICIONAL.nu_partida 			= new Array();

	OBJ_CREDITO_ADICIONAL.descripcion_partida 	= new Array();

	OBJ_CREDITO_ADICIONAL.monto      	 	 	= new Array();
}









function limpiarCamposCreditoAdcional()

{

	

	/*

	ENTRADA: 

	SALIDA:

	DESCRIPCIÓN: FUNCIÓN QUE PERMITE LIMPIAR LOS CAMPOS DEL FORMULARIO DEL CREDITO ADICIONAL

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 09-10-2012

	*/

	

	

	

	

	xGetElementById('gaceta').value='';

	xGetElementById('fgaceta').value='';

	xGetElementById('decreto').value='';

	xGetElementById('fdecreto').value='';	

	xGetElementById('txt_monto').value='0,00';//	

	xGetElementById('txt_motivo').value='';

	

	/*

	xGetElementById('txt_sector').value='';

	xGetElementById('txt_programa').value='';

	xGetElementById('txt_sub_programa').value='';

	xGetElementById('txt_proyecto').value='';	

	xGetElementById('txt_actividad').value='';//	
*/
	xGetElementById('txt_partida').value='';

	

	

	

	xGetElementById('txt_general').value='';

	xGetElementById('txt_especifico').value='';	

	xGetElementById('txt_sub_especifico').value='';//	

	xGetElementById('txt_ordinal').value='';

	

	

	

	limpiarCestaPresupuestaria();

	//desbloquearCampoPartida();

	desbloquearTodoCampoPartida();

	

	//limpiarCampoPartida();

			

	xGetElementById('codempleado').value='';

	xGetElementById('nomempleado').value='';

	

	

	

	

	xGetElementById('tablaItem').innerHTML='\nNo existe movimientos agregado';

	

	xGetElementById('totalMonto').innerHTML='0,00';	

	   

}





function desbloquearTodoCampoPartida()
{

	/*xGetElementById('txt_sector').disabled=false;

	xGetElementById('txt_programa').disabled=false;

	xGetElementById('txt_sub_programa').disabled=false;

	xGetElementById('txt_proyecto').disabled=false;

	xGetElementById('txt_actividad').disabled=false;
*/
	xGetElementById('txt_partida').disabled=false;

	xGetElementById('txt_general').disabled=false;

	xGetElementById('txt_especifico').disabled=false;

	xGetElementById('txt_sub_especifico').disabled=false;

	xGetElementById('txt_ordinal').disabled=false;

	

}



function agregarItemEnter(evt)

{//----------------------------------------------------------------------------------------------------------------------

	

	var e = new xEvent(evt);//crea una instancia de la clase xEvent

	

    if(e.keyCode == 13)//verificamos que alla presionado enter

    {					

			verificarCampoItem();					

     }

		

}//----------------------------------------------------------------------------------------------------------------------





function verificarCampoItem()

{

	

	/*

	ENTRADA: 

	SALIDA:

	DESCRIPCIÓN: FUNCIÓN QUE PERMITE VERIFICAR QUE LOS CAMPOS NO ESTEN EN BLANCO

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 09-10-2012

	*/

	/*

	var sector  		= 	xGetElementById('txt_sector').value;

	var programa  		= 	xGetElementById('txt_programa').value;

	var subPrograma  	= 	xGetElementById('txt_sub_programa').value;

	var proyecto  		= 	xGetElementById('txt_proyecto').value;

	var actividad  		= 	xGetElementById('txt_actividad').value;

	*/

	var partida 		=	xGetElementById('txt_partida').value;

	var general 		= 	xGetElementById('txt_general').value;

	var especifico 		=	xGetElementById('txt_especifico').value;

	var subEspecifico 	=	xGetElementById('txt_sub_especifico').value;

	var ordinal 		=	xGetElementById('txt_ordinal').value;// HASTA LOS MOMENTO NO SE ESTA APLICANDO EL ORDINAL 

	var montoIten 		=	xGetElementById('txt_monto_item').value;

		

	

	
/*
	if(!campoVacio(sector))	

	{

		alert('Debes introducir el sector');	

		xGetElementById('txt_sector').focus();

		bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	else if(!campoVacio(programa))

	{

		alert('Debes introducir el programa');		

		xGetElementById('txt_programa').focus();

		bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	

	else if(!campoVacio(subPrograma))

	{

		alert('Debes introducir el sub programa');	

		xGetElementById('txt_sub_programa').focus();

		bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	

	else if(!campoVacio(proyecto))

	{

		alert('Debes introducir el proyecto');	

		xGetElementById('txt_proyecto').focus();

		bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	

	else if(!campoVacio(actividad))

	{

		alert('Debes introducir la actividad');	

		xGetElementById('txt_actividad').focus();

		bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	*/

	if(!campoVacio(partida))

	{

		alert('Debes introducir la partida');	

		xGetElementById('txt_partida').focus();

		bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	

	else if(!campoVacio(general))

	{

		alert('Debes introducir el generico');	

		xGetElementById('txt_general').focus();

		bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	

	else if(!campoVacio(especifico))

	{

		alert('Debes introducir el específico');	

		xGetElementById('txt_especifico').focus();

		bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	

	else if(!campoVacio(subEspecifico))

	{

		alert('Debes introducir el sub especifico');	

		xGetElementById('txt_sub_especifico').focus();

		bandera = 0;//bandera que permite asegurar que se entro en una condicion

	}

	

	else if(!campoVacio(ordinal))

	{

		alert('Debes introducir el ordinal');	

		xGetElementById('txt_ordinal').focus();

		bandera = 0;//bandera que permite asegurar que se entro en una condicion

	}		

	

	else if(montoIten=='0,00')

	{

		alert('Debes introducir el monto');

		xGetElementById('txt_monto_item').focus();

		bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}				

	

	else

	{		

		//agregarItem();

		

		agregarItem();//X

		limpiarCampoPartida();

		desbloquearCampoPartida();

		xGetElementById('txt_partida').focus();

	} 

}/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////







function verificarCampoItemPartida()

{	

	/*

	ENTRADA: 

	SALIDA:

	DESCRIPCIÓN: FUNCIÓN QUE PERMITE VERIFICAR QUE LOS CAMPOS NO ESTEN EN BLANCO

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 09-10-2012

	*/

	

	/*var sector  		= 	xGetElementById('txt_sector').value;

	var programa  		= 	xGetElementById('txt_programa').value;

	var subPrograma  	= 	xGetElementById('txt_sub_programa').value;

	var proyecto  		= 	xGetElementById('txt_proyecto').value;

	var actividad  		= 	xGetElementById('txt_actividad').value;
*/
	

	var partida 		=	xGetElementById('txt_partida').value;

	var general 		= 	xGetElementById('txt_general').value;

	var especifico 		=	xGetElementById('txt_especifico').value;

	var subEspecifico 	=	xGetElementById('txt_sub_especifico').value;

	var ordinal 		=	xGetElementById('txt_ordinal').value;// HASTA LOS MOMENTO NO SE ESTA APLICANDO EL ORDINAL 

	

		

	

	
/*
	if(!campoVacio(sector))	

	{

		alert('Debes introducir el sector');	

		xGetElementById('txt_sector').focus();

		bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	else if(!campoVacio(programa))

	{

		alert('Debes introducir el programa');		

		xGetElementById('txt_programa').focus();

		bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	

	else if(!campoVacio(subPrograma))

	{

		alert('Debes introducir el sub programa');	

		xGetElementById('txt_sub_programa').focus();

		bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	

	else if(!campoVacio(proyecto))

	{

		alert('Debes introducir el proyecto');	

		xGetElementById('txt_proyecto').focus();

		bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	

	else if(!campoVacio(actividad))

	{

		alert('Debes introducir la actividad');	

		xGetElementById('txt_actividad').focus();

		bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	*/

	if(!campoVacio(partida))

	{

		alert('Debes introducir la partida');	

		xGetElementById('txt_partida').focus();

		bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	

	else if(!campoVacio(general))

	{

		alert('Debes introducir el generico');	

		xGetElementById('txt_general').focus();

		bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	

	else if(!campoVacio(especifico))

	{

		alert('Debes introducir el específico');	

		xGetElementById('txt_especifico').focus();

		bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	

	else if(!campoVacio(subEspecifico))

	{

		alert('Debes introducir el sub especifico');	

		xGetElementById('txt_sub_especifico').focus();

		bandera = 0;//bandera que permite asegurar que se entro en una condicion

	}

	

	else if(!campoVacio(ordinal))

	{

		alert('Debes introducir el ordinal');	

		xGetElementById('txt_ordinal').focus();

		bandera = 0;//bandera que permite asegurar que se entro en una condicion

	}		

					

	

	else

	{		

		buscarPartida();		

	} 

}/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





function agregarItem()

{	

	/*

	ENTRADA: 

	SALIDA:

	DESCRIPCIÓN: FUNCIÓN QUE PERMITE AGREGAR EL VALOR DE LOS CAMPOS DE CODIFICACIÓN EN LOS ATRIBUTOS

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 09-10-2012

	*/





		var i= OBJ_CREDITO_ADICIONAL.nu_partida.length;

		



		/*

		OBJ_CREDITO_ADICIONAL.co_sector[i]      		= global_id_sector;

		OBJ_CREDITO_ADICIONAL.co_programa[i]		 	= global_id_programa;

		OBJ_CREDITO_ADICIONAL.co_sub_programa[i]      	= global_id_sub_programa;

		OBJ_CREDITO_ADICIONAL.co_proyecto[i]			= global_id_proyecto;

		OBJ_CREDITO_ADICIONAL.co_actividad[i] 			= gloval_id_actividad;
*/
		OBJ_CREDITO_ADICIONAL.co_partida[i] 			= global_id_partida;

		

		//alert('1 '+OBJ_CREDITO_ADICIONAL.co_sector[i]+' 2 '+OBJ_CREDITO_ADICIONAL.co_programa[i]+' 3 '+OBJ_CREDITO_ADICIONAL.co_sub_programa[i]+' 4 '+OBJ_CREDITO_ADICIONAL.co_proyecto[i]+' 5 '+OBJ_CREDITO_ADICIONAL.co_actividad[i]+' 6 '+OBJ_CREDITO_ADICIONAL.co_partida[i])

		
/*
		OBJ_CREDITO_ADICIONAL.nu_sector[i] 				= xGetElementById('txt_sector').value;

		OBJ_CREDITO_ADICIONAL.nu_programa[i] 			= xGetElementById('txt_programa').value;

		OBJ_CREDITO_ADICIONAL.nu_sub_programa[i]		= xGetElementById('txt_sub_programa').value;

		OBJ_CREDITO_ADICIONAL.nu_proyecto[i] 			= xGetElementById('txt_proyecto').value;

		OBJ_CREDITO_ADICIONAL.nu_actividad[i] 			= xGetElementById('txt_actividad').value;*/

		OBJ_CREDITO_ADICIONAL.nu_partida[i] 			= xGetElementById('txt_partida').value+'.'+xGetElementById('txt_general').value+'.'+xGetElementById('txt_especifico').value+'.'+xGetElementById('txt_sub_especifico').value+'  ';

		OBJ_CREDITO_ADICIONAL.descripcion_partida[i] 	= xGetElementById('td_descripcion_partida').innerHTML;

		OBJ_CREDITO_ADICIONAL.monto[i]					= xGetElementById('txt_monto_item').value;

								

		i= OBJ_CREDITO_ADICIONAL.nu_partida.length;

		mostrarItemCreditoAdicional(i);

}





function eliminarItem(i)

{

	/*

	ENTRADA: 

	SALIDA:

	DESCRIPCIÓN: FUNCIÓN QUE PERMITE ELIMINAR EL ITEM SELECCIONADO

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 10-10-2012

	*/

	
/*
	OBJ_CREDITO_ADICIONAL.co_sector.splice(i,1);

	OBJ_CREDITO_ADICIONAL.co_programa.splice(i,1);

	OBJ_CREDITO_ADICIONAL.co_sub_programa.splice(i,1);

	OBJ_CREDITO_ADICIONAL.co_proyecto.splice(i,1);

	OBJ_CREDITO_ADICIONAL.co_actividad.splice(i,1);
*/
	OBJ_CREDITO_ADICIONAL.co_partida.splice(i,1);

	
/*
	OBJ_CREDITO_ADICIONAL.nu_sector.splice(i,1);

	OBJ_CREDITO_ADICIONAL.nu_programa.splice(i,1);

	OBJ_CREDITO_ADICIONAL.nu_sub_programa.splice(i,1);

	OBJ_CREDITO_ADICIONAL.nu_proyecto.splice(i,1);

	OBJ_CREDITO_ADICIONAL.nu_actividad.splice(i,1);
*/
	OBJ_CREDITO_ADICIONAL.nu_partida.splice(i,1);

	OBJ_CREDITO_ADICIONAL.descripcion_partida.splice(i,1);

	OBJ_CREDITO_ADICIONAL.monto.splice(i,1);

	var i = OBJ_CREDITO_ADICIONAL.nu_partida.length;

	

	mostrarItemCreditoAdicional(i);

}





function mostrarItemCreditoAdicional(i,cond)

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

			actualizarMotnoTotal('0');		

		}

        else

		{

			

			for(var j=0;j<i;j++)	

			{				

					/*if(!OBJ_CESTA.nu_presupuesto[j] || OBJ_CESTA.nu_presupuesto[j]=='')

					{

							buscarNumeroCodigoPresupuesto();// para buscar el numero presupuestario

							//OBJ_CESTA.nu_presupuesto[j]=xGetElementById(idfx[13]).value;							

					}*/

					/*

					if(j%2==0)

					{

						color='#FFFFFF';

					}

					else

					{

						color='#F3F3F3';

					}*/

					

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

					

																										

					cell1.width = '5%';cell1.setAttribute('align','center');										

					cell2.width = '25%';//cell3.setAttribute('style','overflow: hidden;text-overflow: ellipsis;white-space: nowrap;');				

					cell3.width = '50%';cell3.setAttribute('align','left');

					cell4.width = '15%';cell4.setAttribute('align','right');

					cell5.width = '5%';cell5.setAttribute('align','center');

					

					//alert(OBJ_CESTA.co_presupuesto[j]);

																

						var cellText1 = document.createTextNode(j+1);											

						

						

						

						

						

						var cellText2 = document.createTextNode(/*OBJ_CREDITO_ADICIONAL.nu_sector[j]+OBJ_CREDITO_ADICIONAL.nu_programa[j]+OBJ_CREDITO_ADICIONAL.nu_sub_programa[j]+OBJ_CREDITO_ADICIONAL.nu_proyecto[j]+OBJ_CREDITO_ADICIONAL.nu_actividad[j]+*/OBJ_CREDITO_ADICIONAL.nu_partida[j]);

						

						var cellText3 = document.createTextNode(OBJ_CREDITO_ADICIONAL.descripcion_partida[j]);	

						

						//OBJ_CESTA.descripcion_partida[j]=OBJ_CESTA.servicio[j]+' - '+OBJ_CESTA.descripcion_partida[j]+'*';

						

						//alert(nuPresupuesto);

						

						

						

						var cellText4 = document.createTextNode(OBJ_CREDITO_ADICIONAL.monto[j]);

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

					

					

					//para actualizar el total

							actualizarMotnoTotal(OBJ_CREDITO_ADICIONAL.monto[j]);

							///////////////////////////////////

							var valor = quitarMiles(OBJ_CREDITO_ADICIONAL.monto[j]);

								valor = valor.replace(',','.');

								//xGetElementById(idfx[22]).value=OBJ_CESTA.monto[j];

							

							

								

							montoDis+=parseFloat(valor);

							montoDis = Math.round (montoDis * 100) / 100;

							//alert('total :'+totalItemAgregado+'valor agregado :'+valor);

							

							var totalStrin=montoDis.toString();

							

							var totalArrego=totalStrin.split('.');

							//total =total

							//var sinDecimales=total.split('.');

							

							var miles=formatoNumerico(totalArrego[0]);

							

							var decimal=totalArrego[1];

							 if(!decimal){decimal='00';}

							 if(decimal.length==1){decimal=decimal+'0';}

							var totalFinal=miles+','+decimal;

							

							//xGetElementById(idfx[14]).value= totalFinal;//montoAgre-montoItem;

							/////////////////////////////////////

					

					

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

			

			//limpiarCampoItem();

		}

}





function actualizarMotnoTotal(monto)

{

	/*

	ENTRADA: monto (MONTO DEL ITEM AGREGADO)

	SALIDA:

	DESCRIPCIÓN: FUNCIÓN QUE PERMITE MOSTRAR LOS ITEM QUE ESTAN REGISTRADO 

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 09-10-2012

	*/

	

	var temp=monto;

	while (temp.indexOf('.')>-1) {

			pos= temp.indexOf('.');

			temp = "" + (temp.substring(0, pos) + '' + 

			temp.substring((pos + '.'.length), temp.length));

	}

					/////////////////

					

	var valorSemiFinal = temp;

	var valorFinal     = valorSemiFinal.replace(',','.');

						

						

						

	//var numerico=parseFloat(valorFinal);

	total= parseFloat(total)+parseFloat(valorFinal);

						

	total = Math.round (total * 100) / 100;

	total = total.toFixed(2);

	//alert('valorSemiFinal '+valorSemiFinal);

	//	alert(total);

	//alert('2'+total);

	//alert(aaaa);

						

	//alert(OBJ_CESTA.servicio[j]);

	var totalStrin=total.toString();

	var totalArrego=totalStrin.split('.');

	//total =total

	//var sinDecimales=total.split('.');

			//alert(totalArrego[0]);

	var miles=formatoNumerico(totalArrego[0]);

	var decimal=totalArrego[1];

	// if(!decimal){decimal='00';}

	var totalFinal=miles+','+decimal

	

	

	xGetElementById('totalMonto').innerHTML=totalFinal;

}





function contador (campo,  limite) 

{

	/*

	ENTRADA: CAMPO (NOMBRE DEL CAMPO DE TEXTO), LIMITE(EL LIMITE DE CARACTERES ACEPTADO)

	SALIDA:

	DESCRIPCIÓN: FUNCIÓN QUE PERMITE CONTAR Y LIMITAR LOS CARACTERES DE UN CAMPO DE TEXTO

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 01-10-2012

	*/

	

	

	campo = xGetElementById(campo);

if (campo.value.length > limite) 

		campo.value = campo.value.substring(0, limite);





}







function buscarSector() 

{	

	/*

	ENTRADA:

	SALIDA:

	DESCRIPCIÓN: permite buscar el sector introducido en la casilla sec

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 02-10-2012

	*/

	var valor = xGetElementById('txt_sector').value;

	var url   = 'lib/transCreditoAdicional.php';

	var opx   = 'getSector';

	//alert(valor);

	var longitud = valor.length;

	//alert(longitud);

	

	if(longitud==2)//se han agregado 2 carateres

	{

		AjaxRequest.post

												(

								       				{

								       					'parameters':{'opcion':opx, 

																	  'sector':valor}

								            			,'url':url

								            			,'onSuccess': respBuscarSector													       

														,'onError':function(req)

														{ 

								                        	alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);

								                        }         

								             		}

								        		);

	}

	

}





function respBuscarSector(req)

{	

	/*

	ENTRADA: req (Objeto del resultado obtenido en la consulta SQL)

	SALIDA:

	DESCRIPCIÓN: permite verificar si el sector introducido se encuentra registrado en la BDD  

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 02-10-2012	

	*/

	

	var datos= eval ("("+req.responseText+")");

	//alert(datos.length+' ggg ');

	//var sector  = xGetElementById('txt_sector').value;

	if(datos == null)//no existe el sector

	{

		alert('El sector introducido no existe.\nVerifique');

		xGetElementById('txt_sector').value='';

		xGetElementById('txt_sector').focus();

	}

	

	else //si existe

	{

		global_id_sector = datos[0]['cod_sector'];

		xGetElementById('txt_sector').disabled=true;

		xGetElementById('txt_programa').focus();		

	}

	

	

}





function buscarPrograma() 

{

	/*

	ENTRADA: CAMPO (NOMBRE DEL CAMPO DE TEXTO), LIMITE(EL LIMITE DE CARACTERES ACEPTADO)

	SALIDA:

	DESCRIPCIÓN: FUNCIÓN QUE PERMITE CONTAR Y LIMITAR LOS CARACTERES DE UN CAMPO DE TEXTO

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 01-10-2012

	*/		

	

	var sector 	 = xGetElementById('txt_sector').value;

	var programa = xGetElementById('txt_programa').value;

	var url   = 'lib/transCreditoAdicional.php';

	var opx   = 'getPrograma';

	//alert(valor);

	var longitud = programa.length;

	//alert(longitud);

	

	if(longitud==2)//se han agregado 2 carateres

	{

		AjaxRequest.post

												(

								       				{

								       					'parameters':{'opcion':opx, 

																	  'sector':sector,

																	  'programa':programa}

								            			,'url':url

								            			,'onSuccess': respBuscarPrograma													       

														,'onError':function(req)

														{ 

								                        	alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);

								                        }         

								             		}

								        		);

	}

	

	if(longitud==0)//se no hay nada

	{	

		xGetElementById('txt_sector').disabled=false;

		xGetElementById('txt_sector').focus();

	}

	

}





function respBuscarPrograma(req)

{

	/*

	ENTRADA: req (Objeto del resultado obtenido en la consulta SQL)

	SALIDA:

	DESCRIPCIÓN: permite verificar si el programa introducido se encuentra registrado en la BDD  

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 03-10-2012

	*/

	var datos= eval ("("+req.responseText+")");

	//alert(datos.length+' ggg ');

	var sector  = xGetElementById('txt_sector').value;

	if(datos == null)//no existe el sector

	{

		alert('El programa introducido no existe para el sector '+sector+'\nVerifique');

		xGetElementById('txt_programa').value='';

		xGetElementById('txt_programa').focus();

	}

	

	else //si existe

	{

		global_id_programa = datos[0]['id_programa'];

		xGetElementById('txt_programa').disabled=true;

		xGetElementById('txt_sub_programa').focus();

	}

	//alert(global_id_programa);

}





function buscarSubPrograma() 

{

	/*

	ENTRADA: 

	SALIDA:

	DESCRIPCIÓN: FUNCIÓN QUE PERMITE CONTAR Y LIMITAR LOS CARACTERES DE UN CAMPO DE TEXTO

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 03-10-2012

	*/		

	

	//var sector 	 = xGetElementById('txt_sector').value;

	//var programa    = xGetElementById('txt_programa').value;

	var subPrograma = xGetElementById('txt_sub_programa').value;

	var url   	 = 'lib/transCreditoAdicional.php';

	var opx   	 = 'getSubPrograma';

	//alert(valor);

	var longitud = subPrograma.length;

	//alert(longitud);

	

	if(longitud==2)//se han agregado 2 carateres

	{

		AjaxRequest.post

												(

								       				{

								       					'parameters':{'opcion':opx, 

																	  'subPrograma':subPrograma,

																	  'programa':global_id_programa}

								            			,'url':url

								            			,'onSuccess': respBuscarSubPrograma													       

														,'onError':function(req)

														{ 

								                        	alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);

								                        }         

								             		}

								        		);

	}

	

	if(longitud==0)//se no hay nada

	{	

		xGetElementById('txt_programa').disabled=false;

		xGetElementById('txt_programa').focus();

	}

	

	

}



function respBuscarSubPrograma(req)

{

	/*

	ENTRADA: req (Objeto del resultado obtenido en la consulta SQL)

	SALIDA:

	DESCRIPCIÓN: permite verificar si el subprograma introducido se encuentra registrado en la BDD  

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 03-10-2012

	*/

	var datos= eval ("("+req.responseText+")");

	//alert(datos);

	var programa = xGetElementById('txt_programa').value;

	if(datos == null)//no existe el sector

	{

		alert('El Sub programa introducido no existe para el programa '+programa+'.\nVerifique');

		xGetElementById('txt_sub_programa').value='';

		xGetElementById('txt_sub_programa').focus();

	}

	

	else //si existe

	{

		global_id_sub_programa = datos[0]['id_sub'];

		xGetElementById('txt_sub_programa').disabled=true;

		xGetElementById('txt_proyecto').focus();

	}

}





function buscarProyecto() 

{

	/*

	ENTRADA: 

	SALIDA:

	DESCRIPCIÓN: FUNCIÓN QUE PERMITE CONTAR Y LIMITAR LOS CARACTERES DE UN CAMPO DE TEXTO

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 03-10-2012

	*/		

	

	

	var proyecto = xGetElementById('txt_proyecto').value;

	var url   	 = 'lib/transCreditoAdicional.php';

	var opx   	 = 'getProyecto';

	//alert(valor);

	var longitud = proyecto.length;

	//alert(longitud);

	

	if(longitud==2)//se han agregado 2 carateres

	{

		AjaxRequest.post

												(

								       				{

								       					'parameters':{'opcion':opx, 

																	  'subPrograma':global_id_sub_programa,

																	  'proyecto':proyecto}

								            			,'url':url

								            			,'onSuccess': respBuscarProyecto												       

														,'onError':function(req)

														{ 

								                        	alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);

								                        }         

								             		}

								        		);

	}	

	

	if(longitud==0)//se no hay nada

	{	

		xGetElementById('txt_sub_programa').disabled=false;

		xGetElementById('txt_sub_programa').focus();

	}

}



function respBuscarProyecto(req)

{

	/*

	ENTRADA: req (Objeto del resultado obtenido en la consulta SQL)

	SALIDA:

	DESCRIPCIÓN: permite verificar si el proyecto introducido se encuentra registrado en la BDD  

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 03-10-2012

	*/

	var datos= eval ("("+req.responseText+")");

	//alert(datos);

	var subprograma = xGetElementById('txt_sub_programa').value;

	if(datos == null)//no existe el sector

	{

		alert('El proyecto introducido no existe para el sub programa '+subprograma+'.\nVerifique');

		xGetElementById('txt_proyecto').value='';

		xGetElementById('txt_proyecto').focus();

	}

	

	else //si existe

	{

		global_id_proyecto = datos[0]['id_proyecto'];

		xGetElementById('txt_proyecto').disabled=true;

		xGetElementById('txt_actividad').focus();

	}

}





function buscarActividad() 

{

	/*

	ENTRADA: 

	SALIDA:

	DESCRIPCIÓN: FUNCIÓN QUE PERMITE CONTAR Y LIMITAR LOS CARACTERES DE UN CAMPO DE TEXTO

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 03-10-2012

	*/		

	

	

	var actividad = xGetElementById('txt_actividad').value;

	var url   	 = 'lib/transCreditoAdicional.php';

	var opx   	 = 'getActividad';

	//alert(valor);

	var longitud = actividad.length;

	//alert(longitud);

	

	if(longitud==3)//se han agregado 3 carateres

	{

		AjaxRequest.post

												(

								       				{

								       					'parameters':{'opcion':opx, 

																	  'actividad':actividad,

																	  'proyecto':global_id_proyecto}

								            			,'url':url

								            			,'onSuccess': respBuscarActividad												       

														,'onError':function(req)

														{ 

								                        	alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);

								                        }         

								             		}

								        		);

	}	

	

	if(longitud==0)//se no hay nada

	{	

		xGetElementById('txt_proyecto').disabled=false;

		xGetElementById('txt_proyecto').focus();

	}

	

			

}





function respBuscarActividad(req)

{

	/*

	ENTRADA: req (Objeto del resultado obtenido en la consulta SQL)

	SALIDA:

	DESCRIPCIÓN: permite verificar si la actividad introducida se encuentra registrado en la BDD  

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 03-10-2012

	*/

	var datos= eval ("("+req.responseText+")");

	//alert(datos);

	var proyecto = xGetElementById('txt_proyecto').value;

	if(datos == null)//no existe el sector

	{

		alert('La actividad introducida no existe para el proyecto '+proyecto+'.\nVerifique');

		xGetElementById('txt_actividad').value='';

		xGetElementById('txt_actividad').focus();

	}

	

	else //si existe

	{

		gloval_id_actividad = datos[0]['id_actividad'];

		xGetElementById('txt_actividad').disabled=true;

		xGetElementById('txt_partida').focus();

	}

}









function buscarPartida() 

{

	/*

	ENTRADA: 

	SALIDA:

	DESCRIPCIÓN: FUNCIÓN QUE PERMITE CONTAR, LIMITAR LOS CARACTERES DE UN CAMPO DE TEXTO Y BUSCAR LA PARTIDA INTRODUCIDA

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 04-10-2012

	*/		

	

	var presupuesto     =   xGetElementById('txt_nro_presupuesto').value; 	

	var partida 		= 	xGetElementById('txt_partida').value;

	var general 		= 	xGetElementById('txt_general').value;

	var especifico 		= 	xGetElementById('txt_especifico').value;

	var sub_especifico 	= 	xGetElementById('txt_sub_especifico').value;

	var ordinal 		= 	xGetElementById('txt_ordinal').value;// HASTA LOS MOMENTO NO SE ESTA APLICANDO EL ORDINAL 

	

	var codPartida 		=    partida+'.'+general+'.'+especifico+'.'+sub_especifico;

	

	var url   	 = 'lib/transCreditoAdicional.php';

	var opx   	 = 'getPartida';

	var longitud = ordinal.length;

	//alert(valor);

	/*var tipoCuenta = partida.charAt(0);

	var partida1 = partida.substr(1, 2);

	*/

	//alert(tipoCuenta+' '+partida1);

	//alert(longitud);

	

	if(longitud==3)//se han agregado 3 carateres

	{

		AjaxRequest.post

												(

								       				{

								       					'parameters':{'opcion':opx, 

																	  'partida':codPartida,
																	  'presupuesto':presupuesto}

								            			,'url':url

								            			,'onSuccess': respBuscarPartida												       

														,'onError':function(req)

														{ 

								                        	alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);

								                        }         

								             		}

								        		);

	}	

	

	if(longitud==0)//se no hay nada

	{	

		xGetElementById('txt_actividad').disabled=false;

		xGetElementById('txt_actividad').focus();

	}

	

	

}



function respBuscarPartida(req)

{

	/*

	ENTRADA: req (Objeto del resultado obtenido en la consulta SQL)

	SALIDA:

	DESCRIPCIÓN: permite verificar si la actividad introducida se encuentra registrado en la BDD  

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 03-10-2012

	*/

	

	

	

	var datos= eval ("("+req.responseText+")");

	//alert(datos);

	//var proyecto = xGetElementById('txt_proyecto').value;

	if(datos == null)//no existe el sector

	{

		alert('La partida introducida no existe. \nVerifique');		

		

		

		limpiarCampoPartida();

		desbloquearCampoPartida();



		xGetElementById('txt_partida').focus();

	}

	

	else //si existe

	{

		global_id_partida = datos[0]['cod_partida'];

		/*xGetElementById('txt_partida').disabled=true;*/

		xGetElementById('txt_monto_item').focus();

		

		xGetElementById('td_descripcion_partida').innerHTML=datos[0]['denominacion'];

	}

}





function limpiarCampoPartida()

{

	/*

	ENTRADA: CAMPO (NOMBRE DEL CAMPO DE TEXTO), LIMITE(EL LIMITE DE CARACTERES ACEPTADO)

	SALIDA:

	DESCRIPCIÓN: FUNCIÓN QUE PERMITE CONTAR Y LIMITAR LOS CARACTERES DE UN CAMPO DE TEXTO

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 01-10-2012

	*/	

		xGetElementById('txt_partida').value='4';

		xGetElementById('txt_general').value='';

		xGetElementById('txt_especifico').value='';

		xGetElementById('txt_sub_especifico').value='';

		xGetElementById('txt_ordinal').value='';// HASTA LOS MOMENTO NO SE ESTA APLICANDO EL ORDINAL 

		xGetElementById('txt_monto_item').value='0,00';

		xGetElementById('td_descripcion_partida').innerHTML='';

		global_id_partida = '';

}



function desbloquearCampoPartida()

{

	/*

	ENTRADA: CAMPO (NOMBRE DEL CAMPO DE TEXTO), LIMITE(EL LIMITE DE CARACTERES ACEPTADO)

	SALIDA:

	DESCRIPCIÓN: FUNCIÓN QUE PERMITE CONTAR Y LIMITAR LOS CARACTERES DE UN CAMPO DE TEXTO

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 01-10-2012

	*/	

		xGetElementById('txt_partida').disabled=false;

		xGetElementById('txt_general').disabled=false;

		xGetElementById('txt_especifico').disabled=false;

		xGetElementById('txt_sub_especifico').disabled=false;

		xGetElementById('txt_ordinal').disabled=false;// HASTA LOS MOMENTO NO SE ESTA APLICANDO EL ORDINAL 

}





function validarCampoCreditoAdicional()

{

	/*
	ENTRADA: 
	SALIDA:
	DESCRIPCIÓN: FUNCIÓN QUE PERMITE VALIDAR LOS CAMPOS DEL CREDITO ADICIONAL.
	PROGRAMADOR: ERNESTO RIVAS
	fecha: 16-10-2012

	*/		

	var resolucion		=	xGetElementById('gaceta').value;

	var fechaResolucion	=	xGetElementById('fgaceta').value;

		fechaResolucion	=	fechaJStoMySql(fechaResolucion);

		

	var decreto			=	xGetElementById('decreto').value;

	var fechaDecreto	=	xGetElementById('fdecreto').value;

		fechaDecreto	=	fechaJStoMySql(fechaDecreto);
		
		

	var monto	  		= 	xGetElementById('txt_monto').value;

	var ejercicioPpto	=	xGetElementById('ejercicioPpto').value;

	var fechaCreacion	=	xGetElementById('fcreacion').value;

	var estatus			=	xGetElementById('estado').value;

	var motivo  		= 	xGetElementById('txt_motivo').value;	

	var movimineto		=	OBJ_CREDITO_ADICIONAL.co_partida.length;

	var totalAgregado	=	xGetElementById('totalMonto').innerHTML;

	var aprobado		=	xGetElementById('nomempleado').value;

	

	//alert(totalAgregado+' '+monto)

	

		
	if(!campoVacio(resolucion))
	{
		alert('Debes introducir el número de oficio');		

		xGetElementById('gaceta').focus();

		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 
	
	}
	
	else if(!campoVacio(fechaResolucion))//
	{
		alert('Debes introducir la fecha de la resolución');		

		xGetElementById('fgaceta').focus();

		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 
	}
	
	else if(!campoVacio(decreto))//
	{
		alert('Debes introducir el número de decreto');		

		xGetElementById('decreto').focus();

		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 
	}
	
	else if(!campoVacio(fechaDecreto))//
	{
		alert('Debes introducir el número de decreto');		

		xGetElementById('fdecreto').focus();

		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 
	}

	
	else if(monto=='0,00')	

	{

		alert('Debes introducir el monto total del crédito adicional');	

		xGetElementById('txt_monto').focus();

		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}
	
	else if(!campoVacio(ejercicioPpto))

	{

		alert('Debes introducir el periodo de ejecucón');		

		xGetElementById('ejercicioPpto').focus();

		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	

	else if(!campoVacio(fechaCreacion))

	{

		alert('Debes introducir la fecha de creación del crédito adicional');		

		xGetElementById('fcreacion').focus();

		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	

	else if(!campoVacio(motivo))

	{

		alert('Debes introducir el motivo del crédito adicional');		

		xGetElementById('txt_motivo').focus();

		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	

	else if(movimineto<1)

	{

		alert('No existe movimientos agregado');	

		xGetElementById('txt_sector').focus();

		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	

	else if(totalAgregado!=monto)

	{

		

		alert('El monto total es distinto al monto distribuido');	

		xGetElementById('txt_monto').focus();

		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	

	else if(!campoVacio(aprobado))

	{

		alert('Debes introducir por quien se debe aprobar el crédito adicional');	

		xGetElementById('nomempleado').focus();

		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

		

	else

	{		

		guardarCreditoAdicional();		

	} 

}





function guardarCreditoAdicional()

{		

	var organismo		=	xGetElementById('organismo').value;

	

	var resolucion		=	xGetElementById('gaceta').value;

	var fechaResolucion	=	xGetElementById('fgaceta').value;

		fechaResolucion	=	fechaJStoMySql(fechaResolucion);

		

	var decreto			=	xGetElementById('decreto').value;

	var fechaDecreto	=	xGetElementById('fdecreto').value;

		fechaDecreto	=	fechaJStoMySql(fechaDecreto);

	

	var codPresupuesto  =  xGetElementById('txt_nro_presupuesto').value;

	

	var monto	  		= 	xGetElementById('txt_monto').value;

		monto=quitarMiles(monto);

		monto = monto.replace(',','.');

		

		

		//alert(monto);

	

	

	var motivo  		= 	xGetElementById('txt_motivo').value;

	var movimineto		=	OBJ_CREDITO_ADICIONAL.nu_partida.length;

	

	var ejercicio		=	xGetElementById('ejercicioPpto').value;

	var fcreacion		=	xGetElementById('fcreacion').value;

		fcreacion		=	fechaJStoMySql(fcreacion);

		//alert(fcreacion);

	

	var estatus			=	xGetElementById('co_estado').value;

	

	var totalAgregado	=	xGetElementById('totalMonto').value;

	var aprobado		=	xGetElementById('codempleado').value;

	var preparado 		=	xGetElementById('codprepor').value;

	

	

	var ultimaMod		=	xGetElementById('ult_usuario').value;

	var fechaUltimaMod	=	xGetElementById('ult_fecha').value;		

		fechaUltimaMod	=	fechaJStoMySql(fechaUltimaMod);

		

		



	//alert('estamos listo para guardar');

	

	

		

		

	

	if(confirm('¿Esta seguro de guardar el crédito adicional?'))

	{	
			for(var i=0; i<OBJ_CREDITO_ADICIONAL.monto.length;i++)

			{

				var montoItem=OBJ_CREDITO_ADICIONAL.monto[i];

					montoItem=quitarMiles(montoItem);

					//monto = monto.replace(',','.');	

				//OBJ_CREDITO_ADICIONAL.monto[i]='';

				OBJ_CREDITO_ADICIONAL.monto[i]=	montoItem+';';

				

				//alert(OBJ_CREDITO_ADICIONAL.monto[i]);

			}

			var opx	=	'guardar';

			

			//alert(opx);

			var url 	=	'lib/transCreditoAdicional.php';

			AjaxRequest.post

														(

															{

																'parameters':{'opcion':opx,

																			  'organismo':organismo,
																			  
																			  'codPresupuesto':codPresupuesto,

																			  'resolucion': resolucion,

																			  'fechaResolucion':fechaResolucion,

																			  'decreto':decreto,

																			  'fechaDecreto':fechaDecreto,																	  

																			  'monto':monto,

																			  'motivo':motivo,

																			  'ejercicio':ejercicio,

																			  'fcreacion':fcreacion,

																			  'estatus':estatus,																	  

																			  'totalAgregado':totalAgregado,

																			  'aprobado':aprobado,

																			  'preparado':preparado,

																			  'ultimaMod':ultimaMod,

																			  'fechaUltimaMod':fechaUltimaMod,

																			  //'co_sector':OBJ_CREDITO_ADICIONAL.co_sector,

																			  //'co_programa':OBJ_CREDITO_ADICIONAL.co_programa,

																			  //'co_sub_programa':OBJ_CREDITO_ADICIONAL.co_sub_programa,

																			 // 'co_proyecto':OBJ_CREDITO_ADICIONAL.co_proyecto,

																			 // 'co_actividad':OBJ_CREDITO_ADICIONAL.co_actividad,

																			  'co_partida':OBJ_CREDITO_ADICIONAL.co_partida,

																			  'monto_item':OBJ_CREDITO_ADICIONAL.monto																	  

																			  }

																,'url':url

																,'onSuccess': respGuardarCreditoAdicional												       

																,'onError':function(req)

																{ 

																	alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);

																}         

															}

														);

	}

	

}



function respGuardarCreditoAdicional(req)

{

	//var datos= eval ("("+req.responseText+")");

	//alert(req.responseText);

	if(req.responseText == '1')

	{

		alert('Se ha guardado con exito el crédito adicional');

		limpiarCamposCreditoAdcional();

	}

	else

	{

		alert('Error al guardar crédito adicional');

	}

	

}









/////////////////////////////////////////////////////////////////////////////////////////

//////////////MODIFICAR CREDITO ADICIONAL////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////







function validarCampoCreditoAdicionalEditar()

{

	/*

	ENTRADA: 

	SALIDA:

	DESCRIPCIÓN: FUNCIÓN QUE PERMITE VALIDAR LOS CAMPOS DEL CREDITO ADICIONAL.

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 16-10-2012

	*/		

	

	var monto	  		= 	xGetElementById('txt_monto').value;

	var ejercicioPpto	=	xGetElementById('ejercicioPpto').value;

	var fechaCreacion	=	xGetElementById('fcreacion').value;

	var estatus			=	xGetElementById('estado').value;

	var motivo  		= 	xGetElementById('txt_motivo').value;	

	var movimineto		=	OBJ_CREDITO_ADICIONAL.co_partida.length;

	var totalAgregado	=	xGetElementById('totalMonto').innerHTML;

	var aprobado		=	xGetElementById('nomempleado').value;

	

	//alert(totalAgregado+' '+monto)

	

	if(monto=='0,00')	

	{

		alert('Debes introducir el monto total del crédito adicional');	

		xGetElementById('txt_monto').focus();

		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	

	else if(!campoVacio(ejercicioPpto))

	{

		alert('Debes introducir el periodo de ejecucón');		

		xGetElementById('ejercicioPpto').focus();

		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	

	else if(!campoVacio(fechaCreacion))

	{

		alert('Debes introducir la fecha de creación del crédito adicional');		

		xGetElementById('fcreacion').focus();

		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	

	else if(!campoVacio(motivo))

	{

		alert('Debes introducir el motivo del crédito adicional');		

		xGetElementById('txt_motivo').focus();

		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	

	else if(movimineto<1)

	{

		alert('No existe movimientos agregado');	

		xGetElementById('txt_sector').focus();

		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	

	else if(totalAgregado!=monto)

	{

		

		alert('El monto total es distinto al monto distribuido');	

		xGetElementById('txt_monto').focus();

		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	

	else if(!campoVacio(aprobado))

	{

		alert('Debes introducir por quien se debe aprobar el crédito adicional');	

		xGetElementById('nomempleado').focus();

		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

		

	else

	{		

		guardarCreditoAdicionalEditar();		

	} 

}





function guardarCreditoAdicionalEditar()

{		

	var coCreditoAdicional  = xGetElementById('hid_codigo').value;

	var organismo			=	xGetElementById('organismo').value;	

	var resolucion			=	xGetElementById('gaceta').value;

	var fechaResolucion		=	xGetElementById('fgaceta').value;

		fechaResolucion		=	fechaJStoMySql(fechaResolucion);

		

	var decreto				=	xGetElementById('decreto').value;

	var fechaDecreto		=	xGetElementById('fdecreto').value;

		fechaDecreto		=	fechaJStoMySql(fechaDecreto);		

	

	

	var monto	  			= 	xGetElementById('txt_monto').value;

		monto=quitarMiles(monto);

		monto = monto.replace(',','.');

	

	

	var motivo  			= 	xGetElementById('txt_motivo').value;

	var movimineto			=	OBJ_CREDITO_ADICIONAL.co_partida.length;

	

	var ejercicio			=	xGetElementById('ejercicioPpto').value;

	var fcreacion			=	xGetElementById('fcreacion').value;

		fcreacion			=	fechaJStoMySql(fcreacion);

		//alert(fcreacion);

	

	var estatus				=	xGetElementById('estado').value;

	

	var totalAgregado		=	xGetElementById('totalMonto').value;

	var aprobado			=	xGetElementById('codempleado').value;

	var preparado 			=	xGetElementById('codprepor').value;

	

	

	var ultimaMod			=	xGetElementById('ult_usuario').value;

	var fechaUltimaMod		=	xGetElementById('ult_fecha').value;		

		fechaUltimaMod		=	fechaJStoMySql(fechaUltimaMod);

		

		



	//alert('estamos listo para guardar');

	

	

		

		

	

	if(confirm('¿Esta seguro de modificar el crédito adicional?'))

	{	

	

	

			for(var i=0; i<OBJ_CREDITO_ADICIONAL.monto.length;i++)

			{

				var montoItem=OBJ_CREDITO_ADICIONAL.monto[i];

					montoItem=quitarMiles(montoItem);

					//monto = monto.replace(',','.');	

				//OBJ_CREDITO_ADICIONAL.monto[i]='';

				OBJ_CREDITO_ADICIONAL.monto[i]=	montoItem+' ';

				

				//alert(OBJ_CREDITO_ADICIONAL.monto[i]);

			}

			var opx	=	'modificar';

			var url 	=	'lib/transCreditoAdicional.php';

			AjaxRequest.post

														(

															{

																'parameters':{'opcion':opx,

																			  'coCreditoAdicional':coCreditoAdicional,

																			  'organismo':organismo,

																			  'resolucion': resolucion,

																			  'fechaResolucion':fechaResolucion,

																			  'decreto':decreto,

																			  'fechaDecreto':fechaDecreto,																	  

																			  'monto':monto,

																			  'motivo':motivo,

																			  'ejercicio':ejercicio,

																			  'fcreacion':fcreacion,

																			  'estatus':estatus,																	  

																			  'totalAgregado':totalAgregado,

																			  'aprobado':aprobado,

																			  'preparado':preparado,

																			  'ultimaMod':ultimaMod,

																			  'fechaUltimaMod':fechaUltimaMod,

																			//  'co_sector':OBJ_CREDITO_ADICIONAL.co_sector,

																			  //'co_programa':OBJ_CREDITO_ADICIONAL.co_programa,

																			  //'co_sub_programa':OBJ_CREDITO_ADICIONAL.co_sub_programa,

																			  //'co_proyecto':OBJ_CREDITO_ADICIONAL.co_proyecto,

																			 // 'co_actividad':OBJ_CREDITO_ADICIONAL.co_actividad,

																			  'co_partida':OBJ_CREDITO_ADICIONAL.co_partida,

																			  'monto_item':OBJ_CREDITO_ADICIONAL.monto																	  

																			  }

																,'url':url

																,'onSuccess': respGuardarCreditoAdicionalEditar											       

																,'onError':function(req)

																{ 

																	alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);

																}         

															}

														);

	}

	

}



function respGuardarCreditoAdicionalEditar(req)

{

	//var datos= eval ("("+req.responseText+")");

	//alert(req.responseText);

	if(req.responseText == '1')

	{

		alert('Se ha modificado con exito el crédito adicional');

		limpiarCamposCreditoAdcional();

	}

	else

	{

		alert('Error al modificar crédito adicional');

	}

	

}









/////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////











/////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////





function validarCampoCreditoAdicionalAprobar(form)

{

	/*

	ENTRADA: 

	SALIDA:

	DESCRIPCIÓN: FUNCIÓN QUE PERMITE VALIDAR LOS CAMPOS DEL CREDITO ADICIONAL.

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 16-10-2012

	*/		

	

	var gaceta	  	= 	xGetElementById('gaceta').value;

	var fgaceta		=	xGetElementById('fgaceta').value;

	var decreto		=	xGetElementById('decreto').value;

	var fdecreto	=	xGetElementById('fdecreto').value;

	

	

	//alert(totalAgregado+' '+monto)

	

		

	if(!campoVacio(gaceta))

	{

		alert('Debes introducir el numero de gaceta');		

		xGetElementById('gaceta').focus();

		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	

	else if(!campoVacio(fgaceta))

	{

		alert('Debes introducir la fecha de la gaceta');		

		xGetElementById('fgaceta').focus();

		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	

	else if(!campoVacio(decreto))

	{

		alert('Debes introducir el número de decreto');		

		xGetElementById('decreto').focus();

		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}		

	

	else if(!campoVacio(fdecreto))

	{

		alert('Debes introducir la fecha de decreto');	

		xGetElementById('fdecreto').focus();

		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

		

	else

	{		

		guardarCreditoAdicionalAprobar(form);		

	} 

}





function guardarCreditoAdicionalAprobar(form)
{		

	var coCreditoAdicional  	= 	xGetElementById('hid_codigo').value;

	var organismo			=	xGetElementById('organismo').value;	

	var resolucion			=	xGetElementById('gaceta').value;

	var fechaResolucion		=	xGetElementById('fgaceta').value;

		fechaResolucion		=	fechaJStoMySql(fechaResolucion);

		

	var decreto				=	xGetElementById('decreto').value;

	var fechaDecreto		=	xGetElementById('fdecreto').value;

		fechaDecreto		=	fechaJStoMySql(fechaDecreto);		

	

	

	var monto	  			= 	xGetElementById('txt_monto').value;

		monto=quitarMiles(monto);

		monto = monto.replace(',','.');

	

	

	var motivo  			= 	xGetElementById('txt_motivo').value;

	var movimineto			=	OBJ_CREDITO_ADICIONAL.co_partida.length;

	var partida				=	OBJ_CREDITO_ADICIONAL.co_partida;

	//var monto_item;

	var ejercicio			=	xGetElementById('ejercicioPpto').value;

	var fcreacion			=	xGetElementById('fcreacion').value;

		fcreacion			=	fechaJStoMySql(fcreacion);

		//alert(fcreacion);

	

	var estatus				=	xGetElementById('estado').value;

	

	var totalAgregado		=	xGetElementById('totalMonto').value;

	var aprobado			=	xGetElementById('codempleado').value;

	var preparado 			=	xGetElementById('codprepor').value;

	

	

	var ultimaMod			=	xGetElementById('ult_usuario').value;

	var fechaUltimaMod		=	xGetElementById('ult_fecha').value;		

		fechaUltimaMod		=	fechaJStoMySql(fechaUltimaMod);

		
	var codPresupuesto  =  xGetElementById('txt_nro_presupuesto').value;
	
	var organismo			=	xGetElementById('organismo').value;	
		



	//alert('estamos listo para guardar');

	

	

		for(var i=0; i<OBJ_CREDITO_ADICIONAL.monto.length;i++)

			{

				var montoItem=OBJ_CREDITO_ADICIONAL.monto[i];

					montoItem=quitarMiles(montoItem);

					//monto = monto.replace(',','.');	

				//OBJ_CREDITO_ADICIONAL.monto[i]='';

				OBJ_CREDITO_ADICIONAL.monto[i] =	montoItem+';';

				

				//alert(OBJ_CREDITO_ADICIONAL.monto[i]);

			}

		

	

	if(confirm('¿Esta seguro de APROBAR el crédito adicional?'))

	{	

	

		//aprobarCreditoAdicional(form);

			

			var opx	=	'aprobar';

			var url 	=	'lib/transCreditoAdicional.php';

			AjaxRequest.post

														(

															{

																'parameters':{'opcion':opx,

																			  'coCreditoAdicional':coCreditoAdicional,

																			  'organismo':organismo,

																			  'resolucion': resolucion,

																			  'fechaResolucion':fechaResolucion,

																			  'decreto':decreto,

																			  'fechaDecreto':fechaDecreto,																	  

																			  'monto':monto,

																			  'motivo':motivo,

																			  'ejercicio':ejercicio,

																			  'fcreacion':fcreacion,

																			  'estatus':estatus,																	  

																			  'totalAgregado':totalAgregado,

																			  'aprobado':aprobado,

																			  'preparado':preparado,
																			  
																			  'co_partida': partida,

																			  'monto_item': OBJ_CREDITO_ADICIONAL.monto,
																			  
																			  'codPresupuesto': codPresupuesto,	
																			  
																			  'organismo': organismo,

																			  'ultimaMod':ultimaMod,

																			  'fechaUltimaMod':fechaUltimaMod,																			 																	  

																			  }

																,'url':url

																,'onSuccess': function(req){respGuardarCreditoAdicionalAprobar(req,form)}

																,'onError':function(req)

																{ 

																	alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);

																}         

															}

														);

	}

	


}



function respGuardarCreditoAdicionalAprobar(req)

{

	//var datos= eval ("("+req.responseText+")");

	//alert(req.responseText);

	if(req.responseText == '1')

	{

		alert('Se ha APROBADO con exito el crédito adicional');

		window.close();

				

	}

	else

	{

		alert('Error al APROBAR el crédito adicional');

	}

	

	var form = document.forms['frmentrada'];

     /*if (!theForm) {

         theForm = document.form1;

     }*/

    // theForm.submit();

	
 	location.replace('creditoAdicionalAprobar.php');
	 //window.opener.location.reload('creditoAdicionalAprobar.php');

	 

	  //window.opener.location.href='creditoAdicionalAprobar.php';

	 

	//window.opener.cargarPagina(form,"creditoAdicionalAprobar.php?limite=0' method='POST'");

	

}














function validarCampoCreditoAdicionalConformar(form)
{

	alert('sdfsdfsdfsdfsdfsdfsdf');
	/*
	ENTRADA: 
	SALIDA:
	DESCRIPCIÓN: FUNCIÓN QUE PERMITE VALIDAR LOS CAMPOS DEL CREDITO ADICIONAL.
	PROGRAMADOR: ERNESTO RIVAS
	fecha: 16-10-2012
	*/		

	

	var gaceta	  	= 	xGetElementById('gaceta').value;
	var fgaceta		=	xGetElementById('fgaceta').value;
	var decreto		=	xGetElementById('decreto').value;
	var fdecreto	=	xGetElementById('fdecreto').value;

	


	//alert(totalAgregado+' '+monto)

	

		

	if(!campoVacio(gaceta))

	{

		alert('Debes introducir el numero de gaceta');		

		xGetElementById('gaceta').focus();

		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	

	else if(!campoVacio(fgaceta))

	{

		alert('Debes introducir la fecha de la gaceta');		

		xGetElementById('fgaceta').focus();

		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

	

	else if(!campoVacio(decreto))

	{

		alert('Debes introducir el número de decreto');		

		xGetElementById('decreto').focus();

		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}		

	

	else if(!campoVacio(fdecreto))

	{

		alert('Debes introducir la fecha de decreto');	

		xGetElementById('fdecreto').focus();

		//bandera = 0;//bandera que permite asegurar que se entro en una condicion 

	}

		

	else

	{		

		guardarCreditoAdicionalConformar(form);		

	} 

}





function guardarCreditoAdicionalConformar(form)

{		

	var coCreditoAdicional  = xGetElementById('hid_codigo').value;

	var organismo			=	xGetElementById('organismo').value;	

	var resolucion			=	xGetElementById('gaceta').value;

	var fechaResolucion		=	xGetElementById('fgaceta').value;

		fechaResolucion		=	fechaJStoMySql(fechaResolucion);

		

	var decreto				=	xGetElementById('decreto').value;

	var fechaDecreto		=	xGetElementById('fdecreto').value;

		fechaDecreto		=	fechaJStoMySql(fechaDecreto);		

	

	

	var monto	  			= 	xGetElementById('txt_monto').value;

		monto=quitarMiles(monto);

		monto = monto.replace(',','.');

	

	

	var motivo  			= 	xGetElementById('txt_motivo').value;

	var movimineto			=	OBJ_CREDITO_ADICIONAL.co_partida.length;

	

	var ejercicio			=	xGetElementById('ejercicioPpto').value;

	var fcreacion			=	xGetElementById('fcreacion').value;

		fcreacion			=	fechaJStoMySql(fcreacion);

		//alert(fcreacion);

	

	var estatus				=	xGetElementById('estado').value;

	

	var totalAgregado		=	xGetElementById('totalMonto').value;

	var aprobado			=	xGetElementById('codempleado').value;

	var preparado 			=	xGetElementById('codprepor').value;

	

	

	var ultimaMod			=	xGetElementById('ult_usuario').value;

	var fechaUltimaMod		=	xGetElementById('ult_fecha').value;		

		fechaUltimaMod		=	fechaJStoMySql(fechaUltimaMod);

		

		



	//alert('estamos listo para guardar');

	

	

		

		

	

	if(confirm('¿Esta seguro de CONFORMAR el crédito adicional?'))

	{	
		//aprobarCreditoAdicional(form);

			var opx	=	'conformar';
			var url 	=	'lib/transCreditoAdicional.php';

			AjaxRequest.post

														(

															{

																'parameters':{'opcion':opx,

																			  'coCreditoAdicional':coCreditoAdicional,

																			  'organismo':organismo,

																			  'resolucion': resolucion,

																			  'fechaResolucion':fechaResolucion,

																			  'decreto':decreto,

																			  'fechaDecreto':fechaDecreto,																	  

																			  'monto':monto,

																			  'motivo':motivo,

																			  'ejercicio':ejercicio,

																			  'fcreacion':fcreacion,

																			  'estatus':estatus,																	  

																			  'totalAgregado':totalAgregado,

																			  'aprobado':aprobado,

																			  'preparado':preparado,

																			  'ultimaMod':ultimaMod,

																			  'fechaUltimaMod':fechaUltimaMod,																			 																	  

																			  }

																,'url':url

																,'onSuccess': function(req){respGuardarCreditoAdicionalConformar(req,form)}

																,'onError':function(req)

																{ 

																	alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);

																}         

															}

														);

	}

	

}



function respGuardarCreditoAdicionalConformar(req)

{

	//var datos= eval ("("+req.responseText+")");

	//alert(req.responseText);

	if(req.responseText == '1')

	{

		alert('Se ha Conformado con exito el crédito adicional');

		//window.close();

				

	}

	else

	{

		alert('Error al Conformar el crédito adicional');

	}

	

	var form = document.forms['frmentrada'];

     /*if (!theForm) {

         theForm = document.form1;

     }*/

    // theForm.submit();

	

	 //window.opener.location.reload('creditoAdicionalAprobar.php');

	 location.replace('creditoAdicionalAprobar.php');

	  //window.opener.location.href='creditoAdicionalAprobar.php';

	 

	//window.opener.cargarPagina(form,"creditoAdicionalAprobar.php?limite=0' method='POST'");

	

}















/////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////




function cargarDatosCreditoAdicional(codigo,cond)
{
	//alert(codigo);

	var url   	 = 'lib/transCreditoAdicional.php';
	var opx   	 = 'buscarCreditoAdicional';
	AjaxRequest.post
												(
								       				{
								       					'parameters':{'opcion':opx, 
																	  'codigo':codigo}
								            			,'url':url
								            			,'onSuccess': function(req) {respCargarDatosCreditoAdicional(req,cond)}											       
														,'onError':function(req)
														{ 
								                        	alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
								                        }         
								             		}
								        		);

	

	

	

}





function respCargarDatosCreditoAdicional(req, cond)

{

	
	var datos= eval ("("+req.responseText+")");


	var filas = datos.length;

	//alert(datos[0]['tx_estatus']);
	if((datos[0]['tx_estatus']=='AP' || datos[0]['tx_estatus']=='AN')&& cond!='VER')
	{
		alert('NO SE PUEDE EDITAR');
		var form = document.forms['frmentrada'];     
	 	location.replace('creditoAdicionalListar.php');
	}
	else
	{		
		///////////////////////////////////////////////////
		//para buscar los item del este credito adicional//
		///////////////////////////////////////////////////	
			buscarItemCreditoAdicional(datos[0]['co_credito_adicional'],cond);	
		/////////////////////////////////////////////////
	
		
	
		
	
		///////////////////////////////////////////////////
	
		//PARA BUSCAR QUE VA APROBAR EL CREDITO ADICIONAL//
	
		///////////////////////////////////////////////////
	
			var aprobado = datos[0]['tx_aprobado'];		
	
			//alert(aprobado);
	
			xGetElementById('codempleado').value=aprobado;
	
			buscarAprobarCreditoAdicional(aprobado);
	
		
	
		////////////////////////////////////////////////////////////////////////////////////////
	
		xGetElementById('organismo').value=datos[0]['CodOrganismo'];
	
		
	
		                        xGetElementById('gaceta').value = datos[0]['nu_oficio'];
	
		var fechaResolucion	=	datos[0]['ff_oficio'];
	
			fechaResolucion	=	fechaJStoMySql(fechaResolucion);
	
			xGetElementById('fgaceta').value = fechaResolucion;
			// fechaJStoMySql(fecha)
	
			
	
		var decreto			=	xGetElementById('decreto').value=datos[0]['nu_decreto'];
	
		var fechaDecreto	=	datos[0]['ff_decreto'];
	
			fechaDecreto	=	fechaJStoMySql(fechaDecreto);
			xGetElementById('fdecreto').value=fechaDecreto;
	
		
	
		
	
		
	
		
	
		xGetElementById('txt_monto').value=toCurrency(datos[0]['mm_monto_total']);
	
		xGetElementById('txt_motivo').value=datos[0]['tx_motivo'];
	
		
	
		
	
		xGetElementById('ejercicioPpto').value=datos[0]['ff_ejecucion'];
	
		var fcreacion		=	fechaJStoMySql(datos[0]['ff_creacion']);
	
			xGetElementById('fcreacion').value = fcreacion;
	
			
	
			//alert(fcreacion);
	
		
	
		if(datos[0]['tx_estatus']=='PE')
	
			xGetElementById('estado').value = 'Preparado';
	
		else if(datos[0]['tx_estatus']=='RV')
	
			xGetElementById('estado').value = 'Revisado';
	
		else if(datos[0]['tx_estatus']=='AP')
	
			xGetElementById('estado').value = 'Aprobado';
	
		else if(datos[0]['tx_estatus']=='GE')
	
			xGetElementById('estado').value = 'Generado';
	
		else
	
			xGetElementById('estado').value = 'Anulado';
	
			
	
			
	
		
	
		//var totalAgregado	=	xGetElementById('totalMonto').value;
	
		//alert(datos[0]['tx_preparado']);
	
	
		xGetElementById('codprepor').value = datos[0]['tx_preparado'];
		xGetElementById('fecha_preperada').value = datos[0]['ff_creacion'];//VOY

		

		if(datos[0]['ff_revisada']=='0000-00-00 00:00:00')
			xGetElementById('fecha_revisada').value = '';
		else
			xGetElementById('fecha_revisada').value =datos[0]['ff_revisada'];

		if(datos[0]['ff_conformada']=='0000-00-00 00:00:00')
			xGetElementById('fecha_conformada').value ='';
		else
			xGetElementById('fecha_conformada').value = datos[0]['ff_conformada'];

		if(datos[0]['ff_aprobada']=='0000-00-00 00:00:00')
			xGetElementById('fecha_aprobada').value = '';
		else
			xGetElementById('fecha_aprobada').value = datos[0]['ff_aprobada'];

			
		xGetElementById('prepor').value= datos[0]['NomCompleto'];
	
		
		
	
		
	
		
	
		xGetElementById('ult_usuario').value=datos[0]['tx_ultima_modificacion'];
	
		xGetElementById('ult_fecha').value=datos[0]['ff_ultima_modoficacion'];		
	
			//fechaUltimaMod	=	fechaJStoMySql(fechaUltimaMod);		
		
		xGetElementById('revisado').value= datos[0]['tx_revisado_por'];
		xGetElementById('conformado').value= datos[0]['tx_conformado_por'];
	}


	 				

}





function buscarAprobarCreditoAdicional(codigo)

{

	//alert(codigo);

	var url   	 = 'lib/transCreditoAdicional.php';

	var opx   	 = 'buscarAprobarCreditoAdicional';

	AjaxRequest.post

												(

								       				{

								       					'parameters':{'opcion':opx, 

																	  'codigo':codigo}

								            			,'url':url

								            			,'onSuccess': respBuscarAprobarCreditoAdicional

														,'onError':function(req)

														{ 

								                        	alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);

								                        }         

								             		}

								        		);

	

	

	

}



function respBuscarAprobarCreditoAdicional(req)
{

	//alert(req.responseText);

	var datos= eval ("("+req.responseText+")");		

	xGetElementById('nomempleado').value = datos[0]['NomCompleto'];	

}





function buscarItemCreditoAdicional(codigo, cond)

{

	//alert(codigo);

	var url   	 = 'lib/transCreditoAdicional.php';

	var opx   	 = 'buscarItemCreditoAdicional';

	AjaxRequest.post

												(

								       				{

								       					'parameters':{'opcion':opx, 

																	  'codigo':codigo}

								            			,'url':url

								            			,'onSuccess': function(req) {respBuscarItemCreditoAdicional(req,cond)}

														,'onError':function(req)

														{ 

								                        	alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);

								                        }         

								             		}

								        		);	

}



function respBuscarItemCreditoAdicional(req,cond)

{

	//alert(req.responseText);

	var datos= eval("("+req.responseText+")");		
	
	//alert(req.responseText);

	//xGetElementById('nomempleado').value = datos[0]['NomCompleto'];	

	

	//alert(req.responseText);

	

	

	for(var i=0; i<datos.length;i++)

	{

		

		//var i= OBJ_CREDITO_ADICIONAL.nu_sector.length;

		



		

		/*OBJ_CREDITO_ADICIONAL.co_sector[i]      		= datos[i]['cod_sector'];

		OBJ_CREDITO_ADICIONAL.co_programa[i]		 	= datos[i]['id_programa'];

		OBJ_CREDITO_ADICIONAL.co_sub_programa[i]      	= datos[i]['id_sub'];

		OBJ_CREDITO_ADICIONAL.co_proyecto[i]			= datos[i]['id_proyecto'];

		OBJ_CREDITO_ADICIONAL.co_actividad[i] 			= datos[i]['id_actividad'];*/

		OBJ_CREDITO_ADICIONAL.co_partida[i] 			= datos[i]['cod_partida'];

		

		//alert('1 '+OBJ_CREDITO_ADICIONAL.co_sector[i]+' 2 '+OBJ_CREDITO_ADICIONAL.co_programa[i]+' 3 '+OBJ_CREDITO_ADICIONAL.co_sub_programa[i]+' 4 '+OBJ_CREDITO_ADICIONAL.co_proyecto[i]+' 5 '+OBJ_CREDITO_ADICIONAL.co_actividad[i]+' 6 '+OBJ_CREDITO_ADICIONAL.co_partida[i])

		

		/*OBJ_CREDITO_ADICIONAL.nu_sector[i] 				= datos[i]['descripcion'];

		OBJ_CREDITO_ADICIONAL.nu_programa[i] 			= datos[i]['descp_programa'];

		OBJ_CREDITO_ADICIONAL.nu_sub_programa[i]		= datos[i]['descp_subprog'];

		OBJ_CREDITO_ADICIONAL.nu_proyecto[i] 			= datos[i]['descp_proyecto'];

		OBJ_CREDITO_ADICIONAL.nu_actividad[i] 			= datos[i]['descp_actividad'];*/

		OBJ_CREDITO_ADICIONAL.nu_partida[i] 			= datos[i]['cod_tipocuenta']+datos[i]['partida1']+'.'+datos[i]['generica']+'.'+datos[i]['especifica']+'.'+datos[i]['subespecifica']+'  ';

		OBJ_CREDITO_ADICIONAL.descripcion_partida[i] 	= datos[i]['denominacion'];

		OBJ_CREDITO_ADICIONAL.monto[i]					= toCurrency(datos[i]['mm_monto']);

																

								 	 	 	

								

		var tam= OBJ_CREDITO_ADICIONAL.nu_partida.length;

		

		

	}

	mostrarItemCreditoAdicional(tam,cond);





		

}









function fechaJStoMySql(fecha){

	var pedazo = fecha.split("-");

	var newFecha=pedazo[2]+'-'+pedazo[1]+'-'+pedazo[0];

	return newFecha;

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





function campoSiguiente(evt, idActual, idSiguiente,idAnterior)

{

	/*

	ENTRADA: idActual (ID DEL CAMPO DE TEXTO ACTUAL), idSiguiente (ID DEL CAMPO DE TEXTO SIGUIENTE), idAnterior(ID DEL CAMPO DE TEXTO ANTERIOR)

	SALIDA: 

	DESCRIPCIÓN: FUNCION QUE SE ENCARGA DE UBICAR EL CURSOR DEPENDIENDO EN QUE CAMPO DE TEXTO SE ENCUENTRAN UBICADO EL USUARIO

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 08-10-2012

	*/

	

	/* */

	

	var cero ='';

	var valor = xGetElementById(idActual).value;

		

	var longitud = valor.length;

	//alert(longitud);

	var e = new xEvent(evt);//crea una instancia de la clase xEvent

	if(e.keyCode == 8)

	{

		if(longitud==0)

			{

					xGetElementById(idAnterior).disabled=false;

					xGetElementById(idAnterior).focus();	

					xGetElementById('td_descripcion_partida').innerHTML='';		

			}

	}

	//alert(e.keyCode);

	else//verificamos que alla presionado enter

    {

			

			

			/*if(longitud==0)

			{

					xGetElementById(idAnterior).disabled=false;

					xGetElementById(idAnterior).focus();	

					xGetElementById('td_descripcion_partida').innerHTML='';		

			}*/

			

				

			if(idActual=='txt_partida' ||  idActual=='txt_ordinal')

			{				

				if(e.keyCode == 13)

				{

				

						if(longitud<3)

						{			

											for(var i=0; i<3-longitud;i++){

												cero +='0';

											}

											xGetElementById(idActual).value=cero+valor;

											longitud=3;

						}	

				}

				if(longitud==3){

						if(idActual!='txt_ordinal'){

								xGetElementById(idActual).disabled=true; 

								xGetElementById(idSiguiente).focus();}

							else 

							{

									//buscarPartida();

									verificarCampoItemPartida();

							}

				}

				/*if(longitud==3)

				{

							if(idActual!='txt_ordinal'){

								xGetElementById(idActual).disabled=true; 

								xGetElementById(idSiguiente).focus();}

							else 

							{

									//buscarPartida();

									verificarCampoItemPartida();

							}

				}*/

			}

			

			else

			{

				if(e.keyCode == 13)

				{					

					if(longitud<2){

						for(var i=0; i<2-longitud;i++){

											cero +='0';

										}

										xGetElementById(idActual).value=cero+valor;

										longitud=2;

						}

				}

				if(longitud==2)

				{

						xGetElementById(idActual).value=cero+valor;

						xGetElementById(idActual).disabled=true;

						xGetElementById(idSiguiente).focus();

				}

			}

	}

	

}



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





function validaSoloNumeros(id)

{

	/*

	ENTRADA: id (id del campo de texto)

	SALIDA: TRUE O FALSE

	DESCRIPCIÓN: FUNCION QUE SE ENCARGA DE VERIFICAR SI EXISTE TEXTO EN BLAMCO 

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 10-10-2012

	*/



   if(xGetElementById(id).value.match(/[^0-9\ ]/)){

    xGetElementById(id).value=xGetElementById(id).value.replace(/[^0-9\ ]/gi,"");	

  }

  xGetElementById(id).value = allTrim(xGetElementById(id).value);

}



function allTrim(sStr)

{//___________________________________________________________________________________________________________________________________

//funcion

	return rTrim(lTrim(sStr));

}//___________________________________________________________________________________________________________________________________



function rTrim(sStr)

{//___________________________________________________________________________________________________________________________________

//funcion

	while (sStr.charAt(sStr.length - 1) == " ")

	{

		sStr = sStr.substr(0, sStr.length - 1);

	}

	return sStr;

}//___________________________________________________________________________________________________________________________________



function lTrim(sStr)

{

      while (sStr.charAt(0) == " ")

        sStr = sStr.substr(1, sStr.length - 1);

        return sStr;

}



function rTrim(sStr)

{

        while (sStr.charAt(sStr.length - 1) == " ")

         sStr = sStr.substr(0, sStr.length - 1);

        return sStr;

}



function allTrim(sStr)

{

     return rTrim(lTrim(sStr));

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









function buscarGenerica() 

{

	/*

	ENTRADA: CAMPO (NOMBRE DEL CAMPO DE TEXTO), LIMITE(EL LIMITE DE CARACTERES ACEPTADO)

	SALIDA:

	DESCRIPCIÓN: FUNCIÓN QUE PERMITE CONTAR Y LIMITAR LOS CARACTERES DE UN CAMPO DE TEXTO

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 01-10-2012

	*/		

}











function buscarEspecifica() 

{

	/*

	ENTRADA: CAMPO (NOMBRE DEL CAMPO DE TEXTO), LIMITE(EL LIMITE DE CARACTERES ACEPTADO)

	SALIDA:

	DESCRIPCIÓN: FUNCIÓN QUE PERMITE CONTAR Y LIMITAR LOS CARACTERES DE UN CAMPO DE TEXTO

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 01-10-2012

	*/		

}



function buscarSubEspecifica() 

{

	/*

	ENTRADA: CAMPO (NOMBRE DEL CAMPO DE TEXTO), LIMITE(EL LIMITE DE CARACTERES ACEPTADO)

	SALIDA:

	DESCRIPCIÓN: FUNCIÓN QUE PERMITE CONTAR Y LIMITAR LOS CARACTERES DE UN CAMPO DE TEXTO

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 01-10-2012

	*/		

}



function buscarOrdinal() 

{

	/*

	ENTRADA: CAMPO (NOMBRE DEL CAMPO DE TEXTO), LIMITE(EL LIMITE DE CARACTERES ACEPTADO)

	SALIDA:

	DESCRIPCIÓN: FUNCIÓN QUE PERMITE CONTAR Y LIMITAR LOS CARACTERES DE UN CAMPO DE TEXTO

	PROGRAMADOR: ERNESTO RIVAS

	fecha: 01-10-2012

	*/		

}



//

/*

function editarPartida()

{

	var opcion	=	'editPartida';

	var url 	=	'lib/transCreditoAdicional.php';

	AjaxRequest.post

												(

								       				{

								       					'parameters':{'opcion':opcion}

								            			,'url':url

								            			,'onSuccess': respEditarPartida											       

														,'onError':function(req)

														{ 

								                        	alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);

								                        }         

								             		}

								        		);

}





function respEditarPartida(req)

{

	var datos= eval ("("+req.responseText+")");

	alert(req.responseText);

}*/
