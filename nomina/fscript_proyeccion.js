
// 8-04-2014 CARLOS MARCANO (PRUEBAS) HCM FUNCION PARA VERIFICAR LOS DATOS DE LOS PROCESOS
function verificarProcesos(form, accion) {
	

		
	var CodProyeccion = new String (form.CodProyeccion.value); 
		CodProyeccion=CodProyeccion.trim();
		form.CodProyeccion.value=CodProyeccion;	
		
	var tx_nomina = new String (form.tx_nomina.value); 
		tx_nomina=tx_nomina.trim();
		form.tx_nomina.value=tx_nomina;	
		
	//	console.log (tx_nomina.options[tx_nomina.selectedIndex].text);
		
	var tx_proceso = new String (form.tx_proceso.value); 
		tx_proceso=tx_proceso.trim();
		form.tx_proceso.value=tx_proceso;
		
	var tx_periodo = new String (form.tx_periodo.value); 
		tx_periodo=tx_periodo.trim();
		form.tx_periodo.value=tx_periodo;
		
	var tx_anio = new String (form.tx_anio.value); 
		tx_anio=tx_anio.trim();
		form.tx_anio.value=tx_anio;
		
	var tx_descripcion = new String ("Nomina "+tx_periodo+" "+tx_proceso); 
		tx_descripcion=tx_descripcion.trim();
				
		
		
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	
	if (tx_proceso=="") msjError(1010);
	if (tx_periodo=="") msjError(1010);
	if (tx_anio=="") msjError(1010);
	else if(tx_periodo=="") msjError(1010);
	else {
		var parametros= 
		                "&tx_proceso="+tx_proceso+
		                "&tx_periodo="+tx_periodo+
		                "&tx_anio="+tx_anio+
		                "&CodProyeccion="+CodProyeccion+
		                "&tx_nomina="+tx_nomina+
		                "&tx_descripcion="+tx_descripcion ; 
		                
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/ProcesosControlador.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PROYECCION&accion="+accion+parametros);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="proyeccion_procesos.php?CodProyeccion="+CodProyeccion+"&filtro="+form.filtro.value;
			}
		}
	}
	return false;
}


// 21-04-2014 CARLOS MARCANO (PRUEBAS) HCM FUNCION PARA VERIFICAR LOS DATOS DE LOS PROYECCION
function verificarProyeccion(form, accion) {
	
	
	var tx_desde = new String (form.tx_desde.value); 
		tx_desde=tx_desde.trim();
		form.tx_desde.value=tx_desde;
		
	var tx_hasta = new String (form.tx_hasta.value); 
		tx_hasta=tx_hasta.trim();
		form.tx_hasta.value=tx_hasta;	
	
	var tx_codigo = new String (form.tx_codigo.value); 
		tx_codigo=tx_codigo.trim();
		form.tx_codigo.value=tx_codigo;

		
	var tx_descripcion = new String (form.tx_descripcion.value); 
		tx_descripcion=tx_descripcion.trim();
		form.tx_descripcion.value=tx_descripcion;
				
		
		
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (tx_desde=="") msjError(1010);
	if (tx_hasta=="") msjError(1010);
	
	if (tx_codigo=="") msjError(1010);

	else if(tx_descripcion=="") msjError(1010);
	else {
		var parametros= "&tx_codigo="+tx_codigo+
		                 "&tx_hasta="+tx_hasta+
		                 "&tx_desde="+tx_desde+
		                "&tx_descripcion="+tx_descripcion ; 
		 //  alert  (parametros);            
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/ProyeccionControlador.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PROYECCION&accion="+accion+parametros);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="py_proyeccion.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}


function verificarPorcentajeConcepto(form, accion) {
	
	
	var CodProyeccion = new String (form.CodProyeccion.value); 
		CodProyeccion=CodProyeccion.trim();
		form.CodProyeccion.value=CodProyeccion;

		
	var tx_codigo = new String (form.tx_codigo.value); 
		tx_codigo=tx_codigo.trim();
		form.tx_codigo.value=tx_codigo;
		
	var tx_porcentaje = new String (form.tx_porcentaje.value); 
		tx_porcentaje=tx_porcentaje.trim();
		form.tx_porcentaje.value=tx_porcentaje;
				
	var tx_desde = new String (form.tx_desde.value); 
		tx_desde=tx_desde.trim();
		form.tx_desde.value=tx_desde;

	var tx_hasta = new String (form.tx_hasta.value); 
		tx_hasta=tx_hasta.trim();
		form.tx_hasta.value=tx_hasta;
				
		
		
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (tx_codigo==0) msjError(1010);
    else if(tx_porcentaje=="") msjError(1010);
	else if(CodProyeccion==0) msjError(1010);
	else {
		var parametros= "&tx_codigo="+tx_codigo+
		                 "&tx_porcentaje="+tx_porcentaje+
		                 "&tx_hasta="+tx_hasta+
		                 "&tx_desde="+tx_desde+
		                "&CodProyeccion="+CodProyeccion ; 
		                
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/ProyeccionPorcentajesConceptoControlador.php", true);
		
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PROYECCION&accion="+accion+parametros);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="py_conceptos_porcentaje.php?CodProyeccion="+CodProyeccion;
			}
		}
	}
	return false;
}

