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
 <td class="titulo">Aprobar | Ajuste</td>
 <td align="right"><a class="cerrar"; href="../presupuesto/framemain.php">[Cerrar]</a></td>
</tr>
</table><hr width="100%" color="#333333" />
<?php 
list($CAjuste, $FechaAjuste, $CodOrganismo, $CodPresupuesto) = split('[|]', $_GET['registro']);

$actual=date("Y"); //echo" Registro= ".$_GET['registro'];
//$actual = "2012";
$SCON="SELECT aj.* 
         FROM pv_ajustepresupuestario aj, pv_presupuesto pre 
		WHERE aj.Organismo='".$_SESSION['ORGANISMO_ACTUAL']."' AND 
		      aj.CodAjuste='$CAjuste' AND 
			  pre.EjercicioPpto='$actual' AND
			  aj.CodPresupuesto=pre.CodPresupuesto";
$QCON=mysql_query($SCON) or die ($SCON.mysql_error());
$FCON=mysql_fetch_array($QCON);

 /*$sqlAjuste="SELECT MAX(CodPresupuesto) FROM  pv_ajustepresupuestario";
 $qryAjuste=mysql_query($sqlAjuste) or die (($sqlAjuste).mysql_error());
 $field=mysql_fetch_array($qryAjuste);
 $sqlA="SELECT * FROM pv_ajustepresupuestario WHERE CodAjuste='".$field['0']."'";
 $qryA=mysql_query($sqlA) or die (($sqlA).mysql_error());
 $fieldA=mysql_fetch_array($qryA);*/
?>
<form id="frmentrada" name="frmentrada" action="presupuesto_ajusteaprobar.php?volver=0&accion=GuardarAprobarAjuste" method="post" >
<? include "gpresupuesto.php"; 
   echo"<input type='hidden' name='registro' id='registro' value='".$_GET['registro']."'/>";?>
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
      <input name="codorganismo" id="codorganismo" type="hidden" value="<?=$fOrg['CodOrganismo'];?>" readonly/>
		<!--<select name="organismo" id="organismo" class="selectBig">
		<?php /*
		include "conexion.php";
		$sql="SELECT CodOrganismo,Organismo FROM mastorganismos WHERE 1";
		$rs=mysql_query($sql);
		while($reg=mysql_fetch_assoc($rs)){
		$codOrganismo=$reg['CodOrganismo'];// Codigo del orgnismo
		$organismo=$reg['Organismo'];// Descripcion del Organismo
		   echo "<option value='$codOrganismo'>$organismo</option>";
		}*/
		?></select>--></td>
</tr>
<tr><td height="4"></td></tr>
</table>
<table width="850" class="tblForm" border="0">
<tr><td height="2"></td></tr>
<tr>
    <?
	if($FCON['FechaGaceta']!='0000-00-00'){list($a, $m, $d)=SPLIT( '[/.-]', $FCON['FechaGaceta']); $fgaceta=$d.'-'.$m.'-'.$a;}
    if($FCON['FechaDecreto']!='0000-00-00'){list($a, $m, $d)=SPLIT( '[/.-]', $FCON['FechaDecreto']); $fdecreto=$d.'-'.$m.'-'.$a;}
    list($a, $m, $d)=SPLIT( '[/.-]', $FCON['FechaAjuste']); $fajuste=$d.'-'.$m.'-'.$a;
    ?>
    <td width="190"></td>
	<td width="75" align="right">Nro. Gaceta:</td>
	<td width="70"><input name="gaceta" id="gaceta" type="text" size="8" value="<?=$FCON['NumeroGaceta'];?>" readonly/>*</td>
	<td width="64" align="right">F. Gaceta:</td>
	<td width="150"><input name="fgaceta" id="fgaceta" type="text" size="8" value="<?=$fgaceta?>" readonly/>*<i>(dd-mm-aaaa)</i></td>
	<td colspan="2" width="200"></td>
</tr>
<tr>
 <td width="190"></td>
 <td width="75" align="right">Nro. Decreto:</td>
 <td width="70"><input name="decreto" id="decreto" type="text" size="8" value="<?=$FCON['NumeroDecreto'];?>" readonly/>*</td>
 <td width="64" align="right">F. Decreto:</td>
 <td width="150"><input name="fdecreto" id="fdecreto" type="text" size="8" value="<?=$fdecreto?>" readonly/>*<i>(dd-mm-aaaa)</i></td>
