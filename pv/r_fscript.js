/// ----------------------------------------------------------------------
/// FUNCION BLOQUEAR Y DESBLOQUEAR UN CAMPO
function enabledElaboradoPor(form) {
	if (form.chkelaboradoPor.checked) form.elaboradoPor.disabled=false; 
	else { form.elaboradoPor.disabled=true; form.elaboradoPor.value=""; }
}
function enabledPNpresupuesto(form){
	if (form.chknpresupuesto.checked) form.fnpresupuesto.disabled=false; 
	else{ form.fnpresupuesto.disabled=true; form.fnpresupuesto.value=""; }
}
///------------------------------------------------------------------------
function enabledNroPresupuesto(form){
   if(form.chkPresupuesto.checked) form.fPresupuesto.disabled=false;
   else{ form.fPresupuesto.disabled=true; form.fPresupuesto.value='';}
}
///------------------------------------------------------------------------
function enabledRPPeriodo(form){
 if(form.chkPeriodo.checked) form.fPeriodo.disabled = false;
 else{ form.fPeriodo.disabled = true; form.fPeriodo.value='';}
}
///-------- ACTIVAR Y DESACTIVAR CAMPOS rp_ejecucionpresupuestaria01.php
function enabledRpPeriodoEjecucionPresupuestaria(form){
 if(form.chkPeriodo.checked){ form.fP_desde.disabled = false;  form.fP_hasta.disabled = false; 
 }else{ form.fP_desde.disabled=true; form.fP_hasta.disabled = true; form.fP_hasta.value=''; form.fP_desde.value='';}
}
/// -----------------------------------------------------------------------
/// MOSTAR REPORTE  PRESUPUESTO INCREMENTADO
function cargarReportePresupuestoIncremento(form){
  var EjercicioPpto = document.getElementById("fPeriodo").value;
  var NroPresupuesto = document.getElementById("fPresupuesto").value;
  
  var filtro="";
  
    if(form.chkorganismo.checked) filtro+=" and Organismo=*"+form.forganismo.value+"*";
	if(form.chkPresupuesto.checked) filtro+=" and CodPresupuesto=*"+form.fpresupuesto.value+"*";
	if(form.chkPeriodo.checked) filtro+=" and EjercicioPpto=*"+form.fPeriodo.value+"*"; 
  
  if((form.chkPresupuesto.checked)||(form.chkPeriodo.checked)){ 
    //alert('Paso');
    var pagina_mostrar="rp_presincrementopdf.php?filtro="+filtro;
        form.target = "rp_PresupuestoIncrementado";				
				cargarPagina(form, pagina_mostrar);
  }else{
    alert('Ingrese el numero del presupuesto o el periodo de ejecucion....');
	//document.getElementById("rp_PresupuestoIncrementado").style.display=false;
	//form.getElementById("rp_PresupuestoIncrementado").visibility=false;
	var pagina_mostrar="rp_presincrementopdf.php?EjercicioPpto="+EjercicioPpto+"&NroPresupuesto="+NroPresupuesto;
        form.target = "rp_PresupuestoIncrementado";				
				cargarPagina(form, pagina_mostrar);
  }
}
/// -----------------------------------------------------------------------
/// MOSTAR REPORTE  PRESUPUESTO INCREMENTADO
function cargarReportePresupuestoDisminuido(form){
	
  var NroPresupuesto = document.getElementById("fPresupuesto").value;
  var EjercicioPpto = document.getElementById("fPeriodo").value;
  var filtro="";
  
  if(form.chkorganismo.checked) filtro+=" and Organismo=*"+form.forganismo.value+"*";
  if(form.chkPresupuesto.checked) filtro+=" and CodPresupuesto=*"+form.fpresupuesto.value+"*";
  if(form.chkPeriodo.checked) filtro+=" and EjercicioPpto=*"+form.fPeriodo.value+"*"; 
  	
  if((form.chkPresupuesto.checked)||(form.chkPeriodo.checked)){ 
    //alert('Paso');
    var pagina_mostrar="rp_presdisminuidopdf.php?filtro="+filtro;
        form.target = "rp_PresupuestoDisminuido";				
				cargarPagina(form, pagina_mostrar);
  }else{
    alert('Ingrese el numero del presupuesto o el periodo de ejecucion....');
	//document.getElementById("rp_PresupuestoIncrementado").style.display=false;
	//form.getElementById("rp_PresupuestoIncrementado").visibility=false;
	 var pagina_mostrar="rp_presdisminuidopdf.php?EjercicioPpto="+EjercicioPpto+"&NroPresupuesto="+NroPresupuesto;
        form.target = "rp_PresupuestoDisminuido";				
				cargarPagina(form, pagina_mostrar);
  }
}
/// -----------------------------------------------------------------------
/// MOSTAR REPORTE  PRESUPUESTO INCREMENTADO
function cargarReportePresupuestoEjecucionPresupuestaria(form){
  if((form.chknpresupuesto.checked)||(form.chkejercicio.checked)){ 
    //alert('Paso');
    var pagina_mostrar="reporte_ejecucionpresupuestaria2.php";
        form.target = "rp_ejecucionPresupuestaria";				
				cargarPagina(form, pagina_mostrar);
  }else{
    alert('Ingrese el numero del presupuesto o el periodo de ejecucion....');
	//document.getElementById("rp_PresupuestoIncrementado").style.display=false;
	//form.getElementById("rp_PresupuestoIncrementado").visibility=false;
  }
}
/// -----------------------------------------------------------------------
function enabledRpPeriodoEjecucion(form){
  if(form.chkPeriodo.checked) form.fPeriodo.disabled = false;
  else{ form.fPeriodo.disabled=true; form.fPeriodo.value = '';}
}
///------------------------------------------------------------------------
/// ACTIVA Y DESACTIVA CAMPOS ESTADO RP EJECUCION POR PARTIDA
function enabledEstadoRP(form){
 if(form.chkestado.checked) form.fEstado.disabled = false;
 else{ form.fEstado.disabled = true; form.fEstado.value = '';}
}
///------------------------------------------------------------------------
/// ACTIVA Y DESACTIVA CAMPOS NUMERO PRESUPUESTO RP EJECUCION POR PARTIDA
function enabledNumPresupuestoRP(form){
  if(form.chknpresupuesto.checked){ form.fNumPresupuesto.disabled = false; form.btPresupuesto.disabled = false;}
  else{ form.fNumPresupuesto.disabled = true; form.fNumPresupuesto.value=''; form.btPresupuesto.disabled = true;}
}
///------------------------------------------------------------------------
/// ACTIVA Y DESACTIVA CAMPOS NUMERO PARTIDA RP EJECUCION POR PARTIDA
function enabledPartidaRP(form){
 if(form.chkPartida.checked){ form.fPartida.disabled=false; form.fPartida2.disabled=false; form.btPartida.disabled = false;}
 else{form.fPartida.disabled=true; form.fPartida.value=''; form.fPartida2.value=''; form.btPartida.disabled = true;}
}
///------------------------------------------------------------------------
function enabledPEjecucion(form){
 if(form.chkPEjecucion.checked) form.fPEjecucion.disabled = false;
 else{form.fPEjecucion.disabled=true; form.fPEjecucion.value = '';}
}
///------------------------------------------------------------------------
/// FUNCION PARA SELECCIONAR UNA PERSONA DE UNA LISTA Y COLOCARLO EN OTRA VENTANA
function selPartidasRP(busqueda, campo) {
	var registro=document.getElementById("registro").value; //alert(registro);
	
	if (campo==1) {
		var regis = registro.split("."); //alert('registro='+registro);
		var reg = regis[1]; //alert('reg='+reg);
		if(reg=='00')opener.document.frmentrada.fPartida.value=registro;
		else{ 
		    alert('Debe seleccionar una partida de tipo 401.00.00.00');
		    return false;
		}
	}else 
	if(campo==2){
	  var destino = document.getElementById("destino").value;	
	  var regis = registro.split("."); //alert('registro='+registro);
		var reg = regis[3]; //alert('reg='+reg);
		if(reg=='00'){
		    if(destino == '1')opener.document.frmentrada.fPartida.value = registro;
            if(destino == '2')opener.document.frmentrada.fPartida2.value = registro;
		}else{ 
		    alert('Debe seleccionar una partida de tipo 401.01.01.00');
		    return false;
		}
	
	}else
	if(campo==3){
	  opener.document.frmentrada.fNumPresupuesto.value = registro;
	}
	window.close();
}
/// ------------------------------------------------------------------------
/*function filtroReporteEjecucionPartida(form, limit) {

	var Partida = document.getElementById("fPartida").value;
	var CodOrganismo = document.getElementById("forganismo").value;
	var filtro1="";
	var filtro2="";
	var filtro3="";
	var fh=""; var fd="";
	if(form.chkorganismo.checked) filtro1+=" and Organismo=*"+form.forganismo.value+"*";
	if(form.chkPartida.checked) filtro3+=" and cod_partida=*"+form.fPartida.value+"*";
	if(form.chkPEjecucion.checked) filtro1+=" and EjercicioPpto=*"+form.fPEjecucion.value+"*";
	if(form.chknpresupuesto.checked) filtro1+=" and CodPresupuesto=*"+form.fNumPresupuesto.value+"*";
	if(form.chkestado.checked) filtro2+=" and Estado=*"+form.fEstado.value+"*";
	
	if(form.chkFecha.checked){
		var fdesde = new String (document.getElementById("fdesde").value);
		var fhasta = new String (document.getElementById("fhasta").value);
		var fdesde = fdesde.split("-");
		    fd = fdesde[2]+"-"+fdesde[1]+"-"+fdesde[0];
		
		var fhasta = fhasta.split("-"); 
		    fh = fhasta[2]+"-"+fhasta[1]+"-"+fhasta[0];
		
	  filtro2+=" and FechaOrdenPago>=*"+fd+"*"+"and FechaOrdenPago <=*"+fh+"*";
	}
	
	var pagina="rp_ejecucionpartidapdf.php?filtro1="+filtro1+"&filtro2="+filtro2+"&filtro3="+filtro3+"&Partida="+Partida+"&CodOrganismo="+CodOrganismo+"&fh="+fh+"&fd="+fd;
			cargarPagina(form, pagina);
}*/
/// ------------------------------------------------------------------------
/// FUNCION QUE PERMITE MOSTRAR PDF REPORTE EJECUCION POR ESPECIFICA
function filtroReporteEjecucionEspecifica(form, limit) {
 
 var fPEjecucion = document.getElementById("fPEjecucion").value;
if(fPEjecucion!=""){
	var Partida = document.getElementById("fPartida").value;
	var CodOrganismo = document.getElementById("forganismo").value;
	var anio = document.getElementById("fPEjecucion").value;
	var today = new Date(); //alert('today='+today);
	var dia = today.getDate(); //alert('diames='+ diames);
	var mes=today.getMonth() +1 ; //alert('mes='+ mes); 
	var ano=today.getFullYear(); //alert('ano='+ ano);
	
	var filtro1="";
	var filtro2="";
	var filtro3="";
	var fh=""; var fd="";
	if(form.chkorganismo.checked) filtro1+=" and Organismo=*"+form.forganismo.value+"*";
	if(form.chkPEjecucion.checked) filtro1+=" and EjercicioPpto=*"+form.fPEjecucion.value+"*";
	if(form.chknpresupuesto.checked) filtro1+=" and CodPresupuesto=*"+form.fNumPresupuesto.value+"*";
	//if(form.chkestado.checked) filtro2+=" and apor.Estado=*"+form.fEstado.value+"*";
	
	
	if(form.chkPartida.checked){ 
	    filtro3+=" and apordist.cod_partida>=*"+form.fPartida.value+"*"+" and apordist.cod_partida<=*"+form.fPartida2.value+"*";
	}else{
		var part1 = "000.00.00.00"; var part2 = "999.99.99.99";
		filtro3+=" and apordist.cod_partida>=*"+part1+"*"+" and apordist.cod_partida<=*"+part2+"*";
	}
	
	if(form.chkFecha.checked){
		var fdesde = new String (document.getElementById("fdesde").value);
		var fhasta = new String (document.getElementById("fhasta").value);
		var fdesde = fdesde.split("-");
		    fd = fdesde[2]+"-"+fdesde[1]+"-"+fdesde[0];
		
		var fhasta = fhasta.split("-"); 
		    fh = fhasta[2]+"-"+fhasta[1]+"-"+fhasta[0];
		
	  filtro2+=" and apor.FechaPago>=*"+fd+"*"+"and apor.FechaPago <=*"+fh+"*";
	}else{
		if(mes<10) mes= "0"+mes;
		if(dia<10) dia= "0"+dia;
	  fd = anio+"-"+"01-01";// alert(fd); 
	  fh = ano+"-"+mes+"-"+dia;//alert(fh);
	  filtro2+=" and apor.FechaPago>=*"+fd+"*"+"and apor.FechaPago <=*"+fh+"*";
	}
	
	var pagina="rp_ejecucionespecificapdf.php?filtro1="+filtro1+"&filtro2="+filtro2+"&filtro3="+filtro3+"&Partida="+Partida+"&CodOrganismo="+CodOrganismo+"&fh="+fh+"&fd="+fd+"&fPEjecucion="+fPEjecucion;
			cargarPagina(form, pagina);
}else{
  alert('Debe introducir el Periodo de Ejecución en el campo"\P. Ejecución\"');	
  return false;
  //cargarPagina(form, "rp_ejecucionespecifica.php");
}
}
/// ------------------------------------------------------------------------
function enabledFechaRpEjecucionPartida(form){
  if(form.chkFecha.checked){ form.fdesde.disabled = false; form.fhasta.disabled = false;}
  else{ form.fdesde.disabled=true; form.fdesde.value=''; form.fhasta.disabled=true; form.fhasta.value='';}
}
/// ------------------------------------------------------------------------
/// FUNCION CARGAR VENTANA REPORTE 
function cargarVentanaRpEjecucionPartida(form, pagina, param) {
	var valor = document.getElementById("fPEjecucion").value; //alert(valor);
	window.open(pagina+'&valor='+valor, "wPrincipal", "toolbar=no, menubar=no, location=no, scrollbars=yes, "+param);
}
/// ------------------------------------------------------------------------
/// FUNCION CARGAR VENTANA REPORTE 
function cargarVentanaRpNumeroPresupuesto(form, pagina, param) {
	var valor = document.getElementById("fPEjecucion").value; //alert(valor);
	window.open(pagina+'&valor='+valor, "wPrincipal", "toolbar=no, menubar=no, location=no, scrollbars=yes, "+param);
}
/// ------------------------------------------------------------------------
function valorDestino(form, id){
   var id = document.getElementById(id).name ; //alert(id);
   if(id=='fPartida') document.getElementById("selector").value = 1;
   else if(id=='fPartida2') document.getElementById("selector").value = 2;
}
/// -----------------------------------------------------------------------
/// FUNCION QUE PERMITE CARGAR LAS PARTIDAS
function cargarVentanaListaPatidas(form, pagina, param) {
	var destino = document.getElementById("selector").value; //alert(destino);
	window.open(pagina+"&destino="+destino, "wPrincipal2", "toolbar=no, menubar=no, location=no, scrollbars=yes, "+param);
}
/// -----------------------------------------------------------------------
function enabledPeriodoEjecucion(form){ //alert('.......');
  if(form.chkPeriodoEjec.checked) form.fPeriodoEjec.disabled=false;
  else{ form.fPeriodoEjec.disabled=true; form.fPeriodoEjec.value='';}
}
/// -----------------------------------------------------------------------
function enabledNumeroProyecto(form){ //alert('.......');
 if(form.chknproyecto.checked) form.fnproyecto.disabled=false;
 else{form.fnproyecto.disabled=true; form.fnproyecto.value='';}
}
/// ------------------------------------------------------------------------
function filtroPresupuesto(form, limit){
    var PeriodoEjecucion = document.getElementById("fPeriodoEjec"); //alert(PeriodoEjecucion);
	var NroProyecto = document.getElementById("fnproyecto");
    var filtro=""; var valor = "";
	
	if(form.chkorganismo.checked) filtro+=" and Organismo=*"+form.forganismo.value+"*";
	if(form.chknproyecto.checked) filtro+=" and CodPresupuesto=*"+form.fnproyecto.value+"*";
	if(form.chkPeriodoEjec.checked) filtro+=" and EjercicioPpto=*"+form.fPeriodoEjec.value+"*"; 
	
	if((document.getElementById("fnproyecto").value!='')||(document.getElementById("fPeriodoEjec").value!='')){
	   var pagina="r_formulacionpresupuestopdf.php?filtro="+filtro;
		   cargarPagina(form, pagina);
	}else{
	  alert('!DEBE INTRODUCIR EL NUMERO DEL PRESUPUESTO O EL PERIODO DE EJECUCION!');
	  valor = 1;
	  var pagina="r_formulacionpresupuestopdf.php?filtro="+filtro+"&valor="+valor+"&fPeriodoEjec="+fPeriodoEjec+"&NroProyecto="+NroProyecto;
		   cargarPagina(form, pagina);
	}
  
} 
/// ------------------------------------------------------------------------
function filtroPresupuesto2(form, limit){
    
	var PeriodoEjecucion = document.getElementById("fPeriodoEjec"); //alert(PeriodoEjecucion);
	var NroProyecto = document.getElementById("fnproyecto");
 
    var filtro=""; var valor = "";
	
	if(form.chkorganismo.checked) filtro+=" and Organismo=*"+form.forganismo.value+"*";
	if(form.chknproyecto.checked) filtro+=" and CodPresupuesto=*"+form.fnproyecto.value+"*";
	if(form.chkPeriodoEjec.checked) filtro+=" and EjercicioPpto=*"+form.fPeriodoEjec.value+"*"; 
	
	if((document.getElementById("fnproyecto").value!='')||(document.getElementById("fPeriodoEjec").value!='')){
	   var pagina="r_presupuestoajustadopdf.php?filtro="+filtro;
		   cargarPagina(form, pagina);
	}else{
	  alert('!DEBE INTRODUCIR EL NUMERO DEL PRESUPUESTO O EL PERIODO DE EJECUCION!');
	  valor = 1;
	  var pagina="r_presupuestoajustadopdf.php?filtro="+filtro+"&valor="+valor+"&fPeriodoEjec="+fPeriodoEjec+"&NroProyecto="+NroProyecto;
		   cargarPagina(form, pagina);
	}
} 
/// -----------------------------------------------------------------------------
///  ***********  FUNCION QUE PERMITE MOSTRAR PDF REPORTE EJECUCION POR PARTIDA
function filtroReporteEjecucionPartida(form, limit) {

	var Partida = document.getElementById("fPartida").value; ///alert('gggg'+Partida);
	var CodOrganismo = document.getElementById("forganismo").value;
	var filtro1="";
	var filtro2="";
	var filtro3="";
	var filtro4="";
	var fh=""; var fd="";
	if(form.chkorganismo.checked) filtro1+=" and Organismo=*"+form.forganismo.value+"*";
	if(form.chkPartida.checked) filtro3+=" and cod_partida=*"+form.fPartida.value+"*";
	if(form.chkPEjecucion.checked) filtro1+=" and EjercicioPpto=*"+form.fPEjecucion.value+"*";
	if(form.chknpresupuesto.checked) filtro1+=" and CodPresupuesto=*"+form.fNumPresupuesto.value+"*";
	if(form.chkestado.checked) filtro4+=" and Estado=*"+form.fEstado.value+"*";
	
	if(form.chkFecha.checked){
		var fdesde = new String (document.getElementById("fdesde").value);
		var fhasta = new String (document.getElementById("fhasta").value);
		var fdesde = fdesde.split("-");
		    fd = fdesde[2]+"-"+fdesde[1]+"-"+fdesde[0]; //alert(fd);
		
		var fhasta = fhasta.split("-"); 
		    fh = fhasta[2]+"-"+fhasta[1]+"-"+fhasta[0];
		
	  filtro2+=" and apor.FechaOrdenPago>=*"+fd+"*"+"and apor.FechaOrdenPago <=*"+fh+"*";
	}else{
	  fd = "0000-00-00"; 
	  fh = "9999-99-99";		
	  filtro2+=" and apor.FechaOrdenPago>=*"+fd+"*"+"and apor.FechaOrdenPago <=*"+fh+"*";
	}
	var pagina="rp_ejecucionpartidapdf.php?filtro1="+filtro1+"&filtro2="+filtro2+"&filtro3="+filtro3+"&Partida="+Partida+"&CodOrganismo="+CodOrganismo+"&fh="+fh+"&fd="+fd+"&filtro4="+filtro4;
			cargarPagina(form, pagina);
}
/// -----------------------------------------------------------------------------
///       ********************  MOSTAR REPORTE  EJECUCION PRESUPUESTARIA 
function cargarReportePresupuestoEjecucion(form){
  if((form.chknpresupuesto.checked)||(form.chkejercicio.checked)){ 
    
	var EjercicioPpto = document.getElementById("fejercicio").value;
	var Organismo = document.getElementById("forganismo").value;
	//var Periodo = document.getElementById("fPeriodo").value;
	var CodPresupuesto = document.getElementById("fnpresupuesto").value; //alert(CodPresupuesto);
	
	var filtro="";
	var filtro2="";
	var filtro3="";
	if(form.chkorganismo.checked) filtro+=" and Organismo=*"+form.forganismo.value+"*";
	if(form.chkejercicio.checked) filtro+=" and EjercicioPpto=*"+form.fejercicio.value+"*";
	if(form.chknpresupuesto.checked) filtro+=" and CodPresupuesto=*"+form.fnpresupuesto.value+"*";
	//if(form.chkPeriodo.checked) filtro2+=" and Periodo=*"+form.fPeriodo.value+"*";
	
   // var pagina_mostrar="rp_ejecucionpdf.php?filtro="+filtro+"&filtro2="+filtro2+"&Organismo="+Organismo+"&EjercicioPpto="+EjercicioPpto+"&Periodo="+Periodo;
	var pagina_mostrar="rp_ejecucionpdf.php?filtro="+filtro+"&filtro2="+filtro2+"&Organismo="+Organismo+"&EjercicioPpto="+EjercicioPpto;
        form.target = "rp_ejecucion";				
				cargarPagina(form, pagina_mostrar);
  }else{
    alert('Ingrese el numero del presupuesto o el periodo de ejecucion....');
	//document.getElementById("rp_PresupuestoIncrementado").style.display=false;
	//form.getElementById("rp_PresupuestoIncrementado").visibility=false;
  }
}
/// -----------------------------------------------------------------------------


