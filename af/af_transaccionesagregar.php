<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include ("fphp.php");
include ("controlActivoFijo.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
<style type="text/css">
<!--
UNKNOWN {FONT-SIZE: small}
#header {FONT-SIZE: 93%; BACKGROUND: url(imagenes/bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal}
#header UL {PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none}
#header LI {
        PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(imagenes/left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px}
#header A {
        PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(imagenes/right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none}
#header A { FLOAT: none}
#header A:hover {  COLOR: #333 }
#header #current { BACKGROUND-IMAGE: url(imagenes/left_on.gif)}
#header #current A { BACKGROUND-IMAGE: url(imagenes/right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333 }
-->
</style>
</head>
<body>
<div id="cajaModal"></div>
<!-- pretty -->
<span class="gallery clearfix"></span>
<?

//list($organismo, $nroOrden, $secuencia, $nrosecuencia)=SPLIT('[-]',$_GET['registro']);
//// CONSULTA PRINCIPAL
$sql = "select * from af_activo where Activo = '".$_POST['registro']."'";  
$qry = mysql_query($sql) or die ($sql.mysql_error());
$row = mysql_num_rows($qry);

if($row!='0') $field = mysql_fetch_array($qry);
?>
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
  <td class="titulo">Transacciones | Agregar</td>
  <td align="right"><a class="cerrar" onclick="window.close();">[cerrar]</a></td>
</tr>
</table>
<hr width="100%" color="#333333" />
<form id="frmentrada" name="frmentrada" action="af_activosmenoresagregar.php?limit=0"  onsubmit="return guardarActivosMenores(this);">
<? echo"<input type='hidden' id='registro' name='registro' value='".$registro."'/>";?>
<table width="808" align="center">
<tr>
<td>
<div id="header">
<ul>
<!-- CSS Tabs PESTA�AS OPCIONES -->
<li><a onClick="document.getElementById('tab1').style.display='block';document.getElementById('tab2').style.display='none';" href="#">Transacci&oacute;n </a></li>
<li><a onClick="document.getElementById('tab1').style.display='none';document.getElementById('tab2').style.display='block';" href="#">Historia</a></li> 
</ul>
</div>
</td>
</tr>
</table>
<? echo" <input type='hidden' id='regresar' name='regresar' value='".$_GET['regresar']."' />
         <input type='hidden' id='activo' name='activo' value='' />";?>
<!-- ****************************************************** COMIENZO TAB1 ************************************************ -->
<div id="tab1" style="display: block;">
<div style="width:800px; height=15px;" class="divFormCaption">Transacci&oacute;n</div>
<table class="tblForm" width="800">
<tr>
   <td width="102" class="tagForm"># Activo:</td>
   <td colspan="2"><input type="text" id="nro_activo" name="nro_activo" size="10" value="<?=$field['Activo'];?>" disabled/><input type="text" id="descpActivo" name="descpActivo" size="50" disabled /></td>
   <td width="88" class="tagForm">Organismo:</td>
   <td width="291"><input type="text" id="Organismo" name="Organismo" size="40" disabled/></td>
</tr>
<tr>
  <td class="tagForm">Tipo de Baja:</td>
  <td colspan="2"><select id="tipoBaja" name="tipoBaja"/></td>
  <td class="tagForm">Voucher Baja:</td>
  <td><input type="text" id="Periodo_voucherBaja" name="Periodo_voucherBaja" size="8" disabled/><input type="text" id="voucherBaja" name="voucherBaja" size="8" disabled/> <input type="checkbox" id="contabilizado" name="contabilizado"/></td>
</tr>
<tr>
  <td class="tagForm">Fecha:</td>
  <td width="108"><input type="text" name="fecha" id="fecha" size="8" value="<?=date("d-m-Y");?>" style="text-align:right"/></td>
  <td width="187">Nro. Factura: <input type="text" id="nroFactura" name="nroFactura" disabled/></td>
  <td class="tagForm">Voucher Venta:</td>
  <td><input type="text" id="voucherVenta" name="voucherVenta" size="10" disabled/></td>
</tr>
<tr>
  <td colspan="3"></td>
  <td align="center"><u>Local</u></td>
</tr>
<tr>
  <td class="tagForm">Categor&iacute;a:</td>
  <td colspan="2"><input type="text" id="categoria" name="categoria" size="20"/></td>
  <td class="tagForm">Valor Residual:</td>
  <td><input type="text" id="valorResidual" name="valorResidual" size="12"/></td>
</tr>
<tr>
  <td class="tagForm">Aprobado Por:</td>
  <td colspan="2"><input type="text" id="aprobadoPor" name="aprobadoPor" size="35" disabled/><input type="text" id="fecha_aprobacion" name="fecha_aprobacion" disabled/></td>
  <td class="tagForm">Valor Residual:</td>
  <td><input type="text" id="valorVenta" name="valorVenta" size="12"/></td>
</tr>
<tr>
 <td class="tagForm">Comentario:</td>
 <td colspan="3"><input type="text" id="comentario" name="comentario" size="70"/></td>
 <td>Acuerdo Directorio: <input type="text" id="acuerdoDirectorio" name="acuerdoDirectorio" size="15"/></td>
</tr>
<tr>
 <td class="tagForm">Junta Accionistas:</td>
 <td colspan="3"><input type="text" id="juntaAccionistas" name="juntaAccionistas" size="70"/></td>
 <td>Resoluci&oacute;n Ministerial: <input type="text" id="resolucionMinisterial" name="resolucionMinisterial" size="15"/></td>
</tr>
<tr>
 <td height="10"></td>
</tr>
<tr>
 <td class="tagForm">Estado:</td>
 <td><input type="text" id="estado" name="estado" value="En Preparaci&oacute;n" size="15" disabled/></td>
</tr>
<tr>
  <td colspan="2"></td>
  <td colspan="2" align="center">Ultima Modif: <input type="text" id="usuario" name="usuario" size="15" disabled /><input type="text" id="ultimaFecha" name="ultimaFecha" size="10" disabled/></td>
  <td></td>
</tr>
<tr>
 <td>Distribuci&oacute;n Contable:</td>
</tr>
<tr>
 <td colspan="5">
  <table align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="top" style="height:100px; width:150px;">
    <table align="center" width="300">
    <tr>
      <td align="center">
      <div style="overflow:scroll; height:300px; width:800px;">
      <table width="800" class="tblLista">
        <tr class="trListaHead">
          <th width="80" scope="col"> T </th>
          <th scope="col">Balance General</th>
          <th scope="col">Descripcion</th>
          <th scope="col">Local</th>
        </tr>
	<?php 
	if ($registros!=0) {
		//	CONSULTO LA TABLA
		if ($_POST['filtro']!="") $sql="SELECT 
		                                       *
										  FROM 
										       af_activo
									     WHERE 
										      (Activo LIKE '%".$_POST['filtro']."%' OR Descripcion LIKE '%".$_POST['filtro']."%')
									  ORDER BY 
									          Activo LIMIT ".$_GET['limit'].", $MAXLIMIT";
		else $sql="SELECT
						*
  					FROM
						af_activo
			   ORDER BY
                        Activo LIMIT ".$_GET['limit'].", $MAXLIMIT";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//	MUESTRO LA TABLA
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
						
			if($ventana==insertarDestinatarioDep){
			 echo "
			<tr class='trListaBody' onclick='mClk(this, \"registro\"); insertarDestinatarioDep(this.id,\"".$ventana."\");' id='".$field['CodPersona']."'>
				<td align='center'>".$field['CodDependencia']."</td>
				<td align='left'>".$field['Dependencia']."</td>
				<td align='left'>".$field['NomCompleto']."</td>
				<td align='left'>".$field['DescripCargo']."</td>
			</tr>";
			
			}else{
				if($field['Estado']=='A') $estado = Activo; else $estado = Inactivo;
				if($field['TipoActivo']='I') $tActivo = 'Individual'; else $tActivo = 'Principal';
				/// ------------------------------------------------------
				 $s_consulta = "select 
				                       st.Descripcion as DescpSitActivo,
									   cc.Descripcion as DescpCC,
									   cd.DescripcionLocal as DescpCD,
									   ca.Descripcion as DescpCA,
									   ub.Descripcion as DescpUB
								  from 
								       af_situacionactivo st,
									   ac_mastcentrocosto cc,
									   af_categoriadeprec cd,
									   af_clasificacionactivo ca,
									   af_ubicaciones ub
								  where  
								       st.CodSituActivo= '".$field['SituacionActivo']."' and 
									   cc.CodCentroCosto= '".$field['CentroCosto']."' and 
									   cd.CodCategoria = '".$field['Categoria']."' and 
									   ca.CodClasificacion = '".$field['Clasificacion']."' and 
									   ub.CodUbicacion = '".$field['Ubicacion']."'";
				 $q_consulta = mysql_query($s_consulta) or die ($s_consulta.mysql_error()) ;
				 $f_consulta = mysql_fetch_array($q_consulta);
				/// ------------------------------------------------------
				$s_consulta2 = "select 
				                      mtp.NomCompleto,
									  rhp.DescripCargo 
								  from 
								      mastpersonas mtp
									  inner join mastempleado mte on (mte.CodPersona = mtp.CodPersona)
									  inner join rh_puestos rhp on (rhp.CodCargo = mte.CodCargo) 
								 where
								      mtp.CodPersona = '".$field['EmpleadoResponsable']."'";
				$q_consulta2 = mysql_query($s_consulta2) or die ($s_consulta2.mysql_error());
				$f_consulta2 = mysql_fetch_array($q_consulta2);
				/// ------------------------------------------------------
				$MOSTRAR = $field['CentroCosto'].'|'.htmlentities($f_consulta['DescpCC']).'|'.$field['Ubicacion'].'|'.$f_consulta['DescpUB'].'|'.$f_consulta['DescpSitActivo'].'|'.$field['CodigoInterno'];
				/// ------------------------------------------------------
			echo "
			<tr class='trListaBody' onclick='mClk(this, \"registro\"); selEmpleado(\"".$field['Busqueda']."\", ".$_GET['campo'].", \"".$field['Descripcion']."\", \"".$field['Clasificacion']."\", \"".$f_consulta['DescpCA']."\", \"$MOSTRAR\");' id='".$field['Activo']."'>
			    <td align='center'>".$field['Activo']."</td>
				<td align='center'>".$field['CodigoInterno']."</td>
				<td align='left'>".$field['Descripcion']."</td>
				<td align='center'>".$field['NumeroSerie']."</td>
				<td align='center'>".$field['Modelo']."</td>
				<td align='center'>".$field['NumeroPlaca']."</td>
				<td align='left'>".$f_consulta['DescpUB']."</td>
				<td align='left'>".htmlentities($f_consulta['DescpCC'])."</td>
				<td align='left'>".$f_consulta2['NomCompleto']."</td>
				<td align='left'>".$f_consulta2['DescripCargo']."</td>
				<td align='center'>".$field['ActivoConsolidado']."</td>
				<td align='left'>$estado</td>
			</tr>";
		}}
	}
	$rows=(int)$rows;
	echo "
	<script type='text/javascript' language='javascript'>
		totalLista($registros);
		totalLotes($registros, $rows, ".$_GET['limit'].");
	</script>";				
	?>
      </table>
      </div></td></tr>
     </table>
     </td></tr></table>
 </td>
