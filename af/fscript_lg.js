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
function mClkMulti(src) {
	//	Obtengo el id para seleccionar el check
	var fila = src.id.split("_");
	var id = fila[1];
	
	//	Obtengo la fila y le cambio el color
	var row = document.getElementById(src.id);
	if (row.className == "trListaBodySel") {
		row.className="trListaBody";
		document.getElementById(id).checked = false;
	} else {
		row.className="trListaBodySel";
		document.getElementById(id).checked = true;
	}
	
}

//	FUNCION PARA CARGAR UNA NUEVA PAGINA 
function cargarPagina(form, pagina) {
	form.method="POST";
	form.action=pagina;
	form.submit();
}

//	FUNCION PARA CARGAR UNA NUEVA PAGINA 
function cargarOpcion(form, pagina, target, param) {
	var codigo=form.registro.value;
	if (codigo=="") alert("¡DEBE SELECCIONAR UN REGISTRO!");
	else {
		if (target=="SELF") cargarPagina(form, pagina);
		else { pagina=pagina+"?limit=0&accion=VER&registro="+codigo; cargarVentana(form, pagina, param); }
	}
}

//	FUNCION PARA ELIMINAR UN REGISTRO DE LA BASE DE DATOS
function eliminarRegistro(form, pagina, foraneo, modulo, accion) {
	var codigo=form.registro.value;
	if (codigo=="") alert("¡DEBE SELECCIONAR UN REGISTRO!");
	else {
		//	PREGUNTO SI DESEA ELIMINAR
		var eliminar=confirm("¡Esta seguro de eliminar este registro?");
		if (eliminar) {
			if (foraneo) {
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax=nuevoAjax();
				ajax.open("POST", "fphp_ajax_lg.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("modulo="+modulo+"&accion="+accion+"&codigo="+codigo);
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
function totalRegistrosRequerimientos(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
	var btNuevo=document.getElementById("btNuevo");
	var btEditar=document.getElementById("btEditar");
	var btRevisar=document.getElementById("btRevisar");
	var btAprobar=document.getElementById("btAprobar");
	var btAnular=document.getElementById("btAnular");
	var btCerrar=document.getElementById("btCerrar");
	var btVer=document.getElementById("btVer");
	//
	if (insert=="N") btNuevo.disabled=true;
	if (update=="N" || !rows) btEditar.disabled=true;
	if (!rows) {
		btRevisar.disabled=true;
		btAprobar.disabled=true;
		btAnular.disabled=true;
		btCerrar.disabled=true;
		btVer.disabled=true;
	}
}
function totalRegistrosRequerimientosDet(rows) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
	var btVer=document.getElementById("btVer");
	//
	if (!rows) btVer.disabled=true;
}
function totalRegistrosStock(rows) {
	var numreg = document.getElementById("rows_stock");
	numreg.innerHTML="Registros: "+rows;
	var btInvitarStock=document.getElementById("btInvitarStock");
	//
	if (!rows) btInvitarStock.disabled=true;
}
function totalRegistrosComm(rows) {
	var numreg = document.getElementById("rows_comm");
	numreg.innerHTML="Registros: "+rows;
	var btInvitarComm=document.getElementById("btInvitarComm");
	//
	if (!rows) btInvitarComm.disabled=true;
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
function totalLista(rows) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
}
function totalListaRows(id, rows) {
	var numreg = document.getElementById(id);
	numreg.innerHTML="Registros: "+rows;
}
function totalElementosRequerimientos(rows) {
	if (rows) {
		var btEditar=document.getElementById("btEditar"); btEditar.disabled=false;
		var btBorrar=document.getElementById("btBorrar"); btBorrar.disabled=false;
	} else {		
		var btEditar=document.getElementById("btEditar"); btEditar.disabled=true;
		var btBorrar=document.getElementById("btBorrar"); btBorrar.disabled=true;
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

//	FUNCION PARA SELECCIONAR UN REGISTRO DE UNA LISTA Y COLOCARLO EN OTRA VENTANA
function selLinea(campo, busqueda) {
	var registro=document.getElementById("registro").value;
	switch (campo) {
		case "linea":
			opener.document.frmentrada.codlinea.value=registro;
			opener.document.frmentrada.nomlinea.value=busqueda;
			break;
	}
	window.close();
}
function selFamilia(campo1, campo2, busqueda1, busqueda2) {
	var registro = document.getElementById("registro").value;
	var codigo = registro.split("|");
	switch (campo1) {
		case "linea":
			opener.document.frmentrada.codlinea.value=codigo[0];
			opener.document.frmentrada.nomlinea.value=busqueda1;
			break;
	}
	switch (campo2) {
		case "familia":
			opener.document.frmentrada.codfamilia.value=codigo[1];
			opener.document.frmentrada.nomfamilia.value=busqueda2;
			break;
	}
	window.close();
}
function selSubFamilia(campo1, campo2, campo3, busqueda1, busqueda2, busqueda3) {
	var registro = document.getElementById("registro").value;
	var codigo = registro.split("|");
	switch (campo1) {
		case "linea":
			opener.document.frmentrada.codlinea.value=codigo[0];
			opener.document.frmentrada.nomlinea.value=busqueda1;
			break;
		case "flinea":
			opener.document.frmentrada.fcodlinea.value=codigo[0];
			opener.document.frmentrada.fnomlinea.value=busqueda1;
			break;
	}
	switch (campo2) {
		case "familia":
			opener.document.frmentrada.codfamilia.value=codigo[1];
			opener.document.frmentrada.nomfamilia.value=busqueda2;
			break;
		case "ffamilia":
			if (opener.document.frmentrada.chkfamilia.checked) {
				opener.document.frmentrada.fcodfamilia.value=codigo[1];
				opener.document.frmentrada.fnomfamilia.value=busqueda2;
			} else {
				opener.document.frmentrada.fcodfamilia.value="";
				opener.document.frmentrada.fnomfamilia.value="";
			}
			break;
	}
	switch (campo3) {
		case "subfamilia":
			opener.document.frmentrada.codsubfamilia.value=codigo[2];
			opener.document.frmentrada.nomsubfamilia.value=busqueda3;
			break;
		case "fsubfamilia":
			if (opener.document.frmentrada.chksubfamilia.checked) {
				opener.document.frmentrada.fcodsubfamilia.value=codigo[2];
				opener.document.frmentrada.fnomsubfamilia.value=busqueda3;
			} else {
				opener.document.frmentrada.fcodsubfamilia.value="";
				opener.document.frmentrada.fnomsubfamilia.value="";
			}
			break;
	}
	window.close();
}
function selListado(busqueda, cod, nom) {
	var registro=document.getElementById("registro").value;
	opener.document.getElementById(cod).value=registro;
	opener.document.getElementById(nom).value=busqueda;
	window.close();
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
function chkFiltroLinea(boo) {
	document.getElementById("btLinea").disabled = !boo;
	document.getElementById("fcodlinea").value = "";
	document.getElementById("fnomlinea").value = "";
	document.getElementById("fcodfamilia").value = "";
	document.getElementById("fnomfamilia").value = "";
	document.getElementById("fcodsubfamilia").value = "";
	document.getElementById("fnomsubfamilia").value = "";
}
function chkFiltroFamilia(boo) {
	document.getElementById("fcodfamilia").value = "";
	document.getElementById("fnomfamilia").value = "";
	
}
function chkFiltroSubFamilia(boo) {
	document.getElementById("fcodsubfamilia").value = "";
	document.getElementById("fnomsubfamilia").value = "";
	
}
function enabledBuscar(form) {
	if (form.chkbuscar.checked) { form.fbuscar.disabled=false; form.sltbuscar.disabled=false; } 
	else { form.fbuscar.disabled=true; form.sltbuscar.disabled=true; form.fbuscar.value=""; form.sltbuscar.value=""; }
}
function enabledCCosto(form) {
	if (form.chkccosto.checked) form.btCCosto.disabled=false;
	else { form.btCCosto.disabled=true; form.fccosto.value=""; }
}
function enabledOrganismo(form) {
	if (form.chkorganismo.checked) form.forganismo.disabled=false; 
	else { form.forganismo.disabled=true; form.fdependencia.disabled=true; form.chkdependencia.checked=false; form.forganismo.value=""; form.fdependencia.value=""; }
}
function enabledDependencia(form) {
	if (form.chkorganismo.checked && form.chkdependencia.checked) form.fdependencia.disabled=false;
	else { form.fdependencia.disabled=true; form.fdependencia.value=""; }
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

//	--------------------------------------------------------------------------------
//	--
//	--------------------------------------------------------------------------------


//	CLASIFICACION DE COMMODITIES
function verificarClasificacionCommodities(form, accion) {
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("flagtransaccion").checked) var flagtransaccion = "S"; else var flagtransaccion = "N";
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	if (codigo == "" || descripcion == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else if (codigo.length < 3) alert("¡LA CLASIFICACION DEBE TENER 3 DIGITOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CLASIFICACION-COMMODITIES&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&flagtransaccion="+flagtransaccion+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else cargarPagina(form, "clasificacion_commodities.php");
			}
		}
	}
	return false;
}

//	COMMODITIES
function verificarCommodities(form, accion) {
	var clasificacion = document.getElementById("clasificacion").value;
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	if (clasificacion == "" || descripcion == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=COMMODITIES&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&clasificacion="+clasificacion+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else  {
					if (accion == "GUARDAR-MAST") {
						if (confirm("¿Desea ingresar las sub clasificaciones?")) {
							document.getElementById("bt_guardar").disabled = true;
							document.getElementById("frameSub").src = "commodity_sub_clasificacion.php?commoditymast="+codigo;
						} else cargarPagina(form, "commodity.php");
					} else cargarPagina(form, "commodity.php");
				}
			}
		}
	}
	return false;
}
//	---------------------------------------------------
function verificarSubCommodity(form, accion) {
	var commoditymast = document.getElementById("commoditymast").value;
	var codigo = document.getElementById("codigo").value;
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	var unidad = document.getElementById("unidad").value;
	var estado = document.getElementById("estado").value;
	
	if (descripcion == "" || unidad == "" || estado == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=COMMODITIES&accion="+accion+"&commoditymast="+commoditymast+"&codigo="+codigo+"&descripcion="+descripcion+"&unidad="+unidad+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else  {
					cargarPagina(form, "commodity_sub_clasificacion.php");
				}
			}
		}
	}
	return false;
}
//	---------------------------------------------------
function editarSubCommodity(form, accion) {
	var commoditymast = document.getElementById("commoditymast").value;
	var registro = document.getElementById("registro").value;
	
	if (registro == "") alert("¡DEBE SELECCIONAR UN REGISTRO!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=COMMODITIES&accion="+accion+"&commoditymast="+commoditymast+"&codigo="+registro);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var dato = resp.split("|:|");
				if (dato[0] != 0) alert(resp);
				else  {
					document.getElementById("codigo").value = dato[1];
					document.getElementById("descripcion").value = dato[2];
					document.getElementById("unidad").value = dato[3];
					document.getElementById("estado").value = dato[4];
					document.getElementById("btEditar").disabled = true;
					document.getElementById("btEliminar").disabled = true;
					document.getElementById("accion").value = "ACTUALIZAR-DET";
				}
			}
		}
	}
	return false;
}
//	---------------------------------------------------
function borrarSubCommodity(form, accion) {
	var commoditymast = document.getElementById("commoditymast").value;
	var registro = document.getElementById("registro").value;
	
	if (registro == "") alert("¡DEBE SELECCIONAR UN REGISTRO!");
	else {
		if (confirm("¿Esta seguro de eliminar este registro?")) {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax_lg.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=COMMODITIES&accion="+accion+"&commoditymast="+commoditymast+"&codigo="+registro);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText;
					if (resp != 0) alert(resp);
					else  {
						cargarPagina(form, "commodity_sub_clasificacion.php");
					}
				}
			}
		}
	}
	return false;
}

