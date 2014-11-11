<?php

/**
 * Tutoriel file
 * Description : Imbricating several segments
 * You need PHP 5.2 at least
 * You need Zip Extension or PclZip library
 *
 * @copyright  GPL License 2008 - Julien Pauli - Cyril PIERRE de GEYER - Anaska (http://www.anaska.com)
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL License
 * @version 1.3
 */


// Make sure you have Zip extension or PclZip library loaded
// First : include the librairy

	require_once('../odtphp/library/odf.php');
	
	function convertir_a_letras($numero, $tipo) {
		list($e, $d) = SPLIT('[.]', $numero);
		if ($tipo == "moneda")
			return num2letras($e, false, false)." bolivares con ".num2letras($d, false, false)." centimos";
		else if ($tipo == "decimal")
			return num2letras($e, false, false)." con ".num2letras($d, false, false);
		else if ($tipo == "entero")
			return num2letras($e, false, false);
}

function num2letras($num, $fem = true, $dec = true) {
//if (strlen($num) > 14) die("El número introducido es demasiado grande");
   $matuni[2]  = "dos";
   $matuni[3]  = "tres";
   $matuni[4]  = "cuatro";
   $matuni[5]  = "cinco";
   $matuni[6]  = "seis";
   $matuni[7]  = "siete";
   $matuni[8]  = "ocho";
   $matuni[9]  = "nueve";
   $matuni[10] = "diez";
   $matuni[11] = "once";
   $matuni[12] = "doce";
   $matuni[13] = "trece";
   $matuni[14] = "catorce";
   $matuni[15] = "quince";
   $matuni[16] = "dieciseis";
   $matuni[17] = "diecisiete";
   $matuni[18] = "dieciocho";
   $matuni[19] = "diecinueve";
   $matuni[20] = "veinte";
   $matunisub[2] = "dos";
   $matunisub[3] = "tres";
   $matunisub[4] = "cuatro";
   $matunisub[5] = "quin";
   $matunisub[6] = "seis";
   $matunisub[7] = "sete";
   $matunisub[8] = "ocho";
   $matunisub[9] = "nove";
   $matdec[2] = "veint";
   $matdec[3] = "treinta";
   $matdec[4] = "cuarenta";
   $matdec[5] = "cincuenta";
   $matdec[6] = "sesenta";
   $matdec[7] = "setenta";
   $matdec[8] = "ochenta";
   $matdec[9] = "noventa";
   $matsub[3]  = "mill";
   $matsub[5]  = "bill";
   $matsub[7]  = "mill";
   $matsub[9]  = "trill";
   $matsub[11] = "mill";
   $matsub[13] = "bill";
   $matsub[15] = "mill";
   $matmil[4]  = "millones";
   $matmil[6]  = "billones";
   $matmil[7]  = "de billones";
   $matmil[8]  = "millones de billones";
   $matmil[10] = "trillones";
   $matmil[11] = "de trillones";
   $matmil[12] = "millones de trillones";
   $matmil[13] = "de trillones";
   $matmil[14] = "billones de trillones";
   $matmil[15] = "de billones de trillones";
   $matmil[16] = "millones de billones de trillones";
   $num = trim((string)@$num);
   if ($num[0] == "-") {
      $neg = "menos ";
      $num = substr($num, 1);
   }else
      $neg = "";
   while ($num[0] == "0") $num = substr($num, 1);
   if ($num[0] < "1" or $num[0] > 9) $num = "0" . $num;
   $zeros = true;
   $punt = false;
   $ent = "";
   $fra = "";
   for ($c = 0; $c < strlen($num); $c++) {
      $n = $num[$c];
      if (! (strpos(".,´´`", $n) === false)) {
         if ($punt) break;
         else{
            $punt = true;
            continue;
         }
      }elseif (! (strpos("0123456789", $n) === false)) {
         if ($punt) {
            if ($n != "0") $zeros = false;
            $fra .= $n;
         }else
            $ent .= $n;
      }else
         break;
   }
  
   $ent = "     " . $ent;
  
   if ($dec and $fra and ! $zeros) {
      $fin = " coma";
      for ($n = 0; $n < strlen($fra); $n++) {
         if (($s = $fra[$n]) == "0")
            $fin .= " cero";
         elseif ($s == "1")
            $fin .= $fem ? " una" : " un";
         else
            $fin .= " " . $matuni[$s];
      }
   }else
      $fin = "";
   if ((int)$ent === 0) return "Cero " . $fin;
   $tex = "";
   $sub = 0;
   $mils = 0;
   $neutro = false;
  
   while ( ($num = substr($ent, -3)) != "   ") {
     
      $ent = substr($ent, 0, -3);
      if (++$sub < 3 and $fem) {
         $matuni[1] = "una";
         $subcent = "as";
      }else{
         //$matuni[1] = $neutro ? "un" : "uno";
         $matuni[1] = $neutro ? "un" : "un";
         $subcent = "os";
      }
      $t = "";
      $n2 = substr($num, 1);
      if ($n2 == "00") {
      }elseif ($n2 < 21)
         $t = " " . $matuni[(int)$n2];
      elseif ($n2 < 30) {
         $n3 = $num[2];
         if ($n3 != 0) $t = "i" . $matuni[$n3];
         $n2 = $num[1];
         $t = " " . $matdec[$n2] . $t;
      }else{
         $n3 = $num[2];
         if ($n3 != 0) $t = " y " . $matuni[$n3];
         $n2 = $num[1];
         $t = " " . $matdec[$n2] . $t;
      }
     
      $n = $num[0];
      if ($n == 1) {
         if ($num == 100) $t = " cien" . $t; else $t = " ciento" . $t;
      }elseif ($n == 5){
         $t = " " . $matunisub[$n] . "ient" . $subcent . $t;
      }elseif ($n != 0){
         $t = " " . $matunisub[$n] . "cient" . $subcent . $t;
      }
     
      if ($sub == 1) {
      }elseif (! isset($matsub[$sub])) {
         if ($num == 1) {
            $t = " mil";
         }elseif ($num > 1){
            $t .= " mil";
         }
      }elseif ($num == 1) {
         $t .= " " . $matsub[$sub] . "ón";
      }elseif ($num > 1){
         $t .= " " . $matsub[$sub] . "ones";
      }  
      if ($num == "000") $mils ++;
      elseif ($mils != 0) {
         if (isset($matmil[$sub])) $t .= " " . $matmil[$sub];
         $mils = 0;
      }
      $neutro = true;
      $tex = $t . $tex;
   }
   $tex = $neg . substr($tex, 1) . $fin;
   return $tex;
}
	//unlink("../odtphp/procesoCompra/inicioCompra".$CodActaInicio.".odt");
	
	$odf = new odf("../odtphp/procesoCompra/actainicio.odt");

	$diaLetras = array('un','dos','tres','cuatro','cinco','seis','siete','ocho','nueve','diez','once',
							'doce','trece','catorce','quince','disciseis','diecisiete','dieciocho','diecinueve','veinte',
							'veintiuno','veintidos','veintitres','venticuatro','venticinco','veintiseies','veintisiete','veintiocho',
							'veintinueve','treinta','treinta y uno');
							
	$mesLetra = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Séptiembre","Octubre","Noviembre","Diciembre",);						
	
	$condicionRNC = array("",
						", Empresa suspendida por el Art. 30 la L.C.P.",
						", Empresa registrada en el R.N.C.",
						", Empresa en proceso de descapitalización",
						", Suspendido por el Art. 139",
						", Empresa del Gobierno");

	$sql1 ="select p.NomCompleto, p.Ndocumento,

			pu.DescripCargo
			
			from mastpersonas as p
			
			join mastempleado as me on p.CodPersona=me.CodPersona
			
			join rh_puestos as pu on me.CodCargo=pu.CodCargo
			
			where me.CodEmpleado='".$codAsistenteActaInicio."'";    

    $resultado1 = $objConexion->consultar($sql1,'fila');			
	
	$sql2 ="select p.NomCompleto, p.Ndocumento,

			pu.DescripCargo
			
			from mastpersonas as p
			
			join mastempleado as me on p.CodPersona=me.CodPersona
			
			join rh_puestos as pu on me.CodCargo=pu.CodCargo
			
			where me.CodEmpleado='".$codAsistenteActaInicio2."'";    
	
	$resultado2 = $objConexion->consultar($sql2,'fila');	

	$sql3 ="select p.NomCompleto, p.Ndocumento,

			pu.DescripCargo
			
			from mastpersonas as p
			
			join mastempleado as me on p.CodPersona=me.CodPersona
			
			join rh_puestos as pu on me.CodCargo=pu.CodCargo
			
			where me.CodEmpleado='".$codDirectorActaInicio."'";    
	
	$resultado3 = $objConexion->consultar($sql3,'fila');	


	if(isset($casoLlamado) && ($casoLlamado == 0))
	{
		
	
		$cadenaCondicionReque = $codRequeGlobal;
		$cadenaCondicionSecue = implode(',',$vectorCondicionSecuencia);
	}
