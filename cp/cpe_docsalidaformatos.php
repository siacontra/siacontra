<?php

	session_start();


	
	
	if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
	//	------------------------------------
	include("fphp.php");
	
	connect();
	//	------------------------------------

	list($cod_tipodocumento, $cod_documentocompleto)=SPLIT( '[|]', $_GET['registro']);
   
	   $sa = "select 
	               cps.FechaDocumento,
				   cps.Contenido,
				   cpd.Cod_Organismos,
				   cpd.Cod_Dependencia,
				   cpd.Representante,
				   cps.Cod_DocumentoCompleto,
				   cpd.Cargo,
				   cps.MediaFirma,
				   cpd.FlagEsParticular,
				   cpd.Atencion
			  from 
			       cp_documentoextsalida cps
			    inner join cp_documentodistribucionext cpd on (cps.Cod_Documento = cpd.Cod_Documento) and (cps.Cod_TipoDocumento=cpd.Cod_TipoDocumento) and cps.Periodo=cpd.Periodo
	          where 
			       cps.Cod_DocumentoCompleto = '".$cod_documentocompleto."' and cps.Cod_TipoDocumento='".$cod_tipodocumento."'";

//echo $sa;
	 $qa = mysql_query($sa) or die ($sa.mysql_error());
	 $ra = mysql_num_rows($qa); 
	 //$fa = mysql_fetch_array($qa);
	 
