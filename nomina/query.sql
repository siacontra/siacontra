SELECT
					po.FlagTransferido,
					ao.Estado
				FROM
					pr_obligaciones po
					INNER JOIN ap_obligaciones ao ON (po.CodProveedor = ao.CodProveedor AND
													  po.CodTipoDocumento = ao.CodTipoDocumento AND
													  po.NroDocumento = ao.NroDocumento)
				WHERE
					po.CodOrganismo = '0001' AND
					po.CodTipoNom = '01' AND
					po.Periodo = '2012-11' AND
					po.CodTipoProceso = 'ADE';

SELECT
					CodProveedor,
					CodTipoDocumento,
					NroDocumento
				FROM pr_obligaciones
				WHERE
					CodOrganismo = '0001' AND
					CodTipoNom = '01' AND
					Periodo = '2012-11' AND
					CodTipoProceso = 'ADE';

DELETE FROM pr_obligaciones
				WHERE
					CodOrganismo = '0001' AND
					CodTipoNom = '01' AND
					Periodo = '2012-11' AND
					CodTipoProceso = 'ADE';

SELECT CodTipoDocumento
				FROM pr_tiponominaproceso
				WHERE
					CodTipoNom = '01' AND
					CodTipoProceso = 'ADE';

(SELECT
							tn.Nomina,
							tp.Descripcion AS NomProceso,
							o.CodPersona AS CodProveedor,
							mp2.NomCompleto AS NomProveedor,
							'NE' AS CodTipoDocumento,
							me.CodTipoPago,
							'NOAFE' AS CodTipoServicio,
							'01' AS Ficha,
							SUM(tnec.Monto) AS MontoIngreso,
							'' AS CodCuenta
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
							tnec.CodTipoNom = '01' AND
							tnec.Periodo = '2012-11' AND
							tnec.CodOrganismo = '0001' AND
							tnec.CodTipoProceso = 'ADE' AND
							c.Tipo = 'I'
						GROUP BY tnec.CodOrganismo)
						UNION
						(SELECT
							tn.Nomina,
							tp.Descripcion AS NomProceso,
							mp1.CodPersona AS CodProveedor,
							mp1.NomCompleto AS NomProveedor,
							'NE' AS CodTipoDocumento,
							me.CodTipoPago,
							'NOAFE' AS CodTipoServicio,
							'02' AS Ficha,
							SUM(tnec.Monto) AS MontoIngreso,
							'' AS CodCuenta
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
							tnec.CodTipoNom = '01' AND
							tnec.Periodo = '2012-11' AND
							tnec.CodOrganismo = '0001' AND
							tnec.CodTipoProceso = 'ADE' AND
							c.Tipo = 'I'
						GROUP BY mp1.CodPersona)
						UNION
						(SELECT
							tn.Nomina,
							tp.Descripcion AS NomProceso,
							c.CodPersona AS CodProveedor,
							mp2.NomCompleto AS NomProveedor,
							'NE' AS CodTipoDocumento,
							p.CodTipoPago,
							'NOAFE' AS CodTipoServicio,
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
							tnec.CodTipoNom = '01' AND
							tnec.Periodo = '2012-11' AND
							tnec.CodOrganismo = '0001' AND
							tnec.CodTipoProceso = 'ADE' AND
							c.Tipo = 'A'
						 GROUP BY c.CodPersona)
						UNION
						(SELECT
							tn.Nomina,
							tp.Descripcion AS NomProceso,
							rj.Demandante AS CodProveedor,
							mp2.NomCompleto AS NomProveedor,
							'PL' AS CodTipoDocumento,
							p.CodTipoPago,
							'NOAFE' AS CodTipoServicio,
							'04' AS Ficha,
							SUM(tnec.Monto) AS MontoIngreso,
							'' AS CodCuenta
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
							tnec.CodTipoNom = '01' AND
							tnec.Periodo = '2012-11' AND
							tnec.CodOrganismo = '0001' AND
							tnec.CodTipoProceso = 'ADE'
						 GROUP BY rj.Demandante);

SELECT SUM(tnec2.Monto) AS MontoDescuento
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
							tnec2.CodTipoNom = '01' AND
							tnec2.Periodo = '2012-11' AND
							tnec2.CodOrganismo = '0001' AND
							tnec2.CodTipoProceso = 'ADE' AND
							c2.Tipo = 'D' AND
							c2.FlagRetencion = 'N' 
						GROUP BY tnec2.CodOrganismo;

SELECT SUM(tnec2.Monto) AS MontoRetencion
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
							tnec2.CodTipoNom = '01' AND
							tnec2.Periodo = '2012-11' AND
							tnec2.CodOrganismo = '0001' AND
							tnec2.CodTipoProceso = 'ADE' AND
							c2.Tipo = 'D' AND
							c2.FlagRetencion = 'S' 
						GROUP BY tnec2.CodOrganismo;

