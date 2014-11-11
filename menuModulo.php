<?php
session_name("SIACEDA");
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<title>[S.I.A.C.E.M]</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<link href="imagenes/icono.ico" type="image/x-icon" rel="shortcut icon" />
<link rel="stylesheet" href="imagenes/mm_restaurant1.css" type="text/css" />
<link rel="stylesheet" href="css/gips.css" type="text/css" />
<link rel="stylesheet" href="css/texto_alternativo.css" type="text/css" />
<link rel="stylesheet" href="css/estilo.css" type="text/css" />

<script type="text/javascript" language="javascript" src="fscript.js"></script>

<script language="javascript" src="js/xCes.js"></script>
<script language="javascript" src="js/AjaxRequest.js"></script>
<script language="javascript" src="js/procesarLogeo.js"></script>
<script language="javascript" src="js/dom.js"></script>
<script language="javascript" src="js/texto_alternativo.js"></script>

<script language="javascript" src="js/funciones.js"></script>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/gips.js"></script>

<style type="text/css">
<!--
.Estilo1 {
	color: #FF0000;
	font-weight: bold;
}
-->
</style>
</head>
<body onload="verificarDatosSesion();cargarAlerta('inicioPagina');cargarAlerta('');verificarEnvioCorreo(0);cargarListaAlerta();">

<div id="bloqueo" style="height:99%; width:99%; position:absolute; display:none;"></div>
<div id="validar" style="display:none; position:absolute; left:15%; top:20%;">
<form name="frmentrada" id="frmentrada" onsubmit="return validacionUsuario();">
<input type="hidden" name="modulo" id="modulo" />
<div style="width:400px" class="divFormCaption">Datos del Usuario</div>
<table width="400" class="tblForm">
    <tr>
        <td class="tagForm">Usuario:</td>
        <td><input name="usuario" type="text" id="usuario" size="30" maxlength="20" onchange="cargarOrganismos();" /></td>
    </tr>
    <tr>
        <td class="tagForm">Contrase&ntilde;a:</td>
        <td><input name="clave" type="password" id="clave" size="30" maxlength="20" onfocus="cargarOrganismos();" /></td>
    </tr>
    <tr>
        <td class="tagForm">Organismo:</td>
        <td>
            <select name="organismo" id="organismo" style="width:250px;">
                <option value=""></option>
            </select>
        </td>
    </tr>
    <tr>
    	<td colspan="2" align="center">
        	<input type="submit" value="Aceptar" onclick="cargarOrganismos();" />
        	<input type="button" value="Cancelar" onclick="location.href='index.php'" />
        </td>
    </tr>
