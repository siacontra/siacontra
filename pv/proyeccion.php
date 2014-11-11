<?php
session_start();

if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location:index.php");
//	------------------------------------
include("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);
//	------------------------------------
include("../clases/MySQL.php");
include("../comunes/objConexion.php");
include("../clases/Excel.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="../js/AjaxRequest.js"></script>
<script type="text/javascript" language="javascript" src="../js/xCes.js"></script>
<script type="text/javascript" language="javascript" src="js/tw-sack.js"></script>

<script type="text/javascript" language="javascript" src="js/jquery-1.4.2.min.js"></script>


<script type="text/javascript" language="javascript">
function comparaFecha(){

var str1 = document.getElementById("fdesde").value;
var str2 = document.getElementById("fhasta").value;

var dt1  = parseInt(str1.substring(0,2),10);
var mon1 = parseInt(str1.substring(3,5),10);
var yr1  = parseInt(str1.substring(6,10),10);
var dt2  = parseInt(str2.substring(0,2),10);
var mon2 = parseInt(str2.substring(3,5),10);
var yr2  = parseInt(str2.substring(6,10),10);
var date1 = new Date(yr1, mon1, dt1);
var date2 = new Date(yr2, mon2, dt2); 

var diferencia = date1 - date2;
var Dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
   Dias = -1 * Dias;
   document.getElementById("dias").value= Dias + 1;
}





/*-------------------------------------------------------------------------------------------------*/
</script>
<style type="text/css">
<!--
UNKNOWN {FONT-SIZE: small}
#header {FONT-SIZE: 93%; BACKGROUND: url(imagenes/bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal}
#header UL {PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none}
#header LI {
        PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(imagenes/left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px}
#header A {PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(imagenes/right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none}
#header A {FLOAT: none}
#header A:hover {COLOR: #333}
#header #current {BACKGROUND-IMAGE: url(imagenes/left_on.gif)}
#header #current A {BACKGROUND-IMAGE: url(imagenes/right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333}
-->

.botonExcel{cursor:pointer;}
</style>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
 <td class="titulo">Proyección Gasto</td>
 <td align="right"><a class="cerrar"  href="framemain.php";>[Cerrar]</a></td>
</tr>
</table><hr width="100%" color="#333333" />

<!--////////////////////////// **********DATOS GENERALES DEL PRESUPUESTO*************  ///////////////////////-->   
<div id="tab1" style="display:block;">
<div style="width:800px" class="divFormCaption">Informaci&oacute;n de la Proyección</div>
<table width="800" class="tblForm">
<tr>
 <td width="123"></td>
 <td colspan="2"></td>
</tr>
</table>
<table width="800" class="tblForm">

 <td width="161"></td>
 <td width="103" align="right">Mes Inicio :</td>
 <td width="92">
 	<select id="sel_mes_inicio" >
											<option value="01">ENERO</option>
											<option value="02">FEBRERO</option>
											<option value="03">MARZO</option>
											<option value="04">ABRIL</option>
											<option value="05">MAYO</option>
											<option value="06">JUNIO</option>
											<option value="07">JULIO</option>
											<option value="08">AGOSTO</option>
											<option value="09">SEPTIEMBRE</option>
											<option value="10">OCTUBRE</option>
											<option value="11">NOVIEMBRE</option>
											<option value="12">DICIEMBRE</option>											
	</select>	
 </td>
 <td width="100" align="right">Mes Final :</td>
 <td width="138">
 	<select id="sel_mes_final"   >
											<option value="01">ENERO</option>
											<option value="02">FEBRERO</option>
											<option value="03">MARZO</option>
											<option value="04">ABRIL</option>
											<option value="05">MAYO</option>
											<option value="06">JUNIO</option>
											<option value="07">JULIO</option>
											<option value="08">AGOSTO</option>
											<option value="09">SEPTIEMBRE</option>
											<option value="10">OCTUBRE</option>
											<option value="11">NOVIEMBRE</option>
											<option value="12" selected="selected">DICIEMBRE</option>											
	</select>
 </td>
 <td width="178"></td>
</tr>
<tr>
  <td width="161"></td>
  <td width="103" align="right">Desde la Partida :</td>
  <td width="92">
    <?php 		
		$sql="SELECT DISTINCT `partida`,`tipocuenta` FROM pv_presupuestodet WHERE Organismo='".$_SESSION['ORGANISMO_ACTUAL']."' AND Estado='AP' ORDER BY cod_partida ASC ";
		
				$objConexion->ejecutarQuery($sql);
		$resp = $objConexion->getMatrizCompleta();
		
		/*$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
 		$respP=mysql_fetch_array($query); 
		echo $respP;*/
				
	?>
    
    <select name="sel_desde_partida" id="sel_desde_partida">
      <?php for($i=0;$i<count($resp); $i++){  ?>
      <option value="<?=$resp[$i]['tipocuenta'].$resp[$i]['partida'];?>"><?=$resp[$i]['tipocuenta'].$resp[$i]['partida'];?></option>	
      <?php } ?>									
      </select>	
    </td>
  <td width="100" align="right">Hasta la Partida</td>
  <td width="138">
    <select name="sel_hasta_partida" id="sel_hasta_partida" >
      <?php for($i=0;$i<count($resp); $i++){  ?>
      <option value="<?=$resp[$i]['tipocuenta'].$resp[$i]['partida'];?>"><?=$resp[$i]['tipocuenta'].$resp[$i]['partida'];?></option>	
      <?php } ?>								
      </select>
    </td>
  <td width="178"></td>
</tr>

<tr>
  <td></td>
  <td align="right">&nbsp;</td>
  <td colspan="2" align="center">&nbsp;</td>
  <td>&nbsp;</td>
  <td></td>
</tr>

</table>
<table width="800" class="tblForm">
<td width="161"></td>
  <td class="tagForm">Ejercicio P.:</td>
  <td width="92"><input name="txt_anho" type="text" id="txt_anho" value="<?=date('Y');?>" size="6" maxlength="4" readonly="readonly" /></td>
  <td width="100" align="right"></td>
  <td width="138">&nbsp;</td>
  <td width="178"></td>
<tr>

 	

</tr>
</table>
<!---////////////////////////////////////////////////////////////////////////////////////////////////-->
<!---/////////////////// ********   CARGAR SELECT SECTOR PRESUPUESTO  *******   /////////////////////-->
 <table width="800" class="tblForm" border="0px">
    
    <tr>
      <td align="center"><input type="button" name="bt_proyeccion" id="bt_proyeccion" value="Iniciar" onclick="crearProyeccion()"  /></td>
    </tr>
    <tr>
	  <td align="left">Proyecta</td>         
    </tr>
    
     <tr>
	      
	  <td width="50" align="left">
      		<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="tblLista">
					<tr>
							<td width="100%" align="center">
                            	<div id="dv_proyeccion" style="width:790px; height:350px; display:block;  position: static;"> 
                                                             
                                	
                                </div>
                            </td>
					</tr>
			</table>
      </td>	 
	              
	</tr>
	
	</table>
	
<center>


<form action="../comunes/exportarExcel.php" method="post" target="_blank" id="FormularioExportacion">

<input name="exprtar" type="button" id="exprtar" value="Exportar" class="botonExcel"/>


<input type="hidden" id="datos_a_enviar" name="datos_a_enviar"  />
</form>



</center></div>

<div style="width:700px" class="divMsj">Campos Obligatorios *</div>

</body>
</html> 
<script>

function generarTablaExportar()
{
	//alert('fsdfsfsdfsdf');

	$(document).ready(function() {
	$(".botonExcel").click(function(event) {
		$("#datos_a_enviar").val( $("<div>").append( $("#Exportar_a_Excel").eq(0).clone()).html());
		$("#FormularioExportacion").submit();
});
});
	
	/*var url ='../include/exportarExcel.php';
	var datos = xGetElementById('datos_a_enviar').value;
	alert(datos);
	AjaxRequest.post
													(       
									       				{
									       					'parameters':{'datos_a_enviar':datos																		  
															}
									            			,'url':url
									            			,'onSuccess': respGenerarTablaExportar												
															,'onError':function(req)
															{ 
									                        	alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
									                        }         
									             		}
									        		);
	*/
}

function crearProyeccion()
{	
	//xGetElementById('divLoader').style.display = 'block';
	//alert(mesIni+' '+mesFin);
	var mesIni = xGetElementById("sel_mes_inicio").value;
	var mesFin = xGetElementById("sel_mes_final").value;
	
	var partidaDesde = xGetElementById("sel_desde_partida").value;
	var partidaHasta = xGetElementById("sel_hasta_partida").value;
	
	var anio = xGetElementById('txt_anho').value;	
	var meses=new Array();
	var ud =new Array(); 
	var pos=0;
	
	if(partidaDesde>partidaHasta)
	{
		alert('La partida desde no puede ser mayor. Verifique.');
		xGetElementById('divLoader').style.display = 'none';
		
	}
	else
	{
		for(var j=parseInt(mesIni); j<=parseInt(mesFin);j++)
		{
			//pos
			meses[pos]=j;
			ud[pos]=ultimoDia(parseInt(j),anio);
			pos++;
		}
		
		
		var opx 		='';
		var url 		= '../comunes/formProyeccion.php';
		
		AjaxRequest.post
														(       
															{
																'parameters':{'meses':meses,
																			  'anio':anio,
																			  'partidaDesde':partidaDesde,
																			  'partidaHasta':partidaHasta,
																			  'ud':ud
																}
																,'url':url
																,'onSuccess': respCrearProyeccion												
																,'onError':function(req)
																{ 
																	alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
																}         
															}
														);
		
	}
	
	
													
													
	
}

function respCrearProyeccion(req)
{

	var tabla = xGetElementById('dv_proyeccion');
		tabla.innerHTML=req.responseText;
		
	
	/*xGetElementById('capaBuscador').style.display = 'block';	
	
	xGetElementById('dv_proyeccion_completo').style.display = 'block';
	
	xGetElementById('divLoader').style.display = 'none';*/
	
	generarTablaExportar();
	//generarTablaExportar();
	
	
	
	//xGetElementById('cerrarBuscador').style.display = 'block';
	//xGetElementById('c').style.display = 'block';		
}

function generarTablaExportar()
{
	//alert('fsdfsfsdfsdf');

	$(document).ready(function() {
	$(".botonExcel").click(function(event) {
		$("#datos_a_enviar").val( $("<div>").append( $("#Exportar_a_Excel").eq(0).clone()).html());
		$("#FormularioExportacion").submit();
});
});
	
	/*var url ='../include/exportarExcel.php';
	var datos = xGetElementById('datos_a_enviar').value;
	alert(datos);
	AjaxRequest.post
													(       
									       				{
									       					'parameters':{'datos_a_enviar':datos																		  
															}
									            			,'url':url
									            			,'onSuccess': respGenerarTablaExportar												
															,'onError':function(req)
															{ 
									                        	alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
									                        }         
									             		}
									        		);
	*/
}

function ultimoDia(mes, anio) {
    var diasMes = new Array(31,31,28,31,30,31,30,31,31,30,31,30,31);
	if ( (mes == 2) && (bisiesto(anio)) )
	   return 29;
    else return diasMes[mes];
}

// Determina si un a�o es bisiesto
function bisiesto(anio) {
   if (anio%400==0 || (anio%4==0 && anio%100!=0) )
      return true;
   return false;
}


function mostrarCampoText(id,i,j)
{
	//alert(id);
	var td= xGetElementById(id);
	var text = document.createElement("input");
		text.type='text';		
		text.value=td.innerHTML;
		text.setAttribute('onkeypress','return(formatoMoneda(this,".",",",event))');
		text.setAttribute('style','text-align:right');
		text.setAttribute('id','txt_egreso');		
		text.setAttribute('onblur','quitarCampoText(\'txt_egreso\',\''+id+'\')');		
		text.setAttribute('onkeyup','restaDisponibilidad(\''+i+'\',\''+j+'\')');
		
		
		
		td.innerHTML='';
		td.appendChild(text);
		xGetElementById('txt_egreso').focus();
}


function restaDisponibilidad(i,j)
{
	
	//alert(j);
	var disponible=0.00;
	var disp='disponibilidad'+i;
	var egre='egreso'+i+'_'+j;
	a=j-1;
	//alert(a);
	var dispM = 'disponibleMes'+i+'_'+a;
	//alert(disp);
	
	
	var disponibilidad		=	xGetElementById(disp).innerHTML;
	var egreso				= 	xGetElementById('txt_egreso').value;
	
	
	
	if(j==0)
	{
		disponible = disponibilidad;
	}	
	else
	{
		var disponibleMes		=   xGetElementById(dispM).innerHTML;
		disponible=disponibleMes
	}
	
	//alert(disponible);
	
	
	//var disponible  =   xGetElementById(idDisponible).value;
	
	//var disponible='';
	var temp='';
			var valorFinal='';
			
				
			
			var temp	= quitarMiles(egreso);
				temp   	= temp.replace(',','.');			
			var temp2   = quitarMiles(disponible);
				temp2   = temp2.replace(',','.');
			///alert(temp+' '+temp2);
				
			var resta = temp2 - temp;		
			//alert(resta);
			//var total= parseFloat(resta);
						
			
			
			
			var disponible = resta;
			//alert(disponible);
			var total = Math.round (disponible * 100) / 100;
				//alert(total);
				//total=formatNumber1(total);
				if(total<0.00)
				{
					colorLetra='#FF0000';
				}
				else
				{
					colorLetra='#000000';
					}
				
				//total=total.toString();
				//total=total.replace('.',',');
				//total=formatNumber1(total);
				
				//alert('total:'+ total);
			//var totalArrego=total.split('.');
			//total =total
			//var sinDecimales=total.split('.');
					//alert(totalArrego[0]);
			
			//alert(total);
			
			xGetElementById('disponibleMes'+i+'_'+j).innerHTML= formatNumber(total);
			xGetElementById('disponibleMes'+i+'_'+j).style='text-color:'+colorLetra;
			
			
			
			//var total=Math.round(resta)/Math.pow(10,2);
			
			
			
}

function formatNumber(num,prefix){
prefix = prefix || '';
num += '';
var splitStr = num.split('.');
var splitLeft = splitStr[0];
var splitRight = splitStr.length > 1 ? ',' + splitStr[1] : '';
if(!splitRight)splitRight=',00';
//alert(splitRight);
var regx = /(\d+)(\d{3})/;
while (regx.test(splitLeft)) {
splitLeft = splitLeft.replace(regx, '$1' + '.' + '$2');
}
return prefix + splitLeft + splitRight;
}

function unformatNumber(num) {
return num.replace(/([^0-9\.\-])/g,'')*1;
} 

function quitarCampoText(idText,idTD)
{
	//alert(idTD);
	//alert('sssss');+
	var nuevoValor = xGetElementById(idText).value;
	//alert(nuevoValor);
		xGetElementById(idTD).innerHTML='';
		xGetElementById(idTD).innerHTML=nuevoValor
}

function avitarMesFinal()
{
	xGetElementById(idfx[0]).disabled=true;	
	xGetElementById(idfx[1]).disabled=false;	
	
}


/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////
/*
function cargarContenido(pagina){
   
	
	
	
	
	//xGetElementById('divLoader').style.display = 'block';
	//alert(mesIni+' '+mesFin);
	var mesIni = xGetElementById("sel_mes_inicio").value;
	var mesFin = xGetElementById("sel_mes_final").value;
	
	var partidaDesde = xGetElementById("sel_desde_partida").value;
	var partidaHasta = xGetElementById("sel_hasta_partida").value;
	
	var anio = xGetElementById('txt_anho').value;	
	var meses=new Array();
	var ud =new Array(); 
	var pos=0;
	
	if(partidaDesde>partidaHasta)
	{
		alert('La partida desde no puede ser mayor. Verifique.');
		xGetElementById('divLoader').style.display = 'none';
		
	}
	else
	{
		for(var j=parseInt(mesIni); j<=parseInt(mesFin);j++)
		{
			//pos
			meses[pos]=j;
			ud[pos]=ultimoDia(parseInt(j),anio);
			pos++;
		}
		
		
		var ajaxPag = new sack();
		ajaxPag.requestFile = pagina;
		//Si se necesita pasar algún parámetro se usa: ajaxPag.setVar('nombreDeVariable',valorDeVariable);
	
		ajaxPag.setVar('meses',meses);
		ajaxPag.setVar('anio',anio);
		ajaxPag.setVar('partidaDesde',partidaDesde);
		ajaxPag.setVar('partidaHasta',partidaHasta);
		ajaxPag.setVar('ud',ud);
		
		ajaxPag.method = "POST";
		ajaxPag.runResponse = whenResponsePag;
		ajaxPag.execute = true;
		ajaxPag.runAJAX();
		
		
	}
	
						
	
	
	
}
 
function whenResponsePag(){
    var mydiv = document.getElementById("dv_proyeccion");
    //Esto carga en el <div> destino la página solicitada
    mydiv.innerHTML = this.response;
    /* Si la página solicitada contiene script Javascript se añade esto para que lo ejecute
    /*var elementos = mydiv.getElementsByTagName('script');
    for(var i=0;i<elementos.length;i++) {
	var elemento = elementos[i];
	var nuevoScript = document.createElement('script');
	nuevoScript.text = elemento.innerHTML;
	nuevoScript.type = 'text/javascript';
	if(elemento.src!=null && elemento.src.length>0) nuevoScript.src = elemento.src;
	elemento.parentNode.replaceChild(nuevoScript,elemento);
    //}
}
*/
/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////
</script>