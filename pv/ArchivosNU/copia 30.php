<? //------------------------------------------------------------------------------------------------------------
///////////////////************ MOSTRAR PARTIDAS ASOCIADAS AL ANTEPROYECTO *************/////////////////////
//////////////////************* CARGA LOS DATOS DE LA TABLA "pu_antepresupuestodet" ****///////////////////// 
//------------------------------------------------------------------------------------------------------------
//$sql="SELECT MAX(CodAnteproyecto) FROM pu_antepresupuesto";
$sql="SELECT CodAnteproyecto FROM pu_antepresupuesto WHERE EjercicioPpto='".$_POST['ejercicioPpto']."'";
$qry=mysql_query($sql) or die ($sql.mysql_error());
if(mysql_num_rows($qry)!=0){
  $field=mysql_fetch_array($qry);
  $sqlDet="SELECT * FROM pu_antepresupuestodet WHERE CodAnteProyecto='".$field['CodAnteproyecto']."' ORDER BY cod_partida";
  $query=mysql_query($sqlDet) or die ($sqlDet.mysql_error());
  $rows=mysql_num_rows($query);
  for($i=0; $i<$rows; $i++){
   $fielDet=mysql_fetch_array($query);
   if($codpartida!=$fielDet['cod_partida']){
    $sqlPartida="SELECT * FROM pv_partida WHERE cod_partida='".$fielDet['cod_partida']."'";
    $qryPartida=mysql_query($sqlPartida) or die ($sqlPartida.mysql_error());
    if(mysql_num_rows($qryPartida)!=0){
     $fieldPartida=mysql_fetch_array($qryPartida); /// ********  ORDENO PARTIDAS 
	 $valor=$fieldPartida[cod_tipocuenta];
	 if($cod_partida!=$fieldPartida['cod_partida']){ ///  ORDENA PARTIDAS POR TIPOCUENTA -- PARTIDA=0  -- GENERICA=0
	  if(($fielDet['tipocuenta']!=$valor) and ($fieldPartida['partida1']!=0) and ($fieldPartida['generica']==0) and ($fieldPartida['especifica']==0)){
	   $codigo_partida = $fielDet['cod_partida'];$cont= $cont +1;/////*******************///////
	   echo "<tr class='trListaBody6'>
		<td align='center'>".$fielDet['cod_partida']."</td>
		<td>".$fieldPartida['denominacion']."</td>
		<td align='center'>".$fielDet['Estado']."</td>
		<td align='center'>".$fieldPartida['tipo']."</td>
		<td align='right'><input type='text' size='15' maxlength='12' id='".$codigo_partida."' name='".$fielDet['IdAnteProyectoDet']."' value='".$fielDet['MontoAsignado']."' readonly/>Bs.F</td>         
	   </tr>";$valor=$fieldPartida[cod_tipocuenta]; $tcuenta=$fielDet[tipocuenta]; $partida=$fielDet[partida]; $generica=$fielDet[generica];
	   }else{ ////  ORDENA PARTIDAS POR TIPOCUENTA -- PARTIDA!=0  -- GENERICA!=0 -- ESPECIFICA!=0
		 if(($fielDet['tipocuenta']!=$valor) and ($fieldPartida['partida1']!=0) and ($fieldPartida['generica']!=0) and ($fieldPartida['especifica']==0)){
		   $codigo_generica = $fielDet['cod_partida'];/////*******************///////
		   echo "<tr class='trListaBody5'>
			 <td align='center'>".$fielDet['cod_partida']."</td>
			 <td>".$fieldPartida['denominacion']."</td>
			 <td align='center'><b>".$fielDet['Estado']."</td>
			 <td align='center'><b>".$fieldPartida['tipo']."</td>
			 <td align='right'><input type='text' size='15' maxlength='12'id='".$codigo_generica."' name='".$fielDet['IdAnteProyectoDet']."' value='".$fielDet['MontoAsignado']."' readonly/>Bs.F</td>         
	             </tr>";$valor=$fieldPartida[cod_tipocuenta]; $tcuenta=$fielDet[tipocuenta]; $partida=$fielDet[partida]; $generica=$fielDet[generica];
		   }else{ ////  ORDENA PARTIDAS POR TIPO=DETALLE "D"
			$contTotal = $cont;
			$monto = $fielDet['MontoAsignado'];
			$total = $monto + $total;
			$valor = $fielDet[tipocuenta];
			$codigo_detalle = $fielDet['cod_partida'];
			echo "<tr class='trListaBody' onclick='mClk(this,\"registro2\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$fielDet['IdAnteProyectoDet']."'>
			  <td align='center'>".$fielDet['cod_partida']."</td>
			  <td>".$fieldPartida['denominacion']."</td>
			  <td align='center'>".$fielDet['Estado']."</td>
			  <td align='center'>".$fieldPartida['tipo']."</td>
			  <td align='left'><input type='text' size='15' class='montoA' maxlength='12' id='$codigo_partida|$codigo_generica' value='".$fielDet['MontoAsignado']."' name='".$fielDet['IdAnteProyectoDet']."' onchange='sumarPartida(this.value, this.id);' onfocus='obtener(this.value);'/>Bs.F</td>         
			  </tr>";
			  	  
		   }
}}}}}}echo"<tr><td colspan='3'></td>
               <td align='right'><b>Total:</b></td>
			   <td align='center' class='trListaBody'><input type='hidden' id='total' name='total' size='15' value='$total'/>";
			                                           
			                                     echo"<input type='text' id='totalAnt' name='totalAnt' size='15' value='$total' readonly/> Bs.F</td>
		   </tr>";
		    $sqlPrueba = "SELECT * FROM pu_antepresupuesto WHERE CodAnteproyecto='".$field['0']."'";
			$qryPrueba = mysql_query($sqlPrueba) or die ($sqlPrueba.mysql_error());
			$fieldPrueba = mysql_fetch_array($qryPrueba);
//	------------------------------------------------------------------------------------------------------------
//	------------------------------------------------------------------------------------------------------------