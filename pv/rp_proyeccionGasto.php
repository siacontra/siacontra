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
<script type="text/javascript" language="javascript" src="r_fscript.js"></script>
<script>
/// ------------------------------------------------------------------------
///        ************* MOSTRAR EJECUCION PRESUPUESTARIA POR RANGO DE FECHA
function filtroReporteProyeccion(form, limit) {
	
	
	var mesReferencia 	= document.getElementById("sel_ultimomes").value;
	var anio 			= document.getElementById("fejercicio").value;
	var codPresupuesto 	= document.getElementById("fnpresupuesto").value;
	
	/*if(form.chkorganismo.checked) filtro+=" and Organismo=*"+form.forganismo.value+"*";
	if(form.chkejercicioPpto.checked) filtro+=" and EjercicioPpto=*"+form.fejercicioppto.value+"*";
	if(form.chknropresupuesto.checked) filtro+=" and CodPresupuesto=*"+form.fnropresupuesto.value+"*";*/
	
	/*if(form.chkFecha.checked){
		var fdesde = new String (document.getElementById("fdesde").value);
		var fhasta = new String (document.getElementById("fhasta").value);
		var fdesde = fdesde.split("-");
		    fechaDesde = fdesde[2]+"-"+fdesde[1]+"-"+fdesde[0]; //alert(fd);
			periodoDesde = fdesde[2]+"-"+fdesde[1]; //alert(periodoDesde);
		
		var fhasta = fhasta.split("-"); 
		    fechaHasta = fhasta[2]+"-"+fhasta[1]+"-"+fhasta[0];
			periodoHasta = fhasta[2]+"-"+fhasta[1];
		
	  //filtro2+=" and apor.FechaOrdenPago>=*"+fd+"*"+"and apor.FechaOrdenPago <=*"+fh+"*";
	}else{
	  fechaDesde = "0000-00-00"; 
	  fechaHasta = "9999-99-99";		
	  //filtro2+=" and apor.FechaOrdenPago>=*"+fd+"*"+"and apor.FechaOrdenPago <=*"+fh+"*";
	}*/
	var pagina="rp_proyeccionGastoPDF.php?mesReferencia="+mesReferencia+"&anio="+anio+"&codPresupuesto="+codPresupuesto;
	        form.target = "rp_proyeccionGastoPDF";
			cargarPagina(form, pagina);
}
//// ------------------------------------------------------------------------

</script>


</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Reporte | Proyecci&oacute;n de Gasto </td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?php
//// ----------------------------------------------------------------------------------------
if(!$_POST) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
if(!$_POST) $fejercicio = date("Y");

$MAXLIMIT=30;
$filtro = "";
//echo $fejercicio;
 
if ($forganismo != "") { $filtro .= " AND (Organismo = '".$forganismo."')"; $corganismo = "checked"; } else $dorganismo = "disabled";
if ($fejercicio != "") { $filtro .= " AND (EjercicioPpto = '".$fejercicio."')"; $cejercicio = "checked"; } else $dejercicio = "disabled";
if ($fnpresupuesto != "") { $filtro .= " AND (CodPresupuesto = '".$fnpresupuesto."')"; $cnpresupuesto = "checked"; } else $dnpresupuesto = "disabled";
//if ($fP_desde != "" || $fP_hasta != "")	$cPeriodo = "checked"; else {$fP_desde=$fejercicio.'-'.date('m');$fP_hasta=$fejercicio.'-'.date('m');$cPeriodo = "checked";}
//if (){}
//	-------------------------------------------------------------------------------
$MAXLIMIT=30;

$mesReferencia	='';
$mesInicio		= '1';
$mesActual		= date('n');

$mesesNumeros 	= array();
$mesesSelect 	= array();
$mesesletras  	= array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Nobiembre','Diciembre');

for($i=0; $i<$mesActual; $i++)
{
	$mesesNumeros[$i]=$i+1;					
	$mesesSelect[$i]=$mesesletras[$i];
}





//// ------------------------------------------------------------------------------
echo "
<form name='frmentrada' action='rp_proyeccionGasto.php?limit=0' method='POST'>";
echo" <input type='hidden' name='limit' id='limit' value='".$limit."'/>
      <input type='hidden' name='registros' id='regis
	  tros' value='".$registros."'/>

<div class='divBorder' style='width:900px;'>
<table width='900' class='tblFiltro'>
<tr>
 <td width='90' align='right'>Organismo:</td>
 <td width='150'>
  <input type='checkbox' name='chkorganismo' id='chkorganismo' value='1' $corganismo onclick='this.checked=true' />
  <select name='forganismo' id='forganismo' class='selectBig' $dorganismo onchange='getFOptions_2(this.id, \"fnanteproyecto\", \"chknanteproyecto\");'>";
		getOrganismos($forganismo, 3, $_SESSION[ORGANISMO_ACTUAL]);
		echo "
   </select>
 </td>
 <td width='80' align='right'>P. Ejecuci&oacute;n:</td>
 <td>
  <input type='checkbox' name='chkejercicio' id='chkejercicio' value='1' $cejercicio onclick='enabledPEjercicio(this.form);' />
  <input type='text' name='fejercicio' id='fejercicio' size='6' maxlength='4' $dejercicio value='$fejercicio' />
  </td>
</tr>
<tr>
  <td width='90' align='right'>Nro. Presupuesto:</td>
  <td width='100'>
	<input type='checkbox' name='chknpresupuesto' id='chknpresupuesto' value='1' $cnpresupuesto onclick='enabledPNpresupuesto(this.form);' />
	<input type='text' name='fnpresupuesto' id='fnpresupuesto' size='6' maxlength='4' $dnpresupuesto value='$fnpresupuesto' />
  </td>
  <td width='20' align='right'>Ultimo mes:</td>
  <td width='150' align='left'>
    	<select id='sel_ultimomes' name='sel_ultimomes'>
		";
		for($i=0; $i<count($mesesNumeros);$i++)
		{	
			$cnultimomes='';
			if($sel_ultimomes==$i+1){$cnultimomes="selected='selected'";}
			echo "
				<option value='".$mesesNumeros[$i]."' $cnultimomes>$mesesSelect[$i]</option> 
			";
		}
		
		echo "</select>
  </td>
</tr>

<tr><td height='2'></td></tr>
</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar' onclick='filtroReporteProyeccion(this.form);'></center>
<br />
	<div class='divDivision'></div>
<br />";
//	-------------------------------------------------------------------------------
//// ------------------------------------------------------------------------------
//// ------------------------------------------------------------------------------
?>
<div style="width:1000px" class="divFormCaption"> </div>
<center>
<iframe name="rp_proyeccionGastoPDF" id="rp_proyeccionGastoPDF" style="border:solid 1px #CDCDCD; width:900px; height:400px; visibility:false; display:false;" ></iframe>
</center>
<form/>

<script> 
</script>
</body>
</html>
