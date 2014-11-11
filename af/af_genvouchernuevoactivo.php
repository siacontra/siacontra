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
<link href="css1.css" rel="stylesheet" type="text/css" />
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
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Generaci&oacute;n de Voucher | Nuevo Registro</td>
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


echo"<form name='frmentrada' id='frmentrada' action='af_genvouchernuevoactivo.php?limit=0&amp;campo=$campo' method='POST'>
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
     <td align='right' width='100'>Departamento:</td>";
	 $sql = "select Descripcion from mastaplicaciones where CodAplicacion ='".$_SESSION["APLICACION_ACTUAL"]."'";
	 $qry = mysql_query($sql) or die ($sql.mysql_error());
	 $field = mysql_fetch_array($qry);
     echo"<td width='80'><input type='text' name='fDepartamento' id='fDepartamento' size='16' value='".$field['Descripcion']."' disabled/></td>
      <td align='right' width='150'>Situaci&oacute;n Generaci&oacute;n:</td>
      <td><select id='sit_generacion' name='sit_generacion' class='selectMed'></select></td>
   </tr>
   
  <tr>
   <td align='right'>Aplicacion:</td>
   <td align='left'><input type='text' name='aplicacion' id='aplicacion' size='4' value='".$_SESSION["APLICACION_ACTUAL"]."' disabled/></td>
   <td align='right'>Sistema Fuente:</td>
   <td><input type='text' id='sistFuente' name='sistFuente' size='10' value='AUTOCONT' disabled/></td>
  </tr>
  
  <tr>
   <td align='right'>Periodo:</td>
   <td><input type='text' id='periodo_actual' name='periodo_actual' size='4' value='".date("Y-m")."'/></td>
   <td></td>
   <td></td>
   <td align='right'><input type='checkbox' id='sel_todo' name='sel_todo'/>Seleccionar Todo</td>
   <td></td>
  </tr>
   </table>
   </td>
</tr>
</table>
<center><input type='submit' name='btBuscar' value='Buscar'/> <input type='submit' id='generar' name='generar' value='Generar'/></center>
</form>";

  /// CONSULTA PARA OBTENER DATOS DE LA TABLA A MOSTRAR
  $sa= "select * from 
                      af_activo 
                where 
                      CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and 
					  GenerarVoucherIngresoFlag = 'S' and 
					  VoucherIngPub20 $filtro "; echo $sa;
  $qa= mysql_query($sa) or die ($sa.mysql_error());
  $ra= mysql_num_rows($qa);
  
//// ----------------------------------------------------------------------------------------
$MAXLIMIT=30;
?>
<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td width="250"></td>
		<td align="right"><!--<input type="button" id="btNuevo" name="btNuevo" value="Nuevo" class="btLista" onclick="nuevaTransaccion();"/><input type="button" id="btNuevo" name="btVer" value="Ver" class="btLista" onclick="verTransaccion();"/><input type="button" id="btModificar" name="btModificar" value="Modificar" class="btLista" onclick="modificarTransaccion();"/><input type="button" id="btEliminar" name="btEliminar" value="Eliminar" class="btLista" onclick="eliminarTransaccion();"/><input type="button" id="btAprobar" name="btAprobar" value="Aprobar" class="btLista" onclick="aprobarTransaccion();"/>--></td>
	</tr>
</table>
<input type="hidden" name="registro" id="registro"/>
<input type="hidden" name="ventana" id="ventana" value="<?=$_GET['ventana']?>"/>
<table align="center" cellpadding="0" cellspacing="0"><tr><td valign="top" style="height:100px; width:150px;">
<table align="center" width="400px"><tr><td align="center"><div style="overflow:scroll; height:300px; width:1000px;">
<table width="1400" class="tblLista">
	<tr class="trListaHead">
		<th width="90" scope="col">Fecha Ingreso</th>
        <th scope="col">Activo</th>
		<th scope="col" width="200">Descripcion</th>
        <th scope="col">Fuente</th>
        <th scope="col">Monto Local</th>
        <th scope="col">Centro Costo</th>
        <th scope="col">CC Destino</th>
        <th scope="col">Cod. Interno</th>
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
</table></div></td></tr></table></td></tr></table>
</body>
</html>