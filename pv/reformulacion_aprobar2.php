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
</head>
<body>
<?
include "gpresupuesto.php";
?>
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
 <td class="titulo">Aprobar | Reformulaci&oacute;n</td>
 <td align="right"><a class="cerrar"; href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'),'<?=$regresar?>.php?limit=0')">[Cerrar]</a></td>
</tr>
</table><hr width="100%" color="#333333" />
<form id="frmentrada" name="frmentrada" action="reformulacion_aprobar.php?limit=0&accion=GuardarReformulacionAprobar" method="post" >
<? 
$year_actual = date("Y"); //echo $year_actual;
$f_preparacion = date("Y-m-d");

//// --------------------------------------------------------------------------
list($Organismo, $CodPresupuesto, $CodRef) = split('[-]',$_POST['registro']);
$SQL="SELECT 
            * 
		FROM 
			pv_reformulacionppto
	   WHERE 
			Organismo = '$Organismo' and 
			CodPresupuesto = '$CodPresupuesto' and 
			CodRef = '$CodRef' "; //echo $SQL;
$QRY=mysql_query($SQL) or die ($SQL.mysql_error());
$FIELD=mysql_fetch_array($QRY); //echo $FIELD[CodPresupuesto];
echo"<input type='hidden' id='regresar' name='regresar' value='".$regresar."'/>";
echo"<input type='hidden' id='registro' name='registro' value='".$registro."'/>"; ?>
<table width="850" align="center">
<tr><td>
<div id="header">
<ul>
<!-- CSS Tabs PESTAÑAS OPCIONES DE PRESUPUESTO -->
<li><a onclick="document.getElementById('tab1').style.display='block'; document.getElementById('tab2').style.display='none';" href="#">Datos Generales</a></li>
<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block';" href="#">Detalle de Reformulaci&oacute;n</a></li>
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
	<?
	 $sorg="SELECT CodOrganismo,Organismo FROM mastorganismos WHERE CodOrganismo='".$FIELD['Organismo']."'";
	 $qorg=mysql_query($sorg) or die ($sorg.mysql_error());
	 $forg=mysql_fetch_array($qorg);
	?>
	<td width="300"><input type="text" size="67" name="orga" id="orga" value="<?=$forg['Organismo']?>" readonly />
	                <input type="hidden" name="org" id="org" value="<?=$forg['CodOrganismo']?>"/></td>
</tr>
<tr><td height="4"></td></tr>
</table>
<table width="850" class="tblForm" border="0">
<tr><td height="2"></td></tr>

<tr>
    <td width="129"></td>
	<? 
      if($FIELD['FechaResolucion']!='0000-00-00'){list($a, $m, $d)=SPLIT( '[/.-]', $FIELD['FechaResolucion']); $fres=$d.'-'.$m.'-'.$a;}
      if($fres=='00-00-0000'){$fres='';}
    ?>
	<td width="138" align="right">Nro. Resoluci&oacute;n:</td>
	<td width="140"><input name="resolucion" id="resolucion" type="text" size="18" value="<?=$FIELD['NumResolucion']?>" style="text-align:right" readonly/>*</td>
	<td width="85" align="right">F. Resoluci&oacute;n:</td>
	<td width="174"><input name="fresolucion" id="fresolucion" type="text" size="8" value="<?=$fres?>" style="text-align:right" readonly/>*<i>(dd-mm-aaaa)</i></td>
	<td colspan="2" width="200"></td>
</tr>
<tr>
   <td width="129"></td>
   <? 
     if($FIELD['FechaGaceta']!='0000-00-00'){list($a, $m, $d)=SPLIT( '[/.-]', $FIELD['FechaGaceta']); $fgaceta=$d.'-'.$m.'-'.$a;}
     if($fgaceta=='00-00-0000'){$fgaceta='';}
   ?>
   <td width="138" align="right">Nro. Gaceta:</td>
   <td width="140"><input name="gaceta" id="gaceta" type="text" size="18" style="text-align:right" value="<?=$FIELD['NumGaceta']?>" readonly/>*</td>
   <td width="85" align="right">F. Gaceta:</td>
   <td width="174"><input name="fgaceta" id="fgaceta" type="text" size="8" style="text-align:right" value="<?=$fgaceta?>" readonly/>*<i>(dd-mm-aaaa)</i></td>
   <td colspan="2" width="156"></td>
