<?php
include ("fphp_nomina.php");

function generarObligacion($codproveedor, $codtipodocumento, $nrodocumento, $organismo, $periodo, $nomina, $proceso) {
	//$__archivo = fopen("query.sql", "w+");
	//	fwrite($__archivo, $sql.";\n\n");
	connect();
	$_CCOSTOPR = getParametros("CCOSTOPR");
	
	//	consulto la obligacion
	$sql = "SELECT
				CodProveedor,
				CodTipoDocumento,
				NroDocumento,
				CodOrganismo,
				NroCuenta,
				CodTipoPago,
				CodTipoServicio,
				MontoObligacion,
				MontoImpuestoOtros,
				MontoNoAfecto,
				Voucher,
				Comentarios,
				ComentariosAdicional,
				CodProveedorPagar,
				CodCuenta,
				CodCentroCosto,
				TipoObligacion
			FROM pr_obligaciones
			WHERE
				CodProveedor = '".$codproveedor."' AND
				CodTipoDocumento = '".$codtipodocumento."' AND
				NroDocumento = '".$nrodocumento."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	while ($field = mysql_fetch_array($query)) {
		if ($field['TipoObligacion'] == "04") {
			$flagcompromiso = "N";
			$flagpresupuesto = "N";
		} else {
			//	valido disponibilidad
			$sql = "SELECT
						oc.cod_partida,
						oc.Monto,
						pvd.MontoAjustado,
						pvd.MontoCompromiso
					FROM
						pr_obligacionescuenta oc
						LEFT JOIN pv_presupuesto pv ON (pv.Organismo = '".$organismo."' AND
														pv.EjercicioPpto = '".substr($periodo, 0, 4)."')
						LEFT JOIN pv_presupuestodet pvd ON (pvd.Organismo = pv.Organismo AND
															pvd.CodPresupuesto = pv.CodPresupuesto AND
															pvd.cod_partida = oc.cod_partida)
					WHERE
						oc.CodProveedor = '".$codproveedor."' AND
						oc.CodTipoDocumento = '".$codtipodocumento."' AND
						oc.NroDocumento = '".$nrodocumento."'";
			$query_disp = mysql_query($sql) or die($sql.mysql_error());
			while ($field_disp = mysql_fetch_array($query_disp)) {
				if ($field_disp['Monto'] > ($field_disp['MontoAjustado'] - $field_disp['MontoCompromiso'])) 
					die("Se encontró la partida $field[cod_partida] sin disponibilidad presupuestaria");
			}
			$flagcompromiso = "S";
			$flagpresupuesto = "S";
		}
		//	inserto la obligacion
		$NroRegistro = getCodigo_2("ap_obligaciones", "NroRegistro", "CodOrganismo", $organismo, 6);
		$sql = "INSERT INTO ap_obligaciones (
							CodProveedor,
							CodTipoDocumento,
							NroDocumento,
							NroControl,
							CodOrganismo,
							NroCuenta,
							CodTipoPago,
							FechaRegistro,
							FechaFactura,
							CodTipoServicio,
							ReferenciaTipoDocumento,
							ReferenciaNroDocumento,
							MontoObligacion,
							MontoImpuestoOtros,
							MontoNoAfecto,
							IngresadoPor,
							RevisadoPor,
							FechaPreparacion,
							FechaRevision,
							NroRegistro,
							Comentarios,
							ComentariosAdicional,
							CodProveedorPagar,
							FechaDocumento,
							FechaVencimiento,
							FechaRecepcion,
							FechaProgramada,
							Estado,
							CodCuenta,
							Periodo,
							CodCentroCosto,
							FlagCompromiso,
							FlagPresupuesto,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$field['CodProveedor']."',
							'".$field['CodTipoDocumento']."',
							'".$field['NroDocumento']."',
							'".$field['NroDocumento']."',
							'".$field['CodOrganismo']."',
							'".$field['NroCuenta']."',
							'".$field['CodTipoPago']."',
							NOW(),
							NOW(),
							'".$field['CodTipoServicio']."',
							'NO',
							'".$field['NroDocumento']."',
							'".$field['MontoObligacion']."',
							'-".$field['MontoImpuestoOtros']."',
							'".$field['MontoNoAfecto']."',
							'".$_SESSION['CODPERSONA_ACTUAL']."',
							'".$_SESSION['CODPERSONA_ACTUAL']."',
							NOW(),
							NOW(),
							'".$NroRegistro."',
							'".$field['Comentarios']."',
							'".$field['ComentariosAdicional']."',
							'".$field['CodProveedorPagar']."',
							NOW(),
							NOW(),
							NOW(),
							NOW(),
							'PR',
							'".$field['CodCuenta']."',
							NOW(),
							'".$field['CodCentroCosto']."',
							'".$flagcompromiso."',
							'".$flagpresupuesto."',
							'".$_SESSION['USUARIO_ACTUAL']."',
							NOW()								
				)";
		$query_insert = mysql_query($sql) or die($sql.mysql_error());
		
		//	actualizo pr_obligacion
		$sql = "UPDATE pr_obligaciones
				SET
					NroRegistro = '".$NroRegistro."',
					FlagTransferido = 'S'
				WHERE
					CodProveedor = '".$codproveedor."' AND
					CodTipoDocumento = '".$codtipodocumento."' AND
					NroDocumento = '".$nrodocumento."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
	}
	
	//	consulto las cuentas
	$sql = "SELECT
				CodProveedor,
				CodTipoDocumento,
				NroDocumento,
				Linea,
				Descripcion,
				Monto,
				CodCuenta,
				cod_partida,
				CodCentroCosto
			FROM pr_obligacionescuenta
			WHERE
				CodProveedor = '".$codproveedor."' AND
				CodTipoDocumento = '".$codtipodocumento."' AND
				NroDocumento = '".$nrodocumento."'
			GROUP BY cod_partida";
	$query = mysql_query($sql) or die($sql.mysql_error());
	while ($field = mysql_fetch_array($query)) {
		//	inserto la cuenta
		$sql = "INSERT INTO ap_obligacionescuenta (
							CodProveedor,
							CodTipoDocumento,
							NroDocumento,
							Linea,
							Descripcion,
							Monto,
							CodCuenta,
							cod_partida,
							TipoOrden,
							NroOrden,
							FlagNoAfectoIGV,
							CodCentroCosto,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$field['CodProveedor']."',
							'".$field['CodTipoDocumento']."',
							'".$field['NroDocumento']."',
							'".$field['Linea']."',
							'".$field['Descripcion']."',
							'".$field['Monto']."',
							'".$field['CodCuenta']."',
							'".$field['cod_partida']."',
							'NO',
							'".$field['NroDocumento']."',
							'S',
							'".$field['CodCentroCosto']."',
							'".$_SESSION['USUARIO_ACTUAL']."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die($sql.mysql_error());
		
		//	inserto la distribucion
		$sql = "INSERT INTO ap_distribucionobligacion (
							CodProveedor,
							CodTipoDocumento,
							NroDocumento,
							Monto,
							CodCuenta,
							cod_partida,
							CodCentroCosto,
							Periodo,
							Anio,
							FlagCompromiso,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$field['CodProveedor']."',
							'".$field['CodTipoDocumento']."',
							'".$field['NroDocumento']."',
							'".$field['Monto']."',
							'".$field['CodCuenta']."',
							'".$field['cod_partida']."',
							'".$field['CodCentroCosto']."',
							'".$periodo."',
							'".substr($periodo, 0, 4)."',
							'S',
							'PE',
							'".$_SESSION['USUARIO_ACTUAL']."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die($sql.mysql_error());
		
		if ($flagcompromiso == "S") {
			//	inserto la distribucion
			$sql = "INSERT INTO lg_distribucioncompromisos (
								Anio,
								CodOrganismo,
								CodProveedor,
								CodTipoDocumento,
								NroDocumento,
								Secuencia,
								Linea,
								Mes,
								CodCentroCosto,
								cod_partida,
								Monto,
								Periodo,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".substr($periodo, 0, 4)."',
								'".$organismo."',							
								'".$field['CodProveedor']."',
								'".$field['CodTipoDocumento']."',
								'".$field['NroDocumento']."',
								'".$field['Linea']."',
								'1',
								'".substr($periodo, 5, 2)."',
								'".$field['CodCentroCosto']."',
								'".$field['cod_partida']."',
								'".$field['Monto']."',
								'".$periodo."',
								'PE',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
	}
	
	//	consulto las retenciones
	$sql = "SELECT
				CodProveedor,
				CodTipoDocumento,
				NroDocumento,
				Linea,
				CodConcepto,
				MontoImpuesto,
				MontoAfecto,
				FlagProvision,
				CodCuenta
			FROM pr_obligacionesretenciones
			WHERE
				CodProveedor = '".$codproveedor."' AND
				CodTipoDocumento = '".$codtipodocumento."' AND
				NroDocumento = '".$nrodocumento."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	while ($field = mysql_fetch_array($query)) {
		//	inserto la obligacion
		$sql = "INSERT INTO ap_obligacionesimpuesto (
							CodProveedor,
							CodTipoDocumento,
							NroDocumento,
							Linea,
							CodConcepto,
							MontoImpuesto,
							MontoAfecto,
							FlagProvision,
							CodCuenta,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$field['CodProveedor']."',
							'".$field['CodTipoDocumento']."',
							'".$field['NroDocumento']."',
							'".$field['Linea']."',
							'".$field['CodConcepto']."',
							'-".$field['MontoImpuesto']."',
							'".$field['MontoAfecto']."',
							'".$field['FlagProvision']."',
							'".$field['CodCuenta']."',
							'".$_SESSION['USUARIO_ACTUAL']."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die($sql.mysql_error());
	}
}

function validarObligacion($codproveedor, $codtipodocumento, $nrodocumento, $anio, $organismo) {
	//	consulto para saber si la obligacion fue transferida a cxp
	$sql = "SELECT Estado
			FROM ap_obligaciones
			WHERE
				CodProveedor = '".$codproveedor."' AND
				CodTipoDocumento = '".$codtipodocumento."' AND
				NroDocumento = '".$nrodocumento."' AND
				Estado <> 'AN'";
	$query_cxp = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_cxp) != 0) die("¡ERROR: No puede seleccionar obligaciones que fueron transferidas a CxP\nDebe anular las obligaciones para generarlas nuevamente!");
	else {
		$sql = "DELETE FROM ap_obligaciones
				WHERE
					CodProveedor = '".$codproveedor."' AND
					CodTipoDocumento = '".$codtipodocumento."' AND
					NroDocumento = '".$nrodocumento."' AND
					Estado = 'AN'";
		$query_cxp = mysql_query($sql) or die ($sql.mysql_error());
		
		$sql = "DELETE FROM ap_obligacionescuenta
				WHERE
					CodProveedor = '".$codproveedor."' AND
					CodTipoDocumento = '".$codtipodocumento."' AND
					NroDocumento = '".$nrodocumento."'";
		$query_cxp = mysql_query($sql) or die ($sql.mysql_error());
		
		$sql = "DELETE FROM ap_obligacionesimpuesto
				WHERE
					CodProveedor = '".$codproveedor."' AND
					CodTipoDocumento = '".$codtipodocumento."' AND
					NroDocumento = '".$nrodocumento."'";
		$query_cxp = mysql_query($sql) or die ($sql.mysql_error());
		
		$sql = "DELETE FROM ap_distribucionobligacion
				WHERE
					CodProveedor = '".$codproveedor."' AND
					CodTipoDocumento = '".$codtipodocumento."' AND
					NroDocumento = '".$nrodocumento."'";
		$query_cxp = mysql_query($sql) or die ($sql.mysql_error());
		
		$sql = "DELETE FROM lg_distribucioncompromisos
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					CodProveedor = '".$codproveedor."' AND
					CodTipoDocumento = '".$codtipodocumento."' AND
					NroDocumento = '".$nrodocumento."'";
		$query_cxp = mysql_query($sql) or die ($sql.mysql_error());
		
		
	}
}