</table>
</form>
</div>
<br  />
<div align="center" class="bordePaginaMenu" style="width:1220px" >
<table  width="97%" border="0" cellspacing="0" cellpadding="0">
	<tr bgcolor="">
        <td colspan="2" rowspan="2" nowrap="nowrap"><img  style="width:1220px; HEIGHT:140px" src="imagenes/bannerSiacemMenu.jpg" /></td>
      <td width="4" height="60" align="left" valign="bottom"></td>
	</tr>

	<tr bgcolor="">
        <td height="36" align="right">&nbsp;</td>
	</tr>

	<tr bgcolor="">
	<td width="35" valign="top"><img src="imagenes/mm_spacer.gif" alt="" width="35" height="1" border="0" /></td>
	<td width="1302" align="right" valign="top"><a href="javascript:cerrarSesion();">Cerrar Sesi&oacute;n&nbsp;</a><br />
	  <br />
	    <br />
	<table border="0" cellspacing="0" cellpadding="2" width="1179">
        <tr>
          <td height="43" colspan="7" class="pageName">&nbsp;</td>
        </tr>
		<tr>
         <td width="14%" height="110"><a onclick="abrirModulo('rh');" href="javascript:;"><img class="ces_div_contenido_blanco" src="imagenes/menu_rrhh.png" alt="Entrar a Recursos Humanos"  name="iconRRHH" width="110" height="110"  id="iconRRHH" style="border-color:#999999"  /></a></td>
		  <td width="1%">&nbsp;</td>
		  <td width="16%" height="110"><a onclick="abrirModulo('nomina');" href="javascript:;"><img class="ces_div_contenido_blanco" id="iconNomina" src="imagenes/menu_nomina.png" alt="Entrar a Nómina" width="110" height="110" style="border-color:#999999" /></a></td>
		  <td width="1%">&nbsp;</td>
		  <td width="15%" height="110"><a onclick="abrirModulo('lg');" href="javascript:;"><img class="ces_div_contenido_blanco" id="iconLogistica" src="imagenes/menu_lg.png" alt="Entrar a Logística" width="110" height="110" style="border-color:#999999" /></a></td>
		  <td width="1%">&nbsp;</td>
			  <td width="15%" height="110"><a onclick="abrirModulo('pv');" href="javascript:;"><img class="ces_div_contenido_blanco" id="iconPresupuesto" src="imagenes/menu_pv.png" alt="Entrar a Presupuesto" width="110" height="110" style="border-color:#999999" /></a></td>
		  <td width="1%">&nbsp;</td>
		  <td width="15%" height="110"><a onclick="abrirModulo('pa');" href="javascript:;"><img class="ces_div_contenido_blanco" id="iconParque" src="imagenes/menu_pa.png" alt="Entrar a Parque Automotor" width="110" height="110" style="border-color:#999999" /></a></td>
		   <td width="1%">&nbsp;</td>
		  <td width="15%" height="110"><a onclick="abrirModulo('ev');" href="javascript:;"><img class="ces_div_contenido_blanco" id="iconEvento" src="imagenes/menu_ev.png" alt="Entrar a Control de Documentos" width="110" height="110" style="border-color:#999999" /></a></td>
        </tr>
		<tr>
          <td valign="top" class="bodyText" nowrap="nowrap"><a onclick="abrirModulo('rh');" href="javascript:;">Recursos Humanos</a></td>
		  <td>&nbsp;</td>
		   <td valign="top" class="bodyText" nowrap="nowrap"><a onclick="abrirModulo('nomina');" href="javascript:;">N&oacute;mina</a><br />		  </td>
		 <td>&nbsp;</td>
		   <td valign="top" class="bodyText" nowrap="nowrap"><a onclick="abrirModulo('lg');" href="javascript:;">Log&iacute;stica</a><br />		  </td>
		 <td>&nbsp;</td>
		   <td valign="top" class="bodyText" nowrap="nowrap"><a onclick="abrirModulo('pv');" href="javascript:;">Presupuesto</a><br />		  </td>
		 <td>&nbsp;</td>
		   <td valign="top" class="bodyText" nowrap="nowrap"><a onclick="abrirModulo('pa');" href="javascript:;">Parque Automotor</a><br />		  </td>
		    <td>&nbsp;</td>
		 <td><span class="bodyText"><a onclick="abrirModulo('ev');" href="javascript:;">Eventos</a></span></td>
        </tr>
		<tr>
			<td height="65" colspan="11">&nbsp;</td>
		</tr>
		<tr >
         <td height="110"><a onclick="abrirModulo('ap');" href="javascript:;"><img class="ces_div_contenido_blanco" id="iconCPP" src="imagenes/menu_ap.png" alt="Entrar a Cuentas por Pagar" width="110" height="110" style="border-color:#999999" /></a></td>
		  <td>&nbsp;</td>
		  <td height="110"><a onclick="abrirModulo('ac');" href="javascript:;"><img class="ces_div_contenido_blanco" id="iconContabilidad" src="imagenes/menu_ac.png" alt="Entrar a Contabilidad" width="110" height="110" style="border-color:#999999" /></a></td>
		  <td>&nbsp;</td>
		  <td height="110"><a onclick="abrirModulo('af');" href="javascript:;"><img class="ces_div_contenido_blanco" id="iconActivoFijo" src="imagenes/menu_af.png" alt="Entrar a Activos Fijos" width="110" height="110" style="border-color:#999999" /></a></td>
		  <td>&nbsp;</td>
		  <td height="110"><a onclick="abrirModulo('pf');" href="javascript:;"><img class="ces_div_contenido_blanco" id="iconPF" src="imagenes/menu_pf.png" alt="Entrar a Planificaci&oacute;n Fiscal" width="110" height="110" style="border-color:#999999" /></a></td>
		  <td>&nbsp;</td>
		  <td height="110"><a onclick="abrirModulo('cp');" href="javascript:;"><img class="ces_div_contenido_blanco" id="iconCD" src="imagenes/menu_doc.png" alt="Entrar a Control de Documentos" width="110" height="110" style="border-color:#999999" /></a></td>
		  <td height="110">&nbsp;</td>
		  <td><span class="bodyText"><a onclick="" target="_blank" href="http://192.168.0.3/siacem01/intranet.php"><img class="ces_div_contenido_blanco" id="iconEvento" src="imagenes/menu_intra.png" alt="Entrar a la Intranet" width="110" height="110" style="border-color:#999999" /></a></span></td>
        </tr>
		<tr>
          <td valign="top" class="bodyText" nowrap="nowrap"><a onclick="abrirModulo('ap');" href="javascript:;">Cuentas por Pagar</a><br />		  </td>
		  <td>&nbsp;</td>
		   <td valign="top" class="bodyText" nowrap="nowrap"><a onclick="abrirModulo('ac');" href="javascript:;">Contabilidad</a><br />		  </td>
		 <td>&nbsp;</td>
		   <td valign="top" class="bodyText" nowrap="nowrap"><a onclick="abrirModulo('af');" href="javascript:;">Activos Fijos</a><br />		  </td>
		 <td>&nbsp;</td>
		   <td valign="top" class="bodyText" nowrap="nowrap"><a onclick="abrirModulo('pf');" href="javascript:;">Planificaci&oacute;n Fiscal</a><br />		  </td>
		 <td>&nbsp;</td>
		   <td valign="top" class="bodyText" nowrap="nowrap"><a onclick="abrirModulo('cp');" href="javascript:;">Control de Documentos</a><br />		  </td>
		    <td>&nbsp;</td>
		   <td valign="top" class="bodyText" nowrap="nowrap"><a target="_blank" href="http://192.168.0.3/siacem01/intranet.php">Intranet</a><br />		  </td>
        </tr>
		<tr>
			<td colspan="7">&nbsp;</td>
		</tr>
      </table>	</td>
	<td>&nbsp;</td>
	</tr>
		<tr>
			<td height="16" colspan="11" id="columnaAlerta">

          </td>
		</tr>
	<tr>
	
	<td width="35" height="17">&nbsp;</td>
    <td width="1302">&nbsp;</td>
	<td width="4">&nbsp;</td>
  </tr>
