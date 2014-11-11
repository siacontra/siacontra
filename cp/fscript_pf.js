//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalRegistrosActuacion(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML = "Registros: "+rows;
	var btNuevo = document.getElementById("btNuevo");
	var btEditar = document.getElementById("btEditar");
	var btVer = document.getElementById("btVer");
	var btAprobar = document.getElementById("btAprobar");
	var btAnular = document.getElementById("btAnular");
	var btRevisar = document.getElementById("btRevisar");
	
	if (insert == "N") btNuevo.disabled = true;
	if (update == "N" || !rows) {
		btEditar.disabled = true;
		btAprobar.disabled = true;
		btAnular.disabled = true;
		btRevisar.disabled = true;
		if (!rows) btVer.disabled = true;
	}
}
function totalRegistrosActuacionEjecucion(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML = "Registros: "+rows;
	var btTerminar = document.getElementById("btTerminar");
	
	if (update == "N" || !rows) {
		btTerminar.disabled = true;
	}
}
function totalRegistrosActuacionListar(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML = "Registros: "+rows;
	var btGenerar = document.getElementById("btGenerar");
	
	if (update == "N" || !rows) {
		btGenerar.disabled = true;
	}
}

//	--------------------------------------------------------------------------------
//	--------------------------------------------------------------------------------
//	--------------------------------------------------------------------------------

//	ORGANISMO EXTERNOS
function verificarOrganismoExterno(form, accion) {
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	//var codpersona = new String (form.codpersona.value); codpersona=codpersona.trim(); form.codpersona.value=codpersona;
	var descripcionc = new String (form.descripcionc.value); descripcionc=descripcionc.trim(); form.descripcionc.value=descripcionc;
	var rep = new String (form.rep.value); rep=rep.trim(); form.rep.value=rep;
	var docr = new String (form.docr.value); docr=docr.trim(); form.docr.value=docr;
	var cargo = new String (form.cargo.value); cargo=cargo.trim(); form.cargo.value=cargo;
	var www = new String (form.www.value); www=www.trim(); form.www.value=www;
	var docf = new String (form.docf.value); docf=docf.trim(); form.docf.value=docf;
	var fecha = new String (form.fecha.value); fecha=fecha.trim(); form.fecha.value=fecha;
	var dir = new String (form.dir.value); dir=dir.trim(); form.dir.value=dir;
	var tel1 = new String (form.tel1.value); tel1=tel1.trim(); form.tel1.value=tel1;
	var tel2 = new String (form.tel2.value); tel2=tel2.trim(); form.tel2.value=tel2;
	var tel3 = new String (form.tel3.value); tel3=tel3.trim(); form.tel3.value=tel3;
	var fax1 = new String (form.fax1.value); fax1=fax1.trim(); form.fax1.value=fax1;
	var fax2 = new String (form.fax2.value); fax2=fax2.trim(); form.fax2.value=fax2;
	var logo = new String (form.logo.value); logo=logo.trim(); form.logo.value=logo;
	var nreg = new String (form.nreg.value); nreg=nreg.trim(); form.nreg.value=nreg;
	var treg = new String (form.treg.value); treg=treg.trim(); form.treg.value=treg;
	if (form.orgsocial[0].checked) var orgsocial=form.orgsocial[0].value; else var orgsocial=form.orgsocial[1].value;
	if (form.control[0].checked) var control=form.control[0].value; else var control=form.control[1].value;
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion == "" || descripcionc == "" || form.ciudad.value == "") alert("¡Debe ingresar los campos obligatorios!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=ORGANISMOS-EXTERNOS&accion="+accion+"&codigo="+form.codigo.value+"&descripcion="+descripcion+"&descripcionc="+descripcionc+"&rep="+rep+"&docr="+docr+"&www="+www+"&docf="+docf+"&fecha="+fecha+"&dir="+dir+"&ciudad="+form.ciudad.value+"&tel1="+tel1+"&tel2="+tel2+"&tel3="+tel3+"&fax1="+fax1+"&fax2="+fax2+"&logo="+logo+"&status="+status+"&nreg="+nreg+"&treg="+treg+"&control="+control+"&cargo="+cargo+"&orgsocial="+orgsocial);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert (resp);
				else form.submit();
			}
		}
	}
	return false;
}

//	DEPENDENCIAS EXTERNAS
function verificarDependenciaExterna(form, accion) {
	var codigo = document.getElementById("codigo").value.trim();
	var descripcion = document.getElementById("descripcion").value.trim();
	var representante = document.getElementById("representante").value.trim();
	var cargo = document.getElementById("cargo").value.trim();
	var codorganismo = document.getElementById("codorganismo").value.trim();
	var tel1 = document.getElementById("tel1").value.trim();
	var tel2 = document.getElementById("tel2").value.trim();
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion == "" || codorganismo == "") alert("¡Debe ingresar los campos obligatorios!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=DEPENDENCIAS-EXTERNAS&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&codorganismo="+codorganismo+"&representante="+representante+"&tel1="+tel1+"&tel2="+tel2+"&status="+status+"&cargo="+cargo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp);
				else form.submit();
			}
		}
	}
	return false;
}

//	PROCESOS
function verificarProcesos(form, accion) {
	var codigo = document.getElementById("codigo").value.trim();
	var descripcion = document.getElementById("descripcion").value.trim();
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion == "") alert("¡Debe ingresar los campos obligatorios!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PROCESOS&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp.trim());
				else form.submit();
			}
		}
	}
	return false;
}

//	FASES
function verificarFases(form, accion) {
	var codigo = document.getElementById("codigo").value.trim();
	var descripcion = document.getElementById("descripcion").value.trim();
	var proceso = document.getElementById("proceso").value.trim();
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion == "" || proceso == "") alert("¡Debe ingresar los campos obligatorios!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=FASES&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&proceso="+proceso+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp.trim());
				else form.submit();
			}
		}
	}
	return false;
}

//	ACTIVIDADES
function verificarActividades(form, accion) {
	var codigo = document.getElementById("codigo").value.trim();
	var descripcion = document.getElementById("descripcion").value.trim();
	var proceso = document.getElementById("proceso").value.trim();
	var fase = document.getElementById("fase").value.trim();
	var duracion = document.getElementById("duracion").value.trim();
	if (document.getElementById("flagauto").checked) var flagauto = "S"; else var flagauto = "N";
	if (document.getElementById("flagnoafecto").checked) var flagnoafecto = "S"; else var flagnoafecto = "N";
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion == "" || fase == "") alert("¡Debe ingresar los campos obligatorios!");
	else if (duracion == ""  || duracion == "0") alert("¡La duración es obligatoria!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=ACTIVIDADES&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&proceso="+proceso+"&fase="+fase+"&duracion="+duracion+"&flagauto="+flagauto+"&flagnoafecto="+flagnoafecto+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp.trim());
				else form.submit();
			}
		}
	}
	return false;
}

//	TIPO DE ACTUACION FISCAL
function verificarTipoActuacionFiscal(form, accion) {
	var codigo = document.getElementById("codigo").value.trim();
	var descripcion = document.getElementById("descripcion").value.trim();
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion == "") alert("¡Debe ingresar los campos obligatorios!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPO-ACTUACION-FISCAL&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp.trim());
				else form.submit();
			}
		}
	}
	return false;
}

