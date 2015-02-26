<?php

session_start();

if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location:index.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="css1.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" language="javascript" src="fscript02.js"></script>

<script type="text/javascript" language="javascript" src="fscript.js"></script>

<style type="text/css">

<!--

UNKNOWN {        FONT-SIZE: small}

#header {        FONT-SIZE: 93%; BACKGROUND: url(imagenes/bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal}

#header UL {        PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none}

#header LI {        PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(imagenes/left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px}

#header A {        PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(imagenes/right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none}

#header A {        FLOAT: none}

#header A:hover {        COLOR: #333}

#header #current {        BACKGROUND-IMAGE: url(imagenes/left_on.gif)}

#header #current A {        BACKGROUND-IMAGE: url(imagenes/right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333}

-->

</style>

</head>

<body>

<? 

   include "fphp.php";

   include "gmsector.php";

   connect();

 	$sql="SELECT * FROM pv_partida WHERE (cod_partida='".$_POST['registro']."')";

	$query=mysql_query($sql) or die ($sql.mysql_error());

	$rows=mysql_num_rows($query);

	if($rows!=0) $field=mysql_fetch_array($query);

	if($field['Estado']=="A") $activo="checked"; else $inactivo="checked";

	

?>

<table width="100%" height="19" cellpadding="0" cellspacing="0">

<tr>

  <td class="titulo">Anteproyecto | Aprobar</td>

  <td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'),'anteproyecto_aprobar.php?limit=0')";>[Cerrar]</a></td>

</tr>

</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="anteproyecto_aprobar.php?limit=0&accion=AnteproyectoAprobado" method="POST">

<? include "gmsector.php"; ?>

<table width="800" align="center">

<tr>

  <td>

	<div id="header">

	<ul>

	<!-- CSS Tabs PESTAÑAS OPCIONES DE PRESUPUESTO -->

	<li><a onClick="document.getElementById('tab1').style.display='block'; document.getElementById('tab2').style.display='none';" href="#">Datos Generales</a></li>

	<li><a onClick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block';" href="#">Detalle de Presupuesto</a></li> 

	</ul>

	</div>

  </td>

</tr>

</table>

<div id="tab2" style="display:none;">

<div style="width:800px; height:15px" class="divFormCaption">Detalle de Presupuesto</div>

<table width="800" class="tblLista" border="0">

  <tr class="trListaHead">

		<th width="88" scope="col">Partida</th>

		<th scope="300">Denominaci&oacute;n</th>

		<th width="44" scope="col">Estado</th>

		<th width="24" scope="col">Tipo</th>

		<th width="124" scope="col">MontoUtilizar</th>

  </tr>

<?php

//------------------------------------------------------------------------------------------------------------

///////////////////************  MOSTRAR PARTIDAS ASOCIADAS AL ANTEPROYECTO *************/////////////////////

//------------------------------------------------------------------------------------------------------------

$sql="SELECT CodAnteproyecto FROM pv_antepresupuesto WHERE CodAnteproyecto='".$_GET['registro']."' ";

$qry=mysql_query($sql) or die ($sql.mysql_error());

