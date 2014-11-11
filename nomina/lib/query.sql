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
							'61101' AS CodCuenta,
							'' AS CodCuentaPub20
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
							tnec.Periodo = '2013-11' AND
							tnec.CodOrganismo = '0001' AND
							tnec.CodTipoProceso = 'PRQ' AND
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
							'61101' AS CodCuenta,
							'' AS CodCuentaPub20
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
							tnec.Periodo = '2013-11' AND
							tnec.CodOrganismo = '0001' AND
							tnec.CodTipoProceso = 'PRQ' AND
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
						 
							tnec.CodTipoNom = '01' AND
							tnec.Periodo = '2013-11' AND
							tnec.CodOrganismo = '0001' AND
							tnec.CodTipoProceso = 'PRQ' AND
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
							'61101' AS CodCuenta,
							'' AS CodCuentaPub20
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
							tnec.Periodo = '2013-11' AND
							tnec.CodOrganismo = '0001' AND
							tnec.CodTipoProceso = 'PRQ'
						 GROUP BY rj.Demandante);

