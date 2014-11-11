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
	  		 alert("Escriba los datos correctos en el campo \"C�digo\".");
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
	         alert("Escriba s�lo n�meros en el campo \"C�digo\"."); 
	         formulario.codigo.focus(); 
	         return (false); 
	       } 
		   //VALIDACION DESCRIPCION
		   if (formulario.descripcion.value.length <2) {
	  		 alert("Escriba los datos correctos en el campo \"Descripci�n\".");
	   		 formulario.descripcion.focus();
	      return (false);
	      }
          var checkOK = "ABCDEFGHIJKLMN�OPQRSTUVWXYZ�����" + "abcdefghijklmn�opqrstuvwxyz����� ";
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
	         alert("Escriba s�lo letras en el campo \"Descripci�n\"."); 
	         formulario.descripcion.focus(); 
	         return (false); 
	       } 
	return (true); 
} 
///////////////////////////	PROYECTO  //////////////////////////////////
function verificarproyecto(formulario) {
	
	       //VALIDACION COD_SUBPROG
		   if (formulario.codsubprog.value.length <1) {
	  		 alert("Escriba los datos correctos en el campo \"C�digo Sub-Programa\".");
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
	         alert("Escriba s�lo n�meros en el campo \"C�digo Sub-Programa\"."); 
	         formulario.codsubprog.focus(); 
	         return (false); 
	       } 
		   //VALIDACION PROYECTO
		   if (formulario.codproyecto.value.length <1) {
	  		 alert("Escriba los datos correctos en el campo \"C�digo Proyecto\".");
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
	         alert("Escriba s�lo n�meros en el campo \"C�digo Proyecto\"."); 
	         formulario.codproyecto.focus(); 
	         return (false); 
	       } 
		   //VALIDACION DESCRIPCION
		   if (formulario.descripcion.value.length <2) {
	  		 alert("Escriba los datos correctos en el campo \"Descripci�n\".");
	   		 formulario.descripcion.focus();
	      return (false);
	      }
          var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZ�" + "abcdefghijklmnopqrstuvwxyz�" + "._";
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
	         alert("Escriba s�lo letras en el campo \"Descripci�n\"."); 
	         formulario.descripcion.focus(); 
	         return (false); 
	       } 
	return (true); 
	} 