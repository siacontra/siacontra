<?php
	session_start();
	
	if(!isset($_GET['codRecomendacion']))
	{
		 header("Location: ../lg/framemain.php");
	}
	
	//**********INCLUSION DE ARCHIVOS PARA CONSULTA*******//

	include_once ("../clases/MySQL.php");
	include_once("../comunes/objConexion.php");
	
	//**************************************************//
	function rellenarConCero($cadena, $cantidadRelleno)
	{
		$cantidadCadena = strlen($cadena);
		
		for($i = 0; $i < ($cantidadRelleno-$cantidadCadena); $i++)
		{
				$cadena = '0'.$cadena;
			
		}			
		
		return $cadena;
	}
	
	if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");

	$mensajeSNC = array('S'=>'(Inscrita en el SNC)','N'=>'(No inscrita en el SNC)');

	$sql1 = "SELECT A.CodActaInicio, A.CodPersonaAsistente, A.CodPersonaDirector, A.AnioActa, A.FechaCreacion as FechaCreacionActa, A.NroVisualActaInicio, A.Estado as EstadoActa, A.UltimoUsuario, A.UltimaFechaModif,
			B.CodEvaluacion, B.CodActaInicio, B.ObjetoEvaluacion, B.CriterioCualitativo, B.CriterioCuantitativo, B.Conclusion, B.Recomendacion, B.CodPersonaAsistente, B.CodPersonaDirector, B.AnioEvaluacion, B.NroVisualEvaluacion, B.FechaCreacion as FechaCreacionEvaluacion, B.Estado, B.UltimoUsuario, B.UltimaFechaModif,
			C.CodInformeRecomendacion, C.Conclusiones, C.Recomendacion, C.Asistente as AsistenteRecomendacion, C.Director, C.UltimoUsuario, C.UltimaFechaModif, C.ObjetoConsulta, C.CodEvaluacion, C.Asunto, C.AnioRecomendacion, C.NroVisualRecomendacion, C.FechaCreacion as FechaCreacionREcomendacion, C.Estado, C.RevisadoPor, C.FechaRevisado, C.AprobadoPor, C.FechaAprobado
			FROM lg_actainicio AS A
			JOIN lg_evaluacion AS B ON A.CodActaInicio = B.CodActaInicio
			JOIN lg_informerecomendacion AS C ON C.CodEvaluacion = B.CodEvaluacion
			WHERE C.CodInformeRecomendacion =".$_GET['codRecomendacion'];
				
	$resultado1 = $objConexion->consultar($sql1,'fila');
	
	$variableBusqueda = $resultado1['CodActaInicio'];
	

	$numeroVisualActa = '0004-CPAI-'.rellenarConCero($resultado1['NroVisualActaInicio'], 3).'-'.$resultado1['AnioActa'];
	$numeroVisualEvaluacion= '0004-CPECC-'.rellenarConCero($resultado1['NroVisualEvaluacion'], 3).'-'.$resultado1['AnioEvaluacion'];
	$numeroVisualRecomendacion = '0004-CPIR-'.rellenarConCero($resultado1['NroVisualRecomendacion'], 3).'-'.$resultado1['AnioRecomendacion'];
	
	$sql2 = "SELECT p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento, pu.DescripCargo
			FROM mastpersonas AS p
			JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
			JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
			WHERE me.CodEmpleado = '".$resultado1['AsistenteRecomendacion']."'";

	$resultado2 = $objConexion->consultar($sql2,'fila');//asistente

	
	
	$sql4 = "select CodRequerimiento, Secuencia
		from lg_requedetalleacta where CodActaInicio=".$variableBusqueda;
	
	$resultado4 = $objConexion->consultar($sql4,'matriz');
	
	for($i= 0; $i < count($resultado4); $i++)
	{
		$vectorCondicionSecuencia[$i] = $resultado4[$i]['Secuencia'];
		
	}
			
	$cadenaCondicionSecue = implode(',',$vectorCondicionSecuencia);
	$cadenaCondicionReque = $resultado4[0]['CodRequerimiento'];
	
	$sql5 = "SELECT 
				CodProveedorRecomendado

			FROM
				lg_proveedorrecomendado 
				
			WHERE
			CodInformeRecomendacion in (".$resultado1['NroVisualRecomendacion'].")";
	 $resp = $objConexion->consultar($sql5,'matriz');	
	$proveedor='<tr class="trListaBody" >
				<td width="253">Opci&oacute;n: No hay proveedores ha recomendar</td>
				</tr>';
	if($resp[0]['CodProveedorRecomendado']<>0)
	 
	 {
		 $sql5 = "SELECT 
				c.*,
				p.NomCompleto AS NomProveedor,mp.FlagSNC, pr.SecuenciaRecomendacion

			FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
				INNER JOIN mastpersonas p ON (c.CodProveedor = p.CodPersona)
				INNER JOIN mastproveedores mp ON (c.CodProveedor = mp.CodProveedor)
				INNER JOIN lg_proveedorrecomendado as pr on pr.CodProveedorRecomendado=mp.CodProveedor
				
			WHERE
				rd.CodRequerimiento in (".$cadenaCondicionReque.") AND
				c.Secuencia in (".$cadenaCondicionSecue.") 
				GROUP BY p.NomCompleto order by pr.SecuenciaRecomendacion";
	$resp = $objConexion->consultar($sql5,'matriz');//nombre de los proveedores sin repetir

	$proveedor='';
	
		for($i = 0; $i < count($resp); $i++)
			{ 
			 $proveedor.= '<tr class="trListaBody" >
			<td width="253">Opci&oacute;n '.($i+1).': '.$resp[$i]['NomProveedor'].' '.$mensajeSNC[$resp[$i]['FlagSNC']].'</td>
			 </tr>';
			}
	
}
	
	$sql6 = "SELECT 
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
				
	$resp2 = $objConexion->consultar($sql6,'matriz');//nombre de los item sin repetir*/
	
	if (count($resp2) <= 0)
	{		
		$sql6 = "SELECT 
				c.*,
				p.NomCompleto AS NomProveedor,
				i.CodImpuesto,
				i.FactorPorcentaje, d.Descripcion
			FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
				INNER JOIN mastpersonas p ON (c.CodProveedor = p.CodPersona)
				INNER JOIN mastproveedores mp ON (c.CodProveedor = mp.CodProveedor)
				INNER JOIN lg_commoditysub AS d 
				LEFT JOIN masttiposervicioimpuesto tsi ON (mp.CodTipoServicio = tsi.CodTipoServicio)
				LEFT JOIN mastimpuestos i ON (tsi.CodImpuesto = i.CodImpuesto AND tsi.CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."')
			WHERE
				rd.CodRequerimiento in (".$cadenaCondicionReque.") AND
				c.Secuencia in (".$cadenaCondicionSecue.")
				and (rd.CommoditySub = d.Codigo) group by d.Descripcion";
				
		$resp2 = $objConexion->consultar($sql6,'matriz');//nombre de los item sin repetir
				
	}
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../cp/css1.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />

<link href="css1.css" rel="stylesheet" type="text/css" charset="utf-8" />


<script type="text/javascript" src="../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js" charset="utf-8"></script>


<script type="text/javascript" language="javascript" src="../cp/fscript.js"></script>
<script type="text/javascript" src="../cp/ckeditor/ckeditor.js"></script>
<script type="text/javascript" language="javascript" src="../cp/ckeditor/sample.js"></script>
<link href="../cp/ckeditor/sample.css" rel="stylesheet" type="text/css" />

<!-- INCLUSION DE LOS ARCHIVOS FUNCIONALIDADES CES -->
    <script  type='text/JavaScript' src='../js/vEmergente.js' charset="utf-8"></script>
    
    <script type='text/JavaScript' src='../js/AjaxRequest.js' charset="utf-8"></script>

    <script type='text/JavaScript' src='../js/xCes.js' charset="utf-8"></script>
    
    <!-- <script type='text/JavaScript' src='../js/comun.js' charset="utf-8"></script>--> 
    
    <script type='text/JavaScript' src='../js/dom.js' charset="utf-8"></script>

	<script type='text/JavaScript' src='js/funcionalidadCes.js' charset="utf-8"></script>

    <script type='text/JavaScript' src='js/dom.js' charset="utf-8"></script>
<!--*********************************************** -->

    <!-- INCLUSION DE LOS ARCHIVOS FUNCIONALIDADES CES -->
	<link rel="stylesheet" href="../css/vEmergente.css" type="text/css" charset="utf-8" />

    <link rel="stylesheet" href="../css/estiloCes.css" type="text/css"  charset="utf-8" />
    <!--*********************************************** -->
        
<style type="text/css">
<!--
UNKNOWN {FONT-SIZE: small}
#header {FONT-SIZE: 93%; BACKGROUND: url(../cp/imagenes/bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal}
#header UL {PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none}
#header LI {
        PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(../cp/imagenes/left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px}
#header A {
        PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(../cp/imagenes/right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none}
#header A { FLOAT: none}
#header A:hover {  COLOR: #333 }
#header #current { BACKGROUND-IMAGE: url(../cp/imagenes/left_on.gif)}
#header #current A { BACKGROUND-IMAGE: url(../cp/imagenes/right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333 }
-->
</style>
</head>
<body onload="consultarEstadoReq('');">
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Revisar Recomendaci&oacute;n </td>
		<td align="right"><a class="cerrar" href="../lg/framemain.php"  onclick="cargarPagina(document.getElementById('frmentrada'),'<?=$regresar?>.php?&limit=0')";>[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />



<div style="width:800px; height:15px;" class="divFormCaption">Datos Recomendaci&oacute;n</div>
<table width="808" height="379" border="0" class="tblForm">
<tr><td width="1057" height="5"></td>
</tr>
<tr> <!-- ///////////  PRIMER ////////// -->
  <td><form id="fromActa"></form></td>
</tr>
   <tr>
		<td colspan="2" class="tagForm"><table width="" border="0" align="center" cellpadding="0" cellspacing="1">
                     
                     
                      <tr>
                        <td width="173" height="28"  align="right"><div align="right">N&deg; Acta Inicio:</div></td>
                        <td width="173" align="center" valign="middle"><div align="left">
                          <input name="textfield42" type="text" disabled="disabled" value="<? echo $numeroVisualActa; ?>" size="20" />
                        </div></td>
                        <td width="110" align="center" valign="middle"><div align="right">N&deg; Evaluaci&oacute;n: </div></td>
                        <td width="226"><div align="left">
                          <input name="textfield43" type="text" disabled="disabled" value="<? echo $numeroVisualEvaluacion; ?>" size="20" />
                        </div></td>
                        <td width="137"><div align="right">N&deg; Recomendaci&oacute;n: </div></td>
                        <td width="211"><div align="left">
                          <input name="textfield44" type="text" disabled="disabled" value="<? echo $numeroVisualRecomendacion; ?>" size="20" />
                        </div></td>
                      </tr>
                      <tr>
                        <td height="28"  align="right">&nbsp;</td>
                        <td align="center" valign="middle">&nbsp;</td>
                        <td align="center" valign="middle">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="28" colspan="6"  align="right"><table width="800" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td colspan="2" class="trListaHead"><div align="center">Cantidad</div></td>
                            <td width="519" class="trListaHead"><div align="center">&Iacute;tems</div></td>
                          </tr>
                          <?php 
						  
						  		for($i = 0; $i < count($resp2); $i++)
								{
									  echo '<tr class="trListaBody" >
									  <td width="400" colspan="2" align="center">'. str_replace(".00","",$resp2[$i]['Cantidad']).'</td>
									  <td width="400" align="center">'.$resp2[$i]['Descripcion'].'</td>
										
									  </tr>';
									  /*
										<td width="300">&nbsp;</td>*/
								}
						  ?>
                        </table></td>
                      </tr>
					  <tr>
                        <td height="28" colspan="6"  align="right"><input type="hidden" id="codigoRecomendacion" name="hiddenField" value="<? echo $_GET['codRecomendacion']; ?>" /></td>
                      </tr>
                      <tr>
                        <td height="28" colspan="6"  align="right"><table width="800" border="0" cellspacing="0">
                          <tr>
                            <td colspan="3" class="trListaHead"><div align="center">Proveedores Recomendados </div></td>
                          </tr>
						  <?php 
						  echo $proveedor;
						  ?>
                        </table></td>
                      </tr>
					  <tr>
                        <td height="2" colspan="6"  align="right"></td>
                      </tr>
                      
                      <tr>
                        <td height="28" colspan="6"  align="right"><table width="752" border="0" align="center">
                          <tr>
                        <td width="291" height="28"  align="right"><div align="right">Recomendaci&oacute;n Realizada Por: </div></td>
                        <td width="493" colspan="5" align="center" valign="middle"><div align="left">
                          <label>
                          <input name="textfield" type="text" disabled="disabled" value="<? echo $resultado2['Nombres'].' '.$resultado2['Apellido1'].' '.$resultado2['Apellido2']; ?>" size="50" />
                          </label>
                        </div>                          <div align="left"></div>                          <div align="left"></div>                          <div align="left"></div>                          <div align="left"></div></td>
                      </tr>
                      <tr>
                        <td height="28"  align="right"><div align="right">Recomendaci&oacute;n Revisada Por: </div></td>
                        <td colspan="5" align="center" valign="middle"><div align="left">
                          <input name="textfield2" type="text" size="50" disabled="disabled" />
                        </div>                          <div align="left"></div>                          <div align="left"></div>                          <div align="left"></div>                          <div align="left"></div></td>
                      </tr>
                      <tr>
                        <td height="28"  align="right"><div align="right">Recomendaci&oacute;n Aprobada Por:</div></td>
                        <td colspan="5" align="center" valign="middle"><div align="left">
                          <input name="textfield3" type="text" size="50" disabled="disabled" />
                        </div></td>
                      </tr>
                        </table></td>
                      </tr>
                    </table>
	     <!-- <input type="hidden" id="fila" name="hiddenField" value="0" />
		  <input type="hidden" id="estiloFila" name="hiddenField2" value="0" />
		  <input type="hidden" id="codigoRecomendacion" name="hiddenField3" value="0"/>
					<input type="hidden" id="cantResultados" name="hiddenField3" value="0"/>
					<br />-->
					<div class="capaResultadoBusqueda">
					
		              <div align="center">
		                <table width="135" border="0">
                          <tr>
                            <td width="58"><input type="button" name="Submit" onclick="guardarRevision();" value="Revisar" /></td>
                            <td width="67"><input type="button" name="Submit2" onclick="location.href='lg_revisar_recomendacion.php'" value="Cancelar" /></td>
                          </tr>
                        </table>
		                <label></label>
		              </div>
		  </div>
					<br/>
	 </td>
  </tr>

 
<tr>
 <td colspan="4"><div class="divFormCaption" align="center"><b></b></div></td>
</tr>
<tr>
  <td colspan="4"><!--<textarea class="ckeditor" cols="90" id="editor1" name="editor1" rows="15"></textarea>-->
  </td>
</tr>
</table>
<center>

  <p>
    <!--<div class="divMsj" style="width:1100px">Campos Obligatorios *</div>-->
    <!--<input type="button" value="Guardar Registro" onclick="guardarGenerarActaInicio('<? echo $registroCodSecGenerarActa ?>');" />
    <input type="button" value="Cancelar" onClick="cargarPagina(this.form,'lg_cotizaciones_invitar.php?concepto=01-0006&limit=0&filtrar=default');"  />-->
    <!--    -->
  </p>
</center>

</body>

</html>