/// -----------------------------------------------------------------------------
///       ********************  MOSTAR REPORTE  EJECUCION PRESUPUESTARIA 
function cargarReportePresupuestoEjecucionCompromiso(form){
//alert('asdasdads');
  if((form.chknpresupuesto.checked)||(form.chkejercicio.checked)){ 
    
	var EjercicioPpto = document.getElementById("fejercicio").value;
	var Organismo = document.getElementById("forganismo").value;
	//var Periodo = document.getElementById("fPeriodo").value;
	var CodPresupuesto = document.getElementById("fnpresupuesto").value; //alert(CodPresupuesto);
	
	var filtro="";
	var filtro2="";
	var filtro3="";
	if(form.chkorganismo.checked) filtro+=" and Organismo=*"+form.forganismo.value+"*";
	if(form.chkejercicio.checked) filtro+=" and EjercicioPpto=*"+form.fejercicio.value+"*";
	if(form.chknpresupuesto.checked) filtro+=" and CodPresupuesto=*"+form.fnpresupuesto.value+"*";
	//if(form.chkPeriodo.checked) filtro2+=" and Periodo=*"+form.fPeriodo.value+"*";
	
   // var pagina_mostrar="rp_ejecucionpdf.php?filtro="+filtro+"&filtro2="+filtro2+"&Organismo="+Organismo+"&EjercicioPpto="+EjercicioPpto+"&Periodo="+Periodo;
	var pagina_mostrar="rp_ejecucioncompromisopdf.php?filtro="+filtro+"&filtro2="+filtro2+"&Organismo="+Organismo+"&EjercicioPpto="+EjercicioPpto;
        form.target = "rp_ejecucion_compromiso";				
				cargarPagina(form, pagina_mostrar);
  }else{
    alert('Ingrese el numero del presupuesto o el periodo de ejecucion....');
	//document.getElementById("rp_PresupuestoIncrementado").style.display=false;
	//form.getElementById("rp_PresupuestoIncrementado").visibility=false;
  }
}
/// -----------------------------------------------------------------------------