//	ACTUACIONES FISCALES
function verificarPlanificacion(form, accion) {
	var organismo = document.getElementById("organismo").value.trim();
	var dependencia = document.getElementById("dependencia").value.trim();
	var codigo = document.getElementById("codigo").value.trim();
	var fregistro = document.getElementById("fregistro").value.trim();
	var finicio = document.getElementById("finicio").value.trim();
	var ftermino = document.getElementById("ftermino").value.trim();
	var codccosto = document.getElementById("codccosto").value.trim();
	var tipo_actuacion = document.getElementById("tipo_actuacion").value.trim();
	var duracion = document.getElementById("duracion").value.trim();
	var proceso = document.getElementById("proceso").value.trim();
	var organismoext = document.getElementById("organismoext").value.trim();
	var dependenciaext = document.getElementById("dependenciaext").value.trim();
	var objetivo_general = document.getElementById("objetivo_general").value.trim();
	var alcance = document.getElementById("alcance").value.trim();
	var observaciones = document.getElementById("observaciones").value.trim();
	var estado = document.getElementById("estado").value.trim();
	
	//	auditores
	var detalles_auditores = "";
	var error_detalles_auditores = "";		
	var frmauditores = document.getElementById("frmauditores");
	for(i=0; n=frmauditores.elements[i]; i++) {
		if (n.name == "flagcoordinador") detalles_auditores += n.checked + "|";
		if (n.name == "persona") detalles_auditores += n.value + ";";
	}
	var len = detalles_auditores.length; len--;
	detalles_auditores = detalles_auditores.substr(0, len);
	
	//	actividades
	var detalles_actividades = "";
	var error_detalles_actividades = "";		
	var frmactividades = document.getElementById("frmactividades");
	for(i=0; n=frmactividades.elements[i]; i++) {
		if (n.name == "actividad") detalles_actividades += n.value + "|";
		if (n.name == "duracion") detalles_actividades += n.value + "|";
		if (n.name == "finicio") detalles_actividades += n.value + "|";
		if (n.name == "ftermino") detalles_actividades += n.value + "|";
		if (n.name == "prorroga") detalles_actividades += n.value + "|";
		if (n.name == "finicioreal") detalles_actividades += n.value + "|";
		if (n.name == "fterminoreal") detalles_actividades += n.value + ";";
	}
	var len = detalles_actividades.length; len--;
	detalles_actividades = detalles_actividades.substr(0, len);
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (organismo == "" || dependencia == "" || fregistro == "" || finicio == "" || ftermino == "" || codccosto == "" || tipo_actuacion == "" || proceso == "" || organismoext == "" || objetivo_general == "") alert("¡ERROR: Debe ingresar los campos obligatorios!");
	else if (!valFecha(finicio)) alert("¡ERROR: Formato de la fecha inicio incorrecta (dd-mm-aaaa)!");
	else if (detalles_auditores == "") alert("¡ERROR: Debe ingresar los auditores!");
	else if (detalles_actividades == "") alert("¡ERROR: Debe ingresar las actividades!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PLANIFICACION&accion="+accion+"&organismo="+organismo+"&dependencia="+dependencia+"&codigo="+codigo+"&fregistro="+fregistro+"&finicio="+finicio+"&ftermino="+ftermino+"&codccosto="+codccosto+"&tipo_actuacion="+tipo_actuacion+"&duracion="+duracion+"&proceso="+proceso+"&organismoext="+organismoext+"&dependenciaext="+dependenciaext+"&objetivo_general="+objetivo_general+"&alcance="+alcance+"&observaciones="+observaciones+"&estado="+estado+"&detalles_auditores="+detalles_auditores+"&detalles_actividades="+detalles_actividades);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp.trim());
				else if (accion == "APROBAR") {
					window.open("pf_pdf_actuacion_fiscal.php?codigo="+codigo+"&forganismo="+organismo+"&fregistrod="+fregistro+"&fregistroh="+fregistro+"&forganismoext="+organismoext+"&fdependenciaext="+dependenciaext+"&fedoreg="+estado+"&fproceso="+proceso, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes');
					form.submit();
				}
				else form.submit();
			}
		}
	}
	return false;
}
//	abrir lista
function abrirListaEmpleadosPlanificacion(frmdetalles) {
	var detalles = "";
	var error_detalles = "";
	
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "persona") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	window.open('listado_personas.php?ventana=insertarListaEmpleadoActuacion&filtrar=DEFAULT&limit=0&flagempleado=S&detalles='+detalles, 'listaEmpleados', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=1000, left=200, top=200, resizable=yes');
}
//	quitar de la lista
function quitarListaEmpleadoActuacion(codigo) {
	if (codigo.value == "") alert("¡ERROR: Debe seleccionar una linea!");
	else {
		var listaDetalles = document.getElementById("listaAuditores"); 
		var trDetalle = document.getElementById(codigo.value); 
		listaDetalles.removeChild(trDetalle);
		codigo.value = "";
	}
}
//	abrir lista
function abrirListaActividadesPlanificacion(frmdetalles) {
	var proceso = document.getElementById("proceso").value;
	var fecha_inicio = document.getElementById("finicio").value.trim();
	
	if (proceso == "") alert("¡ERROR: No ha seleccionado un proceso!");
	else if (fecha_inicio == "" || !valFecha(fecha_inicio)) alert("¡ERROR: Fecha de Inicio incorrecta");
	else {
		var detalles = "";
		var error_detalles = "";	
		for(i=0; n=frmdetalles.elements[i]; i++) {
			if (n.name == "actividad") detalles += n.value + ";";
		}
		var len = detalles.length; len--;
		detalles = detalles.substr(0, len);
		
		window.open('pf_lista_actividades.php?ventana=insertarListaActividadesActuacion&filtrar=DEFAULT&limit=0&detalles='+detalles+'&fecha_inicio='+fecha_inicio+'&proceso='+proceso, 'listaActividades', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=1000, left=200, top=200, resizable=yes');
	}
}
//	quitar de la lista
function quitarListaActividadesActuacion(codigo) {
	if (codigo.value == "") alert("¡ERROR: Debe seleccionar una linea!");
	else {
		var listaDetalles = document.getElementById("listaActividades"); 
		var trDetalle = document.getElementById(codigo.value); 
		listaDetalles.removeChild(trDetalle);
		codigo.value = "";
		
		setFechaActividades();
	}
}
//	insertar linea
function insertarListaActividadesActuacion(actividad, detalles, fecha_inicio) {
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_pf.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=insertarListaActividadesActuacion&actividad="+actividad+"&detalles="+detalles+"&fecha_inicio="+fecha_inicio+"&proceso="+opener.document.getElementById("proceso").value);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var datos = resp.split("||");
			if (datos[0].trim() != "") alert(resp);
			else {
				opener.document.getElementById("listaActividades").innerHTML = datos[1];
				opener.document.getElementById("duracion").value = datos[2];
				opener.document.getElementById("total_duracion").innerHTML = datos[2];
				opener.document.getElementById("ftermino").value = datos[3];
				setFechaActividadesOpener();
				window.close();
			}
		}
	}
}
//	insertar lista de actividades
function setListaActividades(proceso, fecha_inicio) {
	if (proceso == "" || fecha_inicio == "" || ! valFecha(fecha_inicio)) {
		document.getElementById("listaActividades").innerHTML = "";
	} else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=insertarListaActividadesActuacion&proceso="+proceso+"&fecha_inicio="+fecha_inicio);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var datos = resp.split("||");
				if (datos[0].trim() != "") alert(resp);
				else {
					document.getElementById("listaActividades").innerHTML = datos[1];
					document.getElementById("duracion").value = datos[2];
					document.getElementById("total_duracion").innerHTML = datos[2];
					document.getElementById("ftermino").value = datos[3];
				}
			}
		}
	}
}
//	actualizar las fechas de las actividades
function setFechaActividades() {
	var proceso = document.getElementById('proceso').value.trim();
	var fecha_inicio = document.getElementById('finicio').value.trim();
	
	if (proceso == "" || fecha_inicio == "" || ! valFecha(fecha_inicio)) {
		document.getElementById("listaActividades").innerHTML = "";
	} else {
		var detalles = "";
		var error_detalles = "";		
		var frmdetalles = document.getElementById("frmactividades");
		for(i=0; n=frmdetalles.elements[i]; i++) {
			if (n.name == "fase") detalles += n.value + "|";
			if (n.name == "nomfase") detalles += n.value + "|";
			if (n.name == "actividad") detalles += n.value + "|";
			if (n.name == "nomactividad") detalles += n.value + "|";
			if (n.name == "flagautoarchivo") detalles += n.value + "|";
			if (n.name == "flagnoafectoplan") detalles += n.value + "|";
			if (n.name == "duracion") {
				var duracion = new Number(setNumero(n.value));
				if (isNaN(duracion)) { error_detalles = "¡Debe ingresar un valor numérico en la duración!"; n.value = 1; n.focus(); }
				if (duracion <= 0) { error_detalles = "¡Debe ingresar un valor mayor a cero en la duración!"; n.value = 1; n.focus(); }
				detalles += n.value + ";";
			}
		}
		var len = detalles.length; len--;
		detalles = detalles.substr(0, len);
		
		if (error_detalles != "") alert(error_detalles);
		else {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_funciones_pf.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("accion=setFechaActividades&proceso="+proceso+"&fecha_inicio="+fecha_inicio+"&detalles="+detalles);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText;
					var datos = resp.split("||");
					if (datos[0] != "") document.getElementById("finicio").value = datos[0];
					document.getElementById("listaActividades").innerHTML = datos[1];
					document.getElementById("duracion").value = datos[2];
					document.getElementById("total_duracion").innerHTML = datos[2];
					document.getElementById("ftermino").value = datos[3];
				}
			}
		}
	}
}
//	actualizar las fechas de las actividades
function setFechaActividadesOpener() {
	var proceso = opener.document.getElementById('proceso').value.trim();
	var fecha_inicio = opener.document.getElementById('finicio').value.trim();
	
	if (proceso == "" || fecha_inicio == "" || ! valFecha(fecha_inicio)) {
		opener.document.getElementById("listaActividades").innerHTML = "";
	} else {
		var detalles = "";
		var error_detalles = "";		
		var frmdetalles = opener.document.getElementById("frmactividades");
		for(i=0; n=frmdetalles.elements[i]; i++) {
			if (n.name == "fase") detalles += n.value + "|";
			if (n.name == "nomfase") detalles += n.value + "|";
			if (n.name == "actividad") detalles += n.value + "|";
			if (n.name == "nomactividad") detalles += n.value + "|";
			if (n.name == "duracion") detalles += n.value + ";";
		}
		var len = detalles.length; len--;
		detalles = detalles.substr(0, len);
	
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=setFechaActividades&proceso="+proceso+"&fecha_inicio="+fecha_inicio+"&detalles="+detalles);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var datos = resp.split("||");
				opener.document.getElementById("listaActividades").innerHTML = datos[0];
				opener.document.getElementById("duracion").value = datos[1];
				opener.document.getElementById("total_duracion").innerHTML = datos[1];
				opener.document.getElementById("ftermino").value = datos[2];
			}
		}
	}
}
//	terminar actividad
function terminarActividad(form) {
	var actuacion = document.getElementById("actuacion").value.trim();
	var actividad = document.getElementById("actividad").value.trim();
	var secuencia = document.getElementById("secuencia").value.trim();
	var finicio_real = document.getElementById("finicio_real").value.trim();
	var ftermino_real = document.getElementById("ftermino_real").value.trim();
	var dias_real = document.getElementById("dias_real").value.trim();
	var fregistro_cierre = document.getElementById("fregistro_cierre").value.trim();
	var ftermino_cierre = document.getElementById("ftermino_cierre").value.trim();
	var duracion_cierre = document.getElementById("duracion_cierre").value.trim();
	var observaciones = document.getElementById("observaciones").value.trim();
	var detalles = "";
	var error_detalles = "";
	
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "documento") detalles += n.value + "|";
		if (n.name == "nrodocumento") detalles += n.value + "|";
		if (n.name == "fdocumento") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (ftermino_cierre == "") alert("¡ERROR: Debe ingresar los campos obligatorios!");
	else if (!valFecha(ftermino_cierre)) alert("¡ERROR: Formato de la fecha terminado incorrecta (dd-mm-aaaa)!");
	else if (formatFechaAMD(ftermino_cierre) > formatFechaAMD(fregistro_cierre)) alert("¡ERROR: La fecha de terminado es mayor a la fecha actual!");
	else if (formatFechaAMD(finicio_real) > formatFechaAMD(fregistro_cierre)) alert("¡ERROR: La fecha de inicio real es mayor a la fecha actual!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PLANIFICACION&accion=TERMINAR&actuacion="+actuacion+"&actividad="+actividad+"&secuencia="+secuencia+"&finicio_real="+finicio_real+"&ftermino_real="+ftermino_real+"&dias_real="+dias_real+"&fregistro_cierre="+fregistro_cierre+"&ftermino_cierre="+ftermino_cierre+"&duracion_cierre="+duracion_cierre+"&observaciones="+observaciones+"&detalles="+detalles);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp.trim());
				else form.submit();
			}
		}
	}
	return false;
}