////////////////////////////////////////////////////////////////////////////////////////////////////////

//	FUNCION PARA CARGAR EN UN SELECT LO SELECCIONADO EN OTRO SELECT (2 SELECTS)
function getFOptions_Proceso_py(idSelectOrigen, idSelectDestino, idChkDestino, nomina, codorganismo, opt,CodProyeccion,CodTipoNom,Periodo) {
	var selectOrigen=document.getElementById(idSelectOrigen);
	var optSelectOrigen=selectOrigen.options[selectOrigen.selectedIndex].value;
	var selectDestino=document.getElementById(idSelectDestino);
	var chkDestino=document.getElementById(idChkDestino);
	if (optSelectOrigen=="") {
		selectDestino.length=0;
		nuevaOpcion=document.createElement("option");
		nuevaOpcion.value="";
		nuevaOpcion.innerHTML="";
		selectDestino.appendChild(nuevaOpcion);
	} else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/SelectsControlador.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=PROCESO&tabla="+idSelectDestino+"&opcion="+optSelectOrigen
		         +"&nomina="+nomina+"&codorganismo="+codorganismo+"&opt="+opt
		         +"&CodProyeccion="+CodProyeccion+"&CodTipoNom="+CodTipoNom+"&Periodo="+Periodo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				selectDestino.parentNode.innerHTML=ajax.responseText;
			}
		}
	}
}

//	FUNCION PARA CARGAR EN UN SELECT LO SELECCIONADO EN OTRO SELECT (2 SELECTS)
function getFOptions_Periodo_py(idSelectOrigen, idSelectDestino, idChkDestino, codorganismo, opt,CodProyeccion,CodTipoNom) {
	var selectOrigen=document.getElementById(idSelectOrigen);
	var optSelectOrigen=selectOrigen.options[selectOrigen.selectedIndex].value;
	var selectDestino=document.getElementById(idSelectDestino);
	var chkDestino=document.getElementById(idChkDestino);
	if (optSelectOrigen=="") {
		selectDestino.length=0;
		nuevaOpcion=document.createElement("option");
		nuevaOpcion.value="";
		nuevaOpcion.innerHTML="";
		selectDestino.appendChild(nuevaOpcion);
	} else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/SelectsControlador.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=PERIODO&tabla="+idSelectDestino+"&opcion="+optSelectOrigen+"&codorganismo="+codorganismo+"&opt="+opt+"&CodProyeccion="+CodProyeccion+"&CodTipoNom="+CodTipoNom);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				selectDestino.parentNode.innerHTML=ajax.responseText;
			}
		}
	}
}


