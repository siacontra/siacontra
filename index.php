<?php
/****************************************************************************************
* LOCACI�N::VENEZUELA-SUCRE-CUMAN�
* PROGRAMADOR: Christian Hern�ndez
* 

*********************************************************************************************/ 
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script language="javascript" src="js/xCes.js"></script>
<script language="javascript" src="js/AjaxRequest.js"></script>

<script language="javascript" src="js/procesarLogeo.js"></script>
<script language="javascript" src="js/dom.js"></script>


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<link href="imagenes/icono.ico" type="image/x-icon" rel="shortcut icon" />
<link rel="stylesheet" href="imagenes/mm_restaurant1.css" type="text/css" />
<link href="css/estilo.css" rel="stylesheet" type="text/css" />

<!--<link href="css/calendar-win2k-cold-1.css" rel="stylesheet" type="text/css" />-->
<title>[S.I.A.C.E.M.]</title>
</head>

<body>
<form>
<br />
<div align="center" class="bordePagina" >
<table width="1024" height="537" border="0" align="center" cellspacing="0">
   <tr>
    <td align="center" valign="top"  width="1024">
		<img src="imagenes/banner.png" width="1026" height="120">
		<!--<table cellpadding="0" cellspacing="0"  width="1024">
			 <tr>
					<td></td>
					
		  </tr>
			
				<tr bgcolor="#003399">
					<td><hr></td>
				</tr>
		</table>-->	</td>
  </tr>
 
  <tr>
    <td align="center" valign="top"></td>
  </tr>
  <tr>
    <td height="384" align="center" valign="top" >
	<br />
		<table width="753" border="0" align="center">
          <tr>
            <td width="466" height="296" align="center" valign="top"><fieldset style="width:350px">
		<legend>Iniciar Sesi&oacute;n</legend>

		<table width="218" border="0" align="center" cellspacing="0">
          <tr>
            <td width="216"></td>
          </tr>
          <!--<tr>
            <td><label>Introduzca su Usuario y Contrase&ntilde;a</label></td>
          </tr>-->
          <tr>
            <td><table width="191" border="0" align="left">
              <tr>
                <td width="39" align="right" ><label class="tagForm">Usuario:</label></td>
                <td width="142" align="left">
                  <input type="text" class="" id="accesoUsuario" onKeyUp="" maxlength="30" value="" />                </td>
              </tr>
              <tr>
                <td align="right" ><label class="tagForm">Contraseña:</label></td>
                <td align="left">
                  <input type="password" class=""  value="" id="accesoClave" maxlength="30" onKeyup="llamadoGenericoEvento(event,'iniciarSesion',this.id);" />
 				</td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><label>
              <div align="right">
                <input type="button" name="Submit" value="Ingresar" onClick="iniciarSesion();" />
              </div></
            </label></td>
          </tr>
        </table>
	  </fieldset></td>
          </tr>
          <tr>
            <td  align="center" valign="top" class="tituloPrincipalFormulario"><div align="center"></div></td>
          </tr>
		   <tr>
            <td  align="center" valign="top" class="tituloPrincipalFormulario"><div align="center"></div></td>
          </tr>
        </table>
		<p>&nbsp;</p>
		
	</td>
  </tr>
</table>
</div>
</form>
</body>
</html>
<?php 
	//include("controller/cargador.php");
?>
