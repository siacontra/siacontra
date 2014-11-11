<?php
session_start();
include("../../lib/fphp.php");
include("fphp.php");
	$__archivo = fopen("query.sql", "w+");
//	fwrite($__archivo, $sql.";\n\n");
///////////////////////////////////////////////////////////////////////////////
//	PARA AJAX
///////////////////////////////////////////////////////////////////////////////
//	desarrollo de carreras y sucesion
if ($modulo == "fideicomiso_calculo") {
	//	nuevo registro
	if ($accion == "procesar") {
		//	elimino
		$sql = "DELETE FROM pr_fideicomisocalculo
				WHERE
					CodPersona = '".$CodPersona."' AND
					Periodo >= '".$Periodo."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto detalles
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			list($Periodo, $SueldoMensual, $Bonificaciones, $AliVac, $AliFin, $SueldoDiario, $SueldoDiarioAli, $Dias, $PrestAntiguedad, $DiasComplemento, $PrestComplemento, $PrestAcumulada, $Tasa, $DiasMes, $InteresMensual, $InteresAcumulado, $Anticipo) = split(";char:td;", $linea);
			//	inserto
			$sql = "INSERT INTO pr_fideicomisocalculo
					SET
						Periodo = '".$Periodo."',
						CodPersona = '".$CodPersona."',
						SueldoMensual = '".$SueldoMensual."',
						Bonificaciones = '".$Bonificaciones."',
						AliVac = '".$AliVac."',
						AliFin = '".$AliFin."',
						SueldoDiario = '".$SueldoDiario."',
						SueldoDiarioAli = '".$SueldoDiarioAli."',
						Dias = '".$Dias."',
						PrestAntiguedad = '".$PrestAntiguedad."',
						DiasComplemento = '".$DiasComplemento."',
						PrestComplemento = '".$PrestComplemento."',
						PrestAcumulada = '".$PrestAcumulada."',
						Tasa = '".$Tasa."',
						DiasMes = '".$DiasMes."',
						InteresMensual = '".$InteresMensual."',
						InteresAcumulado = '".$InteresAcumulado."',
						Anticipo = '".$Anticipo."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
	}
}