</tr>

<tr><td height="2"></td></tr>
</table>
<table width="850" class="tblForm">
<tr><td height="2"></td></tr>
<tr>
 <td width="95"></td>
 <td width="170" class="tagForm">Nro. Presupuesto:</td>
 <td width="81"><input id="num_presupuesto" name="num_presupuesto" type="text" size="8" value="<?=$FIELD['CodPresupuesto']?>" style="text-align:right" readonly/>
 </td>
 <td width="75" class="tagForm">Estado:</td>
 <td width="146"><input type="text" id="status" name="status" size="12" value="Preparado"  readonly/></td>
 <td width="250"></td>
</tr>
<tr>
 <td width="95"></td>
 <td class="tagForm">F. Reformulaci&oacute;n:</td><? $fcreacion=date("d-m-Y"); $fperiodo=date("Y-m");?>
 <td><input type="text" id="fAjuste" name="fAjuste" size="8" maxlength="8" value="<?=$fcreacion?>" readonly/></td>
 <td class="tagForm"></td>
 <td></td>
 <!--<td class="tagForm">Tipo Ajuste:</td>
  <td><select id="tAjuste" name="tAjuste">
      <option value=""></option>
	  <option value="IN">Incremento</option>
	  <option value="DI">Disminuci&oacute;n</option>
     </select>*</td>-->
</tr>
<tr><td height="2"></td></tr>
</table>
<div style="width:850px" class="divFormCaption">Duraci&oacute;n de Reformulaci&oacute;n</div>
<table class="tblForm" width="850">
<tr><td height="2"></td></tr>
<tr>
  <td width="50"></td>
  <td width="70" align="right">Per&iacute;odo:</td>
  <td width="180"><input id="fperiodo" name="fperiodo" type="text" size="8" maxlength="8" style="text-align:right" value="<?=$FIELD['PeriodoRef']?>" readonly/>*<i>(aaaa-mm)</i></td>
</tr>
<tr><td height="2"></td></tr>
</table>
<div style="width:850px" class="divFormCaption">Descripci&oacute;n de Motivo</div>
<table class="tblForm" width="850">
<tr>
  <td width="195"></td>
  <td width="50">Descripci&oacute;n:</td>
</tr>
<tr>
  <td colspan="1"></td>
  <td width="580"><textarea name="descripcion" id="descripcion" rows="5" cols="80" readonly><? echo $FIELD['Descripcion'];?></textarea>*</td>
</tr>
<tr><td height="2"></td></tr>
</table>
<table width="850" class="tblForm">
<tr><td width="45"></td>
   <td width="245" class="tagForm">Preparado por:</td>
   <? 
     $spre = "select NomCompleto from mastpersonas where CodPersona = '".$FIELD['PreparadoPor']."'";
	 $qpre = mysql_query($spre) or die ($spre.mysql_error());
	 $rpre = mysql_num_rows($qpre);
	 if($rpre!=0) $fpre = mysql_fetch_array($qpre);
  ?>
   <td width="520"><input name="prepor" id="prepor" type="text" size="60" value="<?=$fpre['NomCompleto']?>" readonly/>
   </td>
</tr>
<tr><td></td>
   <td class="tagForm">Aprobado por:</td>
   <td>
   <?
   $saprob = "select 
                     NomCompleto,
					 CodPersona
				 from 
				      mastpersonas
				 where 
				     CodPersona = '".$FIELD['AprobadoPor']."'";
   $qaprob = mysql_query($saprob) or die ($saprob.mysql_error());
   $raprob = mysql_num_rows($qaprob);
   if($raprob!=0)$faprob = mysql_fetch_array($qaprob);
   
   ?>
   <input name="codempleado" type="hidden" id="codempleado" value="<?=$faprob['CodPersona'];?>" />
   <input name="nomempleado" id="nomempleado" type="text" size="60" value="<?=$faprob['NomCompletos'];?>" readonly/>
   <!--<input name="bt_examinar" id="bt_examinar" type="button" value="..." onclick="cargarVentana(this.form, 'lista_empleados.php?limit=0&campo=1', 'height=500, width=800, left=200, top=200, resizable=yes');" />-->*</td>
