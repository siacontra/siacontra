<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location:index.php");
//	------------------------------------
include("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);
//	------------------------------------
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="fscriptpresupuesto.js"></script>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Reformulaci&oacute;n | Listar</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?php
echo"<input type='hidden' id='regresar' name='regresar' value='reformulacion_aprobar'/>";

include "gpresupuesto.php";
if(!$_POST){ $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; $corganismo = "checked";}else  $corganismo = "checked";

$MAXLIMIT=30;
$filtro = "";
if($forganismo != ""){ $filtro .= " AND (Organismo = '".$forganismo."')"; $corganismo = "checked"; } //else $dorganismo = "disabled"; /// ORGANISMO
if($fpreparado != ""){ $filtro .= " AND (PreparadoPor = '".$fpreparado."')"; $cpreparado = "checked"; } else $dpreparado = "disabled"; /// PREPARADO POR
if($fnpresupuesto != "") { $filtro .= " AND (CodPresupuesto = '".$fnpresupuesto."')"; $cnpresupuesto = "checked"; } else $dnpresupuesto = "disabled"; /// CODIGO pRESUPUESTO
if($fstatus != "") { $filtro .= " AND (Estado = '".$fstatus."')"; $cstatus = "checked"; } else $dstatus = "disabled";
if ($fnref != "") { $filtro .= " AND (CodRef = '".$fnref."')"; $cnref = "checked"; } else $dnref = "disabled"; /// CODIGO DE REFORMULACION
if ($fdesde != "" || $fhasta != "") {
	if ($fdesde != "") $filtro .= " AND (FechaAjuste >= '".$fdesde."')";
	if ($fhasta != "") $filtro .= " AND (FechaAjuste <= '".$fhasta."')"; 
	$cref = "checked"; 
} else $dref = "disabled";
if($f_oficio!=""){$filtro .= "AND (NumOficio = '".$f_oficio."')"; $coficio="cheked";}else $doficio="disabled";

if ($ftajuste != "") { $filtro .= " AND (aj.TipoAjuste = '".$ftajuste."')"; $ctajuste = "checked"; } else $dtajuste = "disabled";

//if ($fejercicio != "") { $filtro .= " AND (EjercicioPpto = '".$fejercicio."')"; $cejercicio = "checked"; } else $dejercicio = "disabled";




//	-------------------------------------------------------------------------------
$MAXLIMIT=30;
//	-------------------------------------------------------------------------------
echo "
<form name='frmentrada' action='reformulacion_listar.php?limit=0' method='POST'>";
echo" <input type='hidden' name='limit' id='limit' value='".$limit."'/>
      <input type='hidden' name='registros' id='registros' value='".$registros."'/>

<div class='divBorder' style='width:900px;'>
<table width='900' class='tblFiltro'>
<tr>
 <td width='50' align='right'>Organismo:</td>
 <td width='100'>
  <input type='checkbox' name='chkorganismo' id='chkorganismo' value='1' $corganismo onclick='this.checked=true' />
  <select name='forganismo' id='forganismo' class='selectBig' $dorganismo>
        <option value=''></option>";
		getOrganismos($forganismo, 3, $_SESSION[ORGANISMO_ACTUAL]);
		echo "
   </select>
 </td>
 <td width='105' align='right'>Nro. Oficio:</td>
 <td>
  <input type='checkbox' name='chkoficio' id='chkoficio' value='1' $coficio onclick='enabledNrOficio(this.form);' />
  <input type='text' name='f_oficio' id='f_oficio' size='6' maxlength='4' $doficio value='$f_oficio' />
  </td>
</tr>

<tr>
  <td width='125' align='right'>Preparado Por:</td>
  <td><input type='checkbox' name='chkpreparado' id='chkpreparado' value='1' $cpreparado onclick='enabledPreparado(this.form);' />
		<select name='fpreparado' id='fpreparado' class='selectBig' $dpreparado>
		  <option value=''></option>";
			getPreparadoPor($fpreparado, 0);
		    echo "
	    </select>
  </td>
  <td width='80' align='right'>Estado:</td>
  <td><input type='checkbox' name='chkstatus' id='chkstatus' value='1' $cstatus onclick='enabledStatus(this.form);' />
	  <select name='fstatus' id='fstatus' class='selectMed' $dstatus>";
				getEstadoReformulacion($fstatus, 0);
				echo "
			</select>
  </td>
</tr>



<tr>"; if($_POST[fpreparado]!=''){
          $fpreparado2=$_POST[fpreparado];
        }
    
echo "<td width='90' align='right'>Nro. Presupuesto:</td>
  <td width='150'>
	<input type='checkbox' name='chknpresupuesto' id='chknpresupuesto' value='1' $cnpresupuesto onclick='enabledPNpresupuesto(this.form);' />
	<input type='text' name='fnpresupuesto' id='fnpresupuesto' size='6' maxlength='4' $dnpresupuesto value='$fnpresupuesto' />
  </td>
  <td width='80' align='right'>Fecha Reformulaci&oacute;n:</td>
 <td width='78' align='left'>
  <input type='checkbox' name='chkfref' id='chkfref' value='1' $cref onclick='enabledFref(this.form);' />
  <input type='text' name='fdesde' id='fdesde' size='15' maxlength='10' $dref value='$fdesde'  onkeyup='getTotalDiasPermisos();' /> - 
  <input type='text' name='fhasta' id='fhasta' size='15' maxlength='10' $dref value='$fhasta'  onkeyup='getTotalDiasPermisos();' />
 </td>