while ($fa = mysql_fetch_array($qa)) {
	//// CONSULTA PARA OBTNER DATOS A MOSTRAR

	if($fa['FlagEsParticular']=='N'){
	
	 if($fa['Cod_Dependencia']==''){
	  $sb = "select 
				   Organismo, Cargo, Direccion
			  from 
				   pf_organismosexternos
			 where 
				   CodOrganismo = '".$fa['Cod_Organismos']."'";
				   
	 } else{
	 
	   $sb = "select 
				   pfo.Organismo,
				   pfd.Cargo,
				   pfd.Dependencia,
				   pfd.Direccion
			  from 
				   pf_organismosexternos pfo,
				   pf_dependenciasexternas pfd
			 where 
				   pfo.CodOrganismo = '".$fa['Cod_Organismos']."' and 
				   pfd.CodDependencia = '".$fa['Cod_Dependencia']."'";
	 }
	 
	  
	  
	} else {
	  
	   $sb = "select  *  from 
				   cp_particular
			 where 
				   CodParticular = '".$fa['Cod_Organismos']."'";
	}

	$qb = mysql_query($sb) or die ($sb.mysql_error());
  
	$fb = mysql_fetch_array($qb);
	//// ------------------------------------------------------------
	list($a, $m, $d)=SPLIT( '[-]', $fa['FechaDocumento']); $f_documento= $d.'-'.$m.'-'.$a;
	
	switch ($m) 
	{
		case '01': $mes = enero; break;  case '07': $mes = julio; break;
		case '02': $mes = febrero;break; case '08': $mes = agosto; break;
		case '03': $mes = marzo;break;   case '09': $mes = septiembre; break;
		case '04': $mes = abril;break;   case '10': $mes = octubre; break;
		case '05': $mes = mayo;break;    case '11': $mes = noviembre; break;
		case '06': $mes = junio;break;   case '12': $mes = diciembre; break;
	}
	//// ------------------------------------------------------------
	//// consulta para verficar tipo de correspondencia
	$stcuenta = "select * from cp_tipocorrespondencia where Cod_TipoDocumento = '$cod_tipodocumento'";
	$qtcuenta = mysql_query($stcuenta) or die ($stcuenta.mysql_error());
	$rtcuenta = mysql_num_rows($qtcuenta); //echo $stcuenta;
	$ftcuenta = mysql_fetch_array($qtcuenta); 
	//// -------------------------------------------------------------
 
	 if($ftcuenta['DescripCorta']=='OF')
	 {
	 	$tipoOficio = 'Oficio';
		
	 } else if($ftcuenta['DescripCorta']=='OC')
	 {
	 	$tipoOficio = 'Circular';
		
	 }
 

	$direccion=($fb['Direccion']);
	$vector3 = array('á','é','í','ó','ú','º','ñ');

	
	for ($i=0;$i<6;$i++)
	{

		$pos = stripos($direccion,$vector3[$i]);
		if($pos != false)
			break;
	}
	


	if($pos === false)
	{
		$direccion = utf8_encode($direccion);
	}
	
	
	
for ($i=0;$i<6;$i++)
	{

		$pos = stripos($fb['Organismo'],$vector3[$i]);
		if($pos != false)
			break;
	}



	if($pos === false)
	{
		$org = utf8_encode($fb['Organismo']);
	}


	//$direccion = str_replace(";", '<br/>', $direccion);

	$cargo=($fa['Cargo']);
	$cargo = str_replace("_", '<br/>', $cargo);
	
if($fa['Atencion']=='S')	
	{
		
		$nom_at=$fa['Representante'];
		$cargo_at=$cargo;
		
		//$atencion='			';	
		}
else
	{
		$nom_rec=$fa['Representante'];
		$cargo_rec=$cargo;
		$org_rec=$org;
		$direccion_rec=$direccion;
		//$receptor=;//
		}
		
}
	
	
//echo $fa['Cargo'];exit;

     $content = '<page backtop="23mm" backbottom="7mm" backleft="-4mm" backright="0mm"  pageset = "old">
    <page_header><!--CABECERA DEL PDF-->
	<style type="text/css">
		.cabeceraDoc{ font-family:Arial;font-size:14;}
	</style>
       <table width="637" align="center" id="cabecera" cellpadding="0" cellspacing="0" >
		  	<tr>
			   <td width="5" colspan="2"><img src="../imagenes/logos/logo oficio.jpg" style="height:105px; width:120px" /></td>
			   
			   <td width="10"></td>
			   <td width="378">
			   		<table cellpadding="0" cellspacing="2">
			   			<tr>
			      			<td align="center" width="387"><i><strong class="cabeceraDoc"></strong></i></td>
			   			</tr>
					   <tr>
					      <td align="center"><i><strong class="cabeceraDoc"></strong></i></td>
					   </tr>
					   <tr>
					      <td align="center"><i><strong class="cabeceraDoc"></strong></i></td>
					   </tr>
			   		</table>
		   		</td>
		   		<td width="123" align="right"><img src="imagenes/logoContraloria.jpg" style="height:90px; width:90px" /></td>
		  	</tr>
		  	<tr>
			    <td width="10"></td>
    <td ><font face="Times" style=" font-size:11; line-height: 1.2em" >CEM-'.$fa['Cod_DocumentoCompleto'].'</font></td>
    <td></td>
			    <td></td>
			    <td></td>
		  	</tr>
		</table>
     </page_header>
     <page_footer>
     <table><tr>
	 	<td align="center" style=" font-size:9px; line-height: 0.5em;" colspan="2" >Hacia la transparencia, fortalecimiento y consolidación del Sistema Nacional de Control Fiscal</td>
						</tr><tr><td width="90"></td>
						<td align="center" style=" font-size:8px; line-height: 0.5em;">______________________________________________________________________________________________________________________________________________________     </td>
						</tr><tr>
						<td align="center" style=" font-size:8px; line-height: 0.8em;" colspan="2">Dirección: Calle Sucre c/c Monagas, Edificio Sede de la Contraloría del estado Monagas, Maturín. Telefono: 0291-6410441 - 6432713</td>
						</tr><tr>
						<td  align="center" style=" font-size:8px; line-height: 0.8em;" colspan="2">Correo Electrónico: contraloriamonagas@contraloriamonagas.gob.ve, www.contraloriamonagas.gob.ve</td>
						</tr>
						<tr>
						<td  align="center" style=" font-size:8px; line-height: 0.8em;" colspan="2">RIF: G20001397-4</td></tr>
    </table>
     </page_footer>
	<table id="principal" align="center" border="0px">
    	<tr>
		    <td>
			   
			   <table width="675" id="numero_doc" > 
				   <tr>
					    <td width="3"></td>
					    <td width="3"></td>
					    <td width="4"></td>
					    <td width=""></td>
					    <td  width="250"  align="right" class="cabeceraDoc"><br />Matur&iacute;n, '.$d.' de '.$mes.' de '.$a.'</td>
				   </tr>
				   <tr>
					    <td></td>
					    <td></td>
					    <td></td>
					    <td width="375"></td>
					    <td align="right"></td>
				   </tr>
			   </table>
			</td>
		</tr>
		
		<tr><td>
			  
			  <table id="cuerpo1" cellpadding="0" cellspacing="0"  width="280">
			    <tr>
			      <td width="20"></td>
			      <td ></td>
			      <td width="50"></td>
			    </tr>
			    <tr>
			      <td ></td>
			      <td ></td>
			      <td ></td>
			    </tr>
			    <tr>
			      <td></td>
			      <td></td>
			      <td ></td>
			    </tr>
			     <tr>
			      	<td></td>
			      	<td height="15"></td>
				<td ></td>
			    </tr>
			    <tr>
			      	<td></td>
			      	<td class="cabeceraDoc">Ciudadano (a):</td>
				<td ></td>
			    </tr>
			    <tr>
			      	<td></td>
			      	<td width="20" ><b class="cabeceraDoc" ><font face="Times" style=" font-size:12; line-height: 1em">'.$nom_rec.'</font></b></td>
				 <td ></td>
			    </tr>
			    <tr>
			      <td></td>
			      <td width="20" style="width:380px;text-align : justify;" class="cabeceraDoc" ><font face="Times" style=" font-size:12; line-height: 1em">'.$cargo_rec.'</font></td>
				 <td ></td>
			    </tr>
			    <tr>
			      <td></td>
			      <td width="20" style="width:380px;text-align : justify;" class="cabeceraDoc" ><font face="Times" style=" font-size:12; line-height: 1em">'.$org_rec.'</font></td>
				 <td ></td>
			    </tr>
			    <tr>
			      <td></td>
			      <td width="20"><div style="width:380px;text-align : justify;" class="cabeceraDoc"><font face="Times" style=" font-size:12; line-height: 1em">'.wordwrap ( $direccion_rec, 80, '<br />').'</font></div></td>
				 <td ></td>
			    </tr>
			    <tr>
			      <td></td>
			      <td width="20"></td>
				 <td ></td>
			    </tr>
			    <tr>
			      <td></td>
			      <td width="20"></td>
				 <td ><b class="cabeceraDoc" ><font face="Times" style=" font-size:12; line-height: 1em">Atención: '.$nom_at.'</font></b></td>
			    </tr>
			    <tr>
			      <td></td>
			      <td width="20"></td>
				 <td width="20" style="width:380px;text-align : justify;" class="cabeceraDoc" ><font face="Times" style=" font-size:12; line-height: 1em">Cargo: '.$cargo_at.'</font></td>
			    </tr>
			  </table>
			</td>
		</tr>
		
		
	 </table>';

	$content .= '<table><tr><td>&nbsp;</td></tr><tr><td align="justify"><div style="margin-right:20;margin-left:88;width:637px;height:auto;line-height: 100%;">'.$fa['Contenido'].'</div></td></tr></table><br />';

	$content .= '<table align="center"><tr><td>
			<table align="center" id="atentamente" cellpadding="0" cellspacing="0">
			  <tr>
			    <td align="center" class="cabeceraDoc"><br />Atentamente,</td>
			  </tr>
			  <tr>
			    <td height="25"></td>
			  </tr>
			  <tr>
			    <td align="center"></td>
			  </tr>';

				

					$content .='<tr>
					    <td align="center"><strong class="cabeceraDoc">FREDDY JOSÉ CUDJOE</strong></td>
					  </tr>
					  <tr>
					    <td align="center"><strong class="cabeceraDoc">CONTRALOR PROVISIONAL DEL ESTADO MONAGAS</strong></td>
					  </tr>
					  <tr>
					    <td align="center" class="cabeceraDoc" style="font-size:10px;">Resolución N° 01-00-000159, de la Contraloría General de la República del 18-09-2013</td>
					  </tr>
					  <tr>
					    <td align="center" class="cabeceraDoc" style="font-size:10px;">Gaceta Oficial de la República Bolivariana de Venezuela, N° 40.254 del 19-09-2013</td>
					  </tr>
					  <tr>
					    <td align="center" class="cabeceraDoc" style="font-size:10px;"></td>
					  </tr>';
				  
				 
				$content .=' </table>
				</td></tr>
				
				<tr><td>
				 
				  <table id="pie_pagina" border="0" width="600px">
					  <tr>
					     <td width="15"></td>
					     <td width="75"><font size="1">'.$fa['MediaFirma'].'</font></td>
					     <td width="400"></td>
					     <td width="120" align="center"></td>
					  </tr>
					  
				  </table>
			   </td></tr> 
			</table>
			<br>
		</page>';

	$vector1 = array("&aacute;", "&eacute;", "&iacute;", "&oacute;", "&uacute;");
	$vector2 = array("&Aacute;", "&Eacute;", "&Iacute;", "&Oacute;", "&Uacute;");

	$vector3 = array('á','é','í','ó','ú');
	$vector4 = array('Á','É','Í','Ó','Ú');

	// $content = str_replace('<font face="Nimbus Roman No9 L, Times New Roman, serif">',' ',$content);
	$content = str_replace('times new roman','',$content);
	$content = str_replace('font-family: "Times New Roman"','',$content);
	$content = str_replace('"Liberation Serif"','',$content);
	$content = str_replace('"Times New Roman"','',$content);
	$content = str_replace('font-family:','',$content);


	require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
    $html2pdf = new HTML2PDF('P','LETTER','es',true,'UTF-8');
    $html2pdf->WriteHTML($content);
    $html2pdf->Output('exemple.pdf');
?>