function validarProyeccion(form) {
	
	var ftproyeccion = document.getElementById("ftproyeccion").value;
	/*var forganismo = document.getElementById("forganismo").value;
	var ftiponom = document.getElementById("ftiponom").value;
	var fperiodo = document.getElementById("fperiodo").value;
	var ftproceso = document.getElementById("ftproceso").value;
	var archivo = document.getElementById("archivo").value.trim();*/
	//if (document.getElementById("asignaciones").checked) var chkasignacion = "I"; else var chkasignacion = "";
	//if (document.getElementById("deducciones").checked) var chkdeduccion = "D"; else var chkdeduccion = "";
	
	/*if (chkasignacion == "" && chkdeduccion == "") {
		alert("¡DEBE SELECCIONAR EL TIPO DE CONCEPTO A FILTRAR!");
	}*/
	/*else {*/
		/*if (document.getElementById("pdf").checked) {
			window.open("pdf_proyeccion.php?forganismo="+forganismo+"&ftiponom="+ftiponom+"&fperiodo="+fperiodo+"&ftproceso="+ftproceso+"&chkdeduccion="+chkdeduccion+"&chkasignacion="+chkasignacion, "", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes");
		} else {
			if (archivo == "") {
				alert("¡DEBE INGRESAR EL NOMBRE DEL ARCHIVO A EXPORTAR!");
			} else {
				form.action = "excel_proyeccion.php";
				form.submit();
			}
		}*/
	/*}*/
	
	
	/*var ajax=nuevoAjax();
	    accion="CALCULAR-PROYECCION";
		ajax.open("POST", "lib/ProyeccionControlador.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var envio="modulo=EJECUCION-PROCESOS&accion="+accion+"&ftproyeccion="+ftproyeccion;
		ajax.send(envio);
		
		//alert(envio);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
			  var resp = ajax.responseText;
				if (resp.trim() != 0) alert(resp);
			    else  return true;
			}
		}*/
	
	 return true;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////
/*******************************************************************************************************************************/
//	Eliminar fila de tabla Origen y Agregarla a tabla de Destino...
function switchSelTR_Proyeccion(form,accion, tblDest, idTrDestino, tblOrigen, idTrOrigen, idChkDestino, nmChkDestino, idChkOrigen, nmChkOrigen) {
	var tblDestino = document.getElementById(tblDest);
	var num = 0;
	var seleccionados = "";
	var valores = "";
	
	//	Obtengo los valores de las filas seleccionadas...
	for(i=0; n=form.elements[i]; i++) {
		if (n.type=="checkbox" && n.name==nmChkOrigen && n.checked==true) {
			num++;
			if (num == 1) seleccionados = n.value; else seleccionados += "|;|" + n.value;
		} 
	}
	
	if (seleccionados == "") alert("¡DEBE SELECCIONAR UN EMPLEADO!")
	else {
		//	------------------------------
	
			var fperiodo = document.getElementById("fperiodo").value;
			var ftiponom = document.getElementById("ftiponom").value;
			var ftproyeccion	= document.getElementById("ftproyeccion").value; 
			var ftproceso		= document.getElementById("ftproceso").value;
		//var ftproceso = document.getElementById("ftproceso").value;
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/ProcesosControlador.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=EJECUCION-PROCESOS&accion="+accion+"&seleccionados="+seleccionados+"&proceso="+ftproceso
		           +"&Periodo="+fperiodo
		           +"&CodProyeccion="+ftproyeccion
		           +"&CodTipoNom="+ftiponom
		           +"&CodTipoProceso="+ftproceso);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
			  var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp);
			}
		}
		//	------------------------------
		
		var valores = seleccionados.split("|;|");
		
		for (i=0; i<valores.length; i++) {
			var valor = valores[i].split("|:|");
			
			//	Creo los elementos para agregarlos a la tabla destino...
			idTrDest = idTrDestino + valor[0];
			idChkDest = idChkDestino + valor[0];
			var trDestino = document.createElement("tr"); 
			trDestino.className = "trListaBody";
			trDestino.id = idTrDest;
			trDestino.setAttribute("ondblclick", "switchDblTR(this, '"+tblOrigen+"', '"+idTrOrigen+"', '"+tblDest+"', '"+idTrDestino+"', '"+idChkOrigen+"', '"+nmChkOrigen+"', '"+idChkDestino+"', '"+nmChkDestino+"', '"+valores[i]+"');");
		//	trDestino.setAttribute("ondblclick", "activar('"+tblOrigen+"', this, 'trApro"+1+"');");
			var tdDestino1 = document.createElement("td");
			tdDestino1.align = "center";
			var tdDestino2 = document.createElement("td");
			tdDestino2.align = "center";
			var tdDestino3 = document.createElement("td");
			var tdDestino4 = document.createElement("td");
			var tdDestino5 = document.createElement("td");
			var tdDestino6 = document.createElement("td");
			tdDestino6.align = "center";
			var chkDestino1 = document.createElement("input");
			chkDestino1.type = "checkbox";
			chkDestino1.id = idChkDest; 
			chkDestino1.name = nmChkDestino; 
			chkDestino1.value = valores[i];
			var txtDestino2 = document.createTextNode(valor[0]);
			var txtDestino3 = document.createTextNode(valor[1]);
			var txtDestino4 = document.createTextNode(valor[2]);
			var txtDestino5 = document.createTextNode(valor[3]);
			var txtDestino6 = document.createTextNode(valor[4]);
			
			//	Agrego los elementos creados a la tabla destino...
			tblDestino.appendChild(trDestino);
			trDestino.appendChild(tdDestino1);
			trDestino.appendChild(tdDestino2);
			trDestino.appendChild(tdDestino3);
			trDestino.appendChild(tdDestino4);
			trDestino.appendChild(tdDestino5);
			trDestino.appendChild(tdDestino6);
			tdDestino1.appendChild(chkDestino1);
			tdDestino2.appendChild(txtDestino2);
			tdDestino3.appendChild(txtDestino3);
			tdDestino4.appendChild(txtDestino4);
			tdDestino5.appendChild(txtDestino5);
			tdDestino6.appendChild(txtDestino6);
			
			//	Elimino la fila del seleccionado en la tabla origen...
			var idTrO = idTrOrigen + valor[0];
			var trOrigen = document.getElementById(idTrO);
			var tblO = trOrigen.parentNode;		
			tblO.removeChild(trOrigen);
			
		}
	}
}