//	UNIDADES DE MEDIDA
function verificarUnidadesMedida(form, accion) {
	var tipo = document.getElementById("tipo").value;
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	if (tipo == "" || codigo == "" || descripcion == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=UNIDADES-MEDIDA&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&tipo="+tipo+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else  {
					if (accion == "GUARDAR-MAST") {
						if (confirm("¿Desea ingresar las unidades equivalentes?")) {
							document.getElementById("bt_guardar").disabled = true;
							document.getElementById("frameEquivalentes").src = "unidades_medida_equivalentes.php?codunidad="+codigo;
						} else cargarPagina(form, "unidades_medida.php");
					} else cargarPagina(form, "unidades_medida.php");
				}
			}
		}
	}
	return false;
}
//	---------------------------------------------------
function verificarUnidadesEquivalentes(form, accion) {
	var codunidad = document.getElementById("codunidad").value;
	var equivalente = document.getElementById("equivalente").value;
	var cantidad = document.getElementById("cantidad").value; cantidad = cantidad.trim();
	var estado = document.getElementById("estado").value;
	
	if (equivalente == "" || cantidad == "" || estado == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else if (equivalente == codunidad) alert("¡NO PUEDE INGRESAR COMO EQUIVALENTE LA MISMA UNIDAD!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=UNIDADES-MEDIDA&accion="+accion+"&codunidad="+codunidad+"&equivalente="+equivalente+"&cantidad="+cantidad+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else  {
					cargarPagina(form, "unidades_medida_equivalentes.php");
				}
			}
		}
	}
	return false;
}
//	---------------------------------------------------
function editarUnidadesEquivalentes(form, accion) {
	var codunidad = document.getElementById("codunidad").value;
	var registro = document.getElementById("registro").value;
	
	if (registro == "") alert("¡DEBE SELECCIONAR UN REGISTRO!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=UNIDADES-MEDIDA&accion="+accion+"&codunidad="+codunidad+"&equivalente="+registro);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var dato = resp.split("|:|");
				if (dato[0] != 0) alert(resp);
				else  {
					document.getElementById("equivalente").value = dato[1];
					document.getElementById("cantidad").value = dato[2];
					document.getElementById("estado").value = dato[3];
					document.getElementById("equivalente").disabled = true;
					document.getElementById("btEditar").disabled = true;
					document.getElementById("btEliminar").disabled = true;
					document.getElementById("accion").value = "ACTUALIZAR-DET";
				}
			}
		}
	}
	return false;
}
//	---------------------------------------------------
function borrarUnidadesEquivalentes(form, accion) {
	var codunidad = document.getElementById("codunidad").value;
	var registro = document.getElementById("registro").value;
	
	if (registro == "") alert("¡DEBE SELECCIONAR UN REGISTRO!");
	else {
		if (confirm("¿Esta seguro de eliminar este registro?")) {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax_lg.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=UNIDADES-MEDIDA&accion="+accion+"&codunidad="+codunidad+"&equivalente="+registro);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText;
					if (resp != 0) alert(resp);
					else  {
						cargarPagina(form, "unidades_medida_equivalentes.php");
					}
				}
			}
		}
	}
	return false;
}

