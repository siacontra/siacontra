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

//	FUNCION PARA CARGAR UNA NUEVA VENTANA
function cargarVentana(form, pagina, param) {
	window.open(pagina, "wPrincipal", "toolbar=no, menubar=no, location=no, scrollbars=yes, "+param);
}

//	FUNCION PARA CARGAR UNA NUEVA VENTANA
function cargarPDF(form, pag, param) {
	pagina=pag+"?persona="+form.persona.value;
	window.open(pagina, "", "toolbar=no, menubar=no, location=no, scrollbars=yes, "+param);
}

//	FUNCION PARA CARGAR UNA NUEVA PAGINA 
function cargarOpcion(form, pagina, target, param) {
	var codigo=form.registro.value;
	if (codigo=="") msjError(1000);
	else {
		if (target=="SELF") cargarPagina(form, pagina);
		else { pagina=pagina+"?limit=0&accion=VER&registro="+codigo; cargarVentana(form, pagina, param); }
	}
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
function totalLista(rows) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
}

//	FUNCION PARA EJECUTAR OPCION DE MENU DE REGISTROS
function opcionRegistro(form, registro, modulo, accion) {
	if (registro == "") alert("¡DEBE SELECCIONAR UN REGISTRO!");
	else if (accion == "ELIMINAR" && confirm("¿REALMENTE DESEA ELIMINAR ESTE REGISTRO?")) {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_sia.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo="+modulo+"&accion="+accion+"&registro="+registro);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert (resp);
				else cargarPagina(form, form.action);
			}
		}
	}
}

//	FUNCION PARA SELECCIONAR UN REGISTRO DE UNA LISTA Y COLOCARLO EN OTRA VENTANA
function selGrupoCentroCosto(codgrupo, nomgrupo, nomsubgrupo) {
	var registro = document.getElementById("registro").value;
	opener.document.frmentrada.codgrupo_cc.value = codgrupo;
	opener.document.frmentrada.nomgrupo_cc.value = nomgrupo;
	opener.document.frmentrada.codsubgrupo_cc.value = registro;
	opener.document.frmentrada.nomsubgrupo_cc.value = nomsubgrupo;
	window.close();
}
function selListado(busqueda, cod, nom) {
	var registro=document.getElementById("registro").value;
	opener.document.getElementById(cod).value=registro;
	opener.document.getElementById(nom).value=busqueda;
	window.close();
}
function selListado2(idcod1, idnom1, valcod1, valnom1, idcod2, idnom2, valcod2, valnom2) {
	opener.document.getElementById(idcod1).value=valcod1;
	opener.document.getElementById(idnom1).value=valnom1;
	opener.document.getElementById(idcod2).value=valcod2;
	opener.document.getElementById(idnom2).value=valnom2;
	window.close();
}
function selPartidaCuenta(busqueda, cod, nom, codcuenta, nomcuenta) {
	var registro=document.getElementById("registro").value;
	opener.document.getElementById(cod).value=registro;
	opener.document.getElementById(nom).value=busqueda;
	opener.document.getElementById("codcuenta").value=codcuenta;
	opener.document.getElementById("nomcuenta").value=nomcuenta;
	window.close();
}
function selPartidaCuenta2(busqueda, cod, nom, cod2, nom2, codcuenta, nomcuenta) {
	var registro=document.getElementById("registro").value;
	opener.document.getElementById(cod).value=registro;
	opener.document.getElementById(nom).value=busqueda;
	opener.document.getElementById(cod2).value=codcuenta;
	opener.document.getElementById(nom2).value=nomcuenta;
	window.close();
}
//	----------------------------------

//	------------------------------------------------------------------------------------------------
//	FORMULARIOS
//	------------------------------------------------------------------------------------------------

