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

///////////////// **  CODIGO DE PRUEBA ** ///////////////////
//	FUNCION PARA ELIMINAR UN REGISTRO DE LA BASE DE DATOS
function eliminarRegistro(form, pagina, foraneo, modulo) {
	var codigo=form.registro.value;
	if (codigo=="") msjError(1000);
	else {
		//	PREGUNTO SI DESEA ELIMINAR
		var eliminar=confirm("¡Esta seguro de eliminar este registro ahora?");
		if (eliminar) {
			if (foraneo) {
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax=nuevoAjax();
				ajax.open("POST", "fphp.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("modulo="+modulo+"&accion=ELIMINAR&codigo="+codigo);
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


//	FUNCION PARA ELIMINAR UN REGISTRO DE LA BASE DE DATOS
function eliminarDato(form, pagina, foraneo, modulo) {
	var filas = form.registro.length;
	var filtro = form.filtro.value;
	var limit = form.limit.value;
	var codigo = "";
	if (filas>1) { for (i=0; i<filas; i++) if (form.registro[i].checked==true) codigo=form.registro[i].value;	} 
	else if (form.registro.checked==true) codigo=form.registro.value;
	if (codigo=="") msjError(1000);
	else {
		//	PREGUNTO SI DESEA ELIMINAR
		var eliminar=confirm("¡Esta seguro de eliminar este registro?");
		if (eliminar) {
			if (foraneo) {
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax=nuevoAjax();
				ajax.open("POST", "fphp.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("modulo="+modulo+"&accion=ELIMINAR&codigo="+codigo);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4)	{
						var error=ajax.responseText;
						if (error!=0) alert ("¡"+error+"!");
						else {
							form.method="POST";
							form.action=pagina+"&filtro="+filtro+"&limit=0";
							form.submit();
						}
					}
				}
			} else cargarPagina(form, pagina);
		}
	}
}
////////////////////////CONFIRMAR////////////////////////////////////
function eliminar(){ 
if(accion==eliminars){
  if(confirm("Seguro que desea eliminar el registro")){
    location.href="gmeliminar.php?query";
  }
}
}
////////////////////////////////////////////////////////////////////
function confirmDel()
{
var agree=confirm("¿Realmente desea eliminarlo? ");
if (agree) return true ;
else return false ;
}
////////////////////////////////////////////////////////////////////