</tr>
</table>

<table class="tblForm" width="900">
<tr>
   <td class="tagForm">Situaci&oacute;n Activo:</td>
   <td>
       <select id="situacion_activo" class="selectMed">
        
        <? $s_activo = "select * from af_situacionactivo";
           $q_activo = mysql_query($s_activo) or die ($s_activo.mysql_error());
           $r_activo = mysql_num_rows($q_activo);
          
           if($r_activo!='0'){
		    for($i=0;$i<$r_activo;$i++){
               $f_activo = mysql_fetch_array($q_activo);
			   if($f_activo['CodSituActivo']=='OP'){
                echo"<option value='".$f_activo['CodSituActivo']."' selected>".$f_activo['Descripcion']."</option>";
			   }else{
			     echo"<option value='".$f_activo['CodSituActivo']."'>".$f_activo['Descripcion']."</option>";
			   }	
            }
		   }
        ?>         
       </select></td> 
   <td class="tagForm" width="100">Organismo:</td>
   <td><select id="organismo" name="organismo" class="selectBig" >
       <?
        $s_org = "select * from mastorganismos where CodOrganismo='".$field['CodOrganismo']."'";
        $q_org = mysql_query($s_org) or die ($s_org.mysql_error());
        $r_org = mysql_num_rows($q_org);
        if($r_org!='0'){
            for($i=0;$i<$r_org;$i++){
              $f_org = mysql_fetch_array($q_org);
              echo"<option value='".$f_org['CodOrganismo']."'>".$f_org['Organismo']."</option>";
            }
        }
       ?>
       </select></td>