///       *********************  MOSTAR REPORTE  EJECUCION PRESUPUESTARIA 
function cargarReportePresupuestoEjecucionPresupuestaria01(form){
  if((form.chknpresupuesto.checked)||(form.chkejercicio.checked)){ 
    
	var EjercicioPpto = document.getElementById("fejercicio").value;
	var Organismo = document.getElementById("forganismo").value;
	//var Periodo = document.getElementById("fPeriodo").value;
	var CodPresupuesto = document.getElementById("fnpresupuesto").value; //alert(CodPresupuesto);
	var fp_desde = document.getElementById("fP_desde").value; //alert('fP_desde= '+fp_desde)
	var fp_hasta = document.getElementById("fP_hasta").value;//alert('fP_hasta= '+fp_hasta)
	
	var Periodo="";
	var filtro="";
	var filtro2="";
	var filtro3="";
	if(form.chkorganismo.checked) filtro+=" and Organismo=*"+form.forganismo.value+"*";
	if(form.chkejercicio.checked) filtro+=" and EjercicioPpto=*"+form.fejercicio.value+"*";
	if(form.chknpresupuesto.checked) filtro+=" and CodPresupuesto=*"+form.fnpresupuesto.value+"*";
	//if(form.chkPeriodo.checked) filtro2+=" and Periodo=*"+form.fPeriodo.value+"*";
	if(fp_desde==fp_hasta){
		filtro2+=" and Periodo=*"+form.fP_desde.value+"*";
		Periodo = fp_desde; //alert('Periodo='+Periodo);
	}else{ 
	    filtro2+=" and Periodo>=*"+form.fP_desde.value+"*"; filtro2+=" and Periodo<=*"+form.fP_hasta.value+"*";
		Periodo = "";
	}
	
    var pagina_mostrar="rp_ejecucionpresupuestariapdf01.php?filtro="+filtro+"&filtro2="+filtro2+"&Organismo="+Organismo+"&EjercicioPpto="+EjercicioPpto+"&Periodo="+Periodo+"&fp_hasta="+fp_hasta+"&CodPresupuesto="+CodPresupuesto;
        form.target = "rp_ejecucionPresupuestaria01";				
				cargarPagina(form, pagina_mostrar);
  }else{
    alert('Ingrese el numero del presupuesto o el periodo de ejecucion....');
	//document.getElementById("rp_PresupuestoIncrementado").style.display=false;
	//form.getElementById("rp_PresupuestoIncrementado").visibility=false;
  }
}
/// -----------------------------------------------------------------------------
///       *********************  MOSTRAR CIERRE DE MES PREVIO
function cargarCierreMesPresupuestario(form){
  if((form.chknropresupuesto.checked)||(form.chkejercicioPpto.checked)){ 
    
	var EjercicioPpto = document.getElementById("fejercicioppto").value; //alert(EjercicioPpto);
	var Organismo = document.getElementById("forganismo").value; ///alert(Organismo);
	var Periodo = document.getElementById("fperiodo").value; ///alert(Periodo);
	var CodPresupuesto = document.getElementById("fnropresupuesto").value; //alert(CodPresupuesto);
	
	var filtro="";
	var filtro2="";
	var filtro3="";
	if(form.chkorganismo.checked) filtro+=" and Organismo=*"+form.forganismo.value+"*";
	if(form.chkejercicioPpto.checked) filtro+=" and EjercicioPpto=*"+form.fejercicioppto.value+"*";
	if(form.chknropresupuesto.checked) filtro+=" and CodPresupuesto=*"+form.fnropresupuesto.value+"*";
	if(form.chkperiodo.checked) filtro2+=" and Periodo=*"+form.fperiodo.value+"*";
	
    var pagina_mostrar="proceso_cierremespdf.php?filtro="+filtro+"&filtro2="+filtro2+"&Organismo="+Organismo+"&EjercicioPpto="+EjercicioPpto+"&Periodo="+Periodo;
        form.target = "proceso_cierremespdf";				
				cargarPagina(form, pagina_mostrar);
  }else{
    alert('Ingrese el numero del presupuesto o el periodo de ejecucion....');
	//document.getElementById("rp_PresupuestoIncrementado").style.display=false;
	//form.getElementById("rp_PresupuestoIncrementado").visibility=false;
  }
}
/// -----------------------------------------------------------------------------
///              ******************** PROCESO EJECUTAR CIERRE
/// -----------------------------------------------------------------------------
function ProcesoEjecutarCierre(form){
    var EjercicioPpto = document.getElementById("fejercicioppto").value; //alert(EjercicioPpto);
	var Organismo = document.getElementById("forganismo").value; //alert(Organismo);
	var Periodo = document.getElementById("fperiodo").value; //alert(Periodo);
	var CodPresupuesto = document.getElementById("fnropresupuesto").value; //alert(CodPresupuesto);
	var Usuario =  document.getElementById("usuarioActual").value; //alert(Usuario);
	
	var ajax=nuevoAjax();
		ajax.open("POST", "gmsector.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=guardarCierreMesPresupuestario&EjercicioPpto="+EjercicioPpto+"&Organismo="+Organismo+"&Periodo="+Periodo+"&CodPresupuesto="+CodPresupuesto+"&Usuario="+Usuario);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else {
					alert("¡CIERRE DE MES EJECUTADO!");
					form.submit();
				}
			}
	}
	return false;
}
/// -----------------------------------------------------------------------------
///                     FUNCION QUE PERMITE CARGAR REPORTES
function filtroProyectoPresup(form, limit){
   var PeriodoEjecucion = document.getElementById("fPeriodoEjec").value; 
   var filtro=""; var valor = "";
	
	if(form.chkorganismo.checked) filtro+=" and Organismo=*"+form.forganismo.value+"*"; //alert(filtro);
	if(form.chknproyecto.checked) filtro+=" and CodAnteproyecto=*"+form.fnproyecto.value+"*"; 
	if(form.chkPeriodoEjec.checked) filtro+=" and EjercicioPpto=*"+form.fPeriodoEjec.value+"*"; 
	if(form.chksector.checked) filtro+= " and Sector=*"+form.fSector.value+"*";
	if(form.chkprograma.checked) filtro+=" and Programa=*"+form.fPrograma.value+"*";
	if(form.chksubprograma.checked) filtro+=" and SubPrograma=*"+form.fSubPrograma.value+"*";
	if(form.chkproyecto.checked) filtro+=" and Proyecto=*"+form.fProyecto.value+"*";
	if(form.chkactividad.checked) filtro+=" and Actividad=*"+form.fActividad.value+"*";
	if(form.chkunidadejecutora.checked) filtro+=" and UnidadEjecutora=*"+form.fUnidadEjecutora.value+"*";
	
	
	if((document.getElementById("fnproyecto").value!='')||(document.getElementById("fPeriodoEjec").value!='')){
	   var pagina="r_proyectopresupuestopdf.php?filtro="+filtro;
		   cargarPagina(form, pagina);
	}else{
	  alert('!DEBE INTRODUCIR EL NUMERO DEL PROYECTO O EL PERIODO DE EJECUCION!');
	  valor = 1;
	  var pagina="r_proyectopresupuestopdf.php?filtro="+filtro+"&valor="+valor+"&PeriodoEjecucion="+PeriodoEjecucion;
		   cargarPagina(form, pagina);
	}
}
/// -----------------------------------------------------------------------------
///                   FILTRO REPORTE PROYECTO DE PRESUPUESTO
function enabledRPPeriodoEjecucion(form){
 if(form.chkPeriodoEjec.checked) form.fPeriodoEjec.disabled=false;
 else{ form.fPeriodoEjec.disabled=true; form.fPeriodoEjec.value='';}
}
function enabledRPSector(form){
 if(form.chksector.checked) form.fSector.disabled=false;
 else{form.fSector.disabled=true; form.fSector.value='';}
}
function enabledRPPrograma(form){
 if(form.chkprograma.checked) form.fPrograma.disabled=false;
 else{form.fPrograma.disabled=true; form.fPrograma.value='';}
}
function enabledRPSubPrograma(form){
  if(form.chksubprograma.checked) form.fSubPrograma.disabled = false;
  else{form.fSubPrograma.disabled=true; form.fSubPrograma.value='';}
}
function enabledRPProyecto(form){
  if(form.chkproyecto.checked) form.fProyecto.disabled=false;
  else{ form.fProyecto.disabled=true; form.fProyecto.value='';}
}
function enabledRPActividad(form){
  if(form.chkactividad.checked) form.fActividad.disabled=false;
  else{form.fActividad.disabled=true; form.fActividad.value='';}
}
function enabledRPUnidadEjecutora(form){
  if(form.chkunidadejecutora.checked) form.fUnidadEjecutora.disabled=false;
  else{form.fUnidadEjecutora.disabled=true; form.fUnidadEjecutora.value='';}
}
/// -----------------------------------------------------------------------------
function f_Sector(valor){ 
 var valor2 = document.getElementById("fSector").value;
 //alert(valor2);
}
/// -----------------------------------------------------------------------------
/// MOSTAR REPORTE  DISPONIBILIDAD PRESUPUESTARIA RESUMIDA 
function cargarReportePresupuestoDisponibilidadResumida(form){
  if((form.chknpresupuesto.checked)||(form.chkejercicio.checked)){ 
    
	var EjercicioPpto = document.getElementById("fejercicio").value;
	var Organismo = document.getElementById("forganismo").value;
	//var Periodo = document.getElementById("fPeriodo").value;
	var CodPresupuesto = document.getElementById("fnpresupuesto").value; //alert(CodPresupuesto);
	
	var filtro="";
	var filtro2="";
	var filtro3="";
	if(form.chkorganismo.checked) filtro+=" and Organismo=*"+form.forganismo.value+"*";
	if(form.chkejercicio.checked) filtro+=" and EjercicioPpto=*"+form.fejercicio.value+"*";
	if(form.chknpresupuesto.checked) filtro+=" and CodPresupuesto=*"+form.fnpresupuesto.value+"*";
	//if(form.chkPeriodo.checked) filtro2+=" and Periodo=*"+form.fPeriodo.value+"*";
	
   // var pagina_mostrar="rp_ejecucionpdf.php?filtro="+filtro+"&filtro2="+filtro2+"&Organismo="+Organismo+"&EjercicioPpto="+EjercicioPpto+"&Periodo="+Periodo;
	var pagina_mostrar="rp_dispopresresumidapdf.php?filtro="+filtro+"&filtro2="+filtro2+"&Organismo="+Organismo+"&EjercicioPpto="+EjercicioPpto;
        form.target = "rp_dispopresresumida";				
				cargarPagina(form, pagina_mostrar);
  }else{
    alert('Ingrese el numero del presupuesto o el periodo de ejecucion....');
	//document.getElementById("rp_PresupuestoIncrementado").style.display=false;
	//form.getElementById("rp_PresupuestoIncrementado").visibility=false;
  }
}
/// -----------------------------------------------------------------------------
/// 		************* MOSTRAR REPORTE DISPONIBILIDAD PRESUPUESTARIA DETALLADA
function cargarReportePresupuestoDisponibilidadDetallada(form){
  if((form.chknpresupuesto.checked)||(form.chkejercicio.checked)){ 
    
	var EjercicioPpto = document.getElementById("fejercicio").value;
	var Organismo = document.getElementById("forganismo").value;
	//var Periodo = document.getElementById("fPeriodo").value;
	var CodPresupuesto = document.getElementById("fnpresupuesto").value; //alert(CodPresupuesto);
	
	var filtro="";
	var filtro2="";
	var filtro3="";
	if(form.chkorganismo.checked) filtro+=" and Organismo=*"+form.forganismo.value+"*";
	if(form.chkejercicio.checked) filtro+=" and EjercicioPpto=*"+form.fejercicio.value+"*";
	if(form.chknpresupuesto.checked) filtro+=" and CodPresupuesto=*"+form.fnpresupuesto.value+"*";
	//if(form.chkPeriodo.checked) filtro2+=" and Periodo=*"+form.fPeriodo.value+"*";
	
   // var pagina_mostrar="rp_ejecucionpdf.php?filtro="+filtro+"&filtro2="+filtro2+"&Organismo="+Organismo+"&EjercicioPpto="+EjercicioPpto+"&Periodo="+Periodo;
	var pagina_mostrar="rp_dispopresdetalladapdf.php?filtro="+filtro+"&filtro2="+filtro2+"&Organismo="+Organismo+"&EjercicioPpto="+EjercicioPpto;
        form.target = "rp_dispopresdetallada";				
				cargarPagina(form, pagina_mostrar);
  }else{
    alert('Ingrese el numero del presupuesto o el periodo de ejecucion....');
	//document.getElementById("rp_PresupuestoIncrementado").style.display=false;
	//form.getElementById("rp_PresupuestoIncrementado").visibility=false;
  }
}
/// -----------------------------------------------------------------------------
/// 		************* MOSTAR REPORTE  COMPROMISOS
function cargarReporteCompromiso(form){
  if((form.chknpresupuesto.checked)||(form.chkejercicio.checked)){ 
    
	var EjercicioPpto = document.getElementById("fejercicio").value;
	var Organismo = document.getElementById("forganismo").value;
	var CodPresupuesto = document.getElementById("fnpresupuesto").value; //alert(CodPresupuesto);
	var fp_desde = document.getElementById("fP_desde").value; //alert('fP_desde= '+fp_desde)
	var fp_hasta = document.getElementById("fP_hasta").value;//alert('fP_hasta= '+fp_hasta)
	var codpartida = document.getElementById("fpartida").value;
	
	var fechaDesde,fechaHasta="";
	var filtro=filtro2=filtro3=""; 
	if(form.chkorganismo.checked) filtro+=" and Organismo=*"+form.forganismo.value+"*";
	if(form.chkejercicio.checked){ filtro+=" and EjercicioPpto=*"+form.fejercicio.value+"*"; filtro2+=" and Anio=*"+form.fejercicio.value+"*";}
	if(form.chknpresupuesto.checked) filtro+=" and CodPresupuesto=*"+form.fnpresupuesto.value+"*";
	if(form.chkpartida.checked) filtro+=" and CodPresupuesto=*"+form.fpartida.value+"*";
	if(form.chkPeriodo.checked){ 
	   filtro2+=" and Periodo>=*"+form.fP_desde.value+"*"; filtro3+=" and lgdist.Periodo>=*"+form.fP_desde.value+"*";
	   filtro2+=" and Periodo<=*"+form.fP_hasta.value+"*"; filtro3+=" and lgdist.Periodo<=*"+form.fP_hasta.value+"*";
	   fechaDesde = fp_desde+"-"+"00"; //alert('fechaDesde='+fechaDesde);
	   fechaHasta = fp_hasta+"-"+"99"; //alert('fechaHasta='+fechaHasta);
	}else{
		var pdesde = "0000-00"; fechaDesde = "0000-00-00";
		var phasta = "9999-99"; fechaHasta = "9999-99-99";
	   filtro2+=" and Periodo>=*"+pdesde+"*"; filtro3+=" and lgdist.Periodo>=*"+pdesde+"*";
	   filtro2+=" and Periodo<=*"+phasta+"*"; filtro3+=" and lgdist.Periodo<=*"+phasta+"*";
	}
	
    var pagina_mostrar="rp_compromisospdf2.php?filtro="+filtro+"&Organismo="+Organismo+"&EjercicioPpto="+EjercicioPpto+"&fp_hasta="+fp_hasta+"&CodPresupuesto="+CodPresupuesto+"&fp_desde="+fp_desde+"&filtro2="+filtro2+"&filtro3="+filtro3+"&fechaDesde="+fechaDesde+"&fechaHasta="+fechaHasta+"&codpartida="+codpartida;
        form.target = "rp_compromisos";				
	    cargarPagina(form, pagina_mostrar);
  }else{
    alert('Ingrese el numero del presupuesto o el periodo de ejecución....');
  }
}
//// -----------------------------------------------------------------------------------
//// 		************* MOSTAR REPORTE  CAUSADOS
//// -----------------------------------------------------------------------------------
function cargarReporteCausados(form){
  if((form.chknpresupuesto.checked)||(form.chkejercicio.checked)){ 
    
	var EjercicioPpto = document.getElementById("fejercicio").value;
	var Organismo = document.getElementById("forganismo").value;
	var CodPresupuesto = document.getElementById("fnpresupuesto").value; //alert(CodPresupuesto);
	var fp_desde = document.getElementById("fP_desde").value; //alert('fP_desde= '+fp_desde)
	var fp_hasta = document.getElementById("fP_hasta").value;//alert('fP_hasta= '+fp_hasta)
	
	var fechaDesde,fechaHasta="";
	var filtro=filtro2=filtro3=""; 
	if(form.chkorganismo.checked) filtro+=" and Organismo=*"+form.forganismo.value+"*";
	if(form.chkejercicio.checked) filtro+=" and EjercicioPpto=*"+form.fejercicio.value+"*";
	if(form.chknpresupuesto.checked) filtro+=" and CodPresupuesto=*"+form.fnpresupuesto.value+"*";
	if(form.chkPeriodo.checked){ 
	   filtro2+=" and Periodo>=*"+form.fP_desde.value+"*"; filtro3+=" and lgdist.Periodo>=*"+form.fP_desde.value+"*";
	   filtro2+=" and Periodo<=*"+form.fP_hasta.value+"*"; filtro3+=" and lgdist.Periodo<=*"+form.fP_hasta.value+"*";
	   fechaDesde = fp_desde+"-"+"00"; //alert('fechaDesde='+fechaDesde);
	   fechaHasta = fp_hasta+"-"+"99"; //alert('fechaHasta='+fechaHasta);
	}else{
	    var pdesde = "0000-00"; fechaDesde = "0000-00-00";
		var phasta = "9999-99"; fechaHasta = "9999-99-99";
	   filtro2+=" and Periodo>=*"+pdesde+"*"; filtro3+=" and lgdist.Periodo>=*"+pdesde+"*";
	   filtro2+=" and Periodo<=*"+phasta+"*"; filtro3+=" and lgdist.Periodo<=*"+phasta+"*";
	}
	
    var pagina_mostrar="rp_causadospdf.php?filtro="+filtro+"&Organismo="+Organismo+"&EjercicioPpto="+EjercicioPpto+"&fp_hasta="+fp_hasta+"&CodPresupuesto="+CodPresupuesto+"&fp_desde="+fp_desde+"&filtro2="+filtro2+"&filtro3="+filtro3+"&fechaDesde="+fechaDesde+"&fechaHasta="+fechaHasta;
        form.target = "rp_causados";				
	    cargarPagina(form, pagina_mostrar);
  }else{
    alert('Ingrese el numero del presupuesto o el periodo de ejecución....');
  }
}
/// -----------------------------------------------------------------------
/// 		************* MOSTAR REPORTE  CAUSADOS
function cargarReportePagados(form){
  if((form.chknpresupuesto.checked)||(form.chkejercicio.checked)){ 
    
	var EjercicioPpto = document.getElementById("fejercicio").value;
	var Organismo = document.getElementById("forganismo").value;
	var CodPresupuesto = document.getElementById("fnpresupuesto").value; //alert(CodPresupuesto);
	var fp_desde = document.getElementById("fP_desde").value; //alert('fP_desde= '+fp_desde)
	var fp_hasta = document.getElementById("fP_hasta").value;//alert('fP_hasta= '+fp_hasta)
	
	var fechaDesde,fechaHasta="";
	var filtro=filtro2=filtro3=""; 
	if(form.chkorganismo.checked) filtro+=" and Organismo=*"+form.forganismo.value+"*";
	if(form.chkejercicio.checked) filtro+=" and EjercicioPpto=*"+form.fejercicio.value+"*";
	if(form.chknpresupuesto.checked) filtro+=" and CodPresupuesto=*"+form.fnpresupuesto.value+"*";
	if(form.chkPeriodo.checked){ 
	   filtro2+=" and Periodo>=*"+form.fP_desde.value+"*"; filtro3+=" and lgdist.Periodo>=*"+form.fP_desde.value+"*";
	   filtro2+=" and Periodo<=*"+form.fP_hasta.value+"*"; filtro3+=" and lgdist.Periodo<=*"+form.fP_hasta.value+"*";
	   fechaDesde = fp_desde+"-"+"00"; //alert('fechaDesde='+fechaDesde);
	   fechaHasta = fp_hasta+"-"+"99"; //alert('fechaHasta='+fechaHasta);
	}else{
	   var pdesde = "0000-00"; fechaDesde = "0000-00-00";
	   var phasta = "9999-99"; fechaHasta = "9999-99-99";
	   filtro2+=" and Periodo>=*"+pdesde+"*"; filtro3+=" and lgdist.Periodo>=*"+pdesde+"*";
	   filtro2+=" and Periodo<=*"+phasta+"*"; filtro3+=" and lgdist.Periodo<=*"+phasta+"*";
	}
	
    var pagina_mostrar="rp_pagadospdf.php?filtro="+filtro+"&Organismo="+Organismo+"&EjercicioPpto="+EjercicioPpto+"&fp_hasta="+fp_hasta+"&CodPresupuesto="+CodPresupuesto+"&fp_desde="+fp_desde+"&filtro2="+filtro2+"&filtro3="+filtro3+"&fechaDesde="+fechaDesde+"&fechaHasta="+fechaHasta;;
        form.target = "rp_pagados";				
	    cargarPagina(form, pagina_mostrar);
  }else{
    alert('Ingrese el numero del presupuesto o el periodo de ejecución....');
  }
}
/// -----------------------------------------------------------------------



