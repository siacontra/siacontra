<?php
 session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include ("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<link href="css1.css" rel="stylesheet" type="text/css" />-->
<link href="css2.css" rel="stylesheet" type="text/css" />
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript01.js"></script>
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
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Baja de Activos | Lista Transacci&oacute;n</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table>
<hr width="100%" color="#333333" />

<? 
/// FILTRO QUE PERMITE REALIZAR BUSQUEDAS ESPECIFICAS
if(!$_POST) $fOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; else $cOrganismo = "checked"; 
if(!$_POST){ $fEstado = 'PR'; $cEstado = "checked";} 
$filtro = "";

if($fOrganismo!=""){$filtro .= " AND (Organismo = '".$fOrganismo."')"; $cOrganismo = "checked"; }else $dOrganismo = "disabled";
if($fDependencia != "") { $filtro .= " AND (Dependencia = '".$fDependencia."')"; $cDependencia = "checked"; } else $dDependencia = "disabled";
if($fContabilidad!=""){$filtro.=" and (Contabilidad='".$fContabilidad."')"; $cContabilidad = "checked";} else $dContabilidad = "disabled";
if($fActivo!=""){$filtro.=" and (Activo='".$fActivo."')"; $cActivo="checked";} else $dActivo="disabled";
if($fPeriodo!=""){$filtro.=" and (Periodo='".$fPeriodo."')"; $dPeriodo="checked";} else $dPeriodo="disabled";
if($fFecha!=""){$filtro.=" and (Fecha='".$fFecha."')"; $dFecha="checked";} else $dFecha="disabled";
if($fEstado!=""){$filtro.=" and (Estado ='".$fEstado."')"; $cEstado = "checked";} else $dEstado = "disabled";

echo"<form name='frmentrada' id='frmentrada' action='af_bajactivoslistar.php?limit=0' method='POST'>
<input type='hidden' name='limit' value='".$limit."'>
<table class='tblForm' width='1000' height='50'>
<tr>
   <td>
   <table align='center'>
   <tr>
       <td align='right' width='85'>Organismo:</td>
       <td align='left' width='200'>
	       <input type='checkbox' id='checkOrganismo' name='checkOrganismo' value='1' $cOrganismo onclick='this.checked=true;'/>
           <select name='fOrganismo' id='fOrganismo' class='selectMed' $dOrganismo>";
           getOrganismos($_SESSION['ORGANISMO_ACTUAL'],3);
           echo"
           </select>
       </td>
       <td align='right' width='90'>Activo:</td>
	   <td align='left' width='200'>
	      <input type='checkbox' name='checkActivo' id='checkActivo' value='1' $cActivo onclick='enabledActivo(this.form);'/>
		  <input type='text' id='fActivo' name='fActivo' value='$fActivo' style='text-align:right' $dActivo/>
	   </td>
	   <td align='right'>Fecha:</td>
       <td width='248'><input type='checkbox' name='checkFecha' id='checkFecha' value='1' $cFecha onclick='enabledFecha(this.form);'/>
	                   <input type='text' id='fFecha' name='fFecha' value='$fFecha' style='text-aling:right' $dFecha/>
		</td>	   
   </tr>
   
   <tr>
       <td align='right'>Dependencia:</td>
       <td align='left'>
	       <input type='checkbox' id='checkDependencia' name='checkDependencia' value='1' $cDependencia onclick='enabledDependencia(this.form);'/>
           <select name='fDependencia' id='fDependencia' class='selectMed' $dDependencia>
		      <option value=''></option>";
              getDependencias($fDependencia, $fOrganismo,  2);
           echo"
           </select>
       </td>
       <td align='right'>Periodo:</td>
       <td align='left'>
	      <input type='checkbox' id='checkPeriodo' name='checkPeriodo'  $cPeriodo value='1' onclick='enabledPeriodo(this.form);' />
		  <input type='text' id='fPeriodo' name='fPeriodo' value='$fPeriodo' maxlength='7' style='text-align:right' $dPeriodo/>
	   </td>
	   <td align='right'>Estado:</td>
       <td><input type='checkbox' id='checkEstado' name='checkEstado' value='1' $cEstado onclick='enabledEstado(this.form);'/>
	       <select id='fEstado' name='fEstado' class='selectMed' $dEstado>
		    <option></option>";
		      getEstado($fEstado, 3);
		   echo"
	       </select>
	   </td>
   </tr>
     
  <tr>
    <td align='right'></td>
    <td align='left'><input type='hidden' name='numLineas' id='numLineas' value='".$_POST['lineas']."'/>
    </td>
  </tr>
   
   </table>
   </td>
</tr>
</table>
<center><input type='submit' name='btBuscar' value='Buscar'/></center>
</form>";


//$valor = '123456';
//$v = substr($valor,0,4); echo '-///////////'.$v;
  /// CONSULTA PARA OBTENER DATOS DE LA TABLA A MOSTRAR
  $sa= "select 
              * 
		  from 
		      af_transaccionbaja 
         where 
              Organismo<>'' $filtro
         order by 
              Activo"; //echo $sa;
  $qa= mysql_query($sa) or die ($sa.mysql_error());
  $ra= mysql_num_rows($qa);
  
  //echo "<input type='text' name='linea' id='linea' value='$ra'/>";
?>

<form id="tabs" name="tabs">
<table class="tblForm" width="1000">
<tr>
  <td>

<table width="1000" class="tblLista">
 <tr> <input type="hidden" id="registro" name="registro"/>
  <td><div id="rows"># de Lineas:<?=$ra;?></div></td>
  <td align="right">
    <input type="button" name="btNuevo" id="btNuevo" class="btLista" value="Nuevo" onclick="cargarPaginaAgregar(this.form, 'af_bajactivosnuevo.php?regresar=af_bajactivoslistar&fOrganismo=<?=$fOrganismo;?>&fDependencia=<?=$fDependencia;?>&fContabilidad=<?=$fContabilidad;?>&fActivo=<?=$fActivo;?>&fPeriodo=<?=$fPeriodo;?>&fFecha=<?=$fFecha;?>&fEstado=<?=$fEstado;?>');"/>
    <input type="button" name="btVer" id="btVer" class="btLista" value="Ver" onclick="cargarOpcion(this.form,'af_bajactivosver.php?','BLANK', 'height=500, width=920, left=250, top=50, resizable=no');"/>
    <input type="button" name="btModificar" id="btModificar" class="btLista" value="Modificar" onclick="cargarOpcionListActEditar(this.form, 'af_listactivosmodificar.php?regresar=af_bajactivoslistar','SELF')"/>
    <input type="button" name="btEliminar" id="btEliminar" class="btLista" value="Eliminar" onclick="EliminarRegistros(this.form,'af_bajactivoslistar.php','1','APLICACIONES');"/>
    <input type="button" name="btAnular" id="btAnular" class="btLista" value="Anular" onclick="anularRegistro(this.form,'af_bajactivoslistar.php','anularRegistroTransaccionActivo');"/>
  </tr>
</table>

<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:300px;">
<table width="200%" class="tblLista" border="0">
<thead>
  <tr class="trListaHead">
		<th width="70" scope="col">Organismo</th>
        <th width="70" scope="col">Activo</th>
        <th width="70" scope="col">CodigoInterno</th>
		<th width="200" scope="col">Descripci&oacute;n</th>
		<th width="70" scope="col">Tipo</th>
		<th width="70" scope="col">Fecha</th>
    	<th width="70" scope="col">Periodo</th>
 	    <th width="70" scope="col">Estado</th>
        <th width="70" scope="col">Contabilidad</th>
        <th width="70" scope="col">Local Hist.</th>
        <th width="70" scope="col">Local Depr.</th>
        <th width="70" scope="col">Ajuste Hist.</th>
        <th width="70" scope="col">Ajuste Depr.</th>
  </tr>
 </thead>
  <?
  
  if($ra!=0){
      
   for($i=0;$i<$ra;$i++){
     $fa= mysql_fetch_array($qa);
	 if($fa['TipoActivo']=='I') $tipoActivo= 'Individual'; else $tipoActivo = 'Principal';
	 if($fa['Estado']=='PR') $estado = 'Preparación';
	 elseif($fa['Estado']=='AP') $estado = 'Aprobado';
	 else $estado='Anulado';
	 /// -------------------------------------------
	 $s_sitActivo = "select * from af_situacionactivo where  CodSituActivo= '".$fa['SituacionActivo']."'";
	 $q_sitActivo = mysql_query($s_sitActivo) or die ($s_sitActivo.mysql_error()) ;
	 $f_sitActivo = mysql_fetch_array($q_sitActivo);
	 /// -------------------------------------------
	 $s_catDeprec = "select * from af_categoriadeprec where CodCategoria = '".$fa['Categoria']."'";
	 $q_catDeprec = mysql_query($s_catDeprec) or die ($s_catDeprec.mysql_error());
	 $f_catDeprec = mysql_fetch_array($q_catDeprec);
	 /// -------------------------------------------
	 $s_mostrar = "select 
	                     cc.Descripcion as DescripCentroCosto,
						 ca.Descripcion as DescripClasificacionActivo
					 from
					     ac_mastcentrocosto cc,
						 af_clasificacionactivo ca
					where
					     cc.CodCentroCosto = '".$fa['CentroCosto']."' and 
						 ca.CodClasificacion = '".$fa['Clasificacion']."'";
	 $q_mostrar = mysql_query($s_mostrar) or die ($s_mostrar.mysql_error());
	 $f_mostrar = mysql_fetch_array($q_mostrar);
	 /// -------------------------------------------
     $s_ubicaciones = "select * from af_ubicaciones where CodUbicacion = '".$fa['Ubicacion']."'";
	 $q_ubicaciones = mysql_query($s_ubicaciones) or die ($s_ubicaciones.mysql_error());
	 $f_ubicaciones = mysql_fetch_array($q_ubicaciones);
	 /// -------------------------------------------
	 list($fiano, $fimes, $fidia)=split('[-]', $fa['FechaIngreso']);
	 $fecha_ingreso=$fidia.'-'.$fimes.'-'.$fiano;
     $id= $fa['Activo']."|".$fa['Organismo'];
     
    echo"<tr class='trListaBody' onclick='mClk(this, \"registro\");' id='$id'>
	    <td align='center'>".$fa['Organismo']."</td>
		<td align='center'>".$fa['Activo']."</td>
		<td align='center'>".$fa['CodigoInterno']."</td>
        <td align='left'>".htmlentities($fa['Descripcion'])."</td>
        <td align='left'>$tipoActivo</td>
		<td align='center'>$fecha_ingreso</td>
		<td align='center'>".$fa['PeriodoIngreso']."</td>
		<td align='center'>$estado</td>
        <td align='left'></td>
		<td align='left'></td>
		<td align='left'></td>
		<td align='left'></td>
		<td align='left'></td>
	</tr>";
    }
 }
  ?>
</table>
</div></td></tr>
</table>
</td></tr></table>
</form>
</body>
</html>