//	ALMACENES
function verificarAlmacenes(form, accion) {
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	var tipo = document.getElementById("tipo").value; tipo = tipo.trim();
	var principal = document.getElementById("principal").value; principal = principal.trim();
	var organismo = document.getElementById("organismo").value; organismo = organismo.trim();
	var dependencia = document.getElementById("dependencia").value; dependencia = dependencia.trim();
	var codpersona = document.getElementById("codpersona").value; codpersona = codpersona.trim();
	var cuenta = document.getElementById("cuenta").value; cuenta = cuenta.trim();
	var direccion = document.getElementById("direccion").value; direccion = direccion.trim();
	if (document.getElementById("flagventa").checked) var flagventa = "S"; else var flagventa = "N";
	if (document.getElementById("flagproduccion").checked) var flagproduccion = "S"; else var flagproduccion = "N";
	if (document.getElementById("flagcommodities").checked) var flagcommodities = "S"; else var flagcommodities = "N";
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (codigo == "" || descripcion == "" || tipo == "" || codpersona == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=ALMACENES&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&tipo="+tipo+"&principal="+principal+"&organismo="+organismo+"&dependencia="+dependencia+"&codpersona="+codpersona+"&cuenta="+cuenta+"&direccion="+direccion+"&flagventa="+flagventa+"&flagproduccion="+flagproduccion+"&flagcommodities="+flagcommodities+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else cargarPagina(form, "almacenes.php");
			}
		}
	}
	return false;
}
//	---------------------------------------------------
function enabledAlmacenPrincipal(tipo) {
	if (tipo != "T") document.getElementById("principal").disabled = true;
	else document.getElementById("principal").disabled = false;
}

