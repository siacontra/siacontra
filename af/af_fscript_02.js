// JavaScript Document
//// ---------------------------------------------------------------------------------------------
//// 			FUNCION QUE PERMITE ACTIVAR RADIOS
//// ---------------------------------------------------------------------------------------------
function chekeador(form,id){ 
  if(id=="radio2"){ 
     form.radio1.checked=false;
	 document.getElementById("radio").value = "I"; 
  }
  if(id=="radio1"){ 
    form.radio2.checked=false;
	document.getElementById("radio").value = "A"; 
  }
}
//// ---------------------------------------------------------------------------------------------
//// 	FUNCION QUE PERMITE ACTIVAR CAMPOS DE AF_ACTIVOSMENORES
//// ---------------------------------------------------------------------------------------------
function enabledUbicacionActivosMenores(form){
 if(form.checkUbicacion.checked){ 
    form.fubicacion2.disabled=false; form.btUbicacion.disabled = false; document.getElementById('ubicacionactivo').style.visibility='visible';
}else{ 
    form.fubicacion2.disabled=true; form.fubicacion2.value=''; form.btUbicacion.disabled = true; form.fubicacion.value=''; 
	document.getElementById('ubicacionactivo').style.visibility='hidden';}
}
//// ---------------------------------------------------------------------------------------------
//// ----------		FUNCION QUE PERMITE GUARDAR REGISTRO TRANSACCION BAJA
//// ---------------------------------------------------------------------------------------------
function guardarTransaccionBaja(form){ 
  
  var Activo= document.getElementById("nro_activo").value;
  var Organismo = document.getElementById("codorganismo").value;
  var TipoTransaccion = document.getElementById("tipobaja").value;
  var Dependencia = document.getElementById("coddependencia").value;
  var Fecha = document.getElementById("f_actual").value;
  var CentroCosto = document.getElementById("codcentrocosto").value;
  var Responsable = document.getElementById("codresponsable").value;
  var ConceptoMovimiento = document.getElementById("conceptoMovimiento").value;
  var CodigoInterno = document.getElementById("codigo_interno").value;
  var Categoria = document.getElementById("categoria").value;
  var Ubicacion = document.getElementById("codubicacion").value;
  var Comentario = document.getElementById("comentario").value;
   
  
  if(form.flagContabilizado.checked) var ContabilizadoFlag = 'S';

 var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=guardarTransaccionBaja&Activo="+Activo+"&Organismo="+Organismo+"&Dependencia="+Dependencia+"&TipoTransaccion="+TipoTransaccion+"&Fecha="+Fecha+"&CentroCosto="+CentroCosto+"&Responsable="+Responsable+"&ConceptoMovimiento="+ConceptoMovimiento+"&CodigoInterno="+CodigoInterno+"&Categoria="+Categoria+"&Ubicacion="+Ubicacion+"&Comentario="+Comentario);
  
  ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText.trim();
			if (resp != "")alert(resp); 
            else cargarPagina(form, document.getElementById("regresar").value+".php?limit=0");
		}
	}
	return false;
}
//// ---------------------------------------------------------------------------------------------
//// ----------		MOSTRAR REPORTE INVENTARIO X DEPENDENCIA
//// ---------------------------------------------------------------------------------------------
function cargarInventarioxDependencia(form){
 
	var filtro="";
	if(form.chkorganismo.checked) filtro+=" and a.CodOrganismo=*"+form.forganismo.value+"*";
	if(form.chkCatClasf.checked) filtro+=" and a.Clasificacion LIKE *"+form.fClasificacion.value+"%"+"*";
	if(form.checkUbicacion.checked) filtro+=" and a.Ubicacion LIKE *"+form.fubicacion.value+"%"+"*";
	if(form.checkDependencia.checked) filtro+=" and a.CodDependencia=*"+form.fDependencia.value+"*";
	if(form.chkclasificacionpub20.checked) filtro+=" and a.ClasificacionPublic20 LIKE *"+form.fCodclasficacionPub20.value+"%"+"*";
	if(form.checkSituacionActivo.checked) filtro+=" and a.SituacionActivo=*"+form.fSituacionActivo.value+"*";
	if(form.chekcCosto.checked) filtro+=" and a.CentroCosto=*"+form.centro_costos.value+"*";
	if(form.chkEstado.checked) filtro+=" and a.Estado=*"+form.fEstado.value+"*";
	if(form.chkBienes.checked) filtro+=" and a.ClasificacionPublic20 LIKE *"+form.fBienes.value+"%"+"*";
	
    var pagina_mostrar="af_rpinventarioxdependenciapdf.php?filtro="+filtro;
        form.target = "af_rpinventarioxdependenciapdf";				
				cargarPagina(form, pagina_mostrar);
}
//// ---------------------------------------------------------------------------------------------
//// ----------		MOSTRAR REPORTE INVENTARIO ACTIVOS COSTO
//// ---------------------------------------------------------------------------------------------
function cargarInventarioActivosLista(form){

	var filtro="";
	if(form.chkorganismo.checked) filtro+=" and a.CodOrganismo=*"+form.forganismo.value+"*";
	if(form.chkCatClasf.checked) filtro+=" and a.Clasificacion LIKE *"+form.fClasificacion.value+"%"+"*";
	if(form.checkUbicacion.checked) filtro+=" and a.Ubicacion LIKE *"+form.ubicacion.value+"%"+"*";
	if(form.checkDependencia.checked) filtro+=" and a.CodDependencia=*"+form.fDependencia.value+"*";
	if(form.chkclasificacionpub20.checked) filtro+=" and a.ClasificacionPublic20 LIKE *"+form.fCodclasficacionPub20.value+"%"+"*";
	if(form.checkSituacionActivo.checked) filtro+=" and a.SituacionActivo=*"+form.fSituacionActivo.value+"*";
	if(form.chekcCosto.checked) filtro+=" and a.CentroCosto=*"+form.centro_costos.value+"*";
	if(form.chkEstado.checked) filtro+=" and a.Estado=*"+form.fEstado.value+"*";
	if(form.chkBienes.checked) filtro+=" and a.ClasificacionPublic20 LIKE *"+form.fBienes.value+"%"+"*";
	
    var pagina_mostrar="af_rpinventarioactivoscostopdf.php?filtro="+filtro;
        form.target = "af_rpinventarioactivoscostopdf";				
				cargarPagina(form, pagina_mostrar);
}
//// ---------------------------------------------------------------------------------------------
//// ----------		MOSTRAR REPORTE INVENTARIO ACTIVOS COSTO
//// ---------------------------------------------------------------------------------------------
function cargarFormularioBM_1(form){

	var filtro="";
	if(form.chkorganismo.checked) filtro+=" and a.CodOrganismo=*"+form.forganismo.value+"*";
	if(form.chkCatClasf.checked) filtro+=" and a.Clasificacion LIKE *"+form.fClasificacion.value+"%"+"*";
	if(form.checkUbicacion.checked) filtro+=" and a.Ubicacion LIKE *"+form.fubicacion.value+"%"+"*";
	if(form.checkDependencia.checked) filtro+=" and a.CodDependencia=*"+form.fDependencia.value+"*";
	if(form.chkclasificacionpub20.checked) filtro+=" and a.ClasificacionPublic20 LIKE *"+form.fClasificacionPub20.value+"%"+"*";
	if(form.checkSituacionActivo.checked) filtro+=" and a.SituacionActivo=*"+form.fSituacionActivo.value+"*";
	if(form.chekcCosto.checked) filtro+=" and a.CentroCosto=*"+form.centro_costos.value+"*";
	if(form.chkEstado.checked) filtro+=" and a.Estado=*"+form.fEstado.value+"*";
	if(form.chkBienes.checked) filtro+=" and a.ClasificacionPublic20 LIKE *"+form.fBienes.value+"%"+"*";
	
    var pagina_mostrar="af_rpformulariobm_1pdf.php?filtro="+filtro;
        form.target = "af_rpformulariobm_1pdf";				
				cargarPagina(form, pagina_mostrar);
}
function cargarFormularioBM_2(form){

	var filtro="";
	if(form.chkorganismo.checked) filtro+=" and a.CodOrganismo=*"+form.forganismo.value+"*";
	if(form.chkCatClasf.checked) filtro+=" and a.Clasificacion LIKE *"+form.fClasificacion.value+"%"+"*";
	if(form.checkUbicacion.checked) filtro+=" and a.Ubicacion LIKE *"+form.fubicacion.value+"%"+"*";
	if(form.checkDependencia.checked) filtro+=" and a.CodDependencia=*"+form.fDependencia.value+"*";
	if(form.chkclasificacionpub20.checked) filtro+=" and a.ClasificacionPublic20 LIKE *"+form.fCodclasficacionPub20.value+"%"+"*";
	if(form.checkSituacionActivo.checked) filtro+=" and a.SituacionActivo=*"+form.fSituacionActivo.value+"*";
	if(form.chekcCosto.checked) filtro+=" and a.CentroCosto=*"+form.centro_costos.value+"*";
	if(form.chkEstado.checked) filtro+=" and a.Estado=*"+form.fEstado.value+"*";
	if(form.chkBienes.checked) filtro+=" and a.ClasificacionPublic20 LIKE *"+form.fBienes.value+"%"+"*";
	
    var pagina_mostrar="af_rpformulariobm_2pdf.php?filtro="+filtro;
        form.target = "af_rpformulariobm_2pdf";				
				cargarPagina(form, pagina_mostrar);
}