</table>
</div>
<script language="javascript">

	var objRRHH;
	var objPresupuesto;
	var objNomina;
	var objCPP;
	var objActivoFijo;	
	var objControlDocumetno;
	var objPlanificacionFiscal;
	var objLG;
 
	/*$(document).ready(function () {
	
		//objRRHH =  $('#iconRRHH').gips({ 'theme': 'red', autoHide: false, text: '',idGlobo:'iconRRHH',placement:'top'});
		//objPresupuesto =  $('#iconPresupuesto').gips({ 'theme': 'red', autoHide: false, text: '',idGlobo:'iconPresupuesto',placement:'top'});

		
			
	});*/
	

	//.style.display = 'block';
	function mostrarGlobo(idModulo,contenido)
	{
		
		xGetElementById(idModulo+'1').innerHTML=contenido+xGetElementById(idModulo+'1').innerHTML;
		xGetElementById(idModulo+'0').style.display ="block";
		xGetElementById(idModulo+'1').style.display ="block";
		xGetElementById(idModulo+'2').style.display ="block";
		xGetElementById(idModulo+'1').style.opacity="1";
		xGetElementById(idModulo+'2').style.opacity="1";
		
		
		
		if(idModulo == 'iconRRHH')
		{
			objRRHH.mouseover();
		}
		
		if(idModulo == 'iconPresupuesto')
		{
			objPresupuesto.mouseover();
		}
		
		if(idModulo == 'iconNomina')
		{
			objNomina.mouseover();
		}
		
		if(idModulo == 'iconCPP')
		{
			objCPP.mouseover();
		}
		
		if(idModulo == 'iconActivoFijo')
		{
			objActivoFijo.mouseover();
		}
		
		if(idModulo == 'iconCD')
		{
			objControlDocumetno.mouseover();
		}
		
		if(idModulo == 'iconPF')
		{
			objPlanificacionFiscal.mouseover();
		}
		
		if(idModulo == 'iconLogistica')
		{
			objLG.mouseover();
		}
		
		a = parseInt((xGetElementById(idModulo+'0').style.left).replace('px','')-13);
		
//		xGetElementById(idModulo+'0').style.left = a+'px';//="425px";
//		alert((xGetElementById(idModulo+'0').style.left).replace('px',''))
	}
	
	function ocultarGlobo(idModulo)
	{
		
//		document.getElementById('RRHH').innerHTML='<table width="200" border="0"><tr><td >This io hide after pausess time elapses.</td></tr></table>'+document.getElementById('RRHH').innerHTML;
		xGetElementById(idModulo+'0').style.display ="none";
		xGetElementById(idModulo+'1').style.display ="none";
		xGetElementById(idModulo+'2').style.display ="none";
		xGetElementById(idModulo+'1').style.opacity="0";
		xGetElementById(idModulo+'2').style.opacity="0";
		
		if(idModulo == 'iconRRHH')
		{
			objRRHH.mouseout();
		}
		
		if(idModulo == 'iconPresupuesto')
		{
			objPresupuesto.mouseout();
		}
		
		if(idModulo == 'iconNomina')
		{
			objNomina.mouseout();
		}
		
		if(idModulo == 'iconCPP')
		{
			objCPP.mouseout();
		}
		
		if(idModulo == 'iconActivoFijo')
		{
			objActivoFijo.mouseout();
		}
		
		if(idModulo == 'iconCD')
		{
			objControlDocumetno.mouseout();
		}
		
		if(idModulo == 'iconPF')
		{
			objPlanificacionFiscal.mouseout();
		}
		
		if(idModulo == 'iconLogistica')
		{
			objLG.mouseout();
		}

	}
	
	
	if(!document.all) 
	{
	
		document.captureEvents(Event.MOUSEMOVE);
	
	}
	
	document.onmousemove = leerCoordRaton;
	
	


</script>
<div id='ALTdHTML' style='position:absolute;  visibility: hidden; z-index:3050; left:0px; top:0px;'>&nbsp;</div>

</body>
</html>