</tr>

<tr>
  <td width='90' align='right'>Nro. Reformulaci&oacute;n:</td>
  <td>
	<input type='checkbox' name='chknref' id='chknref' value='1' $cnref onclick='enabledPNref(this.form);' />
	<input type='text' name='fnref' id='fnref' size='6' maxlength='4' $dnref value='$fnref' />
  </td>
 <td width='80' align='right'></td>
 <td width='78' align='left'></td>
</tr>

</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar'></center>
<br /><div class='divDivision'>Listado de Ajustes</div><br />
<form/>"; 
//	-------------------------------------------------------------------------------

$fecha=date("Y-m");
$ano=date("Y");
$sql="SELECT *
	    FROM 
			pv_reformulacionppto 
	   WHERE 
			Organismo='".$_SESSION['ORGANISMO_ACTUAL']."' $filtro
    ORDER BY CodRef"; //echo $sql;
$qry=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($qry);
$registros=$rows;
?>

<table width="900" class="tblBotones">
 <tr>
  <td><div id="rows"></div></td>
  <td align="center">
	<?php 
	echo"<input type='hidden' name='regresar' id='regresar' value='reformulacion_listar'/>" ;
	
	echo "
	 <table align='center'>
	 <tr>
	  <td>
		<input name='btPrimero' type='button' id='btPrimero' value='&lt;&lt;' disabled onclick='setLotes(this.form, \"P\", $registros, ".$limit.");' />
		<input name='btAtras' type='button' id='btAtras' value='&lt;' disabled onclick='setLotes(this.form, \"A\", $registros, ".$limit.");' />
	  </td>
	  <td>Del</td><td><div id='desde'></div></td>
	  <td>Al</td><td><div id='hasta'></div></td>
	  <td>
		<input name='btSiguiente' type='button' id='btSiguiente' value='&gt;' disabled onclick='setLotes(this.form, \"S\", $registros, ".$limit.");' />
		<input name='btUltimo' type='button' id='btUltimo' value='&gt;&gt;' disabled onclick='setLotes(this.form, \"U\", $registros, ".$limit.");' />
	  </td>
	 </tr>
	</table>";
   ?> 
  </td>
<td align="right">
<input name="btMostrar" type="button" class="btLista" id="btMostrar" value="Nuevo" onclick="cargarPagina(this.form, 'reformulacion_datosgenerales.php');"/>
<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'reformulacion_editar.php?accion=EDITAR', 'SELF');" />
<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarReformulacion(this.form, 'reformulacion_listar.php?accion=ELIMINARPROG', '1', 'APLICACIONES');" />
<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'reformulacion_ver.php', 'BLANK', 'height=550, width=950, left=200, top=150, resizable=no');" /> 
<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarOpcion(this.form, 'reformulacion_pdf.php', 'BLANK', 'height=800, width=800, left=200, top=200, resizable=yes');" /> |  
<input name="btAnular" type="button" class="btLista" id="btAnular" value="Anular" onclick="anularReformulacion(this.form);" />
	</td>
  </tr>
</table>

<input type="hidden" name="registro" id="registro"/>
<table align="center">
<tr>
  <td align="center">
  <div style="overflow:scroll; width:920px; height:300px;">
  
<table width="900" class="tblLista">
  <tr class="trListaHead">
   <th width="50" scope="col"># Presupuesto</th>
   <th width="50" scope="col"># Reformulaci&oacute;n</th>
   <th width="50" scope="col">F. Creaci&oacute;n</th>
   <th width="350" scope="col">Descripci&oacute;n</th>
   <th width="50" scope="col">Estado</th>
  </tr>
<?
/// ------------------------------------------------------------

$registros=$rows;
if($registros!=0){ 
for($i=0; $i<$registros; $i++){
   $field=mysql_fetch_array($qry); 
   if($field['Estado']=='PR'){$estado=Pendiente;}else{if($field['Estado']=='AP'){$estado=Aprobado;}else{$estado=Anulado;}}
   $id = $field['Organismo'].'-'.$field['CodPresupuesto'].'-'.$field['CodRef'];
   echo "
   <tr class='trListaBody' onclick='mClk(this,\"registro\");' id='$id'>
   <td align='center'>".$field['CodPresupuesto']."</td>";
   list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaPreparacion']); $fcreacion=$d.'-'.$m.'-'.$a;
   list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaFin']); $fFin=$d.'-'.$m.'-'.$a;
   echo"
   <td align='center'>".$field['CodRef']."</td>
   <td align='center'>$fcreacion</td>
   <td align='left'>".$field['Descripcion']."</td>
   <td align='center'>$estado</td>
   </tr>";
}}
$rows=(int)$rows;
echo "
<script type='text/javascript' language='javascript'>
	totalReformulacion($registros,\"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
	totalLotes($registros, $rows, ".$limit.");
</script>";	
//	-------------------------------------------------------------------------------
?>
</table>
</div>
</td></tr>
</table>
</body>
</html>
