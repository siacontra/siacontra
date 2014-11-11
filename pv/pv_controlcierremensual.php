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
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Control de Cierres Mensuales</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?php
echo"<input type='hidden' name='regresar' id='regresar' value='anteproyecto_listar'/>";
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
if((!$_POST)or($volver=='0')) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];

$MAXLIMIT=30; 
$filtro = "";
if ($forganismo != "") { $filtro .= " AND (Organismo = '".$forganismo."')"; $corganismo = "checked"; } else $dorganismo = "disabled";
if ($fLibro !=""){}
if ($fejercicio != "") { $filtro .= " AND (EjercicioPpto = '".$fejercicio."')"; $cejercicio = "checked"; } else $dejercicio = "disabled";


if ($fnanteproyecto != "") { $filtro .= " AND (CodAnteproyecto = '".$fnanteproyecto."')"; $cnpoyecto = "checked"; } else $dnproyecto = "disabled";
if ($fstatus != "") { $filtro .= " AND (Estado = '".$fstatus."')"; $cstatus = "checked"; } else $dstatus = "disabled";
if ($fpreparado != "") { $filtro .= " AND (PreparadoPor = '".$fpreparado."')"; $cpreparado = "checked"; } else $dpreparado = "disabled";


//if ($fnajuste != "") { $filtro .= " AND (aj.CodAjuste = '".$fnajuste."')"; $cnajuste = "checked"; } else $dnajuste = "disabled";
/*if ($fdesde != "" || $fhasta != "") {
	if ($fdesde != "") $filtro .= " AND (FechaAjuste >= '".$fdesde."')";
	if ($fhasta != "") $filtro .= " AND (FechaAjuste <= '".$fhasta."')"; 
	$cajuste = "checked"; 
} else $dajuste = "disabled";*/
if ($ftajuste != "") { $filtro .= " AND (TipoAjuste = '".$ftajuste."')"; $ctajuste = "checked"; } else $dtajuste = "disabled";
//	-------------------------------------------------------------------------------
$MAXLIMIT=30;
//---------------------------------------------------------------------------------
echo "
<form name='frmentrada' action='anteproyecto_listar.php?limite=0' method='POST'>";
echo" <input type='hidden' name='limit' id='limit' value='".$limit."'/>
      <input type='hidden' name='registros' id='registros' value='".$registros."'/>

<div class='divBorder' style='width:1000px;'>
<table width='1000' class='tblFiltro'>
 
 <tr>
  <td width='80' align='right'>Organismo:</td>
  <td width='330'>
	<input type='checkbox' name='chkorganismo' id='chkorganismo' value='1'$corganismo onclick='this.checked=true' />
	<select name='forganismo' id='forganismo' class='selectBig' $dorganismo onchange='getFOptions_2(this.id, \"fnanteproyecto\", \"chknanteproyecto\");'>";
		getOrganismos($obj[2], 3, $_SESSION[ORGANISMO_ACTUAL]);
		echo "
	</select>
  </td>
   <td align='right' width='30'>Libro:</td>
    <td width='200'><input type='checkbox' id='chkLibro'  name='chkLibro' value='1' $cLibro onclick='enabledLibro(this.form);'/>
	    <select id='fLibro' name='fLibro' class='selectMed' $dLibro>
		<option value=''></option>";
		  getLibro($fLibro, 0);
		echo"</select></td>
   <td width='50' align='right'>Periodo Filtro:</td>
  <td width=''><input type='checkbox' name='chkejercicio' value='1' $cejercicio onclick='enabledEjercicio(this.form);' />
	  <input type='text' name='fejercicio' id='fejercicio'size='7' maxlength='7' $dejercicio value='$fejercicio'/>
  </td>
 </tr>
 
 <tr><td width='90' align='right'>Tipo Registros:</td>
  <td><select name='fTipoRegistro' id='fTipoRegistro' class='selectBig'>
		  <option value=''>Periodos Abiertos</option>
		</select>
  </td>
 <td width='40' align='right' rowspan='2'>Estado:</td>
<td><input type='checkbox' name='chkstatus' value='1' $cstatus  onclick='enabledStatus(this.form);' />
	<select name='fstatus' id='fstatus' class='selectMed' $dstatus>
		<option value=''></option>";
		getStatusAnteproyecto("$fstatus", 0);
		echo "
	</select>
