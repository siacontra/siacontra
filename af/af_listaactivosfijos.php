<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
/// ------------------------
include("fphp.php");
connect();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<link href="css1.css" rel="stylesheet" type="text/css" />-->
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../../css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
<!--<script type="text/javascript" language="javascript" src="JavaScript_JQuery.js"></script>-->
<script type="text/javascript" src="../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/fscript.js" charset="utf-8"></script>
<!--<script type="text/javascript">
$(document).ready(function(){
   $("a.external").live('click', function() {
      url = $(this).attr("href");
      window.open(url, '_blank');
      return false;
   });
});
</script>-->

</head>

<body>
<div id="cajaModal"></div>
<!-- pretty -->
<span class="gallery clearfix"></span>

<table width="900" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Activos</td>
        <td></td>
		<!--<?
        if($_GET['cierre']!=1){?>
        <td align="right"><a class="cerrar"; href="javascript:parent.$.prettyPhoto.close();">[Cerrar]</a></td>
        <? }else{?>
		<td align="right"><a class="cerrar"; href="javascript:window.close();">[Cerrar]</a></td>
        <? }?>-->
	</tr>
</table><hr width="900" color="#333333" />
<?php
/// -------------------------
if(!$_POST) $fOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; else $cOrganismo = "checked"; 
if(!$_POST) $fDependencia = $_SESSION["FILTRO_DEPENDENCIA_ACTUAL"]; else{ $fDependencia = $_POST['fDependencia']; $cDependencia = "checked"; }
if(!$_POST) {$fOrdenarPor = "Activo"; $dOrdenarPor="cheked";}

$filtro = "";
$filtro2 = "";
/// -------------------------
if($fOrganismo!=''){$filtro.=" AND (CodOrganismo='".$fOrganismo."')"; $cOrganismo = "checked";} else $dOrganismo = "disabled";
if($fDependencia!=''){$filtro.=" AND (CodDependencia='".$fDependencia."')"; $cDependencia="checked";} else $dDependencia = "disabled";
if($fNaturaleza!=''){$filtro.=" AND (Naturaleza ='".$fNaturaleza."')"; $cNaturaleza = "checked";} else $dNaturaleza = "disabled";
if($fOrdenarPor!=''){$filtro2.=" order by ".$fOrdenarPor.""; $cOrdenarPor="checked";}else $dOrdenarPor="disabled";
if($fUbicacion!=''){$filtro.=" AND (Ubicacion ='".$fUbicacion."')"; $cUbicacion="checked";}else $dUbicacion="disabled";
if($fCentroCosto!=''){$filtro.=" AND (CentroCosto='".$fCentroCosto."')"; $cCentroCosto="checked";}else $dCentroCosto="disabled";
if($fPersona!=''){$filtro.=" AND (EmpleadoUsuario = '".$fPersona."')"; $cPersona="checked";}else $dPersona = "disabled";

