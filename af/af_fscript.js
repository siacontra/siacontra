// CREO ESTE METODO PARA PODER ELIMINAR LOS ESPACIOS EN BLANCOS AL PRINCIPIO Y AL FINAL DE CADA CAMPO 
String.prototype.trim = function() { return this.replace(/^\s+|\s+$/g,'') }

var MAXLIMIT=30;

//	FUNCION QUE ME PERMITE CREAR UN NUEVO OBJETO AJAX
function nuevoAjax() { 
	/* Crea el objeto AJAX. Esta funcion es generica para cualquier utilidad de este tipo, por	lo que se puede copiar tal como esta aqui */
	var xmlhttp=false; 
	try 
	{ 
		// Creacion del objeto AJAX para navegadores no IE
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP"); 
	}
	catch(e)
	{ 
		try
		{
			// Creacion del objeto AJAX para IE 
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); 
		} 
		catch(E) { xmlhttp=false; }
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') { xmlhttp=new XMLHttpRequest(); } 
	return xmlhttp;
}

//	FUNCION QUE CAMBIA DE COLOR LA FILA DE UNA TABLA AL HACER CLICK EL PUNTERO DEL MOUSE SOBRE ELLA
/*function mClk(src, registro) {
	var seleccionado=document.getElementsByTagName("tr");
	for (var i=0; i<seleccionado.length; i++) {
		if (seleccionado[i].getAttribute((document.all ? 'className' : 'class')) ==	'trListaBodySel') {
			seleccionado[i].setAttribute((document.all ? 'className' : 'class'), "trListaBody");
			break;
		}
	}
	//
	var row=document.getElementById(src.id);	//	OBTENGO LA FILA DEL CLICK
	row.className="trListaBodySel";	//	CAMBIO EL COLOR DE LA FILA
	
	var registro=document.getElementById(registro);
	registro.value=src.id;
}*/

//	FUNCION PARA CARGAR UNA NUEVA PAGINA 
function cargarPagina(form, pagina) {
	form.method="POST";
	form.action=pagina;
	form.submit();
}

//	FUNCION PARA CARGAR UNA NUEVA PAGINA 
/*function cargarOpcion(form, seleccion, pagina, target, param) {
	if (seleccion == "") alert("¡DEBE SELECCIONAR UN REGISTRO!");
	else {
		if (target=="SELF") cargarPagina(form, pagina+"?registro="+seleccion);
		else { pagina=pagina+"?limit=0&accion=VER&registro="+seleccion; cargarVentana(form, pagina, param); }
	}
}*/
//// --------------------------------------------------------------------------------------------------------
function cargarOpcionClasfActivos(form, seleccion, pagina, target, param) {
	if (seleccion == "") alert("¡DEBE SELECCIONAR UN REGISTRO!");
	else {
		if (target=="SELF") cargarPagina(form, pagina+"?registro="+seleccion);
		else { pagina=pagina+"?limit=0&accion=VER&registro="+seleccion; cargarVentana(form, pagina, param); }
	}
}
//// --------------------------------------------------------------------------------------------------------
function cargarOpcionVer(form, seleccion, pagina, target, param) {
	if (seleccion == "") alert("¡DEBE SELECCIONAR UN REGISTRO!");
	else {
		if (target=="SELF") cargarPagina(form, pagina+"?registro="+seleccion);
		else { pagina=pagina+"?limit=0&accion=VER&registro="+seleccion; cargarVentana(form, pagina, param); }
	}
}
//	FUNCION PARA ELIMINAR UN REGISTRO DE LA BASE DE DATOS
function eliminarRegistro(form, seleccion, modulo) {
	if (seleccion == "") alert("¡DEBE SELECCIONAR UN REGISTRO!");
	else {
		//	PREGUNTO SI DESEA ELIMINAR
		var eliminar = confirm("¡Esta seguro de eliminar este registro?");
		if (eliminar) {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "af_fphp_ajax.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo="+modulo+"&accion=ELIMINAR&codigo="+seleccion);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var error=ajax.responseText;
					if (error!=0) alert ("¡"+error+"!");
					else cargarPagina(form, form.action);
				}
			}
		}
	}
}
//	FUNCION PARA CARGAR UNA NUEVA VENTANA
function cargarVentana(form, pagina, param) {
	window.open(pagina, "wPrincipal", "toolbar=no, menubar=no, location=no, scrollbars=yes, "+param);
}

//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalRegistros(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
	var btNuevo=document.getElementById("btNuevo");
	var btEditar=document.getElementById("btEditar");
	var btVer=document.getElementById("btVer");
	var btEliminar=document.getElementById("btEliminar");
	var btPDF=document.getElementById("btPDF");
	//
	if (insert=="N") btNuevo.disabled=true;
	if (update=="N" || !rows) btEditar.disabled=true;
	if (del=="N" || !rows) btEliminar.disabled=true;
	if (!rows) {
		btVer.disabled=true;
		btPDF.disabled=true;
	}
}
function totalLotes(registros, rows, limit) {
	var desde = document.getElementById("desde");
	var hasta = document.getElementById("hasta");
	var btPrimero=document.getElementById("btPrimero");
	var btAtras=document.getElementById("btAtras");
	var btSiguiente=document.getElementById("btSiguiente");
	var btUltimo=document.getElementById("btUltimo");
	if (registros) {
		if (limit==0) { btPrimero.disabled=true; btAtras.disabled=true; } else { btPrimero.disabled=false; btAtras.disabled=false; }		
		if ((registros<=MAXLIMIT) || ((limit+rows)==registros) || (limit==registros)) { btSiguiente.disabled=true; btUltimo.disabled=true; }
		else { btSiguiente.disabled=false; btUltimo.disabled=false; }		
		desde.innerHTML=limit+1;
		hasta.innerHTML=limit+rows;
	} else {
		btPrimero.disabled=true; btAtras.disabled=true;
		btSiguiente.disabled=true; btUltimo.disabled=true;
		desde.innerHTML=0;
		hasta.innerHTML=0;
	}
}
function numRegistros(rows) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
}
function setLotes(form, lote, registros, limit, ordenar) {
	//alert('Limit='+limit);
	//alert('Lote='+lote);
	switch (lote) {
		case "P":
			limit=0;
			break;
		case "A":
			limit=limit-MAXLIMIT; 
			break;
		case "S":
			limit=limit+MAXLIMIT; //alert('Limit2='+limit);
			break;
		case "U":
			var num=(registros/MAXLIMIT);
			num=parseInt(num);
			limit=num*MAXLIMIT;
			if (limit==registros) limit=limit-MAXLIMIT;
			break;
	}
	//document.getElementById("limit").value = limit;
	var pagina=form.action+"&limit="+limit+"&ordenar="+ordenar;
	cargarPagina(form, pagina);
}
// FUNCION QUE MUESTRA EL LOTE CORREPONDIENTE
function setLotes2(form, lote, registros, limit, ordenar) {
	//alert('Limit='+limit);
	//alert('Lote='+lote);
	switch (lote) {
		case "P":
			limit=0;
			break;
		case "A":
			limit=limit-MAXLIMIT; 
			break;
		case "S":
			limit=limit+MAXLIMIT; //alert('Limit2='+limit);
			break;
		case "U":
			var num=(registros/MAXLIMIT);
			num=parseInt(num);
			limit=num*MAXLIMIT;
			if (limit==registros) limit=limit-MAXLIMIT;
			break;
	}
	//document.getElementById("limit").value = limit;
	var pagina=form.action+"&limit="+limit+"&ordenar="+ordenar;
	cargarPagina(form, pagina);
}
//// -------------------------------------------------------------
/*function setLotes2(form, lote, registros, limit, ordenar) {
	switch (lote) {
		case "P":
			limit=0;
			break;
		case "A":
			limit=limit-MAXLIMIT;
			break;
		case "S":
			limit=limit+MAXLIMIT;
			break;
		case "U":
			var num=(registros/MAXLIMIT);
			num=parseInt(num);
			limit=num*MAXLIMIT;
			if (limit==registros) limit=limit-MAXLIMIT;
			break;
	}
	document.getElementById("limit").value = limit;
	var pagina=form.action;
	cargarPagina(form, pagina);
}*/
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR INPUTS DEL FILTRO
function chkFiltro(boo, id) {
	document.getElementById(id).value = "";
	document.getElementById(id).disabled = !boo;
}
function chkFiltro_2(boo, id1, id2) {
	document.getElementById(id1).value = "";
	document.getElementById(id2).value = "";
	document.getElementById(id1).disabled = !boo;
	document.getElementById(id2).disabled = !boo;
}

//	FUNCION PARA CARGAR EN UN SELECT LO SELECCIONADO EN OTRO SELECT (2 SELECTS)
function getFOptions_2(idSelectOrigen, idSelectDestino, idChkDestino) {
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
		selectDestino.disabled=true;
		chkDestino.checked=false;
	} else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_selects.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=getOptions_2&tabla="+idSelectDestino+"&opcion="+optSelectOrigen);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==1) {
				// Mientras carga elimino la opcion "" y pongo una que dice "Cargando..."
				selectDestino.length=0;
				var nuevaOpcion=document.createElement("option");
				nuevaOpcion.value="";
				nuevaOpcion.innerHTML="Cargando...";
				selectDestino.appendChild(nuevaOpcion);
				selectDestino.disabled=true;	
			}
			if (ajax.readyState==4)	{
				selectDestino.parentNode.innerHTML=ajax.responseText;
			}
		}
	}
}

//	FUNCION PARA FORZAR A MANTENER CHEQUEADO
function forzarCheck(id) {
	document.getElementById(id).checked=true;
}

//	FUNCION PARA VALIDAR SOLO NUMEROS EN UN INPUT
function valNumerico(input) {
	var checkOK ="0123456789";
	var checkStr = input;
	var allValid = true; 
	for (i = 0; i < checkStr.length; i++) {
		ch = checkStr.charAt(i); 
		for (j = 0; j < checkOK.length; j++)
			if (ch == checkOK.charAt(j))
				break;
		if (j == checkOK.length) { 
			allValid = false; 
			break; 
		}
	}
	return allValid;
}

//	FUNCION PARA VALIDAR SOLO PALABRAS EN UN INPUT
function valAlfabeto(input) {
	var checkOK ="ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚ" + "abcdefghijklmnñopqrstuvwxyzáéíóú" + " ";
	var checkStr = input;
	var allValid = true; 
	for (i = 0; i < checkStr.length; i++) {
		ch = checkStr.charAt(i); 
		for (j = 0; j < checkOK.length; j++)
			if (ch == checkOK.charAt(j))
				break;
		if (j == checkOK.length) { 
			allValid = false; 
			break; 
		}
	}
	return allValid;
}

//	FUNCION PARA VALIDAR ALFANUMERICOS EN UN INPUT
function valAlfanumerico(input) {
	var checkOK ="ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚ" + "abcdefghijklmnñopqrstuvwxyzáéíóú" + "0123456789" + " -.,:;$%()¿?¡!";
	var checkStr = input;
	var allValid = true; 
	for (i = 0; i < checkStr.length; i++) {
		ch = checkStr.charAt(i); 
		for (j = 0; j < checkOK.length; j++)
			if (ch == checkOK.charAt(j))
				break;
		if (j == checkOK.length) { 
			allValid = false; 
			break; 
		}
	}
	return allValid;
}