//	PRORROGAS
function verificarProrroga(form, accion) {
	var organismo = document.getElementById("organismo").value.trim();
	var codigo = document.getElementById("codigo").value.trim();
	var fregistro = document.getElementById("fregistro").value.trim();
	var actuacion = document.getElementById("actuacion").value.trim();
	var motivo = document.getElementById("motivo").value.trim();
	var estado = document.getElementById("estado").value.trim();
	
	//	actividades
	var detalles_actividades = "";
	var error_detalles_actividades = "";
	var frmactividades = document.getElementById("frmactividades");
	var dias = new Number();
	for(i=0; n=frmactividades.elements[i]; i++) {
		if (n.name == "estado") {
			var estado_actividad = n.value;
			detalles_actividades += n.value + "|";
		}
		else if (n.name == "fase") detalles_actividades += n.value + "|";
		else if (n.name == "nomfase") detalles_actividades += n.value + "|";
		else if (n.name == "actividad") detalles_actividades += n.value + "|";
		else if (n.name == "nomactividad") detalles_actividades += n.value + "|";
		else if (n.name == "flagautoarchivo") detalles_actividades += n.value + "|";
		else if (n.name == "flagnoafectoplan") detalles_actividades += n.value + "|";
		else if (n.name == "duracion") detalles_actividades += n.value + "|";
		else if (n.name == "finicio") detalles_actividades += n.value + "|";
		else if (n.name == "ftermino") detalles_actividades += n.value + "|";
		else if (n.name == "acumulado") detalles_actividades += n.value + "|";
		else if (n.name == "prorroga") {
			var prorroga = new Number(setNumero(n.value));
			if (dias == 0 && prorroga != 0) dias = prorroga;
			if (isNaN(prorroga)) { error_detalles_actividades = "¡Debe ingresar un valor numérico en la duración de prórroga!"; n.value = 1; break; }
			else if (prorroga < 0) { error_detalles_actividades = "¡Debe ingresar un valor mayor a cero en la duración de prórroga!"; n.value = 1; break; }
			else if (prorroga == 0 && estado_actividad == "EJ") { error_detalles_actividades = "¡Debe ingresar un valor mayor a cero en la duración de prórroga!"; n.value = 1; break; } 
			else detalles_actividades += n.value + "|";
		}
		else if (n.name == "finicio_real") detalles_actividades += n.value + "|";
		else if (n.name == "ftermino_real") detalles_actividades += n.value + ";";
	}
	var len = detalles_actividades.length; len--;
	detalles_actividades = detalles_actividades.substr(0, len);
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (organismo == "" || fregistro == "" || actuacion == "" || motivo == "") alert("¡ERROR: Debe ingresar los campos obligatorios!");
	else if (detalles_actividades == "") alert("¡ERROR: Debe ingresar las actividades!");
	else if (error_detalles_actividades != "") alert(error_detalles_actividades);
	else if (dias == 0) alert("¡la cantidad de dias de la prórroga no puede ser cero!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PRORROGA&accion="+accion+"&organismo="+organismo+"&codigo="+codigo+"&fregistro="+fregistro+"&actuacion="+actuacion+"&motivo="+motivo+"&estado="+estado+"&detalles_actividades="+detalles_actividades);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp.trim());
				else form.submit();
			}
		}
	}
	return false;
}
//	seleccionar actividades de la actuacion
function setActuacionActividades(actuacion, nomactuacion) {
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_pf.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=setActuacionActividades&actuacion="+actuacion);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var datos = resp.split("||");
			if (datos[0].trim() != "") alert(datos[0]);
			else {
				opener.document.getElementById("actuacion").value = actuacion;
				opener.document.getElementById("nomactuacion").value = nomactuacion;
				opener.document.getElementById("listaActividades").innerHTML = datos[1];
				window.close();
			}
		}
	}
}
//	actualizar lista de actividades (prorroga)
function setProrrogaActividades() {
	var detalles = "";
	var error_detalles = "";		
	var frmdetalles = document.getElementById("frmactividades");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "estado") detalles += n.value + "|";
		else if (n.name == "fase") detalles += n.value + "|";
		else if (n.name == "nomfase") detalles += n.value + "|";
		else if (n.name == "actividad") detalles += n.value + "|";
		else if (n.name == "nomactividad") detalles += n.value + "|";
		else if (n.name == "flagautoarchivo") detalles += n.value + "|";
		else if (n.name == "flagnoafectoplan") detalles += n.value + "|";
		else if (n.name == "duracion") detalles += n.value + "|";
		else if (n.name == "finicio") detalles += n.value + "|";
		else if (n.name == "ftermino") detalles += n.value + "|";
		else if (n.name == "acumulado") detalles += n.value + "|";
		else if (n.name == "prorroga") {
			var prorroga = new Number(setNumero(n.value));
			if (isNaN(prorroga)) { error_detalles = "¡Debe ingresar un valor numérico en la duración de prórroga!"; n.value = 1; }
			else if (prorroga < 0) { error_detalles = "¡Debe ingresar un valor mayor a cero en la duración de prórroga!"; n.value = 1; }
			detalles += n.value + "|";
		}
		else if (n.name == "finicio_real") detalles += n.value + "|";
		else if (n.name == "ftermino_real") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (error_detalles != "") alert(error_detalles);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=setProrrogaActividades&detalles="+detalles);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				document.getElementById("listaActividades").innerHTML = "";
				document.getElementById("listaActividades").innerHTML = resp;
			}
		}
	}
	return false;
}