//	CLASIFICACIONES
function verificarClasificaciones(form, accion) {
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("requisiciones").checked) var disponible = "R"; else var disponible = "O";
	var codalmacen = document.getElementById("codalmacen").value;
	var requerimiento = document.getElementById("requerimiento").value;
	if (document.getElementById("flagrevision").checked) var flagrevision = "S"; else var flagrevision = "N";
	if (document.getElementById("flagrecepcion").checked) var flagrecepcion = "S"; else var flagrecepcion = "N";
	if (document.getElementById("flagtransaccion").checked) var flagtransaccion = "S"; else var flagtransaccion = "N";
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	if (document.getElementById("almacen").checked) var almacen_compra = "A"; else var almacen_compra = "C";
	
	if (codigo == "" || descripcion == "" || requerimiento == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CLASIFICACIONES&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&requerimiento="+requerimiento+"&disponible="+disponible+"&codalmacen="+codalmacen+"&flagrevision="+flagrevision+"&flagrecepcion="+flagrecepcion+"&flagtransaccion="+flagtransaccion+"&almacen_compra="+almacen_compra+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else cargarPagina(form, "clasificaciones.php");
			}
		}
	}
	return false;
}

//	TIPOS DE DOCUMENTOS
function verificarTiposDocumentos(form, accion) {
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("flagdocfiscal").checked) var flagdocfiscal = "S"; else var flagdocfiscal = "N";
	if (document.getElementById("flagtransaccion").checked) var flagtransaccion = "S"; else var flagtransaccion = "N";
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (codigo == "" || descripcion == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPOS-DOCUMENTOS&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&flagdocfiscal="+flagdocfiscal+"&flagtransaccion="+flagtransaccion+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else cargarPagina(form, "tipos_documentos.php");
			}
		}
	}
	return false;
}

