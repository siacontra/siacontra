
--
-- Filtros para la tabla `lg_requerimientosdet`
--
ALTER TABLE `lg_requerimientosdet`
  ADD CONSTRAINT `lg_requerimientosdet_ibfk_14` FOREIGN KEY (`CodOrganismo`) REFERENCES `mastorganismos` (`CodOrganismo`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `lg_requerimientosdet_ibfk_15` FOREIGN KEY (`CodUnidad`) REFERENCES `mastunidades` (`CodUnidad`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `lg_requerimientosdet_ibfk_16` FOREIGN KEY (`CodCentroCosto`) REFERENCES `ac_mastcentrocosto` (`CodCentroCosto`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `lg_requerimientosdet_ibfk_17` FOREIGN KEY (`CodCuenta`) REFERENCES `ac_mastplancuenta` (`CodCuenta`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `lg_requerimientosdet_ibfk_18` FOREIGN KEY (`cod_partida`) REFERENCES `pv_partida` (`cod_partida`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `lg_requerimientosdet_ibfk_19` FOREIGN KEY (`CotizacionFormaPago`) REFERENCES `mastformapago` (`CodFormaPago`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `lg_transaccion`
--
ALTER TABLE `lg_transaccion`
  ADD CONSTRAINT `lg_transaccion_ibfk_2` FOREIGN KEY (`CodCentroCosto`) REFERENCES `ac_mastcentrocosto` (`CodCentroCosto`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `lg_verificarimpuordencom`
--
ALTER TABLE `lg_verificarimpuordencom`
  ADD CONSTRAINT `lg_verificarimpuordencom_ibfk_3` FOREIGN KEY (`NroOrden`) REFERENCES `lg_ordencompra` (`NroOrden`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `lg_verificarimpuordencom_ibfk_4` FOREIGN KEY (`CodPersona`) REFERENCES `mastpersonas` (`CodPersona`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `lg_verificarpresuordencom`
--
ALTER TABLE `lg_verificarpresuordencom`
  ADD CONSTRAINT `lg_verificarpresuordencom_ibfk_3` FOREIGN KEY (`NroOrden`) REFERENCES `lg_ordencompra` (`NroOrden`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `lg_verificarpresuordencom_ibfk_4` FOREIGN KEY (`CodPersona`) REFERENCES `mastpersonas` (`CodPersona`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `mastciudades`
--
ALTER TABLE `mastciudades`
  ADD CONSTRAINT `mastciudades_ibfk_1` FOREIGN KEY (`CodMunicipio`) REFERENCES `mastmunicipios` (`CodMunicipio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mastdependencias`
--
ALTER TABLE `mastdependencias`
  ADD CONSTRAINT `mastdependencias_ibfk_1` FOREIGN KEY (`CodOrganismo`) REFERENCES `mastorganismos` (`CodOrganismo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mastempleado`
--
ALTER TABLE `mastempleado`
  ADD CONSTRAINT `mastempleado_ibfk_1` FOREIGN KEY (`CodDependencia`) REFERENCES `mastdependencias` (`CodDependencia`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mastempleado_ibfk_2` FOREIGN KEY (`CodPersona`) REFERENCES `mastpersonas` (`CodPersona`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mastestados`
--
ALTER TABLE `mastestados`
  ADD CONSTRAINT `mastestados_ibfk_1` FOREIGN KEY (`CodPais`) REFERENCES `mastpaises` (`CodPais`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mastmunicipios`
--
ALTER TABLE `mastmunicipios`
  ADD CONSTRAINT `mastmunicipios_ibfk_1` FOREIGN KEY (`CodEstado`) REFERENCES `mastestados` (`CodEstado`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mastorganismos`
--
ALTER TABLE `mastorganismos`
  ADD CONSTRAINT `mastorganismos_ibfk_1` FOREIGN KEY (`CodCiudad`) REFERENCES `mastciudades` (`CodCiudad`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mastproveedores`
--
ALTER TABLE `mastproveedores`
  ADD CONSTRAINT `mastproveedores_ibfk_10` FOREIGN KEY (`CodTipoServicio`) REFERENCES `masttiposervicio` (`CodTipoServicio`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `mastproveedores_ibfk_12` FOREIGN KEY (`CodProveedor`) REFERENCES `mastpersonas` (`CodPersona`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `mastproveedores_ibfk_7` FOREIGN KEY (`CodTipoDocumento`) REFERENCES `ap_tipodocumento` (`CodTipoDocumento`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `mastproveedores_ibfk_8` FOREIGN KEY (`CodTipoPago`) REFERENCES `masttipopago` (`CodTipoPago`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `mastproveedores_ibfk_9` FOREIGN KEY (`CodFormaPago`) REFERENCES `mastformapago` (`CodFormaPago`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `pv_presupuesto`
--
ALTER TABLE `pv_presupuesto`
  ADD CONSTRAINT `pv_presupuesto_ibfk_2` FOREIGN KEY (`CodPresupuesto`) REFERENCES `pv_antepresupuesto` (`CodAnteproyecto`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `rh_beneficiarioutiles`
--
ALTER TABLE `rh_beneficiarioutiles`
  ADD CONSTRAINT `fk_reference_1` FOREIGN KEY (`codutilesayuda`) REFERENCES `rh_utilesayuda` (`codutilesayuda`);

--
-- Filtros para la tabla `rh_familarutilesbeneficio`
--
ALTER TABLE `rh_familarutilesbeneficio`
  ADD CONSTRAINT `fk_reference_2` FOREIGN KEY (`codbeneficiarioutiles`) REFERENCES `rh_beneficiarioutiles` (`codbeneficiarioutiles`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`CodPersona`) REFERENCES `mastpersonas` (`CodPersona`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