///	--------------------------------------------------------------------------------
///	--	VALIDACION DE FORMULARIOS
///	--------------------------------------------------------------------------------
///	CLASIFICACION DE ACTIVOS
function verificarClasificacionActivo(form, accion) {
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	if (codigo.length == 2) var nivel = 1;
	else if (codigo.length == 4) var nivel = 2;
	else if (codigo.length == 6) var nivel = 3;
	
	if (codigo == "" || descripcion == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else if (!valNumerico(codigo)) alert("¡El código debe ser númerico!");
	else if (!valAlfanumerico(descripcion)) alert("¡No se permiten caracteres especiales en el campo descripción!");
	else if (codigo.length != 2 && codigo.length != 4 && codigo.length != 6) alert("¡Codigo ingresado incorrecto!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "af_fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CLASIFICACION-ACTIVOS&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&nivel="+nivel+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText.trim();
				if (resp != "") alert(resp);
				else cargarPagina(form, "af_clasificacion_activos.php");
			}
		}
	}
	return false;
}
///	--------------------------------------------------------------------------------
///	-------------------	CLASIFICACION DE ACTIVOS SEGUN PUBLICACION 20 NUEVO REGISTRO
function verificarClasificacionActivo20(form){ 
	var codigo1 = document.getElementById("codigo1").value; 
	var codigo2 = document.getElementById("codigo2").value; 
	var descripcion = document.getElementById("descripcion").value;
	var nivel = document.getElementById("nivel").value; 
	
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	if (descripcion == "" || nivel=="") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else{ var ajax=nuevoAjax();
		ajax.open("POST", "gmactivofijo.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("&accion=guardarNuevoPublicacion20&descripcion="+descripcion+"&nivel="+nivel+"&status="+status+"&codigo1="+codigo1+"&codigo2="+codigo2);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp);
				else cargarPagina(form, "af_clasificacion_activos_20.php");
			}
		}	
	}	
	return false;
}
///	--------------------------------------------------------------------------------
///	-------------------	CLASIFICACION DE ACTIVOS SEGUN PUBLICACION 20 EDITAR REGISTRO
function EditarClasificacionActivo20(form){
	
	var codigo = document.getElementById("codigo").value;
	var descripcion = document.getElementById("descripcion").value;
	var nivel = document.getElementById("nivel").value;
	
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	if (codigo == "" || descripcion == "" || nivel=="") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else{ var ajax=nuevoAjax();
		ajax.open("POST", "gmactivofijo.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("&accion=EditarPublicacion20&descripcion="+descripcion+"&nivel="+nivel+"&status="+status+"&codigo="+codigo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText.trim();
				if (resp != "") alert(resp);
				else cargarPagina(form, "af_clasificacion_activos_20.php");
			}
		}	
	}
	
	return false;
}
///	--------------------------------------------------------------------------------
///	-------------------	UBICACION DE ACTIVO
function verificarUbicacionActivo(form, accion) {
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	if (codigo == "" || descripcion == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "af_fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=UBICACION-ACTIVO&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText.trim();
				if (resp != "") alert(resp);
				else cargarPagina(form, "af_ubicaciones_activo.php");
			}
		}
	}
	return false;
}
///	--------------------------------------------------------------------------------
/// -------------------	CARGAR SELECT PARA DEPENDENCIAS EN TRANSFERIRDATOSGENERALES.PHP
function cargarDependencia(idSelectDestino){
	 
	var organismo = document.getElementById("organismo").value; 
	var selectOrganismo=document.getElementById("organismo");
	var optSelectOrganismo=selectOrganismo.options[selectOrganismo.selectedIndex].value;
	var selectDestino=document.getElementById(idSelectDestino);
	    nuevaOpcion=document.createElement("option");	nuevaOpcion.value="";	nuevaOpcion.innerHTML="";
	
	if (optSelectOrganismo=="") {
		selectDestino.length=0;
		selectDestino.appendChild(nuevaOpcion);
		selectDestino.disabled=true;		
	} else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "gmactivofijo.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=obtenerDep&tabla="+idSelectDestino+"&opcion="+optSelectOrganismo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==1) {
				// Mientras carga elimino la opcion "" y pongo una que dice "Cargando..."
				selectDestino.length=0;
				var nuevaOpcion=document.createElement("option");
				nuevaOpcion.value="";
				nuevaOpcion.innerHTML="Cargando...";
				selectDestino.appendChild(nuevaOpcion);
				selectDestino.disabled=true;
			}
			if (ajax.readyState==4)	{
				//alert(ajax.responseText);
				selectDestino.parentNode.innerHTML=ajax.responseText;
			}
		}
	}
}
///	--------------------------------------------------------------------------------
/// -------------------		CARGAR VALOR PARA EL CAMPO CATEGORIA
function cargarCampoCategoria(valor){
 var valorEnviado = document.getElementById(valor).value; //alert(valorEnviado);
 if(valorEnviado!=""){
 var ajax=nuevoAjax();
	ajax.open("POST", "af_fphp_ajax.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=cargarCampoCategoria&valorEnviado="+valorEnviado);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText.trim();
			if (resp != ""){ var respuesta = resp.split('-');
			                 document.getElementById("categoria").value = respuesta[0]; //alert('valor = '+document.getElementById("categoria").value);
							 /*document.getElementById("naturaleza").value = respuesta[1];alert('valor2 = '+document.getElementById("naturaleza").value);
							 document.getElementById("valorNaturaleza").value = respuesta[2]; alert('valorNaturaleza='+document.getElementById("valorNaturaleza").value);*/
							}
		}
	}
 }else{
	  document.getElementById("naturaleza").value = "Activo Normal";
	  document.getElementById("valorNaturaleza").value = "AN";
	  document.getElementById("categoria").value = "";
 }
}
/// --------------------------------------------------------------------------------
/// -------------------		FUNCION MOSTRAR VENTANA
function cargarVentanaLista(form, pagina, param) {
	window.open(pagina, "wPrincipal2", "toolbar=no, menubar=no, location=no, scrollbars=yes, "+param);
}
function cargarVentanaLista03(form, pagina, param) {
	window.open(pagina, "wPrincipal2", "toolbar=no, menubar=no, location=no, scrollbars=yes, "+param);
}
function cargarVentanaLista02(form, pagina, param) {
	var destino = document.getElementById("selector").value;
	var nrodetalle = document.getElementById("nrodetalle").value;
	window.open(pagina+"&destino="+destino+"&nrodetalle="+nrodetalle, "wPrincipal2", "toolbar=no, menubar=no, location=no, scrollbars=yes, "+param);
}
function cargarVentanaSelectorCuentas(form, pagina, param) {
	var destino = document.getElementById("selector").value; 
	window.open(pagina+"&destino="+destino, "wPrincipal2", "toolbar=no, menubar=no, location=no, scrollbars=yes, "+param);
}
/// --------------------------------------------------------------------------------
//	-------------------		FUNCION PARA SELECCIONAR UNA PERSONA DE UNA LISTA Y 
//							COLOCARLO EN OTRA VENTANA
function selEmpleado(busqueda, campo, variable, otro1, otro2, otro3) { //alert('paso');
	var registro=document.getElementById("registro").value; //alert(registro); alert(variable); alert(campo);
	if (campo==1){
		parent.document.frmentrada.clasificacion.value=registro;
		parent.document.frmentrada.clasificacion2.value=variable;
	}else
	 if(campo==2){ 
	    parent.document.frmentrada.ubicacion.value=registro;
		parent.document.frmentrada.ubicacion2.value=variable; 
	 }else
	 if (campo==7){
		parent.document.frmentrada.cod_usuario.value=registro;
		parent.document.frmentrada.nomb_usuario.value=variable;
	}else
	if(campo==8){
	   parent.document.frmentrada.cod_empresponsable.value=registro;
	   parent.document.frmentrada.empleado_responsable.value=variable;
	}else
	if(campo==9){
	   parent.document.getElementById("centro_costos").value=registro;  
	   parent.document.getElementById("centro_costos2").value=variable; 
	}else
	if(campo==10){
	  opener.document.getElementById("c_costosActual").value = registro;
	  opener.document.getElementById("c_costosActual2").value = variable; 
	}else
	if(campo==11){
	 opener.document.getElementById("ubicacion_Actual").value= registro;
	 opener.document.getElementById("ubicacion_Actual2").value= variable;
	}else
	if(campo==12){
	 opener.document.getElementById("e_usuarioActual").value=registro;
	 opener.document.getElementById("e_usuarioActual2").value=variable;
	}else
	if(campo==13){
	 opener.document.getElementById("e_responsableActual").value = registro ;
	 opener.document.getElementById("e_responsableActual2").value = variable;
	}else
	if(campo==14){
	 opener.document.getElementById("dependenciaActual").value = registro ;
	 opener.document.getElementById("dependenciaActual2").value = variable;
	}else
	if(campo==15){
	  opener.document.getElementById("apro_por").value = registro ;
  	  opener.document.getElementById("apro_por2").value = variable ;	
	}else
	if(campo==16){
		parent.document.getElementById("activo_principal").value = registro;
		parent.document.getElementById("activo_principal2").value = variable;
	}else
	if(campo==17){
	   parent.document.getElementById("fClasificacion").value = registro; 
	   parent.document.getElementById("DescpClasificacion").value = variable; 
	   parent.document.getElementById("fCatClasf").value = otro1;  
	   parent.document.getElementById("fCodCatClasf").value = otro2;   
	}else
	if(campo==18){
	   parent.document.getElementById("fClasf20").value = registro;
	   parent.document.getElementById("DescpClasf20").value = variable;
	}else
	if(campo==19){
		parent.document.getElementById("activo_consolidado").value = registro;
		parent.document.getElementById("activo_consolidado2").value = variable;
	}else
	if(campo==20){
	  opener.document.getElementById("fNroActivo").value = registro; // Numero del Activo
	  opener.document.getElementById("descripcion").value = variable; // Descripcion del Activo
	  opener.document.getElementById("fClasificacion").value = otro1; // Codigo Clasificacion Activo
	  opener.document.getElementById("DescpClasificacion").value = otro2; // Descripcion de la clasificacion del Activo
	  
	  //alert(otro3);
	  var arreglo = otro3.split("|");
	  var CodCentroCosto = arreglo[0];   var CodUbicacion = arreglo[2]; var DescpSituActivo = arreglo[4];
	  var DescpCentroCosto = arreglo[1]; var DescpUbicacion  = arreglo[3]; var CodigoInterno = arreglo[5];
	  var Naturaleza = arreglo[6];
	  
	  
	  //opener.document.getElementById("fCentroCosto").value = CodCentroCosto; // Codigo centro costo
	  //opener.document.getElementById("DescpCentroCosto").value = DescpCentroCosto; // Descripcion centro costo
	  opener.document.getElementById("fUbicacion").value = CodUbicacion; // Codigo Ubicacion
	  opener.document.getElementById("DescpUbicacion").value = DescpUbicacion; // Descripcion Ubicacion
	  opener.document.getElementById("DescpSituActivo").value = DescpSituActivo; // Descripcion Situacion Activo
	  opener.document.getElementById("codInterno").value = CodigoInterno; // Codigo Interno
	  opener.document.getElementById("centro_costos").value = CodCentroCosto;
	  opener.document.getElementById("centro_costos2").value = DescpCentroCosto;
	  opener.document.getElementById("Naturaleza").value = Naturaleza;
	}else
	if(campo==21){
	  opener.document.getElementById("fUbicacion").value= registro;
	  opener.document.getElementById("DescpUbicacion").value=variable;
	}else
	if(campo==22){
	  opener.document.getElementById("fCentroCosto").value = registro;
	  opener.document.getElementById("fCentroCosto2").value = variable;
	}else
	 if(campo==23){
	   opener.document.getElementById("fPersona").value = registro;
	   opener.document.getElementById("NombPersona").value = variable;	 
	 }else
	 if(campo==24){
	   parent.document.getElementById("fClasificacion").value = registro; 
	   parent.document.getElementById("DescpClasificacion").value = variable; 
	 }else
	 if(campo==25){
		opener.document.getElementById("fConsolidado").value = registro;
		opener.document.getElementById("fNomConsolidado").value = variable;
	 }else
	  if(campo==26){ 
	    parent.document.frmentrada.fubicacion.value=registro;
		parent.document.frmentrada.fubicacion2.value=variable; 
	 }else
	 if(campo==27){ 
	    parent.document.frmentrada.fubicacion.value=registro;
		parent.document.frmentrada.fubicacion2.value=variable; 
	 }else
	 if(campo==28){ 
	   opener.document.getElementById("centro_costos").value=registro;
	   opener.document.getElementById("centro_costos2").value=variable;
	 }else
	 if(campo==29){
	   parent.document.getElementById("fub_actual").value= registro;
	   parent.document.getElementById("fub_actual_descp").value= variable;
	 }else
	 if(campo==30){
	   parent.document.getElementById("fub_anterior").value= registro;
	   parent.document.getElementById("fub_anterior_descp").value= variable;
	 }
	 
	 if((campo!='18')&&(campo!='26')&&
	    (campo!='24')&&(campo!='2')&&
		(campo!='1')&&(campo!='9')&&
		(campo!='7')&&(campo!='8')&&
		(campo!='19')&&(campo!='17')&&
		(campo!='27')&&(campo!='16')&&
		(campo!='29')&&(campo!='30'))window.close();
	 else parent.$.prettyPhoto.close();
}
/// --------------------------------------------------------------------------------
function totalLista(rows) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
}
/// --------------------------------------------------------------------------------
/// -------------------		ACTIVAR Y DESACTIVAR CAMPOS
/// --------------------------------------------------------------------------------
function enabledCategoria(form){
  if(form.checkCategoria.checked) form.fCategoria.disabled=false;
  else{ form.fCategoria.disabled=true; form.fCategoria.value="";}
}
/// --------------------------------------------------------------------------------
function enabledCosto(form){
 if(form.checkCosto.checked){ form.centro_costos2.disabled=false; form.btcosto.disabled=false; document.getElementById("c_costo").style.visibility='visible';
 }else{ form.centro_costos2.disabled=true; form.centro_costos2.value=""; form.centro_costos.value=""; form.btcosto.disabled=true;
 document.getElementById("c_costo").style.visibility='hidden';}
}
/// --------------------------------------------------------------------------------
function enabledDependencia(form){
  if(form.checkDependencia.checked) form.fDependencia.disabled=false;
  else{ form.fDependencia.disabled=true; form.fDependencia.value='';}
}
/// --------------------------------------------------------------------------------
function enabledSituacionActivo(form){
 if(form.checkSituacionActivo.checked) form.fSituacionActivo.disabled=false;
 else{ form.fSituacionActivo.disabled=true; form.fSituacionActivo.value='';}
}
/// --------------------------------------------------------------------------------
function enabledTipoActivo(form){
 if(form.checkTipoActivo.checked) form.fTipoActivo.disabled=false;
 else{ form.fTipoActivo.disabled=true; form.fTipoActivo.value='';}
}
/// --------------------------------------------------------------------------------
function enabledT_Seguro(form){
 if(form.checkT_Seguro.checked) form.fT_Seguro.disabled=false;
 else{form.fT_Seguro.disabled=true; form.fT_Seguro.value='';}
}
/// --------------------------------------------------------------------------------
function enabledColor(form){
 if(form.checkColor.checked) form.fColor.disabled=false;
 else{ form.fColor.disabled=true; form.fColor.value='';}
}
/// --------------------------------------------------------------------------------
/*function enabledCatClasf(form){
 if(form.checkCatClasf.checked) form.fCatClasf.disabled=false;
 else{ form.fCatClasf.disabled=true; form.fCatClasf.value='';}	
}*/
/// --------------------------------------------------------------------------------
function enabledUbicacionListaActivos(form){
 if(form.checkUbicacion.checked){ form.fubicacion2.disabled=false; form.btUbicacion.disabled = false; document.getElementById("ubicacion").style.visibility='visible';
}else{ form.fubicacion2.disabled=true; form.fubicacion2.value=''; form.btUbicacion.disabled = true; form.fubicacion.value=''; document.getElementById("ubicacion").style.visibility='hidden';}
}
/// --------------------------------------------------------------------------------
function enabledEstado(form){ //alert("Y");
 if(form.checkEstado.checked) form.fEstado.disabled=false;
 else{ form.fEstado.disabled=true; form.fEstado.value='';}
}
/// --------------------------------------------------------------------------------
function enabledCatFinanciera(form){
 if(form.checkCatFinanciera.checked) form.fCatFinanciera.disabled=false;
 else{ form.fCatFinanciera.disabled=true; form.fCatFinanciera.value='';}
}
/// --------------------------------------------------------------------------------
function guardarCatastro(form) {
	
	var descp_catastro = document.getElementById("descp_catastro").value.trim(); //alert(descp_catastro);
	var radioEstado = document.getElementById("radioEstado").value.trim(); //alert(radioEstado);
	var detalles = "";
	var error_detalles = "";
	
	// obtengo los valores de las lineas insertadas
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		/*if (n.name == "select1") {
			if (n.value == "") { error_detalles = "Debe seleccionar algo"; break; }
			else detalles += n.value + "|";
		}*/
		/*if (n.name == "ano") detalles += n.value + "|";
		else if (n.name == "precio_Oficial") detalles += n.value + "|";
		else if (n.name == "precio_Mercado") detalles += n.value + "|";
		else if (n.name == "fecha_Referencial") detalles += n.value + ";";*/
		if (n.name == "id") detalles += n.value + "|";
		else if (n.name == "ano") detalles += n.value + "|";
		else if (n.name == "precio_Oficial") detalles += n.value + "|";
		else if (n.name == "precio_Mercado") detalles += n.value + "|";
		else if (n.name == "fecha_Referencial") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	//if (campo1 == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	//else 
	if (error_detalles != "") alert(error_detalles);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "gmactivofijo.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=guardarCatastro&descp_catastro="+descp_catastro+"&radioEstado="+radioEstado+"&detalles="+detalles);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim()!= "") alert(resp);
				else cargarPagina(form, "af_catastro.php");
				//cargarPagina(form, "af_catastro.php");
			}
		}
	}
	return false;
}
/// --------------------------------------------------------------------------------
/// FUNCION PARA ASIGNAR ESTADO
function asignarEstado1(form){
    if(form.status_catastro1.checked){ 
      document.getElementById("radioEstado").value= 'A'; 
	  form.status_catastro2.checked = false;
    }    
}
/// --------------------------------------------------------------------------------
function asignarEstado2(form){
 if(form.status_catastro2.checked){ 
      document.getElementById("radioEstado").value= 'I'; 
	  form.status_catastro1.checked = false; 
  }
}
/// --------------------------------------------------------------------------------
function asignarEstado3(form){
 var estado  = document.getElementById('radioEstado').value; 
 if(estado == 'A'){
	 if(form.status_catastro2.checked) form.status_catastro1.checked = false;
	 document.getElementById('radioEstado').value = 'I' ; 
 }else{
    if(form.status_catastro1.checked) form.status_catastro2.checked = false;
	document.getElementById('radioEstado').value = 'A' ;
 }
}
/// --------------------------------------------------------------------------------
/// --------------------------------------------------------------------------------
///	FUNCION PARA CARGAR UNA NUEVA PAGINA 
function cargarPaginaAgregar(form, pagina) {
	form.method="POST";
	form.action=pagina;
	form.submit();
}
/// --------------------------------------------------------------------------------
function guardarCatastroEditar(form) {
	var cod_catastro = document.getElementById("cod_catastro").value.trim(); //alert(cod_catastro);
	var descp_catastro = document.getElementById("descp_catastro").value.trim(); //alert(descp_catastro);
	var radioEstado = document.getElementById("radioEstado").value.trim();//alert(radioEstado);
	var detalles = "";
	var error_detalles = "";
	
	// obtengo los valores de las lineas insertadas
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		/*if (n.name == "select1") {
			if (n.value == "") { error_detalles = "Debe seleccionar algo"; break; }
			else detalles += n.value + "|";
		}*/
		/*if (n.name == "ano") detalles += n.value + "|";
		else if (n.name == "precio_Oficial") detalles += n.value + "|";
		else if (n.name == "precio_Mercado") detalles += n.value + "|";
		else if (n.name == "fecha_Referencial") detalles += n.value + ";";*/
		if (n.name == "id") detalles += n.value + "|";
		else if (n.name == "ano") detalles += n.value + "|";
		else if (n.name == "precio_Oficial") detalles += n.value + "|";
		else if (n.name == "precio_Mercado") detalles += n.value + "|";
		else if (n.name == "fecha_Referencial") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	//if (campo1 == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	//else 
	if (error_detalles != "") alert(error_detalles);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "gmactivofijo.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=guardarCatastroEditar&descp_catastro="+descp_catastro+"&radioEstado="+radioEstado+"&detalles="+detalles+"&cod_catastro="+cod_catastro);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp);
				else cargarPagina(form, "af_catastro.php");
				//cargarPagina(form, "af_catastro.php");
			}
		}
	}
	return false;
}
/// --------------------------------------------------------------------------------
function quitarLineaCatastroEditar(seldetalle) {
	
	//alert('Esta seguro de desear eliminar este registro...');
    var eliminar=confirm("¡Esta seguro de eliminar este registro?");	
	if(eliminar){
		var listaDetalles = document.getElementById("listaDetalles");
	    var tr = document.getElementById(seldetalle);
		var id_catanual= document.getElementById(seldetalle).id; //alert("valor_tr="+id_catanual); 
		    listaDetalles.removeChild(tr);
	    document.getElementById("seldetalle").value = "";
		// ---------------------------------------------- //
		var cod_catastro = document.getElementById("cod_catastro").value.trim(); //alert(cod_catastro);
		
		var ajax=nuevoAjax();
		ajax.open("POST", "gmactivofijo.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=EliminarCatastroEditado&cod_catastro="+cod_catastro+"&id_catanual="+id_catanual);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert ("¡"+resp+"!");
				else cargarPagina(form, "af_catastro.php?limit=0");
			}
		}
	}
}
/// --------------------------------------------------------------------------------
/// -------------------		GUARDAR TIPO SEGURO DE MAESTRO CATEGORIA
function guardarTipoSeguro(formulario){
  var cod_tseguro = document.getElementById("cod_tseguro").value.trim(); 
  var descp_tseguro = document.getElementById("descp_tseguro").value.trim(); 
  var radioEstado = document.getElementById("radioEstado").value.trim(); 
  
  //VALIDACION CODIGO TIPO DE SEGUROS
if (formulario.cod_tseguro.value.length <1) {
 alert("Introduzca el tipo de activo en el campo \"Tipo Seguro\".");
 formulario.cod_tseguro.focus();
return (false);
}
var checkOK = "0123456789" + "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ";
var checkStr = formulario.cod_tseguro.value;
var allValid = true; 
for (i = 0; i < checkStr.length; i++) {
  ch = checkStr.charAt(i); 
  for (j = 0; j < checkOK.length; j++)
	  if (ch == checkOK.charAt(j))
	  break;
	  if (j == checkOK.length) { 
		 allValid = false; 
	  break; 
	  }
}
if (!allValid) { 
 alert("Introduzca carateres permitidos en el campo \"Tipo Seguro\"."); 
 formulario.cod_tseguro.focus(); 
 return (false); 
} 

//VALIDACION DESCRIPCION
if (formulario.descp_tseguro.value.length <2) {
 alert("Introduzca la descripción en el Campo \"Descripción\".");
 formulario.descp_tseguro.focus();
return (false);
}
var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ" + " ._/";
var checkStr = formulario.descp_tseguro.value;
var allValid = true; 
for (i = 0; i < checkStr.length; i++) {
  ch = checkStr.charAt(i); 
  for (j = 0; j < checkOK.length; j++)
	  if (ch == checkOK.charAt(j))
	  break;
	  if (j == checkOK.length) { 
		 allValid = false; 
	  break; 
	  }
}
if (!allValid) { 
 alert("Introduzca sólo caracteres permitidos en el campo \"Descripción\"."); 
 formulario.descp_tseguro.focus(); 
 return (false); 
} 

  var ajax=nuevoAjax();
	  ajax.open("POST", "gmactivofijo.php", true);
	  ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	  ajax.send("accion=guardarTipoSeguro&cod_tseguro="+cod_tseguro+"&descp_tseguro="+descp_tseguro+"&radioEstado="+radioEstado); 
	  //alert("accion=guardarTipoSeguro&cod_tseguro="+cod_tseguro+"&descp_tseguro="+descp_tseguro+"&radioEstado="+radioEstado);
	  ajax.onreadystatechange=function(){
		if (ajax.readyState==4)	{
			var resp = ajax.responseText; //alert("resp="+resp);
			if (resp.trim() != "") alert(resp);
			else cargarPagina(formulario, "af_tseguros.php");
		}
	  }
	  return false;
}
/// -------------------------------------------------------------------------------- ///
/// -------------------		ELIMINAR TIPO DE SEGURO
function eliminarTipoSeguros(form) {
	var codigo=form.registro.value; //alert("codigo= "+codigo);
	if (codigo=="") msjError(1000);
	else {
		//	PREGUNTO SI DESEA ELIMINAR
		var eliminar=confirm("¡Esta seguro de eliminar este registro?");
		if (eliminar) {
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax=nuevoAjax();
				ajax.open("POST", "gmactivofijo.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("accion=ELIMINARTIPOSEGUROS&codigo="+codigo);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4)	{
						var error=ajax.responseText;
						if (error!=0) alert ("¡"+error+"!");
						else cargarPagina(form, "af_tseguros.php?limit=0");
					}
				}
		}
	}
}
/// --------------------------------------------------------------------------------
/// -------------------		CONTROLAR EL ESTADO
function status(form){
  var estado  = document.getElementById('radioEstado').value; //alert('Estado = '+estado);
 if(estado == 'A'){
	 if(form.status_tseguro2.checked) form.status_tseguro1.checked = false;
	 document.getElementById('radioEstado').value = 'I' ; //alert('1='+document.getElementById("radioEstado").value);
 }else{
    if(form.status_tseguro1.checked) form.status_tseguro2.checked = false;
	document.getElementById('radioEstado').value = 'A' ; //alert('2 ='+document.getElementById("radioEstado").value);
 }
}
/// --------------------------------------------------------------------------------
/// -------------------		EDITAR TIPO SEGURO
function guardarEditarTipoSeguro(formulario){
 var cod_tseguro = document.getElementById("cod_tseguro").value; //alert("cod_tseguro="+cod_tseguro);	
 var descp_tseguro = document.getElementById("descp_tseguro").value.trim(); //alert("descp_tseguro="+descp_tseguro);
 var radioEstado = document.getElementById("radioEstado").value; //alert("radioEstado="+radioEstado);
 
 //VALIDACION DESCRIPCION
	if (formulario.descp_tseguro.value.length <2) {
	 alert("Introduzca la descripción en el Campo \"Descripción\".");
	 formulario.descp_tseguro.focus();
	return (false);
	}
	var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ" + " ._/";
	var checkStr = formulario.descp_tseguro.value;
	var allValid = true; 
	for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
	}
	if (!allValid) { 
	 alert("Introduzca sólo caracteres permitidos en el campo \"Descripción\"."); 
	 formulario.descp_tseguro.focus(); 
	 return (false); 
	} 
 
	var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true); 
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=EditarTipoSeguro&descp_tseguro="+descp_tseguro+"&radioEstado="+radioEstado+"&cod_tseguro="+cod_tseguro);
		ajax.onreadystatechange=function(){
		if (ajax.readyState==4)	{
			var resp = ajax.responseText; //alert("resp="+resp);
			if (resp.trim() != "") alert(resp);
			else cargarPagina(formulario, "af_tseguros.php");
		}
	  }
	  return false;
}
/// --------------------------------------------------------------------------------
/// -------------------	 CONTROL DE ESTADO
function estadosPosee(form, valor){
  var estado  = document.getElementById('radioEstado').value; //alert('Estado = '+estado);
 if((estado == 'A')&&(form.radio1.checked)&&(valor!="a")) {
	   form.radio1.checked = false; 
	   form.radio2.checked = true; 
	   document.getElementById('radioEstado').value = 'I' ; //alert('1='+document.getElementById("radioEstado").value);
 }else{
	 if((estado == 'I')&&(form.radio2.checked)&&(valor!="b")) {
	   form.radio2.checked = false;
	   form.radio1.checked = true; 
	   document.getElementById('radioEstado').value = 'A' ; //alert('2 ='+document.getElementById("radioEstado").value);
	 }
 }
}
function estadosPosee02(form, valor){
  var estado  = document.getElementById('radioEstado').value; //alert('Estado = '+estado);
 if((estado == 'I')&&(form.radio1.checked)&&(valor!="a")) {
	   form.radio1.checked = false; 
	   form.radio2.checked = true; 
	   document.getElementById('radioEstado').value = 'E' ; //alert('1='+document.getElementById("radioEstado").value);
 }else{
	 if((estado == 'E')&&(form.radio2.checked)&&(valor!="b")) {
	   form.radio2.checked = false;
	   form.radio1.checked = true; 
	   document.getElementById('radioEstado').value = 'I' ; //alert('2 ='+document.getElementById("radioEstado").value);
	 }
 }
}
/// --------------------------------------------------------------------------------
/// -------------------		FUNCION PARA GUARDAR EL TIPO DE VEHICULO
function guardarTipoVehiculo(formulario){
 
  var cod_tvehiculo = document.getElementById("cod_tvehiculo").value.trim(); //alert("cod_tvehiculo="+cod_tvehiculo);
  var descp_tvehiculo = document.getElementById("descp_tvehiculo").value.trim(); //alert("descp_tvehiculo"+descp_tvehiculo);
  var radioEstado = document.getElementById("radioEstado").value; //alert("radioEstado"+radioEstado);
  
  //VALIDACION CODIGO TIPO DE SEGUROS 
  if (formulario.cod_tvehiculo.value.length <1) {
 alert("Introduzca el tipo de vehículo en el campo \"Tipo Vehículo\".");
 formulario.cod_tvehiculo.focus();
return (false);
}
var checkOK = "0123456789" + "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ";
var checkStr = formulario.cod_tvehiculo.value;
var allValid = true; 
for (i = 0; i < checkStr.length; i++) {
  ch = checkStr.charAt(i); 
  for (j = 0; j < checkOK.length; j++)
	  if (ch == checkOK.charAt(j))
	  break;
	  if (j == checkOK.length) { 
		 allValid = false; 
	  break; 
	  }
}
if (!allValid) { 
 alert("Introduzca carateres permitidos en el campo \"Tipo Vehículo\"."); 
 formulario.cod_tvehiculo.focus(); 
 return (false); 
} 

  //VALIDACION DESCRIPCION
  if (formulario.descp_tvehiculo.value.length <2) {
 alert("Introduzca la descripción en el Campo \"Descripción\".");
 formulario.descp_tvehiculo.focus();
return (false);
}
var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ" + " ._/";
var checkStr = formulario.descp_tvehiculo.value;
var allValid = true; 
for (i = 0; i < checkStr.length; i++) {
  ch = checkStr.charAt(i); 
  for (j = 0; j < checkOK.length; j++)
	  if (ch == checkOK.charAt(j))
	  break;
	  if (j == checkOK.length) { 
		 allValid = false; 
	  break; 
	  }
}
if (!allValid) { 
 alert("Introduzca sólo caracteres permitidos en el campo \"Descripción\"."); 
 formulario.descp_tvehiculo.focus(); 
 return (false); 
} 

  var ajax=nuevoAjax();
	  ajax.open("POST", "gmactivofijo.php", true);
	  ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	  ajax.send("accion=guardarTipoVehiculo&cod_tvehiculo="+cod_tvehiculo+"&descp_tvehiculo="+descp_tvehiculo+"&radioEstado="+radioEstado);
	  ajax.onreadystatechange=function(){
		if (ajax.readyState==4)	{
			var resp = ajax.responseText; //alert("resp="+resp);
			if (resp.trim()!= "") alert(resp);
			else cargarPagina(formulario, "af_tvehiculos.php");
		}
	  }
   return false;
}
/// --------------------------------------------------------------------------------
/// -------------------		FUNCION QUE PERMITE EDITAR EL TIPO DE VEHICULO
function EditarTipoVehiculo(formulario){
   var cod_tvehiculo = document.getElementById("cod_tvehiculo").value.trim(); //alert("cod_tvehiculo="+cod_tvehiculo);
   var descp_tvehiculo = document.getElementById("descp_tvehiculo").value.trim(); ///alert("descp_tvehiculo"+descp_tvehiculo);
   var radioEstado = document.getElementById("radioEstado").value; //alert("radioEstado"+radioEstado);
   
   //VALIDACION DESCRIPCION
   if (formulario.descp_tvehiculo.value.length <2) {
    alert("Introduzca la descripción en el Campo \"Descripción\".");
    formulario.descp_tvehiculo.focus();
    return (false);
   }
	var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ" + " ._/";
	var checkStr = formulario.descp_tvehiculo.value;
	var allValid = true; 
	for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
	}
	if (!allValid) { 
	 alert("Introduzca sólo caracteres permitidos en el campo \"Descripción\"."); 
	 formulario.descp_tvehiculo.focus(); 
	 return (false); 
	} 

      var ajax=nuevoAjax();
	  ajax.open("POST", "gmactivofijo.php", true);
	  ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	  ajax.send("accion=EditarTipoVehiculo&cod_tvehiculo="+cod_tvehiculo+"&descp_tvehiculo="+descp_tvehiculo+"&radioEstado="+radioEstado);
	  ajax.onreadystatechange=function(){ 
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			if (resp.trim()!="") alert(resp);
			else cargarPagina(formulario, "af_tvehiculos.php");
		}
	  }
   return (false);
}
/// --------------------------------------------------------------------------------
/// -------------------		FUNCION QUE PERMITE GUARDAR POLIZA SEGUROS
function guardarPolizaSeguro(formulario){
  var cod_pseguro = document.getElementById("cod_pseguro").value.trim();
  var descp_pseguro = document.getElementById("descp_pseguro").value.trim();
  var empa_pseguro = document.getElementById("empa_pseguro").value;
  var ages_pseguro = document.getElementById("ages_pseguro").value.trim();
  var fvenc_pseguro = document.getElementById("fvenc_pseguro").value;
  var mcober_pseguro = document.getElementById("mcober_pseguro").value;
  var cpoli_pseguro = document.getElementById("cpoli_pseguro").value;
  var radioEstado = document.getElementById("radioEstado").value;
  
  
  //VALIDACION CODIGO POLIZA DE SEGURO
 if (formulario.cod_pseguro.value.length <1) {
 alert("Introduzca el código de la póliza en el campo \"Póliza de Seguro\".");
 formulario.cod_pseguro.focus();
return (false);
}
var checkOK = "0123456789" + "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ";
var checkStr = formulario.cod_pseguro.value;
var allValid = true; 
for (i = 0; i < checkStr.length; i++) {
  ch = checkStr.charAt(i); 
  for (j = 0; j < checkOK.length; j++)
	  if (ch == checkOK.charAt(j))
	  break;
	  if (j == checkOK.length) { 
		 allValid = false; 
	  break; 
	  }
}
if (!allValid) { 
 alert("Introduzca carateres permitidos en el campo \"Póliza de Seguro\"."); 
 formulario.cod_pseguro.focus(); 
 return (false); 
} 

 //VALIDACION DESCRIPCION
 if (formulario.descp_pseguro.value.length <2) {
  alert("Introduzca la descripción en el Campo \"Descripción\".");
  formulario.descp_pseguro.focus();
  return (false);
 }
  var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ" + " ._/-" + "0123456789";
  var checkStr = formulario.descp_pseguro.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
    ch = checkStr.charAt(i); 
    for (j = 0; j < checkOK.length; j++)
  	   if (ch == checkOK.charAt(j))
	    break;
	    if (j == checkOK.length) { 
		 allValid = false; 
	    break; 
	    }
  }
  if (!allValid) { 
   alert("Introduzca sólo caracteres permitidos en el campo \"Descripción\"."); 
   formulario.descp_pseguro.focus(); 
   return (false); 
} 

 //VALIDACION EMPRESA ASEGURADORA
 if (formulario.empa_pseguro.value.length <2) {
  alert("Introduzca el nombre de la Empresa en el Campo \"Empresa Aseguradora\".");
  formulario.empa_pseguro.focus();
  return (false);
 }
  var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ" + " ._/" + "0123456789";
  var checkStr = formulario.empa_pseguro.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
    ch = checkStr.charAt(i); 
    for (j = 0; j < checkOK.length; j++)
  	   if (ch == checkOK.charAt(j))
	    break;
	    if (j == checkOK.length) { 
		 allValid = false; 
	    break; 
	    }
  }
  if (!allValid) { 
   alert("Introduzca sólo caracteres permitidos en el campo \"Empresa Aseguradora\"."); 
   formulario.empa_pseguro.focus(); 
   return (false); 
} 
 
 //VALIDACION MONTO COBERTURA
 if (formulario.mcober_pseguro.value.length <1) {
  alert("Introduzca el monto en el Campo \"Monto Cobertura\".");
  formulario.mcober_pseguro.focus();
  return (false);
 }
  var checkOK = ".," + "0123456789";
  var checkStr = formulario.mcober_pseguro.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
    ch = checkStr.charAt(i); 
    for (j = 0; j < checkOK.length; j++)
  	   if (ch == checkOK.charAt(j))
	    break;
	    if (j == checkOK.length) { 
		 allValid = false; 
	    break; 
	    }
  }
  if (!allValid) { 
   alert("Introduzca sólo caracteres permitidos en el campo \"Monto Cobertura\"."); 
   formulario.mcober_pseguro.focus(); 
   return (false); 
} 

 //VALIDACION AGENTE SEGUROS
 if (formulario.ages_pseguro.value.length <1) {
  alert("Introduzca datos del agente en el Campo \"Agente Seguros\".");
  formulario.ages_pseguro.focus();
  return (false);
 }
  var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ" + " ._/" + "0123456789";
  var checkStr = formulario.ages_pseguro.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
    ch = checkStr.charAt(i); 
    for (j = 0; j < checkOK.length; j++)
  	   if (ch == checkOK.charAt(j))
	    break;
	    if (j == checkOK.length) { 
		 allValid = false; 
	    break; 
	    }
  }
  if (!allValid) { 
   alert("Introduzca sólo caracteres permitidos en el campo \"Agente Seguros\"."); 
   formulario.ages_pseguro.focus(); 
   return (false); 
} 

 //VALIDACION COSTO POLIZA
 if (formulario.cpoli_pseguro.value.length <1) {
  alert("Introduzca el monto en el Campo \"Costo de Poliza\".");
  formulario.cpoli_pseguro.focus();
  return (false);
 }
  var checkOK = ".," + "0123456789";
  var checkStr = formulario.cpoli_pseguro.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
    ch = checkStr.charAt(i); 
    for (j = 0; j < checkOK.length; j++)
  	   if (ch == checkOK.charAt(j))
	    break;
	    if (j == checkOK.length) { 
		 allValid = false; 
	    break; 
	    }
  }
  if (!allValid) { 
   alert("Introduzca sólo caracteres permitidos en el campo \"Costo de Poliza\"."); 
   formulario.cpoli_pseguro.focus(); 
   return (false); 
} 


  var ajax=nuevoAjax();
	  ajax.open("POST", "gmactivofijo.php", true);
	  ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	  ajax.send("accion=guardarPolizaSeguro&cod_pseguro="+cod_pseguro+"&descp_pseguro="+descp_pseguro+"&empa_pseguro="+empa_pseguro+"&ages_pseguro="+ages_pseguro+"&fvenc_pseguro="+fvenc_pseguro+"&mcober_pseguro="+mcober_pseguro+"&cpoli_pseguro="+cpoli_pseguro+"&radioEstado="+radioEstado);
	  ajax.onreadystatechange=function(){ 
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			if (resp.trim()!="") alert(resp);
			else 
			cargarPagina(formulario, "af_pseguros.php");
		}
	  }
   return (false);

}
/// --------------------------------------------------------------------------------
/// -------------------		FUNCION PARA ELIMINAR MAESTRO POLIZA SEGURO
function eliminarPseguros(form, seleccion){
 var codigo=form.registro.value; alert("codigo= "+codigo);
	if (codigo=="") msjError(1000);
	else {
		//	PREGUNTO SI DESEA ELIMINAR
		var eliminar=confirm("¡Esta seguro de eliminar este registro?");
		if (eliminar) {
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax=nuevoAjax();
				ajax.open("POST", "gmactivofijo.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("accion=ELIMINARPOLIZASEGUROS&codigo="+codigo);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4)	{
						var error=ajax.responseText;
						if (error!=0) alert ("¡"+error+"!");
						else cargarPagina(form, "af_pseguros.php?limit=0");
					}
				}
		}
	}
}
/// --------------------------------------------------------------------------------
function editarPolizaSeguros( formulario){
   var cod_pseguro = document.getElementById("cod_pseguro").value;
   var descp_pseguro = document.getElementById("descp_pseguro").value.trim();
   var empa_pseguro = document.getElementById("empa_pseguro").value;
   var ages_pseguro = document.getElementById("ages_pseguro").value.trim();
   var fvenc_pseguro = document.getElementById("fvenc_pseguro").value;
   var mcober_pseguro = document.getElementById("mcober_pseguro").value;
   var cpoli_pseguro = document.getElementById("cpoli_pseguro").value;
   var radioEstado = document.getElementById("radioEstado").value;
   
   var ajax=nuevoAjax();
	  ajax.open("POST", "gmactivofijo.php", true);
	  ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	  ajax.send("accion=EditarPolizaSeguros&cod_pseguro="+cod_pseguro+"&descp_pseguro="+descp_pseguro+"&empa_pseguro="+empa_pseguro+"&ages_pseguro="+ages_pseguro+"&fvenc_pseguro="+fvenc_pseguro+"&mcober_pseguro="+mcober_pseguro+"&cpoli_pseguro="+cpoli_pseguro+"&radioEstado="+radioEstado);
	  ajax.onreadystatechange=function(){ 
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			if (resp.trim()!= "") alert(resp);
			else cargarPagina(formulario, "af_pseguros.php?limit=0");
		}
	  }
   return (false);
}
/// --------------------------------------------------------------------------------
///-------------------		 FUNCION QUE PERMITE GUARDAR REGISTRO DE LIBROS CONTABLES
function guardarLibroContable(formulario){
  var cod_librocontable = document.getElementById("cod_librocontable").value.trim();
  var descp_libro = document.getElementById("descp_libro").value.trim();
  var radioEstado = document.getElementById("radioEstado").value;
  
  //VALIDACION CODIGO LIBRO CONTABLE
  if (formulario.cod_librocontable.value.length <2) {
	 alert("Introduzca el código del Libro Contable \"Libro Contable\".");
	 formulario.cod_librocontable.focus();
	return (false);
	}
	var checkOK = "0123456789";
	var checkStr = formulario.cod_librocontable.value;
	var allValid = true; 
	for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
	}
	if (!allValid) { 
	 alert("Introduzca sólo números \"Libro Contable\"."); 
	 formulario.cod_librocontable.focus(); 
	 return (false); 
	} 

  //VALIDACION DESCRIPCION
  if (formulario.descp_libro.value.length <2) {
  alert("Introduzca la Descripción del Libro Contable en el Campo \"Descripción\".");
  formulario.descp_libro.focus();
  return (false);
 }
 var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ" + " ._/´";
 var checkStr = formulario.descp_libro.value;
 var allValid = true; 
 for (i = 0; i < checkStr.length; i++) {
   ch = checkStr.charAt(i); 
   for (j = 0; j < checkOK.length; j++)
 	  if (ch == checkOK.charAt(j))
 	  break;
	  if (j == checkOK.length) { 
		 allValid = false; 
	  break; 
	  }
 }
 if (!allValid) { 
  alert("Introduzca sólo letras en el campo \"Descripción\"."); 
  formulario.descp_libro.focus(); 
 return (false); 
 } 
  
   var ajax=nuevoAjax();
	  ajax.open("POST", "gmactivofijo.php", true);
	  ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	  ajax.send("accion=guardarLibroContable&cod_librocontable="+cod_librocontable+"&descp_libro="+descp_libro+"&radioEstado="+radioEstado);
	  ajax.onreadystatechange=function(){ 
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			if (resp.trim() != "") alert(resp);
			else cargarPagina(formulario, "af_librocontable.php?limit=0");
		}
	  }
   return (false);
  
}
/// --------------------------------------------------------------------------------
/// -------------------		FUNCION QUE PERMITE EDITAR LIBROS CONTABLES
function editarLibroContable(formulario){
  var cod_librocontable = document.getElementById("cod_librocontable").value.trim();
  var descp_libro = document.getElementById("descp_libro").value.trim();
  var radioEstado = document.getElementById("radioEstado").value;
  
   //VALIDACION DESCRIPCION
  if (formulario.descp_libro.value.length <2) {
  alert("Introduzca la Descripción del Libro Contable en el Campo \"Descripción\".");
  formulario.descp_libro.focus();
  return (false);
 }
 var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ" + " ._/´";
 var checkStr = formulario.descp_libro.value;
 var allValid = true; 
 for (i = 0; i < checkStr.length; i++) {
   ch = checkStr.charAt(i); 
   for (j = 0; j < checkOK.length; j++)
 	  if (ch == checkOK.charAt(j))
 	  break;
	  if (j == checkOK.length) { 
		 allValid = false; 
	  break; 
	  }
 }
 if (!allValid) { 
  alert("Introduzca sólo letras en el campo \"Descripción\"."); 
  formulario.descp_libro.focus(); 
 return (false); 
 } 
 
   var ajax=nuevoAjax();
	  ajax.open("POST", "gmactivofijo.php", true);
	  ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	  ajax.send("accion=editarLibroContable&cod_librocontable="+cod_librocontable+"&descp_libro="+descp_libro+"&radioEstado="+radioEstado);
	  ajax.onreadystatechange=function(){ 
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			if (resp.trim() != "") alert(resp);
			else cargarPagina(formulario, "af_librocontable.php?limit=0");
		}
	  }
   return (false);
}
/// --------------------------------------------------------------------------------
/// -------------------		FUNCION PARA INSERTAR LINEAS EN CONTABILIDADES
function insertarLineaContabilidad() {
	var candetalle = document.getElementById("candetalle").value; candetalle++;
	
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=insertarLineaContabilidad&candetalle="+candetalle);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			document.getElementById("candetalle").value = candetalle;
			var resp = ajax.responseText;
			
			var newTr = document.createElement("tr");
			newTr.className = "trListaBody";
			newTr.setAttribute("onclick", "mClk(this, 'seldetalle');");
			newTr.id = "det_"+candetalle;
			document.getElementById("listaDetalles").appendChild(newTr);
			document.getElementById("det_"+candetalle).innerHTML = resp;
		}
	}
}
/// --------------------------------------------------------------------------------
/// -------------------		FUNCION QUE PERMITE GUARDAR MAESTRO CONTABILIDADES
function guardarContabilidades(formulario){
	
	var cod_contabilidad = document.getElementById("cod_contabilidad").value.trim();
	var descp_contabilidad = document.getElementById("descp_contabilidad").value.trim();
	var radioEstado = document.getElementById("radioEstado").value;
	
	
	 //VALIDACION CODIGO LIBRO CONTABLE
	 if (formulario.cod_contabilidad.value.length <1) {
	 alert("Introduzca el código de Contabilidad en el campo \"Contabilidad\".");
	 formulario.cod_contabilidad.focus();
	return (false);
	}
	var checkOK = "0123456789" + "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ";
	var checkStr = formulario.cod_contabilidad.value;
	var allValid = true; 
	for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
	}
	if (!allValid) { 
	 alert("Introduzca sólo números o letras en el campo \"Contabilidad\"."); 
	 formulario.cod_contabilidad.focus(); 
	 return (false); 
	} 

    //VALIDACION DESCRIPCION
	if (formulario.descp_contabilidad.value.length <2) {
	 alert("Introduzca la Descripción del Libro Contable en el Campo \"Descripción\".");
	 formulario.descp_contabilidad.focus();
	return (false);
	}
	var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ" + " ._/";
	var checkStr = formulario.descp_contabilidad.value;
	var allValid = true; 
	for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
	}
	if (!allValid) { 
	 alert("Introduzca sólo letras en el campo \"Descripción\"."); 
	 formulario.descp_contabilidad.focus(); 
	 return (false); 
	} 
	
	
	var detalles = "";
	var error_detalles = "";
	
	// obtengo los valoes de las lineas insertadas
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "codigo") detalles += n.value + "|";
		if (n.name == "l_contable") {
			if (n.value == "") { error_detalles = "¡Debe seleccionar por lo menos un libro contable¡"; break; }
			else detalles += n.value + ";";
		}
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len); //alert(detalles);
	
	if (error_detalles != "") alert(error_detalles);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "gmactivofijo.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=guardarContabilidades&cod_contabilidad="+cod_contabilidad+"&descp_contabilidad="+descp_contabilidad+"&radioEstado="+radioEstado+"&detalles="+detalles);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp);
				else cargarPagina(formulario, "af_contabilidades.php?limit=0");
			}
		}
	}
	return false;
}
/// --------------------------------------------------------------------------------
/// -------------------		FUNCION EDITAR CONTABILIDADES
function editarContabilidades(formulario){
    var cod_contabilidad = document.getElementById("cod_contabilidad").value.trim();
	var descp_contabilidad = document.getElementById("descp_contabilidad").value.trim();
	var radioEstado = document.getElementById("radioEstado").value;
	
	//VALIDACION DESCRIPCION
	if (formulario.descp_contabilidad.value.length <2) {
	 alert("Introduzca la Descripción del Libro Contable en el Campo \"Descripción\".");
	 formulario.descp_contabilidad.focus();
	return (false);
	}
	var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ" + " ._/";
	var checkStr = formulario.descp_contabilidad.value;
	var allValid = true; 
	for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
	}
	if (!allValid) { 
	 alert("Introduzca sólo letras en el campo \"Descripción\"."); 
	 formulario.descp_contabilidad.focus(); 
	 return (false); 
	} 
	
	var detalles = "";
	var error_detalles = "";
	
	// obtengo los valores de las lineas insertadas
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "codigo") detalles += n.value + "|";
		if (n.name == "l_contable") {
			if (n.value == "") { error_detalles = "¡Debe seleccionar por lo menos un libro contable¡"; break; }
			else detalles += n.value + ";";
		}
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len); //alert(detalles);
	
	if (error_detalles != "") alert(error_detalles);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "gmactivofijo.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=editarContabilidades&cod_contabilidad="+cod_contabilidad+"&descp_contabilidad="+descp_contabilidad+"&radioEstado="+radioEstado+"&detalles="+detalles);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != "") alert(resp.trim());
				else cargarPagina(formulario, "af_contabilidades.php?limit=0");
			}
		}
	}
	return false;
}
/// --------------------------------------------------------------------------------
function cargarCodigo(valor, candetalle){
 var l_contable = document.getElementById(valor).value; alert("l_contable="+l_contable);	 
     document.getElementById("codigo_"+candetalle).value = l_contable;

}
/// --------------------------------------------------------------------------------
/// -------------------		FUNCION QUE PERMITE ELIMINAR LINEA DE REGISTRO DE CONTABILIDADES
function quitarLineaEditarContabilidades(seldetalle,form) {
	
	//alert('Esta seguro de desear eliminar este registro...');
    var eliminar=confirm("¡Esta seguro de eliminar este registro?");	
	if(eliminar){
		var listaDetalles = document.getElementById("listaDetalles");
	    var tr = document.getElementById(seldetalle);
		var id_contabilidades= document.getElementById(seldetalle).id; ///alert("valor_tr="+id_contabilidades); 
		    listaDetalles.removeChild(tr);
	    document.getElementById("seldetalle").value = "";
		// ---------------------------------------------- //
		//var cod_catastro = document.getElementById("cod_catastro").value.trim(); //alert(cod_catastro);
		
		var ajax=nuevoAjax();
		ajax.open("POST", "gmactivofijo.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=EliminarEditarContabilidades&id_contabilidades="+id_contabilidades);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert ("¡"+resp+"!");
				//else cargarPagina(form, "af_contabilidadeseditar.php?limit=0");
			}
		}
	}
}
/// --------------------------------------------------------------------------------
/// -------------------		FUNCION QUE PERMITE GUARDAR NUEVA CATEGORIA
function guardarNuevaCategoria(formulario){
  var codcategoria = document.getElementById("codcategoria").value;
  var descp_local = document.getElementById("descp_local").value.trim();
  var v_historico = document.getElementById("v_historico").value.trim();
  var cg_depreciacion = document.getElementById("cg_depreciacion").value.trim();
  var t_depreciacion = document.getElementById("t_depreciacion").value;
  var cc_adiciones = document.getElementById("cc_adiciones").value;
  var cg_ajinflacion = document.getElementById("cg_ajinflacion").value;
  var g_categoria = document.getElementById("g_categoria").value;
  var cc_ajinflacion = document.getElementById("cc_ajinflacion").value;
  var cat_invent = document.getElementById("cat_invent").value;
  var occ_rei = document.getElementById("occ_rei").value;
  var cd_pdepreciacion = document.getElementById("cd_pdepreciacion").value;
  var occ_valorneto = document.getElementById("occ_valorneto").value;
  var cd_adiciones = document.getElementById("cd_adiciones").value;
  var occ_ctaresultado = document.getElementById("occ_ctaresultado").value;
  var cd_ajinflacion = document.getElementById("cd_ajinflacion").value;
  var radioEstado = document.getElementById("radioEstado").value; 
  
  
  
  //VALIDACION CODIGO CATEGORIA
   if (formulario.codcategoria.value.length <2) {
	 alert("Escriba el código de la Categoría en el campo \"Código Categoría\".");
	 formulario.codcategoria.focus();
  return (false);
  }
  var checkOK = "0123456789" + "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ" + "._-/";
  var checkStr = formulario.codcategoria.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo numeros en el campo \"Código Categoría\"."); 
	 formulario.codcategoria.focus(); 
	 return (false); 
   } 
   
  //VALIDACION  DESCRIPCION LOCAL
  if (formulario.descp_local.value.length <2) {
	 alert("Escriba la descripción en el campo \"Descripción Local\".");
	 formulario.descp_local.focus();
  return (false);
  }
  var checkOK = "0123456789" + "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ" + " .,;:_-/";
  var checkStr = formulario.descp_local.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Descripción Local\"."); 
	 formulario.descp_local.focus(); 
	 return (false); 
   } 
   
  // VALIDACION VALOR HISTORICO
  if (formulario.v_historico.value.length <2) {
	 alert("Escriba el valor histórico en el campo \"Valor Histórico\".");
	 formulario.v_historico.focus();
  return (false);
  }
  var checkOK = "0123456789" + "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ" + " ._-/";
  var checkStr = formulario.v_historico.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Valor Histórico\"."); 
	 formulario.v_historico.focus(); 
	 return (false); 
   }  
  
  // VALIDACION CUENTAS DE GASTOS -  DEPRECIACION
  if (formulario.cg_depreciacion.value.length <2) {
	 alert("Escriba el valor de la depreciación en el campo \"Cuentas de Gastos - Depreciación\".");
	 formulario.cg_depreciacion.focus();
  return (false);
  }
  var checkOK = "0123456789" + "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ" + " ._-/";
  var checkStr = formulario.cg_depreciacion.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Cuentas de Gastos - Depreciación\"."); 
	 formulario.cg_depreciacion.focus(); 
	 return (false); 
   }  
  
  // VALIDACION CUENTAS CONTABLES CONTABLES ACTIVO - ADICIONES
  if (formulario.cc_adiciones.value.length <2) {
	 alert("Escriba el valor de la adición  en el campo \"Adiciones\".");
	 formulario.cc_adiciones.focus();
  return (false);
  }
  var checkOK = "0123456789";
  var checkStr = formulario.cc_adiciones.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Adiciones\"."); 
	 formulario.cc_adiciones.focus(); 
	 return (false); 
   }  
  
  // VALIDACION CUENTAS CONTABLES CONTABLES ACTIVO - AJUSTES X INFLACION (CC_AJINFLACION)
  if (formulario.cc_ajinflacion.value.length <2) {
	 alert("Escriba el valor del ajuste por inflación en el campo \"Ajustes x Inflación\".");
	 formulario.cc_ajinflacion.focus();
  return (false);
  }
  var checkOK = "0123456789";
  var checkStr = formulario.cc_ajinflacion.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Ajustes x Inflación\"."); 
	 formulario.cc_ajinflacion.focus(); 
	 return (false); 
   } 
  
  // VALIDACION CUENTAS DE GASTOS - AJUSTE x INFLACION (CG_AJINFLACION)
  if (formulario.cg_ajinflacion.value.length <2) {
	 alert("Escriba el valor del ajuste por inflación en el campo \"Cuentas de Gastos - Ajustes x Inflación\".");
	 formulario.cg_ajinflacion.focus();
  return (false);
  }
  var checkOK = "0123456789";
  var checkStr = formulario.cg_ajinflacion.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Cuentas de Gastos - Ajustes x Inflación\"."); 
	 formulario.cg_ajinflacion.focus(); 
	 return (false); 
   } 
  
  // VALIDACION CUENTAS CONTABLES DEPRECIACION  - PARA DEPRECIACION (CD_PDEPRECIACION)
  if (formulario.cd_pdepreciacion.value.length <2) {
	 alert("Escriba el valor del ajuste por inflación en el campo \"Cuentas Contables Depreciación - Para Depreciación\".");
	 formulario.cd_pdepreciacion.focus();
  return (false);
  }
  var checkOK = "0123456789";
  var checkStr = formulario.cd_pdepreciacion.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Cuentas Contables Depreciación - Para Depreciación\"."); 
	 formulario.cd_pdepreciacion.focus(); 
	 return (false); 
   } 
  
  // VALIDACION CUENTAS CONTABLES DEPRECIACION - ADICIONES (CD_ADICIONES)
  if (formulario.cd_adiciones.value.length <1) {
	 alert("Escriba el valor en el campo \"Cuentas Contables Depreciación - Adiciones\".");
	 formulario.cd_adiciones.focus();
  return (false);
  }
  var checkOK = "0123456789";
  var checkStr = formulario.cd_adiciones.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Cuentas Contables Depreciación - Adiciones\"."); 
	 formulario.cd_adiciones.focus(); 
	 return (false); 
   }
   
  // VALIDACION CUENTAS CONTABLES DEPRECIACION - AJUSTES X INFLACION (CD_AJINFLACION)
  if (formulario.cd_ajinflacion.value.length <1) {
	 alert("Escriba el valor en el campo \"Cuentas Contables Depreciación - Ajustes x Inflación\".");
	 formulario.cd_ajinflacion.focus();
  return (false);
  }
  var checkOK = "0123456789";
  var checkStr = formulario.cd_ajinflacion.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Cuentas Contables Depreciación - Ajustes x Inflación\"."); 
	 formulario.cd_ajinflacion.focus(); 
	 return (false); 
   }
   
  // VALIDACION OTRAS CUENTAS CONTABLES - R.E.I (OCC_REI) 
  if (formulario.occ_rei.value.length <1) {
	 alert("Escriba el valor en el campo \"Otras Cuentas Contables - R.E.I\".");
	 formulario.occ_rei.focus();
  return (false);
  }
  var checkOK = "0123456789";
  var checkStr = formulario.occ_rei.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Otras Cuentas Contables - R.E.I\"."); 
	 formulario.occ_rei.focus(); 
	 return (false); 
   }	 
  
  // VALIDACION OTRAS CUENTAS CONTABLES - VALOR NETO (OCC_VALORNETO) 
  if (formulario.occ_valorneto.value.length <1) {
	 alert("Escriba el valor en el campo \"Otras Cuentas Contables - Valor Neto\".");
	 formulario.occ_valorneto.focus();
  return (false);
  }
  var checkOK = "0123456789";
  var checkStr = formulario.occ_valorneto.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Otras Cuentas Contables - Valor Neto\"."); 
	 formulario.occ_valorneto.focus(); 
	 return (false); 
   }	 
   
  // VALIDACION OTRAS CUENTAS CONTABLES - CTA. RESULTADO (OCC_CTARESULTADO) 
  if (formulario.occ_ctaresultado.value.length <1) {
	 alert("Escriba el valor en el campo \"Otras Cuentas Contables - Cta. Resultado\".");
	 formulario.occ_ctaresultado.focus();
  return (false);
  }
  var checkOK = "0123456789";
  var checkStr = formulario.occ_ctaresultado.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Otras Cuentas Contables - Cta. Resultado\"."); 
	 formulario.occ_ctaresultado.focus(); 
	 return (false); 
   }	  
     
	
  var detalles = "";
  var error_detalles = "";
	
  // obtengo los valoes de las lineas insertadas
  var frmdetalles = document.getElementById("frmdetalles");
  for(i=0; n=frmdetalles.elements[i]; i++) {
	if (n.name == "select1") {
		if (n.value == "") { error_detalles = "¡Debe seleccionar por lo menos un libro contable¡"; break; }
		else detalles += n.value + "|";
	}
	if (n.name == "descripcion") detalles += n.value + ";";
	
 }
 var len = detalles.length; len--;
 detalles = detalles.substr(0, len); //alert(detalles);

 if (error_detalles != "") alert(error_detalles);
 else {
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=guardarNuevaCategoria&codcategoria="+codcategoria+"&descp_local="+descp_local+"&v_historico="+v_historico+"&cg_depreciacion="+cg_depreciacion+"&t_depreciacion="+t_depreciacion+"&cc_adiciones="+cc_adiciones+"&cg_ajinflacion="+cg_ajinflacion+"&g_categoria="+g_categoria+"&cc_ajinflacion="+cc_ajinflacion+"&cat_invent="+cat_invent+"&occ_rei="+occ_rei+"&cd_pdepreciacion="+cd_pdepreciacion+"&occ_ctaresultado="+occ_ctaresultado+"&cd_ajinflacion="+cd_ajinflacion+"&occ_valorneto="+occ_valorneto+"&cd_adiciones="+cd_adiciones+"&radioEstado="+radioEstado+"&detalles="+detalles);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			if (resp.trim()!="") alert(resp);
			else cargarPagina(formulario, "af_categoriadepreciacion.php?limit=0");
		}
	}
}
return false;
}
/// --------------------------------------------------------------------------------
/// --------------------------   FUNCION QUE PERMITE EDITAR CATEGORIA
function editarCategoria(formulario){
	
  var codcategoria = document.getElementById("codcategoria").value;
  var descp_local = document.getElementById("descp_local").value.trim();
  var v_historico = document.getElementById("v_historico").value.trim();
  var cg_depreciacion = document.getElementById("cg_depreciacion").value.trim();
  var t_depreciacion = document.getElementById("t_depreciacion").value;
  var cc_adiciones = document.getElementById("cc_adiciones").value;
  var cg_ajinflacion = document.getElementById("cg_ajinflacion").value;
  var g_categoria = document.getElementById("g_categoria").value;
  var cc_ajinflacion = document.getElementById("cc_ajinflacion").value;
  var cat_invent = document.getElementById("cat_invent").value;
  var occ_rei = document.getElementById("occ_rei").value;
  var cd_pdepreciacion = document.getElementById("cd_pdepreciacion").value;
  var occ_valorneto = document.getElementById("occ_valorneto").value;
  var cd_adiciones = document.getElementById("cd_adiciones").value;
  var occ_ctaresultado = document.getElementById("occ_ctaresultado").value;
  var cd_ajinflacion = document.getElementById("cd_ajinflacion").value;
  var radioEstado = document.getElementById("radioEstado").value; 
  
  
  //VALIDACION  DESCRIPCION LOCAL
  if (formulario.descp_local.value.length <2) {
	 alert("Escriba la descripción en el campo \"Descripción Local\".");
	 formulario.descp_local.focus();
  return (false);
  }
  var checkOK = "0123456789" + "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ" + " ._-/";
  var checkStr = formulario.descp_local.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Descripción Local\"."); 
	 formulario.descp_local.focus(); 
	 return (false); 
   } 
   
  // VALIDACION VALOR HISTORICO
  if (formulario.v_historico.value.length <2) {
	 alert("Escriba el valor histórico en el campo \"Valor Histórico\".");
	 formulario.v_historico.focus();
  return (false);
  }
  var checkOK = "0123456789" + "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ" + " ._-/";
  var checkStr = formulario.v_historico.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Valor Histórico\"."); 
	 formulario.v_historico.focus(); 
	 return (false); 
   }  
  
  // VALIDACION CUENTAS DE GASTOS -  DEPRECIACION
  if (formulario.cg_depreciacion.value.length <2) {
	 alert("Escriba el valor de la depreciación en el campo \"Cuentas de Gastos - Depreciación\".");
	 formulario.cg_depreciacion.focus();
  return (false);
  }
  var checkOK = "0123456789" + "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ" + " ._-/";
  var checkStr = formulario.cg_depreciacion.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Cuentas de Gastos - Depreciación\"."); 
	 formulario.cg_depreciacion.focus(); 
	 return (false); 
   }  
  
  // VALIDACION CUENTAS CONTABLES CONTABLES ACTIVO - ADICIONES
  if (formulario.cc_adiciones.value.length <2) {
	 alert("Escriba el valor de la adición  en el campo \"Adiciones\".");
	 formulario.cc_adiciones.focus();
  return (false);
  }
  var checkOK = "0123456789";
  var checkStr = formulario.cc_adiciones.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Adiciones\"."); 
	 formulario.cc_adiciones.focus(); 
	 return (false); 
   }  
  
  // VALIDACION CUENTAS CONTABLES CONTABLES ACTIVO - AJUSTES X INFLACION (CC_AJINFLACION)
  if (formulario.cc_ajinflacion.value.length <2) {
	 alert("Escriba el valor del ajuste por inflación en el campo \"Ajustes x Inflación\".");
	 formulario.cc_ajinflacion.focus();
  return (false);
  }
  var checkOK = "0123456789";
  var checkStr = formulario.cc_ajinflacion.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Ajustes x Inflación\"."); 
	 formulario.cc_ajinflacion.focus(); 
	 return (false); 
   } 
  
  // VALIDACION CUENTAS DE GASTOS - AJUSTE x INFLACION (CG_AJINFLACION)
  if (formulario.cg_ajinflacion.value.length <2) {
	 alert("Escriba el valor del ajuste por inflación en el campo \"Cuentas de Gastos - Ajustes x Inflación\".");
	 formulario.cg_ajinflacion.focus();
  return (false);
  }
  var checkOK = "0123456789";
  var checkStr = formulario.cg_ajinflacion.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Cuentas de Gastos - Ajustes x Inflación\"."); 
	 formulario.cg_ajinflacion.focus(); 
	 return (false); 
   } 
  
  // VALIDACION CUENTAS CONTABLES DEPRECIACION  - PARA DEPRECIACION (CD_PDEPRECIACION)
  if (formulario.cd_pdepreciacion.value.length <2) {
	 alert("Escriba el valor del ajuste por inflación en el campo \"Cuentas Contables Depreciación - Para Depreciación\".");
	 formulario.cd_pdepreciacion.focus();
  return (false);
  }
  var checkOK = "0123456789";
  var checkStr = formulario.cd_pdepreciacion.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Cuentas Contables Depreciación - Para Depreciación\"."); 
	 formulario.cd_pdepreciacion.focus(); 
	 return (false); 
   } 
  
  // VALIDACION CUENTAS CONTABLES DEPRECIACION - ADICIONES (CD_ADICIONES)
  if (formulario.cd_adiciones.value.length <1) {
	 alert("Escriba el valor en el campo \"Cuentas Contables Depreciación - Adiciones\".");
	 formulario.cd_adiciones.focus();
  return (false);
  }
  var checkOK = "0123456789";
  var checkStr = formulario.cd_adiciones.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Cuentas Contables Depreciación - Adiciones\"."); 
	 formulario.cd_adiciones.focus(); 
	 return (false); 
   }
   
  // VALIDACION CUENTAS CONTABLES DEPRECIACION - AJUSTES X INFLACION (CD_AJINFLACION)
  if (formulario.cd_ajinflacion.value.length <1) {
	 alert("Escriba el valor en el campo \"Cuentas Contables Depreciación - Ajustes x Inflación\".");
	 formulario.cd_ajinflacion.focus();
  return (false);
  }
  var checkOK = "0123456789";
  var checkStr = formulario.cd_ajinflacion.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Cuentas Contables Depreciación - Ajustes x Inflación\"."); 
	 formulario.cd_ajinflacion.focus(); 
	 return (false); 
   }
   
  // VALIDACION OTRAS CUENTAS CONTABLES - R.E.I (OCC_REI) 
  if (formulario.occ_rei.value.length <1) {
	 alert("Escriba el valor en el campo \"Otras Cuentas Contables - R.E.I\".");
	 formulario.occ_rei.focus();
  return (false);
  }
  var checkOK = "0123456789";
  var checkStr = formulario.occ_rei.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Otras Cuentas Contables - R.E.I\"."); 
	 formulario.occ_rei.focus(); 
	 return (false); 
   }	 
  
  // VALIDACION OTRAS CUENTAS CONTABLES - VALOR NETO (OCC_VALORNETO) 
  if (formulario.occ_valorneto.value.length <1) {
	 alert("Escriba el valor en el campo \"Otras Cuentas Contables - Valor Neto\".");
	 formulario.occ_valorneto.focus();
  return (false);
  }
  var checkOK = "0123456789";
  var checkStr = formulario.occ_valorneto.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Otras Cuentas Contables - Valor Neto\"."); 
	 formulario.occ_valorneto.focus(); 
	 return (false); 
   }	 
   
  // VALIDACION OTRAS CUENTAS CONTABLES - CTA. RESULTADO (OCC_CTARESULTADO) 
  if (formulario.occ_ctaresultado.value.length <1) {
	 alert("Escriba el valor en el campo \"Otras Cuentas Contables - Cta. Resultado\".");
	 formulario.occ_ctaresultado.focus();
  return (false);
  }
  var checkOK = "0123456789";
  var checkStr = formulario.occ_ctaresultado.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Otras Cuentas Contables - Cta. Resultado\"."); 
	 formulario.occ_ctaresultado.focus(); 
	 return (false); 
   }	  
     
	
  var detalles = "";
  var error_detalles = "";
	
  // obtengo los valoes de las lineas insertadas
  var frmdetalles = document.getElementById("frmdetalles");
  for(i=0; n=frmdetalles.elements[i]; i++) {
	if (n.name == "select1") {
		if (n.value == "") { error_detalles = "¡Debe seleccionar por lo menos un libro contable¡"; break; }
		else detalles += n.value + "|";
	}
	if (n.name == "descripcion") detalles += n.value + ";";
	
 }
 var len = detalles.length; len--;
 detalles = detalles.substr(0, len); //alert(detalles);

 if (error_detalles != "") alert(error_detalles);
 else {
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=editarCategoria&codcategoria="+codcategoria+"&descp_local="+descp_local+"&v_historico="+v_historico+"&cg_depreciacion="+cg_depreciacion+"&t_depreciacion="+t_depreciacion+"&cc_adiciones="+cc_adiciones+"&cg_ajinflacion="+cg_ajinflacion+"&g_categoria="+g_categoria+"&cc_ajinflacion="+cc_ajinflacion+"&cat_invent="+cat_invent+"&occ_rei="+occ_rei+"&cd_pdepreciacion="+cd_pdepreciacion+"&occ_ctaresultado="+occ_ctaresultado+"&cd_ajinflacion="+cd_ajinflacion+"&occ_valorneto="+occ_valorneto+"&cd_adiciones="+cd_adiciones+"&radioEstado="+radioEstado+"&detalles="+detalles);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			if (resp.trim() != "") alert(resp);
			else cargarPagina(formulario, "af_categoriadepreciacion.php?limit=0");
		}
	}
}
return false;
}
/// --------------------------------------------------------------------------------
/// -------------------		INSERTAR LINEA AF_CATEGORIANUEVA
function insertarLineaCatNueva() {
	var candetalle = document.getElementById("candetalle").value; candetalle++;
	
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=insertarLineaCatNueva&candetalle="+candetalle);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			document.getElementById("candetalle").value = candetalle;
			var resp = ajax.responseText;
			
			var newTr = document.createElement("tr");
			newTr.className = "trListaBody";
			newTr.setAttribute("onclick", "mClk(this, 'seldetalle');");
			newTr.id = "det_"+candetalle;
			document.getElementById("listaDetalles").appendChild(newTr);
			document.getElementById("det_"+candetalle).innerHTML = resp;
		}
	}
}
/// --------------------------------------------------------------------------------
/// FUNCION QUE PERMITE ASIGNAR EL VALOR DE 'S' O 'N' AL CAMPO CATEGORIA INVENTARIABLE
function asignarValorCatInvent(form){
  var valor = document.getElementById("cat_invent").value; //alert("valor"+valor);
  if(form.cat_invent.checked){ document.getElementById("cat_invent").value ="S"; //alert("cat_invent="+document.getElementById("cat_invent").value);
  }else{ document.getElementById("cat_invent").value="N"; //alert("cat_invent="+document.getElementById("cat_invent").value);
  }
}
/// --------------------------------------------------------------------------------
/// FUNCION QUE PERMITE ASIGNAR EL VALOR DE TIPO SEGURO A UN CAMPO
function valorTSeguro(select_TSeguro){
	var select_TSeguro = document.getElementById(select_TSeguro).value;
	document.getElementById("t_seguro").value = select_TSeguro;
}
/// --------------------------------------------------------------------------------
/// FUNCION QUE PERMITE ASIGNAR EL VALOR DE TIPO VEHIVULO
function valorTVehiculo(select_TVehiculo){
	var select_TVehiculo = document.getElementById(select_TVehiculo).value;
	document.getElementById("t_vehiculo").value = select_TVehiculo;
}
/// ---------------------------------------------------------------------------------
function enabledPersona(form){
 if(form.checkPersona.checked){ form.btpersona.disabled=false; 
 }else{ form.btpersona.disabled=true; form.persona.value=""; form.cod_persona.value="";}
}
/// ---------------------------------------------------------------------------------
function enabledCentroCostos(form){
  if(form.checkCentroCosto.checked) form.btCentroCosto.disabled=false;
  else{form.btCentroCosto.disabled=true; form.centro_costos.value=""; form.centro_costos2.value="";}
}
/// ---------------------------------------------------------------------------------
function enabledUbicacion(form){
  if(form.checkUbicacion.checked) form.btubicacion.disabled=false;
  else{form.btubicacion.disabled=true; form.cod_ubicacion.value=""; form.cod_ubicacion2.value=""; }
}
/// ---------------------------------------------------------------------------------
function enabledNaturaleza(form){
 if(form.checkNaturaleza.checked) form.fNaturaleza.disabled=false;
 else{form.fNaturaleza.disabled=true; form.fNaturaleza.value="";}
}
/// ---------------------------------------------------------------------------------
function enabledConsolidado(form){
 if(form.checkConsolidado.checked) form.act_consolidado.disabled=false;
 else{form.act_consolidado.disabled=true; form.act_consolidado.value="";}
}
/// --------------------------------------------------------------------------------------
//	FUNCION PARA SELECCIONAR UNA PERSONA DE UNA LISTA Y COLOCARLO EN OTRA VENTANA
function selActivo(busqueda, campo, variable, otro1, otro2, otro3, otro4, otro5, otro6, otro7, otro8, otro9, otro10, otro11, otro12, otro13, otro14, otro15,otro16, otro17) { //alert('paso');
	var registro=document.getElementById("registro").value; //alert(registro);
	if(campo==1){
		opener.document.getElementById("activo").value=registro;
		opener.document.getElementById("descripcion").value=variable;
		opener.document.getElementById("cod_bar").value=otro1; 
		opener.document.getElementById("c_costos").value=otro2; 
		opener.document.getElementById("c_costosActual").value=otro2; 
		opener.document.getElementById("c_costos2").value=otro3; 
		opener.document.getElementById("c_costosActual2").value=otro3;
		
		opener.document.getElementById("ubicacion").value=otro4; 
		opener.document.getElementById("ubicacion_Actual").value= otro4;
		opener.document.getElementById("ubicacion2").value=otro5;
		opener.document.getElementById("ubicacion_Actual2").value= otro5;
		
		opener.document.getElementById("dependencia").value=otro6; 
		opener.document.getElementById("dependenciaActual").value=otro6;
		opener.document.getElementById("dependencia2").value=otro7;
		opener.document.getElementById("dependenciaActual2").value=otro7;
		
		opener.document.getElementById("e_usuario").value=otro8; 
		opener.document.getElementById("e_usuario2").value = otro9;
		opener.document.getElementById("e_usuarioActual").value = otro8;
		opener.document.getElementById("e_usuarioActual2").value= otro9;
		
		opener.document.getElementById("e_responsable").value=otro10; 
		opener.document.getElementById("e_responsable2").value = otro11;
		opener.document.getElementById("e_responsableActual").value = otro10;
		opener.document.getElementById("e_responsableActual2").value= otro11;
		
		opener.document.getElementById("organismo").value= otro12;
		opener.document.getElementById("organismo2").value= otro13;
		opener.document.getElementById("organismoActual").value= otro12;
		opener.document.getElementById("organismoActual2").value= otro13;
		opener.document.getElementById("valorguardar").value = 1;
	}
	if(campo==2){
	    parent.document.getElementById("nro_activo").value=registro;
		parent.document.getElementById("descripcion").value=variable;
		parent.document.getElementById("codorganismo").value=otro5; 
		parent.document.getElementById("organismo").value=otro6;
		parent.document.getElementById("dpendencia").value=otro4;
		parent.document.getElementById("coddependencia").value=otro3;
		parent.document.getElementById("nrofactura").value=otro7;
		parent.document.getElementById("codcentrocosto").value=otro8;
		parent.document.getElementById("centrocosto").value=otro9;
		parent.document.getElementById("codresponsable").value=otro10;
		parent.document.getElementById("responsable").value=otro11;
		parent.document.getElementById("codubicacion").value=otro12;
		parent.document.getElementById("ubicacion").value=otro13;
		parent.document.getElementById("codcategoria").value=otro14;
		parent.document.getElementById("categoria").value=otro15;
		parent.document.getElementById("valor_activo").value=otro16;
		parent.document.getElementById("codigo_interno").value=otro17;
		
	}
	if(campo==3){
	   parent.document.frmentrada.fActivo.value = registro;
	   parent.document.frmentrada.fDescpActivo.value = variable;
	}
	 if((campo!='3')&&(campo!='2'))window.close();
	 else parent.$.prettyPhoto.close();
}
/// --------------------------------------------------------------------------------------
//	FUNCION PARA SELECCIONAR DATOS DE UNA LISTA Y COLOCARLO EN OTRA VENTANA
function selOperacion(busqueda, campo, variable, otro1, otro2, otro3, otro4, otro5, otro6, otro7) { //alert('paso');
	var registro=document.getElementById("registro").value; //alert(registro);
	if (campo==1){
		parent.document.frmentrada.proveedor.value=registro;
		parent.document.frmentrada.nomb_proveedor.value=variable; //alert(opener.document.frmentrada.clasificacion2.value);
	}else
	if(campo==2){
	   opener.document.frmentrada.descripcion.value = variable ;
	   opener.document.frmentrada.registro.value = registro; 
	   opener.document.getElementById("frmentrada").submit();
	}else
	if(campo==3){
	  var destino = document.getElementById("destino").value;	//alert(destino);
      if(destino == '1')opener.document.frmentrada.v_historico.value = registro;
      if(destino == '2')opener.document.frmentrada.cc_adiciones.value = registro;
	  if(destino == '3')opener.document.frmentrada.cc_ajinflacion.value = registro;
	  if(destino == '4')opener.document.frmentrada.cd_pdepreciacion.value = registro;
	  if(destino == '5')opener.document.frmentrada.cd_adiciones.value = registro;
	  if(destino == '6')opener.document.frmentrada.cd_ajinflacion.value = registro;
	  if(destino == '7')opener.document.frmentrada.cg_depreciacion.value = registro;
	  if(destino == '8')opener.document.frmentrada.cg_ajinflacion.value = registro;
	  if(destino == '9')opener.document.frmentrada.occ_rei.value = registro;
	  if(destino == '10')opener.document.frmentrada.occ_valorneto.value = registro;
	  if(destino == '11')opener.document.frmentrada.occ_ctaresultado.value = registro;
	}else
	if(campo==4){
	   parent.document.frmentrada.fproveedor.value = registro;
	   parent.document.frmentrada.proveedor.value = variable;
	}else
	if(campo==5){
	   ///alert(otro1);
	   opener.document.getElementById(otro1).value = registro;
	}else
	if(campo==6){
	   ///alert(otro1);
	   parent.document.frmentrada.fcuenta.value = registro; //alert(parent.document.frmentrada.fcuenta.value);
	   parent.document.frmentrada.fcuenta_descp.value = busqueda; //alert(parent.document.frmentrada.fcuenta_descp.value);
	}
	
	if((campo!='1')&&(campo!='4')&&(campo!='6'))window.close();
	 else parent.$.prettyPhoto.close();
}
/// --------------------------------------------------------------------------------------
/// 
function valorDestino(form, id){
   var id = document.getElementById(id).name ; //alert(id);
   if(id=='v_historico') document.getElementById("selector").value = 1;
   else if(id=='cc_adiciones') document.getElementById("selector").value = 2;
   else if(id=='cc_ajinflacion') document.getElementById("selector").value = 3;
   else if(id=='cd_pdepreciacion') document.getElementById("selector").value = 4;
   else if(id=='cd_adiciones') document.getElementById("selector").value = 5;
   else if(id=='cd_ajinflacion') document.getElementById("selector").value = 6;
   else if(id=='cg_depreciacion') document.getElementById("selector").value = 7;
   else if(id=='cg_ajinflacion') document.getElementById("selector").value = 8;
   else if(id=='occ_rei') document.getElementById("selector").value = 9;
   else if(id=='occ_valorneto') document.getElementById("selector").value = 10;
   else if(id=='occ_ctaresultado') document.getElementById("selector").value = 11;
   
}
/// --------------------------------------------------------------------------------------
/// 
function number_format(num){    
     //alert(num);
    var n = num.toString(); //alert(n);
    var nums = n.split('.');
    var newNum = "";
    if (nums.length > 1)
    {
        var dec = nums[1].substring(0,2);
        newNum = nums[0] + "," + dec;
    }
    else
    {
    newNum = num;
    }
   // alert(newNum)
}
/// --------------------------------------------------------------------------------------
/// CREAR PERIODO
function crearPeriodo(form, id){
  var fecha = document.getElementById("fecha_ingreso").value; //alert('Fecha='+fecha);
  var fechaInicio = fecha.split('-');
  var dia = fechaInicio[0];
  var mes = new Number(fechaInicio[1]);
  var ano = new Number(fechaInicio[2]);
  
  document.getElementById("periodo_registro").value = ano+"-"+mes; 
  if(mes=='12'){
	 mes = '01'; ano = ano + 1;
	 document.getElementById("ini_depreciacion").value = ano+"-"+mes; 
	 document.getElementById("ini_ajuste").value = ano+"-"+mes; 
  }else{ 
     mes = mes + 1;
	 document.getElementById("ini_depreciacion").value = ano+"-"+"0"+mes; 
	 document.getElementById("ini_ajuste").value = ano+"-"+"0"+mes; 
  } 
  
}
/// CREAR PERIODO
function crearPeriodo2(form, id){
  var fecha = document.getElementById("fecha_ingreso").value; //alert('Fecha='+fecha);
  var fechaInicio = fecha.split('-');
  var dia = fechaInicio[0];
  var mes = new Number(fechaInicio[1]);
  var ano = new Number(fechaInicio[2]);
  
  document.getElementById("periodo_registro").value = ano+"-"+mes; 
  
}
/// --------------------------------------------------------------------------------------
/// -------------------		GUARDAR ACTIVOS TRANSFERIDOS DESDE LOGISTICA
/// --------------------------------------------------------------------------------------
function guardarActivosTransferidos(form){ 
  
  var CodigoInterno = document.getElementById("codigo_interno").value;
	console.log('guardarActivosTransferidos->condigo interno:'+CodigoInterno);
  
  var CodOrganismo = document.getElementById("organismo").value;  
  var CodDependencia = document.getElementById("dependencia").value; 
  var Descripcion = document.getElementById("descripcion").value;
  var CodTipoMovimiento = document.getElementById("conceptoMovimiento").value;
  var TipoActivo = document.getElementById("tipo_activo").value;
  var EstadoConserv = document.getElementById("estado_conserv").value;
  var CodigoBarras = document.getElementById("codigo_barras").value;
  //var CodigoInterno = document.getElementById("codigo_interno").value;
  var TipoSeguro = document.getElementById("t_seguro").value;
  var TipoVehiculo = document.getElementById("t_vehiculo").value;
  var Categoria = document.getElementById("select_categoria").value;
  var Clasificacion = document.getElementById("clasificacion").value;
  var ClasificacionPublic20 = document.getElementById("clasificacion20").value;
  var Ubicacion = document.getElementById("ubicacion").value;
  var TipoMejora = document.getElementById("tipo_mejora").value;
  var ActivoConsolidado = document.getElementById("activo_principal").value;
  var EmpleadoUsuario = document.getElementById("cod_usuario").value;
  var EmpleadoResponsable = document.getElementById("cod_empresponsable").value;
  var CentroCosto = document.getElementById("centro_costos").value;
  var Marca = document.getElementById("fabricante").value;
  var Modelo = document.getElementById("modelo").value;
  var NumeroSerie = document.getElementById("nro_serie").value;
  var NumeroSerieMotor = document.getElementById("nro_seriemotor").value;
  var NumeroPlaca = document.getElementById("nro_placa").value;
  var MarcaMotor = document.getElementById("marca_motor").value;
  var NumeroAsiento = document.getElementById("nro_asientos").value;
  var Material = document.getElementById("material").value;
  var Dimensiones = document.getElementById("dimensiones").value;
  var NumerodeParte = document.getElementById("nro_parte").value;
  var Color = document.getElementById("color").value;
  var FabricacionPais = document.getElementById("pais_fabricacion").value; 
  var FabricacionAno = document.getElementById("ano_fabricacion").value;
  var PolizaSeguro = document.getElementById("poliza_seguro").value;
  var NumeroUnidades = document.getElementById("nro_unidades").value;
  var CodigoCatastro = document.getElementById("cod_catastro").value;
  var AreaFisicaCatastro = document.getElementById("area_terreno").value;
  var MontoCatastro = document.getElementById("area_terreno2").value;
  
  var CodProveedor = document.getElementById("proveedor").value;
  var FacturaTipoDocumento = document.getElementById("factura").value;
  var FacturaNumeroDocumento = document.getElementById("num_factura").value; 
  var FacturaFecha = document.getElementById("fecha_factura").value;
  var NumeroOrden = document.getElementById("orden_compra").value;
  var NumeroOrdenFecha = document.getElementById("fecha_ordencompra").value;
  var NumeroGuia = document.getElementById("nro_guiaremision").value;
  var NumeroGuiaFecha = document.getElementById("fecha_guiaremision").value;
  var NumeroDocAlmacen = document.getElementById("nro_documalmacen").value;
  var DocAlmacenFecha = document.getElementById("fecha_documalmacen").value;
  var InventarioFisicoFecha = document.getElementById("fecha_inventario").value;
  var InventarioFisicoComentario = document.getElementById("observacion").value;
  var FechaIngreso = document.getElementById("fecha_ingreso").value;
  var PeriodoIngreso = document.getElementById("periodo_registro").value;
  var PeriodoInicioDepreciacion = document.getElementById("ini_depreciacion").value;
  var PeriodoInicioRevaluacion = document.getElementById("ini_ajuste").value;
  var PeriodoBaja = document.getElementById("periodo_baja").value;
  var VoucherBaja = document.getElementById("voucher_baja").value;
  var MontoLocal = document.getElementById("monto_local").value;
  var MontoReferencia = document.getElementById("monto_referencial").value;
  var MontoMercado = document.getElementById("valor_mercado").value;
  var VoucherIngreso = document.getElementById("voucher_ingreso").value;
  //var Estado = document.getElementById("estado").value;  
  var SituacionActivo = document.getElementById("situacion_activo").value;
  var PreparadoPor = document.getElementById("cod_prepor").value; 
  var OrigenActivo = document.getElementById("origen_activo").name; //Posibles Valores AT= Aunomatico;  MA= Manual
  var UnidadMedida = document.getElementById("unidad_medida").value; //alert('UnidadMedida='+UnidadMedida);
   //alert('DepreEspecificaFlag='+DepreEspecificaFlag);
  var Naturaleza = document.getElementById("naturaleza").value;
  var CodTipoMovimiento= document.getElementById("conceptoMovimiento").value;
  var TipoTransaccion = document.getElementById("tipobaja").value; 
  var Descripcion = document.getElementById("descripcion").value;
  
  /// --------- FLAGS ------------
  if(form.gen_voucher.checked) var GenerarVoucherIngresoFlag='S'; else  var GenerarVoucherIngresoFlag='N';
  if(form.disp_mantenimientoflag.checked) var FlagParaMantenimiento='S'; else  var FlagParaMantenimiento='N';
  if(form.disp_operaciones.checked)  var FlagParaOperaciones='S'; else var FlagParaOperaciones='N';
  if(form.depre_especifica.checked) var DepreEspecificaFlag='S'; else var DepreEspecificaFlag='N';
  /// ----------------------------
  /// Datos de Informaación contable
  var Contabilidad = document.getElementById("contabilidad").value; 
  var Periodo = document.getElementById("Periodo").value;
  var LocalInicio = document.getElementById("h_inicioAnoLocal").value; LocalInicio = setNumero(LocalInicio); //alert('LocalInicio='+LocalInicio);
  var LocalMesFinal = LocalInicio;
  var LocalNeto = LocalInicio;
  var ALocalInicio = LocalInicio;
  var ALocalMesFinal = LocalInicio;
  var ALocalNeto = LocalInicio;
  // -------------------------------------
  var NroOrden = document.getElementById('num_orden').value;
  var Secuencia = document.getElementById('secuencia').value; 
  var NroSecuencia = document.getElementById('nrosecuencia').value; 
  // -------------------------------------
  var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=GuardarActivosTransferidos&CodOrganismo="+CodOrganismo+"&CodDependencia="+CodDependencia+"&Descripcion="+Descripcion+"&TipoActivo="+TipoActivo+"&EstadoConserv="+EstadoConserv+"&CodigoBarras="+CodigoBarras+"&TipoSeguro="+TipoSeguro+"&TipoVehiculo="+TipoVehiculo+"&Categoria="+Categoria+"&Clasificacion="+Clasificacion+"&ClasificacionPublic20="+ClasificacionPublic20+"&Ubicacion="+Ubicacion+"&TipoMejora="+TipoMejora+"&ActivoConsolidado="+ActivoConsolidado+"&EmpleadoUsuario="+EmpleadoUsuario+"&EmpleadoResponsable="+EmpleadoResponsable+"&CentroCosto="+CentroCosto+"&Marca="+Marca+"&Modelo="+Modelo+"&NumeroSerie="+NumeroSerie+"&NumeroSerieMotor="+NumeroSerieMotor+"&NumeroPlaca="+NumeroPlaca+"&MarcaMotor="+MarcaMotor+"&NumeroAsiento="+NumeroAsiento+"&Material="+Material+"&Dimensiones="+Dimensiones+"&NumerodeParte="+NumerodeParte+"&Color="+Color+"&FabricacionPais="+FabricacionPais+"&FabricacionAno="+FabricacionAno+"&PolizaSeguro="+PolizaSeguro+"&NumeroUnidades="+NumeroUnidades+"&CodigoCatastro="+CodigoCatastro+"&AreaFisicaCatastro="+AreaFisicaCatastro+"&MontoCatastro="+MontoCatastro+"&GenerarVoucherIngresoFlag="+GenerarVoucherIngresoFlag+"&CodProveedor="+CodProveedor+"&FacturaTipoDocumento="+FacturaTipoDocumento+"&FacturaNumeroDocumento="+FacturaNumeroDocumento+"&FacturaFecha="+FacturaFecha+"&NumeroOrden="+NumeroOrden+"&NumeroOrdenFecha="+NumeroOrdenFecha+"&NumeroGuia="+NumeroGuia+"&NumeroGuiaFecha="+NumeroGuiaFecha+"&NumeroDocAlmacen="+NumeroDocAlmacen+"&DocAlmacenFecha="+DocAlmacenFecha+"&InventarioFisicoFecha="+InventarioFisicoFecha+"&InventarioFisicoComentario="+InventarioFisicoComentario+"&FechaIngreso="+FechaIngreso+"&PeriodoIngreso="+PeriodoIngreso+"&PeriodoInicioDepreciacion="+PeriodoInicioDepreciacion+"&PeriodoInicioRevaluacion="+PeriodoInicioRevaluacion+"&PeriodoBaja="+PeriodoBaja+"&VoucherBaja="+VoucherBaja+"&MontoLocal="+MontoLocal+"&MontoReferencia="+MontoReferencia+"&MontoMercado="+MontoMercado+"&VoucherIngreso="+VoucherIngreso+"&FlagParaMantenimiento="+FlagParaMantenimiento+"&FlagParaOperaciones="+FlagParaOperaciones+"&SituacionActivo="+SituacionActivo+"&PreparadoPor="+PreparadoPor+"&OrigenActivo="+OrigenActivo+"&UnidadMedida="+UnidadMedida+"&DepreEspecificaFlag="+DepreEspecificaFlag+"&Naturaleza="+Naturaleza+"&Contabilidad="+Contabilidad+"&NroOrden="+NroOrden+"&Secuencia="+Secuencia+"&NroSecuencia="+NroSecuencia+"&CodTipoMovimiento="+CodTipoMovimiento+"&Periodo="+Periodo+"&LocalInicio="+LocalInicio+"&LocalNeto="+LocalNeto+"&ALocalInicio="+ALocalInicio+"&ALocalNeto="+ALocalNeto+"&LocalMesFinal="+LocalMesFinal+"&ALocalMesFinal="+ALocalMesFinal+"&TipoTransaccion="+TipoTransaccion+"&Descripcion="+Descripcion);
  
  ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText.trim();
			if (resp != "")alert(resp); 
            else opener.document.getElementById("frmentrada").submit();
		}
	}
  alert("ACTIVO TRANSFERIDO EXITOSAMENTE");
  window.close();
  return false;
 
}
//// -------------------------------------------------------------------------------------
////  -----------------		GUARDAR ACTIVOS LISTA ACTIVOS (ACTIVOS MAYORES)
//// -------------------------------------------------------------------------------------
function guardarActivosListaActivos(form){ 
	
	
  
  var CodOrganismo = document.getElementById("organismo").value;  
  var CodDependencia = document.getElementById("dependencia").value; 
  var Descripcion = document.getElementById("descripcion").value;
  var TipoActivo = document.getElementById("tipo_activo").value;
  var EstadoConserv = document.getElementById("estado_conserv").value;
  var CodigoBarras = document.getElementById("codigo_barras").value;
 
  var CodigoInterno = document.getElementById("codigo_interno").value;
 
  var TipoSeguro = document.getElementById("t_seguro").value;
  var TipoVehiculo = document.getElementById("t_vehiculo").value;
  var Categoria = document.getElementById("select_categoria").value;
  var Clasificacion = document.getElementById("clasificacion").value;
  var Ubicacion = document.getElementById("ubicacion").value;
  var TipoMejora = document.getElementById("tipo_mejora").value;
  var ActivoConsolidado = document.getElementById("activo_principal").value;
  var EmpleadoUsuario = document.getElementById("cod_usuario").value;
  var EmpleadoResponsable = document.getElementById("cod_empresponsable").value;
  var CentroCosto = document.getElementById("centro_costos").value;
  var Marca = document.getElementById("fabricante").value;
  var Modelo = document.getElementById("modelo").value;
  var NumeroSerie = document.getElementById("nro_serie").value;
  var NumeroSerieMotor = document.getElementById("nro_seriemotor").value;
  var NumeroPlaca = document.getElementById("nro_placa").value;
  var MarcaMotor = document.getElementById("marca_motor").value;
  var NumeroAsiento = document.getElementById("nro_asientos").value;
  var Material = document.getElementById("material").value;
  var Dimensiones = document.getElementById("dimensiones").value;
  var NumerodeParte = document.getElementById("nro_parte").value;
  var Color = document.getElementById("color").value;
  var FabricacionPais = document.getElementById("pais_fabricacion").value; 
  var FabricacionAno = document.getElementById("ano_fabricacion").value;
  var PolizaSeguro = document.getElementById("poliza_seguro").value;
  var NumeroUnidades = document.getElementById("nro_unidades").value;
  var CodigoCatastro = document.getElementById("cod_catastro").value;
  var AreaFisicaCatastro = document.getElementById("area_terreno").value;
  var MontoCatastro = document.getElementById("area_terreno2").value;
  var CodProveedor = document.getElementById("proveedor").value;
  var FacturaTipoDocumento = document.getElementById("factura").value;
  var FacturaNumeroDocumento = document.getElementById("num_factura").value; 
  var FacturaFecha = document.getElementById("fecha_factura").value;
  var NumeroOrden = document.getElementById("orden_compra").value;
  var NumeroOrdenFecha = document.getElementById("fecha_ordencompra").value;
  var NumeroGuia = document.getElementById("nro_guiaremision").value;
  var NumeroGuiaFecha = document.getElementById("fecha_guiaremision").value;
  var NumeroDocAlmacen = document.getElementById("nro_documalmacen").value;
  var DocAlmacenFecha = document.getElementById("fecha_documalmacen").value;
  var InventarioFisicoFecha = document.getElementById("fecha_inventario").value;
  var InventarioFisicoComentario = document.getElementById("observacion").value;
  var FechaIngreso = document.getElementById("fecha_ingreso").value;
  var PeriodoIngreso = document.getElementById("periodo_registro").value;
  var PeriodoInicioDepreciacion = document.getElementById("ini_depreciacion").value;
  var PeriodoInicioRevaluacion = document.getElementById("ini_ajuste").value;
  var PeriodoBaja = document.getElementById("periodo_baja").value;
  var VoucherBaja = document.getElementById("voucher_baja").value;
  var MontoLocal = document.getElementById("monto_local").value;
  var MontoReferencia = document.getElementById("monto_referencial").value;
  var MontoMercado = document.getElementById("valor_mercado").value;
  var VoucherIngreso = document.getElementById("voucher_ingreso").value;
  var Naturaleza = 'AN';//document.getElementById("naturaleza").value; 
  var SituacionActivo = document.getElementById("situacion_activo").value; 
  var PreparadoPor = document.getElementById("cod_prepor").value;
  var OrigenActivo = document.getElementById("origen_activo").name; 
  var UnidadMedida = document.getElementById("unidad_medida").value; 
  

	console.log('guardarActivosListaActivos->condigo interno:'+CodigoInterno);  	
    console.log('guardarActivosListaActivos->Publicacion 20 :'+Clasificacion); 	
    	
  var GenerarVoucherIngresoFlag = "";
  var FlagParaMantenimiento = "";  
  var FlagParaOperaciones = "";					
  var DepreEspecificaFlag = "";
  if(form.gen_voucher.checked) GenerarVoucherIngresoFlag = 'S'; 
  if(form.disp_mantenimiento.checked) FlagParaMantenimiento = 'S';
  if(form.disp_operaciones.checked) FlagParaOperaciones = 'S';
  if(form.depre_especifica.checked) DepreEspecificaFlag = 'S';
  
  /// -------------------
  /// Datos de Informaación contable
  var Contabilidad = document.getElementById("contabilidad").value;
  var Periodo = document.getElementById("Periodo").value;
  var LocalInicio = document.getElementById("h_inicioAnoLocal").value; LocalInicio = setNumero(LocalInicio); //alert('LocalInicio='+LocalInicio);
  var LocalMesFinal = LocalInicio;
  var LocalNeto = LocalInicio;
  var ALocalInicio = LocalInicio;
  var ALocalMesFinal = LocalInicio;
  var ALocalNeto = LocalInicio;
  
  var ClasificacionPublic20 = document.getElementById("clasificacion20").value;
  var CodTipoMovimiento = document.getElementById("conceptoMovimiento").value; 
  var TipoTransaccion = document.getElementById("tipobaja").value;
  
 var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=GuardarActivosTransferidos&CodOrganismo="+CodOrganismo+"&CodDependencia="+CodDependencia+"&Descripcion="+Descripcion+"&TipoActivo="+TipoActivo+"&EstadoConserv="+EstadoConserv+"&CodigoBarras="+CodigoBarras+"&TipoSeguro="+TipoSeguro+"&TipoVehiculo="+TipoVehiculo+"&Categoria="+Categoria+"&Clasificacion="+Clasificacion+"&ClasificacionPublic20="+ClasificacionPublic20+"&Ubicacion="+Ubicacion+"&TipoMejora="+TipoMejora+"&ActivoConsolidado="+ActivoConsolidado+"&EmpleadoUsuario="+EmpleadoUsuario+"&EmpleadoResponsable="+EmpleadoResponsable+"&CentroCosto="+CentroCosto+"&Marca="+Marca+"&Modelo="+Modelo+"&NumeroSerie="+NumeroSerie+"&NumeroSerieMotor="+NumeroSerieMotor+"&NumeroPlaca="+NumeroPlaca+"&MarcaMotor="+MarcaMotor+"&NumeroAsiento="+NumeroAsiento+"&Material="+Material+"&Dimensiones="+Dimensiones+"&NumerodeParte="+NumerodeParte+"&Color="+Color+"&FabricacionPais="+FabricacionPais+"&FabricacionAno="+FabricacionAno+"&PolizaSeguro="+PolizaSeguro+"&NumeroUnidades="+NumeroUnidades+"&CodigoCatastro="+CodigoCatastro+"&AreaFisicaCatastro="+AreaFisicaCatastro+"&MontoCatastro="+MontoCatastro+"&GenerarVoucherIngresoFlag="+GenerarVoucherIngresoFlag+"&CodProveedor="+CodProveedor+"&FacturaTipoDocumento="+FacturaTipoDocumento+"&FacturaNumeroDocumento="+FacturaNumeroDocumento+"&FacturaFecha="+FacturaFecha+"&NumeroOrden="+NumeroOrden+"&NumeroOrdenFecha="+NumeroOrdenFecha+"&NumeroGuia="+NumeroGuia+"&NumeroGuiaFecha="+NumeroGuiaFecha+"&NumeroDocAlmacen="+NumeroDocAlmacen+"&DocAlmacenFecha="+DocAlmacenFecha+"&InventarioFisicoFecha="+InventarioFisicoFecha+"&InventarioFisicoComentario="+InventarioFisicoComentario+"&FechaIngreso="+FechaIngreso+"&PeriodoIngreso="+PeriodoIngreso+"&PeriodoInicioDepreciacion="+PeriodoInicioDepreciacion+"&PeriodoInicioRevaluacion="+PeriodoInicioRevaluacion+"&PeriodoBaja="+PeriodoBaja+"&VoucherBaja="+VoucherBaja+"&MontoLocal="+MontoLocal+"&MontoReferencia="+MontoReferencia+"&MontoMercado="+MontoMercado+"&VoucherIngreso="+VoucherIngreso+"&FlagParaMantenimiento="+FlagParaMantenimiento+"&FlagParaOperaciones="+FlagParaOperaciones+"&SituacionActivo="+SituacionActivo+"&PreparadoPor="+PreparadoPor+"&OrigenActivo="+OrigenActivo+"&UnidadMedida="+UnidadMedida+"&DepreEspecificaFlag="+DepreEspecificaFlag+"&CodTipoMovimiento="+CodTipoMovimiento+"&LocalInicio="+LocalInicio+"&Naturaleza="+Naturaleza+'&TipoTransaccion='+TipoTransaccion+"&Contabilidad="+Contabilidad+"&Periodo="+Periodo+"&LocalMesFinal="+LocalMesFinal+"&LocalNeto="+LocalNeto+"&ALocalInicio="+ALocalInicio+"&ALocalMesFinal="+ALocalMesFinal+"&ALocalNeto="+ALocalNeto+"&CodigoInterno="+CodigoInterno);
  
  ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText.trim();
			if (resp != "")alert(resp); 
            else cargarPagina(form, "af_listactivos.php?limit=0");
		}
	}
	return false;
}
//// -------------------------------------------------------------------------------------
//// ------------------		GUARDAR MODIFICACIONES EN LISTA DE ACTIVOS (ACTIVOS MAYORES)
//// -------------------------------------------------------------------------------------
function guardarActivosModificados(form){
  
  var Activo = document.getElementById("nro_activo").value;
  var CodOrganismo = document.getElementById("organismo").value;  
  var CodDependencia = document.getElementById("dependencia").value; 
  var Descripcion = document.getElementById("descripcion").value;
  var TipoActivo = document.getElementById("tipo_activo").value;
  var EstadoConserv = document.getElementById("estado_conserv").value;
  var CodigoBarras = document.getElementById("codigo_barras").value;
  var CodigoInterno = document.getElementById("codigo_interno").value;
  var TipoSeguro = document.getElementById("t_seguro").value;
  var TipoVehiculo = document.getElementById("t_vehiculo").value;
  var Categoria = document.getElementById("select_categoria").value;
  var Clasificacion = document.getElementById("clasificacion").value;
  var ClasificacionPublic20 = document.getElementById("clasificacion20").value;
  var Ubicacion = document.getElementById("ubicacion").value;
  var TipoMejora = document.getElementById("tipo_mejora").value;
  var ActivoConsolidado = document.getElementById("activo_principal").value; 
  var EmpleadoUsuario = document.getElementById("cod_usuario").value;
  var EmpleadoResponsable = document.getElementById("cod_empresponsable").value;
  var CentroCosto = document.getElementById("centro_costos").value;
  var Marca = document.getElementById("fabricante").value;
  var Modelo = document.getElementById("modelo").value;
  var NumeroSerie = document.getElementById("nro_serie").value;
  var NumeroSerieMotor = document.getElementById("nro_seriemotor").value;
  var NumeroPlaca = document.getElementById("nro_placa").value;
  var MarcaMotor = document.getElementById("marca_motor").value;
  var NumeroAsiento = document.getElementById("nro_asientos").value;
  var Material = document.getElementById("material").value;
  var Dimensiones = document.getElementById("dimensiones").value;
  var NumerodeParte = document.getElementById("nro_parte").value;
  var Color = document.getElementById("color").value;
  var FabricacionPais = document.getElementById("pais_fabricacion").value; 
  var FabricacionAno = document.getElementById("ano_fabricacion").value;
  var PolizaSeguro = document.getElementById("poliza_seguro").value;
  var NumeroUnidades = document.getElementById("nro_unidades").value;
  var CodigoCatastro = document.getElementById("cod_catastro").value;
  var AreaFisicaCatastro = document.getElementById("area_terreno").value;
  var MontoCatastro = document.getElementById("area_terreno2").value;
  var CodProveedor = document.getElementById("proveedor").value;
  var FacturaTipoDocumento = document.getElementById("factura").value;
  var FacturaNumeroDocumento = document.getElementById("num_factura").value; 
  var FacturaFecha = document.getElementById("fecha_factura").value;
  var NumeroOrden = document.getElementById("orden_compra").value;
  var NumeroOrdenFecha = document.getElementById("fecha_ordencompra").value;
  var NumeroGuia = document.getElementById("nro_guiaremision").value;
  var NumeroGuiaFecha = document.getElementById("fecha_guiaremision").value;
  var NumeroDocAlmacen = document.getElementById("nro_documalmacen").value;
  var DocAlmacenFecha = document.getElementById("fecha_documalmacen").value;
  var InventarioFisicoFecha = document.getElementById("fecha_inventario").value;
  var InventarioFisicoComentario = document.getElementById("observacion").value;
  var FechaIngreso = document.getElementById("fecha_ingreso").value;
  var PeriodoIngreso = document.getElementById("periodo_registro").value;
  var PeriodoInicioDepreciacion = document.getElementById("ini_depreciacion").value;
  var PeriodoInicioRevaluacion = document.getElementById("ini_ajuste").value;
  var PeriodoBaja = document.getElementById("periodo_baja").value;
  var VoucherBaja = document.getElementById("voucher_baja").value;
  var MontoLocal = document.getElementById("monto_local").value;
  var MontoReferencia = document.getElementById("monto_referencial").value;
  var MontoMercado = document.getElementById("valor_mercado").value;
  var VoucherIngreso = document.getElementById("voucher_ingreso").value;
  var Estado = document.getElementById("estado").value;
  var SituacionActivo = document.getElementById("situacion_activo").value; 
  var PreparadoPor = document.getElementById("cod_prepor").value;
  var OrigenActivo = document.getElementById("origen_activo").name;
  var Naturaleza = document.getElementById("naturaleza").value;
  var UnidadMedida = document.getElementById("unidad_medida").value; 
  
  var TipoBaja = document.getElementById("tipobaja").value; 
    	
  var GenerarVoucherIngresoFlag = "";
  var FlagParaMantenimiento = "";  
  var FlagParaOperaciones = "";					
  var DepreEspecificaFlag = "";
  if(form.gen_voucher.checked) GenerarVoucherIngresoFlag = 'S'; 
  if(form.disp_mantenimientoflag.checked) FlagParaMantenimiento = 'S';
  if(form.disp_operacionesflag.checked) FlagParaOperaciones = 'S';
  if(form.depre_especifica.checked) DepreEspecificaFlag = 'S';
  
  /// -------------------
  /// Datos de Informaación contable
  var Contabilidad = document.getElementById("contabilidad").value;
  var Periodo = document.getElementById("Periodo").value;
  var LocalInicio = document.getElementById("h_inicioAnoLocal").value; LocalInicio = setNumero(LocalInicio); alert(LocalInicio);
  var LocalMesFinal = LocalInicio;
  var LocalNeto = LocalInicio;
  var ALocalInicio = LocalInicio;
  var ALocalMesFinal = LocalInicio;
  var ALocalNeto = LocalInicio;
  
  var ClasificacionPublic20 = document.getElementById("clasificacion20").value;
  var CodTipoMovimiento = document.getElementById("conceptoMovimiento").value;
  var TipoTransaccion = document.getElementById("tipobaja").value; 
							
 
  var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=GuardarModificacionesListaActivos&CodOrganismo="+CodOrganismo+"&CodDependencia="+CodDependencia+"&Descripcion="+Descripcion+"&TipoActivo="+TipoActivo+"&EstadoConserv="+EstadoConserv+"&CodigoBarras="+CodigoBarras+"&CodigoInterno="+CodigoInterno+"&TipoSeguro="+TipoSeguro+"&TipoVehiculo="+TipoVehiculo+"&Categoria="+Categoria+"&Clasificacion="+Clasificacion+"&ClasificacionPublic20="+ClasificacionPublic20+"&Ubicacion="+Ubicacion+"&TipoMejora="+TipoMejora+"&ActivoConsolidado="+ActivoConsolidado+"&EmpleadoUsuario="+EmpleadoUsuario+"&EmpleadoResponsable="+EmpleadoResponsable+"&CentroCosto="+CentroCosto+"&Marca="+Marca+"&Modelo="+Modelo+"&NumeroSerie="+NumeroSerie+"&NumeroSerieMotor="+NumeroSerieMotor+"&NumeroPlaca="+NumeroPlaca+"&MarcaMotor="+MarcaMotor+"&NumeroAsiento="+NumeroAsiento+"&Material="+Material+"&Dimensiones="+Dimensiones+"&NumerodeParte="+NumerodeParte+"&Color="+Color+"&FabricacionPais="+FabricacionPais+"&FabricacionAno="+FabricacionAno+"&PolizaSeguro="+PolizaSeguro+"&NumeroUnidades="+NumeroUnidades+"&CodigoCatastro="+CodigoCatastro+"&AreaFisicaCatastro="+AreaFisicaCatastro+"&MontoCatastro="+MontoCatastro+"&GenerarVoucherIngresoFlag="+GenerarVoucherIngresoFlag+"&CodProveedor="+CodProveedor+"&FacturaTipoDocumento="+FacturaTipoDocumento+"&FacturaNumeroDocumento="+FacturaNumeroDocumento+"&FacturaFecha="+FacturaFecha+"&NumeroOrden="+NumeroOrden+"&NumeroOrdenFecha="+NumeroOrdenFecha+"&NumeroGuia="+NumeroGuia+"&NumeroGuiaFecha="+NumeroGuiaFecha+"&NumeroDocAlmacen="+NumeroDocAlmacen+"&DocAlmacenFecha="+DocAlmacenFecha+"&InventarioFisicoFecha="+InventarioFisicoFecha+"&InventarioFisicoComentario="+InventarioFisicoComentario+"&FechaIngreso="+FechaIngreso+"&PeriodoIngreso="+PeriodoIngreso+"&PeriodoInicioDepreciacion="+PeriodoInicioDepreciacion+"&PeriodoInicioRevaluacion="+PeriodoInicioRevaluacion+"&PeriodoBaja="+PeriodoBaja+"&VoucherBaja="+VoucherBaja+"&MontoLocal="+MontoLocal+"&MontoReferencia="+MontoReferencia+"&MontoMercado="+MontoMercado+"&VoucherIngreso="+VoucherIngreso+"&FlagParaMantenimiento="+FlagParaMantenimiento+"&FlagParaOperaciones="+FlagParaOperaciones+"&SituacionActivo="+SituacionActivo+"&PreparadoPor="+PreparadoPor+"&Activo="+Activo+"&Estado="+Estado+"&DepreEspecificaFlag="+DepreEspecificaFlag+"&CodTipoMovimiento="+CodTipoMovimiento+"&UnidadMedida="+UnidadMedida+"&DepreEspecificaFlag="+DepreEspecificaFlag+"&CodTipoMovimiento="+CodTipoMovimiento+"&LocalInicio="+LocalInicio+"&Naturaleza="+Naturaleza+'&TipoTransaccion='+TipoTransaccion+"&Contabilidad="+Contabilidad+"&Periodo="+Periodo+"&LocalMesFinal="+LocalMesFinal+"&LocalNeto="+LocalNeto+"&ALocalInicio="+ALocalInicio+"&ALocalMesFinal="+ALocalMesFinal+"&ALocalNeto="+ALocalNeto);
  
  ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText.trim();
			//if (resp != "")alert(resp); 
            //else 
           		cargarPagina(form, "af_listactivos.php?limit=0");
		}
	}
  //alert("ACTIVO TRANSFERIDO EXITOSAMENTE");
  return false;
 //window.close();
}
//// ------------------------------------------------------------------------------------- ///
//// -----------------		GUARDAR ACTIVOS MENORES
//// ------------------------------------------------------------------------------------- ///
function guardarActivosMenores(form){ 
  var CodOrganismo = document.getElementById("organismo").value;  
  var CodDependencia = document.getElementById("dependencia").value; 
  var Descripcion = document.getElementById("descripcionLocal").value;
  var DescpCorta = document.getElementById("descripcionCorta").value;
  var Ubicacion = document.getElementById("ubicacion").value;
  //var CodigoInterno = document.getElementById("codigo_interno").value;
  var Clasificacion = document.getElementById("clasificacion").value;
  var SituacionActivo = document.getElementById("situacion_activo").value; 
  var Categoria = document.getElementById("select_categoria").value;
  var ActivoConsolidado = document.getElementById("activo_consolidado").value; 
  var CentroCosto = document.getElementById("centro_costos").value;
  var EmpleadoResponsable = document.getElementById("cod_empresponsable").value;
  var EmpleadoUsuario = document.getElementById("cod_usuario").value;
  var Naturaleza = document.getElementById("naturaleza").name; 
  var Marca = document.getElementById("fabricante").value;
  var Modelo = document.getElementById("modelo").value; 
  var NumeroSerie = document.getElementById("nro_serie").value;
  var Color = document.getElementById("color").value;
  var CodigoBarras = document.getElementById("codigo_barras").value;
  var Dimensiones = document.getElementById("medida").value;
  var FabricacionPais = document.getElementById("pais_fabricacion").value;
  var FabricacionAno = document.getElementById("ano_fabricacion").value; 
  var FechaIngreso = document.getElementById("fecha_ingreso").value;
  var PeriodoIngreso = document.getElementById("periodo_registro").value; 
  var CodProveedor = document.getElementById("proveedor").value;
  var FacturaFecha = document.getElementById("fecha_factura").value;
  var FacturaTipoDocumento = document.getElementById("factura").value;
  var FacturaNumeroDocumento = document.getElementById("num_factura").value;
  var NumeroOrden = document.getElementById("orden_compra").value;
  var NumeroOrdenFecha = document.getElementById("fecha_ordencompra").value;
  var NumeroGuia = document.getElementById("nro_guiaremision").value;
  var NumeroGuiaFecha = document.getElementById("fecha_guiaremision").value;
  var MontoLocal = document.getElementById("monto_local").value; 
  var Estado = document.getElementById("radio").value;
  var PreparadoPor = document.getElementById("cod_prepor").value;
  var CodTipoMovimiento = document.getElementById("conceptoMovimiento").value;
  var ClasificacionPublic20 = document.getElementById("clasificacion20").value;  
  if(form.disp_operaciones.checked) var FlagParaOperaciones = 'S'; else var FlagParaOperaciones = 'N';
  

 var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=GuardarActivosMenores&CodOrganismo="+CodOrganismo+"&CodDependencia="+CodDependencia+"&Descripcion="+Descripcion+"&CodigoBarras="+CodigoBarras+"&Clasificacion="+Clasificacion+"&Ubicacion="+Ubicacion+"&ActivoConsolidado="+ActivoConsolidado+"&EmpleadoResponsable="+EmpleadoResponsable+"&CentroCosto="+CentroCosto+"&Marca="+Marca+"&Modelo="+Modelo+"&NumeroSerie="+NumeroSerie+"&Dimensiones="+Dimensiones+"&Color="+Color+"&FabricacionPais="+FabricacionPais+"&FabricacionAno="+FabricacionAno+"&CodProveedor="+CodProveedor+"&FacturaTipoDocumento="+FacturaTipoDocumento+"&FacturaNumeroDocumento="+FacturaNumeroDocumento+"&FacturaFecha="+FacturaFecha+"&NumeroOrden="+NumeroOrden+"&NumeroOrdenFecha="+NumeroOrdenFecha+"&NumeroGuia="+NumeroGuia+"&NumeroGuiaFecha="+NumeroGuiaFecha+"&FechaIngreso="+FechaIngreso+"&PeriodoIngreso="+PeriodoIngreso+"&MontoLocal="+MontoLocal+"&FlagParaOperaciones="+FlagParaOperaciones+"&SituacionActivo="+SituacionActivo+"&PreparadoPor="+PreparadoPor+"&Naturaleza="+Naturaleza+"&DescpCorta="+DescpCorta+"&Estado="+Estado+"&CodTipoMovimiento="+CodTipoMovimiento+"&ClasificacionPublic20="+ClasificacionPublic20+"&Categoria="+Categoria+"&EmpleadoUsuario="+EmpleadoUsuario);
  
  ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText.trim();
			if (resp != "")alert(resp); 
            else cargarPagina(form, "af_activosmenores.php?limit=0");
		}
	}
	return false;
}
//// ------------------------------------------------------------------------------------- ///
//// -----------------		MODIFICAR ACTIVOS MENORES
//// ------------------------------------------------------------------------------------- ///
function modificarActivosMenores(form){ 
  var FlagParaOperaciones ="";
  var CodOrganismo = document.getElementById("organismo").value; 
  var Activo = document.getElementById("nro_activo").value;  
  var CodDependencia = document.getElementById("dependencia").value; 
  var Descripcion = document.getElementById("descripcionLocal").value;
  var DescpCorta = document.getElementById("descripcionCorta").value;
  var Ubicacion = document.getElementById("ubicacion").value;
  var CodigoInterno = document.getElementById("codigo_interno").value;
  var Clasificacion = document.getElementById("clasificacion").value;
  var SituacionActivo = document.getElementById("situacion_activo").value; 
  var ActivoConsolidado = document.getElementById("activo_consolidado").value;
  var CentroCosto = document.getElementById("centro_costos").value;
  var EmpleadoResponsable = document.getElementById("cod_empresponsable").value;
  var EmpleadoUsuario = document.getElementById("cod_usuario").value;
  var Naturaleza = document.getElementById("naturaleza").value;
  var Marca = document.getElementById("fabricante").value;
  var Modelo = document.getElementById("modelo").value; 
  var NumeroSerie = document.getElementById("nro_serie").value;
  var Color = document.getElementById("color").value;
  var CodigoBarras = document.getElementById("cod_barras").value;
  var Dimensiones = document.getElementById("medida").value;
  var FabricacionPais = document.getElementById("pais_fabricacion").value;
  var FabricacionAno = document.getElementById("ano_fabricacion").value;
  var FechaIngreso = document.getElementById("fecha_ingreso").value;
  var PeriodoIngreso = document.getElementById("periodo_registro").value; 
  var CodProveedor = document.getElementById("proveedor").value;
  var FacturaFecha = document.getElementById("fecha_factura").value;
  var FacturaTipoDocumento = document.getElementById("factura").value;
  var FacturaNumeroDocumento = document.getElementById("num_factura").value;
  var NumeroOrden = document.getElementById("orden_compra").value;
  var NumeroOrdenFecha = document.getElementById("fecha_ordencompra").value;
  var NumeroGuia = document.getElementById("nro_guiaremision").value;
  var NumeroGuiaFecha = document.getElementById("fecha_guiaremision").value;
  var MontoLocal = document.getElementById("monto_local").value; 
  var ClasificacionPublic20 = document.getElementById("clasificacion20").value;
  var Estado = document.getElementById("radio").value;
  if(form.disp_operaciones.checked) FlagParaOperaciones = 'S'; else FlagParaOperaciones = 'N'; 
  
  

 var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=GuardarModificacionesActivosMenores&CodOrganismo="+CodOrganismo+"&CodDependencia="+CodDependencia+"&Descripcion="+Descripcion+"&CodigoBarras="+CodigoBarras+"&CodigoInterno="+CodigoInterno+"&Clasificacion="+Clasificacion+"&ClasificacionPublic20="+ClasificacionPublic20+"&Ubicacion="+Ubicacion+"&ActivoConsolidado="+ActivoConsolidado+"&EmpleadoResponsable="+EmpleadoResponsable+"&CentroCosto="+CentroCosto+"&Marca="+Marca+"&Modelo="+Modelo+"&NumeroSerie="+NumeroSerie+"&Dimensiones="+Dimensiones+"&Color="+Color+"&FabricacionPais="+FabricacionPais+"&FabricacionAno="+FabricacionAno+"&CodProveedor="+CodProveedor+"&FacturaTipoDocumento="+FacturaTipoDocumento+"&FacturaNumeroDocumento="+FacturaNumeroDocumento+"&FacturaFecha="+FacturaFecha+"&NumeroOrden="+NumeroOrden+"&NumeroOrdenFecha="+NumeroOrdenFecha+"&NumeroGuia="+NumeroGuia+"&NumeroGuiaFecha="+NumeroGuiaFecha+"&FechaIngreso="+FechaIngreso+"&PeriodoIngreso="+PeriodoIngreso+"&MontoLocal="+MontoLocal+"&FlagParaOperaciones="+FlagParaOperaciones+"&SituacionActivo="+SituacionActivo+"&Naturaleza="+Naturaleza+"&DescpCorta="+DescpCorta+"&Estado="+Estado+"&Activo="+Activo+"&EmpleadoUsuario="+EmpleadoUsuario);
  
  ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText.trim();
			if (resp != "")alert(resp); 
            else cargarPagina(form, "af_activosmenores.php?limit=0");
		}
	}
	return false;
}
//// -------------------------------------------------------------------------------------
//// -------------------		PARA CARGAR EL MONTO CATASTRO
//// -------------------------------------------------------------------------------------
function valorTerreno(form, id){
 var CodCatastro = document.getElementById(id).value; //alert(CodCatastro);
 
 if (CodCatastro!=""){
 var ajax=nuevoAjax();
	ajax.open("POST", "af_fphp_ajax.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=cargarMontoCatastro&CodCatastro="+CodCatastro);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText.trim();
			if (resp != "")document.getElementById("area_terreno2").value = resp; //alert('valor = '+document.getElementById("area_terreno2").value);
		}
	}
 }else document.getElementById("area_terreno2").value = "";
}
//// -------------------------------------------------------------------------------------
/// CARGAR VALOR FLAG GENERAR VOUCHER
function valorFlagVoucher(form){
  if(form.btgen_voucher.checked) document.getElementById("gen_voucher").value = 'S';
  else document.getElementById("gen_voucher").value = '';
}
//// -------------------------------------------------------------------------------------
///
function asignarValorDispMantenimiento(form){
  if(form.disp_mantenimiento.checked) document.getElementById("disp_mantenimiento").value= 'S';
  
  if(form.disp_operaciones.checked) document.getElementById("disp_operaciones").value= 'S';
  
  if(form.depre_especifica.checked) document.getElementById("depre_especifica").value = 'S';
}
//// -------------------------------------------------------------------------------------
//	 -------------------		FUNCION PARA CARGAR UNA NUEVA PAGINA 
function cargarOpcionListActEditar(form, pagina, target, param) { 
	var codigo=form.registro.value;
	if (codigo=="") msjError(1000);
	else {
		if (target=="SELF") cargarPaginaAF(form, pagina);
		else { pagina=pagina+"?limit=0&accion=VER&registro="+codigo; alert('pagina='+pagina); cargarVentana(form, pagina, param); }
	}
}