</tr>
<tr><td></td>
   <td class="tagForm">&Uacute;ltima Modif.:</td>
   <td colspan="1"><? $fCompleta=date("d-m-Y H:m:s");  ?>
	<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$FIELD['UltimoUsuario'];?>" readonly />
	<input name="ult_fecha" type="text" id="ult_fecha" size="22" value="<?=$FIELD['UltimaFechaModif'];?>" readonly /></td>
</tr>
<tr><td height="5"></td></tr>
</table>
</div>
<!-- //// ************** AJUSTE DETALLE ******** ///// -->
<div id="tab2" style="display:none;">
<div style="width:850px" class="divFormCaption">Detalle de Detalle</div>
<input type="hidden" name="registro2" id="registro2" />
<?
echo" <input type='hidden' id='num_presupuesto' name='num_presupuesto' value='".$_POST['registro']."'/>
	  <input type='hidden' id='Org' name='Org' value='".$_POST['Org']."'/>";
?>
<table width="850" class="tblBotones">
<tr><td align="right">
  </td>
</tr>
</table>
<table width="850" class="tblLista" border="0">
<tr class="trListaHead">
 <th width="85" scope="col"># Presupuesto</th>
 <th width="95" scope="col"># Reformulaci&oacute;n</th>
 <th width="80" scope="col"># Partida</th>
 <th scope="300">Denominaci&oacute;n</th>
 <th width="100" scope="col">Monto</th>
</tr>
<?
//------------------------------------------------------------------------------------------------------------
///////////////////************ INSERTAR PARTIDAS ASOCIADAS AL PRESUPUESTO *************/////////////////////
//////////////////************* CARGA LOS DATOS DE LA TABLA "pv_presupuestodet" ****///////////////////// 
//------------------------------------------------------------------------------------------------------------
if($accion=="AGREGAR"){ 
 $SREF="SELECT * 
           FROM pv_reformulacionppto 
          WHERE Organismo='".$_SESSION['ORGANISMO_ACTUAL']."' AND
		        CodPresupuesto='".$FIELD['CodPresupuesto']."' AND
				CodRef='".$FIELD['CodRef']."'";
 $QREF=mysql_query($SREF) or die ($SREF.mysql_error()); 
 $RREF=mysql_num_rows($QREF);
 if($RREF!=0){
  $FREF=mysql_fetch_array($QREF);
  for($i=1; $i<=$filas; $i++){
   if($_POST[$i]!=""){
    $SPART="SELECT * 
             FROM pv_partida 
			WHERE cod_partida='".$_POST[$i]."'";/// CONSULTO PARA COMPARAR COD_PARTIDA
   $QPART=mysql_query($SPART) or die ($SPART.mysql_error());
   $RPART=mysql_num_rows($QPART);
   if($RPART!=0){
	$FPART=mysql_fetch_array($QPART);
	$SADET2="SELECT * FROM pv_reformulacionpptodet
	                  WHERE cod_partida='".$_POST[$i]."' AND 
					        CodPresupuesto='".$FREF['CodPresupuesto']."' AND
							Organismo='".$_SESSION['ORGANISMO_ACTUAL']."' AND
							CodRef='".$FREF['CodRef']."'";
	$QADET2=mysql_query($SADET2) or die ($SADET2.mysql_error());
	if(mysql_num_rows($QADET2)!=0){
		echo"<script>";
		echo"alert('LOS DATOS HAN SIDO INGRESADOS ANTERIORMENTE')";
		echo"</script>";
	}else{
	 if($FPART['tipo'] != 'T'){
	  $sql="INSERT INTO pv_reformulacionpptodet (Organismo,
												 CodPresupuesto,
												 CodRef,
												 cod_partida,
												 Estado)
										VALUES ('".$_SESSION['ORGANISMO_ACTUAL']."',
												'".$FREF['CodPresupuesto']."',
												'".$FREF['CodRef']."',
												'".$_POST[$i]."',
												'PR')"; //echo $sql ;
	  $query=mysql_query($sql) or die ($sql.mysql_error());
}}}}}}}
else{
 if ($accion=="ELIMINAR") {
	$sql="DELETE FROM pv_reformulacionpptodet 
	            WHERE cod_partida='".$registro2."' AND
				      Organismo='".$FIELD['Organismo']."' AND
					  CodPresupuesto='".$FIELD['CodPresupuesto']."' AND
					  CodRef='".$FIELD['CodRef']."'"; //echo $sql;
	$query=mysql_query($sql) or die ($sql.mysql_error());
}}
//------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------
$sql="SELECT * 
        FROM 
		     pv_reformulacionppto 
       WHERE 
	         CodPresupuesto='".$FIELD['CodPresupuesto']."' AND
			 Organismo='".$FIELD['Organismo']."' AND
			 CodRef='".$FIELD['CodRef']."'"; //echo $sql;