if(mysql_num_rows($qry)!=0){

  $field=mysql_fetch_array($qry);

  $sqlDet="SELECT * FROM pv_antepresupuestodet WHERE CodAnteproyecto='".$field['CodAnteproyecto']."' ORDER BY cod_partida";

  $query=mysql_query($sqlDet) or die ($sqlDet.mysql_error());

  $rows=mysql_num_rows($query);

  for($i=0; $i<$rows; $i++){

    $fielDet=mysql_fetch_array($query);

      //// ********  ORDENO PARTIDAS

	  if(($fielDet['partida']!=00) and (($cont1==0) or ($pCapturada!=$fielDet['partida']))){

		 $sqlP="SELECT * FROM pv_partida 

						WHERE partida1='".$fielDet['partida']."' AND

							  cod_tipocuenta='".$fielDet['tipocuenta']."' AND 

							  tipo='T' AND generica='00'";

		 $qryP=mysql_query($sqlP) or die ($sqlP.mysql_error());

		 if(mysql_num_rows($qryP)!=0){

		   $fieldP=mysql_fetch_array($qryP);

		   $cont1=0;$montoP=0;

		   $sqldet="SELECT * FROM pv_antepresupuestodet 

							WHERE partida='".$fieldP['partida1']."' AND 

								  tipocuenta='".$fieldP['cod_tipocuenta']."' AND

								  CodAnteproyecto='".$fielDet['CodAnteproyecto']."'";

		   $qrydet=mysql_query($sqldet) or die ($sqldet.mysql_error());

		   $rwdet=mysql_num_rows($qrydet);

		   for($a=0; $a<$rwdet; $a++){

			 $fdet=mysql_fetch_array($qrydet);

			 $cont1= $cont1 + 1;

			 $montoP = $montoP + $fdet['MontoPresupuestado'];

		   }

		   $montoPar=number_format($montoP,2,',','.');

		   $cont1= $cont1 + 1;

		   $codigo_partida = $fieldP[cod_partida];

		   $pCapturada = $fieldP[partida1];

		   echo "<tr class='trListaBody6'>

			 <td align='center'>".$fieldP['cod_partida']."</td>

			 <td>".$fieldP['denominacion']."</td>

			 <td align='center'>".$fieldP['Estado']."</td>

			 <td align='center'>".$fieldP['tipo']."</td>

			 <td align='right'><input type='text' size='12' class='inputP' style='text-align:right' maxlength='12' name='partida' value='$montoPar' readonly/>Bs.F</td>         

		   </tr>";

		 }

	   }

	   //////////////////////////////////////////////////////////

	   //// **** Obtengo Partidas Tipo "T" 301-01-00-00	**** ////

	   if(($fielDet['partida']!=00) and (($cont2==0) or ($gCapturada!=$fielDet['generica']) or ($pCapturada2!=$fielDet['partida']))){

		 $sqlP="SELECT * FROM pv_partida 

						WHERE partida1='".$fielDet['partida']."' AND 

							  cod_tipocuenta='".$fielDet['tipocuenta']."' AND 

							  tipo='T' AND 

							  generica='".$fielDet['generica']."' AND 

							  especifica='00'";

		 $qryP=mysql_query($sqlP) or die ($sqlP.mysql_error());

		 if(mysql_num_rows($qryP)){

		   $fieldP=mysql_fetch_array($qryP);

		   $cont2=0;$montoG=0;

		   $sqldet="SELECT * FROM pv_antepresupuestodet 

							WHERE partida='".$fieldP['partida1']."' AND 

								  generica='".$fieldP['generica']."' AND 

								  tipocuenta='".$fieldP['cod_tipocuenta']."' AND

								  CodAnteproyecto='".$fielDet['CodAnteproyecto']."'";

		   $qrydet=mysql_query($sqldet) or die ($sqldet.mysql_error());

		   $rwdet=mysql_num_rows($qrydet);

		   for($b=0; $b<$rwdet; $b++){

			$fdet=mysql_fetch_array($qrydet);

			$cont2= $cont2 + 1;

			$montoG = $montoG + $fdet['MontoPresupuestado'];

		   }

		   $montoGen=number_format($montoG,2,',','.');

		   $codigo_generica = $fieldP[cod_partida];

		   $gCapturada = $fieldP[generica];

		   $pCapturada2 = $fieldP[partida1];

		   echo "<tr class='trListaBody5'>

			 <td align='center'>".$fieldP['cod_partida']."</td>

			 <td>".$fieldP['denominacion']."</td>

			 <td align='center'>".$fieldP['Estado']."</td>

			 <td align='center'>".$fieldP['tipo']."</td>

			 <td align='right'><input type='text' size='12' maxlength='12' style='text-align:right' class='inputG' name='generica' value='$montoGen' readonly/>Bs.F</td>         

		   </tr>";

		    }//////////////////////////////////////////////////////////////////////////////////////

		   }//// **** Obtengo Partidas Tipo "D" 301-01-01-01	**** ////

		   if($fielDet['partida']!=00){

		   $s="SELECT * FROM pv_partida WHERE cod_partida='".$fielDet['cod_partida']."'";

	       $q=mysql_query($s) or die ($s.mysql_error());

	       $f=mysql_fetch_array($q);

			  $contTotal = $cont;

	          $monto = $fielDet['MontoPresupuestado'];

			  $montoDet=number_format($monto,2,',','.');

	          $total = $monto + $total;

			  $totalM=number_format($total,2,',','.');

	          $codigo_detalle = $fielDet['cod_partida'];

		      echo "<tr class='trListaBody'>

				  <td align='center'>".$fielDet['cod_partida']."</td>

				  <td>".$f['denominacion']."</td>

				  <td align='center'>".$fielDet['Estado']."</td>

				  <td align='center'>".$fielDet['tipo']."</td>

				  <td align='right'><input type='text' size='12' class='montoA' style='text-align:right' maxlength='12' id='$codigo_partida|$codigo_generica' value='$montoDet' name='".$fielDet['IdAnteProyectoDet']."' onchange='sumarPartida(this.value, this.id);' onfocus='obtener(this.value);' readonly/>Bs.F</td>         

	   </tr>";

	   }

}}echo"<tr><td colspan='3'></td>

               <td align='right'><b>Total:</b></td>

			   <td align='center' class='trListaBody'><b><input type='hidden' id='total' name='total' size='15' value='$total'/>";

		       echo"<input type='text' style='text-align:right' id='totalAnt' class='inputT' name='totalAnt' size='12' value='$totalM' readonly/> Bs.F</b></td>

		   </tr>";

