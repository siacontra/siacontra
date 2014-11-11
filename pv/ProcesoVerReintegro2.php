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
 <td class="titulo">Ver | Reintegro</td>
 <td align="right"><a class="cerrar" onclick="window.close()">[Cerrar]</a></td>
</tr>
</table><hr width="100%" color="#333333" />
<?php 
//list($CodAjuste,$fechaAjuste,$organismo,$CodPresupuesto)= SPLIT('[|]',$_GET['registro']);
//echo $CodAjuste,$fechaAjuste,$organismo,$CodPresupuesto ;
list($CodAjuste, $TAjuste, $CPresupuesto) = split('[-]', $_GET['registro']);
$actual=date("Y"); //echo" Registro= ".$_GET['registro'];
$SCON="SELECT aj.* 
         FROM pv_ReintegroPresupuestario aj, pv_presupuesto pre 
		WHERE aj.Organismo='".$_SESSION['ORGANISMO_ACTUAL']."' AND 
		      aj.CodReintegro='$CodAjuste' AND 
			  pre.EjercicioPpto='$actual' AND
			  aj.CodPresupuesto=pre.CodPresupuesto";
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
<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block';" href="#">Detalle del Reintegro</a></li>
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
 <td width="156" align="right">Nro. Resolucion:</td>
 <? 
   if($FCON['FechaResolucion']!='0000-00-00'){list($a, $m, $d)=SPLIT( '[/.-]', $FCON['FechaResolucion']); $fres=$d.'-'.$m.'-'.$a;}
   if($fres=='00-00-0000'){$fres='';}
 ?>
 <td width="110"><input name="nresolucion" id="nresolucion" type="text" size="18" value="<?=$FCON['NumResolucion'];?>" style="text-align:right" readonly/>*</td>
 <td width="84" align="right">F. Resoluci&oacute;n:</td>
 <td width="181"><input name="fresolucion" id="fresolucion" type="text" size="8" value="<?=$fres?>" style="text-align:right" readonly/>*<i>(dd-mm-aaaa)</i></td>
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
 </td>
 <td width="95" class="tagForm">Estado:</td> <? if($FCON['Estado']='AN') $estado = 'Anulado';
 												if($FCON['Estado']='PR') $estado = 'Preparado';
												if($FCON['Estado']='AP') $estado = 'Aprobado';?>
 <td width="156"><input type="text" id="status" name="status" size="11" value="<?=$estado;?>"  readonly/></td>
 <td width="200"></td>
</tr>
<tr>
 <td width="122"></td><?
   if($FCON[Estado]==PE)$st=Pendiente; elseif($FCON[Estado]==AP)$st=Aprobado; else $st=Anulado;
   if($FCON[TipoAjuste]==IN){$tipoAjuste=Incremento;}else{ $tipoAjuste=Disminuci�n;}
 ?>
<td><input type="hidden" id="tAjuste" value="IN" /></td>
<td class="tagForm">F. Reintegro:</td><? $fcreacion=date("d-m-Y"); $fperiodo=date("Y-m");?>
 <td><input type="text" id="fAjuste" name="fAjuste" size="8" maxlength="8" value="<?=$fcreacion?>" readonly/></td>
</tr>

<tr><td height="2"></td></tr>
</table>

<div style="width:850px" class="divFormCaption">Duraci&oacute;n del Reintegro</div>
<table class="tblForm" width="850">
<tr><td height="2"></td></tr>
<tr>
  <td width="50"></td>
  <td width="70" align="right">Per&iacute;odo:</td>
  <td width="180"><input id="fPeriodo" name="fPeriodo" type="text" size="8" maxlength="10" value="<?=$FCON['Periodo'];?>" readonly/>*<i>(mm-aaaa)</i></td>
</tr>
<tr><td height="2"></td></tr>
</table>
<div style="width:850px" class="divFormCaption">Descripci&oacute;n del Reintegro</div>
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
				         CodPersona = '".$FCON['PreparadoPor']."'";
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
 <th width="80" scope="col"># Reintegro</th>
 <th width="80" scope="col"># Partida</th>
 <th scope="300">Denominaci&oacute;n</th>
 <th width="125" scope="col">MontoDMA</th>
 <th width="125" scope="col">MontoReintegro</th>