function cargarEtiquetaActivos(form){

	var filtro="";
	if(form.chkorganismo.checked) filtro+=" and a.CodOrganismo=*"+form.forganismo.value+"*";
	if(form.chkCatClasf.checked) filtro+=" and a.Clasificacion LIKE *"+form.fClasificacion.value+"%"+"*";
	if(form.checkUbicacion.checked) filtro+=" and a.Ubicacion LIKE *"+form.fubicacion.value+"%"+"*";
	if(form.checkDependencia.checked) filtro+=" and a.CodDependencia=*"+form.fDependencia.value+"*";
	if(form.chkclasificacionpub20.checked) filtro+=" and a.ClasificacionPublic20 LIKE *"+form.fCodclasficacionPub20.value+"%"+"*";
	if(form.checkSituacionActivo.checked) filtro+=" and a.SituacionActivo=*"+form.fSituacionActivo.value+"*";
	if(form.chekcCosto.checked) filtro+=" and a.CentroCosto=*"+form.centro_costos.value+"*";
	if(form.chkEstado.checked) filtro+=" and a.Estado=*"+form.fEstado.value+"*";
	if(form.chkBienes.checked) filtro+=" and a.ClasificacionPublic20 LIKE *"+form.fBienes.value+"%"+"*";
	
    var pagina_mostrar="af_etiquetaactivospdf.php?filtro="+filtro;
        form.target = "af_etiquetaactivospdf";				
				cargarPagina(form, pagina_mostrar);
}