echo"<form name='frmentrada' id='frmentrada' action='af_listaactivosfijos.php?limit=0&amp;campo=$campo' method='POST'>
<input type='hidden' name='tabla' id='tabla' value='$tabla'/>
<table class='tblForm' width='900' height='50'>
<tr>
   <td>
   <table>
   <tr>
       <td align='right' width='100'>Organismo:</td>
       <td align='left' width='335'>
	       <input type='checkbox' id='checkOrganismo' name='checkOrganismo' value='1' $cOrganismo onclick='this.checked=true;'/>
           <select name='fOrganismo' id='fOrganismo' class='selectBig' $dOrganismo>";
           getOrganismos($_SESSION['ORGANISMO_ACTUAL'],3);
           echo"
           </select>
       </td>
       <td align='right'>Ubicaci&oacute;n:</td>
       <td class='gallery clearfix'><input type='checkbox' id='chkUbicacion' name='chkUbicacion' value='1' $cUbicacion onclick='enabledUbicacionActivos(this.form);'/> <input type='text' name='fUbicacion' id='fUbicacion' size='10' value='$fUbicacion' $dUbicacion/><input type='text' name='DescpUbicacion' id='DescpUbicacion' value='$DescpUbicacion' size='35' $dUbicacion/>";?><input type="hidden" id="btUbicacion" name="btUbicacion" value="..." onclick="cargarVentana(this.form,'af_listaubicacionesactivo.php?limit=0&campo=21','height=500,width=850,left=200,top=100,resizable=yes');" <?=$dUbicacion;?>/>
       <a id="ubicacion" onclick="cargarVentana(this.form,'af_listaubicacionesactivo.php?limit=0&campo=21','height=500,width=850,left=200,top=100,resizable=yes');"  style="visibility:hidden">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;"/>
            </a>
			<!--<a id="ubicacion" onclick="cargarVentana(this.form,'af_listaubicacionesactivo.php?limit=0&campo=21&cierre=1','height=500,width=850,left=200,top=100,resizable=yes');"  style="visibility:hidden" rel="prettyPhoto[iframe1]">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;"/>
            </a>-->
			<? echo"</td>
   </tr>
   
  <tr>
   <td align='right'>Dependencia:</td>
   <td align='left'>
	   <input type='checkbox' id='checkDependencia' name='checkDependencia' value='1' $cDependencia onclick='enabledDependencia(this.form);'/>
	   <select name='fDependencia' id='fDependencia' class='selectBig' $dDependencia>";
		  //getDependencias($fDependencia, $fOrganismo,  2);
		  getDependenciaSeguridad($fDependencia, $fOrganismo, 3);
	   echo"
	   </select>
   </td>
   <td align='right'>Centro de Costo:</td>
   <td class='gallery clearfix'><input type='checkbox' id='chkCentroCosto' name='chkCentroCosto' value='1' $cCentroCosto onclick='enabledCentroCostoActivos(this.form);'/> <input type='text' id='fCentroCosto' name='fCentroCosto' value='$fCentroCosto' size='10' $dCentroCosto/><input type='text' id='fCentroCosto2' name='fCentroCosto2' value='$fCentroCosto2' size='35' $dCentroCosto/>";?>
<input type="hidden" id="btCentroCosto" name="btCentroCosto" value="..." onclick="cargarVentana(this.form,'af_listacentroscostos.php?limit=0&campo=22','height=500,width=850,left=200,top=100,resizable=yes');" <?=$dCentroCosto;?> />
<a id="c_costos" onclick="cargarVentana(this.form,'af_listacentroscostos.php?limit=0&campo=22&cierre=1','height=500,width=850,left=200,top=100,resizable=yes');"  style="visibility:hidden">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;"/>
            </a><? echo" </td>	
  </tr>
   
   <tr>
     <td align='right'>Naturaleza:</td>
	 <td><input type='checkbox' id='chkNaturaleza' name='chkNaturaleza' value='1' $cNaturaleza onclick='enalbledNaturalezaActivoFijos(this.form);'/> <select id='fNaturaleza' name='fNaturaleza' class='selectMed' $dNaturaleza>
	 <option value=''></option>";
	 getNaturaleza($fNaturaleza,0);
	echo"</select></td>
	 <td align='right'>Persona:</td>
	 <td class='gallery clearfix'><input type='checkbox' id='chkPersona' name='chkPersona' value='1' $cPersona onclick='enabledPersonaActivo(this.form);'/> <input type='text' id='fPersona' name='fPersona' value='$fPersona' size='10' $dPersona/><input type='text' id='NombPersona' name='NombPersona' size='35' value='$NombPersona' $dPersona/>";?><input type="hidden" id="btPersona" name="btPersona" value="..." onclick="cargarVentana(this.form,'af_listaempleados.php?limit=0&campo=23','height=500,width=850,left=200,top=100,resizable=yes');" <?=$dPersona;?> />
     <a id="persona" onclick="cargarVentana(this.form,'af_listaempleados.php?limit=0&campo=23&cierre=1','height=500,width=850,left=200,top=100,resizable=yes');"  style="visibility:hidden">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;"/>
            </a><? echo" </td>
   </tr>
   
   <tr>
     <td align='right'>Ordenar Por:</td>
	 <td><input type='checkbox' id='chkOrdenarPor' name='chkOrdenarPor' value='1' $cOrdenarPor onclick='enabledOrdenarPor(this.form);'/> <select id='fOrdenarPor' name='fOrdenarPor' class='selectMed' $dOrdenarPor>
	     <option value=''></option>";
		 getOrdenarPor($fOrdenarPor,0);
		 echo"</select></td>
	 <td align='right'></td>
	 <td></td>
   </tr>
   </table>
   </td>