</tr>
<tr>
   <td class="tagForm">C&oacute;digo Interno:</td>
   <td><input type="text" id="codigo_interno" name="codigo_interno" size="30" style="text-align:right"  maxlength="10" value="<?=$field['CodigoInterno'];?>"/></td>
   <td class="tagForm">Dependencia</td>
   <td><select id="dependencia" name="dependencia" class="selectBig">
         <?
          $s_dep = "select 
		                   * 
					  from 
					       mastdependencias 
					 where 
					       CodOrganismo = '".$field['CodOrganismo']."'";
          $q_dep = mysql_query($s_dep) or die ($s_dep.mysql_error());
          $r_dep = mysql_num_rows($q_dep);
  
		  if($r_dep!='0'){
			for($i=0;$i<$r_dep;$i++){
			   $f_dep = mysql_fetch_array($q_dep);
			   if($f_dep['CodDependencia']==$field['CodDependencia'])
			     echo"<option value='".$f_dep['CodDependencia']."' selected>".$f_dep['Dependencia']."</option>"; 
			   else
			     echo"<option value='".$f_dep['CodDependencia']."'>".$f_dep['Dependencia']."</option>"; 
			}
		  }
		 ?>
       </select></td> 
</tr>
<tr>
   <td class="tagForm">Naturaleza:</td><? 
           /*$s_parametro = "select * from mastparametros where ParametroClave = 'CATDEPRDEFECT'";
           $q_parametro = mysql_query($s_parametro) or die ($s_parametro.mysql_error());
           $f_parametro = mysql_fetch_array($q_parametro);
		   if($f_parametro['ValorParam']=='AN'){
			  $parametro = 'Activo Menor';   
			}*/
			if($field['Naturaleza']=='BME') $parametro = 'Activo Menor';
		  ?>
   <td><input type="text" id="naturaleza" name="naturaleza" size="30" value="<?=$parametro;?>" disabled/></td>
   <td class="tagForm"></td>
   <td width="300"></td>
   <td></td>