//	POTESTAD INVESTIGATIVA
function verificarPotestadInvestigativa(form, accion) {
	var organismo = document.getElementById("organismo").value.trim();
	var dependencia = document.getElementById("dependencia").value.trim();
	var actuacion = document.getElementById("actuacion").value.trim();
	var codigo = document.getElementById("codigo").value.trim();
	var fregistro = document.getElementById("fregistro").value.trim();
	var finicio = document.getElementById("finicio").value.trim();
	var ftermino = document.getElementById("ftermino").value.trim();
	var codccosto = document.getElementById("codccosto").value.trim();
	var tipo_actuacion = document.getElementById("tipo_actuacion").value.trim();
	var duracion = document.getElementById("duracion").value.trim();
	var proceso = document.getElementById("proceso").value.trim();
	var organismoext = document.getElementById("organismoext").value.trim();
	var dependenciaext = document.getElementById("dependenciaext").value.trim();
	var objetivo_general = document.getElementById("objetivo_general").value.trim();
	var alcance = document.getElementById("alcance").value.trim();
	var observaciones = document.getElementById("observaciones").value.trim();
	var estado = document.getElementById("estado").value.trim();
	
	//	auditores
	var detalles_auditores = "";
	var error_detalles_auditores = "";		
	var frmauditores = document.getElementById("frmauditores");
	for(i=0; n=frmauditores.elements[i]; i++) {
		if (n.name == "flagcoordinador") detalles_auditores += n.checked + "|";
		if (n.name == "persona") detalles_auditores += n.value + ";";
	}
	var len = detalles_auditores.length; len--;
	detalles_auditores = detalles_auditores.substr(0, len);
	
	//	actividades
	var detalles_actividades = "";
	var error_detalles_actividades = "";		
	var frmactividades = document.getElementById("frmactividades");
	for(i=0; n=frmactividades.elements[i]; i++) {
		if (n.name == "actividad") detalles_actividades += n.value + "|";
		if (n.name == "duracion") detalles_actividades += n.value + "|";
		if (n.name == "finicio") detalles_actividades += n.value + "|";
		if (n.name == "ftermino") detalles_actividades += n.value + "|";
		if (n.name == "prorroga") detalles_actividades += n.value + "|";
		if (n.name == "finicioreal") detalles_actividades += n.value + "|";
		if (n.name == "fterminoreal") detalles_actividades += n.value + ";";
	}
	var len = detalles_actividades.length; len--;
	detalles_actividades = detalles_actividades.substr(0, len);
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (organismo == "" || dependencia == "" || fregistro == "" || finicio == "" || ftermino == "" || codccosto == "" || tipo_actuacion == "" || proceso == "" || organismoext == "" || objetivo_general == "") alert("¡ERROR: Debe ingresar los campos obligatorios!");
	else if (!valFecha(finicio)) alert("¡ERROR: Formato de la fecha inicio incorrecta (dd-mm-aaaa)!");
	else if (detalles_auditores == "") alert("¡ERROR: Debe ingresar los abogados!");
	else if (detalles_actividades == "") alert("¡ERROR: Debe ingresar las actividades!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=POTESTAD-INVESTIGATIVA&accion="+accion+"&organismo="+organismo+"&dependencia="+dependencia+"&actuacion="+actuacion+"&codigo="+codigo+"&fregistro="+fregistro+"&finicio="+finicio+"&ftermino="+ftermino+"&codccosto="+codccosto+"&tipo_actuacion="+tipo_actuacion+"&duracion="+duracion+"&proceso="+proceso+"&organismoext="+organismoext+"&dependenciaext="+dependenciaext+"&objetivo_general="+objetivo_general+"&alcance="+alcance+"&observaciones="+observaciones+"&estado="+estado+"&detalles_auditores="+detalles_auditores+"&detalles_actividades="+detalles_actividades);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp.trim());
				else if (accion == "APROBAR") {
					window.open("pf_pdf_potestad_investigativa.php?codigo="+codigo+"&forganismo="+organismo+"&fregistrod="+fregistro+"&fregistroh="+fregistro+"&forganismoext="+organismoext+"&fdependenciaext="+dependenciaext+"&fedoreg="+estado+"&fproceso="+proceso, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes');
					form.submit();
				}
				else form.submit();
			}
		}
	}
	return false;
}
//	terminar actividad
function terminarPotestadActividad(form) {
	var potestad = document.getElementById("potestad").value.trim();
	var actividad = document.getElementById("actividad").value.trim();
	var secuencia = document.getElementById("secuencia").value.trim();
	var finicio_real = document.getElementById("finicio_real").value.trim();
	var ftermino_real = document.getElementById("ftermino_real").value.trim();
	var dias_real = document.getElementById("dias_real").value.trim();
	var fregistro_cierre = document.getElementById("fregistro_cierre").value.trim();
	var ftermino_cierre = document.getElementById("ftermino_cierre").value.trim();
	var duracion_cierre = document.getElementById("duracion_cierre").value.trim();
	var observaciones = document.getElementById("observaciones").value.trim();
	var detalles = "";
	var error_detalles = "";
	
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "documento") detalles += n.value + "|";
		if (n.name == "nrodocumento") detalles += n.value + "|";
		if (n.name == "fdocumento") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (ftermino_cierre == "") alert("¡ERROR: Debe ingresar los campos obligatorios!");
	else if (!valFecha(ftermino_cierre)) alert("¡ERROR: Formato de la fecha terminado incorrecta (dd-mm-aaaa)!");
	else if (formatFechaAMD(ftermino_cierre) > formatFechaAMD(fregistro_cierre)) alert("¡ERROR: La fecha de terminado es mayor a la fecha actual!");
	else if (formatFechaAMD(finicio_real) > formatFechaAMD(fregistro_cierre)) alert("¡ERROR: La fecha de inicio real es mayor a la fecha actual!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=POTESTAD-INVESTIGATIVA&accion=TERMINAR&potestad="+potestad+"&actividad="+actividad+"&secuencia="+secuencia+"&finicio_real="+finicio_real+"&ftermino_real="+ftermino_real+"&dias_real="+dias_real+"&fregistro_cierre="+fregistro_cierre+"&ftermino_cierre="+ftermino_cierre+"&duracion_cierre="+duracion_cierre+"&observaciones="+observaciones+"&detalles="+detalles);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp.trim());
				else form.submit();
			}
		}
	}
	return false;
}

