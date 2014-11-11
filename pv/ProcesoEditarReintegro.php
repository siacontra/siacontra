<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include ("fphp.php");
connect();
/// ------------------------------------
list($CodAjuste,$fechaAjuste,$organismo,$CodPresupuesto)= SPLIT('[|]',$_POST['registro']);
$sql="SELECT * 
        FROM pv_ReintegroPresupuestario
       WHERE CodReintegro='$CodAjuste'";
$qry=mysql_query($sql) or die ($sql.mysql_error());
if(mysql_num_rows($qry)!=0){
 $field=mysql_fetch_array($qry);
 if($field[Estado]==AP){
   echo"<script>
        alert('NO PUEDE SER EDITADO POR ESTAR EN ESTADO APROBADO');
        history.back(-1);
      </script>";
 }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="fscriptpresupuesto.js"></script>
<link href="js/Calendario/jsDatePick_ltr.css"  media="all" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="js/Calendario/jsDatePick.full.1.3.js"></script>
<script type="text/javascript">
	window.onload = function(){
		new JsDatePick({
			useMode:2,
			target:"fresolucion",
			dateFormat:"%d-%m-%Y"
		});
                new JsDatePick({
			useMode:2,
			target:"fgaceta",
			dateFormat:"%d-%m-%Y"
		});
	};
</script>
<script type="text/javascript">
function Alarma2(){
  alert("ANTES DE REALIZAR LA ACCION DEBE GUARDAR LOS CAMBIOS REALIZADOS...!");
  return true;
}
</script>
</head>
<body>
<style type="text/css">
<!--
UNKNOWN { FONT-SIZE: small}
#header { FONT-SIZE: 93%; BACKGROUND: url(imagenes/bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal}
#header UL {PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none}
#header LI {PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(imagenes/left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px}
#header A {PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(imagenes/right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none}
#header A {FLOAT: none} 
#header A:hover {COLOR: #333}
#header #current {BACKGROUND-IMAGE: url(imagenes/left_on.gif)}
#header #current A {BACKGROUND-IMAGE: url(imagenes/right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333}
-->
</style>
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
 <td class="titulo">Editar | Reintegro</td>
 <td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'),'<?=$regresar?>.php?limit=0')">[Cerrar]</a></td>
</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="ProcesoListarReintegro.php?limit=0&accion=EditarReintegro" method="post">
<?php 
include "gpresupuesto.php"; 
echo"<input type='hidden' id='regresar' name='regresar' value='".$regresar."'/>";
?>
<table width="850" align="center">
<tr><td>
<div id="header">
<ul>
<!-- CSS Tabs PESTA�AS OPCIONES DE PRESUPUESTO -->
<li>
<a onClick="document.getElementById('tab1').style.display='block'; 
            document.getElementById('tab2').style.display='none';" href="#">Datos Generales</a></li>
<li>
<a onClick="document.getElementById('tab1').style.display='none'; 
            document.getElementById('tab2').style.display='block';" href="#">Detalle de Reintegro</a></li>
</ul>
</div>
  </td>
</tr>
</table>
<div id="tab1" style="display:block;"><!-- PRIMER TAB MUESTRA LOS DATOS GENERALES -->
<div style="width:850px" class="divFormCaption">Informaci&oacute;n de Reintegro</div>
<table width="850" class="tblForm">
<tr> <?php
       //echo"Mostrando:".$_POST['registro'];
	  $SQL="SELECT * 
	          FROM 
			       pv_ReintegroPresupuestario 
			 WHERE 
			       CodReintegro='$CodAjuste' and 
				   CodPresupuesto = '$CodPresupuesto' and 
				   Organismo = '$organismo'";
	   $QRY=mysql_query($SQL) or die ($SQL.mysql_error());
	   $FIELD=mysql_fetch_array($QRY);
	   $codajuste=$FIELD['CodAjuste'];
	   $codpresupuesto=$FIELD['CodPresupuesto'];
	   $ejercicioPpto=$FIELD['EjercicioPpto'];
	   echo "
            <input type='hidden' name='ejercicioPpto' id='ejercicioPpto' value='$ejercicioPpto'/>
			<input type='hidden' name='codPresupuesto' id='codPresupuesto' value='$codpresupuesto'/>
			<input type='hidden' name='registro' id='registro' value='".$_POST['registro']."'/>
            <input type='hidden' name='filtro' id='filtro' value='".$filtro."' />
            <input type='hidden' name='regresar' id='regresar' value='".$regresar."' />
            <input type='hidden' name='forganismo' id='forganismo' value='".$forganismo."' />";
			
     ?>
  <td width="48"></td>
  <td width="90" class="tagForm">Organismo:</td>
  <td width="300"><select name="org" id="org" class="selectBig" >
		<?php  
		$s_org = "SELECT CodOrganismo,Organismo FROM mastorganismos";
		$q_org = mysql_query($s_org) or die ($s_org.mysql_error());
		$r_org = mysql_num_rows($q_org);
		for($i=0;$i<$r_org;$i++){
		  $f_org = mysql_fetch_array($q_org);
		  if($f_org['CodOrganismo']==$FIELD['Organismo'])
		    echo"<option value='".$f_org['CodOrganismo']."' selected>".$f_org['Organismo']."</option>";
		  else
		    echo"<option value='".$f_org['CodOrganismo']."'>".$f_org['Organismo']."</option>";
		}?>
    </select></td>
</tr>
<tr>
   <td height="4"></td>
</tr>
</table>
<table width="850" class="tblForm" border="0">
<tr><td height="2"></td></tr>
   <?php
	list($a, $m, $d)=SPLIT( '[/.-]', $FIELD['FechaAjuste']); $fajuste=$d.'-'.$m.'-'.$a;
	list($a, $m)=SPLIT( '[/.-]', $FIELD['Periodo']); $fPeriodo=$m.'-'.$a;
   ?>
<tr>
 <td width="112"></td>
 <?
  if($FIELD['FechaResolucion']!='0000-00-00'){list($a, $m, $d)=SPLIT( '[/.-]', $FIELD['FechaResolucion']); $fres=$d.'-'.$m.'-'.$a;}
 ?>
 <td width="156" align="right">Nro. Oficio o Resolucion:</td>
 <td width="110"><input name="resolucion" id="resolucion" type="text" size="18" style="text-align:right" value="<?=$FIELD['NumResolucion'];?>" />*</td>
 <td width="84" align="right">F. Oficio o Resoluci&oacute;n:</td>
 <td width="181"><input name="fresolucion" id="fresolucion" type="text" size="8" maxlength="10" style="text-align:right" value="<?=$fres?>" />*<i>(dd-mm-aaaa)</i>
 
 </td>
</tr>
<tr>
 <td width="112"></td>
 <?
  if($FIELD['FechaGaceta']!='0000-00-00'){list($a, $m, $d)=SPLIT( '[/.-]', $FIELD['FechaGaceta']); $fgaceta=$d.'-'.$m.'-'.$a;}
 ?>
 <td width="156" align="right">Nro. Gaceta:</td>
 <td width="110"><input name="gaceta" id="gaceta" type="text" size="18" style="text-align:right" value="<?=$FIELD['NumGaceta'];?>"/>*</td>
 <td width="84" align="right">F. Gaceta:</td>
 <td width="181"><input name="fgaceta" id="fgaceta" type="text" size="8" style="text-align:right" maxlength="10" value="<?=$fgaceta?>" />*<i>(dd-mm-aaaa)</i>
 
 </td>
 <td colspan="2" width="179"></td>
</tr>
<tr><td height="2"></td></tr>
</table>
<table width="850" class="tblForm">
<tr><td height="2"></td></tr>
<tr>
 <td width="149"></td>
 <td width="117" class="tagForm">Nro. Presupuesto:</td>
 <td width="115"><input id="npresupuesto" name="npresupuesto" type="text" size="8" value="<?=$FIELD['CodPresupuesto'];?>" readonly/>
    <!-- <input name="bt_examinar" id="bt_examinar" type="button" value="..." onclick="cargarVentana(this.form, 'presupuesto_seleccionar.php?limit=0&campo=1', 'height=500, width=800, left=200, top=200, resizable=yes');"/>*-->
 </td>
 <td width="79" class="tagForm">Estado:</td>
 <?
   if($FIELD[Estado]==PR){$st=Pendiente;}if($FIELD[Estado]==AP){$st=Aprobado;}
   if($FIELD['TipoAjuste']=='IN'){ $tipo = 'IN'; $tipoAjuste='Incremento';} 
   if($FIELD['TipoAjuste']=='DI'){ $tipo = 'DI';$tipoAjuste='Disminuci�n';}
 ?>
 <td width="135"><input type="text" id="status" name="status" size="10" value="<?=$st?>"  readonly/></td>
 <td width="227"></td>
</tr>
<tr>
 <td width="149"></td>
 <td class="tagForm">F. Reintegro:</td>
 <td><input type="text" id="fAjuste" name="fAjuste" size="8" maxlength="8" value="<?=$fajuste?>" readonly/></td>
 <td><input type="hidden" id="tAjuste" value="IN" />
  </td>
</tr>
<tr><td height="2"></td></tr>
</table>
<div style="width:850px" class="divFormCaption">Duraci&oacute;n del Reintegro</div>
<table class="tblForm" width="850">
<tr><td height="2"></td></tr>
<tr>
  <td width="50"></td>
  <td width="70" align="right">Per&iacute;odo:</td>
  <td width="180"><input id="fPeriodo" name="fPeriodo" type="text" size="8" maxlength="10" value="<?=$fPeriodo;?>" readonly/>*<i>(mm-aaaa)</i></td>
</tr>
<tr><td height="2"></td></tr>
</table>
<div style="width:850px" class="divFormCaption">Descripci&oacute;n del Reintegro</div>
<table class="tblForm" width="850">
<tr>
<td colspan="1"></td>
<td width="72">Descripci&oacute;n:</td>
</tr>
<tr>
  <td colspan="1"></td>
  <td></td>
  <td width="580"><textarea name="descripcion" id="descripcion" rows="5" cols="80" ><? echo $FIELD['Descripcion'];?></textarea>*</td>
</tr>
<tr><td height="2"></td></tr>
</table>
<table width="850" class="tblForm">
<tr><td></td>
   <td width="245" class="tagForm">Preparado por:</td>
      <? $sql3=mysql_query("SELECT * FROM usuarios WHERE Usuario='".$_SESSION['USUARIO_ACTUAL']."'");
		 if(mysql_num_rows($sql3)!=0){
		  $field3=mysql_fetch_array($sql3);
		  $sql4=mysql_query("SELECT * FROM mastpersonas WHERE CodPersona='".$field3['CodPersona']."'");
		  if(mysql_num_rows($sql4)!=0){
		   $field4=mysql_fetch_array($sql4);
		  }
		 }
	  ?>
   <td width="520"><input name="prepor" id="prepor" type="text" size="60" value="<?=$field4['NomCompleto']?>" readonly/></td>
</tr>
<tr>
  <td></td>
  <td class="tagForm">Aprobado por:</td>
  <td>
    <input name="codempleado" type="hidden" id="codempleado" value="" />
	<input name="nomempleado" id="nomempleado" type="text" size="60" value="<?=$FIELD['AprobadoPor'];?>" readonly/>
    <!--<input name="bt_examinar" id="bt_examinar" type="button" value="..." onclick="cargarVentana(this.form, 'lista_empleados.php?limit=0&campo=1', 'height=500, width=800, left=200, top=200, resizable=yes');"/>* -->
  </td>
     
</tr>
<tr><td></td>
   <td class="tagForm">&Uacute;ltima Modif.:</td>
   <td colspan="1">
	<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$FIELD['UltimoUsuario']?>" readonly />
	<input name="ult_fecha" type="text" id="ult_fecha" size="22" value="<?=$FIELD['UltimaFechaModif'];?>" readonly /></td>
</tr>
<tr><td height="5"></td></tr>
</table>
</div>

<div id="tab2" style="display:none;">
<div style="width:850px" class="divFormCaption">Detalle del Reintegro</div>
<table width="850" class="tblBotones">
<tr><td align="right"><!--
  <input name="btNuevo" type="button" id="btNuevo" value="Agregar"  onclick="Alarma()"/>
  <input name="btBorrar" type="button" id="btBorrar" value="Eliminar" onClick="Alarma()"/>-->
  </td>
</tr>
</table>
<table width="850" class="tblLista" border="0">
<tr class="trListaHead">
 <th width="85" scope="col"># Presupuesto</th>
 <th width="80" scope="col"># Ajuste</th>
 <th width="80" scope="col"># Partida</th>
 <th scope="300">Denominaci&oacute;n</th>
 <th width="125" scope="col">MontoDMA</th>
 <th width="125" scope="col">Monto Reintegro</th>
</tr>
<?php
//------------------------------------------------------------------------------------------------------------
list($CodAjuste,$fechaAjuste,$organismo,$CodPresupuesto)= SPLIT('[|]',$_POST['registro']);
$year_actual = date("Y");
$year_actual = substr($fechaAjuste, 0, 4);
 $sql="select 
             aj.CodPresupuesto AS CodPresupuesto,
			 aj.CodReintegro AS CodReintegro,
			 aj.cod_partida AS cod_partida,
			 aj.MontoDisponible AS MontoDisponible,
			 aj.MontoReintegro AS MontoReintegro 
        from 
		     pv_ReintegroPresupuestariodet aj
			 inner join pv_presupuesto pre on (pre.CodPresupuesto = aj.CodPresupuesto)
       where 
	         aj.CodReintegro='$CodAjuste' AND
			 aj.Organismo='$organismo' and 
			 aj.CodPresupuesto = '$CodPresupuesto'";
$qry=mysql_query($sql) or die ($sql.mysql_error()); //echo $sql;
$row=mysql_num_rows($qry); //echo $row;
if($row!=0){
 for($i=0; $i<$row; $i++){
  $field=mysql_fetch_array($qry);
  $spart="SELECT * FROM pv_partida 
                  WHERE cod_partida='".$field['cod_partida']."'";
  $qpart=mysql_query($spart) or die ($spart.mysql_error());
  $fpart=mysql_fetch_array($qpart);
  $m_disponible=number_format($field['MontoDisponible'],2,',','.');
  $m_ajustado=number_format($field['MontoReintegro'],2,',','.');
  echo "<tr class='trListaBody'>
	  <td align='center'>".$field['CodPresupuesto']."</td>
	  <td align='center'>".$field['CodReintegro']."</td>
	  <td align='center'>".$fpart['cod_partida']."</td>
	  <td>".$fpart['denominacion']."</td>
	  <td align='right'><b><input class='inputP' type='text' style='text-align:right' size='12' maxlength='12' id='partida_pre' value='$m_disponible' readonly/>Bs.F</td></b>
	  <td align='right'><b><input class='inputP' type='text' style='text-align:right' size='12' maxlength='12' name='partida_preDMA[]' value='$m_ajustado' />Bs.F
              <input name='CodPartida[]' type='hidden' value='".$field['cod_partida']."' />
            </td></b>
 	 </tr>";
 }
}
//------------------------------------------------------------------------------------------------------------
?>
</table>
</div></td></tr></table>
</div>
<center>
    
<input name="btguardar" type="submit" id="btguardar" value="Guardar Registro"/>
<input name="btcancelar" type="button" id="btcancelar" value="Cancelar" onclick="cargarPagina(this.form, '<?=$regresar?>.php?limit=0');"/>
</center>

</form>

</body>
</html>
<!-- VALIDACIONES CAMPOS OBLIGATORIOS  -->
<script language="javascript">

    
//function Alarma(formulario){
//   for (var i=0; formulario.elements.length; i++) {//SE RECORRE TODO LOS CAMPOS LOS MISMOS DEBEN CONTENER UNA CANTIDAD ASIGNADA
//	if (formulario.elements[i].className == "partida_preDMA"){
//	  //alert(formulario.elements[i].value);
//	  if(formulario.elements[i].value!='0,00'){
//	    alert('DEBE GUARDAR LOS REGISTROS ANTES DE INGRESAR OTRAS PARTIDAS...!');
//	    //alert('Debe introducir un monto.');
//		formulario.elements[i].focus();
//		//i=ormulario.elements.length;
//		return(false);
//		//break;
//	  }
// 	}
//  }
//}

function verificarDet(formulario) {
  for (var i=0; formulario.elements.length; i++) {//SE RECORRE TODO LOS CAMPOS LOS MISMOS DEBEN CONTENER UNA CANTIDAD ASIGNADA
	if (formulario.elements[i].className == "montoA"){
	  //alert(formulario.elements[i].value);
	  if((formulario.elements[i].value =='') || (formulario.elements[i].value =='0,00')){
	    alert('NO PUEDE DEJAR CAMPO(S) VACIO(s)...!');
	    //alert('Debe introducir un monto.');
		formulario.elements[i].focus();
		//i=ormulario.elements.length;
		return(false);
		//break;
	  }
 	}
   }
	var checkOK = "0123456789";
	var checkStr = formulario.elements.value;
	var allValid = true;
	for (i=0; i < formulario.elements[i].length; i++){
	  ch = checkStr.chartAt(i);
	  for (j=0 ; j < checkOK.length; j++)
		 if(ch == checkOK.charArt(j))
		 break;
		 if(j== checkOK.length){
		   allValid = false;
		   break;
		 }
     }
     if(!allValid){
	   alert("Debe introducir un monto, el mismo debe ser mayor que cero"); 
	   formulario.elements[i].focus(); 
	   return (false);
	 }
 return (true); 
} 

</script>
