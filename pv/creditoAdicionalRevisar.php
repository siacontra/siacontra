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
		<td class="titulo">Revisión Credito Adicional</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?php
include "gmsector.php";
echo"<input type='hidden' name='regresar' id='regresar' value='anteproyecto_listar'/>";
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
if((!$_POST)or($volver=='0')) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];

$MAXLIMIT=30; 
$filtro = "";

if ($forganismo != "") { $filtro .= " AND (a.`CodOrganismo` = '".$forganismo."')"; $corganismo = "checked"; } else $dorganismo = "disabled";
if ($fejercicio != "") { $filtro .= " AND (EjercicioPpto = '".$fejercicio."')"; $cejercicio = "checked"; } else $dejercicio = "disabled";
if ($fnanteproyecto != "") { $filtro .= " AND (CodAnteproyecto = '".$fnanteproyecto."')"; $cnpoyecto = "checked"; } else $dnproyecto = "disabled";
if ($fstatus != "") { $filtro .= " AND (a.`tx_estatus` = '".$fstatus."')"; $cstatus = "checked"; } else $dstatus = "disabled";
if ($fpreparado != "") { $filtro .= " AND (PreparadoPor = '".$fpreparado."')"; $cpreparado = "checked"; } else $dpreparado = "disabled";

//echo $fstatus;

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
<form name='frmentrada' action='creditoAdicionalRevisar.php?limite=0' method='POST'>";
echo" <input type='hidden' name='limit' id='limit' value='".$limit."'/>
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
  <td width='125' align='right'>Nro. Credito A.:</td>
  <td>
	<input type='checkbox' name='chknanteproyecto' id='chknanteproyecto' disabled='disabled' value='1'$cnproyecto onclick='enabledNanteproyecto(this.form);' />
	<input type='text' name='fnanteproyecto' size='6' maxlength='4' $dnproyecto value='$fnanteproyecto' />
  </td>
 </tr>
 <tr>";
    
echo "<td width='125' align='right'>Preparado Por:</td>
  <td><input type='checkbox' name='chkpreparado' value='1' disabled='disabled' $cpreparado onclick='enabledPreparado(this.form);' />
			<select name='fpreparado' id='fpreparado' class='selectBig' $dpreparado>
		  <option value=''></option>";
			getPreparadoPor($fpreparado, 0);
		    echo "
		</select>
  </td>
  <td width='125' align='right'>Fecha de Ejercicio:</td>
  <td><input type='checkbox' name='chkejercicio' value='1' disabled='disabled' $cejercicio onclick='enabledEjercicio(this.form);' />
			<input type='text' name='fejercicio' id='fejercicio'size='6' maxlength='4' $dejercicio value='$fejercicio'/>
  </td>
</tr>
<tr>
		<td width='125' align='right'></td>
		<td>
			
		</td>
		<td width='125' align='right' rowspan='2'>Estado:</td>
		<td>
			<input type='checkbox' name='chkstatus' value='1' checked='checked' $cstatus onclick='this.checked=true'  />
			<select name='fstatus' id='fstatus' class='selectMed' $dstatus>";
				getStatusAnteproyecto("PE", 1);
				echo "
			</select>
		</td>
	</tr>
</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar'></center>
<br /><div class='divDivision'>Listado de Crédito Adicional</div><br />
<form/>";
/// -------------------------------------------------------------------------------------------------------
$ano=date("Y");

$sql="SELECT a.`CodOrganismo` , a.`co_credito_adicional`, a.`nu_oficio` , a.`ff_oficio` , a.`nu_decreto` , a.`ff_decreto` , a.`tx_motivo` , a.`ff_ejecucion` , a.`ff_creacion` , a.`tx_estatus` , a.`tx_preparado` , a.`tx_aprobado` , a.`tx_ultima_modificacion` , a.`ff_ultima_modoficacion` , a.`mm_monto_total`, b.`Usuario` , b.`CodPersona` , c.`Apellido1` , c.`Apellido2` , c.`Busqueda` , c.`NomCompleto`
FROM `pv_credito_adicional` AS a, `usuarios` AS b, `mastpersonas` AS c
WHERE b.`Usuario` = a.`tx_preparado`
AND b.`CodPersona` = c.`CodPersona`
AND a.`tx_estatus` = 'PE'
$filtro
ORDER BY a.`co_credito_adicional`";
 //echo $sql;

 /*$sql="SELECT * FROM `pv_credito_adicional` WHERE `CodOrganismo`= '".$_SESSION['ORGANISMO_ACTUAL']."'  $filtro
		    ORDER BY `co_credito_adicional`";*/
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
echo"<input type='hidden' name='regresar' id='regresar' value='creditoAdicionalRevisar.php'/>";
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
<td align="right"><input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'creditoAdicionalListarVer.php?accion=VER', 'BLANK', 'height=500, width=950, left=200, top=200, resizable=no');" /> 
<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" disabled="disabled" onclick="cargarOpcion(this.form, 'anteproyecto_pdf.php', 'BLANK', 'height=800, width=800, left=200, top=200, resizable=yes');" /> |  
<input name="btAnular" type="button" class="btLista" id="btRevisar" value="Revisar" onclick="cargarOpcion(this.form, 'creditoAdicionalRevisarEditar.php?accion=REVISAR','SELF');" /> <!-- onclick="revisarCreditoAdicional(this.form);"  -->
<input name="btAnular" type="button" class="btLista" id="btAnular" value="Anular" onclick="anularCreditoAdicional(this.form);" />
</td>
</tr>
</table>
<input type="hidden" name="registro" id="registro" />
<table width="900" class="tblLista">
  <tr class="trListaHead">
	<th width="20" scope="col">N°</th>
	<th width="250" scope="col">Elaborado Por</th>
	<th width="342" scope="col">Descripción</th>	
	<th width="100" scope="col">Fecha E.</th>
	<th width="85" scope="col">Monto</th>
	<th width="75" scope="col">Estado</th>
  </tr>
<?
if($registros!=0){ 
  	for($i=0; $i<$registros; $i++){
  	   $field=mysql_fetch_array($qry);
	  // $field['tx_estatus'];
	   if($field['tx_estatus']=='PE'){$est='Preparado';}
	   if($field['tx_estatus']=='RV'){$est='Revisado';}
	   if($field['tx_estatus']=='AP'){$est='Aprobado';}
	   if($field['tx_estatus']=='GE'){$est='Generado';}
	   if($field['tx_estatus']=='AN'){$est='Anulado';}
	  
	   $montoP=$field['mm_monto_total'];
	   $nFilas = count($field);
	   $montoP=number_format($montoP,2,',','.');
	   $fila = $i+1;
      echo "
      <tr class='trListaBody' onclick='mClk(this,\"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['co_credito_adicional']."'>
	  <td align='center'>".$fila."</td>
	  <td align='center'>".htmlentities($field['NomCompleto'])."</td>
	  <td align='justify'>".$field['tx_motivo']."</td>";
	  list($a, $m, $d)=SPLIT( '[/.-]', $field['ff_creacion']); $fCreacion=$d.'-'.$m.'-'.$a;
      
      echo"	  
	  <td align='center'>$fCreacion</td>	 
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
