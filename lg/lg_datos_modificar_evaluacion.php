<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
//extract($_POST);
//extract($_GET);
//	------------------------------------
include("../lib/fphp.php");
include("../lib/lg_fphp.php");
//	------------------------------------
//list($codorganismo, $codrequerimiento, $secuencia, $numero) = split("[.]", $registro);
//	------------------------------------

//**********INCLUSION DE ARCHIVOS PARA CONSULTA*******//
include_once ("../clases/MySQL.php");
	
include_once("../comunes/objConexion.php");
//**************************************************//

	
	foreach($_GET as $nombreCampo => $valor)
	{
		$$nombreCampo = $valor;
	}
	
	/************VERIFICAO EL ESTADO DE LA ADJUDICACION*********************/
	$sqlEstado = "SELECT A.Estado
				FROM lg_informeadjudicacion as A
				join lg_informerecomendacion as B on A.CodInformeRecomendacion=B.CodInformeRecomendacion
				join lg_evaluacion as C on C.CodEvaluacion=B.CodEvaluacion
				where A.Estado='AD' and C.CodEvaluacion=".$codEvaluacion;

	$resultadoEstadoAdju = $objConexion->consultar($sqlEstado,'fila');//
	
	if($resultadoEstadoAdju['Estado'] == 'AD')
	{
	
		echo '<script language="javascript">
				alert(\'La orden de compra para esta evaluación ya fue generada\nNo se puede modificar la evaluación\');
				location.href="lg_evaluacion_busqueda.php";
			</script>';
	
	}
	/******************************************************/
	
	/************VERIFICAO SI SE DECLARO DESIERTO*********************/
	$sqlDesierto = "SELECT A . *
					FROM lg_declarar_desierto AS A
					JOIN lg_informerecomendacion AS B ON A.CodInformeRecomendacion = B.CodInformeRecomendacion
					JOIN lg_evaluacion AS C ON C.CodEvaluacion = B.CodEvaluacion
					WHERE C.CodEvaluacion =".$codEvaluacion;

	$resultadoDesierto = $objConexion->consultar($sqlDesierto,'fila');//
	
	if(count($resultadoDesierto) >= 1)
	{
	
		echo '<script language="javascript">
				alert(\'Este procedimiento fue declarado desierto\');
				location.href="lg_evaluacion_busqueda.php";
			</script>';
	
	}
	/******************************************************/
	
	$ofertaProveedor = array("","A","B","C","D","F","G","H","I","J","K","L","M","Ñ","O","P","Q","R","S","T","U","V");

	$sqlAsistente ="select p.Nombres, p.Apellido1,p.Apellido2,p.CodPersona
	from lg_evaluacion as A
	JOIN mastpersonas p ON p.CodPersona = (select CodPersonaAsistente from lg_evaluacion where CodEvaluacion=".$codEvaluacion.") and A.CodEvaluacion=".$codEvaluacion;
	
	$asistente = $objConexion->consultar($sqlAsistente ,'fila');
	
	$sqlDirector="select p.Nombres, p.Apellido1,p.Apellido2,p.CodPersona
	from lg_evaluacion as A
	JOIN mastpersonas p ON p.CodPersona in (select CodPersonaDirector from lg_evaluacion where CodEvaluacion=".$codEvaluacion.")and A.CodEvaluacion=".$codEvaluacion;
	
	$director= $objConexion->consultar($sqlDirector ,'fila');
//sum(B.PP+B.TotalPuntajeCuali) as TotalCualiCuanti
		$sql6 = "SELECT A.CodEvaluacion, p.NomCompleto AS NomProveedor, p.CodPersona AS CodProveedor, C.CodActaInicio,
				B.PuntajeRequeTec, B.PuntajeTiempoEntrega, B.PuntajeCondicionPago, B.TotalPuntajeCuali, B.PMO_POE, B.PP, 
			 	A.ObjetoEvaluacion, A.CriterioCualitativo, A.CriterioCuantitativo, A.Conclusion, A.Recomendacion
				FROM lg_evaluacion AS A
				JOIN lg_cualitativacuantitativa AS B ON A.CodEvaluacion = B.CodEvaluacion
				JOIN lg_actainicio AS C ON C.CodActaInicio = A.CodActainicio
				JOIN lg_requedetalleacta AS D ON D.CodActaInicio = A.CodActaInicio
				JOIN mastpersonas p ON ( B.CodProveedor = p.CodPersona )
				JOIN mastproveedores mp ON ( B.CodProveedor = mp.CodProveedor )
				WHERE A.CodEvaluacion =".$codEvaluacion."
				GROUP BY A.CodEvaluacion, NomProveedor, CodProveedor, C.CodActaInicio, B.PuntajeRequeTec, B.PuntajeTiempoEntrega, B.PuntajeCondicionPago, B.TotalPuntajeCuali, B.PMO_POE, B.PP, A.ObjetoEvaluacion, A.CriterioCualitativo, A.CriterioCuantitativo, A.Conclusion, A.Recomendacion";

		$resultado6 = $objConexion->consultar($sql6,'matriz');

		$sql7 = "SELECT C.CodActaInicio
					FROM lg_evaluacion AS A
					JOIN lg_cualitativacuantitativa AS B ON A.CodEvaluacion = B.CodEvaluacion
					JOIN lg_actainicio AS C ON C.CodActaInicio = A.CodActainicio
					JOIN lg_requedetalleacta AS D ON D.CodActaInicio = A.CodActaInicio
					JOIN mastpersonas p ON ( B.CodProveedor = p.CodPersona )
					JOIN mastproveedores mp ON ( B.CodProveedor = mp.CodProveedor )
					WHERE A.CodEvaluacion =".$codEvaluacion."
					GROUP BY C.CodActaInicio";
					
		$resultado7 = $objConexion->consultar($sql7,'matriz');
		
		$sql4 = "select CodRequerimiento, Secuencia
					from lg_requedetalleacta where CodActaInicio=".$resultado6[0]['CodActaInicio'];
				
		$resultado4 = $objConexion->consultar($sql4,'matriz');
		
		$condicionRequerimiento = array();
		$condSecuenReque = array();
		
		for($i= 0; $i < count($resultado4); $i++)
		{
			
			$condicionRequerimiento[$i] = $resultado4[$i]['CodRequerimiento'];
			$condSecuenReque[$i] = $resultado4[$i]['Secuencia'];
		}
		
		$cadenaCondicionReque = implode(',',$condicionRequerimiento);
		$cadenaCondicionSecue = implode(',',$condSecuenReque);
		$CodActaInicio = $codActa;
		

						
	$sql = "SELECT 
				c.*,
				p.NomCompleto AS NomProveedor

			FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
				INNER JOIN mastpersonas p ON (c.CodProveedor = p.CodPersona)
				INNER JOIN mastproveedores mp ON (c.CodProveedor = mp.CodProveedor)
				
				
			WHERE
				rd.CodRequerimiento in (".$cadenaCondicionReque.") AND
				c.Secuencia in (".$cadenaCondicionSecue.")
				GROUP BY p.NomCompleto";
				

	$resp = $objConexion->consultar($sql,'matriz');//nombre de los proveedores sin repetir
	
	$sql2 = "SELECT 
				c.*,
				p.NomCompleto AS NomProveedor, d.Descripcion
			FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
				INNER JOIN mastpersonas p ON (c.CodProveedor = p.CodPersona)
				INNER JOIN mastproveedores mp ON (c.CodProveedor = mp.CodProveedor)
				INNER JOIN lg_itemmast AS d ON ( d.CodItem = rd.CodItem )
			
			WHERE
				rd.CodRequerimiento in (".$cadenaCondicionReque.") AND
				c.Secuencia in (".$cadenaCondicionSecue.") group by d.Descripcion";



	$resp2 = $objConexion->consultar($sql2,'matriz');//nombre de los item sin repetir



if (count($resp) < 3)
{


	echo "<script language=\"javascript\">
			alert('No se han realizado invitaciones a los tres proveedores');
			location.href='lg_veracta_inicio_evaluacion.php';
		  </script>";
}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<link href="../cp/css1.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />

<link href="css1.css" rel="stylesheet" type="text/css" charset="utf-8" />


<script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/lg_funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/lg_fscript.js"></script>
<script type="text/javascript" language="javascript" src="../js/comun.js"></script>

<!-- INCLUSION DE LOS ARCHIVOS FUNCIONALIDADES CES -->
    <script  type='text/JavaScript' src='../js/vEmergente.js' charset="utf-8"></script>
    
    <script type='text/JavaScript' src='../js/AjaxRequest.js' charset="utf-8"></script>

    <script type='text/JavaScript' src='../js/xCes.js' charset="utf-8"></script>
    
    <!-- <script type='text/JavaScript' src='../js/comun.js' charset="utf-8"></script>--> 
    
    <script type='text/JavaScript' src='../js/dom.js' charset="utf-8"></script>

	<script type='text/JavaScript' src='js/funcionalidadCes.js' charset="utf-8"></script>

    <script type='text/JavaScript' src='js/dom.js' charset="utf-8"></script>
<!--*********************************************** -->


<script type="text/javascript" src="../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js" charset="utf-8"></script>
</head>

<body>
<div id="bloqueo" class="divBloqueo"></div>
<div id="cargando" class="divCargando">
<table>
	<tr>
    	<td valign="middle" style="height:50px;">
			<img src="../imagenes/iconos/cargando.gif" /><br />Procesando...
        </td>
    </tr>
</table>
</div>
<form id="frmentrada"></form>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Modificar Evaluaci&oacute;n Cualitativa-Cuantitativa</td>
		<td align="right"><a class="cerrar" href="#" onclick="location.href='lg_veracta_inicio_evaluacion.php';";>[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<!--<form name="frmentrada" id="frmentrada" onsubmit="return cotizaciones_invitar_cotizar(this, 'cotizaciones_invitar_cotizar');">

<input type="hidden" id="concepto" name="concepto" value="<?=$concepto?>" />
<input type="hidden" id="codorganismo" value="<?=$codorganismo?>" />
<input type="hidden" id="codrequerimiento" value="<?=$codrequerimiento?>" />
<input type="hidden" id="secuencia" value="<?=$secuencia?>" />
<input type="hidden" id="numero" value="<?=$numero?>" />
<table width="1000" class="tblForm">
	<tr>
		<td><input type="text" id="coditem" style="width:60px;" value="<?=$codigo?>" disabled="disabled" /></td>
		<td width="75">Unidad:</td>
		<td width="75"><input type="text" id="codunidad" style="width:60px;" value="<?=$field['CodUnidad']?>" disabled="disabled" /></td>
		<td width="75">Cantidad:</td>
		<td width="75"><input type="text" id="cantidad" style="width:60px;" value="<?=number_format($field['CantidadPedida'], 2, ',', '.')?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td colspan="3" rowspan="2"><textarea id="descripcion" style="width:98%; height:40px;" disabled="disabled"><?=($field['Descripcion'])?></textarea></td>
		<td>C.Costo:</td>
		<td><input type="text" id="ccosto" style="width:60px;" value="<?=$field['CodCentroCosto']?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td>C.Contable:</td>
		<td><input type="text" id="cuenta" style="width:60px;" value="<?=$cuenta?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td align="center" colspan="5">
        	<input type="button" value="Guardar" style="width:80px;" onclick="guardarEvaluacionCualitativa();" />
            <input type="button" value="Cancelar" style="width:80px;" onclick="window.close();" />
      </td>
	</tr>
</table>
</form>-->

<form name="frm_detalle" id="frm_detalle"></form>
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fedoreg" id="fedoreg" value="<?=$fedoreg?>" />
<input type="hidden" name="fbuscar" id="fbuscar" value="<?=$fbuscar?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<div style="width:857px; height:15px" class="divFormCaption">Acta Inicio de la Adquisición</div>
<table width="833" class="tblForm">
	<!--<tr>
		<td class="tagForm" width="204">* N° Consulta de Precios:</td>
		<td width="617">
        	<input type="hidden" id="numeroConsultaPrecio" style="width:100px; font-weight:bold; font-size:12px;" maxlength="10" <?=$disabled_modificar?> />
		</td>
	</tr>-->
	<tr>
		<td class="tagForm"><input type="hidden" id="cantidadProveedores" maxlength="10" value="<? echo count($resp); ?>" /><input type="hidden" id="codEvaluacion" maxlength="10" value="<? echo $codEvaluacion; ?>" /><input type="hidden" id="codActaInicio" maxlength="10" value="" />* Objeto:</td>
		<td><textarea cols="110" rows="6" id="objetoEvaluacion"><? echo $resultado6[0]['ObjetoEvaluacion']; ?></textarea></td>
	</tr>
	<tr style="display:none;">
		<td class="tagForm">*Criterio de Evaluación Cualitativa:</td>
		<td>
            <textarea cols="110" rows="6" id="criterioEvaluacionCualitativa"><? echo $resultado6[0]['CriterioCualitativo']; ?></textarea>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Proveedores:</td>
		<td align="left">
        	<?php

				echo '<table width="600" border="1" cellspacing="1" class="tblLista">
				  <tr align="center">';
				  
					for ($i = 0; $i < count($resp); $i++)
					{
						echo '<th>Oferta '.$ofertaProveedor[$i+1].'</th>';
						
					}
				  echo '</tr><tr>';
			
				for ($i = 0; $i < count($resp); $i++)
				{
					echo '<td>'.$resp[$i]['NomProveedor'].'</td>';
					
				}
				
				  echo '</tr>
				</table>';
			?>
		</td>
	</tr>
	<tr>
		<td colspan="2" >*Cuadro Cualitativo:</td>
    </tr>	
	<tr style=" <?=$display_pa?>">
		<td colspan="2" class="tagForm"><table width="853" height="181" border="0" align="center" cellspacing="1" class="tblLista">
		  <thead>
		    <tr class="">
		      <th width="281" rowspan="2" align="center">ASPECTOS CUALITATIVOS A EVALUAR</th>
		      <!--<th width="125" rowspan="2" align="center">ESCALA/CRITERIO</th>
		      <th width="93" rowspan="2">PUNTUACIÓN</th>-->
		      <th colspan="<? echo count($resp); ?>" align="center">PUNTUACIÓN OBTENIDA POR OFERTA</th>
	        </tr>
		    <tr>
				<?php
                    $i = 0;
					
                   for($i = 0; $i < count($resp);$i++)
				   {
                        ?>
		      <th align="center" width="238">Oferta <?=$ofertaProveedor[$i+1]?>
		       
		        <input type="hidden" id="codProveedor<? echo $i; ?>" name="codProveedor" value="<? echo $resp[$i]['CodProveedor'];?>" />
		        <!-- <?=($field_detalle['NomProveedor'])?> -->
		        <input type="hidden" name="nomProveedor" value="<?=($field_detalle['NomProveedor'])?>" />
	          </th>
		      <?
                     }
                     ?>
	        </tr>
	      </thead>
		  <tr class="trListaBody" style="font-size:10px;cursor:default;">
		    <td>Requerimientos Técnicos (PM: 40)</td>
		   
		    <?php 
			
				for($i = 0; $i < count($resp); $i++)
				{// se agrego el valor 0 para aquellos casos en los que no se cotice 
				// Guidmar Espinoza 14-02-2014
				echo '<td align="center"><input  id="proveedorRequeTec'.$i.'" onblur="sumarAspectoCualitativo('.$i.');" type="text" size="6" maxlength="5" value="'.$resultado6[$i]['PuntajeRequeTec'].'" /></td>';
					
				}
			?>
		    
	      </tr>
		  <tr style="font-size:10px;cursor:default" class="trListaBody">
		    <td>Tiempo de Entrega (PM: 10)</td>
		   
		   
            <?php 
			
				for($i = 0; $i < count($resp); $i++)
				{// se agrego el valor 0 para aquellos casos en los que no se cotice 
				// Guidmar Espinoza 14-02-2014
					echo '<td align="center"><input  id="proveedorTiempoEntrega'.$i.'" onblur="sumarAspectoCualitativo('.$i.');" type="text" size="6" maxlength="5" value="'.$resultado6[$i]['PuntajeTiempoEntrega'].'" /></td>';
					
				}
			?>
		    
		    
	      </tr>
		  <tr style="font-size:10px;cursor:default" class="trListaBody">
		    <td>Condiciones de Pago (5 días después de emitida la factura) (PM: 10)</td>
		   
            	<?php 
			
				for($i = 0; $i < count($resp); $i++)
				{// se el combo list por un cuadro de texto 
				// Guidmar Espinoza 20-02-2014
					echo '<td align="center"><input  id="proveedorCondicionPago'.$i.'" onblur="sumarAspectoCualitativo('.$i.');" type="text" size="6" maxlength="5" value="'.$resultado6[$i]['PuntajeCondicionPago'].'" /></td>';
					
				}
			?>
	      </tr>
          <tr style="background-color: #000066;color: #EFEFEF; font-size:10px;">
		      <td  align="center"  >Total de la Puntuación Técnica (PM: 60)</th>
		      
              <?php 
			
				for($i = 0; $i < count($resp); $i++)
				{
					echo '<td align="center">
					  <input  style="font-weight:bold;" name="textfield10" type="text" id="totalPuntuacionProvee'.$i.'" size="5" maxlength="2" disabled="disabled" />
					</th>';
				}
			?>
          
	      </tr>
		
	    </table></td>
	</tr>
	<tr>
		<td colspan="2" class="tagForm">&nbsp;</td>
		
	</tr>
    <tr style="display:none;">
		<td class="tagForm">*Criterio de Evaluación Cuantitativo:</td>
		<td>
            <textarea cols="110" rows="6" id="criterioEvaluacionCuantitativo">...</textarea>
		</td>
	</tr>
    
	<tr>
		<td colspan="2" class="tagForm">&nbsp;</td>
		
	</tr>
    <tr style="display:none;">
		<td class="tagForm">*Criterio de Evaluación Cuantitativo:</td>
		<td>
            <textarea cols="110" rows="6" id="criterioEvaluacionCuantitativo"><? echo $resultado6[0]['CriterioCuantitativo']; ?></textarea>
		</td>
	</tr>
    <tr>
      <td colspan="2" >Cuadro Cuantitativo:</td>
    </tr>
    <tr>
		<td colspan="2" class="tagForm"><table width="853" height="148" border="0" align="center" cellspacing="1" class="tblLista">
		  <thead>
		    <tr class="">
		      <th width="236" align="center">OFERTA</th>
		      <th width="190" align="center">CONDICIÓN</th>
		      <th width="160">Bs.</th>
		      <th width="151" align="center">PMO/POE</th>
		      <th width="48" height="30" align="center">PMP</th>
		      <th width="47" align="center">P.P.</th>
	        </tr>
	      </thead>
          <?php
		
				$menor = 999999999999999;
				
				for($i = 0; $i <count($resp); $i++)
				{
					$sql5 = "SELECT sum(Total) as total
							FROM lg_cotizacion c
							INNER JOIN lg_requerimientosdet rd ON ( c.CodOrganismo = rd.CodOrganismo
							AND c.CodRequerimiento = rd.CodRequerimiento
							AND c.Secuencia = rd.Secuencia )
							INNER JOIN mastpersonas p ON ( c.CodProveedor = p.CodPersona )
							INNER JOIN mastproveedores mp ON ( c.CodProveedor = mp.CodProveedor )
							INNER JOIN lg_itemmast AS d ON ( d.CodItem = rd.CodItem )
							WHERE rd.CodRequerimiento
							IN ( ".$cadenaCondicionReque." )
							AND c.Secuencia
							IN (".$cadenaCondicionSecue.")
							AND c.CodProveedor = '".$resp[$i]['CodProveedor']."'";
							
					$resp5 = $objConexion->consultar($sql5,'fila');
					
					if ($resp5['total'] == NULL)
					{
						$sql5 = "SELECT sum(Total) as total
							FROM lg_cotizacion c
							INNER JOIN lg_requerimientosdet rd ON ( c.CodOrganismo = rd.CodOrganismo
							AND c.CodRequerimiento = rd.CodRequerimiento
							AND c.Secuencia = rd.Secuencia )
							INNER JOIN mastpersonas p ON ( c.CodProveedor = p.CodPersona )
							INNER JOIN mastproveedores mp ON ( c.CodProveedor = mp.CodProveedor )
							INNER JOIN lg_commoditysub AS d 
							WHERE rd.CodRequerimiento
							IN ( ".$cadenaCondicionReque." )
							AND c.Secuencia
							IN (".$cadenaCondicionSecue.") and (rd.CommoditySub = d.Codigo)  
							AND c.CodProveedor = '".$resp[$i]['CodProveedor']."'";
							
						$resp5 = $objConexion->consultar($sql5,'fila');
						
					}
					
					if (($resp5['total'] < $menor) && ($resp5['total'] > 0))
					{
						$menor = $resp5['total'];
						
					} /*else if ($resp5['total'] == 0) {
					
						$menor = 0.0000;
					}
					*/
					echo '<tr class="trListaBody" style="font-size:10px;cursor:default;">
					<td>'.$resp[$i]['NomProveedor'].'</td>
					<td colspan="2" align="center"><table width="357" border="0" cellspacing="0">
					  <tr style="font-size:10px;">
						<td width="143" align="center">Oferta más Económica</td>
						<td width="80" align="center"><input disabled="disabled" id="ofertaEconomica'.$i.'" size="20" value="" /></td>
						</tr>
					  <tr  style="font-size:10px;">
						<td align="center">Monto Oferta '.$ofertaProveedor[$i+1].'</td>
						<td align="center"><input disabled="disabled" size="20" value="'.number_format($resp5['total'],2,'.','').'" id="oferta'.$i.'" /></td>
						</tr>
					  </table></td>
					<td align="center"><label for="textfield2"></label>
						<input disabled="disabled" id="PMO_POE'.$i.'" value="'.$resp5['total'].'" size="20" />
					  </td>
					<td align="center">40</td>
					<td align="center"><input disabled="disabled" size="20" id="PP'.$i.'"  value="'.$resp5['total'].'" size="5" maxlength="3" /></td>
				  </tr>';
			}
			
			if($menor == 999999999999999)
			{
				$menor = 0.00;
			}
			
			echo '<script language="javascript">';
			
			for($i = 0; $i <count($resultado6); $i++)
			{//'.$menor.';
				echo 'xGetElementById(\'ofertaEconomica'.$i.'\').value=(parseFloat(('.$menor.'))).toFixed(2);
				
					if(xGetElementById(\'oferta'.$i.'\').value > 0)
					{
						var PMO_POE = xGetElementById(\'PMO_POE'.$i.'\').value;
						xGetElementById(\'PMO_POE'.$i.'\').value='.$resultado6[$i]['PMO_POE'].';
						var PP = xGetElementById(\'PP'.$i.'\').value;
						xGetElementById(\'PP'.$i.'\').value = '.$resultado6[$i]['PP'].';
						
					} else {
						
						xGetElementById(\'PMO_POE'.$i.'\').value= 0;
						xGetElementById(\'PP'.$i.'\').value = 0;
						
					}
						';

						
						
				//(($menor/$resp5['total'])*50)
			}
				echo '</script>';
		?>     
        
		  <!--<tr class="trListaBody" style="font-size:10px;cursor:default;">
		    <td>Requerimientos Técnicos</td>
		    <td colspan="2" align="center"><table width="357" border="0" cellspacing="0">
		      <tr style="font-size:10px;">
		        <td width="143" align="center">Oferta más Económica</td>
		        <td width="80" align="center"><input disabled="disabled" id="ofetaEconomica" size="20" /></td>
		        </tr>
		      <tr  style="font-size:10px;">
		        <td align="center">Monto Oferta A</td>
		        <td align="center"><input disabled="disabled" size="20" id="oferta1" /></td>
		        </tr>
		      </table></td>
		    <td align="center"><label for="textfield2"></label>
		      <label for="select">
		        <input disabled="disabled" id="PMO_POE" size="20" />
		      </label></td>
		    <td align="center">50</td>
		    <td align="center"><input disabled="disabled" id="ofetaEconomica3" size="5" maxlength="3" /></td>
	      </tr>-->
          
		  <!-- <tr style="background-color: #000066;color: #EFEFEF; font-size:10px;">
		    <td colspan="3" align="center"  >Total de la Puntuación Técnica
		      </th></td>
		    <td align="center"><input  style="font-weight:bold;" name="totalPuntuacionProveeUno" type="text" id="totalPuntuacionProveeUno2" size="5" maxlength="2" disabled="disabled" />
		      </th></td>
		    <td width="45" align="center"><input  style="font-weight:bold;" name="totalPuntuacionProveeDos" type="text" id="totalPuntuacionProveeDos2" size="5" maxlength="2" disabled="disabled" />
		      </th></td>
		    <td width="50" align="center"><input  name="totalPuntuacionProveeTres" type="text" disabled="disabled" id="totalPuntuacionProveeTres2" style="font-weight:bold;" size="5" maxlength="2" />
		      </th></td>
	      </tr>-->
		  <thead>
	      </thead>
	    </table></td>
	</tr>
    <tr>
      <td colspan="2" align="center" valign="middle" class="">Matriz de Evaluación Final:</td>
    </tr>
    <tr>
      <td colspan="2" align="center" valign="middle" class=""><table width="853" height="148" border="0" align="center" cellspacing="1" class="tblLista">
        <thead>
          <tr class="">
            <th width="186" rowspan="2" align="center">OFERTA</th>
            <th width="333" rowspan="2" align="center">EMPRESA</th>
            <th height="14" colspan="2" align="center">PUNTUACIÓN CRITERIOS</th>
            <th width="93" rowspan="2" align="center">TOTAL PUNTUACIÓN</th>
          </tr>
          <tr class="">
            <th height="15" align="center">CUALITATIVOS</th>
            <th width="111" height="15" align="center">CUANTITATIVOS</th>
          </tr>
        </thead>
       <?
			for($i=0; $i < count($resp);$i++)
			{ 
				  echo '<tr class="trListaBody" style="font-size:10px;cursor:default;">
				  <td>'.$ofertaProveedor[$i+1].'</td>
				  <td align="center">'.$resp[$i]['NomProveedor'].'</td>
				  <td width="112" align="center"><label for="textfield3"></label>
					<label for="select">
					  <input disabled="disabled" id="puntajeCualitativo'.$i.'" value="'.$resultado6[$i]['TotalPuntajeCuali'].'" size="5" />
					</label></td>
				  <td align="center"><input disabled="disabled" id="puntajeCuantitativo'.$i.'"  value="'.$resultado6[$i]['PP'].'" size="5" maxlength="3" /></td>
				  <td align="center"><input disabled="disabled" id="puntajeTotal'.$i.'" size="5" maxlength="3" value="'.($resultado6[$i]['TotalPuntajeCuali']+$resultado6[$i]['PP']).'" /></td>
				</tr>';
				
				
			}
			
			echo '<script language="javascript">';
			
			for($i = 0; $i <count($resp); $i++)
			{
				echo '
					//xGetElementById(\'puntajeTotal'.$i.'\').value = xGetElementById(\'PP'.$i.'\').value;
					//xGetElementById(\'puntajeCuantitativo'.$i.'\').value = xGetElementById(\'PP'.$i.'\').value;';
						

			}
				echo '</script>';
	   ?>
        <!-- <tr style="background-color: #000066;color: #EFEFEF; font-size:10px;">
		    <td colspan="3" align="center"  >Total de la Puntuación Técnica
		      </th></td>
		    <td align="center"><input  style="font-weight:bold;" name="totalPuntuacionProveeUno" type="text" id="totalPuntuacionProveeUno2" size="5" maxlength="2" disabled="disabled" />
		      </th></td>
		    <td width="45" align="center"><input  style="font-weight:bold;" name="totalPuntuacionProveeDos" type="text" id="totalPuntuacionProveeDos2" size="5" maxlength="2" disabled="disabled" />
		      </th></td>
		    <td width="50" align="center"><input  name="totalPuntuacionProveeTres" type="text" disabled="disabled" id="totalPuntuacionProveeTres2" style="font-weight:bold;" size="5" maxlength="2" />
		      </th></td>
	      </tr>-->
        <thead>
        </thead>
      </table></td>
    </tr>
  
    <tr>
		<td class="tagForm">*Conclusi&oacute;n:</td>
		<td>
            <textarea cols="110" rows="6" id="conclusionEvaluacion"><? echo $resultado6[0]['Conclusion']; ?></textarea>
		</td>
	</tr>
     <tr>
		<td class="tagForm">*Recomendaci&oacute;n:</td>
		<td>
            <textarea cols="110" rows="6" id="recomendacionEvaluacion"><? echo $resultado6[0]['Recomendacion']; ?></textarea>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="tagForm gallery clearfix"><div style="display:none;">*Asistente Administradtivo:
			<input type="hidden" id="asistenteActaInicioAux" value="<?=$field_requerimiento['asistenteActaInicio']?>" />
			<input type="text" disabled="disabled" class="disabled" id="NomAsistenteActaInicioAux" style="width:220px;" value="<?=$field_requerimiento['NomAsistenteActaInicio']?>" />
			<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=asistenteActaInicioAux&nom=NomAsistenteActaInicioAux&iframe=true&width=950&height=525" rel="prettyPhoto[iframe0]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />            </a>	</div> </td>
	</tr>
    <tr>
		<td align="right" >*Asistente Administrativo:</td>
		<td align="left" class="gallery clearfix">
			<input type="hidden" id="asistenteEvaluacion" value="<?=$_SESSION["CODPERSONA_ACTUAL"]?>" />
			<input type="text" disabled="disabled" class="disabled" id="NomAsistenteActaInicio"  value="<?=$_SESSION["NOMBRE_USUARIO_ACTUAL"]?>" size="80" />
			<!--<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=asistenteEvaluacion&nom=NomAsistenteActaInicio&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>-->
	  </td>
  </tr>
    <tr>
		<td class="tagForm">*Director:</td>
<td align="left" class="gallery clearfix">
			<input type="hidden" id="directorEvaluacion" value="<? echo $director['CodPersona']; ?>" />
		<input type="text" disabled="disabled" class="disabled" id="NomDirectorActaInicio"  value="<? echo $director['Nombres']." ".$director['Apellido1']." ".$director['Apellido2']; ?>" size="80" />
		<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=directorEvaluacion&nom=NomDirectorActaInicio&iframe=true&width=950&height=525" rel="prettyPhoto[iframe2]" style=" <?=$display_ver?>">
           	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
        </a>
	  </td>
	</tr>
	
</table>

<br />

<!--<div style="width:1000px" class="divFormCaption">Cotizaciones</div>-->
<br />
<!--<table width="1000" class="tblBotones">
	<tr>
    	<td>
            <input type="button" value="Disp. Presupuestaria" style="width:125px;" onclick="cargarOpcion2(this.form, 'lg_disponibilidad_presupuestaria_invitacion.php?origen=cotizar&numero='+document.getElementById('numero').value, 'BLANK', 'height=550, width=1050, left=100, top=0, resizable=no', 'coditem');" />
            
            <input type="button" value="&Uacute;ltimas Cotizaciones" style="width:125px;" onclick="cargarOpcion2(this.form, 'lg_cotizaciones_ultimas.php?codorganismo='+document.getElementById('codorganismo').value, 'BLANK', 'height=550, width=1050, left=100, top=0, resizable=no', 'coditem');" />
            
        </td>
		<td align="right">
			<input type="button" class="btLista" value="Insertar" onclick="abrirListadoInsertar('listado_personas.php?flagproveedor=S', 'detalle', 'insertarLineaListado', 'cotizaciones_invitar_cotizar_insertar', 'lg', 'height=800, width=750, left=50, top=50');" <?=$disabled_insertar?> />
            
			<input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'detalle'); setMontosProveedorItem();" /> | 
            <input type="button" value="Imprimir Invitaci&oacute;n" style="width:125px;" onclick="cargarOpcion2(this.form, 'lg_cotizaciones_invitar_pdf.php?origen=cotizar&numero='+document.getElementById('numero').value, 'BLANK', 'height=550, width=1050, left=100, top=0, resizable=no', 'sel_detalle');" />
		</td>
	</tr>
</table>-->

<center>





</center>
<input type="hidden" name="sel_detalle" id="sel_detalle" />
<input type="hidden" name="can_detalle" id="can_detalle" value="<?=$i?>" />
<input type="hidden" name="nro_detalle" id="nro_detalle" value="<?=$i?>" />
<center>
<input name="Botón" type="button" style="width:80px;" value="Guardar" onclick="guardarEvaluacionCualiCuanti('modificar');" />
<input type="button" value="Cancelar" style="width:80px;" onclick="location.href='lg_evaluacion_busqueda.php';"; />
</center>
<div class="divMsj" style="width:1100px">Campos Obligatorios *</div>
</form>
</body>
</html>
