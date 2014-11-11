<? 
include "conexion.php";
$sql="SELECT MAX(UltimaFecha) FROM pu_antepresupuesto";// consulta adquiere datos ultima modificacion
$qry=mysql_query($sql) or die ($sql.mysql_error());
$field=mysql_fetch_array($qry);
$sqlConsulta="SELECT * FROM pu_antepresupuesto WHERE UltimaFecha='".$field['0']."'";// consulta de tabla para obtener datos segun condicion //
$qryConsulta=mysql_query($sqlConsulta) or die ($sqlConsulta.mysql_error());
if(mysql_num_rows($qry)!=0){
 $fieldConsulta=mysql_fetch_array($qryConsulta);
 $sqlDet="SELECT * FROM  pu_antepresupuestodet WHERE CodAnteProyecto='".$fieldConsulta['CodAnteproyecto']."' ORDER BY cod_partida DESC";
 $query=mysql_query($sqlDet) or die ($sqlDet.mysql_error());
 $rows=mysql_num_rows($query);
 for($i=0; $i<$rows; $i++){
  $fielDet=mysql_fetch_array($query);
	// CODIGO DE PRUEBA
	if($fielDet['tipo']=='D'){
	  $capt= $fielDet['partida']; $capt2=$fielDet['generica']; $capt2=$fielDet['tipocuenta']; 
	  $monto= $monto + $fielDet['MontoAsignado'];
	}else{	
	  if(($fielDet['tipo']=='T') and ($fielDet['partida']==$capt) and ($fielDet['generica']==0) and ($fielDet['tipocuenta']==$capt3)){
	     $monto1= $monto1 + $monto;
	  }else{
		 $montoT1= $monto1 + $montoT1; 
	  }
	  if(($fielDet['tipo']=='T') and ($fielDet['partida']==$capt) and ($fielDet['generica']==0) and ($fielDet['tipocuenta']==$capt3)){
	     $monto2= $monto + $monto2;
	  }else{
	     $montoT2= $monto2;
	  }
	}
		  // FIN CODIGO PRUEBA
}}
echo"<tr><td colspan='3'></td><td align='right'><b>Total:</b></td><td align='center' class='trListaBody'>"; echo"".$montoT2; echo" Bs.F</td></tr>";
?>