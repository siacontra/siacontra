<?php
session_start();

if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location:index.php");
//	------------------------------------
include("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);
//	------------------------------------




$coBeneficio =$_REQUEST['registro'];
$sql="SELECT *
				FROM rh_beneficio where codBeneficio = $coBeneficio";
$qry=mysql_query($sql) or die ($sql.mysql_error());
$field=mysql_fetch_array($qry);
if(mysql_num_rows($qry)!=0){
 if(($field[tx_estatus]=='Aprobado')or($field[tx_estatus]=='Generado')){
   echo"<script>
        alert('NO PUEDE SER EDITADO');
        history.back(-1);
      </script>";
 }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="../js/AjaxRequest.js"></script>


<script type="text/javascript" language="javascript" src="../js/xCes.js"></script>


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
</style>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
 <td class="titulo">Beneficio | Ver</td>
 <td align="right"><a class="cerrar"  href="javascript:window.close()";>[Cerrar]</a></td>
</tr>
</table><hr width="100%" color="#333333" />
<?
function validaFormatoFecha($fecha){
  $A=$fecha.date("Y");
  $D=$fecha.date("d");
  $M=$fecha.date("m");
}
?>

<?php
$annio_actual=date("Y");
$mes_actual=date("m");
$dia_actual=date("d");
$hora_actual=date("H");
$min_actual=date("i");
$periodo=$annio_actual."-".$mes_actual;
$fecha=$dia_actual."-".$mes_actual."-".$annio_actual;
$fecha2=$dia_actual."-".$mes_actual."-".$annio_actual;

if ($hora_actual<12) $meridiano="AM";
else {
	$meridiano="PM";
	$hora_actual=(int) $hora_actual;
	$hora_actual-=12;
	if ($hora_actual==0) $hora_actual=12;
	if ($hora_actual<10) $hora_actual="0$hora_actual";
}
$hora=$hora_actual.":".$min_actual;
?>

<?php
$limit=(int) $limit;
echo "
<input type='hidden' name='codantepres' id='codantepres' value='".$codantepres."'/>
<input type='hidden' name='filtro' id='filtro' value='".$filtro."' />
<input type='hidden' name='regresar' id='regresar' value='".$regresar."' />
<input type='hidden' name='forganismo' id='forganismo' value='".$forganismo."' />
<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />";

//echo"Regresar= ".$regresar;

/*include "gmsector.php";
$sql="SELECT * FROM pv_sector,pv_programa1,pv_subprog1,pv_actividad1,pv_proyecto1 WHERE 1";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0){
 $field=mysql_fetch_array($query);
 list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaDesde']); $fdesde=$d.'/'.$m.'/'.$a;
 list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaHasta']); $fhasta=$d.'/'.$m.'/'.$a;
 $limit=(int) $limit;
}*/


$sqlN_HCM="SELECT COUNT( codBeneficio ) AS NRO FROM rh_beneficiO";
$resul=mysql_query($sqlN_HCM);
$reg=mysql_fetch_array($resul);
$nro = $reg['NRO']+'1';
for($i=0; $i<5-strlen($nro); $i++)
{	
		$cero.='0';	
}
 $nro_hcm = $cero.$nro; 
?>

<!--////////////////////////// **********DATOS GENERALES *************  ///////////////////////-->   
<div id="tab1" style="display:block;">
<div style="width:800px" class="divFormCaption">Informaci&oacute;n del Beneficio</div>
<table width="800" class="tblForm">
<tr>
 <td width="129" align="right">Organismo:</td>
 <td width="634">
   <select name="organismo" id="organismo" disabled="disabled">
    <?php 
	// segundo bloque php //* Conectamos a los datos *//
	include "conexion.php";
	$sql="SELECT CodOrganismo, Organismo
	        FROM mastorganismos 
	       WHERE CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' ";
	$rs=mysql_query($sql);
	while($reg=mysql_fetch_assoc($rs)){
	$codOrganismo=$reg['CodOrganismo'];// Codigo del orgnismo
	$organismo=$reg['Organismo'];// Descripcion del Organismo
	   echo "<option value=$codOrganismo>$organismo</option>";
	}
	?></select></td>
 <td width="21" colspan="2"></td>
</tr>
</table>
<table width="800" class="tblForm">
<tr><td width="123" height="2"></td></tr>
<tr>
 <td colspan="2" align="right">Nro. Orden:</td>
 <td width="168"><input name="txt_nro_orden" type="text" id="txt_nro_orden" value="<?= $nro_hcm ?>" size="8" readonly/>*</td>
 <td width="91" align="right">&nbsp;</td>
 <td width="129">&nbsp;</td>
 <td width="264" colspan="2" rowspan="9" valign="top">
 <table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblLista">
   <tr>
     <td colspan="3" align="center" class="trListaHead">Soportes</td>
     </tr>
   <tr>
     <td><input type="checkbox" name="ch_planilla" id="ch_planilla"  onclick="this.checked=true"/></td>
     <td>Planilla de Solicitud</td>
     <td>&nbsp;</td>
   </tr>
   <tr>
     <td width="8%"><input type="checkbox" name="ch_informe" id="ch_informe" onclick="this.checked=true"/></td>
     <td width="66%">Informe Médico</td>
     <td width="26%">&nbsp;</td>
   </tr>
   <tr>
     <td><input type="checkbox" name="ch_factura" id="ch_factura" onclick="this.checked=true" /></td>
     <td>Facturas</td>
     <td>&nbsp;</td>
   </tr>
   <tr>
     <td><input type="checkbox" name="ch_recipe" id="ch_recipe" onclick="this.checked=true"/></td>
     <td>Recipe Médico</td>
     <td>&nbsp;</td>
   </tr>
   <tr>
     <td><input type="checkbox" name="ch_otro" id="ch_otro" onclick="this.checked=true"  /></td>
     <td>Otro</td>
     <td>&nbsp;</td>
   </tr>
   <tr>
     <td>&nbsp;</td>
     <td>&nbsp;</td>
     <td>&nbsp;</td>
   </tr>
 </table></td>
</tr>
<tr>
 <td colspan="2" align="right">Tipo de Solicitud:</td>
 <td colspan="2">
 <select name="sel_tipo_solicitud" id="sel_tipo_solicitud" disabled="disabled">
 <option value="R">REMBOLSO</option>
 <option value="E">EMISIÓN</option>
   </select>
   *</td>
 <td width="129">&nbsp;</td>
 </tr>

<tr>
  <td colspan="2" align="right">Funcionario:</td>
  <td colspan="3"> 
  		<form id="frmentrada" name="frmentrada" action="#">
            <input name="codempleado" type="hidden" id="codempleado" value="" />
           <input name="nomempleado" id="nomempleado" type="text" size="60" readonly/>
           <input name="bt_examinar" id="bt_examinar" type="button" value="..." onclick="cargarVentana(this.form, 'lista_empleados.php?limit=0&campo=4', 'height=500, width=800, left=200, top=200, resizable=yes');" />*                                        
      </form></td>
  </tr>
<tr>
 <td colspan="2" align="right">Familiar : </td>
 <td colspan="3"><select name="sel_familia_funcionario" id="sel_familia_funcionario" disabled="disabled">
 </select>  
 </td>
 </tr>

<tr>
 <td colspan="2" align="right">Servicio :</td>
 <td colspan="2" align="left">
  
 <select name="sel_servicio" id="sel_servicio" onchange="colocarResponsable()" disabled="disabled">
   </select>
   *</td>
 <td align="left">&nbsp;</td>
 </tr>

<tr>
 <td colspan="2" align="right">Rama :</td>
 <td colspan="2"><select name="sel_rama" id="sel_rama" disabled="disabled">
   </select>
   *</td>
 <td width="129">&nbsp;</td>
 </tr>
<tr>
  <td colspan="2" align="right">Institución :</td>
  <td colspan="2"><select name="sel_institucion" id="sel_institucion" disabled="disabled" style='width:70%' >
    </select>
    *</td>
  <td>&nbsp;</td>
  </tr>
<tr>
  <td colspan="2" align="right">Médico :</td>
  <td colspan="2"><select name="sel_medico" id="sel_medico" disabled="disabled">
    </select>
    *</td>
  <td>&nbsp;</td>
  </tr>
<tr>
  <td colspan="2"></td>
  <td colspan="2">&nbsp;</td>
  <td>&nbsp;</td>
  </tr>


</table>
<table width="800" class="tblForm">
<tr>
 <td width="127" align="right">Ejercicio P.:</td>		
 <td width="661"><? $ano = date('Y'); // devuelve el año
       $fcreacion= date("d-m-Y");//Fecha de Creación ?>
   <input title="A&ntilde;o de Presupuesto" name="ejercicioPpto" type="text" value="<?PHP echo $ano;?>" style="text-align:right" id="ejercicioPpto" size="3" maxlength="4" />* 
   F.Creaci&oacute;n:<input name="fcreacion" type="text" id="fcreacion" size="8" value="<?PHP echo $fcreacion;?>" readonly /> 
   Estado:<input name="estado" type="text" id="estado" size="11" value="Preparado" readonly/>	<input name="co_estado" type="hidden" id="co_estado" size="11" value="PE" readonly/>	</td>
</tr>
</table>
<!---////////////////////////////////////////////////////////////////////////////////////////////////-->
<!---/////////////////// ********   CARGAR SELECT SECTOR PRESUPUESTO  *******   /////////////////////-->
 <table width="800" class="tblForm" border="0px">
	<tr>
	 <td width="40" align="left">&nbsp;</td>         
	  <td width="45" align="left">Nro Factura</td>
	  <td width="520" align="left">Descripci&oacute;n</td>     
	  <td width="150" align="right">Monto</td>
	  <td width="45" align="left">&nbsp;</td>               
	</tr>
    
    <tr>
	  <td>&nbsp;</td>
	  <td ><input name="txt_codigo" id="txt_codigo" type="text"   size="12" maxlength="*"  disabled="disabled" /></td>
	  <td><input name="txt_descripcion" id="txt_descripcion" type="text"  style="width:98%"  size="60" maxlength="*" disabled="disabled"/></td>          
	  <td ><input name="txt_monto_item" id="txt_monto_item" type="text" value="0,00" onChange=""  maxlength="*" style="text-align:right; width:98%;" onkeypress="return(formatoMoneda(this,'.',',',event));" 	onkeyup="agregarItemEnter(event)" disabled="disabled"/></td>
	  <td >&nbsp;</td>                       
	</tr>
    
    
     <tr>
	  <td width="50" align="left">&nbsp;</td>         
	  <td  align="left" colspan="6">&nbsp;</td>
         
	</tr>
    
     <tr>
	      
	  <td width="45" align="left" colspan="6">
      		<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="tblLista">
					<tr class="divFormCaption"  style="background:#999;">
							<td width="5%" align="center">N&deg;</td>
							<td width="12%" align="left">Nro Factura</td>
							<td width="63%" align="left">Decripci&oacute;n</td>
							<td width="15%" align='right'>Monto</td>
							<td width="5%" align="center">&nbsp;</td>
					</tr>
					<tr>
							<td colspan="5" align="center">
                            	<div id="tablaItem" style="width:100%; height:150px; overflow:scroll; overflow-x:hidden "> <br /><label>No existe movimientos agregado</label></div>
                            </td>
					</tr>
					<tr>
							<td colspan="3" align="right"><label style="font-weight:bold; font-size:14px">Total (Bs.):</label></td>
							<td align="right" ><label id="totalMonto"  style="font-weight:bold; font-size:14px">0,00</label></td>
							<td align="right" >&nbsp;</td>
					</tr>
			</table>
      </td>	 
	              
	</tr>
	
	</table>
	
<!---  TABLA 2 ------>
<div style="width:800px" class="divFormCaption">Control</div>
<table width="800" class="tblForm">
<tr><td></td></tr>

<tr><td>&nbsp;</td></tr>
<tr>
  <td>&nbsp;</td>
  <td class="tagForm">Preparado por:</td><? $sql3=mysql_query("SELECT * FROM usuarios WHERE Usuario='".$_SESSION['USUARIO_ACTUAL']."'");
											 if(mysql_num_rows($sql3)!=0){
											   $field3=mysql_fetch_array($sql3);
											   $sql4=mysql_query("SELECT * FROM mastpersonas WHERE CodPersona='".$field3['CodPersona']."'");
											   if(mysql_num_rows($sql4)!=0){
												 $field4=mysql_fetch_array($sql4);
											   }
											 }
										  ?>
   <td><input name="prepor" id="prepor" type="text" size="60" value="" readonly/>
    	<input name="codprepor" type="hidden" id="codprepor" value="" /></td>
<tr>
<tr><td></td>
   <td class="tagForm">Aprobado por:</td>
   		
   <td>
      	<input name="nomempleadoA" id="nomempleadoA" type="text" size="60" value="" readonly/>
    	<input name="codempleadoA" type="hidden" id="codempleadoA" value=""/>
  </td>
     
</tr>
<tr><td></td>
	<? $ahora=date("Y-m-d H:m:s");
	   echo"<td class='tagForm'>&Uacute;ltima Modif.:</td>
		   <td coslpan='1'>
			 <input name='ult_usuario' type='text' id='ult_usuario' size='30' readonly />
			 <input name='ult_fecha' type='text' id='ult_fecha' size='22' readonly />
		   </td>";
   ?>
</tr>
</table>
<center>

<input name="btcancelar" type="button" id="btcancelar" value="Cancelar" onclick="javascritp:window.close();"/>
</center></div>

<div style="width:700px" class="divMsj">Campos Obligatorios *</div>

	<SCRIPT LANGUAGE="JavaScript">
    
    function campoVacio(q) { 
    
    /*
        ENTRADA: q (CADENA DE TEXTO)
        SALIDA: TRUE O FALSE
        DESCRIPCIÓN: FUNCION QUE SE ENCARGA DE VERIFICAR SI EXISTE TEXTO EN BLAMCO 
        PROGRAMADOR: ERNESTO RIVAS
        fecha: 10-10-2012
        */
        
         
             for ( i = 0; i < q.length; i++ ) {  
                     if ( q.charAt(i) != " " ) {  
                            return true;  
                     }  
             }  
           return false;  
     }
    
	
	function toCurrency(cnt){
		cnt = cnt.toString().replace(/\$|\,/g,'');
		if (isNaN(cnt))
			return 0;    
		var sgn = (cnt == (cnt = Math.abs(cnt)));
		cnt = Math.floor(cnt * 100 + 0.5);
		cvs = cnt % 100;
		cnt = Math.floor(cnt / 100).toString();
		if (cvs < 10)
		cvs = '0' + cvs;
		for (var i = 0; i < Math.floor((cnt.length - (1 + i)) / 3); i++)
			cnt = cnt.substring(0, cnt.length - (4 * i + 3)) + '.' + cnt.substring(cnt.length - (4 * i + 3));
		return (((sgn) ? '' : '-') + cnt + ',' + cvs);
	}
    
    
    function formatoNumerico(number) {
        /*
        ENTRADA: number (MONTO SIN FORMATO)
        SALIDA: MONTO TRANFORMADO CON LOS MILES Y DECIMALES
        DESCRIPCIÓN: FUNCION QUE SE ENCARGA TRANFORMAR =>  
        PROGRAMADOR: ERNESTO RIVAS
        fecha: 10-10-2012
        */
        
        /* */
       var comma = '.',
           string = Math.max(0, number).toFixed(0),
           length = string.length,
           end = /^\d{4,}$/.test(string) ? length % 3 : 0;
       return (end ? string.slice(0, end) + comma : '') + string.slice(end).replace(/(\d{3})(?=\d)/g, '$1' + comma);
       
    }/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
    
    function formatoMoneda(fld, milSep,decSep, e)
    {
        /*
        ENTRADA: fld (NOMBRE DEL CAMPO DE TEXTO), milSep (separadores miles), decSep(separador de decimales) e (evento)
        SALIDA: 
        DESCRIPCIÓN: FUNCION QUE SE ENCARGA DE TRANFORMAR LOS NUMEROS INTRODUCISDOS EN FORMATO DE MILES Y DECIMALES
        PROGRAMADOR: ERNESTO RIVAS
        fecha: 01-10-2012
        */
        
        /* */
        
        var sep = 0; 
        var key = ''; 
        var i = j = 0; 
        var len = len2 = 0; 
        var strCheck = '0123456789'; 
        var aux = aux2 = ''; 
        var whichCode = (window.Event) ? e.which : e.keyCode; 
       // alert(whichCode);
        //if (whichCode == 13) return true; // Enter 
        
        //key = String.fromCharCode(whichCode); // Get key value from key code
        //alert(whichCode);
        
        if(whichCode!=8) //PARA QUE PERMITA ACEPTAR LA TECHA <- (BORRAR)
        {
            key = String.fromCharCode(whichCode); // Get key value from key code
            //alert(strCheck.indexOf(key));
            if (strCheck.indexOf(key) == -1) return false; // Not a valid key
            len = fld.value.length;    	
            // alert(len);
        }
        
        else len = fld.value.length-1; //PARA QUE PERMITA BORRAR
       // alert(len);
        for(i = 0; i < len; i++) 
         if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != 44)) break; 
        aux = ''; 
        for(; i < len; i++) 
         if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i); 
        aux += key;
        len = aux.length;
        if (len == 0) fld.value = '0,00'; 
        if (len == 1) fld.value = '0'+ decSep + '0' + aux; 
        if (len == 2) fld.value = '0'+ decSep + aux; 
        if (len > 2) 
        { 
             aux2 = ''; 
             for (j = 0, i = len - 3; i >= 0; i--) 
             { 
                  if (j == 3) 
                  { 
                       aux2 += milSep; 
                       j = 0; 
                  } 
                  aux2 += aux.charAt(i); 
                  j++; 
             } 
             fld.value = ''; 
             len2 = aux2.length; 
             for (i = len2 - 1; i >= 0; i--) 
                fld.value += aux2.charAt(i); 
             fld.value += decSep + aux.substr(len - 2, len);
        } //decSep +
        return false;
    }//----------------------------------------------------------------------------------------------------------------------------------
    
    
    var OBJ_BENEFICIO=new itemCestaFactura();
    function itemCestaFactura()
    {
        
        /*
        ENTRADA: 
        SALIDA:
        DESCRIPCIÓN: 
        PROGRAMADOR: ERNESTO RIVAS
        fecha: 09-10-2012
        */
            
        this.nu_codigo     	= new Array();
        this.descripcion	= new Array();	
        this.monto      	= new Array();
    }
    
    
    function actualizarMotnoTotal(monto)
    {
        /*
        ENTRADA: monto (MONTO DEL ITEM AGREGADO)
        SALIDA:
        DESCRIPCIÓN: FUNCIÓN QUE PERMITE MOSTRAR LOS ITEM QUE ESTAN REGISTRADO 
        PROGRAMADOR: ERNESTO RIVAS
        fecha: 09-10-2012
        */
        
        var temp=monto;
        while (temp.indexOf('.')>-1) {
                pos= temp.indexOf('.');
                temp = "" + (temp.substring(0, pos) + '' + 
                temp.substring((pos + '.'.length), temp.length));
        }
                        /////////////////
                        
        var valorSemiFinal = temp;
        var valorFinal     = valorSemiFinal.replace(',','.');
                            
                            
                            
        //var numerico=parseFloat(valorFinal);
        total= parseFloat(total)+parseFloat(valorFinal);
                            
        total = Math.round (total * 100) / 100;
        total = total.toFixed(2);
        //alert('valorSemiFinal '+valorSemiFinal);
        //	alert(total);
        //alert('2'+total);
        //alert(aaaa);
                            
        //alert(OBJ_CESTA.servicio[j]);
        var totalStrin=total.toString();
        var totalArrego=totalStrin.split('.');
        //total =total
        //var sinDecimales=total.split('.');
                //alert(totalArrego[0]);
        var miles=formatoNumerico(totalArrego[0]);
        var decimal=totalArrego[1];
        // if(!decimal){decimal='00';}
        var totalFinal=miles+','+decimal
            
        xGetElementById('totalMonto').innerHTML=totalFinal;
    }
    
    
    function quitarMiles(valor)
    {
        
        /*
        ENTRADA: VALOR (MONTO EN FORMATO DE MILES)
        SALIDA: VALOR (MONTO TRANFORMADO SIN LOS MILES)
        DESCRIPCIÓN: FUNCION QUE SE ENCARGA TRANFORMAR 9.999.999 => 9999999 
        PROGRAMADOR: ERNESTO RIVAS
        fecha: 10-10-2012
        */
        
        /* */
        
        while (valor.indexOf('.')>-1) {
                pos= valor.indexOf('.');
                valor = "" + (valor.substring(0, pos) + '' + 
                valor.substring((pos + '.'.length), valor.length));
        }
        return valor;
    }
    
    function fechaJStoMySql(fecha){
        if(fecha){
            var pedazo = fecha.split("-");
            var newFecha=pedazo[2]+'-'+pedazo[1]+'-'+pedazo[0];
            return newFecha;
        }
        else
            return '';
    }
    
    
    function eliminarItem(i)
    {
        /*
        ENTRADA: 
        SALIDA:
        DESCRIPCIÓN: FUNCIÓN QUE PERMITE ELIMINAR EL ITEM SELECCIONADO
        PROGRAMADOR: ERNESTO RIVAS
        fecha: 10-10-2012
        */
            
        
        OBJ_BENEFICIO.nu_codigo.splice(i,1);
        OBJ_BENEFICIO.descripcion.splice(i,1);
        OBJ_BENEFICIO.monto.splice(i,1);
        mostrarItemBeneficio(i);
    }
    
    
    
    function limpiarCampoFactura()
    {
        xGetElementById('txt_codigo').value='';
        xGetElementById('txt_codigo').focus();
        xGetElementById('txt_descripcion').value='';
        xGetElementById('txt_monto_item').value='0,00';						
    }
    
    function limpiarCamposBeneficio()
    {
        OBJ_BENEFICIO.nu_codigo     = new Array();
        OBJ_BENEFICIO.descripcion	= new Array();	
        OBJ_BENEFICIO.monto      	= new Array();
        limpiarCampoFactura();
        
        
        xGetElementById('sel_servicio').value = '0';		
        xGetElementById('sel_rama').value = '0';			
        xGetElementById('sel_institucion').value = '0';
        xGetElementById('sel_medico').value = '0';
        
        xGetElementById('nomempleadoA').value = '';
        xGetElementById('codempleadoA').value = '';
        
        xGetElementById('nomempleado').value = '';
        xGetElementById('codempleado').value = '';
        
        
        xGetElementById('sel_familia_funcionario').length=1;	
        xGetElementById('sel_familia_funcionario').options[0].value = '0';
        xGetElementById('sel_familia_funcionario').options[0].text  = '..';
        
        
        xGetElementById('ch_planilla').checked=false;
        xGetElementById('ch_informe').checked=false;
        xGetElementById('ch_factura').checked=false;
        xGetElementById('ch_recipe').checked=false;
        xGetElementById('ch_otro').checked=false;	
        
        mostrarItemBeneficio(0,'');
        
    }
    
    
    
    
    
    
    
    function cargarServicio(serv)
    {
        var url = 'lib/transBeneficio.php';
        AjaxRequest.post
                                                    (
                                                        {
                                                            'parameters':{'opcion':'CARGARSERVICIO'}
                                                            ,'url':url
                                                            ,'onSuccess': function(req){respCargarServicio(req,serv)}													       
                                                            ,'onError':function(req)
                                                            { 
                                                                alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
                                                            }         
                                                        }
                                                    );
    }
    
	
	
    var codPersona = new Array();
    var nomPersona = new Array();
    var codigo	   = new Array();
    function respCargarServicio(req,serv)
    {
        
        var resp =eval ("("+req.responseText+")");
        
        xGetElementById('sel_servicio').length=resp.length+1;
        
        xGetElementById('sel_servicio').options[0].value = '0';
        xGetElementById('sel_servicio').options[0].text  = '..';
        
        for(var i=0; i<resp.length;i++)
        {				
            xGetElementById('sel_servicio').options[i+1].value=resp[i]['codAyudaE'];
            xGetElementById('sel_servicio').options[i+1].text=resp[i]['numAyudaE']+' - '+resp[i]['decripcionAyudaE']+' - '+formatoNumerico(resp[i]['limiteAyudaE']);
            
            codigo[i] = resp[i]['codAyudaE'];
            codPersona[i] = resp[i]["CodPerAprobar"];
            nomPersona[i] = resp[i]["NomCompleto"];
            
        }
		xGetElementById('sel_servicio').value=serv;
		
        //xGetElementById('sel_servicio').setAttribute("onchange","colocarResponsable("+resp[i]["CodPerAprobar"]+","+resp[i]["NomCompleto"]+")");
        
    }
    
    
    function colocarResponsable()
    {
        //alert('asdasdasd');
        var cod = xGetElementById('sel_servicio').value;
        
        for(var i=0; i<codigo.length; i++)
        {
            if(cod==codigo[i])
            {
                    xGetElementById('codempleadoA').value = codPersona[i];
                    xGetElementById('nomempleadoA').value = nomPersona[i];
            }
        }
        //alert(codigo);
        
        //xGetElementById('codempleado').value = codigo;
        //xGetElementById('nomempleado').value = persona;
    }
    
    
    
    function cargarRama(rama)
    {
        var url = 'lib/transBeneficio.php';
        AjaxRequest.post
                                                    (
                                                        {
                                                            'parameters':{'opcion':'CARGARRAMA'}
                                                            ,'url':url
                                                            ,'onSuccess': function(req){respCargarRama(req,rama)}
                                                            ,'onError':function(req)
                                                            { 
                                                                alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
                                                            }         
                                                        }
                                                    );
    }
    
    
    function respCargarRama(req,rama)
    {
        
        var resp =eval ("("+req.responseText+")");
        
        xGetElementById('sel_rama').length=resp.length+1;
        
        xGetElementById('sel_rama').options[0].value = '0';
        xGetElementById('sel_rama').options[0].text  = '..';
        
        for(var i=0; i<resp.length;i++)
        {				
            xGetElementById('sel_rama').options[i+1].value=resp[i]['codRamaS'];
            xGetElementById('sel_rama').options[i+1].text=resp[i]['descripcionRamaS'];
        }
		
		xGetElementById('sel_rama').value=rama;
    }
	
    
    
    
    function cargarInstitucion(inst)
    {
        var url = 'lib/transBeneficio.php';
        AjaxRequest.post
                                                    (
                                                        {
                                                            'parameters':{'opcion':'CARGARINSTITUCION'}
                                                            ,'url':url
                                                            ,'onSuccess': function(req){respCargarInstitucion(req,inst)}
                                                            ,'onError':function(req)
                                                            { 
                                                                alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
                                                            }         
                                                        }
                                                    );
    }
    
    
    function respCargarInstitucion(req,inst)
    {
        
        var resp =eval ("("+req.responseText+")");
        
        xGetElementById('sel_institucion').length=resp.length+1;
        
        xGetElementById('sel_institucion').options[0].value = '0';
        xGetElementById('sel_institucion').options[0].text  = '..';
        
        for(var i=0; i<resp.length;i++)
        {				
            xGetElementById('sel_institucion').options[i+1].value=resp[i]['idInstHcm'];
            xGetElementById('sel_institucion').options[i+1].text=resp[i]['descripcioninsthcm'];
        }
		
		xGetElementById('sel_institucion').value=inst;
    }
    
    
    
    
    
    function cargarMedico(med)
    {
        var url = 'lib/transBeneficio.php';
        AjaxRequest.post
                                                    (
                                                        {
                                                            'parameters':{'opcion':'CARGARMEDICO'}
                                                            ,'url':url
                                                            ,'onSuccess': function(req){respCargarMedico(req,med)}
                                                            ,'onError':function(req)
                                                            { 
                                                                alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
                                                            }         
                                                        }
                                                    );
    }
    
    
    function respCargarMedico(req,med)
    {
        
        var resp =eval ("("+req.responseText+")");
        
        xGetElementById('sel_medico').length=resp.length+1;
        
        xGetElementById('sel_medico').options[0].value = '0';
        xGetElementById('sel_medico').options[0].text  = '..';
        
        for(var i=0; i<resp.length;i++)
        {				
            xGetElementById('sel_medico').options[i+1].value=resp[i]['idMedHcm'];
            xGetElementById('sel_medico').options[i+1].text=resp[i]['nombremedico'];
        }
		xGetElementById('sel_medico').value=med;
    }
    
    
    
    function agregarItemEnter(evt)
    {//----------------------------------------------------------------------------------------------------------------------
        
        var e = new xEvent(evt);//crea una instancia de la clase xEvent
        
        if(e.keyCode == 13)//verificamos que alla presionado enter
        {					
                verificarCampoItem();					
         }
            
    }//----------------------------------------------------------------------------------------------------------------------
    
    
    function verificarCampoItem()
    {
        
        /*
        ENTRADA: 
        SALIDA:
        DESCRIPCIÓN: FUNCIÓN QUE PERMITE VERIFICAR QUE LOS CAMPOS NO ESTEN EN BLANCO
        PROGRAMADOR: ERNESTO RIVAS
        fecha: 09-10-2012
        */
        
        var codigo  		= 	xGetElementById('txt_codigo').value;
        var descripcion  	= 	xGetElementById('txt_descripcion').value;
        var montoIten		  	= 	xGetElementById('txt_monto_item').value;
    
        
        
        if(!campoVacio(codigo))	
        {
            alert('Debes introducir el código');	
            xGetElementById('txt_codigo').focus();
            bandera = 0;//bandera que permite asegurar que se entro en una condicion 
        }
        
        else if(!campoVacio(descripcion))
        {
            alert('Debes introducir la descripción');		
            xGetElementById('txt_descripcion').focus();
            bandera = 0;//bandera que permite asegurar que se entro en una condicion 
        }
        
        else if(montoIten=='0,00')
        {
            alert('Debes introducir el monto');
            xGetElementById('txt_monto_item').focus();
            bandera = 0;//bandera que permite asegurar que se entro en una condicion 
        }				
        
        else
        {		
            //agregarItem();
            
            agregarItem();//X
            limpiarCampoFactura();
            //desbloquearCampoPartida();
            xGetElementById('txt_codigo').focus();
        } 
    }/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    function agregarItem()
    {	
        /*
        ENTRADA: 
        SALIDA:
        DESCRIPCIÓN: FUNCIÓN QUE PERMITE AGREGAR EL VALOR DE LOS CAMPOS DE CODIFICACIÓN EN LOS ATRIBUTOS
    
        PROGRAMADOR: ERNESTO RIVAS
        fecha: 09-10-2012
        */
    
    
            var i= OBJ_BENEFICIO.nu_codigo.length;
                                    
            OBJ_BENEFICIO.nu_codigo[i] 	 = xGetElementById('txt_codigo').value;
            OBJ_BENEFICIO.descripcion[i] = xGetElementById('txt_descripcion').value;
            OBJ_BENEFICIO.monto[i]		 = xGetElementById('txt_monto_item').value;
                                            
            i= OBJ_BENEFICIO.nu_codigo.length;
            mostrarItemBeneficio(i,'');
    }
    
    
    function mostrarItemBeneficio(i,cond)
    {
        //alert(cond);
        /*
        ENTRADA: i (TAMAÑO DEL ARREGLO)
        SALIDA:
        DESCRIPCIÓN: FUNCIÓN QUE PERMITE MOSTRAR LOS ITEM QUE ESTAN REGISTRADO 
        PROGRAMADOR: ERNESTO RIVAS
        fecha: 09-10-2012
        */
        total='0.00';
        var tabla = xGetElementById('tablaItem');
        var montoDis = 0.00;
        //xGetElementById(idfx[14]).value='0,00';
        
            // get the reference for the body
            // creates a <table> element and a <tbody> element
            var tbl       = xCreateElement("table");
                tbl.width = '100%';
                tbl.setAttribute('border','0px');		
                tbl.setAttribute("cellpadding", "0");
                tbl.setAttribute("cellspacing", "0");
                //tbl.setAttribute("class", "tblLista");
            
            //tbl.setAttribute('style','table-layout: fixed');
            
            //tbl.setAttribute("id","tabla");
            var tblBody = xCreateElement("tbody");
    
            // creating all cells
            //alert(datos.length);
                
            
            if(i==0)// no existe
            {
                total='0.00';
                var row = xCreateElement("tr");
                    var br = xCreateElement("br");
                    var cell1 = xCreateElement("td");
                    cell1.width='100%';
                    cell1.setAttribute('align','center');
                        var cellText1 =document.createTextNode('No existe movimiento');
                        cell1.appendChild(br);
                        cell1.appendChild(cellText1);
                        //cell1.appendChild(br);
                                    
                row.appendChild(cell1);
                tblBody.appendChild(row);
                tbl.appendChild(tblBody);
                tabla.innerHTML='';
                tabla.appendChild(tbl);
                
                tbl.setAttribute("border", "0");
                actualizarMotnoTotal('0');		
            }
            else
            {
                
                for(var j=0;j<i;j++)	
                {				
                    
                        // creates a table row
                        var row = document.createElement("tr");
                        row.setAttribute("class","trListaBody");									
                        
                        
                
                        // Create a <td> element and a text node, make the text
                        // node the contents of the <td>, and put the <td> at
                        // the end of the table row
                        var cell1 = xCreateElement("td");
                        var cell2 = xCreateElement("td");
                        var cell3 = xCreateElement("td");
                        var cell4 = xCreateElement("td");
                        var cell5 = xCreateElement("td");
                        
                                                                                                            
                        cell1.width = '5%';cell1.setAttribute('align','center');										
                        cell2.width = '12%';//cell3.setAttribute('style','overflow: hidden;text-overflow: ellipsis;white-space: nowrap;');				
                        cell3.width = '63%';cell3.setAttribute('align','left');
                        cell4.width = '15%';cell4.setAttribute('align','right');
                        cell5.width = '5%';cell5.setAttribute('align','center');
                        
                        //alert(OBJ_CESTA.co_presupuesto[j]);
                                                                    
                            var cellText1 = document.createTextNode(j+1);											
                            
                            
                            
                            
                            
                            var cellText2 = document.createTextNode(OBJ_BENEFICIO.nu_codigo[j]);
                            
                            var cellText3 = document.createTextNode(OBJ_BENEFICIO.descripcion[j]);												
                            
                            var cellText4 = document.createTextNode(OBJ_BENEFICIO.monto[j]);
                            //OBJ_CESTA.monto[j]
                        //alert(cond+' for');
                            if(cond!='VER' && cond!='APROBAR'){
                                    var cellText5 = document.createElement('img');
                                    cellText5.src='../imagenes/circle_red_16.png';
                                    cellText5.align='center';//setAttribute('align','center');
                                    cellText5.heigth='16';
                                    cellText5.width='16';
                                
                                    cellText5.title='Eliminar Item';
                                    cellText5.setAttribute('onclick','eliminarItem('+j+')');									
                                }
                            else
                            {
                                    var cellText5 = document.createElement('h1');
                                    //cellText5.src='../imagenes/circle_red_16.png';
                            }
                        
                        
                        //para actualizar el total
                                actualizarMotnoTotal(OBJ_BENEFICIO.monto[j]);
                                ///////////////////////////////////
                                var valor = quitarMiles(OBJ_BENEFICIO.monto[j]);
                                    valor = valor.replace(',','.');								
                                
                                                                
                                montoDis+=parseFloat(valor);
                                montoDis = Math.round (montoDis * 100) / 100;
                                //alert('total :'+totalItemAgregado+'valor agregado :'+valor);
                                
                                var totalStrin=montoDis.toString();
                                
                                var totalArrego=totalStrin.split('.');
                                //total =total
                                //var sinDecimales=total.split('.');
                                
                                var miles=formatoNumerico(totalArrego[0]);
                                
                                var decimal=totalArrego[1];
                                 if(!decimal){decimal='00';}
                                 if(decimal.length==1){decimal=decimal+'0';}
                                var totalFinal=miles+','+decimal;
                                
                                //xGetElementById(idfx[14]).value= totalFinal;//montoAgre-montoItem;
                                /////////////////////////////////////
                        
                        
                            cell1.appendChild(cellText1);
                            cell2.appendChild(cellText2);
                            cell3.appendChild(cellText3);
                            cell4.appendChild(cellText4);
                            cell5.appendChild(cellText5);
                            
                            
                            
                            row.appendChild(cell1);
                            row.appendChild(cell2);	
                            row.appendChild(cell3);
                            row.appendChild(cell4);	
                            row.appendChild(cell5);
                            
                            
                            //row.setAttribute("bgcolor",color);				
    
                        tblBody.appendChild(row);
                }
                
                // put the <tbody> in the <table>
                tblBody.setAttribute('border','0px');
                tbl.appendChild(tblBody);
                // appends <table> into <body>
                tabla.innerHTML='';
                
                tabla.appendChild(tbl);
                            
            }
    }
    
    function validarCampoBeneficio()
    {
        /*
        ENTRADA: 
        SALIDA:
        DESCRIPCIÓN: FUNCIÓN QUE PERMITE VALIDAR LOS CAMPOS DEL CREDITO ADICIONAL.
        PROGRAMADOR: ERNESTO RIVAS
        fecha: 16-10-2012
        */		
        
        var nroOrden	  	= 	xGetElementById('txt_nro_orden').value;	
        var tipoSolicitud	=	xGetElementById('sel_tipo_solicitud').value;
        var codPersona		=	xGetElementById('codempleado').value;
        var servicio		=	xGetElementById('sel_servicio').value;
        var rama			=	xGetElementById('sel_rama').value;
        var institucion		=	xGetElementById('sel_institucion').value;
        var medico			=	xGetElementById('sel_medico').value;		
        var fechaCreacion	=	xGetElementById('fcreacion').value;
        var estatus			=	xGetElementById('estado').value;
        
        
        
        var totalAgregado	=	xGetElementById('totalMonto').innerHTML;
        var aprobado		=	xGetElementById('nomempleadoA').value;
        
        
        /*aun no*/
        var chPlanilla		=	xGetElementById('ch_planilla').value;
        var chInforme		=	xGetElementById('ch_informe').value;
        var chFactura		=	xGetElementById('ch_factura').value;
        var chRecipe		=	xGetElementById('ch_recipe').value;
        var chOtro			=	xGetElementById('ch_otro').value;
        
        var movimineto		=	OBJ_BENEFICIO.nu_codigo.length;
        
        
        
        //alert(totalAgregado+' '+monto)
        
        if(!campoVacio(nroOrden))	
        {
            alert('Debes introducir el número del oficio');	
            xGetElementById('txt_nro_orden').focus();
            //bandera = 0;//bandera que permite asegurar que se entro en una condicion 
        }
        
        else if(tipoSolicitud=='0')
        {
            alert('Debes introducir el tipo de solicitud');		
            xGetElementById('sel_tipo_solicitud').focus();
            //bandera = 0;//bandera que permite asegurar que se entro en una condicion 
        }
        
        else if(codPersona=='')
        {
            alert('Debes seleccionar el funcionario');		
            //xGetElementById('fcreacion').focus();
            //bandera = 0;//bandera que permite asegurar que se entro en una condicion 
        }
        
        else if(servicio=='0')
        {
            alert('Debes seleccionar el servicio');		
            xGetElementById('txt_servicio').focus();
            //bandera = 0;//bandera que permite asegurar que se entro en una condicion 
        }
        
        else if(rama=='0')
        {
            alert('debes seleccionar la rama');	
            xGetElementById('sel_rama').focus();
            //bandera = 0;//bandera que permite asegurar que se entro en una condicion 
        }
        
        else if(institucion=='0')
        {
            alert('Debes seleccionar la institucion');
            xGetElementById('sel_institucion').focus();
            //bandera = 0;//bandera que permite asegurar que se entro en una condicion 
        }
        
        else if(medico=='0')
        {
            alert('Debes introducir por quien se debe aprobar el crédito adicional');	
            xGetElementById('nomempleado').focus();
            //bandera = 0;//bandera que permite asegurar que se entro en una condicion 
        }
        
        else if(movimineto<1)
        {
            alert('No existe movimientos agregado');	
            xGetElementById('txt_codigo').focus();
            //bandera = 0;//bandera que permite asegurar que se entro en una condicion 
        }
            
        else
        {		
            guardarBeneficio();		
        } 
    }
    
    
    
    function guardarBeneficio()
    {
        var nroOrden	  	= 	xGetElementById('txt_nro_orden').value;	
        var tipoSolicitud	=	xGetElementById('sel_tipo_solicitud').value;
        var codPersona		=	xGetElementById('codempleado').value;
        var codFamilia		=	xGetElementById('sel_familia_funcionario').value;
        
        var servicio		=	xGetElementById('sel_servicio').value;
        var rama			=	xGetElementById('sel_rama').value;
        var institucion		=	xGetElementById('sel_institucion').value;
        var medico			=	xGetElementById('sel_medico').value;
                
        var fechaCreacion	=	xGetElementById('fcreacion').value;
            fechaCreacion	=	fechaJStoMySql(fechaCreacion);
                            
        var estatus			=	xGetElementById('co_estado').value;
        var preparado 		=	xGetElementById('codprepor').value;
        var ultimaMod		=	xGetElementById('ult_usuario').value; 
        //codempleadoA
        
        var fechaUltimaMod	=	xGetElementById('ult_fecha').value;		
            fechaUltimaMod	=	fechaJStoMySql(fechaUltimaMod);
        
        var ejercicio		=	xGetElementById('ejercicioPpto').value;
        
        
        
        var totalAgregado	=	xGetElementById('totalMonto').innerHTML;
        var aprobado		=	xGetElementById('codempleadoA').value;
        
        
        
        var chPlanilla		=	xGetElementById('ch_planilla').checked;
        var chInforme		=	xGetElementById('ch_informe').checked;
        var chFactura		=	xGetElementById('ch_factura').checked;
        var chRecipe		=	xGetElementById('ch_recipe').checked;
        var chOtro			=	xGetElementById('ch_otro').checked;
        
        
        
        if(confirm('¿Esta seguro de guardar el beneficio?'))
        {	
        
        
                for(var i=0; i<OBJ_BENEFICIO.monto.length;i++)
                {
                    var montoItem=OBJ_BENEFICIO.monto[i];
                        montoItem=quitarMiles(montoItem);
                        //monto = monto.replace(',','.');	
                    //OBJ_CREDITO_ADICIONAL.monto[i]='';
                    OBJ_BENEFICIO.monto[i]=	montoItem+' ';
                    
                    //alert(OBJ_CREDITO_ADICIONAL.monto[i]);
                }
                var opx	=	'GUARDAR';
                var url 	=	'lib/transBeneficio.php';
                AjaxRequest.post
                                                            (
                                                                {
                                                                    'parameters':{'opcion':opx,
                                                                                  'nroOrden':nroOrden,
                                                                                  'tipoSolicitud': tipoSolicitud,
                                                                                  'codPersona':codPersona,
                                                                                  'codFamilia':codFamilia,
                                                                                  'servicio':servicio,
                                                                                  'rama':rama,																	  
                                                                                  'institucion':institucion,
                                                                                  'medico':medico,
                                                                                  'ejercicio':ejercicio,
                                                                                  'fcreacion':fechaCreacion,
                                                                                  'estatus':estatus,																	  
                                                                                  'totalAgregado':totalAgregado,
                                                                                  'aprobado':aprobado,
                                                                                  'preparado':preparado,
                                                                                  'ultimaMod':ultimaMod,
                                                                                  'fechaUltimaMod':fechaUltimaMod,
                                                                                  'chPlanilla':chPlanilla,
                                                                                  'chInforme':chInforme,
                                                                                  'chFactura':chFactura,
                                                                                  'chRecipe':chRecipe,																			  
                                                                                  'chOtro':chOtro,
                                                                                  'nu_codigo':OBJ_BENEFICIO.nu_codigo,
                                                                                  'descripcion':OBJ_BENEFICIO.descripcion,
                                                                                  'monto':OBJ_BENEFICIO.monto }
                                                                    ,'url':url
                                                                    ,'onSuccess': respGuardarBeneficio												       
                                                                    ,'onError':function(req)
                                                                    { 
                                                                        alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
                                                                    }         
                                                                }
                                                            );
        }
        
    }
    
    function respGuardarBeneficio(req)
    {
        //var datos= eval ("("+req.responseText+")");
        //alert(req.responseText);
        if(req.responseText == '1')
        {
            alert('Se ha guardado con exito el beneficio');
            limpiarCamposBeneficio();
        }
        else
        {
            alert('Error al guardar el beneficio');
        }
        
    }
    