INSERT INTO pr_obligaciones (
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
								'01',
								'0001',
								'01',
								'2012-11',
								'ADE',
								'000163',
								'NE',
								'000120121101ADE01',
								'010200012098',
								'01',
								'NOAFE',
								NOW(),
								'000163',
								'CONTRALORIA DEL ESTADO DELTA AMACURO',
								'PERIODO 2012-11 NOMINA DE EMPLEADOS ADELANTO PRIMERA QUINCENA',
								'PERIODO 2012-11 NOMINA DE EMPLEADOS ADELANTO PRIMERA QUINCENA',
								'130320',
								'130320',
								'0',
								'',
								'1000',
								'EJBOLIVAR',
								NOW()
					);

(SELECT
						o.CodPersona AS CodProveedor,
						'NE' AS CodTipoDocumento,
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
						tnec.CodTipoNom = '01' AND
						tnec.Periodo = '2012-11' AND
						tnec.CodOrganismo = '0001' AND
						tnec.CodTipoProceso = 'ADE' AND
						c.Tipo = 'I'
					GROUP BY o.CodPersona, cpd.cod_partida)
					UNION
					(SELECT
						mp1.CodPersona AS CodProveedor,
						'NE' AS CodTipoDocumento,
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
						tnec.CodTipoNom = '01' AND
						tnec.Periodo = '2012-11' AND
						tnec.CodOrganismo = '0001' AND
						tnec.CodTipoProceso = 'ADE' AND
						c.Tipo = 'I'
					GROUP BY mp1.CodPersona, cpd.cod_partida)
					UNION
					(SELECT
						c.CodPersona AS CodProveedor,
						'NE' AS CodTipoDocumento,
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
						tnec.CodTipoNom = '01' AND
						tnec.Periodo = '2012-11' AND
						tnec.CodOrganismo = '0001' AND
						tnec.CodTipoProceso = 'ADE' AND
						c.Tipo = 'A'
					GROUP BY c.CodPersona, cpd.cod_partida)
					UNION
					(SELECT
						rj.Demandante AS CodProveedor,
						'PL' AS CodTipoDocumento,
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
						tnec.CodTipoNom = '01' AND
						tnec.Periodo = '2012-11' AND
						tnec.CodOrganismo = '0001' AND
						tnec.CodTipoProceso = 'ADE'
					GROUP BY rj.Demandante, cpd.cod_partida);

SELECT SUM(tnec2.Monto) AS MontoDescuento
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
							tnec2.CodTipoNom = '01' AND
							tnec2.Periodo = '2012-11' AND
							tnec2.CodOrganismo = '0001' AND
							tnec2.CodTipoProceso = 'ADE' AND
							c2.Tipo = 'D' AND
							c2.FlagRetencion = 'N' AND
							cpd2.cod_partida = '401.01.01.00'
						GROUP BY tnec2.CodOrganismo;

INSERT INTO pr_obligacionescuenta (
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
								'000163',
								'NE',
								'000120121101ADE01',
								'1',
								'',
								'130080',
								'1000',
								'6110101',
								'401.01.01.00',
								'N',
								'EJBOLIVAR',
								NOW()
					);

SELECT SUM(tnec2.Monto) AS MontoDescuento
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
							tnec2.CodTipoNom = '01' AND
							tnec2.Periodo = '2012-11' AND
							tnec2.CodOrganismo = '0001' AND
							tnec2.CodTipoProceso = 'ADE' AND
							c2.Tipo = 'D' AND
							c2.FlagRetencion = 'N' AND
							cpd2.cod_partida = '401.04.06.00'
						GROUP BY tnec2.CodOrganismo;

INSERT INTO pr_obligacionescuenta (
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
								'000163',
								'NE',
								'000120121101ADE01',
								'2',
								'',
								'240',
								'1000',
								'6110201',
								'401.04.06.00',
								'N',
								'EJBOLIVAR',
								NOW()
					);

(SELECT
						mp2.CodPersona AS CodProveedor,
						'NE' AS CodTipoDocumento,
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
						tnec.CodTipoNom = '01' AND
						tnec.Periodo = '2012-11' AND
						tnec.CodOrganismo = '0001' AND
						tnec.CodTipoProceso = 'ADE' AND
						c.Tipo = 'D' AND
						c.FlagRetencion = 'S'
					 GROUP BY tnec.CodOrganismo, CodRetencion)
					UNION
					(SELECT
						mp1.CodPersona AS CodProveedor,
						'NE' AS CodTipoDocumento,
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
						tnec.CodTipoNom = '01' AND
						tnec.Periodo = '2012-11' AND
						tnec.CodOrganismo = '0001' AND
						tnec.CodTipoProceso = 'ADE' AND
						c.Tipo = 'D' AND
						c.FlagRetencion = 'S'
					 GROUP BY tnec.CodOrganismo, CodRetencion)
					 ORDER BY Ficha;

