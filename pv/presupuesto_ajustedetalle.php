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
</script>

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
 $fActual=date("Y-m-d"); 
 $sqlAjuste="SELECT MAX(CodAjuste) FROM pv_ajustepresupuestario 
                                  WHERE FechaAjuste='$fActual' AND 
								        Organismo='".$_POST['organismo']."' AND
										CodPresupuesto='".$_POST['npresupuesto']."'";
 $qryAjuste=mysql_query($sqlAjuste) or die (($sqlAjuste).mysql_error());
 $field=mysql_fetch_array($qryAjuste);
 echo"<input type='hidden' id='npresupuesto' name='npresupuesto' value='".$_POST['npresupuesto']."'/>
	 <input type='hidden' id='organismo' name='organismo' value='".$_POST['organismo']."'/>";
 
 $S="SELECT * FROM pv_ajustepresupuestario 
             WHERE CodAjuste='".$field['0']."' AND 
			       CodPresupuesto='".$_POST['npresupuesto']."' AND
				   Organismo='".$_POST['organismo']."'";
 $Q=mysql_query($S) or die ($S.mysql_error());
 $F=mysql_fetch_array($Q);
 echo"<input type='hidden' id='regresar' name='regresar' value='".$regresar."' />";
 //echo"Regresar= ".$regresar;
?>
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
 <td class="titulo">Nuevo | Ajuste</td>
 <td align="right"><a class="cerrar"; href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'),'<?=$regresar?>.php?limit=0')">[Cerrar]</a></td>
</tr>
</table><hr width="100%" color="#333333" />
<form id="frmentrada" name="frmentrada" action="presupuesto_ajustedetalle.php?accion=GuardarAjusteDetalle" method="post" onsubmit="return verificarDatos(this,'Guardar')" >
<? echo"<input type='hidden' id='regresar' name='regresar' value='".$regresar."'/>"; ?>
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
<div id="tab1" style="display:none;">
<div style="width:850px" class="divFormCaption">Informaci&oacute;n de Presupuesto</div>
<table width="850" class="tblForm">
<tr>
	<td width="48"></td>
	<td width="90" class="tagForm">Organismo:</td>
	<td width="300">
	  <?
	    $sqlOrg="SELECT CodOrganismo,Organismo FROM mastorganismos WHERE CodOrganismo='".$_POST['organismo']."'";
		$qryOrg=mysql_query($sqlOrg) or die (($sqlOrg).mysql_error());
		$fOrg=mysql_fetch_array($qryOrg);
	  ?>
	  <input name="org" id="org" type="tex" size="60" value="<?=$fOrg['Organismo'];?>" readonly/>
		<!--<select name="organismo" id="organismo" class="selectBig">
		<?php 
		// segundo bloque php //* Conectamos a los datos *//
		/*include "conexion.php";
		$sql="SELECT CodOrganismo,Organismo FROM mastorganismos WHERE 1";
		$rs=mysql_query($sql);
		while($reg=mysql_fetch_assoc($rs)){
		$codOrganismo=$reg['CodOrganismo'];// Codigo del orgnismo
		$organ=$reg['Organismo'];// Descripcion del Organismo
		   echo "<option value=$codOrganismo>$organ</option>";
		}*/
		
		?></select>-->
   </td>
</tr>
<tr><td height="4"></td></tr>
</table>
<table width="850" class="tblForm" border="0">
<tr><td height="2"></td></tr>
<tr>
    <?
	list($a, $m, $d)=SPLIT( '[/.-]', $F['FechaGaceta']); $fgaceta=$d.'-'.$m.'-'.$a;
	list($a, $m, $d)=SPLIT( '[/.-]', $F['FechaDecreto']); $fdecreto=$d.'-'.$m.'-'.$a;
	list($a,$m,$d)=SPLIT('[/.-]',$F['FechaAjuste']); $fajuste=$d.'-'.$m.'-'.$a;
	if($F['Estado']==AP){$festado='Aprobado';}else{$festado='Preparaci&oacute;n';}
	//list($a, $m, $d)=SPLIT( '[/.-]', $F['Periodo']); $fperiodo=$m.'-'.$a;
	?>
    <td width="190"></td>
	<td width="75" align="right">Nro. Gaceta:</td>
	<td width="70"><input name="gaceta" id="gaceta" type="text" size="8" value="<?=$F['NumeroGaceta'];?>" readonly/>*</td>
	<td width="64" align="right">F. Gaceta:</td>
	<td width="150"><input name="fgaceta" id="fgaceta" type="text" size="8" value="<?=$fgaceta;?>" readonly/>*<i>(dd-mm-aaaa)</i></td>
	<td colspan="2" width="200"></td>