</tr>
<tr>
 <td></td>
 <td></td>
 <td class="tagForm">Categor&iacute;a:</td>
 <td>
    <select id="select_categoria" class="selectSma" onchange="cargarCampoCategoria(this.id)" >
                   <option value=""></option>
                   <?
                   $s_categoria = "select * from af_categoriadeprec";
				   $q_categoria = mysql_query($s_categoria) or die ($s_categoria.mysql_error());
				   $r_categoria = mysql_num_rows($q_categoria);
				   
				   if($r_categoria!=0){
					   for($i=0;$i<$r_categoria;$i++){
						  $f_categoria = mysql_fetch_array($q_categoria);
						   echo"<option value='".$f_categoria['CodCategoria']."'>".$f_categoria['CodCategoria']."</option>";
					   }
					}
				   ?>
                   </select>
     <input type="text" id="categoria" name="categoria" size="34" value="" readonly/></td>
</tr>
<tr>
   <td class="tagForm"></td>
   <td></td>
   <td class="tagForm">Clasificaci&oacute;n:</td>
   <?
	$s_clactivo = "select * from af_clasificacionactivo where CodClasificacion= '".$field['Clasificacion']."'";
	$q_clactivo = mysql_query($s_clactivo) or die ($s_clactivo.mysql_error());
	$f_clactivo = mysql_fetch_array($q_clactivo);
   ?>
   <td><input type="text" id="clasificacion" name="clasificacion" size="19" value="<?=$f_clactivo['CodClasificacion'];?>" readonly/>
       <input type="text" id="clasificacion2" name="clasificacion2" size="40" value="<?=$f_clactivo['Descripcion'];?>" readonly/></td>
   <td><input type="button" name="btClasificacion" id="btClasificacion" value="..." onclick="cargarVentanaLista(this.form, 'af_listaclasificacionactivo.php?limit=0&campo=1','height=500, width=800, left=200, top=100, resizable=yes');"/></td>