//	TIPOS DE TRANSACCIONES
function verificarTiposTransacciones(form, accion) {
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("ingreso").checked) var tipo = "I"; 
	else if (document.getElementById("egreso").checked) var tipo = "E"; 
	else if (document.getElementById("transferencia").checked) var tipo = "T"; 
	var docgenerado = document.getElementById("docgenerado").value;
	var doctransaccion = document.getElementById("doctransaccion").value;
	if (document.getElementById("flagconsumo").checked) var flagconsumo = "S"; else var flagconsumo = "N";
	if (document.getElementById("flagajuste").checked) var flagajuste = "S"; else var flagajuste = "N";
	if (document.getElementById("flagtransaccion").checked) var flagtransaccion = "S"; else var flagtransaccion = "N";
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (codigo == "" || descripcion == "" || doctransaccion == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPOS-TRANSACCIONES&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&tipo="+tipo+"&docgenerado="+docgenerado+"&doctransaccion="+doctransaccion+"&flagconsumo="+flagconsumo+"&flagajuste="+flagajuste+"&flagtransaccion="+flagtransaccion+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else cargarPagina(form, "tipos_transacciones.php");
			}
		}
	}
	return false;
}
//	---------------------------------------------------
function setDocumentoGenerado(tipo) {
	if (tipo == "NI") {
		document.getElementById("docgenerado").value = "NI";
		document.getElementById("docgeneradodesc").value = "Nota de Ingreso";
	}
	else if (tipo == "NS") {
		document.getElementById("docgenerado").value = "NS";
		document.getElementById("docgeneradodesc").value = "Nota de Salida";
	}
	else if (tipo == "NT") {
		document.getElementById("docgenerado").value = "NT";
		document.getElementById("docgeneradodesc").value = "Nota de Transferencia";
	}
}

//	TIPOS DE ITEMS
function verificarTiposItems(form, accion) {
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("flagtransaccion").checked) var flagtransaccion = "S"; else var flagtransaccion = "N";
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (descripcion == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPOS-ITEMS&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&flagtransaccion="+flagtransaccion+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else cargarPagina(form, "tipos_items.php");
			}
		}
	}
	return false;
}

//	ITEMS
function verificarItem(form, accion) {
	var codigo = document.getElementById("codigo").value;
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	var titem = document.getElementById("titem").value;
	var unidad = document.getElementById("unidad").value;
	var unidad_compra = document.getElementById("unidad_compra").value;
	var unidad_embalaje = document.getElementById("unidad_embalaje").value;
	var codlinea = document.getElementById("codlinea").value;
	var codfamilia = document.getElementById("codfamilia").value;
	var codsubfamilia = document.getElementById("codsubfamilia").value;
	var codinterno = document.getElementById("codinterno").value; codinterno = codinterno.trim();
	var imagen = document.getElementById("imagen").value; imagen = imagen.trim();
	var marca = document.getElementById("marca").value;
	var color = document.getElementById("color").value;
	var procedencia = document.getElementById("procedencia").value;
	var codbarra = document.getElementById("codbarra").value; codbarra = codbarra.trim();
	var stockmin = document.getElementById("stockmin").value; stockmin = stockmin.trim();
	var stockmax = document.getElementById("stockmax").value; stockmax = stockmax.trim();
	var ctainventario = document.getElementById("ctainventario").value; ctainventario = ctainventario.trim();
	var ctagasto = document.getElementById("ctagasto").value; ctagasto = ctagasto.trim();
	var ctaventa = document.getElementById("ctaventa").value; ctaventa = ctaventa.trim();
	var partida = document.getElementById("partida").value; partida = partida.trim();
	if (document.getElementById("flaglotes").checked) var flaglotes = "S"; else var flaglotes = "N";
	if (document.getElementById("flagkit").checked) var flagkit = "S"; else var flagkit = "N";
	if (document.getElementById("flagitem").checked) var flagitem = "S"; else var flagitem = "N";
	if (document.getElementById("flagimpuesto").checked) var flagimpuesto = "S"; else var flagimpuesto = "N";
	if (document.getElementById("flagauto").checked) var flagauto = "S"; else var flagauto = "N";
	if (document.getElementById("flagdisponible").checked) var flagdisponible = "S"; else var flagdisponible = "N";
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (descripcion == "" || unidad == "" || codsubfamilia == "" || codinterno == "" || procedencia == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=ITEMS&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&titem="+titem+"&unidad="+unidad+"&unidad_compra="+unidad_compra+"&unidad_embalaje="+unidad_embalaje+"&codlinea="+codlinea+"&codfamilia="+codfamilia+"&codsubfamilia="+codsubfamilia+"&codinterno="+codinterno+"&imagen="+imagen+"&marca="+marca+"&color="+color+"&procedencia="+procedencia+"&codbarra="+codbarra+"&stockmin="+stockmin+"&stockmax="+stockmax+"&ctainventario="+ctainventario+"&ctagasto="+ctagasto+"&ctaventa="+ctaventa+"&partida="+partida+"&flaglotes="+flaglotes+"&flagkit="+flagkit+"&flagitem="+flagitem+"&flagimpuesto="+flagimpuesto+"&flagauto="+flagauto+"&flagdisponible="+flagdisponible+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else cargarPagina(form, "items.php?limit=0");
			}
		}
	}
	return false;
}