// voy por aqui
    
	function buscarItemBeneficio(codigo, cond)
{
	//alert(codigo);
	var url   	 = 'lib/transBeneficio.php';
	var opx   	 = 'BUSCARITEMBENEFICIO';
	AjaxRequest.post
												(
								       				{
								       					'parameters':{'opcion':opx, 
																	  'codigo':codigo}
								            			,'url':url
								            			,'onSuccess': function(req) {respBuscarItemBeneficio(req,cond)}
														,'onError':function(req)
														{ 
								                        	alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
								                        }         
								             		}
								        		);	
}

function respBuscarItemBeneficio(req,cond)
{
	//alert(req.responseText);
	var datos= eval("("+req.responseText+")");		
	//xGetElementById('nomempleado').value = datos[0]['NomCompleto'];	
	
	//alert(req.responseText);
	
	
	for(var i=0; i<datos.length;i++)
	{
		
		//var i= OBJ_CREDITO_ADICIONAL.nu_sector.length;
		

		
		  	OBJ_BENEFICIO.nu_codigo[i] 	 = datos[i]['nroFactura'];
            OBJ_BENEFICIO.descripcion[i] = datos[i]['descripcionItem'];
            OBJ_BENEFICIO.monto[i]		 = toCurrency(datos[i]['montoItem']);
								
		var tam= OBJ_BENEFICIO.nu_codigo.length;
		
		
	}
	mostrarItemBeneficio(tam,cond);
			
}

	
	
	function buscarAprobarBeneficio(codigo)
{
	//alert(codigo);
	var url   	 = 'lib/transBeneficio.php';
	var opx   	 = 'BUSCARAPROBARBENEFICIO';
	AjaxRequest.post
												(
								       				{
								       					'parameters':{'opcion':opx, 
																	  'codigo':codigo}
								            			,'url':url
								            			,'onSuccess': respBuscarAprobarBeneficio
														,'onError':function(req)
														{ 
								                        	alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
								                        }         
								             		}
								        		);
	
	
	
}