</tr>
<tr>
   <td class="tagForm"><?
   if($field['FlagParaOperaciones']=='S'){?><input type="checkbox" name="disp_operaciones" id="disp_operaciones" checked/><? }else{?><input type="checkbox" name="disp_operaciones" id="disp_operaciones"  disabled/><? }?></td>
   <td> Disponible Para Operaciones</td>
   <td class="tagForm">Ubicaci&oacute;n:</td>
   <?
    $s_ubicacion = "select CodUbicacion, Descripcion from af_ubicaciones where CodUbicacion = '".$field['Ubicacion']."'";
	$q_ubicacion = mysql_query($s_ubicacion) or die ($s_ubicacion.mysql_error());
	$f_ubicacion = mysql_fetch_array($q_ubicacion);
   ?>
   <td><input type="text" name="ubicacion" id="ubicacion" size="19" value="<?=$f_ubicacion['CodUbicacion'];?>" readonly/>
       <input type="text" name="ubicacion2" id="ubicacion2" size="40" value="<?=$f_ubicacion['Descripcion'];?>" readonly/></td>
   <td><input type="button" name="btUbicacion" id="btUbicacion" value="..." onclick="cargarVentanaLista(this.form, 'af_listaubicacionesactivo.php?limit=0&campo=2','height=500, width=800, left=200, top=100, resizable=yes');" /></td>
</tr>
<tr>
   <td class="tagForm"></td>
   <td></td>
   <td class="tagForm">Activo Consolidado</td>
   <?
    $s_actcon = "select Activo,Descripcion from af_activo where ActivoConsolidado = '".$field['ActivoConsolidado']."'";
	$q_actcon = mysql_query($s_actcon) or die ($s_actcon.mysql_error());
	$f_actcon = mysql_fetch_array($q_actcon);
   ?>
   <td><input type="hidden" name="activo_consolidado" id="activo_consolidado" value="<?=$f_actcon['Activo'];?>" readonly/><input type="text" name="activo_consolidado2" id="activo_consolidado2" value="<?=$f_actcon['Descripcion'];?>" readonly/></td>
   <td><input type="button" name="btActivop" id="btActivop" value="..." onclick="cargarVentanaLista(this.form, 'af_listaactivosfijos.php?limit=0&campo=19','height=500, width=800, left=200,top=100,resizable=yes');" /></td>
</tr>
<tr>
   <td class="tagForm"></td>
   <td>&nbsp;</td>
   <td class="tagForm" width="150"><u>Responsables del Activo</u></td>
   <td></td>
   <td></td>