//// -------------------------------------------------------------------------------------
///  -----------------      GUARDAR NUEVO MOVIMIENTO DE ACTIVO
//// -------------------------------------------------------------------------------------
function guardarNuevoMovimientoActivo(form){
 if(document.getElementById("valorguardar").value==1){ 	
    //var  numeroActivo = "";		
   if(document.getElementById("motivoTrasladoInterno").value!=""){ 
      var MotivoTraslado = document.getElementById("motivoTrasladoInterno").value; //alert("movinterno="+MotivoTraslado);
   }else{ 
      var MotivoTraslado = document.getElementById("motivoTrasladoExterno").value; //alert("movexterno="+MotivoTraslado);
   }
   
   
   var Activo = document.getElementById("activo").value; 
   var Organismo = document.getElementById("CodOrganismo").value;
   var OrganismoAnterior = document.getElementById("organismo").value;
   var OrganismoActual = document.getElementById("organismoActual").value;
   var CentroCosto = document.getElementById("c_costosActual").value;
   var CentroCostoAnterior = document.getElementById("c_costos").value;
   var Ubicacion = document.getElementById("ubicacion_Actual").value;
   var UbicacionAnterior = document.getElementById("ubicacion").value;
   var Dependencia = document.getElementById("dependenciaActual").value;
   var DependenciaAnterior = document.getElementById("dependencia").value;
   var EmpleadoResponsable = document.getElementById("e_responsableActual").value;
   var EmpleadoResponsableAnterior = document.getElementById("e_responsable").value; 
   var EmpleadoUsuario = document.getElementById("e_usuarioActual").value;
   var EmpleadoUsuarioAnterior = document.getElementById("e_usuario").value;
   
   var PreparadoPor = document.getElementById("preparado_por").value;
   var InternoExternoFlag = document.getElementById("radioEstado").value;
   var Comentario = document.getElementById("comentario").value;
   //var Estado = document.getElementById("Estado").value; alert("Estado="+Estado);
   
   //alert("accion=guardarNuevoMovimientoActivo&Activo="+Activo+"&Organismo="+Organismo+"&OrganismoAnterior="+OrganismoAnterior+"&OrganismoActual="+OrganismoActual+"&CentroCosto="+CentroCosto+"&CentroCostoAnterior="+CentroCostoAnterior+"&Ubicacion="+Ubicacion+"&UbicacionAnterior="+UbicacionAnterior+"&Dependencia="+Dependencia+"&DependenciaAnterior="+DependenciaAnterior+"&EmpleadoResponsable="+EmpleadoResponsable+"&EmpleadoResponsableAnterior="+EmpleadoResponsableAnterior+"&EmpleadoUsuario="+EmpleadoUsuario+"&EmpleadoUsuarioAnterior="+EmpleadoUsuarioAnterior+"&PreparadoPor="+PreparadoPor+"&InternoExternoFlag="+InternoExternoFlag+"&MotivoTraslado="+MotivoTraslado+"&Comentario="+Comentario);
   var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=guardarNuevoMovimientoActivo&Activo="+Activo+"&Organismo="+Organismo+"&OrganismoAnterior="+OrganismoAnterior+"&OrganismoActual="+OrganismoActual+"&CentroCosto="+CentroCosto+"&CentroCostoAnterior="+CentroCostoAnterior+"&Ubicacion="+Ubicacion+"&UbicacionAnterior="+UbicacionAnterior+"&Dependencia="+Dependencia+"&DependenciaAnterior="+DependenciaAnterior+"&EmpleadoResponsable="+EmpleadoResponsable+"&EmpleadoResponsableAnterior="+EmpleadoResponsableAnterior+"&EmpleadoUsuario="+EmpleadoUsuario+"&EmpleadoUsuarioAnterior="+EmpleadoUsuarioAnterior+"&PreparadoPor="+PreparadoPor+"&InternoExternoFlag="+InternoExternoFlag+"&MotivoTraslado="+MotivoTraslado+"&Comentario="+Comentario);

       ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText.trim();
			if (resp != ""){//alert(resp); 
			                document.getElementById("numeroMovimientoGenerado").value = resp; 
							//alert('N&uacute;mero Movimiento= '+document.getElementById("numeroMovimientoGenerado").value);
						   } 
			cargarPagina(form,"af_movimientoactivos.php?limit=0");
		}
	  }
     alert("SE HA GENERADO EL MOVIMIENTO");
	 //return false;
 }else{
     alert("DEBE SELECCIONAR EL ACTIVO");
 } 
}
//// -------------------------------------------------------------------------------------
///  -----------------		GUARDAR EDITAR MOVIMIENTO DE ACTIVO
//// -------------------------------------------------------------------------------------
function guardarEditarMovimientoActivo(form){		
   
   if(document.getElementById("motivoTrasladoInterno").value!=""){ 
      var MotivoTraslado = document.getElementById("motivoTrasladoInterno").value;
   }else{ 
      var MotivoTraslado = document.getElementById("motivoTrasladoExterno").value;
	  }
   var MovimientoNumero = document.getElementById("fmovimiento").value;
   var Activo = document.getElementById("activo").value; 
   var Organismo = document.getElementById("fOrganismo").value;
   var OrganismoAnterior = document.getElementById("organismo").value;
   var OrganismoActual = document.getElementById("organismoActual").value;
   var CentroCosto = document.getElementById("c_costosActual").value;
   var CentroCostoAnterior = document.getElementById("c_costos").value;
   var Ubicacion = document.getElementById("ubicacion_Actual").value;
   var UbicacionAnterior = document.getElementById("ubicacion").value;
   var Dependencia = document.getElementById("dependenciaActual").value;
   var DependenciaAnterior = document.getElementById("dependencia").value;
   var EmpleadoResponsable = document.getElementById("e_responsableActual").value;
   var EmpleadoResponsableAnterior = document.getElementById("e_responsable").value; 
   var EmpleadoUsuario = document.getElementById("e_usuarioActual").value;
   var EmpleadoUsuarioAnterior = document.getElementById("e_usuario").value;
   
   var PreparadoPor = document.getElementById("preparado_por").value;
   var InternoExternoFlag = document.getElementById("radioEstado").value;
   var Comentario = document.getElementById("comentario").value;
   
   alert("accion=guardarEditarMovimientoActivo&Activo="+Activo+"&Organismo="+Organismo+"&OrganismoAnterior="+OrganismoAnterior+"&OrganismoActual="+OrganismoActual+"&CentroCosto="+CentroCosto+"&CentroCostoAnterior="+CentroCostoAnterior+"&Ubicacion="+Ubicacion+"&UbicacionAnterior="+UbicacionAnterior+"&Dependencia="+Dependencia+"&DependenciaAnterior="+DependenciaAnterior+"&EmpleadoResponsable="+EmpleadoResponsable+"&EmpleadoResponsableAnterior="+EmpleadoResponsableAnterior+"&EmpleadoUsuario="+EmpleadoUsuario+"&EmpleadoUsuarioAnterior="+EmpleadoUsuarioAnterior+"&PreparadoPor="+PreparadoPor+"&InternoExternoFlag="+InternoExternoFlag+"&MotivoTraslado="+MotivoTraslado+"&Comentario="+Comentario+"&MovimientoNumero="+MovimientoNumero);
   var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=guardarEditarMovimientoActivo&Activo="+Activo+"&Organismo="+Organismo+"&OrganismoAnterior="+OrganismoAnterior+"&OrganismoActual="+OrganismoActual+"&CentroCosto="+CentroCosto+"&CentroCostoAnterior="+CentroCostoAnterior+"&Ubicacion="+Ubicacion+"&UbicacionAnterior="+UbicacionAnterior+"&Dependencia="+Dependencia+"&DependenciaAnterior="+DependenciaAnterior+"&EmpleadoResponsable="+EmpleadoResponsable+"&EmpleadoResponsableAnterior="+EmpleadoResponsableAnterior+"&EmpleadoUsuario="+EmpleadoUsuario+"&EmpleadoUsuarioAnterior="+EmpleadoUsuarioAnterior+"&PreparadoPor="+PreparadoPor+"&InternoExternoFlag="+InternoExternoFlag+"&MotivoTraslado="+MotivoTraslado+"&Comentario="+Comentario+"&MovimientoNumero="+MovimientoNumero);

       ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText.trim();
			if (resp != ""){alert(resp); 
			                document.getElementById("numeroMovimientoGenerado").value = resp; 
							//alert('N&uacute;mero Movimiento= '+document.getElementById("numeroMovimientoGenerado").value);
						   } 
			cargarPagina(form,"af_movimientoactivos.php?limit=0");
		}
	  }
     alert("SE HA GENERADO EL MOVIMIENTO");
}
//// -------------------------------------------------------------------------------------
//// -----------------		ACTIVA Y DESACTIVA LOS CAMPOS
//// -------------------------------------------------------------------------------------
function enabledListActivosCatClasf(form){
 if(form.chkCatClasf.checked){ form.fCatClasf.disabled = false; form.btClasif.disabled= false;form.DescpClasificacion.disabled=false;}
 else {form.fCatClasf.disabled = true; form.fCatClasf.value= ""; form.btClasif.disabled= true; form.DescpClasificacion.disabled=true; form.DescpClasificacion.value=""; }
}
function enabledCatClasf(form){
 if(form.chkCatClasf.checked){ form.fCatClasf.disabled = false; form.btClasif.disabled= false;form.DescpClasificacion.disabled=false;
     document.getElementById("clasificacion").style.visibility='visible';
 } else {form.fCatClasf.disabled = true; form.fCatClasf.value= ""; form.btClasif.disabled= true; form.DescpClasificacion.disabled=true; form.DescpClasificacion.value=""; 
  form.fClasificacion.value=''; 
  	document.getElementById("clasificacion").style.visibility='hidden';
  }
}
function enabledProveedor(form){
 if(form.chkproveedor.checked){form.proveedor.disabled=false; form.btProveedor.disabled=false;}
 else{form.proveedor.disabled=true; form.proveedor.value=""; form.fproveedor.value=""; form.btProveedor.disabled=true;}
}
//// -------------------------------------------------------------------------------------
//// -----------------		APROBAR ACTIVO "ESTADO = AP"
//// -------------------------------------------------------------------------------------
function AprobarActivo(form){
   var estado = 'AP';
   var Activo = document.getElementById("nro_activo").value; //alert(Activo);
   var CodOrganismo = document.getElementById("organismo").value; //alert(CodOrganismo);
   var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=AprobarActivo&estado="+estado+"&Activo="+Activo+"&CodOrganismo="+CodOrganismo);
    ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText.trim();
			if (resp != "")alert(resp); 
            else opener.document.getElementById("frmentrada").submit();
		}
    }
    alert("APROBACION DE ALTA EXITOSA");
	//cargarPagina();
	//window.open('af_actaincorporacion.php?Activo='+Activo+'&CodOrganismo='+CodOrganismo,'', 'height=500, width=870, left=200, top=100, resizable=yes');
	//window.open('af_actaentregabm.php?Activo='+Activo+'&CodOrganismo='+CodOrganismo,'','height=500, width=870, left=200, top=100, resizable=yes');
	//cargarVentanaLista(form, 'af_actaincorporacion.php?Activo='+Activo+'&CodOrganismo='+CodOrganismo, 'height=500, width=870, left=200, top=100, resizable=yes');
	//cargarVentanaLista03(form, 'af_actaentregabm.php?Activo='+Activo+'&CodOrganismo='+CodOrganismo, 'height=500, width=870, left=200, top=100, resizable=yes');
	window.close();
}