//	LINEAS
function verificarLineas(form, accion) {
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (descripcion == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=LINEAS&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else cargarPagina(form, "lineas.php");
			}
		}
	}
	return false;
}

//	FAMILIAS
function verificarFamilias(form, accion) {
	var codlinea = document.getElementById("codlinea").value; codlinea = codlinea.trim();
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	var ctainventario = document.getElementById("ctainventario").value; ctainventario = ctainventario.trim();
	var ctagasto = document.getElementById("ctagasto").value; ctagasto = ctagasto.trim();
	var ctaventas = document.getElementById("ctaventas").value; ctaventas = ctaventas.trim();
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (codlinea == "" || descripcion == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=FAMILIAS&accion="+accion+"&codlinea="+codlinea+"&codigo="+codigo+"&descripcion="+descripcion+"&ctainventario="+ctainventario+"&ctagasto="+ctagasto+"&ctaventas="+ctaventas+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else cargarPagina(form, "familias.php");
			}
		}
	}
	return false;
}

//	SUBFAMILIAS
function verificarSubFamilias(form, accion) {
	var codlinea = document.getElementById("codlinea").value; codlinea = codlinea.trim();
	var codfamilia = document.getElementById("codfamilia").value; codfamilia = codfamilia.trim();
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (codlinea == "" || codfamilia == "" || descripcion == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=SUBFAMILIAS&accion="+accion+"&codlinea="+codlinea+"&codfamilia="+codfamilia+"&codigo="+codigo+"&descripcion="+descripcion+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else cargarPagina(form, "subfamilias.php");
			}
		}
	}
	return false;
}

//	PROCEDENCIAS
function verificarProcedencias(form, accion) {
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (codigo == "" || descripcion == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PROCEDENCIAS&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else cargarPagina(form, "procedencias.php");
			}
		}
	}
	return false;
}

//	MARCAS
function verificarMarcas(form, accion) {
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (descripcion == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=MARCAS&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else cargarPagina(form, "marcas.php");
			}
		}
	}
	return false;
}