</tr>
<tr>
 <td width="190"></td>
 <td width="75" align="right">Nro. Decreto:</td>
 <td width="70"><input name="decreto" id="decreto" type="text" size="8" value="<?=$F['NumeroDecreto'];?>" readonly/>*</td>
 <td width="64" align="right">F. Decreto:</td>
 <td width="150"><input name="fdecreto" id="fdecreto" type="text" size="8" value="<?=$fdecreto;?>" readonly/>*<i>(dd-mm-aaaa)</i></td>
</tr>
<tr><td height="2"></td></tr>
</table>
<table width="850" class="tblForm">
<tr><td height="2"></td></tr>
<tr>
 <td width="143"></td>
 <td class="tagForm">Nro. Presupuesto:</td>
 <td><input id="npresupuesto" name="npresupuesto" type="text" size="8" value="<?=$F['CodPresupuesto'];?>" readonly/>
     <input name="bt_examinar" id="bt_examinar" type="button" value="..." onclick="Mensaje();"/>*
 </td>
 <td class="tagForm">Estado:</td>
 <td><input type="text" id="status" name="status" size="11" value="<?=$festado;?>"  readonly/></td>
 <td width="257"></td>
</tr>
<tr>
 <td width="33"></td>
 <td class="tagForm">F. Ajuste:</td>
 <td><input type="text" id="fAjuste" name="fAjuste" size="8" maxlength="8" value="<?=$fajuste;?>" readonly/></td>
 <td class="tagForm">Tipo Ajuste:</td><? if($F[TipoAjuste]==IN){ $tipoAjuste=Incremento;} if($F[TipoAjuste]==DI){ $tipoAjuste=Disminuci&oacute;n;}?>
  <td><input name="tAjuste" id="tAjuste" type="text" size="11" value="<?=$tipoAjuste;?>" readonly/>*</td>
</tr>
<tr><td height="2"></td></tr>
</table>
<div style="width:850px" class="divFormCaption">Duraci&oacute;n del Ajuste</div>
<table class="tblForm" width="850">
<tr><td height="2"></td></tr>
<tr>
  <td width="50"></td>
  <td width="70" align="right">Per&iacute;odo:</td>
  <td width="180"><input id="fPeriodo" name="fPeriodo" type="text" size="8" maxlength="10" value="<?=$F['Periodo'];?>" readonly/>*<i>(mm-aaaa)</i></td>
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
  <td width="580"><textarea name="descripcion" id="descripcion" rows="5" cols="80" readonly><? echo $F['Descripcion'];?></textarea>*</td>
</tr>
<tr><td height="2"></td></tr>
</table>
<table width="850" class="tblForm">
<tr><td width="45"></td>
   <td width="245" class="tagForm">Preparado por:</td><? $sql3=mysql_query("SELECT * FROM usuarios WHERE Usuario='".$_SESSION['USUARIO_ACTUAL']."'");
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
<tr><td></td>
   <td class="tagForm">Aprobado por:</td>
   <td><input name="codempleado" type="hidden" id="codempleado" value="" />
	       <input name="nomempleado" id="nomempleado" type="text" size="60" value="<?=$F['AprobadoPor'];?>" readonly/>
		   <input name="bt_examinar" id="bt_examinar" type="button" value="..." onclick="Mensaje();" />*</td>