</tr>
<tr>
   <td class="tagForm">Estado:</td>
   <td width="245"><input type="hidden" id="radioEstado" name="radioEstado" value="A"/>
   <? if($field['EstadoRegistro']=='A'){?>
     <input type="radio" id="radio1" name="radio1" checked="checked" onclick="estadosPosee(this.form, 'a')" />Activo <input type="radio" name="radio2" id="radio2" onclick="estadosPosee(this.form, 'b')" />Inactivo
   <? }else{ ?>
   <input type="radio" id="radio1" name="radio1" onclick="estadosPosee(this.form, 'a')" />Activo <input type="radio" name="radio2" id="radio2" onclick="estadosPosee(this.form, 'b')" checked="checked"/>Inactivo
   <? }?></td>
   <td class="tagForm" width="150">Centro Costos:</td>
   <?
     $s_centcosto = "select CodCentroCosto, Descripcion from ac_mastcentrocosto where CodCentrocosto='".$field['CentroCosto']."'";
	 $q_centcosto = mysql_query($s_centcosto) or die ($s_centcosto.mysql_error());
	 $f_centcosto = mysql_fetch_array($q_centcosto);
   ?>
   <td><input type="text" id="centro_costos" name="centro_costos" size="19" value="<?=$f_centcosto['CodCentroCosto'];?>" readonly/>
       <input type="text" id="centro_costos2" name="centro_costos2" size="40" value="<?=htmlentities($f_centcosto['Descripcion']);?>" readonly/></td>
   <td><input type="button" name="btCentroCostos" id="btCentroCostos" value="..." onclick="cargarVentanaLista(this.form,'af_listacentroscostos.php?limit=0&campo=9','height=500,width=800,left=200,top=100,resizable=yes');"/></td>
</tr>
<tr>
   <td class="tagForm"></td>
   <td></td>
   <?
    $s_resp = "select 
	                  mtper1.CodPersona as CodPersonaResp, 
					  mtper1.NomCompleto as NomCompletoResp,
					  mtper2.CodPersona as CodPersonaProveedor,
					  mtper2.NomCompleto as NomCompletoProveedor
				 from 
				      af_activo afact 
					  inner join mastpersonas mtper1 on (mtper1.CodPersona = afact.EmpleadoResponsable) 
					  inner join mastpersonas mtper2 on (mtper2.CodPersona = afact.CodProveedor) 
				where 
				      Activo ='".$field['Activo']."'";
	$q_resp = mysql_query($s_resp) or die ($s_resp.mysql_error());
	$f_resp = mysql_fetch_array($q_resp);
   ?>
   <td class="tagForm">Empleado Responsable:</td>
   <td><input type="hidden" name="cod_usuario" id="cod_usuario" value="<?=$f_resp['CodPersonaResp'];?>" /><input type="text" id="nomb_usuario" name="nomb_usuario" size="68" value="<?=$f_resp['NomCompletoResp']?>" readonly/></td>
   <td><input type="button" name="btEmpleado" id="btEmpleado" value="..." onclick="cargarVentanaLista(this.form,'af_listaempleados.php?limit=0&campo=7','height=500,width=800,left=200,top=100,resizable=yes');"/></td>
</tr>
<tr>
   <td colspan="2"></td>
   <td class="tagForm"></td>
   <td></td>
   <td></td>
</tr>

<tr><td align="center" colspan="5">Ultima Modif.:<input type="text" name="ultimo_usuario" value="<?=$field['UltimoUsuario'];?>" size="25" disabled/><input type="text" name="ultima_fecha" value="<?=$field['UltimaFechaModif'];?>" size="20" disabled/></td></tr>
</table>
</div>
<!-- ****************************************************** COMIENZO TAB2 ************************************************ -->
<div id="tab2" style="display: none;">
<div style="width:900px; height=15px;" class="divFormCaption">Informaci&oacute;n Adicional</div>
<table class="tblForm" width="900">
<tr>
  <td width="137" class="tagForm"><u>Informaci&oacute;n del Activo</u></td>
  <td></td>
  <td width="33"></td>
  <td class="tagForm"><u>Informaci&oacute;n Documentaria</u></td>
</tr>
<tr><?
$scon2 = "select 
                ma.Descripcion as DescpMarcas
			from
			    lg_marcas ma
		   where
		        ma.CodMarca = '".$fa['CodMarca']."'";