//	auto archivar la actuación
function autoArchivoPotestad(form) {
	if (confirm("¿Está seguro de ordenar el Archivo de la Actuación?")) {
		var potestad = document.getElementById("potestad").value.trim();
		var actividad = document.getElementById("actividad").value.trim();
		var secuencia = document.getElementById("secuencia").value.trim();
		var finicio_real = document.getElementById("finicio_real").value.trim();
		var ftermino_real = document.getElementById("ftermino_real").value.trim();
		var dias_real = document.getElementById("dias_real").value.trim();
		var fregistro_cierre = document.getElementById("fregistro_cierre").value.trim();
		var ftermino_cierre = document.getElementById("ftermino_cierre").value.trim();
		var duracion_cierre = document.getElementById("duracion_cierre").value.trim();
		var observaciones = document.getElementById("observaciones").value.trim();
		var detalles = "";
		var error_detalles = "";
		
		var frmdetalles = document.getElementById("frmdetalles");
		for(i=0; n=frmdetalles.elements[i]; i++) {
			if (n.name == "documento") detalles += n.value + "|";
			if (n.name == "nrodocumento") detalles += n.value + "|";
			if (n.name == "fdocumento") detalles += n.value + ";";
		}
		var len = detalles.length; len--;
		detalles = detalles.substr(0, len);
		
		//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
		if (ftermino_cierre == "") alert("¡ERROR: Debe ingresar los campos obligatorios!");
		else if (!valFecha(ftermino_cierre)) alert("¡ERROR: Formato de la fecha terminado incorrecta (dd-mm-aaaa)!");
		else if (formatFechaAMD(ftermino_cierre) > formatFechaAMD(fregistro_cierre)) alert("¡ERROR: La fecha de terminado es mayor a la fecha actual!");
		else {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax_pf.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=POTESTAD-INVESTIGATIVA&accion=AUTO-ARCHIVO&potestad="+potestad+"&actividad="+actividad+"&secuencia="+secuencia+"&finicio_real="+finicio_real+"&ftermino_real="+ftermino_real+"&dias_real="+dias_real+"&fregistro_cierre="+fregistro_cierre+"&ftermino_cierre="+ftermino_cierre+"&duracion_cierre="+duracion_cierre+"&observaciones="+observaciones+"&detalles="+detalles);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText;
					if (resp.trim() != "") alert(resp.trim());
					else form.submit();
				}
			}
		}
	}
}

//	auto archivar la actuación
function autoArchivoActuacion(form) {
	if (confirm("¿Está seguro de ordenar el Archivo de la Actuación?")) {
		var actuacion = document.getElementById("actuacion").value.trim();
		var actividad = document.getElementById("actividad").value.trim();
		var secuencia = document.getElementById("secuencia").value.trim();
		var finicio_real = document.getElementById("finicio_real").value.trim();
		var ftermino_real = document.getElementById("ftermino_real").value.trim();
		var dias_real = document.getElementById("dias_real").value.trim();
		var fregistro_cierre = document.getElementById("fregistro_cierre").value.trim();
		var ftermino_cierre = document.getElementById("ftermino_cierre").value.trim();
		var duracion_cierre = document.getElementById("duracion_cierre").value.trim();
		var observaciones = document.getElementById("observaciones").value.trim();
		var detalles = "";
		var error_detalles = "";
		
		var frmdetalles = document.getElementById("frmdetalles");
		for(i=0; n=frmdetalles.elements[i]; i++) {
			if (n.name == "documento") detalles += n.value + "|";
			if (n.name == "nrodocumento") detalles += n.value + "|";
			if (n.name == "fdocumento") detalles += n.value + ";";
		}
		var len = detalles.length; len--;
		detalles = detalles.substr(0, len);
		
		//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
		if (ftermino_cierre == "") alert("¡ERROR: Debe ingresar los campos obligatorios!");
		else if (!valFecha(ftermino_cierre)) alert("¡ERROR: Formato de la fecha terminado incorrecta (dd-mm-aaaa)!");
		else if (formatFechaAMD(ftermino_cierre) > formatFechaAMD(fregistro_cierre)) alert("¡ERROR: La fecha de terminado es mayor a la fecha actual!");
		else {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax_pf.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=PLANIFICACION&accion=AUTO-ARCHIVO&actuacion="+actuacion+"&actividad="+actividad+"&secuencia="+secuencia+"&finicio_real="+finicio_real+"&ftermino_real="+ftermino_real+"&dias_real="+dias_real+"&fregistro_cierre="+fregistro_cierre+"&ftermino_cierre="+ftermino_cierre+"&duracion_cierre="+duracion_cierre+"&observaciones="+observaciones+"&detalles="+detalles);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText;
					if (resp.trim() != "") alert(resp.trim());
					else form.submit();
				}
			}
		}
	}
}