///////////////////////////////////////////////////////////////////////////////
//	SCRIPTS PARA AJAX
///////////////////////////////////////////////////////////////////////////////
if ($modulo == "INTERFASE-BANCARIA") {
	connect();
	//$_PARAMETROS = getParametros("CTANOMINA");
	$_TIPODOCCXP = getParametros("TIPODOCCXP");
	$_TIPOSERVCXP = getParametros("TIPOSERVCXP");
	$_CCOSTOPR = getParametros("CCOSTOPR");
	
	//	calcular obligaciones
	if ($accion == "calcularObligaciones") {
			$__archivo = fopen("query.sql", "w+");
		//	fwrite($__archivo, $sql.";\n\n");
		
		mysql_query("BEGIN");
		list($periodo_anio, $periodo_mes) = split('[-]', $periodo);
		//	consulto para saber si la obligacion fue transferida a cxp
		$sql = "SELECT
					po.FlagTransferido,
					ao.Estado
				FROM
					pr_obligaciones po
					INNER JOIN ap_obligaciones ao ON (po.CodProveedor = ao.CodProveedor AND
													  po.CodTipoDocumento = ao.CodTipoDocumento AND
													  po.NroDocumento = ao.NroDocumento)
				WHERE
					po.CodOrganismo = '".$organismo."' AND
					po.CodTipoNom = '".$nomina."' AND
					po.Periodo = '".$periodo."' AND
					po.CodTipoProceso = '".$proceso."'";	fwrite($__archivo, $sql.";\n\n");
		$query_cxp = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_cxp = mysql_fetch_array($query_cxp)) {
			if ($field_cxp['FlagTransferido'] == "S" && $field_cxp['Estado'] <> "AN")
				die("¡ERROR: Algunas obligaciones se encuentran actualmente transferidas a CxP\nDebe anular las obligaciones transferidas para calcularlas nuevamente!");
		}
		
		//	consulto para eliminar las obligaciones cxp
		$sql = "SELECT
					CodProveedor,
					CodTipoDocumento,
					NroDocumento
				FROM
					pr_obligaciones
				WHERE
					CodOrganismo = '".$organismo."' AND
					CodTipoNom = '".$nomina."' AND
					Periodo = '".$periodo."' AND
					CodTipoProceso = '".$proceso."'";
		$query_cxp = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_cxp = mysql_fetch_array($query_cxp)) {
			$sql = "DELETE FROM ap_obligaciones
					WHERE
						CodProveedor = '".$field_cxp['CodProveedor']."' AND
						CodTipoDocumento = '".$field_cxp['CodTipoDocumento']."' AND
						NroDocumento = '".$field_cxp['NroDocumento']."'";	fwrite($__archivo, $sql.";\n\n");
			$query_delete = mysql_query($sql) or die ($sql.mysql_error());
			
			$sql = "DELETE FROM ap_obligacionescuenta
					WHERE
						CodProveedor = '".$field_cxp['CodProveedor']."' AND
						CodTipoDocumento = '".$field_cxp['CodTipoDocumento']."' AND
						NroDocumento = '".$field_cxp['NroDocumento']."'";	fwrite($__archivo, $sql.";\n\n");
			$query_delete = mysql_query($sql) or die ($sql.mysql_error());
			
			$sql = "DELETE FROM ap_obligacionesimpuesto
					WHERE
						CodProveedor = '".$field_cxp['CodProveedor']."' AND
						CodTipoDocumento = '".$field_cxp['CodTipoDocumento']."' AND
						NroDocumento = '".$field_cxp['NroDocumento']."'";	fwrite($__archivo, $sql.";\n\n");
			$query_delete = mysql_query($sql) or die ($sql.mysql_error());
			
			$sql = "DELETE FROM ap_distribucionobligacion
					WHERE
						CodProveedor = '".$field_cxp['CodProveedor']."' AND
						CodTipoDocumento = '".$field_cxp['CodTipoDocumento']."' AND
						NroDocumento = '".$field_cxp['NroDocumento']."'";	fwrite($__archivo, $sql.";\n\n");
			$query_delete = mysql_query($sql) or die ($sql.mysql_error());
			
			$sql = "DELETE FROM lg_distribucioncompromisos
					WHERE
						CodProveedor = '".$field_cxp['CodProveedor']."' AND
						CodTipoDocumento = '".$field_cxp['CodTipoDocumento']."' AND
						NroDocumento = '".$field_cxp['NroDocumento']."'";	fwrite($__archivo, $sql.";\n\n");
			$query_delete = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		//	consulto para obtener los calculos que se hayan insertado anteriormente
		$sql = "SELECT
					CodProveedor,
					CodTipoDocumento,
					NroDocumento
				FROM pr_obligaciones
				WHERE
					CodOrganismo = '".$organismo."' AND
					CodTipoNom = '".$nomina."' AND
					Periodo = '".$periodo."' AND
					CodTipoProceso = '".$proceso."'";	fwrite($__archivo, $sql.";\n\n");
		$query_calculos = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_calculos = mysql_fetch_array($query_calculos)) {
			//	elimino las obligaciones x cuentas
			$sql = "DELETE FROM pr_obligacionescuenta
					WHERE
						CodProveedor = '".$field_calculos['CodProveedor']."' AND
						CodTipoDocumento = '".$field_calculos['CodTipoDocumento']."' AND
						NroDocumento = '".$field_calculos['NroDocumento']."'";	fwrite($__archivo, $sql.";\n\n");
			$query_delete = mysql_query($sql) or die ($sql.mysql_error());
			
			//	elimino las obligaciones x retenciones
			$sql = "DELETE FROM pr_obligacionesretenciones
					WHERE
						CodProveedor = '".$field_calculos['CodProveedor']."' AND
						CodTipoDocumento = '".$field_calculos['CodTipoDocumento']."' AND
						NroDocumento = '".$field_calculos['NroDocumento']."'";	fwrite($__archivo, $sql.";\n\n");
			$query_delete = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		//	elimino las obligaciones
		$sql = "DELETE FROM pr_obligaciones
				WHERE
					CodOrganismo = '".$organismo."' AND
					CodTipoNom = '".$nomina."' AND
					Periodo = '".$periodo."' AND
					CodTipoProceso = '".$proceso."'";	fwrite($__archivo, $sql.";\n\n");
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
		
		//	
		$sql = "SELECT CodTipoDocumento
				FROM pr_tiponominaproceso
				WHERE
					CodTipoNom = '".$nomina."' AND
					CodTipoProceso = '".$proceso."'";	fwrite($__archivo, $sql.";\n\n");
		$query_doc = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_doc) != 0) $field_doc = mysql_fetch_array($query_doc);
		
		//	obtengo parametro
		$_INTERFASEAP = getParametro("INTERFASEAP");
		if ($_INTERFASEAP == "S") {
			if ($proceso != "BVC") {
				$sql = "(SELECT
							tn.Nomina,
							tp.Descripcion AS NomProceso,
							o.CodPersona AS CodProveedor,
							mp2.NomCompleto AS NomProveedor,
							'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
							me.CodTipoPago,
							'".$_TIPOSERVCXP['TIPOSERVCXP']."' AS CodTipoServicio,
							'01' AS Ficha,
							SUM(tnec.Monto) AS MontoIngreso,
							'".$_PARAMETROS['CTANOMINA']."' AS CodCuenta
						FROM
							pr_tiponominaempleadoconcepto tnec
							INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
							INNER JOIN pr_tipoproceso tp ON (tnec.CodTipoProceso = tp.CodTipoProceso)
							INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
							INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
							INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
							INNER JOIN mastpersonas mp2 ON (o.CodPersona = mp2.CodPersona)
							INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)    
							INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
							INNER JOIN pr_conceptoperfil cp ON (tn.CodPerfilConcepto = cp.CodPerfilConcepto)
							INNER JOIN pr_conceptoperfildetalle cpd ON (cp.CodPerfilConcepto = cpd.CodPerfilConcepto AND
																		tnec.CodTipoProceso = cpd.CodTipoProceso AND
																		tnec.CodConcepto = cpd.CodConcepto)
						WHERE
							me.CodTipoPago = '01' AND
							tnec.CodTipoNom = '".$nomina."' AND
							tnec.Periodo = '".$periodo."' AND
							tnec.CodOrganismo = '".$organismo."' AND
							tnec.CodTipoProceso = '".$proceso."' AND
							c.Tipo = 'I'
						GROUP BY tnec.CodOrganismo)
						UNION
						(SELECT
							tn.Nomina,
							tp.Descripcion AS NomProceso,
							mp1.CodPersona AS CodProveedor,
							mp1.NomCompleto AS NomProveedor,
							'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
							me.CodTipoPago,
							'".$_TIPOSERVCXP['TIPOSERVCXP']."' AS CodTipoServicio,
							'02' AS Ficha,
							SUM(tnec.Monto) AS MontoIngreso,
							'".$_PARAMETROS['CTANOMINA']."' AS CodCuenta
						FROM
							pr_tiponominaempleadoconcepto tnec
							INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
							INNER JOIN pr_tipoproceso tp ON (tnec.CodTipoProceso = tp.CodTipoProceso)
							INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
							INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
							INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
							INNER JOIN mastpersonas mp2 ON (o.CodPersona = mp2.CodPersona)
							INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)    
							INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
							INNER JOIN pr_conceptoperfil cp ON (tn.CodPerfilConcepto = cp.CodPerfilConcepto)
							INNER JOIN pr_conceptoperfildetalle cpd ON (cp.CodPerfilConcepto = cpd.CodPerfilConcepto AND
																		tnec.CodTipoProceso = cpd.CodTipoProceso AND
																		tnec.CodConcepto = cpd.CodConcepto)
						WHERE
							me.CodTipoPago = '02' AND
							tnec.CodTipoNom = '".$nomina."' AND
							tnec.Periodo = '".$periodo."' AND
							tnec.CodOrganismo = '".$organismo."' AND
							tnec.CodTipoProceso = '".$proceso."' AND
							c.Tipo = 'I'
						GROUP BY mp1.CodPersona)
						UNION
						(SELECT
							tn.Nomina,
							tp.Descripcion AS NomProceso,
							c.CodPersona AS CodProveedor,
							mp2.NomCompleto AS NomProveedor,
							'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
							p.CodTipoPago,
							'".$_TIPOSERVCXP['TIPOSERVCXP']."' AS CodTipoServicio,
							'03' AS Ficha,
							SUM(tnec.Monto) AS MontoIngreso,
							cpd.CuentaHaber AS CodCuenta
						 FROM
							pr_tiponominaempleadoconcepto tnec
							INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
							INNER JOIN pr_tipoproceso tp ON (tnec.CodTipoProceso = tp.CodTipoProceso)
							INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
							INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
							INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
							INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
							INNER JOIN pr_conceptoperfil cp ON (tn.CodPerfilConcepto = cp.CodPerfilConcepto)
							INNER JOIN pr_conceptoperfildetalle cpd ON (cp.CodPerfilConcepto = cpd.CodPerfilConcepto AND
																		tnec.CodTipoProceso = cpd.CodTipoProceso AND
																		tnec.CodConcepto = cpd.CodConcepto)
							INNER JOIN mastpersonas mp2 ON (c.CodPersona = mp2.CodPersona)
							INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)
						 WHERE
							tnec.CodTipoNom = '".$nomina."' AND
							tnec.Periodo = '".$periodo."' AND
							tnec.CodOrganismo = '".$organismo."' AND
							tnec.CodTipoProceso = '".$proceso."' AND
							c.Tipo = 'A'
						 GROUP BY c.CodPersona)
						UNION
						(SELECT
							tn.Nomina,
							tp.Descripcion AS NomProceso,
							rj.Demandante AS CodProveedor,
							mp2.NomCompleto AS NomProveedor,
							'".$_TIPODOCCXP['TIPODOCCXP']."' AS CodTipoDocumento,
							p.CodTipoPago,
							'".$_TIPOSERVCXP['TIPOSERVCXP']."' AS CodTipoServicio,
							'04' AS Ficha,
							SUM(tnec.Monto) AS MontoIngreso,
							'".$_PARAMETROS['CTANOMINA']."' AS CodCuenta
						 FROM
							pr_tiponominaempleadoconcepto tnec
							INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
							INNER JOIN pr_tipoproceso tp ON (tnec.CodTipoProceso = tp.CodTipoProceso)
							INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
							INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
							INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
							INNER JOIN rh_retencionjudicial rj ON (tnec.CodPersona = rj.CodPersona AND
																   tnec.CodOrganismo = rj.CodOrganismo)
							INNER JOIN rh_retencionjudicialconceptos rjc ON (rj.CodRetencion = rjc.CodRetencion AND
																			 tnec.CodOrganismo = rjc.CodOrganismo AND
																			 tnec.CodConcepto = rjc.CodConcepto)
							INNER JOIN mastpersonas mp2 ON (rj.Demandante = mp2.CodPersona)
							INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)
						 WHERE
							tnec.CodTipoNom = '".$nomina."' AND
							tnec.Periodo = '".$periodo."' AND
							tnec.CodOrganismo = '".$organismo."' AND
							tnec.CodTipoProceso = '".$proceso."'
						 GROUP BY rj.Demandante)";	fwrite($__archivo, $sql.";\n\n");
			} else {
				$sql = "(SELECT
							tn.Nomina,
							tp.Descripcion AS NomProceso,
							mp1.CodPersona AS CodProveedor,
							mp1.NomCompleto AS NomProveedor,
							'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
							me.CodTipoPago,
							'".$_TIPOSERVCXP['TIPOSERVCXP']."' AS CodTipoServicio,
							'01' AS Ficha,
							SUM(tnec.Monto) AS MontoIngreso,
							'".$_PARAMETROS['CTANOMINA']."' AS CodCuenta
						FROM
							pr_tiponominaempleadoconcepto tnec
							INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
							INNER JOIN pr_tipoproceso tp ON (tnec.CodTipoProceso = tp.CodTipoProceso)
							INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
							INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
							INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
							INNER JOIN mastpersonas mp2 ON (o.CodPersona = mp2.CodPersona)
							INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)    
							INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
							INNER JOIN pr_conceptoperfil cp ON (tn.CodPerfilConcepto = cp.CodPerfilConcepto)
							INNER JOIN pr_conceptoperfildetalle cpd ON (cp.CodPerfilConcepto = cpd.CodPerfilConcepto AND
																		tnec.CodTipoProceso = cpd.CodTipoProceso AND
																		tnec.CodConcepto = cpd.CodConcepto)
						WHERE

							me.CodTipoPago = '01' AND
							tnec.CodTipoNom = '".$nomina."' AND
							tnec.Periodo = '".$periodo."' AND
							tnec.CodOrganismo = '".$organismo."' AND
							tnec.CodTipoProceso = '".$proceso."' AND
							c.Tipo = 'I'
						GROUP BY mp1.CodPersona)
						UNION
						(SELECT
							tn.Nomina,
							tp.Descripcion AS NomProceso,
							mp1.CodPersona AS CodProveedor,
							mp1.NomCompleto AS NomProveedor,
							'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
							me.CodTipoPago,
							'".$_TIPOSERVCXP['TIPOSERVCXP']."' AS CodTipoServicio,
							'02' AS Ficha,
							SUM(tnec.Monto) AS MontoIngreso,
							'".$_PARAMETROS['CTANOMINA']."' AS CodCuenta
						FROM
							pr_tiponominaempleadoconcepto tnec
							INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
							INNER JOIN pr_tipoproceso tp ON (tnec.CodTipoProceso = tp.CodTipoProceso)
							INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
							INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
							INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
							INNER JOIN mastpersonas mp2 ON (o.CodPersona = mp2.CodPersona)
							INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)    
							INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
							INNER JOIN pr_conceptoperfil cp ON (tn.CodPerfilConcepto = cp.CodPerfilConcepto)
							INNER JOIN pr_conceptoperfildetalle cpd ON (cp.CodPerfilConcepto = cpd.CodPerfilConcepto AND
																		tnec.CodTipoProceso = cpd.CodTipoProceso AND
																		tnec.CodConcepto = cpd.CodConcepto)
						WHERE
							me.CodTipoPago = '02' AND
							tnec.CodTipoNom = '".$nomina."' AND
							tnec.Periodo = '".$periodo."' AND
							tnec.CodOrganismo = '".$organismo."' AND
							tnec.CodTipoProceso = '".$proceso."' AND
							c.Tipo = 'I'
						GROUP BY mp1.CodPersona)
						UNION
						(SELECT
							tn.Nomina,
							tp.Descripcion AS NomProceso,
							c.CodPersona AS CodProveedor,
							mp2.NomCompleto AS NomProveedor,
							'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
							p.CodTipoPago,
							'".$_TIPOSERVCXP['TIPOSERVCXP']."' AS CodTipoServicio,
							'03' AS Ficha,
							SUM(tnec.Monto) AS MontoIngreso,
							cpd.CuentaHaber AS CodCuenta
						 FROM
							pr_tiponominaempleadoconcepto tnec
							INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
							INNER JOIN pr_tipoproceso tp ON (tnec.CodTipoProceso = tp.CodTipoProceso)
							INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
							INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
							INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
							INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
							INNER JOIN pr_conceptoperfil cp ON (tn.CodPerfilConcepto = cp.CodPerfilConcepto)
							INNER JOIN pr_conceptoperfildetalle cpd ON (cp.CodPerfilConcepto = cpd.CodPerfilConcepto AND
																		tnec.CodTipoProceso = cpd.CodTipoProceso AND
																		tnec.CodConcepto = cpd.CodConcepto)
							INNER JOIN mastpersonas mp2 ON (c.CodPersona = mp2.CodPersona)
							INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)
						 WHERE
							tnec.CodTipoNom = '".$nomina."' AND
							tnec.Periodo = '".$periodo."' AND
							tnec.CodOrganismo = '".$organismo."' AND
							tnec.CodTipoProceso = '".$proceso."' AND
							c.Tipo = 'A'
						 GROUP BY c.CodPersona)
						UNION
						(SELECT
							tn.Nomina,
							tp.Descripcion AS NomProceso,
							rj.Demandante AS CodProveedor,
							mp2.NomCompleto AS NomProveedor,
							'".$_TIPODOCCXP['TIPODOCCXP']."' AS CodTipoDocumento,
							p.CodTipoPago,
							'".$_TIPOSERVCXP['TIPOSERVCXP']."' AS CodTipoServicio,
							'04' AS Ficha,
							SUM(tnec.Monto) AS MontoIngreso,
							'".$_PARAMETROS['CTANOMINA']."' AS CodCuenta
						 FROM
							pr_tiponominaempleadoconcepto tnec
							INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
							INNER JOIN pr_tipoproceso tp ON (tnec.CodTipoProceso = tp.CodTipoProceso)
							INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
							INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
							INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
							INNER JOIN rh_retencionjudicial rj ON (tnec.CodPersona = rj.CodPersona AND
																   tnec.CodOrganismo = rj.CodOrganismo)
							INNER JOIN rh_retencionjudicialconceptos rjc ON (rj.CodRetencion = rjc.CodRetencion AND
																			 tnec.CodOrganismo = rjc.CodOrganismo AND
																			 tnec.CodConcepto = rjc.CodConcepto)
							INNER JOIN mastpersonas mp2 ON (rj.Demandante = mp2.CodPersona)
							INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)
						 WHERE
							tnec.CodTipoNom = '".$nomina."' AND
							tnec.Periodo = '".$periodo."' AND
							tnec.CodOrganismo = '".$organismo."' AND
							tnec.CodTipoProceso = '".$proceso."'
						 GROUP BY rj.Demandante)";	fwrite($__archivo, $sql.";\n\n");
			}
		}
		
		//	consulto las obligaciones a insertar
		$query = mysql_query($sql) or die ($sql.mysql_error());
		while ($field = mysql_fetch_array($query)) {
			$field_descuento['MontoDescuento'] = 0.00;
			$field_retencion['MontoRetencion'] = 0.00;
			$field_adelanto['MontoAdelanto'] = 0.00;
			if ($proceso == "BVC") {
				$filtro_retencion = " AND tnec2.CodPersona = '".$field['CodProveedor']."'";
			}
			//	obtengo retenciones y descuentos
			if ($field['Ficha'] == "01") {
				//	descuento
				$sql = "SELECT SUM(tnec2.Monto) AS MontoDescuento
						FROM
							pr_tiponominaempleadoconcepto tnec2
							INNER JOIN tiponomina tn2 ON (tnec2.CodTipoNom = tn2.CodTipoNom)
							INNER JOIN pr_tipoproceso tp2 ON (tnec2.CodTipoProceso = tp2.CodTipoProceso)
							INNER JOIN mastorganismos o2 ON (tnec2.CodOrganismo = o2.CodOrganismo)
							INNER JOIN mastpersonas mp21 ON (tnec2.CodPersona = mp21.CodPersona)
							INNER JOIN mastempleado me2 ON (mp21.CodPersona = me2.CodPersona)
							INNER JOIN mastpersonas mp22 ON (o2.CodPersona = mp22.CodPersona)
							INNER JOIN mastproveedores p2 ON (mp22.CodPersona = p2.CodProveedor)
							INNER JOIN pr_concepto c2 ON (tnec2.CodConcepto = c2.CodConcepto)
							INNER JOIN pr_conceptoperfil cp2 ON (tn2.CodPerfilConcepto = cp2.CodPerfilConcepto)
							INNER JOIN pr_conceptoperfildetalle cpd2 ON (cp2.CodPerfilConcepto = cpd2.CodPerfilConcepto AND
																		 tnec2.CodTipoProceso = cpd2.CodTipoProceso AND
																		 tnec2.CodConcepto = cpd2.CodConcepto)
						WHERE
							me2.CodTipoPago = '01' AND
							tnec2.CodTipoNom = '".$nomina."' AND
							tnec2.Periodo = '".$periodo."' AND
							tnec2.CodOrganismo = '".$organismo."' AND
							tnec2.CodTipoProceso = '".$proceso."' AND
							c2.Tipo = 'D' AND
							c2.FlagRetencion = 'N' $filtro_retencion
						GROUP BY tnec2.CodOrganismo";	fwrite($__archivo, $sql.";\n\n");
				$query_descuento = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_descuento) != 0) $field_descuento = mysql_fetch_array($query_descuento);
				
				//	retenciones
				$sql = "SELECT SUM(tnec2.Monto) AS MontoRetencion
						FROM
							pr_tiponominaempleadoconcepto tnec2
							INNER JOIN tiponomina tn2 ON (tnec2.CodTipoNom = tn2.CodTipoNom)
							INNER JOIN pr_tipoproceso tp2 ON (tnec2.CodTipoProceso = tp2.CodTipoProceso)
							INNER JOIN mastorganismos o2 ON (tnec2.CodOrganismo = o2.CodOrganismo)
							INNER JOIN mastpersonas mp21 ON (tnec2.CodPersona = mp21.CodPersona)
							INNER JOIN mastempleado me2 ON (mp21.CodPersona = me2.CodPersona)
							INNER JOIN mastpersonas mp22 ON (o2.CodPersona = mp22.CodPersona)
							INNER JOIN mastproveedores p2 ON (mp22.CodPersona = p2.CodProveedor)
							INNER JOIN pr_concepto c2 ON (tnec2.CodConcepto = c2.CodConcepto)
							INNER JOIN pr_conceptoperfil cp2 ON (tn2.CodPerfilConcepto = cp2.CodPerfilConcepto)
							INNER JOIN pr_conceptoperfildetalle cpd2 ON (cp2.CodPerfilConcepto = cpd2.CodPerfilConcepto AND
																		 tnec2.CodTipoProceso = cpd2.CodTipoProceso AND
																		 tnec2.CodConcepto = cpd2.CodConcepto)
						WHERE
							me2.CodTipoPago = '01' AND
							tnec2.CodTipoNom = '".$nomina."' AND
							tnec2.Periodo = '".$periodo."' AND
							tnec2.CodOrganismo = '".$organismo."' AND
							tnec2.CodTipoProceso = '".$proceso."' AND
							c2.Tipo = 'D' AND
							c2.FlagRetencion = 'S' $filtro_retencion
						GROUP BY tnec2.CodOrganismo";	fwrite($__archivo, $sql.";\n\n");
				$query_retencion = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_retencion) != 0) $field_retencion = mysql_fetch_array($query_retencion);
			}
			elseif ($field['Ficha'] == "02") {
				//	descuento
				$sql = "SELECT SUM(tnec2.Monto) AS MontoIngreso
						FROM
							pr_tiponominaempleadoconcepto tnec2
							INNER JOIN tiponomina tn2 ON (tnec2.CodTipoNom = tn2.CodTipoNom)
							INNER JOIN pr_tipoproceso tp2 ON (tnec2.CodTipoProceso = tp2.CodTipoProceso)
							INNER JOIN mastorganismos o2 ON (tnec2.CodOrganismo = o2.CodOrganismo)
							INNER JOIN mastpersonas mp21 ON (tnec2.CodPersona = mp21.CodPersona)
							INNER JOIN mastempleado me2 ON (mp21.CodPersona = me2.CodPersona)
							INNER JOIN mastpersonas mp22 ON (o2.CodPersona = mp22.CodPersona)
							INNER JOIN mastproveedores p2 ON (mp22.CodPersona = p2.CodProveedor)
							INNER JOIN pr_concepto c2 ON (tnec2.CodConcepto = c2.CodConcepto)
							INNER JOIN pr_conceptoperfil cp2 ON (tn2.CodPerfilConcepto = cp2.CodPerfilConcepto)
							INNER JOIN pr_conceptoperfildetalle cpd2 ON (cp2.CodPerfilConcepto = cpd2.CodPerfilConcepto AND
																		 tnec2.CodTipoProceso = cpd2.CodTipoProceso AND
																		 tnec2.CodConcepto = cpd2.CodConcepto)
						WHERE
							me2.CodTipoPago = '02' AND
							tnec2.CodTipoNom = '".$nomina."' AND
							tnec2.Periodo = '".$periodo."' AND
							tnec2.CodOrganismo = '".$organismo."' AND
							tnec2.CodTipoProceso = '".$proceso."' AND
							c2.Tipo = 'D' AND
							c2.FlagRetencion = 'N'
						GROUP BY tnec2.CodOrganismo";	fwrite($__archivo, $sql.";\n\n");
				$query_descuento = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_descuento) != 0) $field_descuento = mysql_fetch_array($query_descuento);
				
				//	retenciones
				$sql = "SELECT SUM(tnec2.Monto)
						FROM
							pr_tiponominaempleadoconcepto tnec2
							INNER JOIN tiponomina tn2 ON (tnec2.CodTipoNom = tn2.CodTipoNom)
							INNER JOIN pr_tipoproceso tp2 ON (tnec2.CodTipoProceso = tp2.CodTipoProceso)
							INNER JOIN mastorganismos o2 ON (tnec2.CodOrganismo = o2.CodOrganismo)
							INNER JOIN mastpersonas mp21 ON (tnec2.CodPersona = mp21.CodPersona)
							INNER JOIN mastempleado me2 ON (mp21.CodPersona = me2.CodPersona)
							INNER JOIN mastpersonas mp22 ON (o2.CodPersona = mp22.CodPersona)
							INNER JOIN mastproveedores p2 ON (mp22.CodPersona = p2.CodProveedor)
							INNER JOIN pr_concepto c2 ON (tnec2.CodConcepto = c2.CodConcepto)
							INNER JOIN pr_conceptoperfil cp2 ON (tn2.CodPerfilConcepto = cp2.CodPerfilConcepto)
							INNER JOIN pr_conceptoperfildetalle cpd2 ON (cp2.CodPerfilConcepto = cpd2.CodPerfilConcepto AND
																		 tnec2.CodTipoProceso = cpd2.CodTipoProceso AND
																		 tnec2.CodConcepto = cpd2.CodConcepto)
						WHERE
							me2.CodTipoPago = '02' AND
							tnec2.CodTipoNom = '".$nomina."' AND
							tnec2.Periodo = '".$periodo."' AND
							tnec2.CodOrganismo = '".$organismo."' AND
							tnec2.CodTipoProceso = '".$proceso."' AND
							c2.Tipo = 'D' AND
							c2.FlagRetencion = 'S'
						GROUP BY tnec2.CodOrganismo";	fwrite($__archivo, $sql.";\n\n");
				$query_retencion = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_retencion) != 0) $field_retencion = mysql_fetch_array($query_retencion);
			}
			//	obtengo algunos valores a insertar
			$nrodocumento = $organismo.$periodo_anio.$periodo_mes.$nomina.$proceso.$field['Ficha'];
			$nrocuenta = getCuentaBancariaDefault($organismo, $field['CodTipoPago']);
			$comentarios = "PERIODO $periodo NOMINA DE $field[Nomina] $field[NomProceso]";
			$monto_noafecto = $field['MontoIngreso'] - $field_descuento['MontoDescuento'];
			$monto_obligacion = $monto_noafecto - $field_retencion['MontoRetencion'];
			//	inserto la obligacion
			$sql = "INSERT INTO pr_obligaciones (
								TipoObligacion,
								CodOrganismo,
								CodTipoNom,
								Periodo,
								CodTipoProceso,
								CodProveedor,
								CodTipoDocumento,
								NroDocumento,
								NroCuenta,
								CodTipoPago,
								CodTipoServicio,
								FechaRegistro,
								CodProveedorPagar,
								NomProveedorPagar,
								Comentarios,
								ComentariosAdicional,
								MontoObligacion,
								MontoNoAfecto,
								MontoImpuestoOtros,
								CodCuenta,
								CodCentroCosto,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$field['Ficha']."',
								'".$organismo."',
								'".$nomina."',
								'".$periodo."',
								'".$proceso."',
								'".$field['CodProveedor']."',
								'".$field['CodTipoDocumento']."',
								'".$nrodocumento."',
								'".$nrocuenta."',
								'".$field['CodTipoPago']."',
								'".$field['CodTipoServicio']."',
								NOW(),
								'".$field['CodProveedor']."',
								'".$field['NomProveedor']."',
								'".$comentarios."',
								'".$comentarios."',
								'".$monto_obligacion."',
								'".$monto_noafecto."',
								'".abs(($monto_noafecto-$monto_obligacion))."',
								'".$field['CodCuenta']."',
								'".$_CCOSTOPR['CCOSTOPR']."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";	fwrite($__archivo, $sql.";\n\n");
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
		
		//	consulto las partidas a insertar
		if ($proceso != "BVC") {
			$sql = "(SELECT
						o.CodPersona AS CodProveedor,
						'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
						'01' AS Ficha,
						SUM(tnec.Monto) AS MontoIngreso,
						cpd.cod_partida,
						pv.CodCuenta,
						cpd.CuentaDebe,
						cpd.CuentaHaber
					FROM
						pr_tiponominaempleadoconcepto tnec
						INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
						INNER JOIN pr_tipoproceso tp ON (tnec.CodTipoProceso = tp.CodTipoProceso)
						INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
						INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
						INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
						INNER JOIN mastpersonas mp2 ON (o.CodPersona = mp2.CodPersona)
						INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)    
						INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
						INNER JOIN pr_conceptoperfil cp ON (tn.CodPerfilConcepto = cp.CodPerfilConcepto)
						INNER JOIN pr_conceptoperfildetalle cpd ON (cp.CodPerfilConcepto = cpd.CodPerfilConcepto AND
																	tnec.CodTipoProceso = cpd.CodTipoProceso AND
																	tnec.CodConcepto = cpd.CodConcepto)
						LEFT JOIN pv_partida pv ON (cpd.cod_partida = pv.cod_partida)
					WHERE
						me.CodTipoPago = '01' AND
						tnec.CodTipoNom = '".$nomina."' AND
						tnec.Periodo = '".$periodo."' AND
						tnec.CodOrganismo = '".$organismo."' AND
						tnec.CodTipoProceso = '".$proceso."' AND
						c.Tipo = 'I'
					GROUP BY o.CodPersona, cpd.cod_partida)
					UNION
					(SELECT
						mp1.CodPersona AS CodProveedor,
						'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
						'02' AS Ficha,
						SUM(tnec.Monto) AS MontoIngreso,
						cpd.cod_partida,
						pv.CodCuenta,
						cpd.CuentaDebe,
						cpd.CuentaHaber
					FROM
						pr_tiponominaempleadoconcepto tnec
						INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
						INNER JOIN pr_tipoproceso tp ON (tnec.CodTipoProceso = tp.CodTipoProceso)
						INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
						INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
						INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
						INNER JOIN mastpersonas mp2 ON (o.CodPersona = mp2.CodPersona)
						INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)    
						INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
						INNER JOIN pr_conceptoperfil cp ON (tn.CodPerfilConcepto = cp.CodPerfilConcepto)
						INNER JOIN pr_conceptoperfildetalle cpd ON (cp.CodPerfilConcepto = cpd.CodPerfilConcepto AND
																	tnec.CodTipoProceso = cpd.CodTipoProceso AND
																	tnec.CodConcepto = cpd.CodConcepto)
						LEFT JOIN pv_partida pv ON (cpd.cod_partida = pv.cod_partida)
					WHERE
						me.CodTipoPago = '02' AND
						tnec.CodTipoNom = '".$nomina."' AND
						tnec.Periodo = '".$periodo."' AND
						tnec.CodOrganismo = '".$organismo."' AND
						tnec.CodTipoProceso = '".$proceso."' AND
						c.Tipo = 'I'
					GROUP BY mp1.CodPersona, cpd.cod_partida)
					UNION
					(SELECT
						c.CodPersona AS CodProveedor,
						'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
						'03' AS Ficha,
						SUM(tnec.Monto) AS MontoIngreso,
						cpd.cod_partida,
						pv.CodCuenta,
						cpd.CuentaDebe,
						cpd.CuentaHaber
					 FROM
						pr_tiponominaempleadoconcepto tnec
						INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
						INNER JOIN pr_tipoproceso tp ON (tnec.CodTipoProceso = tp.CodTipoProceso)
						INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
						INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
						INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
						INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
						INNER JOIN pr_conceptoperfil cp ON (tn.CodPerfilConcepto = cp.CodPerfilConcepto)
						INNER JOIN pr_conceptoperfildetalle cpd ON (cp.CodPerfilConcepto = cpd.CodPerfilConcepto AND
																	tnec.CodTipoProceso = cpd.CodTipoProceso AND
																	tnec.CodConcepto = cpd.CodConcepto)
						INNER JOIN mastpersonas mp2 ON (c.CodPersona = mp2.CodPersona)
						INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)
						LEFT JOIN pv_partida pv ON (cpd.cod_partida = pv.cod_partida)
					 WHERE
						tnec.CodTipoNom = '".$nomina."' AND
						tnec.Periodo = '".$periodo."' AND
						tnec.CodOrganismo = '".$organismo."' AND
						tnec.CodTipoProceso = '".$proceso."' AND
						c.Tipo = 'A'
					GROUP BY c.CodPersona, cpd.cod_partida)
					UNION
					(SELECT
						rj.Demandante AS CodProveedor,
						'".$_TIPODOCCXP['TIPODOCCXP']."' AS CodTipoDocumento,
						'04' AS Ficha,
						SUM(tnec.Monto) AS MontoIngreso,
						cpd.cod_partida,
						pv.CodCuenta,
						cpd.CuentaDebe,
						cpd.CuentaHaber
					 FROM
						pr_tiponominaempleadoconcepto tnec
						INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
						INNER JOIN pr_tipoproceso tp ON (tnec.CodTipoProceso = tp.CodTipoProceso)
						INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
						INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
						INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
						INNER JOIN rh_retencionjudicial rj ON (tnec.CodPersona = rj.CodPersona AND
															   tnec.CodOrganismo = rj.CodOrganismo)
						INNER JOIN rh_retencionjudicialconceptos rjc ON (rj.CodRetencion = rjc.CodRetencion AND
																		 tnec.CodOrganismo = rjc.CodOrganismo AND
																		 tnec.CodConcepto = rjc.CodConcepto)
						INNER JOIN mastpersonas mp2 ON (rj.Demandante = mp2.CodPersona)
						INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)
						INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
						INNER JOIN pr_conceptoperfil cp ON (tn.CodPerfilConcepto = cp.CodPerfilConcepto)
						INNER JOIN pr_conceptoperfildetalle cpd ON (cp.CodPerfilConcepto = cpd.CodPerfilConcepto AND
																	tnec.CodTipoProceso = cpd.CodTipoProceso AND
																	tnec.CodConcepto = cpd.CodConcepto)
						LEFT JOIN pv_partida pv ON (cpd.cod_partida = pv.cod_partida)
					 WHERE
						tnec.CodTipoNom = '".$nomina."' AND
						tnec.Periodo = '".$periodo."' AND
						tnec.CodOrganismo = '".$organismo."' AND
						tnec.CodTipoProceso = '".$proceso."'
					GROUP BY rj.Demandante, cpd.cod_partida)";	fwrite($__archivo, $sql.";\n\n");
		} else {
			$sql = "(SELECT
						mp1.CodPersona AS CodProveedor,
						'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
						'01' AS Ficha,
						SUM(tnec.Monto) AS MontoIngreso,
						cpd.cod_partida,
						pv.CodCuenta,
						cpd.CuentaDebe,
						cpd.CuentaHaber
					FROM
						pr_tiponominaempleadoconcepto tnec
						INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
						INNER JOIN pr_tipoproceso tp ON (tnec.CodTipoProceso = tp.CodTipoProceso)
						INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
						INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
						INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
						INNER JOIN mastpersonas mp2 ON (o.CodPersona = mp2.CodPersona)
						INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)    
						INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
						INNER JOIN pr_conceptoperfil cp ON (tn.CodPerfilConcepto = cp.CodPerfilConcepto)
						INNER JOIN pr_conceptoperfildetalle cpd ON (cp.CodPerfilConcepto = cpd.CodPerfilConcepto AND
																	tnec.CodTipoProceso = cpd.CodTipoProceso AND
																	tnec.CodConcepto = cpd.CodConcepto)
						LEFT JOIN pv_partida pv ON (cpd.cod_partida = pv.cod_partida)
					WHERE
						me.CodTipoPago = '01' AND
						tnec.CodTipoNom = '".$nomina."' AND
						tnec.Periodo = '".$periodo."' AND
						tnec.CodOrganismo = '".$organismo."' AND
						tnec.CodTipoProceso = '".$proceso."' AND
						c.Tipo = 'I'
					GROUP BY mp1.CodPersona, cpd.cod_partida)
					UNION
					(SELECT
						mp1.CodPersona AS CodProveedor,
						'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
						'02' AS Ficha,
						SUM(tnec.Monto) AS MontoIngreso,
						cpd.cod_partida,
						pv.CodCuenta,
						cpd.CuentaDebe,
						cpd.CuentaHaber
					FROM
						pr_tiponominaempleadoconcepto tnec
						INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
						INNER JOIN pr_tipoproceso tp ON (tnec.CodTipoProceso = tp.CodTipoProceso)
						INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
						INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
						INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
						INNER JOIN mastpersonas mp2 ON (o.CodPersona = mp2.CodPersona)
						INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)    
						INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
						INNER JOIN pr_conceptoperfil cp ON (tn.CodPerfilConcepto = cp.CodPerfilConcepto)
						INNER JOIN pr_conceptoperfildetalle cpd ON (cp.CodPerfilConcepto = cpd.CodPerfilConcepto AND
																	tnec.CodTipoProceso = cpd.CodTipoProceso AND
																	tnec.CodConcepto = cpd.CodConcepto)
						LEFT JOIN pv_partida pv ON (cpd.cod_partida = pv.cod_partida)
					WHERE
						me.CodTipoPago = '02' AND
						tnec.CodTipoNom = '".$nomina."' AND
						tnec.Periodo = '".$periodo."' AND
						tnec.CodOrganismo = '".$organismo."' AND
						tnec.CodTipoProceso = '".$proceso."' AND
						c.Tipo = 'I'
					GROUP BY mp1.CodPersona, cpd.cod_partida)
					UNION
					(SELECT
						c.CodPersona AS CodProveedor,
						'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
						'03' AS Ficha,
						SUM(tnec.Monto) AS MontoIngreso,
						cpd.cod_partida,
						pv.CodCuenta,
						cpd.CuentaDebe,
						cpd.CuentaHaber
					 FROM
						pr_tiponominaempleadoconcepto tnec
						INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
						INNER JOIN pr_tipoproceso tp ON (tnec.CodTipoProceso = tp.CodTipoProceso)
						INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
						INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
						INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
						INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
						INNER JOIN pr_conceptoperfil cp ON (tn.CodPerfilConcepto = cp.CodPerfilConcepto)
						INNER JOIN pr_conceptoperfildetalle cpd ON (cp.CodPerfilConcepto = cpd.CodPerfilConcepto AND
																	tnec.CodTipoProceso = cpd.CodTipoProceso AND
																	tnec.CodConcepto = cpd.CodConcepto)
						INNER JOIN mastpersonas mp2 ON (c.CodPersona = mp2.CodPersona)
						INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)
						LEFT JOIN pv_partida pv ON (cpd.cod_partida = pv.cod_partida)
					 WHERE
						tnec.CodTipoNom = '".$nomina."' AND
						tnec.Periodo = '".$periodo."' AND
						tnec.CodOrganismo = '".$organismo."' AND
						tnec.CodTipoProceso = '".$proceso."' AND
						c.Tipo = 'A'
					GROUP BY c.CodPersona, cpd.cod_partida)
					UNION
					(SELECT
						rj.Demandante AS CodProveedor,
						'".$_TIPODOCCXP['TIPODOCCXP']."' AS CodTipoDocumento,
						'04' AS Ficha,
						SUM(tnec.Monto) AS MontoIngreso,
						cpd.cod_partida,
						pv.CodCuenta,
						cpd.CuentaDebe,
						cpd.CuentaHaber
					 FROM
						pr_tiponominaempleadoconcepto tnec
						INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
						INNER JOIN pr_tipoproceso tp ON (tnec.CodTipoProceso = tp.CodTipoProceso)
						INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
						INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
						INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
						INNER JOIN rh_retencionjudicial rj ON (tnec.CodPersona = rj.CodPersona AND
															   tnec.CodOrganismo = rj.CodOrganismo)
						INNER JOIN rh_retencionjudicialconceptos rjc ON (rj.CodRetencion = rjc.CodRetencion AND
																		 tnec.CodOrganismo = rjc.CodOrganismo AND
																		 tnec.CodConcepto = rjc.CodConcepto)
						INNER JOIN mastpersonas mp2 ON (rj.Demandante = mp2.CodPersona)
						INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)
						INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
						INNER JOIN pr_conceptoperfil cp ON (tn.CodPerfilConcepto = cp.CodPerfilConcepto)
						INNER JOIN pr_conceptoperfildetalle cpd ON (cp.CodPerfilConcepto = cpd.CodPerfilConcepto AND
																	tnec.CodTipoProceso = cpd.CodTipoProceso AND
																	tnec.CodConcepto = cpd.CodConcepto)
						LEFT JOIN pv_partida pv ON (cpd.cod_partida = pv.cod_partida)
					 WHERE
						tnec.CodTipoNom = '".$nomina."' AND
						tnec.Periodo = '".$periodo."' AND
						tnec.CodOrganismo = '".$organismo."' AND
						tnec.CodTipoProceso = '".$proceso."'
					GROUP BY rj.Demandante, cpd.cod_partida)";	fwrite($__archivo, $sql.";\n\n");
		}
		$query = mysql_query($sql) or die ($sql.mysql_error());	$linea=0;
		while ($field = mysql_fetch_array($query)) {	$linea++;
			$field_descuento['MontoDescuento'] = 0.00;
			$field_adelanto['MontoAdelanto'] = 0.00;
			$field_retencion['MontoRetencion'] = 0.00;
			//	obtengo retenciones y descuentos
			if ($field['Ficha'] == "01") {
				//	descuento
				$sql = "SELECT SUM(tnec2.Monto) AS MontoDescuento
						FROM
							pr_tiponominaempleadoconcepto tnec2
							INNER JOIN tiponomina tn2 ON (tnec2.CodTipoNom = tn2.CodTipoNom)
							INNER JOIN pr_tipoproceso tp2 ON (tnec2.CodTipoProceso = tp2.CodTipoProceso)
							INNER JOIN mastorganismos o2 ON (tnec2.CodOrganismo = o2.CodOrganismo)
							INNER JOIN mastpersonas mp21 ON (tnec2.CodPersona = mp21.CodPersona)
							INNER JOIN mastempleado me2 ON (mp21.CodPersona = me2.CodPersona)
							INNER JOIN mastpersonas mp22 ON (o2.CodPersona = mp22.CodPersona)
							INNER JOIN mastproveedores p2 ON (mp22.CodPersona = p2.CodProveedor)
							INNER JOIN pr_concepto c2 ON (tnec2.CodConcepto = c2.CodConcepto)
							INNER JOIN pr_conceptoperfil cp2 ON (tn2.CodPerfilConcepto = cp2.CodPerfilConcepto)
							INNER JOIN pr_conceptoperfildetalle cpd2 ON (cp2.CodPerfilConcepto = cpd2.CodPerfilConcepto AND
																		 tnec2.CodTipoProceso = cpd2.CodTipoProceso AND
																		 tnec2.CodConcepto = cpd2.CodConcepto)
						WHERE
							me2.CodTipoPago = '01' AND
							tnec2.CodTipoNom = '".$nomina."' AND
							tnec2.Periodo = '".$periodo."' AND
							tnec2.CodOrganismo = '".$organismo."' AND
							tnec2.CodTipoProceso = '".$proceso."' AND
							c2.Tipo = 'D' AND
							c2.FlagRetencion = 'N' AND
							cpd2.cod_partida = '".$field['cod_partida']."'
						GROUP BY tnec2.CodOrganismo";	fwrite($__archivo, $sql.";\n\n");
				$query_descuento = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_descuento) != 0) $field_descuento = mysql_fetch_array($query_descuento);
				
				//	si el proceso es fin de mes
				if ($proceso == "FIN") {
					$sql = "SELECT SUM(tnec.Monto) AS MontoAdelanto
							FROM
								pr_tiponominaempleadoconcepto tnec
								INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
								INNER JOIN pr_tipoproceso tp ON (tnec.CodTipoProceso = tp.CodTipoProceso)
								INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
								INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
								INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
								INNER JOIN mastpersonas mp2 ON (o.CodPersona = mp2.CodPersona)
								INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)    
								INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
								INNER JOIN pr_conceptoperfil cp ON (tn.CodPerfilConcepto = cp.CodPerfilConcepto)
								INNER JOIN pr_conceptoperfildetalle cpd ON (cp.CodPerfilConcepto = cpd.CodPerfilConcepto AND
																			tnec.CodTipoProceso = cpd.CodTipoProceso AND
																			tnec.CodConcepto = cpd.CodConcepto)
								LEFT JOIN pv_partida pv ON (cpd.cod_partida = pv.cod_partida)
							WHERE
								me.CodTipoPago = '01' AND
								tnec.CodTipoNom = '".$nomina."' AND
								tnec.Periodo = '".$periodo."' AND
								tnec.CodOrganismo = '".$organismo."' AND
								tnec.CodTipoProceso = 'ADE' AND
								c.Tipo = 'I' AND
								cpd.cod_partida = '".$field['cod_partida']."'
							GROUP BY o.CodPersona, cpd.cod_partida";	fwrite($__archivo, $sql.";\n\n");
					$query_adelanto = mysql_query($sql) or die ($sql.mysql_error());
					if (mysql_num_rows($query_adelanto) != 0) $field_adelanto = mysql_fetch_array($query_adelanto);
				}
			}
			elseif ($field['Ficha'] == "02") {
				//	descuento
				$sql = "SELECT SUM(tnec2.Monto) AS MontoDescuento
						FROM
							pr_tiponominaempleadoconcepto tnec2
							INNER JOIN tiponomina tn2 ON (tnec2.CodTipoNom = tn2.CodTipoNom)
							INNER JOIN pr_tipoproceso tp2 ON (tnec2.CodTipoProceso = tp2.CodTipoProceso)
							INNER JOIN mastorganismos o2 ON (tnec2.CodOrganismo = o2.CodOrganismo)
							INNER JOIN mastpersonas mp21 ON (tnec2.CodPersona = mp21.CodPersona)
							INNER JOIN mastempleado me2 ON (mp21.CodPersona = me2.CodPersona)
							INNER JOIN mastpersonas mp22 ON (o2.CodPersona = mp22.CodPersona)
							INNER JOIN mastproveedores p2 ON (mp22.CodPersona = p2.CodProveedor)
							INNER JOIN pr_concepto c2 ON (tnec2.CodConcepto = c2.CodConcepto)
							INNER JOIN pr_conceptoperfil cp2 ON (tn2.CodPerfilConcepto = cp2.CodPerfilConcepto)
							INNER JOIN pr_conceptoperfildetalle cpd2 ON (cp2.CodPerfilConcepto = cpd2.CodPerfilConcepto AND
																		 tnec2.CodTipoProceso = cpd2.CodTipoProceso AND
																		 tnec2.CodConcepto = cpd2.CodConcepto)
						WHERE
							me2.CodTipoPago = '02' AND
							tnec2.CodTipoNom = '".$nomina."' AND
							tnec2.Periodo = '".$periodo."' AND
							tnec2.CodOrganismo = '".$organismo."' AND
							tnec2.CodTipoProceso = '".$proceso."' AND
							c2.Tipo = 'D' AND
							c2.FlagRetencion = 'N' AND
							cpd2.cod_partida = '".$field['cod_partida']."'
						GROUP BY tnec2.CodOrganismo";	fwrite($__archivo, $sql.";\n\n");
				$query_descuento = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_descuento) != 0) $field_descuento = mysql_fetch_array($query_descuento);
				
				//	si el proceso es fin de mes
				if ($proceso == "FIN") {
					$sql = "SELECT SUM(tnec.Monto) AS MontoAdelanto
							FROM
								pr_tiponominaempleadoconcepto tnec
								INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
								INNER JOIN pr_tipoproceso tp ON (tnec.CodTipoProceso = tp.CodTipoProceso)
								INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
								INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
								INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
								INNER JOIN mastpersonas mp2 ON (o.CodPersona = mp2.CodPersona)
								INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)    
								INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
								INNER JOIN pr_conceptoperfil cp ON (tn.CodPerfilConcepto = cp.CodPerfilConcepto)
								INNER JOIN pr_conceptoperfildetalle cpd ON (cp.CodPerfilConcepto = cpd.CodPerfilConcepto AND
																			tnec.CodTipoProceso = cpd.CodTipoProceso AND
																			tnec.CodConcepto = cpd.CodConcepto)
								LEFT JOIN pv_partida pv ON (cpd.cod_partida = pv.cod_partida)
							WHERE
								me.CodTipoPago = '02' AND
								tnec.CodTipoNom = '".$nomina."' AND
								tnec.Periodo = '".$periodo."' AND
								tnec.CodOrganismo = '".$organismo."' AND
								tnec.CodTipoProceso = 'ADE' AND
								c.Tipo = 'I' AND
								cpd.cod_partida = '".$field['cod_partida']."'
							GROUP BY mp1.CodPersona, cpd.cod_partida";	fwrite($__archivo, $sql.";\n\n");
					$query_adelanto = mysql_query($sql) or die ($sql.mysql_error());
					if (mysql_num_rows($query_adelanto) != 0) $field_adelanto = mysql_fetch_array($query_adelanto);
				}
			}
			else {
				$field_descuento['MontoDescuento'] = 0.00;
				$field_adelanto['MontoAdelanto'] = 0.00;
				$field_retencion['MontoRetencion'] = 0.00;
			}
			//	valido las cuentas
			if ($field['Ficha'] == "04") {
				if ($field['CuentaDebe'] != "") $codcuenta = $field['CuentaDebe'];
				else $codcuenta = $field['CuentaHaber'];
			} else {
				$codcuenta = $field['CodCuenta'];
			}
			
			//	montos
			$idpartida = $field['cod_partida'];
			$nrodocumento = $organismo.$periodo_anio.$periodo_mes.$nomina.$proceso.$field['Ficha'];
			$monto_partida = floatval($field['MontoIngreso']) - floatval($field_descuento['MontoDescuento']) - floatval($field_adelanto['MontoAdelanto']);
			//	inserto la cuenta
			$sql = "INSERT INTO pr_obligacionescuenta (
								CodProveedor,
								CodTipoDocumento,
								NroDocumento,
								Linea,
								Descripcion,
								Monto,
								CodCentroCosto,
								CodCuenta,
								cod_partida,
								FlagNoAfectoIGV,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$field['CodProveedor']."',
								'".$field['CodTipoDocumento']."',
								'".$nrodocumento."',
								'".$linea."',
								'".$field['Descripcion']."',
								'".$monto_partida."',
								'".$_CCOSTOPR['CCOSTOPR']."',
								'".$codcuenta."',
								'".$field['cod_partida']."',
								'N',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";	fwrite($__archivo, $sql.";\n\n");
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
		
		//	consulto las retenciones a insertar
		if ($proceso != "BVC") {
			$sql = "(SELECT
						mp2.CodPersona AS CodProveedor,
						'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
						'01' AS Ficha,
						SUM(tnec.Monto) AS MontoImpuesto,
						c.CodConcepto AS CodRetencion,
						cpd.CuentaHaber AS CodCuenta
					 FROM
						pr_tiponominaempleadoconcepto tnec
						INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
						INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
						INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
						INNER JOIN mastpersonas mp2 ON (o.CodPersona = mp2.CodPersona)
						INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)
						INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
						--	
						INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
						INNER JOIN pr_conceptoperfil cp ON (tn.CodPerfilConcepto = cp.CodPerfilConcepto)
						INNER JOIN pr_conceptoperfildetalle cpd ON (cp.CodPerfilConcepto = cpd.CodPerfilConcepto AND
																	tnec.CodTipoProceso = cpd.CodTipoProceso AND
																	tnec.CodConcepto = cpd.CodConcepto)
					 WHERE
						me.CodTipoPago = '01' AND
						tnec.CodTipoNom = '".$nomina."' AND
						tnec.Periodo = '".$periodo."' AND
						tnec.CodOrganismo = '".$organismo."' AND
						tnec.CodTipoProceso = '".$proceso."' AND
						c.Tipo = 'D' AND
						c.FlagRetencion = 'S'
					 GROUP BY tnec.CodOrganismo, CodRetencion)
					UNION
					(SELECT
						mp1.CodPersona AS CodProveedor,
						'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
						'02' AS Ficha,
						SUM(tnec.Monto) AS MontoImpuesto,
						c.CodConcepto AS CodRetencion,
						cpd.CuentaHaber AS CodCuenta
					 FROM
						pr_tiponominaempleadoconcepto tnec
						INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
						INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
						INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
						INNER JOIN mastpersonas mp2 ON (o.CodPersona = mp2.CodPersona)
						INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)
						INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
						--	
						INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
						INNER JOIN pr_conceptoperfil cp ON (tn.CodPerfilConcepto = cp.CodPerfilConcepto)
						INNER JOIN pr_conceptoperfildetalle cpd ON (cp.CodPerfilConcepto = cpd.CodPerfilConcepto AND
																	tnec.CodTipoProceso = cpd.CodTipoProceso AND
																	tnec.CodConcepto = cpd.CodConcepto)
					 WHERE
						me.CodTipoPago = '02' AND
						tnec.CodTipoNom = '".$nomina."' AND
						tnec.Periodo = '".$periodo."' AND
						tnec.CodOrganismo = '".$organismo."' AND
						tnec.CodTipoProceso = '".$proceso."' AND
						c.Tipo = 'D' AND
						c.FlagRetencion = 'S'
					 GROUP BY tnec.CodOrganismo, CodRetencion)
					 ORDER BY Ficha";	fwrite($__archivo, $sql.";\n\n");
		} else {
			$sql = "(SELECT
						mp1.CodPersona AS CodProveedor,
						'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
						'01' AS Ficha,
						SUM(tnec.Monto) AS MontoImpuesto,
						c.CodConcepto AS CodRetencion,
						cpd.CuentaHaber AS CodCuenta
					 FROM
						pr_tiponominaempleadoconcepto tnec
						INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
						INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
						INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
						INNER JOIN mastpersonas mp2 ON (o.CodPersona = mp2.CodPersona)
						INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)
						INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
						--	
						INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
						INNER JOIN pr_conceptoperfil cp ON (tn.CodPerfilConcepto = cp.CodPerfilConcepto)
						INNER JOIN pr_conceptoperfildetalle cpd ON (cp.CodPerfilConcepto = cpd.CodPerfilConcepto AND
																	tnec.CodTipoProceso = cpd.CodTipoProceso AND
																	tnec.CodConcepto = cpd.CodConcepto)
					 WHERE
						me.CodTipoPago = '01' AND
						tnec.CodTipoNom = '".$nomina."' AND
						tnec.Periodo = '".$periodo."' AND
						tnec.CodOrganismo = '".$organismo."' AND
						tnec.CodTipoProceso = '".$proceso."' AND
						c.Tipo = 'D' AND
						c.FlagRetencion = 'S'
					 GROUP BY mp1.CodPersona, CodRetencion)
					UNION
					(SELECT
						mp1.CodPersona AS CodProveedor,
						'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
						'02' AS Ficha,
						SUM(tnec.Monto) AS MontoImpuesto,
						c.CodConcepto AS CodRetencion,
						cpd.CuentaHaber AS CodCuenta
					 FROM
						pr_tiponominaempleadoconcepto tnec
						INNER JOIN mastorganismos o ON (tnec.CodOrganismo = o.CodOrganismo)
						INNER JOIN mastpersonas mp1 ON (tnec.CodPersona = mp1.CodPersona)
						INNER JOIN mastempleado me ON (mp1.CodPersona = me.CodPersona)
						INNER JOIN mastpersonas mp2 ON (o.CodPersona = mp2.CodPersona)
						INNER JOIN mastproveedores p ON (mp2.CodPersona = p.CodProveedor)
						INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
						--	
						INNER JOIN tiponomina tn ON (tnec.CodTipoNom = tn.CodTipoNom)
						INNER JOIN pr_conceptoperfil cp ON (tn.CodPerfilConcepto = cp.CodPerfilConcepto)
						INNER JOIN pr_conceptoperfildetalle cpd ON (cp.CodPerfilConcepto = cpd.CodPerfilConcepto AND
																	tnec.CodTipoProceso = cpd.CodTipoProceso AND
																	tnec.CodConcepto = cpd.CodConcepto)
					 WHERE
						me.CodTipoPago = '02' AND
						tnec.CodTipoNom = '".$nomina."' AND
						tnec.Periodo = '".$periodo."' AND
						tnec.CodOrganismo = '".$organismo."' AND
						tnec.CodTipoProceso = '".$proceso."' AND
						c.Tipo = 'D' AND
						c.FlagRetencion = 'S'
					 GROUP BY tnec.CodOrganismo, CodRetencion)
					 ORDER BY Ficha";	fwrite($__archivo, $sql.";\n\n");
		}
		$query = mysql_query($sql) or die ($sql.mysql_error());	$linea=0;
		while ($field = mysql_fetch_array($query)) {	$linea++;
			$nrodocumento = $organismo.$periodo_anio.$periodo_mes.$nomina.$proceso.$field['Ficha'];
			//	inserto las retenciones
			$sql = "INSERT INTO pr_obligacionesretenciones (
								CodProveedor,
								CodTipoDocumento,
								NroDocumento,
								Linea,
								CodConcepto,
								MontoImpuesto,
								MontoAfecto,
								CodCuenta,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$field['CodProveedor']."',
								'".$field['CodTipoDocumento']."',
								'".$nrodocumento."',
								'".$linea."',
								'".$field['CodRetencion']."',
								'".$field['MontoImpuesto']."',
								'".$field['MontoAfecto']."',
								'".$field['CodCuenta']."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";	fwrite($__archivo, $sql.";\n\n");
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
		mysql_query("COMMIT");
	}
	
	//	generar obligaciones
	elseif ($accion == "generarObligaciones") {
		list($periodo_anio, $periodo_mes) = split('[-]', $periodo);
				
		if ($detalles_bancos != "") {
			$registro = split(";", $detalles_bancos);
			foreach ($registro as $obligacion) {
				list($codproveedor, $codtipodocumento, $nrodocumento) = SPLIT( '[.]', $obligacion);
				validarObligacion($codproveedor, $codtipodocumento, $nrodocumento, $periodo_anio, $organismo);
			}
		}
		
		if ($detalles_cheques != "") {
			$registro = split(";", $detalles_cheques);
			foreach ($registro as $obligacion) {
				list($codproveedor, $codtipodocumento, $nrodocumento) = SPLIT( '[.]', $obligacion);
				validarObligacion($codproveedor, $codtipodocumento, $nrodocumento, $periodo_anio, $organismo);
			}
		}
		
		if ($detalles_terceros != "") {
			$registro = split(";", $detalles_terceros);
			foreach ($registro as $obligacion) {
				list($codproveedor, $codtipodocumento, $nrodocumento) = SPLIT( '[.]', $obligacion);
				validarObligacion($codproveedor, $codtipodocumento, $nrodocumento, $periodo_anio, $organismo);
			}
		}
		
		if ($detalles_judiciales != "") {
			$registro = split(";", $detalles_judiciales);
			foreach ($registro as $obligacion) {
				list($codproveedor, $codtipodocumento, $nrodocumento) = SPLIT( '[.]', $obligacion);
				validarObligacion($codproveedor, $codtipodocumento, $nrodocumento, $periodo_anio, $organismo);
			}
		}
		
		if ($detalles_bancos != "") {
			$registro = split(";", $detalles_bancos);
			foreach ($registro as $obligacion) {
				list($codproveedor, $codtipodocumento, $nrodocumento) = SPLIT( '[.]', $obligacion);
				generarObligacion($codproveedor, $codtipodocumento, $nrodocumento, $organismo, $periodo, $nomina, $proceso);
			}
		}
		
		if ($detalles_cheques != "") {
			$registro = split(";", $detalles_cheques);
			foreach ($registro as $obligacion) {
				list($codproveedor, $codtipodocumento, $nrodocumento) = SPLIT( '[.]', $obligacion);
				generarObligacion($codproveedor, $codtipodocumento, $nrodocumento, $organismo, $periodo, $nomina, $proceso);
			}
		}
		
		if ($detalles_terceros != "") {
			$registro = split(";", $detalles_terceros);
			foreach ($registro as $obligacion) {
				list($codproveedor, $codtipodocumento, $nrodocumento) = SPLIT( '[.]', $obligacion);
				generarObligacion($codproveedor, $codtipodocumento, $nrodocumento, $organismo, $periodo, $nomina, $proceso);
			}
		}
		
		if ($detalles_judiciales != "") {
			$registro = split(";", $detalles_judiciales);
			foreach ($registro as $obligacion) {
				list($codproveedor, $codtipodocumento, $nrodocumento) = SPLIT( '[.]', $obligacion);
				generarObligacion($codproveedor, $codtipodocumento, $nrodocumento, $organismo, $periodo, $nomina, $proceso);
			}
		}
	}
	
	//	consolidar obligacion
	elseif ($accion == "consolidarObligacion") {
		list($periodo_anio, $periodo_mes) = split('[-]', $periodo);
		//	consulto el proveedor del organismo
		$sql = "SELECT
					o.CodPersona,
					p.NomCompleto AS NomPersona
				FROM
					mastorganismos o
					INNER JOIN mastpersonas p ON (o.CodPersona = p.CodPersona)
				WHERE o.CodOrganismo = '".$organismo."'";
		$query_proveedor = mysql_query($sql) or die($sql.mysql_error());
		if (mysql_num_rows($query_proveedor) != 0) $field_proveedor = mysql_fetch_array($query_proveedor);
		else die("¡ERROR: No se encontró un proveedor para asociar la obligación!");
		
		if ($detalles_bancos != "") {
			$i = 0;
			$filtro = "";
			$registro = split(";", $detalles_bancos);
			foreach ($registro as $obligacion) {	$i++;
				list($codpersona, $codtipodocumento, $nrodocumento, $secuencia, $ficha) = SPLIT( '[.]', $obligacion);
				
				if ($i == 1) {
					$ficha_comparar = $ficha;
				} else {
					$filtro .= " OR ";
				}
				if ($ficha_comparar != $ficha) die("¡ERROR: No puede seleccionar obligaciones de diferentes tipos de pago!");
			
				$filtro .= "(CodProveedor = '".$codpersona."' AND
							 CodTipoDocumento = '".$codtipodocumento."' AND
							 NroDocumento = '".$nrodocumento."')";
			}
			
			//	consulto la tabla general
			$sql = "SELECT
						TipoObligacion,
						CodOrganismo,
						CodTipoNom,
						Periodo,
						CodTipoProceso,
						CodTipoDocumento,
						NroDocumento,
						CodTipoPago,
						CodTipoServicio,
						CodProveedorPagar,
						NomProveedorPagar,
						Comentarios,
						ComentariosAdicional,
						CodCuenta,
						CodCentroCosto,
						SUM(MontoObligacion) AS MontoObligacion,
						SUM(MontoImpuestoOtros) AS MontoImpuestoOtros,
						SUM(MontoNoAfecto) AS MontoNoAfecto
					FROM pr_obligaciones
					WHERE $filtro
					GROUP BY CodTipoDocumento, NroDocumento";
			$query = mysql_query($sql) or die($sql.mysql_error());
			while($field = mysql_fetch_array($query)) {
				//	elimino las seleccionadas
				$sql = "DELETE FROM pr_obligaciones WHERE $filtro";
				$query_delete = mysql_query($sql) or die($sql.mysql_error());
				
				//	consulto el numero de obligaciones que he consolidado
				$sql = "SELECT *
						FROM pr_obligaciones
						WHERE
							CodProveedor = '".$field_proveedor['CodPersona']."' AND
							CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
							NroDocumento LIKE '".$field['NroDocumento']."-%'";
				$query_numero = mysql_query($sql) or die($sql.mysql_error());
				$rows = intval(mysql_num_rows($query_numero));	$rows++;
				
				//	obtengo algunos valores a insertar
				$nrodocumento = $field['NroDocumento']."-".$rows;
				$nrocuenta = getCuentaBancariaDefault($organismo, $field['CodTipoPago']);
				//	inserto la obligacion
				$sql = "INSERT INTO pr_obligaciones (
									TipoObligacion,
									CodOrganismo,
									CodTipoNom,
									Periodo,
									CodTipoProceso,
									CodProveedor,
									CodTipoDocumento,
									NroDocumento,
									NroCuenta,
									CodTipoPago,
									CodTipoServicio,
									FechaRegistro,
									CodProveedorPagar,
									NomProveedorPagar,
									Comentarios,
									ComentariosAdicional,
									MontoObligacion,
									MontoNoAfecto,
									MontoImpuestoOtros,
									CodCuenta,
									CodCentroCosto,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$field['TipoObligacion']."',
									'".$field['CodOrganismo']."',
									'".$field['CodTipoNom']."',
									'".$field['Periodo']."',
									'".$field['CodTipoProceso']."',
									'".$field_proveedor['CodPersona']."',
									'".$field['CodTipoDocumento']."',
									'".$nrodocumento."',
									'".$nrocuenta."',
									'".$field['CodTipoPago']."',
									'".$field['CodTipoServicio']."',
									NOW(),
									'".$field_proveedor['CodPersona']."',
									'".$field_proveedor['NomPersona']."',
									'".$field['Comentarios']."',
									'".$field['ComentariosAdicional']."',
									'".$field['MontoObligacion']."',
									'".$field['MontoNoAfecto']."',
									'".$field['MontoImpuestoOtros']."',
									'".$field['CodCuenta']."',
									'".$field['CodCentroCosto']."',
									'".$_SESSION['USUARIO_ACTUAL']."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die($sql.mysql_error());
			}
			
			//	consulto las cuentas
			$sql = "SELECT
						CodTipoDocumento,
						NroDocumento,
						CodCentroCosto,
						CodCuenta,
						cod_partida,
						FlagNoAfectoIGV,
						SUM(Monto) AS Monto
					FROM pr_obligacionescuenta
					WHERE $filtro
					GROUP BY CodTipoDocumento, NroDocumento";
			$query = mysql_query($sql) or die($sql.mysql_error());	$i=0;
			while($field = mysql_fetch_array($query)) {	$i++;
				//	elimino las seleccionadas
				$sql = "DELETE FROM pr_obligacionescuenta WHERE $filtro";
				$query_delete = mysql_query($sql) or die($sql.mysql_error());
				
				//	inserto la obligacion x cuenta
				$sql = "INSERT INTO pr_obligacionescuenta (
									CodProveedor,
									CodTipoDocumento,
									NroDocumento,
									Linea,
									CodCentroCosto,
									CodCuenta,
									cod_partida,
									FlagNoAfectoIGV,
									Monto,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$field_proveedor['CodPersona']."',
									'".$field['CodTipoDocumento']."',
									'".$nrodocumento."',
									'".$i."',
									'".$field['CodCentroCosto']."',
									'".$field['CodCuenta']."',
									'".$field['cod_partida']."',
									'".$field['FlagNoAfectoIGV']."',
									'".$field['Monto']."',
									'".$_SESSION['USUARIO_ACTUAL']."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die($sql.mysql_error());
			}
			
			//	consulto las retenciones
			$sql = "SELECT
						CodTipoDocumento,
						NroDocumento,
						CodConcepto,
						SUM(MontoImpuesto) AS MontoImpuesto,
						SUM(MontoAfecto) AS MontoAfecto,
						CodCuenta,
						FlagProvision
					FROM pr_obligacionesretenciones
					WHERE $filtro
					GROUP BY CodTipoDocumento, NroDocumento";
			$query = mysql_query($sql) or die($sql.mysql_error());	$i=0;
			while($field = mysql_fetch_array($query)) {	$i++;
				//	elimino las seleccionadas
				$sql = "DELETE FROM pr_obligacionesretenciones WHERE $filtro";
				$query_delete = mysql_query($sql) or die($sql.mysql_error());
				
				if ($field['MontoImpuesto'] > 0) {
					//	inserto la obligacion retenciones
					$sql = "INSERT INTO pr_obligacionesretenciones (
										CodProveedor,
										CodTipoDocumento,
										NroDocumento,
										Linea,
										CodConcepto,
										MontoImpuesto,
										MontoAfecto,
										CodCuenta,
										FlagProvision,
										UltimoUsuario,
										UltimaFecha
							) VALUES (
										'".$field_proveedor['CodPersona']."',
										'".$field['CodTipoDocumento']."',
										'".$nrodocumento."',
										'".$i."',
										'".$field['CodConcepto']."',
										'".$field['MontoImpuesto']."',
										'".$field['MontoAfecto']."',
										'".$field['CodCuenta']."',
										'".$field['FlagProvision']."',
										'".$_SESSION['USUARIO_ACTUAL']."',
										NOW()
							)";
					$query_insert = mysql_query($sql) or die($sql.mysql_error());
				}
			}
		}
	}
}

