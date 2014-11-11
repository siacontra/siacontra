
var xCoord = 80, yCoord = 30;
var ventanaEvento;
var fotoAnterior = '';

function crearVentanaAmpliarFoto()
{
	
	ventanaNormalEvento();//creando la vEmergente nueva
	ventanaEvento.esconder();
	
	
	
} 


function ventanaNormalEvento() 
{//..............................................
	

	ventanaEvento = new vEmergente
		(
		'capaInternaVentana',
		'Foto',
		xCoord, yCoord,
		470, 490,
		true, 
		true, 
		false, 
		true, 
		null, null, null, null
		);	
		
		//ventanaEvento.esconder();
}//................................................



function ampliarFoto(rutaNombreFoto)
{
	var contenido = '<img id="capaFoto" src="'+rutaNombreFoto+'" style="max-height:550px; max-width:460px;" />';

	var capaContenidoVent = xGetElementById('capaInternaVentana');
	capaContenidoVent.innerHTML = contenido;
	ventanaEvento.mostrar();

}

function uploadFile( file ){

	var nombreFoto = ''+xGetElementById('CodPersona').value;//+''+''+xGetElementById('CodSecuencia').value+'';
	var aleatorio = getRandomArbitrary(100, 999);
	var variables = '&CodPersona='+xGetElementById('CodPersona').value+'&CodSecuencia='+xGetElementById('CodSecuencia').value;

	//5MB
	var limit = 1048576*8,xhr;


	console.log( limit  )

	if( file ){
		if( file.size < limit ){
			if( !confirm('Cargar archivo?') ){return;}

			xhr = new XMLHttpRequest();

			xhr.upload.addEventListener('load',function(e){
				alert('Archivo cargado!');
				xGetElementById('imgFoto').src="../imagenes/fotos/"+aleatorio+nombreFoto+'.jpg';
			}, false);

			xhr.upload.addEventListener('error',function(e){
				alert('Ha habido un error :/');
			}, false);

			xhr.open('POST','lib/controladorCes.php?nombreFoto='+nombreFoto+'&caso=cargarImagenFamiliar&aleatorio='+aleatorio+variables);

			    xhr.setRequestHeader("Cache-Control", "no-cache");
			    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
			    xhr.setRequestHeader("X-File-Name", file.name);

			    xhr.send(file);

		}else{
			alert('El archivo es mayor que 2MB!');
			return;
		}
	}
	
	//fotoAnterior = nombreNuevo
//	xGetElementById('imgFoto').src="../imagenes/fotos/"+aleatorio+nombreFoto+'.jpg';
	//xGetElementById('imgFoto').src="../imagenes/tmp/tmp_"+aleatorio+nombreFoto+'.jpg';
	xGetElementById('objLinkCargar').href = "javascript:ampliarFoto('../imagenes/fotos/"+aleatorio+nombreFoto+".jpg');";
	xGetElementById('numAleatorioFoto').value = ''+aleatorio+'';
	//alert(aleatorio);
	//xGetElementById('capaFoto').src="../imagenes/fotos/"+aleatorio+nombreFoto+'.jpg';
	
}

// Returns a random number between min and max, integer
function getRandomArbitrary(min, max) 
{
	//return Math.random() * (max - min) + min;
	return Math.floor((Math.random()*max)+min); 
}