/// -----------------------------------------------------------------------
/// 		************* MOSTAR REPORTE  CLASIFICADOR PRESUPUESTARIO
function cargarClasificadorPresupuestario(){
 
 	//alert('sdsdf');
 	var form = document.forms['frmentrada'];
    var pagina_mostrar="rp_clasificadorPresupuestarioPdf.php";
        form.target = "rp_clasificador";				
	    cargarPagina(form, pagina_mostrar);
 
}

/// 		************* MOSTRAR CIERRE DE MES
function cargarCierreMesPresupuestarioRP(form){
  if((form.chknropresupuesto.checked)||(form.chkejercicioPpto.checked)){ 
    
	var EjercicioPpto = document.getElementById("fejercicioppto").value; //alert(EjercicioPpto);
	var Organismo = document.getElementById("forganismo").value; ///alert(Organismo);
	var Periodo = document.getElementById("fperiodo").value; ///alert(Periodo);
	var CodPresupuesto = document.getElementById("fnropresupuesto").value; //alert(CodPresupuesto);
	
	var filtro="";
	var filtro2="";
	var filtro3="";
	if(form.chkorganismo.checked) filtro+=" and Organismo=*"+form.forganismo.value+"*";
	if(form.chkejercicioPpto.checked) filtro+=" and EjercicioPpto=*"+form.fejercicioppto.value+"*";
	if(form.chknropresupuesto.checked) filtro+=" and CodPresupuesto=*"+form.fnropresupuesto.value+"*";
	if(form.chkperiodo.checked) filtro2+=" and Periodo=*"+form.fperiodo.value+"*";
	
    var pagina_mostrar="rp_cierremespdf.php?filtro="+filtro+"&filtro2="+filtro2+"&Organismo="+Organismo+"&EjercicioPpto="+EjercicioPpto+"&Periodo="+Periodo;
        form.target = "rp_cierremespdf";				
				cargarPagina(form, pagina_mostrar);
  }else{
    alert('Ingrese el numero del presupuesto o el periodo de ejecucion....');
	//document.getElementById("rp_PresupuestoIncrementado").style.display=false;
	//form.getElementById("rp_PresupuestoIncrementado").visibility=false;
  }
}
/// ------------------------------------------------------------------------
///        ************* MOSTRAR EJECUCION PRESUPUESTARIA POR RANGO DE FECHA
function filtroReporteEjecucionPresupuestariaXFecha(form, limit) {
	
	var filtro=""; var filtro2="";
	var fechaHasta=""; var fechaDesde="";
	var periodoDesde=""; var periodoHasta="";
	var Periodo = document.getElementById("fejercicioppto").value;
	
	if(form.chkorganismo.checked) filtro+=" and Organismo=*"+form.forganismo.value+"*";
	if(form.chkejercicioPpto.checked) filtro+=" and EjercicioPpto=*"+form.fejercicioppto.value+"*";
	if(form.chknropresupuesto.checked) filtro+=" and CodPresupuesto=*"+form.fnropresupuesto.value+"*";
	
	if(form.chkFecha.checked){
		var fdesde = new String (document.getElementById("fdesde").value);
		var fhasta = new String (document.getElementById("fhasta").value);
		var fdesde = fdesde.split("-");
		    fechaDesde = fdesde[2]+"-"+fdesde[1]+"-"+fdesde[0]; //alert(fd);
			periodoDesde = fdesde[2]+"-"+fdesde[1]; //alert(periodoDesde);
		
		var fhasta = fhasta.split("-"); 
		    fechaHasta = fhasta[2]+"-"+fhasta[1]+"-"+fhasta[0];
			periodoHasta = fhasta[2]+"-"+fhasta[1];
		
	  //filtro2+=" and apor.FechaOrdenPago>=*"+fd+"*"+"and apor.FechaOrdenPago <=*"+fh+"*";
	}else{
	  fechaDesde = "0000-00-00"; 
	  fechaHasta = "9999-99-99";		
	  //filtro2+=" and apor.FechaOrdenPago>=*"+fd+"*"+"and apor.FechaOrdenPago <=*"+fh+"*";
	}
	var pagina="rp_ejecucionxfechapdf.php?filtro="+filtro+"&fechaDesde="+fechaDesde+"&fechaHasta="+fechaHasta+"&periodoDesde="+periodoDesde+"&periodoHasta="+periodoHasta+"&Periodo="+Periodo;
	        form.target = "rp_ejecucionxfechapdf";
			cargarPagina(form, pagina);
}

