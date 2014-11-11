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
		<td class="titulo">Lista Activos Fijos</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?php
include "gmactivos.php";
echo"<input type='hidden' name='regresar' id='regresar' value='anteproyecto_listar'/>";
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
if((!$_POST)or($volver=='0')) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];

$MAXLIMIT=30; 
$filtro = "";
if ($forganismo != "") { $filtro .= " AND (Organismo = '".$forganismo."')"; $corganismo = "checked"; } else $dorganismo = "disabled";
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
<form name='frmentrada' action='anteproyecto_listar.php?limite=0' method='POST'>
<input type='hidden' name='limit' id='limit' value='".$limit."'/>
      <input type='hidden' name='registros' id='registros' value='".$registros."'/>

<div class='divBorder' style='width:900px;'>
<table width='900' class='tblFiltro'>

 <tr>
  <td width='125' align='right'>Organismo:</td>
  <td>
	<input type='checkbox' name='chkorganismo' id='chkorganismo' value='1'$corganismo onclick='this.checked=true' />
	<select name='forganismo' id='forganismo' class='selectBig' $dorganismo onchange='getFOptions_2(this.id, \"fnanteproyecto\", \"chknanteproyecto\");'>";
		getOrganismos($obj[2], 3, $_SESSION[ORGANISMO_ACTUAL]);
		echo "
	</select>
  </td>
  <td width='125' align='right'>Buscar Por:</td>
  <td>
	<input type='checkbox' name='chkbuscar' id='chkbuscar' value='1'$cbuscar onclick='enabledBuscarpor(this.form);' />
	<select name='buscar' id='fbuscar' class='selectMed' $dbuscar>
		 <option value=''></option>";
			getBuscarpor($fbuscar, 0);
		    echo "
	</select>
  </td>
 </tr>
 <tr>

 <tr>
  <td width='125' align='right'>Tipo Activo:</td>
  <td>
	<input type='checkbox' name='chktipo_activo' id='chktipo_activo' value='1'$ctipo_activo onclick='enabledTipo_Activo(this.form);' />
	<select name='ftipo_activo' id='ftipo_activo' class='selectMed' $dtipo_activo>
		 <option value=''></option>";
			getTipo_activo($ftipo_activo, 0);
		    echo "
	</select>
  </td>
  <td width='125' align='right'>Nro. Anteproyecto:</td>
  <td>
	<input type='checkbox' name='chknanteproyecto' id='chknanteproyecto' value='1' $cnproyecto onclick='enabledNanteproyecto(this.form);' />
	<input type='text' name='fnanteproyecto' size='6' maxlength='4' $dnproyecto value='$fnanteproyecto' />
  </td>
 </tr>
 <tr>


 <tr>
  <td width='125' align='right'>Situaci&oacute;n:</td>
  <td>
	<input type='checkbox' name='chksituacion' id='chksituacion' value='1' $csituacion onclick='enabledSituacion(this.form);' />
	<select name='fsituacion' id='fsituacion' class='selectMed' $dsituacion>
		 <option value=''></option>";
			getSituacion($fsituacion, 0);
		    echo "
	</select>
  </td>
  <td width='125' align='right'>Nro. Anteproyecto:</td>
  <td>
	<input type='checkbox' name='chknanteproyecto' id='chknanteproyecto' value='1'$cnproyecto onclick='enabledNanteproyecto(this.form);' />
	<input type='text' name='fnanteproyecto' size='6' maxlength='4' $dnproyecto value='$fnanteproyecto' />
  </td>
 </tr>
 <tr>











<td width='125' align='right'>Preparado Por:</td>
  <td><input type='checkbox' name='chkpreparado' value='1' $cpreparado onclick='enabledPreparado(this.form);' />
			<select name='fpreparado' id='fpreparado' class='selectBig' $dpreparado>
		  <option value=''></option>";
			getPreparadoPor($fpreparado, 0);
		    echo "
		</select>
  </td>
  <td width='125' align='right'>Fecha de Ejercicio:</td>
  <td><input type='checkbox' name='chkejercicio' value='1' $cejercicio onclick='enabledEjercicio(this.form);' />
			<input type='text' name='fejercicio' id='fejercicio'size='6' maxlength='4' $dejercicio value='$fejercicio'/>
  </td>
</tr>
<tr>
		<td width='125' align='right'></td>
		<td>
			
		</td>
		<td width='125' align='right' rowspan='2'>Estado:</td>
		<td>
			<input type='checkbox' name='chkstatus' value='1' $cstatus  onclick='enabledStatus(this.form);' />
			<select name='fstatus' id='fstatus' class='selectMed' $dstatus>
			    <option value=''></option>";
				getStatusAnteproyecto("$fstatus", 0);
				echo "
			</select>
		</td>
	</tr>
</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar'></center>
<br /><div class='divDivision'>Listado de Anteproyectos</div><br />
<form/>";
/// -------------------------------------------------------------------------------------------------------
$ano=date("Y");
 $sql="SELECT * FROM pv_antepresupuesto 
		       WHERE Organismo='".$_SESSION['ORGANISMO_ACTUAL']."'  $filtro
		    ORDER BY CodAnteproyecto";
			 //echo $sql;
$qry=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($qry);
$registros=$rows; //echo"Registros=".$registros;
?>
<table width="900" class="tblBotones">
<tr>
<td><div id="rows"></div></td>
<td align="center">

<?
echo"<input type='hidden' name='regresar' id='regresar' value='anteproyecto_listar'/>";
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
<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'anteproyecto_datosgenerales.php');" />
<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'anteproyecto_listareditar.php?accion=EDITAR', 'SELF');"/>
<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarAnteproyecto(this.form, 'anteproyecto_listar.php?accion=ELIMINARPROG', '1', 'APLICACIONES');" />
<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'anteproyecto_ver2.php', 'BLANK', 'height=500, width=950, left=200, top=200, resizable=no');" /> 
<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarOpcion(this.form, 'anteproyecto_pdf.php', 'BLANK', 'height=800, width=800, left=200, top=200, resizable=yes');" /> |  
<input name="btAnular" type="button" class="btLista" id="btAnular" value="Anular" onclick="anularAnteproyecto(this.form);" />
</td>
</tr>
</table>
<input type="hidden" name="registro" id="registro" />
<table width="900" class="tblLista">
  <tr class="trListaHead">
	<th width="100" scope="col"># Anteproyecto</th>
	<th scope="col">Elaborado Por</th>
	<th width="135" scope="col">Ejer. Presupuestario</th>
	<th width="100" scope="col">Fecha Inicio</th>	
	<th width="100" scope="col">Fecha Fin</th>
	<th width="85" scope="col">Monto</th>
	<th width="75" scope="col">Estado</th>
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