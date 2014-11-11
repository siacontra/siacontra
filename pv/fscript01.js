//	FUNCION PARA CARGAR UNA NUEVA PAGINA 
function cargarPagina(form, pagina) {
	form.method="POST";
	form.action=pagina;
	form.submit();
}
//	MAESTRO DE APLICACIONES
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
//////////////////////  VALIDACION DE ENTRADA DE DATOS  /////////////////////////
function verificarsector(formulario) {
	
	       //VALIDACION COD_SECTOR
		   if (formulario.codigo.value.length <1) {
	  		 alert("Escriba los datos correctos en el campo \"Código\".");
	   		 formulario.codigo.focus();
	      return (false);
	      }
          var checkOK ="0123456789";
	      var checkStr = formulario.codigo.value;
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
	         alert("Escriba sólo números en el campo \"Código\"."); 
	         formulario.codigo.focus(); 
	         return (false); 
	       } 
		   //VALIDACION DESCRIPCION
		   if (formulario.descripcion.value.length <2) {
	  		 alert("Escriba los datos correctos en el campo \"Descripción\".");
	   		 formulario.descripcion.focus();
	      return (false);
	      }
          var checkOK = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚ" + "abcdefghijklmnñopqrstuvwxyzáéíóú ";
	      var checkStr = formulario.descripcion.value;
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
	         alert("Escriba sólo letras en el campo \"Descripción\"."); 
	         formulario.descripcion.focus(); 
	         return (false); 
	       } 
	return (true); 
} 
///////////////////////////	PROYECTO  //////////////////////////////////
function verificarproyecto(formulario) {
	
	       //VALIDACION COD_SUBPROG
		   if (formulario.codsubprog.value.length <1) {
	  		 alert("Escriba los datos correctos en el campo \"Código Sub-Programa\".");
	   		 formulario.codsubprog.focus();
	      return (false);
	      }
          var checkOK ="0123456789";
	      var checkStr = formulario.codsubprog.value;
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
	         alert("Escriba sólo números en el campo \"Código Sub-Programa\"."); 
	         formulario.codsubprog.focus(); 
	         return (false); 
	       } 
		   //VALIDACION PROYECTO
		   if (formulario.codproyecto.value.length <1) {
	  		 alert("Escriba los datos correctos en el campo \"Código Proyecto\".");
	   		 formulario.codproyecto.focus();
	      return (false);
	      }
          var checkOK ="0123456789";
	      var checkStr = formulario.codproyecto.value;
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
	         alert("Escriba sólo números en el campo \"Código Proyecto\"."); 
	         formulario.codproyecto.focus(); 
	         return (false); 
	       } 
		   //VALIDACION DESCRIPCION
		   if (formulario.descripcion.value.length <2) {
	  		 alert("Escriba los datos correctos en el campo \"Descripción\".");
	   		 formulario.descripcion.focus();
	      return (false);
	      }
          var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ" + "._";
	      var checkStr = formulario.descripcion.value;
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
	         alert("Escriba sólo letras en el campo \"Descripción\"."); 
	         formulario.descripcion.focus(); 
	         return (false); 
	       } 
	return (true); 
	} 
/// ***********************************************************************************
function guardarCierreMesPresupuestario(form,accion){
    var CodOrganismo = document.getElementById("CodOrganismo").value; //alert(CodOrganismo);
	var ejercicioPpto = document.getElementById("ejercicioPpto").value;
	var periodo = document.getElementById("periodo").value;
	var nroPresupuesto = document.getElementById("nropresupuesto").value;
	
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "gmsector.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=guardarCierreMesPresupuestario&CodOrganismo="+CodOrganismo+"&periodo="+periodo+"&ejercicioPpto="+ejercicioPpto+"&nroPresupuesto="+nroPresupuesto);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != "") alert(resp.trim());
				form.submit();
			}
	}
	return false;
}	
/// ***********************************************************************************
/// FILTRO PROCESO CIERRE MES PRESUPUESTARIO
/*function enabledCierreEjercicioPpto(form){
  if(form.chkejercicioPpto.checked) form.fejercicioppto.disabled= false;
  else{
	form.fejercicioppto.disabled = true; form.fejercicioppto.value='';  }
}*/
function enabledCierreNroPresupuesto(form){
  if(form.chknropresupuesto.checked){ form.fnropresupuesto.disabled = false; form.btnropresup.disabled=false;
  }else{ form.fnropresupuesto.disabled=true; form.fnropresupuesto.value = ''; form.btnropresup.disabled=true;}
}
/// ***********************************************************************************
/// ***********************************************************************************
/// ***********************************************************************************
/// ***********************************************************************************
/// ***********************************************************************************
/// ***********************************************************************************
/// ***********************************************************************************