//	auto archivar la actuación
function autoArchivoDeterminacion(form) {
	if (confirm("¿Está seguro de ordenar el Archivo de la Actuación?")) {
		var determinacion = document.getElementById("determinacion").value.trim();
		var actividad = document.getElementById("actividad").value.trim();
		var secuencia = document.getElementById("secuencia").value.trim();
		var finicio_real = document.getElementById("finicio_real").value.trim();
		var ftermino_real = document.getElementById("ftermino_real").value.trim();
		var dias_real = document.getElementById("dias_real").value.trim();
		var fregistro_cierre = document.getElementById("fregistro_cierre").value.trim();
		var ftermino_cierre = document.getElementById("ftermino_cierre").value.trim();
		var duracion_cierre = document.getElementById("duracion_cierre").value.trim();
		var observaciones = document.getElementById("observaciones").value.trim();
		var detalles = "";
		var error_detalles = "";
		
		var frmdetalles = document.getElementById("frmdetalles");
		for(i=0; n=frmdetalles.elements[i]; i++) {
			if (n.name == "documento") detalles += n.value + "|";
			if (n.name == "nrodocumento") detalles += n.value + "|";
			if (n.name == "fdocumento") detalles += n.value + ";";
		}
		var len = detalles.length; len--;
		detalles = detalles.substr(0, len);
		
		//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
		if (ftermino_cierre == "") alert("¡ERROR: Debe ingresar los campos obligatorios!");
		else if (!valFecha(ftermino_cierre)) alert("¡ERROR: Formato de la fecha terminado incorrecta (dd-mm-aaaa)!");
		else if (formatFechaAMD(ftermino_cierre) > formatFechaAMD(fregistro_cierre)) alert("¡ERROR: La fecha de terminado es mayor a la fecha actual!");
		else {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax_pf.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=DETERMINACION-RESPONSABILIDADES&accion=AUTO-ARCHIVO&determinacion="+determinacion+"&actividad="+actividad+"&secuencia="+secuencia+"&finicio_real="+finicio_real+"&ftermino_real="+ftermino_real+"&dias_real="+dias_real+"&fregistro_cierre="+fregistro_cierre+"&ftermino_cierre="+ftermino_cierre+"&duracion_cierre="+duracion_cierre+"&observaciones="+observaciones+"&detalles="+detalles);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText;
					if (resp.trim() != "") alert(resp.trim());
					else form.submit();
				}
			}
		}
	}
}

//	PRORROGAS(POTESTAD)
function verificarPotestadProrroga(form, accion) {
	var organismo = document.getElementById("organismo").value.trim();
	var codigo = document.getElementById("codigo").value.trim();
	var fregistro = document.getElementById("fregistro").value.trim();
	var potestad = document.getElementById("potestad").value.trim();
	var motivo = document.getElementById("motivo").value.trim();
	var estado = document.getElementById("estado").value.trim();
	
	//	actividades
	var detalles_actividades = "";
	var error_detalles_actividades = "";
	var frmactividades = document.getElementById("frmactividades");
	var dias = new Number();
	for(i=0; n=frmactividades.elements[i]; i++) {
		if (n.name == "estado") {
			var estado_actividad = n.value;
			detalles_actividades += n.value + "|";
		}
		else if (n.name == "fase") detalles_actividades += n.value + "|";
		else if (n.name == "nomfase") detalles_actividades += n.value + "|";
		else if (n.name == "actividad") detalles_actividades += n.value + "|";
		else if (n.name == "nomactividad") detalles_actividades += n.value + "|";
		else if (n.name == "flagautoarchivo") detalles_actividades += n.value + "|";
		else if (n.name == "flagnoafectoplan") detalles_actividades += n.value + "|";
		else if (n.name == "duracion") detalles_actividades += n.value + "|";
		else if (n.name == "finicio") detalles_actividades += n.value + "|";
		else if (n.name == "ftermino") detalles_actividades += n.value + "|";
		else if (n.name == "acumulado") detalles_actividades += n.value + "|";
		else if (n.name == "prorroga") {
			var prorroga = new Number(setNumero(n.value));
			if (dias == 0 && prorroga != 0) dias = prorroga;
			if (isNaN(prorroga)) { error_detalles_actividades = "¡Debe ingresar un valor numérico en la duración de prórroga!"; n.value = 1; break; }
			else if (prorroga < 0) { error_detalles_actividades = "¡Debe ingresar un valor mayor a cero en la duración de prórroga!"; n.value = 1; break; }
			else if (prorroga == 0 && estado_actividad == "EJ") { error_detalles_actividades = "¡Debe ingresar un valor mayor a cero en la duración de prórroga!"; n.value = 1; break; } 
			else detalles_actividades += n.value + "|";
		}
		else if (n.name == "finicio_real") detalles_actividades += n.value + "|";
		else if (n.name == "ftermino_real") detalles_actividades += n.value + ";";
	}
	var len = detalles_actividades.length; len--;
	detalles_actividades = detalles_actividades.substr(0, len);
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (organismo == "" || fregistro == "" || potestad == "" || motivo == "") alert("¡ERROR: Debe ingresar los campos obligatorios!");
	else if (detalles_actividades == "") alert("¡ERROR: Debe ingresar las actividades!");
	else if (error_detalles_actividades != "") alert(error_detalles_actividades);
	else if (dias == 0) alert("¡la cantidad de dias de la prórroga no puede ser cero!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=POTESTAD-PRORROGA&accion="+accion+"&organismo="+organismo+"&codigo="+codigo+"&fregistro="+fregistro+"&potestad="+potestad+"&motivo="+motivo+"&estado="+estado+"&detalles_actividades="+detalles_actividades);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp.trim());
				else form.submit();
			}
		}
	}
	return false;
}
//	seleccionar actividades de la potestad
function setPotestadActividades(potestad, nompotestad) {
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_pf.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=setPotestadActividades&potestad="+potestad);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var datos = resp.split("||");
			if (datos[0].trim() != "") alert(datos[0]);
			else {
				opener.document.getElementById("potestad").value = potestad;
				opener.document.getElementById("nompotestad").value = nompotestad;
				opener.document.getElementById("listaActividades").innerHTML = datos[1];
				window.close();
			}
		}
	}
}
//	actualizar lista de actividades (prorroga)
function setPotestadProrrogaActividades() {
	var detalles = "";
	var error_detalles = "";		
	var frmdetalles = document.getElementById("frmactividades");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "estado") detalles += n.value + "|";
		else if (n.name == "fase") detalles += n.value + "|";
		else if (n.name == "nomfase") detalles += n.value + "|";
		else if (n.name == "actividad") detalles += n.value + "|";
		else if (n.name == "nomactividad") detalles += n.value + "|";
		else if (n.name == "flagautoarchivo") detalles += n.value + "|";
		else if (n.name == "flagnoafectoplan") detalles += n.value + "|";
		else if (n.name == "duracion") detalles += n.value + "|";
		else if (n.name == "finicio") detalles += n.value + "|";
		else if (n.name == "ftermino") detalles += n.value + "|";
		else if (n.name == "acumulado") detalles += n.value + "|";
		else if (n.name == "prorroga") {
			var prorroga = new Number(setNumero(n.value));
			if (isNaN(prorroga)) { error_detalles = "¡Debe ingresar un valor numérico en la duración de prórroga!"; n.value = 1; }
			else if (prorroga < 0) { error_detalles = "¡Debe ingresar un valor mayor a cero en la duración de prórroga!"; n.value = 1; }
			detalles += n.value + "|";
		}
		else if (n.name == "finicio_real") detalles += n.value + "|";
		else if (n.name == "ftermino_real") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (error_detalles != "") alert(error_detalles);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=setPotestadProrrogaActividades&detalles="+detalles);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				document.getElementById("listaActividades").innerHTML = "";
				document.getElementById("listaActividades").innerHTML = resp;
			}
		}
	}
	return false;
}

