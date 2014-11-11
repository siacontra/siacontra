/// --------------------------------------------------------------------------------
/// FUNCION QUE PERMITE ACTIVAR TD EN CLASIFICACION ACTIVO 20 NUEVO
function activarVisible(form, idSelectOrigen, idSelectDestino){
    var nivel = document.getElementById("nivel").value; //alert('nivel='+nivel);
    var selectOrigen=document.getElementById(idSelectOrigen); //alert('origen='+selectOrigen);
	var optSelectOrigen=selectOrigen.options[selectOrigen.selectedIndex].value;
	var selectDestino=document.getElementById(idSelectDestino); //alert('destino='+selectDestino);
	
   if(nivel>'1'){
	    document.getElementById('cod2').style.display = 'block';
		document.getElementById('cod1').style.display = 'none';
		
	if (optSelectOrigen=="") {
		selectDestino.length=0;
		nuevaOpcion=document.createElement("option");
		nuevaOpcion.value="";
		nuevaOpcion.innerHTML="";
		selectDestino.appendChild(nuevaOpcion);
		selectDestino.disabled=true;
	} else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "af_fphp.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=activarVisible&tabla="+idSelectDestino+"&opcion="+optSelectOrigen+"&nivel="+nivel);
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
		
   }else{
	    document.getElementById('cod2').style.display = 'none';
		document.getElementById('cod1').style.display = 'block';
	}
}
/// --------------------------------------------------------------------------------
/// -------------------		FUNCION CARGAR CLASIFICACION 20
/// --------------------------------------------------------------------------------
function cargarSeelctCodClasificacion20(form){
	var valor = document.getElementById("valorNivel").value; //alert(valor);
  var ajax=nuevoAjax();
	  ajax.open("POST", "af_fphp.php", true);
	  ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	  ajax.send("accion=cargarSeelctCodClasificacion20&valor="+valor);
	  ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText.trim();
			if (resp != "")alert(resp); 
            else cargarPagina(form, form.action);
			/*var error=ajax.responseText;
			if (error!=0) alert ("¡"+error+"!");
			else cargarPagina(form, form.action);*/
		}
	 }
}
///	--------------------------------------------------------------------------------
///	-------------------		CLASIFICACION DE ACTIVOS
///	--------------------------------------------------------------------------------
function verificarTipoMovimiento(form, accion) {
	var codigo = document.getElementById("codigo").value;
	var descripcion = document.getElementById("descripcion").value;
	var t_movimiento = document.getElementById("t_movimiento").value;
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
		
	if (descripcion == "" || descripcion.length<2) alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else if (!valNumerico(codigo)) alert("¡El código debe ser númerico!");
	else if (!valAlfanumerico(descripcion)) alert("¡No se permiten caracteres especiales en el campo descripción!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "af_fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=GuardarTipoMovimiento"+"&codigo="+codigo+"&descripcion="+descripcion+"&t_movimiento="+t_movimiento+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText.trim();
				if (resp != "") alert(resp);
				else cargarPagina(form, "af_tipomovimientoactivo.php");
			}
		}
	}
	return false;
}
///	--------------------------------------------------------------------------------
/// -------------------		Filtro lista de Activos "af_lisactivos"
///	--------------------------------------------------------------------------------
function enabledClasificacionPub20(form){ 
  if(form.chkclasificacionpub20.checked){ 
     form.fClasificacionPub20.disabled = false; 
	 form.btClasifPub20.disabled = false; document.getElementById("clasificacion20").style.visibility = 'visible';
  }else{ 
     form.fClasificacionPub20.disabled=true; 
	 form.fClasificacionPub20.value=''; 
	 form.fCodclasficacionPub20.value='';
	 form.btClasifPub20.disabled = true; document.getElementById("clasificacion20").style.visibility = 'hidden';
  }
}
///	--------------------------------------------------------------------------------
/// -------------------	FILTRO MOVIMIENTOS DE ACTIVOS "af_selectoractivos"
///	--------------------------------------------------------------------------------
function enabledCentroCostosSelectorActivos(form){
  if(form.checkCentroCosto.checked){ 
     form.btCentroCosto.disabled=false; form.fCentroCosto2.disabled= false;
  }else{
	 form.btCentroCosto.disabled=true; form.fCentroCosto.value=""; form.fCentroCosto2.value="";  form.fCentroCosto2.disabled=true;
  }
}
///	--------------------------------------------------------------------------------
function enabledPersonaSelecActivo(form){
  if(form.checkPersona.checked){
	  form.btpersona.disabled=false; form.NombPersona.disabled=false;
  }else{ 
     form.btpersona.disabled=true; form.fPersona.value=""; form.NombPersona.value=""; form.NombPersona.disabled=true;
  }
}
///	--------------------------------------------------------------------------------
function enabledUbicacionSelectorActivo(form){
  if(form.checkUbicacion.checked){
	  form.btUbicacion.disabled=false; form.DescpUbicacion.disabled=false;
  }else{
	  form.btUbicacion.disabled=true; form.DescpUbicacion.disabled=true; form.fUbicacion.value=""; form.DescpUbicacion.value="";  
  }
}
///	--------------------------------------------------------------------------------
function enabledNaturalezaSelectorActivo(form){
 if(form.checkNaturaleza.checked) form.fNaturaleza.disabled=false;
 else{form.fNaturaleza.disabled=true; form.fNaturaleza.value="";}
}
///	--------------------------------------------------------------------------------
function enabledConsolidadoSelectorActivo(form){
 if(form.checkConsolidado.checked){
	 form.fNomConsolidado.disabled=false; form.btConsolidado.disabled=false;
 }else{
    form.fNomConsolidado.disabled=true; form.btConsolidado.disabled=true; form.fConsolidado.value="";form.fNomConsolidado.value="";
 }
}
//// -------------------------------------------------------------------------------
//// -------------------	GUARDAR APROBAR MOVIMIENTO DE ACTIVOS
//// -------------------------------------------------------------------------------
function guardarAprobarMovimientoActivo(form){
 
 var Activo = document.getElementById("activo").value;
 var CentroCosto = document.getElementById("c_costosActual").value;
 var Ubicacion = document.getElementById("ubicacion_Actual").value;
 var EmpleadoUsuario = document.getElementById("e_usuarioActual").value;
 var EmpleadoResponsable = document.getElementById("e_responsableActual").value;
 var CodDependencia = document.getElementById("dependenciaActual").value;
 var CodOrganismo = document.getElementById("organismoActual").value;
 var Organismo = document.getElementById("fOrganismo").value;
 var PreparadoPor = document.getElementById("preparado_por").value; 
 var MovimientoNumero = document.getElementById("fmovimiento").value;
	   
     var ajax=nuevoAjax();
	 ajax.open("POST", "gmactivofijo.php", true);
	 ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	 ajax.send("accion=guardarAprobarMovimientoActivo&Activo="+Activo+"&CentroCosto="+CentroCosto+"&Ubicacion="+Ubicacion+"&EmpleadoUsuario="+EmpleadoUsuario+"&EmpleadoResponsable="+EmpleadoResponsable+"&CodDependencia="+CodDependencia+"&CodOrganismo="+CodOrganismo+"&PreparadoPor="+PreparadoPor+"&MovimientoNumero="+MovimientoNumero+"&Organismo="+Organismo);
	 
	 
     ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText.trim();
			if (resp != ""){ 
			    if(resp==1)alert("EL USUARIO QUE PREPARO EL MOVIMIENTO DEBE SER DISTINTO AL MOMENTO DE APROBAR");
	            if(resp==2)alert("MOVIMIENTO APROBADO");
				opener.document.getElementById("frmentrada").submit();
				//opener.document.getElementById("form").submit();
				 window.open('af_rptransferenciaactivofijo.php?Activo='+Activo+'&Organismo='+Organismo,'', 'height=500, width=870, left=200, top=100, resizable=yes');
			     window.open('af_actaentregabm.php?Activo='+Activo+'&CodOrganismo='+Organismo,'', 'height=500, width=870, left=200, top=100, resizable=yes');
				 window.close();
			}//else{
			 
			  //window.close();
			//}
		}
	}
	 return false; 
}
//// -------------------------------------------------------------------------------
//// -------------------	CARGAR LINEA
//// -------------------------------------------------------------------------------
function enabledContabilidadBajaActivo(form){ 
  if(form.checkContabilidad.checked) form.fContabilidad.disabled = false;
  else{ form.fContabilidad.disabled=true; form.fContabilidad.value='';}
}
//// -------------------------------------------------------------------------------
function enabledActivo(form){
  if(form.checkActivo.checked) form.fActivo.disabled = false;
  else{ form.fActivo.disabled=true; form.fActivo.value='';}
}
//// -------------------------------------------------------------------------------
function enabledPeriodo(form){
  if(form.checkPeriodo.checked) form.fPeriodo.disabled= false;
  else{ form.fPeriodo.disabled=true; form.fPeriodo.value='';}
}
//// -------------------------------------------------------------------------------
function enabledFecha(form){
  if(form.checkFecha.checked) form.fFecha.disabled=false;
  else{ form.fFecha.disabled=true; form.fFecha.value='';}
}
//// -------------------------------------------------------------------------------
function enabledBienes(form){
  if (form.chkBienes.checked) form.fBienes.disabled=false;
  else{ form.fBienes.disabled=true; form.fBienes.value='';} 
}
//// -------------------------------------------------------------------------------
//// -------------------	ANULAR REGISTRO DE TRANSACCION DE ACTIVOS
//// -------------------------------------------------------------------------------
function anularRegistro(form, pagina,accion){
 var codigo=form.registro.value;
	if (codigo=="") msjError(1000);
	else{
	   var anular=confirm("¡Esta seguro de anular este permiso?");
	     if(anular){
	       var ajax=nuevoAjax();
			ajax.open("POST", "gmactivofijo.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("accion=accion&codigo="+codigo);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var error=ajax.responseText;
					if (error!=0) alert ("¡"+error+"!");
					else cargarPagina(form, pagina+"&limit="+limit);
				}
			}
	    
		 }
	}
}
//// -------------------------------------------------------------------------------
function enabledRPNaturaleza(form){
 if(form.chkNaturaleza.checked) form.fNaturaleza.disabled = false; 
 else{ form.fNaturaleza.disabled= true; form.fNaturaleza.value='';}
}
//// -------------------------------------------------------------------------------
function enabledRPActivo(form){
 if(form.chkActivo.checked){ 
     form.fActivo.disabled = false; 
	 form.fDescpActivo.disabled = false; 
	 document.getElementById("activo").style.visibility='visible';}
 else{ 
 	form.fActivo.disabled= true; 
	form.fDescpActivo.disabled = true; 
	form.fActivo.value=''; 
	form.fDescpActivo.value=''; 
	document.getElementById("activo").style.visibility='hidden';
	}
}
//// -------------------------------------------------------------------------------
function enabledRPFechaAprobacion(form){
 if(form.chkFAprobacion.checked){form.fFechaAprobacionDesde.disabled=false; form.fFechaAprobacionHasta.disabled=false;}
 else{form.fFechaAprobacionDesde.disabled=true; form.fFechaAprobacionHasta.disabled=true; form.fFechaAprobacionHasta.value=''; form.fFechaAprobacionDesde.value='';}
}
//// -------------------------------------------------------------------------------
function enabledRPFechaPreparacion(form){
 if(form.chkFPreparacion.checked){form.fFechaPreparacionDesde.disabled=false; form.fFechaPreparacionHasta.disabled=false;}
 else{form.fFechaPreparacionDesde.disabled=true; form.fFechaPreparacionHasta.disabled=true; form.fFechaPreparacionHasta.value=''; form.fFechaPreparacionDesde.value='';}	
}
//// -------------------------------------------------------------------------------
function enabledRPUbicacionActual(form){
if(form.chkubicacionActual.checked){ 
   form.fub_actual_descp.disabled=false; 
   form.y_o.disabled=false; 
}else{
   form.fub_actual_descp.disabled=true; 
   form.y_o.disabled=true;
   form.fub_actual.value='';
   form.y_o.value='';
   form.fub_actual_descp.value='';
 }
}
//// -------------------------------------------------------------------------------
//// -------------------------------------------------------------------------------
function enabledRPUbicacionAnterior(form){
 if(form.chkubicacionAnterior.checked){ 
   form.fub_anterior_descp.disabled=false; 
   form.y_o.disabled=false; 
 }else{
   form.fub_anterior_descp.disabled=true;
   form.y_o.disabled=true;
   form.fub_anterior.value='';
   form.y_o.value='';
   form.fub_anterior_descp.value=''; 
 }
}
//// -------------------------------------------------------------------------------
//// -------------------------------------------------------------------------------
function valor_asignado(id){
 if(id=='fub_actual_descp') document.getElementById("asignado").value = '29';
 else document.getElementById("asignado").value = '30';
}
//// -------------------------------------------------------------------------------
//// FUNCION QUE PERMITE ACTIVAR SELECTOR JQUERY UTILIZANDO VALOR DE CAMPOS
//// -------------------------------------------------------------------------------
function rpmovlista() {
	var asignado = $("#asignado").val();
	var href = "af_listaubicacionesactivo.php?filtrar=default&limit=0&campo="+asignado+"&iframe=true&width=100%&height=100%"; ///alert(href);
	$("#a_lista").attr("href", href);
	document.getElementById("a_lista").click();
}
//// ---------------------------------------------------------------------------------------------
//// ----------		MOSTRAR REPORTE MOVIMIENTOS ACTIVOS
//// ---------------------------------------------------------------------------------------------
function CargarRPMovimientosActivo(form){

	var filtro="";
	if(form.chkorganismo.checked) filtro+=" and Organismo=*"+form.forganismo.value+"*";
	if(form.chkActivo.checked) filtro+=" and Activo=*"+form.fActivo.value+"*";
	if(form.checkDependencia.checked) filtro+=" and Dependencia=*"+form.fDependencia.value+"*";
	if(form.chkFAprobacion.checked){ 
	   filtro+=" and FechaAprobacion>=*"+form.fFechaAprobacionDesde.value+"*";
	   filtro+=" and FechaAprobacion<=*"+form.fFechaAprobacionHasta.value+"*";
	}
	if(form.checkcCosto.checked) filtro+=" and CentroCosto=*"+form.centro_costos.value+"*";
	if(form.checkcCosto.checked) filtro+=" and CentroCosto=*"+form.centro_costos.value+"*";
	if(form.chkFPreparacion.checked){ 
	   filtro+=" and FechaPreparacion>=*"+form.fFechaPreparacionDesde.value+"*";
	   filtro+=" and FechaPreparacion<=*"+form.fFechaPreparacionHasta.value+"*";
	}
	if(form.chkubicacionActual.checked) filtro+=" and Ubicacion=*"+form.fub_actual.value+"*";
	if(form.chkubicacionAnterior.checked) filtro+=" and UbicacionAnterior=*"+form.fub_anterior.value+"*";
	
    var pagina_mostrar_1="af_rptabmovimientoactivospdf.php?filtro="+filtro;
	var pagina_mostrar_2="af_rptabmovimientootroformatopdf.php?filtro="+filtro;
        form.target = "af_rptabmovimientos";				
				cargarPagina(form, pagina_mostrar_1);
				cargarPagina(form, pagina_mostrar_2);
}
//// ---------------------------------------------------------------------------------------------
//// ----------		MOSTRAR REPORTE MOVIMIENTOS ACTIVOS
//// ---------------------------------------------------------------------------------------------
function af_rptabmovimiento_activos_movimientos(form,tab){
   var filtro="";
	/*if(form.chkorganismo.checked) filtro+=" and Organismo=*"+form.forganismo.value+"*";
	if(form.chkActivo.checked) filtro+=" and Activo=*"+form.fActivo.value+"*";
	if(form.checkDependencia.checked) filtro+=" and Dependencia=*"+form.fDependencia.value+"*";
	if(form.chkFAprobacion.checked){ 
	   filtro+=" and FechaAprobacion>=*"+form.fFechaAprobacionDesde.value+"*";
	   filtro+=" and FechaAprobacion<=*"+form.fFechaAprobacionHasta.value+"*";
	}
	if(form.chekcCosto.checked) filtro+=" and CentroCosto=*"+form.centro_costos.value+"*";
	if(form.chkFPreparacion.checked){ 
	   filtro+=" and FechaPreparacion>=*"+form.fFechaPreparacionDesde.value+"*";
	   filtro+=" and FechaPreparacion<=*"+form.fFechaPreparacionHasta.value+"*";
	}
	if(form.chkubicacionActual.checked) filtro+=" and Ubicacion=*"+form.fub_actual.value+"*";
	if(form.chkubicacionAnterior.checked) filtro+=" and UbicacionAnterior=*"+form.fub_anterior.value+"*";*/
	
	var form = document.getElementById("frmentrada");
	if (tab == "movimiento") form.action = "af_rptabmovimientoactivospdf.php";
	else form.action = "af_rptabmovimientoactivosotroformatopdf.php";	
	form.submit();
}
//// ---------------------------------------------------------------------------------------------
//// ----------		MOSTRAR REPORTE INGRESO DE ACTIVOS
//// ---------------------------------------------------------------------------------------------
function af_rptab_ingreso_activos(form,tab){
    
	var form = document.getElementById("frmentrada");
	if(tab=="activo_activado") form.action = "af_rptabactivoactivadopdf.php";
	else if(tab=="no_asignado_pend_activar") form.action="af_rptabactivonoasignadospdf.php";
	else  form.action = "af_rptabactivosnoasignadosrecepcionpdf.php"; 	
	form.submit();
}
//// ---------------------------------------------------------------------------------------------
//// ----------		MOSTRAR REPORTE CATALOGO ACTIVOS
//// ---------------------------------------------------------------------------------------------
function af_rptabcatalogo_activos(form,tab){
   var filtro="";
		
	var form = document.getElementById("frmentrada");
	if (tab == "catalogo") form.action = "af_rptabcatalogoactivospdf.php";
	else form.action = "af_rptabcatalogoactivosotroformatopdf.php";	
	form.submit();
}
//// ---------------------------------------------------------------------------------------------
//// ----------		ACTIVAR Y DESACTIVAR CAMPOS
//// ---------------------------------------------------------------------------------------------
function enabledRPTABSituacion(form){
if(form.chksituacion.checked) form.fsituacion.disabled = false; 
else{ form.fsituacion.disabled=true; form.fsituacion.value='';} 
}
//// ----------		ACTIVAR Y DESACTIVAR CAMPOS
function enabledRPTABRango(form){
if(form.chkRango.checked){ 
  form.frango_desde.disabled=false;
  form.frango_hasta.disabled=false;
}else{
  form.frango_desde.disabled=true;
  form.frango_hasta.disabled=true;
  form.frango_desde.value='';
  form.frango_hasta.value='';
}
}
//// --------  ACTIVAR Y DESACTIVAR CAMPOS
function enabledContabilidad(form){
if(form.chkContabilidad.checked) form.fContabilidad.disabled=false;
else{ form.fContabilidad.disabled=false; form.fContabilidad.value='';}
}
//// --------  ACTIVAR Y DESACTIVAR CAMPOS
function enabledPeriodos(form){
if(form.chkPeriodo.checked){ form.fperiodo_desde.disabled=false; form.fperiodo_hasta.disabled=false;}
else{ form.fperiodo_desde.disabled=true; form.fperiodo_hasta.disabled=true; form.fperiodo_desde.value=''; form.fperiodo_hasta.value='';}
}
//// --------  ACTIVAR Y DESACTIVAR CAMPOS
function enabledVoucher(form){
if(form.chkVoucher.checked){ form.fvoucher_desde.disabled=false; form.fvoucher_hasta.disabled=false;}
else{ form.fvoucher_desde.disabled=true; form.fvoucher_hasta.disabled=true; form.fvoucher_desde.value=''; form.fvoucher_hasta.value='';}
}
//// --------  ACTIVAR Y DESACTIVAR CAMPOS
function enabledCuenta(form){
if(form.chkCuenta.checked){ form.fcuenta.disabled=false; form.fcuenta_descp.disabled=false; document.getElementById("cuenta").style.visibility='visible';}
else{ form.fcuenta.disabled=true; form.fcuenta_descp.disabled=true; form.fcuenta.value=''; form.fcuenta_descp.value=''; document.getElementById("cuenta").style.visibility='hidden';}
}
//// ---------------------------------------------------------------------------------------------
//// ----------		MOSTRAR REPORTE CATALOGO ACTIVOS
//// ---------------------------------------------------------------------------------------------
function af_rptabingresoegreso_activos(form,tab){
   var filtro="";
		
	var form = document.getElementById("frmentrada");
	if (tab == "adiciones_periodo") form.action = "af_rptabingresoegresoadicionesperiodopdf.php";
	else if(tab == "transacciones_baja") form.action = "af_rptabingresoegresotransaccionesbajapdf.php";
	else if(tab == "adiciones_x_voucher") form.action = "af_rptabingresoegresoadicionesxvoucherpdf.php"; 
	else form.action = "af_rptabingresoegresoadicionesanualespdf.php";	
	form.submit();
}
//// ---------------------------------------------------------------------------------------------
//// -------- ACTIVAR Y DESACTIVAR CAMPOS
function enabledResponsable(form){
 if(form.chkResponsable.checked){form.empleado_responsable.disabled=false;	document.getElementById("responsable").style.visibility='visible';}
 else{form.empleado_responsable.disabled=true;	form.empleado_responsable.value=''; form.cod_empresponsable.value=''; 
      document.getElementById("responsable").style.visibility='hidden';}
}    
//// ---------------------------------------------------------------------------------------------
//// ----------		MOSTRAR REPORTE INVENTARIO X DEPENDENCIA
//// ---------------------------------------------------------------------------------------------
function cargarActivosAsignadosPersona(form){
 
	var filtro="";
	if(form.chkorganismo.checked) filtro+=" and a.CodOrganismo=*"+form.forganismo.value+"*";
	if(form.checkDependencia.checked) filtro+=" and a.CodDependencia=*"+form.fDependencia.value+"*";
	if(form.checkEstado.checked) filtro+=" and a.Estado=*"+form.fEstado.value+"*";
	if(form.chkNaturaleza.checked) filtro+=" and a.Naturaleza=*"+form.fNaturaleza.value+"*";
	if(form.chkResponsable.checked) filtro+=" and a.EmpleadoUsuario=*"+form.cod_empresponsable.value+"*";
	if(form.chkPeriodo.checked){ 
	  filtro+=" and a.PeriodoIngreso>=*"+form.fperiodo_desde.value+"*";
	  filtro+=" and a.PeriodoIngreso<=*"+form.fperiodo_hasta.value+"*";
	}
		
    var pagina_mostrar="af_rptabactivosxempleadopdf.php?filtro="+filtro;
        form.target = "af_rptabactivosxempleadopdf";				
				cargarPagina(form, pagina_mostrar);
}