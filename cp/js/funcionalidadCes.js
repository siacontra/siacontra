/****************************************************************************************
* DEV: CONTRALORIA DEL ESTADO SUCRE - VENEZUELA
* PROYECTO: SAICOM
* OPERADORES_____________________________________________________________________________________________________________________________
* | # | PROGRAMADOR          |  |   FECHA    |  |   HORA   |   CELULAR  |   VERSION PAG  | DESCRIPCION DEL CAMBIO 
* | 2 | Christian Hernandez   |  | 27/09/2012 |  | 02:16:18 | 04128354891|      0.1.1.A   | creacion del script
* |________________________________________________________|_____________________________________________________________________________
* TIPO: JS
* DESCRIPCION: 
* UBICACION: VENEZUELA, SUCRE, CUMANA
* VERSION: 0.1.1.A 
* SOPORTE: Christian Hernandez 
* CONTACTO: www.cgesucre.gob.ve, @CESucre, contraloria.estado.sucre@cgesucre.gob.ve
*******************************************************************************************/

var ventanaEvento = null;//variable que contiene la ventana emergente
var ventanaEventoExterno = null;//variable que contiene la ventana emergente

var xCoord = 5, yCoord = 5;


  
function crearVentanaAvisoDocumentoInterno()
{
	
	
  	var Tam = TamVentana();
    //alert('La ventana mide: [' + Tam[0] + ', ' + Tam[1] + ']');
    
    
	ventanaEvento = new vEmergente(
		'ventanaAvisoDocumento',
		'Usted posee los Siguientes Documentos Internos sin Enviar el Acuse',
		Tam[0]-460, Tam[1]-220,
		450, 210,
		true, 
		true, 
		false, 
		true, 
		null, null, null, null);	
		
	ventanaEvento.esconder();
	
	var capaContenidoVent = xGetElementById('ventanaAvisoDocumento');
	capaContenidoVent.style.overflow = "scroll";
	
	capaContenidoVent.className = 'ces_fuente';
	var contenidoCapaVentana;
	
	AjaxRequest.post
	(
		{
			'url':'lib/controladorCes.php',
			'parameters':{'caso':'buscarDocumentoInternoAviso'},
			'queryString':'',
			'onSuccess': function(req)
							{

								if (req.responseText != 0)
								{
									
									capaContenidoVent.innerHTML = req.responseText;
									ventanaEvento.mostrar();
									
								} else {
									
									ventanaEvento.esconder();
								}
							
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);
	
		
	
	
	
} 

function crearVentanaAvisoDocumentoExterno()
{
	
	
  	var Tam = TamVentana();
    //alert('La ventana mide: [' + Tam[0] + ', ' + Tam[1] + ']');
    
    
	ventanaEventoExterno = new vEmergente(
		'ventanaAvisoDocumentoExterno',
		'Usted posee los Siguientes Documentos Externos sin Atender',
		Tam[0]-460, Tam[1]-440,
		450, 210,
		true, 
		true, 
		false, 
		true, 
		null, null, null, null);	
		
	ventanaEventoExterno.esconder();
	
	var capaContenidoVent = xGetElementById('ventanaAvisoDocumentoExterno');
	capaContenidoVent.style.overflow = "scroll";
	
	capaContenidoVent.className = 'ces_fuente';
	var contenidoCapaVentana;
	
	AjaxRequest.post
	(
		{
			'url':'lib/controladorCes.php',
			'parameters':{'caso':'buscarDocumentoExternoAviso'},
			'queryString':'',
			'onSuccess': function(req)
							{

								if (req.responseText != 0)
								{
									
									capaContenidoVent.innerHTML = req.responseText;
									ventanaEventoExterno.mostrar();
									
								} else {
									
									ventanaEventoExterno.esconder();
								}
							
							},
			'onError': function(req)
					{ 
						alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
					}
		}
	);
	
		
	
	
	
} 

						
/*function ventanaNormalEvento()
{//..............................................
	

	ventanaEvento = new vEmergente
		(
		'ventanaAvisoDocumento',
		'<span style="color:#F00">AVISO</span>',
		xCoord, yCoord,
		750, 450,
		true, 
		true, 
		false, 
		true, 
		null, null, null, null
		);	
		
		//ventanaEvento.esconder();
}//................................................
*/