//// ---------------------------------------------------------------
///           
//// ---------------------------------------------------------------
/*function Distribucion(form,id){
	var distribucion = document.getElementById(id).value;
	    document.getElementById("distribucion").value= distribucion;
	if(distribucion!=""){
		//document.getElementById("mostrar").style.visibility= "visible";
		var visible = 'style="visibility:visible"';
		var tipobaja = document.getElementById("tipobaja").value;
		cargarPagina(form,"af_bajactivosnuevo.php?distribucion="+distribucion+"&visible="+visible+"&tipobaja="+tipobaja);
		
	}else document.getElementById("mostrar").style.visibility= "hidden";	
		
}*/
//// ---------------------------------------------------------------------------------------------
//// ----------		ACTIVAR TABLA DE FORMULARIO
//// ---------------------------------------------------------------------------------------------
function ActivarTable(form,valor){
	var valor = document.getElementById(valor).value; //alert(valor);
if(valor!=""){
  document.getElementById("mostrar").style.visibility = 'visible';
  document.getElementById("scrool").style.display = 'block';
}else{ 
   document.getElementById("mostrar").style.visibility = 'hidden'; 
   document.getElementById("scrool").style.display = 'none';
}
}
//// --------------------------------------------------------------------------------------------
function formatoMoneda(fld, milSep,decSep, e){
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
}