//	Valido filtro Ejecucion de Procesos
function cargarDisponiblesProcesarProyeccion(form) {
	var frm = document.getElementById("frmentrada");
	//var forganismo = document.getElementById("forganismo").value;
	//var ftiponom = document.getElementById("ftiponom").value;
	//var fperiodo = document.getElementById("fperiodo").value;
	var ftproceso = document.getElementById("ftproceso").value;
	
	
	
	//if (ftiponom=="" || fperiodo=="") { alert("¡DEBE SELECCIONAR EL TIPO DE NOMINA Y PERIODO!"); return false; }
	//else
	 if (ftproceso=="") { alert("¡DEBE SELECCIONAR EL TIPO DE PROCESO!"); return false; }
	else return true;
}


//	FUNCION PARA EJECUTAR EL PROCESO DE CALCULO A TRAVES DE UN AJAX (PRUEBA)
function procesarNominaProyeccion(form) {
	if (confirm("¿Dese generar la nomina para los trabajadores seleccionados (proyeccion) ?")) {
		document.getElementById("bloqueo").style.display = "block";
		document.getElementById("cargando").style.display = "block";
	//	var forganismo = document.getElementById("forganismo").value;
	//	var ftiponom = document.getElementById("ftiponom").value;
	//	var fperiodo = document.getElementById("fperiodo").value;
		
		
		var ftproyeccion = document.getElementById("ftproyeccion").value;
		var ftiponom = document.getElementById("ftiponom").value;
		var fperiodo = document.getElementById("fperiodo").value;
		var ftproceso = document.getElementById("ftproceso").value;
		
		var num = 0;
		var aprobados = "";
		
		//	Obtengo los valores de los empleados a procesar...
		for(i=0; n=form.elements[i]; i++) {
			if (n.type=="checkbox" && n.name=="chkAprobados" && n.checked==true) {
				num++;
				var valor = n.value.split("|:|");
				if (num == 1) aprobados = valor[0]; else aprobados += "|:|" + valor[0];
			} 
		}
		
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/ProcesosControlador.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=EJECUCION-PROCESOS&accion=PROCESAR-NOMINA&aprobados="+
		          aprobados+"&organismo="+
		          "&proceso="+ftproceso+
		          "&tiponom="+ftiponom+
		          "&periodo="+fperiodo+
		          "&ftproyeccion="+ftproyeccion);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp=ajax.responseText;
				if (resp.trim() != "") alert(resp); else alert("¡Se generó el calculo exitosamente!");			
				document.getElementById("bloqueo").style.display = "none";		
				document.getElementById("cargando").style.display = "none";
			}
		}
	}
}