//	echo 	$cadenaCondicionReque ;
//echo '-'.		$cadenaCondicionSecue ;return;
	//	consulto los datos de la cotizacion
	 $sql = "SELECT 
				c.*,
				p.NomCompleto AS NomProveedor,mp.FlagSNC, mp.condicionRNC

			FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
				INNER JOIN mastpersonas p ON (c.CodProveedor = p.CodPersona)
				INNER JOIN mastproveedores mp ON (c.CodProveedor = mp.CodProveedor)
				
				
			WHERE
				rd.CodRequerimiento in (".$cadenaCondicionReque.") AND
				c.Secuencia in (".$cadenaCondicionSecue.")
				GROUP BY p.NomCompleto";
				


	$resp = $objConexion->consultar($sql,'matriz');//nombre de los proveedores sin repetir
	
	/*if ($tipoReque == 'stock')
	{*/
	$sql2 = "SELECT 
				c.*,
				p.NomCompleto AS NomProveedor, d.Descripcion, ac.*,
						lgr.Comentarios
			FROM
				lg_cotizacion c
				join  lg_requerimientos as lgr on (lgr.CodRequerimiento=c.CodRequerimiento)
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
				INNER JOIN mastpersonas p ON (c.CodProveedor = p.CodPersona)
				INNER JOIN mastproveedores mp ON (c.CodProveedor = mp.CodProveedor)
				INNER JOIN lg_itemmast AS d ON ( d.CodItem = rd.CodItem )
				INNER JOIN lg_actainicio ac ON (ac.CodActaInicio =c.NroSolicitudCotizacion)
			WHERE
				rd.CodRequerimiento in (".$cadenaCondicionReque.") AND
				c.Secuencia in (".$cadenaCondicionSecue.") group by c.NomProveedor";
				
	$resp2 = $objConexion->consultar($sql2,'matriz');//nombre de los item sin repetir
	
	//} else if ($tipoReque == 'commodity') {
			
	if (count($resp2) <= 0)
	{		
		$sql2 = "SELECT 
				c.*,
				p.NomCompleto AS NomProveedor,
				i.CodImpuesto,
				i.FactorPorcentaje, d.Descripcion, ac.*,
						lgr.Comentarios
			FROM
				lg_cotizacion c
				join  lg_requerimientos as lgr on (lgr.CodRequerimiento=c.CodRequerimiento)
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
				INNER JOIN mastpersonas p ON (c.CodProveedor = p.CodPersona)
				INNER JOIN mastproveedores mp ON (c.CodProveedor = mp.CodProveedor)
				INNER JOIN lg_commoditysub AS d 
				INNER JOIN lg_actainicio ac ON (ac.CodActaInicio =c.NroSolicitudCotizacion)
				LEFT JOIN masttiposervicioimpuesto tsi ON (mp.CodTipoServicio = tsi.CodTipoServicio)
				LEFT JOIN mastimpuestos i ON (tsi.CodImpuesto = i.CodImpuesto AND tsi.CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."')
				
			WHERE
				rd.CodRequerimiento in (".$cadenaCondicionReque.") AND
				c.Secuencia in (".$cadenaCondicionSecue.")
				and (rd.CommoditySub = d.Codigo) group by c.NomProveedor";
				
		$resp2 = $objConexion->consultar($sql2,'matriz');//nombre de los item sin repetir
				
	}
//	LEFT JOIN masttiposervicioimpuesto tsi ON (mp.CodTipoServicio = tsi.CodTipoServicio)
//LEFT JOIN mastimpuestos i ON (tsi.CodImpuesto = i.CodImpuesto AND tsi.CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."')

	$resp2 = $objConexion->consultar($sql2,'matriz');//nombre de los item sin repetir

	//echo $resp[0]['NomProveedor'];

	$anio2=date('Y', strtotime($resp2[0]['FechaInvitacion']));
	$cod='CEM-PC-02-01-'.rellenarConCero($resp2[0]['NroVisualActaInicio'],4).'-'.$anio2;
	$odf->setVars('nroProc',$cod);
	//$odf->setVars('dia',$dia);
	//$odf->setVars('diaNumero',$diaNumero);
	//$odf->setVars('mes',$mes);
	//$odf->setVars('anio',$anio);
	list($h,$min,$seg)=split("[:]", $resp2[0]['HoraReunion']);
	if($h>=12) {$ext='p.m.'; $h=$h-12; } else {$ext='a.m.'; }
	$hora=$h.':'.$min.':'.$seg.' '.$ext;
	$odf->setVars('hora',$hora);
	
	$odf->setVars('montoN',number_format($resp2[0]['PresupuestoBase'],2,',','.'));
	list($int, $dec) = split("[.]", $resp2[0]['PresupuestoBase']);
	$int_letras = strtoupper(convertir_a_letras($int, "entero"));
	$montoL = $int_letras." CON ".$dec."/100";
	$odf->setVars('montoL',$montoL);
	//$odf->setVars('nroSolicitud',utf8_decode($resp[0]['NroSolicitudCotizacion']));

	list($a,$m,$d) = split('-',$resp2[0]['FechaReunion']);
		$fechaReunion=$d.'-'.$m.'-'.$a;
		$diaR = $diaLetras[$d-1];
		$odf->setVars('diaR',$diaR);
		$odf->setVars('mesR',$mesLetra[$m-1]);
		$odf->setVars('anioR',$a);
		$odf->setVars('diaNumeroR',$d);
		
	list($a1,$m1,$d1) = split('-',$resp2[0]['FechaInicio']);
		$fechaIP=$d1.'-'.$m1.'-'.$a1;
		$odf->setVars('fechai',$fechaIP);
				
	list($a2,$m2,$d2) = split('-',$resp2[0]['FechaFin']);
		$fechaFP=$d2.'-'.$m2.'-'.$a2;
		$odf->setVars('fechaf',$fechaFP);
	
	$odf->setVars('nombreDirector',$resultado3['NomCompleto']);
	$odf->setVars('cargo',$resultado3['DescripCargo']);
	$odf->setVars('nombreAnalista1',$resultado1['NomCompleto']);
	$odf->setVars('cargoAnalista1',$resultado1['DescripCargo']);
	if($resultado2['NomCompleto']!='')
	{
	$analistas=$resultado3['NomCompleto']." ".$resultado3['DescripCargo'].", ".$resultado1['NomCompleto']." ".$resultado1['DescripCargo']." y ".$resultado2['NomCompleto']." ".$resultado2['DescripCargo'];
	$odf->setVars('nombreAnalista2',$resultado2['NomCompleto']);
	$odf->setVars('cargoAnalista2',$resultado2['DescripCargo']);
	}
	else 
	{
	$analistas=$resultado3['NomCompleto']." ".$resultado3['DescripCargo']." y ".$resultado1['NomCompleto']." ".$resultado1['DescripCargo'].", ";
	$odf->setVars('nombreAnalista2'," ");
	$odf->setVars('cargoAnalista2'," ");
	}
	
	$odf->setVars('analistas', $analistas);
	
	
	
	
	$proveedor = "";
	
	for ($i = 0; $i < count($resp); $i++) {
		
		
		
		if($resp[$i]['FlagSNC'] == 'S')
		{
			$descripcionSNC = " con nivel estimado de contratación de ".$resp[$i]['Nivel']." y calificación financiera de ".$resp[$i]['Calificacion']." inscrito en el SNC ".$condicionRNC[$resp[$i]['condicionRNC']];
			
		} else {
			
			$descripcionSNC = ' No inscrito en el SNC';
			
		}
			$proveedor .= $resp[$i]['NomProveedor'].$descripcionSNC;
			if($i<count($resp)-2)
			$proveedor .= ", ";
			else if ($i==count($resp)-2)
			$proveedor .= " y ";
			
	}
		
	$odf->setVars('proveedores',$proveedor);
	
	//$requerimiento = "";
	
			$requerimiento = $resp2[0]['Comentarios'];
	
	
	//$odf->setVars('nroProc',$cod);
	$odf->setVars('requerimiento',$requerimiento);
	$odf->setVars('nroActa',$numeroVisualActa);
	


	$odf->saveToDisk("../odtphp/procesoCompra/inicioCompra".$CodActaInicio.".odt");

 
?>
