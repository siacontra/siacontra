<?PHP
		/*header("Content-Type: application/vnd.ms-excel");header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("content-disposition: attachment;filename=Reporte.xls");*/		
		session_start();

		
		$meses		  = $_POST['meses'];
		$ultimoDiaMes = $_POST['ud'];
		$anio		  = $_POST['anio'];
		$partidaDesde = $_POST['partidaDesde'];
		$partidaHasta = $_POST['partidaHasta'];
		
		$arrayDesde = str_split($partidaDesde);
		
		$tipoD=$arrayDesde[0];
		$partidaD=$arrayDesde[1].$arrayDesde[2];
		
		
		$arrayHasta = str_split($partidaHasta);
		
		$tipoH=$arrayDesde[0];
		$partidaH=$arrayHasta[1].$arrayHasta[2];
		
		

		
		$meses=explode(',',$meses);
		$ultimoDiaMes=explode(',',$ultimoDiaMes);
		
		
		
		include("../clases/MySQL.php");
		include("../clases/Excel.php");	
		include("objConexion.php");			
		
			
		/*------------------CREACIÓN DEL OBJETO ASIGNACION-------------------------*/
		/*$objAsig = new Asignacion($simObjBD);// creamos la instancia de la clase
		$datosAsig = $objAsig->buscarAsignacion($partidaDesde,$partidaHasta);*/// verificamos los datos de la funcion
		
		$sql="SELECT a.`cod_partida`,a.`partida1`,a.`generica`,a.`especifica`,a.`subespecifica`,
				a.`denominacion`,a.`cod_tipocuenta`, b.`MontoAprobado`
				from pv_partida as a
				LEFT join pv_presupuestodet as b on b.cod_partida = a.`cod_partida`
				where 
				(`cod_tipocuenta` ='".$tipoD."' AND `partida1`>='".$partidaD."') AND 
				(`cod_tipocuenta` ='".$tipoD."' AND `partida1`<='".$partidaH."')";			
		
				$objConexion->ejecutarQuery($sql);
		$resp = $objConexion->getMatrizCompleta();
		
		
		
		$partida= array();		
		$generica=array();			
		$especifica=array();	
		$subEspecifica=array();	
		
		for($i=0; $i<count($resp); $i++)
		{
				$tipo[$i]			=	$resp[$i]['cod_tipocuenta'];
				$partida[$i]		=	$resp[$i]['partida1'];
				$generica[$i] 		= 	$resp[$i]['generica'];
				$especifica[$i] 	= 	$resp[$i]['especifica'];
				$subEspecifica[$i] 	= 	$resp[$i]['subespecifica'];
				$denominacion[$i]	=	$resp[$i]['denominacion'];
				$monto[$i]			=	$resp[$i]['MontoAprobado'];
		}
		
		
		



	  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title></title>


<style type="text/css">
.botonExcel{cursor:pointer;}
</style>
</head>

<div  style="width:790px; height:300px; overflow:scroll;  position:static; "> 
<body>

<table width="400%"  border="1px"  cellpadding="0" cellspacing="0" bordercolor="#666666" id="Exportar_a_Excel" style=" border-collapse:collapse; background-color: #FFF;border: 1px solid #999;">
  <tr>
    <td colspan="6%">DESCRIPCI&Oacute;N</td>
    <td width="6%"><?php echo 'SALARIO AL '.$meses[0]; ?>    </td>
	<?PHP for($j=0; $j<count($meses); $j++){ ?>
    	<td ><?php echo 'EGRESOS AL '.$ultimoDiaMes[$j].'/'.$meses[$j].'/'.$anio;?> </td>
    	<td ><?php echo 'DISPONIBILIDAD AL '.$ultimoDiaMes[$j].'/'.$meses[$j].'/'.$anio;?> </td>
	 <?php }?>
    
  </tr>

  <?php for($i = 0; $i<count($partida); $i++) {  
  					if($i%2==0)
					{
						$color='#FFFFFF';
					}
					else
					{
						$color='#F3F3F3';
					}
  	//$datosP = $obj->disponibilidadPresupuestariaGeneral($datosAsig[$i]['co_cuenta_presupuesto'],$anio,$datosAsig[$i]['nu_sector'],$datosAsig[$i]['nu_programa'],$datosAsig[$i]['nu_sub_programa'],$datosAsig[$i]['nu_proyecto'],$datosAsig[$i]['nu_actividad'],$datosAsig[$i]['co_partida'],$datosAsig[$i]['co_general'],$datosAsig[$i]['co_espesifica'],$datosAsig[$i]['co_sub_especifica'],$datosAsig[$i]['co_ordinal']);// verificamos los datos de la funcion
  ?>
  <tr bgcolor="<?php echo $color; ?>">
    <td width="1%"><?php
						//echo $sqlPartida ="SELECT `partida1` FROM `pv_partida` WHERE `partida1`='$partida[$i]' and `generica`='$generica[$i]' and `especifica`='$especifica[$i]' and`tipo`='$tipo[$i]'";
							
							//echo $partida[$i];
							if($i==0){echo $tipo[0].$partida[0];}
							
							else if($partida[$i]!=$partida[$i-1])
							{
								echo $tipo[$i].$partida[$i];	
							}
							else
							{
								echo '';
							}		
							 //echo $datosAsig[$i]['co_partida']; ?></td>
							 
	<td width="1%"><?php  if($generica[$i]!='00' && $generica[$i]!=$generica[$i-1])
							{
								echo $generica[$i];	
							}
							else
							{
								echo '';
							}		
							 //echo $datosAsig[$i]['co_general']; ?></td>
    <td width="1%"><?php  if($especifica[$i]!= '00' &&$especifica[$i]!=$especifica[$i-1])
							{
								echo $especifica[$i];	
							}
							else
							{
								echo '';
							}		
							 //echo $datosAsig[$i]['co_general']; ?></td>
    <td width="1%"><?php if($subEspecifica[$i]!='00' && $subEspecifica[$i]!=$subEspecifica[$i-1])
							{
								echo $subEspecifica[$i];	
							}
							else
							{
								echo '';
							}	///echo $datosAsig[$i]['co_espesifica']; ?></td>
    <td width="1%"><?php 
							//para los ordinales
								echo '';
							
							
							//echo $datosAsig[$i]['co_sub_especifica']; ?></td>
   
    <td width="10%" align="left"><?php echo $denominacion[$i]; ?></td>
    <td width="6%" id="<?php echo 'disponibilidad'.$i; ?>" align="right"><?php echo number_format($monto[$i],2, ',', '.'); ?> </td>
	<?PHP for($j=0; $j<count($meses); $j++){ ?>
		<td id="<?php echo 'egreso'.$i.'_'.$j; ?>" ondblclick="mostrarCampoText('<?php echo 'egreso'.$i.'_'.$j; ?>','<?php echo $i;?>','<?php echo $j;?>')" align="right" style="cursor:pointer" bgcolor="#FFECD7">0,00</td>
	<td id="<?php echo 'disponibleMes'.$i.'_'.$j; ?>" align="right">0,00</td>
	<?PHP }?>
    
  </tr>
  <?php } ?>
</table>


<!--<form action="../comunes/exportarExcel.php" method="post" target="_blank" id="FormularioExportacion">

<p>Exportar a Hoja de calculo -> <img src="../imagenes/arriba.png" width="32" height="32" class="botonExcel" /></p>


<input type="hidden" id="datos_a_enviar" name="datos_a_enviar"  />
</form>
-->



</body>
</div>
</html>