//	DETERMINACION DE RESPONSABILIDADES
function verificarDeterminacionResponsabilidades(form, accion) {
	var organismo = document.getElementById("organismo").value.trim();
	var dependencia = document.getElementById("dependencia").value.trim();
	var potestad = document.getElementById("potestad").value.trim();
	var codigo = document.getElementById("codigo").value.trim();
	var fregistro = document.getElementById("fregistro").value.trim();
	var finicio = document.getElementById("finicio").value.trim();
	var ftermino = document.getElementById("ftermino").value.trim();
	var codccosto = document.getElementById("codccosto").value.trim();
	var tipo_actuacion = document.getElementById("tipo_actuacion").value.trim();
	var duracion = document.getElementById("duracion").value.trim();
	var proceso = document.getElementById("proceso").value.trim();
	var organismoext = document.getElementById("organismoext").value.trim();
	var dependenciaext = document.getElementById("dependenciaext").value.trim();
	var objetivo_general = document.getElementById("objetivo_general").value.trim();
	var alcance = document.getElementById("alcance").value.trim();
	var observaciones = document.getElementById("observaciones").value.trim();
	var estado = document.getElementById("estado").value.trim();
	
	//	auditores
	var detalles_auditores = "";
	var error_detalles_auditores = "";		
	var frmauditores = document.getElementById("frmauditores");
	for(i=0; n=frmauditores.elements[i]; i++) {
		if (n.name == "flagcoordinador") detalles_auditores += n.checked + "|";
		if (n.name == "persona") detalles_auditores += n.value + ";";
	}
	var len = detalles_auditores.length; len--;
	detalles_auditores = detalles_auditores.substr(0, len);
	
	//	actividades
	var detalles_actividades = "";
	var error_detalles_actividades = "";		
	var frmactividades = document.getElementById("frmactividades");
	for(i=0; n=frmactividades.elements[i]; i++) {
		if (n.name == "actividad") detalles_actividades += n.value + "|";
		if (n.name == "duracion") detalles_actividades += n.value + "|";
		if (n.name == "finicio") detalles_actividades += n.value + "|";
		if (n.name == "ftermino") detalles_actividades += n.value + "|";
		if (n.name == "prorroga") detalles_actividades += n.value + "|";
		if (n.name == "finicioreal") detalles_actividades += n.value + "|";
		if (n.name == "fterminoreal") detalles_actividades += n.value + ";";
	}
	var len = detalles_actividades.length; len--;
	detalles_actividades = detalles_actividades.substr(0, len);
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (organismo == "" || dependencia == "" || fregistro == "" || finicio == "" || ftermino == "" || codccosto == "" || tipo_actuacion == "" || proceso == "" || organismoext == "" || objetivo_general == "") alert("¡ERROR: Debe ingresar los campos obligatorios!");
	else if (!valFecha(finicio)) alert("¡ERROR: Formato de la fecha inicio incorrecta (dd-mm-aaaa)!");
	else if (detalles_auditores == "") alert("¡ERROR: Debe ingresar los abogados!");
	else if (detalles_actividades == "") alert("¡ERROR: Debe ingresar las actividades!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=DETERMINACION-RESPONSABILIDADES&accion="+accion+"&organismo="+organismo+"&dependencia="+dependencia+"&potestad="+potestad+"&codigo="+codigo+"&fregistro="+fregistro+"&finicio="+finicio+"&ftermino="+ftermino+"&codccosto="+codccosto+"&tipo_actuacion="+tipo_actuacion+"&duracion="+duracion+"&proceso="+proceso+"&organismoext="+organismoext+"&dependenciaext="+dependenciaext+"&objetivo_general="+objetivo_general+"&alcance="+alcance+"&observaciones="+observaciones+"&estado="+estado+"&detalles_auditores="+detalles_auditores+"&detalles_actividades="+detalles_actividades);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp.trim());
				else if (accion == "APROBAR") {
					window.open("pf_pdf_determinacion_responsabilidades.php?codigo="+codigo+"&forganismo="+organismo+"&fregistrod="+fregistro+"&fregistroh="+fregistro+"&forganismoext="+organismoext+"&fdependenciaext="+dependenciaext+"&fedoreg="+estado+"&fproceso="+proceso, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes');
					form.submit();
				}
				else form.submit();
			}
		}
	}
	return false;
}
//	terminar actividad
function terminarDeterminacionActividad(form) {
	var determinacion = document.getElementById("determinacion").value.trim();
	var actividad = document.getElementById("actividad").value.trim();
	var secuencia = document.getElementById("secuencia").value.trim();
	var finicio_real = document.getElementById("finicio_real").value.trim();
	var ftermino_real = document.getElementById("ftermino_real").value.trim();
	var dias_real = document.getElementById("dias_real").value.trim();
	var fregistro_cierre = document.getElementById("fregistro_cierre").value.trim();
	var ftermino_cierre = document.getElementById("ftermino_cierre").value.trim();
	var duracion_cierre = document.getElementById("duracion_cierre").value.trim();
	var observaciones = document.getElementById("observaciones").value.trim();
	var detalles = "";
	var error_detalles = "";
	
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "documento") detalles += n.value + "|";
		if (n.name == "nrodocumento") detalles += n.value + "|";
		if (n.name == "fdocumento") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (ftermino_cierre == "") alert("¡ERROR: Debe ingresar los campos obligatorios!");
	else if (!valFecha(ftermino_cierre)) alert("¡ERROR: Formato de la fecha terminado incorrecta (dd-mm-aaaa)!");
	else if (formatFechaAMD(ftermino_cierre) > formatFechaAMD(fregistro_cierre)) alert("¡ERROR: La fecha de terminado es mayor a la fecha actual!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=DETERMINACION-RESPONSABILIDADES&accion=TERMINAR&determinacion="+determinacion+"&actividad="+actividad+"&secuencia="+secuencia+"&finicio_real="+finicio_real+"&ftermino_real="+ftermino_real+"&dias_real="+dias_real+"&fregistro_cierre="+fregistro_cierre+"&ftermino_cierre="+ftermino_cierre+"&duracion_cierre="+duracion_cierre+"&observaciones="+observaciones+"&detalles="+detalles);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp.trim());
				else form.submit();
			}
		}
	}
	return false;
}

