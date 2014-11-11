UPDATE tiponomina
				SET
					Nomina = 'PERIODO DE PRUEBA (NO ACTIVO)',
					TituloBoleta = 'NOMINA PERIODO DE PRUEBA',
					FlagPagoMensual = 'N',
					CodPerfilConcepto = '0001',
					Estado = 'A',
					UltimoUsuario = 'CMARCANO',
					UltimaFecha = NOW()
				WHERE CodTipoNom = 'PP';

DELETE FROM pr_tiponominaperiodo WHERE CodTipoNom = 'PP';

INSERT INTO pr_tiponominaperiodo
					SET
						CodTipoNom = 'PP',
						Periodo = '',
						Mes = '',
						Secuencia = '',
						UltimoUsuario = 'CMARCANO',
						UltimaFecha = NOW();

DELETE FROM pr_tiponominaproceso WHERE CodTipoNom = 'PP';

INSERT INTO pr_tiponominaproceso
						SET
							CodTipoNom = 'PP',
							CodTipoProceso = 'FIN',
							CodTipoDocumento = '',
							UltimoUsuario = 'CMARCANO',
							UltimaFecha = NOW();

INSERT INTO pr_tiponominaproceso
						SET
							CodTipoNom = 'PP',
							CodTipoProceso = 'PRQ',
							CodTipoDocumento = '',
							UltimoUsuario = 'CMARCANO',
							UltimaFecha = NOW();

