/****************************************************************************************
* DEV: SOLUCIONES INFORMATICAS MANZANARES
* MODULO: FUNCIONES COMUNES JAVA SCRIPT
* OPERADORES_______________________________________________
* | # | PROGRAMADOR          |  |   FECHA    |  |   HORA   |
* | 1 | Andy David Vasquez   |  | 21/07/2009 |  | 03:16:18 |
* |________________________________________________________|
*
* DESCRIPCION: ARCHIVO DE MANIPULACION DEL TEXTO ALTERNATIVO MULTINAVEGADOR EN EL SISTEMA
* TIPO:JAVA SCRIPTS
* VERSION: 0.1 
* SOPORTE: www.sim.org.ve
*******************************************************************************************/

//DENTRO DE LA FUNCION DE ARRANQUE DEL SISTEMA SE DEBE HACER EL SIGUIENTE LLAMADO
//if(!document.all) {document.captureEvents(Event.MOUSEMOVE);}
//document.onmousemove = leerCoordRaton;
var IDALT = "ALTdHTML"; //VARIABLE QUE CONTIENE EL ID DEL DIV QUE VA A PRESENTAR EL TEXTO ALTERNATIVO EN EL NAVEGADOR
var xCoord = 0;//registramos la ultima coordenada leida en el centinela de coordenadasx
var yCoord = 0;//registramos la ultima coordenada leida en el centinela de coordenadasx
var nagName = '';//indica el nombre del navegador que actualmente tiene el usuario en su terminal
var nagVer = '';//indica la version del navegador que actualmente tiene el usuario en su terminal
var TLBMSG = 'MEGAGOL'; //label o etiqueta para los mensajes de sistema de la pagina megagol
var VSTM ='0.1'//numero de la version del sitio web megagol

function crearAlt(texto,colorcss) 
{

	if(!colorcss){colorcss = "megagolAlternativo";}
	document.getElementById(IDALT).innerHTML = '<div class="'+colorcss+'">' + texto + '</div>';
	document.getElementById(IDALT).style.visibility = 'visible';
	//document.getElementById(IDALT).style.left = xCoord+35+'px';
	//document.getElementById(IDALT).style.top  = yCoord+20+'px';
	
	//alert(xCoord + '-'+yCoord);
}


function hide1() 
{
	document.getElementById(IDALT).style.visibility = 'hidden';
	document.getElementById(IDALT).innerHTML = "";

}


function leerCoordRaton(evt)
{
   var e = new xEvent(evt);
   xCoord = e.pageX;//registramos la ultima coordenada leida en el centinela de coordenadasx
   yCoord = e.pageY;
  
  
    document.getElementById(IDALT).style.left = xCoord+10+'px';
    document.getElementById(IDALT).style.top  = yCoord+14+'px';
	
}


function determinaNavegador()
{//____________________funcion encargada de determinar el navegador retornando el nombre o la version solicitada
	
	var prt = new Array();
	var prt2 = new Array();
	
   //determinamos en que navegador estamos trabajando
   //xGetElementById('txtUsuarioMeg').value = navigator.userAgent; // prompt('a',navigator.userAgent);
   switch(navigator.appName)
   {
   	   case 'Netscape':
	      if (/Konqueror|Safari|KHTML/.test(navigator.userAgent))
			  {//Mozilla/5.0 (Windows; U; Windows NT 5.1; es) AppleWebKit/522.15.5 (KHTML, like Gecko) Version/3.0.3 Safari/522.15.5
			      
			      prt = navigator.userAgent.split('/');prt2 = prt[3].split(' ');
				  nagName = 'Safari';
				  nagVer = prt2[0];
			  	  //alert(nagName+'-'+nagVer);
			  }
		  else
		      {//Mozilla/5.0 (Windows; U; Windows NT 5.1; es-ES; rv:1.9.0.6) Gecko/2009011913 Firefox/3.0.6
			  	 if (/Firefox/.test(navigator.userAgent))
					  {
					  	prt = navigator.userAgent.split('/');
						nagName = 'Firefox';
						nagVer = prt[3];
					  }
			  }
	   break;
	   
	   case 'Opera':
	      //Opera/9.64 (Windows NT 5.1; U; es-ES) Presto/2.1.1
	      prt = navigator.userAgent.split(' ');prt2 = prt[0].split('/');
		  nagName = 'Opera';
		  nagVer = prt2[1];
		  
	   break;
	   	   
	   case 'Microsoft Internet Explorer':
	   
	        prt = navigator.userAgent.split(';');prt2 = prt[1].split(' ');
		    nagName = 'IExplorer';
		    nagVer = prt2[2];
			
			
	     
	   break //4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; WinuE v6; WinuE v6)
	      
   }
   
	
	//alert(nagName+'-'+nagVer);
	
}//____________________funcion encargada de determinar el navegador retornando el nombre o la version solicitada


function cambiaClass(id,clase)
{
	xGetElementById(id).className = clase;
}