/**************************************************************************/
//	FUNCION PARA SELECCIONAR UN REGISTRO DE UNA LISTA Y COLOCARLO EN OTRA VENTANA
function selConceptoProyecion(busqueda) {
		var codpersona = document.getElementById("codpersona").value;
		var registro = document.getElementById("registro").value;
		//var periodo = opener.document.frmentrada.pdesde.value;
		//var nomina = opener.document.frmentrada.nomina.value;


		opener.document.frmentrada.monto.disabled=false;
		opener.document.frmentrada.cantidad.disabled=false;

		opener.document.frmentrada.codconcepto.value=registro;
		opener.document.frmentrada.nomconcepto.value=busqueda;
		window.close();
		
	
}

/*******************************************************************************************************/
//------------------------------------------------------------//
//	CONCEPTOS DEL EMPLEADO
function verificarEmpleadoConceptosProyeccion(form) {
	//var secuencia=document.getElementById("secuencia").value;
//	alert('Hola');
	var registro=document.getElementById("registro").value;
	var accion=document.getElementById("accion").value;
	
	var codproceso=document.getElementById("codproceso").value; codproceso=codproceso.trim();
	var codproyeccion=document.getElementById("codproyeccion").value; codproyeccion=codproyeccion.trim();
	var codperiodo=document.getElementById("codperiodo").value; codperiodo=codperiodo.trim();
	var codnomina=document.getElementById("codnomina").value; codnomina=codnomina.trim();
	
	var codconcepto=document.getElementById("codconcepto").value; codconcepto=codconcepto.trim();
	
	var monto=document.getElementById("monto").value; monto=monto.trim(); monto=monto.replace(",", ".");
	var cantidad=document.getElementById("cantidad").value; cantidad=cantidad.trim(); cantidad=cantidad.replace(",", ".");
	//if (document.getElementById("flagproceso").checked) var flagproceso="S"; else var flagproceso="N";
//s	alert("py_empleados_conceptos.php?registro="+registro+"&proceso="+codproceso+"&periodo="+codperiodo+"&ftproyeccion="+codproyeccion+"&CodTipoNomina="+codnomina);
	//if (codconcepto=="" || status=="" || codproceso=="" || pdesde=="") msjError(1010);
	if (codconcepto==""  || monto=="") msjError(1010);
	
	else if (isNaN(monto)) alert("¡MONTO INCORRECTO!");
	else if (isNaN(cantidad)) alert("¡CANTIDAD INCORRECTA!");
	//else if (monto == 0) alert("¡NO SE PUEDE ASIGNAR UN CONCEPTO CON MONTO EN CERO!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/EmpleadoProcesoProyeccionControlador.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		//alert(accion);
		ajax.send("accion="+accion
		            +"&codproceso="+codproceso
					+"&codproyeccion="+codproyeccion
					+"&codperiodo="+codperiodo
					+"&codnomina="+codnomina
					+"&codconcepto="+codconcepto
					+"&codpersona="+registro
					+"&monto="+monto
					+"&cantidad="+cantidad);
		
		ajax.onreadystatechange=function() {
		   
			if (ajax.readyState==4)	{
				var resp=ajax.responseText;
				if (resp!=0) alert ("¡"+resp+"!");
				else cargarPagina(form, "py_empleados_conceptos.php?registro="+registro+"&proceso="+codproceso+"&periodo="+codperiodo+"&ftproyeccion="+codproyeccion+"&CodTipoNomina="+codnomina);
			}
		}
	}
	return false;
}

