/****************************************************************************************
* LOCACIÓN::VENEZUELA-SUCRE-CUMANÁ
* PROGRAMADOR: Christian Hernández
* 
* DESCRIPCION: 
* VERSION: 0.1 Beta
*********************************************************************************************/ 

function crearObjeto(tipo,propiedades,texto,nodoPadre)
{
	var objHtml = xCreateElement(tipo);
	
	for (pr in propiedades)
	{
		objHtml.setAttribute(pr, propiedades[pr]);
		
	}
	
	if(texto != null)
	{
		
		var objTexto = document.createTextNode(texto);
        xAppendChild(objHtml,objTexto);
	}
	
	if(nodoPadre != null)
	{
		xAppendChild(nodoPadre,objHtml);
		//return;
		
	} else {
		
		return objHtml;
	}

}

function asociaHijo(obj,nodoPadre)
{
	xAppendChild(nodoPadre,obj);
	
}

function eliminaHijo(objHtml)
{
	while(objHtml.firstChild)
	{
	
		objHtml.removeChild(objHtml.firstChild);
	}
	
}

function insertarTexto(objHtml,texto)
{
	var objTtexto = document.createTextNode(texto);
	xAppendChild(objHtml,objTtexto);

	
}

function agregarAtributo(objHtml,propiedades)
{
	 if (!xDef(objHtml) || !xDef(propiedades) || (typeof propiedades != 'object')) return;

        for (pr in propiedades)
        {
			objHtml.setAttribute(pr, propiedades[pr]);
            
        }
}

function eliminarNodo(id)
{
	var nodoEliminar = xGetElementById(id);
	
	if(nodoEliminar != null)
	{
		nodoEliminar.parentNode.removeChild(nodoEliminar);
	}
}