</tr>
</table>
<center><input type='submit' name='btBuscar' value='Buscar'/></center>
</form>";

  /// CONSULTA PARA OBTENER DATOS DE LA TABLA A MOSTRAR
  $sa= "select * from 
                      af_activo 
                where 
                      CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' $filtro
				     $filtro2 "; //echo $sa;
  $qa= mysql_query($sa) or die ($sa.mysql_error());
  $ra= mysql_num_rows($qa); 
  
//// ----------------------------------------------------------------------------------------
//$MAXLIMIT=30;
?>

<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="ventana" id="ventana" value="<?=$_GET['ventana']?>"/>
<center>
<div style="overflow:scroll; height:300px; width:900px;">
<table width="1000" class="tblLista">
<thead>
	<tr class="trListaHead">
		<th scope="col"># Activo</th>
        <th scope="col">Cod.Interno</th>
		<th scope="col">Descripci&oacute;n</th>
        <th scope="col">Nro. Serie</th>
        <th scope="col">Modelo</th>
        <th scope="col">Nro.Placa</th>
        <th scope="col">Ubicaci&oacute;n</th>
        <th scope="col">Centro Costos</th>
        <th scope="col">Persona Resp.</th>
        <th scope="col">Cargo</th>
        <th scope="col">Activo Consolidado</th>
        <th scope="col">Estado</th>
	</tr>
   </thead>
	<?php 
	if ($ra!=0) {
		//	CONSULTO LA TABLA
		if ($_POST['filtro']!="") $sql="SELECT 
		                                       *
										  FROM 
										       af_activo
									     WHERE 
										      (Activo LIKE '%".$_POST['filtro']."%' OR Descripcion LIKE '%".$_POST['filtro']."%')
									  ORDER BY 
									          Activo";
		else $sql="SELECT
						*
  					FROM
						af_activo
			   ORDER BY
                        Activo";
						
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//	MUESTRO LA TABLA
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
						
			if($ventana=="insertarDestinatarioDep"){
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
				$MOSTRAR = $field['CentroCosto'].'|'.$f_consulta['DescpCC'].'|'.$field['Ubicacion'].'|'.$f_consulta['DescpUB'].'|'.$f_consulta['DescpSitActivo'].'|'.$field['CodigoInterno'].'|'.$field['Naturaleza'];
				/// ------------------------------------------------------
			echo "
			<tr class='trListaBody' onclick='mClk(this, \"registro\"); selEmpleado(\"".$field['Busqueda']."\", ".$_GET['campo'].", \"".$field['Descripcion']."\", \"".$field['Clasificacion']."\", \"".$f_consulta['DescpCA']."\", \"$MOSTRAR\");' id='".$field['Activo']."'>
			    <td align='center' width='500'>".$field['Activo']."</td>
				<td align='center'>".$field['CodigoInterno']."</td>
				<td align='left'>".$field['Descripcion']."</td>
				<td align='center'>".$field['NumeroSerie']."</td>
				<td align='center'>".$field['Modelo']."</td>
				<td align='center'>".$field['NumeroPlaca']."</td>
				<td align='left'>".$f_consulta['DescpUB']."</td>
				<td align='left'>".$f_consulta['DescpCC']."</td>
				<td align='left'>".$f_consulta2['NomCompleto']."</td>
				<td align='left'>".$f_consulta2['DescripCargo']."</td>
				<td align='center'>".$field['ActivoConsolidado']."</td>
				<td align='left'>$estado</td>
			</tr>";
		}}
	}
	$rows=(int)$rows;
	/*echo "
	<script type='text/javascript' language='javascript'>
		totalLista($registros);
		totalLotes($registros, $rows, ".$_GET['limit'].");
	</script>";	*/			
	?>
</table></div></center>
</form>
</body>
</html>