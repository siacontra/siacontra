//// JavaScript Document
//// ---------------------------------------------------------------------------------------
//// FUNCION QUE PERMITE GUARDAR NUEVO DOCUMENTO INTERNO - CORRESPONDENCIA
//// ---------------------------------------------------------------------------------------
function guardarDocumentoInterno(form) { 
	var organismo = document.getElementById("organismo").value;
	var dep_interna = document.getElementById("dep_interna").value;
	var n_documento = document.getElementById("n_documento").value;
	var t_documento = document.getElementById("t_documento").value;
	var asunto = document.getElementById("asunto").value;
	var descrip = document.getElementById("descrip").value;
	var destinatario_int = document.getElementById("destinatario_int").value;
	var codigo_interno = document.getElementById("codigo_interno").value;
	var codigo_persona = document.getElementById("codigo_persona").value;
	var codigo_cargo = document.getElementById("codigo_cargo").value;
	var plazo = document.getElementById("plazo").value;
	var anexsi1 = document.getElementById("anexsi1").value; //alert(anexsi1);
	var anexsi2 = document.getElementById("anexsi2").value; //alert(anexsi2);
	var anexDescp = document.getElementById("anexDescp").value; //alert(anexDescp);
	var cc = document.getElementById("cc").value;
	
	var detalles = "";
	var error_detalles = "";	
	// obtengo los valores de las lineas insertadas
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "codpersona") detalles += n.value + "|";
		if (n.name == "cod_dependencia") detalles += n.value + "|";
		if (n.name == "cargo") detalles += n.value + "|";
		if (n.name == "cc") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (error_detalles != "") alert(error_detalles);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "gmcorrespondencia.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=guardarDocumentoInterno&detalles="+detalles+"&organismo="+organismo+"&dep_interna="+dep_interna+"&t_documento="+t_documento+"&n_documento="+n_documento+"&asunto="+asunto+"&descrip="+descrip+"&destinatario_int="+destinatario_int+"&codigo_interno="+codigo_interno+"&codigo_persona="+codigo_persona+"&codigo_cargo="+codigo_cargo+"&plazo="+plazo+"&anexsi1="+anexsi1+"&anexsi2="+anexsi2+"&anexDescp="+anexDescp);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != "") alert(resp.trim());
				form.submit();
			}
		}
	}
	return false;
}
//// ---------------------------------------------------------------------------------------
//// FUNCION QUE PERMITE EDITAR DOCUMENTO INTERNO - CORRESPONDENCIA
//// ---------------------------------------------------------------------------------------
function guardarDocumentoInternoEditar(form) {
	var organismo = document.getElementById("organismo").value;
	var dep_interna = document.getElementById("dep_interna").value;
	var n_documento = document.getElementById("n_documento").value;
	var t_documento = document.getElementById("t_documento").value;
	var asunto = document.getElementById("asunto").value;
	var descrip = document.getElementById("descrip").value;
	var destinatario_int = document.getElementById("destinatario_int").value;
	var codigo_interno = document.getElementById("codigo_interno").value;
	var codigo_persona = document.getElementById("codigo_persona").value;
	var codigo_cargo = document.getElementById("codigo_cargo").value;
	var plazo = document.getElementById("plazo").value;
	var anexsi1 = document.getElementById("anexsi1").value; //alert("anexsi1"+anexsi1);
	var anexsi2 = document.getElementById("anexsi2").value; //alert("anexsi2"+anexsi2);
	var anexDescp = document.getElementById("anexDescp").value; //alert("anexDescp"+anexDescp);
	var Estado  = document.getElementById("Estado").value;
	var Anexos = document.getElementById("anexos").value; //alert("Anexos="+Anexos);
	var cc = document.getElementById("cc").value;
	
	var detalles = "";
	var error_detalles = "";	
	// obtengo los valores de las lineas insertadas
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "codpersona") detalles += n.value + "|";
		if (n.name == "cod_dependencia") detalles += n.value + "|";
		if (n.name == "cargo") detalles += n.value + "|";
		if (n.name == "cc") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (error_detalles != "") alert(error_detalles);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "gmcorrespondencia.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=guardarDocumentoInternoEditar&detalles="+detalles+"&organismo="+organismo+"&dep_interna="+dep_interna+"&t_documento="+t_documento+"&n_documento="+n_documento+"&asunto="+asunto+"&descrip="+descrip+"&destinatario_int="+destinatario_int+"&codigo_interno="+codigo_interno+"&codigo_persona="+codigo_persona+"&codigo_cargo="+codigo_cargo+"&plazo="+plazo+"&anexsi1="+anexsi1+"&anexsi2="+anexsi2+"&anexDescp="+anexDescp+"&Estado="+Estado+"&Anexos="+Anexos);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != "") alert(resp.trim());
				form.submit();
			}
		}
	}
	return false;
}
//// ---------------------------------------------------------------------------------------
//// FUNCION QUE PERMITE ACTIVAR UN CAMPO EN EL ARCHVO CPI_DOCINTERNONUEVOS.PHP
//// ---------------------------------------------------------------------------------------
function activar(form){
    if (document.getElementById("anexsi").checked==true){ form.anexDescp.style.visibility='visible';}
  else {form.anexDescp.style.visibility='hidden';} 
}
//// ---------------------------------------------------------------------------------------
//// FUNCION PARA ASIGNAR EL VALOR  DE "S" Y ACTIVAR UN CAMPO
//// ---------------------------------------------------------------------------------------
function asignar1(form){
  var valor = document.getElementById("anexsi1").value; //alert(valor);
      document.getElementById("anexsi1").value = 'S'; //alert(document.getElementById("anexsi1").value);
	  document.getElementById("anexos").value = 'S';
  
  if (document.getElementById("anexsi1").checked==true){ 
     form.anexDescp.style.visibility='visible';
	 document.getElementById("anexsi2").checked=false;
	 document.getElementById("anexsi2").value=""; //alert(document.getElementById("anexsi2").value);
  }else{
     form.anexDescp.style.visibility='hidden';
  }
}
//// ---------------------------------------------------------------------------------------
//// ---------------------------------------------------------------------------------------
function asignar2(form){
  var valor = document.getElementById("anexsi2").value; //alert(valor);
      document.getElementById("anexsi2").value = 'N'; //alert(document.getElementById("anexsi2").value);
	  document.getElementById("anexos").value = 'N';
	  
	  if (document.getElementById("anexsi2").checked==true){ 
	      form.anexDescp.style.visibility='hidden';
		  document.getElementById("anexsi1").checked=false; 
		  document.getElementById("anexsi1").value=""; //alert(document.getElementById("anexsi1").value);
	  }else{
		  form.anexDescp.style.visibility='visible';
	  }
}
//// ---------------------------------------------------------------------------------------
//// Función que permite activar y desactivar campos (cpe_entrada.php)
//// ---------------------------------------------------------------------------------------
function enabledOrgRemitenteExterno(form){
	if (form.checkRemitente.checked) form.fremitente.disabled=false;
	else {form.fremitente.disabled=true; form.fremitente.value="";} 
} 
//// ACTIVAR Y DESACTIVAR CAMPOS, REPORTE ELABORADO POR
function enabledReporteElaboradoPor(form){
	if (form.checkElaborado.checked) form.fElaboradoPor.disabled=false;
	else {form.fElaboradoPor.disabled=true; form.fElaboradoPor.value="";} 
}  
//// ACTIVAR Y DESACTIVAR CAMPOS, REPORTE NUMERO DE DOCUMENTO
function enabledNroDocumento(form){
	if (form.checkNroDocumento.checked) form.NroDocumento.disabled=false;
	else {form.NroDocumento.disabled=true; form.NroDocumento.value="";} 
}  
//// --------------------------------------------------------------------------------------
//// FUNCION PARA CARGAR UNA NUEVA PAGINA VALIDANDO EL ESTADO PREPARADO 
//// --------------------------------------------------------------------------------------
function cargarOpcionImprimir(form, pagina, target, param) { 
	var codigo=form.registro.value; //alert('l= '+codigo);
	
	if (codigo=="") msjError(1000);
	else{
		var status = document.getElementById("status").value; //alert('status='+status);
	    if((status=="PP") || (status=="EV") || (status=="RE")){
		   //	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "gmcorrespondencia.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("accion=consultarPersonasDocInterno&codigo="+codigo);
			ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				//var veces = new Number(resp);
				//for (var i=0; i<veces; i++) {
					var pag = pagina + "?registro="+codigo;//+"&nropersona="+i;
					window.open(pag, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes')
				//}
					//cargarVentana(form, pagina, param);
				}
			}
		} else {
	       alert('El Documento no posee Contenido');	
	    }
     }
}
//// ---------------------------------------------------------------------------------------
//// FUNCION PARA ACTIVAR Y DESACTIVAR CAMPOS (cpe_atenderext.ph)
//// ---------------------------------------------------------------------------------------
function enabledRemitenteAtenderExt(form){
  	if(form.checkRemitente.checked) form.fremitente.disabled=false;
	else{form.fremitente.disabled=true; form.fremitente.value="";}
}
function enabledRecibidoPor(form){
  if (form.checkRecibido.checked) form.fRecibidoPor.disabled=false;
  else {form.fRecibidoPor.disabled=true; form.fRecibidoPor.value="";} 
}
//// ---------------------------------------------------------------------------------------
//// FUNCION QUE PERMITE INSERTAR UNA DEPENDENCIA " MODULO DE CORRESPONDENCIA"
//// ---------------------------------------------------------------------------------------
function insertarDestinatarioDep(codigo, accion) { //alert('Pasasssss');
    var ventana = document.getElementById("ventana").value; //alert(ventana);
	var tabla = document.getElementById("tabla").value; //alert(tabla);
	var form = opener.document.getElementById("frmdetalles");
	var detalles = "";
	
	for(i=0; n=form.elements[i]; i++) {
		if (n.name == "chkdetalles") detalles += n.title + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "gmcorrespondencia.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=insertarDestinatarioDep&codigo="+codigo+"&ventana="+ventana+"&tabla="+tabla+"&detalles="+detalles);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var datos = resp.split("||");
			if (datos[0].trim() != "") ;
			else {
				var newTr = document.createElement("tr");
				newTr.className = "trListaBody";
				newTr.setAttribute("onclick", "mClk(this, 'seldetalle');");
				newTr.id = codigo; //alert(codigo);
				opener.document.getElementById("listaDetalles").appendChild(newTr);
				opener.document.getElementById(codigo).innerHTML = datos[1];
				window.close();
			}
		}
	}
}
//// ---------------------------------------------------------------------------------------
////  QUITAR LINEA DETALLE DATOS DE EMPLEADO " MODULO DE CORRESPONDENCIA"
//// ---------------------------------------------------------------------------------------
function quitarLineaDestinatario(seldetalle) {
	var listaDetalles = document.getElementById("listaDetalles"); 
	var tr = document.getElementById(seldetalle);
	listaDetalles.removeChild(tr);
	
	document.getElementById(seldetalle).value = "";
		
}
//// ---------------------------------------------------------------------------------------
//// FUNCION VERIFICADOR DE DESTINATARIO
//// ---------------------------------------------------------------------------------------
function Verificar(){
  var valor = new Number(document.getElementById("verificador").value);
  document.getElementById("verificador").value = 1 + valor;
}
//// ---------------------------------------------------------------------------
////     FUNCION PARA CARGAR UNA NUEVA PAGINA VALIDANDO EL ESTADO PREPARADO 
//// ---------------------------------------------------------------------------
function cargarOpcionImprimir(form, pagina, target, param) { 
	var codigo=form.registro.value;
	
	if (codigo=="") msjError(1000);
	else{
		var status = document.getElementById("status").value; //alert('status='+status);
	    if((status=="PP") || (status=="EV") || (status=="RE")){
		   //	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "gmcorrespondencia.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("accion=consultarPersonasDocInterno&codigo="+codigo);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText;
					//var veces = new Number(resp);
					//for (var i=0; i<veces; i++) {
						var pag = pagina + "?registro="+codigo;//+"&nropersona="+i;
						window.open(pag, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes')
					//}
					
					
					
					//cargarVentana(form, pagina, param);
				}
			}
		} else {
	       alert('El Documento no posee Contenido');	
	    }
     }
}
//// ---------------------------------------------------------------------------
//// 
function cargarOpcionImprimirDepenRecibido(form, pagina, target, param) { 
	//var codigo=form.registro.value;
	  var codigo = document.getElementById('registro').value; //alert('codigo='+codigo);
	if (codigo=="") msjError(1000);
	else{
		var status = document.getElementById("status").value; //alert('status='+status);
	    if((status=="PP") || (status=="EV") || (status=="RE")){
		  //var pag = pagina;
		  window.open(pagina, 'blank', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes')
		} else {
	       alert('El Documento no posee Contenido');	
	    }
     }
}
//// ---------------------------------------------------------------------------
//// 
function cargarOpcionImprimirDepenEnviado(form, pagina, target, param) { 
	//var codigo=form.registro.value;
	  var codigo = document.getElementById('registro').value; //alert('codigo='+codigo);
	if (codigo=="") msjError(1000);
	else{
		var status = document.getElementById("status").value; //alert('status='+status);
	    if((status=="PP") || (status=="EV") || (status=="RE")){
		  //var pag = pagina;
		  window.open(pagina, 'blank', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes')
		} else {
	       alert('El Documento no posee Contenido');	
	    }
     }
}
//// ---------------------------------------------------------------------------
//// FUNCION CARGAR PAGINA 
function cargarOpcionPdf(form, pagina, target, param) {
	var fecha1 = document.getElementById("fdesde").value; //alert('fecha1='+fecha1);
	var fecha2 = document.getElementById("fhasta").value; //alert('fecha2='+fecha2);
	if((fecha1!='')&&(fecha2!='')){
	  if (target=="SELF") cargarPagina(form, pagina);
	  else { 
	       pagina=pagina+"&limit=0&accion=VER"; cargarVentana(form, pagina, param); }
	}else{
	    alert('No pueder realizar la acción...!');	
	}
}
//// ---------------------------------------------------------------------------------------
function enabledRpFechaRecibido(form){
	if (form.checkFechaRecibido.checked) {form.fdesde.disabled=false; form.fhasta.disabled=false; form.valor.value='';} 
	else{ form.fdesde.disabled=true; form.fhasta.disabled=true; form.fhasta.value=""; form.fdesde.value=""; form.valor.value='disabled';} 
}
//// ---------------------------------------------------------------------------------------
function CargarOpcionEditarSalida(form,pagina, target, param){
  var codigo = form.registro.value;
  
  if(codigo == "") msjError(1000);
  else{
    var status = document.getElementById("status").value;
	if((status="PR")&&(status="PP")){
		if(target=="SELF") cargarPagina(form, pagina);
		else{ pagina = pagina+"?limit=0&registro="+codigo; cargarVentana(form,pagina,param);}
	}else{
	    alert('No pueder realizar la acción...!');	
	}
  }
}
//// ---------------------------------------------------------------------------------------
//// ---------------------------------------------------------------------------
//// ---------------------------------------------------------------------------
//// FUNCION PARA GUARDAR EL ENVIO NTERNO - CORRESPONDENCIA
//// ---------------------------------------------------------------------------
//// ---------------------------------------------------------------------------
function guardarEnvioInterno(form){ //alert('Pasando vaina g');
  
	var ndoc_completo = document.getElementById("ndoc_completo").value;
	var t_documento = document.getElementById("t_documento").value;
	var periodo = document.getElementById("periodo").value;
	var codempleado = document.getElementById("codempleado").value;
	var cod_cargoremit = document.getElementById("cod_cargoremit").value;
	var cod_tipodocumento = document.getElementById("cod_tipodocumento").value;
	
    var detalles = "";
    var error_detalles = "";
  
  for(i=0; n=form.elements[i]; i++){
	  if ((n.type=="hidden") && (form.elements[i].className == "A")){ 
	     detalles += n.value + ";"; //alert('='+detalles);
	  }
  }
  /*var len = detalles.lenght; len--;
  detalles = detalles.substr(0, len); alert('detalles='+detalles);*/
  
  if (error_detalles != "") alert(error_detalles);
  else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "gmcorrespondencia.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=guardarEnvioInterno&detalles="+detalles+"&ndoc_completo="+ndoc_completo+"&t_documento="+t_documento+"&periodo="+periodo+"&codempleado="+codempleado+"&cod_cargoremit="+cod_cargoremit+"&cod_tipodocumento="+cod_tipodocumento);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != "") alert(resp.trim());
				else form.submit();
			}
		}
	}
	return false;
}
//// ---------------------------------------------------------------------------------------
//// FUNCION QUE PERMITE ENVIAR DATOS Y CARGAR EN PANTALLA EL REPORTE
function filtroDocumentosEntrada(form, limit) {
	
	var filtro="";
	if(form.checkorganismos.checked) filtro+=" and CodOrganismo=*"+form.forganismo.value+"*";
	if(form.checkRemitente.checked) filtro+=" and Cod_Organismos=*"+form.fremitente.value+"*";
	if(form.checkRecibido.checked) filtro+=" and RecibidoPor=*"+form.fRecibidoPor.value+"*";
	if(form.checkDepRemitente.checked) filtro+=" and Cod_Dependencia=*"+form.DepRemitente.value+"*";
	if(form.checkEstado.checked) filtro+=" and Estado=*"+form.fEstado.value+"*";
	if(form.checkFechaRecibido.checked){
		var fdesde = new String (document.getElementById("fdesde").value);
		var fhasta = new String (document.getElementById("fhasta").value);
		var fdesde = fdesde.split("-");
		var fd = fdesde[2]+"-"+fdesde[1]+"-"+fdesde[0];
		
		var fhasta = fhasta.split("-"); 
		var fh = fhasta[2]+"-"+fhasta[1]+"-"+fhasta[0];
		
	  filtro+=" and FechaRegistro>=*"+fd+"*"+"and FechaRegistro <=*"+fh+"*";
	}
	
	var pagina="rp_entradadocumentoslistapdf.php?filtro="+filtro;
			cargarPagina(form, pagina);

}
//// ---------------------------------------------------------------------------------------
//// FUNCION QUE PERMITE ENVIAR DATOS Y CARGAR EN PANTALLA EL REPORTE
function filtroDocumentosEntradaDistXDoc(form, limit) {
	
	var today = new Date(); //alert('today='+today);
	var dia = today.getDate(); //alert('diames='+ diames);
	var mes=today.getMonth() +1 ; //alert('mes='+ mes);
	var ano=today.getFullYear(); //alert('ano='+ ano);
	var fh=""; var fd=""; //var anio="";
	
	var filtro="";
	if(form.checkorganismos.checked) filtro+=" and CodOrganismo=*"+form.forganismo.value+"*";
	if(form.checkRemitente.checked) filtro+=" and Cod_Organismos=*"+form.fremitente.value+"*";
	if(form.checkRecibido.checked) filtro+=" and RecibidoPor=*"+form.fRecibidoPor.value+"*";
	if(form.checkDepRemitente.checked) filtro+=" and Cod_Dependencia=*"+form.DepRemitente.value+"*";
	if(form.checkNroDocumento.checked) filtro+=" and Cod_Documento=*"+form.fNroDocumento.value+"*";
	if(form.checkTdocumento.checked) filtro+=" and Cod_TipoDocumento=*"+form.fTdocumento.value+"*";
	if(form.checkEstado.checked) filtro+=" and cpdocent.Estado=*"+form.fEstado.value+"*";
	
	if(form.checkFechaRecibido.checked){
		var fdesde = new String (document.getElementById("fdesde").value);
		var fhasta = new String (document.getElementById("fhasta").value);
		var fdesde = fdesde.split("-");
		fd = fdesde[2]+"-"+fdesde[1]+"-"+fdesde[0];
		
		var fhasta = fhasta.split("-"); 
		fh = fhasta[2]+"-"+fhasta[1]+"-"+fhasta[0];
		
	  filtro+=" and FechaRegistro>=*"+fd+"*"+"and FechaRegistro <=*"+fh+"*";
	}else{
	   fd = ano+"-"+"01-01";// alert(fd); 
	   fh = ano+"-"+mes+"-"+dia;//alert(fh);
	   filtro+=" and FechaRegistro>=*"+fd+"*"+"and FechaRegistro <=*"+fh+"*";
	}
	
	var pagina="rp_entradadistdocumentopdf.php?filtro="+filtro;
			cargarPagina(form, pagina);

}
//// ---------------------------------------------------------------------------------------
//// FUNCION QUE PERMITE REALIZAR CAMBIOS POR CODIGO, PUEDE SER MODIFICABLE
function activarCambio(){
  var ajax=nuevoAjax();
		ajax.open("POST", "gmcorrespondencia.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=activarCambio");
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != "") alert(resp.trim());
			}
		}
}
//// ---------------------------------------------------------------------------------------
//// ACTIVA Y DESACTIVA CAMPOS EN RP_ENTRADADISTDETALLE
function enabledRegistroInt(form){
 if(form.chkRegInt.checked) form.fRegInt.disabled = false;
 else{ form.fRegInt.disabled = true;  form.fRegInt.value = '';}  
}
//// ---------------------------------------------------------------------------------------
////
function getRemitenteRpEntradaDistDetalle(form){
 if(form.checkRemitente.checked) form.fremitente.disabled = false;
 else{ form.fremitente.disabled = true; form.fremitente.value = '';}
}
//// ---------------------------------------------------------------------------------------
//// FUNCION QUE PERMITE ENVIAR DATOS Y CARGAR EN PANTALLA EL REPORTE
function filtroDocumentosEntradaDistDetalle(form, limit) {
	
	var today = new Date(); //alert('today='+today);
	var dia = today.getDate(); //alert('diames='+ diames);
	var mes=today.getMonth() +1 ; //alert('mes='+ mes);
	var ano=today.getFullYear(); //alert('ano='+ ano);
	var fh=""; var fd=""; //var anio="";
	
	var filtro=""; var CodDependencia ="";
	if(form.checkorganismos.checked) filtro+=" and cpdist.Cod_Organismo=*"+form.forganismo.value+"*";
	if(form.checkRemitente.checked){ 
		CodDependencia = document.getElementById("fremitente").value; 
		//alert('CodDependencia1='+CodDependencia);
	}else{ 
		CodDependencia = ""; 
		//alert('CodDependencia2='+CodDependencia);
	}
	if(form.checkTdocumento.checked) filtro+=" and cpdist.Cod_TipoDocumento=*"+form.fTdocumento.value+"*";
	if(form.checkFechaRecibido.checked){
		var fdesde = new String (document.getElementById("fdesde").value);
		var fhasta = new String (document.getElementById("fhasta").value);
		var fdesde = fdesde.split("-");
		fd = fdesde[2]+"-"+fdesde[1]+"-"+fdesde[0];
		
		var fhasta = fhasta.split("-"); 
		fh = fhasta[2]+"-"+fhasta[1]+"-"+fhasta[0];
		
	  filtro+=" and cpdist.FechaEnvio>=*"+fd+"*"+" and cpdist.FechaEnvio <=*"+fh+"*";
	}else{
	   fd = ano+"-"+"01-01";// alert(fd); 
	   if(dia<10) dia="0"+dia;
	   fh = ano+"-"+mes+"-"+dia;//alert(fh);
	   filtro+=" and cpdist.FechaEnvio>=*"+fd+"*"+" and cpdist.FechaEnvio <=*"+fh+"*";
	}
	if(form.chkRegInt.checked) filtro+=" and cpdist.Cod_Documento=*"+form.fRegInt.value+"*";
	if(form.checkEstado.checked) filtro+=" and cpdist.Estado=*"+form.fEstado.value+"*";
		
	var pagina="rp_entradadistdetallepdf.php?filtro="+filtro+"&CodDependencia="+CodDependencia;
			cargarPagina(form, pagina);

}
//// ---------------------------------------------------------------------------------------
//// FUNCION QUE PERMITE ENVIAR DATOS Y CARGAR EN PANTALLA EL REPORTE
function filtroDocumentosSalida(form, limit) {
	
	var today = new Date(); //alert('today='+today);
	var dia = today.getDate(); //alert('diames='+ diames);
	var mes=today.getMonth() +1 ; //alert('mes='+ mes);
	var ano=today.getFullYear(); //alert('ano='+ ano);
	var fh=""; var fd=""; //var anio="";
	
	var filtro=""; var CodDepExt =""; 
	
	if(form.checkorganismos.checked) filtro+=" and cpsal.CodOrganismo=*"+form.forganismo.value+"*";
	if(form.checkDestinatario.checked) CodDepExt = document.getElementById("fDestinatario").value; // Tabla cp_documentodistribucionext
	if(form.checkElaborado.checked) filtro+=" and cpsal.Cod_Dependencia=*"+form.fElaboradoPor.value+"*"; // Tabla cp_documentoextsalida
	if(form.checkTdocumento.checked) filtro+=" and cpsal.Cod_TipoDocumento=*"+form.fTdocumento.value+"*";
	if(form.checkEstado.checked) filtro+=" and cpsal.Estado=*"+form.fEstado.value+"*";
	if(form.checkFechaRegistro.checked){
		var fdesde = new String (document.getElementById("fdesde").value);
		var fhasta = new String (document.getElementById("fhasta").value);
		var fdesde = fdesde.split("-");
		fd = fdesde[2]+"-"+fdesde[1]+"-"+fdesde[0];
		
		var fhasta = fhasta.split("-"); 
		fh = fhasta[2]+"-"+fhasta[1]+"-"+fhasta[0];
		
	   filtro+=" and FechaRegistro>=*"+fd+"*"+" and FechaRegistro <=*"+fh+"*";
	}else{
	   fd = ano+"-"+"01-01";// alert(fd); 
	   if(dia<10) dia="0"+dia;
	   fh = ano+"-"+mes+"-"+dia;//alert(fh);
	   filtro+=" and FechaRegistro>=*"+fd+"*"+" and FechaRegistro <=*"+fh+"*";
	}
			
	var pagina="rp_documentosexternoslistapdf.php?filtro="+filtro+"&CodDepExt="+CodDepExt;
			cargarPagina(form, pagina);
}
//// ---------------------------------------------------------------------------------------
////
function enabledFechaRegistro(form){
	if (form.checkFechaRegistro.checked) {form.fdesde.disabled=false; form.fhasta.disabled=false;} 
	else{ form.fdesde.disabled=true; form.fhasta.disabled=true; form.fhasta.value=""; form.fdesde.value="";} 
}
function enabledNroDocumentoRp(form){
 if(form.chkNroDocumento.checked) form.fNroDocumento.disabled = false; 
 else {form.fNroDocumento.disabled = true; form.fNroDocumento.value = '';}
}
//// ---------------------------------------------------------------------------------------
//// 
function filtroDocumentoSalidaExtRP(form, limit){

var today = new Date(); //alert('today='+today);
var dia = today.getDate(); //alert('diames='+ diames);
var mes=today.getMonth() +1 ; //alert('mes='+ mes);
var ano=today.getFullYear(); //alert('ano='+ ano);
var fh=""; var fd=""; //var anio="";
	
var filtro=""; var CodDepExt =""; 
	
	if(form.checkorganismos.checked) filtro+=" and cpsal.CodOrganismo=*"+form.forganismo.value+"*";
	if(form.checkElaborado.checked) filtro+=" and cpsal.Cod_Dependencia=*"+form.fElaboradoPor.value+"*"; // Tabla cp_documentoextsalida
	if(form.chkNroDocumento.checked) filtro+=" and cpsal.Cod_DocumentoCompleto=*"+form.fNroDocumento.value+"*";
	if(form.checkTdocumento.checked) filtro+=" and cpsal.Cod_TipoDocumento=*"+form.fTdocumento.value+"*";
	if(form.checkEstado.checked) filtro+=" and cpsal.Estado=*"+form.fEstado.value+"*";
	if(form.checkFechaRegistro.checked){
		var fdesde = new String (document.getElementById("fdesde").value);
		var fhasta = new String (document.getElementById("fhasta").value);
		var fdesde = fdesde.split("-");
		fd = fdesde[2]+"-"+fdesde[1]+"-"+fdesde[0];
		
		var fhasta = fhasta.split("-"); 
		fh = fhasta[2]+"-"+fhasta[1]+"-"+fhasta[0];
		
	   filtro+=" and FechaRegistro>=*"+fd+"*"+" and FechaRegistro <=*"+fh+"*";
	}else{
	   fd = ano+"-"+"01-01";// alert(fd); 
	   if(dia<10) dia="0"+dia;
	   fh = ano+"-"+mes+"-"+dia;//alert(fh);
	   filtro+=" and FechaRegistro>=*"+fd+"*"+" and FechaRegistro <=*"+fh+"*";
	}
	//if(form.checkDestinatario.checked) CodDepExt = document.getElementById("fDestinatario").value; // Tabla cp_documentodistribucionext
	
	var pagina="rp_documentosexternosdistsalidapdf.php?filtro="+filtro+"&CodDepExt="+CodDepExt;
			cargarPagina(form, pagina);
}
//// ---------------------------------------------------------------------------------------
function filtroDocumentoSalidaDistribucionRP(form, limit){

var today = new Date(); //alert('today='+today);
var dia = today.getDate(); //alert('diames='+ diames);
var mes=today.getMonth() +1 ; //alert('mes='+ mes);
var ano=today.getFullYear(); //alert('ano='+ ano);
var fh=""; var fd=""; //var anio="";
	
var filtro=""; var CodDepExt =""; var filtro2=""; var bandera="";
	
	if(form.checkorganismos.checked) filtro+=" and cpsal.CodOrganismo=*"+form.forganismo.value+"*";
	if(form.checkElaborado.checked) filtro+=" and cpsal.Cod_Dependencia=*"+form.fElaboradoPor.value+"*"; // Tabla cp_documentoextsalida
	if(form.chkNroDocumento.checked) filtro+=" and cpsal.Cod_DocumentoCompleto=*"+form.fNroDocumento.value+"*";
	if(form.checkTdocumento.checked) filtro+=" and cpsal.Cod_TipoDocumento=*"+form.fTdocumento.value+"*";
	if(form.checkEstado.checked) filtro2+=" and Estado=*"+form.fEstado.value+"*";
	if(form.checkDestinatario.checked){ filtro2+=" and Cod_Organismos=*"+form.fDestinatario.value+"*"; bandera = 1;}
	if(form.checkPartDest.checked){ filtro2+=" and Cod_Organismos=*"+form.fPartDest.value+"*"; bandera = 2;}
	if(form.checkElaborado.checked) filtro+=" and cpsal.Cod_Dependencia=*"+form.fElaboradoPor.value+"*";
	if(form.checkFechaRegistro.checked){
		var fdesde = new String (document.getElementById("fdesde").value);
		var fhasta = new String (document.getElementById("fhasta").value);
		var fdesde = fdesde.split("-");
		fd = fdesde[2]+"-"+fdesde[1]+"-"+fdesde[0];
		
		var fhasta = fhasta.split("-"); 
		fh = fhasta[2]+"-"+fhasta[1]+"-"+fhasta[0];
		
	   filtro+=" and FechaRegistro>=*"+fd+"*"+" and FechaRegistro <=*"+fh+"*";
	}else{
	   fd = ano+"-"+"01-01";// alert(fd); 
	   if(dia<10) dia="0"+dia;
	   fh = ano+"-"+mes+"-"+dia;//alert(fh);
	   filtro+=" and FechaRegistro>=*"+fd+"*"+" and FechaRegistro <=*"+fh+"*";
	}
	
	var pagina="rp_docsalidadistribucionpdf.php?filtro="+filtro+"&CodDepExt="+CodDepExt+"&filtro2="+filtro2+"&bandera="+bandera;
			cargarPagina(form, pagina);
}
//// ---------------------------------------------------------------------------------------
function filtroSalidaHistRP(form, limit){

var today = new Date(); //alert('today='+today);
var dia = today.getDate(); //alert('diames='+ diames);
var mes=today.getMonth() +1 ; //alert('mes='+ mes);
var ano=today.getFullYear(); //alert('ano='+ ano);
var fh=""; var fd=""; //var anio="";
	
var filtro=""; var CodDepExt =""; var filtro2=""; var bandera="";
	
	if(form.checkorganismos.checked) filtro+=" and cpsal.CodOrganismo=*"+form.forganismo.value+"*";
	if(form.checkElaborado.checked) filtro+=" and cpsal.Cod_Dependencia=*"+form.fElaboradoPor.value+"*"; // Tabla cp_documentoextsalida
	if(form.chkNroDocumento.checked) filtro+=" and cpsal.Cod_DocumentoCompleto=*"+form.fNroDocumento.value+"*";
	if(form.checkTdocumento.checked) filtro+=" and cpsal.Cod_TipoDocumento=*"+form.fTdocumento.value+"*";
	//if(form.checkEstado.checked) filtro2+=" and Estado=*"+form.fEstado.value+"*";
	if(form.checkDestinatario.checked){ filtro2+=" and Cod_Organismos=*"+form.fDestinatario.value+"*"; bandera = 1;}
	if(form.checkPartDest.checked){ filtro2+=" and Cod_Organismos=*"+form.fPartDest.value+"*"; bandera = 2;}
	if(form.checkElaborado.checked) filtro+=" and cpsal.Cod_Dependencia=*"+form.fElaboradoPor.value+"*";
	if(form.checkFechaRegistro.checked){
		var fdesde = new String (document.getElementById("fdesde").value);
		var fhasta = new String (document.getElementById("fhasta").value);
		var fdesde = fdesde.split("-");
		fd = fdesde[2]+"-"+fdesde[1]+"-"+fdesde[0];
		
		var fhasta = fhasta.split("-"); 
		fh = fhasta[2]+"-"+fhasta[1]+"-"+fhasta[0];
		
	   filtro+=" and FechaRegistro>=*"+fd+"*"+" and FechaRegistro <=*"+fh+"*";
	}else{
	   fd = ano+"-"+"01-01";// alert(fd); 
	   if(dia<10) dia="0"+dia;
	   fh = ano+"-"+mes+"-"+dia;//alert(fh);
	   filtro+=" and FechaRegistro>=*"+fd+"*"+" and FechaRegistro <=*"+fh+"*";
	}
	
	var pagina="rp_docsalidahistxdocpdf.php?filtro="+filtro+"&CodDepExt="+CodDepExt+"&filtro2="+filtro2+"&bandera="+bandera;
			cargarPagina(form, pagina);
}
//// ---------------------------------------------------------------------------------------
//// ACTIVAR Y DESACTIVAR CAMPO
function enabledDepDestinataria(form){
 if(form.checkDestinataria.checked) form.fDestinataria.disabled=false;
 else{form.fDestinataria.disabled=true; form.fDestinataria.value='';}
}
//// ---------------------------------------------------------------------------------------
//// FUNCION QUE PERMITE ENVIAR DATOS Y CARGAR EN PANTALLA EL REPORTE
function filtroDocumentoInternoRP(form, limit) {
	
	var today = new Date(); //alert('today='+today);
	var dia = today.getDate(); //alert('diames='+ diames);
	var mes=today.getMonth() +1 ; //alert('mes='+ mes);
	var ano=today.getFullYear(); //alert('ano='+ ano);
	var fh=""; var fd=""; //var anio="";
	
	var filtro=""; var filtro2 = "";
	
	if(form.checkorganismos.checked) filtro+=" and cpint.CodOrganismo=*"+form.forganismo.value+"*";
	if(form.checkRemitente.checked) filtro+=" and cpint.Cod_Dependencia=*"+form.fremitente.value+"*"; // Tabla cp_documentointerno 
	if(form.checkDestinataria.checked) filtro2+=" and CodDependencia=*"+form.fDestinataria.value+"*";// Tabla cp_documentodistribucion
	if(form.checkFechaRegistro.checked){
		var fdesde = new String (document.getElementById("fdesde").value);
		var fhasta = new String (document.getElementById("fhasta").value);
		var fdesde = fdesde.split("-");
		fd = fdesde[2]+"-"+fdesde[1]+"-"+fdesde[0];
		
		var fhasta = fhasta.split("-"); 
		fh = fhasta[2]+"-"+fhasta[1]+"-"+fhasta[0];
		
	   filtro+=" and cpint.FechaRegistro>=*"+fd+"*"+" and cpint.FechaRegistro<=*"+fh+"*";
	}else{
	   fd = ano+"-"+"01-01";// alert(fd); 
	   if(dia<10) dia="0"+dia;
	   fh = ano+"-"+mes+"-"+dia;//alert(fh);
	   filtro+=" and cpint.FechaRegistro>=*"+fd+"*"+" and cpint.FechaRegistro<=*"+fh+"*";
	}
	if(form.checkEstado.checked) filtro+=" and cpint.Estado=*"+form.fEstado.value+"*";
	if(form.checkTdocumento.checked) filtro+=" and cpint.Cod_TipoDocumento=*"+form.fTdocumento.value+"*";
	if(form.checkNroDocumento.checked) filtro+=" and cpint.Cod_DocumentoCompleto=*"+form.fNroDocumento.value+"*";
				
	var pagina="rp_documentosinternoslistapdf.php?filtro="+filtro+"&filtro2="+filtro2;
			cargarPagina(form, pagina);
}
//// ---------------------------------------------------------------------------------------
//// ---------------------------------------------------------------------------------------
//// FUNCION QUE PERMITE ENVIAR DATOS Y CARGAR EN PANTALLA EL REPORTE
function filtroDocumentoInternoDistXDocRP(form, limit) {
	
	var today = new Date(); //alert('today='+today);
	var dia = today.getDate(); //alert('diames='+ diames);
	var mes=today.getMonth() +1 ; //alert('mes='+ mes);
	var ano=today.getFullYear(); //alert('ano='+ ano);
	var fh=""; var fd=""; //var anio="";
	
	var filtro=""; var filtro2 = "";
	
	if(form.checkorganismos.checked) filtro+=" and cpint.CodOrganismo=*"+form.forganismo.value+"*";
	if(form.checkRemitente.checked) filtro+=" and cpint.Cod_Dependencia=*"+form.fremitente.value+"*"; // Tabla cp_documentointerno 
	//if(form.checkDestinataria.checked) filtro2+=" and CodDependencia=*"+form.fDestinataria.value+"*";// Tabla cp_documentodistribucion
	if(form.checkFechaRegistro.checked){
		var fdesde = new String (document.getElementById("fdesde").value);
		var fhasta = new String (document.getElementById("fhasta").value);
		var fdesde = fdesde.split("-");
		fd = fdesde[2]+"-"+fdesde[1]+"-"+fdesde[0];
		
		var fhasta = fhasta.split("-"); 
		fh = fhasta[2]+"-"+fhasta[1]+"-"+fhasta[0];
		
	   filtro+=" and cpint.FechaRegistro>=*"+fd+"*"+" and cpint.FechaRegistro<=*"+fh+"*";
	}else{
	   fd = ano+"-"+"01-01";// alert(fd); 
	   if(dia<10) dia="0"+dia;
	   fh = ano+"-"+mes+"-"+dia;//alert(fh);
	   filtro+=" and cpint.FechaRegistro>=*"+fd+"*"+" and cpint.FechaRegistro<=*"+fh+"*";
	}
	if(form.checkEstado.checked) filtro+=" and cpint.Estado=*"+form.fEstado.value+"*";
	if(form.checkTdocumento.checked) filtro+=" and cpint.Cod_TipoDocumento=*"+form.fTdocumento.value+"*";
	if(form.checkNroDocumento.checked) filtro+=" and cpint.Cod_DocumentoCompleto=*"+form.fNroDocumento.value+"*";
				
	var pagina="rp_documentosinternosdistxdocpdf.php?filtro="+filtro+"&filtro2="+filtro2;
			cargarPagina(form, pagina);
}
//// ---------------------------------------------------------------------------------------
//// FUNCION QUE PERMITE ENVIAR DATOS Y CARGAR EN PANTALLA EL REPORTE
function filtroDocumentoInternoDistribucionRP(form, limit) {
	
	var today = new Date(); //alert('today='+today);
	var dia = today.getDate(); //alert('diames='+ diames);
	var mes=today.getMonth() +1 ; //alert('mes='+ mes);
	var ano=today.getFullYear(); //alert('ano='+ ano);
	var fh=""; var fd=""; //var anio="";
	
	var filtro=""; var filtro2 = "";
	
	if(form.checkorganismos.checked) filtro+=" and cpint.CodOrganismo=*"+form.forganismo.value+"*";
	if(form.checkRemitente.checked) filtro+=" and cpint.Cod_Dependencia=*"+form.fremitente.value+"*"; // Tabla cp_documentointerno 
	if(form.checkDestinataria.checked) filtro2+=" and cpd.CodDependencia=*"+form.fDestinataria.value+"*";// Tabla cp_documentodistribucion
	if(form.checkFechaRegistro.checked){
		var fdesde = new String (document.getElementById("fdesde").value);
		var fhasta = new String (document.getElementById("fhasta").value);
		var fdesde = fdesde.split("-");
		fd = fdesde[2]+"-"+fdesde[1]+"-"+fdesde[0];
		
		var fhasta = fhasta.split("-"); 
		fh = fhasta[2]+"-"+fhasta[1]+"-"+fhasta[0];
		
	   filtro+=" and cpint.FechaRegistro>=*"+fd+"*"+" and cpint.FechaRegistro<=*"+fh+"*";
	}else{
	   fd = ano+"-"+"01-01";// alert(fd); 
	   if(dia<10) dia="0"+dia;
	   fh = ano+"-"+mes+"-"+dia;//alert(fh);
	   filtro+=" and cpint.FechaRegistro>=*"+fd+"*"+" and cpint.FechaRegistro<=*"+fh+"*";
	}
	if(form.checkEstado.checked) filtro2+=" and cpd.Estado=*"+form.fEstado.value+"*";
	if(form.checkTdocumento.checked) filtro+=" and cpint.Cod_TipoDocumento=*"+form.fTdocumento.value+"*";
	if(form.checkNroDocumento.checked) filtro+=" and cpint.Cod_DocumentoCompleto=*"+form.fNroDocumento.value+"*";
				
	var pagina="rp_documentosinternosdistribucionpdf.php?filtro="+filtro+"&filtro2="+filtro2;
			cargarPagina(form, pagina);
}
//// ---------------------------------------------------------------------------------------
//// ---- FUNCION QUE PERMITE MOTRAR REPORTE 
function filtroDocSalidaXMensajeroRP(form, limit){

var today = new Date(); //alert('today='+today);
var dia = today.getDate(); //alert('diames='+ diames);
var mes=today.getMonth() +1 ; //alert('mes='+ mes);
var ano=today.getFullYear(); //alert('ano='+ ano);
var fh=""; var fd=""; //var anio="";
	
var filtro=""; var CodDepExt =""; var filtro2=""; var bandera="";
	
	if(form.checkorganismos.checked) filtro+=" and cpsal.CodOrganismo=*"+form.forganismo.value+"*";
	if(form.checkElaborado.checked) filtro+=" and  cpsal.Cod_Dependencia=*"+form.fElaboradoPor.value+"*"; // Tabla cp_documentoextsalida
	if(form.chkNroDocumento.checked) filtro+=" and cpsal.Cod_DocumentoCompleto=*"+form.fNroDocumento.value+"*";
	if(form.checkTdocumento.checked) filtro+=" and cpsal.Cod_TipoDocumento=*"+form.fTdocumento.value+"*";
	if(form.checkEstado.checked) filtro2+=" and cpsal.Estado=*"+form.fEstado.value+"*";
	if(form.checkFechaRegistro.checked){
		var fdesde = new String (document.getElementById("fdesde").value);
		var fhasta = new String (document.getElementById("fhasta").value);
		var fdesde = fdesde.split("-");
		fd = fdesde[2]+"-"+fdesde[1]+"-"+fdesde[0];
		
		var fhasta = fhasta.split("-"); 
		fh = fhasta[2]+"-"+fhasta[1]+"-"+fhasta[0];
		
	   filtro+=" and FechaRegistro>=*"+fd+"*"+" and FechaRegistro <=*"+fh+"*";
	}else{
	   fd = ano+"-"+"01-01";// alert(fd); 
	   if(dia<10) dia="0"+dia;
	   fh = ano+"-"+mes+"-"+dia;//alert(fh);
	   filtro+=" and FechaRegistro>=*"+fd+"*"+" and FechaRegistro <=*"+fh+"*";
	}
	
	var pagina="rp_docsalidamensajeropdf.php?filtro="+filtro+"&CodDepExt="+CodDepExt+"&filtro2="+filtro2+"&bandera="+bandera;
			cargarPagina(form, pagina);
}
//// ---------------------------------------------------------------------------------------
