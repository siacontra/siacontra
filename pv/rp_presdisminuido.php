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
<script type="text/javascript" language="javascript" src="r_fscript.js"></script>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Reporte | Presupuesto Disminuido</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?php
if(!$_POST) { $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; 
              $fstatus = 'AP'; $cstatus = "checked"; 
			}

$MAXLIMIT=30;
$filtro = "";
if($forganismo != ""){ $filtro .= " AND (pr.Organismo = '".$forganismo."')"; $corganismo = "checked"; } else $dorganismo = "disabled"; /// ORGANISMO
if($fpreparado != ""){ $filtro .= " AND (PreparadoPor = '".$fpreparado."')"; $cpreparado = "checked"; } else $dpreparado = "disabled"; /// PREPARADO POR
if($fPresupuesto != "") { $filtro .= " AND (CodPresupuesto = '".$fPresupuesto."')"; $cPresupuesto = "checked"; } else $dPresupuesto = "disabled"; /// CODIGO pRESUPUESTO
if($fstatus != "") { $filtro .= " AND (Estado = '".$fstatus."')"; $cstatus = "checked"; } else $dstatus = "disabled";
if($fPeriodo != "") { $filtro.= "AND (EjercicioPpto = '".$fPeriodo."')"; $cPeriodo = "checked";} else $dPeriodo = "disabled";
//if ($fnref != "") { $filtro .= " AND (CodRef = '".$fnref."')"; $cnref = "checked"; } else $dnref = "disabled"; /// CODIGO DE REFORMULACION

/*if ($fdesde != "" || $fhasta != "") {
	if ($fdesde != "") $filtro .= " AND (FechaAjuste >= '".$fdesde."')";
	if ($fhasta != "") $filtro .= " AND (FechaAjuste <= '".$fhasta."')"; 
	$cref = "checked"; 
} else $dref = "disabled";*/
if($f_oficio!=""){$filtro .= "AND (NumOficio = '".$f_oficio."')"; $coficio="cheked";}else $doficio="disabled";

if ($ftajuste != "") { $filtro .= " AND (aj.TipoAjuste = '".$ftajuste."')"; $ctajuste = "checked"; } else $dtajuste = "disabled";

//if ($fejercicio != "") { $filtro .= " AND (EjercicioPpto = '".$fejercicio."')"; $cejercicio = "checked"; } else $dejercicio = "disabled";

//	-------------------------------------------------------------------------------
$MAXLIMIT=30;
//	-------------------------------------------------------------------------------
echo "
<form name='frmentrada' id='frmentrada' method='POST' action='rp_presdisminuido.php'>";
echo" <input type='hidden' name='limit' id='limit' value='".$limit."'/>
      <input type='hidden' name='registros' id='registros' value='".$registros."'/>
	  <input type='hidden' name='registro' id='registro' value='".$_POST['fPresupuesto']."'/>

<div class='divBorder' style='width:900px;'>
<table width='900' class='tblFiltro'>
<tr>
 <td width='50' align='right'>Organismo:</td>
 <td width='100'>
  <input type='checkbox' name='chkorganismo' id='chkorganismo' value='1' $corganismo onclick='this.checked=true' />
  <select name='forganismo' id='forganismo' class='selectBig' $dorganismo onchange='getFOptions_2(this.id, \"fnanteproyecto\", \"chknanteproyecto\");'>";
		getOrganismos($forganismo, 3, $_SESSION[ORGANISMO_ACTUAL]);
		echo "
   </select>
 </td>
 <td width='105' align='right'>Nro. Presupuesto:</td>
 <td>
  <input type='checkbox' name='chkPresupuesto' id='chkPresupuesto' value='1' $cPresupuesto onclick='enabledNroPresupuesto(this.form);' />
  <input type='text' name='fPresupuesto' id='fPresupuesto' size='6' maxlength='4' $dPresupuesto value='$fPresupuesto' />
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
  <td><input type='checkbox' name='chkstatus' id='chkstatus' value='1' $cstatus onclick='this.checked=true' />
	  <select name='fstatus' id='fstatus' class='selectMed' $dstatus>
				<option value='AP'>Aprobado</option>";
				//getEstadoAjuste($fstatus, 0);
				echo "
			</select>
  </td>
</tr>



<tr>"; if($_POST[fpreparado]!=''){
          $fpreparado2=$_POST[fpreparado];
        }
    
echo "<td width='90' align='right'></td>
  <td width='150'>
	
  </td>
  <td width='80' align='right'>Per&iacute;odo Ejecuci&oacute;n:</td>
 <td width='78' align='left'>
  <input type='checkbox' name='chkPeriodo' id='chkPeriodo' value='1' $cPeriodo onclick='enabledRpPeriodoEjecucion(this.form);' />
  <input type='text' name='fPeriodo' id='fPeriodo'  maxlength='4' size='6' $dPeriodo value='$fPeriodo' />
 </td>
</tr>

<tr>
  <td width='90' align='right'></td>
  <td>
  </td>
 <td width='80' align='right'></td>
 <td width='78' align='left'></td>
</tr>

</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar' onclick='cargarReportePresupuestoDisminuido(this.form);'></center>
<br /><div class='divDivision'>Resultados</div><br />"; 
//	-------------------------------------------------------------------------------
//// ------------------------------------------------------------------------------
//// ------------------------------------------------------------------------------
?>
<div style="width:1000px" class="divFormCaption"></div>
<center>
<iframe name="rp_PresupuestoDisminuido" id="rp_PresupuestoDisminuido" style="border:solid 1px #CDCDCD; width:1000px; height:500px; visibility:false; display:false;" ></iframe>
</center>
<form/>
</body>
</html>