//	PRORROGAS(DETERMINACION)
function verificarDeterminacionProrroga(form, accion) {
	var organismo = document.getElementById("organismo").value.trim();
	var codigo = document.getElementById("codigo").value.trim();
	var fregistro = document.getElementById("fregistro").value.trim();
	var determinacion = document.getElementById("determinacion").value.trim();
	var motivo = document.getElementById("motivo").value.trim();
	var estado = document.getElementById("estado").value.trim();
	
	//	actividades
	var detalles_actividades = "";
	var error_detalles_actividades = "";
	var frmactividades = document.getElementById("frmactividades");
	var dias = new Number();
	for(i=0; n=frmactividades.elements[i]; i++) {
		if (n.name == "estado") {
			var estado_actividad = n.value;
			detalles_actividades += n.value + "|";
		}
		else if (n.name == "fase") detalles_actividades += n.value + "|";
		else if (n.name == "nomfase") detalles_actividades += n.value + "|";
		else if (n.name == "actividad") detalles_actividades += n.value + "|";
		else if (n.name == "nomactividad") detalles_actividades += n.value + "|";
		else if (n.name == "flagautoarchivo") detalles_actividades += n.value + "|";
		else if (n.name == "flagnoafectoplan") detalles_actividades += n.value + "|";
		else if (n.name == "duracion") detalles_actividades += n.value + "|";
		else if (n.name == "finicio") detalles_actividades += n.value + "|";
		else if (n.name == "ftermino") detalles_actividades += n.value + "|";
		else if (n.name == "acumulado") detalles_actividades += n.value + "|";
		else if (n.name == "prorroga") {
			var prorroga = new Number(setNumero(n.value));
			if (dias == 0 && prorroga != 0) dias = prorroga;
			if (isNaN(prorroga)) { error_detalles_actividades = "¡Debe ingresar un valor numérico en la duración de prórroga!"; n.value = 1; break; }
			else if (prorroga < 0) { error_detalles_actividades = "¡Debe ingresar un valor mayor a cero en la duración de prórroga!"; n.value = 1; break; }
			else if (prorroga == 0 && estado_actividad == "EJ") { error_detalles_actividades = "¡Debe ingresar un valor mayor a cero en la duración de prórroga!"; n.value = 1; break; } 
			else detalles_actividades += n.value + "|";
		}
		else if (n.name == "finicio_real") detalles_actividades += n.value + "|";
		else if (n.name == "ftermino_real") detalles_actividades += n.value + ";";
	}
	var len = detalles_actividades.length; len--;
	detalles_actividades = detalles_actividades.substr(0, len);
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (organismo == "" || fregistro == "" || determinacion == "" || motivo == "") alert("¡ERROR: Debe ingresar los campos obligatorios!");
	else if (detalles_actividades == "") alert("¡ERROR: Debe ingresar las actividades!");
	else if (error_detalles_actividades != "") alert(error_detalles_actividades);
	else if (dias == 0) alert("¡la cantidad de dias de la prórroga no puede ser cero!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=DETERMINACION-PRORROGA&accion="+accion+"&organismo="+organismo+"&codigo="+codigo+"&fregistro="+fregistro+"&determinacion="+determinacion+"&motivo="+motivo+"&estado="+estado+"&detalles_actividades="+detalles_actividades);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp.trim());
				else form.submit();
			}
		}
	}
	return false;
}
//	seleccionar actividades de la potestad
function setDeterminacionActividades(determinacion, nomdeterminacion) {
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_pf.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=setDeterminacionActividades&determinacion="+determinacion);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var datos = resp.split("||");
			if (datos[0].trim() != "") alert(datos[0]);
			else {
				opener.document.getElementById("determinacion").value = determinacion;
				opener.document.getElementById("nomdeterminacion").value = nomdeterminacion;
				opener.document.getElementById("listaActividades").innerHTML = datos[1];
				window.close();
			}
		}
	}
}
//	actualizar lista de actividades (prorroga)
function setDeterminacionProrrogaActividades() {
	var detalles = "";
	var error_detalles = "";		
	var frmdetalles = document.getElementById("frmactividades");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "estado") detalles += n.value + "|";
		else if (n.name == "fase") detalles += n.value + "|";
		else if (n.name == "nomfase") detalles += n.value + "|";
		else if (n.name == "actividad") detalles += n.value + "|";
		else if (n.name == "nomactividad") detalles += n.value + "|";
		else if (n.name == "flagautoarchivo") detalles += n.value + "|";
		else if (n.name == "flagnoafectoplan") detalles += n.value + "|";
		else if (n.name == "duracion") detalles += n.value + "|";
		else if (n.name == "finicio") detalles += n.value + "|";
		else if (n.name == "ftermino") detalles += n.value + "|";
		else if (n.name == "acumulado") detalles += n.value + "|";
		else if (n.name == "prorroga") {
			var prorroga = new Number(setNumero(n.value));
			if (isNaN(prorroga)) { error_detalles = "¡Debe ingresar un valor numérico en la duración de prórroga!"; n.value = 1; }
			else if (prorroga < 0) { error_detalles = "¡Debe ingresar un valor mayor a cero en la duración de prórroga!"; n.value = 1; }
			detalles += n.value + "|";
		}
		else if (n.name == "finicio_real") detalles += n.value + "|";
		else if (n.name == "ftermino_real") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (error_detalles != "") alert(error_detalles);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=setDeterminacionProrrogaActividades&detalles="+detalles);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				document.getElementById("listaActividades").innerHTML = "";
				document.getElementById("listaActividades").innerHTML = resp;
			}
		}
	}
	return false;
}

//	FUNCION PARA VALIDAR EL FILTRO
function pdf_actuacion_fiscal(form) {
	var forganismo = document.getElementById("forganismo").value;
	var fregistrod = document.getElementById("fregistrod").value;
	var fregistroh = document.getElementById("fregistroh").value;
	var forganismoext = document.getElementById("forganismoext").value;
	var fdependenciaext = document.getElementById("fdependenciaext").value;
	var fedoreg = document.getElementById("fedoreg").value;
	var factuacion = document.getElementById("factuacion").value;
	var fproceso = document.getElementById("fproceso").value;
	
	if (fproceso == "01")
		window.open("pf_pdf_actuacion_fiscal.php?forganismo="+forganismo+"&fregistrod="+fregistrod+"&fregistroh="+fregistroh+"&forganismoext="+forganismoext+"&fdependenciaext="+fdependenciaext+"&fedoreg="+fedoreg+"&fproceso="+fproceso, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes');
	return false;
}

//
function insertarDocumentoPlanificacionFiscal() {
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_pf.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=insertarDocumentoPlanificacionFiscal");
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var codigo = new Number(document.getElementById("nrodetalle").value);
			var newTr = document.createElement("tr");
			newTr.className = "trListaBody";
			newTr.setAttribute("onclick", "mClk(this, 'seldetalle');");
			newTr.id = codigo;
			document.getElementById("listaDetalles").appendChild(newTr);
			document.getElementById(codigo).innerHTML += resp;
			document.getElementById("nrodetalle").value = ++codigo;
		}
	}
}

//	
function quitarDocumentoPlanificacionFiscal(detalle) {
	var nrodetalle = new Number(document.getElementById("nrodetalle").value);
	var listaDetalles = document.getElementById("listaDetalles"); 
	var trDetalle = document.getElementById(detalle.value); 
	listaDetalles.removeChild(trDetalle);
	detalle.value = "";
}