function respBuscarAprobarBeneficio(req)
{
	//alert(req.responseText);
	var datos= eval ("("+req.responseText+")");		
	xGetElementById('nomempleadoA').value = datos[0]['NomCompleto'];	
}


	function buscarFuncionarioBeneficio(codigo)
{
	//alert(codigo);
	var url   	 = 'lib/transBeneficio.php';
	var opx   	 = 'BUSCARAPROBARBENEFICIO';
	AjaxRequest.post
												(
								       				{
								       					'parameters':{'opcion':opx, 
																	  'codigo':codigo}
								            			,'url':url
								            			,'onSuccess': respBuscarFuncionarioBeneficio
														,'onError':function(req)
														{ 
								                        	alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
								                        }         
								             		}
								        		);
	
	
	
}

function respBuscarFuncionarioBeneficio(req)
{
	//alert(req.responseText);
	var datos= eval ("("+req.responseText+")");		
	xGetElementById('nomempleado').value = datos[0]['NomCompleto'];	
}

	
	

//	  28/11/2012 ernesto rivas
function cargarFamiliarEmpleadoVer(codigo, accion, familiar) {
	
	
	
	var url = 'lib/transBeneficio.php';
	AjaxRequest.post
												(
								       				{
								       					'parameters':{'opcion':accion, 
																	  'codigo':codigo
																	  }
								            			,'url':url
								            			,'onSuccess': function(req){respCargarFamiliarEmpleadoVer(req,familiar)}
														,'onError':function(req)
														{ 
								                        	alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
								                        }         
								             		}
								        		);
												
												
}