function DesincorporarActivo(form){
   var estado = 'DE';
   var Activo = document.getElementById("nro_activo").value; //alert(Activo);
   var CodOrganismo = document.getElementById("organismo").value; //alert(CodOrganismo);
   var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=DesincorporarActivo&estado="+estado+"&Activo="+Activo+"&CodOrganismo="+CodOrganismo);
    ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText.trim();
			if (resp != "")alert(resp); 
            else opener.document.getElementById("frmentrada").submit();
		}
    }
    alert("DESINCORPORACION EXITOSA");
	//cargarPagina();
	//window.open('af_actaincorporacion.php?Activo='+Activo+'&CodOrganismo='+CodOrganismo,'', 'height=500, width=870, left=200, top=100, resizable=yes');
	//window.open('af_actaentregabm.php?Activo='+Activo+'&CodOrganismo='+CodOrganismo,'','height=500, width=870, left=200, top=100, resizable=yes');
	//cargarVentanaLista(form, 'af_actaincorporacion.php?Activo='+Activo+'&CodOrganismo='+CodOrganismo, 'height=500, width=870, left=200, top=100, resizable=yes');
	//cargarVentanaLista03(form, 'af_actaentregabm.php?Activo='+Activo+'&CodOrganismo='+CodOrganismo, 'height=500, width=870, left=200, top=100, resizable=yes');
	window.close();
}

