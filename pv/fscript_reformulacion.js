//// ------------------------------------------------------------------------------
////	**** FUNCION QUE MUESTRA MENSAJES DE ERROR
function msjError(error) {
	switch (error) {
		case 1000: alert ("¡SELECCIONE UN REGISTRO!"); break;
		case 1010: alert ("¡LOS CAMPOS MARCADOS CON (*) SON OBLIGATORIOS!"); break;
		case 1020: alert ("¡REGISTRO EXISTENTE!"); break;
		case 1030: alert ("¡PERIODO CONTABLE INCORRECTO!"); break;
		case 1040: alert ("¡FECHA FUNDACION INCORRECTA!"); break;
		case 1050: alert ("¡DEBE LLENAR EL CAMPO RIESGO!"); break;
		case 1060: alert ("¡MONTO INCORRECTO!"); break;
		case 1070: alert ("¡NUMERO INCORRECTO!"); break;
		case 1080: alert ("¡FECHA DE NACIMIENTO INCORRECTA!"); break;
		case 1090: alert ("¡FECHA DE ESTADO CIVIL INCORRECTA!"); break;
		case 1100: alert ("¡FECHA DE EXPIRACION DE LICENCIA INCORRECTA!"); break;
		case 1110: alert ("¡FECHA DE INGRESO INCORRECTA!"); break;
		case 1120: alert ("¡FECHA DE CESE INCORRECTA!"); break;
		case 1130: alert ("¡DEBE SELECCIONAR UN VALOR EN EL FILTRO BUSCAR!"); break;
		case 1140: alert ("¡FECHA DE VIGENCIA DE CONTRATO INCORRECTA!"); break;
		case 1150: alert ("¡FECHA DE FIRMA INCORRECTA!"); break;
		case 1160: alert ("¡FECHA DE BAJA INCORRECTA!"); break;
		case 1170: alert ("¡DEBE SELECCIONAR UN CARGO!"); break;
		case 1180: alert ("¡DEBE LLENAR TODOS LOS CAMPOS!"); break;
		case 1190: alert ("¡SUELDO INCORRECTO!"); break;
		case 1200: alert ("FECHA DE ENTREGA INCORRECTA!"); break;
		case 1210: alert ("¡DEBE SELECCIONAR LA PERSONA RELACIONADA!"); break;
		case 1220: alert ("¡SELECCIONE UN DOCUMENTO!"); break;
		case 1230: alert ("¡FECHA DE ESTADO INCORRECTA!"); break;
		case 1240: alert ("¡FECHA DE VENCIMIENTO INCORRECTA!"); break;
		case 1250: alert ("¡LA FECHA DE ESTADO NO PUEDE SER MENOR QUE LA FECHA DE ENTREGA!"); break;
		case 1260: alert ("¡FECHA INCORECTA!"); break;
		case 1270: alert ("¡MONTO DE HORAS INCORRECTO!"); break;
		case 1280: alert ("¡MONTO DE AÑOS INCORRECTO!"); break;
		case 1290: alert ("¡FECHA DE DOCUMENTO INCORECTA!"); break;
		case 1300: alert ("¡PERIODO DE SUSPENSION INCORECTA!"); break;
		case 1310: alert ("¡INTERVALOS DE FECHAS Y HORAS INCORRECTA!"); break;
		case 1320: alert ("¡VALOR INCORRECTO!"); break;
		case 1330: alert ("¡VALOR NOMINAL INCORRECTO!"); break;
		case 1340: alert ("¡CANTIDAD INCORRECTA!"); break;
		case 1350: alert ("¡AÑO DEL VEHICULO INCORRECTO!"); break;
		case 1360: alert ("¡VALOR DE COMPRA INCORRECTO!"); break;
		case 1370: alert ("¡FECHA DE VIGENCIA DEL REQUERIMIENTO INCORRECTA!"); break;
		case 1380: alert ("¡FECHA DE SOLICITUD INCORRECTA!"); break;
		case 1390: alert ("¡FECHA DE CAPACITACION INCORRECTA!"); break;
		case 1400: alert ("¡MONTO DE COSTO INCORRECTO!"); break;
		case 1410: alert ("¡MONTO ASUMIDO INCORRECTO!"); break;
		case 1420: alert ("¡MONTO DE COSTO INCORRECTO!"); break;
		case 1430: alert ("¡NUMERO DE VACANTES INCORRECTO!"); break;
		case 1440: alert ("¡FECHA DE VIGENCIA INCORRECTA!"); break;
		case 1450: alert ("¡DEBE INGRESAR LA HORA DE INICIO Y FIN DEL DIA SELECCIONADO!"); break;
		case 1460: alert ("¡DEBE INGRESAR EL HORARIO DE LA CAPACITACION!"); break;
		case 1470: alert ("¡MONTO INCORRECTO!"); break;
	}
}
//// ------------------------------------------------------------------------------
////     **** FUNCION PARA ELIMINAR PARTIDA AGREGADA
function EliminarPartidaReformulacion(form, pagina){
  var valor = document.getElementById("valor").value; //alert('=='+valor);
  var Organismo = document.getElementById("Org").value; 
  var CodPresupuesto = document.getElementById("num_presupuesto").value;
  var CodRef = document.getElementById("CodRef").value;
  
  if (valor=="") msjError(1000);
	else {
		//	PREGUNTO SI DESEA ELIMINAR
		var eliminar=confirm('¡Esta seguro de eliminar¡');
		if (eliminar) {
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax=nuevoAjax();
				ajax.open("POST", "fphp.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("&accion=EliminarPartidaReformulacion&valor="+valor+"&CodPresupuesto="+CodPresupuesto+"&Organismo="+Organismo+"&CodRef="+CodRef);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4)	{
						var error=ajax.responseText;
						if (error!=0) alert ("¡"+error+"!");
						else {cargarPagina(form, pagina); 
						      document.getElementById("tab2").style.display="block";
							  }
					}
				}
		} else cargarPagina(form, pagina);
	}
}
//// ------------------------------------------------------------------------------
////    **** FUNCION PARA CARGAR VALOR DE PARTIDA
function CargarValorReforPartida(form, id){
    //alert('Valor='+id);	
   document.getElementById("valor").value = id; //alert('='+ document.getElementById("valor").value);
}
//// ------------------------------------------------------------------------------
////    **** FUNCION PARA CARGAR VALOR DE PARTIDA
function EliminarPartReforCanc(form){
  var valor = document.getElementById("valor").value;
  var Organismo = document.getElementById("Org").value; 
  var CodPresupuesto = document.getElementById("num_presupuesto").value;
  var CodRef = document.getElementById("CodRef").value;
  
  var ajax=nuevoAjax();
	  ajax.open("POST", "fphp.php", true);
	  ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	  ajax.send("&accion=EliminarPartReforCanc&valor="+valor+"&CodPresupuesto="+CodPresupuesto+"&Organismo="+Organismo+"&CodRef="+CodRef);
	  ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var error=ajax.responseText;
			if (error!=0) alert ("¡"+error+"!");
		}
	}
}