//	PLAN DE CUENTAS
function verificarPlanCuentas(form, accion) {
	var nivel=document.getElementById("nivel").value;
	var codigo=document.getElementById("codigo").value; codigo=codigo.trim();
	var descripcion=document.getElementById("descripcion").value; descripcion=descripcion.trim();
	var tipo_cuenta=document.getElementById("tipo_cuenta").value;
	if (document.getElementById("deudora").checked) var naturaleza = "D"; else var naturaleza = "A";
	if (document.getElementById("principal").checked) var tipo = "P"; else var tipo = "A";
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	var grupo = codigo.substring(0, 1);
	var subgrupo = codigo.substring(1, 2);
	var rubro = codigo.substring(2, 3);
	var cuenta = codigo.substring(3, 5);
	var subcuenta1 = codigo.substring(5, 7);
	var subcuenta2 = codigo.substring(7, 9);
	var subcuenta3 = codigo.substring(9, 12);
	
	if (nivel == "" || codigo == "" || descripcion == "" || tipo_cuenta == "") alert("¡DEBE INGRESAR LOS CAMPOS OBLIGATORIOS!");
	else if (nivel == 1 && codigo.length != 1) alert("¡CODIGO DE CUENTA INCORRECTO!"); 
	else if (nivel == 2 && codigo.length != 2) alert("¡CODIGO DE CUENTA INCORRECTO!"); 
	else if (nivel == 3 && codigo.length != 3) alert("¡CODIGO DE CUENTA INCORRECTO!"); 
	else if (nivel == 4 && codigo.length != 5) alert("¡CODIGO DE CUENTA INCORRECTO!"); 
	else if (nivel == 5 && codigo.length != 7) alert("¡CODIGO DE CUENTA INCORRECTO!"); 
	else if (nivel == 6 && codigo.length != 9) alert("¡CODIGO DE CUENTA INCORRECTO!"); 
	else if (nivel == 7 && codigo.length != 12) alert("¡CODIGO DE CUENTA INCORRECTO!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_sia.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PLAN-CUENTAS&accion="+accion+"&nivel="+nivel+"&codigo="+codigo+"&descripcion="+descripcion+"&tipo_cuenta="+tipo_cuenta+"&naturaleza="+naturaleza+"&tipo="+tipo+"&status="+status+"&grupo="+grupo+"&subgrupo="+subgrupo+"&rubro="+rubro+"&cuenta="+cuenta+"&subcuenta1="+subcuenta1+"&subcuenta2="+subcuenta2+"&subcuenta3="+subcuenta3);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var datos = resp.split("|");
				if (datos[0] != 0) alert (datos[0]);
				else cargarPagina(form, "plan_cuentas.php?limit=0");
			}
		}
	}
	return false;
}
function eliminarPlanCuentaPartida(form) {
	var idpartida = document.getElementById("seleccion").value;
	var idcuenta = document.getElementById("idcuenta").value;
	if (idpartida == "") alert("¡DEBE SELECCIONAR UNA PARTIDA!");
	else if (confirm("¿REALMENTE DESEA ELIMINAR ESTA PARTIDA?")) {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_sia.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PLAN-CUENTAS&accion=ELIMINAR-PARTIDA&idcuenta="+idcuenta+"&idpartida="+idpartida);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert (resp);
				else cargarPagina(form, form.action);
			}
		}
	}
}
function insertarPartidaCuenta(idpartida) {
	var idcuenta = document.getElementById("idcuenta").value;
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_ajax_sia.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("modulo=PLAN-CUENTAS&accion=INSERTAR-PARTIDA&idcuenta="+idcuenta+"&idpartida="+idpartida);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			if (resp != 0) alert (resp);
			else {
				opener.document.frmentrada.submit();
				window.close();
			}
		}
	}
}
//	----------------------------------

//	GRUPOS DE CENTROS DE COSTOS
function verificarGrupoCentroCosto(form, accion) {
	var codigo=document.getElementById("codigo").value; codigo=codigo.trim();
	var descripcion=document.getElementById("descripcion").value; descripcion=descripcion.trim();
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	if (codigo == "" || descripcion == "") alert("¡DEBE INGRESAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_sia.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=GRUPOS-CENTROS-COSTOS&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert (resp);
				else if (accion != "GUARDAR") cargarPagina(form, "grupos_centros_costos.php?limit=0");
				else cargarPagina(form, "grupos_centros_costos_editar.php?registro="+codigo);
			}
		}
	}
	return false;
}

