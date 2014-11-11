<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include ("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="fscriptpresupuesto.js"></script>
<script type="text/javascript" language="javascript">
 function Mensaje(){
  alert('NO PUEDE MODIFICAR ESTE CAMPO �BOTON INHABILITADO!');
 }
 function Alarma(){
  alert('�BOTON INHABILITADO PARA ESTA OPERACION! ');
 }
</script>
</head>
<body>
<style type="text/css">
<!--
UNKNOWN {FONT-SIZE: small}
#header {FONT-SIZE: 93%; BACKGROUND: url(imagenes/bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal}
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
 <td class="titulo">Ver | Ajuste</td>
 <td align="right"><a class="cerrar" href="" onclick="window.close()">[Cerrar]</a></td>
</tr>
</table><hr width="100%" color="#333333" />
<? 
list($CodAjuste,$fechaAjuste,$organismo,$CodPresupuesto)= SPLIT('[|]',$_GET['registro']);
//echo $CodAjuste,$fechaAjuste,$organismo,$CodPresupuesto ;
list($ano, $mes, $dia) = split('[-]', $fechaAjuste);

//$actual=date("Y"); //echo" Registro= ".$_GET['registro'];
$SCON="SELECT aj.* 
         FROM pv_ajustepresupuestario aj, pv_presupuesto pre 
		WHERE aj.Organismo='".$_SESSION['ORGANISMO_ACTUAL']."' AND 
		      aj.CodAjuste='$CodAjuste' AND 
			  pre.EjercicioPpto='$ano' AND
			  aj.CodPresupuesto=pre.CodPresupuesto"; //echo $SCON;
$QCON=mysql_query($SCON) or die ($SCON.mysql_error());
$FCON=mysql_fetch_array($QCON);
?>
<form id="frmentrada" name="frmentrada">
<table width="850" align="center">
<tr><td>
<div id="header">
<ul>
<!-- CSS Tabs PESTA�AS OPCIONES DE PRESUPUESTO -->
<li><a onclick="document.getElementById('tab1').style.display='block'; document.getElementById('tab2').style.display='none';" href="#">Datos Generales</a></li>
<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block';" href="#">Detalle de Ajuste</a></li>
</ul>
</div>
  </td>
</tr>
</table>
<div id="tab1" style="display:block;">
<div style="width:850px" class="divFormCaption">Informaci&oacute;n de Presupuesto</div>
<table width="850" class="tblForm">
<tr>
	<td width="48"></td>
	<td width="90" class="tagForm">Organismo:</td>
	<td width="300">
	  <?
	    $sqlOrg="SELECT CodOrganismo,Organismo FROM mastorganismos WHERE CodOrganismo='".$FCON['Organismo']."'";
		$qryOrg=mysql_query($sqlOrg) or die (($sqlOrg).mysql_error());
		$fOrg=mysql_fetch_array($qryOrg);
	  ?>
	  <input name="organismo" id="organismo" type="tex" size="60" value="<?=$fOrg['Organismo'];?>" readonly/>
		<!--<select name="organismo" id="organismo" class="selectBig">
		<?php 
		// segundo bloque php //* Conectamos a los datos *//
		include "conexion.php";
		$sql="SELECT CodOrganismo,Organismo FROM mastorganismos WHERE 1";
		$rs=mysql_query($sql);
		while($reg=mysql_fetch_assoc($rs)){
		$codOrganismo=$reg['CodOrganismo'];// Codigo del orgnismo
		$organismo=$reg['Organismo'];// Descripcion del Organismo
		   echo "<option value=$codOrganismo>$organismo</option>";
		}
		?></select>--></td>
</tr>
<tr><td height="4"></td></tr>
</table>
<table width="850" class="tblForm" border="0">
<tr><td height="2"></td></tr>
<tr>
 <td width="112"></td>
 <td width="156" align="right">Nro. Oficio o Resolucion:</td>
 <? 
   if($FCON['FechaResolucion']!='0000-00-00'){list($a, $m, $d)=SPLIT( '[/.-]', $FCON['FechaResolucion']); $fres=$d.'-'.$m.'-'.$a;}
   if($fres=='00-00-0000'){$fres='';}
 ?>
 <td width="110"><input name="nresolucion" id="nresolucion" type="text" size="18" value="<?=$FCON['NumResolucion'];?>" style="text-align:right" readonly/>*</td>
 <td width="114" align="right">F. Oficio o Resoluci&oacute;n:</td>
 <td width="151"><input name="fresolucion" id="fresolucion" type="text" size="8" value="<?=$fres?>" style="text-align:right" readonly/>*<i>(dd-mm-aaaa)</i></td>
</tr>
<tr>
    <?
	if($FCON['FechaGaceta']!='0000-00-00'){list($a, $m, $d)=SPLIT( '[/.-]', $FCON['FechaGaceta']); $fgaceta=$d.'-'.$m.'-'.$a;}
	if($fgaceta=='00-00-0000'){$fgaceta='';}
    ?>
    <td width="112"></td>
	<td width="156" align="right">Nro. Gaceta:</td>
	<td width="110"><input name="gaceta" id="gaceta" type="text" size="18" value="<?=$FCON['NumGaceta'];?>" style="text-align:right" readonly/>*</td>
	<td width="84" align="right">F. Gaceta:</td>
	<td width="181"><input name="fgaceta" id="fgaceta" type="text" size="8" value="<?=$fgaceta?>" style="text-align:right" readonly/>*<i>(dd-mm-aaaa)</i></td>
	<td colspan="2" width="179"></td>
</tr>
<tr><td height="2"></td></tr>
</table>
<table width="850" class="tblForm">
<tr><td height="2"></td></tr>
<tr>
 <td width="122"></td>
 <td width="143" class="tagForm">Nro. Presupuesto:</td>
 <td width="106"><input id="npresupuesto" name="npresupuesto" type="text" size="8" value="<?=$FCON['CodPresupuesto'];?>" style="text-align:right" readonly/>
 </td><?
   if($FCON['Estado']==PR)$st=Pendiente;
   elseif($FCON['Estado']==AP)$st=Aprobado; 
   else $st=Anulado;
 ?>
 <td width="95" class="tagForm">Estado:</td>
 <td width="156"><input type="text" id="status" name="status" size="11" value="<?=$st?>"  readonly/></td>
 <td width="200"></td>
</tr>
<tr>
 <td width="122"></td><?
   if($FCON[Estado]==PR)$st=Pendiente; elseif($FCON[Estado]==AP)$st=Aprobado; else $st=Anulado;
   if($FCON[TipoAjuste]==IN){$tipoAjuste=Incremento;}else{ $tipoAjuste=Disminuci�n;}
 ?>
<td class="tagForm">Tipo Ajuste:</td>
  <td><select id="tAjuste" name="tAjuste">
      <? if($FCON['TipoAjuste']==IN) echo"<option selected>Incremento</option>";
	     elseif($FCON['TipoAjuste']==DI)echo"<option selected>Disminuci�n</option>";?>
     </select>*</td>
<td class="tagForm">F. Ajuste:</td><? $fcreacion=date("d-m-Y"); $fperiodo=date("Y-m");?>
 <td><input type="text" id="fAjuste" name="fAjuste" size="8" maxlength="8" value="<?=$dia.'-'.$mes.'-'.$ano;?>" readonly/></td>
</tr>
<tr>
   <td width="122"></td>
   <td class="tagForm">Motivo:</td>
   <td colspan="2"><select id="montivoAjuste" name="motivoAjuste">
             <?
             $s_motajus = "select * from mastmiscelaneosdet where CodMaestro = 'MOTIVOAJUS'";
			 $q_motajus = mysql_query($s_motajus) or die ($s_motajus.mysql_error());
			 $r_motajus = mysql_num_rows($q_motajus);
			 
			 for($b=0; $b<$r_motajus; $b++){
			    $f_motajus = mysql_fetch_array($q_motajus);
				if($FCON['MotivoAjuste']==$f_motajus['CodDetalle'])echo"<option value='".$f_motajus['CodDetalle']."' selected>".$f_motajus['Descripcion']."</option>";
			 }
			 ?>
             </select>*</td>
</tr>
<tr><td height="2"></td></tr>
</table>
<!--<table width="850" class="tblForm">
<tr><td height="2"></td></tr>
<tr>
<td width="126"></td>
 <td width="143" class="tagForm">Nro. Presupuesto:</td>
 <td width="96"><input id="npresupuesto" name="npresupuesto" type="text" size="8" value="<?=$FCON['CodPresupuesto'];?>" style="text-align:right" readonly/>
     <!--<input name="bt_examinar" id="bt_examinar" type="button" value="..." onclick="Mensaje();"/>-->
 <!--</td>
 <td width="84" class="tagForm">Estado:</td>
 <?
   if($FCON['Estado']==PR)$st=Pendiente;
   elseif($FCON['Estado']==AP)$st=Aprobado; 
   else $st=Anulado;
   
   if($FCON['TipoAjuste']==IN){$tipoAjuste=Incremento;}else{ $tipoAjuste=Disminuci�n;}
 ?>
 <td width="123"><input type="text" id="status" name="status" size="11" value="<?=$st;?>"  readonly/></td>
 <td width="250"></td>
</tr>
<tr>
 <td width="126"></td>
 <td class="tagForm">F. Ajuste:</td>
  <? 
   if($FCON['FechaAjuste']!='0000-00-00'){list($a, $m, $d)=SPLIT( '[/.-]', $FCON['FechaAjuste']); $fajuste=$d.'-'.$m.'-'.$a;}
   if($fajuste=='00-00-0000'){$fajuste='';}
 ?>
 <td><input type="text" id="fAjuste" name="fAjuste" size="8" maxlength="8" value="<?=$fajuste?>" readonly/></td>
 <td class="tagForm">Tipo Ajuste:</td>
  <td><input name="tAjuste" id="tAjuste" type="text" size="11" value="<?=$tipoAjuste;?>" readonly/>*</td>
</tr>
<tr><td height="2"></td></tr>
</table>-->
<div style="width:850px" class="divFormCaption">Duraci&oacute;n del Ajuste</div>
<table class="tblForm" width="850">
<tr><td height="2"></td></tr>
<tr>
  <td width="50"></td>
  <td width="70" align="right">Per&iacute;odo:</td>
  <td width="180"><input id="fPeriodo" name="fPeriodo" type="text" size="8" maxlength="10" value="<?=$FCON['Periodo'];?>" readonly/>*<i>(mm-aaaa)</i></td>
</tr>
<tr><td height="2"></td></tr>
</table>
<div style="width:850px" class="divFormCaption">Descripci&oacute;n de Motivo</div>
<table class="tblForm" width="850">
<tr><td height="2"></td></tr>
<tr>
  <td width="195"></td>
  <td width="50">Descripci&oacute;n:</td>
</tr>
<tr>
  <td colspan="1"></td>
  <td width="580"><textarea name="descripcion" id="descripcion" rows="5" cols="80" readonly><? echo $FCON['Descripcion'];?></textarea>*</td>
</tr>
<tr><td height="2"></td></tr>
</table>
<table width="850" class="tblForm">
<tr><td width="45"></td>
      <? $s_pre = "select 
                         NomCompleto
				    from 
					     mastpersonas 
				   where 
				         CodPersona = '".$FCON['PreparadoPor']."'"; //echo $s_pre;
      $q_pre = mysql_query($s_pre) or die ($s_pre.mysql_error());
	  $r_pre = mysql_num_rows($q_pre);
	  if($r_pre!=0) $f_pre=mysql_fetch_array($q_pre);
   ?>
   <td width="245" class="tagForm">Preparado por:</td>
   <td width="520"><input name="prepor" id="prepor" type="text" size="60" value="<?=$f_pre['0'];?>" readonly/></td>
</tr>
<tr><td></td>
   <td class="tagForm">Aprobado por:</td>
    <? $s_aprob = "select 
                         NomCompleto
				    from 
					     mastpersonas
				   where 
				         CodPersona = '".$FCON['AprobadoPor']."'";
      $q_aprob = mysql_query($s_aprob) or die ($s_aprob.mysql_error());
	  $f_aprob = mysql_fetch_array($q_aprob); 
   ?>
   <td><input name="codempleado" type="hidden" id="codempleado" value="" />
	       <input name="nomempleado" id="nomempleado" type="text" size="60" value="<?=$f_aprob['0'];?>" readonly/></td>
</tr>
<tr><td></td>
   <td class="tagForm">&Uacute;ltima Modif.:</td>
   <td colspan="1">
   <? 
     if($FCON['UltimaFechaModif']!='0000-00-00 00:00:00'){
	   $U_FECHA=$FCON['UltimaFechaModif']; $U_USUARIO=$FCON['UltimoUsuario'];
	 }else{$U_FECHA=''; $U_USUARIO='';}
   ?>
	<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$U_USUARIO;?>" readonly />
	<input name="ult_fecha" type="text" id="ult_fecha" size="22" value="<?=$U_FECHA;?>" readonly /></td>
</tr>
<tr><td height="5"></td></tr>
</table>
</div>

<div id="tab2" style="display:none;">
<div style="width:850px" class="divFormCaption">Detalle de Ajuste</div>
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
 <th width="125" scope="col">MontoAjustado</th>
</tr>
<?php
//------------------------------------------------------------------------------------------------------------
$year_actual = date("Y");
$sql="SELECT 
             aj.CodPresupuesto AS CodPresupuesto,
			 aj.CodAjuste AS CodAjuste,
			 aj.cod_partida AS cod_partida,
			 aj.MontoDisponible AS MontoDisponible,
			 aj.MontoAjuste AS MontoAjuste 
        FROM 
		     pv_ajustepresupuestariodet aj, pv_presupuesto pre
       WHERE 
	         aj.CodAjuste='$CodAjuste' AND
			 aj.Organismo='".$_SESSION['ORGANISMO_ACTUAL']."' AND
			 pre.CodPresupuesto=aj.CodPresupuesto AND
			 pre.EjercicioPpto='$ano'";
$qry=mysql_query($sql) or die ($sql.mysql_error()); //echo $sql;
$row=mysql_num_rows($qry);
if($row!=0){
 for($i; $i<$row; $i++){
  $field=mysql_fetch_array($qry);
  $spart="SELECT * FROM pv_partida 
                  WHERE cod_partida='".$field['cod_partida']."'";
  $qpart=mysql_query($spart) or die ($spart.mysql_error());
  $fpart=mysql_fetch_array($qpart);
  $m_disponible=number_format($field['MontoDisponible'],2,',','.');
  $m_ajustado=number_format($field['MontoAjuste'],2,',','.');
  echo "<tr class='trListaBody'>
	  <td align='center'>".$field['CodPresupuesto']."</td>
	  <td align='center'>".$field['CodAjuste']."</td>
	  <td align='center'>".$fpart['cod_partida']."</td>
	  <td>".$fpart['denominacion']."</td>
	  <td align='right'><b><input class='inputP' type='text' style='text-align:right' size='12' maxlength='12' id='partida_pre' value='$m_disponible' readonly/>Bs.F</td></b>
	  <td align='right'><b><input class='inputP' type='text' style='text-align:right' size='12' maxlength='12' id='partida_preDMA' value='$m_ajustado' readonly/>Bs.F</td></b>
 	 </tr>";
 }
}
?>
<tr class="trListaBody">
 <th width="85" scope="col"></th>
 <th width="80" scope="col"></th>
 <th width="80" scope="col"></th>
 <th scope="300"></th>
 <th width="125" scope="col" align="right">Total =</th>
 <th width="125" scope="col" align='right'>
     <input class='inputP' type='text' style='text-align:right' size='12' maxlength='12' value='<?=  number_format($FCON['TotalAjuste'],2,'.',',');?>' readonly/>Bs.F
 </th>
</tr>
</table>
</div>
<center>
</center>
</form>
</body>
</html>
