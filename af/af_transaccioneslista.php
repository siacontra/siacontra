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
<link href="css2.css" rel="stylesheet" type="text/css" />
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
<script type="text/javascript" src="../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/fscript.js" charset="utf-8"></script>
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

<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Transacciones</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table>
<hr width="100%" color="#333333" />
<?php
/// -------------------------
if(!$_POST) $fOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; else $cOrganismo = "checked"; 
if(!$_POST) $fDependencia = $_SESSION["FILTRO_DEPENDENCIA_ACTUAL"]; else{ $fDependencia = $_POST['fDependencia']; $cDependencia = "checked"; }

$filtro = "";
/// -------------------------
if($fOrganismo!=''){$filtro.=" AND (CodOrganismo='".$fOrganismo."')"; $cOrganismo = "checked";} else $dOrganismo = "disabled";
if($fDependencia!=''){$filtro.=" AND (CodDependencia='".$fDependencia."')"; $cDependencia="checked";} else $dDependencia = "disabled";
if($fContabilidad!=''){$filtro.=" AND (Contabilidad='".$fContabilidad."')"; $cContabilidad = "checked";} else $dContabilidad = "disabled";
/// -------------------------
if($fActivo!=''){$filtro.=" AND (Activo='".$fActivo."')"; $cActivo = "checked";}else $dActivo = "disabled";
if($fPeriodo!=''){$filtro.=" AND (Periodo='".$fPeriodo."')"; $cPeriodo = "checked";}else $dPeriodo = "disabled";
/// -------------------------
if ($fdesde != "" and $fhasta != "") { // FECHA DE REGISTRO DEL DOCUMENTO

  list($d, $m, $a)=SPLIT('[/.-]', $_POST['fdesde']); $fechadesde=$a.'-'.$m.'-'.$d;
  list($d, $m, $a)=SPLIT('[/.-]', $_POST['fhasta']); $fechahasta=$a.'-'.$m.'-'.$d;
  
	if ($fdesde != "") $filtro .= " AND (Fecha >= '$fechadesde')";
	if ($fhasta != "") $filtro .= " AND (Fecha <= '$fechahasta')"; 
	$cFecha = "checked"; 
	
	list($a, $m, $d)=SPLIT('[/.-]', $fechadesde); $fechadesde=$d.'-'.$m.'-'.$a;
    list($a, $m, $d)=SPLIT('[/.-]', $fechahasta); $fechahasta=$d.'-'.$m.'-'.$a;
	
} else $dFecha = "disabled";
if($fEstado!=''){$filtro.=" AND (Estado='".$fEstado."')"; $cEstado = "checked";}else $dEstado="disabled";


echo"<form name='frmentrada' id='frmentrada' action='af_transaccioneslista.php?limit=0&amp;campo=$campo' method='POST'>
<input type='hidden' name='tabla' id='tabla' value='$tabla'/>
<table class='tblForm' width='1000' height='50'>
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
     <td align='right'>Activo:</td>
     <td width='150'><input type='checkbox' id='chkActivo' name='chkActivo' value='1' $cActivo onclick='enabledActivosTransaccion(this.form);'/> <input type='text' name='fActivo' id='fActivo' size='10' value='$fActivo' $dActivo/></td>
     <td align='right'>Fecha:</td>
	 <td><input type='checkbox' id='chkFecha' name='chkFecha' value='1' $cFecha onclick='enabledFechaTransaccion(this.form);'/> desde <input type='text' id='fdesde' name='fdesde' value='$fdesde' size='8' maxlength='10' $dFecha/> hasta <input type='text' id='fhasta' name='fhasta' value='$fhasta' size='8' maxlength='10' $dFecha/></td>
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
   <td align='right'>Per&iacute;odo:</td>
   <td><input type='checkbox' id='chkPeriodo' name='chkPeriodo' value='1' $cPeriodo onclick='enabledPeriodoTransaccion(this.form);'/> <input type='text' id='fPeriodo' name='fPeriodo' value='$fPeriodo' size='10' disabled/></td>	
   <td align='right'>Estado:</td>
   <td><input type='checkbox' id='chkEstado' name='chkEstado' value='1' $cEstado onclick='enabledEstadoTransaccion(this.form);'/> <select id='fEstado' name='fEstado' class='selectSma' $dEstado><option value=''></option>"; getEstado($fEstado, 1); echo"</td>
  </tr>
   
   <tr>
     <td align='right'>Contabilidad:</td>
     <td><input type='checkbox' id='chkContabilidad' name='chkContabilidad' value='1' $cContabilidad onclick='enabledContabilidadTransacciones(this.form);'/>     <select id='fContabilidad' name='fContabilidad' class='selectMed' $dContabilidad> <option value=''></option>";
	 getContabilidad($fContabilidad,0);
	 echo" </select></td>
     <td></td>
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
                      CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and 
					  Naturaleza = 'BME' $filtro
              $filtro2 "; //echo $sa;
  $qa= mysql_query($sa) or die ($sa.mysql_error());
  $ra= mysql_num_rows($qa);
  
//// ----------------------------------------------------------------------------------------
$MAXLIMIT=30;
?>
<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td width="250"></td>
		<td align="right"><input type="button" id="btNuevo" name="btNuevo" value="Nuevo" class="btLista" onclick="nuevaTransaccion();"/><input type="button" id="btNuevo" name="btVer" value="Ver" class="btLista" onclick="verTransaccion();"/><input type="button" id="btModificar" name="btModificar" value="Modificar" class="btLista" onclick="modificarTransaccion();"/><input type="button" id="btEliminar" name="btEliminar" value="Eliminar" class="btLista" onclick="eliminarTransaccion();"/><input type="button" id="btAprobar" name="btAprobar" value="Aprobar" class="btLista" onclick="aprobarTransaccion();"/></td>
	</tr>
</table>
<input type="hidden" name="registro" id="registro"/>
<input type="hidden" name="ventana" id="ventana" value="<?=$_GET['ventana']?>"/>
<table align="center" cellpadding="0" cellspacing="0"><tr><td valign="top" style="height:100px; width:150px;">
<table align="center" width="400px"><tr><td align="center"><div style="overflow:scroll; height:300px; width:1000px;">
<table width="1400" class="tblLista">
<thead>
	<tr class="trListaHead">
		<th width="80" scope="col">Organismo</th>
        <th scope="col">Activo</th>
		<th scope="col">Descripcion</th>
        <th scope="col">Tipo</th>
        <th scope="col">Fecha</th>
        <th scope="col">Periodo</th>
        <th scope="col">Contabilidad</th>
        <th scope="col">Local Hist.</th>
        <th scope="col">Ajuste Hist.</th>
        <th scope="col">Estado</th>
	</tr>
 </thead>
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
</table></div></td></tr></table></td></tr></table>
</form>
</body>
</html>