</td>
<td width='100' align='right'>Periodo a Actualizar:</td>
  <td><input type='checkbox' name='chkejercicio' value='1' $cejercicio onclick='enabledEjercicio(this.form);' />
	  <input type='text' name='fejercicio' id='fejercicio'size='7' maxlength='7' $dejercicio value='$fejercicio'/>
  </td>
</tr>
</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar'></center>
<br /><div class='divDivision'>Listado de Anteproyectos</div><br />
<form/>";
/// -------------------------------------------------------------------------------------------------------
$ano=date("Y");
 /*$sql="SELECT * FROM pv_antepresupuesto 
		       WHERE Organismo='".$_SESSION['ORGANISMO_ACTUAL']."'  $filtro
		    ORDER BY CodAnteproyecto";
$qry=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($qry);*/
?>
<table width="1000" class="tblBotones">
<tr>
<td><div id="rows"></div></td>
<td align="center">
 <input type="checkbox" name="seleccion" id="seleccion" /> Seleccionar Todo
</td>
<td align="right">
<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Agregar" onclick="cargarPagina(this.form, 'anteproyecto_datosgenerales.php');" />
<input name="btEditar" type="button" class="btLista" id="btEditar" value="Modificar" onclick="cargarOpcion(this.form, 'anteproyecto_listareditar.php?accion=EDITAR', 'SELF');"/>
<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Abrir" onclick="eliminarAnteproyecto(this.form, 'anteproyecto_listar.php?accion=ELIMINARPROG', '1', 'APLICACIONES');" />
<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Cerrar" onclick="eliminarAnteproyecto(this.form, 'anteproyecto_listar.php?accion=ELIMINARPROG', '1', 'APLICACIONES');" />
<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Agregar 1era Vez" style="size:50px; width:100px" onclick="eliminarAnteproyecto(this.form, 'anteproyecto_listar.php?accion=ELIMINARPROG', '1', 'APLICACIONES');" />
<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Buscar" onclick="eliminarAnteproyecto(this.form, 'anteproyecto_listar.php?accion=ELIMINARPROG', '1', 'APLICACIONES');" />
</td>
</tr>
</table>
<input type="hidden" name="registro" id="registro" />
<table width="1000" class="tblLista">
  <tr class="trListaHead">
	<th width="100" scope="col">Topo Reg.</th>
	<th scope="col"></th>
	<th width="135" scope="col">Organismo</th>
	<th width="100" scope="col">Periodo</th>	
	<th width="100" scope="col">Libro Contable</th>
	<th width="85" scope="col">Estado</th>
	<th width="75" scope="col">Ult. Usuario</th>
    <th width="75" scope="col">Ult. Modif.</th>
  </tr>
<?
if($registros!=0){ 
  	for($i=0; $i<$registros; $i++){
  	   $field=mysql_fetch_array($qry);
	  if($field['Estado']==PE){$est=Preparado;}
	   if($field['Estado']==RV){$est=Revisado;}
	   if($field['Estado']==AP){$est=Aprobado;}
	   if($field['Estado']==GE){$est=Generado;}
	   if($field['Estado']==AN){$est=Anulado;}
	   $montoP=$field['MontoPresupuestado'];
	   $montoP=number_format($montoP,2,',','.');
      echo "
      <tr class='trListaBody' onclick='mClk(this,\"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['CodAnteproyecto']."'>
	  <td align='center'>".$field['CodAnteproyecto']."</td>
	  <td align='center'>".$field['PreparadoPor']."</td>";
	  list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaInicio']); $fInicio=$d.'-'.$m.'-'.$a;
      list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaFin']); $fFin=$d.'-'.$m.'-'.$a;
      echo"
	  <td align='center'>".$field['EjercicioPpto']."</td>
	  <td align='center'>$fInicio</td>
	  <td align='center'>$fFin</td>
	  <td align='center'>$montoP</td>
	  <td align='center'>$est</td>
      </tr>";
  }}
$rows=(int)$rows;
echo "
<script type='text/javascript' language='javascript'>
	totalAnteproyectos($registros,\"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
	totalLotes($registros, $rows, ".$limit.");
</script>";	
//	-------------------------------------------------------------------------------
?>
</table>
</body>
</html>