$qcon2= mysql_query($scon2) or die ($scon2.mysql_error());
$fcon2= mysql_fetch_array($qcon2);
?>
  <td class="tagForm">Fabricante(Marca):</td>
  <td width="183"><input type="hidden" name="fabricante" id="fabricante" size="40" value="<?=$field['Marca'];?>"/><input type="text" name="fabricante2" id="fabricante2" size="40" value="<?=$field['Marca'];?>" /></td>
  <td></td>
  <td width="162" class="tagForm">Proveedor:</td>
 <td width="361" colspan="2"><input type="text" id="proveedor" name="proveedor" size="8" value="<?=$f_resp['CodPersonaProveedor'];?>" readonly/><input type="text" id="nomb_proveedor" name="nomb_proveedor" size="48" value="<?=$f_resp['NomCompletoProveedor'];?>" readonly/>
                  <input type="button" id="btProveedor" name="btProveedor" value="..." onclick="cargarVentanaLista(this.form, 'af_listaproveedor.php?limit=0&campo=1','height=500, width=800, left=200,top=100,resizable=yes');"/></td>
</tr>
<tr>
  <td class="tagForm">Modelo:</td>
  <td><input type="text" name="modelo" id="modelo" size="40" value="<?=$field['Modelo'];?>" /></td><td></td>
  <td class="tagForm">Factura:</td>
  <td colspan="2"><select id="factura" name="factura" style="width:70px;">
  <?
  $s_facnumerodoc = "select 
                           CodTipoDocumento,
                           Descripcion 
                      from 
                           ap_tipodocumento";
  $q_facnumerodoc = mysql_query($s_facnumerodoc) or die ($s_facnumerodoc.mysql_error());
  
  while($f_facnumerodoc = mysql_fetch_array($q_facnumerodoc)){
    if($f_facnumerodoc['CodTipoDocumento']==$field['FacturaTipoDocumento'])
	  echo"<option value='".$f_facnumerodoc['CodTipoDocumento']."' selected>".$f_facnumerodoc['Descripcion']."</option>";
	else
	  echo"<option value='".$f_facnumerodoc['CodTipoDocumento']."'>".$f_facnumerodoc['Descripcion']."</option>";
  }
  ?>
  </select>
  <input type="text" id="num_factura" name="num_factura" size="30" value="<?=$field['FacturaNumeroDocumento'];?>"/>
  <? list($a, $m, $d) = SPLIT('[-]',$field['FacturaFecha']); $fechaFactura = $d.'-'.$m.'-'.$a;?>
  <input type="text" id="fecha_factura" name="fecha_factura" size="8" maxlength="10" value="<?=$fechaFactura?>"/></td>
</tr>
<tr>
  <td class="tagForm">N&uacute;mero de Serie:</td>
  <td><input type="text" name="nro_serie" id="nro_serie" size="40" value="<?=$field['NumeroSerie'];?>" /></td><td></td>
  <td class="tagForm">Orden Compra:</td>
  <? 
       list($a, $m, $d)= SPLIT('[-]',$field['NumeroOrdenFecha']); $fechaOrdenCompra = $d.'-'.$m.'-'.$a;
  ?>
   <td><input type="text" id="orden_compra" name="orden_compra" size="25" value="<?=$field['NumeroOrden']?>" maxlength="15"/><input type="text" id="fecha_ordencompra" name="fecha_ordencompra" size="8" maxlength="10" value="<?=$fechaOrdenCompra?>"/></td>
</tr>
<tr>
  <td class="tagForm">Color:</td>
  <td><select id="color" class="selectMed" >
       <?
       $s_color = "select * from mastmiscelaneosdet where CodMaestro='COLOR'";
	   $q_color = mysql_query($s_color) or die ($s_color.mysql_error());
	   
	   while($f_color = mysql_fetch_array($q_color)){
		  if ($f_color['CodDetalle']==$field['Color']) 
		    echo"<option value='".$f_color['CodDetalle']."' selected>".$f_color['Descripcion']."</option>";
		  else echo"<option value='".$f_color['CodDetalle']."'>".$f_color['Descripcion']."</option>";
	   }
	  ?>
      </select></td><td></td>
  <td class="tagForm">Gu&iacute;a Remisi&oacute;n #:</td><? list($a, $m, $d) = SPLIT('[-]',$field['NumeroGuiaFecha']); $fechaNumeroGuia = $d.'-'.$m.'-'.$a;?>
  <td><input type="text" id="nro_guiaremision" name="nro_guiaremision" size="25" value="<?=$field['NumeroGuia'];?>" maxlength="15"/><input type="text" id="fecha_guiaremision" name="fecha_guiaremision" size="8" maxlength="10" value="<?=$fechaNumeroGuia;?>"/></td>
