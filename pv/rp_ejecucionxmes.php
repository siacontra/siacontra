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

<link type="text/css" rel="stylesheet" href="../css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />


<link href="css1.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/fscript.js" charset="utf-8"></script>


<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="fscript01.js"></script>
<script type="text/javascript" language="javascript" src="r_fscript.js"></script>


</head>

<script>
//	FUNCIONES PARA FORMATEAR LOS CAMPOS FECHA
function setFechaDMA(campo) {
	
	//alert(campo);
	//	valor del campo
	var texto = new String(campo.value);
	
	//	separo los valores del dia, mes y año
	var nDia = texto.substr(0, 2);
	var nMes = texto.substr(3, 2);
	var nAno = texto.substr(6);
	
	if (!valNumericoEntero(nDia)) nDia = "00";
	if (!valNumericoEntero(nMes)) nMes = "00";
	if (!valNumericoEntero(nAno)) nAno = "0000";
	
	//	ultima letra ingresada
	var letra = texto.substr(-1);
	
	if (texto.length == 3) {
		if (letra != "-") var sep = "-" + letra; else var sep = letra;
		campo.value = nDia + sep;
	}
	
	else if (texto.length == 6) {
		if (letra != "-") var sep = "-" + letra; else var sep = letra;
		campo.value = nDia + "-" + nMes + sep;
	}
	
	else if (texto.length == 10) {
		campo.value = nDia + "-" + nMes + "-" + nAno;
	}
}
//	--------------------------------------

//	VALIDO CARACTERES NUMERICOS EN UN CADENA DE TEXTO
function valNumericoEntero(texto) {
	var checkOK = "-0123456789";
	var checkStr = texto;
	var allValid = true;
	for(var i=0; i < checkStr.length; i++) {
		ch = checkStr.charAt(i);
		for (var j=0; j<checkOK.length; j++)
			if (ch == checkOK.charAt(j)) break;
		if (j == checkOK.length) {
			allValid = false;
			break;
		}
	}
	return allValid;
}
//	--------------------------------------
</script>

<script type="text/javascript">
$(function() {
    $('.date-picker').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
		closeText: 'Aceptar', 
		currentText: 'Periodo Actual', 
		prevText: 'Previo', 
		nextText: 'Próximo',
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
  'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
  monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
  'Jul','Ago','Sep','Oct','Nov','Dic'],
  monthStatus: 'Ver otro mes', yearStatus: 'Ver otro año',
        dateFormat: 'mm-yy',
        onClose: function(dateText, inst) { 
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
        }
    });
});
</script>
<style>
.ui-datepicker-calendar {
    display: none;
    }
</style>


<script>

  </script>
<body>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<table width="100%" cellspacing="0" cellpadding="0">
 <tr>
  <td class="titulo">Reporte | Ejecuci&oacute;n Presupuestaria por Mes</td>
  <td align="right">
   <a class="cerrar" href="../presupuesto/framemain.php">[cerrar]</a>
  </td>
 </tr>
</table>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<hr width="100%" color="#333333" />
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<?php 
$fdesde ='01-01-'.date('Y');
$fhasta =date('d-m-Y');

if(!$_POST) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
if(!$_POST){ $fejercicioppto = date("Y"); $cejercicioppto = "checked";}
if(!$_POST){ $fperiodo = date("Y-m"); $cperiodo = "checked";}

$MAXLIMIT=30;
$filtro = "";
if ($forganismo!="")$corganismo = "checked";else $dorganismo = "disabled";
if ($fejercicioppto!="")$cejercicioppto = "checked"; else $dejercicioppto = "disabled";
if ($fnropresupuesto!="")$cnropresupuesto = "checked"; else $dnropresupuesto = "disabled";
if ($fperiodo!="")$cperiodo = "checked";else $dperiodo = "disabled";
if ($fdesde != "" || $fhasta != "")	$cFechaDocumento = "checked"; else $dFechaDocumento = "disabled";


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



?>
<? echo"
<form name='frmentrada' action='rp_ejecucionxmes.php?limit=0' method='POST'>
<input type='hidden' name='limit' id='limit' value='".$limit."'>
<input type='hidden' name='registros' id='registros' value='".$registros."'/>
<input type='hidden' name='usuarioActual' id='usuarioActual' value='".$_SESSION['USUARIO_ACTUAL']."'/>

<div class='divBorder' style='width:900px;'>
<table width='900' class='tblFiltro'>
<tr>
<td width='125' align='right'>Organismo:</td>
<td>
<input type='checkbox' name='chkorganismo' id='chkorganismo' value='1' $corganismo onclick='this.checked=true' />
<select name='forganismo' id='forganismo' class='selectBig' $dorganismo onchange='getFOptions_2(this.id, \"fanteproyecto\", \"chknanteproyecto\");'>";
	getOrganismos($obj[2], 3, $_SESSION[ORGANISMO_ACTUAL]);
	echo "
</select>
</td>
<td align='right'>Mes-Año:</td>
  <td align='left'>
    <input type='checkbox' name='chkFecha' id='chkFecha' value='1' $cFechaDocumento onclick='enabledFechaRpEjecucionPartida(this.form);' />
   <input  type='hidden'type='text' name='fdesde' id='fdesde' size='15' maxlength='10' $dFechaDocumento value='$fdesde' class='datepicker' onkeyup='setFechaDMA(this);'/>
    <input type='text' name='fhasta' id='fhasta' size='15' maxlength='10' $dFechaDocumento value='' class='date-picker' onkeyup='setFechaDMA(this);'/>
  </td>
<td>
</tr>

<tr>
<td width='125' align='right'>Nro. Presupuesto:</td>
<td>
<input type='checkbox' name='chknropresupuesto' id='chknropresupuesto' value='1' $cnropresupuesto onclick='enabledCierreNroPresupuesto(this.form);'/>
<input name='fnropresupuesto'  id='fnropresupuesto' type='text' size='5' maxlength='4' style='text-align:right' value='' $dnropresupuesto/>";?><input type="button" name="btnropresup" id="btnropresup" value="..." onclick="cargarVentana(this.form, 'listado_presupuestos.php?limit=0&campo=1', 'height=500, width=850, left=200, top=200, resizable=yes');" disabled="false"/>*
<? echo" </td>
<td width='125' align='right'>Ejercicio Ppto.:</td>
<td>
<input type='checkbox' name='chkejercicioPpto' id='chkejercicioPpto' value='1' $cejercicioppto onclick='this.checked=true' />
<input type='text' name='fejercicioppto' id='fejercicioppto' size='6' maxlength='4' $dejercicioppto value='$fejercicioppto' readonly/>
</td>
</tr>

</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar' onclick='filtroReporteEjecucionPresupuestariaXmes(this.form);'></center>
<br /><div style='width:900;' class='divDivision'>Ejecuci&oacute;n</div>
<form/><br />";
?>
<table width="900" class="tblBotones">
<tr>
<td><div id="rows"></div></td>
<td width="250">
		</td>
		<td align="right">
<!--<input type="button" id="btEjecutar" name="btejecutar"  value="Ejecutar Cierre" onclick="ProcesoEjecutarCierre(this.form);"/>-->
		</td>
	</tr>
</table>
<input type="hidden" name="registro" id="registro" />
<div style="width:900px" class="divFormCaption"></div>
<center>
<iframe name="rp_ejecucionxmespdf" id="rp_ejecucionxmespdf" style="border:solid 1px #CDCDCD; width:100%; height:400px; visibility:false; display:false;" ></iframe>
</center>
<form/>
</body>
</html>