//// -------------------------------------------------------------------------------------
//	 -----------------		FUNCION PARA ELIMINAR UN REGISTRO DE LA BASE DE DATOS
function EliminarRegistros(form, pagina, modulo, foraneo) {
	var codigo=form.registro.value;
	if (codigo=="") msjError(1000);
	else {
		//	PREGUNTO SI DESEA ELIMINAR
		var eliminar=confirm("¡Esta seguro de eliminar este registro?");
		if (eliminar) {
			if (foraneo) {
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax=nuevoAjax();
				ajax.open("POST", "af_fphp_ajax.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("modulo="+modulo+"&accion=ELIMINARREGISTROS&codigo="+codigo);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4)	{
						var error=ajax.responseText;
						if (error!=0) alert ("¡"+error+"!");
						else cargarPagina(form, pagina);
					}
				}
			} else cargarPagina(form, pagina);
		}
	}
}
//// -------------------------------------------------------------------------------------
function selectMotMovimiento(form){
    var radio = document.getElementById("radioEstado").value; //alert('Radio = '+radio);
	if(radio == 'I'){ 
	   form.motivoTrasladoInterno.style.display = 'block'; 
	   form.motivoTrasladoExterno.style.display='none'; 
	   form.motivoTrasladoExterno.value="";
	}else{ 
	  form.motivoTrasladoInterno.style.display = 'none'; 
	  form.motivoTrasladoInterno.value = ""; 
	  form.motivoTrasladoExterno.style.display='block';
	}
}
//// -------------------------------------------------------------------------------------
function enabledEstadoActivos(form){
  if(form.chkEstado.checked) form.fEstado.disabled=false;
  else{ form.fEstado.disabled=true; form.fEstado.value="";}
}
//// -------------------------------------------------------------------------------------
function cargarOrganismoMovimiento(form){
   var organismoActual = document.getElementById("organismoActual").value; //alert('organismoActual='+organismoActual);
   
   var ajax=nuevoAjax();
	ajax.open("POST", "af_fphp_ajax.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=cargarOrganismoMovimiento&organismoActual="+organismoActual);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			document.getElementById("organismoActual2").value = resp;
		}
	}
   
}
//// -------------------------------------------------------------------------------------
function cargarOpcionVerCategoria(form, pagina, target, param) {
	var codigo=document.getElementById("select_categoria").value;
	if (target=="SELF") cargarPagina(form, pagina);
	else { pagina=pagina+"?limit=0&accion=VER&registro="+codigo; cargarVentana(form, pagina, param); }
}
function cargarOpcionVerDetalles(form, pagina, target, param) {
	var codigo=document.getElementById("activo").value;
	if (target=="SELF") cargarPagina(form, pagina);
	else { pagina=pagina+"?limit=0&accion=VER&registro="+codigo; cargarVentana(form, pagina, param); window.open(pagina, "wPrincipal", "toolbar=no, menubar=no, location=no, scrollbars=yes, "+param); }
}
//// -------------------------------------------------------------------------------
function cargarOpcionVerCategoria2(form, pagina, target, param) {
	var codigo=document.getElementById("select_categoria").value;
	if (target=="SELF") cargarPagina(form, pagina);
	else { pagina=pagina+"?limit=0&accion=VER&registro="+codigo; cargarVantanaActivo(form, pagina, param); }
}
function cargarOpcionVerDetalles2(form, pagina, target, param) {
	var codigo=document.getElementById("activo").value;
	if (target=="SELF") cargarPagina(form, pagina);
	else { pagina=pagina+"?limit=0&accion=VER&registro="+codigo; cargarVantanaActivo(form, pagina, param); }
}
function cargarVantanaActivo(form, pagina, param) {
	window.open(pagina, "wPrincipal2", "toolbar=no, menubar=no, location=no, scrollbars=yes, "+param);
}
/// --------------------------------------------------------------------------------
/// -----------------		FUNCION QUE PERMITE GUARDAR NUEVA CARACTERISTICA TECNICA
function guardarNuevaCaracteristicaTecnica(formulario){
  
  var CodCaractTecnica = document.getElementById("cod_caractTecnica").value;
  var DescripcionLocal = document.getElementById("descp_local").value;
  var Estado = document.getElementById("radioEstado").value;
   
  //VALIDACION CODIGO CARACTERISTICA TECNICA
   if (formulario.cod_caractTecnica.value.length <1) {
	 alert("Escriba el código de la Caracteristica Tecnica en el campo \"Característica Técnica\".");
	 formulario.cod_caractTecnica.focus();
  return (false);
  }
  var checkOK = "0123456789" + "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ";
  var checkStr = formulario.cod_caractTecnica.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Característica Técnica\"."); 
	 formulario.cod_caractTecnica.focus(); 
	 return (false); 
   } 
   
  //VALIDACION  DESCRIPCION LOCAL
  if (formulario.descp_local.value.length <2) {
	 alert("Escriba la descripción en el campo \"Descripción Local\".");
	 formulario.descp_local.focus();
  return (false);
  }
  var checkOK = "0123456789" + "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ" + " .,;:_-/";
  var checkStr = formulario.descp_local.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Descripción Local\"."); 
	 formulario.descp_local.focus(); 
	 return (false); 
   } 
   
  //	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=guardarNuevaCaracteristicaTecnica&CodCaractTecnica="+CodCaractTecnica+"&DescripcionLocal="+DescripcionLocal+"&Estado="+Estado);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			if (resp != "") alert(resp.trim());
			else cargarPagina(formulario, "af_caracteristicatecnica.php?limit=0");
		}
	}