</tr>
<tr><td></td>
   <td class="tagForm">&Uacute;ltima Modif.:</td>
   <td colspan="1"><? $fCompleta=date("d-m-Y H:m:s");  ?>
	<!--<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$F['UltimoUsuario'];?>" readonly />
	<input name="ult_fecha" type="text" id="ult_fecha" size="22" value="<?=$F['UltimaFechaModif'];?>" readonly />-->
    <input name="ult_usuario" type="text" id="ult_usuario" size="30"  readonly />
	<input name="ult_fecha" type="text" id="ult_fecha" size="22"  readonly /></td>
</tr>
<tr><td height="5"></td></tr>
</table>
</div>

<div id="tab2" style="display:block;">
<div style="width:850px" class="divFormCaption">Detalle de Ajuste</div>
<?
echo" <input type='hidden' id='npresupuesto' name='npresupuesto' value='".$_POST['npresupuesto']."'/>
	  <input type='hidden' id='organismo' name='organismo' value='".$_POST['organismo']."'/>
      <input type='hidden' id='codajuste' name='codajuste' value='".$field['0']."' />";
?>
<table width="850" class="tblBotones">
<tr><td align="right">
  <input name="btNuevo" type="button" id="btNuevo" value="Agregar" onmouseup="Alarma(frmentrada)" onclick="cargarVentana(this.form,'lista_partidas.php?pagina=presupuesto_ajustedetalle.php?accion=AGREGAR&limit=0&npresupuesto=<?=$npresupuesto?>&organismo=<?=$organismo?>&target=main','height=500, width=800, left=200, top=200, resizable=yes');"/>
  <input name="btBorrar" type="button" id="btBorrar" value="Eliminar" onClick="eliminarAjustePartida();"/>
  </td>
</tr>
</table>
<table width="850" class="tblLista" border="0">
<tr class="trListaHead">
 <th width="85" scope="col"># Presupuesto</th>
 <th width="80" scope="col"># Partida</th>
 <th scope="300">Denominaci&oacute;n</th>
 <th width="115" scope="col">Monto Asignado</th>
 <th width="115" scope="col">Monto Ajustado</th>