</tr>
<tr>
  <td class="tagForm">C&oacute;digo de Barras:</td>
  <td><input type="text" name="cod_barras" id="cod_barras" size="40" value="<?=$field['CodigoBarras'];?>" style="text-align:right;"/></td><td></td>
  <td class="tagForm"></td>
  <td></td>
</tr>
<tr>
  <td class="tagForm">Medida:</td>
  <td><input type="text" name="medida" id="medida" size="40" value="<?=$field['Dimensiones'];?>" style="text-align:right;"/></td>
  <td></td>
  <td class="tagForm"><u>Informaci&oacute;n Monetaria</u></td>
  <td></td>
</tr>
<tr>
  <td class="tagForm">Pa&iacute;s de Fabricaci&oacute;n:</td>
  <td><select id="pais_fabricacion" class="selectMed">
  <?
     $spaises = "select * from mastpaises";
     $qpaises = mysql_query($spaises) or die ($spaises.mysql_error());
     $rpaises = mysql_num_rows($qpaises);
     
     for($i=0;$i<$rpaises;$i++){
       $fpaises = mysql_fetch_array($qpaises);
       if($fpaises['CodPais']==$field['FabricacionPais'])echo"<option value='".$fpaises['CodPais']."' selected>".$fpaises['Pais']."</option>";
       else echo"<option value='".$fpaises['CodPais']."'>".$fpaises['Pais']."</option>";
     }
     
   ?>
      </select></td>
  <td></td>
  <td class="tagForm">Monto Local:</td>
  <?
   $montoLocal = number_format($field['MontoLocal'],2,',','.');;
  ?>
  <td><input type="text" id="monto_local" name="monto_local" value="<?=$montoLocal;?>" style="text-align:right"/>Bs.F</td>
</tr>
<tr>
  <td class="tagForm">A&ntilde;o de Fabricaci&oacute;n:</td>
  <td><input type="text" id="ano_fabricacion" name="ano_fabricacion" size="8" style="text-align: right;" value="<?=$field['FabricacionAno'];?>"></td>
</tr>
<tr>
 <td class="tagForm"></td>
 <td></td>
</tr>
<tr>
 <td class="tagForm">Fecha de Ingreso:</td><? list($a, $m, $d) = SPLIT('[-]',$field['FechaIngreso']); $fechaIngreso = $d.'-'.$m.'-'.$a;?>
 <td><input type="text" name="fecha_ingreso" id="fecha_ingreso" size="8" onclick="crearPeriodo2(this.form, this.id);" value="<?=$fechaIngreso;?>" readonly/></td>
</tr>
<tr>
 <td class="tagForm">Periodo Registro:</td>
 <td><input type="text" name="periodo_registro" id="periodo_registro" size="8" value="<?=$field['PeriodoIngreso'];?>" readonly/></td>
</tr>
</table>
</div>
<!-- ****************************************************** COMIENZO TAB3 ************************************************ -->
<div id="tab3" style="display: none;">
<div style="width:900px; height=15px;" class="divFormCaption">Componentes de un Archivo</div>
<table id="principal" name="principal" width="900" align="center" class="tblForm">
</table>
</div>
<center><input type="submit" name="btGuardar" id="btGuardar" value="Guardar Registro"/><input type="button" name="btCancelar" id="btCancelar" value="Cancelar" onclick="cargarPagina(this.form,'<?=$regresar?>.php?limit=0');"/></center>
</form>
<!-- ****************************************************** COMIENZO TAB4 ************************************************ -->