return false;
}
/// --------------------------------------------------------------------------------
/// -----------------		FUNCION QUE PERMITE EDITAR UNA CARACTERISTICA TECNICA
function editarCaracteristicaTecnica(formulario){
  var CodCaractTecnica = document.getElementById("cod_caractTecnica").value; 
  var DescripcionLocal = document.getElementById("descp_local").value;
  var Estado = document.getElementById("radioEstado").value; alert(Estado);
   
  //VALIDACION CODIGO CARACTERISTICA TECNICA
   if (formulario.cod_caractTecnica.value.length <1) {
	 alert("Escriba el código de la Caracteristica Tecnica en el campo \"Característica Técnica\".");
	 formulario.cod_caractTecnica.focus();
  return (false);
  }
  var checkOK = "0123456789" + "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ";
  var checkStr = formulario.cod_caractTecnica.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Característica Técnica\"."); 
	 formulario.cod_caractTecnica.focus(); 
	 return (false); 
   } 
   
  //VALIDACION  DESCRIPCION LOCAL
  if (formulario.descp_local.value.length <2) {
	 alert("Escriba la descripción en el campo \"Descripción Local\".");
	 formulario.descp_local.focus();
  return (false);
  }
  var checkOK = "0123456789" + "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ" + " .,;:_-/";
  var checkStr = formulario.descp_local.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Descripción Local\"."); 
	 formulario.descp_local.focus(); 
	 return (false); 
   } 
   
  //	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=editarCaracteristicaTecnica&CodCaractTecnica="+CodCaractTecnica+"&DescripcionLocal="+DescripcionLocal+"&Estado="+Estado);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			if (resp != "") alert(resp.trim());
			else cargarPagina(formulario, "af_caracteristicatecnica.php?limit=0");
		}
	}
