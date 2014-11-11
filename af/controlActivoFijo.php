<?php
/// ----------------------------------------------------------------------
/// GUARDAR DATOS GENERALES Y DETALLE DE TRANSFERIR ACTIVO FIJO
/// ----------------------------------------------------------------------
if($accion==GuardarTransferidos){
 
 $sa = "select max(Activo) from af_activo"; 
 $qa = mysql_query($sa) or die ($sa.mysql_error());
 $fa = mysql_fetch_array($qa);

 $activo = (int) ($fa[0] + 1); /// CODIGO DEL ACTIVO
 $activo = (string) str_repeat("0",10 - strlen($activo)).$activo;

 $s_insert = "insert into af_activo(CodOrganismo,
                                  CodDependencia,
                                  Activo,
                                  Descripcion,
                                  TipoActivo,
                                  EstadoConserv,
                                  CodigoBarras,
                                  CodigoInterno,
                                  TipoSeguro,
                                  TipoVehiculo,
                                  Categoria,
                                  Clasificacion,
                                  ClasificacionPublic20,
                                  Ubicacion,
                                  Tipo Mejora,
                                  ActivoConsolidado,
                                  EmpleadoUsuario,
                                  EmpleadoResponsable,
                                  CentroCosto,
                                  Marca,
                                  Modelo,
                                  NumeroSerie,
                                  NumeroSerieMotor,
                                  NumeroPlaca,
                                  MarcaMotor,
                                  NumeroAsiento,
                                  Material,
                                  Dimensiones,
                                  NumerodeParte,
                                  Color,
                                  FabricacionPais,
                                  FabricacionAno,
                                  PolizaSeguro,
                                  NumeroUnidades,
                                  CodigoCatastro,
                                  AreaFisicaCatastro,
                                  MontoCatastro,
                                  GenerarVoucherIngresoFlag,
                                  CodProveedor,
                                  FacturaTipoDocumento,
                                  FacturaNumeroDocumento,
                                  FacturaFecha,
                                  NumeroOrden,
                                  NumeroOrdenFecha,
                                  NumeroGuia,
                                  NumeroGuiaFecha,
                                  NumeroDocAlmacen,
                                  DocAlmacenFecha,
                                  InventarioFisicoFecha,
                                  InventarioFisicoComentario,
                                  FechaIngreso,
                                  PeriodoIngreso,
                                  PeriodoInicioDepreciacion,
                                  PeriodoInicioRevaluacion,
                                  PeriodoBaja,
                                  VoucherBaja,
                                  MontoLocal,
                                  MontoReferencia,
                                  MontoMercado,
                                  VoucherIngreso,
                                  Estado,
                                  UltimoUsuario,
                                  UltimaFechaModif)
                           values
                                 ()";
}

?>