$qry=mysql_query($sql) or die ($sql.mysql_error());
$row=mysql_num_rows($qry);
if($row!=0){ 
    $field=mysql_fetch_array($qry); //echo $field['partida'];
	$sref="SELECT * 
	         FROM pv_reformulacionpptodet
			WHERE CodPresupuesto='".$field['CodPresupuesto']."' AND
			      Organismo='".$field['Organismo']."' AND
			      CodRef='".$field['CodRef']."'";
	$qref=mysql_query($sref) or die ($sref.mysql_error());
	$rref=mysql_num_rows($qref);
	if($rref!=0){
	  for($i=0; $i<$rref; $i++){
	     $fref=mysql_fetch_array($qref);
		 $sqlP="SELECT * 
	              FROM pv_partida 
		         WHERE cod_partida='".$fref['cod_partida']."'";
         $qryP=mysql_query($sqlP) or die ($sqlP.mysql_error());
		 $fieldP=mysql_fetch_array($qryP);
	     echo "<tr class='trListaBody' onclick='mClk(this,\"registro2\");'  id='".$fref['cod_partida']."'>
	     <td align='center'>".$fref['CodPresupuesto']."</td>
		 <td align='center'>".$fref['CodRef']."</td>
	     <td align='center'>".$fieldP['cod_partida']."</td>
	     <td>".$fieldP['denominacion']."</td>
		 <td align='center'><input type='text' id='montoref' name='montoref' size='10' style='text-align:right' value='".$fref['MontoRef']."' readonly/>Bs.F</td>
		 </tr>";
	   }
     }
}
	
	
	
	/*
	
	
   ///  ORDENA PARTIDAS ////  ORDENA PARTIDAS POR TIPO=DETALLE "D"
   //// **** Obtengo Partidas Tipo "T" 301-00-00-00	**** ////  
   if(($field['partida']!=00) and (($cont1==0) or ($pCapturada!=$field['partida']))){
    $count= $count + 1;
    $sqlP="SELECT * 
	         FROM pv_partida 
		    WHERE partida1='".$field['partida']."' AND
				  cod_tipocuenta='".$field['tipocuenta']."' AND
				  tipo='T' AND 
				  generica='00'";
    $qryP=mysql_query($sqlP) or die ($sqlP.mysql_error());
    if(mysql_num_rows($qryP)!=0){
	 $fieldP=mysql_fetch_array($qryP);
	 //$montoP=0; $cont1=0;
	 $sqldet="SELECT * 
	            FROM pv_reformulacionpptodet 
			   WHERE partida='".$fieldP['partida1']."' AND
					 tipocuenta='".$fieldP['cod_tipocuenta']."' AND
					 CodPresupuesto='".$field['CodPresupuesto']."' AND
					 Organismo=";
	 $qrydet=mysql_query($sqldet);
	 $rwd=mysql_num_rows($qrydet);
	 /*for($a=0; $a<$rwd; $a++){
	  $fdet=mysql_fetch_array($qrydet);
	  $cont1 = $cont1 + 1;
	  $montoP = $montoP + $fdet['MontoAjustado'];
	 }
	 $montoPar=number_format($montoP,2,',','.');
	 $cont1= $cont1 + 1;
	 $codigo_partida = $fieldP[cod_partida];
	 $pCapturada = $fieldP[partida1];
	 echo "<tr class='trListaBody6'>
	  <td align='center'>".$field['CodPresupuesto']."</td>
	  <td align='center'>".$fieldP['cod_partida']."</td>
	  <td>".$fieldP['denominacion']."</td>
	  <td align='right'><input type='text' size='11' maxlength='12' style='text-align:right' class='inputP' id='montoPartida' name='montoPartida' value='$montoPar' readonly/>Bs.F</td>
	  <td align='right'><b><input type='text' size='11' maxlength='12' style='text-align:right' class='inputP' id='".$codigo_partida."'  name='montoPartida' value='$montoT1' readonly/>Bs.F</td></b>
	  <td></td>     
	 </tr>";
	    }
	  }
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
	   $cont2=0; $montoG=0;
	   $sqldet="SELECT * FROM pv_reformulacionpptodet 
						WHERE partida='".$fieldP['partida1']."' AND 
							  generica='".$fieldP['generica']."' AND 
							  tipocuenta='".$fieldP['cod_tipocuenta']."' AND
							  CodPresupuesto='".$field['CodPresupuesto']."'";
      $qrydet=mysql_query($sqldet);
      $rwdet=mysql_num_rows($qrydet);
      for($b=0; $b<$rwdet; $b++){
	   $fdet=mysql_fetch_array($qrydet);
	   $cont2= $cont2 + 1;
	   $montoG= $montoG + $fdet['MontoAjustado'];
      } 
	  $montoGen=number_format($montoG,2,',','.');
      $codigo_generica = $fieldP[cod_partida];
      $pCapturada2 = $fieldP[partida1];
	  $gCapturada = $fieldP[generica];
	   echo "<tr class='trListaBody5'>
		 <td align='center'>".$field['CodPresupuesto']."</td>
	     <td align='center'>".$fieldP['cod_partida']."</td>
		 <td>".$fieldP['denominacion']."</td>
		 <td align='right'><input type='text' size='11' maxlenght='12' style='text-align:right' class='inputG' id='montoGenerica' name='montoGenerica' value='$montoGen' readonly/>Bs.F</td>
		 <td align='right'><b><input type='text' size='11' maxlength='12' style='text-align:right' class='inputG' id='".$codigo_generica."' name='montoGenerica' value='$montoT2' readonly/>Bs.F</td>
		 <td></td>       
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
	$codigo_codpartida=$field['cod_partida'];
    $monto = $field['MontoAjustado'];
	$total = $monto + $total;
	$totalT=number_format($total,2,',','.');
	$montoD=number_format($monto,2,',','.');
    $codigo_detalle = $field['cod_partida'];
	// PRUEBA
	
	// FIN PRUEBA
    echo "<tr class='trListaBody'>
		<td align='center'>".$field['CodPresupuesto']."</td>
		<td align='center'>".$field['cod_partida']."</td>
		<td>".$f['denominacion']."</td>
		<td align='right'><input type='text' size='11' style='text-align:right' maxlenght='12' id='".$codigo_codpartida."' name='montoCodPartida' value='$montoD' onfocus='obtenerMontoD(this.value);' readonly/>Bs.F</td>
		<td align='right'><input type='text' size='11' style='text-align:right' class='montoA' maxlength='12' id='$codigo_partida|$codigo_generica|$codigo_codpartida' name='".$field['Secuencia']."'  onchange='sumarAjuste(this.value, this.id);' onfocus='obtener(this.value);' onBlur='numeroBlur(this);'/>Bs.F</td>         
	   </tr>";
	   }
}}    echo"
     <tr><td colspan='2'></td>
         <td align='right'><b>Total:</b></td>
	     <td align='center' class='trListaBody'>
			 <input type='hidden' id='total' name='total' size='15' value='$total'/>
			 <input type='text' id='totalAnt' name='totalAnt' style='text-align:right' size='13' value='$totalT' readonly/>Bs.F</td>
	     <td align='center' class='trListaBody'>
		    <input type='text' name='total_ajuste' style='text-align:right' id='total_ajuste' size='12' readonly/> Bs.F</td>
    </tr>";*/
