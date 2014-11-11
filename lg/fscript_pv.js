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
function mClk(src, registro) {
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
}

//	FUNCION PARA CARGAR UNA NUEVA PAGINA 
function cargarPagina(form, pagina) {
	form.method="POST";
	form.action=pagina;
	form.submit();
}

//	FUNCION PARA CARGAR UNA NUEVA PAGINA 
function cargarOpcion(form, seleccion, pagina, target, param) {
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
			ajax.open("POST", "fphp_ajax_pv.php", true);
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
}

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

//	--------------------------------------------------------------------------------
//	--
//	--------------------------------------------------------------------------------


//	TIPO DE CUENTAS
function verificarTipoCuenta(form, accion) {
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	if (codigo == "" || descripcion == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else if (!valNumerico(codigo)) alert("¡El código debe ser númerico!");
	else if (!valAlfabeto(descripcion)) alert("¡No se permiten caracteres especiales en el campo descripción!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pv.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPO-CUENTA&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp);
				else cargarPagina(form, "pv_tipo_cuenta.php");
			}
		}
	}
	return false;
}
//	---------------------------------------------


//	CLASIFICADOR PRESUPUESTARIO
function verificarClasificadorPresupuestario(form, accion) {
	var cuenta = document.getElementById("cuenta").value; cuenta = cuenta.trim();
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var par = document.getElementById("par").value; par = par.trim();
	var gen = document.getElementById("gen").value; gen = gen.trim();
	var esp = document.getElementById("esp").value; esp = esp.trim();
	var subesp = document.getElementById("subesp").value; subesp = subesp.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	var codcuenta = document.getElementById("codcuenta").value;
	if (document.getElementById("titulo").checked) var tipo = "T"; else var tipo = "D";
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	if (cuenta == "" || par == "" || gen == "" || esp == "" || subesp == "" || descripcion == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else if (!valAlfanumerico(descripcion)) alert("¡No se permiten caracteres especiales en el campo descripción!");
	else if (!valNumerico(par) || !valNumerico(gen) || !valNumerico(esp) || !valNumerico(subesp)) alert("¡El código de la partida debe ser númerico!");
	else if (par.length != 2 || gen.length != 2 || esp.length != 2 || subesp.length != 2) alert("¡El código de la partida debe tener por lo menos dos digitos!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pv.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CLASIFICADOR-PRESUPUESTARIO&accion="+accion+"&cuenta="+cuenta+"&par="+par+"&esp="+esp+"&subesp="+subesp+"&gen="+gen+"&descripcion="+descripcion+"&status="+status+"&tipo="+tipo+"&codigo="+codigo+"&codcuenta="+codcuenta);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText.trim();
				if (resp != "") alert(resp);
				else cargarPagina(form, "pv_clasificador_presupuestario.php");
			}
		}
	}
	return false;
}
//	---------------------------------------------