-- Filtros para la tabla `lg_verificarpresuordencom`
--
ALTER TABLE `lg_verificarpresuordencom`
  ADD CONSTRAINT `lg_verificarpresuordencom_ibfk_2` FOREIGN KEY (`CodPersona`) REFERENCES `mastpersonas` (`CodPersona`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lg_verificarpresuordencom_ibfk_1` FOREIGN KEY (`NroOrden`) REFERENCES `lg_ordencompra` (`NroOrden`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `mastproveedores_ibfk_1` FOREIGN KEY (`CodProveedor`) REFERENCES `mastpersonas` (`CodPersona`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mastproveedores_ibfk_2` FOREIGN KEY (`CodTipoDocumento`) REFERENCES `ap_tipodocumento` (`CodTipoDocumento`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mastproveedores_ibfk_3` FOREIGN KEY (`CodTipoPago`) REFERENCES `masttipopago` (`CodTipoPago`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mastproveedores_ibfk_4` FOREIGN KEY (`CodFormaPago`) REFERENCES `mastformapago` (`CodFormaPago`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mastproveedores_ibfk_5` FOREIGN KEY (`CodTipoServicio`) REFERENCES `masttiposervicio` (`CodTipoServicio`) ON DELETE CASCADE ON UPDATE CASCADE;

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
