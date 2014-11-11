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
		<td class="titulo">Aprobar Beneficio</td>
		<td align="right"><a class="cerrar" href="window.close()">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?php
//include "gmsector.php";

///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
if((!$_POST)or($volver=='0')) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; 

//$fpreparado = $_SESSION["CODPERSONA_ACTUAL"];

//echo 'kkk'.$_POST['chkpreparado'];

if($_POST['chkpreparado']){$fpreparado = $_SESSION["CODPERSONA_ACTUAL"];}
else{$fpreparado = "";}

//echo $fpreparado.'ddd ';


//$fpreparado = $_POST['fpreparado'];//$_SESSION["CODPERSONA_ACTUAL"];$fstatus='RV';
//echo $fpreparado;
$MAXLIMIT=30; 
$filtro = "";
$fstatus='RV';
if ($forganismo != "") { $filtro .= " AND (a.CodOrganismo = '".$forganismo."')"; $corganismo = "checked"; } else $dorganismo = "disabled";
//if ($fejercicio != "") { $filtro .= " AND (EjercicioPpto = '".$fejercicio."')"; $cejercicio = "checked"; } else $dejercicio = "disabled";
//if ($fnanteproyecto != "") { $filtro .= " AND (CodAnteproyecto = '".$fnanteproyecto."')"; $cnpoyecto = "checked"; } else $dnproyecto = "disabled";
if ($fstatus != "") { $filtro .= " AND (a.estadoBeneficio = '".$fstatus."')"; $cstatus = "checked"; } else $dstatus = "disabled";
if ($fpreparado != "") { $filtro .= " AND (a.aprobadoPor = '".$fpreparado."')"; $cpreparado = "checked"; $dpreparado = "disabled"; } else {$dpreparado = ""; $filtro .=''; $cpreparado = "";}

//echo $fpreparado.'1 '.$cstatus.' 1'.$cpreparado;

//if ($fnajuste != "") { $filtro .= " AND (aj.CodAjuste = '".$fnajuste."')"; $cnajuste = "checked"; } else $dnajuste = "disabled";
/*if ($fdesde != "" || $fhasta != "") {
	if ($fdesde != "") $filtro .= " AND (FechaAjuste >= '".$fdesde."')";
	if ($fhasta != "") $filtro .= " AND (FechaAjuste <= '".$fhasta."')"; 
	$cajuste = "checked"; 
} else $dajuste = "disabled";*/
//if ($ftajuste != "") { $filtro .= " AND (TipoAjuste = '".$ftajuste."')"; $ctajuste = "checked"; } else $dtajuste = "disabled";
//	-------------------------------------------------------------------------------
$MAXLIMIT=30;
//---------------------------------------------------------------------------------
echo "
<form name='frmentrada' action='beneficioAprobar.php?limite=0' method='POST'>";
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
  <td width='125' align='right'>Nro. Beneficio:</td>
  <td>
	<input type='checkbox' name='chknanteproyecto' id='chknanteproyecto' disabled='disabled' value='1' $cnproyecto onclick='enabledNanteproyecto(this.form);' />
	<input type='text' name='fnanteproyecto' size='6' maxlength='4' $dnproyecto value='$fnanteproyecto' />
  </td>
 </tr>
 <tr>";
    
echo "<td width='125' align='right'>Aprobar Por:</td>
  <td><input type='checkbox' name='chkpreparado' value='1' $cpreparado onclick='enabledAprobar(this.form)' />
			<select name='fpreparado' id='fpreparado' class='selectBig' disable='$dpreparado'>
         		    
			    <option select=selected value='$fpreparado'>".$_SESSION['NOMBRE_USUARIO_ACTUAL']."</option>";
				//getCreditoPreparadoPor($fpreparado, 0);
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
			<select name='fstatus' id='fstatus' class='selectMed' $dstatus>
				<option value='RV'>Revisado</option>";
				
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

