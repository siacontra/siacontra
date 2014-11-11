--	OBTENER E INSERTAR AL SALDO DE LOS VOUCHERS
INSERT INTO ac_voucherbalance (
	CodOrganismo,
	Periodo,
	CodCuenta,
	SaldoBalance
) 
SELECT 
    '0001' AS CodOrganismo,
    Periodo,
    CodCuenta,
    SUM(MontoVoucher)
FROM ac_voucherdet
WHERE Estado = 'MA'
GROUP BY Periodo, CodCuenta
ORDER BY Periodo, CodCuenta;

--	CONSULTO SALDO BALANCE
SELECT
    vb.CodCuenta AS 'Cuenta',
    pc.Descripcion,
    vb.SaldoBalance AS 'Balance'    
FROM
    ac_voucherbalance vb
    INNER JOIN ac_mastplancuenta pc ON (vb.CodCuenta = pc.CodCuenta)
WHERE vb.Periodo = '2012-02'
ORDER BY vb.CodCuenta

--	OBTENER REGISTRO DE COMPRAS
--	TXT
SELECT
    REPLACE(o.DocFiscal, "-", "") AS 'Rif del Organismo',
    REPLACE(r.PeriodoFiscal, "-", "") AS 'Periodo Fiscal',
    r.FechaComprobante AS 'Fecha del Comprobante',
    'C' AS 'Campo 1',
    '01' AS 'Campo 2',
    REPLACE(p.DocFiscal, "-", "") AS 'Rif del Proveedor',
    r.NroDocumento AS 'Numero de Control',
    r.NroControl AS 'Numero de Factura',
    r.MontoFactura AS 'Monto de la Factura',
    r.MontoAfecto AS 'Monto Imponible',
    ABS(r.MontoRetenido) AS 'Monto Retenido',
    '0' AS 'Campo 3',
    CONCAT(SUBSTRING(PeriodoFiscal, 1, 4), SUBSTRING(PeriodoFiscal, 6, 2), NroComprobante) AS 'Comprobante',
    '0.00' AS 'Campo 4',
    '12.00' AS '% IVA',
    '0' AS 'Campo 6'
FROM
    ap_retenciones r
    INNER JOIN mastorganismos o ON (r.CodOrganismo = o.CodOrganismo)
    INNER JOIN mastpersonas p ON (r.CodProveedor = p.CodPersona)
    INNER JOIN mastimpuestos i ON (r.CodImpuesto = i.CodImpuesto)
WHERE
    i.TipoComprobante = 'IVA' AND
	r.Estado = 'PA' AND
    r.FechaComprobante >= '2012-03-01' AND
    r.FechaComprobante <= '2012-03-15'
ORDER BY FechaComprobante
--	EXCEL
SELECT
    CONCAT(SUBSTRING(PeriodoFiscal, 1, 4), SUBSTRING(PeriodoFiscal, 6, 2), NroComprobante) AS 'Comprobante',
    p.NomCompleto AS 'Proveedor',
    r.Anio AS 'Año',
    r.PeriodoFiscal AS 'Periodo Fiscal',
    r.FechaComprobante AS 'Fecha Comprobante',
    r.NroDocumento AS 'Numero Control',
    r.NroControl AS 'Numero Factura',
    r.FechaFactura AS 'Fecha Factura',
    r.MontoAfecto AS 'Monto Imponible',
    r.MontoImpuesto AS 'Monto Impuesto',
    r.MontoFactura AS 'Monto Factura',
    '75' AS 'Porcentaje',
    ABS(r.MontoRetenido) AS 'Monto Retenido'
FROM
    ap_retenciones r
    INNER JOIN mastorganismos o ON (r.CodOrganismo = o.CodOrganismo)
    INNER JOIN mastpersonas p ON (r.CodProveedor = p.CodPersona)
    INNER JOIN mastimpuestos i ON (r.CodImpuesto = i.CodImpuesto)
WHERE
    i.TipoComprobante = 'IVA' AND
	r.Estado = 'PA' AND
    r.FechaComprobante >= '2012-03-01' AND
    r.FechaComprobante <= '2012-03-15'
ORDER BY FechaComprobante