//	------------------------------------------------------------------------------------------------------------

//	------------------------------------------------------------------------------------------------------------

$rows=(int)$rows;

echo "

	<script type='text/javascript' language='javascript'>

		totalLista($registros);

		totalLotes($registros, $rows, ".$limit.");

	</script>";

?>

</table>

<script type="text/javascript" language="javascript">

	totalPuestos(<?=$rows?>);

</script>

</div>

<!--////////////////// ***** ****** ****** **** ****************  PESTAÑA N 1  ************  ******** ********** ********* //////////////////// -->

<!--////////////////// ***** ****** ****** **** ****************               ************  ******** ********** ********* //////////////////// -->

<div name="tab1" id="tab1" style="display:block;">

<body>

<?php

$limit=(int) $limit;

echo "

<input type='hidden' name='codantepres' id='codantepres' value='".$codantepres."'/>

<input type='hidden' name='filtro' id='filtro' value='".$filtro."' />

<input type='hidden' name='regresar' id='regresar' value='".$regresar."' />

<input type='hidden' name='forganismo' id='forganismo' value='".$forganismo."' />

<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />";

////////////////////////////////////////////////////////////////////////////////////////////

include "gmsector.php";

//echo"<br>Post:".$_POST["registro"];

$sql="SELECT * FROM pv_antepresupuesto WHERE CodAnteproyecto='".$_GET['registro']."'";

$query=mysql_query($sql) or die ($sql.mysql_error());

$rows=mysql_num_rows($query);

if($rows!=0){

 $field=mysql_fetch_array($query);

 list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaAnteproyecto']); $fAntp=$d.'-'.$m.'-'.$a;

 list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaInicio']); $fInicio=$d.'-'.$m.'-'.$a;

 list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaFin']); $fFin=$d.'-'.$m.'-'.$a;

 $limit=(int) $limit;

}

?>

<div style="width:800px" class="divFormCaption">Informaci&oacute;n de Anteproyecto</div>

<table width="800" class="tblForm">

<tr>

	<td width="123"></td>

	<td width="151" class="tagForm">Organismo:</td>

	<? $sql2=mysql_query("SELECT * FROM mastorganismos WHERE CodOrganismo='".$field['Organismo']."'");

	   if(mysql_num_rows($sql2)!=0){$field2=mysql_fetch_array($sql2);}?>

	<td width="342"><input name="organismo" id="organismo" value="<?=$field2['Organismo']?>" maxlength="80" size="60" readonly/></td>

	<td colspan="2"></td>

</tr>

</table>

<table width="800" class="tblForm">

<tr><td height="2"></td></tr>

<tr>

 <td width="199"></td>

 <td width="75" align="right">Nro. Gaceta:</td>

   <? 

	  if($field['FechaGaceta']!='0000-00-00'){list($a,$m,$d)=SPLIT('[/.-]', $field['FechaGaceta']); $fGaceta=$d.'-'.$m.'-'.$a;}

	  if($field['FechaDecreto']!='0000-00-00'){list($a,$m,$d)=SPLIT('[/.-]', $field['FechaDecreto']); $fDecreto=$d.'-'.$m.'-'.$a;}

	?>

 <td width="70"><input name="gaceta" id="gaceta" type="text" size="8" value="<?=$field[NumeroGaceta]?>" readonly/>*</td>

 <td width="65" align="right">F. Gaceta:</td>

 <td width="150"><input name="fgaceta" id="fgaceta" type="text" size="8" maxlength="10" value="<?=$fGaceta?>" readonly/>*<i>(dd-mm-aaaa)</i></td>

 <td colspan="2" width="200"></td>