function respCargarFamiliarEmpleadoVer(req,familia)
{
	//alert('sdfsdfsdfsdf');
	var resp =eval ("("+req.responseText+")");
	//alert(resp);
	
	var parentesco ='';
	
		xGetElementById('sel_familia_funcionario').length=1;
	
		xGetElementById('sel_familia_funcionario').options[0].value = '0';
		xGetElementById('sel_familia_funcionario').options[0].text  = '..';
	
	
	
	if(resp!=null)
	{
		xGetElementById('sel_familia_funcionario').length=resp.length+1;
	
		xGetElementById('sel_familia_funcionario').options[0].value = '0';
		xGetElementById('sel_familia_funcionario').options[0].text  = '..';
	
		for(var i=0; i<resp.length;i++)
		{
			parentesco ='';
			if(resp[i]['Parentesco']=='PA') parentesco='PADRE';
			if(resp[i]['Parentesco']=='MA') parentesco='MADRE';
			if(resp[i]['Parentesco']=='HI') parentesco='HIJO';
			if(resp[i]['Parentesco']=='ES') parentesco='ESPOSA(O)';
			
			xGetElementById('sel_familia_funcionario').options[i+1].value=resp[i]['CodSecuencia'];
			xGetElementById('sel_familia_funcionario').options[i+1].text=resp[i]['NombresCarga']+'      --      '+parentesco;
		}
	}
	xGetElementById('sel_familia_funcionario').value=familia;
	
	
	
}	

	
	