function verificarSubGrupoCentroCosto(form, accion) {
	var codgrupo=document.getElementById("registro").value;
	var codsubgrupo=document.getElementById("codsubgrupo").value; codsubgrupo=codsubgrupo.trim();
	var nomsubgrupo=document.getElementById("nomsubgrupo").value; nomsubgrupo=nomsubgrupo.trim();
	var edosubgrupo=document.getElementById("edosubgrupo").value;
	
	if (codsubgrupo == "" || nomsubgrupo == "" || edosubgrupo == "") alert("¡DEBE INGRESAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_sia.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=SUBGRUPOS-CENTROS-COSTOS&accion="+accion+"&codgrupo="+codgrupo+"&codsubgrupo="+codsubgrupo+"&nomsubgrupo="+nomsubgrupo+"&edosubgrupo="+edosubgrupo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert (resp);
				else cargarPagina(form, "grupos_centros_costos_editar.php");
			}
		}
	}
	return false;
}

function editarSubGrupoCentroCosto(form, codsubgrupo) {
	var codgrupo=document.getElementById("registro").value;
	if (codsubgrupo == "") alert("¡DEBE SELECCIONAR UN REGISTRO!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_sia.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=SUBGRUPOS-CENTROS-COSTOS&accion=EDITAR&codgrupo="+codgrupo+"&codsubgrupo="+codsubgrupo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText.split("|.|");
				if (resp[0] != "") alert (resp);
				else {
					document.getElementById("accion").value = "ACTUALIZAR";
					document.getElementById("btEditar").disabled = true;
					document.getElementById("btBorrar").disabled = true;
					document.getElementById("codsubgrupo").disabled = true;
					document.getElementById("codsubgrupo").value = resp[1];
					document.getElementById("nomsubgrupo").value = resp[2];
					document.getElementById("edosubgrupo").value = resp[3];
				}
			}
		}
	}
	return false;
}

function borrarSubGrupoCentroCosto(form, codsubgrupo) {
	var codgrupo=document.getElementById("registro").value;
	if (codsubgrupo == "") alert("¡DEBE SELECCIONAR UN REGISTRO!");
	else if (confirm("¿DESEA REALMENTE ELIMINAR ESTE REGISTRO?")) {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_sia.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=SUBGRUPOS-CENTROS-COSTOS&accion=BORRAR&codgrupo="+codgrupo+"&codsubgrupo="+codsubgrupo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert (resp);
				else cargarPagina(form, "grupos_centros_costos_editar.php");
			}
		}
	}
	return false;
}
//	----------------------------------

//	CENTROS DE COSTOS
function verificarCentrosCostos(form, accion) {
	var codigo=document.getElementById("codigo").value; codigo=codigo.trim();
	var descripcion=document.getElementById("descripcion").value; descripcion=descripcion.trim();
	var dependencia=document.getElementById("dependencia").value;
	var codempleado=document.getElementById("codempleado").value;
	var tipo_ccosto=document.getElementById("tipo_ccosto").value;
	var codgrupo_cc=document.getElementById("codgrupo_cc").value;
	var codsubgrupo_cc=document.getElementById("codsubgrupo_cc").value;
	if (document.getElementById("flagadministrativo").checked) var flagadministrativo = "S"; else var flagadministrativo = "N";
	if (document.getElementById("flagventas").checked) var flagventas = "S"; else var flagventas = "N";
	if (document.getElementById("flagfinanciero").checked) var flagfinanciero = "S"; else var flagfinanciero = "N";
	if (document.getElementById("flagproduccion").checked) var flagproduccion = "S"; else var flagproduccion = "N";
	if (document.getElementById("flagcentroingreso").checked) var flagcentroingreso = "S"; else var flagcentroingreso = "N";
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	if (dependencia == "" || codigo == "" || descripcion == "" || codempleado == "" || tipo_ccosto == "" || codgrupo_cc == "" || codsubgrupo_cc == "") alert("¡DEBE INGRESAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_sia.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CENTROS-COSTOS&accion="+accion+"&dependencia="+dependencia+"&codigo="+codigo+"&descripcion="+descripcion+"&codempleado="+codempleado+"&tipo_ccosto="+tipo_ccosto+"&codgrupo_cc="+codgrupo_cc+"&status="+status+"&codsubgrupo_cc="+codsubgrupo_cc+"&flagadministrativo="+flagadministrativo+"&flagventas="+flagventas+"&flagfinanciero="+flagfinanciero+"&flagproduccion="+flagproduccion+"&flagcentroingreso="+flagcentroingreso);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert (resp);
				else cargarPagina(form, "centros_costos.php?limit=0");
			}
		}
	}
	return false;
}
//	----------------------------------