return false;
}
/// --------------------------------------------------------------------------------
/// -----------------		FUNCION QUE PERMITE ELIMINAR UNA CARACTERISTICA TECNICA
function eliminarCaractTecnica(form,pagina){
	var codigo=form.registro.value; //alert(codigo);
	if (codigo=="") msjError(1000);
	else {
		//	PREGUNTO SI DESEA ELIMINAR
		var eliminar=confirm("¡Esta seguro de eliminar este registro?");
		if (eliminar) {
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax=nuevoAjax();
				ajax.open("POST", "gmactivofijo.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("accion=eliminarCaractTecnica&codigo="+codigo);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4)	{
						var error=ajax.responseText;
						if (error!=0) alert ("¡"+error+"!");
						else cargarPagina(form,pagina);
					}
				}
			}else cargarPagina(form,pagina);
	}
}
/// --------------------------------------------------------------------------------
/// -----------------		FUNCION QUE PERMITE GUARDAR NUEVO COMPONENTE EQUIPO
function guardarNuevoComponenteEquipo(formulario){
 var CodTipoComp = document.getElementById("cod_tipocomponente").value;
 var DescripcionLocal = document.getElementById("descp_local").value;
 var Estado = document.getElementById("radioEstado").value;
 
 //VALIDACION CODIGO CARACTERISTICA TECNICA
   if (formulario.cod_tipocomponente.value.length <1) {
	 alert("Escriba el tipo de equipo en el campo \"Tipo de Equipo\".");
	 formulario.cod_tipocomponente.focus();
  return (false);
  }
  var checkOK = "0123456789" + "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ";
  var checkStr = formulario.cod_tipocomponente.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Tipo de Equipo\"."); 
	 formulario.cod_caractTecnica.focus(); 
	 return (false); 
   } 
   
  //VALIDACION  DESCRIPCION LOCAL
  if (formulario.descp_local.value.length <2) {
	 alert("Escriba la descripción en el campo \"Descripción Local\".");
	 formulario.descp_local.focus();
  return (false);
  }
  var checkOK = "0123456789" + "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ" + " .,;:_-/";
  var checkStr = formulario.descp_local.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Descripción Local\"."); 
	 formulario.descp_local.focus(); 
	 return (false); 
   } 
   
  //	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=guardarNuevoComponenteEquipo&CodTipoComp="+CodTipoComp+"&DescripcionLocal="+DescripcionLocal+"&Estado="+Estado);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			if (resp != "") alert(resp.trim());
			else cargarPagina(formulario, "af_componentesequipo.php?limit=0");
		}
	}