</tr>
<tr><td height="2"></td></tr>
</table>
<table width="850" class="tblForm">
<tr><td height="2"></td></tr>
<tr>
<td width="145"></td>
 <td class="tagForm">Nro. Presupuesto:</td>
 <td><input id="npresupuesto" name="npresupuesto" type="text" size="8" value="<?=$FCON['CodPresupuesto'];?>" readonly/>
     <input name="bt_examinar" id="bt_examinar" type="button" value="..." onclick="Mensaje();"/>*
 </td>
 <td class="tagForm">Estado:</td>
 <?
   if($FCON[Estado]==PR){$st=Preparado;}if($FCON[Estado]==AP){$st=Aprobado;}
   if($FCON[TipoAjuste]==IN){$tipoAjuste=Incremento;}else{ $tipoAjuste=Disminuci�n;}
 ?>
 <td><input type="text" id="status" name="status" size="12" value="<?=$st?>"  readonly/></td>
 <td width="250"></td>
</tr>
<tr>
 <td width="33"></td>
 <td class="tagForm">F. Ajuste:</td>
 <td><input type="text" id="fAjuste" name="fAjuste" size="8" maxlength="8" value="<?=$fajuste?>" readonly/></td>
 <td class="tagForm">Tipo Ajuste:</td>
  <td><input name="tAjuste" id="tAjuste" type="hidden" value="<?=$FCON['TipoAjuste'];?>"/><input name="Ajuste" id="Ajuste" type="text" size="11" value="<?=$tipoAjuste;?>" readonly/>*</td>
</tr>
<tr><td height="2"></td></tr>
</table>
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
   <td width="245" class="tagForm">Preparado por:</td><? $scon01 = "select * from mastpersonas where CodPersona = '".$FCON['PreparadoPor']."'";
   													     $qcon01 = mysql_query($scon01) or die ($scon01.mysql_error());
														 $rcon01 = mysql_num_rows($qcon01);
														 
														 if($rcon01!=0) $fcon01 = mysql_fetch_array($qcon01);
													  ?>
   <td width="520"><input name="prepor" id="prepor" type="text" size="60" value="<?=$fcon01['NomCompleto']?>" readonly/></td>
</tr>
<tr><td></td>
   <td class="tagForm">Aprobado por:</td><? $scon02 = "select * from mastpersonas where CodPersona = '".$FCON['AprobadoPor']."'";
										    $qcon02 = mysql_query($scon02) or die ($scon02.mysql_error());
										    $rcon02 = mysql_num_rows($qcon02);
										 
										    if($rcon02!=0) $fcon02 = mysql_fetch_array($qcon02);
										 ?>
   <td><input name="codempleado" type="hidden" id="codempleado" value="" />
	       <input name="nomempleado" id="nomempleado" type="text" size="60" value="<?=$fcon02['NomCompleto'];?>" readonly/></td>
</tr>
<tr><td></td>
   <td class="tagForm">&Uacute;ltima Modif.:</td>
   <td colspan="1"><? $fCompleta=date("d-m-Y H:m:s");  ?>
	<input name="ult_usuario" type="text"  id="ult_usuario" size="30" value="<?=$FCON['UltimoUsuario'];?>" readonly />
	<input name="ult_fecha" type="text" id="ult_fecha" size="22" value="<?=$FCON['UltimaFechaModif'];?>" readonly /></td>
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
 <th width="80" scope="col"># Partida</th>
 <th scope="300">Denominaci&oacute;n</th>
 <th width="125" scope="col">MontoAsignado</th>
 <th width="125" scope="col">MontoAjustado</th>
</tr>
<?
//------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------
$ContGen=0;$ContPartida=0;
$sql="SELECT pdet.* 
        FROM pv_presupuestodet pdet, pv_presupuesto pre
       WHERE pre.CodPresupuesto=pdet.CodPresupuesto AND 
	         pre.Organismo=pdet.Organismo AND 
			 pre.EjercicioPpto='$actual'
   ORDER BY  cod_partida";
