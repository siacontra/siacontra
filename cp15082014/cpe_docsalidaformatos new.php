<?php

		session_start();
connect(); 
extract ($_POST);
extract ($_GET);
		require_once('html2pdf/html2pdf.class.php');
		$html2pdf = new HTML2PDF('P','A4','es',true,'UTF-8');
		
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
				   cpd.FlagEsParticular
				  
			  from 
			       cp_documentoextsalida cps
				   inner join cp_documentodistribucionext cpd on (cps.Cod_Documento = cpd.Cod_Documento)
	          where 
			       cps.Cod_DocumentoCompleto = '$cod_documentocompleto'";

	 $qa = mysql_query($sa) or die ($sa.mysql_error());
	 $ra = mysql_num_rows($qa); 
	 $fa = mysql_fetch_array($qa); 
 
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
	 	$tipoOficio = 'Oficio Circular';
		
	 }
 
    $content = '<page backtop="23mm" backbottom="7mm" backleft="5mm" backright="15mm"  pageset = "old">
    <page_header><!--CABECERA DEL PDF-->
       <table width="637" align="center" id="cabecera" cellpadding="0" cellspacing="0">
		  	<tr>
			   <td width="20"></td>
			   <td width="102" align="center"><img src="../imagenes/logos/contraloria.jpg" style="height:65px; width:85px" /></td>
			   <td width="10"></td>
			   <td width="378">
			   		<table cellpadding="0" cellspacing="2">
			   			<tr>
			      			<td align="center" width="387"><font size="3"><i><strong>Rep&uacute;blica Bolivariana de Venezuela</strong></i></font></td>
			   			</tr>
					   <tr>
					      <td align="center"><font size="3"><i><strong>Contralor&iacute;a del estado Sucre</strong></i></font></td>
					   </tr>
					   <tr>
					      <td align="center"><font size="3"><i><strong>Despacho del Contralor</strong></i></font></td>
					   </tr>
			   		</table>
		   		</td>
		   		<td width="103"><img src="imagenes/logoContraloria.jpg" style="height:80px; width:80px" /></td>
		  	</tr>
		  	<tr>
			    <td></td>
			    <td></td>
			    <td></td>
			    <td></td>
			    <td></td>
		  	</tr>
		</table>
     </page_header>
     <page_footer>
	 	<p style="font-size:8px;">Dirección Avenida Arismendi, Edificio Palacio Legislativo, Cumaná, estado Sucre, Teléfonos: (0293) 4320708-4323658. Directo: Tele Fax 4323447. Correo Eléctronico: contraloria.estado.sucre@cgesucre.gob.ve</p>
     </page_footer>
	<table id="principal"  align="center">
    	<tr>
		    <td>
			   
			   <table width="675" id="numero_doc"> 
				   <tr>
					    <td width="3"></td>
					    <td width="3"></td>
					    <td width="4"></td>
					    <td width="375"></td>
					    <td width="266" align="right">Cuman&aacute;, '.$d.' de '.$mes.' de '.$a.'</td>
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
			  
			  <table id="cuerpo1" cellpadding="0" cellspacing="0">
			    <tr>
			      <td width="80"></td>
			      <td width="351"><b>'.$tipoOficio.' N°: '.$fa['Cod_DocumentoCompleto'].'</b></td>
			    </tr>
			    <tr>
			      <td width="80"></td>
			      <td width="351"></td>
			      <td width="180"></td>
			    </tr>
			    <tr>
			      <td></td>
			      <td></td>
			    </tr>
			     <tr>
			      <td></td>
			      <td height="15"></td>
			    </tr>
			    <tr>
			      <td></td>
			      <td>Ciudadano(a):</td>
			    </tr>
			    <tr>
			      <td></td>
			      <td><b>'.$fa['Representante'].'</b></td>
			    </tr>
			    <tr>
			      <td></td>
			      <td>'.$fa['Cargo'].'</td>
			    </tr>
			    <tr>
			      <td></td>
			      <td>'.$fb['Direccion'].'</td>
			    </tr>
			  </table>
			</td>
		</tr>
		
		
	 </table>';

	$content .= '<div style="margin-left:50;width:637px;height:auto;"><font size="3" face="">'.$fa['Contenido'].'</font></div><br />';

	$content .= '<table><tr><td>
			<table align="center" id="atentamente" cellpadding="0" cellspacing="0">
			  <tr>
			    <td align="center"><br />Atentamente,</td>
			  </tr>
			  <tr>
			    <td height="25"></td>
			  </tr>
			  <tr>
			    <td align="center"></td>
			  </tr>';

				list($a, $m, $d)=SPLIT( '[-]', $fa['FechaDocumento']); $f_documento= $a.$m.$d; 
	
				if(($f_documento<'20130227') || ( $f_documento>'20130301' && $f_documento <='20130922'))
				{ 

					$content .='<tr>
					    <td align="center"><strong style="font-size:12;">Lcdo. Freddy Cudjoe</strong></td>
					  </tr>
					  <tr>
					    <td align="center"><strong style="font-size:12;">Contralor Provisional del estado Sucre</strong></td>
					  </tr>
					  <tr>
					    <td align="center" style="font-size:8;"><font size="1">Resolución Nº 01-00-000130 de la Contralor&iacute;a General de la República  del 12-06-2012</font></td>
					  </tr>
					  <tr>
					    <td align="center" style="font-size:8;"><font size="1">Gaceta Oficial de la Rep&uacute;blica Bolivariana de Venezuela Nº 39.943 del 13-06-2012</font></td>
					  </tr>';
				  
				 } else if($f_documento>='20130227' && $f_documento<='20130301') {

					$content .='<tr>
					    <td align="center"><strong style="font-size:12;">Abog. Jos&eacute; &Aacute;ngel Fari&ntilde;as Cayamo</strong></td>
					  </tr>
					  <tr>
					    <td align="center"><strong style="font-size:12;">Contralor(P) del estado Sucre (E)</strong></td>
					  </tr>
					  <tr>
					    <td align="center" style="font-size:8;"><font size="1">Designado mediante Gaceta Nº. 1798 de  Fecha 27-02-2013,</font></td>
					  </tr>
					  <tr>
					    <td align="center" style="font-size:8;"><font size="1">Emanada del Despacho del Contralor General del Edo. Sucre,</font></td>
					  </tr>';  

				} else if($f_documento>='20130923'){

					$content .=' <tr>
					    <td align="center"><strong style="font-size:12;">Lcdo. ANDY VÁSQUEZ</strong> </td>
					  </tr>
					  <tr>
					    <td align="center"><strong style="font-size:12;">Contralor Provisional del estado Sucre</strong></td>
					  </tr>
					   <tr>
					    <td align="center" style="font-size:8;"><font size="1" >Resolución Nº 01-00-000158 de la Contralor&iacute;a General de la República  del 18-09-2013</font></td>
					  </tr>
					 
					  <tr>
					    <td align="center" style="font-size:8;"><font size="1">Gaceta Oficial de la Rep&uacute;blica Bolivariana de Venezuela Nº 40.254 del 19-09-2013</font></td>
					  </tr>';
				  
				}

				$content .=' </table>
				</td></tr>
				
				<tr><td>
				 
				  <table id="pie_pagina">
					  <tr>
					     <td width="63"></td>
					     <td width="17"><font size="1">'.$fa['MediaFirma'].'</font></td>
					     <td width="450"></td>
					     <td width="134" align="center"></td>
					  </tr>
					  
				  </table>
			   </td></tr> 
			</table>
			<br>
		</page>';

	// $content = str_replace('<font face="Nimbus Roman No9 L, Times New Roman, serif">',' ',$content);
	$content = str_replace('times new roman','',$content);
	$content = str_replace('font-family:','',$content);

//echo $content; 

	//$html2pdf->SetFont('Helvetica', 'BI', 20, '', 'false');
	$html2pdf->WriteHTML($content);
	$html2pdf->Output('dosumentosalida.pdf');
?>