//	TIPOS DE VOUCHER
function verificarTipoVoucher(form, accion) {
	var codigo=document.getElementById("codigo").value;
	var descripcion=document.getElementById("descripcion").value; descripcion=descripcion.trim();
	if (document.getElementById("flagmanual").checked) var flagmanual = "S"; else var flagmanual = "N";
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	if (descripcion == "") alert("¡DEBE INGRESAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_sia.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPOS-VOUCHER&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&status="+status+"&flagmanual="+flagmanual);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert (resp);
				else cargarPagina(form, "tipos_voucher.php?limit=0");
			}
		}
	}
	return false;
}
//	----------------------------------

//	REGIMEN FISCAL
function verificarRegimenFiscal(form, accion) {
	var codigo=document.getElementById("codigo").value;
	var descripcion=document.getElementById("descripcion").value; descripcion=descripcion.trim();
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	if (codigo == "" || descripcion == "") alert("¡DEBE INGRESAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_sia.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=REGIMEN-FISCAL&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert (resp);
				else cargarPagina(form, "regimen_fiscal.php?limit=0");
			}
		}
	}
	return false;
}
//	----------------------------------

//	IMPUESTOS
function verificarImpuesto(form, accion) {
	var codigo=document.getElementById("codigo").value;
	var descripcion=document.getElementById("descripcion").value; descripcion=descripcion.trim();
	var regimen=document.getElementById("regimen").value;
	if (document.getElementById("positivo").checked) var signo = "P"; else var signo = "N";
	var provisionar=document.getElementById("provisionar").value;
	var imponible=document.getElementById("imponible").value;
	var codcuenta=document.getElementById("codcuenta").value;
	var porcentaje=document.getElementById("porcentaje").value; porcentaje=porcentaje.trim();
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	if (codigo == "" || descripcion == "" || provisionar == "" || imponible == "" || codcuenta == "" || porcentaje == "") alert("¡DEBE INGRESAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_sia.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=IMPUESTOS&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&regimen="+regimen+"&signo="+signo+"&provisionar="+provisionar+"&imponible="+imponible+"&codcuenta="+codcuenta+"&porcentaje="+porcentaje+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert (resp);
				else cargarPagina(form, "impuestos.php?limit=0");
			}
		}
	}
	return false;
}
//	----------------------------------

//	TIPOS DE DOCUMENTOS CTA X PAGAR
function verificarTipoDocumentoCXP(form, accion) {
	var codigo=document.getElementById("codigo").value;
	var descripcion=document.getElementById("descripcion").value; descripcion=descripcion.trim();
	var clasificacion=document.getElementById("clasificacion").value;
	var regimen=document.getElementById("regimen").value;
	var voucher=document.getElementById("voucher").value;
	var codcuentaprov=document.getElementById("codcuentaprov").value;
	if (document.getElementById("flagprovision").checked) var flagprovision = "S"; else flagprovision = "N";
	var codcuentaade=document.getElementById("codcuentaade").value;
	if (document.getElementById("flagadelanto").checked) var flagadelanto = "S"; else flagadelanto = "N";
	if (document.getElementById("flagfiscal").checked) var flagfiscal = "S"; else flagfiscal = "N";
	var codfiscal=document.getElementById("codfiscal").value; codfiscal=codfiscal.trim();
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	if (codigo == "" || descripcion == "" || clasificacion == "" || regimen == "" || voucher == "") alert("¡DEBE INGRESAR LOS CAMPOS OBLIGATORIOS!");
	else if (flagprovision == "S" && codcuentaprov == "") alert("¡DEBE SELECCIONAR UNA CUENTA PROVISION!");
	else if (flagadelanto == "S" && codcuentaade == "") alert("¡DEBE SELECCIONAR UNA CUENTA ADELANTO!");	
	else if (flagfiscal == "S" && codfiscal == "") alert("¡DEBE INGRESAR EL CODIGO FISCAL!");	
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_sia.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPOS-DOCUMENTOS-CXP&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&regimen="+regimen+"&voucher="+voucher+"&codcuentaprov="+codcuentaprov+"&flagprovision="+flagprovision+"&codcuentaade="+codcuentaade+"&flagadelanto="+flagadelanto+"&status="+status+"&clasificacion="+clasificacion+"&flagfiscal="+flagfiscal+"&codfiscal="+codfiscal);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert (resp);
				else cargarPagina(form, "tipo_doc_cxp.php?limit=0");
			}
		}
	}
	return false;
}