return false;
}
/// --------------------------------------------------------------------------------
/// -----------------		FUNCION QUE PERMITE EDITAR COMPONENTE EQUIPO
function editarComponenteEquipo(formulario){
 var CodTipoComp = document.getElementById("cod_tipocomponente").value;
 var DescripcionLocal = document.getElementById("descp_local").value;
 var Estado = document.getElementById("radioEstado").value;
 
 //VALIDACION CODIGO CARACTERISTICA TECNICA
   if (formulario.cod_tipocomponente.value.length <1) {
	 alert("Escriba el tipo de equipo en el campo \"Tipo de Equipo\".");
	 formulario.cod_tipocomponente.focus();
  return (false);
  }
  var checkOK = "0123456789" + "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ";
  var checkStr = formulario.cod_tipocomponente.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Tipo de Equipo\"."); 
	 formulario.cod_caractTecnica.focus(); 
	 return (false); 
   } 
   
  //VALIDACION  DESCRIPCION LOCAL
  if (formulario.descp_local.value.length <2) {
	 alert("Escriba la descripción en el campo \"Descripción Local\".");
	 formulario.descp_local.focus();
  return (false);
  }
  var checkOK = "0123456789" + "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ" + " .,;:_-/";
  var checkStr = formulario.descp_local.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Descripción Local\"."); 
	 formulario.descp_local.focus(); 
	 return (false); 
   } 
   
  //	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=editarComponenteEquipo&CodTipoComp="+CodTipoComp+"&DescripcionLocal="+DescripcionLocal+"&Estado="+Estado);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			if (resp != "") alert(resp.trim());
			else cargarPagina(formulario, "af_componentesequipo.php?limit=0");
		}
	}
return false;
}
/// --------------------------------------------------------------------------------
/// -----------------		FUNCION QUE PERMITE ELIMINAR COMPONENTE EQUIPO
function eliminarComponentesEquipo(form,pagina){
var codigo=form.registro.value; //alert(codigo);
	if (codigo=="") msjError(1000);
	else {
		//	PREGUNTO SI DESEA ELIMINAR
		var eliminar=confirm("¡Esta seguro de eliminar este registro?");
		if (eliminar) {
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax=nuevoAjax();
				ajax.open("POST", "gmactivofijo.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("accion=eliminarComponentesEquipo&codigo="+codigo);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4)	{
						var error=ajax.responseText;
						if (error!=0) alert ("¡"+error+"!");
						else cargarPagina(form,pagina);
					}
				}
			}else cargarPagina(form,pagina);
	}
}
/// --------------------------------------------------------------------------------
/// -----------------		INSERTAR LINEA AF_LISTACTIVOSAGREGAR CARACTERISTICAS TECNICAS Y DOCUMENTACION
function insertarLineaCaracTecnicasActivo() {
	var candetalle = document.getElementById("candetalle").value; candetalle++;
	var cont = "";
	var detalles = "";
	var error_detalles = "";
	
	// obtengo los valores de las lineas insertadas
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "cont") detalles = new Number(n.value); //alert(detalles);
		    cont = detalles + 1; //alert('cont='+cont);
		/*if (n.name == "l_contable") {
			if (n.value == "") { error_detalles = "¡Debe seleccionar por lo menos un libro contable¡"; break; }
			else detalles += n.value + ";";
		}*/
	}
	
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=insertarLineaCaracTecnicasActivo&candetalle="+candetalle+"&cont="+cont);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			document.getElementById("candetalle").value = candetalle;
			var resp = ajax.responseText;
			
			var newTr = document.createElement("tr");
			newTr.className = "trListaBody";
			newTr.setAttribute("onclick", "mClk(this, 'seldetalle');");
			newTr.id = "det_"+candetalle;
			document.getElementById("listaDetalles").appendChild(newTr);
			document.getElementById("det_"+candetalle).innerHTML = resp;
		}
	}
}
/// -----------------		INSERTAR LINEA AF_LISTACTIVOSAGREGAR CARACTERISTICAS TECNICAS Y DOCUMENTACION
function insertarLineaCaracTecnicasActivo2() {
	var candetalle2 = document.getElementById("candetalle2").value; candetalle2++;
	
	var cont2 = "";
	var detalles2 = "";
	var error_detalles = "";
	
	// obtengo los valores de las lineas insertadas
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "cont2") detalles2 = new Number(n.value); //alert(detalles);
		    cont2 = detalles2 + 1; //alert('cont='+cont);
		/*if (n.name == "l_contable") {
			if (n.value == "") { error_detalles = "¡Debe seleccionar por lo menos un libro contable¡"; break; }
			else detalles += n.value + ";";
		}*/
	}
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=insertarLineaCaracTecnicasActivo2&candetalle2="+candetalle2+"&cont2="+cont2);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			document.getElementById("candetalle2").value = candetalle2;
			var resp = ajax.responseText;
			
			var newTr = document.createElement("tr");
			newTr.className = "trListaBody";
			newTr.setAttribute("onclick", "mClk(this, 'seldetalle2');");
			newTr.id = "det_"+candetalle2;
			document.getElementById("listaDetalles2").appendChild(newTr);
			document.getElementById("det_"+candetalle2).innerHTML = resp;
		}
	}
}
/// --------------------------------------------------------------------------------
/// -----------------		FUNCION QUE PERMITE ELIMINAR LINEA DE REGISTRO DE CONTABILIDADES
function quitarLineaCompuestoActivos(seldetalle2) {
	var listaDetalles = document.getElementById("listaDetalles2");
	var tr = document.getElementById(seldetalle2);
	listaDetalles.removeChild(tr);
	document.getElementById("seldetalle2").value = "";
}
/// --------------------------------------------------------------------------------
/// 
function enabledClasf(form){
 if(form.chkClasificacion.checked){ form.btClasif.disabled=false; form.DescpClasificacion.disabled=false;
   document.getElementById('clasf').style.visibility= 'visible';}
 else{form.DescpClasificacion.value=''; form.fClasificacion.value='';form.btClasif.disabled=true;
    document.getElementById('clasf').style.visibility= 'hidden'; form.DescpClasificacion.disabled=true;
 }
}
function enabledClasf20(form){
   if(form.chkClasf20.checked){ form.btclasf20.disabled = false; form.DescpClasf20.disabled=false; document.getElementById('clasificacion20').style.visibility= 'visible';}
   else{form.btclasf20.disabled=true; form.DescpClasf20.value=''; form.fClasf20.value=''; form.DescpClasf20.disabled=true; document.getElementById('clasificacion20').style.visibility= 'hidden';}
}

//// ------------------------------------------------------------------------------------- ///
//// -----------------		funcion para convertir un numero formateado en su valor real
//// ------------------------------------------------------------------------------------- ///
function setNumero(num_formateado) {
	var num = num_formateado.toString();
	num = num.replace(/[.]/gi, "");
	num = num.replace(/[,]/gi, ".");
	
	var numero = new Number(num);
	return numero;
}
//// 
function setNumeroFormato(num, dec, sep_mil, sep_dec) {
	var oNum = new oNumero(num);
	var num_formateado = oNum.formato(dec, true);
	var numero = num_formateado.toString();
	
	numero = numero.replace(/[.]/gi, ";");
	numero = numero.replace(/[,]/gi, sep_mil); 
	numero = numero.replace(/[;]/gi, sep_dec);
	
	return numero;
}
//// ------------------------------------------------------------------------------------- ///
////
function cambioFormatoCantidad(id,valor){
	//alert(id+"="+valor);
    valorF = setNumeroFormato(valor,2,'.',','); //alert("valorF="+valorF);
	document.getElementById(id).value = valorF;
}
//// ------------------------------------------------------------------------------------- ///
//// -----------------		ACTIVA Y DESACTIVA CAMPO EN FILTRO AGRUPAR/CONSOLIODAR
function enabledNroActivoAgrupCons(form){
  if(form.chkNroActivo.checked){ form.fNroActivo.disabled=false; form.btNroActivo.disabled=false;}
  else{ form.fNroActivo.disabled=true; form.fNroActivo.value=''; form.btNroActivo.disabled=true;}
}
//// ------------------------------------------------------------------------------------- ///
//// -----------------		INSERTAR LINEA AF_CATEGORIANUEVA
function insertarLineaAgroCons(form) {
	var clickAF = document.getElementById("clickAF").value; 
	var valor = document.getElementById("registro").value; 
		valor = valor.split('|');
		var registro = valor[0];	
	
	
	if($('#registro').val()=="") msjError(1000);
	else
	if(clickAF==1){
	//var codigo = document.getElementById("codigoMostrar").value; alert('codigo='+codigo);
	var codigo = registro; //alert('codigo='+codigo);
	if (codigo=="") msjError(1000);
	
	else{
		var candetalle = document.getElementById("can_detalle").value; candetalle++;
		
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "gmactivofijo.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=insertarLineaAgroCons&candetalle="+candetalle+"&codigo="+codigo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				document.getElementById("can_detalle").value = candetalle;
				var resp = ajax.responseText;
			    if(document.getElementById("det_"+codigo)) alert("¡El Activo ya fue seleccionado¡");
				else $("#lista_detalle").append(resp);
			}
		}
	}
	}
}
//// ------------------------------------------------------------------------------------- ///
//// -----------------		FUNCION QUE CAMBIA DE COLOR LA FILA DE UNA TABLA AL HACER CLICK EL PUNTERO DEL MOUSE 
//// -----------------		SOBRE ELLA PARA AGRUPACION/CONSOLIDACION
function mClkAC(src, registro) {
	var seleccionado=document.getElementsByTagName("tr");
	for (var i=0; i<seleccionado.length; i++) {
		if (seleccionado[i].getAttribute((document.all ? 'className' : 'class')) ==	'trListaBodySel') {
			seleccionado[i].setAttribute((document.all ? 'className' : 'class'), "trListaBody");
			break;
		}
	}
	//
	var row=document.getElementById(src.id);	//	OBTENGO LA FILA DEL CLICK
	row.className="trListaBodySel";	//	CAMBIO EL COLOR DE LA FILA
	
	var registro=document.getElementById(registro); //alert('Registro = '+registro);
	registro.value=src.id;  //alert(registro.value);
	document.getElementById("codigoMostrar").value = registro.value;
}
//// -------------------------------------------------------------------------------------- ///
////
function clickAC(){
   document.getElementById("clickAF").value = 1; //alert(document.getElementById("clickAF").value);;
}
//// -------------------------------------------------------------------------------------- ///
//// -----------------		FUNCION QUE PERMITE ACTIVIAR Y DESACTIVAR CAMPOS
function enalbledNaturalezaActivoFijos(form){
 if(form.chkNaturaleza.checked) form.fNaturaleza.disabled=false;
 else{ form.fNaturaleza.disabled=true; form.fNaturaleza.value='';}
}
//// --------------------------------------------------------------------------------------
//// 	FUNCION QUE PERMITE ACTIVIAR Y DESACTIVAR CAMPOS
//// -------------------------------------------------------------------------------------- ///
function enabledOrdenarPor(form){
 if(form.chkOrdenarPor.checked) form.fOrdenarPor.disabled= false;
 else{form.fOrdenarPor.disabled=true; form.fOrdenarPor.value='';} 
}
function enabledUbicacionActivos(form){
 if(form.chkUbicacion.checked){ form.btUbicacion.disabled=false; form.fUbicacion.disabled=false; form.DescpUbicacion.disabled=false; 
   document.getElementById("ubicacion").style.visibility='visible';
 }else{form.btUbicacion.disabled=true; form.fUbicacion.value=''; form.DescpUbicacion.value=''; form.fUbicacion.disabled=true; form.DescpUbicacion.disabled=true;
    document.getElementById("ubicacion").style.visibility='hidden';
 }
}
function enabledCentroCostoActivos(form){
 if(form.chkCentroCosto.checked){ form.btCentroCosto.disabled=false; form.fCentroCosto.disabled=false; form.fCentroCosto2.disabled=false;
     document.getElementById("c_costos").style.visibility='visible';
 }else{form.fCentroCosto.value=''; form.fCentroCosto2.value=''; form.btCentroCosto.disabled=true;form.fCentroCosto.disabled=true; form.fCentroCosto2.disabled=true;
    document.getElementById("c_costos").style.visibility='hidden';
 }
} 
function enabledPersonaActivo(form){
 if(form.chkPersona.checked){ 
 	form.btPersona.disabled=false; form.fPersona.disabled=false;document.getElementById("persona").style.visibility='visible';
	form.NombPersona.disabled=false;
}else{
	form.btPersona.disabled=false; form.NombPersona.disabled=true; form.fPersona.disabled=true;
	form.fPersona.value=''; form.NombPersona.value=''; document.getElementById("persona").style.visibility='hidden';
 }
}
function enabledContabilidadTransacciones(form){
 if(form.chkContabilidad.checked) form.fContabilidad.disabled=false;
 else{ form.fContabilidad.disabled=true; form.fContabilidad.value='';}
}
function enabledActivosTransaccion(form){
 if(form.chkActivo.checked) form.fActivo.disabled = false;
 else{form.fActivo.disabled=true; form.fActivo.value=''; }
}
function enabledPeriodoTransaccion(form){
 if(form.chkPeriodo.checked) form.fPeriodo.disabled=false;
 else{ form.fPeriodo.disabled=true; form.fPeriodo.value='';}
}
function enabledPeriodoTransaccion(form){
 if(form.chkPeriodo.checked) form.fPeriodo.disabled=false;
 else{form.fPeriodo.disabled=true; form.fPeriodo.value='';}
}
function enabledFechaTransaccion(form){
 if(form.chkFecha.checked){ form.fdesde.disabled=false; form.fhasta.disabled=false;}
 else{form.fdesde.disabled=true; form.fhasta.disabled=true; form.fdesde.value=''; form.fhasta.value='';}
}
function enabledEstadoTransaccion(form){
 if(form.chkEstado.checked) form.fEstado.disabled=false;
 else{ form.fEstado.disabled=true; form.fEstado.value='';}
}
/// --------------------------------------------------------------------------------
/// -----------------		INSERTAR LINEA AF_TRANSACCIONESTIPOTRANSACCION -MAESTRO TIPO TRANSACCION
/// -------------------------------------------------------------------------------- ///
function insertarLineaTipoTransaccion() {
	
var  v_obtengo = Number(document.getElementById("valorObtengo").value); //alert('v_obtengo='+v_obtengo);
     if(v_obtengo>0){
  	   document.getElementById("contador").value = v_obtengo; 
	   var contador= Number(document.getElementById("contador").value); contador++;//alert('1='+contador);  alert('2='+contador);
	   document.getElementById("contador").value = contador; 
	   document.getElementById("valorObtengo").value = 0;
	 }else{
	    var contador= Number(document.getElementById("contador").value); contador++;//alert('1='+contador);  alert('2='+contador);
		document.getElementById("contador").value = contador; 	  
	 }
	   
	var  nrodetalle= Number(document.getElementById("nrodetalle").value); nrodetalle++; //alert(nrodetalle) ;	
	     document.getElementById("nrodetalle").value = nrodetalle;
	
	var candetalle = document.getElementById("candetalle").value; candetalle++;
	
	
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=insertarLineaTipoTransaccion&candetalle="+candetalle+"&contador="+contador);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			document.getElementById("candetalle").value = candetalle;
			var resp = ajax.responseText;
			
			var newTr = document.createElement("tr");
			newTr.className = "trListaBody";
			newTr.setAttribute("onclick", "mClk(this, 'seldetalle');");
			newTr.id = "det_"+candetalle; //alert(newTr.id);
			document.getElementById("listaDetalles").appendChild(newTr);
			document.getElementById("det_"+candetalle).innerHTML = resp;
		}
	}
}
/// --------------------------------------------------------------------------------
/// -----------------		CONTROL DE ESTADO
/// -------------------------------------------------------------------------------- ///
function tipoTransaccion(form, valor){
  var t_transaccion  = document.getElementById('tipo_transa').value; 
 if((t_transaccion == 'A')&&(form.Alta.checked)&&(valor!="a")) {
	   form.Alta.checked = false; 
	   form.Baja.checked = true; 
	   document.getElementById('tipo_transa').value = 'B' ; 
 }else{
	 if((t_transaccion == 'B')&&(form.Baja.checked)&&(valor!="b")) {
	   form.Baja.checked = false;
	   form.Alta.checked = true; 
	   document.getElementById('tipo_transa').value = 'A' ; 
	 }
 }
}
/// -------------------------------------------------------------------------------- ///
/// -----------------		ELIMINAR CLASIFICACION20
/// -------------------------------------------------------------------------------- ///
function eliminarClasificacion20(form) {
	var codigo=form.seleccion.value;
	if (codigo=="") msjError(1000);
	else {
		//	PREGUNTO SI DESEA ELIMINAR
		var eliminar=confirm("¡Esta seguro de eliminar este registro?");
		if (eliminar) {
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax=nuevoAjax();
				ajax.open("POST", "gmactivofijo.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("accion=eliminarClasificacion20&codigo="+codigo);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4)	{
						var error=ajax.responseText;
						if (error!=0) alert ("¡"+error+"!");
						else cargarPagina(form, "af_clasificacion_activos_20.php?limit=0");
					}
				}
		}
	}
	return false;
}