elseif ($modulo == "VOUCHER") {
	$nrovoucher = getCodigo_2("ac_vouchermast", "NroVoucher", "CodVoucher", $codvoucher, 4);
	$nrointerno = getCodigo("ac_vouchermast", "NroInterno", 10);
	$codigo = "$codvoucher-$nrovoucher";
	$lineas = 0;
	$creditos = 0;
	$debitos = 0;
			
	//	inserto el voucher
	$sql = "INSERT INTO ac_vouchermast (
						CodOrganismo,
						Periodo,
						Voucher,
						Prefijo,
						NroVoucher,
						CodVoucher,
						CodSistemaFuente,
						PreparadoPor,
						FechaPreparacion,
						AprobadoPor,
						FechaAprobacion,
						TituloVoucher,
						ComentariosVoucher,
						FechaVoucher,
						NroInterno,
						Estado,
						UltimoUsuario,
						UltimaFecha
			) VALUES (
						'".$organismo."',
						NOW(),
						'".$codigo."',
						'".$codvoucher."',
						'".$nrovoucher."',
						'".$codvoucher."',
						'".$codsistemafuente."',
						'".$_SESSION['CODPERSONA_ACTUAL']."',
						NOW(),
						'".$_SESSION['CODPERSONA_ACTUAL']."',
						NOW(),
						'".($descripcion)."',
						'".($descripcion)."',
						NOW(),
						'".$nrointerno."',
						'MA',
						'".$_SESSION["USUARIO_ACTUAL"]."',
						NOW())";
	$query_insert = mysql_query($sql) or die ($sql.mysql_error());
	
	//	detalles del voucher
	if ($detalles != "") {
		$detalle = split(";", $detalles);	$lineas = 0;
		foreach ($detalle as $registro) {	$lineas++;
			list($codcuenta, $monto, $comentarios, $tiposaldo) = SPLIT( '[|]', $registro);				
			if ($tiposaldo == "A") $creditos += $monto;
			else $debitos += $monto;
			
			//	inserto los detalles del voucher
			$sql = "INSERT INTO ac_voucherdet (
								CodOrganismo,
								Periodo,
								Voucher,
								Linea,
								CodCuenta,
								MontoVoucher,
								FechaVoucher,
								Descripcion,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$organismo."',
								NOW(),
								'".$codigo."',
								'".$lineas."',
								'".$codcuenta."',
								'".$monto."',
								NOW(),
								'".$comentarios."',
								'PR',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW())";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			
			//	consulto si eciste la cuenta en balance
			$sql = "SELECT *
					FROM ac_voucherbalance
					WHERE
						CodCuenta = '".$codcuenta."' AND
						Periodo = '".$periodo."'";
			$query_balance = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_balance) != 0) {
				$field_balance = mysql_fetch_array($query_balance);
				//	actualizo balance
				$sql = "UPDATE ac_voucherbalance
						SET SaldoBalance = (SaldoBalance - $monto)
						WHERE
							CodCuenta = '".$codcuenta."' AND
							Periodo = '".$periodo."'";
				$query_update = mysql_query($sql) or die ($sql.mysql_error());
			} else {
				//	inserto balance
				$sql = "INSERT INTO ac_voucherbalance (
									CodOrganismo,
									Periodo,
									CodCuenta,
									SaldoBalance,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$organismo."',
									NOW(),
									'".$codcuenta."',
									'".$monto."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW())";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
	}
	
	//	actualizo los montos del voucher
	$sql = "UPDATE ac_vouchermast
			SET
				Lineas = '".$lineas."',
				Creditos = '".$creditos."',
				Debitos = '".$debitos."'
			WHERE
				CodOrganismo = '".$organismo."' AND
				Periodo = '".$periodo."' AND
				Voucher = '".$codigo."'";
	$query_update = mysql_query($sql) or die ($sql.mysql_error());
	
	//	actualizo periodo
	$sql = "UPDATE pr_procesoperiodo
			SET Voucher = '".$codigo."'
			WHERE
				CodOrganismo = '".$organismo."' AND
				CodTipoNom = '".$nomina."' AND
				Periodo = '".$periodo."' AND
				CodTipoProceso = '".$proceso."'";
	$query_update = mysql_query($sql) or die ($sql.mysql_error());
}