$qry=mysql_query($sql) or die (($sql).mysql_error());
$row=mysql_num_rows($qry);
if($row!=0){
 for($i=0; $i<$row; $i++){
    $field=mysql_fetch_array($qry);
   ///  ORDENA PARTIDAS ////  ORDENA PARTIDAS POR TIPO=DETALLE "D"
   //// **** Obtengo Partidas Tipo "T" 301-00-00-00	**** ////  
   if(($field['partida']!=00) and (($cont1==0) or ($pCapturada!=$field['partida']))){
    $sqlP="SELECT * FROM pv_partida 
				   WHERE partida1='".$field['partida']."' AND
						 cod_tipocuenta='".$field['tipocuenta']."' AND
						 tipo='T' AND 
						 generica='00'";
    $qryP=mysql_query($sqlP) or die ($sqlP.mysql_error());
    if(mysql_num_rows($qryP)!=0){
	 $fieldP=mysql_fetch_array($qryP);
	 $montoP=0; $cont1=0;$monto1=0;
	 $sqldet="SELECT * FROM pv_presupuestodet 
					  WHERE partida='".$fieldP['partida1']."' AND
							tipocuenta='".$fieldP['cod_tipocuenta']."' AND
							CodPresupuesto='".$field['CodPresupuesto']."'";
	 $qrydet=mysql_query($sqldet);
	 $rwd=mysql_num_rows($qrydet);
	 for($a=0; $a<$rwd; $a++){
	   $fdet=mysql_fetch_array($qrydet);
	   $cont1 = $cont1 + 1;
	   $montoP = $montoP + $fdet['MontoAprobado'];
	   ///**** CONSULTO PARA OBTENER LAS PARTIDAS AFECTADAS POR AJUSTES
	   $SAJUSTE="SELECT * FROM pv_ajustepresupuestariodet 
	                    WHERE CodAjuste='$CAjuste' AND
					          CodPresupuesto='".$field['CodPresupuesto']."' AND
							  Organismo='".$field['Organismo']."' AND 
							  cod_partida='".$fdet['cod_partida']."'";
	   $QAJUSTE=mysql_query($SAJUSTE) or die ($SAJUSTE.mysql_error()) ;
	   $RAJUSTE=mysql_num_rows($QAJUSTE);
	   if($RAJUSTE!=0){
	     $FAJUSTE=mysql_fetch_array($QAJUSTE);
	     $monto1 = $monto1 + $FAJUSTE['MontoAjuste'];
	   }	
	 }
	 $monto1 = number_format($monto1,2,',','.'); /// ** Monto para la opcion de Partida Ajuste
	 $montoPar = number_format($montoP,2,',','.'); /// ** Monto para la opcion de Partida Presupuesto
	 $cont1= $cont1 + 1;
	 $codigo_partida = $fieldP[cod_partida];
	 $pCapturada = $fieldP[partida1];
	 echo "<tr class='trListaBody6'>
	  <td align='center'>".$field['CodPresupuesto']."</td>
	  <td align='center'>".$fieldP['cod_partida']."</td>
	  <td>".$fieldP['denominacion']."</td>
	  <td align='right'><b><input class='inputP' style='text-align:right' type='text' size='12' maxlength='12' id='partida_pre' value='$montoPar' readonly/>Bs.F</td></b>
	  <td align='right'><b><input class='inputP' style='text-align:right' type='text' size='12' maxlength='12' id='partida_aj' value='$monto1' readonly/>Bs.F</td></b>     
	 </tr>";
	    }
	  }
  //////////////////////////////////////////////////////////////////////////////////////
  //// **** Obtengo Partidas Tipo "T" 301-01-00-00	**** ////
  if(($field['generica']!=00) and (($cont2==0) or ($gCapturada!=$field['generica']) or ($pCapturada2!=$field['partida']))){
	$sqlP="SELECT * FROM pv_partida 
				   WHERE partida1='".$field['partida']."' AND 
						 cod_tipocuenta='".$field['tipocuenta']."' AND 
						 tipo='T' AND 
						 generica='".$field['generica']."' AND 
						 especifica='00'";
	 $qryP=mysql_query($sqlP) or die ($sqlP.mysql_error());
	 $rw=mysql_num_rows($qryP); 
	 if($rw!=0){
	   $fieldP=mysql_fetch_array($qryP);
	   $cont2=0; $montoG=0; $montoAgen=0;
	   $sqldet="SELECT * FROM pv_presupuestodet 
						WHERE partida='".$fieldP['partida1']."' AND 
							  generica='".$fieldP['generica']."' AND 
							  tipocuenta='".$fieldP['cod_tipocuenta']."' AND
							  CodPresupuesto='".$field['CodPresupuesto']."'";
      $qrydet=mysql_query($sqldet);
      $rwdet=mysql_num_rows($qrydet);
      for($b=0; $b<$rwdet; $b++){
	   $fdet=mysql_fetch_array($qrydet);
	   $cont2= $cont2 + 1;
	   $montoG= $montoG + $fdet['MontoAprobado'];
	   ///**** CONSULTO PARA OBTENER LAS PARTIDAS AFECTADAS POR AJUSTES
	   $SAJUSTE="SELECT * FROM pv_ajustepresupuestariodet 
	                    WHERE CodAjuste='$CAjuste' AND
					          CodPresupuesto='".$field['CodPresupuesto']."' AND
							  Organismo='".$field['Organismo']."' AND 
							  cod_partida='".$fdet['cod_partida']."'";
	   $QAJUSTE=mysql_query($SAJUSTE) or die ($SAJUSTE.mysql_error()) ;
	   $RAJUSTE=mysql_num_rows($QAJUSTE);
	   if($RAJUSTE!=0){
	     $FAJUSTE=mysql_fetch_array($QAJUSTE);
	     $montoAgen = $montoAgen + $FAJUSTE['MontoAjuste'];
	   }	
      } 
	  $montoAgen=number_format($montoAgen,2,',','.');
	  $montoGen=number_format($montoG,2,',','.');
      $codigo_generica = $fieldP[cod_partida];
      $pCapturada2 = $fieldP[partida1];
	  $gCapturada = $fieldP[generica];
	   echo "<tr class='trListaBody5'>
		 <td align='center'>".$field['CodPresupuesto']."</td>
	     <td align='center'>".$fieldP['cod_partida']."</td>
		 <td>".$fieldP['denominacion']."</td>
		 <td align='right'><b><input type='text' style='text-align:right' class='inputG' size='12' maxlength='12' id='generica_pre' name='' value='$montoGen' readonly/>Bs.F</td>
		 <td align='right'><b><input type='text' style='text-align:right' class='inputG' size='12' maxlength='12' id='generica_aj' name='' value='$montoAgen' readonly/>Bs.F</td>       
	   </tr>";
	  }
   }
	   //////////////////////////////////////////////////////////////////////////////////////
	   //// **** Obtengo Partidas Tipo "D" 301-01-01-01	**** ////
   if($field['partida']!=00){
    //$cont=1;
	$s="SELECT cod_partida,denominacion FROM pv_partida WHERE cod_partida='".$field['cod_partida']."'";
	$q=mysql_query($s) or die ($s.mysql_error());
	$f=mysql_fetch_array($q);
    $monto = $field['MontoAprobado'];
	$total = $monto + $total;
	$totalT=number_format($total,2,',','.');
	$montoD=number_format($monto,2,',','.');
    $codigo_detalle = $field['cod_partida'];
	////////////////////////////////////////////////////////////////////////////
	$SAJUSTE="SELECT * FROM pv_ajustepresupuestariodet 
	                    WHERE CodAjuste='$CAjuste' AND
					          CodPresupuesto='".$field['CodPresupuesto']."' AND
							  Organismo='".$field['Organismo']."' AND 
							  cod_partida='".$field['cod_partida']."'";
	 $QAJUSTE=mysql_query($SAJUSTE) or die ($SAJUSTE.mysql_error()) ;
	 $RAJUSTE=mysql_num_rows($QAJUSTE);
	 if($RAJUSTE!=0){
	   $FAJUSTE=mysql_fetch_array($QAJUSTE);
	   $montoAj  = $FAJUSTE['MontoAjuste'];
	 }else{
	   $montoAj  = '';
	 }
	 $T_Ajuste = $T_Ajuste + $montoAj;
	 $Total_Ajuste = number_format($T_Ajuste,2,',','.');
	////////////////////////////////////////////////////////////////////////////
    echo "<tr class='trListaBody' onclick='mClk(this,\"registro\");' id='".$field['cod_partida']."'>
		<td align='center'>".$field['CodPresupuesto']."</td>
		<td align='center'>".$field['cod_partida']."</td>
		<td>".$f['denominacion']."</td>
		<td align='right'><input type='text' style='text-align:right' size='11' maxlength='12' id='pre' value='$montoD' readonly/>Bs.F</td>  
		<td align='right'><input type='text' style='text-align:right' size='11' maxlegth='12' id='p_ajuste' value='$montoAj' readonly/>Bs.F</td>       
	   </tr>";
	   }
}}echo"<tr><td colspan='2'></td>
               <td align='right'><b>Total:</b></td>
			   <td align='center' class='trListaBody'>
			      <input type='hidden' id='total' name='total' size='15' value='$total'/>
			      <input type='text' id='totalAnt' name='totalAnt' size='13' value='$totalT' readonly/>
			        <input type='hidden' class='inputT' id='totalAnt' name='totalAnt' size='13' value='$totalT' readonly/> Bs.F</td>
			   <td align='center' class='trListaBody'><input type='text' size='13' id='Total_Ajuste' value='$Total_Ajuste'/>Bs.F</td>
		   </tr>";
?>
</table>
</div>


<center>
<input name="btguardar" type="submit" id="btguardar" value="Aceptar"/>
<input name="btcancelar" type="button" id="btcancelar" value="Cancelar" onclick="cargarPagina(this.form,'presupuesto_ajusteaprobar.php?limit=0&volver=0');"/>
<input type="hidden" name="filas" id="filas" value="<?=$rows?>" />
</center>

</form>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