</tr>
<?php
//------------------------------------------------------------------------------------------------------------
$year_actual = date("Y");
$sql="SELECT 
             aj.CodPresupuesto AS CodPresupuesto,
			 aj.CodReintegro AS CodReintegro,
			 aj.cod_partida AS cod_partida,
			 aj.MontoDisponible AS MontoDisponible,
			 aj.MontoReintegro AS MontoReintegro 
        FROM 
		     pv_ReintegroPresupuestariodet aj, pv_presupuesto pre
       WHERE 
	         aj.CodReintegro='$CodAjuste' AND
			 aj.Organismo='".$_SESSION['ORGANISMO_ACTUAL']."' AND
			 pre.CodPresupuesto=aj.CodPresupuesto AND
			 pre.EjercicioPpto='$year_actual'";
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
  $m_ajustado=number_format($field['MontoReintegro'],2,',','.');
  echo "<tr class='trListaBody'>
	  <td align='center'>".$field['CodPresupuesto']."</td>
	  <td align='center'>".$field['CodReintegro']."</td>
	  <td align='center'>".$fpart['cod_partida']."</td>
	  <td>".$fpart['denominacion']."</td>
	  <td align='right'><b><input class='inputP' type='text' style='text-align:right' size='12' maxlength='12' id='partida_pre' value='$m_disponible' readonly/>Bs.F</td></b>
	  <td align='right'><b><input class='inputP' type='text' style='text-align:right' size='12' maxlength='12' id='partida_preDMA' value='$m_ajustado' readonly/>Bs.F</td></b>
 	 </tr>";
 }
}
//------------------------------------------------------------------------------------------------------------
/*$ContGen=0;$ContPartida=0;
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
	 $montoP=0; $cont1=0;$monto1=0;$montoParDMA=0;
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
	   $SAJUSTE="SELECT * FROM pv_ReintegroPresupuestariodet 
	                    WHERE CodAjuste='".$_GET['registro']."' AND
					          CodPresupuesto='".$field['CodPresupuesto']."' AND
							  Organismo='".$field['Organismo']."' AND 
							  cod_partida='".$fdet['cod_partida']."'";
	   $QAJUSTE=mysql_query($SAJUSTE) or die ($SAJUSTE.mysql_error()) ;
	   $RAJUSTE=mysql_num_rows($QAJUSTE);
	   if($RAJUSTE!=0){
	     $FAJUSTE=mysql_fetch_array($QAJUSTE);
	     $monto1 = $monto1 + $FAJUSTE['MontoAjuste'];
		 //$montoParMDA = $FAJUSTE['MontoDMA'] + $montoParMDA; echo"MontoDMA=".$FAJUSTE['MontoDMA'];
		 if($fdet['MontoAprobado']<=$FAJUSTE['MontoDMA']){  //echo"MontoAprobado=".$fdet['MontoAprobado'];
		   $montoParDMA = $FAJUSTE['MontoDMA'] + $montoParDMA;
		 }else{
		   if($fdet['MontoAprobado']>$FAJUSTE['MontoDMA']){
		      $montoParDMA = $FAJUSTE['MontoDMA'] + $montoParDMA;
		   }else{
		      $montoParDMA = $fdet['MontoAprobado'] + $montoParDMA;
		   }
		 }
	   }else{$montoParDMA = $fdet['MontoAprobado'] + $montoParDMA;}
	 }
	 $monto1 = number_format($monto1,2,',','.'); /// ** Monto para la opcion de Partida Ajuste
	 $montoPar = number_format($montoP,2,',','.'); /// ** Monto para la opcion de Partida Presupuesto
	 $montoParDMA = number_format($montoParDMA,2,',','.'); /// ** Monto para la opcion de Partida MDA
	 $cont1= $cont1 + 1;
	 $codigo_partida = $fieldP[cod_partida];
	 $pCapturada = $fieldP[partida1];
	 echo "<tr class='trListaBody6'>
	  <td align='center'>".$field['CodPresupuesto']."</td>
	  <td align='center'>".$fieldP['cod_partida']."</td>
	  <td>".$fieldP['denominacion']."</td>
	  <td align='right'><b><input class='inputP' type='text' style='text-align:right' size='12' maxlength='12' id='partida_pre' value='$montoPar' readonly/>Bs.F</td></b>
	  <td align='right'><b><input class='inputP' type='text' style='text-align:right' size='12' maxlength='12' id='partida_preDMA' value='$montoParDMA' readonly/>Bs.F</td></b>
	  <td align='right'><b><input class='inputP' type='text' style='text-align:right' size='12' maxlength='12' id='partida_aj' value='$monto1' readonly/>Bs.F</td></b>     
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
	   $cont2=0; $montoG=0; $montoAgen=0;$montoGenMDA=0;
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
	   $SAJUSTE="SELECT * FROM pv_ReintegroPresupuestariodet 
	                    WHERE CodAjuste='".$_GET['registro']."' AND
					          CodPresupuesto='".$field['CodPresupuesto']."' AND
							  Organismo='".$field['Organismo']."' AND 
							  cod_partida='".$fdet['cod_partida']."'";
	   $QAJUSTE=mysql_query($SAJUSTE) or die ($SAJUSTE.mysql_error()) ;
	   $RAJUSTE=mysql_num_rows($QAJUSTE);
	   if($RAJUSTE!=0){
	     $FAJUSTE=mysql_fetch_array($QAJUSTE);
	     $montoAgen = $montoAgen + $FAJUSTE['MontoAjuste'];
		 $montoGenMDA = $FAJUSTE['MontoDMA'] + $montoGenMDA;
	   }else{
	     $montoGenMDA = $fdet['MontoAprobado'] + $montoGenMDA;
	   }	
      }
	  $montoGenMDA = number_format($montoGenMDA,2,',','.');
	  $montoAge=number_format($montoAgen,2,',','.');
	  $montoGen=number_format($montoG,2,',','.');
      $codigo_generica = $fieldP[cod_partida];
      $pCapturada2 = $fieldP[partida1];
	  $gCapturada = $fieldP[generica];
	   echo "<tr class='trListaBody5'>
		 <td align='center'>".$field['CodPresupuesto']."</td>
	     <td align='center'>".$fieldP['cod_partida']."</td>
		 <td>".$fieldP['denominacion']."</td>
		 <td align='right'><b><input type='text' class='inputG' size='12' style='text-align:right' maxlength='12' id='generica_pre' value='$montoGen' readonly/>Bs.F</td>
		 <td align='right'><b><input type='text' class='inputG' size='12' style='text-align:right' maxlength='12' id='generica_preMDA' value='$montoGenMDA' readonly/>Bs.F</td>
		 <td align='right'><b><input type='text' class='inputG' size='12' style='text-align:right' maxlength='12' id='generica_aj' value='$montoAge' readonly/>Bs.F</td>       
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
	$SAJUSTE="SELECT * FROM pv_ReintegroPresupuestariodet 
	                  WHERE CodAjuste='".$_GET['registro']."' AND
					        CodPresupuesto='".$field['CodPresupuesto']."' AND
							Organismo='".$field['Organismo']."' AND 
							cod_partida='".$field['cod_partida']."'";
	 $QAJUSTE=mysql_query($SAJUSTE) or die ($SAJUSTE.mysql_error()) ;
	 $RAJUSTE=mysql_num_rows($QAJUSTE);
	 if($RAJUSTE!=0){
	   $FAJUSTE=mysql_fetch_array($QAJUSTE);
	   $montoAj  = $FAJUSTE['MontoAjuste'];
	   $montoDMA = $FAJUSTE['MontoDMA'];
	 }else{
	   $montoAj  = ''; $montoDMA = $field['MontoAprobado'];
	 }
	 /// CALCULOS ///
	 $totalDMA = $totalDMA + $montoDMA ; $total_DMA = number_format($totalDMA,2,',','.');
	 $montoDMA = number_format($montoDMA,2,',','.');
	 /// FIN DE CALCULOS ///
	 $T_Ajuste = $T_Ajuste + $montoAj;
	 //$montoD= $monto + $montoAj; $montoD=number_format($montoD,2,',','.');
	 $montoAj= number_format($montoAj,2,',','.');
	 $Total_Ajuste = number_format($T_Ajuste,2,',','.');
	 //$Total_Ajuste = number_format($T_Ajuste,2,',','.');
	////////////////////////////////////////////////////////////////////////////
    echo "<tr class='trListaBody' onclick='mClk(this,\"registro\");' id='".$field['cod_partida']."'>
		<td align='center'>".$field['CodPresupuesto']."</td>
		<td align='center'>".$field['cod_partida']."</td>
		<td>".$f['denominacion']."</td>
		<td align='right'><input type='text' size='11' style='text-align:right' maxlength='12' id='pre' value='$montoD' readonly/>Bs.F</td>  
		<td align='right'><input type='text' size='11' style='text-align:right' maxlength='12' id='DisponibleD' value='$montoDMA' readonly/>Bs.F</td>  
		<td align='right'><input type='text' size='11' style='text-align:right' maxlegth='12' id='p_ajuste' value='$montoAj' readonly/>Bs.F</td>       
	   </tr>";
	   }
}}echo"<tr><td colspan='2'></td>
               <td align='right'><b>Total:</b></td>
			   <td align='center' class='trListaBody'><input type='hidden' id='total' name='total' size='15' value='$total'/>";
			   echo"<input type='text' id='totalAnt' name='totalAnt' style='text-align:right' size='13' value='$totalT' readonly/>
			        <td align='center' class='trListaBody'>
					  <input type='text' id='totalDMA' name='totalDMA' style='text-align:right' size='13' value='$total_DMA' readonly/>
			          <input type='hidden' class='inputT' id='totalAnt' style='text-align:right' name='totalAnt' size='13' value='$totalT' readonly/> Bs.F</td>
			        <td align='center' class='trListaBody'>
					  <input type='text' size='13' id='Total_Ajuste' style='text-align:right' value='$Total_Ajuste' readonly/>Bs.F</td>
		   </tr>";*/
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