?>
</table>
</div>
<input type="hidden" id="montovoy" name="montovoy"/>
<input type="hidden" id="montova"  name="montova"/>

<center>
<input name="btguardar" type="submit" id="btguardar" value="Aprobar Registro"/>
<input name="btcancelar" type="button" id="btcancelar" value="Cancelar" onclick="cargarPagina(this.form, '<?=$regresar?>.php?limit=0');"/>
</center>

</form>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
<!-- VALIDACIONES CAMPOS OBLIGATORIOS  -->
<script language="javascript">
/*function Alarma(formulario){
   for (var i=0; formulario.elements.length; i++) {//SE RECORRE TODO LOS CAMPOS LOS MISMOS DEBEN CONTENER UNA CANTIDAD ASIGNADA
	if (formulario.elements[i].className == "montoA"){
	  //alert(formulario.elements[i].value);
	  if(formulario.elements[i].value!='0,00'){
	    alert('DEBE GUARDAR LOS REGISTROS ANTES DE INGRESAR OTRAS PARTIDAS...!');
	    //alert('Debe introducir un monto.');
		formulario.elements[i].focus();
		//i=ormulario.elements.length;
		return(false);
		//break;
	  }
 	}
  }
}

function verificarDatos(formulario) {
////   VALIDACION TIPO AJUSTE
if (formulario.tAjuste.value.length <1) {
 alert("Seleccione el Tipo de Ajuste");
 formulario.tAjuste.focus();
return (false);
}
var checkOK ="0123456789" + "ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚ" + "abcdefghijklmnñopqrstuvwxyzáéíóú" ;
var checkStr = formulario.tAjuste.value;
var allValid = true; 
for (i = 0; i < checkStr.length; i++) {
  ch = checkStr.charAt(i); 
  for (j = 0; j < checkOK.length; j++)
	  if (ch == checkOK.charAt(j))
	  break;
	  if (j == checkOK.length) { 
		 allValid = false; 
	  break; 
	  }
}
if (!allValid) { 
 alert("Escriba sólo números en el campo \"Tipo Ajuste\"."); 
 formulario.tAjuste.focus(); 
 return (false); 
} 

////     VALIDACION DEL PERIODO
if (formulario.fperiodo.value.length <2) {
 alert("Introduzca el Periodo.");
 formulario.fperiodo.focus();
return (false);
}
var checkOK ="0123456789" + "-";
var checkStr = formulario.fperiodo.value;
var allValid = true; 
for (i = 0; i < checkStr.length; i++) {
  ch = checkStr.charAt(i); 
  for (j = 0; j < checkOK.length; j++)
	  if (ch == checkOK.charAt(j))
	  break;
	  if (j == checkOK.length) { 
		 allValid = false; 
	  break; 
	  }
}
if (!allValid) { 
 alert("Introduzca el Periodo."); 
 formulario.fperiodo.focus(); 
 return (false); 
} 
//VALIDACION DEL CAMPO DESCRIPCION
if (formulario.descripcion.value.length <1) {
 alert("Introduzca la Descripción del Ajuste.");
 formulario.descripcion.focus();
return (false);
}
var checkOK ="0123456789" + "ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚ" + "abcdefghijklmnñopqrstuvwxyzáéíóú" + " /-*.;";
var checkStr = formulario.descripcion.value;
var allValid = true; 
for (i = 0; i < checkStr.length; i++) {
  ch = checkStr.charAt(i); 
  for (j = 0; j < checkOK.length; j++)
	  if (ch == checkOK.charAt(j))
	  break;
	  if (j == checkOK.length) { 
		 allValid = false; 
	  break; 
	  }
}
if (!allValid) { 
 alert("Introduzca la Descripción del Ajuste."); 
 formulario.descripcion.focus(); 
 return (false); 
} 
//VALIDACION APROBADO POR
if (formulario.nomempleado.value.length <2) {
 alert("Elija por quien sera aprobado haciendo click en el botón");
 formulario.nomempleado.focus();
return (false);
}
var checkOK = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚ" + "abcdefghijklmnñopqrstuvwxyzáéíóú" + " .,_/";
var checkStr = formulario.nomempleado.value;
var allValid = true; 
for (i = 0; i < checkStr.length; i++) {
  ch = checkStr.charAt(i); 
  for (j = 0; j < checkOK.length; j++)
	  if (ch == checkOK.charAt(j))
	  break;
	  if (j == checkOK.length) { 
		 allValid = false; 
	  break; 
	  }
}
if (!allValid) { 
 alert("Elija por quien sera aprobado haciendo click en el Botón"); 
 formulario.nomempleado.focus(); 
 return (false); 
} 
return (true); 
} */
</SCRIPT>