$sql="SELECT a.codBeneficio, a.nroBeneficio, a.tipoSolicitud, a.codPersona, a.codAyudaE, 
a.estadoBeneficio, a.montoTotal, b.NomCompleto, c.decripcionAyudaE
FROM rh_beneficio as a
JOIN mastpersonas as b  ON b.CodPersona=a.codPersona
JOIN rh_ayudamedicaespecifica as c  ON c.codAyudaE=a.codAyudaE
where 1 
$filtro
ORDER BY a.nroBeneficio";


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
echo"<input type='hidden' name='regresar' id='regresar' value='creditoAdicionalListar.php'/>";
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
<td align="right"><input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'beneficioVer.php?accion=VER', 'BLANK', 'height=500, width=950, left=200, top=200, resizable=no');" /> 
<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" disabled="disabled" onclick="cargarOpcion(this.form, 'anteproyecto_pdf.php', 'BLANK', 'height=800, width=800, left=200, top=200, resizable=yes');" /> |  
<input name="btAnular" type="button" class="btLista" id="btAnular" value="Aprobar" onclick="cargarOpcion(this.form, 'beneficioAprobarPopup.php', 'SELF')"/></td>
</tr><!-- onclick="aprobarCreditoAdicional(this.form);"     aprobarBeneficio(this.form)  -->
</table>
<input type="hidden" name="registro" id="registro" />
<table width="880" class="tblLista">
  <tr class="trListaHead">
	<th width="20" scope="col">N°</th>
	<th width="110" scope="col">Nro de Orden</th>
    <th width="94" scope="col">Tipo</th>
	<th width="300" scope="col">Funcionario</th>	
	<th width="200" scope="col">Servicio</th>
	<th width="85" scope="col">Monto</th>
	<th width="71" scope="col" >Estado</th>
  </tr>
  <tr>
  	<td colspan="7">
  		<div style='height:320px; overflow:scroll; overflow-x:hidden; border: 0px;'>
  			<table width="880" class="tblLista" border="0px"  >
  				<?
					if($registros!=0){
						//echo ""; 
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
						   $fila = $i+1;
					
							if($field['tipoSolicitud']=='R')$solicitud='REMBOLSO';
							if($field['tipoSolicitud']=='E')$solicitud='EMISIÓN';					
							
					      echo "
					     
					      
					      <tr class='trListaBody' onclick='mClk(this,\"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['codBeneficio']."'>
						   	  <td width='16' align='center'>".$fila."</td>
							  <td width='110' align='center'>".$field['nroBeneficio']."</td>
							  <td width='94' align='center'>".$solicitud."</td>		 
							  <td width='300' align='center'>".$field['NomCompleto']."</td>	 
							  <td width='200' align='center'>".$field['decripcionAyudaE']."</td>
							  <td width='85' align='right'>".number_format($field['montoTotal'],2,',','.')."</td>
							  <td width='55' align='center'>$est</td>
					      </tr>
					      ";
					  }
					}
					$rows=(int)$rows;
					?>
  			</table>	
  		</div>
  	</td>
  </tr>
  


<script type='text/javascript' language='javascript'>
	///////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////***********   REVISAR BENEFICIO  *******/////////////////////////////////
function aprobarBeneficio(form){
	
	/*var filtro=document.getElementById('filtro').value;;
	var limit=document.getElementById('limit').value;*/
	var codigo=document.getElementById('registro').value;
	//alert(codigo);
	if (codigo=="") msjError(1000);
	else {
		//	PREGUNTO SI DESEA ANULAR
		var anular=confirm("¡Esta seguro de Aprobar el Beneficio?");
		if (anular) {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "lib/transBeneficio.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("opcion=APROBARBENEFICIO&codigo="+codigo);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var error=ajax.responseText;
					if (error!=1) alert ("¡"+error+"!");
					else cargarPagina(form,"beneficioAprobar.php");
				}
			}
		}	
	}
}

function enabledAprobar(form) {
	
	if (form.chkpreparado.checked) form.fpreparado.disabled=false; 
	else { form.fpreparado.disabled=true;  form.fpreparado.value="";}
}
	
</script>

</table>
</body>
</html>