//	interfase cuentas por pagar
elseif ($modulo == "interfase_cuentas_por_pagar") {
	//	calcular
	if ($accion == "calcular") {
		mysql_query("BEGIN");
		//	-----------------
		list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
		$PeriodoActual = "$AnioActual-$MesActual";
		list($PeriodoAnio, $PeriodoMes) = split("[-]", $Periodo);
		
		//	verifico si la obligacion transferida a cxp esta anulada
		$sql = "SELECT
					po.FlagTransferido,
					ao.Estado
				FROM
					pr_obligaciones po
					INNER JOIN ap_obligaciones ao ON (po.CodProveedor = ao.CodProveedor AND
													  po.CodTipoDocumento = ao.CodTipoDocumento AND
													  po.NroDocumento = ao.NroDocumento)
				WHERE
					po.CodOrganismo = '".$CodOrganismo."' AND
					po.CodTipoNom = '".$CodTipoNom."' AND
					po.Periodo = '".$PeriodoActual."' AND
					po.PeriodoNomina = '".$Periodo."' AND
					po.CodTipoProceso = '".$CodTipoProceso."' AND
					po.FlagTransferido = 'S' AND
					ao.Estado <> 'AN'";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query) != 0) die("$sql Algunas obligaciones se encuentran actualmente transferidas a CxP<br />Debe anular las obligaciones transferidas para calcularlas nuevamente.");
		
		//	consulto para eliminar las obligaciones cxp
		$sql = "SELECT
					CodProveedor,
					CodTipoDocumento,
					NroDocumento
				FROM pr_obligaciones
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodTipoNom = '".$CodTipoNom."' AND
					Periodo = '".$PeriodoActual."' AND
					CodTipoProceso = '".$CodTipoProceso."'";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field = mysql_fetch_array($query)) {
			//	obligacion
			$sql = "DELETE FROM ap_obligaciones
					WHERE
						CodProveedor = '".$field['CodProveedor']."' AND
						CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
						NroDocumento = '".$field['NroDocumento']."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			//	cuentas
			$sql = "DELETE FROM ap_obligacionescuenta
					WHERE
						CodProveedor = '".$field['CodProveedor']."' AND
						CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
						NroDocumento = '".$field['NroDocumento']."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			//	impuestos/retenciones
			$sql = "DELETE FROM ap_obligacionesimpuesto
					WHERE
						CodProveedor = '".$field['CodProveedor']."' AND
						CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
						NroDocumento = '".$field['NroDocumento']."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			//	causados
			$sql = "DELETE FROM ap_distribucionobligacion
					WHERE
						CodProveedor = '".$field['CodProveedor']."' AND
						CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
						NroDocumento = '".$field['NroDocumento']."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			//	compromisos
			$sql = "DELETE FROM lg_distribucioncompromisos
					WHERE
						CodProveedor = '".$field['CodProveedor']."' AND
						CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
						NroDocumento = '".$field['NroDocumento']."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	consulto para obtener los calculos que se hayan insertado anteriormente
		$sql = "SELECT
					CodProveedor,
					CodTipoDocumento,
					NroDocumento
				FROM pr_obligaciones
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodTipoNom = '".$CodTipoNom."' AND
					Periodo = '".$PeriodoActual."' AND
					CodTipoProceso = '".$CodTipoProceso."'";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field = mysql_fetch_array($query)) {
			//	elimino las obligaciones x cuentas
			$sql = "DELETE FROM pr_obligacionescuenta
					WHERE
						CodProveedor = '".$field['CodProveedor']."' AND
						CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
						NroDocumento = '".$field['NroDocumento']."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			//	elimino las obligaciones x retenciones
			$sql = "DELETE FROM pr_obligacionesretenciones
					WHERE
						CodProveedor = '".$field['CodProveedor']."' AND
						CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
						NroDocumento = '".$field['NroDocumento']."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	elimino las obligaciones
		$sql = "DELETE FROM pr_obligaciones
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodTipoNom = '".$CodTipoNom."' AND
					Periodo = '".$PeriodoActual."' AND
					CodTipoProceso = '".$CodTipoProceso."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	obtengo el tipo de documento
		$sql = "SELECT CodTipoDocumento
				FROM pr_tiponominaproceso
				WHERE
					CodTipoNom = '".$CodTipoNom."' AND
					CodTipoProceso = '".$CodTipoProceso."'";
		$query_doc = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_doc) != 0) $field_doc = mysql_fetch_array($query_doc);
		
		//	obtengo la obligaciones a insertar
		if ($_PARAMETRO['INTERFASEAP'] == "S") {
			if ($CodTipoProceso != "BVC") {
				$sql = "(SELECT
							tn.Nomina,
							tp.Descripcion AS NomProceso,
							o.CodPersona AS CodProveedor,
							mp2.NomCompleto AS NomProveedor,
							'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
							me.CodTipoPago,
							'".$_PARAMETRO['TIPOSERVCXP']."' AS CodTipoServicio,
							'01' AS Ficha,
							SUM(tnec.Monto) AS MontoIngreso,
							'".$_PARAMETRO['CTANOMINA']."' AS CodCuenta,
							'".$_PARAMETRO['CTANOMINAPUB20']."' AS CodCuentaPub20
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
							tnec.CodTipoNom = '".$CodTipoNom."' AND
							tnec.Periodo = '".$Periodo."' AND
							tnec.CodOrganismo = '".$CodOrganismo."' AND
							tnec.CodTipoProceso = '".$CodTipoProceso."' AND
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
							'".$_PARAMETRO['TIPOSERVCXP']."' AS CodTipoServicio,
							'02' AS Ficha,
							SUM(tnec.Monto) AS MontoIngreso,
							'".$_PARAMETRO['CTANOMINA']."' AS CodCuenta,
							'".$_PARAMETRO['CTANOMINAPUB20']."' AS CodCuentaPub20
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
							tnec.CodTipoNom = '".$CodTipoNom."' AND
							tnec.Periodo = '".$Periodo."' AND
							tnec.CodOrganismo = '".$CodOrganismo."' AND
							tnec.CodTipoProceso = '".$CodTipoProceso."' AND
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
							'".$_PARAMETRO['TIPOSERVCXP']."' AS CodTipoServicio,
							'03' AS Ficha,
							SUM(tnec.Monto) AS MontoIngreso,
							cpd.CuentaHaber AS CodCuenta,
							cpd.CuentaHaberPub20 AS CodCuentaPub20
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
							tnec.CodTipoNom = '".$CodTipoNom."' AND
							tnec.Periodo = '".$Periodo."' AND
							tnec.CodOrganismo = '".$CodOrganismo."' AND
							tnec.CodTipoProceso = '".$CodTipoProceso."' AND
							c.Tipo = 'A'
						 GROUP BY c.CodPersona)
						UNION
						(SELECT
							tn.Nomina,
							tp.Descripcion AS NomProceso,
							rj.Demandante AS CodProveedor,
							mp2.NomCompleto AS NomProveedor,
							'".$_PARAMETRO['TIPODOCCXP']."' AS CodTipoDocumento,
							p.CodTipoPago,
							'".$_PARAMETRO['TIPOSERVCXP']."' AS CodTipoServicio,
							'04' AS Ficha,
							SUM(tnec.Monto) AS MontoIngreso,
							'".$_PARAMETRO['CTANOMINA']."' AS CodCuenta,
							'".$_PARAMETRO['CTANOMINAPUB20']."' AS CodCuentaPub20
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
							tnec.CodTipoNom = '".$CodTipoNom."' AND
							tnec.Periodo = '".$Periodo."' AND
							tnec.CodOrganismo = '".$CodOrganismo."' AND
							tnec.CodTipoProceso = '".$CodTipoProceso."'
						 GROUP BY rj.Demandante)";
			} else {
				$sql = "(SELECT
							tn.Nomina,
							tp.Descripcion AS NomProceso,
							mp1.CodPersona AS CodProveedor,
							mp1.NomCompleto AS NomProveedor,
							'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
							me.CodTipoPago,
							'".$_PARAMETRO['TIPOSERVCXP']."' AS CodTipoServicio,
							'01' AS Ficha,
							SUM(tnec.Monto) AS MontoIngreso,
							'".$_PARAMETRO['CTANOMINA']."' AS CodCuenta,
							'".$_PARAMETRO['CTANOMINAPUB20']."' AS CodCuentaPub20
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
							tnec.CodTipoNom = '".$CodTipoNom."' AND
							tnec.Periodo = '".$Periodo."' AND
							tnec.CodOrganismo = '".$CodOrganismo."' AND
							tnec.CodTipoProceso = '".$CodTipoProceso."' AND
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
							'".$_PARAMETRO['TIPOSERVCXP']."' AS CodTipoServicio,
							'02' AS Ficha,
							SUM(tnec.Monto) AS MontoIngreso,
							'".$_PARAMETRO['CTANOMINA']."' AS CodCuenta,
							'".$_PARAMETRO['CTANOMINAPUB20']."' AS CodCuentaPub20
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
							tnec.CodTipoNom = '".$CodTipoNom."' AND
							tnec.Periodo = '".$Periodo."' AND
							tnec.CodOrganismo = '".$CodOrganismo."' AND
							tnec.CodTipoProceso = '".$CodTipoProceso."' AND
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
							'".$_PARAMETRO['TIPOSERVCXP']."' AS CodTipoServicio,
							'03' AS Ficha,
							SUM(tnec.Monto) AS MontoIngreso,
							cpd.CuentaHaber AS CodCuenta,
							cpd.CuentaHaberPub20 AS CodCuentaPub20
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
							tnec.CodTipoNom = '".$CodTipoNom."' AND
							tnec.Periodo = '".$Periodo."' AND
							tnec.CodOrganismo = '".$CodOrganismo."' AND
							tnec.CodTipoProceso = '".$CodTipoProceso."' AND
							c.Tipo = 'A'
						 GROUP BY c.CodPersona)
						UNION
						(SELECT
							tn.Nomina,
							tp.Descripcion AS NomProceso,
							rj.Demandante AS CodProveedor,
							mp2.NomCompleto AS NomProveedor,
							'".$_PARAMETRO['TIPODOCCXP']."' AS CodTipoDocumento,
							p.CodTipoPago,
							'".$_PARAMETRO['TIPOSERVCXP']."' AS CodTipoServicio,
							'04' AS Ficha,
							SUM(tnec.Monto) AS MontoIngreso,
							'".$_PARAMETRO['CTANOMINA']."' AS CodCuenta,
							'".$_PARAMETRO['CTANOMINAPUB20']."' AS CodCuentaPub20
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
							tnec.CodTipoNom = '".$CodTipoNom."' AND
							tnec.Periodo = '".$Periodo."' AND
							tnec.CodOrganismo = '".$CodOrganismo."' AND
							tnec.CodTipoProceso = '".$CodTipoProceso."'
						 GROUP BY rj.Demandante)";
			}
		}	fwrite($__archivo, $sql.";\n\n");
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field = mysql_fetch_array($query)) {
			unset($field_descuento);
			unset($field_retencion);
			if ($CodTipoProceso == "BVC") $filtro_retencion = " AND tnec2.CodPersona = '".$field['CodProveedor']."'";
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
							tnec2.CodTipoNom = '".$CodTipoNom."' AND
							tnec2.Periodo = '".$Periodo."' AND
							tnec2.CodOrganismo = '".$CodOrganismo."' AND
							tnec2.CodTipoProceso = '".$CodTipoProceso."' AND
							c2.Tipo = 'D' AND
							c2.FlagRetencion = 'N' $filtro_retencion
						GROUP BY tnec2.CodOrganismo";
				$query_descuento = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
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
							tnec2.CodTipoNom = '".$CodTipoNom."' AND
							tnec2.Periodo = '".$Periodo."' AND
							tnec2.CodOrganismo = '".$CodOrganismo."' AND
							tnec2.CodTipoProceso = '".$CodTipoProceso."' AND
							c2.Tipo = 'D' AND
							c2.FlagRetencion = 'S' $filtro_retencion
						GROUP BY tnec2.CodOrganismo";
				$query_retencion = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
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
							tnec2.CodTipoNom = '".$CodTipoNom."' AND
							tnec2.Periodo = '".$Periodo."' AND
							tnec2.CodOrganismo = '".$CodOrganismo."' AND
							tnec2.CodTipoProceso = '".$CodTipoProceso."' AND
							c2.Tipo = 'D' AND
							c2.FlagRetencion = 'N'
						GROUP BY tnec2.CodOrganismo";
				$query_descuento = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
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
							tnec2.CodTipoNom = '".$CodTipoNom."' AND
							tnec2.Periodo = '".$Periodo."' AND
							tnec2.CodOrganismo = '".$CodOrganismo."' AND
							tnec2.CodTipoProceso = '".$CodTipoProceso."' AND
							c2.Tipo = 'D' AND
							c2.FlagRetencion = 'S'
						GROUP BY tnec2.CodOrganismo";
				$query_retencion = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_retencion) != 0) $field_retencion = mysql_fetch_array($query_retencion);
			}
			//	obtengo algunos valores a insertar
			$NroDocumento = $CodOrganismo.$PeriodoAnio.$PeriodoMes.$CodTipoNom.$CodTipoProceso.$field['Ficha'];
			##	valido nro de documento
			$sql = "SELECT *
					FROM ap_obligaciones
					WHERE
						CodProveedor = '".$field['CodProveedor']."' AND
						CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
						NroDocumento LIKE '".$NroDocumento."%'";
			$query_doc = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			$_nro = mysql_num_rows($query_doc);
			if ($_nro > 0) $NroDocumento .= "-".(++$_nro);
			##
			$NroCuenta = getCuentaBancariaDefault($CodOrganismo, $field['CodTipoPago']);
			$Comentarios = "PERIODO $Periodo NOMINA DE $field[Nomina] $field[NomProceso]";
			$MontoNoAfecto = $field['MontoIngreso'] - $field_descuento['MontoDescuento'];
			$MontoObligacion = $MontoNoAfecto - $field_retencion['MontoRetencion'];
			//	inserto la obligacion
			$sql = "INSERT INTO pr_obligaciones
					SET
						TipoObligacion = '".$field['Ficha']."',
						CodOrganismo = '".$CodOrganismo."',
						CodTipoNom = '".$CodTipoNom."',
						Periodo = '".$PeriodoActual."',
						PeriodoNomina = '".$Periodo."',
						CodTipoProceso = '".$CodTipoProceso."',
						CodProveedor = '".$field['CodProveedor']."',
						CodTipoDocumento = '".$field['CodTipoDocumento']."',
						NroDocumento = '".$NroDocumento."',
						NroCuenta = '".$NroCuenta."',
						CodTipoPago = '".$field['CodTipoPago']."',
						CodTipoServicio = '".$field['CodTipoServicio']."',
						FechaRegistro = NOW(),
						CodProveedorPagar = '".$field['CodProveedor']."',
						NomProveedorPagar = '".$field['NomProveedor']."',
						Comentarios = '".$Comentarios."',
						ComentariosAdicional = '".$Comentarios."',
						MontoObligacion = '".$MontoObligacion."',
						MontoNoAfecto = '".$MontoNoAfecto."',
						MontoImpuestoOtros = '".abs(($MontoNoAfecto-$MontoObligacion))."',
						CodCuenta = '".$field['CodCuenta']."',
						CodCuentaPub20 = '".$field['CodCuentaPub20']."',
						CodCentroCosto = '".$_PARAMETRO['CCOSTOPR']."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	consulto las partidas a insertar
		if ($CodTipoProceso != "BVC") {
			$sql = "(SELECT
						o.CodPersona AS CodProveedor,
						'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
						'01' AS Ficha,
						SUM(tnec.Monto) AS MontoIngreso,
						cpd.cod_partida,
						pv.CodCuenta,
						cpd.CuentaDebe,
						cpd.CuentaHaber,
						pv.CodCuentaPub20,
						cpd.CuentaDebePub20,
						cpd.CuentaHaberPub20
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
						tnec.CodTipoNom = '".$CodTipoNom."' AND
						tnec.Periodo = '".$Periodo."' AND
						tnec.CodOrganismo = '".$CodOrganismo."' AND
						tnec.CodTipoProceso = '".$CodTipoProceso."' AND
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
						cpd.CuentaHaber,
						pv.CodCuentaPub20,
						cpd.CuentaDebePub20,
						cpd.CuentaHaberPub20
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
						tnec.CodTipoNom = '".$CodTipoNom."' AND
						tnec.Periodo = '".$Periodo."' AND
						tnec.CodOrganismo = '".$CodOrganismo."' AND
						tnec.CodTipoProceso = '".$CodTipoProceso."' AND
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
						cpd.CuentaHaber,
						pv.CodCuentaPub20,
						cpd.CuentaDebePub20,
						cpd.CuentaHaberPub20
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
						tnec.CodTipoNom = '".$CodTipoNom."' AND
						tnec.Periodo = '".$Periodo."' AND
						tnec.CodOrganismo = '".$CodOrganismo."' AND
						tnec.CodTipoProceso = '".$CodTipoProceso."' AND
						c.Tipo = 'A'
					GROUP BY c.CodPersona, cpd.cod_partida)
					UNION
					(SELECT
						rj.Demandante AS CodProveedor,
						'".$_PARAMETRO['TIPODOCCXP']."' AS CodTipoDocumento,
						'04' AS Ficha,
						SUM(tnec.Monto) AS MontoIngreso,
						cpd.cod_partida,
						pv.CodCuenta,
						cpd.CuentaDebe,
						cpd.CuentaHaber,
						pv.CodCuentaPub20,
						cpd.CuentaDebePub20,
						cpd.CuentaHaberPub20
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
						tnec.CodTipoNom = '".$CodTipoNom."' AND
						tnec.Periodo = '".$Periodo."' AND
						tnec.CodOrganismo = '".$CodOrganismo."' AND
						tnec.CodTipoProceso = '".$CodTipoProceso."'
					GROUP BY rj.Demandante, cpd.cod_partida)";
		} else {
			$sql = "(SELECT
						mp1.CodPersona AS CodProveedor,
						'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
						'01' AS Ficha,
						SUM(tnec.Monto) AS MontoIngreso,
						cpd.cod_partida,
						pv.CodCuenta,
						cpd.CuentaDebe,
						cpd.CuentaHaber,
						pv.CodCuentaPub20,
						cpd.CuentaDebePub20,
						cpd.CuentaHaberPub20
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
						tnec.CodTipoNom = '".$CodTipoNom."' AND
						tnec.Periodo = '".$Periodo."' AND
						tnec.CodOrganismo = '".$CodOrganismo."' AND
						tnec.CodTipoProceso = '".$CodTipoProceso."' AND
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
						cpd.CuentaHaber,
						pv.CodCuentaPub20,
						cpd.CuentaDebePub20,
						cpd.CuentaHaberPub20
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
						tnec.CodTipoNom = '".$CodTipoNom."' AND
						tnec.Periodo = '".$Periodo."' AND
						tnec.CodOrganismo = '".$CodOrganismo."' AND
						tnec.CodTipoProceso = '".$CodTipoProceso."' AND
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
						cpd.CuentaHaber,
						pv.CodCuentaPub20,
						cpd.CuentaDebePub20,
						cpd.CuentaHaberPub20
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
						tnec.CodTipoNom = '".$CodTipoNom."' AND
						tnec.Periodo = '".$Periodo."' AND
						tnec.CodOrganismo = '".$CodOrganismo."' AND
						tnec.CodTipoProceso = '".$CodTipoProceso."' AND
						c.Tipo = 'A'
					GROUP BY c.CodPersona, cpd.cod_partida)
					UNION
					(SELECT
						rj.Demandante AS CodProveedor,
						'".$_PARAMETRO['TIPODOCCXP']."' AS CodTipoDocumento,
						'04' AS Ficha,
						SUM(tnec.Monto) AS MontoIngreso,
						cpd.cod_partida,
						pv.CodCuenta,
						cpd.CuentaDebe,
						cpd.CuentaHaber,
						pv.CodCuentaPub20,
						cpd.CuentaDebePub20,
						cpd.CuentaHaberPub20
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
						tnec.CodTipoNom = '".$CodTipoNom."' AND
						tnec.Periodo = '".$Periodo."' AND
						tnec.CodOrganismo = '".$CodOrganismo."' AND
						tnec.CodTipoProceso = '".$CodTipoProceso."'
					GROUP BY rj.Demandante, cpd.cod_partida)";
		}	fwrite($__archivo, $sql.";\n\n");
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$Linea=0;
		while ($field = mysql_fetch_array($query)) {	$Linea++;
			unset($field_descuento);
			unset($field_adelanto);
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
							tnec2.CodTipoNom = '".$CodTipoNom."' AND
							tnec2.Periodo = '".$Periodo."' AND
							tnec2.CodOrganismo = '".$CodOrganismo."' AND
							tnec2.CodTipoProceso = '".$CodTipoProceso."' AND
							c2.Tipo = 'D' AND
							c2.FlagRetencion = 'N' AND
							cpd2.cod_partida = '".$field['cod_partida']."'
						GROUP BY tnec2.CodOrganismo";
				$query_descuento = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_descuento) != 0) $field_descuento = mysql_fetch_array($query_descuento);
				//	si el proceso es fin de mes
				if ($CodTipoProceso == "FIN") {
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
								tnec.CodTipoNom = '".$CodTipoNom."' AND
								tnec.Periodo = '".$Periodo."' AND
								tnec.CodOrganismo = '".$CodOrganismo."' AND
								tnec.CodTipoProceso = 'ADE' AND
								c.Tipo = 'I' AND
								cpd.cod_partida = '".$field['cod_partida']."'
							GROUP BY o.CodPersona, cpd.cod_partida";
					$query_adelanto = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
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
							tnec2.CodTipoNom = '".$CodTipoNom."' AND
							tnec2.Periodo = '".$Periodo."' AND
							tnec2.CodOrganismo = '".$CodOrganismo."' AND
							tnec2.CodTipoProceso = '".$CodTipoProceso."' AND
							c2.Tipo = 'D' AND
							c2.FlagRetencion = 'N' AND
							cpd2.cod_partida = '".$field['cod_partida']."'
						GROUP BY tnec2.CodOrganismo";
				$query_descuento = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_descuento) != 0) $field_descuento = mysql_fetch_array($query_descuento);
				//	si el proceso es fin de mes
				if ($CodTipoProceso == "FIN") {
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
								tnec.CodTipoNom = '".$CodTipoNom."' AND
								tnec.Periodo = '".$Periodo."' AND
								tnec.CodOrganismo = '".$CodOrganismo."' AND
								tnec.CodTipoProceso = 'ADE' AND
								c.Tipo = 'I' AND
								cpd.cod_partida = '".$field['cod_partida']."'
							GROUP BY mp1.CodPersona, cpd.cod_partida";
					$query_adelanto = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					if (mysql_num_rows($query_adelanto) != 0) $field_adelanto = mysql_fetch_array($query_adelanto);
				}
			}
			//	valido las cuentas
			if ($field['Ficha'] == "04") {
				if ($field['CuentaDebe'] != "") $CodCuenta = $field['CuentaDebe'];
				else $CodCuenta = $field['CuentaHaber'];
				if ($field['CuentaDebePub20'] != "") $CodCuentaPub20 = $field['CuentaDebePub20'];
				else $CodCuentaPub20 = $field['CuentaHaberPub20'];
			} else {
				$CodCuenta = $field['CodCuenta'];
				$CodCuentaPub20 = $field['CodCuentaPub20'];
			}
			//	montos
			$cod_partida = $field['cod_partida'];
			$NroDocumento = $CodOrganismo.$PeriodoAnio.$PeriodoMes.$CodTipoNom.$CodTipoProceso.$field['Ficha'];
			$Monto = floatval($field['MontoIngreso']) - floatval($field_descuento['MontoDescuento']) - floatval($field_adelanto['MontoAdelanto']);
			//	inserto la cuenta
			$sql = "INSERT INTO pr_obligacionescuenta
					SET
						CodProveedor = '".$field['CodProveedor']."',
						CodTipoDocumento = '".$field['CodTipoDocumento']."',
						NroDocumento = '".$NroDocumento."',
						Linea = '".$Linea."',
						Descripcion = '".$field['Descripcion']."',
						Monto = '".$Monto."',
						CodCentroCosto = '".$_PARAMETRO['CCOSTOPR']."',
						CodCuenta = '".$CodCuenta."',
						CodCuentaPub20 = '".$CodCuentaPub20."',
						cod_partida = '".$field['cod_partida']."',
						FlagNoAfectoIGV = 'N',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	consulto las retenciones a insertar
		if ($CodTipoProceso != "BVC") {
			$sql = "(SELECT
						mp2.CodPersona AS CodProveedor,
						'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
						'01' AS Ficha,
						SUM(tnec.Monto) AS MontoImpuesto,
						c.CodConcepto AS CodRetencion,
						cpd.CuentaHaber AS CodCuenta,
						cpd.CuentaHaberPub20 AS CodCuentaPub20
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
						tnec.CodTipoNom = '".$CodTipoNom."' AND
						tnec.Periodo = '".$Periodo."' AND
						tnec.CodOrganismo = '".$CodOrganismo."' AND
						tnec.CodTipoProceso = '".$CodTipoProceso."' AND
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
						cpd.CuentaHaber AS CodCuenta,
						cpd.CuentaHaberPub20 AS CodCuentaPub20
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
						tnec.CodTipoNom = '".$CodTipoNom."' AND
						tnec.Periodo = '".$Periodo."' AND
						tnec.CodOrganismo = '".$CodOrganismo."' AND
						tnec.CodTipoProceso = '".$CodTipoProceso."' AND
						c.Tipo = 'D' AND
						c.FlagRetencion = 'S'
					 GROUP BY tnec.CodOrganismo, CodRetencion)
					 ORDER BY Ficha";
		} else {
			$sql = "(SELECT
						mp1.CodPersona AS CodProveedor,
						'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
						'01' AS Ficha,
						SUM(tnec.Monto) AS MontoImpuesto,
						c.CodConcepto AS CodRetencion,
						cpd.CuentaHaber AS CodCuenta,
						cpd.CuentaHaberPub20 AS CodCuentaPub20
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
						tnec.CodTipoNom = '".$CodTipoNom."' AND
						tnec.Periodo = '".$Periodo."' AND
						tnec.CodOrganismo = '".$CodOrganismo."' AND
						tnec.CodTipoProceso = '".$CodTipoProceso."' AND
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
						cpd.CuentaHaber AS CodCuenta,
						cpd.CuentaHaberPub20 AS CodCuentaPub20
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
						tnec.CodTipoNom = '".$CodTipoNom."' AND
						tnec.Periodo = '".$Periodo."' AND
						tnec.CodOrganismo = '".$CodOrganismo."' AND
						tnec.CodTipoProceso = '".$CodTipoProceso."' AND
						c.Tipo = 'D' AND
						c.FlagRetencion = 'S'
					 GROUP BY tnec.CodOrganismo, CodRetencion)
					 ORDER BY Ficha";
		}	fwrite($__archivo, $sql.";\n\n");
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$Linea=0;
		while ($field = mysql_fetch_array($query)) {	$Linea++;
			$NroDocumento = $CodOrganismo.$PeriodoAnio.$PeriodoMes.$CodTipoNom.$CodTipoProceso.$field['Ficha'];
			//	inserto las retenciones
			$sql = "INSERT INTO pr_obligacionesretenciones
					SET
						CodProveedor = '".$field['CodProveedor']."',
						CodTipoDocumento = '".$field['CodTipoDocumento']."',
						NroDocumento = '".$NroDocumento."',
						Linea = '".$Linea."',
						CodConcepto = '".$field['CodRetencion']."',
						MontoImpuesto = '".$field['MontoImpuesto']."',
						MontoAfecto = '".$field['MontoAfecto']."',
						CodCuenta = '".$field['CodCuenta']."',
						CodCuentaPub20 = '".$field['CodCuentaPub20']."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	generar
	elseif ($accion == "generar") {
		mysql_query("BEGIN");
		//	-----------------
		list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
		$PeriodoActual = "$AnioActual-$MesActual";
		list($PeriodoAnio, $PeriodoMes) = split("[-]", $Periodo);
		
		//	presupuesto
		$sql = "SELECT CodPresupuesto
				FROM pv_presupuesto 
				WHERE EjercicioPpto = '".$AnioActual."' AND Organismo = '".$CodOrganismo."'";
		$query_presupuesto = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_presupuesto)) $field_presupuesto = mysql_fetch_array($query_presupuesto);
		$CodPresupuesto = $field_presupuesto['CodPresupuesto'];

		if ($detalles_bancos != "" || $detalles_cheques != "" || $detalles_terceros != ""  || $detalles_judiciales != "" ) {
			if ($frm == "bancos") $detalles_ficha = $detalles_bancos;
			elseif ($frm == "cheques") $detalles_ficha = $detalles_cheques;
			elseif ($frm == "terceros") $detalles_ficha = $detalles_terceros;
			elseif ($frm == "judiciales") $detalles_ficha = $detalles_judiciales;
			
			$detalles = split(";", $detalles_ficha);
			foreach ($detalles as $detalle) {
				list($CodProveedor, $CodTipoDocumento, $NroDocumento, $TipoObligacion) = split("[_]", $detalle);
		
				//	verifico si la obligacion transferida a cxp esta anulada
				$sql = "SELECT
							po.FlagTransferido,
							ao.Estado
						FROM
							pr_obligaciones po
							INNER JOIN ap_obligaciones ao ON (po.CodProveedor = ao.CodProveedor AND
															  po.CodTipoDocumento = ao.CodTipoDocumento AND
															  po.NroDocumento = ao.NroDocumento)
						WHERE
							ao.CodProveedor = '".$CodProveedor."' AND
							ao.CodTipoDocumento = '".$CodTipoDocumento."' AND
							ao.NroDocumento = '".$NroDocumento."' AND
							ao.Estado <> 'AN'";
				$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query) != 0) die("Algunas obligaciones seleccionadas se encuentran actualmente transferidas a CxP<br />Debe anular las obligaciones transferidas para calcularlas nuevamente.");
				
				//	verifico presupuesto
				if ($TipoObligacion != "04") {
					$sql = "SELECT cod_partida, Monto
							FROM pr_obligacionescuenta
							WHERE
								CodProveedor = '".$CodProveedor."' AND
								CodTipoDocumento = '".$CodTipoDocumento."' AND
								NroDocumento = '".$NroDocumento."'";
					$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					while ($field = mysql_fetch_array($query)) {
						if (!valPresupuesto($CodOrganismo, substr($PeriodoActual, 0, 4), $field['cod_partida'], $field['Monto'])) die("Se encontr&oacute; la partida <strong>$field[cod_partida]</strong> sin disponibilidad presupuestaria");
					}
				}
				
				//	elimino
				##	obligacion
				$sql = "DELETE FROM ap_obligaciones
						WHERE
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'";
				$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				##	cuentas
				$sql = "DELETE FROM ap_obligacionescuenta
						WHERE
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'";
				$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				##	impuestos/retenciones
				$sql = "DELETE FROM ap_obligacionesimpuesto
						WHERE
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'";
				$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				##	causados
				$sql = "DELETE FROM ap_distribucionobligacion
						WHERE
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'";
				$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				##	compromisos
				$sql = "DELETE FROM lg_distribucioncompromisos
						WHERE
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'";
				$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				//	si es pago de retenciones no afecta presupuesto
				if ($TipoObligacion == "04") {
					$FlagCompromiso = "N";
					$FlagPresupuesto = "N";
				} else {
					$FlagCompromiso = "S";
					$FlagPresupuesto = "S";
				}
				
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
							CodCuentaPub20,
							CodCentroCosto,
							TipoObligacion
						FROM pr_obligaciones
						WHERE
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'";
				$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				while ($field = mysql_fetch_array($query)) {
					//	inserto la obligacion
					$NroRegistro = getCodigo_2("ap_obligaciones", "NroRegistro", "CodOrganismo", $CodOrganismo, 6);
					$sql = "INSERT INTO ap_obligaciones
							SET
								CodProveedor = '".$field['CodProveedor']."',
								CodTipoDocumento = '".$field['CodTipoDocumento']."',
								NroDocumento = '".$field['NroDocumento']."',
								NroControl = '".$field['NroDocumento']."',
								CodOrganismo = '".$field['CodOrganismo']."',
								NroCuenta = '".$field['NroCuenta']."',
								CodTipoPago = '".$field['CodTipoPago']."',
								FechaRegistro = NOW(),
								FechaFactura = NOW(),
								CodTipoServicio = '".$field['CodTipoServicio']."',
								ReferenciaTipoDocumento = 'NO',
								ReferenciaNroDocumento = '".$field['NroDocumento']."',
								MontoObligacion = '".$field['MontoObligacion']."',
								MontoImpuestoOtros = '-".$field['MontoImpuestoOtros']."',
								MontoNoAfecto = '".$field['MontoNoAfecto']."',
								IngresadoPor = '".$_SESSION['CODPERSONA_ACTUAL']."',
								RevisadoPor = '".$_SESSION['CODPERSONA_ACTUAL']."',
								FechaPreparacion = NOW(),
								FechaRevision = NOW(),
								NroRegistro = '".$NroRegistro."',
								Comentarios = '".$field['Comentarios']."',
								ComentariosAdicional = '".$field['ComentariosAdicional']."',
								CodProveedorPagar = '".$field['CodProveedorPagar']."',
								FechaDocumento = NOW(),
								FechaVencimiento = NOW(),
								FechaRecepcion = NOW(),
								FechaProgramada = NOW(),
								Estado = 'PR',
								CodCuenta = '".$field['CodCuenta']."',
								CodCuentaPub20 = '".$field['CodCuentaPub20']."',
								Periodo = NOW(),
								CodCentroCosto = '".$field['CodCentroCosto']."',
								FlagCompromiso = '".$FlagCompromiso."',
								FlagPresupuesto = '".$FlagPresupuesto."',
								CodPresupuesto = '".$CodPresupuesto."',
								UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
								UltimaFecha = NOW()";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					
					//	actualizo pr_obligacion
					$sql = "UPDATE pr_obligaciones
							SET
								NroRegistro = '".$NroRegistro."',
								FlagTransferido = 'S'
							WHERE
								CodProveedor = '".$CodProveedor."' AND
								CodTipoDocumento = '".$CodTipoDocumento."' AND
								NroDocumento = '".$NroDocumento."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
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
							CodCuentaPub20,
							cod_partida,
							CodCentroCosto
						FROM pr_obligacionescuenta
						WHERE
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'
						GROUP BY cod_partida";
				$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				while ($field = mysql_fetch_array($query)) {
					//	inserto la cuenta
					$sql = "INSERT INTO ap_obligacionescuenta
							SET
								CodProveedor = '".$field['CodProveedor']."',
								CodTipoDocumento = '".$field['CodTipoDocumento']."',
								NroDocumento = '".$field['NroDocumento']."',
								Linea = '".$field['Linea']."',
								Descripcion = '".$field['Descripcion']."',
								Monto = '".$field['Monto']."',
								CodCuenta = '".$field['CodCuenta']."',
								CodCuentaPub20 = '".$field['CodCuentaPub20']."',
								cod_partida = '".$field['cod_partida']."',
								TipoOrden = 'NO',
								NroOrden = '".$field['NroDocumento']."',
								FlagNoAfectoIGV = 'S',
								CodCentroCosto = '".$field['CodCentroCosto']."',
								UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
								UltimaFecha = NOW()";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					
					//	inserto la distribucion
					$sql = "INSERT INTO ap_distribucionobligacion
							SET
								CodProveedor = '".$field['CodProveedor']."',
								CodTipoDocumento = '".$field['CodTipoDocumento']."',
								NroDocumento = '".$field['NroDocumento']."',
								Monto = '".$field['Monto']."',
								CodCuenta = '".$field['CodCuenta']."',
								CodCuentaPub20 = '".$field['CodCuentaPub20']."',
								cod_partida = '".$field['cod_partida']."',
								CodCentroCosto = '".$field['CodCentroCosto']."',
								Periodo = '".$PeriodoActual."',
								Anio = '".substr($PeriodoActual, 0, 4)."',
								FlagCompromiso = '".$FlagCompromiso."',
								CodPresupuesto = '".$CodPresupuesto."',
								Estado = 'PE',
								UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
								UltimaFecha = NOW()";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					
					if ($FlagCompromiso == "S") {
						//	inserto la distribucion
						$sql = "INSERT INTO lg_distribucioncompromisos
								SET
									Anio = '".substr($PeriodoActual, 0, 4)."',
									CodOrganismo = '".$CodOrganismo."',
									CodProveedor = '".$field['CodProveedor']."',
									CodTipoDocumento = '".$field['CodTipoDocumento']."',
									NroDocumento = '".$field['NroDocumento']."',
									Secuencia = '".$field['Linea']."',
									Linea = '1',
									Mes = '".substr($Periodo, 5, 2)."',
									CodCentroCosto = '".$field['CodCentroCosto']."',
									cod_partida = '".$field['cod_partida']."',
									Monto = '".$field['Monto']."',
									Periodo = '".$PeriodoActual."',
									CodPresupuesto = '".$CodPresupuesto."',
									Estado = 'PE',
									UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
									UltimaFecha = NOW()";
						$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
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
							CodCuenta,
							CodCuentaPub20
						FROM pr_obligacionesretenciones
						WHERE
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'";
				$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				while ($field = mysql_fetch_array($query)) {
					//	inserto la obligacion
					$sql = "INSERT INTO ap_obligacionesimpuesto
							SET
								CodProveedor = '".$field['CodProveedor']."',
								CodTipoDocumento = '".$field['CodTipoDocumento']."',
								NroDocumento = '".$field['NroDocumento']."',
								Linea = '".$field['Linea']."',
								CodConcepto = '".$field['CodConcepto']."',
								MontoImpuesto = '-".$field['MontoImpuesto']."',
								MontoAfecto = '".$field['MontoAfecto']."',
								FlagProvision = '".$field['FlagProvision']."',
								CodCuenta = '".$field['CodCuenta']."',
								CodCuentaPub20 = '".$field['CodCuentaPub20']."',
								UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
								UltimaFecha = NOW()";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	consolidar
	elseif ($accion == "consolidar") {
		mysql_query("BEGIN");
		//	-----------------
		list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
		$PeriodoActual = "$AnioActual-$MesActual";
		list($PeriodoAnio, $PeriodoMes) = split("[-]", $Periodo);
		
		//	consulto el proveedor del organismo
		$sql = "SELECT
					o.CodPersona,
					p.NomCompleto AS NomPersona
				FROM
					mastorganismos o
					INNER JOIN mastpersonas p ON (o.CodPersona = p.CodPersona)
				WHERE o.CodOrganismo = '".$CodOrganismo."'";
		$query_proveedor = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_proveedor) != 0) $field_proveedor = mysql_fetch_array($query_proveedor);
		else die("No se encontr&oacute; un Proveedor para asociar la Obligaci&oacute;n!");

		if ($detalles_bancos != "") {
			$filtro = "";
			$detalles = split(";", $detalles_bancos);
			foreach ($detalles as $detalle) {
				list($CodProveedor, $CodTipoDocumento, $NroDocumento, $TipoObligacion) = split("[_]", $detalle);
		
				//	verifico si la obligacion transferida a cxp esta anulada
				$sql = "SELECT
							po.FlagTransferido,
							ao.Estado
						FROM
							pr_obligaciones po
							INNER JOIN ap_obligaciones ao ON (po.CodProveedor = ao.CodProveedor AND
															  po.CodTipoDocumento = ao.CodTipoDocumento AND
															  po.NroDocumento = ao.NroDocumento)
						WHERE
							ao.CodProveedor = '".$CodProveedor."' AND
							ao.CodTipoDocumento = '".$CodTipoDocumento."' AND
							ao.NroDocumento = '".$NroDocumento."' AND
							ao.Estado <> 'AN'";
				$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query) != 0) die("Algunas obligaciones seleccionadas se encuentran actualmente transferidas a CxP<br />Debe anular las obligaciones transferidas para calcularlas nuevamente.");
				
				if ($filtro != "") $filtro .= " OR ";
				$filtro .= "(CodProveedor = '".$CodProveedor."' AND
							 CodTipoDocumento = '".$CodTipoDocumento."' AND
							 NroDocumento = '".$NroDocumento."')";
			}
			
			//	consulto la tabla general
			$sql = "SELECT
						TipoObligacion,
						CodOrganismo,
						CodTipoNom,
						Periodo,
						PeriodoNomina,
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
						CodCuentaPub20,
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
				$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				//	consulto el numero de obligaciones que he consolidado
				$sql = "SELECT *
						FROM pr_obligaciones
						WHERE
							CodProveedor = '".$field_proveedor['CodPersona']."' AND
							CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
							NroDocumento LIKE '".$field['NroDocumento']."-%'";
				$query_numero = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				$rows = intval(mysql_num_rows($query_numero));	$rows++;
				
				//	obtengo algunos valores a insertar
				$NroDocumento = $field['NroDocumento']."-".$rows;
				$NroCuenta = getCuentaBancariaDefault($CodOrganismo, $field['CodTipoPago']);
				
				//	inserto la obligacion
				$sql = "INSERT INTO pr_obligaciones
						SET
							TipoObligacion = '".$field['TipoObligacion']."',
							CodOrganismo = '".$field['CodOrganismo']."',
							CodTipoNom = '".$field['CodTipoNom']."',
							Periodo = '".$field['Periodo']."',
							PeriodoNomina = '".$field['PeriodoNomina']."',
							CodTipoProceso = '".$field['CodTipoProceso']."',
							CodProveedor = '".$field_proveedor['CodPersona']."',
							CodTipoDocumento = '".$field['CodTipoDocumento']."',
							NroDocumento = '".$NroDocumento."',
							NroCuenta = '".$NroCuenta."',
							CodTipoPago = '".$field['CodTipoPago']."',
							CodTipoServicio = '".$field['CodTipoServicio']."',
							FechaRegistro = NOW(),
							CodProveedorPagar = '".$field_proveedor['CodPersona']."',
							NomProveedorPagar = '".$field_proveedor['NomPersona']."',
							Comentarios = '".$field['Comentarios']."',
							ComentariosAdicional = '".$field['ComentariosAdicional']."',
							MontoObligacion = '".$field['MontoObligacion']."',
							MontoNoAfecto = '".$field['MontoNoAfecto']."',
							MontoImpuestoOtros = '".$field['MontoImpuestoOtros']."',
							CodCuenta = '".$field['CodCuenta']."',
							CodCuentaPub20 = '".$field['CodCuentaPub20']."',
							CodCentroCosto = '".$field['CodCentroCosto']."',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
			
			//	consulto las cuentas
			$sql = "SELECT
						CodTipoDocumento,
						NroDocumento,
						CodCentroCosto,
						CodCuenta,
						CodCuentaPub20,
						cod_partida,
						FlagNoAfectoIGV,
						SUM(Monto) AS Monto
					FROM pr_obligacionescuenta
					WHERE $filtro
					GROUP BY CodTipoDocumento, NroDocumento";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$i=0;
			while($field = mysql_fetch_array($query)) {	$i++;
				//	elimino las seleccionadas
				$sql = "DELETE FROM pr_obligacionescuenta WHERE $filtro";
				$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
				//	inserto la obligacion x cuenta
				$sql = "INSERT INTO pr_obligacionescuenta
						SET
							CodProveedor = '".$field_proveedor['CodPersona']."',
							CodTipoDocumento = '".$field['CodTipoDocumento']."',
							NroDocumento = '".$NroDocumento."',
							Linea = '".$i."',
							CodCentroCosto = '".$field['CodCentroCosto']."',
							CodCuenta = '".$field['CodCuenta']."',
							CodCuentaPub20 = '".$field['CodCuentaPub20']."',
							cod_partida = '".$field['cod_partida']."',
							FlagNoAfectoIGV = '".$field['FlagNoAfectoIGV']."',
							Monto = '".$field['Monto']."',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
			
			//	consulto las retenciones
			$sql = "SELECT
						CodTipoDocumento,
						NroDocumento,
						CodConcepto,
						SUM(MontoImpuesto) AS MontoImpuesto,
						SUM(MontoAfecto) AS MontoAfecto,
						CodCuenta,
						CodCuentaPub20,
						FlagProvision
					FROM pr_obligacionesretenciones
					WHERE MontoImpuesto > 0 AND ($filtro)
					GROUP BY CodTipoDocumento, NroDocumento";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$i=0;
			while($field = mysql_fetch_array($query)) {	$i++;
				//	elimino las seleccionadas
				$sql = "DELETE FROM pr_obligacionesretenciones WHERE $filtro";
				$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
				//	inserto la obligacion retenciones
				$sql = "INSERT INTO pr_obligacionesretenciones
						SET
							CodProveedor = '".$field_proveedor['CodPersona']."',
							CodTipoDocumento = '".$field['CodTipoDocumento']."',
							NroDocumento = '".$nrodocumento."',
							Linea = '".$i."',
							CodConcepto = '".$field['CodConcepto']."',
							MontoImpuesto = '".$field['MontoImpuesto']."',
							MontoAfecto = '".$field['MontoAfecto']."',
							CodCuenta = '".$field['CodCuenta']."',
							CodCuentaPub20 = '".$field['CodCuentaPub20']."',
							FlagProvision = '".$field['FlagProvision']."',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	

//	interfase cuentas por pagar
/*elseif ($modulo == "interfase_cuentas_por_pagar") {
	//	calcular
	if ($accion == "calcular") {
		mysql_query("BEGIN");
		//	-----------------
		list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
		$PeriodoActual = "$AnioActual-$MesActual";
		list($PeriodoAnio, $PeriodoMes) = split("[-]", $Periodo);
		
		//	verifico si la obligacion transferida a cxp esta anulada
		$sql = "SELECT
					po.FlagTransferido,
					ao.Estado
				FROM
					pr_obligaciones po
					INNER JOIN ap_obligaciones ao ON (po.CodProveedor = ao.CodProveedor AND
													  po.CodTipoDocumento = ao.CodTipoDocumento AND
													  po.NroDocumento = ao.NroDocumento)
				WHERE
					po.CodOrganismo = '".$CodOrganismo."' AND
					po.CodTipoNom = '".$CodTipoNom."' AND
					po.Periodo = '".$PeriodoActual."' AND
					po.PeriodoNomina = '".$Periodo."' AND
					po.CodTipoProceso = '".$CodTipoProceso."' AND
					po.FlagTransferido = 'S' AND
					ao.Estado <> 'AN'";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query) != 0) die("$sql Algunas obligaciones se encuentran actualmente transferidas a CxP<br />Debe anular las obligaciones transferidas para calcularlas nuevamente.");
		
		//	consulto para eliminar las obligaciones cxp
		$sql = "SELECT
					CodProveedor,
					CodTipoDocumento,
					NroDocumento
				FROM pr_obligaciones
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodTipoNom = '".$CodTipoNom."' AND
					Periodo = '".$PeriodoActual."' AND
					CodTipoProceso = '".$CodTipoProceso."'";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field = mysql_fetch_array($query)) {
			//	obligacion
			$sql = "DELETE FROM ap_obligaciones
					WHERE
						CodProveedor = '".$field['CodProveedor']."' AND
						CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
						NroDocumento = '".$field['NroDocumento']."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			//	cuentas
			$sql = "DELETE FROM ap_obligacionescuenta
					WHERE
						CodProveedor = '".$field['CodProveedor']."' AND
						CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
						NroDocumento = '".$field['NroDocumento']."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			//	impuestos/retenciones
			$sql = "DELETE FROM ap_obligacionesimpuesto
					WHERE
						CodProveedor = '".$field['CodProveedor']."' AND
						CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
						NroDocumento = '".$field['NroDocumento']."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			//	causados
			$sql = "DELETE FROM ap_distribucionobligacion
					WHERE
						CodProveedor = '".$field['CodProveedor']."' AND
						CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
						NroDocumento = '".$field['NroDocumento']."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			//	compromisos
			$sql = "DELETE FROM lg_distribucioncompromisos
					WHERE
						CodProveedor = '".$field['CodProveedor']."' AND
						CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
						NroDocumento = '".$field['NroDocumento']."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	consulto para obtener los calculos que se hayan insertado anteriormente
		$sql = "SELECT
					CodProveedor,
					CodTipoDocumento,
					NroDocumento
				FROM pr_obligaciones
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodTipoNom = '".$CodTipoNom."' AND
					Periodo = '".$Periodo."' AND
					CodTipoProceso = '".$CodTipoProceso."'";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field = mysql_fetch_array($query)) {
			//	elimino las obligaciones x cuentas
			$sql = "DELETE FROM pr_obligacionescuenta
					WHERE
						CodProveedor = '".$field['CodProveedor']."' AND
						CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
						NroDocumento = '".$field['NroDocumento']."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			//	elimino las obligaciones x retenciones
			$sql = "DELETE FROM pr_obligacionesretenciones
					WHERE
						CodProveedor = '".$field['CodProveedor']."' AND
						CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
						NroDocumento = '".$field['NroDocumento']."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	elimino las obligaciones
		$sql = "DELETE FROM pr_obligaciones
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodTipoNom = '".$CodTipoNom."' AND
					Periodo = '".$Periodo."' AND
					CodTipoProceso = '".$CodTipoProceso."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	obtengo el tipo de documento
		$sql = "SELECT CodTipoDocumento
				FROM pr_tiponominaproceso
				WHERE
					CodTipoNom = '".$CodTipoNom."' AND
					CodTipoProceso = '".$CodTipoProceso."'";
		$query_doc = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_doc) != 0) $field_doc = mysql_fetch_array($query_doc);
		
		//	obtengo la obligaciones a insertar
		if ($_PARAMETRO['INTERFASEAP'] == "S") {
			if ($CodTipoProceso != "BVC") {
				$sql = "(SELECT
							tn.Nomina,
							tp.Descripcion AS NomProceso,
							o.CodPersona AS CodProveedor,
							mp2.NomCompleto AS NomProveedor,
							'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
							me.CodTipoPago,
							'".$_PARAMETRO['TIPOSERVCXP']."' AS CodTipoServicio,
							'01' AS Ficha,
							SUM(tnec.Monto) AS MontoIngreso,
							'".$_PARAMETRO['CTANOMINA']."' AS CodCuenta,
							'".$_PARAMETRO['CTANOMINAPUB20']."' AS CodCuentaPub20
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
							tnec.CodTipoNom = '".$CodTipoNom."' AND
							tnec.Periodo = '".$Periodo."' AND
							tnec.CodOrganismo = '".$CodOrganismo."' AND
							tnec.CodTipoProceso = '".$CodTipoProceso."' AND
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
							'".$_PARAMETRO['TIPOSERVCXP']."' AS CodTipoServicio,
							'02' AS Ficha,
							SUM(tnec.Monto) AS MontoIngreso,
							'".$_PARAMETRO['CTANOMINA']."' AS CodCuenta,
							'".$_PARAMETRO['CTANOMINAPUB20']."' AS CodCuentaPub20
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
							tnec.CodTipoNom = '".$CodTipoNom."' AND
							tnec.Periodo = '".$Periodo."' AND
							tnec.CodOrganismo = '".$CodOrganismo."' AND
							tnec.CodTipoProceso = '".$CodTipoProceso."' AND
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
							'".$_PARAMETRO['TIPOSERVCXP']."' AS CodTipoServicio,
							'03' AS Ficha,
							SUM(tnec.Monto) AS MontoIngreso,
							cpd.CuentaHaber AS CodCuenta,
							cpd.CuentaHaberPub20 AS CodCuentaPub20
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
							tnec.CodTipoNom = '".$CodTipoNom."' AND
							tnec.Periodo = '".$Periodo."' AND
							tnec.CodOrganismo = '".$CodOrganismo."' AND
							tnec.CodTipoProceso = '".$CodTipoProceso."' AND
							c.Tipo = 'A'
						 GROUP BY c.CodPersona)
						UNION
						(SELECT
							tn.Nomina,
							tp.Descripcion AS NomProceso,
							rj.Demandante AS CodProveedor,
							mp2.NomCompleto AS NomProveedor,
							'".$_PARAMETRO['TIPODOCCXP']."' AS CodTipoDocumento,
							p.CodTipoPago,
							'".$_PARAMETRO['TIPOSERVCXP']."' AS CodTipoServicio,
							'04' AS Ficha,
							SUM(tnec.Monto) AS MontoIngreso,
							'".$_PARAMETRO['CTANOMINA']."' AS CodCuenta,
							'".$_PARAMETRO['CTANOMINAPUB20']."' AS CodCuentaPub20
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
							tnec.CodTipoNom = '".$CodTipoNom."' AND
							tnec.Periodo = '".$Periodo."' AND
							tnec.CodOrganismo = '".$CodOrganismo."' AND
							tnec.CodTipoProceso = '".$CodTipoProceso."'
						 GROUP BY rj.Demandante)";
			} else {
				$sql = "(SELECT
							tn.Nomina,
							tp.Descripcion AS NomProceso,
							mp1.CodPersona AS CodProveedor,
							mp1.NomCompleto AS NomProveedor,
							'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
							me.CodTipoPago,
							'".$_PARAMETRO['TIPOSERVCXP']."' AS CodTipoServicio,
							'01' AS Ficha,
							SUM(tnec.Monto) AS MontoIngreso,
							'".$_PARAMETRO['CTANOMINA']."' AS CodCuenta,
							'".$_PARAMETRO['CTANOMINAPUB20']."' AS CodCuentaPub20
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
							tnec.CodTipoNom = '".$CodTipoNom."' AND
							tnec.Periodo = '".$Periodo."' AND
							tnec.CodOrganismo = '".$CodOrganismo."' AND
							tnec.CodTipoProceso = '".$CodTipoProceso."' AND
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
							'".$_PARAMETRO['TIPOSERVCXP']."' AS CodTipoServicio,
							'02' AS Ficha,
							SUM(tnec.Monto) AS MontoIngreso,
							'".$_PARAMETRO['CTANOMINA']."' AS CodCuenta,
							'".$_PARAMETRO['CTANOMINAPUB20']."' AS CodCuentaPub20
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
							tnec.CodTipoNom = '".$CodTipoNom."' AND
							tnec.Periodo = '".$Periodo."' AND
							tnec.CodOrganismo = '".$CodOrganismo."' AND
							tnec.CodTipoProceso = '".$CodTipoProceso."' AND
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
							'".$_PARAMETRO['TIPOSERVCXP']."' AS CodTipoServicio,
							'03' AS Ficha,
							SUM(tnec.Monto) AS MontoIngreso,
							cpd.CuentaHaber AS CodCuenta,
							cpd.CuentaHaberPub20 AS CodCuentaPub20
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
							tnec.CodTipoNom = '".$CodTipoNom."' AND
							tnec.Periodo = '".$Periodo."' AND
							tnec.CodOrganismo = '".$CodOrganismo."' AND
							tnec.CodTipoProceso = '".$CodTipoProceso."' AND
							c.Tipo = 'A'
						 GROUP BY c.CodPersona)
						UNION
						(SELECT
							tn.Nomina,
							tp.Descripcion AS NomProceso,
							rj.Demandante AS CodProveedor,
							mp2.NomCompleto AS NomProveedor,
							'".$_PARAMETRO['TIPODOCCXP']."' AS CodTipoDocumento,
							p.CodTipoPago,
							'".$_PARAMETRO['TIPOSERVCXP']."' AS CodTipoServicio,
							'04' AS Ficha,
							SUM(tnec.Monto) AS MontoIngreso,
							'".$_PARAMETRO['CTANOMINA']."' AS CodCuenta,
							'".$_PARAMETRO['CTANOMINAPUB20']."' AS CodCuentaPub20
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
							tnec.CodTipoNom = '".$CodTipoNom."' AND
							tnec.Periodo = '".$Periodo."' AND
							tnec.CodOrganismo = '".$CodOrganismo."' AND
							tnec.CodTipoProceso = '".$CodTipoProceso."'
						 GROUP BY rj.Demandante)";
			}
		}	fwrite($__archivo, $sql.";\n\n");
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field = mysql_fetch_array($query)) {
			unset($field_descuento);
			unset($field_retencion);
			if ($CodTipoProceso == "BVC") $filtro_retencion = " AND tnec2.CodPersona = '".$field['CodProveedor']."'";
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
							tnec2.CodTipoNom = '".$CodTipoNom."' AND
							tnec2.Periodo = '".$Periodo."' AND
							tnec2.CodOrganismo = '".$CodOrganismo."' AND
							tnec2.CodTipoProceso = '".$CodTipoProceso."' AND
							c2.Tipo = 'D' AND
							c2.FlagRetencion = 'N' $filtro_retencion
						GROUP BY tnec2.CodOrganismo";
				$query_descuento = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
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
							tnec2.CodTipoNom = '".$CodTipoNom."' AND
							tnec2.Periodo = '".$Periodo."' AND
							tnec2.CodOrganismo = '".$CodOrganismo."' AND
							tnec2.CodTipoProceso = '".$CodTipoProceso."' AND
							c2.Tipo = 'D' AND
							c2.FlagRetencion = 'S' $filtro_retencion
						GROUP BY tnec2.CodOrganismo";
				$query_retencion = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
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
							tnec2.CodTipoNom = '".$CodTipoNom."' AND
							tnec2.Periodo = '".$Periodo."' AND
							tnec2.CodOrganismo = '".$CodOrganismo."' AND
							tnec2.CodTipoProceso = '".$CodTipoProceso."' AND
							c2.Tipo = 'D' AND
							c2.FlagRetencion = 'N'
						GROUP BY tnec2.CodOrganismo";
				$query_descuento = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
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
							tnec2.CodTipoNom = '".$CodTipoNom."' AND
							tnec2.Periodo = '".$Periodo."' AND
							tnec2.CodOrganismo = '".$CodOrganismo."' AND
							tnec2.CodTipoProceso = '".$CodTipoProceso."' AND
							c2.Tipo = 'D' AND
							c2.FlagRetencion = 'S'
						GROUP BY tnec2.CodOrganismo";
				$query_retencion = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_retencion) != 0) $field_retencion = mysql_fetch_array($query_retencion);
			}
			//	obtengo algunos valores a insertar
			$NroDocumento = $CodOrganismo.$PeriodoAnio.$PeriodoMes.$CodTipoNom.$CodTipoProceso.$field['Ficha'];
			##	valido nro de documento
			$sql = "SELECT *
					FROM ap_obligaciones
					WHERE
						CodProveedor = '".$field['CodProveedor']."' AND
						CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
						NroDocumento LIKE '".$NroDocumento."%'";
			$query_doc = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			$_nro = mysql_num_rows($query_doc);
			if ($_nro > 0) $NroDocumento .= "-".(++$_nro);
			##
			$NroCuenta = getCuentaBancariaDefault($CodOrganismo, $field['CodTipoPago']);
			$Comentarios = "PERIODO $Periodo NOMINA DE $field[Nomina] $field[NomProceso]";
			$MontoNoAfecto = $field['MontoIngreso'] - $field_descuento['MontoDescuento'];
			$MontoObligacion = $MontoNoAfecto - $field_retencion['MontoRetencion'];
			//	inserto la obligacion
			$sql = "INSERT INTO pr_obligaciones
					SET
						TipoObligacion = '".$field['Ficha']."',
						CodOrganismo = '".$CodOrganismo."',
						CodTipoNom = '".$CodTipoNom."',
						Periodo = '".$PeriodoActual."',
						PeriodoNomina = '".$Periodo."',
						CodTipoProceso = '".$CodTipoProceso."',
						CodProveedor = '".$field['CodProveedor']."',
						CodTipoDocumento = '".$field['CodTipoDocumento']."',
						NroDocumento = '".$NroDocumento."',
						NroCuenta = '".$NroCuenta."',
						CodTipoPago = '".$field['CodTipoPago']."',
						CodTipoServicio = '".$field['CodTipoServicio']."',
						FechaRegistro = NOW(),
						CodProveedorPagar = '".$field['CodProveedor']."',
						NomProveedorPagar = '".$field['NomProveedor']."',
						Comentarios = '".$Comentarios."',
						ComentariosAdicional = '".$Comentarios."',
						MontoObligacion = '".$MontoObligacion."',
						MontoNoAfecto = '".$MontoNoAfecto."',
						MontoImpuestoOtros = '".abs(($MontoNoAfecto-$MontoObligacion))."',
						CodCuenta = '".$field['CodCuenta']."',
						CodCuentaPub20 = '".$field['CodCuentaPub20']."',
						CodCentroCosto = '".$_PARAMETRO['CCOSTOPR']."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	consulto las partidas a insertar
		if ($CodTipoProceso != "BVC") {
			$sql = "(SELECT
						o.CodPersona AS CodProveedor,
						'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
						'01' AS Ficha,
						SUM(tnec.Monto) AS MontoIngreso,
						cpd.cod_partida,
						pv.CodCuenta,
						cpd.CuentaDebe,
						cpd.CuentaHaber,
						pv.CodCuentaPub20,
						cpd.CuentaDebePub20,
						cpd.CuentaHaberPub20
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
						tnec.CodTipoNom = '".$CodTipoNom."' AND
						tnec.Periodo = '".$Periodo."' AND
						tnec.CodOrganismo = '".$CodOrganismo."' AND
						tnec.CodTipoProceso = '".$CodTipoProceso."' AND
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
						cpd.CuentaHaber,
						pv.CodCuentaPub20,
						cpd.CuentaDebePub20,
						cpd.CuentaHaberPub20
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
						tnec.CodTipoNom = '".$CodTipoNom."' AND
						tnec.Periodo = '".$Periodo."' AND
						tnec.CodOrganismo = '".$CodOrganismo."' AND
						tnec.CodTipoProceso = '".$CodTipoProceso."' AND
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
						cpd.CuentaHaber,
						pv.CodCuentaPub20,
						cpd.CuentaDebePub20,
						cpd.CuentaHaberPub20
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
						tnec.CodTipoNom = '".$CodTipoNom."' AND
						tnec.Periodo = '".$Periodo."' AND
						tnec.CodOrganismo = '".$CodOrganismo."' AND
						tnec.CodTipoProceso = '".$CodTipoProceso."' AND
						c.Tipo = 'A'
					GROUP BY c.CodPersona, cpd.cod_partida)
					UNION
					(SELECT
						rj.Demandante AS CodProveedor,
						'".$_PARAMETRO['TIPODOCCXP']."' AS CodTipoDocumento,
						'04' AS Ficha,
						SUM(tnec.Monto) AS MontoIngreso,
						cpd.cod_partida,
						pv.CodCuenta,
						cpd.CuentaDebe,
						cpd.CuentaHaber,
						pv.CodCuentaPub20,
						cpd.CuentaDebePub20,
						cpd.CuentaHaberPub20
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
						tnec.CodTipoNom = '".$CodTipoNom."' AND
						tnec.Periodo = '".$Periodo."' AND
						tnec.CodOrganismo = '".$CodOrganismo."' AND
						tnec.CodTipoProceso = '".$CodTipoProceso."'
					GROUP BY rj.Demandante, cpd.cod_partida)";
		} else {
			$sql = "(SELECT
						mp1.CodPersona AS CodProveedor,
						'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
						'01' AS Ficha,
						SUM(tnec.Monto) AS MontoIngreso,
						cpd.cod_partida,
						pv.CodCuenta,
						cpd.CuentaDebe,
						cpd.CuentaHaber,
						pv.CodCuentaPub20,
						cpd.CuentaDebePub20,
						cpd.CuentaHaberPub20
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
						tnec.CodTipoNom = '".$CodTipoNom."' AND
						tnec.Periodo = '".$Periodo."' AND
						tnec.CodOrganismo = '".$CodOrganismo."' AND
						tnec.CodTipoProceso = '".$CodTipoProceso."' AND
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
						cpd.CuentaHaber,
						pv.CodCuentaPub20,
						cpd.CuentaDebePub20,
						cpd.CuentaHaberPub20
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
						tnec.CodTipoNom = '".$CodTipoNom."' AND
						tnec.Periodo = '".$Periodo."' AND
						tnec.CodOrganismo = '".$CodOrganismo."' AND
						tnec.CodTipoProceso = '".$CodTipoProceso."' AND
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
						cpd.CuentaHaber,
						pv.CodCuentaPub20,
						cpd.CuentaDebePub20,
						cpd.CuentaHaberPub20
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
						tnec.CodTipoNom = '".$CodTipoNom."' AND
						tnec.Periodo = '".$Periodo."' AND
						tnec.CodOrganismo = '".$CodOrganismo."' AND
						tnec.CodTipoProceso = '".$CodTipoProceso."' AND
						c.Tipo = 'A'
					GROUP BY c.CodPersona, cpd.cod_partida)
					UNION
					(SELECT
						rj.Demandante AS CodProveedor,
						'".$_PARAMETRO['TIPODOCCXP']."' AS CodTipoDocumento,
						'04' AS Ficha,
						SUM(tnec.Monto) AS MontoIngreso,
						cpd.cod_partida,
						pv.CodCuenta,
						cpd.CuentaDebe,
						cpd.CuentaHaber,
						pv.CodCuentaPub20,
						cpd.CuentaDebePub20,
						cpd.CuentaHaberPub20
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
						tnec.CodTipoNom = '".$CodTipoNom."' AND
						tnec.Periodo = '".$Periodo."' AND
						tnec.CodOrganismo = '".$CodOrganismo."' AND
						tnec.CodTipoProceso = '".$CodTipoProceso."'
					GROUP BY rj.Demandante, cpd.cod_partida)";
		}	fwrite($__archivo, $sql.";\n\n");
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$Linea=0;
		while ($field = mysql_fetch_array($query)) {	$Linea++;
			unset($field_descuento);
			unset($field_adelanto);
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
							tnec2.CodTipoNom = '".$CodTipoNom."' AND
							tnec2.Periodo = '".$Periodo."' AND
							tnec2.CodOrganismo = '".$CodOrganismo."' AND
							tnec2.CodTipoProceso = '".$CodTipoProceso."' AND
							c2.Tipo = 'D' AND
							c2.FlagRetencion = 'N' AND
							cpd2.cod_partida = '".$field['cod_partida']."'
						GROUP BY tnec2.CodOrganismo";
				$query_descuento = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_descuento) != 0) $field_descuento = mysql_fetch_array($query_descuento);
				//	si el proceso es fin de mes
				if ($CodTipoProceso == "FIN") {
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
								tnec.CodTipoNom = '".$CodTipoNom."' AND
								tnec.Periodo = '".$Periodo."' AND
								tnec.CodOrganismo = '".$CodOrganismo."' AND
								tnec.CodTipoProceso = 'ADE' AND
								c.Tipo = 'I' AND
								cpd.cod_partida = '".$field['cod_partida']."'
							GROUP BY o.CodPersona, cpd.cod_partida";
					$query_adelanto = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
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
							tnec2.CodTipoNom = '".$CodTipoNom."' AND
							tnec2.Periodo = '".$Periodo."' AND
							tnec2.CodOrganismo = '".$CodOrganismo."' AND
							tnec2.CodTipoProceso = '".$CodTipoProceso."' AND
							c2.Tipo = 'D' AND
							c2.FlagRetencion = 'N' AND
							cpd2.cod_partida = '".$field['cod_partida']."'
						GROUP BY tnec2.CodOrganismo";
				$query_descuento = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_descuento) != 0) $field_descuento = mysql_fetch_array($query_descuento);
				//	si el proceso es fin de mes
				if ($CodTipoProceso == "FIN") {
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
								tnec.CodTipoNom = '".$CodTipoNom."' AND
								tnec.Periodo = '".$Periodo."' AND
								tnec.CodOrganismo = '".$CodOrganismo."' AND
								tnec.CodTipoProceso = 'ADE' AND
								c.Tipo = 'I' AND
								cpd.cod_partida = '".$field['cod_partida']."'
							GROUP BY mp1.CodPersona, cpd.cod_partida";
					$query_adelanto = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					if (mysql_num_rows($query_adelanto) != 0) $field_adelanto = mysql_fetch_array($query_adelanto);
				}
			}
			//	valido las cuentas
			if ($field['Ficha'] == "04") {
				if ($field['CuentaDebe'] != "") $CodCuenta = $field['CuentaDebe'];
				else $CodCuenta = $field['CuentaHaber'];
				if ($field['CuentaDebePub20'] != "") $CodCuentaPub20 = $field['CuentaDebePub20'];
				else $CodCuentaPub20 = $field['CuentaHaberPub20'];
			} else {
				$CodCuenta = $field['CodCuenta'];
				$CodCuentaPub20 = $field['CodCuentaPub20'];
			}
			//	montos
			$cod_partida = $field['cod_partida'];
			$NroDocumento = $CodOrganismo.$PeriodoAnio.$PeriodoMes.$CodTipoNom.$CodTipoProceso.$field['Ficha'];
			$Monto = floatval($field['MontoIngreso']) - floatval($field_descuento['MontoDescuento']) - floatval($field_adelanto['MontoAdelanto']);
			//	inserto la cuenta
			$sql = "INSERT INTO pr_obligacionescuenta
					SET
						CodProveedor = '".$field['CodProveedor']."',
						CodTipoDocumento = '".$field['CodTipoDocumento']."',
						NroDocumento = '".$NroDocumento."',
						Linea = '".$Linea."',
						Descripcion = '".$field['Descripcion']."',
						Monto = '".$Monto."',
						CodCentroCosto = '".$_PARAMETRO['CCOSTOPR']."',
						CodCuenta = '".$CodCuenta."',
						CodCuentaPub20 = '".$CodCuentaPub20."',
						cod_partida = '".$field['cod_partida']."',
						FlagNoAfectoIGV = 'N',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	consulto las retenciones a insertar
		if ($CodTipoProceso != "BVC") {
			$sql = "(SELECT
						mp2.CodPersona AS CodProveedor,
						'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
						'01' AS Ficha,
						SUM(tnec.Monto) AS MontoImpuesto,
						c.CodConcepto AS CodRetencion,
						cpd.CuentaHaber AS CodCuenta,
						cpd.CuentaHaberPub20 AS CodCuentaPub20
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
						tnec.CodTipoNom = '".$CodTipoNom."' AND
						tnec.Periodo = '".$Periodo."' AND
						tnec.CodOrganismo = '".$CodOrganismo."' AND
						tnec.CodTipoProceso = '".$CodTipoProceso."' AND
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
						cpd.CuentaHaber AS CodCuenta,
						cpd.CuentaHaberPub20 AS CodCuentaPub20
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
						tnec.CodTipoNom = '".$CodTipoNom."' AND
						tnec.Periodo = '".$Periodo."' AND
						tnec.CodOrganismo = '".$CodOrganismo."' AND
						tnec.CodTipoProceso = '".$CodTipoProceso."' AND
						c.Tipo = 'D' AND
						c.FlagRetencion = 'S'
					 GROUP BY tnec.CodOrganismo, CodRetencion)
					 ORDER BY Ficha";
		} else {
			$sql = "(SELECT
						mp1.CodPersona AS CodProveedor,
						'".$field_doc['CodTipoDocumento']."' AS CodTipoDocumento,
						'01' AS Ficha,
						SUM(tnec.Monto) AS MontoImpuesto,
						c.CodConcepto AS CodRetencion,
						cpd.CuentaHaber AS CodCuenta,
						cpd.CuentaHaberPub20 AS CodCuentaPub20
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
						tnec.CodTipoNom = '".$CodTipoNom."' AND
						tnec.Periodo = '".$Periodo."' AND
						tnec.CodOrganismo = '".$CodOrganismo."' AND
						tnec.CodTipoProceso = '".$CodTipoProceso."' AND
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
						cpd.CuentaHaber AS CodCuenta,
						cpd.CuentaHaberPub20 AS CodCuentaPub20
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
						tnec.CodTipoNom = '".$CodTipoNom."' AND
						tnec.Periodo = '".$Periodo."' AND
						tnec.CodOrganismo = '".$CodOrganismo."' AND
						tnec.CodTipoProceso = '".$CodTipoProceso."' AND
						c.Tipo = 'D' AND
						c.FlagRetencion = 'S'
					 GROUP BY tnec.CodOrganismo, CodRetencion)
					 ORDER BY Ficha";
		}	fwrite($__archivo, $sql.";\n\n");
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$Linea=0;
		while ($field = mysql_fetch_array($query)) {	$Linea++;
			$NroDocumento = $CodOrganismo.$PeriodoAnio.$PeriodoMes.$CodTipoNom.$CodTipoProceso.$field['Ficha'];
			//	inserto las retenciones
			$sql = "INSERT INTO pr_obligacionesretenciones
					SET
						CodProveedor = '".$field['CodProveedor']."',
						CodTipoDocumento = '".$field['CodTipoDocumento']."',
						NroDocumento = '".$NroDocumento."',
						Linea = '".$Linea."',
						CodConcepto = '".$field['CodRetencion']."',
						MontoImpuesto = '".$field['MontoImpuesto']."',
						MontoAfecto = '".$field['MontoAfecto']."',
						CodCuenta = '".$field['CodCuenta']."',
						CodCuentaPub20 = '".$field['CodCuentaPub20']."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	generar
	elseif ($accion == "generar") {
		mysql_query("BEGIN");
		//	-----------------
		list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
		$PeriodoActual = "$AnioActual-$MesActual";
		list($PeriodoAnio, $PeriodoMes) = split("[-]", $Periodo);

		if ($detalles_bancos != "" || $detalles_cheques != "" || $detalles_terceros != ""  || $detalles_judiciales != "" ) {
			if ($frm == "bancos") $detalles_ficha = $detalles_bancos;
			elseif ($frm == "cheques") $detalles_ficha = $detalles_cheques;
			elseif ($frm == "terceros") $detalles_ficha = $detalles_terceros;
			elseif ($frm == "judiciales") $detalles_ficha = $detalles_judiciales;
			
			$detalles = split(";", $detalles_ficha);
			foreach ($detalles as $detalle) {
				list($CodProveedor, $CodTipoDocumento, $NroDocumento, $TipoObligacion) = split("[_]", $detalle);
		
				//	verifico si la obligacion transferida a cxp esta anulada
				$sql = "SELECT
							po.FlagTransferido,
							ao.Estado
						FROM
							pr_obligaciones po
							INNER JOIN ap_obligaciones ao ON (po.CodProveedor = ao.CodProveedor AND
															  po.CodTipoDocumento = ao.CodTipoDocumento AND
															  po.NroDocumento = ao.NroDocumento)
						WHERE
							ao.CodProveedor = '".$CodProveedor."' AND
							ao.CodTipoDocumento = '".$CodTipoDocumento."' AND
							ao.NroDocumento = '".$NroDocumento."' AND
							ao.Estado <> 'AN'";
				$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query) != 0) die("Algunas obligaciones seleccionadas se encuentran actualmente transferidas a CxP<br />Debe anular las obligaciones transferidas para calcularlas nuevamente.");
				
				//	verifico presupuesto
				if ($TipoObligacion != "04") {
					$sql = "SELECT cod_partida, Monto
							FROM pr_obligacionescuenta
							WHERE
								CodProveedor = '".$CodProveedor."' AND
								CodTipoDocumento = '".$CodTipoDocumento."' AND
								NroDocumento = '".$NroDocumento."'";
					$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					while ($field = mysql_fetch_array($query)) {
						if (!valPresupuesto($CodOrganismo, substr($PeriodoActual, 0, 4), $field['cod_partida'], $field['Monto'])) die("Se encontr&oacute; la partida <strong>$field[cod_partida]</strong> sin disponibilidad presupuestaria");
					}
				}
				
				//	elimino
				##	obligacion
				$sql = "DELETE FROM ap_obligaciones
						WHERE
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'";
				$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				##	cuentas
				$sql = "DELETE FROM ap_obligacionescuenta
						WHERE
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'";
				$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				##	impuestos/retenciones
				$sql = "DELETE FROM ap_obligacionesimpuesto
						WHERE
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'";
				$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				##	causados
				$sql = "DELETE FROM ap_distribucionobligacion
						WHERE
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'";
				$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				##	compromisos
				$sql = "DELETE FROM lg_distribucioncompromisos
						WHERE
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'";
				$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				//	si es pago de retenciones no afecta presupuesto
				if ($TipoObligacion == "04") {
					$FlagCompromiso = "N";
					$FlagPresupuesto = "N";
				} else {
					$FlagCompromiso = "S";
					$FlagPresupuesto = "S";
				}
				
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
							CodCuentaPub20,
							CodCentroCosto,
							TipoObligacion
						FROM pr_obligaciones
						WHERE
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'";
				$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				while ($field = mysql_fetch_array($query)) {
					//	inserto la obligacion
					$NroRegistro = getCodigo_2("ap_obligaciones", "NroRegistro", "CodOrganismo", $CodOrganismo, 6);
					$sql = "INSERT INTO ap_obligaciones
							SET
								CodProveedor = '".$field['CodProveedor']."',
								CodTipoDocumento = '".$field['CodTipoDocumento']."',
								NroDocumento = '".$field['NroDocumento']."',
								NroControl = '".$field['NroDocumento']."',
								CodOrganismo = '".$field['CodOrganismo']."',
								NroCuenta = '".$field['NroCuenta']."',
								CodTipoPago = '".$field['CodTipoPago']."',
								FechaRegistro = NOW(),
								FechaFactura = NOW(),
								CodTipoServicio = '".$field['CodTipoServicio']."',
								ReferenciaTipoDocumento = 'NO',
								ReferenciaNroDocumento = '".$field['NroDocumento']."',
								MontoObligacion = '".$field['MontoObligacion']."',
								MontoImpuestoOtros = '-".$field['MontoImpuestoOtros']."',
								MontoNoAfecto = '".$field['MontoNoAfecto']."',
								IngresadoPor = '".$_SESSION['CODPERSONA_ACTUAL']."',
								RevisadoPor = '".$_SESSION['CODPERSONA_ACTUAL']."',
								FechaPreparacion = NOW(),
								FechaRevision = NOW(),
								NroRegistro = '".$NroRegistro."',
								Comentarios = '".$field['Comentarios']."',
								ComentariosAdicional = '".$field['ComentariosAdicional']."',
								CodProveedorPagar = '".$field['CodProveedorPagar']."',
								FechaDocumento = NOW(),
								FechaVencimiento = NOW(),
								FechaRecepcion = NOW(),
								FechaProgramada = NOW(),
								Estado = 'PR',
								CodCuenta = '".$field['CodCuenta']."',
								CodCuentaPub20 = '".$field['CodCuentaPub20']."',
								Periodo = NOW(),
								CodCentroCosto = '".$field['CodCentroCosto']."',
								FlagCompromiso = '".$FlagCompromiso."',
								FlagPresupuesto = '".$FlagPresupuesto."',
								UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
								UltimaFecha = NOW()";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					
					//	actualizo pr_obligacion
					$sql = "UPDATE pr_obligaciones
							SET
								NroRegistro = '".$NroRegistro."',
								FlagTransferido = 'S'
							WHERE
								CodProveedor = '".$CodProveedor."' AND
								CodTipoDocumento = '".$CodTipoDocumento."' AND
								NroDocumento = '".$NroDocumento."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
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
							CodCuentaPub20,
							cod_partida,
							CodCentroCosto
						FROM pr_obligacionescuenta
						WHERE
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'
						GROUP BY cod_partida";
				$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				while ($field = mysql_fetch_array($query)) {
					//	inserto la cuenta
					$sql = "INSERT INTO ap_obligacionescuenta
							SET
								CodProveedor = '".$field['CodProveedor']."',
								CodTipoDocumento = '".$field['CodTipoDocumento']."',
								NroDocumento = '".$field['NroDocumento']."',
								Linea = '".$field['Linea']."',
								Descripcion = '".$field['Descripcion']."',
								Monto = '".$field['Monto']."',
								CodCuenta = '".$field['CodCuenta']."',
								CodCuentaPub20 = '".$field['CodCuentaPub20']."',
								cod_partida = '".$field['cod_partida']."',
								TipoOrden = 'NO',
								NroOrden = '".$field['NroDocumento']."',
								FlagNoAfectoIGV = 'S',
								CodCentroCosto = '".$field['CodCentroCosto']."',
								UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
								UltimaFecha = NOW()";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					
					//	inserto la distribucion
					$sql = "INSERT INTO ap_distribucionobligacion
							SET
								CodProveedor = '".$field['CodProveedor']."',
								CodTipoDocumento = '".$field['CodTipoDocumento']."',
								NroDocumento = '".$field['NroDocumento']."',
								Monto = '".$field['Monto']."',
								CodCuenta = '".$field['CodCuenta']."',
								CodCuentaPub20 = '".$field['CodCuentaPub20']."',
								cod_partida = '".$field['cod_partida']."',
								CodCentroCosto = '".$field['CodCentroCosto']."',
								Periodo = '".$PeriodoActual."',
								Anio = '".substr($PeriodoActual, 0, 4)."',
								FlagCompromiso = '".$FlagCompromiso."',
								Estado = 'PE',
								UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
								UltimaFecha = NOW()";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					
					if ($FlagCompromiso == "S") {
						//	inserto la distribucion
						$sql = "INSERT INTO lg_distribucioncompromisos
								SET
									Anio = '".substr($PeriodoActual, 0, 4)."',
									CodOrganismo = '".$CodOrganismo."',
									CodProveedor = '".$field['CodProveedor']."',
									CodTipoDocumento = '".$field['CodTipoDocumento']."',
									NroDocumento = '".$field['NroDocumento']."',
									Secuencia = '".$field['Linea']."',
									Linea = '1',
									Mes = '".substr($Periodo, 5, 2)."',
									CodCentroCosto = '".$field['CodCentroCosto']."',
									cod_partida = '".$field['cod_partida']."',
									Monto = '".$field['Monto']."',
									Periodo = '".$PeriodoActual."',
									Estado = 'PE',
									UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
									UltimaFecha = NOW()";
						$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
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
							CodCuenta,
							CodCuentaPub20
						FROM pr_obligacionesretenciones
						WHERE
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'";
				$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				while ($field = mysql_fetch_array($query)) {
					//	inserto la obligacion
					$sql = "INSERT INTO ap_obligacionesimpuesto
							SET
								CodProveedor = '".$field['CodProveedor']."',
								CodTipoDocumento = '".$field['CodTipoDocumento']."',
								NroDocumento = '".$field['NroDocumento']."',
								Linea = '".$field['Linea']."',
								CodConcepto = '".$field['CodConcepto']."',
								MontoImpuesto = '-".$field['MontoImpuesto']."',
								MontoAfecto = '".$field['MontoAfecto']."',
								FlagProvision = '".$field['FlagProvision']."',
								CodCuenta = '".$field['CodCuenta']."',
								CodCuentaPub20 = '".$field['CodCuentaPub20']."',
								UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
								UltimaFecha = NOW()";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	consolidar
	elseif ($accion == "consolidar") {
		mysql_query("BEGIN");
		//	-----------------
		list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
		$PeriodoActual = "$AnioActual-$MesActual";
		list($PeriodoAnio, $PeriodoMes) = split("[-]", $Periodo);
		
		//	consulto el proveedor del organismo
		$sql = "SELECT
					o.CodPersona,
					p.NomCompleto AS NomPersona
				FROM
					mastorganismos o
					INNER JOIN mastpersonas p ON (o.CodPersona = p.CodPersona)
				WHERE o.CodOrganismo = '".$CodOrganismo."'";
		$query_proveedor = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_proveedor) != 0) $field_proveedor = mysql_fetch_array($query_proveedor);
		else die("No se encontr&oacute; un Proveedor para asociar la Obligaci&oacute;n!");

		if ($detalles_bancos != "") {
			$filtro = "";
			$detalles = split(";", $detalles_bancos);
			foreach ($detalles as $detalle) {
				list($CodProveedor, $CodTipoDocumento, $NroDocumento, $TipoObligacion) = split("[_]", $detalle);
		
				//	verifico si la obligacion transferida a cxp esta anulada
				$sql = "SELECT
							po.FlagTransferido,
							ao.Estado
						FROM
							pr_obligaciones po
							INNER JOIN ap_obligaciones ao ON (po.CodProveedor = ao.CodProveedor AND
															  po.CodTipoDocumento = ao.CodTipoDocumento AND
															  po.NroDocumento = ao.NroDocumento)
						WHERE
							ao.CodProveedor = '".$CodProveedor."' AND
							ao.CodTipoDocumento = '".$CodTipoDocumento."' AND
							ao.NroDocumento = '".$NroDocumento."' AND
							ao.Estado <> 'AN'";
				$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query) != 0) die("Algunas obligaciones seleccionadas se encuentran actualmente transferidas a CxP<br />Debe anular las obligaciones transferidas para calcularlas nuevamente.");
				
				if ($filtro != "") $filtro .= " OR ";
				$filtro .= "(CodProveedor = '".$CodProveedor."' AND
							 CodTipoDocumento = '".$CodTipoDocumento."' AND
							 NroDocumento = '".$NroDocumento."')";
			}
			
			//	consulto la tabla general
			$sql = "SELECT
						TipoObligacion,
						CodOrganismo,
						CodTipoNom,
						Periodo,
						PeriodoNomina,
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
						CodCuentaPub20,
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
				$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				//	consulto el numero de obligaciones que he consolidado
				$sql = "SELECT *
						FROM pr_obligaciones
						WHERE
							CodProveedor = '".$field_proveedor['CodPersona']."' AND
							CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
							NroDocumento LIKE '".$field['NroDocumento']."-%'";
				$query_numero = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				$rows = intval(mysql_num_rows($query_numero));	$rows++;
				
				//	obtengo algunos valores a insertar
				$NroDocumento = $field['NroDocumento']."-".$rows;
				$NroCuenta = getCuentaBancariaDefault($CodOrganismo, $field['CodTipoPago']);
				
				//	inserto la obligacion
				$sql = "INSERT INTO pr_obligaciones
						SET
							TipoObligacion = '".$field['TipoObligacion']."',
							CodOrganismo = '".$field['CodOrganismo']."',
							CodTipoNom = '".$field['CodTipoNom']."',
							Periodo = '".$field['Periodo']."',
							PeriodoNomina = '".$field['PeriodoNomina']."',
							CodTipoProceso = '".$field['CodTipoProceso']."',
							CodProveedor = '".$field_proveedor['CodPersona']."',
							CodTipoDocumento = '".$field['CodTipoDocumento']."',
							NroDocumento = '".$NroDocumento."',
							NroCuenta = '".$NroCuenta."',
							CodTipoPago = '".$field['CodTipoPago']."',
							CodTipoServicio = '".$field['CodTipoServicio']."',
							FechaRegistro = NOW(),
							CodProveedorPagar = '".$field_proveedor['CodPersona']."',
							NomProveedorPagar = '".$field_proveedor['NomPersona']."',
							Comentarios = '".$field['Comentarios']."',
							ComentariosAdicional = '".$field['ComentariosAdicional']."',
							MontoObligacion = '".$field['MontoObligacion']."',
							MontoNoAfecto = '".$field['MontoNoAfecto']."',
							MontoImpuestoOtros = '".$field['MontoImpuestoOtros']."',
							CodCuenta = '".$field['CodCuenta']."',
							CodCuentaPub20 = '".$field['CodCuentaPub20']."',
							CodCentroCosto = '".$field['CodCentroCosto']."',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
			
			//	consulto las cuentas
			$sql = "SELECT
						CodTipoDocumento,
						NroDocumento,
						CodCentroCosto,
						CodCuenta,
						CodCuentaPub20,
						cod_partida,
						FlagNoAfectoIGV,
						SUM(Monto) AS Monto
					FROM pr_obligacionescuenta
					WHERE $filtro
					GROUP BY CodTipoDocumento, NroDocumento";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$i=0;
			while($field = mysql_fetch_array($query)) {	$i++;
				//	elimino las seleccionadas
				$sql = "DELETE FROM pr_obligacionescuenta WHERE $filtro";
				$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
				//	inserto la obligacion x cuenta
				$sql = "INSERT INTO pr_obligacionescuenta
						SET
							CodProveedor = '".$field_proveedor['CodPersona']."',
							CodTipoDocumento = '".$field['CodTipoDocumento']."',
							NroDocumento = '".$NroDocumento."',
							Linea = '".$i."',
							CodCentroCosto = '".$field['CodCentroCosto']."',
							CodCuenta = '".$field['CodCuenta']."',
							CodCuentaPub20 = '".$field['CodCuentaPub20']."',
							cod_partida = '".$field['cod_partida']."',
							FlagNoAfectoIGV = '".$field['FlagNoAfectoIGV']."',
							Monto = '".$field['Monto']."',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
			
			//	consulto las retenciones
			$sql = "SELECT
						CodTipoDocumento,
						NroDocumento,
						CodConcepto,
						SUM(MontoImpuesto) AS MontoImpuesto,
						SUM(MontoAfecto) AS MontoAfecto,
						CodCuenta,
						CodCuentaPub20,
						FlagProvision
					FROM pr_obligacionesretenciones
					WHERE MontoImpuesto > 0 AND ($filtro)
					GROUP BY CodTipoDocumento, NroDocumento";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$i=0;
			while($field = mysql_fetch_array($query)) {	$i++;
				//	elimino las seleccionadas
				$sql = "DELETE FROM pr_obligacionesretenciones WHERE $filtro";
				$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
				//	inserto la obligacion retenciones
				$sql = "INSERT INTO pr_obligacionesretenciones
						SET
							CodProveedor = '".$field_proveedor['CodPersona']."',
							CodTipoDocumento = '".$field['CodTipoDocumento']."',
							NroDocumento = '".$nrodocumento."',
							Linea = '".$i."',
							CodConcepto = '".$field['CodConcepto']."',
							MontoImpuesto = '".$field['MontoImpuesto']."',
							MontoAfecto = '".$field['MontoAfecto']."',
							CodCuenta = '".$field['CodCuenta']."',
							CodCuentaPub20 = '".$field['CodCuentaPub20']."',
							FlagProvision = '".$field['FlagProvision']."',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	-----------------
		mysql_query("COMMIT");
	}*/
	
	//	verificar
	elseif ($accion == "verificar") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizo
		$sql = "UPDATE pr_obligaciones
				SET FlagVerificado = 'S'
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
}

//	conceptos
elseif ($modulo == "conceptos") {
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-----------------
		//	genero codigo
		$CodConcepto = getCodigo("pr_concepto", "CodConcepto", 4);
		
		//	inserto
		$sql = "INSERT INTO pr_concepto
				SET
					CodConcepto = '".$CodConcepto."',
					Descripcion = '".changeUrl($Descripcion)."',
					Tipo = '".$Tipo."',
					TextoImpresion = '".changeUrl($TextoImpresion)."',
					PlanillaOrden = '".$PlanillaOrden."',
					Formula = '".changeUrl($Formula)."',
					FormulaEditor = '".changeUrl($FormulaEditor)."',
					FlagFormula = '".$FlagFormula."',
					FlagAutomatico = '".$FlagAutomatico."',
					Abreviatura = '".changeUrl($Abreviatura)."',
					FlagBono = '".$FlagBono."',
					FlagRetencion = '".$FlagRetencion."',
					FlagObligacion = '".$FlagObligacion."',
					CodPersona = '".$CodPersona."',
					FlagBonoRemuneracion = '".$FlagBonoRemuneracion."',
					FlagRelacionIngreso = '".$FlagRelacionIngreso."',
					FlagJubilacion = '".$FlagJubilacion."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	tipos de nomina
		if ($detalles_nominas != "") {
			$nominas = split(";char:tr;", $detalles_nominas);
			foreach ($nominas as $_CodTipoNom) {
				//	inserto
				$sql = "INSERT INTO pr_conceptotiponomina
						SET
							CodConcepto = '".$CodConcepto."',
							CodTipoNom = '".$_CodTipoNom."'";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		
		//	tipos de proceso
		if ($detalles_procesos != "") {
			$procesos = split(";char:tr;", $detalles_procesos);
			foreach ($procesos as $_CodTipoProceso) {
				//	inserto
				$sql = "INSERT INTO pr_conceptoproceso
						SET
							CodConcepto = '".$CodConcepto."',
							CodTipoProceso = '".$_CodTipoProceso."'";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizar
		$sql = "UPDATE pr_concepto
				SET
					Descripcion = '".changeUrl($Descripcion)."',
					Tipo = '".$Tipo."',
					TextoImpresion = '".changeUrl($TextoImpresion)."',
					PlanillaOrden = '".$PlanillaOrden."',
					Formula = '".changeUrl($Formula)."',
					FormulaEditor = '".changeUrl($FormulaEditor)."',
					FlagFormula = '".$FlagFormula."',
					FlagAutomatico = '".$FlagAutomatico."',
					Abreviatura = '".changeUrl($Abreviatura)."',
					FlagBono = '".$FlagBono."',
					FlagRetencion = '".$FlagRetencion."',
					FlagObligacion = '".$FlagObligacion."',
					CodPersona = '".$CodPersona."',
					FlagBonoRemuneracion = '".$FlagBonoRemuneracion."',
					FlagRelacionIngreso = '".$FlagRelacionIngreso."',
					FlagJubilacion = '".$FlagJubilacion."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodConcepto = '".$CodConcepto."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	tipos de nomina
		$sql = "DELETE FROM pr_conceptotiponomina WHERE CodConcepto = '".$CodConcepto."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if ($detalles_nominas != "") {
			$nominas = split(";char:tr;", $detalles_nominas);
			foreach ($nominas as $_CodTipoNom) {
				//	inserto
				$sql = "INSERT INTO pr_conceptotiponomina
						SET
							CodConcepto = '".$CodConcepto."',
							CodTipoNom = '".$_CodTipoNom."'";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		
		//	tipos de proceso
		$sql = "DELETE FROM pr_conceptoproceso WHERE CodConcepto = '".$CodConcepto."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if ($detalles_procesos != "") {
			$procesos = split(";char:tr;", $detalles_procesos);
			foreach ($procesos as $_CodTipoProceso) {
				//	inserto
				$sql = "INSERT INTO pr_conceptoproceso
						SET
							CodConcepto = '".$CodConcepto."',
							CodTipoProceso = '".$_CodTipoProceso."'";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion == "eliminar") {
		mysql_query("BEGIN");
		//	-----------------
		//	elimino
		$sql = "DELETE FROM pr_concepto WHERE CodConcepto = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
}

//	conceptos
elseif ($modulo == "conceptos_perfil") {
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-----------------
		//	genero codigo
		$CodPerfilConcepto = getCodigo("pr_conceptoperfil", "CodPerfilConcepto", 4);
		
		//	inserto
		$sql = "INSERT INTO pr_conceptoperfil
				SET
					CodPerfilConcepto = '".$CodPerfilConcepto."',
					Descripcion = '".changeUrl($Descripcion)."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizar
		$sql = "UPDATE pr_conceptoperfil
				SET
					Descripcion = '".changeUrl($Descripcion)."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodPerfilConcepto = '".$CodPerfilConcepto."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	perfil de conceptos
	elseif ($accion == "conceptos") {
		mysql_query("BEGIN");
		//	-----------------
		//	conceptos
		$sql = "DELETE FROM pr_conceptoperfildetalle WHERE CodPerfilConcepto = '".$CodPerfilConcepto."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if ($detalles_conceptos != "") {
			$conceptos = split(";char:tr;", $detalles_conceptos);
			foreach ($conceptos as $_linea) {
				list($_CodTipoProceso, $_CodConcepto, $_cod_partida, $_CuentaDebe, $_CuentaDebePub20, $_FlagDebeCC, $_CuentaHaber, $_CuentaHaberPub20, $_FlagHaberCC) = split(";char:td;", $_linea);
				//	inserto
				$sql = "INSERT INTO pr_conceptoperfildetalle
						SET
							CodPerfilConcepto = '".$CodPerfilConcepto."',
							CodTipoProceso = '".$_CodTipoProceso."',
							CodConcepto = '".$_CodConcepto."',
							cod_partida = '".$_cod_partida."',
							CuentaDebe = '".$_CuentaDebe."',
							CuentaDebePub20 = '".$_CuentaDebePub20."',
							FlagDebeCC = '".$_FlagDebeCC."',
							CuentaHaber = '".$_CuentaHaber."',
							CuentaHaberPub20 = '".$_CuentaHaberPub20."',
							FlagHaberCC = '".$_FlagHaberCC."',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion == "eliminar") {
		mysql_query("BEGIN");
		//	-----------------
		//	elimino
		$sql = "DELETE FROM pr_conceptoperfil WHERE CodPerfilConcepto = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
}
?>
