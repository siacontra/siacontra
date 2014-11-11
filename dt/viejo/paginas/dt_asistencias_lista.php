<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include ("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<link href="css1.css" rel="stylesheet" type="text/css" />-->
<link href="../../css/estilo.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="../../af/af_fscript.js"></script>
<script type="text/javascript" language="javascript" src="../../af/af_fscript_02.js"></script>
<script type="text/javascript" src="../../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/fscript.js" charset="utf-8"></script>
<!--<style type="text/css">

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
		<td class="titulo">Lista de Asistencias Tecnicas</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table>
<hr width="100%" color="#333333" />


<form id="tabs" name="tabs">
<table class="tblForm" width="1000">
<tr>
  <td>


<table width="1000" class="tblLista">
 <tr> <input type="hidden" id="registro" name="registro"/>
  <td><div id="rows"></div></td>
  <td align="right"></td>
  <td align="right">
    <input type="button" name="btSolicitar"		id="btSolicitar"  class="btLista" value="Solicitar" onclick="cargarPaginaAgregar(this.form, 'dt_asistencia_nueva.php?regresar=dt_asistencias_lista&fEstado=<?=$fEstado;?>&fOrganismo=<?=$fOrganismo;?>&fBuscarPor=<?=$fBuscarPor;?>&fDependencia=<?=$fDependencia;?>&fSituacionActivo=<?=$fSituacionActivo;?>&fClasf20=<?=$fClasf20;?>&DescpClasf20=<?=$DescpClasf20;?>&fClasificacion=<?=$fClasificacion;?>&fubicacion=<?=$fubicacion;?>&BuscarValor=<?=$BuscarValor;?>&fubicacion2=<?=$fubicacion2;?>&DescpClasificacion=<?=$DescpClasificacion;?>');"/>
    <input type="button" name="btAprobar"       id="btAprobar"    class="btLista" value="Aprobar"   onclick="cargarPaginaAgregar(this.form, 'dt_asistencia_aprobar.php?regresar=dt_asistencias_lista&fEstado=<?=$fEstado;?>&fOrganismo=<?=$fOrganismo;?>&fBuscarPor=<?=$fBuscarPor;?>&fDependencia=<?=$fDependencia;?>&fSituacionActivo=<?=$fSituacionActivo;?>&fClasf20=<?=$fClasf20;?>&DescpClasf20=<?=$DescpClasf20;?>&fClasificacion=<?=$fClasificacion;?>&fubicacion=<?=$fubicacion;?>&BuscarValor=<?=$BuscarValor;?>&fubicacion2=<?=$fubicacion2;?>&DescpClasificacion=<?=$DescpClasificacion;?>');"/>
    <input type="button" name="btAnular"        id="btAnular"     class="btLista" value="Anular"    onclick="cargarPaginaAgregar(this.form, 'dt_asistencia_anular.php?regresar=dt_asistencias_lista&fEstado=<?=$fEstado;?>&fOrganismo=<?=$fOrganismo;?>&fBuscarPor=<?=$fBuscarPor;?>&fDependencia=<?=$fDependencia;?>&fSituacionActivo=<?=$fSituacionActivo;?>&fClasf20=<?=$fClasf20;?>&DescpClasf20=<?=$DescpClasf20;?>&fClasificacion=<?=$fClasificacion;?>&fubicacion=<?=$fubicacion;?>&BuscarValor=<?=$BuscarValor;?>&fubicacion2=<?=$fubicacion2;?>&DescpClasificacion=<?=$DescpClasificacion;?>');"/>
    <input type="button" name="btAsignar"       id="btAsignar"    class="btLista" value="Asignar"   onclick="cargarPaginaAgregar(this.form, 'dt_asistencia_asignacion.php?regresar=dt_asistencias_lista&fEstado=<?=$fEstado;?>&fOrganismo=<?=$fOrganismo;?>&fBuscarPor=<?=$fBuscarPor;?>&fDependencia=<?=$fDependencia;?>&fSituacionActivo=<?=$fSituacionActivo;?>&fClasf20=<?=$fClasf20;?>&DescpClasf20=<?=$DescpClasf20;?>&fClasificacion=<?=$fClasificacion;?>&fubicacion=<?=$fubicacion;?>&BuscarValor=<?=$BuscarValor;?>&fubicacion2=<?=$fubicacion2;?>&DescpClasificacion=<?=$DescpClasificacion;?>');"/>  
    <input type="button" name="btModificar"     id="btModificar"  class="btLista" value="Modificar" onclick="cargarOpcionListActEditar(this.form, 'af_activosmenoreseditar.php?regresar=af_activosmenores&fEstado=<?=$fEstado;?>&fOrganismo=<?=$fOrganismo;?>&fBuscarPor=<?=$fBuscarPor;?>&fDependencia=<?=$fDependencia;?>&fSituacionActivo=<?=$fSituacionActivo;?>&fClasf20=<?=$fClasf20;?>&DescpClasf20=<?=$DescpClasf20;?>&fClasificacion=<?=$fClasificacion;?>&fubicacion=<?=$fubicacion;?>&BuscarValor=<?=$BuscarValor;?>&fubicacion2=<?=$fubicacion2;?>&DescpClasificacion=<?=$DescpClasificacion;?>','SELF')"/>
    <input type="button" name="btEvaluar"       id="btEvaluar"    class="btLista" value="Evaluar"   onclick="cargarPaginaAgregar(this.form, 'dt_asistencia_evaluar.php?regresar=dt_asistencias_lista&fEstado=<?=$fEstado;?>&fOrganismo=<?=$fOrganismo;?>&fBuscarPor=<?=$fBuscarPor;?>&fDependencia=<?=$fDependencia;?>&fSituacionActivo=<?=$fSituacionActivo;?>&fClasf20=<?=$fClasf20;?>&DescpClasf20=<?=$DescpClasf20;?>&fClasificacion=<?=$fClasificacion;?>&fubicacion=<?=$fubicacion;?>&BuscarValor=<?=$BuscarValor;?>&fubicacion2=<?=$fubicacion2;?>&DescpClasificacion=<?=$DescpClasificacion;?>');"/>
    <input type="button" name="btPdf" 			id="btPdf"        class="btLista" value="PDF"       onclick="cargarOpcion(this.form,'dt_asistencia_pdf.php?','BLANK', 'height=600, width=920, left=250, top=50, resizable=no');"/>
    <input type="button" name="btVer" 			id="btVer"        class="btLista" value="Ver"       onclick="cargarOpcion(this.form,'dt_asistencia_ver.php?','BLANK', 'height=600, width=920, left=250, top=50, resizable=no');"/>
  
   
   </tr>
  
  
</table>


<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1100px; height:300px;">
<table width="1500" class="tblLista">
<thead>
  <tr class="trListaHead">
		<th width="40" align="center">Codigo</th>
		<th width="80" align="center">Fecha Solicitud</th>
		<th width="60" align="center">Status</th>
		
    	<th width="200" align="center">Asunto</th>
        <th width="250" align="center">Funcionario</th>
        <th width="250" align="center">Dependencia (Ubicacion)</th>
        <th width="80" align="center">Fecha Aprobacion</th>
		<th width="80" align="center">Fecha Ejecucion</th>
		<th width="80" align="center">Fecha Finalizacion</th>

  </tr>
  </thead>
  <?
  include('../paginas/acceso_db.php');
  $ra=1;
  if($ra!=0){
      
 //  for($i=0;$i<$ra;$i++){
     mysql_query ("SET NAMES 'utf8'");
	 /// -------------------------------------------
     $query = "
						SELECT
						dt_asistencia.co_asistencia,
						dt_asistencia.co_persona,
						dt_asistencia.co_unidad,
						dt_asistencia.co_modalidad,
						dt_asistencia.co_evaluacion,
						dt_asistencia.fe_solicitud,
						dt_asistencia.fe_aprobacion,
						dt_asistencia.fe_ejecucion,
						dt_asistencia.fe_finalizacion,
						dt_asistencia.tx_status,
						dt_asistencia.tx_observacion,
						dt_asistencia.tx_asunto
						FROM
						dt_asistencia  order by co_asistencia DESC
      ";
	 $resultado = mysql_query($query) or die ($query.mysql_error());
	 
	 while ( $row = mysql_fetch_array($resultado)  )	
		{	 	
			 /// -------------------------------------------
				echo"<tr class='trListaBody' onclick='mClk(this, \"registro\");' id='".$row['co_asistencia']."'>
				<td align='center'>".$row['co_asistencia']."</td>
				<td align='center'>".$row['fe_solicitud']."</td>
				<td align='center'>".$row['tx_status']."</td>
				
				
				<td align='center'>".$row['tx_asunto']."</td>
				<td align='center'>".$row['co_persona']."</td>
				<td align='center'>".$row['co_unidad']."</td>
				<td align='center'>".$row['fe_aprobacion']."</td>
				<td align='center'>".$row['fe_ejecucion']."</td>
				<td align='center'>".$row['fe_finalizacion']."</td>

				</tr>";
			}
	
			
   // }
 }
  ?>
</table></div>
</td></tr>
</table>
</td></tr></table>
</form>
</body>
</html>