//	REQUERIMIENTOS
function verificarRequerimiento(form, accion) {
	var codigo = document.getElementById("codigo").value;
	var organismo = document.getElementById("organismo").value;
	var dependencia = document.getElementById("dependencia").value;
	var estado = document.getElementById("estado").value;
	var prioridad = document.getElementById("prioridad").value;
	var clasificacion = document.getElementById("clasificacion").value;
	var ccosto = document.getElementById("ccosto").value;
	var almacen = document.getElementById("almacen").value;
	var frequerida = document.getElementById("frequerida").value;
	var fpreparado = document.getElementById("fpreparado").value;
	var frevisado = document.getElementById("frevisado").value;
	var faprobado = document.getElementById("faprobado").value;
	var preparadopor = document.getElementById("preparadopor").value;
	var revisadopor = document.getElementById("revisadopor").value;
	var aprobadopor = document.getElementById("aprobadopor").value;
	var comentarios = document.getElementById("comentarios").value; comentarios = comentarios.trim();
	var razon = document.getElementById("razon").value; razon = razon.trim();
	if (document.getElementById("flagcompras").checked) var flagdirigido = "C"; else var flagdirigido = "A";
	
	if (prioridad == "" || clasificacion == "" || ccosto == "" || almacen == "" || frequerida == "" || preparadopor == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=REQUERIMIENTOS&accion="+accion+"&codigo="+codigo+"&organismo="+organismo+"&dependencia="+dependencia+"&estado="+estado+"&prioridad="+prioridad+"&clasificacion="+clasificacion+"&ccosto="+ccosto+"&almacen="+almacen+"&frequerida="+frequerida+"&fpreparado="+fpreparado+"&frevisado="+frevisado+"&faprobado="+faprobado+"&preparadopor="+preparadopor+"&revisadopor="+revisadopor+"&aprobadopor="+aprobadopor+"&comentarios="+comentarios+"&razon="+razon+"&flagdirigido="+flagdirigido);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var datos = resp.split("|");
				if (datos[0] != 0) alert(datos[0]);
				else if (confirm("¿Desea agregar el detalle?")) {
					var opciones = document.getElementById("opciones").value;
					if (opciones == "") var regresar = "framemain"; else var regresar = "lg_requerimientos";
					var registro = organismo + "|" + datos[1];
					cargarPagina(form, "lg_requerimientos_editar.php?limit=0&registro="+registro+"&bt=EDITAR&regresar="+regresar);
				}
				else cargarPagina(form, "lg_requerimientos.php?limit=0");
			}
		}
	}
	return false;
}
function setEstadoRequerimiento(form, accion) {
	var codrequerimiento = document.getElementById("codigo").value;
	var organismo = document.getElementById("organismo").value;
	var dependencia = document.getElementById("dependencia").value;
	var razon = document.getElementById("razon").value; razon = razon.trim();
	
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_ajax_lg.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("modulo=REQUERIMIENTOS&accion="+accion+"&codrequerimiento="+codrequerimiento+"&organismo="+organismo+"&dependencia="+dependencia+"&razon="+razon);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else cargarPagina(form, "lg_requerimientos.php?limit=0");
		}
	}
}
function verificarRequerimientoDetalle(form, seleccion, codrequerimiento) {
	var cant = document.getElementById("cant").value;
	var ccosto = document.getElementById("codccosto").value;
	if (document.getElementById("flagexonerado").checked) var flagexonerado = "S"; else var flagexonerado = "N";
	
	if (cant == 0 || ccosto == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=REQUERIMIENTOS-DETALLE&accion=UPDATE&codrequerimiento="+codrequerimiento+"&seleccion="+seleccion+"&ccosto="+ccosto+"&cant="+cant+"&flagexonerado="+flagexonerado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else {
					form.submit();
					window.close();
				}
			}
		}
	}
	return false;
}
function editarRequerimientoDetalle(form, seleccion) {
	var codrequerimiento = document.getElementById("codrequerimiento").value;
	var tiporeq = document.getElementById("tiporeq").value;
	
	if (seleccion == "") alert("¡DEBE SELECCIONAR UN REGISTRO!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=REQUERIMIENTOS-DETALLE&accion=EDITAR&seleccion="+seleccion+"&codrequerimiento="+codrequerimiento+"&tiporeq="+tiporeq);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var datos = ajax.responseText.split("|.|");
				if (datos[0].trim() != "") alert ("¡"+datos[0]+"!");
				else {
					document.getElementById("accion").value = "UPDATE";
					document.getElementById("secuencia").value = datos[1];
					document.getElementById("codigo").value = datos[2];
					document.getElementById("descripcion").value = datos[3];
					document.getElementById("cant").value = datos[4];
					document.getElementById("ccosto").value = datos[5];
					document.getElementById("estado").value = datos[6];
					document.getElementById("comentario").value = datos[7];
					document.getElementById("documento").value = datos[8];
					if (datos[9] == "S") document.getElementById("flagexonerado").checked = true;
					else document.getElementById("flagexonerado").checked = false;
					document.getElementById("btExaminar").disabled = true;
					document.getElementById("btEditar").disabled = true;
					document.getElementById("btBorrar").disabled = true;
				}
			}
		}
	}
}
function borrarRequerimientoDetalle(form, seleccion) {
	var codrequerimiento = document.getElementById("codrequerimiento").value;
	
	if (seleccion == "") alert("¡DEBE SELECCIONAR UN REGISTRO!");
	else {
		if (confirm("¿REALMENTE DESEA ELIMINAR ESTE REGISTRO?")) {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax_lg.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=REQUERIMIENTOS-DETALLE&accion=DELETE&seleccion="+seleccion+"&codrequerimiento="+codrequerimiento);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var datos = ajax.responseText;
					if (datos.trim() != "") alert ("¡"+datos+"!");
					else cargarPagina(form, "lg_requerimientos_detalle.php");
				}
			}
		}
	}
}
function insertarCantidadRequerimiento(form) {
	var codrequerimiento = document.getElementById("codrequerimiento").value;
	var organismo = document.getElementById("organismo").value;
	var dependencia = document.getElementById("dependencia").value;
	var flagdirigido = document.getElementById("flagdirigido").value;
	var ccostoreq = document.getElementById("ccostoreq").value;
	var edoreq = document.getElementById("edoreq").value;
	var tiporeq = document.getElementById("tiporeq").value;
	
	var limit = document.getElementById("limit").value;
	var cod = document.getElementById("cod").value;
	var nom = document.getElementById("nom").value;
	
	for(i=0; n=form.elements[i]; i++) {
		if (n.type=="text" && n.value != "" && n.value != 0 && !isNaN(n.value)) {
			idunidad = "unidad:" + n.id;
			iddescripcion = "descripcion:" + n.id;
			var codigo = n.id;
			var codunidad = document.getElementById(idunidad).value;
			var descripcion = document.getElementById(iddescripcion).value;
			
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax_lg.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=REQUERIMIENTOS-DETALLE&accion=INSERTAR&codrequerimiento="+codrequerimiento+"&organismo="+organismo+"&dependencia="+dependencia+"&flagdirigido="+flagdirigido+"&ccosto="+ccostoreq+"&estado="+edoreq+"&tiporeq="+tiporeq+"&cant="+n.value+"&codunidad="+codunidad+"&descripcion="+descripcion+"&codigo="+codigo);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var datos = ajax.responseText;
					if (datos.trim() != "") alert ("¡"+datos+"!");
					else {
						document.getElementById(codigo).value = "";
						form.submit();						
						document.getElementById("frmentrada").submit();
					}
				}
			}
		}
	}
	return false;
}


