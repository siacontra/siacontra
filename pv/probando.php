<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<script type="text/javascript"/>
function insertarFila(Modo) {
  var elmTBODY = document.getElementById('CuerpoTabla');
  var elmTR;
  var elmTD;
  var elmText;
  if (Modo==0) { // Modo HTML
     elmTR = elmTBODY.insertRow(2);
     for (var i=0; i<3; i++) {
        elmTD = elmTR.insertCell(i);
        elmText = document.createTextNode('Nueva celda.');
        elmTD.appendChild(elmText);
        }
     }
  if (Modo==1) { // Modo Núcleo
     elmTR = document.createElement('tr');
     for (var i=0; i<3; i++) {
        elmTD = document.createElement('td');
        elmText = document.createTextNode('Nueva celda.');
        elmTD.appendChild(elmText);
        elmTR.appendChild(elmTD);
        }
     elmTBODY.insertBefore(elmTR,elmTBODY.childNodes[3])
     }
}
 
function eliminarFila(Modo) {
  var elmTBODY = document.getElementById('CuerpoTabla');
  if (Modo==0) elmTBODY.deleteRow(2);
  if (Modo==1) elmTBODY.removeChild(elmTBODY.childNodes[2]);
}
</script>
</head>
<body>
<table width="500" align="center" height="150" border="2">
<tr>
 <td><input id="monto" name="monto" size="10" onblur="if (! isNaN(this.value)) alert('El valor debe ser numérico.');"/></td>
</tr>
</table>
<table id="Modo" border="1" bordercolor="#0033FF">
<tbody id="CuerpoTabla">
<tr>
 <td bordercolor="#000066" bgcolor="#0066FF">Celda de encabezado</td>
 <td bordercolor="#000066" bgcolor="#0066FF">Celda de encabezado</td>
 <td bordercolor="#000066" bgcolor="#0066FF">Celda de encabezado</td>
</tr>
<tr>
 <td>Celda</td>
 <td>Celda</td>
 <td>Celda</td>
</tr>
<tr>
 <td>Celda</td>
 <td>Celda</td>
 <td>Celda</td>
</tr>
</tbody>
</table>
<div class="Ejemplo">
<form class="Oc1" action="pad/error.php" method="post" onsubmit="insertarFila(document.getElementById('ListaIf').selectedIndex);return false;">
<div><label for="ListaIf">Insertar fila usando: </label>
    <select id="ListaIf">
	  <option selected="selected">DOM HTML</option>
	  <option>DOM N&uacute;cleo</option></select> 
	  <input type="hidden" name="D" value="87" />
	  <input type="submit" class="Boton" value="Probar" /></div>
</form>
<form class="Oc1" action="pad/error.php" method="post" onsubmit="eliminarFila(document.getElementById('ListaEf').selectedIndex);return false;">
<div><label for="ListaEf">Eliminar fila usando: </label>
 <select id="ListaEf">
  <option selected="selected">DOM HTML</option>
  <option>DOM N&uacute;cleo</option>
 </select> 
  <input type="hidden" name="D" value="87" />
  <input type="submit" class="Boton" value="Probar" />
</div>
</form>
</div>

</body>
</html>
