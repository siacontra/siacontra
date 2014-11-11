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
		<td class="titulo">Listar Beneficio</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?php
//include "gmsector.php";
echo"<input type='hidden' name='regresar' id='regresar' value='anteproyecto_listar'/>";
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
if((!$_POST)or($volver=='0')) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];

$MAXLIMIT=30; 
$filtro = "";
if ($forganismo != "") { $filtro .= " AND a.CodOrganismo = '".$forganismo."'"; $corganismo = "checked"; } else $dorganismo = "disabled";
if ($fejercicio != "") { $filtro .= " AND EjercicioPpto = '".$fejercicio."'"; $cejercicio = "checked"; } else $dejercicio = "disabled";
if ($fnanteproyecto != "") { $filtro .= " AND (CodAnteproyecto = '".$fnanteproyecto."')"; $cnpoyecto = "checked"; } else $dnproyecto = "disabled";
if ($fstatus != "") { $filtro .= " AND a.estadoBeneficio = '".$fstatus."'"; $cstatus = "checked"; } else $dstatus = "disabled";
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
<form name='frmentrada' action='creditoAdicionalListar.php?limite=0' method='POST'>";
echo" <input type='hidden' name='limit' id='limit' value='".$limit."'/>
      <input type='hidden' name='registros' id='registros' value='0'/>

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
  <td width='125' align='right'>Nro. Beneficio:</td>
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
			//getPreparadoPor($fpreparado, 0);
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
			<input type='checkbox' name='chkstatus' value='1' $cstatus  onclick='enabledStatus(this.form)' />
			<select name='fstatus' id='fstatus' class='selectMed'  $dstatus>
			    <option value=''></option>";
				//getStatusAnteproyecto("$fstatus", 0);
				echo "
			</select>
		</td>
	</tr>
</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar'></center>
<br /><div class='divDivision'>Listado de Beneficio</div><br />
<form/>";
/// -------------------------------------------------------------------------------------------------------
$ano=date("Y");

   $sql="SELECT NomCompleto, CodPersona, Ndocumento
		FROM mastpersonas
		WHERE CodPersona
			IN (
				SELECT DISTINCT CodPersona
					FROM rh_beneficio
			   )		
		ORDER BY CodPersona";

	/*$sql ="SELECT a.codBeneficio, a.nroBeneficio, a.tipoSolicitud, a.codPersona, a.codAyudaE, a.estadoBeneficio, a.montoTotal, b.NomCompleto, c.decripcionAyudaE FROM rh_beneficio as a JOIN mastpersonas as b ON b.CodPersona=a.codPersona JOIN rh_ayudamedicaespecifica as c ON c.codAyudaE=a.codAyudaE";*/


 /*$sql="SELECT * FROM `pv_credito_adicional` WHERE `CodOrganismo`= '".$_SESSION['ORGANISMO_ACTUAL']."'  $filtro
		    ORDER BY `co_credito_adicional`";*/
			 //echo $sql;
$qry=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($qry);
//echo $rows;
$registros=$rows; //echo"Registros=".$registros;



?>
<table width="900" class="tblBotones">
<tr>
<td><div id="rows"></div></td>
<td align="center">


</td>
<td align="right">


<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF"  onclick="cargarOpcion(this.form, 'hcm_consumo_partida_reporte_pdf.php', 'BLANK', 'height=800, width=800, left=200, top=200, resizable=yes');" />  

</td>
</tr>
</table>
<input type="hidden" name="registro" id="registro" />
<table width="900" class="tblLista">
  <tr class="trListaHead">
	<th width="20" scope="col">N°</th>
	<th width="110" scope="col">C&eacute;dula</th>        
	<th width="642" scope="col" aling='left'>Funcionario</th>				
  </tr>
  <tr>
	<td colspan='3'>
	
<?
if($registros!=0){
echo "<div id='tablaScroll' style='height:320px; overflow:scroll; overflow-x:hidden'>  
   <table width='880px' >";
	$j=1;
  	for($i=0; $i<$registros; $i++){
	  $field=mysql_fetch_array($qry);
	  // $field['tx_estatus'];
	   if($field['estadoBeneficio']=='PE'){$est='Preparado';}
	   if($field['estadoBeneficio']=='RV'){$est='Revisado';}
	   if($field['estadoBeneficio']=='AP'){$est='Aprobado';}
	   if($field['estadoBeneficio']=='GE'){$est='Generado';}
	   if($field['estadoBeneficio']=='AN'){$est='Anulado';}
	  
	   $montoP=$field['mm_monto_total'];
	   $nFilas = count($field);
	   $montoP=number_format($montoP,2,',','.');
	   $fila = $j;
	   
	   if ($field['tipoSolicitud']=='R')$solicitud='REEMBOLSO';
	   if($field['tipoSolicitud']=='E')$solicitud='EMISIÓN';  	
      echo "
      <tr class='trListaBody' onclick='mClk(this,\"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['CodPersona']."'>
	  <td align='center' width='20'>".$fila."</td>
	  <td align='center' width='110'>".$field['Ndocumento']."</td>	  	  
	  <td align='left' width='642'>".$field['NomCompleto']."</td>	 	  		  
      </tr>";$j++;
  }}
echo "</table> </div> ";
$rows=(int)$rows;

//	-------------------------------------------------------------------------------
?>
	</td>
</tr>
</table>
</body>
</html>