elseif ($modulo == "AJUSTE-SALARIAL") {
	connect();
	if ($accion == "INSERTAR") {
		//	consulto si ya existe un ajuste para el periodo
		$sql = "SELECT *
				FROM pr_ajustesalarial
				WHERE
					CodOrganismo = '".$codorganismo."' AND
					Periodo = '".$periodo."'";
		$query_consulta = mysql_query($sql) or die($sql.mysql_error());
		if (mysql_num_rows($query_consulta) != 0) die("¡ERROR: Ya existe un ajuste para este periodo!");
		
		//	inserto
		$secuencia = getSecuencia2("Secuencia", "CodOrganismo", "Periodo", "pr_ajustesalarial", $codorganismo, $periodo);
		$sql = "INSERT INTO pr_ajustesalarial (
							CodOrganismo,
							Periodo,
							Secuencia,
							Estado,
							Descripcion,
							NroResolucion,
							NroGaceta,
							PreparadoPor,
							FechaPreparacion,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$codorganismo."',
							'".$periodo."',
							'".$secuencia."',
							'".$estado."',
							'".($descripcion)."',
							'".$nroresolucion."',
							'".$nrogaceta."',
							'".$preparadopor."',
							'".formatFechaAMD($fpreparacion)."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die($sql.mysql_error());
		
		//	detalles
		if ($detalles != "") {
			$grados = split(";", $detalles);
			foreach ($grados as $grado) {
				list($codnivel, $porcentaje, $monto, $nuevo) = split("[|]", $grado);
				//	inserto
				$sql = "INSERT INTO pr_ajustesalarialajustes (
									CodOrganismo,
									Periodo,
									Secuencia,
									CodNivel,
									Descripcion,
									Porcentaje,
									Monto,
									SueldoPromedio,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$codorganismo."',
									'".$periodo."',
									'".$secuencia."',
									'".$codnivel."',
									(SELECT Descripcion FROM rh_nivelsalarial WHERE CodNivel = '".$codnivel."'),
									'".$porcentaje."',
									'".$monto."',
									'".$nuevo."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die($sql.mysql_error());
			}
		}
	}
	
	elseif ($accion == "ACTUALIZAR") {
		//	actualizo
		$sql = "UPDATE pr_ajustesalarial
				SET 
					Descripcion = '".($descripcion)."',
					NroResolucion = '".$nroresolucion."',
					NroGaceta = '".$nrogaceta."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$codorganismo."' AND
					Periodo = '".$periodo."' AND
					Secuencia = '".$secuencia."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		//	valido	
		if ($detalles != "") {
			//	elimino
			$sql = "DELETE FROM pr_ajustesalarialajustes
					WHERE
						CodOrganismo = '".$codorganismo."' AND
						Periodo = '".$periodo."' AND
						Secuencia = '".$secuencia."'";
			$query_delete = mysql_query($sql) or die($sql.mysql_error());
			//	detalles
			$grados = split(";", $detalles);
			foreach ($grados as $grado) {
				list($codnivel, $porcentaje, $monto, $nuevo) = SPLIT( '[|]', $grado);
				//	inserto
				$sql = "INSERT INTO pr_ajustesalarialajustes (
									CodOrganismo,
									Periodo,
									Secuencia,
									CodNivel,
									Descripcion,
									Porcentaje,
									Monto,
									SueldoPromedio,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$codorganismo."',
									'".$periodo."',
									'".$secuencia."',
									'".$codnivel."',
									(SELECT Descripcion FROM rh_nivelsalarial WHERE CodNivel = '".$codnivel."'),
									'".$porcentaje."',
									'".$monto."',
									'".$nuevo."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die($sql.mysql_error());
			}
		}
	}
	
	elseif ($accion == "ELIMINAR") {
		list($codorganismo, $periodo, $secuencia) = split("[.]", $registro);
		//	consulto el estado del registro
		$sql = "SELECT Estado
				FROM pr_ajustesalarial
				WHERE
					CodOrganismo = '".$codorganismo."' AND
					Periodo = '".$periodo."' AND
					Secuencia = '".$secuencia."' AND
					Estado = 'AP'";
		$query_consulta = mysql_query($sql) or die($sql.mysql_error());
		if (mysql_num_rows($query_consulta) != 0) die("¡ERROR: No se puede eliminar un ajuste en estado Aprobado!");
		
		//	elimino
		$sql = "DELETE FROM pr_ajustesalarial
				WHERE
					CodOrganismo = '".$codorganismo."' AND
					Periodo = '".$periodo."' AND
					Secuencia = '".$secuencia."'";
		$query_delete = mysql_query($sql) or die($sql.mysql_error());
		
		//	elimino
		$sql = "DELETE FROM pr_ajustesalarialajustes
				WHERE
					CodOrganismo = '".$codorganismo."' AND
					Periodo = '".$periodo."' AND
					Secuencia = '".$secuencia."'";
		$query_delete = mysql_query($sql) or die($sql.mysql_error());
	}
	
	elseif ($accion == "APROBAR") {
		//	consulto el estado del registro
		$sql = "SELECT Estado
				FROM pr_ajustesalarial
				WHERE
					CodOrganismo = '".$codorganismo."' AND
					Periodo = '".$periodo."' AND
					Secuencia = '".$secuencia."' AND
					Estado = 'AP'";
		$query_consulta = mysql_query($sql) or die($sql.mysql_error());
		if (mysql_num_rows($query_consulta) != 0) die("¡ERROR: No se puede aprobar un ajuste en estado Aprobado!");
		
		//	actualizo
		$sql = "UPDATE pr_ajustesalarial
				SET
					Estado = 'AP',
					AprobadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaAprobado = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$codorganismo."' AND
					Periodo = '".$periodo."' AND
					Secuencia = '".$secuencia."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		//	actualizo
		$sql = "UPDATE pr_ajustesalarialajustes
				SET
					Estado = 'AP',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$codorganismo."' AND
					Periodo = '".$periodo."' AND
					Secuencia = '".$secuencia."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		//	actualizo montos
		$sql = "SELECT *
				FROM pr_ajustesalarialajustes
				WHERE
					CodOrganismo = '".$codorganismo."' AND
					Periodo = '".$periodo."' AND
					Secuencia = '".$secuencia."'";
		$query = mysql_query($sql) or die($sql.mysql_error());
		while($field = mysql_fetch_array($query)) {
			//	actualizo
			$sql = "UPDATE rh_nivelsalarial
					SET
						SueldoMinimo = '".$field['SueldoPromedio']."',
						SueldoMaximo = '".$field['SueldoPromedio']."',
						SueldoPromedio = '".$field['SueldoPromedio']."'
					WHERE CodNivel = '".$field['CodNivel']."'";
			$query_update = mysql_query($sql) or die($sql.mysql_error());
						
			//	consulto
			$sql = "SELECT CategoriaCargo, Grado, SueldoPromedio
					FROM rh_nivelsalarial
					WHERE CodNivel = '".$field['CodNivel']."'";
			$query_nivel = mysql_query($sql) or die($sql.mysql_error());
			while($field_nivel = mysql_fetch_array($query_nivel)) {
				//	actualizo
				$sql = "UPDATE rh_puestos
						SET NivelSalarial = '".$field_nivel['SueldoPromedio']."'
						WHERE
							CategoriaCargo = '".$field_nivel['CategoriaCargo']."' AND
							Grado = '".$field_nivel['Grado']."'";
				$query_update = mysql_query($sql) or die($sql.mysql_error());
							
				//	consulto
				$sql = "SELECT CodCargo
						FROM rh_puestos
						WHERE
							CategoriaCargo = '".$field_nivel['CategoriaCargo']."' AND
							Grado = '".$field_nivel['Grado']."'";
				$query_puesto = mysql_query($sql) or die($sql.mysql_error());
				while($field_puesto = mysql_fetch_array($query_puesto)) {
					//	actualizo
					$sql = "UPDATE mastempleado
							SET
								SueldoAnterior = SueldoActual,
								SueldoActual = '".$field_puesto['SueldoPromedio']."'
							WHERE
								CodCargo = '".$field_puesto['CodCargo']."' AND
								Estado = 'A'";
					$query_update = mysql_query($sql) or die($sql.mysql_error());
				}
			}
		}
	}
	
	elseif ($accion == "ANULAR") {
		//	consulto el estado del registro
		$sql = "SELECT Estado
				FROM pr_ajustesalarial
				WHERE
					CodOrganismo = '".$codorganismo."' AND
					Periodo = '".$periodo."' AND
					Secuencia = '".$secuencia."' AND
					(Estado = 'AP' OR Estado = 'AN')";
		$query_consulta = mysql_query($sql) or die($sql.mysql_error());
		if (mysql_num_rows($query_consulta) != 0) {
			$field_consulta = mysql_fetch_array($query_consulta);
			if ($field_consulta['Estado'] == "AP") die("¡ERROR: No se puede anular un ajuste en estado Aprobado!");
			elseif ($field_consulta['Estado'] == "AP") die("¡ERROR: No se puede anular un ajuste en estado Anulado!");
		}
		
		//	actualizo
		$sql = "UPDATE pr_ajustesalarial
				SET
					Estado = 'AN',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$codorganismo."' AND
					Periodo = '".$periodo."' AND
					Secuencia = '".$secuencia."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		//	actualizo
		$sql = "UPDATE pr_ajustesalarialajustes
				SET
					Estado = 'AN',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$codorganismo."' AND
					Periodo = '".$periodo."' AND
					Secuencia = '".$secuencia."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
	}
}
?>
