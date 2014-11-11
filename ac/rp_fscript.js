/// --------------------------------------------------------------------------------- */ 
/// ---------- ////////// REPOPRTE LIBRO DIARIO
/// --------------------------------------------------------------------------------- */
function enabledRPPeriodo(form){
 if(form.chkPeriodo.checked) form.fPeriodo.disabled = false;
 else{form.fPeriodo.disabled = true; form.fPeriodo.value = '';}
}
function enabledRPContabilidad(form){
 if(form.chkContabilidad.checked) form.fContabilidad.disabled = false;
 else{form.fContabilidad.disabled = true; form.fContabilidad.value = '';}
}
function enabledRPVoucher(form){
 if(form.chkVoucher.checked) form.fVoucher.disabled = false;
 else{form.fVoucher.disabled = true; form.fVoucher.value = '';}
}
function enabledRPCuenta(form){
 if(form.chkCuenta.checked){ form.fCuentaDesde.disabled= false; form.fCuentaHasta.disabled=false;}
 else{ form.fCuentaDesde.disabled = true; form.fCuentaHasta.disabled = true; form.fCuentaDesde.value = ''; form.fCuentaHasta.value='';}
}
/// --------------------------------------------------------------------------------- */
/// ---------------	FUNCION QUE PERMITE MOSTRAR PDF REPORTE LIBRO DIARIO
function filtroReporteLibroDiario(form, limit) {

	var filtro1="";
	var codorganismo = document.getElementById("forganismo").value; 
	var Periodo = document.getElementById("fPeriodo").value; 
	if(form.chkorganismo.checked) filtro1+=" and a.CodOrganismo=*"+form.forganismo.value+"*";
	if(form.chkPeriodo.checked) filtro1+=" and a.Periodo=*"+form.fPeriodo.value+"*";
	else{ 
	     var PeriodoA = "0000-00";
		 var PeriodoB = "9999-99";
	     filtro1+=" and a.Periodo>=*"+PeriodoA+"*"+" and a.Periodo<=*"+PeriodoB+"*";
	}
	if(form.chkContabilidad.checked) filtro1+=" and b.CodLibroCont=*"+form.fContabilidad.value+"*";
	if(form.chkVoucher.checked) filtro1+=" and a.Voucher=*"+form.fVoucher.value+"*";

	var pagina="rp_librodiariopdf.php?filtro1="+filtro1+"&Periodo="+Periodo;
			cargarPagina(form, pagina);
}
/// --------------------------------------------------------------------------------- */
/// ---------------	FUNCION QUE PERMITE MOSTRAR PDF REPORTE LIBRO DIARIO
function filtroReporteLibroMayor(form, limit) {

	var filtro1="";
	var filtro2="";
	var Periodo = document.getElementById("fPeriodo").value;
	var codorganismo = document.getElementById("forganismo").value;
	if(form.chkorganismo.checked) filtro1+=" and a.CodOrganismo=*"+form.forganismo.value+"*";
	if(form.chkPeriodo.checked){
	   //if(Periodo==''){
	   //	  var PeriodoA = "0000-00";
	   //	  var PeriodoB = "9999-99";
	   //     filtro2+=" and a.Periodo>=*"+PeriodoA+"*"+" and a.Periodo<=*"+PeriodoB+"*";
	   //}else 
	   filtro1+=" and a.Periodo=*"+form.fPeriodo.value+"*";
	}
	if(form.chkContabilidad.checked) filtro1+=" and b.CodLibroCont=*"+form.fContabilidad.value+"*";
	if(form.chkCuenta.checked){ 
	   filtro1+=" and a.Voucher>=*"+form.fVoucher.value+"*";
	   filtro1+=" and a.Voucher<=*"+form.fVoucher.value+"*";
	}

	var pagina="rp_libromayorpdf.php?filtro1="+filtro1+"&Periodo="+Periodo;
			cargarPagina(form, pagina);
}
/// --------------------------------------------------------------------------------- */
/// ---------------	FUNCION QUE PERMITE MOSTRAR PDF REPORTE BALANCE COMPROBACION
function filtroReporteLibroDeComprobacion(form, limit) {

	var filtro1="";
	var codorganismo = document.getElementById("forganismo").value;
	if(form.chkorganismo.checked) filtro1+=" and ac.CodOrganismo=*"+form.forganismo.value+"*";
	if(form.chkContabilidad.checked) filtro1+=" and ac.CodLibroCont=*"+form.fContabilidad.value+"*";
	
	if(form.chkPeriodo.checked){
		var fdesde = new String (document.getElementById("fdesde").value);
		var fhasta = new String (document.getElementById("fhasta").value);
		//var fdesde = fdesde.split("-");
		    //fd = fdesde[2]+"-"+fdesde[1]+"-"+fdesde[0]; //alert(fd);
		
		//var fhasta = fhasta.split("-"); 
		    //fh = fhasta[2]+"-"+fhasta[1]+"-"+fhasta[0];
			fd = document.getElementById("fdesde").value;
			fh = document.getElementById("fhasta").value
		
	  filtro1+=" and ac.Periodo>=*"+fdesde+"*"+"and ac.Periodo <=*"+fhasta+"*";
	}else{
		fd = "0000-00"; fh = "9999-99";
	  filtro1+=" and ac.Periodo>=*"+fd+"*"+"and ac.Periodo <=*"+fh+"*";
	}

	var pagina="rp_balancecomprobacionpdf.php?filtro1="+filtro1+"&fd="+fd+"&fh="+fh;
			cargarPagina(form, pagina);
}
/// --------------------------------------------------------------------------------- */
function enabledFechaRpBalanceComprobacion(form){
  if(form.chkPeriodo.checked){ form.fdesde.disabled = false; form.fhasta.disabled = false;}
  else{ form.fdesde.disabled=true; form.fdesde.value=''; form.fhasta.disabled=true; form.fhasta.value='';}
}
/// --------------------------------------------------------------------------------- */
/// ---------------	FUNCION QUE PERMITE MOSTRAR PDF REPORTE BALANCE COMPROBACION
/// --------------- SUMAS Y SALDOS
function filtroReporteLibroComprobacionSumasSaldos(form, limit) {

	var filtro1="";
	var codorganismo = document.getElementById("forganismo").value;
	if(form.chkorganismo.checked) filtro1+=" and ac.CodOrganismo=*"+form.forganismo.value+"*";
	if(form.chkContabilidad.checked) filtro1+=" and ac.CodLibroCont=*"+form.fContabilidad.value+"*";
	
	if(form.chkPeriodo.checked){
		var fdesde = new String (document.getElementById("fdesde").value);
		var fhasta = new String (document.getElementById("fhasta").value);
		//var fdesde = fdesde.split("-");
		    //fd = fdesde[2]+"-"+fdesde[1]+"-"+fdesde[0]; //alert(fd);
		
		//var fhasta = fhasta.split("-"); 
		    //fh = fhasta[2]+"-"+fhasta[1]+"-"+fhasta[0];
			fd = document.getElementById("fdesde").value;
			fh = document.getElementById("fhasta").value
		
	  filtro1+=" and ac.Periodo>=*"+fdesde+"*"+"and ac.Periodo <=*"+fhasta+"*";
	}else{
		fd = "0000-00"; fh = "9999-99";
	  filtro1+=" and ac.Periodo>=*"+fd+"*"+"and ac.Periodo <=*"+fh+"*";
	}

	var pagina="rp_balancesumassaldospdf.php?filtro1="+filtro1+"&fd="+fd+"&fh="+fh;;
			cargarPagina(form, pagina);
}
/// --------------------------------------------------------------------------------- */
/// --------------------------------------------------------------------------------- */
/// --------------------------------------------------------------------------------- */
/// --------------------------------------------------------------------------------- */
/// --------------------------------------------------------------------------------- */
/// --------------------------------------------------------------------------------- */
/// --------------------------------------------------------------------------------- */