//	FORMAS DE PAGO
function verificarFormaPago(form, accion) {
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("flagcredito").checked) var flagcredito = "S"; else var flagcredito = "N";
	var dias = document.getElementById("dias").value; dias = dias.trim();
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (descripcion == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else if (flagcredito == "S" && (dias == "" || isNaN(dias) || dias == 0)) alert("¡DEBE INGRESAR LOS DIAS DE VENCIMIENTO!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=FORMAS-PAGO&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&flagcredito="+flagcredito+"&dias="+dias+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else cargarPagina(form, "formas_pago.php");
			}
		}
	}
	return false;
}

//	
function setDirigidoA(clasificacion) {
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_lg.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=setDirigidoA&clasificacion="+clasificacion);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			if (resp.trim() != "A" && resp.trim() != "C") alert(resp);
			else if (resp.trim() == "A") document.getElementById("flagalmacen").checked = true;
			else if (resp.trim() == "C") document.getElementById("flagcompras").checked = true;
		}
	}
}

//	
function cerrarRequerimientoDetalle(form) {
	var registro = document.getElementById("registro").value;
	
	if (registro == "") alert("¡Debe seleccionar un detalle!");
	else if (confirm("¿Seguro de cerrar este detalle?")) {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=REQUERIMIENTOS-DETALLE&accion=CERRAR-DETALLE&registro="+registro);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "0") alert(resp);
				else cargarPagina(form, "lg_requerimientos_lista_detalle.php?limit=0");
			}
		}
	}
}

//	funcion para mostrar los detalles de los requerimientos en despacho de almacen
function mostrarRequerimientoDetalles() {
	var registroreq = document.getElementById("registroreq").value;
	
	if (registroreq != "") {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=mostrarRequerimientoDetalles&registroreq="+registroreq);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				document.getElementById("listaDet").innerHTML = resp;
				
			}
		}
	}
	document.getElementById("rows_det").innerHTML = document.getElementById("rows_detalle").value;
}

//	funcion para realizar la transaccion de despacho en almacen
function despachoAlmacen(form) {
	var detalles = "";
	
	for(i=0; n=form.elements[i]; i++) {
		if (n.type=="checkbox" && n.checked) detalles += n.value + ";";
	}
	
	if (detalles == "") alert("¡Debe seleccionar un detalle para despacharlo!");
	else window.open("transacciones_despachar.php?detalles="+detalles, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=1000, left=0, top=0, resizable=yes') 
}

//	funcion para obtener las filas seleccionadas de invitar/cotizar proveedores y despues cargar ajax...
function invitarProveedor(form) {
	var detalles = "";
	
	for(i=0; n=form.elements[i]; i++) {
		if (n.type=="checkbox" && n.checked) detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (detalles == "") alert("¡Debe seleccionar por lo menos un detalle!");
	else window.open("invitar_proveedores_proceso.php?detalles="+detalles, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=1000, left=0, top=0, resizable=yes') 
}

// funcion para insertar un proveedor  en invitar proveedores proceso
function insertarProveedorCotizacion(codproveedor) {
	var form = opener.document.getElementById("frmentrada");
	var detalles = opener.document.getElementById("detalles").value;
	var numero = opener.document.getElementById("numero").value;
	
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_lg.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=insertarProveedorCotizacion&detalles="+detalles+"&codproveedor="+codproveedor+"&numero="+numero);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var datos = resp.split("||");
			if (datos[0].trim() != "") alert(resp);
			else {
				opener.document.getElementById("listaProveedores").innerHTML = datos[1];
				window.close();
			}
		}
	}
}