/// ------------------------------------------------------------------------
///        ************* MOSTRAR EJECUCION PRESUPUESTARIA POR RANGO DE MES
function filtroReporteEjecucionPresupuestariaXmes(form, limit) {
	
	var filtro=""; var filtro2="";
	var fechaHasta=""; var fechaDesde="";
	var periodoDesde=""; var periodoHasta="";
	var Periodo = document.getElementById("fejercicioppto").value;
	
	if(form.chkorganismo.checked) filtro+=" and Organismo=*"+form.forganismo.value+"*";
	if(form.chkejercicioPpto.checked) filtro+=" and EjercicioPpto=*"+form.fejercicioppto.value+"*";
	if(form.chknropresupuesto.checked) filtro+=" and CodPresupuesto=*"+form.fnropresupuesto.value+"*";
	
	if(form.chkFecha.checked){
		var fdesde = new String (document.getElementById("fdesde").value);
		var fhasta = new String (document.getElementById("fhasta").value);
		var fdesde = fdesde.split("-");
		    fechaDesde = fdesde[2]+"-"+fdesde[1]+"-"+fdesde[0]; //alert(fd);
			periodoDesde = fdesde[2]+"-"+fdesde[1]; //alert(periodoDesde);
		
		var fhasta = fhasta.split("-"); 
		    fechaHasta = fhasta[2]+"-"+fhasta[1]+"-"+fhasta[0];
			periodoHasta = fhasta[2]+"-"+fhasta[1];
		
	  //filtro2+=" and apor.FechaOrdenPago>=*"+fd+"*"+"and apor.FechaOrdenPago <=*"+fh+"*";
	}else{
	  fechaDesde = "0000-00-00"; 
	  fechaHasta = "9999-99-99";		
	  //filtro2+=" and apor.FechaOrdenPago>=*"+fd+"*"+"and apor.FechaOrdenPago <=*"+fh+"*";
	}
	var pagina="rp_ejecucionxmespdf.php?filtro="+filtro+"&fechaDesde="+fechaDesde+"&fechaHasta="+fechaHasta+"&periodoDesde="+periodoDesde+"&periodoHasta="+periodoHasta+"&Periodo="+Periodo;
	        form.target = "rp_ejecucionxmespdf";
			cargarPagina(form, pagina);
}
//// ------------------------------------------------------------------------
//// 
function enabledPPartida(form){
   if(form.chkpartida.checked) form.fpartida.disabled= false;
   else{ form.fpartida.disabled=true; form.fpartida.value='';}  
}
//// ------------------------------------------------------------------------