function enabledCtaContable(chk, boton, campo) {
	document.getElementById(boton).disabled = !chk;
	document.getElementById(campo).value = "";
}
//	----------------------------------

//	TIPOS DE SERVICIO
function verificarTipoServicio(form, accion) {
	var codigo=document.getElementById("codigo").value;
	var descripcion=document.getElementById("descripcion").value; descripcion=descripcion.trim();
	var regimen=document.getElementById("regimen").value;
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	if (codigo == "" || descripcion == "" || regimen == "") alert("¡DEBE INGRESAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_sia.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPOS-SERVICIO&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&regimen="+regimen+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText.split("|");
				if (resp[0] != 0) alert (resp[0]);
				else if (accion == "GUARDAR") cargarPagina(form, "tipos_servicio_editar.php?registro="+resp[1]);
				else cargarPagina(form, "tipos_servicio.php?limit=0");
			}
		}
	}
	return false;
}
function verificarTipoServicioImpuesto(form, accion) {
	var codtiposervicio=document.getElementById("codigo").value;
	var impuesto=document.getElementById("impuesto").value;
	
	if (impuesto == "") alert("¡DEBE INGRESAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_sia.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPOS-SERVICIO-IMPUESTO&accion="+accion+"&codtiposervicio="+codtiposervicio+"&impuesto="+impuesto);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText.split("|");
				if (resp[0] != 0) alert (resp[0]);
				else cargarPagina(form, "tipos_servicio_editar.php?registro="+resp[1]);
			}
		}
	}
	return false;
}
function borrarTipoServicioImpuesto(form, accion) {
	var codtiposervicio=document.getElementById("codigo").value;
	var impuesto=document.getElementById("elemento").value;
	
	if (impuesto == "") alert("¡DEBE SELECCIONAR UN REGISTRO!");
	else if (confirm("¿DESEA ELIMINAR ESTE REGISTRO?")) {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_sia.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPOS-SERVICIO-IMPUESTO&accion=BORRAR&codtiposervicio="+codtiposervicio+"&impuesto="+impuesto);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText.split("|");
				if (resp[0] != 0) alert (resp[0]);
				else cargarPagina(form, "tipos_servicio_editar.php?registro="+resp[1]);
			}
		}
	}
	return false;
}
//	----------------------------------

function selPartidaConceptoPerfil(busqueda, codcuenta, tiposaldo) {
	var cod = document.getElementById("cod").value;	
	var registro = document.getElementById("registro").value;	
	opener.document.getElementById("partida_"+cod).value = registro;	
	if (tiposaldo == "D") opener.document.getElementById("debe_"+cod).value = codcuenta;
	else opener.document.getElementById("haber_"+cod).value = codcuenta;	
	window.close();
}
//	----------------------------------

function selCuentaContablePerfil(codcuenta, columna) {
	var cod = document.getElementById("cod").value;	
	if (columna == "debe") opener.document.getElementById("debe_"+cod).value = codcuenta;
	else if (columna == "haber") opener.document.getElementById("haber_"+cod).value = codcuenta;
	window.close();
}
//	----------------------------------

function listadoCuentaContable(cod, seldetalle) {
	opener.document.getElementById("cuenta"+seldetalle).value = cod;
	window.close();
}