</tr>

<tr>

 <td width="192"></td>

 <td width="75" align="right">Nro. Decreto:</td>

 <td width="70"><input name="decreto" id="decreto" type="text" size="8" value="<?=$field[NumeroDecreto]?>" readonly/>*</td>

 <td width="65" align="right">F. Decreto:</td>

 <td width="150"><input name="fdecreto" id="fdecreto" type="text" size="8" maxlength="10" value="<?=$fDecreto?>" readonly/>*<i>(dd-mm-aaaa)</i></td>

</tr>

<tr><td height="2"></td></tr>

</table>

<table width="800" class="tblForm">	

<tr>

   <td width="163"></td>		

   <td class="tagForm">Ejercicio P.:</td>

   <td><? $ano = date(Y); // devuelve el ao $fcreacion= date("d-m-Y");//Fecha de Creacin ?>

		<input name="anop" type="text" id="anop" size="8" value="<?=$field[EjercicioPpto]?>" readonly /> 

		F.Creaci&oacute;n:<input name="fcreacion" type="text" id="fcreacion" size="9" value="<?=$fAntp?>" readonly/> 

		<? if($field[Estado]==RV){$estado=Revisado;}?>

		 Estado:<input name="estado" type="text" id="estado" size="11" value="<?=$estado?>" readonly/></td>

   <td></td>

</tr>

</table>

<table width="800" class="tblForm">

<tr>

  <td width="105"></td>

  <td width="181" class="tagForm">Sector:</td><? $sql="SELECT * FROM pv_sector WHERE cod_sector='".$field['Sector']."'";

												 $qry=mysql_query($sql) or die ($sql.mysql_error());

												 if(mysql_num_rows($qry)!=0){$fieldSector=mysql_fetch_array($qry);}

											  ?>

  <td width="520"><input name="sector" id="sector" value="<?=$fieldSector['descripcion']?>" size="70" readonly/></td>

</tr>

<tr>

  <td width="83"></td>

  <td class="tagForm">Programa:</td><? $sql="SELECT * FROM pv_programa1 WHERE id_programa='".$field['Programa']."'";

												 $qry=mysql_query($sql) or die ($sql.mysql_error());

												 if(mysql_num_rows($qry)!=0){$fieldPrograma=mysql_fetch_array($qry);}

											  ?>

  <td><input name="programa" id="programa" value="<?=$fieldPrograma[descp_programa]?>" size="70" readonly/></td>

</tr>

<tr>

  <td width="83"></td>

  <td class="tagForm">Actividad:</td><? $sql="SELECT * FROM pv_subprog1 WHERE id_sub='".$field['SubPrograma']."'";

												 $qry=mysql_query($sql) or die ($sql.mysql_error());

												 if(mysql_num_rows($qry)!=0){$fieldSubprog=mysql_fetch_array($qry);}

											  ?>

  <td><input name="subprograma" id="subprograma" value="<?=$fieldSubprog[descp_subprog]?>" size="70" readonly/></td>

</tr>

<tr>
  <td width="83"></td>
  <td class="tagForm">Unidad Ejecutora:</td>
  <td><input type="text" name="unidadejecutora" id="unidadejecutora" value="<?=$field['UnidadEjecutora'];?>" size="70" readonly/></td>
</tr>

<tr>

  <td width="83"></td>

  <td class="tagForm">Proyecto:</td><? $sql="SELECT * FROM pv_proyecto1 WHERE id_proyecto='".$field['Proyecto']."'";

									   $qry=mysql_query($sql) or die ($sql.mysql_error());

												 if(mysql_num_rows($qry)!=0){$fieldProyecto=mysql_fetch_array($qry);}

											  ?>

  <td><input name="proyecto" id="proyecto" value="<?=$fieldProyecto[descp_proyecto]?>" size="70" readonly/></td>

</tr>

<tr>

  <td width="83"></td>

  <td class="tagForm">Sub-Programa:</td><? $sql="SELECT * FROM pv_actividad1 WHERE id_actividad='".$field['Actividad']."'";

												 $qry=mysql_query($sql) or die ($sql.mysql_error());

												 if(mysql_num_rows($qry)!=0){$fieldActividad=mysql_fetch_array($qry);}

											  ?>

  <td><input name="actividad" id="actividad" value="<?=$fieldActividad[descp_actividad]?>" size="70" readonly/></td>

</tr>

