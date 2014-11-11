<? //	MUESTRO LA TABLA
    $MAXLIMIT=30;
   $registros=$rows;
   if($registros!=0){ 
  	for($i=0; $i<$registros; $i++){
  	   $field=mysql_fetch_array($QRY); 
	   echo"CodAjuste=".$field['CAjuste'];echo"CodPresupuesto=".$field['CPresupuesto'];
	    $SQL="SELECT p.cod_partida,p.partida1 as partida, p.cod_tipoCuenta as tcuenta 
	          FROM pv_partida p, pv_ajustepresupuestariodet a 
			 WHERE a.cod_partida=p.cod_Partida AND 
			       a.CodPresupuesto='".$field['CPresupuesto']."' AND 
				   a.CodAjuste='".$field['CAjuste']."'";
	   $QRY=mysql_query($SQL) or die ($SQL.mysql_error());
	   $FIELD=mysql_fetch_array($QRY);
	   $mostrar='00.00.00';
	   $mostrar2=$FIELD['tcuenta'].$FIELD['partida'].".".$mostrar;
	   if($field['TAjuste']=='DI'){$tAjuste=Disminucion;}else{$tAjuste=Incremento;}
	   if($field['Estado']=='PR'){$estado=Pendiente;}else{$estado=Aprobado;}
	   $monto=$field['Monto']; $monto=number_format($monto,2,',','.');
      echo "
      <tr class='trListaBody' onclick='mClk(this,\"registro\");' id='".$field['CAjuste']."'>
	  <td align='center'>".$field['CPresupuesto']."</td>";
	  list($a, $m, $d)=SPLIT( '[/.-]', $field['FAjuste']); $fAjuste=$d.'-'.$m.'-'.$a;
      list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaFin']); $fFin=$d.'-'.$m.'-'.$a;
      echo"
	  <td align='center'>".$field['CAjuste']."</td>
	  <td align='center'>$mostrar2</td>
	  <td align='center'>$tAjuste</td>
	  <td align='center'>$fAjuste</td>
	  <td align='center'>$estado</td>
	  <td align='center'>$monto</td>
      </tr>";
}}
?>