function cargarDatosBeneficio(codigo,cond)
{
	//alert(codigo);
	
	var url   	 = 'lib/transBeneficio.php';
	var opx   	 = 'BUSCARBENEFICIO';
	AjaxRequest.post
												(
								       				{
								       					'parameters':{'opcion':opx, 
																	  'codigo':codigo}
								            			,'url':url
								            			,'onSuccess': function(req) {respCargarDatosBeneficio(req,cond)}											       
														,'onError':function(req)
														{ 
								                        	alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
								                        }         
								             		}
								        		);
	
	
	
}


function respCargarDatosBeneficio(req, cond)
{
	var datos= eval ("("+req.responseText+")");
	//var filas = datos.length;
		
	//alert(datos[0]['tx_estatus']);
	
	///////////////////////////////////////////////////
	//para buscar los item //
	///////////////////////////////////////////////////
		buscarItemBeneficio(datos[0]['codBeneficio'],cond);		
	/////////////////////////////////////////////////
	
	
	///////////////////////////////////////////////////
	//PARA BUSCAR QUE VA APROBAR EL CREDITO ADICIONAL//
	///////////////////////////////////////////////////
		var aprobado = datos[0]['aprobadoPor'];		
		//alert(aprobado);
		//xGetElementById('codempleadoA').value=aprobado;
		buscarAprobarBeneficio(aprobado);
	
	////////////////////////////////////////////////////////////////////////////////////////
	//xGetElementById('organismo').value=datos[0]['CodOrganismo'];
	//
	xGetElementById('txt_nro_orden').value=datos[0]['nroBeneficio'];
	//xGetElementById('sel_tipo_solicitud').value=datos[0]['tipoSolicitud'];
		
	buscarFuncionarioBeneficio(datos[0]['codPersona']);
	cargarFamiliarEmpleadoVer(datos[0]['codPersona'],'CARGAFAMILIAR',datos[0]['codFamiliar']);
				
	
	cargarServicio(datos[0]['codAyudaE']);
    cargarRama(datos[0]['codRamaS']);
    cargarInstitucion(datos[0]['idInstHcm']);
    cargarMedico(datos[0]['idMedHcm']);
	
	
	if(datos[0]['planillaSolicitud']=='1')
		xGetElementById('ch_planilla').checked=true;
	
	if(datos[0]['informeMedico']=='1')			
		xGetElementById('ch_informe').checked=true;
		
	if(datos[0]['facturaMedicina']=='1')
		xGetElementById('ch_factura').checked=true;
	
	if(datos[0]['recipeMedico']=='1')
		xGetElementById('ch_recipe').checked=true;
		
	if(datos[0]['otros']=='1')
		xGetElementById('ch_otro').checked=true;
		
	xGetElementById('ejercicioPpto').value=datos[0]['anhoEjecucio'];
	
	xGetElementById('fcreacion').value=datos[0]['fechaEjecucion'];
	

	
	if(datos[0]['estadoBeneficio']=='PE')
		xGetElementById('estado').value = 'Preparado';
	else if(datos[0]['estadoBeneficio']=='RV')
		xGetElementById('estado').value = 'Revisado';
	else if(datos[0]['estadoBeneficio']=='AP')
		xGetElementById('estado').value = 'Aprobado';
	else if(datos[0]['estadoBeneficio']=='GE')
		xGetElementById('estado').value = 'Generado';
	else
		xGetElementById('estado').value = 'Anulado';
		
		
	
	//var totalAgregado	=	xGetElementById('totalMonto').value;
	//alert(datos[0]['tx_preparado']);
	xGetElementById('codprepor').value = datos[0]['preparadoPor'];
	xGetElementById('prepor').value= datos[0]['preparadoPor'];
	
	
	
	xGetElementById('ult_usuario').value=datos[0]['UltimoUsuario'];
	xGetElementById('ult_fecha').value=datos[0]['UltimaFecha'];		
	
	preparadoPor();
		//fechaUltimaMod	=	fechaJStoMySql(fechaUltimaMod);			*/			
}   

function preparadoPor()
{
	var codigo = xGetElementById('codprepor').value;
	//xGetElementById('prepor').value= datos[0]['preparadoPor'];
	
	
	var url   	 = 'lib/transBeneficio.php';
	var opx   	 = 'PREPARADOPOR';
	AjaxRequest.post
												(
								       				{
								       					'parameters':{'opcion':opx, 
																	  'codigo':codigo}
								            			,'url':url
								            			,'onSuccess': respPreparadoPor											       
														,'onError':function(req)
														{ 
								                        	alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);
								                        }         
								             		}
								        		);
}     

function respPreparadoPor(req)
{
	var datos= eval ("("+req.responseText+")");
	
	xGetElementById('codprepor').value = datos[0]['Usuario'];
	xGetElementById('prepor').value= datos[0]['NomCompleto'];
	
}    
    
        
    </SCRIPT>
    
    <?php
	echo '<script type="text/javascript">';
	echo 'cargarDatosBeneficio('.$coBeneficio.',"VER");';
	echo '</script>';
	?>
</body>

</html>>>