</tr>
<?
//------------------------------------------------------------------------------------------------------------
///////////////////************ INSERTAR PARTIDAS ASOCIADAS AL ANTEPROYECTO *************/////////////////////
//////////////////************* CARGA LOS DATOS DE LA TABLA "pv_antepresupuestodet" ****///////////////////// 
//------------------------------------------------------------------------------------------------------------
if($accion=="AGREGAR"){ 
 $SADET="SELECT * FROM pv_ajustepresupuestario 
                 WHERE CodPresupuesto='".$F['CodPresupuesto']."' AND
				       CodAjuste='".$F['CodAjuste']."'";// CONSULTA EL CODIGO DE PRESUPUESTO
 $QADET=mysql_query($SADET) or die ($SADET.mysql_error()); 
 $RADET=mysql_num_rows($QADET);
 if($RADET!=0){
  $FADET=mysql_fetch_array($QADET);
 for($i=1; $i<=$filas; $i++){
  if($_POST[$i]!=""){
   $SPART=mysql_query("SELECT * FROM pv_partida WHERE cod_partida='".$_POST[$i]."'");/// CONSULTO PARA COMPARAR COD_PARTIDA
   if(mysql_num_rows($SPART)!=0){
	$FPART=mysql_fetch_array($SPART);
	$SADET2="SELECT * FROM pv_ajustepresupuestariodet
	                  WHERE cod_partida='".$_POST[$i]."' AND 
					         CodPresupuesto='".$FADET['CodPresupuesto']."'";
	$QADET2=mysql_query($SADET2) or die ($SADET2.mysql_error());
	if(mysql_num_rows($QADET2)!=0){
		echo"<script>";
		echo"alert('LOS DATOS HAN SIDO INGRESADOS ANTERIORMENTE')";
		echo"</script>";
	}else{
	 if($FPART['tipo'] != 'T'){
	  $sqlAnt="SELECT MAX(CodAjuste) FROM pv_ajustepresupuestariodet WHERE CodPresupuesto='".$FADET['CodPresupuesto']."'";
	  $qryAnt=mysql_query($sqlAnt) or die ($sqlAnt.mysql_error());
	  $fieldAnt=mysql_fetch_array($qryAnt);
	  $sql="INSERT INTO pv_ajustepresupuestariodet (Organismo,
												   CodPresupuesto,
												   CodAjuste,
												   CodPartida)
											VALUES ('".$_SESSION['ORGANISMO_ACTUAL']."',
													'".$fieldPpto['CodPresupuesto']."',
													'".$_POST[$i]."',
													'".$fieldP['CodPartida']."'";
		  $query=mysql_query($sql) or die ($sql.mysql_error());
}}}}}}}
else{
 if ($accion=="ELIMINAR") {
	$sql="DELETE FROM pv_ajustepresupuestariodet WHERE CodPresupuesto='".$registro2."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}}
//------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------
$sql="SELECT * FROM pv_presupuestodet 
              WHERE CodPresupuesto='0001' AND
			        Organismo='0001'";

/*$sql="SELECT * FROM pv_presupuestodet 
              WHERE CodPresupuesto='".$_POST['npresupuesto']."' AND
			        Organismo='".$_POST['organismo']."'";*/
$qry=mysql_query($sql) or die (($sql).mysql_error());
$row=mysql_num_rows($qry);
if($row!=0){
 for($i=0; $i<$row; $i++){
    $field=mysql_fetch_array($qry);
   ///  ORDENA PARTIDAS ////  ORDENA PARTIDAS POR TIPO=DETALLE "D"
   //// **** Obtengo Partidas Tipo "T" 301-00-00-00	**** ////  
   if(($field['partida']!=00) and (($cont1==0) or ($pCapturada!=$field['partida']))){
    $count= $count + 1;
    $sqlP="SELECT * FROM pv_partida 
				   WHERE partida1='".$field['partida']."' AND
						 cod_tipocuenta='".$field['tipocuenta']."' AND
						 tipo='T' AND 
						 generica='00'";
    $qryP=mysql_query($sqlP) or die ($sqlP.mysql_error());
    if(mysql_num_rows($qryP)!=0){
	 $fieldP=mysql_fetch_array($qryP);
	 $montoP=0; $cont1=0;
	 $sqldet="SELECT * FROM pv_presupuestodet 
					  WHERE partida='".$fieldP['partida1']."' AND
							tipocuenta='".$fieldP['cod_tipocuenta']."' AND
							CodPresupuesto='".$field['CodPresupuesto']."'";
	 $qrydet=mysql_query($sqldet);
	 $rwd=mysql_num_rows($qrydet);
	 for($a=0; $a<$rwd; $a++){
	  $fdet=mysql_fetch_array($qrydet);
	  $cont1 = $cont1 + 1;
	  $montoP = $montoP + $fdet['MontoCalculado'];
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
	   $montoG= $montoG + $fdet['MontoCalculado'];
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
    $monto = $field['MontoCalculado'];
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
			 <input type='text' id='totalAnt' name='totalAnt' size='13' value='$totalT' readonly/></td>
	     <td align='center' class='trListaBody'>
		    <input type='text' name='total_ajuste' id='total_ajuste' size='12' readonly/> Bs.F</td>
    </tr>";
?>
</table>
</div>
<input type="hidden" id="montovoy" name="montovoy"/>
<input type="hidden" id="montova"  name="montova"/>

<center>
<input name="btguardar" type="submit" id="btguardar" value="Guardar Registro"/>
<input name="btcancelar" type="button" id="btcancelar" value="Cancelar" onclick="cargarPagina(this.form, '<?=$regresar?>.php?limit=0');"/>
</center>

</form>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
<!-- VALIDACIONES CAMPOS OBLIGATORIOS  -->
<script language="javascript">
function Alarma(formulario){
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
  /*for (var i=0; formulario.elements.length; i++) {//SE RECORRE TODO LOS CAMPOS LOS MISMOS DEBEN CONTENER UNA CANTIDAD ASIGNADA
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
 return (true); */
} 
</SCRIPT>