<tr><td></td></tr>

</table>

<div style="width:800px" class="divFormCaption">Duraci&oacute;n de Presupuesto</div>

<table width="800" class="tblForm"> 

<tr><td></td></tr>

<tr><td width="60"></td>

	<td class="tagForm">F. Inicio:</td>

	<td colspan="2"><input  name="finicio" type="text" id="finicio" size="10" maxlength="10" value="<?=$fInicio?>" readonly/>*(dd-mm-yyyy)</td>

</tr>	

<tr><td></td>

	<td class="tagForm">F. Termino:</td>

	<td colspan="2"><input name="ftermino" type="text" id="ftermino" size="10" maxlength="10" value="<?=$fFin?>" readonly/>*(dd-mm-yyyy)</td>

</tr>	

<tr><td></td>

	<td class="tagForm">Duraci&oacute;n:</td><? $date1=strtotime($field['FechaInicio']);

												$date2=strtotime($field['FechaFin']);

												$s = ($date1)-($date2);

												$d = intval($s/86400);

												$s -= $d*86400;

												$h = intval($s/3600);

												$s -= $h*3600;

												$m = intval($s/60);

												$s -= $m*60;

												

												$dif= (($d*24)+$h).hrs." ".$m."min";

												$dif2= abs($d.$space); $dif2= $dif2 + 1; ?>

	<td><input name="vacantes" type="text" id="vacantes" size="6" maxlength="3" style="text-align:right" value="<?=$dif2?>" readonly/> d&iacute;as.</td>

	<td colspan="1"></td>

</tr>

<tr><td></td></tr>

</table>

<div style="width:800px" class="divFormCaption">Monto de Presupuesto</div>

<table width="800" class="tblForm">

<tr><td></td></tr>

<tr><td></td>

  <td class="tagForm">Monto Total Anteproyecto:</td><? $totalAnt=number_format($total,2,',','.');?>

  <td ><input name="totalAnteproyecto" type="text" id="totalAnteproyecto" size="20" maxlength="15" style="text-align:right" value="<?=$totalAnt?>" readonly/>Bs.F</td>

</tr>

<tr><td></td>

  <td class="tagForm">Monto Autorizado:</td>

  <td ><input name="montoautori" type="text" id="montoautori" size="20" maxlength="15"  style="text-align:right"  readonly/>Bs.F</td>

</tr>

<tr><td></td>

  <td class="tagForm">Diferencia:</td><? $montoRestante= $field[MontoPresupuestado] - $total;?>

  <td><input name="diferencia" id="diferencia" type="text" size="20" maxlength="15" style="text-align:right"  readonly/>Bs.F</td>

</tr>

<tr><td></td></tr>

<tr><td></td>

   <td class="tagForm">Preparado por:</td>

   <td><input name="prepor" id="prepor" type="text" size="60" value="<?=htmlentities($field[PreparadoPor])?>" readonly/></td>

<tr>

<tr><td></td>

   <td class="tagForm">Aprobado por:</td>

   <td><input name="apropor" id="apropor" type="text" size="60" value="<?=$field[AprobadoPor]?>" readonly/></td>

</tr>

<tr><td></td>

	<td class="tagForm">&Uacute;ltima Modif.:</td>

	<td colspan="1">

	<?

	 if($field[UltimaFechaModif]!='0000-00-00 00:00:00'){

	   $UFM=$field['UltimaFechaModif'];

	   $USUA=$field['UltimoUsuario'];

	 }

	 ?>

	<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$USUA?>" readonly />

	<input name="ult_fecha" type="text" id="ult_fecha" size="22" value="<?=$UFM?>" readonly />		</td>

</tr>

</table>

</div>

<div id="tab2" style="display:none;">

</div>

<center>

 <input type="hidden" name="estado" id="estado" value="Aprobado"/>

 <input type="hidden" name="CodAnteproyecto" id="CodAnteproyecto" value="<?=$field['CodAnteproyecto']?>"/>

 <input type="submit" name="btAprovado" id="btAprobado" value="Aceptar"/>

 <input type="button" name="btCerrar" id="btCerrar" value="Cerrar" onclick="cargarPagina(this.form,'anteproyecto_aprobar.php?limit=0')"/> 

 <input type="hidden" name="filas" id="filas" value="<?=$rows?>" />

</center></div>

</form>

</body>

</html>



<table><tr><td valign="middle"><iframe style="border:none" align="left"</td></tr>