function editarEmpleadoConceptosProyeccion(form) {
	elemento=document.getElementById("elemento").value;
	registro=document.getElementById("registro").value;
	var codproceso=document.getElementById("codproceso").value; codproceso=codproceso.trim();
	if (elemento=="") msjError(1000);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/EmpleadoProcesoProyeccionControlador.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=EMPLEADOS-CONCEPTOS&accion=EDITAR&codpersona="+registro+"&elemento="+elemento+"&codproceso="+codproceso);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp=ajax.responseText;
				var valores=resp.split("|:|");
				if (valores[0]!=0) alert ("¡"+valores[0]+"!");
				else {
					document.getElementById("btConcepto").disabled=true;
					document.getElementById("codconcepto").value=valores[1];
					document.getElementById("nomconcepto").value=valores[2];
					document.getElementById("monto").value=valores[3];
					document.getElementById("cantidad").value=valores[4];
					/*if (valores[9]=="S") {
						//document.getElementById("flagproceso").checked=true;
						document.getElementById("codproceso").value=valores[5];
						document.getElementById("nomproceso").value=valores[5];
						document.getElementById("btProceso").disabled=false;
					} else {
						document.getElementById("flagproceso").checked=false;
						document.getElementById("codproceso").value="[TODOS]";
						document.getElementById("nomproceso").value="[TODOS]";
						document.getElementById("btProceso").disabled=true;
					}*/
					document.getElementById("btEditar").disabled=true;
					document.getElementById("btEliminar").disabled=true;
					document.getElementById("accion").value="ACTUALIZAR";
				}
			}
		}
	}
}
function eliminarEmpleadoConceptosProyeccion(form) {
	elemento=document.getElementById("elemento").value;
	registro=document.getElementById("registro").value;
	
	var codconcepto=document.getElementById("codconcepto").value; codconcepto=codconcepto.trim();
	var codproceso=document.getElementById("codproceso").value; codproceso=codproceso.trim();
	
	
	if (elemento=="") msjError(1000);
	else {
		var x=confirm("¿Realmente desde eliminar este registro?");
		if (x) {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "lib/EmpleadoProcesoProyeccionControlador.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=EMPLEADOS-CONCEPTOS&accion=ELIMINAR&codpersona="+registro+"&codproceso="+codproceso+"&elemento="+elemento);
		   
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					
					var resp=ajax.responseText;
					if (resp!=0) alert("¡"+resp+"!");
					else cargarPagina(form, "py_empleados_conceptos.php");
				}
			}
		}
	}
}
function limpiarEmpleadoConceptos(form) {
	//document.getElementById("secuencia").value="";
	document.getElementById("codconcepto").value="";
	document.getElementById("nomconcepto").value="";
	//document.getElementById("pdesde").value="";
	//document.getElementById("phasta").value="";
	//document.getElementById("codproceso").value="";
	//document.getElementById("nomproceso").value="";
	document.getElementById("monto").value="";
	document.getElementById("cantidad").value="";
	//document.getElementById("status").value="";
	//document.getElementById("flagproceso").checked=false;
	document.getElementById("accion").value="INSERTAR";
	document.getElementById("btConcepto").disabled=false;
	//document.getElementById("btProceso").disabled=true;
	document.getElementById("btEditar").disabled=false;
	document.getElementById("btEliminar").disabled=false;
}
